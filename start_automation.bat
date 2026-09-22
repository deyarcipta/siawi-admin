@echo off
title SIAWI Background Automation (Queue & Scheduler)
echo ========================================================
echo   SISTEM INFORMASI AKADEMIK SIAWI - BACKGROUND WORKER
echo ========================================================
echo.
echo Menjalankan Laravel Queue Worker dan Scheduler...
echo JANGAN TUTUP JENDELA INI AGAR NOTIFIKASI WA TERUS BERJALAN.
echo.

:: Menjalankan Queue Worker di background command
start "SIAWI Queue Worker" /min cmd /k "php artisan queue:work --tries=3"

:: Menjalankan Scheduler Worker di foreground
php artisan schedule:work
