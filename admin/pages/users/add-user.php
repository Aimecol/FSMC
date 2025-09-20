<!DOCTYPE php>
<php lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New User | Fair Surveying & Mapping Ltd</title>
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
                <h1 class="mt-0 mb-0">Add New User</h1>
                <a href="all-users.php" class="btn btn-light">
                    <i class="fas fa-arrow-left"></i> Back to Users
                </a>
            </div>
            
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">User Information</h5>
                </div>
                <div class="card-body">
                    <form id="addUserForm">
                        <div class="row">
                            <div class="col-lg-8">
                                <div class="form-group">
                                    <label for="firstName" class="form-label">First Name*</label>
                                    <input type="text" id="firstName" name="firstName" class="form-control" placeholder="Enter first name" required>
                                </div>
                                
                                <div class="form-group">
                                    <label for="lastName" class="form-label">Last Name*</label>
                                    <input type="text" id="lastName" name="lastName" class="form-control" placeholder="Enter last name" required>
                                </div>
                                
                                <div class="form-group">
                                    <label for="email" class="form-label">Email Address*</label>
                                    <input type="email" id="email" name="email" class="form-control" placeholder="Enter email address" required>
                                </div>
                                
                                <div class="form-group">
                                    <label for="phone" class="form-label">Phone Number</label>
                                    <input type="tel" id="phone" name="phone" class="form-control" placeholder="Enter phone number">
                                </div>
                                
                                <div class="form-group">
                                    <label for="role" class="form-label">User Role*</label>
                                    <select id="role" name="role" class="form-select" required>
                                        <option value="">Select a role</option>
                                        <option value="admin">Administrator</option>
                                        <option value="staff">Staff</option>
                                        <option value="customer">Customer</option>
                                    </select>
                                </div>
                                
                                <div class="form-group">
                                    <label for="status" class="form-label">Status</label>
                                    <select id="status" name="status" class="form-select">
                                        <option value="active">Active</option>
                                        <option value="inactive">Inactive</option>
                                        <option value="pending">Pending</option>
                                    </select>
                                </div>
                                
                                <div class="form-group">
                                    <label for="password" class="form-label">Password*</label>
                                    <input type="password" id="password" name="password" class="form-control" placeholder="Enter password" required>
                                </div>
                                
                                <div class="form-group">
                                    <label for="confirmPassword" class="form-label">Confirm Password*</label>
                                    <input type="password" id="confirmPassword" name="confirmPassword" class="form-control" placeholder="Confirm password" required>
                                </div>
                                
                                <div class="form-group">
                                    <label class="form-label">User Permissions</label>
                                    <div class="d-flex flex-column gap-2 mt-2">
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
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-lg-4">
                                <div class="card">
                                    <div class="card-header">
                                        <h6 class="card-title">Profile Picture</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="user-profile-upload">
                                            <div class="profile-image-preview text-center mb-3">
                                                <div style="width: 150px; height: 150px; background-color: #f4f6f7; border-radius: 50%; margin: 0 auto; display: flex; align-items: center; justify-content: center;">
                                                    <i class="fas fa-user" style="font-size: 4rem; color: #95a5a6;"></i>
                                                </div>
                                            </div>
                                            
                                            <div class="text-center">
                                                <input type="file" id="profileImage" name="profileImage" accept="image/*" style="display: none;">
                                                <button type="button" id="browseProfileBtn" class="btn btn-secondary btn-sm">
                                                    <i class="fas fa-upload"></i> Upload Image
                                                </button>
                                                <p class="text-gray mt-2" style="font-size: 0.85rem;">JPG, PNG or GIF (Max 2MB)</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="card mt-3">
                                    <div class="card-header">
                                        <h6 class="card-title">Additional Options</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="form-group">
                                            <label class="d-flex align-center gap-2">
                                                <input type="checkbox" name="sendWelcomeEmail" checked>
                                                <span>Send welcome email</span>
                                            </label>
                                        </div>
                                        <div class="form-group">
                                            <label class="d-flex align-center gap-2">
                                                <input type="checkbox" name="requirePasswordChange">
                                                <span>Require password change on first login</span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-group mt-4">
                            <label for="notes" class="form-label">Notes</label>
                            <textarea id="notes" name="notes" class="form-control" rows="3" placeholder="Enter notes about this user"></textarea>
                        </div>
                        
                        <div class="d-flex justify-between mt-4">
                            <button type="button" class="btn btn-light" id="resetBtn">
                                <i class="fas fa-undo"></i> Reset Form
                            </button>
                            <div>
                                <button type="button" class="btn btn-secondary mr-2" id="saveAsDraft">
                                    <i class="fas fa-save"></i> Save as Draft
                                </button>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-user-plus"></i> Create User
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </main>
    </div>
    
    <!-- Notification Container -->
    <div class="notification-container"></div>
    
    <!-- Admin JavaScript -->
    <script src="../../js/admin.js"></script>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Profile image upload handling
            const browseProfileBtn = document.getElementById('browseProfileBtn');
            const profileImage = document.getElementById('profileImage');
            
            browseProfileBtn.addEventListener('click', function() {
                profileImage.click();
            });
            
            profileImage.addEventListener('change', function() {
                if (this.files && this.files[0]) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        const previewArea = document.querySelector('.profile-image-preview');
                        previewArea.innerphp = `
                            <div style="width: 150px; height: 150px; margin: 0 auto; border-radius: 50%; overflow: hidden;">
                                <img src="${e.target.result}" alt="Profile Image" style="width: 100%; height: 100%; object-fit: cover;">
                            </div>
                            <button type="button" id="removeImageBtn" class="btn btn-sm btn-danger mt-2">
                                <i class="fas fa-trash"></i> Remove
                            </button>
                        `;
                        
                        // Add event listener to the remove button
                        document.getElementById('removeImageBtn').addEventListener('click', function() {
                            profileImage.value = '';
                            previewArea.innerphp = `
                                <div style="width: 150px; height: 150px; background-color: #f4f6f7; border-radius: 50%; margin: 0 auto; display: flex; align-items: center; justify-content: center;">
                                    <i class="fas fa-user" style="font-size: 4rem; color: #95a5a6;"></i>
                                </div>
                            `;
                        });
                    };
                    reader.readAsDataURL(this.files[0]);
                }
            });
            
            // Form submission
            const addUserForm = document.getElementById('addUserForm');
            
            addUserForm.addEventListener('submit', function(e) {
                e.preventDefault();
                
                // Validate form
                if (!validateForm('addUserForm')) {
                    showNotification('Please fill in all required fields.', 'error');
                    return;
                }
                
                // Check if passwords match
                const password = document.getElementById('password').value;
                const confirmPassword = document.getElementById('confirmPassword').value;
                
                if (password !== confirmPassword) {
                    showNotification('Passwords do not match.', 'error');
                    document.getElementById('confirmPassword').classList.add('is-invalid');
                    return;
                }
                
                // Show loading state
                const submitBtn = addUserForm.querySelector('[type="submit"]');
                const originalBtnText = submitBtn.innerphp;
                submitBtn.disabled = true;
                submitBtn.innerphp = '<i class="fas fa-spinner fa-spin"></i> Processing...';
                
                // Simulate form submission (in a real app, this would be an AJAX call)
                setTimeout(function() {
                    // Reset form
                    addUserForm.reset();
                    
                    // Reset profile image preview
                    const previewArea = document.querySelector('.profile-image-preview');
                    previewArea.innerphp = `
                        <div style="width: 150px; height: 150px; background-color: #f4f6f7; border-radius: 50%; margin: 0 auto; display: flex; align-items: center; justify-content: center;">
                            <i class="fas fa-user" style="font-size: 4rem; color: #95a5a6;"></i>
                        </div>
                    `;
                    
                    // Show success notification
                    showNotification('User created successfully!', 'success');
                    
                    // Reset button state
                    submitBtn.disabled = false;
                    submitBtn.innerphp = originalBtnText;
                }, 1500);
            });
            
            // Reset form button
            const resetBtn = document.getElementById('resetBtn');
            resetBtn.addEventListener('click', function() {
                if (confirm('Are you sure you want to reset the form? All data will be lost.')) {
                    addUserForm.reset();
                    
                    // Reset profile image preview
                    const previewArea = document.querySelector('.profile-image-preview');
                    previewArea.innerphp = `
                        <div style="width: 150px; height: 150px; background-color: #f4f6f7; border-radius: 50%; margin: 0 auto; display: flex; align-items: center; justify-content: center;">
                            <i class="fas fa-user" style="font-size: 4rem; color: #95a5a6;"></i>
                        </div>
                    `;
                }
            });
            
            // Save as draft button
            const saveAsDraft = document.getElementById('saveAsDraft');
            saveAsDraft.addEventListener('click', function() {
                showNotification('User saved as draft.', 'success');
            });
            
            // Auto-select permissions based on role
            const roleSelect = document.getElementById('role');
            const permissionCheckboxes = document.querySelectorAll('input[name="permissions[]"]');
            
            roleSelect.addEventListener('change', function() {
                const role = this.value;
                
                // Reset all permissions
                permissionCheckboxes.forEach(checkbox => {
                    checkbox.checked = false;
                });
                
                // Set permissions based on role
                if (role === 'admin') {
                    // Admins get all permissions
                    permissionCheckboxes.forEach(checkbox => {
                        checkbox.checked = true;
                    });
                } else if (role === 'staff') {
                    // Staff get limited permissions
                    const staffPermissions = ['view_dashboard', 'manage_services', 'manage_products', 'view_analytics'];
                    
                    permissionCheckboxes.forEach(checkbox => {
                        if (staffPermissions.includes(checkbox.value)) {
                            checkbox.checked = true;
                        }
                    });
                } else if (role === 'customer') {
                    // Customers get minimal permissions
                    const customerPermissions = ['view_dashboard'];
                    
                    permissionCheckboxes.forEach(checkbox => {
                        if (customerPermissions.includes(checkbox.value)) {
                            checkbox.checked = true;
                        }
                    });
                }
            });
        });
    </script>
</body>
</php> 