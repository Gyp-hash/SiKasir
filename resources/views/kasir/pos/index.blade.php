@extends('layouts.app', ['title' => 'Point of Sale - Sikasir Angkringan'])

@push('styles')
<style>
    /* ===== POS-specific styles ===== */
    .pos-product-card {
        cursor: pointer;
        transition: all 0.15s ease;
    }
    .pos-product-card:hover:not(.out-of-stock) {
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(15,164,175,0.12) !important;
        border-color: var(--primary) !important;
    }
    .pos-product-card.out-of-stock {
        opacity: 0.55;
        cursor: not-allowed;
    }
    .pos-product-img {
        height: 100px;
        object-fit: cover;
    }
    .pos-product-placeholder {
        height: 100px;
        background: linear-gradient(135deg, #F1F5F9 0%, #E2E8F0 100%);
        display: flex; align-items: center; justify-content: center;
        color: var(--text-muted);
        font-size: 1.5rem;
    }

    /* Cart items */
    .cart-item {
        padding: 0.75rem 0;
        border-bottom: 1px solid #F1F5F9;
    }
    .cart-item:last-child { border-bottom: none; }

    /* Qty stepper */
    .qty-stepper {
        display: flex;
        align-items: center;
        gap: 0;
        border: 1.5px solid #E2E8F0;
        border-radius: 0.5rem;
        overflow: hidden;
        width: 96px;
        flex-shrink: 0;
    }
    .qty-stepper button {
        width: 28px;
        height: 28px;
        background: #F8FAFC;
        border: none;
        display: flex; align-items: center; justify-content: center;
        font-size: 0.9rem;
        color: #64748B;
        cursor: pointer;
        flex-shrink: 0;
        transition: background 0.1s;
    }
    .qty-stepper button:hover { background: #E2E8F0; color: #0F172A; }
    .qty-stepper input {
        width: 38px;
        min-width: 0;
        border: none;
        border-left: 1.5px solid #E2E8F0;
        border-right: 1.5px solid #E2E8F0;
        text-align: center;
        font-weight: 700;
        font-size: 0.82rem;
        color: #0F172A !important;
        background: white;
        padding: 0;
        height: 28px;
    }
    .qty-stepper input:focus { outline: none; }

    /* Cart remove button */
    .cart-remove-btn {
        width: 28px; height: 28px;
        display: flex; align-items: center; justify-content: center;
        border: none;
        background: transparent;
        color: #CBD5E1;
        border-radius: 50%;
        cursor: pointer;
        transition: all 0.15s;
        flex-shrink: 0;
    }
    .cart-remove-btn:hover { background: rgba(150,71,52,0.08); color: #964734; }

    /* Summary panel */
    .checkout-panel {
        background: #F8FAFC;
        border: 1px solid #E2E8F0;
        border-radius: 0.75rem;
        padding: 1rem;
    }
    .total-highlight {
        background: var(--dark);
        color: #fff;
        border-radius: 0.625rem;
        padding: 0.625rem 0.875rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1rem;
    }
    .total-highlight .label { font-size: 0.78rem; opacity: 0.7; }
    .total-highlight .amount { font-size: 1.1rem; font-weight: 800; letter-spacing: -0.02em; }

    /* Change display */
    .change-display {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 0.5rem 0.75rem;
        border-radius: 0.5rem;
        background: rgba(22,163,74,0.08);
        margin-top: 0.75rem;
        font-size: 0.85rem;
    }
    .change-display .change-label { color: #15803d; font-weight: 600; }
    .change-display .change-amount { color: #15803d; font-weight: 800; }

    /* Empty cart state */
    .empty-cart {
        text-align: center;
        padding: 2rem 1rem;
        color: #94A3B8;
    }
    .empty-cart i { font-size: 2rem; margin-bottom: 0.5rem; display: block; }
</style>
@endpush

@section('content')
<div class="row g-3">

    {{-- Kolom Kiri: Daftar Produk --}}
    <div class="col-lg-7">
        <div class="card">
            <div class="card-header d-flex align-items-center justify-content-between">
                <span class="fw-bold text-dark" style="font-size:0.875rem;">Pilih Produk</span>
                <span class="badge bg-secondary-subtle" id="product-count">{{ $products->total() }} Produk</span>
            </div>
            <div class="card-body">
                {{-- Form Search --}}
                <form method="GET" action="{{ route('kasir.pos.index') }}" class="mb-3">
                    <div class="input-group">
                        <span class="input-group-text" style="background:#F8FAFC; border-color:#E2E8F0;">
                            <i class="bi bi-search text-muted" style="font-size:0.875rem;"></i>
                        </span>
                        <input
                            id="pos-search"
                            type="text"
                            name="search"
                            class="form-control"
                            style="border-left: none; background:#F8FAFC;"
                            placeholder="Cari produk atau kategori..."
                            value="{{ $search }}"
                            autocomplete="off"
                        >
                        @if ($search)
                            <a href="{{ route('kasir.pos.index') }}" class="btn btn-outline-secondary d-flex align-items-center">
                                <i class="bi bi-x-lg"></i>
                            </a>
                        @endif
                        <button class="btn btn-primary fw-semibold" type="submit">Cari</button>
                    </div>
                </form>

                {{-- Validasi Error --}}
                @if ($errors->any())
                    <div class="alert alert-danger mb-3 py-2 small" role="alert">
                        @foreach ($errors->all() as $error)
                            <div>{{ $error }}</div>
                        @endforeach
                    </div>
                @endif

                {{-- Grid Produk --}}
                @if ($products->isEmpty())
                    <div class="text-center text-muted py-5">
                        <i class="bi bi-search" style="font-size:1.75rem; display:block; margin-bottom:0.5rem;"></i>
                        <p class="small mb-0">{{ $search ? "Produk \"$search\" tidak ditemukan." : 'Belum ada produk aktif.' }}</p>
                    </div>
                @else
                    <div class="row row-cols-2 row-cols-md-3 g-2">
                        @foreach ($products as $product)
                            <div class="col">
                                @if($product->stock > 0)
                                    {{-- Klik kartu langsung tambah qty=1 ke keranjang --}}
                                    <form method="POST" action="{{ route('kasir.pos.cart.add', $product) }}" class="h-100 m-0">
                                        @csrf
                                        <input type="hidden" name="quantity" value="1">
                                        <button type="submit" class="d-block w-100 h-100 text-start p-0 border-0 bg-transparent">
                                            <div class="card h-100 pos-product-card">
                                                @if ($product->image_url)
                                                    <img src="{{ $product->image_url }}" class="pos-product-img w-100" alt="{{ $product->name }}">
                                                @else
                                                    <div class="pos-product-placeholder w-100">
                                                        <i class="bi bi-cup-hot"></i>
                                                    </div>
                                                @endif
                                                <div class="card-body p-2">
                                                    <div class="text-muted d-block mb-0" style="font-size:0.68rem;">{{ $product->category->name }}</div>
                                                    <div class="fw-bold text-dark text-truncate" style="font-size:0.8rem;" title="{{ $product->name }}">{{ $product->name }}</div>
                                                    <div class="text-primary fw-bold mt-1" style="font-size:0.82rem;">Rp {{ number_format($product->selling_price, 0, ',', '.') }}</div>
                                                    <div class="text-muted" style="font-size:0.68rem;">Stok: {{ $product->stock }}</div>
                                                </div>
                                            </div>
                                        </button>
                                    </form>
                                @else
                                    <div class="card h-100 pos-product-card out-of-stock">
                                        @if ($product->image_url)
                                            <img src="{{ $product->image_url }}" class="pos-product-img w-100" alt="{{ $product->name }}">
                                        @else
                                            <div class="pos-product-placeholder w-100">
                                                <i class="bi bi-cup-hot"></i>
                                            </div>
                                        @endif
                                        <div class="card-body p-2">
                                            <div class="text-muted d-block mb-0" style="font-size:0.68rem;">{{ $product->category->name }}</div>
                                            <div class="fw-bold text-dark text-truncate" style="font-size:0.8rem;">{{ $product->name }}</div>
                                            <div class="text-primary fw-bold mt-1" style="font-size:0.82rem;">Rp {{ number_format($product->selling_price, 0, ',', '.') }}</div>
                                            <span class="badge bg-danger-subtle" style="font-size:0.65rem;">Habis</span>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>

                    {{-- Pagination --}}
                    <div class="mt-3 d-flex justify-content-center">
                        {{ $products->links() }}
                    </div>
                @endif

            </div>
        </div>
    </div>

    {{-- Kolom Kanan: Keranjang & Checkout --}}
    <div class="col-lg-5">
        <div class="card sticky-top" style="top: 80px; z-index: 80;">
            <div class="card-header d-flex align-items-center justify-content-between">
                <span class="fw-bold text-dark" style="font-size:0.875rem;">Keranjang Belanja</span>
                <span class="badge bg-secondary-subtle" id="cart-count">{{ $cartItems->count() }} item</span>
            </div>
            <div class="card-body" style="padding: 1rem;">

                {{-- Cart Items --}}
                <div id="cart-items-wrapper" style="max-height: 320px; overflow-y: auto; margin: 0 -0.25rem;">
                    @if ($cartItems->isEmpty())
                        <div class="empty-cart" id="cart-empty-state">
                            <i class="bi bi-cart3"></i>
                            <span class="small">Klik produk untuk menambahkan ke keranjang</span>
                        </div>
                    @else
                        @foreach ($cartItems as $item)
                            <div class="cart-item d-flex align-items-center gap-2"
                                 data-product-id="{{ $item['product']->id }}"
                                 data-price="{{ (int)$item['price'] }}">
                                <div class="flex-grow-1 min-width-0">
                                    <div class="fw-semibold text-dark text-truncate" style="font-size:0.82rem;">{{ $item['product']->name }}</div>
                                    <div class="text-muted" style="font-size:0.72rem;">Rp {{ number_format($item['price'], 0, ',', '.') }} / pcs</div>
                                    <div class="text-primary fw-bold item-subtotal" style="font-size:0.82rem;" data-price="{{ (int)$item['price'] }}">
                                        Rp {{ number_format($item['subtotal'], 0, ',', '.') }}
                                    </div>
                                </div>
                                <div class="qty-stepper">
                                    <button type="button" class="qty-minus" data-max="{{ $item['product']->stock }}">−</button>
                                    <input
                                        type="number"
                                        class="qty-input"
                                        value="{{ $item['quantity'] }}"
                                        min="1"
                                        max="{{ $item['product']->stock }}"
                                        data-product-id="{{ $item['product']->id }}"
                                        data-update-url="{{ route('kasir.pos.cart.update', $item['product']->id) }}"
                                        data-remove-url="{{ route('kasir.pos.cart.remove', $item['product']->id) }}"
                                    >
                                    <button type="button" class="qty-plus" data-max="{{ $item['product']->stock }}">+</button>
                                </div>
                                <form method="POST" action="{{ route('kasir.pos.cart.remove', $item['product']->id) }}" class="m-0 remove-form">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="cart-remove-btn" title="Hapus item">
                                        <i class="bi bi-x-lg" style="font-size:0.7rem;"></i>
                                    </button>
                                </form>
                            </div>
                        @endforeach
                    @endif
                </div>

                {{-- Checkout Panel --}}
                <div class="checkout-panel mt-3">
                    <div class="total-highlight">
                        <span class="label">TOTAL BELANJA</span>
                        <span class="amount" id="display-total">Rp {{ number_format($cartSubtotal, 0, ',', '.') }}</span>
                    </div>

                    @if ($cartItems->isNotEmpty())
                        <form method="POST" action="{{ route('kasir.pos.checkout') }}" id="checkout-form">
                            @csrf

                            <div class="mb-2">
                                <label for="discount" class="form-label small fw-semibold text-dark mb-1">Diskon (Rp)</label>
                                <input
                                    type="number"
                                    id="discount"
                                    name="discount"
                                    class="form-control form-control-sm @error('discount') is-invalid @enderror"
                                    min="0"
                                    step="1"
                                    value="{{ old('discount', 0) }}"
                                    placeholder="0"
                                >
                                @error('discount')
                                    <div class="invalid-feedback small">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="cash_paid" class="form-label small fw-semibold text-dark mb-1">
                                    Uang Diterima (Rp) <span class="text-danger">*</span>
                                </label>
                                <input
                                    type="number"
                                    id="cash_paid"
                                    name="cash_paid"
                                    class="form-control form-control-sm @error('cash_paid') is-invalid @enderror"
                                    min="0"
                                    step="1"
                                    value="{{ old('cash_paid') }}"
                                    placeholder="Masukkan nominal uang..."
                                    required
                                >
                                @error('cash_paid')
                                    <div class="invalid-feedback small">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Kembalian live preview --}}
                            <div class="change-display" id="change-display" style="display:none !important;">
                                <span class="change-label">Kembalian</span>
                                <span class="change-amount" id="change-amount">Rp 0</span>
                            </div>

                            @error('cart')
                                <div class="alert alert-danger py-2 small mt-2">{{ $message }}</div>
                            @enderror

                            <button
                                type="submit"
                                id="btn-checkout"
                                class="btn btn-primary w-100 fw-bold mt-3"
                                style="padding: 0.7rem;"
                            >
                                <i class="bi bi-check-circle me-1"></i> Proses Checkout
                            </button>
                        </form>
                    @else
                        <div class="text-center text-muted small py-2">Tambahkan produk untuk checkout</div>
                    @endif
                </div>

            </div>
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script>
(function () {
    'use strict';

    // ── Helpers ────────────────────────────────────────────────────────────
    const fmt = n => 'Rp ' + Math.round(n).toLocaleString('id-ID');
    const csrf = document.querySelector('meta[name="csrf-token"]').content;

    // ── Qty stepper logic ──────────────────────────────────────────────────
    function debounce(fn, delay) {
        let t;
        return function (...args) { clearTimeout(t); t = setTimeout(() => fn.apply(this, args), delay); };
    }

    function recalcTotals() {
        let total = 0;
        document.querySelectorAll('.cart-item').forEach(item => {
            const price = parseInt(item.dataset.price) || 0;
            const qtyInput = item.querySelector('.qty-input');
            const qty = parseInt(qtyInput?.value) || 0;
            const subtotalEl = item.querySelector('.item-subtotal');
            if (subtotalEl) subtotalEl.textContent = fmt(price * qty);
            total += price * qty;
        });

        const totalEl = document.getElementById('display-total');
        if (totalEl) totalEl.textContent = fmt(total);

        // Update kembalian
        updateChange();
    }

    function updateChange() {
        const totalEl = document.getElementById('display-total');
        const cashInput = document.getElementById('cash_paid');
        const discountInput = document.getElementById('discount');
        const changeDisplay = document.getElementById('change-display');
        const changeAmount = document.getElementById('change-amount');

        if (!totalEl || !cashInput || !changeDisplay || !changeAmount) return;

        // Parse total from display (strip non-digits)
        const totalText = totalEl.textContent.replace(/[^0-9]/g, '');
        const total = parseInt(totalText) || 0;
        const discount = parseInt(discountInput?.value) || 0;
        const finalTotal = Math.max(0, total - discount);
        const cash = parseInt(cashInput.value) || 0;
        const change = cash - finalTotal;

        if (cash > 0 && change >= 0) {
            changeDisplay.style.setProperty('display', 'flex', 'important');
            changeAmount.textContent = fmt(change);
        } else {
            changeDisplay.style.setProperty('display', 'none', 'important');
        }
    }

    const debouncedUpdate = debounce(function (input) {
        const productId = input.dataset.productId;
        const updateUrl = input.dataset.updateUrl;
        const qty = parseInt(input.value) || 1;

        // Update subtotal immediately
        recalcTotals();

        // Submit update to server
        const fd = new FormData();
        fd.append('_token', csrf);
        fd.append('_method', 'PATCH');
        fd.append('quantity', qty);

        fetch(updateUrl, { method: 'POST', body: fd })
            .catch(err => console.error('Cart update failed:', err));
    }, 400);

    // Attach events to stepper buttons and inputs
    document.querySelectorAll('.cart-item').forEach(item => {
        const input = item.querySelector('.qty-input');
        const minusBtn = item.querySelector('.qty-minus');
        const plusBtn = item.querySelector('.qty-plus');

        if (!input) return;

        const max = parseInt(input.max) || 999;

        minusBtn?.addEventListener('click', () => {
            const val = parseInt(input.value) || 1;
            if (val > 1) {
                input.value = val - 1;
                debouncedUpdate(input);
            } else {
                // qty 0: trigger remove
                item.querySelector('.remove-form')?.submit();
            }
        });

        plusBtn?.addEventListener('click', () => {
            const val = parseInt(input.value) || 1;
            if (val < max) {
                input.value = val + 1;
                debouncedUpdate(input);
            }
        });

        input.addEventListener('change', () => {
            let val = parseInt(input.value) || 1;
            val = Math.max(1, Math.min(val, max));
            input.value = val;
            debouncedUpdate(input);
        });
    });

    // ── Live change preview ────────────────────────────────────────────────
    const cashInput = document.getElementById('cash_paid');
    const discountInput = document.getElementById('discount');
    if (cashInput) cashInput.addEventListener('input', updateChange);
    if (discountInput) discountInput.addEventListener('input', updateChange);

    // Initial totals
    recalcTotals();

})();
</script>
@endpush
