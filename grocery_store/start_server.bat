@echo off
echo =====================================
echo    GROCERY STORE WEBSITE LAUNCHER
echo =====================================
echo.

echo Starting PHP Development Server...
echo.
echo Website will be available at:
echo   - http://localhost:8000/test_setup.php (Setup Test)
echo   - http://localhost:8000/home.php (Home Page)
echo   - http://localhost:8000/login.php (Login)
echo   - http://localhost:8000/register.php (Register)
echo   - http://localhost:8000/admin_page.php (Admin Panel)
echo.
echo Press Ctrl+C to stop the server
echo.

php -S localhost:8000
