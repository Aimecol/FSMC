<?php
require_once 'config/config.php';

echo "<h2>Database INSERT Issue Debug</h2>";

// Check file_uploads table structure
try {
    $columns = dbGetRows("DESCRIBE file_uploads");
    echo "<h3>file_uploads Table Structure:</h3>";
    echo "<table border='1' style='border-collapse: collapse;'>";
    echo "<tr><th>Field</th><th>Type</th><th>Null</th><th>Key</th><th>Default</th><th>Extra</th></tr>";
    
    $requiredColumns = [];
    $optionalColumns = [];
    
    foreach ($columns as $col) {
        echo "<tr>";
        echo "<td>{$col['Field']}</td>";
        echo "<td>{$col['Type']}</td>";
        echo "<td>{$col['Null']}</td>";
        echo "<td>{$col['Key']}</td>";
        echo "<td>{$col['Default']}</td>";
        echo "<td>{$col['Extra']}</td>";
        echo "</tr>";
        
        // Categorize columns
        if ($col['Null'] === 'NO' && $col['Default'] === null && $col['Extra'] !== 'auto_increment') {
            $requiredColumns[] = $col['Field'];
        } else {
            $optionalColumns[] = $col['Field'];
        }
    }
    echo "</table>";
    
    echo "<h3>Column Analysis:</h3>";
    echo "<p><strong>Required columns (NOT NULL, no default):</strong> " . implode(', ', $requiredColumns) . "</p>";
    echo "<p><strong>Optional columns (NULL allowed or has default):</strong> " . implode(', ', $optionalColumns) . "</p>";
    
} catch (Exception $e) {
    echo "<p style='color: red;'>Error: " . $e->getMessage() . "</p>";
}

// Test different INSERT approaches
echo "<h3>Testing INSERT Approaches:</h3>";

$testData = [
    'original_name' => 'test.jpg',
    'file_name' => 'test_123.jpg',
    'file_path' => 'images/test/test_123.jpg',
    'file_size' => 12345,
    'mime_type' => 'image/jpeg',
    'file_type' => 'image',
    'uploaded_by' => 1
];

// Approach 1: Current approach
echo "<h4>Approach 1: Current (7 columns)</h4>";
$query1 = "INSERT INTO file_uploads (original_name, file_name, file_path, file_size, mime_type, file_type, uploaded_by) VALUES (?, ?, ?, ?, ?, ?, ?)";
echo "<p><strong>Query:</strong> $query1</p>";
echo "<p><strong>Values:</strong> " . implode(', ', array_values($testData)) . "</p>";
echo "<p><strong>Count:</strong> " . count(array_values($testData)) . " values, " . substr_count($query1, '?') . " placeholders</p>";

// Approach 2: Include all required columns
echo "<h4>Approach 2: All Required Columns</h4>";
$testData2 = [
    'original_name' => 'test.jpg',
    'file_name' => 'test_123.jpg',
    'file_path' => 'images/test/test_123.jpg',
    'file_size' => 12345,
    'mime_type' => 'image/jpeg',
    'file_type' => 'image'
];

$query2 = "INSERT INTO file_uploads (original_name, file_name, file_path, file_size, mime_type, file_type) VALUES (?, ?, ?, ?, ?, ?)";
echo "<p><strong>Query:</strong> $query2</p>";
echo "<p><strong>Values:</strong> " . implode(', ', array_values($testData2)) . "</p>";
echo "<p><strong>Count:</strong> " . count(array_values($testData2)) . " values, " . substr_count($query2, '?') . " placeholders</p>";

// Test actual INSERT
echo "<h3>Testing Actual INSERT:</h3>";

try {
    // Test with minimal required data
    $result = dbInsert($query2, array_values($testData2));
    echo "<p style='color: green;'>✅ INSERT successful! File ID: $result</p>";
    
    // Clean up test record
    dbExecute("DELETE FROM file_uploads WHERE id = ?", [$result]);
    echo "<p style='color: blue;'>🧹 Test record cleaned up</p>";
    
} catch (Exception $e) {
    echo "<p style='color: red;'>❌ INSERT failed: " . $e->getMessage() . "</p>";
    
    // Try with uploaded_by as NULL
    try {
        $testData3 = $testData2;
        $testData3['uploaded_by'] = null;
        $query3 = "INSERT INTO file_uploads (original_name, file_name, file_path, file_size, mime_type, file_type, uploaded_by) VALUES (?, ?, ?, ?, ?, ?, ?)";
        
        $result = dbInsert($query3, array_values($testData3));
        echo "<p style='color: green;'>✅ INSERT with NULL uploaded_by successful! File ID: $result</p>";
        
        // Clean up test record
        dbExecute("DELETE FROM file_uploads WHERE id = ?", [$result]);
        echo "<p style='color: blue;'>🧹 Test record cleaned up</p>";
        
    } catch (Exception $e2) {
        echo "<p style='color: red;'>❌ INSERT with NULL uploaded_by also failed: " . $e2->getMessage() . "</p>";
    }
}

// Test getCurrentUserId function
echo "<h3>Testing getCurrentUserId Function:</h3>";
$currentUserId = getCurrentUserId();
if ($currentUserId) {
    echo "<p style='color: green;'>✅ getCurrentUserId() returns: $currentUserId (type: " . gettype($currentUserId) . ")</p>";
} else {
    echo "<p style='color: orange;'>⚠️ getCurrentUserId() returns: " . var_export($currentUserId, true) . " (type: " . gettype($currentUserId) . ")</p>";
}

echo "<style>
table { border-collapse: collapse; margin: 10px 0; }
th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
th { background-color: #f2f2f2; }
pre { background: #f8f9fa; padding: 10px; border-radius: 4px; overflow-x: auto; }
</style>";
?>
