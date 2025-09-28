<?php
/**
 * Products Management for FSMC Admin System
 * Created: 2025-01-22
 */

require_once 'config/config.php';

// Set page variables
$pageTitle = 'Products Management';
$pageIcon = 'fas fa-box';
$pageDescription = 'Manage your equipment, software, and training materials';
$pageActions = '<a href="product_enquiries.php" class="btn btn-info me-2"><i class="fas fa-envelope"></i> View All Enquiries</a><a href="product_edit.php" class="btn btn-primary"><i class="fas fa-plus"></i> Add New Product</a>';

// Handle actions
$action = $_GET['action'] ?? '';
$productId = $_GET['id'] ?? 0;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verifyCSRFToken($_POST['csrf_token'] ?? '')) {
        setErrorMessage('Invalid security token.');
        redirect('products.php');
    }
    
    if ($action === 'delete' && $productId) {
        // Delete product
        $product = dbGetRow("SELECT title FROM products WHERE id = ?", [$productId]);
        if ($product) {
            if (dbExecute("DELETE FROM products WHERE id = ?", [$productId])) {
                logActivity('Product Deleted', 'products', $productId, $product);
                setSuccessMessage('Product "' . $product['title'] . '" has been deleted successfully.');
            } else {
                setErrorMessage('Failed to delete product.');
            }
        }
        redirect('products.php');
    }
    
    if ($action === 'toggle_status' && $productId) {
        // Toggle product status
        $product = dbGetRow("SELECT title, status FROM products WHERE id = ?", [$productId]);
        if ($product) {
            $newStatus = $product['status'] === 'active' ? 'inactive' : 'active';
            if (dbExecute("UPDATE products SET status = ?, updated_at = NOW() WHERE id = ?", [$newStatus, $productId])) {
                logActivity('Product Status Changed', 'products', $productId, 
                    ['status' => $product['status']], 
                    ['status' => $newStatus]
                );
                setSuccessMessage('Product status updated successfully.');
            } else {
                setErrorMessage('Failed to update product status.');
            }
        }
        redirect('products.php');
    }
}

// Get products with pagination
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
    $whereConditions[] = "(title LIKE ? OR description LIKE ? OR manufacturer LIKE ?)";
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
$totalQuery = "SELECT COUNT(*) as total FROM products $whereClause";
$totalResult = dbGetRow($totalQuery, $params);
$totalProducts = $totalResult['total'] ?? 0;
$totalPages = ceil($totalProducts / $limit);

// Get products
$productsQuery = "SELECT id, title, slug, short_description, category, manufacturer, model, price, warranty, support, icon, status, sort_order, created_at, updated_at 
                  FROM products $whereClause 
                  ORDER BY sort_order ASC, created_at DESC 
                  LIMIT $limit OFFSET $offset";
$products = dbGetRows($productsQuery, $params);

// Get categories for filter
$categories = ['equipment', 'software', 'training', 'bundle'];

include 'includes/header.php';
?>

<!-- Filters -->
<div class="card mb-3">
    <div class="card-body">
        <form method="GET" class="row align-items-end">
            <div class="col-md-3">
                <label for="search" class="form-label">Search Products</label>
                <input type="text" id="search" name="search" class="form-control" 
                       value="<?php echo htmlspecialchars($search); ?>" 
                       placeholder="Search by title, description, or manufacturer...">
            </div>
            <div class="col-md-2">
                <label for="category" class="form-label">Category</label>
                <select id="category" name="category" class="form-control">
                    <option value="">All Categories</option>
                    <?php foreach ($categories as $cat): ?>
                        <option value="<?php echo $cat; ?>" <?php echo $category === $cat ? 'selected' : ''; ?>>
                            <?php echo ucfirst($cat); ?>
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
                <a href="products.php" class="btn btn-secondary">
                    <i class="fas fa-times"></i> Clear
                </a>
            </div>
        </form>
    </div>
</div>

<!-- Products Table -->
<div class="card">
    <div class="card-header">
        <h3 class="card-title">
            <i class="fas fa-list"></i>
            Products List (<?php echo number_format($totalProducts); ?> total)
        </h3>
    </div>
    <div class="card-body">
        <?php if (empty($products)): ?>
            <div class="text-center py-4">
                <i class="fas fa-box fa-3x text-muted mb-3"></i>
                <h5 class="text-muted">No products found</h5>
                <p class="text-muted">Start by adding your first product.</p>
                <a href="product_edit.php" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Add New Product
                </a>
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th>Category</th>
                            <th>Manufacturer</th>
                            <th>Price</th>
                            <th>Status</th>
                            <th>Order</th>
                            <th>Created</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($products as $product): ?>
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    <?php if ($product['icon']): ?>
                                        <i class="<?php echo htmlspecialchars($product['icon']); ?> fa-2x text-primary me-3"></i>
                                    <?php endif; ?>
                                    <div>
                                        <strong><?php echo htmlspecialchars($product['title']); ?></strong><br>
                                        <small class="text-muted"><?php echo htmlspecialchars($product['model'] ?? 'N/A'); ?></small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="badge badge-<?php 
                                    echo $product['category'] === 'equipment' ? 'primary' : 
                                        ($product['category'] === 'software' ? 'info' : 
                                        ($product['category'] === 'training' ? 'warning' : 'success')); 
                                ?>">
                                    <?php echo ucfirst($product['category']); ?>
                                </span>
                            </td>
                            <td><?php echo htmlspecialchars($product['manufacturer'] ?? 'N/A'); ?></td>
                            <td>
                                <?php if ($product['price']): ?>
                                    <strong>RWF <?php echo number_format($product['price'], 0); ?></strong>
                                <?php else: ?>
                                    <span class="text-muted">Contact for price</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <span class="badge badge-<?php 
                                    echo $product['status'] === 'active' ? 'success' : 
                                        ($product['status'] === 'inactive' ? 'secondary' : 'warning'); 
                                ?>">
                                    <?php echo ucfirst($product['status']); ?>
                                </span>
                            </td>
                            <td>
                                <span class="badge badge-info"><?php echo $product['sort_order']; ?></span>
                            </td>
                            <td>
                                <small><?php echo date('M j, Y', strtotime($product['created_at'])); ?></small>
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <a href="product_edit.php?id=<?php echo $product['id']; ?>" 
                                       class="btn btn-outline-primary" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    
                                    <a href="product_enquiries.php?product_id=<?php echo $product['id']; ?>" 
                                       class="btn btn-outline-info" title="View Enquiries">
                                        <i class="fas fa-envelope"></i>
                                    </a>
                                    
                                    <form method="POST" style="display: inline;" 
                                          action="products.php?action=toggle_status&id=<?php echo $product['id']; ?>">
                                        <input type="hidden" name="csrf_token" value="<?php echo generateCSRFToken(); ?>">
                                        <button type="submit" 
                                                class="btn btn-outline-<?php echo $product['status'] === 'active' ? 'warning' : 'success'; ?>" 
                                                title="<?php echo $product['status'] === 'active' ? 'Deactivate' : 'Activate'; ?>">
                                            <i class="fas fa-<?php echo $product['status'] === 'active' ? 'pause' : 'play'; ?>"></i>
                                        </button>
                                    </form>
                                    
                                    <button type="button" 
                                            class="btn btn-outline-danger btn-delete" 
                                            data-url="products.php?action=delete&id=<?php echo $product['id']; ?>"
                                            data-name="<?php echo htmlspecialchars($product['title']); ?>"
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
            <nav aria-label="Products pagination">
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
                <p>Are you sure you want to delete this product? This action cannot be undone.</p>
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
