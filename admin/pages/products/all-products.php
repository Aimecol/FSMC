<?php
require_once '../../../config/database.php';
require_once '../../../includes/functions.php';

// Handle AJAX requests
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: application/json');

    $action = $_POST['action'] ?? '';
    $id = intval($_POST['id'] ?? 0);

    switch ($action) {
        case 'delete':
            $result = deleteRecord('products', $id);
            echo json_encode(['success' => $result]);
            exit;

        case 'toggle_status':
            $product = getRecordById('products', $id);
            if ($product) {
                $newStatus = $product['status'] === 'Active' ? 'Inactive' : 'Active';
                $result = updateRecord('products', $id, ['status' => $newStatus]);
                echo json_encode(['success' => $result]);
            } else {
                echo json_encode(['success' => false]);
            }
            exit;
    }
}

// Get all products with category information
$mysqli = getDatabaseConnection();
$query = "SELECT p.*, pc.name as category_name
          FROM products p
          LEFT JOIN product_categories pc ON p.category_id = pc.id
          ORDER BY p.created_at DESC";
$result = $mysqli->query($query);
$products = [];
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $products[] = $row;
    }
}

// Get statistics
$totalProducts = count($products);
$activeProducts = count(array_filter($products, fn($p) => $p['status'] === 'Active'));
$inactiveProducts = count(array_filter($products, fn($p) => $p['status'] === 'Inactive'));
$featuredProducts = count(array_filter($products, fn($p) => $p['featured'] == 1));
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Products Management | Fair Surveying & Mapping Ltd</title>
    <link rel="icon" type="image/svg+xml" href="../../../images/logo.png">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- DataTables -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css">
    <!-- Admin CSS -->
    <link rel="stylesheet" href="../../css/admin.css">
</head>
<body>
    <div class="admin-layout">
        <!-- Sidebar -->
        <?php include '../includes/sidebar.php'; ?>
        
        
        <!-- Header -->
        <?php include '../includes/header.php'; ?>
        
        
        <!-- Main Content -->
        <main class="admin-main animate-fadeIn">
            <div class="d-flex justify-between align-center mb-4">
                <h1 class="mt-0 mb-0">Products</h1>
                <div class="d-flex gap-2">
                    <div class="dropdown">
                        <button class="btn btn-light">
                            <i class="fas fa-filter"></i> Filter <i class="fas fa-chevron-down ml-2"></i>
                        </button>
                        <div class="dropdown-menu" style="display: none;">
                            <a href="#" class="dropdown-item">All Products</a>
                            <a href="#" class="dropdown-item">Active Products</a>
                            <a href="#" class="dropdown-item">Inactive Products</a>
                            <a href="#" class="dropdown-item">Featured Products</a>
                            <a href="#" class="dropdown-item">Out of Stock</a>
                        </div>
                    </div>
                    <a href="add-product.php" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Add New Product
                    </a>
                </div>
            </div>
            
            <!-- Products Card -->
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">All Products</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="admin-table" id="productsTable">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Image</th>
                                    <th>Product Name</th>
                                    <th>Category</th>
                                    <th>Price (₵)</th>
                                    <th>Stock</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($products as $index => $product): ?>
                                <tr>
                                    <td><?php echo $index + 1; ?></td>
                                    <td>
                                        <div class="product-img" style="width: 50px; height: 50px; background: #f0f0f0; border-radius: 4px; display: flex; align-items: center; justify-content: center;">
                                            <?php if (!empty($product['image'])): ?>
                                                <img src="../../../uploads/products/<?php echo htmlspecialchars($product['image']); ?>"
                                                     alt="<?php echo htmlspecialchars($product['name']); ?>"
                                                     style="width: 100%; height: 100%; object-fit: cover; border-radius: 4px;">
                                            <?php else: ?>
                                                <i class="fas fa-box text-primary"></i>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                    <td><?php echo htmlspecialchars($product['name']); ?></td>
                                    <td><?php echo htmlspecialchars($product['category_name'] ?? 'Uncategorized'); ?></td>
                                    <td><?php echo formatCurrency($product['price']); ?></td>
                                    <td>
                                        <?php if ($product['stock_quantity'] > 0): ?>
                                            <span class="badge bg-success">In Stock (<?php echo $product['stock_quantity']; ?>)</span>
                                        <?php else: ?>
                                            <span class="badge bg-danger">Out of Stock</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <span class="badge <?php echo $product['status'] === 'Active' ? 'badge-success' : 'badge-danger'; ?>">
                                            <?php echo $product['status']; ?>
                                        </span>
                                    </td>
                                    <td>
                                        <div class="d-flex gap-1">
                                            <button class="btn btn-sm btn-light" title="View" onclick="viewProduct(<?php echo $product['id']; ?>)">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <button class="btn btn-sm btn-secondary" title="Edit" onclick="editProduct(<?php echo $product['id']; ?>)">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button class="btn btn-sm <?php echo $product['status'] === 'Active' ? 'btn-warning' : 'btn-success'; ?>"
                                                    title="<?php echo $product['status'] === 'Active' ? 'Deactivate' : 'Activate'; ?>"
                                                    onclick="toggleProductStatus(<?php echo $product['id']; ?>, '<?php echo $product['status']; ?>')">
                                                <i class="fas fa-<?php echo $product['status'] === 'Active' ? 'eye-slash' : 'eye'; ?>"></i>
                                            </button>
                                            <button class="btn btn-sm btn-danger" title="Delete" onclick="deleteProduct(<?php echo $product['id']; ?>)">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                <?php endforeach; ?>

                            </tbody>
                        </table>

                    </div>
                </div>
            </div>
            
            <!-- Bulk Actions -->
            <div class="card mt-4">
                <div class="card-header">
                    <h5 class="card-title">Bulk Actions</h5>
                </div>
                <div class="card-body">
                    <div class="d-flex gap-2">
                        <select class="form-select" style="max-width: 200px;">
                            <option value="">Select Action</option>
                            <option value="activate">Activate Products</option>
                            <option value="deactivate">Deactivate Products</option>
                            <option value="delete">Delete Products</option>
                            <option value="export">Export Products</option>
                        </select>
                        <button class="btn btn-secondary">Apply</button>
                    </div>
                </div>
            </div>
        </main>
    </div>
    
    <!-- Notification Container -->
    <div class="notification-container"></div>
    
    <!-- JavaScript Dependencies -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>
    
    <!-- Admin JavaScript -->
    <script src="../../js/admin.js"></script>
    
    <script>
        // Initialize DataTable
        $(document).ready(function() {
            $('#productsTable').DataTable({
                responsive: true,
                pageLength: 10,
                lengthMenu: [5, 10, 25, 50],
                language: {
                    search: "",
                    searchPlaceholder: "Search products..."
                }
            });
        });

        // Product management functions
        function viewProduct(productId) {
            // Redirect to product details page
            window.location.href = `../products/view-product.php?id=${productId}`;
        }

        function editProduct(productId) {
            // Redirect to edit product page
            window.location.href = `add-product.php?edit=${productId}`;
        }

        function deleteProduct(productId) {
            if (confirm('Are you sure you want to delete this product? This action cannot be undone.')) {
                $.ajax({
                    url: '',
                    method: 'POST',
                    data: {
                        action: 'delete',
                        id: productId
                    },
                    dataType: 'json',
                    success: function(response) {
                        if (response.success) {
                            showNotification('Product deleted successfully!', 'success');
                            setTimeout(() => {
                                location.reload();
                            }, 1500);
                        } else {
                            showNotification('Failed to delete product. Please try again.', 'error');
                        }
                    },
                    error: function() {
                        showNotification('An error occurred. Please try again.', 'error');
                    }
                });
            }
        }

        function toggleProductStatus(productId, currentStatus) {
            const action = currentStatus === 'Active' ? 'deactivate' : 'activate';
            if (confirm(`Are you sure you want to ${action} this product?`)) {
                $.ajax({
                    url: '',
                    method: 'POST',
                    data: {
                        action: 'toggle_status',
                        id: productId
                    },
                    dataType: 'json',
                    success: function(response) {
                        if (response.success) {
                            showNotification(`Product ${action}d successfully!`, 'success');
                            setTimeout(() => {
                                location.reload();
                            }, 1500);
                        } else {
                            showNotification(`Failed to ${action} product. Please try again.`, 'error');
                        }
                    },
                    error: function() {
                        showNotification('An error occurred. Please try again.', 'error');
                    }
                });
            }
        }

        // Notification function
        function showNotification(message, type) {
            const notification = $(`
                <div class="notification notification-${type}">
                    <i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-circle'}"></i>
                    <span>${message}</span>
                </div>
            `);

            $('.notification-container').append(notification);

            setTimeout(() => {
                notification.fadeOut(() => {
                    notification.remove();
                });
            }, 3000);
        }
    </script>
</body>
</html>