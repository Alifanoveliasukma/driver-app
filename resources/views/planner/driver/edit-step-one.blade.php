@extends('layouts.template-planner')

@section('title', 'Edit Driver - Step 1')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-10">

             <div class="d-flex align-items-center justify-content-between mb-4">
                <div class="d-flex align-items-center">
                    <button class="btn btn-outline-primary me-3" onclick="window.history.back()" aria-label="Kembali">
                        <i class="bi bi-chevron-left"></i>
                    </button>
                    <h4 class="fw-bold text-primary mb-0">Edit Driver</h4>
                </div>
            </div>

            <form action="#" method="POST">
                @csrf

                <div class="card shadow-sm border-0">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0 fw-semibold">Step 1: Data Dasar Driver</h5>
                    </div>

                    <div class="card-body">
                        {{-- Contoh alert validasi --}}
                        <div class="alert alert-danger d-none">
                            <ul class="mb-0">
                                <li>Nama driver wajib diisi.</li>
                                <li>Status driver harus dipilih.</li>
                            </ul>
                        </div>

                        <div class="mb-3">
                            <label for="search_key" class="form-label fw-semibold">Search Key</label>
                            <input type="text" class="form-control" id="search_key" name="search_key"
                                   value="DRV001" placeholder="Masukkan Search Key (opsional)">
                        </div>

                        <div class="mb-3">
                            <label for="name" class="form-label fw-semibold">Nama Driver <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="name" name="name"
                                   value="Ahmad Yusuf" placeholder="Masukkan nama driver" required>
                        </div>

                        <div class="mb-3">
                            <label for="c_bpartner_id" class="form-label fw-semibold">Business Partner</label>
                            <input type="text" class="form-control" id="c_bpartner_id" name="c_bpartner_id"
                                   value="BP-1002" placeholder="Masukkan Business Partner">
                        </div>

                        <div class="mb-3">
                            <label for="driverstatus" class="form-label fw-semibold">Status Driver <span class="text-danger">*</span></label>
                            <select class="form-select" name="driverstatus" id="driverstatus" required>
                                <option value="">-- Pilih Status --</option>
                                <option value="Stand By" selected>Stand By</option>
                                <option value="On Duty">On Duty</option>
                                <option value="Off">Off</option>
                            </select>
                        </div>
                    </div>

                    <div class="card-footer text-end">
                        <a href="/driver/edit-step-two" class="btn btn-primary">
                            Next <i class="bi bi-arrow-right-circle ms-2"></i>
                        </a>
                    </div>

                </div>
            </form>
        </div>
    </div>
</div>
@endsection
