@extends('layouts.template')

<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Konfirmasi Berangkat')</title>
</head>

@section('content')
    <div class="position-relative bg-purple text-white" style="height: 100px;">
        @if (session('message'))
            <div class="alert alert-danger">{{ session('message') }}</div>
        @endif
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        <!-- <form action="{{ route('logout') }}" method="POST" style="position: absolute; top: 10px; right: 10px;">
            @csrf
            <button type="submit" class="btn btn-sm btn-light text-dark">
                <i class="bi bi-box-arrow-right"></i> Logout
            </button>
        </form> -->
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
            style=" max-width: 400px; background-color: #f3f3f3; margin:50px auto 0; padding: 16px; border-radius: 12px; box-shadow: 0 4px 8px rgba(0,0,0,0.05);">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="text-muted mb-1" style="font-size: 14px;">Rute</div>
                    <div style="font-weight: bold;">{{ $mappedDetail['route'] ?? '-' }}</div>
                </div>
                <div class="text-center">

                </div>
            </div>
        </div>

        <div class="text-center mt-3 mb-2">
            <span class="text-muted" style="font-weight: 500;">Tanggal-Jam Berangkat</span>
        </div>
        <div class="d-flex justify-content-between gap-2 mb-3" style="max-width: 400px; margin: 0 auto;">
            <div class="bg-light rounded p-3 text-center flex-fill">
                <div id="tanggalKeluar" style="font-weight: bold;">--</div>
            </div>
            <div class="bg-light rounded p-3 text-center flex-fill">
                <div id="jamKeluar" style="font-weight: bold;">--</div>
            </div>
        </div>

        <div class="d-flex align-items-center rounded px-3 py-2" style="width: 100%; max-width: 400px; margin: 0 auto;">
            <div class="text-muted" style="flex: 1;">KM Mobil</div>
            <input type="number" id="kmTake" class="form-control border-0 text-center mx-2" value="100"
                style="max-width: 80px;">
            <div class="fw-bold">KM</div>
        </div>

    </div>

    <div class="slide-confirm-container px-3">
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
            <span class="slide-label text-primary fw-semibold" style="margin-left:56px;">Konfirmasi Berangkat</span>
        </div>
    </div>

    <script>
        let isDragging = false;
        let offsetX = 0;

        function startSlide(e) {
        isDragging = true;

        // cegah halaman scroll saat mulai sentuh
        if (e.type === 'touchstart') e.preventDefault();

        const btn = document.querySelector('.slide-button');
        const clientX = e.touches ? e.touches[0].clientX : e.clientX;

        // supaya tombol tidak "loncat" saat awal geser
        const currentLeft = parseInt(btn.style.left || '0', 10);
        offsetX = clientX - currentLeft;

        document.addEventListener('mousemove', onSlide);
        document.addEventListener('mouseup', stopSlide);
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
            btn.innerHTML = '<i class="bi bi-check-lg" style="font-size:24px; color:purple;"></i>';
        } else {
            btn.style.background = '#ffffff';
            btn.innerHTML = '<i class="bi bi-chevron-double-right text-primary" style="font-size:24px; transform: translateX(8px);"></i>';
        }
        }

        function stopSlide() {
        isDragging = false;

        const btn = document.querySelector('.slide-button');
        const container = document.querySelector('.slide-track');

        const postUrl = @json(route('utama.konfirmasi-berangkat'));
        const nextUrl = @json(route('utama.konfirmasi-tiba-muat', ['orderId' => $mappedDetail['XX_TransOrder_ID'] ?? '']));
        const orderId = @json($mappedDetail['XX_TransOrder_ID'] ?? '');
        const status  = @json($mappedDetail['Status'] ?? '');
        const kmTake  = document.getElementById('kmTake').value || 0;

        const left = parseInt(btn.style.left || '0', 10);
        const threshold = container.clientWidth - btn.clientWidth - 5;

        if (left >= threshold) {
            btn.style.pointerEvents = 'none';
            fetch(postUrl, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            },
            body: JSON.stringify({ orderId, kmTake, status }),
            })
            .then(async (res) => {
            const ct = res.headers.get('content-type') || '';
            const isJson = ct.includes('application/json');
            const data = isJson ? await res.json() : null;
            if (res.ok && isJson && data?.success) {
                window.location.href = data.nextUrl;
            } else if (res.status === 419) {
                alert('Sesi kedaluwarsa (419). Refresh halaman lalu coba lagi.');
                resetSlider();
            } else {
                alert((isJson && data?.message) || `Gagal konfirmasi (HTTP ${res.status}).`);
                resetSlider();
            }
            })
            .catch(() => {
            alert('Kesalahan jaringan.');
            resetSlider();
            })
            .finally(() => {
            btn.style.pointerEvents = '';
            });
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
            btn.innerHTML = '<i class="bi bi-chevron-double-right text-primary" style="font-size:24px; transform: translateX(8px);"></i>';
        }
        }

        // let isDragging = false;
        // let offsetX = 0;

        // // berangkat
        // function startSlide(e) {
        //     isDragging = true;
        //     offsetX = e.clientX || (e.touches && e.touches[0].clientX);
        //     document.addEventListener("mousemove", onSlide);
        //     document.addEventListener("mouseup", stopSlide);
        //     document.addEventListener("touchmove", onSlide);
        //     document.addEventListener("touchend", stopSlide);
        // }

        // function onSlide(e) {
        //     if (!isDragging) return;

        //     const btn = document.querySelector(".slide-button");
        //     const container = document.querySelector(".slide-track");
        //     let clientX = e.clientX || (e.touches && e.touches[0].clientX);
        //     let moveX = clientX - offsetX;
        //     moveX = Math.max(
        //         0,
        //         Math.min(moveX, container.clientWidth - btn.clientWidth)
        //     );
        //     btn.style.left = moveX + "px";

        //     if (moveX >= container.clientWidth - btn.clientWidth - 5) {
        //         btn.style.background = "#198754";
        //         btn.innerHTML =
        //             '<i class="bi bi-check-lg" style="font-size: 24px; color: purple;"></i>';
        //     } else {
        //         btn.style.background = "#ffffff";
        //         btn.innerHTML =
        //             '<i class="bi bi-chevron-double-right text-primary" style="font-size: 24px; transform: translateX(8px);"></i>';
        //     }
        // }

        // function stopSlide(e) {
        //     isDragging = false;

        //     const btn = document.querySelector(".slide-button");
        //     const container = document.querySelector(".slide-track");

        //     const postUrl = @json(route('utama.konfirmasi-berangkat'));
        //     const nextUrl = @json(route('utama.konfirmasi-tiba-muat', ['orderId' => $mappedDetail['XX_TransOrder_ID'] ?? '']));
        //     const orderId = @json($mappedDetail['XX_TransOrder_ID'] ?? '');
        //     const status = @json($mappedDetail['Status'] ?? '');


        //     const kmTake = document.getElementById("kmTake").value || 0;


        //     const left = parseInt(btn.style.left || "0", 10);
        //     const threshold = container.clientWidth - btn.clientWidth - 5;

        //     if (left >= threshold) {
        //         // optional: cegah double submit
        //         btn.style.pointerEvents = "none";

        //         fetch(postUrl, {
        //             method: "POST",
        //             headers: {
        //                 "Content-Type": "application/json",
        //                 "Accept": "application/json",
        //                 "X-Requested-With": "XMLHttpRequest",
        //                 "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute("content"),
        //             },
        //             body: JSON.stringify({
        //                 orderId,
        //                 kmTake: kmTake,
        //                 status,
        //             }),
        //         })
        //             .then(async (res) => {
        //                 const ct = res.headers.get("content-type") || "";
        //                 const isJson = ct.includes("application/json");
        //                 const data = isJson ? await res.json() : null;
        //                 console.log(data, "TEST TEST")
        //                 if (res.ok && isJson && data?.success) {
        //                     window.location.href = data.nextUrl; // pindah ke halaman tiba muat
        //                 } else if (res.status === 419) {
        //                     alert("Sesi kedaluwarsa (419). Refresh halaman lalu coba lagi.");
        //                     resetSlider();
        //                 } else {
        //                     alert((isJson && data?.message) || `Gagal konfirmasi (HTTP ${res.status}).`);
        //                     resetSlider();
        //                 }
        //             })
        //             .catch(() => {
        //                 alert("Kesalahan jaringan.");
        //                 resetSlider();
        //             })
        //             .finally(() => {
        //                 btn.style.pointerEvents = "";
        //             });

        //     } else {
        //         resetSlider();
        //     }

        //     document.removeEventListener("mousemove", onSlide);
        //     document.removeEventListener("mouseup", stopSlide);
        //     document.removeEventListener("touchmove", onSlide);
        //     document.removeEventListener("touchend", stopSlide);

        //     function resetSlider() {
        //         btn.style.left = "0px";
        //         btn.style.background = "#ffffff";
        //         btn.innerHTML =
        //             '<i class="bi bi-chevron-double-right text-primary" style="font-size: 24px; transform: translateX(8px);"></i>';
        //     }
        // }


        function initRealtimeDateTime() {
            function updateDateTime() {
                let now = new Date();

                const bulan = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];
                let tanggal = String(now.getDate()).padStart(2, "0") + " " + bulan[now.getMonth()] + " " + now
                    .getFullYear();
                let jam = String(now.getHours()).padStart(2, "0") + ":" + String(now.getMinutes()).padStart(2, "0");

                // LOAD
                let tanggalElLoad = document.getElementById("tanggalKeluar");
                let jamElLoad = document.getElementById("jamKeluar");
                let hiddenElLoad = document.getElementById("OutLoadDate");

                if (tanggalElLoad) tanggalElLoad.innerText = tanggal;
                if (jamElLoad) jamElLoad.innerText = jam;


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
