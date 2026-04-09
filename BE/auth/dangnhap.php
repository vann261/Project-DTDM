<?php
header('Content-Type: application/json; charset=utf-8');

require_once '../config.php';
require_once '../functions.php';

startSession();

// Redirect map theo role
$redirectMap = [
    'student' => '/Project-DTDM/FE/index.html',
    'advisor' => '/Project-DTDM/FE/advisor_dashboard.html',
    'admin'   => '/Project-DTDM/FE/admin.html',
];

if (isLoggedIn()) {
    $role     = $_SESSION['user_role'] ?? 'student';
    $redirect = $redirectMap[$role] ?? '/Project-DTDM/FE/index.html';
    jsonResponse(true, 'Đã đăng nhập.', ['redirect' => $redirect]);
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    jsonResponse(false, 'Phương thức không hợp lệ.');
}

$email    = sanitize($_POST['email']    ?? '');
$password = $_POST['password']          ?? '';

if (!$email || !$password) {
    jsonResponse(false, 'Vui lòng nhập email và mật khẩu.');
}

try {
    $stmt = $pdo->prepare("SELECT id, name, email, password, role FROM users WHERE email = :email LIMIT 1");
    $stmt->execute([':email' => $email]);
    $user = $stmt->fetch();

    $ok = false;
    if ($user) {
        if (password_verify($password, $user['password'])) {
            $ok = true;
        } elseif ($user['password'] === $password) {
            $hashed = password_hash($password, PASSWORD_BCRYPT);
            $upd = $pdo->prepare("UPDATE users SET password = :pw WHERE id = :id");
            $upd->execute([':pw' => $hashed, ':id' => $user['id']]);
            $ok = true;
        }
    }

    if (!$ok) {
        jsonResponse(false, 'Email hoặc mật khẩu không đúng.');
    }

    session_regenerate_id(true);
    $_SESSION['user_id']   = $user['id'];
    $_SESSION['user_name'] = $user['name'];
    $_SESSION['user_role'] = $user['role'];
    $_SESSION['user']      = [
        'id'    => $user['id'],
        'name'  => $user['name'],
        'email' => $user['email'],
        'role'  => $user['role'],
    ];

    $redirect = $redirectMap[$user['role']] ?? '/Project-DTDM/FE/index.html';

    jsonResponse(true, 'Đăng nhập thành công!', [
        'name'     => $user['name'],
        'role'     => $user['role'],
        'redirect' => $redirect,
    ]);

} catch (PDOException $e) {
    error_log('Lỗi đăng nhập: ' . $e->getMessage());
    jsonResponse(false, 'Lỗi máy chủ, vui lòng thử lại.');
}