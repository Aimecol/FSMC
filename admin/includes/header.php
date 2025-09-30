<?php
/**
 * Admin Header Template for FSMC Admin System
 * Created: 2025-01-22
 */

// Ensure user is authenticated
requireAuth();

$currentUser = getCurrentUser();
$currentPage = basename($_SERVER['PHP_SELF'], '.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($pageTitle) ? $pageTitle . ' - ' : ''; ?>FSMC Admin</title>
    <link rel="icon" type="image/png" href="../images/logo.png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <link rel="stylesheet" href="assets/css/admin.css">
    <?php if (isset($additionalCSS)): ?>
        <?php foreach ($additionalCSS as $css): ?>
            <link rel="stylesheet" href="<?php echo $css; ?>">
        <?php endforeach; ?>
    <?php endif; ?>
</head>
<body class="admin-body">
    <!-- Top Navigation -->
    <nav class="top-nav">
        <div class="nav-left">
            <button class="sidebar-toggle" id="sidebarToggle">
                <i class="fas fa-bars"></i>
            </button>
            <div class="logo">
                <img src="../images/logo.png" alt="FSMC" class="logo-img">
                <span class="logo-text">FSMC Admin</span>
            </div>
        </div>
        
        <div class="nav-right">            
            <!-- User Menu -->
            <div class="nav-item dropdown" id="userDropdown">
                <button class="nav-btn user-btn" data-toggle="dropdown">
                    <div class="user-avatar">
                        <i class="fas fa-user"></i>
                    </div>
                    <span class="user-name"><?php echo htmlspecialchars($currentUser['full_name']); ?></span>
                    <i class="fas fa-chevron-down"></i>
                </button>
                <div class="dropdown-menu dropdown-menu-right">
                    <div class="dropdown-header">
                        <h6><?php echo htmlspecialchars($currentUser['full_name']); ?></h6>
                        <small><?php echo htmlspecialchars($currentUser['role']); ?></small>
                    </div>
                    <div class="dropdown-divider"></div>
                    <a href="profile.php" class="dropdown-item">
                        <i class="fas fa-user"></i> Profile
                    </a>
                    <div class="dropdown-divider"></div>
                    <a href="logout.php?token=<?php echo generateCSRFToken(); ?>" class="dropdown-item">
                        <i class="fas fa-sign-out-alt"></i> Logout
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Sidebar -->
    <aside class="sidebar" id="sidebar">
        <div class="sidebar-content">
            <nav class="sidebar-nav">
                <ul class="nav-list">
                    <li class="nav-item">
                        <a href="index.php" class="nav-link <?php echo $currentPage === 'index' ? 'active' : ''; ?>">
                            <i class="fas fa-tachometer-alt"></i>
                            <span>Dashboard</span>
                        </a>
                    </li>
                    
                    <li class="nav-section">
                        <span class="nav-section-title">Content Management</span>
                    </li>
                    
                    <li class="nav-item">
                        <a href="services.php" class="nav-link <?php echo $currentPage === 'services' ? 'active' : ''; ?>">
                            <i class="fas fa-concierge-bell"></i>
                            <span>Services</span>
                        </a>
                    </li>
                    
                    <li class="nav-item">
                        <a href="products.php" class="nav-link <?php echo $currentPage === 'products' ? 'active' : ''; ?>">
                            <i class="fas fa-box"></i>
                            <span>Products</span>
                        </a>
                    </li>
                    
                    <li class="nav-item">
                        <a href="training.php" class="nav-link <?php echo $currentPage === 'training' ? 'active' : ''; ?>">
                            <i class="fas fa-graduation-cap"></i>
                            <span>Training</span>
                        </a>
                    </li>
                    
                    <li class="nav-item">
                        <a href="research.php" class="nav-link <?php echo $currentPage === 'research' ? 'active' : ''; ?>">
                            <i class="fas fa-microscope"></i>
                            <span>Research</span>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="about_manage.php" class="nav-link <?php echo $currentPage === 'about_manage' ? 'active' : ''; ?>">
                            <i class="fas fa-info-circle"></i>
                            <span>About</span>
                        </a>
                    </li>
                    
                    <li class="nav-item">
                        <a href="contact_manage.php" class="nav-link <?php echo $currentPage === 'contact_manage' ? 'active' : ''; ?>">
                            <i class="fas fa-info-circle"></i>
                            <span>Contact</span>
                        </a>
                    </li>
                    
                    <li class="nav-section">
                        <span class="nav-section-title">Communications</span>
                    </li>
                    
                    <li class="nav-item">
                        <a href="inquiries.php" class="nav-link <?php echo $currentPage === 'inquiries' ? 'active' : ''; ?>">
                            <i class="fas fa-envelope"></i>
                            <span>Inquiries</span>
                            <span class="badge badge-primary">5</span>
                        </a>
                    </li>
                    

                    
                    <li class="nav-section">
                        <span class="nav-section-title">Media & Files</span>
                    </li>
                    
                    <li class="nav-item">
                        <a href="media.php" class="nav-link <?php echo $currentPage === 'media' ? 'active' : ''; ?>">
                            <i class="fas fa-images"></i>
                            <span>Media Library</span>
                        </a>
                    </li>
                    
                    <?php if (hasPermission('delete')): ?>
                    <li class="nav-section">
                        <span class="nav-section-title">System</span>
                    </li>
                    
                    <li class="nav-item">
                        <a href="users.php" class="nav-link <?php echo $currentPage === 'users' ? 'active' : ''; ?>">
                            <i class="fas fa-users"></i>
                            <span>Users</span>
                        </a>
                    </li>
                    
                    <li class="nav-item">
                        <a href="settings.php" class="nav-link <?php echo $currentPage === 'settings' ? 'active' : ''; ?>">
                            <i class="fas fa-cog"></i>
                            <span>Settings</span>
                        </a>
                    </li>
                    
                    <li class="nav-item">
                        <a href="logs.php" class="nav-link <?php echo $currentPage === 'logs' ? 'active' : ''; ?>">
                            <i class="fas fa-list-alt"></i>
                            <span>Activity Logs</span>
                        </a>
                    </li>
                    <?php endif; ?>
                </ul>
            </nav>
        </div>
        
        <div class="sidebar-footer">
            <div class="sidebar-user">
                <div class="user-avatar">
                    <i class="fas fa-user"></i>
                </div>
                <div class="user-info">
                    <div class="user-name"><?php echo htmlspecialchars($currentUser['username']); ?></div>
                    <div class="user-role"><?php echo htmlspecialchars($currentUser['role']); ?></div>
                </div>
            </div>
        </div>
    </aside>

    <!-- Main Content -->
    <main class="main-content" id="mainContent">
        <div class="content-wrapper">
            <!-- Breadcrumb -->
            <?php if (isset($breadcrumbs) && !empty($breadcrumbs)): ?>
            <nav class="breadcrumb-nav">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="index.php"><i class="fas fa-home"></i> Dashboard</a>
                    </li>
                    <?php foreach ($breadcrumbs as $crumb): ?>
                        <?php if (isset($crumb['url'])): ?>
                            <li class="breadcrumb-item">
                                <a href="<?php echo $crumb['url']; ?>"><?php echo htmlspecialchars($crumb['title']); ?></a>
                            </li>
                        <?php else: ?>
                            <li class="breadcrumb-item active"><?php echo htmlspecialchars($crumb['title']); ?></li>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </ol>
            </nav>
            <?php endif; ?>
            
            <!-- Page Header -->
            <?php if (isset($pageTitle)): ?>
            <div class="page-header">
                <div class="page-header-content">
                    <h1 class="page-title">
                        <?php if (isset($pageIcon)): ?>
                            <i class="<?php echo $pageIcon; ?>"></i>
                        <?php endif; ?>
                        <?php echo htmlspecialchars($pageTitle); ?>
                    </h1>
                    <?php if (isset($pageDescription)): ?>
                        <p class="page-description"><?php echo htmlspecialchars($pageDescription); ?></p>
                    <?php endif; ?>
                </div>
                <?php if (isset($pageActions)): ?>
                    <div class="page-actions">
                        <?php echo $pageActions; ?>
                    </div>
                <?php endif; ?>
            </div>
            <?php endif; ?>
            
            <!-- Flash Messages -->
            <?php 
            $successMessage = getSuccessMessage();
            $errorMessage = getErrorMessage();
            ?>
            <?php if ($successMessage): ?>
                <div class="alert alert-success alert-dismissible">
                    <i class="fas fa-check-circle"></i>
                    <?php echo htmlspecialchars($successMessage); ?>
                    <button type="button" class="alert-close" data-dismiss="alert">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            <?php endif; ?>
            
            <?php if ($errorMessage): ?>
                <div class="alert alert-error alert-dismissible">
                    <i class="fas fa-exclamation-triangle"></i>
                    <?php echo htmlspecialchars($errorMessage); ?>
                    <button type="button" class="alert-close" data-dismiss="alert">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            <?php endif; ?>
            
            <!-- Page Content -->
            <div class="page-content">
