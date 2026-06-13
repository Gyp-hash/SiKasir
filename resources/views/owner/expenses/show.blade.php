@extends('layouts.app', ['title' => 'Detail Pengeluaran - Sikasir Angkringan'])

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-6">

        <div class="d-flex justify-content-between align-items-center mb-4">
            <a href="{{ route('owner.expenses.index') }}" class="btn btn-outline-secondary d-flex align-items-center gap-2 fw-semibold">
                <i class="bi bi-arrow-left"></i> Kembali
            </a>
            <h1 class="h5 mb-0 fw-bold text-dark">Detail Pengeluaran</h1>
        </div>

        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span class="fw-bold text-dark" style="font-size: 0.875rem;">Rincian Pengeluaran</span>
                <span class="badge bg-secondary-subtle">{{ $expense->category }}</span>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-borderless align-middle mb-0">
                        <tbody>
                            <tr>
                                <th scope="row" class="text-muted fw-normal py-2 ps-0" style="width: 40%">Tanggal</th>
                                <td class="fw-bold text-dark py-2">: {{ $expense->expense_date->format('d M Y') }}</td>
                            </tr>
                            <tr>
                                <th scope="row" class="text-muted fw-normal py-2 ps-0">Kategori</th>
                                <td class="py-2">: {{ $expense->category }}</td>
                            </tr>
                            <tr>
                                <th scope="row" class="text-muted fw-normal py-2 ps-0">Keterangan</th>
                                <td class="py-2 text-muted">: {{ $expense->description }}</td>
                            </tr>
                            <tr class="border-top">
                                <th scope="row" class="text-muted fw-normal py-3 ps-0">Nominal</th>
                                <td class="py-3 fw-bold text-danger fs-5">
                                    : Rp {{ number_format($expense->amount, 0, ',', '.') }}
                                </td>
                            </tr>
                            <tr class="border-top">
                                <th scope="row" class="text-muted fw-normal py-2 ps-0">Dicatat Oleh</th>
                                <td class="py-2 text-dark">: {{ $expense->creator->name }}</td>
                            </tr>
                            <tr>
                                <th scope="row" class="text-muted fw-normal py-2 ps-0">Waktu Dicatat</th>
                                <td class="py-2 text-muted small">: {{ $expense->created_at->format('d M Y, H:i') }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card-footer d-flex gap-2">
                <a href="{{ route('owner.expenses.index') }}" class="btn btn-outline-secondary fw-semibold">
                    Kembali ke Daftar
                </a>
                <a href="{{ route('owner.expenses.create') }}" class="btn btn-primary fw-semibold">
                    Tambah Baru
                </a>
            </div>
        </div>

    </div>
</div>
@endsection
