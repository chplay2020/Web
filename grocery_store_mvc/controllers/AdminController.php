<?php

require_once 'core/Controller.php';
require_once 'models/Product.php';
require_once 'models/Order.php';
require_once 'models/User.php';
require_once 'models/Message.php';
require_once 'models/Cart.php';
require_once 'models/Wishlist.php';

class AdminController extends Controller
{
    private $productModel;
    private $orderModel;
    private $userModel;
    private $messageModel;
    private $cartModel;
    private $wishlistModel;

    public function __construct()
    {
        parent::__construct();
        $this->productModel = new Product();
        $this->orderModel = new Order();
        $this->userModel = new User();
        $this->messageModel = new Message();
        $this->cartModel = new Cart();
        $this->wishlistModel = new Wishlist();
    }



    public function dashboard()
    {
        $this->requireAdmin();

        // Lấy thống kê
        $totalPendings = $this->orderModel->getTotalPendingAmount();
        $totalCompleted = $this->orderModel->getTotalCompletedAmount();
        $orderCount = count($this->orderModel->getByStatus('pending'));
        $productCount = count($this->productModel->getAll());
        $userCount = count($this->userModel->getAll());
        $adminCount = 0;
        $regularUserCount = 0;

        $allUsers = $this->userModel->getAll();
        foreach ($allUsers as $user) {
            if ($user['user_type'] === 'admin') {
                $adminCount++;
            } else {
                $regularUserCount++;
            }
        }

        $messageCount = $this->messageModel->getMessageCount();

        $this->view('admin/dashboard', [
            'totalPendings' => $totalPendings,
            'totalCompleted' => $totalCompleted,
            'orderCount' => $orderCount,
            'productCount' => $productCount,
            'userCount' => $userCount,
            'adminCount' => $adminCount,
            'regularUserCount' => $regularUserCount,
            'messageCount' => $messageCount
        ]);
    }

    public function products()
    {
        $this->requireAdmin();

        $message = [];

        // Xử lý thêm sản phẩm
        if (isset($_POST['add_product'])) {
            $message = $this->handleAddProduct();
        }

        // Xử lý xóa sản phẩm
        if (isset($_GET['delete'])) {
            $this->handleDeleteProduct($_GET['delete']);
            $this->redirect('admin_products');
        }

        $products = $this->productModel->getAll();

        $this->view('admin/products', [
            'products' => $products,
            'message' => $message
        ]);
    }

    public function orders()
    {
        $this->requireAdmin();

        $message = [];

        // Xử lý cập nhật trạng thái đơn hàng
        if (isset($_POST['update_order'])) {
            $orderId = $_POST['order_id'];
            $status = $this->sanitize($_POST['update_payment']);
            $this->orderModel->updateStatus($orderId, $status);
            $message[] = 'payment has been updated!';
        }

        // Xử lý xóa đơn hàng - QUAN TRỌNG: Đây là nơi xử lý xóa order
        if (isset($_GET['delete'])) {
            $orderId = $_GET['delete'];

            try {
                // Gọi method delete của Order model để xóa order khỏi database
                $deleteResult = $this->orderModel->delete($orderId);

                // Thêm thông báo xóa thành công
                if ($deleteResult) {
                    $message[] = 'Order deleted successfully!';
                } else {
                    $message[] = 'Failed to delete order!';
                }
            } catch (Exception $e) {
                $message[] = 'Error deleting order: ' . $e->getMessage();
            }

            // KHÔNG redirect ngay, để hiển thị thông báo
            // Sau khi xóa, sẽ reload trang với thông báo
        }

        // Chỉ lấy đơn hàng chưa hoàn thành (pending)
        $orders = $this->orderModel->getByStatus('pending');

        $this->view('admin/orders', [
            'orders' => $orders,
            'message' => $message
        ]);
    }

    public function users()
    {
        $this->requireAdmin();
        $adminId = $_SESSION['admin_id'];

        // Xử lý xóa user
        if (isset($_GET['delete'])) {
            $this->userModel->delete($_GET['delete']);
            $this->redirect('admin_users');
        }

        $type = $_GET['type'] ?? 'all';
        $allUsers = $this->userModel->getAll();
        $users = [];
        if ($type === 'user') {
            foreach ($allUsers as $u) {
                if ($u['user_type'] === 'user') $users[] = $u;
            }
        } elseif ($type === 'admin') {
            foreach ($allUsers as $u) {
                if ($u['user_type'] === 'admin') $users[] = $u;
            }
        } else {
            $users = $allUsers;
        }
        $this->view('admin/users', [
            'users' => $users,
            'adminId' => $adminId
        ]);
    }

    public function contacts()
    {
        $this->requireAdmin();

        // Xử lý xóa tin nhắn
        if (isset($_GET['delete'])) {
            $this->messageModel->delete($_GET['delete']);
            $this->redirect('admin_contacts');
        }

        $messages = $this->messageModel->getAll();

        $this->view('admin/contacts', [
            'messages' => $messages
        ]);
    }

    public function updateProduct()
    {
        $this->requireAdmin();

        $message = [];
        $updateId = $_GET['update'] ?? null;

        if (!$updateId) {
            $this->redirect('admin_products');
        }

        // Xử lý cập nhật sản phẩm
        if (isset($_POST['update_product'])) {
            $message = $this->handleUpdateProduct();
        }

        $product = $this->productModel->getById($updateId);

        if (!$product) {
            $this->redirect('admin_products');
        }

        $this->view('admin/update_product', [
            'product' => $product,
            'message' => $message
        ]);
    }

    public function updateProfile()
    {
        $this->requireAdmin();
        $adminId = $_SESSION['admin_id'];

        $message = [];

        // Xử lý cập nhật profile
        if (isset($_POST['update_profile'])) {
            $message = $this->handleUpdateProfile($adminId);
        }

        $admin = $this->userModel->getById($adminId);

        $this->view('admin/update_profile', [
            'admin' => $admin,
            'message' => $message
        ]);
    }

    private function handleAddProduct()
    {
        $name = $this->sanitize($_POST['name']);
        $price = $this->sanitize($_POST['price']);
        $category = $this->sanitize($_POST['category']);
        $details = $this->sanitize($_POST['details']);

        // Xử lý upload ảnh
        $image = $_FILES['image']['name'];
        $image = $this->sanitize($image);
        $imageSize = $_FILES['image']['size'];
        $imageTmpName = $_FILES['image']['tmp_name'];
        $imageFolder = 'uploaded_img/' . $image;

        // Kiểm tra tên sản phẩm đã tồn tại
        $existingProducts = $this->productModel->getAll();
        foreach ($existingProducts as $product) {
            if ($product['name'] === $name) {
                return ['product name already exist!'];
            }
        }

        $quantity = isset($_POST['quantity']) ? (int)$_POST['quantity'] : 0;
        // Tạo sản phẩm mới
        $productData = [
            'name' => $name,
            'category' => $category,
            'details' => $details,
            'price' => $price,
            'image' => $image,
            'quantity' => $quantity
        ];

        if ($this->productModel->create($productData)) {
            if ($imageSize > 2000000) {
                return ['image size is too large!'];
            } else {
                move_uploaded_file($imageTmpName, $imageFolder);
                return ['new product added!'];
            }
        }

        return ['failed to add product!'];
    }

    private function handleDeleteProduct($productId)
    {
        // Lấy thông tin sản phẩm để xóa ảnh
        $product = $this->productModel->getById($productId);
        if ($product && file_exists('uploaded_img/' . $product['image'])) {
            unlink('uploaded_img/' . $product['image']);
        }

        // Xóa sản phẩm khỏi wishlist và cart
        $this->wishlistModel->removeByProduct('', ''); // Cần cập nhật method này
        $this->cartModel->removeAll(''); // Cần cập nhật method này

        // Xóa sản phẩm
        $this->productModel->delete($productId);
    }

    private function handleUpdateProduct()
    {
        $pid = $_POST['pid'];
        $name = $this->sanitize($_POST['name']);
        $price = $this->sanitize($_POST['price']);
        $category = $this->sanitize($_POST['category']);
        $details = $this->sanitize($_POST['details']);

        // Xử lý ảnh
        $image = $_FILES['image']['name'];
        $image = $this->sanitize($image);
        $imageSize = $_FILES['image']['size'];
        $imageTmpName = $_FILES['image']['tmp_name'];
        $imageFolder = 'uploaded_img/' . $image;
        $oldImage = $_POST['old_image'];

        $quantity = isset($_POST['quantity']) ? (int)$_POST['quantity'] : 0;
        // Cập nhật thông tin sản phẩm
        $productData = [
            'name' => $name,
            'category' => $category,
            'details' => $details,
            'price' => $price,
            'image' => $oldImage, // Giữ ảnh cũ nếu không upload mới
            'quantity' => $quantity
        ];

        if (!empty($image)) {
            if ($imageSize > 2000000) {
                return ['image size is too large!'];
            } else {
                $productData['image'] = $image;
                move_uploaded_file($imageTmpName, $imageFolder);
                if (file_exists('uploaded_img/' . $oldImage)) {
                    unlink('uploaded_img/' . $oldImage);
                }
            }
        }

        $this->productModel->update($pid, $productData);
        return ['product updated successfully!'];
    }

    private function handleUpdateProfile($adminId)
    {
        $name = $this->sanitize($_POST['name']);
        $email = $this->sanitize($_POST['email']);

        // Cập nhật tên và email
        $userData = ['name' => $name, 'email' => $email];
        $this->userModel->update($adminId, $userData);

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
                $updateImage->execute([$image, $adminId]);
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
                $this->userModel->updatePassword($adminId, $confirmPass);
                $message[] = 'password updated successfully!';
            }
        }

        return $message;
    }
}
