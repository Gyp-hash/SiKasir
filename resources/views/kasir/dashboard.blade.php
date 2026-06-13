@extends('layouts.app', ['title' => 'Dashboard Kasir - SiKasir Angkringan'])

@section('content')

{{-- Header --}}
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h4 mb-0 fw-bold">Dashboard Kasir</h1>
        <p class="text-muted small mb-0">Selamat datang, {{ auth()->user()->name }} &mdash; {{ now()->translatedFormat('l, d F Y') }}</p>
    </div>
    <a href="{{ route('kasir.pos.index') }}" class="btn btn-dark fw-semibold">
        Buka POS
    </a>
</div>

{{-- Statistik Hari Ini (personal kasir) --}}
<div class="row g-3 mb-4">

    <div class="col-sm-6 col-lg-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body text-center py-4">
                <div class="fs-1 fw-bold text-dark">{{ $todayCount }}</div>
                <div class="text-muted small mt-1">Transaksi Hari Ini</div>
            </div>
        </div>
    </div>

    <div class="col-sm-6 col-lg-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body text-center py-4">
                <div class="fs-4 fw-bold text-success">Rp {{ number_format($todaySales, 0, ',', '.') }}</div>
                <div class="text-muted small mt-1">Omzet Hari Ini</div>
            </div>
        </div>
    </div>

    <div class="col-sm-6 col-lg-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body text-center py-4">
                <div class="fs-1 fw-bold text-primary">{{ $todayItemsSold }}</div>
                <div class="text-muted small mt-1">Item Terjual Hari Ini</div>
            </div>
        </div>
    </div>

    <div class="col-sm-6 col-lg-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body text-center py-4">
                <div class="fs-1 fw-bold text-secondary">{{ $totalPersonal }}</div>
                <div class="text-muted small mt-1">Total Transaksi Saya</div>
            </div>
        </div>
    </div>

</div>

{{-- 5 Transaksi Terakhir (personal kasir) --}}
<div class="row g-3">
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-bottom-0 d-flex justify-content-between align-items-center">
                <h6 class="fw-bold mb-0">Transaksi Terakhir Saya</h6>
                <a href="{{ route('kasir.transactions.index') }}" class="btn btn-sm btn-outline-dark">Lihat Semua</a>
            </div>
            <div class="card-body p-0">
                @if ($recentTransactions->isEmpty())
                    <div class="text-center text-muted py-5">
                        <p class="mb-2 small">Belum ada transaksi.</p>
                        <a href="{{ route('kasir.pos.index') }}" class="btn btn-dark btn-sm">Mulai Transaksi</a>
                    </div>
                @else
                    <div class="table-responsive">
                        <table class="table table-hover table-borderless align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="ps-3">Kode</th>
                                    <th>Tanggal</th>
                                    <th class="text-end pe-3">Total</th>
                                    <th class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($recentTransactions as $trx)
                                    <tr>
                                        <td class="ps-3 fw-semibold font-monospace small">{{ $trx->code }}</td>
                                        <td class="text-muted small">{{ $trx->transaction_date->format('d M Y, H:i') }}</td>
                                        <td class="text-end pe-3 fw-bold text-success">
                                            Rp {{ number_format($trx->total, 0, ',', '.') }}
                                        </td>
                                        <td class="text-center">
                                            <a href="{{ route('kasir.transactions.show', $trx) }}" class="btn btn-sm btn-outline-dark">
                                                Detail
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Panel Aksi Cepat --}}
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white border-bottom-0">
                <h6 class="fw-bold mb-0">Aksi Cepat</h6>
            </div>
            <div class="card-body d-flex flex-column gap-2">
                <a href="{{ route('kasir.pos.index') }}" class="btn btn-dark w-100">
                    Point of Sale
                </a>
                <a href="{{ route('kasir.transactions.index') }}" class="btn btn-outline-secondary w-100">
                    Riwayat Transaksi
                </a>
            </div>
        </div>
    </div>
</div>

@endsection
