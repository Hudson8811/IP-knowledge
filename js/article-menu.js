const menuToggle = document.querySelector("[data-article-menu-toggle]");
const articleMenu = document.querySelector("[data-article-menu]");
const menuClose = document.querySelector("[data-article-menu-close]");
const menuBackdrop = document.querySelector("[data-article-menu-backdrop]");
const mobileMenuQuery = window.matchMedia("(max-width: 900px)");

if (menuToggle && articleMenu && menuClose && menuBackdrop) {
  const setMenuState = (isOpen, returnFocus = true) => {
    menuToggle.setAttribute("aria-expanded", String(isOpen));
    articleMenu.classList.toggle("article-menu--open", isOpen);
    articleMenu.setAttribute("aria-hidden", String(mobileMenuQuery.matches && !isOpen));
    menuBackdrop.classList.toggle("article-menu-backdrop--visible", isOpen);
    menuBackdrop.hidden = !isOpen;

    if (isOpen) {
      menuClose.focus();
    } else if (returnFocus && mobileMenuQuery.matches) {
      menuToggle.focus();
    }
  };

  const closeMenu = (returnFocus = true) => setMenuState(false, returnFocus);

  menuToggle.addEventListener("click", () => setMenuState(true));
  menuClose.addEventListener("click", () => closeMenu());
  menuBackdrop.addEventListener("click", () => closeMenu());

  articleMenu.querySelectorAll("a").forEach((link) => {
    link.addEventListener("click", () => closeMenu(false));
  });

  document.addEventListener("keydown", (event) => {
    if (event.key === "Escape" && articleMenu.classList.contains("article-menu--open")) {
      closeMenu();
    }
  });

  if (typeof mobileMenuQuery.addEventListener === "function") {
    mobileMenuQuery.addEventListener("change", () => closeMenu(false));
  } else {
    mobileMenuQuery.addListener(() => closeMenu(false));
  }

  closeMenu(false);
}
