<?php

class Order {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }
    
    public function getAll() {
        $stmt = $this->db->prepare("SELECT * FROM `orders`");
        $stmt->execute();
        return $stmt->fetchAll();
    }
    
    public function getById($id) {
        $stmt = $this->db->prepare("SELECT * FROM `orders` WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }
    
    public function getByUserId($userId) {
        $stmt = $this->db->prepare("SELECT * FROM `orders` WHERE user_id = ?");
        $stmt->execute([$userId]);
        return $stmt->fetchAll();
    }
    
    public function getByStatus($status) {
        $stmt = $this->db->prepare("SELECT * FROM `orders` WHERE payment_status = ?");
        $stmt->execute([$status]);
        return $stmt->fetchAll();
    }
    
    public function getTotalPendingAmount() {
        $orders = $this->getByStatus('pending');
        $total = 0;
        foreach ($orders as $order) {
            $total += $order['total_price'];
        }
        return $total;
    }
    
    public function getTotalCompletedAmount() {
        $orders = $this->getByStatus('completed');
        $total = 0;
        foreach ($orders as $order) {
            $total += $order['total_price'];
        }
        return $total;
    }
    
    public function getOrderCount() {
        $stmt = $this->db->prepare("SELECT COUNT(*) as count FROM `orders`");
        $stmt->execute();
        $result = $stmt->fetch();
        return $result['count'];
    }
    
    public function create($data) {
        $stmt = $this->db->prepare("INSERT INTO `orders` (user_id, name, number, email, method, address, total_products, total_price, placed_on, payment_status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        return $stmt->execute([
            $data['user_id'], $data['name'], $data['number'], $data['email'], 
            $data['method'], $data['address'], $data['total_products'], 
            $data['total_price'], $data['placed_on'], $data['payment_status']
        ]);
    }
    
    public function updateStatus($id, $status) {
        $stmt = $this->db->prepare("UPDATE `orders` SET payment_status = ? WHERE id = ?");
        return $stmt->execute([$status, $id]);
    }
    
    public function delete($id) {
        $stmt = $this->db->prepare("DELETE FROM `orders` WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
