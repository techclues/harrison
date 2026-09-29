(() => {
  'use strict';
  // Scroll-triggered typewriter for headings marked .v-typewriter-heading (Home page).
  // Progressive enhancement: without IntersectionObserver, or under reduced motion, headings are left untouched.
  const headings = document.querySelectorAll('.v-typewriter-heading');
  if (!headings.length || !('IntersectionObserver' in window)) return;
  if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) return;

  const MS_PER_CHAR = 32;

  // Wrap every character in a span (keeping <br> and <em> structure); words stay unbreakable.
  const prepare = heading => {
    const chars = [];
    const wrap = node => {
      [...node.childNodes].forEach(child => {
        if (child.nodeType === Node.TEXT_NODE) {
          const frag = document.createDocumentFragment();
          child.textContent.split(/(\s+)/).forEach(token => {
            if (!token) return;
            if (/^\s+$/.test(token)) { frag.append(document.createTextNode(' ')); return; }
            const word = document.createElement('span');
            word.className = 'tw-word';
            word.setAttribute('aria-hidden', 'true');
            [...token].forEach(ch => {
              const c = document.createElement('span');
              c.className = 'tw-char';
              c.textContent = ch;
              word.append(c);
              chars.push(c);
            });
            frag.append(word);
          });
          child.replaceWith(frag);
        } else if (child.nodeType === Node.ELEMENT_NODE && child.tagName !== 'BR') {
          wrap(child);
        }
      });
    };
    // Accessible name is the full text, so screen readers never hear partial typing.
    const clone = heading.cloneNode(true);
    clone.querySelectorAll('br').forEach(br => br.replaceWith(' '));
    heading.setAttribute('aria-label', clone.textContent.replace(/\s+/g, ' ').trim());
    wrap(heading);
    return chars;
  };

  const type = chars => {
    const start = performance.now();
    let shown = 0;
    const step = now => {
      const target = Math.min(chars.length, Math.floor((now - start) / MS_PER_CHAR) + 1);
      for (; shown < target; shown++) {
        chars[shown].classList.add('tw-on');
        if (shown > 0) chars[shown - 1].classList.remove('tw-current');
        chars[shown].classList.add('tw-current');
      }
      if (shown < chars.length) requestAnimationFrame(step);
      else setTimeout(() => chars[chars.length - 1].classList.remove('tw-current'), 600);
    };
    requestAnimationFrame(step);
  };

  const observer = new IntersectionObserver(entries => {
    entries.forEach(entry => {
      if (!entry.isIntersecting) return;
      observer.unobserve(entry.target);
      type(entry.target.twChars);
    });
  }, { threshold: 0.4 });

  headings.forEach(heading => {
    heading.twChars = prepare(heading);
    heading.classList.add('tw-ready');
    observer.observe(heading);
  });
})();
