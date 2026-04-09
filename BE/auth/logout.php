<?php
// 1. Khởi động session để có thể thao tác
session_start();

// 2. Xóa sạch tất cả các biến session đã lưu
$_SESSION = array();

// 3. Nếu trình duyệt có sử dụng cookie cho session, hãy xóa nó đi (tăng tính bảo mật)
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// 4. Hủy bỏ hoàn toàn phiên làm việc (session) trên máy chủ
session_destroy();

/**
 * 5. Chuyển hướng về trang thông báo đăng xuất thành công
 * Giải thích đường dẫn ../../FE/logout.html:
 * - ../ đầu tiên: Thoát khỏi thư mục 'auth' để ra thư mục 'BE'
 * - ../ thứ hai: Thoát khỏi thư mục 'BE' để ra thư mục gốc dự án
 * - FE/logout.html: Đi vào thư mục 'FE' và mở file 'logout.html'
 */
header("Location: ../../FE/logout.html");
exit();
?>