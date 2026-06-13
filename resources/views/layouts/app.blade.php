<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'SiKasir Angkringan' }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    @stack('styles')
</head>
<body class="bg-light">
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand fw-bold" href="{{ route('home') }}">SiKasir Angkringan</a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavbar" aria-controls="mainNavbar" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="mainNavbar">
                @auth
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                        @if (auth()->user()->isOwner())
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('owner.dashboard') }}">Dashboard</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('owner.categories.index') }}">Kategori</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('owner.products.index') }}">Produk</a>
                            </li>
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    Stok
                                </a>
                                <ul class="dropdown-menu">
                                    <li>
                                        <a class="dropdown-item" href="{{ route('owner.stock.index') }}">Riwayat Stok</a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="{{ route('owner.stock.restock') }}">Restok Produk</a>
                                    </li>
                                </ul>
                            </li>
                        @elseif (auth()->user()->isKasir())
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('kasir.dashboard') }}">Dashboard</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('kasir.pos.index') }}">POS</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('kasir.transactions.index') }}">Riwayat Transaksi</a>
                            </li>
                        @endif
                    </ul>

                    <div class="d-flex align-items-center gap-3">
                        <span class="text-white-50 small">{{ auth()->user()->name }} ({{ auth()->user()->role }})</span>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button class="btn btn-outline-light btn-sm" type="submit">Logout</button>
                        </form>
                    </div>
                @endauth
            </div>
        </div>
    </nav>

    <main class="container py-4">
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @yield('content')
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>
