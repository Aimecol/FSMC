<?php
/**
 * Test Array Handling Fixes
 */

require_once 'config/config.php';

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

echo "<h2>🧪 Array Handling Fixes Test</h2>";

// Test cases that would previously cause errors
$testCases = [
    'Empty POST data' => [],
    'Missing fields' => ['title' => 'Test'],
    'Null values' => ['features' => null, 'gallery' => null],
    'Empty strings' => ['features' => '', 'gallery' => ''],
    'String values' => ['features' => 'Feature 1, Feature 2', 'gallery' => 'image1.jpg, image2.jpg'],
    'Array values' => ['features' => ['Feature 1', 'Feature 2'], 'gallery' => ['image1.jpg', 'image2.jpg']],
    'Mixed values' => ['features' => 'Feature 1, Feature 2', 'gallery' => ['image1.jpg', 'image2.jpg']]
];

echo "<h3>Test Results:</h3>";

foreach ($testCases as $testName => $postData) {
    echo "<h4>$testName:</h4>";
    echo "<p><strong>Input:</strong> " . json_encode($postData) . "</p>";
    
    try {
        // Test different field types
        $fields = ['features', 'gallery', 'authors', 'keywords', 'curriculum', 'languages', 'specifications', 'documents'];
        
        $results = [];
        foreach ($fields as $field) {
            $processed = processArrayField($postData, $field);
            $results[$field] = json_encode($processed);
        }
        
        echo "<p style='color: green;'>✅ <strong>Success!</strong></p>";
        echo "<details><summary>Results</summary>";
        echo "<ul>";
        foreach ($results as $field => $result) {
            echo "<li><strong>$field:</strong> $result</li>";
        }
        echo "</ul>";
        echo "</details>";
        
    } catch (Exception $e) {
        echo "<p style='color: red;'>❌ <strong>Error:</strong> " . $e->getMessage() . "</p>";
    }
    
    echo "<hr>";
}

// Test form submission simulation
echo "<h3>Form Submission Simulation:</h3>";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
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
        
        echo "<p style='color: green;'>✅ <strong>Form processing successful!</strong></p>";
        echo "<details><summary>Processed Data</summary>";
        echo "<pre>" . print_r($formData, true) . "</pre>";
        echo "</details>";
        
    } catch (Exception $e) {
        echo "<p style='color: red;'>❌ <strong>Form processing failed:</strong> " . $e->getMessage() . "</p>";
    }
}

echo "<form method='POST' style='margin: 20px 0; padding: 20px; border: 1px solid #ddd; border-radius: 4px;'>";
echo "<h4>Test Form Submission:</h4>";
echo "<p>Submit this form to test array handling with actual POST data:</p>";

echo "<label>Title: <input type='text' name='title' value='Test Item' style='margin-left: 10px;'></label><br><br>";
echo "<label>Description: <textarea name='description' style='margin-left: 10px;'>Test description</textarea></label><br><br>";

echo "<label>Features (comma-separated): <input type='text' name='features' value='Feature 1, Feature 2, Feature 3' style='margin-left: 10px; width: 300px;'></label><br><br>";
echo "<label>Gallery (comma-separated): <input type='text' name='gallery' value='image1.jpg, image2.jpg' style='margin-left: 10px; width: 300px;'></label><br><br>";
echo "<label>Authors (comma-separated): <input type='text' name='authors' value='John Doe, Jane Smith' style='margin-left: 10px; width: 300px;'></label><br><br>";
echo "<label>Keywords (comma-separated): <input type='text' name='keywords' value='surveying, mapping, GIS' style='margin-left: 10px; width: 300px;'></label><br><br>";

echo "<button type='submit' style='padding: 8px 16px; background: #007bff; color: white; border: none; border-radius: 4px;'>Test Form Processing</button>";
echo "</form>";

echo "<h3>🎯 Summary:</h3>";
echo "<p><strong>Array Handling Fixes Applied:</strong></p>";
echo "<ul>";
echo "<li>✅ Added <code>processArrayField()</code> helper function</li>";
echo "<li>✅ Safe handling of missing POST fields</li>";
echo "<li>✅ Type checking before array operations</li>";
echo "<li>✅ Fallback to explode() for string values</li>";
echo "<li>✅ Empty array return for null/empty values</li>";
echo "<li>✅ Applied to all edit forms (research, training, service, product)</li>";
echo "</ul>";

echo "<p><strong>Files Updated:</strong></p>";
echo "<ul>";
echo "<li>✅ <code>admin/research_edit.php</code> - authors, keywords, gallery, documents</li>";
echo "<li>✅ <code>admin/training_edit.php</code> - features, curriculum, gallery</li>";
echo "<li>✅ <code>admin/service_edit.php</code> - languages, features</li>";
echo "<li>✅ <code>admin/product_edit.php</code> - specifications</li>";
echo "</ul>";

echo "<style>
details { margin: 10px 0; }
summary { cursor: pointer; font-weight: bold; }
pre { background: #f8f9fa; padding: 10px; border-radius: 4px; overflow-x: auto; }
hr { margin: 20px 0; }
label { display: block; margin: 5px 0; }
input, textarea { padding: 5px; border: 1px solid #ddd; border-radius: 3px; }
</style>";
?>
