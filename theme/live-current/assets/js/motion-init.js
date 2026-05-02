(function () {
  'use strict';

  // ── PAGE LOAD ─────────────────────────────────────────────────────────────
  document.body.classList.add('loaded');

  // ── SCROLL PROGRESS BAR ───────────────────────────────────────────────────
  var bar = document.getElementById('scroll-bar');
  if (!bar) {
    bar = document.createElement('div');
    bar.id = 'scroll-bar';
    document.body.appendChild(bar);
  }
  window.addEventListener('scroll', function () {
    var pct = window.scrollY / (document.documentElement.scrollHeight - window.innerHeight);
    bar.style.transform = 'scaleX(' + Math.min(Math.max(pct, 0), 1) + ')';
  }, { passive: true });

  // ── NAV SCROLL STATE ─────────────────────────────────────────────────────
  var nav = document.getElementById('nav');
  if (nav) {
    window.addEventListener('scroll', function () {
      nav.classList.toggle('sc', window.scrollY > 60);
    }, { passive: true });
  }

  // ── HERO PARALLAX ────────────────────────────────────────────────────────
  var heroImg = document.querySelector('.hero-bg-img');
  if (heroImg) {
    window.addEventListener('scroll', function () {
      if (window.scrollY < window.innerHeight)
        heroImg.style.transform = 'translateY(' + (window.scrollY * 0.2) + 'px)';
    }, { passive: true });
  }

  // ── PAGE TRANSITION ──────────────────────────────────────────────────────
  document.addEventListener('click', function (e) {
    var a = e.target.closest('a[href]');
    if (!a) return;
    var href = a.getAttribute('href');
    if (!href || href[0] === '#' || /^(http|mailto|tel|javascript)/.test(href) || /wp-(admin|login)/.test(href)) return;
    e.preventDefault();
    document.body.style.opacity = '0';
    document.body.style.transition = 'opacity .18s ease';
    setTimeout(function () { window.location.href = href; }, 200);
  });

  // ── INTERSECTION OBSERVER ────────────────────────────────────────────────
  if (!('IntersectionObserver' in window)) {
    // Fallback: just show everything immediately
    document.querySelectorAll('[data-an]').forEach(function (el) {
      el.classList.add('is-vis');
    });
    return;
  }

  // Observer with a generous bottom margin so elements animate slightly before fully in view
  var io = new IntersectionObserver(function (entries) {
    entries.forEach(function (entry) {
      if (entry.isIntersecting) {
        entry.target.classList.add('is-vis');
        io.unobserve(entry.target); // fire once only
      }
    });
  }, { rootMargin: '0px 0px -60px 0px', threshold: 0.05 });

  function an(el, type, delay) {
    el.setAttribute('data-an', type || 'up');
    if (delay) el.style.setProperty('--ad', delay + 's');
    io.observe(el);
  }

  function stagger(selector, type, base, step) {
    document.querySelectorAll(selector).forEach(function (el, i) {
      an(el, type || 'up', (base || 0) + i * (step || 0.07));
    });
  }

  // ── HERO ─────────────────────────────────────────────────────────────────
  var heroCt = document.querySelector('.hero-ct');
  if (heroCt) {
    var heroH1 = heroCt.querySelector('h1');
    if (heroH1) an(heroH1, 'up', 0.05);
    var heroBadge = heroCt.querySelector('.hero-badge');
    if (heroBadge) an(heroBadge, 'up', 0);
    var heroSub = heroCt.querySelector('.hero-sub');
    if (heroSub) an(heroSub, 'up', 0.18);
    var heroBtns = heroCt.querySelector('.hero-btns');
    if (heroBtns) an(heroBtns, 'up', 0.28);
    var heroRight = heroCt.querySelector('div[style*="aspect-ratio"]') || (heroCt.querySelector('[style*="grid"]') && heroCt.querySelector('[style*="grid"]').children[1]);
    if (heroRight) an(heroRight, 'scale', 0.35);
  }

  // ── PAGE BANNER ───────────────────────────────────────────────────────────
  var pb = document.querySelector('.pb');
  if (pb) {
    pb.querySelectorAll('.pill, h1, p').forEach(function (el, i) {
      an(el, 'up', 0.08 + i * 0.1);
    });
  }

  // ── SECTION HEADINGS ──────────────────────────────────────────────────────
  document.querySelectorAll('h2.st').forEach(function (h2) { an(h2, 'up', 0); });
  document.querySelectorAll('.pill').forEach(function (p) { an(p, 'down', 0); });

  // ── STATS ─────────────────────────────────────────────────────────────────
  stagger('.stat-card', 'up', 0, 0.08);

  // ── TRAIN ─────────────────────────────────────────────────────────────────
  var ti = document.querySelector('.train-inner');
  if (ti) {
    if (ti.children[0]) an(ti.children[0], 'left', 0);
    if (ti.children[1]) an(ti.children[1], 'right', 0.1);
  }
  stagger('.train-d', 'up', 0.05, 0.1);

  // ── ABOUT ─────────────────────────────────────────────────────────────────
  document.querySelectorAll('.about-grid').forEach(function (grid) {
    var img = grid.querySelector('.about-img');
    if (img) an(img, 'left', 0);
    var txt = Array.from(grid.children).find(function (c) { return !c.classList.contains('about-img'); });
    if (txt) Array.from(txt.children).forEach(function (c, i) { an(c, 'right', 0.1 + i * 0.1); });
  });

  // ── FEATURE IMAGE GRID ────────────────────────────────────────────────────
  stagger('.img-hover-card', 'scale', 0, 0.08);

  // ── CTA BANNER ────────────────────────────────────────────────────────────
  var cta = document.querySelector('.cta');
  if (cta) an(cta, 'scale', 0);

  // ── PLAYER / CARD GRIDS ───────────────────────────────────────────────────
  // Player cards — use data-an scroll reveal with stagger
  stagger('.pcard', 'up', 0, 0.04);

  // Admin/Retired cards — slide in from left
  stagger('.ac', 'left', 0, 0.06);
  stagger('.cc', 'scale', 0, 0.08);
  stagger('.tier', 'up', 0, 0.1);
  stagger('.gi', 'scale', 0, 0.05);

  // ── PARTNER GRID ──────────────────────────────────────────────────────────
  stagger('.partner-slot', 'scale', 0, 0.03);

  // ── TABLE ROWS ────────────────────────────────────────────────────────────
  document.querySelectorAll('.ftb tbody tr, .htb tbody tr, .gtable tbody tr').forEach(function (row, i) {
    an(row, 'left', i * 0.04);
  });

  // ── FORM CARD ─────────────────────────────────────────────────────────────
  stagger('.fcard', 'up', 0, 0);

  // ── SONG ──────────────────────────────────────────────────────────────────
  var song = document.querySelector('.song');
  if (song) song.querySelectorAll('p').forEach(function (p, i) { an(p, 'up', i * 0.05); });

  // ── FOOTER ────────────────────────────────────────────────────────────────
  var footer = document.querySelector('.footer');
  if (footer) {
    footer.querySelectorAll('.ft-brand, .ft-col').forEach(function (col, i) {
      an(col, 'up', i * 0.07);
    });
  }

  // ── MARQUEE ───────────────────────────────────────────────────────────────
  stagger('.marquee-item', 'scale', 0, 0.03);

  // ── SPONSOR FEATS ─────────────────────────────────────────────────────────
  stagger('.sponsor-feat', 'up', 0, 0.1);

  // ── CONTEST / GAMES ───────────────────────────────────────────────────────
  stagger('.cc', 'up', 0, 0.08);

}());

// ── TILT EFFECT (home feature image cards) ────────────
document.querySelectorAll('.img-hover-card').forEach(function(card) {
  card.addEventListener('mousemove', function(e) {
    var r = card.getBoundingClientRect();
    var x = (e.clientX - r.left) / r.width  - 0.5;
    var y = (e.clientY - r.top)  / r.height - 0.5;
    card.style.transform = 'perspective(600px) rotateY(' + (x * 12) + 'deg) rotateX(' + (-y * 12) + 'deg) scale3d(1.03,1.03,1.03)';
    card.style.transition = 'transform .05s ease';
  });
  card.addEventListener('mouseleave', function() {
    card.style.transform = '';
    card.style.transition = 'transform .4s ease';
  });
});

// ── NEXT GAME countdown (games page) ─────────────────
(function() {
  var banner = document.querySelector('.ng-banner');
  if (!banner) return;
  var cdEl = banner.querySelector('.countdown');
  if (!cdEl) return;
  var raw = banner.getAttribute('data-date');
  if (!raw || raw.length < 8) return;
  // Parse as local time — replace T separator to space for cross-browser support
  var target = new Date(raw.replace('T', ' '));
  if (isNaN(target.getTime())) return;
  function tick() {
    var diff = target - new Date();
    if (diff <= 0) { cdEl.textContent = 'Game Day!'; return; }
    var d = Math.floor(diff / 864e5);
    var h = Math.floor((diff % 864e5) / 36e5);
    var m = Math.floor((diff % 36e5) / 6e4);
    cdEl.textContent = (d > 0 ? d + 'd ' : '') + h + 'h ' + m + 'm';
  }
  tick();
  setInterval(tick, 30000);
})();
