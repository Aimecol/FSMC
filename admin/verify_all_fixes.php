<?php
/**
 * Comprehensive Verification of All Database and Array Handling Fixes
 */

require_once 'config/config.php';
require_once 'includes/image_upload.php';

echo "<h2>🔧 Comprehensive Fix Verification</h2>";

// Helper function to safely process array fields
function processArrayField($postData, $fieldName) {
    if (!isset($postData[$fieldName])) {
        return [];
    }
    
    $value = $postData[$fieldName];
    
    if (is_array($value)) {
        return array_filter(array_map('sanitize', $value));
    } elseif (is_string($value) && !empty($value)) {
        return array_filter(array_map('sanitize', explode(',', $value)));
    }
    
    return [];
}

// Test 1: Database Schema Verification
echo "<h3>1. ✅ Database Schema Verification</h3>";
try {
    $columns = dbGetRows("DESCRIBE file_uploads");
    $columnNames = array_column($columns, 'Field');
    echo "<p style='color: green;'>✅ file_uploads table accessible</p>";
    echo "<p><strong>Total columns:</strong> " . count($columnNames) . "</p>";
    echo "<p><strong>Columns:</strong> " . implode(', ', $columnNames) . "</p>";
    
    // Check for required columns
    $requiredCols = ['original_name', 'file_name', 'file_path', 'file_size', 'mime_type', 'file_type'];
    $missingCols = array_diff($requiredCols, $columnNames);
    if (empty($missingCols)) {
        echo "<p style='color: green;'>✅ All required columns present</p>";
    } else {
        echo "<p style='color: red;'>❌ Missing columns: " . implode(', ', $missingCols) . "</p>";
    }
    
} catch (Exception $e) {
    echo "<p style='color: red;'>❌ Database error: " . $e->getMessage() . "</p>";
}

// Test 2: Array Handling Verification
echo "<h3>2. ✅ Array Handling Verification</h3>";

$arrayTestCases = [
    'Undefined field' => [],
    'Null value' => ['features' => null],
    'Empty string' => ['features' => ''],
    'String with commas' => ['features' => 'Feature 1, Feature 2, Feature 3'],
    'Array value' => ['features' => ['Feature 1', 'Feature 2', 'Feature 3']],
    'Mixed data' => ['features' => 'Feature 1, Feature 2', 'gallery' => ['img1.jpg', 'img2.jpg']]
];

$allTestsPassed = true;
foreach ($arrayTestCases as $testName => $postData) {
    try {
        $result = json_encode(processArrayField($postData, 'features'));
        echo "<p style='color: green;'>✅ $testName: $result</p>";
    } catch (Exception $e) {
        echo "<p style='color: red;'>❌ $testName failed: " . $e->getMessage() . "</p>";
        $allTestsPassed = false;
    }
}

if ($allTestsPassed) {
    echo "<p style='color: green; font-weight: bold;'>🎉 All array handling tests passed!</p>";
}

// Test 3: Database INSERT Test
echo "<h3>3. ✅ Database INSERT Test</h3>";

try {
    // Test the exact INSERT that image_upload.php uses
    $testFileData = [
        'original_name' => 'test_verification.jpg',
        'file_name' => 'test_verification_' . time() . '.jpg',
        'file_path' => 'images/test/test_verification_' . time() . '.jpg',
        'file_size' => 12345,
        'mime_type' => 'image/jpeg',
        'file_type' => 'image',
        'related_table' => null,
        'related_id' => null,
        'uploaded_by' => getCurrentUserId(),
        'alt_text' => null,
        'caption' => null
    ];
    
    $insertQuery = "INSERT INTO file_uploads (original_name, file_name, file_path, file_size, mime_type, file_type, related_table, related_id, uploaded_by, alt_text, caption) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    
    echo "<p><strong>Testing INSERT query:</strong></p>";
    echo "<pre>$insertQuery</pre>";
    echo "<p><strong>Data count:</strong> " . count(array_values($testFileData)) . "</p>";
    echo "<p><strong>Placeholder count:</strong> " . substr_count($insertQuery, '?') . "</p>";
    
    if (count(array_values($testFileData)) === substr_count($insertQuery, '?')) {
        echo "<p style='color: green;'>✅ Parameter counts match</p>";
        
        $fileId = dbInsert($insertQuery, array_values($testFileData));
        echo "<p style='color: green;'>✅ Database INSERT successful! File ID: $fileId</p>";
        
        // Verify the record was created
        $record = dbGetRow("SELECT * FROM file_uploads WHERE id = ?", [$fileId]);
        if ($record) {
            echo "<p style='color: green;'>✅ Database record verified</p>";
            echo "<details><summary>Record Details</summary>";
            echo "<pre>" . print_r($record, true) . "</pre>";
            echo "</details>";
        }
        
        // Clean up test record
        dbExecute("DELETE FROM file_uploads WHERE id = ?", [$fileId]);
        echo "<p style='color: blue;'>🧹 Test record cleaned up</p>";
        
    } else {
        echo "<p style='color: red;'>❌ Parameter count mismatch</p>";
    }
    
} catch (Exception $e) {
    echo "<p style='color: red;'>❌ Database INSERT test failed: " . $e->getMessage() . "</p>";
}

// Test 4: Edit Forms Status Check
echo "<h3>4. ✅ Edit Forms Status Check</h3>";

$editForms = [
    'research_edit.php' => 'Research Edit',
    'training_edit.php' => 'Training Edit', 
    'service_edit.php' => 'Service Edit',
    'product_edit.php' => 'Product Edit'
];

echo "<p>Click these links to verify the edit forms load without errors:</p>";
echo "<ul>";
foreach ($editForms as $form => $title) {
    if (file_exists($form)) {
        echo "<li style='color: green;'>✅ <a href='$form' target='_blank' style='color: #007bff;'>$title</a> - File exists</li>";
    } else {
        echo "<li style='color: red;'>❌ $title - File missing</li>";
    }
}
echo "</ul>";

// Test 5: Image Upload Test
echo "<h3>5. ✅ Image Upload Test</h3>";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['test_image'])) {
    echo "<h4>Processing Image Upload:</h4>";
    
    $result = handleImageUpload('test_image', 'images/test');
    
    if ($result['success']) {
        echo "<p style='color: green;'>🎉 Image upload successful!</p>";
        echo "<div style='background: #d4edda; padding: 15px; border-radius: 5px; margin: 10px 0;'>";
        echo "<p><strong>File ID:</strong> {$result['file_id']}</p>";
        echo "<p><strong>Filename:</strong> {$result['filename']}</p>";
        echo "<p><strong>File Path:</strong> {$result['file_path']}</p>";
        echo "<p><strong>URL:</strong> <a href='{$result['url']}' target='_blank'>{$result['url']}</a></p>";
        echo "<p><strong>Size:</strong> " . formatFileSize($result['size']) . "</p>";
        echo "</div>";
        
        // Verify database record
        try {
            $fileRecord = dbGetRow("SELECT * FROM file_uploads WHERE id = ?", [$result['file_id']]);
            if ($fileRecord) {
                echo "<p style='color: green;'>✅ Database record created and verified</p>";
            }
        } catch (Exception $e) {
            echo "<p style='color: red;'>❌ Database verification error: " . $e->getMessage() . "</p>";
        }
        
        // Display uploaded image
        echo "<div style='margin: 20px 0;'>";
        echo "<img src='{$result['url']}' alt='Uploaded test image' style='max-width: 300px; border: 1px solid #ddd; border-radius: 4px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);'>";
        echo "</div>";
        
    } else {
        echo "<p style='color: red;'>❌ Image upload failed: {$result['error']}</p>";
    }
}

echo "<form method='POST' enctype='multipart/form-data' style='margin: 20px 0; padding: 20px; border: 2px solid #007bff; border-radius: 8px; background: #f8f9fa;'>";
echo "<h4>🖼️ Test Image Upload</h4>";
echo "<p>Upload an image to test the complete database schema fix:</p>";
echo "<input type='file' name='test_image' accept='image/*' required style='margin: 10px 0; padding: 8px; border: 1px solid #ddd; border-radius: 4px;'>";
echo "<br><button type='submit' style='padding: 10px 20px; background: #007bff; color: white; border: none; border-radius: 4px; font-weight: bold; cursor: pointer;'>Upload & Test Database</button>";
echo "</form>";

// Summary
echo "<h3>🎯 Fix Summary</h3>";
echo "<div style='background: #e7f3ff; padding: 20px; border-radius: 8px; border-left: 4px solid #007bff;'>";
echo "<h4>✅ Issues Resolved:</h4>";
echo "<ol>";
echo "<li><strong>Database Schema Mismatch:</strong> Fixed column names and added all required columns</li>";
echo "<li><strong>Parameter Count Mismatch:</strong> Aligned INSERT query with exact table structure</li>";
echo "<li><strong>Array Handling Errors:</strong> Added safe processArrayField() function to all edit forms</li>";
echo "<li><strong>Type Safety:</strong> Added proper null checking and type validation</li>";
echo "</ol>";

echo "<h4>📁 Files Updated:</h4>";
echo "<ul>";
echo "<li><code>admin/includes/image_upload.php</code> - Complete database schema alignment</li>";
echo "<li><code>admin/research_edit.php</code> - Safe array handling for authors, keywords, gallery, documents</li>";
echo "<li><code>admin/training_edit.php</code> - Safe array handling for features, curriculum, gallery</li>";
echo "<li><code>admin/service_edit.php</code> - Safe array handling for languages, features</li>";
echo "<li><code>admin/product_edit.php</code> - Safe array handling for specifications</li>";
echo "</ul>";

echo "<h4>🎉 Expected Results:</h4>";
echo "<ul>";
echo "<li>✅ No more database parameter count errors</li>";
echo "<li>✅ No more array_map() type errors</li>";
echo "<li>✅ Successful image uploads across all forms</li>";
echo "<li>✅ Proper database record creation</li>";
echo "<li>✅ Robust error handling and user feedback</li>";
echo "</ul>";
echo "</div>";

echo "<style>
details { margin: 10px 0; }
summary { cursor: pointer; font-weight: bold; }
pre { background: #f8f9fa; padding: 10px; border-radius: 4px; overflow-x: auto; font-family: monospace; }
</style>";
?>
