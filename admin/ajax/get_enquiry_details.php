<?php
/**
 * AJAX endpoint for getting enquiry details
 * Created: 2025-01-26
 */

require_once '../config/config.php';

// Check if user is logged in
if (!isLoggedIn()) {
    http_response_code(401);
    echo '<div class="alert alert-danger">Unauthorized access.</div>';
    exit;
}

$enquiryId = intval($_GET['id'] ?? 0);

if (!$enquiryId) {
    echo '<div class="alert alert-danger">Invalid enquiry ID.</div>';
    exit;
}

// Get enquiry details (without user join since users table may not exist)
$enquiry = dbGetRow("
    SELECT pe.*, p.title as product_title, p.category as product_category, p.price as product_price
    FROM product_inquiries pe 
    LEFT JOIN products p ON pe.product_id = p.id 
    WHERE pe.id = ?
", [$enquiryId]);

if (!$enquiry) {
    echo '<div class="alert alert-danger">Enquiry not found.</div>';
    exit;
}
?>

<div class="row">
    <div class="col-md-6">
        <h6><i class="fas fa-user"></i> Customer Information</h6>
        <table class="table table-sm">
            <tr>
                <td><strong>Name:</strong></td>
                <td><?php echo htmlspecialchars($enquiry['name']); ?></td>
            </tr>
            <tr>
                <td><strong>Email:</strong></td>
                <td>
                    <a href="mailto:<?php echo htmlspecialchars($enquiry['email']); ?>">
                        <?php echo htmlspecialchars($enquiry['email']); ?>
                    </a>
                </td>
            </tr>
            <?php if ($enquiry['phone']): ?>
            <tr>
                <td><strong>Phone:</strong></td>
                <td>
                    <a href="tel:<?php echo htmlspecialchars($enquiry['phone']); ?>">
                        <?php echo htmlspecialchars($enquiry['phone']); ?>
                    </a>
                </td>
            </tr>
            <?php endif; ?>
            <?php if ($enquiry['company']): ?>
            <tr>
                <td><strong>Company:</strong></td>
                <td><?php echo htmlspecialchars($enquiry['company']); ?></td>
            </tr>
            <?php endif; ?>
        </table>
    </div>
    
    <div class="col-md-6">
        <h6><i class="fas fa-box"></i> Product Information</h6>
        <table class="table table-sm">
            <tr>
                <td><strong>Product:</strong></td>
                <td><?php echo htmlspecialchars($enquiry['product_title'] ?? 'Unknown Product'); ?></td>
            </tr>
            <?php if ($enquiry['product_category']): ?>
            <tr>
                <td><strong>Category:</strong></td>
                <td>
                    <span class="badge badge-<?php 
                        echo $enquiry['product_category'] === 'equipment' ? 'primary' : 
                            ($enquiry['product_category'] === 'software' ? 'info' : 
                            ($enquiry['product_category'] === 'training' ? 'warning' : 'success')); 
                    ?>">
                        <?php echo ucfirst($enquiry['product_category']); ?>
                    </span>
                </td>
            </tr>
            <?php endif; ?>
            <?php if ($enquiry['product_price']): ?>
            <tr>
                <td><strong>Price:</strong></td>
                <td><strong>RWF <?php echo number_format($enquiry['product_price'], 0); ?></strong></td>
            </tr>
            <?php endif; ?>
        </table>
    </div>
</div>

<div class="row mt-3">
    <div class="col-md-12">
        <h6><i class="fas fa-envelope"></i> Enquiry Message</h6>
        <div class="card">
            <div class="card-body">
                <?php echo nl2br(htmlspecialchars($enquiry['message'])); ?>
            </div>
        </div>
    </div>
</div>

<div class="row mt-3">
    <div class="col-md-6">
        <h6><i class="fas fa-info-circle"></i> Status Information</h6>
        <table class="table table-sm">
            <tr>
                <td><strong>Status:</strong></td>
                <td>
                    <span class="badge badge-<?php 
                        echo $enquiry['status'] === 'new' ? 'primary' : 
                            ($enquiry['status'] === 'contacted' ? 'info' : 
                            ($enquiry['status'] === 'quoted' ? 'warning' : 'secondary')); 
                    ?>">
                        <?php echo ucfirst(str_replace('_', ' ', $enquiry['status'])); ?>
                    </span>
                </td>
            </tr>
            <tr>
                <td><strong>Assigned To:</strong></td>
                <td>
                    <?php if ($enquiry['assigned_to']): ?>
                        <span class="text-muted">User ID: <?php echo $enquiry['assigned_to']; ?></span>
                    <?php else: ?>
                        <span class="text-muted">Unassigned</span>
                    <?php endif; ?>
                </td>
            </tr>
            <tr>
                <td><strong>Received:</strong></td>
                <td><?php echo date('F j, Y g:i A', strtotime($enquiry['created_at'])); ?></td>
            </tr>
            <tr>
                <td><strong>Last Updated:</strong></td>
                <td><?php echo date('F j, Y g:i A', strtotime($enquiry['updated_at'])); ?></td>
            </tr>
        </table>
    </div>
    
    <div class="col-md-6">
        <?php if ($enquiry['notes']): ?>
        <h6><i class="fas fa-sticky-note"></i> Notes</h6>
        <div class="card">
            <div class="card-body">
                <?php echo nl2br(htmlspecialchars($enquiry['notes'])); ?>
            </div>
        </div>
        <?php else: ?>
        <h6><i class="fas fa-sticky-note"></i> Notes</h6>
        <p class="text-muted">No notes added yet.</p>
        <?php endif; ?>
    </div>
</div>

<div class="row mt-3">
    <div class="col-md-12">
        <div class="d-flex justify-content-between">
            <div>
                <a href="mailto:<?php echo htmlspecialchars($enquiry['email']); ?>?subject=Re: Product Enquiry - <?php echo htmlspecialchars($enquiry['product_title'] ?? 'Product'); ?>" 
                   class="btn btn-primary btn-sm">
                    <i class="fas fa-reply"></i> Reply via Email
                </a>
                <?php if ($enquiry['phone']): ?>
                <a href="tel:<?php echo htmlspecialchars($enquiry['phone']); ?>" 
                   class="btn btn-success btn-sm">
                    <i class="fas fa-phone"></i> Call Customer
                </a>
                <?php endif; ?>
            </div>
            <div>
                <button type="button" 
                        class="btn btn-info btn-sm btn-update-status" 
                        data-enquiry-id="<?php echo $enquiry['id']; ?>"
                        onclick="document.getElementById('viewEnquiryModal').classList.remove('show'); document.getElementById('updateStatusModal').classList.add('show');">
                    <i class="fas fa-edit"></i> Update Status
                </button>
            </div>
        </div>
    </div>
</div>
