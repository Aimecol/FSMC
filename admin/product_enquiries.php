<?php
/**
 * Product Enquiries Management for FSMC Admin System
 * Created: 2025-01-26
 */

require_once 'config/config.php';

// Set page variables
$pageTitle = 'Product Enquiries';
$pageIcon = 'fas fa-envelope';
$pageDescription = 'Manage customer product inquiries from initial contact to quote and closure';

// Get filter parameters
$productId = intval($_GET['product_id'] ?? 0);
$status = sanitize($_GET['status'] ?? '');
$search = sanitize($_GET['search'] ?? '');

// Handle actions
$action = $_GET['action'] ?? '';
$enquiryId = intval($_GET['id'] ?? 0);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verifyCSRFToken($_POST['csrf_token'] ?? '')) {
        setErrorMessage('Invalid security token.');
        redirect('product_enquiries.php');
    }
    
    if ($action === 'update_status' && $enquiryId) {
        $newStatus = sanitize($_POST['status'] ?? '');
        $notes = sanitize($_POST['notes'] ?? '');
        $assignedTo = intval($_POST['assigned_to'] ?? 0) ?: null;
        
        if (in_array($newStatus, ['new', 'contacted', 'quoted', 'closed'])) {
            $sql = "UPDATE product_inquiries SET status = ?, assigned_to = ?, notes = ?, updated_at = NOW() WHERE id = ?";
            if (dbExecute($sql, [$newStatus, $assignedTo, $notes, $enquiryId])) {
                logActivity('Enquiry Status Updated', 'product_enquiries', $enquiryId, ['status' => $newStatus]);
                setSuccessMessage('Enquiry status updated successfully.');
            } else {
                setErrorMessage('Failed to update enquiry status.');
            }
        }
        redirect('product_enquiries.php' . ($productId ? '?product_id=' . $productId : ''));
    }
    
    if ($action === 'delete' && $enquiryId) {
        $enquiry = dbGetRow("SELECT id FROM product_inquiries WHERE id = ?", [$enquiryId]);
        if ($enquiry) {
            if (dbExecute("DELETE FROM product_inquiries WHERE id = ?", [$enquiryId])) {
                logActivity('Enquiry Deleted', 'product_enquiries', $enquiryId);
                setSuccessMessage('Enquiry has been deleted successfully.');
            } else {
                setErrorMessage('Failed to delete enquiry.');
            }
        }
        redirect('product_enquiries.php' . ($productId ? '?product_id=' . $productId : ''));
    }
}

// Build query conditions
$whereConditions = [];
$params = [];

if ($productId) {
    $whereConditions[] = "pe.product_id = ?";
    $params[] = $productId;
}

if ($status) {
    $whereConditions[] = "pe.status = ?";
    $params[] = $status;
}

if ($search) {
    $whereConditions[] = "(pe.name LIKE ? OR pe.email LIKE ? OR pe.company LIKE ? OR pe.message LIKE ? OR p.title LIKE ?)";
    $params[] = "%$search%";
    $params[] = "%$search%";
    $params[] = "%$search%";
    $params[] = "%$search%";
    $params[] = "%$search%";
}

$whereClause = !empty($whereConditions) ? 'WHERE ' . implode(' AND ', $whereConditions) : '';

// Get enquiries with pagination
$page = max(1, intval($_GET['page'] ?? 1));
$limit = DEFAULT_PAGE_SIZE;
$offset = ($page - 1) * $limit;

// Get total count
$totalQuery = "SELECT COUNT(*) as total 
               FROM product_inquiries pe 
               LEFT JOIN products p ON pe.product_id = p.id 
               $whereClause";
$totalResult = dbGetRow($totalQuery, $params);
$totalEnquiries = $totalResult['total'] ?? 0;
$totalPages = ceil($totalEnquiries / $limit);

// Get enquiries (without user join since users table may not exist)
$enquiriesQuery = "SELECT pe.*, p.title as product_title, p.category as product_category
                   FROM product_inquiries pe 
                   LEFT JOIN products p ON pe.product_id = p.id 
                   $whereClause 
                   ORDER BY pe.created_at DESC 
                   LIMIT $limit OFFSET $offset";
$enquiries = dbGetRows($enquiriesQuery, $params);

// Get products for filter
$products = dbGetRows("SELECT id, title FROM products WHERE status = 'active' ORDER BY title ASC");

// Get users for assignment (optional - only if users table exists)
$users = [];
try {
    $users = dbGetRows("SELECT id, username, first_name, last_name FROM users WHERE status = 'active' ORDER BY first_name ASC");
} catch (Exception $e) {
    // Users table doesn't exist - assignment feature will be disabled
    $users = [];
}

// Get selected product info if filtering by product
$selectedProduct = null;
if ($productId) {
    $selectedProduct = dbGetRow("SELECT title FROM products WHERE id = ?", [$productId]);
}

// Update page actions based on context
if ($selectedProduct) {
    $pageActions = '<a href="product_enquiries.php" class="btn btn-secondary me-2"><i class="fas fa-arrow-left"></i> All Enquiries</a>';
    $pageTitle = 'Enquiries for: ' . $selectedProduct['title'];
} else {
    $pageActions = '<a href="products.php" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Back to Products</a>';
}

include 'includes/header.php';
?>

<!-- Filters -->
<div class="card mb-3">
    <div class="card-body">
        <form method="GET" class="row align-items-end">
            <?php if ($productId): ?>
                <input type="hidden" name="product_id" value="<?php echo $productId; ?>">
            <?php endif; ?>
            
            <div class="col-md-3">
                <label for="search" class="form-label">Search Enquiries</label>
                <input type="text" id="search" name="search" class="form-control" 
                       value="<?php echo htmlspecialchars($search); ?>" 
                       placeholder="Search by name, email, company, or message...">
            </div>
            
            <?php if (!$productId): ?>
            <div class="col-md-3">
                <label for="product_filter" class="form-label">Product</label>
                <select id="product_filter" name="product_id" class="form-control">
                    <option value="">All Products</option>
                    <?php foreach ($products as $product): ?>
                        <option value="<?php echo $product['id']; ?>" <?php echo $productId == $product['id'] ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($product['title']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <?php endif; ?>
            
            <div class="col-md-2">
                <label for="status" class="form-label">Status</label>
                <select id="status" name="status" class="form-control">
                    <option value="">All Statuses</option>
                    <option value="new" <?php echo $status === 'new' ? 'selected' : ''; ?>>New</option>
                    <option value="contacted" <?php echo $status === 'contacted' ? 'selected' : ''; ?>>Contacted</option>
                    <option value="quoted" <?php echo $status === 'quoted' ? 'selected' : ''; ?>>Quoted</option>
                    <option value="closed" <?php echo $status === 'closed' ? 'selected' : ''; ?>>Closed</option>
                </select>
            </div>
            
            <div class="col-md-4">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-search"></i> Search
                </button>
                <a href="product_enquiries.php<?php echo $productId ? '?product_id=' . $productId : ''; ?>" class="btn btn-secondary">
                    <i class="fas fa-times"></i> Clear
                </a>
            </div>
        </form>
    </div>
</div>

<!-- Enquiries Statistics -->
<?php if (!$productId): ?>
<div class="row mb-3">
    <?php
    $stats = dbGetRow("SELECT 
        COUNT(*) as total,
        SUM(CASE WHEN status = 'new' THEN 1 ELSE 0 END) as new,
        SUM(CASE WHEN status = 'contacted' THEN 1 ELSE 0 END) as contacted,
        SUM(CASE WHEN status = 'quoted' THEN 1 ELSE 0 END) as quoted,
        SUM(CASE WHEN status = 'closed' THEN 1 ELSE 0 END) as closed
        FROM product_inquiries");
    ?>
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="card bg-primary text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4><?php echo number_format($stats['total'] ?? 0); ?></h4>
                        <p class="mb-0">Total Inquiries</p>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-envelope fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="card bg-warning text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4><?php echo number_format($stats['new'] ?? 0); ?></h4>
                        <p class="mb-0">New</p>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-envelope-open fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="card bg-info text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4><?php echo number_format($stats['contacted'] ?? 0); ?></h4>
                        <p class="mb-0">Contacted</p>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-phone fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="card bg-success text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4><?php echo number_format($stats['quoted'] ?? 0); ?></h4>
                        <p class="mb-0">Quoted</p>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-file-invoice-dollar fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row mb-3">
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="card bg-secondary text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4><?php echo number_format($stats['closed'] ?? 0); ?></h4>
                        <p class="mb-0">Closed</p>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-check-circle fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-9 col-md-6">
        <div class="card">
            <div class="card-body">
                <h6 class="card-title"><i class="fas fa-chart-line"></i> Inquiry Workflow</h6>
                <div class="d-flex align-items-center">
                    <span class="badge badge-warning me-2">New</span>
                    <i class="fas fa-arrow-right text-muted me-2"></i>
                    <span class="badge badge-info me-2">Contacted</span>
                    <i class="fas fa-arrow-right text-muted me-2"></i>
                    <span class="badge badge-success me-2">Quoted</span>
                    <i class="fas fa-arrow-right text-muted me-2"></i>
                    <span class="badge badge-secondary">Closed</span>
                </div>
                <small class="text-muted mt-2 d-block">Track customer inquiries from initial contact through to final closure</small>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>

<!-- Enquiries Table -->
<div class="card">
    <div class="card-header">
        <h3 class="card-title">
            <i class="fas fa-list"></i>
            <?php if ($selectedProduct): ?>
                Enquiries for "<?php echo htmlspecialchars($selectedProduct['title']); ?>" (<?php echo number_format($totalEnquiries); ?> total)
            <?php else: ?>
                Product Enquiries (<?php echo number_format($totalEnquiries); ?> total)
            <?php endif; ?>
        </h3>
    </div>
    <div class="card-body">
        <?php if (empty($enquiries)): ?>
            <div class="text-center py-4">
                <i class="fas fa-envelope fa-3x text-muted mb-3"></i>
                <h5 class="text-muted">No enquiries found</h5>
                <p class="text-muted">
                    <?php if ($selectedProduct): ?>
                        No enquiries have been received for this product yet.
                    <?php else: ?>
                        No product enquiries have been received yet.
                    <?php endif; ?>
                </p>
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Customer</th>
                            <th>Product</th>
                            <th>Message</th>
                            <th>Status</th>
                            <th>Assigned To</th>
                            <th>Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($enquiries as $enquiry): ?>
                        <tr>
                            <td>
                                <div>
                                    <strong><?php echo htmlspecialchars($enquiry['name']); ?></strong><br>
                                    <small class="text-muted">
                                        <i class="fas fa-envelope"></i> <?php echo htmlspecialchars($enquiry['email']); ?>
                                    </small>
                                    <?php if ($enquiry['phone']): ?>
                                        <br><small class="text-muted">
                                            <i class="fas fa-phone"></i> <?php echo htmlspecialchars($enquiry['phone']); ?>
                                        </small>
                                    <?php endif; ?>
                                    <?php if ($enquiry['company']): ?>
                                        <br><small class="text-muted">
                                            <i class="fas fa-building"></i> <?php echo htmlspecialchars($enquiry['company']); ?>
                                        </small>
                                    <?php endif; ?>
                                </div>
                            </td>
                            <td>
                                <strong><?php echo htmlspecialchars($enquiry['product_title'] ?? 'Unknown Product'); ?></strong>
                                <?php if ($enquiry['product_category']): ?>
                                    <br><span class="badge badge-<?php 
                                        echo $enquiry['product_category'] === 'equipment' ? 'primary' : 
                                            ($enquiry['product_category'] === 'software' ? 'info' : 
                                            ($enquiry['product_category'] === 'training' ? 'warning' : 'success')); 
                                    ?>">
                                        <?php echo ucfirst($enquiry['product_category']); ?>
                                    </span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <div class="message-preview" style="max-width: 300px;">
                                    <?php 
                                    $message = htmlspecialchars($enquiry['message']);
                                    echo strlen($message) > 100 ? substr($message, 0, 100) . '...' : $message;
                                    ?>
                                </div>
                            </td>
                            <td>
                                <span class="badge badge-<?php 
                                    echo $enquiry['status'] === 'new' ? 'primary' : 
                                        ($enquiry['status'] === 'contacted' ? 'info' : 
                                        ($enquiry['status'] === 'quoted' ? 'warning' : 'secondary')); 
                                ?>">
                                    <?php echo ucfirst(str_replace('_', ' ', $enquiry['status'])); ?>
                                </span>
                            </td>
                            <td>
                                <?php if (!empty($users) && $enquiry['assigned_to']): ?>
                                    <small>User ID: <?php echo $enquiry['assigned_to']; ?></small>
                                <?php else: ?>
                                    <span class="text-muted">Unassigned</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <small><?php echo date('M j, Y g:i A', strtotime($enquiry['created_at'])); ?></small>
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <button type="button" 
                                            class="btn btn-outline-primary btn-view-enquiry" 
                                            data-enquiry-id="<?php echo $enquiry['id']; ?>"
                                            title="View Details">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    
                                    <button type="button" 
                                            class="btn btn-outline-info btn-update-status" 
                                            data-enquiry-id="<?php echo $enquiry['id']; ?>"
                                            title="Update Status">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    
                                    <button type="button" 
                                            class="btn btn-outline-danger btn-delete" 
                                            data-url="product_enquiries.php?action=delete&id=<?php echo $enquiry['id']; ?><?php echo $productId ? '&product_id=' . $productId : ''; ?>"
                                            data-name="enquiry from <?php echo htmlspecialchars($enquiry['name']); ?>"
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
            <nav aria-label="Enquiries pagination">
                <ul class="pagination justify-content-center">
                    <?php if ($page > 1): ?>
                        <li class="page-item">
                            <a class="page-link" href="?page=<?php echo $page - 1; ?>&search=<?php echo urlencode($search); ?>&status=<?php echo urlencode($status); ?><?php echo $productId ? '&product_id=' . $productId : ''; ?>">
                                <i class="fas fa-chevron-left"></i> Previous
                            </a>
                        </li>
                    <?php endif; ?>
                    
                    <?php for ($i = max(1, $page - 2); $i <= min($totalPages, $page + 2); $i++): ?>
                        <li class="page-item <?php echo $i === $page ? 'active' : ''; ?>">
                            <a class="page-link" href="?page=<?php echo $i; ?>&search=<?php echo urlencode($search); ?>&status=<?php echo urlencode($status); ?><?php echo $productId ? '&product_id=' . $productId : ''; ?>">
                                <?php echo $i; ?>
                            </a>
                        </li>
                    <?php endfor; ?>
                    
                    <?php if ($page < $totalPages): ?>
                        <li class="page-item">
                            <a class="page-link" href="?page=<?php echo $page + 1; ?>&search=<?php echo urlencode($search); ?>&status=<?php echo urlencode($status); ?><?php echo $productId ? '&product_id=' . $productId : ''; ?>">
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

<!-- View Enquiry Modal -->
<div class="modal" id="viewEnquiryModal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Enquiry Details</h5>
                <button type="button" class="modal-close" data-dismiss="modal">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="modal-body" id="enquiryDetails">
                <!-- Content loaded via AJAX -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- Update Status Modal -->
<div class="modal" id="updateStatusModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Update Enquiry Status</h5>
                <button type="button" class="modal-close" data-dismiss="modal">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <form id="updateStatusForm" method="POST">
                <input type="hidden" name="csrf_token" value="<?php echo generateCSRFToken(); ?>">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="status_select" class="form-label">Status</label>
                        <select id="status_select" name="status" class="form-control" required>
                            <option value="new">New</option>
                            <option value="contacted">Contacted</option>
                            <option value="quoted">Quoted</option>
                            <option value="closed">Closed</option>
                        </select>
                    </div>
                    
                    <?php if (!empty($users)): ?>
                    <div class="form-group">
                        <label for="assigned_to_select" class="form-label">Assign To</label>
                        <select id="assigned_to_select" name="assigned_to" class="form-control">
                            <option value="">Unassigned</option>
                            <?php foreach ($users as $user): ?>
                                <option value="<?php echo $user['id']; ?>">
                                    <?php echo htmlspecialchars($user['first_name'] . ' ' . $user['last_name'] . ' (' . $user['username'] . ')'); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <?php else: ?>
                    <div class="form-group">
                        <label class="form-label">Assignment</label>
                        <p class="form-control-static text-muted">User management not available</p>
                        <input type="hidden" name="assigned_to" value="">
                    </div>
                    <?php endif; ?>
                    
                    <div class="form-group">
                        <label for="notes_textarea" class="form-label">Notes</label>
                        <textarea id="notes_textarea" name="notes" class="form-control" rows="3" 
                                  placeholder="Add any notes about this enquiry..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Update Status</button>
                </div>
            </form>
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
                <p>Are you sure you want to delete this enquiry? This action cannot be undone.</p>
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
    // Handle view enquiry buttons
    document.querySelectorAll('.btn-view-enquiry').forEach(function(btn) {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            
            const enquiryId = this.dataset.enquiryId;
            const modal = document.getElementById('viewEnquiryModal');
            const detailsDiv = document.getElementById('enquiryDetails');
            
            // Show loading
            detailsDiv.innerHTML = '<div class="text-center"><i class="fas fa-spinner fa-spin"></i> Loading...</div>';
            modal.classList.add('show');
            
            // Load enquiry details via AJAX
            fetch(`ajax/get_enquiry_details.php?id=${enquiryId}`)
                .then(response => response.text())
                .then(html => {
                    detailsDiv.innerHTML = html;
                })
                .catch(error => {
                    detailsDiv.innerHTML = '<div class="alert alert-danger">Error loading enquiry details.</div>';
                });
        });
    });
    
    // Handle update status buttons
    document.querySelectorAll('.btn-update-status').forEach(function(btn) {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            
            const enquiryId = this.dataset.enquiryId;
            const modal = document.getElementById('updateStatusModal');
            const form = document.getElementById('updateStatusForm');
            
            // Update form action
            form.action = `product_enquiries.php?action=update_status&id=${enquiryId}<?php echo $productId ? '&product_id=' . $productId : ''; ?>`;
            
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
                `Are you sure you want to delete this ${name}? This action cannot be undone.`;
            
            // Update form action
            form.action = url;
            
            // Show modal
            modal.classList.add('show');
        });
    });
});
</script>

<?php include 'includes/footer.php'; ?>
