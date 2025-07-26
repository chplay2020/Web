<?php
// Model xử lý dữ liệu sản phẩm (Product model)

class Product
{
    private $db;

    public function __construct()
    {
        // Kết nối đến database thông qua singleton pattern
        $this->db = Database::getInstance()->getConnection();
    }

    // Lấy tất cả sản phẩm
    public function getAll()
    {
        $stmt = $this->db->prepare("SELECT * FROM `products`");
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // Lấy số lượng sản phẩm mới nhất (dùng cho trang chủ)
    public function getLatest($limit = 6)
    {
        $limit = intval($limit);
        $stmt = $this->db->prepare("SELECT * FROM `products` LIMIT $limit");
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // Lấy sản phẩm theo ID
    public function getById($id)
    {
        $stmt = $this->db->prepare("SELECT * FROM `products` WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    // Lấy sản phẩm theo danh mục
    public function getByCategory($category)
    {
        $stmt = $this->db->prepare("SELECT * FROM `products` WHERE category = ?");
        $stmt->execute([$category]);
        return $stmt->fetchAll();
    }

    // Tìm kiếm sản phẩm theo từ khóa (tên, mô tả hoặc danh mục)
    public function search($keyword)
    {
        // Xử lý từ khóa đặc biệt cho vegetables/vegitables
        $normalizedKeyword = $keyword;
        if (strtolower($keyword) === 'vegetables') { // // Chuyển đổi từ khóa vegetables thành vegitables
            $normalizedKeyword = 'vegitables';
        }

        // Tìm kiếm không phân biệt hoa thường
        $stmt = $this->db->prepare("SELECT * FROM `products` WHERE LOWER(name) LIKE LOWER(?) OR LOWER(details) LIKE LOWER(?) OR LOWER(category) LIKE LOWER(?)");
        $searchTerm = "%$normalizedKeyword%";
        $stmt->execute([$searchTerm, $searchTerm, $searchTerm]);
        return $stmt->fetchAll();
    }

    // Tạo sản phẩm mới
    public function create($data)
    {
        $stmt = $this->db->prepare("INSERT INTO `products` (name, category, details, price, image) VALUES (?, ?, ?, ?, ?)");
        return $stmt->execute([$data['name'], $data['category'], $data['details'], $data['price'], $data['image']]);
    }

    // Cập nhật thông tin sản phẩm
    public function update($id, $data)
    {
        $stmt = $this->db->prepare("UPDATE `products` SET name = ?, category = ?, details = ?, price = ?, image = ? WHERE id = ?");
        return $stmt->execute([$data['name'], $data['category'], $data['details'], $data['price'], $data['image'], $id]);
    }

    // Xóa sản phẩm
    public function delete($id)
    {
        $stmt = $this->db->prepare("DELETE FROM `products` WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
