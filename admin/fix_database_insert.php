<?php
require_once 'config/config.php';

echo "<h2>🔧 Database INSERT Fix</h2>";

// Get the exact table structure
try {
    $columns = dbGetRows("DESCRIBE file_uploads");
    echo "<h3>file_uploads Table Structure:</h3>";
    echo "<table border='1' style='border-collapse: collapse;'>";
    echo "<tr><th>Field</th><th>Type</th><th>Null</th><th>Key</th><th>Default</th><th>Extra</th></tr>";
    
    $allColumns = [];
    $requiredColumns = [];
    $autoIncrementColumns = [];
    
    foreach ($columns as $col) {
        $field = $col['Field'];
        $null = $col['Null'];
        $default = $col['Default'];
        $extra = $col['Extra'];
        
        echo "<tr>";
        echo "<td>$field</td>";
        echo "<td>{$col['Type']}</td>";
        echo "<td>$null</td>";
        echo "<td>{$col['Key']}</td>";
        echo "<td>$default</td>";
        echo "<td>$extra</td>";
        echo "</tr>";
        
        $allColumns[] = $field;
        
        if ($extra === 'auto_increment') {
            $autoIncrementColumns[] = $field;
        } elseif ($null === 'NO' && $default === null) {
            $requiredColumns[] = $field;
        }
    }
    echo "</table>";
    
    echo "<p><strong>All columns:</strong> " . implode(', ', $allColumns) . "</p>";
    echo "<p><strong>Auto-increment columns:</strong> " . implode(', ', $autoIncrementColumns) . "</p>";
    echo "<p><strong>Required columns (NOT NULL, no default):</strong> " . implode(', ', $requiredColumns) . "</p>";
    
    // Create the correct INSERT statement
    $insertColumns = array_diff($requiredColumns, $autoIncrementColumns);
    echo "<p><strong>Columns needed for INSERT:</strong> " . implode(', ', $insertColumns) . "</p>";
    
    $placeholders = str_repeat('?, ', count($insertColumns) - 1) . '?';
    $correctQuery = "INSERT INTO file_uploads (" . implode(', ', $insertColumns) . ") VALUES ($placeholders)";
    echo "<p><strong>Correct INSERT query:</strong></p>";
    echo "<pre>$correctQuery</pre>";
    
    // Test the correct INSERT
    echo "<h3>Testing Correct INSERT:</h3>";
    
    $testData = [];
    foreach ($insertColumns as $col) {
        switch ($col) {
            case 'original_name':
                $testData[] = 'test.jpg';
                break;
            case 'file_name':
                $testData[] = 'test_' . time() . '.jpg';
                break;
            case 'file_path':
                $testData[] = 'images/test/test_' . time() . '.jpg';
                break;
            case 'file_size':
                $testData[] = 12345;
                break;
            case 'mime_type':
                $testData[] = 'image/jpeg';
                break;
            case 'file_type':
                $testData[] = 'image';
                break;
            case 'uploaded_by':
                $testData[] = getCurrentUserId() ?: null;
                break;
            case 'related_table':
                $testData[] = null;
                break;
            case 'related_id':
                $testData[] = null;
                break;
            default:
                $testData[] = null;
        }
    }
    
    echo "<p><strong>Test data:</strong> " . implode(', ', array_map(function($v) { return $v === null ? 'NULL' : $v; }, $testData)) . "</p>";
    echo "<p><strong>Data count:</strong> " . count($testData) . "</p>";
    echo "<p><strong>Placeholder count:</strong> " . substr_count($correctQuery, '?') . "</p>";
    
    if (count($testData) === substr_count($correctQuery, '?')) {
        echo "<p style='color: green;'>✅ Counts match!</p>";
        
        try {
            $result = dbInsert($correctQuery, $testData);
            echo "<p style='color: green;'>✅ INSERT successful! File ID: $result</p>";
            
            // Clean up
            dbExecute("DELETE FROM file_uploads WHERE id = ?", [$result]);
            echo "<p style='color: blue;'>🧹 Test record cleaned up</p>";
            
            // Generate the fixed code
            echo "<h3>Fixed Code for image_upload.php:</h3>";
            echo "<pre>";
            echo "// Fixed file data array\n";
            echo "\$fileData = [\n";
            foreach ($insertColumns as $col) {
                switch ($col) {
                    case 'original_name':
                        echo "    'original_name' => \$file['name'],\n";
                        break;
                    case 'file_name':
                        echo "    'file_name' => \$newFilename,\n";
                        break;
                    case 'file_path':
                        echo "    'file_path' => \$uploadDir . '/' . \$newFilename,\n";
                        break;
                    case 'file_size':
                        echo "    'file_size' => \$file['size'],\n";
                        break;
                    case 'mime_type':
                        echo "    'mime_type' => \$mimeType,\n";
                        break;
                    case 'file_type':
                        echo "    'file_type' => 'image',\n";
                        break;
                    case 'uploaded_by':
                        echo "    'uploaded_by' => \$currentUserId,\n";
                        break;
                    case 'related_table':
                        echo "    'related_table' => null,\n";
                        break;
                    case 'related_id':
                        echo "    'related_id' => null,\n";
                        break;
                    default:
                        echo "    '$col' => null,\n";
                }
            }
            echo "];\n\n";
            echo "// Fixed INSERT query\n";
            echo "\$fileId = dbInsert(\n";
            echo "    \"$correctQuery\",\n";
            echo "    array_values(\$fileData)\n";
            echo ");\n";
            echo "</pre>";
            
        } catch (Exception $e) {
            echo "<p style='color: red;'>❌ INSERT failed: " . $e->getMessage() . "</p>";
        }
    } else {
        echo "<p style='color: red;'>❌ Count mismatch!</p>";
    }
    
} catch (Exception $e) {
    echo "<p style='color: red;'>Error: " . $e->getMessage() . "</p>";
}

echo "<style>
table { border-collapse: collapse; margin: 10px 0; }
th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
th { background-color: #f2f2f2; }
pre { background: #f8f9fa; padding: 10px; border-radius: 4px; overflow-x: auto; }
</style>";
?>
