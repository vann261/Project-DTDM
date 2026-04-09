<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

// Tắt hiển thị lỗi để không làm hỏng JSON
error_reporting(0); 
ini_set('display_errors', 0);

include 'config.php'; 

try {
    $data = [];

    // 1. Tổng lịch hẹn
    $stmt1 = $pdo->query("SELECT COUNT(*) as total FROM appointments");
    $data['total_appointments'] = (int)$stmt1->fetch()['total'];

    // 2. Tổng sinh viên (Dựa trên cột role = 'student')
    $stmt2 = $pdo->query("SELECT COUNT(*) as total FROM users WHERE role = 'student'");
    $data['total_students'] = (int)$stmt2->fetch()['total'];

    // 3. Tổng cố vấn
    $stmt3 = $pdo->query("SELECT COUNT(*) as total FROM advisors");
    $data['total_advisors'] = (int)$stmt3->fetch()['total'];

    echo json_encode($data);

} catch (PDOException $e) {
    echo json_encode(["error" => true, "message" => $e->getMessage()]);
}
?>