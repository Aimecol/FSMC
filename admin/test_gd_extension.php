<?php
/**
 * GD Extension Test for FSMC Admin System
 */

require_once 'config/config.php';

echo "<h2>🖼️ GD Extension Status Check</h2>";

// Test 1: Check if GD extension is loaded
echo "<h3>1. GD Extension Status</h3>";
if (extension_loaded('gd')) {
    echo "<p style='color: green;'>✅ GD extension is loaded and available</p>";
    
    // Get GD info
    $gdInfo = gd_info();
    echo "<h4>GD Extension Details:</h4>";
    echo "<ul>";
    foreach ($gdInfo as $key => $value) {
        $status = $value ? '✅' : '❌';
        echo "<li>$status <strong>$key:</strong> " . ($value === true ? 'Yes' : ($value === false ? 'No' : $value)) . "</li>";
    }
    echo "</ul>";
    
} else {
    echo "<p style='color: red;'>❌ GD extension is NOT loaded</p>";
    echo "<div style='background: #fff3cd; border: 1px solid #ffeaa7; padding: 15px; border-radius: 5px; margin: 20px 0;'>";
    echo "<h4>🔧 How to Enable GD Extension in XAMPP:</h4>";
    echo "<ol>";
    echo "<li>Open <code>C:\\xampp\\php\\php.ini</code> in a text editor</li>";
    echo "<li>Find the line <code>;extension=gd</code> (with semicolon)</li>";
    echo "<li>Remove the semicolon to uncomment: <code>extension=gd</code></li>";
    echo "<li>Save the file</li>";
    echo "<li>Restart Apache server in XAMPP Control Panel</li>";
    echo "<li>Refresh this page to verify</li>";
    echo "</ol>";
    echo "</div>";
}

// Test 2: Check specific image functions
echo "<h3>2. Image Processing Functions</h3>";
$imageFunctions = [
    'imagecreatefromjpeg' => 'JPEG support',
    'imagecreatefrompng' => 'PNG support', 
    'imagecreatefromgif' => 'GIF support',
    'imagecreatefromwebp' => 'WebP support',
    'imagecreatetruecolor' => 'True color support',
    'imagecopyresampled' => 'Resampling support',
    'imagejpeg' => 'JPEG output',
    'imagepng' => 'PNG output',
    'imagewebp' => 'WebP output'
];

foreach ($imageFunctions as $func => $description) {
    if (function_exists($func)) {
        echo "<p style='color: green;'>✅ $description ($func)</p>";
    } else {
        echo "<p style='color: red;'>❌ $description ($func) - Not available</p>";
    }
}

// Test 3: Test image upload with current settings
echo "<h3>3. Image Upload Test</h3>";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['test_image'])) {
    require_once 'includes/image_upload.php';
    
    $result = handleImageUpload('test_image', 'images/test');
    
    if ($result['success']) {
        echo "<p style='color: green;'>✅ Image uploaded successfully!</p>";
        echo "<p><strong>File ID:</strong> {$result['file_id']}</p>";
        echo "<p><strong>Filename:</strong> {$result['filename']}</p>";
        echo "<p><strong>File Path:</strong> {$result['file_path']}</p>";
        echo "<p><strong>URL:</strong> <a href='{$result['url']}' target='_blank'>{$result['url']}</a></p>";
        echo "<p><strong>Size:</strong> " . formatFileSize($result['size']) . "</p>";
        
        if (isset($result['message'])) {
            echo "<p style='color: orange;'>⚠️ <strong>Note:</strong> {$result['message']}</p>";
        }
        
        echo "<div style='margin: 20px 0;'>";
        echo "<img src='{$result['url']}' alt='Uploaded image' style='max-width: 300px; border: 1px solid #ddd; border-radius: 4px;'>";
        echo "</div>";
    } else {
        echo "<p style='color: red;'>❌ Image upload failed: {$result['error']}</p>";
    }
}

echo "<form method='POST' enctype='multipart/form-data' style='margin: 20px 0; padding: 20px; border: 1px solid #ddd; border-radius: 4px;'>";
echo "<h4>Test Image Upload</h4>";
echo "<p>Upload an image to test the current functionality:</p>";
echo "<input type='file' name='test_image' accept='image/*' required style='margin: 10px 0;'>";
echo "<br><button type='submit' style='padding: 8px 16px; background: #007bff; color: white; border: none; border-radius: 4px;'>Upload Test Image</button>";
echo "</form>";

// Test 4: PHP Configuration
echo "<h3>4. PHP Configuration</h3>";
echo "<p><strong>PHP Version:</strong> " . phpversion() . "</p>";
echo "<p><strong>Upload Max Filesize:</strong> " . ini_get('upload_max_filesize') . "</p>";
echo "<p><strong>Post Max Size:</strong> " . ini_get('post_max_size') . "</p>";
echo "<p><strong>Max Execution Time:</strong> " . ini_get('max_execution_time') . " seconds</p>";
echo "<p><strong>Memory Limit:</strong> " . ini_get('memory_limit') . "</p>";

// Test 5: Upload directories
echo "<h3>5. Upload Directories</h3>";
$uploadDirs = [
    UPLOAD_PATH,
    UPLOAD_PATH . '/images',
    UPLOAD_PATH . '/images/test',
    UPLOAD_PATH . '/images/products',
    UPLOAD_PATH . '/images/services',
    UPLOAD_PATH . '/images/training',
    UPLOAD_PATH . '/images/research'
];

foreach ($uploadDirs as $dir) {
    if (!is_dir($dir)) {
        if (mkdir($dir, 0755, true)) {
            echo "<p style='color: green;'>✅ Created directory: $dir</p>";
        } else {
            echo "<p style='color: red;'>❌ Failed to create directory: $dir</p>";
        }
    } else {
        $writable = is_writable($dir) ? 'writable' : 'not writable';
        $color = is_writable($dir) ? 'green' : 'orange';
        echo "<p style='color: $color;'>✅ Directory exists and is $writable: $dir</p>";
    }
}

echo "<h3>🎯 Summary</h3>";
if (extension_loaded('gd')) {
    echo "<p style='color: green; font-weight: bold;'>✅ GD extension is available - Full image processing enabled</p>";
    echo "<p>Images will be automatically resized and optimized during upload.</p>";
} else {
    echo "<p style='color: orange; font-weight: bold;'>⚠️ GD extension is not available - Basic upload only</p>";
    echo "<p>Images will be uploaded without processing. Enable GD extension for automatic resizing.</p>";
}

echo "<p><a href='test_image_upload.php' style='color: #007bff;'>🔗 Go to Full Image Upload Test</a></p>";

echo "<style>
code { background: #f8f9fa; padding: 2px 4px; border-radius: 3px; font-family: monospace; }
ul { margin: 10px 0; }
li { margin: 5px 0; }
ol li { margin: 8px 0; }
</style>";
?>
