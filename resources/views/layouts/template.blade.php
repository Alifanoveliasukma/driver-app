<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Login Supier</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">


    <!-- CSS Custom -->
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="{{ asset('assets/js/slide-confirm.js') }}"></script>

<body class="login-bg">
    @yield('content')

</body>
<nav class="bottom-nav nav nav-pills nav-fill bg-light border-top position-fixed bottom-0 w-100"
    style="height:60px; z-index:999;">
    <a href="{{ route('menu.list-order') }}"
        class="nav-link text-center {{ request()->routeIs('menu.list-order') ? 'active' : '' }}"
        aria-current="{{ request()->routeIs('menu.list-order') ? 'page' : '' }}">
        <i class="bi bi-house-door-fill d-block"></i>
        <small class="d-block">Utama</small>
    </a>

    <!-- <a href="{{ route('menu.ujp') }}"
     class="nav-link text-center {{ request()->routeIs('menu.ujp') ? 'active' : '' }}"
     aria-current="{{ request()->routeIs('menu.ujp') ? 'page' : '' }}">
    <i class="bi bi-credit-card-2-front d-block"></i>
    <small class="d-block">UJP</small>
  </a> -->

    <a href="{{ route('menu.histori') }}"
        class="nav-link text-center {{ request()->routeIs('menu.histori') ? 'active' : '' }}"
        aria-current="{{ request()->routeIs('menu.histori') ? 'page' : '' }}">
        <i class="bi bi-clock-history d-block"></i>
        <small class="d-block">Histori</small>
    </a>

    <a href="{{ route('menu.profil') }}"
        class="nav-link text-center {{ request()->routeIs('menu.profil') ? 'active' : '' }}"
        aria-current="{{ request()->routeIs('menu.profil') ? 'page' : '' }}">
        <i class="bi bi-person-circle d-block"></i>
        <small class="d-block">Profil</small>
    </a>
</nav>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous">
    const navLinks = document.querySelectorAll('.nav-link');

    navLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();

            navLinks.forEach(item => {
                item.querySelector('i').style.color = 'grey';
                item.querySelector('small').style.color = 'grey';
            });

            this.querySelector('i').style.color = 'purple';
            this.querySelector('small').style.color = 'purple';
        });
    });
</script>
</body>

</html>
