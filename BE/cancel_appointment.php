<?php
header('Content-Type: application/json; charset=utf-8');
error_reporting(0);

require_once 'config.php';
require_once 'functions.php';

startSession();

if (!isLoggedIn()) {
    jsonResponse(false, 'Vui lòng đăng nhập.');
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    jsonResponse(false, 'Phương thức không hợp lệ.');
}

$appointment_id = intval($_POST['appointment_id'] ?? 0);
$student_id     = $_SESSION['user_id'];

if (!$appointment_id) {
    jsonResponse(false, 'Thiếu thông tin lịch hẹn.');
}

try {
    // Kiểm tra lịch này có phải của sinh viên đang đăng nhập không
    $stmt = $pdo->prepare("
        SELECT ap.appointment_id, ap.slot_id, ap.status
        FROM appointments ap
        WHERE ap.appointment_id = :apid AND ap.student_id = :sid
    ");
    $stmt->execute([':apid' => $appointment_id, ':sid' => $student_id]);
    $ap = $stmt->fetch();

    if (!$ap) {
        jsonResponse(false, 'Không tìm thấy lịch hẹn.');
    }

    if ($ap['status'] === 'cancelled') {
        jsonResponse(false, 'Lịch này đã được hủy trước đó.');
    }

    $pdo->beginTransaction();

    // Cập nhật appointment thành cancelled
    $stmt = $pdo->prepare("UPDATE appointments SET status = 'cancelled' WHERE appointment_id = :apid");
    $stmt->execute([':apid' => $appointment_id]);

    // Trả slot về available
    $stmt = $pdo->prepare("UPDATE time_slots SET status = 'available' WHERE slot_id = :slot_id");
    $stmt->execute([':slot_id' => $ap['slot_id']]);

    $pdo->commit();

    jsonResponse(true, 'Hủy lịch thành công.');

} catch (PDOException $e) {
    $pdo->rollBack();
    error_log('Lỗi cancel: ' . $e->getMessage());
    jsonResponse(false, 'Lỗi máy chủ, vui lòng thử lại.');
}