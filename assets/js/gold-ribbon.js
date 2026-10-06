// The gold swoosh as a flowing 3D ribbon. Two placements:
//  - inside a hero (data-gold-ribbon-above / -below given): sits in the measured gap between two
//    blocks, behind the text, and fades as the hero scrolls away (Services overview);
//  - a standalone band (data-gold-ribbon="band"): a divider across a section seam (Home), which
//    flows with page scroll.
// It unrolls on arrival and ripples where the cursor passes. An orthographic camera maps one unit to
// one CSS pixel. Pauses off-screen; reduced motion draws one still frame; without WebGL nothing changes.
const stages = [...document.querySelectorAll('[data-gold-ribbon]')];

if (stages.length) {
  const reduced = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
  const probe = document.createElement('canvas');
  if (probe.getContext('webgl2') || probe.getContext('webgl')) {
    Promise.all([import('three'), import('three/addons/environments/RoomEnvironment.js')])
      .then(([THREE, { RoomEnvironment }]) => stages.forEach(stage => start(stage, THREE, RoomEnvironment)))
      .catch(() => { /* page stays as is */ });
  }

  function start(stage, THREE, RoomEnvironment) {
    const isBand = stage.dataset.goldRibbon === 'band';
    const hero = isBand ? stage : stage.closest('section');
    const above = isBand ? null : hero.querySelector(stage.dataset.goldRibbonAbove);
    const below = isBand ? null : hero.querySelector(stage.dataset.goldRibbonBelow);

    const renderer = new THREE.WebGLRenderer({ antialias: true, alpha: true, powerPreference: 'low-power' });
    renderer.setPixelRatio(Math.min(window.devicePixelRatio || 1, 2));
    renderer.outputColorSpace = THREE.SRGBColorSpace;
    renderer.toneMapping = THREE.NeutralToneMapping;
    renderer.domElement.className = 'gold-ribbon-canvas';
    renderer.domElement.setAttribute('aria-hidden', 'true');

    const scene = new THREE.Scene();
    scene.environment = new THREE.PMREMGenerator(renderer).fromScene(new RoomEnvironment(), 0.04).texture;
    const light = new THREE.DirectionalLight(0xfff4dc, 1.2);
    light.position.set(-0.4, 1, 1);
    scene.add(light);
    const camera = new THREE.OrthographicCamera(-1, 1, 1, -1, -2000, 2000);

    // Ribbon geometry built column by column, so drawRange can unroll it left to right.
    const COLS = 240, ROWS = 7;
    const makeRibbon = material => {
      const geo = new THREE.BufferGeometry();
      geo.setAttribute('position', new THREE.BufferAttribute(new Float32Array(COLS * ROWS * 3), 3).setUsage(THREE.DynamicDrawUsage));
      const idx = [];
      for (let i = 0; i < COLS - 1; i++) {
        for (let j = 0; j < ROWS - 1; j++) {
          const a = i * ROWS + j, b = a + ROWS;
          idx.push(a, b, a + 1, b, b + 1, a + 1);
        }
      }
      geo.setIndex(idx);
      const mesh = new THREE.Mesh(geo, material);
      scene.add(mesh);
      return mesh;
    };
    const gold = makeRibbon(new THREE.MeshPhysicalMaterial({ color: 0xd7bb72, metalness: 1, roughness: 0.28, clearcoat: 0.5, side: THREE.DoubleSide, envMapIntensity: 1.4, transparent: true }));
    const navy = makeRibbon(new THREE.MeshPhysicalMaterial({ color: 0x222d65, metalness: 0.1, roughness: 0.45, side: THREE.DoubleSide, envMapIntensity: 0.5, transparent: true, opacity: 0.55 }));

    let w = 1, h = 1, centerY = 0, room = 60;
    const fit = () => {
      w = hero.clientWidth; h = hero.clientHeight;
      if (!w || !h) return;
      renderer.setSize(w, h, false);
      Object.assign(camera, { left: -w / 2, right: w / 2, top: h / 2, bottom: -h / 2 });
      camera.updateProjectionMatrix();
      // Place the ribbon in the gap between the headline block and the service list.
      const hr = hero.getBoundingClientRect();
      const top = isBand ? h * 0.12 : above ? above.getBoundingClientRect().bottom - hr.top : h * 0.55;
      const bottom = isBand ? h * 0.88 : below ? below.getBoundingClientRect().top - hr.top : h * 0.8;
      centerY = h / 2 - (top + bottom) / 2;
      room = Math.max(36, (bottom - top) / 2);
    };

    const ripple = { x: 0, amp: 0, target: 0 };
    // Listen on the window: a band divider sits over other content and takes no pointer events.
    window.addEventListener('pointermove', e => {
      const r = hero.getBoundingClientRect();
      const near = isBand ? 140 : 0;
      if (e.clientY < r.top - near || e.clientY > r.bottom + near) return;
      ripple.x = e.clientX - r.left - w / 2;
      ripple.target = 1;
    }, { passive: true });

    const shape = (mesh, t, phase, halfWidth, ampScale, twistBias) => {
      const pos = mesh.geometry.attributes.position.array;
      const span = w * 1.3;
      const A1 = room * 0.36 * ampScale, A2 = room * 0.14 * ampScale; // peak swing + width stays inside the band
      for (let i = 0; i < COLS; i++) {
        const u = i / (COLS - 1);
        const x = -span / 2 + u * span;
        const g = Math.exp(-(((x - ripple.x) / 160) ** 2)) * ripple.amp;           // cursor ripple
        const y = centerY + A1 * Math.sin(u * Math.PI * 2.2 + phase) + A2 * Math.sin(u * Math.PI * 4.6 - phase * 0.7)
          + g * room * 0.22 * Math.sin(t * 6 - x * 0.03);
        const taper = 0.35 + 0.65 * Math.sin(Math.PI * Math.min(1, Math.max(0, u * 1.08 - 0.04)));
        const hw = halfWidth * taper;
        const twist = u * Math.PI * 1.6 + phase * 0.6 + twistBias + g * 0.9;
        const cy = Math.cos(twist), cz = Math.sin(twist);
        for (let j = 0; j < ROWS; j++) {
          const v = (j / (ROWS - 1)) * 2 - 1, k = (i * ROWS + j) * 3;
          pos[k] = x; pos[k + 1] = y + hw * v * cy; pos[k + 2] = hw * v * cz * 1.6;
        }
      }
      mesh.geometry.attributes.position.needsUpdate = true;
      mesh.geometry.computeVertexNormals();
    };

    const UNROLL_MS = 2200;
    let startedAt = 0;
    const update = (now, still) => {
      if (!startedAt) startedAt = now;
      const t = still ? 0 : (now - startedAt) / 1000;
      const hr = hero.getBoundingClientRect();
      // Hero: fade as it scrolls away. Band: never fade; flow with its position in the viewport.
      const away = isBand ? 0 : Math.max(0, Math.min(1, -hr.top / hr.height));
      const drift = isBand ? (1 - (hr.top + hr.height / 2) / window.innerHeight) * 2.4 : away * 3.2;
      ripple.amp += (ripple.target - ripple.amp) * 0.05;
      ripple.target *= 0.985;                                                       // ripple settles when the cursor rests
      const phase = t * 0.35 + drift;                                               // scrolling makes it flow
      shape(gold, t, phase, Math.min(26, room * 0.3), 1, 0);
      shape(navy, t, phase + 0.9, Math.min(9, room * 0.14), 0.8, 1.2);
      const unroll = still ? 1 : 1 - Math.pow(1 - Math.min(1, (now - startedAt) / UNROLL_MS), 3);
      const count = Math.floor((COLS - 1) * unroll) * (ROWS - 1) * 6;
      gold.geometry.setDrawRange(0, count);
      navy.geometry.setDrawRange(0, count);
      gold.material.opacity = 0.92 * (1 - away * 0.85);
      navy.material.opacity = 0.5 * (1 - away * 0.85);
      renderer.render(scene, camera);
    };

    stage.append(renderer.domElement);
    fit();
    stage.classList.add('is-3d');
    new ResizeObserver(() => { fit(); update(performance.now(), reduced); }).observe(hero);

    if (reduced) { update(performance.now(), true); return; }
    let running = false, raf = 0;
    const frame = now => { update(now, false); if (running) raf = requestAnimationFrame(frame); };
    new IntersectionObserver(entries => {
      const visible = entries.some(e => e.isIntersecting);
      if (visible && !running) { running = true; raf = requestAnimationFrame(frame); }
      if (!visible && running) { running = false; cancelAnimationFrame(raf); }
    }).observe(hero);
  }
}
