<?php
/**
 * Common Functions for FSMC Project
 * Database operations and utility functions
 */

require_once __DIR__ . '/../config/database.php';

// Include security configuration after database is loaded
require_once __DIR__ . '/../config/security.php';

// escapeString() function is already declared in config/database.php

/**
 * Validate email address
 * @param string $email Email to validate
 * @return bool True if valid, false otherwise
 */
function validateEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}

/**
 * Validate phone number (Rwanda format)
 * @param string $phone Phone number to validate
 * @return bool True if valid, false otherwise
 */
function validatePhone($phone) {
    // Remove spaces and special characters
    $phone = preg_replace('/[^0-9+]/', '', $phone);

    // Check Rwanda phone number patterns
    $patterns = [
        '/^(\+250|250)?[0-9]{9}$/',  // +250xxxxxxxxx or 250xxxxxxxxx or xxxxxxxxx
        '/^07[0-9]{8}$/',            // 07xxxxxxxx
        '/^08[0-9]{8}$/',            // 08xxxxxxxx
    ];

    foreach ($patterns as $pattern) {
        if (preg_match($pattern, $phone)) {
            return true;
        }
    }

    return false;
}

/**
 * Validate password strength
 * @param string $password Password to validate
 * @return array Validation result with 'valid' boolean and 'errors' array
 */
function validatePassword($password) {
    $errors = [];

    if (strlen($password) < 6) {
        $errors[] = 'Password must be at least 6 characters long';
    }

    if (!preg_match('/[A-Z]/', $password)) {
        $errors[] = 'Password must contain at least one uppercase letter';
    }

    if (!preg_match('/[a-z]/', $password)) {
        $errors[] = 'Password must contain at least one lowercase letter';
    }

    if (!preg_match('/[0-9]/', $password)) {
        $errors[] = 'Password must contain at least one number';
    }

    return [
        'valid' => empty($errors),
        'errors' => $errors
    ];
}

/**
 * Generate CSRF token
 * @return string CSRF token
 */
function generateCSRFToken() {
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }

    $token = bin2hex(random_bytes(32));
    $_SESSION['csrf_token'] = $token;
    return $token;
}

/**
 * Verify CSRF token
 * @param string $token Token to verify
 * @return bool True if valid, false otherwise
 */
function verifyCSRFToken($token) {
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }

    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}

/**
 * Rate limiting for login attempts
 * @param string $identifier User identifier (email or IP)
 * @param int $maxAttempts Maximum attempts allowed
 * @param int $timeWindow Time window in seconds
 * @return bool True if allowed, false if rate limited
 */
function checkRateLimit($identifier, $maxAttempts = 5, $timeWindow = 900) {
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }

    $key = 'rate_limit_' . md5($identifier);
    $now = time();

    if (!isset($_SESSION[$key])) {
        $_SESSION[$key] = [];
    }

    // Remove old attempts outside the time window
    $_SESSION[$key] = array_filter($_SESSION[$key], function($timestamp) use ($now, $timeWindow) {
        return ($now - $timestamp) < $timeWindow;
    });

    // Check if limit exceeded
    if (count($_SESSION[$key]) >= $maxAttempts) {
        return false;
    }

    // Add current attempt
    $_SESSION[$key][] = $now;
    return true;
}

/**
 * Log security events
 * @param string $event Event description
 * @param string $userIdentifier User identifier
 * @param string $ipAddress IP address
 * @param array $additionalData Additional data to log
 */
function logSecurityEvent($event, $userIdentifier = '', $ipAddress = '', $additionalData = []) {
    $mysqli = getDatabaseConnection();
    if (!$mysqli) return;

    $logData = [
        'event' => $event,
        'user_identifier' => $userIdentifier,
        'ip_address' => $ipAddress ?: $_SERVER['REMOTE_ADDR'] ?? '',
        'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? '',
        'additional_data' => json_encode($additionalData),
        'created_at' => date('Y-m-d H:i:s')
    ];

    // Check if security_logs table exists, if not create it
    $tableExists = $mysqli->query("SHOW TABLES LIKE 'security_logs'");
    if ($tableExists->num_rows == 0) {
        $createTable = "
            CREATE TABLE security_logs (
                id INT AUTO_INCREMENT PRIMARY KEY,
                event VARCHAR(255) NOT NULL,
                user_identifier VARCHAR(255),
                ip_address VARCHAR(45),
                user_agent TEXT,
                additional_data TEXT,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            )
        ";
        $mysqli->query($createTable);
    }

    insertRecord('security_logs', $logData);
}

/**
 * Execute a custom SQL query and return results
 * @param string $sql SQL query
 * @return array Array of records
 */
function executeCustomQuery($sql) {
    $mysqli = getDatabaseConnection();
    if (!$mysqli) return [];

    $result = $mysqli->query($sql);
    if (!$result) {
        error_log("Query failed: " . $mysqli->error);
        return [];
    }

    return $result->fetch_all(MYSQLI_ASSOC);
}

/**
 * Get all records from a table with optional conditions
 * @param string $table Table name
 * @param string $where WHERE clause (optional)
 * @param string $orderBy ORDER BY clause (optional)
 * @param int $limit LIMIT clause (optional)
 * @return array Array of records
 */
function getAllRecords($table, $where = '', $orderBy = '', $limit = 0) {
    $mysqli = getDatabaseConnection();
    if (!$mysqli) return [];

    $sql = "SELECT * FROM " . escapeString($table);

    if (!empty($where)) {
        $sql .= " WHERE " . $where;
    }

    if (!empty($orderBy)) {
        $sql .= " ORDER BY " . $orderBy;
    }

    if ($limit > 0) {
        $sql .= " LIMIT " . intval($limit);
    }

    $result = $mysqli->query($sql);
    if (!$result) {
        error_log("Query failed: " . $mysqli->error);
        return [];
    }

    return $result->fetch_all(MYSQLI_ASSOC);
}

/**
 * Get a single record by ID
 * @param string $table Table name
 * @param int $id Record ID
 * @return array|null Record data or null if not found
 */
function getRecordById($table, $id) {
    $mysqli = getDatabaseConnection();
    if (!$mysqli) return null;
    
    $sql = "SELECT * FROM " . escapeString($table) . " WHERE id = ?";
    $stmt = $mysqli->prepare($sql);
    
    if (!$stmt) {
        error_log("Prepare failed: " . $mysqli->error);
        return null;
    }
    
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $record = $result->fetch_assoc();
    $stmt->close();
    
    return $record;
}

/**
 * Insert a new record
 * @param string $table Table name
 * @param array $data Associative array of column => value
 * @return int|false Insert ID on success, false on failure
 */
function insertRecord($table, $data) {
    $mysqli = getDatabaseConnection();
    if (!$mysqli) return false;
    
    $columns = array_keys($data);
    $values = array_values($data);
    $placeholders = str_repeat('?,', count($values) - 1) . '?';
    
    $sql = "INSERT INTO " . escapeString($table) . " (" . implode(',', $columns) . ") VALUES ($placeholders)";
    $stmt = $mysqli->prepare($sql);
    
    if (!$stmt) {
        error_log("Prepare failed: " . $mysqli->error);
        return false;
    }
    
    $types = str_repeat('s', count($values));
    $stmt->bind_param($types, ...$values);
    
    if ($stmt->execute()) {
        $insertId = $mysqli->insert_id;
        $stmt->close();
        return $insertId;
    } else {
        error_log("Execute failed: " . $stmt->error);
        $stmt->close();
        return false;
    }
}

/**
 * Update a record by ID
 * @param string $table Table name
 * @param int $id Record ID
 * @param array $data Associative array of column => value
 * @return bool Success status
 */
function updateRecord($table, $id, $data) {
    $mysqli = getDatabaseConnection();
    if (!$mysqli) return false;
    
    $setParts = [];
    $values = [];
    
    foreach ($data as $column => $value) {
        $setParts[] = "$column = ?";
        $values[] = $value;
    }
    
    $values[] = $id; // Add ID for WHERE clause
    
    $sql = "UPDATE " . escapeString($table) . " SET " . implode(', ', $setParts) . " WHERE id = ?";
    $stmt = $mysqli->prepare($sql);
    
    if (!$stmt) {
        error_log("Prepare failed: " . $mysqli->error);
        return false;
    }
    
    $types = str_repeat('s', count($values) - 1) . 'i';
    $stmt->bind_param($types, ...$values);
    
    $success = $stmt->execute();
    if (!$success) {
        error_log("Execute failed: " . $stmt->error);
    }
    
    $stmt->close();
    return $success;
}

/**
 * Delete a record by ID
 * @param string $table Table name
 * @param int $id Record ID
 * @return bool Success status
 */
function deleteRecord($table, $id) {
    $mysqli = getDatabaseConnection();
    if (!$mysqli) return false;
    
    $sql = "DELETE FROM " . escapeString($table) . " WHERE id = ?";
    $stmt = $mysqli->prepare($sql);
    
    if (!$stmt) {
        error_log("Prepare failed: " . $mysqli->error);
        return false;
    }
    
    $stmt->bind_param("i", $id);
    $success = $stmt->execute();
    
    if (!$success) {
        error_log("Execute failed: " . $stmt->error);
    }
    
    $stmt->close();
    return $success;
}

/**
 * Get count of records in a table
 * @param string $table Table name
 * @param string $where WHERE clause (optional)
 * @return int Record count
 */
function getRecordCount($table, $where = '') {
    $mysqli = getDatabaseConnection();
    if (!$mysqli) return 0;
    
    $sql = "SELECT COUNT(*) as count FROM " . escapeString($table);
    
    if (!empty($where)) {
        $sql .= " WHERE " . $where;
    }
    
    $result = $mysqli->query($sql);
    if (!$result) {
        error_log("Query failed: " . $mysqli->error);
        return 0;
    }
    
    $row = $result->fetch_assoc();
    return intval($row['count']);
}

/**
 * Format date for display
 * @param string $date Date string
 * @param string $format Date format (default: 'M d, Y')
 * @return string Formatted date
 */
function formatDate($date, $format = 'M d, Y') {
    if (empty($date) || $date === '0000-00-00' || $date === '0000-00-00 00:00:00') {
        return 'N/A';
    }
    return date($format, strtotime($date));
}

/**
 * Format currency
 * @param float $amount Amount to format
 * @param string $currency Currency symbol (default: '₵')
 * @return string Formatted currency
 */
function formatCurrency($amount, $currency = '₵') {
    return $currency . number_format($amount, 2);
}

/**
 * Generate a unique filename for uploaded files
 * @param string $originalName Original filename
 * @return string Unique filename
 */
function generateUniqueFilename($originalName) {
    $extension = pathinfo($originalName, PATHINFO_EXTENSION);
    return uniqid() . '_' . time() . '.' . $extension;
}

/**
 * Validate image file
 * @param array $file $_FILES array element
 * @param int $maxSize Maximum file size in bytes (default: 5MB)
 * @return array Validation result with 'valid' boolean and 'error' message
 */
function validateImageFile($file, $maxSize = 5242880) {
    $allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'];
    $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];
    
    if ($file['error'] !== UPLOAD_ERR_OK) {
        return ['valid' => false, 'error' => 'File upload error'];
    }
    
    if ($file['size'] > $maxSize) {
        return ['valid' => false, 'error' => 'File size too large (max 5MB)'];
    }
    
    if (!in_array($file['type'], $allowedTypes)) {
        return ['valid' => false, 'error' => 'Invalid file type. Only JPG, PNG, and GIF allowed'];
    }
    
    $extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    if (!in_array($extension, $allowedExtensions)) {
        return ['valid' => false, 'error' => 'Invalid file extension'];
    }
    
    return ['valid' => true, 'error' => ''];
}

/**
 * Upload and save image file
 * @param array $file $_FILES array element
 * @param string $uploadDir Upload directory path
 * @return array Result with 'success' boolean, 'filename' and 'error' message
 */
function uploadImage($file, $uploadDir = 'uploads/images/') {
    $validation = validateImageFile($file);
    if (!$validation['valid']) {
        return ['success' => false, 'filename' => '', 'error' => $validation['error']];
    }
    
    // Create upload directory if it doesn't exist
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0755, true);
    }
    
    $filename = generateUniqueFilename($file['name']);
    $filepath = $uploadDir . $filename;
    
    if (move_uploaded_file($file['tmp_name'], $filepath)) {
        return ['success' => true, 'filename' => $filename, 'error' => ''];
    } else {
        return ['success' => false, 'filename' => '', 'error' => 'Failed to save file'];
    }
}

/**
 * Get image URL with fallback
 * @param string $filename Image filename
 * @param string $uploadDir Upload directory path
 * @param string $fallback Fallback image path
 * @return string Image URL
 */
function getImageUrl($filename, $uploadDir = 'uploads/images/', $fallback = 'images/placeholder.jpg') {
    if (empty($filename)) {
        return $fallback;
    }

    $filepath = $uploadDir . $filename;
    if (file_exists($filepath)) {
        return $filepath;
    }

    return $fallback;
}

/**
 * Delete image file
 * @param string $filename Image filename
 * @param string $uploadDir Upload directory path
 * @return bool True if deleted successfully, false otherwise
 */
function deleteImage($filename, $uploadDir = 'uploads/images/') {
    if (empty($filename)) {
        return false;
    }

    $filepath = $uploadDir . $filename;
    if (file_exists($filepath)) {
        return unlink($filepath);
    }

    return false;
}

/**
 * Get image file size in bytes
 * @param string $filename Image filename
 * @param string $uploadDir Upload directory path
 * @return int File size in bytes, 0 if file doesn't exist
 */
function getImageFileSize($filename, $uploadDir = 'uploads/images/') {
    if (empty($filename)) {
        return 0;
    }

    $filepath = $uploadDir . $filename;
    if (file_exists($filepath)) {
        return filesize($filepath);
    }

    return 0;
}

/**
 * Get formatted file size
 * @param int $bytes File size in bytes
 * @return string Formatted file size (e.g., "1.5 MB")
 */
function formatFileSize($bytes) {
    if ($bytes >= 1073741824) {
        return number_format($bytes / 1073741824, 2) . ' GB';
    } elseif ($bytes >= 1048576) {
        return number_format($bytes / 1048576, 2) . ' MB';
    } elseif ($bytes >= 1024) {
        return number_format($bytes / 1024, 2) . ' KB';
    } else {
        return $bytes . ' bytes';
    }
}

/**
 * Create thumbnail image
 * @param string $sourcePath Source image path
 * @param string $thumbnailPath Thumbnail image path
 * @param int $width Thumbnail width
 * @param int $height Thumbnail height
 * @return bool True if thumbnail created successfully, false otherwise
 */
function createThumbnail($sourcePath, $thumbnailPath, $width = 150, $height = 150) {
    if (!file_exists($sourcePath)) {
        return false;
    }

    $imageInfo = getimagesize($sourcePath);
    if (!$imageInfo) {
        return false;
    }

    $sourceWidth = $imageInfo[0];
    $sourceHeight = $imageInfo[1];
    $mimeType = $imageInfo['mime'];

    // Create source image resource
    switch ($mimeType) {
        case 'image/jpeg':
            $sourceImage = imagecreatefromjpeg($sourcePath);
            break;
        case 'image/png':
            $sourceImage = imagecreatefrompng($sourcePath);
            break;
        case 'image/gif':
            $sourceImage = imagecreatefromgif($sourcePath);
            break;
        default:
            return false;
    }

    if (!$sourceImage) {
        return false;
    }

    // Calculate thumbnail dimensions maintaining aspect ratio
    $aspectRatio = $sourceWidth / $sourceHeight;
    if ($width / $height > $aspectRatio) {
        $width = $height * $aspectRatio;
    } else {
        $height = $width / $aspectRatio;
    }

    // Create thumbnail image
    $thumbnailImage = imagecreatetruecolor($width, $height);

    // Preserve transparency for PNG and GIF
    if ($mimeType == 'image/png' || $mimeType == 'image/gif') {
        imagealphablending($thumbnailImage, false);
        imagesavealpha($thumbnailImage, true);
        $transparent = imagecolorallocatealpha($thumbnailImage, 255, 255, 255, 127);
        imagefilledrectangle($thumbnailImage, 0, 0, $width, $height, $transparent);
    }

    // Resize image
    imagecopyresampled($thumbnailImage, $sourceImage, 0, 0, 0, 0, $width, $height, $sourceWidth, $sourceHeight);

    // Create thumbnail directory if it doesn't exist
    $thumbnailDir = dirname($thumbnailPath);
    if (!is_dir($thumbnailDir)) {
        mkdir($thumbnailDir, 0755, true);
    }

    // Save thumbnail
    $result = false;
    switch ($mimeType) {
        case 'image/jpeg':
            $result = imagejpeg($thumbnailImage, $thumbnailPath, 85);
            break;
        case 'image/png':
            $result = imagepng($thumbnailImage, $thumbnailPath);
            break;
        case 'image/gif':
            $result = imagegif($thumbnailImage, $thumbnailPath);
            break;
    }

    // Clean up memory
    imagedestroy($sourceImage);
    imagedestroy($thumbnailImage);

    return $result;
}

/**
 * Check if user is logged in and has admin privileges
 * @return bool True if authenticated admin, false otherwise
 */
function isAdminLoggedIn() {
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }

    return isset($_SESSION['admin_logged_in']) &&
           $_SESSION['admin_logged_in'] === true &&
           isset($_SESSION['admin_user_id']) &&
           ($_SESSION['admin_user_role'] === 'Admin' || $_SESSION['admin_user_role'] === 'Super Admin');
}

/**
 * Require admin authentication - redirect to login if not authenticated
 */
function requireAdminAuth() {
    if (!isAdminLoggedIn()) {
        header('Location: ./login.php');
        exit;
    }

    // Check session timeout (30 minutes)
    $timeout_duration = 1800; // 30 minutes
    if (isset($_SESSION['login_time']) && (time() - $_SESSION['login_time']) > $timeout_duration) {
        // Session expired
        session_destroy();
        header('Location: ../login.php?timeout=1');
        exit;
    }

    // Update last activity time
    $_SESSION['last_activity'] = time();
}

/**
 * Get current admin user information
 * @return array|null User data or null if not logged in
 */
function getCurrentAdminUser() {
    if (!isAdminLoggedIn()) {
        return null;
    }

    return [
        'id' => $_SESSION['admin_user_id'],
        'name' => $_SESSION['admin_user_name'],
        'email' => $_SESSION['admin_user_email'],
        'role' => $_SESSION['admin_user_role']
    ];
}

/**
 * Hash password securely
 * @param string $password Plain text password
 * @return string Hashed password
 */
function hashPassword($password) {
    return password_hash($password, PASSWORD_DEFAULT);
}

/**
 * Verify password against hash
 * @param string $password Plain text password
 * @param string $hash Hashed password
 * @return bool True if password matches, false otherwise
 */
function verifyPassword($password, $hash) {
    return password_verify($password, $hash);
}

?>
