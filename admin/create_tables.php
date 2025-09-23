<?php
/**
 * Create Missing Database Tables
 */

require_once 'config/config.php';

echo "<h2>Creating Missing Database Tables</h2>";

try {
    $db = getDB();
    
    // Create inquiries table if it doesn't exist
    $createInquiries = "
    CREATE TABLE IF NOT EXISTS inquiries (
        id INT PRIMARY KEY AUTO_INCREMENT,
        type ENUM('general', 'service', 'product', 'training', 'research', 'support') DEFAULT 'general',
        name VARCHAR(100) NOT NULL,
        email VARCHAR(100) NOT NULL,
        phone VARCHAR(20),
        subject VARCHAR(200) NOT NULL,
        message TEXT NOT NULL,
        status ENUM('new', 'read', 'responded', 'closed') DEFAULT 'new',
        priority ENUM('low', 'normal', 'high', 'urgent') DEFAULT 'normal',
        assigned_to INT,
        response TEXT,
        ip_address VARCHAR(45),
        user_agent TEXT,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        FOREIGN KEY (assigned_to) REFERENCES admin_users(id) ON DELETE SET NULL
    )";
    
    if (dbExecute($createInquiries)) {
        echo "<p style='color: green;'>✓ Inquiries table created/verified</p>";
    } else {
        echo "<p style='color: red;'>✗ Failed to create inquiries table</p>";
    }
    
    // Create settings table if it doesn't exist
    $createSettings = "
    CREATE TABLE IF NOT EXISTS settings (
        id INT PRIMARY KEY AUTO_INCREMENT,
        setting_key VARCHAR(100) UNIQUE NOT NULL,
        setting_value TEXT,
        setting_type ENUM('text', 'textarea', 'number', 'boolean', 'json', 'file') DEFAULT 'text',
        category VARCHAR(50) DEFAULT 'general',
        description TEXT,
        is_public BOOLEAN DEFAULT FALSE,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    )";
    
    if (dbExecute($createSettings)) {
        echo "<p style='color: green;'>✓ Settings table created/verified</p>";
    } else {
        echo "<p style='color: red;'>✗ Failed to create settings table</p>";
    }
    
    // Create file_uploads table if it doesn't exist
    $createFileUploads = "
    CREATE TABLE IF NOT EXISTS file_uploads (
        id INT PRIMARY KEY AUTO_INCREMENT,
        filename VARCHAR(255) NOT NULL,
        original_name VARCHAR(255) NOT NULL,
        file_path VARCHAR(500) NOT NULL,
        file_size INT NOT NULL,
        file_type VARCHAR(100),
        mime_type VARCHAR(100),
        uploaded_by INT,
        description TEXT,
        alt_text VARCHAR(255),
        is_public BOOLEAN DEFAULT TRUE,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        FOREIGN KEY (uploaded_by) REFERENCES admin_users(id) ON DELETE SET NULL
    )";
    
    if (dbExecute($createFileUploads)) {
        echo "<p style='color: green;'>✓ File uploads table created/verified</p>";
    } else {
        echo "<p style='color: red;'>✗ Failed to create file_uploads table</p>";
    }
    
    // Create activity_logs table if it doesn't exist
    $createActivityLogs = "
    CREATE TABLE IF NOT EXISTS activity_logs (
        id INT PRIMARY KEY AUTO_INCREMENT,
        user_id INT,
        action VARCHAR(100) NOT NULL,
        table_name VARCHAR(50),
        record_id INT,
        old_data JSON,
        new_data JSON,
        description TEXT,
        ip_address VARCHAR(45),
        user_agent TEXT,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (user_id) REFERENCES admin_users(id) ON DELETE SET NULL
    )";
    
    if (dbExecute($createActivityLogs)) {
        echo "<p style='color: green;'>✓ Activity logs table created/verified</p>";
    } else {
        echo "<p style='color: red;'>✗ Failed to create activity_logs table</p>";
    }
    
    // Insert some sample data
    echo "<h3>Inserting Sample Data</h3>";
    
    // Sample inquiry
    $sampleInquiry = "
    INSERT IGNORE INTO inquiries (name, email, subject, message, type, status) 
    VALUES ('John Doe', 'john@example.com', 'General Inquiry', 'This is a test inquiry message.', 'general', 'new')";
    
    if (dbExecute($sampleInquiry)) {
        echo "<p style='color: green;'>✓ Sample inquiry added</p>";
    }
    
    // Sample settings
    $sampleSettings = [
        ['company_name', 'Fair Surveying & Mapping Company'],
        ['company_email', 'fsamcompanyltd@gmail.com'],
        ['company_phone', '+250 788 331 697'],
        ['site_title', 'FSMC Admin System']
    ];
    
    foreach ($sampleSettings as $setting) {
        $sql = "INSERT IGNORE INTO settings (setting_key, setting_value) VALUES (?, ?)";
        if (dbExecute($sql, $setting)) {
            echo "<p style='color: green;'>✓ Setting '{$setting[0]}' added</p>";
        }
    }
    
    echo "<h3>Database Setup Complete!</h3>";
    echo "<p><a href='test_db.php'>Run Database Test</a></p>";
    echo "<p><a href='test_pages.php'>Test Admin Pages</a></p>";
    echo "<p><a href='index.php'>Go to Admin Dashboard</a></p>";

    // Test the problematic pages
    echo "<h3>Test Problematic Pages</h3>";
    echo "<ul>";
    echo "<li><a href='inquiries.php' target='_blank'>Test Inquiries Page</a></li>";
    echo "<li><a href='media.php' target='_blank'>Test Media Page</a></li>";
    echo "<li><a href='logs.php' target='_blank'>Test Logs Page</a></li>";
    echo "</ul>";
    
} catch (Exception $e) {
    echo "<p style='color: red;'>✗ Error: " . $e->getMessage() . "</p>";
}
?>
