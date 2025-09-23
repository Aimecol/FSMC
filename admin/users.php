<?php
/**
 * Users Management for FSMC Admin System
 * Created: 2025-01-22
 */

require_once 'config/config.php';

// Check permissions
if (!hasPermission('delete')) {
    setErrorMessage('Access denied. You do not have permission to manage users.');
    redirect('index.php');
}

// Set page variables
$pageTitle = 'Users Management';
$pageIcon = 'fas fa-users';
$pageDescription = 'Manage admin users and permissions';
$pageActions = '<a href="user_edit.php" class="btn btn-primary"><i class="fas fa-plus"></i> Add New User</a>';

// Handle actions
$action = $_GET['action'] ?? '';
$userId = $_GET['id'] ?? 0;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verifyCSRFToken($_POST['csrf_token'] ?? '')) {
        setErrorMessage('Invalid security token.');
        redirect('users.php');
    }
    
    if ($action === 'delete' && $userId) {
        // Prevent deleting own account
        if ($userId == getCurrentUserId()) {
            setErrorMessage('You cannot delete your own account.');
            redirect('users.php');
        }
        
        // Delete user
        $user = dbGetRow("SELECT username, full_name FROM admin_users WHERE id = ?", [$userId]);
        if ($user) {
            if (dbExecute("DELETE FROM admin_users WHERE id = ?", [$userId])) {
                logActivity('User Deleted', 'admin_users', $userId, $user);
                setSuccessMessage('User "' . $user['username'] . '" has been deleted successfully.');
            } else {
                setErrorMessage('Failed to delete user.');
            }
        }
        redirect('users.php');
    }
    
    if ($action === 'toggle_status' && $userId) {
        // Prevent disabling own account
        if ($userId == getCurrentUserId()) {
            setErrorMessage('You cannot disable your own account.');
            redirect('users.php');
        }
        
        // Toggle user status
        $user = dbGetRow("SELECT username, status FROM admin_users WHERE id = ?", [$userId]);
        if ($user) {
            $newStatus = $user['status'] === 'active' ? 'inactive' : 'active';
            if (dbExecute("UPDATE admin_users SET status = ?, updated_at = NOW() WHERE id = ?", [$newStatus, $userId])) {
                logActivity('User Status Changed', 'admin_users', $userId, 
                    ['status' => $user['status']], 
                    ['status' => $newStatus]
                );
                setSuccessMessage('User status updated successfully.');
            } else {
                setErrorMessage('Failed to update user status.');
            }
        }
        redirect('users.php');
    }
}

// Get users with pagination
$page = max(1, intval($_GET['page'] ?? 1));
$limit = DEFAULT_PAGE_SIZE;
$offset = ($page - 1) * $limit;

$search = sanitize($_GET['search'] ?? '');
$role = sanitize($_GET['role'] ?? '');
$status = sanitize($_GET['status'] ?? '');

// Build query
$whereConditions = [];
$params = [];

if ($search) {
    $whereConditions[] = "(username LIKE ? OR full_name LIKE ? OR email LIKE ?)";
    $params[] = "%$search%";
    $params[] = "%$search%";
    $params[] = "%$search%";
}

if ($role) {
    $whereConditions[] = "role = ?";
    $params[] = $role;
}

if ($status) {
    $whereConditions[] = "status = ?";
    $params[] = $status;
}

$whereClause = !empty($whereConditions) ? 'WHERE ' . implode(' AND ', $whereConditions) : '';

// Get total count
$totalQuery = "SELECT COUNT(*) as total FROM admin_users $whereClause";
$totalResult = dbGetRow($totalQuery, $params);
$totalUsers = $totalResult['total'] ?? 0;
$totalPages = ceil($totalUsers / $limit);

// Get users
$usersQuery = "SELECT id, username, email, full_name, role, status, last_login, created_at, updated_at 
               FROM admin_users $whereClause 
               ORDER BY created_at DESC 
               LIMIT $limit OFFSET $offset";
$users = dbGetRows($usersQuery, $params);

// Get roles and statuses for filters
$roles = ['super_admin', 'admin', 'editor'];
$statuses = ['active', 'inactive'];

include 'includes/header.php';
?>

<!-- Filters -->
<div class="card mb-3">
    <div class="card-body">
        <form method="GET" class="row align-items-end">
            <div class="col-md-4">
                <label for="search" class="form-label">Search Users</label>
                <input type="text" id="search" name="search" class="form-control" 
                       value="<?php echo htmlspecialchars($search); ?>" 
                       placeholder="Search by username, name, or email...">
            </div>
            <div class="col-md-2">
                <label for="role" class="form-label">Role</label>
                <select id="role" name="role" class="form-control">
                    <option value="">All Roles</option>
                    <?php foreach ($roles as $r): ?>
                        <option value="<?php echo $r; ?>" <?php echo $role === $r ? 'selected' : ''; ?>>
                            <?php echo ucfirst(str_replace('_', ' ', $r)); ?>
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
                <a href="users.php" class="btn btn-secondary">
                    <i class="fas fa-times"></i> Clear
                </a>
            </div>
        </form>
    </div>
</div>

<!-- Users Table -->
<div class="card">
    <div class="card-header">
        <h3 class="card-title">
            <i class="fas fa-list"></i>
            Admin Users (<?php echo number_format($totalUsers); ?> total)
        </h3>
    </div>
    <div class="card-body">
        <?php if (empty($users)): ?>
            <div class="text-center py-4">
                <i class="fas fa-users fa-3x text-muted mb-3"></i>
                <h5 class="text-muted">No users found</h5>
                <p class="text-muted">No users match your search criteria.</p>
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>User</th>
                            <th>Role</th>
                            <th>Status</th>
                            <th>Last Login</th>
                            <th>Created</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($users as $user): ?>
                        <tr>
                            <td>
                                <div>
                                    <strong><?php echo htmlspecialchars($user['full_name']); ?></strong><br>
                                    <small class="text-muted">
                                        <i class="fas fa-user"></i> <?php echo htmlspecialchars($user['username']); ?>
                                    </small><br>
                                    <small class="text-muted">
                                        <i class="fas fa-envelope"></i> <?php echo htmlspecialchars($user['email']); ?>
                                    </small>
                                </div>
                            </td>
                            <td>
                                <span class="badge badge-<?php 
                                    echo $user['role'] === 'super_admin' ? 'danger' : 
                                        ($user['role'] === 'admin' ? 'primary' : 'info'); 
                                ?>">
                                    <?php echo ucfirst(str_replace('_', ' ', $user['role'])); ?>
                                </span>
                            </td>
                            <td>
                                <span class="badge badge-<?php echo $user['status'] === 'active' ? 'success' : 'secondary'; ?>">
                                    <?php echo ucfirst($user['status']); ?>
                                </span>
                            </td>
                            <td>
                                <?php if ($user['last_login']): ?>
                                    <small>
                                        <?php echo date('M j, Y', strtotime($user['last_login'])); ?><br>
                                        <?php echo date('H:i', strtotime($user['last_login'])); ?>
                                    </small>
                                <?php else: ?>
                                    <span class="text-muted">Never</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <small><?php echo date('M j, Y', strtotime($user['created_at'])); ?></small>
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <a href="user_edit.php?id=<?php echo $user['id']; ?>" 
                                       class="btn btn-outline-primary" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    
                                    <?php if ($user['id'] != getCurrentUserId()): ?>
                                        <form method="POST" style="display: inline;" 
                                              action="users.php?action=toggle_status&id=<?php echo $user['id']; ?>">
                                            <input type="hidden" name="csrf_token" value="<?php echo generateCSRFToken(); ?>">
                                            <button type="submit" 
                                                    class="btn btn-outline-<?php echo $user['status'] === 'active' ? 'warning' : 'success'; ?>" 
                                                    title="<?php echo $user['status'] === 'active' ? 'Deactivate' : 'Activate'; ?>">
                                                <i class="fas fa-<?php echo $user['status'] === 'active' ? 'pause' : 'play'; ?>"></i>
                                            </button>
                                        </form>
                                        
                                        <button type="button" 
                                                class="btn btn-outline-danger btn-delete" 
                                                data-url="users.php?action=delete&id=<?php echo $user['id']; ?>"
                                                data-name="<?php echo htmlspecialchars($user['username']); ?>"
                                                title="Delete">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    <?php else: ?>
                                        <span class="badge badge-info">Current User</span>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            <?php if ($totalPages > 1): ?>
            <nav aria-label="Users pagination">
                <ul class="pagination justify-content-center">
                    <?php if ($page > 1): ?>
                        <li class="page-item">
                            <a class="page-link" href="?page=<?php echo $page - 1; ?>&search=<?php echo urlencode($search); ?>&role=<?php echo urlencode($role); ?>&status=<?php echo urlencode($status); ?>">
                                <i class="fas fa-chevron-left"></i> Previous
                            </a>
                        </li>
                    <?php endif; ?>
                    
                    <?php for ($i = max(1, $page - 2); $i <= min($totalPages, $page + 2); $i++): ?>
                        <li class="page-item <?php echo $i === $page ? 'active' : ''; ?>">
                            <a class="page-link" href="?page=<?php echo $i; ?>&search=<?php echo urlencode($search); ?>&role=<?php echo urlencode($role); ?>&status=<?php echo urlencode($status); ?>">
                                <?php echo $i; ?>
                            </a>
                        </li>
                    <?php endfor; ?>
                    
                    <?php if ($page < $totalPages): ?>
                        <li class="page-item">
                            <a class="page-link" href="?page=<?php echo $page + 1; ?>&search=<?php echo urlencode($search); ?>&role=<?php echo urlencode($role); ?>&status=<?php echo urlencode($status); ?>">
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
                <p>Are you sure you want to delete this user? This action cannot be undone.</p>
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
                `Are you sure you want to delete user "${name}"? This action cannot be undone.`;
            
            // Update form action
            form.action = url;
            
            // Show modal
            modal.classList.add('show');
        });
    });
});
</script>

<?php include 'includes/footer.php'; ?>
