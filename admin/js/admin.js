document.addEventListener('DOMContentLoaded', function() {
  // Toggle sidebar
  const toggleSidebar = document.querySelector('.toggle-sidebar');
  const adminLayout = document.querySelector('.admin-layout');
  const adminSidebar = document.querySelector('.admin-sidebar');
  
  if (toggleSidebar) {
    toggleSidebar.addEventListener('click', function() {
      document.body.classList.toggle('sidebar-collapsed');
      
      // For mobile
      if (window.innerWidth < 768) {
        adminSidebar.classList.toggle('show');
      }
    });
  }
  
  // Close sidebar when clicking outside on mobile
  document.addEventListener('click', function(e) {
    if (window.innerWidth < 768 && 
        adminSidebar && 
        adminSidebar.classList.contains('show') && 
        !adminSidebar.contains(e.target) && 
        !toggleSidebar.contains(e.target)) {
      adminSidebar.classList.remove('show');
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
  
  // User dropdown
  const userDropdown = document.querySelector('.user-dropdown');
  const userMenu = document.querySelector('.user-menu');
  
  if (userDropdown && userMenu) {
    userDropdown.addEventListener('click', function(e) {
      e.stopPropagation();
      userMenu.classList.toggle('show');
    });
    
    // Close when clicking outside
    document.addEventListener('click', function() {
      if (userMenu.classList.contains('show')) {
        userMenu.classList.remove('show');
      }
    });
    
    userMenu.addEventListener('click', function(e) {
      e.stopPropagation();
    });
  }
  
  // Initialize charts if they exist
  if (typeof Chart !== 'undefined') {
    initializeCharts();
  }
  
  // Add animation to widgets
  const widgets = document.querySelectorAll('.widget, .card');
  widgets.forEach(widget => {
    widget.classList.add('animate-slideInUp');
  });
});

// Initialize charts function (if Chart.js is available)
function initializeCharts() {
  // Example: Sales overview chart
  const salesChartEl = document.getElementById('salesChart');
  if (salesChartEl) {
    const salesChart = new Chart(salesChartEl, {
      type: 'line',
      data: {
        labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
        datasets: [{
          label: 'Revenue',
          data: [12, 19, 15, 27, 22, 39, 35, 42, 50, 56, 60, 70],
          borderColor: '#2e86c1',
          backgroundColor: 'rgba(46, 134, 193, 0.1)',
          borderWidth: 2,
          tension: 0.3,
          fill: true
        }, {
          label: 'Expenses',
          data: [8, 12, 10, 18, 15, 25, 22, 30, 36, 40, 45, 50],
          borderColor: '#e67e22',
          backgroundColor: 'rgba(230, 126, 34, 0.1)',
          borderWidth: 2,
          tension: 0.3,
          fill: true
        }]
      },
      options: {
        responsive: true,
        plugins: {
          legend: {
            position: 'top',
          },
          tooltip: {
            mode: 'index',
            intersect: false
          }
        },
        scales: {
          y: {
            beginAtZero: true
          }
        }
      }
    });
  }
  
  // Example: Users chart
  const usersChartEl = document.getElementById('usersChart');
  if (usersChartEl) {
    const usersChart = new Chart(usersChartEl, {
      type: 'bar',
      data: {
        labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
        datasets: [{
          label: 'New Users',
          data: [65, 59, 80, 81, 56, 55],
          backgroundColor: 'rgba(46, 134, 193, 0.7)'
        }, {
          label: 'Active Users',
          data: [28, 48, 40, 19, 86, 27],
          backgroundColor: 'rgba(243, 156, 18, 0.7)'
        }]
      },
      options: {
        responsive: true,
        plugins: {
          legend: {
            position: 'top',
          }
        },
        scales: {
          y: {
            beginAtZero: true
          }
        }
      }
    });
  }
  
  // Example: Products pie chart
  const productsChartEl = document.getElementById('productsChart');
  if (productsChartEl) {
    const productsChart = new Chart(productsChartEl, {
      type: 'doughnut',
      data: {
        labels: ['Category 1', 'Category 2', 'Category 3', 'Category 4', 'Category 5'],
        datasets: [{
          data: [12, 19, 8, 15, 10],
          backgroundColor: [
            '#2e86c1',
            '#f39c12',
            '#27ae60',
            '#8e44ad',
            '#e74c3c'
          ],
          borderWidth: 0
        }]
      },
      options: {
        responsive: true,
        plugins: {
          legend: {
            position: 'right',
          }
        },
        cutout: '70%'
      }
    });
  }
}

// Data tables initialization
if (typeof DataTable !== 'undefined') {
  const tables = document.querySelectorAll('.datatable');
  tables.forEach(table => {
    new DataTable(table, {
      responsive: true,
      pageLength: 10,
      lengthMenu: [5, 10, 25, 50],
      language: {
        search: "",
        searchPlaceholder: "Search..."
      }
    });
  });
}

// Add animation for page transitions
window.addEventListener('load', function() {
  document.body.classList.add('page-loaded');
});

// Form validation example
function validateForm(formId) {
  const form = document.getElementById(formId);
  if (!form) return false;
  
  const inputs = form.querySelectorAll('input[required], select[required], textarea[required]');
  let isValid = true;
  
  inputs.forEach(input => {
    if (!input.value.trim()) {
      input.classList.add('is-invalid');
      isValid = false;
    } else {
      input.classList.remove('is-invalid');
    }
    
    input.addEventListener('input', function() {
      if (this.value.trim()) {
        this.classList.remove('is-invalid');
      } else {
        this.classList.add('is-invalid');
      }
    });
  });
  
  return isValid;
}

// Show notification
function showNotification(message, type = 'success', duration = 3000) {
  const notificationContainer = document.querySelector('.notification-container');
  
  if (!notificationContainer) {
    // Create notification container if it doesn't exist
    const container = document.createElement('div');
    container.className = 'notification-container';
    document.body.appendChild(container);
  }
  
  // Create new notification
  const notification = document.createElement('div');
  notification.className = `notification notification-${type} animate-slideInUp`;
  notification.innerHTML = `
    <div class="notification-icon">
      <i class="fas ${type === 'success' ? 'fa-check-circle' : type === 'warning' ? 'fa-exclamation-triangle' : type === 'error' ? 'fa-times-circle' : 'fa-info-circle'}"></i>
    </div>
    <div class="notification-content">
      <p>${message}</p>
    </div>
    <button class="notification-close">
      <i class="fas fa-times"></i>
    </button>
  `;
  
  // Add to container
  document.querySelector('.notification-container').appendChild(notification);
  
  // Add close button functionality
  notification.querySelector('.notification-close').addEventListener('click', function() {
    notification.classList.add('fade-out');
    setTimeout(() => {
      notification.remove();
    }, 300);
  });
  
  // Auto remove after duration
  setTimeout(() => {
    notification.classList.add('fade-out');
    setTimeout(() => {
      notification.remove();
    }, 300);
  }, duration);
}

// Handle AJAX form submissions
function submitFormAjax(formId, successCallback, errorCallback) {
  const form = document.getElementById(formId);
  if (!form) return;
  
  form.addEventListener('submit', function(e) {
    e.preventDefault();
    
    if (!validateForm(formId)) {
      showNotification('Please fill in all required fields', 'error');
      return;
    }
    
    const formData = new FormData(form);
    const submitBtn = form.querySelector('[type="submit"]');
    
    if (submitBtn) {
      submitBtn.disabled = true;
      submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Processing...';
    }
    
    // Simulate AJAX request (replace with actual fetch/xhr in production)
    setTimeout(() => {
      if (Math.random() > 0.2) { // 80% success rate for demo
        if (typeof successCallback === 'function') {
          successCallback(formData);
        } else {
          showNotification('Form submitted successfully!', 'success');
        }
      } else {
        if (typeof errorCallback === 'function') {
          errorCallback();
        } else {
          showNotification('An error occurred. Please try again.', 'error');
        }
      }
      
      // Reset button
      if (submitBtn) {
        submitBtn.disabled = false;
        submitBtn.innerHTML = 'Submit';
      }
    }, 1500);
  });
}
