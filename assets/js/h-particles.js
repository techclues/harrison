// "Numbers into clarity": gold and navy particles gather into the Harrison H (Who We Are hero).
// Progressive enhancement: the hero photo stays until WebGL is ready. Particle targets are sampled
// from the Brand Guide vectors (assets/images/h-mark.json), weighted towards the outline so the
// mark reads crisply. Particles assemble on arrival, part around the cursor, and scatter again as
// the hero scrolls away. Rendering pauses off-screen; reduced motion draws the finished mark once.
const stage = document.querySelector('[data-h-particles]');

if (stage) {
  const reduced = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
  const small = window.matchMedia('(max-width: 680px)').matches;
  const probe = document.createElement('canvas');
  if (probe.getContext('webgl2') || probe.getContext('webgl')) {
    stage.classList.add('is-3d-pending'); // hide the photo straight away so it never flashes
    start().catch(() => stage.classList.remove('is-3d-pending')); // on failure, show the photo
  }

  async function start() {
    const [THREE, data] = await Promise.all([import('three'), fetch(stage.dataset.hParticles).then(r => r.json())]);
    const { width: W, height: H } = data;

    // ---- Sample target points from the logo shapes -----------------------------------------
    const SCALE = 3;
    const cw = Math.ceil(W * SCALE), ch = Math.ceil(H * SCALE);
    const ctx = Object.assign(document.createElement('canvas'), { width: cw, height: ch }).getContext('2d', { willReadFrequently: true });
    const toPath = cmds => {
      const p = new Path2D();
      for (const [op, ...v] of cmds) {
        const q = v.map(n => n * SCALE);
        if (op === 'M') p.moveTo(q[0], q[1]);
        else if (op === 'L') p.lineTo(q[0], q[1]);
        else p.bezierCurveTo(...q);
      }
      p.closePath();
      return p;
    };
    ctx.fillStyle = '#f00'; ctx.fill(toPath(data.stemL)); ctx.fill(toPath(data.stemR));
    ctx.fillStyle = '#0f0'; ctx.fill(toPath(data.swoosh));
    const px = ctx.getImageData(0, 0, cw, ch).data;
    const kind = i => (px[i * 4 + 1] > 128 ? 2 : px[i * 4] > 128 ? 1 : 0); // 1 navy stem, 2 gold swoosh
    const fill = [], edge = [];
    for (let y = 1; y < ch - 1; y++) {
      for (let x = 1; x < cw - 1; x++) {
        const i = y * cw + x, k = kind(i);
        if (!k) continue;
        const isEdge = kind(i - 1) !== k || kind(i + 1) !== k || kind(i - cw) !== k || kind(i + cw) !== k;
        (isEdge ? edge : fill).push(i);
      }
    }
    const pick = arr => arr[(Math.random() * arr.length) | 0];

    const COUNT = small ? 2600 : 6000;
    const AMBIENT = Math.round(COUNT * 0.08); // "loose numbers" that never settle
    const home = new Float32Array(COUNT * 3), scatter = new Float32Array(COUNT * 3);
    const delay = new Float32Array(COUNT), seed = new Float32Array(COUNT), offset = new Float32Array(COUNT * 2);
    const colors = new Float32Array(COUNT * 3), positions = new Float32Array(COUNT * 3);
    const navy = new THREE.Color(0x222d65), gold = new THREE.Color(0xa08359), goldLight = new THREE.Color(0xd7bb72), c = new THREE.Color();
    const spread = Math.max(W, H);

    for (let n = 0; n < COUNT; n++) {
      const ambient = n < AMBIENT;
      const i = Math.random() < 0.35 ? pick(edge) : pick(fill);
      const x = (i % cw) / SCALE, y = Math.floor(i / cw) / SCALE;
      const k = kind(i);
      if (ambient) {
        home.set([(Math.random() - 0.5) * spread * 1.5, (Math.random() - 0.5) * spread * 1.2, (Math.random() - 0.5) * 140], n * 3);
        c.copy(goldLight).lerp(navy, Math.random() * 0.3);
      } else {
        home.set([x - W / 2, H / 2 - y, (Math.random() - 0.5) * 16], n * 3);
        if (k === 2) c.copy(gold).lerp(goldLight, (1 - y / H) * 0.6); // gold darkens towards the base, as in the logo
        else c.copy(navy).lerp(goldLight, Math.random() * 0.06);
      }
      // Scattered start: a loose shell around the mark.
      const a = Math.random() * Math.PI * 2, r = spread * (0.55 + Math.random() * 0.7);
      scatter.set([Math.cos(a) * r, Math.sin(a) * r * 0.75, (Math.random() - 0.5) * spread], n * 3);
      delay[n] = ambient ? 0 : Math.random() * 0.45;
      seed[n] = Math.random() * 1000;
      colors.set([c.r, c.g, c.b], n * 3);
    }

    // ---- Scene ---------------------------------------------------------------------------
    const renderer = new THREE.WebGLRenderer({ antialias: true, alpha: true, powerPreference: 'low-power' });
    renderer.setPixelRatio(Math.min(window.devicePixelRatio || 1, 2));
    renderer.outputColorSpace = THREE.SRGBColorSpace;
    renderer.domElement.className = 'h-particles-canvas';
    renderer.domElement.setAttribute('aria-hidden', 'true');

    const dot = Object.assign(document.createElement('canvas'), { width: 64, height: 64 });
    const dctx = dot.getContext('2d');
    const grad = dctx.createRadialGradient(32, 32, 0, 32, 32, 32);
    grad.addColorStop(0, 'rgba(255,255,255,1)'); grad.addColorStop(0.62, 'rgba(255,255,255,1)'); grad.addColorStop(1, 'rgba(255,255,255,0)'); // crisp dot, soft rim
    dctx.fillStyle = grad; dctx.fillRect(0, 0, 64, 64);
    const sprite = new THREE.CanvasTexture(dot);
    sprite.colorSpace = THREE.SRGBColorSpace;

    const geometry = new THREE.BufferGeometry();
    geometry.setAttribute('position', new THREE.BufferAttribute(positions, 3).setUsage(THREE.DynamicDrawUsage));
    geometry.setAttribute('color', new THREE.BufferAttribute(colors, 3));
    const material = new THREE.PointsMaterial({ size: small ? 5 : 4.6, map: sprite, vertexColors: true, transparent: true, depthWrite: false, sizeAttenuation: true });
    const points = new THREE.Points(geometry, material);
    const scene = new THREE.Scene();
    scene.add(points);

    const camera = new THREE.PerspectiveCamera(30, 1, 1, 5000);
    let halfH = 1, halfW = 1;
    const fit = () => {
      const { clientWidth: w, clientHeight: h } = stage;
      if (!w || !h) return;
      renderer.setSize(w, h, false);
      camera.aspect = w / h;
      const size = spread * 1.35;
      const dist = (size / 2) / Math.tan(THREE.MathUtils.degToRad(camera.fov / 2)) / Math.min(1, camera.aspect);
      camera.position.set(0, 0, dist);
      camera.updateProjectionMatrix();
      halfH = dist * Math.tan(THREE.MathUtils.degToRad(camera.fov / 2));
      halfW = halfH * camera.aspect;
    };

    // ---- Interaction ---------------------------------------------------------------------
    const pointer = { x: 0, y: 0, active: false, tiltX: 0, tiltY: 0 };
    window.addEventListener('pointermove', e => {
      const r = stage.getBoundingClientRect();
      const nx = ((e.clientX - r.left) / r.width) * 2 - 1, ny = -(((e.clientY - r.top) / r.height) * 2 - 1);
      pointer.x = nx * halfW; pointer.y = ny * halfH;
      pointer.active = Math.abs(nx) < 1.4 && Math.abs(ny) < 1.4;
      pointer.tiltY = Math.max(-1, Math.min(1, nx)) * 0.28;
      pointer.tiltX = -Math.max(-1, Math.min(1, ny)) * 0.18;
    }, { passive: true });
    document.addEventListener('pointerleave', () => { pointer.active = false; pointer.tiltX = pointer.tiltY = 0; });

    const section = stage.closest('section') || stage;
    const scrollAway = () => {
      const r = section.getBoundingClientRect();
      return Math.max(0, Math.min(1, -r.top / (r.height * 0.8)));
    };

    const ASSEMBLE_MS = 2600, RADIUS = spread * 0.16, PUSH = 0.9;
    const smooth = t => t * t * (3 - 2 * t);
    let startedAt = 0;
    const update = (now, still) => {
      if (!startedAt) startedAt = now;
      const t = (now - startedAt) / 1000;
      const progress = still ? 1 : Math.min(1, (now - startedAt) / ASSEMBLE_MS) * (1 - scrollAway());
      for (let n = 0; n < COUNT; n++) {
        const j = n * 3, ambient = n < AMBIENT;
        const a = ambient ? 1 : smooth(Math.max(0, Math.min(1, (progress - delay[n]) / 0.55)));
        const wob = still ? 0 : (ambient ? 7 : 0.9 + (1 - a) * 10);
        const s = seed[n];
        let x = scatter[j] + (home[j] - scatter[j]) * a + Math.sin(t * 0.7 + s) * wob;
        let y = scatter[j + 1] + (home[j + 1] - scatter[j + 1]) * a + Math.cos(t * 0.6 + s * 1.3) * wob;
        const z = scatter[j + 2] + (home[j + 2] - scatter[j + 2]) * a + Math.sin(t * 0.5 + s * 0.7) * wob;
        // Cursor parts the particles; each springs back when it leaves.
        let tx = 0, ty = 0;
        if (pointer.active && !still) {
          const dx = x - pointer.x, dy = y - pointer.y, d = Math.hypot(dx, dy);
          if (d < RADIUS && d > 0.001) { const f = (1 - d / RADIUS) ** 2 * RADIUS * PUSH; tx = (dx / d) * f; ty = (dy / d) * f; }
        }
        offset[n * 2] += (tx - offset[n * 2]) * 0.14;
        offset[n * 2 + 1] += (ty - offset[n * 2 + 1]) * 0.14;
        positions[j] = x + offset[n * 2]; positions[j + 1] = y + offset[n * 2 + 1]; positions[j + 2] = z;
      }
      geometry.attributes.position.needsUpdate = true;
      points.rotation.y += ((still ? 0.12 : pointer.tiltY + Math.sin(t * 0.3) * 0.08) - points.rotation.y) * 0.06;
      points.rotation.x += ((still ? 0 : pointer.tiltX) - points.rotation.x) * 0.06;
      renderer.render(scene, camera);
    };

    stage.prepend(renderer.domElement);
    fit();
    stage.classList.add('is-3d');
    new ResizeObserver(() => { fit(); update(performance.now(), reduced); }).observe(stage);

    if (reduced) { update(performance.now(), true); return; }
    let running = false, raf = 0;
    const frame = now => { update(now, false); if (running) raf = requestAnimationFrame(frame); };
    new IntersectionObserver(entries => {
      const visible = entries.some(e => e.isIntersecting);
      if (visible && !running) { running = true; raf = requestAnimationFrame(frame); }
      if (!visible && running) { running = false; cancelAnimationFrame(raf); }
    }).observe(stage);
  }
}
