<?php
/**
 * Authentication Class for FSMC Admin System
 * Created: 2025-01-22
 * Description: Handles user authentication, session management, and security
 */

class Auth {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance();
    }
    
    /**
     * Authenticate user login
     */
    public function login($username, $password, $rememberMe = false) {
        try {
            // Check for too many failed attempts
            if ($this->isAccountLocked($username)) {
                return [
                    'success' => false,
                    'message' => 'Account is temporarily locked due to too many failed login attempts. Please try again later.'
                ];
            }
            
            // Get user from database
            $user = $this->db->getRow(
                "SELECT id, username, email, password_hash, full_name, role, status, failed_login_attempts 
                 FROM admin_users 
                 WHERE (username = ? OR email = ?) AND status = 'active'",
                [$username, $username]
            );
            
            if (!$user) {
                $this->recordFailedAttempt($username);
                return [
                    'success' => false,
                    'message' => 'Invalid username or password.'
                ];
            }
            
            // Verify password
            if (!password_verify($password, $user['password_hash'])) {
                $this->recordFailedAttempt($username);
                return [
                    'success' => false,
                    'message' => 'Invalid username or password.'
                ];
            }
            
            // Reset failed attempts on successful login
            $this->resetFailedAttempts($user['id']);
            
            // Create session
            $this->createSession($user);
            
            // Update last login
            $this->updateLastLogin($user['id']);
            
            // Log activity
            logActivity('User Login', 'admin_users', $user['id']);
            
            return [
                'success' => true,
                'message' => 'Login successful.',
                'user' => $user
            ];
            
        } catch (Exception $e) {
            error_log("Login error: " . $e->getMessage());
            return [
                'success' => false,
                'message' => 'An error occurred during login. Please try again.'
            ];
        }
    }
    
    /**
     * Logout user
     */
    public function logout() {
        if (isLoggedIn()) {
            $user = getCurrentUser();
            logActivity('User Logout', 'admin_users', $user['id']);
        }
        
        // Destroy session
        session_destroy();
        
        // Clear session cookie
        if (isset($_COOKIE[session_name()])) {
            setcookie(session_name(), '', time() - 3600, '/');
        }
        
        return true;
    }
    
    /**
     * Check if account is locked
     */
    private function isAccountLocked($username) {
        $user = $this->db->getRow(
            "SELECT failed_login_attempts, locked_until FROM admin_users 
             WHERE (username = ? OR email = ?)",
            [$username, $username]
        );
        
        if (!$user) {
            return false;
        }
        
        // Check if account is currently locked
        if ($user['locked_until'] && strtotime($user['locked_until']) > time()) {
            return true;
        }
        
        // Check if too many failed attempts
        if ($user['failed_login_attempts'] >= MAX_LOGIN_ATTEMPTS) {
            // Lock account
            $this->lockAccount($username);
            return true;
        }
        
        return false;
    }
    
    /**
     * Record failed login attempt
     */
    private function recordFailedAttempt($username) {
        $this->db->execute(
            "UPDATE admin_users 
             SET failed_login_attempts = failed_login_attempts + 1 
             WHERE (username = ? OR email = ?)",
            [$username, $username]
        );
    }
    
    /**
     * Reset failed login attempts
     */
    private function resetFailedAttempts($userId) {
        $this->db->execute(
            "UPDATE admin_users 
             SET failed_login_attempts = 0, locked_until = NULL 
             WHERE id = ?",
            [$userId]
        );
    }
    
    /**
     * Lock user account
     */
    private function lockAccount($username) {
        $lockUntil = date('Y-m-d H:i:s', time() + LOGIN_LOCKOUT_TIME);
        
        $this->db->execute(
            "UPDATE admin_users 
             SET locked_until = ? 
             WHERE (username = ? OR email = ?)",
            [$lockUntil, $username, $username]
        );
    }
    
    /**
     * Create user session
     */
    private function createSession($user) {
        // Regenerate session ID for security
        session_regenerate_id(true);
        
        // Set session variables
        $_SESSION['admin_user_id'] = $user['id'];
        $_SESSION['admin_username'] = $user['username'];
        $_SESSION['admin_email'] = $user['email'];
        $_SESSION['admin_full_name'] = $user['full_name'];
        $_SESSION['admin_role'] = $user['role'];
        $_SESSION['last_activity'] = time();
        $_SESSION['session_started'] = time();
        
        // Generate CSRF token
        generateCSRFToken();
    }
    
    /**
     * Update last login timestamp
     */
    private function updateLastLogin($userId) {
        $this->db->execute(
            "UPDATE admin_users SET last_login = NOW() WHERE id = ?",
            [$userId]
        );
    }
    
    /**
     * Change user password
     */
    public function changePassword($userId, $currentPassword, $newPassword) {
        try {
            // Get current user
            $user = $this->db->getRow(
                "SELECT password_hash FROM admin_users WHERE id = ?",
                [$userId]
            );
            
            if (!$user) {
                return [
                    'success' => false,
                    'message' => 'User not found.'
                ];
            }
            
            // Verify current password
            if (!password_verify($currentPassword, $user['password_hash'])) {
                return [
                    'success' => false,
                    'message' => 'Current password is incorrect.'
                ];
            }
            
            // Validate new password
            if (strlen($newPassword) < PASSWORD_MIN_LENGTH) {
                return [
                    'success' => false,
                    'message' => 'New password must be at least ' . PASSWORD_MIN_LENGTH . ' characters long.'
                ];
            }
            
            // Hash new password
            $newPasswordHash = password_hash($newPassword, PASSWORD_DEFAULT);
            
            // Update password
            $this->db->execute(
                "UPDATE admin_users SET password_hash = ?, updated_at = NOW() WHERE id = ?",
                [$newPasswordHash, $userId]
            );
            
            // Log activity
            logActivity('Password Changed', 'admin_users', $userId);
            
            return [
                'success' => true,
                'message' => 'Password changed successfully.'
            ];
            
        } catch (Exception $e) {
            error_log("Password change error: " . $e->getMessage());
            return [
                'success' => false,
                'message' => 'An error occurred while changing password.'
            ];
        }
    }
    
    /**
     * Create new admin user
     */
    public function createUser($data) {
        try {
            // Validate required fields
            $required = ['username', 'email', 'password', 'full_name'];
            foreach ($required as $field) {
                if (empty($data[$field])) {
                    return [
                        'success' => false,
                        'message' => ucfirst($field) . ' is required.'
                    ];
                }
            }
            
            // Validate email
            if (!isValidEmail($data['email'])) {
                return [
                    'success' => false,
                    'message' => 'Invalid email address.'
                ];
            }
            
            // Validate password
            if (strlen($data['password']) < PASSWORD_MIN_LENGTH) {
                return [
                    'success' => false,
                    'message' => 'Password must be at least ' . PASSWORD_MIN_LENGTH . ' characters long.'
                ];
            }
            
            // Check if username or email already exists
            $existing = $this->db->getRow(
                "SELECT id FROM admin_users WHERE username = ? OR email = ?",
                [$data['username'], $data['email']]
            );
            
            if ($existing) {
                return [
                    'success' => false,
                    'message' => 'Username or email already exists.'
                ];
            }
            
            // Hash password
            $passwordHash = password_hash($data['password'], PASSWORD_DEFAULT);
            
            // Insert user
            $userId = $this->db->insert(
                "INSERT INTO admin_users (username, email, password_hash, full_name, role, status) 
                 VALUES (?, ?, ?, ?, ?, ?)",
                [
                    $data['username'],
                    $data['email'],
                    $passwordHash,
                    $data['full_name'],
                    $data['role'] ?? 'admin',
                    $data['status'] ?? 'active'
                ]
            );
            
            if ($userId) {
                // Log activity
                logActivity('User Created', 'admin_users', $userId);
                
                return [
                    'success' => true,
                    'message' => 'User created successfully.',
                    'user_id' => $userId
                ];
            } else {
                return [
                    'success' => false,
                    'message' => 'Failed to create user.'
                ];
            }
            
        } catch (Exception $e) {
            error_log("User creation error: " . $e->getMessage());
            return [
                'success' => false,
                'message' => 'An error occurred while creating user.'
            ];
        }
    }
    
    /**
     * Validate session and refresh if needed
     */
    public function validateSession() {
        if (!isLoggedIn()) {
            return false;
        }
        
        // Check session timeout
        if (isset($_SESSION['last_activity']) && 
            (time() - $_SESSION['last_activity']) > SESSION_TIMEOUT) {
            $this->logout();
            return false;
        }
        
        // Update last activity
        $_SESSION['last_activity'] = time();
        
        return true;
    }
}
?>
