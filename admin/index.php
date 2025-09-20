<?php
session_start();

// Include database configuration and functions
require_once '../config/database.php';
require_once '../includes/functions.php';

// Require admin authentication
requireAdminAuth();

// Get current admin user
$currentUser = getCurrentAdminUser();

// Get dashboard statistics
$stats = getDashboardStats();
$recentActivity = getRecentActivity();

/**
 * Get dashboard statistics
 */
function getDashboardStats() {
    $mysqli = getDatabaseConnection();
    if (!$mysqli) return [];

    $stats = [];

    // Users statistics
    $result = $mysqli->query("SELECT COUNT(*) as total,
                             SUM(CASE WHEN status = 'Active' THEN 1 ELSE 0 END) as active,
                             SUM(CASE WHEN role = 'Admin' OR role = 'Super Admin' THEN 1 ELSE 0 END) as admins
                             FROM users");
    $stats['users'] = $result ? $result->fetch_assoc() : ['total' => 0, 'active' => 0, 'admins' => 0];

    // Products statistics
    $result = $mysqli->query("SELECT COUNT(*) as total,
                             SUM(CASE WHEN status = 'Active' THEN 1 ELSE 0 END) as active,
                             SUM(CASE WHEN stock_quantity <= 10 THEN 1 ELSE 0 END) as low_stock
                             FROM products");
    $stats['products'] = $result ? $result->fetch_assoc() : ['total' => 0, 'active' => 0, 'low_stock' => 0];

    // Services statistics
    $result = $mysqli->query("SELECT COUNT(*) as total,
                             SUM(CASE WHEN status = 'Active' THEN 1 ELSE 0 END) as active
                             FROM services");
    $stats['services'] = $result ? $result->fetch_assoc() : ['total' => 0, 'active' => 0];

    // Research projects statistics
    $result = $mysqli->query("SELECT COUNT(*) as total,
                             SUM(CASE WHEN status = 'In Progress' THEN 1 ELSE 0 END) as in_progress,
                             SUM(CASE WHEN status = 'Completed' THEN 1 ELSE 0 END) as completed
                             FROM research_projects");
    $stats['research'] = $result ? $result->fetch_assoc() : ['total' => 0, 'in_progress' => 0, 'completed' => 0];

    // Training programs statistics
    $result = $mysqli->query("SELECT COUNT(*) as total,
                             SUM(CASE WHEN status = 'Active' THEN 1 ELSE 0 END) as active
                             FROM training_programs");
    $stats['training'] = $result ? $result->fetch_assoc() : ['total' => 0, 'active' => 0];

    // Messages statistics
    $result = $mysqli->query("SELECT COUNT(*) as total,
                             SUM(CASE WHEN status = 'Unread' THEN 1 ELSE 0 END) as unread
                             FROM messages");
    $stats['messages'] = $result ? $result->fetch_assoc() : ['total' => 0, 'unread' => 0];

    return $stats;
}

/**
 * Get recent activity data
 */
function getRecentActivity() {
    $mysqli = getDatabaseConnection();
    if (!$mysqli) return [];

    $activity = [];

    // Recent users (last 5)
    $result = $mysqli->query("SELECT first_name, last_name, email, role, created_at
                             FROM users ORDER BY created_at DESC LIMIT 5");
    $activity['users'] = $result ? $result->fetch_all(MYSQLI_ASSOC) : [];

    // Recent products (last 5)
    $result = $mysqli->query("SELECT name, price, status, created_at
                             FROM products ORDER BY created_at DESC LIMIT 5");
    $activity['products'] = $result ? $result->fetch_all(MYSQLI_ASSOC) : [];

    // Recent messages (last 5)
    $result = $mysqli->query("SELECT sender_name, subject, status, created_at
                             FROM messages ORDER BY created_at DESC LIMIT 5");
    $activity['messages'] = $result ? $result->fetch_all(MYSQLI_ASSOC) : [];

    // Recent research projects (last 5)
    $result = $mysqli->query("SELECT title, status, created_at
                             FROM research_projects ORDER BY created_at DESC LIMIT 5");
    $activity['research'] = $result ? $result->fetch_all(MYSQLI_ASSOC) : [];

    return $activity;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard | Fair Surveying & Mapping Ltd</title>
    <link rel="icon" type="image/svg+xml" href="../images/logo.png">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <!-- Admin CSS -->
    <link rel="stylesheet" href="css/admin.css">
</head>
<body>
    <div class="admin-layout">
        <!-- Sidebar -->
        <aside class="admin-sidebar">
          <div class="sidebar-logo">
              <div class="logo-icon">
              <i class="fas fa-map-marked-alt"></i>
              </div>
              <div class="logo-text">FSMC Admin</div>
          </div>

          <nav class="nav-menu">
              <ul>
              <li class="nav-item">
                  <a href="./index.php" class="nav-link">
                  <span class="nav-icon"
                      ><i class="fas fa-tachometer-alt"></i
                  ></span>
                  <span class="nav-text">Dashboard</span>
                  </a>
              </li>

              <li class="nav-item">
                  <a href="#" class="nav-link nav-dropdown-toggle">
                  <span class="nav-icon"><i class="fas fa-users"></i></span>
                  <span class="nav-text">Users</span>
                  <span class="nav-arrow"
                      ><i class="fas fa-chevron-right"></i
                  ></span>
                  </a>
                  <div class="nav-dropdown">
                  <a href="./pages/users/all-users.php" class="dropdown-item">All Users</a>
                  <a href="./pages/users/add-user.php" class="dropdown-item">Add User</a>
                  <a href="./pages/users/user-roles.php" class="dropdown-item">User Roles</a>
                  </div>
              </li>

              <li class="nav-item">
                  <a href="#" class="nav-link nav-dropdown-toggle">
                  <span class="nav-icon"><i class="fas fa-cogs"></i></span>
                  <span class="nav-text">Services</span>
                  <span class="nav-arrow"
                      ><i class="fas fa-chevron-right"></i
                  ></span>
                  </a>
                  <div class="nav-dropdown">
                  <a href="./pages/services/all-services.php" class="dropdown-item"
                      >All Services</a
                  >
                  <a href="./pages/services/add-service.php" class="dropdown-item"
                      >Add Service</a
                  >
                  <a
                      href="./pages/services/service-categories.php"
                      class="dropdown-item"
                      >Categories</a
                  >
                  </div>
              </li>

              <li class="nav-item">
                  <a href="#" class="nav-link nav-dropdown-toggle">
                  <span class="nav-icon"><i class="fas fa-box"></i></span>
                  <span class="nav-text">Products</span>
                  <span class="nav-arrow"
                      ><i class="fas fa-chevron-right"></i
                  ></span>
                  </a>
                  <div class="nav-dropdown">
                  <a href="./pages/products/all-products.php" class="dropdown-item"
                      >All Products</a
                  >
                  <a href="./pages/products/add-product.php" class="dropdown-item"
                      >Add Product</a
                  >
                  <a
                      href="./pages/products/product-categories.php"
                      class="dropdown-item"
                      >Categories</a
                  >
                  </div>
              </li>

              <li class="nav-item">
                  <a href="#" class="nav-link nav-dropdown-toggle">
                  <span class="nav-icon"><i class="fas fa-book"></i></span>
                  <span class="nav-text">Trainings</span>
                  <span class="nav-arrow"
                      ><i class="fas fa-chevron-right"></i
                  ></span>
                  </a>
                  <div class="nav-dropdown">
                  <a href="./pages/trainings/all-trainings.php" class="dropdown-item"
                      >All Trainings</a
                  >
                  <a href="./pages/trainings/add-training.php" class="dropdown-item"
                      >Add Training</a
                  >
                  <a href="./pages/trainings/categories.php" class="dropdown-item"
                      >Categories</a
                  >
                  </div>
              </li>

              <li class="nav-item">
                  <a href="#" class="nav-link nav-dropdown-toggle">
                  <span class="nav-icon"><i class="fas fa-flask"></i></span>
                  <span class="nav-text">Research</span>
                  <span class="nav-arrow"
                      ><i class="fas fa-chevron-right"></i
                  ></span>
                  </a>
                  <div class="nav-dropdown">
                  <a href="./pages/research/all-research.php" class="dropdown-item"
                      >All Research</a
                  >
                  <a href="./pages/research/add-research.php" class="dropdown-item"
                      >Add Research</a
                  >
                  </div>
              </li>

              <li class="nav-item">
                  <a href="#" class="nav-link nav-dropdown-toggle">
                  <span class="nav-icon"><i class="fas fa-envelope"></i></span>
                  <span class="nav-text">Messages</span>
                  <span class="nav-arrow"
                      ><i class="fas fa-chevron-right"></i
                  ></span>
                  </a>
                  <div class="nav-dropdown">
                  <a href="./pages/messages/inbox.php" class="dropdown-item">Inbox</a>
                  <a href="./pages/messages/sent.php" class="dropdown-item">Sent</a>
                  </div>
              </li>
              </ul>
          </nav>
        </aside>

        <?php
        // Get current user if not already set
        if (!isset($currentUser)) {
            $currentUser = getCurrentAdminUser();
        }
        ?>
        <!-- Header -->
        <header class="admin-header">
            <button class="toggle-sidebar" aria-label="Toggle navigation">
                <div class="hamburger">
                    <span></span>
                    <span></span>
                    <span></span>
                </div>
            </button>

            <div class="header-title">
                <?php
                $pageTitle = 'Dashboard';
                if (isset($_GET['page'])) {
                    $pageTitle = ucfirst($_GET['page']);
                } else {
                    $currentPage = basename($_SERVER['PHP_SELF'], '.php');
                    switch($currentPage) {
                        case 'all-users':
                            $pageTitle = 'Users Management';
                            break;
                        case 'add-user':
                            $pageTitle = 'Add User';
                            break;
                        case 'all-products':
                            $pageTitle = 'Products Management';
                            break;
                        case 'add-product':
                            $pageTitle = 'Add Product';
                            break;
                        case 'all-services':
                            $pageTitle = 'Services Management';
                            break;
                        case 'add-service':
                            $pageTitle = 'Add Service';
                            break;
                        case 'all-research':
                            $pageTitle = 'Research Projects';
                            break;
                        case 'add-research':
                            $pageTitle = 'Add Research Project';
                            break;
                        case 'all-trainings':
                            $pageTitle = 'Training Programs';
                            break;
                        case 'add-training':
                            $pageTitle = 'Add Training Program';
                            break;
                        case 'inbox':
                            $pageTitle = 'Messages Inbox';
                            break;
                        default:
                            $pageTitle = 'Dashboard';
                    }
                }
                echo htmlspecialchars($pageTitle);
                ?>
            </div>

            <div class="header-controls">
                <!-- User Dropdown -->
                <div class="admin-user">
                    <div class="user-dropdown">
                        <div class="user-avatar">
                            <?php if ($currentUser && !empty($currentUser['avatar'])): ?>
                                <img src="<?php echo htmlspecialchars($currentUser['avatar']); ?>" alt="User Avatar">
                            <?php else: ?>
                                <i class="fas fa-user"></i>
                            <?php endif; ?>
                        </div>
                        <div class="user-name">
                            <?php echo $currentUser ? htmlspecialchars($currentUser['name']) : 'Admin'; ?>
                        </div>
                        <i class="fas fa-chevron-down"></i>

                        <div class="user-menu">
                            <div class="user-menu-header">
                                <div class="user-info">
                                    <div class="user-name"><?php echo $currentUser ? htmlspecialchars($currentUser['name']) : 'Admin'; ?></div>
                                    <div class="user-email"><?php echo $currentUser ? htmlspecialchars($currentUser['email']) : ''; ?></div>
                                    <div class="user-role">
                                        <span class="badge badge-primary">
                                            <?php echo $currentUser ? htmlspecialchars($currentUser['role']) : 'Admin'; ?>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="dropdown-divider"></div>
                            <a href="./pages/profile.php" class="user-menu-item">
                                <i class="fas fa-user-circle"></i>
                                <span>Profile</span>
                            </a>
                            <div class="dropdown-divider"></div>
                            <a href="./logout.php" class="user-menu-item">
                                <i class="fas fa-sign-out-alt"></i>
                                <span>Logout</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <div class="sidebar-overlay"></div>

        <!-- Main Content -->
        <main class="admin-main animate-fadeIn">
            <!-- Welcome Section -->
            <div class="welcome-section mb-4">
                <div class="d-flex justify-between align-center">
                    <div>
                        <h1 class="page-title">Welcome back, <?php echo htmlspecialchars($currentUser['name']); ?>!</h1>
                        <p class="page-subtitle">Here's what's happening with your business today.</p>
                    </div>
                    <div class="welcome-actions">
                        <span class="badge badge-primary"><?php echo htmlspecialchars($currentUser['role']); ?></span>
                        <span class="text-muted ms-2"><?php echo date('F j, Y'); ?></span>
                    </div>
                </div>
            </div>

            <!-- Statistics Cards -->
            <div class="row mb-4">
                <div class="col-lg-3 col-md-6 mb-3">
                    <div class="stats-card">
                        <div class="stats-icon bg-primary">
                            <i class="fas fa-users"></i>
                        </div>
                        <div class="stats-content">
                            <h3><?php echo $stats['users']['total']; ?></h3>
                            <p>Total Users</p>
                            <small class="text-success">
                                <i class="fas fa-check-circle"></i>
                                <?php echo $stats['users']['active']; ?> Active
                            </small>
                        </div>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6 mb-3">
                    <div class="stats-card">
                        <div class="stats-icon bg-success">
                            <i class="fas fa-box"></i>
                        </div>
                        <div class="stats-content">
                            <h3><?php echo $stats['products']['total']; ?></h3>
                            <p>Products</p>
                            <small class="text-warning">
                                <i class="fas fa-exclamation-triangle"></i>
                                <?php echo $stats['products']['low_stock']; ?> Low Stock
                            </small>
                        </div>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6 mb-3">
                    <div class="stats-card">
                        <div class="stats-icon bg-info">
                            <i class="fas fa-cogs"></i>
                        </div>
                        <div class="stats-content">
                            <h3><?php echo $stats['services']['total']; ?></h3>
                            <p>Services</p>
                            <small class="text-success">
                                <i class="fas fa-check-circle"></i>
                                <?php echo $stats['services']['active']; ?> Active
                            </small>
                        </div>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6 mb-3">
                    <div class="stats-card">
                        <div class="stats-icon bg-warning">
                            <i class="fas fa-envelope"></i>
                        </div>
                        <div class="stats-content">
                            <h3><?php echo $stats['messages']['total']; ?></h3>
                            <p>Messages</p>
                            <small class="text-danger">
                                <i class="fas fa-envelope-open"></i>
                                <?php echo $stats['messages']['unread']; ?> Unread
                            </small>
                        </div>
                    </div>
                </div>
            <!-- Secondary Statistics -->
            <div class="row mb-4">
                <div class="col-lg-6 mb-3">
                    <div class="stats-card">
                        <div class="stats-icon bg-purple">
                            <i class="fas fa-microscope"></i>
                        </div>
                        <div class="stats-content">
                            <h3><?php echo $stats['research']['total']; ?></h3>
                            <p>Research Projects</p>
                            <small class="text-info">
                                <i class="fas fa-play-circle"></i>
                                <?php echo $stats['research']['in_progress']; ?> In Progress
                            </small>
                        </div>
                    </div>
                </div>

                <div class="col-lg-6 mb-3">
                    <div class="stats-card">
                        <div class="stats-icon bg-orange">
                            <i class="fas fa-graduation-cap"></i>
                        </div>
                        <div class="stats-content">
                            <h3><?php echo $stats['training']['total']; ?></h3>
                            <p>Training Programs</p>
                            <small class="text-success">
                                <i class="fas fa-check-circle"></i>
                                <?php echo $stats['training']['active']; ?> Active
                            </small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title">
                        <i class="fas fa-bolt"></i>
                        Quick Actions
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-3 col-md-6 mb-3">
                            <a href="pages/users/add-user.php" class="quick-action-btn">
                                <i class="fas fa-user-plus"></i>
                                <span>Add User</span>
                            </a>
                        </div>
                        <div class="col-lg-3 col-md-6 mb-3">
                            <a href="pages/products/add-product.php" class="quick-action-btn">
                                <i class="fas fa-plus-circle"></i>
                                <span>Add Product</span>
                            </a>
                        </div>
                        <div class="col-lg-3 col-md-6 mb-3">
                            <a href="pages/services/add-service.php" class="quick-action-btn">
                                <i class="fas fa-cog"></i>
                                <span>Add Service</span>
                            </a>
                        </div>
                        <div class="col-lg-3 col-md-6 mb-3">
                            <a href="pages/research/add-research.php" class="quick-action-btn">
                                <i class="fas fa-flask"></i>
                                <span>Add Research</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Activity -->
            <div class="row">
                <div class="col-lg-6 mb-4">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title">
                                <i class="fas fa-clock"></i>
                                Recent Users
                            </h5>
                        </div>
                        <div class="card-body">
                            <?php if (!empty($recentActivity['users'])): ?>
                                <div class="activity-list">
                                    <?php foreach ($recentActivity['users'] as $user): ?>
                                        <div class="activity-item">
                                            <div class="activity-icon bg-primary">
                                                <i class="fas fa-user"></i>
                                            </div>
                                            <div class="activity-content">
                                                <div class="activity-title">
                                                    <?php echo htmlspecialchars($user['first_name'] . ' ' . $user['last_name']); ?>
                                                </div>
                                                <div class="activity-description">
                                                    <?php echo htmlspecialchars($user['role']); ?> • <?php echo htmlspecialchars($user['email']); ?>
                                                </div>
                                                <div class="activity-time">
                                                    <?php echo date('M j, Y', strtotime($user['created_at'])); ?>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            <?php else: ?>
                                <p class="text-muted">No recent users found.</p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <div class="col-lg-6 mb-4">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title">
                                <i class="fas fa-envelope"></i>
                                Recent Messages
                            </h5>
                        </div>
                        <div class="card-body">
                            <?php if (!empty($recentActivity['messages'])): ?>
                                <div class="activity-list">
                                    <?php foreach ($recentActivity['messages'] as $message): ?>
                                        <div class="activity-item">
                                            <div class="activity-icon bg-warning">
                                                <i class="fas fa-envelope"></i>
                                            </div>
                                            <div class="activity-content">
                                                <div class="activity-title">
                                                    <?php echo htmlspecialchars($message['sender_name']); ?>
                                                </div>
                                                <div class="activity-description">
                                                    <?php echo htmlspecialchars($message['subject']); ?>
                                                </div>
                                                <div class="activity-time">
                                                    <?php echo date('M j, Y', strtotime($message['created_at'])); ?>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            <?php else: ?>
                                <p class="text-muted">No recent messages found.</p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <!-- Admin JavaScript -->
    <script src="js/admin.js"></script>
</body>
</html>
