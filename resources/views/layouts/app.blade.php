<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Sikasir Angkringan' }}</title>
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    <style>
        :root {
            --primary:       #0FA4AF;
            --primary-hover: #0c8c96;
            --primary-light: rgba(15,164,175,0.10);
            --dark:          #002529;
            --dark-2:        #003135;
            --dark-3:        #024950;
            --accent:        #964734;
            --light:         #AFDDE5;
            --background:    #0d1f23;
            --surface:       #162a2f;
            --card:          #ffffff;
            --card-border:   rgba(255,255,255,0.07);
            --text:          #0F172A;
            --text-muted:    #64748B;
            --border-color:  #E2E8F0;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: var(--background);
            color: var(--text);
            overflow-x: hidden;
            margin: 0;
            padding: 0;
        }

        /* Dark page background dot-grid + ambient glow */
        body::before {
            content: '';
            position: fixed;
            inset: 0;
            background-image: radial-gradient(rgba(175,221,229,0.045) 1px, transparent 1px);
            background-size: 36px 36px;
            pointer-events: none;
            z-index: 0;
        }
        body::after {
            content: '';
            position: fixed;
            top: -200px;
            right: -200px;
            width: 600px;
            height: 600px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(15,164,175,0.10) 0%, transparent 65%);
            pointer-events: none;
            z-index: 0;
        }

        /* Override Autofill styles */
        input:-webkit-autofill,
        input:-webkit-autofill:hover, 
        input:-webkit-autofill:focus, 
        input:-webkit-autofill:active {
            -webkit-box-shadow: 0 0 0 30px #F8FAFC inset !important;
            -webkit-text-fill-color: #0F172A !important;
        }

        /* Override Bootstrap utility colors */
        .bg-primary { background-color: var(--primary) !important; }
        .text-primary { color: var(--primary) !important; }
        .bg-dark { background-color: #002529 !important; }
        /* text-dark stays readable dark text, NOT the dark layout color */
        .text-dark { color: #0F172A !important; }
        .bg-success { background-color: #16A34A !important; }
        .text-success { color: #16A34A !important; }
        .bg-warning { background-color: #F59E0B !important; }
        .text-warning { color: #F59E0B !important; }
        .bg-danger { background-color: var(--accent) !important; }
        .text-danger { color: var(--accent) !important; }

        /* Buttons */
        .btn {
            font-size: 0.9rem;
            padding: 0.55rem 1.25rem;
            border-radius: 0.5rem;
            font-weight: 600;
            transition: all 0.2s ease;
        }
        .btn-sm {
            padding: 0.35rem 0.85rem;
            font-size: 0.8rem;
        }
        .btn-primary {
            background-color: var(--primary);
            border-color: var(--primary);
            color: #ffffff;
        }
        .btn-primary:hover, .btn-primary:focus, .btn-primary:active {
            background-color: var(--primary-hover) !important;
            border-color: var(--primary-hover) !important;
            color: #ffffff !important;
        }
        .btn-success {
            background-color: #16A34A;
            border-color: #16A34A;
        }
        .btn-success:hover, .btn-success:focus, .btn-success:active {
            background-color: #15803d !important;
            border-color: #15803d !important;
        }
        .btn-danger {
            background-color: var(--accent);
            border-color: var(--accent);
        }
        .btn-danger:hover, .btn-danger:focus, .btn-danger:active {
            background-color: #843b2b !important;
            border-color: #843b2b !important;
        }
        .btn-warning {
            background-color: #F59E0B;
            border-color: #F59E0B;
            color: #fff;
        }
        .btn-warning:hover, .btn-warning:focus, .btn-warning:active {
            background-color: #d97706 !important;
            border-color: #d97706 !important;
            color: #fff !important;
        }
        .btn-outline-primary {
            color: var(--primary);
            border-color: var(--primary);
        }
        .btn-outline-primary:hover, .btn-outline-primary:focus, .btn-outline-primary:active {
            background-color: var(--primary) !important;
            border-color: var(--primary) !important;
            color: #fff !important;
        }
        .btn-outline-secondary {
            color: var(--text-muted);
            border-color: var(--border-color);
            background-color: transparent;
        }
        .btn-outline-secondary:hover, .btn-outline-secondary:focus, .btn-outline-secondary:active {
            background-color: #F8FAFC !important;
            border-color: #CBD5E1 !important;
            color: var(--text) !important;
        }
        /* Detail / secondary action button */
        .btn-action {
            background-color: #F8FAFC;
            border: 1px solid var(--border-color);
            color: #475569;
            font-weight: 600;
        }
        .btn-action:hover {
            background-color: #F1F5F9;
            border-color: #CBD5E1;
            color: var(--text);
        }
        /* btn-action inside dark card-header */
        .card-header .btn-action {
            background: rgba(255,255,255,0.10) !important;
            border-color: rgba(255,255,255,0.15) !important;
            color: rgba(255,255,255,0.85) !important;
        }
        .card-header .btn-action:hover {
            background: rgba(255,255,255,0.18) !important;
            border-color: rgba(255,255,255,0.25) !important;
            color: #ffffff !important;
        }

        /* ── Card System ────────────────────────────────── */
        .card {
            background-color: var(--card);
            border: 1px solid rgba(0,0,0,0.07);
            border-radius: 1rem;
            box-shadow:
                0 0 0 1px rgba(0,0,0,0.04),
                0 4px 16px rgba(0,0,0,0.12),
                0 1px 4px rgba(0,0,0,0.06);
            transition: transform 0.18s ease, box-shadow 0.18s ease;
            overflow: hidden;
        }
        .card-stat {
            box-shadow:
                0 0 0 1px rgba(0,0,0,0.04),
                0 8px 24px rgba(0,0,0,0.14),
                0 2px 6px rgba(0,0,0,0.06);
        }
        .card-hover:hover {
            transform: translateY(-2px);
            box-shadow:
                0 0 0 1px rgba(15,164,175,0.12),
                0 12px 32px rgba(0,0,0,0.16),
                0 4px 8px rgba(0,0,0,0.08);
        }
        /* Card header: dark gradient matches login header */
        .card-header {
            border-bottom: 1px solid rgba(0,0,0,0.08);
            background: linear-gradient(135deg, #003135 0%, #024950 100%);
            padding: 0.875rem 1.375rem;
        }
        .card-header span,
        .card-header .fw-bold,
        .card-header .fw-semibold,
        .card-header a.btn-action {
            color: rgba(255,255,255,0.9) !important;
        }
        .card-header .badge {
            background-color: rgba(255,255,255,0.12) !important;
            color: rgba(175,221,229,1) !important;
        }
        .card-footer {
            border-top: 1px solid var(--border-color);
            background-color: #F8FAFC;
            padding: 0.875rem 1.375rem;
        }
        .card-body {
            padding: 1.375rem;
        }
        .shadow-sm {
            box-shadow: 0 4px 16px rgba(0,0,0,0.12) !important;
        }

        /* Table Design: clean integrated borders, hover row, custom headers */
        .table-responsive {
            border: none !important;
            border-radius: 0 !important;
            margin: 0;
        }
        .table {
            margin-bottom: 0;
            color: var(--text);
            border-collapse: collapse;
            width: 100%;
        }
        .table th {
            font-weight: 700;
            color: var(--text-muted);
            text-transform: uppercase;
            font-size: 0.7rem;
            letter-spacing: 0.06em;
            padding: 0.85rem 1.25rem;
            background-color: #F8FAFC;
            border-bottom: 1px solid var(--border-color);
            border-top: none;
            white-space: nowrap;
        }
        .table td {
            padding: 0.875rem 1.25rem;
            vertical-align: middle;
            border-bottom: 1px solid var(--border-color);
            color: #334155;
            font-size: 0.875rem;
        }
        .table tr:last-child td {
            border-bottom: none;
        }
        .table-hover tbody tr:hover td {
            background-color: rgba(15, 164, 175, 0.03);
        }
        /* Override Bootstrap .table-light to use design system */
        .table-light th, thead.table-light th {
            background-color: #F8FAFC !important;
            color: var(--text-muted) !important;
            border-color: var(--border-color) !important;
        }
        /* Override default bootstrap border utilities on tables */
        .table-bordered, .table-bordered th, .table-bordered td {
            border: none !important;
            border-bottom: 1px solid var(--border-color) !important;
        }
        .table-bordered tr:last-child td {
            border-bottom: none !important;
        }
        /* fw-extrabold utility — matches Plus Jakarta Sans weight 800 */
        .fw-extrabold {
            font-weight: 800 !important;
        }

        /* Form Controls */
        .form-control, .form-select, .input-group-text {
            border-radius: 0.5rem;
            border: 1px solid var(--border-color);
            padding: 0.625rem 1rem;
            font-size: 0.95rem;
            transition: all 0.2s ease;
        }
        .form-control:focus, .form-select:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(15, 164, 175, 0.15);
        }
        .input-group > .form-control,
        .input-group > .form-select {
            border-top-right-radius: 0;
            border-bottom-right-radius: 0;
        }
        .input-group > button {
            border-top-right-radius: 0.5rem;
            border-bottom-right-radius: 0.5rem;
        }
        /* Form label global */
        label.form-label {
            font-size: 0.82rem;
            font-weight: 600;
            color: #1E293B;
            margin-bottom: 0.375rem;
        }

        /* Badges */
        .badge {
            padding: 0.4em 0.7em;
            font-weight: 600;
            border-radius: 0.375rem;
            font-size: 0.75rem;
        }
        .bg-success-subtle {
            background-color: rgba(22, 163, 74, 0.08) !important;
            color: #16A34A !important;
        }
        .bg-danger-subtle {
            background-color: rgba(150, 71, 52, 0.08) !important;
            color: var(--accent) !important;
        }
        .bg-warning-subtle {
            background-color: rgba(245, 158, 11, 0.08) !important;
            color: #d97706 !important;
        }
        .bg-secondary-subtle {
            background-color: #F1F5F9 !important;
            color: #475569 !important;
        }
        .bg-primary-subtle {
            background-color: rgba(15, 164, 175, 0.08) !important;
            color: var(--primary) !important;
        }
        .bg-info-subtle {
            background-color: rgba(15, 164, 175, 0.08) !important;
            color: var(--primary) !important;
        }

        /* Pagination: clean borderless box links */
        .pagination {
            gap: 0.25rem;
            margin-bottom: 0;
        }
        .page-item .page-link {
            border-radius: 0.375rem !important;
            border: 1px solid var(--border-color);
            color: var(--text-muted);
            padding: 0.45rem 0.85rem;
            font-size: 0.85rem;
            transition: all 0.2s ease;
        }
        .page-item .page-link:hover {
            background-color: #F8FAFC;
            color: var(--primary);
            border-color: var(--border-color);
        }
        .page-item.active .page-link {
            background-color: var(--primary) !important;
            border-color: var(--primary) !important;
            color: #ffffff !important;
        }
        .page-item.disabled .page-link {
            background-color: transparent;
            color: #CBD5E1;
            border-color: #E2E8F0;
        }

        /* Alerts: soft backgrounds, borderless */
        .alert {
            border-radius: 0.75rem;
            border: none;
            padding: 1rem 1.25rem;
            font-size: 0.9rem;
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.01);
        }
        .alert-success {
            background-color: rgba(22, 163, 74, 0.08) !important;
            color: #16A34A !important;
        }
        .alert-danger {
            background-color: rgba(150, 71, 52, 0.08) !important;
            color: var(--accent) !important;
        }
        .alert-info {
            background-color: var(--primary-light) !important;
            color: var(--primary) !important;
        }

        /* Modals: clean 18px radius */
        .modal-content {
            border-radius: 1.125rem;
            border: none;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.05), 0 10px 10px -5px rgba(0, 0, 0, 0.02);
        }
        .modal-header {
            border-bottom: 1px solid var(--border-color);
            padding: 1.25rem 1.5rem;
        }
        .modal-footer {
            border-top: 1px solid var(--border-color);
            padding: 1.25rem 1.5rem;
        }

        /* ── Layout ─────────────────────────────────────── */
        .app-container {
            display: flex;
            min-height: 100vh;
            position: relative;
            z-index: 1;
        }

        .sidebar {
            width: 260px;
            background: linear-gradient(180deg, #002529 0%, #003135 40%, #024950 100%);
            border-right: 1px solid rgba(255,255,255,0.06);
            display: flex;
            flex-direction: column;
            position: fixed;
            top: 0;
            bottom: 0;
            left: 0;
            z-index: 100;
            transition: all 0.2s ease;
        }

        .sidebar-brand {
            padding: 1.5rem 1.25rem 1.25rem;
            border-bottom: 1px solid rgba(255,255,255,0.07);
            font-weight: 800;
            font-size: 1.2rem;
            color: #ffffff;
            letter-spacing: -0.02em;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 0.625rem;
        }

        .sidebar-menu {
            padding: 1.5rem 1rem;
            flex-grow: 1;
            overflow-y: auto;
        }

        .menu-label {
            font-size: 0.72rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            color: rgba(255, 255, 255, 0.4);
            margin-bottom: 0.75rem;
            padding-left: 0.75rem;
            margin-top: 1.5rem;
        }
        .menu-label:first-of-type {
            margin-top: 0;
        }

        .menu-item {
            margin-bottom: 0.25rem;
        }

        .menu-link {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.65rem 0.75rem;
            color: rgba(255, 255, 255, 0.75);
            font-weight: 500;
            font-size: 0.92rem;
            text-decoration: none;
            border-radius: 0.375rem;
            transition: all 0.2s ease;
        }

        .menu-link i:first-child {
            font-size: 1rem;
            color: rgba(255, 255, 255, 0.5);
            transition: all 0.2s ease;
        }

        .menu-link:hover {
            color: #ffffff;
            background-color: rgba(255, 255, 255, 0.05);
        }

        .menu-link:hover i:first-child {
            color: #ffffff;
        }

        .menu-link.active {
            background: linear-gradient(135deg, rgba(15,164,175,0.18) 0%, rgba(15,164,175,0.10) 100%);
            border-left: 2.5px solid var(--primary);
            color: var(--primary);
            font-weight: 600;
        }

        .menu-link.active i:first-child {
            color: var(--primary);
        }

        .menu-dropdown {
            margin-top: 0.25rem;
            padding-left: 1rem;
            list-style: none;
        }

        .menu-dropdown-link {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.45rem 0.75rem;
            color: rgba(255, 255, 255, 0.6);
            text-decoration: none;
            font-size: 0.85rem;
            border-radius: 0.25rem;
            transition: all 0.2s ease;
        }

        .menu-dropdown-link:hover, .menu-dropdown-link.active {
            color: var(--primary);
            background-color: rgba(255, 255, 255, 0.03);
        }

        .sidebar-profile {
            padding: 1rem 1.25rem;
            border-top: 1px solid rgba(255,255,255,0.07);
            background: rgba(0,0,0,0.2);
        }

        .profile-card {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .profile-avatar {
            width: 36px;
            height: 36px;
            border-radius: 10px;
            background: linear-gradient(135deg, #003135, #0FA4AF);
            color: #ffffff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 800;
            font-size: 0.9rem;
            flex-shrink: 0;
            box-shadow: 0 2px 8px rgba(15,164,175,0.3);
        }

        .profile-info {
            flex-grow: 1;
            min-width: 0;
        }

        .profile-name {
            font-weight: 600;
            font-size: 0.88rem;
            color: #ffffff;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            margin-bottom: 0.1rem;
        }

        .profile-role {
            font-size: 0.72rem;
            color: rgba(255, 255, 255, 0.45);
            text-transform: capitalize;
        }

        .main-content {
            margin-left: 260px;
            flex-grow: 1;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            transition: all 0.2s ease;
        }

        .top-navbar {
            background: linear-gradient(135deg, #002529 0%, #003135 60%, #024950 100%);
            border-bottom: 1px solid rgba(255,255,255,0.07);
            height: 65px;
            padding: 0 1.5rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 90;
            box-shadow: 0 4px 16px rgba(0,0,0,0.2);
        }

        /* Navbar breadcrumb text: white on dark navbar */
        .top-navbar .text-muted {
            color: rgba(175,221,229,0.65) !important;
        }
        /* Navbar user button */
        .top-navbar .btn-link {
            color: #ffffff !important;
        }
        .top-navbar .text-dark {
            color: rgba(255,255,255,0.9) !important;
        }
        /* Hamburger on mobile */
        .top-navbar .btn-outline-secondary {
            border-color: rgba(255,255,255,0.2);
            color: rgba(255,255,255,0.8);
        }
        .top-navbar .btn-outline-secondary:hover {
            background: rgba(255,255,255,0.08);
            border-color: rgba(255,255,255,0.3);
            color: #fff;
        }

        .content-body {
            padding: 1.75rem 2rem;
            flex-grow: 1;
        }

        /* ── Page-level typography on dark background ──────── */
        /* The standard page header pattern: div > div > h1/h4 + p.text-muted */
        .content-body .h4.fw-bold,
        .content-body .h5.fw-bold,
        .content-body h1.h4,
        .content-body h1.h5 {
            color: #f0f9fa !important;
        }
        /* Subtitle text below page headings */
        .content-body > div > div > p.text-muted,
        .content-body > div > p.text-muted {
            color: rgba(175,221,229,0.55) !important;
        }


        /* Number inputs: ensure value is always readable */
        input[type="number"] {
            color: var(--text) !important;
            -moz-appearance: textfield;
        }
        input[type="number"]::-webkit-outer-spin-button,
        input[type="number"]::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }
        /* Ensure all form inputs have clear text color */
        .form-control, .form-select {
            color: #0F172A !important;
        }
        .form-control:disabled, .form-control[readonly] {
            color: #64748B !important;
            background-color: #F8FAFC;
        }

        /* Modern POS product cards and images */
        .pos-product-card {
            border: 1px solid var(--border-color) !important;
            border-radius: 1rem !important; /* Large radius */
            overflow: hidden;
            transition: all 0.2s ease;
            background: #ffffff;
        }
        .pos-product-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(0, 0, 0, 0.04);
            border-color: var(--primary) !important;
        }
        .pos-product-img {
            height: 110px;
            object-fit: cover;
            background-color: #F8FAFC;
        }
        .pos-product-placeholder {
            height: 110px;
            background-color: #F8FAFC;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--text-muted);
        }

        /* Responsive adjustments */
        @media (max-width: 991.98px) {
            .sidebar {
                transform: translateX(-100%);
            }
            .main-content {
                margin-left: 0;
            }
            .sidebar.show {
                transform: translateX(0);
            }
        }
    </style>
    @stack('styles')
</head>
<body>

    @auth
        <!-- Desktop Sidebar -->
        <aside class="sidebar d-none d-lg-flex">
            <a href="{{ route('home') }}" class="sidebar-brand">
                <div style="width:32px;height:32px;border-radius:9px;background:linear-gradient(135deg,#003135 0%,#024950 50%,#0FA4AF 100%);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                    <svg width="18" height="18" viewBox="0 0 28 28" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M7 20 C7 20 8 13 14 13 C20 13 21 20 21 20" stroke="white" stroke-width="2.2" stroke-linecap="round"/>
                        <line x1="5" y1="20" x2="23" y2="20" stroke="white" stroke-width="2.2" stroke-linecap="round"/>
                        <circle cx="11" cy="10" r="1.2" fill="rgba(255,255,255,0.65)"/>
                        <circle cx="14" cy="8" r="1.3" fill="white"/>
                        <circle cx="17" cy="10" r="1.2" fill="rgba(255,255,255,0.65)"/>
                    </svg>
                </div>
                <span>Sikasir</span>
            </a>
            
            <div class="sidebar-menu">
                @if (auth()->user()->isOwner())
                    <div class="menu-label">Navigasi Owner</div>
                    <div class="menu-item">
                        <a href="{{ route('owner.dashboard') }}" class="menu-link {{ request()->routeIs('owner.dashboard') ? 'active' : '' }}">
                            <i class="bi bi-speedometer2"></i> Dashboard
                        </a>
                    </div>
                    <div class="menu-item">
                        <a href="{{ route('owner.categories.index') }}" class="menu-link {{ request()->routeIs('owner.categories.*') ? 'active' : '' }}">
                            <i class="bi bi-tags"></i> Kategori
                        </a>
                    </div>
                    <div class="menu-item">
                        <a href="{{ route('owner.products.index') }}" class="menu-link {{ request()->routeIs('owner.products.*') ? 'active' : '' }}">
                            <i class="bi bi-bag"></i> Produk
                        </a>
                    </div>
                    
                    <div class="menu-item">
                        <a href="{{ route('owner.stock.index') }}" class="menu-link {{ request()->routeIs('owner.stock.*') ? 'active' : '' }}">
                            <i class="bi bi-box-seam"></i> Manajemen Stok
                        </a>
                    </div>

                    <div class="menu-item">
                        <a href="{{ route('owner.expenses.index') }}" class="menu-link {{ request()->routeIs('owner.expenses.*') ? 'active' : '' }}">
                            <i class="bi bi-wallet2"></i> Pengeluaran
                        </a>
                    </div>

                    <div class="menu-item">
                        <a href="#reportsMenu" class="menu-link d-flex justify-content-between align-items-center {{ request()->routeIs('owner.reports.*') ? 'active' : '' }}" data-bs-toggle="collapse" role="button" aria-expanded="{{ request()->routeIs('owner.reports.*') ? 'true' : 'false' }}">
                            <span class="d-flex align-items-center gap-2">
                                <i class="bi bi-graph-up-arrow"></i> Laporan
                            </span>
                            <i class="bi bi-chevron-down small"></i>
                        </a>
                        <div class="collapse {{ request()->routeIs('owner.reports.*') ? 'show' : '' }}" id="reportsMenu">
                            <ul class="menu-dropdown">
                                <li>
                                    <a href="{{ route('owner.reports.sales') }}" class="menu-dropdown-link {{ request()->routeIs('owner.reports.sales') ? 'active' : '' }}">
                                        Laporan Penjualan
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('owner.reports.expenses') }}" class="menu-dropdown-link {{ request()->routeIs('owner.reports.expenses') ? 'active' : '' }}">
                                        Laporan Pengeluaran
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('owner.reports.stocks') }}" class="menu-dropdown-link {{ request()->routeIs('owner.reports.stocks') ? 'active' : '' }}">
                                        Laporan Stok
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                @elseif (auth()->user()->isKasir())
                    <div class="menu-label">Navigasi Kasir</div>
                    <div class="menu-item">
                        <a href="{{ route('kasir.dashboard') }}" class="menu-link {{ request()->routeIs('kasir.dashboard') ? 'active' : '' }}">
                            <i class="bi bi-speedometer2"></i> Dashboard
                        </a>
                    </div>
                    <div class="menu-item">
                        <a href="{{ route('kasir.pos.index') }}" class="menu-link {{ request()->routeIs('kasir.pos.*') ? 'active' : '' }}">
                            <i class="bi bi-calculator"></i> POS
                        </a>
                    </div>
                    <div class="menu-item">
                        <a href="{{ route('kasir.transactions.index') }}" class="menu-link {{ request()->routeIs('kasir.transactions.*') ? 'active' : '' }}">
                            <i class="bi bi-receipt"></i> Riwayat Transaksi
                        </a>
                    </div>
                @endif
            </div>

            <div class="sidebar-profile">
                <div class="profile-card">
                    <div class="profile-avatar">
                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                    </div>
                    <div class="profile-info">
                        <div class="profile-name" title="{{ auth()->user()->name }}">{{ auth()->user()->name }}</div>
                        <div class="profile-role">{{ auth()->user()->role }}</div>
                    </div>
                </div>
            </div>
        </aside>

        <!-- Mobile Offcanvas Sidebar -->
        <div class="offcanvas offcanvas-start d-lg-none" tabindex="-1" id="mobileSidebar" aria-labelledby="mobileSidebarLabel">
            <div class="offcanvas-header border-bottom" style="background-color: #003135;">
                <a href="{{ route('home') }}" class="sidebar-brand text-decoration-none py-2 border-0">
                    <div style="width:32px;height:32px;border-radius:9px;background:linear-gradient(135deg,#003135 0%,#024950 50%,#0FA4AF 100%);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                        <svg width="18" height="18" viewBox="0 0 28 28" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M7 20 C7 20 8 13 14 13 C20 13 21 20 21 20" stroke="white" stroke-width="2.2" stroke-linecap="round"/>
                            <line x1="5" y1="20" x2="23" y2="20" stroke="white" stroke-width="2.2" stroke-linecap="round"/>
                            <circle cx="11" cy="10" r="1.2" fill="rgba(255,255,255,0.65)"/>
                            <circle cx="14" cy="8" r="1.3" fill="white"/>
                            <circle cx="17" cy="10" r="1.2" fill="rgba(255,255,255,0.65)"/>
                        </svg>
                    </div>
                    <span>Sikasir</span>
                </a>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas" aria-label="Close"></button>
            </div>
            <div class="offcanvas-body p-0 d-flex flex-column" style="background-color: #003135;">
                <div class="sidebar-menu">
                    @if (auth()->user()->isOwner())
                        <div class="menu-label">Navigasi Owner</div>
                        <div class="menu-item">
                            <a href="{{ route('owner.dashboard') }}" class="menu-link {{ request()->routeIs('owner.dashboard') ? 'active' : '' }}">
                                <i class="bi bi-speedometer2"></i> Dashboard
                            </a>
                        </div>
                        <div class="menu-item">
                            <a href="{{ route('owner.categories.index') }}" class="menu-link {{ request()->routeIs('owner.categories.*') ? 'active' : '' }}">
                                <i class="bi bi-tags"></i> Kategori
                            </a>
                        </div>
                        <div class="menu-item">
                            <a href="{{ route('owner.products.index') }}" class="menu-link {{ request()->routeIs('owner.products.*') ? 'active' : '' }}">
                                <i class="bi bi-bag"></i> Produk
                            </a>
                        </div>
                        
                        <div class="menu-item">
                            <a href="{{ route('owner.stock.index') }}" class="menu-link {{ request()->routeIs('owner.stock.*') ? 'active' : '' }}">
                                <i class="bi bi-box-seam"></i> Manajemen Stok
                            </a>
                        </div>

                        <div class="menu-item">
                            <a href="{{ route('owner.expenses.index') }}" class="menu-link {{ request()->routeIs('owner.expenses.*') ? 'active' : '' }}">
                                <i class="bi bi-wallet2"></i> Pengeluaran
                            </a>
                        </div>

                        <div class="menu-item">
                            <a href="#mobileReportsMenu" class="menu-link d-flex justify-content-between align-items-center {{ request()->routeIs('owner.reports.*') ? 'active' : '' }}" data-bs-toggle="collapse" role="button" aria-expanded="{{ request()->routeIs('owner.reports.*') ? 'true' : 'false' }}">
                                <span class="d-flex align-items-center gap-2">
                                    <i class="bi bi-graph-up-arrow"></i> Laporan
                                </span>
                                <i class="bi bi-chevron-down small"></i>
                            </a>
                            <div class="collapse {{ request()->routeIs('owner.reports.*') ? 'show' : '' }}" id="mobileReportsMenu">
                                <ul class="menu-dropdown">
                                    <li>
                                        <a href="{{ route('owner.reports.sales') }}" class="menu-dropdown-link {{ request()->routeIs('owner.reports.sales') ? 'active' : '' }}">
                                            Laporan Penjualan
                                        </a>
                                    </li>
                                    <li>
                                        <a href="{{ route('owner.reports.expenses') }}" class="menu-dropdown-link {{ request()->routeIs('owner.reports.expenses') ? 'active' : '' }}">
                                            Laporan Pengeluaran
                                        </a>
                                    </li>
                                    <li>
                                        <a href="{{ route('owner.reports.stocks') }}" class="menu-dropdown-link {{ request()->routeIs('owner.reports.stocks') ? 'active' : '' }}">
                                            Laporan Stok
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    @elseif (auth()->user()->isKasir())
                        <div class="menu-label">Navigasi Kasir</div>
                        <div class="menu-item">
                            <a href="{{ route('kasir.dashboard') }}" class="menu-link {{ request()->routeIs('kasir.dashboard') ? 'active' : '' }}">
                                <i class="bi bi-speedometer2"></i> Dashboard
                            </a>
                        </div>
                        <div class="menu-item">
                            <a href="{{ route('kasir.pos.index') }}" class="menu-link {{ request()->routeIs('kasir.pos.*') ? 'active' : '' }}">
                                <i class="bi bi-calculator"></i> POS
                            </a>
                        </div>
                        <div class="menu-item">
                            <a href="{{ route('kasir.transactions.index') }}" class="menu-link {{ request()->routeIs('kasir.transactions.*') ? 'active' : '' }}">
                                <i class="bi bi-receipt"></i> Riwayat Transaksi
                            </a>
                        </div>
                    @endif
                </div>

                <div class="sidebar-profile">
                    <div class="profile-card">
                        <div class="profile-avatar">
                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                        </div>
                        <div class="profile-info">
                            <div class="profile-name" title="{{ auth()->user()->name }}">{{ auth()->user()->name }}</div>
                            <div class="profile-role">{{ auth()->user()->role }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content Area -->
        <div class="main-content">
            <header class="top-navbar">
                <button class="btn btn-outline-secondary d-lg-none" type="button" data-bs-toggle="offcanvas" data-bs-target="#mobileSidebar" aria-controls="mobileSidebar">
                    <i class="bi bi-list fs-5"></i>
                </button>
                
                <div class="d-none d-sm-block">
                    <span class="text-muted small fw-medium">
                        @if(auth()->user()->isOwner())
                            @if(request()->routeIs('owner.dashboard')) Ringkasan Operasional
                            @elseif(request()->routeIs('owner.categories.*')) Manajemen Kategori
                            @elseif(request()->routeIs('owner.products.*')) Manajemen Produk
                            @elseif(request()->routeIs('owner.stock.*')) Manajemen Stok
                            @elseif(request()->routeIs('owner.expenses.*')) Manajemen Pengeluaran
                            @elseif(request()->routeIs('owner.reports.*')) Laporan Bisnis
                            @else Sikasir Angkringan
                            @endif
                        @elseif(auth()->user()->isKasir())
                            @if(request()->routeIs('kasir.dashboard')) Pusat Aktivitas Kasir
                            @elseif(request()->routeIs('kasir.pos.*')) Point of Sale
                            @elseif(request()->routeIs('kasir.transactions.*')) Riwayat Transaksi
                            @else Sikasir Angkringan
                            @endif
                        @endif
                    </span>
                </div>

                <div class="dropdown">
                    <button class="d-flex align-items-center gap-2 p-0 border-0 bg-transparent" type="button" data-bs-toggle="dropdown" aria-expanded="false" style="cursor:pointer;">
                        <div class="profile-avatar" style="width:32px;height:32px;font-size:0.82rem;border-radius:8px;">
                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                        </div>
                        <span class="d-none d-md-inline small fw-semibold" style="color:rgba(255,255,255,0.9);">{{ auth()->user()->name }}</span>
                        <i class="bi bi-chevron-down d-none d-md-inline" style="font-size:0.6rem;color:rgba(255,255,255,0.5);"></i>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end mt-2" style="border:1px solid rgba(0,0,0,0.08);border-radius:0.75rem;box-shadow:0 8px 24px rgba(0,0,0,0.14);min-width:180px;padding:0.375rem;overflow:hidden;">
                        <li style="padding:0.625rem 0.75rem;border-bottom:1px solid #F1F5F9;margin-bottom:0.25rem;">
                            <div class="fw-bold small" style="color:#0F172A;">{{ auth()->user()->name }}</div>
                            <div class="small" style="font-size:0.72rem;color:#64748B;text-transform:capitalize;">{{ auth()->user()->role }}</div>
                        </li>
                        <li>
                            <form method="POST" action="{{ route('logout') }}" class="m-0">
                                @csrf
                                <button type="submit" class="dropdown-item d-flex align-items-center gap-2" style="border-radius:0.5rem;font-size:0.85rem;color:#964734;font-weight:600;">
                                    <i class="bi bi-box-arrow-right"></i> Keluar
                                </button>
                            </form>
                        </li>
                    </ul>
                </div>
            </header>

            <main class="content-body">
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                        <div class="d-flex align-items-center gap-2">
                            <div>{{ session('success') }}</div>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if (session('error'))
                    <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
                        <div class="d-flex align-items-center gap-2">
                            <div>{{ session('error') }}</div>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @yield('content')
            </main>
        </div>
    @else
        {{-- Guest Layout — no wrapper, pages manage their own layout --}}
        @yield('content')
    @endauth

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>
