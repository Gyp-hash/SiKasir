@extends('layouts.app', ['title' => 'Detail Transaksi ' . $transaction->code . ' - SiKasir Angkringan'])

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">

        {{-- Header --}}
        <div class="d-flex justify-content-between align-items-center mb-3">
            <a href="{{ route('kasir.transactions.index') }}" class="btn btn-outline-secondary btn-sm">
                ← Kembali ke Riwayat
            </a>
            <h1 class="h5 mb-0 text-muted">Detail Transaksi</h1>
        </div>

        {{-- Kartu Informasi Transaksi --}}
        <div class="card shadow-sm mb-3">
            <div class="card-header bg-dark text-white">
                <div class="d-flex justify-content-between align-items-center">
                    <span class="fw-bold font-monospace fs-6">{{ $transaction->code }}</span>
                    @if ($transaction->status === 'paid')
                        <span class="badge bg-success">✓ Lunas</span>
                    @else
                        <span class="badge bg-secondary">{{ $transaction->status }}</span>
                    @endif
                </div>
            </div>

            <div class="card-body">
                <div class="row g-3">
                    <div class="col-sm-6">
                        <table class="table table-sm table-borderless mb-0">
                            <tr>
                                <th scope="row" class="text-muted fw-normal ps-0" style="width:45%">Kasir</th>
                                <td class="fw-semibold">{{ $transaction->user->name }}</td>
                            </tr>
                            <tr>
                                <th scope="row" class="text-muted fw-normal ps-0">Tanggal</th>
                                <td>{{ $transaction->transaction_date->format('d M Y, H:i') }}</td>
                            </tr>
                            <tr>
                                <th scope="row" class="text-muted fw-normal ps-0">Metode</th>
                                <td class="text-capitalize">{{ $transaction->payment_method }}</td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-sm-6">
                        <table class="table table-sm table-borderless mb-0">
                            <tr>
                                <th scope="row" class="text-muted fw-normal ps-0" style="width:45%">Subtotal</th>
                                <td class="text-end fw-semibold">Rp {{ number_format($transaction->subtotal, 0, ',', '.') }}</td>
                            </tr>
                            <tr>
                                <th scope="row" class="text-muted fw-normal ps-0">Diskon</th>
                                <td class="text-end text-danger">
                                    @if ($transaction->discount > 0)
                                        − Rp {{ number_format($transaction->discount, 0, ',', '.') }}
                                    @else
                                        −
                                    @endif
                                </td>
                            </tr>
                            <tr class="border-top">
                                <th scope="row" class="text-muted fw-normal ps-0">Total</th>
                                <td class="text-end fw-bold text-success fs-6">Rp {{ number_format($transaction->total, 0, ',', '.') }}</td>
                            </tr>
                            <tr>
                                <th scope="row" class="text-muted fw-normal ps-0">Dibayar</th>
                                <td class="text-end">Rp {{ number_format($transaction->cash_paid, 0, ',', '.') }}</td>
                            </tr>
                            <tr>
                                <th scope="row" class="text-muted fw-normal ps-0">Kembalian</th>
                                <td class="text-end fw-bold text-primary">Rp {{ number_format($transaction->change, 0, ',', '.') }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        {{-- Tabel Detail Produk --}}
        <div class="card shadow-sm">
            <div class="card-header bg-light fw-semibold">
                Daftar Item ({{ $transaction->details->count() }} produk)
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover table-bordered align-middle mb-0">
                        <thead class="table-secondary">
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Produk</th>
                                <th scope="col">Kategori</th>
                                <th scope="col" class="text-center">Qty</th>
                                <th scope="col" class="text-end">Harga Satuan</th>
                                <th scope="col" class="text-end">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($transaction->details as $i => $detail)
                                <tr>
                                    <td class="text-muted small">{{ $i + 1 }}</td>
                                    <td class="fw-semibold">{{ $detail->product->name }}</td>
                                    <td class="small text-muted">{{ $detail->product->category->name }}</td>
                                    <td class="text-center">{{ $detail->quantity }}</td>
                                    <td class="text-end small">Rp {{ number_format($detail->price, 0, ',', '.') }}</td>
                                    <td class="text-end fw-semibold text-success">Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="table-light">
                            <tr>
                                <td colspan="5" class="text-end fw-bold">Total</td>
                                <td class="text-end fw-bold text-success">Rp {{ number_format($transaction->total, 0, ',', '.') }}</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
            <div class="card-footer text-muted small text-end">
                Transaksi diproses oleh <strong>{{ $transaction->user->name }}</strong> pada {{ $transaction->transaction_date->format('d M Y \p\u\k\u\l H:i') }}
            </div>
        </div>

        {{-- Tombol Aksi --}}
        <div class="d-flex gap-2 mt-3">
            <a href="{{ route('kasir.transactions.index') }}" class="btn btn-outline-secondary">
                ← Kembali
            </a>
            <a href="{{ route('kasir.pos.index') }}" class="btn btn-dark">
                🛒 Transaksi Baru
            </a>
        </div>

    </div>
</div>
@endsection
