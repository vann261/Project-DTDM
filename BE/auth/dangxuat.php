<?php
// ===================================
// XỬ LÝ ĐĂNG XUẤT
// ===================================
require_once '../functions.php';

startSession();

// Xóa toàn bộ session
$_SESSION = [];

// Xóa cookie session nếu có
if (ini_get('session.use_cookies')) {
    $params = session_get_cookie_params();
    setcookie(
        session_name(), '', time() - 42000,
        $params['path'], $params['domain'],
        $params['secure'], $params['httponly']
    );
}

session_destroy();

// Chuyển về trang đăng nhập
header('Location: ../../FE/dangnhap.html');
exit;