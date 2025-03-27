document.addEventListener("DOMContentLoaded", function () {
  const navToggle = document.getElementById("navToggle");
  const navigation = document.getElementById("navigation");

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

  // Close menu when clicking outside
  document.addEventListener("click", function (event) {
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
  });

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
});
