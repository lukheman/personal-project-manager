#!/bin/bash

# Personal Project Manager - Desktop Launcher
# This script starts both the Laravel backend and the Tauri desktop app

SCRIPT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
APP_DIR="$SCRIPT_DIR"

# Check if we're running from the AppImage bundle directory
if [ -f "$SCRIPT_DIR/src-tauri/target/release/bundle/appimage/Personal Project Manager_0.1.0_amd64.AppImage" ]; then
    APPIMAGE="$SCRIPT_DIR/src-tauri/target/release/bundle/appimage/Personal Project Manager_0.1.0_amd64.AppImage"
elif [ -f "$SCRIPT_DIR/Personal Project Manager_0.1.0_amd64.AppImage" ]; then
    APPIMAGE="$SCRIPT_DIR/Personal Project Manager_0.1.0_amd64.AppImage"
else
    echo "Error: AppImage not found!"
    echo "Please build the AppImage first with: npm run build:appimage"
    exit 1
fi

# Check if PHP is available
if ! command -v php &> /dev/null; then
    echo "Error: PHP is not installed or not in PATH"
    echo "Please install PHP 8.2+ to run this application"
    exit 1
fi

# Check if we're in the Laravel project directory
if [ ! -f "$APP_DIR/artisan" ]; then
    echo "Error: This script must be run from the Laravel project directory"
    exit 1
fi

echo "🚀 Starting Personal Project Manager..."

# Start PHP server in background
echo "📦 Starting Laravel server on http://127.0.0.1:50000..."
php artisan serve --host=127.0.0.1 --port=50000 --no-ansi &
PHP_PID=$!

# Wait for server to start
sleep 2

# Check if server started successfully
if ! kill -0 $PHP_PID 2>/dev/null; then
    echo "Error: Failed to start Laravel server"
    exit 1
fi

echo "✅ Laravel server started (PID: $PHP_PID)"

# Start AppImage
echo "🖥️  Launching desktop application..."
"$APPIMAGE" &
APP_PID=$!

# Cleanup function
cleanup() {
    echo ""
    echo "🛑 Shutting down..."
    
    # Kill AppImage
    if kill -0 $APP_PID 2>/dev/null; then
        kill $APP_PID 2>/dev/null
        echo "   Desktop app stopped"
    fi
    
    # Kill PHP server
    if kill -0 $PHP_PID 2>/dev/null; then
        kill $PHP_PID 2>/dev/null
        echo "   Laravel server stopped"
    fi
    
    echo "👋 Goodbye!"
    exit 0
}

# Set up signal handlers
trap cleanup SIGINT SIGTERM

echo ""
echo "═══════════════════════════════════════════════════════"
echo "  Personal Project Manager is running!"
echo "  Press Ctrl+C to stop"
echo "═══════════════════════════════════════════════════════"
echo ""

# Wait for either process to exit
wait $APP_PID

# If AppImage exits, cleanup
cleanup
