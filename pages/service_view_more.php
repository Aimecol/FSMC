<?php
// Include database configuration
require_once './config/database.php';

// Get service slug from URL parameter
$slug = isset($_GET['slug']) ? $_GET['slug'] : '';

if (empty($slug)) {
    header('Location: ./services.php');
    exit();
}

// Fetch service from database
$service = dbGetRow("SELECT * FROM services WHERE slug = ? AND status = 'active'", [$slug]);

if (!$service) {
    header('Location: ./services.php');
    exit();
}

// Decode JSON fields
$languages = json_decode($service['languages'], true) ?: [];
$featuresData = json_decode($service['features'], true) ?: [];
$gallery = json_decode($service['gallery'], true) ?: [];
$processSteps = json_decode($service['process_steps'], true) ?: [];
$benefits = json_decode($service['benefits'], true) ?: [];
$requirements = json_decode($service['requirements'], true) ?: [];
$faqs = json_decode($service['faqs'], true) ?: [];

// Handle backward compatibility for features
$features = [];
if (!empty($featuresData)) {
    // Check if it's the new format (array of objects) or old format (array of strings)
    if (isset($featuresData[0]) && is_array($featuresData[0]) && isset($featuresData[0]['title'])) {
        // New enhanced format
        $features = $featuresData;
    } else {
        // Old simple format - convert to new format for display
        foreach ($featuresData as $feature) {
            if (is_string($feature)) {
                $features[] = [
                    'title' => $feature,
                    'description' => '',
                    'icon' => 'fas fa-check-circle'
                ];
            }
        }
    }
}

// Fetch related services (same category or random)
$relatedServices = dbGetRows("SELECT * FROM services WHERE slug != ? AND status = 'active' ORDER BY sort_order ASC, created_at DESC LIMIT 3", [$slug]);

$companySettings = getCompanySettings();
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title><?php echo htmlspecialchars($service['meta_title'] ?: $service['title']); ?> - <?php echo getSetting('company_name', 'Fair Surveying & Mapping Ltd'); ?></title>
    <meta name="description" content="<?php echo htmlspecialchars($service['meta_description'] ?: $service['short_description'] ?: substr($service['description'], 0, 160)); ?>">
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css"
    />
    <link rel="stylesheet" href="../css/style.css" />
    <link rel="icon" type="image/svg+xml" href="../images/logo.png" />
    <style>
      :root {
        --primary-color: #1a5276;
        --secondary-color: #2e86c1;
        --accent-color: #f39c12;
        --light-color: #f4f6f7;
        --dark-color: #2c3e50;
        --text-dark: #2c3e50;
        --text-light: #7f8c8d;
        --white: #ffffff;
        --gray-light: #ecf0f1;
        --gray-medium: #bdc3c7;
        --success-color: #27ae60;
        --error-color: #e74c3c;
        --box-shadow: 0 5px 5px rgba(0, 0, 0, 0.1);
        --transition: all 0.3s ease;
      }

      body {
        font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
        line-height: 1.6;
        color: var(--text-dark);
        background-color: var(--light-color);
        overflow-x: hidden;
      }

      .container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 20px;
      }

      .breadcrumb {
        padding: 15px 0;
        background-color: var(--white);
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
      }

      .breadcrumb-links {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
        align-items: center;
        margin-top: 7rem;
      }

      .breadcrumb-links a {
        color: var(--secondary-color);
        text-decoration: none;
        transition: var(--transition);
      }

      .breadcrumb-links a:hover {
        color: var(--primary-color);
      }

      .breadcrumb-links span {
        color: var(--text-light);
      }

      /* Hero Section */
      .service-hero {
        background: linear-gradient(
            rgba(28, 40, 51, 0.8),
            rgba(28, 40, 51, 0.8)
          ),
          url("../images/9c_NLZ75_JAwFH7mjiGB_.jpg") center/cover no-repeat;
        color: var(--white);
        padding: 100px 0;
        text-align: center;
        position: relative;
      }

      .service-hero-content {
        max-width: 800px;
        margin: 0 auto;
      }

      .service-hero h1 {
        font-size: 2.8rem;
        margin-bottom: 20px;
        position: relative;
        padding-bottom: 15px;
      }

      .service-hero h1:after {
        content: "";
        position: absolute;
        bottom: 0;
        left: 50%;
        transform: translateX(-50%);
        width: 80px;
        height: 4px;
        background-color: var(--accent-color);
      }

      .service-hero p {
        font-size: 1.1rem;
        margin-bottom: 30px;
        max-width: 600px;
        margin-left: auto;
        margin-right: auto;
      }

      .service-hero .language-tags {
        display: flex;
        justify-content: center;
        gap: 15px;
        margin-bottom: 30px;
      }

      .language-tag {
        background-color: rgba(255, 255, 255, 0.1);
        padding: 8px 15px;
        border-radius: 4px;
        font-size: 0.9rem;
        display: flex;
        align-items: center;
        gap: 5px;
      }

      .cta-button {
        display: inline-block;
        background-color: var(--accent-color);
        color: var(--white);
        padding: 12px 25px;
        border-radius: 4px;
        text-decoration: none;
        font-weight: 600;
        transition: var(--transition);
        box-shadow: 0 4px 15px rgba(243, 156, 18, 0.3);
      }

      .cta-button:hover {
        background-color: #e67e22;
        transform: translateY(-2px);
      }

      .service-price-hero {
        background-color: rgba(255, 255, 255, 0.1);
        padding: 10px 20px;
        border-radius: 4px;
        margin-bottom: 20px;
        display: inline-block;
      }

      .service-duration-info {
        background-color: var(--light-color);
        padding: 20px;
        border-radius: 4px;
        margin: 20px 0;
        border-left: 4px solid var(--accent-color);
      }

      .service-duration-info h3 {
        color: var(--primary-color);
        margin-bottom: 10px;
      }

      /* Overview Section */
      .service-overview {
        background-color: var(--white);
        padding: 80px 0;
      }

      .section-title {
        text-align: center;
        margin-bottom: 50px;
        position: relative;
        padding-bottom: 15px;
      }

      .section-title:after {
        content: "";
        position: absolute;
        bottom: 0;
        left: 50%;
        transform: translateX(-50%);
        width: 60px;
        height: 3px;
        background-color: var(--accent-color);
      }

      .overview-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 30px;
        margin-top: 40px;
      }

      .overview-card {
        background-color: var(--light-color);
        border-radius: 4px;
        overflow: hidden;
        box-shadow: var(--box-shadow);
        transition: var(--transition);
      }

      .overview-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 5px 10px rgba(0, 0, 0, 0.15);
      }

      .overview-card-icon {
        background-color: var(--primary-color);
        color: var(--white);
        font-size: 2rem;
        padding: 20px;
        text-align: center;
      }

      .overview-card-content {
        padding: 25px;
      }

      .overview-card h3 {
        margin-bottom: 15px;
        color: var(--primary-color);
      }

      /* Process Section */
      .service-process {
        background-color: var(--light-color);
        padding: 80px 0;
      }

      .process-steps {
        display: flex;
        flex-wrap: wrap;
        justify-content: space-between;
        margin-top: 50px;
        position: relative;
      }

      .process-steps:before {
        content: "";
        position: absolute;
        top: 25px;
        left: 0;
        width: 100%;
        height: 3px;
        background-color: var(--gray-medium);
        z-index: 1;
      }

      .process-step {
        flex: 1;
        min-width: 200px;
        max-width: 220px;
        text-align: center;
        position: relative;
        z-index: 2;
        padding: 0 15px;
      }

      .step-number {
        background-color: var(--secondary-color);
        color: var(--white);
        width: 50px;
        height: 50px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.2rem;
        font-weight: bold;
        margin: 0 auto 20px;
        box-shadow: 0 5px 15px rgba(46, 134, 193, 0.3);
      }

      .step-title {
        font-weight: 600;
        margin-bottom: 10px;
        color: var(--primary-color);
      }

      .step-description {
        font-size: 0.95rem;
        color: var(--text-light);
      }

      /* Benefits Section */
      .service-benefits {
        background-color: var(--white);
        padding: 80px 0;
      }

      .benefits-list {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 30px;
        margin-top: 40px;
      }

      .benefit-item {
        display: flex;
        align-items: flex-start;
        gap: 15px;
        padding: 20px;
        background-color: var(--light-color);
        border-radius: 4px;
        box-shadow: var(--box-shadow);
        transition: var(--transition);
      }

      .benefit-item:hover {
        transform: translateY(-3px);
        box-shadow: 0 5px 10px rgba(0, 0, 0, 0.15);
      }

      .benefit-icon {
        color: var(--accent-color);
        font-size: 1.5rem;
        background-color: rgba(243, 156, 18, 0.1);
        padding: 10px;
        border-radius: 50%;
      }

      .benefit-content h3 {
        margin-bottom: 10px;
        color: var(--primary-color);
      }

      /* Requirements Section */
      .service-requirements {
        background-color: var(--gray-light);
        padding: 80px 0;
      }

      .requirements-container {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 30px;
        margin-top: 40px;
      }

      .requirements-list {
        background-color: var(--white);
        padding: 30px;
        border-radius: 4px;
        box-shadow: var(--box-shadow);
      }

      .requirements-list h3 {
        margin-bottom: 20px;
        color: var(--primary-color);
        padding-bottom: 10px;
        border-bottom: 2px solid var(--accent-color);
      }

      .requirements-list ul {
        list-style-type: none;
      }

      .requirements-list li {
        margin-bottom: 15px;
        position: relative;
        padding-left: 25px;
      }

      .requirements-list li:before {
        content: "\f00c";
        font-family: "Font Awesome 6 Free";
        font-weight: 900;
        color: var(--success-color);
        position: absolute;
        left: 0;
      }

      .requirements-image {
        border-radius: 4px;
        overflow: hidden;
        box-shadow: var(--box-shadow);
      }

      .requirements-image img {
        width: 100%;
        height: auto;
        display: block;
      }

      /* FAQ Section */
      .service-faq {
        background-color: var(--white);
        padding: 80px 0;
      }

      .faq-container {
        max-width: 800px;
        margin: 40px auto 0;
      }

      .faq-item {
        margin-bottom: 15px;
        border-radius: 4px;
        box-shadow: var(--box-shadow);
        overflow: hidden;
      }

      .faq-question {
        background-color: var(--light-color);
        padding: 20px;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: space-between;
        font-weight: 600;
      }

      .faq-question:hover {
        background-color: rgba(244, 246, 247, 0.8);
      }

      .faq-answer {
        padding: 0 20px;
        max-height: 0;
        overflow: hidden;
        transition: var(--transition);
        background-color: white;
      }

      .faq-answer p {
        padding: 20px 0;
      }

      .faq-item.active .faq-answer {
        max-height: 500px;
      }

      .faq-toggle {
        transition: var(--transition);
      }

      .faq-item.active .faq-toggle {
        transform: rotate(180deg);
      }

      /* Related Services Section */
      .related-services {
        background-color: var(--white);
        padding: 80px 0;
      }

      .related-services-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 30px;
        margin-top: 40px;
      }

      .related-service-card {
        background-color: var(--light-color);
        border-radius: 4px;
        overflow: hidden;
        box-shadow: var(--box-shadow);
        transition: var(--transition);
      }

      .related-service-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 5px 10px rgba(0, 0, 0, 0.15);
      }

      .related-service-image {
        height: 200px;
        overflow: hidden;
      }

      .related-service-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: var(--transition);
      }

      .related-service-card:hover .related-service-image img {
        transform: scale(1.1);
      }

      .related-service-content {
        padding: 20px;
      }

      .related-service-content h3 {
        margin-bottom: 10px;
        color: var(--primary-color);
      }

      .related-service-content p {
        color: var(--text-light);
        margin-bottom: 15px;
      }

      .service-link {
        color: var(--secondary-color);
        text-decoration: none;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 5px;
        transition: var(--transition);
      }

      .service-link:hover {
        color: var(--primary-color);
      }

      .service-link i {
        transition: var(--transition);
      }

      .service-link:hover i {
        transform: translateX(5px);
      }

      /* Responsive styling */
      @media (max-width: 1024px) {
        .process-steps {
          flex-direction: column;
          align-items: center;
          gap: 40px;
        }

        .process-steps:before {
          display: none;
        }

        .process-step {
          max-width: 100%;
        }
      }

      @media (max-width: 768px) {
        .service-hero h1 {
          font-size: 2.2rem;
        }

        .service-hero p {
          font-size: 1rem;
        }

        .section-title {
          font-size: 1.8rem;
        }

        .overview-grid,
        .benefits-list,
        .requirements-container,
        .related-services-grid {
          grid-template-columns: 1fr;
        }

        .cta-buttons {
          flex-direction: column;
          align-items: center;
        }
      }

      @media (max-width: 480px) {
        .service-hero h1 {
          font-size: 1.8rem;
        }

        .language-tags {
          flex-direction: column;
          align-items: center;
        }

        .testimonial-author {
          flex-direction: column;
          text-align: center;
        }
      }

      /* Contact Form Section */
      .contact-form-section {
        background-color: var(--light-color);
        padding: 80px 0;
      }

      .contact-container {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 40px;
        margin-top: 40px;
      }

      .contact-form {
        background-color: var(--white);
        padding: 30px;
        border-radius: 4px;
        box-shadow: var(--box-shadow);
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
      }

      .form-group {
        margin-bottom: 5px;
      }

      .full-width {
        grid-column: 1 / -1;
      }

      .contact-form label {
        display: block;
        margin-bottom: 8px;
        font-weight: 500;
        color: var(--text-dark);
      }

      .contact-form input,
      .contact-form select,
      .contact-form textarea {
        width: 100%;
        padding: 12px 15px;
        border: 1px solid var(--gray-medium);
        border-radius: 4px;
        font-family: inherit;
        font-size: 1rem;
        transition: var(--transition);
      }

      .contact-form input:focus,
      .contact-form select:focus,
      .contact-form textarea:focus {
        outline: none;
        border-color: var(--secondary-color);
        box-shadow: 0 0 0 2px rgba(46, 134, 193, 0.2);
      }

      .contact-form input.error,
      .contact-form select.error,
      .contact-form textarea.error {
        border-color: var(--error-color);
      }

      .submit-button {
        background-color: var(--primary-color);
        color: var(--white);
        border: none;
        padding: 12px 25px;
        border-radius: 4px;
        cursor: pointer;
        font-size: 1rem;
        font-weight: 600;
        transition: var(--transition);
        width: 100%;
      }

      .submit-button:hover {
        background-color: var(--secondary-color);
        transform: translateY(-2px);
      }

      .service-contact-info {
        display: grid;
        grid-template-columns: 1fr;
        gap: 20px;
      }

      .info-card {
        background-color: var(--white);
        padding: 20px;
        border-radius: 4px;
        box-shadow: var(--box-shadow);
        display: flex;
        align-items: center;
        gap: 20px;
        transition: var(--transition);
      }

      .info-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 5px 10px rgba(0, 0, 0, 0.15);
      }

      .info-icon {
        width: 50px;
        height: 50px;
        background-color: rgba(26, 82, 118, 0.1);
        color: var(--primary-color);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.2rem;
      }

      .info-content h3 {
        margin-bottom: 5px;
        color: var(--primary-color);
      }

      .info-content p {
        color: var(--text-light);
        line-height: 1.4;
      }

      /* Responsive updates */
      @media (max-width: 992px) {
        .contact-container {
          grid-template-columns: 1fr;
        }

        .service-contact-info {
          grid-template-columns: repeat(2, 1fr);
        }
      }

      @media (max-width: 768px) {
        .contact-form {
          grid-template-columns: 1fr;
        }

        .service-contact-info {
          grid-template-columns: 1fr;
        }

        .info-card {
          flex-direction: column;
          text-align: center;
          padding: 25px 15px;
        }

        .info-icon {
          margin-bottom: 10px;
        }
      }

      @media (max-width: 480px) {
        .submit-button {
          padding: 10px 15px;
        }
      }
    </style>
  </head>
  <body>
    <?php include 'includes/header.php'; ?>

    <!-- Breadcrumb -->
    <section class="breadcrumb">
      <div class="container">
        <div class="breadcrumb-links">
          <a href="../index.php"><i class="fas fa-home"></i> Home</a>
          <i class="fas fa-chevron-right"></i>
          <a href="./services.php">Services</a>
          <i class="fas fa-chevron-right"></i>
          <span><?php echo htmlspecialchars($service['title']); ?></span>
        </div>
      </div>
    </section>

    <!-- Hero Section -->
    <section class="service-hero">
      <div class="container">
        <div class="service-hero-content">
          <h1><?php echo htmlspecialchars($service['title']); ?></h1>
          <?php if (!empty($languages)): ?>
          <div class="language-tags">
            <?php foreach ($languages as $language): ?>
            <span class="language-tag">
              <i class="fas fa-globe"></i> <?php echo htmlspecialchars($language); ?>
            </span>
            <?php endforeach; ?>
          </div>
          <?php endif; ?>
          <p>
            <?php echo htmlspecialchars($service['short_description'] ?: substr($service['description'], 0, 200) . '...'); ?>
          </p>
          <?php if (!empty($service['price']) && $service['price'] > 0): ?>
          <div class="service-price-hero">
            <strong>Price: <?php echo formatPrice($service['price']); ?></strong>
          </div>
          <?php endif; ?>
          <a href="./contact.php" class="cta-button">
            <i class="fas fa-phone-alt"></i> Request a Consultation
          </a>
        </div>
      </div>
    </section>

    <!-- Overview Section -->
    <section class="service-overview">
      <div class="container">
        <h2 class="section-title">Service Overview</h2>
        <p style="text-align: center;">
          <?php echo nl2br(htmlspecialchars($service['description'])); ?>
        </p>
        
        <?php if (!empty($service['duration'])): ?>
        <div class="service-duration-info">
          <h3><i class="fas fa-clock"></i> Duration</h3>
          <p><?php echo htmlspecialchars($service['duration']); ?></p>
        </div>
        <?php endif; ?>

        <?php if (!empty($features)): ?>
        <div class="overview-grid">
          <?php foreach (array_slice($features, 0, 6) as $index => $feature): ?>
          <div class="overview-card">
            <div class="overview-card-icon">
              <i class="<?php echo htmlspecialchars($feature['icon'] ?: 'fas fa-check-circle'); ?>"></i>
            </div>
            <div class="overview-card-content">
              <h3><?php echo htmlspecialchars($feature['title']); ?></h3>
              <p>
                <?php 
                echo htmlspecialchars($feature['description'] ?: 
                  'Professional service feature ensuring quality and reliability in our ' . strtolower($service['title']) . ' offerings.'
                ); 
                ?>
              </p>
            </div>
          </div>
          <?php endforeach; ?>
        </div>
        <?php endif; ?>
      </div>
    </section>

    <!-- Process Section -->
    <section class="service-process">
      <div class="container">
        <h2 class="section-title">Our Process</h2>
        <p style="text-align: center;">
          We follow a systematic approach to ensure your <?php echo strtolower($service['title']); ?> service is
          completed efficiently and accurately.
        </p>

        <div class="process-steps">
          <?php if (!empty($processSteps)): ?>
            <?php foreach ($processSteps as $step): ?>
            <div class="process-step">
              <div class="step-number"><?php echo htmlspecialchars($step['step']); ?></div>
              <div class="step-title"><?php echo htmlspecialchars($step['title']); ?></div>
              <div class="step-description">
                <?php echo htmlspecialchars($step['description']); ?>
              </div>
            </div>
            <?php endforeach; ?>
          <?php else: ?>
            <!-- Default process steps if none defined -->
            <div class="process-step">
              <div class="step-number">1</div>
              <div class="step-title">Initial Consultation</div>
              <div class="step-description">
                We meet with you to understand your needs and gather initial information.
              </div>
            </div>
            <div class="process-step">
              <div class="step-number">2</div>
              <div class="step-title">Assessment</div>
              <div class="step-description">
                Our professional team conducts a thorough assessment of your requirements.
              </div>
            </div>
            <div class="process-step">
              <div class="step-number">3</div>
              <div class="step-title">Implementation</div>
              <div class="step-description">
                We implement the service according to professional standards and best practices.
              </div>
            </div>
            <div class="process-step">
              <div class="step-number">4</div>
              <div class="step-title">Completion</div>
              <div class="step-description">
                We deliver the completed service and provide all necessary documentation.
              </div>
            </div>
          <?php endif; ?>
        </div>
      </div>
    </section>

    <!-- Benefits Section -->
    <section class="service-benefits">
      <div class="container">
        <h2 class="section-title">Benefits of <?php echo htmlspecialchars($service['title']); ?></h2>
        <p style="text-align: center;">
          Our <?php echo strtolower($service['title']); ?> service offers numerous advantages that provide
          value and peace of mind for our clients.
        </p>

        <div class="benefits-list">
          <?php if (!empty($benefits)): ?>
            <?php foreach ($benefits as $benefit): ?>
            <div class="benefit-item">
              <div class="benefit-icon">
                <i class="<?php echo htmlspecialchars($benefit['icon'] ?: 'fas fa-check-circle'); ?>"></i>
              </div>
              <div class="benefit-content">
                <h3><?php echo htmlspecialchars($benefit['title']); ?></h3>
                <p>
                  <?php echo htmlspecialchars($benefit['description']); ?>
                </p>
              </div>
            </div>
            <?php endforeach; ?>
          <?php else: ?>
            <!-- Default benefits if none defined -->
            <div class="benefit-item">
              <div class="benefit-icon">
                <i class="fas fa-shield-alt"></i>
              </div>
              <div class="benefit-content">
                <h3>Professional Service</h3>
                <p>
                  Our experienced team provides professional and reliable service
                  that meets industry standards and client expectations.
                </p>
              </div>
            </div>
            <div class="benefit-item">
              <div class="benefit-icon">
                <i class="fas fa-clock"></i>
              </div>
              <div class="benefit-content">
                <h3>Timely Delivery</h3>
                <p>
                  We complete projects within agreed timeframes, ensuring your
                  schedule and deadlines are met efficiently.
                </p>
              </div>
            </div>
            <div class="benefit-item">
              <div class="benefit-icon">
                <i class="fas fa-thumbs-up"></i>
              </div>
              <div class="benefit-content">
                <h3>Quality Assurance</h3>
                <p>
                  All our work is backed by quality assurance processes and
                  professional guarantees for your peace of mind.
                </p>
              </div>
            </div>
          <?php endif; ?>
        </div>
      </div>
    </section>

    <!-- Requirements Section -->
    <section class="service-requirements">
      <div class="container">
        <h2 class="section-title">Requirements for <?php echo htmlspecialchars($service['title']); ?></h2>
        <p style="text-align: center;">
          To proceed with our <?php echo strtolower($service['title']); ?> service, you'll need to provide
          certain documents and meet specific requirements.
        </p>

        <div class="requirements-container">
          <?php if (!empty($requirements)): ?>
            <?php foreach ($requirements as $requirement): ?>
            <div class="requirements-list">
              <h3><?php echo htmlspecialchars($requirement['category']); ?></h3>
              <ul>
                <?php foreach ($requirement['items'] as $item): ?>
                <li><?php echo htmlspecialchars($item); ?></li>
                <?php endforeach; ?>
              </ul>
            </div>
            <?php endforeach; ?>
          <?php else: ?>
            <!-- Default requirements if none defined -->
            <div class="requirements-list">
              <h3>General Requirements</h3>
              <ul>
                <li>Valid identification documents</li>
                <li>Relevant project or property documentation</li>
                <li>Contact information and availability for consultation</li>
                <li>Clear project specifications and requirements</li>
              </ul>
            </div>
          <?php endif; ?>

          <?php if (!empty($service['image'])): ?>
          <div class="requirements-image">
            <img
              src="<?php echo getFileUrl($service['image']); ?>"
              alt="<?php echo htmlspecialchars($service['title']); ?> Requirements"
            />
          </div>
          <?php endif; ?>
        </div>
      </div>
    </section>

    <!-- FAQ Section -->
    <section class="service-faq">
      <div class="container">
        <h2 class="section-title">Frequently Asked Questions</h2>
        <p style="text-align: center;">
          Here are answers to some common questions about our <?php echo strtolower($service['title']); ?>
          service.
        </p>

        <div class="faq-container">
          <?php if (!empty($faqs)): ?>
            <?php foreach ($faqs as $faq): ?>
            <div class="faq-item">
              <div class="faq-question">
                <span><?php echo htmlspecialchars($faq['question']); ?></span>
                <i class="fas fa-chevron-down faq-toggle"></i>
              </div>
              <div class="faq-answer">
                <p>
                  <?php echo nl2br(htmlspecialchars($faq['answer'])); ?>
                </p>
              </div>
            </div>
            <?php endforeach; ?>
          <?php else: ?>
            <!-- Default FAQs if none defined -->
            <div class="faq-item">
              <div class="faq-question">
                <span>How long does the service take?</span>
                <i class="fas fa-chevron-down faq-toggle"></i>
              </div>
              <div class="faq-answer">
                <p>
                  The duration varies depending on the complexity and scope of the project. 
                  We provide estimated timelines during the initial consultation and keep 
                  you updated throughout the process.
                </p>
              </div>
            </div>

            <div class="faq-item">
              <div class="faq-question">
                <span>What is the cost of this service?</span>
                <i class="fas fa-chevron-down faq-toggle"></i>
              </div>
              <div class="faq-answer">
                <p>
                  Costs vary based on project requirements and complexity. We provide 
                  detailed quotes after the initial consultation, with transparent 
                  pricing and no hidden charges.
                </p>
              </div>
            </div>

            <div class="faq-item">
              <div class="faq-question">
                <span>Do you provide ongoing support?</span>
                <i class="fas fa-chevron-down faq-toggle"></i>
              </div>
              <div class="faq-answer">
                <p>
                  Yes, we provide comprehensive support throughout the entire process 
                  and offer post-completion assistance to ensure your satisfaction 
                  with our services.
                </p>
              </div>
            </div>
          <?php endif; ?>
        </div>
      </div>
    </section>

    <!-- Contact Form Section -->
    <section class="contact-form-section" id="contact-form">
      <div class="container">
        <h2 class="section-title">Get in Touch</h2>
        <p style="text-align: center;">
          Fill out the form below, and one of our registration specialists will
          contact you within 24 hours.
        </p>

        <div class="contact-container">
          <form class="contact-form">
            <div class="form-group">
              <label for="name">Full Name</label>
              <input
                type="text"
                id="name"
                name="name"
                placeholder="Your full name"
                required
              />
            </div>

            <div class="form-group">
              <label for="email">Email Address</label>
              <input
                type="email"
                id="email"
                name="email"
                placeholder="Your email address"
                required
              />
            </div>

            <div class="form-group">
              <label for="phone">Phone Number</label>
              <input
                type="tel"
                id="phone"
                name="phone"
                placeholder="Your phone number"
                required
              />
            </div>

            <div class="form-group">
              <label for="property-type">Property Type</label>
              <select id="property-type" name="property-type" required>
                <option value="" disabled selected>Select property type</option>
                <option value="residential">Residential</option>
                <option value="commercial">Commercial</option>
                <option value="agricultural">Agricultural</option>
                <option value="mixed-use">Mixed-Use</option>
                <option value="other">Other</option>
              </select>
            </div>

            <div class="form-group">
              <label for="property-location">Property Location</label>
              <input
                type="text"
                id="property-location"
                name="property-location"
                placeholder="District, Sector, Cell"
                required
              />
            </div>

            <div class="form-group full-width">
              <label for="message">Additional Information</label>
              <textarea
                id="message"
                name="message"
                rows="5"
                placeholder="Please provide any additional details about your property or specific requirements"
              ></textarea>
            </div>

            <div class="form-group full-width">
              <button type="submit" class="submit-button">
                Submit Inquiry
              </button>
            </div>
          </form>

          <div class="service-contact-info">
            <div class="info-card">
              <div class="info-icon">
                <i class="fas fa-map-marker-alt"></i>
              </div>
              <div class="info-content">
                <h3>Our Office</h3>
                <p><?php echo getSetting('company_address', 'Kigali, Rwanda'); ?></p>
              </div>
            </div>

            <div class="info-card">
              <div class="info-icon">
                <i class="fas fa-phone-alt"></i>
              </div>
              <div class="info-content">
                <h3>Phone</h3>
                <p><?php echo getSetting('company_phone', '+250 788 331 697'); ?></p>
              </div>
            </div>

            <div class="info-card">
              <div class="info-icon">
                <i class="fas fa-envelope"></i>
              </div>
              <div class="info-content">
                <h3>Email</h3>
                <p><?php echo getSetting('company_email', 'fsamcompanyltd@gmail.com'); ?></p>
              </div>
            </div>

            <div class="info-card">
              <div class="info-icon">
                <i class="fas fa-clock"></i>
              </div>
              <div class="info-content">
                <h3>Working Hours</h3>
                <p>
                  Monday - Friday: 8:00 AM - 5:00 PM<br />Saturday: 9:00 AM -
                  1:00 PM
                </p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Related Services Section -->
    <section class="related-services">
      <div class="container">
        <h2 class="section-title">Related Services</h2>
        <p style="text-align: center;">
          Explore our other professional surveying and mapping services that
          might be of interest.
        </p>

        <div class="related-services-grid">
          <?php if (!empty($relatedServices)): ?>
            <?php foreach ($relatedServices as $relatedService): ?>
            <div class="related-service-card">
              <div class="related-service-image">
                <img
                  src="<?php echo getFileUrl($relatedService['image']); ?>"
                  alt="<?php echo htmlspecialchars($relatedService['title']); ?>"
                />
              </div>
              <div class="related-service-content">
                <h3><?php echo htmlspecialchars($relatedService['title']); ?></h3>
                <p>
                  <?php echo htmlspecialchars($relatedService['short_description'] ?: substr($relatedService['description'], 0, 100) . '...'); ?>
                </p>
                <a href="./service_view_more.php?slug=<?php echo urlencode($relatedService['slug']); ?>" class="service-link">
                  Learn More <i class="fas fa-arrow-right"></i>
                </a>
              </div>
            </div>
            <?php endforeach; ?>
          <?php else: ?>
            <div class="related-service-card">
              <div class="related-service-content">
                <h3>More Services</h3>
                <p>Explore our comprehensive range of surveying and mapping services.</p>
                <a href="./services.php" class="service-link">
                  View All Services <i class="fas fa-arrow-right"></i>
                </a>
              </div>
            </div>
          <?php endif; ?>
        </div>
      </div>
    </section>

    <!-- Footer -->
    <?php include 'includes/footer.php'; ?>
    
    <script src="../js/script.js"></script>
    <script>
      // FAQ Accordion
      document.querySelectorAll(".faq-question").forEach((question) => {
        question.addEventListener("click", () => {
          const item = question.parentElement;
          const isActive = item.classList.contains("active");

          // Close all FAQ items
          document.querySelectorAll(".faq-item").forEach((faqItem) => {
            faqItem.classList.remove("active");
          });

          // If clicked item wasn't active, open it
          if (!isActive) {
            item.classList.add("active");
          }
        });
      });
    </script>
  </body>
</html>
