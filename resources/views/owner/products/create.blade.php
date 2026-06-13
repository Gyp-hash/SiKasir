@extends('layouts.app', ['title' => 'Tambah Produk - Sikasir Angkringan'])

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">

        <div class="d-flex justify-content-between align-items-center mb-4">
            <a href="{{ route('owner.products.index') }}" class="btn btn-outline-secondary d-flex align-items-center gap-2 fw-semibold">
                <i class="bi bi-arrow-left"></i> Kembali
            </a>
            <h1 class="h5 mb-0 fw-bold text-dark">Tambah Produk</h1>
        </div>

        <div class="card">
            <div class="card-body">
                @if ($errors->any())
                    <div class="alert alert-danger mb-4" role="alert">
                        <ul class="mb-0 small">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('owner.products.store') }}" enctype="multipart/form-data">
                    @include('owner.products.form')

                    <div class="d-flex gap-2 mt-2">
                        <button class="btn btn-primary fw-semibold px-4" type="submit">Simpan</button>
                        <a class="btn btn-outline-secondary fw-semibold" href="{{ route('owner.products.index') }}">Batal</a>
                    </div>
                </form>
            </div>
        </div>

    </div>
</div>
@endsection
