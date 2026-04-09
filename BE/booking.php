<?php
header('Content-Type: application/json; charset=utf-8');
error_reporting(0);

require_once 'config.php';
require_once 'functions.php';

startSession();

// Phải đăng nhập mới được đặt lịch
if (!isLoggedIn()) {
    jsonResponse(false, 'Vui lòng đăng nhập để đặt lịch.');
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    jsonResponse(false, 'Phương thức không hợp lệ.');
}

$advisor_id = intval($_POST['advisor_id'] ?? 0);
$slot_id    = intval($_POST['slot_id']    ?? 0);
$topic      = sanitize($_POST['topic']    ?? '');

if (!$advisor_id || !$slot_id || !$topic) {
    jsonResponse(false, 'Vui lòng điền đầy đủ thông tin.');
}

$student_id = $_SESSION['user_id'];

try {
    // Kiểm tra slot còn available không
    $stmt = $pdo->prepare("SELECT slot_id FROM time_slots WHERE slot_id = :slot_id AND status = 'available'");
    $stmt->execute([':slot_id' => $slot_id]);
    if (!$stmt->fetch()) {
        jsonResponse(false, 'Khung giờ này đã được đặt hoặc không còn trống.');
    }

    // Kiểm tra sinh viên chưa đặt slot này chưa
    $stmt = $pdo->prepare("SELECT appointment_id FROM appointments WHERE student_id = :sid AND slot_id = :slot_id");
    $stmt->execute([':sid' => $student_id, ':slot_id' => $slot_id]);
    if ($stmt->fetch()) {
        jsonResponse(false, 'Bạn đã đặt khung giờ này rồi.');
    }

    // Bắt đầu transaction
    $pdo->beginTransaction();

    // Lưu appointment
    $stmt = $pdo->prepare("
        INSERT INTO appointments (student_id, advisor_id, slot_id, topic, status)
        VALUES (:student_id, :advisor_id, :slot_id, :topic, 'pending')
    ");
    $stmt->execute([
        ':student_id' => $student_id,
        ':advisor_id' => $advisor_id,
        ':slot_id'    => $slot_id,
        ':topic'      => $topic,
    ]);

    // Cập nhật slot thành booked
    $stmt = $pdo->prepare("UPDATE time_slots SET status = 'booked' WHERE slot_id = :slot_id");
    $stmt->execute([':slot_id' => $slot_id]);

    $pdo->commit();

    jsonResponse(true, 'Đặt lịch thành công! Chờ cố vấn xác nhận.');

} catch (PDOException $e) {
    $pdo->rollBack();
    error_log('Lỗi booking: ' . $e->getMessage());
    jsonResponse(false, 'Lỗi máy chủ, vui lòng thử lại.');
}