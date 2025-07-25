<?php

require_once 'core/Controller.php';
require_once 'models/User.php';

class AuthController extends Controller {
    private $userModel;
    
    public function __construct() {
        parent::__construct();
        $this->userModel = new User();
    }
    
    public function login() {
        $message = [];
        
        if (isset($_POST['submit'])) {
            $email = $this->sanitize($_POST['email']);
            $inputPass = $_POST['pass'];
            
            $user = $this->userModel->getByEmail($email);
            
            if ($user) {
                $storedPassword = $user['password'];
                $passwordValid = false;
                
                // Kiểm tra nếu password là bcrypt
                if (strpos($storedPassword, '$2y$') === 0) {
                    $passwordValid = password_verify($inputPass, $storedPassword);
                } else {
                    // Kiểm tra password MD5 cũ
                    $md5Pass = md5($inputPass);
                    $passwordValid = ($storedPassword === $md5Pass);
                }
                
                if ($passwordValid) {
                    if ($user['user_type'] == 'admin') {
                        $_SESSION['admin_id'] = $user['id'];
                        $this->redirect('admin_page');
                    } elseif ($user['user_type'] == 'user') {
                        $_SESSION['user_id'] = $user['id'];
                        $this->redirect('/');
                    } else {
                        $message[] = 'Invalid user type!';
                    }
                } else {
                    $message[] = 'Incorrect email or password!';
                }
            } else {
                $message[] = 'No user found with this email!';
            }
        }
        
        $this->view('auth/login', ['message' => $message]);
    }
    
    public function register() {
        $message = [];
        
        if (isset($_POST['submit'])) {
            $name = $this->sanitize($_POST['name']);
            $email = $this->sanitize($_POST['email']);
            $pass = $_POST['pass'];
            $cpass = $_POST['cpass'];
            
            // Validation
            if ($pass !== $cpass) {
                $message[] = 'Confirm password not matched!';
            } else {
                // Kiểm tra email đã tồn tại
                $existingUser = $this->userModel->getByEmail($email);
                if ($existingUser) {
                    $message[] = 'User already exists!';
                } else {
                    // Hash password và tạo user mới
                    $hashedPass = password_hash($pass, PASSWORD_DEFAULT);
                    
                    $userData = [
                        'name' => $name,
                        'email' => $email,
                        'password' => $hashedPass,
                        'user_type' => 'user'
                    ];
                    
                    if ($this->userModel->create($userData)) {
                        $message[] = 'Registered successfully!';
                    } else {
                        $message[] = 'Registration failed!';
                    }
                }
            }
        }
        
        $this->view('auth/register', ['message' => $message]);
    }
    
    public function logout() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        session_unset();
        session_destroy();
        $this->redirect('login');
    }
}
