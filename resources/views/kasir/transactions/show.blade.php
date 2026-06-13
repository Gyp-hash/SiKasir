@extends('layouts.app', ['title' => 'Detail Transaksi ' . $transaction->code . ' - Sikasir Angkringan'])

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">

        {{-- Header --}}
        <div class="d-flex justify-content-between align-items-center mb-4">
            <a href="{{ route('kasir.transactions.index') }}" class="btn btn-outline-secondary d-flex align-items-center gap-2 fw-semibold">
                <i class="bi bi-arrow-left"></i> Kembali ke Riwayat
            </a>
            <h1 class="h5 mb-0 fw-bold text-dark">Detail Transaksi</h1>
        </div>

        {{-- Kartu Informasi Transaksi --}}
        <div class="card mb-4">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-4 pb-3 border-bottom">
                    <div class="fw-bold text-dark font-monospace">{{ $transaction->code }}</div>
                    @if ($transaction->status === 'paid')
                        <span class="badge bg-success-subtle">Lunas</span>
                    @else
                        <span class="badge bg-secondary-subtle">{{ $transaction->status }}</span>
                    @endif
                </div>

                <div class="row g-4">
                    <div class="col-sm-6">
                        <div class="table-responsive">
                            <table class="table table-sm table-borderless align-middle mb-0">
                                <tr>
                                    <th scope="row" class="text-muted fw-normal ps-0" style="width:40%">Kasir</th>
                                    <td class="fw-bold text-dark">: {{ $transaction->user->name }}</td>
                                </tr>
                                <tr>
                                    <th scope="row" class="text-muted fw-normal ps-0">Tanggal</th>
                                    <td class="text-dark">: {{ $transaction->transaction_date->format('d M Y, H:i') }}</td>
                                </tr>
                                <tr>
                                    <th scope="row" class="text-muted fw-normal ps-0">Metode</th>
                                    <td class="text-capitalize text-dark">: {{ $transaction->payment_method }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="table-responsive">
                            <table class="table table-sm table-borderless align-middle mb-0">
                                <tr>
                                    <th scope="row" class="text-muted fw-normal ps-0" style="width:40%">Subtotal</th>
                                    <td class="text-end fw-semibold text-dark">Rp {{ number_format($transaction->subtotal, 0, ',', '.') }}</td>
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
                                    <th scope="row" class="text-muted fw-bold ps-0 pt-2">Total</th>
                                    <td class="text-end fw-extrabold text-success fs-5 pt-2">Rp {{ number_format($transaction->total, 0, ',', '.') }}</td>
                                </tr>
                                <tr>
                                    <th scope="row" class="text-muted fw-normal ps-0">Dibayar</th>
                                    <td class="text-end text-dark">Rp {{ number_format($transaction->cash_paid, 0, ',', '.') }}</td>
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
        </div>

        {{-- Tabel Detail Produk --}}
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span class="fw-bold text-dark" style="font-size: 0.875rem;">
                    Item Pembelian
                </span>
                <span class="badge bg-secondary-subtle">{{ $transaction->details->count() }} produk</span>
            </div>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead>
                        <tr>
                            <th style="width: 5%">#</th>
                            <th>Produk</th>
                            <th>Kategori</th>
                            <th class="text-center">Qty</th>
                            <th class="text-end">Harga Satuan</th>
                            <th class="text-end">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($transaction->details as $i => $detail)
                            <tr>
                                <td class="text-muted small">{{ $i + 1 }}</td>
                                <td class="fw-bold text-dark small">{{ $detail->product->name }}</td>
                                <td class="small text-muted">{{ $detail->product->category->name }}</td>
                                <td class="text-center fw-medium text-dark">{{ $detail->quantity }}</td>
                                <td class="text-end small text-muted">Rp {{ number_format($detail->price, 0, ',', '.') }}</td>
                                <td class="text-end fw-bold text-success small">Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="5" class="text-end fw-bold text-muted" style="font-size: 0.72rem; text-transform: uppercase; letter-spacing: 0.05em;">Total:</td>
                            <td class="text-end fw-bold text-success">Rp {{ number_format($transaction->total, 0, ',', '.') }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
            <div class="card-footer text-muted small">
                Transaksi diproses oleh <strong class="text-dark">{{ $transaction->user->name }}</strong>
                pada {{ $transaction->transaction_date->format('d M Y \p\u\k\u\l H:i') }}
            </div>
        </div>

        {{-- Tombol Aksi --}}
        <div class="d-flex gap-2">
            <a href="{{ route('kasir.transactions.index') }}" class="btn btn-outline-secondary fw-semibold">
                Riwayat
            </a>
            <a href="{{ route('kasir.pos.index') }}" class="btn btn-primary fw-semibold">
                Transaksi Baru
            </a>
        </div>

    </div>
</div>
@endsection
