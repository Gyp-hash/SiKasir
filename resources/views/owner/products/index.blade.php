@extends('layouts.app', ['title' => 'Produk - SiKasir Angkringan'])

@section('content')
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-3">
        <div>
            <h1 class="h3 mb-1">Produk</h1>
            <p class="text-muted mb-0">Kelola produk, stok, harga modal, dan harga jual.</p>
        </div>
        <a class="btn btn-primary" href="{{ route('owner.products.create') }}">Tambah Produk</a>
    </div>

    <div class="card shadow-sm mb-3">
        <div class="card-body">
            <form class="row g-2" method="GET" action="{{ route('owner.products.index') }}">
                <div class="col-md-10">
                    <input class="form-control" name="search" type="search" value="{{ $search }}" placeholder="Cari nama produk atau kategori">
                </div>
                <div class="col-md-2 d-grid">
                    <button class="btn btn-outline-primary" type="submit">Cari</button>
                </div>
            </form>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Produk</th>
                        <th>Kategori</th>
                        <th class="text-end">Harga Jual</th>
                        <th class="text-end">Harga Modal</th>
                        <th class="text-center">Stok</th>
                        <th class="text-center">Minimum</th>
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
                                        <img class="rounded border" src="{{ $product->image_url }}" alt="{{ $product->name }}" width="56" height="56" style="object-fit: cover;">
                                    @else
                                        <div class="rounded border bg-light d-flex align-items-center justify-content-center text-muted" style="width: 56px; height: 56px;">-</div>
                                    @endif
                                    <div class="fw-semibold">{{ $product->name }}</div>
                                </div>
                            </td>
                            <td>{{ $product->category->name }}</td>
                            <td class="text-end">Rp{{ number_format((float) $product->selling_price, 0, ',', '.') }}</td>
                            <td class="text-end">Rp{{ number_format((float) $product->capital_price, 0, ',', '.') }}</td>
                            <td class="text-center">{{ $product->stock }}</td>
                            <td class="text-center">{{ $product->minimum_stock }}</td>
                            <td>
                                <span class="badge {{ $product->status === 'active' ? 'text-bg-success' : 'text-bg-secondary' }}">
                                    {{ $product->status === 'active' ? 'Aktif' : 'Nonaktif' }}
                                </span>
                            </td>
                            <td class="text-end">
                                <a class="btn btn-sm btn-outline-primary" href="{{ route('owner.products.edit', $product) }}">Edit</a>
                                <form class="d-inline" method="POST" action="{{ route('owner.products.destroy', $product) }}" onsubmit="return confirm('Hapus produk ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger" type="submit">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td class="text-center text-muted py-4" colspan="8">Produk tidak ditemukan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-3">
        {{ $products->links() }}
    </div>
@endsection
