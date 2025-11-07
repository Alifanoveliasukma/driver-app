@extends('layouts.template-planner')

@section('title', 'Tambah Driver - Step 6')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="d-flex align-items-center justify-content-between mb-4">
                <div class="d-flex align-items-center">
                    <a href="{{ route('driver.create.step.five') }}" class="btn btn-outline-primary me-3" aria-label="Kembali">
                        <i class="bi bi-chevron-left"></i>
                    </a>
                    <h4 class="fw-bold text-primary mb-0">Tambah Driver</h4>
                </div>
                <div class="text-muted small">Step 6 dari 8</div>
            </div>

            <!-- Progress Bar -->
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-body py-3">
                    <div class="progress" style="height: 8px;">
                        <div class="progress-bar bg-warning" role="progressbar" style="width: 75%"></div>
                    </div>
                    <div class="d-flex justify-content-between mt-2">
                        <span class="small text-muted">User Management</span>
                        <span class="small text-warning fw-semibold">Business Partner</span>
                        <span class="small text-muted">Driver Specification</span>
                    </div>
                </div>
            </div>

            <form action="{{ route('driver.create.step.six.post') }}" method="POST">
                @csrf
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-warning text-dark">
                        <h5 class="mb-0 fw-semibold">Step 6: Informasi Business Partner</h5>
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
                                <label for="bp_search_key" class="form-label">Kode Referensi</label>
                                <input type="text" class="form-control @error('bp_search_key') is-invalid @enderror" 
                                       id="bp_search_key" name="bp_search_key" 
                                       value="{{ old('bp_search_key', session('driver.bp_search_key') ?? 'DRV' . date('YmdHis')) }}" 
                                       placeholder="Auto-generate kode referensi">
                                @error('bp_search_key')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">Kode referensi akan auto-generate</div>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="bp_name" class="form-label">Nama Business Partner <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('bp_name') is-invalid @enderror" 
                                       id="bp_name" name="bp_name" 
                                       value="{{ old('bp_name', session('driver.bp_name') ?? '') }}" 
                                       required placeholder="Nama + Jungrak">
                                @error('bp_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="credit_status" class="form-label">Status Kredit <span class="text-danger">*</span></label>
                                <select class="form-select @error('credit_status') is-invalid @enderror" 
                                        id="credit_status" name="credit_status" required>
                                    <option value="">-- Pilih Status Kredit --</option>
                                    <option value="credit_hold" {{ old('credit_status', session('driver.credit_status') ?? '') == 'credit_hold' ? 'selected' : '' }}>Credit Hold</option>
                                    <option value="credit_ok" {{ old('credit_status', session('driver.credit_status') ?? '') == 'credit_ok' ? 'selected' : '' }}>Credit OK</option>
                                    <option value="credit_stop" {{ old('credit_status', session('driver.credit_status') ?? '') == 'credit_stop' ? 'selected' : '' }}>Credit Stop</option>
                                    <option value="credit_watch" {{ old('credit_status', session('driver.credit_status') ?? '') == 'credit_watch' ? 'selected' : '' }}>Credit Watch</option>
                                    <option value="no_credit_check" {{ old('credit_status', session('driver.credit_status') ?? '') == 'no_credit_check' ? 'selected' : '' }}>No Credit Check</option>
                                </select>
                                @error('credit_status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="business_partner_group" class="form-label">Grup Partner <span class="text-danger">*</span></label>
                                <select class="form-select @error('business_partner_group') is-invalid @enderror" 
                                        id="business_partner_group" name="business_partner_group" required>
                                    <option value="">-- Pilih Grup Partner --</option>
                                    <option value="driver_group" {{ old('business_partner_group', session('driver.business_partner_group') ?? '') == 'driver_group' ? 'selected' : '' }}>Driver Group</option>
                                    <option value="regular" {{ old('business_partner_group', session('driver.business_partner_group') ?? '') == 'regular' ? 'selected' : '' }}>Regular</option>
                                    <option value="premium" {{ old('business_partner_group', session('driver.business_partner_group') ?? '') == 'premium' ? 'selected' : '' }}>Premium</option>
                                </select>
                                @error('business_partner_group')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="alert alert-info small">
                            <i class="bi bi-info-circle me-2"></i>
                            Business partner akan terhubung dengan data driver untuk keperluan transaksi dan pembayaran.
                        </div>
                    </div>

                    <div class="card-footer d-flex justify-content-between">
                        <a href="{{ route('driver.create.step.five') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-arrow-left-circle me-1"></i> Kembali
                        </a>
                        <button type="submit" class="btn btn-warning">
                            Lanjut ke Step 7 <i class="bi bi-arrow-right-circle ms-2"></i>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection