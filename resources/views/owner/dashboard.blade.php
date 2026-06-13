@extends('layouts.app', ['title' => 'Dashboard Owner - SiKasir Angkringan'])

@section('content')

{{-- Header --}}
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h4 mb-0 fw-bold">Dashboard Owner</h1>
        <p class="text-muted small mb-0">Selamat datang, {{ auth()->user()->name }} &mdash; {{ now()->translatedFormat('l, d F Y') }}</p>
    </div>
</div>

{{-- Baris 1: Statistik Utama (4 kartu) --}}
<div class="row g-3 mb-4">

    <div class="col-sm-6 col-xl-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <div class="text-muted small">Penjualan Hari Ini</div>
                <div class="fs-4 fw-bold text-success mt-1">
                    Rp {{ number_format($salesToday, 0, ',', '.') }}
                </div>
            </div>
        </div>
    </div>

    <div class="col-sm-6 col-xl-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <div class="text-muted small">Penjualan Bulan Ini</div>
                <div class="fs-4 fw-bold text-primary mt-1">
                    Rp {{ number_format($salesThisMonth, 0, ',', '.') }}
                </div>
            </div>
        </div>
    </div>

    <div class="col-sm-6 col-xl-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <div class="text-muted small">Pengeluaran Bulan Ini</div>
                <div class="fs-4 fw-bold text-danger mt-1">
                    Rp {{ number_format($expensesThisMonth, 0, ',', '.') }}
                </div>
            </div>
        </div>
    </div>

    <div class="col-sm-6 col-xl-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <div class="text-muted small">Est. Laba Bersih Bulan Ini</div>
                <div class="fs-4 fw-bold mt-1 {{ $netProfitThisMonth >= 0 ? 'text-success' : 'text-danger' }}">
                    Rp {{ number_format($netProfitThisMonth, 0, ',', '.') }}
                </div>
            </div>
        </div>
    </div>

</div>

{{-- Baris 2: Statistik Sekunder (3 kartu angka) --}}
<div class="row g-3 mb-4">

    <div class="col-sm-4">
        <div class="card border-0 shadow-sm text-center h-100">
            <div class="card-body py-3">
                <div class="text-muted small">Total Produk</div>
                <div class="display-6 fw-bold text-dark">{{ $totalProducts }}</div>
            </div>
        </div>
    </div>

    <div class="col-sm-4">
        <div class="card border-0 shadow-sm text-center h-100">
            <div class="card-body py-3">
                <div class="text-muted small">Total Transaksi</div>
                <div class="display-6 fw-bold text-dark">{{ $totalTransactions }}</div>
            </div>
        </div>
    </div>

    <div class="col-sm-4">
        <div class="card border-0 shadow-sm text-center h-100">
            <div class="card-body py-3">
                <div class="text-muted small">Total Pengeluaran</div>
                <div class="fs-4 fw-bold text-danger">Rp {{ number_format($totalExpenses, 0, ',', '.') }}</div>
            </div>
        </div>
    </div>

</div>

{{-- Baris 3: Grafik 7 Hari (kiri) + Stok Menipis (kanan) --}}
<div class="row g-3 mb-4">

    <div class="col-lg-8">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white border-bottom-0 pb-0">
                <h6 class="fw-bold mb-0">Penjualan vs Pengeluaran (7 Hari Terakhir)</h6>
            </div>
            <div class="card-body">
                <canvas id="salesExpensesChart" height="100"></canvas>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white border-bottom-0 pb-0">
                <h6 class="fw-bold mb-0">Produk Stok Menipis
                    @if ($lowStockProducts->isNotEmpty())
                        <span class="badge bg-danger ms-1">{{ $lowStockProducts->count() }}</span>
                    @endif
                </h6>
            </div>
            <div class="card-body p-0">
                @if ($lowStockProducts->isEmpty())
                    <div class="text-center text-muted py-4 small">
                        Semua stok aman
                    </div>
                @else
                    <ul class="list-group list-group-flush">
                        @foreach ($lowStockProducts as $product)
                            <li class="list-group-item d-flex justify-content-between align-items-center px-3 py-2">
                                <span class="small fw-semibold">{{ $product->name }}</span>
                                <span class="badge {{ $product->stock <= 0 ? 'bg-danger' : 'bg-warning text-dark' }}">
                                    Stok: {{ $product->stock }}
                                </span>
                            </li>
                        @endforeach
                    </ul>
                    <div class="px-3 py-2">
                        <a href="{{ route('owner.stock.restock') }}" class="btn btn-sm btn-outline-dark w-100">
                            Restok Sekarang
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>

</div>

{{-- Baris 4: Top 5 Produk Terlaris + Transaksi Terbaru --}}
<div class="row g-3">

    <div class="col-lg-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white border-bottom-0 pb-0">
                <h6 class="fw-bold mb-0">Top 5 Produk Terlaris</h6>
            </div>
            <div class="card-body p-0">
                @if ($topProducts->isEmpty())
                    <div class="text-center text-muted py-4 small">Belum ada data penjualan.</div>
                @else
                    <ul class="list-group list-group-flush">
                        @foreach ($topProducts as $i => $item)
                            <li class="list-group-item d-flex justify-content-between align-items-center px-3 py-2">
                                <div class="d-flex align-items-center gap-2">
                                    <span class="badge bg-dark rounded-circle" style="width:24px;height:24px;line-height:16px;font-size:0.7rem;">
                                        {{ $i + 1 }}
                                    </span>
                                    <span class="small fw-semibold">{{ $item->product->name ?? '—' }}</span>
                                </div>
                                <span class="badge bg-success">{{ $item->total_sold }} terjual</span>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>
        </div>
    </div>

    <div class="col-lg-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white border-bottom-0 pb-0">
                <h6 class="fw-bold mb-0">Transaksi Terbaru</h6>
            </div>
            <div class="card-body p-0">
                @if ($recentTransactions->isEmpty())
                    <div class="text-center text-muted py-4 small">Belum ada transaksi.</div>
                @else
                    <ul class="list-group list-group-flush">
                        @foreach ($recentTransactions as $trx)
                            <li class="list-group-item px-3 py-2">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <div class="fw-semibold small font-monospace">{{ $trx->code }}</div>
                                        <div class="text-muted" style="font-size:0.72rem;">
                                            {{ $trx->transaction_date->format('d M Y, H:i') }}
                                            &bull; {{ $trx->user->name }}
                                        </div>
                                    </div>
                                    <span class="fw-bold text-success small">
                                        Rp {{ number_format($trx->total, 0, ',', '.') }}
                                    </span>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>
        </div>
    </div>

</div>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.3/dist/chart.umd.min.js"></script>
<script>
(function () {
    const labels   = @json($chartLabels);
    const sales    = @json($chartSales);
    const expenses = @json($chartExpenses);

    const ctx = document.getElementById('salesExpensesChart');
    if (!ctx) return;

    new Chart(ctx, {
        type: 'bar',
        data: {
            labels,
            datasets: [
                {
                    label: 'Penjualan (Rp)',
                    data: sales,
                    backgroundColor: 'rgba(25, 135, 84, 0.7)',
                    borderColor: 'rgba(25, 135, 84, 1)',
                    borderWidth: 1,
                    borderRadius: 4,
                },
                {
                    label: 'Pengeluaran (Rp)',
                    data: expenses,
                    backgroundColor: 'rgba(220, 53, 69, 0.6)',
                    borderColor: 'rgba(220, 53, 69, 1)',
                    borderWidth: 1,
                    borderRadius: 4,
                },
            ],
        },
        options: {
            responsive: true,
            plugins: {
                legend: { position: 'top' },
                tooltip: {
                    callbacks: {
                        label: ctx => 'Rp ' + ctx.parsed.y.toLocaleString('id-ID'),
                    },
                },
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: val => 'Rp ' + val.toLocaleString('id-ID'),
                        maxTicksLimit: 6,
                    },
                },
            },
        },
    });
})();
</script>
@endpush
