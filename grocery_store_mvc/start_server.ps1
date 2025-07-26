# Grocery Store Website Launcher
Write-Host "====================================="
Write-Host "   GROCERY STORE WEBSITE LAUNCHER"
Write-Host "====================================="
Write-Host ""

Write-Host "Starting PHP Development Server..."
Write-Host ""
Write-Host "Website will be available at:"
Write-Host "  - http://localhost:8000/ (Home Page - MVC Entry Point)"
Write-Host "  - http://localhost:8000/login (Login)"
Write-Host "  - http://localhost:8000/register (Register)"
Write-Host "  - http://localhost:8000/admin_page (Admin Panel)"
Write-Host "  - http://localhost:8000/shop (Shop Products)"
Write-Host "  - http://localhost:8000/cart (Shopping Cart)"
Write-Host "  - http://localhost:8000/about (About Us)"
Write-Host ""
Write-Host "Press Ctrl+C to stop the server"
Write-Host ""

php -S localhost:8000
