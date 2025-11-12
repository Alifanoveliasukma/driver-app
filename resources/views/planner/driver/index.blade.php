@extends('layouts.template-planner') {{-- Pastikan nama layout Anda benar --}}

@section('title', 'Data Semua Driver')

@section('content')
<div class="container py-3">
    <h4 class="fw-bold text-primary mb-4">🚛 Daftar Semua Driver</h4>

    {{-- Pesan Sukses (digunakan saat clear cache) --}}
    @if(session('success_message'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i> {{ session('success_message') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @isset($error)
        <div class="alert alert-danger" role="alert">
            <i class="bi bi-x-octagon-fill me-2"></i> **Error:** {{ $error }}
        </div>
    @endisset

    <div class="d-flex justify-content-between align-items-center mb-4">
        {{-- Pencarian menggunakan Form, dikirim ke Controller --}}
        <form action="{{ url()->current() }}" method="GET" class="d-flex align-items-center gap-2">
            <label for="searchInput" class="form-label mb-0 fw-semibold">Cari Driver:</label>
            <input type="text" class="form-control w-auto shadow-sm" id="searchInput" name="search" 
                   placeholder="Ketik NIP, Nama, atau Status..." 
                   value="{{ $search ?? '' }}">
            <button type="submit" class="btn btn-primary"><i class="bi bi-search"></i> Cari</button>
            @if(isset($search))
                <a href="{{ url()->current() }}" class="btn btn-outline-secondary">Reset</a>
            @endif
        </form>

        <div class="d-flex gap-2">
            <a href="{{ route('driver.create') }}" class="btn btn-success fw-semibold shadow-sm">
                <i class="bi bi-plus-lg me-1"></i> Tambah Driver
            </a>
            
            <a href="{{ url()->current() }}?clear_cache=true" class="btn btn-sm btn-outline-danger" title="Hapus cache driver dan muat ulang data">
                <i class="bi bi-arrow-clockwise me-1"></i> Clear Cache
            </a>
        </div>
    </div>


    @if($driverData->isEmpty() && !isset($error) && !isset($search))
        {{-- Tampil jika tidak ada data sama sekali --}}
        <div class="alert alert-info" role="alert">
            <i class="bi bi-info-circle-fill me-2"></i> Tidak ada data Driver yang ditemukan.
        </div>
    @elseif($driverData->isEmpty() && isset($search))
        {{-- Tampil jika tidak ada data setelah difilter --}}
        <div class="alert alert-warning" role="alert">
            <i class="bi bi-search me-2"></i> Tidak ditemukan data yang cocok dengan kata kunci **"{{ $search }}"**.
        </div>
    @else
        <div class="card shadow-lg border-0">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-hover align-middle mb-4" id="driverTable">
                        <thead>
                            <tr class="table-primary">
                                <th>#</th>
                                <th>ID</th>
                                <th>Nama Lengkap</th>
                                <th>Status</th>
                                <th>Fleet</th>
                                <th>No Account</th>
                                <th>BPartner</th>
                                <!-- <th>Aksi</th> -->
                            </tr>
                        </thead>
                        <tbody id="tableBody">
                            @foreach($driverData as $driver)
                                <tr>
                                    <td>{{ $loop->iteration + ($driverData->currentPage() - 1) * $driverData->perPage() }}</td>
                                    <td>{{ $driver->nip }}</td>
                                    <td>{{ $driver->nama_lengkap }}</td>
                                    <td>
                                        <span class="badge 
                                            @if($driver->driverstatus == 'Stand By') bg-success 
                                            @elseif($driver->driverstatus == 'Out') bg-warning 
                                            @else bg-secondary 
                                            @endif">
                                            {{ $driver->driverstatus }}
                                        </span>
                                    </td>
                                    <td>{{ $driver->fleet_name ?? '-' }}</td>
                                    <td>{{ $driver->accountno ?? '-' }}</td>
                                    <td>{{ $driver->bp_name ?? '-' }}</td>
<!--                                     
                                    <td>
                                        <button class="btn btn-sm btn-outline-info" title="Lihat Detail">
                                            <i class="bi bi-eye"></i> Detail
                                        </button>
                                    </td> -->
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- ✅ Pagination Sesuai Contoh User -->
                <div class="d-flex justify-content-between align-items-center mt-3">
                    <div class="text-muted">
                        Menampilkan **{{ $driverData->firstItem() ?? 0 }}** - **{{ $driverData->lastItem() ?? 0 }}** dari **{{ $driverData->total() }}** data
                    </div>
                    <div>
                        {{-- Menggunakan vendor.pagination.bootstrap-4 dan onEachSide(1) --}}
                        {{ $driverData->onEachSide(1)->appends(['search' => $search])->links('vendor.pagination.bootstrap-4') }}
                    </div>
                </div>
                <p class="text-muted small mt-2 mb-0">
                    <i class="bi bi-info-circle-fill me-1"></i> **Catatan:** Jika ada Driver baru yang sudah ditambahkan di database tetapi belum muncul, silakan klik tombol **Clear Cache** di atas untuk memuat ulang data terbaru.
                </p>

            </div>
        </div>
    @endif
</div>
@endsection