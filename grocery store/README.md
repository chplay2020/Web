# HƯỚNG DẪN SETUP GROCERY STORE WEBSITE

## 1. Cài đặt môi trường

### Cài đặt XAMPP/WAMP/MAMP
- Tải và cài đặt XAMPP từ https://www.apachefriends.org/
- Khởi động Apache và MySQL

### Hoặc sử dụng PHP built-in server
```bash
php -S localhost:8000
```

## 2. Thiết lập Database

### Tạo Database
1. Mở phpMyAdmin (http://localhost/phpmyadmin)
2. Tạo database mới tên "shop_db"
3. Import file `shop_db.sql` vào database

### Hoặc sử dụng MySQL command line
```sql
CREATE DATABASE shop_db;
USE shop_db;
SOURCE shop_db.sql;
```

## 3. Cấu hình

### Kiểm tra file config.php
- Đảm bảo thông tin database đúng
- Username: root
- Password: (để trống)
- Database: shop_db

## 4. Các lỗi đã sửa

### ✅ Đã sửa filter_var() deprecated
- Thay thế FILTER_SANITIZE_STRING bằng htmlspecialchars()
- Tương thích với PHP 8.x

### ✅ Cải thiện error handling
- Thêm try-catch cho PDO connection
- Thêm exit() sau header redirect

### ✅ Session handling
- Cải thiện kiểm tra session
- Sử dụng null coalescing operator (??)

## 5. Cấu trúc thư mục

```
grocery store/
├── images/          # Hình ảnh giao diện
├── project images/  # Hình ảnh sản phẩm
├── uploaded_img/    # Hình ảnh upload (cần tạo)
├── css/            # File CSS
├── js/             # File JavaScript
├── *.php           # Các file PHP
└── shop_db.sql     # File database
```

## 6. Chạy website

1. Khởi động web server
2. Truy cập: http://localhost:8000/home.php
3. Hoặc: http://localhost/grocery store/home.php (nếu dùng XAMPP)

## 7. Tài khoản admin mặc định

- Email: admin@gmail.com
- Password: 123456
- Hoặc tạo tài khoản mới qua register.php

## 8. Các file quan trọng

- `home.php` - Trang chủ
- `login.php` - Đăng nhập
- `register.php` - Đăng ký
- `shop.php` - Cửa hàng
- `cart.php` - Giỏ hàng
- `admin_page.php` - Trang admin
- `config.php` - Cấu hình database
