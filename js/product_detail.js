document.addEventListener("DOMContentLoaded", function () {
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

  // Product Gallery - Thumbnail Functionality
  const thumbnails = document.querySelectorAll(".thumbnail");
  const mainImage = document.getElementById("mainImage");

  if (thumbnails.length > 0 && mainImage) {
    thumbnails.forEach((thumb) => {
      thumb.addEventListener("click", function () {
        // Remove active class from all thumbnails
        thumbnails.forEach((t) => t.classList.remove("active"));

        // Add active class to clicked thumbnail
        this.classList.add("active");

        // Update main image
        const newImageSrc = this.getAttribute("data-image");

        // Animate the image change
        mainImage.style.opacity = "0";

        setTimeout(() => {
          mainImage.src = newImageSrc;
          mainImage.style.opacity = "1";
        }, 300);
      });
    });
  }

  // Tabs Functionality
  const tabButtons = document.querySelectorAll(".tab-btn");
  const tabContents = document.querySelectorAll(".tab-content");

  if (tabButtons.length > 0) {
    tabButtons.forEach((button) => {
      button.addEventListener("click", function () {
        const tabId = this.getAttribute("data-tab");

        // Remove active class from all buttons and contents
        tabButtons.forEach((btn) => btn.classList.remove("active"));
        tabContents.forEach((content) => content.classList.remove("active"));

        // Add active class to current button and content
        this.classList.add("active");
        document.getElementById(tabId).classList.add("active");
      });
    });
  }

  // FAQ Accordion
  const faqItems = document.querySelectorAll(".faq-item");

  if (faqItems.length > 0) {
    faqItems.forEach((item) => {
      const question = item.querySelector(".faq-question");

      question.addEventListener("click", function () {
        const isActive = item.classList.contains("active");

        // Close all FAQs
        faqItems.forEach((faq) => faq.classList.remove("active"));

        // If it wasn't active before, make it active now
        if (!isActive) {
          item.classList.add("active");
        }
      });
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
  // Add sticky header behavior
  const header = document.querySelector(".header");

  if (header) {
    window.addEventListener("scroll", function () {
      if (window.scrollY > 50) {
        header.classList.add("sticky");
        header.style.boxShadow = "0 5px 15px rgba(0, 0, 0, 0.1)";
      } else {
        header.classList.remove("sticky");
        header.style.boxShadow = "0 2px 10px rgba(0, 0, 0, 0.1)";
      }
    });
  }

  // Initialize product details page
  function initProductDetail() {
    // Make the first tab active by default
    const firstTab = document.querySelector(".tab-btn");
    if (firstTab && !firstTab.classList.contains("active")) {
      firstTab.click();
    }

    // Make the first FAQ item active by default
    const firstFaq = document.querySelector(".faq-item");
    if (firstFaq) {
      firstFaq.classList.add("active");
    }

    // Add fade-in animations for page sections
    const sections = document.querySelectorAll("section");
    if (sections.length > 0) {
      sections.forEach((section, index) => {
        if (!section.hasAttribute("data-aos")) {
          section.setAttribute("data-aos", "fade-up");
          section.setAttribute("data-aos-delay", (index * 100).toString());
        }
      });
    }
  }

  // Run initialization
  initProductDetail();
});
