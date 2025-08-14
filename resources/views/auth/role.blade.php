<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>{{ $title ?? 'Pilih Role & Org' }}</title>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
</head>
<body>
  <div class="position-relative bg-purple text-white" style="height: 220px;">
    <div class="text-center pt-5">
      <img src="{{ asset('assets/img/logo-perusahaan.jpg') }}" alt="Logo" style="max-height: 40px;">
      <p class="mt-3 fw-bold">Pilih Role & Organisasi</p>
      <div class="small">User: <strong>{{ $user->name ?? $user->value ?? session('username') }}</strong></div>
    </div>
  </div>

  <div class="container d-flex justify-content-center align-items-start mt-n4" style="min-height: 60vh;">
    <div class="w-100" style="max-width: 420px;">

      @if(session('message')) {!! session('message') !!} @endif
      @if($errors->any())
        <div class="alert alert-danger">{{ $errors->first() }}</div>
      @endif

      <form method="POST" action="{{ route('login.roleorg') }}">
        @csrf

        <div class="mb-3">
          <label for="role" class="form-label">Role <span class="text-danger">*</span></label>
          <select id="role" name="role" class="form-select @error('role') is-invalid @enderror">
            <option value="" hidden>-- Pilih Role --</option>
            @foreach($roles as $r)
              <option value="{{ $r->id }}" {{ old('role')==$r->id ? 'selected' : '' }}>
                {{ $r->name }}
              </option>
            @endforeach
          </select>
          @error('role') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="mb-4">
          <label for="org" class="form-label">Organisasi / Cabang <span class="text-danger">*</span></label>
          <select id="org" name="org" class="form-select @error('org') is-invalid @enderror">
            <option value="" hidden>-- Pilih Organisasi --</option>
            @foreach($orgs as $o)
              <option value="{{ $o->id }}" {{ old('org')==$o->id ? 'selected' : '' }}>
                {{ $o->name }}
              </option>
            @endforeach
          </select>
          @error('org') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="d-flex gap-2">
          <a href="{{ route('login') }}" class="btn btn-outline-secondary w-50">Kembali</a>
          <button type="submit" class="btn btn-primary w-50">Lanjutkan</button>
        </div>
      </form>
    </div>
  </div>
</body>
</html>
