@extends('layouts.app', ['title' => 'Point of Sale - SiKasir Angkringan'])

@section('content')
<div class="row g-3">

    {{-- Kolom Kiri: Daftar Produk --}}
    <div class="col-lg-7">
        <div class="card shadow-sm">
            <div class="card-header bg-dark text-white d-flex align-items-center justify-content-between">
                <h1 class="h5 mb-0">🛒 Point of Sale</h1>
                <span class="badge bg-secondary">{{ $products->total() }} produk</span>
            </div>
            <div class="card-body">

                {{-- Form Search --}}
                <form method="GET" action="{{ route('kasir.pos.index') }}" class="mb-3">
                    <div class="input-group">
                        <input
                            id="pos-search"
                            type="text"
                            name="search"
                            class="form-control"
                            placeholder="Cari produk atau kategori..."
                            value="{{ $search }}"
                            autocomplete="off"
                        >
                        <button class="btn btn-outline-secondary" type="submit">Cari</button>
                        @if ($search)
                            <a href="{{ route('kasir.pos.index') }}" class="btn btn-outline-danger">×</a>
                        @endif
                    </div>
                </form>

                {{-- Validasi Error --}}
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

                {{-- Grid Produk --}}
                @if ($products->isEmpty())
                    <div class="text-center text-muted py-5">
                        <div class="fs-1">🍚</div>
                        <p class="mt-2">Tidak ada produk ditemukan.</p>
                    </div>
                @else
                    <div class="row row-cols-2 row-cols-md-3 g-2">
                        @foreach ($products as $product)
                            <div class="col">
                                <div class="card h-100 border product-card">
                                    {{-- Gambar Produk --}}
                                    @if ($product->image_url)
                                        <img src="{{ $product->image_url }}" class="card-img-top product-img" alt="{{ $product->name }}">
                                    @else
                                        <div class="product-img-placeholder d-flex align-items-center justify-content-center bg-light text-muted">
                                            <span style="font-size:2rem;">🍽️</span>
                                        </div>
                                    @endif

                                    <div class="card-body p-2">
                                        <div class="fw-semibold small text-truncate" title="{{ $product->name }}">{{ $product->name }}</div>
                                        <div class="text-muted" style="font-size:0.75rem;">{{ $product->category->name }}</div>
                                        <div class="text-success fw-bold small mt-1">Rp {{ number_format($product->selling_price, 0, ',', '.') }}</div>
                                        <div class="text-muted" style="font-size:0.72rem;">Stok: {{ $product->stock }}</div>
                                    </div>

                                    <div class="card-footer p-2">
                                        <form method="POST" action="{{ route('kasir.pos.cart.add', $product) }}">
                                            @csrf
                                            <div class="input-group input-group-sm">
                                                <input
                                                    type="number"
                                                    name="quantity"
                                                    class="form-control"
                                                    value="1"
                                                    min="1"
                                                    max="{{ $product->stock }}"
                                                    required
                                                >
                                                <button class="btn btn-dark btn-sm" type="submit" title="Tambah ke keranjang">+</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    {{-- Pagination --}}
                    <div class="mt-3">
                        {{ $products->links() }}
                    </div>
                @endif

            </div>
        </div>
    </div>

    {{-- Kolom Kanan: Keranjang & Checkout --}}
    <div class="col-lg-5">
        <div class="card shadow-sm sticky-top" style="top: 1rem;">
            <div class="card-header bg-dark text-white d-flex align-items-center justify-content-between">
                <h2 class="h5 mb-0">🧾 Keranjang</h2>
                <span class="badge bg-warning text-dark">{{ $cartItems->count() }} item</span>
            </div>

            <div class="card-body p-0">
                @if ($cartItems->isEmpty())
                    <div class="text-center text-muted py-4">
                        <div class="fs-2">🛒</div>
                        <p class="mt-2 small">Keranjang masih kosong.</p>
                    </div>
                @else
                    <div class="list-group list-group-flush" style="max-height: 340px; overflow-y: auto;">
                        @foreach ($cartItems as $item)
                            <div class="list-group-item px-3 py-2">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div class="flex-grow-1 me-2">
                                        <div class="fw-semibold small">{{ $item['product']->name }}</div>
                                        <div class="text-muted" style="font-size:0.75rem;">
                                            Rp {{ number_format($item['price'], 0, ',', '.') }} / pcs
                                        </div>
                                        <div class="text-success small fw-bold">
                                            = Rp {{ number_format($item['subtotal'], 0, ',', '.') }}
                                        </div>
                                    </div>

                                    {{-- Update Quantity --}}
                                    <form method="POST" action="{{ route('kasir.pos.cart.update', $item['product']->id) }}" class="d-flex align-items-center gap-1">
                                        @csrf
                                        @method('PATCH')
                                        <input
                                            type="number"
                                            name="quantity"
                                            value="{{ $item['quantity'] }}"
                                            min="1"
                                            max="{{ $item['product']->stock }}"
                                            class="form-control form-control-sm"
                                            style="width: 60px;"
                                            required
                                        >
                                        <button type="submit" class="btn btn-outline-secondary btn-sm" title="Update">✓</button>
                                    </form>

                                    {{-- Hapus Item --}}
                                    <form method="POST" action="{{ route('kasir.pos.cart.remove', $item['product']->id) }}" class="ms-1">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger btn-sm" title="Hapus">×</button>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            {{-- Summary & Checkout --}}
            <div class="card-footer">
                <div class="d-flex justify-content-between mb-1">
                    <span class="text-muted">Subtotal</span>
                    <span class="fw-semibold">Rp {{ number_format($cartSubtotal, 0, ',', '.') }}</span>
                </div>

                @if ($cartItems->isNotEmpty())
                    <hr class="my-2">
                    <form method="POST" action="{{ route('kasir.pos.checkout') }}" id="checkout-form">
                        @csrf

                        <div class="mb-2">
                            <label for="discount" class="form-label small fw-semibold">Diskon (Rp)</label>
                            <input
                                type="number"
                                id="discount"
                                name="discount"
                                class="form-control form-control-sm @error('discount') is-invalid @enderror"
                                min="0"
                                max="{{ $cartSubtotal }}"
                                step="0.01"
                                value="{{ old('discount', 0) }}"
                                placeholder="0"
                            >
                            @error('discount')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="cash_paid" class="form-label small fw-semibold">Uang Tunai (Rp) <span class="text-danger">*</span></label>
                            <input
                                type="number"
                                id="cash_paid"
                                name="cash_paid"
                                class="form-control form-control-sm @error('cash_paid') is-invalid @enderror"
                                min="0"
                                step="0.01"
                                value="{{ old('cash_paid') }}"
                                placeholder="Masukkan nominal uang..."
                                required
                            >
                            @error('cash_paid')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        @error('cart')
                            <div class="alert alert-danger py-2 small">{{ $message }}</div>
                        @enderror

                        <button
                            type="submit"
                            id="btn-checkout"
                            class="btn btn-success w-100 fw-bold"
                        >
                            ✅ Proses Checkout
                        </button>
                    </form>
                @endif
            </div>
        </div>
    </div>

</div>
@endsection

@push('styles')
<style>
    .product-card {
        transition: box-shadow 0.15s ease;
    }
    .product-card:hover {
        box-shadow: 0 4px 12px rgba(0,0,0,0.12);
    }
    .product-img {
        height: 100px;
        object-fit: cover;
    }
    .product-img-placeholder {
        height: 100px;
    }
</style>
@endpush
