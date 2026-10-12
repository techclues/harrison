(() => {
  'use strict';
  // Home hero: the background is three full-width photos in a sideways-scrolling strip that also
  // changes by itself and loops. It works with no script (native swipe, trackpad or keyboard
  // scrolling); this adds the arrows, dots, mouse dragging, arrow keys and autoplay.
  //
  // Looping: a copy of the first photo is appended after the last, so "next" from the last photo
  // slides on to the copy and then jumps (unseen) back to the real first photo; "previous" from
  // the first jumps to the copy and slides back. Autoplay changes photo every 6.5 seconds (instant cuts,
  // every 8, under reduced motion) and pauses while the pointer is on the controls or the strip has keyboard focus, while the tab is
  // hidden or the hero is off screen.
  const root = document.querySelector('[data-hero-slider]');
  if (!root) return;
  const track = root.querySelector('.hero-slider-track');
  const ctrl = document.querySelector('.hero-slider-ctrl');
  const prev = ctrl?.querySelector('[data-hs="prev"]');
  const next = ctrl?.querySelector('[data-hs="next"]');
  const dots = [...(ctrl?.querySelectorAll('[data-hs-dot]') || [])];
  if (!track || !ctrl || !prev || !next) return;

  const real = [...track.children];
  const count = real.length;
  const reduced = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
  const DELAY = reduced ? 8000 : 6500;   // reduced motion: slower, and each change is an instant cut, never a slide
  ctrl.hidden = false;

  // seamless loop: a decorative copy of the first photo after the last
  const clone = real[0].cloneNode(true);
  clone.setAttribute('aria-hidden', 'true');
  clone.removeAttribute('aria-label');
  clone.removeAttribute('aria-roledescription');
  clone.querySelector('img')?.setAttribute('alt', '');
  track.append(clone);

  const step = () => track.clientWidth;
  const raw = () => Math.round(track.scrollLeft / step());               // 0..count (count = the copy)
  const current = () => raw() % count;
  const smooth = reduced ? 'auto' : 'smooth';
  const jump = x => { track.style.scrollSnapType = 'none'; track.scrollLeft = x; void track.offsetWidth; track.style.scrollSnapType = ''; };
  const scrollToIndex = i => track.scrollTo({ left: i * step(), behavior: smooth });

  let holdJump = false;   // true while sliding back from the copy, so the loop reset leaves it alone
  const go = delta => {
    let i = raw();
    if (delta > 0) i += 1;
    else if (i === 0) {                                                   // from the first photo: slide back from the copy
      jump(count * step());
      i = count - 1;
      holdJump = true;
      window.setTimeout(() => { holdJump = false; }, 1300);
    }
    else i -= 1;
    scrollToIndex(i);
    restart();
  };
  const goTo = target => {                                                // dots: the shortest honest route
    scrollToIndex(target);
    restart();
  };

  // after a slide onto the copy settles, jump back to the real first photo without anyone noticing
  let settle = 0;
  const onScroll = () => {
    window.clearTimeout(settle);
    settle = window.setTimeout(() => { if (raw() >= count && !holdJump) jump(0); updateDots(); }, 140);
    updateDots();
  };
  const updateDots = () => dots.forEach((d, i) => d.setAttribute('aria-current', String(i === current())));
  track.addEventListener('scroll', onScroll, { passive: true });
  window.addEventListener('resize', () => { jump(raw() * step()); updateDots(); }, { passive: true });
  updateDots();

  prev.addEventListener('click', () => go(-1));
  next.addEventListener('click', () => go(1));
  dots.forEach((d, i) => d.addEventListener('click', () => goTo(i)));
  track.addEventListener('keydown', e => {
    if (e.key === 'ArrowRight') { e.preventDefault(); go(1); }
    if (e.key === 'ArrowLeft') { e.preventDefault(); go(-1); }
  });

  // ---- autoplay ----------------------------------------------------------------------------
  let timer = 0;
  let hoveringControls = false;
  let focusInside = false;
  let visible = true;
  let dragging = false;
  const canPlay = () => !hoveringControls && !focusInside && !dragging && visible && !document.hidden;
  const stop = () => { window.clearTimeout(timer); timer = 0; };
  function restart() {
    stop();
    if (canPlay()) timer = window.setTimeout(() => { if (canPlay()) go(1); else restart(); }, DELAY);
  }
  ctrl.addEventListener('pointerenter', () => { hoveringControls = true; restart(); });
  ctrl.addEventListener('pointerleave', () => { hoveringControls = false; restart(); });
  track.addEventListener('focusin', () => { focusInside = true; restart(); });
  track.addEventListener('focusout', () => { focusInside = false; restart(); });
  track.addEventListener('touchstart', stop, { passive: true });          // a finger on the photo pauses it
  track.addEventListener('touchend', restart, { passive: true });
  document.addEventListener('visibilitychange', restart);
  new IntersectionObserver(entries => { visible = entries.some(e => e.isIntersecting); restart(); }, { threshold: 0.25 }).observe(root);

  // ---- mouse: click and drag the photo (touch and trackpads already scroll natively) ------------
  let drag = null;
  document.querySelector('.v-hero').addEventListener('pointerdown', e => {
    if (e.pointerType !== 'mouse' || e.button !== 0 || e.target.closest('a, button, input')) return;
    drag = { x: e.clientX, left: track.scrollLeft };
    dragging = true;
    stop();
    track.classList.add('is-dragging');
  });
  window.addEventListener('pointermove', e => { if (drag) track.scrollLeft = drag.left - (e.clientX - drag.x); });
  const endDrag = () => {
    if (!drag) return;
    const moved = track.scrollLeft - drag.left;
    const i = raw();
    drag = null;
    dragging = false;
    track.classList.remove('is-dragging');
    scrollToIndex(Math.abs(moved) > 40 ? (moved > 0 ? i + 1 : Math.max(i - 1, 0)) : i);
    restart();
  };
  window.addEventListener('pointerup', endDrag);
  window.addEventListener('pointercancel', endDrag);

  restart();
})();
