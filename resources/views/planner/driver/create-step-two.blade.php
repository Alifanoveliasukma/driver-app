@extends('layouts.template-planner')

@section('title', 'Tambah Driver - Step 2')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="d-flex align-items-center justify-content-between mb-4">
                <div class="d-flex align-items-center">
                    <h4 class="fw-bold text-primary mb-0">Tambah Driver</h4>
                </div>
            </div>

            <form action="{{ route('driver.create.step.two.post') }}" method="POST">
                @csrf

                <div class="card shadow-sm border-0">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0 fw-semibold">Step 2: Informasi Kendaraan & Rekening</h5>
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
                            <label for="krani_id" class="form-label fw-semibold">Krani</label>
                            <input type="text"
                                   class="form-control"
                                   id="krani_id"
                                   name="krani_id"
                                   value="{{ $driver['krani_id'] ?? '' }}"
                                   placeholder="Masukkan ID atau nama Krani">
                        </div>

                        <div class="mb-3">
                            <label for="account" class="form-label fw-semibold">Account (Bank)</label>
                            <input type="text"
                                   class="form-control"
                                   id="account"
                                   name="account"
                                   value="{{ $driver['account'] ?? '' }}"
                                   placeholder="Masukkan nama bank / rekening">
                        </div>

                        <div class="mb-3">
                            <label for="note" class="form-label fw-semibold">Catatan</label>
                            <textarea class="form-control"
                                      id="note"
                                      name="note"
                                      rows="3"
                                      placeholder="Tulis catatan tambahan">{{ $driver['note'] ?? '' }}</textarea>
                        </div>
                    </div>

                    <div class="card-footer d-flex justify-content-between">
                        <a href="{{ route('driver.create.step.one') }}" class="btn btn-secondary">
                            <i class="bi bi-arrow-left-circle me-2"></i> Back
                        </a>
                        <button type="submit" class="btn btn-primary">
                            Next <i class="bi bi-arrow-right-circle ms-2"></i>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
