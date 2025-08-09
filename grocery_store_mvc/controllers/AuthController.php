<?php
// Controller xử lý xác thực người dùng (đăng nhập, đăng ký, đăng xuất)

require_once 'core/Controller.php';
require_once 'models/User.php';

class AuthController extends Controller
{
    private $userModel;

    public function __construct()
    {
        parent::__construct();
        // Khởi tạo model User để xử lý dữ liệu người dùng
        $this->userModel = new User();
    }

    // Xử lý đăng nhập người dùng
    public function login()
    {
        $message = [];

        // Kiểm tra nếu form đăng nhập được submit
        if (isset($_POST['submit'])) {
            // Lấy và làm sạch dữ liệu từ form
            $email = $this->sanitize($_POST['email']);
            $inputPass = $_POST['pass'];

            // Tìm người dùng theo email
            $user = $this->userModel->getByEmail($email);

            if ($user) {
                $storedPassword = $user['password'];
                $passwordValid = false;

                // Kiểm tra định dạng mật khẩu và xác thực
                // Hỗ trợ cả bcrypt (mới) và MD5 (cũ) để tương thích ngược
                if (strpos($storedPassword, '$2y$') === 0) {
                    // Xác thực mật khẩu bcrypt
                    $passwordValid = password_verify($inputPass, $storedPassword);
                } else {
                    // Xác thực mật khẩu MD5 cũ
                    $md5Pass = md5($inputPass);
                    $passwordValid = ($storedPassword === $md5Pass);
                }

                if ($passwordValid) {
                    // Đăng nhập thành công, chuyển hướng theo loại user
                    if ($user['user_type'] == 'admin') {
                        $_SESSION['admin_id'] = $user['id'];
                        $this->redirect('admin_page');
                    } elseif ($user['user_type'] == 'user') {
                        $_SESSION['user_id'] = $user['id'];
                        $this->redirect('home');
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

        // Hiển thị trang đăng nhập với thông báo (nếu có)
        $this->view('auth/login', ['message' => $message]);
    }

    // Xử lý đăng ký người dùng mới
    public function register()
    {
        $message = [];

        // Kiểm tra nếu form đăng ký được submit
        if (isset($_POST['submit'])) {
            // Lấy và làm sạch dữ liệu từ form
            $name = $this->sanitize($_POST['name']);
            $email = $this->sanitize($_POST['email']);
            $pass = $_POST['pass'];
            $cpass = $_POST['cpass'];

            // Xử lý upload hình ảnh
            $image = 'default_pic.png'; // Ảnh mặc định
            if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
                $uploadedImage = $_FILES['image']['name'];
                $imageSize = $_FILES['image']['size'];
                $imageTmpName = $_FILES['image']['tmp_name'];

                // Kiểm tra định dạng file
                $allowedExtensions = ['jpg', 'jpeg', 'png'];
                $imageExtension = strtolower(pathinfo($uploadedImage, PATHINFO_EXTENSION));

                if (in_array($imageExtension, $allowedExtensions)) {
                    if ($imageSize <= 2000000) { // 2MB
                        // Tạo tên file unique
                        $image = $this->sanitize($email) . '_' . time() . '.' . $imageExtension;
                        $imageFolder = 'uploaded_img/' . $image;

                        // Tạo thư mục nếu chưa tồn tại
                        if (!is_dir('uploaded_img')) {
                            mkdir('uploaded_img', 0777, true);
                        }

                        // Upload file
                        if (!move_uploaded_file($imageTmpName, $imageFolder)) {
                            $message[] = 'Failed to upload image!';
                            $image = 'default_pic.png';
                        }
                    } else {
                        $message[] = 'Image size too large! Maximum 2MB allowed.';
                        $image = 'default_pic.png';
                    }
                } else {
                    $message[] = 'Invalid image format! Only JPG, JPEG, PNG allowed.';
                    $image = 'default_pic.png';
                }
            }

            // Kiểm tra tính hợp lệ của dữ liệu
            if ($pass !== $cpass) {
                $message[] = 'Confirm password not matched!';
            } else {
                // Kiểm tra email đã tồn tại trong hệ thống
                $existingUser = $this->userModel->getByEmail($email);
                if ($existingUser) {
                    $message[] = 'User already exists!';
                } else {
                    // Mã hóa mật khẩu bằng bcrypt để bảo mật
                    $hashedPass = password_hash($pass, PASSWORD_DEFAULT);

                    // Tạo dữ liệu người dùng mới
                    $userData = [
                        'name' => $name,
                        'email' => $email,
                        'password' => $hashedPass,
                        'image' => $image,
                        'user_type' => 'user' // Mặc định là user thường
                    ];

                    // Tạo tài khoản mới trong database
                    if ($this->userModel->create($userData)) {
                        $message[] = 'Registered successfully!';
                    } else {
                        $message[] = 'Registration failed!';
                    }
                }
            }
        }

        // Hiển thị trang đăng ký với thông báo (nếu có)
        $this->view('auth/register', ['message' => $message]);
    }

    // Xử lý đăng xuất người dùng
    public function logout()
    {
        // Đảm bảo session đã được khởi tạo
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Xóa tất cả dữ liệu session và hủy session
        session_unset();
        session_destroy();

        // Chuyển hướng về trang đăng nhập
        $this->redirect('login');
    }
}
