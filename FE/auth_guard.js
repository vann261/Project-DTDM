/**
 * EduConsult – auth_guard.js
 * Bảo vệ trang theo role. Nhúng vào đầu <body> của trang cần phân quyền.
 *
 * Cách dùng:
 *   <script src="auth_guard.js?role=advisor"></script>   ← chỉ cho advisor vào
 *   <script src="auth_guard.js?role=student"></script>   ← chỉ cho student vào
 *   <script src="auth_guard.js?role=any"></script>       ← cần đăng nhập, bất kỳ role
 */
(async function () {
    const params       = new URLSearchParams(document.currentScript?.src.split('?')[1] || '');
    const requiredRole = params.get('role') || 'any';

    try {
        const res  = await fetch('../BE/auth/check_session.php');
        const data = await res.json();

        if (!data.logged_in) {
            // Chưa đăng nhập → về trang đăng nhập
            window.location.href = 'dangnhap.html?redirect=' + encodeURIComponent(window.location.href);
            return;
        }

        if (requiredRole !== 'any' && data.role !== requiredRole) {
            // Sai role → về trang phù hợp với role của họ
            const roleHome = {
                student: 'index.html',
                advisor: 'advisor-dashboard.html',
                admin:   'admin.html',
            };
            window.location.href = roleHome[data.role] ?? 'index.html';
        }

    } catch (e) {
        // Không check được → cho qua, trang sẽ tự xử lý
        console.warn('[AuthGuard] Không thể kiểm tra session:', e);
    }
})();