<?php
// Include database configuration
require_once 'config/database.php';

// Get company settings
$settings = getCompanySettings();
$companyName = getSetting('company_name', 'Fair Surveying & Mapping Ltd');

// Get all research projects
$researchProjects = dbGetRows("SELECT * FROM research_projects WHERE status IN ('ongoing', 'completed', 'published') ORDER BY featured DESC, created_at DESC");

// Group projects by category for filtering
$projectsByCategory = [];
foreach ($researchProjects as $project) {
    if (!empty($project['category'])) {
        $projectsByCategory[$project['category']][] = $project;
    }
}

// Get project statistics
$totalProjects = count($researchProjects);
$activeProjects = count(array_filter($researchProjects, function($p) { return $p['status'] === 'ongoing'; }));
$publishedProjects = count(array_filter($researchProjects, function($p) { return $p['status'] === 'published'; }));

// Categories mapping
$categoryMap = [
    'surveying' => ['label' => 'Land Surveying', 'icon' => 'fas fa-compass'],
    'mapping' => ['label' => 'Mapping & Cartography', 'icon' => 'fas fa-map'],
    'gis' => ['label' => 'GIS', 'icon' => 'fas fa-map-marked-alt'],
    'remote_sensing' => ['label' => 'Remote Sensing', 'icon' => 'fas fa-satellite'],
    'cartography' => ['label' => 'Cartography', 'icon' => 'fas fa-globe'],
    'photogrammetry' => ['label' => 'Photogrammetry', 'icon' => 'fas fa-camera'],
    'environmental' => ['label' => 'Environmental', 'icon' => 'fas fa-water'],
    'urban' => ['label' => 'Urban Planning', 'icon' => 'fas fa-city'],
    'geospatial' => ['label' => 'Geospatial', 'icon' => 'fas fa-map-marked-alt']
];
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title><?php echo htmlspecialchars(getSetting('meta_title', 'Research Projects - ' . $companyName)); ?></title>
    <meta name="description" content="<?php echo htmlspecialchars(getSetting('meta_description', 'Exploring innovative solutions in surveying and mapping technologies through our dedicated research initiatives.')); ?>" />
    <link rel="icon" type="image/svg+xml" href="../images/logo.png" />
    <!-- Font Awesome -->
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
    />
    <!-- AOS Animation Library -->
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.css"
    />
    <!-- Main CSS -->
    <link rel="stylesheet" href="../css/style.css" />
    <!-- Page CSS -->
    <link rel="stylesheet" href="../css/researches.css" />
  </head>
  <body>
    <?php include 'includes/header.php'; ?>

    <div class="page-hero">
      <div class="container">
        <h1 class="page-title" data-aos="fade-up">Research Projects</h1>
        <p class="page-subtitle" data-aos="fade-up" data-aos-delay="100">
          Exploring innovative solutions in surveying and mapping technologies
        </p>
      </div>
      <div class="hero-shape"></div>
    </div>

    <section class="research-intro">
      <div class="container">
        <div class="intro-content" data-aos="fade-up">
          <h2>Advancing Innovation Through Research</h2>
          <p>
            At Fair Surveying & Mapping Ltd, we're committed to pushing the
            boundaries of geospatial science through our dedicated research
            initiatives. Our interdisciplinary team works on cutting-edge
            projects that address real-world challenges in surveying, mapping,
            and environmental management.
          </p>
          <div class="intro-stats">
            <div class="stat-item" data-aos="fade-up" data-aos-delay="100">
              <div class="stat-value"><span class="counter"><?php echo $activeProjects; ?></span>+</div>
              <div class="stat-label">Active Projects</div>
            </div>
            <div class="stat-item" data-aos="fade-up" data-aos-delay="200">
              <div class="stat-value"><span class="counter"><?php echo $totalProjects; ?></span></div>
              <div class="stat-label">Total Projects</div>
            </div>
            <div class="stat-item" data-aos="fade-up" data-aos-delay="300">
              <div class="stat-value"><span class="counter"><?php echo $publishedProjects; ?></span></div>
              <div class="stat-label">Published</div>
            </div>
          </div>
        </div>
        <div class="intro-image" data-aos="fade-left">
          <img
            src="../images/9c_NLZ75_JAwFH7mjiGB_.jpg"
            alt="Research Team"
            class="img-fluid"
          />
        </div>
      </div>
    </section>

    <section class="research-projects">
      <div class="container">
        <div class="section-header" data-aos="fade-up">
          <h2>Featured Research Projects</h2>
          <p>
            Discover our innovative research initiatives and their real-world
            applications
          </p>
          <div class="filter-controls">
            <button class="filter-btn active" data-filter="all">
              All Projects
            </button>
            <?php 
            $availableCategories = array_unique(array_column($researchProjects, 'category'));
            foreach ($availableCategories as $category): 
                if (!empty($category) && isset($categoryMap[$category])): ?>
            <button class="filter-btn" data-filter="<?php echo htmlspecialchars($category); ?>">
              <?php echo htmlspecialchars($categoryMap[$category]['label']); ?>
            </button>
            <?php endif; endforeach; ?>
          </div>
        </div>

        <div class="projects-grid">
          <?php foreach ($researchProjects as $index => $project): 
            $imageUrl = !empty($project['image']) ? getFileUrl($project['image']) : '../images/placeholder.jpg';
            $category = $project['category'] ?? 'general';
            $categoryInfo = $categoryMap[$category] ?? ['label' => ucfirst($category), 'icon' => 'fas fa-flask'];
            $authors = !empty($project['authors']) ? json_decode($project['authors'], true) : [];
            $keywords = !empty($project['keywords']) ? json_decode($project['keywords'], true) : [];
            $publicationYear = !empty($project['publication_date']) ? date('Y', strtotime($project['publication_date'])) : date('Y', strtotime($project['created_at']));
          ?>
          <div
            class="project-card"
            data-category="<?php echo htmlspecialchars($category); ?>"
            data-aos="fade-up"
            data-aos-delay="<?php echo ($index % 6) * 100; ?>"
          >
            <div class="project-image">
              <img
                src="<?php echo htmlspecialchars($imageUrl); ?>"
                alt="<?php echo htmlspecialchars($project['title']); ?>"
              />
              <div class="project-overlay">
                <span class="project-category">
                  <i class="<?php echo $categoryInfo['icon']; ?>"></i> 
                  <?php echo htmlspecialchars($categoryInfo['label']); ?>
                </span>
                <a href="#project-<?php echo $project['id']; ?>" class="btn-view-project">View Project</a>
              </div>
            </div>
            <div class="project-content">
              <h3><?php echo htmlspecialchars($project['title']); ?></h3>
              <p>
                <?php echo htmlspecialchars(substr($project['abstract'], 0, 150) . '...'); ?>
              </p>
              <div class="project-meta">
                <?php if (!empty($project['publication_date'])): ?>
                <div class="meta-item">
                  <i class="fas fa-calendar-alt"></i> <?php echo htmlspecialchars(date('M Y', strtotime($project['publication_date']))); ?>
                </div>
                <?php endif; ?>
                <?php if (!empty($authors) && is_array($authors)): ?>
                <div class="meta-item">
                  <i class="fas fa-user"></i> <?php echo htmlspecialchars(implode(', ', array_slice($authors, 0, 2))); ?><?php echo count($authors) > 2 ? ' et al.' : ''; ?>
                </div>
                <?php endif; ?>
                <div class="meta-item">
                  <i class="fas fa-tag"></i> <?php echo htmlspecialchars(ucfirst($project['status'])); ?>
                </div>
              </div>
            </div>
          </div>
          <?php endforeach; ?>
        </div>

        <div class="projects-pagination" data-aos="fade-up">
          <button class="load-more-btn">
            <span>Load More Projects</span>
            <i class="fas fa-arrow-down"></i>
          </button>
        </div>
      </div>
    </section>

    <section class="research-collaboration">
      <div class="container">
        <div class="collaboration-content" data-aos="fade-up">
          <h2>Research Collaboration</h2>
          <p>
            We welcome partnerships with academic institutions, industry
            leaders, and government agencies to advance our research goals and
            create meaningful impact. Our collaborative approach allows us to
            combine expertise and resources to tackle complex challenges in
            surveying, mapping, and environmental management.
          </p>
          <div class="collaboration-stats">
            <div class="collab-item" data-aos="fade-up" data-aos-delay="100">
              <i class="fas fa-university"></i>
              <h4>Academic Partners</h4>
              <p>Collaborations with universities and research institutes</p>
            </div>
            <div class="collab-item" data-aos="fade-up" data-aos-delay="200">
              <i class="fas fa-industry"></i>
              <h4>Industry Connections</h4>
              <p>
                Partnerships with leading companies in geospatial technology
              </p>
            </div>
            <div class="collab-item" data-aos="fade-up" data-aos-delay="300">
              <i class="fas fa-globe-africa"></i>
              <h4>International Network</h4>
              <p>Global connections with researchers and practitioners</p>
            </div>
          </div>
          <div class="collaboration-action">
            <a href="./contact.php" class="btn-primary"
              >Explore Collaboration Opportunities</a
            >
          </div>
        </div>
      </div>
    </section>

    <?php include 'includes/footer.php'; ?>

    <!-- Dynamic Project Modal Templates -->
    <?php foreach ($researchProjects as $project): 
      $imageUrl = !empty($project['image']) ? getFileUrl($project['image']) : '../images/placeholder.jpg';
      $category = $project['category'] ?? 'general';
      $categoryInfo = $categoryMap[$category] ?? ['label' => ucfirst($category), 'icon' => 'fas fa-flask'];
      $authors = !empty($project['authors']) ? json_decode($project['authors'], true) : [];
      $keywords = !empty($project['keywords']) ? json_decode($project['keywords'], true) : [];
      $keyFindings = !empty($project['key_findings']) ? json_decode($project['key_findings'], true) : [];
      $gallery = !empty($project['gallery']) ? json_decode($project['gallery'], true) : [];
      $documents = !empty($project['documents']) ? json_decode($project['documents'], true) : [];
    ?>
    <div class="modal" id="project-<?php echo $project['id']; ?>">
      <div class="modal-content">
        <span class="close-modal">&times;</span>
        <div class="modal-body">
          <div class="modal-header">
            <span class="project-category">
              <i class="<?php echo $categoryInfo['icon']; ?>"></i> 
              <?php echo htmlspecialchars($categoryInfo['label']); ?>
            </span>
            <h2><?php echo htmlspecialchars($project['title']); ?></h2>
            <div class="project-meta">
              <?php if (!empty($project['publication_date'])): ?>
              <span><i class="fas fa-calendar-alt"></i> <?php echo htmlspecialchars(date('M Y', strtotime($project['publication_date']))); ?></span>
              <?php endif; ?>
              <?php if (!empty($authors) && is_array($authors)): ?>
              <span><i class="fas fa-user"></i> <?php echo htmlspecialchars(implode(', ', $authors)); ?></span>
              <?php endif; ?>
              <span><i class="fas fa-tag"></i> <?php echo htmlspecialchars(ucfirst($project['status'])); ?></span>
              <?php if (!empty($project['journal'])): ?>
              <span><i class="fas fa-book"></i> <?php echo htmlspecialchars($project['journal']); ?></span>
              <?php endif; ?>
            </div>
          </div>
          
          <?php if (!empty($gallery) && is_array($gallery) && count($gallery) > 0): ?>
          <div class="modal-gallery">
            <div class="gallery-main">
              <img src="<?php echo htmlspecialchars(getFileUrl($gallery[0])); ?>" alt="Project Main Image" />
            </div>
            <div class="gallery-thumbs">
              <?php foreach ($gallery as $index => $galleryImage): ?>
              <img src="<?php echo htmlspecialchars(getFileUrl($galleryImage)); ?>" 
                   alt="Thumbnail <?php echo $index + 1; ?>" 
                   <?php echo $index === 0 ? 'class="active"' : ''; ?> />
              <?php endforeach; ?>
            </div>
          </div>
          <?php else: ?>
          <div class="modal-gallery">
            <div class="gallery-main">
              <img src="<?php echo htmlspecialchars($imageUrl); ?>" alt="Project Main Image" />
            </div>
          </div>
          <?php endif; ?>
          
          <div class="project-content">
            <div class="content-section">
              <h3><i class="fas fa-info-circle"></i> Project Abstract</h3>
              <p><?php echo nl2br(htmlspecialchars($project['abstract'] ?? '')); ?></p>
            </div>
            
            <?php if (!empty($project['description'])): ?>
            <div class="content-section">
              <h3><i class="fas fa-align-left"></i> Description</h3>
              <p><?php echo nl2br(htmlspecialchars($project['description'])); ?></p>
            </div>
            <?php endif; ?>
            
            <?php if (!empty($project['methodology'])): ?>
            <div class="content-section">
              <h3><i class="fas fa-tools"></i> Methodology</h3>
              <p><?php echo nl2br(htmlspecialchars($project['methodology'])); ?></p>
            </div>
            <?php endif; ?>
            
            <?php if (!empty($keyFindings) && is_array($keyFindings)): ?>
            <div class="content-section">
              <h3><i class="fas fa-chart-line"></i> Key Findings</h3>
              <div class="findings-list">
                <?php foreach ($keyFindings as $finding): ?>
                <div class="finding-item">
                  <i class="fas fa-check-circle"></i>
                  <p><?php echo htmlspecialchars(is_array($finding) ? ($finding['title'] ?? $finding['description'] ?? '') : $finding); ?></p>
                </div>
                <?php endforeach; ?>
              </div>
            </div>
            <?php endif; ?>
            
            <?php if (!empty($project['doi'])): ?>
            <div class="content-section">
              <h3><i class="fas fa-link"></i> Publication Details</h3>
              <p><strong>DOI:</strong> <a href="https://doi.org/<?php echo htmlspecialchars($project['doi']); ?>" target="_blank"><?php echo htmlspecialchars($project['doi']); ?></a></p>
            </div>
            <?php endif; ?>
          </div>
          
          <div class="modal-footer">
            <?php if (!empty($keywords) && is_array($keywords)): ?>
            <div class="tags">
              <?php foreach (array_slice($keywords, 0, 5) as $keyword): ?>
              <span><i class="fas fa-tag"></i> <?php echo htmlspecialchars($keyword); ?></span>
              <?php endforeach; ?>
            </div>
            <?php endif; ?>
            
            <div class="action-buttons">
              <?php if (!empty($documents) && is_array($documents) && count($documents) > 0): ?>
              <a href="<?php echo htmlspecialchars(getFileUrl($documents[0])); ?>" class="btn-download" target="_blank">
                <i class="fas fa-download"></i> Download Report
              </a>
              <?php endif; ?>
              <a href="./contact.php" class="btn-contact">
                <i class="fas fa-envelope"></i> Contact Research Team
              </a>
            </div>
          </div>
        </div>
      </div>
    </div>
    <?php endforeach; ?>

    <!-- Loading Overlay -->
    <div class="loading-overlay">
      <div class="spinner">
        <i class="fas fa-circle-notch fa-spin"></i>
      </div>
    </div>

    <!-- JavaScript Libraries -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.js"></script>
    <script src="../js/script.js"></script>
    <script src="../js/researches.js"></script>
    
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize AOS
        AOS.init({
            duration: 800,
            easing: 'ease-in-out',
            once: true
        });
        
        // Project filtering
        const filterButtons = document.querySelectorAll('.filter-btn');
        const projectCards = document.querySelectorAll('.project-card');
        
        filterButtons.forEach(button => {
            button.addEventListener('click', () => {
                // Remove active class from all buttons
                filterButtons.forEach(btn => btn.classList.remove('active'));
                // Add active class to clicked button
                button.classList.add('active');
                
                const filter = button.getAttribute('data-filter');
                
                // Show/hide projects based on filter
                projectCards.forEach(card => {
                    if (filter === 'all' || card.getAttribute('data-category') === filter) {
                        card.style.display = 'block';
                    } else {
                        card.style.display = 'none';
                    }
                });
            });
        });
        
        // Modal functionality
        const modals = document.querySelectorAll('.modal');
        const modalTriggers = document.querySelectorAll('.btn-view-project');
        const closeButtons = document.querySelectorAll('.close-modal');
        
        // Open modal
        modalTriggers.forEach(trigger => {
            trigger.addEventListener('click', (e) => {
                e.preventDefault();
                const modalId = trigger.getAttribute('href');
                const modal = document.querySelector(modalId);
                if (modal) {
                    modal.style.display = 'flex';
                    document.body.style.overflow = 'hidden';
                }
            });
        });
        
        // Close modal
        closeButtons.forEach(button => {
            button.addEventListener('click', () => {
                button.closest('.modal').style.display = 'none';
                document.body.style.overflow = 'auto';
            });
        });
        
        // Close modal when clicking outside
        modals.forEach(modal => {
            modal.addEventListener('click', (e) => {
                if (e.target === modal) {
                    modal.style.display = 'none';
                    document.body.style.overflow = 'auto';
                }
            });
        });
        
        // Gallery functionality
        const galleryThumbs = document.querySelectorAll('.gallery-thumbs img');
        galleryThumbs.forEach(thumb => {
            thumb.addEventListener('click', () => {
                const gallery = thumb.closest('.modal-gallery');
                const mainImage = gallery.querySelector('.gallery-main img');
                const allThumbs = gallery.querySelectorAll('.gallery-thumbs img');
                
                // Update main image
                mainImage.src = thumb.src;
                
                // Update active thumbnail
                allThumbs.forEach(t => t.classList.remove('active'));
                thumb.classList.add('active');
            });
        });
        
        // Counter animation
        const counters = document.querySelectorAll('.counter');
        const animateCounter = (counter) => {
            const target = parseInt(counter.textContent);
            const duration = 2000;
            const step = target / (duration / 16);
            let current = 0;
            
            const timer = setInterval(() => {
                current += step;
                if (current >= target) {
                    counter.textContent = target;
                    clearInterval(timer);
                } else {
                    counter.textContent = Math.floor(current);
                }
            }, 16);
        };
        
        // Intersection Observer for counter animation
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const counter = entry.target.querySelector('.counter');
                    if (counter && !counter.classList.contains('animated')) {
                        counter.classList.add('animated');
                        animateCounter(counter);
                    }
                }
            });
        });
        
        document.querySelectorAll('.stat-item').forEach(item => {
            observer.observe(item);
        });
        
        // Load more functionality (if needed)
        const loadMoreBtn = document.querySelector('.load-more-btn');
        if (loadMoreBtn) {
            loadMoreBtn.addEventListener('click', () => {
                // Implement load more functionality if needed
                console.log('Load more projects');
            });
        }
    });
    </script>
  </body>
</html>
