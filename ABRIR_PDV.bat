@echo off
setlocal
pushd "%~dp0" || exit /b 1
if not exist "php\php.exe" (
    echo Extraia o ZIP completo antes de abrir.
    pause
    exit /b 1
)
"php\php.exe" -n -d "extension_dir=php/ext" -d extension=mbstring -d extension=pdo_sqlite -r "exit(extension_loaded('pdo_sqlite') && extension_loaded('mbstring') ? 0 : 1);"
if errorlevel 1 (
    echo O PHP nao iniciou. Veja a secao Windows do README.
    pause
    exit /b 1
)
echo PDV: http://127.0.0.1:8765
echo Mantenha esta janela aberta enquanto usa o sistema.
start "" /b cmd /d /c "ping -n 2 127.0.0.1 >nul & start http://127.0.0.1:8765/"
"php\php.exe" -n -d "extension_dir=php/ext" -d extension=mbstring -d extension=pdo_sqlite -S 127.0.0.1:8765 -t public
if errorlevel 1 pause
popd
