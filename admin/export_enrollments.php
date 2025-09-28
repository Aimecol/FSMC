<?php
/**
 * Export Training Enrollments to CSV
 * Created: 2025-01-26
 */

require_once 'config/config.php';

// Check if user is logged in
if (!isLoggedIn()) {
    redirect('login.php');
}

$programId = intval($_GET['program_id'] ?? 0);

// Build query conditions
$whereConditions = [];
$params = [];

if ($programId) {
    $whereConditions[] = "te.schedule_id IN (SELECT id FROM training_schedules WHERE program_id = ?)";
    $params[] = $programId;
}

$whereClause = !empty($whereConditions) ? 'WHERE ' . implode(' AND ', $whereConditions) : '';

// Get enrollments for export
$enrollmentsQuery = "SELECT te.*, tp.title as program_title, tp.category as program_category,
                            tp.price as program_price, tp.duration, tp.instructor,
                            ts.start_date, ts.end_date, ts.schedule_type, ts.time_slot
                     FROM training_enrollments te 
                     LEFT JOIN training_schedules ts ON te.schedule_id = ts.id 
                     LEFT JOIN training_programs tp ON ts.program_id = tp.id 
                     $whereClause 
                     ORDER BY te.created_at DESC";
$enrollments = dbGetRows($enrollmentsQuery, $params);

// Set headers for CSV download
$filename = 'training_enrollments_' . date('Y-m-d_H-i-s') . '.csv';
if ($programId) {
    $program = dbGetRow("SELECT title FROM training_programs WHERE id = ?", [$programId]);
    if ($program) {
        $filename = 'enrollments_' . preg_replace('/[^a-zA-Z0-9_-]/', '_', $program['title']) . '_' . date('Y-m-d_H-i-s') . '.csv';
    }
}

header('Content-Type: text/csv');
header('Content-Disposition: attachment; filename="' . $filename . '"');
header('Pragma: no-cache');
header('Expires: 0');

// Open output stream
$output = fopen('php://output', 'w');

// CSV Headers
$headers = [
    'ID',
    'Student Name',
    'Email',
    'Phone',
    'Company',
    'Position',
    'Experience Level',
    'Program Title',
    'Program Category',
    'Program Price',
    'Instructor',
    'Duration',
    'Start Date',
    'End Date',
    'Schedule Type',
    'Time Slot',
    'Enrollment Status',
    'Payment Status',
    'Payment Amount',
    'Certificate Issued',
    'Special Requirements',
    'Notes',
    'Enrolled Date',
    'Last Updated'
];

fputcsv($output, $headers);

// Export data
foreach ($enrollments as $enrollment) {
    $row = [
        $enrollment['id'],
        $enrollment['name'],
        $enrollment['email'],
        $enrollment['phone'] ?? '',
        $enrollment['company'] ?? '',
        $enrollment['position'] ?? '',
        ucfirst($enrollment['experience_level']),
        $enrollment['program_title'] ?? '',
        $enrollment['program_category'] ?? '',
        $enrollment['program_price'] ?? '',
        $enrollment['instructor'] ?? '',
        $enrollment['duration'] ?? '',
        $enrollment['start_date'] ? date('Y-m-d', strtotime($enrollment['start_date'])) : '',
        $enrollment['end_date'] ? date('Y-m-d', strtotime($enrollment['end_date'])) : '',
        $enrollment['schedule_type'] ?? '',
        $enrollment['time_slot'] ?? '',
        ucfirst($enrollment['enrollment_status']),
        ucfirst($enrollment['payment_status']),
        $enrollment['payment_amount'] ?? '0',
        $enrollment['certificate_issued'] ? 'Yes' : 'No',
        $enrollment['special_requirements'] ?? '',
        $enrollment['notes'] ?? '',
        date('Y-m-d H:i:s', strtotime($enrollment['created_at'])),
        date('Y-m-d H:i:s', strtotime($enrollment['updated_at']))
    ];
    
    fputcsv($output, $row);
}

fclose($output);
exit;
?>
