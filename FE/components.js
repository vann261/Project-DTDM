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
    }
  }

  // ── 4. Inject FOOTER ────────────────────────────────────────
  const footerSlot = document.getElementById('site-footer');
  if (footerSlot) {
    const footerNode = getTemplate('tpl-footer');
    if (footerNode) footerSlot.replaceWith(footerNode);
  }
})();