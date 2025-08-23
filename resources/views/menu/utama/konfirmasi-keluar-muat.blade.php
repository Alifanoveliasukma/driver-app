@extends('layouts.template')

@section('content')
<div class="position-relative bg-purple text-white" style="height: 100px;">
  <div class="floating-box">
    <div class="row-item">
      <span class="label">Surat Jalan</span>
      <span class="value">{{$mappedDetail['Value'] ?? '-'}}</span>
    </div>
    <div class="row-item">
      <span class="label">Pelanggan</span>
      <span class="value">{{ $mappedDetail['Customer_Name'] ?? '-' }}</span>
    </div>
  </div>
</div>


<div class="scrollable-content px-3" style="margin-top: 20px; margin-bottom: 100px;">

    <div class="alamat-box" style="width: 90%; max-width: 400px; margin-top: 50px; background-color: #f3f3f3; padding: 16px; border-radius: 12px; box-shadow: 0 4px 8px rgba(0,0,0,0.05); ">
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
        <span class="text-muted" style="font-weight: 500;">Tanggal-Jam Keluar Tempat Muat</span>
    </div>
    <div class="d-flex justify-content-between gap-2 mb-3" style="max-width: 400px; margin: 0 auto;">
        <div class="bg-light rounded p-3 text-center flex-fill">
            <div style="font-weight: bold;">06 Oct 2022</div>
        </div>
        <div class="bg-light rounded p-3 text-center flex-fill">
            <div style="font-weight: bold;">20:26</div>
        </div>
    </div>

    <div class="text-center total-muat-container ">
        <label class="total-muat-label">Total Muat</label>
        <div class="total-muat-input">
            <input type="number" value="1" min="0" step="1">
            <span class="unit">Ton</span>
        </div>
    </div>
    
    <div class="foto-upload-wrapper mt-3">
        <label for="foto-sopir" class="foto-upload-box" id="fotoBox">
            <i class="bi bi-camera-fill icon"></i>
            <span class="placeholder">Foto Sopir</span>

            <!-- Preview -->
            <img id="fotoPreview" class="preview" alt="Preview foto" />
            <span id="fotoName" class="filename"></span>

            <input type="file" id="foto-sopir" accept="image/*" capture="environment" hidden>
            <button type="button" class="btn-delete-foto" id="clearFoto" style="display:none;">
                <i class="bi bi-trash"></i> Hapus Foto
            </button>
        </label>
        
    </div>

    <div class="position-fixed start-0 end-0 px-3" style="bottom: 80px; z-index: 999;">
      <div class="slide-confirm-container start-0 end-0 px-3" style="bottom: 50px; z-index: 999;" >
        <div class="slide-track bg-light rounded shadow-sm d-flex align-items-center justify-content-between px-3 py-2" style="max-width: 400px; margin: 0 auto;" data-redirect="{{ route('utama.konfirmasi-tiba-tujuan', ['orderId' => $mappedDetail['XX_TransOrder_ID'] ?? '']) }}">
          <div class="slide-button bg-white d-flex justify-content-center align-items-center" onmousedown="startSlide(event)" style="width: 48px; height: 48px; border-radius: 0;">
          <img src="{{ asset('assets/icon/img-right.png') }}" alt="Right Arrow" style="width: 30px; height: 30px; filter: brightness(0) saturate(100%) invert(29%) sepia(94%) saturate(5096%) hue-rotate(202deg) brightness(95%) contrast(94%);">
          </div>
          <span class="slide-label text-primary fw-semibold">Konfirmasi Keluar Muat</span>
        </div>
      </div>
    </div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
  const input   = document.getElementById('foto-sopir');
  const box     = document.getElementById('fotoBox');
  const preview = document.getElementById('fotoPreview');
  const nameEl  = document.getElementById('fotoName');
  const clear   = document.getElementById('clearFoto');

  function resetFoto(){
    input.value = '';
    preview.src = '';
    nameEl.textContent = '';
    box.classList.remove('has-file');
    if (clear) clear.style.display = 'none';
  }

  input.addEventListener('change', () => {
    const file = input.files && input.files[0];
    if (!file) { resetFoto(); return; }

    nameEl.textContent = file.name;
    const reader = new FileReader();
    reader.onload = (e) => {
      preview.src = e.target.result;
      box.classList.add('has-file');
      if (clear) clear.style.display = 'inline-block';
    };
    reader.readAsDataURL(file);
  });

  if (clear){
    clear.addEventListener('click', (e) => {
      e.preventDefault();
      resetFoto();
    });
  }
});
</script>
@endpush
@endsection
