(() => {
  'use strict';
  // Site-wide scroll animations, applied automatically (no per-page markup):
  //  - headings, cards, lists, form fields and footer columns fade up in a staggered cascade;
  //  - photos reveal with a soft wipe and zoom, then drift slightly (parallax) as you scroll;
  //  - a thin gold progress bar tracks how far down the page you are.
  // Progressive enhancement: nothing is hidden unless this script runs, reduced motion switches it
  // all off, and each element returns to its normal styles once its entrance is done (so hover
  // effects are untouched). Animates `opacity`, `translate`, `scale` and `clip-path` only.
  const root = document.documentElement;
  if (window.matchMedia('(prefers-reduced-motion: reduce)').matches || !('IntersectionObserver' in window)) {
    root.classList.remove('fx-js');
    return;
  }

  // Parts of the site that already have their own motion are left alone.
  const SKIP = '.pin-section, .ribbon-band, .v-hero, .v-industries, .v-typewriter-heading, [data-scroll-lines], .visually-hidden, details:not([open]) *, .hp';

  const FADE = [
    // hero copy (entrance on load)
    '.detail-hero .hero-copy > *',
    // section headings and intros
    'main h2', '.section-heading-row > *', '.value-intro > *', '.v-section-intro > *',
    // article body
    '.detail-article > :is(h2, p, ul, address)', '.policy > *',
    // cards, panels and grid items
    '.related-card', '.audience-card', '.post-card', '.post-feature', '.review-card', '.principle-card',
    '.cta-card', '.aside-card', '.value-grid > article', '.v-serve-grid > article', '.v-faq-list > details',
    '.promise-list', '.who-intro > *',
    // closing call-to-action and footer
    '.cta-band .cta-inner > div', '.site-footer .footer-top > div', '.site-footer .footer-bottom',
    // forms
    '.contact-form > .field, .contact-form > .field-row, .contact-form > button',
  ];
  const FADE_SMALL = ['.check-list li', '.step-list li'];
  const MASK = ['.hero-art', '.manifesto-figure', '.who-photo', '.post-feature-art', '.post-card-art', '.detail-figure'];
  const PARALLAX = ['.hero-art', '.manifesto-figure', '.who-photo'];

  const tagged = [];
  const tag = (selectors, classes) => {
    document.querySelectorAll(selectors.join(',')).forEach(el => {
      if (el.dataset.fx || el.matches(SKIP) || el.closest(SKIP)) return;
      el.dataset.fx = '1';
      el.classList.add('fx', ...classes);
      tagged.push(el);
    });
  };
  tag(FADE, []);
  tag(FADE_SMALL, ['fx-sm']);
  tag(MASK, ['fx-mask']);        // masks win over the fade rule for the same element
  document.querySelectorAll('[data-reveal]').forEach(el => { if (!el.dataset.fx && !el.closest(SKIP)) { el.dataset.fx = '1'; el.classList.add('fx'); tagged.push(el); } });

  // ---- Entrance --------------------------------------------------------------------------------
  const clear = (el, wait) => window.setTimeout(() => {
    el.classList.remove('fx', 'fx-sm', 'fx-mask', 'is-in');
    el.style.removeProperty('--fx-d');
  }, wait);

  const pending = new Set(tagged);
  const reveal = (el, n) => {
    if (!pending.delete(el)) return;
    io.unobserve(el);
    const delay = Math.min(n * 90, 450);
    el.style.setProperty('--fx-d', `${delay}ms`);
    requestAnimationFrame(() => el.classList.add('is-in'));
    clear(el, 1700 + delay);
  };
  const io = new IntersectionObserver(entries => {
    const perParent = new Map();
    entries
      .filter(e => e.isIntersecting)
      .sort((a, b) => a.boundingClientRect.top - b.boundingClientRect.top || a.boundingClientRect.left - b.boundingClientRect.left)
      .forEach(({ target: el }) => {
        const n = perParent.get(el.parentElement) || 0;
        perParent.set(el.parentElement, n + 1);
        reveal(el, n);
      });
  }, { threshold: 0.05, rootMargin: '0px 0px -3% 0px' });

  // Safety net: whenever scrolling pauses (and shortly after load), reveal anything that is on
  // screen but was missed, for example an element at the very bottom of the page.
  const sweep = () => {
    let n = 0;
    pending.forEach(el => {
      const r = el.getBoundingClientRect();
      if (!(r.width || r.height)) return;
      if (r.bottom <= 0) {                       // scrolled past without being seen: show it, no animation
        pending.delete(el);
        io.unobserve(el);
        el.classList.remove('fx', 'fx-sm', 'fx-mask', 'is-in');
      } else if (r.top < window.innerHeight) {   // on screen but missed: animate it in
        reveal(el, n++);
      }
    });
  };
  let sweepTimer = 0;
  window.addEventListener('scroll', () => { window.clearTimeout(sweepTimer); sweepTimer = window.setTimeout(sweep, 350); }, { passive: true });
  window.setTimeout(sweep, 1500);

  tagged.forEach(el => io.observe(el));
  root.classList.add('fx-ready');
  root.classList.remove('fx-js'); // the pre-hide from the page head is now replaced by the .fx rules

  // ---- Parallax (photos drift a little against the scroll) --------------------------------------
  const drifters = [];
  PARALLAX.forEach(sel => document.querySelectorAll(sel).forEach(el => {
    if (el.closest(SKIP) || !el.querySelector('img') || drifters.some(d => d.el === el)) return;
    el.classList.add('fx-par');
    drifters.push({ el, visible: false });
  }));
  if (drifters.length) {
    const seen = new IntersectionObserver(entries => entries.forEach(e => {
      const d = drifters.find(x => x.el === e.target);
      if (d) d.visible = e.isIntersecting;
    }), { rootMargin: '10% 0px' });
    drifters.forEach(d => seen.observe(d.el));
  }

  // ---- Progress bar + parallax update on scroll (one rAF per frame) ------------------------------
  const bar = Object.assign(document.createElement('div'), { className: 'scroll-progress' });
  bar.setAttribute('aria-hidden', 'true');
  document.body.append(bar);
  let ticking = false;
  const update = () => {
    ticking = false;
    const max = root.scrollHeight - window.innerHeight;
    bar.style.setProperty('--p', max > 0 ? Math.min(1, Math.max(0, window.scrollY / max)).toFixed(4) : '0');
    const vh = window.innerHeight;
    drifters.forEach(d => {
      if (!d.visible) return;
      const r = d.el.getBoundingClientRect();
      const p = (r.top + r.height / 2 - vh / 2) / vh;             // -1 .. 1 across the viewport
      d.el.style.setProperty('--py', `${Math.max(-24, Math.min(24, -p * 44)).toFixed(1)}px`);
    });
  };
  window.addEventListener('scroll', () => { if (!ticking) { ticking = true; requestAnimationFrame(update); } }, { passive: true });
  window.addEventListener('resize', update, { passive: true });
  update();
})();
