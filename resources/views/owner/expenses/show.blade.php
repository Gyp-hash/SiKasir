@extends('layouts.app', ['title' => 'Detail Pengeluaran - SiKasir Angkringan'])

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-6">

        <div class="d-flex justify-content-between align-items-center mb-3">
            <a href="{{ route('owner.expenses.index') }}" class="btn btn-outline-secondary btn-sm">← Kembali</a>
            <h1 class="h5 mb-0 text-muted">Detail Pengeluaran</h1>
        </div>

        <div class="card shadow-sm">
            <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
                <span class="fw-semibold">💸 Rincian Pengeluaran</span>
                <span class="badge bg-secondary">{{ $expense->category }}</span>
            </div>

            <div class="card-body">
                <table class="table table-borderless table-sm mb-0">
                    <tbody>
                        <tr>
                            <th scope="row" class="text-muted fw-normal" style="width: 40%">Tanggal</th>
                            <td class="fw-semibold">{{ $expense->expense_date->format('d M Y') }}</td>
                        </tr>
                        <tr>
                            <th scope="row" class="text-muted fw-normal">Kategori</th>
                            <td>
                                <span class="badge bg-secondary">{{ $expense->category }}</span>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row" class="text-muted fw-normal">Keterangan</th>
                            <td>{{ $expense->description }}</td>
                        </tr>
                        <tr class="border-top">
                            <th scope="row" class="text-muted fw-normal pt-3">Nominal</th>
                            <td class="pt-3 fw-bold text-danger fs-5">
                                Rp {{ number_format($expense->amount, 0, ',', '.') }}
                            </td>
                        </tr>
                        <tr class="border-top">
                            <th scope="row" class="text-muted fw-normal pt-3">Dicatat Oleh</th>
                            <td class="pt-3">{{ $expense->creator->name }}</td>
                        </tr>
                        <tr>
                            <th scope="row" class="text-muted fw-normal">Waktu Dicatat</th>
                            <td class="text-muted small">{{ $expense->created_at->format('d M Y, H:i') }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="card-footer d-flex gap-2">
                <a href="{{ route('owner.expenses.index') }}" class="btn btn-outline-secondary btn-sm">← Kembali ke Daftar</a>
                <a href="{{ route('owner.expenses.create') }}" class="btn btn-dark btn-sm">+ Tambah Pengeluaran Baru</a>
            </div>
        </div>

    </div>
</div>
@endsection
