/* =========================================================
   tentacode.dev — Skills pile
   A physical heap of skill "bricks". They drop in and stack;
   you can grab one and shake the pile. Progressive enhancement:
   - No Matter.js / reduced motion / touch  -> tidy static flex-wrap.
   - Pointer + motion OK                     -> physics playground.
   ========================================================= */
(function () {
  "use strict";

  // Defer one frame so Vite dev-mode style injection has time to apply before
  // we measure brick dimensions (production is fine; dev injects CSS async).
  requestAnimationFrame(function init() {

  var inner = document.querySelector(".skills__inner");
  var pile = document.getElementById("skills-pile");
  var shakeBtn = document.getElementById("skills-shake");
  var tidyBtn = document.getElementById("skills-tidy");
  var tidyLabel = document.getElementById("skills-tidy-label");
  var titleState = document.getElementById("skills-title-state");
  if (!inner || !pile) return;

  var bricks = Array.prototype.slice.call(pile.querySelectorAll(".brick"));
  if (!bricks.length) return;

  // Gate: only run physics with a fine pointer + motion allowed + Matter present.
  var finePointer = window.matchMedia("(hover: hover) and (pointer: fine)").matches;
  var reduceMotion = window.matchMedia("(prefers-reduced-motion: reduce)").matches;
  if (!finePointer || reduceMotion || !window.Matter) return; // keep static fallback

  var M = window.Matter;
  var engine = M.Engine.create();
  engine.gravity.y = 1;

  // Switch to physics layout AFTER measuring would be ideal, but bricks are
  // inline-flex now; measure each, then absolutely position.
  var measured = bricks.map(function (el) {
    return { el: el, w: el.offsetWidth, h: el.offsetHeight };
  });

  inner.classList.add("is-physics");
  pile.classList.add("is-physics");

  function dims() {
    return { w: pile.clientWidth, h: pile.clientHeight };
  }

  var d = dims();
  var wallT = 200; // wall thickness
  var walls = [
    M.Bodies.rectangle(d.w / 2, d.h + wallT / 2, d.w + wallT * 2, wallT, { isStatic: true }), // floor
    M.Bodies.rectangle(-wallT / 2, d.h / 2, wallT, d.h * 3, { isStatic: true }),               // left
    M.Bodies.rectangle(d.w + wallT / 2, d.h / 2, wallT, d.h * 3, { isStatic: true })           // right
  ];
  M.World.add(engine.world, walls);

  // Build a body per brick, stacked above the frame at staggered heights so
  // they rain down when the runner starts.
  var items = measured.map(function (m, i) {
    var x = d.w * (0.12 + 0.76 * Math.random());
    var y = -60 - Math.random() * 900 - i * 12;
    var body = M.Bodies.rectangle(x, y, m.w, m.h, {
      restitution: 0.18,
      friction: 0.55,
      frictionStatic: 1.2,
      chamfer: { radius: 6 },
      angle: (Math.random() - 0.5) * 0.5
    });
    return { el: m.el, body: body, w: m.w, h: m.h };
  });
  M.World.add(engine.world, items.map(function (it) { return it.body; }));

  // Sync DOM transforms to physics bodies.
  function render() {
    for (var i = 0; i < items.length; i++) {
      var it = items[i];
      var p = it.body.position;
      it.el.style.transform =
        "translate(" + (p.x - it.w / 2) + "px," + (p.y - it.h / 2) + "px) rotate(" + it.body.angle + "rad)";
    }
  }
  M.Events.on(engine, "afterUpdate", render);
  render();

  // Runner — start only when the section scrolls into view (so the drop is seen).
  var runner = M.Runner.create();
  var started = false;
  function start() {
    if (started) return;
    started = true;
    items.forEach(function (it, i) {
      setTimeout(function () { it.el.classList.add("is-live"); }, 40 + i * 18);
    });
    M.Runner.run(runner, engine);
  }

  if ("IntersectionObserver" in window) {
    var io = new IntersectionObserver(function (entries) {
      entries.forEach(function (e) {
        if (e.isIntersecting) { start(); io.disconnect(); }
      });
    }, { threshold: 0.12 });
    io.observe(pile);
    // Safety net: if it's already on-screen or the observer never fires.
    setTimeout(function () {
      var r = pile.getBoundingClientRect();
      if (r.top < window.innerHeight && r.bottom > 0) start();
    }, 1200);
  } else {
    start();
  }

  // Shake — give every brick a random upward kick.
  function shake() {
    if (tidy) { setTidy(false); return; }
    if (!started) start();
    items.forEach(function (it) {
      M.Body.setVelocity(it.body, { x: (Math.random() - 0.5) * 18, y: -8 - Math.random() * 14 });
      M.Body.setAngularVelocity(it.body, (Math.random() - 0.5) * 0.4);
    });
  }
  if (shakeBtn) shakeBtn.addEventListener("click", shake);

  // ---- Tidy: pack bricks into readable left-aligned rows --------------------
  var tidy = false;
  var basePileH = pile.clientHeight;

  // Build category order from data attribute on pile.
  var categoryOrder = (pile.dataset.categoryOrder || '').split(',').filter(Boolean);

  function categoryRank(item) {
    var cat = item.el.dataset.category || '';
    var i = categoryOrder.indexOf(cat);
    return i === -1 ? categoryOrder.length : i;
  }

  function tidyPositions() {
    var pad = 16, gap = 12;
    var maxX = pile.clientWidth - pad;
    var x = pad, y = pad, rowH = 0, total = 0;

    // Sort by category order, preserving original index for position mapping.
    var sorted = items.map(function (it, i) { return { it: it, i: i }; });
    sorted.sort(function (a, b) { return categoryRank(a.it) - categoryRank(b.it); });

    var pos = new Array(items.length);
    var lastCat = null;
    sorted.forEach(function (entry) {
      var it = entry.it;
      var cat = it.el.dataset.category || '';
      // Force new row at each category boundary.
      if (lastCat !== null && cat !== lastCat) { x = pad; y += rowH + gap; rowH = 0; }
      lastCat = cat;
      if (x + it.w > maxX && x > pad) { x = pad; y += rowH + gap; rowH = 0; }
      var c = { x: x + it.w / 2, y: y + it.h / 2 };
      x += it.w + gap;
      rowH = Math.max(rowH, it.h);
      total = y + rowH + pad;
      pos[entry.i] = c;
    });
    return { pos: pos, height: total };
  }

  function setTidy(on) {
    if (on === tidy) return;
    tidy = on;
    if (tidyBtn) {
      tidyBtn.setAttribute("aria-pressed", on ? "true" : "false");
      if (tidyLabel) tidyLabel.textContent = on ? "Mélanger" : "Ranger";
      if (titleState) titleState.textContent = on ? "rangée" : "en vrac";
    }
    if (on) {
      if (!started) start();
      var t = tidyPositions();
      M.Runner.stop(runner);
      pile.style.height = Math.max(basePileH, t.height) + "px";
      items.forEach(function (it, i) {
        M.Body.setStatic(it.body, true);
        M.Body.setAngle(it.body, 0);
        M.Body.setPosition(it.body, t.pos[i]);
        it.el.classList.add("is-live");
        it.el.style.transition = "transform 0.55s cubic-bezier(.2,.85,.25,1)";
        it.el.style.transitionDelay = (i * 0.012) + "s";
        it.el.style.transform =
          "translate(" + (t.pos[i].x - it.w / 2) + "px," + (t.pos[i].y - it.h / 2) + "px) rotate(0rad)";
      });
    } else {
      items.forEach(function (it) {
        it.el.style.transition = "";
        it.el.style.transitionDelay = "";
        M.Body.setStatic(it.body, false);
      });
      pile.style.height = "";
      // resync floor to the restored height, then drop them loose again
      var nd = dims();
      M.Body.setPosition(walls[0], { x: nd.w / 2, y: nd.h + wallT / 2 });
      M.Runner.run(runner, engine);
      items.forEach(function (it) {
        M.Body.setVelocity(it.body, { x: (Math.random() - 0.5) * 14, y: -6 - Math.random() * 12 });
        M.Body.setAngularVelocity(it.body, (Math.random() - 0.5) * 0.35);
      });
    }
  }
  if (tidyBtn) tidyBtn.addEventListener("click", function () { setTidy(!tidy); });

  // Keep walls in sync with width changes; recalculate tidy layout if active.
  var resizeTO;
  window.addEventListener("resize", function () {
    clearTimeout(resizeTO);
    resizeTO = setTimeout(function () {
      var nd = dims();
      M.Body.setPosition(walls[0], { x: nd.w / 2, y: nd.h + wallT / 2 });
      M.Body.setPosition(walls[2], { x: nd.w + wallT / 2, y: nd.h / 2 });
      if (tidy) {
        var t = tidyPositions();
        pile.style.height = Math.max(basePileH, t.height) + "px";
        items.forEach(function (it, i) {
          // Suppress transition during resize to avoid glitch
          it.el.style.transition = "none";
          it.el.style.transitionDelay = "";
          M.Body.setPosition(it.body, t.pos[i]);
          it.el.style.transform =
            "translate(" + (t.pos[i].x - it.w / 2) + "px," + (t.pos[i].y - it.h / 2) + "px) rotate(0rad)";
        });
        // Re-enable transitions after the frame is painted
        requestAnimationFrame(function () {
          items.forEach(function (it) {
            it.el.style.transition = "transform 0.55s cubic-bezier(.2,.85,.25,1)";
          });
        });
      } else {
        items.forEach(function (it) {
          if (it.body.position.x > nd.w) M.Body.setPosition(it.body, { x: nd.w - it.w, y: it.body.position.y });
        });
      }
    }, 150);
  });

  }); // end requestAnimationFrame
})();
