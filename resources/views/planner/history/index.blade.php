@extends('layouts.template-planner')

@section('title', 'Data Driver') {{-- Tetap gunakan title yang relevan --}}

@section('content')
<div class="container-fluid mt-4">
    <h4 class="mb-4 text-primary fw-bold">Data Driver Management</h4> {{-- Judul disesuaikan --}}

    <div class="card shadow-sm mb-4 border-0">
        <div class="card-body">
            {{-- Sesuaikan route: gunakan route 'driver.index' untuk halaman ini --}}
            <form action="{{ route('driver.index') }}" method="GET" class="row g-3 align-items-center">
                <div class="col-md-8">
                    <div class="input-group">
                        <input type="text" 
                                name="search" 
                                class="form-control" 
                                {{-- Sesuaikan placeholder untuk Data Driver --}}
                                placeholder="Cari berdasarkan Search Key, Nama Driver, Business Partner, atau Krani ID..."
                                value="{{ request('search') }}"
                                aria-label="Search">
                        <button class="btn btn-primary" type="submit">
                            <i class="bi bi-search me-1"></i> Cari
                        </button>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="d-flex justify-content-end align-items-center">
                        @if(request('search'))
                        <span class="badge bg-info me-2 p-2">
                            {{ $drivers->total() }} hasil ditemukan
                        </span>
                        {{-- Sesuaikan route untuk Clear --}}
                        <a href="{{ route('driver.index') }}" class="btn btn-sm btn-outline-secondary">
                            <i class="bi bi-x-circle me-1"></i> Clear
                        </a>
                        @endif
                        {{-- Tombol Tambah Driver dipindahkan ke sini --}}
                        <a href="{{ route('driver.create') }}" class="btn btn-primary ms-3">
                            <i class="bi bi-plus-circle me-1"></i> Tambah Driver
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body">
            @if(request('search') && $drivers->total() == 0)
            <div class="alert alert-warning mb-4">
                <i class="bi bi-exclamation-triangle-fill"></i> 
                Tidak ada data driver yang ditemukan untuk pencarian "<strong>{{ request('search') }}</strong>"
            </div>
            @endif

            <div class="table-responsive">
                {{-- ID driverTable dihapus karena tidak lagi menggunakan DataTables --}}
                <table class="table table-bordered table-striped table-hover align-middle">
                    <thead class="table-primary text-center">
                        <tr>
                            <th>#</th> {{-- Ganti No menjadi # dan dimulai dari paginasi --}}
                            <th>Search Key</th>
                            <th>Status Driver</th>
                            <th>Nama Driver</th>
                            <th>Business Partner</th>
                            <th>Fleet ID</th>
                            <th>Active</th>
                            <th>Krani ID</th>
                            <th>Account No</th>
                            <th>Account</th>
                            <th>Catatan</th>
                            <th width="120px">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        {{-- Ganti contoh data dummy dengan loop Laravel --}}
                        @forelse($drivers as $i => $driver)
                        <tr>
                            <td class="text-center">{{ $drivers->firstItem() + $i }}</td>
                            {{-- Gunakan helper highlightText untuk kolom yang bisa dicari --}}
                            <td>{!! highlightText($driver['SearchKey'] ?? '-', request('search')) !!}</td>
                            <td class="text-center">
                                @if(isset($driver['StatusDriver']) && $driver['StatusDriver'] == 'Aktif')
                                <span class="badge bg-success">{{ $driver['StatusDriver'] }}</span>
                                @elseif(isset($driver['StatusDriver']) && $driver['StatusDriver'] == 'Nonaktif')
                                <span class="badge bg-danger">{{ $driver['StatusDriver'] }}</span>
                                @else
                                <span class="badge bg-secondary">{{ $driver['StatusDriver'] ?? '-' }}</span>
                                @endif
                            </td>
                            <td>{!! highlightText($driver['NamaDriver'] ?? '-', request('search')) !!}</td>
                            <td>{!! highlightText($driver['BusinessPartner'] ?? '-', request('search')) !!}</td>
                            <td class="text-center">{{ $driver['FleetID'] ?? '-' }}</td>
                            <td class="text-center">
                                @if(isset($driver['Active']) && $driver['Active'] == 'Yes')
                                <span class="badge bg-success">Yes</span>
                                @else
                                <span class="badge bg-secondary">No</span>
                                @endif
                            </td>
                            <td>{!! highlightText($driver['KraniID'] ?? '-', request('search')) !!}</td>
                            <td>{{ $driver['AccountNo'] ?? '-' }}</td>
                            <td>{{ $driver['Account'] ?? '-' }}</td>
                            <td>{{ $driver['Catatan'] ?? '-' }}</td>
                            <td class="text-center">
                                {{-- Sesuaikan route untuk Aksi (misalnya Edit) --}}
                                <a href="{{ route('driver.edit', ['id' => $driver['id']]) }}" class="btn btn-sm btn-warning me-1">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                {{-- Contoh tombol delete --}}
                                <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal{{ $driver['id'] }}">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="12" class="text-center text-muted">
                                @if(request('search'))
                                    Tidak ada data driver yang cocok dengan pencarian Anda.
                                @else
                                    Belum ada data driver.
                                @endif
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-between align-items-center mt-3">
                <div class="text-muted">
                    Menampilkan {{ $drivers->firstItem() ?? 0 }} - {{ $drivers->lastItem() ?? 0 }} dari {{ $drivers->total() }} data
                </div>
                <div>
                    {{-- Sesuaikan tampilan paginasi jika perlu, atau gunakan default Laravel/Bootstrap --}}
                    {{ $drivers->onEachSide(1)->links('vendor.pagination.bootstrap-5') }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

{{-- Asumsi Anda memiliki variabel $drivers yang berisi data yang sudah di-paginate --}}
{{-- Contoh data dummy untuk simulasi:
<?php
// Ini HANYA untuk SIMULASI, Anda harus mendapatkan data dari Controller
use Illuminate\Pagination\LengthAwarePaginator;
$dummyData = [
    ['id' => 1, 'SearchKey' => 'DRV001', 'StatusDriver' => 'Aktif', 'NamaDriver' => 'Ahmad Yusuf', 'BusinessPartner' => 'BP-1002', 'FleetID' => 'FLT-009', 'Active' => 'Yes', 'KraniID' => 'KRN-008', 'AccountNo' => '1234567890', 'Account' => 'BCA - a.n Ahmad Yusuf', 'Catatan' => 'Driver baru'],
    ['id' => 2, 'SearchKey' => 'DRV002', 'StatusDriver' => 'Nonaktif', 'NamaDriver' => 'Rudi Hartono', 'BusinessPartner' => 'BP-1003', 'FleetID' => 'FLT-015', 'Active' => 'No', 'KraniID' => 'KRN-010', 'AccountNo' => '9876543210', 'Account' => 'BRI - a.n Rudi Hartono', 'Catatan' => 'Cut off sementara'],
    // ... Tambahkan data lain untuk paginasi
];

$currentPage = LengthAwarePaginator::resolveCurrentPage();
$perPage = 10;
$currentItems = array_slice($dummyData, $perPage * ($currentPage - 1), $perPage);
$drivers = new LengthAwarePaginator($currentItems, count($dummyData), $perPage, $currentPage, ['path' => LengthAwarePaginator::resolveCurrentPath()]);

// Jika Anda menggunakan Laravel, Anda harus memastikan variabel $drivers 
// didefinisikan dan di-pass dari Controller sebagai objek Paginator.
?>
--}}

{{-- Helper function untuk highlight text --}}
<?php
if (!function_exists('highlightText')) {
    function highlightText($text, $search) {
        if (empty($text) || empty($search)) return $text;
        // Gunakan preg_quote untuk menangani karakter khusus dalam string pencarian
        $safeSearch = preg_quote($search, '/');
        // Gunakan \b di sekitar $1 agar penyorotan hanya terjadi pada kata/frasa lengkap
        // Namun, jika Anda ingin penyorotan sebagian kata, hapus \b
        $highlighted = preg_replace("/($safeSearch)/i", '<mark class="bg-warning">$1</mark>', $text);
        return $highlighted ?: $text;
    }
}
?>