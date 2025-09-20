<?php
session_start();

// Include database configuration and functions
require_once '../config/database.php';
require_once '../includes/functions.php';

// Log logout activity if user is logged in
if (isset($_SESSION['admin_user_id'])) {
    logActivity($_SESSION['admin_user_id'], 'logout', 'Admin user logged out');
}

// Clear all session variables
$_SESSION = array();

// Delete the session cookie
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// Destroy the session
session_destroy();

// Redirect to login page
header('Location: login.php?logout=success');
exit;

/**
 * Log user activity
 */
function logActivity($user_id, $action, $description) {
    $mysqli = getDatabaseConnection();
    if (!$mysqli) return false;
    
    $user_id = intval($user_id);
    $action = $mysqli->real_escape_string($action);
    $description = $mysqli->real_escape_string($description);
    $ip_address = $_SERVER['REMOTE_ADDR'];
    
    $query = "INSERT INTO user_activity_logs (user_id, action, description, ip_address) 
              VALUES ($user_id, '$action', '$description', '$ip_address')";
    
    return $mysqli->query($query);
}
?>
