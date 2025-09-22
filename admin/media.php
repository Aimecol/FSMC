<?php
/**
 * File Upload & Media Management for FSMC Admin System
 * Created: 2025-01-22
 */

require_once 'config/config.php';

// Set page variables
$pageTitle = 'Media Management';
$pageIcon = 'fas fa-images';
$pageDescription = 'Manage uploaded files and media';
$pageActions = '<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#uploadModal"><i class="fas fa-upload"></i> Upload Files</button>';

// Handle actions
$action = $_GET['action'] ?? '';
$fileId = $_GET['id'] ?? 0;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verifyCSRFToken($_POST['csrf_token'] ?? '')) {
        setErrorMessage('Invalid security token.');
        redirect('media.php');
    }
    
    if ($action === 'delete' && $fileId) {
        // Delete file
        $file = dbGetRow("SELECT filename, file_path FROM file_uploads WHERE id = ?", [$fileId]);
        if ($file) {
            // Delete physical file
            $fullPath = UPLOAD_PATH . '/' . $file['file_path'];
            if (file_exists($fullPath)) {
                unlink($fullPath);
            }
            
            // Delete database record
            if (dbExecute("DELETE FROM file_uploads WHERE id = ?", [$fileId])) {
                logActivity('File Deleted', 'file_uploads', $fileId, $file);
                setSuccessMessage('File "' . $file['filename'] . '" has been deleted successfully.');
            } else {
                setErrorMessage('Failed to delete file.');
            }
        }
        redirect('media.php');
    }
    
    if ($action === 'upload') {
        // Handle file upload
        if (!empty($_FILES['files']['name'][0])) {
            $uploadedFiles = [];
            $errors = [];
            
            for ($i = 0; $i < count($_FILES['files']['name']); $i++) {
                if ($_FILES['files']['error'][$i] === UPLOAD_ERR_OK) {
                    $filename = $_FILES['files']['name'][$i];
                    $tmpName = $_FILES['files']['tmp_name'][$i];
                    $fileSize = $_FILES['files']['size'][$i];
                    $fileType = $_FILES['files']['type'][$i];
                    
                    // Validate file
                    $validation = validateUploadedFile($filename, $fileSize, $tmpName);
                    if ($validation['valid']) {
                        // Generate unique filename
                        $extension = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
                        $uniqueName = uniqid() . '_' . time() . '.' . $extension;
                        
                        // Determine upload directory based on file type
                        $uploadDir = 'general';
                        if (in_array($extension, ['jpg', 'jpeg', 'png', 'gif', 'webp'])) {
                            $uploadDir = 'images';
                        } elseif (in_array($extension, ['pdf', 'doc', 'docx'])) {
                            $uploadDir = 'documents';
                        }
                        
                        $uploadPath = UPLOAD_PATH . '/' . $uploadDir;
                        if (!is_dir($uploadPath)) {
                            mkdir($uploadPath, 0755, true);
                        }
                        
                        $filePath = $uploadDir . '/' . $uniqueName;
                        $fullPath = UPLOAD_PATH . '/' . $filePath;
                        
                        if (move_uploaded_file($tmpName, $fullPath)) {
                            // Save to database
                            $data = [
                                'filename' => $filename,
                                'original_name' => $filename,
                                'file_path' => $filePath,
                                'file_size' => $fileSize,
                                'file_type' => $fileType,
                                'mime_type' => mime_content_type($fullPath),
                                'uploaded_by' => getCurrentUserId()
                            ];
                            
                            $sql = "INSERT INTO file_uploads 
                                    (filename, original_name, file_path, file_size, file_type, mime_type, uploaded_by) 
                                    VALUES (?, ?, ?, ?, ?, ?, ?)";
                            
                            $fileId = dbInsert($sql, array_values($data));
                            if ($fileId) {
                                $uploadedFiles[] = $filename;
                                logActivity('File Uploaded', 'file_uploads', $fileId, null, $data);
                            } else {
                                $errors[] = "Failed to save file record for: $filename";
                                unlink($fullPath);
                            }
                        } else {
                            $errors[] = "Failed to move uploaded file: $filename";
                        }
                    } else {
                        $errors[] = "File validation failed for $filename: " . $validation['error'];
                    }
                } else {
                    $errors[] = "Upload error for file: $filename";
                }
            }
            
            if (!empty($uploadedFiles)) {
                setSuccessMessage(count($uploadedFiles) . ' file(s) uploaded successfully: ' . implode(', ', $uploadedFiles));
            }
            
            if (!empty($errors)) {
                setErrorMessage(implode('<br>', $errors));
            }
        } else {
            setErrorMessage('No files selected for upload.');
        }
        
        redirect('media.php');
    }
}

// Get files with pagination
$page = max(1, intval($_GET['page'] ?? 1));
$limit = 20; // Show more files per page
$offset = ($page - 1) * $limit;

$search = sanitize($_GET['search'] ?? '');
$type = sanitize($_GET['type'] ?? '');

// Build query
$whereConditions = [];
$params = [];

if ($search) {
    $whereConditions[] = "(filename LIKE ? OR original_name LIKE ?)";
    $params[] = "%$search%";
    $params[] = "%$search%";
}

if ($type) {
    if ($type === 'images') {
        $whereConditions[] = "mime_type LIKE 'image/%'";
    } elseif ($type === 'documents') {
        $whereConditions[] = "mime_type IN ('application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document')";
    } elseif ($type === 'other') {
        $whereConditions[] = "mime_type NOT LIKE 'image/%' AND mime_type NOT IN ('application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document')";
    }
}

$whereClause = !empty($whereConditions) ? 'WHERE ' . implode(' AND ', $whereConditions) : '';

// Get total count
$totalQuery = "SELECT COUNT(*) as total FROM file_uploads $whereClause";
$totalResult = dbGetRow($totalQuery, $params);
$totalFiles = $totalResult['total'] ?? 0;
$totalPages = ceil($totalFiles / $limit);

// Get files
$filesQuery = "SELECT f.*, u.username as uploaded_by_name 
               FROM file_uploads f 
               LEFT JOIN admin_users u ON f.uploaded_by = u.id 
               $whereClause 
               ORDER BY f.created_at DESC 
               LIMIT $limit OFFSET $offset";
$files = dbGetRows($filesQuery, $params);

// Get storage statistics
$stats = [
    'total_files' => dbGetValue("SELECT COUNT(*) FROM file_uploads"),
    'total_size' => dbGetValue("SELECT SUM(file_size) FROM file_uploads") ?: 0,
    'images' => dbGetValue("SELECT COUNT(*) FROM file_uploads WHERE mime_type LIKE 'image/%'"),
    'documents' => dbGetValue("SELECT COUNT(*) FROM file_uploads WHERE mime_type IN ('application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document')")
];

include 'includes/header.php';
?>

<!-- Storage Statistics -->
<div class="row mb-4">
    <div class="col-md-3">
        <div class="stats-card">
            <div class="stats-icon bg-primary">
                <i class="fas fa-file"></i>
            </div>
            <div class="stats-content">
                <h3><?php echo number_format($stats['total_files']); ?></h3>
                <p>Total Files</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stats-card">
            <div class="stats-icon bg-info">
                <i class="fas fa-hdd"></i>
            </div>
            <div class="stats-content">
                <h3><?php echo formatFileSize($stats['total_size']); ?></h3>
                <p>Storage Used</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stats-card">
            <div class="stats-icon bg-success">
                <i class="fas fa-image"></i>
            </div>
            <div class="stats-content">
                <h3><?php echo number_format($stats['images']); ?></h3>
                <p>Images</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stats-card">
            <div class="stats-icon bg-warning">
                <i class="fas fa-file-pdf"></i>
            </div>
            <div class="stats-content">
                <h3><?php echo number_format($stats['documents']); ?></h3>
                <p>Documents</p>
            </div>
        </div>
    </div>
</div>

<!-- Filters -->
<div class="card mb-3">
    <div class="card-body">
        <form method="GET" class="row align-items-end">
            <div class="col-md-6">
                <label for="search" class="form-label">Search Files</label>
                <input type="text" id="search" name="search" class="form-control" 
                       value="<?php echo htmlspecialchars($search); ?>" 
                       placeholder="Search by filename...">
            </div>
            <div class="col-md-3">
                <label for="type" class="form-label">File Type</label>
                <select id="type" name="type" class="form-control">
                    <option value="">All Types</option>
                    <option value="images" <?php echo $type === 'images' ? 'selected' : ''; ?>>Images</option>
                    <option value="documents" <?php echo $type === 'documents' ? 'selected' : ''; ?>>Documents</option>
                    <option value="other" <?php echo $type === 'other' ? 'selected' : ''; ?>>Other</option>
                </select>
            </div>
            <div class="col-md-3">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-search"></i> Search
                </button>
                <a href="media.php" class="btn btn-secondary">
                    <i class="fas fa-times"></i> Clear
                </a>
            </div>
        </form>
    </div>
</div>

<!-- Files Grid -->
<div class="card">
    <div class="card-header">
        <h3 class="card-title">
            <i class="fas fa-folder"></i>
            Media Files (<?php echo number_format($totalFiles); ?> total)
        </h3>
    </div>
    <div class="card-body">
        <?php if (empty($files)): ?>
            <div class="text-center py-4">
                <i class="fas fa-images fa-3x text-muted mb-3"></i>
                <h5 class="text-muted">No files found</h5>
                <p class="text-muted">Upload your first file to get started.</p>
                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#uploadModal">
                    <i class="fas fa-upload"></i> Upload Files
                </button>
            </div>
        <?php else: ?>
            <div class="row">
                <?php foreach ($files as $file): ?>
                <div class="col-md-3 mb-4">
                    <div class="file-card">
                        <div class="file-preview">
                            <?php if (strpos($file['mime_type'], 'image/') === 0): ?>
                                <img src="<?php echo UPLOAD_URL . '/' . $file['file_path']; ?>" 
                                     alt="<?php echo htmlspecialchars($file['filename']); ?>" 
                                     class="file-thumbnail">
                            <?php else: ?>
                                <div class="file-icon">
                                    <i class="fas fa-<?php 
                                        echo strpos($file['mime_type'], 'pdf') !== false ? 'file-pdf' : 
                                            (strpos($file['mime_type'], 'word') !== false ? 'file-word' : 'file'); 
                                    ?> fa-3x"></i>
                                </div>
                            <?php endif; ?>
                        </div>
                        
                        <div class="file-info">
                            <h6 class="file-name" title="<?php echo htmlspecialchars($file['filename']); ?>">
                                <?php echo htmlspecialchars(strlen($file['filename']) > 20 ? substr($file['filename'], 0, 20) . '...' : $file['filename']); ?>
                            </h6>
                            <small class="text-muted">
                                <?php echo formatFileSize($file['file_size']); ?> • 
                                <?php echo date('M j, Y', strtotime($file['created_at'])); ?>
                            </small>
                            <br>
                            <small class="text-muted">
                                by <?php echo htmlspecialchars($file['uploaded_by_name'] ?? 'Unknown'); ?>
                            </small>
                        </div>
                        
                        <div class="file-actions">
                            <a href="<?php echo UPLOAD_URL . '/' . $file['file_path']; ?>" 
                               target="_blank" class="btn btn-sm btn-outline-primary" title="View">
                                <i class="fas fa-eye"></i>
                            </a>
                            <button type="button" class="btn btn-sm btn-outline-info btn-copy" 
                                    data-url="<?php echo UPLOAD_URL . '/' . $file['file_path']; ?>" title="Copy URL">
                                <i class="fas fa-copy"></i>
                            </button>
                            <button type="button" class="btn btn-sm btn-outline-danger btn-delete" 
                                    data-url="media.php?action=delete&id=<?php echo $file['id']; ?>"
                                    data-name="<?php echo htmlspecialchars($file['filename']); ?>" title="Delete">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
            
            <!-- Pagination -->
            <?php if ($totalPages > 1): ?>
            <nav aria-label="Files pagination">
                <ul class="pagination justify-content-center">
                    <?php if ($page > 1): ?>
                        <li class="page-item">
                            <a class="page-link" href="?page=<?php echo $page - 1; ?>&search=<?php echo urlencode($search); ?>&type=<?php echo urlencode($type); ?>">
                                <i class="fas fa-chevron-left"></i> Previous
                            </a>
                        </li>
                    <?php endif; ?>
                    
                    <?php for ($i = max(1, $page - 2); $i <= min($totalPages, $page + 2); $i++): ?>
                        <li class="page-item <?php echo $i === $page ? 'active' : ''; ?>">
                            <a class="page-link" href="?page=<?php echo $i; ?>&search=<?php echo urlencode($search); ?>&type=<?php echo urlencode($type); ?>">
                                <?php echo $i; ?>
                            </a>
                        </li>
                    <?php endfor; ?>
                    
                    <?php if ($page < $totalPages): ?>
                        <li class="page-item">
                            <a class="page-link" href="?page=<?php echo $page + 1; ?>&search=<?php echo urlencode($search); ?>&type=<?php echo urlencode($type); ?>">
                                Next <i class="fas fa-chevron-right"></i>
                            </a>
                        </li>
                    <?php endif; ?>
                </ul>
            </nav>
            <?php endif; ?>
        <?php endif; ?>
    </div>
</div>

<!-- Upload Modal -->
<div class="modal" id="uploadModal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Upload Files</h5>
                <button type="button" class="modal-close" data-dismiss="modal">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <form method="POST" enctype="multipart/form-data" action="media.php?action=upload">
                <input type="hidden" name="csrf_token" value="<?php echo generateCSRFToken(); ?>">
                
                <div class="modal-body">
                    <div class="upload-area" id="uploadArea">
                        <div class="upload-content">
                            <i class="fas fa-cloud-upload-alt fa-3x text-muted mb-3"></i>
                            <h5>Drag & Drop Files Here</h5>
                            <p class="text-muted">or click to browse files</p>
                            <input type="file" id="fileInput" name="files[]" multiple 
                                   accept=".jpg,.jpeg,.png,.gif,.webp,.pdf,.doc,.docx,.xls,.xlsx">
                        </div>
                    </div>
                    
                    <div id="fileList" class="mt-3"></div>
                    
                    <div class="mt-3">
                        <small class="text-muted">
                            <strong>Allowed file types:</strong> Images (JPG, PNG, GIF, WebP), Documents (PDF, DOC, DOCX, XLS, XLSX)<br>
                            <strong>Maximum file size:</strong> <?php echo formatFileSize(MAX_FILE_SIZE); ?> per file
                        </small>
                    </div>
                </div>
                
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary" id="uploadBtn" disabled>
                        <i class="fas fa-upload"></i> Upload Files
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal" id="deleteModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirm Deletion</h5>
                <button type="button" class="modal-close" data-dismiss="modal">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete this file? This action cannot be undone.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <form id="deleteForm" method="POST" style="display: inline;">
                    <input type="hidden" name="csrf_token" value="<?php echo generateCSRFToken(); ?>">
                    <button type="submit" class="btn btn-danger">Delete</button>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
.file-card {
    border: 1px solid #ddd;
    border-radius: 8px;
    overflow: hidden;
    transition: transform 0.2s ease, box-shadow 0.2s ease;
}

.file-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
}

.file-preview {
    height: 150px;
    background: #f8f9fa;
    display: flex;
    align-items: center;
    justify-content: center;
    overflow: hidden;
}

.file-thumbnail {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.file-icon {
    color: #6c757d;
}

.file-info {
    padding: 12px;
    border-bottom: 1px solid #eee;
}

.file-name {
    margin: 0 0 4px 0;
    font-weight: 500;
}

.file-actions {
    padding: 8px 12px;
    display: flex;
    gap: 4px;
}

.upload-area {
    border: 2px dashed #ddd;
    border-radius: 8px;
    padding: 40px;
    text-align: center;
    cursor: pointer;
    transition: border-color 0.3s ease;
}

.upload-area:hover,
.upload-area.dragover {
    border-color: var(--primary-color);
    background: rgba(26, 82, 118, 0.05);
}

.upload-area input[type="file"] {
    position: absolute;
    opacity: 0;
    width: 100%;
    height: 100%;
    cursor: pointer;
}

.file-item {
    display: flex;
    align-items: center;
    padding: 8px;
    border: 1px solid #eee;
    border-radius: 4px;
    margin-bottom: 8px;
}

.file-item .file-name {
    flex: 1;
    margin: 0 12px;
}

.file-item .file-size {
    color: #6c757d;
    font-size: 0.875rem;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const uploadArea = document.getElementById('uploadArea');
    const fileInput = document.getElementById('fileInput');
    const fileList = document.getElementById('fileList');
    const uploadBtn = document.getElementById('uploadBtn');
    
    // Handle drag and drop
    uploadArea.addEventListener('dragover', function(e) {
        e.preventDefault();
        this.classList.add('dragover');
    });
    
    uploadArea.addEventListener('dragleave', function(e) {
        e.preventDefault();
        this.classList.remove('dragover');
    });
    
    uploadArea.addEventListener('drop', function(e) {
        e.preventDefault();
        this.classList.remove('dragover');
        
        const files = e.dataTransfer.files;
        fileInput.files = files;
        displayFiles(files);
    });
    
    uploadArea.addEventListener('click', function() {
        fileInput.click();
    });
    
    fileInput.addEventListener('change', function() {
        displayFiles(this.files);
    });
    
    function displayFiles(files) {
        fileList.innerHTML = '';
        
        if (files.length > 0) {
            uploadBtn.disabled = false;
            
            for (let i = 0; i < files.length; i++) {
                const file = files[i];
                const fileItem = document.createElement('div');
                fileItem.className = 'file-item';
                fileItem.innerHTML = `
                    <i class="fas fa-file"></i>
                    <span class="file-name">${file.name}</span>
                    <span class="file-size">${formatFileSize(file.size)}</span>
                `;
                fileList.appendChild(fileItem);
            }
        } else {
            uploadBtn.disabled = true;
        }
    }
    
    function formatFileSize(bytes) {
        if (bytes === 0) return '0 Bytes';
        const k = 1024;
        const sizes = ['Bytes', 'KB', 'MB', 'GB'];
        const i = Math.floor(Math.log(bytes) / Math.log(k));
        return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
    }
    
    // Handle copy URL buttons
    document.querySelectorAll('.btn-copy').forEach(function(btn) {
        btn.addEventListener('click', function() {
            const url = this.dataset.url;
            navigator.clipboard.writeText(url).then(function() {
                // Show success feedback
                const icon = btn.querySelector('i');
                const originalClass = icon.className;
                icon.className = 'fas fa-check';
                btn.classList.add('btn-success');
                btn.classList.remove('btn-outline-info');
                
                setTimeout(function() {
                    icon.className = originalClass;
                    btn.classList.remove('btn-success');
                    btn.classList.add('btn-outline-info');
                }, 2000);
            });
        });
    });
    
    // Handle delete buttons
    document.querySelectorAll('.btn-delete').forEach(function(btn) {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            
            const url = this.dataset.url;
            const name = this.dataset.name;
            const modal = document.getElementById('deleteModal');
            const form = document.getElementById('deleteForm');
            
            // Update modal content
            modal.querySelector('.modal-body p').textContent = 
                `Are you sure you want to delete "${name}"? This action cannot be undone.`;
            
            // Update form action
            form.action = url;
            
            // Show modal
            modal.classList.add('show');
        });
    });
});
</script>

<?php include 'includes/footer.php'; ?>
