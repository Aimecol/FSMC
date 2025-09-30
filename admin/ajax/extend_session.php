<?php
/**
 * AJAX Session Extension Script
 * Extends user session to prevent timeout during active use
 */

// Define access constant
define('ADMIN_ACCESS', true);

// Include configuration
require_once '../config/config.php';
require_once '../config/database.php';

// Set JSON response header
header('Content-Type: application/json');

// Check if user is authenticated
if (!isLoggedIn()) {
    http_response_code(401);
    echo json_encode([
        'success' => false,
        'message' => 'Not authenticated',
        'redirect' => ADMIN_BASE_URL . '/login.php'
    ]);
    exit;
}

try {
    // Check if session is still valid
    if (!isValidSession()) {
        http_response_code(401);
        echo json_encode([
            'success' => false,
            'message' => 'Session expired',
            'redirect' => ADMIN_BASE_URL . '/login.php'
        ]);
        exit;
    }
    
    // Update last activity timestamp (already done in isValidSession)
    // Get current user info
    $user = getCurrentUser();
    
    if (!$user) {
        http_response_code(401);
        echo json_encode([
            'success' => false,
            'message' => 'User not found',
            'redirect' => ADMIN_BASE_URL . '/login.php'
        ]);
        exit;
    }
    
    // Calculate remaining session time
    $lastActivity = $_SESSION['last_activity'] ?? time();
    $remainingTime = SESSION_TIMEOUT - (time() - $lastActivity);
    
    // Update last login time in database (optional)
    try {
        dbExecute("UPDATE admin_users SET last_login = CURRENT_TIMESTAMP WHERE id = ?", [$user['id']]);
    } catch (Exception $e) {
        // Don't fail if database update fails
        error_log('Failed to update last login: ' . $e->getMessage());
    }
    
    // Return success response
    echo json_encode([
        'success' => true,
        'message' => 'Session extended successfully',
        'user' => [
            'id' => $user['id'],
            'username' => $user['username'],
            'full_name' => $user['full_name'],
            'role' => $user['role']
        ],
        'session' => [
            'remaining_time' => $remainingTime,
            'timeout_duration' => SESSION_TIMEOUT,
            'last_activity' => date('Y-m-d H:i:s', $lastActivity)
        ],
        'server_time' => date('Y-m-d H:i:s')
    ]);
    
} catch (Exception $e) {
    error_log('Session extension error: ' . $e->getMessage());
    
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Server error occurred',
        'error' => $e->getMessage()
    ]);
}
?>
