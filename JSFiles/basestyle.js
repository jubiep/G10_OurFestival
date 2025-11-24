document.addEventListener("DOMContentLoaded", function() {

  // Navbar Toggle
  const navbarToggle = document.getElementById("navbarToggle");
  const navbarMenu = document.getElementById("navbarMenu");

  if (navbarToggle && navbarMenu) {
    navbarToggle.addEventListener("click", function() {
      navbarMenu.classList.toggle("show");
    });
  }

  // Tooltip
  const tooltipTriggerList = [].slice.call(
    document.querySelectorAll('[data-bs-toggle="tooltip"]')
  );

  tooltipTriggerList.map(function (tooltipTriggerEl) {
    return new bootstrap.Tooltip(tooltipTriggerEl);
  });

});

// Theme Toggle
const toggleSwitch = document.getElementById("apperance-toggle");
const body = document.body;
const STORAGE_KEY = "theme"; // localStorage

function toggleTheme() {
    // Switch between dark and light
    const isCurrentlyDark = localStorage.getItem(STORAGE_KEY);

    // Toggle the theme >> save to localStorage
    const newTheme = isCurrentlyDark === "dark" ? "light" : "dark";
    localStorage.setItem(STORAGE_KEY, newTheme);
    loadTheme();
}

function loadTheme() {
  const savedTheme = localStorage.getItem(STORAGE_KEY);
  
  // Match the system theme
  const preferredDark = window.matchMedia && window.matchMedia("(prefers-color-scheme: dark)").matches;

  if (preferredDark && savedTheme !== "light") {
    body.classList.add("dark-mode");
  }

  // Check localStorage key >> set up to key
  if (savedTheme === "dark") {
    body.classList.add("dark-mode");
    return;
  } else {
    body.classList.remove("dark-mode");
    return;
  }

}

loadTheme();

toggleSwitch.addEventListener("click", toggleTheme);