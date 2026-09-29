(() => {
  'use strict';
  // Navigation, focus behaviour and the preview contact form. No dependency on animation libraries.
  document.documentElement.classList.remove('no-js');

  const header = document.querySelector('.site-header');
  const desktopWrap = document.querySelector('[data-services-menu]');
  const desktopButton = document.querySelector('[data-services-toggle]');
  const desktopMenu = document.getElementById('desktop-services-menu');
  const mobileButton = document.querySelector('[data-mobile-toggle]');
  const mobileMenu = document.getElementById('mobile-navigation');
  const mobileServicesButton = document.querySelector('[data-mobile-services-toggle]');
  const mobileServicesMenu = document.getElementById('mobile-services-menu');
  const desktopQuery = window.matchMedia('(min-width: 961px)');

  const setExpanded = (button, menu, open) => {
    if (!button || !menu) return;
    button.setAttribute('aria-expanded', String(open));
    menu.hidden = !open;
  };
  const closeDesktop = (returnFocus = false) => {
    if (!desktopMenu || desktopMenu.hidden) return;
    setExpanded(desktopButton, desktopMenu, false);
    if (returnFocus) desktopButton.focus();
  };
  const setMobile = open => {
    setExpanded(mobileButton, mobileMenu, open);
    document.body.classList.toggle('menu-open', open);
    mobileButton?.setAttribute('aria-label', open ? 'Close navigation' : 'Open navigation');
    if (!open) closeMobileServices();
  };
  const closeMobileServices = () => {
    setExpanded(mobileServicesButton, mobileServicesMenu, false);
    if (mobileServicesButton) mobileServicesButton.textContent = '+';
  };

  desktopButton?.addEventListener('click', () => setExpanded(desktopButton, desktopMenu, desktopMenu.hidden));
  mobileButton?.addEventListener('click', () => setMobile(mobileMenu.hidden));
  mobileServicesButton?.addEventListener('click', () => {
    const open = mobileServicesMenu.hidden;
    setExpanded(mobileServicesButton, mobileServicesMenu, open);
    mobileServicesButton.textContent = open ? '−' : '+';
  });

  document.addEventListener('click', event => {
    if (!event.target.closest('[data-services-menu]')) closeDesktop();
  });
  // Open on hover for mouse users; the toggle button and keyboard behaviour are unchanged.
  if (desktopWrap && window.matchMedia('(hover: hover)').matches) {
    let closeTimer;
    desktopWrap.addEventListener('mouseenter', () => {
      clearTimeout(closeTimer);
      if (desktopQuery.matches) setExpanded(desktopButton, desktopMenu, true);
    });
    desktopWrap.addEventListener('mouseleave', () => {
      closeTimer = setTimeout(() => closeDesktop(), 180);
    });
  }
  // Tabbing out of the dropdown closes it so it never traps or hides focus.
  desktopWrap?.addEventListener('focusout', event => {
    if (event.relatedTarget && !desktopWrap.contains(event.relatedTarget)) closeDesktop();
  });
  // Mobile menu links close the menu so it is never left open on the next view.
  mobileMenu?.addEventListener('click', event => {
    if (event.target.closest('a')) setMobile(false);
  });
  document.addEventListener('keydown', event => {
    if (event.key !== 'Escape') return;
    if (desktopMenu && !desktopMenu.hidden) {
      closeDesktop(desktopWrap.contains(document.activeElement));
    }
    if (mobileMenu && !mobileMenu.hidden) {
      const inside = mobileMenu.contains(document.activeElement) || mobileButton === document.activeElement;
      setMobile(false);
      if (inside) mobileButton.focus();
    }
  });
  desktopQuery.addEventListener('change', event => {
    if (event.matches) setMobile(false);
    else closeDesktop();
  });

  const onScroll = () => header?.classList.toggle('scrolled', window.scrollY > 12);
  onScroll();
  window.addEventListener('scroll', onScroll, { passive: true });

  // Contact form preview: never submits, sends or stores anything.
  const form = document.querySelector('[data-preview-form]');
  if (form) {
    const status = form.querySelector('.form-status');
    const showPreview = () => {
      form.classList.add('was-checked');
      const invalid = form.querySelector(':invalid');
      if (status) {
        status.textContent = invalid
          ? 'Please complete the required fields. Note: this is a preview and nothing is sent.'
          : 'Preview only. Your message has not been sent or saved.';
      }
      invalid?.focus();
    };
    form.addEventListener('submit', event => { event.preventDefault(); showPreview(); });
    form.querySelector('[data-preview-submit]')?.addEventListener('click', showPreview);
  }
})();
