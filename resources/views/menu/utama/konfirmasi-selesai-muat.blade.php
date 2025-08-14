@extends('layouts.template')

@section('content')
<div class="position-relative bg-purple text-white" style="height: 100px;">
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

<!-- Konten scroll -->
<div class="scrollable-content px-3">

  <div class="alamat-box" style="width: 90%; max-width: 400px; margin-top: 50px; background-color: #f3f3f3; padding: 16px; border-radius: 12px; box-shadow: 0 4px 8px rgba(0,0,0,0.05);">
    <div class="d-flex justify-content-between align-items-start">
      <div>
        <div class="text-muted mb-1" style="font-size: 14px;">Alamat Pengiriman</div>
        <div style="font-weight: bold;">Gudang Jakarta</div>
        <div style="font-weight: bold;">Jakarta Utara</div>
      </div>
      <div class="text-center">
        <div class="bg-primary text-white rounded p-2 d-flex flex-column align-items-center justify-content-center" style="width: 80px; height: 80px;">
          <i class="bi bi-geo-alt-fill" style="font-size: 24px;"></i>
          <small>Lihat Peta</small>
        </div>
      </div>
    </div>
  </div>

  <div class="text-center mt-3 mb-2">
    <span class="text-muted" style="font-weight: 500;">Tanggal-Jam Mulai Muat</span>
  </div>
  <div class="d-flex justify-content-between gap-2 mb-3" style="max-width: 400px; margin: 0 auto;">
    <div class="bg-light rounded p-3 text-center flex-fill">
      <div style="font-weight: bold;">06 Oct 2022</div>
    </div>
    <div class="bg-light rounded p-3 text-center flex-fill">
      <div style="font-weight: bold;">20:25</div>
    </div>
  </div>

</div>

<!-- Tombol fixed -->
<div class="slide-confirm-container px-3">
  <div class="slide-track bg-light rounded shadow-sm d-flex align-items-center justify-content-between px-3 py-2" style="max-width: 400px; margin: 0 auto;" data-redirect="{{ route('utama.konfirmasi-keluar-muat') }}">
    <div class="slide-button bg-white d-flex justify-content-center align-items-center" onmousedown="startSlide(event)" style="width: 48px; height: 48px; border-radius: 0;">
      <img src="{{ asset('assets/icon/img-right.png') }}" alt="Right Arrow" style="width: 30px; height: 30px; filter: brightness(0) saturate(100%) invert(29%) sepia(94%) saturate(5096%) hue-rotate(202deg) brightness(95%) contrast(94%);">
    </div>
    <span class="slide-label text-primary fw-semibold">Konfirmasi Selesai Muat</span>
  </div>
</div>

@push('scripts')
<script>
document.addEventListener('focusin', (e) => {
  if (e.target.matches('input, textarea, select')) {
    setTimeout(() => {
      e.target.scrollIntoView({ behavior: 'smooth', block: 'center' });
    }, 100);
  }
});
</script>
@endpush

@endsection
