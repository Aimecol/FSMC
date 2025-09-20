<!DOCTYPE php>
<php lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sent Messages | Fair Surveying & Mapping Ltd</title>
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
                    <h1 class="page-title">Sent Messages</h1>
                    <p class="page-subtitle">Manage all outgoing messages and communications.</p>
                </div>
                <div>
                    <button class="btn btn-primary" data-toggle="modal" data-target="#composeMessageModal">
                        <i class="fas fa-paper-plane"></i> Compose Message
                    </button>
                </div>
            </div>
            
            <!-- Filters -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title">Filter Messages</h5>
                </div>
                <div class="card-body">
                    <div class="d-flex flex-wrap gap-3 justify-between">
                        <div class="d-flex gap-2 flex-wrap">
                            <div class="dropdown">
                                <button class="btn btn-light dropdown-toggle">
                                    Type <i class="fas fa-chevron-down"></i>
                                </button>
                                <div class="dropdown-menu">
                                    <a href="#" class="dropdown-item">All</a>
                                    <a href="#" class="dropdown-item">Email</a>
                                    <a href="#" class="dropdown-item">Phone</a>
                                    <a href="#" class="dropdown-item">Social Media</a>
                                </div>
                            </div>
                            <div class="dropdown">
                                <button class="btn btn-light dropdown-toggle">
                                    Date <i class="fas fa-chevron-down"></i>
                                </button>
                                <div class="dropdown-menu">
                                    <a href="#" class="dropdown-item">All</a>
                                    <a href="#" class="dropdown-item">Today</a>
                                    <a href="#" class="dropdown-item">This Week</a>
                                    <a href="#" class="dropdown-item">This Month</a>
                                    <a href="#" class="dropdown-item">This Year</a>
                                    <a href="#" class="dropdown-item">Earlier</a>
                                </div>
                            </div>
                            <div class="dropdown">
                                <button class="btn btn-light dropdown-toggle">
                                    Recipient <i class="fas fa-chevron-down"></i>
                                </button>
                                <div class="dropdown-menu">
                                    <a href="#" class="dropdown-item">All</a>
                                    <a href="#" class="dropdown-item">Dr. John Smith</a>
                                    <a href="#" class="dropdown-item">Dr. Jane Doe</a>
                                    <a href="#" class="dropdown-item">Dr. David Miller</a>
                                    <a href="#" class="dropdown-item">Dr. Sarah Green</a>
                                    <a href="#" class="dropdown-item">Dr. Lisa Taylor</a>
                                </div>
                            </div>
                        </div>
                        <div>
                            <button class="btn btn-secondary">
                                <i class="fas fa-sync-alt"></i> Reset Filters
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Messages Table -->
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Sent Messages</h5>
                    <div class="card-tools">
                        <button class="btn btn-light">
                            <i class="fas fa-download"></i> Export
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="sentMessagesTable" class="table table-hover">
                            <thead>
                                <tr>
                                    <th>
                                        <input type="checkbox" class="form-check-input">
                                    </th>
                                    <th>Title</th>
                                    <th>Recipient</th>
                                    <th>Date</th>
                                    <th>Type</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>
                                        <input type="checkbox" class="form-check-input">
                                    </td>
                                    <td>
                                        <div class="fw-bold">Training Program Update</div>
                                        <div class="text-gray" style="font-size: 0.85rem">Quick update about the new GIS training modules...</div>
                                    </td>
                                    <td>Dr. John Smith</td>
                                    <td>Jun 10, 2023</td>
                                    <td><span class="badge bg-info">Email</span></td>
                                    <td><span class="badge bg-success">Delivered</span></td>
                                    <td>
                                        <div class="d-flex gap-1">
                                            <button class="btn btn-sm btn-light" title="View">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <button class="btn btn-sm btn-secondary" title="Forward">
                                                <i class="fas fa-share"></i>
                                            </button>
                                            <button class="btn btn-sm btn-danger" title="Delete">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <input type="checkbox" class="form-check-input">
                                    </td>
                                    <td>
                                        <div class="fw-bold">Research Collaboration Proposal</div>
                                        <div class="text-gray" style="font-size: 0.85rem">Regarding the upcoming joint research project on urban mapping...</div>
                                    </td>
                                    <td>Dr. Jane Doe</td>
                                    <td>May 28, 2023</td>
                                    <td><span class="badge bg-info">Email</span></td>
                                    <td><span class="badge bg-success">Delivered</span></td>
                                    <td>
                                        <div class="d-flex gap-1">
                                            <button class="btn btn-sm btn-light" title="View">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <button class="btn btn-sm btn-secondary" title="Forward">
                                                <i class="fas fa-share"></i>
                                            </button>
                                            <button class="btn btn-sm btn-danger" title="Delete">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <input type="checkbox" class="form-check-input">
                                    </td>
                                    <td>
                                        <div class="fw-bold">Equipment Acquisition</div>
                                        <div class="text-gray" style="font-size: 0.85rem">Follow-up on the new surveying equipment order...</div>
                                    </td>
                                    <td>Dr. David Miller</td>
                                    <td>May 15, 2023</td>
                                    <td><span class="badge bg-primary">Phone</span></td>
                                    <td><span class="badge bg-success">Completed</span></td>
                                    <td>
                                        <div class="d-flex gap-1">
                                            <button class="btn btn-sm btn-light" title="View">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <button class="btn btn-sm btn-secondary" title="Forward">
                                                <i class="fas fa-share"></i>
                                            </button>
                                            <button class="btn btn-sm btn-danger" title="Delete">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <input type="checkbox" class="form-check-input">
                                    </td>
                                    <td>
                                        <div class="fw-bold">Project Status Report</div>
                                        <div class="text-gray" style="font-size: 0.85rem">Monthly status report for the GIS mapping project...</div>
                                    </td>
                                    <td>Dr. Sarah Green</td>
                                    <td>Apr 30, 2023</td>
                                    <td><span class="badge bg-info">Email</span></td>
                                    <td><span class="badge bg-warning">Pending</span></td>
                                    <td>
                                        <div class="d-flex gap-1">
                                            <button class="btn btn-sm btn-light" title="View">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <button class="btn btn-sm btn-secondary" title="Forward">
                                                <i class="fas fa-share"></i>
                                            </button>
                                            <button class="btn btn-sm btn-danger" title="Delete">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <input type="checkbox" class="form-check-input">
                                    </td>
                                    <td>
                                        <div class="fw-bold">Conference Invitation</div>
                                        <div class="text-gray" style="font-size: 0.85rem">Invitation to speak at the Annual Geospatial Conference...</div>
                                    </td>
                                    <td>Dr. Lisa Taylor</td>
                                    <td>Apr 18, 2023</td>
                                    <td><span class="badge bg-success">SMS</span></td>
                                    <td><span class="badge bg-success">Delivered</span></td>
                                    <td>
                                        <div class="d-flex gap-1">
                                            <button class="btn btn-sm btn-light" title="View">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <button class="btn btn-sm btn-secondary" title="Forward">
                                                <i class="fas fa-share"></i>
                                            </button>
                                            <button class="btn btn-sm btn-danger" title="Delete">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            
            <!-- Bulk Actions -->
            <div class="card mt-4">
                <div class="card-header">
                    <h5 class="card-title">Bulk Actions</h5>
                </div>
                <div class="card-body">
                    <div class="d-flex gap-2">
                        <select class="form-select" style="max-width: 200px;">
                            <option value="">Select Action</option>
                            <option value="forward">Forward</option>
                            <option value="archive">Archive</option>
                            <option value="delete">Delete</option>
                            <option value="export">Export</option>
                        </select>
                        <button class="btn btn-secondary">Apply</button>
                    </div>
                </div>
            </div>
        </main>
    </div>
    
    <!-- Compose Message Modal -->
    <div class="modal" id="composeMessageModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Compose Message</h5>
                    <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="composeMessageForm">
                        <div class="mb-3">
                            <label for="recipient" class="form-label">Recipient</label>
                            <select class="form-select" id="recipient" required>
                                <option value="">Select Recipient</option>
                                <option value="Dr. John Smith">Dr. John Smith</option>
                                <option value="Dr. Jane Doe">Dr. Jane Doe</option>
                                <option value="Dr. David Miller">Dr. David Miller</option>
                                <option value="Dr. Sarah Green">Dr. Sarah Green</option>
                                <option value="Dr. Lisa Taylor">Dr. Lisa Taylor</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="msgTitle" class="form-label">Message Title</label>
                            <input type="text" class="form-control" id="msgTitle" required>
                        </div>
                        <div class="mb-3">
                            <label for="msgType" class="form-label">Type</label>
                            <select class="form-select" id="msgType" required>
                                <option value="">Select Type</option>
                                <option value="Email">Email</option>
                                <option value="SMS">SMS</option>
                                <option value="Phone">Phone</option>
                                <option value="Social Media">Social Media</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="msgContent" class="form-label">Content</label>
                            <textarea class="form-control" id="msgContent" rows="4" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="attachments" class="form-label">Attachments</label>
                            <input type="file" class="form-control" id="attachments" multiple>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" id="sendMessage">Send Message</button>
                </div>
            </div>
        </div>
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
            $('#sentMessagesTable').DataTable({
                responsive: true,
                pageLength: 10,
                lengthMenu: [5, 10, 25, 50],
                language: {
                    search: "",
                    searchPlaceholder: "Search messages..."
                }
            });
            
            // Modal functionality
            const composeMessageModal = document.getElementById('composeMessageModal');
            const closeButtons = document.querySelectorAll('[data-dismiss="modal"]');
            const sendMessage = document.getElementById('sendMessage');
            const composeButton = document.querySelector('[data-target="#composeMessageModal"]');
            
            if (composeButton) {
                composeButton.addEventListener('click', function() {
                    composeMessageModal.style.display = 'block';
                    document.body.style.overflow = 'hidden'; // Prevent scrolling
                });
            }
            
            closeButtons.forEach(button => {
                button.addEventListener('click', function() {
                    composeMessageModal.style.display = 'none';
                    document.body.style.overflow = 'auto'; // Allow scrolling
                });
            });
            
            // Close modal when clicking outside
            window.addEventListener('click', function(event) {
                if (event.target === composeMessageModal) {
                    composeMessageModal.style.display = 'none';
                    document.body.style.overflow = 'auto'; // Allow scrolling
                }
            });
            
            // Send message
            if (sendMessage) {
                sendMessage.addEventListener('click', function() {
                    const form = document.getElementById('composeMessageForm');
                    const formElements = form.querySelectorAll('input[required], select[required], textarea[required]');
                    
                    let valid = true;
                    formElements.forEach(element => {
                        if (!element.value.trim()) {
                            element.classList.add('is-invalid');
                            valid = false;
                        } else {
                            element.classList.remove('is-invalid');
                        }
                    });
                    
                    if (!valid) {
                        showNotification('Please fill out all required fields', 'error');
                        return;
                    }
                    
                    // Show loading state
                    sendMessage.disabled = true;
                    sendMessage.innerphp = '<i class="fas fa-spinner fa-spin"></i> Sending...';
                    
                    // Simulate form submission (in a real app, this would be an AJAX call)
                    setTimeout(function() {
                        // Reset form
                        form.reset();
                        
                        // Close modal
                        composeMessageModal.style.display = 'none';
                        document.body.style.overflow = 'auto'; // Allow scrolling
                        
                        // Show success notification
                        showNotification('Message sent successfully!', 'success');
                        
                        // Reset button state
                        sendMessage.disabled = false;
                        sendMessage.innerphp = 'Send Message';
                    }, 1000);
                });
            }
            
            // Initialize delete confirmation modals
            const deleteButtons = document.querySelectorAll('.btn-danger');
            deleteButtons.forEach(button => {
                button.addEventListener('click', function(e) {
                    e.preventDefault();
                    if (confirm('Are you sure you want to delete this message? This action cannot be undone.')) {
                        showNotification('Message deleted successfully!', 'success');
                    }
                });
            });
            
            // Filter dropdowns
            const filterBtns = document.querySelectorAll('.dropdown button');
            const filterMenus = document.querySelectorAll('.dropdown-menu');
            
            filterBtns.forEach((btn, index) => {
                btn.addEventListener('click', function(e) {
                    e.stopPropagation();
                    
                    // Close all open menus first
                    filterMenus.forEach(menu => {
                        menu.style.display = 'none';
                    });
                    
                    // Open the clicked menu
                    filterMenus[index].style.display = 'block';
                });
            });
            
            document.addEventListener('click', function() {
                filterMenus.forEach(menu => {
                    menu.style.display = 'none';
                });
            });
        });
    </script>
</body>
</php> 