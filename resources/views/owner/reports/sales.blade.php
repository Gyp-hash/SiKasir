@extends('layouts.app', ['title' => 'Laporan Penjualan - SiKasir Angkringan'])

@section('content')
<div class="card shadow-sm">
    <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
        <h1 class="h5 mb-0">Laporan Penjualan</h1>
    </div>

    {{-- Filter --}}
    <div class="card-body border-bottom">
        <form method="GET" action="{{ route('owner.reports.sales') }}" class="row g-2 align-items-end" id="filter-form">
            <div class="col-md-3">
                <label for="date_from" class="form-label small fw-semibold">Dari Tanggal</label>
                <input id="date_from" type="date" name="date_from" class="form-control form-control-sm" value="{{ $dateFrom }}" required>
            </div>
            <div class="col-md-3">
                <label for="date_to" class="form-label small fw-semibold">Sampai Tanggal</label>
                <input id="date_to" type="date" name="date_to" class="form-control form-control-sm" value="{{ $dateTo }}" required>
            </div>
            <div class="col-md-4 d-flex gap-2">
                <button type="submit" class="btn btn-dark btn-sm">Tampilkan</button>
                <a href="{{ route('owner.reports.sales.pdf', request()->query()) }}" class="btn btn-danger btn-sm" target="_blank">
                    Export PDF
                </a>
                <a href="{{ route('owner.reports.sales.excel', request()->query()) }}" class="btn btn-success btn-sm">
                    Export Excel
                </a>
            </div>
        </form>
    </div>

    {{-- Ringkasan --}}
    <div class="px-3 py-2 bg-light border-bottom d-flex gap-4">
        <div>
            <span class="text-muted small">Jumlah Transaksi:</span>
            <span class="fw-bold ms-1">{{ $summary['count'] }}</span>
        </div>
        <div>
            <span class="text-muted small">Total Penjualan:</span>
            <span class="fw-bold text-success ms-1">Rp {{ number_format($summary['total'], 0, ',', '.') }}</span>
        </div>
    </div>

    <div class="card-body p-0">
        @if ($transactions->isEmpty())
            <div class="text-center text-muted py-5">
                <p class="mb-0">Tidak ada transaksi pada rentang tanggal ini.</p>
            </div>
        @else
            <div class="table-responsive">
                <table class="table table-hover table-bordered align-middle mb-0">
                    <thead class="table-dark">
                        <tr>
                            <th>#</th>
                            <th>Kode</th>
                            <th>Tanggal</th>
                            <th>Kasir</th>
                            <th class="text-end">Total (Rp)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($transactions as $i => $trx)
                            <tr>
                                <td class="text-muted small">{{ $i + 1 }}</td>
                                <td class="font-monospace small fw-semibold">{{ $trx->code }}</td>
                                <td class="small text-muted text-nowrap">{{ $trx->transaction_date->format('d M Y, H:i') }}</td>
                                <td class="small">{{ $trx->user->name ?? '-' }}</td>
                                <td class="text-end fw-bold text-success">Rp {{ number_format($trx->total, 0, ',', '.') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="table-light">
                        <tr>
                            <td colspan="4" class="text-end fw-semibold small text-muted">Total:</td>
                            <td class="text-end fw-bold text-success">Rp {{ number_format($summary['total'], 0, ',', '.') }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        @endif
    </div>
</div>
@endsection
