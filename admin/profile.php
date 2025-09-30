<?php
require_once 'config/config.php';
require_once 'config/database.php';

// Check authentication
requireAuth();

// Helper functions (these would typically be in includes/functions.php)

function getUserById($user_id) {
    return dbGetRow("SELECT * FROM admin_users WHERE id = ?", [$user_id]);
}

function updateProfile($user_id, $data) {
    try {
        // Validate input
        if (empty($data['full_name']) || empty($data['username']) || empty($data['email'])) {
            return ['success' => false, 'message' => 'All fields are required'];
        }
        
        // Check if username/email already exists (excluding current user)
        $existing = dbGetRow("SELECT id FROM admin_users WHERE (username = ? OR email = ?) AND id != ?", 
                            [$data['username'], $data['email'], $user_id]);
        if ($existing) {
            return ['success' => false, 'message' => 'Username or email already exists'];
        }
        
        // Handle file upload
        $avatar_path = null;
        if (isset($_FILES['avatar']) && $_FILES['avatar']['error'] === 0) {
            $upload_dir = 'uploads/avatars/';
            if (!is_dir($upload_dir)) {
                mkdir($upload_dir, 0755, true);
            }
            
            $file_extension = pathinfo($_FILES['avatar']['name'], PATHINFO_EXTENSION);
            $avatar_path = $upload_dir . $user_id . '_' . time() . '.' . $file_extension;
            
            if (!move_uploaded_file($_FILES['avatar']['tmp_name'], $avatar_path)) {
                return ['success' => false, 'message' => 'Failed to upload avatar'];
            }
        }
        
        // Update user data
        $sql = "UPDATE admin_users SET full_name = ?, username = ?, email = ?, updated_at = CURRENT_TIMESTAMP";
        $params = [$data['full_name'], $data['username'], $data['email']];
        
        if ($avatar_path) {
            $sql .= ", avatar = ?";
            $params[] = $avatar_path;
        }
        
        $sql .= " WHERE id = ?";
        $params[] = $user_id;
        
        dbExecute($sql, $params);
        
        // Log activity
        logActivity('profile_updated', 'admin_users', $user_id);
        
        return ['success' => true, 'message' => 'Profile updated successfully'];
        
    } catch (Exception $e) {
        error_log($e->getMessage());
        return ['success' => false, 'message' => 'An error occurred while updating profile'];
    }
}

function changePassword($user_id, $data) {
    try {
        // Validate input
        if (empty($data['current_password']) || empty($data['new_password']) || empty($data['confirm_password'])) {
            return ['success' => false, 'message' => 'All password fields are required'];
        }
        
        if ($data['new_password'] !== $data['confirm_password']) {
            return ['success' => false, 'message' => 'New passwords do not match'];
        }
        
        if (strlen($data['new_password']) < PASSWORD_MIN_LENGTH) {
            return ['success' => false, 'message' => 'Password must be at least ' . PASSWORD_MIN_LENGTH . ' characters long'];
        }
        
        // Get current user
        $user = getUserById($user_id);
        if (!$user || !password_verify($data['current_password'], $user['password_hash'])) {
            return ['success' => false, 'message' => 'Current password is incorrect'];
        }
        
        // Update password
        $new_hash = password_hash($data['new_password'], PASSWORD_DEFAULT);
        dbExecute("UPDATE admin_users SET password_hash = ?, updated_at = CURRENT_TIMESTAMP WHERE id = ?", 
                 [$new_hash, $user_id]);
        
        // Log activity
        logActivity('password_changed', 'admin_users', $user_id);
        
        return ['success' => true, 'message' => 'Password changed successfully'];
        
    } catch (Exception $e) {
        error_log($e->getMessage());
        return ['success' => false, 'message' => 'An error occurred while changing password'];
    }
}

function updateUserSettings($user_id, $data) {
    // This would update user preferences in a settings table
    // Implementation depends on your specific settings structure
    logActivity('settings_updated', 'admin_users', $user_id);
    return ['success' => true, 'message' => 'Settings updated successfully'];
}

function getUserActivityLogs($user_id, $limit = 10) {
    return dbGetRows("SELECT * FROM activity_logs WHERE user_id = ? ORDER BY created_at DESC LIMIT ?", 
                    [$user_id, $limit]);
}

function getUserStatistics($user_id) {
    // Mock statistics - implement based on your tracking needs
    return [
        'total_logins' => rand(50, 200),
        'failed_attempts' => rand(0, 5),
        'days_active' => rand(30, 365),
        'profile_updates' => rand(5, 20)
    ];
}

function logUserActivity($user_id, $action, $description) {
    logActivity($action, 'admin_users', $user_id, null, ['description' => $description]);
}

function getActivityIcon($action) {
    $icons = [
        'login' => 'fas fa-sign-in-alt',
        'logout' => 'fas fa-sign-out-alt',
        'profile_updated' => 'fas fa-user-edit',
        'password_changed' => 'fas fa-key',
        'settings_updated' => 'fas fa-cog',
        'default' => 'fas fa-info-circle'
    ];
    
    return $icons[$action] ?? $icons['default'];
}

function timeAgo($datetime) {
    $time = time() - strtotime($datetime);
    
    if ($time < 60) return 'just now';
    if ($time < 3600) return floor($time/60) . ' minutes ago';
    if ($time < 86400) return floor($time/3600) . ' hours ago';
    if ($time < 2592000) return floor($time/86400) . ' days ago';
    
    return date('M d, Y', strtotime($datetime));
}

// Check if user is logged in and get user ID
if (!isLoggedIn()) {
    redirect(ADMIN_BASE_URL . '/login.php');
}

$user_id = getCurrentUserId();
if (!$user_id) {
    setErrorMessage('Session error. Please log in again.');
    redirect(ADMIN_BASE_URL . '/login.php');
}
$current_user = getUserById($user_id);

// Check if user data was retrieved successfully
if (!$current_user) {
    setErrorMessage('Unable to load user profile. Please try logging in again.');
    redirect(ADMIN_BASE_URL . '/login.php');
}

// Handle form submissions
$success_message = '';
$error_message = '';

if ($_POST) {
    $csrf_token = $_POST['csrf_token'] ?? '';
    
    if (!hash_equals($_SESSION['csrf_token'], $csrf_token)) {
        $error_message = 'Invalid CSRF token';
    } else {
        $action = $_POST['action'] ?? '';
        
        switch ($action) {
            case 'update_profile':
                $result = updateProfile($user_id, $_POST);
                if ($result['success']) {
                    $success_message = 'Profile updated successfully!';
                    $current_user = getUserById($user_id); // Refresh user data
                } else {
                    $error_message = $result['message'];
                }
                break;
                
            case 'change_password':
                $result = changePassword($user_id, $_POST);
                if ($result['success']) {
                    $success_message = 'Password changed successfully!';
                } else {
                    $error_message = $result['message'];
                }
                break;
                
            case 'update_settings':
                $result = updateUserSettings($user_id, $_POST);
                if ($result['success']) {
                    $success_message = 'Settings updated successfully!';
                } else {
                    $error_message = $result['message'];
                }
                break;
        }
    }
}

// Generate CSRF token for forms
$_SESSION['csrf_token'] = bin2hex(random_bytes(32));

// Get user activity logs
$activity_logs = getUserActivityLogs($user_id, 10);

// Get user statistics
$user_stats = getUserStatistics($user_id);

include "includes/header.php";
?>

    
    <style>
        .profile-header {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            border-radius: var(--border-radius);
            padding: 2rem;
            margin-bottom: 2rem;
            color: var(--white);
            box-shadow: var(--shadow);
        }

        .profile-avatar {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            border: 4px solid var(--white);
            object-fit: cover;
            box-shadow: var(--shadow);
        }

        .profile-card {
            background: var(--white);
            border-radius: var(--border-radius);
            box-shadow: var(--shadow);
            margin-bottom: 2rem;
            border: none;
            overflow: hidden;
            transition: var(--transition);
        }

        .profile-card:hover {
            box-shadow: var(--shadow-lg);
            transform: translateY(-2px);
        }

        .profile-card .card-header {
            background: linear-gradient(45deg, var(--light-color), var(--border-light));
            border-bottom: 1px solid var(--border-light);
            font-weight: 600;
            color: var(--text-dark);
        }

        .form-control, .form-select {
            border: 2px solid var(--border-light);
            border-radius: var(--border-radius);
            padding: 0.75rem 1rem;
            transition: var(--transition);
        }

        .form-control:focus, .form-select:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.2rem rgba(26, 82, 118, 0.25);
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            border: none;
            border-radius: var(--border-radius);
            padding: 0.75rem 1.5rem;
            font-weight: 600;
            transition: var(--transition);
        }

        .btn-primary:hover {
            background: linear-gradient(135deg, var(--secondary-color), var(--primary-color));
            transform: translateY(-2px);
            box-shadow: var(--shadow);
        }

        .btn-outline-primary {
            border: 2px solid var(--primary-color);
            color: var(--primary-color);
            border-radius: var(--border-radius);
            padding: 0.75rem 1.5rem;
            font-weight: 600;
            transition: var(--transition);
        }

        .btn-outline-primary:hover {
            background: var(--primary-color);
            border-color: var(--primary-color);
            transform: translateY(-2px);
        }

        .alert {
            border: none;
            border-radius: var(--border-radius);
            padding: 1rem 1.5rem;
            margin-bottom: 2rem;
        }

        .alert-success {
            background: linear-gradient(135deg, var(--success-color), #2ecc71);
            color: var(--white);
        }

        .alert-danger {
            background: linear-gradient(135deg, var(--error-color), #c0392b);
            color: var(--white);
        }

        .stats-card {
            background: var(--white);
            border-radius: var(--border-radius);
            padding: 1.5rem;
            text-align: center;
            box-shadow: var(--shadow);
            transition: var(--transition);
            border-left: 4px solid var(--primary-color);
        }

        .stats-card:hover {
            transform: translateY(-5px);
            box-shadow: var(--shadow-lg);
        }

        .stats-number {
            font-size: 2rem;
            font-weight: 700;
            color: var(--primary-color);
            margin-bottom: 0.5rem;
        }

        .stats-label {
            color: var(--text-light);
            font-size: 0.9rem;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .activity-item {
            background: var(--white);
            border-radius: var(--border-radius);
            padding: 1rem;
            margin-bottom: 1rem;
            border-left: 4px solid var(--accent-color);
            box-shadow: var(--shadow);
            transition: var(--transition);
        }

        .activity-item:hover {
            transform: translateX(5px);
        }

        .activity-time {
            color: var(--text-muted);
            font-size: 0.85rem;
        }

        .nav-pills .nav-link {
            border-radius: var(--border-radius);
            color: var(--text-dark);
            font-weight: 500;
            transition: var(--transition);
        }

        .nav-pills .nav-link.active {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: var(--white);
        }

        .nav-pills .nav-link:hover:not(.active) {
            background-color: var(--light-color);
        }

        .status-badge {
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .status-active {
            background: linear-gradient(135deg, var(--success-color), #2ecc71);
            color: var(--white);
        }

        .status-inactive {
            background: linear-gradient(135deg, var(--text-muted), #7f8c8d);
            color: var(--white);
        }

        .password-strength {
            margin-top: 0.5rem;
        }

        .password-strength-bar {
            height: 4px;
            border-radius: 2px;
            background: var(--border-light);
            overflow: hidden;
        }

        .password-strength-fill {
            height: 100%;
            transition: var(--transition);
        }

        .strength-weak { background: var(--error-color); }
        .strength-medium { background: var(--warning-color); }
        .strength-strong { background: var(--success-color); }

        @media (max-width: 768px) {
            .main-content {
                margin-left: 0;
                padding: 1rem;
            }
            
            .profile-header {
                text-align: center;
            }
        }
    </style>
    <!-- Profile Header -->
    <div class="profile-header">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h2 class="mb-1"><?php echo htmlspecialchars($current_user['full_name']); ?></h2>
                <p class="mb-1 opacity-75">@<?php echo htmlspecialchars($current_user['username']); ?></p>
                <p class="mb-2 opacity-75"><?php echo htmlspecialchars($current_user['email']); ?></p>
                <div class="d-flex align-items-center">
                    <span class="status-badge status-<?php echo $current_user['status']; ?>">
                        <?php echo ucfirst($current_user['status']); ?>
                    </span>
                    <span class="ms-3 opacity-75">
                        <i class="fas fa-user-tag"></i> <?php echo ucfirst(str_replace('_', ' ', $current_user['role'])); ?>
                    </span>
                </div>
            </div>
            <div class="col-md-3">
                <div class="text-md-end">
                    <p class="mb-1 opacity-75">
                        <i class="fas fa-calendar"></i> 
                        Joined <?php echo date('M d, Y', strtotime($current_user['created_at'])); ?>
                    </p>
                    <p class="mb-0 opacity-75">
                        <i class="fas fa-clock"></i> 
                        Last login: <?php echo $current_user['last_login'] ? date('M d, Y H:i', strtotime($current_user['last_login'])) : 'Never'; ?>
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Alert Messages -->
    <?php if ($success_message): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>
            <?php echo htmlspecialchars($success_message); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <?php if ($error_message): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i>
            <?php echo htmlspecialchars($error_message); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <!-- Main Content with Tabs -->
    <div class="row">
        <div class="col-12">
            <!-- Tab Content -->
            <div class="tab-content" id="profileTabContent">
                <!-- Profile Information Tab -->
                <div class="tab-pane fade show active" id="profile-content" role="tabpanel">
                    <div class="profile-card">
                        <div class="card-header">
                            <h5 class="mb-0"><i class="fas fa-user-edit me-2"></i>Edit Profile Information</h5>
                        </div>
                        <div class="card-body">
                            <form method="POST" enctype="multipart/form-data">
                                <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                                <input type="hidden" name="action" value="update_profile">
                                
                                <div class="row">
                                    <div class="col-6 mb-3 margin-right: 20px;">
                                        <label for="full_name" class="form-label">Full Name</label>
                                        <input type="text" class="form-control" id="full_name" name="full_name" 
                                                value="<?php echo htmlspecialchars($current_user['full_name']); ?>" required>
                                    </div>
                                    <div class="p-1"></div>
                                    <div class="col-4 mb-3">
                                        <label for="username" class="form-label">Username</label>
                                        <input type="text" class="form-control" id="username" name="username" 
                                                value="<?php echo htmlspecialchars($current_user['username']); ?>" required>
                                    </div>
                                </div>
                                
                                <div class="col-6 mb-3">
                                    <label for="email" class="form-label">Email Address</label>
                                    <input type="email" class="form-control" id="email" name="email" 
                                            value="<?php echo htmlspecialchars($current_user['email']); ?>" required>
                                </div>
                                
                                
                                <div class="d-flex justify-content-end">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save me-2"></i>Update Profile
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Security Tab -->
                <div class="tab-pane fade" id="security-content" role="tabpanel">
                    <div class="profile-card">
                        <div class="card-header">
                            <h5 class="mb-0"><i class="fas fa-key me-2"></i>Change Password</h5>
                        </div>
                        <div class="card-body">
                            <form method="POST" id="passwordForm" class="flex flex-column flex-wrap gap-2 align-items-center justify-content-center">
                                <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                                <input type="hidden" name="action" value="change_password">
                                
                                <div class="col-10 mb-3">
                                    <label for="current_password" class="form-label">Current Password</label>
                                    <input type="password" class="form-control" id="current_password" name="current_password" required>
                                </div>
                                
                                <div class="row">
                                    <div class="col-5 mb-3">
                                        <label for="new_password" class="form-label">New Password</label>
                                        <input type="password" class="form-control" id="new_password" name="new_password" required>
                                        <div class="password-strength">
                                            <div class="password-strength-bar">
                                                <div class="password-strength-fill" id="strengthBar"></div>
                                            </div>
                                            <small class="form-text" id="strengthText">Password strength</small>
                                        </div>
                                    </div>
                                    <div class="p-1"></div>
                                    <div class="col-5 mb-3">
                                        <label for="confirm_password" class="form-label">Confirm New Password</label>
                                        <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                                        <div class="invalid-feedback" id="passwordMismatch">Passwords do not match</div>
                                    </div>
                                </div>
                                
                                <div class="d-flex justify-content-end">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-shield-alt me-2"></i>Change Password
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Security Information -->
                    <div class="profile-card">
                        <div class="card-header">
                            <h5 class="mb-0"><i class="fas fa-shield-alt me-2"></i>Security Information</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <p><strong>Account Status:</strong> 
                                        <span class="status-badge status-<?php echo $current_user['status']; ?>">
                                            <?php echo ucfirst($current_user['status']); ?>
                                        </span>
                                    </p>
                                    <p><strong>Failed Login Attempts:</strong> 
                                        <span class="badge bg-warning"><?php echo $current_user['failed_login_attempts']; ?></span>
                                    </p>
                                </div>
                                <div class="col-md-6">
                                    <p><strong>Account Locked:</strong> 
                                        <?php if ($current_user['locked_until'] && strtotime($current_user['locked_until']) > time()): ?>
                                            <span class="badge bg-danger">Yes, until <?php echo date('M d, Y H:i', strtotime($current_user['locked_until'])); ?></span>
                                        <?php else: ?>
                                            <span class="badge bg-success">No</span>
                                        <?php endif; ?>
                                    </p>
                                    <p><strong>Last Password Change:</strong> 
                                        <?php echo date('M d, Y', strtotime($current_user['updated_at'])); ?>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Settings Tab -->
                <div class="tab-pane fade" id="settings-content" role="tabpanel">
                    <div class="profile-card">
                        <div class="card-header">
                            <h5 class="mb-0"><i class="fas fa-cog me-2"></i>Account Settings</h5>
                        </div>
                        <div class="card-body">
                            <form method="POST">
                                <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                                <input type="hidden" name="action" value="update_settings">
                                
                                <div class="mb-4">
                                    <h6>Notification Preferences</h6>
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" id="email_notifications" name="email_notifications" checked>
                                        <label class="form-check-label" for="email_notifications">
                                            Email Notifications
                                        </label>
                                    </div>
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" id="login_alerts" name="login_alerts" checked>
                                        <label class="form-check-label" for="login_alerts">
                                            Login Alerts
                                        </label>
                                    </div>
                                </div>
                                
                                <div class="mb-4">
                                    <h6>Display Preferences</h6>
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="timezone" class="form-label">Timezone</label>
                                            <select class="form-select" id="timezone" name="timezone">
                                                <option value="UTC">UTC</option>
                                                <option value="America/New_York">Eastern Time</option>
                                                <option value="America/Chicago">Central Time</option>
                                                <option value="America/Denver">Mountain Time</option>
                                                <option value="America/Los_Angeles">Pacific Time</option>
                                            </select>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="language" class="form-label">Language</label>
                                            <select class="form-select" id="language" name="language">
                                                <option value="en">English</option>
                                                <option value="fr">French</option>
                                                <option value="es">Spanish</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="d-flex justify-content-end">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save me-2"></i>Save Settings
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // Password strength checker
        document.getElementById('new_password').addEventListener('input', function() {
            const password = this.value;
            const strengthBar = document.getElementById('strengthBar');
            const strengthText = document.getElementById('strengthText');
            
            let strength = 0;
            if (password.length >= 8) strength++;
            if (/[a-z]/.test(password)) strength++;
            if (/[A-Z]/.test(password)) strength++;
            if (/[0-9]/.test(password)) strength++;
            if (/[^A-Za-z0-9]/.test(password)) strength++;
            
            strengthBar.className = 'password-strength-fill';
            
            if (strength < 2) {
                strengthBar.style.width = '20%';
                strengthBar.classList.add('strength-weak');
                strengthText.textContent = 'Weak password';
                strengthText.className = 'form-text text-danger';
            } else if (strength < 4) {
                strengthBar.style.width = '60%';
                strengthBar.classList.add('strength-medium');
                strengthText.textContent = 'Medium password';
                strengthText.className = 'form-text text-warning';
            } else {
                strengthBar.style.width = '100%';
                strengthBar.classList.add('strength-strong');
                strengthText.textContent = 'Strong password';
                strengthText.className = 'form-text text-success';
            }
        });

        // Password confirmation checker
        document.getElementById('confirm_password').addEventListener('input', function() {
            const password = document.getElementById('new_password').value;
            const confirmPassword = this.value;
            const mismatchDiv = document.getElementById('passwordMismatch');
            
            if (confirmPassword && password !== confirmPassword) {
                this.classList.add('is-invalid');
                mismatchDiv.style.display = 'block';
            } else {
                this.classList.remove('is-invalid');
                mismatchDiv.style.display = 'none';
            }
        });

        // Form validation
        document.getElementById('passwordForm').addEventListener('submit', function(e) {
            const password = document.getElementById('new_password').value;
            const confirmPassword = document.getElementById('confirm_password').value;
            
            if (password !== confirmPassword) {
                e.preventDefault();
                alert('Passwords do not match!');
                return false;
            }
            
            if (password.length < 8) {
                e.preventDefault();
                alert('Password must be at least 8 characters long!');
                return false;
            }
        });

        // Auto-save draft functionality for forms
        let formData = {};
        const forms = document.querySelectorAll('form');
        
        forms.forEach(form => {
            const inputs = form.querySelectorAll('input[type="text"], input[type="email"], select');
            inputs.forEach(input => {
                input.addEventListener('input', function() {
                    formData[this.name] = this.value;
                    localStorage.setItem('profileFormDraft', JSON.stringify(formData));
                });
            });
        });

        // Load draft data on page load
        window.addEventListener('load', function() {
            const savedData = localStorage.getItem('profileFormDraft');
            if (savedData) {
                const data = JSON.parse(savedData);
                Object.keys(data).forEach(key => {
                    const input = document.querySelector(`[name="${key}"]`);
                    if (input && input.type !== 'password') {
                        input.value = data[key];
                    }
                });
            }
        });

        // Clear draft data on successful form submission
        forms.forEach(form => {
            form.addEventListener('submit', function() {
                setTimeout(() => {
                    if (document.querySelector('.alert-success')) {
                        localStorage.removeItem('profileFormDraft');
                    }
                }, 100);
            });
        });

        // Real-time validation feedback
        const requiredInputs = document.querySelectorAll('input[required]');
        requiredInputs.forEach(input => {
            input.addEventListener('blur', function() {
                if (this.value.trim() === '') {
                    this.classList.add('is-invalid');
                } else {
                    this.classList.remove('is-invalid');
                    this.classList.add('is-valid');
                }
            });

            input.addEventListener('input', function() {
                if (this.classList.contains('is-invalid') && this.value.trim() !== '') {
                    this.classList.remove('is-invalid');
                    this.classList.add('is-valid');
                }
            });
        });

        // Email validation
        const emailInput = document.getElementById('email');
        if (emailInput) {
            emailInput.addEventListener('input', function() {
                const email = this.value;
                const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                
                if (email && !emailRegex.test(email)) {
                    this.classList.add('is-invalid');
                    this.classList.remove('is-valid');
                } else if (email) {
                    this.classList.remove('is-invalid');
                    this.classList.add('is-valid');
                }
            });
        }

        // Username availability check (debounced)
        const usernameInput = document.getElementById('username');
        if (usernameInput) {
            let timeoutId;
            usernameInput.addEventListener('input', function() {
                clearTimeout(timeoutId);
                const username = this.value;
                const currentUsername = '<?php echo $current_user['username']; ?>';
                
                if (username && username !== currentUsername) {
                    timeoutId = setTimeout(() => {
                        fetch('check_username.php', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-Token': '<?php echo $_SESSION['csrf_token']; ?>'
                            },
                            body: JSON.stringify({ username: username })
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.available) {
                                this.classList.remove('is-invalid');
                                this.classList.add('is-valid');
                            } else {
                                this.classList.add('is-invalid');
                                this.classList.remove('is-valid');
                            }
                        });
                    }, 500);
                }
            });
        }

        // Loading states for forms
        forms.forEach(form => {
            form.addEventListener('submit', function() {
                const submitBtn = this.querySelector('button[type="submit"]');
                if (submitBtn) {
                    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Saving...';
                    submitBtn.disabled = true;
                }
            });
        });

        // Initialize everything when page loads
        document.addEventListener('DOMContentLoaded', function() {
            // Add smooth transitions to cards
            const cards = document.querySelectorAll('.profile-card');
            cards.forEach(card => {
                card.style.opacity = '0';
                card.style.transform = 'translateY(20px)';
            });
            
            // Animate cards in
            setTimeout(() => {
                cards.forEach((card, index) => {
                    setTimeout(() => {
                        card.style.transition = 'all 0.5s ease';
                        card.style.opacity = '1';
                        card.style.transform = 'translateY(0)';
                    }, index * 100);
                });
            }, 100);
        });
    </script>

<?php include 'includes/footer.php'; ?>
