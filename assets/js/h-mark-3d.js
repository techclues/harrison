// 3D Harrison H mark for the Home closing section.
// Progressive enhancement: the container shows h-mark.svg until WebGL is ready. three.js is
// only fetched when the section nears the viewport, rendering pauses off-screen, and under
// reduced motion a single still frame is drawn. Geometry comes from the Brand Guide vectors
// (assets/images/h-mark.json): navy stems + gold swoosh, extruded.
const stage = document.querySelector('[data-h-mark]');

if (stage) {
  const reduced = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
  const loadWhenNear = new IntersectionObserver(entries => {
    if (!entries.some(e => e.isIntersecting)) return;
    loadWhenNear.disconnect();
    start().catch(() => { /* keep the SVG fallback */ });
  }, { rootMargin: '300px 0px' });
  loadWhenNear.observe(stage);

  async function start() {
    const probe = document.createElement('canvas');
    if (!(probe.getContext('webgl2') || probe.getContext('webgl'))) return;

    const [THREE, { RoomEnvironment }, shapesData] = await Promise.all([
      import('three'),
      import('three/addons/environments/RoomEnvironment.js'),
      fetch(stage.dataset.hMark).then(r => r.json()),
    ]);

    const renderer = new THREE.WebGLRenderer({ antialias: true, alpha: true, powerPreference: 'low-power' });
    renderer.setPixelRatio(Math.min(window.devicePixelRatio || 1, 2));
    renderer.outputColorSpace = THREE.SRGBColorSpace;
    renderer.toneMapping = THREE.NeutralToneMapping; // keeps the brand navy/gold close to their true values
    renderer.toneMappingExposure = 1;
    renderer.domElement.className = 'h-mark-canvas';
    renderer.domElement.setAttribute('aria-hidden', 'true');

    const scene = new THREE.Scene();
    const pmrem = new THREE.PMREMGenerator(renderer);
    scene.environment = pmrem.fromScene(new RoomEnvironment(), 0.04).texture;
    const key = new THREE.DirectionalLight(0xfff4dc, 1.1);
    key.position.set(-2, 3, 4);
    const rim = new THREE.DirectionalLight(0xd7bb72, 1.2);
    rim.position.set(3, -1, -2);
    scene.add(key, rim);

    // Brand Guide paths -> THREE.Shape (SVG y-down flipped to y-up).
    const { width: W, height: H } = shapesData;
    const toShape = cmds => {
      const s = new THREE.Shape();
      for (const [op, ...v] of cmds) {
        const p = v.map((n, i) => (i % 2 ? H - n : n));
        if (op === 'M') s.moveTo(p[0], p[1]);
        else if (op === 'L') s.lineTo(p[0], p[1]);
        else s.bezierCurveTo(p[0], p[1], p[2], p[3], p[4], p[5]);
      }
      return s;
    };
    const depth = 34;
    const extrude = (shape, z) => {
      const g = new THREE.ExtrudeGeometry(shape, {
        depth, curveSegments: 28, bevelEnabled: true, bevelThickness: 3, bevelSize: 2.2, bevelSegments: 5,
      });
      g.translate(-W / 2, -H / 2, -depth / 2 + z);
      return g;
    };
    const navy = new THREE.MeshPhysicalMaterial({ color: 0x222d65, metalness: 0.05, roughness: 0.5, clearcoat: 0.5, clearcoatRoughness: 0.35, envMapIntensity: 0.45 });
    const gold = new THREE.MeshPhysicalMaterial({ color: 0xd7bb72, metalness: 1, roughness: 0.3, clearcoat: 0.4, envMapIntensity: 1.5 });

    const mark = new THREE.Group();
    mark.add(new THREE.Mesh(extrude(toShape(shapesData.stemL), 0), navy));
    mark.add(new THREE.Mesh(extrude(toShape(shapesData.stemR), 0), navy));
    mark.add(new THREE.Mesh(extrude(toShape(shapesData.swoosh), 10), gold)); // swoosh sits slightly forward
    scene.add(mark);

    const camera = new THREE.PerspectiveCamera(28, 1, 1, 5000);
    const fit = () => {
      const { clientWidth: w, clientHeight: h } = stage;
      if (!w || !h) return;
      renderer.setSize(w, h, false);
      camera.aspect = w / h;
      const size = Math.max(W, H) * 1.7; // breathing room so tilt and drift never clip
      const dist = (size / 2) / Math.tan(THREE.MathUtils.degToRad(camera.fov / 2)) / Math.min(1, camera.aspect);
      camera.position.set(0, 0, dist);
      camera.updateProjectionMatrix();
    };

    stage.prepend(renderer.domElement);
    fit();
    new ResizeObserver(() => { fit(); if (!running) renderer.render(scene, camera); }).observe(stage);

    // Pointer tilt: tracks the cursor anywhere on the page, aimed relative to the mark itself,
    // so it reacts as soon as the mouse moves. Idle sway fades out while the pointer is active.
    const target = { x: 0, y: 0, px: 0, py: 0 };
    let lastMove = -1e9;
    const clamp = (v, m) => Math.max(-m, Math.min(m, v));
    window.addEventListener('pointermove', e => {
      const r = stage.getBoundingClientRect();
      const nx = clamp((e.clientX - (r.left + r.width * 0.74)) / (r.width * 0.6), 1);  // mark sits ~74% across
      const ny = clamp((e.clientY - (r.top + r.height / 2)) / (r.height * 0.9), 1);
      target.y = nx * 0.95;      // left/right turn, about ±55°
      target.x = ny * 0.6;       // up/down tilt, about ±35°
      target.px = nx * 18;       // slight drift towards the cursor
      target.py = -ny * 12;
      lastMove = performance.now();
    }, { passive: true });
    document.addEventListener('pointerleave', () => { target.x = target.y = target.px = target.py = 0; });

    const ENTRANCE_MS = 1400;
    const FOLLOW = 0.14;        // per-frame easing towards the target (higher = snappier)
    let startedAt = 0;
    let running = false;
    let raf = 0;
    let idle = 1;
    const frame = now => {
      if (!startedAt) startedAt = now;
      const t = (now - startedAt) / 1000;
      const entrance = Math.min(1, (now - startedAt) / ENTRANCE_MS); // time-based, same on any frame rate
      const ease = 1 - Math.pow(1 - entrance, 3);
      // Sway only when the pointer has been still for a moment.
      idle += ((now - lastMove > 1200 ? 1 : 0.15) - idle) * 0.05;
      const idleY = Math.sin(t * 0.45) * 0.32 * idle;
      const idleX = Math.sin(t * 0.31) * 0.06 * idle;
      const follow = entrance < 1 ? 1 : FOLLOW;
      mark.rotation.y += ((idleY + target.y - (1 - ease) * 1.4) - mark.rotation.y) * follow;
      mark.rotation.x += ((idleX + target.x) - mark.rotation.x) * FOLLOW;
      mark.position.x += (target.px - mark.position.x) * FOLLOW;
      mark.position.y += ((target.py + Math.sin(t * 0.8) * 4) - mark.position.y) * FOLLOW;
      renderer.render(scene, camera);
      if (running) raf = requestAnimationFrame(frame);
    };

    stage.classList.add('is-3d');
    if (reduced) {
      mark.rotation.set(-0.08, 0.32, 0);
      renderer.render(scene, camera);
      return;
    }
    mark.rotation.y = -1.4;
    new IntersectionObserver(entries => {
      const visible = entries.some(e => e.isIntersecting);
      if (visible && !running) { running = true; raf = requestAnimationFrame(frame); }
      if (!visible && running) { running = false; cancelAnimationFrame(raf); }
    }).observe(stage);
  }
}
