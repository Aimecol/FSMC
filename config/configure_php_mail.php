<?php
/**
 * Configure PHP mail settings for XAMPP
 * This script configures PHP's mail() function to work with Gmail SMTP
 */

// Define access constant
define('FSMC_ACCESS', true);

// Configure PHP mail settings at runtime
ini_set('SMTP', 'smtp.gmail.com');
ini_set('smtp_port', '587');
ini_set('sendmail_from', 'aimecol314@gmail.com');

// Note: PHP's built-in mail() function doesn't support SMTP authentication
// So we need to use a different approach for Gmail

/**
 * Alternative email sending using cURL for better compatibility
 */
function sendEmailViaCurl($to, $subject, $htmlBody, $fromEmail, $fromName, $replyTo = null) {
    // This is a placeholder for a cURL-based email solution
    // For now, we'll focus on fixing the SMTP connection
    return false;
}

/**
 * Configure XAMPP for email sending
 */
function configureXAMPPEmail() {
    $phpIniPath = php_ini_loaded_file();
    $recommendations = [];
    
    // Check current settings
    $currentSMTP = ini_get('SMTP');
    $currentPort = ini_get('smtp_port');
    $currentFrom = ini_get('sendmail_from');
    
    $recommendations[] = "Current PHP.ini file: " . $phpIniPath;
    $recommendations[] = "Current SMTP: " . ($currentSMTP ?: 'Not set');
    $recommendations[] = "Current smtp_port: " . ($currentPort ?: 'Not set');
    $recommendations[] = "Current sendmail_from: " . ($currentFrom ?: 'Not set');
    
    // Provide recommendations
    $recommendations[] = "\nTo fix PHP mail() function, add these lines to php.ini:";
    $recommendations[] = "[mail function]";
    $recommendations[] = "SMTP = smtp.gmail.com";
    $recommendations[] = "smtp_port = 587";
    $recommendations[] = "sendmail_from = aimecol314@gmail.com";
    $recommendations[] = "auth_username = aimecol314@gmail.com";
    $recommendations[] = "auth_password = fgut iyvb yafe avxr";
    
    return $recommendations;
}

// Test if we can configure at runtime
try {
    // Try to set mail configuration
    ini_set('SMTP', 'smtp.gmail.com');
    ini_set('smtp_port', '587');
    ini_set('sendmail_from', 'aimecol314@gmail.com');
    
    $configured = true;
} catch (Exception $e) {
    $configured = false;
}

if (!$configured) {
    error_log('Could not configure PHP mail settings at runtime');
}
?>
