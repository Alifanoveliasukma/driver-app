@extends('layouts.template')

@section('content')
    <div class="profile-wrap">

        <div class="profile-hero">
            <button class="btn-back" onclick="window.history.back()" aria-label="Kembali">
                <i class="bi bi-chevron-left"></i>
            </button>

            <div class="hero-row">
                <div class="avatar">
                    <img src="https://via.placeholder.com/72x72.png?text=A" alt="Avatar">
                </div>
                <div class="hero-info">
                    <a href="#" class="driver-name strong text-light">{{ $data['name'] }}</a>
                    <div class="sim-line">
                        {{ $data['accountNo'] }}
                    </div>
                </div>
            </div>
        </div>

        <div class="section-panel mt-3 gap-1 d-flex flex-column">
            <div class="tile">
                <div class="strong mb-1">Note</div>
                <div class="addr-text">
                    <div class="muted">{{$data['note']}}</div>
                </div>
            </div>
            <div class="tile">
                <div class="muted">Fleet</div>
                <div class="strong">{{$data['fleetId']}}</div>
            </div>
            <div class="tile">
                <div class="muted">Krani</div>
                <div class="strong">{{$data['kraniId']}}</div>
            </div>
        </div>

        <form action="{{ route('logout') }}" method="POST" class="tile mt-3">
            @csrf
            <button type="submit" class="btn btn-outline-danger btn-lg btn-block" style="width: 100%;">
                <i class="bi bi-box-arrow-right"></i> Logout
            </button>
        </form>

        {{-- <button type="button" class="btn-sim-alert w-100">
            <i class="bi bi-exclamation-triangle-fill me-1"></i>
            Segera Perpanjang SIM
        </button> --}}

        {{-- <div class="section-panel mt-3">
            <div class="section-title">Order Berjalan</div>

            <div class="grid-2">
                <div class="tile">
                    <div class="muted">Surat Jalan</div>
                    <div class="strong">FTL/2022/07/02832</div>
                </div>
                <div class="tile">
                    <div class="muted">Customer</div>
                    <div class="strong">PT. SUKSES SEJAHTERA</div>
                </div>

                <div class="tile">
                    <div class="muted">Tanggal Pengiriman</div>
                    <div class="strong">30 Jul 2022 <span class="sub">11:30</span></div>
                </div>
                <div class="tile">
                    <div class="muted">Uang Jalan</div>
                    <div class="strong">590.000</div>
                </div>

                <div class="tile">
                    <div class="muted">No Pol</div>
                    <div class="strong">B98760KT</div>
                </div>
                <div class="tile">
                    <div class="muted">Cc Driver</div>
                    <div class="strong">—</div>
                </div>
            </div>
        </div> --}}

        {{-- <div class="section-panel mt-3">
            <div class="muted mb-1">Asal</div>
            <div class="addr-row">
                <div class="addr-text">
                    <div class="strong">Pabrik Pulogadung</div>
                    <div class="muted">Jl. Pulo gadung no. 44</div>
                </div>
                <a href="#" class="btn-map">Lihat Peta</a>
            </div>
        </div> --}}

    </div>
@endsection