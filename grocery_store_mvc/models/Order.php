<?php
// Model xử lý dữ liệu đơn hàng (Order model)

class Order
{
    private $db;

    public function __construct()
    {
        // Kết nối đến database thông qua singleton pattern
        $this->db = Database::getInstance()->getConnection();
    }

    // Lấy tất cả đơn hàng
    public function getAll()
    {
        $stmt = $this->db->prepare("SELECT * FROM `orders`");
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // Lấy đơn hàng theo ID
    public function getById($id)
    {
        $stmt = $this->db->prepare("SELECT * FROM `orders` WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    // Lấy đơn hàng theo ID người dùng
    public function getByUserId($userId)
    {
        $stmt = $this->db->prepare("SELECT * FROM `orders` WHERE user_id = ?");
        $stmt->execute([$userId]);
        return $stmt->fetchAll();
    }

    // Lấy đơn hàng theo trạng thái thanh toán
    public function getByStatus($status)
    {
        $stmt = $this->db->prepare("SELECT * FROM `orders` WHERE payment_status = ?");
        $stmt->execute([$status]);
        return $stmt->fetchAll();
    }

    // Tính tổng tiền của các đơn hàng đang chờ xử lý
    public function getTotalPendingAmount()
    {
        $orders = $this->getByStatus('pending');
        $total = 0;
        foreach ($orders as $order) {
            $total += $order['total_price'];
        }
        return $total;
    }

    // Tính tổng tiền của các đơn hàng đã hoàn thành
    public function getTotalCompletedAmount()
    {
        $orders = $this->getByStatus('completed');
        $total = 0;
        foreach ($orders as $order) {
            $total += $order['total_price'];
        }
        return $total;
    }

    // Đếm tổng số đơn hàng
    public function getOrderCount()
    {
        $stmt = $this->db->prepare("SELECT COUNT(*) as count FROM `orders`");
        $stmt->execute();
        $result = $stmt->fetch();
        return $result['count'];
    }

    // Tạo đơn hàng mới
    public function create($data)
    {
        $stmt = $this->db->prepare("INSERT INTO `orders` (user_id, name, number, email, method, address, total_products, total_price, placed_on, payment_status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        return $stmt->execute([
            $data['user_id'],
            $data['name'],
            $data['number'],
            $data['email'],
            $data['method'],
            $data['address'],
            $data['total_products'],
            $data['total_price'],
            $data['placed_on'],
            $data['payment_status']
        ]);
    }

    // Cập nhật trạng thái đơn hàng
    public function updateStatus($id, $status)
    {
        $stmt = $this->db->prepare("UPDATE `orders` SET payment_status = ? WHERE id = ?");
        return $stmt->execute([$status, $id]);
    }

    // Xóa đơn hàng - QUAN TRỌNG: Đây là method để xóa order
    public function delete($id)
    {
        $stmt = $this->db->prepare("DELETE FROM `orders` WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
