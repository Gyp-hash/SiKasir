@extends('layouts.app', ['title' => 'Kategori - Sikasir Angkringan'])

@section('content')
<div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
    <div>
        <h1 class="h4 mb-1 fw-bold">Kategori</h1>
        <p class="text-muted small mb-0">Kelola kategori produk angkringan.</p>
    </div>
    <a class="btn btn-primary fw-semibold" href="{{ route('owner.categories.create') }}">Tambah Kategori</a>
</div>

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span class="fw-semibold text-dark" style="font-size: 0.875rem;">Daftar Kategori</span>
        <span class="badge bg-secondary-subtle">{{ $categories->total() }} kategori</span>
    </div>
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead>
                <tr>
                    <th>Nama</th>
                    <th>Deskripsi</th>
                    <th class="text-center">Jumlah Produk</th>
                    <th class="text-end">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($categories as $category)
                    <tr>
                        <td class="fw-semibold">{{ $category->name }}</td>
                        <td class="text-muted small">{{ $category->description ?: '—' }}</td>
                        <td class="text-center">
                            <span class="badge bg-secondary-subtle">{{ $category->products_count }}</span>
                        </td>
                        <td class="text-end">
                            <a class="btn btn-sm btn-action" href="{{ route('owner.categories.edit', $category) }}">Edit</a>
                            <form class="d-inline" method="POST" action="{{ route('owner.categories.destroy', $category) }}" onsubmit="return confirm('Hapus kategori ini?')">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger" type="submit">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td class="text-center text-muted py-5" colspan="4">
                            <p class="mb-2 small">Belum ada kategori.</p>
                            <a href="{{ route('owner.categories.create') }}" class="btn btn-sm btn-primary">Tambah Kategori</a>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if ($categories->hasPages())
        <div class="card-footer d-flex flex-column flex-sm-row justify-content-between align-items-center gap-2">
            <small class="text-muted">Menampilkan {{ $categories->firstItem() }}–{{ $categories->lastItem() }} dari {{ $categories->total() }}</small>
            <div>{{ $categories->links() }}</div>
        </div>
    @endif
</div>
@endsection
