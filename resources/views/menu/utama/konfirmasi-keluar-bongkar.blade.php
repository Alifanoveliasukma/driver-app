@extends('layouts.template')

<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Konfirmasi Keluar Bongkar')</title>
</head>

@section('content')
    <div class="position-relative bg-purple text-white" style="height: 100px;">
        <!-- Parent ungu sebagai anchor -->
        <div class="floating-box">
            <div class="row-item">
                <span class="label">Surat Jalan</span>
                <span class="value">{{ $mappedDetail['Value'] ?? '-' }}</span>
            </div>
            <div class="row-item">
                <span class="label">Pelanggan</span>
                <span class="value">{{ $mappedDetail['Customer_Name'] ?? '-' }}</span>
            </div>
            <div class="row-item">
                <span class="label">Status</span>
                <span class="value">{{ $mappedDetail['Status'] ?? '-' }}</span>
            </div>
        </div>
    </div>


    <div class="scrollable-content px-3" style="margin-top: 20px; margin-bottom: 100px;">

        <div class="alamat-box"
            style="width: 90%; max-width: 400px; margin-top: 50px; background-color: #f3f3f3; padding: 16px; border-radius: 12px; box-shadow: 0 4px 8px rgba(0,0,0,0.05); ">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="text-muted mb-1" style="font-size: 14px;">Alamat Pengiriman</div>
                    <div style="font-weight: bold;">Gudang Jakarta</div>
                    <div style="font-weight: bold;">Jakarta Utara</div>
                </div>
                <div class="text-center">

                </div>
            </div>
        </div>
        <div class="text-center mt-3 mb-2">
            <span class="text-muted" style="font-weight: 500;">Tanggal-Jam Keluar Tempat Bongkar</span>
        </div>
        <div class="d-flex justify-content-between gap-2 mb-3" style="max-width: 400px; margin: 0 auto;">
            <div class="bg-light rounded p-3 text-center flex-fill">
                <div id="tanggalKeluarBongkar" style="font-weight: bold;">--</div>
            </div>
            <div class="bg-light rounded p-3 text-center flex-fill">
                <div id="jamKeluarBongkar" style="font-weight: bold;">--</div>
            </div>
        </div>
        <input type="hidden" name="UnloadStd" id="UnloadStd">
        <div class="section-divider my-3">
            <span>DO - SPJ Detail</span>
        </div>

        <div class="form-rows">
            <div class="unit-row">
                <label for="total_tonase">Tonase</label>
                <div class="unit-input">
                    <input type="number" style="text-align: right" id="total_tonase" name="total_tonase"
                        value="{{$mappedDetail['Tonnage']}}" disabled>
                    <span class="unit">Tonase</span>
                </div>
            </div>

            <div class="unit-row">
                <label for="biaya_tonase">Biaya Tonase</label>
                <div class="unit-input">
                    <input type="number" style="text-align: right" id="biaya_tonase" name="TonnageCost"
                        value="{{$mappedDetail['TonnageCost']}}" disabled>
                </div>
            </div>

            <div class="unit-row">
                <label for="total_kubikasi">Penjualan Tonase</label>
                <div class="unit-input">
                    <input type="number" style="text-align: right" id="penjualan_tonase" name="TonnageSales"
                        value="{{$mappedDetail['TonnageSales']}}" disabled>
                </div>
            </div>
        </div>

        <hr>
        </hr>

        <div class="form-rows" style="max-width:400px;margin:0 auto;">
            <div class="section-label">eSigning</div>
            <div class="sign-box">
                <canvas id="signPad" width="360" height="150"></canvas>
            </div>
            <button id="uploadSign">Upload Sign</button>
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

            {{-- <div class="foto-upload-wrapper mt-3">
                <label for="fotoSopir" class="foto-upload-box" id="fotoBox">
                    <i class="bi bi-camera-fill icon"></i>
                    <span class="placeholder">Foto Sopir</span>
                    <img id="fotoPreview" class="preview" alt="Preview foto" />
                    <span id="fotoName" class="filename"></span>
                    <input type="file" id="fotoSopir" accept="image/*" capture="environment" hidden>
                </label>
                <button type="button" class="btn-delete-foto" id="clearFoto" style="display:none;">
                    <i class="bi bi-trash"></i> Hapus Foto
                </button>
            </div> --}}

            <!-- <div class="inline-input mt-3">
                                                                                    <label for="noDo">NO DO-SPJ Order</label>
                                                                                    <input id="noDo" type="text" class="form-control">
                                                                                </div> -->
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
            <div class="slide-track bg-light rounded shadow-sm d-flex align-items-center justify-content-between px-3 py-2"
            style="max-width:400px; margin:0 auto; position:relative;">
            <div class="slide-button bg-white d-flex justify-content-center align-items-center"
                onmousedown="startSlide(event)"
                ontouchstart="startSlide(event)"   
                style="width:48px;height:48px;border-radius:0; position:absolute; left:0; top:50%; transform:translateY(-50%);
                        touch-action:none; -webkit-user-select:none; user-select:none;">
            <img src="{{ asset('assets/icon/img-right.png') }}" alt="Right Arrow"
                style="width:30px;height:30px; pointer-events:none;" draggable="false">
            </div>
            <span class="slide-label text-primary fw-semibold" style="margin-left:56px;">Konfirmasi Keluar Bongkar</span>
            </div>
        </div>
        <script>
            let signPath = "";
            let fotoDocPath = "";
            document.addEventListener('DOMContentLoaded', function () {
               
                (function () {
                    const c = document.getElementById('signPad');
                    if (!c) return;
                    const ctx = c.getContext('2d');
                    let drawing = false,
                        last = null;

                    const pos = (e) => {
                        const r = c.getBoundingClientRect();
                        const x = (e.touches ? e.touches[0].clientX : e.clientX) - r.left;
                        const y = (e.touches ? e.touches[0].clientY : e.clientY) - r.top;
                        return {
                            x,
                            y
                        };
                    };
                    const start = (e) => {
                        drawing = true;
                        last = pos(e);
                        e.preventDefault();
                    };
                    const move = (e) => {
                        if (!drawing) return;
                        const p = pos(e);
                        ctx.lineWidth = 2;
                        ctx.lineCap = 'round';
                        ctx.strokeStyle = '#000';
                        ctx.beginPath();
                        ctx.moveTo(last.x, last.y);
                        ctx.lineTo(p.x, p.y);
                        ctx.stroke();
                        last = p;
                        e.preventDefault();
                    };
                    const end = () => {
                        drawing = false;
                    };

                    c.addEventListener('mousedown', start);
                    c.addEventListener('mousemove', move);
                    window.addEventListener('mouseup', end);
                    c.addEventListener('touchstart', start, {
                        passive: false
                    });
                    c.addEventListener('touchmove', move, {
                        passive: false
                    });
                    c.addEventListener('touchend', end);

                    const clearBtn = document.getElementById('clearSign');
                    if (clearBtn) clearBtn.addEventListener('click', () => ctx.clearRect(0, 0, c.width, c.height));

                    // === Tombol upload/sign submit ===
                    const uploadBtn = document.getElementById('uploadSign');
                    if (uploadBtn) {
                        uploadBtn.addEventListener('click', async () => {
                            // ambil isi canvas sebagai data URL
                            const dataURL = c.toDataURL('image/png');
                            // konversi ke blob agar bisa diupload layaknya file
                            const blob = await (await fetch(dataURL)).blob();
                            const formData = new FormData();
                            formData.append('foto', blob, 'signature.png');
                            formData.append('folder', 'signature');

                            try {
                                const res = await fetch('/api/upload-foto', {
                                    method: 'POST',
                                    body: formData,
                                    headers: {
                                        'X-CSRF-TOKEN': document.querySelector(
                                            'meta[name="csrf-token"]')
                                            .getAttribute('content')
                                    }
                                });

                                if (!res.ok) throw new Error('Gagal upload tanda tangan');
                                const data = await res.json();
                                console.log('Upload tanda tangan sukses:', data);
                                signPath = data;
                                window.alert("Tanda Tangan Tersimpan");

                            } catch (err) {
                                console.error(err);
                                alert('Upload tanda tangan gagal', err);
                            }
                        });
                    }
                })();

                // === GENERIC FILE UPLOAD PREVIEW + UPLOAD ===
                function setupFilePreview(opts) {
                    const input = document.getElementById(opts.inputId);
                    const preview = document.getElementById(opts.previewId);
                    const placeholder = opts.placeholderId ? document.getElementById(opts.placeholderId) : null;
                    const removeBtn = opts.removeBtnId ? document.getElementById(opts.removeBtnId) : null;
                    const nameEl = opts.nameElId ? document.getElementById(opts.nameElId) : null;
                    const box = opts.boxId ? document.getElementById(opts.boxId) : null;
                    if (!input) return;

                    function reset() {
                        input.value = '';
                        if (preview) {
                            preview.src = '';
                            preview.style.display = 'none';
                        }
                        if (placeholder) placeholder.style.display = 'flex';
                        if (removeBtn) removeBtn.style.display = 'none';
                        if (nameEl) nameEl.textContent = '';
                        if (box) box.classList.remove('has-file');
                    }

                    async function uploadFile(file, folderName = 'default') {
                        const formData = new FormData();
                        formData.append('foto', file);
                        formData.append('folder', folderName);
                        try {
                            const res = await fetch('/api/upload-foto', {
                                method: 'POST',
                                body: formData,
                                headers: {
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                        .getAttribute('content')
                                }
                            });
                            if (!res.ok) throw new Error('Upload gagal: ' + res.status);
                            const data = await res.json();
                            console.log('Upload sukses:', data);
                            fotoDocPath = data;
                            window.alert("Foto Dokumen Tersimpan");
                        } catch (err) {
                            console.error(err);
                            alert('Gagal upload file');
                        }
                    }


                    input.addEventListener('change', () => {
                        const f = input.files && input.files[0];
                        if (!f) {
                            reset();
                            return;
                        }
                        if (nameEl) nameEl.textContent = f.name;
                        const r = new FileReader();
                        r.onload = e => {
                            if (preview) {
                                preview.src = e.target.result;
                                preview.style.display = 'block';
                            }
                            if (placeholder) placeholder.style.display = 'none';
                            if (removeBtn) removeBtn.style.display = 'inline-flex';
                            if (box) box.classList.add('has-file');
                            uploadFile(f, opts.folder || 'default');

                        };
                        r.readAsDataURL(f);
                    });
                    if (removeBtn) removeBtn.addEventListener('click', e => {
                        e.preventDefault();
                        reset();
                    });
                }


                setupFilePreview({
                    inputId: 'docFile',
                    previewId: 'docPreview',
                    placeholderId: 'docPh',
                    removeBtnId: 'removeDoc',
                    folder: 'document'
                });


                setupFilePreview({
                    inputId: 'fotoSopir',
                    previewId: 'fotoPreview',
                    nameElId: 'fotoName',
                    boxId: 'fotoBox',
                    removeBtnId: 'clearFoto',
                    folder: 'foto-sopir'
                });

            });
            let isDragging = false;
let offsetX = 0;

function startSlide(e) {
  isDragging = true;

  // cegah halaman ikut scroll saat touch
  if (e.type === 'touchstart') e.preventDefault();

  const btn = document.querySelector('.slide-button');
  const clientX = e.touches ? e.touches[0].clientX : e.clientX;

  // agar tidak "loncat" saat mulai geser
  const currentLeft = parseInt(btn.style.left || '0', 10);
  offsetX = clientX - currentLeft;

  document.addEventListener('mousemove', onSlide);
  document.addEventListener('mouseup', stopSlide);
  // passive:false agar preventDefault() di touchmove bisa bekerja
  document.addEventListener('touchmove', onSlide, { passive: false });
  document.addEventListener('touchend', stopSlide);
}

function onSlide(e) {
  if (!isDragging) return;
  if (e.type === 'touchmove') e.preventDefault();

  const btn = document.querySelector('.slide-button');
  const container = document.querySelector('.slide-track');
  const clientX = e.touches ? e.touches[0].clientX : e.clientX;

  let moveX = clientX - offsetX;
  moveX = Math.max(0, Math.min(moveX, container.clientWidth - btn.clientWidth));
  btn.style.left = moveX + 'px';

  if (moveX >= container.clientWidth - btn.clientWidth - 5) {
    btn.style.background = '#198754';
    btn.innerHTML = '<i class="bi bi-check-lg" style="font-size:24px; color: purple;"></i>';
  } else {
    btn.style.background = '#ffffff';
    btn.innerHTML = '<i class="bi bi-chevron-double-right text-primary" style="font-size:24px; transform: translateX(8px);"></i>';
  }
}

function stopSlide(e) {
  isDragging = false;

  const btn = document.querySelector(".slide-button");
  const container = document.querySelector(".slide-track");

  const postUrl = @json(route('utama.konfirmasi-keluar-bongkar.submit'));
  const nextUrl = @json(route('menu.list-order'));
  const orderId = @json($mappedDetail['XX_TransOrder_ID'] ?? '');

  const left = parseInt(btn.style.left || "0", 10);
  const threshold = container.clientWidth - btn.clientWidth - 5;

  if (left >= threshold) {
    // optional: cegah double submit (pakai validasi lama)
    if (fotoDocPath.length != 0 && signPath.length != 0) {
      btn.style.pointerEvents = "none";
      console.log({
        orderId,
        signPath: signPath?.path,
        fotoDocPath: fotoDocPath?.path
      });

      fetch(postUrl, {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
          "Accept": "application/json",
          "X-Requested-With": "XMLHttpRequest",
          "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute("content"),
        },
        body: JSON.stringify({
          orderId,
          signPath: signPath?.path,
          fotoDocPath: fotoDocPath?.path
        }),
      })
      .then(async (res) => {
        const ct = res.headers.get("content-type") || "";
        const isJson = ct.includes("application/json");
        const data = isJson ? await res.json() : null;

        if (res.ok && isJson && data?.success) {
          window.location.href = data.nextUrl ?? nextUrl; // tetap aman kalau backend tidak kirim nextUrl
        } else if (res.status === 419) {
          alert("Sesi kedaluwarsa (419). Refresh halaman lalu coba lagi.");
          resetSlider();
        } else {
          alert((isJson && data?.message) || `Gagal konfirmasi (HTTP ${res.status}).`);
          resetSlider();
        }
      })
      .catch(() => {
        alert("Kesalahan jaringan.");
        resetSlider();
      })
      .finally(() => {
        btn.style.pointerEvents = "";
      });

    } else {
      window.alert("Tandatangan dan Foto dokumen Harus diisi");
      resetSlider();
    }

  } else {
    resetSlider();
  }

  document.removeEventListener("mousemove", onSlide);
  document.removeEventListener("mouseup", stopSlide);
  document.removeEventListener("touchmove", onSlide);
  document.removeEventListener("touchend", stopSlide);

  function resetSlider() {
    btn.style.left = "0px";
    btn.style.background = "#ffffff";
    btn.innerHTML =
      '<i class="bi bi-chevron-double-right text-primary" style="font-size:24px; transform: translateX(8px);"></i>';
  }
}


            function initRealtimeDateTime() {
                function updateDateTime() {
                    let now = new Date();

                    const bulan = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];
                    let tanggal = String(now.getDate()).padStart(2, "0") + " " + bulan[now.getMonth()] + " " + now
                        .getFullYear();
                    let jam = String(now.getHours()).padStart(2, "0") + ":" + String(now.getMinutes()).padStart(2, "0");


                    // UNLOAD
                    let tanggalElUnload = document.getElementById("tanggalKeluarBongkar");
                    let jamElUnload = document.getElementById("jamKeluarBongkar");
                    let hiddenElUnload = document.getElementById("UnloadStd");

                    if (tanggalElUnload) tanggalElUnload.innerText = tanggal;
                    if (jamElUnload) jamElUnload.innerText = jam;




                    // format SQL datetime
                    let yyyy = now.getFullYear();
                    let mm = String(now.getMonth() + 1).padStart(2, "0");
                    let dd = String(now.getDate()).padStart(2, "0");
                    let HH = String(now.getHours()).padStart(2, "0");
                    let ii = String(now.getMinutes()).padStart(2, "0");
                    let ss = String(now.getSeconds()).padStart(2, "0");
                    let formatted = `${yyyy}-${mm}-${dd} ${HH}:${ii}:${ss}`;

                    if (hiddenElUnload) hiddenElUnload.value = formatted;

                }

                setInterval(updateDateTime, 1000);
                updateDateTime();
            }


            document.addEventListener("DOMContentLoaded", initRealtimeDateTime);
        </script>
@endsection