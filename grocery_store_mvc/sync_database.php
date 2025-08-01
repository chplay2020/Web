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
        <button class="export" onclick="exportData()">Export Database (Base64)</button>
        <button class="export" onclick="exportDataRaw()" style="background: #FF9800;">Export Database (Raw JSON)</button>
        <textarea id="exportResult" placeholder="Kết quả export sẽ hiển thị ở đây..."></textarea>
    </div>

    <div class="section">
        <h3>📥 Import dữ liệu (Máy đích)</h3>
        <p>Nhập dữ liệu từ máy khác vào database hiện tại:</p>
        <textarea id="importData" placeholder="Dán dữ liệu export vào đây..."></textarea><br>
        <button class="import" onclick="importData()">Import Database (Auto-detect)</button>
        <button class="import" onclick="importDataRaw()" style="background: #FF9800;">Import Raw JSON</button>
        <div id="importResult"></div>
    </div>

    <script>
        // Export dữ liệu database
        function exportData() {
            document.getElementById('exportResult').value = 'Đang export...';

            fetch('sync_database.php?action=export')
                .then(response => response.text())
                .then(data => {
                    if (data.startsWith('ERROR:')) {
                        alert('❌ Lỗi export: ' + data);
                        document.getElementById('exportResult').value = '';
                        return;
                    }

                    document.getElementById('exportResult').value = data;

                    // Thử decode để hiển thị thông tin
                    try {
                        const decoded = JSON.parse(atob(data));
                        if (decoded.metadata) {
                            alert(`✅ Export thành công!\n📅 Thời gian: ${decoded.metadata.export_time}\n📊 Tổng records: ${decoded.metadata.total_records}\n📋 Bảng: ${decoded.metadata.tables.join(', ')}\n\n📋 Copy dữ liệu và paste vào máy đích.`);
                        } else {
                            alert('✅ Export thành công! Copy dữ liệu và paste vào máy đích.');
                        }
                    } catch (e) {
                        alert('✅ Export thành công! Copy dữ liệu và paste vào máy đích.');
                    }
                })
                .catch(error => {
                    alert('❌ Lỗi export: ' + error);
                    document.getElementById('exportResult').value = '';
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

        // Export dữ liệu database (Raw JSON)
        function exportDataRaw() {
            document.getElementById('exportResult').value = 'Đang export...';

            fetch('sync_database.php?action=export_raw')
                .then(response => response.text())
                .then(data => {
                    if (data.startsWith('ERROR:')) {
                        alert('❌ Lỗi export: ' + data);
                        document.getElementById('exportResult').value = '';
                        return;
                    }

                    document.getElementById('exportResult').value = data;
                    alert('✅ Export Raw JSON thành công! Copy dữ liệu và paste vào máy đích.');
                })
                .catch(error => {
                    alert('❌ Lỗi export: ' + error);
                    document.getElementById('exportResult').value = '';
                });
        }

        // Import dữ liệu database (Raw JSON)
        function importDataRaw() {
            const data = document.getElementById('importData').value;
            if (!data.trim()) {
                alert('⚠️ Vui lòng nhập dữ liệu cần import!');
                return;
            }

            fetch('sync_database.php?action=import_raw', {
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
    // Xử lý Export dữ liệu (Base64)
    if (isset($_GET['action']) && $_GET['action'] == 'export') {
        try {
            $tables = ['users', 'products', 'orders', 'cart', 'wishlist', 'message'];
            $exportData = [];
            $totalRecords = 0;

            foreach ($tables as $table) {
                $stmt = $conn->prepare("SELECT * FROM `$table`");
                $stmt->execute();
                $tableData = $stmt->fetchAll(PDO::FETCH_ASSOC);
                $exportData[$table] = $tableData;
                $totalRecords += count($tableData);
            }

            // Tạo metadata
            $metadata = [
                'export_time' => date('Y-m-d H:i:s'),
                'total_records' => $totalRecords,
                'tables' => array_keys($exportData),
                'version' => '1.0'
            ];

            $finalData = [
                'metadata' => $metadata,
                'data' => $exportData
            ];

            // Trả về dữ liệu dạng JSON được encode base64
            header('Content-Type: text/plain');
            $jsonData = json_encode($finalData, JSON_UNESCAPED_UNICODE);
            if ($jsonData === false) {
                echo "ERROR: Không thể encode JSON - " . json_last_error_msg();
                exit;
            }
            echo base64_encode($jsonData);
            exit;
        } catch (Exception $e) {
            header('Content-Type: text/plain');
            echo "ERROR: " . $e->getMessage();
            exit;
        }
    }

    // Xử lý Export dữ liệu (Raw JSON)
    if (isset($_GET['action']) && $_GET['action'] == 'export_raw') {
        try {
            $tables = ['users', 'products', 'orders', 'cart', 'wishlist', 'message'];
            $exportData = [];
            $totalRecords = 0;

            foreach ($tables as $table) {
                $stmt = $conn->prepare("SELECT * FROM `$table`");
                $stmt->execute();
                $tableData = $stmt->fetchAll(PDO::FETCH_ASSOC);
                $exportData[$table] = $tableData;
                $totalRecords += count($tableData);
            }

            // Tạo metadata
            $metadata = [
                'export_time' => date('Y-m-d H:i:s'),
                'total_records' => $totalRecords,
                'tables' => array_keys($exportData),
                'version' => '1.0',
                'format' => 'raw_json'
            ];

            $finalData = [
                'metadata' => $metadata,
                'data' => $exportData
            ];

            // Trả về dữ liệu JSON thô
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode($finalData, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
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
            $rawData = trim($_POST['data']);

            // Debug: Kiểm tra dữ liệu đầu vào
            if (empty($rawData)) {
                echo '<div style="color:red;">❌ Không có dữ liệu để import!</div>';
                exit;
            }

            echo '<div style="color:blue;">🔍 Độ dài dữ liệu: ' . strlen($rawData) . ' ký tự</div>';
            echo '<div style="color:blue;">🔍 Dữ liệu bắt đầu: ' . substr($rawData, 0, 100) . '...</div>';
            echo '<div style="color:blue;">🔍 Dữ liệu kết thúc: ...' . substr($rawData, -50) . '</div>';

            // Loại bỏ ký tự xuống dòng và khoảng trắng
            $cleanData = preg_replace('/\s+/', '', $rawData);

            // Thử decode base64
            $decodedData = base64_decode($cleanData, true);
            if ($decodedData === false) {
                echo '<div style="color:red;">❌ Dữ liệu không phải định dạng base64 hợp lệ!</div>';
                echo '<div style="color:orange;">� Có thể dữ liệu bị cắt hoặc copy không đủ!</div>';
                echo '<div style="color:orange;">🔍 Ký tự đầu tiên: "' . substr($cleanData, 0, 1) . '" (ASCII: ' . ord(substr($cleanData, 0, 1)) . ')</div>';

                // Thử fix một số lỗi thường gặp
                echo '<div style="color:blue;">🔄 Đang thử sửa tự động...</div>';

                // Thử bỏ các ký tự không hợp lệ
                $fixedData = preg_replace('/[^A-Za-z0-9+\/=]/', '', $cleanData);

                // Thử thêm padding nếu thiếu
                $missing = strlen($fixedData) % 4;
                if ($missing) {
                    $fixedData .= str_repeat('=', 4 - $missing);
                }

                $decodedData = base64_decode($fixedData, true);
                if ($decodedData === false) {
                    echo '<div style="color:red;">❌ Vẫn không thể decode sau khi sửa!</div>';
                    echo '<div style="color:orange;">📋 Hướng dẫn:</div>';
                    echo '<div style="color:orange;">1. Đảm bảo copy TOÀN BỘ dữ liệu export</div>';
                    echo '<div style="color:orange;">2. Không để thừa khoảng trắng đầu/cuối</div>';
                    echo '<div style="color:orange;">3. Paste một lần, không copy từng phần</div>';
                    exit;
                } else {
                    echo '<div style="color:green;">✅ Đã sửa thành công!</div>';
                }
            }

            // Kiểm tra JSON
            $importData = json_decode($decodedData, true);
            if ($importData === null) {
                echo '<div style="color:red;">❌ Dữ liệu JSON không hợp lệ!</div>';
                echo '<div style="color:orange;">🔍 JSON Error: ' . json_last_error_msg() . '</div>';
                echo '<div style="color:orange;">🔍 Decoded data preview: ' . substr($decodedData, 0, 200) . '...</div>';
                exit;
            }

            // Kiểm tra cấu trúc dữ liệu
            if (!is_array($importData)) {
                echo '<div style="color:red;">❌ Cấu trúc dữ liệu không đúng! Cần là array.</div>';
                exit;
            }

            // Kiểm tra format mới hoặc cũ
            if (isset($importData['metadata']) && isset($importData['data'])) {
                // Format mới có metadata
                echo '<div style="color:blue;">📋 Import data version: ' . ($importData['metadata']['version'] ?? 'Unknown') . '</div>';
                echo '<div style="color:blue;">📅 Export time: ' . ($importData['metadata']['export_time'] ?? 'Unknown') . '</div>';
                echo '<div style="color:blue;">📊 Total records: ' . ($importData['metadata']['total_records'] ?? 'Unknown') . '</div>';
                $actualData = $importData['data'];
            } else {
                // Format cũ - data trực tiếp
                echo '<div style="color:orange;">⚠️ Sử dụng format cũ (không có metadata)</div>';
                $actualData = $importData;
            }

            $conn->beginTransaction();            // Danh sách bảng được phép import
            $allowedTables = ['users', 'products', 'orders', 'cart', 'wishlist', 'message'];
            $importedTables = [];

            foreach ($actualData as $table => $rows) {
                // Kiểm tra bảng có được phép không
                if (!in_array($table, $allowedTables)) {
                    throw new Exception("Bảng '$table' không được phép import!");
                }

                echo "<div style='color:blue;'>🔄 Đang xử lý bảng: $table</div>";

                // Xóa dữ liệu cũ
                $conn->exec("TRUNCATE TABLE `$table`");

                // Insert dữ liệu mới
                if (!empty($rows) && is_array($rows)) {
                    if (!is_array($rows[0])) {
                        throw new Exception("Dữ liệu bảng '$table' không đúng format!");
                    }

                    $columns = array_keys($rows[0]);
                    $placeholders = ':' . implode(', :', $columns);
                    $sql = "INSERT INTO `$table` (`" . implode('`, `', $columns) . "`) VALUES ($placeholders)";

                    $stmt = $conn->prepare($sql);
                    foreach ($rows as $rowIndex => $row) {
                        try {
                            $stmt->execute($row);
                        } catch (Exception $e) {
                            throw new Exception("Lỗi insert bảng '$table' dòng $rowIndex: " . $e->getMessage());
                        }
                    }
                    echo "<div style='color:green;'>✅ Import bảng '$table': " . count($rows) . " dòng</div>";
                } else {
                    echo "<div style='color:orange;'>⚠️ Bảng '$table' không có dữ liệu</div>";
                }

                $importedTables[] = $table;
            }

            $conn->commit();
            echo '<div style="color:green;font-weight:bold;">🎉 Import thành công tất cả bảng: ' . implode(', ', $importedTables) . '</div>';
        } catch (Exception $e) {
            $conn->rollback();
            echo '<div style="color:red;">❌ Lỗi import: ' . $e->getMessage() . '</div>';
        }
        exit;
    }

    // Xử lý Import dữ liệu (Raw JSON)
    if (isset($_GET['action']) && $_GET['action'] == 'import_raw' && isset($_POST['data'])) {
        try {
            $rawData = trim($_POST['data']);

            // Debug: Kiểm tra dữ liệu đầu vào
            if (empty($rawData)) {
                echo '<div style="color:red;">❌ Không có dữ liệu để import!</div>';
                exit;
            }

            echo '<div style="color:blue;">🔍 Import Raw JSON mode</div>';
            echo '<div style="color:blue;">🔍 Độ dài dữ liệu: ' . strlen($rawData) . ' ký tự</div>';

            // Kiểm tra JSON trực tiếp
            $importData = json_decode($rawData, true);
            if ($importData === null) {
                echo '<div style="color:red;">❌ Dữ liệu JSON không hợp lệ!</div>';
                echo '<div style="color:orange;">🔍 JSON Error: ' . json_last_error_msg() . '</div>';
                echo '<div style="color:orange;">🔍 Data preview: ' . substr($rawData, 0, 200) . '...</div>';
                exit;
            }

            // Kiểm tra cấu trúc dữ liệu
            if (!is_array($importData)) {
                echo '<div style="color:red;">❌ Cấu trúc dữ liệu không đúng! Cần là array.</div>';
                exit;
            }

            // Kiểm tra format
            if (isset($importData['metadata']) && isset($importData['data'])) {
                echo '<div style="color:blue;">📋 Import data version: ' . ($importData['metadata']['version'] ?? 'Unknown') . '</div>';
                echo '<div style="color:blue;">📅 Export time: ' . ($importData['metadata']['export_time'] ?? 'Unknown') . '</div>';
                echo '<div style="color:blue;">📊 Total records: ' . ($importData['metadata']['total_records'] ?? 'Unknown') . '</div>';
                $actualData = $importData['data'];
            } else {
                echo '<div style="color:orange;">⚠️ Sử dụng format cũ (không có metadata)</div>';
                $actualData = $importData;
            }

            $conn->beginTransaction();

            $allowedTables = ['users', 'products', 'orders', 'cart', 'wishlist', 'message'];
            $importedTables = [];

            foreach ($actualData as $table => $rows) {
                if (!in_array($table, $allowedTables)) {
                    throw new Exception("Bảng '$table' không được phép import!");
                }

                echo "<div style='color:blue;'>🔄 Đang xử lý bảng: $table</div>";

                $conn->exec("TRUNCATE TABLE `$table`");

                if (!empty($rows) && is_array($rows)) {
                    if (!is_array($rows[0])) {
                        throw new Exception("Dữ liệu bảng '$table' không đúng format!");
                    }

                    $columns = array_keys($rows[0]);
                    $placeholders = ':' . implode(', :', $columns);
                    $sql = "INSERT INTO `$table` (`" . implode('`, `', $columns) . "`) VALUES ($placeholders)";

                    $stmt = $conn->prepare($sql);
                    foreach ($rows as $rowIndex => $row) {
                        try {
                            $stmt->execute($row);
                        } catch (Exception $e) {
                            throw new Exception("Lỗi insert bảng '$table' dòng $rowIndex: " . $e->getMessage());
                        }
                    }
                    echo "<div style='color:green;'>✅ Import bảng '$table': " . count($rows) . " dòng</div>";
                } else {
                    echo "<div style='color:orange;'>⚠️ Bảng '$table' không có dữ liệu</div>";
                }

                $importedTables[] = $table;
            }

            $conn->commit();
            echo '<div style="color:green;font-weight:bold;">🎉 Import Raw JSON thành công tất cả bảng: ' . implode(', ', $importedTables) . '</div>';
        } catch (Exception $e) {
            $conn->rollback();
            echo '<div style="color:red;">❌ Lỗi import raw: ' . $e->getMessage() . '</div>';
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