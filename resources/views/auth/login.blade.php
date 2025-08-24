<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MZL Driver - Login Sopir</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

    <!-- CSS Custom -->
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="{{ asset('assets/js/script.js') }}"></script>

</head>
<body>
    <div class="position-relative bg-purple text-white" style="height: 280px;">
        <div class="text-center pt-5">
             <img src="{{ asset('assets/img/logo-perusahaan.jpg') }}" alt="Logo" style="max-height: 40px;">
            <p>Sopir</p>
        </div>

        <div class="circle circle1"></div>
        <div class="circle circle2"></div>
        <div class="circle circle3"></div>
        <div class="circle circle4"></div>
        <div class="circle circle5"></div>
        <div class="circle circle6"></div>
    </div>

    <div class="truck-icon text-center" style="margin-top: -30px; z-index: 10; position: relative;">
        <i class="fas fa-truck" style="font-size: 32px; color: white; background-color: #3F2F96; padding: 12px; border-radius: 50%;"></i>
    </div>

    <div class="container d-flex justify-content-center align-items-start mt-n5" style="min-height: 60vh;">
        <div class="w-100" style="max-width: 400px;">

            {{-- Tampilkan pesan dari session --}}
            @if(session('message'))
                <div class="alert alert-danger">
                    {{ is_array(session('message')) ? json_encode(session('message')) : session('message') }}
                </div>
            @endif

            {{-- Kalau mau tetap ada validasi bawaan errors --}}
            @if($errors->any())
                <div class="alert alert-danger">
                    {{ is_array($errors->first()) ? json_encode($errors->first()) : $errors->first() }}
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}" class="bg-white p-4 rounded">
                @csrf
                <div class="d-flex justify-content-between align-items-center mb-4 px-1">
                    <div class="text-center">
                        <div class="fw-bold text-dark">Masuk</div>
                        <div class="mx-auto mt-1 bullet"></div>
                    </div>
                    <a href="#" class="text-decoration-none text-dark align-self-end small">Lupa Kata Sandi</a>
                </div>

                {{-- Username --}}
                <div class="mb-3">
                    <input type="text" name="username" class="form-control form-control-lg"
                        placeholder="Username" value="{{ old('username') }}" required>
                </div>

                {{-- Password --}}
                <div class="mb-4 position-relative">
                    <input type="password" name="password" class="form-control form-control-lg"
                        placeholder="Kata Sandi" required>
                </div>

                <button type="submit" class="btn w-100 text-white btn-purple">Masuk</button>
            </form>
        </div>
    </div>

</body>
    <script src="{{ asset('assets/libs/select2/js/select2.min.js') }}"></script>

	<script type="text/javascript">
		document.addEventListener('DOMContentLoaded', function () {
			"use strict";

			const org = document.getElementById('org');
			if (org && window.$ && $.fn.select2) {
				$('#org').select2({
					placeholder: "Pilih Cabang / Gerai",
					allowClear: true,
				});

				if ($('#org').data('invalid')) {
					$('#org + span').addClass('is-invalid');
				}
			}
		});
	</script>
</body>
