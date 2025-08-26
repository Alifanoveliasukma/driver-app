@extends('layouts.template')

<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Histori Order')</title>
</head>

@section('content')
    <div class="history-wrap">
        <div class="history-hero"> 
          <button class="btn-back" onclick="window.history.back()" aria-label="Kembali">
                <i class="bi bi-chevron-left"></i>
            </button>
        </div>

        @forelse ($orders as $i => $o)
            @break($i === 40)

            @php
                $noSp = data_get($o, 'Value', '-');
                $etaRaw = data_get($o, 'ETA');
                $etdRaw = data_get($o, 'ETD');
                $customerName = data_get($o, 'Customer_Name');
                $route = data_get($o, 'route');
                $orderId = data_get($o, 'XX_TransOrder_ID');
                $status = data_get($o, 'Status');

                $etaClean = $etaRaw ? str_replace('.0', '', $etaRaw) : null;
                $etdClean = $etdRaw ? str_replace('.0', '', $etdRaw) : null;

                try {
                    $tglSelesai = $etaClean ? \Carbon\Carbon::parse($etaClean)->translatedFormat('d M Y') : null;
                } catch (\Exception $e) {
                    $tglSelesai = null;
                }
            @endphp

            <div class="history-card">
                <div class="row-top">
                    <div>
                        <div class="muted">Surat Jalan</div>
                        <div class="strong">
                            {{ $noSp }}
                            <small class="text-muted d-block">ID: {{ $orderId }}</small>
                        </div>
                    </div>

                    <div class="text-end">
                        <div class="muted">Tanggal Selesai</div>
                        <a href="#" class="date-link">{{ $tglSelesai ?? '-' }}</a>

                        <div class="muted">Status</div>
                        <div class="fw-bold text-success">{{ $status ?? '-' }}</div>
                    </div>
                </div>

                @if ($route)
                    <div class="pair">
                        <div class="muted">Route</div>
                        <div class="strong">{{ $route }}</div>
                    </div>
                @endif

                @if ($customerName)
                    <div class="pair">
                        <div class="muted">Customer Name</div>
                        <div class="strong">{{ $customerName }}</div>
                    </div>
                @endif
            </div>
        @empty
            <div class="alert alert-info">Belum ada histori order.</div>
        @endforelse
    </div>
@endsection
