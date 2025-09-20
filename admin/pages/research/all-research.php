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
            $result = deleteRecord('research_projects', $id);
            echo json_encode(['success' => $result]);
            exit;
            
        case 'toggle_status':
            $research = getRecordById('research_projects', $id);
            if ($research) {
                $newStatus = $research['status'] === 'Active' ? 'Inactive' : 'Active';
                $result = updateRecord('research_projects', $id, ['status' => $newStatus]);
                echo json_encode(['success' => $result]);
            }
            exit;
    }
}

// Get all research projects with category information
$query = "SELECT rp.*, rc.name as category_name FROM research_projects rp LEFT JOIN research_categories rc ON rp.category_id = rc.id ORDER BY rp.created_at DESC";
$research_projects = executeCustomQuery($query);

// Calculate statistics
$totalResearch = count($research_projects);
$activeResearch = count(array_filter($research_projects, function($r) { return $r['status'] === 'Active'; }));
$inactiveResearch = $totalResearch - $activeResearch;
$totalCategories = count(executeCustomQuery("SELECT DISTINCT category_id FROM research_projects WHERE category_id IS NOT NULL"));
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Research Management | Fair Surveying & Mapping Ltd</title>
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
        <main class="admin-main">
            <div class="page-header">
                <div>
                    <h1 class="page-title">Research Projects</h1>
                    <p class="page-subtitle">Manage all research projects and studies.</p>
                </div>
                <div>
                    <a href="add-research.php" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Add Research
                    </a>
                </div>
            </div>
            
            <!-- Stats Cards -->
            <div class="row mb-4">
                <div class="col-md-3">
                    <div class="stats-card">
                        <div class="stats-icon">
                            <i class="fas fa-flask"></i>
                        </div>
                        <div class="stats-content">
                            <h3><?php echo $totalResearch; ?></h3>
                            <p>Total Research</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stats-card">
                        <div class="stats-icon">
                            <i class="fas fa-check-circle"></i>
                        </div>
                        <div class="stats-content">
                            <h3><?php echo $activeResearch; ?></h3>
                            <p>Active Projects</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stats-card">
                        <div class="stats-icon">
                            <i class="fas fa-pause-circle"></i>
                        </div>
                        <div class="stats-content">
                            <h3><?php echo $inactiveResearch; ?></h3>
                            <p>Inactive Projects</p>
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
            
            <!-- Research Table -->
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">All Research Projects</h5>
                    <div class="card-tools">
                        <button class="btn btn-light">
                            <i class="fas fa-download"></i> Export
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="researchTable" class="table table-hover">
                            <thead>
                                <tr>
                                    <th>
                                        <input type="checkbox" class="form-check-input">
                                    </th>
                                    <th>Project</th>
                                    <th>Category</th>
                                    <th>Researchers</th>
                                    <th>Start Date</th>
                                    <th>End Date</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($research_projects as $index => $research): ?>
                                <tr>
                                    <td>
                                        <input type="checkbox" class="form-check-input" value="<?php echo $research['id']; ?>">
                                    </td>
                                    <td>
                                        <div class="d-flex align-center gap-2">
                                            <div class="research-icon" style="width: 40px; height: 40px; background: #3498db; color: white; border-radius: 4px; display: flex; align-items: center; justify-content: center;">
                                                <i class="fas fa-flask"></i>
                                            </div>
                                            <div>
                                                <div class="fw-bold"><?php echo htmlspecialchars($research['title']); ?></div>
                                                <div class="text-gray" style="font-size: 0.85rem">ID: <?php echo htmlspecialchars($research['project_code'] ?? 'RES-' . str_pad($research['id'], 3, '0', STR_PAD_LEFT)); ?></div>
                                            </div>
                                        </div>
                                    </td>
                                    <td><?php echo htmlspecialchars($research['category_name'] ?? 'Uncategorized'); ?></td>
                                    <td><?php echo htmlspecialchars($research['researchers'] ?? 'N/A'); ?></td>
                                    <td><?php echo $research['start_date'] ? date('M d, Y', strtotime($research['start_date'])) : 'N/A'; ?></td>
                                    <td><?php echo $research['end_date'] ? date('M d, Y', strtotime($research['end_date'])) : 'N/A'; ?></td>
                                    <td>
                                        <span class="badge <?php 
                                            echo $research['status'] === 'Active' ? 'bg-success' : 
                                                ($research['status'] === 'Completed' ? 'bg-primary' : 
                                                ($research['status'] === 'On Hold' ? 'bg-warning' : 'bg-danger')); 
                                        ?>">
                                            <?php echo $research['status']; ?>
                                        </span>
                                    </td>
                                    <td>
                                        <div class="d-flex gap-1">
                                            <button class="btn btn-sm btn-light" title="View" onclick="viewResearch(<?php echo $research['id']; ?>)">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <button class="btn btn-sm btn-secondary" title="Edit" onclick="editResearch(<?php echo $research['id']; ?>)">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button class="btn btn-sm <?php echo $research['status'] === 'Active' ? 'btn-warning' : 'btn-success'; ?>" 
                                                    title="<?php echo $research['status'] === 'Active' ? 'Deactivate' : 'Activate'; ?>" 
                                                    onclick="toggleResearchStatus(<?php echo $research['id']; ?>, '<?php echo $research['status']; ?>')">
                                                <i class="fas fa-<?php echo $research['status'] === 'Active' ? 'pause' : 'play'; ?>"></i>
                                            </button>
                                            <button class="btn btn-sm btn-danger" title="Delete" onclick="deleteResearch(<?php echo $research['id']; ?>)">
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
            $('#researchTable').DataTable({
                responsive: true,
                pageLength: 10,
                lengthMenu: [5, 10, 25, 50],
                language: {
                    search: "",
                    searchPlaceholder: "Search research projects..."
                }
            });
        });
        
        // Research management functions
        function viewResearch(researchId) {
            window.location.href = `../research/view-research.php?id=${researchId}`;
        }
        
        function editResearch(researchId) {
            window.location.href = `add-research.php?edit=${researchId}`;
        }
        
        function deleteResearch(researchId) {
            if (confirm('Are you sure you want to delete this research project? This action cannot be undone.')) {
                $.ajax({
                    url: '',
                    method: 'POST',
                    data: {
                        action: 'delete',
                        id: researchId
                    },
                    dataType: 'json',
                    success: function(response) {
                        if (response.success) {
                            showNotification('Research project deleted successfully!', 'success');
                            setTimeout(() => {
                                location.reload();
                            }, 1500);
                        } else {
                            showNotification('Failed to delete research project. Please try again.', 'error');
                        }
                    },
                    error: function() {
                        showNotification('An error occurred. Please try again.', 'error');
                    }
                });
            }
        }
        
        function toggleResearchStatus(researchId, currentStatus) {
            const action = currentStatus === 'Active' ? 'deactivate' : 'activate';
            if (confirm(`Are you sure you want to ${action} this research project?`)) {
                $.ajax({
                    url: '',
                    method: 'POST',
                    data: {
                        action: 'toggle_status',
                        id: researchId
                    },
                    dataType: 'json',
                    success: function(response) {
                        if (response.success) {
                            showNotification(`Research project ${action}d successfully!`, 'success');
                            setTimeout(() => {
                                location.reload();
                            }, 1500);
                        } else {
                            showNotification(`Failed to ${action} research project. Please try again.`, 'error');
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
