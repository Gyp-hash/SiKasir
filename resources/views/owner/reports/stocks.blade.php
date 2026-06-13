@extends('layouts.app', ['title' => 'Laporan Stok - SiKasir Angkringan'])

@section('content')
<div class="card shadow-sm">
    <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
        <h1 class="h5 mb-0">Laporan Stok</h1>
    </div>

    {{-- Filter --}}
    <div class="card-body border-bottom">
        <form method="GET" action="{{ route('owner.reports.stocks') }}" class="row g-2 align-items-end">
            <div class="col-md-2">
                <label for="date_from" class="form-label small fw-semibold">Dari Tanggal</label>
                <input id="date_from" type="date" name="date_from" class="form-control form-control-sm" value="{{ $dateFrom }}" required>
            </div>
            <div class="col-md-2">
                <label for="date_to" class="form-label small fw-semibold">Sampai Tanggal</label>
                <input id="date_to" type="date" name="date_to" class="form-control form-control-sm" value="{{ $dateTo }}" required>
            </div>
            <div class="col-md-3">
                <label for="product_id" class="form-label small fw-semibold">Produk</label>
                <select id="product_id" name="product_id" class="form-select form-select-sm">
                    <option value="">Semua Produk</option>
                    @foreach ($products as $product)
                        <option value="{{ $product->id }}" {{ $productId == $product->id ? 'selected' : '' }}>
                            {{ $product->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <label for="type" class="form-label small fw-semibold">Jenis</label>
                <select id="type" name="type" class="form-select form-select-sm">
                    <option value="">Semua Jenis</option>
                    <option value="IN"         {{ $type === 'IN'         ? 'selected' : '' }}>Masuk (IN)</option>
                    <option value="OUT"        {{ $type === 'OUT'        ? 'selected' : '' }}>Keluar (OUT)</option>
                    <option value="ADJUSTMENT" {{ $type === 'ADJUSTMENT' ? 'selected' : '' }}>Penyesuaian</option>
                </select>
            </div>
            <div class="col-md-3 d-flex gap-2">
                <button type="submit" class="btn btn-dark btn-sm">Tampilkan</button>
                <a href="{{ route('owner.reports.stocks.pdf', request()->query()) }}" class="btn btn-danger btn-sm" target="_blank">
                    Export PDF
                </a>
                <a href="{{ route('owner.reports.stocks.excel', request()->query()) }}" class="btn btn-success btn-sm">
                    Export Excel
                </a>
            </div>
        </form>
    </div>

    {{-- Ringkasan --}}
    <div class="px-3 py-2 bg-light border-bottom">
        <span class="text-muted small">Jumlah Pergerakan:</span>
        <span class="fw-bold ms-1">{{ $movements->count() }}</span>
    </div>

    <div class="card-body p-0">
        @if ($movements->isEmpty())
            <div class="text-center text-muted py-5">
                <p class="mb-0">Tidak ada pergerakan stok pada rentang tanggal ini.</p>
            </div>
        @else
            <div class="table-responsive">
                <table class="table table-hover table-bordered align-middle mb-0">
                    <thead class="table-dark">
                        <tr>
                            <th>#</th>
                            <th>Tanggal</th>
                            <th>Produk</th>
                            <th class="text-center">Jenis</th>
                            <th class="text-center">Qty</th>
                            <th class="text-center">Stok Sblm</th>
                            <th class="text-center">Stok Ssdh</th>
                            <th>User</th>
                            <th>Catatan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($movements as $i => $mv)
                            <tr>
                                <td class="text-muted small">{{ $i + 1 }}</td>
                                <td class="small text-muted text-nowrap">{{ $mv->created_at->format('d M Y, H:i') }}</td>
                                <td class="fw-semibold small">{{ $mv->product->name ?? '-' }}</td>
                                <td class="text-center">
                                    @if ($mv->type === 'IN')
                                        <span class="badge bg-success">Masuk</span>
                                    @elseif ($mv->type === 'OUT')
                                        <span class="badge bg-danger">Keluar</span>
                                    @else
                                        <span class="badge bg-warning text-dark">Penyesuaian</span>
                                    @endif
                                </td>
                                <td class="text-center fw-bold">{{ $mv->quantity }}</td>
                                <td class="text-center text-muted">{{ $mv->stock_before }}</td>
                                <td class="text-center fw-semibold {{ $mv->type === 'IN' ? 'text-success' : ($mv->type === 'OUT' ? 'text-danger' : 'text-warning') }}">
                                    {{ $mv->stock_after }}
                                </td>
                                <td class="small">{{ $mv->creator->name ?? '-' }}</td>
                                <td class="small text-muted">{{ $mv->notes ?? '-' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>
@endsection
