@extends('layouts.app', ['title' => 'Dashboard Kasir - Sikasir Angkringan'])

@section('content')

{{-- Header --}}
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h4 mb-1 fw-bold" style="color:#0F172A;">Pusat Aktivitas Kasir</h1>
        <p class="text-muted small mb-0">Selamat datang, {{ auth()->user()->name }} &mdash; {{ now()->translatedFormat('l, d F Y') }}</p>
    </div>
    <a href="{{ route('kasir.pos.index') }}" class="btn btn-primary fw-semibold px-4">
        Buka POS
    </a>
</div>

{{-- Statistik Hari Ini (personal kasir) --}}
<div class="row g-3 mb-4">

    <div class="col-sm-6 col-lg-3">
        <div class="card card-stat h-100 card-hover" style="border-top: 3px solid #0FA4AF;">
            <div class="card-body" style="padding:1.125rem 1.25rem;">
                <div class="text-muted text-uppercase mb-2" style="font-size: 0.68rem; font-weight: 700; letter-spacing: 0.07em;">Transaksi Hari Ini</div>
                <div class="fw-extrabold text-dark mt-1" style="font-size: 1.75rem;">{{ $todayCount }}</div>
            </div>
        </div>
    </div>

    <div class="col-sm-6 col-lg-3">
        <div class="card card-stat h-100 card-hover" style="border-top: 3px solid #16A34A;">
            <div class="card-body" style="padding:1.125rem 1.25rem;">
                <div class="text-muted text-uppercase mb-2" style="font-size: 0.68rem; font-weight: 700; letter-spacing: 0.07em;">Omzet Hari Ini</div>
                <div class="fw-extrabold text-success mt-1" style="font-size: 1.5rem; letter-spacing: -0.02em;">Rp {{ number_format($todaySales, 0, ',', '.') }}</div>
            </div>
        </div>
    </div>

    <div class="col-sm-6 col-lg-3">
        <div class="card card-stat h-100 card-hover" style="border-top: 3px solid #64748B;">
            <div class="card-body" style="padding:1.125rem 1.25rem;">
                <div class="text-muted text-uppercase mb-2" style="font-size: 0.68rem; font-weight: 700; letter-spacing: 0.07em;">Item Terjual Hari Ini</div>
                <div class="fw-extrabold text-dark mt-1" style="font-size: 1.75rem;">{{ $todayItemsSold }}</div>
            </div>
        </div>
    </div>

    <div class="col-sm-6 col-lg-3">
        <div class="card card-stat h-100 card-hover" style="border-top: 3px solid #024950;">
            <div class="card-body" style="padding:1.125rem 1.25rem;">
                <div class="text-muted text-uppercase mb-2" style="font-size: 0.68rem; font-weight: 700; letter-spacing: 0.07em;">Total Transaksi Saya</div>
                <div class="fw-extrabold text-dark mt-1" style="font-size: 1.75rem;">{{ $totalPersonal }}</div>
            </div>
        </div>
    </div>

</div>

{{-- 5 Transaksi Terakhir + Aksi Cepat --}}
<div class="row g-3">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span class="fw-bold text-dark" style="font-size: 0.875rem;">Transaksi Terakhir Saya</span>
                <a href="{{ route('kasir.transactions.index') }}" class="btn btn-sm btn-action fw-semibold">Lihat Semua</a>
            </div>
            @if ($recentTransactions->isEmpty())
                <div class="card-body text-center text-muted py-5">
                    <p class="mb-3 small">Belum ada transaksi hari ini.</p>
                    <a href="{{ route('kasir.pos.index') }}" class="btn btn-primary btn-sm fw-medium px-4">Mulai Transaksi</a>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead>
                            <tr>
                                <th>Kode Transaksi</th>
                                <th>Tanggal</th>
                                <th class="text-end">Total</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($recentTransactions as $trx)
                                <tr>
                                    <td class="fw-bold text-dark font-monospace small">{{ $trx->code }}</td>
                                    <td class="text-muted small">{{ $trx->transaction_date->format('d M Y, H:i') }}</td>
                                    <td class="text-end fw-bold text-success small">
                                        Rp {{ number_format($trx->total, 0, ',', '.') }}
                                    </td>
                                    <td class="text-center">
                                        <a href="{{ route('kasir.transactions.show', $trx) }}" class="btn btn-sm btn-action fw-semibold">
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

    {{-- Panel Aksi Cepat --}}
    <div class="col-lg-4">
        <div class="card h-100">
            <div class="card-header">
                <span class="fw-bold text-dark" style="font-size: 0.875rem;">Aksi Cepat</span>
            </div>
            <div class="card-body d-flex flex-column gap-3">
                <a href="{{ route('kasir.pos.index') }}" class="btn btn-primary w-100 py-3 fw-bold">
                    Buka Point of Sale (POS)
                </a>
                <a href="{{ route('kasir.transactions.index') }}" class="btn btn-outline-secondary w-100 py-2 fw-semibold">
                    Lihat Riwayat Transaksi
                </a>
            </div>
        </div>
    </div>
</div>

@endsection
