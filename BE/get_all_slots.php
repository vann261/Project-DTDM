<?php
header('Content-Type: application/json; charset=utf-8');
error_reporting(0);

require_once 'config.php';
require_once 'functions.php';

$date = $_GET['date'] ?? '';

try {
    $where = "ts.status = 'available' AND ts.date >= CURDATE()";
    $params = [];

    if ($date) {
        $where .= " AND ts.date = :date";
        $params[':date'] = $date;
    }

    $stmt = $pdo->prepare("
        SELECT
            ts.slot_id,
            ts.date,
            ts.start_time,
            ts.end_time,
            a.advisor_id,
            a.full_name,
            a.tag,
            a.avatar_url,
            a.rating
        FROM time_slots ts
        JOIN advisors a ON a.advisor_id = ts.advisor_id
        WHERE $where
        ORDER BY ts.date ASC, ts.start_time ASC
    ");
    $stmt->execute($params);
    $slots = $stmt->fetchAll();

    $result = array_map(function($s) {
        return [
            'slot_id'      => $s['slot_id'],
            'date'         => $s['date'],
            'date_label'   => date('d/m/Y', strtotime($s['date'])),
            'start_time'   => substr($s['start_time'], 0, 5),
            'end_time'     => substr($s['end_time'], 0, 5),
            'advisor_id'   => $s['advisor_id'],
            'full_name'    => $s['full_name'],
            'tag'          => $s['tag'],
            'avatar_url'   => $s['avatar_url'],
            'rating'       => $s['rating'],
        ];
    }, $slots);

    echo json_encode($result, JSON_UNESCAPED_UNICODE);

} catch (PDOException $e) {
    error_log('Lỗi get_all_slots: ' . $e->getMessage());
    echo json_encode([]);
}