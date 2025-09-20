<?php
/**
 * Database Setup Script for FSMC Project
 * This script creates the database, tables, and populates with sample data
 */

// Database configuration
$host = 'localhost';
$username = 'root';
$password = '';
$database = 'fsmc_database';

echo "<h2>FSMC Database Setup</h2>\n";
echo "<p>Starting database setup process...</p>\n";

try {
    // Create connection without selecting database first
    $mysqli = new mysqli($host, $username, $password);
    
    if ($mysqli->connect_error) {
        throw new Exception("Connection failed: " . $mysqli->connect_error);
    }
    
    echo "<p>✓ Connected to MySQL server</p>\n";
    
    // Create database
    $sql = "CREATE DATABASE IF NOT EXISTS $database CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci";
    if ($mysqli->query($sql)) {
        echo "<p>✓ Database '$database' created successfully</p>\n";
    } else {
        throw new Exception("Error creating database: " . $mysqli->error);
    }
    
    // Select the database
    $mysqli->select_db($database);
    echo "<p>✓ Selected database '$database'</p>\n";
    
    // Read and execute schema file
    $schemaFile = __DIR__ . '/schema.sql';
    if (!file_exists($schemaFile)) {
        throw new Exception("Schema file not found: $schemaFile");
    }
    
    $schema = file_get_contents($schemaFile);
    
    // Remove the CREATE DATABASE and USE statements since we already handled them
    $schema = preg_replace('/CREATE DATABASE.*?;/i', '', $schema);
    $schema = preg_replace('/USE.*?;/i', '', $schema);
    
    // Split into individual statements
    $statements = array_filter(array_map('trim', explode(';', $schema)));
    
    echo "<p>Executing schema statements...</p>\n";
    foreach ($statements as $statement) {
        if (!empty($statement)) {
            if ($mysqli->query($statement)) {
                // Extract table name for better feedback
                if (preg_match('/CREATE TABLE\s+(\w+)/i', $statement, $matches)) {
                    echo "<p>✓ Created table: {$matches[1]}</p>\n";
                } elseif (preg_match('/CREATE INDEX\s+(\w+)/i', $statement, $matches)) {
                    echo "<p>✓ Created index: {$matches[1]}</p>\n";
                }
            } else {
                echo "<p>⚠ Warning executing statement: " . $mysqli->error . "</p>\n";
            }
        }
    }
    
    // Read and execute sample data file
    $sampleDataFile = __DIR__ . '/sample_data.sql';
    if (!file_exists($sampleDataFile)) {
        throw new Exception("Sample data file not found: $sampleDataFile");
    }
    
    $sampleData = file_get_contents($sampleDataFile);
    
    // Remove the USE statement
    $sampleData = preg_replace('/USE.*?;/i', '', $sampleData);
    
    // Split into individual statements
    $dataStatements = array_filter(array_map('trim', explode(';', $sampleData)));
    
    echo "<p>Inserting sample data...</p>\n";
    foreach ($dataStatements as $statement) {
        if (!empty($statement)) {
            if ($mysqli->query($statement)) {
                // Extract table name for better feedback
                if (preg_match('/INSERT INTO\s+(\w+)/i', $statement, $matches)) {
                    echo "<p>✓ Inserted data into: {$matches[1]}</p>\n";
                } elseif (preg_match('/UPDATE\s+(\w+)/i', $statement, $matches)) {
                    echo "<p>✓ Updated data in: {$matches[1]}</p>\n";
                }
            } else {
                echo "<p>⚠ Warning inserting data: " . $mysqli->error . "</p>\n";
            }
        }
    }
    
    // Verify setup by counting records in each table
    echo "<h3>Database Setup Summary:</h3>\n";
    $tables = [
        'users', 'product_categories', 'products', 'service_categories', 
        'services', 'training_categories', 'training_programs', 
        'research_projects', 'messages', 'contact_submissions'
    ];
    
    foreach ($tables as $table) {
        $result = $mysqli->query("SELECT COUNT(*) as count FROM $table");
        if ($result) {
            $row = $result->fetch_assoc();
            echo "<p>✓ Table '$table': {$row['count']} records</p>\n";
        }
    }
    
    echo "<h3>✅ Database setup completed successfully!</h3>\n";
    echo "<p>You can now use the FSMC application with the populated database.</p>\n";
    
} catch (Exception $e) {
    echo "<h3>❌ Error during setup:</h3>\n";
    echo "<p>" . $e->getMessage() . "</p>\n";
} finally {
    if (isset($mysqli)) {
        $mysqli->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FSMC Database Setup</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 800px;
            margin: 50px auto;
            padding: 20px;
            background-color: #f5f5f5;
        }
        h2, h3 {
            color: #1a5276;
        }
        p {
            margin: 5px 0;
            padding: 5px;
            background-color: white;
            border-left: 4px solid #2e86c1;
        }
        .success {
            border-left-color: #27ae60;
        }
        .warning {
            border-left-color: #f39c12;
        }
        .error {
            border-left-color: #e74c3c;
        }
    </style>
</head>
<body>
    <div style="text-align: center; margin-top: 30px;">
        <a href="../admin/index.php" style="background-color: #1a5276; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;">Go to Admin Panel</a>
        <a href="../index.php" style="background-color: #2e86c1; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; margin-left: 10px;">Go to Website</a>
    </div>
</body>
</html>
