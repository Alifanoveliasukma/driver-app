<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>MZL Driver - Pilih Role & Organisasi</title>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

  <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
  {{-- HEADER UNGU + GELEMBUNG (sama seperti login) --}}
  <div class="position-relative bg-purple text-white" style="height: 280px;">
    <div class="text-center pt-5">
      <img src="{{ asset('assets/img/logo-perusahaan.jpg') }}" alt="Logo" style="max-height: 40px;">
      <p class="mt-2">Pilih Role & Organisasi</p>
      <div class="small">
        User: <strong>{{ $user->name ?? $user->value ?? session('username') }}</strong>
      </div>
    </div>

    <div class="circle circle1"></div>
    <div class="circle circle2"></div>
    <div class="circle circle3"></div>
    <div class="circle circle4"></div>
    <div class="circle circle5"></div>
    <div class="circle circle6"></div>
  </div>

  {{-- ICON TRUK (posisi & style sama) --}}
  <div class="truck-icon text-center" style="margin-top: -30px; z-index: 10; position: relative;">
    <i class="fas fa-truck" style="font-size: 32px; color: white; background-color: #3F2F96; padding: 12px; border-radius: 50%;"></i>
  </div>

  {{-- BODY FORM (kartu putih sama seperti login) --}}
  <div class="container d-flex justify-content-center align-items-start mt-n5" style="min-height: 60vh;">
    <div class="w-100" style="max-width: 420px;">

      {{-- Alert session/message & errors (format sama) --}}
      @if(session('message'))
        <div class="alert alert-danger">
          {{ is_array(session('message')) ? json_encode(session('message')) : session('message') }}
        </div>
      @endif
      @if($errors->any())
        <div class="alert alert-danger">
          {{ is_array($errors->first()) ? json_encode($errors->first()) : $errors->first() }}
        </div>
      @endif

      <form action="{{ route('login', ['step' => 'roleorg']) }}" method="POST" class="bg-white p-4 rounded" id="form-roleorg">
        @csrf

        <div class="mb-3">
          <label for="role" class="form-label">Role <span class="text-danger">*</span></label>
          <select id="role" name="role" class="form-control select2 @error('role') is-invalid @enderror" data-invalid="@error('role') true @enderror">
            <option value=""></option>
            @foreach(($roles ?? []) as $role)
              <option value="{{ $role->id }}" {{ old('role')==$role->id ? 'selected' : '' }}>
                {{ $role->name }}
              </option>
            @endforeach
          </select>
          @error('role') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="mb-4">
          <label for="org" class="form-label">Cabang <span class="text-danger">*</span></label>
          <select id="org" name="org" class="form-control select2 @error('org') is-invalid @enderror" data-invalid="@error('org') true @enderror">
            <option value=""></option>
            @foreach(($orgs ?? []) as $org)
              <option value="{{ $org->id }}" {{ old('org')==$org->id ? 'selected' : '' }}>
                {{ $org->name }}
              </option>
            @endforeach
          </select>
          @error('org') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <button type="submit" class="btn w-100 text-white btn-purple">Lanjut</button>
      </form>

      <div class="text-center mt-3">
        <a href="{{ route('login') }}" class="text-decoration-none small text-muted">
          <i class="bi bi-arrow-left"></i> Kembali ke Login
        </a>
      </div>
    </div>
  </div>

  <script src="{{ asset('assets/libs/select2/js/select2.min.js') }}"></script>
  <script>
    document.addEventListener('DOMContentLoaded', function () {
      if (window.$ && $.fn.select2) {
        $('#role').select2({ placeholder: 'Pilih Role', allowClear: true, width: '100%' });
        $('#org').select2({ placeholder: 'Pilih Cabang / Gerai', allowClear: true, width: '100%' });

        if ($('#role').data('invalid')) $('#role + span').addClass('is-invalid');
        if ($('#org').data('invalid')) $('#org + span').addClass('is-invalid');
      }
    });
  </script>
</body>
</html>
