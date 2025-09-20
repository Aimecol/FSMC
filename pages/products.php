<!DOCTYPE php>
<php lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Products & Equipment - Banner Fair Surveying & Mapping Ltd</title>
    <link rel="icon" type="image/svg+xml" href="../images/logo.png" />
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css"
    />
    <link rel="stylesheet" href="../css/style.css" />
    <link rel="stylesheet" href="../css/products.css" />
  </head>
  <body>
    <?php include 'includes/header.php'; ?>

    <main class="main-content">
      <section class="page-title">
        <h1>Our Products & Equipment</h1>
        <p>
          Discover our range of high-quality surveying equipment, software
          solutions, and training materials for professionals in the land
          surveying and mapping industry.
        </p>
      </section>

      <section class="products-filter">
        <button class="filter-btn active" data-filter="all">
          All Products
        </button>
        <button class="filter-btn" data-filter="equipment">Equipment</button>
        <button class="filter-btn" data-filter="software">Software</button>
        <button class="filter-btn" data-filter="training">Training</button>
      </section>

      <section class="category-heading">
        <h2>Surveying Equipment</h2>
      </section>

      <section class="products-grid" id="equipmentGrid">
        <div class="product-card" data-category="equipment">
          <div class="product-image">
            <i class="fas fa-broadcast-tower"></i>
          </div>
          <div class="product-details">
            <h3 class="product-title">Total Station Professional</h3>
            <p class="product-description">
              High-precision surveying instrument for accurate measurements in
              the field.
            </p>
            <ul class="product-features">
              <li><i class="fas fa-check-circle"></i> Angular accuracy: 2"</li>
              <li>
                <i class="fas fa-check-circle"></i> Range: Up to 500m without
                prism
              </li>
              <li><i class="fas fa-check-circle"></i> Battery life: 8 hours</li>
            </ul>
            <div class="product-actions">
              <button class="btn-details" data-product="total-station">
                View Details
              </button>
              <button class="btn-inquire" data-product="total-station">
                Inquire
              </button>
            </div>
          </div>
        </div>

        <div class="product-card" data-category="equipment">
          <div class="product-image">
            <i class="fas fa-satellite-dish"></i>
          </div>
          <div class="product-details">
            <h3 class="product-title">RTK GPS System</h3>
            <p class="product-description">
              Real-time kinematic GPS system for centimeter-level positioning
              accuracy.
            </p>
            <ul class="product-features">
              <li>
                <i class="fas fa-check-circle"></i> Horizontal accuracy: 8mm +
                1ppm
              </li>
              <li>
                <i class="fas fa-check-circle"></i> Vertical accuracy: 15mm +
                1ppm
              </li>
              <li>
                <i class="fas fa-check-circle"></i> Tracking: GPS, GLONASS,
                Galileo
              </li>
            </ul>
            <div class="product-actions">
              <button class="btn-details" data-product="rtk-gps">
                View Details
              </button>
              <button class="btn-inquire" data-product="rtk-gps">
                Inquire
              </button>
            </div>
          </div>
        </div>

        <div class="product-card" data-category="equipment">
          <div class="product-image">
            <i class="fas fa-ruler-combined"></i>
          </div>
          <div class="product-details">
            <h3 class="product-title">Digital Level</h3>
            <p class="product-description">
              Precise digital leveling instrument for elevation measurements.
            </p>
            <ul class="product-features">
              <li>
                <i class="fas fa-check-circle"></i> Height accuracy: 0.3mm/km
              </li>
              <li><i class="fas fa-check-circle"></i> Range: Up to 100m</li>
              <li>
                <i class="fas fa-check-circle"></i> Internal memory: 2,000
                measurements
              </li>
            </ul>
            <div class="product-actions">
              <button class="btn-details" data-product="digital-level">
                View Details
              </button>
              <button class="btn-inquire" data-product="digital-level">
                Inquire
              </button>
            </div>
          </div>
        </div>
      </section>

      <section class="category-heading">
        <h2>Software Solutions</h2>
      </section>

      <section class="products-grid" id="softwareGrid">
        <div class="product-card" data-category="software">
          <div class="product-image">
            <i class="fas fa-drafting-compass"></i>
          </div>
          <div class="product-details">
            <h3 class="product-title">AutoCAD Software</h3>
            <p class="product-description">
              Industry-standard CAD software for precise drafting and design.
            </p>
            <ul class="product-features">
              <li>
                <i class="fas fa-check-circle"></i> Full license with updates
              </li>
              <li>
                <i class="fas fa-check-circle"></i> Specialized surveying tools
              </li>
              <li>
                <i class="fas fa-check-circle"></i> Technical support included
              </li>
            </ul>
            <div class="product-actions">
              <button class="btn-details" data-product="autocad">
                View Details
              </button>
              <button class="btn-inquire" data-product="autocad">
                Inquire
              </button>
            </div>
          </div>
        </div>

        <div class="product-card" data-category="software">
          <div class="product-image">
            <i class="fas fa-globe-africa"></i>
          </div>
          <div class="product-details">
            <h3 class="product-title">ArcGIS Software</h3>
            <p class="product-description">
              Comprehensive GIS software for spatial data analysis and mapping.
            </p>
            <ul class="product-features">
              <li>
                <i class="fas fa-check-circle"></i> Spatial analysis tools
              </li>
              <li>
                <i class="fas fa-check-circle"></i> Advanced mapping
                capabilities
              </li>
              <li><i class="fas fa-check-circle"></i> Data management tools</li>
            </ul>
            <div class="product-actions">
              <button class="btn-details" data-product="arcgis">
                View Details
              </button>
              <button class="btn-inquire" data-product="arcgis">Inquire</button>
            </div>
          </div>
        </div>

        <div class="product-card" data-category="software">
          <div class="product-image">
            <i class="fas fa-laptop-code"></i>
          </div>
          <div class="product-details">
            <h3 class="product-title">Python for Data Analysis</h3>
            <p class="product-description">
              Python packages for geospatial data processing and analysis.
            </p>
            <ul class="product-features">
              <li>
                <i class="fas fa-check-circle"></i> NumPy, Pandas, Matplotlib
              </li>
              <li><i class="fas fa-check-circle"></i> GeoPandas and GDAL</li>
              <li>
                <i class="fas fa-check-circle"></i> Installation and setup
                support
              </li>
            </ul>
            <div class="product-actions">
              <button class="btn-details" data-product="python-analysis">
                View Details
              </button>
              <button class="btn-inquire" data-product="python-analysis">
                Inquire
              </button>
            </div>
          </div>
        </div>
      </section>

      <section class="category-heading">
        <h2>Training Materials</h2>
      </section>

      <section class="products-grid" id="trainingGrid">
        <div class="product-card" data-category="training">
          <div class="product-image">
            <i class="fas fa-chalkboard-teacher"></i>
          </div>
          <div class="product-details">
            <h3 class="product-title">Surveying Equipment Training</h3>
            <p class="product-description">
              Comprehensive training materials for total station and GPS
              equipment.
            </p>
            <ul class="product-features">
              <li><i class="fas fa-check-circle"></i> Video tutorials</li>
              <li>
                <i class="fas fa-check-circle"></i> Hands-on practice guides
              </li>
              <li>
                <i class="fas fa-check-circle"></i> Troubleshooting manual
              </li>
            </ul>
            <div class="product-actions">
              <button class="btn-details" data-product="equipment-training">
                View Details
              </button>
              <button class="btn-inquire" data-product="equipment-training">
                Inquire
              </button>
            </div>
          </div>
        </div>

        <div class="product-card" data-category="training">
          <div class="product-image">
            <i class="fas fa-brain"></i>
          </div>
          <div class="product-details">
            <h3 class="product-title">AI for GIS Analysis</h3>
            <p class="product-description">
              Training materials for implementing AI in geospatial data
              analysis.
            </p>
            <ul class="product-features">
              <li>
                <i class="fas fa-check-circle"></i> Machine Learning
                fundamentals
              </li>
              <li>
                <i class="fas fa-check-circle"></i> Deep Learning for remote
                sensing
              </li>
              <li>
                <i class="fas fa-check-circle"></i> Project-based learning
                materials
              </li>
            </ul>
            <div class="product-actions">
              <button class="btn-details" data-product="ai-gis">
                View Details
              </button>
              <button class="btn-inquire" data-product="ai-gis">Inquire</button>
            </div>
          </div>
        </div>

        <div class="product-card" data-category="training">
          <div class="product-image">
            <i class="fas fa-satellite"></i>
          </div>
          <div class="product-details">
            <h3 class="product-title">Remote Sensing Analysis</h3>
            <p class="product-description">
              Comprehensive training on satellite imagery and remote sensing
              techniques.
            </p>
            <ul class="product-features">
              <li>
                <i class="fas fa-check-circle"></i> Image classification
                techniques
              </li>
              <li>
                <i class="fas fa-check-circle"></i> Change detection analysis
              </li>
              <li>
                <i class="fas fa-check-circle"></i> Environmental monitoring
              </li>
            </ul>
            <div class="product-actions">
              <button class="btn-details" data-product="remote-sensing">
                View Details
              </button>
              <button class="btn-inquire" data-product="remote-sensing">
                Inquire
              </button>
            </div>
          </div>
        </div>
      </section>

      <section class="recommended-products">
        <div class="recommended-title">
          <h2>Popular Bundles</h2>
          <p>Save with our specially curated product bundles</p>
        </div>
        <div class="products-grid">
          <div class="product-card" data-category="bundle">
            <div class="product-image">
              <i class="fas fa-layer-group"></i>
            </div>
            <div class="product-details">
              <h3 class="product-title">Surveyor Starter Bundle</h3>
              <p class="product-description">
                Complete package for beginners in land surveying.
              </p>
              <ul class="product-features">
                <li><i class="fas fa-check-circle"></i> Total Station Basic</li>
                <li><i class="fas fa-check-circle"></i> AutoCAD License</li>
                <li>
                  <i class="fas fa-check-circle"></i> Beginner's Training Course
                </li>
              </ul>
              <div class="product-actions">
                <button class="btn-details" data-product="starter-bundle">
                  View Details
                </button>
                <button class="btn-inquire" data-product="starter-bundle">
                  Inquire
                </button>
              </div>
            </div>
          </div>

          <div class="product-card" data-category="bundle">
            <div class="product-image">
              <i class="fas fa-cubes"></i>
            </div>
            <div class="product-details">
              <h3 class="product-title">GIS Professional Bundle</h3>
              <p class="product-description">
                Advanced GIS software and training for professionals.
              </p>
              <ul class="product-features">
                <li><i class="fas fa-check-circle"></i> ArcGIS Pro License</li>
                <li>
                  <i class="fas fa-check-circle"></i> Python for Geospatial
                  Analysis
                </li>
                <li>
                  <i class="fas fa-check-circle"></i> Advanced GIS Training
                  Course
                </li>
              </ul>
              <div class="product-actions">
                <button class="btn-details" data-product="gis-bundle">
                  View Details
                </button>
                <button class="btn-inquire" data-product="gis-bundle">
                  Inquire
                </button>
              </div>
            </div>
          </div>
        </div>
      </section>

      <section class="cta-section">
        <div class="cta-content">
          <h2>Need Custom Solutions?</h2>
          <p>
            Our experts can help you find the perfect equipment and software for
            your specific surveying and mapping needs. Contact us for
            personalized recommendations.
          </p>
          <div class="cta-buttons">
            <button class="btn-cta-primary contact">
              <i class="fas fa-phone"></i> Contact Sales
            </button>
          </div>
        </div>
      </section>
    </main>

    <?php include 'includes/footer.php'; ?>

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

    <script src="../js/script.js"></script>
    <script>
      document.addEventListener("DOMContentLoaded", function () {
        // Product filtering
        const filterButtons = document.querySelectorAll(".filter-btn");
        const productCards = document.querySelectorAll(".product-card");

        filterButtons.forEach((button) => {
          button.addEventListener("click", () => {
            // Remove active class from all buttons
            filterButtons.forEach((btn) => btn.classList.remove("active"));

            // Add active class to clicked button
            button.classList.add("active");

            const filter = button.getAttribute("data-filter");

            // Show/hide products based on filter
            productCards.forEach((card) => {
              if (
                filter === "all" ||
                card.getAttribute("data-category") === filter
              ) {
                card.style.display = "block";
              } else {
                card.style.display = "none";
              }
            });
          });
        });

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
          autocad: {
            title: "AutoCAD Software",
            description:
              "Industry-standard CAD software for precise drafting and design. Create detailed 2D and 3D models for surveying and engineering projects.",
            icon: "fa-drafting-compass",
            manufacturer: "Autodesk",
            model: "AutoCAD 2025",
            warranty: "Lifetime License",
            support: "Online Documentation & Support",
          },
          arcgis: {
            title: "ArcGIS Software",
            description:
              "Comprehensive GIS software for spatial data analysis and mapping. Analyze patterns, visualize data, and create professional maps.",
            icon: "fa-globe-africa",
            manufacturer: "Esri",
            model: "ArcGIS Pro",
            warranty: "Annual Subscription",
            support: "Forum & Knowledge Base",
          },
          "python-analysis": {
            title: "Python for Data Analysis",
            description:
              "Python packages for geospatial data processing and analysis. Custom solutions for data manipulation, visualization, and modeling.",
            icon: "fa-laptop-code",
            manufacturer: "Banner Fair",
            model: "Custom Package",
            warranty: "6 Months Support",
            support: "Email Support",
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

        // Close modals when clicking outside
        window.addEventListener("click", (e) => {
          if (e.target === detailModal) {
            detailModal.style.display = "none";
          }
          if (e.target === inquiryModal) {
            inquiryModal.style.display = "none";
          }
        });

        // Add scroll effect
        window.addEventListener("scroll", function () {
          const header = document.querySelector(".header");
          if (window.scrollY > 50) {
            header.style.boxShadow = "0 5px 15px rgba(0, 0, 0, 0.1)";
          } else {
            header.style.boxShadow = "0 2px 10px rgba(0, 0, 0, 0.1)";
          }
        });

        document.querySelector(".contact").addEventListener("click", () => {
          window.location.href = "./contact.php";
        });
      });
    </script>
  </body>
</php>
