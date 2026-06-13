@extends('layouts.app', ['title' => 'Daftar Pengeluaran - Sikasir Angkringan'])

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h4 mb-1 fw-bold">Daftar Pengeluaran</h1>
        <p class="text-muted small mb-0">Kelola transaksi pengeluaran operasional.</p>
    </div>
    <a href="{{ route('owner.expenses.create') }}" class="btn btn-primary fw-semibold">Tambah Pengeluaran</a>
</div>

{{-- Filter --}}
<div class="card mb-4">
    <div class="card-header">
        <span class="fw-semibold text-dark" style="font-size: 0.875rem;">Filter Pengeluaran</span>
    </div>
    <div class="card-body">
        <form method="GET" action="{{ route('owner.expenses.index') }}" class="row g-3 align-items-end">
            <div class="col-md-3">
                <label for="filter-category" class="form-label">Kategori</label>
                <select id="filter-category" name="category" class="form-select">
                    <option value="">Semua Kategori</option>
                    @foreach ($categories as $cat)
                        <option value="{{ $cat }}" {{ $category === $cat ? 'selected' : '' }}>{{ $cat }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label for="filter-date-from" class="form-label">Dari Tanggal</label>
                <input id="filter-date-from" type="date" name="date_from" class="form-control" value="{{ $dateFrom }}">
            </div>
            <div class="col-md-3">
                <label for="filter-date-to" class="form-label">Sampai Tanggal</label>
                <input id="filter-date-to" type="date" name="date_to" class="form-control" value="{{ $dateTo }}">
            </div>
            <div class="col-md-3 d-flex gap-2">
                <button type="submit" class="btn btn-primary fw-semibold w-100">Filter</button>
                <a href="{{ route('owner.expenses.index') }}" class="btn btn-outline-secondary fw-semibold w-100">Reset</a>
            </div>
        </form>
    </div>
</div>

{{-- Ringkasan Filter --}}
@if ($category || $dateFrom || $dateTo)
    <div class="alert alert-info d-flex align-items-center justify-content-between mb-4" role="alert">
        <span class="small fw-semibold">Total Pengeluaran Hasil Filter:</span>
        <span class="fw-bold fs-5 text-danger">Rp {{ number_format($totalFiltered, 0, ',', '.') }}</span>
    </div>
@endif

{{-- Data Table --}}
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span class="fw-semibold text-dark" style="font-size: 0.875rem;">Data Pengeluaran</span>
        @if (!$expenses->isEmpty())
            <span class="badge bg-secondary-subtle">{{ $expenses->total() }} pengeluaran</span>
        @endif
    </div>
    @if ($expenses->isEmpty())
        <div class="card-body text-center text-muted py-5">
            <p class="small mb-3">Belum ada data pengeluaran.</p>
            <a href="{{ route('owner.expenses.create') }}" class="btn btn-primary btn-sm fw-semibold">Tambah Pengeluaran</a>
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
                        <th>Dicatat Oleh</th>
                        <th class="text-center" style="width: 8%">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($expenses as $index => $expense)
                        <tr>
                            <td class="text-muted small">{{ $expenses->firstItem() + $index }}</td>
                            <td class="text-nowrap small fw-semibold text-dark">
                                {{ $expense->expense_date->format('d M Y') }}
                            </td>
                            <td>
                                <span class="badge bg-secondary-subtle">{{ $expense->category }}</span>
                            </td>
                            <td class="small text-muted">{{ Str::limit($expense->description, 60) }}</td>
                            <td class="text-end fw-bold text-danger small">
                                Rp {{ number_format($expense->amount, 0, ',', '.') }}
                            </td>
                            <td class="small text-dark">{{ $expense->creator->name }}</td>
                            <td class="text-center">
                                <a href="{{ route('owner.expenses.show', $expense) }}" class="btn btn-sm btn-action">
                                    Detail
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="4" class="text-end fw-bold text-muted" style="font-size: 0.72rem; text-transform: uppercase; letter-spacing: 0.05em;">Total Halaman Ini:</td>
                        <td class="text-end fw-bold text-danger">
                            Rp {{ number_format($expenses->sum('amount'), 0, ',', '.') }}
                        </td>
                        <td colspan="2"></td>
                    </tr>
                </tfoot>
            </table>
        </div>
        <div class="card-footer d-flex flex-column flex-sm-row justify-content-between align-items-center gap-2">
            <small class="text-muted">
                Menampilkan {{ $expenses->firstItem() }}–{{ $expenses->lastItem() }} dari {{ $expenses->total() }} pengeluaran
            </small>
            <div>{{ $expenses->links() }}</div>
        </div>
    @endif
</div>
@endsection
