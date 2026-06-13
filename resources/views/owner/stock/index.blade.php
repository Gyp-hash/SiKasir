@extends('layouts.app', ['title' => 'Riwayat Stok - Sikasir Angkringan'])

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h4 mb-1 fw-bold">Riwayat Stok</h1>
        <p class="text-muted small mb-0">Kelola dan pantau semua mutasi stok produk.</p>
    </div>
    <a href="{{ route('owner.stock.restock') }}" class="btn btn-primary fw-semibold">Restok Produk</a>
</div>

{{-- Filter --}}
<div class="card mb-4">
    <div class="card-header">
        <span class="fw-semibold text-dark" style="font-size: 0.875rem;">Filter Pergerakan Stok</span>
    </div>
    <div class="card-body">
        <form method="GET" action="{{ route('owner.stock.index') }}" class="row g-3 align-items-end">
            <div class="col-md-3">
                <label for="filter-product" class="form-label">Produk</label>
                <select id="filter-product" name="product_id" class="form-select">
                    <option value="">Semua Produk</option>
                    @foreach ($products as $product)
                        <option value="{{ $product->id }}" {{ $productId == $product->id ? 'selected' : '' }}>
                            {{ $product->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <label for="filter-type" class="form-label">Jenis</label>
                <select id="filter-type" name="type" class="form-select">
                    <option value="">Semua Jenis</option>
                    <option value="IN"         {{ $type === 'IN'         ? 'selected' : '' }}>Masuk (IN)</option>
                    <option value="OUT"        {{ $type === 'OUT'        ? 'selected' : '' }}>Keluar (OUT)</option>
                    <option value="ADJUSTMENT" {{ $type === 'ADJUSTMENT' ? 'selected' : '' }}>Penyesuaian</option>
                </select>
            </div>
            <div class="col-md-2">
                <label for="filter-date-from" class="form-label">Dari Tanggal</label>
                <input id="filter-date-from" type="date" name="date_from" class="form-control" value="{{ $dateFrom }}">
            </div>
            <div class="col-md-2">
                <label for="filter-date-to" class="form-label">Sampai Tanggal</label>
                <input id="filter-date-to" type="date" name="date_to" class="form-control" value="{{ $dateTo }}">
            </div>
            <div class="col-md-3 d-flex gap-2">
                <button type="submit" class="btn btn-primary fw-semibold w-100">Filter</button>
                <a href="{{ route('owner.stock.index') }}" class="btn btn-outline-secondary fw-semibold w-100">Reset</a>
            </div>
        </form>
    </div>
</div>

{{-- Data Table --}}
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span class="fw-semibold text-dark" style="font-size: 0.875rem;">Data Mutasi Stok</span>
        @if (!$movements->isEmpty())
            <span class="badge bg-secondary-subtle">{{ $movements->total() }} pergerakan</span>
        @endif
    </div>
    @if ($movements->isEmpty())
        <div class="card-body text-center text-muted py-5">
            <p class="small mb-0">Belum ada pergerakan stok ditemukan.</p>
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
                        <th>User</th>
                        <th>Catatan</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($movements as $index => $movement)
                        <tr>
                            <td class="text-muted small">{{ $movements->firstItem() + $index }}</td>
                            <td class="small text-muted text-nowrap">
                                {{ $movement->created_at->format('d M Y, H:i') }}
                            </td>
                            <td class="fw-semibold text-dark small">{{ $movement->product->name }}</td>
                            <td class="text-center">
                                @if ($movement->type === 'IN')
                                    <span class="badge bg-success-subtle">Masuk</span>
                                @elseif ($movement->type === 'OUT')
                                    <span class="badge bg-danger-subtle">Keluar</span>
                                @else
                                    <span class="badge bg-warning-subtle">Penyesuaian</span>
                                @endif
                            </td>
                            <td class="text-center fw-bold text-dark">{{ $movement->quantity }}</td>
                            <td class="text-center text-muted small">{{ $movement->stock_before }}</td>
                            <td class="text-center fw-bold
                                @if ($movement->type === 'IN') text-success
                                @elseif ($movement->type === 'OUT') text-danger
                                @else text-warning
                                @endif
                            ">{{ $movement->stock_after }}</td>
                            <td class="small text-dark">{{ $movement->creator->name }}</td>
                            <td class="small text-muted">{{ $movement->notes ?? '—' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="card-footer d-flex flex-column flex-sm-row justify-content-between align-items-center gap-2">
            <small class="text-muted">
                Menampilkan {{ $movements->firstItem() }}–{{ $movements->lastItem() }} dari {{ $movements->total() }} pergerakan
            </small>
            <div>{{ $movements->links() }}</div>
        </div>
    @endif
</div>
@endsection
