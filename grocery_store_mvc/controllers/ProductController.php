<?php
// Controller xử lý các chức năng liên quan đến sản phẩm

require_once 'core/Controller.php';
require_once 'models/Product.php';
require_once 'models/Cart.php';
require_once 'models/Wishlist.php';

class ProductController extends Controller
{
    private $productModel;
    private $cartModel;
    private $wishlistModel;

    public function __construct()
    {
        parent::__construct();
        // Khởi tạo các model cần thiết
        $this->productModel = new Product();
        $this->cartModel = new Cart();
        $this->wishlistModel = new Wishlist();
    }

    // Hiển thị trang shop với tất cả sản phẩm
    public function shop()
    {
        $this->requireAuth(); // Yêu cầu đăng nhập

        $message = [];

        // Xử lý thêm vào wishlist
        if (isset($_POST['add_to_wishlist'])) {
            $message = $this->handleAddToWishlist();
        }

        // Xử lý thêm vào cart
        if (isset($_POST['add_to_cart'])) {
            $message = $this->handleAddToCart();
        }

        // Lấy tất cả sản phẩm
        $products = $this->productModel->getAll();

        // Hiển thị view với dữ liệu
        $this->view('products/shop', [
            'products' => $products,
            'message' => $message
        ]);
    }

    // Hiển thị sản phẩm theo danh mục
    public function category()
    {
        $this->requireAuth();

        // Lấy parameter category từ URL
        $category = $_GET['category'] ?? '';

        $message = [];

        // DEBUG: Uncomment để debug category
        // $message[] = "Debug: Category = '$category'";

        // Xử lý thêm vào wishlist
        if (isset($_POST['add_to_wishlist'])) {
            $message = $this->handleAddToWishlist();
        }

        // Xử lý thêm vào cart
        if (isset($_POST['add_to_cart'])) {
            $message = $this->handleAddToCart();
        }

        // Lấy sản phẩm theo danh mục
        $products = $this->productModel->getByCategory($category);

        // DEBUG: Uncomment để debug số lượng sản phẩm
        // $message[] = "Debug: Found " . count($products) . " products";

        // Hiển thị view category với dữ liệu
        $this->view('products/category', [
            'products' => $products,
            'category' => $category,
            'message' => $message
        ]);
    }

    // Xem chi tiết sản phẩm
    public function viewProduct()
    {
        $this->requireAuth();

        // Lấy product ID từ URL parameter
        $pid = $_GET['pid'] ?? 0;
        $product = $this->productModel->getById($pid);

        // Nếu không tìm thấy sản phẩm, chuyển về shop
        if (!$product) {
            $this->redirect('/shop');
        }

        $message = [];

        // Xử lý thêm vào wishlist
        if (isset($_POST['add_to_wishlist'])) {
            $message = $this->handleAddToWishlist();
        }

        // Xử lý thêm vào cart
        if (isset($_POST['add_to_cart'])) {
            $message = $this->handleAddToCart();
        }

        // Hiển thị view chi tiết sản phẩm
        $this->view('products/view', [
            'product' => $product,
            'message' => $message
        ]);
    }

    // Tìm kiếm sản phẩm
    public function search()
    {
        $this->requireAuth();

        $searchBox = $_POST['search_box'] ?? $_GET['search_box'] ?? '';

        $message = [];

        // Xử lý thêm vào wishlist
        if (isset($_POST['add_to_wishlist'])) {
            $message = $this->handleAddToWishlist();
        }

        // Xử lý thêm vào cart
        if (isset($_POST['add_to_cart'])) {
            $message = $this->handleAddToCart();
        }

        $products = [];
        if (!empty($searchBox)) {
            $products = $this->productModel->search($searchBox);
        }

        $this->view('products/search', [
            'products' => $products,
            'searchBox' => $searchBox,
            'message' => $message
        ]);
    }

    private function handleAddToWishlist()
    {
        $userId = $this->getUserId();
        $pid = $this->sanitize($_POST['pid']);
        $p_name = $this->sanitize($_POST['p_name']);
        $p_price = $this->sanitize($_POST['p_price']);
        $p_image = $this->sanitize($_POST['p_image']);

        if ($this->wishlistModel->isInWishlist($userId, $p_name)) {
            return ['already added to wishlist!'];
        } elseif ($this->cartModel->isInCart($userId, $p_name)) {
            return ['already added to cart!'];
        } else {
            $this->wishlistModel->add($userId, $pid, $p_name, $p_price, $p_image);
            return ['added to wishlist!'];
        }
    }

    private function handleAddToCart()
    {
        $userId = $this->getUserId();
        $pid = $this->sanitize($_POST['pid']);
        $p_name = $this->sanitize($_POST['p_name']);
        $p_price = $this->sanitize($_POST['p_price']);
        $p_image = $this->sanitize($_POST['p_image']);
        $p_qty = $this->sanitize($_POST['p_qty']);

        if ($this->cartModel->isInCart($userId, $p_name)) {
            return ['already added to cart!'];
        } else {
            if ($this->wishlistModel->isInWishlist($userId, $p_name)) {
                $this->wishlistModel->removeByProduct($userId, $p_name);
            }

            $this->cartModel->add($userId, $pid, $p_name, $p_price, $p_qty, $p_image);
            return ['added to cart!'];
        }
    }
}
