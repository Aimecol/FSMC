<?php
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
?>