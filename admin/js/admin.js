document.addEventListener('DOMContentLoaded', function() {
  // Toggle sidebar
  const toggleSidebar = document.querySelector('.toggle-sidebar');
  const adminSidebar = document.querySelector('.admin-sidebar');
  const sidebarOverlay = document.querySelector('.sidebar-overlay');
  const body = document.body;

  function openSidebar() {
      adminSidebar.classList.add('show');
      sidebarOverlay.classList.add('show');
      body.classList.add('sidebar-open');
  }

  function closeSidebar() {
      adminSidebar.classList.remove('show');
      sidebarOverlay.classList.remove('show');
      body.classList.remove('sidebar-open');
  }

  if (toggleSidebar) {
      toggleSidebar.addEventListener('click', function() {
          if (window.innerWidth < 992) {
              if (adminSidebar.classList.contains('show')) {
                  closeSidebar();
              } else {
                  openSidebar();
              }
          }
      });
  }

  // Close sidebar when clicking overlay
  if (sidebarOverlay) {
      sidebarOverlay.addEventListener('click', closeSidebar);
  }

  // Handle ESC key to close sidebar
  document.addEventListener('keydown', function(e) {
      if (e.key === 'Escape' && adminSidebar.classList.contains('show')) {
          closeSidebar();
      }
  });

  // Handle window resize
  window.addEventListener('resize', function() {
      if (window.innerWidth >= 992) {
          closeSidebar();
      }
  });
  
  // Toggle dropdown menus in sidebar
  const dropdownToggles = document.querySelectorAll('.nav-dropdown-toggle');
  
  dropdownToggles.forEach(toggle => {
    toggle.addEventListener('click', function(e) {
      e.preventDefault();
      
      // Toggle active class on the nav link
      this.classList.toggle('active');
      
      // Find the dropdown content
      const dropdownContent = this.nextElementSibling;
      
      // Toggle the 'open' class
      if (dropdownContent.classList.contains('open')) {
        dropdownContent.style.height = '0';
        dropdownContent.classList.remove('open');
      } else {
        // Calculate the height of the dropdown content
        const height = dropdownContent.scrollHeight;
        dropdownContent.style.height = height + 'px';
        dropdownContent.classList.add('open');
      }
    });
  });
  
  // Add animation to widgets
  const widgets = document.querySelectorAll('.widget, .card');
  widgets.forEach(widget => {
    widget.classList.add('animate-slideInUp');
  });
});

// Add animation for page transitions
window.addEventListener('load', function() {
  document.body.classList.add('page-loaded');
});
