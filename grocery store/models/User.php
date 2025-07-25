<?php

class User {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }
    
    public function getById($id) {
        $stmt = $this->db->prepare("SELECT * FROM `users` WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }
    
    public function getByEmail($email) {
        $stmt = $this->db->prepare("SELECT * FROM `users` WHERE email = ?");
        $stmt->execute([$email]);
        return $stmt->fetch();
    }
    
    public function create($data) {
        $stmt = $this->db->prepare("INSERT INTO `users` (name, email, password, user_type) VALUES (?, ?, ?, ?)");
        return $stmt->execute([$data['name'], $data['email'], $data['password'], $data['user_type']]);
    }
    
    public function update($id, $data) {
        $stmt = $this->db->prepare("UPDATE `users` SET name = ?, email = ? WHERE id = ?");
        return $stmt->execute([$data['name'], $data['email'], $id]);
    }
    
    public function updatePassword($id, $newPassword) {
        $stmt = $this->db->prepare("UPDATE `users` SET password = ? WHERE id = ?");
        return $stmt->execute([$newPassword, $id]);
    }
    
    public function delete($id) {
        $stmt = $this->db->prepare("DELETE FROM `users` WHERE id = ?");
        return $stmt->execute([$id]);
    }
    
    public function getAll() {
        $stmt = $this->db->prepare("SELECT * FROM `users`");
        $stmt->execute();
        return $stmt->fetchAll();
    }
    
    public function authenticate($email, $password) {
        $user = $this->getByEmail($email);
        if ($user && password_verify($password, $user['password'])) {
            return $user;
        }
        return false;
    }
    
    public function isAdmin($userId) {
        $user = $this->getById($userId);
        return $user && $user['user_type'] === 'admin';
    }
}
