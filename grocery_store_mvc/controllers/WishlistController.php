<?php

require_once 'core/Controller.php';
require_once 'models/Wishlist.php';
require_once 'models/Cart.php';

class WishlistController extends Controller {
    private $wishlistModel;
    private $cartModel;
    
    public function __construct() {
        parent::__construct();
        $this->wishlistModel = new Wishlist();
        $this->cartModel = new Cart();
    }
    
    public function index() {
        $this->requireAuth();
        
        $userId = $this->getUserId();
        $message = [];
        
        // Xử lý thêm từ wishlist vào cart
        if (isset($_POST['add_to_cart'])) {
            $message = $this->handleAddToCart();
        }
        
        // Xử lý xóa sản phẩm
        if (isset($_GET['delete'])) {
            $deleteId = $_GET['delete'];
            $this->wishlistModel->remove($deleteId);
            $this->redirect('wishlist');
        }
        
        // Xử lý xóa tất cả
        if (isset($_GET['delete_all'])) {
            $this->wishlistModel->removeAll($userId);
            $this->redirect('wishlist');
        }
        
        // Lấy tất cả sản phẩm trong wishlist
        $wishlistItems = $this->wishlistModel->getByUserId($userId);
        
        // Tính tổng tiền
        $grandTotal = 0;
        foreach ($wishlistItems as $item) {
            $grandTotal += $item['price'];
        }
        
        $this->view('wishlist/index', [
            'wishlistItems' => $wishlistItems,
            'grandTotal' => $grandTotal,
            'message' => $message
        ]);
    }
    
    private function handleAddToCart() {
        $userId = $this->getUserId();
        $pid = $this->sanitize($_POST['pid']);
        $p_name = $this->sanitize($_POST['p_name']);
        $p_price = $this->sanitize($_POST['p_price']);
        $p_image = $this->sanitize($_POST['p_image']);
        $p_qty = $this->sanitize($_POST['p_qty']);
        
        if ($this->cartModel->isInCart($userId, $p_name)) {
            return ['already added to cart!'];
        } else {
            // Xóa khỏi wishlist
            $this->wishlistModel->removeByProduct($userId, $p_name);
            
            // Thêm vào cart
            $this->cartModel->add($userId, $pid, $p_name, $p_price, $p_qty, $p_image);
            return ['added to cart!'];
        }
    }
}
