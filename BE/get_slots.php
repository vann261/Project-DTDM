<?php
header('Content-Type: application/json; charset=utf-8');
// Tắt báo lỗi trực tiếp để tránh làm hỏng cấu trúc JSON trả về
error_reporting(0);

require_once 'config.php';
require_once 'functions.php';

$advisor_id = intval($_GET['advisor_id'] ?? 0);

if (!$advisor_id) {
    echo json_encode([]);
    exit;
}

try {
    // SQL: Lấy các khung giờ 'available', thuộc về cố vấn và từ ngày hiện tại trở đi
    $stmt = $pdo->prepare("
        SELECT slot_id, date, start_time, end_time, status
        FROM time_slots
        WHERE advisor_id = :advisor_id
          AND status = 'available'
          AND (date > CURDATE() OR (date = CURDATE() AND start_time > CURTIME()))
        ORDER BY date ASC, start_time ASC
    ");
    $stmt->execute([':advisor_id' => $advisor_id]);
    $slots = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Format lại dữ liệu cho đồng bộ với frontend
    $result = array_map(function($s) {
        $formattedDate = date('d/m/Y', strtotime($s['date']));
        $startTime = date('H:i', strtotime($s['start_time']));
        $endTime = date('H:i', strtotime($s['end_time']));
        
        return [
            'slot_id'    => $s['slot_id'],
            'date'       => $s['date'],
            'date_label' => $formattedDate,
            'start_time' => $startTime,
            'end_time'   => $endTime,
            // Label này sẽ hiển thị trực tiếp trong thẻ <option> của sinh viên
            'label'      => $formattedDate . ' · ' . $startTime . ' – ' . $endTime
        ];
    }, $slots);

    echo json_encode($result, JSON_UNESCAPED_UNICODE);

} catch (PDOException $e) {
    // Ghi lỗi vào log hệ thống thay vì xuất ra màn hình
    error_log('Lỗi get_slots: ' . $e->getMessage());
    echo json_encode([]);
}