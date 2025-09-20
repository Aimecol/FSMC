<!DOCTYPE php>
<php lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Profile | Fair Surveying & Mapping Ltd</title>
    <link rel="icon" type="image/svg+xml" href="../../images/logo.png" />
    <!-- Font Awesome -->
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
    />
    <!-- Admin CSS -->
    <link rel="stylesheet" href="../css/admin.css" />
    <style>
      .profile-header {
        background: linear-gradient(
          135deg,
          var(--primary-color),
          var(--secondary-color)
        );
        padding: 3rem 2rem;
        border-radius: 12px;
        color: white;
        margin-bottom: 2rem;
        position: relative;
        overflow: hidden;
      }

      .profile-header::before {
        content: "";
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: url("../../images/pattern.png");
        opacity: 0.1;
        animation: moveBackground 20s linear infinite;
      }

      @keyframes moveBackground {
        from {
          background-position: 0 0;
        }
        to {
          background-position: 100% 100%;
        }
      }

      .profile-avatar {
        width: 150px;
        height: 150px;
        border-radius: 50%;
        background: white;
        padding: 4px;
        margin-bottom: 1.5rem;
        position: relative;
        cursor: pointer;
        transition: transform 0.3s ease;
      }

      .profile-avatar:hover {
        transform: scale(1.05);
      }

      .profile-avatar img {
        width: 100%;
        height: 100%;
        border-radius: 50%;
        object-fit: cover;
      }

      .profile-avatar .upload-overlay {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0, 0, 0, 0.5);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        opacity: 0;
        transition: opacity 0.3s ease;
      }

      .profile-avatar:hover .upload-overlay {
        opacity: 1;
      }

      .profile-stats {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
        gap: 1.5rem;
        margin-top: 2rem;
        width: 100%;
      }

      .stat-card {
        background: rgba(255, 255, 255, 0.1);
        padding: 1rem;
        border-radius: 8px;
        text-align: center;
        backdrop-filter: blur(10px);
        transition: transform 0.3s ease;
      }

      .stat-card:hover {
        transform: translateY(-5px);
      }

      .stat-value {
        font-size: 1.5rem;
        font-weight: bold;
        margin-bottom: 0.5rem;
      }

      .stat-label {
        font-size: 0.9rem;
        opacity: 0.9;
      }

      .profile-tabs {
        display: flex;
        gap: 1rem;
        margin-bottom: 2rem;
        border-bottom: 2px solid #eee;
        padding-bottom: 1rem;
      }

      .profile-tab {
        padding: 0.75rem 1.5rem;
        border-radius: 6px;
        cursor: pointer;
        transition: all 0.3s ease;
        position: relative;
      }

      .profile-tab.active {
        background: var(--primary-color);
        color: white;
      }

      .profile-tab:not(.active):hover {
        background: #f0f0f0;
      }

      .tab-content {
        display: none;
        animation: fadeIn 0.5s ease;
      }

      .tab-content.active {
        display: block;
      }

      .form-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 1.5rem;
      }

      .social-links {
        display: flex;
        gap: 1rem;
        margin-top: 1rem;
      }

      .social-link {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        transition: transform 0.3s ease;
      }

      .social-link:hover {
        transform: scale(1.1);
      }

      .activity-item {
        display: flex;
        align-items: center;
        gap: 1rem;
        padding: 1rem;
        border-radius: 8px;
        margin-bottom: 1rem;
        background: #f8f9fa;
        transition: transform 0.3s ease;
      }

      .activity-item:hover {
        transform: translateX(5px);
      }

      .activity-icon {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
      }

      .activity-content {
        flex: 1;
      }

      .activity-time {
        font-size: 0.85rem;
        color: #6c757d;
      }
    </style>
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
              <a href="../index.php" class="nav-link">
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
                <a href="./users/all-users.php" class="dropdown-item"
                  >All Users</a
                >
                <a href="./users/add-user.php" class="dropdown-item"
                  >Add User</a
                >
                <a href="./users/user-roles.php" class="dropdown-item"
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
                <a href="./services/all-services.php" class="dropdown-item"
                  >All Services</a
                >
                <a href="./services/add-service.php" class="dropdown-item"
                  >Add Service</a
                >
                <a
                  href="./services/service-categories.php"
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
                <a href="./products/all-products.php" class="dropdown-item"
                  >All Products</a
                >
                <a href="./products/add-product.php" class="dropdown-item"
                  >Add Product</a
                >
                <a
                  href="./products/product-categories.php"
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
                  href="./trainings/all-trainings.php"
                  class="dropdown-item"
                  >All Trainings</a
                >
                <a
                  href="./trainings/add-training.php"
                  class="dropdown-item"
                  >Add Training</a
                >
                <a href="./trainings/categories.php" class="dropdown-item"
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
                <a href="./research/all-research.php" class="dropdown-item"
                  >All Research</a
                >
                <a href="./research/add-research.php" class="dropdown-item"
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
                <a href="./messages/inbox.php" class="dropdown-item"
                  >Inbox</a
                >
                <a href="./messages/sent.php" class="dropdown-item"
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

        <div class="header-title">Users Management</div>

        <div class="header-controls">
          <div class="admin-user">
            <div class="user-dropdown">
              <div class="user-avatar">
                <i class="fas fa-user"></i>
              </div>
              <div class="user-name">Admin</div>
              <i class="fas fa-chevron-down"></i>

              <div class="user-menu">
                <a href="./profile.php" class="user-menu-item">
                  <i class="fas fa-user-circle"></i>
                  <span>Profile</span>
                </a>
                <div class="dropdown-divider"></div>
                <a href="../login.php" class="user-menu-item">
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
        <div class="profile-header">
          <div class="d-flex flex-column align-center">
            <div class="profile-avatar">
              <img
                src="../../bhHQ2-XvUG3QKct5kwamE.jpg"
                alt="Profile Picture"
              />
              <div class="upload-overlay">
                <i class="fas fa-camera"></i>
              </div>
            </div>
            <h2 class="mb-2">John Doe</h2>
            <p class="mb-3">Senior Surveyor & Administrator</p>
            <div class="social-links">
              <a href="#" class="social-link" style="background: #1da1f2"
                ><i class="fab fa-twitter"></i
              ></a>
              <a href="#" class="social-link" style="background: #0077b5"
                ><i class="fab fa-linkedin-in"></i
              ></a>
              <a href="#" class="social-link" style="background: #333"
                ><i class="fab fa-github"></i
              ></a>
              <a href="#" class="social-link" style="background: #ea4c89"
                ><i class="fab fa-dribbble"></i
              ></a>
            </div>

            <div class="profile-stats">
              <div class="stat-card">
                <div class="stat-value">125</div>
                <div class="stat-label">Projects</div>
              </div>
              <div class="stat-card">
                <div class="stat-value">12K</div>
                <div class="stat-label">Survey Points</div>
              </div>
              <div class="stat-card">
                <div class="stat-value">48</div>
                <div class="stat-label">Team Members</div>
              </div>
              <div class="stat-card">
                <div class="stat-value">92%</div>
                <div class="stat-label">Success Rate</div>
              </div>
            </div>
          </div>
        </div>

        <div class="profile-tabs">
          <div class="profile-tab active" data-tab="personal">
            <i class="fas fa-user"></i> Personal Info
          </div>
          <div class="profile-tab" data-tab="security">
            <i class="fas fa-shield-alt"></i> Security
          </div>
          <div class="profile-tab" data-tab="activity">
            <i class="fas fa-chart-line"></i> Activity
          </div>
          <div class="profile-tab" data-tab="settings">
            <i class="fas fa-cog"></i> Settings
          </div>
        </div>

        <div class="tab-content active" id="personal">
          <div class="card">
            <div class="card-header">
              <h5 class="card-title">Personal Information</h5>
            </div>
            <div class="card-body">
              <form class="form-grid">
                <div class="form-group">
                  <label class="form-label">First Name</label>
                  <input type="text" class="form-control" value="John" />
                </div>
                <div class="form-group">
                  <label class="form-label">Last Name</label>
                  <input type="text" class="form-control" value="Doe" />
                </div>
                <div class="form-group">
                  <label class="form-label">Email</label>
                  <input
                    type="email"
                    class="form-control"
                    value="john.doe@example.com"
                  />
                </div>
                <div class="form-group">
                  <label class="form-label">Phone</label>
                  <input
                    type="tel"
                    class="form-control"
                    value="+1 234 567 890"
                  />
                </div>
                <div class="form-group">
                  <label class="form-label">Location</label>
                  <input
                    type="text"
                    class="form-control"
                    value="New York, USA"
                  />
                </div>
                <div class="form-group">
                  <label class="form-label">Department</label>
                  <input type="text" class="form-control" value="Surveying" />
                </div>
              </form>
            </div>
          </div>
        </div>

        <div class="tab-content" id="security">
          <div class="card">
            <div class="card-header">
              <h5 class="card-title">Security Settings</h5>
            </div>
            <div class="card-body">
              <form>
                <div class="form-group">
                  <label class="form-label">Current Password</label>
                  <input type="password" class="form-control" />
                </div>
                <div class="form-group">
                  <label class="form-label">New Password</label>
                  <input type="password" class="form-control" />
                </div>
                <div class="form-group">
                  <label class="form-label">Confirm Password</label>
                  <input type="password" class="form-control" />
                </div>
                <button type="submit" class="btn btn-primary">
                  Update Password
                </button>
              </form>
            </div>
          </div>
        </div>

        <div class="tab-content" id="activity">
          <div class="activity-item">
            <div class="activity-icon bg-primary">
              <i class="fas fa-map-marker-alt"></i>
            </div>
            <div class="activity-content">
              <div>Completed land survey project</div>
              <div class="activity-time">2 hours ago</div>
            </div>
          </div>
          <div class="activity-item">
            <div class="activity-icon bg-success">
              <i class="fas fa-users"></i>
            </div>
            <div class="activity-content">
              <div>Added new team member</div>
              <div class="activity-time">Yesterday</div>
            </div>
          </div>
          <div class="activity-item">
            <div class="activity-icon bg-warning">
              <i class="fas fa-file-alt"></i>
            </div>
            <div class="activity-content">
              <div>Updated project documentation</div>
              <div class="activity-time">3 days ago</div>
            </div>
          </div>
        </div>

        <div class="tab-content" id="settings">
          <div class="card">
            <div class="card-header">
              <h5 class="card-title">Preferences</h5>
            </div>
            <div class="card-body">
              <form>
                <div class="form-group">
                  <label class="form-label d-flex justify-between">
                    Email Notifications
                    <input type="checkbox" checked />
                  </label>
                </div>
                <div class="form-group">
                  <label class="form-label d-flex justify-between">
                    SMS Notifications
                    <input type="checkbox" />
                  </label>
                </div>
                <div class="form-group">
                  <label class="form-label d-flex justify-between">
                    Two-Factor Authentication
                    <input type="checkbox" checked />
                  </label>
                </div>
              </form>
            </div>
          </div>
        </div>
      </main>
    </div>

    <script src="../js/admin.js"></script>
    <script>
      document.addEventListener("DOMContentLoaded", function () {
        // Tab switching functionality
        const tabs = document.querySelectorAll(".profile-tab");
        const contents = document.querySelectorAll(".tab-content");

        tabs.forEach((tab) => {
          tab.addEventListener("click", () => {
            const target = tab.dataset.tab;

            // Update active tab
            tabs.forEach((t) => t.classList.remove("active"));
            tab.classList.add("active");

            // Update active content
            contents.forEach((content) => {
              if (content.id === target) {
                content.classList.add("active");
              } else {
                content.classList.remove("active");
              }
            });
          });
        });

        // Profile picture upload
        const profileAvatar = document.querySelector(".profile-avatar");
        profileAvatar.addEventListener("click", () => {
          const input = document.createElement("input");
          input.type = "file";
          input.accept = "image/*";
          input.onchange = (e) => {
            const file = e.target.files[0];
            if (file) {
              const reader = new FileReader();
              reader.onload = (e) => {
                profileAvatar.querySelector("img").src = e.target.result;
                showNotification(
                  "Profile picture updated successfully!",
                  "success"
                );
              };
              reader.readAsDataURL(file);
            }
          };
          input.click();
        });

        // Form submission handling
        const forms = document.querySelectorAll("form");
        forms.forEach((form) => {
          form.addEventListener("submit", (e) => {
            e.preventDefault();
            showNotification("Changes saved successfully!", "success");
          });
        });
      });
    </script>
  </body>
</php>
