@extends('layouts.template-planner')

@section('title', 'Edit Driver')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold text-primary">Edit Data Driver</h4>
        <a href="{{ route('driver.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left me-2"></i> Kembali
        </a>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body">
            {{-- Ganti action sesuai route update (contoh: route('driver.update', $driver->id)) --}}
            <form action="#" method="POST">
                @csrf
                @method('PUT')

                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="nama" class="form-label fw-semibold">Nama Driver</label>
                        <input type="text" id="nama" name="nama" class="form-control" 
                            value="{{ old('nama', $driver->nama ?? 'Ahmad Yusuf') }}" required>
                    </div>

                    <div class="col-md-6">
                        <label for="telepon" class="form-label fw-semibold">Nomor Telepon</label>
                        <input type="text" id="telepon" name="telepon" class="form-control" 
                            value="{{ old('telepon', $driver->telepon ?? '08123456789') }}" required>
                    </div>

                    <div class="col-md-6">
                        <label for="plat_nomor" class="form-label fw-semibold">Plat Nomor</label>
                        <input type="text" id="plat_nomor" name="plat_nomor" class="form-control" 
                            value="{{ old('plat_nomor', $driver->plat_nomor ?? 'B 1234 XYZ') }}" required>
                    </div>

                    <div class="col-md-6">
                        <label for="kendaraan" class="form-label fw-semibold">Jenis Kendaraan</label>
                        <select id="kendaraan" name="kendaraan" class="form-select" required>
                            <option value="">Pilih jenis kendaraan</option>
                            <option value="mobil box" {{ (old('kendaraan', $driver->kendaraan ?? '') == 'mobil box') ? 'selected' : '' }}>Mobil Box</option>
                            <option value="pick up" {{ (old('kendaraan', $driver->kendaraan ?? '') == 'pick up') ? 'selected' : '' }}>Pick Up</option>
                            <option value="motor" {{ (old('kendaraan', $driver->kendaraan ?? '') == 'motor') ? 'selected' : '' }}>Motor</option>
                            <option value="lainnya" {{ (old('kendaraan', $driver->kendaraan ?? '') == 'lainnya') ? 'selected' : '' }}>Lainnya</option>
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label for="status" class="form-label fw-semibold">Status</label>
                        <select id="status" name="status" class="form-select" required>
                            <option value="">Pilih status</option>
                            <option value="aktif" {{ (old('status', $driver->status ?? '') == 'aktif') ? 'selected' : '' }}>Aktif</option>
                            <option value="nonaktif" {{ (old('status', $driver->status ?? '') == 'nonaktif') ? 'selected' : '' }}>Nonaktif</option>
                        </select>
                    </div>

                    <div class="col-md-12">
                        <label for="alamat" class="form-label fw-semibold">Alamat</label>
                        <textarea id="alamat" name="alamat" class="form-control" rows="3">{{ old('alamat', $driver->alamat ?? 'Jl. Mawar No. 123, Jakarta') }}</textarea>
                    </div>
                </div>

                <div class="mt-4 text-end">
                    <button type="reset" class="btn btn-outline-secondary me-2">
                        <i class="bi bi-x-circle me-1"></i> Reset
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save me-1"></i> Update
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
