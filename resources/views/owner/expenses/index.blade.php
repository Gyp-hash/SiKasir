@extends('layouts.app', ['title' => 'Daftar Pengeluaran - SiKasir Angkringan'])

@section('content')
<div class="card shadow-sm">
    <div class="card-header bg-dark text-white d-flex align-items-center justify-content-between">
        <h1 class="h5 mb-0">Daftar Pengeluaran</h1>
        <a href="{{ route('owner.expenses.create') }}" class="btn btn-sm btn-warning text-dark fw-semibold">+ Tambah Pengeluaran</a>
    </div>

    {{-- Filter --}}
    <div class="card-body border-bottom">
        <form method="GET" action="{{ route('owner.expenses.index') }}" class="row g-2 align-items-end">

            <div class="col-md-3">
                <label for="filter-category" class="form-label small fw-semibold">Kategori</label>
                <select id="filter-category" name="category" class="form-select form-select-sm">
                    <option value="">— Semua Kategori —</option>
                    @foreach ($categories as $cat)
                        <option value="{{ $cat }}" {{ $category === $cat ? 'selected' : '' }}>{{ $cat }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-2">
                <label for="filter-date-from" class="form-label small fw-semibold">Dari Tanggal</label>
                <input id="filter-date-from" type="date" name="date_from" class="form-control form-control-sm" value="{{ $dateFrom }}">
            </div>

            <div class="col-md-2">
                <label for="filter-date-to" class="form-label small fw-semibold">Sampai Tanggal</label>
                <input id="filter-date-to" type="date" name="date_to" class="form-control form-control-sm" value="{{ $dateTo }}">
            </div>

            <div class="col-md-3 d-flex gap-2">
                <button type="submit" class="btn btn-dark btn-sm">Filter</button>
                <a href="{{ route('owner.expenses.index') }}" class="btn btn-outline-secondary btn-sm">Reset</a>
            </div>

        </form>
    </div>

    {{-- Ringkasan Total --}}
    @if ($category || $dateFrom || $dateTo)
        <div class="px-3 py-2 bg-light border-bottom d-flex align-items-center justify-content-between">
            <small class="text-muted">Total hasil filter:</small>
            <span class="fw-bold text-danger">Rp {{ number_format($totalFiltered, 0, ',', '.') }}</span>
        </div>
    @endif

    <div class="card-body p-0">
        @if ($expenses->isEmpty())
            <div class="text-center text-muted py-5">
                <p class="mt-2">Belum ada data pengeluaran.</p>
                <a href="{{ route('owner.expenses.create') }}" class="btn btn-dark mt-2">+ Tambah Pengeluaran</a>
            </div>
        @else
            <div class="table-responsive">
                <table class="table table-hover table-bordered align-middle mb-0">
                    <thead class="table-dark">
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Tanggal</th>
                            <th scope="col">Kategori</th>
                            <th scope="col">Keterangan</th>
                            <th scope="col" class="text-end">Nominal</th>
                            <th scope="col">Dicatat Oleh</th>
                            <th scope="col" class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($expenses as $index => $expense)
                            <tr>
                                <td class="text-muted small">{{ $expenses->firstItem() + $index }}</td>
                                <td class="text-nowrap small">
                                    {{ $expense->expense_date->format('d M Y') }}
                                </td>
                                <td>
                                    <span class="badge bg-secondary">{{ $expense->category }}</span>
                                </td>
                                <td class="small">{{ Str::limit($expense->description, 60) }}</td>
                                <td class="text-end fw-bold text-danger">
                                    Rp {{ number_format($expense->amount, 0, ',', '.') }}
                                </td>
                                <td class="small text-muted">{{ $expense->creator->name }}</td>
                                <td class="text-center">
                                    <a
                                        href="{{ route('owner.expenses.show', $expense) }}"
                                        class="btn btn-sm btn-outline-dark"
                                    >Detail</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="table-light">
                        <tr>
                            <td colspan="4" class="text-end fw-semibold text-muted small">Total halaman ini:</td>
                            <td class="text-end fw-bold text-danger">
                                Rp {{ number_format($expenses->sum('amount'), 0, ',', '.') }}
                            </td>
                            <td colspan="2"></td>
                        </tr>
                    </tfoot>
                </table>
            </div>

            {{-- Pagination --}}
            <div class="px-3 py-2 d-flex justify-content-between align-items-center border-top">
                <small class="text-muted">
                    Menampilkan {{ $expenses->firstItem() }}–{{ $expenses->lastItem() }} dari {{ $expenses->total() }} pengeluaran
                </small>
                {{ $expenses->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
