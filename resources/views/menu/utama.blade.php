@extends('layouts.template')

@section('content')
<div class="position-relative bg-purple text-white " style="height: 120px;">
    <div class="floating-box">
        <div class="row-item">
            <span class="label">Surat Jalan</span>
            <span class="value">PROJECT/22/10/08827</span>
        </div>
        <div class="row-item">
            <span class="label">Pelanggan</span>
            <span class="value">PT. SEMEN INDONESIA</span>
        </div>
    </div>
</div>
<!-- CARD ALAMAT -->
<div class="alamat-box" style="width: 90%; max-width: 400px; margin-top: 70px; background-color: #f3f3f3; padding: 16px; border-radius: 12px; box-shadow: 0 4px 8px rgba(0,0,0,0.05); ">
  <div class="d-flex justify-content-between align-items-start">
    <div>
      <div class="text-muted mb-1" style="font-size: 14px;">Alamat Pengambilan</div>
      <div style="font-weight: bold;">INTEGRATION SIG FR</div>
      <div style="font-weight: bold;">Jl Veteran</div>
    </div>
    <div class="text-center">
      <div class="bg-primary text-white rounded p-2 d-flex flex-column align-items-center justify-content-center" style="width: 80px; height: 80px;">
        <i class="bi bi-geo-alt-fill" style="font-size: 24px;"></i>
        <small>Lihat Peta</small>
      </div>
    </div>
  </div>
</div>

<!-- WRAPPER -->
<div style="width: 90%; max-width: 400px; margin: 0 auto;">

  <!-- Label -->
  <div class="text-center mt-3 mb-2">
    <span class="text-muted" style="font-weight: 500;">Tanggal-Jam Berangkat</span>
  </div>

  <!-- Tanggal dan Jam -->
  <div class="d-flex justify-content-between gap-2 mb-3">
    <div class="bg-light rounded p-3 text-center flex-fill">
      <div style="font-weight: bold;">06 Oct 2022</div>
    </div>
    <div class="bg-light rounded p-3 text-center flex-fill">
      <div style="font-weight: bold;">15:51</div>
    </div>
  </div>

  <!-- KM Mobil -->
    <div class="d-flex align-items-center rounded px-3 py-2" style="width: 100%;">
        <div class="text-muted" style="flex: 1;">KM Mobil</div>
        <input type="number" class="form-control border-0 text-center mx-2" value="100" style="max-width: 80px;">
        <div class="fw-bold">KM</div>
    </div>

    <div class="slide-confirm-container">
      <div class="slide-track bg-light rounded shadow-sm">
        <div class="slide-button bg-white" onmousedown="startSlide(event)">
          <i class="bi bi-chevron-double-right text-primary" style="font-size: 24px; transform: translateX(8px);"></i>
        </div>
        <span class="slide-label text-primary fw-semibold">Konfirmasi Berangkat</span>
      </div>
    </div>
</div>

@endsection
