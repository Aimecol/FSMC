<!DOCTYPE php>
<php lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Training Categories | Fair Surveying & Mapping Ltd</title>
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

        <!-- training categories management -->
        
                    
    <script>
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
                    
                    if (confirm(`Are you sure you want to delete the "${categoryName}" category? This will affect all associated trainings.`)) {
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