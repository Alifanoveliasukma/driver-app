@extends('layouts.template')

@section('content')
    <div class="position-relative bg-purple text-white" style="height: 100px;">
        <form action="{{ route('logout') }}" method="POST" style="position: absolute; top: 10px; right: 10px;">
            @csrf
            <button type="submit" class="btn btn-sm btn-light text-dark">
                <i class="bi bi-box-arrow-right"></i> Logout
            </button>
        </form>
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


    <div class="scrollable-content px-3">

        <div class="alamat-box"
            style=" max-width: 400px; background-color: #f3f3f3; margin:50px auto 0; padding: 16px; border-radius: 12px; box-shadow: 0 4px 8px rgba(0,0,0,0.05);">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="text-muted mb-1" style="font-size: 14px;">Alamat Pengiriman</div>
                    <div style="font-weight: bold;">Gudang Jakarta</div>
                </div>
                <div class="text-center">
                    <!-- <div class="bg-primary text-white rounded p-2 d-flex flex-column align-items-center justify-content-center" style="width: 80px; height: 80px;">
                                                                          <i class="bi bi-geo-alt-fill" style="font-size: 24px;"></i>
                                                                          <small>Lihat Peta</small>
                                                                        </div> -->
                </div>
            </div>
        </div>

        <div class="text-center mt-3 mb-2">
            <span class="text-muted" style="font-weight: 500;">Tanggal-Jam Keluar Tempat Muat</span>
        </div>
        <div class="d-flex justify-content-between gap-2 mb-3" style="max-width: 400px; margin: 0 auto;">
            <div class="bg-light rounded p-3 text-center flex-fill">
                <div id="tanggalKeluar" style="font-weight: bold;">--</div>
            </div>
            <div class="bg-light rounded p-3 text-center flex-fill">
                <div id="jamKeluar" style="font-weight: bold;">--</div>
            </div>
        </div>
        <input type="hidden" name="OutLoadDate" id="OutLoadDate">

        <div class="d-flex align-items-center rounded px-3 py-2" style="width: 100%; max-width: 400px; margin: 0 auto;">
            <div class="text-muted" style="flex: 1;">KM Mobil</div>
            <input type="number" class="form-control border-0 text-center mx-2" value="100" style="max-width: 80px;">
            <div class="fw-bold">KM</div>
        </div>

    </div>

    <div class="slide-confirm-container px-3">
        <div class="slide-track bg-light rounded shadow-sm d-flex align-items-center justify-content-between px-3 py-2"
            style="max-width: 400px; margin: 0 auto;" data-redirect="{{ route('utama.konfirmasi-tiba-muat') }}">
            <div class="slide-button bg-white d-flex justify-content-center align-items-center"
                onmousedown="startSlide(event)" style="width:48px;height:48px;border-radius:0;">
                <img src="{{ asset('assets/icon/img-right.png') }}" alt="Right Arrow"
                    style="width:30px;height:30px;filter:brightness(0) saturate(100%) invert(29%) sepia(94%) saturate(5096%) hue-rotate(202deg) brightness(95%) contrast(94%);">
            </div>
            <span class="slide-label text-primary fw-semibold">Konfirmasi Berangkat</span>
        </div>
    </div>
    <script>
        let isSliding = false;
        let startX = 0;
        let button = null;
        let track = null;

        function startSlide(e) {
            e.preventDefault();
            button = e.target.closest('.slide-button');
            track = button.closest('.slide-track');
            isSliding = true;
            startX = e.clientX || e.touches?.[0].clientX;

            document.addEventListener('mousemove', slideMove);
            document.addEventListener('mouseup', endSlide);
            document.addEventListener('touchmove', slideMove);
            document.addEventListener('touchend', endSlide);
        }

        function slideMove(e) {
            if (!isSliding) return;

            let currentX = e.clientX || e.touches?.[0].clientX;
            let deltaX = currentX - startX;

            let maxSlide = track.offsetWidth - button.offsetWidth - 10; // sedikit jarak
            if (deltaX < 0) deltaX = 0;
            if (deltaX > maxSlide) deltaX = maxSlide;

            button.style.transform = `translateX(${deltaX}px)`;

            // Jika sudah sampai ujung
            if (deltaX >= maxSlide) {
                endSlide();
                triggerConfirm();
            }
        }

        function endSlide() {
            if (!isSliding) return;
            isSliding = false;

            document.removeEventListener('mousemove', slideMove);
            document.removeEventListener('mouseup', endSlide);
            document.removeEventListener('touchmove', slideMove);
            document.removeEventListener('touchend', endSlide);

            // reset posisi kalau belum sampai ujung
            button.style.transform = 'translateX(0px)';
        }

        function triggerConfirm() {

            const now = new Date();
            const formatted = now.getFullYear() + '-' +
                String(now.getMonth() + 1).padStart(2, '0') + '-' +
                String(now.getDate()).padStart(2, '0') + ' ' +
                String(now.getHours()).padStart(2, '0') + ':' +
                String(now.getMinutes()).padStart(2, '0') + ':' +
                String(now.getSeconds()).padStart(2, '0');
            document.getElementById('OutLoadDate').value = formatted;


            const url = track.dataset.redirect;


            const orderId = "{{ $mappedDetail['XX_TransOrder_ID'] ?? '' }}";

            fetch(url, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        OutLoadDate: formatted,
                        orderId: orderId
                    })
                })
                .then(res => res.json())
                .then(data => {

                    console.log(data, "TEST ITNG")
                    if (data.success) {
                        alert('Berhasil konfirmasi berangkat');

                    } else {
                        alert('Gagal konfirmasi!');
                    }
                })
                .catch(err => console.error(err));
        }
    </script>
@endsection
