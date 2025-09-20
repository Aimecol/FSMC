<?php
/**
 * Common Functions for FSMC Project
 * Database operations and utility functions
 */

require_once __DIR__ . '/../config/database.php';

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
        header('Location: ../login.php');
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

/**
 * Sanitize input data
 * @param string $data Input data
 * @return string Sanitized data
 */
function sanitizeInput($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}
?>
