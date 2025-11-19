<!doctype html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') - Planner Panel</title>

    <link rel="stylesheet" href="{{ asset('assets/css/style2.css') }}">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />

</head>

<body class="bg-light">
    <div id="wrapper"> 
        
        <aside id="sidebar-wrapper" class="bg-primary text-white d-flex flex-column">
            <div class="p-3 border-bottom border-light text-center">
                <h5 class="fw-bold mb-0">Planner Panel</h5>
            </div>

            <ul class="nav flex-column mt-3 flex-grow-1">
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

                <li class="nav-item">
                    <a href="{{ route('menu.profil.planner') }}" 
                        class="nav-link {{ request()->is('profile*') ? 'bg-light text-primary fw-semibold rounded' : 'text-white' }}">
                        <i class="bi bi-person-circle me-2"></i> Profil
                    </a>
                </li>
            </ul>
        </aside>

        <div id="page-content-wrapper" class="flex-grow-1">
            
            <nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom shadow-sm sticky-top d-lg-none">
                <div class="container-fluid">
                    <button class="btn btn-primary" id="sidebarToggleMobile">
                        <i class="bi bi-list"></i>
                    </button>
                    <a class="navbar-brand fw-bold text-primary" href="#">Planner</a>
                    </div>
            </nav>

            <main class="p-4">
                @yield('content')
            </main>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
    $(document).ready(function() {
        $('#xm_fleet_id').select2({
            theme: 'bootstrap-5',
            placeholder: '-- Pilih Fleet --',
            allowClear: true,
            width: '100%',
            language: {
                noResults: function() { return "Fleet tidak ditemukan"; },
                searching: function() { return "Mencari..."; }
            }
        });

        $('#sidebarToggleMobile').on('click', function (e) {
            e.preventDefault();
            $('#wrapper').toggleClass('toggled');
        });
    });
    </script>
</body>
</html>