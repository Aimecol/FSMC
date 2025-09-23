<?php
/**
 * Database Schema and Array Handling Fixes Test
 */

require_once 'config/config.php';
require_once 'includes/image_upload.php';

echo "<h2>🔧 Database Schema and Array Handling Fixes Test</h2>";

// Test 1: Check file_uploads table structure
echo "<h3>1. File Uploads Table Structure</h3>";
try {
    $columns = dbGetRows("DESCRIBE file_uploads");
    echo "<p style='color: green;'>✅ file_uploads table exists</p>";
    echo "<table border='1' style='border-collapse: collapse; margin: 10px 0;'>";
    echo "<tr><th>Column</th><th>Type</th><th>Null</th><th>Key</th><th>Default</th></tr>";
    foreach ($columns as $col) {
        echo "<tr>";
        echo "<td>{$col['Field']}</td>";
        echo "<td>{$col['Type']}</td>";
        echo "<td>{$col['Null']}</td>";
        echo "<td>{$col['Key']}</td>";
        echo "<td>{$col['Default']}</td>";
        echo "</tr>";
    }
    echo "</table>";
} catch (Exception $e) {
    echo "<p style='color: red;'>❌ file_uploads table error: " . $e->getMessage() . "</p>";
}

// Test 2: Test array handling functions
echo "<h3>2. Array Handling Test</h3>";

// Simulate form data that would cause array_map errors
$testCases = [
    'Empty array' => [],
    'String value' => 'test,value,string',
    'Null value' => null,
    'Array value' => ['test', 'value', 'array'],
    'Empty string' => ''
];

foreach ($testCases as $label => $testData) {
    try {
        $result = json_encode(array_filter(array_map('sanitize', is_array($testData ?? []) ? $testData : explode(',', $testData ?? ''))));
        echo "<p style='color: green;'>✅ $label: $result</p>";
    } catch (Exception $e) {
        echo "<p style='color: red;'>❌ $label failed: " . $e->getMessage() . "</p>";
    }
}

// Test 3: Test image upload with correct database schema
echo "<h3>3. Image Upload Database Test</h3>";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['test_image'])) {
    $result = handleImageUpload('test_image', 'images/test');
    
    if ($result['success']) {
        echo "<p style='color: green;'>✅ Image uploaded successfully!</p>";
        echo "<p><strong>File ID:</strong> {$result['file_id']}</p>";
        echo "<p><strong>Filename:</strong> {$result['filename']}</p>";
        echo "<p><strong>File Path:</strong> {$result['file_path']}</p>";
        echo "<p><strong>URL:</strong> <a href='{$result['url']}' target='_blank'>{$result['url']}</a></p>";
        echo "<p><strong>Size:</strong> " . formatFileSize($result['size']) . "</p>";
        
        if (isset($result['message'])) {
            echo "<p style='color: orange;'>⚠️ <strong>Note:</strong> {$result['message']}</p>";
        }
        
        // Verify database record
        try {
            $fileRecord = dbGetRow("SELECT * FROM file_uploads WHERE id = ?", [$result['file_id']]);
            if ($fileRecord) {
                echo "<p style='color: green;'>✅ Database record created successfully</p>";
                echo "<details><summary>Database Record</summary>";
                echo "<pre>" . print_r($fileRecord, true) . "</pre>";
                echo "</details>";
            } else {
                echo "<p style='color: red;'>❌ Database record not found</p>";
            }
        } catch (Exception $e) {
            echo "<p style='color: red;'>❌ Database verification error: " . $e->getMessage() . "</p>";
        }
        
        echo "<div style='margin: 20px 0;'>";
        echo "<img src='{$result['url']}' alt='Uploaded image' style='max-width: 300px; border: 1px solid #ddd; border-radius: 4px;'>";
        echo "</div>";
    } else {
        echo "<p style='color: red;'>❌ Image upload failed: {$result['error']}</p>";
    }
}

echo "<form method='POST' enctype='multipart/form-data' style='margin: 20px 0; padding: 20px; border: 1px solid #ddd; border-radius: 4px;'>";
echo "<h4>Test Image Upload with Database</h4>";
echo "<p>Upload an image to test the database schema fixes:</p>";
echo "<input type='file' name='test_image' accept='image/*' required style='margin: 10px 0;'>";
echo "<br><button type='submit' style='padding: 8px 16px; background: #007bff; color: white; border: none; border-radius: 4px;'>Upload Test Image</button>";
echo "</form>";

// Test 4: Test getCurrentUserId function
echo "<h3>4. User ID Function Test</h3>";
$currentUserId = getCurrentUserId();
if ($currentUserId) {
    echo "<p style='color: green;'>✅ getCurrentUserId() returns: $currentUserId</p>";
} else {
    echo "<p style='color: orange;'>⚠️ getCurrentUserId() returns null (user not logged in)</p>";
}

// Test 5: Test edit form links
echo "<h3>5. Edit Forms Test</h3>";
$editForms = [
    'product_edit.php' => 'Product Edit',
    'service_edit.php' => 'Service Edit',
    'training_edit.php' => 'Training Edit',
    'research_edit.php' => 'Research Edit'
];

echo "<p>Test these forms to verify image upload and array handling work:</p>";
echo "<ul>";
foreach ($editForms as $form => $title) {
    echo "<li><a href='$form' target='_blank' style='color: #007bff;'>🔗 Test $title</a></li>";
}
echo "</ul>";

// Test 6: Database connection and functions
echo "<h3>6. Database Functions Test</h3>";
$dbFunctions = ['dbGetRow', 'dbGetRows', 'dbInsert', 'dbExecute', 'dbGetValue'];
foreach ($dbFunctions as $func) {
    if (function_exists($func)) {
        echo "<p style='color: green;'>✅ Function $func exists</p>";
    } else {
        echo "<p style='color: red;'>❌ Function $func missing</p>";
    }
}

// Test 7: Simulate form data processing
echo "<h3>7. Form Data Processing Simulation</h3>";

// Simulate POST data that would come from forms
$simulatedPosts = [
    'research' => [
        'authors' => 'John Doe, Jane Smith',
        'keywords' => 'surveying, mapping, GIS',
        'gallery' => null,
        'documents' => ''
    ],
    'training' => [
        'features' => 'Feature 1, Feature 2',
        'curriculum' => null,
        'gallery' => ['image1.jpg', 'image2.jpg']
    ],
    'service' => [
        'languages' => 'English, French',
        'features' => null
    ],
    'product' => [
        'specifications' => 'Spec 1, Spec 2, Spec 3'
    ]
];

foreach ($simulatedPosts as $formType => $postData) {
    echo "<h4>$formType Form Data:</h4>";
    foreach ($postData as $field => $value) {
        try {
            $processed = json_encode(array_filter(array_map('sanitize', is_array($value ?? []) ? $value : explode(',', $value ?? ''))));
            echo "<p style='color: green;'>✅ $field: $processed</p>";
        } catch (Exception $e) {
            echo "<p style='color: red;'>❌ $field failed: " . $e->getMessage() . "</p>";
        }
    }
}

echo "<h3>🎯 Summary</h3>";
echo "<p><strong>Database Schema Fixes:</strong></p>";
echo "<ul>";
echo "<li>✅ Fixed column name mismatch (filename → file_name)</li>";
echo "<li>✅ Removed non-existent is_public column</li>";
echo "<li>✅ Fixed file_type to use ENUM value 'image'</li>";
echo "<li>✅ Added null handling for uploaded_by field</li>";
echo "</ul>";

echo "<p><strong>Array Handling Fixes:</strong></p>";
echo "<ul>";
echo "<li>✅ Added is_array() checks before array_map()</li>";
echo "<li>✅ Added explode() fallback for string values</li>";
echo "<li>✅ Added null coalescing for undefined fields</li>";
echo "<li>✅ Fixed all edit forms (research, training, service, product)</li>";
echo "</ul>";

echo "<style>
table { border-collapse: collapse; margin: 10px 0; }
th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
th { background-color: #f2f2f2; }
details { margin: 10px 0; }
summary { cursor: pointer; font-weight: bold; }
pre { background: #f8f9fa; padding: 10px; border-radius: 4px; overflow-x: auto; }
</style>";
?>
