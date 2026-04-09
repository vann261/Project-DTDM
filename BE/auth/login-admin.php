<?php
// 1. Cấu hình trả về JSON và chặn lỗi HTML làm hỏng giao diện
header('Content-Type: application/json; charset=utf-8');
error_reporting(E_ALL);
ini_set('display_errors', 0); // Không cho phép hiện lỗi trực tiếp ra màn hình

session_start();

// 2. Thông số kết nối (Dùng mật khẩu 123456 như bạn xác nhận)
$host = "localhost";
$db_user = "root";
$db_pass = "123456"; 
$db_name = "hethongdatlich";

try {
    $conn = new mysqli($host, $db_user, $db_pass, $db_name);

    if ($conn->connect_error) {
        throw new Exception("Lỗi kết nối database rùi: " . $conn->connect_error);
    }

    // 3. Lấy dữ liệu từ Frontend gửi qua
    $json_data = file_get_contents('php://input');
    $data = json_decode($json_data, true);

    // Frontend của bạn gửi biến tên là 'username'
    $user_input = $data['username'] ?? '';
    $pass_input = $data['password'] ?? '';

    if (empty($user_input) || empty($pass_input)) {
        echo json_encode(['success' => false, 'message' => 'Nhập thiếu tên hoặc mật khẩu kìa!']);
        exit;
    }

    // 4. Truy vấn vào bảng 'users' (Vì database của bạn tên bảng là users)
    // Mình kiểm tra email khớp và role phải là admin
    $sql = "SELECT id, name, email, password, role FROM users WHERE email = ? AND role = 'admin' LIMIT 1";
    $stmt = $conn->prepare($sql);
    
    if (!$stmt) {
        throw new Exception("Lỗi truy vấn SQL: " . $conn->error);
    }

    $stmt->bind_param("s", $user_input);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user_data = $result->fetch_assoc();
        
        // 5. Kiểm tra mật khẩu (So sánh trực tiếp '123456')
        if ($pass_input === $user_data['password']) {
            $_SESSION['admin_id'] = $user_data['id'];
            $_SESSION['admin_name'] = $user_data['name'];
            
            echo json_encode([
                'success' => true,
                'name' => $user_data['name']
            ]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Mật khẩu không đúng đâu nhé!']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Tài khoản không tồn tại hoặc bạn không có quyền Admin!']);
    }

    $stmt->close();
    $conn->close();

} catch (Exception $e) {
    // Nếu có bất cứ lỗi gì, trả về dưới dạng JSON sạch sẽ
    echo json_encode([
        'success' => false, 
        'message' => 'Hệ thống báo lỗi: ' . $e->getMessage()
    ]);
}
?>