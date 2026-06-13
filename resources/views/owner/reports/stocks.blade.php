@extends('layouts.app', ['title' => 'Laporan Stok - Sikasir Angkringan'])

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h4 mb-1 fw-bold">Laporan Stok</h1>
        <p class="text-muted small mb-0">Pergerakan mutasi stok produk berdasarkan rentang waktu.</p>
    </div>
</div>

{{-- Filter --}}
<div class="card mb-4">
    <div class="card-header">
        <span class="fw-semibold text-dark" style="font-size: 0.875rem;">Filter Laporan</span>
    </div>
    <div class="card-body">
        <form method="GET" action="{{ route('owner.reports.stocks') }}" class="row g-3 align-items-end">
            <div class="col-md-3">
                <label for="date_from" class="form-label">Dari Tanggal</label>
                <input id="date_from" type="date" name="date_from" class="form-control" value="{{ $dateFrom }}" required>
            </div>
            <div class="col-md-3">
                <label for="date_to" class="form-label">Sampai Tanggal</label>
                <input id="date_to" type="date" name="date_to" class="form-control" value="{{ $dateTo }}" required>
            </div>
            <div class="col-md-3">
                <label for="product_id" class="form-label">Produk</label>
                <select id="product_id" name="product_id" class="form-select">
                    <option value="">Semua Produk</option>
                    @foreach ($products as $prod)
                        <option value="{{ $prod->id }}" {{ $productId == $prod->id ? 'selected' : '' }}>{{ $prod->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3 d-flex flex-column gap-2">
                <button type="submit" class="btn btn-primary px-4 fw-semibold w-100">Tampilkan</button>
                <div class="d-flex gap-2">
                    <a href="{{ route('owner.reports.stocks.pdf', request()->query()) }}" class="btn btn-outline-danger fw-semibold flex-grow-1" target="_blank">PDF</a>
                    <a href="{{ route('owner.reports.stocks.excel', request()->query()) }}" class="btn btn-outline-success fw-semibold flex-grow-1">Excel</a>
                </div>
            </div>
        </form>
    </div>
</div>

{{-- Summary Cards --}}
<div class="row g-3 mb-4">
    <div class="col-sm-4">
        <div class="card card-hover">
            <div class="card-body">
                <div class="text-muted small fw-medium text-uppercase mb-1" style="font-size: 0.7rem; letter-spacing: 0.05em;">Total Masuk</div>
                <div class="fs-2 fw-extrabold text-success">{{ $summary['total_in'] }}</div>
            </div>
        </div>
    </div>
    <div class="col-sm-4">
        <div class="card card-hover">
            <div class="card-body">
                <div class="text-muted small fw-medium text-uppercase mb-1" style="font-size: 0.7rem; letter-spacing: 0.05em;">Total Keluar</div>
                <div class="fs-2 fw-extrabold text-danger">{{ $summary['total_out'] }}</div>
            </div>
        </div>
    </div>
    <div class="col-sm-4">
        <div class="card card-hover">
            <div class="card-body">
                <div class="text-muted small fw-medium text-uppercase mb-1" style="font-size: 0.7rem; letter-spacing: 0.05em;">Total Penyesuaian</div>
                <div class="fs-2 fw-extrabold text-warning">{{ $summary['total_adjustment'] }}</div>
            </div>
        </div>
    </div>
</div>

{{-- Data Table --}}
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span class="fw-semibold text-dark" style="font-size: 0.875rem;">Detail Mutasi Stok</span>
        @if (!$movements->isEmpty())
            <span class="badge bg-secondary-subtle">{{ count($movements) }} pergerakan</span>
        @endif
    </div>
    @if ($movements->isEmpty())
        <div class="card-body text-center text-muted py-5">
            <p class="small mb-0">Tidak ada mutasi stok pada rentang tanggal ini.</p>
        </div>
    @else
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th style="width: 4%">#</th>
                        <th>Tanggal</th>
                        <th>Produk</th>
                        <th class="text-center">Jenis</th>
                        <th class="text-center">Jumlah</th>
                        <th class="text-center">Stok Sebelum</th>
                        <th class="text-center">Stok Sesudah</th>
                        <th>Catatan</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($movements as $i => $mv)
                        <tr>
                            <td class="text-muted small">{{ $i + 1 }}</td>
                            <td class="small text-nowrap text-muted">{{ $mv->created_at->format('d M Y, H:i') }}</td>
                            <td class="fw-semibold text-dark small">{{ $mv->product->name }}</td>
                            <td class="text-center">
                                @if ($mv->type === 'IN')
                                    <span class="badge bg-success-subtle">Masuk</span>
                                @elseif ($mv->type === 'OUT')
                                    <span class="badge bg-danger-subtle">Keluar</span>
                                @else
                                    <span class="badge bg-warning-subtle">Penyesuaian</span>
                                @endif
                            </td>
                            <td class="text-center fw-bold text-dark">{{ $mv->quantity }}</td>
                            <td class="text-center text-muted small">{{ $mv->stock_before }}</td>
                            <td class="text-center fw-bold small {{ $mv->type === 'IN' ? 'text-success' : ($mv->type === 'OUT' ? 'text-danger' : 'text-warning') }}">
                                {{ $mv->stock_after }}
                            </td>
                            <td class="small text-muted">{{ $mv->notes ?? '—' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>
@endsection
