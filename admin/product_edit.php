<?php
/**
 * Product Edit/Add for FSMC Admin System
 * Created: 2025-01-22
 */

require_once 'config/config.php';

$productId = intval($_GET['id'] ?? 0);
$isEdit = $productId > 0;

// Set page variables
$pageTitle = $isEdit ? 'Edit Product' : 'Add New Product';
$pageIcon = 'fas fa-box';
$pageDescription = $isEdit ? 'Update product information' : 'Create a new product';
$breadcrumbs = [
    ['title' => 'Products', 'url' => 'products.php'],
    ['title' => $isEdit ? 'Edit Product' : 'Add Product']
];

// Get product data if editing
$product = null;
if ($isEdit) {
    $product = dbGetRow("SELECT * FROM products WHERE id = ?", [$productId]);
    if (!$product) {
        setErrorMessage('Product not found.');
        redirect('products.php');
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
            'category' => sanitize($_POST['category'] ?? ''),
            'manufacturer' => sanitize($_POST['manufacturer'] ?? ''),
            'model' => sanitize($_POST['model'] ?? ''),
            'specifications' => json_encode(array_filter(array_map('sanitize', $_POST['specifications'] ?? []))),
            'price' => !empty($_POST['price']) ? floatval($_POST['price']) : null,
            'warranty' => sanitize($_POST['warranty'] ?? ''),
            'support' => sanitize($_POST['support'] ?? ''),
            'icon' => sanitize($_POST['icon'] ?? ''),
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
        
        if (empty($data['category'])) {
            $errors[] = 'Category is required.';
        }
        
        // Check for duplicate slug
        $slugCheck = dbGetRow(
            "SELECT id FROM products WHERE slug = ? AND id != ?", 
            [$data['slug'], $productId]
        );
        if ($slugCheck) {
            $errors[] = 'Slug already exists. Please choose a different one.';
        }
        
        if (empty($errors)) {
            try {
                if ($isEdit) {
                    // Update product
                    $sql = "UPDATE products SET 
                            title = ?, slug = ?, description = ?, short_description = ?, 
                            category = ?, manufacturer = ?, model = ?, specifications = ?, 
                            price = ?, warranty = ?, support = ?, icon = ?, 
                            status = ?, sort_order = ?, meta_title = ?, meta_description = ?, 
                            updated_at = NOW() 
                            WHERE id = ?";
                    
                    $params = array_values($data);
                    $params[] = $productId;
                    
                    if (dbExecute($sql, $params)) {
                        logActivity('Product Updated', 'products', $productId, $product, $data);
                        setSuccessMessage('Product updated successfully.');
                        redirect('products.php');
                    } else {
                        $errors[] = 'Failed to update product.';
                    }
                } else {
                    // Create new product
                    $sql = "INSERT INTO products 
                            (title, slug, description, short_description, category, manufacturer, 
                             model, specifications, price, warranty, support, icon, 
                             status, sort_order, meta_title, meta_description) 
                            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
                    
                    $newId = dbInsert($sql, array_values($data));
                    if ($newId) {
                        logActivity('Product Created', 'products', $newId, null, $data);
                        setSuccessMessage('Product created successfully.');
                        redirect('products.php');
                    } else {
                        $errors[] = 'Failed to create product.';
                    }
                }
            } catch (Exception $e) {
                error_log("Product save error: " . $e->getMessage());
                $errors[] = 'An error occurred while saving the product.';
            }
        }
        
        if (!empty($errors)) {
            setErrorMessage(implode('<br>', $errors));
        }
    }
}

// Prepare form data
$formData = $product ?: [
    'title' => '',
    'slug' => '',
    'description' => '',
    'short_description' => '',
    'category' => '',
    'manufacturer' => '',
    'model' => '',
    'specifications' => '[]',
    'price' => '',
    'warranty' => '',
    'support' => '',
    'icon' => '',
    'status' => 'active',
    'sort_order' => 0,
    'meta_title' => '',
    'meta_description' => ''
];

// Decode JSON fields
$specifications = json_decode($formData['specifications'] ?? '[]', true) ?: [];

// Categories
$categories = [
    'equipment' => 'Equipment',
    'software' => 'Software',
    'training' => 'Training Materials',
    'bundle' => 'Bundle/Package'
];

include 'includes/header.php';
?>

<form method="POST" data-validate>
    <input type="hidden" name="csrf_token" value="<?php echo generateCSRFToken(); ?>">
    
    <div class="row">
        <!-- Main Content -->
        <div class="col-8">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Product Information</h3>
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
                                <label for="manufacturer" class="form-label">Manufacturer</label>
                                <input type="text" id="manufacturer" name="manufacturer" class="form-control" 
                                       value="<?php echo htmlspecialchars($formData['manufacturer']); ?>" 
                                       maxlength="100">
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-6">
                            <div class="form-group">
                                <label for="model" class="form-label">Model</label>
                                <input type="text" id="model" name="model" class="form-control" 
                                       value="<?php echo htmlspecialchars($formData['model']); ?>" 
                                       maxlength="100">
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label for="price" class="form-label">Price (RWF)</label>
                                <input type="number" id="price" name="price" class="form-control" 
                                       value="<?php echo htmlspecialchars($formData['price']); ?>" 
                                       min="0" step="0.01">
                                <div class="form-text">Leave empty for "Contact for price"</div>
                            </div>
                        </div>
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
                                <label for="warranty" class="form-label">Warranty</label>
                                <input type="text" id="warranty" name="warranty" class="form-control" 
                                       value="<?php echo htmlspecialchars($formData['warranty']); ?>" 
                                       placeholder="e.g., 2 years, 1 year parts & labor">
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label for="support" class="form-label">Support</label>
                                <input type="text" id="support" name="support" class="form-control" 
                                       value="<?php echo htmlspecialchars($formData['support']); ?>" 
                                       placeholder="e.g., 24/7 phone support, Email support">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Specifications -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Specifications</h3>
                </div>
                <div class="card-body">
                    <div id="specifications-container">
                        <?php foreach ($specifications as $index => $spec): ?>
                        <div class="form-group d-flex align-items-center spec-item">
                            <input type="text" name="specifications[]" class="form-control" 
                                   value="<?php echo htmlspecialchars($spec); ?>" 
                                   placeholder="Specification (e.g., Weight: 2.5kg, Resolution: 1920x1080)">
                            <button type="button" class="btn btn-outline-danger btn-sm ms-2 remove-spec">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                        <?php endforeach; ?>
                        
                        <?php if (empty($specifications)): ?>
                        <div class="form-group d-flex align-items-center spec-item">
                            <input type="text" name="specifications[]" class="form-control" 
                                   placeholder="Specification (e.g., Weight: 2.5kg, Resolution: 1920x1080)">
                            <button type="button" class="btn btn-outline-danger btn-sm ms-2 remove-spec">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                        <?php endif; ?>
                    </div>
                    <button type="button" id="add-spec" class="btn btn-outline-primary btn-sm">
                        <i class="fas fa-plus"></i> Add Specification
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
                        <i class="fas fa-save"></i> <?php echo $isEdit ? 'Update' : 'Create'; ?> Product
                    </button>
                    <a href="products.php" class="btn btn-secondary">
                        <i class="fas fa-times"></i> Cancel
                    </a>
                </div>
            </div>
            
            <!-- Product Icon -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Product Icon</h3>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <label for="icon" class="form-label">Font Awesome Icon Class</label>
                        <input type="text" id="icon" name="icon" class="form-control" 
                               value="<?php echo htmlspecialchars($formData['icon']); ?>" 
                               placeholder="e.g., fas fa-laptop, fas fa-tools">
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
    
    // Add/remove specifications
    document.getElementById('add-spec').addEventListener('click', function() {
        const container = document.getElementById('specifications-container');
        const newItem = document.createElement('div');
        newItem.className = 'form-group d-flex align-items-center spec-item';
        newItem.innerHTML = `
            <input type="text" name="specifications[]" class="form-control" 
                   placeholder="Specification (e.g., Weight: 2.5kg, Resolution: 1920x1080)">
            <button type="button" class="btn btn-outline-danger btn-sm ms-2 remove-spec">
                <i class="fas fa-times"></i>
            </button>
        `;
        container.appendChild(newItem);
    });
    
    document.addEventListener('click', function(e) {
        if (e.target.closest('.remove-spec')) {
            const item = e.target.closest('.spec-item');
            if (document.querySelectorAll('.spec-item').length > 1) {
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
