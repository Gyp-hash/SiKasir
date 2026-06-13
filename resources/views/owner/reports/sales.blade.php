@extends('layouts.app', ['title' => 'Laporan Penjualan - Sikasir Angkringan'])

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h4 mb-1 fw-bold">Laporan Penjualan</h1>
        <p class="text-muted small mb-0">Data penjualan transaksi yang berhasil diselesaikan.</p>
    </div>
</div>

{{-- Filter --}}
<div class="card mb-4">
    <div class="card-header">
        <span class="fw-semibold text-dark" style="font-size: 0.875rem;">Filter Laporan</span>
    </div>
    <div class="card-body">
        <form method="GET" action="{{ route('owner.reports.sales') }}" class="row g-3 align-items-end" id="filter-form">
            <div class="col-md-3">
                <label for="date_from" class="form-label">Dari Tanggal</label>
                <input id="date_from" type="date" name="date_from" class="form-control" value="{{ $dateFrom }}" required>
            </div>
            <div class="col-md-3">
                <label for="date_to" class="form-label">Sampai Tanggal</label>
                <input id="date_to" type="date" name="date_to" class="form-control" value="{{ $dateTo }}" required>
            </div>
            <div class="col-md-6 d-flex gap-2">
                <button type="submit" class="btn btn-primary px-4 fw-semibold">Tampilkan</button>
                <a href="{{ route('owner.reports.sales.pdf', request()->query()) }}" class="btn btn-outline-danger fw-semibold px-3" target="_blank">PDF</a>
                <a href="{{ route('owner.reports.sales.excel', request()->query()) }}" class="btn btn-outline-success fw-semibold px-3">Excel</a>
            </div>
        </form>
    </div>
</div>

{{-- Summary Cards --}}
<div class="row g-3 mb-4">
    <div class="col-md-6 col-lg-4">
        <div class="card card-hover">
            <div class="card-body">
                <div class="text-muted small fw-medium text-uppercase mb-1" style="font-size: 0.7rem; letter-spacing: 0.05em;">Jumlah Transaksi</div>
                <div class="fs-2 fw-extrabold text-dark">{{ $summary['count'] }}</div>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-lg-4">
        <div class="card card-hover">
            <div class="card-body">
                <div class="text-muted small fw-medium text-uppercase mb-1" style="font-size: 0.7rem; letter-spacing: 0.05em;">Total Penjualan</div>
                <div class="fs-2 fw-extrabold text-success">Rp {{ number_format($summary['total'], 0, ',', '.') }}</div>
            </div>
        </div>
    </div>
</div>

{{-- Data Table --}}
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span class="fw-semibold text-dark" style="font-size: 0.875rem;">Detail Transaksi</span>
        @if (!$transactions->isEmpty())
            <span class="badge bg-secondary-subtle">{{ count($transactions) }} transaksi</span>
        @endif
    </div>
    @if ($transactions->isEmpty())
        <div class="card-body text-center text-muted py-5">
            <p class="small mb-0">Tidak ada transaksi pada rentang tanggal ini.</p>
        </div>
    @else
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th style="width: 4%">#</th>
                        <th>Kode Transaksi</th>
                        <th>Tanggal</th>
                        <th>Kasir</th>
                        <th class="text-end">Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($transactions as $i => $trx)
                        <tr>
                            <td class="text-muted small">{{ $i + 1 }}</td>
                            <td class="font-monospace small fw-bold text-dark">{{ $trx->code }}</td>
                            <td class="small text-muted text-nowrap">{{ $trx->transaction_date->format('d M Y, H:i') }}</td>
                            <td class="small">{{ $trx->user->name ?? '-' }}</td>
                            <td class="text-end fw-bold text-success small">Rp {{ number_format($trx->total, 0, ',', '.') }}</td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="4" class="text-end fw-bold text-muted" style="font-size: 0.72rem; text-transform: uppercase; letter-spacing: 0.05em;">Total:</td>
                        <td class="text-end fw-bold text-success">Rp {{ number_format($summary['total'], 0, ',', '.') }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>
    @endif
</div>
@endsection
