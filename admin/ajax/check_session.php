<?php
/**
 * AJAX Session Check Script
 * Returns current session status
 */

// Define access constant
define('ADMIN_ACCESS', true);

// Include configuration
require_once '../config/config.php';
require_once '../config/database.php';

// Set JSON response header
header('Content-Type: application/json');

try {
    // Check if user is authenticated
    if (!isLoggedIn()) {
        echo json_encode([
            'success' => false,
            'authenticated' => false,
            'message' => 'Not authenticated',
            'redirect' => ADMIN_BASE_URL . '/login.php'
        ]);
        exit;
    }
    
    // Check if session is still valid
    if (!isValidSession()) {
        echo json_encode([
            'success' => false,
            'authenticated' => false,
            'message' => 'Session expired',
            'redirect' => ADMIN_BASE_URL . '/login.php'
        ]);
        exit;
    }
    
    // Get current user info
    $user = getCurrentUser();
    
    if (!$user) {
        echo json_encode([
            'success' => false,
            'authenticated' => false,
            'message' => 'User not found',
            'redirect' => ADMIN_BASE_URL . '/login.php'
        ]);
        exit;
    }
    
    // Calculate remaining session time
    $lastActivity = $_SESSION['last_activity'] ?? time();
    $remainingTime = SESSION_TIMEOUT - (time() - $lastActivity);
    
    // Return session status
    echo json_encode([
        'success' => true,
        'authenticated' => true,
        'user' => [
            'id' => $user['id'],
            'username' => $user['username'],
            'full_name' => $user['full_name'],
            'role' => $user['role'],
            'status' => $user['status']
        ],
        'session' => [
            'remaining_time' => $remainingTime,
            'timeout_duration' => SESSION_TIMEOUT,
            'last_activity' => date('Y-m-d H:i:s', $lastActivity),
            'expires_at' => date('Y-m-d H:i:s', $lastActivity + SESSION_TIMEOUT)
        ],
        'server_time' => date('Y-m-d H:i:s'),
        'permissions' => [
            'view' => hasPermission('view'),
            'create' => hasPermission('create'),
            'edit' => hasPermission('edit'),
            'delete' => hasPermission('delete')
        ]
    ]);
    
} catch (Exception $e) {
    error_log('Session check error: ' . $e->getMessage());
    
    echo json_encode([
        'success' => false,
        'authenticated' => false,
        'message' => 'Server error occurred',
        'error' => $e->getMessage()
    ]);
}
?>
