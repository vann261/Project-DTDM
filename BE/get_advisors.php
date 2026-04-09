<?php
header('Content-Type: application/json; charset=utf-8');
error_reporting(0);

$host   = 'localhost';
$dbname = 'hethongdatlich';
$user   = 'root';
$pass   = '123456';

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $user, $pass);
    $conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

    $action = $_GET['action'] ?? 'list';

    if ($action === 'list') {
        $stmt = $conn->query("SELECT * FROM advisors ORDER BY advisor_id ASC");
        echo json_encode($stmt->fetchAll(), JSON_UNESCAPED_UNICODE);

    } elseif ($action === 'detail') {
        // Hỗ trợ cả ?advisor_id=X lẫn ?id=X
        $advisor_id = intval($_GET['advisor_id'] ?? $_GET['id'] ?? 0);

        // Thử tìm theo advisor_id trước, nếu không có thì theo user_id
        $stmt = $conn->prepare("SELECT * FROM advisors WHERE advisor_id = ? OR user_id = ? LIMIT 1");
        $stmt->execute([$advisor_id, $advisor_id]);
        $adv = $stmt->fetch();

        echo json_encode($adv ?: null, JSON_UNESCAPED_UNICODE);
    }

} catch (Exception $e) {
    echo json_encode(["error" => $e->getMessage()]);
}