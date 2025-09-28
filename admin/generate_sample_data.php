<?php
/**
 * Generate Sample Data for FSMC Training System
 * Created: 2025-01-26
 */

require_once 'config/config.php';

// Check if user is logged in
if (!isLoggedIn()) {
    redirect('login.php');
}

// Only allow this script to run in development/testing
if (!isset($_GET['confirm']) || $_GET['confirm'] !== 'yes') {
    echo "<!DOCTYPE html><html><head><title>Generate Sample Data</title></head><body>";
    echo "<h2>Generate Sample Training Data</h2>";
    echo "<p><strong>Warning:</strong> This will add sample data to your database.</p>";
    echo "<p><a href='?confirm=yes' style='background: #007cba; color: white; padding: 10px 20px; text-decoration: none; border-radius: 4px;'>Generate Sample Data</a></p>";
    echo "<p><a href='training.php'>← Back to Training Management</a></p>";
    echo "</body></html>";
    exit;
}

echo "<!DOCTYPE html><html><head><title>Generating Sample Data</title></head><body>";
echo "<h2>Generating Sample Training Data...</h2>";

// Sample training programs
$programs = [
    [
        'title' => 'Land Surveying Fundamentals',
        'slug' => 'land-surveying-fundamentals',
        'description' => 'Comprehensive introduction to land surveying principles, techniques, and modern equipment usage.',
        'short_description' => 'Learn the basics of land surveying with hands-on training.',
        'category' => 'surveying',
        'level' => 'beginner',
        'instructor' => 'Dr. Jean Baptiste Uwimana',
        'duration' => '5 days',
        'price' => 250000,
        'max_students' => 25,
        'language' => 'English',
        'status' => 'active',
        'sort_order' => 1,
        'features' => json_encode(['Hands-on practice', 'Equipment training', 'Certificate included', 'Field work experience']),
        'curriculum' => json_encode(['Survey principles', 'Equipment usage', 'Data collection', 'Report writing'])
    ],
    [
        'title' => 'GIS and Remote Sensing',
        'slug' => 'gis-remote-sensing',
        'description' => 'Advanced Geographic Information Systems and Remote Sensing techniques for spatial analysis.',
        'short_description' => 'Master GIS and remote sensing technologies.',
        'category' => 'gis',
        'level' => 'intermediate',
        'instructor' => 'Prof. Marie Claire Mukamana',
        'duration' => '7 days',
        'price' => 350000,
        'max_students' => 20,
        'language' => 'English',
        'status' => 'active',
        'sort_order' => 2,
        'features' => json_encode(['QGIS training', 'Satellite imagery', 'Spatial analysis', 'Project work']),
        'curriculum' => json_encode(['GIS basics', 'Remote sensing', 'Data analysis', 'Map production'])
    ],
    [
        'title' => 'Drone Mapping and Photogrammetry',
        'slug' => 'drone-mapping-photogrammetry',
        'description' => 'Learn modern drone mapping techniques and photogrammetry for accurate aerial surveys.',
        'short_description' => 'Professional drone mapping and 3D modeling training.',
        'category' => 'photogrammetry',
        'level' => 'advanced',
        'instructor' => 'Eng. Patrick Nzeyimana',
        'duration' => '4 days',
        'price' => 450000,
        'max_students' => 15,
        'language' => 'English',
        'status' => 'active',
        'sort_order' => 3,
        'features' => json_encode(['Drone operation', '3D modeling', 'Point clouds', 'Orthomosaic creation']),
        'curriculum' => json_encode(['Drone basics', 'Flight planning', 'Data processing', '3D reconstruction'])
    ],
    [
        'title' => 'AutoCAD for Surveyors',
        'slug' => 'autocad-surveyors',
        'description' => 'Master AutoCAD software specifically for surveying and mapping applications.',
        'short_description' => 'AutoCAD training tailored for surveying professionals.',
        'category' => 'software',
        'level' => 'beginner',
        'instructor' => 'Eng. Alice Uwimana',
        'duration' => '3 days',
        'price' => 180000,
        'max_students' => 30,
        'language' => 'English',
        'status' => 'active',
        'sort_order' => 4,
        'features' => json_encode(['Software license', 'Practical exercises', 'Templates included', 'Support materials']),
        'curriculum' => json_encode(['AutoCAD basics', 'Survey drawings', 'Plot plans', 'Data import/export'])
    ],
    [
        'title' => 'Construction Surveying',
        'slug' => 'construction-surveying',
        'description' => 'Specialized training in construction site surveying and layout techniques.',
        'short_description' => 'Essential surveying skills for construction projects.',
        'category' => 'construction',
        'level' => 'intermediate',
        'instructor' => 'Eng. Robert Habimana',
        'duration' => '6 days',
        'price' => 300000,
        'max_students' => 20,
        'language' => 'English',
        'status' => 'active',
        'sort_order' => 5,
        'features' => json_encode(['Site visits', 'Layout practice', 'Quality control', 'Safety training']),
        'curriculum' => json_encode(['Site preparation', 'Layout methods', 'Monitoring', 'Documentation'])
    ]
];

echo "<p>Creating training programs...</p>";

// Insert training programs
$programIds = [];
foreach ($programs as $program) {
    // Check if program with this slug already exists
    $existing = dbGetRow("SELECT id FROM training_programs WHERE slug = ?", [$program['slug']]);
    
    if ($existing) {
        echo "<p>⚠ Program already exists: {$program['title']} (using existing)</p>";
        $programIds[] = $existing['id'];
    } else {
        $sql = "INSERT INTO training_programs (title, slug, description, short_description, category, level, instructor, duration, price, max_students, language, status, sort_order, features, curriculum, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW())";
        
        if (dbExecute($sql, [
            $program['title'],
            $program['slug'],
            $program['description'],
            $program['short_description'],
            $program['category'],
            $program['level'],
            $program['instructor'],
            $program['duration'],
            $program['price'],
            $program['max_students'],
            $program['language'],
            $program['status'],
            $program['sort_order'],
            $program['features'],
            $program['curriculum']
        ])) {
            // Get the last inserted ID
            $result = dbGetRow("SELECT LAST_INSERT_ID() as id");
            $programIds[] = $result['id'];
            echo "<p>✓ Created program: {$program['title']}</p>";
        } else {
            echo "<p>✗ Failed to create program: {$program['title']}</p>";
        }
    }
}

// Sample training schedules
$schedules = [];
foreach ($programIds as $index => $programId) {
    // Create 2-3 schedules per program
    $baseDate = strtotime('+' . (($index * 2) + 1) . ' weeks');
    
    $schedules[] = [
        'program_id' => $programId,
        'start_date' => date('Y-m-d', $baseDate),
        'end_date' => date('Y-m-d', $baseDate + (5 * 24 * 60 * 60)), // 5 days later
        'schedule_type' => 'weekdays',
        'time_slot' => '9:00 AM - 5:00 PM',
        'max_participants' => $programs[$index]['max_students'],
        'status' => 'active'
    ];
    
    $schedules[] = [
        'program_id' => $programId,
        'start_date' => date('Y-m-d', $baseDate + (14 * 24 * 60 * 60)), // 2 weeks later
        'end_date' => date('Y-m-d', $baseDate + (19 * 24 * 60 * 60)), // 5 days after start
        'schedule_type' => 'weekend',
        'time_slot' => '8:00 AM - 4:00 PM',
        'max_participants' => $programs[$index]['max_students'],
        'status' => 'active'
    ];
}

echo "<p>Creating training schedules...</p>";

// Check if training_schedules table exists and get its structure
$tableExists = dbGetRow("SHOW TABLES LIKE 'training_schedules'");

// Insert training schedules
$scheduleIds = [];

if (!$tableExists) {
    echo "<p>ℹ Training schedules table not found, using program IDs directly for enrollments</p>";
    // Use program IDs as schedule IDs since we don't have a schedules table
    $scheduleIds = $programIds;
    foreach ($programIds as $programId) {
        echo "<p>✓ Using program ID {$programId} as schedule reference</p>";
    }
} else {
    // Get the actual column structure of training_schedules table
    $columns = dbGetRows("SHOW COLUMNS FROM training_schedules");
    $columnNames = array_column($columns, 'Field');
    
    echo "<p>ℹ Found training_schedules table with columns: " . implode(', ', $columnNames) . "</p>";
    
    foreach ($schedules as $schedule) {
        // Check if schedule already exists for this program and date (without schedule_type if it doesn't exist)
        if (in_array('schedule_type', $columnNames)) {
            $existing = dbGetRow("SELECT id FROM training_schedules WHERE program_id = ? AND start_date = ? AND schedule_type = ?", [
                $schedule['program_id'],
                $schedule['start_date'],
                $schedule['schedule_type']
            ]);
        } else {
            $existing = dbGetRow("SELECT id FROM training_schedules WHERE program_id = ? AND start_date = ?", [
                $schedule['program_id'],
                $schedule['start_date']
            ]);
        }
        
        if ($existing) {
            echo "<p>⚠ Schedule already exists: {$schedule['start_date']} (using existing)</p>";
            $scheduleIds[] = $existing['id'];
        } else {
            // Build SQL based on available columns
            $insertColumns = ['program_id', 'start_date', 'end_date'];
            $insertValues = [$schedule['program_id'], $schedule['start_date'], $schedule['end_date']];
            $placeholders = ['?', '?', '?'];
            
            // Add optional columns if they exist
            if (in_array('schedule_type', $columnNames)) {
                $insertColumns[] = 'schedule_type';
                $insertValues[] = $schedule['schedule_type'];
                $placeholders[] = '?';
            }
            if (in_array('time_slot', $columnNames)) {
                $insertColumns[] = 'time_slot';
                $insertValues[] = $schedule['time_slot'];
                $placeholders[] = '?';
            }
            if (in_array('max_participants', $columnNames)) {
                $insertColumns[] = 'max_participants';
                $insertValues[] = $schedule['max_participants'];
                $placeholders[] = '?';
            }
            if (in_array('status', $columnNames)) {
                $insertColumns[] = 'status';
                $insertValues[] = $schedule['status'];
                $placeholders[] = '?';
            }
            if (in_array('created_at', $columnNames)) {
                $insertColumns[] = 'created_at';
                $placeholders[] = 'NOW()';
            }
            if (in_array('updated_at', $columnNames)) {
                $insertColumns[] = 'updated_at';
                $placeholders[] = 'NOW()';
            }
            
            $sql = "INSERT INTO training_schedules (" . implode(', ', $insertColumns) . ") VALUES (" . implode(', ', $placeholders) . ")";
            
            if (dbExecute($sql, $insertValues)) {
                // Get the last inserted ID
                $result = dbGetRow("SELECT LAST_INSERT_ID() as id");
                $scheduleIds[] = $result['id'];
                echo "<p>✓ Created schedule: {$schedule['start_date']} to {$schedule['end_date']}</p>";
            } else {
                echo "<p>✗ Failed to create schedule: {$schedule['start_date']}</p>";
            }
        }
    }
}

// Sample enrollments
$sampleStudents = [
    ['name' => 'John Mugisha', 'email' => 'john.mugisha@email.com', 'phone' => '+250788123456', 'company' => 'Rwanda Land Management', 'position' => 'Junior Surveyor', 'experience' => 'beginner'],
    ['name' => 'Sarah Uwimana', 'email' => 'sarah.uwimana@email.com', 'phone' => '+250788234567', 'company' => 'Kigali Construction Ltd', 'position' => 'Site Engineer', 'experience' => 'intermediate'],
    ['name' => 'David Nkurunziza', 'email' => 'david.nkurunziza@email.com', 'phone' => '+250788345678', 'company' => 'GeoSurvey Rwanda', 'position' => 'Senior Surveyor', 'experience' => 'advanced'],
    ['name' => 'Grace Mukamana', 'email' => 'grace.mukamana@email.com', 'phone' => '+250788456789', 'company' => 'Ministry of Infrastructure', 'position' => 'GIS Specialist', 'experience' => 'intermediate'],
    ['name' => 'Peter Habimana', 'email' => 'peter.habimana@email.com', 'phone' => '+250788567890', 'company' => 'Urban Planning Authority', 'position' => 'Planning Officer', 'experience' => 'beginner'],
    ['name' => 'Alice Nyirahabimana', 'email' => 'alice.nyira@email.com', 'phone' => '+250788678901', 'company' => 'Private Consultant', 'position' => 'Freelance Surveyor', 'experience' => 'advanced'],
    ['name' => 'Emmanuel Bizimana', 'email' => 'emmanuel.biz@email.com', 'phone' => '+250788789012', 'company' => 'Rwanda Mines Authority', 'position' => 'Mining Engineer', 'experience' => 'intermediate'],
    ['name' => 'Claudine Uwase', 'email' => 'claudine.uwase@email.com', 'phone' => '+250788890123', 'company' => 'WASAC Ltd', 'position' => 'Water Engineer', 'experience' => 'beginner'],
    ['name' => 'Vincent Nsengimana', 'email' => 'vincent.nseng@email.com', 'phone' => '+250788901234', 'company' => 'REG Rwanda', 'position' => 'Electrical Engineer', 'experience' => 'intermediate'],
    ['name' => 'Immaculee Mukamazera', 'email' => 'immaculee.muka@email.com', 'phone' => '+250788012345', 'company' => 'University of Rwanda', 'position' => 'Research Assistant', 'experience' => 'beginner']
];

$enrollmentStatuses = ['pending', 'confirmed', 'cancelled', 'completed'];
$paymentStatuses = ['pending', 'partial', 'paid', 'refunded'];

echo "<p>Creating sample enrollments...</p>";

// Create enrollments for each schedule
foreach ($scheduleIds as $scheduleId) {
    // Create 3-8 random enrollments per schedule
    $numEnrollments = rand(3, 8);
    $usedStudents = [];
    
    for ($i = 0; $i < $numEnrollments; $i++) {
        // Pick a random student that hasn't been used for this schedule
        do {
            $studentIndex = rand(0, count($sampleStudents) - 1);
        } while (in_array($studentIndex, $usedStudents));
        
        $usedStudents[] = $studentIndex;
        $student = $sampleStudents[$studentIndex];
        
        // Random enrollment data
        $enrollmentStatus = $enrollmentStatuses[rand(0, count($enrollmentStatuses) - 1)];
        $paymentStatus = $paymentStatuses[rand(0, count($paymentStatuses) - 1)];
        $paymentAmount = 0;
        
        // Set realistic payment amounts based on status
        if ($paymentStatus === 'paid') {
            $paymentAmount = rand(150000, 500000);
        } elseif ($paymentStatus === 'partial') {
            $paymentAmount = rand(50000, 200000);
        }
        
        // Certificate issued for completed enrollments with paid status
        $certificateIssued = ($enrollmentStatus === 'completed' && $paymentStatus === 'paid') ? 1 : 0;
        
        // Special requirements (random)
        $specialRequirements = '';
        if (rand(1, 4) === 1) { // 25% chance
            $requirements = [
                'Wheelchair accessible venue required',
                'Vegetarian meals preferred',
                'Need laptop for training',
                'Require translation services',
                'Early morning sessions preferred'
            ];
            $specialRequirements = $requirements[rand(0, count($requirements) - 1)];
        }
        
        // Notes (random)
        $notes = '';
        if (rand(1, 3) === 1) { // 33% chance
            $noteOptions = [
                'Student shows great potential',
                'Needs additional support with software',
                'Very experienced, could help others',
                'Payment plan arranged',
                'Company sponsored enrollment'
            ];
            $notes = $noteOptions[rand(0, count($noteOptions) - 1)];
        }
        
        // Check if this student is already enrolled in this schedule/program
        $existingEnrollment = dbGetRow("SELECT id FROM training_enrollments WHERE schedule_id = ? AND email = ?", [
            $scheduleId, // This could be either schedule_id or program_id depending on table structure
            $student['email']
        ]);
        
        if ($existingEnrollment) {
            echo "<p>⚠ {$student['name']} already enrolled in this schedule</p>";
        } else {
            $sql = "INSERT INTO training_enrollments (schedule_id, name, email, phone, company, position, experience_level, special_requirements, payment_status, payment_amount, enrollment_status, certificate_issued, notes, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW())";
            
            if (dbExecute($sql, [
                $scheduleId,
                $student['name'],
                $student['email'],
                $student['phone'],
                $student['company'],
                $student['position'],
                $student['experience'],
                $specialRequirements,
                $paymentStatus,
                $paymentAmount,
                $enrollmentStatus,
                $certificateIssued,
                $notes
            ])) {
                echo "<p>✓ Enrolled: {$student['name']} - Status: {$enrollmentStatus}</p>";
            } else {
                echo "<p>✗ Failed to enroll: {$student['name']}</p>";
            }
        }
    }
}

// Check what we actually created
$actualEnrollments = dbGetRows("SELECT COUNT(*) as count FROM training_enrollments");
$enrollmentCount = $actualEnrollments[0]['count'] ?? 0;

echo "<h3>✅ Sample Data Generation Complete!</h3>";
echo "<p><strong>Generated:</strong></p>";
echo "<ul>";
echo "<li>" . count($programs) . " Training Programs</li>";
if ($tableExists) {
    echo "<li>" . count($scheduleIds) . " Training Schedules</li>";
} else {
    echo "<li>Using " . count($scheduleIds) . " Program References (no schedules table)</li>";
}
echo "<li>{$enrollmentCount} Student Enrollments with various statuses</li>";
echo "</ul>";

echo "<p><strong>Debug Information:</strong></p>";
echo "<ul>";
echo "<li>Training schedules table exists: " . ($tableExists ? 'Yes' : 'No') . "</li>";
echo "<li>Schedule IDs used: " . implode(', ', $scheduleIds) . "</li>";
echo "<li>Total enrollments in database: {$enrollmentCount}</li>";
echo "</ul>";

echo "<p><strong>What was created:</strong></p>";
echo "<ul>";
echo "<li>Training programs covering surveying, GIS, photogrammetry, software, and construction</li>";
echo "<li>Multiple schedules for each program (weekdays and weekends)</li>";
echo "<li>Realistic student enrollments with various statuses and payment information</li>";
echo "<li>Some completed enrollments with certificates issued</li>";
echo "<li>Sample special requirements and admin notes</li>";
echo "</ul>";

echo "<p><a href='training.php' style='background: #007cba; color: white; padding: 10px 20px; text-decoration: none; border-radius: 4px;'>View Training Programs</a></p>";
echo "<p><a href='training_enrollments.php' style='background: #28a745; color: white; padding: 10px 20px; text-decoration: none; border-radius: 4px;'>View Enrollments</a></p>";

echo "</body></html>";
?>
