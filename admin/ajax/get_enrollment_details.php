<?php
/**
 * AJAX endpoint for getting enrollment details
 * Created: 2025-01-26
 */

require_once '../config/config.php';

// Check if user is logged in
if (!isLoggedIn()) {
    http_response_code(401);
    echo '<div class="alert alert-danger">Unauthorized access.</div>';
    exit;
}

$enrollmentId = intval($_GET['id'] ?? 0);

if (!$enrollmentId) {
    echo '<div class="alert alert-danger">Invalid enrollment ID.</div>';
    exit;
}

// Get enrollment details
$enrollment = dbGetRow("
    SELECT te.*, tp.title as program_title, tp.category as program_category, tp.price as program_price,
           tp.duration, tp.instructor, tp.level as program_level,
           ts.start_date, ts.end_date, ts.schedule_type, ts.time_slot
    FROM training_enrollments te 
    LEFT JOIN training_schedules ts ON te.schedule_id = ts.id 
    LEFT JOIN training_programs tp ON ts.program_id = tp.id 
    WHERE te.id = ?
", [$enrollmentId]);

if (!$enrollment) {
    echo '<div class="alert alert-danger">Enrollment not found.</div>';
    exit;
}
?>

<div class="row">
    <div class="col-md-6">
        <h6><i class="fas fa-user"></i> Student Information</h6>
        <table class="table table-sm">
            <tr>
                <td><strong>Name:</strong></td>
                <td><?php echo htmlspecialchars($enrollment['name']); ?></td>
            </tr>
            <tr>
                <td><strong>Email:</strong></td>
                <td>
                    <a href="mailto:<?php echo htmlspecialchars($enrollment['email']); ?>">
                        <?php echo htmlspecialchars($enrollment['email']); ?>
                    </a>
                </td>
            </tr>
            <tr>
                <td><strong>Phone:</strong></td>
                <td>
                    <?php if ($enrollment['phone']): ?>
                        <a href="tel:<?php echo htmlspecialchars($enrollment['phone']); ?>">
                            <?php echo htmlspecialchars($enrollment['phone']); ?>
                        </a>
                    <?php else: ?>
                        <span class="text-muted">Not provided</span>
                    <?php endif; ?>
                </td>
            </tr>
            <tr>
                <td><strong>Company:</strong></td>
                <td><?php echo $enrollment['company'] ? htmlspecialchars($enrollment['company']) : '<span class="text-muted">Not provided</span>'; ?></td>
            </tr>
            <tr>
                <td><strong>Position:</strong></td>
                <td><?php echo $enrollment['position'] ? htmlspecialchars($enrollment['position']) : '<span class="text-muted">Not provided</span>'; ?></td>
            </tr>
            <tr>
                <td><strong>Experience Level:</strong></td>
                <td>
                    <span class="badge badge-<?php 
                        echo $enrollment['experience_level'] === 'beginner' ? 'success' : 
                            ($enrollment['experience_level'] === 'intermediate' ? 'warning' : 'info'); 
                    ?>">
                        <?php echo ucfirst($enrollment['experience_level']); ?>
                    </span>
                </td>
            </tr>
        </table>
    </div>
    
    <div class="col-md-6">
        <h6><i class="fas fa-graduation-cap"></i> Program Information</h6>
        <table class="table table-sm">
            <tr>
                <td><strong>Program:</strong></td>
                <td><?php echo htmlspecialchars($enrollment['program_title'] ?? 'Unknown Program'); ?></td>
            </tr>
            <tr>
                <td><strong>Category:</strong></td>
                <td>
                    <?php if ($enrollment['program_category']): ?>
                        <span class="badge badge-light">
                            <?php echo ucfirst($enrollment['program_category']); ?>
                        </span>
                    <?php else: ?>
                        <span class="text-muted">Not specified</span>
                    <?php endif; ?>
                </td>
            </tr>
            <tr>
                <td><strong>Level:</strong></td>
                <td>
                    <?php if ($enrollment['program_level']): ?>
                        <span class="badge badge-<?php 
                            echo $enrollment['program_level'] === 'beginner' ? 'success' : 
                                ($enrollment['program_level'] === 'intermediate' ? 'warning' : 'info'); 
                        ?>">
                            <?php echo ucfirst($enrollment['program_level']); ?>
                        </span>
                    <?php else: ?>
                        <span class="text-muted">Not specified</span>
                    <?php endif; ?>
                </td>
            </tr>
            <tr>
                <td><strong>Instructor:</strong></td>
                <td><?php echo $enrollment['instructor'] ? htmlspecialchars($enrollment['instructor']) : '<span class="text-muted">TBA</span>'; ?></td>
            </tr>
            <tr>
                <td><strong>Duration:</strong></td>
                <td><?php echo $enrollment['duration'] ? htmlspecialchars($enrollment['duration']) : '<span class="text-muted">Not specified</span>'; ?></td>
            </tr>
            <tr>
                <td><strong>Program Price:</strong></td>
                <td>
                    <?php if ($enrollment['program_price'] > 0): ?>
                        <strong>RWF <?php echo number_format($enrollment['program_price'], 0); ?></strong>
                    <?php else: ?>
                        <span class="text-success"><strong>Free</strong></span>
                    <?php endif; ?>
                </td>
            </tr>
        </table>
    </div>
</div>

<div class="row mt-3">
    <div class="col-md-6">
        <h6><i class="fas fa-calendar"></i> Schedule Information</h6>
        <table class="table table-sm">
            <tr>
                <td><strong>Start Date:</strong></td>
                <td>
                    <?php if ($enrollment['start_date']): ?>
                        <?php echo date('F j, Y', strtotime($enrollment['start_date'])); ?>
                    <?php else: ?>
                        <span class="text-muted">TBA</span>
                    <?php endif; ?>
                </td>
            </tr>
            <tr>
                <td><strong>End Date:</strong></td>
                <td>
                    <?php if ($enrollment['end_date']): ?>
                        <?php echo date('F j, Y', strtotime($enrollment['end_date'])); ?>
                    <?php else: ?>
                        <span class="text-muted">TBA</span>
                    <?php endif; ?>
                </td>
            </tr>
            <tr>
                <td><strong>Schedule Type:</strong></td>
                <td>
                    <?php if ($enrollment['schedule_type']): ?>
                        <span class="badge badge-info">
                            <?php echo ucfirst($enrollment['schedule_type']); ?>
                        </span>
                    <?php else: ?>
                        <span class="text-muted">Not specified</span>
                    <?php endif; ?>
                </td>
            </tr>
            <tr>
                <td><strong>Time Slot:</strong></td>
                <td><?php echo $enrollment['time_slot'] ? htmlspecialchars($enrollment['time_slot']) : '<span class="text-muted">Not specified</span>'; ?></td>
            </tr>
        </table>
    </div>
    
    <div class="col-md-6">
        <h6><i class="fas fa-info-circle"></i> Enrollment Status</h6>
        <table class="table table-sm">
            <tr>
                <td><strong>Status:</strong></td>
                <td>
                    <span class="badge badge-<?php 
                        echo $enrollment['enrollment_status'] === 'confirmed' ? 'success' : 
                            ($enrollment['enrollment_status'] === 'pending' ? 'warning' : 
                            ($enrollment['enrollment_status'] === 'completed' ? 'primary' : 'secondary')); 
                    ?>">
                        <?php echo ucfirst($enrollment['enrollment_status']); ?>
                    </span>
                </td>
            </tr>
            <tr>
                <td><strong>Payment Status:</strong></td>
                <td>
                    <span class="badge badge-<?php 
                        echo $enrollment['payment_status'] === 'paid' ? 'success' : 
                            ($enrollment['payment_status'] === 'partial' ? 'warning' : 
                            ($enrollment['payment_status'] === 'refunded' ? 'secondary' : 'danger')); 
                    ?>">
                        <?php echo ucfirst($enrollment['payment_status']); ?>
                    </span>
                </td>
            </tr>
            <tr>
                <td><strong>Payment Amount:</strong></td>
                <td>
                    <?php if ($enrollment['payment_amount'] > 0): ?>
                        <strong>RWF <?php echo number_format($enrollment['payment_amount'], 0); ?></strong>
                    <?php else: ?>
                        <span class="text-muted">No payment recorded</span>
                    <?php endif; ?>
                </td>
            </tr>
            <tr>
                <td><strong>Certificate:</strong></td>
                <td>
                    <?php if ($enrollment['certificate_issued']): ?>
                        <span class="badge badge-gold">
                            <i class="fas fa-certificate"></i> Issued
                        </span>
                    <?php else: ?>
                        <span class="text-muted">Not issued</span>
                    <?php endif; ?>
                </td>
            </tr>
            <tr>
                <td><strong>Enrolled:</strong></td>
                <td><?php echo date('F j, Y g:i A', strtotime($enrollment['created_at'])); ?></td>
            </tr>
            <tr>
                <td><strong>Last Updated:</strong></td>
                <td><?php echo date('F j, Y g:i A', strtotime($enrollment['updated_at'])); ?></td>
            </tr>
        </table>
    </div>
</div>

<?php if ($enrollment['special_requirements']): ?>
<div class="row mt-3">
    <div class="col-md-12">
        <h6><i class="fas fa-exclamation-circle"></i> Special Requirements</h6>
        <div class="card">
            <div class="card-body">
                <?php echo nl2br(htmlspecialchars($enrollment['special_requirements'])); ?>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>

<?php if ($enrollment['notes']): ?>
<div class="row mt-3">
    <div class="col-md-12">
        <h6><i class="fas fa-sticky-note"></i> Admin Notes</h6>
        <div class="card">
            <div class="card-body">
                <?php echo nl2br(htmlspecialchars($enrollment['notes'])); ?>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>

<div class="row mt-3">
    <div class="col-md-12">
        <div class="d-flex justify-content-between">
            <div>
                <a href="mailto:<?php echo htmlspecialchars($enrollment['email']); ?>?subject=Training Enrollment - <?php echo htmlspecialchars($enrollment['program_title'] ?? 'Program'); ?>" 
                   class="btn btn-primary btn-sm">
                    <i class="fas fa-reply"></i> Email Student
                </a>
                <?php if ($enrollment['phone']): ?>
                <a href="tel:<?php echo htmlspecialchars($enrollment['phone']); ?>" 
                   class="btn btn-success btn-sm">
                    <i class="fas fa-phone"></i> Call Student
                </a>
                <?php endif; ?>
            </div>
            <div>
                <button type="button" 
                        class="btn btn-info btn-sm btn-update-status" 
                        data-enrollment-id="<?php echo $enrollment['id']; ?>"
                        onclick="document.getElementById('viewEnrollmentModal').classList.remove('show'); document.getElementById('updateStatusModal').classList.add('show');">
                    <i class="fas fa-edit"></i> Update Status
                </button>
                <?php if (!$enrollment['certificate_issued'] && $enrollment['enrollment_status'] === 'completed'): ?>
                <button type="button" 
                        class="btn btn-warning btn-sm"
                        onclick="issueCertificate(<?php echo $enrollment['id']; ?>)">
                    <i class="fas fa-certificate"></i> Issue Certificate
                </button>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
