<?php
/**
 * Training Programs Management for FSMC Admin System
 * Created: 2025-01-22
 */

require_once 'config/config.php';

// Set page variables
$pageTitle = 'Training Programs Management';
$pageIcon = 'fas fa-graduation-cap';
$pageDescription = 'Manage your training courses and programs';
$pageActions = '<a href="training_edit.php" class="btn btn-primary"><i class="fas fa-plus"></i> Add New Program</a>';

// Handle actions
$action = $_GET['action'] ?? '';
$trainingId = $_GET['id'] ?? 0;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verifyCSRFToken($_POST['csrf_token'] ?? '')) {
        setErrorMessage('Invalid security token.');
        redirect('training.php');
    }
    
    if ($action === 'delete' && $trainingId) {
        // Delete training program
        $training = dbGetRow("SELECT title FROM training_programs WHERE id = ?", [$trainingId]);
        if ($training) {
            if (dbExecute("DELETE FROM training_programs WHERE id = ?", [$trainingId])) {
                logActivity('Training Program Deleted', 'training_programs', $trainingId, $training);
                setSuccessMessage('Training program "' . $training['title'] . '" has been deleted successfully.');
            } else {
                setErrorMessage('Failed to delete training program.');
            }
        }
        redirect('training.php');
    }
    
    if ($action === 'toggle_status' && $trainingId) {
        // Toggle training status
        $training = dbGetRow("SELECT title, status FROM training_programs WHERE id = ?", [$trainingId]);
        if ($training) {
            $newStatus = $training['status'] === 'active' ? 'inactive' : 'active';
            if (dbExecute("UPDATE training_programs SET status = ?, updated_at = NOW() WHERE id = ?", [$newStatus, $trainingId])) {
                logActivity('Training Program Status Changed', 'training_programs', $trainingId, 
                    ['status' => $training['status']], 
                    ['status' => $newStatus]
                );
                setSuccessMessage('Training program status updated successfully.');
            } else {
                setErrorMessage('Failed to update training program status.');
            }
        }
        redirect('training.php');
    }
}

// Get training programs with pagination
$page = max(1, intval($_GET['page'] ?? 1));
$limit = DEFAULT_PAGE_SIZE;
$offset = ($page - 1) * $limit;

$search = sanitize($_GET['search'] ?? '');
$level = sanitize($_GET['level'] ?? '');
$status = sanitize($_GET['status'] ?? '');

// Build query
$whereConditions = [];
$params = [];

if ($search) {
    $whereConditions[] = "(title LIKE ? OR description LIKE ? OR instructor LIKE ?)";
    $params[] = "%$search%";
    $params[] = "%$search%";
    $params[] = "%$search%";
}

if ($level) {
    $whereConditions[] = "level = ?";
    $params[] = $level;
}

if ($status) {
    $whereConditions[] = "status = ?";
    $params[] = $status;
}

$whereClause = !empty($whereConditions) ? 'WHERE ' . implode(' AND ', $whereConditions) : '';

// Get total count
$totalQuery = "SELECT COUNT(*) as total FROM training_programs $whereClause";
$totalResult = dbGetRow($totalQuery, $params);
$totalPrograms = $totalResult['total'] ?? 0;
$totalPages = ceil($totalPrograms / $limit);

// Get training programs
$programsQuery = "SELECT id, title, slug, short_description, level, instructor, duration, price, 
                         max_participants, start_date, end_date, schedule, status, sort_order, 
                         created_at, updated_at 
                  FROM training_programs $whereClause 
                  ORDER BY start_date DESC, sort_order ASC, created_at DESC 
                  LIMIT $limit OFFSET $offset";
$programs = dbGetRows($programsQuery, $params);

// Get levels for filter
$levels = ['beginner', 'intermediate', 'advanced', 'professional'];

include 'includes/header.php';
?>

<!-- Filters -->
<div class="card mb-3">
    <div class="card-body">
        <form method="GET" class="row align-items-end">
            <div class="col-md-4">
                <label for="search" class="form-label">Search Programs</label>
                <input type="text" id="search" name="search" class="form-control" 
                       value="<?php echo htmlspecialchars($search); ?>" 
                       placeholder="Search by title, description, or instructor...">
            </div>
            <div class="col-md-2">
                <label for="level" class="form-label">Level</label>
                <select id="level" name="level" class="form-control">
                    <option value="">All Levels</option>
                    <?php foreach ($levels as $lvl): ?>
                        <option value="<?php echo $lvl; ?>" <?php echo $level === $lvl ? 'selected' : ''; ?>>
                            <?php echo ucfirst($lvl); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-2">
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
                <a href="training.php" class="btn btn-secondary">
                    <i class="fas fa-times"></i> Clear
                </a>
            </div>
        </form>
    </div>
</div>

<!-- Training Programs Table -->
<div class="card">
    <div class="card-header">
        <h3 class="card-title">
            <i class="fas fa-list"></i>
            Training Programs (<?php echo number_format($totalPrograms); ?> total)
        </h3>
    </div>
    <div class="card-body">
        <?php if (empty($programs)): ?>
            <div class="text-center py-4">
                <i class="fas fa-graduation-cap fa-3x text-muted mb-3"></i>
                <h5 class="text-muted">No training programs found</h5>
                <p class="text-muted">Start by adding your first training program.</p>
                <a href="training_edit.php" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Add New Program
                </a>
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Program</th>
                            <th>Level</th>
                            <th>Instructor</th>
                            <th>Duration</th>
                            <th>Price</th>
                            <th>Dates</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($programs as $program): ?>
                        <tr>
                            <td>
                                <div>
                                    <strong><?php echo htmlspecialchars($program['title']); ?></strong><br>
                                    <small class="text-muted">
                                        <?php 
                                        $description = $program['short_description'] ?: $program['description'] ?? '';
                                        echo htmlspecialchars(substr($description, 0, 80)) . (strlen($description) > 80 ? '...' : ''); 
                                        ?>
                                    </small>
                                </div>
                            </td>
                            <td>
                                <span class="badge badge-<?php 
                                    echo $program['level'] === 'beginner' ? 'success' : 
                                        ($program['level'] === 'intermediate' ? 'warning' : 
                                        ($program['level'] === 'advanced' ? 'info' : 'primary')); 
                                ?>">
                                    <?php echo ucfirst($program['level']); ?>
                                </span>
                            </td>
                            <td><?php echo htmlspecialchars($program['instructor'] ?? 'TBA'); ?></td>
                            <td><?php echo htmlspecialchars($program['duration'] ?? 'N/A'); ?></td>
                            <td>
                                <?php if ($program['price']): ?>
                                    <strong>RWF <?php echo number_format($program['price'], 0); ?></strong>
                                <?php else: ?>
                                    <span class="text-muted">Free</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if ($program['start_date']): ?>
                                    <small>
                                        <?php echo date('M j', strtotime($program['start_date'])); ?>
                                        <?php if ($program['end_date'] && $program['end_date'] !== $program['start_date']): ?>
                                            - <?php echo date('M j, Y', strtotime($program['end_date'])); ?>
                                        <?php else: ?>
                                            <?php echo date(', Y', strtotime($program['start_date'])); ?>
                                        <?php endif; ?>
                                    </small>
                                <?php else: ?>
                                    <span class="text-muted">TBA</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <span class="badge badge-<?php 
                                    echo $program['status'] === 'active' ? 'success' : 
                                        ($program['status'] === 'inactive' ? 'secondary' : 'warning'); 
                                ?>">
                                    <?php echo ucfirst($program['status']); ?>
                                </span>
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <a href="training_edit.php?id=<?php echo $program['id']; ?>" 
                                       class="btn btn-outline-primary" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    
                                    <form method="POST" style="display: inline;" 
                                          action="training.php?action=toggle_status&id=<?php echo $program['id']; ?>">
                                        <input type="hidden" name="csrf_token" value="<?php echo generateCSRFToken(); ?>">
                                        <button type="submit" 
                                                class="btn btn-outline-<?php echo $program['status'] === 'active' ? 'warning' : 'success'; ?>" 
                                                title="<?php echo $program['status'] === 'active' ? 'Deactivate' : 'Activate'; ?>">
                                            <i class="fas fa-<?php echo $program['status'] === 'active' ? 'pause' : 'play'; ?>"></i>
                                        </button>
                                    </form>
                                    
                                    <button type="button" 
                                            class="btn btn-outline-danger btn-delete" 
                                            data-url="training.php?action=delete&id=<?php echo $program['id']; ?>"
                                            data-name="<?php echo htmlspecialchars($program['title']); ?>"
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
            <nav aria-label="Training programs pagination">
                <ul class="pagination justify-content-center">
                    <?php if ($page > 1): ?>
                        <li class="page-item">
                            <a class="page-link" href="?page=<?php echo $page - 1; ?>&search=<?php echo urlencode($search); ?>&level=<?php echo urlencode($level); ?>&status=<?php echo urlencode($status); ?>">
                                <i class="fas fa-chevron-left"></i> Previous
                            </a>
                        </li>
                    <?php endif; ?>
                    
                    <?php for ($i = max(1, $page - 2); $i <= min($totalPages, $page + 2); $i++): ?>
                        <li class="page-item <?php echo $i === $page ? 'active' : ''; ?>">
                            <a class="page-link" href="?page=<?php echo $i; ?>&search=<?php echo urlencode($search); ?>&level=<?php echo urlencode($level); ?>&status=<?php echo urlencode($status); ?>">
                                <?php echo $i; ?>
                            </a>
                        </li>
                    <?php endfor; ?>
                    
                    <?php if ($page < $totalPages): ?>
                        <li class="page-item">
                            <a class="page-link" href="?page=<?php echo $page + 1; ?>&search=<?php echo urlencode($search); ?>&level=<?php echo urlencode($level); ?>&status=<?php echo urlencode($status); ?>">
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
                <p>Are you sure you want to delete this training program? This action cannot be undone.</p>
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
