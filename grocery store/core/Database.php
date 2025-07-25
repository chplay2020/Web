<?php

class Database {
    private static $instance = null;
    private $connection;
    
    private function __construct() {
        $db_host = "localhost";
        $db_name = "shop_db";
        $db_user = "root";
        $db_pass = "191204";
        
        try {
            $this->connection = new PDO("mysql:host=$db_host;dbname=$db_name", $db_user, $db_pass);
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->connection->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            $this->connection->exec("set names utf8");
        } catch (PDOException $e) {
            die("Connection failed: " . $e->getMessage());
        }
    }
    
    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    public function getConnection() {
        return $this->connection;
    }
}
