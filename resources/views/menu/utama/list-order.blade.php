@extends('layouts.template')

@section('content')
<div class="history-wrap">
    <div class="history-hero"> </div>

  @foreach($orders as $i => $o)
    @break($i === 5)

    @php
      $noSp        = data_get($o, 'Value', '-');
      $etaRaw      = data_get($o, 'ETA');
      $etdRaw      = data_get($o, 'ETD');
      $customerID  = data_get($o, 'Customer_ID');
      $orderId     = data_get($o, 'XX_TransOrder_ID');

      $etaClean = $etaRaw ? str_replace('.0', '', $etaRaw) : null;
      $etdClean = $etdRaw ? str_replace('.0', '', $etdRaw) : null;

      try { $tglSelesai = $etaClean ? \Carbon\Carbon::parse($etaClean)->translatedFormat('d M Y') : null; }
      catch (\Exception $e) { $tglSelesai = null; }

      try { $tglBerangkat = $etdClean ? \Carbon\Carbon::parse($etdClean)->translatedFormat('d M Y') : null; }
      catch (\Exception $e) { $tglBerangkat = null; }
    @endphp

    <div class="history-card">
      <div class="row-top">
        <div>
          <div class="muted">Surat Jalan</div>
          <div class="strong">
            {{ $noSp }}
            @if($orderId)
              <small class="text-muted d-block">ID: {{ $orderId }}</small>
            @endif
          </div>
        </div>

        @if($tglSelesai)
          <div class="text-end">
            <div class="muted">Tanggal Selesai</div>
            <a href="#" class="date-link">{{ $tglSelesai }}</a>
          </div>
        @endif
      </div>

      @if($customerID)
        <div class="pair">
          <div class="muted">Customer ID</div>
          <div class="strong">{{ $customerID }}</div>
        </div>
      @endif

      @if($tglBerangkat)
        <div class="pair">
          <div class="muted">Tanggal Berangkat (ETD)</div>
          <div class="strong">{{ $tglBerangkat }}</div>
        </div>
      @endif

      {{-- Tombol Pilih --}}
      @if($orderId)
        <div class="mt-3 text-end">
            <a class="btn btn-primary btn-sm"
               href="{{ route('menu.detail-order', ['orderId' => $o['XX_TransOrder_ID']]) }}">
            Pilih
            </a>
        </div>
      @endif
    </div>
  @endforeach

</div>
@endsection
