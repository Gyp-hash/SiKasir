@extends('layouts.app', ['title' => 'Riwayat Stok - SiKasir Angkringan'])

@section('content')
<div class="card shadow-sm">
    <div class="card-header bg-dark text-white d-flex align-items-center justify-content-between">
        <h1 class="h5 mb-0">Riwayat Pergerakan Stok</h1>
        <a href="{{ route('owner.stock.restock') }}" class="btn btn-sm btn-warning text-dark fw-semibold">+ Restok Produk</a>
    </div>

    <div class="card-body border-bottom">
        {{-- Filter --}}
        <form method="GET" action="{{ route('owner.stock.index') }}" class="row g-2 align-items-end">
            <div class="col-md-3">
                <label for="filter-product" class="form-label small fw-semibold">Produk</label>
                <select id="filter-product" name="product_id" class="form-select form-select-sm">
                    <option value="">— Semua Produk —</option>
                    @foreach ($products as $product)
                        <option value="{{ $product->id }}" {{ $productId == $product->id ? 'selected' : '' }}>
                            {{ $product->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-2">
                <label for="filter-type" class="form-label small fw-semibold">Jenis</label>
                <select id="filter-type" name="type" class="form-select form-select-sm">
                    <option value="">— Semua Jenis —</option>
                    <option value="IN"         {{ $type === 'IN'         ? 'selected' : '' }}>Masuk (IN)</option>
                    <option value="OUT"        {{ $type === 'OUT'        ? 'selected' : '' }}>Keluar (OUT)</option>
                    <option value="ADJUSTMENT" {{ $type === 'ADJUSTMENT' ? 'selected' : '' }}>Penyesuaian</option>
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
                <a href="{{ route('owner.stock.index') }}" class="btn btn-outline-secondary btn-sm">Reset</a>
            </div>
        </form>
    </div>

    <div class="card-body p-0">
        @if ($movements->isEmpty())
            <div class="text-center text-muted py-5">
                <p class="mt-2">Belum ada pergerakan stok ditemukan.</p>
            </div>
        @else
            <div class="table-responsive">
                <table class="table table-hover table-bordered align-middle mb-0">
                    <thead class="table-dark">
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Tanggal</th>
                            <th scope="col">Produk</th>
                            <th scope="col" class="text-center">Jenis</th>
                            <th scope="col" class="text-center">Jumlah</th>
                            <th scope="col" class="text-center">Stok Sebelum</th>
                            <th scope="col" class="text-center">Stok Sesudah</th>
                            <th scope="col">User</th>
                            <th scope="col">Catatan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($movements as $index => $movement)
                            <tr>
                                <td class="text-muted small">{{ $movements->firstItem() + $index }}</td>
                                <td class="small text-muted text-nowrap">
                                    {{ $movement->created_at->format('d M Y, H:i') }}
                                </td>
                                <td class="fw-semibold">{{ $movement->product->name }}</td>
                                <td class="text-center">
                                    @if ($movement->type === 'IN')
                                        <span class="badge bg-success">Masuk</span>
                                    @elseif ($movement->type === 'OUT')
                                        <span class="badge bg-danger">Keluar</span>
                                    @else
                                        <span class="badge bg-warning text-dark">Penyesuaian</span>
                                    @endif
                                </td>
                                <td class="text-center fw-bold">{{ $movement->quantity }}</td>
                                <td class="text-center text-muted">{{ $movement->stock_before }}</td>
                                <td class="text-center fw-semibold
                                    @if ($movement->type === 'IN') text-success
                                    @elseif ($movement->type === 'OUT') text-danger
                                    @else text-warning
                                    @endif
                                ">{{ $movement->stock_after }}</td>
                                <td class="small">{{ $movement->creator->name }}</td>
                                <td class="small text-muted">{{ $movement->notes ?? '—' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            <div class="px-3 py-2 d-flex justify-content-between align-items-center border-top">
                <small class="text-muted">
                    Menampilkan {{ $movements->firstItem() }}–{{ $movements->lastItem() }} dari {{ $movements->total() }} pergerakan
                </small>
                {{ $movements->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
