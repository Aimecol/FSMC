<?php
/**
 * Final Test for Database Schema and Array Handling Fixes
 */

require_once 'config/config.php';
require_once 'includes/image_upload.php';

echo "<h2>🎯 Final Test - Database Schema and Array Handling Fixes</h2>";

// Test 1: Array handling
echo "<h3>1. Array Handling Test</h3>";

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

$testCases = [
    'Missing field' => [],
    'Null value' => ['features' => null],
    'Empty string' => ['features' => ''],
    'String value' => ['features' => 'Feature 1, Feature 2'],
    'Array value' => ['features' => ['Feature 1', 'Feature 2']]
];

foreach ($testCases as $testName => $postData) {
    try {
        $result = json_encode(processArrayField($postData, 'features'));
        echo "<p style='color: green;'>✅ $testName: $result</p>";
    } catch (Exception $e) {
        echo "<p style='color: red;'>❌ $testName failed: " . $e->getMessage() . "</p>";
    }
}

// Test 2: Database structure verification
echo "<h3>2. Database Structure Verification</h3>";
try {
    $columns = dbGetRows("DESCRIBE file_uploads");
    $columnNames = array_column($columns, 'Field');
    echo "<p style='color: green;'>✅ file_uploads table accessible</p>";
    echo "<p><strong>Columns:</strong> " . implode(', ', $columnNames) . "</p>";
} catch (Exception $e) {
    echo "<p style='color: red;'>❌ Database error: " . $e->getMessage() . "</p>";
}

// Test 3: Image upload functionality
echo "<h3>3. Image Upload Test</h3>";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['test_image'])) {
    $result = handleImageUpload('test_image', 'images/test');
    
    if ($result['success']) {
        echo "<p style='color: green;'>✅ Image upload successful!</p>";
        echo "<p><strong>File ID:</strong> {$result['file_id']}</p>";
        echo "<p><strong>Filename:</strong> {$result['filename']}</p>";
        echo "<p><strong>File Path:</strong> {$result['file_path']}</p>";
        echo "<p><strong>URL:</strong> <a href='{$result['url']}' target='_blank'>{$result['url']}</a></p>";
        
        // Verify database record
        try {
            $fileRecord = dbGetRow("SELECT * FROM file_uploads WHERE id = ?", [$result['file_id']]);
            if ($fileRecord) {
                echo "<p style='color: green;'>✅ Database record created successfully</p>";
                echo "<details><summary>Database Record</summary>";
                echo "<pre>" . print_r($fileRecord, true) . "</pre>";
                echo "</details>";
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
echo "<h4>Test Image Upload</h4>";
echo "<p>Upload an image to test the database schema fixes:</p>";
echo "<input type='file' name='test_image' accept='image/*' required style='margin: 10px 0;'>";
echo "<br><button type='submit' style='padding: 8px 16px; background: #007bff; color: white; border: none; border-radius: 4px;'>Upload Test Image</button>";
echo "</form>";

// Test 4: Form data processing simulation
echo "<h3>4. Form Data Processing Simulation</h3>";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['test_form'])) {
    echo "<h4>Processing Form Data:</h4>";
    
    try {
        // Simulate what the edit forms do
        $formData = [
            'title' => sanitize($_POST['title'] ?? ''),
            'description' => sanitize($_POST['description'] ?? ''),
            'features' => json_encode(processArrayField($_POST, 'features')),
            'gallery' => json_encode(processArrayField($_POST, 'gallery')),
            'authors' => json_encode(processArrayField($_POST, 'authors')),
            'keywords' => json_encode(processArrayField($_POST, 'keywords')),
            'curriculum' => json_encode(processArrayField($_POST, 'curriculum')),
            'languages' => json_encode(processArrayField($_POST, 'languages')),
            'specifications' => json_encode(processArrayField($_POST, 'specifications')),
            'documents' => json_encode(processArrayField($_POST, 'documents'))
        ];
        
        echo "<p style='color: green;'>✅ Form processing successful!</p>";
        echo "<details><summary>Processed Data</summary>";
        echo "<pre>" . print_r($formData, true) . "</pre>";
        echo "</details>";
        
    } catch (Exception $e) {
        echo "<p style='color: red;'>❌ Form processing failed: " . $e->getMessage() . "</p>";
    }
}

echo "<form method='POST' style='margin: 20px 0; padding: 20px; border: 1px solid #ddd; border-radius: 4px;'>";
echo "<input type='hidden' name='test_form' value='1'>";
echo "<h4>Test Form Data Processing</h4>";
echo "<p>Submit this form to test array handling:</p>";

echo "<label>Title: <input type='text' name='title' value='Test Item' style='margin-left: 10px;'></label><br><br>";
echo "<label>Features: <input type='text' name='features' value='Feature 1, Feature 2, Feature 3' style='margin-left: 10px; width: 300px;'></label><br><br>";
echo "<label>Authors: <input type='text' name='authors' value='John Doe, Jane Smith' style='margin-left: 10px; width: 300px;'></label><br><br>";
echo "<label>Keywords: <input type='text' name='keywords' value='surveying, mapping, GIS' style='margin-left: 10px; width: 300px;'></label><br><br>";

echo "<button type='submit' style='padding: 8px 16px; background: #28a745; color: white; border: none; border-radius: 4px;'>Test Form Processing</button>";
echo "</form>";

// Test 5: Edit form links
echo "<h3>5. Edit Forms Test Links</h3>";
$editForms = [
    'product_edit.php' => 'Product Edit',
    'service_edit.php' => 'Service Edit',
    'training_edit.php' => 'Training Edit',
    'research_edit.php' => 'Research Edit'
];

echo "<p>Test these forms to verify they work without errors:</p>";
echo "<ul>";
foreach ($editForms as $form => $title) {
    echo "<li><a href='$form' target='_blank' style='color: #007bff;'>🔗 Test $title</a></li>";
}
echo "</ul>";

echo "<h3>🎉 Summary of Fixes Applied</h3>";
echo "<div style='background: #f8f9fa; padding: 15px; border-radius: 5px; margin: 20px 0;'>";
echo "<h4>✅ Database Schema Fixes:</h4>";
echo "<ul>";
echo "<li>Fixed column name mismatch (filename → file_name)</li>";
echo "<li>Added missing required columns (related_table, related_id)</li>";
echo "<li>Corrected parameter count in INSERT query</li>";
echo "<li>Proper ENUM value usage for file_type</li>";
echo "</ul>";

echo "<h4>✅ Array Handling Fixes:</h4>";
echo "<ul>";
echo "<li>Added processArrayField() helper function</li>";
echo "<li>Safe handling of missing POST fields</li>";
echo "<li>Type checking before array operations</li>";
echo "<li>Fallback to explode() for string values</li>";
echo "<li>Applied to all edit forms (research, training, service, product)</li>";
echo "</ul>";

echo "<h4>📁 Files Updated:</h4>";
echo "<ul>";
echo "<li><code>admin/includes/image_upload.php</code> - Database schema alignment</li>";
echo "<li><code>admin/research_edit.php</code> - Array handling for authors, keywords, gallery, documents</li>";
echo "<li><code>admin/training_edit.php</code> - Array handling for features, curriculum, gallery</li>";
echo "<li><code>admin/service_edit.php</code> - Array handling for languages, features</li>";
echo "<li><code>admin/product_edit.php</code> - Array handling for specifications</li>";
echo "</ul>";
echo "</div>";

echo "<style>
details { margin: 10px 0; }
summary { cursor: pointer; font-weight: bold; }
pre { background: #f8f9fa; padding: 10px; border-radius: 4px; overflow-x: auto; }
label { display: block; margin: 5px 0; }
input, textarea { padding: 5px; border: 1px solid #ddd; border-radius: 3px; }
</style>";
?>
