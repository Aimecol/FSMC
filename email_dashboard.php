<?php
/**
 * Email Management Dashboard for FSMC
 * Shows all email methods and allows manual processing
 */

define('FSMC_ACCESS', true);
require_once 'config/email_config.php';

// Get directories
$queueDir = __DIR__ . '/email_queue';
$readyDir = __DIR__ . '/email_ready';
$processedDir = $queueDir . '/processed';

// Create directories if they don't exist
foreach ([$queueDir, $readyDir, $processedDir] as $dir) {
    if (!is_dir($dir)) {
        mkdir($dir, 0755, true);
    }
}

// Handle actions
$message = '';
if (isset($_POST['action'])) {
    switch ($_POST['action']) {
        case 'test_email':
            $testData = [
                'name' => 'Test User',
                'email' => 'test@example.com',
                'phone' => '+250 123 456 789',
                'subject' => 'Test Email from Dashboard',
                'service' => 'Testing',
                'message' => 'This is a test email sent from the email dashboard to verify the email system is working correctly.',
                'ip_address' => $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1',
                'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? 'Dashboard Test'
            ];
            
            $result = sendContactEmailUltimate($testData);
            $message = $result ? 
                '<div class="alert success">✅ Test email processed! Check all email locations below.</div>' :
                '<div class="alert error">❌ Test email failed. Check error logs.</div>';
            break;
            
        case 'clear_queue':
            $files = glob($queueDir . '/email_*.json');
            $count = 0;
            foreach ($files as $file) {
                if (unlink($file)) $count++;
            }
            $message = '<div class="alert success">✅ Cleared ' . $count . ' files from queue.</div>';
            break;
            
        case 'clear_ready':
            $files = array_merge(
                glob($readyDir . '/email_*.html'),
                glob($readyDir . '/reply_*.html'),
                glob($readyDir . '/INSTRUCTIONS_*.txt')
            );
            $count = 0;
            foreach ($files as $file) {
                if (unlink($file)) $count++;
            }
            $message = '<div class="alert success">✅ Cleared ' . $count . ' files from ready folder.</div>';
            break;
    }
}

// Get file counts
$queueFiles = glob($queueDir . '/email_*.json');
$readyEmails = glob($readyDir . '/email_*.html');
$readyReplies = glob($readyDir . '/reply_*.html');
$instructions = glob($readyDir . '/INSTRUCTIONS_*.txt');
$processedFiles = glob($processedDir . '/email_*.json');

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FSMC Email Management Dashboard</title>
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
            border-bottom: 2px solid #1a5276;
        }
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        .stat-card {
            background: linear-gradient(135deg, #1a5276, #2e86c1);
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
        .stat-label {
            font-size: 0.9rem;
            opacity: 0.9;
        }
        .section {
            margin-bottom: 30px;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 8px;
        }
        .section h3 {
            margin-top: 0;
            color: #1a5276;
            border-bottom: 1px solid #eee;
            padding-bottom: 10px;
        }
        .file-list {
            max-height: 300px;
            overflow-y: auto;
            border: 1px solid #eee;
            padding: 10px;
            border-radius: 5px;
        }
        .file-item {
            padding: 8px;
            border-bottom: 1px solid #f0f0f0;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .file-item:last-child {
            border-bottom: none;
        }
        .btn {
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
            margin: 5px;
            font-size: 14px;
        }
        .btn-primary {
            background: #1a5276;
            color: white;
        }
        .btn-success {
            background: #27ae60;
            color: white;
        }
        .btn-warning {
            background: #f39c12;
            color: white;
        }
        .btn-danger {
            background: #e74c3c;
            color: white;
        }
        .btn:hover {
            opacity: 0.8;
        }
        .alert {
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 5px;
        }
        .alert.success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        .alert.error {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        .actions {
            text-align: center;
            margin: 20px 0;
        }
        .instructions {
            background: #e8f4fd;
            padding: 20px;
            border-radius: 8px;
            border-left: 4px solid #1a5276;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>📧 FSMC Email Management Dashboard</h1>
            <p>Manage and monitor all email communications</p>
        </div>

        <?php echo $message; ?>

        <!-- Statistics -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-number"><?php echo count($queueFiles); ?></div>
                <div class="stat-label">Queued Emails</div>
            </div>
            <div class="stat-card">
                <div class="stat-number"><?php echo count($readyEmails); ?></div>
                <div class="stat-label">Ready to Send</div>
            </div>
            <div class="stat-card">
                <div class="stat-number"><?php echo count($processedFiles); ?></div>
                <div class="stat-label">Processed</div>
            </div>
            <div class="stat-card">
                <div class="stat-number"><?php echo count($instructions); ?></div>
                <div class="stat-label">Instructions</div>
            </div>
        </div>

        <!-- Actions -->
        <div class="actions">
            <form method="post" style="display: inline;">
                <input type="hidden" name="action" value="test_email">
                <button type="submit" class="btn btn-primary">🧪 Send Test Email</button>
            </form>
            <a href="pages/contact.php" class="btn btn-success">📝 Contact Form</a>
            <a href="email_queue_processor.php" class="btn btn-warning">⚙️ Queue Processor</a>
        </div>

        <!-- Email Queue -->
        <div class="section">
            <h3>📥 Email Queue (JSON Format)</h3>
            <?php if (empty($queueFiles)): ?>
                <p>No emails in queue.</p>
            <?php else: ?>
                <div class="file-list">
                    <?php foreach ($queueFiles as $file): ?>
                        <?php $data = json_decode(file_get_contents($file), true); ?>
                        <div class="file-item">
                            <div>
                                <strong><?php echo htmlspecialchars($data['subject'] ?? 'No Subject'); ?></strong><br>
                                <small>From: <?php echo htmlspecialchars($data['contact_data']['name'] ?? 'Unknown'); ?> | 
                                <?php echo htmlspecialchars($data['timestamp'] ?? 'Unknown time'); ?></small>
                            </div>
                            <div>
                                <a href="<?php echo basename($file); ?>" class="btn btn-primary" style="padding: 5px 10px; font-size: 12px;">View</a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
                <form method="post" style="margin-top: 10px;">
                    <input type="hidden" name="action" value="clear_queue">
                    <button type="submit" class="btn btn-danger" onclick="return confirm('Clear all queue files?')">🗑️ Clear Queue</button>
                </form>
            <?php endif; ?>
        </div>

        <!-- Ready Emails -->
        <div class="section">
            <h3>📤 Ready to Send (HTML Format)</h3>
            <?php if (empty($readyEmails) && empty($readyReplies)): ?>
                <p>No emails ready to send.</p>
            <?php else: ?>
                <div class="file-list">
                    <?php foreach ($readyEmails as $file): ?>
                        <div class="file-item">
                            <div>
                                <strong>📧 Main Email</strong><br>
                                <small><?php echo basename($file); ?> | <?php echo date('Y-m-d H:i:s', filemtime($file)); ?></small>
                            </div>
                            <div>
                                <a href="email_ready/<?php echo basename($file); ?>" target="_blank" class="btn btn-primary" style="padding: 5px 10px; font-size: 12px;">Open</a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                    <?php foreach ($readyReplies as $file): ?>
                        <div class="file-item">
                            <div>
                                <strong>↩️ Auto-Reply</strong><br>
                                <small><?php echo basename($file); ?> | <?php echo date('Y-m-d H:i:s', filemtime($file)); ?></small>
                            </div>
                            <div>
                                <a href="email_ready/<?php echo basename($file); ?>" target="_blank" class="btn btn-success" style="padding: 5px 10px; font-size: 12px;">Open</a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
                <form method="post" style="margin-top: 10px;">
                    <input type="hidden" name="action" value="clear_ready">
                    <button type="submit" class="btn btn-danger" onclick="return confirm('Clear all ready files?')">🗑️ Clear Ready Files</button>
                </form>
            <?php endif; ?>
        </div>

        <!-- Instructions -->
        <?php if (!empty($instructions)): ?>
        <div class="section">
            <h3>📋 Email Instructions</h3>
            <div class="file-list">
                <?php foreach ($instructions as $file): ?>
                    <div class="file-item">
                        <div>
                            <strong>📋 Instructions</strong><br>
                            <small><?php echo basename($file); ?> | <?php echo date('Y-m-d H:i:s', filemtime($file)); ?></small>
                        </div>
                        <div>
                            <a href="email_ready/<?php echo basename($file); ?>" target="_blank" class="btn btn-warning" style="padding: 5px 10px; font-size: 12px;">Read</a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endif; ?>

        <!-- Instructions -->
        <div class="instructions">
            <h3>📖 How to Use This Dashboard</h3>
            <ol>
                <li><strong>Test Email:</strong> Click "Send Test Email" to generate test emails in all formats</li>
                <li><strong>Queue Files:</strong> JSON files with all email data (for developers)</li>
                <li><strong>Ready Files:</strong> HTML emails ready to copy/paste into your email client</li>
                <li><strong>Instructions:</strong> Text files with step-by-step sending instructions</li>
                <li><strong>Manual Sending:</strong> Open HTML files, copy content, paste into Gmail/Outlook</li>
                <li><strong>Contact Form:</strong> Test the actual contact form to generate real emails</li>
            </ol>
            
            <h4>📧 Manual Email Process:</h4>
            <ol>
                <li>Open an HTML email file from "Ready to Send"</li>
                <li>Copy all the content (Ctrl+A, Ctrl+C)</li>
                <li>Open your email client (Gmail, Outlook, etc.)</li>
                <li>Compose new email to: <strong><?php echo EMAIL_TO; ?></strong></li>
                <li>Paste the content and send</li>
            </ol>
        </div>
    </div>
</body>
</html>
