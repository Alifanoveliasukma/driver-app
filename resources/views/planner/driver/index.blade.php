@extends('layouts.template-planner')

@section('title', 'Data Driver')

@section('content')
<div class="container py-3">
    <div class="d-flex align-items-center mb-4">
        <button class="btn btn-outline-primary me-3" onclick="window.history.back()" aria-label="Kembali">
            <i class="bi bi-chevron-left"></i>
        </button>
        <h4 class="fw-bold text-primary mb-0">Data Driver</h4>

        <div class="ms-auto">
            <a href="{{ route('driver.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-circle me-2"></i> Tambah Driver
            </a>
        </div>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body">
            <div class="table-responsive">
                <table id="driverTable" class="table table-bordered table-hover align-middle">
                    <thead class="table-primary text-center">
                        <tr>
                            <th>No</th>
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
                        {{-- Contoh data dummy --}}
                        <tr>
                            <td>1</td>
                            <td>DRV001</td>
                            <td><span class="badge bg-success">Aktif</span></td>
                            <td>Ahmad Yusuf</td>
                            <td>BP-1002</td>
                            <td>FLT-009</td>
                            <td><span class="badge bg-success">Yes</span></td>
                            <td>KRN-008</td>
                            <td>1234567890</td>
                            <td>BCA - a.n Ahmad Yusuf</td>
                            <td>Driver baru pindahan dari cabang Bekasi.</td>
                            
                        </tr>
                        <tr>
                            <td>2</td>
                            <td>DRV002</td>
                            <td><span class="badge bg-danger">Nonaktif</span></td>
                            <td>Rudi Hartono</td>
                            <td>BP-1003</td>
                            <td>FLT-015</td>
                            <td><span class="badge bg-secondary">No</span></td>
                            <td>KRN-010</td>
                            <td>9876543210</td>
                            <td>BRI - a.n Rudi Hartono</td>
                            <td>Cut off sementara karena perawatan kendaraan.</td>
                            
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- DataTables JS & CSS -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

<script>
    $(document).ready(function () {
        $('#driverTable').DataTable({
            order: [[0, 'asc']],
            pageLength: 10,
            language: {
                search: "Cari:",
                lengthMenu: "Tampilkan _MENU_ data per halaman",
                zeroRecords: "Data tidak ditemukan",
                info: "Menampilkan _START_ - _END_ dari _TOTAL_ data",
                infoEmpty: "Tidak ada data tersedia",
                infoFiltered: "(disaring dari total _MAX_ data)",
                paginate: {
                    previous: "Sebelumnya",
                    next: "Berikutnya"
                }
            },
            columnDefs: [
                { targets: [0, 6, 7, 8, 9, 10, 11], className: 'text-center' }
            ]
        });
    });
</script>
@endsection
