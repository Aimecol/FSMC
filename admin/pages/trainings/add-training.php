<!DOCTYPE php>
<php lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Training | Fair Surveying & Mapping Ltd</title>
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
        <main class="admin-main animate-fadeIn">
            <div class="d-flex justify-between align-center mb-4">
                <h1 class="mt-0 mb-0">Add New Training</h1>
                <div class="d-flex gap-2">
                    <a href="all-trainings.php" class="btn btn-primary">
                        <i class="fas fa-arrow-left"></i> Back to All Trainings
                    </a>
                </div>
            </div>
            
            <!-- Training Form -->
            <div class="card">
                <div class="card-body">
                    <form>
                        <div class="mb-3">
                            <label for="trainingTitle" class="form-label">Training Title</label>
                            <input type="text" class="form-control" id="trainingTitle" required>
                        </div>
                        <div class="mb-3">
                            <label for="category" class="form-label">Category</label>
                            <select class="form-select" id="category" required>
                                <option value="">Select Category</option>
                                <option value="GPS Training">GPS Training</option>
                                <option value="Mapping Techniques">Mapping Techniques</option>
                                <option value="Software Training">Software Training</option>
                                <option value="Field Procedures">Field Procedures</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="duration" class="form-label">Duration</label>
                            <input type="text" class="form-control" id="duration" required>
                        </div>
                        <div class="mb-3">
                            <label for="price" class="form-label">Price</label>
                            <input type="text" class="form-control" id="price" required>
                        </div>
                        <div class="mb-3">
                            <label for="enrollment" class="form-label">Enrollment</label>
                            <input type="text" class="form-control" id="enrollment" required>
                        </div>
                        <div class="mb-3">
                            <label for="status" class="form-label">Status</label>
                            <select class="form-select" id="status" required>
                                <option value="">Select Status</option>
                                <option value="Active">Active</option>
                                <option value="Inactive">Inactive</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control" id="description" rows="3" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="startDate" class="form-label">Start Date</label>
                            <input type="date" class="form-control" id="startDate" required>
                        </div>
                        <div class="mb-3">
                            <label for="endDate" class="form-label">End Date</label>
                            <input type="date" class="form-control" id="endDate" required>
                        </div>
                        <div class="mb-3">
                            <label for="location" class="form-label">Location</label>
                            <input type="text" class="form-control" id="location" required>
                        </div>
                        <div class="mb-3">
                            <label for="instructor" class="form-label">Instructor</label>
                            <input type="text" class="form-control" id="instructor" required>
                        </div>
                        <div class="mb-3">
                            <label for="prerequisites" class="form-label">Prerequisites</label>
                            <textarea class="form-control" id="prerequisites" rows="3" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="objectives" class="form-label">Objectives</label>
                            <textarea class="form-control" id="objectives" rows="3" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="materials" class="form-label">Materials</label>
                            <textarea class="form-control" id="materials" rows="3" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="evaluation" class="form-label">Evaluation</label>
                            <textarea class="form-control" id="evaluation" rows="3" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="notes" class="form-label">Notes</label>
                            <textarea class="form-control" id="notes" rows="3"></textarea>
                        </div>
                        <div class="d-flex justify-end">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-plus"></i> Add Training
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
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>
    
    <!-- Admin JavaScript -->
    <script src="../../js/admin.js"></script>
    
    <script>
        // Initialize DataTable
        $(document).ready(function() {
            $('#trainingsTable').DataTable({
                responsive: true,
                pageLength: 10,
                lengthMenu: [5, 10, 25, 50],
                language: {
                    search: "",
                    searchPlaceholder: "Search trainings..."
                }
            });
            
            // Initialize delete confirmation modals
            const deleteButtons = document.querySelectorAll('.btn-danger');
            deleteButtons.forEach(button => {
                button.addEventListener('click', function(e) {
                    e.preventDefault();
                    if (confirm('Are you sure you want to delete this training? This action cannot be undone.')) {
                        showNotification('Training deleted successfully!', 'success');
                    }
                });
            });
            
            // Filter dropdown
            const filterBtn = document.querySelector('.dropdown button');
            const filterMenu = document.querySelector('.dropdown-menu');
            
            if (filterBtn && filterMenu) {
                filterBtn.addEventListener('click', function(e) {
                    e.stopPropagation();
                    filterMenu.style.display = filterMenu.style.display === 'block' ? 'none' : 'block';
                });
                
                document.addEventListener('click', function() {
                    filterMenu.style.display = 'none';
                });
                
                filterMenu.addEventListener('click', function(e) {
                    e.stopPropagation();
                });
            }
        });
    </script>
</body>
</php> 