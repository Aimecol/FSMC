<!DOCTYPE php>
<php lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Service | Fair Surveying & Mapping Ltd</title>
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
                <h1 class="mt-0 mb-0">Add New Service</h1>
                <a href="all-services.php" class="btn btn-light">
                    <i class="fas fa-arrow-left"></i> Back to Services
                </a>
            </div>
            
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Service Information</h5>
                </div>
                <div class="card-body">
                    <form id="addServiceForm">
                        <div class="row">
                            <div class="col-lg-8">
                                <div class="form-group">
                                    <label for="serviceName" class="form-label">Service Name*</label>
                                    <input type="text" id="serviceName" name="serviceName" class="form-control" placeholder="Enter service name" required>
                                </div>
                                
                                <div class="form-group">
                                    <label for="serviceCategory" class="form-label">Category*</label>
                                    <select id="serviceCategory" name="serviceCategory" class="form-select" required>
                                        <option value="">Select a category</option>
                                        <option value="surveying">Surveying</option>
                                        <option value="engineering">Engineering</option>
                                        <option value="technology">Technology</option>
                                        <option value="consulting">Consulting</option>
                                        <option value="training">Training</option>
                                    </select>
                                </div>
                                
                                <div class="form-group">
                                    <label for="serviceShortDesc" class="form-label">Short Description*</label>
                                    <textarea id="serviceShortDesc" name="serviceShortDesc" class="form-control" rows="3" placeholder="Brief description (150 characters max)" required></textarea>
                                    <small class="text-gray">This will appear in service cards and listings</small>
                                </div>
                                
                                <div class="form-group">
                                    <label for="serviceFullDesc" class="form-label">Full Description*</label>
                                    <textarea id="serviceFullDesc" name="serviceFullDesc" class="form-control form-textarea" rows="6" placeholder="Detailed description of the service" required></textarea>
                                </div>
                                
                                <div class="form-group">
                                    <label for="serviceFeatures" class="form-label">Key Features</label>
                                    <textarea id="serviceFeatures" name="serviceFeatures" class="form-control" rows="4" placeholder="Enter key features (one per line)"></textarea>
                                    <small class="text-gray">Each line will be displayed as a separate feature point</small>
                                </div>
                                
                                <div class="form-group">
                                    <label class="form-label">Service Options</label>
                                    <div class="d-flex gap-4 mt-2">
                                        <div>
                                            <label class="d-flex align-center gap-2">
                                                <input type="checkbox" name="isFeatured" id="isFeatured">
                                                <span>Featured Service</span>
                                            </label>
                                        </div>
                                        <div>
                                            <label class="d-flex align-center gap-2">
                                                <input type="checkbox" name="isPopular" id="isPopular">
                                                <span>Popular Service</span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-lg-4">
                                <div class="card mb-4">
                                    <div class="card-header">
                                        <h6 class="card-title mb-0">Service Image</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="service-image-upload text-center p-3" style="border: 2px dashed #ccc; border-radius: 8px; min-height: 200px; display: flex; flex-direction: column; align-items: center; justify-content: center;">
                                            <i class="fas fa-cloud-upload-alt mb-2" style="font-size: 3rem; color: #95a5a6;"></i>
                                            <p class="mb-2">Drag & drop your image here</p>
                                            <p class="mb-3 text-gray" style="font-size: 0.85rem;">Supports: JPG, PNG, WebP (Max 2MB)</p>
                                            <input type="file" id="serviceImage" name="serviceImage" accept="image/*" style="display: none;">
                                            <button type="button" id="browseBtn" class="btn btn-secondary">Browse Files</button>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="card mb-4">
                                    <div class="card-header">
                                        <h6 class="card-title mb-0">Service Status</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="form-group mb-0">
                                            <select id="serviceStatus" name="serviceStatus" class="form-select">
                                                <option value="active">Active</option>
                                                <option value="pending">Pending</option>
                                                <option value="inactive">Inactive</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="card">
                                    <div class="card-header">
                                        <h6 class="card-title mb-0">Icon Selection</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="form-group mb-0">
                                            <select id="serviceIcon" name="serviceIcon" class="form-select">
                                                <option value="fa-map">Map</option>
                                                <option value="fa-drafting-compass">Drafting Compass</option>
                                                <option value="fa-road">Road</option>
                                                <option value="fa-satellite">Satellite</option>
                                                <option value="fa-drone">Drone</option>
                                                <option value="fa-mountain">Mountain</option>
                                                <option value="fa-building">Building</option>
                                                <option value="fa-laptop">Laptop</option>
                                                <option value="fa-chart-bar">Chart</option>
                                                <option value="fa-ruler-combined">Ruler</option>
                                            </select>
                                            
                                            <div class="icon-preview mt-3 text-center">
                                                <div style="width: 60px; height: 60px; background-color: rgba(46, 134, 193, 0.1); border-radius: 50%; display: inline-flex; align-items: center; justify-content: center;">
                                                    <i id="iconPreview" class="fas fa-map" style="font-size: 1.75rem; color: var(--secondary-color);"></i>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="serviceSEO" class="form-label">SEO Meta Description</label>
                            <textarea id="serviceSEO" name="serviceSEO" class="form-control" rows="2" placeholder="SEO meta description (optional)"></textarea>
                            <small class="text-gray">Used for search engine optimization</small>
                        </div>
                        
                        <div class="form-group">
                            <label for="serviceTags" class="form-label">Tags</label>
                            <input type="text" id="serviceTags" name="serviceTags" class="form-control" placeholder="Enter tags separated by commas">
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
                                    <i class="fas fa-plus"></i> Create Service
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
            // File upload handling
            const browseBtn = document.getElementById('browseBtn');
            const serviceImage = document.getElementById('serviceImage');
            
            browseBtn.addEventListener('click', function() {
                serviceImage.click();
            });
            
            serviceImage.addEventListener('change', function() {
                if (this.files && this.files[0]) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        const uploadArea = document.querySelector('.service-image-upload');
                        uploadArea.innerphp = `
                            <img src="${e.target.result}" alt="Service Image" style="max-width: 100%; max-height: 200px; border-radius: 4px;">
                            <button type="button" id="removeImageBtn" class="btn btn-sm btn-danger mt-2">
                                <i class="fas fa-trash"></i> Remove
                            </button>
                        `;
                        
                        // Add event listener to the remove button
                        document.getElementById('removeImageBtn').addEventListener('click', function() {
                            serviceImage.value = '';
                            uploadArea.innerphp = `
                                <i class="fas fa-cloud-upload-alt mb-2" style="font-size: 3rem; color: #95a5a6;"></i>
                                <p class="mb-2">Drag & drop your image here</p>
                                <p class="mb-3 text-gray" style="font-size: 0.85rem;">Supports: JPG, PNG, WebP (Max 2MB)</p>
                                <input type="file" id="serviceImage" name="serviceImage" accept="image/*" style="display: none;">
                                <button type="button" id="browseBtn" class="btn btn-secondary">Browse Files</button>
                            `;
                            
                            // Re-add event listener to the browse button
                            document.getElementById('browseBtn').addEventListener('click', function() {
                                serviceImage.click();
                            });
                        });
                    };
                    reader.readAsDataURL(this.files[0]);
                }
            });
            
            // Icon preview
            const serviceIcon = document.getElementById('serviceIcon');
            const iconPreview = document.getElementById('iconPreview');
            
            serviceIcon.addEventListener('change', function() {
                iconPreview.className = 'fas ' + this.value;
            });
            
            // Form submission
            const addServiceForm = document.getElementById('addServiceForm');
            
            addServiceForm.addEventListener('submit', function(e) {
                e.preventDefault();
                
                // Validate form
                if (!validateForm('addServiceForm')) {
                    showNotification('Please fill in all required fields.', 'error');
                    return;
                }
                
                // Show loading state
                const submitBtn = addServiceForm.querySelector('[type="submit"]');
                const originalBtnText = submitBtn.innerphp;
                submitBtn.disabled = true;
                submitBtn.innerphp = '<i class="fas fa-spinner fa-spin"></i> Processing...';
                
                // Simulate form submission (in a real app, this would be an AJAX call)
                setTimeout(function() {
                    // Reset form
                    addServiceForm.reset();
                    
                    // Reset file upload area
                    const uploadArea = document.querySelector('.service-image-upload');
                    uploadArea.innerphp = `
                        <i class="fas fa-cloud-upload-alt mb-2" style="font-size: 3rem; color: #95a5a6;"></i>
                        <p class="mb-2">Drag & drop your image here</p>
                        <p class="mb-3 text-gray" style="font-size: 0.85rem;">Supports: JPG, PNG, WebP (Max 2MB)</p>
                        <input type="file" id="serviceImage" name="serviceImage" accept="image/*" style="display: none;">
                        <button type="button" id="browseBtn" class="btn btn-secondary">Browse Files</button>
                    `;
                    
                    // Re-add event listener to the browse button
                    document.getElementById('browseBtn').addEventListener('click', function() {
                        document.getElementById('serviceImage').click();
                    });
                    
                    // Reset icon preview
                    iconPreview.className = 'fas fa-map';
                    
                    // Show success notification
                    showNotification('Service created successfully!', 'success');
                    
                    // Reset button state
                    submitBtn.disabled = false;
                    submitBtn.innerphp = originalBtnText;
                }, 1500);
            });
            
            // Reset form button
            const resetBtn = document.getElementById('resetBtn');
            resetBtn.addEventListener('click', function() {
                if (confirm('Are you sure you want to reset the form? All data will be lost.')) {
                    addServiceForm.reset();
                    iconPreview.className = 'fas fa-map';
                    
                    // Reset file upload area
                    const uploadArea = document.querySelector('.service-image-upload');
                    uploadArea.innerphp = `
                        <i class="fas fa-cloud-upload-alt mb-2" style="font-size: 3rem; color: #95a5a6;"></i>
                        <p class="mb-2">Drag & drop your image here</p>
                        <p class="mb-3 text-gray" style="font-size: 0.85rem;">Supports: JPG, PNG, WebP (Max 2MB)</p>
                        <input type="file" id="serviceImage" name="serviceImage" accept="image/*" style="display: none;">
                        <button type="button" id="browseBtn" class="btn btn-secondary">Browse Files</button>
                    `;
                    
                    // Re-add event listener to the browse button
                    document.getElementById('browseBtn').addEventListener('click', function() {
                        document.getElementById('serviceImage').click();
                    });
                }
            });
            
            // Save as draft button
            const saveAsDraft = document.getElementById('saveAsDraft');
            saveAsDraft.addEventListener('click', function() {
                showNotification('Service saved as draft.', 'success');
            });
            
            // Form validation function
            function validateForm(formId) {
                const form = document.getElementById(formId);
                if (!form) return false;
                
                const requiredFields = form.querySelectorAll('[required]');
                let isValid = true;
                
                requiredFields.forEach(field => {
                    if (!field.value.trim()) {
                        field.classList.add('is-invalid');
                        isValid = false;
                    } else {
                        field.classList.remove('is-invalid');
                    }
                });
                
                return isValid;
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
