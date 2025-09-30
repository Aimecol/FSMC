<?php
/**
 * AJAX Admin Actions Handler
 * Handles various admin panel AJAX requests
 */

// Define access constant
define('ADMIN_ACCESS', true);

// Include configuration
require_once '../config/config.php';
require_once '../config/database.php';

// Set JSON response header
header('Content-Type: application/json');

// Check authentication
if (!isValidSession()) {
    http_response_code(401);
    echo json_encode([
        'success' => false,
        'message' => 'Authentication required',
        'redirect' => ADMIN_BASE_URL . '/login.php'
    ]);
    exit;
}

// Get current user
$currentUser = getCurrentUser();
if (!$currentUser) {
    http_response_code(401);
    echo json_encode([
        'success' => false,
        'message' => 'User not found',
        'redirect' => ADMIN_BASE_URL . '/login.php'
    ]);
    exit;
}

// Get action from request
$action = $_POST['action'] ?? $_GET['action'] ?? '';

try {
    switch ($action) {
        case 'get_user_info':
            echo json_encode([
                'success' => true,
                'user' => [
                    'id' => $currentUser['id'],
                    'username' => $currentUser['username'],
                    'full_name' => $currentUser['full_name'],
                    'email' => $currentUser['email'],
                    'role' => $currentUser['role'],
                    'status' => $currentUser['status'],
                    'last_login' => $currentUser['last_login'],
                    'created_at' => $currentUser['created_at']
                ]
            ]);
            break;
            
        case 'update_last_activity':
            // This is handled automatically by isValidSession()
            echo json_encode([
                'success' => true,
                'message' => 'Activity updated',
                'timestamp' => date('Y-m-d H:i:s')
            ]);
            break;
            
        case 'get_system_info':
            if (!hasPermission('view')) {
                http_response_code(403);
                echo json_encode([
                    'success' => false,
                    'message' => 'Insufficient permissions'
                ]);
                exit;
            }
            
            echo json_encode([
                'success' => true,
                'system' => [
                    'php_version' => phpversion(),
                    'server_time' => date('Y-m-d H:i:s'),
                    'session_timeout' => SESSION_TIMEOUT,
                    'max_file_size' => MAX_FILE_SIZE,
                    'admin_version' => '1.0.0'
                ]
            ]);
            break;
            
        case 'check_email_queue':
            if (!hasPermission('view')) {
                http_response_code(403);
                echo json_encode([
                    'success' => false,
                    'message' => 'Insufficient permissions'
                ]);
                exit;
            }
            
            $queueDir = dirname(__DIR__) . '/email_queue';
            $queueCount = 0;
            
            if (is_dir($queueDir)) {
                $files = glob($queueDir . '/email_*.json');
                $queueCount = count($files);
            }
            
            echo json_encode([
                'success' => true,
                'email_queue' => [
                    'pending_count' => $queueCount,
                    'queue_dir' => $queueDir,
                    'processor_url' => ADMIN_BASE_URL . '/email_queue_processor.php'
                ]
            ]);
            break;
            
        default:
            http_response_code(400);
            echo json_encode([
                'success' => false,
                'message' => 'Invalid action: ' . htmlspecialchars($action),
                'available_actions' => [
                    'get_user_info',
                    'update_last_activity', 
                    'get_system_info',
                    'check_email_queue'
                ]
            ]);
            break;
    }
    
} catch (Exception $e) {
    error_log('Admin AJAX error: ' . $e->getMessage());
    
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Server error occurred',
        'error' => $e->getMessage()
    ]);
}
?>
