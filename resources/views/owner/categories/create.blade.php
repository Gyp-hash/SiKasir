@extends('layouts.app', ['title' => 'Tambah Kategori - SiKasir Angkringan'])

@section('content')
    <div class="card shadow-sm">
        <div class="card-body">
            <h1 class="h3 mb-3">Tambah Kategori</h1>

            <form method="POST" action="{{ route('owner.categories.store') }}">
                @include('owner.categories.form')

                <div class="d-flex gap-2">
                    <button class="btn btn-primary" type="submit">Simpan</button>
                    <a class="btn btn-outline-secondary" href="{{ route('owner.categories.index') }}">Batal</a>
                </div>
            </form>
        </div>
    </div>
@endsection
