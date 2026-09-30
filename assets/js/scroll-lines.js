(() => {
  'use strict';
  // Home About statement: each line stays blurred until it scrolls into view, then sharpens.
  // Lines re-blur when scrolled back out below the fold. The blurred state (styles.css) only applies
  // once .scroll-lines-ready is on the section, so without JS or under reduced motion the text is plain.
  const heading = document.querySelector('[data-scroll-lines]');
  if (!heading || !('IntersectionObserver' in window)) return;
  if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) return;

  const section = heading.closest('.v-about');
  const lines = heading.querySelectorAll('.reveal-line');
  if (!section || !lines.length) return;

  const observer = new IntersectionObserver(entries => {
    entries.forEach(entry => {
      // A line counts as "in view" once it clears the lower 22% of the viewport; it un-reveals only
      // when it drops back below that band, so text already scrolled past never re-blurs.
      const belowFold = entry.boundingClientRect.top > 0;
      if (entry.isIntersecting) entry.target.classList.add('is-visible');
      else if (belowFold) entry.target.classList.remove('is-visible');
    });
  }, { rootMargin: '0px 0px -22% 0px', threshold: 0 });

  section.classList.add('scroll-lines-ready');
  lines.forEach(line => observer.observe(line));
})();
