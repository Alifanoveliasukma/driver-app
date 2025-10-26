@extends('layouts.template')

<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Order')</title>
</head>

@section('content')
    <div class="position-relative bg-purple text-white " style="height: 120px;">
    </div>

    <div class="d-flex flex-column justify-content-start align-items-center"
        style="height: calc(100vh - 60px); padding-top: 100px; padding-bottom: 20px;">

        <img src="{{ asset('assets/icon/i-no-order.svg') }}" alt="truck" style="max-width: 250px;">

        <h5 class="text-primary mt-3" style="font-weight: 600;">Tidak Ada History!</h5>

        <p class="text-muted text-center" style="font-size: 14px;">Harap tunggu penugasan<br>berikutnya dari kantor</p>
    </div>
@endsection
