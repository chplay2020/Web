<?php

require_once 'core/Controller.php';
require_once 'models/User.php';
require_once 'models/Order.php';

class UserController extends Controller {
    private $userModel;
    private $orderModel;
    
    public function __construct() {
        parent::__construct();
        $this->userModel = new User();
        $this->orderModel = new Order();
    }
    
    public function updateProfile() {
        $this->requireAuth();
        $userId = $this->getUserId();
        
        $message = [];
        
        // Xử lý cập nhật profile
        if (isset($_POST['update_profile'])) {
            $message = $this->handleUpdateProfile($userId);
        }
        
        $user = $this->userModel->getById($userId);
        
        $this->view('user/update_profile', [
            'user' => $user,
            'message' => $message
        ]);
    }
    
    public function orders() {
        $this->requireAuth();
        $userId = $this->getUserId();
        
        $orders = $this->orderModel->getByUserId($userId);
        
        $this->view('user/orders', [
            'orders' => $orders
        ]);
    }
    
    public function checkout() {
        $this->requireAuth();
        $userId = $this->getUserId();
        
        $message = [];
        
        // Xử lý đặt hàng
        if (isset($_POST['order'])) {
            $message = $this->handleOrder($userId);
        }
        
        // Lấy cart items để hiển thị
        $cartItems = $this->db->prepare("SELECT * FROM `cart` WHERE user_id = ?");
        $cartItems->execute([$userId]);
        $cartItems = $cartItems->fetchAll();
        
        $cartGrandTotal = 0;
        foreach ($cartItems as $item) {
            $cartGrandTotal += ($item['price'] * $item['quantity']);
        }
        
        $this->view('user/checkout', [
            'cartItems' => $cartItems,
            'cartGrandTotal' => $cartGrandTotal,
            'message' => $message
        ]);
    }
    
    private function handleUpdateProfile($userId) {
        $name = $this->sanitize($_POST['name']);
        $email = $this->sanitize($_POST['email']);
        
        // Cập nhật tên và email
        $userData = ['name' => $name, 'email' => $email];
        $this->userModel->update($userId, $userData);
        
        $message = [];
        
        // Xử lý ảnh
        $image = $_FILES['image']['name'];
        $image = $this->sanitize($image);
        $imageSize = $_FILES['image']['size'];
        $imageTmpName = $_FILES['image']['tmp_name'];
        $imageFolder = 'uploaded_img/' . $image;
        $oldImage = $_POST['old_image'];
        
        if (!empty($image)) {
            if ($imageSize > 2000000) {
                $message[] = 'image size is too large!';
            } else {
                $updateImage = $this->db->prepare("UPDATE `users` SET image = ? WHERE id = ?");
                $updateImage->execute([$image, $userId]);
                if ($updateImage) {
                    move_uploaded_file($imageTmpName, $imageFolder);
                    if (file_exists('uploaded_img/' . $oldImage)) {
                        unlink('uploaded_img/' . $oldImage);
                    }
                    $message[] = 'image updated successfully!';
                }
            }
        }
        
        // Xử lý đổi mật khẩu
        $oldPass = $_POST['old_pass'];
        $updatePass = md5($_POST['update_pass']);
        $newPass = md5($_POST['new_pass']);
        $confirmPass = md5($_POST['confirm_pass']);
        
        if (!empty($updatePass) && !empty($newPass) && !empty($confirmPass)) {
            if ($updatePass != $oldPass) {
                $message[] = 'old password not matched!';
            } elseif ($newPass != $confirmPass) {
                $message[] = 'confirm password not matched!';
            } else {
                $this->userModel->updatePassword($userId, $confirmPass);
                $message[] = 'password updated successfully!';
            }
        }
        
        return $message;
    }
    
    private function handleOrder($userId) {
        $name = $this->sanitize($_POST['name']);
        $number = $this->sanitize($_POST['number']);
        $email = $this->sanitize($_POST['email']);
        $method = $this->sanitize($_POST['method']);
        
        // Ghép địa chỉ
        $address = 'flat no. ' . $_POST['flat'] . ' ' . $_POST['street'] . ' ' . $_POST['city'] . ' ' . $_POST['state'] . ' ' . $_POST['country'] . ' - ' . $_POST['pin_code'];
        $address = $this->sanitize($address);
        $placedOn = date('d-M-Y');
        
        $cartTotal = 0;
        $cartProducts = [];
        
        // Lấy tất cả sản phẩm trong cart
        $cartQuery = $this->db->prepare("SELECT * FROM `cart` WHERE user_id = ?");
        $cartQuery->execute([$userId]);
        $cartItems = $cartQuery->fetchAll();
        
        if (!empty($cartItems)) {
            foreach ($cartItems as $item) {
                $cartProducts[] = $item['name'] . ' ( ' . $item['quantity'] . ' )';
                $subTotal = ($item['price'] * $item['quantity']);
                $cartTotal += $subTotal;
            }
        }
        
        $totalProducts = implode(', ', $cartProducts);
        
        // Kiểm tra đơn hàng đã tồn tại
        $orderQuery = $this->db->prepare("SELECT * FROM `orders` WHERE name = ? AND number = ? AND email = ? AND method = ? AND address = ? AND total_products = ? AND total_price = ?");
        $orderQuery->execute([$name, $number, $email, $method, $address, $totalProducts, $cartTotal]);
        
        if ($cartTotal == 0) {
            return ['your cart is empty'];
        } elseif ($orderQuery->rowCount() > 0) {
            return ['order placed already!'];
        } else {
            // Tạo đơn hàng mới
            $orderData = [
                'user_id' => $userId,
                'name' => $name,
                'number' => $number,
                'email' => $email,
                'method' => $method,
                'address' => $address,
                'total_products' => $totalProducts,
                'total_price' => $cartTotal,
                'placed_on' => $placedOn,
                'payment_status' => 'pending'
            ];
            
            $this->orderModel->create($orderData);
            
            // Xóa cart sau khi đặt hàng
            $deleteCart = $this->db->prepare("DELETE FROM `cart` WHERE user_id = ?");
            $deleteCart->execute([$userId]);
            
            return ['order placed successfully!'];
        }
    }
}
