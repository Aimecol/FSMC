<!DOCTYPE php>
<php lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Professional Training Programs - Fair Surveying & Mapping Ltd</title>
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
            <button class="tab-btn active" data-category="surveying">
              Surveying Equipment
            </button>
            <button class="tab-btn" data-category="software">
              Software & Data
            </button>
            <button class="tab-btn" data-category="gis">
              GIS & Remote Sensing
            </button>
            <button class="tab-btn" data-category="advanced">
              Advanced Technologies
            </button>
          </div>

          <!-- Surveying Equipment Courses -->
          <div class="courses-grid active" id="surveying-courses">
            <div class="course-card" data-aos="fade-up">
              <div class="course-image">
                <img
                  src="../images/9ymlPUvjUSWPlrk-qrJ2k.jpg"
                  alt="Total Station Training"
                />
                <div class="course-level intermediate">Intermediate</div>
              </div>
              <div class="course-content">
                <h3>Total Station Masterclass</h3>
                <div class="course-meta">
                  <span><i class="far fa-clock"></i> 3 Days</span>
                  <span><i class="fas fa-users"></i> Max 12 Students</span>
                  <span><i class="fas fa-globe"></i> English</span>
                </div>
                <p>
                  Master the operation and advanced features of modern total
                  stations for precision surveying.
                </p>
                <ul class="course-features">
                  <li>
                    <i class="fas fa-check-circle"></i> Setup and calibration
                  </li>
                  <li>
                    <i class="fas fa-check-circle"></i> Data collection and
                    transfer
                  </li>
                  <li>
                    <i class="fas fa-check-circle"></i> Troubleshooting in the
                    field
                  </li>
                </ul>
                <div class="course-footer">
                  <span class="course-price">75,000 RWF</span>
                  <a href="#" class="btn-enroll">Enroll Now</a>
                </div>
              </div>
            </div>

            <div class="course-card" data-aos="fade-up" data-aos-delay="100">
              <div class="course-image">
                <img
                  src="../images/bhHQ2-XvUG3QKct5kwamE.jpg"
                  alt="GPS Systems Training"
                />
                <div class="course-level beginner">Beginner</div>
              </div>
              <div class="course-content">
                <h3>GPS Systems Fundamentals</h3>
                <div class="course-meta">
                  <span><i class="far fa-clock"></i> 2 Days</span>
                  <span><i class="fas fa-users"></i> Max 10 Students</span>
                  <span><i class="fas fa-globe"></i> English</span>
                </div>
                <p>
                  Learn the fundamentals of GPS/GNSS technology and handheld
                  receivers for field measurements.
                </p>
                <ul class="course-features">
                  <li>
                    <i class="fas fa-check-circle"></i> GPS principles and
                    limitations
                  </li>
                  <li>
                    <i class="fas fa-check-circle"></i> Field collection
                    techniques
                  </li>
                  <li>
                    <i class="fas fa-check-circle"></i> Data processing
                    workflows
                  </li>
                </ul>
                <div class="course-footer">
                  <span class="course-price">50,000 RWF</span>
                  <a href="#" class="btn-enroll">Enroll Now</a>
                </div>
              </div>
            </div>

            <div class="course-card" data-aos="fade-up" data-aos-delay="200">
              <div class="course-image">
                <img
                  src="../images/rC9AvGLJMT6thyTyTxB--.jpg"
                  alt="RTK GPS Advanced Training"
                />
                <div class="course-level advanced">Advanced</div>
              </div>
              <div class="course-content">
                <h3>RTK GPS Advanced Techniques</h3>
                <div class="course-meta">
                  <span><i class="far fa-clock"></i> 4 Days</span>
                  <span><i class="fas fa-users"></i> Max 8 Students</span>
                  <span><i class="fas fa-globe"></i> English</span>
                </div>
                <p>
                  Advanced training on RTK GPS systems for centimeter-level
                  accuracy in real-time surveying.
                </p>
                <ul class="course-features">
                  <li>
                    <i class="fas fa-check-circle"></i> Base & rover setup
                  </li>
                  <li>
                    <i class="fas fa-check-circle"></i> Network RTK solutions
                  </li>
                  <li>
                    <i class="fas fa-check-circle"></i> Quality control
                    procedures
                  </li>
                </ul>
                <div class="course-footer">
                  <span class="course-price">120,000 RWF</span>
                  <a href="#" class="btn-enroll">Enroll Now</a>
                </div>
              </div>
            </div>
          </div>

          <!-- Software & Data Courses -->
          <div class="courses-grid" id="software-courses">
            <div class="course-card" data-aos="fade-up">
              <div class="course-image">
                <img
                  src="../images/autocad-training.jpg"
                  alt="AutoCAD for Surveyors"
                />
                <div class="course-level beginner">Beginner</div>
              </div>
              <div class="course-content">
                <h3>AutoCAD for Surveyors</h3>
                <div class="course-meta">
                  <span><i class="far fa-clock"></i> 5 Days</span>
                  <span><i class="fas fa-users"></i> Max 15 Students</span>
                  <span><i class="fas fa-globe"></i> English</span>
                </div>
                <p>
                  Learn essential AutoCAD skills for creating professional
                  survey drawings and maps.
                </p>
                <ul class="course-features">
                  <li>
                    <i class="fas fa-check-circle"></i> Drawing setup and
                    organization
                  </li>
                  <li>
                    <i class="fas fa-check-circle"></i> Survey data import
                  </li>
                  <li>
                    <i class="fas fa-check-circle"></i> Plan production
                    workflows
                  </li>
                </ul>
                <div class="course-footer">
                  <span class="course-price">90,000 RWF</span>
                  <a href="#" class="btn-enroll">Enroll Now</a>
                </div>
              </div>
            </div>

            <div class="course-card" data-aos="fade-up" data-aos-delay="100">
              <div class="course-image">
                <img
                  src="../images/python-training.jpg"
                  alt="Python for Geospatial Analysis"
                />
                <div class="course-level intermediate">Intermediate</div>
              </div>
              <div class="course-content">
                <h3>Python for Geospatial Analysis</h3>
                <div class="course-meta">
                  <span><i class="far fa-clock"></i> 5 Days</span>
                  <span><i class="fas fa-users"></i> Max 12 Students</span>
                  <span><i class="fas fa-globe"></i> English</span>
                </div>
                <p>
                  Build Python programming skills for automating and analyzing
                  geospatial data.
                </p>
                <ul class="course-features">
                  <li>
                    <i class="fas fa-check-circle"></i> Python fundamentals
                  </li>
                  <li>
                    <i class="fas fa-check-circle"></i> GeoPandas & GDAL
                    libraries
                  </li>
                  <li>
                    <i class="fas fa-check-circle"></i> Automating GIS workflows
                  </li>
                </ul>
                <div class="course-footer">
                  <span class="course-price">100,000 RWF</span>
                  <a href="#" class="btn-enroll">Enroll Now</a>
                </div>
              </div>
            </div>

            <div class="course-card" data-aos="fade-up" data-aos-delay="200">
              <div class="course-image">
                <img
                  src="../images/data-analysis.jpg"
                  alt="Spatial Data Analysis"
                />
                <div class="course-level intermediate">Intermediate</div>
              </div>
              <div class="course-content">
                <h3>Spatial Data Analysis & Visualization</h3>
                <div class="course-meta">
                  <span><i class="far fa-clock"></i> 4 Days</span>
                  <span><i class="fas fa-users"></i> Max 10 Students</span>
                  <span><i class="fas fa-globe"></i> English</span>
                </div>
                <p>
                  Learn techniques for analyzing and visualizing spatial data to
                  derive meaningful insights.
                </p>
                <ul class="course-features">
                  <li>
                    <i class="fas fa-check-circle"></i> Statistical analysis
                  </li>
                  <li>
                    <i class="fas fa-check-circle"></i> Advanced visualization
                  </li>
                  <li><i class="fas fa-check-circle"></i> Report generation</li>
                </ul>
                <div class="course-footer">
                  <span class="course-price">85,000 RWF</span>
                  <a href="#" class="btn-enroll">Enroll Now</a>
                </div>
              </div>
            </div>
          </div>

          <!-- GIS & Remote Sensing Courses -->
          <div class="courses-grid" id="gis-courses">
            <div class="course-card" data-aos="fade-up">
              <div class="course-image">
                <img
                  src="../images/arcgis-training.jpg"
                  alt="ArcGIS Fundamentals"
                />
                <div class="course-level beginner">Beginner</div>
              </div>
              <div class="course-content">
                <h3>ArcGIS Fundamentals</h3>
                <div class="course-meta">
                  <span><i class="far fa-clock"></i> 5 Days</span>
                  <span><i class="fas fa-users"></i> Max 15 Students</span>
                  <span><i class="fas fa-globe"></i> English</span>
                </div>
                <p>
                  Comprehensive introduction to ArcGIS for mapping, spatial
                  analysis, and data management.
                </p>
                <ul class="course-features">
                  <li>
                    <i class="fas fa-check-circle"></i> Data handling &
                    management
                  </li>
                  <li>
                    <i class="fas fa-check-circle"></i> Map creation & layout
                    design
                  </li>
                  <li>
                    <i class="fas fa-check-circle"></i> Basic spatial analysis
                  </li>
                </ul>
                <div class="course-footer">
                  <span class="course-price">95,000 RWF</span>
                  <a href="#" class="btn-enroll">Enroll Now</a>
                </div>
              </div>
            </div>

            <div class="course-card" data-aos="fade-up" data-aos-delay="100">
              <div class="course-image">
                <img
                  src="../images/remote-sensing.jpg"
                  alt="Remote Sensing Fundamentals"
                />
                <div class="course-level intermediate">Intermediate</div>
              </div>
              <div class="course-content">
                <h3>Remote Sensing Fundamentals</h3>
                <div class="course-meta">
                  <span><i class="far fa-clock"></i> 4 Days</span>
                  <span><i class="fas fa-users"></i> Max 12 Students</span>
                  <span><i class="fas fa-globe"></i> English</span>
                </div>
                <p>
                  Learn the principles of remote sensing and satellite imagery
                  for environmental monitoring.
                </p>
                <ul class="course-features">
                  <li>
                    <i class="fas fa-check-circle"></i> Image acquisition &
                    processing
                  </li>
                  <li>
                    <i class="fas fa-check-circle"></i> Land cover
                    classification
                  </li>
                  <li>
                    <i class="fas fa-check-circle"></i> Change detection
                    analysis
                  </li>
                </ul>
                <div class="course-footer">
                  <span class="course-price">110,000 RWF</span>
                  <a href="#" class="btn-enroll">Enroll Now</a>
                </div>
              </div>
            </div>

            <div class="course-card" data-aos="fade-up" data-aos-delay="200">
              <div class="course-image">
                <img src="../images/qgis-training.jpg" alt="QGIS Advanced" />
                <div class="course-level advanced">Advanced</div>
              </div>
              <div class="course-content">
                <h3>QGIS Advanced Techniques</h3>
                <div class="course-meta">
                  <span><i class="far fa-clock"></i> 4 Days</span>
                  <span><i class="fas fa-users"></i> Max 10 Students</span>
                  <span><i class="fas fa-globe"></i> English</span>
                </div>
                <p>
                  Master advanced QGIS capabilities for complex GIS projects and
                  open-source integration.
                </p>
                <ul class="course-features">
                  <li>
                    <i class="fas fa-check-circle"></i> Custom plugins & Python
                    scripting
                  </li>
                  <li>
                    <i class="fas fa-check-circle"></i> Database integration
                  </li>
                  <li>
                    <i class="fas fa-check-circle"></i> Web mapping solutions
                  </li>
                </ul>
                <div class="course-footer">
                  <span class="course-price">95,000 RWF</span>
                  <a href="#" class="btn-enroll">Enroll Now</a>
                </div>
              </div>
            </div>
          </div>

          <!-- Advanced Technologies Courses -->
          <div class="courses-grid" id="advanced-courses">
            <div class="course-card" data-aos="fade-up">
              <div class="course-image">
                <img src="../images/drone-mapping.jpg" alt="Drone Mapping" />
                <div class="course-level intermediate">Intermediate</div>
              </div>
              <div class="course-content">
                <h3>Drone Mapping & Photogrammetry</h3>
                <div class="course-meta">
                  <span><i class="far fa-clock"></i> 5 Days</span>
                  <span><i class="fas fa-users"></i> Max 8 Students</span>
                  <span><i class="fas fa-globe"></i> English</span>
                </div>
                <p>
                  Learn to plan, execute, and process drone surveys for mapping
                  and 3D modeling.
                </p>
                <ul class="course-features">
                  <li>
                    <i class="fas fa-check-circle"></i> Flight planning &
                    regulations
                  </li>
                  <li>
                    <i class="fas fa-check-circle"></i> Data capture techniques
                  </li>
                  <li>
                    <i class="fas fa-check-circle"></i> Photogrammetry
                    processing
                  </li>
                </ul>
                <div class="course-footer">
                  <span class="course-price">150,000 RWF</span>
                  <a href="#" class="btn-enroll">Enroll Now</a>
                </div>
              </div>
            </div>

            <div class="course-card" data-aos="fade-up" data-aos-delay="100">
              <div class="course-image">
                <img
                  src="../images/ai-geospatial.jpg"
                  alt="AI for Geospatial"
                />
                <div class="course-level advanced">Advanced</div>
              </div>
              <div class="course-content">
                <h3>AI for Geospatial Analysis</h3>
                <div class="course-meta">
                  <span><i class="far fa-clock"></i> 5 Days</span>
                  <span><i class="fas fa-users"></i> Max 10 Students</span>
                  <span><i class="fas fa-globe"></i> English</span>
                </div>
                <p>
                  Master machine learning and deep learning applications for
                  geographic data analysis.
                </p>
                <ul class="course-features">
                  <li>
                    <i class="fas fa-check-circle"></i> ML fundamentals for
                    spatial data
                  </li>
                  <li>
                    <i class="fas fa-check-circle"></i> CNN for image
                    classification
                  </li>
                  <li>
                    <i class="fas fa-check-circle"></i> Predictive modeling
                  </li>
                </ul>
                <div class="course-footer">
                  <span class="course-price">175,000 RWF</span>
                  <a href="#" class="btn-enroll">Enroll Now</a>
                </div>
              </div>
            </div>

            <div class="course-card" data-aos="fade-up" data-aos-delay="200">
              <div class="course-image">
                <img
                  src="../images/lidar-processing.jpg"
                  alt="LiDAR Processing"
                />
                <div class="course-level advanced">Advanced</div>
              </div>
              <div class="course-content">
                <h3>LiDAR Data Processing & Analysis</h3>
                <div class="course-meta">
                  <span><i class="far fa-clock"></i> 4 Days</span>
                  <span><i class="fas fa-users"></i> Max 8 Students</span>
                  <span><i class="fas fa-globe"></i> English</span>
                </div>
                <p>
                  Learn to process and analyze LiDAR point cloud data for 3D
                  modeling and terrain analysis.
                </p>
                <ul class="course-features">
                  <li>
                    <i class="fas fa-check-circle"></i> Point cloud
                    classification
                  </li>
                  <li>
                    <i class="fas fa-check-circle"></i> DTM/DSM generation
                  </li>
                  <li>
                    <i class="fas fa-check-circle"></i> Feature extraction
                  </li>
                </ul>
                <div class="course-footer">
                  <span class="course-price">160,000 RWF</span>
                  <a href="#" class="btn-enroll">Enroll Now</a>
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>

      <!-- Featured Course -->
      <section class="featured-course">
        <div class="container">
          <div class="featured-content" data-aos="fade-right">
            <div class="featured-label">Featured Course</div>
            <h2>Comprehensive Surveying Professional Certificate</h2>
            <p>
              Our flagship 3-week program combines essential surveying skills,
              CAD drafting, and GIS analysis into one comprehensive
              certification course.
            </p>
            <ul class="featured-highlights">
              <li>
                <i class="fas fa-check"></i> Total Station & GPS field
                operations
              </li>
              <li>
                <i class="fas fa-check"></i> AutoCAD drafting for surveyors
              </li>
              <li>
                <i class="fas fa-check"></i> GIS analysis & map production
              </li>
              <li>
                <i class="fas fa-check"></i> Survey mathematics & data
                processing
              </li>
              <li>
                <i class="fas fa-check"></i> Professional certification exam
              </li>
            </ul>
            <div class="featured-footer">
              <div class="featured-price">
                <span class="price-label">Price</span>
                <span class="price-amount">350,000 RWF</span>
              </div>
              <a href="./contact.php" class="btn-primary">Learn More</a>
            </div>
          </div>
          <div class="featured-image" data-aos="fade-left">
            <img
              src="../images/rC9AvGLJMT6thyTyTxB--.jpg"
              alt="Comprehensive Surveying Professional"
            />
          </div>
        </div>
      </section>

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
