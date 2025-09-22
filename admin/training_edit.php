<?php
/**
 * Training Program Edit/Add for FSMC Admin System
 * Created: 2025-01-22
 */

require_once 'config/config.php';

$trainingId = intval($_GET['id'] ?? 0);
$isEdit = $trainingId > 0;

// Set page variables
$pageTitle = $isEdit ? 'Edit Training Program' : 'Add New Training Program';
$pageIcon = 'fas fa-graduation-cap';
$pageDescription = $isEdit ? 'Update training program information' : 'Create a new training program';
$breadcrumbs = [
    ['title' => 'Training', 'url' => 'training.php'],
    ['title' => $isEdit ? 'Edit Program' : 'Add Program']
];

// Get training data if editing
$training = null;
if ($isEdit) {
    $training = dbGetRow("SELECT * FROM training_programs WHERE id = ?", [$trainingId]);
    if (!$training) {
        setErrorMessage('Training program not found.');
        redirect('training.php');
    }
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verifyCSRFToken($_POST['csrf_token'] ?? '')) {
        setErrorMessage('Invalid security token.');
    } else {
        $data = [
            'title' => sanitize($_POST['title'] ?? ''),
            'slug' => sanitize($_POST['slug'] ?? ''),
            'description' => sanitize($_POST['description'] ?? ''),
            'short_description' => sanitize($_POST['short_description'] ?? ''),
            'level' => sanitize($_POST['level'] ?? ''),
            'instructor' => sanitize($_POST['instructor'] ?? ''),
            'duration' => sanitize($_POST['duration'] ?? ''),
            'price' => !empty($_POST['price']) ? floatval($_POST['price']) : null,
            'max_participants' => !empty($_POST['max_participants']) ? intval($_POST['max_participants']) : null,
            'start_date' => !empty($_POST['start_date']) ? $_POST['start_date'] : null,
            'end_date' => !empty($_POST['end_date']) ? $_POST['end_date'] : null,
            'schedule' => sanitize($_POST['schedule'] ?? ''),
            'curriculum' => json_encode(array_filter(array_map('sanitize', $_POST['curriculum'] ?? []))),
            'requirements' => json_encode(array_filter(array_map('sanitize', $_POST['requirements'] ?? []))),
            'status' => sanitize($_POST['status'] ?? 'active'),
            'sort_order' => intval($_POST['sort_order'] ?? 0),
            'meta_title' => sanitize($_POST['meta_title'] ?? ''),
            'meta_description' => sanitize($_POST['meta_description'] ?? '')
        ];
        
        // Validation
        $errors = [];
        
        if (empty($data['title'])) {
            $errors[] = 'Title is required.';
        }
        
        if (empty($data['slug'])) {
            $data['slug'] = generateSlug($data['title']);
        }
        
        if (empty($data['description'])) {
            $errors[] = 'Description is required.';
        }
        
        if (empty($data['level'])) {
            $errors[] = 'Level is required.';
        }
        
        // Validate dates
        if ($data['start_date'] && $data['end_date']) {
            if (strtotime($data['end_date']) < strtotime($data['start_date'])) {
                $errors[] = 'End date must be after start date.';
            }
        }
        
        // Check for duplicate slug
        $slugCheck = dbGetRow(
            "SELECT id FROM training_programs WHERE slug = ? AND id != ?", 
            [$data['slug'], $trainingId]
        );
        if ($slugCheck) {
            $errors[] = 'Slug already exists. Please choose a different one.';
        }
        
        if (empty($errors)) {
            try {
                if ($isEdit) {
                    // Update training program
                    $sql = "UPDATE training_programs SET 
                            title = ?, slug = ?, description = ?, short_description = ?, 
                            level = ?, instructor = ?, duration = ?, price = ?, max_participants = ?, 
                            start_date = ?, end_date = ?, schedule = ?, curriculum = ?, requirements = ?, 
                            status = ?, sort_order = ?, meta_title = ?, meta_description = ?, 
                            updated_at = NOW() 
                            WHERE id = ?";
                    
                    $params = array_values($data);
                    $params[] = $trainingId;
                    
                    if (dbExecute($sql, $params)) {
                        logActivity('Training Program Updated', 'training_programs', $trainingId, $training, $data);
                        setSuccessMessage('Training program updated successfully.');
                        redirect('training.php');
                    } else {
                        $errors[] = 'Failed to update training program.';
                    }
                } else {
                    // Create new training program
                    $sql = "INSERT INTO training_programs 
                            (title, slug, description, short_description, level, instructor, 
                             duration, price, max_participants, start_date, end_date, schedule, 
                             curriculum, requirements, status, sort_order, meta_title, meta_description) 
                            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
                    
                    $newId = dbInsert($sql, array_values($data));
                    if ($newId) {
                        logActivity('Training Program Created', 'training_programs', $newId, null, $data);
                        setSuccessMessage('Training program created successfully.');
                        redirect('training.php');
                    } else {
                        $errors[] = 'Failed to create training program.';
                    }
                }
            } catch (Exception $e) {
                error_log("Training program save error: " . $e->getMessage());
                $errors[] = 'An error occurred while saving the training program.';
            }
        }
        
        if (!empty($errors)) {
            setErrorMessage(implode('<br>', $errors));
        }
    }
}

// Prepare form data
$formData = $training ?: [
    'title' => '',
    'slug' => '',
    'description' => '',
    'short_description' => '',
    'level' => '',
    'instructor' => '',
    'duration' => '',
    'price' => '',
    'max_participants' => '',
    'start_date' => '',
    'end_date' => '',
    'schedule' => '',
    'curriculum' => '[]',
    'requirements' => '[]',
    'status' => 'active',
    'sort_order' => 0,
    'meta_title' => '',
    'meta_description' => ''
];

// Decode JSON fields
$curriculum = json_decode($formData['curriculum'] ?? '[]', true) ?: [];
$requirements = json_decode($formData['requirements'] ?? '[]', true) ?: [];

// Levels
$levels = [
    'beginner' => 'Beginner',
    'intermediate' => 'Intermediate',
    'advanced' => 'Advanced',
    'professional' => 'Professional'
];

include 'includes/header.php';
?>

<form method="POST" data-validate>
    <input type="hidden" name="csrf_token" value="<?php echo generateCSRFToken(); ?>">
    
    <div class="row">
        <!-- Main Content -->
        <div class="col-8">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Program Information</h3>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <label for="title" class="form-label">Title *</label>
                        <input type="text" id="title" name="title" class="form-control" 
                               value="<?php echo htmlspecialchars($formData['title']); ?>" 
                               required maxlength="200">
                    </div>
                    
                    <div class="form-group">
                        <label for="slug" class="form-label">Slug</label>
                        <input type="text" id="slug" name="slug" class="form-control" 
                               value="<?php echo htmlspecialchars($formData['slug']); ?>" 
                               maxlength="200">
                        <div class="form-text">URL-friendly version of the title. Leave empty to auto-generate.</div>
                    </div>
                    
                    <div class="row">
                        <div class="col-6">
                            <div class="form-group">
                                <label for="level" class="form-label">Level *</label>
                                <select id="level" name="level" class="form-control" required>
                                    <option value="">Select Level</option>
                                    <?php foreach ($levels as $value => $label): ?>
                                        <option value="<?php echo $value; ?>" <?php echo $formData['level'] === $value ? 'selected' : ''; ?>>
                                            <?php echo $label; ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label for="instructor" class="form-label">Instructor</label>
                                <input type="text" id="instructor" name="instructor" class="form-control" 
                                       value="<?php echo htmlspecialchars($formData['instructor']); ?>" 
                                       maxlength="100">
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-4">
                            <div class="form-group">
                                <label for="duration" class="form-label">Duration</label>
                                <input type="text" id="duration" name="duration" class="form-control" 
                                       value="<?php echo htmlspecialchars($formData['duration']); ?>" 
                                       placeholder="e.g., 3 days, 2 weeks">
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="form-group">
                                <label for="price" class="form-label">Price (RWF)</label>
                                <input type="number" id="price" name="price" class="form-control" 
                                       value="<?php echo htmlspecialchars($formData['price']); ?>" 
                                       min="0" step="0.01">
                                <div class="form-text">Leave empty for free</div>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="form-group">
                                <label for="max_participants" class="form-label">Max Participants</label>
                                <input type="number" id="max_participants" name="max_participants" class="form-control" 
                                       value="<?php echo htmlspecialchars($formData['max_participants']); ?>" 
                                       min="1">
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-6">
                            <div class="form-group">
                                <label for="start_date" class="form-label">Start Date</label>
                                <input type="date" id="start_date" name="start_date" class="form-control" 
                                       value="<?php echo htmlspecialchars($formData['start_date']); ?>">
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label for="end_date" class="form-label">End Date</label>
                                <input type="date" id="end_date" name="end_date" class="form-control" 
                                       value="<?php echo htmlspecialchars($formData['end_date']); ?>">
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="schedule" class="form-label">Schedule</label>
                        <input type="text" id="schedule" name="schedule" class="form-control" 
                               value="<?php echo htmlspecialchars($formData['schedule']); ?>" 
                               placeholder="e.g., Mon-Fri 9:00-17:00, Weekends 8:00-16:00">
                    </div>
                    
                    <div class="form-group">
                        <label for="short_description" class="form-label">Short Description</label>
                        <textarea id="short_description" name="short_description" class="form-control" 
                                  rows="3" maxlength="500"><?php echo htmlspecialchars($formData['short_description']); ?></textarea>
                        <div class="form-text">Brief description for listings and previews.</div>
                    </div>
                    
                    <div class="form-group">
                        <label for="description" class="form-label">Full Description *</label>
                        <textarea id="description" name="description" class="form-control" 
                                  rows="8" required><?php echo htmlspecialchars($formData['description']); ?></textarea>
                    </div>
                </div>
            </div>
            
            <!-- Curriculum -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Curriculum</h3>
                </div>
                <div class="card-body">
                    <div id="curriculum-container">
                        <?php foreach ($curriculum as $index => $item): ?>
                        <div class="form-group d-flex align-items-center curriculum-item">
                            <input type="text" name="curriculum[]" class="form-control" 
                                   value="<?php echo htmlspecialchars($item); ?>" 
                                   placeholder="Curriculum item (e.g., Introduction to GIS, Data Collection Methods)">
                            <button type="button" class="btn btn-outline-danger btn-sm ms-2 remove-curriculum">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                        <?php endforeach; ?>
                        
                        <?php if (empty($curriculum)): ?>
                        <div class="form-group d-flex align-items-center curriculum-item">
                            <input type="text" name="curriculum[]" class="form-control" 
                                   placeholder="Curriculum item (e.g., Introduction to GIS, Data Collection Methods)">
                            <button type="button" class="btn btn-outline-danger btn-sm ms-2 remove-curriculum">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                        <?php endif; ?>
                    </div>
                    <button type="button" id="add-curriculum" class="btn btn-outline-primary btn-sm">
                        <i class="fas fa-plus"></i> Add Curriculum Item
                    </button>
                </div>
            </div>
            
            <!-- Requirements -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Requirements</h3>
                </div>
                <div class="card-body">
                    <div id="requirements-container">
                        <?php foreach ($requirements as $index => $requirement): ?>
                        <div class="form-group d-flex align-items-center requirement-item">
                            <input type="text" name="requirements[]" class="form-control" 
                                   value="<?php echo htmlspecialchars($requirement); ?>" 
                                   placeholder="Requirement (e.g., Basic computer skills, High school diploma)">
                            <button type="button" class="btn btn-outline-danger btn-sm ms-2 remove-requirement">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                        <?php endforeach; ?>
                        
                        <?php if (empty($requirements)): ?>
                        <div class="form-group d-flex align-items-center requirement-item">
                            <input type="text" name="requirements[]" class="form-control" 
                                   placeholder="Requirement (e.g., Basic computer skills, High school diploma)">
                            <button type="button" class="btn btn-outline-danger btn-sm ms-2 remove-requirement">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                        <?php endif; ?>
                    </div>
                    <button type="button" id="add-requirement" class="btn btn-outline-primary btn-sm">
                        <i class="fas fa-plus"></i> Add Requirement
                    </button>
                </div>
            </div>
        </div>
        
        <!-- Sidebar -->
        <div class="col-4">
            <!-- Publish -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Publish</h3>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <label for="status" class="form-label">Status</label>
                        <select id="status" name="status" class="form-control">
                            <option value="active" <?php echo $formData['status'] === 'active' ? 'selected' : ''; ?>>Active</option>
                            <option value="inactive" <?php echo $formData['status'] === 'inactive' ? 'selected' : ''; ?>>Inactive</option>
                            <option value="draft" <?php echo $formData['status'] === 'draft' ? 'selected' : ''; ?>>Draft</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="sort_order" class="form-label">Sort Order</label>
                        <input type="number" id="sort_order" name="sort_order" class="form-control" 
                               value="<?php echo htmlspecialchars($formData['sort_order']); ?>" 
                               min="0">
                        <div class="form-text">Lower numbers appear first.</div>
                    </div>
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> <?php echo $isEdit ? 'Update' : 'Create'; ?> Program
                    </button>
                    <a href="training.php" class="btn btn-secondary">
                        <i class="fas fa-times"></i> Cancel
                    </a>
                </div>
            </div>
            
            <!-- SEO -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">SEO Settings</h3>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <label for="meta_title" class="form-label">Meta Title</label>
                        <input type="text" id="meta_title" name="meta_title" class="form-control" 
                               value="<?php echo htmlspecialchars($formData['meta_title']); ?>" 
                               maxlength="200">
                    </div>
                    
                    <div class="form-group">
                        <label for="meta_description" class="form-label">Meta Description</label>
                        <textarea id="meta_description" name="meta_description" class="form-control" 
                                  rows="3" maxlength="300"><?php echo htmlspecialchars($formData['meta_description']); ?></textarea>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto-generate slug from title
    const titleInput = document.getElementById('title');
    const slugInput = document.getElementById('slug');
    
    titleInput.addEventListener('input', function() {
        if (!slugInput.value || slugInput.dataset.autoGenerated) {
            const slug = this.value.toLowerCase()
                .replace(/[^a-z0-9\s-]/g, '')
                .replace(/\s+/g, '-')
                .replace(/-+/g, '-')
                .trim('-');
            slugInput.value = slug;
            slugInput.dataset.autoGenerated = 'true';
        }
    });
    
    slugInput.addEventListener('input', function() {
        this.dataset.autoGenerated = 'false';
    });
    
    // Add/remove curriculum items
    document.getElementById('add-curriculum').addEventListener('click', function() {
        const container = document.getElementById('curriculum-container');
        const newItem = document.createElement('div');
        newItem.className = 'form-group d-flex align-items-center curriculum-item';
        newItem.innerHTML = `
            <input type="text" name="curriculum[]" class="form-control" 
                   placeholder="Curriculum item (e.g., Introduction to GIS, Data Collection Methods)">
            <button type="button" class="btn btn-outline-danger btn-sm ms-2 remove-curriculum">
                <i class="fas fa-times"></i>
            </button>
        `;
        container.appendChild(newItem);
    });
    
    document.addEventListener('click', function(e) {
        if (e.target.closest('.remove-curriculum')) {
            const item = e.target.closest('.curriculum-item');
            if (document.querySelectorAll('.curriculum-item').length > 1) {
                item.remove();
            }
        }
    });
    
    // Add/remove requirements
    document.getElementById('add-requirement').addEventListener('click', function() {
        const container = document.getElementById('requirements-container');
        const newItem = document.createElement('div');
        newItem.className = 'form-group d-flex align-items-center requirement-item';
        newItem.innerHTML = `
            <input type="text" name="requirements[]" class="form-control" 
                   placeholder="Requirement (e.g., Basic computer skills, High school diploma)">
            <button type="button" class="btn btn-outline-danger btn-sm ms-2 remove-requirement">
                <i class="fas fa-times"></i>
            </button>
        `;
        container.appendChild(newItem);
    });
    
    document.addEventListener('click', function(e) {
        if (e.target.closest('.remove-requirement')) {
            const item = e.target.closest('.requirement-item');
            if (document.querySelectorAll('.requirement-item').length > 1) {
                item.remove();
            }
        }
    });
});
</script>

<?php include 'includes/footer.php'; ?>
