<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Contact Us - Banner Fair Surveying & Mapping Ltd</title>
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

          <div class="contact-info-item">
            <div class="contact-icon">
              <i class="fas fa-map-marker-alt"></i>
            </div>
            <div class="contact-text">
              <h4>Our Location</h4>
              <p>Kigali, Rwanda</p>
            </div>
          </div>

          <div class="contact-info-item">
            <div class="contact-icon">
              <i class="fas fa-phone-alt"></i>
            </div>
            <div class="contact-text">
              <h4>Phone Number</h4>
              <p><a href="tel:0788331697">0788331697</a></p>
            </div>
          </div>

          <div class="contact-info-item">
            <div class="contact-icon">
              <i class="fas fa-envelope"></i>
            </div>
            <div class="contact-text">
              <h4>Email Address</h4>
              <p>
                <a href="mailto:fsamcompanyltd@gmail.com"
                  >fsamcompanyltd@gmail.com</a
                >
              </p>
            </div>
          </div>

          <div class="contact-info-item">
            <div class="contact-icon">
              <i class="fas fa-user-tie"></i>
            </div>
            <div class="contact-text">
              <h4>Professional Details</h4>
              <p>HATANGIMANA Fulgence<br />Surveyor code: LS00280</p>
            </div>
          </div>

          <div class="social-links">
            <h3>Follow Us</h3>
            <div class="social-icons">
              <div class="social-icon">
                <i class="fab fa-facebook-f"></i>
              </div>
              <div class="social-icon">
                <i class="fab fa-twitter"></i>
              </div>
              <div class="social-icon">
                <i class="fab fa-linkedin-in"></i>
              </div>
              <div class="social-icon">
                <i class="fab fa-instagram"></i>
              </div>
            </div>
          </div>

          <div class="business-hours">
            <h3>Business Hours</h3>
            <div class="hours-item">
              <span class="day">Monday - Friday:</span>
              <span class="time">8:00 AM - 5:00 PM</span>
            </div>
            <div class="hours-item">
              <span class="day">Saturday:</span>
              <span class="time">9:00 AM - 1:00 PM</span>
            </div>
            <div class="hours-item">
              <span class="day">Sunday:</span>
              <span class="time">Closed</span>
            </div>
          </div>
        </div>

        <div class="contact-form-section">
          <h2>Send Us A Message</h2>
          <p class="contact-form-intro">
            Have questions about our surveying services? Fill out the form below
            and we'll get back to you as soon as possible.
          </p>

          <div class="success-message" id="successMessage">
            <i class="fas fa-check-circle"></i> Your message has been sent
            successfully. We'll get back to you soon!
          </div>

          <div class="error-message" id="errorMessage">
            <i class="fas fa-exclamation-circle"></i> There was an error sending
            your message. Please try again.
          </div>

          <form id="contactForm">
            <div class="form-row">
              <div class="form-group">
                <label for="name" class="form-label">Your Name</label>
                <input
                  type="text"
                  class="form-control"
                  id="name"
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
                  placeholder="Enter your phone number"
                />
              </div>
              <div class="form-group">
                <label for="subject" class="form-label">Subject</label>
                <input
                  type="text"
                  class="form-control"
                  id="subject"
                  placeholder="Enter subject"
                />
              </div>
            </div>

            <div class="form-group">
              <label for="service" class="form-label"
                >Service Interested In</label
              >
              <select class="form-control" id="service">
                <option value="" selected disabled>Select a service</option>
                <option value="Land Surveying">Land Surveying</option>
                <option value="First Registration">First Registration</option>
                <option value="Merging Land Parcels">
                  Merging Land Parcels
                </option>
                <option value="Land Subdivision">Land Subdivision</option>
                <option value="Boundary Correction">Boundary Correction</option>
                <option value="Building Permits">Building Permits</option>
                <option value="Road Consultancy">Road Consultancy</option>
                <option value="House Plans">House Plans</option>
                <option value="Environmental Impact Assessment">
                  Environmental Impact Assessment
                </option>
                <option value="Technical Training">Technical Training</option>
                <option value="Other">Other</option>
              </select>
            </div>

            <div class="form-group">
              <label for="message" class="form-label">Your Message</label>
              <textarea
                class="form-control"
                id="message"
                rows="5"
                placeholder="Type your message here..."
                required
              ></textarea>
            </div>

            <button type="submit" class="btn-submit">
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

      <div class="services-section">
        <div class="services-title">
          <h2>Our Services</h2>
          <div class="title-underline"></div>
        </div>

        <div class="services-grid">
          <div class="service-card">
            <div class="service-icon">
              <i class="fas fa-map"></i>
            </div>
            <h3 class="service-title">Land Surveying & Mapping</h3>
            <p class="service-desc">
              Professional land surveying services including first registration,
              merging land parcels, land subdivision, and boundary correction.
            </p>
          </div>

          <div class="service-card">
            <div class="service-icon">
              <i class="fas fa-building"></i>
            </div>
            <h3 class="service-title">Building & Construction</h3>
            <p class="service-desc">
              We assist with building permits, road consultancy, and house plans
              design to support your construction projects.
            </p>
          </div>

          <div class="service-card">
            <div class="service-icon">
              <i class="fas fa-leaf"></i>
            </div>
            <h3 class="service-title">Environmental Consultancy</h3>
            <p class="service-desc">
              Environmental Impact Assessment (EIA) services to ensure your
              projects meet environmental standards and regulations.
            </p>
          </div>

          <div class="service-card">
            <div class="service-icon">
              <i class="fas fa-laptop-code"></i>
            </div>
            <h3 class="service-title">Technical Training</h3>
            <p class="service-desc">
              Training on surveying equipment & software, Python for data
              analysis, GIS & Remote Sensing, and Artificial Intelligence.
            </p>
          </div>

          <div class="service-card">
            <div class="service-icon">
              <i class="fas fa-tools"></i>
            </div>
            <h3 class="service-title">Equipment & Software</h3>
            <p class="service-desc">
              Access to professional surveying equipment including Total
              Station, as well as software solutions like AutoCAD & ArcGIS.
            </p>
          </div>

          <div class="service-card">
            <div class="service-icon">
              <i class="fas fa-search"></i>
            </div>
            <h3 class="service-title">Research Support</h3>
            <p class="service-desc">
              Comprehensive research support services for geospatial analysis,
              environmental studies, and data-driven projects.
            </p>
          </div>
        </div>
      </div>
    </main>

    <?php include 'includes/footer.php'; ?>

    <script src="../js/script.js"></script>
  </body>
</html>
