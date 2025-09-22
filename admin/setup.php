<?php
/**
 * Setup Script for FSMC Admin System
 * Created: 2025-01-22
 * Description: Initialize database and create admin user
 */

// Check if already installed
if (file_exists('config/.installed')) {
    die('System is already installed. Delete config/.installed file to reinstall.');
}

$step = intval($_GET['step'] ?? 1);
$error = '';
$success = '';

// Step 1: Database Connection Test
if ($step === 1) {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $host = $_POST['host'] ?? 'localhost';
        $username = $_POST['username'] ?? 'root';
        $password = $_POST['password'] ?? '';
        $database = $_POST['database'] ?? 'fsmc_db';
        
        try {
            $mysqli = new mysqli($host, $username, $password);
            
            if ($mysqli->connect_error) {
                throw new Exception("Connection failed: " . $mysqli->connect_error);
            }
            
            // Try to create database
            $mysqli->query("CREATE DATABASE IF NOT EXISTS `$database` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
            $mysqli->select_db($database);
            
            // Save database config
            $configContent = "<?php\n";
            $configContent .= "define('DB_HOST', '" . addslashes($host) . "');\n";
            $configContent .= "define('DB_USERNAME', '" . addslashes($username) . "');\n";
            $configContent .= "define('DB_PASSWORD', '" . addslashes($password) . "');\n";
            $configContent .= "define('DB_NAME', '" . addslashes($database) . "');\n";
            $configContent .= "?>";
            
            if (!is_dir('config')) {
                mkdir('config', 0755, true);
            }
            
            file_put_contents('config/db_config.php', $configContent);
            
            $mysqli->close();
            header('Location: setup.php?step=2');
            exit;
            
        } catch (Exception $e) {
            $error = $e->getMessage();
        }
    }
}

// Step 2: Create Tables
if ($step === 2) {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        try {
            require_once 'config/db_config.php';
            
            $mysqli = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);
            
            if ($mysqli->connect_error) {
                throw new Exception("Connection failed: " . $mysqli->connect_error);
            }
            
            // Read and execute schema
            $schema = file_get_contents('database/schema.sql');
            $statements = explode(';', $schema);
            
            foreach ($statements as $statement) {
                $statement = trim($statement);
                if (!empty($statement)) {
                    if (!$mysqli->query($statement)) {
                        throw new Exception("Error executing statement: " . $mysqli->error);
                    }
                }
            }
            
            $mysqli->close();
            header('Location: setup.php?step=3');
            exit;
            
        } catch (Exception $e) {
            $error = $e->getMessage();
        }
    }
}

// Step 3: Create Admin User
if ($step === 3) {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        try {
            require_once 'config/db_config.php';
            
            $mysqli = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);
            
            if ($mysqli->connect_error) {
                throw new Exception("Connection failed: " . $mysqli->connect_error);
            }
            
            $username = $_POST['username'] ?? '';
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';
            $fullName = $_POST['full_name'] ?? '';
            
            if (empty($username) || empty($email) || empty($password) || empty($fullName)) {
                throw new Exception("All fields are required.");
            }
            
            if (strlen($password) < 6) {
                throw new Exception("Password must be at least 6 characters long.");
            }
            
            $passwordHash = password_hash($password, PASSWORD_DEFAULT);
            
            $stmt = $mysqli->prepare("INSERT INTO admin_users (username, email, password_hash, full_name, role, status) VALUES (?, ?, ?, ?, 'super_admin', 'active')");
            $stmt->bind_param("ssss", $username, $email, $passwordHash, $fullName);
            
            if (!$stmt->execute()) {
                throw new Exception("Failed to create admin user: " . $stmt->error);
            }
            
            $stmt->close();
            $mysqli->close();
            
            header('Location: setup.php?step=4');
            exit;
            
        } catch (Exception $e) {
            $error = $e->getMessage();
        }
    }
}

// Step 4: Insert Initial Data
if ($step === 4) {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        try {
            require_once 'config/db_config.php';
            
            $mysqli = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);
            
            if ($mysqli->connect_error) {
                throw new Exception("Connection failed: " . $mysqli->connect_error);
            }
            
            // Read and execute initial data
            $initialData = file_get_contents('database/initial_data.sql');
            $statements = explode(';', $initialData);
            
            foreach ($statements as $statement) {
                $statement = trim($statement);
                if (!empty($statement) && !strpos($statement, 'INSERT INTO admin_users')) {
                    if (!$mysqli->query($statement)) {
                        // Log error but continue
                        error_log("Warning: " . $mysqli->error);
                    }
                }
            }
            
            $mysqli->close();
            
            // Create installation marker
            file_put_contents('config/.installed', date('Y-m-d H:i:s'));
            
            header('Location: setup.php?step=5');
            exit;
            
        } catch (Exception $e) {
            $error = $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FSMC Admin Setup</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <style>
        :root {
            --primary-color: #1a5276;
            --secondary-color: #2e86c1;
            --light-color: #f4f6f7;
            --white: #ffffff;
            --text-dark: #2c3e50;
            --border-color: #ddd;
            --error-color: #e74c3c;
            --success-color: #27ae60;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        
        .setup-container {
            background: var(--white);
            border-radius: 12px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
            width: 100%;
            max-width: 600px;
            overflow: hidden;
        }
        
        .setup-header {
            background: var(--primary-color);
            color: var(--white);
            padding: 30px;
            text-align: center;
        }
        
        .setup-header h1 {
            font-size: 28px;
            margin-bottom: 10px;
        }
        
        .setup-content {
            padding: 30px;
        }
        
        .step-indicator {
            display: flex;
            justify-content: center;
            margin-bottom: 30px;
        }
        
        .step {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: var(--light-color);
            color: var(--text-dark);
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 10px;
            font-weight: bold;
        }
        
        .step.active {
            background: var(--primary-color);
            color: var(--white);
        }
        
        .step.completed {
            background: var(--success-color);
            color: var(--white);
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        .form-label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: var(--text-dark);
        }
        
        .form-control {
            width: 100%;
            padding: 12px 16px;
            border: 2px solid var(--border-color);
            border-radius: 8px;
            font-size: 14px;
            transition: border-color 0.3s ease;
        }
        
        .form-control:focus {
            outline: none;
            border-color: var(--primary-color);
        }
        
        .btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 12px 24px;
            background: var(--primary-color);
            color: var(--white);
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 500;
            cursor: pointer;
            transition: background-color 0.3s ease;
            text-decoration: none;
        }
        
        .btn:hover {
            background: var(--secondary-color);
        }
        
        .btn-success {
            background: var(--success-color);
        }
        
        .alert {
            padding: 12px 16px;
            border-radius: 8px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .alert-error {
            background: rgba(231, 76, 60, 0.1);
            color: var(--error-color);
            border: 1px solid rgba(231, 76, 60, 0.2);
        }
        
        .alert-success {
            background: rgba(39, 174, 96, 0.1);
            color: var(--success-color);
            border: 1px solid rgba(39, 174, 96, 0.2);
        }
        
        .text-center {
            text-align: center;
        }
        
        .mb-3 {
            margin-bottom: 24px;
        }
    </style>
</head>
<body>
    <div class="setup-container">
        <div class="setup-header">
            <h1><i class="fas fa-cog"></i> FSMC Admin Setup</h1>
            <p>Fair Surveying & Mapping Company</p>
        </div>
        
        <div class="setup-content">
            <!-- Step Indicator -->
            <div class="step-indicator">
                <div class="step <?php echo $step >= 1 ? ($step > 1 ? 'completed' : 'active') : ''; ?>">1</div>
                <div class="step <?php echo $step >= 2 ? ($step > 2 ? 'completed' : 'active') : ''; ?>">2</div>
                <div class="step <?php echo $step >= 3 ? ($step > 3 ? 'completed' : 'active') : ''; ?>">3</div>
                <div class="step <?php echo $step >= 4 ? ($step > 4 ? 'completed' : 'active') : ''; ?>">4</div>
                <div class="step <?php echo $step >= 5 ? 'active' : ''; ?>">5</div>
            </div>
            
            <?php if ($error): ?>
                <div class="alert alert-error">
                    <i class="fas fa-exclamation-triangle"></i>
                    <?php echo htmlspecialchars($error); ?>
                </div>
            <?php endif; ?>
            
            <?php if ($success): ?>
                <div class="alert alert-success">
                    <i class="fas fa-check-circle"></i>
                    <?php echo htmlspecialchars($success); ?>
                </div>
            <?php endif; ?>
            
            <?php if ($step === 1): ?>
                <h2 class="mb-3">Step 1: Database Configuration</h2>
                <form method="POST">
                    <div class="form-group">
                        <label for="host" class="form-label">Database Host</label>
                        <input type="text" id="host" name="host" class="form-control" 
                               value="localhost" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="username" class="form-label">Database Username</label>
                        <input type="text" id="username" name="username" class="form-control" 
                               value="root" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="password" class="form-label">Database Password</label>
                        <input type="password" id="password" name="password" class="form-control">
                    </div>
                    
                    <div class="form-group">
                        <label for="database" class="form-label">Database Name</label>
                        <input type="text" id="database" name="database" class="form-control" 
                               value="fsmc_db" required>
                    </div>
                    
                    <button type="submit" class="btn">
                        <i class="fas fa-arrow-right"></i> Test Connection & Continue
                    </button>
                </form>
                
            <?php elseif ($step === 2): ?>
                <h2 class="mb-3">Step 2: Create Database Tables</h2>
                <p class="mb-3">Click the button below to create the required database tables.</p>
                
                <form method="POST">
                    <button type="submit" class="btn">
                        <i class="fas fa-database"></i> Create Tables
                    </button>
                </form>
                
            <?php elseif ($step === 3): ?>
                <h2 class="mb-3">Step 3: Create Admin User</h2>
                <form method="POST">
                    <div class="form-group">
                        <label for="username" class="form-label">Username</label>
                        <input type="text" id="username" name="username" class="form-control" 
                               required minlength="3">
                    </div>
                    
                    <div class="form-group">
                        <label for="email" class="form-label">Email Address</label>
                        <input type="email" id="email" name="email" class="form-control" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="full_name" class="form-label">Full Name</label>
                        <input type="text" id="full_name" name="full_name" class="form-control" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" id="password" name="password" class="form-control" 
                               required minlength="6">
                    </div>
                    
                    <button type="submit" class="btn">
                        <i class="fas fa-user-plus"></i> Create Admin User
                    </button>
                </form>
                
            <?php elseif ($step === 4): ?>
                <h2 class="mb-3">Step 4: Insert Initial Data</h2>
                <p class="mb-3">Click the button below to insert sample data and company settings.</p>
                
                <form method="POST">
                    <button type="submit" class="btn">
                        <i class="fas fa-download"></i> Insert Initial Data
                    </button>
                </form>
                
            <?php elseif ($step === 5): ?>
                <div class="text-center">
                    <h2 class="mb-3"><i class="fas fa-check-circle text-success"></i> Setup Complete!</h2>
                    <p class="mb-3">Your FSMC Admin System has been successfully installed.</p>
                    
                    <a href="login.php" class="btn btn-success">
                        <i class="fas fa-sign-in-alt"></i> Go to Admin Login
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
