<?php
// Include database configuration
require_once 'config/database.php';

// Get company settings
$settings = getCompanySettings();
$companyName = getSetting('company_name', 'Fair Surveying & Mapping Ltd');

// Handle product enquiry form submission
$enquiryMessage = '';
$enquiryError = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_enquiry'])) {
    $productId = (int)$_POST['product_id'];
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $company = trim($_POST['company']);
    $message = trim($_POST['message']);
    
    // Validate required fields
    if (empty($name) || empty($email) || empty($message) || $productId <= 0) {
        $enquiryError = 'Please fill in all required fields.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $enquiryError = 'Please enter a valid email address.';
    } else {
        // Insert enquiry into database
        $sql = "INSERT INTO product_inquiries (product_id, name, email, phone, company, message, status, created_at) VALUES (?, ?, ?, ?, ?, ?, 'new', NOW())";
        $stmt = getDB()->query($sql, [$productId, $name, $email, $phone, $company, $message]);
        
        if ($stmt) {
            $enquiryMessage = 'Thank you for your enquiry! Our team will contact you shortly.';
            // Clear form data
            $_POST = [];
        } else {
            $enquiryError = 'Sorry, there was an error submitting your enquiry. Please try again.';
        }
    }
}

// Get all active products
$products = dbGetRows("SELECT * FROM products WHERE status = 'active' ORDER BY sort_order ASC, created_at DESC");

// Group products by category
$productsByCategory = [];
foreach ($products as $product) {
    $productsByCategory[$product['category']][] = $product;
}
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title><?php echo htmlspecialchars(getSetting('meta_title', 'Products & Equipment - ' . $companyName)); ?></title>
    <meta name="description" content="<?php echo htmlspecialchars(getSetting('meta_description', 'Discover our range of high-quality surveying equipment, software solutions, and training materials for professionals in the land surveying and mapping industry.')); ?>" />
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

      <?php if ($enquiryMessage): ?>
      <div class="alert alert-success" style="background: #d4edda; color: #155724; padding: 15px; margin: 20px 0; border-radius: 5px; border: 1px solid #c3e6cb;">
        <?php echo htmlspecialchars($enquiryMessage); ?>
      </div>
      <?php endif; ?>
      
      <?php if ($enquiryError): ?>
      <div class="alert alert-error" style="background: #f8d7da; color: #721c24; padding: 15px; margin: 20px 0; border-radius: 5px; border: 1px solid #f5c6cb;">
        <?php echo htmlspecialchars($enquiryError); ?>
      </div>
      <?php endif; ?>

      <section class="products-filter">
        <button class="filter-btn active" data-filter="all">
          All Products
        </button>
        <?php 
        $categories = ['equipment' => 'Equipment', 'software' => 'Software', 'training' => 'Training', 'bundle' => 'Bundles'];
        foreach ($categories as $key => $label): 
            if (isset($productsByCategory[$key]) && !empty($productsByCategory[$key])): ?>
        <button class="filter-btn" data-filter="<?php echo $key; ?>"><?php echo $label; ?></button>
        <?php endif; endforeach; ?>
      </section>

      <?php foreach ($categories as $categoryKey => $categoryLabel): ?>
        <?php if (isset($productsByCategory[$categoryKey]) && !empty($productsByCategory[$categoryKey])): ?>
        <section class="category-heading">
          <h2><?php echo htmlspecialchars($categoryLabel); ?></h2>
        </section>

        <section class="products-grid" id="<?php echo $categoryKey; ?>Grid">
          <?php foreach ($productsByCategory[$categoryKey] as $product): 
            $features = !empty($product['features']) ? json_decode($product['features'], true) : [];
            $specifications = !empty($product['specifications']) ? json_decode($product['specifications'], true) : [];
            $imageUrl = !empty($product['image']) ? getFileUrl($product['image']) : '../images/placeholder.jpg';
          ?>
          <div class="product-card" data-category="<?php echo htmlspecialchars($product['category']); ?>">
            <div class="product-image">
              <img src="<?php echo htmlspecialchars($imageUrl); ?>" alt="<?php echo htmlspecialchars($product['title']); ?>" /> 
            </div>
            <div class="product-details">
              <h3 class="product-title"><?php echo htmlspecialchars($product['title']); ?></h3>
              <p class="product-description">
                <?php echo htmlspecialchars($product['short_description'] ?: substr($product['description'], 0, 150) . '...'); ?>
              </p>
              <?php if (!empty($features) && is_array($features)): ?>
              <ul class="product-features">
                <?php foreach (array_slice($features, 0, 3) as $feature): ?>
                <li><i class="fas fa-check-circle"></i> <?php echo htmlspecialchars(is_array($feature) ? $feature['title'] : $feature); ?></li>
                <?php endforeach; ?>
              </ul>
              <?php endif; ?>
              <?php if ($product['price']): ?>
              <div class="product-price">
                <span class="price"><?php echo formatPrice($product['price']); ?></span>
              </div>
              <?php endif; ?>
              <div class="product-actions">
                <button class="btn-details" data-product-id="<?php echo $product['id']; ?>">
                  View Details
                </button>
                <button class="btn-inquire" data-product-id="<?php echo $product['id']; ?>">
                  Inquire
                </button>
              </div>
            </div>
          </div>
          <?php endforeach; ?>
        </section>
        <?php endif; ?>
      <?php endforeach; ?>

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
            <span class="spec-name">Category</span>
            <span class="spec-value" id="specCategory">-</span>
          </div>
          <div class="spec-item">
            <span class="spec-name">Manufacturer</span>
            <span class="spec-value" id="specManufacturer">-</span>
          </div>
          <div class="spec-item">
            <span class="spec-name">Model</span>
            <span class="spec-value" id="specModel">-</span>
          </div>
          <div class="spec-item">
            <span class="spec-name">Price</span>
            <span class="spec-value" id="specPrice">-</span>
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
        <div id="modalFeatures" class="modal-features" style="margin-top: 20px;">
          <h4>Features</h4>
          <ul id="modalFeaturesList"></ul>
        </div>
        <div id="modalSpecifications" class="modal-specifications" style="margin-top: 20px;">
          <h4>Technical Specifications</h4>
          <div id="modalSpecificationsList"></div>
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
        <form class="inquiry-form" id="inquiryForm" method="POST">
          <input type="hidden" id="product_id" name="product_id" value="" />
          <div class="form-group">
            <label for="name">Full Name *</label>
            <input type="text" id="name" name="name" required value="<?php echo isset($_POST['name']) ? htmlspecialchars($_POST['name']) : ''; ?>" />
          </div>
          <div class="form-group">
            <label for="email">Email Address *</label>
            <input type="email" id="email" name="email" required value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>" />
          </div>
          <div class="form-group">
            <label for="phone">Phone Number</label>
            <input type="tel" id="phone" name="phone" value="<?php echo isset($_POST['phone']) ? htmlspecialchars($_POST['phone']) : ''; ?>" />
          </div>
          <div class="form-group">
            <label for="company">Company/Organization</label>
            <input type="text" id="company" name="company" value="<?php echo isset($_POST['company']) ? htmlspecialchars($_POST['company']) : ''; ?>" />
          </div>
          <div class="form-group">
            <label for="product">Product of Interest</label>
            <input type="text" id="product" name="product" readonly />
          </div>
          <div class="form-group">
            <label for="message">Message *</label>
            <textarea id="message" name="message" required placeholder="Please describe your requirements, questions, or any specific information you'd like to know about this product..."><?php echo isset($_POST['message']) ? htmlspecialchars($_POST['message']) : ''; ?></textarea>
          </div>
          <button type="submit" name="submit_enquiry" class="form-submit">Submit Inquiry</button>
        </form>
      </div>
    </div>

    <script src="../js/script.js"></script>
    <script>
      // Product data from database
      const productData = {
        <?php foreach ($products as $product): 
          $features = !empty($product['features']) ? json_decode($product['features'], true) : [];
          $specifications = !empty($product['specifications']) ? json_decode($product['specifications'], true) : [];
          $imageUrl = !empty($product['image']) ? getFileUrl($product['image']) : '../images/placeholder.jpg';
        ?>
        <?php echo $product['id']; ?>: {
          id: <?php echo $product['id']; ?>,
          title: <?php echo json_encode($product['title']); ?>,
          description: <?php echo json_encode($product['description']); ?>,
          short_description: <?php echo json_encode($product['short_description']); ?>,
          category: <?php echo json_encode($product['category']); ?>,
          manufacturer: <?php echo json_encode($product['manufacturer']); ?>,
          model: <?php echo json_encode($product['model']); ?>,
          price: <?php echo json_encode(formatPrice($product['price'])); ?>,
          warranty: <?php echo json_encode($product['warranty']); ?>,
          support: <?php echo json_encode($product['support']); ?>,
          features: <?php echo json_encode($features); ?>,
          specifications: <?php echo json_encode($specifications); ?>,
          image: <?php echo json_encode($imageUrl); ?>,
          icon: <?php echo json_encode($product['icon']); ?>
        },
        <?php endforeach; ?>
      };

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
        const modalProductDescription = document.getElementById("modalProductDescription");
        const modalProductImage = document.getElementById("modalProductImage");
        const specCategory = document.getElementById("specCategory");
        const specManufacturer = document.getElementById("specManufacturer");
        const specModel = document.getElementById("specModel");
        const specPrice = document.getElementById("specPrice");
        const specWarranty = document.getElementById("specWarranty");
        const specSupport = document.getElementById("specSupport");
        const modalInquireBtn = document.getElementById("modalInquireBtn");
        const modalFeaturesList = document.getElementById("modalFeaturesList");
        const modalSpecificationsList = document.getElementById("modalSpecificationsList");
        const modalFeatures = document.getElementById("modalFeatures");
        const modalSpecifications = document.getElementById("modalSpecifications");

        detailButtons.forEach((button) => {
          button.addEventListener("click", () => {
            const productId = button.getAttribute("data-product-id");
            const product = productData[productId];

            if (!product) return;

            modalProductTitle.textContent = product.title;
            modalProductDescription.textContent = product.description;
            modalProductImage.innerHTML = `<img src="${product.image}" alt="${product.title}" style="width: 100%; height: 200px; object-fit: cover; border-radius: 8px;" />`;
            
            specCategory.textContent = product.category ? product.category.charAt(0).toUpperCase() + product.category.slice(1) : '-';
            specManufacturer.textContent = product.manufacturer || '-';
            specModel.textContent = product.model || '-';
            specPrice.textContent = product.price || '-';
            specWarranty.textContent = product.warranty || '-';
            specSupport.textContent = product.support || '-';

            // Display features
            if (product.features && product.features.length > 0) {
              modalFeaturesList.innerHTML = '';
              product.features.forEach(feature => {
                const li = document.createElement('li');
                li.innerHTML = `<i class="fas fa-check-circle"></i> ${typeof feature === 'object' ? feature.title || feature.description : feature}`;
                modalFeaturesList.appendChild(li);
              });
              modalFeatures.style.display = 'block';
            } else {
              modalFeatures.style.display = 'none';
            }

            // Display specifications
            if (product.specifications && Object.keys(product.specifications).length > 0) {
              modalSpecificationsList.innerHTML = '';
              Object.entries(product.specifications).forEach(([key, value]) => {
                const div = document.createElement('div');
                div.className = 'spec-item';
                div.innerHTML = `
                  <span class="spec-name">${key}</span>
                  <span class="spec-value">${value}</span>
                `;
                modalSpecificationsList.appendChild(div);
              });
              modalSpecifications.style.display = 'block';
            } else {
              modalSpecifications.style.display = 'none';
            }

            modalInquireBtn.setAttribute("data-product-id", productId);
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
        const productIdInput = document.getElementById("product_id");

        inquiryButtons.forEach((button) => {
          button.addEventListener("click", () => {
            const productId = button.getAttribute("data-product-id");
            const product = productData[productId];

            if (!product) return;

            productInput.value = product.title;
            productIdInput.value = product.id;
            inquiryModal.style.display = "flex";
          });
        });

        modalInquireBtn.addEventListener("click", () => {
          const productId = modalInquireBtn.getAttribute("data-product-id");
          const product = productData[productId];

          if (!product) return;

          productInput.value = product.title;
          productIdInput.value = product.id;
          detailModal.style.display = "none";
          inquiryModal.style.display = "flex";
        });

        closeInquiryModal.addEventListener("click", () => {
          inquiryModal.style.display = "none";
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

        // Contact button functionality
        const contactBtn = document.querySelector(".contact");
        if (contactBtn) {
          contactBtn.addEventListener("click", () => {
            window.location.href = "./contact.php";
          });
        }

        // Show inquiry modal if there was an error (to retain form data)
        <?php if ($enquiryError && isset($_POST['product_id'])): ?>
        const errorProductId = <?php echo (int)$_POST['product_id']; ?>;
        const errorProduct = productData[errorProductId];
        if (errorProduct) {
          productInput.value = errorProduct.title;
          productIdInput.value = errorProduct.id;
          inquiryModal.style.display = "flex";
        }
        <?php endif; ?>
      });
    </script>
  </body>
</html>
