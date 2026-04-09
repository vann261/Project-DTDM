<?php
header('Content-Type: application/json');
require_once 'config.php';

try {
    // Sửa 'id' thành 'advisor_id'
    $stmt = $pdo->query("SELECT advisor_id, full_name, description FROM advisors"); 
    $data = $stmt->fetchAll();
    
    echo json_encode($data);
} catch (PDOException $e) {
    echo json_encode(["error" => $e->getMessage()]);
}
?>