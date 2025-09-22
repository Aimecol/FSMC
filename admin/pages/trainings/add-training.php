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
$training = null;
$isEdit = false;
$errors = [];
$success = '';

// Check if editing existing training program
if (isset($_GET['id']) && !empty($_GET['id'])) {
    $trainingId = intval($_GET['id']);
    $training = getRecordById('training_programs', $trainingId);
    if ($training) {
        $isEdit = true;
    } else {
        $errors[] = 'Training program not found.';
    }
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate and sanitize input
    $title = sanitizeInput($_POST['title'] ?? '');
    $description = sanitizeInput($_POST['description'] ?? '');
    $short_description = sanitizeInput($_POST['short_description'] ?? '');
    $category_id = intval($_POST['category_id'] ?? 0);
    $duration = sanitizeInput($_POST['duration'] ?? '');
    $level = sanitizeInput($_POST['level'] ?? '');
    $price = floatval($_POST['price'] ?? 0);
    $max_participants = intval($_POST['max_participants'] ?? 0);
    $status = sanitizeInput($_POST['status'] ?? 'Active');
    $start_date = sanitizeInput($_POST['start_date'] ?? '');
    $end_date = sanitizeInput($_POST['end_date'] ?? '');
    $instructor = sanitizeInput($_POST['instructor'] ?? '');
    $location = sanitizeInput($_POST['location'] ?? '');
    $prerequisites = sanitizeInput($_POST['prerequisites'] ?? '');
    $learning_objectives = sanitizeInput($_POST['learning_objectives'] ?? '');
    $curriculum = sanitizeInput($_POST['curriculum'] ?? '');
    $certification = sanitizeInput($_POST['certification'] ?? '');
    $materials_included = sanitizeInput($_POST['materials_included'] ?? '');

    // Validation
    if (empty($title)) {
        $errors[] = 'Training title is required.';
    }
    if (empty($description)) {
        $errors[] = 'Training description is required.';
    }
    if (empty($short_description)) {
        $errors[] = 'Short description is required.';
    }
    if ($category_id <= 0) {
        $errors[] = 'Please select a valid category.';
    }
    if ($price < 0) {
        $errors[] = 'Training price cannot be negative.';
    }
    if ($max_participants < 0) {
        $errors[] = 'Maximum participants cannot be negative.';
    }
    if (!empty($start_date) && !empty($end_date) && strtotime($start_date) > strtotime($end_date)) {
        $errors[] = 'End date must be after start date.';
    }

    // Handle image upload
    $imagePath = $isEdit ? $training['image'] : '';
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $uploadResult = uploadImage($_FILES['image'], '../../../uploads/trainings/');
        if ($uploadResult['success']) {
            $imagePath = 'uploads/trainings/' . $uploadResult['filename'];
        } else {
            $errors[] = 'Image upload failed: ' . $uploadResult['error'];
        }
    }

    // If no errors, save the training program
    if (empty($errors)) {
        $trainingData = [
            'title' => $title,
            'description' => $description,
            'short_description' => $short_description,
            'category_id' => $category_id,
            'duration' => $duration,
            'level' => $level,
            'price' => $price,
            'max_participants' => $max_participants,
            'status' => $status,
            'start_date' => $start_date ?: null,
            'end_date' => $end_date ?: null,
            'instructor' => $instructor,
            'location' => $location,
            'prerequisites' => $prerequisites,
            'learning_objectives' => $learning_objectives,
            'curriculum' => $curriculum,
            'certification' => $certification,
            'materials_included' => $materials_included,
            'image' => $imagePath
        ];

        if ($isEdit) {
            $result = updateRecord('training_programs', $trainingData, $trainingId);
            $success = $result ? 'Training program updated successfully!' : 'Failed to update training program.';
        } else {
            $result = insertRecord('training_programs', $trainingData);
            $success = $result ? 'Training program added successfully!' : 'Failed to add training program.';
        }

        if ($result) {
            // Redirect to training list after successful save
            header('Location: all-trainings.php?success=' . urlencode($success));
            exit;
        } else {
            $errors[] = $success;
        }
    }
}

// Get training categories for dropdown
$categories = getAllRecords('training_categories', '', 'name ASC');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $isEdit ? 'Edit' : 'Add'; ?> Training Program | Fair Surveying & Mapping Ltd</title>
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
                <h1 class="page-title"><?php echo $isEdit ? 'Edit' : 'Add'; ?> Training Program</h1>
                <a href="all-trainings.php" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i>
                    Back to Training Programs
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

            <!-- Training Form -->
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">
                        <i class="fas fa-graduation-cap"></i>
                        Training Program Information
                    </h5>
                </div>
                <div class="card-body">
                    <form method="POST" enctype="multipart/form-data" id="trainingForm">
                        <div class="row">
                            <!-- Basic Information -->
                            <div class="col-lg-8">
                                <div class="form-group mb-3">
                                    <label for="title" class="form-label">Training Title <span class="text-danger">*</span></label>
                                    <input type="text"
                                           class="form-control"
                                           id="title"
                                           name="title"
                                           value="<?php echo $training ? htmlspecialchars($training['title']) : ''; ?>"
                                           required>
                                </div>

                                <div class="form-group mb-3">
                                    <label for="short_description" class="form-label">Short Description <span class="text-danger">*</span></label>
                                    <textarea class="form-control"
                                              id="short_description"
                                              name="short_description"
                                              rows="3"
                                              placeholder="Brief description for training listings"
                                              required><?php echo $training ? htmlspecialchars($training['short_description']) : ''; ?></textarea>
                                </div>

                                <div class="form-group mb-3">
                                    <label for="description" class="form-label">Full Description <span class="text-danger">*</span></label>
                                    <textarea class="form-control tinymce"
                                              id="description"
                                              name="description"
                                              rows="8"
                                              required><?php echo $training ? htmlspecialchars($training['description']) : ''; ?></textarea>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label for="category_id" class="form-label">Category <span class="text-danger">*</span></label>
                                            <select class="form-control" id="category_id" name="category_id" required>
                                                <option value="">Select Category</option>
                                                <?php foreach ($categories as $category): ?>
                                                    <option value="<?php echo $category['id']; ?>"
                                                            <?php echo ($training && $training['category_id'] == $category['id']) ? 'selected' : ''; ?>>
                                                        <?php echo htmlspecialchars($category['name']); ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label for="level" class="form-label">Training Level</label>
                                            <select class="form-control" id="level" name="level">
                                                <option value="">Select Level</option>
                                                <option value="Beginner" <?php echo ($training && $training['level'] == 'Beginner') ? 'selected' : ''; ?>>Beginner</option>
                                                <option value="Intermediate" <?php echo ($training && $training['level'] == 'Intermediate') ? 'selected' : ''; ?>>Intermediate</option>
                                                <option value="Advanced" <?php echo ($training && $training['level'] == 'Advanced') ? 'selected' : ''; ?>>Advanced</option>
                                                <option value="Expert" <?php echo ($training && $training['level'] == 'Expert') ? 'selected' : ''; ?>>Expert</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group mb-3">
                                            <label for="duration" class="form-label">Duration</label>
                                            <input type="text"
                                                   class="form-control"
                                                   id="duration"
                                                   name="duration"
                                                   value="<?php echo $training ? htmlspecialchars($training['duration']) : ''; ?>"
                                                   placeholder="e.g., 3 days, 2 weeks">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
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
                                                       value="<?php echo $training ? $training['price'] : ''; ?>"
                                                       placeholder="0.00">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group mb-3">
                                            <label for="max_participants" class="form-label">Max Participants</label>
                                            <input type="number"
                                                   class="form-control"
                                                   id="max_participants"
                                                   name="max_participants"
                                                   min="1"
                                                   value="<?php echo $training ? $training['max_participants'] : ''; ?>"
                                                   placeholder="e.g., 20">
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label for="start_date" class="form-label">Start Date</label>
                                            <input type="date"
                                                   class="form-control"
                                                   id="start_date"
                                                   name="start_date"
                                                   value="<?php echo $training ? $training['start_date'] : ''; ?>">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label for="end_date" class="form-label">End Date</label>
                                            <input type="date"
                                                   class="form-control"
                                                   id="end_date"
                                                   name="end_date"
                                                   value="<?php echo $training ? $training['end_date'] : ''; ?>">
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label for="instructor" class="form-label">Instructor</label>
                                            <input type="text"
                                                   class="form-control"
                                                   id="instructor"
                                                   name="instructor"
                                                   value="<?php echo $training ? htmlspecialchars($training['instructor']) : ''; ?>"
                                                   placeholder="Instructor name">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label for="location" class="form-label">Location</label>
                                            <input type="text"
                                                   class="form-control"
                                                   id="location"
                                                   name="location"
                                                   value="<?php echo $training ? htmlspecialchars($training['location']) : ''; ?>"
                                                   placeholder="Training location">
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group mb-3">
                                    <label for="prerequisites" class="form-label">Prerequisites</label>
                                    <textarea class="form-control"
                                              id="prerequisites"
                                              name="prerequisites"
                                              rows="3"
                                              placeholder="Required knowledge or experience"><?php echo $training ? htmlspecialchars($training['prerequisites']) : ''; ?></textarea>
                                </div>

                                <div class="form-group mb-3">
                                    <label for="learning_objectives" class="form-label">Learning Objectives</label>
                                    <textarea class="form-control tinymce"
                                              id="learning_objectives"
                                              name="learning_objectives"
                                              rows="6"><?php echo $training ? htmlspecialchars($training['learning_objectives']) : ''; ?></textarea>
                                </div>

                                <div class="form-group mb-3">
                                    <label for="curriculum" class="form-label">Curriculum</label>
                                    <textarea class="form-control tinymce"
                                              id="curriculum"
                                              name="curriculum"
                                              rows="8"><?php echo $training ? htmlspecialchars($training['curriculum']) : ''; ?></textarea>
                                </div>

                                <div class="form-group mb-3">
                                    <label for="materials_included" class="form-label">Materials Included</label>
                                    <textarea class="form-control"
                                              id="materials_included"
                                              name="materials_included"
                                              rows="3"
                                              placeholder="List of materials provided to participants"><?php echo $training ? htmlspecialchars($training['materials_included']) : ''; ?></textarea>
                                </div>

                                <div class="form-group mb-3">
                                    <label for="certification" class="form-label">Certification</label>
                                    <textarea class="form-control"
                                              id="certification"
                                              name="certification"
                                              rows="2"
                                              placeholder="Information about certification provided"><?php echo $training ? htmlspecialchars($training['certification']) : ''; ?></textarea>
                                </div>

                            <!-- Sidebar -->
                            <div class="col-lg-4">
                                <div class="card">
                                    <div class="card-header">
                                        <h6 class="card-title">Training Settings</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="form-group mb-3">
                                            <label for="status" class="form-label">Status</label>
                                            <select class="form-control" id="status" name="status">
                                                <option value="Active" <?php echo (!$training || $training['status'] == 'Active') ? 'selected' : ''; ?>>Active</option>
                                                <option value="Inactive" <?php echo ($training && $training['status'] == 'Inactive') ? 'selected' : ''; ?>>Inactive</option>
                                                <option value="Coming Soon" <?php echo ($training && $training['status'] == 'Coming Soon') ? 'selected' : ''; ?>>Coming Soon</option>
                                                <option value="Full" <?php echo ($training && $training['status'] == 'Full') ? 'selected' : ''; ?>>Full</option>
                                                <option value="Cancelled" <?php echo ($training && $training['status'] == 'Cancelled') ? 'selected' : ''; ?>>Cancelled</option>
                                            </select>
                                        </div>

                                        <div class="form-group mb-3">
                                            <label for="image" class="form-label">Training Image</label>
                                            <input type="file"
                                                   class="form-control"
                                                   id="image"
                                                   name="image"
                                                   accept="image/*">
                                            <small class="form-text text-muted">
                                                Supported formats: JPG, PNG, GIF. Max size: 5MB
                                            </small>

                                            <?php if ($training && !empty($training['image'])): ?>
                                                <div class="mt-3">
                                                    <label class="form-label">Current Image:</label>
                                                    <div class="current-image">
                                                        <img src="../../../<?php echo htmlspecialchars($training['image']); ?>"
                                                             alt="Current training image"
                                                             class="img-thumbnail"
                                                             style="max-width: 200px; max-height: 200px;">
                                                    </div>
                                                </div>
                                            <?php endif; ?>
                                        </div>

                                        <?php if ($training): ?>
                                            <div class="training-info-card">
                                                <h6 class="mb-2">Training Information</h6>
                                                <div class="info-item">
                                                    <small class="text-muted">Created:</small>
                                                    <div><?php echo date('M j, Y', strtotime($training['created_at'])); ?></div>
                                                </div>
                                                <div class="info-item">
                                                    <small class="text-muted">Last Updated:</small>
                                                    <div><?php echo date('M j, Y', strtotime($training['updated_at'])); ?></div>
                                                </div>
                                                <?php if (!empty($training['start_date']) && !empty($training['end_date'])): ?>
                                                    <div class="info-item">
                                                        <small class="text-muted">Duration:</small>
                                                        <div>
                                                            <?php
                                                            $start = new DateTime($training['start_date']);
                                                            $end = new DateTime($training['end_date']);
                                                            $interval = $start->diff($end);
                                                            echo $interval->format('%a days');
                                                            ?>
                                                        </div>
                                                    </div>
                                                <?php endif; ?>
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
                                <?php echo $isEdit ? 'Update' : 'Save'; ?> Training Program
                            </button>
                            <a href="all-trainings.php" class="btn btn-secondary">
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
        document.getElementById('trainingForm').addEventListener('submit', function(e) {
            const title = document.getElementById('title').value.trim();
            const shortDescription = document.getElementById('short_description').value.trim();
            const description = tinymce.get('description').getContent();
            const categoryId = document.getElementById('category_id').value;

            if (!title) {
                e.preventDefault();
                alert('Please enter a training title.');
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

            // Validate date range
            const startDate = document.getElementById('start_date').value;
            const endDate = document.getElementById('end_date').value;

            if (startDate && endDate && new Date(startDate) > new Date(endDate)) {
                e.preventDefault();
                alert('End date must be after start date.');
                return false;
            }

            // Validate max participants
            const maxParticipants = document.getElementById('max_participants').value;
            if (maxParticipants && parseInt(maxParticipants) <= 0) {
                e.preventDefault();
                alert('Maximum participants must be greater than 0.');
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

        // Auto-calculate end date based on duration
        document.getElementById('start_date').addEventListener('change', function() {
            const startDate = new Date(this.value);
            const duration = document.getElementById('duration').value.toLowerCase();

            if (startDate && duration) {
                let endDate = new Date(startDate);

                // Parse duration and calculate end date
                if (duration.includes('day')) {
                    const days = parseInt(duration.match(/\d+/)) || 1;
                    endDate.setDate(startDate.getDate() + days - 1);
                } else if (duration.includes('week')) {
                    const weeks = parseInt(duration.match(/\d+/)) || 1;
                    endDate.setDate(startDate.getDate() + (weeks * 7) - 1);
                } else if (duration.includes('month')) {
                    const months = parseInt(duration.match(/\d+/)) || 1;
                    endDate.setMonth(startDate.getMonth() + months);
                    endDate.setDate(startDate.getDate() - 1);
                }

                // Set the end date field
                document.getElementById('end_date').value = endDate.toISOString().split('T')[0];
            }
        });
    </script>
</body>
</html>