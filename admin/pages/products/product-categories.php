<!DOCTYPE php>
<php lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Categories | Fair Surveying & Mapping Ltd</title>
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
                <h1 class="mt-0 mb-0">Product Categories</h1>
                <button class="btn btn-primary" id="addCategoryBtn">
                    <i class="fas fa-plus"></i> Add New Category
                </button>
            </div>
            
            <!-- Categories Grid -->
            <div class="dashboard-grid">
                <!-- GPS Equipment -->
                <div class="card" data-aos="fade-up" data-aos-delay="100">
                    <div class="card-header bg-primary text-white">
                        <div class="d-flex justify-between align-center">
                            <div class="d-flex align-center gap-2">
                                <i class="fas fa-satellite"></i>
                                <h5 class="card-title mb-0">GPS Equipment</h5>
                            </div>
                            <div class="dropdown">
                                <button class="btn-icon btn-sm text-white" style="background: transparent;">
                                    <i class="fas fa-ellipsis-v"></i>
                                </button>
                                <div class="dropdown-menu dropdown-menu-right" style="display: none;">
                                    <a href="#" class="dropdown-item edit-category">Edit</a>
                                    <a href="#" class="dropdown-item text-danger delete-category">Delete</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-between mb-3">
                            <div>
                                <div class="mb-2"><strong>Products:</strong> 15</div>
                                <div><strong>Status:</strong> <span class="badge badge-success">Active</span></div>
                            </div>
                            <div class="category-icon bg-primary text-white">
                                <i class="fas fa-satellite"></i>
                            </div>
                        </div>
                        <p>Professional GPS equipment for surveying and mapping.</p>
                    </div>
                    <div class="card-footer">
                        <div class="d-flex justify-between">
                            <a href="all-products.php?category=gps-equipment" class="btn btn-sm btn-light">View Products</a>
                            <button class="btn btn-sm btn-secondary edit-category">Edit Category</button>
                        </div>
                    </div>
                </div>
                
                <!-- Software -->
                <div class="card" data-aos="fade-up" data-aos-delay="200">
                    <div class="card-header bg-info text-white">
                        <div class="d-flex justify-between align-center">
                            <div class="d-flex align-center gap-2">
                                <i class="fas fa-laptop-code"></i>
                                <h5 class="card-title mb-0">Software</h5>
                            </div>
                            <div class="dropdown">
                                <button class="btn-icon btn-sm text-white" style="background: transparent;">
                                    <i class="fas fa-ellipsis-v"></i>
                                </button>
                                <div class="dropdown-menu dropdown-menu-right" style="display: none;">
                                    <a href="#" class="dropdown-item edit-category">Edit</a>
                                    <a href="#" class="dropdown-item text-danger delete-category">Delete</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-between mb-3">
                            <div>
                                <div class="mb-2"><strong>Products:</strong> 8</div>
                                <div><strong>Status:</strong> <span class="badge badge-success">Active</span></div>
                            </div>
                            <div class="category-icon bg-info text-white">
                                <i class="fas fa-laptop-code"></i>
                            </div>
                        </div>
                        <p>Specialized software solutions for data processing and analysis.</p>
                    </div>
                    <div class="card-footer">
                        <div class="d-flex justify-between">
                            <a href="all-products.php?category=software" class="btn btn-sm btn-light">View Products</a>
                            <button class="btn btn-sm btn-secondary edit-category">Edit Category</button>
                        </div>
                    </div>
                </div>
                
                <!-- Measurement Tools -->
                <div class="card" data-aos="fade-up" data-aos-delay="300">
                    <div class="card-header bg-success text-white">
                        <div class="d-flex justify-between align-center">
                            <div class="d-flex align-center gap-2">
                                <i class="fas fa-ruler-combined"></i>
                                <h5 class="card-title mb-0">Measurement Tools</h5>
                            </div>
                            <div class="dropdown">
                                <button class="btn-icon btn-sm text-white" style="background: transparent;">
                                    <i class="fas fa-ellipsis-v"></i>
                                </button>
                                <div class="dropdown-menu dropdown-menu-right" style="display: none;">
                                    <a href="#" class="dropdown-item edit-category">Edit</a>
                                    <a href="#" class="dropdown-item text-danger delete-category">Delete</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-between mb-3">
                            <div>
                                <div class="mb-2"><strong>Products:</strong> 12</div>
                                <div><strong>Status:</strong> <span class="badge badge-success">Active</span></div>
                            </div>
                            <div class="category-icon bg-success text-white">
                                <i class="fas fa-ruler-combined"></i>
                            </div>
                        </div>
                        <p>Precision measurement tools for accurate surveying and mapping.</p>
                    </div>
                    <div class="card-footer">
                        <div class="d-flex justify-between">
                            <a href="all-products.php?category=measurement-tools" class="btn btn-sm btn-light">View Products</a>
                            <button class="btn btn-sm btn-secondary edit-category">Edit Category</button>
                        </div>
                    </div>
                </div>
                
                <!-- Survey Equipment -->
                <div class="card" data-aos="fade-up" data-aos-delay="400">
                    <div class="card-header bg-warning text-white">
                        <div class="d-flex justify-between align-center">
                            <div class="d-flex align-center gap-2">
                                <i class="fas fa-compass"></i>
                                <h5 class="card-title mb-0">Survey Equipment</h5>
                            </div>
                            <div class="dropdown">
                                <button class="btn-icon btn-sm text-white" style="background: transparent;">
                                    <i class="fas fa-ellipsis-v"></i>
                                </button>
                                <div class="dropdown-menu dropdown-menu-right" style="display: none;">
                                    <a href="#" class="dropdown-item edit-category">Edit</a>
                                    <a href="#" class="dropdown-item text-danger delete-category">Delete</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-between mb-3">
                            <div>
                                <div class="mb-2"><strong>Products:</strong> 9</div>
                                <div><strong>Status:</strong> <span class="badge badge-success">Active</span></div>
                            </div>
                            <div class="category-icon bg-warning text-white">
                                <i class="fas fa-compass"></i>
                            </div>
                        </div>
                        <p>Essential equipment for professional surveying operations.</p>
                    </div>
                    <div class="card-footer">
                        <div class="d-flex justify-between">
                            <a href="all-products.php?category=survey-equipment" class="btn btn-sm btn-light">View Products</a>
                            <button class="btn btn-sm btn-secondary edit-category">Edit Category</button>
                        </div>
                    </div>
                </div>
                
                <!-- Drones -->
                <div class="card" data-aos="fade-up" data-aos-delay="500">
                    <div class="card-header bg-secondary text-white">
                        <div class="d-flex justify-between align-center">
                            <div class="d-flex align-center gap-2">
                                <i class="fas fa-drone"></i>
                                <h5 class="card-title mb-0">Drones</h5>
                            </div>
                            <div class="dropdown">
                                <button class="btn-icon btn-sm text-white" style="background: transparent;">
                                    <i class="fas fa-ellipsis-v"></i>
                                </button>
                                <div class="dropdown-menu dropdown-menu-right" style="display: none;">
                                    <a href="#" class="dropdown-item edit-category">Edit</a>
                                    <a href="#" class="dropdown-item text-danger delete-category">Delete</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-between mb-3">
                            <div>
                                <div class="mb-2"><strong>Products:</strong> 5</div>
                                <div><strong>Status:</strong> <span class="badge badge-success">Active</span></div>
                            </div>
                            <div class="category-icon bg-secondary text-white">
                                <i class="fas fa-drone"></i>
                            </div>
                        </div>
                        <p>Aerial surveying drones with advanced imaging capabilities.</p>
                    </div>
                    <div class="card-footer">
                        <div class="d-flex justify-between">
                            <a href="all-products.php?category=drones" class="btn btn-sm btn-light">View Products</a>
                            <button class="btn btn-sm btn-secondary edit-category">Edit Category</button>
                        </div>
                    </div>
                </div>
                
                <!-- Safety Equipment -->
                <div class="card" data-aos="fade-up" data-aos-delay="600">
                    <div class="card-header bg-danger text-white">
                        <div class="d-flex justify-between align-center">
                            <div class="d-flex align-center gap-2">
                                <i class="fas fa-hard-hat"></i>
                                <h5 class="card-title mb-0">Safety Equipment</h5>
                            </div>
                            <div class="dropdown">
                                <button class="btn-icon btn-sm text-white" style="background: transparent;">
                                    <i class="fas fa-ellipsis-v"></i>
                                </button>
                                <div class="dropdown-menu dropdown-menu-right" style="display: none;">
                                    <a href="#" class="dropdown-item edit-category">Edit</a>
                                    <a href="#" class="dropdown-item text-danger delete-category">Delete</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-between mb-3">
                            <div>
                                <div class="mb-2"><strong>Products:</strong> 7</div>
                                <div><strong>Status:</strong> <span class="badge badge-success">Active</span></div>
                            </div>
                            <div class="category-icon bg-danger text-white">
                                <i class="fas fa-hard-hat"></i>
                            </div>
                        </div>
                        <p>Essential safety gear for surveying and fieldwork professionals.</p>
                    </div>
                    <div class="card-footer">
                        <div class="d-flex justify-between">
                            <a href="all-products.php?category=safety-equipment" class="btn btn-sm btn-light">View Products</a>
                            <button class="btn btn-sm btn-secondary edit-category">Edit Category</button>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
    
    <!-- Add/Edit Category Modal -->
    <div id="categoryModal" class="modal" style="display: none;">
        <div class="modal-content" style="max-width: 500px;">
            <div class="modal-header">
                <h4 class="modal-title">Add New Category</h4>
                <button type="button" class="close-modal">&times;</button>
            </div>
            <div class="modal-body">
                <form id="categoryForm">
                    <div class="form-group mb-3">
                        <label for="categoryName" class="form-label">Category Name</label>
                        <input type="text" class="form-control" id="categoryName" required>
                    </div>
                    
                    <div class="form-group mb-3">
                        <label for="categoryIcon" class="form-label">Icon</label>
                        <select class="form-select" id="categoryIcon" required>
                            <option value="fa-satellite">Satellite</option>
                            <option value="fa-laptop-code">Laptop Code</option>
                            <option value="fa-ruler-combined">Ruler</option>
                            <option value="fa-compass">Compass</option>
                            <option value="fa-drone">Drone</option>
                            <option value="fa-hard-hat">Hard Hat</option>
                            <option value="fa-book">Book</option>
                            <option value="fa-tools">Tools</option>
                        </select>
                    </div>
                    
                    <div class="form-group mb-3">
                        <label for="categoryColor" class="form-label">Color</label>
                        <input type="color" class="form-control" id="categoryColor" value="#1a5276" style="height: 40px;">
                    </div>
                    
                    <div class="form-group mb-3">
                        <label for="categoryDescription" class="form-label">Description</label>
                        <textarea class="form-control" id="categoryDescription" rows="3" required></textarea>
                    </div>
                    
                    <div class="form-group mb-3">
                        <label for="categoryStatus" class="form-label">Status</label>
                        <select class="form-select" id="categoryStatus" required>
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light close-modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="saveCategory">Save Category</button>
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
            const categoryModal = document.getElementById('categoryModal');
            const addCategoryBtn = document.getElementById('addCategoryBtn');
            const editCategoryBtns = document.querySelectorAll('.edit-category');
            const closeButtons = document.querySelectorAll('.close-modal');
            const modalTitle = document.querySelector('.modal-title');
            
            // Open modal for adding
            addCategoryBtn.addEventListener('click', function() {
                modalTitle.textContent = 'Add New Category';
                categoryModal.style.display = 'block';
                document.body.style.overflow = 'hidden'; // Prevent scrolling
                document.getElementById('categoryForm').reset();
            });
            
            // Open modal for editing
            editCategoryBtns.forEach(button => {
                button.addEventListener('click', function(e) {
                    e.preventDefault();
                    modalTitle.textContent = 'Edit Category';
                    categoryModal.style.display = 'block';
                    document.body.style.overflow = 'hidden'; // Prevent scrolling
                    
                    // Get category data from the card
                    const card = this.closest('.card');
                    const categoryName = card.querySelector('.card-title').textContent;
                    const categoryDescription = card.querySelector('p').textContent;
                    const categoryIcon = card.querySelector('.category-icon i').className.split(' ')[1];
                    const headerColor = getComputedStyle(card.querySelector('.card-header')).backgroundColor;
                    
                    // Fill form with data
                    document.getElementById('categoryName').value = categoryName;
                    document.getElementById('categoryDescription').value = categoryDescription;
                    document.getElementById('categoryIcon').value = categoryIcon;
                    document.getElementById('categoryColor').value = rgbToHex(headerColor);
                    
                    // Set status based on badge
                    const statusBadge = card.querySelector('.badge');
                    document.getElementById('categoryStatus').value = statusBadge.classList.contains('badge-success') ? 'active' : 'inactive';
                });
            });
            
            // Close modal
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
            
            // Save category
            const saveCategory = document.getElementById('saveCategory');
            
            saveCategory.addEventListener('click', function() {
                const categoryForm = document.getElementById('categoryForm');
                
                // Validate form
                if (!validateForm('categoryForm')) {
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
                    const action = modalTitle.textContent.includes('Add') ? 'created' : 'updated';
                    showNotification(`Category ${action} successfully!`, 'success');
                    
                    // Reset button state
                    saveCategory.disabled = false;
                    saveCategory.innerphp = 'Save Category';
                }, 1000);
            });
            
            // Delete category functionality
            const deleteButtons = document.querySelectorAll('.delete-category');
            
            deleteButtons.forEach(button => {
                button.addEventListener('click', function(e) {
                    e.preventDefault();
                    const card = this.closest('.card');
                    const categoryName = card.querySelector('.card-title').textContent;
                    
                    if (confirm(`Are you sure you want to delete the "${categoryName}" category? This will affect all associated products.`)) {
                        // Add fade-out animation
                        card.style.opacity = '0';
                        card.style.transform = 'scale(0.9)';
                        
                        setTimeout(() => {
                            card.remove();
                            showNotification('Category deleted successfully!', 'success');
                        }, 300);
                    }
                });
            });
            
            // Dropdown menu functionality
            const dropdownButtons = document.querySelectorAll('.card .dropdown button');
            
            dropdownButtons.forEach(button => {
                button.addEventListener('click', function(e) {
                    e.stopPropagation();
                    const menu = this.nextElementSibling;
                    
                    // Close all other open menus
                    document.querySelectorAll('.dropdown-menu').forEach(m => {
                        if (m !== menu) m.style.display = 'none';
                    });
                    
                    menu.style.display = menu.style.display === 'block' ? 'none' : 'block';
                });
            });
            
            // Close dropdown when clicking outside
            document.addEventListener('click', function() {
                document.querySelectorAll('.dropdown-menu').forEach(menu => {
                    menu.style.display = 'none';
                });
            });
            
            // Helper function to convert RGB to Hex
            function rgbToHex(rgb) {
                // Extract RGB values
                const rgbMatch = rgb.match(/^rgb\((\d+),\s*(\d+),\s*(\d+)\)$/);
                if (!rgbMatch) return '#1a5276'; // Default color
                
                function hex(x) {
                    return ("0" + parseInt(x).toString(16)).slice(-2);
                }
                
                return "#" + hex(rgbMatch[1]) + hex(rgbMatch[2]) + hex(rgbMatch[3]);
            }
            
            // Add animation to cards
            document.querySelectorAll('.card').forEach((card, index) => {
                setTimeout(() => {
                    card.style.opacity = '1';
                    card.style.transform = 'translateY(0)';
                }, 100 * index);
            });
        });
    </script>
    
    <style>
        .category-icon {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
        }
        
        .card {
            opacity: 0;
            transform: translateY(20px);
            transition: all 0.3s ease-out;
        }
    </style>
</body>
</php> 