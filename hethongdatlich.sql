-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Máy chủ: 127.0.0.1
-- Thời gian đã tạo: Th4 09, 2026 lúc 05:10 PM
-- Phiên bản máy phục vụ: 10.4.32-MariaDB
-- Phiên bản PHP: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Cơ sở dữ liệu: `hethongdatlich`
--

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `advisors`
--

CREATE TABLE `advisors` (
  `advisor_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `full_name` varchar(255) NOT NULL,
  `tag` varchar(100) DEFAULT NULL,
  `rating` decimal(2,1) DEFAULT 5.0,
  `student_count` int(11) DEFAULT 0,
  `experience` varchar(100) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `subjects` text DEFAULT NULL,
  `skills` varchar(255) DEFAULT NULL,
  `avatar_url` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `advisors`
--

INSERT INTO `advisors` (`advisor_id`, `user_id`, `full_name`, `tag`, `rating`, `student_count`, `experience`, `description`, `subjects`, `skills`, `avatar_url`) VALUES
(1, 5, 'Phạm Huy Hoàng', 'Fullstack Developer', 4.8, 120, '8 năm kinh nghiệm', 'Tác giả blog Tôi Đi Code Dạo, chuyên gia kiến trúc phần mềm.', 'Web Dev, Career Path', 'C#, Javascript, React', 'https://cdnphoto.dantri.com.vn/DmEOuCxvG8pTlMvHHGHOIKz1jig=/thumb_w/990/2021/08/17/okalumnibai-10pham-huy-hoangver-12docx-1629211989482.png'),
(2, 4, 'Ngô Minh Hiếu (Hiếu PC)', 'An ninh mạng', 4.9, 150, '15 năm kinh nghiệm', 'Chuyên gia tại NCSC, sáng lập dự án Chống Lừa Đảo.', 'An toàn thông tin, Pentest', 'Cyber Security, OSINT', 'https://funix.edu.vn/wp-content/uploads/2022/12/294101199_574574181053011_7408701771793289230_n.jpeg'),
(3, 3, 'TS. Lê Yên Thanh', 'Founder Phenikaa Maas', 5.0, 100, '12 năm kinh nghiệm', 'Cựu thực tập sinh Google, gương mặt Forbes 30 Under 30. Chuyên gia về thuật toán và GIS.', 'Giải thuật, Hệ thống thông tin địa lý, Mobile App', 'C++, Algorithms, System Design', 'https://phenikaa.edu.vn/wp-content/uploads/2022/06/CEO-Le-Yen-Thanh-.jpg'),
(4, 6, 'Bà Thái Vân Linh', 'Chiến lược & Quản trị', 4.9, 126, '20 năm kinh nghiệm', 'Shark Linh - Chuyên gia vận hành doanh nghiệp.', 'MIS, Startup Strategy', 'Management, Strategy', 'https://vcdn1-kinhdoanh.vnecdn.net/2021/09/21/Thai-Van-Linh-Profile-2021-15-1729-9143-1632192239.png?w=680&h=0&q=100&dpr=2&fit=crop&s=8_rKMFsr7PDdAy_g_sIQvw'),
(5, 7, 'Nguyễn Thành Nam', 'Founder FUNiX & Cựu CEO FPT', 5.0, 155, '35 năm kinh nghiệm', 'Chuyên gia hàng đầu trong lĩnh vực phần mềm và giáo dục trực tuyến. Người đặt nền móng cho xuất khẩu phần mềm Việt Nam. Phong cách tư vấn hóm hỉnh, thực tế và giàu trải nghiệm quốc tế.', 'Chiến lược phần mềm, Khởi nghiệp công nghệ, Quản trị dự án quốc tế', 'Software Engineering, Project Management, Entrepreneurship', 'https://vinuni.edu.vn/wp-content/uploads/2020/10/Nguyen-Thanh-Nam-e1602153886179.jpg'),
(6, 8, 'Trần Đặng Đăng Khoa', 'Traveler & Storyteller', 4.9, 135, '10 năm kinh nghiệm', 'Người Việt Nam đầu tiên đi vòng quanh thế giới bằng xe máy. Anh chia sẻ về kỹ năng sinh tồn, thích nghi môi trường mới và tư duy sáng tạo từ những chuyến đi.', 'Kỹ năng mềm, Tư duy sáng tạo, Thích nghi văn hóa', 'Creative Thinking, Soft Skills, Survival Skills, Networking', 'https://mensfolio.vn/wp-content/uploads/2021/01/batch1111_IP7A0406_print_batch.jpg');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `appointments`
--

CREATE TABLE `appointments` (
  `appointment_id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `advisor_id` int(11) NOT NULL,
  `slot_id` int(11) NOT NULL,
  `topic` text NOT NULL,
  `status` enum('pending','confirmed','cancelled') DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `appointments`
--

INSERT INTO `appointments` (`appointment_id`, `student_id`, `advisor_id`, `slot_id`, `topic`, `status`, `created_at`) VALUES
(1, 1, 3, 2, 'Tư vấn chọn môn học cho học kỳ mới', 'confirmed', '2026-03-27 07:04:07'),
(6, 1, 1, 6, 'Web – rdqb,ạacbhjcsghscabsbahjhCHBJ', 'confirmed', '2026-04-09 13:33:52'),
(7, 1, 5, 22, 'AI – tôi muốn tư vấn về cách tích hợp AI vào phần mền', 'cancelled', '2026-04-09 14:06:52'),
(8, 1, 5, 24, 'Web – jamschbsdhshajkaka', 'cancelled', '2026-04-09 14:08:18'),
(10, 1, 3, 10, 'Web – MZZZZZZZZZZ', 'confirmed', '2026-04-09 14:32:16');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `time_slots`
--

CREATE TABLE `time_slots` (
  `slot_id` int(11) NOT NULL,
  `advisor_id` int(11) NOT NULL,
  `date` date NOT NULL,
  `start_time` time NOT NULL,
  `end_time` time NOT NULL,
  `status` enum('available','booked') DEFAULT 'available'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `time_slots`
--

INSERT INTO `time_slots` (`slot_id`, `advisor_id`, `date`, `start_time`, `end_time`, `status`) VALUES
(1, 3, '2026-04-01', '08:00:00', '09:00:00', 'available'),
(2, 3, '2026-04-01', '09:00:00', '10:00:00', 'available'),
(3, 3, '2026-04-02', '14:00:00', '15:00:00', 'available'),
(4, 3, '2026-04-01', '10:00:00', '11:00:00', 'available'),
(5, 3, '2026-04-03', '13:00:00', '14:00:00', 'available'),
(6, 3, '2026-04-10', '08:00:00', '09:00:00', 'booked'),
(7, 3, '2026-04-10', '10:00:00', '11:00:00', 'available'),
(8, 3, '2026-04-11', '14:00:00', '15:00:00', 'available'),
(9, 3, '2026-04-12', '09:00:00', '10:00:00', 'available'),
(10, 3, '2026-04-10', '09:00:00', '10:00:00', 'booked'),
(11, 3, '2026-04-11', '08:00:00', '09:00:00', 'available'),
(12, 3, '2026-04-13', '14:00:00', '15:00:00', 'available'),
(13, 3, '2026-04-14', '10:00:00', '11:00:00', 'available'),
(15, 3, '2026-04-11', '09:00:00', '10:00:00', 'available'),
(16, 3, '2026-04-12', '14:00:00', '15:00:00', 'available'),
(17, 3, '2026-04-15', '10:00:00', '11:00:00', 'available'),
(18, 4, '2026-04-10', '10:00:00', '11:00:00', 'available'),
(19, 4, '2026-04-12', '08:00:00', '09:00:00', 'available'),
(20, 4, '2026-04-14', '14:00:00', '15:00:00', 'available'),
(21, 4, '2026-04-15', '09:00:00', '10:00:00', 'available'),
(22, 5, '2026-04-11', '08:00:00', '09:00:00', 'available'),
(23, 1, '2026-04-13', '10:00:00', '11:00:00', 'available'),
(24, 5, '2026-04-14', '14:00:00', '15:00:00', 'available'),
(25, 5, '2026-04-16', '09:00:00', '10:00:00', 'available'),
(26, 6, '2026-04-11', '10:00:00', '11:00:00', 'available'),
(27, 6, '2026-04-13', '08:00:00', '09:00:00', 'available'),
(28, 6, '2026-04-15', '14:00:00', '15:00:00', 'available'),
(29, 6, '2026-04-16', '10:00:00', '11:00:00', 'available'),
(31, 3, '2026-04-10', '14:00:00', '15:00:00', 'available'),
(34, 3, '2026-04-15', '09:00:00', '10:00:00', 'available');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('student','advisor','admin') NOT NULL DEFAULT 'student',
  `rank_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `role`, `rank_id`, `created_at`) VALUES
(1, 'Nguyễn Đỗ Hồng Vân', 'hongvan@gmail.com', '$2y$10$.fCGF..wI1YyeQIZqxX15uV8D8aAhzXlX7NBAGK7W9k8IhWs6E.RS', 'student', 2, '2026-03-27 07:04:07'),
(2, 'Trần Thị Quyên', 'thiquyen@gmail.com', '123456', 'student', 2, '2026-03-27 07:04:07'),
(3, 'TS. Lê Yên Thanh', 'thanh.le@gmail.com', '$2y$10$u1m0bvWzKFtcbVlbbraNl.4.LPwZqrIVqiBI2AmuLpydEWNxsrgqK', 'advisor', 3, '2026-03-27 07:04:07'),
(4, 'Ngô Minh Hiếu', 'hieu.ngo@gmail.com', '123456', 'advisor', 3, '2026-03-27 07:04:07'),
(5, 'Admin', 'admin@gmail.com', '123456', 'admin', 1, '2026-03-27 07:04:07'),
(6, 'Trần Đặng Mỹ Duyên', 'myduyen@gmail.com', '123456', 'student', 2, '2026-03-27 07:30:51'),
(7, 'Nguyễn Thị Thuỳ Vân', 'thuyvan@gmail.com', '123456', 'student', 2, '2026-03-27 07:30:51'),
(8, 'Trần Đặng Đăng Khoa', 'khoa.tdd@gmail.com', '123456', 'advisor', 3, '2026-04-03 07:35:45');

--
-- Chỉ mục cho các bảng đã đổ
--

--
-- Chỉ mục cho bảng `advisors`
--
ALTER TABLE `advisors`
  ADD PRIMARY KEY (`advisor_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Chỉ mục cho bảng `appointments`
--
ALTER TABLE `appointments`
  ADD PRIMARY KEY (`appointment_id`),
  ADD UNIQUE KEY `slot_id` (`slot_id`),
  ADD KEY `student_id` (`student_id`),
  ADD KEY `advisor_id` (`advisor_id`);

--
-- Chỉ mục cho bảng `time_slots`
--
ALTER TABLE `time_slots`
  ADD PRIMARY KEY (`slot_id`),
  ADD UNIQUE KEY `unique_slot_idx` (`advisor_id`,`date`,`start_time`);

--
-- Chỉ mục cho bảng `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT cho các bảng đã đổ
--

--
-- AUTO_INCREMENT cho bảng `advisors`
--
ALTER TABLE `advisors`
  MODIFY `advisor_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=100;

--
-- AUTO_INCREMENT cho bảng `appointments`
--
ALTER TABLE `appointments`
  MODIFY `appointment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT cho bảng `time_slots`
--
ALTER TABLE `time_slots`
  MODIFY `slot_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT cho bảng `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- Các ràng buộc cho các bảng đã đổ
--

--
-- Các ràng buộc cho bảng `advisors`
--
ALTER TABLE `advisors`
  ADD CONSTRAINT `advisors_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `appointments`
--
ALTER TABLE `appointments`
  ADD CONSTRAINT `appointments_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `appointments_ibfk_2` FOREIGN KEY (`advisor_id`) REFERENCES `advisors` (`advisor_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `appointments_ibfk_3` FOREIGN KEY (`slot_id`) REFERENCES `time_slots` (`slot_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Các ràng buộc cho bảng `time_slots`
--
ALTER TABLE `time_slots`
  ADD CONSTRAINT `time_slots_ibfk_1` FOREIGN KEY (`advisor_id`) REFERENCES `advisors` (`advisor_id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
