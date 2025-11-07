@extends('layouts.template-planner')

@section('title', 'Tambah Driver - Step 5')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="d-flex align-items-center justify-content-between mb-4">
                <div class="d-flex align-items-center">
                    <a href="{{ route('driver.create.step.four') }}" class="btn btn-outline-primary me-3" aria-label="Kembali">
                        <i class="bi bi-chevron-left"></i>
                    </a>
                    <h4 class="fw-bold text-primary mb-0">Tambah Driver</h4>
                </div>
                <div class="text-muted small">Step 5 dari 8</div>
            </div>

            <!-- Progress Bar -->
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-body py-3">
                    <div class="progress" style="height: 8px;">
                        <div class="progress-bar bg-primary" role="progressbar" style="width: 62.5%"></div>
                    </div>
                    <div class="d-flex justify-content-between mt-2">
                        <span class="small text-muted">User Management</span>
                        <span class="small text-primary fw-semibold">Bank Account</span>
                        <span class="small text-muted">Business Partner</span>
                        <span class="small text-muted">Driver Specification</span>
                    </div>
                </div>
            </div>

            <form action="{{ route('driver.create.step.five.post') }}" method="POST">
                @csrf
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0 fw-semibold">Step 5: Pengaturan Akun Bank</h5>
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
                            <label for="bank_account_id" class="form-label">Pilih Akun Bank <span class="text-danger">*</span></label>
                            <select class="form-select @error('bank_account_id') is-invalid @enderror" 
                                    id="bank_account_id" name="bank_account_id" required>
                                <option value="">-- Pilih Akun Bank --</option>
                                <option value="bca_001" {{ old('bank_account_id', session('driver.bank_account_id') ?? '') == 'bca_001' ? 'selected' : '' }}>BCA - 1234567890</option>
                                <option value="bni_001" {{ old('bank_account_id', session('driver.bank_account_id') ?? '') == 'bni_001' ? 'selected' : '' }}>BNI - 0987654321</option>
                                <option value="mandiri_001" {{ old('bank_account_id', session('driver.bank_account_id') ?? '') == 'mandiri_001' ? 'selected' : '' }}>Mandiri - 5678901234</option>
                                <option value="bri_001" {{ old('bank_account_id', session('driver.bank_account_id') ?? '') == 'bri_001' ? 'selected' : '' }}>BRI - 4321098765</option>
                            </select>
                            @error('bank_account_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="alert alert-info small">
                            <i class="bi bi-info-circle me-2"></i>
                            Akun bank ini akan digunakan untuk transaksi keuangan driver seperti pembayaran dan transfer.
                        </div>
                    </div>

                    <div class="card-footer d-flex justify-content-between">
                        <a href="{{ route('driver.create.step.four') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-arrow-left-circle me-1"></i> Kembali
                        </a>
                        <button type="submit" class="btn btn-primary">
                            Lanjut ke Step 6 <i class="bi bi-arrow-right-circle ms-2"></i>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection