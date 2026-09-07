<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard') | Admin Checker</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>
<body class="dashboard-body">
    <input type="checkbox" id="sidebar-toggle" class="sidebar-toggle" aria-label="Buka navigasi">
    <label for="sidebar-toggle" class="sidebar-overlay"></label>

    <aside class="sidebar">
        <div class="brand">
            <span class="brand-mark">AC</span>
            <span>
                <strong>Admin Checker</strong>
                <small>Device intelligence</small>
            </span>
        </div>

        <nav class="sidebar-nav" aria-label="Navigasi utama">
            <p class="nav-label">Workspace</p>
            <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'is-active' : '' }}">
                <span class="nav-dot"></span>Dashboard scan
            </a>
            <a href="{{ route('dashboard.live_monitoring.index') }}" class="nav-link {{ request()->routeIs('dashboard.live_monitoring.*') ? 'is-active' : '' }}">
                <span class="nav-dot"></span>Live monitoring
            </a>
            <a href="{{ route('dashboard.devices.index') }}" class="nav-link {{ request()->routeIs('dashboard.devices.*') ? 'is-active' : '' }}">
                <span class="nav-dot"></span>Daftar Device
            </a>
        </nav>

        <div class="sidebar-footer">
            <form method="POST" action="{{ route('logout') }}" class="logout-form">
                @csrf
                <button type="submit" class="logout-button">Keluar</button>
            </form>
            <div class="status-indicator"><span></span>All systems operational</div>
            <div class="profile-mini">
                <span class="avatar">AD</span>
                <span><strong>Admin</strong><small>Administrator</small></span>
            </div>
        </div>
    </aside>

    <main class="dashboard-main">
        <header class="topbar">
            <label for="sidebar-toggle" class="menu-button" aria-label="Buka navigasi">Menu</label>
            <div class="breadcrumb"><span>Workspace</span><b>/</b><strong>@yield('page-title', 'Dashboard')</strong></div>
            <div class="topbar-actions">
                <span class="live-pill"><span></span>Live</span>
                <span class="date-label">@yield('timestamp', now()->format('d M Y'))</span>
            </div>
        </header>

        <section class="page-content">
            @yield('content')
        </section>
    </main>

    @stack('scripts')
</body>
</html>
