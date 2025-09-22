<?php
/**
 * Research Projects Management for FSMC Admin System
 * Created: 2025-01-22
 */

require_once 'config/config.php';

// Set page variables
$pageTitle = 'Research Projects Management';
$pageIcon = 'fas fa-microscope';
$pageDescription = 'Manage your research publications and findings';
$pageActions = '<a href="research_edit.php" class="btn btn-primary"><i class="fas fa-plus"></i> Add New Research</a>';

// Handle actions
$action = $_GET['action'] ?? '';
$researchId = $_GET['id'] ?? 0;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verifyCSRFToken($_POST['csrf_token'] ?? '')) {
        setErrorMessage('Invalid security token.');
        redirect('research.php');
    }
    
    if ($action === 'delete' && $researchId) {
        // Delete research project
        $research = dbGetRow("SELECT title FROM research_projects WHERE id = ?", [$researchId]);
        if ($research) {
            if (dbExecute("DELETE FROM research_projects WHERE id = ?", [$researchId])) {
                logActivity('Research Project Deleted', 'research_projects', $researchId, $research);
                setSuccessMessage('Research project "' . $research['title'] . '" has been deleted successfully.');
            } else {
                setErrorMessage('Failed to delete research project.');
            }
        }
        redirect('research.php');
    }
    
    if ($action === 'toggle_status' && $researchId) {
        // Toggle research status
        $research = dbGetRow("SELECT title, status FROM research_projects WHERE id = ?", [$researchId]);
        if ($research) {
            $newStatus = $research['status'] === 'published' ? 'draft' : 'published';
            if (dbExecute("UPDATE research_projects SET status = ?, updated_at = NOW() WHERE id = ?", [$newStatus, $researchId])) {
                logActivity('Research Project Status Changed', 'research_projects', $researchId, 
                    ['status' => $research['status']], 
                    ['status' => $newStatus]
                );
                setSuccessMessage('Research project status updated successfully.');
            } else {
                setErrorMessage('Failed to update research project status.');
            }
        }
        redirect('research.php');
    }
}

// Get research projects with pagination
$page = max(1, intval($_GET['page'] ?? 1));
$limit = DEFAULT_PAGE_SIZE;
$offset = ($page - 1) * $limit;

$search = sanitize($_GET['search'] ?? '');
$category = sanitize($_GET['category'] ?? '');
$status = sanitize($_GET['status'] ?? '');

// Build query
$whereConditions = [];
$params = [];

if ($search) {
    $whereConditions[] = "(title LIKE ? OR abstract LIKE ? OR authors LIKE ?)";
    $params[] = "%$search%";
    $params[] = "%$search%";
    $params[] = "%$search%";
}

if ($category) {
    $whereConditions[] = "category = ?";
    $params[] = $category;
}

if ($status) {
    $whereConditions[] = "status = ?";
    $params[] = $status;
}

$whereClause = !empty($whereConditions) ? 'WHERE ' . implode(' AND ', $whereConditions) : '';

// Get total count
$totalQuery = "SELECT COUNT(*) as total FROM research_projects $whereClause";
$totalResult = dbGetRow($totalQuery, $params);
$totalProjects = $totalResult['total'] ?? 0;
$totalPages = ceil($totalProjects / $limit);

// Get research projects
$projectsQuery = "SELECT id, title, slug, abstract, category, authors, publication_date, 
                         journal, doi, status, sort_order, created_at, updated_at 
                  FROM research_projects $whereClause 
                  ORDER BY publication_date DESC, sort_order ASC, created_at DESC 
                  LIMIT $limit OFFSET $offset";
$projects = dbGetRows($projectsQuery, $params);

// Get categories for filter
$categories = ['surveying', 'mapping', 'gis', 'remote_sensing', 'cartography', 'geodesy'];

include 'includes/header.php';
?>

<!-- Filters -->
<div class="card mb-3">
    <div class="card-body">
        <form method="GET" class="row align-items-end">
            <div class="col-md-4">
                <label for="search" class="form-label">Search Research</label>
                <input type="text" id="search" name="search" class="form-control" 
                       value="<?php echo htmlspecialchars($search); ?>" 
                       placeholder="Search by title, abstract, or authors...">
            </div>
            <div class="col-md-2">
                <label for="category" class="form-label">Category</label>
                <select id="category" name="category" class="form-control">
                    <option value="">All Categories</option>
                    <?php foreach ($categories as $cat): ?>
                        <option value="<?php echo $cat; ?>" <?php echo $category === $cat ? 'selected' : ''; ?>>
                            <?php echo ucfirst(str_replace('_', ' ', $cat)); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-2">
                <label for="status" class="form-label">Status</label>
                <select id="status" name="status" class="form-control">
                    <option value="">All Statuses</option>
                    <option value="published" <?php echo $status === 'published' ? 'selected' : ''; ?>>Published</option>
                    <option value="draft" <?php echo $status === 'draft' ? 'selected' : ''; ?>>Draft</option>
                    <option value="under_review" <?php echo $status === 'under_review' ? 'selected' : ''; ?>>Under Review</option>
                </select>
            </div>
            <div class="col-md-3">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-search"></i> Search
                </button>
                <a href="research.php" class="btn btn-secondary">
                    <i class="fas fa-times"></i> Clear
                </a>
            </div>
        </form>
    </div>
</div>

<!-- Research Projects Table -->
<div class="card">
    <div class="card-header">
        <h3 class="card-title">
            <i class="fas fa-list"></i>
            Research Projects (<?php echo number_format($totalProjects); ?> total)
        </h3>
    </div>
    <div class="card-body">
        <?php if (empty($projects)): ?>
            <div class="text-center py-4">
                <i class="fas fa-microscope fa-3x text-muted mb-3"></i>
                <h5 class="text-muted">No research projects found</h5>
                <p class="text-muted">Start by adding your first research project.</p>
                <a href="research_edit.php" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Add New Research
                </a>
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Research</th>
                            <th>Category</th>
                            <th>Authors</th>
                            <th>Publication</th>
                            <th>Status</th>
                            <th>Order</th>
                            <th>Created</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($projects as $project): ?>
                        <tr>
                            <td>
                                <div>
                                    <strong><?php echo htmlspecialchars($project['title']); ?></strong><br>
                                    <small class="text-muted">
                                        <?php 
                                        $abstract = $project['abstract'] ?? '';
                                        echo htmlspecialchars(substr($abstract, 0, 100)) . (strlen($abstract) > 100 ? '...' : ''); 
                                        ?>
                                    </small>
                                </div>
                            </td>
                            <td>
                                <span class="badge badge-<?php 
                                    echo $project['category'] === 'surveying' ? 'primary' : 
                                        ($project['category'] === 'mapping' ? 'info' : 
                                        ($project['category'] === 'gis' ? 'success' : 
                                        ($project['category'] === 'remote_sensing' ? 'warning' : 'secondary'))); 
                                ?>">
                                    <?php echo ucfirst(str_replace('_', ' ', $project['category'])); ?>
                                </span>
                            </td>
                            <td>
                                <?php 
                                $authors = $project['authors'] ?? '';
                                echo htmlspecialchars(strlen($authors) > 30 ? substr($authors, 0, 30) . '...' : $authors); 
                                ?>
                            </td>
                            <td>
                                <?php if ($project['publication_date']): ?>
                                    <small><?php echo date('M Y', strtotime($project['publication_date'])); ?></small><br>
                                <?php endif; ?>
                                <?php if ($project['journal']): ?>
                                    <small class="text-muted"><?php echo htmlspecialchars($project['journal']); ?></small>
                                <?php endif; ?>
                            </td>
                            <td>
                                <span class="badge badge-<?php 
                                    echo $project['status'] === 'published' ? 'success' : 
                                        ($project['status'] === 'draft' ? 'warning' : 'info'); 
                                ?>">
                                    <?php echo ucfirst(str_replace('_', ' ', $project['status'])); ?>
                                </span>
                            </td>
                            <td>
                                <span class="badge badge-info"><?php echo $project['sort_order']; ?></span>
                            </td>
                            <td>
                                <small><?php echo date('M j, Y', strtotime($project['created_at'])); ?></small>
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <a href="research_edit.php?id=<?php echo $project['id']; ?>" 
                                       class="btn btn-outline-primary" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    
                                    <form method="POST" style="display: inline;" 
                                          action="research.php?action=toggle_status&id=<?php echo $project['id']; ?>">
                                        <input type="hidden" name="csrf_token" value="<?php echo generateCSRFToken(); ?>">
                                        <button type="submit" 
                                                class="btn btn-outline-<?php echo $project['status'] === 'published' ? 'warning' : 'success'; ?>" 
                                                title="<?php echo $project['status'] === 'published' ? 'Unpublish' : 'Publish'; ?>">
                                            <i class="fas fa-<?php echo $project['status'] === 'published' ? 'eye-slash' : 'eye'; ?>"></i>
                                        </button>
                                    </form>
                                    
                                    <button type="button" 
                                            class="btn btn-outline-danger btn-delete" 
                                            data-url="research.php?action=delete&id=<?php echo $project['id']; ?>"
                                            data-name="<?php echo htmlspecialchars($project['title']); ?>"
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
            <nav aria-label="Research projects pagination">
                <ul class="pagination justify-content-center">
                    <?php if ($page > 1): ?>
                        <li class="page-item">
                            <a class="page-link" href="?page=<?php echo $page - 1; ?>&search=<?php echo urlencode($search); ?>&category=<?php echo urlencode($category); ?>&status=<?php echo urlencode($status); ?>">
                                <i class="fas fa-chevron-left"></i> Previous
                            </a>
                        </li>
                    <?php endif; ?>
                    
                    <?php for ($i = max(1, $page - 2); $i <= min($totalPages, $page + 2); $i++): ?>
                        <li class="page-item <?php echo $i === $page ? 'active' : ''; ?>">
                            <a class="page-link" href="?page=<?php echo $i; ?>&search=<?php echo urlencode($search); ?>&category=<?php echo urlencode($category); ?>&status=<?php echo urlencode($status); ?>">
                                <?php echo $i; ?>
                            </a>
                        </li>
                    <?php endfor; ?>
                    
                    <?php if ($page < $totalPages): ?>
                        <li class="page-item">
                            <a class="page-link" href="?page=<?php echo $page + 1; ?>&search=<?php echo urlencode($search); ?>&category=<?php echo urlencode($category); ?>&status=<?php echo urlencode($status); ?>">
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
                <p>Are you sure you want to delete this research project? This action cannot be undone.</p>
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
