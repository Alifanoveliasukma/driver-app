@extends('layouts.template')

<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Order')</title>
</head>

@section('content')
    <div class="history-wrap">
        
        <div class="history-hero">
    </div>

        @foreach ($orders as $i => $o)
            @break($i === 40)

            @php
                $noSp = $o->value ?? '-';
                $etaRaw = $o->eta ?? null;
                $etdRaw = $o->etd ?? null;
                $customerName = $o->customer_name ?? null;
                $route = $o->route ?? null;
                $orderId = $o->xx_transorder_id ?? null;
                $status = $o->status ?? null;

                $etaClean = $etaRaw ? str_replace('.0', '', $etaRaw) : null;
                $etdClean = $etdRaw ? str_replace('.0', '', $etdRaw) : null;

                try {
                    $tglSelesai = $etaClean ? \Carbon\Carbon::parse($etaClean)->translatedFormat('d M Y') : null;
                } catch (\Exception $e) {
                    $tglSelesai = null;
                }

                try {
                    $tglBerangkat = $etdClean ? \Carbon\Carbon::parse($etdClean)->translatedFormat('d M Y') : null;
                } catch (\Exception $e) {
                    $tglBerangkat = null;
                }
            @endphp

            <div class="history-card">

                <div class="row-top">
                    <div>
                        <div class="muted">Surat Jalan</div>
                        <div class="strong">
                            {{ $noSp }}
                            @if ($orderId)
                                <small class="text-muted d-block">ID: {{ $orderId }}</small>
                            @endif
                        </div>
                    </div>

                    <div class="text-end">
                        @if ($tglSelesai)
                            <div class="muted">Tanggal Selesai</div>
                            <a href="#" class="date-link">{{ $tglSelesai }}</a>
                        @endif

                        {{-- ⬇️ Status langsung di bawah tanggal selesai --}}
                        @if ($status)
                            <div class="muted">Status</div>
                             <div class="fw-bold text-danger">
                                {{ $status !== null && $status !== '' ? $status : 'EXECUTE' }}
                            </div>
                        @endif
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

                @if ($tglBerangkat)
                    <div class="pair">
                        <div class="muted">Tanggal Berangkat (ETD)</div>
                        <div class="strong">{{ $tglBerangkat }}</div>
                    </div>
                @endif

                {{-- Tombol Pilih --}}
                @if ($orderId)
                    <div class="text-end">
                        <a class="btn btn-primary btn-sm"
                            href="{{ route('menu.detail-order', ['orderId' => $o->xx_transorder_id]) }}">
                            Pilih
                        </a>
                    </div>
                @endif
            </div>

        @endforeach

    </div>
@endsection