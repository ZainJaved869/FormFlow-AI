<!DOCTYPE html>
<html lang="en" data-bs-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'FormFlow AI')</title>

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Bootstrap 5 + Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <!-- Tailwind CSS via CDN (for utilities only) -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Custom Styles -->
    <style>
        :root {
            --primary: #6C63FF;
            --primary-dark: #3A2D9A;
            --secondary: #8B83FF;
            --glass-bg: rgba(255,255,255,0.65);
            --glass-border: rgba(255,255,255,0.2);
            --shadow: 0 20px 60px rgba(108,99,255,0.12);
            --radius: 24px;
        }

        [data-bs-theme="dark"] {
            --glass-bg: rgba(20,15,40,0.75);
            --glass-border: rgba(255,255,255,0.06);
            --shadow: 0 20px 60px rgba(0,0,0,0.4);
            --bs-body-bg: #0F0A1A;
            --bs-body-color: #E2D9FF;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: var(--bs-body-bg);
            color: var(--bs-body-color);
            transition: background 0.3s, color 0.3s;
            min-height: 100vh;
        }

        .glass {
            background: var(--glass-bg) !important;
            backdrop-filter: blur(16px) saturate(1.4);
            -webkit-backdrop-filter: blur(16px) saturate(1.4);
            border: 1px solid var(--glass-border);
            box-shadow: var(--shadow);
            border-radius: var(--radius);
        }

        .glass-sm {
            background: var(--glass-bg) !important;
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border: 1px solid var(--glass-border);
            box-shadow: var(--shadow);
            border-radius: 16px;
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            border: none;
            padding: 10px 28px;
            border-radius: 60px;
            font-weight: 600;
            transition: all 0.3s;
            box-shadow: 0 8px 30px rgba(108,99,255,0.3);
        }
        .btn-primary:hover {
            transform: translateY(-3px);
            box-shadow: 0 14px 40px rgba(108,99,255,0.45);
            color: white;
        }

        .btn-outline-primary {
            border: 2px solid var(--primary);
            color: var(--primary);
            background: transparent;
            border-radius: 60px;
            font-weight: 600;
            transition: all 0.3s;
        }
        .btn-outline-primary:hover {
            background: var(--primary);
            color: white;
            transform: translateY(-3px);
        }

        .navbar-glass {
            background: var(--glass-bg) !important;
            backdrop-filter: blur(16px);
            border-bottom: 1px solid var(--glass-border);
        }

        .sidebar {
            background: var(--glass-bg) !important;
            backdrop-filter: blur(16px);
            border-right: 1px solid var(--glass-border);
            height: 100vh;
            position: sticky;
            top: 0;
            padding: 1.5rem 1rem;
        }

        .sidebar-link {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 16px;
            border-radius: 14px;
            color: var(--bs-secondary-color);
            font-weight: 500;
            transition: all 0.2s;
            text-decoration: none;
        }
        .sidebar-link:hover, .sidebar-link.active {
            background: rgba(108,99,255,0.12);
            color: var(--primary);
        }
        .sidebar-link i {
            font-size: 1.2rem;
            width: 24px;
            text-align: center;
        }

        .stat-card {
            transition: all 0.3s;
            border: none;
        }
        .stat-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 20px 50px rgba(108,99,255,0.1);
        }
        .stat-icon {
            width: 52px;
            height: 52px;
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: rgba(108,99,255,0.1);
            color: var(--primary);
            font-size: 1.6rem;
        }

        .theme-toggle {
            width: 40px;
            height: 40px;
            border-radius: 60px;
            background: var(--glass-bg);
            border: 1px solid var(--glass-border);
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.3s;
            color: var(--bs-body-color);
        }
        .theme-toggle:hover {
            background: var(--primary);
            color: white;
            transform: rotate(20deg);
        }

        .card-premium {
            background: var(--glass-bg) !important;
            backdrop-filter: blur(12px);
            border: 1px solid var(--glass-border);
            border-radius: var(--radius);
            box-shadow: var(--shadow);
            transition: all 0.3s;
        }
        .card-premium:hover {
            transform: translateY(-4px);
            box-shadow: 0 30px 60px rgba(108,99,255,0.08);
        }

        @keyframes fadeUp {
            from { opacity:0; transform:translateY(20px); }
            to { opacity:1; transform:translateY(0); }
        }
        .animate-fade-up {
            animation: fadeUp 0.6s ease forwards;
        }
        .page-wrapper {
            animation: fadeUp 0.5s ease forwards;
        }

        /* Scrollbar */
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: var(--primary); border-radius: 12px; }
    </style>

    @stack('styles')
</head>
<body>

    @auth
    <div class="d-flex">
        <!-- Sidebar -->
        <aside class="sidebar d-none d-md-flex flex-column" style="width:260px;">
            <div class="d-flex align-items-center gap-3 px-3 mb-4">
                <div style="width:44px;height:44px;border-radius:16px;background:linear-gradient(135deg,var(--primary),var(--primary-dark));display:flex;align-items:center;justify-content:center;color:white;font-weight:800;font-size:1.4rem;">F</div>
                <span class="fw-bold fs-5" style="background:linear-gradient(135deg,var(--primary),var(--secondary));-webkit-background-clip:text;-webkit-text-fill-color:transparent;">FormFlow AI</span>
            </div>

<nav class="nav flex-column gap-1 flex-grow-1">
    <a href="{{ route('dashboard') }}" class="sidebar-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
        <i class="bi bi-grid"></i> Dashboard
    </a>

    <a href="{{ route('forms.index') }}" class="sidebar-link {{ request()->routeIs('forms.index') || request()->routeIs('forms.edit') ? 'active' : '' }}">
        <i class="bi bi-file-earmark-text"></i> My Forms
    </a>

    <a href="{{ route('forms.create') }}" class="sidebar-link {{ request()->routeIs('forms.create') ? 'active' : '' }}">
        <i class="bi bi-plus-circle"></i> New Form
    </a>

    <a href="{{ route('submissions.index') }}" class="sidebar-link {{ request()->routeIs('submissions.*') ? 'active' : '' }}">
        <i class="bi bi-table"></i> Submissions
    </a>

    <a href="{{ route('analytics') }}" class="sidebar-link {{ request()->routeIs('analytics') ? 'active' : '' }}">
        <i class="bi bi-bar-chart"></i> Analytics
    </a>

    <a href="{{ route('subscription.index') }}" class="sidebar-link {{ request()->routeIs('subscription.*') ? 'active' : '' }}">
        <i class="bi bi-credit-card"></i> Subscription
    </a>

    <a href="{{ route('profile') }}" class="sidebar-link {{ request()->routeIs('profile') ? 'active' : '' }}">
        <i class="bi bi-person"></i> Profile
    </a>

    @if(auth()->user()->is_admin)
    <a href="{{ route('admin.dashboard') }}" class="sidebar-link {{ request()->routeIs('admin.*') ? 'active' : '' }}">
        <i class="bi bi-shield-lock"></i> Admin
    </a>
    @endif
</nav>

            <hr class="border-secondary opacity-25 my-3">

            <div class="d-flex align-items-center gap-3 px-3">
                <div style="width:38px;height:38px;border-radius:60px;background:linear-gradient(135deg,var(--primary),var(--secondary));display:flex;align-items:center;justify-content:center;color:white;font-weight:700;">{{ auth()->user()->name[0] }}</div>
                <div style="flex:1;min-width:0;">
                    <div style="font-weight:600;font-size:0.9rem;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">{{ auth()->user()->name }}</div>
                    <div style="font-size:0.7rem;color:var(--bs-secondary-color);">{{ ucfirst(auth()->user()->subscription_plan ?? 'free') }}</div>
                </div>
                <div class="theme-toggle" onclick="toggleTheme()">
                    <i class="bi bi-moon-stars" id="themeIcon"></i>
                </div>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="flex-grow-1 p-4 p-md-5" style="overflow-y:auto;max-height:100vh;">
    @endauth

    @auth
        @yield('content')
    @else
        @yield('auth-content')
    @endif

    @auth
        </main>
    </div>
    @endauth

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // Dark/Light Mode Toggle
        function toggleTheme() {
            const html = document.documentElement;
            const current = html.getAttribute('data-bs-theme');
            const next = current === 'light' ? 'dark' : 'light';
            html.setAttribute('data-bs-theme', next);
            localStorage.setItem('theme', next);
            document.getElementById('themeIcon').className = next === 'dark' ? 'bi-sun' : 'bi-moon-stars';
        }

        // On load
        (function() {
            const saved = localStorage.getItem('theme');
            if (saved) {
                document.documentElement.setAttribute('data-bs-theme', saved);
                document.getElementById('themeIcon').className = saved === 'dark' ? 'bi-sun' : 'bi-moon-stars';
            }
        })();
    </script>
    @stack('scripts')
</body>
</html>