document.addEventListener("DOMContentLoaded", function () {
  // Initialize AOS (Animate on Scroll)
  AOS.init({
    duration: 800,
    easing: "ease-in-out",
    once: true,
    mirror: false,
  });

  // Course Category Tabs - Updated for dynamic content
  const categoryTabs = document.querySelectorAll(".tab-btn");
  const courseCards = document.querySelectorAll(".course-card");

  categoryTabs.forEach((tab) => {
    tab.addEventListener("click", () => {
      // Remove active class from all tabs
      categoryTabs.forEach((t) => t.classList.remove("active"));
      
      // Add active class to clicked tab
      tab.classList.add("active");

      // Get selected category
      const category = tab.getAttribute("data-category");
      
      // Show/hide course cards based on category
      courseCards.forEach((card) => {
        if (category === "all" || card.getAttribute("data-category") === category) {
          card.style.display = "block";
        } else {
          card.style.display = "none";
        }
      });
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
    if (testimonialTrack) {
      testimonialTrack.style.transform = `translateX(-${currentSlide * 100}%)`;
      
      // Update indicators
      indicators.forEach((indicator, index) => {
        indicator.classList.toggle("active", index === currentSlide);
      });
    }
  }

  if (prevBtn && nextBtn && totalSlides > 0) {
    prevBtn.addEventListener("click", () => {
      currentSlide = currentSlide === 0 ? totalSlides - 1 : currentSlide - 1;
      updateTestimonialSlider();
    });

    nextBtn.addEventListener("click", () => {
      currentSlide = currentSlide === totalSlides - 1 ? 0 : currentSlide + 1;
      updateTestimonialSlider();
    });

    // Auto-rotate testimonials every 6 seconds
    setInterval(() => {
      currentSlide = currentSlide === totalSlides - 1 ? 0 : currentSlide + 1;
      updateTestimonialSlider();
    }, 6000);
  }

  indicators.forEach((indicator) => {
    indicator.addEventListener("click", () => {
      currentSlide = parseInt(indicator.getAttribute("data-index"));
      updateTestimonialSlider();
    });
  });


  // FAQ Accordion
  const faqQuestions = document.querySelectorAll(".faq-question");

  faqQuestions.forEach((question) => {
    question.addEventListener("click", () => {
      const answer = question.nextElementSibling;
      const toggle = question.querySelector(".faq-toggle");

      if (answer && toggle) {
        // Close all other answers
        document.querySelectorAll(".faq-answer").forEach((item) => {
          if (item !== answer) {
            item.classList.remove("active");
            const prevToggle = item.previousElementSibling?.querySelector(".faq-toggle");
            if (prevToggle) prevToggle.classList.remove("active");
          }
        });

        // Toggle current answer
        answer.classList.toggle("active");
        toggle.classList.toggle("active");
      }
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
      }
    });
  });

  // Course Enrollment Button Animation
  const enrollButtons = document.querySelectorAll(".btn-enroll");

  enrollButtons.forEach((button) => {
    button.addEventListener("click", function (e) {
      // Add pulse animation class
      this.classList.add("btn-pulse");

      // Remove animation class after animation completes
      setTimeout(() => {
        this.classList.remove("btn-pulse");
      }, 300);
    });
  });

  // Add essential button pulse animation CSS
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
    `;

  document.head.appendChild(style);

  // Initialize animations and interactive elements
  console.log("Training page initialized");
});
