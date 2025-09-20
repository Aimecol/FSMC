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
$research = null;
$isEdit = false;
$errors = [];
$success = '';

// Check if editing existing research project
if (isset($_GET['id']) && !empty($_GET['id'])) {
    $researchId = intval($_GET['id']);
    $research = getRecordById('research_projects', $researchId);
    if ($research) {
        $isEdit = true;
    } else {
        $errors[] = 'Research project not found.';
    }
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate and sanitize input
    $title = sanitizeInput($_POST['title'] ?? '');
    $description = sanitizeInput($_POST['description'] ?? '');
    $abstract = sanitizeInput($_POST['abstract'] ?? '');
    $category_id = intval($_POST['category_id'] ?? 0);
    $status = sanitizeInput($_POST['status'] ?? 'Active');
    $start_date = sanitizeInput($_POST['start_date'] ?? '');
    $end_date = sanitizeInput($_POST['end_date'] ?? '');
    $budget = floatval($_POST['budget'] ?? 0);
    $funding_source = sanitizeInput($_POST['funding_source'] ?? '');
    $principal_investigator = sanitizeInput($_POST['principal_investigator'] ?? '');
    $team_members = sanitizeInput($_POST['team_members'] ?? '');
    $objectives = sanitizeInput($_POST['objectives'] ?? '');
    $methodology = sanitizeInput($_POST['methodology'] ?? '');
    $expected_outcomes = sanitizeInput($_POST['expected_outcomes'] ?? '');
    $keywords = sanitizeInput($_POST['keywords'] ?? '');

    // Validation
    if (empty($title)) {
        $errors[] = 'Project title is required.';
    }
    if (empty($description)) {
        $errors[] = 'Project description is required.';
    }
    if (empty($abstract)) {
        $errors[] = 'Project abstract is required.';
    }
    if ($category_id <= 0) {
        $errors[] = 'Please select a valid category.';
    }
    if (!empty($start_date) && !empty($end_date) && strtotime($start_date) > strtotime($end_date)) {
        $errors[] = 'End date must be after start date.';
    }
    if ($budget < 0) {
        $errors[] = 'Budget cannot be negative.';
    }

    // Handle image upload
    $imagePath = $isEdit ? $research['image'] : '';
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $uploadResult = uploadImage($_FILES['image'], '../../../uploads/research/');
        if ($uploadResult['success']) {
            $imagePath = 'uploads/research/' . $uploadResult['filename'];
        } else {
            $errors[] = 'Image upload failed: ' . $uploadResult['error'];
        }
    }

    // If no errors, save the research project
    if (empty($errors)) {
        $researchData = [
            'title' => $title,
            'description' => $description,
            'abstract' => $abstract,
            'category_id' => $category_id,
            'status' => $status,
            'start_date' => $start_date ?: null,
            'end_date' => $end_date ?: null,
            'budget' => $budget,
            'funding_source' => $funding_source,
            'principal_investigator' => $principal_investigator,
            'team_members' => $team_members,
            'objectives' => $objectives,
            'methodology' => $methodology,
            'expected_outcomes' => $expected_outcomes,
            'keywords' => $keywords,
            'image' => $imagePath
        ];

        if ($isEdit) {
            $result = updateRecord('research_projects', $researchData, $researchId);
            $success = $result ? 'Research project updated successfully!' : 'Failed to update research project.';
        } else {
            $result = insertRecord('research_projects', $researchData);
            $success = $result ? 'Research project added successfully!' : 'Failed to add research project.';
        }

        if ($result) {
            // Redirect to research list after successful save
            header('Location: all-research.php?success=' . urlencode($success));
            exit;
        } else {
            $errors[] = $success;
        }
    }
}

// Get research categories for dropdown
$categories = getAllRecords('research_categories', 'name ASC');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $isEdit ? 'Edit' : 'Add'; ?> Research Project | Fair Surveying & Mapping Ltd</title>
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
                <h1 class="page-title"><?php echo $isEdit ? 'Edit' : 'Add'; ?> Research Project</h1>
                <a href="all-research.php" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i>
                    Back to Research
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

            <!-- Research Form -->
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">
                        <i class="fas fa-flask"></i>
                        Research Project Information
                    </h5>
                </div>
                <div class="card-body">
                    <form method="POST" enctype="multipart/form-data" id="researchForm">
                        <div class="row">
                            <!-- Basic Information -->
                            <div class="col-lg-8">
                                <div class="form-group mb-3">
                                    <label for="title" class="form-label">Project Title <span class="text-danger">*</span></label>
                                    <input type="text"
                                           class="form-control"
                                           id="title"
                                           name="title"
                                           value="<?php echo $research ? htmlspecialchars($research['title']) : ''; ?>"
                                           required>
                                </div>

                                <div class="form-group mb-3">
                                    <label for="abstract" class="form-label">Abstract <span class="text-danger">*</span></label>
                                    <textarea class="form-control"
                                              id="abstract"
                                              name="abstract"
                                              rows="4"
                                              placeholder="Brief summary of the research project"
                                              required><?php echo $research ? htmlspecialchars($research['abstract']) : ''; ?></textarea>
                                </div>

                                <div class="form-group mb-3">
                                    <label for="description" class="form-label">Full Description <span class="text-danger">*</span></label>
                                    <textarea class="form-control tinymce"
                                              id="description"
                                              name="description"
                                              rows="8"
                                              required><?php echo $research ? htmlspecialchars($research['description']) : ''; ?></textarea>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label for="category_id" class="form-label">Category <span class="text-danger">*</span></label>
                                            <select class="form-control" id="category_id" name="category_id" required>
                                                <option value="">Select Category</option>
                                                <?php foreach ($categories as $category): ?>
                                                    <option value="<?php echo $category['id']; ?>"
                                                            <?php echo ($research && $research['category_id'] == $category['id']) ? 'selected' : ''; ?>>
                                                        <?php echo htmlspecialchars($category['name']); ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label for="principal_investigator" class="form-label">Principal Investigator</label>
                                            <input type="text"
                                                   class="form-control"
                                                   id="principal_investigator"
                                                   name="principal_investigator"
                                                   value="<?php echo $research ? htmlspecialchars($research['principal_investigator']) : ''; ?>"
                                                   placeholder="Lead researcher name">
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
                                                   value="<?php echo $research ? $research['start_date'] : ''; ?>">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label for="end_date" class="form-label">End Date</label>
                                            <input type="date"
                                                   class="form-control"
                                                   id="end_date"
                                                   name="end_date"
                                                   value="<?php echo $research ? $research['end_date'] : ''; ?>">
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label for="budget" class="form-label">Budget (USD)</label>
                                            <div class="input-group">
                                                <span class="input-group-text">$</span>
                                                <input type="number"
                                                       class="form-control"
                                                       id="budget"
                                                       name="budget"
                                                       step="0.01"
                                                       min="0"
                                                       value="<?php echo $research ? $research['budget'] : ''; ?>"
                                                       placeholder="0.00">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label for="funding_source" class="form-label">Funding Source</label>
                                            <input type="text"
                                                   class="form-control"
                                                   id="funding_source"
                                                   name="funding_source"
                                                   value="<?php echo $research ? htmlspecialchars($research['funding_source']) : ''; ?>"
                                                   placeholder="e.g., Government, Private, University">
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group mb-3">
                                    <label for="team_members" class="form-label">Team Members</label>
                                    <textarea class="form-control"
                                              id="team_members"
                                              name="team_members"
                                              rows="3"
                                              placeholder="List team members and their roles"><?php echo $research ? htmlspecialchars($research['team_members']) : ''; ?></textarea>
                                </div>

                                <div class="form-group mb-3">
                                    <label for="objectives" class="form-label">Research Objectives</label>
                                    <textarea class="form-control tinymce"
                                              id="objectives"
                                              name="objectives"
                                              rows="6"><?php echo $research ? htmlspecialchars($research['objectives']) : ''; ?></textarea>
                                </div>

                                <div class="form-group mb-3">
                                    <label for="methodology" class="form-label">Methodology</label>
                                    <textarea class="form-control tinymce"
                                              id="methodology"
                                              name="methodology"
                                              rows="6"><?php echo $research ? htmlspecialchars($research['methodology']) : ''; ?></textarea>
                                </div>

                                <div class="form-group mb-3">
                                    <label for="expected_outcomes" class="form-label">Expected Outcomes</label>
                                    <textarea class="form-control tinymce"
                                              id="expected_outcomes"
                                              name="expected_outcomes"
                                              rows="4"><?php echo $research ? htmlspecialchars($research['expected_outcomes']) : ''; ?></textarea>
                                </div>

                                <div class="form-group mb-3">
                                    <label for="keywords" class="form-label">Keywords</label>
                                    <input type="text"
                                           class="form-control"
                                           id="keywords"
                                           name="keywords"
                                           value="<?php echo $research ? htmlspecialchars($research['keywords']) : ''; ?>"
                                           placeholder="Separate keywords with commas">
                                    <small class="form-text text-muted">e.g., GIS, Remote Sensing, Mapping, Surveying</small>
                                </div>

                            <!-- Sidebar -->
                            <div class="col-lg-4">
                                <div class="card">
                                    <div class="card-header">
                                        <h6 class="card-title">Project Settings</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="form-group mb-3">
                                            <label for="status" class="form-label">Status</label>
                                            <select class="form-control" id="status" name="status">
                                                <option value="Active" <?php echo (!$research || $research['status'] == 'Active') ? 'selected' : ''; ?>>Active</option>
                                                <option value="In Progress" <?php echo ($research && $research['status'] == 'In Progress') ? 'selected' : ''; ?>>In Progress</option>
                                                <option value="Completed" <?php echo ($research && $research['status'] == 'Completed') ? 'selected' : ''; ?>>Completed</option>
                                                <option value="On Hold" <?php echo ($research && $research['status'] == 'On Hold') ? 'selected' : ''; ?>>On Hold</option>
                                                <option value="Cancelled" <?php echo ($research && $research['status'] == 'Cancelled') ? 'selected' : ''; ?>>Cancelled</option>
                                            </select>
                                        </div>

                                        <div class="form-group mb-3">
                                            <label for="image" class="form-label">Project Image</label>
                                            <input type="file"
                                                   class="form-control"
                                                   id="image"
                                                   name="image"
                                                   accept="image/*">
                                            <small class="form-text text-muted">
                                                Supported formats: JPG, PNG, GIF. Max size: 5MB
                                            </small>

                                            <?php if ($research && !empty($research['image'])): ?>
                                                <div class="mt-3">
                                                    <label class="form-label">Current Image:</label>
                                                    <div class="current-image">
                                                        <img src="../../../<?php echo htmlspecialchars($research['image']); ?>"
                                                             alt="Current research image"
                                                             class="img-thumbnail"
                                                             style="max-width: 200px; max-height: 200px;">
                                                    </div>
                                                </div>
                                            <?php endif; ?>
                                        </div>

                                        <?php if ($research): ?>
                                            <div class="research-info-card">
                                                <h6 class="mb-2">Project Information</h6>
                                                <div class="info-item">
                                                    <small class="text-muted">Created:</small>
                                                    <div><?php echo date('M j, Y', strtotime($research['created_at'])); ?></div>
                                                </div>
                                                <div class="info-item">
                                                    <small class="text-muted">Last Updated:</small>
                                                    <div><?php echo date('M j, Y', strtotime($research['updated_at'])); ?></div>
                                                </div>
                                                <?php if (!empty($research['start_date'])): ?>
                                                    <div class="info-item">
                                                        <small class="text-muted">Duration:</small>
                                                        <div>
                                                            <?php
                                                            $start = new DateTime($research['start_date']);
                                                            $end = !empty($research['end_date']) ? new DateTime($research['end_date']) : new DateTime();
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
                                <?php echo $isEdit ? 'Update' : 'Save'; ?> Research Project
                            </button>
                            <a href="all-research.php" class="btn btn-secondary">
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
        document.getElementById('researchForm').addEventListener('submit', function(e) {
            const title = document.getElementById('title').value.trim();
            const abstract = document.getElementById('abstract').value.trim();
            const description = tinymce.get('description').getContent();
            const categoryId = document.getElementById('category_id').value;

            if (!title) {
                e.preventDefault();
                alert('Please enter a project title.');
                return false;
            }

            if (!abstract) {
                e.preventDefault();
                alert('Please enter an abstract.');
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
        });

        // Character counter for abstract
        document.getElementById('abstract').addEventListener('input', function() {
            const maxLength = 500;
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
            counter.className = `char-counter form-text ${remaining < 50 ? 'text-warning' : 'text-muted'}`;

            if (currentLength > maxLength) {
                counter.className = 'char-counter form-text text-danger';
                this.setCustomValidity('Abstract is too long');
            } else {
                this.setCustomValidity('');
            }
        });

        // Auto-generate keywords from title and abstract
        function generateKeywords() {
            const title = document.getElementById('title').value;
            const abstract = document.getElementById('abstract').value;
            const keywordsField = document.getElementById('keywords');

            if (keywordsField.value.trim() === '' && (title || abstract)) {
                const text = (title + ' ' + abstract).toLowerCase();
                const commonWords = ['the', 'and', 'or', 'but', 'in', 'on', 'at', 'to', 'for', 'of', 'with', 'by', 'from', 'up', 'about', 'into', 'through', 'during', 'before', 'after', 'above', 'below', 'between', 'among', 'is', 'are', 'was', 'were', 'be', 'been', 'being', 'have', 'has', 'had', 'do', 'does', 'did', 'will', 'would', 'could', 'should', 'may', 'might', 'must', 'can', 'this', 'that', 'these', 'those', 'a', 'an'];

                const words = text.match(/\b\w{4,}\b/g) || [];
                const keywords = [...new Set(words)]
                    .filter(word => !commonWords.includes(word))
                    .slice(0, 8)
                    .join(', ');

                if (keywords) {
                    keywordsField.value = keywords;
                }
            }
        }

        // Add event listeners for auto-keyword generation
        document.getElementById('title').addEventListener('blur', generateKeywords);
        document.getElementById('abstract').addEventListener('blur', generateKeywords);
    </script>
</body>
</html>