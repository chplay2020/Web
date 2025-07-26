<?php
// Lớp Router - Xử lý routing và điều hướng requests

class Router
{
    private $routes = [];

    // Đăng ký route cho GET request
    public function get($path, $controller, $method)
    {
        $this->routes['GET'][$path] = ['controller' => $controller, 'method' => $method];
    }

    // Đăng ký route cho POST request
    public function post($path, $controller, $method)
    {
        $this->routes['POST'][$path] = ['controller' => $controller, 'method' => $method];
    }

    // Xử lý request và điều hướng đến controller/method tương ứng
    public function dispatch()
    {
        $requestMethod = $_SERVER['REQUEST_METHOD'];
        $requestUri = $_SERVER['REQUEST_URI'];

        // Loại bỏ query string khỏi URI
        $path = parse_url($requestUri, PHP_URL_PATH);

        // Loại bỏ dấu / đầu để đồng nhất
        $path = ltrim($path, '/');

        // Xử lý trường hợp path rỗng (trang chủ)
        if (empty($path)) {
            $path = 'home';
        }

        // Debug: bỏ comment dòng dưới để xem path được request
        // echo "Debug: Requested path = '$path', Method = '$requestMethod'<br>";

        // Kiểm tra xem route có tồn tại không
        if (isset($this->routes[$requestMethod][$path])) {
            $route = $this->routes[$requestMethod][$path];
            $controllerName = $route['controller'];
            $methodName = $route['method'];

            try {
                // Include các file cần thiết
                require_once "core/Database.php";
                require_once "core/Controller.php";
                require_once "controllers/{$controllerName}.php";

                // Tạo instance controller và gọi method
                $controller = new $controllerName();
                $controller->$methodName();
            } catch (Exception $e) {
                http_response_code(500);
                echo "Error: " . $e->getMessage();
            }
        } else {
            // Try to handle legacy URLs first
            if ($this->handleLegacyRedirect($path)) {
                return;
            }

            // 404 - Page not found
            $this->handlePageNotFound($path);
        }
    }

    private function handleLegacyRedirect($path)
    {
        // Map old PHP files to new MVC routes
        $legacyRedirects = [
            'home.php' => '/',
            'shop.php' => '/shop',
            'cart.php' => '/cart',
            'wishlist.php' => '/wishlist',
            'login.php' => '/login',
            'register.php' => '/register',
            'logout.php' => '/logout',
            'about.php' => '/about',
            'contact.php' => '/contact',
            'orders.php' => '/orders',
            'checkout.php' => '/checkout',
            'category.php' => '/category',
            'view_page.php' => '/view_page',
            'search_page.php' => '/search_page',
            'admin_page.php' => '/admin_page',
            'admin_products.php' => '/admin_products',
            'admin_orders.php' => '/admin_orders',
            'admin_users.php' => '/admin_users',
            'admin_contacts.php' => '/admin_contacts',
            'user_profile_update.php' => '/user_profile_update'
        ];

        if (isset($legacyRedirects[$path])) {
            header("Location: " . $legacyRedirects[$path], true, 301);
            exit();
        }

        return false;
    }

    private function handlePageNotFound($path)
    {
        http_response_code(404);
        echo "<!DOCTYPE html>
<html lang='en'>
<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <title>Page Not Found</title>
    <style>
        body { font-family: Arial, sans-serif; text-align: center; margin-top: 50px; }
        .error { color: #e74c3c; }
        .info { color: #3498db; margin-top: 20px; }
        a { color: #3498db; text-decoration: none; }
    </style>
</head>
<body>
    <h1 class='error'>404 - Page Not Found</h1>
    <p>The requested path '<strong>$path</strong>' was not found.</p>
    <div class='info'>
        <p>Available routes:</p>
        <ul style='display: inline-block; text-align: left;'>
            <li><a href='/'>Home</a></li>
            <li><a href='/login'>Login</a></li>
            <li><a href='/register'>Register</a></li>
            <li><a href='/shop'>Shop</a></li>
            <li><a href='/cart'>Cart</a></li>
            <li><a href='/about'>About</a></li>
            <li><a href='/contact'>Contact</a></li>
        </ul>
    </div>
</body>
</html>";
    }
}
