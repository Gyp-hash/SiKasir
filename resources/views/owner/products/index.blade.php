@extends('layouts.app', ['title' => 'Produk - Sikasir Angkringan'])

@section('content')
<div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
    <div>
        <h1 class="h4 mb-1 fw-bold">Produk</h1>
        <p class="text-muted small mb-0">Kelola produk, stok, harga modal, dan harga jual.</p>
    </div>
    <a class="btn btn-primary fw-semibold" href="{{ route('owner.products.create') }}">Tambah Produk</a>
</div>

{{-- Filter --}}
<div class="card mb-4">
    <div class="card-body">
        <form class="row g-2 align-items-center" method="GET" action="{{ route('owner.products.index') }}">
            <div class="col">
                <input class="form-control" name="search" type="search" value="{{ $search }}" placeholder="Cari nama produk atau kategori...">
            </div>
            <div class="col-auto">
                <button class="btn btn-primary fw-semibold" type="submit">Cari</button>
                @if ($search)
                    <a href="{{ route('owner.products.index') }}" class="btn btn-outline-secondary fw-semibold">Reset</a>
                @endif
            </div>
        </form>
    </div>
</div>

{{-- Table --}}
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span class="fw-semibold text-dark" style="font-size: 0.875rem;">Daftar Produk</span>
        <span class="badge bg-secondary-subtle">{{ $products->total() }} produk</span>
    </div>
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead>
                <tr>
                    <th>Produk</th>
                    <th>Kategori</th>
                    <th class="text-end">Harga Jual</th>
                    <th class="text-end">Harga Modal</th>
                    <th class="text-center">Stok</th>
                    <th class="text-center">Min.</th>
                    <th>Status</th>
                    <th class="text-end">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($products as $product)
                    <tr>
                        <td>
                            <div class="d-flex align-items-center gap-3">
                                @if ($product->image_url)
                                    <img class="rounded" src="{{ $product->image_url }}" alt="{{ $product->name }}" width="44" height="44" style="object-fit: cover; border: 1px solid var(--border-color);">
                                @else
                                    <div class="rounded d-flex align-items-center justify-content-center text-muted" style="width: 44px; height: 44px; background: #F8FAFC; border: 1px solid var(--border-color); font-size: 1rem;">
                                        <i class="bi bi-image"></i>
                                    </div>
                                @endif
                                <div class="fw-semibold text-dark small">{{ $product->name }}</div>
                            </div>
                        </td>
                        <td class="small text-muted">{{ $product->category->name }}</td>
                        <td class="text-end fw-semibold text-dark small">Rp{{ number_format((float) $product->selling_price, 0, ',', '.') }}</td>
                        <td class="text-end text-muted small">Rp{{ number_format((float) $product->capital_price, 0, ',', '.') }}</td>
                        <td class="text-center">
                            <span class="{{ $product->stock <= $product->minimum_stock ? 'text-danger fw-bold' : 'text-dark' }} small">{{ $product->stock }}</span>
                        </td>
                        <td class="text-center text-muted small">{{ $product->minimum_stock }}</td>
                        <td>
                            @if ($product->status === 'active')
                                <span class="badge bg-success-subtle">Aktif</span>
                            @else
                                <span class="badge bg-secondary-subtle">Nonaktif</span>
                            @endif
                        </td>
                        <td class="text-end">
                            <a class="btn btn-sm btn-action" href="{{ route('owner.products.edit', $product) }}">Edit</a>
                            <form class="d-inline" method="POST" action="{{ route('owner.products.destroy', $product) }}" onsubmit="return confirm('Hapus produk ini?')">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger" type="submit">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td class="text-center text-muted py-5" colspan="8">
                            <p class="small mb-2">Produk tidak ditemukan.</p>
                            @if ($search)
                                <a href="{{ route('owner.products.index') }}" class="btn btn-sm btn-outline-secondary">Lihat Semua</a>
                            @else
                                <a href="{{ route('owner.products.create') }}" class="btn btn-sm btn-primary">Tambah Produk</a>
                            @endif
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if ($products->hasPages())
        <div class="card-footer d-flex flex-column flex-sm-row justify-content-between align-items-center gap-2">
            <small class="text-muted">Menampilkan {{ $products->firstItem() }}–{{ $products->lastItem() }} dari {{ $products->total() }} produk</small>
            <div>{{ $products->links() }}</div>
        </div>
    @endif
</div>
@endsection
