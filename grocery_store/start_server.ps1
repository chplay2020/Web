# Grocery Store Website Launcher
Write-Host "====================================="
Write-Host "   GROCERY STORE WEBSITE LAUNCHER"
Write-Host "====================================="
Write-Host ""

Write-Host "Starting PHP Development Server..."
Write-Host ""
Write-Host "Website will be available at:"
Write-Host "  - http://localhost:8000/test_setup.php (Setup Test)"
Write-Host "  - http://localhost:8000/home.php (Home Page)"
Write-Host "  - http://localhost:8000/login.php (Login)"
Write-Host "  - http://localhost:8000/register.php (Register)"
Write-Host "  - http://localhost:8000/admin_page.php (Admin Panel)"
Write-Host ""
Write-Host "Press Ctrl+C to stop the server"
Write-Host ""

php -S localhost:8000
