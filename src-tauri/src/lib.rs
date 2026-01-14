use std::process::{Command, Child};
use std::sync::Mutex;
use std::thread;
use std::time::Duration;
use std::env;
use tauri::Manager;

// Global state to hold the PHP server process
struct PhpServer(Mutex<Option<Child>>);

fn start_php_server(app_dir: &str) -> Option<Child> {
    // Try to find php executable
    let php_path = if cfg!(target_os = "windows") {
        "php.exe"
    } else {
        "php"
    };

    // Start PHP built-in server
    let child = Command::new(php_path)
        .args([
            "artisan",
            "serve",
            "--host=127.0.0.1",
            "--port=50000",
            "--no-ansi",
        ])
        .current_dir(app_dir)
        .spawn();

    match child {
        Ok(process) => {
            log::info!("PHP server started with PID: {}", process.id());
            // Wait a bit for the server to start
            thread::sleep(Duration::from_millis(1500));
            Some(process)
        }
        Err(e) => {
            log::error!("Failed to start PHP server: {}", e);
            None
        }
    }
}

fn get_app_dir() -> Option<String> {
    // Check for LARAVEL_PATH environment variable first
    if let Ok(laravel_path) = env::var("LARAVEL_PATH") {
        let path = std::path::Path::new(&laravel_path);
        if path.join("artisan").exists() {
            log::info!("Using LARAVEL_PATH: {}", laravel_path);
            return Some(laravel_path);
        }
    }

    // Check current working directory
    if let Ok(cwd) = env::current_dir() {
        if cwd.join("artisan").exists() {
            log::info!("Using current directory: {}", cwd.display());
            return Some(cwd.to_string_lossy().to_string());
        }
    }

    // Check executable's directory (for development)
    if let Ok(exe_path) = env::current_exe() {
        if let Some(exe_dir) = exe_path.parent() {
            // Go up several levels to find project root
            let mut current = exe_dir.to_path_buf();
            for _ in 0..6 {
                if current.join("artisan").exists() {
                    log::info!("Found Laravel in: {}", current.display());
                    return Some(current.to_string_lossy().to_string());
                }
                if let Some(parent) = current.parent() {
                    current = parent.to_path_buf();
                } else {
                    break;
                }
            }
        }
    }

    // If running from AppImage, check the directory where AppImage is located
    if let Ok(appimage_path) = env::var("APPIMAGE") {
        if let Some(parent) = std::path::Path::new(&appimage_path).parent() {
            // Check if artisan is in the same directory
            if parent.join("artisan").exists() {
                log::info!("Found Laravel next to AppImage: {}", parent.display());
                return Some(parent.to_string_lossy().to_string());
            }
            // Check for a 'personal-project-manager' subdirectory
            let subdir = parent.join("personal-project-manager");
            if subdir.join("artisan").exists() {
                log::info!("Found Laravel in subdirectory: {}", subdir.display());
                return Some(subdir.to_string_lossy().to_string());
            }
        }
    }

    log::warn!("Could not find Laravel project directory!");
    None
}

#[cfg_attr(mobile, tauri::mobile_entry_point)]
pub fn run() {
    tauri::Builder::default()
        .manage(PhpServer(Mutex::new(None)))
        .setup(|app| {
            // Setup logging
            if cfg!(debug_assertions) {
                app.handle().plugin(
                    tauri_plugin_log::Builder::default()
                        .level(log::LevelFilter::Info)
                        .build(),
                )?;
            }

            // Start PHP server
            if let Some(app_dir) = get_app_dir() {
                log::info!("Starting PHP server in directory: {}", app_dir);
                let php_server = start_php_server(&app_dir);
                
                // Store the process handle
                let state = app.state::<PhpServer>();
                let mut server = state.0.lock().unwrap();
                *server = php_server;
            } else {
                log::error!("Laravel project directory not found! Please run from the project folder or set LARAVEL_PATH environment variable.");
            }

            Ok(())
        })
        .on_window_event(|window, event| {
            // Kill PHP server when app closes
            if let tauri::WindowEvent::CloseRequested { .. } = event {
                let state = window.state::<PhpServer>();
                let mut server = state.0.lock().unwrap();
                if let Some(ref mut process) = *server {
                    log::info!("Stopping PHP server...");
                    let _ = process.kill();
                }
            }
        })
        .run(tauri::generate_context!())
        .expect("error while running tauri application");
}
