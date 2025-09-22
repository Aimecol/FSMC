/**
 * Admin JavaScript for FSMC Admin System
 * Created: 2025-01-22
 * Description: Core admin interface functionality
 */

// Global admin object
window.Admin = {
    // Configuration
    config: {
        baseUrl: '/admin',
        ajaxTimeout: 30000,
        sessionWarningTime: 5 * 60 * 1000, // 5 minutes
        autoSaveInterval: 30000 // 30 seconds
    },
    
    // Initialize admin interface
    init: function() {
        this.initSidebar();
        this.initDropdowns();
        this.initAlerts();
        this.initModals();
        this.initForms();
        this.initTables();
        this.initTooltips();
        this.initSessionManagement();
        this.initFileUploads();
        this.initSearch();
    },
    
    // Sidebar functionality
    initSidebar: function() {
        const sidebarToggle = document.getElementById('sidebarToggle');
        const sidebar = document.getElementById('sidebar');
        const mainContent = document.getElementById('mainContent');
        
        if (sidebarToggle && sidebar && mainContent) {
            sidebarToggle.addEventListener('click', function() {
                sidebar.classList.toggle('collapsed');
                mainContent.classList.toggle('expanded');
                
                // Save state to localStorage
                localStorage.setItem('sidebarCollapsed', sidebar.classList.contains('collapsed'));
            });
            
            // Restore sidebar state
            if (localStorage.getItem('sidebarCollapsed') === 'true') {
                sidebar.classList.add('collapsed');
                mainContent.classList.add('expanded');
            }
        }
        
        // Mobile sidebar toggle
        if (window.innerWidth <= 768) {
            document.addEventListener('click', function(e) {
                if (!sidebar.contains(e.target) && !sidebarToggle.contains(e.target)) {
                    sidebar.classList.remove('show');
                }
            });
        }
    },
    
    // Dropdown functionality
    initDropdowns: function() {
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
    },
    
    // Alert functionality
    initAlerts: function() {
        // Alert dismissal
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
    },
    
    // Modal functionality
    initModals: function() {
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
        
        // Escape key to close modal
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                document.querySelectorAll('.modal.show').forEach(function(modal) {
                    modal.classList.remove('show');
                });
            }
        });
    },
    
    // Form functionality
    initForms: function() {
        // Form validation
        document.querySelectorAll('form[data-validate]').forEach(function(form) {
            form.addEventListener('submit', function(e) {
                if (!Admin.validateForm(this)) {
                    e.preventDefault();
                }
            });
        });
        
        // Auto-save functionality
        document.querySelectorAll('form[data-autosave]').forEach(function(form) {
            const inputs = form.querySelectorAll('input, textarea, select');
            inputs.forEach(function(input) {
                input.addEventListener('change', function() {
                    Admin.autoSaveForm(form);
                });
            });
        });
        
        // Delete confirmations
        document.querySelectorAll('.btn-delete').forEach(function(deleteBtn) {
            deleteBtn.addEventListener('click', function(e) {
                e.preventDefault();
                
                const url = this.href || this.dataset.url;
                const itemName = this.dataset.name || 'this item';
                
                Admin.confirmAction(
                    `Are you sure you want to delete "${itemName}"? This action cannot be undone.`,
                    function() {
                        if (url) {
                            window.location.href = url;
                        }
                    }
                );
            });
        });
    },
    
    // Table functionality
    initTables: function() {
        // Sortable tables
        document.querySelectorAll('.table-sortable th[data-sort]').forEach(function(header) {
            header.style.cursor = 'pointer';
            header.addEventListener('click', function() {
                Admin.sortTable(this);
            });
        });
        
        // Row selection
        document.querySelectorAll('.table-selectable').forEach(function(table) {
            const selectAll = table.querySelector('th input[type="checkbox"]');
            const rowCheckboxes = table.querySelectorAll('td input[type="checkbox"]');
            
            if (selectAll) {
                selectAll.addEventListener('change', function() {
                    rowCheckboxes.forEach(function(checkbox) {
                        checkbox.checked = selectAll.checked;
                    });
                });
            }
            
            rowCheckboxes.forEach(function(checkbox) {
                checkbox.addEventListener('change', function() {
                    const checkedCount = table.querySelectorAll('td input[type="checkbox"]:checked').length;
                    if (selectAll) {
                        selectAll.checked = checkedCount === rowCheckboxes.length;
                        selectAll.indeterminate = checkedCount > 0 && checkedCount < rowCheckboxes.length;
                    }
                });
            });
        });
    },
    
    // Tooltip functionality
    initTooltips: function() {
        document.querySelectorAll('[data-tooltip]').forEach(function(element) {
            element.addEventListener('mouseenter', function() {
                Admin.showTooltip(this, this.dataset.tooltip);
            });
            
            element.addEventListener('mouseleave', function() {
                Admin.hideTooltip();
            });
        });
    },
    
    // Session management
    initSessionManagement: function() {
        // Session timeout warning
        let sessionWarningShown = false;
        const sessionTimeout = 30 * 60 * 1000; // 30 minutes in milliseconds
        const warningTime = sessionTimeout - this.config.sessionWarningTime;
        
        setTimeout(function() {
            if (!sessionWarningShown) {
                sessionWarningShown = true;
                if (confirm('Your session will expire in 5 minutes. Click OK to extend your session.')) {
                    Admin.extendSession().then(function() {
                        sessionWarningShown = false;
                    });
                }
            }
        }, warningTime);
        
        // Keep session alive on activity
        let lastActivity = Date.now();
        document.addEventListener('click', function() {
            lastActivity = Date.now();
        });
        
        document.addEventListener('keypress', function() {
            lastActivity = Date.now();
        });
        
        // Check for inactivity every minute
        setInterval(function() {
            const inactiveTime = Date.now() - lastActivity;
            if (inactiveTime > 25 * 60 * 1000) { // 25 minutes
                Admin.extendSession();
            }
        }, 60000);
    },
    
    // File upload functionality
    initFileUploads: function() {
        document.querySelectorAll('.file-upload').forEach(function(upload) {
            const input = upload.querySelector('input[type="file"]');
            const preview = upload.querySelector('.file-preview');
            
            if (input) {
                input.addEventListener('change', function() {
                    Admin.handleFileUpload(this, preview);
                });
            }
        });
        
        // Drag and drop
        document.querySelectorAll('.file-drop-zone').forEach(function(zone) {
            zone.addEventListener('dragover', function(e) {
                e.preventDefault();
                this.classList.add('dragover');
            });
            
            zone.addEventListener('dragleave', function() {
                this.classList.remove('dragover');
            });
            
            zone.addEventListener('drop', function(e) {
                e.preventDefault();
                this.classList.remove('dragover');
                const files = e.dataTransfer.files;
                Admin.handleDroppedFiles(files, this);
            });
        });
    },
    
    // Search functionality
    initSearch: function() {
        document.querySelectorAll('.search-input').forEach(function(input) {
            let searchTimeout;
            
            input.addEventListener('input', function() {
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(() => {
                    Admin.performSearch(this.value, this.dataset.target);
                }, 300);
            });
        });
    },
    
    // Utility functions
    showLoading: function() {
        document.getElementById('loadingOverlay').classList.add('show');
    },
    
    hideLoading: function() {
        document.getElementById('loadingOverlay').classList.remove('show');
    },
    
    showAlert: function(message, type = 'info') {
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
    },
    
    confirmAction: function(message, callback) {
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
    },
    
    validateForm: function(form) {
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
            this.showAlert('Please fill in all required fields.', 'error');
        }
        
        return isValid;
    },
    
    autoSaveForm: function(form) {
        const formData = new FormData(form);
        formData.append('autosave', '1');
        
        fetch(form.action || window.location.href, {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        }).then(response => {
            if (response.ok) {
                console.log('Form auto-saved');
            }
        }).catch(error => {
            console.error('Auto-save failed:', error);
        });
    },
    
    extendSession: function() {
        return fetch('ajax/extend_session.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        }).then(response => response.json());
    },
    
    sortTable: function(header) {
        const table = header.closest('table');
        const tbody = table.querySelector('tbody');
        const rows = Array.from(tbody.querySelectorAll('tr'));
        const column = Array.from(header.parentNode.children).indexOf(header);
        const isAscending = header.classList.contains('sort-asc');
        
        // Remove sort classes from all headers
        table.querySelectorAll('th').forEach(th => {
            th.classList.remove('sort-asc', 'sort-desc');
        });
        
        // Add appropriate sort class
        header.classList.add(isAscending ? 'sort-desc' : 'sort-asc');
        
        // Sort rows
        rows.sort((a, b) => {
            const aText = a.children[column].textContent.trim();
            const bText = b.children[column].textContent.trim();
            
            if (isAscending) {
                return bText.localeCompare(aText, undefined, { numeric: true });
            } else {
                return aText.localeCompare(bText, undefined, { numeric: true });
            }
        });
        
        // Reorder rows in DOM
        rows.forEach(row => tbody.appendChild(row));
    },
    
    handleFileUpload: function(input, preview) {
        const files = input.files;
        if (files.length > 0) {
            const file = files[0];
            
            // Validate file
            if (!this.validateFile(file)) {
                input.value = '';
                return;
            }
            
            // Show preview for images
            if (file.type.startsWith('image/') && preview) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.innerHTML = `<img src="${e.target.result}" alt="Preview" style="max-width: 200px; max-height: 200px;">`;
                };
                reader.readAsDataURL(file);
            }
        }
    },
    
    validateFile: function(file) {
        const maxSize = 10 * 1024 * 1024; // 10MB
        const allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp', 'application/pdf', 'application/msword'];
        
        if (file.size > maxSize) {
            this.showAlert('File size must be less than 10MB.', 'error');
            return false;
        }
        
        if (!allowedTypes.includes(file.type)) {
            this.showAlert('File type not allowed.', 'error');
            return false;
        }
        
        return true;
    },
    
    performSearch: function(query, target) {
        if (query.length < 2) return;
        
        fetch(`ajax/search.php?q=${encodeURIComponent(query)}&target=${target}`, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            // Handle search results
            console.log('Search results:', data);
        })
        .catch(error => {
            console.error('Search failed:', error);
        });
    }
};

// Initialize when DOM is ready
document.addEventListener('DOMContentLoaded', function() {
    Admin.init();
});

// Export for global access
window.Admin = Admin;
