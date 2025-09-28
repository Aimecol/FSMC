<?php
/**
 * About Page Management
 * Comprehensive management interface for About page content
 */

require_once 'config/config.php';
require_once 'config/database.php';

// Check authentication
requireAuth();

// Handle form submissions
$message = '';
$messageType = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verifyCSRFToken($_POST['csrf_token'] ?? '')) {
        die('Invalid CSRF token');
    }
    
    $action = $_POST['action'] ?? '';
    
    try {
        switch ($action) {
            case 'update_content':
                updateAboutContent($_POST);
                $message = 'About content updated successfully!';
                $messageType = 'success';
                break;
                
            case 'add_team_member':
                addTeamMember($_POST);
                $message = 'Team member added successfully!';
                $messageType = 'success';
                break;
                
            case 'update_team_member':
                updateTeamMember($_POST);
                $message = 'Team member updated successfully!';
                $messageType = 'success';
                break;
                
            case 'delete_team_member':
                deleteTeamMember($_POST['id']);
                $message = 'Team member deleted successfully!';
                $messageType = 'success';
                break;
                
            case 'update_stats':
                updateCompanyStats($_POST);
                $message = 'Company statistics updated successfully!';
                $messageType = 'success';
                break;
                
            case 'add_timeline_item':
                addTimelineItem($_POST);
                $message = 'Timeline item added successfully!';
                $messageType = 'success';
                break;
                
            case 'update_timeline_item':
                updateTimelineItem($_POST);
                $message = 'Timeline item updated successfully!';
                $messageType = 'success';
                break;
                
            case 'delete_timeline_item':
                deleteTimelineItem($_POST['id']);
                $message = 'Timeline item deleted successfully!';
                $messageType = 'success';
                break;
                
            case 'add_core_value':
                addCoreValue($_POST);
                $message = 'Core value added successfully!';
                $messageType = 'success';
                break;
                
            case 'update_core_value':
                updateCoreValue($_POST);
                $message = 'Core value updated successfully!';
                $messageType = 'success';
                break;
                
            case 'delete_core_value':
                deleteCoreValue($_POST['id']);
                $message = 'Core value deleted successfully!';
                $messageType = 'success';
                break;
                
            case 'add_expertise':
                addExpertiseArea($_POST);
                $message = 'Expertise area added successfully!';
                $messageType = 'success';
                break;
                
            case 'update_expertise':
                updateExpertiseArea($_POST);
                $message = 'Expertise area updated successfully!';
                $messageType = 'success';
                break;
                
            case 'delete_expertise':
                deleteExpertiseArea($_POST['id']);
                $message = 'Expertise area deleted successfully!';
                $messageType = 'success';
                break;
                
            case 'add_faq':
                addAboutFAQ($_POST);
                $message = 'FAQ added successfully!';
                $messageType = 'success';
                break;
                
            case 'update_faq':
                updateAboutFAQ($_POST);
                $message = 'FAQ updated successfully!';
                $messageType = 'success';
                break;
                
            case 'delete_faq':
                deleteAboutFAQ($_POST['id']);
                $message = 'FAQ deleted successfully!';
                $messageType = 'success';
                break;
        }
    } catch (Exception $e) {
        $message = 'Error: ' . $e->getMessage();
        $messageType = 'error';
    }
}

// Helper functions
function updateAboutContent($data) {
    foreach ($data['content'] as $sectionKey => $content) {
        if (isset($content['title']) || isset($content['subtitle']) || isset($content['content'])) {
            dbExecute("
                UPDATE about_content 
                SET title = ?, subtitle = ?, content = ?, updated_at = NOW()
                WHERE section_key = ?
            ", [
                $content['title'] ?? null,
                $content['subtitle'] ?? null,
                $content['content'] ?? null,
                $sectionKey
            ]);
        }
    }
}

function addTeamMember($data) {
    $socialLinks = json_encode([
        'linkedin' => $data['linkedin'] ?? '',
        'twitter' => $data['twitter'] ?? '',
        'facebook' => $data['facebook'] ?? ''
    ]);
    
    $specializations = json_encode(array_filter(explode(',', $data['specializations'] ?? '')));
    
    dbInsert("
        INSERT INTO team_members (name, role, bio, image, email, phone, social_links, specializations, sort_order)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
    ", [
        $data['name'],
        $data['role'],
        $data['bio'] ?? '',
        $data['image'] ?? null,
        $data['email'] ?? null,
        $data['phone'] ?? null,
        $socialLinks,
        $specializations,
        intval($data['sort_order'] ?? 0)
    ]);
}

function updateTeamMember($data) {
    $socialLinks = json_encode([
        'linkedin' => $data['linkedin'] ?? '',
        'twitter' => $data['twitter'] ?? '',
        'facebook' => $data['facebook'] ?? ''
    ]);
    
    $specializations = json_encode(array_filter(explode(',', $data['specializations'] ?? '')));
    
    dbExecute("
        UPDATE team_members 
        SET name = ?, role = ?, bio = ?, image = ?, email = ?, phone = ?, 
            social_links = ?, specializations = ?, sort_order = ?, updated_at = NOW()
        WHERE id = ?
    ", [
        $data['name'],
        $data['role'],
        $data['bio'] ?? '',
        $data['image'] ?? null,
        $data['email'] ?? null,
        $data['phone'] ?? null,
        $socialLinks,
        $specializations,
        intval($data['sort_order'] ?? 0),
        $data['id']
    ]);
}

function deleteTeamMember($id) {
    dbExecute("DELETE FROM team_members WHERE id = ?", [$id]);
}

function updateCompanyStats($data) {
    foreach ($data['stats'] as $statKey => $stat) {
        dbExecute("
            UPDATE company_stats 
            SET label = ?, value = ?, icon = ?, suffix = ?, sort_order = ?, updated_at = NOW()
            WHERE stat_key = ?
        ", [
            $stat['label'],
            intval($stat['value']),
            $stat['icon'] ?? null,
            $stat['suffix'] ?? '+',
            intval($stat['sort_order'] ?? 0),
            $statKey
        ]);
    }
}

function addTimelineItem($data) {
    dbInsert("
        INSERT INTO company_timeline (year, title, description, icon, sort_order)
        VALUES (?, ?, ?, ?, ?)
    ", [
        $data['year'],
        $data['title'],
        $data['description'],
        $data['icon'] ?? null,
        intval($data['sort_order'] ?? 0)
    ]);
}

function updateTimelineItem($data) {
    dbExecute("
        UPDATE company_timeline 
        SET year = ?, title = ?, description = ?, icon = ?, sort_order = ?, updated_at = NOW()
        WHERE id = ?
    ", [
        $data['year'],
        $data['title'],
        $data['description'],
        $data['icon'] ?? null,
        intval($data['sort_order'] ?? 0),
        $data['id']
    ]);
}

function deleteTimelineItem($id) {
    dbExecute("DELETE FROM company_timeline WHERE id = ?", [$id]);
}

function addCoreValue($data) {
    dbInsert("
        INSERT INTO core_values (title, description, icon, sort_order)
        VALUES (?, ?, ?, ?)
    ", [
        $data['title'],
        $data['description'],
        $data['icon'] ?? null,
        intval($data['sort_order'] ?? 0)
    ]);
}

function updateCoreValue($data) {
    dbExecute("
        UPDATE core_values 
        SET title = ?, description = ?, icon = ?, sort_order = ?, updated_at = NOW()
        WHERE id = ?
    ", [
        $data['title'],
        $data['description'],
        $data['icon'] ?? null,
        intval($data['sort_order'] ?? 0),
        $data['id']
    ]);
}

function deleteCoreValue($id) {
    dbExecute("DELETE FROM core_values WHERE id = ?", [$id]);
}

function addExpertiseArea($data) {
    dbInsert("
        INSERT INTO expertise_areas (title, description, icon, sort_order)
        VALUES (?, ?, ?, ?)
    ", [
        $data['title'],
        $data['description'],
        $data['icon'] ?? null,
        intval($data['sort_order'] ?? 0)
    ]);
}

function updateExpertiseArea($data) {
    dbExecute("
        UPDATE expertise_areas 
        SET title = ?, description = ?, icon = ?, sort_order = ?, updated_at = NOW()
        WHERE id = ?
    ", [
        $data['title'],
        $data['description'],
        $data['icon'] ?? null,
        intval($data['sort_order'] ?? 0),
        $data['id']
    ]);
}

function deleteExpertiseArea($id) {
    dbExecute("DELETE FROM expertise_areas WHERE id = ?", [$id]);
}

function addAboutFAQ($data) {
    dbInsert("
        INSERT INTO about_faqs (question, answer, sort_order)
        VALUES (?, ?, ?)
    ", [
        $data['question'],
        $data['answer'],
        intval($data['sort_order'] ?? 0)
    ]);
}

function updateAboutFAQ($data) {
    dbExecute("
        UPDATE about_faqs 
        SET question = ?, answer = ?, sort_order = ?, updated_at = NOW()
        WHERE id = ?
    ", [
        $data['question'],
        $data['answer'],
        intval($data['sort_order'] ?? 0),
        $data['id']
    ]);
}

function deleteAboutFAQ($id) {
    dbExecute("DELETE FROM about_faqs WHERE id = ?", [$id]);
}

// Fetch data for display
$aboutContent = [];
$stmt = dbGetRows("SELECT * FROM about_content ORDER BY sort_order ASC");
foreach ($stmt as $row) {
    $aboutContent[$row['section_key']] = $row;
}

$teamMembers = dbGetRows("SELECT * FROM team_members WHERE is_active = 1 ORDER BY sort_order ASC");
$companyStats = dbGetRows("SELECT * FROM company_stats WHERE is_active = 1 ORDER BY sort_order ASC");
$timeline = dbGetRows("SELECT * FROM company_timeline WHERE is_active = 1 ORDER BY sort_order ASC");
$coreValues = dbGetRows("SELECT * FROM core_values WHERE is_active = 1 ORDER BY sort_order ASC");
$expertiseAreas = dbGetRows("SELECT * FROM expertise_areas WHERE is_active = 1 ORDER BY sort_order ASC");
$aboutFAQs = dbGetRows("SELECT * FROM about_faqs WHERE is_active = 1 ORDER BY sort_order ASC");

// For now, we'll create a simple header since includes/header.php might not exist
include 'includes/header.php';
?>

<div class="container-fluid about-manage-page">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">About Page Management</h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
                        <li class="breadcrumb-item active">About Management</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <?php if ($message): ?>
    <div class="row">
        <div class="col-12">
            <div class="alert alert-<?php echo $messageType === 'success' ? 'success' : 'danger'; ?> alert-dismissible fade show">
                <?php echo htmlspecialchars($message); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <!-- About Content Sections -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title mb-0"><i class="fas fa-file-alt me-2"></i>About Page Content</h4>
                </div>
                <div class="card-body">
                    <form method="POST">
                        <input type="hidden" name="csrf_token" value="<?php echo generateCSRFToken(); ?>">
                        <input type="hidden" name="action" value="update_content">
                        
                        <div class="accordion" id="contentAccordion">
                            <?php 
                            $sections = [
                                'hero' => 'Hero Section',
                                'story' => 'Our Story',
                                'expertise_intro' => 'Expertise Introduction',
                                'values_intro' => 'Values Introduction',
                                'team_intro' => 'Team Introduction',
                                'timeline_intro' => 'Timeline Introduction',
                                'faq_intro' => 'FAQ Introduction',
                                'cta' => 'Call to Action'
                            ];
                            
                            foreach ($sections as $key => $label):
                                $content = $aboutContent[$key] ?? [];
                            ?>
                            <div class="accordion-item">
                                <h2 class="accordion-header">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" 
                                            data-bs-target="#collapse<?php echo $key; ?>">
                                        <?php echo $label; ?>
                                    </button>
                                </h2>
                                <div id="collapse<?php echo $key; ?>" class="accordion-collapse collapse" 
                                     data-bs-parent="#contentAccordion">
                                    <div class="accordion-body">
                                        <div class="form-group mb-3">
                                            <label class="form-label">Title</label>
                                            <input type="text" name="content[<?php echo $key; ?>][title]" 
                                                   class="form-control" value="<?php echo htmlspecialchars($content['title'] ?? ''); ?>">
                                        </div>
                                        
                                        <div class="form-group mb-3">
                                            <label class="form-label">Subtitle</label>
                                            <input type="text" name="content[<?php echo $key; ?>][subtitle]" 
                                                   class="form-control" value="<?php echo htmlspecialchars($content['subtitle'] ?? ''); ?>">
                                        </div>
                                        
                                        <div class="form-group mb-3">
                                            <label class="form-label">Content</label>
                                            <textarea name="content[<?php echo $key; ?>][content]" 
                                                      class="form-control" rows="6"><?php echo htmlspecialchars($content['content'] ?? ''); ?></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                        
                        <div class="mt-3">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Update Content
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Company Statistics -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title mb-0"><i class="fas fa-chart-bar me-2"></i>Company Statistics</h4>
                </div>
                <div class="card-body">
                    <form method="POST">
                        <input type="hidden" name="csrf_token" value="<?php echo generateCSRFToken(); ?>">
                        <input type="hidden" name="action" value="update_stats">
                        
                        <div class="row">
                            <?php foreach ($companyStats as $stat): ?>
                            <div class="col-md-6 col-lg-3 mb-3">
                                <div class="border rounded p-3 stats-card">
                                    <h6><?php echo htmlspecialchars($stat['label']); ?></h6>
                                    
                                    <div class="form-group mb-2">
                                        <label class="form-label">Label</label>
                                        <input type="text" name="stats[<?php echo $stat['stat_key']; ?>][label]" 
                                               class="form-control" value="<?php echo htmlspecialchars($stat['label']); ?>">
                                    </div>
                                    
                                    <div class="form-group mb-2">
                                        <label class="form-label">Value</label>
                                        <input type="number" name="stats[<?php echo $stat['stat_key']; ?>][value]" 
                                               class="form-control" value="<?php echo $stat['value']; ?>">
                                    </div>
                                    
                                    <div class="form-group mb-2">
                                        <label class="form-label">Icon</label>
                                        <input type="text" name="stats[<?php echo $stat['stat_key']; ?>][icon]" 
                                               class="form-control" value="<?php echo htmlspecialchars($stat['icon']); ?>">
                                    </div>
                                    
                                    <div class="form-group mb-2">
                                        <label class="form-label">Suffix</label>
                                        <input type="text" name="stats[<?php echo $stat['stat_key']; ?>][suffix]" 
                                               class="form-control" value="<?php echo htmlspecialchars($stat['suffix']); ?>">
                                    </div>
                                    
                                    <input type="hidden" name="stats[<?php echo $stat['stat_key']; ?>][sort_order]" 
                                           value="<?php echo $stat['sort_order']; ?>">
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                        
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Update Statistics
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Areas of Expertise Management -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="card-title mb-0"><i class="fas fa-lightbulb me-2"></i>Areas of Expertise</h4>
                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addExpertiseModal">
                        <i class="fas fa-plus"></i> Add Expertise Area
                    </button>
                </div>
                <div class="card-body">
                    <div class="row">
                        <?php foreach ($expertiseAreas as $area): ?>
                        <div class="col-md-6 col-lg-4 mb-3">
                            <div class="card border item-card">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                        <h6 class="card-title"><?php echo htmlspecialchars($area['title']); ?></h6>
                                        <div class="dropdown">
                                            <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-toggle="dropdown" aria-expanded="false">
                                                <i class="fas fa-ellipsis-v"></i>
                                            </button>
                                            <ul class="dropdown-menu">
                                                <li><a class="dropdown-item edit-expertise" href="#" data-id="<?php echo $area['id']; ?>" 
                                                       data-title="<?php echo htmlspecialchars($area['title']); ?>"
                                                       data-description="<?php echo htmlspecialchars($area['description']); ?>"
                                                       data-icon="<?php echo htmlspecialchars($area['icon']); ?>"
                                                       data-sort-order="<?php echo $area['sort_order']; ?>">
                                                    <i class="fas fa-edit"></i> Edit
                                                </a></li>
                                                <li><hr class="dropdown-divider"></li>
                                                <li>
                                                    <form method="POST" class="d-inline" onsubmit="return confirm('Are you sure?')">
                                                        <input type="hidden" name="csrf_token" value="<?php echo generateCSRFToken(); ?>">
                                                        <input type="hidden" name="action" value="delete_expertise">
                                                        <input type="hidden" name="id" value="<?php echo $area['id']; ?>">
                                                        <button type="submit" class="dropdown-item text-danger">
                                                            <i class="fas fa-trash"></i> Delete
                                                        </button>
                                                    </form>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                    <p class="card-text small"><?php echo htmlspecialchars($area['description']); ?></p>
                                    <div class="text-muted small">
                                        <i class="<?php echo htmlspecialchars($area['icon']); ?>"></i> 
                                        Sort: <?php echo $area['sort_order']; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Core Values Management -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="card-title mb-0"><i class="fas fa-heart me-2"></i>Core Values</h4>
                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addValueModal">
                        <i class="fas fa-plus"></i> Add Core Value
                    </button>
                </div>
                <div class="card-body">
                    <div class="row">
                        <?php foreach ($coreValues as $value): ?>
                        <div class="col-md-6 col-lg-4 mb-3">
                            <div class="card border item-card">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                        <h6 class="card-title"><?php echo htmlspecialchars($value['title']); ?></h6>
                                        <div class="dropdown">
                                            <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-toggle="dropdown" aria-expanded="false">
                                                <i class="fas fa-ellipsis-v"></i>
                                            </button>
                                            <ul class="dropdown-menu">
                                                <li><a class="dropdown-item edit-value" href="#" data-id="<?php echo $value['id']; ?>"
                                                       data-title="<?php echo htmlspecialchars($value['title']); ?>"
                                                       data-description="<?php echo htmlspecialchars($value['description']); ?>"
                                                       data-icon="<?php echo htmlspecialchars($value['icon']); ?>"
                                                       data-sort-order="<?php echo $value['sort_order']; ?>">
                                                    <i class="fas fa-edit"></i> Edit
                                                </a></li>
                                                <li><hr class="dropdown-divider"></li>
                                                <li>
                                                    <form method="POST" class="d-inline" onsubmit="return confirm('Are you sure?')">
                                                        <input type="hidden" name="csrf_token" value="<?php echo generateCSRFToken(); ?>">
                                                        <input type="hidden" name="action" value="delete_core_value">
                                                        <input type="hidden" name="id" value="<?php echo $value['id']; ?>">
                                                        <button type="submit" class="dropdown-item text-danger">
                                                            <i class="fas fa-trash"></i> Delete
                                                        </button>
                                                    </form>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                    <p class="card-text small"><?php echo htmlspecialchars($value['description']); ?></p>
                                    <div class="text-muted small">
                                        <i class="<?php echo htmlspecialchars($value['icon']); ?>"></i> 
                                        Sort: <?php echo $value['sort_order']; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Company Journey (Timeline) Management -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="card-title mb-0"><i class="fas fa-history me-2"></i>Our Journey (Timeline)</h4>
                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addTimelineModal">
                        <i class="fas fa-plus"></i> Add Timeline Item
                    </button>
                </div>
                <div class="card-body">
                    <div class="timeline-container">
                        <?php foreach ($timeline as $item): ?>
                        <div class="timeline-item mb-4 p-3">
                            <div class="d-flex justify-content-between align-items-start">
                                <div class="flex-grow-1">
                                    <div class="d-flex align-items-center mb-2">
                                        <span class="badge bg-primary me-2"><?php echo $item['year']; ?></span>
                                        <h6 class="mb-0"><?php echo htmlspecialchars($item['title']); ?></h6>
                                    </div>
                                    <p class="text-muted mb-2"><?php echo htmlspecialchars($item['description']); ?></p>
                                    <small class="text-muted">
                                        <i class="<?php echo htmlspecialchars($item['icon']); ?>"></i> 
                                        Sort: <?php echo $item['sort_order']; ?>
                                    </small>
                                </div>
                                <div class="dropdown">
                                    <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-toggle="dropdown" aria-expanded="false">
                                        <i class="fas fa-ellipsis-v"></i>
                                    </button>
                                    <ul class="dropdown-menu">
                                        <li><a class="dropdown-item edit-timeline" href="#" data-id="<?php echo $item['id']; ?>"
                                               data-year="<?php echo $item['year']; ?>"
                                               data-title="<?php echo htmlspecialchars($item['title']); ?>"
                                               data-description="<?php echo htmlspecialchars($item['description']); ?>"
                                               data-icon="<?php echo htmlspecialchars($item['icon']); ?>"
                                               data-sort-order="<?php echo $item['sort_order']; ?>">
                                            <i class="fas fa-edit"></i> Edit
                                        </a></li>
                                        <li><hr class="dropdown-divider"></li>
                                        <li>
                                            <form method="POST" class="d-inline" onsubmit="return confirm('Are you sure?')">
                                                <input type="hidden" name="csrf_token" value="<?php echo generateCSRFToken(); ?>">
                                                <input type="hidden" name="action" value="delete_timeline_item">
                                                <input type="hidden" name="id" value="<?php echo $item['id']; ?>">
                                                <button type="submit" class="dropdown-item text-danger">
                                                    <i class="fas fa-trash"></i> Delete
                                                </button>
                                            </form>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Team Members Management -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="card-title mb-0"><i class="fas fa-users me-2"></i>Team Members</h4>
                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addTeamMemberModal">
                        <i class="fas fa-plus"></i> Add Team Member
                    </button>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Role</th>
                                    <th>Email</th>
                                    <th>Sort Order</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($teamMembers as $member): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($member['name']); ?></td>
                                    <td><?php echo htmlspecialchars($member['role']); ?></td>
                                    <td><?php echo htmlspecialchars($member['email'] ?? 'N/A'); ?></td>
                                    <td><?php echo $member['sort_order']; ?></td>
                                    <td>
                                        <button type="button" class="btn btn-sm btn-outline-primary edit-team-member" 
                                                data-id="<?php echo $member['id']; ?>">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <form method="POST" class="d-inline" onsubmit="return confirm('Are you sure?')">
                                            <input type="hidden" name="csrf_token" value="<?php echo generateCSRFToken(); ?>">
                                            <input type="hidden" name="action" value="delete_team_member">
                                            <input type="hidden" name="id" value="<?php echo $member['id']; ?>">
                                            <button type="submit" class="btn btn-sm btn-outline-danger">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add Team Member Modal -->
<div class="modal fade" id="addTeamMemberModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add Team Member</h5>
                <button type="button" class="btn-close" data-dismiss="modal">&times;</button>
            </div>
            <form method="POST">
                <input type="hidden" name="csrf_token" value="<?php echo generateCSRFToken(); ?>">
                <input type="hidden" name="action" value="add_team_member">
                
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label class="form-label">Name *</label>
                                <input type="text" name="name" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label class="form-label">Role *</label>
                                <input type="text" name="role" class="form-control" required>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group mb-3">
                        <label class="form-label">Bio</label>
                        <textarea name="bio" class="form-control" rows="4"></textarea>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label class="form-label">Email</label>
                                <input type="email" name="email" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label class="form-label">Phone</label>
                                <input type="text" name="phone" class="form-control">
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group mb-3">
                        <label class="form-label">Specializations (comma separated)</label>
                        <input type="text" name="specializations" class="form-control" 
                               placeholder="e.g., Land Surveying, GIS Analysis, Project Management">
                    </div>
                    
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group mb-3">
                                <label class="form-label">LinkedIn URL</label>
                                <input type="url" name="linkedin" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group mb-3">
                                <label class="form-label">Twitter URL</label>
                                <input type="url" name="twitter" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group mb-3">
                                <label class="form-label">Facebook URL</label>
                                <input type="url" name="facebook" class="form-control">
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group mb-3">
                        <label class="form-label">Sort Order</label>
                        <input type="number" name="sort_order" class="form-control" value="0">
                    </div>
                </div>
                
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Add Team Member</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Team Member Modal -->
<div class="modal fade" id="editTeamMemberModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Team Member</h5>
                <button type="button" class="btn-close" data-dismiss="modal">&times;</button>
            </div>
            <form method="POST" id="editTeamMemberForm">
                <input type="hidden" name="csrf_token" value="<?php echo generateCSRFToken(); ?>">
                <input type="hidden" name="action" value="update_team_member">
                <input type="hidden" name="id" id="edit_member_id">
                
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label class="form-label">Name *</label>
                                <input type="text" name="name" id="edit_name" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label class="form-label">Role *</label>
                                <input type="text" name="role" id="edit_role" class="form-control" required>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group mb-3">
                        <label class="form-label">Bio</label>
                        <textarea name="bio" id="edit_bio" class="form-control" rows="4"></textarea>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label class="form-label">Email</label>
                                <input type="email" name="email" id="edit_email" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label class="form-label">Phone</label>
                                <input type="text" name="phone" id="edit_phone" class="form-control">
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group mb-3">
                        <label class="form-label">Specializations (comma separated)</label>
                        <input type="text" name="specializations" id="edit_specializations" class="form-control">
                    </div>
                    
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group mb-3">
                                <label class="form-label">LinkedIn URL</label>
                                <input type="url" name="linkedin" id="edit_linkedin" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group mb-3">
                                <label class="form-label">Twitter URL</label>
                                <input type="url" name="twitter" id="edit_twitter" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group mb-3">
                                <label class="form-label">Facebook URL</label>
                                <input type="url" name="facebook" id="edit_facebook" class="form-control">
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group mb-3">
                        <label class="form-label">Sort Order</label>
                        <input type="number" name="sort_order" id="edit_sort_order" class="form-control">
                    </div>
                </div>
                
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Update Team Member</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Add Expertise Area Modal -->
<div class="modal fade" id="addExpertiseModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add Expertise Area</h5>
                <button type="button" class="btn-close" data-dismiss="modal">&times;</button>
            </div>
            <form method="POST">
                <input type="hidden" name="csrf_token" value="<?php echo generateCSRFToken(); ?>">
                <input type="hidden" name="action" value="add_expertise">
                
                <div class="modal-body">
                    <div class="form-group mb-3">
                        <label class="form-label">Title *</label>
                        <input type="text" name="title" class="form-control" required>
                    </div>
                    
                    <div class="form-group mb-3">
                        <label class="form-label">Description *</label>
                        <textarea name="description" class="form-control" rows="4" required></textarea>
                    </div>
                    
                    <div class="form-group mb-3">
                        <label class="form-label">Icon (FontAwesome class)</label>
                        <input type="text" name="icon" class="form-control" placeholder="e.g., fas fa-map-marked-alt">
                    </div>
                    
                    <div class="form-group mb-3">
                        <label class="form-label">Sort Order</label>
                        <input type="number" name="sort_order" class="form-control" value="0">
                    </div>
                </div>
                
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Add Expertise Area</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Add Core Value Modal -->
<div class="modal fade" id="addValueModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add Core Value</h5>
                <button type="button" class="btn-close" data-dismiss="modal">&times;</button>
            </div>
            <form method="POST">
                <input type="hidden" name="csrf_token" value="<?php echo generateCSRFToken(); ?>">
                <input type="hidden" name="action" value="add_core_value">
                
                <div class="modal-body">
                    <div class="form-group mb-3">
                        <label class="form-label">Title *</label>
                        <input type="text" name="title" class="form-control" required>
                    </div>
                    
                    <div class="form-group mb-3">
                        <label class="form-label">Description *</label>
                        <textarea name="description" class="form-control" rows="4" required></textarea>
                    </div>
                    
                    <div class="form-group mb-3">
                        <label class="form-label">Icon (FontAwesome class)</label>
                        <input type="text" name="icon" class="form-control" placeholder="e.g., fas fa-handshake">
                    </div>
                    
                    <div class="form-group mb-3">
                        <label class="form-label">Sort Order</label>
                        <input type="number" name="sort_order" class="form-control" value="0">
                    </div>
                </div>
                
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Add Core Value</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Add Timeline Item Modal -->
<div class="modal fade" id="addTimelineModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add Timeline Item</h5>
                <button type="button" class="btn-close" data-dismiss="modal">&times;</button>
            </div>
            <form method="POST">
                <input type="hidden" name="csrf_token" value="<?php echo generateCSRFToken(); ?>">
                <input type="hidden" name="action" value="add_timeline_item">
                
                <div class="modal-body">
                    <div class="form-group mb-3">
                        <label class="form-label">Year *</label>
                        <input type="number" name="year" class="form-control" min="1900" max="2100" required>
                    </div>
                    
                    <div class="form-group mb-3">
                        <label class="form-label">Title *</label>
                        <input type="text" name="title" class="form-control" required>
                    </div>
                    
                    <div class="form-group mb-3">
                        <label class="form-label">Description *</label>
                        <textarea name="description" class="form-control" rows="4" required></textarea>
                    </div>
                    
                    <div class="form-group mb-3">
                        <label class="form-label">Icon (FontAwesome class)</label>
                        <input type="text" name="icon" class="form-control" placeholder="e.g., fas fa-flag">
                    </div>
                    
                    <div class="form-group mb-3">
                        <label class="form-label">Sort Order</label>
                        <input type="number" name="sort_order" class="form-control" value="0">
                    </div>
                </div>
                
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Add Timeline Item</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Expertise Area Modal -->
<div class="modal fade" id="editExpertiseModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Expertise Area</h5>
                <button type="button" class="btn-close" data-dismiss="modal">&times;</button>
            </div>
            <form method="POST" id="editExpertiseForm">
                <input type="hidden" name="csrf_token" value="<?php echo generateCSRFToken(); ?>">
                <input type="hidden" name="action" value="update_expertise">
                <input type="hidden" name="id" id="edit_expertise_id">
                
                <div class="modal-body">
                    <div class="form-group mb-3">
                        <label class="form-label">Title *</label>
                        <input type="text" name="title" id="edit_expertise_title" class="form-control" required>
                    </div>
                    
                    <div class="form-group mb-3">
                        <label class="form-label">Description *</label>
                        <textarea name="description" id="edit_expertise_description" class="form-control" rows="4" required></textarea>
                    </div>
                    
                    <div class="form-group mb-3">
                        <label class="form-label">Icon (FontAwesome class)</label>
                        <input type="text" name="icon" id="edit_expertise_icon" class="form-control" placeholder="e.g., fas fa-map-marked-alt">
                    </div>
                    
                    <div class="form-group mb-3">
                        <label class="form-label">Sort Order</label>
                        <input type="number" name="sort_order" id="edit_expertise_sort_order" class="form-control">
                    </div>
                </div>
                
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Update Expertise Area</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Core Value Modal -->
<div class="modal fade" id="editValueModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Core Value</h5>
                <button type="button" class="btn-close" data-dismiss="modal">&times;</button>
            </div>
            <form method="POST" id="editValueForm">
                <input type="hidden" name="csrf_token" value="<?php echo generateCSRFToken(); ?>">
                <input type="hidden" name="action" value="update_core_value">
                <input type="hidden" name="id" id="edit_value_id">
                
                <div class="modal-body">
                    <div class="form-group mb-3">
                        <label class="form-label">Title *</label>
                        <input type="text" name="title" id="edit_value_title" class="form-control" required>
                    </div>
                    
                    <div class="form-group mb-3">
                        <label class="form-label">Description *</label>
                        <textarea name="description" id="edit_value_description" class="form-control" rows="4" required></textarea>
                    </div>
                    
                    <div class="form-group mb-3">
                        <label class="form-label">Icon (FontAwesome class)</label>
                        <input type="text" name="icon" id="edit_value_icon" class="form-control" placeholder="e.g., fas fa-handshake">
                    </div>
                    
                    <div class="form-group mb-3">
                        <label class="form-label">Sort Order</label>
                        <input type="number" name="sort_order" id="edit_value_sort_order" class="form-control">
                    </div>
                </div>
                
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Update Core Value</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Timeline Item Modal -->
<div class="modal fade" id="editTimelineModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Timeline Item</h5>
                <button type="button" class="btn-close" data-dismiss="modal">&times;</button>
            </div>
            <form method="POST" id="editTimelineForm">
                <input type="hidden" name="csrf_token" value="<?php echo generateCSRFToken(); ?>">
                <input type="hidden" name="action" value="update_timeline_item">
                <input type="hidden" name="id" id="edit_timeline_id">
                
                <div class="modal-body">
                    <div class="form-group mb-3">
                        <label class="form-label">Year *</label>
                        <input type="number" name="year" id="edit_timeline_year" class="form-control" min="1900" max="2100" required>
                    </div>
                    
                    <div class="form-group mb-3">
                        <label class="form-label">Title *</label>
                        <input type="text" name="title" id="edit_timeline_title" class="form-control" required>
                    </div>
                    
                    <div class="form-group mb-3">
                        <label class="form-label">Description *</label>
                        <textarea name="description" id="edit_timeline_description" class="form-control" rows="4" required></textarea>
                    </div>
                    
                    <div class="form-group mb-3">
                        <label class="form-label">Icon (FontAwesome class)</label>
                        <input type="text" name="icon" id="edit_timeline_icon" class="form-control" placeholder="e.g., fas fa-flag">
                    </div>
                    
                    <div class="form-group mb-3">
                        <label class="form-label">Sort Order</label>
                        <input type="number" name="sort_order" id="edit_timeline_sort_order" class="form-control">
                    </div>
                </div>
                
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Update Timeline Item</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>

<script>
// Enhanced JavaScript functionality
document.addEventListener('DOMContentLoaded', function() {
    // Dropdowns are handled by admin.js
    
    // Handle edit team member buttons
    document.querySelectorAll('.edit-team-member').forEach(button => {
        button.addEventListener('click', function() {
            const memberId = this.getAttribute('data-id');
            
            // Fetch member data via AJAX or from data attributes
            fetch(`get_team_member.php?id=${memberId}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const member = data.member;
                        
                        // Populate edit form
                        document.getElementById('edit_member_id').value = member.id;
                        document.getElementById('edit_name').value = member.name || '';
                        document.getElementById('edit_role').value = member.role || '';
                        document.getElementById('edit_bio').value = member.bio || '';
                        document.getElementById('edit_email').value = member.email || '';
                        document.getElementById('edit_phone').value = member.phone || '';
                        document.getElementById('edit_sort_order').value = member.sort_order || 0;
                        
                        // Handle social links JSON
                        const socialLinks = member.social_links ? JSON.parse(member.social_links) : {};
                        document.getElementById('edit_linkedin').value = socialLinks.linkedin || '';
                        document.getElementById('edit_twitter').value = socialLinks.twitter || '';
                        document.getElementById('edit_facebook').value = socialLinks.facebook || '';
                        
                        // Handle specializations JSON
                        const specializations = member.specializations ? JSON.parse(member.specializations) : [];
                        document.getElementById('edit_specializations').value = specializations.join(', ');
                        
                        // Show modal
                        document.getElementById('editTeamMemberModal').classList.add('show');
                    } else {
                        alert('Error loading team member data');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Error loading team member data');
                });
        });
    });
    
    // Handle edit expertise area buttons
    document.querySelectorAll('.edit-expertise').forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            
            // Get data from attributes
            const id = this.getAttribute('data-id');
            const title = this.getAttribute('data-title');
            const description = this.getAttribute('data-description');
            const icon = this.getAttribute('data-icon');
            const sortOrder = this.getAttribute('data-sort-order');
            
            // Populate edit form
            document.getElementById('edit_expertise_id').value = id;
            document.getElementById('edit_expertise_title').value = title || '';
            document.getElementById('edit_expertise_description').value = description || '';
            document.getElementById('edit_expertise_icon').value = icon || '';
            document.getElementById('edit_expertise_sort_order').value = sortOrder || 0;
            
            // Show modal
            document.getElementById('editExpertiseModal').classList.add('show');
        });
    });
    
    // Handle edit core value buttons
    document.querySelectorAll('.edit-value').forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            
            // Get data from attributes
            const id = this.getAttribute('data-id');
            const title = this.getAttribute('data-title');
            const description = this.getAttribute('data-description');
            const icon = this.getAttribute('data-icon');
            const sortOrder = this.getAttribute('data-sort-order');
            
            // Populate edit form
            document.getElementById('edit_value_id').value = id;
            document.getElementById('edit_value_title').value = title || '';
            document.getElementById('edit_value_description').value = description || '';
            document.getElementById('edit_value_icon').value = icon || '';
            document.getElementById('edit_value_sort_order').value = sortOrder || 0;
            
            // Show modal
            document.getElementById('editValueModal').classList.add('show');
        });
    });
    
    // Handle edit timeline item buttons
    document.querySelectorAll('.edit-timeline').forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            
            // Get data from attributes
            const id = this.getAttribute('data-id');
            const year = this.getAttribute('data-year');
            const title = this.getAttribute('data-title');
            const description = this.getAttribute('data-description');
            const icon = this.getAttribute('data-icon');
            const sortOrder = this.getAttribute('data-sort-order');
            
            // Populate edit form
            document.getElementById('edit_timeline_id').value = id;
            document.getElementById('edit_timeline_year').value = year || '';
            document.getElementById('edit_timeline_title').value = title || '';
            document.getElementById('edit_timeline_description').value = description || '';
            document.getElementById('edit_timeline_icon').value = icon || '';
            document.getElementById('edit_timeline_sort_order').value = sortOrder || 0;
            
            // Show modal
            document.getElementById('editTimelineModal').classList.add('show');
        });
    });
    
    // Form validation
    document.querySelectorAll('form').forEach(form => {
        form.addEventListener('submit', function(e) {
            const requiredFields = this.querySelectorAll('[required]');
            let isValid = true;
            
            requiredFields.forEach(field => {
                if (!field.value.trim()) {
                    field.classList.add('is-invalid');
                    isValid = false;
                } else {
                    field.classList.remove('is-invalid');
                }
            });
            
            if (!isValid) {
                e.preventDefault();
                alert('Please fill in all required fields');
            }
        });
    });
    
    // Auto-hide success/error messages
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(alert => {
        setTimeout(() => {
            alert.style.opacity = '0';
            setTimeout(() => alert.remove(), 300);
        }, 5000);
    });
});
</script>
