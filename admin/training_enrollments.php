<?php
/**
 * Training Enrollments Management for FSMC Admin System
 * Created: 2025-01-26
 */

require_once 'config/config.php';

// Set page variables
$pageTitle = 'Training Enrollments';
$pageIcon = 'fas fa-users';
$pageDescription = 'Manage student enrollments and track progress';

// Get filter parameters
$programId = intval($_GET['program_id'] ?? 0);
$status = sanitize($_GET['status'] ?? '');
$paymentStatus = sanitize($_GET['payment_status'] ?? '');
$search = sanitize($_GET['search'] ?? '');

// Handle actions
$action = $_GET['action'] ?? '';
$enrollmentId = intval($_GET['id'] ?? 0);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verifyCSRFToken($_POST['csrf_token'] ?? '')) {
        setErrorMessage('Invalid security token.');
        redirect('training_enrollments.php');
    }
    
    if ($action === 'update_status' && $enrollmentId) {
        $newStatus = sanitize($_POST['enrollment_status'] ?? '');
        $paymentStatus = sanitize($_POST['payment_status'] ?? '');
        $paymentAmount = floatval($_POST['payment_amount'] ?? 0);
        $notes = sanitize($_POST['notes'] ?? '');
        
        if (in_array($newStatus, ['pending', 'confirmed', 'cancelled', 'completed'])) {
            $sql = "UPDATE training_enrollments SET enrollment_status = ?, payment_status = ?, payment_amount = ?, notes = ?, updated_at = NOW() WHERE id = ?";
            if (dbExecute($sql, [$newStatus, $paymentStatus, $paymentAmount, $notes, $enrollmentId])) {
                logActivity('Enrollment Status Updated', 'training_enrollments', $enrollmentId, ['status' => $newStatus]);
                setSuccessMessage('Enrollment status updated successfully.');
            } else {
                setErrorMessage('Failed to update enrollment status.');
            }
        }
        redirect('training_enrollments.php' . ($programId ? '?program_id=' . $programId : ''));
    }
    
    if ($action === 'issue_certificate' && $enrollmentId) {
        $enrollment = dbGetRow("SELECT name, enrollment_status, certificate_issued FROM training_enrollments WHERE id = ?", [$enrollmentId]);
        if ($enrollment && $enrollment['enrollment_status'] === 'completed' && !$enrollment['certificate_issued']) {
            if (dbExecute("UPDATE training_enrollments SET certificate_issued = 1, updated_at = NOW() WHERE id = ?", [$enrollmentId])) {
                logActivity('Certificate Issued', 'training_enrollments', $enrollmentId, ['certificate_issued' => 1]);
                setSuccessMessage('Certificate has been issued to ' . $enrollment['name'] . '.');
            } else {
                setErrorMessage('Failed to issue certificate.');
            }
        } else {
            setErrorMessage('Certificate cannot be issued for this enrollment.');
        }
        redirect('training_enrollments.php' . ($programId ? '?program_id=' . $programId : ''));
    }
    
    if ($action === 'delete' && $enrollmentId) {
        $enrollment = dbGetRow("SELECT name FROM training_enrollments WHERE id = ?", [$enrollmentId]);
        if ($enrollment) {
            if (dbExecute("DELETE FROM training_enrollments WHERE id = ?", [$enrollmentId])) {
                logActivity('Enrollment Deleted', 'training_enrollments', $enrollmentId);
                setSuccessMessage('Enrollment has been deleted successfully.');
            } else {
                setErrorMessage('Failed to delete enrollment.');
            }
        }
        redirect('training_enrollments.php' . ($programId ? '?program_id=' . $programId : ''));
    }
}

// Build query conditions
$whereConditions = [];
$params = [];

if ($programId) {
    // Check if we have schedules table first
    $schedulesCheck = dbGetRow("SHOW TABLES LIKE 'training_schedules'");
    if ($schedulesCheck) {
        $whereConditions[] = "te.schedule_id IN (SELECT id FROM training_schedules WHERE program_id = ?)";
    } else {
        // If no schedules table, assume schedule_id is actually program_id
        $whereConditions[] = "te.schedule_id = ?";
    }
    $params[] = $programId;
}

if ($status) {
    $whereConditions[] = "te.enrollment_status = ?";
    $params[] = $status;
}

if ($paymentStatus) {
    $whereConditions[] = "te.payment_status = ?";
    $params[] = $paymentStatus;
}

if ($search) {
    $whereConditions[] = "(te.name LIKE ? OR te.email LIKE ? OR te.company LIKE ?)";
    $params[] = "%$search%";
    $params[] = "%$search%";
    $params[] = "%$search%";
}

$whereClause = !empty($whereConditions) ? 'WHERE ' . implode(' AND ', $whereConditions) : '';

// Check if training_schedules table exists and get its columns
$schedulesTableExists = dbGetRow("SHOW TABLES LIKE 'training_schedules'");
$scheduleColumns = [];
if ($schedulesTableExists) {
    $columns = dbGetRows("SHOW COLUMNS FROM training_schedules");
    $scheduleColumns = array_column($columns, 'Field');
}

// Get enrollments with pagination
$page = max(1, intval($_GET['page'] ?? 1));
$limit = DEFAULT_PAGE_SIZE;
$offset = ($page - 1) * $limit;

// Get total count - adapt based on table availability
if ($schedulesTableExists) {
    $totalQuery = "SELECT COUNT(*) as total 
                   FROM training_enrollments te 
                   LEFT JOIN training_schedules ts ON te.schedule_id = ts.id 
                   LEFT JOIN training_programs tp ON ts.program_id = tp.id 
                   $whereClause";
} else {
    // If no schedules table, assume schedule_id is actually program_id
    $totalQuery = "SELECT COUNT(*) as total 
                   FROM training_enrollments te 
                   LEFT JOIN training_programs tp ON te.schedule_id = tp.id 
                   $whereClause";
}
$totalResult = dbGetRow($totalQuery, $params);
$totalEnrollments = $totalResult['total'] ?? 0;
$totalPages = ceil($totalEnrollments / $limit);

// Build dynamic SELECT based on available columns
$selectFields = "te.*, tp.title as program_title, tp.category as program_category";
if ($schedulesTableExists) {
    if (in_array('start_date', $scheduleColumns)) {
        $selectFields .= ", ts.start_date";
    }
    if (in_array('end_date', $scheduleColumns)) {
        $selectFields .= ", ts.end_date";
    }
    if (in_array('schedule_type', $scheduleColumns)) {
        $selectFields .= ", ts.schedule_type";
    }
}

// Get enrollments
if ($schedulesTableExists) {
    $enrollmentsQuery = "SELECT $selectFields
                         FROM training_enrollments te 
                         LEFT JOIN training_schedules ts ON te.schedule_id = ts.id 
                         LEFT JOIN training_programs tp ON ts.program_id = tp.id 
                         $whereClause 
                         ORDER BY te.created_at DESC 
                         LIMIT $limit OFFSET $offset";
} else {
    // If no schedules table, assume schedule_id is actually program_id
    $enrollmentsQuery = "SELECT $selectFields
                         FROM training_enrollments te 
                         LEFT JOIN training_programs tp ON te.schedule_id = tp.id 
                         $whereClause 
                         ORDER BY te.created_at DESC 
                         LIMIT $limit OFFSET $offset";
}
$enrollments = dbGetRows($enrollmentsQuery, $params);

// Get programs for filter
$programs = dbGetRows("SELECT id, title FROM training_programs WHERE status = 'active' ORDER BY title ASC");

// Get selected program info if filtering by program
$selectedProgram = null;
if ($programId) {
    $selectedProgram = dbGetRow("SELECT title FROM training_programs WHERE id = ?", [$programId]);
}

// Update page actions based on context
if ($selectedProgram) {
    $pageActions = '<a href="training_enrollments.php" class="btn btn-secondary me-2"><i class="fas fa-arrow-left"></i> All Enrollments</a>';
    $pageActions .= '<a href="export_enrollments.php?program_id=' . $programId . '" class="btn btn-success me-2"><i class="fas fa-download"></i> Export Data</a>';
    $pageTitle = 'Enrollments for: ' . $selectedProgram['title'];
} else {
    $pageActions = '<a href="training.php" class="btn btn-secondary me-2"><i class="fas fa-arrow-left"></i> Back to Programs</a>';
    $pageActions .= '<a href="export_enrollments.php" class="btn btn-success"><i class="fas fa-download"></i> Export All Data</a>';
}

include 'includes/header.php';
?>

<!-- Enrollments Statistics -->
<?php if (!$programId): ?>
<div class="row mb-3">
    <?php
    $stats = dbGetRow("SELECT 
        COUNT(*) as total,
        SUM(CASE WHEN enrollment_status = 'pending' THEN 1 ELSE 0 END) as pending,
        SUM(CASE WHEN enrollment_status = 'confirmed' THEN 1 ELSE 0 END) as confirmed,
        SUM(CASE WHEN enrollment_status = 'cancelled' THEN 1 ELSE 0 END) as cancelled,
        SUM(CASE WHEN enrollment_status = 'completed' THEN 1 ELSE 0 END) as completed,
        SUM(CASE WHEN payment_status = 'paid' THEN 1 ELSE 0 END) as paid,
        SUM(CASE WHEN certificate_issued = 1 THEN 1 ELSE 0 END) as certified,
        SUM(payment_amount) as total_revenue
        FROM training_enrollments");
    ?>
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="card bg-primary text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4><?php echo number_format($stats['total'] ?? 0); ?></h4>
                        <p class="mb-0">Total Enrollments</p>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-users fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="card bg-warning text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4><?php echo number_format($stats['pending'] ?? 0); ?></h4>
                        <p class="mb-0">Pending</p>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-clock fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="card bg-success text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4><?php echo number_format($stats['confirmed'] ?? 0); ?></h4>
                        <p class="mb-0">Confirmed</p>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-check fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="card bg-info text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4><?php echo number_format($stats['completed'] ?? 0); ?></h4>
                        <p class="mb-0">Completed</p>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-graduation-cap fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row mb-3">
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="card bg-secondary text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4><?php echo number_format($stats['paid'] ?? 0); ?></h4>
                        <p class="mb-0">Paid</p>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-money-bill fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="card bg-gold text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4><?php echo number_format($stats['certified'] ?? 0); ?></h4>
                        <p class="mb-0">Certified</p>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-certificate fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-6 col-md-12">
        <div class="card">
            <div class="card-body">
                <h6 class="card-title"><i class="fas fa-chart-bar"></i> Revenue & Performance</h6>
                <div class="row">
                    <div class="col-md-6">
                        <h4 class="text-success">RWF <?php echo number_format($stats['total_revenue'] ?? 0, 0); ?></h4>
                        <small class="text-muted">Total Revenue</small>
                    </div>
                    <div class="col-md-6">
                        <h4 class="text-info"><?php echo $stats['total'] > 0 ? round(($stats['certified'] / $stats['total']) * 100, 1) : 0; ?>%</h4>
                        <small class="text-muted">Completion Rate</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>

<!-- Filters -->
<div class="card mb-3">
    <div class="card-body">
        <form method="GET" class="row align-items-end">
            <?php if ($programId): ?>
                <input type="hidden" name="program_id" value="<?php echo $programId; ?>">
            <?php endif; ?>
            
            <div class="col-md-3">
                <label for="search" class="form-label">Search Enrollments</label>
                <input type="text" id="search" name="search" class="form-control" 
                       value="<?php echo htmlspecialchars($search); ?>" 
                       placeholder="Search by name, email, or company...">
            </div>
            
            <?php if (!$programId): ?>
            <div class="col-md-2">
                <label for="program_filter" class="form-label">Program</label>
                <select id="program_filter" name="program_id" class="form-control">
                    <option value="">All Programs</option>
                    <?php foreach ($programs as $program): ?>
                        <option value="<?php echo $program['id']; ?>" <?php echo $programId == $program['id'] ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($program['title']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <?php endif; ?>
            
            <div class="col-md-2">
                <label for="status" class="form-label">Status</label>
                <select id="status" name="status" class="form-control">
                    <option value="">All Statuses</option>
                    <option value="pending" <?php echo $status === 'pending' ? 'selected' : ''; ?>>Pending</option>
                    <option value="confirmed" <?php echo $status === 'confirmed' ? 'selected' : ''; ?>>Confirmed</option>
                    <option value="cancelled" <?php echo $status === 'cancelled' ? 'selected' : ''; ?>>Cancelled</option>
                    <option value="completed" <?php echo $status === 'completed' ? 'selected' : ''; ?>>Completed</option>
                </select>
            </div>
            
            <div class="col-md-2">
                <label for="payment_status" class="form-label">Payment</label>
                <select id="payment_status" name="payment_status" class="form-control">
                    <option value="">All Payments</option>
                    <option value="pending" <?php echo $paymentStatus === 'pending' ? 'selected' : ''; ?>>Pending</option>
                    <option value="partial" <?php echo $paymentStatus === 'partial' ? 'selected' : ''; ?>>Partial</option>
                    <option value="paid" <?php echo $paymentStatus === 'paid' ? 'selected' : ''; ?>>Paid</option>
                    <option value="refunded" <?php echo $paymentStatus === 'refunded' ? 'selected' : ''; ?>>Refunded</option>
                </select>
            </div>
            
            <div class="col-md-3">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-search"></i> Search
                </button>
                <a href="training_enrollments.php<?php echo $programId ? '?program_id=' . $programId : ''; ?>" class="btn btn-secondary">
                    <i class="fas fa-times"></i> Clear
                </a>
            </div>
        </form>
    </div>
</div>

<!-- Enrollments Table -->
<div class="card">
    <div class="card-header">
        <h3 class="card-title">
            <i class="fas fa-list"></i>
            <?php if ($selectedProgram): ?>
                Enrollments for "<?php echo htmlspecialchars($selectedProgram['title']); ?>" (<?php echo number_format($totalEnrollments); ?> total)
            <?php else: ?>
                Training Enrollments (<?php echo number_format($totalEnrollments); ?> total)
            <?php endif; ?>
        </h3>
    </div>
    <div class="card-body">
        <?php if (empty($enrollments)): ?>
            <div class="text-center py-4">
                <i class="fas fa-users fa-3x text-muted mb-3"></i>
                <h5 class="text-muted">No enrollments found</h5>
                <p class="text-muted">
                    <?php if ($selectedProgram): ?>
                        No enrollments have been received for this program yet.
                    <?php else: ?>
                        No training enrollments have been received yet.
                    <?php endif; ?>
                </p>
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Student</th>
                            <th>Program</th>
                            <th>Schedule</th>
                            <th>Experience</th>
                            <th>Payment</th>
                            <th>Status</th>
                            <th>Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($enrollments as $enrollment): ?>
                        <tr>
                            <td>
                                <div>
                                    <strong><?php echo htmlspecialchars($enrollment['name']); ?></strong><br>
                                    <small class="text-muted">
                                        <i class="fas fa-envelope"></i> <?php echo htmlspecialchars($enrollment['email']); ?>
                                    </small>
                                    <?php if ($enrollment['phone']): ?>
                                        <br><small class="text-muted">
                                            <i class="fas fa-phone"></i> <?php echo htmlspecialchars($enrollment['phone']); ?>
                                        </small>
                                    <?php endif; ?>
                                    <?php if ($enrollment['company']): ?>
                                        <br><small class="text-muted">
                                            <i class="fas fa-building"></i> <?php echo htmlspecialchars($enrollment['company']); ?>
                                        </small>
                                    <?php endif; ?>
                                </div>
                            </td>
                            <td>
                                <strong><?php echo htmlspecialchars($enrollment['program_title'] ?? 'Unknown Program'); ?></strong>
                                <?php if ($enrollment['program_category']): ?>
                                    <br><span class="badge badge-light">
                                        <?php echo ucfirst($enrollment['program_category']); ?>
                                    </span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if ($enrollment['start_date'] && $enrollment['end_date']): ?>
                                    <small>
                                        <?php echo date('M j', strtotime($enrollment['start_date'])); ?> - 
                                        <?php echo date('M j, Y', strtotime($enrollment['end_date'])); ?>
                                    </small>
                                    <?php if ($enrollment['schedule_type']): ?>
                                        <br><span class="badge badge-info">
                                            <?php echo ucfirst($enrollment['schedule_type']); ?>
                                        </span>
                                    <?php endif; ?>
                                <?php else: ?>
                                    <span class="text-muted">TBA</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <span class="badge badge-<?php 
                                    echo $enrollment['experience_level'] === 'beginner' ? 'success' : 
                                        ($enrollment['experience_level'] === 'intermediate' ? 'warning' : 'info'); 
                                ?>">
                                    <?php echo ucfirst($enrollment['experience_level']); ?>
                                </span>
                            </td>
                            <td>
                                <span class="badge badge-<?php 
                                    echo $enrollment['payment_status'] === 'paid' ? 'success' : 
                                        ($enrollment['payment_status'] === 'partial' ? 'warning' : 
                                        ($enrollment['payment_status'] === 'refunded' ? 'secondary' : 'danger')); 
                                ?>">
                                    <?php echo ucfirst($enrollment['payment_status']); ?>
                                </span>
                                <?php if ($enrollment['payment_amount'] > 0): ?>
                                    <br><small>RWF <?php echo number_format($enrollment['payment_amount'], 0); ?></small>
                                <?php endif; ?>
                            </td>
                            <td>
                                <span class="badge badge-<?php 
                                    echo $enrollment['enrollment_status'] === 'confirmed' ? 'success' : 
                                        ($enrollment['enrollment_status'] === 'pending' ? 'warning' : 
                                        ($enrollment['enrollment_status'] === 'completed' ? 'primary' : 'secondary')); 
                                ?>">
                                    <?php echo ucfirst($enrollment['enrollment_status']); ?>
                                </span>
                                <?php if ($enrollment['certificate_issued']): ?>
                                    <br><span class="badge badge-gold">
                                        <i class="fas fa-certificate"></i> Certified
                                    </span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <small><?php echo date('M j, Y', strtotime($enrollment['created_at'])); ?></small>
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <button type="button" 
                                            class="btn btn-outline-primary btn-view-enrollment" 
                                            data-enrollment-id="<?php echo $enrollment['id']; ?>"
                                            title="View Details">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    
                                    <button type="button" 
                                            class="btn btn-outline-info btn-update-status" 
                                            data-enrollment-id="<?php echo $enrollment['id']; ?>"
                                            title="Update Status">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    
                                    <button type="button" 
                                            class="btn btn-outline-danger btn-delete" 
                                            data-url="training_enrollments.php?action=delete&id=<?php echo $enrollment['id']; ?><?php echo $programId ? '&program_id=' . $programId : ''; ?>"
                                            data-name="enrollment for <?php echo htmlspecialchars($enrollment['name']); ?>"
                                            title="Delete">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            <?php if ($totalPages > 1): ?>
            <nav aria-label="Enrollments pagination">
                <ul class="pagination justify-content-center">
                    <?php if ($page > 1): ?>
                        <li class="page-item">
                            <a class="page-link" href="?page=<?php echo $page - 1; ?>&search=<?php echo urlencode($search); ?>&status=<?php echo urlencode($status); ?>&payment_status=<?php echo urlencode($paymentStatus); ?><?php echo $programId ? '&program_id=' . $programId : ''; ?>">
                                <i class="fas fa-chevron-left"></i> Previous
                            </a>
                        </li>
                    <?php endif; ?>
                    
                    <?php for ($i = max(1, $page - 2); $i <= min($totalPages, $page + 2); $i++): ?>
                        <li class="page-item <?php echo $i === $page ? 'active' : ''; ?>">
                            <a class="page-link" href="?page=<?php echo $i; ?>&search=<?php echo urlencode($search); ?>&status=<?php echo urlencode($status); ?>&payment_status=<?php echo urlencode($paymentStatus); ?><?php echo $programId ? '&program_id=' . $programId : ''; ?>">
                                <?php echo $i; ?>
                            </a>
                        </li>
                    <?php endfor; ?>
                    
                    <?php if ($page < $totalPages): ?>
                        <li class="page-item">
                            <a class="page-link" href="?page=<?php echo $page + 1; ?>&search=<?php echo urlencode($search); ?>&status=<?php echo urlencode($status); ?>&payment_status=<?php echo urlencode($paymentStatus); ?><?php echo $programId ? '&program_id=' . $programId : ''; ?>">
                                Next <i class="fas fa-chevron-right"></i>
                            </a>
                        </li>
                    <?php endif; ?>
                </ul>
            </nav>
            <?php endif; ?>
        <?php endif; ?>
    </div>
</div>

<!-- View Enrollment Modal -->
<div class="modal" id="viewEnrollmentModal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Enrollment Details</h5>
                <button type="button" class="modal-close" data-dismiss="modal">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="modal-body" id="enrollmentDetails">
                <!-- Content loaded via AJAX -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- Update Status Modal -->
<div class="modal" id="updateStatusModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Update Enrollment Status</h5>
                <button type="button" class="modal-close" data-dismiss="modal">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <form id="updateStatusForm" method="POST">
                <input type="hidden" name="csrf_token" value="<?php echo generateCSRFToken(); ?>">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="enrollment_status_select" class="form-label">Enrollment Status</label>
                                <select id="enrollment_status_select" name="enrollment_status" class="form-control" required>
                                    <option value="pending">Pending</option>
                                    <option value="confirmed">Confirmed</option>
                                    <option value="cancelled">Cancelled</option>
                                    <option value="completed">Completed</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="payment_status_select" class="form-label">Payment Status</label>
                                <select id="payment_status_select" name="payment_status" class="form-control" required>
                                    <option value="pending">Pending</option>
                                    <option value="partial">Partial</option>
                                    <option value="paid">Paid</option>
                                    <option value="refunded">Refunded</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="payment_amount_input" class="form-label">Payment Amount (RWF)</label>
                        <input type="number" id="payment_amount_input" name="payment_amount" class="form-control" 
                               min="0" step="0.01" placeholder="Enter payment amount">
                    </div>
                    
                    <div class="form-group">
                        <label for="notes_textarea" class="form-label">Notes</label>
                        <textarea id="notes_textarea" name="notes" class="form-control" rows="3" 
                                  placeholder="Add any notes about this enrollment..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Update Status</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal" id="deleteModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirm Deletion</h5>
                <button type="button" class="modal-close" data-dismiss="modal">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete this enrollment? This action cannot be undone.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <form id="deleteForm" method="POST" style="display: inline;">
                    <input type="hidden" name="csrf_token" value="<?php echo generateCSRFToken(); ?>">
                    <button type="submit" class="btn btn-danger">Delete</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Handle view enrollment buttons
    document.querySelectorAll('.btn-view-enrollment').forEach(function(btn) {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            
            const enrollmentId = this.dataset.enrollmentId;
            const modal = document.getElementById('viewEnrollmentModal');
            const detailsDiv = document.getElementById('enrollmentDetails');
            
            // Show loading
            detailsDiv.innerHTML = '<div class="text-center"><i class="fas fa-spinner fa-spin"></i> Loading...</div>';
            modal.classList.add('show');
            
            // Load enrollment details via AJAX
            fetch(`ajax/get_enrollment_details.php?id=${enrollmentId}`)
                .then(response => response.text())
                .then(html => {
                    detailsDiv.innerHTML = html;
                })
                .catch(error => {
                    detailsDiv.innerHTML = '<div class="alert alert-danger">Error loading enrollment details.</div>';
                });
        });
    });
    
    // Handle update status buttons
    document.querySelectorAll('.btn-update-status').forEach(function(btn) {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            
            const enrollmentId = this.dataset.enrollmentId;
            const modal = document.getElementById('updateStatusModal');
            const form = document.getElementById('updateStatusForm');
            
            // Update form action
            form.action = `training_enrollments.php?action=update_status&id=${enrollmentId}<?php echo $programId ? '&program_id=' . $programId : ''; ?>`;
            
            // Show modal
            modal.classList.add('show');
        });
    });
    
    // Handle delete buttons
    document.querySelectorAll('.btn-delete').forEach(function(btn) {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            
            const url = this.dataset.url;
            const name = this.dataset.name;
            const modal = document.getElementById('deleteModal');
            const form = document.getElementById('deleteForm');
            
            // Update modal content
            modal.querySelector('.modal-body p').textContent = 
                `Are you sure you want to delete this ${name}? This action cannot be undone.`;
            
            // Update form action
            form.action = url;
            
            // Show modal
            modal.classList.add('show');
        });
    });
    
    // Handle modal close
    document.querySelectorAll('[data-dismiss="modal"]').forEach(function(btn) {
        btn.addEventListener('click', function() {
            document.querySelectorAll('.modal').forEach(modal => {
                modal.classList.remove('show');
            });
        });
    });
});

// Certificate issuance function
function issueCertificate(enrollmentId) {
    if (confirm('Are you sure you want to issue a certificate for this enrollment?')) {
        window.location.href = `training_enrollments.php?action=issue_certificate&id=${enrollmentId}<?php echo $programId ? '&program_id=' . $programId : ''; ?>`;
    }
}
</script>

<style>
.modal {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.5);
    z-index: 1000;
}

.modal.show {
    display: flex;
    align-items: center;
    justify-content: center;
}

.modal-dialog {
    max-width: 500px;
    width: 90%;
}

.modal-lg {
    max-width: 800px;
}

.modal-content {
    background: white;
    border-radius: 0.375rem;
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
}

.modal-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 1rem;
    border-bottom: 1px solid #dee2e6;
}

.modal-title {
    margin: 0;
    font-size: 1.25rem;
    font-weight: 500;
}

.modal-close {
    background: none;
    border: none;
    font-size: 1.5rem;
    cursor: pointer;
    padding: 0;
}

.modal-body {
    padding: 1rem;
}

.modal-footer {
    display: flex;
    justify-content: flex-end;
    gap: 0.5rem;
    padding: 1rem;
    border-top: 1px solid #dee2e6;
}

.bg-gold {
    background-color: #ffc107 !important;
}

.badge-gold {
    background-color: #ffc107;
    color: #212529;
}
</style>

<?php include 'includes/footer.php'; ?>
