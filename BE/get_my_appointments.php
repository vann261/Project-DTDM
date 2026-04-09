<?php
header('Content-Type: application/json; charset=utf-8');
error_reporting(0);

require_once 'config.php';
require_once 'functions.php';

startSession();

if (!isLoggedIn()) {
    echo json_encode(['error' => 'Chưa đăng nhập']);
    exit;
}

$student_id = $_SESSION['user_id'];

try {
    $stmt = $pdo->prepare("
        SELECT
            ap.appointment_id,
            ap.topic,
            ap.status,
            ap.created_at,
            ts.date,
            ts.start_time,
            ts.end_time,
            a.full_name   AS advisor_name,
            a.tag         AS advisor_tag,
            a.avatar_url  AS advisor_avatar
        FROM appointments ap
        JOIN time_slots ts ON ts.slot_id    = ap.slot_id
        JOIN advisors   a  ON a.advisor_id  = ap.advisor_id
        WHERE ap.student_id = :student_id
        ORDER BY ts.date DESC, ts.start_time DESC
    ");
    $stmt->execute([':student_id' => $student_id]);
    $rows = $stmt->fetchAll();

    $result = array_map(function($r) {
        $statusLabel = [
            'pending'   => 'Chờ xác nhận',
            'confirmed' => 'Đã xác nhận',
            'cancelled' => 'Đã hủy',
        ];
        return [
            'appointment_id' => $r['appointment_id'],
            'topic'          => $r['topic'],
            'status'         => $r['status'],
            'status_label'   => $statusLabel[$r['status']] ?? $r['status'],
            'date'           => $r['date'],
            'date_label'     => date('d/m/Y', strtotime($r['date'])),
            'start_time'     => substr($r['start_time'], 0, 5),
            'end_time'       => substr($r['end_time'],   0, 5),
            'advisor_name'   => $r['advisor_name'],
            'advisor_tag'    => $r['advisor_tag'],
            'advisor_avatar' => $r['advisor_avatar'],
        ];
    }, $rows);

    echo json_encode($result, JSON_UNESCAPED_UNICODE);

} catch (PDOException $e) {
    error_log('Lỗi get_my_appointments: ' . $e->getMessage());
    echo json_encode([]);
}