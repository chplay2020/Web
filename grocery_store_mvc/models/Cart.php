<?php
// Model xử lý giỏ hàng (Cart model)

class Cart
{
    private $db;

    public function __construct()
    {
        // Kết nối đến database thông qua singleton pattern
        $this->db = Database::getInstance()->getConnection();
    }

    // Lấy tất cả sản phẩm trong giỏ hàng của user
    public function getByUserId($userId)
    {
        $stmt = $this->db->prepare("SELECT * FROM `cart` WHERE user_id = ?");
        $stmt->execute([$userId]);
        return $stmt->fetchAll();
    }

    // Thêm sản phẩm vào giỏ hàng
    public function add($userId, $productId, $name, $price, $quantity, $image)
    {
        // Kiểm tra sản phẩm đã có trong cart chưa
        if ($this->isInCart($userId, $name)) {
            return false;
        }

        $stmt = $this->db->prepare("INSERT INTO `cart` (user_id, pid, name, price, quantity, image) VALUES (?, ?, ?, ?, ?, ?)");
        return $stmt->execute([$userId, $productId, $name, $price, $quantity, $image]);
    }

    // Kiểm tra sản phẩm đã có trong giỏ hàng chưa
    public function isInCart($userId, $productName)
    {
        $stmt = $this->db->prepare("SELECT * FROM `cart` WHERE name = ? AND user_id = ?");
        $stmt->execute([$productName, $userId]);
        return $stmt->rowCount() > 0;
    }

    // Cập nhật số lượng sản phẩm trong giỏ hàng
    public function updateQuantity($cartId, $quantity)
    {
        $stmt = $this->db->prepare("UPDATE `cart` SET quantity = ? WHERE id = ?");
        return $stmt->execute([$quantity, $cartId]);
    }

    // Xóa một sản phẩm khỏi giỏ hàng
    public function remove($cartId)
    {
        $stmt = $this->db->prepare("DELETE FROM `cart` WHERE id = ?");
        return $stmt->execute([$cartId]);
    }

    // Xóa tất cả sản phẩm trong giỏ hàng của user
    public function removeAll($userId)
    {
        $stmt = $this->db->prepare("DELETE FROM `cart` WHERE user_id = ?");
        return $stmt->execute([$userId]);
    }

    // Tính tổng tiền giỏ hàng của user
    public function getTotal($userId)
    {
        $items = $this->getByUserId($userId);
        $total = 0;
        foreach ($items as $item) {
            $total += $item['price'] * $item['quantity'];
        }
        return $total;
    }

    public function getItemCount($userId)
    {
        $stmt = $this->db->prepare("SELECT COUNT(*) as count FROM `cart` WHERE user_id = ?");
        $stmt->execute([$userId]);
        $result = $stmt->fetch();
        return $result['count'];
    }
}
