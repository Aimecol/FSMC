<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Course Enrollment - Fair Surveying & Mapping Ltd</title>
    <link rel="icon" type="image/svg+xml" href="../images/logo.png" />
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
    />
    <link rel="stylesheet" href="../css/style.css" />
    <link rel="stylesheet" href="../css/training.css" />
    <link rel="stylesheet" href="../css/enrollment.css" />
  </head>
  <body>
    <!-- Header Section -->
    <?php include 'includes/header.php'; ?>

    <!-- Main Content -->
    <main class="main-content">
      <!-- Hero Section -->
      <section class="enrollment-hero">
        <div class="container">
          <div class="enrollment-hero-content" data-aos="fade-up">
            <h1>Course Enrollment</h1>
            <p>
              Complete your registration to secure your spot in our professional
              training programs
            </p>
            <div class="breadcrumbs">
              <a href="../index.html">Home</a>
              <i class="fas fa-chevron-right"></i>
              <a href="training.html">Training</a>
              <i class="fas fa-chevron-right"></i>
              <span>Enrollment</span>
            </div>
          </div>
        </div>
      </section>

      <!-- Progress Tracker -->
      <section class="progress-tracker">
        <div class="container">
          <div class="progress-steps">
            <div class="progress-step active" data-step="1">
              <div class="step-icon">
                <i class="fas fa-user"></i>
              </div>
              <div class="step-label">Personal Info</div>
            </div>
            <div class="progress-connector"></div>
            <div class="progress-step" data-step="2">
              <div class="step-icon">
                <i class="fas fa-graduation-cap"></i>
              </div>
              <div class="step-label">Course Selection</div>
            </div>
            <div class="progress-connector"></div>
            <div class="progress-step" data-step="3">
              <div class="step-icon">
                <i class="fas fa-credit-card"></i>
              </div>
              <div class="step-label">Payment</div>
            </div>
            <div class="progress-connector"></div>
            <div class="progress-step" data-step="4">
              <div class="step-icon">
                <i class="fas fa-check-circle"></i>
              </div>
              <div class="step-label">Confirmation</div>
            </div>
          </div>
        </div>
      </section>

      <!-- Enrollment Form Section -->
      <section class="enrollment-form-section">
        <div class="container">
          <div class="enrollment-layout">
            <!-- Main Form Area -->
            <div class="form-container" data-aos="fade-up">
              <form id="enrollmentForm">
                <!-- Step 1: Personal Information -->
                <div class="form-step active" id="step1">
                  <h2>Personal Information</h2>
                  <p class="form-intro">
                    Please provide your personal details so we can contact you
                    regarding your enrollment.
                  </p>

                  <div class="form-row">
                    <div class="form-group">
                      <label for="firstName"
                        >First Name <span class="required">*</span></label
                      >
                      <input
                        type="text"
                        id="firstName"
                        name="firstName"
                        required
                      />
                      <span class="error-message"></span>
                    </div>
                    <div class="form-group">
                      <label for="lastName"
                        >Last Name <span class="required">*</span></label
                      >
                      <input
                        type="text"
                        id="lastName"
                        name="lastName"
                        required
                      />
                      <span class="error-message"></span>
                    </div>
                  </div>

                  <div class="form-row">
                    <div class="form-group">
                      <label for="email"
                        >Email Address <span class="required">*</span></label
                      >
                      <input type="email" id="email" name="email" required />
                      <span class="error-message"></span>
                    </div>
                    <div class="form-group">
                      <label for="phone"
                        >Phone Number <span class="required">*</span></label
                      >
                      <input type="tel" id="phone" name="phone" required />
                      <span class="error-message"></span>
                    </div>
                  </div>

                  <div class="form-row">
                    <div class="form-group">
                      <label for="company"
                        >Company/Organization (Optional)</label
                      >
                      <input type="text" id="company" name="company" />
                    </div>
                    <div class="form-group">
                      <label for="position">Job Title (Optional)</label>
                      <input type="text" id="position" name="position" />
                    </div>
                  </div>

                  <div class="form-row">
                    <div class="form-group full-width">
                      <label for="address">Address</label>
                      <input type="text" id="address" name="address" />
                    </div>
                  </div>

                  <div class="form-row">
                    <div class="form-group">
                      <label for="city">City</label>
                      <input type="text" id="city" name="city" />
                    </div>
                    <div class="form-group">
                      <label for="country"
                        >Country <span class="required">*</span></label
                      >
                      <select id="country" name="country" required>
                        <option value="">-- Select Country --</option>
                        <option value="Rwanda">Rwanda</option>
                        <option value="Kenya">Kenya</option>
                        <option value="Uganda">Uganda</option>
                        <option value="Tanzania">Tanzania</option>
                        <option value="Burundi">Burundi</option>
                        <option value="DRC">
                          Democratic Republic of Congo
                        </option>
                        <option value="Other">Other</option>
                      </select>
                      <span class="error-message"></span>
                    </div>
                  </div>

                  <div class="form-navigation">
                    <button type="button" class="btn-next" data-next="2">
                      Continue to Course Selection
                      <i class="fas fa-arrow-right"></i>
                    </button>
                  </div>
                </div>

                <!-- Step 2: Course Selection -->
                <div class="form-step" id="step2">
                  <h2>Course Selection</h2>
                  <p class="form-intro">
                    Choose the course you'd like to enroll in and your preferred
                    schedule.
                  </p>

                  <div class="form-row">
                    <div class="form-group full-width">
                      <label for="courseCategory"
                        >Course Category <span class="required">*</span></label
                      >
                      <select
                        id="courseCategory"
                        name="courseCategory"
                        required
                      >
                        <option value="">-- Select Category --</option>
                        <option value="surveying">Surveying Equipment</option>
                        <option value="software">Software & Data</option>
                        <option value="gis">GIS & Remote Sensing</option>
                        <option value="advanced">Advanced Technologies</option>
                      </select>
                      <span class="error-message"></span>
                    </div>
                  </div>

                  <div class="form-row">
                    <div class="form-group full-width">
                      <label for="courseSelect"
                        >Course <span class="required">*</span></label
                      >
                      <select
                        id="courseSelect"
                        name="courseSelect"
                        required
                        disabled
                      >
                        <option value="">-- Select Course --</option>
                      </select>
                      <span class="error-message"></span>
                    </div>
                  </div>

                  <div class="course-details" id="courseDetails">
                    <!-- Course details will be displayed here -->
                  </div>

                  <div class="form-row">
                    <div class="form-group">
                      <label for="sessionDate"
                        >Preferred Start Date
                        <span class="required">*</span></label
                      >
                      <select
                        id="sessionDate"
                        name="sessionDate"
                        required
                        disabled
                      >
                        <option value="">-- Select Date --</option>
                      </select>
                      <span class="error-message"></span>
                    </div>
                    <div class="form-group">
                      <label for="attendanceMode"
                        >Attendance Mode <span class="required">*</span></label
                      >
                      <select
                        id="attendanceMode"
                        name="attendanceMode"
                        required
                      >
                        <option value="">-- Select Mode --</option>
                        <option value="in-person">In-person</option>
                        <option value="online">Online</option>
                      </select>
                      <span class="error-message"></span>
                    </div>
                  </div>

                  <div class="form-row">
                    <div class="form-group full-width">
                      <label for="specialRequirements"
                        >Special Requirements or Accommodations</label
                      >
                      <textarea
                        id="specialRequirements"
                        name="specialRequirements"
                        rows="3"
                      ></textarea>
                    </div>
                  </div>

                  <div class="form-row">
                    <div class="form-group checkbox-group">
                      <label class="checkbox-container">
                        <input
                          type="checkbox"
                          id="groupDiscount"
                          name="groupDiscount"
                        />
                        <span class="checkmark"></span>
                        I am registering as part of a group (3+ participants)
                      </label>
                    </div>
                  </div>

                  <div class="form-navigation">
                    <button type="button" class="btn-prev" data-prev="1">
                      <i class="fas fa-arrow-left"></i>
                      Back
                    </button>
                    <button type="button" class="btn-next" data-next="3">
                      Continue to Payment
                      <i class="fas fa-arrow-right"></i>
                    </button>
                  </div>
                </div>

                <!-- Step 3: Payment Information -->
                <div class="form-step" id="step3">
                  <h2>Payment Information</h2>
                  <p class="form-intro">
                    Choose your payment method to complete registration.
                  </p>

                  <div class="order-summary">
                    <h3>Order Summary</h3>
                    <div class="summary-details">
                      <div class="summary-row">
                        <span>Course:</span>
                        <span id="summaryCourseName">--</span>
                      </div>
                      <div class="summary-row">
                        <span>Start Date:</span>
                        <span id="summaryDate">--</span>
                      </div>
                      <div class="summary-row">
                        <span>Attendance Mode:</span>
                        <span id="summaryMode">--</span>
                      </div>
                      <div class="summary-row">
                        <span>Course Fee:</span>
                        <span id="summaryPrice">--</span>
                      </div>
                      <div class="summary-row discount hidden" id="discountRow">
                        <span>Group Discount (10%):</span>
                        <span id="summaryDiscount">--</span>
                      </div>
                      <div class="summary-row total">
                        <span>Total:</span>
                        <span id="summaryTotal">--</span>
                      </div>
                    </div>
                  </div>

                  <div class="form-row">
                    <div class="form-group checkbox-group">
                      <label class="checkbox-container">
                        <input
                          type="checkbox"
                          id="terms"
                          name="terms"
                          required
                        />
                        <span class="checkmark"></span>
                        I agree to the
                        <a href="#" class="terms-link">Terms and Conditions</a>
                        and <a href="#" class="terms-link">Privacy Policy</a>
                      </label>
                      <span class="error-message"></span>
                    </div>
                  </div>

                  <div class="form-navigation">
                    <button type="button" class="btn-prev" data-prev="2">
                      <i class="fas fa-arrow-left"></i>
                      Back
                    </button>
                    <button
                      type="button"
                      class="btn-submit"
                      id="completeEnrollment"
                    >
                      Complete Enrollment
                      <i class="fas fa-check-circle"></i>
                    </button>
                  </div>
                </div>

                <!-- Step 4: Confirmation (Added dynamically after submission) -->
                <div class="form-step" id="step4">
                  <div class="confirmation-content">
                    <div class="confirmation-icon">
                      <i class="fas fa-check-circle"></i>
                    </div>
                    <h2>Enrollment Successful!</h2>
                    <p>
                      Thank you for enrolling in our training program. We've
                      sent a confirmation email to
                      <strong id="confirmationEmail"></strong> with all the
                      details.
                    </p>

                    <div class="enrollment-details">
                      <h3>Enrollment Details</h3>
                      <div class="detail-row">
                        <span>Enrollment ID:</span>
                        <span id="enrollmentId"></span>
                      </div>
                      <div class="detail-row">
                        <span>Course:</span>
                        <span id="confirmationCourse"></span>
                      </div>
                      <div class="detail-row">
                        <span>Start Date:</span>
                        <span id="confirmationDate"></span>
                      </div>
                      <div class="detail-row">
                        <span>Location:</span>
                        <span id="confirmationLocation"
                          >Kigali Training Center</span
                        >
                      </div>
                    </div>

                    <div class="next-steps">
                      <h3>Next Steps</h3>
                      <ul>
                        <li>
                          <i class="fas fa-envelope"></i> Check your email for
                          detailed instructions
                        </li>
                        <li>
                          <i class="fas fa-download"></i> Download course
                          materials prior to the start date
                        </li>
                        <li>
                          <i class="fas fa-user-plus"></i> Complete your
                          participant profile
                        </li>
                        <li>
                          <i class="fas fa-question-circle"></i> Contact us if
                          you have any questions
                        </li>
                      </ul>
                    </div>

                    <div class="confirmation-actions">
                      <a href="training.html" class="btn-secondary">
                        <i class="fas fa-arrow-left"></i>
                        Return to Training
                      </a>
                      <a href="#" class="btn-primary" id="downloadReceipt">
                        <i class="fas fa-file-invoice"></i>
                        Download Receipt
                      </a>
                    </div>
                  </div>
                </div>
              </form>
            </div>

            <!-- Side Information -->
            <div class="enrollment-sidebar" data-aos="fade-left">
              <div class="sidebar-section help-section">
                <h3><i class="fas fa-question-circle"></i> Need Help?</h3>
                <p>
                  Our team is here to assist you with the enrollment process.
                </p>
                <div class="contact-options">
                  <a href="tel:0788331697" class="contact-option">
                    <i class="fas fa-phone"></i>
                    <span>Call Us: 0788331697</span>
                  </a>
                  <a
                    href="mailto:training@fairsurveying.com"
                    class="contact-option"
                  >
                    <i class="fas fa-envelope"></i>
                    <span>Email: training@fairsurveying.com</span>
                  </a>
                </div>
              </div>

              <div class="sidebar-section why-enroll">
                <h3><i class="fas fa-star"></i> Why Enroll With Us</h3>
                <ul class="benefits-list">
                  <li>
                    <i class="fas fa-check"></i> Expert instructors with
                    real-world experience
                  </li>
                  <li>
                    <i class="fas fa-check"></i> Hands-on training with
                    industry-standard equipment
                  </li>
                  <li>
                    <i class="fas fa-check"></i> Professional certification
                    recognized in the industry
                  </li>
                  <li>
                    <i class="fas fa-check"></i> Job placement assistance for
                    top performers
                  </li>
                  <li>
                    <i class="fas fa-check"></i> Flexible payment options and
                    group discounts
                  </li>
                </ul>
              </div>
            </div>
          </div>
        </div>
      </section>
    </main>

    <!-- Footer -->
    <?php include 'includes/footer.php'; ?>

    <!-- Terms Modal -->
    <div class="modal" id="termsModal">
      <div class="modal-content">
        <div class="modal-header">
          <h2>Terms and Conditions</h2>
          <button class="modal-close" id="closeTermsModal">
            <i class="fas fa-times"></i>
          </button>
        </div>
        <div class="modal-body">
          <h3>Enrollment Terms</h3>
          <p>
            By enrolling in courses offered by Fair Surveying & Mapping Ltd, you
            agree to the following terms and conditions:
          </p>

          <h4>1. Payment and Fees</h4>
          <p>
            Full payment is required to confirm your enrollment. The fee
            includes course materials, but does not include transportation or
            accommodation.
          </p>

          <h4>2. Cancellation Policy</h4>
          <p>
            Cancellations made 14 or more days before the course start date will
            receive a full refund. Cancellations between 7-13 days will receive
            a 75% refund. No refunds will be provided for cancellations less
            than 7 days before the course start date.
          </p>

          <h4>3. Rescheduling</h4>
          <p>
            You may reschedule your enrollment to another available date at no
            additional cost, provided that the request is made at least 7 days
            prior to the original course date.
          </p>

          <h4>4. Attendance</h4>
          <p>
            Participants are expected to attend all sessions of the course. Fair
            Surveying & Mapping Ltd reserves the right to withhold certification
            if a participant misses more than 20% of the course.
          </p>

          <h4>5. Intellectual Property</h4>
          <p>
            All course materials provided by Fair Surveying & Mapping Ltd are
            protected by copyright. Participants may not reproduce, distribute,
            or create derivative works without explicit permission.
          </p>

          <h4>6. Conduct</h4>
          <p>
            Participants are expected to conduct themselves professionally
            during all training activities. Fair Surveying & Mapping Ltd
            reserves the right to dismiss any participant for disruptive or
            inappropriate behavior without refund.
          </p>

          <h4>7. Photography and Recording</h4>
          <p>
            Fair Surveying & Mapping Ltd may take photographs or record sessions
            for promotional purposes. By enrolling, you consent to appearing in
            such media unless you explicitly notify us otherwise.
          </p>

          <h4>8. Certification</h4>
          <p>
            Certificates will be issued only to participants who successfully
            complete the course requirements, including any assessments.
          </p>

          <h4>9. Program Changes</h4>
          <p>
            Fair Surveying & Mapping Ltd reserves the right to change the
            content, timing, or venue of courses due to unforeseen
            circumstances. In such cases, participants will be notified as early
            as possible.
          </p>

          <h4>10. Liability</h4>
          <p>
            Fair Surveying & Mapping Ltd is not liable for any loss, damage, or
            injury incurred during participation in our training programs.
          </p>
        </div>
        <div class="modal-footer">
          <button class="btn-primary" id="acceptTerms">I Accept</button>
        </div>
      </div>
    </div>

    <script src="../js/script.js"></script>
    <script src="../js/enrollment.js"></script>
  </body>
</html>
