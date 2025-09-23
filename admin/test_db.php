<?php
/**
 * Database Connection Test
 */

require_once 'config/config.php';

echo "<h2>Database Connection Test</h2>";

try {
    // Test database connection
    $db = getDB();
    echo "<p style='color: green;'>✓ Database connection successful</p>";
    
    // Test if tables exist
    $tables = ['admin_users', 'inquiries', 'settings', 'file_uploads', 'activity_logs'];
    
    foreach ($tables as $table) {
        try {
            $result = dbGetValue("SELECT COUNT(*) FROM $table");
            echo "<p style='color: green;'>✓ Table '$table' exists with $result records</p>";
        } catch (Exception $e) {
            echo "<p style='color: red;'>✗ Table '$table' error: " . $e->getMessage() . "</p>";
        }
    }
    
    // Test helper functions
    echo "<h3>Helper Functions Test</h3>";
    
    // Test formatFileSize
    echo "<p>formatFileSize(1024): " . formatFileSize(1024) . "</p>";
    echo "<p>formatFileSize(1048576): " . formatFileSize(1048576) . "</p>";
    
    // Test validateUploadedFile (mock test)
    echo "<p>validateUploadedFile function exists: " . (function_exists('validateUploadedFile') ? 'Yes' : 'No') . "</p>";
    
    // Test dbGetValue
    try {
        $count = dbGetValue("SELECT COUNT(*) FROM admin_users");
        echo "<p style='color: green;'>✓ dbGetValue works: $count admin users</p>";
    } catch (Exception $e) {
        echo "<p style='color: red;'>✗ dbGetValue error: " . $e->getMessage() . "</p>";
    }
    
} catch (Exception $e) {
    echo "<p style='color: red;'>✗ Database connection failed: " . $e->getMessage() . "</p>";
}

echo "<h3>Configuration Check</h3>";
echo "<p>UPLOAD_PATH: " . UPLOAD_PATH . "</p>";
echo "<p>UPLOAD_URL: " . UPLOAD_URL . "</p>";
echo "<p>MAX_FILE_SIZE: " . formatFileSize(MAX_FILE_SIZE) . "</p>";
echo "<p>Session status: " . session_status() . "</p>";

// Check if directories exist
$dirs = [UPLOAD_PATH, UPLOAD_PATH . '/images', UPLOAD_PATH . '/documents', UPLOAD_PATH . '/general'];
foreach ($dirs as $dir) {
    echo "<p>Directory '$dir': " . (is_dir($dir) ? 'Exists' : 'Missing') . "</p>";
}
?>
