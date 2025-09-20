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
            $result = deleteRecord('services', $id);
            echo json_encode(['success' => $result]);
            exit;

        case 'toggle_status':
            $service = getRecordById('services', $id);
            if ($service) {
                $newStatus = $service['status'] === 'Active' ? 'Inactive' : 'Active';
                $result = updateRecord('services', $id, ['status' => $newStatus]);
                echo json_encode(['success' => $result]);
            } else {
                echo json_encode(['success' => false]);
            }
            exit;
    }
}

// Get all services with category information
$mysqli = getDatabaseConnection();
$query = "SELECT s.*, sc.name as category_name
          FROM services s
          LEFT JOIN service_categories sc ON s.category_id = sc.id
          ORDER BY s.created_at DESC";
$result = $mysqli->query($query);
$services = [];
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $services[] = $row;
    }
}

// Get statistics
$totalServices = count($services);
$activeServices = count(array_filter($services, fn($s) => $s['status'] === 'Active'));
$inactiveServices = count(array_filter($services, fn($s) => $s['status'] === 'Inactive'));
$totalCategories = count(executeCustomQuery("SELECT DISTINCT category_id FROM services WHERE category_id IS NOT NULL"));
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Services Management | Fair Surveying & Mapping Ltd</title>
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
                <h1 class="mt-0 mb-0">All Services</h1>
                <a href="add-service.php" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Add New Service
                </a>
            </div>

            <!-- Stats Cards -->
            <div class="row mb-4">
                <div class="col-md-3">
                    <div class="stats-card">
                        <div class="stats-icon">
                            <i class="fas fa-cogs"></i>
                        </div>
                        <div class="stats-content">
                            <h3><?php echo $totalServices; ?></h3>
                            <p>Total Services</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stats-card">
                        <div class="stats-icon">
                            <i class="fas fa-check-circle"></i>
                        </div>
                        <div class="stats-content">
                            <h3><?php echo $activeServices; ?></h3>
                            <p>Active Services</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stats-card">
                        <div class="stats-icon">
                            <i class="fas fa-pause-circle"></i>
                        </div>
                        <div class="stats-content">
                            <h3><?php echo $inactiveServices; ?></h3>
                            <p>Inactive Services</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stats-card">
                        <div class="stats-icon">
                            <i class="fas fa-tags"></i>
                        </div>
                        <div class="stats-content">
                            <h3><?php echo $totalCategories; ?></h3>
                            <p>Categories</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header d-flex justify-between align-center">
                    <h5 class="card-title">Services List</h5>
                    <div class="d-flex gap-2">
                        <button class="btn btn-sm btn-light">
                            <i class="fas fa-filter"></i> Filter
                        </button>
                        <button class="btn btn-sm btn-light">
                            <i class="fas fa-download"></i> Export
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="admin-table datatable" id="servicesTable">
                            <thead>
                                <tr>
                                    <th width="50">ID</th>
                                    <th width="80">Image</th>
                                    <th>Service Name</th>
                                    <th>Category</th>
                                    <th>Description</th>
                                    <th>Status</th>
                                    <th width="150">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($services as $index => $service): ?>
                                <tr>
                                    <td><?php echo $service['id']; ?></td>
                                    <td>
                                        <div class="service-img" style="width: 50px; height: 50px; background: #f0f0f0; border-radius: 4px; display: flex; align-items: center; justify-content: center;">
                                            <?php if (!empty($service['image'])): ?>
                                                <img src="../../../uploads/services/<?php echo htmlspecialchars($service['image']); ?>"
                                                     alt="<?php echo htmlspecialchars($service['name']); ?>"
                                                     style="width: 100%; height: 100%; object-fit: cover; border-radius: 4px;">
                                            <?php else: ?>
                                                <i class="fas fa-cogs text-primary"></i>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                    <td><?php echo htmlspecialchars($service['name']); ?></td>
                                    <td><?php echo htmlspecialchars($service['category_name'] ?? 'Uncategorized'); ?></td>
                                    <td><?php echo htmlspecialchars(substr($service['description'], 0, 50)) . (strlen($service['description']) > 50 ? '...' : ''); ?></td>
                                    <td>
                                        <span class="badge <?php echo $service['status'] === 'Active' ? 'badge-success' : 'badge-danger'; ?>">
                                            <?php echo $service['status']; ?>
                                        </span>
                                    </td>
                                    <td>
                                        <div class="d-flex gap-1">
                                            <button class="btn btn-sm btn-light" title="View" onclick="viewService(<?php echo $service['id']; ?>)">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <button class="btn btn-sm btn-secondary" title="Edit" onclick="editService(<?php echo $service['id']; ?>)">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button class="btn btn-sm <?php echo $service['status'] === 'Active' ? 'btn-warning' : 'btn-success'; ?>"
                                                    title="<?php echo $service['status'] === 'Active' ? 'Deactivate' : 'Activate'; ?>"
                                                    onclick="toggleServiceStatus(<?php echo $service['id']; ?>, '<?php echo $service['status']; ?>')">
                                                <i class="fas fa-<?php echo $service['status'] === 'Active' ? 'eye-slash' : 'eye'; ?>"></i>
                                            </button>
                                            <button class="btn btn-sm btn-danger" title="Delete" onclick="deleteService(<?php echo $service['id']; ?>)">
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
            $('#servicesTable').DataTable({
                responsive: true,
                pageLength: 10,
                lengthMenu: [5, 10, 25, 50],
                language: {
                    search: "",
                    searchPlaceholder: "Search services..."
                }
            });
        });

        // Service management functions
        function viewService(serviceId) {
            // Redirect to service details page
            window.location.href = `../services/view-service.php?id=${serviceId}`;
        }

        function editService(serviceId) {
            // Redirect to edit service page
            window.location.href = `add-service.php?edit=${serviceId}`;
        }

        function deleteService(serviceId) {
            if (confirm('Are you sure you want to delete this service? This action cannot be undone.')) {
                $.ajax({
                    url: '',
                    method: 'POST',
                    data: {
                        action: 'delete',
                        id: serviceId
                    },
                    dataType: 'json',
                    success: function(response) {
                        if (response.success) {
                            showNotification('Service deleted successfully!', 'success');
                            setTimeout(() => {
                                location.reload();
                            }, 1500);
                        } else {
                            showNotification('Failed to delete service. Please try again.', 'error');
                        }
                    },
                    error: function() {
                        showNotification('An error occurred. Please try again.', 'error');
                    }
                });
            }
        }

        function toggleServiceStatus(serviceId, currentStatus) {
            const action = currentStatus === 'Active' ? 'deactivate' : 'activate';
            if (confirm(`Are you sure you want to ${action} this service?`)) {
                $.ajax({
                    url: '',
                    method: 'POST',
                    data: {
                        action: 'toggle_status',
                        id: serviceId
                    },
                    dataType: 'json',
                    success: function(response) {
                        if (response.success) {
                            showNotification(`Service ${action}d successfully!`, 'success');
                            setTimeout(() => {
                                location.reload();
                            }, 1500);
                        } else {
                            showNotification(`Failed to ${action} service. Please try again.`, 'error');
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

