<?php
/**
 * Database Setup Script for FSMC Admin System
 * Run this script once to create the required tables
 */

require_once 'config/config.php';
require_once 'config/database.php';

// Check if running from command line or browser
$isCLI = php_sapi_name() === 'cli';

if (!$isCLI) {
    echo "<!DOCTYPE html><html><head><title>FSMC Database Setup</title></head><body>";
    echo "<h1>FSMC Database Setup</h1>";
}

function output($message, $isError = false) {
    global $isCLI;
    
    if ($isCLI) {
        echo ($isError ? "ERROR: " : "") . $message . "\n";
    } else {
        $color = $isError ? 'red' : 'green';
        echo "<p style='color: $color;'>" . htmlspecialchars($message) . "</p>";
    }
}

try {
    // Get database connection
    $db = getDB();
    
    output("Connected to database successfully.");
    
    // Read SQL file
    $sqlFile = __DIR__ . '/setup_tables.sql';
    if (!file_exists($sqlFile)) {
        throw new Exception("SQL setup file not found: $sqlFile");
    }
    
    $sql = file_get_contents($sqlFile);
    if ($sql === false) {
        throw new Exception("Failed to read SQL setup file");
    }
    
    // Split SQL into individual statements
    $statements = array_filter(
        array_map('trim', explode(';', $sql)),
        function($stmt) {
            return !empty($stmt) && !preg_match('/^\s*--/', $stmt);
        }
    );
    
    output("Found " . count($statements) . " SQL statements to execute.");
    
    // Execute each statement
    foreach ($statements as $index => $statement) {
        try {
            $db->getConnection()->query($statement);
            
            // Determine what was created/executed
            if (stripos($statement, 'CREATE TABLE') !== false) {
                preg_match('/CREATE TABLE.*?`?(\w+)`?/i', $statement, $matches);
                $tableName = $matches[1] ?? 'unknown';
                output("✓ Created table: $tableName");
            } elseif (stripos($statement, 'INSERT') !== false) {
                preg_match('/INSERT.*?INTO\s+`?(\w+)`?/i', $statement, $matches);
                $tableName = $matches[1] ?? 'unknown';
                output("✓ Inserted data into: $tableName");
            } else {
                output("✓ Executed statement " . ($index + 1));
            }
            
        } catch (Exception $e) {
            // Check if it's a "table already exists" error, which is okay
            if (stripos($e->getMessage(), 'already exists') !== false) {
                preg_match('/table\s+`?(\w+)`?/i', $e->getMessage(), $matches);
                $tableName = $matches[1] ?? 'unknown';
                output("- Table already exists: $tableName");
            } elseif (stripos($e->getMessage(), 'Duplicate entry') !== false) {
                output("- Duplicate entry skipped (this is normal)");
            } else {
                throw $e;
            }
        }
    }
    
    output("Database setup completed successfully!");
    
    // Verify tables exist
    $tables = ['admin_users', 'activity_logs', 'settings'];
    output("\nVerifying tables...");
    
    foreach ($tables as $table) {
        $result = $db->getConnection()->query("SHOW TABLES LIKE '$table'");
        if ($result && $result->num_rows > 0) {
            output("✓ Table '$table' exists");
        } else {
            output("✗ Table '$table' missing", true);
        }
    }
    
    // Check if admin user exists
    $adminUser = dbGetRow("SELECT username, email FROM admin_users WHERE role = 'super_admin' LIMIT 1");
    if ($adminUser) {
        output("\nDefault admin user created:");
        output("Username: " . $adminUser['username']);
        output("Email: " . $adminUser['email']);
        output("Password: admin123 (please change this!)");
    }
    
} catch (Exception $e) {
    output("Setup failed: " . $e->getMessage(), true);
    
    if (!$isCLI) {
        echo "<p style='color: red;'>Please check your database configuration and try again.</p>";
    }
}

if (!$isCLI) {
    echo "<hr>";
    echo "<p><a href='login.php'>Go to Login Page</a></p>";
    echo "</body></html>";
}
?>
