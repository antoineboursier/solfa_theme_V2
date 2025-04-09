////////////// MENU //////////////

document.addEventListener("DOMContentLoaded", function () {
  const burgerMenu = document.getElementById("burger-menu");
  const closeMenuBtn = document.getElementById("close-menu");
  const menu = document.getElementById("main-nav");

  function openMenu() {
    burgerMenu.setAttribute("aria-expanded", "true");
    menu.setAttribute("aria-hidden", "false");
    menu.classList.remove("hidden");
  }

  function closeMenu() {
    burgerMenu.setAttribute("aria-expanded", "false");
    menu.setAttribute("aria-hidden", "true");
    menu.classList.add("hidden");
  }

  burgerMenu.addEventListener("click", openMenu);

  closeMenuBtn.addEventListener("click", closeMenu);
});

////////////// MENU FLOTTANT //////////////

window.addEventListener("scroll", function () {
  const header = document.querySelector("header");
  if (window.scrollY > 0) {
    header.classList.add("scrolled");
  } else {
    header.classList.remove("scrolled");
  }
});

////////////// SEPARTOR MENU //////////////

document.addEventListener("DOMContentLoaded", function () {
  const menuLinks = document.querySelectorAll(".menu-item a");

  menuLinks.forEach((link) => {
    if (link.textContent.trim() === "Separator") {
      const parentLi = link.closest("li");
      const separator = document.createElement("div");
      separator.classList.add("separator");
      separator.setAttribute("aria-hidden", "true");
      parentLi.replaceWith(separator);
    }
  });
});
