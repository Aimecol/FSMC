<?php
/**
 * Contact Page Management
 * Comprehensive management interface for Contact page content and messages
 */

require_once 'config/config.php';
require_once 'config/database.php';

// Check authentication
requireAuth();

// Handle form submissions
$message = '';
$messageType = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verifyCSRFToken($_POST['csrf_token'] ?? '')) {
        die('Invalid CSRF token');
    }
    
    $action = $_POST['action'] ?? '';
    
    try {
        switch ($action) {
            case 'update_contact_info':
                updateContactInfo($_POST);
                $message = 'Contact information updated successfully!';
                $messageType = 'success';
                break;
                
            case 'update_business_hours':
                updateBusinessHours($_POST);
                $message = 'Business hours updated successfully!';
                $messageType = 'success';
                break;
                
            case 'update_message_status':
                updateMessageStatus($_POST['message_id'], $_POST['status'], $_POST['admin_notes'] ?? '');
                $message = 'Message status updated successfully!';
                $messageType = 'success';
                break;
                
            case 'delete_message':
                deleteContactMessage($_POST['message_id']);
                $message = 'Message deleted successfully!';
                $messageType = 'success';
                break;
        }
    } catch (Exception $e) {
        $message = 'Error: ' . $e->getMessage();
        $messageType = 'error';
    }
}

// Helper functions
function updateContactInfo($data) {
    foreach ($data['contact_info'] as $infoKey => $info) {
        dbExecute("
            UPDATE contact_info 
            SET title = ?, value = ?, icon = ?, link_type = ?, sort_order = ?, updated_at = NOW()
            WHERE info_key = ?
        ", [
            $info['title'],
            $info['value'],
            $info['icon'] ?? null,
            $info['link_type'] ?? 'none',
            intval($info['sort_order'] ?? 0),
            $infoKey
        ]);
    }
}

function updateBusinessHours($data) {
    foreach ($data['business_hours'] as $dayKey => $hours) {
        $isClosed = isset($hours['is_closed']) ? 1 : 0;
        $openingTime = $isClosed ? null : ($hours['opening_time'] ?? null);
        $closingTime = $isClosed ? null : ($hours['closing_time'] ?? null);
        
        dbExecute("
            UPDATE business_hours 
            SET day_label = ?, opening_time = ?, closing_time = ?, is_closed = ?, 
                custom_text = ?, sort_order = ?, updated_at = NOW()
            WHERE day_key = ?
        ", [
            $hours['day_label'],
            $openingTime,
            $closingTime,
            $isClosed,
            $hours['custom_text'] ?? null,
            intval($hours['sort_order'] ?? 0),
            $dayKey
        ]);
    }
}

function updateMessageStatus($messageId, $status, $adminNotes = '') {
    dbExecute("
        UPDATE contact_messages 
        SET status = ?, admin_notes = ?, updated_at = NOW()
        WHERE id = ?
    ", [$status, $adminNotes, $messageId]);
}

function deleteContactMessage($messageId) {
    dbExecute("DELETE FROM contact_messages WHERE id = ?", [$messageId]);
}

// Fetch data for display
$contactInfo = [];
$stmt = dbGetRows("SELECT * FROM contact_info WHERE is_active = 1 ORDER BY sort_order ASC");
foreach ($stmt as $row) {
    $contactInfo[$row['info_key']] = $row;
}

$businessHours = [];
$stmt = dbGetRows("SELECT * FROM business_hours WHERE is_active = 1 ORDER BY sort_order ASC");
foreach ($stmt as $row) {
    $businessHours[$row['day_key']] = $row;
}

// Pagination for messages
$page = intval($_GET['page'] ?? 1);
$perPage = 20;
$offset = ($page - 1) * $perPage;

$totalMessages = dbGetValue("SELECT COUNT(*) FROM contact_messages");
$totalPages = ceil($totalMessages / $perPage);

$contactMessages = dbGetRows("
    SELECT * FROM contact_messages 
    ORDER BY created_at DESC 
    LIMIT ? OFFSET ?
", [$perPage, $offset]);

// Message statistics
$messageStatsRows = dbGetRows("
    SELECT 
        status,
        COUNT(*) as count
    FROM contact_messages 
    GROUP BY status
");
$messageStats = [];
foreach ($messageStatsRows as $row) {
    $messageStats[$row['status']] = $row['count'];
}

// For now, we'll create a simple header since includes/header.php might not exist
include 'includes/header.php';
?>

<div class="container-fluid about-manage-page">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0"><i class="fas fa-phone-alt me-2"></i>Contact Page Management</h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
                        <li class="breadcrumb-item active">Contact Management</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <?php if ($message): ?>
    <div class="row">
        <div class="col-12">
            <div class="alert alert-<?php echo $messageType === 'success' ? 'success' : 'danger'; ?> alert-dismissible fade show" role="alert">
                <?php echo htmlspecialchars($message); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        </div>
    </div>
    <?php endif; ?>
    <!-- Message Statistics gap in the cards -->
    <div class="row g-3">
        <div class="col-xl-3 col-md-6">
            <div class="card card-h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <span class="text-muted mb-3 lh-1 d-block text-truncate">Total Messages</span>
                            <h4 class="mb-3">
                                <span class="counter-value" data-target="<?php echo $totalMessages; ?>">0</span>
                            </h4>
                        </div>
                        <div class="flex-shrink-0">
                            <div class="avatar-sm rounded-circle bg-primary">
                                <span class="avatar-title rounded-circle bg-primary">
                                    <i class="fas fa-envelope font-size-24"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-md-6">
            <div class="card card-h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <span class="text-muted mb-3 lh-1 d-block text-truncate">New Messages</span>
                            <h4 class="mb-3">
                                <span class="counter-value" data-target="<?php echo $messageStats['new'] ?? 0; ?>">0</span>
                            </h4>
                        </div>
                        <div class="flex-shrink-0">
                            <div class="avatar-sm rounded-circle bg-success">
                                <span class="avatar-title rounded-circle bg-success">
                                    <i class="fas fa-envelope-open font-size-24"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-md-6">
            <div class="card card-h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <span class="text-muted mb-3 lh-1 d-block text-truncate">Read Messages</span>
                            <h4 class="mb-3">
                                <span class="counter-value" data-target="<?php echo $messageStats['read'] ?? 0; ?>">0</span>
                            </h4>
                        </div>
                        <div class="flex-shrink-0">
                            <div class="avatar-sm rounded-circle bg-info">
                                <span class="avatar-title rounded-circle bg-info">
                                    <i class="fas fa-eye font-size-24"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-md-6">
            <div class="card card-h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <span class="text-muted mb-3 lh-1 d-block text-truncate">Replied Messages</span>
                            <h4 class="mb-3">
                                <span class="counter-value" data-target="<?php echo $messageStats['replied'] ?? 0; ?>">0</span>
                            </h4>
                        </div>
                        <div class="flex-shrink-0">
                            <div class="avatar-sm rounded-circle bg-warning">
                                <span class="avatar-title rounded-circle bg-warning">
                                    <i class="fas fa-reply font-size-24"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Contact Information Management -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title mb-0"><i class="fas fa-address-book me-2"></i>Contact Information</h4>
                </div>
                <div class="card-body">
                    <form method="POST">
                        <input type="hidden" name="csrf_token" value="<?php echo generateCSRFToken(); ?>">
                        <input type="hidden" name="action" value="update_contact_info">
                        
                        <div class="row">
                            <?php foreach ($contactInfo as $key => $info): ?>
                            <div class="col-md-6 mb-4">
                                <div class="border rounded p-3">
                                    <h6><?php echo htmlspecialchars($info['title']); ?></h6>
                                    
                                    <div class="form-group mb-3">
                                        <label class="form-label">Title</label>
                                        <input type="text" name="contact_info[<?php echo $key; ?>][title]" 
                                               class="form-control" value="<?php echo htmlspecialchars($info['title']); ?>">
                                    </div>
                                    
                                    <div class="form-group mb-3">
                                        <label class="form-label">Value</label>
                                        <textarea name="contact_info[<?php echo $key; ?>][value]" 
                                                  class="form-control" rows="3"><?php echo htmlspecialchars($info['value']); ?></textarea>
                                    </div>
                                    
                                    <div class="form-group mb-3">
                                        <label class="form-label">Icon</label>
                                        <input type="text" name="contact_info[<?php echo $key; ?>][icon]" 
                                               class="form-control" value="<?php echo htmlspecialchars($info['icon']); ?>">
                                    </div>
                                    
                                    <div class="form-group mb-3">
                                        <label class="form-label">Link Type</label>
                                        <select name="contact_info[<?php echo $key; ?>][link_type]" class="form-control">
                                            <option value="none" <?php echo $info['link_type'] === 'none' ? 'selected' : ''; ?>>None</option>
                                            <option value="tel" <?php echo $info['link_type'] === 'tel' ? 'selected' : ''; ?>>Phone</option>
                                            <option value="mailto" <?php echo $info['link_type'] === 'mailto' ? 'selected' : ''; ?>>Email</option>
                                            <option value="url" <?php echo $info['link_type'] === 'url' ? 'selected' : ''; ?>>URL</option>
                                        </select>
                                    </div>
                                    
                                    <input type="hidden" name="contact_info[<?php echo $key; ?>][sort_order]" 
                                           value="<?php echo $info['sort_order']; ?>">
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                        
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Update Contact Information
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Business Hours Management -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title mb-0"><i class="fas fa-clock me-2"></i>Business Hours</h4>
                </div>
                <div class="card-body">
                    <form method="POST">
                        <input type="hidden" name="csrf_token" value="<?php echo generateCSRFToken(); ?>">
                        <input type="hidden" name="action" value="update_business_hours">
                        
                        <?php foreach ($businessHours as $key => $hours): ?>
                        <div class="row mb-3 align-items-center">
                            <div class="col-md-3">
                                <label class="form-label">Day</label>
                                <input type="text" name="business_hours[<?php echo $key; ?>][day_label]" 
                                       class="form-control" value="<?php echo htmlspecialchars($hours['day_label']); ?>">
                            </div>
                            
                            <div class="col-md-2">
                                <label class="form-label">Opening Time</label>
                                <input type="time" name="business_hours[<?php echo $key; ?>][opening_time]" 
                                       class="form-control" value="<?php echo $hours['opening_time']; ?>"
                                       <?php echo $hours['is_closed'] ? 'disabled' : ''; ?>>
                            </div>
                            
                            <div class="col-md-2">
                                <label class="form-label">Closing Time</label>
                                <input type="time" name="business_hours[<?php echo $key; ?>][closing_time]" 
                                       class="form-control" value="<?php echo $hours['closing_time']; ?>"
                                       <?php echo $hours['is_closed'] ? 'disabled' : ''; ?>>
                            </div>
                            
                            <div class="col-md-2">
                                <label class="form-label">Custom Text</label>
                                <input type="text" name="business_hours[<?php echo $key; ?>][custom_text]" 
                                       class="form-control" value="<?php echo htmlspecialchars($hours['custom_text']); ?>"
                                       placeholder="e.g., Closed">
                            </div>
                            
                            <div class="col-md-2">
                                <div class="form-check mt-4">
                                    <input type="checkbox" name="business_hours[<?php echo $key; ?>][is_closed]" 
                                           class="form-check-input" value="1" 
                                           <?php echo $hours['is_closed'] ? 'checked' : ''; ?>
                                           onchange="toggleTimeInputs(this, '<?php echo $key; ?>')">
                                    <label class="form-check-label">Closed</label>
                                </div>
                            </div>
                            
                            <input type="hidden" name="business_hours[<?php echo $key; ?>][sort_order]" 
                                   value="<?php echo $hours['sort_order']; ?>">
                        </div>
                        <?php endforeach; ?>
                        
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Update Business Hours
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Message Statistics Dashboard -->
    <div class="row g-3">
        <div class="col-md-3">
            <div class="card text-center">
                <div class="card-body">
                    <div class="mb-2">
                        <i class="fas fa-envelope fa-2x text-primary"></i>
                    </div>
                    <h4 class="mb-1"><?php echo $totalMessages; ?></h4>
                    <p class="text-muted mb-0">Total Messages</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center">
                <div class="card-body">
                    <div class="mb-2">
                        <i class="fas fa-exclamation-circle fa-2x text-warning"></i>
                    </div>
                    <h4 class="mb-1"><?php echo $messageStats['new'] ?? 0; ?></h4>
                    <p class="text-muted mb-0">New Messages</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center">
                <div class="card-body">
                    <div class="mb-2">
                        <i class="fas fa-eye fa-2x text-info"></i>
                    </div>
                    <h4 class="mb-1"><?php echo $messageStats['read'] ?? 0; ?></h4>
                    <p class="text-muted mb-0">Read Messages</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center">
                <div class="card-body">
                    <div class="mb-2">
                        <i class="fas fa-reply fa-2x text-success"></i>
                    </div>
                    <h4 class="mb-1"><?php echo $messageStats['replied'] ?? 0; ?></h4>
                    <p class="text-muted mb-0">Replied</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Contact Messages -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="card-title mb-0"><i class="fas fa-envelope me-2"></i>Contact Messages</h4>
                    <div class="d-flex gap-2">
                        <select class="form-select form-select-sm" id="statusFilter" onchange="filterMessages()">
                            <option value="">All Status</option>
                            <option value="new">New</option>
                            <option value="read">Read</option>
                            <option value="replied">Replied</option>
                            <option value="archived">Archived</option>
                        </select>
                        <input type="text" class="form-control form-control-sm" id="searchMessages" placeholder="Search messages..." onkeyup="searchMessages()">
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-nowrap">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Subject</th>
                                    <th>Service Interest</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($contactMessages as $msg): ?>
                                <tr class="<?php echo $msg['status'] === 'new' ? 'table-warning' : ''; ?>">
                                    <td><?php echo date('M j, Y', strtotime($msg['created_at'])); ?></td>
                                    <td><?php echo htmlspecialchars($msg['name']); ?></td>
                                    <td><?php echo htmlspecialchars($msg['email']); ?></td>
                                    <td><?php echo htmlspecialchars($msg['subject'] ?? 'N/A'); ?></td>
                                    <td><?php echo htmlspecialchars($msg['service_interest'] ?? 'N/A'); ?></td>
                                    <td>
                                        <span class="badge bg-<?php 
                                            echo $msg['status'] === 'new' ? 'warning' : 
                                                ($msg['status'] === 'read' ? 'info' : 
                                                ($msg['status'] === 'replied' ? 'success' : 'secondary')); 
                                        ?>">
                                            <?php echo ucfirst($msg['status']); ?>
                                        </span>
                                    </td>
                                    <td>
                                        <button type="button" class="btn btn-sm btn-outline-primary view-message" 
                                                data-toggle="modal" data-target="#messageModal"
                                                data-message='<?php echo htmlspecialchars(json_encode($msg)); ?>'>
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <form method="POST" class="d-inline" onsubmit="return confirm('Are you sure?')">
                                            <input type="hidden" name="csrf_token" value="<?php echo generateCSRFToken(); ?>">
                                            <input type="hidden" name="action" value="delete_message">
                                            <input type="hidden" name="message_id" value="<?php echo $msg['id']; ?>">
                                            <button type="submit" class="btn btn-sm btn-outline-danger">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- Pagination -->
                    <?php if ($totalPages > 1): ?>
                    <nav aria-label="Page navigation">
                        <ul class="pagination justify-content-center">
                            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                            <li class="page-item <?php echo $i === $page ? 'active' : ''; ?>">
                                <a class="page-link" href="?page=<?php echo $i; ?>"><?php echo $i; ?></a>
                            </li>
                            <?php endfor; ?>
                        </ul>
                    </nav>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Message View Modal -->
<div class="modal fade" id="messageModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Contact Message Details</h5>
                <button type="button" class="btn-close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <div id="messageContent">
                    <!-- Message content will be loaded here -->
                </div>
                
                <form method="POST" class="mt-4">
                    <input type="hidden" name="csrf_token" value="<?php echo generateCSRFToken(); ?>">
                    <input type="hidden" name="action" value="update_message_status">
                    <input type="hidden" name="message_id" id="modalMessageId">
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Status</label>
                                <select name="status" id="modalStatus" class="form-control">
                                    <option value="new">New</option>
                                    <option value="read">Read</option>
                                    <option value="replied">Replied</option>
                                    <option value="archived">Archived</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group mt-3">
                        <label class="form-label">Admin Notes</label>
                        <textarea name="admin_notes" id="modalAdminNotes" class="form-control" rows="3"></textarea>
                    </div>
                    
                    <div class="mt-3">
                        <button type="submit" class="btn btn-primary">Update Status</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>

<script>
function toggleTimeInputs(checkbox, dayKey) {
    const openingInput = document.querySelector(`input[name="business_hours[${dayKey}][opening_time]"]`);
    const closingInput = document.querySelector(`input[name="business_hours[${dayKey}][closing_time]"]`);
    
    if (checkbox.checked) {
        openingInput.disabled = true;
        closingInput.disabled = true;
        openingInput.value = '';
        closingInput.value = '';
    } else {
        openingInput.disabled = false;
        closingInput.disabled = false;
    }
}

// Filter messages by status
function filterMessages() {
    const filter = document.getElementById('statusFilter').value.toLowerCase();
    const rows = document.querySelectorAll('tbody tr');
    
    rows.forEach(row => {
        const statusBadge = row.querySelector('.badge');
        const status = statusBadge ? statusBadge.textContent.toLowerCase() : '';
        
        if (filter === '' || status.includes(filter)) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
}

// Search messages
function searchMessages() {
    const searchTerm = document.getElementById('searchMessages').value.toLowerCase();
    const rows = document.querySelectorAll('tbody tr');
    
    rows.forEach(row => {
        const text = row.textContent.toLowerCase();
        if (text.includes(searchTerm)) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
}

document.addEventListener('DOMContentLoaded', function() {
    // Handle view message buttons
    document.querySelectorAll('.view-message').forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            const messageData = JSON.parse(this.getAttribute('data-message'));
            
            // Populate modal content with enhanced styling
            document.getElementById('messageContent').innerHTML = `
                <div class="card border-0 bg-light">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="text-muted small">Name</label>
                                    <div class="fw-bold">${messageData.name}</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="text-muted small">Email</label>
                                    <div class="fw-bold">
                                        <a href="mailto:${messageData.email}" class="text-decoration-none">
                                            ${messageData.email}
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="text-muted small">Phone</label>
                                    <div class="fw-bold">
                                        ${messageData.phone ? `<a href="tel:${messageData.phone}" class="text-decoration-none">${messageData.phone}</a>` : 'N/A'}
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="text-muted small">Date Received</label>
                                    <div class="fw-bold">${new Date(messageData.created_at).toLocaleDateString('en-US', {
                                        year: 'numeric',
                                        month: 'long',
                                        day: 'numeric',
                                        hour: '2-digit',
                                        minute: '2-digit'
                                    })}</div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="text-muted small">Subject</label>
                                    <div class="fw-bold">${messageData.subject || 'No subject'}</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="text-muted small">Service Interest</label>
                                    <div class="fw-bold">${messageData.service_interest || 'General inquiry'}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="mt-4">
                    <label class="text-muted small">Message</label>
                    <div class="card">
                        <div class="card-body">
                            ${messageData.message.replace(/\n/g, '<br>')}
                        </div>
                    </div>
                </div>
                
                ${messageData.admin_notes ? `
                <div class="mt-4">
                    <label class="text-muted small">Previous Admin Notes</label>
                    <div class="card border-warning">
                        <div class="card-body bg-warning bg-opacity-10">
                            ${messageData.admin_notes.replace(/\n/g, '<br>')}
                        </div>
                    </div>
                </div>
                ` : ''}
            `;
            
            // Set form values
            document.getElementById('modalMessageId').value = messageData.id;
            document.getElementById('modalStatus').value = messageData.status;
            document.getElementById('modalAdminNotes').value = messageData.admin_notes || '';
            
            // Auto-mark as read if it's new
            if (messageData.status === 'new') {
                document.getElementById('modalStatus').value = 'read';
            }
            
            // Show modal using admin.js compatible method
            document.getElementById('messageModal').classList.add('show');
        });
    });
    
    // Auto-hide success/error messages
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(alert => {
        setTimeout(() => {
            alert.style.opacity = '0';
            setTimeout(() => alert.remove(), 300);
        }, 5000);
    });
    
    // Add hover effects to message rows
    document.querySelectorAll('tbody tr').forEach(row => {
        row.addEventListener('mouseenter', function() {
            this.style.backgroundColor = '#f8f9fa';
        });
        row.addEventListener('mouseleave', function() {
            this.style.backgroundColor = '';
        });
    });
});
</script>
