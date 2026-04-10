# XÂY DỰNG HỆ THỐNG ĐẶT LỊCH TƯ VẤN HỌC TẬP TRỰC TUYẾN TRÊN NỀN TẢNG ĐIỆN TOÁN ĐÁM MÂY AZURE

Hệ thống hỗ trợ kết nối sinh viên và cố vấn học tập, tối ưu hóa quy trình đặt lịch và quản lý tư vấn thông qua nền tảng điện toán đám mây.

## Hướng dẫn cài đặt và chạy trên Localhost

Mặc dù dự án được thiết kế để triển khai trên **Microsoft Azure**, bạn vẫn có thể chạy thử nghiệm dưới môi trường Localhost bằng XAMPP theo các bước sau:

### 1. Chuẩn bị môi trường
* Cài đặt phần mềm **XAMPP**.
* Đảm bảo thư mục code nằm tại: `C:\xampp\htdocs\Project-DTDM`.

### 2. Thiết lập Cơ sở dữ liệu (MySQL)
1. Khởi động **Apache** và **MySQL** từ XAMPP Control Panel.
2. Truy cập: [http://localhost/phpmyadmin/](http://localhost/phpmyadmin/).
3. Tạo Database mới tên là: `hethongdatlich`.
4. Chọn thẻ **Import**, tìm đến file `hethongdatlich.sql` trong thư mục gốc và nhấn **Import**.
5. Vào folder BE tìm file database.php và config.php để đổi mật khẩu và thông tin theo máy bạn.

### 3. Cấu hình Điện toán đám mây (Azure Cloud)
* Dự án được cấu hình để sẵn sàng triển khai trên **Azure App Service**.
* Database có thể kết nối với **Azure Database for MySQL Flexible Server**.
* *Lưu ý:* Khi chạy Local, hãy đảm bảo file cấu hình kết nối đang trỏ về `localhost` với User là `root`.

## Công nghệ sử dụng
* **Cloud Platform:** Microsoft Azure (App Service, SQL Database).
* **Backend:** PHP.
* **Frontend:** HTML, CSS, JavaScript.
* **Database:** MySQL.
* **Version Control:** Git & GitHub.