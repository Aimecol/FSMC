<?php
// Include database configuration and functions
require_once '../../../config/database.php';
require_once '../../../includes/functions.php';

// Handle AJAX requests
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    $id = intval($_POST['id'] ?? 0);
    
    switch ($action) {
        case 'delete':
            $result = deleteRecord('messages', $id);
            echo json_encode(['success' => $result]);
            exit;
            
        case 'mark_read':
            $result = updateRecord('messages', $id, ['status' => 'Read']);
            echo json_encode(['success' => $result]);
            exit;
            
        case 'mark_unread':
            $result = updateRecord('messages', $id, ['status' => 'Unread']);
            echo json_encode(['success' => $result]);
            exit;
    }
}

// Get all messages
$query = "SELECT * FROM messages ORDER BY created_at DESC";
$messages = executeCustomQuery($query);

// Calculate statistics
$totalMessages = count($messages);
$unreadMessages = count(array_filter($messages, function($m) { return $m['status'] === 'Unread'; }));
$readMessages = $totalMessages - $unreadMessages;
$todayMessages = count(array_filter($messages, function($m) { return date('Y-m-d', strtotime($m['created_at'])) === date('Y-m-d'); }));
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inbox | Fair Surveying & Mapping Ltd</title>
    <link rel="icon" type="image/svg+xml" href="../../../images/logo.png">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- DataTables -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css">
    <!-- Admin CSS -->
    <link rel="stylesheet" href="../../css/admin.css">
</head>
<body>
    <div class="admin-layout">
        <!-- Sidebar -->
        <?php include '../includes/sidebar.php'; ?>

        <!-- Header -->
        <?php include '../includes/header.php'; ?>

        <!-- Main Content -->
        <main class="admin-main">
            <div class="page-header">
                <div>
                    <h1 class="page-title">Inbox</h1>
                    <p class="page-subtitle">Manage all messages and communications.</p>
                </div>
            </div>

            <!-- Stats Cards -->
            <div class="row mb-4">
                <div class="col-md-3">
                    <div class="stats-card">
                        <div class="stats-icon">
                            <i class="fas fa-envelope"></i>
                        </div>
                        <div class="stats-content">
                            <h3><?php echo $totalMessages; ?></h3>
                            <p>Total Messages</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stats-card">
                        <div class="stats-icon">
                            <i class="fas fa-envelope-open"></i>
                        </div>
                        <div class="stats-content">
                            <h3><?php echo $unreadMessages; ?></h3>
                            <p>Unread Messages</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stats-card">
                        <div class="stats-icon">
                            <i class="fas fa-check-circle"></i>
                        </div>
                        <div class="stats-content">
                            <h3><?php echo $readMessages; ?></h3>
                            <p>Read Messages</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stats-card">
                        <div class="stats-icon">
                            <i class="fas fa-calendar-day"></i>
                        </div>
                        <div class="stats-content">
                            <h3><?php echo $todayMessages; ?></h3>
                            <p>Today's Messages</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Messages Table -->
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">All Messages</h5>
                    <div class="card-tools">
                        <button class="btn btn-light">
                            <i class="fas fa-download"></i> Export
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="messagesTable" class="table table-hover">
                            <thead>
                                <tr>
                                    <th>
                                        <input type="checkbox" class="form-check-input">
                                    </th>
                                    <th>Sender</th>
                                    <th>Subject</th>
                                    <th>Type</th>
                                    <th>Date</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($messages as $index => $message): ?>
                                <tr class="<?php echo $message['status'] === 'Unread' ? 'table-warning' : ''; ?>">
                                    <td>
                                        <input type="checkbox" class="form-check-input" value="<?php echo $message['id']; ?>">
                                    </td>
                                    <td>
                                        <div class="d-flex align-center gap-2">
                                            <div class="message-avatar" style="width: 40px; height: 40px; background: #3498db; color: white; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                                                <?php echo strtoupper(substr($message['sender_name'], 0, 1)); ?>
                                            </div>
                                            <div>
                                                <div class="fw-bold"><?php echo htmlspecialchars($message['sender_name']); ?></div>
                                                <div class="text-gray" style="font-size: 0.85rem"><?php echo htmlspecialchars($message['sender_email']); ?></div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="fw-bold"><?php echo htmlspecialchars($message['subject']); ?></div>
                                        <div class="text-gray" style="font-size: 0.85rem"><?php echo htmlspecialchars(substr($message['message'], 0, 50)) . (strlen($message['message']) > 50 ? '...' : ''); ?></div>
                                    </td>
                                    <td>
                                        <span class="badge bg-secondary"><?php echo htmlspecialchars($message['type'] ?? 'Email'); ?></span>
                                    </td>
                                    <td><?php echo date('M d, Y H:i', strtotime($message['created_at'])); ?></td>
                                    <td>
                                        <span class="badge <?php echo $message['status'] === 'Unread' ? 'bg-warning' : 'bg-success'; ?>">
                                            <?php echo $message['status']; ?>
                                        </span>
                                    </td>
                                    <td>
                                        <div class="d-flex gap-1">
                                            <button class="btn btn-sm btn-light" title="View" onclick="viewMessage(<?php echo $message['id']; ?>)">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <button class="btn btn-sm btn-secondary" title="Reply" onclick="replyMessage(<?php echo $message['id']; ?>)">
                                                <i class="fas fa-reply"></i>
                                            </button>
                                            <button class="btn btn-sm <?php echo $message['status'] === 'Unread' ? 'btn-info' : 'btn-warning'; ?>" 
                                                    title="<?php echo $message['status'] === 'Unread' ? 'Mark as Read' : 'Mark as Unread'; ?>" 
                                                    onclick="toggleMessageStatus(<?php echo $message['id']; ?>, '<?php echo $message['status']; ?>')">
                                                <i class="fas fa-<?php echo $message['status'] === 'Unread' ? 'envelope-open' : 'envelope'; ?>"></i>
                                            </button>
                                            <button class="btn btn-sm btn-danger" title="Delete" onclick="deleteMessage(<?php echo $message['id']; ?>)">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <!-- Notification Container -->
    <div class="notification-container"></div>

    <!-- JavaScript Dependencies -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>

    <!-- Admin JavaScript -->
    <script src="../../js/admin.js"></script>

    <script>
        // Initialize DataTable
        $(document).ready(function() {
            $('#messagesTable').DataTable({
                responsive: true,
                pageLength: 10,
                lengthMenu: [5, 10, 25, 50],
                language: {
                    search: "",
                    searchPlaceholder: "Search messages..."
                },
                order: [[4, 'desc']] // Sort by date descending
            });
        });
        
        // Message management functions
        function viewMessage(messageId) {
            window.location.href = `view-message.php?id=${messageId}`;
        }
        
        function replyMessage(messageId) {
            window.location.href = `reply-message.php?id=${messageId}`;
        }
        
        function deleteMessage(messageId) {
            if (confirm('Are you sure you want to delete this message? This action cannot be undone.')) {
                $.ajax({
                    url: '',
                    method: 'POST',
                    data: {
                        action: 'delete',
                        id: messageId
                    },
                    dataType: 'json',
                    success: function(response) {
                        if (response.success) {
                            showNotification('Message deleted successfully!', 'success');
                            setTimeout(() => {
                                location.reload();
                            }, 1500);
                        } else {
                            showNotification('Failed to delete message. Please try again.', 'error');
                        }
                    },
                    error: function() {
                        showNotification('An error occurred. Please try again.', 'error');
                    }
                });
            }
        }
        
        function toggleMessageStatus(messageId, currentStatus) {
            const action = currentStatus === 'Unread' ? 'mark_read' : 'mark_unread';
            $.ajax({
                url: '',
                method: 'POST',
                data: {
                    action: action,
                    id: messageId
                },
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        showNotification(`Message marked as ${currentStatus === 'Unread' ? 'read' : 'unread'}!`, 'success');
                        setTimeout(() => {
                            location.reload();
                        }, 1500);
                    } else {
                        showNotification('Failed to update message status. Please try again.', 'error');
                    }
                },
                error: function() {
                    showNotification('An error occurred. Please try again.', 'error');
                }
            });
        }
        
        // Notification function
        function showNotification(message, type) {
            const notification = $(`
                <div class="notification notification-${type}">
                    <i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-circle'}"></i>
                    <span>${message}</span>
                </div>
            `);
            
            $('.notification-container').append(notification);
            
            setTimeout(() => {
                notification.fadeOut(() => {
                    notification.remove();
                });
            }, 3000);
        }
    </script>
</body>
</html>
