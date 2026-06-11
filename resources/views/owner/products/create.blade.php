@extends('layouts.app', ['title' => 'Tambah Produk - SiKasir Angkringan'])

@section('content')
    <div class="card shadow-sm">
        <div class="card-body">
            <h1 class="h3 mb-3">Tambah Produk</h1>

            <form method="POST" action="{{ route('owner.products.store') }}" enctype="multipart/form-data">
                @include('owner.products.form')

                <div class="d-flex gap-2">
                    <button class="btn btn-primary" type="submit">Simpan</button>
                    <a class="btn btn-outline-secondary" href="{{ route('owner.products.index') }}">Batal</a>
                </div>
            </form>
        </div>
    </div>
@endsection
