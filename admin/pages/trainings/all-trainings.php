<?php
// Include database configuration and functions
require_once '../../../config/database.php';
require_once '../../../includes/functions.php';

// Handle AJAX requests
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    $id = intval($_POST['id'] ?? 0);
    
    switch ($action) {
        case 'delete':
            $result = deleteRecord('training_programs', $id);
            echo json_encode(['success' => $result]);
            exit;
            
        case 'toggle_status':
            $training = getRecordById('training_programs', $id);
            if ($training) {
                $newStatus = $training['status'] === 'Active' ? 'Inactive' : 'Active';
                $result = updateRecord('training_programs', $id, ['status' => $newStatus]);
                echo json_encode(['success' => $result]);
            }
            exit;
    }
}

// Get all training programs with category information
$query = "SELECT tp.*, tc.name as category_name FROM training_programs tp LEFT JOIN training_categories tc ON tp.category_id = tc.id ORDER BY tp.created_at DESC";
$training_programs = executeCustomQuery($query);

// Calculate statistics
$totalTrainings = count($training_programs);
$activeTrainings = count(array_filter($training_programs, function($t) { return $t['status'] === 'Active'; }));
$inactiveTrainings = $totalTrainings - $activeTrainings;
$totalCategories = count(executeCustomQuery("SELECT DISTINCT category_id FROM training_programs WHERE category_id IS NOT NULL"));
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trainings Management | Fair Surveying & Mapping Ltd</title>
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
                <h1 class="mt-0 mb-0">All Trainings</h1>
                <a href="add-training.php" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Add New Training
                </a>
            </div>
            
            <!-- Stats Cards -->
            <div class="row mb-4">
                <div class="col-md-3">
                    <div class="stats-card">
                        <div class="stats-icon">
                            <i class="fas fa-graduation-cap"></i>
                        </div>
                        <div class="stats-content">
                            <h3><?php echo $totalTrainings; ?></h3>
                            <p>Total Trainings</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stats-card">
                        <div class="stats-icon">
                            <i class="fas fa-check-circle"></i>
                        </div>
                        <div class="stats-content">
                            <h3><?php echo $activeTrainings; ?></h3>
                            <p>Active Programs</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stats-card">
                        <div class="stats-icon">
                            <i class="fas fa-pause-circle"></i>
                        </div>
                        <div class="stats-content">
                            <h3><?php echo $inactiveTrainings; ?></h3>
                            <p>Inactive Programs</p>
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
            
            <!-- Trainings List -->
            <div class="card">
                <div class="card-header d-flex justify-between align-center">
                    <h5 class="card-title">Training Programs</h5>
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
                        <table class="admin-table datatable" id="trainingsTable">
                            <thead>
                                <tr>
                                    <th width="50">ID</th>
                                    <th width="80">Image</th>
                                    <th>Training Name</th>
                                    <th>Category</th>
                                    <th>Duration</th>
                                    <th>Price</th>
                                    <th>Status</th>
                                    <th width="150">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($training_programs as $index => $training): ?>
                                <tr>
                                    <td><?php echo $training['id']; ?></td>
                                    <td>
                                        <div class="training-img" style="width: 50px; height: 50px; background: #f0f0f0; border-radius: 4px; display: flex; align-items: center; justify-content: center;">
                                            <?php if (!empty($training['image'])): ?>
                                                <img src="../../../uploads/trainings/<?php echo htmlspecialchars($training['image']); ?>" 
                                                     alt="<?php echo htmlspecialchars($training['title']); ?>" 
                                                     style="width: 100%; height: 100%; object-fit: cover; border-radius: 4px;">
                                            <?php else: ?>
                                                <i class="fas fa-graduation-cap text-primary"></i>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                    <td><?php echo htmlspecialchars($training['title']); ?></td>
                                    <td><?php echo htmlspecialchars($training['category_name'] ?? 'Uncategorized'); ?></td>
                                    <td><?php echo htmlspecialchars($training['duration'] ?? 'N/A'); ?></td>
                                    <td>
                                        <?php if ($training['price']): ?>
                                            $<?php echo number_format($training['price'], 2); ?>
                                        <?php else: ?>
                                            Free
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <span class="badge <?php echo $training['status'] === 'Active' ? 'badge-success' : 'badge-danger'; ?>">
                                            <?php echo $training['status']; ?>
                                        </span>
                                    </td>
                                    <td>
                                        <div class="d-flex gap-1">
                                            <button class="btn btn-sm btn-light" title="View" onclick="viewTraining(<?php echo $training['id']; ?>)">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <button class="btn btn-sm btn-secondary" title="Edit" onclick="editTraining(<?php echo $training['id']; ?>)">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button class="btn btn-sm <?php echo $training['status'] === 'Active' ? 'btn-warning' : 'btn-success'; ?>" 
                                                    title="<?php echo $training['status'] === 'Active' ? 'Deactivate' : 'Activate'; ?>" 
                                                    onclick="toggleTrainingStatus(<?php echo $training['id']; ?>, '<?php echo $training['status']; ?>')">
                                                <i class="fas fa-<?php echo $training['status'] === 'Active' ? 'eye-slash' : 'eye'; ?>"></i>
                                            </button>
                                            <button class="btn btn-sm btn-danger" title="Delete" onclick="deleteTraining(<?php echo $training['id']; ?>)">
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
            $('#trainingsTable').DataTable({
                responsive: true,
                pageLength: 10,
                lengthMenu: [5, 10, 25, 50],
                language: {
                    search: "",
                    searchPlaceholder: "Search trainings..."
                }
            });
        });
        
        // Training management functions
        function viewTraining(trainingId) {
            window.location.href = `../trainings/view-training.php?id=${trainingId}`;
        }
        
        function editTraining(trainingId) {
            window.location.href = `add-training.php?edit=${trainingId}`;
        }
        
        function deleteTraining(trainingId) {
            if (confirm('Are you sure you want to delete this training program? This action cannot be undone.')) {
                $.ajax({
                    url: '',
                    method: 'POST',
                    data: {
                        action: 'delete',
                        id: trainingId
                    },
                    dataType: 'json',
                    success: function(response) {
                        if (response.success) {
                            showNotification('Training program deleted successfully!', 'success');
                            setTimeout(() => {
                                location.reload();
                            }, 1500);
                        } else {
                            showNotification('Failed to delete training program. Please try again.', 'error');
                        }
                    },
                    error: function() {
                        showNotification('An error occurred. Please try again.', 'error');
                    }
                });
            }
        }
        
        function toggleTrainingStatus(trainingId, currentStatus) {
            const action = currentStatus === 'Active' ? 'deactivate' : 'activate';
            if (confirm(`Are you sure you want to ${action} this training program?`)) {
                $.ajax({
                    url: '',
                    method: 'POST',
                    data: {
                        action: 'toggle_status',
                        id: trainingId
                    },
                    dataType: 'json',
                    success: function(response) {
                        if (response.success) {
                            showNotification(`Training program ${action}d successfully!`, 'success');
                            setTimeout(() => {
                                location.reload();
                            }, 1500);
                        } else {
                            showNotification(`Failed to ${action} training program. Please try again.`, 'error');
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
