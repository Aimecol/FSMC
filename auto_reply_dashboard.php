<?php
/**
 * Auto-Reply Management Dashboard
 * Manage customer confirmation emails
 */

define('FSMC_ACCESS', true);
require_once 'config/email_config.php';

// Get directories
$autoReplyDir = __DIR__ . '/auto_reply_ready';
$sentDir = __DIR__ . '/auto_reply_sent';

// Create directories if they don't exist
foreach ([$autoReplyDir, $sentDir] as $dir) {
    if (!is_dir($dir)) {
        mkdir($dir, 0755, true);
    }
}

// Handle actions
$message = '';
if (isset($_POST['action'])) {
    switch ($_POST['action']) {
        case 'send_auto_reply':
            $email = $_POST['customer_email'];
            $htmlFile = $_POST['html_file'];
            
            if (file_exists($htmlFile)) {
                $htmlContent = file_get_contents($htmlFile);
                
                // Move to sent folder
                $sentFile = $sentDir . '/' . basename($htmlFile);
                rename($htmlFile, $sentFile);
                
                $message = '<div class="success">✅ Auto-reply prepared for: ' . htmlspecialchars($email) . '</div>';
                $message .= '<div class="info">📧 <strong>Next:</strong> Copy the content below and send via Gmail to: <strong>' . htmlspecialchars($email) . '</strong></div>';
                $message .= '<textarea style="width: 100%; height: 200px; font-family: monospace; margin: 10px 0;">' . htmlspecialchars($htmlContent) . '</textarea>';
            }
            break;
            
        case 'mark_sent':
            $htmlFile = $_POST['html_file'];
            if (file_exists($htmlFile)) {
                $sentFile = $sentDir . '/' . basename($htmlFile);
                rename($htmlFile, $sentFile);
                $message = '<div class="success">✅ Auto-reply marked as sent and moved to sent folder.</div>';
            }
            break;
    }
}

// Get files
$autoReplyFiles = glob($autoReplyDir . '/auto_reply_*.html');
$instructionFiles = glob($autoReplyDir . '/AUTO_REPLY_INSTRUCTIONS_*.txt');
$sentFiles = glob($sentDir . '/auto_reply_*.html');

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FSMC Auto-Reply Dashboard</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background: #f5f5f5;
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 2px solid #27ae60;
        }
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        .stat-card {
            background: linear-gradient(135deg, #27ae60, #2ecc71);
            color: white;
            padding: 20px;
            border-radius: 8px;
            text-align: center;
        }
        .stat-number {
            font-size: 2rem;
            font-weight: bold;
            margin-bottom: 5px;
        }
        .section {
            margin-bottom: 30px;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 8px;
        }
        .section h3 {
            margin-top: 0;
            color: #27ae60;
            border-bottom: 1px solid #eee;
            padding-bottom: 10px;
        }
        .auto-reply-item {
            border: 1px solid #ddd;
            margin: 10px 0;
            padding: 15px;
            border-radius: 6px;
            background: #f9f9f9;
        }
        .customer-info {
            background: #e8f5e8;
            padding: 10px;
            border-radius: 4px;
            margin: 10px 0;
        }
        .btn {
            padding: 8px 16px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
            margin: 3px;
            font-size: 13px;
        }
        .btn-primary { background: #007cba; color: white; }
        .btn-success { background: #27ae60; color: white; }
        .btn-warning { background: #f39c12; color: white; }
        .btn-danger { background: #e74c3c; color: white; }
        .btn:hover { opacity: 0.8; }
        .success {
            background: #d4edda;
            color: #155724;
            padding: 15px;
            border-radius: 5px;
            margin: 15px 0;
        }
        .info {
            background: #d1ecf1;
            color: #0c5460;
            padding: 15px;
            border-radius: 5px;
            margin: 15px 0;
        }
        .urgent {
            border-left: 4px solid #e74c3c;
            background: #fff5f5;
        }
        .priority-high {
            background: #fff3cd;
            border-left: 4px solid #ffc107;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>📧 FSMC Auto-Reply Dashboard</h1>
            <p>Manage customer confirmation emails</p>
        </div>

        <?php echo $message; ?>

        <!-- Statistics -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-number"><?php echo count($autoReplyFiles); ?></div>
                <div class="stat-label">Pending Auto-Replies</div>
            </div>
            <div class="stat-card">
                <div class="stat-number"><?php echo count($instructionFiles); ?></div>
                <div class="stat-label">Instruction Files</div>
            </div>
            <div class="stat-card">
                <div class="stat-number"><?php echo count($sentFiles); ?></div>
                <div class="stat-label">Sent Auto-Replies</div>
            </div>
        </div>

        <!-- Pending Auto-Replies -->
        <div class="section">
            <h3>📤 Pending Auto-Replies</h3>
            <?php if (empty($autoReplyFiles)): ?>
                <p>No pending auto-replies. All customers have been notified!</p>
                <a href="pages/contact.php" class="btn btn-primary">📝 Test Contact Form</a>
            <?php else: ?>
                <div class="info">
                    <strong>⚠️ Action Required:</strong> <?php echo count($autoReplyFiles); ?> customer(s) waiting for confirmation emails.
                </div>
                
                <?php foreach ($autoReplyFiles as $file): ?>
                    <?php
                    // Extract customer info from filename or content
                    $content = file_get_contents($file);
                    preg_match('/Dear ([^,]+),/', $content, $nameMatch);
                    $customerName = $nameMatch[1] ?? 'Unknown Customer';
                    
                    // Try to extract email from instructions file
                    $baseFilename = basename($file, '.html');
                    $timestamp = '';
                    if (preg_match('/auto_reply_(\d{4}-\d{2}-\d{2}_\d{2}-\d{2}-\d{2})/', $baseFilename, $timeMatch)) {
                        $timestamp = $timeMatch[1];
                    }
                    
                    $customerEmail = 'customer@example.com'; // Default
                    foreach ($instructionFiles as $instructFile) {
                        if (strpos($instructFile, $timestamp) !== false) {
                            $instructContent = file_get_contents($instructFile);
                            if (preg_match('/Email: ([^\n]+)/', $instructContent, $emailMatch)) {
                                $customerEmail = trim($emailMatch[1]);
                            }
                            break;
                        }
                    }
                    ?>
                    
                    <div class="auto-reply-item urgent">
                        <div class="customer-info">
                            <strong>👤 Customer:</strong> <?php echo htmlspecialchars($customerName); ?><br>
                            <strong>📧 Email:</strong> <?php echo htmlspecialchars($customerEmail); ?><br>
                            <strong>🕒 Generated:</strong> <?php echo date('Y-m-d H:i:s', filemtime($file)); ?>
                        </div>
                        
                        <div style="margin-top: 10px;">
                            <a href="<?php echo basename($file); ?>" target="_blank" class="btn btn-primary">👁️ Preview Email</a>
                            
                            <form method="post" style="display: inline;">
                                <input type="hidden" name="action" value="send_auto_reply">
                                <input type="hidden" name="customer_email" value="<?php echo htmlspecialchars($customerEmail); ?>">
                                <input type="hidden" name="html_file" value="<?php echo htmlspecialchars($file); ?>">
                                <button type="submit" class="btn btn-success">📧 Prepare to Send</button>
                            </form>
                            
                            <form method="post" style="display: inline;">
                                <input type="hidden" name="action" value="mark_sent">
                                <input type="hidden" name="html_file" value="<?php echo htmlspecialchars($file); ?>">
                                <button type="submit" class="btn btn-warning" onclick="return confirm('Mark as sent?')">✅ Mark as Sent</button>
                            </form>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

        <!-- Instructions -->
        <?php if (!empty($instructionFiles)): ?>
        <div class="section">
            <h3>📋 Sending Instructions</h3>
            <?php foreach ($instructionFiles as $file): ?>
                <div class="auto-reply-item">
                    <strong>📄 <?php echo basename($file); ?></strong><br>
                    <small>Created: <?php echo date('Y-m-d H:i:s', filemtime($file)); ?></small><br>
                    <a href="<?php echo 'auto_reply_ready/' . basename($file); ?>" target="_blank" class="btn btn-primary">📖 Read Instructions</a>
                </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>

        <!-- Quick Actions -->
        <div class="section">
            <h3>🚀 Quick Actions</h3>
            <a href="test_auto_reply.php" class="btn btn-primary">🧪 Test Auto-Reply System</a>
            <a href="pages/contact.php" class="btn btn-success">📝 Contact Form</a>
            <a href="email_dashboard.php" class="btn btn-warning">📊 Main Email Dashboard</a>
        </div>

        <!-- How to Send Auto-Replies -->
        <div class="section priority-high">
            <h3>📖 How to Send Auto-Reply Emails</h3>
            <ol>
                <li><strong>Click "Preview Email"</strong> to see the auto-reply content</li>
                <li><strong>Copy all content</strong> from the preview (Ctrl+A, Ctrl+C)</li>
                <li><strong>Open Gmail</strong> in another tab</li>
                <li><strong>Compose new email</strong> to the customer's email address</li>
                <li><strong>Paste content</strong> and send immediately</li>
                <li><strong>Mark as sent</strong> to move to sent folder</li>
            </ol>
            
            <div class="info">
                <strong>💡 Pro Tip:</strong> Send auto-replies within 5 minutes of receiving contact forms to maintain excellent customer service!
            </div>
        </div>
    </div>
</body>
</html>
