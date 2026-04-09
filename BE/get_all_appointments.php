<?php
header('Content-Type: application/json');
require_once 'config.php';

try {
    // Sửa users.full_name thành users.name để khớp với DB của bạn
    $sql = "SELECT 
                a.appointment_id, 
                u.name AS student_name, 
                a.topic,
                ts.date AS booking_date, 
                ts.start_time AS booking_time, 
                adv.full_name AS advisor_name, 
                a.status 
            FROM appointments a
            INNER JOIN users u ON a.student_id = u.id
            INNER JOIN advisors adv ON a.advisor_id = adv.advisor_id
            INNER JOIN time_slots ts ON a.slot_id = ts.slot_id
            ORDER BY ts.date DESC, ts.start_time DESC";

    $stmt = $pdo->query($sql);
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo json_encode($data);
} catch (PDOException $e) {
    echo json_encode(["error" => $e->getMessage()]);
}
?>