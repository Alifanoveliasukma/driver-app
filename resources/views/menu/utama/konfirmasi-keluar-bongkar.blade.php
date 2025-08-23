@extends('layouts.template')

@section('content')
<div class="position-relative bg-purple text-white" style="height: 100px;">
  <!-- Parent ungu sebagai anchor -->
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
        <span class="text-muted" style="font-weight: 500;">Tanggal-Jam Keluar Tempat Bongkar</span>
    </div>
    <div class="d-flex justify-content-between gap-2 mb-3" style="max-width: 400px; margin: 0 auto;">
        <div class="bg-light rounded p-3 text-center flex-fill">
            <div style="font-weight: bold;">06 Oct 2022</div>
        </div>
        <div class="bg-light rounded p-3 text-center flex-fill">
            <div style="font-weight: bold;">20:26</div>
        </div>
    </div>

<div class="section-divider my-3">
  <span>DO - SPJ Detail</span>
</div>

    <div class="form-rows">
    <div class="unit-row">
        <label for="total_muat">Total Muat</label>
        <div class="unit-input">
        <input type="number" id="total_muat" name="total_muat" value="1" min="0" step="1">
        <span class="unit">Ton</span>
        </div>
    </div>

    <div class="unit-row">
        <label for="total_bongkar">Total Bongkar</label>
        <div class="unit-input">
        <input type="number" id="total_bongkar" name="total_bongkar" value="1" min="0" step="1">
        <span class="unit">Ton</span>
        </div>
    </div>

    <div class="unit-row">
        <label for="total_tonase">Total Tonase</label>
        <div class="unit-input">
        <input type="number" id="total_tonase" name="total_tonase" value="0.001" min="0" step="0.001">
        <span class="unit">Tonase</span>
        </div>
    </div>

    <div class="unit-row">
        <label for="total_kubikasi">Total Kubikasi</label>
        <div class="unit-input">
        <input type="number" id="total_kubikasi" name="total_kubikasi" value="0" min="0" step="0.001">
        <span class="unit">Kubikasi</span>
        </div>
    </div>
    </div>

   <hr></hr>

    <div class="form-rows" style="max-width:400px;margin:0 auto;">
        <div class="section-label">eSigning</div>
        <div class="sign-box">
            <canvas id="signPad" width="360" height="150"></canvas>
        </div>
    <button type="button" id="clearSign" class="btn btn-sm btn-outline-danger mt-2">
    Hapus Tanda Tangan
    </button>

    <div class="section-label mt-3">Upload Dokumen</div>

    <div class="doc-card" id="docCard">
        <span class="doc-title">Surat Jalan</span>
        <button type="button" class="doc-remove" id="removeDoc" style="display:none;">
        <i class="bi bi-x-lg"></i>
        </button>

        <label for="docFile" class="doc-placeholder" id="docPh">
        <i class="bi bi-camera-fill"></i>
        <span>Upload Foto</span>
        </label>
        <input type="file" id="docFile" accept="image/*" hidden>

        <img id="docPreview" class="doc-preview" alt="Preview dokumen" style="display:none;">
    </div>

    <div class="foto-upload-wrapper mt-3">
        <label for="fotoSopir" class="foto-upload-box" id="fotoBox">
        <i class="bi bi-camera-fill icon"></i>
        <span class="placeholder">Foto Sopir</span>
        <img id="fotoPreview" class="preview" alt="Preview foto"/>
        <span id="fotoName" class="filename"></span>
        <input type="file" id="fotoSopir" accept="image/*" capture="environment" hidden>
        </label>
        <button type="button" class="btn-delete-foto" id="clearFoto" style="display:none;">
        <i class="bi bi-trash"></i> Hapus Foto
        </button>
    </div>

    <div class="inline-input mt-3">
        <label for="noDo">NO DO-SPJ Order</label>
        <input id="noDo" type="text" class="form-control">
    </div>
    </div>
    <div class="mt-3" style="max-width:400px;margin:0 auto;">
        <button type="button" class="btn-next-order w-100">
            Tunggu Order Berikutnya
        </button>
    </div>

    <div class="confirm-note mt-3" style="max-width:400px;margin:0 auto;">
    Dengan melakukan konfirmasi, anda menyetujui hasil proses pembongkaran
    </div>


    <div class="position-fixed start-0 end-0 px-3" style="bottom: 80px; z-index: 999;">
        <div class="slide-confirm-container start-0 end-0 px-3" style="bottom: 50px; z-index: 999;">
            <div class="slide-track bg-light rounded shadow-sm d-flex align-items-center justify-content-between px-3 py-2" style="max-width: 400px; margin: 0 auto;" data-redirect="{{ route('menu.konfirmasi-berangkat') }}">
                <div class="slide-button bg-white d-flex justify-content-center align-items-center" onmousedown="startSlide(event)" style="width: 48px; height: 48px; border-radius: 0;">
                <img src="{{ asset('assets/icon/img-right.png') }}" alt="Right Arrow" style="width: 30px; height: 30px; filter: brightness(0) saturate(100%) invert(29%) sepia(94%) saturate(5096%) hue-rotate(202deg) brightness(95%) contrast(94%);">
                </div>
                <span class="slide-label text-primary fw-semibold">Konfirmasi Keluar Bongkar</span>
            </div>
        </div>
    </div>
<script>

@push('scripts')
(function(){
  const c = document.getElementById('signPad');
  if(!c) return;
  const ctx = c.getContext('2d');
  let drawing=false,last=null;

  const pos = (e)=> {
    const r=c.getBoundingClientRect();
    const x = (e.touches? e.touches[0].clientX : e.clientX) - r.left;
    const y = (e.touches? e.touches[0].clientY : e.clientY) - r.top;
    return {x, y};
  };

  const start = (e)=>{ drawing=true; last=pos(e); e.preventDefault(); };
  const move  = (e)=>{
    if(!drawing) return;
    const p=pos(e);
    ctx.lineWidth=2; ctx.lineCap='round'; ctx.strokeStyle='#000';
    ctx.beginPath(); ctx.moveTo(last.x,last.y); ctx.lineTo(p.x,p.y); ctx.stroke();
    last=p; e.preventDefault();
  };
  const end = ()=>{ drawing=false; };

  c.addEventListener('mousedown', start);
  c.addEventListener('mousemove', move);
  window.addEventListener('mouseup', end);

  c.addEventListener('touchstart', start, {passive:false});
  c.addEventListener('touchmove',  move,  {passive:false});
  c.addEventListener('touchend',   end);
})();

(function(){
  const input = document.getElementById('docFile');
  const preview = document.getElementById('docPreview');
  const placeholder = document.getElementById('docPh');
  const removeBtn = document.getElementById('removeDoc');

  if(!input) return;

  input.addEventListener('change', ()=>{
    const f = input.files && input.files[0];
    if(!f){ reset(); return; }
    const reader = new FileReader();
    reader.onload = e=>{
      preview.src = e.target.result;
      preview.style.display = 'block';
      placeholder.style.display = 'none';
      removeBtn.style.display = 'inline-flex';
    };
    reader.readAsDataURL(f);
  });

  function reset(){
    input.value='';
    preview.src='';
    preview.style.display='none';
    placeholder.style.display='flex';
    removeBtn.style.display='none';
  }
  document.getElementById('removeDoc')?.addEventListener('click', reset);
})();

(function(){
  const input   = document.getElementById('fotoSopir');
  const box     = document.getElementById('fotoBox');
  const preview = document.getElementById('fotoPreview');
  const nameEl  = document.getElementById('fotoName');
  const clear   = document.getElementById('clearFoto');
  if(!input) return;

  function reset(){
    input.value=''; preview.src=''; nameEl.textContent='';
    box.classList.remove('has-file'); clear.style.display='none';
  }
  input.addEventListener('change', ()=>{
    const f = input.files && input.files[0];
    if(!f){ reset(); return; }
    nameEl.textContent=f.name;
    const r = new FileReader();
    r.onload = e=>{
      preview.src=e.target.result;
      box.classList.add('has-file'); clear.style.display='inline-flex';
    };
    r.readAsDataURL(f);
  });
  clear.addEventListener('click', (e)=>{ e.preventDefault(); reset(); });
})();

document.addEventListener('DOMContentLoaded', function () {
  const c = document.getElementById('signPad');
  const ctx = c.getContext('2d');
  const clearBtn = document.getElementById('clearSign');

  clearBtn.addEventListener('click', function () {
    ctx.clearRect(0, 0, c.width, c.height); 
  });
});

</script>
@endpush

@endsection
