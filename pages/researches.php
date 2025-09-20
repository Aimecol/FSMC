<!DOCTYPE php>
<php lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Research Projects | Fair Surveying & Mapping Ltd</title>
    <link rel="icon" type="image/svg+xml" href="../images/logo.png" />
    <!-- Font Awesome -->
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
    />
    <!-- AOS Animation Library -->
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.css"
    />
    <!-- Main CSS -->
    <link rel="stylesheet" href="../css/style.css" />
    <!-- Page CSS -->
    <link rel="stylesheet" href="../css/researches.css" />
  </head>
  <body>
    <?php include 'includes/header.php'; ?>

    <div class="page-hero">
      <div class="container">
        <h1 class="page-title" data-aos="fade-up">Research Projects</h1>
        <p class="page-subtitle" data-aos="fade-up" data-aos-delay="100">
          Exploring innovative solutions in surveying and mapping technologies
        </p>
      </div>
      <div class="hero-shape"></div>
    </div>

    <section class="research-intro">
      <div class="container">
        <div class="intro-content" data-aos="fade-up">
          <h2>Advancing Innovation Through Research</h2>
          <p>
            At Fair Surveying & Mapping Ltd, we're committed to pushing the
            boundaries of geospatial science through our dedicated research
            initiatives. Our interdisciplinary team works on cutting-edge
            projects that address real-world challenges in surveying, mapping,
            and environmental management.
          </p>
          <div class="intro-stats">
            <div class="stat-item" data-aos="fade-up" data-aos-delay="100">
              <div class="stat-value"><span class="counter">12</span>+</div>
              <div class="stat-label">Active Projects</div>
            </div>
            <div class="stat-item" data-aos="fade-up" data-aos-delay="300">
              <div class="stat-value"><span class="counter">8</span></div>
              <div class="stat-label">Research Partners</div>
            </div>
          </div>
        </div>
        <div class="intro-image" data-aos="fade-left">
          <img
            src="../images/9c_NLZ75_JAwFH7mjiGB_.jpg"
            alt="Research Team"
            class="img-fluid"
          />
        </div>
      </div>
    </section>

    <section class="research-categories">
      <div class="container">
        <div class="section-title" data-aos="fade-up">
          <h2>Research Focus Areas</h2>
          <p>Explore our specialized fields of research and innovation</p>
        </div>
        <div class="categories-grid">
          <div class="category-card" data-aos="fade-up" data-aos-delay="50">
            <div class="category-icon">
              <i class="fas fa-satellite"></i>
            </div>
            <h3>Remote Sensing</h3>
            <p>
              Advanced applications of satellite and aerial imagery for land
              monitoring and analysis
            </p>
          </div>
          <div class="category-card" data-aos="fade-up" data-aos-delay="100">
            <div class="category-icon">
              <i class="fas fa-map-marked-alt"></i>
            </div>
            <h3>Geospatial Analysis</h3>
            <p>
              Innovative approaches to spatial data processing and visualization
            </p>
          </div>
          <div class="category-card" data-aos="fade-up" data-aos-delay="150">
            <div class="category-icon">
              <i class="fas fa-water"></i>
            </div>
            <h3>Environmental Monitoring</h3>
            <p>
              Solutions for tracking ecological changes and managing natural
              resources
            </p>
          </div>
          <div class="category-card" data-aos="fade-up" data-aos-delay="200">
            <div class="category-icon">
              <i class="fas fa-city"></i>
            </div>
            <h3>Urban Planning</h3>
            <p>
              Research on sustainable development and smart city infrastructure
            </p>
          </div>
        </div>
      </div>
    </section>

    <section class="research-projects">
      <div class="container">
        <div class="section-header" data-aos="fade-up">
          <h2>Featured Research Projects</h2>
          <p>
            Discover our innovative research initiatives and their real-world
            applications
          </p>
          <div class="filter-controls">
            <button class="filter-btn active" data-filter="all">
              All Projects
            </button>
            <button class="filter-btn" data-filter="remote-sensing">
              Remote Sensing
            </button>
            <button class="filter-btn" data-filter="geospatial">
              Geospatial
            </button>
            <button class="filter-btn" data-filter="environmental">
              Environmental
            </button>
            <button class="filter-btn" data-filter="urban">
              Urban Planning
            </button>
          </div>
        </div>

        <div class="projects-grid">
          <!-- Project 1 -->
          <div
            class="project-card"
            data-category="remote-sensing"
            data-aos="fade-up"
          >
            <div class="project-image">
              <img
                src="../images/9c_NLZ75_JAwFH7mjiGB_.jpg"
                alt="Remote Sensing Applications"
              />
              <div class="project-overlay">
                <span class="project-category">Remote Sensing</span>
                <a href="#project-1" class="btn-view-project">View Project</a>
              </div>
            </div>
            <div class="project-content">
              <h3>Remote Sensing Applications for Land Cover Mapping</h3>
              <p>
                Developing advanced techniques for automated land cover
                classification using multi-spectral satellite imagery and
                machine learning algorithms.
              </p>
              <div class="project-meta">
                <div class="meta-item">
                  <i class="fas fa-calendar-alt"></i> 2024-2025
                </div>
                <div class="meta-item">
                  <i class="fas fa-user"></i>  HATANGIMANA F.
                </div>
              </div>
            </div>
          </div>

          <!-- Project 2 -->
          <div
            class="project-card"
            data-category="geospatial"
            data-aos="fade-up"
            data-aos-delay="100"
          >
            <div class="project-image">
              <img
                src="../images/bhHQ2-XvUG3QKct5kwamE.jpg"
                alt="3D Terrain Modeling"
              />
              <div class="project-overlay">
                <span class="project-category">Geospatial</span>
                <a href="#project-2" class="btn-view-project">View Project</a>
              </div>
            </div>
            <div class="project-content">
              <h3>Advanced 3D Terrain Modeling Using Drone Photogrammetry</h3>
              <p>
                Creating high-precision digital terrain models using
                drone-captured imagery for applications in infrastructure
                planning and natural hazard assessment.
              </p>
              <div class="project-meta">
                <div class="meta-item">
                  <i class="fas fa-calendar-alt"></i> 2023-2025
                </div>
                <div class="meta-item">
                  <i class="fas fa-user"></i>  HATANGIMANA F.
                </div>
              </div>
            </div>
          </div>

          <!-- Project 3 -->
          <div
            class="project-card"
            data-category="environmental"
            data-aos="fade-up"
            data-aos-delay="200"
          >
            <div class="project-image">
              <img
                src="../images/rC9AvGLJMT6thyTyTxB--.jpg"
                alt="Water Resource Management"
              />
              <div class="project-overlay">
                <span class="project-category">Environmental</span>
                <a href="#project-3" class="btn-view-project">View Project</a>
              </div>
            </div>
            <div class="project-content">
              <h3>Integrated Water Resource Management System</h3>
              <p>
                Developing a comprehensive system for monitoring and managing
                water resources using satellite data, IoT sensors, and
                predictive analytics.
              </p>
              <div class="project-meta">
                <div class="meta-item">
                  <i class="fas fa-calendar-alt"></i> 2023-2024
                </div>
                <div class="meta-item">
                  <i class="fas fa-user"></i>  HATANGIMANA F.
                </div>
              </div>
            </div>
          </div>

          <!-- Project 4 -->
          <div
            class="project-card"
            data-category="urban"
            data-aos="fade-up"
            data-aos-delay="300"
          >
            <div class="project-image">
              <img
                src="../images/bhHQ2-XvUG3QKct5kwamE.jpg"
                alt="Urban Planning"
              />
              <div class="project-overlay">
                <span class="project-category">Urban Planning</span>
                <a href="#project-4" class="btn-view-project">View Project</a>
              </div>
            </div>
            <div class="project-content">
              <h3>Sustainable Urban Transportation Planning</h3>
              <p>
                Researching efficient transportation network designs that
                minimize environmental impact while optimizing accessibility and
                urban mobility.
              </p>
              <div class="project-meta">
                <div class="meta-item">
                  <i class="fas fa-calendar-alt"></i> 2024-2026
                </div>
                <div class="meta-item">
                  <i class="fas fa-user"></i>  HATANGIMANA F.
                </div>
              </div>
            </div>
          </div>

          <!-- Project 5 -->
          <div
            class="project-card"
            data-category="geospatial"
            data-aos="fade-up"
            data-aos-delay="400"
          >
            <div class="project-image">
              <img
                src="../images/9c_NLZ75_JAwFH7mjiGB_.jpg"
                alt="AI in Mapping"
              />
              <div class="project-overlay">
                <span class="project-category">Geospatial</span>
                <a href="#project-5" class="btn-view-project">View Project</a>
              </div>
            </div>
            <div class="project-content">
              <h3>AI-Powered Feature Extraction from Satellite Imagery</h3>
              <p>
                Using deep learning techniques to automatically identify and
                extract features like buildings, roads, and vegetation from
                high-resolution satellite images.
              </p>
              <div class="project-meta">
                <div class="meta-item">
                  <i class="fas fa-calendar-alt"></i> 2023-2025
                </div>
                <div class="meta-item">
                  <i class="fas fa-user"></i>  HATANGIMANA F.
                </div>
              </div>
            </div>
          </div>

          <!-- Project 6 -->
          <div
            class="project-card"
            data-category="environmental"
            data-aos="fade-up"
            data-aos-delay="500"
          >
            <div class="project-image">
              <img
                src="../images/9ymlPUvjUSWPlrk-qrJ2k.jpg"
                alt="Climate Change Monitoring"
              />
              <div class="project-overlay">
                <span class="project-category">Environmental</span>
                <a href="#project-6" class="btn-view-project">View Project</a>
              </div>
            </div>
            <div class="project-content">
              <h3>Climate Change Impact Assessment Toolkit</h3>
              <p>
                Creating tools for analyzing and visualizing the effects of
                climate change on land use, vegetation health, and water
                resources over time.
              </p>
              <div class="project-meta">
                <div class="meta-item">
                  <i class="fas fa-calendar-alt"></i> 2024-2025
                </div>
                <div class="meta-item">
                  <i class="fas fa-user"></i>  HATANGIMANA F.
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="projects-pagination" data-aos="fade-up">
          <button class="load-more-btn">
            <span>Load More Projects</span>
            <i class="fas fa-arrow-down"></i>
          </button>
        </div>
      </div>
    </section>

    <section class="research-collaboration">
      <div class="container">
        <div class="collaboration-content" data-aos="fade-up">
          <h2>Research Collaboration</h2>
          <p>
            We welcome partnerships with academic institutions, industry
            leaders, and government agencies to advance our research goals and
            create meaningful impact. Our collaborative approach allows us to
            combine expertise and resources to tackle complex challenges in
            surveying, mapping, and environmental management.
          </p>
          <div class="collaboration-stats">
            <div class="collab-item" data-aos="fade-up" data-aos-delay="100">
              <i class="fas fa-university"></i>
              <h4>Academic Partners</h4>
              <p>Collaborations with universities and research institutes</p>
            </div>
            <div class="collab-item" data-aos="fade-up" data-aos-delay="200">
              <i class="fas fa-industry"></i>
              <h4>Industry Connections</h4>
              <p>
                Partnerships with leading companies in geospatial technology
              </p>
            </div>
            <div class="collab-item" data-aos="fade-up" data-aos-delay="300">
              <i class="fas fa-globe-africa"></i>
              <h4>International Network</h4>
              <p>Global connections with researchers and practitioners</p>
            </div>
          </div>
          <div class="collaboration-action">
            <a href="./contact.php" class="btn-primary"
              >Explore Collaboration Opportunities</a
            >
          </div>
        </div>
      </div>
    </section>

    <?php include 'includes/footer.php'; ?>

    <!-- Project Modal Templates -->
    <div class="modal" id="project-1">
      <div class="modal-content">
        <span class="close-modal">&times;</span>
        <div class="modal-body">
          <div class="modal-header">
            <span class="project-category"
              ><i class="fas fa-satellite"></i> Remote Sensing</span
            >
            <h2>Remote Sensing Applications for Land Cover Mapping</h2>
            <div class="project-meta">
              <span><i class="fas fa-calendar-alt"></i> 2024-2025</span>
              <span><i class="fas fa-user"></i>  HATANGIMANA F.</span>
              <span><i class="fas fa-map-marker-alt"></i> Kigali, Rwanda</span>
            </div>
          </div>
          <div class="modal-gallery">
            <div class="gallery-main">
              <img
                src="../images/9c_NLZ75_JAwFH7mjiGB_.jpg"
                alt="Project Main Image"
              />
            </div>
            <div class="gallery-thumbs">
              <img
                src="../images/9c_NLZ75_JAwFH7mjiGB_.jpg"
                alt="Thumbnail 1"
                class="active"
              />
              <img
                src="../images/9ymlPUvjUSWPlrk-qrJ2k.jpg"
                alt="Thumbnail 2"
              />
              <img
                src="../images/bhHQ2-XvUG3QKct5kwamE.jpg"
                alt="Thumbnail 3"
              />
              <img
                src="../images/rC9AvGLJMT6thyTyTxB--.jpg"
                alt="Thumbnail 4"
              />
            </div>
          </div>
          <div class="project-content">
            <div class="content-section">
              <h3><i class="fas fa-bullseye"></i> Project Objectives</h3>
              <ul>
                <li>Develop automated land cover classification algorithms</li>
                <li>Implement machine learning for feature detection</li>
                <li>Create accurate mapping of vegetation changes</li>
                <li>Establish monitoring protocols for land use changes</li>
              </ul>
            </div>
            <div class="content-section">
              <h3><i class="fas fa-tools"></i> Methodology</h3>
              <p>
                Our research employs cutting-edge remote sensing techniques
                combined with machine learning algorithms to analyze
                multi-spectral satellite imagery. The process includes:
              </p>
              <ul>
                <li>Data acquisition from multiple satellite sources</li>
                <li>Pre-processing and atmospheric correction</li>
                <li>Feature extraction and classification</li>
                <li>Accuracy assessment and validation</li>
              </ul>
            </div>
            <div class="content-section">
              <h3><i class="fas fa-chart-line"></i> Key Findings</h3>
              <div class="findings-grid">
                <div class="finding-card">
                  <i class="fas fa-check-circle"></i>
                  <h4>95% Accuracy</h4>
                  <p>In land cover classification</p>
                </div>
                <div class="finding-card">
                  <i class="fas fa-clock"></i>
                  <h4>60% Faster</h4>
                  <p>Processing time reduction</p>
                </div>
                <div class="finding-card">
                  <i class="fas fa-map"></i>
                  <h4>1000 km²</h4>
                  <p>Area analyzed</p>
                </div>
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <div class="tags">
              <span><i class="fas fa-tag"></i> Remote Sensing</span>
              <span><i class="fas fa-tag"></i> Machine Learning</span>
              <span><i class="fas fa-tag"></i> Land Cover</span>
              <span><i class="fas fa-tag"></i> GIS</span>
            </div>
            <div class="action-buttons">
              <a href="#" class="btn-download"
                ><i class="fas fa-download"></i> Download Report</a
              >
              <a href="./contact.php" class="btn-contact"
                ><i class="fas fa-envelope"></i> Contact Research Team</a
              >
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- Add remaining project modals -->
    <div class="modal" id="project-2">
      <div class="modal-content">
        <span class="close-modal">&times;</span>
        <div class="modal-body">
          <div class="modal-header">
            <span class="project-category"
              ><i class="fas fa-map-marked-alt"></i> Geospatial</span
            >
            <h2>Advanced 3D Terrain Modeling Using Drone Photogrammetry</h2>
            <div class="project-meta">
              <span><i class="fas fa-calendar-alt"></i> 2023-2025</span>
              <span><i class="fas fa-user"></i>  HATANGIMANA F.</span>
              <span><i class="fas fa-map-marker-alt"></i> Kigali, Rwanda</span>
            </div>
          </div>
          <!-- Add similar content structure as project-1 modal -->
        </div>
      </div>
    </div>

    <!-- Add modals for projects 3-6 following same structure -->

    <!-- Loading Overlay -->
    <div class="loading-overlay">
      <div class="spinner">
        <i class="fas fa-circle-notch fa-spin"></i>
      </div>
    </div>

    <!-- JavaScript Libraries -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.js"></script>
    <script src="../js/script.js"></script>
    <script src="../js/researches.js"></script>
  </body>
</php>
