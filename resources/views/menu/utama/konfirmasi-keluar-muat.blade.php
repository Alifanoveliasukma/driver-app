@extends('layouts.template')
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Konfirmasi Keluar Muat')</title>
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
        </div>
    </div>


    <div class="scrollable-content px-3" style="margin-top: 20px; margin-bottom: 100px;">

        <div class="alamat-box"
            style="width: 90%; max-width: 400px; margin-top: 50px; background-color: #f3f3f3; padding: 16px; border-radius: 12px; box-shadow: 0 4px 8px rgba(0,0,0,0.05); ">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="text-muted mb-1" style="font-size: 14px;">Alamat Pengiriman</div>
                    <div style="font-weight: bold;">{{ $mappedDetail['delivery_address'] ?? '-' }}</div>
                    {{-- <div style="font-weight: bold;">Jakarta Utara</div> --}}
                </div>
                <div class="text-center">
                    
                </div>
            </div>
        </div>
        <div class="text-center mt-3 mb-2">
            <span class="text-muted" style="font-weight: 500;">Tanggal-Jam Keluar Tempat Muat</span>
        </div>
        <div class="d-flex justify-content-between gap-2 mb-3" style="max-width: 400px; margin: 0 auto;">
            <div class="bg-light rounded p-3 text-center flex-fill">
                <div id="tanggalKeluarMuat" style="font-weight: bold;">--</div>
            </div>
            <div class="bg-light rounded p-3 text-center flex-fill">
                <div id="jamKeluarMuat" style="font-weight: bold;">--</div>
            </div>
        </div>
        <input type="hidden" name="" id="">

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
            <div class="slide-confirm-container start-0 end-0 px-3" style="bottom: 50px; z-index: 999;">
                <div class="slide-track bg-light rounded shadow-sm d-flex align-items-center justify-content-between px-3 py-2"
                    style="max-width: 400px; margin: 0 auto;">
                    <div class="slide-button bg-white d-flex justify-content-center align-items-center"
                        onmousedown="startSlide(event)" style="width: 48px; height: 48px; border-radius: 0;">
                        <img src="{{ asset('assets/icon/img-right.png') }}" alt="Right Arrow"
                            style="width: 30px; height: 30px; filter: brightness(0) saturate(100%) invert(29%) sepia(94%) saturate(5096%) hue-rotate(202deg) brightness(95%) contrast(94%);">
                    </div>
                    <span class="slide-label text-primary fw-semibold">Konfirmasi Keluar Muat</span>
                </div>
            </div>
        </div>


        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const input = document.getElementById('foto-sopir');
                const box = document.getElementById('fotoBox');
                const preview = document.getElementById('fotoPreview');
                const nameEl = document.getElementById('fotoName');
                const clear = document.getElementById('clearFoto');

                function resetFoto() {
                    input.value = '';
                    preview.src = '';
                    nameEl.textContent = '';
                    box.classList.remove('has-file');
                    if (clear) clear.style.display = 'none';
                }

                input.addEventListener('change', async () => {
                    const file = input.files && input.files[0];

                    if (!file) {
                        resetFoto();
                        return;
                    }

                    nameEl.textContent = file.name;
                    const reader = new FileReader();
                    reader.onload = (e) => {
                        preview.src = e.target.result;
                        box.classList.add('has-file');
                        if (clear) clear.style.display = 'inline-block';
                    };
                    reader.readAsDataURL(file);


                    try {
                        const formData = new FormData();
                        formData.append('foto', file);

                        const res = await fetch('/api/upload-foto', {
                            method: 'POST',
                            body: formData,
                            headers: {
                                // kalau pakai Laravel CSRF
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                    .getAttribute('content')
                            }
                        });

                        const data = await res.json();
                        console.log('Upload sukses:', data);

                        // document.getElementById('fotoPath').value = data.path;
                    } catch (err) {
                        console.error('Upload gagal', err);
                        alert('Gagal upload foto');
                        resetFoto();
                    }
                });

                if (clear) {
                    clear.addEventListener('click', (e) => {
                        e.preventDefault();
                        resetFoto();
                    });
                }
            });
        </script>

        <script>
            let isDragging = false;
            let offsetX = 0;

            // berangkat
            function startSlide(e) {
                isDragging = true;
                offsetX = e.clientX || (e.touches && e.touches[0].clientX);
                document.addEventListener("mousemove", onSlide);
                document.addEventListener("mouseup", stopSlide);
                document.addEventListener("touchmove", onSlide);
                document.addEventListener("touchend", stopSlide);
            }

            function onSlide(e) {
                if (!isDragging) return;

                const btn = document.querySelector(".slide-button");
                const container = document.querySelector(".slide-track");
                let clientX = e.clientX || (e.touches && e.touches[0].clientX);
                let moveX = clientX - offsetX;
                moveX = Math.max(
                    0,
                    Math.min(moveX, container.clientWidth - btn.clientWidth)
                );
                btn.style.left = moveX + "px";

                if (moveX >= container.clientWidth - btn.clientWidth - 5) {
                    btn.style.background = "#198754";
                    btn.innerHTML =
                        '<i class="bi bi-check-lg" style="font-size: 24px; color: purple;"></i>';
                } else {
                    btn.style.background = "#ffffff";
                    btn.innerHTML =
                        '<i class="bi bi-chevron-double-right text-primary" style="font-size: 24px; transform: translateX(8px);"></i>';
                }
            }

            function stopSlide(e) {
                isDragging = false;

                const btn = document.querySelector(".slide-button");
                const container = document.querySelector(".slide-track");

                const postUrl = @json(route('utama.konfirmasi-keluar-muat.submit'));
                const nextUrl = @json(route('utama.konfirmasi-tiba-tujuan', ['orderId' => $mappedDetail['XX_TransOrder_ID'] ?? '']));
                const orderId = @json($mappedDetail['XX_TransOrder_ID'] ?? '');


                const left = parseInt(btn.style.left || "0", 10);
                const threshold = container.clientWidth - btn.clientWidth - 5;

                if (left >= threshold) {
                    // optional: cegah double submit
                    btn.style.pointerEvents = "none";

                    fetch(postUrl, {
                            method: "POST",
                            headers: {
                                "Content-Type": "application/json",
                                "Accept": "application/json",
                                "X-Requested-With": "XMLHttpRequest",
                                "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute("content"),
                            },
                            body: JSON.stringify({
                                orderId
                            }),
                        })
                        .then(async (res) => {
                            const ct = res.headers.get("content-type") || "";
                            const isJson = ct.includes("application/json");
                            const data = isJson ? await res.json() : null;

                            if (res.ok && isJson && data?.success) {
                                window.location.href = data.nextUrl; // pindah ke halaman tiba muat
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
                        '<i class="bi bi-chevron-double-right text-primary" style="font-size: 24px; transform: translateX(8px);"></i>';
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
                    let tanggalElUnload = document.getElementById("tanggalKeluarMuat");
                    let jamElUnload = document.getElementById("jamKeluarMuat");
                    let hiddenElUnload = document.getElementById("UnloadDateStart");

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
