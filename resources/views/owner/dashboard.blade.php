@extends('layouts.app', ['title' => 'Dashboard Owner - Sikasir Angkringan'])

@section('content')

{{-- Header --}}
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h4 mb-1 fw-bold" style="color:#0F172A;">Ringkasan Operasional</h1>
        <p class="text-muted small mb-0">{{ now()->translatedFormat('l, d F Y') }} &mdash; {{ auth()->user()->name }}</p>
    </div>
</div>

{{-- Baris 1: Statistik Utama (4 kartu) --}}
<div class="row g-3 mb-4">

    <div class="col-sm-6 col-xl-3">
        <div class="card card-stat card-hover h-100" style="border-top: 3px solid #0FA4AF;">
            <div class="card-body" style="padding:1.125rem 1.25rem;">
                <div class="text-muted text-uppercase mb-2" style="font-size: 0.68rem; font-weight: 700; letter-spacing: 0.07em;">Penjualan Hari Ini</div>
                <div class="fw-extrabold text-dark" style="font-size: 1.5rem; letter-spacing: -0.02em;">
                    Rp {{ number_format($salesToday, 0, ',', '.') }}
                </div>
            </div>
        </div>
    </div>

    <div class="col-sm-6 col-xl-3">
        <div class="card card-stat card-hover h-100" style="border-top: 3px solid #16A34A;">
            <div class="card-body" style="padding:1.125rem 1.25rem;">
                <div class="text-muted text-uppercase mb-2" style="font-size: 0.68rem; font-weight: 700; letter-spacing: 0.07em;">Penjualan Bulan Ini</div>
                <div class="fw-extrabold text-success" style="font-size: 1.5rem; letter-spacing: -0.02em;">
                    Rp {{ number_format($salesThisMonth, 0, ',', '.') }}
                </div>
            </div>
        </div>
    </div>

    <div class="col-sm-6 col-xl-3">
        <div class="card card-stat card-hover h-100" style="border-top: 3px solid #964734;">
            <div class="card-body" style="padding:1.125rem 1.25rem;">
                <div class="text-muted text-uppercase mb-2" style="font-size: 0.68rem; font-weight: 700; letter-spacing: 0.07em;">Pengeluaran Bulan Ini</div>
                <div class="fw-extrabold text-danger" style="font-size: 1.5rem; letter-spacing: -0.02em;">
                    Rp {{ number_format($expensesThisMonth, 0, ',', '.') }}
                </div>
            </div>
        </div>
    </div>

    <div class="col-sm-6 col-xl-3">
        <div class="card card-stat card-hover h-100" style="border-top: 3px solid {{ $netProfitThisMonth >= 0 ? '#0FA4AF' : '#964734' }};">
            <div class="card-body" style="padding:1.125rem 1.25rem;">
                <div class="text-muted text-uppercase mb-2" style="font-size: 0.68rem; font-weight: 700; letter-spacing: 0.07em;">Est. Laba Bersih Bulan Ini</div>
                <div class="fw-extrabold {{ $netProfitThisMonth >= 0 ? 'text-dark' : 'text-danger' }}" style="font-size: 1.5rem; letter-spacing: -0.02em;">
                    Rp {{ number_format($netProfitThisMonth, 0, ',', '.') }}
                </div>
            </div>
        </div>
    </div>

</div>

{{-- Baris 2: Statistik Sekunder (3 kartu angka) --}}
<div class="row g-3 mb-4">

    <div class="col-sm-4">
        <div class="card card-hover h-100">
            <div class="card-body" style="padding:1.125rem 1.25rem;">
                <div class="text-muted text-uppercase mb-2" style="font-size: 0.68rem; font-weight: 700; letter-spacing: 0.07em;">Total Produk Aktif</div>
                <div class="fw-extrabold text-dark" style="font-size: 1.75rem;">{{ $totalProducts }}</div>
            </div>
        </div>
    </div>

    <div class="col-sm-4">
        <div class="card card-hover h-100">
            <div class="card-body" style="padding:1.125rem 1.25rem;">
                <div class="text-muted text-uppercase mb-2" style="font-size: 0.68rem; font-weight: 700; letter-spacing: 0.07em;">Total Transaksi</div>
                <div class="fw-extrabold text-dark" style="font-size: 1.75rem;">{{ $totalTransactions }}</div>
            </div>
        </div>
    </div>

    <div class="col-sm-4">
        <div class="card card-hover h-100">
            <div class="card-body" style="padding:1.125rem 1.25rem;">
                <div class="text-muted text-uppercase mb-2" style="font-size: 0.68rem; font-weight: 700; letter-spacing: 0.07em;">Total Pengeluaran</div>
                <div class="fw-extrabold text-danger" style="font-size: 1.75rem;">Rp {{ number_format($totalExpenses, 0, ',', '.') }}</div>
            </div>
        </div>
    </div>

</div>

{{-- Baris 3: Grafik (kiri) + Stok Menipis (kanan) --}}
<div class="row g-3 mb-4">

    <div class="col-lg-8">
        <div class="card h-100">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span class="fw-bold text-dark" style="font-size: 0.875rem;">Penjualan vs Pengeluaran — 7 Hari Terakhir</span>
            </div>
            <div class="card-body" style="height: 340px; position: relative; padding: 1.25rem;">
                <canvas id="salesExpensesChart"></canvas>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card h-100">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span class="fw-bold text-dark" style="font-size: 0.875rem;">Stok Menipis</span>
                @if ($lowStockProducts->isNotEmpty())
                    <span class="badge bg-danger-subtle">{{ $lowStockProducts->count() }}</span>
                @endif
            </div>
            @if ($lowStockProducts->isEmpty())
                <div class="card-body text-center text-muted py-5">
                    <i class="bi bi-check-circle text-success" style="font-size: 1.5rem;"></i>
                    <p class="mt-2 small mb-0">Semua stok produk aman</p>
                </div>
            @else
                <div style="overflow-y: auto; max-height: 240px;">
                    <ul class="list-group list-group-flush">
                        @foreach ($lowStockProducts as $product)
                            <li class="list-group-item d-flex justify-content-between align-items-center px-4 py-3" style="border-color: #F1F5F9;">
                                <div class="min-width-0">
                                    <div class="small fw-semibold text-dark text-truncate">{{ $product->name }}</div>
                                </div>
                                <span class="badge {{ $product->stock <= 0 ? 'bg-danger-subtle' : 'bg-warning-subtle' }} ms-2">
                                    Sisa: {{ $product->stock }}
                                </span>
                            </li>
                        @endforeach
                    </ul>
                </div>
                <div class="card-footer">
                    <a href="{{ route('owner.stock.restock') }}" class="btn btn-sm btn-primary w-100 fw-semibold">
                        Restok Sekarang
                    </a>
                </div>
            @endif
        </div>
    </div>

</div>

{{-- Baris 4: Top Produk + Transaksi Terbaru --}}
<div class="row g-3">

    <div class="col-lg-6">
        <div class="card h-100">
            <div class="card-header">
                <span class="fw-bold text-dark" style="font-size: 0.875rem;">Top 5 Produk Terlaris</span>
            </div>
            @if ($topProducts->isEmpty())
                <div class="card-body text-center text-muted py-5">
                    <p class="mt-2 small mb-0">Belum ada data penjualan</p>
                </div>
            @else
                <ul class="list-group list-group-flush">
                    @foreach ($topProducts as $i => $item)
                        <li class="list-group-item d-flex justify-content-between align-items-center px-4 py-3" style="border-color: #F1F5F9;">
                            <div class="d-flex align-items-center gap-3">
                                <span class="d-flex align-items-center justify-content-center fw-bold rounded-circle text-primary"
                                      style="width: 26px; height: 26px; background-color: var(--primary-light); font-size: 0.78rem; flex-shrink:0;">
                                    {{ $i + 1 }}
                                </span>
                                <div>
                                    <span class="small fw-semibold text-dark">{{ $item->product->name ?? '—' }}</span>
                                    <div class="text-muted" style="font-size: 0.73rem;">Rp {{ number_format($item->product->selling_price ?? 0, 0, ',', '.') }}</div>
                                </div>
                            </div>
                            <span class="badge bg-secondary-subtle ms-2">{{ $item->total_sold }} terjual</span>
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>
    </div>

    <div class="col-lg-6">
        <div class="card h-100">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span class="fw-bold text-dark" style="font-size: 0.875rem;">Transaksi Terbaru</span>
                <a href="{{ route('owner.reports.sales') }}" class="btn btn-sm btn-action fw-semibold">Laporan</a>
            </div>
            @if ($recentTransactions->isEmpty())
                <div class="card-body text-center text-muted py-5">
                    <p class="mt-2 small mb-0">Belum ada transaksi</p>
                </div>
            @else
                <ul class="list-group list-group-flush">
                    @foreach ($recentTransactions as $trx)
                        <li class="list-group-item px-4 py-3 d-flex justify-content-between align-items-center" style="border-color: #F1F5F9;">
                            <div>
                                <div class="fw-bold small text-dark font-monospace">{{ $trx->code }}</div>
                                <div class="text-muted" style="font-size: 0.73rem;">
                                    {{ $trx->transaction_date->format('d M Y, H:i') }} &bull; {{ $trx->user->name }}
                                </div>
                            </div>
                            <span class="fw-bold text-success small">
                                Rp {{ number_format($trx->total, 0, ',', '.') }}
                            </span>
                        </li>
                    @endforeach
                </ul>
            @endif
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
                    backgroundColor: '#0FA4AF',
                    borderColor: '#0FA4AF',
                    borderWidth: 0,
                    borderRadius: 5,
                    borderSkipped: false,
                },
                {
                    label: 'Pengeluaran (Rp)',
                    data: expenses,
                    backgroundColor: '#E2E8F0',
                    borderColor: '#E2E8F0',
                    borderWidth: 0,
                    borderRadius: 5,
                    borderSkipped: false,
                },
            ],
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'top',
                    labels: {
                        boxWidth: 10,
                        boxHeight: 10,
                        borderRadius: 3,
                        useBorderRadius: true,
                        font: { family: 'Plus Jakarta Sans', size: 11.5, weight: '500' },
                        color: '#64748B',
                    }
                },
                tooltip: {
                    backgroundColor: '#ffffff',
                    titleColor: '#0F172A',
                    bodyColor: '#64748B',
                    borderColor: '#E2E8F0',
                    borderWidth: 1,
                    padding: 10,
                    callbacks: {
                        label: ctx => ctx.dataset.label + ': Rp ' + ctx.parsed.y.toLocaleString('id-ID'),
                    },
                },
            },
            scales: {
                x: {
                    grid: { display: false },
                    border: { display: false },
                    ticks: {
                        font: { family: 'Plus Jakarta Sans', size: 11 },
                        color: '#94A3B8',
                    }
                },
                y: {
                    beginAtZero: true,
                    grid: { color: '#F1F5F9' },
                    border: { display: false, dash: [4, 4] },
                    ticks: {
                        callback: val => 'Rp ' + val.toLocaleString('id-ID'),
                        maxTicksLimit: 5,
                        font: { family: 'Plus Jakarta Sans', size: 11 },
                        color: '#94A3B8',
                    },
                },
            },
        },
    });
})();
</script>
@endpush
