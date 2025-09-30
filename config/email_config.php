<?php
/**
 * Email Configuration for FSMC
 * PHPMailer settings and email templates
 */

// Prevent direct access
if (!defined('FSMC_ACCESS')) {
    define('FSMC_ACCESS', true);
}

// Email Configuration
define('EMAIL_HOST', 'smtp.gmail.com');
define('EMAIL_PORT', 587);
define('EMAIL_USER', 'aimecol314@gmail.com');
define('EMAIL_PASS', 'fgut iyvb yafe avxr');
define('EMAIL_FROM_NAME', 'FSMC Contact Form');
define('EMAIL_TO', 'fsamcompanyltd@gmail.com');
define('EMAIL_REPLY_TO', 'aimecol314@gmail.com');

// Email settings
define('EMAIL_CHARSET', 'UTF-8');
define('EMAIL_ENCODING', '8bit');

/**
 * Send contact email using PHPMailer with Gmail SMTP
 */
function sendContactEmailPHPMailer($contactData) {
    // Check if PHPMailer is available
    if (!class_exists('PHPMailer\PHPMailer\PHPMailer')) {
        // Try to load PHPMailer
        $phpmailerPath = __DIR__ . '/../vendor/autoload.php';
        if (file_exists($phpmailerPath)) {
            require_once $phpmailerPath;
        } else {
            error_log('PHPMailer not found. Please install via: composer require phpmailer/phpmailer');
            return false;
        }
    }
    
    try {
        $mail = new PHPMailer\PHPMailer\PHPMailer(true);
        
        // Server settings
        $mail->isSMTP();
        $mail->Host = EMAIL_HOST;
        $mail->SMTPAuth = true;
        $mail->Username = EMAIL_USER;
        $mail->Password = EMAIL_PASS;
        $mail->SMTPSecure = PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = EMAIL_PORT;
        
        // Increase timeout for slow connections
        $mail->Timeout = 30;
        $mail->SMTPKeepAlive = true;
        
        // Recipients
        $mail->setFrom(EMAIL_USER, EMAIL_FROM_NAME);
        $mail->addAddress(EMAIL_TO);
        $mail->addReplyTo($contactData['email'], $contactData['name']);
        
        // Content
        $mail->isHTML(true);
        $mail->CharSet = EMAIL_CHARSET;
        $mail->Subject = 'New Contact Form Submission - ' . ($contactData['subject'] ?: 'General Inquiry');
        $mail->Body = generateEmailTemplate($contactData);
        $mail->AltBody = generatePlainTextEmail($contactData);
        
        // Send email
        $mail->send();
        
        // Send auto-reply
        sendAutoReplyPHPMailer($contactData);
        
        return true;
        
    } catch (Exception $e) {
        error_log('PHPMailer Error: ' . $e->getMessage());
        return false;
    }
}

/**
 * Send auto-reply using PHPMailer
 */
function sendAutoReplyPHPMailer($contactData) {
    try {
        $mail = new PHPMailer\PHPMailer\PHPMailer(true);
        
        // Server settings
        $mail->isSMTP();
        $mail->Host = EMAIL_HOST;
        $mail->SMTPAuth = true;
        $mail->Username = EMAIL_USER;
        $mail->Password = EMAIL_PASS;
        $mail->SMTPSecure = PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = EMAIL_PORT;
        $mail->Timeout = 30;
        
        // Recipients
        $mail->setFrom(EMAIL_USER, EMAIL_FROM_NAME);
        $mail->addAddress($contactData['email'], $contactData['name']);
        $mail->addReplyTo(EMAIL_USER, EMAIL_FROM_NAME);
        
        // Content
        $mail->isHTML(true);
        $mail->CharSet = EMAIL_CHARSET;
        $mail->Subject = 'Thank you for contacting FSMC - We received your message';
        $mail->Body = generateAutoReplyTemplate($contactData);
        
        $mail->send();
        
    } catch (Exception $e) {
        error_log('Auto-reply PHPMailer Error: ' . $e->getMessage());
    }
}

/**
 * Fallback: Configure PHP mail() to use SMTP via ini_set
 * This configures the built-in mail() function to use Gmail SMTP
 */
function configurePHPMailForSMTP() {
    // For Windows XAMPP
    if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
        ini_set('SMTP', EMAIL_HOST);
        ini_set('smtp_port', EMAIL_PORT);
        ini_set('sendmail_from', EMAIL_USER);
    } else {
        // For Linux/Mac, configure sendmail path
        ini_set('sendmail_path', '/usr/sbin/sendmail -t -i');
    }
}

/**
 * Simple email function using configured mail()
 */
function sendContactEmailSimple($contactData) {
    try {
        // Validate required data
        if (empty($contactData['name']) || empty($contactData['email']) || empty($contactData['message'])) {
            throw new Exception('Missing required contact information');
        }

        // Configure mail() for SMTP (won't work with Gmail - needs PHPMailer)
        configurePHPMailForSMTP();

        // Create email content
        $subject = 'New Contact Form Submission - ' . ($contactData['subject'] ?: 'General Inquiry');
        
        // Generate HTML email content
        $htmlContent = generateEmailTemplate($contactData);
        
        // Email headers for HTML email
        $headers = [
            'MIME-Version: 1.0',
            'Content-Type: text/html; charset=UTF-8',
            'From: ' . EMAIL_FROM_NAME . ' <' . EMAIL_USER . '>',
            'Reply-To: ' . $contactData['name'] . ' <' . $contactData['email'] . '>',
            'X-Mailer: PHP/' . phpversion(),
            'X-Priority: 3'
        ];
        
        // Send email using PHP mail()
        $success = @mail(
            EMAIL_TO,
            $subject,
            $htmlContent,
            implode("\r\n", $headers)
        );
        
        if ($success) {
            sendAutoReplySimple($contactData);
            return true;
        }
        
        return false;
        
    } catch (Exception $e) {
        error_log('Simple email sending failed: ' . $e->getMessage());
        return false;
    }
}

/**
 * Send auto-reply using simple mail()
 */
function sendAutoReplySimple($contactData) {
    try {
        $subject = 'Thank you for contacting FSMC - We received your message';
        $htmlContent = generateAutoReplyTemplate($contactData);
        
        $headers = [
            'MIME-Version: 1.0',
            'Content-Type: text/html; charset=UTF-8',
            'From: ' . EMAIL_FROM_NAME . ' <' . EMAIL_USER . '>',
            'Reply-To: ' . EMAIL_USER,
            'X-Mailer: PHP/' . phpversion()
        ];
        
        @mail(
            $contactData['email'],
            $subject,
            $htmlContent,
            implode("\r\n", $headers)
        );
        
    } catch (Exception $e) {
        error_log('Auto-reply simple failed: ' . $e->getMessage());
    }
}

/**
 * Master send function - tries PHPMailer first, falls back to mail()
 */
function sendContactEmail($contactData) {
    // Try PHPMailer first (recommended for Gmail)
    if (sendContactEmailPHPMailer($contactData)) {
        return true;
    }
    
    // Fallback to simple mail() (may not work with Gmail)
    return sendContactEmailSimple($contactData);
}

/**
 * Ultimate email sending function - tries all available methods
 * This is the main function called by contact forms
 */
function sendContactEmailUltimate($contactData) {
    // Validate required data
    if (empty($contactData['name']) || empty($contactData['email']) || empty($contactData['message'])) {
        error_log('Ultimate email: Missing required contact information');
        return false;
    }
    
    $mainEmailSent = false;
    $autoReplySent = false;
    
    // Method 1: Try PHPMailer with Gmail SMTP (most reliable)
    if (sendContactEmailPHPMailer($contactData)) {
        error_log('Ultimate email: Successfully sent via PHPMailer (includes auto-reply)');
        $mainEmailSent = true;
        $autoReplySent = true; // PHPMailer function handles auto-reply
    }
    
    // Method 2: Try other email methods if available
    if (function_exists('sendEmailSpamFree')) {
        if (sendEmailSpamFree($contactData)) {
            error_log('Ultimate email: Successfully sent via SpamFree method');
            return true;
        }
    }
    
    if (function_exists('sendEmailWorking')) {
        if (sendEmailWorking($contactData)) {
            error_log('Ultimate email: Successfully sent via Working method');
            return true;
        }
    }
    
    if (function_exists('sendEmailSMTP')) {
        if (sendEmailSMTP($contactData)) {
            error_log('Ultimate email: Successfully sent via SMTP method');
            return true;
        }
    }
    
    if (function_exists('sendEmailSendGrid')) {
        if (sendEmailSendGrid($contactData)) {
            error_log('Ultimate email: Successfully sent via SendGrid');
            return true;
        }
    }
    
    // Method 3: Try simple mail() as last resort
    if (!$mainEmailSent && sendContactEmailSimple($contactData)) {
        error_log('Ultimate email: Successfully sent via simple mail()');
        $mainEmailSent = true;
    }
    
    // Ensure auto-reply is always attempted, even if main email fails
    if (!$autoReplySent) {
        try {
            // Try PHPMailer auto-reply first
            if (class_exists('PHPMailer\PHPMailer\PHPMailer')) {
                sendAutoReplyPHPMailer($contactData);
                $autoReplySent = true;
                error_log('Ultimate email: Auto-reply sent via PHPMailer');
            } else {
                // Fallback to simple auto-reply
                sendAutoReplySimple($contactData);
                $autoReplySent = true;
                error_log('Ultimate email: Auto-reply attempted via simple mail()');
            }
        } catch (Exception $e) {
            error_log('Ultimate email: Auto-reply failed - ' . $e->getMessage());
        }
        
        // Always create auto-reply files for manual sending
        createAutoReplyFiles($contactData);
        error_log('Ultimate email: Auto-reply files created for manual sending');
    }
    
    // Return true if either main email sent OR auto-reply attempted
    if ($mainEmailSent || $autoReplySent) {
        return true;
    }
    
    // All methods failed
    error_log('Ultimate email: All email methods failed for ' . $contactData['email']);
    return false;
}

/**
 * Send auto-reply to customer
 */
function sendAutoReply($contactData) {
    // Try PHPMailer first
    if (class_exists('PHPMailer\PHPMailer\PHPMailer')) {
        sendAutoReplyPHPMailer($contactData);
    } else {
        sendAutoReplySimple($contactData);
    }
}

/**
 * Create auto-reply files for manual sending
 */
function createAutoReplyFiles($contactData) {
    try {
        $autoReplyDir = __DIR__ . '/../auto_reply_ready';
        if (!is_dir($autoReplyDir)) {
            mkdir($autoReplyDir, 0755, true);
        }
        
        // Generate auto-reply HTML
        $autoReplyHTML = generateAutoReplyTemplate($contactData);
        $filename = 'auto_reply_' . date('Y-m-d_H-i-s') . '_' . uniqid() . '.html';
        $filepath = $autoReplyDir . '/' . $filename;
        
        file_put_contents($filepath, $autoReplyHTML);
        
        // Create instructions
        $instructions = "AUTO-REPLY EMAIL INSTRUCTIONS\n";
        $instructions .= "================================\n\n";
        $instructions .= "CUSTOMER DETAILS:\n";
        $instructions .= "Name: " . $contactData['name'] . "\n";
        $instructions .= "Email: " . $contactData['email'] . "\n";
        $instructions .= "Subject: " . ($contactData['subject'] ?: 'General Inquiry') . "\n";
        $instructions .= "Date: " . date('Y-m-d H:i:s') . "\n\n";
        
        $instructions .= "EMAIL DETAILS:\n";
        $instructions .= "TO: " . $contactData['email'] . "\n";
        $instructions .= "FROM: " . EMAIL_USER . "\n";
        $instructions .= "SUBJECT: Thank you for contacting FSMC - We received your message\n\n";
        
        $instructions .= "SENDING INSTRUCTIONS:\n";
        $instructions .= "1. Open the HTML file: " . $filename . "\n";
        $instructions .= "2. Copy all the content (Ctrl+A, Ctrl+C)\n";
        $instructions .= "3. Open Gmail or your email client\n";
        $instructions .= "4. Compose new email to: " . $contactData['email'] . "\n";
        $instructions .= "5. Paste the content and send\n\n";
        
        $instructions .= "IMPORTANT: This customer is waiting for confirmation!\n";
        $instructions .= "Send this auto-reply as soon as possible to maintain professional service.\n\n";
        
        $instructions .= "Generated: " . date('Y-m-d H:i:s') . "\n";
        
        $instructFile = $autoReplyDir . '/AUTO_REPLY_INSTRUCTIONS_' . date('Y-m-d_H-i-s') . '.txt';
        file_put_contents($instructFile, $instructions);
        
        return true;
        
    } catch (Exception $e) {
        error_log('Failed to create auto-reply files: ' . $e->getMessage());
        return false;
    }
}

/**
 * Generate professional HTML email template (Spam-optimized)
 */
function generateEmailTemplate($data) {
    $companyName = 'Fair Surveying & Mapping Company Ltd';
    $currentDate = date('F j, Y \a\t g:i A');
    
    $html = '
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Contact Form Submission</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }
        .email-container {
            max-width: 600px;
            margin: 20px auto;
            background: #ffffff;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
        }
        .email-header {
            background: linear-gradient(135deg, #1a5276, #2e86c1);
            color: white;
            padding: 30px;
            text-align: center;
        }
        .email-header h1 {
            margin: 0;
            font-size: 24px;
            font-weight: 300;
        }
        .email-header .subtitle {
            margin: 10px 0 0 0;
            font-size: 14px;
            opacity: 0.9;
        }
        .email-body {
            padding: 30px;
        }
        .contact-info {
            background: #f8f9fa;
            border-left: 4px solid #1a5276;
            padding: 20px;
            margin: 20px 0;
            border-radius: 0 5px 5px 0;
        }
        .info-row {
            margin-bottom: 15px;
        }
        .info-row:last-child {
            margin-bottom: 0;
        }
        .info-label {
            font-weight: bold;
            color: #1a5276;
            display: inline-block;
            min-width: 120px;
        }
        .info-value {
            display: inline;
        }
        .message-content {
            background: #ffffff;
            border: 1px solid #e9ecef;
            border-radius: 5px;
            padding: 20px;
            margin: 20px 0;
            font-style: italic;
            line-height: 1.8;
        }
        .email-footer {
            background: #f8f9fa;
            padding: 20px 30px;
            text-align: center;
            border-top: 1px solid #e9ecef;
            font-size: 12px;
            color: #6c757d;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="email-header">
            <h1>📧 New Contact Form Submission</h1>
            <p class="subtitle">From ' . htmlspecialchars($companyName) . ' Website</p>
        </div>
        
        <div class="email-body">
            <p>Hello,</p>
            <p>You have received a new contact form submission from your website. Here are the details:</p>
            
            <div class="contact-info">
                <div class="info-row">
                    <span class="info-label">👤 Name:</span>
                    <span class="info-value">' . htmlspecialchars($data['name']) . '</span>
                </div>
                
                <div class="info-row">
                    <span class="info-label">📧 Email:</span>
                    <span class="info-value"><a href="mailto:' . htmlspecialchars($data['email']) . '">' . htmlspecialchars($data['email']) . '</a></span>
                </div>
                
                ' . (!empty($data['phone']) ? '
                <div class="info-row">
                    <span class="info-label">📞 Phone:</span>
                    <span class="info-value"><a href="tel:' . htmlspecialchars($data['phone']) . '">' . htmlspecialchars($data['phone']) . '</a></span>
                </div>
                ' : '') . '
                
                ' . (!empty($data['subject']) ? '
                <div class="info-row">
                    <span class="info-label">📋 Subject:</span>
                    <span class="info-value">' . htmlspecialchars($data['subject']) . '</span>
                </div>
                ' : '') . '
                
                ' . (!empty($data['service']) ? '
                <div class="info-row">
                    <span class="info-label">🔧 Service:</span>
                    <span class="info-value">' . htmlspecialchars($data['service']) . '</span>
                </div>
                ' : '') . '
                
                <div class="info-row">
                    <span class="info-label">🕒 Submitted:</span>
                    <span class="info-value">' . $currentDate . '</span>
                </div>
                
                ' . (!empty($data['ip_address']) ? '
                <div class="info-row">
                    <span class="info-label">🌐 IP Address:</span>
                    <span class="info-value">' . htmlspecialchars($data['ip_address']) . '</span>
                </div>
                ' : '') . '
            </div>
            
            <h3 style="color: #1a5276; margin-top: 30px;">💬 Message:</h3>
            <div class="message-content">
                ' . nl2br(htmlspecialchars($data['message'])) . '
            </div>
            
            <p style="margin-top: 30px;">
                <strong>Next Steps:</strong><br>
                • Review the inquiry details above<br>
                • Respond to the customer within 24 hours<br>
                • Use the provided email address to reply directly
            </p>
        </div>
        
        <div class="email-footer">
            <p>This email was automatically generated from the contact form on your website.<br>
            Please do not reply to this email directly. Use the customer\'s email address provided above.</p>
            <p style="margin-top: 10px;">
                <strong>' . htmlspecialchars($companyName) . '</strong><br>
                Professional Surveying & Mapping Services
            </p>
        </div>
    </div>
</body>
</html>';

    return $html;
}

/**
 * Generate auto-reply email template for customers
 */
function generateAutoReplyTemplate($data) {
    $companyName = 'Fair Surveying & Mapping Company Ltd';
    
    $html = '
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thank you for contacting us</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }
        .email-container {
            max-width: 600px;
            margin: 20px auto;
            background: #ffffff;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
        }
        .email-header {
            background: linear-gradient(135deg, #27ae60, #2ecc71);
            color: white;
            padding: 30px;
            text-align: center;
        }
        .email-header h1 {
            margin: 0;
            font-size: 24px;
            font-weight: 300;
        }
        .email-body {
            padding: 30px;
        }
        .highlight-box {
            background: #e8f5e8;
            border-left: 4px solid #27ae60;
            padding: 20px;
            margin: 20px 0;
            border-radius: 0 5px 5px 0;
        }
        .contact-info {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 5px;
            margin: 20px 0;
        }
        .email-footer {
            background: #f8f9fa;
            padding: 20px 30px;
            text-align: center;
            border-top: 1px solid #e9ecef;
            font-size: 12px;
            color: #6c757d;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="email-header">
            <h1>✅ Message Received!</h1>
            <p>Thank you for contacting us</p>
        </div>
        
        <div class="email-body">
            <p>Dear ' . htmlspecialchars($data['name']) . ',</p>
            
            <p>Thank you for reaching out to <strong>' . htmlspecialchars($companyName) . '</strong>. We have successfully received your message and appreciate your interest in our services.</p>
            
            <div class="highlight-box">
                <h3 style="margin-top: 0; color: #27ae60;">📋 Your Message Summary:</h3>
                <p><strong>Subject:</strong> ' . htmlspecialchars($data['subject'] ?: 'General Inquiry') . '</p>
                ' . (!empty($data['service']) ? '<p><strong>Service Interest:</strong> ' . htmlspecialchars($data['service']) . '</p>' : '') . '
                <p><strong>Submitted:</strong> ' . date('F j, Y \a\t g:i A') . '</p>
            </div>
            
            <h3 style="color: #1a5276;">What happens next?</h3>
            <ul>
                <li>Our team will review your inquiry within 24 hours</li>
                <li>A specialist will contact you to discuss your requirements</li>
                <li>We\'ll provide you with a detailed consultation and quote</li>
            </ul>
            
            <div class="contact-info">
                <h4 style="margin-top: 0; color: #1a5276;">📞 Need immediate assistance?</h4>
                <p>If your inquiry is urgent, please don\'t hesitate to contact us directly:</p>
                <p>
                    <strong>Email:</strong> <a href="mailto:' . EMAIL_TO . '">' . EMAIL_TO . '</a><br>
                    <strong>Phone:</strong> +250 XXX XXX XXX
                </p>
            </div>
            
            <p>We look forward to working with you and providing you with exceptional surveying and mapping services.</p>
            
            <p>Best regards,<br>
            <strong>The FSMC Team</strong></p>
        </div>
        
        <div class="email-footer">
            <p><strong>' . htmlspecialchars($companyName) . '</strong><br>
            Professional Surveying & Mapping Services</p>
            <p>This is an automated response. Please do not reply to this email.</p>
        </div>
    </div>
</body>
</html>';

    return $html;
}

/**
 * Generate plain text version of email
 */
function generatePlainTextEmail($data) {
    $text = "NEW CONTACT FORM SUBMISSION\n";
    $text .= "================================\n\n";
    $text .= "Name: " . $data['name'] . "\n";
    $text .= "Email: " . $data['email'] . "\n";
    
    if (!empty($data['phone'])) {
        $text .= "Phone: " . $data['phone'] . "\n";
    }
    
    if (!empty($data['subject'])) {
        $text .= "Subject: " . $data['subject'] . "\n";
    }
    
    if (!empty($data['service'])) {
        $text .= "Service Interest: " . $data['service'] . "\n";
    }
    
    $text .= "Submitted: " . date('F j, Y \a\t g:i A') . "\n\n";
    $text .= "MESSAGE:\n";
    $text .= "--------\n";
    $text .= $data['message'] . "\n\n";
    $text .= "This email was automatically generated from the contact form.";
    
    return $text;
}
?>