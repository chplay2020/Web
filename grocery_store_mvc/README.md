# 🛒 GROCERY STORE WEBSITE - HƯỚNG DẪN CÀI ĐẶT

Website bán hàng tạp hóa được xây dựng bằng PHP với kiến trúc MVC. Hệ thống bao gồm trang khách hàng và trang quản trị.

## 📋 Yêu cầu hệ thống

- **PHP**: >= 7.4 (khuyến nghị PHP 8.0+)
- **MySQL**: >= 5.7 hoặc MariaDB >= 10.2
- **Web Server**: Apache hoặc Nginx (hoặc PHP built-in server)
- **Extension PHP cần thiết**: PDO, PDO_MySQL, mbstring, gd

## 🚀 Hướng dẫn cài đặt

### Bước 1: Tải source code
```bash
# Clone repository (nếu từ Git)
git clone [repository-url]

# Hoặc giải nén file zip vào thư mục web server
```

### Bước 2: Cài đặt môi trường

#### Phương án 1: Sử dụng XAMPP (Khuyến nghị cho Windows)
1. Tải và cài đặt XAMPP từ https://www.apachefriends.org/
2. Khởi động **Apache** và **MySQL** trong XAMPP Control Panel
3. Copy thư mục project vào `C:\xampp\htdocs\`

#### Phương án 2: Sử dụng PHP Built-in Server
1. Cài đặt PHP và MySQL riêng biệt
2. Đảm bảo PHP đã được thêm vào PATH
3. Khởi động MySQL service

### Bước 3: Thiết lập Database

#### Sử dụng phpMyAdmin (XAMPP):
1. Truy cập http://localhost/phpmyadmin
2. Tạo database mới tên **"shop_db"**
3. Chọn database vừa tạo → Import → Chọn file `shop_db.sql` → Go

#### Sử dụng MySQL Command Line:
```sql
mysql -u root -p
CREATE DATABASE shop_db CHARACTER SET utf8 COLLATE utf8_general_ci;
USE shop_db;
SOURCE shop_db.sql;
exit;
```

### Bước 4: Cấu hình Database Connection

Mở file `core/Database.php` và cập nhật thông tin kết nối:

```php
// Trong file core/Database.php, dòng 14-17
$db_host = "";      // Host database
$db_name = "";        // Tên database
$db_user = "";           // Username MySQL
$db_pass = "";               // Password MySQL (mặc định XAMPP để trống)
```

**Lưu ý**: Nếu MySQL của bạn có password, hãy thay đổi `$db_pass`

### Bước 5: Khởi động Website

#### Sử dụng XAMPP:
1. Đảm bảo Apache và MySQL đang chạy
2. Truy cập: `http://localhost/[tên-thư-mục-project]/`

#### Sử dụng PHP Built-in Server:
```bash
# Mở terminal/command prompt tại thư mục project
cd path/to/grocery_store_mvc
php -S localhost:8000

# Truy cập: http://localhost:8000/
```

### Bước 6: Kiểm tra cài đặt
1. Truy cập trang chủ: `http://localhost:8000/` hoặc `http://localhost/grocery_store_mvc/`
2. Nếu thấy trang chủ hiển thị → Cài đặt thành công! ✅

## 🏗️ Cấu trúc thư mục dự án

```
grocery_store_mvc/
├── 📁 controllers/              # Controllers (MVC Pattern)
│   ├── AdminController.php      # Quản lý trang admin
│   ├── AuthController.php       # Xử lý đăng nhập/đăng ký
│   ├── CartController.php       # Quản lý giỏ hàng
│   ├── HomeController.php       # Trang chủ
│   ├── PageController.php       # Các trang static
│   ├── ProductController.php    # Quản lý sản phẩm
│   ├── UserController.php       # Quản lý người dùng
│   └── WishlistController.php   # Danh sách yêu thích
├── 📁 core/                     # Core System
│   ├── Controller.php           # Base Controller class
│   ├── Database.php             # Database connection
│   └── Router.php               # URL routing system
├── 📁 models/                   # Models (MVC Pattern)
│   ├── Cart.php                 # Model giỏ hàng
│   ├── Message.php              # Model tin nhắn
│   ├── Order.php                # Model đơn hàng
│   ├── Product.php              # Model sản phẩm
│   ├── User.php                 # Model người dùng
│   └── Wishlist.php             # Model danh sách yêu thích
├── 📁 views/                    # Views (MVC Pattern)
│   ├── 📁 admin/                # Giao diện admin
│   │   ├── admin.php            # Trang chính admin
│   │   ├── dashboard.php        # Dashboard
│   │   ├── products.php         # Quản lý sản phẩm
│   │   ├── orders.php           # Quản lý đơn hàng
│   │   ├── users.php            # Quản lý người dùng
│   │   └── contacts.php         # Quản lý liên hệ
│   ├── 📁 auth/                 # Giao diện xác thực
│   │   ├── login.php            # Trang đăng nhập
│   │   └── register.php         # Trang đăng ký
│   ├── 📁 cart/                 # Giao diện giỏ hàng
│   ├── 📁 layouts/              # Layout templates
│   │   ├── header.php           # Header chung
│   │   ├── footer.php           # Footer chung
│   │   └── admin_header.php     # Header admin
│   ├── 📁 pages/                # Các trang nội dung
│   │   └── about.php            # Trang giới thiệu
│   ├── 📁 products/             # Giao diện sản phẩm
│   ├── 📁 user/                 # Giao diện người dùng
│   └── 📁 wishlist/             # Giao diện wishlist
├── 📁 css/                      # Stylesheets
│   ├── style.css                # CSS chính
│   ├── admin_style.css          # CSS admin
│   └── components.css           # CSS components
├── 📁 js/                       # JavaScript files
│   └── script.js                # JavaScript chính
├── 📁 images/                   # Hình ảnh giao diện
├── 📁 project images/           # Hình ảnh sản phẩm mặc định
├── 📁 uploaded_img/             # Hình ảnh upload (tự tạo)
├── 📁 migrations/               # Database migrations
│   └── add_quantity_to_products.sql
├── 📄 index.php                 # Entry point
├── 📄 shop_db.sql               # Database structure & data
├── 📄 data_*.json               # Data backup files
├── 📄 sync_database.php         # Database sync tool
└── 📄 README.md                 # Tài liệu này
```

## 👥 Tài khoản mặc định

### Tài khoản Admin:
- **Email**: `admin@gmail.com`
- **Password**: `123456`
- **Quyền**: Quản lý toàn bộ hệ thống

### Tạo tài khoản mới:
- Truy cập trang đăng ký để tạo tài khoản khách hàng
- Admin có thể tạo tài khoản trong trang quản trị

## 🔧 Các tính năng chính

### Cho Khách hàng:
- 🏠 Xem sản phẩm, tìm kiếm, lọc theo danh mục
- 🛒 Thêm vào giỏ hàng, quản lý giỏ hàng
- ❤️ Thêm vào danh sách yêu thích
- 📦 Đặt hàng và theo dõi đơn hàng
- 👤 Quản lý thông tin cá nhân

## 🐛 Xử lý sự cố thường gặp

### Lỗi kết nối Database:
```
PDOException: SQLSTATE[HY000] [1045] Access denied
```
**Giải pháp**: Kiểm tra username/password MySQL trong `core/Database.php`

### Lỗi "Database not found":
```
PDOException: SQLSTATE[HY000] [1049] Unknown database 'shop_db'
```
**Giải pháp**: Đảm bảo đã tạo database và import file `shop_db.sql`

### Lỗi thiếu extension PHP:
```
Class 'PDO' not found
```
**Giải pháp**: Cài đặt và enable PHP extensions: `pdo`, `pdo_mysql`

### Trang hiển thị mã PHP thay vì chạy:
**Giải pháp**: Đảm bảo đang chạy qua web server, không mở file trực tiếp

### Lỗi upload hình ảnh:
**Giải pháp**: Tạo thư mục `uploaded_img/` và cấp quyền ghi

## 🔄 Cập nhật và Bảo trì

### Backup Database:
```bash
mysqldump -u root -p shop_db > backup_$(date +%Y%m%d).sql
```

### Khôi phục Database:
```bash
mysql -u root -p shop_db < backup_file.sql
```

### Cập nhật Dependencies:
- Kiểm tra phiên bản PHP mới
- Cập nhật MySQL/MariaDB khi cần thiết

## 📚 Tài liệu kỹ thuật

### Kiến trúc hệ thống:
- **Pattern**: MVC (Model-View-Controller)
- **Database**: MySQL với PDO
- **Session**: PHP native sessions
- **Security**: Prepared statements, input validation

### API Routes:
- `/` - Trang chủ
- `/auth/login` - Đăng nhập
- `/auth/register` - Đăng ký
- `/products` - Danh sách sản phẩm
- `/cart` - Giỏ hàng
- `/admin` - Trang quản trị

### Database Tables:
- `users` - Thông tin người dùng
- `products` - Sản phẩm
- `cart` - Giỏ hàng
- `orders` - Đơn hàng
- `wishlist` - Danh sách yêu thích
- `messages` - Tin nhắn liên hệ

## 📞 Hỗ trợ

Nếu gặp vấn đề trong quá trình cài đặt:
1. Kiểm tra lại từng bước hướng dẫn
2. Xem phần "Xử lý sự cố thường gặp"
3. Kiểm tra log lỗi PHP và MySQL

---

**Phiên bản**: 1.0  
**Ngày cập nhật**: August 2025  
**Tương thích**: PHP 7.4+, MySQL 5.7+
