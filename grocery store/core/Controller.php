<?php

abstract class Controller {
    protected $db;
    
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
        
        // Khởi tạo session nếu chưa có
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }
    
    protected function getUserId() {
        return $_SESSION['user_id'] ?? null;
    }
    
    protected function requireAuth() {
        if (!$this->getUserId()) {
            header('location: /login');
            exit();
        }
    }
    
    protected function requireAdmin() {
        if (!isset($_SESSION['admin_id'])) {
            header('location: /login');
            exit();
        }
    }
    
    protected function view($viewPath, $data = []) {
        extract($data);
        $fullPath = __DIR__ . "/../views/$viewPath.php";
        
        if (file_exists($fullPath)) {
            include $fullPath;
        } else {
            throw new Exception("View not found: $viewPath");
        }
    }
    
    protected function redirect($url) {
        header("location: $url");
        exit();
    }
    
    protected function sanitize($input) {
        return htmlspecialchars($input, ENT_QUOTES, 'UTF-8');
    }
}
