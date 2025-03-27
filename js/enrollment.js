document.addEventListener("DOMContentLoaded", function () {
  // Cache DOM Elements
  const enrollmentForm = document.getElementById("enrollmentForm");
  const formSteps = document.querySelectorAll(".form-step");
  const progressSteps = document.querySelectorAll(".progress-step");
  const progressConnectors = document.querySelectorAll(".progress-connector");
  const nextButtons = document.querySelectorAll(".btn-next");
  const prevButtons = document.querySelectorAll(".btn-prev");
  const completeEnrollmentBtn = document.getElementById("completeEnrollment");
  const termsLink = document.querySelectorAll(".terms-link");
  const termsModal = document.getElementById("termsModal");
  const closeTermsModal = document.getElementById("closeTermsModal");
  const acceptTermsBtn = document.getElementById("acceptTerms");
  const termsCheckbox = document.getElementById("terms");
  const downloadReceiptBtn = document.getElementById("downloadReceipt");

  // Course related elements
  const courseCategory = document.getElementById("courseCategory");
  const courseSelect = document.getElementById("courseSelect");
  const sessionDate = document.getElementById("sessionDate");
  const attendanceMode = document.getElementById("attendanceMode");
  const courseDetails = document.getElementById("courseDetails");
  const groupDiscountCheckbox = document.getElementById("groupDiscount");

  // Course data (would typically come from a database)
  const courses = {
    surveying: [
      {
        id: "total-station",
        name: "Total Station Operation & Survey Data Processing",
        description:
          "Learn to operate total stations and process survey data efficiently for various applications.",
        price: 250000,
        duration: "5 days",
        students: 12,
        language: "English, French",
        level: "Beginner",
        image: "../images/course-total-station.jpg",
        features: [
          "Hands-on practice",
          "Field exercises",
          "Data processing",
          "Certificate",
          "Job placement assistance",
        ],
        dates: ["2025-03-10", "2025-04-15", "2025-05-20"],
      },
      {
        id: "rtk-gnss",
        name: "RTK GNSS Surveying Systems",
        description:
          "Master the use of Real-Time Kinematic (RTK) GNSS systems for high-precision surveying.",
        price: 300000,
        duration: "4 days",
        students: 10,
        language: "English",
        level: "Intermediate",
        image: "../images/course-gnss.jpg",
        features: [
          "Field work",
          "Base & rover setup",
          "Network RTK",
          "Troubleshooting",
          "Post-processing",
        ],
        dates: ["2025-03-18", "2025-04-22", "2025-06-10"],
      },
    ],
    software: [
      {
        id: "autocad-civil-3d",
        name: "AutoCAD Civil 3D for Land Surveyors",
        description:
          "Comprehensive training on using AutoCAD Civil 3D for land surveying and civil engineering projects.",
        price: 280000,
        duration: "10 days",
        students: 15,
        language: "English",
        level: "Intermediate",
        image: "../images/course-civil-3d.jpg",
        features: [
          "Project setup",
          "Surface modeling",
          "Corridor design",
          "Quantity takeoff",
          "Plan production",
        ],
        dates: ["2025-03-05", "2025-04-08", "2025-05-12"],
      },
    ],
    gis: [
      {
        id: "qgis-fundamentals",
        name: "QGIS Fundamentals for Mapping Professionals",
        description:
          "Learn to use QGIS for creating, editing, visualizing, analyzing and publishing geospatial information.",
        price: 220000,
        duration: "5 days",
        students: 15,
        language: "English, French",
        level: "Beginner",
        image: "../images/course-qgis.jpg",
        features: [
          "Data management",
          "Spatial analysis",
          "Map design",
          "Plugins",
          "Automation",
        ],
        dates: ["2025-03-24", "2025-04-28", "2025-06-02"],
      },
    ],
    advanced: [
      {
        id: "uav-mapping",
        name: "UAV/Drone Mapping & Photogrammetry",
        description:
          "Comprehensive training on drone operation, data collection, and processing for mapping applications.",
        price: 350000,
        duration: "7 days",
        students: 8,
        language: "English",
        level: "Advanced",
        image: "../images/course-drone.jpg",
        features: [
          "Flight planning",
          "Mission execution",
          "Data processing",
          "Orthomosaic creation",
          "DTM/DSM generation",
        ],
        dates: ["2025-03-17", "2025-05-05", "2025-06-14"],
      },
    ],
  };

  // Initialize form elements and functionality
  function initializeForm() {
    // Show active form step
    updateFormStep(1);

    // Handle next button clicks
    nextButtons.forEach((button) => {
      button.addEventListener("click", function () {
        const nextStep = parseInt(this.getAttribute("data-next"));
        if (validateStep(nextStep - 1)) {
          updateFormStep(nextStep);
        }
      });
    });

    // Handle previous button clicks
    prevButtons.forEach((button) => {
      button.addEventListener("click", function () {
        const prevStep = parseInt(this.getAttribute("data-prev"));
        updateFormStep(prevStep);
      });
    });

    // Handle course category change
    courseCategory.addEventListener("change", function () {
      const category = this.value;
      courseSelect.innerHTML = '<option value="">-- Select Course --</option>';
      courseSelect.disabled = !category;
      courseDetails.innerHTML = "";
      courseDetails.classList.remove("active");

      if (category) {
        const categoryCourses = courses[category] || [];
        categoryCourses.forEach((course) => {
          const option = document.createElement("option");
          option.value = course.id;
          option.textContent = course.name;
          courseSelect.appendChild(option);
        });

        // Show notification
        showNotification(
          "Courses loaded",
          `${categoryCourses.length} courses available in this category.`,
          "info"
        );
      }
    });

    // Handle course selection change
    courseSelect.addEventListener("change", function () {
      const courseId = this.value;
      const category = courseCategory.value;
      sessionDate.innerHTML = '<option value="">-- Select Date --</option>';
      sessionDate.disabled = !courseId;

      if (courseId && category) {
        const categoryCourses = courses[category] || [];
        const selectedCourse = categoryCourses.find(
          (course) => course.id === courseId
        );

        if (selectedCourse) {
          // Populate session dates
          selectedCourse.dates.forEach((date) => {
            const formattedDate = new Date(date).toLocaleDateString("en-US", {
              weekday: "long",
              year: "numeric",
              month: "long",
              day: "numeric",
            });

            const option = document.createElement("option");
            option.value = date;
            option.textContent = formattedDate;
            sessionDate.appendChild(option);
          });

          // Show course details
          updateCourseDetails(selectedCourse);
        }
      } else {
        courseDetails.classList.remove("active");
      }
    });

    // Handle session date change
    sessionDate.addEventListener("change", function () {
      const date = this.value;
      if (date) {
        // Could add functionality here like checking availability
        showNotification(
          "Date selected",
          "Your selected date has been saved.",
          "success"
        );
      }
    });

    // Handle group discount checkbox change
    groupDiscountCheckbox.addEventListener("change", function () {
      updateOrderSummary();
    });

    // Handle form submission
    completeEnrollmentBtn.addEventListener("click", function (e) {
      e.preventDefault();

    //   if (validateStep(3)) {
    //     // Show loading state
    //     this.disabled = true;
    //     this.innerHTML = '<span class="spinner"></span> Processing...';

    //     // Simulate server processing
    //     setTimeout(() => {
    //       // Prepare confirmation details
    //       prepareConfirmation();

          // Update form step to confirmation
          updateFormStep(4);

          // Reset button state
    //       this.disabled = false;
    //       this.innerHTML =
    //         'Complete Enrollment <i class="fas fa-check-circle"></i>';

    //       // Show success notification
    //       showNotification(
    //         "Enrollment successful",
    //         "Your course enrollment has been confirmed!",
    //         "success"
    //       );
    //     }, 2000);
    //   }
    });

    // Handle terms and conditions modal
    termsLink.forEach((link) => {
      link.addEventListener("click", function (e) {
        e.preventDefault();
        termsModal.classList.add("active");
      });
    });

    closeTermsModal.addEventListener("click", function () {
      termsModal.classList.remove("active");
    });

    acceptTermsBtn.addEventListener("click", function () {
      termsCheckbox.checked = true;
      termsModal.classList.remove("active");
    });

    // Handle download receipt button
    downloadReceiptBtn.addEventListener("click", function (e) {
      e.preventDefault();

      // Show notification before printing
      showNotification(
        "Preparing receipt",
        "Your receipt is being prepared for download.",
        "info"
      );

      // Simulate delay then print
      setTimeout(() => {
        window.print();
      }, 1000);
    });

    // Initialize modal close on outside click
    window.addEventListener("click", function (e) {
      if (e.target === termsModal) {
        termsModal.classList.remove("active");
      }
    });

    // Input validation on blur
    const requiredInputs = document.querySelectorAll(
      "input[required], select[required]"
    );
    requiredInputs.forEach((input) => {
      input.addEventListener("blur", function () {
        validateField(this);
      });
    });
  }

  // Update the current form step
  function updateFormStep(step) {
    // Hide all steps
    formSteps.forEach((formStep) => {
      formStep.classList.remove("active");
    });

    // Show the current step
    document.getElementById(`step${step}`).classList.add("active");

    // Update progress tracker
    progressSteps.forEach((progressStep, index) => {
      const stepNum = parseInt(progressStep.getAttribute("data-step"));

      if (stepNum < step) {
        progressStep.classList.add("completed");
        progressStep.classList.remove("active");
      } else if (stepNum === step) {
        progressStep.classList.add("active");
        progressStep.classList.remove("completed");
      } else {
        progressStep.classList.remove("active");
        progressStep.classList.remove("completed");
      }
    });

    // Update progress connectors
    progressConnectors.forEach((connector, index) => {
      if (index < step - 1) {
        connector.classList.add("active");
      } else {
        connector.classList.remove("active");
      }
    });

    // If on step 3 (payment), update order summary
    if (step === 3) {
      updateOrderSummary();
    }

    // Scroll to top of form
    window.scrollTo({
      top: document.querySelector(".progress-tracker").offsetTop - 100,
      behavior: "smooth",
    });
  }

  // Validate the current step's fields
  function validateStep(step) {
    let isValid = true;

    switch (step) {
      case 1:
        // Validate personal info fields
        const personalFields = [
          "firstName",
          "lastName",
          "email",
          "phone",
          "country",
        ];
        personalFields.forEach((field) => {
          const input = document.getElementById(field);
          if (!validateField(input)) {
            isValid = false;
          }
        });
        break;

      case 2:
        // Validate course selection fields
        const courseFields = [
          "courseCategory",
          "courseSelect",
          "sessionDate",
          "attendanceMode",
        ];
        courseFields.forEach((field) => {
          const input = document.getElementById(field);
          if (!validateField(input)) {
            isValid = false;
          }
        });
        break;

      case 3:
        // Check terms agreement
        if (!validateField(document.getElementById("terms"))) {
          isValid = false;
        }
        break;
    }

    return isValid;
  }

  // Validate an individual field
  function validateField(field) {
    if (!field || !field.required) return true;

    const errorMessage = field.nextElementSibling;
    let isValid = true;
    let message = "";

    // Clear previous error state
    field.classList.remove("error");
    if (errorMessage && errorMessage.className === "error-message") {
      errorMessage.style.display = "none";
    }

    // Check if empty
    if (!field.value.trim()) {
      isValid = false;
      message = "This field is required.";
    }
    // Check specific validations based on field type
    else if (
      field.type === "email" &&
      !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(field.value)
    ) {
      isValid = false;
      message = "Please enter a valid email address.";
    } else if (
      field.id === "phone" &&
      !/^\d{9,15}$/.test(field.value.replace(/[^0-9]/g, ""))
    ) {
      isValid = false;
      message = "Please enter a valid phone number.";
    }

    // If invalid, show error
    if (!isValid) {
      field.classList.add("error");
      if (errorMessage && errorMessage.className === "error-message") {
        errorMessage.textContent = message;
        errorMessage.style.display = "block";
      }

      // Focus on first invalid field
      if (document.querySelectorAll(".error").length === 1) {
        field.focus();
      }
    }

    return isValid;
  }

  // Update course details display
  function updateCourseDetails(course) {
    courseDetails.innerHTML = `
            <div class="course-info">
                <div class="course-image">
                    <img src="${
                      course.image || "../images/course-placeholder.jpg"
                    }" alt="${course.name}">
                </div>
                <div class="course-text">
                    <h3>${course.name}</h3>
                    <div class="course-meta">
                        <span><i class="fas fa-clock"></i> ${
                          course.duration
                        }</span>
                        <span><i class="fas fa-users"></i> Max ${
                          course.students
                        } students</span>
                        <span><i class="fas fa-globe"></i> ${
                          course.language
                        }</span>
                        <span><i class="fas fa-signal"></i> ${
                          course.level
                        }</span>
                    </div>
                    <p>${course.description}</p>
                </div>
            </div>
            <div class="course-price">
                <div class="price-tag">${formatCurrency(course.price)}</div>
            </div>
            <div class="course-features">
                <h4>Course Features</h4>
                <ul class="features-list">
                    ${course.features
                      .map(
                        (feature) =>
                          `<li><i class="fas fa-check-circle"></i> ${feature}</li>`
                      )
                      .join("")}
                </ul>
            </div>
        `;

    courseDetails.classList.add("active");
  }

  // Update order summary based on selected course
  function updateOrderSummary() {
    const courseId = courseSelect.value;
    const category = courseCategory.value;

    if (!courseId || !category) return;

    const categoryCourses = courses[category] || [];
    const selectedCourse = categoryCourses.find(
      (course) => course.id === courseId
    );

    if (selectedCourse) {
      // Get the elements
      const courseName = document.getElementById("summaryCourseName");
      const coursePrice = document.getElementById("summaryCoursePrice");
      const discountRow = document.getElementById("discountRow");
      const discountAmount = document.getElementById("discountAmount");
      const totalAmount = document.getElementById("totalAmount");

      // Calculate prices
      let price = selectedCourse.price;
      let discount = 0;

      if (groupDiscountCheckbox && groupDiscountCheckbox.checked) {
        discount = price * 0.15; // 15% discount for group enrollment
      }

      // Update the elements
      courseName.textContent = selectedCourse.name;
      coursePrice.textContent = formatCurrency(price);

      if (discount > 0) {
        discountRow.style.display = "flex";
        discountAmount.textContent = `- ${formatCurrency(discount)}`;
      } else {
        discountRow.style.display = "none";
      }

      totalAmount.textContent = formatCurrency(price - discount);
    }
  }

  // Prepare confirmation page
  function prepareConfirmation() {
    // Get selected course details
    const courseId = courseSelect.value;
    const category = courseCategory.value;
    const date = sessionDate.options[sessionDate.selectedIndex].text;
    const mode = attendanceMode.options[attendanceMode.selectedIndex].text;

    // Get personal info
    const firstName = document.getElementById("firstName").value;
    const lastName = document.getElementById("lastName").value;
    const email = document.getElementById("email").value;
    const phone = document.getElementById("phone").value;

    // Get selected course
    const categoryCourses = courses[category] || [];
    const selectedCourse = categoryCourses.find(
      (course) => course.id === courseId
    );

    // Calculate price
    let price = selectedCourse.price;
    let discount = 0;

    if (groupDiscountCheckbox && groupDiscountCheckbox.checked) {
      discount = price * 0.15; // 15% discount for group enrollment
    }

    // Update confirmation details
    document.getElementById(
      "confirmName"
    ).textContent = `${firstName} ${lastName}`;
    document.getElementById("confirmEmail").textContent = email;
    document.getElementById("confirmPhone").textContent = phone;
    document.getElementById("confirmCourse").textContent = selectedCourse.name;
    document.getElementById("confirmDate").textContent = date;
    document.getElementById("confirmMode").textContent = mode;
    document.getElementById("confirmTotal").textContent = formatCurrency(
      price - discount
    );

    // Set enrollment ID
    const enrollmentId = "FSM-" + Math.floor(100000 + Math.random() * 900000);
    document.getElementById("enrollmentId").textContent = enrollmentId;

    // Set confirmation date
    const today = new Date().toLocaleDateString("en-US", {
      year: "numeric",
      month: "long",
      day: "numeric",
    });
    document.getElementById("confirmDate").textContent = today;
  }

  // Show a notification
  function showNotification(title, message, type = "info") {
    // Remove any existing notifications
    const existingNotification = document.querySelector(".notification");
    if (existingNotification) {
      existingNotification.remove();
    }

    // Create notification element
    const notification = document.createElement("div");
    notification.className = `notification ${type}`;

    // Set icon based on notification type
    let icon = "info-circle";
    if (type === "success") icon = "check-circle";
    if (type === "error") icon = "exclamation-circle";
    if (type === "warning") icon = "exclamation-triangle";

    // Set notification content
    notification.innerHTML = `
            <div class="notification-icon">
                <i class="fas fa-${icon}"></i>
            </div>
            <div class="notification-content">
                <h4>${title}</h4>
                <p>${message}</p>
            </div>
        `;

    // Add to document
    document.body.appendChild(notification);

    // Show notification
    setTimeout(() => {
      notification.classList.add("active");
    }, 10);

    // Remove after timeout
    setTimeout(() => {
      notification.classList.remove("active");
      setTimeout(() => {
        notification.remove();
      }, 300);
    }, 5000);
  }

  // Format currency
  function formatCurrency(amount) {
    return "RWF " + amount.toLocaleString("en-US");
  }

  // Escape HTML to prevent XSS
  function escapeHTML(str) {
    return str
      .replace(/&/g, "&amp;")
      .replace(/</g, "&lt;")
      .replace(/>/g, "&gt;")
      .replace(/"/g, "&quot;")
      .replace(/'/g, "&#039;");
  }

  // Sticky Header
  window.addEventListener("scroll", function () {
    const progressTracker = document.querySelector(".progress-tracker");
    if (window.scrollY > 300) {
      progressTracker.classList.add("sticky");
    } else {
      progressTracker.classList.remove("sticky");
    }
  });

  // Add autocomplete for countries
  function setupCountryAutocomplete() {
    const countryInput = document.getElementById("country");

    if (!countryInput) return;

    const countries = [
      "Rwanda",
      "Uganda",
      "Kenya",
      "Tanzania",
      "Burundi",
      "Democratic Republic of Congo",
      "South Sudan",
      "Ethiopia",
      "United States",
      "United Kingdom",
      "Canada",
      "Australia",
      "Nigeria",
      "South Africa",
      "Ghana",
      "Egypt",
      "Morocco",
      "France",
      "Germany",
      "Italy",
      "Spain",
      "Japan",
      "China",
      "India",
    ];

    // Create datalist element
    const datalist = document.createElement("datalist");
    datalist.id = "countries";

    // Add options to datalist
    countries.forEach((country) => {
      const option = document.createElement("option");
      option.value = country;
      datalist.appendChild(option);
    });

    // Add datalist to document
    document.body.appendChild(datalist);

    // Set input attributes
    countryInput.setAttribute("list", "countries");
    countryInput.setAttribute("autocomplete", "off");
  }

  // Initialize all features
  function init() {
    initializeForm();
    setupCountryAutocomplete();
  }

  // Run initialization when DOM is loaded
  init();
});

// Initialize AOS (Animate on Scroll) library on window load to ensure all images are loaded
window.addEventListener("load", function () {
  // Refresh AOS
  AOS.refresh();

  // Add animation to form steps
  document.querySelectorAll(".form-step").forEach(function (step, index) {
    step.setAttribute("data-aos", "fade-up");
    step.setAttribute("data-aos-delay", (index * 100).toString());
  });

  // Add animation to sidebar sections
  document
    .querySelectorAll(".sidebar-section")
    .forEach(function (section, index) {
      section.setAttribute("data-aos", "fade-left");
      section.setAttribute("data-aos-delay", (index * 100 + 200).toString());
    });
});
