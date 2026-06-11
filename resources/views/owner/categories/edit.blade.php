@extends('layouts.app', ['title' => 'Edit Kategori - SiKasir Angkringan'])

@section('content')
    <div class="card shadow-sm">
        <div class="card-body">
            <h1 class="h3 mb-3">Edit Kategori</h1>

            <form method="POST" action="{{ route('owner.categories.update', $category) }}">
                @method('PUT')
                @include('owner.categories.form')

                <div class="d-flex gap-2">
                    <button class="btn btn-primary" type="submit">Simpan Perubahan</button>
                    <a class="btn btn-outline-secondary" href="{{ route('owner.categories.index') }}">Batal</a>
                </div>
            </form>
        </div>
    </div>
@endsection
