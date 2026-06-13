@extends('layouts.app', ['title' => 'Restok Produk - SiKasir Angkringan'])

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-6">

        <div class="d-flex justify-content-between align-items-center mb-3">
            <a href="{{ route('owner.stock.index') }}" class="btn btn-outline-secondary btn-sm">← Riwayat Stok</a>
            <h1 class="h5 mb-0 text-muted">Restok Produk</h1>
        </div>

        <div class="card shadow-sm">
            <div class="card-header bg-dark text-white">
                <span class="fw-semibold">Form Penambahan Stok</span>
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

                <form method="POST" action="{{ route('owner.stock.restock.store') }}" id="restock-form">
                    @csrf

                    {{-- Pilih Produk --}}
                    <div class="mb-3">
                        <label for="product_id" class="form-label fw-semibold">
                            Produk <span class="text-danger">*</span>
                        </label>
                        <select
                            id="product_id"
                            name="product_id"
                            class="form-select @error('product_id') is-invalid @enderror"
                            required
                            onchange="updateStockInfo(this)"
                        >
                            <option value="">— Pilih Produk —</option>
                            @foreach ($products as $product)
                                <option
                                    value="{{ $product->id }}"
                                    data-stock="{{ $product->stock }}"
                                    {{ old('product_id') == $product->id ? 'selected' : '' }}
                                >
                                    {{ $product->name }} (Stok: {{ $product->stock }})
                                </option>
                            @endforeach
                        </select>
                        @error('product_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Info Stok Saat Ini --}}
                    <div id="stock-info" class="alert alert-info py-2 small mb-3" style="display:none;">
                        Stok saat ini: <strong id="current-stock">0</strong>
                    </div>

                    {{-- Jumlah Restok --}}
                    <div class="mb-3">
                        <label for="quantity" class="form-label fw-semibold">
                            Jumlah Restok <span class="text-danger">*</span>
                        </label>
                        <input
                            type="number"
                            id="quantity"
                            name="quantity"
                            class="form-control @error('quantity') is-invalid @enderror"
                            min="1"
                            value="{{ old('quantity', '') }}"
                            placeholder="Masukkan jumlah stok yang ditambahkan..."
                            required
                            oninput="updatePreview()"
                        >
                        @error('quantity')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Preview Stok Baru --}}
                    <div id="stock-preview" class="alert alert-success py-2 small mb-3" style="display:none;">
                        Stok baru setelah restok: <strong id="stock-after-preview">0</strong>
                    </div>

                    {{-- Catatan --}}
                    <div class="mb-4">
                        <label for="notes" class="form-label fw-semibold">Catatan</label>
                        <textarea
                            id="notes"
                            name="notes"
                            class="form-control @error('notes') is-invalid @enderror"
                            rows="3"
                            placeholder="Contoh: Restok dari Supplier A"
                        >{{ old('notes') }}</textarea>
                        @error('notes')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" id="btn-restock" class="btn btn-success fw-bold">
                            Simpan Restok
                        </button>
                        <a href="{{ route('owner.stock.index') }}" class="btn btn-outline-secondary">Batal</a>
                    </div>
                </form>
            </div>
        </div>

    </div>
</div>
@endsection

@push('scripts')
<script>
    let currentStock = 0;

    function updateStockInfo(select) {
        const option = select.options[select.selectedIndex];
        const stockInfoEl  = document.getElementById('stock-info');
        const currentStockEl = document.getElementById('current-stock');

        if (select.value) {
            currentStock = parseInt(option.dataset.stock, 10);
            currentStockEl.textContent = currentStock;
            stockInfoEl.style.display = 'block';
        } else {
            currentStock = 0;
            stockInfoEl.style.display = 'none';
        }

        updatePreview();
    }

    function updatePreview() {
        const qty         = parseInt(document.getElementById('quantity').value, 10);
        const previewEl   = document.getElementById('stock-preview');
        const afterEl     = document.getElementById('stock-after-preview');
        const productId   = document.getElementById('product_id').value;

        if (productId && qty > 0) {
            afterEl.textContent = currentStock + qty;
            previewEl.style.display = 'block';
        } else {
            previewEl.style.display = 'none';
        }
    }

    // Restore state jika ada old input
    document.addEventListener('DOMContentLoaded', function () {
        const select = document.getElementById('product_id');
        if (select.value) {
            updateStockInfo(select);
        }
    });
</script>
@endpush
