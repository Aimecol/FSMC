<?php
// Include database configuration
require_once './config/database.php';

// Fetch about content
$aboutContent = [];
$stmt = dbGetRows("SELECT * FROM about_content WHERE is_active = 1 ORDER BY sort_order ASC");
foreach ($stmt as $row) {
    $aboutContent[$row['section_key']] = $row;
}

// Fetch team members
$teamMembers = dbGetRows("SELECT * FROM team_members WHERE is_active = 1 ORDER BY sort_order ASC");

// Decode social links for team members
foreach ($teamMembers as &$member) {
    $member['social_links'] = json_decode($member['social_links'], true) ?: [];
    $member['specializations'] = json_decode($member['specializations'], true) ?: [];
}

// Fetch company statistics
$companyStats = dbGetRows("SELECT * FROM company_stats WHERE is_active = 1 ORDER BY sort_order ASC");

// Fetch timeline
$timeline = dbGetRows("SELECT * FROM company_timeline WHERE is_active = 1 ORDER BY sort_order ASC");

// Fetch core values
$coreValues = dbGetRows("SELECT * FROM core_values WHERE is_active = 1 ORDER BY sort_order ASC");

// Fetch expertise areas
$expertiseAreas = dbGetRows("SELECT * FROM expertise_areas WHERE is_active = 1 ORDER BY sort_order ASC");

// Fetch About FAQs
$aboutFAQs = dbGetRows("SELECT * FROM about_faqs WHERE is_active = 1 ORDER BY sort_order ASC");

// Get company settings
$companySettings = getCompanySettings();

// Helper function to get content
function getAboutContent($key, $field = 'content', $default = '') {
    global $aboutContent;
    return isset($aboutContent[$key][$field]) ? $aboutContent[$key][$field] : $default;
}

// Helper function to get mission from meta_data
function getMission() {
    global $aboutContent;
    if (isset($aboutContent['story']['meta_data'])) {
        $metaData = json_decode($aboutContent['story']['meta_data'], true);
        return $metaData['mission'] ?? '';
    }
    return '';
}
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title><?php echo htmlspecialchars(getAboutContent('hero', 'title', 'About Us')); ?> - <?php echo htmlspecialchars(getSetting('company_name', 'Fair Surveying & Mapping Ltd')); ?></title>
    <meta name="description" content="<?php echo htmlspecialchars(getAboutContent('hero', 'subtitle', 'Excellence in Land Surveying, Mapping and Environmental Solutions')); ?>">
    <link rel="icon" type="image/svg+xml" href="../images/logo.png" />
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css"
    />
    <link rel="stylesheet" href="../css/style.css" />
    <link rel="stylesheet" href="../css/about.css" />
  </head>
  <body>
    <?php include 'includes/header.php'; ?>

    <main>
      <div class="container">
        <div class="page-title">
          <h1><?php echo htmlspecialchars(getAboutContent('hero', 'title', 'About Fair Surveying & Mapping')); ?></h1>
          <p class="subtitle">
            <?php echo htmlspecialchars(getAboutContent('hero', 'subtitle', 'Excellence in Land Surveying, Mapping and Environmental Solutions')); ?>
          </p>
        </div>

        <section class="about-section">
          <h2 class="section-title"><?php echo htmlspecialchars(getAboutContent('story', 'title', 'Our Story')); ?></h2>
          <div class="about-intro">
            <div class="about-text">
              <?php 
              $storyContent = getAboutContent('story', 'content', '');
              if ($storyContent) {
                  $paragraphs = explode("\n\n", $storyContent);
                  foreach ($paragraphs as $paragraph) {
                      if (trim($paragraph)) {
                          echo '<p>' . nl2br(htmlspecialchars(trim($paragraph))) . '</p>';
                      }
                  }
              }
              ?>

              <?php $mission = getMission(); if ($mission): ?>
              <div class="highlight-box">
                <p>
                  "<?php echo htmlspecialchars($mission); ?>"
                </p>
              </div>
              <?php endif; ?>
            </div>
            <div class="about-image">
              <svg viewBox="0 0 400 300" xmlns="http://www.w3.org/2000/svg">
                <rect width="400" height="300" fill="#e8f4f8" />
                <circle cx="200" cy="150" r="80" fill="#2e86c1" opacity="0.1" />
                <path
                  d="M100,190 L300,190 L200,50 Z"
                  fill="#1a5276"
                  opacity="0.7"
                />
                <rect x="180" y="190" width="40" height="60" fill="#7f8c8d" />
                <circle cx="200" cy="170" r="15" fill="#f39c12" />
                <line
                  x1="50"
                  y1="190"
                  x2="350"
                  y2="190"
                  stroke="#34495e"
                  stroke-width="5"
                />
                <text
                  x="100"
                  y="20"
                  font-family="Arial"
                  font-size="14"
                  fill="#2c3e50"
                >
                  Fair Surveying and Mapping Ltd
                </text>
              </svg>
            </div>
          </div>
        </section>

        <section class="about-section">
          <h2 class="section-title"><?php echo htmlspecialchars(getAboutContent('expertise_intro', 'title', 'Our Areas of Expertise')); ?></h2>
          <p>
            <?php echo htmlspecialchars(getAboutContent('expertise_intro', 'content', 'At Fair Surveying and Mapping Ltd, we offer a comprehensive range of professional services tailored to meet diverse needs in land management, construction support, and environmental consultancy.')); ?>
          </p>

          <div class="expertise-grid">
            <?php foreach ($expertiseAreas as $expertise): ?>
            <div class="expertise-card">
              <div class="expertise-icon">
                <i class="<?php echo htmlspecialchars($expertise['icon'] ?: 'fas fa-cog'); ?>"></i>
              </div>
              <h3 class="expertise-title"><?php echo htmlspecialchars($expertise['title']); ?></h3>
              <p>
                <?php echo htmlspecialchars($expertise['description']); ?>
              </p>
            </div>
            <?php endforeach; ?>
          </div>
        </section>

        <section class="about-section">
          <h2 class="section-title"><?php echo htmlspecialchars(getAboutContent('values_intro', 'title', 'Our Core Values')); ?></h2>
          <p>
            <?php echo htmlspecialchars(getAboutContent('values_intro', 'content', 'The principles that guide our work and define our approach to every project:')); ?>
          </p>

          <div class="values-grid">
            <?php foreach ($coreValues as $value): ?>
            <div class="value-card">
              <div class="value-icon">
                <i class="<?php echo htmlspecialchars($value['icon'] ?: 'fas fa-star'); ?>"></i>
              </div>
              <h3 class="value-title"><?php echo htmlspecialchars($value['title']); ?></h3>
              <p>
                <?php echo htmlspecialchars($value['description']); ?>
              </p>
            </div>
            <?php endforeach; ?>
          </div>
        </section>

        <section class="stats-section">
          <?php foreach ($companyStats as $stat): ?>
          <div class="stat-item">
            <div class="stat-number" id="stat<?php echo ucfirst($stat['stat_key']); ?>">
              <span data-count="<?php echo $stat['value']; ?>">0</span><i class="<?php echo htmlspecialchars($stat['suffix']); ?>"></i>
            </div>
            <div class="stat-text"><?php echo htmlspecialchars($stat['label']); ?></div>
          </div>
          <?php endforeach; ?>
        </section>

        <section class="about-section">
          <h2 class="section-title"><?php echo htmlspecialchars(getAboutContent('team_intro', 'title', 'Meet Our Leadership')); ?></h2>
          <div class="team-intro">
            <p>
              <?php echo htmlspecialchars(getAboutContent('team_intro', 'content', 'Our team is led by experienced professionals with extensive knowledge in surveying, mapping, and environmental sciences. Together, we work collaboratively to deliver exceptional results for our clients.')); ?>
            </p>
          </div>

          <div class="expertise-grid">
            <?php foreach ($teamMembers as $member): ?>
            <div class="team-member">
              <div class="team-photo">
                <?php if (!empty($member['image'])): ?>
                  <img src="<?php echo getFileUrl($member['image']); ?>" alt="<?php echo htmlspecialchars($member['name']); ?>">
                <?php else: ?>
                  <i class="fas fa-user-circle"></i>
                <?php endif; ?>
              </div>
              <div class="team-info">
                <h3 class="team-name"><?php echo htmlspecialchars($member['name']); ?></h3>
                <p class="team-role"><?php echo htmlspecialchars($member['role']); ?></p>
                <p class="team-bio">
                  <?php echo htmlspecialchars($member['bio']); ?>
                </p>
                <?php if (!empty($member['social_links'])): ?>
                <div class="social-links">
                  <?php if (!empty($member['social_links']['linkedin'])): ?>
                    <a href="<?php echo htmlspecialchars($member['social_links']['linkedin']); ?>" target="_blank"><i class="fab fa-linkedin"></i></a>
                  <?php endif; ?>
                  <?php if (!empty($member['social_links']['twitter'])): ?>
                    <a href="<?php echo htmlspecialchars($member['social_links']['twitter']); ?>" target="_blank"><i class="fab fa-twitter"></i></a>
                  <?php endif; ?>
                  <?php if (!empty($member['social_links']['facebook'])): ?>
                    <a href="<?php echo htmlspecialchars($member['social_links']['facebook']); ?>" target="_blank"><i class="fab fa-facebook-f"></i></a>
                  <?php endif; ?>
                </div>
                <?php endif; ?>
              </div>
            </div>
            <?php endforeach; ?>
          </div>
        </section>

        <section class="about-section">
          <h2 class="section-title"><?php echo htmlspecialchars(getAboutContent('timeline_intro', 'title', 'Our Journey')); ?></h2>
          <div class="timeline-section">
            <?php foreach ($timeline as $item): ?>
            <div class="timeline-item">
              <div class="timeline-date"><?php echo htmlspecialchars($item['year']); ?></div>
              <h3 class="timeline-title"><?php echo htmlspecialchars($item['title']); ?></h3>
              <p>
                <?php echo htmlspecialchars($item['description']); ?>
              </p>
            </div>
            <?php endforeach; ?>
          </div>
        </section>

        <section class="about-section">
          <h2 class="section-title"><?php echo htmlspecialchars(getAboutContent('faq_intro', 'title', 'Frequently Asked Questions')); ?></h2>
          <div class="accordion">
            <?php foreach ($aboutFAQs as $faq): ?>
            <div class="accordion-item">
              <div class="accordion-header">
                <?php echo htmlspecialchars($faq['question']); ?>
                <i class="fas fa-chevron-down accordion-icon"></i>
              </div>
              <div class="accordion-content">
                <div class="accordion-content-inner">
                  <p>
                    <?php echo nl2br(htmlspecialchars($faq['answer'])); ?>
                  </p>
                </div>
              </div>
            </div>
            <?php endforeach; ?>
          </div>
        </section>

        <section class="cta-section">
          <h2 class="cta-title"><?php echo htmlspecialchars(getAboutContent('cta', 'title', 'Ready to Work With Us?')); ?></h2>
          <p class="cta-text">
            <?php echo htmlspecialchars(getAboutContent('cta', 'content', 'Whether you need land surveying, mapping services, construction support, or environmental consultancy, our team is ready to deliver expert solutions tailored to your specific needs.')); ?>
          </p>
          <button class="btn-primary contact">
            <i class="fas fa-phone"></i> Contact Us Today
          </button>
        </section>
      </div>
    </main>

    <?php include 'includes/footer.php'; ?>

    <script src="../js/script.js"></script>
    <script>
      document.addEventListener("DOMContentLoaded", function () {
        // Accordion functionality
        const accordionHeaders = document.querySelectorAll(".accordion-header");

        accordionHeaders.forEach((header) => {
          header.addEventListener("click", function () {
            const accordionItem = this.parentElement;
            accordionItem.classList.toggle("active");
          });
        });

        // Animate stats when in viewport
        const stats = document.querySelectorAll(".stat-number span");
        let animated = false;

        function animateStats() {
          if (animated) return;

          const statsSection = document.querySelector(".stats-section");
          const position = statsSection.getBoundingClientRect();

          // Check if stats section is in viewport
          if (position.top < window.innerHeight && position.bottom >= 0) {
            stats.forEach((stat) => {
              const target = parseInt(stat.getAttribute("data-count"));
              let count = 0;
              const duration = 2000; // 2 seconds
              const interval = duration / target;

              const counter = setInterval(() => {
                count += 1;
                stat.innerText = count;

                if (count >= target) {
                  clearInterval(counter);
                }
              }, interval);
            });

            animated = true;
          }
        }

        document.querySelector(".contact").addEventListener("click", () => {
          window.location.href = "./contact.php";
        });

        // Run on scroll and initial load
        window.addEventListener("scroll", animateStats);
        animateStats(); // Check on initial load
      });
    </script>
  </body>
</html>
