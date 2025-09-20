<?php
/**
 * Database Configuration for FSMC Project
 * Using MySQLi for database connections
 */

// Database configuration constants
define('DB_HOST', 'localhost');
define('DB_NAME', 'fsmc_database');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_CHARSET', 'utf8mb4');

// Global database connection variable
$mysqli = null;

/**
 * Create database connection using MySQLi
 * @return mysqli|false Returns MySQLi connection object or false on failure
 */
function getDatabaseConnection() {
    global $mysqli;
    
    if ($mysqli === null) {
        // Create connection
        $mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
        
        // Check connection
        if ($mysqli->connect_error) {
            error_log("Database connection failed: " . $mysqli->connect_error);
            return false;
        }
        
        // Set charset
        if (!$mysqli->set_charset(DB_CHARSET)) {
            error_log("Error setting charset: " . $mysqli->error);
            return false;
        }
    }
    
    return $mysqli;
}

/**
 * Execute a prepared statement with error handling
 * @param string $query SQL query with placeholders
 * @param string $types Parameter types (e.g., 'ssi' for string, string, integer)
 * @param array $params Parameters to bind
 * @return mysqli_result|bool Returns result set for SELECT queries, true/false for others
 */
function executeQuery($query, $types = '', $params = []) {
    $mysqli = getDatabaseConnection();
    if (!$mysqli) {
        return false;
    }
    
    $stmt = $mysqli->prepare($query);
    if (!$stmt) {
        error_log("Prepare failed: " . $mysqli->error);
        return false;
    }
    
    if (!empty($params)) {
        $stmt->bind_param($types, ...$params);
    }
    
    if (!$stmt->execute()) {
        error_log("Execute failed: " . $stmt->error);
        $stmt->close();
        return false;
    }
    
    $result = $stmt->get_result();
    $stmt->close();
    
    return $result;
}

/**
 * Sanitize input data
 * @param string $data Input data to sanitize
 * @return string Sanitized data
 */
function sanitizeInput($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
    return $data;
}

/**
 * Escape string for database insertion
 * @param string $string String to escape
 * @return string Escaped string
 */
function escapeString($string) {
    $mysqli = getDatabaseConnection();
    if (!$mysqli) {
        return addslashes($string);
    }
    return $mysqli->real_escape_string($string);
}

/**
 * Close database connection
 */
function closeDatabaseConnection() {
    global $mysqli;
    if ($mysqli) {
        $mysqli->close();
        $mysqli = null;
    }
}

// Initialize database connection
$mysqli = getDatabaseConnection();

if (!$mysqli) {
    die("Database connection failed. Please check your configuration.");
}
?>
