<?php

class Product {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }
    
    public function getAll() {
        $stmt = $this->db->prepare("SELECT * FROM `products`");
        $stmt->execute();
        return $stmt->fetchAll();
    }
    
    public function getLatest($limit = 6) {
        $limit = intval($limit);
        $stmt = $this->db->prepare("SELECT * FROM `products` LIMIT $limit");
        $stmt->execute();
        return $stmt->fetchAll();
    }
    
    public function getById($id) {
        $stmt = $this->db->prepare("SELECT * FROM `products` WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }
    
    public function getByCategory($category) {
        $stmt = $this->db->prepare("SELECT * FROM `products` WHERE category = ?");
        $stmt->execute([$category]);
        return $stmt->fetchAll();
    }
    
    public function search($keyword) {
        $stmt = $this->db->prepare("SELECT * FROM `products` WHERE name LIKE ? OR details LIKE ?");
        $searchTerm = "%$keyword%";
        $stmt->execute([$searchTerm, $searchTerm]);
        return $stmt->fetchAll();
    }
    
    public function create($data) {
        $stmt = $this->db->prepare("INSERT INTO `products` (name, category, details, price, image) VALUES (?, ?, ?, ?, ?)");
        return $stmt->execute([$data['name'], $data['category'], $data['details'], $data['price'], $data['image']]);
    }
    
    public function update($id, $data) {
        $stmt = $this->db->prepare("UPDATE `products` SET name = ?, category = ?, details = ?, price = ?, image = ? WHERE id = ?");
        return $stmt->execute([$data['name'], $data['category'], $data['details'], $data['price'], $data['image'], $id]);
    }
    
    public function delete($id) {
        $stmt = $this->db->prepare("DELETE FROM `products` WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
