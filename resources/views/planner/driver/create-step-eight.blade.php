@extends('layouts.template-planner')

@section('title', 'Tambah Driver - Step 8')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="d-flex align-items-center justify-content-between mb-4">
                <div class="d-flex align-items-center">
                    <a href="{{ route('driver.create.step.seven') }}" class="btn btn-outline-primary me-3" aria-label="Kembali">
                        <i class="bi bi-chevron-left"></i>
                    </a>
                    <h4 class="fw-bold text-primary mb-0">Tambah Driver</h4>
                </div>
                <div class="text-muted small">Step 8 dari 8</div>
            </div>

            <!-- Progress Bar -->
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-body py-3">
                    <div class="progress" style="height: 8px;">
                        <div class="progress-bar bg-success" role="progressbar" style="width: 100%"></div>
                    </div>
                    <div class="d-flex justify-content-between mt-2">
                        <span class="small text-muted">User Management</span>
                        <span class="small text-muted">Business Partner</span>
                        <span class="small text-success fw-semibold">Driver Specification</span>
                    </div>
                </div>
            </div>

            <form action="{{ route('driver.create.step.eight.post') }}" method="POST">
                @csrf
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-success text-white">
                        <h5 class="mb-0 fw-semibold">Step 8: Detail Informasi Driver</h5>
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

                        <!-- Hidden fields untuk auto-match dengan BPartner -->
                        <input type="hidden" name="auto_search_key" value="{{ session('driver.bp_search_key') ?? '' }}">
                        <input type="hidden" name="auto_name" value="{{ session('driver.bp_name') ?? '' }}">

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="driver_search_key" class="form-label">Kode Referensi Driver</label>
                                <input type="text" class="form-control" 
                                       id="driver_search_key" name="driver_search_key" 
                                       value="{{ old('driver_search_key', session('driver.driver_search_key') ?? session('driver.bp_search_key') ?? '') }}" 
                                       readonly>
                                <div class="form-text">Auto-match dengan Business Partner</div>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="driver_name" class="form-label">Nama Driver <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('driver_name') is-invalid @enderror" 
                                       id="driver_name" name="driver_name" 
                                       value="{{ old('driver_name', session('driver.driver_name') ?? session('driver.name') ?? '') }}" 
                                       required placeholder="Nama driver">
                                @error('driver_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">Bisa auto-fill dari user contact</div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="driver_status" class="form-label">Status Driver <span class="text-danger">*</span></label>
                                <select class="form-select @error('driver_status') is-invalid @enderror" 
                                        id="driver_status" name="driver_status" required>
                                    <option value="">-- Pilih Status Driver --</option>
                                    <option value="active" {{ old('driver_status', session('driver.driver_status') ?? '') == 'active' ? 'selected' : '' }}>Active</option>
                                    <option value="inactive" {{ old('driver_status', session('driver.driver_status') ?? '') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                    <option value="suspended" {{ old('driver_status', session('driver.driver_status') ?? '') == 'suspended' ? 'selected' : '' }}>Suspended</option>
                                    <option value="on_leave" {{ old('driver_status', session('driver.driver_status') ?? '') == 'on_leave' ? 'selected' : '' }}>On Leave</option>
                                </select>
                                @error('driver_status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="fleet_id" class="form-label">Pilih Fleet <span class="text-danger">*</span></label>
                                <select class="form-select @error('fleet_id') is-invalid @enderror" 
                                        id="fleet_id" name="fleet_id" required>
                                    <option value="">-- Pilih Fleet --</option>
                                    <option value="fleet_a" {{ old('fleet_id', session('driver.fleet_id') ?? '') == 'fleet_a' ? 'selected' : '' }}>Fleet A - Truk Besar</option>
                                    <option value="fleet_b" {{ old('fleet_id', session('driver.fleet_id') ?? '') == 'fleet_b' ? 'selected' : '' }}>Fleet B - Truk Sedang</option>
                                    <option value="fleet_c" {{ old('fleet_id', session('driver.fleet_id') ?? '') == 'fleet_c' ? 'selected' : '' }}>Fleet C - Truk Kecil</option>
                                    <option value="fleet_d" {{ old('fleet_id', session('driver.fleet_id') ?? '') == 'fleet_d' ? 'selected' : '' }}>Fleet D - Pickup</option>
                                </select>
                                @error('fleet_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="krani" class="form-label">Nama Krani</label>
                                <input type="text" class="form-control @error('krani') is-invalid @enderror" 
                                       id="krani" name="krani" 
                                       value="{{ old('krani', session('driver.krani') ?? '') }}" 
                                       placeholder="Nama krani">
                                @error('krani')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="account_no" class="form-label">Nomor Akun</label>
                                <input type="text" class="form-control @error('account_no') is-invalid @enderror" 
                                       id="account_no" name="account_no" 
                                       value="{{ old('account_no', session('driver.account_no') ?? '') }}" 
                                       placeholder="Nomor akun">
                                @error('account_no')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="account" class="form-label">Nama Akun</label>
                            <input type="text" class="form-control @error('account') is-invalid @enderror" 
                                   id="account" name="account" 
                                   value="{{ old('account', session('driver.account') ?? '') }}" 
                                   placeholder="Nama akun">
                            @error('account')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="alert alert-success small">
                            <i class="bi bi-check-circle me-2"></i>
                            Ini adalah step terakhir. Pastikan semua data driver sudah benar sebelum disimpan.
                        </div>
                    </div>

                    <div class="card-footer d-flex justify-content-between">
                        <a href="{{ route('driver.create.step.seven') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-arrow-left-circle me-1"></i> Kembali
                        </a>
                        <button type="submit" class="btn btn-success">
                            Simpan Data Driver <i class="bi bi-check2-circle ms-2"></i>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection