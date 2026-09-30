(() => {
  'use strict';
  // Interactive dot field: a grid of dots that gets pushed apart by the cursor, then springs back.
  // Decorative only (aria-hidden canvas). Static under reduced motion; idle loop stops itself.
  const canvases = document.querySelectorAll('canvas[data-dot-field]');
  if (!canvases.length) return;

  const reduced = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
  const SPACING = 30;        // px between dots
  const RADIUS = 150;        // cursor influence radius
  const PUSH = 11;           // repel strength
  const SPRING = 0.065;      // pull back to home position
  const DAMPING = 0.82;
  const BASE = 'rgba(34,45,101,0.2)';   // brand navy
  const ACTIVE = '160,131,89';           // brand gold

  canvases.forEach(canvas => {
    const ctx = canvas.getContext('2d');
    if (!ctx) return;
    const host = canvas.parentElement;
    let dots = [], w = 0, h = 0, dpr = 1;
    let pointer = null, visible = false, raf = 0;

    const build = () => {
      const rect = host.getBoundingClientRect();
      dpr = Math.min(window.devicePixelRatio || 1, 2);
      w = Math.max(1, Math.round(rect.width));
      h = Math.max(1, Math.round(rect.height));
      canvas.width = w * dpr;
      canvas.height = h * dpr;
      ctx.setTransform(dpr, 0, 0, dpr, 0, 0);
      dots = [];
      const cols = Math.ceil(w / SPACING) + 1, rows = Math.ceil(h / SPACING) + 1;
      const ox = (w - (cols - 1) * SPACING) / 2, oy = (h - (rows - 1) * SPACING) / 2;
      for (let r = 0; r < rows; r++) for (let c = 0; c < cols; c++) {
        const hx = ox + c * SPACING, hy = oy + r * SPACING;
        dots.push({ hx, hy, x: hx, y: hy, vx: 0, vy: 0 });
      }
      draw();
    };

    const draw = () => {
      ctx.clearRect(0, 0, w, h);
      ctx.fillStyle = BASE;
      ctx.beginPath();
      const moved = [];
      for (const d of dots) {
        const disp = Math.hypot(d.x - d.hx, d.y - d.hy);
        if (disp > 1.5) { moved.push([d, disp]); continue; }
        ctx.moveTo(d.x + 1.5, d.y);
        ctx.arc(d.x, d.y, 1.5, 0, Math.PI * 2);
      }
      ctx.fill();
      for (const [d, disp] of moved) {
        const t = Math.min(1, disp / 40);
        ctx.fillStyle = `rgba(${ACTIVE},${0.35 + t * 0.55})`;
        ctx.beginPath();
        ctx.arc(d.x, d.y, 1.5 + t * 1.6, 0, Math.PI * 2);
        ctx.fill();
      }
    };

    const step = () => {
      raf = 0;
      const rect = host.getBoundingClientRect();
      const px = pointer ? pointer.x - rect.left : -9999;
      const py = pointer ? pointer.y - rect.top : -9999;
      let energy = 0;
      for (const d of dots) {
        const dx = d.x - px, dy = d.y - py, dist = Math.hypot(dx, dy);
        if (dist < RADIUS && dist > 0.01) {
          const f = (1 - dist / RADIUS) ** 2 * PUSH;
          d.vx += (dx / dist) * f;
          d.vy += (dy / dist) * f;
        }
        d.vx += (d.hx - d.x) * SPRING;
        d.vy += (d.hy - d.y) * SPRING;
        d.vx *= DAMPING; d.vy *= DAMPING;
        d.x += d.vx; d.y += d.vy;
        energy += Math.abs(d.vx) + Math.abs(d.vy) + Math.abs(d.x - d.hx) + Math.abs(d.y - d.hy);
      }
      draw();
      // Keep running while the cursor is over the field or dots are still settling.
      if (visible && (pointer || energy > 0.5)) raf = requestAnimationFrame(step);
    };
    const wake = () => { if (!raf && visible) raf = requestAnimationFrame(step); };

    build();
    if (reduced) { new ResizeObserver(build).observe(host); return; }

    new ResizeObserver(build).observe(host);
    new IntersectionObserver(entries => {
      visible = entries[0].isIntersecting;
      if (visible) wake();
    }, { threshold: 0 }).observe(host);

    window.addEventListener('pointermove', e => {
      if (!visible) return;
      const rect = host.getBoundingClientRect();
      const inside = e.clientX >= rect.left - RADIUS && e.clientX <= rect.right + RADIUS
        && e.clientY >= rect.top - RADIUS && e.clientY <= rect.bottom + RADIUS;
      pointer = inside ? { x: e.clientX, y: e.clientY } : null;
      wake();
    }, { passive: true });
    document.addEventListener('pointerleave', () => { pointer = null; wake(); });
    window.addEventListener('blur', () => { pointer = null; });
  });
})();
