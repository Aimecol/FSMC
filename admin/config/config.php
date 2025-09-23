<?php
/**
 * Main Configuration File for FSMC Admin System
 * Created: 2025-01-22
 * Description: Core configuration settings and initialization
 */

// Prevent direct access
if (!defined('ADMIN_ACCESS')) {
    define('ADMIN_ACCESS', true);
}

// Error reporting (disable in production)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Set timezone
date_default_timezone_set('Africa/Kigali');

// Define paths
define('ADMIN_ROOT', dirname(__DIR__));
define('PROJECT_ROOT', dirname(ADMIN_ROOT));
define('ADMIN_URL', '/admin');
define('BASE_URL', 'http://localhost/ikimina/FSMC');
define('ADMIN_BASE_URL', BASE_URL . ADMIN_URL);

// Define upload paths
define('UPLOAD_PATH', PROJECT_ROOT . '/uploads');
define('UPLOAD_URL', BASE_URL . '/uploads');

// Maximum file upload sizes
define('MAX_FILE_SIZE', 10 * 1024 * 1024); // 10MB
define('MAX_IMAGE_SIZE', 5 * 1024 * 1024);  // 5MB

// Allowed file types
define('ALLOWED_IMAGE_TYPES', ['jpg', 'jpeg', 'png', 'gif', 'webp']);
define('ALLOWED_DOCUMENT_TYPES', ['pdf', 'doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx']);

// Session configuration (must be before session_start)
ini_set('session.cookie_httponly', 1);
ini_set('session.cookie_secure', 0); // set 1 if using HTTPS
ini_set('session.use_strict_mode', 1);
ini_set('session.cookie_samesite', 'Strict');

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Session timeout (30 minutes)
define('SESSION_TIMEOUT', 30 * 60);

// Security settings
define('MAX_LOGIN_ATTEMPTS', 5);
define('LOGIN_LOCKOUT_TIME', 15 * 60); // 15 minutes
define('PASSWORD_MIN_LENGTH', 6);

// Pagination settings
define('DEFAULT_PAGE_SIZE', 20);
define('MAX_PAGE_SIZE', 100);

// Include database configuration
require_once ADMIN_ROOT . '/config/database.php';


/**
 * Autoloader for classes
 */
spl_autoload_register(function ($className) {
    $directories = [
        ADMIN_ROOT . '/classes/',
        ADMIN_ROOT . '/models/',
        ADMIN_ROOT . '/controllers/'
    ];
    
    foreach ($directories as $directory) {
        $file = $directory . $className . '.php';
        if (file_exists($file)) {
            require_once $file;
            return;
        }
    }
});

/**
 * Helper Functions
 */

/**
 * Sanitize input data
 */
function sanitize($data) {
    if (is_array($data)) {
        return array_map('sanitize', $data);
    }
    return htmlspecialchars(trim($data), ENT_QUOTES, 'UTF-8');
}

/**
 * Validate email address
 */
function isValidEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}

/**
 * Generate CSRF token
 */
function generateCSRFToken() {
    if (!isset($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

/**
 * Verify CSRF token
 */
function verifyCSRFToken($token) {
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}

/**
 * Redirect function
 */
function redirect($url, $permanent = false) {
    if (!headers_sent()) {
        header('Location: ' . $url, true, $permanent ? 301 : 302);
        exit;
    }
    echo '<script>window.location.href="' . $url . '";</script>';
    exit;
}

/**
 * Check if user is logged in
 */
function isLoggedIn() {
    return isset($_SESSION['admin_user_id']) && isset($_SESSION['admin_username']);
}

/**
 * Check if session is valid
 */
function isValidSession() {
    if (!isLoggedIn()) {
        return false;
    }
    
    // Check session timeout
    if (isset($_SESSION['last_activity']) && 
        (time() - $_SESSION['last_activity']) > SESSION_TIMEOUT) {
        session_destroy();
        return false;
    }
    
    // Update last activity
    $_SESSION['last_activity'] = time();
    
    return true;
}

/**
 * Require authentication
 */
function requireAuth() {
    if (!isValidSession()) {
        redirect(ADMIN_BASE_URL . '/login.php');
    }
}

/**
 * Get current user data
 */
function getCurrentUser() {
    if (!isLoggedIn()) {
        return null;
    }
    
    static $user = null;
    if ($user === null) {
        $user = dbGetRow(
            "SELECT id, username, email, full_name, role, status FROM admin_users WHERE id = ?",
            [$_SESSION['admin_user_id']]
        );
    }
    
    return $user;
}

/**
 * Check user permission
 */
function hasPermission($permission) {
    $user = getCurrentUser();
    if (!$user) {
        return false;
    }
    
    // Super admin has all permissions
    if ($user['role'] === 'super_admin') {
        return true;
    }
    
    // Define role permissions
    $permissions = [
        'admin' => ['view', 'create', 'edit', 'delete'],
        'editor' => ['view', 'create', 'edit']
    ];
    
    return isset($permissions[$user['role']]) && 
           in_array($permission, $permissions[$user['role']]);
}

/**
 * Format file size
 */
function formatFileSize($bytes) {
    $units = ['B', 'KB', 'MB', 'GB'];
    $bytes = max($bytes, 0);
    $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
    $pow = min($pow, count($units) - 1);
    
    $bytes /= pow(1024, $pow);
    
    return round($bytes, 2) . ' ' . $units[$pow];
}

/**
 * Generate slug from string
 */
function generateSlug($string) {
    $slug = strtolower(trim($string));
    $slug = preg_replace('/[^a-z0-9-]/', '-', $slug);
    $slug = preg_replace('/-+/', '-', $slug);
    return trim($slug, '-');
}

/**
 * Log activity
 */
function logActivity($action, $table = null, $recordId = null, $oldValues = null, $newValues = null) {
    $user = getCurrentUser();
    $userId = $user ? $user['id'] : null;
    
    $sql = "INSERT INTO activity_logs (user_id, action, table_name, record_id, old_values, new_values, ip_address, user_agent) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    
    $params = [
        $userId,
        $action,
        $table,
        $recordId,
        $oldValues ? json_encode($oldValues) : null,
        $newValues ? json_encode($newValues) : null,
        $_SERVER['REMOTE_ADDR'] ?? null,
        $_SERVER['HTTP_USER_AGENT'] ?? null
    ];
    
    dbExecute($sql, $params);
}

/**
 * Show success message
 */
function setSuccessMessage($message) {
    $_SESSION['success_message'] = $message;
}

/**
 * Show error message
 */
function setErrorMessage($message) {
    $_SESSION['error_message'] = $message;
}

/**
 * Get and clear success message
 */
function getSuccessMessage() {
    if (isset($_SESSION['success_message'])) {
        $message = $_SESSION['success_message'];
        unset($_SESSION['success_message']);
        return $message;
    }
    return null;
}

/**
 * Get and clear error message
 */
function getErrorMessage() {
    if (isset($_SESSION['error_message'])) {
        $message = $_SESSION['error_message'];
        unset($_SESSION['error_message']);
        return $message;
    }
    return null;
}

/**
 * Get company setting
 */
function getSetting($key, $default = null) {
    static $settings = null;
    
    if ($settings === null) {
        $rows = dbGetRows("SELECT setting_key, setting_value FROM company_settings");
        $settings = [];
        foreach ($rows as $row) {
            $settings[$row['setting_key']] = $row['setting_value'];
        }
    }
    
    return isset($settings[$key]) ? $settings[$key] : $default;
}

/**
 * Update company setting
 */
function updateSetting($key, $value) {
    $sql = "INSERT INTO company_settings (setting_key, setting_value, updated_at)
            VALUES (?, ?, NOW())
            ON DUPLICATE KEY UPDATE setting_value = VALUES(setting_value), updated_at = NOW()";

    return dbExecute($sql, [$key, $value]);
}

/**
 * Validate uploaded file
 */
function validateUploadedFile($filename, $fileSize, $tmpName) {
    $result = ['valid' => false, 'error' => ''];

    // Check file size
    if ($fileSize > MAX_FILE_SIZE) {
        $result['error'] = 'File size exceeds maximum allowed size of ' . formatFileSize(MAX_FILE_SIZE);
        return $result;
    }

    // Check file extension
    $extension = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
    $allowedTypes = array_merge(ALLOWED_IMAGE_TYPES, ALLOWED_DOCUMENT_TYPES);

    if (!in_array($extension, $allowedTypes)) {
        $result['error'] = 'File type not allowed. Allowed types: ' . implode(', ', $allowedTypes);
        return $result;
    }

    // Check if file is actually uploaded
    if (!is_uploaded_file($tmpName)) {
        $result['error'] = 'Invalid file upload';
        return $result;
    }

    // Additional validation for images
    if (in_array($extension, ALLOWED_IMAGE_TYPES)) {
        $imageInfo = getimagesize($tmpName);
        if ($imageInfo === false) {
            $result['error'] = 'Invalid image file';
            return $result;
        }

        if ($fileSize > MAX_IMAGE_SIZE) {
            $result['error'] = 'Image size exceeds maximum allowed size of ' . formatFileSize(MAX_IMAGE_SIZE);
            return $result;
        }
    }

    $result['valid'] = true;
    return $result;
}

// Initialize error handling
set_error_handler(function($severity, $message, $file, $line) {
    if (!(error_reporting() & $severity)) {
        return false;
    }
    
    error_log("PHP Error: $message in $file on line $line");
    
    if ($severity === E_ERROR || $severity === E_USER_ERROR) {
        die("A system error occurred. Please contact the administrator.");
    }
    
    return true;
});

// Initialize exception handling
set_exception_handler(function($exception) {
    error_log("Uncaught exception: " . $exception->getMessage() . " in " . $exception->getFile() . " on line " . $exception->getLine());
    die("A system error occurred. Please contact the administrator.");
});
?>
