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
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />

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
                    <a href="{{ route('menu.profil.planner') }}" 
                        class="nav-link {{ request()->is('profile') ? 'bg-light text-primary fw-semibold rounded' : 'text-white' }}">
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

    <!-- Select2 CSS -->
     <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
    $(document).ready(function() {
        $('#xm_fleet_id').select2({
            theme: 'bootstrap-5',
            placeholder: '-- Pilih Fleet --',
            allowClear: true,
            width: '100%',
            language: {
                noResults: function() {
                    return "Fleet tidak ditemukan";
                },
                searching: function() {
                    return "Mencari...";
                }
            }
        });
    });
    </script>

    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

    <link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet" />

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
