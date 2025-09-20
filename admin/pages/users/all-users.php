<?php
require_once '../../../config/database.php';
require_once '../../../includes/functions.php';

// Handle AJAX requests
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    header('Content-Type: application/json');

    switch ($_POST['action']) {
        case 'delete':
            $id = intval($_POST['id']);
            $success = deleteRecord('users', $id);
            echo json_encode(['success' => $success]);
            exit;

        case 'toggle_status':
            $id = intval($_POST['id']);
            $user = getRecordById('users', $id);
            if ($user) {
                $newStatus = $user['status'] === 'Active' ? 'Inactive' : 'Active';
                $success = updateRecord('users', $id, ['status' => $newStatus]);
                echo json_encode(['success' => $success, 'new_status' => $newStatus]);
            } else {
                echo json_encode(['success' => false]);
            }
            exit;
    }
}

// Get users data
$users = getAllRecords('users', '', 'created_at DESC');
$totalUsers = getRecordCount('users');
$activeUsers = getRecordCount('users', "status = 'Active'");
$newUsers = getRecordCount('users', "created_at >= DATE_SUB(NOW(), INTERVAL 30 DAY)");
$inactiveUsers = getRecordCount('users', "status = 'Inactive'");
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Users Management | Fair Surveying & Mapping Ltd</title>
    <link rel="icon" type="image/svg+xml" href="../../../images/logo.png" />
    <!-- Font Awesome -->
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
    />
    <!-- Admin CSS -->
    <link rel="stylesheet" href="../../css/admin.css" />
  </head>
  <body>
    <div class="admin-layout">
      <!-- Sidebar -->
      <?php include "../includes/sidebar.php"; ?>
      <!-- Header -->
      <?php include "../includes/header.php"; ?>

      <!-- Main Content -->
      <main class="admin-main animate-fadeIn">
        <div class="d-flex justify-between align-center mb-4">
          <h1 class="mt-0 mb-0">All Users</h1>
          <a href="add-user.php" class="btn btn-primary">
            <i class="fas fa-user-plus"></i> Add New User
          </a>
        </div>

        <!-- Stats Cards -->
        <div class="dashboard-grid mb-4">
          <div class="widget">
            <div class="widget-header">
              <div class="widget-title">Total Users</div>
              <div class="widget-icon">
                <i class="fas fa-users"></i>
              </div>
            </div>
            <div class="widget-body">
              <div class="widget-value"><?php echo $totalUsers; ?></div>
              <div class="widget-description">Registered users</div>
            </div>
            <div class="widget-footer">
              <div class="widget-change text-success">
                <i class="fas fa-arrow-up"></i>
                <span>All time</span>
              </div>
            </div>
          </div>

          <div class="widget">
            <div class="widget-header">
              <div class="widget-title">Active Users</div>
              <div
                class="widget-icon"
                style="background-color: rgba(39, 174, 96, 0.1); color: #27ae60"
              >
                <i class="fas fa-user-check"></i>
              </div>
            </div>
            <div class="widget-body">
              <div class="widget-value"><?php echo $activeUsers; ?></div>
              <div class="widget-description">Currently active</div>
            </div>
            <div class="widget-footer">
              <div class="widget-change text-success">
                <i class="fas fa-arrow-up"></i>
                <span><?php echo round(($activeUsers / max($totalUsers, 1)) * 100, 1); ?>% active</span>
              </div>
            </div>
          </div>

          <div class="widget">
            <div class="widget-header">
              <div class="widget-title">New Users</div>
              <div
                class="widget-icon"
                style="
                  background-color: rgba(243, 156, 18, 0.1);
                  color: #f39c12;
                "
              >
                <i class="fas fa-user-plus"></i>
              </div>
            </div>
            <div class="widget-body">
              <div class="widget-value"><?php echo $newUsers; ?></div>
              <div class="widget-description">Last 30 days</div>
            </div>
            <div class="widget-footer">
              <div class="widget-change text-success">
                <i class="fas fa-arrow-up"></i>
                <span><?php echo round(($newUsers / max($totalUsers, 1)) * 100, 1); ?>% of total</span>
              </div>
            </div>
          </div>

          <div class="widget">
            <div class="widget-header">
              <div class="widget-title">Inactive Users</div>
              <div
                class="widget-icon"
                style="
                  background-color: rgba(231, 76, 60, 0.1);
                  color: #e74c3c;
                "
              >
                <i class="fas fa-user-times"></i>
              </div>
            </div>
            <div class="widget-body">
              <div class="widget-value"><?php echo $inactiveUsers; ?></div>
              <div class="widget-description">Suspended/Inactive</div>
            </div>
            <div class="widget-footer">
              <div class="widget-change text-danger">
                <i class="fas fa-arrow-down"></i>
                <span><?php echo round(($inactiveUsers / max($totalUsers, 1)) * 100, 1); ?>% inactive</span>
              </div>
            </div>
          </div>
        </div>

        <div class="card">
          <div class="card-header d-flex justify-between align-center">
            <h5 class="card-title">Users List</h5>
            <div class="d-flex gap-2">
              <div class="dropdown">
                <button class="btn btn-sm btn-light">
                  <i class="fas fa-filter"></i> Filter
                </button>
                <div class="dropdown-menu" style="display: none">
                  <a href="#" class="dropdown-item">All Users</a>
                  <a href="#" class="dropdown-item">Admins</a>
                  <a href="#" class="dropdown-item">Staff</a>
                  <a href="#" class="dropdown-item">Customers</a>
                  <div class="dropdown-divider"></div>
                  <a href="#" class="dropdown-item">Active</a>
                  <a href="#" class="dropdown-item">Inactive</a>
                </div>
              </div>
              <button class="btn btn-sm btn-light">
                <i class="fas fa-download"></i> Export
              </button>
            </div>
          </div>
          <div class="card-body">
            <div class="table-container">
              <table class="table">
                <thead>
                  <tr>
                    <th>ID</th>
                    <th>User</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Status</th>
                    <th>Last Active</th>
                    <th>Actions</th>
                  </tr>
                </thead>
                <tbody>
                  <?php foreach ($users as $user): ?>
                  <tr>
                    <td><?php echo $user['id']; ?></td>
                    <td>
                      <div class="d-flex align-center gap-2">
                        <div
                          class="user-avatar"
                          style="width: 40px; height: 40px; font-size: 14px"
                        >
                          <?php echo strtoupper(substr($user['first_name'], 0, 1) . substr($user['last_name'], 0, 1)); ?>
                        </div>
                        <div>
                          <div class="fw-bold"><?php echo htmlspecialchars($user['first_name'] . ' ' . $user['last_name']); ?></div>
                          <div class="text-gray" style="font-size: 0.85rem">
                            Joined: <?php echo formatDate($user['created_at']); ?>
                          </div>
                        </div>
                      </div>
                    </td>
                    <td><?php echo htmlspecialchars($user['email']); ?></td>
                    <td>
                      <span class="badge <?php echo $user['role'] === 'Admin' ? 'bg-primary' : ($user['role'] === 'Staff' ? 'bg-secondary' : 'bg-info'); ?>">
                        <?php echo $user['role']; ?>
                      </span>
                    </td>
                    <td>
                      <span class="badge <?php echo $user['status'] === 'Active' ? 'badge-success' : 'badge-danger'; ?>">
                        <?php echo $user['status']; ?>
                      </span>
                    </td>
                    <td><?php echo $user['last_active'] ? formatDate($user['last_active'], 'M d, Y g:i A') : 'Never'; ?></td>
                    <td>
                      <div class="d-flex gap-1">
                        <button
                          class="btn btn-sm btn-light"
                          title="View Profile"
                          onclick="viewUser(<?php echo $user['id']; ?>)"
                        >
                          <i class="fas fa-eye"></i>
                        </button>
                        <button
                          class="btn btn-sm btn-secondary"
                          title="Edit User"
                          onclick="editUser(<?php echo $user['id']; ?>)"
                        >
                          <i class="fas fa-edit"></i>
                        </button>
                        <button
                          class="btn btn-sm <?php echo $user['status'] === 'Active' ? 'btn-warning' : 'btn-success'; ?>"
                          title="<?php echo $user['status'] === 'Active' ? 'Deactivate' : 'Activate'; ?> User"
                          onclick="toggleUserStatus(<?php echo $user['id']; ?>, '<?php echo $user['status']; ?>')"
                        >
                          <i class="fas fa-<?php echo $user['status'] === 'Active' ? 'user-slash' : 'user-check'; ?>"></i>
                        </button>
                        <button
                          class="btn btn-sm btn-danger"
                          title="Delete User"
                          onclick="deleteUser(<?php echo $user['id']; ?>)"
                        >
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

    <!-- Admin JavaScript -->
    <script src="../../js/admin.js"></script>

    <script>
      // User management functions
      function viewUser(userId) {
        // Redirect to user profile page
        window.location.href = `../profile.php?id=${userId}`;
      }

      function editUser(userId) {
        // Redirect to edit user page
        window.location.href = `add-user.php?edit=${userId}`;
      }

      function deleteUser(userId) {
        if (confirm('Are you sure you want to delete this user? This action cannot be undone.')) {
          $.ajax({
            url: '',
            method: 'POST',
            data: {
              action: 'delete',
              id: userId
            },
            dataType: 'json',
            success: function(response) {
              if (response.success) {
                showNotification('User deleted successfully!', 'success');
                setTimeout(() => {
                  location.reload();
                }, 1500);
              } else {
                showNotification('Failed to delete user. Please try again.', 'error');
              }
            },
            error: function() {
              showNotification('An error occurred. Please try again.', 'error');
            }
          });
        }
      }

      function toggleUserStatus(userId, currentStatus) {
        const action = currentStatus === 'Active' ? 'deactivate' : 'activate';
        if (confirm(`Are you sure you want to ${action} this user?`)) {
          $.ajax({
            url: '',
            method: 'POST',
            data: {
              action: 'toggle_status',
              id: userId
            },
            dataType: 'json',
            success: function(response) {
              if (response.success) {
                showNotification(`User ${action}d successfully!`, 'success');
                setTimeout(() => {
                  location.reload();
                }, 1500);
              } else {
                showNotification(`Failed to ${action} user. Please try again.`, 'error');
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
