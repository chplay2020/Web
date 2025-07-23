<?php
// Tool import database từ localhost lên cloud database
// Không sửa gì ở database gốc, chỉ copy dữ liệu

session_start();
$admin_id = $_SESSION['admin_id'] ?? null;

if (!isset($admin_id)) {
    header('Location: login.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>📤 Database Migrator</title>
    <style>
        body { 
            font-family: Arial, sans-serif; 
            margin: 0; 
            padding: 20px;
            background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
            min-height: 100vh;
        }
        .container { 
            max-width: 1000px; 
            margin: 0 auto; 
            background: white;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
        }
        h1 { color: #2c3e50; text-align: center; }
        .step { 
            background: #f8f9fa;
            border-left: 4px solid #28a745;
            padding: 20px;
            margin: 20px 0;
            border-radius: 5px;
        }
        .form-group {
            margin-bottom: 15px;
        }
        label {
            display: block;
            font-weight: bold;
            margin-bottom: 5px;
            color: #495057;
        }
        input, select {
            width: 100%;
            padding: 10px;
            border: 2px solid #ddd;
            border-radius: 5px;
            font-size: 14px;
        }
        .btn {
            background: #007bff;
            color: white;
            padding: 12px 24px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            margin: 5px;
        }
        .btn:hover { background: #0056b3; }
        .btn-success { background: #28a745; }
        .btn-success:hover { background: #1e7e34; }
        .btn-danger { background: #dc3545; }
        .btn-danger:hover { background: #c82333; }
        #result {
            background: #2c3e50;
            color: #ecf0f1;
            padding: 20px;
            border-radius: 8px;
            font-family: monospace;
            max-height: 400px;
            overflow-y: auto;
            white-space: pre-line;
            display: none;
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
        .warning {
            background: #fff3cd;
            border: 1px solid #ffeaa7;
            color: #856404;
            padding: 15px;
            border-radius: 8px;
            margin: 15px 0;
        }
        .progress {
            width: 100%;
            height: 20px;
            background: #e9ecef;
            border-radius: 10px;
            overflow: hidden;
            margin: 10px 0;
        }
        .progress-bar {
            height: 100%;
            background: #28a745;
            width: 0%;
            transition: width 0.3s;
        }
        .table-status {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin: 20px 0;
        }
        .table-item {
            background: #f8f9fa;
            border: 1px solid #dee2e6;
            padding: 10px;
            border-radius: 5px;
            min-width: 120px;
            text-align: center;
        }
        .table-item.success { background: #d4edda; border-color: #c3e6cb; }
        .table-item.error { background: #f8d7da; border-color: #f5c6cb; }
    </style>
</head>
<body>
    <div class="container">
        <h1>📤 Database Migrator - Import lên Cloud</h1>
        
        <div class="nav">
            <a href="admin_page.php">← Dashboard</a>
            <a href="cloud_database_guide.php">📚 Hướng dẫn</a>
            <a href="cloud_config_generator.php">🔧 Tạo Config</a>
            <a href="test_cloud_connection.php">🔍 Test Connection</a>
        </div>

        <div class="warning">
            <h4>⚠️ Lưu ý quan trọng:</h4>
            <ul>
                <li>Tool này sẽ <strong>COPY</strong> dữ liệu từ localhost lên cloud</li>
                <li>Database localhost <strong>KHÔNG</strong> bị thay đổi gì</li>
                <li>Dữ liệu trên cloud sẽ bị <strong>GHI ĐÈ</strong> nếu đã tồn tại</li>
                <li>Đảm bảo đã tạo cloud database và có thông tin kết nối</li>
            </ul>
        </div>

        <!-- Step 1: Cấu hình kết nối -->
        <div class="step">
            <h3>🔧 Bước 1: Cấu hình kết nối</h3>
            
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                <div>
                    <h4>📍 Database Nguồn (Localhost)</h4>
                    <div class="form-group">
                        <label>Host:</label>
                        <input type="text" id="src_host" value="localhost" readonly>
                    </div>
                    <div class="form-group">
                        <label>Database:</label>
                        <input type="text" id="src_name" value="shop_db">
                    </div>
                    <div class="form-group">
                        <label>Username:</label>
                        <input type="text" id="src_user" value="root">
                    </div>
                    <div class="form-group">
                        <label>Password:</label>
                        <input type="password" id="src_pass" value="191204">
                    </div>
                </div>

                <div>
                    <h4>☁️ Database Đích (Cloud)</h4>
                    <div class="form-group">
                        <label>Host:</label>
                        <input type="text" id="dest_host" placeholder="sql12.freesqldatabase.com">
                    </div>
                    <div class="form-group">
                        <label>Database:</label>
                        <input type="text" id="dest_name" placeholder="sql12xxxxx_shopdb">
                    </div>
                    <div class="form-group">
                        <label>Username:</label>
                        <input type="text" id="dest_user" placeholder="sql12xxxxx">
                    </div>
                    <div class="form-group">
                        <label>Password:</label>
                        <input type="password" id="dest_pass" placeholder="Your cloud password">
                    </div>
                </div>
            </div>

            <div style="text-align: center; margin-top: 20px;">
                <button class="btn" onclick="testConnections()">🔍 Test Kết Nối</button>
                <button class="btn btn-success" onclick="loadCloudConfig()">📂 Load từ Config File</button>
            </div>
        </div>

        <!-- Step 2: Chọn bảng cần import -->
        <div class="step">
            <h3>📋 Bước 2: Chọn bảng cần import</h3>
            
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 10px;">
                <label><input type="checkbox" id="table_users" checked> 👥 users (Người dùng)</label>
                <label><input type="checkbox" id="table_products" checked> 🛒 products (Sản phẩm)</label>
                <label><input type="checkbox" id="table_orders" checked> 📦 orders (Đơn hàng)</label>
                <label><input type="checkbox" id="table_cart" checked> 🛍️ cart (Giỏ hàng)</label>
                <label><input type="checkbox" id="table_wishlist" checked> ❤️ wishlist (Yêu thích)</label>
                <label><input type="checkbox" id="table_message" checked> 💬 message (Tin nhắn)</label>
            </div>

            <div style="text-align: center; margin-top: 20px;">
                <button class="btn" onclick="checkAll(true)">✅ Chọn tất cả</button>
                <button class="btn" onclick="checkAll(false)">❌ Bỏ chọn tất cả</button>
            </div>
        </div>

        <!-- Step 3: Import -->
        <div class="step">
            <h3>🚀 Bước 3: Bắt đầu import</h3>
            
            <div style="text-align: center;">
                <button class="btn btn-success" onclick="startMigration()" id="startBtn">
                    📤 Bắt đầu Import Database
                </button>
                <button class="btn btn-danger" onclick="stopMigration()" id="stopBtn" style="display:none;">
                    ⏹️ Dừng Import
                </button>
            </div>

            <div class="progress" style="display:none;" id="progressBar">
                <div class="progress-bar" id="progressFill"></div>
            </div>

            <div class="table-status" id="tableStatus"></div>
        </div>

        <!-- Kết quả -->
        <div id="result"></div>
    </div>

    <script>
        let migrationActive = false;

        // Test kết nối database
        async function testConnections() {
            const result = document.getElementById('result');
            result.style.display = 'block';
            result.innerHTML = '🔍 Đang test kết nối...\n';

            const data = {
                action: 'test_connections',
                src_host: document.getElementById('src_host').value,
                src_name: document.getElementById('src_name').value,
                src_user: document.getElementById('src_user').value,
                src_pass: document.getElementById('src_pass').value,
                dest_host: document.getElementById('dest_host').value,
                dest_name: document.getElementById('dest_name').value,
                dest_user: document.getElementById('dest_user').value,
                dest_pass: document.getElementById('dest_pass').value
            };

            try {
                const response = await fetch('database_migrator_handler.php', {
                    method: 'POST',
                    headers: {'Content-Type': 'application/json'},
                    body: JSON.stringify(data)
                });
                
                const text = await response.text();
                result.innerHTML += text;
            } catch (error) {
                result.innerHTML += '❌ Lỗi: ' + error.message;
            }
        }

        // Load config từ file cloud
        function loadCloudConfig() {
            // Tìm và load file config_cloud.php nếu có
            fetch('database_migrator_handler.php', {
                method: 'POST',
                headers: {'Content-Type': 'application/json'},
                body: JSON.stringify({action: 'load_cloud_config'})
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    document.getElementById('dest_host').value = data.db_host || '';
                    document.getElementById('dest_name').value = data.db_name || '';
                    document.getElementById('dest_user').value = data.db_user || '';
                    document.getElementById('dest_pass').value = data.db_pass || '';
                    alert('✅ Đã load thông tin từ config cloud!');
                } else {
                    alert('❌ Không tìm thấy file config cloud. Vui lòng tạo trước!');
                }
            })
            .catch(error => {
                alert('❌ Lỗi load config: ' + error.message);
            });
        }

        // Chọn/bỏ chọn tất cả bảng
        function checkAll(checked) {
            const checkboxes = document.querySelectorAll('input[type="checkbox"][id^="table_"]');
            checkboxes.forEach(cb => cb.checked = checked);
        }

        // Bắt đầu migration
        async function startMigration() {
            if (!confirm('⚠️ Bạn có chắc chắn muốn import database? Dữ liệu cloud sẽ bị ghi đè!')) {
                return;
            }

            migrationActive = true;
            document.getElementById('startBtn').style.display = 'none';
            document.getElementById('stopBtn').style.display = 'inline-block';
            document.getElementById('progressBar').style.display = 'block';
            document.getElementById('result').style.display = 'block';

            const selectedTables = [];
            document.querySelectorAll('input[type="checkbox"][id^="table_"]:checked').forEach(cb => {
                selectedTables.push(cb.id.replace('table_', ''));
            });

            const data = {
                action: 'migrate_database',
                src_host: document.getElementById('src_host').value,
                src_name: document.getElementById('src_name').value,
                src_user: document.getElementById('src_user').value,
                src_pass: document.getElementById('src_pass').value,
                dest_host: document.getElementById('dest_host').value,
                dest_name: document.getElementById('dest_name').value,
                dest_user: document.getElementById('dest_user').value,
                dest_pass: document.getElementById('dest_pass').value,
                tables: selectedTables
            };

            try {
                const response = await fetch('database_migrator_handler.php', {
                    method: 'POST',
                    headers: {'Content-Type': 'application/json'},
                    body: JSON.stringify(data)
                });
                
                const text = await response.text();
                document.getElementById('result').innerHTML = text;
                
                // Update progress
                updateProgress(100);
                
            } catch (error) {
                document.getElementById('result').innerHTML = '❌ Lỗi migration: ' + error.message;
            } finally {
                migrationActive = false;
                document.getElementById('startBtn').style.display = 'inline-block';
                document.getElementById('stopBtn').style.display = 'none';
            }
        }

        // Dừng migration
        function stopMigration() {
            migrationActive = false;
            document.getElementById('result').innerHTML += '\n⏹️ Đã dừng migration theo yêu cầu người dùng.';
            document.getElementById('startBtn').style.display = 'inline-block';
            document.getElementById('stopBtn').style.display = 'none';
        }

        // Update progress bar
        function updateProgress(percent) {
            document.getElementById('progressFill').style.width = percent + '%';
        }

        // Auto-fill FreeSQLDatabase template
        document.getElementById('dest_host').addEventListener('focus', function() {
            if (!this.value) {
                this.value = 'sql12.freesqldatabase.com';
                document.getElementById('dest_name').value = 'sql12xxxxx_shopdb';
                document.getElementById('dest_user').value = 'sql12xxxxx';
            }
        });
    </script>
</body>
</html>
