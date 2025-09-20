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
$service = null;
$isEdit = false;
$errors = [];
$success = '';

// Check if editing existing service
if (isset($_GET['id']) && !empty($_GET['id'])) {
    $serviceId = intval($_GET['id']);
    $service = getRecordById('services', $serviceId);
    if ($service) {
        $isEdit = true;
    } else {
        $errors[] = 'Service not found.';
    }
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate and sanitize input
    $name = sanitizeInput($_POST['name'] ?? '');
    $description = sanitizeInput($_POST['description'] ?? '');
    $short_description = sanitizeInput($_POST['short_description'] ?? '');
    $price = floatval($_POST['price'] ?? 0);
    $category_id = intval($_POST['category_id'] ?? 0);
    $duration = sanitizeInput($_POST['duration'] ?? '');
    $status = sanitizeInput($_POST['status'] ?? 'Active');
    $features = sanitizeInput($_POST['features'] ?? '');
    $requirements = sanitizeInput($_POST['requirements'] ?? '');

    // Validation
    if (empty($name)) {
        $errors[] = 'Service name is required.';
    }
    if (empty($description)) {
        $errors[] = 'Service description is required.';
    }
    if (empty($short_description)) {
        $errors[] = 'Short description is required.';
    }
    if ($price < 0) {
        $errors[] = 'Service price cannot be negative.';
    }
    if ($category_id <= 0) {
        $errors[] = 'Please select a valid category.';
    }

    // Handle image upload
    $imagePath = $isEdit ? $service['image'] : '';
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $uploadResult = uploadImage($_FILES['image'], '../../../uploads/services/');
        if ($uploadResult['success']) {
            $imagePath = 'uploads/services/' . $uploadResult['filename'];
        } else {
            $errors[] = 'Image upload failed: ' . $uploadResult['error'];
        }
    }

    // If no errors, save the service
    if (empty($errors)) {
        $serviceData = [
            'name' => $name,
            'description' => $description,
            'short_description' => $short_description,
            'price' => $price,
            'category_id' => $category_id,
            'duration' => $duration,
            'status' => $status,
            'features' => $features,
            'requirements' => $requirements,
            'image' => $imagePath
        ];

        if ($isEdit) {
            $result = updateRecord('services', $serviceData, $serviceId);
            $success = $result ? 'Service updated successfully!' : 'Failed to update service.';
        } else {
            $result = insertRecord('services', $serviceData);
            $success = $result ? 'Service added successfully!' : 'Failed to add service.';
        }

        if ($result) {
            // Redirect to services list after successful save
            header('Location: all-services.php?success=' . urlencode($success));
            exit;
        } else {
            $errors[] = $success;
        }
    }
}

// Get service categories for dropdown
$categories = getAllRecords('service_categories', 'name ASC');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $isEdit ? 'Edit' : 'Add'; ?> Service | Fair Surveying & Mapping Ltd</title>
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
                <h1 class="page-title"><?php echo $isEdit ? 'Edit' : 'Add'; ?> Service</h1>
                <a href="all-services.php" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i>
                    Back to Services
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

            <!-- Service Form -->
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">
                        <i class="fas fa-cogs"></i>
                        Service Information
                    </h5>
                </div>
                <div class="card-body">
                    <form method="POST" enctype="multipart/form-data" id="serviceForm">
                        <div class="row">
                            <!-- Basic Information -->
                            <div class="col-lg-8">
                                <div class="form-group mb-3">
                                    <label for="name" class="form-label">Service Name <span class="text-danger">*</span></label>
                                    <input type="text"
                                           class="form-control"
                                           id="name"
                                           name="name"
                                           value="<?php echo $service ? htmlspecialchars($service['name']) : ''; ?>"
                                           required>
                                </div>

                                <div class="form-group mb-3">
                                    <label for="short_description" class="form-label">Short Description <span class="text-danger">*</span></label>
                                    <textarea class="form-control"
                                              id="short_description"
                                              name="short_description"
                                              rows="3"
                                              placeholder="Brief description for service listings"
                                              required><?php echo $service ? htmlspecialchars($service['short_description']) : ''; ?></textarea>
                                </div>

                                <div class="form-group mb-3">
                                    <label for="description" class="form-label">Full Description <span class="text-danger">*</span></label>
                                    <textarea class="form-control tinymce"
                                              id="description"
                                              name="description"
                                              rows="8"
                                              required><?php echo $service ? htmlspecialchars($service['description']) : ''; ?></textarea>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label for="price" class="form-label">Price (USD)</label>
                                            <div class="input-group">
                                                <span class="input-group-text">$</span>
                                                <input type="number"
                                                       class="form-control"
                                                       id="price"
                                                       name="price"
                                                       step="0.01"
                                                       min="0"
                                                       value="<?php echo $service ? $service['price'] : ''; ?>"
                                                       placeholder="0.00">
                                            </div>
                                            <small class="form-text text-muted">Leave blank for quote-based pricing</small>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label for="category_id" class="form-label">Category <span class="text-danger">*</span></label>
                                            <select class="form-control" id="category_id" name="category_id" required>
                                                <option value="">Select Category</option>
                                                <?php foreach ($categories as $category): ?>
                                                    <option value="<?php echo $category['id']; ?>"
                                                            <?php echo ($service && $service['category_id'] == $category['id']) ? 'selected' : ''; ?>>
                                                        <?php echo htmlspecialchars($category['name']); ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group mb-3">
                                    <label for="duration" class="form-label">Service Duration</label>
                                    <input type="text"
                                           class="form-control"
                                           id="duration"
                                           name="duration"
                                           value="<?php echo $service ? htmlspecialchars($service['duration']) : ''; ?>"
                                           placeholder="e.g., 2-3 weeks, 1 month, Varies">
                                </div>

                                <div class="form-group mb-3">
                                    <label for="features" class="form-label">Service Features</label>
                                    <textarea class="form-control tinymce"
                                              id="features"
                                              name="features"
                                              rows="6"><?php echo $service ? htmlspecialchars($service['features']) : ''; ?></textarea>
                                </div>

                                <div class="form-group mb-3">
                                    <label for="requirements" class="form-label">Requirements</label>
                                    <textarea class="form-control tinymce"
                                              id="requirements"
                                              name="requirements"
                                              rows="4"><?php echo $service ? htmlspecialchars($service['requirements']) : ''; ?></textarea>
                                </div>

                            <!-- Sidebar -->
                            <div class="col-lg-4">
                                <div class="card">
                                    <div class="card-header">
                                        <h6 class="card-title">Service Settings</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="form-group mb-3">
                                            <label for="status" class="form-label">Status</label>
                                            <select class="form-control" id="status" name="status">
                                                <option value="Active" <?php echo (!$service || $service['status'] == 'Active') ? 'selected' : ''; ?>>Active</option>
                                                <option value="Inactive" <?php echo ($service && $service['status'] == 'Inactive') ? 'selected' : ''; ?>>Inactive</option>
                                                <option value="Coming Soon" <?php echo ($service && $service['status'] == 'Coming Soon') ? 'selected' : ''; ?>>Coming Soon</option>
                                            </select>
                                        </div>

                                        <div class="form-group mb-3">
                                            <label for="image" class="form-label">Service Image</label>
                                            <input type="file"
                                                   class="form-control"
                                                   id="image"
                                                   name="image"
                                                   accept="image/*">
                                            <small class="form-text text-muted">
                                                Supported formats: JPG, PNG, GIF. Max size: 5MB
                                            </small>

                                            <?php if ($service && !empty($service['image'])): ?>
                                                <div class="mt-3">
                                                    <label class="form-label">Current Image:</label>
                                                    <div class="current-image">
                                                        <img src="../../../<?php echo htmlspecialchars($service['image']); ?>"
                                                             alt="Current service image"
                                                             class="img-thumbnail"
                                                             style="max-width: 200px; max-height: 200px;">
                                                    </div>
                                                </div>
                                            <?php endif; ?>
                                        </div>

                                        <?php if ($service): ?>
                                            <div class="service-info-card">
                                                <h6 class="mb-2">Service Information</h6>
                                                <div class="info-item">
                                                    <small class="text-muted">Created:</small>
                                                    <div><?php echo date('M j, Y', strtotime($service['created_at'])); ?></div>
                                                </div>
                                                <div class="info-item">
                                                    <small class="text-muted">Last Updated:</small>
                                                    <div><?php echo date('M j, Y', strtotime($service['updated_at'])); ?></div>
                                                </div>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Form Actions -->
                        <div class="form-actions mt-4">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i>
                                <?php echo $isEdit ? 'Update' : 'Save'; ?> Service
                            </button>
                            <a href="all-services.php" class="btn btn-secondary">
                                <i class="fas fa-times"></i>
                                Cancel
                            </a>
                        </div>
                    </form>
                </div>
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
        document.getElementById('serviceForm').addEventListener('submit', function(e) {
            const name = document.getElementById('name').value.trim();
            const shortDescription = document.getElementById('short_description').value.trim();
            const description = tinymce.get('description').getContent();
            const categoryId = document.getElementById('category_id').value;

            if (!name) {
                e.preventDefault();
                alert('Please enter a service name.');
                return false;
            }

            if (!shortDescription) {
                e.preventDefault();
                alert('Please enter a short description.');
                return false;
            }

            if (!description) {
                e.preventDefault();
                alert('Please enter a full description.');
                return false;
            }

            if (!categoryId) {
                e.preventDefault();
                alert('Please select a category.');
                return false;
            }
        });

        // Character counter for short description
        document.getElementById('short_description').addEventListener('input', function() {
            const maxLength = 200;
            const currentLength = this.value.length;
            const remaining = maxLength - currentLength;

            // Create or update character counter
            let counter = this.parentElement.querySelector('.char-counter');
            if (!counter) {
                counter = document.createElement('small');
                counter.className = 'char-counter form-text';
                this.parentElement.appendChild(counter);
            }

            counter.textContent = `${currentLength}/${maxLength} characters`;
            counter.className = `char-counter form-text ${remaining < 20 ? 'text-warning' : 'text-muted'}`;

            if (currentLength > maxLength) {
                counter.className = 'char-counter form-text text-danger';
                this.setCustomValidity('Description is too long');
            } else {
                this.setCustomValidity('');
            }
        });
    </script>
</body>
</html>
