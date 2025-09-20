<!DOCTYPE php>
<php lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Service Categories | Fair Surveying & Mapping Ltd</title>
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
                <h1 class="mt-0 mb-0">Service Categories</h1>
                <button class="btn btn-primary" data-toggle="modal" data-target="#addCategoryModal">
                    <i class="fas fa-plus"></i> Add Category
                </button>
            </div>
            
            <!-- Category Cards -->
            <div class="dashboard-grid">
                <!-- Surveying Category -->
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <div class="d-flex justify-between align-center">
                            <h5 class="card-title mb-0">Surveying</h5>
                            <div class="dropdown">
                                <button class="btn-icon btn-sm text-white" style="background: transparent;">
                                    <i class="fas fa-ellipsis-v"></i>
                                </button>
                                <div class="dropdown-menu" style="position: absolute; right: 0; min-width: 120px; background: white; border-radius: 4px; box-shadow: 0 2px 5px rgba(0,0,0,0.2); z-index: 100; display: none;">
                                    <a href="#" class="dropdown-item">Edit</a>
                                    <a href="#" class="dropdown-item text-danger">Delete</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-between mb-3">
                            <div>
                                <div class="mb-2"><strong>Services:</strong> 10</div>
                                <div><strong>Status:</strong> <span class="badge badge-success">Active</span></div>
                            </div>
                            <div class="category-icon bg-primary text-white" style="width: 50px; height: 50px; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                                <i class="fas fa-map"></i>
                            </div>
                        </div>
                        <p class="mb-0">Land surveying, boundary determination, and mapping services.</p>
                    </div>
                    <div class="card-footer bg-light">
                        <div class="d-flex justify-between">
                            <span>Created: Mar 15, 2023</span>
                            <a href="all-services.php?category=surveying" class="text-primary">View Services</a>
                        </div>
                    </div>
                </div>
                
                <!-- Engineering Category -->
                <div class="card">
                    <div class="card-header bg-secondary text-white">
                        <div class="d-flex justify-between align-center">
                            <h5 class="card-title mb-0">Engineering</h5>
                            <div class="dropdown">
                                <button class="btn-icon btn-sm text-white" style="background: transparent;">
                                    <i class="fas fa-ellipsis-v"></i>
                                </button>
                                <div class="dropdown-menu" style="display: none;">
                                    <a href="#" class="dropdown-item">Edit</a>
                                    <a href="#" class="dropdown-item text-danger">Delete</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-between mb-3">
                            <div>
                                <div class="mb-2"><strong>Services:</strong> 7</div>
                                <div><strong>Status:</strong> <span class="badge badge-success">Active</span></div>
                            </div>
                            <div class="category-icon bg-secondary text-white" style="width: 50px; height: 50px; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                                <i class="fas fa-drafting-compass"></i>
                            </div>
                        </div>
                        <p class="mb-0">Engineering-related surveying and design services.</p>
                    </div>
                    <div class="card-footer bg-light">
                        <div class="d-flex justify-between">
                            <span>Created: Mar 18, 2023</span>
                            <a href="all-services.php?category=engineering" class="text-secondary">View Services</a>
                        </div>
                    </div>
                </div>
                
                <!-- Technology Category -->
                <div class="card">
                    <div class="card-header" style="background-color: #3498db; color: white;">
                        <div class="d-flex justify-between align-center">
                            <h5 class="card-title mb-0">Technology</h5>
                            <div class="dropdown">
                                <button class="btn-icon btn-sm text-white" style="background: transparent;">
                                    <i class="fas fa-ellipsis-v"></i>
                                </button>
                                <div class="dropdown-menu" style="display: none;">
                                    <a href="#" class="dropdown-item">Edit</a>
                                    <a href="#" class="dropdown-item text-danger">Delete</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-between mb-3">
                            <div>
                                <div class="mb-2"><strong>Services:</strong> 5</div>
                                <div><strong>Status:</strong> <span class="badge badge-success">Active</span></div>
                            </div>
                            <div class="category-icon text-white" style="background-color: #3498db; width: 50px; height: 50px; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                                <i class="fas fa-satellite"></i>
                            </div>
                        </div>
                        <p class="mb-0">Technology-enabled surveying and mapping solutions.</p>
                    </div>
                    <div class="card-footer bg-light">
                        <div class="d-flex justify-between">
                            <span>Created: Mar 20, 2023</span>
                            <a href="all-services.php?category=technology" style="color: #3498db;">View Services</a>
                        </div>
                    </div>
                </div>
                
                <!-- Consulting Category -->
                <div class="card">
                    <div class="card-header" style="background-color: #9b59b6; color: white;">
                        <div class="d-flex justify-between align-center">
                            <h5 class="card-title mb-0">Consulting</h5>
                            <div class="dropdown">
                                <button class="btn-icon btn-sm text-white" style="background: transparent;">
                                    <i class="fas fa-ellipsis-v"></i>
                                </button>
                                <div class="dropdown-menu" style="display: none;">
                                    <a href="#" class="dropdown-item">Edit</a>
                                    <a href="#" class="dropdown-item text-danger">Delete</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-between mb-3">
                            <div>
                                <div class="mb-2"><strong>Services:</strong> 3</div>
                                <div><strong>Status:</strong> <span class="badge badge-success">Active</span></div>
                            </div>
                            <div class="category-icon text-white" style="background-color: #9b59b6; width: 50px; height: 50px; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                                <i class="fas fa-comments"></i>
                            </div>
                        </div>
                        <p class="mb-0">Professional consulting services for land and property matters.</p>
                    </div>
                    <div class="card-footer bg-light">
                        <div class="d-flex justify-between">
                            <span>Created: Apr 5, 2023</span>
                            <a href="all-services.php?category=consulting" style="color: #9b59b6;">View Services</a>
                        </div>
                    </div>
                </div>
                
                <!-- Training Category -->
                <div class="card">
                    <div class="card-header" style="background-color: #e67e22; color: white;">
                        <div class="d-flex justify-between align-center">
                            <h5 class="card-title mb-0">Training</h5>
                            <div class="dropdown">
                                <button class="btn-icon btn-sm text-white" style="background: transparent;">
                                    <i class="fas fa-ellipsis-v"></i>
                                </button>
                                <div class="dropdown-menu" style="display: none;">
                                    <a href="#" class="dropdown-item">Edit</a>
                                    <a href="#" class="dropdown-item text-danger">Delete</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-between mb-3">
                            <div>
                                <div class="mb-2"><strong>Services:</strong> 4</div>
                                <div><strong>Status:</strong> <span class="badge badge-success">Active</span></div>
                            </div>
                            <div class="category-icon text-white" style="background-color: #e67e22; width: 50px; height: 50px; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                                <i class="fas fa-graduation-cap"></i>
                            </div>
                        </div>
                        <p class="mb-0">Training programs and workshops for surveying professionals.</p>
                    </div>
                    <div class="card-footer bg-light">
                        <div class="d-flex justify-between">
                            <span>Created: Apr 12, 2023</span>
                            <a href="all-services.php?category=training" style="color: #e67e22;">View Services</a>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
    
    <!-- Add Category Modal -->
    <div class="modal" id="addCategoryModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background-color: rgba(0,0,0,0.5); z-index: 1000; overflow: auto;">
        <div class="modal-dialog" style="position: relative; width: 500px; max-width: 90%; margin: 100px auto; background-color: white; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
            <div class="modal-content">
                <div class="modal-header" style="padding: 1rem 1.5rem; border-bottom: 1px solid #eee; display: flex; justify-content: space-between; align-items: center;">
                    <h5 class="modal-title" style="margin: 0; font-size: 1.25rem; font-weight: 600; color: var(--primary-color);">Add New Category</h5>
                    <button type="button" class="close-modal" style="background: none; border: none; font-size: 1.5rem; cursor: pointer;">&times;</button>
                </div>
                <div class="modal-body" style="padding: 1.5rem;">
                    <form id="addCategoryForm">
                        <div class="form-group">
                            <label for="categoryName" class="form-label">Category Name*</label>
                            <input type="text" id="categoryName" name="categoryName" class="form-control" placeholder="Enter category name" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="categoryDescription" class="form-label">Description*</label>
                            <textarea id="categoryDescription" name="categoryDescription" class="form-control" rows="3" placeholder="Enter category description" required></textarea>
                        </div>
                        
                        <div class="form-group">
                            <label for="categoryIcon" class="form-label">Icon</label>
                            <select id="categoryIcon" name="categoryIcon" class="form-select">
                                <option value="fa-map">Map</option>
                                <option value="fa-drafting-compass">Drafting Compass</option>
                                <option value="fa-road">Road</option>
                                <option value="fa-satellite">Satellite</option>
                                <option value="fa-comments">Comments</option>
                                <option value="fa-graduation-cap">Graduation Cap</option>
                                <option value="fa-building">Building</option>
                                <option value="fa-laptop">Laptop</option>
                            </select>
                            
                            <div class="icon-preview mt-3 text-center">
                                <div style="width: 50px; height: 50px; background-color: var(--primary-color); border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; color: white;">
                                    <i id="categoryIconPreview" class="fas fa-map"></i>
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="categoryColor" class="form-label">Color</label>
                            <input type="color" id="categoryColor" name="categoryColor" class="form-control" value="#1a5276" style="height: 40px;">
                        </div>
                        
                        <div class="form-group">
                            <label for="categoryStatus" class="form-label">Status</label>
                            <select id="categoryStatus" name="categoryStatus" class="form-select">
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                            </select>
                        </div>
                    </form>
                </div>
                <div class="modal-footer" style="padding: 1rem 1.5rem; border-top: 1px solid #eee; display: flex; justify-content: flex-end; gap: 0.5rem;">
                    <button type="button" class="btn btn-light close-modal">Cancel</button>
                    <button type="button" class="btn btn-primary" id="saveCategory">Save Category</button>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Notification Container -->
    <div class="notification-container"></div>
    
    <!-- Admin JavaScript -->
    <script src="../../js/admin.js"></script>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Modal functionality
            const addCategoryBtn = document.querySelector('[data-toggle="modal"]');
            const categoryModal = document.getElementById('addCategoryModal');
            const closeButtons = document.querySelectorAll('.close-modal');
            
            addCategoryBtn.addEventListener('click', function() {
                categoryModal.style.display = 'block';
                document.body.style.overflow = 'hidden'; // Prevent scrolling
            });
            
            closeButtons.forEach(button => {
                button.addEventListener('click', function() {
                    categoryModal.style.display = 'none';
                    document.body.style.overflow = 'auto'; // Allow scrolling
                });
            });
            
            // Close modal when clicking outside
            window.addEventListener('click', function(e) {
                if (e.target === categoryModal) {
                    categoryModal.style.display = 'none';
                    document.body.style.overflow = 'auto'; // Allow scrolling
                }
            });
            
            // Category icon preview
            const categoryIcon = document.getElementById('categoryIcon');
            const categoryIconPreview = document.getElementById('categoryIconPreview');
            
            categoryIcon.addEventListener('change', function() {
                categoryIconPreview.className = 'fas ' + this.value;
            });
            
            // Category color preview
            const categoryColor = document.getElementById('categoryColor');
            const iconPreviewContainer = categoryIconPreview.parentElement;
            
            categoryColor.addEventListener('input', function() {
                iconPreviewContainer.style.backgroundColor = this.value;
            });
            
            // Save category button
            const saveCategory = document.getElementById('saveCategory');
            
            saveCategory.addEventListener('click', function() {
                const categoryForm = document.getElementById('addCategoryForm');
                
                // Validate form
                if (!validateForm(categoryForm)) {
                    showNotification('Please fill in all required fields.', 'error');
                    return;
                }
                
                // Show loading state
                saveCategory.disabled = true;
                saveCategory.innerphp = '<i class="fas fa-spinner fa-spin"></i> Processing...';
                
                // Simulate form submission (in a real app, this would be an AJAX call)
                setTimeout(function() {
                    // Reset form
                    categoryForm.reset();
                    
                    // Close modal
                    categoryModal.style.display = 'none';
                    document.body.style.overflow = 'auto'; // Allow scrolling
                    
                    // Show success notification
                    showNotification('Category created successfully!', 'success');
                    
                    // Reset button state
                    saveCategory.disabled = false;
                    saveCategory.innerphp = 'Save Category';
                }, 1000);
            });
            
            // Dropdown menu functionality
            const dropdownButtons = document.querySelectorAll('.card .dropdown button');
            
            dropdownButtons.forEach(button => {
                button.addEventListener('click', function(e) {
                    e.stopPropagation();
                    const menu = this.nextElementSibling;
                    menu.style.display = menu.style.display === 'block' ? 'none' : 'block';
                });
            });
            
            // Close dropdown when clicking outside
            document.addEventListener('click', function() {
                const menus = document.querySelectorAll('.dropdown-menu');
                menus.forEach(menu => {
                    menu.style.display = 'none';
                });
            });
            
            // Form validation function
            function validateForm(form) {
                const requiredInputs = form.querySelectorAll('[required]');
                let valid = true;
                
                requiredInputs.forEach(input => {
                    if (!input.value.trim()) {
                        input.classList.add('is-invalid');
                        valid = false;
                    } else {
                        input.classList.remove('is-invalid');
                    }
                });
                
                return valid;
            }
            
            // Show notification function
            function showNotification(message, type) {
                const notificationContainer = document.querySelector('.notification-container');
                
                const notification = document.createElement('div');
                notification.className = `notification notification-${type} animate-slideInUp`;
                notification.innerphp = `
                    <div class="notification-icon">
                        <i class="fas ${type === 'success' ? 'fa-check-circle' : type === 'warning' ? 'fa-exclamation-triangle' : 'fa-times-circle'}"></i>
                    </div>
                    <div class="notification-content">
                        <p>${message}</p>
                    </div>
                    <button class="notification-close">
                        <i class="fas fa-times"></i>
                    </button>
                `;
                
                notificationContainer.appendChild(notification);
                
                // Close button functionality
                notification.querySelector('.notification-close').addEventListener('click', function() {
                    notification.classList.add('fade-out');
                    setTimeout(() => {
                        notification.remove();
                    }, 300);
                });
                
                // Auto remove after 5 seconds
                setTimeout(() => {
                    notification.classList.add('fade-out');
                    setTimeout(() => {
                        notification.remove();
                    }, 300);
                }, 5000);
            }
        });
    </script>
</body>
</php>
