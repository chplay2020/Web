<?php

require_once 'core/Controller.php';
require_once 'models/Product.php';
require_once 'models/Cart.php';
require_once 'models/Wishlist.php';

class HomeController extends Controller {
    private $productModel;
    private $cartModel;
    private $wishlistModel;
    
    public function __construct() {
        parent::__construct();
        $this->productModel = new Product();
        $this->cartModel = new Cart();
        $this->wishlistModel = new Wishlist();
    }
    
    public function index() {
        $this->requireAuth();
        
        $message = [];
        
        // Xử lý thêm vào wishlist
        if (isset($_POST['add_to_wishlist'])) {
            $message = $this->handleAddToWishlist();
        }
        
        // Xử lý thêm vào cart
        if (isset($_POST['add_to_cart'])) {
            $message = $this->handleAddToCart();
        }
        
        // Lấy 6 sản phẩm mới nhất
        $products = $this->productModel->getLatest(6);
        
        $this->view('products/home', [
            'products' => $products,
            'message' => $message
        ]);
    }
    
    private function handleAddToWishlist() {
        $userId = $this->getUserId();
        $pid = $this->sanitize($_POST['pid']);
        $p_name = $this->sanitize($_POST['p_name']);
        $p_price = $this->sanitize($_POST['p_price']);
        $p_image = $this->sanitize($_POST['p_image']);
        
        // Kiểm tra sản phẩm đã có trong wishlist và cart
        if ($this->wishlistModel->isInWishlist($userId, $p_name)) {
            return ['already added to wishlist!'];
        } elseif ($this->cartModel->isInCart($userId, $p_name)) {
            return ['already added to cart!'];
        } else {
            $this->wishlistModel->add($userId, $pid, $p_name, $p_price, $p_image);
            return ['added to wishlist!'];
        }
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
            // Xóa khỏi wishlist nếu có
            if ($this->wishlistModel->isInWishlist($userId, $p_name)) {
                $this->wishlistModel->removeByProduct($userId, $p_name);
            }
            
            $this->cartModel->add($userId, $pid, $p_name, $p_price, $p_qty, $p_image);
            return ['added to cart!'];
        }
    }
}
