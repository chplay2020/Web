<?php
// Model xử lý dữ liệu người dùng (User model)

class User
{
    private $db;

    public function __construct()
    {
        // Kết nối đến database thông qua singleton pattern
        $this->db = Database::getInstance()->getConnection();
    }

    // Lấy thông tin người dùng theo ID
    public function getById($id)
    {
        $stmt = $this->db->prepare("SELECT * FROM `users` WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    // Lấy thông tin người dùng theo email
    public function getByEmail($email)
    {
        $stmt = $this->db->prepare("SELECT * FROM `users` WHERE email = ?");
        $stmt->execute([$email]);
        return $stmt->fetch();
    }

    // Tạo người dùng mới
    public function create($data)
    {
        $stmt = $this->db->prepare("INSERT INTO `users` (name, email, password, user_type) VALUES (?, ?, ?, ?)");
        return $stmt->execute([$data['name'], $data['email'], $data['password'], $data['user_type']]);
    }

    // Cập nhật thông tin cơ bản của người dùng
    public function update($id, $data)
    {
        $stmt = $this->db->prepare("UPDATE `users` SET name = ?, email = ? WHERE id = ?");
        return $stmt->execute([$data['name'], $data['email'], $id]);
    }

    // Cập nhật mật khẩu người dùng
    public function updatePassword($id, $newPassword)
    {
        $stmt = $this->db->prepare("UPDATE `users` SET password = ? WHERE id = ?");
        return $stmt->execute([$newPassword, $id]);
    }

    // Xóa người dùng
    public function delete($id)
    {
        $stmt = $this->db->prepare("DELETE FROM `users` WHERE id = ?");
        return $stmt->execute([$id]);
    }

    // Lấy danh sách tất cả người dùng
    public function getAll()
    {
        $stmt = $this->db->prepare("SELECT * FROM `users`");
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // Xác thực người dùng với email và mật khẩu
    public function authenticate($email, $password)
    {
        $user = $this->getByEmail($email);
        // Kiểm tra mật khẩu bằng bcrypt
        if ($user && password_verify($password, $user['password'])) {
            return $user;
        }
        return false;
    }

    // Kiểm tra xem người dùng có phải admin không
    public function isAdmin($userId)
    {
        $user = $this->getById($userId);
        return $user && $user['user_type'] === 'admin';
    }
}
