<?php
// Include database configuration
require_once './config/database.php';

// Fetch services from database
$services = dbGetRows("SELECT * FROM services WHERE status = 'active' ORDER BY sort_order ASC, created_at DESC");
$companySettings = getCompanySettings();
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Our Services - <?php echo getSetting('company_name', 'Fair Surveying & Mapping Ltd'); ?></title>
    <meta name="description" content="Professional surveying and mapping services by <?php echo getSetting('company_name', 'Fair Surveying & Mapping Ltd'); ?>">
    <link rel="icon" type="image/svg+xml" href="../images/logo.png" />
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css"
    />
    <link rel="stylesheet" href="../css/style.css" />
    <link rel="stylesheet" href="../css/services.css" />
  </head>
  <body>
    <!-- Header Section -->
    <?php include 'includes/header.php'; ?>

    <!-- Main Content -->
    <main class="page-container">
      <!-- Services Hero Section -->
      <section class="services-hero">
        <h1>Our Professional Services</h1>
        <p>
          <?php echo getSetting('company_name', 'Fair Surveying & Mapping Ltd'); ?> provides comprehensive
          surveying, mapping, and technical services with precision and
          expertise. Our team of qualified professionals ensures accuracy and
          excellence in all projects.
        </p>
      </section>

      <!-- Services Section -->
      <section class="services-section">
        <div class="services-grid">
          <?php if (!empty($services)): ?>
            <?php foreach ($services as $service): 
              $languages = json_decode($service['languages'], true) ?: [];
              $features = json_decode($service['features'], true) ?: [];
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
                
                <?php if (!empty($service['price']) && $service['price'] > 0): ?>
                <div class="service-price">
                  <strong>Price: <?php echo formatPrice($service['price']); ?></strong>
                </div>
                <?php endif; ?>
                
                <?php if (!empty($service['duration'])): ?>
                <div class="service-duration">
                  <i class="fas fa-clock"></i> Duration: <?php echo htmlspecialchars($service['duration']); ?>
                </div>
                <?php endif; ?>
                
                <div class="service-cta">
                  <a href="./service_view_more.php?slug=<?php echo urlencode($service['slug']); ?>" class="btn-service">
                    <i class="fas fa-info-circle"></i> Learn More
                  </a>
                </div>
              </div>
            </div>
            <?php endforeach; ?>
          <?php else: ?>
            <div class="service-card">
              <div class="service-card-content">
                <p>No services available at the moment. Please check back later.</p>
              </div>
            </div>
          <?php endif; ?>
        </div>
      </section>


      <!-- Why Choose Us Section -->
      <section class="why-choose-us">
        <h2>Why Choose <?php echo getSetting('company_name', 'Fair Surveying & Mapping Ltd'); ?></h2>
        <div class="advantages">
          <!-- Advantage 1 -->
          <div class="advantage-item">
            <div class="advantage-icon">
              <i class="fas fa-certificate"></i>
            </div>
            <div class="advantage-content">
              <h3>Licensed Professional</h3>
              <p>
                Our lead surveyor holds official certification with Surveyor
                code <?php echo getSetting('surveyor_code', 'LS00280'); ?>, ensuring all work meets legal standards.
              </p>
            </div>
          </div>

          <!-- Advantage 2 -->
          <div class="advantage-item">
            <div class="advantage-icon">
              <i class="fas fa-tools"></i>
            </div>
            <div class="advantage-content">
              <h3>Modern Equipment</h3>
              <p>
                We utilize state-of-the-art surveying and mapping technology for
                precision and efficiency.
              </p>
            </div>
          </div>

          <!-- Advantage 3 -->
          <div class="advantage-item">
            <div class="advantage-icon">
              <i class="fas fa-language"></i>
            </div>
            <div class="advantage-content">
              <h3>Multilingual Services</h3>
              <p>
                We offer services in multiple languages including English,
                Kinyarwanda, and French for better communication.
              </p>
            </div>
          </div>

          <!-- Advantage 4 -->
          <div class="advantage-item">
            <div class="advantage-icon">
              <i class="fas fa-handshake"></i>
            </div>
            <div class="advantage-content">
              <h3>Customer-Focused</h3>
              <p>
                We prioritize customer satisfaction with transparent processes
                and dedicated support throughout projects.
              </p>
            </div>
          </div>
        </div>
      </section>

      <!-- Testimonials Section -->
      <section class="testimonials">
        <h2>What Our Clients Say</h2>
        <div class="testimonial-slider">
          <div class="testimonial-wrapper" id="testimonialWrapper">
            <!-- Testimonial 1 -->
            <div class="testimonial-item">
              <div class="testimonial-content">
                <p class="testimonial-text">
                  Banner Fair helped me register my land and obtain building
                  permits with remarkable efficiency. Their professional
                  approach made what could have been a complex process
                  surprisingly smooth.
                </p>
                <div class="testimonial-author">
                  <div class="author-avatar">
                    <img src="../bhHQ2-XvUG3QKct5kwamE.jpg" alt="Client" />
                  </div>
                  <div class="author-info">
                    <h4>Jean Mutabazi</h4>
                    <p>Property Owner, Kigali</p>
                  </div>
                </div>
              </div>
            </div>

            <!-- Testimonial 2 -->
            <div class="testimonial-item">
              <div class="testimonial-content">
                <p class="testimonial-text">
                  The GIS training provided by Banner Fair transformed our
                  team's capabilities. Now we handle spatial data analysis with
                  confidence and precision. Highly recommended for organizations
                  looking to build technical capacity.
                </p>
                <div class="testimonial-author">
                  <div class="author-avatar">
                    <img src="../bhHQ2-XvUG3QKct5kwamE.jpg" alt="Client" />
                  </div>
                  <div class="author-info">
                    <h4>Marie Uwase</h4>
                    <p>Project Manager, NGO</p>
                  </div>
                </div>
              </div>
            </div>

            <!-- Testimonial 3 -->
            <div class="testimonial-item">
              <div class="testimonial-content">
                <p class="testimonial-text">
                  The environmental impact assessment conducted by Banner Fair
                  was thorough and insightful. Their recommendations helped us
                  adjust our development plans to be more sustainable while
                  still meeting our business objectives.
                </p>
                <div class="testimonial-author">
                  <div class="author-avatar">
                    <img src="../bhHQ2-XvUG3QKct5kwamE.jpg" alt="Client" />
                  </div>
                  <div class="author-info">
                    <h4>Robert Karemera</h4>
                    <p>Director, Construction Company</p>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <div class="slider-controls">
            <button class="slider-btn" id="prevBtn">
              <i class="fas fa-chevron-left"></i>
            </button>
            <button class="slider-btn" id="nextBtn">
              <i class="fas fa-chevron-right"></i>
            </button>
          </div>

          <div class="slider-dots" id="sliderDots">
            <span class="slider-dot active" data-index="0"></span>
            <span class="slider-dot" data-index="1"></span>
            <span class="slider-dot" data-index="2"></span>
          </div>
        </div>
      </section>

      <!-- Call to Action Section -->
      <section class="cta-section">
        <h2>Ready to Start Your Project?</h2>
        <p>
          Whether you need land surveying, construction support, environmental
          consultancy, or technical training, our team is ready to deliver
          expert solutions tailored to your needs.
        </p>
        <div class="cta-buttons">
          <a href="contact.php" class="btn-cta-primary">
            <i class="fas fa-phone-alt"></i> Contact Us Today
          </a>
          <a href="contact.php" class="btn-cta-secondary">
            <i class="fas fa-calendar-alt"></i> Schedule a Consultation
          </a>
        </div>
      </section>
    </main>

    <!-- Footer Section -->
    <?php include 'includes/footer.php'; ?>

    <script src="../js/script.js"></script>
    <script>
      // Navigation Toggle
      document.addEventListener("DOMContentLoaded", function () {
        // Services Tab Functionality
        const tabButtons = document.querySelectorAll(".tab-btn");
        const serviceCategories =
          document.querySelectorAll(".service-category");

        tabButtons.forEach((button) => {
          button.addEventListener("click", function () {
            // Remove active class from all buttons
            tabButtons.forEach((btn) => btn.classList.remove("active"));

            // Add active class to clicked button
            this.classList.add("active");

            // Hide all service categories
            serviceCategories.forEach((category) =>
              category.classList.remove("active")
            );

            // Show the selected category
            const categoryId = this.getAttribute("data-category");
            document.getElementById(categoryId).classList.add("active");
          });
        });

        // Testimonial Slider
        const testimonialWrapper =
          document.getElementById("testimonialWrapper");
        const prevBtn = document.getElementById("prevBtn");
        const nextBtn = document.getElementById("nextBtn");
        const dots = document.querySelectorAll(".slider-dot");
        let currentSlide = 0;
        const totalSlides =
          document.querySelectorAll(".testimonial-item").length;

        function updateSlider() {
          testimonialWrapper.style.transform = `translateX(-${
            currentSlide * 100
          }%)`;

          // Update dots
          dots.forEach((dot) => dot.classList.remove("active"));
          dots[currentSlide].classList.add("active");
        }

        prevBtn.addEventListener("click", function () {
          currentSlide =
            currentSlide === 0 ? totalSlides - 1 : currentSlide - 1;
          updateSlider();
        });

        nextBtn.addEventListener("click", function () {
          currentSlide =
            currentSlide === totalSlides - 1 ? 0 : currentSlide + 1;
          updateSlider();
        });

        dots.forEach((dot) => {
          dot.addEventListener("click", function () {
            currentSlide = parseInt(this.getAttribute("data-index"));
            updateSlider();
          });
        });

        // Auto-rotate testimonials
        setInterval(() => {
          currentSlide =
            currentSlide === totalSlides - 1 ? 0 : currentSlide + 1;
          updateSlider();
        }, 8000);
      });
    </script>
  </body>
</html>
