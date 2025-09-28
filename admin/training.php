<?php
/**
 * Training Programs Management for FSMC Admin System
 * Created: 2025-01-22
 * Updated to match database schema
 */

require_once 'config/config.php';

// Set page variables
$pageTitle = 'Training Programs Management';
$pageIcon = 'fas fa-graduation-cap';
$pageDescription = 'Manage your training courses and programs';
$pageActions = '<a href="training_enrollments.php" class="btn btn-info me-2"><i class="fas fa-users"></i> View All Enrollments</a><a href="training_edit.php" class="btn btn-primary"><i class="fas fa-plus"></i> Add New Program</a>';

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
$category = sanitize($_GET['category'] ?? '');
$level = sanitize($_GET['level'] ?? '');
$status = sanitize($_GET['status'] ?? '');

// Build query
$whereConditions = [];
$params = [];

if ($search) {
    $whereConditions[] = "(title LIKE ? OR description LIKE ? OR instructor LIKE ? OR short_description LIKE ?)";
    $params[] = "%$search%";
    $params[] = "%$search%";
    $params[] = "%$search%";
    $params[] = "%$search%";
}

if ($category) {
    $whereConditions[] = "category = ?";
    $params[] = $category;
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

// Get training programs - Updated to match database schema
$programsQuery = "SELECT id, title, slug, description, short_description, category, level, 
                         instructor, duration, price, max_students, language, status, 
                         sort_order, image, created_at, updated_at 
                  FROM training_programs $whereClause 
                  ORDER BY sort_order ASC, created_at DESC 
                  LIMIT $limit OFFSET $offset";
$programs = dbGetRows($programsQuery, $params);

// Get categories for filter
$categoriesQuery = "SELECT DISTINCT category FROM training_programs WHERE category IS NOT NULL AND category != '' ORDER BY category";
$categoriesResult = dbGetRows($categoriesQuery);
$categories = array_column($categoriesResult, 'category');

// Get levels from database enum
$levels = ['beginner', 'intermediate', 'advanced'];

include 'includes/header.php';
?>

<!-- Filters -->
<div class="card mb-3">
    <div class="card-body">
        <form method="GET" class="row align-items-end">
            <div class="col-md-3">
                <label for="search" class="form-label">Search Programs</label>
                <input type="text" id="search" name="search" class="form-control" 
                       value="<?php echo htmlspecialchars($search); ?>" 
                       placeholder="Search by title, description, or instructor...">
            </div>
            <div class="col-md-2">
                <label for="category" class="form-label">Category</label>
                <select id="category" name="category" class="form-control">
                    <option value="">All Categories</option>
                    <?php foreach ($categories as $cat): ?>
                        <option value="<?php echo htmlspecialchars($cat); ?>" <?php echo $category === $cat ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($cat); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
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
                            <th>Category</th>
                            <th>Level</th>
                            <th>Instructor</th>
                            <th>Duration</th>
                            <th>Price</th>
                            <th>Max Students</th>
                            <th>Language</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($programs as $program): ?>
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    <?php if (!empty($program['image'])): ?>
                                        <img src="<?php echo htmlspecialchars($program['image']); ?>" 
                                             alt="<?php echo htmlspecialchars($program['title']); ?>"
                                             class="img-thumbnail me-3" style="width: 50px; height: 50px; object-fit: cover;">
                                    <?php else: ?>
                                        <div class="bg-secondary text-white d-flex align-items-center justify-content-center me-3" 
                                             style="width: 50px; height: 50px; border-radius: 0.375rem;">
                                            <i class="fas fa-graduation-cap"></i>
                                        </div>
                                    <?php endif; ?>
                                    <div>
                                        <strong><?php echo htmlspecialchars($program['title']); ?></strong><br>
                                        <small class="text-muted">
                                            <?php 
                                            $description = $program['short_description'] ?: $program['description'] ?? '';
                                            echo htmlspecialchars(substr($description, 0, 60)) . (strlen($description) > 60 ? '...' : ''); 
                                            ?>
                                        </small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <?php if ($program['category']): ?>
                                    <span class="badge badge-light">
                                        <?php echo htmlspecialchars($program['category']); ?>
                                    </span>
                                <?php else: ?>
                                    <span class="text-muted">-</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <span class="badge badge-<?php 
                                    echo $program['level'] === 'beginner' ? 'success' : 
                                        ($program['level'] === 'intermediate' ? 'warning' : 'info'); 
                                ?>">
                                    <?php echo ucfirst($program['level']); ?>
                                </span>
                            </td>
                            <td>
                                <?php echo htmlspecialchars($program['instructor'] ?? 'TBA'); ?>
                            </td>
                            <td>
                                <?php echo htmlspecialchars($program['duration'] ?? 'N/A'); ?>
                            </td>
                            <td>
                                <?php if ($program['price'] > 0): ?>
                                    <strong>RWF <?php echo number_format($program['price'], 0); ?></strong>
                                <?php else: ?>
                                    <span class="text-success"><strong>Free</strong></span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <span class="badge badge-info">
                                    <?php echo $program['max_students'] ?? 20; ?> students
                                </span>
                            </td>
                            <td>
                                <?php echo htmlspecialchars($program['language'] ?? 'English'); ?>
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
                                    
                                    <a href="training_enrollments.php?program_id=<?php echo $program['id']; ?>" 
                                       class="btn btn-outline-success" title="View Enrollments">
                                        <i class="fas fa-users"></i>
                                    </a>
                                    
                                    <a href="training_view.php?id=<?php echo $program['id']; ?>" 
                                       class="btn btn-outline-info" title="View Details">
                                        <i class="fas fa-eye"></i>
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
                            <a class="page-link" href="?page=<?php echo $page - 1; ?>&search=<?php echo urlencode($search); ?>&category=<?php echo urlencode($category); ?>&level=<?php echo urlencode($level); ?>&status=<?php echo urlencode($status); ?>">
                                <i class="fas fa-chevron-left"></i> Previous
                            </a>
                        </li>
                    <?php endif; ?>
                    
                    <?php for ($i = max(1, $page - 2); $i <= min($totalPages, $page + 2); $i++): ?>
                        <li class="page-item <?php echo $i === $page ? 'active' : ''; ?>">
                            <a class="page-link" href="?page=<?php echo $i; ?>&search=<?php echo urlencode($search); ?>&category=<?php echo urlencode($category); ?>&level=<?php echo urlencode($level); ?>&status=<?php echo urlencode($status); ?>">
                                <?php echo $i; ?>
                            </a>
                        </li>
                    <?php endfor; ?>
                    
                    <?php if ($page < $totalPages): ?>
                        <li class="page-item">
                            <a class="page-link" href="?page=<?php echo $page + 1; ?>&search=<?php echo urlencode($search); ?>&category=<?php echo urlencode($category); ?>&level=<?php echo urlencode($level); ?>&status=<?php echo urlencode($status); ?>">
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
                <div class="text-center">
                    <i class="fas fa-exclamation-triangle fa-3x text-warning mb-3"></i>
                    <p>Are you sure you want to delete this training program?</p>
                    <p><strong>This action cannot be undone.</strong></p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">
                    <i class="fas fa-times"></i> Cancel
                </button>
                <form id="deleteForm" method="POST" style="display: inline;">
                    <input type="hidden" name="csrf_token" value="<?php echo generateCSRFToken(); ?>">
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-trash"></i> Delete Program
                    </button>
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
            modal.querySelector('.modal-body p:first-of-type').innerHTML = 
                `Are you sure you want to delete "<strong>${name}</strong>"?`;
            
            // Update form action
            form.action = url;
            
            // Show modal
            modal.classList.add('show');
        });
    });
    
    // Handle modal close
    document.querySelectorAll('[data-dismiss="modal"]').forEach(function(btn) {
        btn.addEventListener('click', function() {
            document.getElementById('deleteModal').classList.remove('show');
        });
    });
    
    // Handle status toggle confirmations
    document.querySelectorAll('form[action*="toggle_status"] button').forEach(function(btn) {
        btn.addEventListener('click', function(e) {
            const isActivating = this.innerHTML.includes('play');
            const action = isActivating ? 'activate' : 'deactivate';
            
            if (!confirm(`Are you sure you want to ${action} this training program?`)) {
                e.preventDefault();
            }
        });
    });
});
</script>

<style>
.modal {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.5);
    z-index: 1000;
}

.modal.show {
    display: flex;
    align-items: center;
    justify-content: center;
}

.modal-dialog {
    max-width: 500px;
    width: 90%;
}

.modal-content {
    background: white;
    border-radius: 0.375rem;
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
}

.modal-header {
    display: flex;
    justify-content: between;
    align-items: center;
    padding: 1rem;
    border-bottom: 1px solid #dee2e6;
}

.modal-title {
    margin: 0;
    font-size: 1.25rem;
    font-weight: 500;
}

.modal-close {
    background: none;
    border: none;
    font-size: 1.5rem;
    cursor: pointer;
    padding: 0;
    margin-left: auto;
}

.modal-body {
    padding: 1rem;
}

.modal-footer {
    display: flex;
    justify-content: flex-end;
    gap: 0.5rem;
    padding: 1rem;
    border-top: 1px solid #dee2e6;
}

.table td {
    vertical-align: middle;
}

.img-thumbnail {
    border-radius: 0.375rem;
}

.opacity-75 {
    opacity: 0.75;
}
</style>

<?php include 'includes/footer.php'; ?>
