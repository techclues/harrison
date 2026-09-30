(() => {
  'use strict';
  // Pinned scroll section (Services overview): the panel stays put while images scroll past,
  // and the list item matching the centred image expands. Desktop only; below 901px every item
  // stays expanded. Without JS every item is visible, so nothing depends on this script.
  const section = document.querySelector('[data-pin-section]');
  if (!section) return;

  const desktop = window.matchMedia('(min-width: 901px)');
  const reduced = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
  const figures = [...section.querySelectorAll('[data-pin-figure]')];
  const items = [...section.querySelectorAll('[data-pin-item]')];
  let observer = null;
  let ticking = false;

  const setActive = index => {
    items.forEach((item, i) => {
      const on = i === index;
      item.classList.toggle('is-active', on);
      item.querySelector('.pin-title').setAttribute('aria-expanded', String(on));
      item.querySelector('.pin-body').inert = !on; // collapsed links must not be focusable
    });
    figures.forEach((fig, i) => fig.classList.toggle('is-active', i === index));
  };

  const onTitleClick = event => {
    const item = event.currentTarget.closest('[data-pin-item]');
    const index = items.indexOf(item);
    figures[index]?.scrollIntoView({ behavior: reduced ? 'auto' : 'smooth', block: 'center' });
    setActive(index);
  };

  const updateProgress = () => {
    ticking = false;
    const rect = section.getBoundingClientRect();
    const p = (window.innerHeight / 2 - rect.top) / rect.height;
    section.style.setProperty('--pin-p', Math.max(0, Math.min(1, p)).toFixed(3));
  };
  const onScroll = () => { if (!ticking) { ticking = true; requestAnimationFrame(updateProgress); } };

  const enable = () => {
    section.classList.add('pin-js');
    setActive(0);
    observer = new IntersectionObserver(entries => {
      entries.forEach(entry => { if (entry.isIntersecting) setActive(figures.indexOf(entry.target)); });
    }, { rootMargin: '-45% 0px -45% 0px' });
    figures.forEach(fig => observer.observe(fig));
    items.forEach(item => item.querySelector('.pin-title').addEventListener('click', onTitleClick));
    if (!reduced) { window.addEventListener('scroll', onScroll, { passive: true }); updateProgress(); }
  };

  const disable = () => {
    section.classList.remove('pin-js');
    observer?.disconnect();
    observer = null;
    window.removeEventListener('scroll', onScroll);
    items.forEach(item => {
      const title = item.querySelector('.pin-title');
      title.removeEventListener('click', onTitleClick);
      title.removeAttribute('aria-expanded');
      item.querySelector('.pin-body').inert = false;
      item.classList.remove('is-active');
    });
    figures.forEach(fig => fig.classList.remove('is-active'));
  };

  const sync = () => (desktop.matches ? enable() : disable());
  desktop.addEventListener('change', () => { disable(); sync(); });
  sync();
})();
