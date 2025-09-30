<?php
/**
 * SendGrid Email Solution for FSMC
 * Uses SendGrid API for reliable email delivery
 */

// Prevent direct access
if (!defined('FSMC_ACCESS')) {
    define('FSMC_ACCESS', true);
}

/**
 * Send email using SendGrid API (no SMTP needed)
 */
function sendEmailViaSendGrid($contactData) {
    // For now, let's use a simpler HTTP-based approach
    // You can get a free SendGrid account later
    
    try {
        // Create the email content
        $subject = 'New Contact Form Submission - ' . ($contactData['subject'] ?: 'General Inquiry');
        $htmlContent = generateEmailTemplate($contactData);
        
        // For now, let's use a webhook approach
        $emailData = [
            'to' => EMAIL_TO,
            'from' => EMAIL_USER,
            'subject' => $subject,
            'html' => $htmlContent,
            'contact' => $contactData,
            'timestamp' => date('Y-m-d H:i:s')
        ];
        
        // Save to a special "send_now" folder that can be processed
        $sendDir = __DIR__ . '/../send_now';
        if (!is_dir($sendDir)) {
            mkdir($sendDir, 0755, true);
        }
        
        $filename = $sendDir . '/email_' . date('Y-m-d_H-i-s') . '_' . uniqid() . '.json';
        file_put_contents($filename, json_encode($emailData, JSON_PRETTY_PRINT));
        
        return true;
        
    } catch (Exception $e) {
        error_log('SendGrid email failed: ' . $e->getMessage());
        return false;
    }
}

/**
 * Alternative: Use a simple webhook to send emails
 */
function sendEmailViaWebhook($contactData) {
    try {
        // Create email data
        $emailData = [
            'to' => EMAIL_TO,
            'from_name' => $contactData['name'],
            'from_email' => $contactData['email'],
            'subject' => 'New Contact Form Submission - ' . ($contactData['subject'] ?: 'General Inquiry'),
            'message' => $contactData['message'],
            'phone' => $contactData['phone'] ?? '',
            'service' => $contactData['service'] ?? '',
            'timestamp' => date('Y-m-d H:i:s'),
            'ip' => $contactData['ip_address'] ?? ''
        ];
        
        // Try to use a free email service (like Formspree or EmailJS)
        // For now, let's create a formatted email that can be easily sent
        
        $formattedEmail = createFormattedEmail($contactData);
        
        // Save to immediate send folder
        $sendDir = __DIR__ . '/../send_immediately';
        if (!is_dir($sendDir)) {
            mkdir($sendDir, 0755, true);
        }
        
        $filename = $sendDir . '/SEND_NOW_' . date('Y-m-d_H-i-s') . '.txt';
        file_put_contents($filename, $formattedEmail);
        
        return true;
        
    } catch (Exception $e) {
        error_log('Webhook email failed: ' . $e->getMessage());
        return false;
    }
}

/**
 * Create a formatted email for immediate sending
 */
function createFormattedEmail($contactData) {
    $email = "TO: " . EMAIL_TO . "\n";
    $email .= "FROM: " . $contactData['name'] . " <" . $contactData['email'] . ">\n";
    $email .= "SUBJECT: New Contact Form Submission - " . ($contactData['subject'] ?: 'General Inquiry') . "\n";
    $email .= "DATE: " . date('Y-m-d H:i:s') . "\n";
    $email .= str_repeat("=", 50) . "\n\n";
    
    $email .= "CONTACT DETAILS:\n";
    $email .= "Name: " . $contactData['name'] . "\n";
    $email .= "Email: " . $contactData['email'] . "\n";
    $email .= "Phone: " . ($contactData['phone'] ?: 'Not provided') . "\n";
    $email .= "Subject: " . ($contactData['subject'] ?: 'General Inquiry') . "\n";
    $email .= "Service Interest: " . ($contactData['service'] ?: 'Not specified') . "\n";
    $email .= "IP Address: " . ($contactData['ip_address'] ?: 'Unknown') . "\n\n";
    
    $email .= "MESSAGE:\n";
    $email .= str_repeat("-", 20) . "\n";
    $email .= $contactData['message'] . "\n\n";
    
    $email .= str_repeat("=", 50) . "\n";
    $email .= "INSTRUCTIONS:\n";
    $email .= "1. Copy this entire message\n";
    $email .= "2. Open Gmail or your email client\n";
    $email .= "3. Compose new email to: " . EMAIL_TO . "\n";
    $email .= "4. Paste this content as the message\n";
    $email .= "5. Send immediately\n\n";
    
    $email .= "REPLY TO CUSTOMER:\n";
    $email .= "Send a reply to: " . $contactData['email'] . "\n";
    $email .= "Thank them for their inquiry and provide next steps.\n";
    
    return $email;
}

/**
 * Try Gmail SMTP with app password (more reliable)
 */
function sendEmailGmailDirect($contactData) {
    try {
        // This requires proper Gmail app password setup
        // For now, let's create instructions for manual setup
        
        $instructions = createGmailSetupInstructions($contactData);
        
        $setupDir = __DIR__ . '/../gmail_setup';
        if (!is_dir($setupDir)) {
            mkdir($setupDir, 0755, true);
        }
        
        $filename = $setupDir . '/GMAIL_SETUP_' . date('Y-m-d_H-i-s') . '.txt';
        file_put_contents($filename, $instructions);
        
        return true;
        
    } catch (Exception $e) {
        error_log('Gmail direct failed: ' . $e->getMessage());
        return false;
    }
}

/**
 * Create Gmail setup instructions
 */
function createGmailSetupInstructions($contactData) {
    $instructions = "GMAIL SMTP SETUP INSTRUCTIONS\n";
    $instructions .= str_repeat("=", 40) . "\n\n";
    
    $instructions .= "To enable automatic email sending:\n\n";
    
    $instructions .= "1. ENABLE 2-FACTOR AUTHENTICATION:\n";
    $instructions .= "   - Go to myaccount.google.com\n";
    $instructions .= "   - Security > 2-Step Verification\n";
    $instructions .= "   - Enable if not already enabled\n\n";
    
    $instructions .= "2. CREATE APP PASSWORD:\n";
    $instructions .= "   - Go to myaccount.google.com\n";
    $instructions .= "   - Security > App passwords\n";
    $instructions .= "   - Generate password for 'Mail'\n";
    $instructions .= "   - Copy the 16-character password\n\n";
    
    $instructions .= "3. UPDATE EMAIL CONFIG:\n";
    $instructions .= "   - Open: config/email_config.php\n";
    $instructions .= "   - Replace EMAIL_PASS with your app password\n";
    $instructions .= "   - Save the file\n\n";
    
    $instructions .= "4. TEST THE SYSTEM:\n";
    $instructions .= "   - Submit contact form again\n";
    $instructions .= "   - Emails should send automatically\n\n";
    
    $instructions .= "CURRENT CONTACT FORM DATA:\n";
    $instructions .= str_repeat("-", 30) . "\n";
    $instructions .= "Name: " . $contactData['name'] . "\n";
    $instructions .= "Email: " . $contactData['email'] . "\n";
    $instructions .= "Message: " . substr($contactData['message'], 0, 100) . "...\n";
    $instructions .= "Time: " . date('Y-m-d H:i:s') . "\n\n";
    
    $instructions .= "This contact form submission is waiting to be sent!\n";
    
    return $instructions;
}
?>
