<?php
session_start();

// Include database configuration and functions
require_once '../config/database.php';
require_once '../includes/functions.php';

// Redirect if already logged in
if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true) {
    header('Location: index.php');
    exit;
}

$error_message = '';
$login_attempts = 0;
$max_attempts = 5;
$lockout_time = 900; // 15 minutes

// Handle login form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    // Check for brute force protection
    $ip_address = $_SERVER['REMOTE_ADDR'];
    $current_time = time();

    // Get login attempts from session or initialize
    if (!isset($_SESSION['login_attempts'])) {
        $_SESSION['login_attempts'] = [];
    }

    // Clean old attempts (older than lockout time)
    $_SESSION['login_attempts'] = array_filter($_SESSION['login_attempts'], function($attempt) use ($current_time, $lockout_time) {
        return ($current_time - $attempt) < $lockout_time;
    });

    $login_attempts = count($_SESSION['login_attempts']);

    // Check if locked out
    if ($login_attempts >= $max_attempts) {
        $error_message = "Too many failed login attempts. Please try again in 15 minutes.";
    } else {
        // Validate input
        if (empty($email) || empty($password)) {
            $error_message = "Please enter both email and password.";
        } else {
            // Check user credentials
            $user = authenticateUser($email, $password);

            if ($user) {
                // Check if user is active and has admin role
                if ($user['status'] !== 'Active') {
                    $error_message = "Your account has been deactivated. Please contact the administrator.";
                } elseif ($user['role'] !== 'Admin' && $user['role'] !== 'Super Admin') {
                    $error_message = "Access denied. Admin privileges required.";
                } else {
                    // Successful login
                    $_SESSION['admin_logged_in'] = true;
                    $_SESSION['admin_user_id'] = $user['id'];
                    $_SESSION['admin_user_name'] = $user['name'];
                    $_SESSION['admin_user_email'] = $user['email'];
                    $_SESSION['admin_user_role'] = $user['role'];
                    $_SESSION['login_time'] = time();

                    // Clear login attempts
                    unset($_SESSION['login_attempts']);

                    // Log successful login
                    logActivity($user['id'], 'login', 'Admin user logged in');

                    // Redirect to dashboard
                    header('Location: index.php');
                    exit;
                }
            } else {
                $error_message = "Invalid email or password.";
                // Record failed attempt
                $_SESSION['login_attempts'][] = $current_time;
            }
        }
    }
}

/**
 * Authenticate user with email and password
 */
function authenticateUser($email, $password) {
    $mysqli = getDatabaseConnection();
    if (!$mysqli) return false;

    $email = $mysqli->real_escape_string($email);
    $query = "SELECT * FROM users WHERE email = '$email' LIMIT 1";
    $result = $mysqli->query($query);

    if ($result && $result->num_rows === 1) {
        $user = $result->fetch_assoc();

        // Verify password
        if (password_verify($password, $user['password'])) {
            return $user;
        }
    }

    return false;
}

/**
 * Log user activity
 */
function logActivity($user_id, $action, $description) {
    $mysqli = getDatabaseConnection();
    if (!$mysqli) return false;

    $user_id = intval($user_id);
    $action = $mysqli->real_escape_string($action);
    $description = $mysqli->real_escape_string($description);
    $ip_address = $_SERVER['REMOTE_ADDR'];

    $query = "INSERT INTO user_activity_logs (user_id, action, description, ip_address)
              VALUES ($user_id, '$action', '$description', '$ip_address')";

    return $mysqli->query($query);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login | Fair Surveying & Mapping Ltd</title>
    <link rel="icon" type="image/svg+xml" href="../images/logo.png">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Admin CSS -->
    <link rel="stylesheet" href="css/admin.css">
    <style>
        .login-container {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 20px;
        }

        .login-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
            padding: 40px;
            width: 100%;
            max-width: 400px;
            text-align: center;
        }

        .login-logo {
            width: 80px;
            height: 80px;
            margin: 0 auto 20px;
            background: #3498db;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 2rem;
        }

        .login-title {
            color: #2c3e50;
            margin-bottom: 10px;
            font-size: 1.8rem;
            font-weight: 600;
        }

        .login-subtitle {
            color: #7f8c8d;
            margin-bottom: 30px;
            font-size: 0.9rem;
        }

        .form-group {
            margin-bottom: 20px;
            text-align: left;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
            color: #2c3e50;
            font-weight: 500;
        }

        .form-control {
            width: 100%;
            padding: 12px 15px;
            border: 2px solid #ecf0f1;
            border-radius: 8px;
            font-size: 1rem;
            transition: border-color 0.3s ease;
        }

        .form-control:focus {
            outline: none;
            border-color: #3498db;
        }

        .btn-login {
            width: 100%;
            padding: 12px;
            background: #3498db;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .btn-login:hover {
            background: #2980b9;
        }

        .btn-login:disabled {
            background: #bdc3c7;
            cursor: not-allowed;
        }

        .error-message {
            background: #e74c3c;
            color: white;
            padding: 12px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 0.9rem;
        }

        .login-footer {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #ecf0f1;
            color: #7f8c8d;
            font-size: 0.8rem;
        }

        .attempts-warning {
            background: #f39c12;
            color: white;
            padding: 8px;
            border-radius: 5px;
            margin-bottom: 15px;
            font-size: 0.85rem;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-card">
            <div class="login-logo">
                <i class="fas fa-map-marked-alt"></i>
            </div>

            <h1 class="login-title">Admin Login</h1>
            <p class="login-subtitle">Fair Surveying & Mapping Ltd</p>

            <?php if (!empty($error_message)): ?>
                <div class="error-message">
                    <i class="fas fa-exclamation-triangle"></i>
                    <?php echo htmlspecialchars($error_message); ?>
                </div>
            <?php endif; ?>

            <?php if ($login_attempts > 0 && $login_attempts < $max_attempts): ?>
                <div class="attempts-warning">
                    <i class="fas fa-warning"></i>
                    Warning: <?php echo $login_attempts; ?> of <?php echo $max_attempts; ?> login attempts used.
                </div>
            <?php endif; ?>

            <form method="POST" action="">
                <div class="form-group">
                    <label for="email">Email Address</label>
                    <input type="email" id="email" name="email" class="form-control"
                           value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>"
                           required <?php echo ($login_attempts >= $max_attempts) ? 'disabled' : ''; ?>>
                </div>

                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" class="form-control"
                           required <?php echo ($login_attempts >= $max_attempts) ? 'disabled' : ''; ?>>
                </div>

                <button type="submit" class="btn-login"
                        <?php echo ($login_attempts >= $max_attempts) ? 'disabled' : ''; ?>>
                    <i class="fas fa-sign-in-alt"></i>
                    Sign In
                </button>
            </form>

            <div class="login-footer">
                <p>&copy; <?php echo date('Y'); ?> Fair Surveying & Mapping Ltd. All rights reserved.</p>
            </div>
        </div>
    </div>

    <script>
        // Auto-focus on email field
        document.addEventListener('DOMContentLoaded', function() {
            const emailField = document.getElementById('email');
            if (emailField && !emailField.disabled) {
                emailField.focus();
            }
        });

        // Form validation
        document.querySelector('form').addEventListener('submit', function(e) {
            const email = document.getElementById('email').value.trim();
            const password = document.getElementById('password').value;

            if (!email || !password) {
                e.preventDefault();
                alert('Please enter both email and password.');
                return false;
            }

            // Basic email validation
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(email)) {
                e.preventDefault();
                alert('Please enter a valid email address.');
                return false;
            }
        });
    </script>
</body>
</html>