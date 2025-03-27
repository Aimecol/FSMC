document.addEventListener("DOMContentLoaded", function () {
  // Initialize AOS Animation Library
  AOS.init({
    duration: 500,
    easing: "ease-in-out",
    once: true,
    mirror: false,
  });

  // Mobile Navigation Toggle
  const navToggle = document.getElementById("navToggle");
  const navigation = document.getElementById("navigation");

  if (navToggle && navigation) {
    navToggle.addEventListener("click", function () {
      navigation.classList.toggle("active");

      // Toggle between hamburger and close icon
      const icon = navToggle.querySelector("i");
      if (icon.classList.contains("fa-bars")) {
        icon.classList.remove("fa-bars");
        icon.classList.add("fa-times");
      } else {
        icon.classList.remove("fa-times");
        icon.classList.add("fa-bars");
      }
    });
  }

  // Enquiry Form Submission
  const enquiryForm = document.getElementById("enquiryForm");
  const successModal = document.getElementById("successModal");

  if (enquiryForm) {
    enquiryForm.addEventListener("submit", function (e) {
      e.preventDefault();

      // Form validation
      const requiredFields = enquiryForm.querySelectorAll("[required]");
      let isValid = true;

      requiredFields.forEach((field) => {
        if (!field.value.trim()) {
          isValid = false;
          field.classList.add("error");

          // Add shake animation to highlight error
          field.animate(
            [
              { transform: "translateX(0)" },
              { transform: "translateX(-10px)" },
              { transform: "translateX(10px)" },
              { transform: "translateX(0)" },
            ],
            {
              duration: 400,
              iterations: 1,
            }
          );

          // Add error message if it doesn't exist
          const errorMessage =
            field.parentElement.querySelector(".error-message");
          if (!errorMessage) {
            const message = document.createElement("div");
            message.className = "error-message";
            message.textContent = "This field is required";
            field.parentElement.appendChild(message);
          }
        } else {
          // Remove error class and message if field is valid
          field.classList.remove("error");
          const errorMessage =
            field.parentElement.querySelector(".error-message");
          if (errorMessage) {
            errorMessage.remove();
          }
        }
      });

      // If form is valid, submit and show success modal
      if (isValid) {
        // Simulate form submission with loading state
        const submitBtn = enquiryForm.querySelector(".submit-btn");
        const originalText = submitBtn.innerHTML;

        submitBtn.disabled = true;
        submitBtn.innerHTML =
          '<i class="fas fa-spinner fa-spin"></i> Submitting...';

        // Simulate server request with timeout
        setTimeout(() => {
          // Reset form
          enquiryForm.reset();

          // Show success modal
          if (successModal) {
            successModal.classList.add("active");
          }

          // Reset button
          submitBtn.innerHTML = originalText;
          submitBtn.disabled = false;
        }, 1500);
      }
    });
  }

  // Success Modal Close Button
  const closeModalBtn = document.querySelector(".close-modal-btn");

  if (closeModalBtn && successModal) {
    closeModalBtn.addEventListener("click", function () {
      successModal.classList.remove("active");
    });

    // Close modal when clicking outside
    successModal.addEventListener("click", function (e) {
      if (e.target === successModal) {
        successModal.classList.remove("active");
      }
    });
  }

  // Related Products Slider
  const productsSlider = document.querySelector(".products-slider");
  const prevBtn = document.getElementById("prevProduct");
  const nextBtn = document.getElementById("nextProduct");

  if (productsSlider && prevBtn && nextBtn) {
    const cardWidth = 280 + 32; // card width + gap
    const visibleWidth = productsSlider.clientWidth;
    const scrollAmount = cardWidth * 2; // Scroll 2 cards at a time

    // Initial state
    updateSliderButtons();

    prevBtn.addEventListener("click", function () {
      productsSlider.scrollBy({
        left: -scrollAmount,
        behavior: "smooth",
      });

      setTimeout(updateSliderButtons, 500);
    });

    nextBtn.addEventListener("click", function () {
      productsSlider.scrollBy({
        left: scrollAmount,
        behavior: "smooth",
      });

      setTimeout(updateSliderButtons, 500);
    });

    productsSlider.addEventListener("scroll", function () {
      updateSliderButtons();
    });

    function updateSliderButtons() {
      const scrollPosition = productsSlider.scrollLeft;
      const maxScrollLeft =
        productsSlider.scrollWidth - productsSlider.clientWidth;

      // Enable/disable buttons based on scroll position
      prevBtn.disabled = scrollPosition <= 0;
      nextBtn.disabled = scrollPosition >= maxScrollLeft - 5; // 5px tolerance
    }

    // Update on resize
    window.addEventListener("resize", function () {
      updateSliderButtons();
    });
  }

  // Sticky Header on Scroll
  const header = document.querySelector(".header");
  let lastScrollTop = 0;

  if (header) {
    window.addEventListener("scroll", function () {
      const scrollTop =
        window.pageYOffset || document.documentElement.scrollTop;

      if (scrollTop > 100) {
        header.classList.add("sticky");

        // Hide on scroll down, show on scroll up
        if (scrollTop > lastScrollTop) {
          header.style.transform = "translateY(-100%)";
        } else {
          header.style.transform = "translateY(0)";
        }
      } else {
        header.classList.remove("sticky");
        header.style.transform = "translateY(0)";
      }

      lastScrollTop = scrollTop;
    });
  }

  // Form validation feedback styles
  // Add these to CSS with JavaScript instead of modifying CSS file
  const style = document.createElement("style");
  style.textContent = `
    .form-group input.error,
    .form-group textarea.error,
    .form-group select.error {
      border-color: var(--danger-color);
      background-color: rgba(231, 76, 60, 0.05);
    }
    
    .error-message {
      color: var(--danger-color);
      font-size: 0.85rem;
      margin-top: 0.4rem;
      animation: fadeIn 0.3s ease;
    }
    
    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(-5px); }
      to { opacity: 1; transform: translateY(0); }
    }
    
    .field-focused label {
      color: var(--primary-color);
      font-weight: 600;
      transition: color 0.3s ease;
    }
    
    input.focused, select.focused, textarea.focused {
      border-color: var(--primary-light);
      box-shadow: 0 0 0 3px rgba(46, 134, 193, 0.2);
    }
    
    .header.sticky {
      position: fixed;
      top: 0;
      left: 0;
      right: 0;
      z-index: 100;
      background-color: white;
      box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
      transition: transform 0.3s ease;
    }
    
    /* Make the products slider work properly */
    .products-slider {
      display: flex;
      flex-wrap: nowrap;
      gap: 1.5rem;
      transition: transform 0.5s ease;
    }
    
    .products-slider .product-card {
      flex: 0 0 auto;
      width: calc(25% - 1.125rem);
    }
    
    @media (max-width: 992px) {
      .products-slider .product-card {
        width: calc(33.333% - 1rem);
      }
    }
    
    @media (max-width: 768px) {
      .products-slider .product-card {
        width: calc(50% - 0.75rem);
      }
    }
    
    @media (max-width: 576px) {
      .products-slider .product-card {
        width: 100%;
      }
    }
  `;

  document.head.appendChild(style);

  // Add subtle animations to UI elements
  const addAnimationToElements = () => {
    // Animate spec items in sidebar
    const specItems = document.querySelectorAll(".key-specs .spec-item");
    specItems.forEach((item, index) => {
      item.style.opacity = "0";
      item.style.transform = "translateX(-10px)";
      item.style.transition = "opacity 0.5s ease, transform 0.5s ease";

      setTimeout(() => {
        item.style.opacity = "1";
        item.style.transform = "translateX(0)";
      }, 300 + index * 100);
    });

    // Animate benefits list
    const benefitItems = document.querySelectorAll(".benefits-list li");
    benefitItems.forEach((item, index) => {
      item.style.opacity = "0";
      item.style.transform = "translateY(10px)";
      item.style.transition = "opacity 0.5s ease, transform 0.5s ease";

      setTimeout(() => {
        item.style.opacity = "1";
        item.style.transform = "translateY(0)";
      }, 500 + index * 150);
    });
  };

  // Run animations after page loads
  addAnimationToElements();
});
