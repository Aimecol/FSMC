<?php
/**
 * User Edit/Add for FSMC Admin System
 * Created: 2025-01-22
 */

require_once 'config/config.php';

// Check permissions
if (!hasPermission('delete')) {
    setErrorMessage('Access denied. You do not have permission to manage users.');
    redirect('index.php');
}

$userId = intval($_GET['id'] ?? 0);
$isEdit = $userId > 0;

// Set page variables
$pageTitle = $isEdit ? 'Edit User' : 'Add New User';
$pageIcon = $isEdit ? 'fas fa-user-edit' : 'fas fa-user-plus';
$pageDescription = $isEdit ? 'Edit user account details' : 'Create a new admin user account';

// Get user data if editing
$user = null;
if ($isEdit) {
    $user = dbGetRow("SELECT * FROM admin_users WHERE id = ?", [$userId]);
    if (!$user) {
        setErrorMessage('User not found.');
        redirect('users.php');
    }
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verifyCSRFToken($_POST['csrf_token'] ?? '')) {
        setErrorMessage('Invalid security token.');
        redirect($isEdit ? "user_edit.php?id=$userId" : 'user_edit.php');
    }
    
    $username = sanitize($_POST['username'] ?? '');
    $email = sanitize($_POST['email'] ?? '');
    $fullName = sanitize($_POST['full_name'] ?? '');
    $role = sanitize($_POST['role'] ?? '');
    $status = sanitize($_POST['status'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirmPassword = $_POST['confirm_password'] ?? '';
    
    $errors = [];
    
    // Validation
    if (empty($username)) {
        $errors[] = 'Username is required.';
    } elseif (!preg_match('/^[a-zA-Z0-9_]{3,20}$/', $username)) {
        $errors[] = 'Username must be 3-20 characters and contain only letters, numbers, and underscores.';
    }
    
    if (empty($email)) {
        $errors[] = 'Email is required.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Please enter a valid email address.';
    }
    
    if (empty($fullName)) {
        $errors[] = 'Full name is required.';
    }
    
    if (!in_array($role, ['super_admin', 'admin', 'editor'])) {
        $errors[] = 'Please select a valid role.';
    }
    
    if (!in_array($status, ['active', 'inactive'])) {
        $errors[] = 'Please select a valid status.';
    }
    
    // Check for duplicate username/email
    $duplicateCheck = "SELECT id FROM admin_users WHERE (username = ? OR email = ?)";
    $duplicateParams = [$username, $email];
    
    if ($isEdit) {
        $duplicateCheck .= " AND id != ?";
        $duplicateParams[] = $userId;
    }
    
    $duplicate = dbGetRow($duplicateCheck, $duplicateParams);
    if ($duplicate) {
        $errors[] = 'Username or email already exists.';
    }
    
    // Password validation
    if (!$isEdit || !empty($password)) {
        if (empty($password)) {
            $errors[] = 'Password is required.';
        } elseif (strlen($password) < 8) {
            $errors[] = 'Password must be at least 8 characters long.';
        } elseif ($password !== $confirmPassword) {
            $errors[] = 'Passwords do not match.';
        }
    }
    
    if (empty($errors)) {
        try {
            if ($isEdit) {
                // Update user
                $updateData = [
                    'username' => $username,
                    'email' => $email,
                    'full_name' => $fullName,
                    'role' => $role,
                    'status' => $status
                ];
                
                $sql = "UPDATE admin_users SET username = ?, email = ?, full_name = ?, role = ?, status = ?, updated_at = NOW()";
                $params = [$username, $email, $fullName, $role, $status];
                
                if (!empty($password)) {
                    $sql .= ", password_hash = ?";
                    $params[] = password_hash($password, PASSWORD_DEFAULT);
                    $updateData['password_changed'] = true;
                }
                
                $sql .= " WHERE id = ?";
                $params[] = $userId;
                
                if (dbExecute($sql, $params)) {
                    logActivity('User Updated', 'admin_users', $userId, $user, $updateData);
                    setSuccessMessage('User updated successfully.');
                    redirect('users.php');
                } else {
                    $errors[] = 'Failed to update user.';
                }
            } else {
                // Create new user
                $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
                
                $sql = "INSERT INTO admin_users (username, email, full_name, password_hash, role, status)
                        VALUES (?, ?, ?, ?, ?, ?)";
                $params = [$username, $email, $fullName, $hashedPassword, $role, $status];
                
                $newUserId = dbInsert($sql, $params);
                if ($newUserId) {
                    $userData = [
                        'username' => $username,
                        'email' => $email,
                        'full_name' => $fullName,
                        'role' => $role,
                        'status' => $status
                    ];
                    
                    logActivity('User Created', 'admin_users', $newUserId, null, $userData);
                    setSuccessMessage('User created successfully.');
                    redirect('users.php');
                } else {
                    $errors[] = 'Failed to create user.';
                }
            }
        } catch (Exception $e) {
            error_log("User save error: " . $e->getMessage());
            $errors[] = 'An error occurred while saving the user.';
        }
    }
    
    if (!empty($errors)) {
        setErrorMessage(implode('<br>', $errors));
    }
}

include 'includes/header.php';
?>

<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="<?php echo $pageIcon; ?>"></i>
                    <?php echo $pageTitle; ?>
                </h3>
            </div>
            
            <form method="POST">
                <input type="hidden" name="csrf_token" value="<?php echo generateCSRFToken(); ?>">
                
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="username" class="form-label required">Username</label>
                                <input type="text" id="username" name="username" class="form-control" 
                                       value="<?php echo htmlspecialchars($user['username'] ?? ''); ?>" 
                                       required maxlength="20" pattern="[a-zA-Z0-9_]{3,20}"
                                       title="3-20 characters, letters, numbers, and underscores only">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="email" class="form-label required">Email Address</label>
                                <input type="email" id="email" name="email" class="form-control" 
                                       value="<?php echo htmlspecialchars($user['email'] ?? ''); ?>" 
                                       required maxlength="100">
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="full_name" class="form-label required">Full Name</label>
                        <input type="text" id="full_name" name="full_name" class="form-control" 
                               value="<?php echo htmlspecialchars($user['full_name'] ?? ''); ?>" 
                               required maxlength="100">
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="role" class="form-label required">Role</label>
                                <select id="role" name="role" class="form-control" required>
                                    <option value="">Select Role</option>
                                    <option value="editor" <?php echo ($user['role'] ?? '') === 'editor' ? 'selected' : ''; ?>>
                                        Editor
                                    </option>
                                    <option value="admin" <?php echo ($user['role'] ?? '') === 'admin' ? 'selected' : ''; ?>>
                                        Admin
                                    </option>
                                    <option value="super_admin" <?php echo ($user['role'] ?? '') === 'super_admin' ? 'selected' : ''; ?>>
                                        Super Admin
                                    </option>
                                </select>
                                <div class="form-text">
                                    <strong>Editor:</strong> Can manage content<br>
                                    <strong>Admin:</strong> Can manage content and users<br>
                                    <strong>Super Admin:</strong> Full system access
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="status" class="form-label required">Status</label>
                                <select id="status" name="status" class="form-control" required>
                                    <option value="">Select Status</option>
                                    <option value="active" <?php echo ($user['status'] ?? 'active') === 'active' ? 'selected' : ''; ?>>
                                        Active
                                    </option>
                                    <option value="inactive" <?php echo ($user['status'] ?? '') === 'inactive' ? 'selected' : ''; ?>>
                                        Inactive
                                    </option>
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <hr>
                    <h5>Password</h5>
                    <?php if ($isEdit): ?>
                        <p class="text-muted">Leave password fields empty to keep current password.</p>
                    <?php endif; ?>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="password" class="form-label <?php echo $isEdit ? '' : 'required'; ?>">
                                    <?php echo $isEdit ? 'New Password' : 'Password'; ?>
                                </label>
                                <input type="password" id="password" name="password" class="form-control" 
                                       <?php echo $isEdit ? '' : 'required'; ?> minlength="8">
                                <div class="form-text">Minimum 8 characters</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="confirm_password" class="form-label <?php echo $isEdit ? '' : 'required'; ?>">
                                    Confirm Password
                                </label>
                                <input type="password" id="confirm_password" name="confirm_password" class="form-control" 
                                       <?php echo $isEdit ? '' : 'required'; ?> minlength="8">
                            </div>
                        </div>
                    </div>
                    
                    <?php if ($isEdit && $user): ?>
                    <hr>
                    <h5>Account Information</h5>
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>Created:</strong> <?php echo date('M j, Y H:i', strtotime($user['created_at'])); ?></p>
                            <p><strong>Last Updated:</strong> 
                                <?php echo $user['updated_at'] ? date('M j, Y H:i', strtotime($user['updated_at'])) : 'Never'; ?>
                            </p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Last Login:</strong> 
                                <?php echo $user['last_login'] ? date('M j, Y H:i', strtotime($user['last_login'])) : 'Never'; ?>
                            </p>
                            <p><strong>Failed Logins:</strong> <?php echo $user['failed_login_attempts'] ?? 0; ?></p>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
                
                <div class="card-footer">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> 
                        <?php echo $isEdit ? 'Update User' : 'Create User'; ?>
                    </button>
                    <a href="users.php" class="btn btn-secondary">
                        <i class="fas fa-times"></i> Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Password confirmation validation
    const password = document.getElementById('password');
    const confirmPassword = document.getElementById('confirm_password');
    
    function validatePasswords() {
        if (password.value && confirmPassword.value) {
            if (password.value !== confirmPassword.value) {
                confirmPassword.setCustomValidity('Passwords do not match');
            } else {
                confirmPassword.setCustomValidity('');
            }
        } else {
            confirmPassword.setCustomValidity('');
        }
    }
    
    password.addEventListener('input', validatePasswords);
    confirmPassword.addEventListener('input', validatePasswords);
    
    // Username validation
    const username = document.getElementById('username');
    username.addEventListener('input', function() {
        const value = this.value;
        const pattern = /^[a-zA-Z0-9_]{3,20}$/;
        
        if (value && !pattern.test(value)) {
            this.setCustomValidity('Username must be 3-20 characters and contain only letters, numbers, and underscores');
        } else {
            this.setCustomValidity('');
        }
    });
});
</script>

<?php include 'includes/footer.php'; ?>
