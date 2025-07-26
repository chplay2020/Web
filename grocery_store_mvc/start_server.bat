@echo off
echo =====================================
echo    GROCERY STORE WEBSITE LAUNCHER
echo =====================================
echo.

echo Starting PHP Development Server...
echo.
echo Website will be available at:
echo   - http://localhost:8000/ (Home Page - MVC Entry Point)
echo   - http://localhost:8000/login (Login)
echo   - http://localhost:8000/register (Register)
echo   - http://localhost:8000/admin_page (Admin Panel)
echo   - http://localhost:8000/shop (Shop Products)
echo   - http://localhost:8000/cart (Shopping Cart)
echo   - http://localhost:8000/about (About Us)
echo.
echo Press Ctrl+C to stop the server
echo.

php -S localhost:8000
