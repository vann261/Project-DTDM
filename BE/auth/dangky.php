<?php
header('Content-Type: application/json; charset=utf-8');

require_once '../config.php';   // $pdo
require_once '../functions.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    jsonResponse(false, 'Phương thức không hợp lệ.');
}

$name     = sanitize($_POST['name']             ?? '');
$email    = sanitize($_POST['email']            ?? '');
$password = $_POST['password']                  ?? '';
$confirm  = $_POST['confirm_password']          ?? '';

if (!$name || !$email || !$password || !$confirm) {
    jsonResponse(false, 'Vui lòng điền đầy đủ thông tin.');
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    jsonResponse(false, 'Email không hợp lệ.');
}

if (strlen($password) < 6) {
    jsonResponse(false, 'Mật khẩu phải có ít nhất 6 ký tự.');
}

if ($password !== $confirm) {
    jsonResponse(false, 'Mật khẩu xác nhận không khớp.');
}

try {
    $stmt = $pdo->prepare("SELECT id FROM users WHERE email = :email");
    $stmt->execute([':email' => $email]);
    if ($stmt->fetch()) {
        jsonResponse(false, 'Email này đã được sử dụng.');
    }

    $hashed = password_hash($password, PASSWORD_BCRYPT);

    $stmt = $pdo->prepare("
        INSERT INTO users (name, email, password, role, rank_id)
        VALUES (:name, :email, :password, 'student', 2)
    ");
    $stmt->execute([
        ':name'     => $name,
        ':email'    => $email,
        ':password' => $hashed,
    ]);

    jsonResponse(true, 'Đăng ký thành công! Vui lòng đăng nhập.');

} catch (PDOException $e) {
    error_log('Lỗi đăng ký: ' . $e->getMessage());
    jsonResponse(false, 'Lỗi máy chủ, vui lòng thử lại.');
}