<?php
session_start();

// Include database configuration and functions
require_once '../../../config/database.php';
require_once '../../../includes/functions.php';

// Require admin authentication
requireAdminAuth();

// Get current admin user
$currentUser = getCurrentAdminUser();

// Initialize variables
$product = null;
$isEdit = false;
$errors = [];
$success = '';

// Check if editing existing product
if (isset($_GET['id']) && !empty($_GET['id'])) {
    $productId = intval($_GET['id']);
    $product = getRecordById('products', $productId);
    if ($product) {
        $isEdit = true;
    } else {
        $errors[] = 'Product not found.';
    }
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate and sanitize input
    $name = sanitizeInput($_POST['name'] ?? '');
    $description = sanitizeInput($_POST['description'] ?? '');
    $price = floatval($_POST['price'] ?? 0);
    $category_id = intval($_POST['category_id'] ?? 0);
    $stock_quantity = intval($_POST['stock_quantity'] ?? 0);
    $sku = sanitizeInput($_POST['sku'] ?? '');
    $status = sanitizeInput($_POST['status'] ?? 'Active');
    $specifications = sanitizeInput($_POST['specifications'] ?? '');
    $features = sanitizeInput($_POST['features'] ?? '');

    // Validation
    if (empty($name)) {
        $errors[] = 'Product name is required.';
    }
    if (empty($description)) {
        $errors[] = 'Product description is required.';
    }
    if ($price <= 0) {
        $errors[] = 'Product price must be greater than 0.';
    }
    if ($category_id <= 0) {
        $errors[] = 'Please select a valid category.';
    }
    if (empty($sku)) {
        $errors[] = 'SKU is required.';
    }

    // Check for duplicate SKU (excluding current product if editing)
    $mysqli = getDatabaseConnection();
    if ($mysqli) {
        $skuCheckQuery = "SELECT id FROM products WHERE sku = ? " . ($isEdit ? "AND id != $productId" : "");
        $stmt = $mysqli->prepare($skuCheckQuery);
        $stmt->bind_param('s', $sku);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            $errors[] = 'SKU already exists. Please use a different SKU.';
        }
        $stmt->close();
    }

    // Handle image upload
    $imagePath = $isEdit ? $product['image'] : '';
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $uploadResult = uploadImage($_FILES['image'], '../../../uploads/products/');
        if ($uploadResult['success']) {
            $imagePath = 'uploads/products/' . $uploadResult['filename'];
        } else {
            $errors[] = 'Image upload failed: ' . $uploadResult['error'];
        }
    }

    // If no errors, save the product
    if (empty($errors)) {
        $productData = [
            'name' => $name,
            'description' => $description,
            'price' => $price,
            'category_id' => $category_id,
            'stock_quantity' => $stock_quantity,
            'sku' => $sku,
            'status' => $status,
            'specifications' => $specifications,
            'features' => $features,
            'image' => $imagePath
        ];

        if ($isEdit) {
            $result = updateRecord('products', $productData, $productId);
            $success = $result ? 'Product updated successfully!' : 'Failed to update product.';
        } else {
            $result = insertRecord('products', $productData);
            $success = $result ? 'Product added successfully!' : 'Failed to add product.';
        }

        if ($result) {
            // Redirect to products list after successful save
            header('Location: all-products.php?success=' . urlencode($success));
            exit;
        } else {
            $errors[] = $success;
        }
    }
}

// Get product categories for dropdown
$categories = getAllRecords('product_categories', 'name ASC');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $isEdit ? 'Edit' : 'Add'; ?> Product | Fair Surveying & Mapping Ltd</title>
    <link rel="icon" type="image/svg+xml" href="../../../images/logo.png">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- TinyMCE -->
    <script src="https://cdn.tiny.cloud/1/cgqhjkrtsrkiah22kz9gqk6aiwkozwbuookw8z3w4zg21xk5/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
    <!-- Admin CSS -->
    <link rel="stylesheet" href="../../css/admin.css">
</head>
<body>
    <div class="admin-layout">
        <!-- Sidebar -->
        <?php include '../includes/sidebar.php'; ?>

        <!-- Header -->
        <?php include '../includes/header.php'; ?>

        <!-- Main Content -->
        <main class="admin-main animate-fadeIn">
            <div class="d-flex justify-between align-center mb-4">
                <h1 class="page-title"><?php echo $isEdit ? 'Edit' : 'Add'; ?> Product</h1>
                <a href="all-products.php" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i>
                    Back to Products
                </a>

            <!-- Display Messages -->
            <?php if (!empty($errors)): ?>
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-circle"></i>
                    <ul class="mb-0">
                        <?php foreach ($errors as $error): ?>
                            <li><?php echo htmlspecialchars($error); ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <?php if (!empty($success)): ?>
                <div class="alert alert-success">
                    <i class="fas fa-check-circle"></i>
                    <?php echo htmlspecialchars($success); ?>
                </div>
            <?php endif; ?>

            <!-- Product Form -->
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">
                        <i class="fas fa-box"></i>
                        Product Information
                    </h5>
                </div>
                <div class="card-body">
                    <form method="POST" enctype="multipart/form-data" id="productForm">
                        <div class="row">
                            <!-- Basic Information -->
                            <div class="col-lg-8">
                                <div class="form-group mb-3">
                                    <label for="name" class="form-label">Product Name <span class="text-danger">*</span></label>
                                    <input type="text"
                                           class="form-control"
                                           id="name"
                                           name="name"
                                           value="<?php echo $product ? htmlspecialchars($product['name']) : ''; ?>"
                                           required>
                                </div>

                                <div class="form-group mb-3">
                                    <label for="description" class="form-label">Description <span class="text-danger">*</span></label>
                                    <textarea class="form-control tinymce"
                                              id="description"
                                              name="description"
                                              rows="6"
                                              required><?php echo $product ? htmlspecialchars($product['description']) : ''; ?></textarea>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label for="price" class="form-label">Price (USD) <span class="text-danger">*</span></label>
                                            <div class="input-group">
                                                <span class="input-group-text">$</span>
                                                <input type="number"
                                                       class="form-control"
                                                       id="price"
                                                       name="price"
                                                       step="0.01"
                                                       min="0"
                                                       value="<?php echo $product ? $product['price'] : ''; ?>"
                                                       required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label for="category_id" class="form-label">Category <span class="text-danger">*</span></label>
                                            <select class="form-control" id="category_id" name="category_id" required>
                                                <option value="">Select Category</option>
                                                <?php foreach ($categories as $category): ?>
                                                    <option value="<?php echo $category['id']; ?>"
                                                            <?php echo ($product && $product['category_id'] == $category['id']) ? 'selected' : ''; ?>>
                                                        <?php echo htmlspecialchars($category['name']); ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label for="sku" class="form-label">SKU <span class="text-danger">*</span></label>
                                            <input type="text"
                                                   class="form-control"
                                                   id="sku"
                                                   name="sku"
                                                   value="<?php echo $product ? htmlspecialchars($product['sku']) : ''; ?>"
                                                   required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label for="stock_quantity" class="form-label">Stock Quantity</label>
                                            <input type="number"
                                                   class="form-control"
                                                   id="stock_quantity"
                                                   name="stock_quantity"
                                                   min="0"
                                                   value="<?php echo $product ? $product['stock_quantity'] : '0'; ?>">
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group mb-3">
                                    <label for="specifications" class="form-label">Specifications</label>
                                    <textarea class="form-control tinymce"
                                              id="specifications"
                                              name="specifications"
                                              rows="4"><?php echo $product ? htmlspecialchars($product['specifications']) : ''; ?></textarea>
                                </div>

                                <div class="form-group mb-3">
                                    <label for="features" class="form-label">Features</label>
                                    <textarea class="form-control tinymce"
                                              id="features"
                                              name="features"
                                              rows="4"><?php echo $product ? htmlspecialchars($product['features']) : ''; ?></textarea>
                                </div>
                            </div>

                            <!-- Sidebar -->
                            <div class="col-lg-4">
                                <div class="card">
                                    <div class="card-header">
                                        <h6 class="card-title">Product Settings</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="form-group mb-3">
                                            <label for="status" class="form-label">Status</label>
                                            <select class="form-control" id="status" name="status">
                                                <option value="Active" <?php echo (!$product || $product['status'] == 'Active') ? 'selected' : ''; ?>>Active</option>
                                                <option value="Inactive" <?php echo ($product && $product['status'] == 'Inactive') ? 'selected' : ''; ?>>Inactive</option>
                                                <option value="Discontinued" <?php echo ($product && $product['status'] == 'Discontinued') ? 'selected' : ''; ?>>Discontinued</option>
                                            </select>
                                        </div>

                                        <div class="form-group mb-3">
                                            <label for="image" class="form-label">Product Image</label>
                                            <input type="file"
                                                   class="form-control"
                                                   id="image"
                                                   name="image"
                                                   accept="image/*">
                                            <small class="form-text text-muted">
                                                Supported formats: JPG, PNG, GIF. Max size: 5MB
                                            </small>

                                            <?php if ($product && !empty($product['image'])): ?>
                                                <div class="mt-3">
                                                    <label class="form-label">Current Image:</label>
                                                    <div class="current-image">
                                                        <img src="../../../<?php echo htmlspecialchars($product['image']); ?>"
                                                             alt="Current product image"
                                                             class="img-thumbnail"
                                                             style="max-width: 200px; max-height: 200px;">
                                                    </div>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Form Actions -->
                        <div class="form-actions mt-4">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i>
                                <?php echo $isEdit ? 'Update' : 'Save'; ?> Product
                            </button>
                            <a href="all-products.php" class="btn btn-secondary">
                                <i class="fas fa-times"></i>
                                Cancel
                            </a>
                        </div>
                    </form>
                </div>
        </main>
    </div>

    <!-- Admin JavaScript -->
    <script src="../../js/admin.js"></script>

    <script>
        // Initialize TinyMCE
        tinymce.init({
            selector: '.tinymce',
            height: 300,
            menubar: false,
            plugins: [
                'advlist', 'autolink', 'lists', 'link', 'image', 'charmap', 'preview',
                'anchor', 'searchreplace', 'visualblocks', 'code', 'fullscreen',
                'insertdatetime', 'media', 'table', 'help', 'wordcount'
            ],
            toolbar: 'undo redo | blocks | ' +
                'bold italic forecolor | alignleft aligncenter ' +
                'alignright alignjustify | bullist numlist outdent indent | ' +
                'removeformat | help',
            content_style: 'body { font-family:Helvetica,Arial,sans-serif; font-size:14px }'
        });

        // Form validation
        document.getElementById('productForm').addEventListener('submit', function(e) {
            const name = document.getElementById('name').value.trim();
            const description = tinymce.get('description').getContent();
            const price = document.getElementById('price').value;
            const categoryId = document.getElementById('category_id').value;
            const sku = document.getElementById('sku').value.trim();

            if (!name) {
                e.preventDefault();
                alert('Please enter a product name.');
                return false;
            }

            if (!description) {
                e.preventDefault();
                alert('Please enter a product description.');
                return false;
            }

            if (!price || parseFloat(price) <= 0) {
                e.preventDefault();
                alert('Please enter a valid price greater than 0.');
                return false;
            }

            if (!categoryId) {
                e.preventDefault();
                alert('Please select a category.');
                return false;
            }

            if (!sku) {
                e.preventDefault();
                alert('Please enter a SKU.');
                return false;
            }
        });

        // Auto-generate SKU based on product name
        document.getElementById('name').addEventListener('blur', function() {
            const skuField = document.getElementById('sku');
            if (!skuField.value) {
                const name = this.value.trim();
                if (name) {
                    const sku = name.toUpperCase()
                        .replace(/[^A-Z0-9]/g, '')
                        .substring(0, 8) + '-' + Math.random().toString(36).substr(2, 4).toUpperCase();
                    skuField.value = sku;
                }
            }
        });
    </script>
</body>
</html>