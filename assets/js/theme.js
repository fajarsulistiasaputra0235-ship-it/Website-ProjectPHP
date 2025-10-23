// assets/js/theme.js
(function () {
  const toggle = document.getElementById("themeToggle");
  const icon = document.getElementById("themeIcon");
  const saved = localStorage.getItem("dc_theme") || "light";
  function apply(t) {
    document.body.setAttribute("data-theme", t);
    if (t === "dark") icon.className = "bx bx-sun";
    else icon.className = "bx bx-moon";
    localStorage.setItem("dc_theme", t);
    // update some class-friendly colors
    if (t === "dark") {
      document.documentElement.classList.add("dark-mode");
    } else {
      document.documentElement.classList.remove("dark-mode");
    }
  }
  apply(saved);
  if (toggle)
    toggle.addEventListener("click", function () {
      const cur =
        document.body.getAttribute("data-theme") === "dark" ? "dark" : "light";
      apply(cur === "dark" ? "light" : "dark");
    });
})();
