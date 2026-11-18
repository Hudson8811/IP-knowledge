(() => {
  const toggle = document.querySelector('[data-article-menu-toggle]');
  const menu = document.querySelector('[data-article-menu]');
  const close = document.querySelector('[data-article-menu-close]');
  const backdrop = document.querySelector('[data-article-menu-backdrop]');

  if (!toggle || !menu || !close || !backdrop) {
    return;
  }

  const setMenuState = (isOpen) => {
    menu.classList.toggle('article-menu--open', isOpen);
    backdrop.classList.toggle('article-menu-backdrop--visible', isOpen);
    backdrop.hidden = !isOpen;
    toggle.setAttribute('aria-expanded', String(isOpen));
    document.body.classList.toggle('article-menu-is-open', isOpen);

    if (isOpen) {
      close.focus();
    } else {
      toggle.focus();
    }
  };

  toggle.addEventListener('click', () => {
    setMenuState(!menu.classList.contains('article-menu--open'));
  });

  close.addEventListener('click', () => setMenuState(false));
  backdrop.addEventListener('click', () => setMenuState(false));

  document.addEventListener('keydown', (event) => {
    if ('Escape' === event.key && menu.classList.contains('article-menu--open')) {
      setMenuState(false);
    }
  });
})();
