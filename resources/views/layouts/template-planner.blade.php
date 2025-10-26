<!doctype html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') - Planner Panel</title>

    <!-- Custom CSS -->
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

</head>

<body class="bg-light">
    <div class="d-flex" style="min-height: 100vh;">

        <!-- Sidebar -->
        <aside class="sidebar bg-primary text-white d-flex flex-column">
            <div class="p-3 border-bottom border-light text-center">
                <h5 class="fw-bold mb-0">Planner Panel</h5>
            </div>

            <ul class="nav flex-column mt-3">
                <li class="nav-item">
                    <a href="{{ url('/dashboard') }}" 
                    class="nav-link {{ request()->is('dashboard') ? 'bg-light text-primary fw-semibold rounded' : 'text-white' }}">
                    <i class="bi bi-speedometer2 me-2"></i> Dashboard
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('driver.index') }}" 
                        class="nav-link {{ request()->is('driver*') ? 'bg-light text-primary fw-semibold rounded' : 'text-white' }}">
                        <i class="bi bi-person-plus me-2"></i> Tambah Driver
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('histori.planner') }}" 
                        class="nav-link {{ request()->is('histori*') ? 'bg-light text-primary fw-semibold rounded' : 'text-white' }}">
                        <i class="bi bi-clock-history me-2"></i> Transport Tracking
                    </a>
                </li>

                <li class="nav-item mt-auto">
                    <a href="#" class="nav-link text-white">
                        <i class="bi bi-person-circle me-2"></i> Profil
                    </a>
                </li>
            </ul>
        </aside>

        <!-- Content -->
        <main class="flex-grow-1 p-4">
            @yield('content')
        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
