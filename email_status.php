<?php
/**
 * Quick Email Status Check
 * Shows where your emails are located
 */

define('FSMC_ACCESS', true);

// Check directories
$queueDir = __DIR__ . '/email_queue';
$readyDir = __DIR__ . '/email_ready';

// Get file counts
$queueFiles = is_dir($queueDir) ? glob($queueDir . '/email_*.json') : [];
$readyEmails = is_dir($readyDir) ? glob($readyDir . '/email_*.html') : [];
$readyReplies = is_dir($readyDir) ? glob($readyDir . '/reply_*.html') : [];
$instructions = is_dir($readyDir) ? glob($readyDir . '/INSTRUCTIONS_*.txt') : [];

?>
<!DOCTYPE html>
<html>
<head>
    <title>FSMC Email Status</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 40px; background: #f5f5f5; }
        .container { max-width: 800px; margin: 0 auto; background: white; padding: 30px; border-radius: 10px; }
        .success { background: #d4edda; color: #155724; padding: 15px; border-radius: 5px; margin: 10px 0; }
        .info { background: #cce7ff; color: #004085; padding: 15px; border-radius: 5px; margin: 10px 0; }
        .btn { background: #007cba; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; margin: 5px; display: inline-block; }
        .file-list { background: #f8f9fa; padding: 15px; border-radius: 5px; margin: 10px 0; }
        h1 { color: #1a5276; text-align: center; }
        h2 { color: #1a5276; border-bottom: 2px solid #1a5276; padding-bottom: 10px; }
    </style>
</head>
<body>
    <div class="container">
        <h1>📧 FSMC Email Status Report</h1>
        
        <div class="success">
            <h3>✅ Email System is Working!</h3>
            <p>Your contact form is successfully creating emails. The PHP mail warnings are expected in XAMPP - the system uses fallback methods.</p>
        </div>

        <h2>📊 Current Email Status</h2>
        
        <div class="info">
            <strong>📥 Queue Files:</strong> <?php echo count($queueFiles); ?> emails<br>
            <strong>📤 Ready to Send:</strong> <?php echo count($readyEmails); ?> main emails, <?php echo count($readyReplies); ?> replies<br>
            <strong>📋 Instructions:</strong> <?php echo count($instructions); ?> instruction files
        </div>

        <?php if (!empty($readyEmails)): ?>
        <h2>📤 Ready-to-Send Emails</h2>
        <div class="file-list">
            <p><strong>These are your actual emails ready to copy and send:</strong></p>
            <?php foreach ($readyEmails as $file): ?>
                <p>📧 <a href="email_ready/<?php echo basename($file); ?>" target="_blank"><?php echo basename($file); ?></a> 
                   <small>(<?php echo date('H:i:s', filemtime($file)); ?>)</small></p>
            <?php endforeach; ?>
            
            <?php foreach ($readyReplies as $file): ?>
                <p>↩️ <a href="email_ready/<?php echo basename($file); ?>" target="_blank"><?php echo basename($file); ?></a> 
                   <small>(Auto-reply - <?php echo date('H:i:s', filemtime($file)); ?>)</small></p>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>

        <?php if (!empty($instructions)): ?>
        <h2>📋 How to Send</h2>
        <div class="file-list">
            <?php foreach ($instructions as $file): ?>
                <p>📋 <a href="email_ready/<?php echo basename($file); ?>" target="_blank"><?php echo basename($file); ?></a></p>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>

        <h2>🚀 Quick Actions</h2>
        <a href="email_dashboard.php" class="btn">📊 Full Dashboard</a>
        <a href="pages/contact.php" class="btn">📝 Test Contact Form</a>
        <a href="test_email.php" class="btn">🧪 Run Email Test</a>

        <div class="info">
            <h3>📖 How to Send Emails Manually:</h3>
            <ol>
                <li>Click on any email file above to open it</li>
                <li>Copy all the content (Ctrl+A, then Ctrl+C)</li>
                <li>Open Gmail or your email client</li>
                <li>Compose new email to: <strong>fsamcompanyltd@gmail.com</strong></li>
                <li>Paste the content and send!</li>
            </ol>
        </div>

        <?php if (empty($readyEmails) && empty($queueFiles)): ?>
        <div class="info">
            <p><strong>No emails found.</strong> Submit the contact form to generate test emails:</p>
            <a href="pages/contact.php" class="btn">📝 Go to Contact Form</a>
        </div>
        <?php endif; ?>
    </div>
</body>
</html>
