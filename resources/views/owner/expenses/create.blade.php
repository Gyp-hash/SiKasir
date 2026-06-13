@extends('layouts.app', ['title' => 'Tambah Pengeluaran - SiKasir Angkringan'])

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-6">

        <div class="d-flex justify-content-between align-items-center mb-3">
            <a href="{{ route('owner.expenses.index') }}" class="btn btn-outline-secondary btn-sm">← Daftar Pengeluaran</a>
            <h1 class="h5 mb-0 text-muted">Tambah Pengeluaran</h1>
        </div>

        <div class="card shadow-sm">
            <div class="card-header bg-dark text-white">
                <span class="fw-semibold">Form Pencatatan Pengeluaran</span>
            </div>

            <div class="card-body">

                @if ($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <form method="POST" action="{{ route('owner.expenses.store') }}" id="expense-form">
                    @csrf

                    {{-- Tanggal --}}
                    <div class="mb-3">
                        <label for="expense_date" class="form-label fw-semibold">
                            Tanggal <span class="text-danger">*</span>
                        </label>
                        <input
                            type="date"
                            id="expense_date"
                            name="expense_date"
                            class="form-control @error('expense_date') is-invalid @enderror"
                            value="{{ old('expense_date', date('Y-m-d')) }}"
                            required
                        >
                        @error('expense_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Kategori --}}
                    <div class="mb-3">
                        <label for="category" class="form-label fw-semibold">
                            Kategori <span class="text-danger">*</span>
                        </label>
                        <select
                            id="category"
                            name="category"
                            class="form-select @error('category') is-invalid @enderror"
                            required
                        >
                            <option value="">— Pilih Kategori —</option>
                            @foreach ($categories as $cat)
                                <option value="{{ $cat }}" {{ old('category') === $cat ? 'selected' : '' }}>
                                    {{ $cat }}
                                </option>
                            @endforeach
                        </select>
                        @error('category')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Keterangan --}}
                    <div class="mb-3">
                        <label for="description" class="form-label fw-semibold">
                            Keterangan <span class="text-danger">*</span>
                        </label>
                        <textarea
                            id="description"
                            name="description"
                            rows="3"
                            class="form-control @error('description') is-invalid @enderror"
                            placeholder="Contoh: Beli tepung, gula, dan minyak goreng..."
                            required
                        >{{ old('description') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Nominal --}}
                    <div class="mb-4">
                        <label for="amount" class="form-label fw-semibold">
                            Nominal (Rp) <span class="text-danger">*</span>
                        </label>
                        <input
                            type="number"
                            id="amount"
                            name="amount"
                            class="form-control @error('amount') is-invalid @enderror"
                            min="1"
                            step="1"
                            value="{{ old('amount') }}"
                            placeholder="Masukkan jumlah pengeluaran..."
                            required
                        >
                        @error('amount')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" id="btn-save-expense" class="btn btn-success fw-bold">
                            Simpan Pengeluaran
                        </button>
                        <a href="{{ route('owner.expenses.index') }}" class="btn btn-outline-secondary">Batal</a>
                    </div>
                </form>

            </div>
        </div>

    </div>
</div>
@endsection
