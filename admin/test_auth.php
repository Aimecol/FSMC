<?php
/**
 * Test Authentication
 */

require_once 'config/config.php';

echo "<h2>Authentication Test</h2>";

echo "<p>Session Status: " . session_status() . "</p>";
echo "<p>Session ID: " . session_id() . "</p>";

if (isset($_SESSION)) {
    echo "<h3>Session Data:</h3>";
    echo "<pre>" . print_r($_SESSION, true) . "</pre>";
} else {
    echo "<p>No session data</p>";
}

echo "<h3>Authentication Functions:</h3>";

try {
    $isLoggedIn = isLoggedIn();
    echo "<p>isLoggedIn(): " . ($isLoggedIn ? 'Yes' : 'No') . "</p>";
} catch (Exception $e) {
    echo "<p style='color: red;'>isLoggedIn() error: " . $e->getMessage() . "</p>";
}

try {
    $isValidSession = isValidSession();
    echo "<p>isValidSession(): " . ($isValidSession ? 'Yes' : 'No') . "</p>";
} catch (Exception $e) {
    echo "<p style='color: red;'>isValidSession() error: " . $e->getMessage() . "</p>";
}

try {
    $currentUser = getCurrentUser();
    if ($currentUser) {
        echo "<h3>Current User:</h3>";
        echo "<pre>" . print_r($currentUser, true) . "</pre>";
    } else {
        echo "<p>No current user</p>";
    }
} catch (Exception $e) {
    echo "<p style='color: red;'>getCurrentUser() error: " . $e->getMessage() . "</p>";
}

echo "<h3>Quick Login Test:</h3>";
echo "<p><a href='login.php'>Go to Login Page</a></p>";
echo "<p><a href='index.php'>Go to Dashboard</a></p>";
?>
