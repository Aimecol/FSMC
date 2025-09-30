<?php
// Define access constant
define('FSMC_ACCESS', true);

// Include database configuration
require_once './config/database.php';
require_once '../config/email_config.php';

// Handle contact form submission
$formMessage = '';
$formMessageType = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_contact'])) {
    try {
        // Sanitize and validate form data
        $name = trim($_POST['name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $phone = trim($_POST['phone'] ?? '');
        $subject = trim($_POST['subject'] ?? '');
        $serviceInterest = trim($_POST['service'] ?? '');
        $message = trim($_POST['message'] ?? '');
        
        // Basic validation
        if (empty($name) || empty($email) || empty($message)) {
            throw new Exception('Please fill in all required fields.');
        }
        
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new Exception('Please enter a valid email address.');
        }
        
        // Prepare contact data for email
        $contactData = [
            'name' => $name,
            'email' => $email,
            'phone' => $phone,
            'subject' => $subject,
            'service' => $serviceInterest,
            'message' => $message,
            'ip_address' => $_SERVER['REMOTE_ADDR'] ?? '',
            'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? ''
        ];
        
        // Insert into database
        $stmt = getDB()->query("
            INSERT INTO contact_messages (name, email, phone, subject, service_interest, message, ip_address, user_agent)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)
        ", [
            $name,
            $email,
            $phone,
            $subject,
            $serviceInterest,
            $message,
            $_SERVER['REMOTE_ADDR'] ?? '',
            $_SERVER['HTTP_USER_AGENT'] ?? ''
        ]);
        
        // Send email notification using the ultimate method (tries everything)
        $emailSent = sendContactEmailUltimate($contactData);
        
        if ($emailSent) {
            $formMessage = 'Your message has been sent successfully! We\'ll get back to you within 24 hours.';
            $formMessageType = 'success';
        } else {
            $formMessage = 'Your message has been saved successfully. We will review it and get back to you soon.';
            $formMessageType = 'success'; // Still show success since message was saved
            error_log('Contact form email failed for: ' . $email);
        }
        
        // Clear form data on success
        $_POST = [];
        
    } catch (Exception $e) {
        $formMessage = 'Error: ' . $e->getMessage();
        $formMessageType = 'error';
    }
}

// Fetch contact information
$contactInfo = [];
$stmt = dbGetRows("SELECT * FROM contact_info WHERE is_active = 1 ORDER BY sort_order ASC");
foreach ($stmt as $row) {
    $contactInfo[$row['info_key']] = $row;
}

// Fetch business hours
$businessHours = dbGetRows("SELECT * FROM business_hours WHERE is_active = 1 ORDER BY sort_order ASC");

// Get company settings
$companySettings = getCompanySettings();

// Helper function to format contact value with link
function formatContactValue($info) {
    $value = htmlspecialchars($info['value']);
    
    switch ($info['link_type']) {
        case 'tel':
            return '<a href="tel:' . htmlspecialchars($info['value']) . '">' . $value . '</a>';
        case 'mailto':
            return '<a href="mailto:' . htmlspecialchars($info['value']) . '">' . $value . '</a>';
        case 'url':
            return '<a href="' . htmlspecialchars($info['value']) . '" target="_blank">' . $value . '</a>';
        default:
            return nl2br($value);
    }
}

// Helper function to format business hours
function formatBusinessHours($hours) {
    if ($hours['is_closed']) {
        return $hours['custom_text'] ?: 'Closed';
    }
    
    if ($hours['opening_time'] && $hours['closing_time']) {
        return date('g:i A', strtotime($hours['opening_time'])) . ' - ' . date('g:i A', strtotime($hours['closing_time']));
    }
    
    return $hours['custom_text'] ?: 'Contact for hours';
}
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Contact Us - <?php echo htmlspecialchars(getSetting('company_name', 'Fair Surveying & Mapping Ltd')); ?></title>
    <meta name="description" content="Get in touch with <?php echo htmlspecialchars(getSetting('company_name', 'Fair Surveying & Mapping Ltd')); ?> for professional surveying and mapping services.">
    <link rel="icon" type="image/svg+xml" href="../images/logo.png" />
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css"
    />
    <link rel="stylesheet" href="../css/style.css" />
    <link rel="stylesheet" href="../css/contact.css" />
  </head>
  <body>
    <?php include 'includes/header.php'; ?>

    <main class="contact-page">
      <div class="page-title">
        <h1>Contact Us</h1>
        <div class="title-underline"></div>
      </div>

      <div class="contact-container">
        <div class="contact-info-section">
          <h2>Get In Touch</h2>

          <?php foreach ($contactInfo as $info): ?>
          <div class="contact-info-item">
            <div class="contact-icon">
              <i class="<?php echo htmlspecialchars($info['icon'] ?: 'fas fa-info-circle'); ?>"></i>
            </div>
            <div class="contact-text">
              <h4><?php echo htmlspecialchars($info['title']); ?></h4>
              <p><?php echo formatContactValue($info); ?></p>
            </div>
          </div>
          <?php endforeach; ?>

          <div class="social-links">
            <h3>Follow Us</h3>
            <div class="social-icons">
              <?php if (!empty(getSetting('facebook_url'))): ?>
              <a href="<?php echo htmlspecialchars(getSetting('facebook_url')); ?>" target="_blank" class="social-icon">
                <i class="fab fa-facebook-f"></i>
              </a>
              <?php endif; ?>
              
              <?php if (!empty(getSetting('twitter_url'))): ?>
              <a href="<?php echo htmlspecialchars(getSetting('twitter_url')); ?>" target="_blank" class="social-icon">
                <i class="fab fa-twitter"></i>
              </a>
              <?php endif; ?>
              
              <?php if (!empty(getSetting('linkedin_url'))): ?>
              <a href="<?php echo htmlspecialchars(getSetting('linkedin_url')); ?>" target="_blank" class="social-icon">
                <i class="fab fa-linkedin-in"></i>
              </a>
              <?php endif; ?>
              
              <?php if (!empty(getSetting('instagram_url'))): ?>
              <a href="<?php echo htmlspecialchars(getSetting('instagram_url')); ?>" target="_blank" class="social-icon">
                <i class="fab fa-instagram"></i>
              </a>
              <?php endif; ?>
            </div>
          </div>

          <div class="business-hours">
            <h3>Business Hours</h3>
            <?php foreach ($businessHours as $hours): ?>
            <div class="hours-item">
              <span class="day"><?php echo htmlspecialchars($hours['day_label']); ?>:</span>
              <span class="time"><?php echo formatBusinessHours($hours); ?></span>
            </div>
            <?php endforeach; ?>
          </div>
        </div>

        <div class="contact-form-section">
          <h2>Send Us A Message</h2>
          <p class="contact-form-intro">
            Have questions about our surveying services? Fill out the form below
            and we'll get back to you as soon as possible.
          </p>

          <?php if ($formMessage): ?>
          <div class="<?php echo $formMessageType === 'success' ? 'success-message' : 'error-message'; ?>" style="display: block;">
            <i class="fas fa-<?php echo $formMessageType === 'success' ? 'check-circle' : 'exclamation-circle'; ?>"></i> 
            <?php echo htmlspecialchars($formMessage); ?>
          </div>
          <?php endif; ?>

          <div class="success-message" id="successMessage" style="display: none;">
            <i class="fas fa-check-circle"></i> Your message has been sent
            successfully. We'll get back to you soon!
          </div>

          <div class="error-message" id="errorMessage" style="display: none;">
            <i class="fas fa-exclamation-circle"></i> There was an error sending
            your message. Please try again.
          </div>

          <form id="contactForm" method="POST">
            <div class="form-row">
              <div class="form-group">
                <label for="name" class="form-label">Your Name</label>
                <input
                  type="text"
                  class="form-control"
                  id="name"
                  name="name"
                  value="<?php echo htmlspecialchars($_POST['name'] ?? ''); ?>"
                  placeholder="Enter your name"
                  required
                />
              </div>
              <div class="form-group">
                <label for="email" class="form-label">Email Address</label>
                <input
                  type="email"
                  class="form-control"
                  id="email"
                  name="email"
                  value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>"
                  placeholder="Enter your email"
                  required
                />
              </div>
            </div>

            <div class="form-row">
              <div class="form-group">
                <label for="phone" class="form-label">Phone Number</label>
                <input
                  type="tel"
                  class="form-control"
                  id="phone"
                  name="phone"
                  value="<?php echo htmlspecialchars($_POST['phone'] ?? ''); ?>"
                  placeholder="Enter your phone number"
                />
              </div>
              <div class="form-group">
                <label for="subject" class="form-label">Subject</label>
                <input
                  type="text"
                  class="form-control"
                  id="subject"
                  name="subject"
                  value="<?php echo htmlspecialchars($_POST['subject'] ?? ''); ?>"
                  placeholder="Enter subject"
                />
              </div>
            </div>

            <div class="form-group">
              <label for="service" class="form-label"
                >Service Interested In</label
              >
              <select class="form-control" id="service" name="service">
                <option value="" selected disabled>Select a service</option>
                <?php 
                // Fetch active services for dropdown
                $services = dbGetRows("SELECT title FROM services WHERE status = 'active' ORDER BY sort_order ASC, title ASC");
                foreach ($services as $service): 
                    $selected = (isset($_POST['service']) && $_POST['service'] === $service['title']) ? 'selected' : '';
                ?>
                <option value="<?php echo htmlspecialchars($service['title']); ?>" <?php echo $selected; ?>>
                  <?php echo htmlspecialchars($service['title']); ?>
                </option>
                <?php endforeach; ?>
                <option value="Other" <?php echo (isset($_POST['service']) && $_POST['service'] === 'Other') ? 'selected' : ''; ?>>Other</option>
              </select>
            </div>

            <div class="form-group">
              <label for="message" class="form-label">Your Message</label>
              <textarea
                class="form-control"
                id="message"
                name="message"
                rows="5"
                placeholder="Type your message here..."
                required
              ><?php echo htmlspecialchars($_POST['message'] ?? ''); ?></textarea>
            </div>

            <button type="submit" name="submit_contact" class="btn-submit">
              <i class="fas fa-paper-plane"></i> Send Message
            </button>
          </form>
        </div>
      </div>

      <div class="map-section">
        <div class="map-container">
          <div class="map-placeholder">
            <iframe
              src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d6159.236393519709!2d29.61090679999999!3d-1.5043718999999953!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x19dc5b0075527317%3A0x39698d2e1df81fe!2sKigali%20Ceramics%20warehouse%20musanze!5e1!3m2!1sen!2srw!4v1742725628035!5m2!1sen!2srw"
              width="600"
              height="450"
              style="border: 0"
              allowfullscreen=""
              loading="lazy"
              referrerpolicy="no-referrer-when-downgrade"
            ></iframe>
          </div>
        </div>
      </div>
    </main>

    <?php include 'includes/footer.php'; ?>

    <script src="../js/script.js"></script>
  </body>
</html>
