@extends('layouts.template-planner')

@section('title', 'Detail Driver')

@section('content')
<div class="container py-4">
    <div class="d-flex align-items-center mb-4">
        <button class="btn btn-outline-primary me-3" onclick="window.history.back()" aria-label="Kembali">
            <i class="bi bi-chevron-left"></i>
        </button>
        <h4 class="fw-bold text-primary mb-0">Detail Driver</h4>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body">
            <div class="row g-3">
                <!-- Kiri -->
                <div class="col-md-6">
                    <div class="mb-3 row">
                        <label class="col-sm-4 col-form-label fw-semibold">Client</label>
                        <div class="col-sm-8 pt-2">
                            <span class="fw-semibold">MZL</span>
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label class="col-sm-4 col-form-label fw-semibold">Search Key</label>
                        <div class="col-sm-8 pt-2">
                            <span>100000999</span>
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label class="col-sm-4 col-form-label fw-semibold">Name</label>
                        <div class="col-sm-8 pt-2">
                            <span>BAGAS</span>
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label class="col-sm-4 col-form-label fw-semibold">Business Partner</label>
                        <div class="col-sm-8 pt-2">
                            <span>BAGAS</span>
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label class="col-sm-4 col-form-label fw-semibold">Active</label>
                        <div class="col-sm-8 pt-2">
                            <i class="bi bi-check2-square text-success fs-5"></i> <span class="text-success fw-semibold">Active</span>
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label class="col-sm-4 col-form-label fw-semibold">Account No</label>
                        <div class="col-sm-8 pt-2">
                            <span>217401789</span>
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label class="col-sm-4 col-form-label fw-semibold">Note</label>
                        <div class="col-sm-8">
                            <textarea class="form-control" rows="2" readonly>-</textarea>
                        </div>
                    </div>
                </div>

                <!-- Kanan -->
                <div class="col-md-6">
                    <div class="mb-3 row">
                        <label class="col-sm-4 col-form-label fw-semibold">Organization</label>
                        <div class="col-sm-8 pt-2">
                            <span>*</span>
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label class="col-sm-4 col-form-label fw-semibold">Driver Status</label>
                        <div class="col-sm-8 pt-2">
                            <span>Stand By</span>
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label class="col-sm-4 col-form-label fw-semibold">Fleet</label>
                        <div class="col-sm-8 pt-2">
                            <span>823222-B 2807 AUD</span>
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label class="col-sm-4 col-form-label fw-semibold">Krani</label>
                        <div class="col-sm-8 pt-2">
                            <span>K001 - SUKRI</span>
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label class="col-sm-4 col-form-label fw-semibold">Account</label>
                        <div class="col-sm-8 pt-2">
                            <span>BCA</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="text-end mt-4">
                <a href="{{ route('driver.edit', 1) }}" class="btn btn-warning text-white">
                    <i class="bi bi-pencil-square me-2"></i> Edit
                </a>
                <button type="button" class="btn btn-secondary" onclick="window.history.back()">
                    <i class="bi bi-arrow-left-circle me-2"></i> Kembali
                </button>
            </div>
        </div>
    </div>
</div>
@endsection
