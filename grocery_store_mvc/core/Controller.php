<?php
// Base Controller class - Lớp cơ sở cho tất cả controllers

abstract class Controller
{
    protected $db;

    public function __construct()
    {
        // Kết nối database
        $this->db = Database::getInstance()->getConnection();

        // Khởi tạo session nếu chưa có
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    // Lấy ID người dùng hiện tại từ session
    protected function getUserId()
    {
        return $_SESSION['user_id'] ?? null;
    }

    // Kiểm tra yêu cầu đăng nhập
    protected function requireAuth()
    {
        if (!$this->getUserId()) {
            header('location: /login');
            exit();
        }
    }

    // Kiểm tra yêu cầu quyền admin
    protected function requireAdmin()
    {
        if (!isset($_SESSION['admin_id'])) {
            header('location: /login');
            exit();
        }
    }

    // Hiển thị view với dữ liệu
    protected function view($viewPath, $data = [])
    {
        extract($data); // Chuyển mảng thành biến
        $fullPath = __DIR__ . "/../views/$viewPath.php";

        if (file_exists($fullPath)) {
            include $fullPath;
        } else {
            throw new Exception("View not found: $viewPath");
        }
    }

    // Chuyển hướng đến URL khác
    protected function redirect($url)
    {
        header("location: $url");
        exit();
    }

    // Làm sạch dữ liệu đầu vào để tránh XSS
    protected function sanitize($input)
    {
        return htmlspecialchars($input, ENT_QUOTES, 'UTF-8');
    }
}
