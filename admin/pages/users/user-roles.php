<!DOCTYPE php>
<php lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Roles | Fair Surveying & Mapping Ltd</title>
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
                <h1 class="mt-0 mb-0">User Roles</h1>
                <button class="btn btn-primary" id="addRoleBtn">
                    <i class="fas fa-plus"></i> Add New Role
                </button>
            </div>
            
            <!-- Role Cards -->
            <div class="dashboard-grid">
                <!-- Administrator Role -->
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <div class="d-flex justify-between align-center">
                            <div class="d-flex align-center gap-2">
                                <i class="fas fa-user-shield"></i>
                                <h5 class="card-title mb-0">Administrator</h5>
                            </div>
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
                                <div class="mb-2"><strong>Users:</strong> 6</div>
                                <div><strong>Status:</strong> <span class="badge badge-success">Active</span></div>
                            </div>
                            <div class="role-icon bg-primary text-white" style="width: 50px; height: 50px; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                                <i class="fas fa-user-shield"></i>
                            </div>
                        </div>
                        <p class="mb-3">Full access to all system features and capabilities.</p>
                        
                        <div class="role-permissions">
                            <strong>Permissions:</strong>
                            <div class="mt-2 d-flex flex-wrap gap-1">
                                <span class="badge bg-light text-dark">View Dashboard</span>
                                <span class="badge bg-light text-dark">Manage Users</span>
                                <span class="badge bg-light text-dark">Manage Services</span>
                                <span class="badge bg-light text-dark">Manage Products</span>
                                <span class="badge bg-light text-dark">Manage Trainings</span>
                                <span class="badge bg-light text-dark">Manage Research</span>
                                <span class="badge bg-light text-dark">View Analytics</span>
                                <span class="badge bg-light text-dark">Manage Settings</span>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <div class="d-flex justify-between">
                            <button class="btn btn-sm btn-light">View Users</button>
                            <button class="btn btn-sm btn-secondary">Edit Role</button>
                        </div>
                    </div>
                </div>
                
                <!-- Staff Role -->
                <div class="card">
                    <div class="card-header bg-secondary text-white">
                        <div class="d-flex justify-between align-center">
                            <div class="d-flex align-center gap-2">
                                <i class="fas fa-user-tie"></i>
                                <h5 class="card-title mb-0">Staff</h5>
                            </div>
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
                                <div class="mb-2"><strong>Users:</strong> 12</div>
                                <div><strong>Status:</strong> <span class="badge badge-success">Active</span></div>
                            </div>
                            <div class="role-icon bg-secondary text-white" style="width: 50px; height: 50px; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                                <i class="fas fa-user-tie"></i>
                            </div>
                        </div>
                        <p class="mb-3">Limited access to manage services, products, and view analytics.</p>
                        
                        <div class="role-permissions">
                            <strong>Permissions:</strong>
                            <div class="mt-2 d-flex flex-wrap gap-1">
                                <span class="badge bg-light text-dark">View Dashboard</span>
                                <span class="badge bg-light text-dark">Manage Services</span>
                                <span class="badge bg-light text-dark">Manage Products</span>
                                <span class="badge bg-light text-dark">View Analytics</span>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <div class="d-flex justify-between">
                            <button class="btn btn-sm btn-light">View Users</button>
                            <button class="btn btn-sm btn-secondary">Edit Role</button>
                        </div>
                    </div>
                </div>
                
                <!-- Customer Role -->
                <div class="card">
                    <div class="card-header bg-accent text-white">
                        <div class="d-flex justify-between align-center">
                            <div class="d-flex align-center gap-2">
                                <i class="fas fa-user"></i>
                                <h5 class="card-title mb-0">Customer</h5>
                            </div>
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
                                <div class="mb-2"><strong>Users:</strong> 28</div>
                                <div><strong>Status:</strong> <span class="badge badge-success">Active</span></div>
                            </div>
                            <div class="role-icon bg-accent text-white" style="width: 50px; height: 50px; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                                <i class="fas fa-user"></i>
                            </div>
                        </div>
                        <p class="mb-3">Basic access to view dashboard and account information.</p>
                        
                        <div class="role-permissions">
                            <strong>Permissions:</strong>
                            <div class="mt-2 d-flex flex-wrap gap-1">
                                <span class="badge bg-light text-dark">View Dashboard</span>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <div class="d-flex justify-between">
                            <button class="btn btn-sm btn-light">View Users</button>
                            <button class="btn btn-sm btn-secondary">Edit Role</button>
                        </div>
                    </div>
                </div>
                
                <!-- Partner Role -->
                <div class="card">
                    <div class="card-header bg-success text-white">
                        <div class="d-flex justify-between align-center">
                            <div class="d-flex align-center gap-2">
                                <i class="fas fa-handshake"></i>
                                <h5 class="card-title mb-0">Partner</h5>
                            </div>
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
                                <div class="mb-2"><strong>Users:</strong> 4</div>
                                <div><strong>Status:</strong> <span class="badge badge-success">Active</span></div>
                            </div>
                            <div class="role-icon bg-success text-white" style="width: 50px; height: 50px; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                                <i class="fas fa-handshake"></i>
                            </div>
                        </div>
                        <p class="mb-3">Special access for business partners and collaborators.</p>
                        
                        <div class="role-permissions">
                            <strong>Permissions:</strong>
                            <div class="mt-2 d-flex flex-wrap gap-1">
                                <span class="badge bg-light text-dark">View Dashboard</span>
                                <span class="badge bg-light text-dark">View Services</span>
                                <span class="badge bg-light text-dark">View Products</span>
                                <span class="badge bg-light text-dark">View Research</span>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <div class="d-flex justify-between">
                            <button class="btn btn-sm btn-light">View Users</button>
                            <button class="btn btn-sm btn-secondary">Edit Role</button>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Permission Matrix -->
            <div class="card mt-4">
                <div class="card-header">
                    <h5 class="card-title">Role Permissions Matrix</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="admin-table">
                            <thead>
                                <tr>
                                    <th>Permission</th>
                                    <th>Administrator</th>
                                    <th>Staff</th>
                                    <th>Customer</th>
                                    <th>Partner</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>View Dashboard</td>
                                    <td><i class="fas fa-check-circle text-success"></i></td>
                                    <td><i class="fas fa-check-circle text-success"></i></td>
                                    <td><i class="fas fa-check-circle text-success"></i></td>
                                    <td><i class="fas fa-check-circle text-success"></i></td>
                                </tr>
                                <tr>
                                    <td>Manage Users</td>
                                    <td><i class="fas fa-check-circle text-success"></i></td>
                                    <td><i class="fas fa-times-circle text-danger"></i></td>
                                    <td><i class="fas fa-times-circle text-danger"></i></td>
                                    <td><i class="fas fa-times-circle text-danger"></i></td>
                                </tr>
                                <tr>
                                    <td>Manage Services</td>
                                    <td><i class="fas fa-check-circle text-success"></i></td>
                                    <td><i class="fas fa-check-circle text-success"></i></td>
                                    <td><i class="fas fa-times-circle text-danger"></i></td>
                                    <td><i class="fas fa-times-circle text-danger"></i></td>
                                </tr>
                                <tr>
                                    <td>Manage Products</td>
                                    <td><i class="fas fa-check-circle text-success"></i></td>
                                    <td><i class="fas fa-check-circle text-success"></i></td>
                                    <td><i class="fas fa-times-circle text-danger"></i></td>
                                    <td><i class="fas fa-times-circle text-danger"></i></td>
                                </tr>
                                <tr>
                                    <td>Manage Trainings</td>
                                    <td><i class="fas fa-check-circle text-success"></i></td>
                                    <td><i class="fas fa-times-circle text-danger"></i></td>
                                    <td><i class="fas fa-times-circle text-danger"></i></td>
                                    <td><i class="fas fa-times-circle text-danger"></i></td>
                                </tr>
                                <tr>
                                    <td>Manage Research</td>
                                    <td><i class="fas fa-check-circle text-success"></i></td>
                                    <td><i class="fas fa-times-circle text-danger"></i></td>
                                    <td><i class="fas fa-times-circle text-danger"></i></td>
                                    <td><i class="fas fa-times-circle text-danger"></i></td>
                                </tr>
                                <tr>
                                    <td>View Analytics</td>
                                    <td><i class="fas fa-check-circle text-success"></i></td>
                                    <td><i class="fas fa-check-circle text-success"></i></td>
                                    <td><i class="fas fa-times-circle text-danger"></i></td>
                                    <td><i class="fas fa-times-circle text-danger"></i></td>
                                </tr>
                                <tr>
                                    <td>Manage Settings</td>
                                    <td><i class="fas fa-check-circle text-success"></i></td>
                                    <td><i class="fas fa-times-circle text-danger"></i></td>
                                    <td><i class="fas fa-times-circle text-danger"></i></td>
                                    <td><i class="fas fa-times-circle text-danger"></i></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </main>
    </div>
    
    <!-- Add Role Modal -->
    <div id="roleModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add New Role</h5>
                <button class="close-modal">&times;</button>
            </div>
            <div class="modal-body">
                <form id="addRoleForm">
                    <div class="form-group">
                        <label for="roleName" class="form-label">Role Name*</label>
                        <input type="text" id="roleName" name="roleName" class="form-control" placeholder="Enter role name" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="roleDescription" class="form-label">Description</label>
                        <textarea id="roleDescription" name="roleDescription" class="form-control" rows="3" placeholder="Enter role description"></textarea>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Role Icon</label>
                        <div class="d-flex align-center gap-2 mb-2">
                            <div class="role-icon-preview" style="width: 40px; height: 40px; border-radius: 50%; background-color: var(--primary-color); color: white; display: flex; align-items: center; justify-content: center;">
                                <i id="roleIconPreview" class="fas fa-user"></i>
                            </div>
                            <select id="roleIcon" name="roleIcon" class="form-select">
                                <option value="fa-user">User</option>
                                <option value="fa-user-shield">Admin</option>
                                <option value="fa-user-tie">Staff</option>
                                <option value="fa-handshake">Partner</option>
                                <option value="fa-user-graduate">Trainer</option>
                                <option value="fa-user-cog">Manager</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="roleColor" class="form-label">Role Color</label>
                        <input type="color" id="roleColor" name="roleColor" class="form-control" value="#1a5276">
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Role Permissions</label>
                        <div class="role-permissions-checkboxes mt-2">
                            <div class="d-flex flex-column gap-2">
                                <div>
                                    <label class="d-flex align-center gap-2">
                                        <input type="checkbox" name="permissions[]" value="view_dashboard">
                                        <span>View Dashboard</span>
                                    </label>
                                </div>
                                <div>
                                    <label class="d-flex align-center gap-2">
                                        <input type="checkbox" name="permissions[]" value="manage_users">
                                        <span>Manage Users</span>
                                    </label>
                                </div>
                                <div>
                                    <label class="d-flex align-center gap-2">
                                        <input type="checkbox" name="permissions[]" value="manage_services">
                                        <span>Manage Services</span>
                                    </label>
                                </div>
                                <div>
                                    <label class="d-flex align-center gap-2">
                                        <input type="checkbox" name="permissions[]" value="manage_products">
                                        <span>Manage Products</span>
                                    </label>
                                </div>
                                <div>
                                    <label class="d-flex align-center gap-2">
                                        <input type="checkbox" name="permissions[]" value="manage_trainings">
                                        <span>Manage Trainings</span>
                                    </label>
                                </div>
                                <div>
                                    <label class="d-flex align-center gap-2">
                                        <input type="checkbox" name="permissions[]" value="manage_research">
                                        <span>Manage Research</span>
                                    </label>
                                </div>
                                <div>
                                    <label class="d-flex align-center gap-2">
                                        <input type="checkbox" name="permissions[]" value="view_analytics">
                                        <span>View Analytics</span>
                                    </label>
                                </div>
                                <div>
                                    <label class="d-flex align-center gap-2">
                                        <input type="checkbox" name="permissions[]" value="manage_settings">
                                        <span>Manage Settings</span>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="roleStatus" class="form-label">Status</label>
                        <select id="roleStatus" name="roleStatus" class="form-select">
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light close-modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="saveRole">Save Role</button>
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
            const roleModal = document.getElementById('roleModal');
            const addRoleBtn = document.getElementById('addRoleBtn');
            const closeButtons = document.querySelectorAll('.close-modal');
            
            addRoleBtn.addEventListener('click', function() {
                roleModal.style.display = 'block';
                document.body.style.overflow = 'hidden'; // Prevent scrolling
            });
            
            closeButtons.forEach(button => {
                button.addEventListener('click', function() {
                    roleModal.style.display = 'none';
                    document.body.style.overflow = 'auto'; // Allow scrolling
                });
            });
            
            // Close modal when clicking outside
            window.addEventListener('click', function(e) {
                if (e.target === roleModal) {
                    roleModal.style.display = 'none';
                    document.body.style.overflow = 'auto'; // Allow scrolling
                }
            });
            
            // Role icon preview
            const roleIcon = document.getElementById('roleIcon');
            const roleIconPreview = document.getElementById('roleIconPreview');
            
            roleIcon.addEventListener('change', function() {
                roleIconPreview.className = 'fas ' + this.value;
            });
            
            // Role color preview
            const roleColor = document.getElementById('roleColor');
            const iconPreviewContainer = roleIconPreview.parentElement;
            
            roleColor.addEventListener('input', function() {
                iconPreviewContainer.style.backgroundColor = this.value;
            });
            
            // Save role button
            const saveRole = document.getElementById('saveRole');
            
            saveRole.addEventListener('click', function() {
                const roleForm = document.getElementById('addRoleForm');
                
                // Validate form
                if (!validateForm(roleForm)) {
                    showNotification('Please fill in all required fields.', 'error');
                    return;
                }
                
                // Show loading state
                saveRole.disabled = true;
                saveRole.innerphp = '<i class="fas fa-spinner fa-spin"></i> Processing...';
                
                // Simulate form submission (in a real app, this would be an AJAX call)
                setTimeout(function() {
                    // Reset form
                    roleForm.reset();
                    
                    // Close modal
                    roleModal.style.display = 'none';
                    document.body.style.overflow = 'auto'; // Allow scrolling
                    
                    // Show success notification
                    showNotification('Role created successfully!', 'success');
                    
                    // Reset button state
                    saveRole.disabled = false;
                    saveRole.innerphp = 'Save Role';
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
        });
    </script>
</body>
</php> 