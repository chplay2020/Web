<?php

class Message {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }
    
    public function getAll() {
        $stmt = $this->db->prepare("SELECT * FROM `message`");
        $stmt->execute();
        return $stmt->fetchAll();
    }
    
    public function getById($id) {
        $stmt = $this->db->prepare("SELECT * FROM `message` WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }
    
    public function getByUserId($userId) {
        $stmt = $this->db->prepare("SELECT * FROM `message` WHERE user_id = ?");
        $stmt->execute([$userId]);
        return $stmt->fetchAll();
    }
    
    public function getMessageCount() {
        $stmt = $this->db->prepare("SELECT COUNT(*) as count FROM `message`");
        $stmt->execute();
        $result = $stmt->fetch();
        return $result['count'];
    }
    
    public function exists($name, $email, $number, $message) {
        $stmt = $this->db->prepare("SELECT * FROM `message` WHERE name = ? AND email = ? AND number = ? AND message = ?");
        $stmt->execute([$name, $email, $number, $message]);
        return $stmt->rowCount() > 0;
    }
    
    public function create($data) {
        $stmt = $this->db->prepare("INSERT INTO `message` (user_id, name, email, number, message) VALUES (?, ?, ?, ?, ?)");
        return $stmt->execute([
            $data['user_id'], $data['name'], $data['email'], 
            $data['number'], $data['message']
        ]);
    }
    
    public function delete($id) {
        $stmt = $this->db->prepare("DELETE FROM `message` WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
