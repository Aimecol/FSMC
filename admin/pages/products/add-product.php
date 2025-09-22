<?php
session_start();

// Include database configuration and functions
require_once '../../../config/database.php';
require_once '../../../includes/functions.php';

// Initialize security (must be called before any output)
initializeSecurity();

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
$categories = getAllRecords('product_categories', '', 'name ASC');
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
    <!-- Admin CSS -->
    <link rel="stylesheet" href="../../css/admin.css">
    
    <!-- Enhanced Form Styling & TinyMCE Integration -->
    <style>
        /* Enhanced Form Styling */
        .product-form {
            background: var(--bg-primary);
            border-radius: 12px;
            box-shadow: var(--shadow-md);
            overflow: visible;
            border: 1px solid var(--border-color);
        }

        /* Page Title Styling */
        .page-title {
            font-size: var(--font-size-3xl);
            font-weight: 700;
            color: var(--text-primary);
            margin: 0;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .page-title .fas {
            color: var(--primary-color);
            font-size: var(--font-size-2xl);
        }

        /* Enhanced Card Styling */
        .product-form.card {
            transition: all 0.3s ease;
        }

        .product-form.card:hover {
            box-shadow: var(--shadow-lg);
            transform: translateY(-2px);
        }

        .product-form .form-group {
            margin-bottom: 1.5rem;
            position: relative;
        }

        .product-form .form-label {
            display: block;
            margin-bottom: 0.75rem;
            font-weight: 600;
            color: var(--text-primary);
            font-size: var(--font-size-base);
            line-height: 1.4;
        }

        .product-form .form-label .text-danger {
            color: var(--danger-color);
            margin-left: 0.25rem;
        }

        /* Enhanced Form Controls */
        .product-form .form-control {
            width: 100%;
            padding: 0.875rem 1rem;
            font-size: var(--font-size-base);
            line-height: 1.5;
            color: var(--text-primary);
            background-color: var(--bg-primary);
            border: 2px solid var(--border-color);
            border-radius: 8px;
            transition: all 0.3s ease;
            font-family: var(--font-primary);
        }

        .product-form .form-control:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(46, 134, 193, 0.1);
            background-color: var(--bg-primary);
        }

        .product-form .form-control:hover:not(:focus) {
            border-color: var(--border-color-dark);
        }

        .product-form .form-control::placeholder {
            color: var(--text-muted);
            opacity: 0.8;
        }

        /* Input Group Styling */
        .product-form .input-group {
            display: flex;
            align-items: stretch;
            border-radius: 8px;
            overflow: hidden;
        }

        .product-form .input-group-text {
            display: flex;
            align-items: center;
            padding: 0.875rem 1rem;
            font-size: var(--font-size-base);
            font-weight: 500;
            color: var(--text-secondary);
            background-color: var(--bg-tertiary);
            border: 2px solid var(--border-color);
            border-right: none;
            white-space: nowrap;
        }

        .product-form .input-group .form-control {
            border-left: none;
            border-radius: 0 8px 8px 0;
        }

        .product-form .input-group:focus-within .input-group-text {
            border-color: var(--primary-color);
            background-color: rgba(46, 134, 193, 0.05);
        }

        .product-form .input-group:focus-within .form-control {
            border-color: var(--primary-color);
        }

        /* Select Styling */
        .product-form select.form-control {
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='%2364748b'%3E%3Cpath d='M7 10l5 5 5-5z'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 1rem center;
            background-size: 1.25rem;
            padding-right: 3rem;
            cursor: pointer;
        }

        .product-form select.form-control:focus {
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='%232e86c1'%3E%3Cpath d='M7 10l5 5 5-5z'/%3E%3C/svg%3E");
        }

        /* File Input Styling */
        .product-form input[type="file"].form-control {
            padding: 0.75rem 1rem;
            cursor: pointer;
            border-style: dashed;
        }

        .product-form input[type="file"].form-control:hover {
            border-color: var(--primary-color);
            background-color: rgba(46, 134, 193, 0.02);
        }

        /* Form Text Styling */
        .product-form .form-text {
            font-size: var(--font-size-sm);
            color: var(--text-muted);
            margin-top: 0.5rem;
            line-height: 1.4;
        }

        /* Current Image Display */
        .current-image {
            margin-top: 1rem;
            padding: 1rem;
            background: var(--bg-secondary);
            border-radius: 8px;
            border: 1px solid var(--border-color);
        }

        .current-image img {
            border-radius: 6px;
            box-shadow: var(--shadow-sm);
            transition: transform 0.3s ease;
        }

        .current-image img:hover {
            transform: scale(1.05);
        }

        /* Enhanced TinyMCE Integration */
        .tinymce-container {
            position: relative !important;
            z-index: 10 !important;
            width: 100% !important;
            margin-bottom: 1.5rem !important;
        }

        .tinymce-container .form-label {
            margin-bottom: 0.75rem;
        }

        /* TinyMCE Textarea Fallback */
        textarea.tinymce {
            visibility: visible !important;
            display: block !important;
            width: 100% !important;
            min-height: 300px !important;
            border: 2px solid var(--border-color) !important;
            border-radius: 8px !important;
            padding: 0.875rem 1rem !important;
            font-family: var(--font-primary) !important;
            font-size: var(--font-size-base) !important;
            line-height: 1.5 !important;
            color: var(--text-primary) !important;
            background-color: var(--bg-primary) !important;
            transition: all 0.3s ease !important;
            resize: vertical;
        }

        textarea.tinymce:focus {
            outline: none !important;
            border-color: var(--primary-color) !important;
            box-shadow: 0 0 0 3px rgba(46, 134, 193, 0.1) !important;
        }

        /* TinyMCE Editor Styling */
        .tox-tinymce {
            visibility: visible !important;
            display: block !important;
            width: 100% !important;
            border: 2px solid var(--border-color) !important;
            border-radius: 8px !important;
            z-index: 100 !important;
            font-family: var(--font-primary) !important;
        }

        .tox-tinymce:focus-within {
            border-color: var(--primary-color) !important;
            box-shadow: 0 0 0 3px rgba(46, 134, 193, 0.1) !important;
        }

        .tox-editor-container {
            display: block !important;
            visibility: visible !important;
            z-index: 100 !important;
        }

        .tox-edit-area {
            display: block !important;
            visibility: visible !important;
        }

        .tox-edit-area iframe {
            display: block !important;
            visibility: visible !important;
            width: 100% !important;
            min-height: 250px !important;
        }

        /* TinyMCE Toolbar Styling */
        .tox-toolbar-overlord,
        .tox-toolbar,
        .tox-toolbar__primary,
        .tox-toolbar__overflow {
            z-index: 1001 !important;
            visibility: visible !important;
            display: flex !important;
            background: var(--bg-secondary) !important;
            border-bottom: 1px solid var(--border-color) !important;
        }

        .tox-menubar {
            z-index: 1001 !important;
            visibility: visible !important;
            background: var(--bg-tertiary) !important;
        }

        .tox-statusbar {
            z-index: 1001 !important;
            visibility: visible !important;
            background: var(--bg-secondary) !important;
            border-top: 1px solid var(--border-color) !important;
        }

        /* TinyMCE Modal and Popup Elements */
        .tox-dialog-wrap,
        .tox-dialog,
        .tox-pop,
        .tox-pop__dialog,
        .tox-menu,
        .tox-collection,
        .tox-autocompleter {
            z-index: 10000 !important;
        }

        .tox-swatches-menu,
        .tox-color-picker-container,
        .tox-tooltip,
        .tox-silver-sink {
            z-index: 10001 !important;
        }

        /* Layout Compatibility */
        .admin-layout .tox-tinymce,
        .admin-layout .tox-editor-container,
        .admin-layout .tox-edit-area {
            z-index: 1000 !important;
            position: relative !important;
        }

        .product-form .form-group,
        .product-form .card,
        .product-form .card-body,
        .product-form .row,
        .product-form .col-lg-8,
        .product-form .col-lg-4,
        .product-form .col-md-6 {
            overflow: visible !important;
            position: relative !important;
        }

        /* Loading State */
        .tinymce-loading {
            background: var(--bg-secondary);
            border: 2px dashed var(--border-color);
            border-radius: 8px;
            padding: 2rem;
            text-align: center;
            color: var(--text-muted);
            font-size: var(--font-size-base);
        }

        /* Form Validation States */
        .product-form .form-control.is-invalid {
            border-color: var(--danger-color);
            box-shadow: 0 0 0 3px rgba(231, 76, 60, 0.1);
        }

        .product-form .form-control.is-valid {
            border-color: var(--success-color);
            box-shadow: 0 0 0 3px rgba(39, 174, 96, 0.1);
        }

        .product-form .invalid-feedback {
            display: none;
            color: var(--danger-color);
            font-size: var(--font-size-sm);
            margin-top: 0.5rem;
            font-weight: 500;
        }

        .product-form .valid-feedback {
            display: none;
            color: var(--success-color);
            font-size: var(--font-size-sm);
            margin-top: 0.5rem;
            font-weight: 500;
        }

        .product-form .is-invalid ~ .invalid-feedback {
            display: block;
        }

        .product-form .is-valid ~ .valid-feedback {
            display: block;
        }

        /* Enhanced Button Styling */
        .product-form .btn {
            font-weight: 600;
            letter-spacing: 0.025em;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .product-form .btn:hover {
            transform: translateY(-1px);
            box-shadow: var(--shadow-lg);
        }

        .product-form .btn:active {
            transform: translateY(0);
        }

        .product-form .btn-primary {
            background: linear-gradient(135deg, var(--primary-color), var(--primary-light));
            border: none;
            color: var(--text-light);
        }

        .product-form .btn-primary:hover {
            background: linear-gradient(135deg, var(--primary-dark), var(--primary-color));
        }

        .product-form .btn-secondary {
            background: var(--neutral-500);
            border: none;
            color: var(--text-light);
        }

        .product-form .btn-secondary:hover {
            background: var(--neutral-600);
        }

        /* Form Actions Styling */
        .product-form .form-actions {
            background: var(--bg-secondary);
            margin: 0 -1.5rem -1.5rem -1.5rem;
            padding: 1.5rem;
            border-radius: 0 0 12px 12px;
        }

        /* Enhanced Focus States */
        .product-form .form-control:focus,
        .product-form .form-control:focus-visible {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(46, 134, 193, 0.15);
            background-color: rgba(46, 134, 193, 0.02);
        }

        /* Loading States */
        .product-form .btn.loading {
            pointer-events: none;
            opacity: 0.7;
        }

        .product-form .btn.loading::after {
            content: "";
            position: absolute;
            width: 16px;
            height: 16px;
            margin: auto;
            border: 2px solid transparent;
            border-top-color: currentColor;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .tox-tinymce {
                width: 100% !important;
            }

            .tinymce-container {
                margin-bottom: 1rem !important;
            }

            .product-form .form-control {
                padding: 0.75rem 0.875rem;
                font-size: var(--font-size-sm);
            }

            .product-form .input-group-text {
                padding: 0.75rem 0.875rem;
                font-size: var(--font-size-sm);
            }

            .product-form .form-actions {
                margin: 0 -1rem -1rem -1rem;
                padding: 1rem;
            }

            .product-form .form-actions .d-flex {
                flex-direction: column;
                gap: 0.75rem;
            }

            .product-form .btn {
                width: 100%;
                justify-content: center;
            }
        }

        @media (max-width: 576px) {
            .product-form .card-body {
                padding: 1rem;
            }

            .product-form .form-group {
                margin-bottom: 1rem;
            }

            .product-form .input-group-text {
                padding: 0.625rem 0.75rem;
                font-size: var(--font-size-xs);
            }

            .product-form .form-control {
                padding: 0.625rem 0.75rem;
                font-size: var(--font-size-sm);
            }
        }
    </style>
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
                <h1 class="page-title" style="color: var(--text-primary); font-weight: 600;">
                    <i class="fas fa-box text-primary me-2"></i>
                    <?php echo $isEdit ? 'Edit' : 'Add'; ?> Product
                </h1>
                <a href="all-products.php" class="btn btn-secondary" style="text-decoration: none;">
                    <i class="fas fa-arrow-left"></i>
                    Back to Products
                </a>
            </div>

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
            <div class="card product-form">
                <div class="card-header">
                    <h5 class="card-title">
                        <i class="fas fa-box"></i>
                        Product Information
                    </h5>
                </div>
                <div class="card-body">
                    <form method="POST" enctype="multipart/form-data" id="productForm" class="product-form">
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
                                    <div class="tinymce-container">
                                        <textarea class="form-control tinymce"
                                                  id="description"
                                                  name="description"
                                                  rows="6"
                                                  required><?php echo $product ? htmlspecialchars($product['description']) : ''; ?></textarea>
                                    </div>
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
                                    <div class="tinymce-container">
                                        <textarea class="form-control tinymce"
                                                  id="specifications"
                                                  name="specifications"
                                                  rows="4"><?php echo $product ? htmlspecialchars($product['specifications']) : ''; ?></textarea>
                                    </div>
                                </div>

                                <div class="form-group mb-3">
                                    <label for="features" class="form-label">Features</label>
                                    <div class="tinymce-container">
                                        <textarea class="form-control tinymce"
                                                  id="features"
                                                  name="features"
                                                  rows="4"><?php echo $product ? htmlspecialchars($product['features']) : ''; ?></textarea>
                                    </div>
                                </div>
                            </div>

                            <!-- Sidebar -->
                            <div class="col-lg-4">
                                <div class="card" style="background: var(--bg-secondary); border: 1px solid var(--border-color);">
                                    <div class="card-header" style="background: var(--bg-tertiary); border-bottom: 1px solid var(--border-color);">
                                        <h6 class="card-title" style="color: var(--text-primary); font-weight: 600;">
                                            <i class="fas fa-cog text-muted me-2"></i>
                                            Product Settings
                                        </h6>
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
                                            <label for="image" class="form-label">
                                                <i class="fas fa-image text-muted me-2"></i>
                                                Product Image
                                            </label>
                                            <input type="file"
                                                   class="form-control"
                                                   id="image"
                                                   name="image"
                                                   accept="image/jpeg,image/png,image/gif,image/webp">
                                            <div class="form-text">
                                                <i class="fas fa-info-circle text-info me-1"></i>
                                                Supported formats: JPG, PNG, GIF, WebP. Maximum size: 5MB
                                            </div>

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
                        <div class="form-actions mt-4 pt-4" style="border-top: 1px solid var(--border-color);">
                            <div class="d-flex gap-3 justify-end align-center">
                                <a href="all-products.php" class="btn btn-secondary" style="text-decoration: none;">
                                    <i class="fas fa-arrow-left"></i>
                                    Cancel
                                </a>
                                <button type="submit" class="btn btn-primary btn-lg">
                                    <i class="fas fa-save"></i>
                                    <?php echo $isEdit ? 'Update Product' : 'Save Product'; ?>
                                </button>
                            </div>
                            <div class="mt-3" style="text-align: center;">
                                <small style="color: var(--text-muted);">
                                    <i class="fas fa-info-circle"></i>
                                    All required fields must be completed before saving
                                </small>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </main>
    </div>

    <!-- TinyMCE CDN -->
    <script src="https://cdn.tiny.cloud/1/cgqhjkrtsrkiah22kz9gqk6aiwkozwbuookw8z3w4zg21xk5/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
    
    <!-- Admin JavaScript -->
    <script src="../../js/admin.js"></script>

    <script>
        // Enhanced TinyMCE Initialization with Visibility Fixes
        document.addEventListener('DOMContentLoaded', function() {
            // Add loading states to textareas
            document.querySelectorAll('.tinymce').forEach(function(textarea) {
                textarea.style.display = 'block';
                textarea.style.visibility = 'visible';
            });

            // Initialize TinyMCE with enhanced configuration
            tinymce.init({
                selector: '.tinymce',
                height: 300,
                menubar: false,
                branding: false,
                resize: true,
                statusbar: true,
                element_format: 'html',
                forced_root_block: 'p',
                force_br_newlines: false,
                force_p_newlines: false,
                plugins: [
                    'advlist', 'autolink', 'lists', 'link', 'image', 'charmap', 'preview',
                    'anchor', 'searchreplace', 'visualblocks', 'code', 'fullscreen',
                    'insertdatetime', 'media', 'table', 'help', 'wordcount'
                ],
                toolbar: 'undo redo | blocks | ' +
                    'bold italic forecolor | alignleft aligncenter ' +
                    'alignright alignjustify | bullist numlist outdent indent | ' +
                    'removeformat | code | help',
                content_style: 'body { font-family: Helvetica, Arial, sans-serif; font-size: 14px; line-height: 1.6; }',
                
                // Enhanced setup for visibility
                setup: function(editor) {
                    editor.on('init', function() {
                        console.log('TinyMCE initialized for:', editor.id);
                        
                        // Ensure the editor container is visible
                        var container = editor.getContainer();
                        if (container) {
                            container.style.visibility = 'visible';
                            container.style.display = 'block';
                            container.style.zIndex = '100';
                        }

                        // Ensure the iframe is visible
                        var iframe = editor.getBody();
                        if (iframe) {
                            iframe.style.visibility = 'visible';
                        }
                    });

                    editor.on('focus', function() {
                        // Ensure editor stays visible on focus
                        var container = editor.getContainer();
                        if (container) {
                            container.style.zIndex = '1000';
                        }
                    });

                    editor.on('blur', function() {
                        // Reset z-index on blur
                        var container = editor.getContainer();
                        if (container) {
                            container.style.zIndex = '100';
                        }
                    });
                },

                // Initialization callback
                init_instance_callback: function(editor) {
                    console.log('Editor initialized:', editor.id);
                    
                    // Force visibility after initialization
                    setTimeout(function() {
                        var container = editor.getContainer();
                        var editorArea = container.querySelector('.tox-edit-area');
                        var iframe = container.querySelector('.tox-edit-area iframe');
                        
                        if (container) {
                            container.style.visibility = 'visible';
                            container.style.display = 'block';
                        }
                        
                        if (editorArea) {
                            editorArea.style.visibility = 'visible';
                            editorArea.style.display = 'block';
                        }
                        
                        if (iframe) {
                            iframe.style.visibility = 'visible';
                            iframe.style.display = 'block';
                        }
                    }, 100);
                }
            });
        });

        // Enhanced Form validation with visual feedback
        function validateField(field, isValid, message = '') {
            const formGroup = field.closest('.form-group');
            const feedback = formGroup.querySelector('.invalid-feedback') ||
                           formGroup.querySelector('.valid-feedback');

            // Remove existing validation classes
            field.classList.remove('is-valid', 'is-invalid');

            if (feedback) {
                feedback.remove();
            }

            if (isValid) {
                field.classList.add('is-valid');
                if (message) {
                    const validFeedback = document.createElement('div');
                    validFeedback.className = 'valid-feedback';
                    validFeedback.textContent = message;
                    formGroup.appendChild(validFeedback);
                }
            } else {
                field.classList.add('is-invalid');
                if (message) {
                    const invalidFeedback = document.createElement('div');
                    invalidFeedback.className = 'invalid-feedback';
                    invalidFeedback.textContent = message;
                    formGroup.appendChild(invalidFeedback);
                }
            }
        }

        // Real-time validation
        document.getElementById('name').addEventListener('blur', function() {
            const isValid = this.value.trim().length >= 2;
            validateField(this, isValid, isValid ? 'Product name looks good!' : 'Product name must be at least 2 characters long.');
        });

        document.getElementById('price').addEventListener('blur', function() {
            const price = parseFloat(this.value);
            const isValid = price > 0;
            validateField(this, isValid, isValid ? 'Price is valid!' : 'Price must be greater than 0.');
        });

        document.getElementById('sku').addEventListener('blur', function() {
            const isValid = this.value.trim().length >= 3;
            validateField(this, isValid, isValid ? 'SKU format is valid!' : 'SKU must be at least 3 characters long.');
        });

        document.getElementById('category_id').addEventListener('change', function() {
            const isValid = this.value !== '';
            validateField(this, isValid, isValid ? 'Category selected!' : 'Please select a category.');
        });

        // Enhanced Form submission with loading state
        document.getElementById('productForm').addEventListener('submit', function(e) {
            const submitBtn = this.querySelector('button[type="submit"]');
            const name = document.getElementById('name').value.trim();
            const price = document.getElementById('price').value;
            const categoryId = document.getElementById('category_id').value;
            const sku = document.getElementById('sku').value.trim();

            // Get TinyMCE content
            let description = '';
            if (tinymce.get('description')) {
                description = tinymce.get('description').getContent().trim();
            } else {
                description = document.getElementById('description').value.trim();
            }

            let hasErrors = false;

            // Validate all fields
            if (!name || name.length < 2) {
                validateField(document.getElementById('name'), false, 'Product name is required and must be at least 2 characters long.');
                hasErrors = true;
            }

            if (!description || description === '<p></p>' || description === '') {
                // For TinyMCE, we'll show a general alert since it's harder to add validation styling
                if (tinymce.get('description')) {
                    alert('Please enter a product description.');
                    tinymce.get('description').focus();
                } else {
                    validateField(document.getElementById('description'), false, 'Product description is required.');
                }
                hasErrors = true;
            }

            if (!price || parseFloat(price) <= 0) {
                validateField(document.getElementById('price'), false, 'Please enter a valid price greater than 0.');
                hasErrors = true;
            }

            if (!categoryId) {
                validateField(document.getElementById('category_id'), false, 'Please select a category.');
                hasErrors = true;
            }

            if (!sku || sku.length < 3) {
                validateField(document.getElementById('sku'), false, 'SKU is required and must be at least 3 characters long.');
                hasErrors = true;
            }

            if (hasErrors) {
                e.preventDefault();
                // Scroll to first error
                const firstError = this.querySelector('.is-invalid');
                if (firstError) {
                    firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    firstError.focus();
                }
                return false;
            }

            // Add loading state to submit button
            submitBtn.classList.add('loading');
            submitBtn.disabled = true;
            const originalText = submitBtn.innerHTML;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Saving...';

            // Re-enable button after 10 seconds as fallback
            setTimeout(() => {
                submitBtn.classList.remove('loading');
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalText;
            }, 10000);
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

        // Force visibility check on window resize
        window.addEventListener('resize', function() {
            setTimeout(function() {
                document.querySelectorAll('.tox-tinymce').forEach(function(editor) {
                    editor.style.visibility = 'visible';
                    editor.style.display = 'block';
                });
            }, 100);
        });
    </script>
</body>
</html>