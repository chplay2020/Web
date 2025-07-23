<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hướng dẫn tạo Cloud Database</title>
    <style>
        body { 
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6; 
            margin: 0; 
            padding: 20px;
            background: #f5f5f5;
        }
        .container { 
            max-width: 1200px; 
            margin: 0 auto; 
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
        }
        h1 { 
            color: #2c3e50; 
            text-align: center;
            border-bottom: 3px solid #3498db;
            padding-bottom: 10px;
        }
        .option { 
            border: 2px solid #ddd; 
            margin: 20px 0; 
            padding: 20px;
            border-radius: 8px;
            transition: all 0.3s ease;
        }
        .option:hover {
            border-color: #3498db;
            box-shadow: 0 5px 15px rgba(52, 152, 219, 0.1);
        }
        .option h3 { 
            color: #2980b9; 
            margin-top: 0;
        }
        .pros { 
            background: #e8f5e8; 
            padding: 10px;
            border-left: 4px solid #27ae60;
            margin: 10px 0;
        }
        .cons { 
            background: #fdf2e8; 
            padding: 10px;
            border-left: 4px solid #f39c12;
            margin: 10px 0;
        }
        .code { 
            background: #2c3e50; 
            color: #ecf0f1;
            padding: 15px;
            border-radius: 5px;
            font-family: 'Courier New', monospace;
            overflow-x: auto;
        }
        .step { 
            background: #f8f9fa;
            border-left: 4px solid #007bff;
            padding: 15px;
            margin: 10px 0;
        }
        .button {
            display: inline-block;
            background: #3498db;
            color: white;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 5px;
            margin: 5px;
            transition: background 0.3s;
        }
        .button:hover { background: #2980b9; }
        .free { color: #27ae60; font-weight: bold; }
        .paid { color: #e74c3c; font-weight: bold; }
        .nav { text-align: center; margin: 20px 0; }
    </style>
</head>
<body>
    <div class="container">
        <h1>🌐 Hướng dẫn tạo Cloud Database miễn phí</h1>
        
        <div class="nav">
            <a href="admin_page.php" class="button">← Dashboard Admin</a>
            <a href="cloud_config_generator.php" class="button">🔧 Tạo Config</a>
            <a href="database_migrator.php" class="button">📤 Import Database</a>
        </div>

        <!-- Option 1: FreeSQLDatabase -->
        <div class="option">
            <h3>🆓 1. FreeSQLDatabase.com <span class="free">(100% Miễn phí)</span></h3>
            
            <div class="pros">
                <strong>✅ Ưu điểm:</strong>
                <ul>
                    <li>Hoàn toàn miễn phí, không giới hạn thời gian</li>
                    <li>Không cần thẻ tín dụng</li>
                    <li>Hỗ trợ MySQL 8.0</li>
                    <li>5MB dung lượng (đủ cho dự án nhỏ)</li>
                    <li>Đăng ký nhanh chóng</li>
                </ul>
            </div>
            
            <div class="cons">
                <strong>⚠️ Nhược điểm:</strong>
                <ul>
                    <li>Dung lượng hạn chế (5MB)</li>
                    <li>Hiệu năng cơ bản</li>
                    <li>Quảng cáo trên trang đăng ký</li>
                </ul>
            </div>

            <div class="step">
                <strong>🔥 Cách đăng ký:</strong>
                <ol>
                    <li>Truy cập: <a href="https://www.freesqldatabase.com/" target="_blank">FreeSQLDatabase.com</a></li>
                    <li>Click "Create Free MySQL Database"</li>
                    <li>Điền thông tin:
                        <ul>
                            <li><strong>Database Name:</strong> shopdb</li>
                            <li><strong>Username:</strong> Tên bạn muốn</li>
                            <li><strong>Password:</strong> Mật khẩu mạnh</li>
                            <li><strong>Email:</strong> Email của bạn</li>
                        </ul>
                    </li>
                    <li>Nhận thông tin kết nối qua email</li>
                    <li>Sử dụng thông tin để cập nhật config</li>
                </ol>
            </div>

            <div class="code">
                Thông tin bạn sẽ nhận được:<br>
                Server: sql12.freesqldatabase.com<br>
                Database Name: sql12xxxxx_shopdb<br>
                Username: sql12xxxxx<br>
                Password: [mật khẩu bạn đặt]<br>
                Port: 3306
            </div>
        </div>

        <!-- Option 2: PlanetScale -->
        <div class="option">
            <h3>🚀 2. PlanetScale <span class="free">(Miễn phí có giới hạn)</span></h3>
            
            <div class="pros">
                <strong>✅ Ưu điểm:</strong>
                <ul>
                    <li>MySQL serverless hiện đại</li>
                    <li>5GB dung lượng miễn phí</li>
                    <li>Hiệu năng cao</li>
                    <li>Branching database (như Git)</li>
                    <li>Dashboard đẹp, dễ sử dụng</li>
                </ul>
            </div>
            
            <div class="cons">
                <strong>⚠️ Nhược điểm:</strong>
                <ul>
                    <li>Cần đăng ký bằng GitHub</li>
                    <li>Phức tạp hơn cho người mới</li>
                    <li>Giới hạn 1 billion reads/tháng</li>
                </ul>
            </div>

            <div class="step">
                <strong>🔥 Cách đăng ký:</strong>
                <ol>
                    <li>Truy cập: <a href="https://planetscale.com/" target="_blank">PlanetScale.com</a></li>
                    <li>Đăng ký bằng GitHub hoặc email</li>
                    <li>Tạo database mới: "grocery-store"</li>
                    <li>Chọn region gần nhất (Singapore cho VN)</li>
                    <li>Copy connection string</li>
                </ol>
            </div>
        </div>

        <!-- Option 3: Supabase -->
        <div class="option">
            <h3>🔋 3. Supabase <span class="free">(Miễn phí + nhiều tính năng)</span></h3>
            
            <div class="pros">
                <strong>✅ Ưu điểm:</strong>
                <ul>
                    <li>PostgreSQL mạnh mẽ</li>
                    <li>500MB dung lượng miễn phí</li>
                    <li>Real-time database</li>
                    <li>Authentication tích hợp</li>
                    <li>Dashboard trực quan</li>
                </ul>
            </div>
            
            <div class="cons">
                <strong>⚠️ Nhược điểm:</strong>
                <ul>
                    <li>PostgreSQL (khác với MySQL)</li>
                    <li>Cần chuyển đổi một số câu SQL</li>
                    <li>Phức tạp cho người mới</li>
                </ul>
            </div>

            <div class="step">
                <strong>🔥 Cách đăng ký:</strong>
                <ol>
                    <li>Truy cập: <a href="https://supabase.com/" target="_blank">Supabase.com</a></li>
                    <li>Đăng ký bằng GitHub</li>
                    <li>Tạo project mới</li>
                    <li>Lấy connection string từ Settings → Database</li>
                </ol>
            </div>
        </div>

        <!-- Option 4: Railway -->
        <div class="option">
            <h3>🚄 4. Railway <span class="free">(Miễn phí có hạn mức)</span></h3>
            
            <div class="pros">
                <strong>✅ Ưu điểm:</strong>
                <ul>
                    <li>$5 credit miễn phí/tháng</li>
                    <li>Deploy cả web + database</li>
                    <li>MySQL và PostgreSQL</li>
                    <li>Giao diện đẹp</li>
                    <li>Git integration</li>
                </ul>
            </div>
            
            <div class="cons">
                <strong>⚠️ Nhược điểm:</strong>
                <ul>
                    <li>Hạn mức credit ($5/tháng)</li>
                    <li>Cần thẻ tín dụng để verify</li>
                    <li>Tự động sleep nếu không dùng</li>
                </ul>
            </div>
        </div>

        <div style="background: #e8f4fd; padding: 20px; border-radius: 8px; margin: 30px 0;">
            <h3>🎯 Khuyến nghị cho dự án Grocery Store:</h3>
            <p><strong>Tốt nhất:</strong> Sử dụng <strong>FreeSQLDatabase.com</strong> vì:</p>
            <ul>
                <li>✅ Hoàn toàn miễn phí</li>
                <li>✅ Không cần thẻ tín dụng</li>
                <li>✅ MySQL tương thích 100%</li>
                <li>✅ Đủ dung lượng cho demo</li>
                <li>✅ Đăng ký nhanh trong 2 phút</li>
            </ul>
        </div>

        <div class="nav">
            <h3>🔧 Bước tiếp theo sau khi có cloud database:</h3>
            <a href="cloud_config_generator.php" class="button">1. Tạo file config mới</a>
            <a href="database_migrator.php" class="button">2. Import dữ liệu lên cloud</a>
            <a href="test_cloud_connection.php" class="button">3. Test kết nối</a>
        </div>

        <div style="text-align: center; margin-top: 30px; color: #7f8c8d;">
            <p>📞 Cần hỗ trợ? Liên hệ admin hoặc xem log lỗi trong file config</p>
        </div>
    </div>
</body>
</html>
