document.addEventListener("DOMContentLoaded", function () {
  // Initialize AOS animations
  AOS.init({
    duration: 800,
    easing: "ease-in-out",
    once: true,
    mirror: false,
  });

  // Mobile Navigation
  const navToggle = document.getElementById("navToggle");
  const navigation = document.getElementById("navigation");

  if (navToggle) {
    navToggle.addEventListener("click", function () {
      navigation.classList.toggle("active");

      // Change icon based on menu state
      const icon = navToggle.querySelector("i");
      if (navigation.classList.contains("active")) {
        icon.classList.remove("fa-bars");
        icon.classList.add("fa-times");
      } else {
        icon.classList.remove("fa-times");
        icon.classList.add("fa-bars");
      }
    });
  }

  // Close menu when clicking outside
  document.addEventListener("click", function (event) {
    if (navigation && navToggle) {
      const isClickInsideNav = navigation.contains(event.target);
      const isClickOnToggle = navToggle.contains(event.target);

      if (
        !isClickInsideNav &&
        !isClickOnToggle &&
        navigation.classList.contains("active")
      ) {
        navigation.classList.remove("active");
        const icon = navToggle.querySelector("i");
        icon.classList.remove("fa-times");
        icon.classList.add("fa-bars");
      }
    }
  });

  // Project Filtering
  const filterButtons = document.querySelectorAll(".filter-btn");
  const projectCards = document.querySelectorAll(".project-card");

  if (filterButtons.length > 0) {
    filterButtons.forEach((button) => {
      button.addEventListener("click", () => {
        // Remove active class from all buttons
        filterButtons.forEach((btn) => btn.classList.remove("active"));

        // Add active class to clicked button
        button.classList.add("active");

        // Get filter value
        const filterValue = button.getAttribute("data-filter");

        // Filter projects
        projectCards.forEach((card) => {
          const cardCategory = card.getAttribute("data-category");

          // Show/hide based on filter
          if (filterValue === "all" || filterValue === cardCategory) {
            card.style.display = "block";
            setTimeout(() => {
              card.classList.add("visible");
            }, 100);
          } else {
            card.classList.remove("visible");
            setTimeout(() => {
              card.style.display = "none";
            }, 300);
          }
        });
      });
    });
  }

  // Animated Counters
  const counters = document.querySelectorAll(".counter");

  const animateCounter = (counter, target) => {
    const speed = 200; // Lower is faster
    const increment = target / speed;
    let current = 0;

    const updateCount = () => {
      if (current < target) {
        current += increment;
        counter.textContent = Math.ceil(current);
        setTimeout(updateCount, 1);
      } else {
        counter.textContent = target;
      }
    };

    updateCount();
  };

  // Initialize counters when they come into view
  if (counters.length > 0) {
    const counterObserver = new IntersectionObserver(
      (entries, observer) => {
        entries.forEach((entry) => {
          if (entry.isIntersecting) {
            const counter = entry.target;
            const target = parseInt(counter.textContent);
            counter.textContent = "0";
            animateCounter(counter, target);
            observer.unobserve(counter);
          }
        });
      },
      { threshold: 0.5 }
    );

    counters.forEach((counter) => {
      counterObserver.observe(counter);
    });
  }

  // Project Modal Functionality
  const projectLinks = document.querySelectorAll(".btn-view-project");
  const modals = document.querySelectorAll(".modal");
  const closeButtons = document.querySelectorAll(".close-modal");

  if (projectLinks.length > 0) {
    projectLinks.forEach((link) => {
      link.addEventListener("click", function (e) {
        e.preventDefault();
        const modalId = this.getAttribute("href");
        const modal = document.querySelector(modalId);

        if (modal) {
          modal.style.display = "flex";
          setTimeout(() => {
            modal.classList.add("show");
          }, 10);
          document.body.style.overflow = "hidden";
        }
      });
    });
  }

  if (closeButtons.length > 0) {
    closeButtons.forEach((button) => {
      button.addEventListener("click", function () {
        const modal = this.closest(".modal");
        modal.classList.remove("show");
        setTimeout(() => {
          modal.style.display = "none";
          document.body.style.overflow = "auto";
        }, 300);
      });
    });
  }

  // Close modal when clicking outside content
  if (modals.length > 0) {
    modals.forEach((modal) => {
      modal.addEventListener("click", function (e) {
        if (e.target === this) {
          this.classList.remove("show");
          setTimeout(() => {
            this.style.display = "none";
            document.body.style.overflow = "auto";
          }, 300);
        }
      });
    });
  }

  // Enhanced Modal Gallery Functionality
  const galleryThumbs = document.querySelectorAll(".gallery-thumbs img");
  const galleryMain = document.querySelector(".gallery-main img");

  if (galleryThumbs.length > 0 && galleryMain) {
    galleryThumbs.forEach((thumb) => {
      thumb.addEventListener("click", function () {
        // Update main image
        galleryMain.src = this.src;
        galleryMain.alt = this.alt;

        // Update active state
        galleryThumbs.forEach((t) => t.classList.remove("active"));
        this.classList.add("active");
      });
    });
  }

  // Smooth scroll for modal content
  const modalBody = document.querySelector(".modal-body");
  if (modalBody) {
    modalBody.addEventListener("scroll", function () {
      const scrolled = modalBody.scrollTop > 50;
      modalBody.classList.toggle("scrolled", scrolled);
    });
  }

  // Add touch swipe support for gallery
  let touchStartX = 0;
  let touchEndX = 0;

  const galleryContainer = document.querySelector(".gallery-thumbs");
  if (galleryContainer) {
    galleryContainer.addEventListener(
      "touchstart",
      (e) => {
        touchStartX = e.changedTouches[0].screenX;
      },
      false
    );

    galleryContainer.addEventListener(
      "touchend",
      (e) => {
        touchEndX = e.changedTouches[0].screenX;
        handleSwipe();
      },
      false
    );
  }

  function handleSwipe() {
    const swipeDistance = touchEndX - touchStartX;
    if (Math.abs(swipeDistance) > 50) {
      galleryContainer.scrollBy({
        left: -swipeDistance,
        behavior: "smooth",
      });
    }
  }

  // Load More Projects Button
  const loadMoreBtn = document.querySelector(".load-more-btn");

  if (loadMoreBtn) {
    loadMoreBtn.addEventListener("click", function () {
      // Replace with actual AJAX loading in a real application
      loadMoreBtn.classList.add("loading");
      loadMoreBtn.innerHTML =
        '<span>Loading...</span><i class="fas fa-spinner fa-spin"></i>';

      // Simulate loading delay (would be an AJAX call in real app)
      setTimeout(() => {
        // Simulate no more items to load
        loadMoreBtn.innerHTML =
          '<span>No More Projects</span><i class="fas fa-check-circle"></i>';
        loadMoreBtn.classList.remove("loading");
        loadMoreBtn.classList.add("disabled");
        loadMoreBtn.disabled = true;
      }, 1500);
    });
  }

  // Project image hover effect
  const projectImages = document.querySelectorAll(".project-image");

  if (projectImages.length > 0) {
    projectImages.forEach((image) => {
      image.addEventListener("mouseenter", function () {
        this.querySelector(".project-overlay").style.opacity = "1";
      });

      image.addEventListener("mouseleave", function () {
        this.querySelector(".project-overlay").style.opacity = "0";
      });
    });
  }

  // Sticky Header on Scroll
  const header = document.querySelector(".header");

  if (header) {
    window.addEventListener("scroll", function () {
      if (window.scrollY > 50) {
        header.classList.add("sticky");
      } else {
        header.classList.remove("sticky");
      }
    });
  }

  // Smooth Scrolling for Anchor Links
  document.querySelectorAll('a[href^="#"]').forEach((anchor) => {
    anchor.addEventListener("click", function (e) {
      if (this.getAttribute("href") !== "#") {
        e.preventDefault();

        const targetId = this.getAttribute("href");
        const targetElement = document.querySelector(targetId);

        if (targetElement) {
          const headerHeight = document.querySelector(".header").offsetHeight;
          const targetPosition =
            targetElement.getBoundingClientRect().top +
            window.pageYOffset -
            headerHeight;

          window.scrollTo({
            top: targetPosition,
            behavior: "smooth",
          });
        }
      }
    });
  });

  // Add CSS for project card animations
  const style = document.createElement("style");
  style.textContent = `
        .project-card {
            opacity: 0;
            transform: translateY(20px);
            transition: opacity 0.5s ease, transform 0.5s ease, box-shadow 0.3s ease;
        }
        
        .project-card.visible {
            opacity: 1;
            transform: translateY(0);
        }
        
        @media (max-width: 768px) {
            .filter-controls {
                flex-wrap: wrap;
                justify-content: center;
            }
            
            .filter-btn {
                margin-bottom: 8px;
            }
        }
    `;
  document.head.appendChild(style);

  // Make all project cards initially visible
  setTimeout(() => {
    projectCards.forEach((card) => {
      card.classList.add("visible");
    });
  }, 100);

  // Publication items animation on scroll
  const publicationItems = document.querySelectorAll(".publication-item");

  if (publicationItems.length > 0) {
    const publicationObserver = new IntersectionObserver(
      (entries) => {
        entries.forEach((entry, index) => {
          if (entry.isIntersecting) {
            setTimeout(() => {
              entry.target.classList.add("visible");
            }, index * 100);
            publicationObserver.unobserve(entry.target);
          }
        });
      },
      { threshold: 0.1 }
    );

    publicationItems.forEach((item) => {
      publicationObserver.observe(item);
      // Add needed CSS for animation
      item.style.opacity = "0";
      item.style.transform = "translateY(20px)";
      item.style.transition = "opacity 0.5s ease, transform 0.5s ease";
    });
  }

  // Add CSS for publication item animations
  const pubStyle = document.createElement("style");
  pubStyle.textContent = `
        .publication-item.visible {
            opacity: 1 !important;
            transform: translateY(0) !important;
        }
    `;
  document.head.appendChild(pubStyle);

  // Enhanced Project Loading
  // const loadMoreBtn = document.querySelector('.load-more-btn');
  const projectsGrid = document.querySelector(".projects-grid");
  let currentPage = 1;

  if (loadMoreBtn) {
    loadMoreBtn.addEventListener("click", async function () {
      try {
        loadMoreBtn.classList.add("loading");
        const loadingOverlay = document.querySelector(".loading-overlay");
        loadingOverlay.classList.add("active");

        // Simulate API call (replace with actual API endpoint)
        const response = await new Promise((resolve) =>
          setTimeout(() => {
            resolve({
              hasMore: currentPage < 3,
              projects: [], // Add mock project data here
            });
          }, 1000)
        );

        if (!response.hasMore) {
          loadMoreBtn.innerHTML =
            '<span>All Projects Loaded</span><i class="fas fa-check"></i>';
          loadMoreBtn.disabled = true;
        } else {
          currentPage++;
        }
      } catch (error) {
        console.error("Error loading projects:", error);
        loadMoreBtn.innerHTML =
          '<span>Error Loading Projects</span><i class="fas fa-exclamation-circle"></i>';
      } finally {
        loadMoreBtn.classList.remove("loading");
        document.querySelector(".loading-overlay").classList.remove("active");
      }
    });
  }

  // Enhanced Filter Functionality
  const filterProjects = (category) => {
    const projects = document.querySelectorAll(".project-card");

    projects.forEach((project) => {
      const projectCategory = project.dataset.category;
      project.classList.remove("fade-in");

      if (category === "all" || projectCategory === category) {
        project.style.display = "block";
        setTimeout(() => project.classList.add("fade-in"), 10);
      } else {
        project.style.display = "none";
      }
    });
  };

  // Initialize counters with Intersection Observer
  const initCounters = () => {
    const counters = document.querySelectorAll(".counter");
    const options = {
      threshold: 0.5,
      rootMargin: "0px",
    };

    const observer = new IntersectionObserver((entries) => {
      entries.forEach((entry) => {
        if (entry.isIntersecting && !entry.target.dataset.counted) {
          const target = parseInt(
            entry.target.dataset.target || entry.target.innerText
          );
          animateCounter(entry.target, target);
          entry.target.dataset.counted = true;
        }
      });
    }, options);

    counters.forEach((counter) => observer.observe(counter));
  };

  // Call initialization functions
  initCounters();
});
