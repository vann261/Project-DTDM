<?php
include 'config.php';
header('Content-Type: application/json');

$advisor_id = 1; // ID cố định để test
$method = $_SERVER['REQUEST_METHOD'];

try {
    // 1. LẤY DANH SÁCH KHUNG GIỜ (Để hiện bảng dưới form)
    if ($method === 'GET') {
        $stmt = $pdo->prepare("SELECT * FROM time_slots WHERE advisor_id = :id ORDER BY date DESC, start_time ASC");
        $stmt->execute(['id' => $advisor_id]);
        $slots = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($slots);
        exit;
    }

    // 2. THÊM MỚI KHUNG GIỜ (Code cũ của bạn)
    if ($method === 'POST') {
        $data = json_decode(file_get_contents('php://input'), true);
        
        if (!isset($data['date'], $data['start_time'], $data['end_time'])) {
            echo json_encode(['success' => false, 'message' => 'Thiếu dữ liệu']);
            exit;
        }

        $sql = "INSERT INTO time_slots (advisor_id, date, start_time, end_time, status) 
                VALUES (:advisor_id, :date, :start_time, :end_time, 'available')";
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            'advisor_id' => $advisor_id,
            'date'       => $data['date'],
            'start_time' => $data['start_time'],
            'end_time'   => $data['end_time']
        ]);

        echo json_encode(['success' => true, 'message' => 'Thêm thành công!']);
        exit;
    }

    // 3. XÓA KHUNG GIỜ
    if ($method === 'DELETE') {
        if (!isset($_GET['id'])) {
            echo json_encode(['success' => false, 'message' => 'Thiếu ID']);
            exit;
        }

        $slot_id = $_GET['id'];
        // Chỉ cho phép xóa nếu khung giờ đó chưa bị ai đặt (status = 'available')
        $stmt = $pdo->prepare("DELETE FROM time_slots WHERE slot_id = :slot_id AND advisor_id = :advisor_id AND status = 'available'");
        $stmt->execute(['slot_id' => $slot_id, 'advisor_id' => $advisor_id]);

        if ($stmt->rowCount() > 0) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Không thể xóa khung giờ đã được đặt hoặc không tồn tại']);
        }
        exit;
    }

} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
?>