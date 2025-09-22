<?php
/**
 * Security Configuration for FSMC Project
 */

// Security headers
function setSecurityHeaders() {
    // Prevent clickjacking
    header('X-Frame-Options: DENY');
    
    // Prevent MIME type sniffing
    header('X-Content-Type-Options: nosniff');
    
    // Enable XSS protection
    header('X-XSS-Protection: 1; mode=block');
    
    // Referrer policy
    header('Referrer-Policy: strict-origin-when-cross-origin');
    
    // Content Security Policy
    header("Content-Security-Policy: default-src 'self'; script-src 'self' 'unsafe-inline' https://cdnjs.cloudflare.com https://cdn.tiny.cloud; style-src 'self' 'unsafe-inline' https://cdnjs.cloudflare.com; img-src 'self' data: https:; font-src 'self' https://cdnjs.cloudflare.com; connect-src 'self';");
    
    // HTTPS enforcement (uncomment in production)
    // header('Strict-Transport-Security: max-age=31536000; includeSubDomains; preload');
}

// Session security configuration
function configureSecureSession() {
    // Only configure session settings if session is not active
    if (session_status() == PHP_SESSION_NONE) {
        // Session configuration
        ini_set('session.cookie_httponly', 1);
        ini_set('session.cookie_secure', 0); // Set to 1 in production with HTTPS
        ini_set('session.cookie_samesite', 'Strict');
        ini_set('session.use_strict_mode', 1);
        ini_set('session.gc_maxlifetime', 1800); // 30 minutes

        // Start session after configuration
        session_start();
    }

    // Regenerate session ID every 15 minutes
    if (!isset($_SESSION['last_regeneration'])) {
        $_SESSION['last_regeneration'] = time();
    } elseif (time() - $_SESSION['last_regeneration'] > 900) {
        session_regenerate_id(true);
        $_SESSION['last_regeneration'] = time();
    }
}

// Input sanitization (function already declared in database.php)
// Using the existing sanitizeInput() function from database.php

// SQL injection prevention (using escapeString function from functions.php)
// This function is implemented in includes/functions.php as escapeString()

// File upload security
function validateFileUpload($file, $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'], $maxSize = 5242880) {
    $errors = [];
    
    // Check if file was uploaded
    if (!isset($file['tmp_name']) || !is_uploaded_file($file['tmp_name'])) {
        $errors[] = 'No file uploaded or upload error occurred';
        return ['valid' => false, 'errors' => $errors];
    }
    
    // Check file size
    if ($file['size'] > $maxSize) {
        $errors[] = 'File size exceeds maximum allowed size';
    }
    
    // Check MIME type
    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mimeType = finfo_file($finfo, $file['tmp_name']);
    finfo_close($finfo);
    
    if (!in_array($mimeType, $allowedTypes)) {
        $errors[] = 'Invalid file type';
    }
    
    // Check file extension
    $extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    $allowedExtensions = [];
    foreach ($allowedTypes as $type) {
        switch ($type) {
            case 'image/jpeg':
                $allowedExtensions[] = 'jpg';
                $allowedExtensions[] = 'jpeg';
                break;
            case 'image/png':
                $allowedExtensions[] = 'png';
                break;
            case 'image/gif':
                $allowedExtensions[] = 'gif';
                break;
        }
    }
    
    if (!in_array($extension, $allowedExtensions)) {
        $errors[] = 'Invalid file extension';
    }
    
    // Check for malicious content
    $content = file_get_contents($file['tmp_name']);
    if (strpos($content, '<?php') !== false || strpos($content, '<script') !== false) {
        $errors[] = 'File contains potentially malicious content';
    }
    
    return [
        'valid' => empty($errors),
        'errors' => $errors
    ];
}

// IP address validation and blocking
function isIPBlocked($ip) {
    // You can implement IP blocking logic here
    // For example, check against a database of blocked IPs
    $blockedIPs = [
        // Add blocked IP addresses here
    ];
    
    return in_array($ip, $blockedIPs);
}

// User agent validation
function validateUserAgent($userAgent) {
    // Block suspicious user agents
    $suspiciousPatterns = [
        '/bot/i',
        '/crawler/i',
        '/spider/i',
        '/scraper/i',
        '/curl/i',
        '/wget/i',
    ];
    
    foreach ($suspiciousPatterns as $pattern) {
        if (preg_match($pattern, $userAgent)) {
            return false;
        }
    }
    
    return true;
}

// Request validation
function validateRequest() {
    $userAgent = $_SERVER['HTTP_USER_AGENT'] ?? '';
    $ip = $_SERVER['REMOTE_ADDR'] ?? '';
    
    // Check if IP is blocked
    if (isIPBlocked($ip)) {
        http_response_code(403);
        die('Access denied');
    }
    
    // Validate user agent (optional - might block legitimate users)
    // if (!validateUserAgent($userAgent)) {
    //     http_response_code(403);
    //     die('Access denied');
    // }
    
    // Check for common attack patterns in request
    $requestUri = $_SERVER['REQUEST_URI'] ?? '';
    $suspiciousPatterns = [
        '/\.\./i',           // Directory traversal
        '/union.*select/i',  // SQL injection
        '/<script/i',        // XSS
        '/javascript:/i',    // XSS
        '/vbscript:/i',      // XSS
        '/onload=/i',        // XSS
        '/onerror=/i',       // XSS
    ];
    
    foreach ($suspiciousPatterns as $pattern) {
        if (preg_match($pattern, $requestUri)) {
            logSecurityEvent('suspicious_request', '', $ip, ['pattern' => $pattern, 'uri' => $requestUri]);
            http_response_code(400);
            die('Bad request');
        }
    }
}

// Initialize security
function initializeSecurity() {
    // Set security headers
    setSecurityHeaders();
    
    // Configure secure session
    configureSecureSession();
    
    // Validate request
    validateRequest();
}

// Password policy enforcement
function enforcePasswordPolicy($password) {
    $errors = [];
    
    if (strlen($password) < 8) {
        $errors[] = 'Password must be at least 8 characters long';
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
    
    if (!preg_match('/[^A-Za-z0-9]/', $password)) {
        $errors[] = 'Password must contain at least one special character';
    }
    
    // Check against common passwords
    $commonPasswords = [
        'password', '123456', '123456789', 'qwerty', 'abc123',
        'password123', 'admin', 'letmein', 'welcome', 'monkey'
    ];
    
    if (in_array(strtolower($password), $commonPasswords)) {
        $errors[] = 'Password is too common. Please choose a more secure password';
    }
    
    return [
        'valid' => empty($errors),
        'errors' => $errors
    ];
}

// Security initialization is now manual - call initializeSecurity() before any output
// This prevents "headers already sent" errors
?>
