<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Dashboard') — {{ config('app.name') }}</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Source+Code+Pro:ital,wght@0,200..900;1,200..900&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6.5.2/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <style>
        * {
            font-family: 'Source Code Pro', ui-sans-serif, system-ui, sans-serif !important;
        }
        .brand-text { font-size: 1.5rem; }
        .content-wrapper { min-height: calc(100vh - 57px); }
    </style>
    @stack('styles')
</head>
<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">

    <nav class="main-header navbar navbar-expand navbar-white navbar-light border-bottom">
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i data-lucide="menu"></i></a>
            </li>
        </ul>

        <form action="{{ route('search') }}" method="GET" class="form-inline ml-3 grow">
            <div class="input-group input-group-sm w-100" style="max-width: 480px;">
                <input type="search" name="q" class="form-control" placeholder="Search name, passport, visa, phone, case ID…"
                       value="{{ request('q') }}">
                <div class="input-group-append">
                    <button class="btn btn-primary" type="submit"><i width="18" height="18" data-lucide="search"></i></button>
                </div>
            </div>
        </form>
    </nav>

    <aside class="main-sidebar sidebar-dark-primary elevation-4">
        <a href="{{ route('dashboard') }}" class="brand-link">
            <span class="brand-text font-weight-light ml-3" style="letter-spacing: 40px;">
    ECMS
</span>
        </a>
        <div class="sidebar">
            <nav class="mt-2">
                <ul class="nav nav-pills nav-sidebar flex-column" role="menu">
                    <li class="nav-item">
                        <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                            <i class="nav-icon" data-lucide="gauge"></i>
                            <p>Dashboard</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('citizens.index') }}" class="nav-link {{ request()->routeIs('citizens.*') ? 'active' : '' }}">
                            <i class="nav-icon" data-lucide="users"></i>
                            <p>Citizen Registry</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('visas.index') }}" class="nav-link {{ request()->routeIs('visas.*') ? 'active' : '' }}">
                            <i class="nav-icon" data-lucide="id-card"></i>
                            <p>Visa Management</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('assistance.index') }}" class="nav-link {{ request()->routeIs('assistance.*') ? 'active' : '' }}">
                            <i class="nav-icon" data-lucide="hand-helping"></i>
                            <p>Assistance</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('documents.index') }}" class="nav-link {{ request()->routeIs('documents.*') ? 'active' : '' }}">
                            <i class="nav-icon" data-lucide="folder-open"></i>
                            <p>Documents</p>
                        </a>
                    </li>
                    
                    <li class="nav-item">
                        <a href="{{ route('search') }}" class="nav-link {{ request()->routeIs('search') ? 'active' : '' }}">
                            <i class="nav-icon" data-lucide="search"></i>
                            <p>Global Search</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('reports.index') }}" class="nav-link {{ request()->routeIs('reports.*') ? 'active' : '' }}">
                            <i class="nav-icon" data-lucide="file-text"></i>
                            <p>Reports</p>
                        </a>
                    </li>
                </ul>
            </nav>
        </div>
    </aside>

    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">@yield('page-title', 'Dashboard')</h1>
                    </div>
                    @hasSection('breadcrumb')
                        <div class="col-sm-6">
                            @yield('breadcrumb')
                        </div>
                    @endif
                </div>
            </div>
        </section>

        <section class="content">
            <div class="container-fluid">
                @include('partials.flash')
                @yield('content')
            </div>
        </section>
    </div>

    <footer class="main-footer text-sm">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <strong  > Embassy Consular Management System  <a href="{{ route('dev.database-viewer') }}" class="text-decoration-none">(ECMS)</a> </strong> 
            </div>
            <div class="text-muted small">
                System health:
                <span class="badge badge-{{ $systemHealth['status'] === 'Healthy' ? 'success' : 'danger' }}">
                    {{ $systemHealth['status'] }}
                </span>
                · DB: {{ $systemHealth['db'] }}
                · Env: {{ ucfirst($systemHealth['env']) }}
            </div>
        </div>
    </footer>
</div>

@include('partials.modals')

<script src="https://cdn.jsdelivr.net/npm/jquery@3.6.4/dist/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/js/adminlte.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/lucide@1.21.0/dist/umd/lucide.min.js"></script>
<script>
    lucide.createIcons();
</script>
@stack('scripts')
</body>
</html>
