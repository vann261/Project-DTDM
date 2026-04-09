<?php
// Bật session để lấy ID người đăng nhập
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once 'config.php'; // Đảm bảo đường dẫn này đúng với file config của bạn
header('Content-Type: application/json; charset=utf-8');
error_reporting(0);

// Kiểm tra quyền truy cập
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['error' => 'Chưa đăng nhập']);
    exit;
}

// LẤY ID TỪ SESSION (Đây là mấu chốt để hiện đúng TS. Lê Yên Thanh)
$user_id = $_SESSION['user_id']; 

try {
    // --- XỬ LÝ CẬP NHẬT TRẠNG THÁI (DUYỆT/HỦY) ---
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $input = json_decode(file_get_contents('php://input'), true);
        
        if (isset($input['appointment_id']) && isset($input['new_status'])) {
            // Chỉ cập nhật nếu lịch hẹn đó đúng là của cố vấn này (dùng user_id làm advisor_id)
            $stmt = $pdo->prepare("UPDATE appointments SET status = :status WHERE appointment_id = :id AND advisor_id = :adv_id");
            $success = $stmt->execute([
                'status' => $input['new_status'],
                'id' => $input['appointment_id'],
                'adv_id' => $user_id
            ]);
            
            echo json_encode(['success' => $success]);
            exit;
        }
    }

    // --- XỬ LÝ LẤY DỮ LIỆU ĐỔ RA DASHBOARD (GET) ---
    $res = [];

    // 1. Lấy đúng tên từ bảng advisors dựa trên user_id của Session
    $stmt = $pdo->prepare("SELECT full_name FROM advisors WHERE user_id = :uid LIMIT 1");
    $stmt->execute(['uid' => $user_id]);
    $advisor = $stmt->fetch(PDO::FETCH_ASSOC);
    
    // Ưu tiên lấy full_name trong bảng advisors, nếu không có thì lấy tên ở bảng users
    $res['advisor_name'] = $advisor['full_name'] ?? ($_SESSION['user_name'] ?? "Cố vấn");

    // 2. Thống kê (Chỉ lấy của cố vấn đang đăng nhập)
    // Chờ duyệt
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM appointments WHERE advisor_id = :id AND status = 'pending'");
    $stmt->execute(['id' => $user_id]);
    $res['pending'] = $stmt->fetchColumn();

    // Đã xác nhận
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM appointments WHERE advisor_id = :id AND status = 'confirmed'");
    $stmt->execute(['id' => $user_id]);
    $res['confirmed'] = $stmt->fetchColumn();

    // Tổng sinh viên đã tư vấn
    $stmt = $pdo->prepare("SELECT COUNT(DISTINCT student_id) FROM appointments WHERE advisor_id = :id");
    $stmt->execute(['id' => $user_id]);
    $res['total_students'] = $stmt->fetchColumn();
    
    // 3. Lịch hẹn hôm nay
    $today = date('Y-m-d');
    $stmt = $pdo->prepare("
        SELECT COUNT(*) 
        FROM appointments a 
        JOIN time_slots ts ON a.slot_id = ts.slot_id 
        WHERE a.advisor_id = :id AND ts.date = :today AND a.status = 'confirmed'
    ");
    $stmt->execute(['id' => $user_id, 'today' => $today]);
    $res['today'] = $stmt->fetchColumn();

    // 4. Danh sách lịch hẹn chi tiết (JOIN bảng users để lấy tên sinh viên)
    $sql_list = "SELECT a.appointment_id, u.name as student_name, ts.date, ts.start_time, ts.end_time, a.status, a.topic
                 FROM appointments a 
                 JOIN users u ON a.student_id = u.id
                 JOIN time_slots ts ON a.slot_id = ts.slot_id
                 WHERE a.advisor_id = :id
                 ORDER BY 
                    CASE WHEN a.status = 'pending' THEN 1 ELSE 2 END, 
                    ts.date DESC, 
                    ts.start_time ASC";
    
    $stmt = $pdo->prepare($sql_list);
    $stmt->execute(['id' => $user_id]);
    $res['appointments_list'] = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($res, JSON_UNESCAPED_UNICODE);

} catch (PDOException $e) {
    error_log("Lỗi API: " . $e->getMessage());
    echo json_encode(["error" => "Lỗi hệ thống, vui lòng thử lại sau."]);
}
?>