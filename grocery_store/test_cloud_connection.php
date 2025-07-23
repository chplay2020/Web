<?php
// Tool test kết nối cloud database
session_start();
$admin_id = $_SESSION['admin_id'] ?? null;

if (!isset($admin_id)) {
    header('Location: login.php');
    exit;
}

$test_result = '';
$connection_info = '';

if ($_POST) {
    $host = trim($_POST['host']);
    $dbname = trim($_POST['dbname']);
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    
    if ($host && $dbname && $username) {
        try {
            $start_time = microtime(true);
            
            // Test connection
            $dsn = "mysql:host=$host;dbname=$dbname;charset=utf8mb4";
            $options = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_TIMEOUT => 10, // 10 seconds timeout
            ];
            
            $conn = new PDO($dsn, $username, $password, $options);
            
            $end_time = microtime(true);
            $connection_time = round(($end_time - $start_time) * 1000, 2);
            
            // Get server info
            $server_info = $conn->getAttribute(PDO::ATTR_SERVER_INFO);
            $server_version = $conn->getAttribute(PDO::ATTR_SERVER_VERSION);
            
            // Test queries
            $conn->exec("SET time_zone = '+00:00'");
            $time_query = $conn->query("SELECT NOW() as current_time, @@version as version, DATABASE() as current_db");
            $time_result = $time_query->fetch();
            
            // Check tables
            $tables_query = $conn->query("SHOW TABLES");
            $tables = $tables_query->fetchAll(PDO::FETCH_COLUMN);
            
            $test_result = 'success';
            $connection_info = [
                'connection_time' => $connection_time,
                'server_version' => $server_version,
                'current_time' => $time_result['current_time'],
                'current_db' => $time_result['current_db'],
                'tables' => $tables,
                'table_count' => count($tables)
            ];
            
        } catch (PDOException $e) {
            $test_result = 'error';
            $connection_info = $e->getMessage();
        }
    } else {
        $test_result = 'error';
        $connection_info = 'Vui lòng điền đầy đủ thông tin!';
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>🔍 Test Cloud Connection</title>
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
        h1 { color: #2c3e50; text-align: center; }
        .form-group {
            margin-bottom: 20px;
        }
        label {
            display: block;
            font-weight: bold;
            color: #495057;
            margin-bottom: 8px;
        }
        input {
            width: 100%;
            padding: 12px;
            border: 2px solid #ddd;
            border-radius: 8px;
            font-size: 16px;
            transition: border-color 0.3s;
        }
        input:focus {
            outline: none;
            border-color: #007bff;
        }
        .btn {
            background: #007bff;
            color: white;
            padding: 12px 30px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 16px;
            width: 100%;
            transition: background 0.3s;
        }
        .btn:hover { background: #0056b3; }
        .result {
            margin-top: 30px;
            padding: 20px;
            border-radius: 8px;
            font-family: monospace;
        }
        .result.success {
            background: #d4edda;
            border: 1px solid #c3e6cb;
            color: #155724;
        }
        .result.error {
            background: #f8d7da;
            border: 1px solid #f5c6cb;
            color: #721c24;
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
        .templates {
            background: #f8f9fa;
            border: 1px solid #dee2e6;
            border-radius: 8px;
            padding: 15px;
            margin: 20px 0;
        }
        .template-btn {
            background: #28a745;
            color: white;
            border: none;
            padding: 8px 15px;
            border-radius: 5px;
            cursor: pointer;
            margin: 5px;
            font-size: 14px;
        }
        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin: 20px 0;
        }
        .info-box {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 8px;
            border-left: 4px solid #007bff;
        }
        .tables-list {
            max-height: 200px;
            overflow-y: auto;
            background: #f8f9fa;
            padding: 10px;
            border-radius: 5px;
            font-family: monospace;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>🔍 Test Cloud Database Connection</h1>
        
        <div class="nav">
            <a href="admin_page.php">← Dashboard</a>
            <a href="cloud_database_guide.php">📚 Hướng dẫn</a>
            <a href="cloud_config_generator.php">🔧 Tạo Config</a>
            <a href="database_migrator.php">📤 Import DB</a>
        </div>

        <div class="templates">
            <h4>📋 Templates nhanh:</h4>
            <button class="template-btn" onclick="fillFreeSQLDB()">FreeSQLDatabase</button>
            <button class="template-btn" onclick="fillPlanetScale()">PlanetScale</button>
            <button class="template-btn" onclick="fillLocalhost()">Localhost</button>
            <button class="template-btn" onclick="loadFromConfig()">Load từ Config</button>
        </div>

        <form method="POST">
            <div class="form-group">
                <label for="host">🌐 Database Host:</label>
                <input type="text" id="host" name="host" required 
                       placeholder="Ví dụ: sql12.freesqldatabase.com"
                       value="<?= htmlspecialchars($_POST['host'] ?? '') ?>">
            </div>

            <div class="form-group">
                <label for="dbname">🗄️ Database Name:</label>
                <input type="text" id="dbname" name="dbname" required 
                       placeholder="Ví dụ: sql12xxxxx_shopdb"
                       value="<?= htmlspecialchars($_POST['dbname'] ?? '') ?>">
            </div>

            <div class="form-group">
                <label for="username">👤 Username:</label>
                <input type="text" id="username" name="username" required 
                       placeholder="Ví dụ: sql12xxxxx"
                       value="<?= htmlspecialchars($_POST['username'] ?? '') ?>">
            </div>

            <div class="form-group">
                <label for="password">🔑 Password:</label>
                <input type="password" id="password" name="password" required 
                       placeholder="Mật khẩu database">
            </div>

            <button type="submit" class="btn">🚀 Test Connection</button>
        </form>

        <?php if ($test_result): ?>
            <div class="result <?= $test_result ?>">
                <?php if ($test_result === 'success'): ?>
                    <h3>✅ KẾT NỐI THÀNH CÔNG!</h3>
                    
                    <div class="info-grid">
                        <div class="info-box">
                            <h4>⚡ Hiệu năng:</h4>
                            <p><strong>Thời gian kết nối:</strong> <?= $connection_info['connection_time'] ?>ms</p>
                            <p><strong>Trạng thái:</strong> <span style="color: #28a745;">ONLINE</span></p>
                        </div>
                        
                        <div class="info-box">
                            <h4>🏷️ Thông tin server:</h4>
                            <p><strong>MySQL Version:</strong> <?= $connection_info['server_version'] ?></p>
                            <p><strong>Current Database:</strong> <?= $connection_info['current_db'] ?></p>
                            <p><strong>Server Time:</strong> <?= $connection_info['current_time'] ?></p>
                        </div>
                    </div>

                    <div class="info-box">
                        <h4>📋 Danh sách bảng (<?= $connection_info['table_count'] ?> bảng):</h4>
                        <div class="tables-list">
                            <?php if (empty($connection_info['tables'])): ?>
                                <em>Chưa có bảng nào trong database</em>
                            <?php else: ?>
                                <?php foreach ($connection_info['tables'] as $table): ?>
                                    <div>📄 <?= htmlspecialchars($table) ?></div>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div style="background: #e8f5e8; padding: 15px; border-radius: 8px; margin-top: 20px;">
                        <h4>🎉 Thành công! Bước tiếp theo:</h4>
                        <ol>
                            <li><strong>Tạo config file:</strong> <a href="cloud_config_generator.php">Cloud Config Generator</a></li>
                            <li><strong>Import dữ liệu:</strong> <a href="database_migrator.php">Database Migrator</a></li>
                            <li><strong>Cập nhật code:</strong> Thay đổi <code>@include 'config.php'</code> thành <code>@include 'config_cloud.php'</code></li>
                        </ol>
                    </div>

                <?php else: ?>
                    <h3>❌ KẾT NỐI THẤT BẠI!</h3>
                    <p><strong>Lỗi:</strong> <?= htmlspecialchars($connection_info) ?></p>
                    
                    <div style="background: #fff3cd; padding: 15px; border-radius: 8px; margin-top: 15px;">
                        <h4>🔧 Hướng dẫn khắc phục:</h4>
                        <ul>
                            <li><strong>Kiểm tra thông tin:</strong> Host, Database name, Username, Password có chính xác?</li>
                            <li><strong>Kiểm tra database:</strong> Database đã được tạo trên cloud chưa?</li>
                            <li><strong>Kiểm tra mạng:</strong> Kết nối internet có ổn định không?</li>
                            <li><strong>Kiểm tra firewall:</strong> Port 3306 có bị block không?</li>
                            <li><strong>Kiểm tra quota:</strong> Cloud database có hết hạn/quota không?</li>
                        </ul>
                    </div>
                <?php endif; ?>
            </div>
        <?php endif; ?>

        <div style="text-align: center; margin-top: 30px; color: #6c757d;">
            <p>💡 Tool này giúp kiểm tra kết nối trước khi migration database</p>
        </div>
    </div>

    <script>
        function fillFreeSQLDB() {
            document.getElementById('host').value = 'sql12.freesqldatabase.com';
            document.getElementById('dbname').value = 'sql12xxxxx_shopdb';
            document.getElementById('username').value = 'sql12xxxxx';
            document.getElementById('password').focus();
        }

        function fillPlanetScale() {
            document.getElementById('host').value = 'aws.connect.psdb.cloud';
            document.getElementById('dbname').value = 'grocery-store';
            document.getElementById('username').value = 'xxxxxxxxxx';
            document.getElementById('password').focus();
        }

        function fillLocalhost() {
            document.getElementById('host').value = 'localhost';
            document.getElementById('dbname').value = 'shop_db';
            document.getElementById('username').value = 'root';
            document.getElementById('password').focus();
        }

        function loadFromConfig() {
            fetch('database_migrator_handler.php', {
                method: 'POST',
                headers: {'Content-Type': 'application/json'},
                body: JSON.stringify({action: 'load_cloud_config'})
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    document.getElementById('host').value = data.db_host || '';
                    document.getElementById('dbname').value = data.db_name || '';
                    document.getElementById('username').value = data.db_user || '';
                    document.getElementById('password').value = data.db_pass || '';
                    alert('✅ Đã load thông tin từ config cloud!');
                } else {
                    alert('❌ Không tìm thấy file config cloud!');
                }
            })
            .catch(error => {
                alert('❌ Lỗi: ' + error.message);
            });
        }
    </script>
</body>
</html>
