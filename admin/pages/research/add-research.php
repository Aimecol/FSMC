<!DOCTYPE php>
<php lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Research | Fair Surveying & Mapping Ltd</title>
    <link rel="icon" type="image/svg+xml" href="../../../images/logo.png">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Admin CSS -->
    <link rel="stylesheet" href="../../css/admin.css">
</head>
<body>
    <div class="admin-layout">
        <!-- Sidebar -->
        <?php include '../includes/sidebar.php'; ?>

        <!-- Header -->
        <?php include '../includes/header.php'; ?>
        
        <!-- Main Content -->
        <main class="admin-main">
            <div class="page-header">
                <div>
                    <h1 class="page-title">Add New Research Project</h1>
                    <p class="page-subtitle">Create a new research project or study.</p>
                </div>
                <div>
                    <a href="all-research.php" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Back to Research
                    </a>
                </div>
            </div>
            
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Research Project Details</h5>
                </div>
                <div class="card-body">
                    <form id="addResearchForm">
                        <div class="mb-3">
                            <label for="title" class="form-label">Project Title</label>
                            <input type="text" class="form-control" id="title" required>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="category" class="form-label">Category</label>
                                <select class="form-select" id="category" required>
                                    <option value="">Select Category</option>
                                    <option value="Geospatial">Geospatial</option>
                                    <option value="GIS">GIS</option>
                                    <option value="Remote Sensing">Remote Sensing</option>
                                    <option value="Surveying">Surveying</option>
                                    <option value="Mapping">Mapping</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="status" class="form-label">Status</label>
                                <select class="form-select" id="status" required>
                                    <option value="">Select Status</option>
                                    <option value="In Progress">In Progress</option>
                                    <option value="Completed">Completed</option>
                                    <option value="Pending">Pending</option>
                                    <option value="On Hold">On Hold</option>
                                </select>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="investigators" class="form-label">Principal Investigators</label>
                            <input type="text" class="form-control" id="investigators" required>
                            <small class="form-text text-muted">Separate multiple investigators with commas</small>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="startDate" class="form-label">Start Date</label>
                                <input type="date" class="form-control" id="startDate" required>
                            </div>
                            <div class="col-md-6">
                                <label for="endDate" class="form-label">End Date</label>
                                <input type="date" class="form-control" id="endDate" required>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="abstract" class="form-label">Abstract</label>
                            <textarea class="form-control" id="abstract" rows="4" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="objectives" class="form-label">Objectives</label>
                            <textarea class="form-control" id="objectives" rows="3" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="methodology" class="form-label">Methodology</label>
                            <textarea class="form-control" id="methodology" rows="4" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="budget" class="form-label">Budget (USD)</label>
                            <input type="number" class="form-control" id="budget" step="0.01" required>
                        </div>
                        <div class="mb-3">
                            <label for="funding" class="form-label">Funding Source</label>
                            <input type="text" class="form-control" id="funding" required>
                        </div>
                        <div class="mb-3">
                            <label for="partners" class="form-label">Partners / Collaborators</label>
                            <input type="text" class="form-control" id="partners">
                            <small class="form-text text-muted">Separate multiple partners with commas</small>
                        </div>
                        <div class="mb-3">
                            <label for="publications" class="form-label">Related Publications</label>
                            <textarea class="form-control" id="publications" rows="2"></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="attachments" class="form-label">Attachments</label>
                            <input type="file" class="form-control" id="attachments" multiple>
                        </div>
                        <div class="d-flex justify-end">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-plus"></i> Add Research Project
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </main>
    </div>
    
    <!-- Notification Container -->
    <div class="notification-container"></div>
    
    <!-- JavaScript Dependencies -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    <!-- Admin JavaScript -->
    <script src="../../js/admin.js"></script>
    
    <script>
        $(document).ready(function() {
            // Handle form submission
            $('#addResearchForm').on('submit', function(e) {
                e.preventDefault();
                
                // Validate start and end dates
                const startDate = new Date($('#startDate').val());
                const endDate = new Date($('#endDate').val());
                
                if (endDate < startDate) {
                    showNotification('End date cannot be before start date', 'error');
                    return;
                }
                
                // Show success notification
                showNotification('Research project added successfully!', 'success');
                
                // In a real application, you would submit the form data via AJAX here
                // and redirect on success
                setTimeout(function() {
                    window.location.href = 'all-research.php';
                }, 1500);
            });
        });
    </script>
</body>
</php> 