@extends('layouts.template-planner')

@section('title', 'Edit Driver - Step 2')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-10">

             <div class="d-flex align-items-center justify-content-between mb-4">
                <div class="d-flex align-items-center">
                    <h4 class="fw-bold text-primary mb-0">Edit Driver</h4>
                </div>
            </div>

            <form action="#" method="POST">
                @csrf

                <div class="card shadow-sm border-0">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0 fw-semibold">Step 2: Informasi Kendaraan & Rekening</h5>
                    </div>

                    <div class="card-body">
                        {{-- Contoh alert validasi --}}
                        <div class="alert alert-danger d-none">
                            <ul class="mb-0">
                                <li>Field wajib diisi.</li>
                            </ul>
                        </div>

                        <div class="mb-3">
                            <label for="krani_id" class="form-label fw-semibold">Krani</label>
                            <input type="text" class="form-control" id="krani_id" name="krani_id"
                                   value="KRN-008" placeholder="Masukkan ID atau nama Krani">
                        </div>

                        <div class="mb-3">
                            <label for="account" class="form-label fw-semibold">Account (Bank)</label>
                            <input type="text" class="form-control" id="account" name="account"
                                   value="BCA - 1234567890 a.n Ahmad Yusuf" placeholder="Masukkan nama bank / rekening">
                        </div>

                        <div class="mb-3">
                            <label for="note" class="form-label fw-semibold">Catatan</label>
                            <textarea class="form-control" id="note" name="note" rows="3"
                                      placeholder="Tulis catatan tambahan">Driver baru pindahan dari cabang Bekasi.</textarea>
                        </div>
                    </div>

                    <div class="card-footer d-flex justify-content-between">
                        <a href="#" class="btn btn-secondary">
                            <i class="bi bi-arrow-left-circle me-2"></i> Back
                        </a>
                        <div class="card-footer text-end">
                            <a href="/driver/edit-step-three" class="btn btn-primary">
                                Next <i class="bi bi-arrow-right-circle ms-2"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
