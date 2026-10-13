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

  // Service and audience pages get a restrained editorial entrance: the copy leads,
  // then the image and action controls arrive in a short, readable cascade.
  const detailHero = document.querySelector('[data-detail-hero]');
  if (detailHero) {
    const detailTimeline = gsap.timeline({ defaults: { ease: 'power3.out' } });
    detailTimeline
      .from(detailHero.querySelectorAll('.crumbs, .eyebrow-dark, h1, .detail-lead'), {
        opacity: 0, y: 18, duration: .65, stagger: .08, clearProps: 'all',
      })
      .from(detailHero.querySelector('.hero-actions'), {
        opacity: 0, y: 12, duration: .45, clearProps: 'all',
      }, '-=.25')
      .from(detailHero.querySelector('.hero-art'), {
        opacity: 0, x: 28, rotate: 4, duration: .9, clearProps: 'all',
      }, '-=.6');
  }

  // Keep the small cards feeling tactile without adding a dependency or affecting touch devices.
  if (!window.matchMedia('(pointer: coarse)').matches) {
    document.querySelectorAll('.related-card, .process-card').forEach(card => {
      card.addEventListener('pointermove', event => {
        const rect = card.getBoundingClientRect();
        const x = (event.clientX - rect.left) / rect.width - .5;
        const y = (event.clientY - rect.top) / rect.height - .5;
        gsap.to(card, { rotateY: x * 3, rotateX: y * -3, duration: .35, overwrite: true });
      });
      card.addEventListener('pointerleave', () => gsap.to(card, { rotateY: 0, rotateX: 0, duration: .5, overwrite: true }));
    });
  }

  // Scroll reveals for headings, cards, lists and photos live in scroll-fx.js (no GSAP needed).

  if (Lenis) {
    const lenis = new Lenis({ duration: 1.1 });
    lenis.on('scroll', ScrollTrigger.update);
    gsap.ticker.add(time => lenis.raf(time * 1000));
    gsap.ticker.lagSmoothing(0);
  }
})();
