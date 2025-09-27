@extends('layouts.template')

<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Konfirmasi Selesai Muat')</title>
</head>

@section('content')
    <div class="position-relative bg-purple text-white" style="height: 100px;">
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

    <div class="scrollable-content px-3">

        <div class="alamat-box"
            style="width: 90%; max-width: 400px; margin-top: 50px; background-color: #f3f3f3; padding: 16px; border-radius: 12px; box-shadow: 0 4px 8px rgba(0,0,0,0.05);">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="text-muted mb-1" style="font-size: 14px;">Alamat Pengiriman</div>
                    <div style="font-weight: bold;">{{ $mappedDetail['delivery_address'] ?? '-' }}</div>

                </div>
                <div class="text-center">

                </div>
            </div>
        </div>

        <div class="text-center mt-3 mb-2">
            <span class="text-muted" style="font-weight: 500;">Tanggal-Jam Selesai Muat</span>
        </div>

        <div class="d-flex justify-content-between gap-2 mb-3" style="max-width: 400px; margin: 0 auto;">
            <div class="bg-light rounded p-3 text-center flex-fill">
                <div id="tanggalSelesaiMuat" style="font-weight: bold;">--</div>
            </div>
            <div class="bg-light rounded p-3 text-center flex-fill">
                <div id="jamSelesaiMuat" style="font-weight: bold;">--</div>
            </div>
        </div>
        <input type="hidden" name="LoadDateEnd" id="LoadDateEnd">

        <div class="foto-upload-wrapper mt-3">
            <label for="fotoSopir" class="foto-upload-box" id="fotoBox">
                <i class="bi bi-camera-fill icon"></i>
                <span class="placeholder">Foto Sopir</span>
                <img id="fotoPreview" class="preview" alt="Preview foto" />
                <span id="fotoName" class="filename"></span>
                <input type="file" required id="fotoSopir" accept="image/*" capture="environment" hidden>
            </label>
            <button type="button" class="btn-delete-foto" id="clearFoto" style="display:none;">
                <i class="bi bi-trash"></i> Hapus Foto
            </button>
        </div>

        <div class="section-label mt-3">Upload Dokumen</div>

        <div class="doc-card" id="docCard">
            <span class="doc-title">Surat Jalan</span>
            <button type="button" class="doc-remove" id="removeDoc" style="display:none;">
                <i class="bi bi-x-lg"></i>
            </button>

            <label for="docFile" class="doc-placeholder" id="docPh">
                <i class="bi bi-camera-fill"></i>
                <span>Upload Foto Surat Jalan</span>
            </label>
            <input type="file" required id="docFile" accept="image/*" hidden>

            <img id="docPreview" class="doc-preview" alt="Preview dokumen" style="display:none;">
        </div>

    </div>

    <!-- Tombol fixed -->
    <div class="slide-confirm-container px-3">
        <div class="slide-track bg-light rounded shadow-sm d-flex align-items-center justify-content-between px-3 py-2"
            style="max-width:400px; margin:0 auto; position:relative;">
            <div class="slide-button bg-white d-flex justify-content-center align-items-center"
                onmousedown="startSlide(event)" ontouchstart="startSlide(event)"
                style="width:48px;height:48px;border-radius:0; position:absolute; left:0; top:50%; transform:translateY(-50%);
                        touch-action:none; -webkit-user-select:none; user-select:none;">
                <img src="{{ asset('assets/icon/img-right.png') }}" alt="Right Arrow"
                    style="width:30px;height:30px; pointer-events:none;" draggable="false">
            </div>
            <span class="slide-label text-primary fw-semibold" style="margin-left:56px;">Konfirmasi Selesai Muat</span>
        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('focusin', (e) => {
                if (e.target.matches('input, textarea, select')) {
                    setTimeout(() => {
                        e.target.scrollIntoView({
                            behavior: 'smooth',
                            block: 'center'
                        });
                    }, 100);
                }
            });
        </script>
    @endpush

    <script>
        let isDragging = false;
        let offsetX = 0;
        let fotoSupir, fotoSupirPath = "";
        let dokumenFile, dokumenFilePath = "";
        // FILE PREVIEW
        // FILE PREVIEW
        function setupFilePreview(opts) {
            const input = document.getElementById(opts.inputId);
            const preview = document.getElementById(opts.previewId);
            const removeBtn = document.getElementById(opts.removeBtnId);
            const box = document.getElementById(opts.boxId);
            const fileName = document.getElementById(opts.fileNameId);

            if (!input) return;

            function reset() {
                input.value = '';
                if (opts.type === 'foto') fotoSupir = null;
                if (opts.type === 'dokumen') dokumenFile = null;

                if (preview) {
                    preview.src = '';
                    preview.style.display = 'none';
                }
                if (fileName) fileName.innerText = '';
                if (removeBtn) removeBtn.style.display = 'none';
                if (box) box.classList.remove('has-file');
            }


            input.addEventListener('change', () => {
                const f = input.files && input.files[0];
                if (!f) {
                    reset();
                    return;
                }
                const r = new FileReader();
                r.onload = e => {
                    if (preview) {
                        preview.src = e.target.result;
                        preview.style.display = 'block';
                    }
                    if (fileName) fileName.innerText = f.name;
                    if (removeBtn) removeBtn.style.display = 'inline-flex';
                    if (box) box.classList.add('has-file');
                };
                r.readAsDataURL(f);


                if (opts.type === 'foto') fotoSupir = f;
                if (opts.type === 'dokumen') dokumenFile = f;
            });

            if (removeBtn) removeBtn.addEventListener('click', e => {
                e.preventDefault();
                reset();
            });
        }

        setupFilePreview({
            inputId: 'fotoSopir',
            previewId: 'fotoPreview',
            removeBtnId: 'clearFoto',
            boxId: 'fotoBox',
            fileNameId: 'fotoName',
            type: 'foto'
        });


        setupFilePreview({
            inputId: 'docFile',
            previewId: 'docPreview',
            removeBtnId: 'removeDoc',
            boxId: 'docPh',
            fileNameId: null, // optional
            type: 'dokumen'
        });

        // mulai geser
        function startSlide(e) {
            isDragging = true;

            // cegah halaman ikut scroll saat mulai sentuh
            if (e.type === 'touchstart') e.preventDefault();

            const btn = document.querySelector('.slide-button');
            const clientX = e.touches ? e.touches[0].clientX : e.clientX;

            // agar tidak "loncat" saat mulai geser
            const currentLeft = parseInt(btn.style.left || '0', 10);
            offsetX = clientX - currentLeft;

            document.addEventListener('mousemove', onSlide);
            document.addEventListener('mouseup', stopSlide);
            document.addEventListener('touchmove', onSlide, {
                passive: false
            }); // penting
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
                btn.innerHTML = '<i class="bi bi-check-lg" style="font-size:24px; color:purple;"></i>';
            } else {
                btn.style.background = '#ffffff';
                btn.innerHTML =
                    '<i class="bi bi-chevron-double-right text-primary" style="font-size:24px; transform: translateX(8px);"></i>';
            }
        }

        async function stopSlide() {
            isDragging = false;

            const btn = document.querySelector('.slide-button');
            const container = document.querySelector('.slide-track');


            const postUrl = @json(route('utama.konfirmasi-selesai-muat.submit'));
            const nextUrl = @json(route('utama.konfirmasi-tiba-tujuan', ['orderId' => $mappedDetail['XX_TransOrder_ID'] ?? '']));
            const orderId = @json($mappedDetail['XX_TransOrder_ID'] ?? '');

            const left = parseInt(btn.style.left || '0', 10);
            const threshold = container.clientWidth - btn.clientWidth - 5;

            if (left >= threshold) {
                if (!fotoSupir) {
                    alert("Harap upload foto sopir terlebih dahulu sebelum konfirmasi.");
                    resetSlider();
                    return;
                }
                if (!dokumenFile) {
                    alert("Harap upload dokumen file terlebih dahulu sebelum konfirmasi.");
                    resetSlider();
                    return;
                }
                // cegah double submit
                btn.style.pointerEvents = 'none';

                try {
                    // === Upload foto sopir ===

                    if (fotoSupir) {
                        const fd2 = new FormData();
                        fd2.append('foto', fotoSupir);
                        fd2.append('folder', 'supir');

                        const fotoRes = await fetch('/api/upload-foto', {
                            method: 'POST',
                            body: fd2,
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                                    'content')
                            }
                        });

                        if (!fotoRes.ok) {
                            throw new Error(`Gagal upload foto (HTTP ${fotoRes.status})`);
                        }

                        const fotoData = await fotoRes.json();
                        fotoSupirPath = fotoData;

                    }

                    if (dokumenFile) {
                        const fdDoc = new FormData();
                        fdDoc.append('foto', dokumenFile);
                        fdDoc.append('folder', 'dokumen');
                        const resDoc = await fetch('/api/upload-foto', {
                            method: 'POST',
                            body: fdDoc,
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                                    'content')
                            }
                        });
                        if (!resDoc.ok) throw new Error(`Gagal upload dokumen (HTTP ${resDoc.status})`);
                        const dataDoc = await resDoc.json();
                        dokumenFilePath = dataDoc.path;
                    }


                    // === Submit konfirmasi ===
                    const resp = await fetch(postUrl, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                                'content'),
                        },
                        body: JSON.stringify({
                            orderId,
                            fotoSupirPath: fotoSupirPath?.path ?? null,
                            dokumenFilePath: dokumenFilePath?.path ?? null,
                        }),
                    });

                    const ct = resp.headers.get('content-type') || '';
                    const isJson = ct.includes('application/json');
                    const data = isJson ? await resp.json() : null;
                    // console.log(data, "TEST TEST")
                    if (resp.ok && isJson && data?.success) {
                        window.location.href = data.nextUrl ?? nextUrl;
                    } else if (resp.status === 419) {
                        alert('Sesi kedaluwarsa (419). Refresh halaman lalu coba lagi.');
                        resetSlider();
                    } else {
                        alert((isJson && data?.message) || `Gagal konfirmasi (HTTP ${resp.status}).`);
                        resetSlider();
                    }

                } catch (err) {
                    console.error(err);
                    alert(err.message || 'Kesalahan jaringan / upload.');
                    resetSlider();
                } finally {
                    btn.style.pointerEvents = '';
                }


            } else {
                resetSlider();
            }

            document.removeEventListener('mousemove', onSlide);
            document.removeEventListener('mouseup', stopSlide);
            document.removeEventListener('touchmove', onSlide);
            document.removeEventListener('touchend', stopSlide);

            function resetSlider() {
                btn.style.left = '0px';
                btn.style.background = '#ffffff';
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
                let tanggalElUnload = document.getElementById("tanggalSelesaiMuat");
                let jamElUnload = document.getElementById("jamSelesaiMuat");
                let hiddenElLoad = document.getElementById("LoadDate");

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

                if (hiddenElLoad) hiddenElLoad.value = formatted;
                // if (hiddenSelesaiMuat) hiddenSelesaiMuat.value = formatted;
            }

            setInterval(updateDateTime, 1000);
            updateDateTime();
        }


        document.addEventListener("DOMContentLoaded", initRealtimeDateTime);
    </script>
@endsection
