@extends('layouts.template')

@section('content')
    <!-- HEADER UNGU -->
    <div class="position-relative bg-purple text-white" style="height: 280px;">
        <div class="text-center pt-5">
            <h3>accelog</h3>
            <p>Sopir</p>
        </div>

        <!-- BULAT PUTIH -->
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

    <!-- FORM LOGIN -->
    <div class="container d-flex justify-content-center align-items-start mt-n5" style="min-height: 60vh;">
        <div class="w-100" style="max-width: 400px;">
            @if($errors->any())
                <div class="alert alert-danger">
                    {{ $errors->first() }}
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

                <div class="mb-3">
                    <input type="text" name="phone_number" class="form-control form-control-lg" placeholder="Nomor Handphone" required>
                </div>

                <div class="mb-4 position-relative">
                    <input type="password" name="password" class="form-control form-control-lg" placeholder="Kata Sandi" required>
                </div>

                <button type="submit" class="btn w-100 text-white btn-purple">Masuk</button>
            </form>
        </div>
    </div>
@endsection
