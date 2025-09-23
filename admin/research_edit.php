<?php
/**
 * Research Project Edit/Add for FSMC Admin System
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

$researchId = intval($_GET['id'] ?? 0);
$isEdit = $researchId > 0;

// Set page variables
$pageTitle = $isEdit ? 'Edit Research Project' : 'Add New Research Project';
$pageIcon = 'fas fa-microscope';
$pageDescription = $isEdit ? 'Update research project information' : 'Create a new research project';
$breadcrumbs = [
    ['title' => 'Research', 'url' => 'research.php'],
    ['title' => $isEdit ? 'Edit Project' : 'Add Project']
];

// Get research data if editing
$research = null;
if ($isEdit) {
    $research = dbGetRow("SELECT * FROM research_projects WHERE id = ?", [$researchId]);
    if (!$research) {
        setErrorMessage('Research project not found.');
        redirect('research.php');
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
            $imageUploadResult = handleImageUpload('image', 'images/research');
            if (!$imageUploadResult['success']) {
                $errors[] = 'Image upload failed: ' . $imageUploadResult['error'];
            }
        }

        $data = [
            'title' => sanitize($_POST['title'] ?? ''),
            'slug' => sanitize($_POST['slug'] ?? ''),
            'abstract' => sanitize($_POST['abstract'] ?? ''),
            'description' => sanitize($_POST['description'] ?? ''),
            'methodology' => sanitize($_POST['methodology'] ?? ''),
            'key_findings' => json_encode([sanitize($_POST['key_findings'] ?? '')]),
            'authors' => json_encode(processArrayField($_POST, 'authors')),
            'publication_date' => !empty($_POST['publication_date']) ? $_POST['publication_date'] : null,
            'journal' => sanitize($_POST['journal'] ?? ''),
            'doi' => sanitize($_POST['doi'] ?? ''),
            'keywords' => json_encode(processArrayField($_POST, 'keywords')),
            'category' => sanitize($_POST['category'] ?? ''),
            'status' => sanitize($_POST['status'] ?? 'draft'),
            'featured' => isset($_POST['featured']) ? 1 : 0,
            'image' => $imageUploadResult && $imageUploadResult['success'] ? $imageUploadResult['file_path'] : (sanitize($_POST['current_image'] ?? '') ?: ''),
            'gallery' => json_encode(processArrayField($_POST, 'gallery')),
            'documents' => json_encode(processArrayField($_POST, 'documents')),
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
        
        if (empty($data['abstract'])) {
            $errors[] = 'Abstract is required.';
        }
        
        if (empty($data['category'])) {
            $errors[] = 'Category is required.';
        }
        
        // Check for duplicate slug
        $slugCheck = dbGetRow(
            "SELECT id FROM research_projects WHERE slug = ? AND id != ?", 
            [$data['slug'], $researchId]
        );
        if ($slugCheck) {
            $errors[] = 'Slug already exists. Please choose a different one.';
        }
        
        if (empty($errors)) {
            try {
                if ($isEdit) {
                    // Update research project
                    $sql = "UPDATE research_projects SET
                            title = ?, slug = ?, abstract = ?, description = ?, methodology = ?, key_findings = ?,
                            authors = ?, publication_date = ?, journal = ?, doi = ?, keywords = ?,
                            category = ?, status = ?, featured = ?, image = ?, gallery = ?, documents = ?,
                            meta_title = ?, meta_description = ?, updated_at = NOW()
                            WHERE id = ?";
                    
                    $params = array_values($data);
                    $params[] = $researchId;
                    
                    if (dbExecute($sql, $params)) {
                        logActivity('Research Project Updated', 'research_projects', $researchId, $research, $data);
                        setSuccessMessage('Research project updated successfully.');
                        redirect('research.php');
                    } else {
                        $errors[] = 'Failed to update research project.';
                    }
                } else {
                    // Create new research project
                    $sql = "INSERT INTO research_projects
                            (title, slug, abstract, description, methodology, key_findings, authors,
                             publication_date, journal, doi, keywords, category, status, featured,
                             image, gallery, documents, meta_title, meta_description)
                            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
                    
                    $newId = dbInsert($sql, array_values($data));
                    if ($newId) {
                        logActivity('Research Project Created', 'research_projects', $newId, null, $data);
                        setSuccessMessage('Research project created successfully.');
                        redirect('research.php');
                    } else {
                        $errors[] = 'Failed to create research project.';
                    }
                }
            } catch (Exception $e) {
                error_log("Research project save error: " . $e->getMessage());
                $errors[] = 'An error occurred while saving the research project.';
            }
        }
        
        if (!empty($errors)) {
            setErrorMessage(implode('<br>', $errors));
        }
    }
}

// Prepare form data
$formData = $research ?: [
    'title' => '',
    'slug' => '',
    'abstract' => '',
    'description' => '',
    'methodology' => '',
    'key_findings' => '[]',
    'authors' => '[]',
    'publication_date' => '',
    'journal' => '',
    'doi' => '',
    'keywords' => '[]',
    'category' => '',
    'status' => 'draft',
    'featured' => 0,
    'image' => '',
    'gallery' => '[]',
    'documents' => '[]',
    'meta_title' => '',
    'meta_description' => ''
];

// Decode JSON fields
$keywords = json_decode($formData['keywords'] ?? '[]', true) ?: [];

// Categories
$categories = [
    'surveying' => 'Land Surveying',
    'mapping' => 'Mapping & Cartography',
    'gis' => 'Geographic Information Systems',
    'remote_sensing' => 'Remote Sensing',
    'cartography' => 'Cartography',
    'geodesy' => 'Geodesy'
];

// Status options
$statuses = [
    'draft' => 'Draft',
    'under_review' => 'Under Review',
    'published' => 'Published'
];

include 'includes/header.php';
?>

<form method="POST" enctype="multipart/form-data" data-validate>
    <input type="hidden" name="csrf_token" value="<?php echo generateCSRFToken(); ?>">
    
    <div class="row">
        <!-- Main Content -->
        <div class="col-8">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Research Information</h3>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <label for="title" class="form-label">Title *</label>
                        <input type="text" id="title" name="title" class="form-control" 
                               value="<?php echo htmlspecialchars($formData['title']); ?>" 
                               required maxlength="300">
                    </div>
                    
                    <div class="form-group">
                        <label for="slug" class="form-label">Slug</label>
                        <input type="text" id="slug" name="slug" class="form-control" 
                               value="<?php echo htmlspecialchars($formData['slug']); ?>" 
                               maxlength="300">
                        <div class="form-text">URL-friendly version of the title. Leave empty to auto-generate.</div>
                    </div>
                    
                    <div class="row">
                        <div class="col-6">
                            <div class="form-group">
                                <label for="category" class="form-label">Category *</label>
                                <select id="category" name="category" class="form-control" required>
                                    <option value="">Select Category</option>
                                    <?php foreach ($categories as $value => $label): ?>
                                        <option value="<?php echo $value; ?>" <?php echo $formData['category'] === $value ? 'selected' : ''; ?>>
                                            <?php echo $label; ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label for="authors" class="form-label">Authors</label>
                                <input type="text" id="authors" name="authors" class="form-control" 
                                       value="<?php echo htmlspecialchars($formData['authors']); ?>" 
                                       placeholder="e.g., John Doe, Jane Smith, et al.">
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="abstract" class="form-label">Abstract *</label>
                        <textarea id="abstract" name="abstract" class="form-control" 
                                  rows="6" required><?php echo htmlspecialchars($formData['abstract']); ?></textarea>
                        <div class="form-text">Brief summary of the research project.</div>
                    </div>
                    
                    <div class="form-group">
                        <label for="methodology" class="form-label">Methodology</label>
                        <textarea id="methodology" name="methodology" class="form-control" 
                                  rows="6"><?php echo htmlspecialchars($formData['methodology']); ?></textarea>
                        <div class="form-text">Research methods and approaches used.</div>
                    </div>
                    
                    <div class="form-group">
                        <label for="key_findings" class="form-label">Key Findings</label>
                        <textarea id="key_findings" name="key_findings" class="form-control"
                                  rows="6"><?php echo htmlspecialchars($formData['key_findings']); ?></textarea>
                        <div class="form-text">Main results and conclusions.</div>
                    </div>
                </div>
            </div>
            
            <!-- Publication Details -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Publication Details</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-6">
                            <div class="form-group">
                                <label for="publication_date" class="form-label">Publication Date</label>
                                <input type="date" id="publication_date" name="publication_date" class="form-control" 
                                       value="<?php echo htmlspecialchars($formData['publication_date']); ?>">
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label for="journal" class="form-label">Journal/Conference</label>
                                <input type="text" id="journal" name="journal" class="form-control" 
                                       value="<?php echo htmlspecialchars($formData['journal']); ?>" 
                                       placeholder="e.g., Journal of Surveying Engineering">
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="description" class="form-label required">Description</label>
                        <textarea id="description" name="description" class="form-control"
                                  rows="4" required><?php echo htmlspecialchars($formData['description']); ?></textarea>
                        <div class="form-text">Detailed description of the research project.</div>
                    </div>

                    <div class="row">
                        <div class="col-6">
                            <div class="form-group">
                                <label for="category" class="form-label">Category</label>
                                <select id="category" name="category" class="form-control">
                                    <option value="">Select Category</option>
                                    <option value="surveying" <?php echo $formData['category'] === 'surveying' ? 'selected' : ''; ?>>Surveying</option>
                                    <option value="mapping" <?php echo $formData['category'] === 'mapping' ? 'selected' : ''; ?>>Mapping</option>
                                    <option value="gis" <?php echo $formData['category'] === 'gis' ? 'selected' : ''; ?>>GIS</option>
                                    <option value="remote_sensing" <?php echo $formData['category'] === 'remote_sensing' ? 'selected' : ''; ?>>Remote Sensing</option>
                                    <option value="cartography" <?php echo $formData['category'] === 'cartography' ? 'selected' : ''; ?>>Cartography</option>
                                    <option value="geodesy" <?php echo $formData['category'] === 'geodesy' ? 'selected' : ''; ?>>Geodesy</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label class="form-label">
                                    <input type="checkbox" name="featured" value="1"
                                           <?php echo $formData['featured'] ? 'checked' : ''; ?>>
                                    Featured Research
                                </label>
                                <div class="form-text">Mark as featured research project.</div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="doi" class="form-label">DOI</label>
                        <input type="text" id="doi" name="doi" class="form-control" 
                               value="<?php echo htmlspecialchars($formData['doi']); ?>" 
                               placeholder="e.g., 10.1061/(ASCE)SU.1943-5428.0000123">
                        <div class="form-text">Digital Object Identifier</div>
                    </div>
                </div>
            </div>
            
            <!-- Keywords -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Keywords</h3>
                </div>
                <div class="card-body">
                    <div id="keywords-container">
                        <?php foreach ($keywords as $index => $keyword): ?>
                        <div class="form-group d-flex align-items-center keyword-item">
                            <input type="text" name="keywords[]" class="form-control" 
                                   value="<?php echo htmlspecialchars($keyword); ?>" 
                                   placeholder="Keyword (e.g., GPS, surveying, mapping)">
                            <button type="button" class="btn btn-outline-danger btn-sm ms-2 remove-keyword">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                        <?php endforeach; ?>
                        
                        <?php if (empty($keywords)): ?>
                        <div class="form-group d-flex align-items-center keyword-item">
                            <input type="text" name="keywords[]" class="form-control" 
                                   placeholder="Keyword (e.g., GPS, surveying, mapping)">
                            <button type="button" class="btn btn-outline-danger btn-sm ms-2 remove-keyword">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                        <?php endif; ?>
                    </div>
                    <button type="button" id="add-keyword" class="btn btn-outline-primary btn-sm">
                        <i class="fas fa-plus"></i> Add Keyword
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
                            <?php foreach ($statuses as $value => $label): ?>
                                <option value="<?php echo $value; ?>" <?php echo $formData['status'] === $value ? 'selected' : ''; ?>>
                                    <?php echo $label; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
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
                <div class="card-footer">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> <?php echo $isEdit ? 'Update' : 'Create'; ?> Research
                    </button>
                    <a href="research.php" class="btn btn-secondary">
                        <i class="fas fa-times"></i> Cancel
                    </a>
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
    
    // Add/remove keywords
    document.getElementById('add-keyword').addEventListener('click', function() {
        const container = document.getElementById('keywords-container');
        const newItem = document.createElement('div');
        newItem.className = 'form-group d-flex align-items-center keyword-item';
        newItem.innerHTML = `
            <input type="text" name="keywords[]" class="form-control" 
                   placeholder="Keyword (e.g., GPS, surveying, mapping)">
            <button type="button" class="btn btn-outline-danger btn-sm ms-2 remove-keyword">
                <i class="fas fa-times"></i>
            </button>
        `;
        container.appendChild(newItem);
    });
    
    document.addEventListener('click', function(e) {
        if (e.target.closest('.remove-keyword')) {
            const item = e.target.closest('.keyword-item');
            if (document.querySelectorAll('.keyword-item').length > 1) {
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
