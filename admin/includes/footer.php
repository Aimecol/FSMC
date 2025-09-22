        </div> <!-- End page-content -->
        </div> <!-- End content-wrapper -->
    </main> <!-- End main-content -->

    <!-- Loading Overlay -->
    <div class="loading-overlay" id="loadingOverlay">
        <div class="loading-spinner">
            <i class="fas fa-spinner fa-spin"></i>
            <p>Loading...</p>
        </div>
    </div>

    <!-- Confirmation Modal -->
    <div class="modal" id="confirmModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Confirm Action</h5>
                    <button type="button" class="modal-close" data-dismiss="modal">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <p id="confirmMessage">Are you sure you want to perform this action?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-danger" id="confirmAction">Confirm</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="assets/js/admin.js"></script>
    <?php if (isset($additionalJS)): ?>
        <?php foreach ($additionalJS as $js): ?>
            <script src="<?php echo $js; ?>"></script>
        <?php endforeach; ?>
    <?php endif; ?>
    
    <script>
        // Initialize admin interface
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize sidebar toggle
            const sidebarToggle = document.getElementById('sidebarToggle');
            const sidebar = document.getElementById('sidebar');
            const mainContent = document.getElementById('mainContent');
            
            if (sidebarToggle) {
                sidebarToggle.addEventListener('click', function() {
                    sidebar.classList.toggle('collapsed');
                    mainContent.classList.toggle('expanded');
                    
                    // Save state to localStorage
                    localStorage.setItem('sidebarCollapsed', sidebar.classList.contains('collapsed'));
                });
            }
            
            // Restore sidebar state
            if (localStorage.getItem('sidebarCollapsed') === 'true') {
                sidebar.classList.add('collapsed');
                mainContent.classList.add('expanded');
            }
            
            // Initialize dropdowns
            document.querySelectorAll('[data-toggle="dropdown"]').forEach(function(trigger) {
                trigger.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    
                    const dropdown = this.closest('.dropdown');
                    const menu = dropdown.querySelector('.dropdown-menu');
                    
                    // Close other dropdowns
                    document.querySelectorAll('.dropdown-menu.show').forEach(function(otherMenu) {
                        if (otherMenu !== menu) {
                            otherMenu.classList.remove('show');
                        }
                    });
                    
                    // Toggle current dropdown
                    menu.classList.toggle('show');
                });
            });
            
            // Close dropdowns when clicking outside
            document.addEventListener('click', function() {
                document.querySelectorAll('.dropdown-menu.show').forEach(function(menu) {
                    menu.classList.remove('show');
                });
            });
            
            // Initialize alert dismissal
            document.querySelectorAll('.alert-close').forEach(function(closeBtn) {
                closeBtn.addEventListener('click', function() {
                    const alert = this.closest('.alert');
                    alert.style.opacity = '0';
                    setTimeout(() => alert.remove(), 300);
                });
            });
            
            // Auto-dismiss alerts after 5 seconds
            document.querySelectorAll('.alert-dismissible').forEach(function(alert) {
                setTimeout(() => {
                    if (alert.parentNode) {
                        alert.style.opacity = '0';
                        setTimeout(() => alert.remove(), 300);
                    }
                }, 5000);
            });
            
            // Initialize confirmation modal
            window.confirmAction = function(message, callback) {
                const modal = document.getElementById('confirmModal');
                const messageEl = document.getElementById('confirmMessage');
                const confirmBtn = document.getElementById('confirmAction');
                
                messageEl.textContent = message;
                modal.classList.add('show');
                
                // Remove previous event listeners
                const newConfirmBtn = confirmBtn.cloneNode(true);
                confirmBtn.parentNode.replaceChild(newConfirmBtn, confirmBtn);
                
                // Add new event listener
                newConfirmBtn.addEventListener('click', function() {
                    modal.classList.remove('show');
                    if (callback) callback();
                });
            };
            
            // Close modal functionality
            document.querySelectorAll('[data-dismiss="modal"]').forEach(function(closeBtn) {
                closeBtn.addEventListener('click', function() {
                    const modal = this.closest('.modal');
                    modal.classList.remove('show');
                });
            });
            
            // Close modal when clicking backdrop
            document.querySelectorAll('.modal').forEach(function(modal) {
                modal.addEventListener('click', function(e) {
                    if (e.target === this) {
                        this.classList.remove('show');
                    }
                });
            });
            
            // Initialize delete confirmations
            document.querySelectorAll('.btn-delete').forEach(function(deleteBtn) {
                deleteBtn.addEventListener('click', function(e) {
                    e.preventDefault();
                    
                    const url = this.href || this.dataset.url;
                    const itemName = this.dataset.name || 'this item';
                    
                    confirmAction(
                        `Are you sure you want to delete "${itemName}"? This action cannot be undone.`,
                        function() {
                            if (url) {
                                window.location.href = url;
                            }
                        }
                    );
                });
            });
            
            // Initialize form validation
            document.querySelectorAll('form[data-validate]').forEach(function(form) {
                form.addEventListener('submit', function(e) {
                    const requiredFields = form.querySelectorAll('[required]');
                    let isValid = true;
                    
                    requiredFields.forEach(function(field) {
                        if (!field.value.trim()) {
                            field.classList.add('is-invalid');
                            isValid = false;
                        } else {
                            field.classList.remove('is-invalid');
                        }
                    });
                    
                    if (!isValid) {
                        e.preventDefault();
                        alert('Please fill in all required fields.');
                    }
                });
            });
            
            // Initialize tooltips (if needed)
            document.querySelectorAll('[data-tooltip]').forEach(function(element) {
                element.addEventListener('mouseenter', function() {
                    // Tooltip implementation can be added here
                });
            });
            
            // Session timeout warning
            let sessionWarningShown = false;
            const sessionTimeout = <?php echo SESSION_TIMEOUT; ?> * 1000; // Convert to milliseconds
            const warningTime = sessionTimeout - (5 * 60 * 1000); // 5 minutes before timeout
            
            setTimeout(function() {
                if (!sessionWarningShown) {
                    sessionWarningShown = true;
                    if (confirm('Your session will expire in 5 minutes. Click OK to extend your session.')) {
                        // Make a request to extend session
                        fetch('ajax/extend_session.php', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-Requested-With': 'XMLHttpRequest'
                            }
                        }).then(function() {
                            sessionWarningShown = false;
                        });
                    }
                }
            }, warningTime);
        });
        
        // Global utility functions
        window.showLoading = function() {
            document.getElementById('loadingOverlay').classList.add('show');
        };
        
        window.hideLoading = function() {
            document.getElementById('loadingOverlay').classList.remove('show');
        };
        
        window.showAlert = function(message, type = 'info') {
            const alertContainer = document.querySelector('.page-content');
            const alert = document.createElement('div');
            alert.className = `alert alert-${type} alert-dismissible`;
            alert.innerHTML = `
                <i class="fas fa-${type === 'success' ? 'check-circle' : type === 'error' ? 'exclamation-triangle' : 'info-circle'}"></i>
                ${message}
                <button type="button" class="alert-close" onclick="this.closest('.alert').remove()">
                    <i class="fas fa-times"></i>
                </button>
            `;
            
            alertContainer.insertBefore(alert, alertContainer.firstChild);
            
            // Auto-dismiss after 5 seconds
            setTimeout(() => {
                if (alert.parentNode) {
                    alert.style.opacity = '0';
                    setTimeout(() => alert.remove(), 300);
                }
            }, 5000);
        };
    </script>
</body>
</html>
