<?php
// Include database configuration
require_once 'config/database.php';

// Get company settings
$settings = getCompanySettings();
$companyName = getSetting('company_name', 'Fair Surveying & Mapping Ltd');

// Get all training programs
$trainingPrograms = dbGetRows("SELECT * FROM training_programs WHERE status = 'active' ORDER BY sort_order ASC, created_at DESC");

// Group programs by category for filtering
$programsByCategory = [];
foreach ($trainingPrograms as $program) {
    if (!empty($program['category'])) {
        $programsByCategory[$program['category']][] = $program;
    }
}

// Get training statistics
$totalPrograms = count($trainingPrograms);
$beginnerPrograms = count(array_filter($trainingPrograms, function($p) { return $p['level'] === 'beginner'; }));
$intermediatePrograms = count(array_filter($trainingPrograms, function($p) { return $p['level'] === 'intermediate'; }));
$advancedPrograms = count(array_filter($trainingPrograms, function($p) { return $p['level'] === 'advanced'; }));

// Categories mapping
$categoryMap = [
    'surveying' => ['label' => 'Surveying Equipment', 'icon' => 'fas fa-compass'],
    'software' => ['label' => 'Software & Data', 'icon' => 'fas fa-laptop-code'],
    'gis' => ['label' => 'GIS & Remote Sensing', 'icon' => 'fas fa-satellite'],
    'advanced' => ['label' => 'Advanced Technologies', 'icon' => 'fas fa-rocket'],
    'mapping' => ['label' => 'Mapping & Cartography', 'icon' => 'fas fa-map'],
    'photogrammetry' => ['label' => 'Photogrammetry', 'icon' => 'fas fa-camera'],
    'drone' => ['label' => 'Drone Technology', 'icon' => 'fas fa-helicopter'],
    'cad' => ['label' => 'CAD & Design', 'icon' => 'fas fa-drafting-compass']
];

// Level colors
$levelColors = [
    'beginner' => 'beginner',
    'intermediate' => 'intermediate', 
    'advanced' => 'advanced'
];
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title><?php echo htmlspecialchars(getSetting('meta_title', 'Professional Training Programs - ' . $companyName)); ?></title>
    <meta name="description" content="<?php echo htmlspecialchars(getSetting('meta_description', 'Enhance your skills with industry-leading training in surveying, mapping, and geospatial technologies.')); ?>" />
    <link rel="icon" type="image/svg+xml" href="../images/logo.png" />
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css"
    />
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.css"
    />
    <link rel="stylesheet" href="../css/style.css" />
    <link rel="stylesheet" href="../css/training.css" />
  </head>
  <body>
    <!-- Header Section -->
    <?php include 'includes/header.php'; ?>

    <!-- Main Content -->
    <main class="main-content">
      <!-- Hero Section -->
      <section class="hero-section">
        <div class="hero-content">
          <h1 data-aos="fade-up">Professional Training Programs</h1>
          <p data-aos="fade-up" data-aos-delay="100">
            Enhance your skills with industry-leading training in surveying,
            mapping, and geospatial technologies
          </p>
          <div class="hero-cta" data-aos="fade-up" data-aos-delay="200">
            <a href="#courses" class="btn-primary"
              ><i class="fas fa-graduation-cap"></i> View Courses</a
            >
          </div>
        </div>
        <div class="hero-image" data-aos="fade-left">
          <img src="../images/9c_NLZ75_JAwFH7mjiGB_.jpg" alt="Professional Training" />
        </div>
      </section>

      <!-- Benefits Section -->
      <section class="benefits-section">
        <div class="container">
          <h2 class="section-title" data-aos="fade-up">Why Train With Us</h2>
          <div class="benefits-grid">
            <div class="benefit-card" data-aos="fade-up" data-aos-delay="100">
              <div class="benefit-icon">
                <i class="fas fa-user-graduate"></i>
              </div>
              <h3>Expert Instructors</h3>
              <p>
                Learn from licensed surveyors with extensive field experience
                and teaching expertise
              </p>
            </div>
            <div class="benefit-card" data-aos="fade-up" data-aos-delay="200">
              <div class="benefit-icon">
                <i class="fas fa-hands"></i>
              </div>
              <h3>Hands-on Practice</h3>
              <p>
                Gain practical experience with industry-standard equipment and
                software
              </p>
            </div>
            <div class="benefit-card" data-aos="fade-up" data-aos-delay="300">
              <div class="benefit-icon">
                <i class="fas fa-certificate"></i>
              </div>
              <h3>Professional Certification</h3>
              <p>
                Earn recognized certificates that enhance your professional
                credentials
              </p>
            </div>
            <div class="benefit-card" data-aos="fade-up" data-aos-delay="400">
              <div class="benefit-icon">
                <i class="fas fa-tools"></i>
              </div>
              <h3>Modern Equipment</h3>
              <p>
                Train using the latest surveying and mapping technology
                available in the industry
              </p>
            </div>
          </div>
        </div>
      </section>

      <!-- Course Categories Section -->
      <section class="categories-section" id="courses">
        <div class="container">
          <h2 class="section-title" data-aos="fade-up">Training Categories</h2>
          <div class="categories-tabs" data-aos="fade-up">
            <button class="tab-btn active" data-category="all">
              All Programs
            </button>
            <?php 
            $availableCategories = array_unique(array_column($trainingPrograms, 'category'));
            foreach ($availableCategories as $category): 
                if (!empty($category) && isset($categoryMap[$category])): ?>
            <button class="tab-btn" data-category="<?php echo htmlspecialchars($category); ?>">
              <?php echo htmlspecialchars($categoryMap[$category]['label']); ?>
            </button>
            <?php endif; endforeach; ?>
          </div>

          <!-- Dynamic Training Programs -->
          <div class="courses-grid active" id="all-courses">
            <?php foreach ($trainingPrograms as $index => $program): 
              $imageUrl = !empty($program['image']) ? getFileUrl($program['image']) : '../images/placeholder.jpg';
              $category = $program['category'] ?? 'general';
              $features = !empty($program['features']) ? json_decode($program['features'], true) : [];
              $curriculum = !empty($program['curriculum']) ? json_decode($program['curriculum'], true) : [];
              $level = $program['level'] ?? 'beginner';
              $levelClass = $levelColors[$level] ?? 'beginner';
              $priceFormatted = !empty($program['price']) ? number_format($program['price'], 0, '.', ',') . ' RWF' : 'Contact for price';
            ?>
            <div class="course-card" data-category="<?php echo htmlspecialchars($category); ?>" data-aos="fade-up" data-aos-delay="<?php echo ($index % 6) * 100; ?>">
              <div class="course-image">
                <img
                  src="<?php echo htmlspecialchars($imageUrl); ?>"
                  alt="<?php echo htmlspecialchars($program['title']); ?>"
                />
                <div class="course-level <?php echo $levelClass; ?>"><?php echo htmlspecialchars(ucfirst($level)); ?></div>
              </div>
              <div class="course-content">
                <h3><?php echo htmlspecialchars($program['title']); ?></h3>
                <div class="course-meta">
                  <span><i class="far fa-clock"></i> <?php echo htmlspecialchars($program['duration'] ?? 'TBD'); ?></span>
                  <span><i class="fas fa-users"></i> Max <?php echo htmlspecialchars($program['max_students'] ?? '15'); ?> Students</span>
                  <span><i class="fas fa-globe"></i> <?php echo htmlspecialchars($program['language'] ?? 'English'); ?></span>
                </div>
                <p>
                  <?php echo htmlspecialchars($program['short_description'] ?? substr($program['description'], 0, 120) . '...'); ?>
                </p>
                <?php if (!empty($features) && is_array($features)): ?>
                <ul class="course-features">
                  <?php foreach (array_slice($features, 0, 3) as $feature): ?>
                  <li>
                    <i class="fas fa-check-circle"></i> <?php echo htmlspecialchars(is_array($feature) ? ($feature['title'] ?? $feature['name'] ?? '') : $feature); ?>
                  </li>
                  <?php endforeach; ?>
                </ul>
                <?php endif; ?>
                <div class="course-footer">
                  <span class="course-price"><?php echo htmlspecialchars($priceFormatted); ?></span>
                  <a href="./enrollment.php?program=<?php echo $program['id']; ?>" class="btn-enroll">Enroll Now</a>
                </div>
              </div>
            </div>
            <?php endforeach; ?>
          </div>
        </div>
      </section>

      <!-- Featured Course -->
      <?php 
      $featuredProgram = null;
      foreach ($trainingPrograms as $program) {
          if (($program['featured'] ?? 0) == 1) {
              $featuredProgram = $program;
              break;
          }
      }
      if (!$featuredProgram && !empty($trainingPrograms)) {
          $featuredProgram = $trainingPrograms[0]; // Use first program if no featured
      }
      ?>
      <?php if ($featuredProgram): ?>
      <section class="featured-course">
        <div class="container">
          <div class="featured-content" data-aos="fade-right">
            <div class="featured-label">Featured Course</div>
            <h2><?php echo htmlspecialchars($featuredProgram['title']); ?></h2>
            <p>
              <?php echo htmlspecialchars($featuredProgram['description']); ?>
            </p>
            <?php 
            $featuredFeatures = !empty($featuredProgram['features']) ? json_decode($featuredProgram['features'], true) : [];
            if (!empty($featuredFeatures) && is_array($featuredFeatures)): ?>
            <ul class="featured-highlights">
              <?php foreach (array_slice($featuredFeatures, 0, 5) as $feature): ?>
              <li>
                <i class="fas fa-check"></i> <?php echo htmlspecialchars(is_array($feature) ? ($feature['title'] ?? $feature['name'] ?? '') : $feature); ?>
              </li>
              <?php endforeach; ?>
            </ul>
            <?php endif; ?>
            <div class="featured-footer">
              <div class="featured-price">
                <span class="price-label">Price</span>
                <span class="price-amount"><?php echo !empty($featuredProgram['price']) ? number_format($featuredProgram['price'], 0, '.', ',') . ' RWF' : 'Contact for price'; ?></span>
              </div>
              <a href="./enrollment.php?program=<?php echo $featuredProgram['id']; ?>" class="btn-primary">Enroll Now</a>
            </div>
          </div>
          <div class="featured-image" data-aos="fade-left">
            <img
              src="<?php echo htmlspecialchars(!empty($featuredProgram['image']) ? getFileUrl($featuredProgram['image']) : '../images/placeholder.jpg'); ?>"
              alt="<?php echo htmlspecialchars($featuredProgram['title']); ?>"
            />
          </div>
        </div>
      </section>
      <?php endif; ?>

      <!-- Testimonials Section -->
      <section class="testimonials">
        <div class="container">
          <h2 class="section-title" data-aos="fade-up">
            What Our Students Say
          </h2>

          <div
            class="testimonial-slider"
            data-aos="fade-up"
            data-aos-delay="100"
          >
            <div class="testimonial-track" id="testimonialTrack">
              <div class="testimonial-item">
                <div class="testimonial-content">
                  <p>
                    "The GIS training from Fair Surveying transformed how our
                    team handles spatial data. The instructors were
                    knowledgeable and patient, ensuring everyone understood the
                    concepts regardless of their starting point."
                  </p>
                  <div class="testimonial-author">
                    <div class="author-image">
                      <img
                        src="../images/testimonial-1.jpg"
                        alt="Marie Uwase"
                      />
                    </div>
                    <div class="author-info">
                      <h4>Marie Uwase</h4>
                      <p>Project Manager, Environmental NGO</p>
                    </div>
                  </div>
                </div>
              </div>
              <div class="testimonial-item">
                <div class="testimonial-content">
                  <p>
                    "I took the Python for Geospatial Analysis course as a
                    complete beginner to programming, and now I'm automating
                    most of my GIS workflows. The step-by-step approach and
                    real-world examples made learning easy."
                  </p>
                  <div class="testimonial-author">
                    <div class="author-image">
                      <img
                        src="../images/testimonial-2.jpg"
                        alt="Jean Mutabazi"
                      />
                    </div>
                    <div class="author-info">
                      <h4>Jean Mutabazi</h4>
                      <p>GIS Specialist, Ministry of Infrastructure</p>
                    </div>
                  </div>
                </div>
              </div>
              <div class="testimonial-item">
                <div class="testimonial-content">
                  <p>
                    "The Drone Mapping and Photogrammetry course gave me the
                    skills to significantly expand my surveying services. The
                    hands-on approach allowed me to immediately apply what I
                    learned to real projects."
                  </p>
                  <div class="testimonial-author">
                    <div class="author-image">
                      <img
                        src="../images/testimonial-3.jpg"
                        alt="Patrick Ndayambaje"
                      />
                    </div>
                    <div class="author-info">
                      <h4>Patrick Ndayambaje</h4>
                      <p>Independent Surveyor, Kigali</p>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <div class="testimonial-navigation">
              <button class="nav-arrow prev" id="prevTestimonial">
                <i class="fas fa-chevron-left"></i>
              </button>
              <div class="nav-indicators">
                <span class="indicator active" data-index="0"></span>
                <span class="indicator" data-index="1"></span>
                <span class="indicator" data-index="2"></span>
              </div>
              <button class="nav-arrow next" id="nextTestimonial">
                <i class="fas fa-chevron-right"></i>
              </button>
            </div>
          </div>
        </div>
      </section>

      <!-- Enrollment Process Section -->
      <section class="enrollment-section">
        <div class="container">
          <h2 class="section-title" data-aos="fade-up">How to Enroll</h2>
          <p class="section-subtitle" data-aos="fade-up" data-aos-delay="100">
            Simple steps to join our training programs
          </p>

          <div class="enrollment-steps" data-aos="fade-up" data-aos-delay="200">
            <div class="step">
              <div class="step-number">1</div>
              <div class="step-icon">
                <i class="fas fa-search"></i>
              </div>
              <h3>Choose a Course</h3>
              <p>
                Browse our training catalog and select the program that best
                fits your needs and skill level.
              </p>
            </div>

            <div class="step">
              <div class="step-number">2</div>
              <div class="step-icon">
                <i class="fas fa-edit"></i>
              </div>
              <h3>Register Online</h3>
              <p>
                Fill out the registration form with your details and preferred
                training date.
              </p>
            </div>

            <div class="step">
              <div class="step-number">3</div>
              <div class="step-icon">
                <i class="fas fa-credit-card"></i>
              </div>
              <h3>Secure Your Spot</h3>
              <p>
                Make the payment to confirm your registration and secure your
                place in the course.
              </p>
            </div>

            <div class="step">
              <div class="step-number">4</div>
              <div class="step-icon">
                <i class="fas fa-graduation-cap"></i>
              </div>
              <h3>Attend & Learn</h3>
              <p>
                Join us on the scheduled date and enhance your professional
                skills with our expert instructors.
              </p>
            </div>
          </div>
        </div>
      </section>

      <!-- FAQ Section -->
      <section class="faq-section">
        <div class="container">
          <h2 class="section-title" data-aos="fade-up">
            Frequently Asked Questions
          </h2>

          <div class="faq-container" data-aos="fade-up" data-aos-delay="100">
            <div class="faq-item">
              <div class="faq-question">
                <h3>What prerequisites are needed for your courses?</h3>
                <button class="faq-toggle"><i class="fas fa-plus"></i></button>
              </div>
              <div class="faq-answer">
                <p>
                  Our beginner courses require no prior experience in the field.
                  Intermediate courses generally require basic knowledge of
                  surveying concepts or related software. Advanced courses
                  typically require professional experience or completion of our
                  prerequisite courses. Specific requirements are listed on each
                  course page.
                </p>
              </div>
            </div>

            <div class="faq-item">
              <div class="faq-question">
                <h3>Are computers provided for software training courses?</h3>
                <button class="faq-toggle"><i class="fas fa-plus"></i></button>
              </div>
              <div class="faq-answer">
                <p>
                  Yes, we provide computers loaded with all necessary software
                  for our training courses. However, participants are welcome to
                  bring their own laptops if they prefer. We will assist with
                  temporary software installation where possible, or help you
                  utilize trial versions for the duration of the course.
                </p>
              </div>
            </div>

            <div class="faq-item">
              <div class="faq-question">
                <h3>What is your cancellation and rescheduling policy?</h3>
                <button class="faq-toggle"><i class="fas fa-plus"></i></button>
              </div>
              <div class="faq-answer">
                <p>
                  Cancellations made more than 14 days before the course start
                  date receive a full refund. Cancellations between 7-14 days
                  receive a 75% refund. Cancellations less than 7 days before
                  the start date are not eligible for refunds. Rescheduling to
                  another training date is possible at no additional cost if
                  requested at least 7 days before the original course date.
                </p>
              </div>
            </div>

            <div class="faq-item">
              <div class="faq-question">
                <h3>Do you provide certification after course completion?</h3>
                <button class="faq-toggle"><i class="fas fa-plus"></i></button>
              </div>
              <div class="faq-answer">
                <p>
                  Yes, all participants who successfully complete our courses
                  receive a professional certificate from Fair Surveying &
                  Mapping Ltd. Our flagship courses also include assessment
                  components, and those who pass receive certification
                  indicating proficiency in the subject matter.
                </p>
              </div>
            </div>

            <div class="faq-item">
              <div class="faq-question">
                <h3>Do you offer discounts for group enrollments?</h3>
                <button class="faq-toggle"><i class="fas fa-plus"></i></button>
              </div>
              <div class="faq-answer">
                <p>
                  Yes, we offer tiered discounts for group enrollments: 10%
                  discount for 3-5 participants, 15% for 6-10 participants, and
                  20% for groups larger than 10. We also offer special rates for
                  academic institutions and government agencies. Please contact
                  us for details about group registrations.
                </p>
              </div>
            </div>
          </div>
        </div>
      </section>

      <!-- Call to Action Section -->
      <section class="cta-section">
        <div class="container">
          <div class="cta-content" data-aos="fade-up">
            <h2>Ready to Advance Your Professional Skills?</h2>
            <p>
              Join our expert-led training programs and enhance your
              capabilities in surveying, mapping, and geospatial technologies.
            </p>
            <div class="cta-buttons">
              <a href="#courses" class="btn-primary">View Courses</a>
              <a href="tel:0788331697" class="btn-outline">
                <i class="fas fa-phone"></i> Call Us: 0788331697
              </a>
            </div>
          </div>
        </div>
      </section>
    </main>

    <!-- Footer -->
    <?php include 'includes/footer.php'; ?>

    <!-- JavaScript Libraries -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.js"></script>
    <script src="../js/training.js"></script>
  </body>
</php>
