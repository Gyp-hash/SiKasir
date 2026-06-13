@extends('layouts.app', ['title' => 'Riwayat Transaksi - SiKasir Angkringan'])

@section('content')
<div class="card shadow-sm">
    <div class="card-header bg-dark text-white d-flex align-items-center justify-content-between">
        <h1 class="h5 mb-0">📋 Riwayat Transaksi</h1>
        <a href="{{ route('kasir.pos.index') }}" class="btn btn-sm btn-warning text-dark fw-semibold">+ POS Baru</a>
    </div>

    <div class="card-body">

        {{-- Form Search --}}
        <form method="GET" action="{{ route('kasir.transactions.index') }}" class="mb-3">
            <div class="input-group">
                <input
                    id="transaction-search"
                    type="text"
                    name="search"
                    class="form-control"
                    placeholder="Cari kode transaksi..."
                    value="{{ $search }}"
                    autocomplete="off"
                >
                <button class="btn btn-outline-secondary" type="submit">Cari</button>
                @if ($search)
                    <a href="{{ route('kasir.transactions.index') }}" class="btn btn-outline-danger">× Reset</a>
                @endif
            </div>
        </form>

        {{-- Tabel Transaksi --}}
        @if ($transactions->isEmpty())
            <div class="text-center text-muted py-5">
                <div class="fs-1">📭</div>
                <p class="mt-2">Belum ada transaksi ditemukan.</p>
                <a href="{{ route('kasir.pos.index') }}" class="btn btn-dark mt-2">Mulai Transaksi</a>
            </div>
        @else
            <div class="table-responsive">
                <table class="table table-hover table-bordered align-middle mb-0">
                    <thead class="table-dark">
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Kode Transaksi</th>
                            <th scope="col">Tanggal</th>
                            <th scope="col" class="text-end">Total</th>
                            <th scope="col" class="text-center">Status</th>
                            <th scope="col" class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($transactions as $index => $transaction)
                            <tr>
                                <td class="text-muted small">{{ $transactions->firstItem() + $index }}</td>
                                <td>
                                    <span class="fw-semibold font-monospace">{{ $transaction->code }}</span>
                                </td>
                                <td class="small text-muted">
                                    {{ $transaction->transaction_date->format('d M Y, H:i') }}
                                </td>
                                <td class="text-end fw-bold text-success">
                                    Rp {{ number_format($transaction->total, 0, ',', '.') }}
                                </td>
                                <td class="text-center">
                                    @if ($transaction->status === 'paid')
                                        <span class="badge bg-success">Lunas</span>
                                    @else
                                        <span class="badge bg-secondary">{{ $transaction->status }}</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <a
                                        href="{{ route('kasir.transactions.show', $transaction) }}"
                                        class="btn btn-sm btn-outline-dark"
                                    >
                                        Detail
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            <div class="mt-3 d-flex justify-content-between align-items-center">
                <small class="text-muted">
                    Menampilkan {{ $transactions->firstItem() }}–{{ $transactions->lastItem() }} dari {{ $transactions->total() }} transaksi
                </small>
                {{ $transactions->links() }}
            </div>
        @endif

    </div>
</div>
@endsection
