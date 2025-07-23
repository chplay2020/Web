<?php
// File đồng bộ database giữa các máy khác nhau
// Chỉ admin mới được sử dụng file này

@include 'config.php';
session_start();

// Kiểm tra quyền admin
$admin_id = $_SESSION['admin_id'] ?? null;
if (!isset($admin_id)) {
    die('Chỉ admin mới có quyền truy cập!');
}

?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>Đồng bộ Database</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }

        .section {
            margin: 20px 0;
            padding: 15px;
            border: 1px solid #ddd;
        }

        button {
            padding: 10px 15px;
            margin: 5px;
            cursor: pointer;
        }

        .export {
            background: #4CAF50;
            color: white;
        }

        .import {
            background: #2196F3;
            color: white;
        }

        textarea {
            width: 100%;
            height: 200px;
        }
    </style>
</head>

<body>
    <h1>🔄 Công cụ đồng bộ Database</h1>

    <div class="section">
        <h3>📤 Export dữ liệu (Máy nguồn)</h3>
        <p>Xuất dữ liệu từ database hiện tại để chuyển sang máy khác:</p>
        <button class="export" onclick="exportData()">Export Database</button>
        <textarea id="exportResult" placeholder="Kết quả export sẽ hiển thị ở đây..."></textarea>
    </div>

    <div class="section">
        <h3>📥 Import dữ liệu (Máy đích)</h3>
        <p>Nhập dữ liệu từ máy khác vào database hiện tại:</p>
        <textarea id="importData" placeholder="Dán dữ liệu export vào đây..."></textarea><br>
        <button class="import" onclick="importData()">Import Database</button>
        <div id="importResult"></div>
    </div>

    <script>
        // Export dữ liệu database
        function exportData() {
            fetch('sync_database.php?action=export')
                .then(response => response.text())
                .then(data => {
                    document.getElementById('exportResult').value = data;
                    alert('✅ Export thành công! Copy dữ liệu và paste vào máy đích.');
                })
                .catch(error => {
                    alert('❌ Lỗi export: ' + error);
                });
        }

        // Import dữ liệu database  
        function importData() {
            const data = document.getElementById('importData').value;
            if (!data.trim()) {
                alert('⚠️ Vui lòng nhập dữ liệu cần import!');
                return;
            }

            fetch('sync_database.php?action=import', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    },
                    body: 'data=' + encodeURIComponent(data)
                })
                .then(response => response.text())
                .then(result => {
                    document.getElementById('importResult').innerHTML = result;
                })
                .catch(error => {
                    alert('❌ Lỗi import: ' + error);
                });
        }
    </script>

    <?php
    // Xử lý Export dữ liệu
    if (isset($_GET['action']) && $_GET['action'] == 'export') {
        try {
            $tables = ['users', 'products', 'orders', 'cart', 'wishlist', 'message'];
            $exportData = [];

            foreach ($tables as $table) {
                $stmt = $conn->prepare("SELECT * FROM `$table`");
                $stmt->execute();
                $exportData[$table] = $stmt->fetchAll(PDO::FETCH_ASSOC);
            }

            // Trả về dữ liệu dạng JSON
            header('Content-Type: text/plain');
            echo base64_encode(json_encode($exportData));
            exit;
        } catch (Exception $e) {
            header('Content-Type: text/plain');
            echo "ERROR: " . $e->getMessage();
            exit;
        }
    }

    // Xử lý Import dữ liệu
    if (isset($_GET['action']) && $_GET['action'] == 'import' && isset($_POST['data'])) {
        try {
            $importData = json_decode(base64_decode($_POST['data']), true);

            if (!$importData) {
                echo '<div style="color:red;">❌ Dữ liệu không hợp lệ!</div>';
                exit;
            }

            $conn->beginTransaction();

            foreach ($importData as $table => $rows) {
                // Xóa dữ liệu cũ
                $conn->exec("TRUNCATE TABLE `$table`");

                // Insert dữ liệu mới
                if (!empty($rows)) {
                    $columns = array_keys($rows[0]);
                    $placeholders = ':' . implode(', :', $columns);
                    $sql = "INSERT INTO `$table` (`" . implode('`, `', $columns) . "`) VALUES ($placeholders)";

                    $stmt = $conn->prepare($sql);
                    foreach ($rows as $row) {
                        $stmt->execute($row);
                    }
                }
            }

            $conn->commit();
            echo '<div style="color:green;">✅ Import thành công!</div>';
        } catch (Exception $e) {
            $conn->rollback();
            echo '<div style="color:red;">❌ Lỗi import: ' . $e->getMessage() . '</div>';
        }
        exit;
    }
    ?>

    <div class="section">
        <h3>📋 Hướng dẫn sử dụng:</h3>
        <ol>
            <li><strong>Trên máy nguồn:</strong> Click "Export Database" và copy dữ liệu</li>
            <li><strong>Trên máy đích:</strong> Paste dữ liệu vào ô Import và click "Import Database"</li>
            <li><strong>Lưu ý:</strong> Import sẽ ghi đè toàn bộ dữ liệu hiện tại!</li>
        </ol>

        <p><a href="admin_page.php">← Quay lại Dashboard Admin</a></p>
    </div>

</body>

</html>