<?php
/**
 * Activity Logs for FSMC Admin System
 * Created: 2025-01-22
 */

require_once 'config/config.php';

// Check permissions
if (!hasPermission('delete')) {
    setErrorMessage('Access denied. You do not have permission to view activity logs.');
    redirect('index.php');
}

// Set page variables
$pageTitle = 'Activity Logs';
$pageIcon = 'fas fa-list-alt';
$pageDescription = 'View system activity and audit trail';

// Handle actions
$action = $_GET['action'] ?? '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $action === 'clear_old') {
    if (!verifyCSRFToken($_POST['csrf_token'] ?? '')) {
        setErrorMessage('Invalid security token.');
        redirect('logs.php');
    }
    
    $days = intval($_POST['days'] ?? 30);
    $cutoffDate = date('Y-m-d H:i:s', strtotime("-$days days"));
    
    $deletedCount = dbExecute("DELETE FROM activity_logs WHERE created_at < ?", [$cutoffDate]);
    if ($deletedCount !== false) {
        logActivity('Activity Logs Cleared', 'activity_logs', 0, null, ['days' => $days, 'cutoff_date' => $cutoffDate]);
        setSuccessMessage("Cleared activity logs older than $days days.");
    } else {
        setErrorMessage('Failed to clear old logs.');
    }
    
    redirect('logs.php');
}

// Get logs with pagination
$page = max(1, intval($_GET['page'] ?? 1));
$limit = 50; // Show more logs per page
$offset = ($page - 1) * $limit;

$search = sanitize($_GET['search'] ?? '');
$action_filter = sanitize($_GET['action_filter'] ?? '');
$table_filter = sanitize($_GET['table_filter'] ?? '');
$user_filter = sanitize($_GET['user_filter'] ?? '');
$date_from = sanitize($_GET['date_from'] ?? '');
$date_to = sanitize($_GET['date_to'] ?? '');

// Build query
$whereConditions = [];
$params = [];

if ($search) {
    $whereConditions[] = "(l.action LIKE ? OR l.table_name LIKE ? OR l.description LIKE ?)";
    $params[] = "%$search%";
    $params[] = "%$search%";
    $params[] = "%$search%";
}

if ($action_filter) {
    $whereConditions[] = "l.action LIKE ?";
    $params[] = "%$action_filter%";
}

if ($table_filter) {
    $whereConditions[] = "l.table_name = ?";
    $params[] = $table_filter;
}

if ($user_filter) {
    $whereConditions[] = "l.user_id = ?";
    $params[] = $user_filter;
}

if ($date_from) {
    $whereConditions[] = "DATE(l.created_at) >= ?";
    $params[] = $date_from;
}

if ($date_to) {
    $whereConditions[] = "DATE(l.created_at) <= ?";
    $params[] = $date_to;
}

$whereClause = !empty($whereConditions) ? 'WHERE ' . implode(' AND ', $whereConditions) : '';

// Get total count
$totalQuery = "SELECT COUNT(*) as total FROM activity_logs l $whereClause";
$totalResult = dbGetRow($totalQuery, $params);
$totalLogs = $totalResult['total'] ?? 0;
$totalPages = ceil($totalLogs / $limit);

// Get logs
$logsQuery = "SELECT l.*, u.username, u.full_name 
              FROM activity_logs l 
              LEFT JOIN admin_users u ON l.user_id = u.id 
              $whereClause 
              ORDER BY l.created_at DESC 
              LIMIT $limit OFFSET $offset";
$logs = dbGetRows($logsQuery, $params);

// Get filter options
$actions = dbGetRows("SELECT DISTINCT action FROM activity_logs ORDER BY action");
$tables = dbGetRows("SELECT DISTINCT table_name FROM activity_logs WHERE table_name IS NOT NULL ORDER BY table_name");
$users = dbGetRows("SELECT DISTINCT u.id, u.username, u.full_name FROM activity_logs l JOIN admin_users u ON l.user_id = u.id ORDER BY u.full_name");

// Get statistics
$stats = [
    'total_logs' => dbGetValue("SELECT COUNT(*) FROM activity_logs"),
    'today_logs' => dbGetValue("SELECT COUNT(*) FROM activity_logs WHERE DATE(created_at) = CURDATE()"),
    'week_logs' => dbGetValue("SELECT COUNT(*) FROM activity_logs WHERE created_at >= DATE_SUB(NOW(), INTERVAL 7 DAY)"),
    'month_logs' => dbGetValue("SELECT COUNT(*) FROM activity_logs WHERE created_at >= DATE_SUB(NOW(), INTERVAL 30 DAY)")
];

include 'includes/header.php';
?>

<!-- Statistics -->
<div class="row mb-4">
    <div class="col-md-3">
        <div class="stats-card">
            <div class="stats-icon bg-primary">
                <i class="fas fa-list"></i>
            </div>
            <div class="stats-content">
                <h3><?php echo number_format($stats['total_logs']); ?></h3>
                <p>Total Logs</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stats-card">
            <div class="stats-icon bg-success">
                <i class="fas fa-calendar-day"></i>
            </div>
            <div class="stats-content">
                <h3><?php echo number_format($stats['today_logs']); ?></h3>
                <p>Today</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stats-card">
            <div class="stats-icon bg-info">
                <i class="fas fa-calendar-week"></i>
            </div>
            <div class="stats-content">
                <h3><?php echo number_format($stats['week_logs']); ?></h3>
                <p>This Week</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stats-card">
            <div class="stats-icon bg-warning">
                <i class="fas fa-calendar-alt"></i>
            </div>
            <div class="stats-content">
                <h3><?php echo number_format($stats['month_logs']); ?></h3>
                <p>This Month</p>
            </div>
        </div>
    </div>
</div>

<!-- Filters -->
<div class="card mb-3">
    <div class="card-body">
        <form method="GET" class="row align-items-end">
            <div class="col-md-3">
                <label for="search" class="form-label">Search</label>
                <input type="text" id="search" name="search" class="form-control" 
                       value="<?php echo htmlspecialchars($search); ?>" 
                       placeholder="Search logs...">
            </div>
            <div class="col-md-2">
                <label for="action_filter" class="form-label">Action</label>
                <select id="action_filter" name="action_filter" class="form-control">
                    <option value="">All Actions</option>
                    <?php foreach ($actions as $action): ?>
                        <option value="<?php echo htmlspecialchars($action['action']); ?>" 
                                <?php echo $action_filter === $action['action'] ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($action['action']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-2">
                <label for="table_filter" class="form-label">Table</label>
                <select id="table_filter" name="table_filter" class="form-control">
                    <option value="">All Tables</option>
                    <?php foreach ($tables as $table): ?>
                        <option value="<?php echo htmlspecialchars($table['table_name']); ?>" 
                                <?php echo $table_filter === $table['table_name'] ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($table['table_name']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-2">
                <label for="user_filter" class="form-label">User</label>
                <select id="user_filter" name="user_filter" class="form-control">
                    <option value="">All Users</option>
                    <?php foreach ($users as $user): ?>
                        <option value="<?php echo $user['id']; ?>" 
                                <?php echo $user_filter == $user['id'] ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($user['full_name']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-3">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-search"></i> Search
                </button>
                <a href="logs.php" class="btn btn-secondary">
                    <i class="fas fa-times"></i> Clear
                </a>
            </div>
        </form>
        
        <div class="row mt-3">
            <div class="col-md-3">
                <label for="date_from" class="form-label">Date From</label>
                <input type="date" id="date_from" name="date_from" class="form-control" 
                       value="<?php echo htmlspecialchars($date_from); ?>">
            </div>
            <div class="col-md-3">
                <label for="date_to" class="form-label">Date To</label>
                <input type="date" id="date_to" name="date_to" class="form-control" 
                       value="<?php echo htmlspecialchars($date_to); ?>">
            </div>
            <div class="col-md-6 d-flex align-items-end">
                <button type="button" class="btn btn-warning" data-toggle="modal" data-target="#clearLogsModal">
                    <i class="fas fa-trash"></i> Clear Old Logs
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Logs Table -->
<div class="card">
    <div class="card-header">
        <h3 class="card-title">
            <i class="fas fa-list-alt"></i>
            Activity Logs (<?php echo number_format($totalLogs); ?> total)
        </h3>
    </div>
    <div class="card-body">
        <?php if (empty($logs)): ?>
            <div class="text-center py-4">
                <i class="fas fa-list-alt fa-3x text-muted mb-3"></i>
                <h5 class="text-muted">No logs found</h5>
                <p class="text-muted">No activity logs match your search criteria.</p>
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-striped table-sm">
                    <thead>
                        <tr>
                            <th>Date/Time</th>
                            <th>User</th>
                            <th>Action</th>
                            <th>Table</th>
                            <th>Record ID</th>
                            <th>IP Address</th>
                            <th>Details</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($logs as $log): ?>
                        <tr>
                            <td>
                                <small>
                                    <?php echo date('M j, Y', strtotime($log['created_at'])); ?><br>
                                    <?php echo date('H:i:s', strtotime($log['created_at'])); ?>
                                </small>
                            </td>
                            <td>
                                <?php if ($log['username']): ?>
                                    <small>
                                        <strong><?php echo htmlspecialchars($log['full_name']); ?></strong><br>
                                        <span class="text-muted"><?php echo htmlspecialchars($log['username']); ?></span>
                                    </small>
                                <?php else: ?>
                                    <span class="text-muted">System</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <span class="badge badge-<?php 
                                    echo strpos($log['action'], 'Delete') !== false ? 'danger' : 
                                        (strpos($log['action'], 'Create') !== false || strpos($log['action'], 'Add') !== false ? 'success' : 
                                        (strpos($log['action'], 'Update') !== false || strpos($log['action'], 'Edit') !== false ? 'warning' : 'info')); 
                                ?>">
                                    <?php echo htmlspecialchars($log['action']); ?>
                                </span>
                            </td>
                            <td>
                                <?php if ($log['table_name']): ?>
                                    <code><?php echo htmlspecialchars($log['table_name']); ?></code>
                                <?php else: ?>
                                    <span class="text-muted">-</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if ($log['record_id']): ?>
                                    <code><?php echo $log['record_id']; ?></code>
                                <?php else: ?>
                                    <span class="text-muted">-</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <small><?php echo htmlspecialchars($log['ip_address'] ?? '-'); ?></small>
                            </td>
                            <td>
                                <?php if ($log['description']): ?>
                                    <button type="button" class="btn btn-sm btn-outline-info btn-details" 
                                            data-description="<?php echo htmlspecialchars($log['description']); ?>"
                                            data-old-data="<?php echo htmlspecialchars($log['old_data'] ?? ''); ?>"
                                            data-new-data="<?php echo htmlspecialchars($log['new_data'] ?? ''); ?>">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                <?php else: ?>
                                    <span class="text-muted">-</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            <?php if ($totalPages > 1): ?>
            <nav aria-label="Logs pagination">
                <ul class="pagination justify-content-center">
                    <?php if ($page > 1): ?>
                        <li class="page-item">
                            <a class="page-link" href="?page=<?php echo $page - 1; ?>&search=<?php echo urlencode($search); ?>&action_filter=<?php echo urlencode($action_filter); ?>&table_filter=<?php echo urlencode($table_filter); ?>&user_filter=<?php echo urlencode($user_filter); ?>&date_from=<?php echo urlencode($date_from); ?>&date_to=<?php echo urlencode($date_to); ?>">
                                <i class="fas fa-chevron-left"></i> Previous
                            </a>
                        </li>
                    <?php endif; ?>
                    
                    <?php for ($i = max(1, $page - 2); $i <= min($totalPages, $page + 2); $i++): ?>
                        <li class="page-item <?php echo $i === $page ? 'active' : ''; ?>">
                            <a class="page-link" href="?page=<?php echo $i; ?>&search=<?php echo urlencode($search); ?>&action_filter=<?php echo urlencode($action_filter); ?>&table_filter=<?php echo urlencode($table_filter); ?>&user_filter=<?php echo urlencode($user_filter); ?>&date_from=<?php echo urlencode($date_from); ?>&date_to=<?php echo urlencode($date_to); ?>">
                                <?php echo $i; ?>
                            </a>
                        </li>
                    <?php endfor; ?>
                    
                    <?php if ($page < $totalPages): ?>
                        <li class="page-item">
                            <a class="page-link" href="?page=<?php echo $page + 1; ?>&search=<?php echo urlencode($search); ?>&action_filter=<?php echo urlencode($action_filter); ?>&table_filter=<?php echo urlencode($table_filter); ?>&user_filter=<?php echo urlencode($user_filter); ?>&date_from=<?php echo urlencode($date_from); ?>&date_to=<?php echo urlencode($date_to); ?>">
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

<!-- Clear Logs Modal -->
<div class="modal" id="clearLogsModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Clear Old Logs</h5>
                <button type="button" class="modal-close" data-dismiss="modal">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <form method="POST" action="logs.php?action=clear_old">
                <input type="hidden" name="csrf_token" value="<?php echo generateCSRFToken(); ?>">
                
                <div class="modal-body">
                    <p>Delete activity logs older than:</p>
                    <div class="form-group">
                        <label for="days" class="form-label">Days</label>
                        <select id="days" name="days" class="form-control">
                            <option value="30">30 days</option>
                            <option value="60">60 days</option>
                            <option value="90">90 days</option>
                            <option value="180">6 months</option>
                            <option value="365">1 year</option>
                        </select>
                    </div>
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle"></i>
                        This action cannot be undone. Old logs will be permanently deleted.
                    </div>
                </div>
                
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-warning">Clear Logs</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Log Details Modal -->
<div class="modal" id="detailsModal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Log Details</h5>
                <button type="button" class="modal-close" data-dismiss="modal">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="modal-body">
                <div id="logDescription"></div>
                <div id="logData"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Handle details buttons
    document.querySelectorAll('.btn-details').forEach(function(btn) {
        btn.addEventListener('click', function() {
            const description = this.dataset.description;
            const oldData = this.dataset.oldData;
            const newData = this.dataset.newData;
            
            const modal = document.getElementById('detailsModal');
            const descriptionDiv = document.getElementById('logDescription');
            const dataDiv = document.getElementById('logData');
            
            descriptionDiv.innerHTML = `<h6>Description:</h6><p>${description}</p>`;
            
            let dataHtml = '';
            if (oldData) {
                dataHtml += `<h6>Old Data:</h6><pre class="bg-light p-2">${oldData}</pre>`;
            }
            if (newData) {
                dataHtml += `<h6>New Data:</h6><pre class="bg-light p-2">${newData}</pre>`;
            }
            
            dataDiv.innerHTML = dataHtml;
            modal.classList.add('show');
        });
    });
});
</script>

<?php include 'includes/footer.php'; ?>
