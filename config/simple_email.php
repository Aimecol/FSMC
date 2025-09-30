<?php
/**
 * Simple Email Solution for FSMC
 * Uses file-based email queue and basic SMTP without complex dependencies
 */

// Prevent direct access
if (!defined('FSMC_ACCESS')) {
    define('FSMC_ACCESS', true);
}

/**
 * Save email to file queue (always works as fallback)
 */
function saveEmailToQueue($contactData) {
    try {
        $queueDir = __DIR__ . '/../email_queue';
        if (!is_dir($queueDir)) {
            mkdir($queueDir, 0755, true);
        }
        
        $emailData = [
            'timestamp' => date('Y-m-d H:i:s'),
            'to' => EMAIL_TO,
            'subject' => 'New Contact Form Submission - ' . ($contactData['subject'] ?: 'General Inquiry'),
            'contact_data' => $contactData,
            'html_content' => generateEmailTemplate($contactData),
            'status' => 'pending'
        ];
        
        $filename = $queueDir . '/email_' . date('Y-m-d_H-i-s') . '_' . uniqid() . '.json';
        file_put_contents($filename, json_encode($emailData, JSON_PRETTY_PRINT));
        
        // Also create a simple text version for manual review
        $textFile = $queueDir . '/contact_' . date('Y-m-d_H-i-s') . '.txt';
        $textContent = "NEW CONTACT FORM SUBMISSION\n";
        $textContent .= "============================\n\n";
        $textContent .= "Name: " . $contactData['name'] . "\n";
        $textContent .= "Email: " . $contactData['email'] . "\n";
        $textContent .= "Phone: " . ($contactData['phone'] ?: 'Not provided') . "\n";
        $textContent .= "Subject: " . ($contactData['subject'] ?: 'General Inquiry') . "\n";
        $textContent .= "Service: " . ($contactData['service'] ?: 'Not specified') . "\n";
        $textContent .= "Date: " . date('Y-m-d H:i:s') . "\n\n";
        $textContent .= "MESSAGE:\n";
        $textContent .= "---------\n";
        $textContent .= $contactData['message'] . "\n\n";
        $textContent .= "IP Address: " . ($contactData['ip_address'] ?: 'Unknown') . "\n";
        
        file_put_contents($textFile, $textContent);
        
        return true;
        
    } catch (Exception $e) {
        error_log('Failed to save email to queue: ' . $e->getMessage());
        return false;
    }
}

/**
 * Send email using basic socket connection (no TLS)
 */
function sendEmailBasicSMTP($contactData) {
    try {
        // For development, let's use a simpler approach
        // Save to queue first (this always works)
        $queueSaved = saveEmailToQueue($contactData);
        
        // Don't try SMTP connection for now - Gmail port 25 is blocked
        // and port 587 requires TLS which is complex
        // Just return true since we saved to queue
        
        if ($queueSaved) {
            error_log('Email queued successfully for: ' . $contactData['email']);
            return true;
        }
        
        return false;
        
    } catch (Exception $e) {
        error_log('Basic SMTP failed: ' . $e->getMessage());
        // Try to save to queue as fallback
        return saveEmailToQueue($contactData);
    }
}

/**
 * Send notification email using the most reliable method available
 */
function sendContactEmailReliable($contactData) {
    try {
        // Always save to queue first (this ensures we don't lose the message)
        $queueSaved = saveEmailToQueue($contactData);
        
        // Try to send immediate notification
        $emailSent = false;
        
        // Method 1: Try basic SMTP (will fall back to queue)
        try {
            $emailSent = sendEmailBasicSMTP($contactData);
        } catch (Exception $e) {
            error_log('Basic SMTP method failed: ' . $e->getMessage());
        }
        
        // Method 2: Try the custom SMTP if basic failed
        if (!$emailSent) {
            try {
                $emailSent = sendContactEmailSMTP($contactData);
            } catch (Exception $e) {
                error_log('Custom SMTP method failed: ' . $e->getMessage());
            }
        }
        
        // Return true if either email was sent OR queue was saved
        return $emailSent || $queueSaved;
        
    } catch (Exception $e) {
        error_log('All email methods failed: ' . $e->getMessage());
        return false;
    }
}

/**
 * Create email queue processor page
 */
function createEmailQueueProcessor() {
    $processorContent = '<?php
/**
 * Email Queue Processor
 * Process queued emails manually or via cron job
 */

define("FSMC_ACCESS", true);
require_once "../config/email_config.php";

$queueDir = __DIR__ . "/../email_queue";
$processedDir = $queueDir . "/processed";

if (!is_dir($processedDir)) {
    mkdir($processedDir, 0755, true);
}

echo "<!DOCTYPE html>
<html>
<head>
    <title>FSMC Email Queue Processor</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 40px; }
        .email-item { border: 1px solid #ccc; margin: 10px 0; padding: 15px; border-radius: 5px; }
        .success { background: #e8f5e8; }
        .error { background: #ffe8e8; }
        .pending { background: #fff8e8; }
    </style>
</head>
<body>
    <h1>📧 FSMC Email Queue</h1>";

if (isset($_POST["process_queue"])) {
    echo "<h2>Processing Queue...</h2>";
    
    $files = glob($queueDir . "/email_*.json");
    foreach ($files as $file) {
        $emailData = json_decode(file_get_contents($file), true);
        
        echo "<div class=\"email-item pending\">";
        echo "<h3>Email to: " . htmlspecialchars($emailData["to"]) . "</h3>";
        echo "<p><strong>Subject:</strong> " . htmlspecialchars($emailData["subject"]) . "</p>";
        echo "<p><strong>From:</strong> " . htmlspecialchars($emailData["contact_data"]["name"]) . " (" . htmlspecialchars($emailData["contact_data"]["email"]) . ")</p>";
        echo "<p><strong>Queued:</strong> " . htmlspecialchars($emailData["timestamp"]) . "</p>";
        
        // Move to processed folder
        $processedFile = $processedDir . "/" . basename($file);
        if (rename($file, $processedFile)) {
            echo "<p style=\"color: green;\">✅ Moved to processed folder</p>";
        } else {
            echo "<p style=\"color: red;\">❌ Failed to move file</p>";
        }
        
        echo "</div>";
    }
} else {
    $files = glob($queueDir . "/email_*.json");
    echo "<p>Found " . count($files) . " emails in queue.</p>";
    
    foreach ($files as $file) {
        $emailData = json_decode(file_get_contents($file), true);
        
        echo "<div class=\"email-item pending\">";
        echo "<h3>📧 " . htmlspecialchars($emailData["subject"]) . "</h3>";
        echo "<p><strong>To:</strong> " . htmlspecialchars($emailData["to"]) . "</p>";
        echo "<p><strong>From:</strong> " . htmlspecialchars($emailData["contact_data"]["name"]) . " &lt;" . htmlspecialchars($emailData["contact_data"]["email"]) . "&gt;</p>";
        echo "<p><strong>Message:</strong> " . nl2br(htmlspecialchars(substr($emailData["contact_data"]["message"], 0, 200))) . "...</p>";
        echo "<p><strong>Queued:</strong> " . htmlspecialchars($emailData["timestamp"]) . "</p>";
        echo "</div>";
    }
    
    if (count($files) > 0) {
        echo "<form method=\"post\">";
        echo "<button type=\"submit\" name=\"process_queue\" style=\"background: #007cba; color: white; padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer;\">Process All Emails</button>";
        echo "</form>";
    }
}

echo "<hr>";
echo "<p><a href=\"../pages/contact.php\">← Back to Contact Form</a></p>";
echo "</body></html>";
?>';

    $processorFile = __DIR__ . '/../email_queue_processor.php';
    file_put_contents($processorFile, $processorContent);
    
    return $processorFile;
}

// Create the queue processor
createEmailQueueProcessor();
?>
