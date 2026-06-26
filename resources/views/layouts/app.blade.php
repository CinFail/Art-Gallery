<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') — Art Gallery MS</title>

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,400;0,600;1,400&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
    <!-- SweetAlert2 -->
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">

    <style>
        :root {
            --sidebar-width: 260px;
            --brand-dark:    #000000;
            --brand-gold:    #ffffff;
            --brand-cream:   #ffffff;
            --brand-accent:  #888888;
            --sidebar-bg:    #000000;
            --sidebar-text:  rgba(255,255,255,0.75);
            --sidebar-active:#ffffff;
        }

        body {
            font-family: 'DM Sans', sans-serif;
            background: var(--brand-cream);
            color: #333333;
        }

        h1, h2, h3, h4, h5, .brand-font {
            font-family: 'Cormorant Garamond', serif;
        }

        /* ---- Sidebar ---- */
        #sidebar {
            position: fixed;
            top: 0; left: 0; bottom: 0;
            width: var(--sidebar-width);
            background: var(--sidebar-bg);
            z-index: 1000;
            overflow-y: auto;
            transition: transform .25s ease;
        }

        .sidebar-brand {
            padding: 1.5rem 1.25rem 1rem;
            border-bottom: 1px solid rgba(255,255,255,.2);
        }

        .sidebar-brand h4 {
            color: var(--brand-gold);
            font-size: 1.35rem;
            letter-spacing: .03em;
            margin: 0;
        }

        .sidebar-brand small {
            color: var(--sidebar-text);
            font-size: .72rem;
            letter-spacing: .08em;
            text-transform: uppercase;
        }

        .sidebar-nav { padding: .75rem 0; }

        .sidebar-section {
            padding: .6rem 1.25rem .25rem;
            font-size: .65rem;
            letter-spacing: .1em;
            text-transform: uppercase;
            color: rgba(255,255,255,.5);
        }

        .sidebar-nav .nav-link {
            display: flex;
            align-items: center;
            gap: .65rem;
            padding: .55rem 1.25rem;
            color: var(--sidebar-text);
            font-size: .875rem;
            border-left: 3px solid transparent;
            transition: all .15s;
        }

        .sidebar-nav .nav-link:hover,
        .sidebar-nav .nav-link.active {
            color: var(--brand-gold);
            background: rgba(201,168,76,.07);
            border-left-color: var(--brand-gold);
        }

        .sidebar-nav .nav-link i { font-size: 1rem; opacity: .85; }

        /* ---- Top bar ---- */
        #topbar {
            position: fixed;
            top: 0;
            left: var(--sidebar-width);
            right: 0;
            height: 58px;
            background: white;
            border-bottom: 1px solid #e0e0e0;
            display: flex;
            align-items: center;
            padding: 0 1.5rem;
            z-index: 900;
            gap: 1rem;
        }

        /* ---- Main content ---- */
        #main-content {
            margin-left: var(--sidebar-width);
            padding-top: 58px;
            min-height: 100vh;
        }

        .content-area {
            padding: 1.75rem 2rem;
        }

        /* ---- Cards ---- */
        .card {
            border: 1px solid #e0e0e0;
            border-radius: 8px;
            box-shadow: 0 1px 4px rgba(0,0,0,.05);
        }

        .card-header {
            background: white;
            border-bottom: 1px solid #e0e0e0;
            font-family: 'Cormorant Garamond', serif;
            font-size: 1.1rem;
            font-weight: 600;
            padding: .85rem 1.25rem;
            color: #000000;
        }

        /* ---- Stat cards ---- */
        .stat-card {
            background: white;
            border: 1px solid #e0e0e0;
            border-radius: 10px;
            padding: 1.25rem 1.5rem;
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .stat-icon {
            width: 52px; height: 52px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            flex-shrink: 0;
        }

        .stat-value {
            font-family: 'Cormorant Garamond', serif;
            font-size: 2rem;
            font-weight: 600;
            line-height: 1;
            color: #000000;
        }

        .stat-label {
            font-size: .75rem;
            letter-spacing: .06em;
            text-transform: uppercase;
            color: #888888;
        }

        /* ---- Tables ---- */
        .table { font-size: .875rem; }
        .table thead th {
            background: #f5f5f5;
            font-family: 'Cormorant Garamond', serif;
            font-weight: 600;
            font-size: .95rem;
            border-bottom: 2px solid #e0e0e0;
            color: #000000;
        }

        /* ---- Badges ---- */
        .badge-role {
            font-size: .7rem;
            padding: .3em .65em;
            border-radius: 30px;
            font-weight: 500;
            letter-spacing: .04em;
        }

        /* ---- Buttons ---- */
        .btn-primary {
            background: var(--brand-dark);
            border-color: var(--brand-dark);
        }
        .btn-primary:hover {
            background: #2e1f10;
            border-color: #2e1f10;
        }

        .btn-gold {
            background: #ffffff;
            border-color: #ffffff;
            color: #000000;
        }
        .btn-gold:hover { background: #e0e0e0; border-color: #e0e0e0; color: #000000; }

        /* ---- Action badge colours ---- */
        .badge-login  { background: #d1f0e5; color: #1a6e4a; }
        .badge-logout { background: #e8e8e8; color: #555; }
        .badge-create { background: #d6eaff; color: #0c50a0; }
        .badge-update { background: #fff3cd; color: #7a5b00; }
        .badge-delete { background: #fde8e8; color: #9b1c1c; }

        /* ---- Page title ---- */
        .page-title {
            font-family: 'Cormorant Garamond', serif;
            font-size: 1.75rem;
            font-weight: 600;
            color: #000000;
            margin-bottom: 0;
        }

        /* ---- Responsive ---- */
        @media (max-width: 768px) {
            #sidebar { transform: translateX(-100%); }
            #sidebar.show { transform: translateX(0); }
            #topbar, #main-content { left: 0; margin-left: 0; }
        }
    </style>
    @stack('styles')
</head>
<body>

<!-- Sidebar -->
<nav id="sidebar">
    <div class="sidebar-brand">
        <h4><i class="bi bi-palette2 me-2"></i>ArtGallery MS</h4>
        <small>Management System</small>
    </div>

    <ul class="sidebar-nav nav flex-column">
        <!-- Dashboard -->
        <li class="nav-item">
            <a href="{{ route('dashboard') }}"
               class="nav-link @if(request()->routeIs('dashboard')) active @endif">
                <i class="bi bi-speedometer2"></i> Dashboard
            </a>
        </li>

        <!-- Maintenance -->
        <li><div class="sidebar-section">Maintenance</div></li>

        @can('view artists')
        <li class="nav-item">
            <a href="{{ route('artists.index') }}"
               class="nav-link @if(request()->routeIs('artists.*')) active @endif">
                <i class="bi bi-person-badge"></i> Artists
            </a>
        </li>
        @endcan

        @can('view artworks')
        <li class="nav-item">
            <a href="{{ route('artworks.index') }}"
               class="nav-link @if(request()->routeIs('artworks.*')) active @endif">
                <i class="bi bi-image"></i> Artworks
            </a>
        </li>
        @endcan

        @can('manage artworks')
        <li class="nav-item">
            <a href="{{ route('tags.index') }}"
               class="nav-link @if(request()->routeIs('tags.*')) active @endif">
                <i class="bi bi-tags"></i> Tags
            </a>
        </li>
        @endcan

        @can('view groups')
        <li class="nav-item">
            <a href="{{ route('groups.index') }}"
               class="nav-link @if(request()->routeIs('groups.*')) active @endif">
                <i class="bi bi-collection"></i> Artwork Groups
            </a>
        </li>
        @endcan

        @can('view customers')
        <li class="nav-item">
            <a href="{{ route('customers.index') }}"
               class="nav-link @if(request()->routeIs('customers.*')) active @endif">
                <i class="bi bi-people"></i> Customers
            </a>
        </li>
        @endcan

        <!-- Administration -->
        @canany(['manage users', 'manage roles', 'view activity logs'])
        <li><div class="sidebar-section">Administration</div></li>

        @can('manage users')
        <li class="nav-item">
            <a href="{{ route('users.index') }}"
               class="nav-link @if(request()->routeIs('users.*')) active @endif">
                <i class="bi bi-person-gear"></i> Users
            </a>
        </li>
        @endcan

        @can('manage roles')
        <li class="nav-item">
            <a href="{{ route('roles.index') }}"
               class="nav-link @if(request()->routeIs('roles.*')) active @endif">
                <i class="bi bi-shield-lock"></i> Roles
            </a>
        </li>
        @endcan

        @can('view activity logs')
        <li class="nav-item">
            <a href="{{ route('activity-logs.index') }}"
               class="nav-link @if(request()->routeIs('activity-logs.*')) active @endif">
                <i class="bi bi-journal-text"></i> Activity Logs
            </a>
        </li>
        @endcan
        @endcanany
    </ul>
</nav>

<!-- Top Bar -->
<div id="topbar">
    <button class="btn btn-sm btn-outline-secondary d-md-none" onclick="document.getElementById('sidebar').classList.toggle('show')">
        <i class="bi bi-list"></i>
    </button>

    <span class="text-muted small d-none d-md-block">
        <i class="bi bi-geo-alt me-1"></i>@yield('breadcrumb', 'Dashboard')
    </span>

    <div class="ms-auto d-flex align-items-center gap-3">
        <span class="small text-muted d-none d-sm-block">
            <i class="bi bi-person-circle me-1"></i>
            {{ auth()->user()->name }}
            <span class="badge badge-role ms-1"
                  style="background:#f0e8d5;color:#5a3e1b;">
                {{ auth()->user()->roles->first()?->name ?? 'No Role' }}
            </span>
        </span>

        <form action="{{ route('logout') }}" method="POST" class="mb-0">
            @csrf
            <button type="submit" class="btn btn-sm btn-outline-danger">
                <i class="bi bi-box-arrow-right me-1"></i>Logout
            </button>
        </form>
    </div>
</div>

<!-- Main Content -->
<div id="main-content">
    <div class="content-area">

        {{-- Flash Messages --}}
        @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show mb-3" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        @endif

        @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show mb-3" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        @endif

        @yield('content')
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@stack('scripts')
<script>
document.querySelectorAll('.confirm-delete').forEach(function(form) {
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        var name = form.dataset.name || 'this record';
        Swal.fire({
            title: 'Are you sure?',
            html: 'You are about to delete <strong>' + name + '</strong>.<br>This action cannot be undone.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#000000',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Yes, delete it',
            cancelButtonText: 'Cancel',
            reverseButtons: true
        }).then(function(result) {
            if (result.isConfirmed) {
                form.submit();
            }
        });
    });
});
</script>
</body>
</html>
