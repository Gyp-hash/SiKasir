@extends('layouts.app', ['title' => 'Dashboard Owner - SiKasir Angkringan'])

@section('content')
    <div class="card shadow-sm">
        <div class="card-body">
            <h1 class="h3">Dashboard Owner</h1>
            <p class="text-muted mb-4">Selamat datang, {{ auth()->user()->name }}. Anda login sebagai owner.</p>

            <div class="row g-3">
                <div class="col-md-4">
                    <div class="border rounded p-3 bg-light">
                        <div class="text-muted">Omzet Hari Ini</div>
                        <div class="fs-3 fw-bold">Rp0</div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="border rounded p-3 bg-light">
                        <div class="text-muted">Total Laba</div>
                        <div class="fs-3 fw-bold">Rp0</div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="border rounded p-3 bg-light">
                        <div class="text-muted">Stok Menipis</div>
                        <div class="fs-3 fw-bold">0 Produk</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
