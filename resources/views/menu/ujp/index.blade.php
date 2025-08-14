@extends('layouts.template')

@section('content')
<div class="ujp-wrap">
  <div class="history-hero">
    <button class="btn-back" onclick="window.history.back()" aria-label="Kembali">
      <i class="bi bi-chevron-left"></i>
    </button>
  </div>

  <button type="button" class="btn-ujp w-100">Uang Jalan Pengemudi</button>

  <div class="card-ujp">
    <div class="d-flex justify-content-between align-items-start mb-2">
      <div class="small text-muted">Surat Jalan<br>
        <span class="fw-bold text-dark">FTL/2022/08/00266</span>
      </div>
      <div class="text-end small text-muted">Tanggal Pengiriman<br>
        <a class="fw-bold link-date" href="#">26 Agu 2022</a>
      </div>
    </div>

    <div class="mb-2">
      <div class="small text-muted">Customer</div>
      <div class="fw-bold">PT. GARUDA FOOD TEST</div>
    </div>

    <div class="mb-2">
      <div class="small text-muted">Asal</div>
      <div class="fw-bold">Pabrik Garuda, Jakarta</div>
    </div>

    <div class="mb-3">
      <div class="small text-muted">Tujuan</div>
      <div class="fw-bold">DC Garuda Surabaya, Surabaya</div>
    </div>
    <hr>
    <div class="row g-2">
      <div class="col-6 small text-muted">Uang Jalan</div>
      <div class="col-6 text-end fw-bold">4.091.875</div>

      <div class="col-6 small text-muted">Sudah Bayar</div>
      <div class="col-6 text-end fw-bold text-success">0</div>

      <div class="col-6 small text-muted">Sisa</div>
      <div class="col-6 text-end fw-bold text-danger">4.091.875</div>
    </div>
  </div>

  <div class="card-ujp">
    <div class="d-flex justify-content-between align-items-start mb-2">
      <div class="small text-muted">Surat Jalan<br>
        <span class="fw-bold text-dark">FTL/2022/07/02832</span>
      </div>
      <div class="text-end small text-muted">Tanggal Pengiriman<br>
        <a class="fw-bold link-date" href="#">30 Jul 2022</a>
      </div>
    </div>

    <div class="mb-2">
      <div class="small text-muted">Customer</div>
      <div class="fw-bold">PT. SUKSES SEJAHTERA</div>
    </div>

    <div class="mb-2">
      <div class="small text-muted">Asal</div>
      <div class="fw-bold">Pabrik Pulogadung, Jakarta</div>
    </div>

    <div class="mb-3">
      <div class="small text-muted">Tujuan</div>
      <div class="fw-bold">Gudang A, Bandung</div>
    </div>
    <hr>
    <div class="row g-2">
      <div class="col-6 small text-muted">Uang Jalan</div>
      <div class="col-6 text-end fw-bold">3.500.000</div>

      <div class="col-6 small text-muted">Sudah Bayar</div>
      <div class="col-6 text-end fw-bold text-success">1.500.000</div>

      <div class="col-6 small text-muted">Sisa</div>
      <div class="col-6 text-end fw-bold text-danger">2.000.000</div>
    </div>
  </div>

</div>
@endsection
