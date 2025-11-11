<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Buat User dan Driver Baru</title>
    <style>
        .container { max-width: 700px; margin: 50px auto; padding: 25px; border: 1px solid #ddd; border-radius: 8px; box-shadow: 0 4px 8px rgba(0,0,0,0.05); font-family: sans-serif;}
        h2 { border-bottom: 2px solid #eee; padding-bottom: 10px; margin-top: 30px; }
        .form-group { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; font-weight: bold; color: #333; }
        input[type="text"], input[type="password"], input[type="number"], select, textarea { width: 100%; padding: 10px; box-sizing: border-box; border: 1px solid #ccc; border-radius: 4px; }
        select { height: 40px; }
        textarea { resize: vertical; height: 80px; }
        button { padding: 10px 20px; background-color: #007bff; color: white; border: none; border-radius: 4px; cursor: pointer; font-size: 16px; margin-top: 20px; }
        .alert-success { background-color: #d4edda; color: #155724; padding: 10px; border: 1px solid #c3e6cb; border-radius: 4px; margin-bottom: 15px; }
        .alert-error { background-color: #f8d7da; color: #721c24; padding: 10px; border: 1px solid #f5c6cb; border-radius: 4px; margin-bottom: 15px; }
    </style>
</head>
<body>
    <div class="container">
        <h1>👤🚛 Pendaftaran User & Driver Baru</h1>

        @if(session('success'))
            <div class="alert-success">{{ session('success') }}</div>
        @endif
        @if(session('error') || session('warning'))
            <div class="alert-error">
                {{ session('error') ?? session('warning') }}
            </div>
        @endif
        
        @if ($errors->any())
            <div class="alert-error">
                **Terjadi Kesalahan Validasi:**
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('driver.store') }}">
            @csrf

            <h2>Step 1: Data User (AD_User)</h2>
            <p>Data ini digunakan untuk membuat akun User dan akan otomatis mendapatkan **Role Driver (ID 1000049)**.</p>

            <div class="form-group">
                <label for="user_value">Value (Kode/NIP User)</label>
                <input type="text" name="user_value" id="user_value" value="{{ old('user_value') }}" required>
            </div>

            <div class="form-group">
                <label for="user_name">Name (Nama Lengkap)</label>
                <input type="text" name="user_name" id="user_name" value="{{ old('user_name') }}" required>
            </div>

            <div class="form-group">
                <label for="user_password">Password</label>
                <input type="password" name="user_password" id="user_password" required>
            </div>
            
            <div class="form-group">
                <label for="c_bpartner_id">ID Business Partner (C_BPartner_ID)</label>
                <input type="number" name="c_bpartner_id" id="c_bpartner_id" value="{{ old('c_bpartner_id') }}" required>
            </div>

            <div class="form-group">
                <label for="is_full_bp_access">Akses Penuh BP? (IsFullBPAccess)</label>
                <select name="is_full_bp_access" id="is_full_bp_access" required>
                    <option value="Y" {{ old('is_full_bp_access', 'N') == 'Y' ? 'selected' : '' }}>Ya (Y)</option>
                    <option value="N" {{ old('is_full_bp_access', 'N') == 'N' ? 'selected' : '' }}>Tidak (N)</option>
                </select>
            </div>

            <div class="form-group">
                <label for="is_login_user">Bisa Login? (IsLoginUser)</label>
                <select name="is_login_user" id="is_login_user" required>
                    <option value="Y" {{ old('is_login_user', 'Y') == 'Y' ? 'selected' : '' }}>Ya (Y)</option>
                    <option value="N" {{ old('is_login_user', 'Y') == 'N' ? 'selected' : '' }}>Tidak (N)</option>
                </select>
            </div>
            
            <hr>

            <h2>Step 2: Data Driver (XM_Driver)</h2>
            <p>Kolom **Value** dan **Name** Driver akan menggunakan data dari Step 1.</p>

            <div class="form-group">
            <label for="driver_status">Status Driver (DriverStatus)</label>
            <select name="driver_status" id="driver_status" required>
                <option value="">-- Pilih Status --</option>
                @php
                    $statuses = [
                        'Stand By', 
                        'Maintenance No Driver', 
                        'Maintenance with Driver',
                        'Off Duty', 
                        'On Duty', 
                        'Rented'
                    ];
                @endphp

                @foreach ($statuses as $status)
                    <option 
                        value="{{ $status }}" 
                        {{ old('driver_status', 'Stand By') == $status ? 'selected' : '' }}>
                        {{ $status }}
                    </option>
                @endforeach
            </select>
        </div>
            
            <div class="form-group">
                <label for="xm_fleet_id">ID Fleet (XM_Fleet_ID)</label>
                <input type="number" name="xm_fleet_id" id="xm_fleet_id" value="{{ old('xm_fleet_id') }}">
            </div>

            <div class="form-group">
                <label for="krani_id">ID Krani (Krani_ID)</label>
                <input type="number" name="krani_id" id="krani_id" value="{{ old('krani_id') }}">
            </div>
            
            <div class="form-group">
                <label for="account_no">No. Rekening (AccountNo)</label>
                <input type="text" name="account_no" id="account_no" value="{{ old('account_no') }}">
            </div>

            <div class="form-group">
                <label for="account_name">Nama Akun/Bank (Account)</label>
                <input type="text" name="account_name" id="account_name" value="{{ old('account_name') }}">
            </div>

            <div class="form-group">
                <label for="note">Catatan (Note)</label>
                <textarea name="note" id="note">{{ old('note') }}</textarea>
            </div>


            <button type="submit">Buat User & Driver</button>
        </form>
    </div>
</body>
</html>