<?php

class Cart {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }
    
    public function getByUserId($userId) {
        $stmt = $this->db->prepare("SELECT * FROM `cart` WHERE user_id = ?");
        $stmt->execute([$userId]);
        return $stmt->fetchAll();
    }
    
    public function add($userId, $productId, $name, $price, $quantity, $image) {
        // Kiểm tra sản phẩm đã có trong cart chưa
        if ($this->isInCart($userId, $name)) {
            return false;
        }
        
        $stmt = $this->db->prepare("INSERT INTO `cart` (user_id, pid, name, price, quantity, image) VALUES (?, ?, ?, ?, ?, ?)");
        return $stmt->execute([$userId, $productId, $name, $price, $quantity, $image]);
    }
    
    public function isInCart($userId, $productName) {
        $stmt = $this->db->prepare("SELECT * FROM `cart` WHERE name = ? AND user_id = ?");
        $stmt->execute([$productName, $userId]);
        return $stmt->rowCount() > 0;
    }
    
    public function updateQuantity($cartId, $quantity) {
        $stmt = $this->db->prepare("UPDATE `cart` SET quantity = ? WHERE id = ?");
        return $stmt->execute([$quantity, $cartId]);
    }
    
    public function remove($cartId) {
        $stmt = $this->db->prepare("DELETE FROM `cart` WHERE id = ?");
        return $stmt->execute([$cartId]);
    }
    
    public function removeAll($userId) {
        $stmt = $this->db->prepare("DELETE FROM `cart` WHERE user_id = ?");
        return $stmt->execute([$userId]);
    }
    
    public function getTotal($userId) {
        $items = $this->getByUserId($userId);
        $total = 0;
        foreach ($items as $item) {
            $total += $item['price'] * $item['quantity'];
        }
        return $total;
    }
    
    public function getItemCount($userId) {
        $stmt = $this->db->prepare("SELECT COUNT(*) as count FROM `cart` WHERE user_id = ?");
        $stmt->execute([$userId]);
        $result = $stmt->fetch();
        return $result['count'];
    }
}
