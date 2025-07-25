<?php

// Khởi tạo session và cấu hình bảo mật
ini_set('session.cookie_httponly', 1);
ini_set('session.cookie_secure', 0); // Set to 1 for HTTPS
ini_set('session.use_strict_mode', 1);

// Cấu hình báo cáo lỗi cho development
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Include các class cần thiết
require_once 'core/Database.php';
require_once 'core/Router.php';

// Tạo router instance
$router = new Router();

// Định nghĩa các routes
$router->get('home', 'HomeController', 'index');
$router->post('home', 'HomeController', 'index');

$router->get('shop', 'ProductController', 'shop');
$router->post('shop', 'ProductController', 'shop');

$router->get('cart', 'CartController', 'index');
$router->post('cart', 'CartController', 'index');

$router->get('category', 'ProductController', 'category');
$router->post('category', 'ProductController', 'category');

$router->get('view_page', 'ProductController', 'viewProduct');
$router->post('view_page', 'ProductController', 'viewProduct');

$router->get('search_page', 'ProductController', 'search');
$router->post('search_page', 'ProductController', 'search');

$router->get('login', 'AuthController', 'login');
$router->post('login', 'AuthController', 'login');

$router->get('register', 'AuthController', 'register');
$router->post('register', 'AuthController', 'register');

$router->get('logout', 'AuthController', 'logout');

$router->get('wishlist', 'WishlistController', 'index');
$router->post('wishlist', 'WishlistController', 'index');

$router->get('about', 'PageController', 'about');

$router->get('contact', 'PageController', 'contact');
$router->post('contact', 'PageController', 'contact');

// Admin routes
$router->get('admin_page', 'AdminController', 'dashboard');
$router->get('admin_products', 'AdminController', 'products');
$router->post('admin_products', 'AdminController', 'products');
$router->get('admin_orders', 'AdminController', 'orders');
$router->post('admin_orders', 'AdminController', 'orders');
$router->get('admin_users', 'AdminController', 'users');
$router->get('admin_contacts', 'AdminController', 'contacts');
$router->get('admin_update_product', 'AdminController', 'updateProduct');
$router->post('admin_update_product', 'AdminController', 'updateProduct');
$router->get('admin_update_profile', 'AdminController', 'updateProfile');
$router->post('admin_update_profile', 'AdminController', 'updateProfile');

// User routes
$router->get('user_profile_update', 'UserController', 'updateProfile');
$router->post('user_profile_update', 'UserController', 'updateProfile');
$router->get('orders', 'UserController', 'orders');
$router->get('checkout', 'UserController', 'checkout');
$router->post('checkout', 'UserController', 'checkout');

// Xử lý request
$router->dispatch();
