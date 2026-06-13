@extends('layouts.app', ['title' => 'Laporan Pengeluaran - Sikasir Angkringan'])

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h4 mb-1 fw-bold">Laporan Pengeluaran</h1>
        <p class="text-muted small mb-0">Data pengeluaran operasional usaha.</p>
    </div>
</div>

{{-- Filter --}}
<div class="card mb-4">
    <div class="card-header">
        <span class="fw-semibold text-dark" style="font-size: 0.875rem;">Filter Laporan</span>
    </div>
    <div class="card-body">
        <form method="GET" action="{{ route('owner.reports.expenses') }}" class="row g-3 align-items-end">
            <div class="col-md-3">
                <label for="date_from" class="form-label">Dari Tanggal</label>
                <input id="date_from" type="date" name="date_from" class="form-control" value="{{ $dateFrom }}" required>
            </div>
            <div class="col-md-3">
                <label for="date_to" class="form-label">Sampai Tanggal</label>
                <input id="date_to" type="date" name="date_to" class="form-control" value="{{ $dateTo }}" required>
            </div>
            <div class="col-md-3">
                <label for="category" class="form-label">Kategori</label>
                <select id="category" name="category" class="form-select">
                    <option value="">Semua Kategori</option>
                    @foreach ($categories as $cat)
                        <option value="{{ $cat }}" {{ $category === $cat ? 'selected' : '' }}>{{ $cat }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3 d-flex gap-2">
                <button type="submit" class="btn btn-primary px-4 fw-semibold w-100">Tampilkan</button>
            </div>
            <div class="col-12 d-flex gap-2">
                <a href="{{ route('owner.reports.expenses.pdf', request()->query()) }}" class="btn btn-outline-danger fw-semibold px-3" target="_blank">PDF</a>
                <a href="{{ route('owner.reports.expenses.excel', request()->query()) }}" class="btn btn-outline-success fw-semibold px-3">Excel</a>
            </div>
        </form>
    </div>
</div>

{{-- Summary Cards --}}
<div class="row g-3 mb-4">
    <div class="col-md-6 col-lg-4">
        <div class="card card-hover">
            <div class="card-body">
                <div class="text-muted small fw-medium text-uppercase mb-1" style="font-size: 0.7rem; letter-spacing: 0.05em;">Jumlah Pengeluaran</div>
                <div class="fs-2 fw-extrabold text-dark">{{ $expenses->count() }}</div>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-lg-4">
        <div class="card card-hover">
            <div class="card-body">
                <div class="text-muted small fw-medium text-uppercase mb-1" style="font-size: 0.7rem; letter-spacing: 0.05em;">Total Pengeluaran</div>
                <div class="fs-2 fw-extrabold text-danger">Rp {{ number_format($summary['total'], 0, ',', '.') }}</div>
            </div>
        </div>
    </div>
</div>

{{-- Data Table --}}
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span class="fw-semibold text-dark" style="font-size: 0.875rem;">Rincian Pengeluaran</span>
        @if (!$expenses->isEmpty())
            <span class="badge bg-secondary-subtle">{{ $expenses->count() }} entri</span>
        @endif
    </div>
    @if ($expenses->isEmpty())
        <div class="card-body text-center text-muted py-5">
            <p class="small mb-0">Tidak ada data pengeluaran pada rentang tanggal ini.</p>
        </div>
    @else
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th style="width: 4%">#</th>
                        <th>Tanggal</th>
                        <th>Kategori</th>
                        <th>Keterangan</th>
                        <th class="text-end">Nominal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($expenses as $i => $expense)
                        <tr>
                            <td class="text-muted small">{{ $i + 1 }}</td>
                            <td class="small text-nowrap fw-semibold text-dark">{{ $expense->expense_date->format('d M Y') }}</td>
                            <td><span class="badge bg-secondary-subtle">{{ $expense->category }}</span></td>
                            <td class="small text-muted">{{ $expense->description }}</td>
                            <td class="text-end fw-bold text-danger small">Rp {{ number_format($expense->amount, 0, ',', '.') }}</td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="4" class="text-end fw-bold text-muted" style="font-size: 0.72rem; text-transform: uppercase; letter-spacing: 0.05em;">Total:</td>
                        <td class="text-end fw-bold text-danger">Rp {{ number_format($summary['total'], 0, ',', '.') }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>
    @endif
</div>
@endsection
