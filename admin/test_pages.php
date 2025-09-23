<?php
/**
 * Test Admin Pages
 */

require_once 'config/config.php';

echo "<h2>Admin Pages Test</h2>";

$pages = [
    'index.php' => 'Dashboard',
    'inquiries.php' => 'Inquiries Management',
    'media.php' => 'Media Management',
    'logs.php' => 'Activity Logs',
    'settings.php' => 'Settings',
    'users.php' => 'Users Management'
];

echo "<ul>";
foreach ($pages as $page => $title) {
    echo "<li><a href='$page' target='_blank'>$title</a></li>";
}
echo "</ul>";

echo "<h3>JavaScript Test</h3>";
echo "<button onclick='testModal()' class='btn btn-primary'>Test Modal</button>";
echo "<button onclick='testAlert()' class='btn btn-success'>Test Alert</button>";

echo "<div id='testModal' class='modal'>
    <div class='modal-dialog'>
        <div class='modal-content'>
            <div class='modal-header'>
                <h5 class='modal-title'>Test Modal</h5>
                <button type='button' class='modal-close' data-dismiss='modal'>
                    <i class='fas fa-times'></i>
                </button>
            </div>
            <div class='modal-body'>
                <p>This is a test modal to verify JavaScript functionality.</p>
            </div>
            <div class='modal-footer'>
                <button type='button' class='btn btn-secondary' data-dismiss='modal'>Close</button>
            </div>
        </div>
    </div>
</div>";

echo "<script>
function testModal() {
    document.getElementById('testModal').classList.add('show');
}

function testAlert() {
    alert('JavaScript is working!');
}
</script>";

echo "<style>
.btn {
    display: inline-block;
    padding: 8px 16px;
    margin: 4px;
    background: #007bff;
    color: white;
    text-decoration: none;
    border: none;
    border-radius: 4px;
    cursor: pointer;
}
.btn-success { background: #28a745; }
.btn-secondary { background: #6c757d; }
</style>";
?>
