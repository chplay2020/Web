<?php
// Generator tạo file config cho cloud database
// Không sửa file config.php gốc, tạo file config mới

session_start();
$admin_id = $_SESSION['admin_id'] ?? null;

if (!isset($admin_id)) {
    header('Location: login.php');
    exit;
}

$message = '';
$config_content = '';

if ($_POST) {
    $db_host = trim($_POST['db_host']);
    $db_name = trim($_POST['db_name']);
    $db_user = trim($_POST['db_user']);
    $db_pass = trim($_POST['db_pass']);
    $config_name = trim($_POST['config_name']) ?: 'config_cloud.php';
    
    if ($db_host && $db_name && $db_user) {
        // Tạo nội dung file config mới
        $config_content = '<?php
// -- CẤU HÌNH CLOUD DATABASE --
// File này được tạo tự động bởi Cloud Config Generator
// Tạo lúc: ' . date('d/m/Y H:i:s') . '

// Thông tin kết nối cloud database
$db_host = "' . addslashes($db_host) . '"; // Host cloud database
$db_name = "' . addslashes($db_name) . '"; // Tên database cloud
$db_user = "' . addslashes($db_user) . '"; // Username cloud
$db_pass = "' . addslashes($db_pass) . '"; // Password cloud

// -- PHẦN KẾT NỐI CSDL BẰNG PDO --
// Sử dụng PDO để kết nối an toàn đến cloud database
try {
    // Tạo DSN với SSL cho cloud connection
    $dsn = "mysql:host=$db_host;dbname=$db_name;charset=utf8mb4";
    $options = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ];
    
    // Thêm SSL nếu cần thiết cho một số cloud provider
    if (strpos($db_host, "freesqldatabase") !== false || 
        strpos($db_host, "planetscale") !== false ||
        strpos($db_host, "aws") !== false) {
        $options[PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT] = false;
    }
    
    $conn = new PDO($dsn, $db_user, $db_pass, $options);
    
    // Thiết lập UTF-8 cho dữ liệu tiếng Việt
    $conn->exec("set names utf8mb4");
    
} catch (PDOException $e) {
    die("❌ Lỗi kết nối cloud database: " . $e->getMessage());
}

// -- PHẦN CÀI ĐẶT BẢO MẬT CHO SESSION --
ini_set("session.cookie_httponly", 1);
ini_set("session.cookie_secure", 0); // Đặt 1 nếu có HTTPS
ini_set("session.use_strict_mode", 1);

// -- PHẦN BÁO CÁO LỖI --
error_reporting(E_ALL);
ini_set("display_errors", 1); // Đặt 0 khi production

?>';

        // Lưu file config mới
        file_put_contents($config_name, $config_content);
        $message = "✅ Tạo file $config_name thành công!";
    } else {
        $message = "❌ Vui lòng điền đầy đủ thông tin!";
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>🔧 Tạo Config Cloud Database</title>
    <style>
        body { 
            font-family: Arial, sans-serif; 
            margin: 0; 
            padding: 20px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
        }
        .container { 
            max-width: 800px; 
            margin: 0 auto; 
            background: white;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
        }
        h1 { 
            color: #2c3e50; 
            text-align: center;
            margin-bottom: 30px;
        }
        .form-group {
            margin-bottom: 20px;
        }
        label {
            display: block;
            font-weight: bold;
            color: #34495e;
            margin-bottom: 5px;
        }
        input[type="text"], input[type="password"], select {
            width: 100%;
            padding: 12px;
            border: 2px solid #ddd;
            border-radius: 8px;
            font-size: 16px;
            transition: border-color 0.3s;
        }
        input:focus {
            outline: none;
            border-color: #3498db;
        }
        .btn {
            background: #3498db;
            color: white;
            padding: 12px 30px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 16px;
            transition: background 0.3s;
            width: 100%;
        }
        .btn:hover {
            background: #2980b9;
        }
        .message {
            padding: 15px;
            border-radius: 8px;
            margin: 20px 0;
            text-align: center;
            font-weight: bold;
        }
        .success { background: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        .error { background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
        .provider {
            background: #f8f9fa;
            border: 1px solid #dee2e6;
            border-radius: 8px;
            padding: 15px;
            margin: 10px 0;
            cursor: pointer;
            transition: all 0.3s;
        }
        .provider:hover {
            background: #e9ecef;
            border-color: #3498db;
        }
        .provider.active {
            background: #e3f2fd;
            border-color: #2196f3;
        }
        .code-preview {
            background: #2c3e50;
            color: #ecf0f1;
            padding: 20px;
            border-radius: 8px;
            font-family: monospace;
            font-size: 14px;
            max-height: 400px;
            overflow-y: auto;
            white-space: pre-line;
        }
        .nav {
            text-align: center;
            margin: 20px 0;
        }
        .nav a {
            display: inline-block;
            background: #6c757d;
            color: white;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 5px;
            margin: 5px;
        }
        .hint {
            background: #fff3cd;
            border: 1px solid #ffeaa7;
            color: #856404;
            padding: 10px;
            border-radius: 5px;
            font-size: 14px;
            margin-top: 5px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>🔧 Tạo Config Cloud Database</h1>
        
        <div class="nav">
            <a href="admin_page.php">← Dashboard</a>
            <a href="cloud_database_guide.php">📚 Hướng dẫn</a>
            <a href="database_migrator.php">📤 Import DB</a>
        </div>

        <?php if ($message): ?>
            <div class="message <?= strpos($message, '✅') !== false ? 'success' : 'error' ?>">
                <?= $message ?>
            </div>
        <?php endif; ?>

        <form method="POST">
            <div class="form-group">
                <label>🏷️ Chọn Cloud Provider</label>
                <div class="provider" onclick="fillFreeSQLDB()">
                    <strong>FreeSQLDatabase.com (Miễn phí)</strong><br>
                    <small>Click để tự động điền template</small>
                </div>
                <div class="provider" onclick="fillPlanetScale()">
                    <strong>PlanetScale (MySQL Serverless)</strong><br>
                    <small>Click để tự động điền template</small>
                </div>
                <div class="provider" onclick="fillSupabase()">
                    <strong>Supabase (PostgreSQL)</strong><br>
                    <small>Click để tự động điền template</small>
                </div>
            </div>

            <div class="form-group">
                <label for="db_host">🌐 Database Host</label>
                <input type="text" id="db_host" name="db_host" required 
                       placeholder="Ví dụ: sql12.freesqldatabase.com">
                <div class="hint">Host server của cloud database</div>
            </div>

            <div class="form-group">
                <label for="db_name">🗄️ Database Name</label>
                <input type="text" id="db_name" name="db_name" required 
                       placeholder="Ví dụ: sql12xxxxx_shopdb">
                <div class="hint">Tên database được cấp từ cloud provider</div>
            </div>

            <div class="form-group">
                <label for="db_user">👤 Username</label>
                <input type="text" id="db_user" name="db_user" required 
                       placeholder="Ví dụ: sql12xxxxx">
                <div class="hint">Username để truy cập database</div>
            </div>

            <div class="form-group">
                <label for="db_pass">🔑 Password</label>
                <input type="password" id="db_pass" name="db_pass" required 
                       placeholder="Mật khẩu cloud database">
                <div class="hint">Password bạn đã đặt khi tạo database</div>
            </div>

            <div class="form-group">
                <label for="config_name">📄 Tên file config mới</label>
                <input type="text" id="config_name" name="config_name" 
                       value="config_cloud.php" placeholder="config_cloud.php">
                <div class="hint">Tên file config mới (không ghi đè file cũ)</div>
            </div>

            <button type="submit" class="btn">🚀 Tạo Config File</button>
        </form>

        <?php if ($config_content): ?>
            <h3>📋 Preview Config File:</h3>
            <div class="code-preview"><?= htmlspecialchars($config_content) ?></div>
            
            <div style="margin-top: 20px; padding: 15px; background: #e8f5e8; border-radius: 8px;">
                <h4>✅ Bước tiếp theo:</h4>
                <ol>
                    <li><strong>Test kết nối:</strong> <a href="test_cloud_connection.php">Kiểm tra kết nối cloud</a></li>
                    <li><strong>Import dữ liệu:</strong> <a href="database_migrator.php">Chuyển data lên cloud</a></li>
                    <li><strong>Cập nhật code:</strong> Thay <code>@include 'config.php'</code> thành <code>@include '<?= htmlspecialchars($_POST['config_name'] ?? 'config_cloud.php') ?>'</code></li>
                    <li><strong>Backup:</strong> Lưu file config cũ để phục hồi nếu cần</li>
                </ol>
            </div>
        <?php endif; ?>
    </div>

    <script>
        function fillFreeSQLDB() {
            document.getElementById('db_host').value = 'sql12.freesqldatabase.com';
            document.getElementById('db_name').value = 'sql12xxxxx_shopdb';
            document.getElementById('db_user').value = 'sql12xxxxx';
            document.getElementById('db_pass').focus();
            
            // Highlight active provider
            document.querySelectorAll('.provider').forEach(p => p.classList.remove('active'));
            event.target.closest('.provider').classList.add('active');
        }

        function fillPlanetScale() {
            document.getElementById('db_host').value = 'aws.connect.psdb.cloud';
            document.getElementById('db_name').value = 'grocery-store';
            document.getElementById('db_user').value = 'xxxxxxxxxx';
            document.getElementById('db_pass').focus();
            
            document.querySelectorAll('.provider').forEach(p => p.classList.remove('active'));
            event.target.closest('.provider').classList.add('active');
        }

        function fillSupabase() {
            document.getElementById('db_host').value = 'db.xxxxxxxxxx.supabase.co';
            document.getElementById('db_name').value = 'postgres';
            document.getElementById('db_user').value = 'postgres';
            document.getElementById('db_pass').focus();
            
            document.querySelectorAll('.provider').forEach(p => p.classList.remove('active'));
            event.target.closest('.provider').classList.add('active');
        }
    </script>
</body>
</html>
