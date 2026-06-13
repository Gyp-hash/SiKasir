@extends('layouts.app', ['title' => 'Riwayat Transaksi - Sikasir Angkringan'])

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h4 mb-1 fw-bold">Riwayat Transaksi</h1>
        <p class="text-muted small mb-0">Semua transaksi yang sudah diselesaikan.</p>
    </div>
    <a href="{{ route('kasir.pos.index') }}" class="btn btn-primary fw-semibold px-4">Transaksi Baru</a>
</div>

{{-- Filter --}}
<div class="card mb-4">
    <div class="card-header">
        <span class="fw-semibold text-dark" style="font-size: 0.875rem;">Cari Transaksi</span>
    </div>
    <div class="card-body">
        <form class="row g-2 align-items-center" method="GET" action="{{ route('kasir.transactions.index') }}">
            <div class="col">
                <input class="form-control" name="search" type="search" value="{{ $search }}" placeholder="Cari kode transaksi...">
            </div>
            <div class="col-auto">
                <button class="btn btn-primary fw-semibold" type="submit">Cari</button>
                @if ($search)
                    <a href="{{ route('kasir.transactions.index') }}" class="btn btn-outline-secondary fw-semibold">Reset</a>
                @endif
            </div>
        </form>
    </div>
</div>

{{-- Data Table --}}
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span class="fw-semibold text-dark" style="font-size: 0.875rem;">Data Transaksi</span>
        @if (!$transactions->isEmpty())
            <span class="badge bg-secondary-subtle">{{ $transactions->total() }} transaksi</span>
        @endif
    </div>
    @if ($transactions->isEmpty())
        <div class="card-body text-center text-muted py-5">
            <p class="small mb-3">{{ $search ? 'Transaksi tidak ditemukan.' : 'Belum ada transaksi.' }}</p>
            @if (!$search)
                <a href="{{ route('kasir.pos.index') }}" class="btn btn-primary btn-sm fw-semibold">Buka POS</a>
            @endif
        </div>
    @else
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th style="width: 4%">#</th>
                        <th>Kode Transaksi</th>
                        <th>Tanggal & Waktu</th>
                        <th class="text-end">Total Belanja</th>
                        <th class="text-end">Dibayar</th>
                        <th class="text-end">Kembalian</th>
                        <th class="text-center" style="width: 8%">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($transactions as $index => $transaction)
                        <tr>
                            <td class="text-muted small">{{ $transactions->firstItem() + $index }}</td>
                            <td class="font-monospace fw-bold text-dark small">{{ $transaction->code }}</td>
                            <td class="small text-muted text-nowrap">{{ $transaction->transaction_date->format('d M Y, H:i') }}</td>
                            <td class="text-end fw-semibold text-dark small">Rp {{ number_format($transaction->total, 0, ',', '.') }}</td>
                            <td class="text-end text-muted small">Rp {{ number_format($transaction->cash_paid, 0, ',', '.') }}</td>
                            <td class="text-end text-muted small">Rp {{ number_format($transaction->change, 0, ',', '.') }}</td>
                            <td class="text-center">
                                <a href="{{ route('kasir.transactions.show', $transaction) }}" class="btn btn-sm btn-action">
                                    Detail
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="card-footer d-flex flex-column flex-sm-row justify-content-between align-items-center gap-2">
            <small class="text-muted">Menampilkan {{ $transactions->firstItem() }}–{{ $transactions->lastItem() }} dari {{ $transactions->total() }} transaksi</small>
            <div>{{ $transactions->links() }}</div>
        </div>
    @endif
</div>
@endsection
