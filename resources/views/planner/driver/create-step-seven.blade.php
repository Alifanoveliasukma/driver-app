@extends('layouts.template-planner')

@section('title', 'Tambah Driver - Step 7')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="d-flex align-items-center justify-content-between mb-4">
                <div class="d-flex align-items-center">
                    <a href="{{ route('driver.create.step.six') }}" class="btn btn-outline-primary me-3" aria-label="Kembali">
                        <i class="bi bi-chevron-left"></i>
                    </a>
                    <h4 class="fw-bold text-primary mb-0">Tambah Driver</h4>
                </div>
                <div class="text-muted small">Step 7 dari 8</div>
            </div>

            <!-- Progress Bar -->
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-body py-3">
                    <div class="progress" style="height: 8px;">
                        <div class="progress-bar bg-warning" role="progressbar" style="width: 87.5%"></div>
                    </div>
                    <div class="d-flex justify-content-between mt-2">
                        <span class="small text-muted">User Management</span>
                        <span class="small text-warning fw-semibold">Business Partner</span>
                        <span class="small text-muted">Driver Specification</span>
                    </div>
                </div>
            </div>

            <form action="{{ route('driver.create.step.seven.post') }}" method="POST">
                @csrf
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-warning text-dark">
                        <h5 class="mb-0 fw-semibold">Step 7: Penghubungan User Contact</h5>
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
                            <label for="user_contact_id" class="form-label">Pilih User Driver <span class="text-danger">*</span></label>
                            <select class="form-select @error('user_contact_id') is-invalid @enderror" 
                                    id="user_contact_id" name="user_contact_id" required>
                                <option value="">-- Pilih User Driver --</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}" 
                                        {{ old('user_contact_id', session('driver.user_contact_id') ?? '') == $user->id ? 'selected' : '' }}>
                                        {{ $user->name }} ({{ $user->email ?? $user->phone }})
                                    </option>
                                @endforeach
                            </select>
                            @error('user_contact_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" 
                                   id="customer_only" name="customer_only" 
                                   {{ old('customer_only', session('driver.customer_only') ?? '') ? 'checked' : '' }}>
                            <label class="form-check-label" for="customer_only">Hanya Customer?</label>
                            <div class="form-text">Jika dicentang, business partner hanya akan berperan sebagai customer</div>
                        </div>

                        <div class="alert alert-info small">
                            <i class="bi bi-info-circle me-2"></i>
                            Business partner akan menunjukkan nama driver yang dipilih di sini.
                        </div>

                        <!-- Preview Business Partner Data -->
                        <div class="card bg-light border-0 mt-4">
                            <div class="card-header bg-transparent">
                                <h6 class="mb-0 fw-semibold">Data Business Partner:</h6>
                            </div>
                            <div class="card-body small">
                                @php $driver = session('driver'); @endphp
                                @if($driver && isset($driver['bp_name']))
                                    <div class="row">
                                        <div class="col-md-6">
                                            <strong>Nama BP:</strong> {{ $driver['bp_name'] }}
                                        </div>
                                        <div class="col-md-6">
                                            <strong>Status Kredit:</strong> 
                                            @switch($driver['credit_status'] ?? '')
                                                @case('credit_hold') Credit Hold @break
                                                @case('credit_ok') Credit OK @break
                                                @case('credit_stop') Credit Stop @break
                                                @case('credit_watch') Credit Watch @break
                                                @case('no_credit_check') No Credit Check @break
                                                @default -
                                            @endswitch
                                        </div>
                                    </div>
                                @else
                                    <div class="text-warning">Belum ada data business partner</div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="card-footer d-flex justify-content-between">
                        <a href="{{ route('driver.create.step.six') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-arrow-left-circle me-1"></i> Kembali
                        </a>
                        <button type="submit" class="btn btn-warning">
                            Lanjut ke Step 8 <i class="bi bi-arrow-right-circle ms-2"></i>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection