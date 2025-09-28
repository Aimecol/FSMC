<?php
// Include database configuration
require_once 'config/database.php';

// Fetch data from database
$services = dbGetRows("SELECT * FROM services WHERE status = 'active' ORDER BY sort_order ASC, created_at DESC LIMIT 3");
$products = dbGetRows("SELECT * FROM products WHERE status = 'active' ORDER BY sort_order ASC, created_at DESC LIMIT 6");
$trainings = dbGetRows("SELECT * FROM training_programs WHERE status = 'active' ORDER BY sort_order ASC, created_at DESC LIMIT 3");
$researches = dbGetRows("SELECT * FROM research_projects WHERE status IN ('ongoing', 'completed', 'published') ORDER BY featured DESC, created_at DESC LIMIT 3");
$companySettings = getCompanySettings();
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title><?php echo getSetting('site_title', 'Fair Surveying & Mapping Ltd'); ?></title>
    <meta name="description" content="<?php echo getSetting('site_description', 'Professional surveying and mapping services in Rwanda'); ?>">
    <link rel="icon" type="image/svg+xml" href="./images/logo.png" />
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css"
    />
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.css"
    />
    <link rel="stylesheet" href="css/style.css" />
    <link rel="stylesheet" href="css/home-main.css" />
  </head>
  <body>
    <header class="header">
      <div class="top-bar">
        <div class="top-bar-content">
          <div class="contact-info">
            <a href="tel:<?php echo str_replace(' ', '', getSetting('company_phone', '0788331697')); ?>"><i class="fas fa-phone"></i> <?php echo getSetting('company_phone', '0788331697'); ?></a>
            <a href="mailto:<?php echo getSetting('company_email', 'fsamcompanyltd@gmail.com'); ?>"
              ><i class="fas fa-envelope"></i> <?php echo getSetting('company_email', 'fsamcompanyltd@gmail.com'); ?></a
            >
          </div>
          <div>
            <span
              ><i class="fas fa-user-tie"></i> <?php echo getSetting('surveyor_name', 'HATANGIMANA Fulgence'); ?>, Surveyor
              code: <?php echo getSetting('surveyor_code', 'LS00280'); ?></span
            >
          </div>
        </div>
      </div>
      <div class="main-header">
        <div class="logo">
          <div class="logo-icon">
            <svg
              class="logo-svg"
              xmlns="http://www.w3.org/2000/svg"
              viewBox="0 0 500 500"
            >
              <!-- Background Circle -->
              <circle
                cx="250"
                cy="250"
                r="245"
                fill="#f4f6f7"
                stroke="#1a5276"
                stroke-width="10"
              />

              <!-- Inner Circle -->
              <circle
                cx="250"
                cy="250"
                r="210"
                fill="#ffffff"
                stroke="#2e86c1"
                stroke-width="5"
              />

              <!-- Compass Rose -->
              <g transform="translate(250, 250)">
                <!-- Compass Points -->
                <line
                  x1="0"
                  y1="-150"
                  x2="0"
                  y2="-120"
                  stroke="#2c3e50"
                  stroke-width="6"
                />
                <line
                  x1="150"
                  y1="0"
                  x2="120"
                  y2="0"
                  stroke="#2c3e50"
                  stroke-width="6"
                />
                <line
                  x1="0"
                  y1="150"
                  x2="0"
                  y2="120"
                  stroke="#2c3e50"
                  stroke-width="6"
                />
                <line
                  x1="-150"
                  y1="0"
                  x2="-120"
                  y2="0"
                  stroke="#2c3e50"
                  stroke-width="6"
                />

                <!-- Compass Diagonals -->
                <line
                  x1="106"
                  y1="-106"
                  x2="85"
                  y2="-85"
                  stroke="#2c3e50"
                  stroke-width="4"
                />
                <line
                  x1="106"
                  y1="106"
                  x2="85"
                  y2="85"
                  stroke="#2c3e50"
                  stroke-width="4"
                />
                <line
                  x1="-106"
                  y1="106"
                  x2="-85"
                  y2="85"
                  stroke="#2c3e50"
                  stroke-width="4"
                />
                <line
                  x1="-106"
                  y1="-106"
                  x2="-85"
                  y2="-85"
                  stroke="#2c3e50"
                  stroke-width="4"
                />

                <!-- Compass Outer Circle -->
                <circle
                  cx="0"
                  cy="0"
                  r="150"
                  fill="none"
                  stroke="#2c3e50"
                  stroke-width="3"
                />

                <!-- Compass Inner Circle -->
                <circle
                  cx="0"
                  cy="0"
                  r="120"
                  fill="none"
                  stroke="#2c3e50"
                  stroke-width="2"
                />

                <!-- Compass Needle -->
                <path
                  d="M0,-110 L15,-30 L0,-15 L-15,-30 Z"
                  fill="#f39c12"
                  stroke="#e67e22"
                  stroke-width="2"
                />
                <path
                  d="M0,110 L15,30 L0,15 L-15,30 Z"
                  fill="#1a5276"
                  stroke="#2e86c1"
                  stroke-width="2"
                />

                <!-- Center Point -->
                <circle
                  cx="0"
                  cy="0"
                  r="15"
                  fill="#2c3e50"
                  stroke="#2e86c1"
                  stroke-width="2"
                />
              </g>

              <!-- Map and Surveying Elements -->
              <g transform="translate(250, 250) scale(0.6)">
                <!-- Land Contour Lines -->
                <path
                  d="M-150,-50 Q-100,-80 -50,-60 T50,-70 T150,-50"
                  fill="none"
                  stroke="#2e86c1"
                  stroke-width="3"
                />
                <path
                  d="M-150,-30 Q-100,-60 -50,-40 T50,-50 T150,-30"
                  fill="none"
                  stroke="#2e86c1"
                  stroke-width="3"
                />
                <path
                  d="M-150,-10 Q-100,-40 -50,-20 T50,-30 T150,-10"
                  fill="none"
                  stroke="#2e86c1"
                  stroke-width="3"
                />
                <path
                  d="M-150,10 Q-100,-20 -50,0 T50,-10 T150,10"
                  fill="none"
                  stroke="#2e86c1"
                  stroke-width="3"
                />
                <path
                  d="M-150,30 Q-100,0 -50,20 T50,10 T150,30"
                  fill="none"
                  stroke="#2e86c1"
                  stroke-width="3"
                />
                <path
                  d="M-150,50 Q-100,20 -50,40 T50,30 T150,50"
                  fill="none"
                  stroke="#2e86c1"
                  stroke-width="3"
                />

                <!-- Surveying Marker -->
                <path
                  d="M70,-10 L70,80 L90,80 L80,95 L70,80 L70,120"
                  fill="none"
                  stroke="#f39c12"
                  stroke-width="5"
                />

                <!-- Triangulation Points -->
                <circle
                  cx="-70"
                  cy="70"
                  r="8"
                  fill="#f39c12"
                  stroke="#e67e22"
                  stroke-width="2"
                />
                <circle
                  cx="0"
                  cy="-70"
                  r="8"
                  fill="#f39c12"
                  stroke="#e67e22"
                  stroke-width="2"
                />
                <circle
                  cx="70"
                  cy="70"
                  r="8"
                  fill="#f39c12"
                  stroke="#e67e22"
                  stroke-width="2"
                />

                <!-- Triangulation Lines -->
                <line
                  x1="-70"
                  y1="70"
                  x2="0"
                  y2="-70"
                  stroke="#2c3e50"
                  stroke-width="2"
                  stroke-dasharray="5,5"
                />
                <line
                  x1="0"
                  y1="-70"
                  x2="70"
                  y2="70"
                  stroke="#2c3e50"
                  stroke-width="2"
                  stroke-dasharray="5,5"
                />
                <line
                  x1="70"
                  y1="70"
                  x2="-70"
                  y2="70"
                  stroke="#2c3e50"
                  stroke-width="2"
                  stroke-dasharray="5,5"
                />
              </g>

              <!-- Company Name -->
              <path
                id="textPath"
                d="M250,450 a215,210 0 1,1 0.1,0"
                fill="none"
              />
              <text
                font-family="Arial, sans-serif"
                font-weight="bold"
                font-size="22"
                fill="#1a5276"
                text-anchor="middle"
              >
                <textPath href="#textPath" startOffset="50%">
                  <?php echo strtoupper(getSetting('company_name', 'FAIR SURVEYING & MAPPING LTD')); ?>
                </textPath>
              </text>

              <!-- Tagline -->
              <path
                id="textPath2"
                d="M250,450 a-215,2 0 1,1 0.1,0"
                fill="none"
              />
              <text
                font-family="Arial, sans-serif"
                font-size="14"
                fill="#2c3e50"
                text-anchor="middle"
              >
                <textPath href="#textPath2" startOffset="50%">
                  <?php echo getSetting('company_tagline', 'Reliable | Professional | Expert Solutions'); ?>
                </textPath>
              </text>

              <!-- Establishment Year -->
              <text
                x="250"
                y="100"
                font-family="Arial, sans-serif"
                font-weight="bold"
                font-size="16"
                fill="#1a5276"
                text-anchor="middle"
              >
                EST. <?php echo getSetting('establishment_year', '2023'); ?>
              </text>
            </svg>
          </div>
          <div class="logo-text">
            <div class="logo-name"><?php echo strtoupper(getSetting('company_name', 'FAIR SURVEYING & MAPPING LTD')); ?></div>
            <div class="logo-tagline">
              <?php echo getSetting('company_tagline', 'Reliable | Professional | Expert Solutions'); ?>
            </div>
          </div>
        </div>
        <button class="nav-toggle" id="navToggle">
          <i class="fas fa-bars"></i>
        </button>
        <nav class="navigation" id="navigation">
          <ul class="nav-links">
            <li><a href="./index.php">Home</a></li>
            <li><a href="./pages/services.php">Service</a></li>
            <li><a href="./pages/products.php">Product</a></li>
            <li><a href="./pages/researches.php">Research</a></li>
            <li><a href="./pages/training.php">Training</a></li>
            <li><a href="./pages/about.php">About</a></li>
            <li><a href="./pages/contact.php">Contact</a></li>
          </ul>
        </nav>
      </div>
    </header>

    <section
      class="hero"
      style="
        position: relative;
        background-image: url('./images/bhHQ2-XvUG3QKct5kwamE.jpg');
        background-size: cover;
        background-attachment: fixed;
        background-position: center;
        background-repeat: no-repeat;
      "
    >
      <!-- Dark overlay to improve text visibility -->
      <div
        style="
          position: absolute;
          top: 0;
          left: 0;
          width: 100%;
          height: 100%;
          background-color: rgba(0, 0, 0, 0.5);
        "
      ></div>

      <div
        class="container hero-content"
        style="position: relative; z-index: 2; color: white"
      >
        <h1 class="hero-title">Precision Surveying & Mapping Solutions</h1>
        <p class="hero-subtitle">
          Professional land surveying, mapping, and technical services with
          unmatched accuracy and reliability
        </p>
        <div class="hero-buttons">
          <a href="./pages/services.php" class="btn-primary"
            ><i class="fas fa-cogs"></i> Our Services</a
          >
          <a href="./pages/contact.php" class="btn-secondary"
            ><i class="fas fa-phone"></i> Contact Us</a
          >
        </div>
      </div>
    </section>

    <section id="services" class="services">
      <div class="container">
        <div class="services-grid">
          <?php foreach ($services as $service): 
            $languages = json_decode($service['languages'], true) ?: [];
          ?>
          <div class="service-card">
            <div class="service-card-header">
              <h3><?php echo htmlspecialchars($service['title']); ?></h3>
              <i class="<?php echo htmlspecialchars($service['icon'] ?: 'fas fa-cogs'); ?>"></i>
            </div>
            <div class="service-card-content">
              <?php if (!empty($languages)): ?>
              <div class="service-languages">
                <?php foreach ($languages as $language): ?>
                <span class="language-tag">
                  <i class="fas fa-globe"></i> <?php echo htmlspecialchars($language); ?>
                </span>
                <?php endforeach; ?>
              </div>
              <?php endif; ?>
              
              <?php if (!empty($service['short_description'])): ?>
              <p><?php echo htmlspecialchars($service['short_description']); ?></p>
              <?php endif; ?>
              
              <p><?php echo nl2br(htmlspecialchars(substr($service['description'], 0, 200))); ?>...</p>
              
              <div class="service-cta">
                <a href="./pages/service_view_more.php?slug=<?php echo urlencode($service['slug']); ?>" class="btn-service">
                  <i class="fas fa-info-circle"></i> Learn More
                </a>
              </div>
            </div>
          </div>
          <?php endforeach; ?>
          
          <?php if (empty($services)): ?>
          <div class="service-card">
            <div class="service-card-content">
              <p>No services available at the moment.</p>
            </div>
          </div>
          <?php endif; ?>
        </div>
      </div>
    </section>

    <section class="products-section">
      <div class="container">
        <div class="section-header" data-aos="fade-up">
          <h2>Our Products</h2>
          <p>
            Experience precision and excellence with our premium surveying
            equipment and software solutions designed for professionals.
          </p>
        </div>

        <div class="products-slider" style="padding-left: 0; padding-right: 0;">
          <?php foreach ($products as $product): 
            $specifications = json_decode($product['specifications'], true) ?: [];
          ?>
          <div class="product-card" data-category="<?php echo htmlspecialchars($product['category']); ?>">
            <div class="product-image">
              <img src="<?php echo getFileUrl($product['image']); ?>" alt="<?php echo htmlspecialchars($product['title']); ?>">
            </div>
            <div class="product-details">
              <h3 class="product-title"><?php echo htmlspecialchars($product['title']); ?></h3>
              <p class="product-description">
                <?php echo htmlspecialchars($product['short_description'] ?: substr($product['description'], 0, 100) . '...'); ?>
              </p>
              
              <?php if (!empty($specifications)): ?>
              <ul class="product-features">
                <?php foreach (array_slice($specifications, 0, 3) as $spec): ?>
                <li>
                  <i class="fas fa-check-circle"></i> <?php echo htmlspecialchars($spec); ?>
                </li>
                <?php endforeach; ?>
              </ul>
              <?php endif; ?>
              
              <div class="product-actions">
                <button class="btn-details" data-product="<?php echo htmlspecialchars($product['slug']); ?>">
                  View Details
                </button>
                <button class="btn-inquire" data-product="<?php echo htmlspecialchars($product['slug']); ?>">
                  Inquire
                </button>
              </div>
            </div>
          </div>
          <?php endforeach; ?>
          
          <?php if (empty($products)): ?>
          <div class="product-card">
            <div class="product-details">
              <p>No products available at the moment.</p>
            </div>
          </div>
          <?php endif; ?>
        </div>

        <div class="slider-controls">
          <button class="prev-btn" id="prevProduct">
            <i class="fas fa-chevron-left"></i>
          </button>
          <button class="next-btn" id="nextProduct">
            <i class="fas fa-chevron-right"></i>
          </button>
        </div>
      </div>
    </section>

    <section id="trainings" class="trainings">
      <div class="container">
        <div class="section-title" data-aos="fade-up">
          <h2>Technical Training</h2>
          <p>
            Enhance your skills with our specialized technical training programs
            taught by industry experts.
          </p>
        </div>
        <div class="courses-grid active" id="surveying-courses">
          <?php foreach ($trainings as $index => $training): 
            $curriculum = json_decode($training['curriculum'], true) ?: [];
            $features = json_decode($training['features'], true) ?: [];
          ?>
          <div class="course-card" data-aos="fade-up" data-aos-delay="<?php echo $index * 100; ?>">
            <div class="course-image">
              <img
                src="<?php echo getFileUrl($training['image']); ?>"
                alt="<?php echo htmlspecialchars($training['title']); ?>"
              />
              <div class="course-level <?php echo htmlspecialchars($training['level']); ?>"><?php echo ucfirst($training['level']); ?></div>
            </div>
            <div class="course-content">
              <h3><?php echo htmlspecialchars($training['title']); ?></h3>
              <div class="course-meta">
                <?php if (!empty($training['duration'])): ?>
                <span><i class="far fa-clock"></i> <?php echo htmlspecialchars($training['duration']); ?></span>
                <?php endif; ?>
                <span><i class="fas fa-users"></i> Max <?php echo $training['max_students']; ?> Students</span>
                <span><i class="fas fa-globe"></i> <?php echo htmlspecialchars($training['language']); ?></span>
              </div>
              <p>
                <?php echo htmlspecialchars($training['short_description'] ?: substr($training['description'], 0, 120) . '...'); ?>
              </p>
              
              <?php if (!empty($features)): ?>
              <ul class="course-features">
                <?php foreach (array_slice($features, 0, 3) as $feature): ?>
                <li>
                  <i class="fas fa-check-circle"></i> <?php echo htmlspecialchars($feature); ?>
                </li>
                <?php endforeach; ?>
              </ul>
              <?php endif; ?>
              
              <div class="course-footer">
                <span class="course-price"><?php echo formatPrice($training['price']); ?></span>
                <a href="./pages/training_view_more.php?slug=<?php echo urlencode($training['slug']); ?>" class="btn-enroll">Enroll Now</a>
              </div>
            </div>
          </div>
          <?php endforeach; ?>
          
          <?php if (empty($trainings)): ?>
          <div class="course-card">
            <div class="course-content">
              <p>No training programs available at the moment.</p>
            </div>
          </div>
          <?php endif; ?>
        </div>
      </div>
    </section>

    <section id="researches" class="researches">
      <div class="container">
        <div class="section-title" data-aos="fade-up">
          <h2>Research Support</h2>
          <p>
            Discover our latest research projects and professional support
            services for academic and industrial research.
          </p>
        </div>
        <div class="projects-grid">
          <?php foreach ($researches as $index => $research): 
            $authors = json_decode($research['authors'], true) ?: [];
            $keywords = json_decode($research['keywords'], true) ?: [];
          ?>
          <div
            class="project-card"
            data-category="<?php echo htmlspecialchars($research['category'] ?: 'general'); ?>"
            data-aos="fade-up"
            data-aos-delay="<?php echo $index * 100; ?>"
          >
            <div class="project-image">
              <img
                src="<?php echo getFileUrl($research['image']); ?>"
                alt="<?php echo htmlspecialchars($research['title']); ?>"
              />
              <div class="project-overlay">
                <span class="project-category"><?php echo ucfirst(str_replace('_', ' ', $research['category'] ?: 'General')); ?></span>
                <a href="./pages/research_view_more.php?slug=<?php echo urlencode($research['slug']); ?>" class="btn-view-project">View Project</a>
              </div>
            </div>
            <div class="project-content">
              <h3><?php echo htmlspecialchars($research['title']); ?></h3>
              <p>
                <?php echo htmlspecialchars(substr($research['abstract'] ?: $research['description'], 0, 150) . '...'); ?>
              </p>
              <div class="project-meta">
                <?php if (!empty($research['publication_date'])): ?>
                <div class="meta-item">
                  <i class="fas fa-calendar-alt"></i> <?php echo date('Y', strtotime($research['publication_date'])); ?>
                </div>
                <?php endif; ?>
                
                <?php if (!empty($authors)): ?>
                <div class="meta-item">
                  <i class="fas fa-user"></i> <?php echo htmlspecialchars(implode(', ', array_slice($authors, 0, 2))); ?>
                  <?php if (count($authors) > 2): ?>et al.<?php endif; ?>
                </div>
                <?php endif; ?>
              </div>
            </div>
          </div>
          <?php endforeach; ?>
          
          <?php if (empty($researches)): ?>
          <div class="project-card">
            <div class="project-content">
              <p>No research projects available at the moment.</p>
            </div>
          </div>
          <?php endif; ?>
        </div>
      </div>
    </section>

    <section class="cta">
      <div class="container">
        <h2 data-aos="fade-up">Ready to Start Your Project?</h2>
        <p data-aos="fade-up" data-aos-delay="50">
          Contact us today to discuss your surveying and mapping needs. Our
          expert team is ready to provide you with reliable and professional
          solutions.
        </p>
        <div class="cta-buttons" data-aos="fade-up" data-aos-delay="100">
          <a href="./pages/contact.php" class="btn-primary"
            ><i class="fas fa-phone-alt"></i> Contact Us</a
          >
          <a href="./pages/about.php" class="btn-secondary"
            ><i class="fas fa-info-circle"></i> Learn More</a
          >
        </div>
      </div>
    </section>

    <footer id="contact" class="footer">
      <div class="container">
        <div class="footer-content">
          <div class="footer-column">
            <h3>About Us</h3>
            <p style="color: #aaa; margin-bottom: 20px; line-height: 1.6">
              <?php echo getSetting('company_name', 'Fair Surveying & Mapping Ltd'); ?> provides reliable,
              professional, and expert solutions for all your surveying and
              mapping needs.
            </p>
            <div class="social-links">
              <a href="<?php echo getSetting('facebook_url', '#'); ?>" class="social-link"
                ><i class="fab fa-facebook-f"></i
              ></a>
              <a href="<?php echo getSetting('twitter_url', '#'); ?>" class="social-link"><i class="fab fa-twitter"></i></a>
              <a href="<?php echo getSetting('linkedin_url', '#'); ?>" class="social-link"
                ><i class="fab fa-linkedin-in"></i
              ></a>
              <a href="<?php echo getSetting('instagram_url', '#'); ?>" class="social-link"
                ><i class="fab fa-instagram"></i
              ></a>
            </div>
          </div>
          <div class="footer-column">
            <h3>Our Services</h3>
            <ul class="footer-links">
              <li>
                <a href="#"
                  ><i class="fas fa-chevron-right"></i> Land Surveying</a
                >
              </li>
              <li>
                <a href="#"
                  ><i class="fas fa-chevron-right"></i> Land Subdivision</a
                >
              </li>
              <li>
                <a href="#"
                  ><i class="fas fa-chevron-right"></i> Building Permits</a
                >
              </li>
              <li>
                <a href="#"
                  ><i class="fas fa-chevron-right"></i> Environmental
                  Assessment</a
                >
              </li>
              <li>
                <a href="#"
                  ><i class="fas fa-chevron-right"></i> Technical Training</a
                >
              </li>
            </ul>
          </div>
          <div class="footer-column">
            <h3>Quick Links</h3>
            <ul class="footer-links">
              <li>
                <a href="./index.php"
                  ><i class="fas fa-chevron-right"></i> Home</a
                >
              </li>
              <li>
                <a href="./pages/services.php"
                  ><i class="fas fa-chevron-right"></i> Services</a
                >
              </li>
              <li>
                <a href="./pages/products.php"
                  ><i class="fas fa-chevron-right"></i> Products</a
                >
              </li>
              <li>
                <a href="./pages/services.php?training"
                  ><i class="fas fa-chevron-right"></i> Trainings</a
                >
              </li>
              <li>
                <a href="./pages/researches.php"
                  ><i class="fas fa-chevron-right"></i> Research</a
                >
              </li>
            </ul>
          </div>
          <div class="footer-column footer-contact">
            <h3>Contact Us</h3>
            <p><i class="fas fa-map-marker-alt"></i> <?php echo getSetting('company_address', 'Kigali, Rwanda'); ?></p>
            <p><i class="fas fa-phone"></i> <?php echo getSetting('company_phone', '+250 788 331 697'); ?></p>
            <p><i class="fas fa-envelope"></i> <?php echo getSetting('company_email', 'fsamcompanyltd@gmail.com'); ?></p>
            <p><i class="fas fa-user-tie"></i> Surveyor Code: <?php echo getSetting('surveyor_code', 'LS00280'); ?></p>
          </div>
        </div>
      </div>
      <div class="footer-bottom">
        <div class="copyright">
          &copy; <?php echo date('Y'); ?> <?php echo getSetting('company_name', 'Fair Surveying & Mapping Ltd'); ?>. All Rights Reserved.
        </div>
        <div class="footer-nav">
          <a href="#">Privacy Policy</a>
          <a href="#">Terms of Service</a>
        </div>
      </div>
    </footer>

    <!-- Product Detail Modal -->
    <div class="modal" id="productDetailModal">
      <div class="modal-content">
        <span class="close-modal" id="closeDetailModal">&times;</span>
        <div class="modal-product-image" id="modalProductImage">
          <i class="fas fa-broadcast-tower"></i>
        </div>
        <h3 id="modalProductTitle">Product Title</h3>
        <p id="modalProductDescription">Product Description</p>
        <div class="product-specs">
          <h4>Specifications</h4>
          <div class="spec-item">
            <span class="spec-name">Manufacturer</span>
            <span class="spec-value" id="specManufacturer">-</span>
          </div>
          <div class="spec-item">
            <span class="spec-name">Model</span>
            <span class="spec-value" id="specModel">-</span>
          </div>
          <div class="spec-item">
            <span class="spec-name">Warranty</span>
            <span class="spec-value" id="specWarranty">-</span>
          </div>
          <div class="spec-item">
            <span class="spec-name">Support</span>
            <span class="spec-value" id="specSupport">-</span>
          </div>
        </div>
        <div class="product-actions" style="margin-top: 20px">
          <button class="btn-inquire" id="modalInquireBtn">
            Inquire About This Product
          </button>
        </div>
      </div>
    </div>

    <!-- Inquiry Modal -->
    <div class="modal" id="inquiryModal">
      <div class="modal-content">
        <span class="close-modal" id="closeInquiryModal">&times;</span>
        <h3>Product Inquiry</h3>
        <p>
          Please fill out the form below to inquire about this product. Our team
          will get back to you shortly.
        </p>
        <form class="inquiry-form" id="inquiryForm">
          <div class="form-group">
            <label for="name">Full Name</label>
            <input type="text" id="name" name="name" required />
          </div>
          <div class="form-group">
            <label for="email">Email Address</label>
            <input type="email" id="email" name="email" required />
          </div>
          <div class="form-group">
            <label for="phone">Phone Number</label>
            <input type="tel" id="phone" name="phone" required />
          </div>
          <div class="form-group">
            <label for="product">Product of Interest</label>
            <input type="text" id="product" name="product" readonly />
          </div>
          <div class="form-group">
            <label for="message">Message</label>
            <textarea id="message" name="message" required></textarea>
          </div>
          <button type="submit" class="form-submit">Submit Inquiry</button>
        </form>
      </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.js"></script>
    <script src="js/script.js"></script>
    <script>
      document.addEventListener("DOMContentLoaded", function () {
        // Initialize AOS
        AOS.init({
          duration: 500,
          easing: "ease-in-out",
          once: true,
        });

        // Related Products Slider
        const productsSlider = document.querySelector(".products-slider");
        const prevBtn = document.getElementById("prevProduct");
        const nextBtn = document.getElementById("nextProduct");

        if (productsSlider && prevBtn && nextBtn) {
          const cardWidth = 280 + 32; // card width + gap
          const visibleWidth = productsSlider.clientWidth;
          const scrollAmount = cardWidth * 2; // Scroll 2 cards at a time

          // Initial state
          updateSliderButtons();

          prevBtn.addEventListener("click", function () {
            productsSlider.scrollBy({
              left: -scrollAmount,
              behavior: "smooth",
            });

            setTimeout(updateSliderButtons, 500);
          });

          nextBtn.addEventListener("click", function () {
            productsSlider.scrollBy({
              left: scrollAmount,
              behavior: "smooth",
            });

            setTimeout(updateSliderButtons, 500);
          });

          productsSlider.addEventListener("scroll", function () {
            updateSliderButtons();
          });

          function updateSliderButtons() {
            const scrollPosition = productsSlider.scrollLeft;
            const maxScrollLeft =
              productsSlider.scrollWidth - productsSlider.clientWidth;

            // Enable/disable buttons based on scroll position
            prevBtn.disabled = scrollPosition <= 0;
            nextBtn.disabled = scrollPosition >= maxScrollLeft - 5; // 5px tolerance
          }

          // Update on resize
          window.addEventListener("resize", function () {
            updateSliderButtons();
          });
        }

        // Product Details Modal
        const detailModal = document.getElementById("productDetailModal");
        const detailButtons = document.querySelectorAll(".btn-details");
        const closeDetailModal = document.getElementById("closeDetailModal");
        const modalProductTitle = document.getElementById("modalProductTitle");
        const modalProductDescription = document.getElementById(
          "modalProductDescription"
        );
        const modalProductImage = document.getElementById("modalProductImage");
        const specManufacturer = document.getElementById("specManufacturer");
        const specModel = document.getElementById("specModel");
        const specWarranty = document.getElementById("specWarranty");
        const specSupport = document.getElementById("specSupport");
        const modalInquireBtn = document.getElementById("modalInquireBtn");

        // Product data
        const productData = {
          "total-station": {
            title: "Total Station Professional",
            description:
              "High-precision surveying instrument for accurate measurements in the field. Ideal for construction layout, topographic surveys, and boundary determinations.",
            icon: "fa-broadcast-tower",
            manufacturer: "Trimble",
            model: "SX10",
            warranty: "2 Years",
            support: "24/7 Technical Support",
          },
          "rtk-gps": {
            title: "RTK GPS System",
            description:
              "Real-time kinematic GPS system for centimeter-level positioning accuracy. Perfect for detailed mapping and staking applications.",
            icon: "fa-satellite-dish",
            manufacturer: "Leica Geosystems",
            model: "GS18 T",
            warranty: "3 Years",
            support: "On-site Support Available",
          },
          "digital-level": {
            title: "Digital Level",
            description:
              "Precise digital leveling instrument for elevation measurements. Streamline your workflow with automated calculations and data recording.",
            icon: "fa-ruler-combined",
            manufacturer: "Topcon",
            model: "DL-500",
            warranty: "1 Year",
            support: "Email & Phone Support",
          },
          "equipment-training": {
            title: "Surveying Equipment Training",
            description:
              "Comprehensive training materials for total station and GPS equipment. Learn how to operate and maintain your surveying instruments effectively.",
            icon: "fa-chalkboard-teacher",
            manufacturer: "Banner Fair",
            model: "Version 2025",
            warranty: "1 Year Updates",
            support: "Q&A Sessions",
          },
          "ai-gis": {
            title: "AI for GIS Analysis",
            description:
              "Training materials for implementing AI in geospatial data analysis. Learn machine learning and deep learning techniques for geospatial applications.",
            icon: "fa-brain",
            manufacturer: "Banner Fair",
            model: "Version 2025",
            warranty: "1 Year Updates",
            support: "Forum Support",
          },
          "remote-sensing": {
            title: "Remote Sensing Analysis",
            description:
              "Comprehensive training on satellite imagery and remote sensing techniques. Master image classification, change detection, and environmental monitoring.",
            icon: "fa-satellite",
            manufacturer: "Banner Fair",
            model: "Version 2025",
            warranty: "1 Year Updates",
            support: "Email Support",
          },
          "starter-bundle": {
            title: "Surveyor Starter Bundle",
            description:
              "Complete package for beginners in land surveying. Includes equipment, software, and training to get you started.",
            icon: "fa-layer-group",
            manufacturer: "Banner Fair",
            model: "Starter Bundle 2025",
            warranty: "2 Years",
            support: "Priority Support",
          },
          "gis-bundle": {
            title: "GIS Professional Bundle",
            description:
              "Advanced GIS software and training for professionals. Comprehensive solution for spatial data analysis and mapping.",
            icon: "fa-cubes",
            manufacturer: "Banner Fair",
            model: "Pro Bundle 2025",
            warranty: "3 Years",
            support: "Premium Support",
          },
        };

        detailButtons.forEach((button) => {
          button.addEventListener("click", () => {
            const productId = button.getAttribute("data-product");
            const product = productData[productId];

            modalProductTitle.textContent = product.title;
            modalProductDescription.textContent = product.description;
            modalProductImage.innerphp = `<i class="fas ${product.icon}"></i>`;
            specManufacturer.textContent = product.manufacturer;
            specModel.textContent = product.model;
            specWarranty.textContent = product.warranty;
            specSupport.textContent = product.support;

            modalInquireBtn.setAttribute("data-product", productId);

            detailModal.style.display = "flex";
          });
        });

        closeDetailModal.addEventListener("click", () => {
          detailModal.style.display = "none";
        });

        // Inquiry Modal
        const inquiryModal = document.getElementById("inquiryModal");
        const inquiryButtons = document.querySelectorAll(".btn-inquire");
        const closeInquiryModal = document.getElementById("closeInquiryModal");
        const inquiryForm = document.getElementById("inquiryForm");
        const productInput = document.getElementById("product");

        inquiryButtons.forEach((button) => {
          button.addEventListener("click", () => {
            const productId = button.getAttribute("data-product");
            const product = productData[productId];

            productInput.value = product.title;

            inquiryModal.style.display = "flex";
          });
        });

        modalInquireBtn.addEventListener("click", () => {
          const productId = modalInquireBtn.getAttribute("data-product");
          const product = productData[productId];

          productInput.value = product.title;

          detailModal.style.display = "none";
          inquiryModal.style.display = "flex";
        });

        closeInquiryModal.addEventListener("click", () => {
          inquiryModal.style.display = "none";
        });

        inquiryForm.addEventListener("submit", (e) => {
          e.preventDefault();

          // Simulate form submission
          alert(
            "Thank you for your inquiry! Our team will contact you shortly."
          );

          inquiryModal.style.display = "none";
          inquiryForm.reset();
        });
      });
    </script>
  </body>
</php>
