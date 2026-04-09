<?php

$host = "localhost";          // Địa chỉ máy chủ
$dbname = "hethongdatlich";   // Tên cơ sở dữ liệu
$user = "root";               // Tên người dùng MySQL
$pass = "123456";             // Mật khẩu MySQL

try {
    // Tạo DSN (Data Source Name) cho PDO
    $dsn = "mysql:host=$host;dbname=$dbname;charset=utf8mb4";
    
    // Tạo kết nối PDO
    $pdo = new PDO($dsn, $user, $pass);
    
    // ===================================
    // CẤU HÌNH BÁO LỖI BẰNG EXCEPTION
    // ===================================
    // Khi xảy ra lỗi, PDO sẽ ném ra ngoại lệ (Exception)
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // ===================================
    // CẤU HÌNH FETCH DATA DẠNG MẢNG
    // ===================================
    // Trả về dữ liệu dạng mảng kết hợp với tên cột
    // Ví dụ: ['id' => 1, 'name' => 'Lịch họp', ...]
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    
} catch (PDOException $e) {
    // Bắt lỗi kết nối database
    die("Lỗi kết nối database: " . $e->getMessage());
}

// Trả về đối tượng kết nối PDO để sử dụng ở các file khác
?>
