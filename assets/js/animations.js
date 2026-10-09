(() => {
  'use strict';
  // Optional motion layer. Runs only when GSAP + ScrollTrigger loaded and reduced motion is not requested.
  // Nothing is hidden in CSS, so content stays visible if this file or a CDN fails.
  const reduced = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
  const { gsap, ScrollTrigger, Lenis } = window;
  if (reduced || !gsap || !ScrollTrigger) return;

  gsap.registerPlugin(ScrollTrigger);
  document.documentElement.classList.add('reveal-ready');

  // Calm hero entrance on the approved pages.
  gsap.from('.v-hero-content, .page-hero-grid, .about-hero-grid', { opacity: 0, y: 22, duration: .75, ease: 'power2.out', clearProps: 'all' });

  // Scroll reveals for headings, cards, lists and photos live in scroll-fx.js (no GSAP needed).

  if (Lenis) {
    const lenis = new Lenis({ duration: 1.1 });
    lenis.on('scroll', ScrollTrigger.update);
    gsap.ticker.add(time => lenis.raf(time * 1000));
    gsap.ticker.lagSmoothing(0);
  }
})();
