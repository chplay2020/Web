<?php
// ========== TRANG ĐĂNG XUẤT - HỦY SESSION VÀ CHUYỂN VỀ LOGIN ==========

@include 'config.php'; // Kết nối database (có thể không cần thiết cho logout)

session_start(); // Khởi tạo session
session_unset(); // Xóa tất cả biến session
session_destroy(); // Hủy session hoàn toàn

header('location:login.php'); // Chuyển hướng về trang đăng nhập
