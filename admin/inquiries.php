<?php
/**
 * Inquiries & Contact Management for FSMC Admin System
 * Created: 2025-01-22
 */

require_once 'config/config.php';

// Set page variables
$pageTitle = 'Inquiries & Contact Management';
$pageIcon = 'fas fa-envelope';
$pageDescription = 'Manage contact form submissions and inquiries';

// Handle actions
$action = $_GET['action'] ?? '';
$inquiryId = $_GET['id'] ?? 0;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verifyCSRFToken($_POST['csrf_token'] ?? '')) {
        setErrorMessage('Invalid security token.');
        redirect('inquiries.php');
    }
    
    if ($action === 'delete' && $inquiryId) {
        // Delete inquiry
        $inquiry = dbGetRow("SELECT name, email FROM inquiries WHERE id = ?", [$inquiryId]);
        if ($inquiry) {
            if (dbExecute("DELETE FROM inquiries WHERE id = ?", [$inquiryId])) {
                logActivity('Inquiry Deleted', 'inquiries', $inquiryId, $inquiry);
                setSuccessMessage('Inquiry from "' . $inquiry['name'] . '" has been deleted successfully.');
            } else {
                setErrorMessage('Failed to delete inquiry.');
            }
        }
        redirect('inquiries.php');
    }
    
    if ($action === 'mark_read' && $inquiryId) {
        // Mark inquiry as read
        if (dbExecute("UPDATE inquiries SET status = 'read', updated_at = NOW() WHERE id = ?", [$inquiryId])) {
            logActivity('Inquiry Marked as Read', 'inquiries', $inquiryId);
            setSuccessMessage('Inquiry marked as read.');
        } else {
            setErrorMessage('Failed to update inquiry status.');
        }
        redirect('inquiries.php');
    }
    
    if ($action === 'mark_responded' && $inquiryId) {
        // Mark inquiry as responded
        if (dbExecute("UPDATE inquiries SET status = 'responded', updated_at = NOW() WHERE id = ?", [$inquiryId])) {
            logActivity('Inquiry Marked as Responded', 'inquiries', $inquiryId);
            setSuccessMessage('Inquiry marked as responded.');
        } else {
            setErrorMessage('Failed to update inquiry status.');
        }
        redirect('inquiries.php');
    }
}

// Get inquiries with pagination
$page = max(1, intval($_GET['page'] ?? 1));
$limit = DEFAULT_PAGE_SIZE;
$offset = ($page - 1) * $limit;

$search = sanitize($_GET['search'] ?? '');
$type = sanitize($_GET['type'] ?? '');
$status = sanitize($_GET['status'] ?? '');

// Build query
$whereConditions = [];
$params = [];

if ($search) {
    $whereConditions[] = "(name LIKE ? OR email LIKE ? OR subject LIKE ? OR message LIKE ?)";
    $params[] = "%$search%";
    $params[] = "%$search%";
    $params[] = "%$search%";
    $params[] = "%$search%";
}

if ($type) {
    $whereConditions[] = "type = ?";
    $params[] = $type;
}

if ($status) {
    $whereConditions[] = "status = ?";
    $params[] = $status;
}

$whereClause = !empty($whereConditions) ? 'WHERE ' . implode(' AND ', $whereConditions) : '';

// Get total count
$totalQuery = "SELECT COUNT(*) as total FROM inquiries $whereClause";
$totalResult = dbGetRow($totalQuery, $params);
$totalInquiries = $totalResult['total'] ?? 0;
$totalPages = ceil($totalInquiries / $limit);

// Get inquiries
$inquiriesQuery = "SELECT id, name, email, phone, subject, message, type, status, 
                          ip_address, user_agent, created_at, updated_at 
                   FROM inquiries $whereClause 
                   ORDER BY created_at DESC 
                   LIMIT $limit OFFSET $offset";
$inquiries = dbGetRows($inquiriesQuery, $params);

// Get types and statuses for filters
$types = ['general', 'service', 'product', 'training', 'support'];
$statuses = ['new', 'read', 'responded', 'closed'];

// Get statistics
$stats = [
    'total' => dbGetValue("SELECT COUNT(*) FROM inquiries"),
    'new' => dbGetValue("SELECT COUNT(*) FROM inquiries WHERE status = 'new'"),
    'read' => dbGetValue("SELECT COUNT(*) FROM inquiries WHERE status = 'read'"),
    'responded' => dbGetValue("SELECT COUNT(*) FROM inquiries WHERE status = 'responded'"),
    'today' => dbGetValue("SELECT COUNT(*) FROM inquiries WHERE DATE(created_at) = CURDATE()")
];

include 'includes/header.php';
?>

<!-- Statistics Cards -->
<div class="row mb-4">
    <div class="col-md-2">
        <div class="stats-card">
            <div class="stats-icon bg-primary">
                <i class="fas fa-envelope"></i>
            </div>
            <div class="stats-content">
                <h3><?php echo number_format($stats['total']); ?></h3>
                <p>Total Inquiries</p>
            </div>
        </div>
    </div>
    <div class="col-md-2">
        <div class="stats-card">
            <div class="stats-icon bg-warning">
                <i class="fas fa-envelope-open"></i>
            </div>
            <div class="stats-content">
                <h3><?php echo number_format($stats['new']); ?></h3>
                <p>New</p>
            </div>
        </div>
    </div>
    <div class="col-md-2">
        <div class="stats-card">
            <div class="stats-icon bg-info">
                <i class="fas fa-eye"></i>
            </div>
            <div class="stats-content">
                <h3><?php echo number_format($stats['read']); ?></h3>
                <p>Read</p>
            </div>
        </div>
    </div>
    <div class="col-md-2">
        <div class="stats-card">
            <div class="stats-icon bg-success">
                <i class="fas fa-reply"></i>
            </div>
            <div class="stats-content">
                <h3><?php echo number_format($stats['responded']); ?></h3>
                <p>Responded</p>
            </div>
        </div>
    </div>
    <div class="col-md-2">
        <div class="stats-card">
            <div class="stats-icon bg-accent">
                <i class="fas fa-calendar-day"></i>
            </div>
            <div class="stats-content">
                <h3><?php echo number_format($stats['today']); ?></h3>
                <p>Today</p>
            </div>
        </div>
    </div>
</div>

<!-- Filters -->
<div class="card mb-3">
    <div class="card-body">
        <form method="GET" class="row align-items-end">
            <div class="col-md-4">
                <label for="search" class="form-label">Search Inquiries</label>
                <input type="text" id="search" name="search" class="form-control" 
                       value="<?php echo htmlspecialchars($search); ?>" 
                       placeholder="Search by name, email, subject, or message...">
            </div>
            <div class="col-md-2">
                <label for="type" class="form-label">Type</label>
                <select id="type" name="type" class="form-control">
                    <option value="">All Types</option>
                    <?php foreach ($types as $t): ?>
                        <option value="<?php echo $t; ?>" <?php echo $type === $t ? 'selected' : ''; ?>>
                            <?php echo ucfirst($t); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-2">
                <label for="status" class="form-label">Status</label>
                <select id="status" name="status" class="form-control">
                    <option value="">All Statuses</option>
                    <?php foreach ($statuses as $s): ?>
                        <option value="<?php echo $s; ?>" <?php echo $status === $s ? 'selected' : ''; ?>>
                            <?php echo ucfirst($s); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-3">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-search"></i> Search
                </button>
                <a href="inquiries.php" class="btn btn-secondary">
                    <i class="fas fa-times"></i> Clear
                </a>
            </div>
        </form>
    </div>
</div>

<!-- Inquiries Table -->
<div class="card">
    <div class="card-header">
        <h3 class="card-title">
            <i class="fas fa-list"></i>
            Inquiries (<?php echo number_format($totalInquiries); ?> total)
        </h3>
    </div>
    <div class="card-body">
        <?php if (empty($inquiries)): ?>
            <div class="text-center py-4">
                <i class="fas fa-envelope fa-3x text-muted mb-3"></i>
                <h5 class="text-muted">No inquiries found</h5>
                <p class="text-muted">No inquiries match your search criteria.</p>
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Contact</th>
                            <th>Subject</th>
                            <th>Type</th>
                            <th>Status</th>
                            <th>Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($inquiries as $inquiry): ?>
                        <tr class="<?php echo $inquiry['status'] === 'new' ? 'table-warning' : ''; ?>">
                            <td>
                                <div>
                                    <strong><?php echo htmlspecialchars($inquiry['name']); ?></strong><br>
                                    <small class="text-muted">
                                        <i class="fas fa-envelope"></i> <?php echo htmlspecialchars($inquiry['email']); ?>
                                    </small><br>
                                    <?php if ($inquiry['phone']): ?>
                                        <small class="text-muted">
                                            <i class="fas fa-phone"></i> <?php echo htmlspecialchars($inquiry['phone']); ?>
                                        </small>
                                    <?php endif; ?>
                                </div>
                            </td>
                            <td>
                                <div>
                                    <strong><?php echo htmlspecialchars($inquiry['subject']); ?></strong><br>
                                    <small class="text-muted">
                                        <?php 
                                        $message = $inquiry['message'] ?? '';
                                        echo htmlspecialchars(substr($message, 0, 80)) . (strlen($message) > 80 ? '...' : ''); 
                                        ?>
                                    </small>
                                </div>
                            </td>
                            <td>
                                <span class="badge badge-<?php 
                                    echo $inquiry['type'] === 'general' ? 'secondary' : 
                                        ($inquiry['type'] === 'service' ? 'primary' : 
                                        ($inquiry['type'] === 'product' ? 'info' : 
                                        ($inquiry['type'] === 'training' ? 'warning' : 'danger'))); 
                                ?>">
                                    <?php echo ucfirst($inquiry['type']); ?>
                                </span>
                            </td>
                            <td>
                                <span class="badge badge-<?php 
                                    echo $inquiry['status'] === 'new' ? 'warning' : 
                                        ($inquiry['status'] === 'read' ? 'info' : 
                                        ($inquiry['status'] === 'responded' ? 'success' : 'secondary')); 
                                ?>">
                                    <?php echo ucfirst($inquiry['status']); ?>
                                </span>
                            </td>
                            <td>
                                <small>
                                    <?php echo date('M j, Y', strtotime($inquiry['created_at'])); ?><br>
                                    <?php echo date('H:i', strtotime($inquiry['created_at'])); ?>
                                </small>
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <button type="button" class="btn btn-outline-primary btn-view" 
                                            data-inquiry='<?php echo json_encode($inquiry); ?>' title="View Details">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    
                                    <?php if ($inquiry['status'] === 'new'): ?>
                                        <form method="POST" style="display: inline;" 
                                              action="inquiries.php?action=mark_read&id=<?php echo $inquiry['id']; ?>">
                                            <input type="hidden" name="csrf_token" value="<?php echo generateCSRFToken(); ?>">
                                            <button type="submit" class="btn btn-outline-info" title="Mark as Read">
                                                <i class="fas fa-check"></i>
                                            </button>
                                        </form>
                                    <?php endif; ?>
                                    
                                    <?php if ($inquiry['status'] !== 'responded'): ?>
                                        <form method="POST" style="display: inline;" 
                                              action="inquiries.php?action=mark_responded&id=<?php echo $inquiry['id']; ?>">
                                            <input type="hidden" name="csrf_token" value="<?php echo generateCSRFToken(); ?>">
                                            <button type="submit" class="btn btn-outline-success" title="Mark as Responded">
                                                <i class="fas fa-reply"></i>
                                            </button>
                                        </form>
                                    <?php endif; ?>
                                    
                                    <button type="button" 
                                            class="btn btn-outline-danger btn-delete" 
                                            data-url="inquiries.php?action=delete&id=<?php echo $inquiry['id']; ?>"
                                            data-name="<?php echo htmlspecialchars($inquiry['name']); ?>"
                                            title="Delete">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            <?php if ($totalPages > 1): ?>
            <nav aria-label="Inquiries pagination">
                <ul class="pagination justify-content-center">
                    <?php if ($page > 1): ?>
                        <li class="page-item">
                            <a class="page-link" href="?page=<?php echo $page - 1; ?>&search=<?php echo urlencode($search); ?>&type=<?php echo urlencode($type); ?>&status=<?php echo urlencode($status); ?>">
                                <i class="fas fa-chevron-left"></i> Previous
                            </a>
                        </li>
                    <?php endif; ?>
                    
                    <?php for ($i = max(1, $page - 2); $i <= min($totalPages, $page + 2); $i++): ?>
                        <li class="page-item <?php echo $i === $page ? 'active' : ''; ?>">
                            <a class="page-link" href="?page=<?php echo $i; ?>&search=<?php echo urlencode($search); ?>&type=<?php echo urlencode($type); ?>&status=<?php echo urlencode($status); ?>">
                                <?php echo $i; ?>
                            </a>
                        </li>
                    <?php endfor; ?>
                    
                    <?php if ($page < $totalPages): ?>
                        <li class="page-item">
                            <a class="page-link" href="?page=<?php echo $page + 1; ?>&search=<?php echo urlencode($search); ?>&type=<?php echo urlencode($type); ?>&status=<?php echo urlencode($status); ?>">
                                Next <i class="fas fa-chevron-right"></i>
                            </a>
                        </li>
                    <?php endif; ?>
                </ul>
            </nav>
            <?php endif; ?>
        <?php endif; ?>
    </div>
</div>

<!-- View Inquiry Modal -->
<div class="modal" id="viewModal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Inquiry Details</h5>
                <button type="button" class="modal-close" data-dismiss="modal">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="modal-body">
                <div id="inquiry-details"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <a href="#" id="reply-email" class="btn btn-primary">
                    <i class="fas fa-reply"></i> Reply via Email
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal" id="deleteModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirm Deletion</h5>
                <button type="button" class="modal-close" data-dismiss="modal">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete this inquiry? This action cannot be undone.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <form id="deleteForm" method="POST" style="display: inline;">
                    <input type="hidden" name="csrf_token" value="<?php echo generateCSRFToken(); ?>">
                    <button type="submit" class="btn btn-danger">Delete</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Handle view buttons
    document.querySelectorAll('.btn-view').forEach(function(btn) {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            
            const inquiry = JSON.parse(this.dataset.inquiry);
            const modal = document.getElementById('viewModal');
            const detailsDiv = document.getElementById('inquiry-details');
            const replyLink = document.getElementById('reply-email');
            
            // Build inquiry details HTML
            const html = `
                <div class="row">
                    <div class="col-md-6">
                        <h6>Contact Information</h6>
                        <p><strong>Name:</strong> ${inquiry.name}</p>
                        <p><strong>Email:</strong> ${inquiry.email}</p>
                        ${inquiry.phone ? `<p><strong>Phone:</strong> ${inquiry.phone}</p>` : ''}
                    </div>
                    <div class="col-md-6">
                        <h6>Inquiry Details</h6>
                        <p><strong>Type:</strong> <span class="badge badge-secondary">${inquiry.type}</span></p>
                        <p><strong>Status:</strong> <span class="badge badge-info">${inquiry.status}</span></p>
                        <p><strong>Date:</strong> ${new Date(inquiry.created_at).toLocaleString()}</p>
                    </div>
                </div>
                <hr>
                <h6>Subject</h6>
                <p>${inquiry.subject}</p>
                <h6>Message</h6>
                <p style="white-space: pre-wrap;">${inquiry.message}</p>
                ${inquiry.ip_address ? `<hr><small class="text-muted">IP Address: ${inquiry.ip_address}</small>` : ''}
            `;
            
            detailsDiv.innerHTML = html;
            
            // Set reply email link
            const subject = encodeURIComponent('Re: ' + inquiry.subject);
            const body = encodeURIComponent(`Dear ${inquiry.name},\n\nThank you for contacting Fair Surveying & Mapping Company.\n\n`);
            replyLink.href = `mailto:${inquiry.email}?subject=${subject}&body=${body}`;
            
            // Show modal
            modal.classList.add('show');
        });
    });
    
    // Handle delete buttons
    document.querySelectorAll('.btn-delete').forEach(function(btn) {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            
            const url = this.dataset.url;
            const name = this.dataset.name;
            const modal = document.getElementById('deleteModal');
            const form = document.getElementById('deleteForm');
            
            // Update modal content
            modal.querySelector('.modal-body p').textContent = 
                `Are you sure you want to delete the inquiry from "${name}"? This action cannot be undone.`;
            
            // Update form action
            form.action = url;
            
            // Show modal
            modal.classList.add('show');
        });
    });
});
</script>

<?php include 'includes/footer.php'; ?>
