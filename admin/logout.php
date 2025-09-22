<?php
/**
 * Admin Logout for FSMC Admin System
 * Created: 2025-01-22
 */

require_once 'config/config.php';

// Verify CSRF token if provided
if (isset($_GET['token']) && !verifyCSRFToken($_GET['token'])) {
    setErrorMessage('Invalid security token.');
    redirect(ADMIN_BASE_URL . '/index.php');
}

// Perform logout
if (isLoggedIn()) {
    $auth = new Auth();
    $auth->logout();
    setSuccessMessage('You have been successfully logged out.');
}

// Redirect to login page
redirect(ADMIN_BASE_URL . '/login.php');
?>
