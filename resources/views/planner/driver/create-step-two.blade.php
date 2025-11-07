@extends('layouts.template-planner')

@section('title', 'Tambah Driver - Step 2')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="d-flex align-items-center justify-content-between mb-4">
                <div class="d-flex align-items-center">
                    <a href="{{ route('driver.create.step.one') }}" class="btn btn-outline-primary me-3" aria-label="Kembali">
                        <i class="bi bi-chevron-left"></i>
                    </a>
                    <h4 class="fw-bold text-primary mb-0">Tambah Driver</h4>
                </div>
                <div class="text-muted small">Step 2 dari 8</div>
            </div>

            <!-- Progress Bar -->
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-body py-3">
                    <div class="progress" style="height: 8px;">
                        <div class="progress-bar bg-primary" role="progressbar" style="width: 25%"></div>
                    </div>
                    <div class="d-flex justify-content-between mt-2">
                        <span class="small text-muted">Informasi Login</span>
                        <span class="small text-primary fw-semibold">Role Pengguna</span>
                        <span class="small text-muted">Akses Organisasi</span>
                        <span class="small text-muted">Business Partner</span>
                        <span class="small text-muted">Driver Specification</span>
                    </div>
                </div>
            </div>

            <form action="{{ route('driver.create.step.two.post') }}" method="POST">
                @csrf
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0 fw-semibold">Step 2: Penentuan Role Pengguna</h5>
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

                        <div class="mb-3">
                            <label for="role_id" class="form-label">Pilih Role <span class="text-danger">*</span></label>
                            <select class="form-select @error('role_id') is-invalid @enderror" 
                                    id="role_id" name="role_id" required>
                                <option value="">-- Pilih Role --</option>
                                <option value="5" {{ old('role_id', session('driver.role_id') ?? '') == '5' ? 'selected' : '' }}>Driver</option>
                                
                            </select>
                            @error('role_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Hidden field untuk driver role -->
                        <input type="hidden" name="auto_role" value="driver">

                        <div class="alert alert-info small">
                            <i class="bi bi-info-circle me-2"></i>
                            Untuk driver, sistem akan otomatis menetapkan role "driver". Pilihan di atas untuk role tambahan.
                        </div>

                        <!-- Preview data dari step sebelumnya -->
                        <div class="card bg-light border-0 mt-4">
                            <div class="card-header bg-transparent">
                                <h6 class="mb-0 fw-semibold">Data dari Step 1:</h6>
                            </div>
                            <div class="card-body small">
                                @php $driver = session('driver'); @endphp
                                @if($driver)
                                    <div class="row">
                                        <div class="col-md-6">
                                            <strong>Nama:</strong> {{ $driver['name'] ?? '-' }}
                                        </div>
                                        <div class="col-md-6">
                                            <strong>Username/Telepon:</strong> {{ $driver['search_key'] ?? '-' }}
                                        </div>
                                    </div>
                                @else
                                    <div class="text-warning">Tidak ada data dari step sebelumnya</div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="card-footer d-flex justify-content-between">
                        <a href="{{ route('driver.create.step.one') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-arrow-left-circle me-1"></i> Kembali
                        </a>
                        <button type="submit" class="btn btn-primary">
                            Lanjut ke Step 3 <i class="bi bi-arrow-right-circle ms-2"></i>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection