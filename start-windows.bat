@echo off
setlocal
cd /d "%~dp0"
title Sun Son Solar - Local Website

rem Always start in the folder containing spark, even when double-clicked.
if not exist "spark" goto missing_project

rem Prefer XAMPP to avoid a different PHP installation with no php.ini.
if exist "C:\xampp\php\php.exe" goto use_xampp
where php.exe >nul 2>nul
if errorlevel 1 goto missing_php
set "SOLAR_PHP=php.exe"
goto check_setup

:use_xampp
set "SOLAR_PHP=C:\xampp\php\php.exe"
set "PHPRC=C:\xampp\php"

:check_setup
"%SOLAR_PHP%" -d display_errors=1 -d display_startup_errors=1 tools\check-environment.php
if errorlevel 1 goto setup_failed
echo.
echo Starting Sun Son Solar at http://localhost:8080
echo Keep this window open. Press Ctrl+C to stop the server.
echo.
"%SOLAR_PHP%" -d display_errors=1 -d display_startup_errors=1 spark serve --host localhost --port 8080
echo.
echo The server has stopped. Any startup error is displayed above.
pause
exit /b

:missing_project
echo The spark file is missing. Extract the entire ZIP before running this launcher.
pause
exit /b 1

:missing_php
echo PHP was not found. Install XAMPP with PHP 8.2 or newer in C:\xampp.
pause
exit /b 1

:setup_failed
echo.
echo The server did not start. Fix the messages above, then run this file again.
pause
exit /b 1
