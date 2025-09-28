<?php
/**
 * Get Team Member Data for Editing
 * Returns JSON data for a specific team member
 */

require_once 'config/config.php';
require_once 'config/database.php';

// Check authentication
requireAuth();

// Set JSON header
header('Content-Type: application/json');

try {
    // Get member ID from request
    $memberId = intval($_GET['id'] ?? 0);
    
    if ($memberId <= 0) {
        throw new Exception('Invalid member ID');
    }
    
    // Fetch member data
    $member = dbGetRow("SELECT * FROM team_members WHERE id = ?", [$memberId]);
    
    if (!$member) {
        throw new Exception('Team member not found');
    }
    
    // Return success response
    echo json_encode([
        'success' => true,
        'member' => $member
    ]);
    
} catch (Exception $e) {
    // Return error response
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ]);
}
?>
