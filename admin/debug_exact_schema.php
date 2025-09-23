<?php
require_once 'config/config.php';

echo "<h2>🔍 Exact Database Schema Debug</h2>";

// Get the exact table structure
try {
    $columns = dbGetRows("DESCRIBE file_uploads");
    echo "<h3>file_uploads Table Structure:</h3>";
    echo "<table border='1' style='border-collapse: collapse;'>";
    echo "<tr><th>Field</th><th>Type</th><th>Null</th><th>Key</th><th>Default</th><th>Extra</th></tr>";
    
    $allColumns = [];
    $requiredColumns = [];
    $autoIncrementColumns = [];
    $nullableColumns = [];
    
    foreach ($columns as $col) {
        $field = $col['Field'];
        $null = $col['Null'];
        $default = $col['Default'];
        $extra = $col['Extra'];
        
        echo "<tr>";
        echo "<td><strong>$field</strong></td>";
        echo "<td>{$col['Type']}</td>";
        echo "<td>$null</td>";
        echo "<td>{$col['Key']}</td>";
        echo "<td>" . ($default === null ? 'NULL' : $default) . "</td>";
        echo "<td>$extra</td>";
        echo "</tr>";
        
        $allColumns[] = $field;
        
        if ($extra === 'auto_increment') {
            $autoIncrementColumns[] = $field;
        } elseif ($null === 'NO' && $default === null && $extra !== 'auto_increment') {
            $requiredColumns[] = $field;
        } else {
            $nullableColumns[] = $field;
        }
    }
    echo "</table>";
    
    echo "<h3>Column Analysis:</h3>";
    echo "<p><strong>All columns:</strong> " . implode(', ', $allColumns) . "</p>";
    echo "<p><strong>Auto-increment columns:</strong> " . implode(', ', $autoIncrementColumns) . "</p>";
    echo "<p><strong>Required columns (NOT NULL, no default, not auto-increment):</strong> " . implode(', ', $requiredColumns) . "</p>";
    echo "<p><strong>Nullable/Default columns:</strong> " . implode(', ', $nullableColumns) . "</p>";
    
    // Test different INSERT approaches
    echo "<h3>Testing INSERT Approaches:</h3>";
    
    // Approach 1: Only truly required columns
    $insertColumns1 = $requiredColumns;
    echo "<h4>Approach 1: Only Required Columns</h4>";
    echo "<p><strong>Columns:</strong> " . implode(', ', $insertColumns1) . "</p>";
    echo "<p><strong>Count:</strong> " . count($insertColumns1) . "</p>";
    
    if (!empty($insertColumns1)) {
        $placeholders1 = str_repeat('?, ', count($insertColumns1) - 1) . '?';
        $query1 = "INSERT INTO file_uploads (" . implode(', ', $insertColumns1) . ") VALUES ($placeholders1)";
        echo "<p><strong>Query:</strong></p>";
        echo "<pre>$query1</pre>";
        
        // Create test data
        $testData1 = [];
        foreach ($insertColumns1 as $col) {
            switch ($col) {
                case 'original_name':
                    $testData1[] = 'test.jpg';
                    break;
                case 'file_name':
                    $testData1[] = 'test_' . time() . '.jpg';
                    break;
                case 'file_path':
                    $testData1[] = 'images/test/test_' . time() . '.jpg';
                    break;
                case 'file_size':
                    $testData1[] = 12345;
                    break;
                case 'mime_type':
                    $testData1[] = 'image/jpeg';
                    break;
                case 'file_type':
                    $testData1[] = 'image';
                    break;
                default:
                    $testData1[] = 'default_value';
            }
        }
        
        echo "<p><strong>Test data:</strong> " . implode(', ', $testData1) . "</p>";
        echo "<p><strong>Data count:</strong> " . count($testData1) . "</p>";
        echo "<p><strong>Placeholder count:</strong> " . substr_count($query1, '?') . "</p>";
        
        if (count($testData1) === substr_count($query1, '?')) {
            echo "<p style='color: green;'>✅ Counts match for Approach 1!</p>";
            
            try {
                $result1 = dbInsert($query1, $testData1);
                echo "<p style='color: green;'>✅ Approach 1 INSERT successful! File ID: $result1</p>";
                
                // Clean up
                dbExecute("DELETE FROM file_uploads WHERE id = ?", [$result1]);
                echo "<p style='color: blue;'>🧹 Test record cleaned up</p>";
                
            } catch (Exception $e) {
                echo "<p style='color: red;'>❌ Approach 1 INSERT failed: " . $e->getMessage() . "</p>";
            }
        } else {
            echo "<p style='color: red;'>❌ Count mismatch for Approach 1!</p>";
        }
    }
    
    // Approach 2: All columns except auto-increment
    $insertColumns2 = array_diff($allColumns, $autoIncrementColumns);
    echo "<h4>Approach 2: All Columns Except Auto-Increment</h4>";
    echo "<p><strong>Columns:</strong> " . implode(', ', $insertColumns2) . "</p>";
    echo "<p><strong>Count:</strong> " . count($insertColumns2) . "</p>";
    
    $placeholders2 = str_repeat('?, ', count($insertColumns2) - 1) . '?';
    $query2 = "INSERT INTO file_uploads (" . implode(', ', $insertColumns2) . ") VALUES ($placeholders2)";
    echo "<p><strong>Query:</strong></p>";
    echo "<pre>$query2</pre>";
    
    // Create test data for all columns
    $testData2 = [];
    foreach ($insertColumns2 as $col) {
        switch ($col) {
            case 'original_name':
                $testData2[] = 'test.jpg';
                break;
            case 'file_name':
                $testData2[] = 'test_' . time() . '.jpg';
                break;
            case 'file_path':
                $testData2[] = 'images/test/test_' . time() . '.jpg';
                break;
            case 'file_size':
                $testData2[] = 12345;
                break;
            case 'mime_type':
                $testData2[] = 'image/jpeg';
                break;
            case 'file_type':
                $testData2[] = 'image';
                break;
            case 'related_table':
                $testData2[] = null;
                break;
            case 'related_id':
                $testData2[] = null;
                break;
            case 'uploaded_by':
                $testData2[] = getCurrentUserId();
                break;
            case 'alt_text':
                $testData2[] = null;
                break;
            case 'caption':
                $testData2[] = null;
                break;
            default:
                $testData2[] = null;
        }
    }
    
    echo "<p><strong>Test data:</strong> " . implode(', ', array_map(function($v) { return $v === null ? 'NULL' : $v; }, $testData2)) . "</p>";
    echo "<p><strong>Data count:</strong> " . count($testData2) . "</p>";
    echo "<p><strong>Placeholder count:</strong> " . substr_count($query2, '?') . "</p>";
    
    if (count($testData2) === substr_count($query2, '?')) {
        echo "<p style='color: green;'>✅ Counts match for Approach 2!</p>";
        
        try {
            $result2 = dbInsert($query2, $testData2);
            echo "<p style='color: green;'>✅ Approach 2 INSERT successful! File ID: $result2</p>";
            
            // Clean up
            dbExecute("DELETE FROM file_uploads WHERE id = ?", [$result2]);
            echo "<p style='color: blue;'>🧹 Test record cleaned up</p>";
            
            // Generate the correct code
            echo "<h3>✅ Correct Code for image_upload.php:</h3>";
            echo "<pre>";
            echo "// Correct file data array\n";
            echo "\$fileData = [\n";
            foreach ($insertColumns2 as $col) {
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
                    case 'related_table':
                        echo "    'related_table' => null,\n";
                        break;
                    case 'related_id':
                        echo "    'related_id' => null,\n";
                        break;
                    case 'uploaded_by':
                        echo "    'uploaded_by' => \$currentUserId,\n";
                        break;
                    case 'alt_text':
                        echo "    'alt_text' => null,\n";
                        break;
                    case 'caption':
                        echo "    'caption' => null,\n";
                        break;
                    default:
                        echo "    '$col' => null,\n";
                }
            }
            echo "];\n\n";
            echo "// Correct INSERT query\n";
            echo "\$fileId = dbInsert(\n";
            echo "    \"$query2\",\n";
            echo "    array_values(\$fileData)\n";
            echo ");\n";
            echo "</pre>";
            
        } catch (Exception $e) {
            echo "<p style='color: red;'>❌ Approach 2 INSERT failed: " . $e->getMessage() . "</p>";
        }
    } else {
        echo "<p style='color: red;'>❌ Count mismatch for Approach 2!</p>";
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
