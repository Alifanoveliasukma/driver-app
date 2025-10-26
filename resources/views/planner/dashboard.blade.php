@extends('layouts.template-planner')

@section('title', 'Dashboard Planner')

@section('content')
<div class="container">
    <h4 class="mb-4 fw-bold text-primary">Dashboard Planner</h4>
    <p class="text-muted mb-4">Kelola data driver dan pantau histori pengiriman</p>

    <div class="row g-4">
        <!-- Card Tambah Driver -->
        <div class="col-md-6">
            <a href="{{ route('driver.index') }}" class="text-decoration-none">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-body text-center py-5">
                        <i class="bi bi-person-plus fs-1 text-primary mb-3"></i>
                        <h5 class="fw-bold text-dark">Tambah Driver</h5>
                        <p class="text-muted mb-0">Tambahkan atau ubah data driver</p>
                    </div>
                </div>
            </a>
        </div>

        <!-- Card Transport Tracking -->
        <div class="col-md-6">
            <a href="{{ route('histori.planner') }}" class="text-decoration-none">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-body text-center py-5">
                        <i class="bi bi-clock-history fs-1 text-success mb-3"></i>
                        <h5 class="fw-bold text-dark">Transport Tracking</h5>
                        <p class="text-muted mb-0">Lihat surat jalan dan pengiriman</p>
                    </div>
                </div>
            </a>
        </div>
    </div>
</div>
@endsection
