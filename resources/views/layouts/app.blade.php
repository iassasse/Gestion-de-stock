<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'Inventory Portal') }} - Magasinier Dashboard</title>
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.2/font/bootstrap-icons.min.css" rel="stylesheet">
    <!-- Custom Premium CSS -->
    <style>
        :root {
            --primary-gradient: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
            --dark-bg: #0f172a;
            --sidebar-bg: #1e293b;
            --card-bg: #ffffff;
            --text-main: #0f172a;
            --text-muted: #64748b;
        }

        body {
            font-family: 'Outfit', sans-serif;
            background-color: #f8fafc;
            color: var(--text-main);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            overflow-x: hidden;
        }

        /* Sidebar Styling */
        #sidebar-wrapper {
            min-height: 100vh;
            width: 260px;
            background-color: var(--sidebar-bg);
            transition: all 0.3s ease;
            box-shadow: 4px 0 10px rgba(0, 0, 0, 0.05);
            z-index: 1000;
        }

        .sidebar-heading {
            padding: 1.5rem 1.2rem;
            font-size: 1.25rem;
            font-weight: 700;
            letter-spacing: 0.5px;
            background: var(--primary-gradient);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
        }

        .sidebar-nav-item {
            padding: 0.8rem 1.5rem;
            display: flex;
            align-items: center;
            color: #94a3b8;
            text-decoration: none;
            transition: all 0.2s ease;
            border-left: 4px solid transparent;
            font-weight: 500;
        }

        .sidebar-nav-item i {
            margin-right: 0.75rem;
            font-size: 1.15rem;
        }

        .sidebar-nav-item:hover, .sidebar-nav-item.active {
            color: #ffffff;
            background-color: rgba(255, 255, 255, 0.04);
            border-left-color: #6366f1;
        }

        /* Top Navbar */
        .top-navbar {
            background-color: #ffffff;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
            border-bottom: 1px solid #e2e8f0;
            padding: 1rem 1.5rem;
        }

        /* Cards & UI elements */
        .premium-card {
            background: var(--card-bg);
            border: 1px solid #e2e8f0;
            border-radius: 16px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.02), 0 2px 4px -1px rgba(0, 0, 0, 0.01);
            transition: all 0.3s ease;
        }

        .premium-card:hover {
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.05), 0 4px 6px -2px rgba(0, 0, 0, 0.03);
            transform: translateY(-2px);
        }

        .gradient-btn {
            background: var(--primary-gradient);
            color: white;
            border: none;
            font-weight: 600;
            border-radius: 8px;
            padding: 0.5rem 1.25rem;
            transition: all 0.2s ease;
        }

        .gradient-btn:hover {
            opacity: 0.9;
            color: white;
            transform: translateY(-1px);
        }

        .table-responsive {
            border-radius: 12px;
            overflow: hidden;
            border: 1px solid #e2e8f0;
        }

        .table thead th {
            background-color: #f1f5f9;
            color: #475569;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.75rem;
            letter-spacing: 0.5px;
            padding: 1rem;
        }

        .table tbody td {
            padding: 1rem;
            vertical-align: middle;
        }

        /* Page wrapper structure */
        #wrapper {
            display: flex;
            width: 100%;
            align-items: stretch;
        }

        #page-content-wrapper {
            width: 100%;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        /* Stats Cards */
        .stat-card-icon {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
        }
        
        .bg-indigo-light { background-color: #e0e7ff; color: #4338ca; }
        .bg-purple-light { background-color: #f3e8ff; color: #6b21a8; }
        .bg-pink-light { background-color: #fce7f3; color: #be185d; }
        .bg-emerald-light { background-color: #d1fae5; color: #047857; }

        /* Custom scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
        }
        ::-webkit-scrollbar-track {
            background: #f1f5f9;
        }
        ::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 4px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }
    </style>
</head>
<body>

    <div id="wrapper">
        <!-- Sidebar -->
        <div id="sidebar-wrapper">
            <div class="sidebar-heading">
                <i class="bi bi-boxes me-2"></i>Inventory App
            </div>
            <div class="list-group list-group-flush mt-3">
                <a href="{{ route('dashboard') }}" class="sidebar-nav-item {{ Route::is('dashboard') ? 'active' : '' }}">
                    <i class="bi bi-speedometer2"></i> Dashboard
                </a>
                <a href="{{ route('categories.index') }}" class="sidebar-nav-item {{ Route::is('categories.*') ? 'active' : '' }}">
                    <i class="bi bi-tags"></i> Categories
                </a>
                <a href="{{ route('materials.index') }}" class="sidebar-nav-item {{ Route::is('materials.*') ? 'active' : '' }}">
                    <i class="bi bi-hammer"></i> Materials
                </a>
                <a href="{{ route('espaces.index') }}" class="sidebar-nav-item {{ Route::is('espaces.*') ? 'active' : '' }}">
                    <i class="bi bi-geo-alt"></i> Spaces (Espaces)
                </a>
                <a href="{{ route('articles.index') }}" class="sidebar-nav-item {{ Route::is('articles.*') ? 'active' : '' }}">
                    <i class="bi bi-box-seam"></i> Articles
                </a>
                @if(Auth::user() && Auth::user()->isChefMagasinier())
                    <a href="{{ route('users.index') }}" class="sidebar-nav-item {{ Route::is('users.*') ? 'active' : '' }}">
                        <i class="bi bi-people"></i> Users
                    </a>
                @endif
            </div>
        </div>

        <!-- Page Content -->
        <div id="page-content-wrapper">
            <!-- Top Navbar -->
            <nav class="navbar navbar-expand top-navbar">
                <div class="container-fluid p-0">
                    <span class="navbar-text fw-semibold text-muted">
                        <i class="bi bi-calendar3 me-2"></i>{{ now()->format('l, d F Y') }}
                    </span>
                    <div class="collapse navbar-collapse justify-content-end" id="navbarSupportedContent">
                        <ul class="navbar-nav align-items-center">
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle text-dark fw-semibold d-flex align-items-center" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    @if(Auth::user() && Auth::user()->profile_picture)
                                        <img src="{{ asset('storage/' . Auth::user()->profile_picture) }}" alt="Avatar" class="rounded-circle me-2" style="width: 28px; height: 28px; object-fit: cover; border: 1px solid #cbd5e1;">
                                    @else
                                        <i class="bi bi-person-circle me-2 fs-5"></i>
                                    @endif
                                    <span>{{ Auth::user()->name ?? 'Magasinier' }} <small class="text-muted fw-normal" style="font-size: 0.8rem;">({{ Auth::user()->role ?? 'Magasinier' }})</small></span>
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0" aria-labelledby="navbarDropdown">
                                    <li>
                                        <a class="dropdown-item" href="{{ route('profile.edit') }}">
                                            <i class="bi bi-person me-2"></i>My Profile
                                        </a>
                                    </li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li>
                                        <form method="POST" action="{{ route('logout') }}">
                                            @csrf
                                            <button type="submit" class="dropdown-item text-danger">
                                                <i class="bi bi-box-arrow-right me-2"></i>Logout
                                            </button>
                                        </form>
                                    </li>
                                </ul>
                            </li>
                        </ul>
                    </div>
                </div>
            </nav>

            <!-- Main Content Container -->
            <div class="container-fluid p-4">
                <!-- Session Alert Messages -->
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-4" role="alert" style="border-left: 4px solid #198754 !important; border-radius: 8px;">
                        <div class="d-flex align-items-center">
                            <i class="bi bi-check-circle-fill me-2 fs-5 text-success"></i>
                            <div>{{ session('success') }}</div>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if (session('error'))
                    <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm mb-4" role="alert" style="border-left: 4px solid #dc3545 !important; border-radius: 8px;">
                        <div class="d-flex align-items-center">
                            <i class="bi bi-exclamation-triangle-fill me-2 fs-5 text-danger"></i>
                            <div>{{ session('error') }}</div>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @yield('content')
            </div>
        </div>
    </div>

    <!-- Bootstrap 5 JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
