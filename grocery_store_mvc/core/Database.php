<?php
// Lớp Database - Quản lý kết nối cơ sở dữ liệu theo mẫu Singleton

class Database
{
    // Instance duy nhất của Database (Singleton pattern)
    private static $instance = null;
    private $connection;

    // Constructor private để đảm bảo chỉ có một instance
    private function __construct()
    {
        // Cấu hình kết nối database
        $db_host = "localhost";
        $db_name = "shop_db";
        $db_user = "root";
        $db_pass = "191204";

        try {
            // Tạo kết nối PDO với MySQL
            $this->connection = new PDO("mysql:host=$db_host;dbname=$db_name", $db_user, $db_pass);

            // Cấu hình PDO để hiển thị lỗi và sử dụng fetch associative
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->connection->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

            // Đặt charset UTF-8 để hỗ trợ tiếng Việt
            $this->connection->exec("set names utf8");
        } catch (PDOException $e) {
            // Hiển thị lỗi kết nối và dừng chương trình
            die("Connection failed: " . $e->getMessage());
        }
    }

    // Lấy instance duy nhất của Database
    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    // Lấy kết nối PDO
    public function getConnection()
    {
        return $this->connection;
    }
}
