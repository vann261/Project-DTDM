<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
session_start();

include 'config.php';

// Giả sử ID cố vấn được lưu trong session khi đăng nhập
// Nếu chưa có session, bạn có thể tạm thay bằng một ID cụ thể để test (VD: $advisor_id = 3;)
$advisor_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 0;

try {
    $data = [];

    // 1. Đếm lịch hẹn Chờ xác nhận (Giả sử status = 'pending')
    $stmt1 = $pdo->prepare("SELECT COUNT(*) as total FROM appointments WHERE advisor_id = ? AND status = 'pending'");
    $stmt1->execute([$advisor_id]);
    $data['pending_count'] = (int)$stmt1->fetch()['total'];

    // 2. Đếm lịch hẹn Đã xác nhận (Giả sử status = 'confirmed')
    $stmt2 = $pdo->prepare("SELECT COUNT(*) as total FROM appointments WHERE advisor_id = ? AND status = 'confirmed'");
    $stmt2->execute([$advisor_id]);
    $data['confirmed_count'] = (int)$stmt2->fetch()['total'];

    // 3. Tổng sinh viên đã từng đặt lịch với cố vấn này
    $stmt3 = $pdo->prepare("SELECT COUNT(DISTINCT student_id) as total FROM appointments WHERE advisor_id = ?");
    $stmt3->execute([$advisor_id]);
    $data['total_students'] = (int)$stmt3->fetch()['total'];

    // 4. Lịch hẹn hôm nay
    $today = date('Y-m-d');
    $stmt4 = $pdo->prepare("SELECT COUNT(*) as total FROM appointments WHERE advisor_id = ? AND appointment_date = ?");
    $stmt4->execute([$advisor_id, $today]);
    $data['today_count'] = (int)$stmt4->fetch()['total'];

    echo json_encode($data);

} catch (PDOException $e) {
    echo json_encode(["error" => true, "message" => $e->getMessage()]);
}
?>