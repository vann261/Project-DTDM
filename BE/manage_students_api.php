<?php
header('Content-Type: application/json');
require_once 'config.php';

$data = json_decode(file_get_contents("php://input"), true);
$action = $_GET['action'] ?? '';

try {
    switch ($action) {
        case 'fetch':
            $stmt = $pdo->query("SELECT id, name, email, created_at FROM users WHERE role = 'student' ORDER BY id DESC");
            echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
            break;

        case 'add':
            $pw = password_hash($data['password'], PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, 'student')");
            $stmt->execute([$data['name'], $data['email'], $pw]);
            echo json_encode(["success" => true]);
            break;

        case 'update':
            $stmt = $pdo->prepare("UPDATE users SET name = ?, email = ? WHERE id = ? AND role = 'student'");
            $stmt->execute([$data['name'], $data['email'], $data['id']]);
            echo json_encode(["success" => true]);
            break;

        case 'delete':
            $stmt = $pdo->prepare("DELETE FROM users WHERE id = ? AND role = 'student'");
            $stmt->execute([$data['id']]);
            echo json_encode(["success" => true]);
            break;

        default:
            echo json_encode(["error" => "Hành động không hợp lệ"]);
    }
} catch (PDOException $e) {
    echo json_encode(["error" => "Lỗi: Email đã tồn tại hoặc vi phạm ràng buộc dữ liệu."]);
}
?>