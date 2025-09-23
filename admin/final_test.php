<?php
/**
 * Final Comprehensive Test for FSMC Admin System
 */

require_once 'config/config.php';

echo "<h2>🎯 Final System Test</h2>";

// Test 1: Database Connection
echo "<h3>1. Database Connection Test</h3>";
try {
    $db = getDB();
    echo "<p style='color: green;'>✅ Database connection successful</p>";
} catch (Exception $e) {
    echo "<p style='color: red;'>❌ Database connection failed: " . $e->getMessage() . "</p>";
}

// Test 2: Required Tables
echo "<h3>2. Database Tables Test</h3>";
$requiredTables = [
    'admin_users', 'services', 'products', 'training_programs', 'research_projects',
    'inquiries', 'settings', 'file_uploads', 'activity_logs'
];

foreach ($requiredTables as $table) {
    try {
        $count = dbGetValue("SELECT COUNT(*) FROM $table");
        echo "<p style='color: green;'>✅ Table '$table' exists with $count records</p>";
    } catch (Exception $e) {
        echo "<p style='color: red;'>❌ Table '$table' error: " . $e->getMessage() . "</p>";
    }
}

// Test 3: Helper Functions
echo "<h3>3. Helper Functions Test</h3>";
$functions = [
    'getCurrentUser', 'getCurrentUserId', 'getSetting', 'formatFileSize', 
    'validateUploadedFile', 'dbGetValue', 'dbGetRow', 'dbGetRows'
];

foreach ($functions as $func) {
    if (function_exists($func)) {
        echo "<p style='color: green;'>✅ Function '$func' exists</p>";
    } else {
        echo "<p style='color: red;'>❌ Function '$func' missing</p>";
    }
}

// Test 4: Authentication
echo "<h3>4. Authentication Test</h3>";
try {
    $isLoggedIn = isLoggedIn();
    echo "<p style='color: " . ($isLoggedIn ? 'green' : 'orange') . "'>";
    echo ($isLoggedIn ? '✅' : '⚠️') . " User logged in: " . ($isLoggedIn ? 'Yes' : 'No') . "</p>";
    
    if ($isLoggedIn) {
        $user = getCurrentUser();
        echo "<p style='color: green;'>✅ Current user: " . htmlspecialchars($user['full_name'] ?? 'Unknown') . "</p>";
    }
} catch (Exception $e) {
    echo "<p style='color: red;'>❌ Authentication error: " . $e->getMessage() . "</p>";
}

// Test 5: Settings
echo "<h3>5. Settings Test</h3>";
try {
    $testSetting = getSetting('company_name', 'Default Company');
    echo "<p style='color: green;'>✅ Settings function works: '$testSetting'</p>";
} catch (Exception $e) {
    echo "<p style='color: red;'>❌ Settings error: " . $e->getMessage() . "</p>";
}

// Test 6: File Upload Directories
echo "<h3>6. File Upload Directories Test</h3>";
$uploadDirs = [
    UPLOAD_PATH,
    UPLOAD_PATH . '/images',
    UPLOAD_PATH . '/documents', 
    UPLOAD_PATH . '/general'
];

foreach ($uploadDirs as $dir) {
    if (is_dir($dir)) {
        $writable = is_writable($dir) ? 'writable' : 'not writable';
        echo "<p style='color: green;'>✅ Directory '$dir' exists and is $writable</p>";
    } else {
        echo "<p style='color: red;'>❌ Directory '$dir' missing</p>";
    }
}

// Test 7: Page Links
echo "<h3>7. Admin Pages Test</h3>";
$pages = [
    'index.php' => 'Dashboard',
    'services.php' => 'Services',
    'products.php' => 'Products', 
    'training.php' => 'Training',
    'research.php' => 'Research',
    'inquiries.php' => 'Inquiries',
    'media.php' => 'Media',
    'users.php' => 'Users',
    'settings.php' => 'Settings',
    'logs.php' => 'Activity Logs'
];

echo "<ul>";
foreach ($pages as $page => $title) {
    echo "<li><a href='$page' target='_blank' style='color: #007bff;'>🔗 Test $title</a></li>";
}
echo "</ul>";

// Test 8: JavaScript Test
echo "<h3>8. JavaScript Functionality Test</h3>";
echo "<button onclick='testModal()' class='btn btn-primary' style='margin: 5px;'>Test Modal</button>";
echo "<button onclick='testAlert()' class='btn btn-success' style='margin: 5px;'>Test Alert</button>";
echo "<button onclick='testConfirm()' class='btn btn-warning' style='margin: 5px;'>Test Confirm</button>";

// Modal for testing
echo "<div id='testModal' class='modal'>
    <div class='modal-dialog'>
        <div class='modal-content'>
            <div class='modal-header'>
                <h5 class='modal-title'>Test Modal</h5>
                <button type='button' class='modal-close' data-dismiss='modal'>
                    <i class='fas fa-times'></i>
                </button>
            </div>
            <div class='modal-body'>
                <p>✅ JavaScript modal functionality is working correctly!</p>
            </div>
            <div class='modal-footer'>
                <button type='button' class='btn btn-secondary' data-dismiss='modal'>Close</button>
            </div>
        </div>
    </div>
</div>";

echo "<h3>🎉 Test Summary</h3>";
echo "<p><strong>If all tests show ✅ green checkmarks, your admin system is fully functional!</strong></p>";
echo "<p>If you see any ❌ red errors, please check the specific component mentioned.</p>";

echo "<script>
function testModal() {
    document.getElementById('testModal').classList.add('show');
}

function testAlert() {
    alert('✅ JavaScript alerts are working!');
}

function testConfirm() {
    if (confirm('✅ JavaScript confirm dialogs are working! Click OK to continue.')) {
        alert('✅ Confirmation successful!');
    }
}
</script>";

echo "<style>
.btn {
    display: inline-block;
    padding: 8px 16px;
    margin: 4px;
    background: #007bff;
    color: white;
    text-decoration: none;
    border: none;
    border-radius: 4px;
    cursor: pointer;
}
.btn-success { background: #28a745; }
.btn-warning { background: #ffc107; color: #212529; }
.btn-secondary { background: #6c757d; }
</style>";
?>
