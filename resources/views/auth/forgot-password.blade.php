{{-- resources/views/auth/forgot-password.blade.php --}}
@extends('layouts.template')

@section('content')
<style>
  .history-hero{
    position: relative; height: 100px; color:#fff;
    display:flex; align-items:center; justify-content:center;
    background-color:#226a68; /* ganti kalau perlu */
  }
  .btn-back{
    position:absolute; left:12px; top:50%; transform:translateY(-50%);
    width:36px;height:36px;border:none;border-radius:999px;
    background:rgba(255,255,255,.15); color:#fff; display:grid; place-items:center;
    cursor:pointer;
  }
  .btn-back:hover{ background:rgba(255,255,255,.25); }
  .history-chip{
    background:rgba(255,255,255,.15); border:1px solid rgba(255,255,255,.25);
    color:#fff; padding:6px 12px; border-radius:10px; font-weight:600; font-size:14px; backdrop-filter:blur(4px);
    display:flex; flex-direction:column; align-items:center; gap:2px; text-align:center;
  }
  .soft{ border:0; border-radius:14px; box-shadow:0 8px 18px rgba(0,0,0,.08); }
</style>

<!-- HERO -->
<div class="history-hero">
  <button class="btn-back" onclick="window.history.back()" aria-label="Kembali">
    <i class="bi bi-chevron-left"></i>
  </button>
  <div class="history-chip">
    <div>Lupa Kata Sandi</div>
  </div>
</div>

<!-- CONTENT -->
<div class="px-3">
  <div class="mx-auto" style="max-width:400px; margin-top:-22px;">
    <div class="soft bg-white p-3">
      <div class="text-muted mb-2" style="font-weight:500;">Instruksi</div>
      <p class="mb-3" style="font-size:14px">
        Masukkan email yang terdaftar. Kami akan mengirim tautan untuk mengatur ulang kata sandi.
      </p>

      {{-- Alert sukses --}}
      @if (session('status'))
        <div class="alert alert-success py-2">{{ session('status') }}</div>
      @endif

      {{-- Error --}}
      @if ($errors->any())
        <div class="alert alert-danger py-2 mb-3">
          {{ $errors->first() }}
        </div>
      @endif

      <form method="POST" action="#" onsubmit="disableBtn(this)">
        @csrf
        <div class="mb-3">
          <label class="form-label">Email</label>
          <div class="input-group">
            <span class="input-group-text bg-light"><i class="bi bi-envelope"></i></span>
            <input type="email" name="email" class="form-control" placeholder="nama@contoh.com" required autofocus>
          </div>
        </div>

        <button type="submit" class="btn btn-primary w-100">
          <span class="btn-text">Kirim Tautan Reset</span>
          <span class="btn-spinner d-none ms-2 spinner-border spinner-border-sm" role="status"></span>
        </button>

        <div class="text-center mt-3">
          <a href="{{ route('showLoginForm') }}" class="text-decoration-none">Kembali ke Masuk</a>
        </div>
      </form>
    </div>
  </div>
</div>

<script>
  function disableBtn(form){
    const btn = form.querySelector('button[type="submit"]');
    btn.disabled = true;
    btn.querySelector('.btn-spinner').classList.remove('d-none');
    btn.querySelector('.btn-text').textContent = 'Mengirim...';
  }
</script>
@endsection
