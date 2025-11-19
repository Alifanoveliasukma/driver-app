@extends('layouts.template-planner') 

@section('title', 'Pendaftaran Driver Baru')

@section('content')
<div class="container py-3">
    <div class="card shadow-lg border-0 mb-4">
        <div class="card-header bg-primary text-white border-bottom-0">
            <h4 class="mb-0 fw-bold">Pendaftaran User & Driver Baru</h4>
        </div>
        <div class="card-body p-4">

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if(session('error') || session('warning'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="bi bi-x-octagon-fill me-2"></i> {{ session('error') ?? session('warning') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            
            @if ($errors->any())
                {{-- Alert ini akan ditampilkan jika ada error validasi dari server --}}
                <div class="alert alert-warning alert-dismissible fade show" role="alert" id="serverValidationError">
                    <h6 class="alert-heading fw-bold"><i class="bi bi-exclamation-triangle-fill me-2"></i> Terjadi Kesalahan Validasi:</h6>
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            {{-- END ALERT MESSAGES --}}

            <form method="POST" action="{{ route('driver.store') }}" id="driverForm">
                @csrf

                {{-- PROGRESS INDICATOR --}}
                <div class="d-flex justify-content-between mb-4 mt-2">
                    <div id="step-indicator-1" class="text-center w-50 border-bottom border-primary border-4 pb-2">
                        <p class="mb-0 fw-bold text-primary">1. Data User</p>
                    </div>
                    <div id="step-indicator-2" class="text-center w-50 border-bottom border-secondary border-2 pb-2">
                        <p class="mb-0 fw-bold text-secondary">2. Data Driver</p>
                    </div>
                </div>

                {{-- STEP 1: DATA USER (AD_User) --}}
                <div id="step1" class="step">
                    <div class="mb-5 p-3 border rounded shadow-sm">
                        <h5 class="fw-bold text-secondary border-bottom pb-2 mb-3">Step 1: Data User (AD_User)</h5>
                        <p class="text-muted small">Data ini digunakan untuk membuat akun User dan akan otomatis mendapatkan **Role Driver (ID 1000049)**.</p>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="user_value" class="form-label fw-semibold">Value (Kode/NIP User) <span class="text-danger">*</span></label>
                                <input type="text" name="user_value" id="user_value" class="form-control @error('user_value') is-invalid @enderror" value="{{ old('user_value') }}" required>
                                @error('user_value')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="user_name" class="form-label fw-semibold">Name (Nama Lengkap) <span class="text-danger">*</span></label>
                                <input type="text" name="user_name" id="user_name" class="form-control @error('user_name') is-invalid @enderror" value="{{ old('user_name') }}" required>
                                @error('user_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="user_password" class="form-label fw-semibold">Password <span class="text-danger">*</span></label>
                                <input type="password" name="user_password" id="user_password" class="form-control @error('user_password') is-invalid @enderror" required>
                                @error('user_password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                        </div>

                    </div>
                </div>
                {{-- END STEP 1 --}}


                {{-- STEP 2: DATA DRIVER (XM_Driver) --}}
                <div id="step2" class="step d-none">
                    <div class="mb-5 p-3 border rounded shadow-sm">
                        <h5 class="fw-bold text-secondary border-bottom pb-2 mb-3">Step 2: Data Driver (XM_Driver)</h5>
                        <p class="text-muted small">Kolom **Value** dan **Name** Driver akan menggunakan data dari Step 1.</p>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="driver_status" class="form-label fw-semibold">Status Driver (DriverStatus)</label>
                                <select name="driver_status" id="driver_status" class="form-select @error('driver_status') is-invalid @enderror" required>
                                    <option value="">-- Pilih Status --</option>
                                    @php
                                        $statuses = [
                                            'Stand By', 
                                            'Maintenance No Driver', 
                                            'Maintenance with Driver',
                                            'Off Duty', 
                                            'On Duty', 
                                            'Rented'
                                        ];
                                    @endphp
                                    @foreach ($statuses as $status)
                                        <option 
                                            value="{{ $status }}" 
                                            {{ old('driver_status') == $status ? 'selected' : (old('driver_status') === null && $status === 'Stand By' ? 'selected' : '') }}>
                                            {{ $status }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('driver_status')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="xm_fleet_id" class="form-label fw-semibold">Nama Fleet</label>
                                <select name="xm_fleet_id" id="xm_fleet_id" class="form-select select2 @error('xm_fleet_id') is-invalid @enderror">
                                    <option value="">-- Pilih Fleet --</option>
                                    @foreach($fleets as $fleet)
                                        <option 
                                            value="{{ $fleet->xm_fleet_id }}" 
                                            {{ old('xm_fleet_id') == $fleet->xm_fleet_id ? 'selected' : '' }}
                                        >
                                            {{ $fleet->fleet_name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('xm_fleet_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>

                        <div class="row">
                            <!-- <div class="col-md-6 mb-3">
                                <label for="krani_id" class="form-label fw-semibold">ID Krani (Krani_ID)</label>
                                <input type="number" name="krani_id" id="krani_id" class="form-control @error('krani_id') is-invalid @enderror" value="{{ old('krani_id') }}">
                                @error('krani_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div> -->

                            <div class="col-md-6 mb-3">
                                <label for="account_no" class="form-label fw-semibold">No. Akun</label>
                                <input type="text" name="account_no" id="account_no" class="form-control @error('account_no') is-invalid @enderror" value="{{ old('account_no') }}">
                                @error('account_no')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="note" class="form-label fw-semibold">Catatan (Note)</label>
                            <textarea name="note" id="note" class="form-control @error('note') is-invalid @enderror">{{ old('note') }}</textarea>
                            @error('note')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>
                </div>
                {{-- END STEP 2 --}}

                <div class="d-flex justify-content-between mt-4 mb-5">
                    {{-- NAVIGATION BUTTONS --}}
                    <a href="{{ route('driver.index') }}" class="btn btn-outline-secondary" id="backToListBtn">
                        <i class="bi bi-arrow-left me-1"></i> Kembali ke Daftar Driver
                    </a>
                    
                    <button type="button" class="btn btn-secondary d-none" id="prevBtn" onclick="nextStep(-1)">
                        <i class="bi bi-chevron-left me-1"></i> Sebelumnya
                    </button>
                    
                    <button type="button" class="btn btn-primary" id="nextBtn" onclick="nextStep(1)">
                        Lanjut ke Data Driver <i class="bi bi-chevron-right ms-1"></i>
                    </button>
                    
                    <button type="submit" class="btn btn-success d-none" id="submitBtn">
                        <i class="bi bi-save me-2"></i> Selesaikan Pendaftaran
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        $('#xm_fleet_id').select2({
            theme: 'bootstrap-5',
            placeholder: '-- Pilih Fleet --',
            allowClear: true
        });
    });
</script>

<script>
    let currentStep = 1;

    const step1Fields = ['user_value', 'user_name', 'user_password'];

    const step2Fields = [
        'driver_status', 'xm_fleet_id', 'krani_id', 'account_no', 
        'account_name', 'note', 'is_full_bp_access', 'is_login_user' 
    ];

    function showStep(n) {
        document.querySelectorAll('.step').forEach(step => {
            step.classList.add('d-none');
        });

        document.getElementById(`step${n}`).classList.remove('d-none');
        currentStep = n;

        document.getElementById('prevBtn').classList.toggle('d-none', n === 1);
        document.getElementById('nextBtn').classList.toggle('d-none', n === 2);
        document.getElementById('submitBtn').classList.toggle('d-none', n === 1);
        document.getElementById('backToListBtn').classList.toggle('d-none', n === 2);

        updateProgress(n);
    }

    function updateProgress(n) {
        const indicator1 = document.getElementById('step-indicator-1');
        const indicator2 = document.getElementById('step-indicator-2');
        const p1 = indicator1.querySelector('p');
        const p2 = indicator2.querySelector('p');

        if (n === 1) {
            indicator1.className = 'text-center w-50 border-bottom border-primary border-4 pb-2';
            p1.className = 'mb-0 fw-bold text-primary';
            indicator2.className = 'text-center w-50 border-bottom border-secondary border-2 pb-2';
            p2.className = 'mb-0 fw-bold text-secondary';
        } else if (n === 2) {
            indicator1.className = 'text-center w-50 border-bottom border-success border-2 pb-2';
            p1.className = 'mb-0 fw-bold text-success';
            indicator2.className = 'text-center w-50 border-bottom border-primary border-4 pb-2';
            p2.className = 'mb-0 fw-bold text-primary';
        }
    }

    function validateStep(step) {
        let isValid = true;
        let fieldsToCheck = [];
        
        if (step === 1) {
            fieldsToCheck = step1Fields;
        } 

        fieldsToCheck.forEach(fieldName => {
            const input = document.getElementById(fieldName);
            if (input && input.hasAttribute('required') && !input.value) {
                input.classList.add('is-invalid');
                isValid = false;
            } else if (input) {
                input.classList.remove('is-invalid');
            }
        });

        if (!isValid) {
            alert('Mohon lengkapi semua field yang wajib di Step 1 sebelum melanjutkan.');
        }

        return isValid;
    }

    function nextStep(n) {
        if (n === 1 && currentStep === 1) {
            if (!validateStep(1)) {
                return; 
            }
        }

        let next = currentStep + n;

        if (next > 2) next = 2;
        if (next < 1) next = 1;

        showStep(next);
    }
    document.addEventListener('DOMContentLoaded', function() {
        const errorElement = document.getElementById('serverValidationError');
        
        if (errorElement) {
            const isStep2Error = step2Fields.some(fieldName => {
                return document.getElementById(fieldName)?.classList.contains('is-invalid');
            });

            if (isStep2Error) {
                showStep(2);
            } else {
                showStep(1);
            }
        } else {
            showStep(1);
        }
    });


</script>
@endsection