<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Teacher & Student Portal') - EduPortal</title>
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Bootstrap 5.3.3 & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.2/dist/chart.umd.min.js"></script>

    <style>
        :root {
            --primary: #4f46e5;
            --primary-dark: #4338ca;
            --primary-light: #e0e7ff;
            --secondary: #06b6d4;
            --dark-sidebar: #0f172a;
            --sidebar-border: rgba(255, 255, 255, 0.08);
            --bg-canvas: #f8fafc;
            --card-border: rgba(226, 232, 240, 0.8);
            --card-shadow: 0 4px 20px -2px rgba(15, 23, 42, 0.05), 0 2px 6px -1px rgba(15, 23, 42, 0.02);
            --radius-xl: 1rem;
            --radius-md: 0.75rem;
        }

        body {
            font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            background-color: var(--bg-canvas);
            color: #1e293b;
            min-height: 100vh;
            overflow-x: hidden;
        }

        /* Top Navbar */
        .top-navbar {
            height: 70px;
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border-bottom: 1px solid rgba(226, 232, 240, 0.8);
            position: sticky;
            top: 0;
            z-index: 1020;
        }

        .brand-badge {
            background: linear-gradient(135deg, #4f46e5 0%, #06b6d4 100%);
            color: white;
            padding: 8px 12px;
            border-radius: 10px;
            font-weight: 800;
            letter-spacing: -0.5px;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            box-shadow: 0 4px 14px rgba(79, 70, 229, 0.35);
        }

        /* Sidebar Modern Styling */
        .sidebar-wrapper {
            background: #0f172a;
            background: linear-gradient(180deg, #0f172a 0%, #1e293b 100%);
            min-height: calc(100vh - 70px);
            border-right: 1px solid var(--sidebar-border);
            padding: 1.5rem 1rem;
        }

        .nav-category {
            font-size: 0.72rem;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            color: #64748b;
            font-weight: 700;
            padding: 0.75rem 0.85rem 0.35rem;
        }

        .sidebar-wrapper .nav-link {
            color: #94a3b8;
            border-radius: var(--radius-md);
            padding: 0.7rem 1rem;
            margin-bottom: 0.25rem;
            font-weight: 500;
            font-size: 0.925rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .sidebar-wrapper .nav-link i {
            font-size: 1.15rem;
            transition: transform 0.2s;
        }

        .sidebar-wrapper .nav-link:hover {
            color: #ffffff;
            background: rgba(255, 255, 255, 0.07);
            transform: translateX(3px);
        }

        .sidebar-wrapper .nav-link.active {
            color: #ffffff;
            background: linear-gradient(135deg, rgba(79, 70, 229, 0.9) 0%, rgba(6, 182, 212, 0.8) 100%);
            box-shadow: 0 4px 12px rgba(79, 70, 229, 0.25);
            font-weight: 600;
        }

        .sidebar-wrapper .nav-link.active i {
            transform: scale(1.1);
        }

        /* Content Area */
        .content-wrapper {
            padding: 2rem 2.5rem;
            min-height: calc(100vh - 70px);
        }

        /* Modern Cards */
        .card {
            border: 1px solid var(--card-border);
            border-radius: var(--radius-xl);
            box-shadow: var(--card-shadow);
            background: #ffffff;
            transition: transform 0.25s ease, box-shadow 0.25s ease;
        }

        .card:hover {
            box-shadow: 0 10px 25px -3px rgba(15, 23, 42, 0.08), 0 4px 10px -2px rgba(15, 23, 42, 0.04);
        }

        .stat-card-gradient-1 {
            background: linear-gradient(135deg, #4f46e5 0%, #6366f1 100%);
            color: white;
            border: none;
        }
        .stat-card-gradient-2 {
            background: linear-gradient(135deg, #0ea5e9 0%, #38bdf8 100%);
            color: white;
            border: none;
        }
        .stat-card-gradient-3 {
            background: linear-gradient(135deg, #f59e0b 0%, #fbbf24 100%);
            color: white;
            border: none;
        }
        .stat-card-gradient-4 {
            background: linear-gradient(135deg, #10b981 0%, #34d399 100%);
            color: white;
            border: none;
        }

        .stat-icon-pill {
            width: 52px;
            height: 52px;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(8px);
            font-size: 1.5rem;
        }

        /* Buttons & Forms */
        .btn {
            border-radius: var(--radius-md);
            padding: 0.6rem 1.25rem;
            font-weight: 600;
            font-size: 0.9rem;
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .btn-primary {
            background: linear-gradient(135deg, #4f46e5 0%, #4338ca 100%);
            border: none;
            box-shadow: 0 4px 12px rgba(79, 70, 229, 0.25);
        }

        .btn-primary:hover {
            background: linear-gradient(135deg, #4338ca 0%, #3730a3 100%);
            box-shadow: 0 6px 16px rgba(79, 70, 229, 0.35);
            transform: translateY(-1px);
        }

        .form-control, .form-select {
            border-radius: 0.65rem;
            border: 1px solid #cbd5e1;
            padding: 0.65rem 0.95rem;
            font-size: 0.925rem;
            transition: all 0.2s;
        }

        .form-control:focus, .form-select:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 4px var(--primary-light);
        }

        /* Modern Tables */
        .table {
            --bs-table-bg: transparent;
            vertical-align: middle;
        }

        .table thead th {
            font-size: 0.78rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: #64748b;
            font-weight: 700;
            padding: 1rem 1.25rem;
            border-bottom: 1px solid #e2e8f0;
            background: #f8fafc;
        }

        .table tbody td {
            padding: 1rem 1.25rem;
            border-bottom: 1px solid #f1f5f9;
            color: #334155;
            font-size: 0.925rem;
        }

        .table tbody tr:hover {
            background-color: #f8fafc;
        }

        /* Badges */
        .badge {
            padding: 0.45em 0.8em;
            font-weight: 600;
            border-radius: 8px;
            letter-spacing: 0.02em;
        }

        .badge.bg-success {
            background-color: #ecfdf5 !important;
            color: #059669 !important;
            border: 1px solid #a7f3d0;
        }
        .badge.bg-danger {
            background-color: #fef2f2 !important;
            color: #dc2626 !important;
            border: 1px solid #fecaca;
        }
        .badge.bg-warning {
            background-color: #fffbeb !important;
            color: #d97706 !important;
            border: 1px solid #fde68a;
        }
        .badge.bg-info {
            background-color: #f0fdf4 !important;
            color: #0284c7 !important;
            border: 1px solid #bae6fd;
        }
        .badge.bg-primary {
            background-color: #eef2ff !important;
            color: #4f46e5 !important;
            border: 1px solid #c7d2fe;
        }
        .badge.bg-secondary {
            background-color: #f1f5f9 !important;
            color: #475569 !important;
            border: 1px solid #e2e8f0;
        }

        /* Clean animations */
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        .animate-fade-in {
            animation: fadeIn 0.25s ease forwards;
        }

        .modal-backdrop {
            z-index: 1050;
        }

        .modal {
            z-index: 1055;
        }
    </style>
    @stack('head')
</head>
<body>
@auth
    <!-- Top Navbar -->
    <nav class="navbar top-navbar px-4">
        <div class="d-flex align-items-center gap-3">
            <a class="navbar-brand text-decoration-none m-0" href="{{ auth()->user()->isAdmin() ? route('admin.dashboard') : route('dashboard') }}">
                <span class="brand-badge">
                    <i class="bi bi-mortarboard-fill"></i> EduPortal
                </span>
            </a>
            <span class="badge bg-light text-dark border d-none d-md-inline-block py-2 px-3 fw-medium">
                <i class="bi bi-clock me-1 text-primary"></i> {{ now()->format('l, d M Y') }}
            </span>
        </div>

        <div class="d-flex align-items-center gap-3">
            <div class="d-flex align-items-center gap-2 px-3 py-1 bg-light rounded-pill border">
                @if(auth()->user()->profile_picture)
                    <img src="{{ asset('storage/'.auth()->user()->profile_picture) }}" class="rounded-circle border" width="34" height="34" style="object-fit:cover;">
                @else
                    <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center" style="width:34px;height:34px;font-size:0.85rem;font-weight:700;">
                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                    </div>
                @endif
                <div class="d-none d-sm-block text-start">
                    <div class="fw-bold small text-dark lh-1">{{ auth()->user()->name }}</div>
                    <span class="text-muted" style="font-size:0.75rem;">
                        {{ auth()->user()->hasRole('admin') ? 'Administrator' : (auth()->user()->hasRole('teacher') ? 'Teacher / Faculty' : 'Student') }}
                    </span>
                </div>
            </div>

            <form method="POST" action="{{ route('logout') }}" class="m-0">
                @csrf
                <button class="btn btn-sm btn-outline-danger d-flex align-items-center gap-1 px-3 py-2">
                    <i class="bi bi-box-arrow-right"></i> <span class="d-none d-md-inline">Logout</span>
                </button>
            </form>
        </div>
    </nav>

    <div class="container-fluid p-0">
        <div class="row g-0">
            <!-- Sidebar -->
            <aside class="col-lg-2 col-md-3 sidebar-wrapper">
                <div class="nav flex-column">
                    @if(auth()->user()->isAdmin())
                        <div class="nav-category">Academic Structure</div>
                        @php $r = fn($n) => request()->routeIs($n) ? 'active' : ''; @endphp
                        <a class="nav-link {{ $r('admin.dashboard') }}" href="{{ route('admin.dashboard') }}">
                            <i class="bi bi-grid-1x2-fill"></i> Dashboard
                        </a>
                        <a class="nav-link {{ $r('admin.classrooms.*') }}" href="{{ route('admin.classrooms.index') }}">
                            <i class="bi bi-mortarboard-fill"></i> Classes &amp; Sections
                        </a>
                        <a class="nav-link {{ $r('admin.students.*') }}" href="{{ route('admin.students.index') }}">
                            <i class="bi bi-people-fill"></i> Students Directory
                        </a>

                        <div class="nav-category mt-2">Attendance &amp; Leaves</div>
                        <a class="nav-link {{ $r('admin.attendance.*') }}" href="{{ route('admin.attendance.index') }}">
                            <i class="bi bi-calendar-check-fill"></i> Daily Attendance
                        </a>
                        <a class="nav-link {{ $r('admin.leaves.*') }}" href="{{ route('admin.leaves.index') }}">
                            <i class="bi bi-envelope-paper-heart-fill"></i> Leave Requests
                        </a>

                        <div class="nav-category mt-2">Academics & Analytics</div>
                        <a class="nav-link {{ $r('admin.grades.*') }}" href="{{ route('admin.grades.index') }}">
                            <i class="bi bi-award-fill"></i> Grade Criteria
                        </a>
                        <a class="nav-link {{ $r('admin.tasks.*') }}" href="{{ route('admin.tasks.index') }}">
                            <i class="bi bi-kanban-fill"></i> Tasks & Projects
                        </a>
                        <a class="nav-link {{ $r('admin.reports.student') }}" href="{{ route('admin.reports.student') }}">
                            <i class="bi bi-person-lines-fill"></i> Student Reports
                        </a>
                        <a class="nav-link {{ $r('admin.reports.system') }}" href="{{ route('admin.reports.system') }}">
                            <i class="bi bi-bar-chart-line-fill"></i> System Reports
                        </a>

                        <div class="nav-category mt-2">Administration</div>
                        <a class="nav-link {{ $r('admin.roles.*') }}" href="{{ route('admin.roles.index') }}">
                            <i class="bi bi-shield-lock-fill"></i> Roles & Permissions
                        </a>
                    @else
                        <div class="nav-category">Student Portal</div>
                        @php $r = fn($n) => request()->routeIs($n) ? 'active' : ''; @endphp
                        <a class="nav-link {{ $r('dashboard') }}" href="{{ route('dashboard') }}">
                            <i class="bi bi-grid-1x2-fill"></i> My Dashboard
                        </a>
                        <a class="nav-link {{ $r('attendance.*') }}" href="{{ route('attendance.index') }}">
                            <i class="bi bi-calendar-check-fill"></i> My Attendance
                        </a>
                        <a class="nav-link {{ $r('leaves.*') }}" href="{{ route('leaves.index') }}">
                            <i class="bi bi-airplane-fill"></i> Leave Requests
                        </a>
                        <a class="nav-link {{ $r('tasks.*') }}" href="{{ route('tasks.index') }}">
                            <i class="bi bi-kanban-fill"></i> My Tasks
                        </a>
                        <a class="nav-link {{ $r('profile.*') }}" href="{{ route('profile.edit') }}">
                            <i class="bi bi-person-badge-fill"></i> Profile Settings
                        </a>
                    @endif
                </div>
            </aside>

            <!-- Main Content Area -->
            <main class="col-lg-10 col-md-9 content-wrapper animate-fade-in">
                @include('partials.flash')
                @yield('content')
            </main>
        </div>
    </div>
@else
    <main class="min-h-screen d-flex align-items-center justify-content-center">
        @yield('content')
    </main>
@endauth

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
@stack('modals')
@stack('scripts')
</body>
</html>
