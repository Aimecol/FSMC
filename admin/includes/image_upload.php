<?php
/**
 * Image Upload Handler for FSMC Admin System
 */

/**
 * Handle image upload with validation and processing
 */
function handleImageUpload($fileInput, $uploadDir = 'images', $maxSize = 5242880, $allowedTypes = ['jpg', 'jpeg', 'png', 'gif', 'webp']) {
    if (!isset($_FILES[$fileInput]) || $_FILES[$fileInput]['error'] === UPLOAD_ERR_NO_FILE) {
        return ['success' => false, 'error' => 'No file uploaded'];
    }
    
    $file = $_FILES[$fileInput];
    
    // Check for upload errors
    if ($file['error'] !== UPLOAD_ERR_OK) {
        return ['success' => false, 'error' => 'Upload error: ' . getUploadErrorMessage($file['error'])];
    }
    
    // Validate file size
    if ($file['size'] > $maxSize) {
        return ['success' => false, 'error' => 'File too large. Maximum size: ' . formatFileSize($maxSize)];
    }
    
    // Get file extension
    $fileExtension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    
    // Validate file type
    if (!in_array($fileExtension, $allowedTypes)) {
        return ['success' => false, 'error' => 'Invalid file type. Allowed: ' . implode(', ', $allowedTypes)];
    }
    
    // Validate MIME type
    $allowedMimes = [
        'jpg' => 'image/jpeg',
        'jpeg' => 'image/jpeg', 
        'png' => 'image/png',
        'gif' => 'image/gif',
        'webp' => 'image/webp'
    ];
    
    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mimeType = finfo_file($finfo, $file['tmp_name']);
    finfo_close($finfo);
    
    if (!in_array($mimeType, $allowedMimes) || $allowedMimes[$fileExtension] !== $mimeType) {
        return ['success' => false, 'error' => 'Invalid file type or corrupted file'];
    }
    
    // Create upload directory if it doesn't exist
    $uploadPath = UPLOAD_PATH . '/' . $uploadDir;
    if (!is_dir($uploadPath)) {
        if (!mkdir($uploadPath, 0755, true)) {
            return ['success' => false, 'error' => 'Failed to create upload directory'];
        }
    }
    
    // Generate unique filename
    $timestamp = time();
    $randomString = bin2hex(random_bytes(8));
    $newFilename = $timestamp . '_' . $randomString . '.' . $fileExtension;
    $filePath = $uploadPath . '/' . $newFilename;
    
    // Move uploaded file
    if (!move_uploaded_file($file['tmp_name'], $filePath)) {
        return ['success' => false, 'error' => 'Failed to move uploaded file'];
    }
    
    // Process image (resize if needed)
    $processResult = processUploadedImage($filePath, $fileExtension);
    if (!$processResult['success']) {
        unlink($filePath); // Clean up on failure
        return $processResult;
    }

    // Store any processing messages for user feedback
    $processingMessage = $processResult['message'] ?? null;
    
    // Store file info in database
    $currentUserId = getCurrentUserId();
    $fileData = [
        'original_name' => $file['name'],
        'file_name' => $newFilename,
        'file_path' => $uploadDir . '/' . $newFilename,
        'file_size' => $file['size'],
        'mime_type' => $mimeType,
        'file_type' => 'image', // ENUM value from schema
        'related_table' => null,
        'related_id' => null,
        'uploaded_by' => $currentUserId,
        'alt_text' => null,
        'caption' => null
    ];

    try {
        $fileId = dbInsert(
            "INSERT INTO file_uploads (original_name, file_name, file_path, file_size, mime_type, file_type, related_table, related_id, uploaded_by, alt_text, caption)
             VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)",
            array_values($fileData)
        );
        
        if ($fileId) {
            logActivity('File Uploaded', 'file_uploads', $fileId, null, $fileData);
            
            $result = [
                'success' => true,
                'file_id' => $fileId,
                'filename' => $newFilename,
                'file_path' => $fileData['file_path'],
                'url' => UPLOAD_URL . '/' . $fileData['file_path'],
                'size' => $file['size']
            ];

            // Add processing message if available
            if ($processingMessage) {
                $result['message'] = $processingMessage;
            }

            return $result;
        } else {
            unlink($filePath); // Clean up on database failure
            return ['success' => false, 'error' => 'Failed to save file information'];
        }
    } catch (Exception $e) {
        unlink($filePath); // Clean up on error
        error_log("File upload database error: " . $e->getMessage());
        return ['success' => false, 'error' => 'Database error occurred'];
    }
}

/**
 * Process uploaded image (resize, optimize)
 */
function processUploadedImage($filePath, $extension, $maxWidth = 1200, $maxHeight = 800, $quality = 85) {
    try {
        // Check if GD extension is available
        if (!extension_loaded('gd')) {
            error_log("GD extension not available - skipping image processing for: $filePath");
            return ['success' => true, 'message' => 'Image uploaded successfully (processing skipped - GD extension not available)'];
        }

        // Get image dimensions
        $imageInfo = getimagesize($filePath);
        if (!$imageInfo) {
            return ['success' => false, 'error' => 'Invalid image file'];
        }
        
        list($width, $height) = $imageInfo;
        
        // Skip processing if image is already small enough
        if ($width <= $maxWidth && $height <= $maxHeight) {
            return ['success' => true];
        }
        
        // Calculate new dimensions
        $ratio = min($maxWidth / $width, $maxHeight / $height);
        $newWidth = round($width * $ratio);
        $newHeight = round($height * $ratio);
        
        // Create image resource based on type
        $source = false;
        switch ($extension) {
            case 'jpg':
            case 'jpeg':
                if (function_exists('imagecreatefromjpeg')) {
                    $source = @imagecreatefromjpeg($filePath);
                }
                break;
            case 'png':
                if (function_exists('imagecreatefrompng')) {
                    $source = @imagecreatefrompng($filePath);
                }
                break;
            case 'gif':
                if (function_exists('imagecreatefromgif')) {
                    $source = @imagecreatefromgif($filePath);
                }
                break;
            case 'webp':
                if (function_exists('imagecreatefromwebp')) {
                    $source = @imagecreatefromwebp($filePath);
                }
                break;
            default:
                return ['success' => true]; // Skip processing for unsupported types
        }
        
        if (!$source) {
            error_log("Failed to create image resource for: $filePath (extension: $extension)");
            return ['success' => true, 'message' => 'Image uploaded successfully (processing failed - unsupported format or corrupted file)'];
        }

        // Create new image
        if (!function_exists('imagecreatetruecolor')) {
            imagedestroy($source);
            return ['success' => true, 'message' => 'Image uploaded successfully (processing skipped - missing GD functions)'];
        }

        $destination = @imagecreatetruecolor($newWidth, $newHeight);
        if (!$destination) {
            imagedestroy($source);
            return ['success' => true, 'message' => 'Image uploaded successfully (processing failed - memory limit)'];
        }
        
        // Preserve transparency for PNG and GIF
        if ($extension === 'png' || $extension === 'gif') {
            imagealphablending($destination, false);
            imagesavealpha($destination, true);
            $transparent = imagecolorallocatealpha($destination, 255, 255, 255, 127);
            imagefill($destination, 0, 0, $transparent);
        }
        
        // Resize image
        imagecopyresampled($destination, $source, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);
        
        // Save resized image
        $result = false;
        switch ($extension) {
            case 'jpg':
            case 'jpeg':
                if (function_exists('imagejpeg')) {
                    $result = @imagejpeg($destination, $filePath, $quality);
                }
                break;
            case 'png':
                if (function_exists('imagepng')) {
                    $result = @imagepng($destination, $filePath, round(9 * (100 - $quality) / 100));
                }
                break;
            case 'gif':
                if (function_exists('imagegif')) {
                    $result = @imagegif($destination, $filePath);
                }
                break;
            case 'webp':
                if (function_exists('imagewebp')) {
                    $result = @imagewebp($destination, $filePath, $quality);
                }
                break;
        }
        
        // Clean up memory
        imagedestroy($source);
        imagedestroy($destination);
        
        return $result ? ['success' => true] : ['success' => false, 'error' => 'Failed to save resized image'];
        
    } catch (Exception $e) {
        error_log("Image processing error: " . $e->getMessage());
        return ['success' => true]; // Continue even if processing fails
    }
}

/**
 * Get upload error message
 */
function getUploadErrorMessage($errorCode) {
    switch ($errorCode) {
        case UPLOAD_ERR_INI_SIZE:
            return 'File exceeds upload_max_filesize directive';
        case UPLOAD_ERR_FORM_SIZE:
            return 'File exceeds MAX_FILE_SIZE directive';
        case UPLOAD_ERR_PARTIAL:
            return 'File was only partially uploaded';
        case UPLOAD_ERR_NO_FILE:
            return 'No file was uploaded';
        case UPLOAD_ERR_NO_TMP_DIR:
            return 'Missing temporary folder';
        case UPLOAD_ERR_CANT_WRITE:
            return 'Failed to write file to disk';
        case UPLOAD_ERR_EXTENSION:
            return 'File upload stopped by extension';
        default:
            return 'Unknown upload error';
    }
}

/**
 * Delete uploaded file and database record
 */
function deleteUploadedFile($fileId) {
    try {
        $file = dbGetRow("SELECT * FROM file_uploads WHERE id = ?", [$fileId]);
        if (!$file) {
            return ['success' => false, 'error' => 'File not found'];
        }
        
        $filePath = UPLOAD_PATH . '/' . $file['file_path'];
        
        // Delete physical file
        if (file_exists($filePath)) {
            unlink($filePath);
        }
        
        // Delete database record
        $deleted = dbExecute("DELETE FROM file_uploads WHERE id = ?", [$fileId]);
        
        if ($deleted) {
            logActivity('File Deleted', 'file_uploads', $fileId, $file, null);
            return ['success' => true];
        } else {
            return ['success' => false, 'error' => 'Failed to delete file record'];
        }
    } catch (Exception $e) {
        error_log("File deletion error: " . $e->getMessage());
        return ['success' => false, 'error' => 'Database error occurred'];
    }
}
?>
