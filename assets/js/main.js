/**
 * Elite Tech Precision — automaticdoorrepair.ie
 * Single Page JavaScript
 */
(function () {
  'use strict';

  /* ── 1. Sticky header ──────────────────────────────── */
  const header = document.getElementById('siteHeader');
  if (header) {
    window.addEventListener('scroll', () => {
      header.classList.toggle('scrolled', window.scrollY > 40);
    }, { passive: true });
  }

  /* ── 2. Hamburger / mobile nav ──────────────────────── */
  const hamburger     = document.getElementById('hamburger');
  const mobileNav     = document.getElementById('mobileNav');
  const mobileOverlay = document.getElementById('mobileOverlay');

  function openNav() {
    hamburger.classList.add('open');
    hamburger.setAttribute('aria-expanded', 'true');
    mobileNav.classList.add('open');
    mobileNav.setAttribute('aria-hidden', 'false');
    mobileOverlay.classList.add('open');
    document.body.style.overflow = 'hidden';
  }
  function closeNav() {
    hamburger.classList.remove('open');
    hamburger.setAttribute('aria-expanded', 'false');
    mobileNav.classList.remove('open');
    mobileNav.setAttribute('aria-hidden', 'true');
    mobileOverlay.classList.remove('open');
    document.body.style.overflow = '';
  }

  if (hamburger) hamburger.addEventListener('click', () => {
    mobileNav.classList.contains('open') ? closeNav() : openNav();
  });
  if (mobileOverlay) mobileOverlay.addEventListener('click', closeNav);

  /* Close mobile nav on regular link click */
  document.querySelectorAll('.mob-link, .mob-dropdown a').forEach(link => {
    link.addEventListener('click', closeNav);
  });

  /* ── 3. Mobile accordion dropdowns ─────────────────── */
  document.querySelectorAll('.mob-dropdown-toggle').forEach(btn => {
    btn.addEventListener('click', () => {
      const expanded = btn.getAttribute('aria-expanded') === 'true';
      // close all first
      document.querySelectorAll('.mob-dropdown-toggle').forEach(b => {
        b.setAttribute('aria-expanded', 'false');
        const s = b.nextElementSibling;
        if (s) s.classList.remove('open');
      });
      // toggle clicked one
      if (!expanded) {
        btn.setAttribute('aria-expanded', 'true');
        const sub = btn.nextElementSibling;
        if (sub) sub.classList.add('open');
      }
    });
  });

  /* ── 4. Smooth scroll for anchor links ──────────────── */
  document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function (e) {
      const target = document.querySelector(this.getAttribute('href'));
      if (!target) return;
      e.preventDefault();
      const offset = header ? header.offsetHeight + 8 : 130;
      const top = target.getBoundingClientRect().top + window.scrollY - offset;
      window.scrollTo({ top, behavior: 'smooth' });
    });
  });

  /* ── 5. Active nav highlight on scroll ──────────────── */
  const sections = document.querySelectorAll('section[id], div[id]');
  const navLinks = document.querySelectorAll('.main-nav-links a[href^="#"]');

  function setActiveNav() {
    let current = '';
    const scrollY = window.scrollY + (header ? header.offsetHeight + 20 : 130);
    sections.forEach(sec => {
      if (sec.offsetTop <= scrollY) current = sec.id;
    });
    navLinks.forEach(link => {
      link.classList.toggle('active', link.getAttribute('href') === '#' + current);
    });
  }
  window.addEventListener('scroll', setActiveNav, { passive: true });
  setActiveNav();

  /* ── 6. Back to top ─────────────────────────────────── */
  const btt = document.getElementById('backToTop');
  if (btt) {
    window.addEventListener('scroll', () => {
      btt.classList.toggle('visible', window.scrollY > 500);
    }, { passive: true });
    btt.addEventListener('click', () => window.scrollTo({ top: 0, behavior: 'smooth' }));
  }

  /* ── 7. Fade-up on scroll ───────────────────────────── */
  const fadeEls = document.querySelectorAll('.fade-up');
  if ('IntersectionObserver' in window && fadeEls.length) {
    const obs = new IntersectionObserver((entries) => {
      entries.forEach((e, i) => {
        if (!e.isIntersecting) return;
        setTimeout(() => e.target.classList.add('in-view'), i * 80);
        obs.unobserve(e.target);
      });
    }, { threshold: 0.1 });
    fadeEls.forEach(el => obs.observe(el));
  } else {
    fadeEls.forEach(el => el.classList.add('in-view'));
  }

  /* ── 8. Counter animation ───────────────────────────── */
  const counters = document.querySelectorAll('[data-count]');
  if ('IntersectionObserver' in window && counters.length) {
    const cObs = new IntersectionObserver((entries) => {
      entries.forEach(e => {
        if (!e.isIntersecting) return;
        const el     = e.target;
        const target = parseInt(el.dataset.count, 10);
        const suffix = el.dataset.suffix || '';
        const dur    = 1800;
        const step   = target / (dur / 16);
        let cur      = 0;
        const tick = () => {
          cur += step;
          if (cur >= target) { el.textContent = target + suffix; return; }
          el.textContent = Math.floor(cur) + suffix;
          requestAnimationFrame(tick);
        };
        requestAnimationFrame(tick);
        cObs.unobserve(el);
      });
    }, { threshold: 0.4 });
    counters.forEach(c => cObs.observe(c));
  }

  /* ── 9. Contact form inline validation ─────────────── */
  const form = document.getElementById('contactForm');
  if (form) {
    form.addEventListener('submit', function (e) {
      let ok = true;
      form.querySelectorAll('[required]').forEach(f => {
        const invalid = !f.value.trim();
        f.style.borderColor = invalid ? '#dc3545' : '';
        if (invalid) ok = false;
      });
      const emailF = form.querySelector('[type="email"]');
      if (emailF && emailF.value && !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(emailF.value)) {
        emailF.style.borderColor = '#dc3545';
        ok = false;
      }
      if (!ok) e.preventDefault();
    });
    form.querySelectorAll('input, select, textarea').forEach(f => {
      f.addEventListener('input', () => { f.style.borderColor = ''; });
    });
  }

})();
