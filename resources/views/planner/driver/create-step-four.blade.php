@extends('layouts.template-planner')

@section('title', 'Tambah Driver - Step 4')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="d-flex align-items-center justify-content-between mb-4">
                <div class="d-flex align-items-center">
                    <a href="{{ route('driver.create.step.three') }}" class="btn btn-outline-primary me-3" aria-label="Kembali">
                        <i class="bi bi-chevron-left"></i>
                    </a>
                    <h4 class="fw-bold text-primary mb-0">Tambah Driver</h4>
                </div>
                <div class="text-muted small">Step 4 dari 8</div>
            </div>

            <!-- Progress Bar -->
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-body py-3">
                    <div class="progress" style="height: 8px;">
                        <div class="progress-bar bg-primary" role="progressbar" style="width: 50%"></div>
                    </div>
                    <div class="d-flex justify-content-between mt-2">
                        <span class="small text-muted">User Management</span>
                        <span class="small text-primary fw-semibold">Cashbook</span>
                        <span class="small text-muted">Bank Account</span>
                        <span class="small text-muted">Business Partner</span>
                        <span class="small text-muted">Driver Specification</span>
                    </div>
                </div>
            </div>

            <form action="{{ route('driver.create.step.four.post') }}" method="POST">
                @csrf
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0 fw-semibold">Step 4: Pengaturan Cashbook</h5>
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
                            <label for="cashbox_id" class="form-label">Pilih Cashbox <span class="text-danger">*</span></label>
                            <select class="form-select @error('cashbox_id') is-invalid @enderror" 
                                    id="cashbox_id" name="cashbox_id" required>
                                <option value="">-- Pilih Cashbox --</option>
                                <option value="cashbox_1" {{ old('cashbox_id', session('driver.cashbox_id') ?? '') == 'cashbox_1' ? 'selected' : '' }}>Cashbox Utama</option>
                                <option value="cashbox_2" {{ old('cashbox_id', session('driver.cashbox_id') ?? '') == 'cashbox_2' ? 'selected' : '' }}>Cashbox Operasional</option>
                                <option value="cashbox_3" {{ old('cashbox_id', session('driver.cashbox_id') ?? '') == 'cashbox_3' ? 'selected' : '' }}>Cashbox Driver</option>
                            </select>
                            @error('cashbox_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="alert alert-info small">
                            <i class="bi bi-info-circle me-2"></i>
                            Cashbox digunakan untuk mengelola transaksi kas driver. Pilih cashbox yang sesuai dengan kebutuhan operasional.
                        </div>
                    </div>

                    <div class="card-footer d-flex justify-content-between">
                        <a href="{{ route('driver.create.step.three') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-arrow-left-circle me-1"></i> Kembali
                        </a>
                        <button type="submit" class="btn btn-primary">
                            Lanjut ke Step 5 <i class="bi bi-arrow-right-circle ms-2"></i>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection