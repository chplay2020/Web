<?php
// Backend handler cho database migrator
// Xử lý các request từ frontend

header('Content-Type: text/plain; charset=utf-8');

// Nhận dữ liệu JSON từ frontend
$input = json_decode(file_get_contents('php://input'), true);
$action = $input['action'] ?? '';

try {
    switch ($action) {
        case 'test_connections':
            testConnections($input);
            break;
            
        case 'load_cloud_config':
            loadCloudConfig();
            break;
            
        case 'migrate_database':
            migrateDatabase($input);
            break;
            
        default:
            echo "❌ Action không hợp lệ: $action";
    }
} catch (Exception $e) {
    echo "❌ Lỗi: " . $e->getMessage();
}

// Test kết nối cả 2 database
function testConnections($data) {
    echo "🔍 ĐANG TEST KẾT NỐI...\n\n";
    
    // Test localhost
    echo "📍 Test kết nối Localhost:\n";
    try {
        $src_conn = new PDO(
            "mysql:host={$data['src_host']};dbname={$data['src_name']};charset=utf8",
            $data['src_user'],
            $data['src_pass']
        );
        $src_conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        // Đếm số bảng
        $stmt = $src_conn->query("SHOW TABLES");
        $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
        echo "✅ Kết nối localhost thành công!\n";
        echo "📊 Tìm thấy " . count($tables) . " bảng: " . implode(', ', $tables) . "\n\n";
        
    } catch (PDOException $e) {
        echo "❌ Lỗi kết nối localhost: " . $e->getMessage() . "\n\n";
        return;
    }
    
    // Test cloud
    echo "☁️ Test kết nối Cloud Database:\n";
    try {
        $dest_conn = new PDO(
            "mysql:host={$data['dest_host']};dbname={$data['dest_name']};charset=utf8",
            $data['dest_user'],
            $data['dest_pass']
        );
        $dest_conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        // Test query
        $stmt = $dest_conn->query("SELECT VERSION() as version");
        $version = $stmt->fetch(PDO::FETCH_ASSOC);
        echo "✅ Kết nối cloud thành công!\n";
        echo "🎯 MySQL Version: " . $version['version'] . "\n";
        echo "🌍 Host: {$data['dest_host']}\n";
        echo "🗄️ Database: {$data['dest_name']}\n\n";
        
        echo "🎉 CẢ HAI KẾT NỐI ĐỀU THÀNH CÔNG! Có thể bắt đầu migration.\n";
        
    } catch (PDOException $e) {
        echo "❌ Lỗi kết nối cloud: " . $e->getMessage() . "\n";
        echo "💡 Kiểm tra lại:\n";
        echo "   - Host có đúng không?\n";
        echo "   - Username/Password có chính xác?\n";
        echo "   - Database đã được tạo chưa?\n";
        echo "   - Firewall có block không?\n";
    }
}

// Load cloud config từ file
function loadCloudConfig() {
    header('Content-Type: application/json');
    
    $config_files = ['config_cloud.php', 'cloud_config.php'];
    $config_data = [];
    
    foreach ($config_files as $file) {
        if (file_exists($file)) {
            // Đọc file config và extract variables
            $content = file_get_contents($file);
            
            // Extract database variables
            if (preg_match('/\$db_host\s*=\s*["\']([^"\']+)["\']/', $content, $matches)) {
                $config_data['db_host'] = $matches[1];
            }
            if (preg_match('/\$db_name\s*=\s*["\']([^"\']+)["\']/', $content, $matches)) {
                $config_data['db_name'] = $matches[1];
            }
            if (preg_match('/\$db_user\s*=\s*["\']([^"\']+)["\']/', $content, $matches)) {
                $config_data['db_user'] = $matches[1];
            }
            if (preg_match('/\$db_pass\s*=\s*["\']([^"\']+)["\']/', $content, $matches)) {
                $config_data['db_pass'] = $matches[1];
            }
            
            echo json_encode(['success' => true] + $config_data);
            return;
        }
    }
    
    echo json_encode(['success' => false, 'message' => 'Không tìm thấy file config cloud']);
}

// Migration chính
function migrateDatabase($data) {
    echo "🚀 BẮT ĐẦU MIGRATION DATABASE\n";
    echo "📅 Thời gian: " . date('d/m/Y H:i:s') . "\n\n";
    
    // Kết nối nguồn
    echo "📍 Đang kết nối database nguồn (localhost)...\n";
    try {
        $src_conn = new PDO(
            "mysql:host={$data['src_host']};dbname={$data['src_name']};charset=utf8",
            $data['src_user'],
            $data['src_pass']
        );
        $src_conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        echo "✅ Kết nối nguồn thành công!\n\n";
    } catch (PDOException $e) {
        throw new Exception("Lỗi kết nối database nguồn: " . $e->getMessage());
    }
    
    // Kết nối đích
    echo "☁️ Đang kết nối database đích (cloud)...\n";
    try {
        $dest_conn = new PDO(
            "mysql:host={$data['dest_host']};dbname={$data['dest_name']};charset=utf8",
            $data['dest_user'],
            $data['dest_pass']
        );
        $dest_conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        echo "✅ Kết nối đích thành công!\n\n";
    } catch (PDOException $e) {
        throw new Exception("Lỗi kết nối database đích: " . $e->getMessage());
    }
    
    // Migration từng bảng
    $tables = $data['tables'] ?? [];
    $total_tables = count($tables);
    $completed = 0;
    
    foreach ($tables as $table) {
        echo "📋 ĐANG MIGRATE BẢNG: $table\n";
        echo "----------------------------------------\n";
        
        try {
            // 1. Lấy cấu trúc bảng
            echo "🏗️ Đang lấy cấu trúc bảng...\n";
            $create_stmt = $src_conn->query("SHOW CREATE TABLE `$table`");
            $create_info = $create_stmt->fetch(PDO::FETCH_ASSOC);
            
            if (!$create_info) {
                echo "❌ Không tìm thấy bảng $table trong database nguồn!\n\n";
                continue;
            }
            
            // 2. Tạo bảng trên cloud (DROP nếu đã có)
            echo "🔨 Đang tạo bảng trên cloud...\n";
            $dest_conn->exec("DROP TABLE IF EXISTS `$table`");
            $dest_conn->exec($create_info['Create Table']);
            echo "✅ Tạo cấu trúc bảng thành công!\n";
            
            // 3. Lấy dữ liệu từ nguồn
            echo "📊 Đang lấy dữ liệu từ nguồn...\n";
            $data_stmt = $src_conn->query("SELECT * FROM `$table`");
            $rows = $data_stmt->fetchAll(PDO::FETCH_ASSOC);
            
            if (empty($rows)) {
                echo "📭 Bảng $table không có dữ liệu.\n\n";
                $completed++;
                continue;
            }
            
            // 4. Insert dữ liệu vào cloud
            echo "💾 Đang insert " . count($rows) . " bản ghi vào cloud...\n";
            
            $columns = array_keys($rows[0]);
            $placeholders = ':' . implode(', :', $columns);
            $sql = "INSERT INTO `$table` (`" . implode('`, `', $columns) . "`) VALUES ($placeholders)";
            
            $insert_stmt = $dest_conn->prepare($sql);
            $inserted_count = 0;
            
            foreach ($rows as $row) {
                try {
                    $insert_stmt->execute($row);
                    $inserted_count++;
                } catch (PDOException $e) {
                    echo "⚠️ Lỗi insert 1 bản ghi: " . $e->getMessage() . "\n";
                }
            }
            
            echo "✅ Đã insert $inserted_count/" . count($rows) . " bản ghi thành công!\n\n";
            
        } catch (Exception $e) {
            echo "❌ Lỗi migrate bảng $table: " . $e->getMessage() . "\n\n";
        }
        
        $completed++;
        $progress = round(($completed / $total_tables) * 100);
        echo "📈 Tiến độ: $progress% ($completed/$total_tables bảng hoàn thành)\n\n";
    }
    
    // Tổng kết
    echo "🎉 HOÀN THÀNH MIGRATION!\n";
    echo "----------------------------------------\n";
    echo "📊 Thống kê:\n";
    echo "   - Tổng số bảng: $total_tables\n";
    echo "   - Đã hoàn thành: $completed\n";
    echo "   - Thời gian kết thúc: " . date('d/m/Y H:i:s') . "\n\n";
    
    echo "✅ NEXT STEPS:\n";
    echo "1. 🔧 Cập nhật các file PHP để sử dụng config cloud\n";
    echo "2. 🧪 Test website với database cloud\n";
    echo "3. 🗑️ Xóa các file migration này để bảo mật\n";
    echo "4. 💾 Backup file config cũ để phục hồi nếu cần\n\n";
    
    echo "🌐 Database cloud đã sẵn sàng sử dụng!\n";
}
?>
