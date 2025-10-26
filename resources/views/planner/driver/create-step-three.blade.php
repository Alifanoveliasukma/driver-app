@extends('layouts.template-planner')

@section('title', 'Tambah Driver - Step 3')

@section('content')

<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="d-flex align-items-center justify-content-between mb-4">
                <div class="d-flex align-items-center">
                    <h4 class="fw-bold text-primary mb-0">Tambah Driver</h4>
                </div>
            </div>

            <form action="{{ route('driver.create.step.three.post') }}" method="POST">
                @csrf

                <div class="card shadow-sm border-0">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0 fw-semibold">Step 3: Data Organisasi & Finalisasi</h5>
                    </div>

                    <div class="card-body">
                        {{-- Validasi error --}}
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
                            <label for="ad_client_id" class="form-label fw-semibold">Client ID <span class="text-danger">*</span></label>
                            <input type="text"
                                   class="form-control"
                                   id="ad_client_id"
                                   name="ad_client_id"
                                   value="{{ $driver['ad_client_id'] ?? '' }}"
                                   placeholder="Masukkan Client ID"
                                   required>
                        </div>

                        <div class="mb-3">
                            <label for="ad_org_id" class="form-label fw-semibold">Organization ID <span class="text-danger">*</span></label>
                            <input type="text"
                                   class="form-control"
                                   id="ad_org_id"
                                   name="ad_org_id"
                                   value="{{ $driver['ad_org_id'] ?? '' }}"
                                   placeholder="Masukkan Organization ID"
                                   required>
                        </div>

                        <div class="mb-3 form-check">
                            <input class="form-check-input" type="checkbox" id="active" name="active" value="1"
                                   {{ isset($driver['active']) && $driver['active'] ? 'checked' : '' }}>
                            <label class="form-check-label fw-semibold" for="active">Active</label>
                        </div>
                    </div>

                    <div class="card-footer d-flex justify-content-between">
                        <a href="{{ route('driver.create.step.two') }}" class="btn btn-secondary">
                            <i class="bi bi-arrow-left-circle me-2"></i> Back
                        </a>
                        <button type="submit" class="btn btn-success">
                            <i class="bi bi-check-circle me-2"></i> Finish
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
