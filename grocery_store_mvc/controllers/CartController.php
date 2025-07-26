<?php

require_once 'core/Controller.php';
require_once 'models/Cart.php';

class CartController extends Controller {
    private $cartModel;
    
    public function __construct() {
        parent::__construct();
        $this->cartModel = new Cart();
    }
    
    public function index() {
        $this->requireAuth();
        
        $userId = $this->getUserId();
        $message = [];
        
        // Xử lý xóa sản phẩm
        if (isset($_GET['delete'])) {
            $deleteId = $_GET['delete'];
            $this->cartModel->remove($deleteId);
            $this->redirect('cart');
        }
        
        // Xử lý xóa tất cả
        if (isset($_GET['delete_all'])) {
            $this->cartModel->removeAll($userId);
            $this->redirect('cart');
        }
        
        // Xử lý cập nhật số lượng
        if (isset($_POST['update_qty'])) {
            $cartId = $_POST['cart_id'];
            $qty = $this->sanitize($_POST['p_qty']);
            $this->cartModel->updateQuantity($cartId, $qty);
            $message[] = 'cart quantity updated';
        }
        
        // Lấy tất cả sản phẩm trong cart
        $cartItems = $this->cartModel->getByUserId($userId);
        $grandTotal = $this->cartModel->getTotal($userId);
        
        $this->view('cart/index', [
            'cartItems' => $cartItems,
            'grandTotal' => $grandTotal,
            'message' => $message
        ]);
    }
}
