<?php
// Include required files if not already included
if (!function_exists('getCurrentAdminUser')) {
    require_once __DIR__ . '/../../../includes/functions.php';
}

// Get current user if not already set (authentication should be done before including header)
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
                    <a href="../profile.php" class="user-menu-item">
                        <i class="fas fa-user-circle"></i>
                        <span>Profile</span>
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
