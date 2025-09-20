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
$user = null;
$isEdit = false;
$errors = [];
$success = '';

// Check if editing existing user
if (isset($_GET['id']) && !empty($_GET['id'])) {
    $userId = intval($_GET['id']);
    $user = getRecordById('users', $userId);
    if ($user) {
        $isEdit = true;
    } else {
        $errors[] = 'User not found.';
    }
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate and sanitize input
    $firstName = sanitizeInput($_POST['first_name'] ?? '');
    $lastName = sanitizeInput($_POST['last_name'] ?? '');
    $email = sanitizeInput($_POST['email'] ?? '');
    $phone = sanitizeInput($_POST['phone'] ?? '');
    $address = sanitizeInput($_POST['address'] ?? '');
    $role = sanitizeInput($_POST['role'] ?? 'User');
    $status = sanitizeInput($_POST['status'] ?? 'Active');
    $password = $_POST['password'] ?? '';
    $confirmPassword = $_POST['confirm_password'] ?? '';

    // Validation
    if (empty($firstName)) {
        $errors[] = 'First name is required.';
    }
    if (empty($lastName)) {
        $errors[] = 'Last name is required.';
    }
    if (empty($email)) {
        $errors[] = 'Email address is required.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Please enter a valid email address.';
    }

    // Check for duplicate email (excluding current user if editing)
    $mysqli = getDatabaseConnection();
    if ($mysqli) {
        $emailCheckQuery = "SELECT id FROM users WHERE email = ? " . ($isEdit ? "AND id != $userId" : "");
        $stmt = $mysqli->prepare($emailCheckQuery);
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            $errors[] = 'Email address already exists. Please use a different email.';
        }
        $stmt->close();
    }

    // Password validation (required for new users, optional for editing)
    if (!$isEdit) {
        if (empty($password)) {
            $errors[] = 'Password is required for new users.';
        } elseif (strlen($password) < 6) {
            $errors[] = 'Password must be at least 6 characters long.';
        } elseif ($password !== $confirmPassword) {
            $errors[] = 'Passwords do not match.';
        }
    } else {
        // For editing, only validate password if provided
        if (!empty($password)) {
            if (strlen($password) < 6) {
                $errors[] = 'Password must be at least 6 characters long.';
            } elseif ($password !== $confirmPassword) {
                $errors[] = 'Passwords do not match.';
            }
        }
    }

    // Handle avatar upload
    $avatarPath = $isEdit ? $user['avatar'] : '';
    if (isset($_FILES['avatar']) && $_FILES['avatar']['error'] === UPLOAD_ERR_OK) {
        $uploadResult = uploadImage($_FILES['avatar'], '../../../uploads/avatars/');
        if ($uploadResult['success']) {
            $avatarPath = 'uploads/avatars/' . $uploadResult['filename'];
        } else {
            $errors[] = 'Avatar upload failed: ' . $uploadResult['error'];
        }
    }

    // If no errors, save the user
    if (empty($errors)) {
        $userData = [
            'first_name' => $firstName,
            'last_name' => $lastName,
            'email' => $email,
            'phone' => $phone,
            'address' => $address,
            'role' => $role,
            'status' => $status,
            'avatar' => $avatarPath
        ];

        // Add password to data if provided
        if (!empty($password)) {
            $userData['password'] = hashPassword($password);
        }

        if ($isEdit) {
            $result = updateRecord('users', $userData, $userId);
            $success = $result ? 'User updated successfully!' : 'Failed to update user.';
        } else {
            $result = insertRecord('users', $userData);
            $success = $result ? 'User added successfully!' : 'Failed to add user.';
        }

        if ($result) {
            // Redirect to users list after successful save
            header('Location: all-users.php?success=' . urlencode($success));
            exit;
        } else {
            $errors[] = $success;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $isEdit ? 'Edit' : 'Add'; ?> User | Fair Surveying & Mapping Ltd</title>
    <link rel="icon" type="image/svg+xml" href="../../../images/logo.png">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
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
                <h1 class="page-title"><?php echo $isEdit ? 'Edit' : 'Add'; ?> User</h1>
                <a href="all-users.php" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i>
                    Back to Users
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

            <!-- User Form -->
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">
                        <i class="fas fa-user"></i>
                        User Information
                    </h5>
                </div>
                <div class="card-body">
                    <form method="POST" enctype="multipart/form-data" id="userForm">
                        <div class="row">
                            <!-- Basic Information -->
                            <div class="col-lg-8">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label for="first_name" class="form-label">First Name <span class="text-danger">*</span></label>
                                            <input type="text"
                                                   class="form-control"
                                                   id="first_name"
                                                   name="first_name"
                                                   value="<?php echo $user ? htmlspecialchars($user['first_name']) : ''; ?>"
                                                   required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label for="last_name" class="form-label">Last Name <span class="text-danger">*</span></label>
                                            <input type="text"
                                                   class="form-control"
                                                   id="last_name"
                                                   name="last_name"
                                                   value="<?php echo $user ? htmlspecialchars($user['last_name']) : ''; ?>"
                                                   required>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group mb-3">
                                    <label for="email" class="form-label">Email Address <span class="text-danger">*</span></label>
                                    <input type="email"
                                           class="form-control"
                                           id="email"
                                           name="email"
                                           value="<?php echo $user ? htmlspecialchars($user['email']) : ''; ?>"
                                           required>
                                </div>

                                <div class="form-group mb-3">
                                    <label for="phone" class="form-label">Phone Number</label>
                                    <input type="tel"
                                           class="form-control"
                                           id="phone"
                                           name="phone"
                                           value="<?php echo $user ? htmlspecialchars($user['phone']) : ''; ?>">
                                </div>

                                <div class="form-group mb-3">
                                    <label for="address" class="form-label">Address</label>
                                    <textarea class="form-control"
                                              id="address"
                                              name="address"
                                              rows="3"><?php echo $user ? htmlspecialchars($user['address']) : ''; ?></textarea>
                                </div>

                                <!-- Password Fields -->
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label for="password" class="form-label">
                                                Password
                                                <?php if (!$isEdit): ?>
                                                    <span class="text-danger">*</span>
                                                <?php else: ?>
                                                    <small class="text-muted">(leave blank to keep current)</small>
                                                <?php endif; ?>
                                            </label>
                                            <input type="password"
                                                   class="form-control"
                                                   id="password"
                                                   name="password"
                                                   <?php echo !$isEdit ? 'required' : ''; ?>>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label for="confirm_password" class="form-label">
                                                Confirm Password
                                                <?php if (!$isEdit): ?>
                                                    <span class="text-danger">*</span>
                                                <?php endif; ?>
                                            </label>
                                            <input type="password"
                                                   class="form-control"
                                                   id="confirm_password"
                                                   name="confirm_password"
                                                   <?php echo !$isEdit ? 'required' : ''; ?>>
                                        </div>
                                    </div>
                                </div>

                            <!-- Sidebar -->
                            <div class="col-lg-4">
                                <div class="card">
                                    <div class="card-header">
                                        <h6 class="card-title">User Settings</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="form-group mb-3">
                                            <label for="role" class="form-label">User Role <span class="text-danger">*</span></label>
                                            <select class="form-control" id="role" name="role" required>
                                                <option value="">Select Role</option>
                                                <option value="Super Admin" <?php echo ($user && $user['role'] == 'Super Admin') ? 'selected' : ''; ?>>Super Admin</option>
                                                <option value="Admin" <?php echo ($user && $user['role'] == 'Admin') ? 'selected' : ''; ?>>Admin</option>
                                                <option value="Staff" <?php echo ($user && $user['role'] == 'Staff') ? 'selected' : ''; ?>>Staff</option>
                                                <option value="User" <?php echo (!$user || $user['role'] == 'User') ? 'selected' : ''; ?>>User</option>
                                            </select>
                                        </div>

                                        <div class="form-group mb-3">
                                            <label for="status" class="form-label">Status</label>
                                            <select class="form-control" id="status" name="status">
                                                <option value="Active" <?php echo (!$user || $user['status'] == 'Active') ? 'selected' : ''; ?>>Active</option>
                                                <option value="Inactive" <?php echo ($user && $user['status'] == 'Inactive') ? 'selected' : ''; ?>>Inactive</option>
                                                <option value="Suspended" <?php echo ($user && $user['status'] == 'Suspended') ? 'selected' : ''; ?>>Suspended</option>
                                            </select>
                                        </div>

                                        <div class="form-group mb-3">
                                            <label for="avatar" class="form-label">Avatar</label>
                                            <input type="file"
                                                   class="form-control"
                                                   id="avatar"
                                                   name="avatar"
                                                   accept="image/*">
                                            <small class="form-text text-muted">
                                                Supported formats: JPG, PNG, GIF. Max size: 2MB
                                            </small>

                                            <?php if ($user && !empty($user['avatar'])): ?>
                                                <div class="mt-3">
                                                    <label class="form-label">Current Avatar:</label>
                                                    <div class="current-avatar">
                                                        <img src="../../../<?php echo htmlspecialchars($user['avatar']); ?>"
                                                             alt="Current avatar"
                                                             class="img-thumbnail"
                                                             style="max-width: 100px; max-height: 100px;">
                                                    </div>
                                                </div>
                                            <?php endif; ?>
                                        </div>

                                        <?php if ($user): ?>
                                            <div class="user-info-card">
                                                <h6 class="mb-2">Account Information</h6>
                                                <div class="info-item">
                                                    <small class="text-muted">Created:</small>
                                                    <div><?php echo date('M j, Y', strtotime($user['created_at'])); ?></div>
                                                </div>
                                                <?php if (!empty($user['last_active'])): ?>
                                                    <div class="info-item">
                                                        <small class="text-muted">Last Active:</small>
                                                        <div><?php echo date('M j, Y g:i A', strtotime($user['last_active'])); ?></div>
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
                                <?php echo $isEdit ? 'Update' : 'Save'; ?> User
                            </button>
                            <a href="all-users.php" class="btn btn-secondary">
                                <i class="fas fa-times"></i>
                                Cancel
                            </a>
                            <?php if ($isEdit && $currentUser['id'] != $user['id']): ?>
                                <button type="button" class="btn btn-warning" onclick="resetPassword()">
                                    <i class="fas fa-key"></i>
                                    Reset Password
                                </button>
                            <?php endif; ?>
                        </div>
                    </form>
                </div>
            </div>
        </main>
    </div>

    <!-- Admin JavaScript -->
    <script src="../../js/admin.js"></script>

    <script>
        // Form validation
        document.getElementById('userForm').addEventListener('submit', function(e) {
            const firstName = document.getElementById('first_name').value.trim();
            const lastName = document.getElementById('last_name').value.trim();
            const email = document.getElementById('email').value.trim();
            const password = document.getElementById('password').value;
            const confirmPassword = document.getElementById('confirm_password').value;
            const role = document.getElementById('role').value;
            const isEdit = <?php echo $isEdit ? 'true' : 'false'; ?>;

            if (!firstName) {
                e.preventDefault();
                alert('Please enter a first name.');
                return false;
            }

            if (!lastName) {
                e.preventDefault();
                alert('Please enter a last name.');
                return false;
            }

            if (!email) {
                e.preventDefault();
                alert('Please enter an email address.');
                return false;
            }

            // Email validation
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(email)) {
                e.preventDefault();
                alert('Please enter a valid email address.');
                return false;
            }

            if (!role) {
                e.preventDefault();
                alert('Please select a user role.');
                return false;
            }

            // Password validation for new users
            if (!isEdit) {
                if (!password) {
                    e.preventDefault();
                    alert('Please enter a password.');
                    return false;
                }

                if (password.length < 6) {
                    e.preventDefault();
                    alert('Password must be at least 6 characters long.');
                    return false;
                }

                if (password !== confirmPassword) {
                    e.preventDefault();
                    alert('Passwords do not match.');
                    return false;
                }
            } else {
                // For editing, only validate if password is provided
                if (password && password.length < 6) {
                    e.preventDefault();
                    alert('Password must be at least 6 characters long.');
                    return false;
                }

                if (password && password !== confirmPassword) {
                    e.preventDefault();
                    alert('Passwords do not match.');
                    return false;
                }
            }
        });

        // Password reset function
        function resetPassword() {
            if (confirm('Are you sure you want to reset this user\'s password? A new temporary password will be generated and sent to their email.')) {
                // Here you would implement the password reset functionality
                alert('Password reset functionality would be implemented here.');
            }
        }

        // Show/hide password confirmation based on password field
        document.getElementById('password').addEventListener('input', function() {
            const confirmField = document.getElementById('confirm_password');
            if (this.value) {
                confirmField.required = true;
                confirmField.parentElement.style.display = 'block';
            } else {
                confirmField.required = false;
                if (<?php echo $isEdit ? 'true' : 'false'; ?>) {
                    confirmField.parentElement.style.display = 'none';
                }
            }
        });
    </script>
</body>
</html>