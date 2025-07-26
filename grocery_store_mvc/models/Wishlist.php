<?php
// Model xử lý wishlist (danh sách yêu thích)

class Wishlist
{
    private $db;

    public function __construct()
    {
        // Kết nối đến database thông qua singleton pattern
        $this->db = Database::getInstance()->getConnection();
    }

    // Lấy tất cả sản phẩm trong wishlist của user
    public function getByUserId($userId)
    {
        $stmt = $this->db->prepare("SELECT * FROM `wishlist` WHERE user_id = ?");
        $stmt->execute([$userId]);
        return $stmt->fetchAll();
    }

    // Thêm sản phẩm vào wishlist
    public function add($userId, $productId, $name, $price, $image)
    {
        // Kiểm tra sản phẩm đã có trong wishlist chưa
        if ($this->isInWishlist($userId, $name)) {
            return false;
        }

        $stmt = $this->db->prepare("INSERT INTO `wishlist` (user_id, pid, name, price, image) VALUES (?, ?, ?, ?, ?)");
        return $stmt->execute([$userId, $productId, $name, $price, $image]);
    }

    // Kiểm tra sản phẩm đã có trong wishlist chưa
    public function isInWishlist($userId, $productName)
    {
        $stmt = $this->db->prepare("SELECT * FROM `wishlist` WHERE name = ? AND user_id = ?");
        $stmt->execute([$productName, $userId]);
        return $stmt->rowCount() > 0;
    }

    // Xóa một sản phẩm khỏi wishlist
    public function remove($wishlistId)
    {
        $stmt = $this->db->prepare("DELETE FROM `wishlist` WHERE id = ?");
        return $stmt->execute([$wishlistId]);
    }

    // Xóa sản phẩm khỏi wishlist theo tên và user ID
    public function removeByProduct($userId, $productName)
    {
        $stmt = $this->db->prepare("DELETE FROM `wishlist` WHERE name = ? AND user_id = ?");
        return $stmt->execute([$productName, $userId]);
    }

    // Xóa tất cả sản phẩm trong wishlist của user
    public function removeAll($userId)
    {
        $stmt = $this->db->prepare("DELETE FROM `wishlist` WHERE user_id = ?");
        return $stmt->execute([$userId]);
    }

    // Đếm số sản phẩm trong wishlist
    public function getItemCount($userId)
    {
        $stmt = $this->db->prepare("SELECT COUNT(*) as count FROM `wishlist` WHERE user_id = ?");
        $stmt->execute([$userId]);
        $result = $stmt->fetch();
        return $result['count'];
    }
}
