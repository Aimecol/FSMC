<?php
/**
 * Service Edit/Add for FSMC Admin System
 * Created: 2025-01-22
 */

require_once 'config/config.php';

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
        $data = [
            'title' => sanitize($_POST['title'] ?? ''),
            'slug' => sanitize($_POST['slug'] ?? ''),
            'description' => sanitize($_POST['description'] ?? ''),
            'short_description' => sanitize($_POST['short_description'] ?? ''),
            'icon' => sanitize($_POST['icon'] ?? ''),
            'languages' => json_encode(array_filter(array_map('sanitize', $_POST['languages'] ?? []))),
            'price' => !empty($_POST['price']) ? floatval($_POST['price']) : null,
            'duration' => sanitize($_POST['duration'] ?? ''),
            'features' => json_encode(array_filter(array_map('sanitize', $_POST['features'] ?? []))),
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
                            status = ?, sort_order = ?, meta_title = ?, meta_description = ?, 
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
                             price, duration, features, status, sort_order, meta_title, meta_description) 
                            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
                    
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
    'status' => 'active',
    'sort_order' => 0,
    'meta_title' => '',
    'meta_description' => ''
];

// Decode JSON fields
$languages = json_decode($formData['languages'] ?? '[]', true) ?: [];
$features = json_decode($formData['features'] ?? '[]', true) ?: [];

include 'includes/header.php';
?>

<form method="POST" data-validate>
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
                    <h3 class="card-title">Features</h3>
                </div>
                <div class="card-body">
                    <div id="features-container">
                        <?php foreach ($features as $index => $feature): ?>
                        <div class="form-group d-flex align-items-center feature-item">
                            <input type="text" name="features[]" class="form-control" 
                                   value="<?php echo htmlspecialchars($feature); ?>" 
                                   placeholder="Feature description">
                            <button type="button" class="btn btn-outline-danger btn-sm ms-2 remove-feature">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                        <?php endforeach; ?>
                        
                        <?php if (empty($features)): ?>
                        <div class="form-group d-flex align-items-center feature-item">
                            <input type="text" name="features[]" class="form-control" 
                                   placeholder="Feature description">
                            <button type="button" class="btn btn-outline-danger btn-sm ms-2 remove-feature">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                        <?php endif; ?>
                    </div>
                    <button type="button" id="add-feature" class="btn btn-outline-primary btn-sm">
                        <i class="fas fa-plus"></i> Add Feature
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
        newItem.className = 'form-group d-flex align-items-center feature-item';
        newItem.innerHTML = `
            <input type="text" name="features[]" class="form-control" 
                   placeholder="Feature description">
            <button type="button" class="btn btn-outline-danger btn-sm ms-2 remove-feature">
                <i class="fas fa-times"></i>
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
});
</script>

<?php include 'includes/footer.php'; ?>
