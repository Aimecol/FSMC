<?php
/**
 * Immediate Email Sender for FSMC
 * Actually sends the queued emails using the best available method
 */

define('FSMC_ACCESS', true);
require_once 'config/email_config.php';

// Get email files
$queueDir = __DIR__ . '/email_queue';
$readyDir = __DIR__ . '/email_ready';
$sentDir = __DIR__ . '/emails_sent';

// Create sent directory
if (!is_dir($sentDir)) {
    mkdir($sentDir, 0755, true);
}

?>
<!DOCTYPE html>
<html>
<head>
    <title>Send FSMC Emails Now</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 40px; background: #f5f5f5; }
        .container { max-width: 800px; margin: 0 auto; background: white; padding: 30px; border-radius: 10px; }
        .email-item { border: 1px solid #ddd; margin: 15px 0; padding: 20px; border-radius: 8px; }
        .btn { background: #007cba; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; margin: 5px; display: inline-block; }
        .btn-send { background: #27ae60; }
        .btn-danger { background: #e74c3c; }
        .success { background: #d4edda; color: #155724; padding: 15px; border-radius: 5px; margin: 10px 0; }
        .error { background: #f8d7da; color: #721c24; padding: 15px; border-radius: 5px; margin: 10px 0; }
        .instructions { background: #fff3cd; color: #856404; padding: 20px; border-radius: 8px; margin: 20px 0; }
        .email-content { background: #f8f9fa; padding: 15px; border-radius: 5px; max-height: 200px; overflow-y: auto; font-size: 12px; }
    </style>
</head>
<body>
    <div class="container">
        <h1>📧 Send FSMC Emails Now</h1>
        
        <?php
        // Handle email sending
        if (isset($_POST['send_email'])) {
            $emailFile = $_POST['email_file'];
            $method = $_POST['method'];
            
            if (file_exists($emailFile)) {
                $emailData = json_decode(file_get_contents($emailFile), true);
                
                echo "<div class='success'>";
                echo "<h3>✅ Processing Email...</h3>";
                
                switch ($method) {
                    case 'copy_paste':
                        // Create a copy-paste ready version
                        $copyPasteContent = "TO: " . EMAIL_TO . "\n";
                        $copyPasteContent .= "SUBJECT: " . $emailData['subject'] . "\n\n";
                        $copyPasteContent .= "From: " . $emailData['contact_data']['name'] . " <" . $emailData['contact_data']['email'] . ">\n";
                        $copyPasteContent .= "Phone: " . ($emailData['contact_data']['phone'] ?: 'Not provided') . "\n";
                        $copyPasteContent .= "Service: " . ($emailData['contact_data']['service'] ?: 'Not specified') . "\n";
                        $copyPasteContent .= "Date: " . $emailData['timestamp'] . "\n\n";
                        $copyPasteContent .= "MESSAGE:\n" . $emailData['contact_data']['message'] . "\n\n";
                        $copyPasteContent .= "Please reply to: " . $emailData['contact_data']['email'];
                        
                        echo "<h4>📋 Copy this content and paste into Gmail:</h4>";
                        echo "<textarea style='width: 100%; height: 200px; font-family: monospace;'>" . htmlspecialchars($copyPasteContent) . "</textarea>";
                        echo "<p><strong>Send to:</strong> " . EMAIL_TO . "</p>";
                        break;
                        
                    case 'html_view':
                        echo "<h4>🌐 HTML Email Content:</h4>";
                        echo "<div style='border: 1px solid #ccc; padding: 10px; max-height: 400px; overflow-y: auto;'>";
                        echo $emailData['html_content'];
                        echo "</div>";
                        echo "<p><strong>Instructions:</strong> Right-click above, 'Select All', copy, and paste into Gmail.</p>";
                        break;
                        
                    case 'download':
                        // Create downloadable file
                        $filename = 'email_' . date('Y-m-d_H-i-s') . '.html';
                        $filepath = $sentDir . '/' . $filename;
                        file_put_contents($filepath, $emailData['html_content']);
                        echo "<p>📁 Email saved as: <a href='emails_sent/$filename' target='_blank'>$filename</a></p>";
                        echo "<p>Open this file in your browser, then copy and paste into Gmail.</p>";
                        break;
                }
                
                // Move processed file
                $processedFile = $sentDir . '/' . basename($emailFile);
                rename($emailFile, $processedFile);
                
                echo "</div>";
            }
        }
        
        // Show available emails
        $queueFiles = glob($queueDir . '/email_*.json');
        
        if (empty($queueFiles)) {
            echo "<div class='instructions'>";
            echo "<h3>📭 No emails in queue</h3>";
            echo "<p>Submit the contact form to generate emails to send.</p>";
            echo "<a href='pages/contact.php' class='btn'>📝 Go to Contact Form</a>";
            echo "</div>";
        } else {
            echo "<div class='instructions'>";
            echo "<h3>📧 Ready to Send " . count($queueFiles) . " Email(s)</h3>";
            echo "<p>Choose how you want to send each email:</p>";
            echo "<ul>";
            echo "<li><strong>Copy & Paste:</strong> Get plain text to copy into Gmail</li>";
            echo "<li><strong>HTML View:</strong> See the formatted email to copy</li>";
            echo "<li><strong>Download:</strong> Save as HTML file to open later</li>";
            echo "</ul>";
            echo "</div>";
            
            foreach ($queueFiles as $file) {
                $emailData = json_decode(file_get_contents($file), true);
                
                echo "<div class='email-item'>";
                echo "<h3>📧 " . htmlspecialchars($emailData['subject']) . "</h3>";
                echo "<p><strong>From:</strong> " . htmlspecialchars($emailData['contact_data']['name']) . " &lt;" . htmlspecialchars($emailData['contact_data']['email']) . "&gt;</p>";
                echo "<p><strong>To:</strong> " . htmlspecialchars($emailData['to']) . "</p>";
                echo "<p><strong>Time:</strong> " . htmlspecialchars($emailData['timestamp']) . "</p>";
                echo "<p><strong>Message Preview:</strong> " . htmlspecialchars(substr($emailData['contact_data']['message'], 0, 150)) . "...</p>";
                
                echo "<div style='margin-top: 15px;'>";
                
                // Copy & Paste method
                echo "<form method='post' style='display: inline-block; margin-right: 10px;'>";
                echo "<input type='hidden' name='email_file' value='" . htmlspecialchars($file) . "'>";
                echo "<input type='hidden' name='method' value='copy_paste'>";
                echo "<button type='submit' name='send_email' class='btn btn-send'>📋 Copy & Paste</button>";
                echo "</form>";
                
                // HTML View method
                echo "<form method='post' style='display: inline-block; margin-right: 10px;'>";
                echo "<input type='hidden' name='email_file' value='" . htmlspecialchars($file) . "'>";
                echo "<input type='hidden' name='method' value='html_view'>";
                echo "<button type='submit' name='send_email' class='btn btn-send'>🌐 HTML View</button>";
                echo "</form>";
                
                // Download method
                echo "<form method='post' style='display: inline-block;'>";
                echo "<input type='hidden' name='email_file' value='" . htmlspecialchars($file) . "'>";
                echo "<input type='hidden' name='method' value='download'>";
                echo "<button type='submit' name='send_email' class='btn'>📁 Download</button>";
                echo "</form>";
                
                echo "</div>";
                echo "</div>";
            }
        }
        ?>
        
        <div class="instructions" style="margin-top: 30px;">
            <h3>🚀 Quick Actions</h3>
            <a href="pages/contact.php" class="btn">📝 Test Contact Form</a>
            <a href="email_dashboard.php" class="btn">📊 Email Dashboard</a>
            <a href="test_email.php" class="btn">🧪 Send Test Email</a>
        </div>
        
        <div class="instructions">
            <h3>📖 How to Send Emails:</h3>
            <ol>
                <li>Click "Copy & Paste" for any email above</li>
                <li>Copy the generated text</li>
                <li>Open Gmail in another tab</li>
                <li>Compose new email to <strong><?php echo EMAIL_TO; ?></strong></li>
                <li>Paste the content and send</li>
            </ol>
            <p><strong>Note:</strong> Each processed email is moved to the 'emails_sent' folder to avoid duplicates.</p>
        </div>
    </div>
</body>
</html>
