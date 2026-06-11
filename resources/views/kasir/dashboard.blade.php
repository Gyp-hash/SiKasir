@extends('layouts.app', ['title' => 'Dashboard Kasir - SiKasir Angkringan'])

@section('content')
    <div class="card shadow-sm">
        <div class="card-body">
            <h1 class="h3">Dashboard Kasir</h1>
            <p class="text-muted mb-4">Selamat datang, {{ auth()->user()->name }}. Anda login sebagai kasir.</p>

            <div class="row g-3">
                <div class="col-md-4">
                    <div class="border rounded p-3 bg-light">
                        <div class="text-muted">Transaksi Hari Ini</div>
                        <div class="fs-3 fw-bold">0</div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="border rounded p-3 bg-light">
                        <div class="text-muted">Omzet Hari Ini</div>
                        <div class="fs-3 fw-bold">Rp0</div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="border rounded p-3 bg-light">
                        <div class="text-muted">Produk Terjual</div>
                        <div class="fs-3 fw-bold">0</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
