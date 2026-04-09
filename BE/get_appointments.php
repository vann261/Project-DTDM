<?php
header('Content-Type: application/json');
include 'config.php'; // Hoặc file kết nối DB của bạn

// Lấy advisor_id từ session hoặc query string
$advisor_id = isset($_GET['advisor_id']) ? $_GET['advisor_id'] : 0;

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Lấy lịch hẹn kèm thông tin (Sắp xếp mới nhất lên đầu)
    $stmt = $conn->prepare("SELECT id, student_name, student_email, booking_date, booking_time, subject, status 
                            FROM appointments 
                            WHERE advisor_id = ? 
                            ORDER BY created_at DESC");
    $stmt->execute([$advisor_id]);
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($data);
} catch(PDOException $e) {
    echo json_encode(["error" => $e->getMessage()]);
}
?>