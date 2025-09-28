<?php
/**
 * Service Edit/Add for FSMC Admin System
 * Created: 2025-01-22
 */

require_once 'config/config.php';
require_once 'includes/image_upload.php';

// Helper function to safely process array fields
function processArrayField($postData, $fieldName) {
    if (!isset($postData[$fieldName])) {
        return [];
    }

    $value = $postData[$fieldName];

    if (is_array($value)) {
        return array_filter(array_map('sanitize', $value));
    } elseif (is_string($value) && !empty($value)) {
        return array_filter(array_map('sanitize', explode(',', $value)));
    }

    return [];
}

// Helper function to process enhanced features
function processEnhancedFeatures($postData) {
    $features = [];
    if (isset($postData['feature_title']) && is_array($postData['feature_title'])) {
        foreach ($postData['feature_title'] as $index => $title) {
            if (!empty(trim($title))) {
                $features[] = [
                    'title' => sanitize($title),
                    'description' => sanitize($postData['feature_description'][$index] ?? ''),
                    'icon' => sanitize($postData['feature_icon'][$index] ?? 'fas fa-check-circle')
                ];
            }
        }
    }
    return $features;
}

// Helper function to process process steps
function processProcessSteps($postData) {
    $steps = [];
    if (isset($postData['process_step_title']) && is_array($postData['process_step_title'])) {
        foreach ($postData['process_step_title'] as $index => $title) {
            if (!empty(trim($title))) {
                $steps[] = [
                    'step' => $index + 1,
                    'title' => sanitize($title),
                    'description' => sanitize($postData['process_step_description'][$index] ?? ''),
                    'icon' => sanitize($postData['process_step_icon'][$index] ?? 'fas fa-check-circle')
                ];
            }
        }
    }
    return $steps;
}

// Helper function to process benefits
function processBenefits($postData) {
    $benefits = [];
    if (isset($postData['benefit_title']) && is_array($postData['benefit_title'])) {
        foreach ($postData['benefit_title'] as $index => $title) {
            if (!empty(trim($title))) {
                $benefits[] = [
                    'title' => sanitize($title),
                    'description' => sanitize($postData['benefit_description'][$index] ?? ''),
                    'icon' => sanitize($postData['benefit_icon'][$index] ?? 'fas fa-check-circle')
                ];
            }
        }
    }
    return $benefits;
}

// Helper function to process requirements
function processRequirements($postData) {
    $requirements = [];
    if (isset($postData['requirement_category']) && is_array($postData['requirement_category'])) {
        foreach ($postData['requirement_category'] as $index => $category) {
            if (!empty(trim($category))) {
                $items = [];
                if (isset($postData['requirement_items'][$index])) {
                    $itemsText = $postData['requirement_items'][$index];
                    $items = array_filter(array_map('trim', explode("\n", $itemsText)));
                }
                $requirements[] = [
                    'category' => sanitize($category),
                    'items' => $items
                ];
            }
        }
    }
    return $requirements;
}

// Helper function to process FAQs
function processFAQs($postData) {
    $faqs = [];
    if (isset($postData['faq_question']) && is_array($postData['faq_question'])) {
        foreach ($postData['faq_question'] as $index => $question) {
            if (!empty(trim($question))) {
                $faqs[] = [
                    'question' => sanitize($question),
                    'answer' => sanitize($postData['faq_answer'][$index] ?? '')
                ];
            }
        }
    }
    return $faqs;
}

$serviceId = intval($_GET['id'] ?? 0);
$isEdit = $serviceId > 0;

// Set page variables
$pageTitle = $isEdit ? 'Edit Service' : 'Add New Service';
$pageIcon = 'fas fa-concierge-bell';
$pageDescription = $isEdit ? 'Update service information' : 'Create a new service offering';
$breadcrumbs = [
    ['title' => 'Services', 'url' => 'services.php'],
    ['title' => $isEdit ? 'Edit Service' : 'Add Service']
];

// Get service data if editing
$service = null;
if ($isEdit) {
    $service = dbGetRow("SELECT * FROM services WHERE id = ?", [$serviceId]);
    if (!$service) {
        setErrorMessage('Service not found.');
        redirect('services.php');
    }
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verifyCSRFToken($_POST['csrf_token'] ?? '')) {
        setErrorMessage('Invalid security token.');
    } else {
        // Handle image upload
        $imageUploadResult = null;
        if (isset($_FILES['image']) && $_FILES['image']['error'] !== UPLOAD_ERR_NO_FILE) {
            $imageUploadResult = handleImageUpload('image', 'images/services');
            if (!$imageUploadResult['success']) {
                $errors[] = 'Image upload failed: ' . $imageUploadResult['error'];
            }
        }

        $data = [
            'title' => sanitize($_POST['title'] ?? ''),
            'slug' => sanitize($_POST['slug'] ?? ''),
            'description' => sanitize($_POST['description'] ?? ''),
            'short_description' => sanitize($_POST['short_description'] ?? ''),
            'icon' => sanitize($_POST['icon'] ?? ''),
            'languages' => json_encode(processArrayField($_POST, 'languages')),
            'price' => !empty($_POST['price']) ? floatval($_POST['price']) : null,
            'duration' => sanitize($_POST['duration'] ?? ''),
            'features' => json_encode(processEnhancedFeatures($_POST)),
            'process_steps' => json_encode(processProcessSteps($_POST)),
            'benefits' => json_encode(processBenefits($_POST)),
            'requirements' => json_encode(processRequirements($_POST)),
            'faqs' => json_encode(processFAQs($_POST)),
            'image' => $imageUploadResult && $imageUploadResult['success'] ? $imageUploadResult['file_path'] : (sanitize($_POST['current_image'] ?? '') ?: ''),
            'status' => sanitize($_POST['status'] ?? 'active'),
            'sort_order' => intval($_POST['sort_order'] ?? 0),
            'meta_title' => sanitize($_POST['meta_title'] ?? ''),
            'meta_description' => sanitize($_POST['meta_description'] ?? '')
        ];
        
        // Validation
        $errors = [];
        
        if (empty($data['title'])) {
            $errors[] = 'Title is required.';
        }
        
        if (empty($data['slug'])) {
            $data['slug'] = generateSlug($data['title']);
        }
        
        if (empty($data['description'])) {
            $errors[] = 'Description is required.';
        }
        
        // Check for duplicate slug
        $slugCheck = dbGetRow(
            "SELECT id FROM services WHERE slug = ? AND id != ?", 
            [$data['slug'], $serviceId]
        );
        if ($slugCheck) {
            $errors[] = 'Slug already exists. Please choose a different one.';
        }
        
        if (empty($errors)) {
            try {
                if ($isEdit) {
                    // Update service
                    $sql = "UPDATE services SET 
                            title = ?, slug = ?, description = ?, short_description = ?, 
                            icon = ?, languages = ?, price = ?, duration = ?, features = ?, 
                            process_steps = ?, benefits = ?, requirements = ?, faqs = ?,
                            image = ?, status = ?, sort_order = ?, meta_title = ?, meta_description = ?, 
                            updated_at = NOW() 
                            WHERE id = ?";
                    
                    $params = array_values($data);
                    $params[] = $serviceId;
                    
                    if (dbExecute($sql, $params)) {
                        logActivity('Service Updated', 'services', $serviceId, $service, $data);
                        setSuccessMessage('Service updated successfully.');
                        redirect('services.php');
                    } else {
                        $errors[] = 'Failed to update service.';
                    }
                } else {
                    // Create new service
                    $sql = "INSERT INTO services 
                            (title, slug, description, short_description, icon, languages, 
                             price, duration, features, process_steps, benefits, requirements, faqs,
                             image, status, sort_order, meta_title, meta_description) 
                            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
                    
                    $newId = dbInsert($sql, array_values($data));
                    if ($newId) {
                        logActivity('Service Created', 'services', $newId, null, $data);
                        setSuccessMessage('Service created successfully.');
                        redirect('services.php');
                    } else {
                        $errors[] = 'Failed to create service.';
                    }
                }
            } catch (Exception $e) {
                error_log("Service save error: " . $e->getMessage());
                $errors[] = 'An error occurred while saving the service.';
            }
        }
        
        if (!empty($errors)) {
            setErrorMessage(implode('<br>', $errors));
        }
    }
}

// Prepare form data
$formData = $service ?: [
    'title' => '',
    'slug' => '',
    'description' => '',
    'short_description' => '',
    'icon' => '',
    'languages' => '[]',
    'price' => '',
    'duration' => '',
    'features' => '[]',
    'process_steps' => '[]',
    'benefits' => '[]',
    'requirements' => '[]',
    'faqs' => '[]',
    'status' => 'active',
    'sort_order' => 0,
    'meta_title' => '',
    'meta_description' => ''
];

// Decode JSON fields
$languages = json_decode($formData['languages'] ?? '[]', true) ?: [];
$featuresData = json_decode($formData['features'] ?? '[]', true) ?: [];

// Handle backward compatibility for features
$features = [];
if (!empty($featuresData)) {
    // Check if it's the new format (array of objects) or old format (array of strings)
    if (isset($featuresData[0]) && is_array($featuresData[0]) && isset($featuresData[0]['title'])) {
        // New enhanced format
        $features = $featuresData;
    } else {
        // Old simple format - convert to new format
        foreach ($featuresData as $feature) {
            if (is_string($feature)) {
                $features[] = [
                    'title' => $feature,
                    'description' => '',
                    'icon' => 'fas fa-check-circle'
                ];
            }
        }
    }
}

$processSteps = json_decode($formData['process_steps'] ?? '[]', true) ?: [];
$benefits = json_decode($formData['benefits'] ?? '[]', true) ?: [];
$requirements = json_decode($formData['requirements'] ?? '[]', true) ?: [];
$faqs = json_decode($formData['faqs'] ?? '[]', true) ?: [];

include 'includes/header.php';
?>

<form method="POST" enctype="multipart/form-data" data-validate>
    <input type="hidden" name="csrf_token" value="<?php echo generateCSRFToken(); ?>">
    
    <div class="row">
        <!-- Main Content -->
        <div class="col-8">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Service Information</h3>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <label for="title" class="form-label">Title *</label>
                        <input type="text" id="title" name="title" class="form-control" 
                               value="<?php echo htmlspecialchars($formData['title']); ?>" 
                               required maxlength="200">
                    </div>
                    
                    <div class="form-group">
                        <label for="slug" class="form-label">Slug</label>
                        <input type="text" id="slug" name="slug" class="form-control" 
                               value="<?php echo htmlspecialchars($formData['slug']); ?>" 
                               maxlength="200">
                        <div class="form-text">URL-friendly version of the title. Leave empty to auto-generate.</div>
                    </div>
                    
                    <div class="form-group">
                        <label for="short_description" class="form-label">Short Description</label>
                        <textarea id="short_description" name="short_description" class="form-control" 
                                  rows="3" maxlength="500"><?php echo htmlspecialchars($formData['short_description']); ?></textarea>
                        <div class="form-text">Brief description for listings and previews.</div>
                    </div>
                    
                    <div class="form-group">
                        <label for="description" class="form-label">Full Description *</label>
                        <textarea id="description" name="description" class="form-control" 
                                  rows="8" required><?php echo htmlspecialchars($formData['description']); ?></textarea>
                    </div>
                    
                    <div class="row">
                        <div class="col-6">
                            <div class="form-group">
                                <label for="price" class="form-label">Price (RWF)</label>
                                <input type="number" id="price" name="price" class="form-control" 
                                       value="<?php echo htmlspecialchars($formData['price']); ?>" 
                                       min="0" step="0.01">
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label for="duration" class="form-label">Duration</label>
                                <input type="text" id="duration" name="duration" class="form-control" 
                                       value="<?php echo htmlspecialchars($formData['duration']); ?>" 
                                       placeholder="e.g., 2-3 days, 1 week">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Languages -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Languages</h3>
                </div>
                <div class="card-body">
                    <div id="languages-container">
                        <?php foreach ($languages as $index => $language): ?>
                        <div class="form-group d-flex align-items-center language-item">
                            <input type="text" name="languages[]" class="form-control" 
                                   value="<?php echo htmlspecialchars($language); ?>" 
                                   placeholder="Language (e.g., English, Kinyarwanda)">
                            <button type="button" class="btn btn-outline-danger btn-sm ms-2 remove-language">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                        <?php endforeach; ?>
                        
                        <?php if (empty($languages)): ?>
                        <div class="form-group d-flex align-items-center language-item">
                            <input type="text" name="languages[]" class="form-control" 
                                   placeholder="Language (e.g., English, Kinyarwanda)">
                            <button type="button" class="btn btn-outline-danger btn-sm ms-2 remove-language">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                        <?php endif; ?>
                    </div>
                    <button type="button" id="add-language" class="btn btn-outline-primary btn-sm">
                        <i class="fas fa-plus"></i> Add Language
                    </button>
                </div>
            </div>
            
            <!-- Features -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Service Features</h3>
                </div>
                <div class="card-body">
                    <div id="features-container">
                        <?php foreach ($features as $index => $feature): ?>
                        <div class="feature-item border rounded p-3 mb-3">
                            <div class="row">
                                <div class="col-6">
                                    <div class="form-group">
                                        <label class="form-label">Feature Title</label>
                                        <input type="text" name="feature_title[]" class="form-control" 
                                               value="<?php echo htmlspecialchars($feature['title'] ?? ''); ?>" 
                                               placeholder="e.g., Professional Survey">
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group">
                                        <label class="form-label">Icon Class</label>
                                        <input type="text" name="feature_icon[]" class="form-control" 
                                               value="<?php echo htmlspecialchars($feature['icon'] ?? 'fas fa-check-circle'); ?>" 
                                               placeholder="e.g., fas fa-map-marked-alt">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Feature Description</label>
                                <textarea name="feature_description[]" class="form-control" rows="2" 
                                          placeholder="Describe this feature and its benefits"><?php echo htmlspecialchars($feature['description'] ?? ''); ?></textarea>
                            </div>
                            <button type="button" class="btn btn-outline-danger btn-sm remove-feature">
                                <i class="fas fa-times"></i> Remove Feature
                            </button>
                        </div>
                        <?php endforeach; ?>
                        
                        <?php if (empty($features)): ?>
                        <div class="feature-item border rounded p-3 mb-3">
                            <div class="row">
                                <div class="col-6">
                                    <div class="form-group">
                                        <label class="form-label">Feature Title</label>
                                        <input type="text" name="feature_title[]" class="form-control" 
                                               placeholder="e.g., Professional Survey">
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group">
                                        <label class="form-label">Icon Class</label>
                                        <input type="text" name="feature_icon[]" class="form-control" 
                                               value="fas fa-check-circle" placeholder="e.g., fas fa-map-marked-alt">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Feature Description</label>
                                <textarea name="feature_description[]" class="form-control" rows="2" 
                                          placeholder="Describe this feature and its benefits"></textarea>
                            </div>
                            <button type="button" class="btn btn-outline-danger btn-sm remove-feature">
                                <i class="fas fa-times"></i> Remove Feature
                            </button>
                        </div>
                        <?php endif; ?>
                    </div>
                    <button type="button" id="add-feature" class="btn btn-outline-primary btn-sm">
                        <i class="fas fa-plus"></i> Add Feature
                    </button>
                </div>
            </div>
            
            <!-- Process Steps -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Process Steps</h3>
                </div>
                <div class="card-body">
                    <div id="process-steps-container">
                        <?php foreach ($processSteps as $index => $step): ?>
                        <div class="process-step-item border rounded p-3 mb-3">
                            <div class="row">
                                <div class="col-6">
                                    <div class="form-group">
                                        <label class="form-label">Step Title</label>
                                        <input type="text" name="process_step_title[]" class="form-control" 
                                               value="<?php echo htmlspecialchars($step['title'] ?? ''); ?>" 
                                               placeholder="e.g., Initial Consultation">
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group">
                                        <label class="form-label">Icon Class</label>
                                        <input type="text" name="process_step_icon[]" class="form-control" 
                                               value="<?php echo htmlspecialchars($step['icon'] ?? 'fas fa-check-circle'); ?>" 
                                               placeholder="e.g., fas fa-comments">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Step Description</label>
                                <textarea name="process_step_description[]" class="form-control" rows="2" 
                                          placeholder="Describe what happens in this step"><?php echo htmlspecialchars($step['description'] ?? ''); ?></textarea>
                            </div>
                            <button type="button" class="btn btn-outline-danger btn-sm remove-process-step">
                                <i class="fas fa-times"></i> Remove Step
                            </button>
                        </div>
                        <?php endforeach; ?>
                        
                        <?php if (empty($processSteps)): ?>
                        <div class="process-step-item border rounded p-3 mb-3">
                            <div class="row">
                                <div class="col-6">
                                    <div class="form-group">
                                        <label class="form-label">Step Title</label>
                                        <input type="text" name="process_step_title[]" class="form-control" 
                                               placeholder="e.g., Initial Consultation">
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group">
                                        <label class="form-label">Icon Class</label>
                                        <input type="text" name="process_step_icon[]" class="form-control" 
                                               value="fas fa-check-circle" placeholder="e.g., fas fa-comments">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Step Description</label>
                                <textarea name="process_step_description[]" class="form-control" rows="2" 
                                          placeholder="Describe what happens in this step"></textarea>
                            </div>
                            <button type="button" class="btn btn-outline-danger btn-sm remove-process-step">
                                <i class="fas fa-times"></i> Remove Step
                            </button>
                        </div>
                        <?php endif; ?>
                    </div>
                    <button type="button" id="add-process-step" class="btn btn-outline-primary btn-sm">
                        <i class="fas fa-plus"></i> Add Process Step
                    </button>
                </div>
            </div>
            
            <!-- Benefits -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Service Benefits</h3>
                </div>
                <div class="card-body">
                    <div id="benefits-container">
                        <?php foreach ($benefits as $index => $benefit): ?>
                        <div class="benefit-item border rounded p-3 mb-3">
                            <div class="row">
                                <div class="col-6">
                                    <div class="form-group">
                                        <label class="form-label">Benefit Title</label>
                                        <input type="text" name="benefit_title[]" class="form-control" 
                                               value="<?php echo htmlspecialchars($benefit['title'] ?? ''); ?>" 
                                               placeholder="e.g., Legal Protection">
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group">
                                        <label class="form-label">Icon Class</label>
                                        <input type="text" name="benefit_icon[]" class="form-control" 
                                               value="<?php echo htmlspecialchars($benefit['icon'] ?? 'fas fa-check-circle'); ?>" 
                                               placeholder="e.g., fas fa-shield-alt">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Benefit Description</label>
                                <textarea name="benefit_description[]" class="form-control" rows="2" 
                                          placeholder="Explain the benefit to customers"><?php echo htmlspecialchars($benefit['description'] ?? ''); ?></textarea>
                            </div>
                            <button type="button" class="btn btn-outline-danger btn-sm remove-benefit">
                                <i class="fas fa-times"></i> Remove Benefit
                            </button>
                        </div>
                        <?php endforeach; ?>
                        
                        <?php if (empty($benefits)): ?>
                        <div class="benefit-item border rounded p-3 mb-3">
                            <div class="row">
                                <div class="col-6">
                                    <div class="form-group">
                                        <label class="form-label">Benefit Title</label>
                                        <input type="text" name="benefit_title[]" class="form-control" 
                                               placeholder="e.g., Legal Protection">
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group">
                                        <label class="form-label">Icon Class</label>
                                        <input type="text" name="benefit_icon[]" class="form-control" 
                                               value="fas fa-check-circle" placeholder="e.g., fas fa-shield-alt">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Benefit Description</label>
                                <textarea name="benefit_description[]" class="form-control" rows="2" 
                                          placeholder="Explain the benefit to customers"></textarea>
                            </div>
                            <button type="button" class="btn btn-outline-danger btn-sm remove-benefit">
                                <i class="fas fa-times"></i> Remove Benefit
                            </button>
                        </div>
                        <?php endif; ?>
                    </div>
                    <button type="button" id="add-benefit" class="btn btn-outline-primary btn-sm">
                        <i class="fas fa-plus"></i> Add Benefit
                    </button>
                </div>
            </div>
            
            <!-- Requirements -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Service Requirements</h3>
                </div>
                <div class="card-body">
                    <div id="requirements-container">
                        <?php foreach ($requirements as $index => $requirement): ?>
                        <div class="requirement-item border rounded p-3 mb-3">
                            <div class="form-group">
                                <label class="form-label">Category</label>
                                <input type="text" name="requirement_category[]" class="form-control" 
                                       value="<?php echo htmlspecialchars($requirement['category'] ?? ''); ?>" 
                                       placeholder="e.g., Required Documents">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Items (one per line)</label>
                                <textarea name="requirement_items[]" class="form-control" rows="4" 
                                          placeholder="List each requirement item on a new line"><?php echo htmlspecialchars(implode("\n", $requirement['items'] ?? [])); ?></textarea>
                            </div>
                            <button type="button" class="btn btn-outline-danger btn-sm remove-requirement">
                                <i class="fas fa-times"></i> Remove Category
                            </button>
                        </div>
                        <?php endforeach; ?>
                        
                        <?php if (empty($requirements)): ?>
                        <div class="requirement-item border rounded p-3 mb-3">
                            <div class="form-group">
                                <label class="form-label">Category</label>
                                <input type="text" name="requirement_category[]" class="form-control" 
                                       placeholder="e.g., Required Documents">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Items (one per line)</label>
                                <textarea name="requirement_items[]" class="form-control" rows="4" 
                                          placeholder="List each requirement item on a new line"></textarea>
                            </div>
                            <button type="button" class="btn btn-outline-danger btn-sm remove-requirement">
                                <i class="fas fa-times"></i> Remove Category
                            </button>
                        </div>
                        <?php endif; ?>
                    </div>
                    <button type="button" id="add-requirement" class="btn btn-outline-primary btn-sm">
                        <i class="fas fa-plus"></i> Add Requirement Category
                    </button>
                </div>
            </div>
            
            <!-- FAQs -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Frequently Asked Questions</h3>
                </div>
                <div class="card-body">
                    <div id="faqs-container">
                        <?php foreach ($faqs as $index => $faq): ?>
                        <div class="faq-item border rounded p-3 mb-3">
                            <div class="form-group">
                                <label class="form-label">Question</label>
                                <input type="text" name="faq_question[]" class="form-control" 
                                       value="<?php echo htmlspecialchars($faq['question'] ?? ''); ?>" 
                                       placeholder="Enter the frequently asked question">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Answer</label>
                                <textarea name="faq_answer[]" class="form-control" rows="3" 
                                          placeholder="Provide a detailed answer"><?php echo htmlspecialchars($faq['answer'] ?? ''); ?></textarea>
                            </div>
                            <button type="button" class="btn btn-outline-danger btn-sm remove-faq">
                                <i class="fas fa-times"></i> Remove FAQ
                            </button>
                        </div>
                        <?php endforeach; ?>
                        
                        <?php if (empty($faqs)): ?>
                        <div class="faq-item border rounded p-3 mb-3">
                            <div class="form-group">
                                <label class="form-label">Question</label>
                                <input type="text" name="faq_question[]" class="form-control" 
                                       placeholder="Enter the frequently asked question">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Answer</label>
                                <textarea name="faq_answer[]" class="form-control" rows="3" 
                                          placeholder="Provide a detailed answer"></textarea>
                            </div>
                            <button type="button" class="btn btn-outline-danger btn-sm remove-faq">
                                <i class="fas fa-times"></i> Remove FAQ
                            </button>
                        </div>
                        <?php endif; ?>
                    </div>
                    <button type="button" id="add-faq" class="btn btn-outline-primary btn-sm">
                        <i class="fas fa-plus"></i> Add FAQ
                    </button>
                </div>
            </div>
        </div>
        
        <!-- Sidebar -->
        <div class="col-4">
            <!-- Publish -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Publish</h3>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <label for="status" class="form-label">Status</label>
                        <select id="status" name="status" class="form-control">
                            <option value="active" <?php echo $formData['status'] === 'active' ? 'selected' : ''; ?>>Active</option>
                            <option value="inactive" <?php echo $formData['status'] === 'inactive' ? 'selected' : ''; ?>>Inactive</option>
                            <option value="draft" <?php echo $formData['status'] === 'draft' ? 'selected' : ''; ?>>Draft</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="sort_order" class="form-label">Sort Order</label>
                        <input type="number" id="sort_order" name="sort_order" class="form-control" 
                               value="<?php echo htmlspecialchars($formData['sort_order']); ?>" 
                               min="0">
                        <div class="form-text">Lower numbers appear first.</div>
                    </div>
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> <?php echo $isEdit ? 'Update' : 'Create'; ?> Service
                    </button>
                    <a href="services.php" class="btn btn-secondary">
                        <i class="fas fa-times"></i> Cancel
                    </a>
                </div>
            </div>
            
            <!-- Service Icon -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Service Icon</h3>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <label for="icon" class="form-label">Font Awesome Icon Class</label>
                        <input type="text" id="icon" name="icon" class="form-control" 
                               value="<?php echo htmlspecialchars($formData['icon']); ?>" 
                               placeholder="e.g., fas fa-concierge-bell">
                        <div class="form-text">
                            Use Font Awesome icon classes. 
                            <a href="https://fontawesome.com/icons" target="_blank">Browse icons</a>
                        </div>
                    </div>
                    
                    <?php if ($formData['icon']): ?>
                    <div class="text-center">
                        <i class="<?php echo htmlspecialchars($formData['icon']); ?> fa-3x text-primary"></i>
                        <p class="mt-2 text-muted">Current Icon</p>
                    </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Service Image -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Service Image</h3>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <label for="image" class="form-label">Featured Image</label>

                        <?php if (!empty($formData['image'])): ?>
                            <div class="current-image mb-3">
                                <img src="<?php echo getFileUrl($formData['image']); ?>"
                                     alt="Current image"
                                     style="max-width: 200px; max-height: 150px; border: 1px solid #ddd; border-radius: 4px;">
                                <div class="mt-2">
                                    <small class="text-muted">Current image</small>
                                    <input type="hidden" name="current_image" value="<?php echo htmlspecialchars($formData['image']); ?>">
                                </div>
                            </div>
                        <?php endif; ?>

                        <input type="file" id="image" name="image" class="form-control"
                               accept="image/*" onchange="previewImage(this)">
                        <div class="form-text">
                            Upload a new image (JPG, PNG, GIF, WebP). Maximum size: 5MB.
                            <?php if (!empty($formData['image'])): ?>
                                Leave empty to keep current image.
                            <?php endif; ?>
                        </div>

                        <div id="image-preview" class="mt-3" style="display: none;">
                            <img id="preview-img" src="" alt="Preview"
                                 style="max-width: 200px; max-height: 150px; border: 1px solid #ddd; border-radius: 4px;">
                            <div class="mt-2">
                                <small class="text-muted">New image preview</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- SEO -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">SEO Settings</h3>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <label for="meta_title" class="form-label">Meta Title</label>
                        <input type="text" id="meta_title" name="meta_title" class="form-control" 
                               value="<?php echo htmlspecialchars($formData['meta_title']); ?>" 
                               maxlength="200">
                    </div>
                    
                    <div class="form-group">
                        <label for="meta_description" class="form-label">Meta Description</label>
                        <textarea id="meta_description" name="meta_description" class="form-control" 
                                  rows="3" maxlength="300"><?php echo htmlspecialchars($formData['meta_description']); ?></textarea>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto-generate slug from title
    const titleInput = document.getElementById('title');
    const slugInput = document.getElementById('slug');
    
    titleInput.addEventListener('input', function() {
        if (!slugInput.value || slugInput.dataset.autoGenerated) {
            const slug = this.value.toLowerCase()
                .replace(/[^a-z0-9\s-]/g, '')
                .replace(/\s+/g, '-')
                .replace(/-+/g, '-')
                .trim('-');
            slugInput.value = slug;
            slugInput.dataset.autoGenerated = 'true';
        }
    });
    
    slugInput.addEventListener('input', function() {
        this.dataset.autoGenerated = 'false';
    });
    
    // Add/remove languages
    document.getElementById('add-language').addEventListener('click', function() {
        const container = document.getElementById('languages-container');
        const newItem = document.createElement('div');
        newItem.className = 'form-group d-flex align-items-center language-item';
        newItem.innerHTML = `
            <input type="text" name="languages[]" class="form-control" 
                   placeholder="Language (e.g., English, Kinyarwanda)">
            <button type="button" class="btn btn-outline-danger btn-sm ms-2 remove-language">
                <i class="fas fa-times"></i>
            </button>
        `;
        container.appendChild(newItem);
    });
    
    document.addEventListener('click', function(e) {
        if (e.target.closest('.remove-language')) {
            const item = e.target.closest('.language-item');
            if (document.querySelectorAll('.language-item').length > 1) {
                item.remove();
            }
        }
    });
    
    // Add/remove features
    document.getElementById('add-feature').addEventListener('click', function() {
        const container = document.getElementById('features-container');
        const newItem = document.createElement('div');
        newItem.className = 'feature-item border rounded p-3 mb-3';
        newItem.innerHTML = `
            <div class="row">
                <div class="col-6">
                    <div class="form-group">
                        <label class="form-label">Feature Title</label>
                        <input type="text" name="feature_title[]" class="form-control" 
                               placeholder="e.g., Professional Survey">
                    </div>
                </div>
                <div class="col-6">
                    <div class="form-group">
                        <label class="form-label">Icon Class</label>
                        <input type="text" name="feature_icon[]" class="form-control" 
                               value="fas fa-check-circle" placeholder="e.g., fas fa-map-marked-alt">
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label class="form-label">Feature Description</label>
                <textarea name="feature_description[]" class="form-control" rows="2" 
                          placeholder="Describe this feature and its benefits"></textarea>
            </div>
            <button type="button" class="btn btn-outline-danger btn-sm remove-feature">
                <i class="fas fa-times"></i> Remove Feature
            </button>
        `;
        container.appendChild(newItem);
    });
    
    document.addEventListener('click', function(e) {
        if (e.target.closest('.remove-feature')) {
            const item = e.target.closest('.feature-item');
            if (document.querySelectorAll('.feature-item').length > 1) {
                item.remove();
            }
        }
    });
    
    // Icon preview
    const iconInput = document.getElementById('icon');
    iconInput.addEventListener('input', function() {
        // Update icon preview if exists
        const preview = document.querySelector('.card-body i[class*="fa-3x"]');
        if (preview && this.value) {
            preview.className = this.value + ' fa-3x text-primary';
        }
    });
    
    // Process Steps functionality
    document.getElementById('add-process-step').addEventListener('click', function() {
        const container = document.getElementById('process-steps-container');
        const newItem = document.createElement('div');
        newItem.className = 'process-step-item border rounded p-3 mb-3';
        newItem.innerHTML = `
            <div class="row">
                <div class="col-6">
                    <div class="form-group">
                        <label class="form-label">Step Title</label>
                        <input type="text" name="process_step_title[]" class="form-control" 
                               placeholder="e.g., Initial Consultation">
                    </div>
                </div>
                <div class="col-6">
                    <div class="form-group">
                        <label class="form-label">Icon Class</label>
                        <input type="text" name="process_step_icon[]" class="form-control" 
                               value="fas fa-check-circle" placeholder="e.g., fas fa-comments">
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label class="form-label">Step Description</label>
                <textarea name="process_step_description[]" class="form-control" rows="2" 
                          placeholder="Describe what happens in this step"></textarea>
            </div>
            <button type="button" class="btn btn-outline-danger btn-sm remove-process-step">
                <i class="fas fa-times"></i> Remove Step
            </button>
        `;
        container.appendChild(newItem);
    });
    
    document.addEventListener('click', function(e) {
        if (e.target.closest('.remove-process-step')) {
            const item = e.target.closest('.process-step-item');
            if (document.querySelectorAll('.process-step-item').length > 1) {
                item.remove();
            }
        }
    });
    
    // Benefits functionality
    document.getElementById('add-benefit').addEventListener('click', function() {
        const container = document.getElementById('benefits-container');
        const newItem = document.createElement('div');
        newItem.className = 'benefit-item border rounded p-3 mb-3';
        newItem.innerHTML = `
            <div class="row">
                <div class="col-6">
                    <div class="form-group">
                        <label class="form-label">Benefit Title</label>
                        <input type="text" name="benefit_title[]" class="form-control" 
                               placeholder="e.g., Legal Protection">
                    </div>
                </div>
                <div class="col-6">
                    <div class="form-group">
                        <label class="form-label">Icon Class</label>
                        <input type="text" name="benefit_icon[]" class="form-control" 
                               value="fas fa-check-circle" placeholder="e.g., fas fa-shield-alt">
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label class="form-label">Benefit Description</label>
                <textarea name="benefit_description[]" class="form-control" rows="2" 
                          placeholder="Explain the benefit to customers"></textarea>
            </div>
            <button type="button" class="btn btn-outline-danger btn-sm remove-benefit">
                <i class="fas fa-times"></i> Remove Benefit
            </button>
        `;
        container.appendChild(newItem);
    });
    
    document.addEventListener('click', function(e) {
        if (e.target.closest('.remove-benefit')) {
            const item = e.target.closest('.benefit-item');
            if (document.querySelectorAll('.benefit-item').length > 1) {
                item.remove();
            }
        }
    });
    
    // Requirements functionality
    document.getElementById('add-requirement').addEventListener('click', function() {
        const container = document.getElementById('requirements-container');
        const newItem = document.createElement('div');
        newItem.className = 'requirement-item border rounded p-3 mb-3';
        newItem.innerHTML = `
            <div class="form-group">
                <label class="form-label">Category</label>
                <input type="text" name="requirement_category[]" class="form-control" 
                       placeholder="e.g., Required Documents">
            </div>
            <div class="form-group">
                <label class="form-label">Items (one per line)</label>
                <textarea name="requirement_items[]" class="form-control" rows="4" 
                          placeholder="List each requirement item on a new line"></textarea>
            </div>
            <button type="button" class="btn btn-outline-danger btn-sm remove-requirement">
                <i class="fas fa-times"></i> Remove Category
            </button>
        `;
        container.appendChild(newItem);
    });
    
    document.addEventListener('click', function(e) {
        if (e.target.closest('.remove-requirement')) {
            const item = e.target.closest('.requirement-item');
            if (document.querySelectorAll('.requirement-item').length > 1) {
                item.remove();
            }
        }
    });
    
    // FAQs functionality
    document.getElementById('add-faq').addEventListener('click', function() {
        const container = document.getElementById('faqs-container');
        const newItem = document.createElement('div');
        newItem.className = 'faq-item border rounded p-3 mb-3';
        newItem.innerHTML = `
            <div class="form-group">
                <label class="form-label">Question</label>
                <input type="text" name="faq_question[]" class="form-control" 
                       placeholder="Enter the frequently asked question">
            </div>
            <div class="form-group">
                <label class="form-label">Answer</label>
                <textarea name="faq_answer[]" class="form-control" rows="3" 
                          placeholder="Provide a detailed answer"></textarea>
            </div>
            <button type="button" class="btn btn-outline-danger btn-sm remove-faq">
                <i class="fas fa-times"></i> Remove FAQ
            </button>
        `;
        container.appendChild(newItem);
    });
    
    document.addEventListener('click', function(e) {
        if (e.target.closest('.remove-faq')) {
            const item = e.target.closest('.faq-item');
            if (document.querySelectorAll('.faq-item').length > 1) {
                item.remove();
            }
        }
    });
});

// Image preview function
function previewImage(input) {
    const preview = document.getElementById('image-preview');
    const previewImg = document.getElementById('preview-img');

    if (input.files && input.files[0]) {
        const reader = new FileReader();

        reader.onload = function(e) {
            previewImg.src = e.target.result;
            preview.style.display = 'block';
        };

        reader.readAsDataURL(input.files[0]);
    } else {
        preview.style.display = 'none';
    }
}
</script>

<?php include 'includes/footer.php'; ?>
