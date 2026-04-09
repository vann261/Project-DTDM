/**
 * EduConsult – components.js
 * Tự động load header & footer từ components.html vào mọi trang.
 *
 * Cách dùng: Thêm 1 dòng này vào cuối <body> của bất kỳ trang nào:
 *   <script src="components.js"></script>
 *
 * Trang cần có sẵn 2 thẻ placeholder:
 *   <div id="site-header"></div>   ← header sẽ được inject vào đây
 *   <div id="site-footer"></div>   ← footer sẽ được inject vào đây
 */

(async function () {
  // ── 1. Load file components.html ────────────────────────────
  let doc;
  try {
    const res = await fetch('components.html');
    const html = await res.text();
    const parser = new DOMParser();
    doc = parser.parseFromString(html, 'text/html');
  } catch (e) {
    console.error('[EduConsult] Không thể load components.html:', e);
    return;
  }

  // ── 2. Helper: lấy nội dung từ <template id="..."> ──────────
  function getTemplate(id) {
    const tpl = doc.getElementById(id);
    if (!tpl) return null;
    return tpl.content
      ? tpl.content.cloneNode(true)          // browser hỗ trợ <template>
      : document.createRange()               // fallback
          .createContextualFragment(tpl.innerHTML);
  }

  // ── 3. Inject HEADER ────────────────────────────────────────
  const headerSlot = document.getElementById('site-header');
  if (headerSlot) {
    const headerNode = getTemplate('tpl-header');
    if (headerNode) {
      headerSlot.replaceWith(headerNode);

      // Đánh dấu active link theo tên file hiện tại
      const currentFile = location.pathname.split('/').pop() || 'index.html';
      document.querySelectorAll('.site-header__nav a').forEach(link => {
        const href = link.getAttribute('href') || '';
        if (href === currentFile || (currentFile === '' && href === 'index.html')) {
          link.classList.add('active');
        }
      });

      // Hamburger menu toggle
      const hamburger = document.getElementById('hamburgerBtn');
      const nav = document.getElementById('mainNav');
      if (hamburger && nav) {
        hamburger.addEventListener('click', () => {
          nav.classList.toggle('is-open');
          hamburger.setAttribute('aria-expanded', nav.classList.contains('is-open'));
        });
        nav.querySelectorAll('a').forEach(link =>
          link.addEventListener('click', () => nav.classList.remove('is-open'))
        );
      }

      // ── Kiểm tra session → cập nhật header ──────────────────
      try {
        const res  = await fetch('../BE/auth/check_session.php');
        const data = await res.json();

        const guestBtn  = document.getElementById('header-cta-guest');
        const userInfo  = document.getElementById('header-user-info');
        const userName  = document.getElementById('header-user-name');
        const userRole  = document.getElementById('header-user-role');

        const roleLabel = { student: 'Sinh viên', advisor: 'Cố vấn', admin: 'Quản trị viên' };

        if (data.logged_in) {
          // Đã đăng nhập → ẩn nút đăng ký, hiện tên + dropdown
          if (guestBtn) guestBtn.style.display = 'none';
          if (userInfo) userInfo.style.display = 'flex';
          if (userName) { userName.textContent = data.name; userName.style.color = '#ffffff'; }
          if (userRole) { userRole.textContent = roleLabel[data.role] ?? data.role; userRole.style.color = 'rgba(255,255,255,0.75)'; }

          // Cập nhật dropdown
          const ddName   = document.getElementById('dropdown-user-name');
          const ddRole   = document.getElementById('dropdown-user-role');
          const ddAvatar = document.getElementById('dropdown-avatar');
          if (ddName)   ddName.textContent   = data.name;
          if (ddRole)   ddRole.textContent   = roleLabel[data.role] ?? data.role;
          if (ddAvatar) ddAvatar.textContent = data.name.charAt(0).toUpperCase();

          // Ẩn card CTA khi đã login (index.html)
          const ctaCard = document.querySelector('.card-cta');
          if (ctaCard) {
            ctaCard.style.display = 'none';
            const grid = document.querySelector('.bottom-grid');
            if (grid) grid.style.gridTemplateColumns = '1fr 1fr';
          }
        } else {
          // Chưa đăng nhập → hiện nút Đăng ký, ẩn tên
          if (guestBtn) guestBtn.style.display = '';
          if (userInfo) userInfo.style.display = 'none';
        }
      } catch (e) {
        // Không kết nối được BE → giữ nguyên giao diện guest
        console.warn('[EduConsult] Không thể kiểm tra session:', e);
      }
    }
  }

  // ── 4. Inject FOOTER ────────────────────────────────────────
  const footerSlot = document.getElementById('site-footer');
  if (footerSlot) {
    const footerNode = getTemplate('tpl-footer');
    if (footerNode) footerSlot.replaceWith(footerNode);
  }
})();

// ── Toggle dropdown user menu ────────────────────────────
// Dùng event delegation vì #user-menu-btn được inject sau
document.addEventListener('click', function(e) {
  const btn      = document.getElementById('user-menu-btn');
  const dropdown = document.getElementById('user-dropdown');

  if (!dropdown) return;

  // Nếu click vào nút tên → toggle
  if (btn && btn.contains(e.target)) {
    const isOpen = dropdown.style.display === 'block';
    dropdown.style.display = isOpen ? 'none' : 'block';
    return;
  }

  // Nếu click ra ngoài → đóng
  if (!dropdown.contains(e.target)) {
    dropdown.style.display = 'none';
  }
});