<?php
header('Content-Type: application/json');
require_once 'config.php';

try {
    // Lấy thông tin sinh viên từ bảng users
    $sql = "SELECT id, name, email, role, created_at FROM users WHERE role = 'student' ORDER BY id DESC";
    $stmt = $pdo->query($sql);
    echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
} catch (PDOException $e) {
    echo json_encode(["error" => $e->getMessage()]);
}
?>