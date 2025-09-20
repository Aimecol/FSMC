<!DOCTYPE php>
<php lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Our Services - Banner Fair Surveying & Mapping Ltd</title>
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
          Banner Fair Surveying and Mapping Ltd provides comprehensive
          surveying, mapping, and technical services with precision and
          expertise. Our team of qualified professionals ensures accuracy and
          excellence in all projects.
        </p>
      </section>

      <!-- Services Tabs -->
      <div class="services-tabs">
        <button class="tab-btn active" data-category="land-surveying">
          <i class="fas fa-map"></i> Land Surveying
        </button>
        <button class="tab-btn" data-category="building-construction">
          <i class="fas fa-building"></i> Building & Construction
        </button>
        <button class="tab-btn" data-category="environmental">
          <i class="fas fa-leaf"></i> Environmental
        </button>
        <button class="tab-btn" data-category="technical-training">
          <i class="fas fa-graduation-cap"></i> Technical Training
        </button>
      </div>

      <!-- Land Surveying Section -->
      <section class="service-category active" id="land-surveying">
        <h2 class="category-heading">
          <i class="fas fa-map-marked-alt"></i> Land Surveying & Mapping
          Services
        </h2>
        <div class="services-grid">
          <!-- Service Card 1 -->
          <div class="service-card">
            <div class="service-card-header">
              <h3>First Registration</h3>
              <i class="fas fa-file-signature"></i>
            </div>
            <div class="service-card-content">
              <div class="service-languages">
                <span class="language-tag"
                  ><i class="fas fa-globe"></i> English</span
                >
                <span class="language-tag"
                  ><i class="fas fa-globe"></i> Kinyarwanda</span
                >
              </div>
              <p>
                We offer comprehensive first-time registration services for land
                parcels, ensuring accurate measurements and legal documentation
                for your property.
              </p>
              <p>
                Our team uses state-of-the-art equipment to survey your land and
                prepare all necessary documentation required by land
                registration authorities.
              </p>
              <div class="service-cta">
                <a href="./service_view_more.php" class="btn-service">
                  <i class="fas fa-info-circle"></i> Learn More
                </a>
              </div>
            </div>
          </div>

          <!-- Service Card 2 -->
          <div class="service-card">
            <div class="service-card-header">
              <h3>Merging Land Parcels</h3>
              <i class="fas fa-object-group"></i>
            </div>
            <div class="service-card-content">
              <div class="service-languages">
                <span class="language-tag"
                  ><i class="fas fa-globe"></i> English</span
                >
                <span class="language-tag"
                  ><i class="fas fa-globe"></i> Kinyarwanda</span
                >
              </div>
              <p>
                We provide professional services for merging multiple land
                parcels into a single property, ensuring compliance with all
                legal requirements.
              </p>
              <p>
                Our experienced surveyors handle the entire process, from
                initial surveys to final documentation and registration of the
                merged property.
              </p>
              <div class="service-cta">
                <a href="#" class="btn-service">
                  <i class="fas fa-info-circle"></i> Learn More
                </a>
              </div>
            </div>
          </div>

          <!-- Service Card 3 -->
          <div class="service-card">
            <div class="service-card-header">
              <h3>Land Subdivision</h3>
              <i class="fas fa-cut"></i>
            </div>
            <div class="service-card-content">
              <div class="service-languages">
                <span class="language-tag"
                  ><i class="fas fa-globe"></i> English</span
                >
                <span class="language-tag"
                  ><i class="fas fa-globe"></i> Kinyarwanda</span
                >
              </div>
              <p>
                We specialize in dividing land parcels into smaller portions,
                ensuring accurate measurements and proper documentation for each
                new parcel.
              </p>
              <p>
                Our team ensures all subdivisions comply with local zoning laws
                and regulations, providing you with legally recognized property
                divisions.
              </p>
              <div class="service-cta">
                <a href="#" class="btn-service">
                  <i class="fas fa-info-circle"></i> Learn More
                </a>
              </div>
            </div>
          </div>

          <!-- Service Card 4 -->
          <div class="service-card">
            <div class="service-card-header">
              <h3>Boundary Correction</h3>
              <i class="fas fa-border-style"></i>
            </div>
            <div class="service-card-content">
              <div class="service-languages">
                <span class="language-tag"
                  ><i class="fas fa-globe"></i> English</span
                >
                <span class="language-tag"
                  ><i class="fas fa-globe"></i> Kinyarwanda</span
                >
              </div>
              <p>
                We offer professional boundary correction services to resolve
                disputes and ensure accurate property lines between neighboring
                properties.
              </p>
              <p>
                Using advanced surveying equipment, we can accurately determine
                and correct property boundaries, providing legal documentation
                for the updated boundaries.
              </p>
              <div class="service-cta">
                <a href="#" class="btn-service">
                  <i class="fas fa-info-circle"></i> Learn More
                </a>
              </div>
            </div>
          </div>
        </div>
      </section>

      <!-- Building & Construction Section -->
      <section class="service-category" id="building-construction">
        <h2 class="category-heading">
          <i class="fas fa-building"></i> Building & Construction Support
        </h2>
        <div class="services-grid">
          <!-- Service Card 1 -->
          <div class="service-card">
            <div class="service-card-header">
              <h3>Building Permits</h3>
              <i class="fas fa-clipboard-check"></i>
            </div>
            <div class="service-card-content">
              <div class="service-languages">
                <span class="language-tag"
                  ><i class="fas fa-globe"></i> English</span
                >
                <span class="language-tag"
                  ><i class="fas fa-globe"></i> Kinyarwanda</span
                >
                <span class="language-tag"
                  ><i class="fas fa-globe"></i> French</span
                >
              </div>
              <p>
                We assist clients in obtaining building permits (Autorisation de
                Bâtir) by preparing all required documentation and navigating
                the approval process.
              </p>
              <p>
                Our team has extensive experience with local building
                regulations and maintains excellent relationships with relevant
                authorities.
              </p>
              <div class="service-cta">
                <a href="#" class="btn-service">
                  <i class="fas fa-info-circle"></i> Learn More
                </a>
              </div>
            </div>
          </div>

          <!-- Service Card 2 -->
          <div class="service-card">
            <div class="service-card-header">
              <h3>Road Consultancy</h3>
              <i class="fas fa-road"></i>
            </div>
            <div class="service-card-content">
              <div class="service-languages">
                <span class="language-tag"
                  ><i class="fas fa-globe"></i> English</span
                >
                <span class="language-tag"
                  ><i class="fas fa-globe"></i> Kinyarwanda</span
                >
              </div>
              <p>
                We provide comprehensive road consultancy services including
                mapping, design, and planning for both public and private road
                projects.
              </p>
              <p>
                Our team utilizes advanced mapping technologies to create
                detailed road plans that meet both regulatory requirements and
                client specifications.
              </p>
              <div class="service-cta">
                <a href="#" class="btn-service">
                  <i class="fas fa-info-circle"></i> Learn More
                </a>
              </div>
            </div>
          </div>

          <!-- Service Card 3 -->
          <div class="service-card">
            <div class="service-card-header">
              <h3>House Plans</h3>
              <i class="fas fa-home"></i>
            </div>
            <div class="service-card-content">
              <div class="service-languages">
                <span class="language-tag"
                  ><i class="fas fa-globe"></i> English</span
                >
                <span class="language-tag"
                  ><i class="fas fa-globe"></i> Kinyarwanda</span
                >
              </div>
              <p>
                We create detailed and code-compliant house plans tailored to
                your specific needs, preferences, and budget.
              </p>
              <p>
                Our designs incorporate modern architectural principles while
                ensuring structural integrity and optimal space utilization.
              </p>
              <div class="service-cta">
                <a href="#" class="btn-service">
                  <i class="fas fa-info-circle"></i> Learn More
                </a>
              </div>
            </div>
          </div>
        </div>
      </section>

      <!-- Environmental Section -->
      <section class="service-category" id="environmental">
        <h2 class="category-heading">
          <i class="fas fa-leaf"></i> Environmental Consultancy
        </h2>
        <div class="services-grid">
          <!-- Service Card 1 -->
          <div class="service-card">
            <div class="service-card-header">
              <h3>Environmental Impact Assessment (EIA)</h3>
              <i class="fas fa-search"></i>
            </div>
            <div class="service-card-content">
              <div class="service-languages">
                <span class="language-tag"
                  ><i class="fas fa-globe"></i> English</span
                >
                <span class="language-tag"
                  ><i class="fas fa-globe"></i> Kinyarwanda</span
                >
              </div>
              <p>
                We conduct comprehensive Environmental Impact Assessments for
                development projects, ensuring compliance with local and
                international standards.
              </p>
              <p>
                Our assessments evaluate potential environmental effects,
                propose mitigation measures, and facilitate sustainable project
                implementation.
              </p>
              <div class="service-cta">
                <a href="#" class="btn-service">
                  <i class="fas fa-info-circle"></i> Learn More
                </a>
              </div>
            </div>
          </div>
        </div>
      </section>

      <!-- Technical Training Section -->
      <section class="service-category" id="technical-training">
        <h2 class="category-heading">
          <i class="fas fa-graduation-cap"></i> Technical Training
        </h2>
        <div class="services-grid">
          <!-- Service Card 1 -->
          <div class="service-card">
            <div class="service-card-header">
              <h3>Surveying Equipment & Software</h3>
              <i class="fas fa-tools"></i>
            </div>
            <div class="service-card-content">
              <div class="service-languages">
                <span class="language-tag"
                  ><i class="fas fa-globe"></i> English</span
                >
              </div>
              <p>
                We provide hands-on training for surveying equipment such as
                Total Stations and software like AutoCAD and ArcGIS.
              </p>
              <p>
                Our training programs are designed for professionals, students,
                and organizations looking to enhance their technical
                capabilities.
              </p>
              <div class="service-cta">
                <a href="#" class="btn-service">
                  <i class="fas fa-info-circle"></i> Learn More
                </a>
              </div>
            </div>
          </div>

          <!-- Service Card 2 -->
          <div class="service-card">
            <div class="service-card-header">
              <h3>Python for Data Analysis</h3>
              <i class="fab fa-python"></i>
            </div>
            <div class="service-card-content">
              <div class="service-languages">
                <span class="language-tag"
                  ><i class="fas fa-globe"></i> English</span
                >
              </div>
              <p>
                Learn data manipulation, visualization, and modeling using
                Python programming language and its powerful libraries.
              </p>
              <p>
                Our courses cover practical applications of Python in spatial
                data analysis, suitable for both beginners and experienced
                programmers.
              </p>
              <div class="service-cta">
                <a href="#" class="btn-service">
                  <i class="fas fa-info-circle"></i> Learn More
                </a>
              </div>
            </div>
          </div>

          <!-- Service Card 3 -->
          <div class="service-card">
            <div class="service-card-header">
              <h3>GIS & Remote Sensing</h3>
              <i class="fas fa-satellite"></i>
            </div>
            <div class="service-card-content">
              <div class="service-languages">
                <span class="language-tag"
                  ><i class="fas fa-globe"></i> English</span
                >
              </div>
              <p>
                Master Geographic Information Systems (GIS) and Remote Sensing
                techniques for advanced geospatial analysis.
              </p>
              <p>
                Our training covers data collection, processing, analysis, and
                visualization using industry-standard tools and methodologies.
              </p>
              <div class="service-cta">
                <a href="#" class="btn-service">
                  <i class="fas fa-info-circle"></i> Learn More
                </a>
              </div>
            </div>
          </div>

          <!-- Service Card 4 -->
          <div class="service-card">
            <div class="service-card-header">
              <h3>Artificial Intelligence for Data Analysis</h3>
              <i class="fas fa-brain"></i>
            </div>
            <div class="service-card-content">
              <div class="service-languages">
                <span class="language-tag"
                  ><i class="fas fa-globe"></i> English</span
                >
              </div>
              <p>
                Learn Machine Learning (ML) and Deep Learning (DL) techniques
                applied to geospatial and surveying data analysis.
              </p>
              <p>
                Our courses cover practical applications of AI in land use
                classification, feature extraction, and predictive modeling.
              </p>
              <div class="service-cta">
                <a href="#" class="btn-service">
                  <i class="fas fa-info-circle"></i> Learn More
                </a>
              </div>
            </div>
          </div>

          <!-- Service Card 5 -->
          <div class="service-card">
            <div class="service-card-header">
              <h3>Research Support Services</h3>
              <i class="fas fa-chart-line"></i>
            </div>
            <div class="service-card-content">
              <div class="service-languages">
                <span class="language-tag"
                  ><i class="fas fa-globe"></i> English</span
                >
              </div>
              <p>
                We provide comprehensive support for academic and professional
                research projects related to surveying, mapping, and geospatial
                analysis.
              </p>
              <p>
                Our services include data collection, processing, statistical
                analysis, and visualization to enhance the quality of research
                outcomes.
              </p>
              <div class="service-cta">
                <a href="#" class="btn-service">
                  <i class="fas fa-info-circle"></i> Learn More
                </a>
              </div>
            </div>
          </div>
        </div>
      </section>

      <!-- Why Choose Us Section -->
      <section class="why-choose-us">
        <h2>Why Choose Banner Fair Surveying & Mapping Ltd</h2>
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
                code LS00280, ensuring all work meets legal standards.
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
          <a href="#" class="btn-cta-primary">
            <i class="fas fa-phone-alt"></i> Contact Us Today
          </a>
          <a href="#" class="btn-cta-secondary">
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
</php>
