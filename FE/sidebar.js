// FE/sidebar.js
document.addEventListener("DOMContentLoaded", function() {
    // 1. Chèn Header
    const headerHTML = `
        <header class="admin-header">
            <a href="admin.html" class="logo">🎓 EduConsult <span class="admin-badge">ADMIN PANEL</span></a>
            <div class="header-right">
                <div>Admin <small style="display:block; font-size:10px; opacity:0.7;">Quản trị hệ thống</small></div>
                <button class="btn-logout" onclick="location.href='../BE/logout.php'">Đăng xuất</button>
            </div>
        </header>`;

    // 2. Chèn Sidebar
    const sidebarHTML = `
        <div class="sidebar">
            <div class="sidebar-label">Hệ thống</div>
            <a href="admin.html" id="nav-admin"><span></span> Tổng quan</a>
            <div class="sidebar-label">Quản lý chính</div>
            <a href="manage_schedule.html" id="nav-schedule"><span></span> Lịch hẹn</a>
            <a href="manage_covan.html" id="nav-covan"><span></span> Cố vấn</a>
            <a href="students.html" id="nav-students"><span></span> Sinh viên</a>
        </div>`;

    // Chèn vào đầu body (trước các nội dung khác)
    document.body.insertAdjacentHTML('afterbegin', headerHTML + sidebarHTML);

    // 3. Tự động xử lý class "active" dựa trên URL hiện tại
    const currentPage = window.location.pathname.split("/").pop();
    const navMapping = {
        'admin.html': 'nav-admin',
        'manage_schedule.html': 'nav-schedule',
        'manage_covan.html': 'nav-covan',
        'students.html': 'nav-students'
    };

    const activeId = navMapping[currentPage];
    if (activeId) {
        document.getElementById(activeId).classList.add('active');
    }
});