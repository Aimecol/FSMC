<!DOCTYPE php>
<php lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Admin Dashboard | Fair Surveying & Mapping Ltd</title>
    <link rel="icon" type="image/svg+xml" href="../images/logo.png" />
    <!-- Font Awesome -->
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
    />
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <!-- Admin CSS -->
    <link rel="stylesheet" href="css/styles.css" />
    <link rel="stylesheet" href="css/admin.css" />
  </head>
  <body>
    <div class="admin-layout">
      <!-- Sidebar -->
      <aside class="admin-sidebar">
        <div class="sidebar-logo">
          <div class="logo-icon">
            <i class="fas fa-map-marked-alt"></i>
          </div>
          <div class="logo-text">FSMC Admin</div>
        </div>

        <nav class="nav-menu">
          <ul>
            <li class="nav-item">
              <a href="index.php" class="nav-link active">
                <span class="nav-icon"
                  ><i class="fas fa-tachometer-alt"></i
                ></span>
                <span class="nav-text">Dashboard</span>
              </a>
            </li>

            <li class="nav-item">
              <a href="#" class="nav-link nav-dropdown-toggle">
                <span class="nav-icon"><i class="fas fa-users"></i></span>
                <span class="nav-text">Users</span>
                <span class="nav-arrow"
                  ><i class="fas fa-chevron-right"></i
                ></span>
              </a>
              <div class="nav-dropdown">
                <a href="pages/users/all-users.php" class="dropdown-item"
                  >All Users</a
                >
                <a href="pages/users/add-user.php" class="dropdown-item"
                  >Add User</a
                >
                <a href="pages/users/user-roles.php" class="dropdown-item"
                  >User Roles</a
                >
              </div>
            </li>

            <li class="nav-item">
              <a href="#" class="nav-link nav-dropdown-toggle">
                <span class="nav-icon"><i class="fas fa-cogs"></i></span>
                <span class="nav-text">Services</span>
                <span class="nav-arrow"
                  ><i class="fas fa-chevron-right"></i
                ></span>
              </a>
              <div class="nav-dropdown">
                <a href="pages/services/all-services.php" class="dropdown-item"
                  >All Services</a
                >
                <a href="pages/services/add-service.php" class="dropdown-item"
                  >Add Service</a
                >
                <a
                  href="pages/services/service-categories.php"
                  class="dropdown-item"
                  >Categories</a
                >
              </div>
            </li>

            <li class="nav-item">
              <a href="#" class="nav-link nav-dropdown-toggle">
                <span class="nav-icon"><i class="fas fa-box"></i></span>
                <span class="nav-text">Products</span>
                <span class="nav-arrow"
                  ><i class="fas fa-chevron-right"></i
                ></span>
              </a>
              <div class="nav-dropdown">
                <a href="pages/products/all-products.php" class="dropdown-item"
                  >All Products</a
                >
                <a href="pages/products/add-product.php" class="dropdown-item"
                  >Add Product</a
                >
                <a
                  href="pages/products/product-categories.php"
                  class="dropdown-item"
                  >Categories</a
                >
              </div>
            </li>

            <li class="nav-item">
              <a href="#" class="nav-link nav-dropdown-toggle">
                <span class="nav-icon"><i class="fas fa-book"></i></span>
                <span class="nav-text">Trainings</span>
                <span class="nav-arrow"
                  ><i class="fas fa-chevron-right"></i
                ></span>
              </a>
              <div class="nav-dropdown">
                <a
                  href="pages/trainings/all-trainings.php"
                  class="dropdown-item"
                  >All Trainings</a
                >
                <a
                  href="pages/trainings/add-training.php"
                  class="dropdown-item"
                  >Add Training</a
                >
                <a href="pages/trainings/categories.php" class="dropdown-item"
                  >Categories</a
                >
              </div>
            </li>

            <li class="nav-item">
              <a href="#" class="nav-link nav-dropdown-toggle">
                <span class="nav-icon"><i class="fas fa-flask"></i></span>
                <span class="nav-text">Research</span>
                <span class="nav-arrow"
                  ><i class="fas fa-chevron-right"></i
                ></span>
              </a>
              <div class="nav-dropdown">
                <a href="pages/research/all-research.php" class="dropdown-item"
                  >All Research</a
                >
                <a href="pages/research/add-research.php" class="dropdown-item"
                  >Add Research</a
                >
              </div>
            </li>

            <li class="nav-item">
              <a href="#" class="nav-link nav-dropdown-toggle">
                <span class="nav-icon"><i class="fas fa-envelope"></i></span>
                <span class="nav-text">Messages</span>
                <span class="nav-arrow"
                  ><i class="fas fa-chevron-right"></i
                ></span>
              </a>
              <div class="nav-dropdown">
                <a href="pages/messages/inbox.php" class="dropdown-item"
                  >Inbox</a
                >
                <a href="pages/messages/sent.php" class="dropdown-item"
                  >Sent</a
                >
              </div>
            </li>
          </ul>
        </nav>
      </aside>

      <!-- Header -->
      <header class="admin-header">
        <button class="toggle-sidebar" aria-label="Toggle navigation">
          <div class="hamburger">
            <span></span>
            <span></span>
            <span></span>
          </div>
        </button>

        <div class="header-title">Dashboard</div>

        <div class="header-controls">

          <div class="admin-user">
            <div class="user-dropdown">
              <div class="user-avatar">
                <i class="fas fa-user"></i>
              </div>
              <div class="user-name">Admin</div>
              <i class="fas fa-chevron-down"></i>

              <div class="user-menu">
                <a href="pages/profile.php" class="user-menu-item">
                  <i class="fas fa-user-circle"></i>
                  <span>Profile</span>
                </a>
                <div class="dropdown-divider"></div>
                <a href="./login.php" class="user-menu-item">
                  <i class="fas fa-sign-out-alt"></i>
                  <span>Logout</span>
                </a>
              </div>
            </div>
          </div>
        </div>
      </header>

      <div class="sidebar-overlay"></div>

      <!-- Main Content -->
      <main class="admin-main animate-fadeIn">
        <!-- Welcome Alert -->
        <div class="alert alert-info">
          <i class="fas fa-info-circle fa-lg"></i>
          <div>
            <h4 class="mt-0 mb-1">Welcome to the Admin Dashboard</h4>
            <p class="mb-0">
              Here you can manage your website content, users, and settings.
            </p>
          </div>
        </div>

        <!-- Stats Widgets -->
        <div class="dashboard-grid">
          <div class="widget">
            <div class="widget-header">
              <div class="widget-title">Total Users</div>
              <div class="widget-icon">
                <i class="fas fa-users"></i>
              </div>
            </div>
            <div class="widget-body">
              <div class="widget-value">265</div>
              <div class="widget-description">Registered users</div>
            </div>
            <div class="widget-footer">
              <div class="widget-change text-success">
                <i class="fas fa-arrow-up"></i>
                <span>12% from last month</span>
              </div>
            </div>
          </div>

          <div class="widget">
            <div class="widget-header">
              <div class="widget-title">Total Services</div>
              <div class="widget-icon">
                <i class="fas fa-cogs"></i>
              </div>
            </div>
            <div class="widget-body">
              <div class="widget-value">24</div>
              <div class="widget-description">Active services</div>
            </div>
            <div class="widget-footer">
              <div class="widget-change text-success">
                <i class="fas fa-arrow-up"></i>
                <span>3 new this month</span>
              </div>
            </div>
          </div>

          <div class="widget">
            <div class="widget-header">
              <div class="widget-title">Total Products</div>
              <div class="widget-icon">
                <i class="fas fa-box"></i>
              </div>
            </div>
            <div class="widget-body">
              <div class="widget-value">78</div>
              <div class="widget-description">Available products</div>
            </div>
            <div class="widget-footer">
              <div class="widget-change text-success">
                <i class="fas fa-arrow-up"></i>
                <span>5 new this month</span>
              </div>
            </div>
          </div>

          <div class="widget">
            <div class="widget-header">
              <div class="widget-title">Inquiries</div>
              <div class="widget-icon">
                <i class="fas fa-envelope"></i>
              </div>
            </div>
            <div class="widget-body">
              <div class="widget-value">18</div>
              <div class="widget-description">New inquiries</div>
            </div>
            <div class="widget-footer">
              <div class="widget-change text-danger">
                <i class="fas fa-arrow-down"></i>
                <span>7% from last month</span>
              </div>
            </div>
          </div>
        </div>

        <!-- Recent Messages -->
        <div class="card mt-4">
          <div class="card-header d-flex justify-between align-center">
            <h5 class="card-title">Recent Messages</h5>
            <a href="pages/messages/inbox.php" class="btn btn-sm btn-primary"
              >View All</a
            >
          </div>
          <div class="card-body">
            <div class="table-responsive">
              <table class="table">
                <thead>
                  <tr>
                    <th>From</th>
                    <th>Subject</th>
                    <th>Date</th>
                    <th>Actions</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td>
                      <div class="d-flex align-center gap-2">
                        <div
                          class="user-avatar"
                          style="width: 32px; height: 32px; font-size: 12px"
                        >
                          CS
                        </div>
                        <div>Client Support</div>
                      </div>
                    </td>
                    <td>Need help with land surveying</td>
                    <td>Today, 8:45 AM</td>
                    <td>
                      <div class="d-flex gap-1">
                        <button class="btn btn-sm btn-light">View</button>
                        <button class="btn btn-sm btn-primary">Reply</button>
                      </div>
                    </td>
                  </tr>
                  <tr>
                    <td>
                      <div class="d-flex align-center gap-2">
                        <div
                          class="user-avatar"
                          style="width: 32px; height: 32px; font-size: 12px"
                        >
                          MJ
                        </div>
                        <div>Michael Johnson</div>
                      </div>
                    </td>
                    <td>Product inquiry - GPS Devices</td>
                    <td>Yesterday, 2:30 PM</td>
                    <td>
                      <div class="d-flex gap-1">
                        <button class="btn btn-sm btn-light">View</button>
                        <button class="btn btn-sm btn-primary">Reply</button>
                      </div>
                    </td>
                  </tr>
                  <tr>
                    <td>
                      <div class="d-flex align-center gap-2">
                        <div
                          class="user-avatar"
                          style="width: 32px; height: 32px; font-size: 12px"
                        >
                          SL
                        </div>
                        <div>Sarah Lee</div>
                      </div>
                    </td>
                    <td>Training program registration</td>
                    <td>Mar 18, 9:15 AM</td>
                    <td>
                      <div class="d-flex gap-1">
                        <button class="btn btn-sm btn-light">View</button>
                        <button class="btn btn-sm btn-primary">Reply</button>
                      </div>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </main>
      <!-- Notification Container -->
      <div class="notification-container"></div>
    </div>

    <!-- Admin JavaScript -->
    <script src="js/admin.js"></script>
    <script>
      document.addEventListener("DOMContentLoaded", function () {
        // Stats Counter Animation
        const stats = document.querySelectorAll(".widget-value");
        stats.forEach((stat) => {
          const target = parseInt(stat.textContent);
          let current = 0;
          const increment = target / 50;
          const duration = 1500;
          const step = duration / 50;

          const updateCounter = () => {
            current += increment;
            if (current < target) {
              stat.textContent = Math.round(current).toLocaleString();
              setTimeout(updateCounter, step);
            } else {
              stat.textContent = target.toLocaleString();
            }
          };

          updateCounter();
        });
        // Add this to your existing CSS or in a style tag
        const newStyles = `
            .admin-table tr {
                transition: transform 0.3s ease, background-color 0.3s ease;
            }

            .admin-table tr:hover {
                background-color: rgba(46, 134, 193, 0.05);
            }
        `;

        // Add styles to document
        const styleSheet = document.createElement("style");
        styleSheet.textContent = newStyles;
        document.head.appendChild(styleSheet);
      });
    </script>
  </body>
</php>
