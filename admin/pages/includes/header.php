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
        <!-- Notifications -->
        <div class="notification-dropdown">
            <button class="notification-btn" aria-label="Notifications">
                <i class="fas fa-bell"></i>
                <span class="notification-badge">3</span>
            </button>
            <div class="notification-menu">
                <div class="notification-header">
                    <h6>Notifications</h6>
                    <span class="badge badge-primary">3 New</span>
                </div>
                <div class="notification-list">
                    <div class="notification-item">
                        <div class="notification-icon bg-primary">
                            <i class="fas fa-user"></i>
                        </div>
                        <div class="notification-content">
                            <div class="notification-title">New user registered</div>
                            <div class="notification-time">2 minutes ago</div>
                        </div>
                    </div>
                    <div class="notification-item">
                        <div class="notification-icon bg-warning">
                            <i class="fas fa-envelope"></i>
                        </div>
                        <div class="notification-content">
                            <div class="notification-title">New message received</div>
                            <div class="notification-time">5 minutes ago</div>
                        </div>
                    </div>
                    <div class="notification-item">
                        <div class="notification-icon bg-success">
                            <i class="fas fa-check"></i>
                        </div>
                        <div class="notification-content">
                            <div class="notification-title">Product updated successfully</div>
                            <div class="notification-time">10 minutes ago</div>
                        </div>
                    </div>
                </div>
                <div class="notification-footer">
                    <a href="#" class="btn btn-sm btn-primary">View All</a>
                </div>
            </div>
        </div>

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
                    <a href="../profile.php" class="user-menu-item">
                        <i class="fas fa-user-circle"></i>
                        <span>Profile</span>
                    </a>
                    <a href="../settings.php" class="user-menu-item">
                        <i class="fas fa-cog"></i>
                        <span>Settings</span>
                    </a>
                    <div class="dropdown-divider"></div>
                    <a href="../../logout.php" class="user-menu-item">
                        <i class="fas fa-sign-out-alt"></i>
                        <span>Logout</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
</header>

<div class="sidebar-overlay"></div>
