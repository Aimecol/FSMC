<?php
/**
 * Admin Dashboard for FSMC Admin System
 * Created: 2025-01-22
 */

require_once 'config/config.php';

// Set page variables
$pageTitle = 'Dashboard';
$pageIcon = 'fas fa-tachometer-alt';
$pageDescription = 'Overview of your website statistics and recent activity';

// Get dashboard statistics
$stats = [
    'services' => dbGetRow("SELECT COUNT(*) as total, COUNT(CASE WHEN status = 'active' THEN 1 END) as active FROM services")['total'] ?? 0,
    'products' => dbGetRow("SELECT COUNT(*) as total, COUNT(CASE WHEN status = 'active' THEN 1 END) as active FROM products")['total'] ?? 0,
    'training' => dbGetRow("SELECT COUNT(*) as total, COUNT(CASE WHEN status = 'active' THEN 1 END) as active FROM training_programs")['total'] ?? 0,
    'research' => dbGetRow("SELECT COUNT(*) as total, COUNT(CASE WHEN status = 'published' THEN 1 END) as published FROM research_projects")['total'] ?? 0,
    'inquiries' => dbGetRow("SELECT COUNT(*) as total, COUNT(CASE WHEN status = 'new' THEN 1 END) as new FROM contact_inquiries")['total'] ?? 0,
    'enrollments' => dbGetRow("SELECT COUNT(*) as total, COUNT(CASE WHEN enrollment_status = 'pending' THEN 1 END) as pending FROM training_enrollments")['total'] ?? 0
];

// Get recent activity
$recentInquiries = dbGetRows(
    "SELECT id, name, email, subject, type, status, created_at 
     FROM contact_inquiries 
     ORDER BY created_at DESC 
     LIMIT 5"
);

$recentEnrollments = dbGetRows(
    "SELECT e.id, e.name, e.email, e.enrollment_status, e.created_at, 
            tp.title as program_title
     FROM training_enrollments e
     JOIN training_schedules ts ON e.schedule_id = ts.id
     JOIN training_programs tp ON ts.program_id = tp.id
     ORDER BY e.created_at DESC 
     LIMIT 5"
);

$recentActivity = dbGetRows(
    "SELECT al.action, al.table_name, al.created_at, au.full_name
     FROM activity_logs al
     LEFT JOIN admin_users au ON al.user_id = au.id
     ORDER BY al.created_at DESC
     LIMIT 10"
);

include 'includes/header.php';
?>

<!-- Dashboard Stats -->
<div class="stats-grid">
    <div class="stat-card stat-primary">
        <div class="stat-header">
            <div class="stat-title">Total Services</div>
            <div class="stat-icon stat-primary">
                <i class="fas fa-concierge-bell"></i>
            </div>
        </div>
        <div class="stat-value"><?php echo number_format($stats['services']); ?></div>
        <div class="stat-change positive">
            <i class="fas fa-arrow-up"></i>
            <span>Active services available</span>
        </div>
    </div>
    
    <div class="stat-card stat-success">
        <div class="stat-header">
            <div class="stat-title">Products</div>
            <div class="stat-icon stat-success">
                <i class="fas fa-box"></i>
            </div>
        </div>
        <div class="stat-value"><?php echo number_format($stats['products']); ?></div>
        <div class="stat-change positive">
            <i class="fas fa-arrow-up"></i>
            <span>Equipment & software</span>
        </div>
    </div>
    
    <div class="stat-card stat-warning">
        <div class="stat-header">
            <div class="stat-title">Training Programs</div>
            <div class="stat-icon stat-warning">
                <i class="fas fa-graduation-cap"></i>
            </div>
        </div>
        <div class="stat-value"><?php echo number_format($stats['training']); ?></div>
        <div class="stat-change positive">
            <i class="fas fa-arrow-up"></i>
            <span>Available courses</span>
        </div>
    </div>
    
    <div class="stat-card stat-danger">
        <div class="stat-header">
            <div class="stat-title">Research Projects</div>
            <div class="stat-icon stat-danger">
                <i class="fas fa-microscope"></i>
            </div>
        </div>
        <div class="stat-value"><?php echo number_format($stats['research']); ?></div>
        <div class="stat-change positive">
            <i class="fas fa-arrow-up"></i>
            <span>Published research</span>
        </div>
    </div>
    
    <div class="stat-card stat-primary">
        <div class="stat-header">
            <div class="stat-title">New Inquiries</div>
            <div class="stat-icon stat-primary">
                <i class="fas fa-envelope"></i>
            </div>
        </div>
        <div class="stat-value"><?php echo number_format($stats['inquiries']); ?></div>
        <div class="stat-change">
            <i class="fas fa-clock"></i>
            <span>Awaiting response</span>
        </div>
    </div>
    
    <div class="stat-card stat-success">
        <div class="stat-header">
            <div class="stat-title">Enrollments</div>
            <div class="stat-icon stat-success">
                <i class="fas fa-user-graduate"></i>
            </div>
        </div>
        <div class="stat-value"><?php echo number_format($stats['enrollments']); ?></div>
        <div class="stat-change">
            <i class="fas fa-users"></i>
            <span>Training registrations</span>
        </div>
    </div>
</div>

<!-- Dashboard Content -->
<div class="row">
    <!-- Recent Inquiries -->
    <div class="col-6">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-envelope"></i>
                    Recent Inquiries
                </h3>
            </div>
            <div class="card-body">
                <?php if (empty($recentInquiries)): ?>
                    <p class="text-muted text-center">No recent inquiries</p>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Subject</th>
                                    <th>Type</th>
                                    <th>Status</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($recentInquiries as $inquiry): ?>
                                <tr>
                                    <td>
                                        <strong><?php echo htmlspecialchars($inquiry['name']); ?></strong><br>
                                        <small class="text-muted"><?php echo htmlspecialchars($inquiry['email']); ?></small>
                                    </td>
                                    <td><?php echo htmlspecialchars(substr($inquiry['subject'], 0, 30)) . (strlen($inquiry['subject']) > 30 ? '...' : ''); ?></td>
                                    <td>
                                        <span class="badge badge-<?php echo $inquiry['type'] === 'general' ? 'secondary' : 'primary'; ?>">
                                            <?php echo ucfirst($inquiry['type']); ?>
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge badge-<?php 
                                            echo $inquiry['status'] === 'new' ? 'danger' : 
                                                ($inquiry['status'] === 'read' ? 'warning' : 'success'); 
                                        ?>">
                                            <?php echo ucfirst($inquiry['status']); ?>
                                        </span>
                                    </td>
                                    <td>
                                        <small><?php echo date('M j, Y', strtotime($inquiry['created_at'])); ?></small>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
            <div class="card-footer">
                <a href="inquiries.php" class="btn btn-primary btn-sm">
                    <i class="fas fa-eye"></i> View All Inquiries
                </a>
            </div>
        </div>
    </div>
    
    <!-- Recent Enrollments -->
    <div class="col-6">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-user-graduate"></i>
                    Recent Enrollments
                </h3>
            </div>
            <div class="card-body">
                <?php if (empty($recentEnrollments)): ?>
                    <p class="text-muted text-center">No recent enrollments</p>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Student</th>
                                    <th>Program</th>
                                    <th>Status</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($recentEnrollments as $enrollment): ?>
                                <tr>
                                    <td>
                                        <strong><?php echo htmlspecialchars($enrollment['name']); ?></strong><br>
                                        <small class="text-muted"><?php echo htmlspecialchars($enrollment['email']); ?></small>
                                    </td>
                                    <td><?php echo htmlspecialchars(substr($enrollment['program_title'], 0, 30)) . (strlen($enrollment['program_title']) > 30 ? '...' : ''); ?></td>
                                    <td>
                                        <span class="badge badge-<?php 
                                            echo $enrollment['enrollment_status'] === 'pending' ? 'warning' : 
                                                ($enrollment['enrollment_status'] === 'confirmed' ? 'success' : 'secondary'); 
                                        ?>">
                                            <?php echo ucfirst($enrollment['enrollment_status']); ?>
                                        </span>
                                    </td>
                                    <td>
                                        <small><?php echo date('M j, Y', strtotime($enrollment['created_at'])); ?></small>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
            <div class="card-footer">
                <a href="enrollments.php" class="btn btn-success btn-sm">
                    <i class="fas fa-eye"></i> View All Enrollments
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Recent Activity -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-list-alt"></i>
                    Recent Activity
                </h3>
            </div>
            <div class="card-body">
                <?php if (empty($recentActivity)): ?>
                    <p class="text-muted text-center">No recent activity</p>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>User</th>
                                    <th>Action</th>
                                    <th>Table</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($recentActivity as $activity): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($activity['full_name'] ?? 'System'); ?></td>
                                    <td>
                                        <span class="badge badge-info">
                                            <?php echo htmlspecialchars($activity['action']); ?>
                                        </span>
                                    </td>
                                    <td><?php echo htmlspecialchars($activity['table_name'] ?? 'N/A'); ?></td>
                                    <td>
                                        <small><?php echo date('M j, Y H:i', strtotime($activity['created_at'])); ?></small>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
            <div class="card-footer">
                <a href="logs.php" class="btn btn-secondary btn-sm">
                    <i class="fas fa-eye"></i> View All Activity
                </a>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
