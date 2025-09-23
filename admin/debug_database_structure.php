<?php
require_once 'config/config.php';

echo "<h2>Database Structure Debug</h2>";

// Check file_uploads table structure
try {
    $columns = dbGetRows("DESCRIBE file_uploads");
    echo "<h3>file_uploads Table Structure:</h3>";
    echo "<table border='1' style='border-collapse: collapse;'>";
    echo "<tr><th>Field</th><th>Type</th><th>Null</th><th>Key</th><th>Default</th><th>Extra</th></tr>";
    foreach ($columns as $col) {
        echo "<tr>";
        echo "<td>{$col['Field']}</td>";
        echo "<td>{$col['Type']}</td>";
        echo "<td>{$col['Null']}</td>";
        echo "<td>{$col['Key']}</td>";
        echo "<td>{$col['Default']}</td>";
        echo "<td>{$col['Extra']}</td>";
        echo "</tr>";
    }
    echo "</table>";
    
    // Count columns
    echo "<p><strong>Total columns:</strong> " . count($columns) . "</p>";
    
    // Show column names
    $columnNames = array_column($columns, 'Field');
    echo "<p><strong>Column names:</strong> " . implode(', ', $columnNames) . "</p>";
    
} catch (Exception $e) {
    echo "<p style='color: red;'>Error: " . $e->getMessage() . "</p>";
}

// Test the INSERT query structure
echo "<h3>Testing INSERT Query Structure:</h3>";

$testData = [
    'original_name' => 'test.jpg',
    'file_name' => 'test_123.jpg',
    'file_path' => 'images/test/test_123.jpg',
    'file_size' => 12345,
    'mime_type' => 'image/jpeg',
    'file_type' => 'image',
    'uploaded_by' => 1
];

echo "<p><strong>Test data array:</strong></p>";
echo "<pre>" . print_r($testData, true) . "</pre>";

echo "<p><strong>Array values:</strong></p>";
echo "<pre>" . print_r(array_values($testData), true) . "</pre>";

echo "<p><strong>Number of values:</strong> " . count(array_values($testData)) . "</p>";

$query = "INSERT INTO file_uploads (original_name, file_name, file_path, file_size, mime_type, file_type, uploaded_by) VALUES (?, ?, ?, ?, ?, ?, ?)";
echo "<p><strong>Query:</strong></p>";
echo "<pre>$query</pre>";

// Count placeholders
$placeholderCount = substr_count($query, '?');
echo "<p><strong>Number of placeholders (?):</strong> $placeholderCount</p>";

// Test if they match
$valueCount = count(array_values($testData));
if ($placeholderCount === $valueCount) {
    echo "<p style='color: green;'>✅ Placeholders match values ($placeholderCount = $valueCount)</p>";
} else {
    echo "<p style='color: red;'>❌ Mismatch: $placeholderCount placeholders vs $valueCount values</p>";
}

echo "<style>
table { border-collapse: collapse; margin: 10px 0; }
th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
th { background-color: #f2f2f2; }
pre { background: #f8f9fa; padding: 10px; border-radius: 4px; overflow-x: auto; }
</style>";
?>
