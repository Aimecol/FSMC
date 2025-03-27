document.addEventListener("DOMContentLoaded", function () {
  // Initialize AOS (Animate on Scroll)
  AOS.init({
    duration: 500,
    easing: "ease-in-out",
    once: true,
    mirror: false,
  });

  // Mobile Navigation Toggle
  const navToggle = document.getElementById("navToggle");
  const navigation = document.getElementById("navigation");

  if (navToggle) {
    navToggle.addEventListener("click", function () {
      navigation.classList.toggle("active");

      // Toggle icon between bars and times
      const icon = this.querySelector("i");
      if (icon.classList.contains("fa-bars")) {
        icon.classList.remove("fa-bars");
        icon.classList.add("fa-times");
      } else {
        icon.classList.remove("fa-times");
        icon.classList.add("fa-bars");
      }
    });
  }

  // Course Category Tabs
  const categoryTabs = document.querySelectorAll(".tab-btn");
  const courseGrids = document.querySelectorAll(".courses-grid");

  categoryTabs.forEach((tab) => {
    tab.addEventListener("click", () => {
      // Remove active class from all tabs and course grids
      categoryTabs.forEach((t) => t.classList.remove("active"));
      courseGrids.forEach((grid) => grid.classList.remove("active"));

      // Add active class to clicked tab
      tab.classList.add("active");

      // Show corresponding course grid
      const category = tab.getAttribute("data-category");
      document.getElementById(`${category}-courses`).classList.add("active");
    });
  });

  // Testimonial Slider
  const testimonialTrack = document.getElementById("testimonialTrack");
  const prevBtn = document.getElementById("prevTestimonial");
  const nextBtn = document.getElementById("nextTestimonial");
  const indicators = document.querySelectorAll(".nav-indicators .indicator");
  let currentSlide = 0;
  const totalSlides = document.querySelectorAll(".testimonial-item").length;

  function updateTestimonialSlider() {
    testimonialTrack.style.transform = `translateX(-${currentSlide * 100}%)`;

    // Update indicators
    indicators.forEach((indicator) => indicator.classList.remove("active"));
    indicators[currentSlide].classList.add("active");
  }

  if (prevBtn && nextBtn) {
    prevBtn.addEventListener("click", () => {
      currentSlide = currentSlide === 0 ? totalSlides - 1 : currentSlide - 1;
      updateTestimonialSlider();
    });

    nextBtn.addEventListener("click", () => {
      currentSlide = currentSlide === totalSlides - 1 ? 0 : currentSlide + 1;
      updateTestimonialSlider();
    });
  }

  indicators.forEach((indicator) => {
    indicator.addEventListener("click", () => {
      currentSlide = parseInt(indicator.getAttribute("data-index"));
      updateTestimonialSlider();
    });
  });

  // Auto-rotate testimonials every 6 seconds
  const testimonialInterval = setInterval(() => {
    currentSlide = currentSlide === totalSlides - 1 ? 0 : currentSlide + 1;
    updateTestimonialSlider();
  }, 6000);

  // Pause auto-rotation when user interacts with slider
  const testimonialSlider = document.querySelector(".testimonial-slider");
  if (testimonialSlider) {
    testimonialSlider.addEventListener("mouseenter", () => {
      clearInterval(testimonialInterval);
    });
  }

  // Schedule Tabs
  const scheduleTabs = document.querySelectorAll(".schedule-tab");
  const scheduleMonths = document.querySelectorAll(".schedule-month");

  scheduleTabs.forEach((tab) => {
    tab.addEventListener("click", () => {
      // Remove active class from all tabs and months
      scheduleTabs.forEach((t) => t.classList.remove("active"));
      scheduleMonths.forEach((month) => month.classList.remove("active"));

      // Add active class to clicked tab
      tab.classList.add("active");

      // Show corresponding month
      const month = tab.getAttribute("data-month");
      document.getElementById(`${month}-schedule`).classList.add("active");
    });
  });

  // FAQ Accordion
  const faqQuestions = document.querySelectorAll(".faq-question");

  faqQuestions.forEach((question) => {
    question.addEventListener("click", () => {
      const answer = question.nextElementSibling;
      const toggle = question.querySelector(".faq-toggle");

      // Close all other answers
      document.querySelectorAll(".faq-answer").forEach((item) => {
        if (item !== answer) {
          item.classList.remove("active");
          item.previousElementSibling
            .querySelector(".faq-toggle")
            .classList.remove("active");
        }
      });

      // Toggle current answer
      answer.classList.toggle("active");
      toggle.classList.toggle("active");
    });
  });

  // Smooth Scroll for Internal Links
  document.querySelectorAll('a[href^="#"]').forEach((anchor) => {
    anchor.addEventListener("click", function (e) {
      e.preventDefault();

      const targetId = this.getAttribute("href");
      if (targetId === "#") return;

      const targetElement = document.querySelector(targetId);
      if (targetElement) {
        window.scrollTo({
          top: targetElement.offsetTop - 100,
          behavior: "smooth",
        });

        // Close mobile menu if open
        if (navigation.classList.contains("active")) {
          navigation.classList.remove("active");
          if (navToggle.querySelector("i").classList.contains("fa-times")) {
            navToggle.querySelector("i").classList.remove("fa-times");
            navToggle.querySelector("i").classList.add("fa-bars");
          }
        }
      }
    });
  });

  // Course Enrollment Button Animation
  const enrollButtons = document.querySelectorAll(".btn-enroll");

  enrollButtons.forEach((button) => {
    button.addEventListener("click", function (e) {
      e.preventDefault();

      // Add pulse animation class
      this.classList.add("btn-pulse");

      // Remove animation class after animation completes
      setTimeout(() => {
        this.classList.remove("btn-pulse");

        // Show enrollment confirmation modal or redirect to enrollment page
        // This would be implemented based on the enrollment flow
        window.location.href = './enrollment.html';
      }, 300);
    });
  });

  // Schedule Book Now Button Animation
  const bookButtons = document.querySelectorAll(".btn-book:not(.disabled)");

  bookButtons.forEach((button) => {
    button.addEventListener("click", function (e) {
      e.preventDefault();

      // Prevent multiple clicks
      if (this.classList.contains("btn-loading")) return;

      // Add loading state
      this.classList.add("btn-loading");
      const originalText = this.textContent;
      this.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Processing...';

      // Simulate booking process
      setTimeout(() => {
        this.classList.remove("btn-loading");
        this.innerHTML = '<i class="fas fa-check"></i> Reserved';

        // Reset after 2 seconds
        setTimeout(() => {
          this.innerHTML = originalText;
        }, 2000);
      }, 1500);
    });
  });

  // Sticky Header on Scroll
  const header = document.querySelector(".header");
  let lastScrollTop = 0;

  window.addEventListener("scroll", function () {
    const scrollTop = window.pageYOffset || document.documentElement.scrollTop;

    if (scrollTop > 100) {
      header.classList.add("sticky");

      // Hide header when scrolling down, show when scrolling up
      if (scrollTop > lastScrollTop) {
        header.style.top = "-100px";
      } else {
        header.style.top = "0";
      }
    } else {
      header.classList.remove("sticky");
      header.style.top = "0";
    }

    lastScrollTop = scrollTop;
  });

  // Course Filter for Mobile
  const filterToggle = document.createElement("button");
  filterToggle.className = "filter-toggle";
  filterToggle.innerHTML = '<i class="fas fa-filter"></i> Filter Courses';

  if (window.innerWidth < 768) {
    const categoriesSection = document.querySelector(
      ".categories-section .container"
    );
    if (categoriesSection) {
      categoriesSection.insertBefore(
        filterToggle,
        document.querySelector(".categories-tabs")
      );

      const categoriesTabs = document.querySelector(".categories-tabs");
      categoriesTabs.classList.add("mobile-hidden");

      filterToggle.addEventListener("click", () => {
        categoriesTabs.classList.toggle("mobile-hidden");

        if (categoriesTabs.classList.contains("mobile-hidden")) {
          filterToggle.innerHTML =
            '<i class="fas fa-filter"></i> Filter Courses';
        } else {
          filterToggle.innerHTML = '<i class="fas fa-times"></i> Close Filter';
        }
      });
    }
  }

  // Add animation class to course cards for hover effect
  const courseCards = document.querySelectorAll(".course-card");
  courseCards.forEach((card) => {
    card.classList.add("animate-on-hover");

    // Create preview overlay for course cards
    const courseImage = card.querySelector(".course-image");
    const previewBtn = document.createElement("button");
    previewBtn.className = "preview-btn";
    previewBtn.innerHTML = '<i class="fas fa-eye"></i> Quick Preview';

    if (courseImage) {
      courseImage.appendChild(previewBtn);

      previewBtn.addEventListener("click", function (e) {
        e.preventDefault();
        e.stopPropagation();

        const courseTitle = card.querySelector("h3").textContent;
        const courseDesc = card.querySelector("p").textContent;
        const coursePrice = card.querySelector(".course-price").textContent;
        const courseFeatures = Array.from(
          card.querySelectorAll(".course-features li")
        ).map((li) => li.textContent);

        // Create a modal for course preview
        const modal = document.createElement("div");
        modal.className = "preview-modal";

        modal.innerHTML = `
                    <div class="preview-content">
                        <button class="close-preview"><i class="fas fa-times"></i></button>
                        <h3>${courseTitle}</h3>
                        <p>${courseDesc}</p>
                        <div class="preview-details">
                            <div class="preview-features">
                                <h4>What You'll Learn</h4>
                                <ul>
                                    ${courseFeatures
                                      .map((feature) => `<li>${feature}</li>`)
                                      .join("")}
                                </ul>
                            </div>
                            <div class="preview-info">
                                <div class="preview-price">
                                    <span>Price</span>
                                    <strong>${coursePrice}</strong>
                                </div>
                                <a href="./enrollment.html" class="btn-primary">Enroll</a>
                            </div>
                        </div>
                    </div>
                `;

        document.body.appendChild(modal);

        // Prevent scrolling when modal is open
        document.body.style.overflow = "hidden";

        // Fade in the modal
        setTimeout(() => {
          modal.classList.add("active");
        }, 10);

        // Close modal functionality
        const closeBtn = modal.querySelector(".close-preview");
        closeBtn.addEventListener("click", () => {
          modal.classList.remove("active");

          // Remove modal after animation
          setTimeout(() => {
            document.body.removeChild(modal);
            document.body.style.overflow = "";
          }, 300);
        });

        // Close on click outside
        modal.addEventListener("click", function (e) {
          if (e.target === modal) {
            closeBtn.click();
          }
        });
      });
    }
  });

  // Add button pulse animation
  const addPulseAnimation = (button) => {
    button.classList.add("btn-pulse");
    setTimeout(() => {
      button.classList.remove("btn-pulse");
    }, 300);
  };

  // Enhance CTA buttons
  const ctaButtons = document.querySelectorAll(".btn-primary, .btn-secondary");
  ctaButtons.forEach((button) => {
    button.addEventListener("click", function (e) {
      // Don't prevent default if it's an anchor with a real href
      if (
        this.getAttribute("href") === "#" ||
        this.getAttribute("href") === ""
      ) {
        e.preventDefault();
      }

      addPulseAnimation(this);
    });
  });

  // Course category counters
  function updateCategoryCounts() {
    const categoryTabs = document.querySelectorAll(".tab-btn");

    categoryTabs.forEach((tab) => {
      const category = tab.getAttribute("data-category");
      const courseCount = document.querySelectorAll(
        `#${category}-courses .course-card`
      ).length;

      // Add or update count span
      let countSpan = tab.querySelector(".category-count");
      if (!countSpan) {
        countSpan = document.createElement("span");
        countSpan.className = "category-count";
        tab.appendChild(countSpan);
      }

      countSpan.textContent = courseCount;
    });
  }

  updateCategoryCounts();

  // Form validation for registration button
  const registerBtn = document.querySelector(".enrollment-cta .btn-primary");
  if (registerBtn) {
    registerBtn.addEventListener("click", function (e) {
      e.preventDefault();

      // Create modal for registration form
      const modal = document.createElement("div");
      modal.className = "register-modal";

      modal.innerHTML = `
                <div class="register-content">
                    <button class="close-register"><i class="fas fa-times"></i></button>
                    <h3>Register for Training</h3>
                    <form id="registrationForm">
                        <div class="form-group">
                            <label for="fullName">Full Name *</label>
                            <input type="text" id="fullName" required>
                        </div>
                        <div class="form-group">
                            <label for="email">Email Address *</label>
                            <input type="email" id="email" required>
                        </div>
                        <div class="form-group">
                            <label for="phone">Phone Number *</label>
                            <input type="tel" id="phone" required>
                        </div>
                        <div class="form-group">
                            <label for="courseSelect">Select Course *</label>
                            <select id="courseSelect" required>
                                <option value="">-- Select a Course --</option>
                                <option value="total-station">Total Station Masterclass</option>
                                <option value="gps-systems">GPS Systems Fundamentals</option>
                                <option value="rtk-gps">RTK GPS Advanced Techniques</option>
                                <option value="autocad">AutoCAD for Surveyors</option>
                                <option value="python">Python for Geospatial Analysis</option>
                                <option value="arcgis">ArcGIS Fundamentals</option>
                                <option value="drone">Drone Mapping & Photogrammetry</option>
                                <option value="lidar">LiDAR Data Processing & Analysis</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="preferredDate">Preferred Date</label>
                            <input type="date" id="preferredDate">
                        </div>
                        <div class="form-group">
                            <label for="message">Additional Information</label>
                            <textarea id="message" rows="3"></textarea>
                        </div>
                        <div class="form-actions">
                            <button type="submit" class="btn-primary">Submit Registration</button>
                        </div>
                    </form>
                </div>
            `;

      document.body.appendChild(modal);

      // Prevent scrolling when modal is open
      document.body.style.overflow = "hidden";

      // Fade in the modal
      setTimeout(() => {
        modal.classList.add("active");
      }, 10);

      // Close modal functionality
      const closeBtn = modal.querySelector(".close-register");
      closeBtn.addEventListener("click", () => {
        modal.classList.remove("active");

        // Remove modal after animation
        setTimeout(() => {
          document.body.removeChild(modal);
          document.body.style.overflow = "";
        }, 300);
      });

      // Close on click outside
      modal.addEventListener("click", function (e) {
        if (e.target === modal) {
          closeBtn.click();
        }
      });

      // Form submission
      const form = modal.querySelector("#registrationForm");
      form.addEventListener("submit", function (e) {
        e.preventDefault();

        // Show loading state
        const submitBtn = this.querySelector('button[type="submit"]');
        const originalText = submitBtn.innerHTML;
        submitBtn.innerHTML =
          '<i class="fas fa-spinner fa-spin"></i> Processing...';
        submitBtn.disabled = true;

        // Simulate form submission
        setTimeout(() => {
          // Replace form with success message
          const formParent = form.parentElement;
          form.innerHTML = `
                        <div class="registration-success">
                            <div class="success-icon">
                                <i class="fas fa-check-circle"></i>
                            </div>
                            <h3>Registration Successful!</h3>
                            <p>Thank you for registering for our training program. We have sent a confirmation email with further details to your email address.</p>
                            <button class="btn-primary" id="closeSuccess">Close</button>
                        </div>
                    `;

          // Close on success button click
          document
            .getElementById("closeSuccess")
            .addEventListener("click", () => {
              closeBtn.click();
            });
        }, 2000);
      });
    });
  }

  // Add course search functionality
  const searchBox = document.createElement("div");
  searchBox.className = "course-search";
  searchBox.innerHTML = `
        <input type="text" id="courseSearch" placeholder="Search courses...">
        <button id="searchButton"><i class="fas fa-search"></i></button>
    `;

  const categoriesSection = document.querySelector(
    ".categories-section .container"
  );
  if (categoriesSection) {
    categoriesSection.insertBefore(
      searchBox,
      categoriesSection.querySelector("h2").nextSibling
    );

    const searchInput = document.getElementById("courseSearch");
    const searchButton = document.getElementById("searchButton");

    function performSearch() {
      const searchTerm = searchInput.value.toLowerCase().trim();
      const allCourseCards = document.querySelectorAll(".course-card");

      if (searchTerm === "") {
        // Show all courses if search is empty
        allCourseCards.forEach((card) => {
          card.style.display = "";
        });
        return;
      }

      // Show all course grids to search across all courses
      courseGrids.forEach((grid) => (grid.style.display = "grid"));

      // Filter courses
      allCourseCards.forEach((card) => {
        const title = card.querySelector("h3").textContent.toLowerCase();
        const desc = card.querySelector("p").textContent.toLowerCase();
        const features = Array.from(
          card.querySelectorAll(".course-features li")
        )
          .map((li) => li.textContent.toLowerCase())
          .join(" ");

        if (
          title.includes(searchTerm) ||
          desc.includes(searchTerm) ||
          features.includes(searchTerm)
        ) {
          card.style.display = "";
        } else {
          card.style.display = "none";
        }
      });

      // Hide empty grids
      courseGrids.forEach((grid) => {
        const visibleCourses = grid.querySelectorAll(
          '.course-card[style=""]'
        ).length;
        if (visibleCourses === 0) {
          grid.style.display = "none";
        }
      });

      // Show the first non-empty grid
      const firstVisibleGrid = document.querySelector(
        '.courses-grid[style="display: grid;"]'
      );
      if (firstVisibleGrid) {
        // Activate corresponding tab
        const category = firstVisibleGrid.id.replace("-courses", "");
        categoryTabs.forEach((tab) => {
          tab.classList.remove("active");
          if (tab.getAttribute("data-category") === category) {
            tab.classList.add("active");
          }
        });
      }
    }

    searchButton.addEventListener("click", performSearch);
    searchInput.addEventListener("keyup", function (e) {
      if (e.key === "Enter") {
        performSearch();
      }
    });
  }

  // Intersection Observer for revealing course cards
  if ("IntersectionObserver" in window) {
    const courseCards = document.querySelectorAll(".course-card");

    const courseObserver = new IntersectionObserver(
      (entries) => {
        entries.forEach((entry) => {
          if (entry.isIntersecting) {
            entry.target.classList.add("revealed");
            courseObserver.unobserve(entry.target);
          }
        });
      },
      {
        threshold: 0.1,
      }
    );

    courseCards.forEach((card) => {
      card.classList.add("reveal-card");
      courseObserver.observe(card);
    });
  }

  // Add animation for button pulse
  const style = document.createElement("style");
  style.textContent = `
        @keyframes btnPulse {
            0% {transform: scale(1);}
            50% {transform: scale(1.05);}
            100% {transform: scale(1);}
        }
        .btn-pulse {
            animation: btnPulse 0.3s ease;
        }
        .reveal-card {
            opacity: 0;
            transform: translateY(20px);
            transition: opacity 0.6s ease, transform 0.6s ease;
        }
        .reveal-card.revealed {
            opacity: 1;
            transform: translateY(0);
        }
        .mobile-hidden {
            display: none;
        }
        .filter-toggle {
            width: 100%;
            padding: 0.75rem 1rem;
            background-color: var(--primary-color);
            color: var(--text-light);
            border: none;
            border-radius: var(--border-radius-md);
            margin-bottom: 1rem;
            font-weight: 600;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            cursor: pointer;
        }
        .course-search {
            display: flex;
            margin: 1rem auto 2rem;
            max-width: 500px;
        }
        .course-search input {
            flex: 1;
            padding: 0.75rem 1rem;
            border: 1px solid var(--border-color);
            border-right: none;
            border-radius: var(--border-radius-md) 0 0 var(--border-radius-md);
            font-size: 1rem;
        }
        .course-search button {
            padding: 0.75rem 1rem;
            background-color: var(--primary-color);
            color: var(--text-light);
            border: none;
            border-radius: 0 var(--border-radius-md) var(--border-radius-md) 0;
            cursor: pointer;
        }
        .category-count {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 20px;
            height: 20px;
            background-color: rgba(255,255,255,0.3);
            border-radius: 50%;
            font-size: 0.75rem;
            margin-left: 0.5rem;
        }
        .preview-btn {
            position: absolute;
            bottom: 1rem;
            left: 50%;
            transform: translateX(-50%) translateY(20px);
            background-color: var(--primary-color);
            color: var(--text-light);
            border: none;
            border-radius: var(--border-radius-md);
            padding: 0.5rem 1rem;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            cursor: pointer;
            opacity: 0;
            transition: all var(--transition-fast);
        }
        .course-image:hover .preview-btn {
            opacity: 1;
            transform: translateX(-50%) translateY(0);
        }
        .preview-modal, .register-modal {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.8);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 1000;
            opacity: 0;
            transition: opacity var(--transition-fast);
        }
        .preview-modal.active, .register-modal.active {
            opacity: 1;
        }
        .preview-content, .register-content {
            background-color: var(--bg-white);
            border-radius: var(--border-radius-lg);
            padding: 2rem;
            max-width: 800px;
            width: 90%;
            max-height: 90vh;
            overflow-y: auto;
            position: relative;
            transform: translateY(20px);
            transition: transform var(--transition-fast);
        }
        .preview-modal.active .preview-content,
        .register-modal.active .register-content {
            transform: translateY(0);
        }
        .close-preview, .close-register {
            position: absolute;
            top: 1rem;
            right: 1rem;
            background: none;
            border: none;
            font-size: 1.5rem;
            color: var(--text-secondary);
            cursor: pointer;
        }
        .preview-details {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 2rem;
            margin-top: 1.5rem;
        }
        .preview-price {
            margin-bottom: 1.5rem;
        }
        .preview-price span {
            display: block;
            font-size: 0.9rem;
            color: var(--text-secondary);
        }
        .preview-price strong {
            font-size: 1.5rem;
            color: var(--primary-color);
        }
        .form-group {
            margin-bottom: 1.5rem;
        }
        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 600;
            color: var(--text-primary);
        }
        .form-group input, .form-group textarea, .form-group select {
            width: 100%;
            padding: 0.75rem;
            border: 1px solid var(--border-color);
            border-radius: var(--border-radius-sm);
            font-size: 1rem;
        }
        .form-actions {
            margin-top: 2rem;
            text-align: right;
        }
        .registration-success {
            text-align: center;
            padding: 2rem 0;
        }
        .success-icon {
            font-size: 3rem;
            color: var(--success-color);
            margin-bottom: 1rem;
        }
        .registration-success h3 {
            color: var(--success-color);
            margin-bottom: 1rem;
        }
        .registration-success p {
            margin-bottom: 2rem;
        }
        @media (max-width: 768px) {
            .preview-details {
                grid-template-columns: 1fr;
            }
        }
    `;

  document.head.appendChild(style);

  // Lazy load images
  if ("IntersectionObserver" in window) {
    const lazyImages = document.querySelectorAll("img[data-src]");

    const imageObserver = new IntersectionObserver((entries) => {
      entries.forEach((entry) => {
        if (entry.isIntersecting) {
          const img = entry.target;
          img.src = img.dataset.src;
          img.removeAttribute("data-src");
          imageObserver.unobserve(img);
        }
      });
    });

    lazyImages.forEach((img) => {
      imageObserver.observe(img);
    });
  }

  // Initialize animations and interactive elements
  console.log("Training page initialized");
});
