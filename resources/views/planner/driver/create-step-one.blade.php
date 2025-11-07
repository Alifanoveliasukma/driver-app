@extends('layouts.template-planner')

@section('title', 'Tambah Driver - Step 1')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="d-flex align-items-center justify-content-between mb-4">
                <div class="d-flex align-items-center">
                    <button class="btn btn-outline-primary me-3" onclick="window.history.back()" aria-label="Kembali">
                        <i class="bi bi-chevron-left"></i>
                    </button>
                    <h4 class="fw-bold text-primary mb-0">Tambah Driver</h4>
                </div>
                <div class="text-muted small">Step 1 dari 8</div>
            </div>

            <!-- Progress Bar -->
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-body py-3">
                    <div class="progress" style="height: 8px;">
                        <div class="progress-bar bg-primary" role="progressbar" style="width: 12.5%"></div>
                    </div>
                    <div class="d-flex justify-content-between mt-2">
                        <span class="small text-primary fw-semibold">Informasi Login</span>
                        <span class="small text-muted">Business Partner</span>
                        <span class="small text-muted">Driver Specification</span>
                    </div>
                </div>
            </div>

            <form action="{{ route('driver.create.step.one.post') }}" method="POST">
                @csrf
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0 fw-semibold">Step 1: Informasi Login Driver</h5>
                    </div>

                    <div class="card-body">
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="name" class="form-label">Nama Lengkap Driver <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                       id="name" name="name" value="{{ old('name', session('driver.name') ?? '') }}" 
                                       required placeholder="Masukkan nama lengkap">
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="search_key" class="form-label">Username / No. Telepon <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('search_key') is-invalid @enderror" 
                                       id="search_key" name="search_key" value="{{ old('search_key', session('driver.search_key') ?? '') }}" 
                                       required placeholder="Username atau nomor telepon">
                                @error('search_key')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="password" class="form-label">Password <span class="text-danger">*</span></label>
                                <input type="password" class="form-control @error('password') is-invalid @enderror" 
                                       id="password" name="password" required placeholder="Masukkan password">
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="password_confirmation" class="form-label">Konfirmasi Password <span class="text-danger">*</span></label>
                                <input type="password" class="form-control" 
                                       id="password_confirmation" name="password_confirmation" 
                                       required placeholder="Konfirmasi password">
                            </div>
                        </div>

                        <div class="alert alert-info small">
                            <i class="bi bi-info-circle me-2"></i>
                            Pastikan informasi login sudah benar. Data ini akan digunakan untuk akses sistem.
                        </div>
                    </div>

                    <div class="card-footer text-end">
                        <button type="submit" class="btn btn-primary">
                            Lanjut ke Step 2 <i class="bi bi-arrow-right-circle ms-2"></i>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection