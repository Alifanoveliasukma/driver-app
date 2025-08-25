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
        </div>
    </div>

    <div class="scrollable-content px-3">

        <div class="alamat-box"
            style="width: 90%; max-width: 400px; margin-top: 50px; background-color: #f3f3f3; padding: 16px; border-radius: 12px; box-shadow: 0 4px 8px rgba(0,0,0,0.05);">
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
            <span class="text-muted" style="font-weight: 500;">Tanggal-Jam Selesai Bongkar</span>
        </div>
        <div class="d-flex justify-content-between gap-2 mb-3" style="max-width: 400px; margin: 0 auto;">
            <div class="bg-light rounded p-3 text-center flex-fill">
                <div id="tanggalSelesaiMuat" style="font-weight: bold;">--</div>
            </div>
            <div class="bg-light rounded p-3 text-center flex-fill">
                <div id="jamSelesaiMuat" style="font-weight: bold;">--</div>
            </div>
        </div>
        <input type="hidden" name="UnloadDateEnd" id="UnloadDateEnd">
    </div>

    <!-- Tombol fixed -->
    <div class="position-fixed start-0 end-0 px-3" style="bottom: 80px; z-index: 999;">
        <div class="slide-confirm-container position-fixed start-0 end-0 px-3" style="bottom: 50px; z-index: 999;">
            <div class="slide-track bg-light rounded shadow-sm d-flex align-items-center justify-content-between px-3 py-2"
                style="max-width: 400px; margin: 0 auto;" 
                data-action="{{ route('utama.konfirmasi-selesai-bongkar.submit') }}"
                data-redirect="{{ route('utama.konfirmasi-keluar-bongkar', ['orderId' => $mappedDetail['XX_TransOrder_ID'] ?? '']) }}"
                data-orderid="{{ $mappedDetail['XX_TransOrder_ID'] ?? '' }}">

                <div class="slide-button bg-white d-flex justify-content-center align-items-center"
                    onmousedown="startSlideSelesaiMuat(event)" style="width: 48px; height: 48px; border-radius: 0;">
                    <img src="{{ asset('assets/icon/img-right.png') }}" alt="Right Arrow"
                        style="width: 30px; height: 30px; filter: brightness(0) saturate(100%) invert(29%) sepia(94%) saturate(5096%) hue-rotate(202deg) brightness(95%) contrast(94%);">
                </div>

                <span class="slide-label text-primary fw-semibold">Konfirmasi Selesai Bongkar</span>
            </div>
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
@endsection
