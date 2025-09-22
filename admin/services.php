<?php
/**
 * Services Management for FSMC Admin System
 * Created: 2025-01-22
 */

require_once 'config/config.php';

// Set page variables
$pageTitle = 'Services Management';
$pageIcon = 'fas fa-concierge-bell';
$pageDescription = 'Manage your company services and offerings';
$pageActions = '<a href="service_edit.php" class="btn btn-primary"><i class="fas fa-plus"></i> Add New Service</a>';

// Handle actions
$action = $_GET['action'] ?? '';
$serviceId = $_GET['id'] ?? 0;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verifyCSRFToken($_POST['csrf_token'] ?? '')) {
        setErrorMessage('Invalid security token.');
        redirect('services.php');
    }
    
    if ($action === 'delete' && $serviceId) {
        // Delete service
        $service = dbGetRow("SELECT title FROM services WHERE id = ?", [$serviceId]);
        if ($service) {
            if (dbExecute("DELETE FROM services WHERE id = ?", [$serviceId])) {
                logActivity('Service Deleted', 'services', $serviceId, $service);
                setSuccessMessage('Service "' . $service['title'] . '" has been deleted successfully.');
            } else {
                setErrorMessage('Failed to delete service.');
            }
        }
        redirect('services.php');
    }
    
    if ($action === 'toggle_status' && $serviceId) {
        // Toggle service status
        $service = dbGetRow("SELECT title, status FROM services WHERE id = ?", [$serviceId]);
        if ($service) {
            $newStatus = $service['status'] === 'active' ? 'inactive' : 'active';
            if (dbExecute("UPDATE services SET status = ?, updated_at = NOW() WHERE id = ?", [$newStatus, $serviceId])) {
                logActivity('Service Status Changed', 'services', $serviceId, 
                    ['status' => $service['status']], 
                    ['status' => $newStatus]
                );
                setSuccessMessage('Service status updated successfully.');
            } else {
                setErrorMessage('Failed to update service status.');
            }
        }
        redirect('services.php');
    }
}

// Get services with pagination
$page = max(1, intval($_GET['page'] ?? 1));
$limit = DEFAULT_PAGE_SIZE;
$offset = ($page - 1) * $limit;

$search = sanitize($_GET['search'] ?? '');
$status = sanitize($_GET['status'] ?? '');

// Build query
$whereConditions = [];
$params = [];

if ($search) {
    $whereConditions[] = "(title LIKE ? OR description LIKE ?)";
    $params[] = "%$search%";
    $params[] = "%$search%";
}

if ($status) {
    $whereConditions[] = "status = ?";
    $params[] = $status;
}

$whereClause = !empty($whereConditions) ? 'WHERE ' . implode(' AND ', $whereConditions) : '';

// Get total count
$totalQuery = "SELECT COUNT(*) as total FROM services $whereClause";
$totalResult = dbGetRow($totalQuery, $params);
$totalServices = $totalResult['total'] ?? 0;
$totalPages = ceil($totalServices / $limit);

// Get services
$servicesQuery = "SELECT id, title, slug, short_description, icon, status, sort_order, created_at, updated_at 
                  FROM services $whereClause 
                  ORDER BY sort_order ASC, created_at DESC 
                  LIMIT $limit OFFSET $offset";
$services = dbGetRows($servicesQuery, $params);

include 'includes/header.php';
?>

<!-- Filters -->
<div class="card mb-3">
    <div class="card-body">
        <form method="GET" class="row align-items-end">
            <div class="col-md-4">
                <label for="search" class="form-label">Search Services</label>
                <input type="text" id="search" name="search" class="form-control" 
                       value="<?php echo htmlspecialchars($search); ?>" 
                       placeholder="Search by title or description...">
            </div>
            <div class="col-md-3">
                <label for="status" class="form-label">Status</label>
                <select id="status" name="status" class="form-control">
                    <option value="">All Statuses</option>
                    <option value="active" <?php echo $status === 'active' ? 'selected' : ''; ?>>Active</option>
                    <option value="inactive" <?php echo $status === 'inactive' ? 'selected' : ''; ?>>Inactive</option>
                    <option value="draft" <?php echo $status === 'draft' ? 'selected' : ''; ?>>Draft</option>
                </select>
            </div>
            <div class="col-md-3">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-search"></i> Search
                </button>
                <a href="services.php" class="btn btn-secondary">
                    <i class="fas fa-times"></i> Clear
                </a>
            </div>
        </form>
    </div>
</div>

<!-- Services Table -->
<div class="card">
    <div class="card-header">
        <h3 class="card-title">
            <i class="fas fa-list"></i>
            Services List (<?php echo number_format($totalServices); ?> total)
        </h3>
    </div>
    <div class="card-body">
        <?php if (empty($services)): ?>
            <div class="text-center py-4">
                <i class="fas fa-concierge-bell fa-3x text-muted mb-3"></i>
                <h5 class="text-muted">No services found</h5>
                <p class="text-muted">Start by adding your first service.</p>
                <a href="service_edit.php" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Add New Service
                </a>
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Service</th>
                            <th>Description</th>
                            <th>Status</th>
                            <th>Order</th>
                            <th>Created</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($services as $service): ?>
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    <?php if ($service['icon']): ?>
                                        <i class="<?php echo htmlspecialchars($service['icon']); ?> fa-2x text-primary me-3"></i>
                                    <?php endif; ?>
                                    <div>
                                        <strong><?php echo htmlspecialchars($service['title']); ?></strong><br>
                                        <small class="text-muted"><?php echo htmlspecialchars($service['slug']); ?></small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <p class="mb-0">
                                    <?php 
                                    $description = $service['short_description'] ?: $service['description'] ?? '';
                                    echo htmlspecialchars(substr($description, 0, 100)) . (strlen($description) > 100 ? '...' : ''); 
                                    ?>
                                </p>
                            </td>
                            <td>
                                <span class="badge badge-<?php 
                                    echo $service['status'] === 'active' ? 'success' : 
                                        ($service['status'] === 'inactive' ? 'secondary' : 'warning'); 
                                ?>">
                                    <?php echo ucfirst($service['status']); ?>
                                </span>
                            </td>
                            <td>
                                <span class="badge badge-info"><?php echo $service['sort_order']; ?></span>
                            </td>
                            <td>
                                <small><?php echo date('M j, Y', strtotime($service['created_at'])); ?></small>
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <a href="service_edit.php?id=<?php echo $service['id']; ?>" 
                                       class="btn btn-outline-primary" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    
                                    <form method="POST" style="display: inline;" 
                                          action="services.php?action=toggle_status&id=<?php echo $service['id']; ?>">
                                        <input type="hidden" name="csrf_token" value="<?php echo generateCSRFToken(); ?>">
                                        <button type="submit" 
                                                class="btn btn-outline-<?php echo $service['status'] === 'active' ? 'warning' : 'success'; ?>" 
                                                title="<?php echo $service['status'] === 'active' ? 'Deactivate' : 'Activate'; ?>">
                                            <i class="fas fa-<?php echo $service['status'] === 'active' ? 'pause' : 'play'; ?>"></i>
                                        </button>
                                    </form>
                                    
                                    <button type="button" 
                                            class="btn btn-outline-danger btn-delete" 
                                            data-url="services.php?action=delete&id=<?php echo $service['id']; ?>"
                                            data-name="<?php echo htmlspecialchars($service['title']); ?>"
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
            <nav aria-label="Services pagination">
                <ul class="pagination justify-content-center">
                    <?php if ($page > 1): ?>
                        <li class="page-item">
                            <a class="page-link" href="?page=<?php echo $page - 1; ?>&search=<?php echo urlencode($search); ?>&status=<?php echo urlencode($status); ?>">
                                <i class="fas fa-chevron-left"></i> Previous
                            </a>
                        </li>
                    <?php endif; ?>
                    
                    <?php for ($i = max(1, $page - 2); $i <= min($totalPages, $page + 2); $i++): ?>
                        <li class="page-item <?php echo $i === $page ? 'active' : ''; ?>">
                            <a class="page-link" href="?page=<?php echo $i; ?>&search=<?php echo urlencode($search); ?>&status=<?php echo urlencode($status); ?>">
                                <?php echo $i; ?>
                            </a>
                        </li>
                    <?php endfor; ?>
                    
                    <?php if ($page < $totalPages): ?>
                        <li class="page-item">
                            <a class="page-link" href="?page=<?php echo $page + 1; ?>&search=<?php echo urlencode($search); ?>&status=<?php echo urlencode($status); ?>">
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
                <p>Are you sure you want to delete this service? This action cannot be undone.</p>
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
                `Are you sure you want to delete "${name}"? This action cannot be undone.`;
            
            // Update form action
            form.action = url;
            
            // Show modal
            modal.classList.add('show');
        });
    });
});
</script>

<?php include 'includes/footer.php'; ?>
