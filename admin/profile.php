<?php
require_once 'config/config.php';
require_once 'config/database.php';

// Check authentication
requireAuth();

// Helper functions (these would typically be in includes/functions.php)

function getUserById($user_id) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->execute([$user_id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function updateProfile($user_id, $data) {
    global $pdo;
    
    try {
        // Validate input
        if (empty($data['full_name']) || empty($data['username']) || empty($data['email'])) {
            return ['success' => false, 'message' => 'All fields are required'];
        }
        
        // Check if username/email already exists (excluding current user)
        $stmt = $pdo->prepare("SELECT id FROM users WHERE (username = ? OR email = ?) AND id != ?");
        $stmt->execute([$data['username'], $data['email'], $user_id]);
        if ($stmt->fetch()) {
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
        $sql = "UPDATE users SET full_name = ?, username = ?, email = ?, updated_at = CURRENT_TIMESTAMP";
        $params = [$data['full_name'], $data['username'], $data['email']];
        
        if ($avatar_path) {
            $sql .= ", avatar = ?";
            $params[] = $avatar_path;
        }
        
        $sql .= " WHERE id = ?";
        $params[] = $user_id;
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        
        // Log activity
        logUserActivity($user_id, 'profile_updated', 'Profile information updated');
        
        return ['success' => true, 'message' => 'Profile updated successfully'];
        
    } catch (Exception $e) {
        error_log($e->getMessage());
        return ['success' => false, 'message' => 'An error occurred while updating profile'];
    }
}

function changePassword($user_id, $data) {
    global $pdo;
    
    try {
        // Validate input
        if (empty($data['current_password']) || empty($data['new_password']) || empty($data['confirm_password'])) {
            return ['success' => false, 'message' => 'All password fields are required'];
        }
        
        if ($data['new_password'] !== $data['confirm_password']) {
            return ['success' => false, 'message' => 'New passwords do not match'];
        }
        
        if (strlen($data['new_password']) < 8) {
            return ['success' => false, 'message' => 'Password must be at least 8 characters long'];
        }
        
        // Get current user
        $user = getUserById($user_id);
        if (!password_verify($data['current_password'], $user['password_hash'])) {
            return ['success' => false, 'message' => 'Current password is incorrect'];
        }
        
        // Update password
        $new_hash = password_hash($data['new_password'], PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("UPDATE users SET password_hash = ?, updated_at = CURRENT_TIMESTAMP WHERE id = ?");
        $stmt->execute([$new_hash, $user_id]);
        
        // Log activity
        logUserActivity($user_id, 'password_changed', 'Password changed successfully');
        
        return ['success' => true, 'message' => 'Password changed successfully'];
        
    } catch (Exception $e) {
        error_log($e->getMessage());
        return ['success' => false, 'message' => 'An error occurred while changing password'];
    }
}

function updateUserSettings($user_id, $data) {
    // This would update user preferences in a settings table
    // Implementation depends on your specific settings structure
    logUserActivity($user_id, 'settings_updated', 'Account settings updated');
    return ['success' => true, 'message' => 'Settings updated successfully'];
}

function getUserActivityLogs($user_id, $limit = 10) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT * FROM user_activity WHERE user_id = ? ORDER BY created_at DESC LIMIT ?");
    $stmt->execute([$user_id, $limit]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
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
    global $pdo;
    try {
        $stmt = $pdo->prepare("INSERT INTO user_activity (user_id, action, description, ip_address, user_agent, created_at) VALUES (?, ?, ?, ?, ?, CURRENT_TIMESTAMP)");
        $stmt->execute([
            $user_id,
            $action,
            $description,
            $_SERVER['REMOTE_ADDR'] ?? 'unknown',
            $_SERVER['HTTP_USER_AGENT'] ?? 'unknown'
        ]);
    } catch (Exception $e) {
        error_log('Failed to log activity: ' . $e->getMessage());
    }
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

$user_id = $_SESSION['user_id'];
$current_user = getUserById($user_id);

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
        :root {
            --primary-color: #1a5276;
            --secondary-color: #2e86c1;
            --accent-color: #f39c12;
            --light-color: #f4f6f7;
            --dark-color: #2c3e50;
            --white: #ffffff;
            --text-dark: #2c3e50;
            --text-light: #7f8c8d;
            --text-muted: #95a5a6;
            --border-color: #ddd;
            --border-light: #ecf0f1;
            --error-color: #e74c3c;
            --success-color: #27ae60;
            --warning-color: #f39c12;
            --info-color: #3498db;
            --shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            --shadow-lg: 0 10px 30px rgba(0, 0, 0, 0.15);
            --transition: all 0.3s ease;
            --border-radius: 8px;
            --sidebar-width: 260px;
            --topnav-height: 60px;
        }

        body {
            background-color: var(--light-color);
            color: var(--text-dark);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .main-content {
            margin-left: var(--sidebar-width);
            padding: 2rem;
            min-height: 100vh;
        }

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

    <div class="main-content">
        <!-- Profile Header -->
        <div class="profile-header">
            <div class="row align-items-center">
                <div class="col-md-3 text-center">
                    <img src="<?php echo htmlspecialchars($current_user['avatar'] ?? 'assets/images/default-avatar.png'); ?>" 
                         alt="Profile Avatar" class="profile-avatar">
                </div>
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

        <!-- Statistics Row -->
        <div class="row mb-4">
            <div class="col-md-3 mb-3">
                <div class="stats-card">
                    <div class="stats-number"><?php echo number_format($user_stats['total_logins'] ?? 0); ?></div>
                    <div class="stats-label">Total Logins</div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="stats-card">
                    <div class="stats-number"><?php echo $user_stats['failed_attempts'] ?? 0; ?></div>
                    <div class="stats-label">Failed Attempts</div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="stats-card">
                    <div class="stats-number"><?php echo $user_stats['days_active'] ?? 0; ?></div>
                    <div class="stats-label">Days Active</div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="stats-card">
                    <div class="stats-number"><?php echo $user_stats['profile_updates'] ?? 0; ?></div>
                    <div class="stats-label">Profile Updates</div>
                </div>
            </div>
        </div>

        <!-- Main Content with Tabs -->
        <div class="row">
            <div class="col-lg-8">
                <!-- Navigation Pills -->
                <ul class="nav nav-pills mb-4" id="profileTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="profile-tab" data-bs-toggle="pill" data-bs-target="#profile-content" type="button" role="tab">
                            <i class="fas fa-user me-2"></i>Profile Information
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="security-tab" data-bs-toggle="pill" data-bs-target="#security-content" type="button" role="tab">
                            <i class="fas fa-lock me-2"></i>Security
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="settings-tab" data-bs-toggle="pill" data-bs-target="#settings-content" type="button" role="tab">
                            <i class="fas fa-cog me-2"></i>Settings
                        </button>
                    </li>
                </ul>

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
                                        <div class="col-md-6 mb-3">
                                            <label for="full_name" class="form-label">Full Name</label>
                                            <input type="text" class="form-control" id="full_name" name="full_name" 
                                                   value="<?php echo htmlspecialchars($current_user['full_name']); ?>" required>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="username" class="form-label">Username</label>
                                            <input type="text" class="form-control" id="username" name="username" 
                                                   value="<?php echo htmlspecialchars($current_user['username']); ?>" required>
                                        </div>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label for="email" class="form-label">Email Address</label>
                                        <input type="email" class="form-control" id="email" name="email" 
                                               value="<?php echo htmlspecialchars($current_user['email']); ?>" required>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label for="avatar" class="form-label">Profile Picture</label>
                                        <input type="file" class="form-control" id="avatar" name="avatar" accept="image/*">
                                        <div class="form-text">Choose a new profile picture (optional)</div>
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
                                <form method="POST" id="passwordForm">
                                    <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                                    <input type="hidden" name="action" value="change_password">
                                    
                                    <div class="mb-3">
                                        <label for="current_password" class="form-label">Current Password</label>
                                        <input type="password" class="form-control" id="current_password" name="current_password" required>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label for="new_password" class="form-label">New Password</label>
                                        <input type="password" class="form-control" id="new_password" name="new_password" required>
                                        <div class="password-strength">
                                            <div class="password-strength-bar">
                                                <div class="password-strength-fill" id="strengthBar"></div>
                                            </div>
                                            <small class="form-text" id="strengthText">Password strength</small>
                                        </div>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label for="confirm_password" class="form-label">Confirm New Password</label>
                                        <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                                        <div class="invalid-feedback" id="passwordMismatch">Passwords do not match</div>
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

            <!-- Sidebar -->
            <div class="col-lg-4">
                <!-- Recent Activity -->
                <div class="profile-card">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-history me-2"></i>Recent Activity</h5>
                    </div>
                    <div class="card-body">
                        <?php if (!empty($activity_logs)): ?>
                            <?php foreach ($activity_logs as $activity): ?>
                                <div class="activity-item">
                                    <div class="d-flex align-items-center">
                                        <i class="<?php echo getActivityIcon($activity['action']); ?> text-primary me-3"></i>
                                        <div class="flex-grow-1">
                                            <p class="mb-1"><?php echo htmlspecialchars($activity['description']); ?></p>
                                            <small class="activity-time">
                                                <?php echo timeAgo($activity['created_at']); ?>
                                            </small>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <p class="text-muted text-center py-3">No recent activity</p>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="profile-card">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-bolt me-2"></i>Quick Actions</h5>
                    </div>
                    <div class="card-body">
                        <div class="d-grid gap-2">
                            <button class="btn btn-outline-primary" onclick="exportProfile()">
                                <i class="fas fa-download me-2"></i>Export Profile Data
                            </button>
                            <button class="btn btn-outline-primary" onclick="clearActivity()">
                                <i class="fas fa-trash-alt me-2"></i>Clear Activity Log
                            </button>
                            <button class="btn btn-outline-primary" onclick="generateBackup()">
                                <i class="fas fa-shield-alt me-2"></i>Generate Backup
                            </button>
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

        // Profile picture preview
        document.getElementById('avatar').addEventListener('change', function() {
            const file = this.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.querySelector('.profile-avatar').src = e.target.result;
                }
                reader.readAsDataURL(file);
            }
        });

        // Quick actions
        function exportProfile() {
            if (confirm('Export your profile data? This will download a JSON file with your information.')) {
                window.location.href = 'export_profile.php';
            }
        }

        function clearActivity() {
            if (confirm('Clear your activity log? This action cannot be undone.')) {
                fetch('clear_activity.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-Token': '<?php echo $_SESSION['csrf_token']; ?>'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        location.reload();
                    } else {
                        alert('Failed to clear activity log.');
                    }
                });
            }
        }

        function generateBackup() {
            if (confirm('Generate a backup of your account data?')) {
                window.location.href = 'generate_backup.php';
            }
        }

        // Auto-save draft functionality for forms
        let formData = {};
        const forms = document.querySelectorAll('form');
        
        forms.forEach(form => {
            const inputs = form.querySelectorAll('input[type="text"], input[type="email"], textarea, select');
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

        // Smooth scrolling for tab switching
        document.querySelectorAll('#profileTabs button').forEach(tab => {
            tab.addEventListener('shown.bs.tab', function(e) {
                document.querySelector('.main-content').scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            });
        });

        // Real-time validation feedback
        const requiredInputs = document.querySelectorAll('input[required], textarea[required]');
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

        // Activity refresh
        function refreshActivity() {
            fetch('get_activity.php', {
                method: 'GET',
                headers: {
                    'X-CSRF-Token': '<?php echo $_SESSION['csrf_token']; ?>'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const activityContainer = document.querySelector('.card-body');
                    if (activityContainer) {
                        activityContainer.innerHTML = data.html;
                    }
                }
            });
        }

        // Refresh activity every 5 minutes
        setInterval(refreshActivity, 300000);

        // Keyboard shortcuts
        document.addEventListener('keydown', function(e) {
            // Ctrl/Cmd + S to save current form
            if ((e.ctrlKey || e.metaKey) && e.key === 's') {
                e.preventDefault();
                const activeForm = document.querySelector('.tab-pane.active form');
                if (activeForm) {
                    activeForm.submit();
                }
            }
            
            // Ctrl/Cmd + 1, 2, 3 to switch tabs
            if ((e.ctrlKey || e.metaKey) && ['1', '2', '3'].includes(e.key)) {
                e.preventDefault();
                const tabIndex = parseInt(e.key) - 1;
                const tabs = document.querySelectorAll('#profileTabs button');
                if (tabs[tabIndex]) {
                    tabs[tabIndex].click();
                }
            }
        });

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

        // Tooltips
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });

        // File upload validation
        document.getElementById('avatar').addEventListener('change', function() {
            const file = this.files[0];
            const maxSize = 5 * 1024 * 1024; // 5MB
            const allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
            
            if (file) {
                if (file.size > maxSize) {
                    alert('File size must be less than 5MB');
                    this.value = '';
                    return;
                }
                
                if (!allowedTypes.includes(file.type)) {
                    alert('Only JPEG, PNG, GIF, and WebP images are allowed');
                    this.value = '';
                    return;
                }
            }
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
</body>
</html>
