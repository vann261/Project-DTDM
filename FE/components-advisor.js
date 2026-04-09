/**
 * EduConsult – components-advisor.js
 * Header/Footer riêng cho trang cố vấn.
 * Không ảnh hưởng gì tới components.js của sinh viên.
 *
 * Dùng: <script src="components-advisor.js"></script>
 */

(async function () {

  // ── 1. Inject CSS riêng cho advisor header ───────────────────
  const style = document.createElement('style');
  style.textContent = `
    /* ══ ADV HEADER — tông xanh giống sinh viên ══ */
    .adv-header {
      position: sticky; top: 0; z-index: 100;
      background: rgba(30, 58, 138, 0.95);
      backdrop-filter: blur(14px);
      border-bottom: 1.5px solid #1e3a8a;
      box-shadow: 0 2px 16px rgba(30,58,138,0.25);
    }
    .adv-header__inner {
      display: flex; align-items: center; gap: 20px;
      padding: 0 32px; height: 64px;
      max-width: 1400px; margin: 0 auto; width: 100%;
    }

    /* Logo */
    .adv-header__logo {
      display: flex; align-items: center; gap: 10px;
      text-decoration: none; flex-shrink: 0;
    }
    .adv-header__logo-icon {
      width: 36px; height: 36px; border-radius: 10px;
      background: linear-gradient(135deg, #93c5fd, #3b82f6);
      display: flex; align-items: center; justify-content: center;
      font-size: 18px; box-shadow: 0 2px 8px rgba(59,130,246,0.28);
    }
    .adv-header__logo-text {
      font-size: 16px; font-weight: 800;
      color: #ffffff; letter-spacing: -0.02em; line-height: 1.15;
    }
    .adv-header__logo-text span {
      display: block; font-size: 10px; font-weight: 400;
      color: #93c5fd; letter-spacing: 0.02em;
    }

    /* Nav */
    .adv-header__nav {
      display: flex; align-items: center; gap: 2px;
      flex: 1; justify-content: center;
    }
    .adv-header__nav a {
      display: inline-flex; align-items: center; gap: 7px;
      font-size: 13.5px; font-weight: 600; color: #bfdbfe;
      text-decoration: none; padding: 7px 14px;
      border-radius: 8px; transition: all 0.18s; white-space: nowrap;
    }
    .adv-header__nav a:hover { color: #ffffff; background: rgba(255,255,255,0.12); }
    .adv-header__nav a.active { color: #ffffff; background: rgba(255,255,255,0.12); }
    .adv-header__nav a svg { opacity: 0.7; }

    /* Right */
    .adv-header__right {
      display: flex; align-items: center; gap: 14px; flex-shrink: 0;
    }

    /* Badge cố vấn */
    .adv-role-badge {
      display: flex; align-items: center; gap: 7px;
      background: rgba(34,197,94,0.15); border: 1px solid rgba(34,197,94,0.3);
      border-radius: 999px; padding: 5px 14px;
      font-size: 12px; font-weight: 600; color: #4ade80;
    }
    .adv-role-dot {
      width: 7px; height: 7px; border-radius: 50%;
      background: #22c55e; box-shadow: 0 0 6px #22c55e;
      animation: pulse-dot 2s infinite;
    }
    @keyframes pulse-dot { 0%,100%{opacity:1} 50%{opacity:0.4} }

    /* User button */
    .adv-user-btn {
      display: flex; align-items: center; gap: 9px;
      background: rgba(255,255,255,0.10);
      border: 1.5px solid rgba(255,255,255,0.20);
      border-radius: 12px; padding: 7px 12px 7px 8px;
      cursor: pointer; transition: all 0.2s; color: white; font-family: inherit;
    }
    .adv-user-btn:hover { background: rgba(255,255,255,0.18); border-color: rgba(255,255,255,0.35); }
    .adv-user-avatar {
      width: 30px; height: 30px; border-radius: 50%;
      background: linear-gradient(135deg, #60a5fa, #2563eb);
      display: flex; align-items: center; justify-content: center;
      font-size: 13px; font-weight: 800; color: white; flex-shrink: 0;
      border: 2px solid rgba(255,255,255,0.3);
    }
    .adv-user-info { text-align: left; line-height: 1.25; }
    .adv-user-name { display: block; font-size: 13px; font-weight: 700; color: #fff; white-space: nowrap; max-width: 120px; overflow: hidden; text-overflow: ellipsis; }
    .adv-user-role { display: block; font-size: 10.5px; font-weight: 400; color: rgba(255,255,255,0.65); }
    .adv-chevron   { color: rgba(255,255,255,0.6); flex-shrink: 0; transition: transform 0.25s; }
    .adv-chevron.open { transform: rotate(180deg); }

    /* Dropdown */
    .adv-dropdown {
      position: absolute; top: calc(100% + 10px); right: 0; width: 230px;
      background: white; border-radius: 18px;
      box-shadow: 0 16px 48px rgba(30,58,138,0.18), 0 2px 8px rgba(0,0,0,0.08);
      border: 1px solid #e8f0ff; overflow: hidden;
      opacity: 0; visibility: hidden;
      transform: translateY(-8px) scale(0.97);
      transition: all 0.22s cubic-bezier(0.34,1.3,0.64,1);
      z-index: 9999;
    }
    .adv-dropdown.open { opacity: 1; visibility: visible; transform: translateY(0) scale(1); }

    .adv-dropdown__header {
      display: flex; align-items: center; gap: 12px; padding: 16px 18px;
      background: linear-gradient(135deg, #eff6ff, #dbeafe);
      border-bottom: 1px solid #dbeafe;
    }
    .adv-dropdown__avatar {
      width: 40px; height: 40px; border-radius: 50%;
      background: linear-gradient(135deg, #60a5fa, #2563eb);
      display: flex; align-items: center; justify-content: center;
      font-size: 16px; font-weight: 800; color: white; flex-shrink: 0;
    }
    .adv-dropdown__name { font-size: 14px; font-weight: 800; color: #1e3a8a; }
    .adv-dropdown__role { font-size: 11px; color: #2563eb; margin-top: 2px; font-weight: 600; background: #dbeafe; border: 1px solid #bfdbfe; border-radius: 999px; padding: 1px 8px; display: inline-block; }

    .adv-dropdown__body { padding: 8px; }
    .adv-dropdown__item {
      display: flex; align-items: center; gap: 10px;
      padding: 10px 12px; border-radius: 10px;
      font-size: 13.5px; font-weight: 600; color: #1e3a8a;
      text-decoration: none; transition: background 0.15s;
    }
    .adv-dropdown__item:hover { background: #f0f7ff; }

    .adv-dropdown__footer { padding: 8px; border-top: 1px solid #f0f4ff; }
    .adv-dropdown__logout {
      display: flex; align-items: center; gap: 10px;
      padding: 10px 12px; border-radius: 10px;
      font-size: 13px; font-weight: 700; color: #dc2626;
      text-decoration: none; transition: background 0.15s;
    }
    .adv-dropdown__logout:hover { background: #fef2f2; }

    /* Hamburger */
    .adv-hamburger {
      display: none; flex-direction: column;
      justify-content: center; gap: 5px;
      background: none; border: none; cursor: pointer; padding: 6px;
    }
    .adv-hamburger span { display: block; width: 22px; height: 2px; background: #f1f5f9; border-radius: 2px; }

    /* Mobile */
    @media (max-width: 768px) {
      .adv-header__inner { padding: 0 16px; }
      .adv-header__nav {
        display: none; flex-direction: column;
        position: absolute; top: 64px; left: 0; right: 0;
        background: #1e3a8a; border-bottom: 1.5px solid #1d4ed8;
        padding: 12px 16px 20px; box-shadow: 0 8px 24px rgba(0,0,0,0.2);
      }
      .adv-header__nav.is-open { display: flex; }
      .adv-header__nav a { width: 100%; padding: 10px 14px; }
      .adv-hamburger { display: flex; }
      .adv-role-badge { display: none; }
    }

    /* ══ ADV FOOTER ══ */
    .adv-footer { background: #1e3a8a; margin-top: auto; }
    .adv-footer__inner {
      max-width: 1400px; margin: 0 auto; padding: 18px 32px;
      display: flex; align-items: center; justify-content: space-between;
      flex-wrap: wrap; gap: 10px;
    }
    .adv-footer__brand { display: flex; align-items: center; gap: 8px; font-size: 13px; }
    .adv-footer__icon  { font-size: 16px; }
    .adv-footer__name  { font-weight: 700; color: #fff; }
    .adv-footer__sep   { color: #3b82f6; }
    .adv-footer__sub   { color: #93c5fd; font-size: 12px; }
    .adv-footer__copy  { font-size: 12px; color: #93c5fd; }
  `;
  document.head.appendChild(style);

  // ── 2. Load components-advisor.html ─────────────────────────
  let doc;
  try {
    const res  = await fetch('components-advisor.html');
    const html = await res.text();
    doc = new DOMParser().parseFromString(html, 'text/html');
  } catch (e) {
    console.error('[Advisor] Không thể load components-advisor.html:', e);
    return;
  }

  function getTemplate(id) {
    const tpl = doc.getElementById(id);
    if (!tpl) return null;
    return tpl.content ? tpl.content.cloneNode(true)
      : document.createRange().createContextualFragment(tpl.innerHTML);
  }

  // ── 3. Inject HEADER ────────────────────────────────────────
  const headerSlot = document.getElementById('site-header');
  if (headerSlot) {
    const node = getTemplate('tpl-header-advisor');
    if (node) {
      headerSlot.replaceWith(node);

      // Active nav link
      const currentFile = location.pathname.split('/').pop() || 'advisor_dashboard.html';
      document.querySelectorAll('.adv-header__nav a').forEach(link => {
        if ((link.getAttribute('href') || '') === currentFile) link.classList.add('active');
      });

      // Hamburger
      const hamburger = document.getElementById('hamburgerBtn');
      const nav       = document.getElementById('mainNav');
      if (hamburger && nav) {
        hamburger.addEventListener('click', () => nav.classList.toggle('is-open'));
        nav.querySelectorAll('a').forEach(l => l.addEventListener('click', () => nav.classList.remove('is-open')));
      }

      // ── Check session ──────────────────────────────────────
      try {
        const res  = await fetch('../BE/auth/check_session.php');
        const data = await res.json();

        const userWrap = document.getElementById('header-user-info');

        if (data.logged_in && data.role === 'advisor') {
          if (userWrap) userWrap.style.display = 'flex';

          // Điền tên
          const elName = document.getElementById('header-user-name');
          const elRole = document.getElementById('header-user-role');
          if (elName) elName.textContent = data.name;
          if (elRole) elRole.textContent = 'Cố vấn học tập';

          // Avatar chữ cái đầu
          const initial = data.name?.trim().charAt(0).toUpperCase() || '?';
          const avatarEl   = document.getElementById('header-user-avatar');
          const dropAvatar = document.getElementById('dropdown-avatar');
          const dropName   = document.getElementById('dropdown-user-name');
          if (avatarEl)   avatarEl.textContent   = initial;
          if (dropAvatar) dropAvatar.textContent = initial;
          if (dropName)   dropName.textContent   = data.name;

          // Dropdown toggle
          const trigger  = document.getElementById('userMenuTrigger');
          const dropdown = document.getElementById('userDropdown');
          const chevron  = document.getElementById('userMenuChevron');

          if (trigger && dropdown) {
            trigger.addEventListener('click', e => {
              e.stopPropagation();
              const isOpen = dropdown.classList.toggle('open');
              chevron?.classList.toggle('open', isOpen);
            });
            document.addEventListener('click', e => {
              if (!trigger.contains(e.target) && !dropdown.contains(e.target)) {
                dropdown.classList.remove('open');
                chevron?.classList.remove('open');
              }
            });
            document.addEventListener('keydown', e => {
              if (e.key === 'Escape') {
                dropdown.classList.remove('open');
                chevron?.classList.remove('open');
              }
            });
          }

        } else if (!data.logged_in) {
          window.location.href = 'dangnhap.html';
        } else {
          // Đăng nhập nhưng không phải advisor → về trang chủ
          window.location.href = 'index.html';
        }

      } catch (e) {
        console.warn('[Advisor] Không thể kiểm tra session:', e);
      }
    }
  }

  // ── 4. Inject FOOTER ────────────────────────────────────────
  const footerSlot = document.getElementById('site-footer');
  if (footerSlot) {
    const node = getTemplate('tpl-footer-advisor');
    if (node) footerSlot.replaceWith(node);
  }

})();