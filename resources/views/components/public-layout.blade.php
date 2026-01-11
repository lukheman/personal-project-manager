@props([
    'title' => 'Project Tracker',
    'brandName' => 'Personal Project Manager'
])

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title }}</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    @livewireStyles
    <style>
        :root {
            --primary-color: #6366f1;
            --primary-dark: #4f46e5;
            --primary-light: #818cf8;
            --secondary-color: #0ea5e9;
            --success-color: #10b981;
            --warning-color: #f59e0b;
            --danger-color: #ef4444;

            /* Light theme (default) */
            --bg-primary: #f1f5f9;
            --bg-secondary: #ffffff;
            --bg-tertiary: #f8fafc;
            --text-primary: #1e293b;
            --text-secondary: #64748b;
            --text-muted: #94a3b8;
            --border-color: #e2e8f0;
            --border-light: #f1f5f9;
            --card-shadow: 0 1px 3px rgba(0,0,0,0.12), 0 1px 2px rgba(0,0,0,0.24);
        }

        [data-theme="dark"] {
            --bg-primary: #0f172a;
            --bg-secondary: #1e293b;
            --bg-tertiary: #334155;
            --text-primary: #f1f5f9;
            --text-secondary: #cbd5e1;
            --text-muted: #94a3b8;
            --border-color: #334155;
            --border-light: #475569;
            --card-shadow: 0 1px 3px rgba(0,0,0,0.3), 0 1px 2px rgba(0,0,0,0.4);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            background: var(--bg-primary);
            color: var(--text-primary);
            transition: background-color 0.3s ease, color 0.3s ease;
            min-height: 100vh;
        }

        .navbar-brand {
            font-weight: 700;
            font-size: 1.25rem;
            color: var(--primary-color) !important;
        }

        .navbar {
            background: var(--bg-secondary) !important;
            border-bottom: 1px solid var(--border-color);
            box-shadow: var(--card-shadow);
        }

        .modern-card {
            background: var(--bg-secondary);
            border-radius: 16px;
            padding: 1.75rem;
            box-shadow: var(--card-shadow);
            border: 1px solid var(--border-light);
        }

        .badge-modern {
            padding: 0.5rem 1rem;
            border-radius: 50px;
            font-size: 0.875rem;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .badge-warning { background: rgba(245, 158, 11, 0.1); color: var(--warning-color); }
        .badge-info { background: rgba(14, 165, 233, 0.1); color: var(--secondary-color); }
        .badge-success { background: rgba(16, 185, 129, 0.1); color: var(--success-color); }
        .badge-primary { background: rgba(99, 102, 241, 0.1); color: var(--primary-color); }

        .progress-modern {
            height: 12px;
            border-radius: 50px;
            background: var(--border-light);
            overflow: hidden;
        }

        .progress-bar-modern {
            height: 100%;
            border-radius: 50px;
            background: linear-gradient(90deg, var(--primary-color), var(--primary-light));
            transition: width 0.5s ease;
        }

        .theme-toggle {
            background: transparent;
            border: none;
            color: var(--text-secondary);
            cursor: pointer;
            padding: 0.5rem;
            border-radius: 8px;
            transition: all 0.2s;
        }

        .theme-toggle:hover {
            background: var(--bg-tertiary);
            color: var(--primary-color);
        }

        .table-modern {
            border-collapse: separate;
            border-spacing: 0 0.5rem;
        }

        .table-modern thead th {
            border: none;
            background: transparent !important;
            color: var(--text-secondary);
            font-weight: 600;
            font-size: 0.875rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            padding: 0.75rem 1rem;
        }

        .table-modern tbody tr {
            background: var(--bg-tertiary) !important;
            border-radius: 8px;
        }

        .table-modern tbody td {
            padding: 1rem;
            border: none;
            vertical-align: middle;
            color: var(--text-primary);
            background: transparent !important;
        }

        .table-modern tbody tr td:first-child {
            border-top-left-radius: 8px;
            border-bottom-left-radius: 8px;
        }

        .table-modern tbody tr td:last-child {
            border-top-right-radius: 8px;
            border-bottom-right-radius: 8px;
        }

        [data-theme="dark"] .table-modern tbody tr {
            background: var(--bg-tertiary) !important;
        }

        .stat-box {
            background: var(--bg-tertiary);
            border-radius: 12px;
            padding: 1.25rem;
            text-align: center;
        }

        .stat-value {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--text-primary);
        }

        .stat-label {
            font-size: 0.875rem;
            color: var(--text-muted);
        }

        .feature-item {
            padding: 1rem;
            border-bottom: 1px solid var(--border-color);
        }

        .feature-item:last-child {
            border-bottom: none;
        }

        .payment-status-paid {
            color: var(--success-color);
        }

        .payment-status-pending {
            color: var(--warning-color);
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg sticky-top">
        <div class="container">
            <a class="navbar-brand" href="#">
                <i class="fas fa-layer-group me-2"></i>{{ $brandName }}
            </a>
            <button class="theme-toggle" onclick="toggleTheme()" title="Toggle theme">
                <i id="theme-icon" class="fas fa-moon"></i>
            </button>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="py-5">
        <div class="container">
            {{ $slot }}
        </div>
    </main>

    <!-- Footer -->
    <footer class="py-4 text-center" style="color: var(--text-muted);">
        <small>&copy; {{ date('Y') }} {{ $brandName }}. All rights reserved.</small>
    </footer>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/js/bootstrap.bundle.min.js"></script>
    <script>
        function initTheme() {
            const savedTheme = localStorage.getItem('theme');
            const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;

            if (savedTheme) {
                document.documentElement.setAttribute('data-theme', savedTheme);
            } else if (prefersDark) {
                document.documentElement.setAttribute('data-theme', 'dark');
            }

            updateThemeIcon();
        }

        function toggleTheme() {
            const currentTheme = document.documentElement.getAttribute('data-theme');
            const newTheme = currentTheme === 'dark' ? 'light' : 'dark';

            document.documentElement.setAttribute('data-theme', newTheme);
            localStorage.setItem('theme', newTheme);
            updateThemeIcon();
        }

        function updateThemeIcon() {
            const isDark = document.documentElement.getAttribute('data-theme') === 'dark';
            const themeIcon = document.getElementById('theme-icon');
            if (themeIcon) {
                themeIcon.className = isDark ? 'fas fa-sun' : 'fas fa-moon';
            }
        }

        initTheme();
    </script>
    @livewireScripts
</body>
</html>
