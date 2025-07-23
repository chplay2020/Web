<?php

// -- PHẦN CẤU HÌNH DATABASE CLOUD --
// Thông tin kết nối database cloud (FreeSQLDatabase.com)

// Host của database cloud - thay bằng thông tin thực tế từ FreeSQLDatabase
$db_host = "sql12.freesqldatabase.com"; // Server từ FreeSQLDatabase
// Tên database được cấp từ FreeSQLDatabase (dạng: sql12xxxxx_shopdb)
$db_name = "sql12xxxxx_shopdb"; // Thay xxxxx bằng số thực tế
// Username được cấp từ FreeSQLDatabase (dạng: sql12xxxxx)
$db_user = "sql12xxxxx"; // Thay xxxxx bằng số thực tế  
// Password bạn đã đặt khi đăng ký
$db_pass = "your_password_here"; // Thay bằng password thực tế

// -- PHẦN KẾT NỐI CSDL BẰNG PDO --
// Đoạn mã này sử dụng PDO (PHP Data Objects) để tạo một kết nối an toàn đến cơ sở dữ liệu.


try {
   // Tạo một đối tượng PDO mới để thiết lập kết nối cloud database.
   // DSN (Data Source Name) chỉ định loại CSDL (mysql), host và tên CSDL.
   // Thêm SSL cho cloud database để bảo mật
   $dsn = "mysql:host=$db_host;dbname=$db_name;charset=utf8mb4";
   $options = [
       PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
       PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
       PDO::ATTR_EMULATE_PREPARES => false,
       PDO::MYSQL_ATTR_SSL_CA => true, // Bật SSL cho cloud connection
   ];
   
   $conn = new PDO($dsn, $db_user, $db_pass, $options);

   // -- Cài đặt các thuộc tính cho kết nối PDO --
   // 1. Chế độ báo lỗi: Ném ra ngoại lệ (Exception) khi có lỗi SQL.
   // Điều này giúp dễ dàng bắt và xử lý lỗi một cách có cấu trúc.
   $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

   // 2. Chế độ lấy dữ liệu mặc định: Trả về một mảng kết hợp (associative array).
   // Khi truy vấn, kết quả sẽ có dạng ['ten_cot' => 'gia_tri'].
   $conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

   // 3. Thiết lập bảng mã (charset) cho kết nối là UTF-8.
   // Điều này đảm bảo dữ liệu tiếng Việt được hiển thị và lưu trữ chính xác.
   $conn->exec("set names utf8");
} catch (PDOException $e) {
   // Nếu có lỗi xảy ra trong khối try, chương trình sẽ dừng lại và hiển thị thông báo lỗi.
   // $e->getMessage() trả về mô tả chi tiết của lỗi kết nối.
   die("Connection failed: " . $e->getMessage());
}

// -- PHẦN CÀI ĐẶT BẢO MẬT CHO SESSION --
// Các cài đặt này giúp tăng cường bảo mật cho cơ chế session của PHP.

// Ngăn chặn truy cập cookie của session qua JavaScript (chống tấn công XSS).
ini_set('session.cookie_httponly', 1);
// Yêu cầu cookie chỉ được gửi qua kết nối HTTPS an toàn.
// Đặt là 0 nếu bạn đang phát triển trên môi trường không có HTTPS (ví dụ: localhost).
// Chuyển thành 1 khi triển khai trên máy chủ sản phẩm có SSL.
ini_set('session.cookie_secure', 0); // Set to 1 for HTTPS
// Bật chế độ nghiêm ngặt cho session, giúp chống tấn công session fixation.
ini_set('session.use_strict_mode', 1);

// -- PHẦN BÁO CÁO LỖI (ERROR REPORTING) --
// Cấu hình này dùng cho môi trường phát triển (development) để dễ dàng gỡ lỗi.

// Hiển thị tất cả các loại lỗi PHP (lỗi nghiêm trọng, cảnh báo, thông báo).
error_reporting(E_ALL);
// Bật tính năng hiển thị lỗi trực tiếp trên trang web.
// **Lưu ý quan trọng**: Nên tắt tính năng này (đặt giá trị là 0) khi triển khai
// trên môi trường sản phẩm (production) để tránh lộ thông tin nhạy cảm.
ini_set('display_errors', 1);
