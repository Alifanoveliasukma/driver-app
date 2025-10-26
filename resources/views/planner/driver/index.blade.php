@extends('layouts.template-planner')

@section('title', 'Data Driver')

@section('content')
<div class="container py-3">
    <div class="d-flex align-items-center mb-4">
        <button class="btn btn-outline-primary me-3" onclick="window.history.back()" aria-label="Kembali">
            <i class="bi bi-chevron-left"></i>
        </button>
        <h4 class="fw-bold text-primary mb-0">Daftar Driver</h4>

        <div class="ms-auto">
            <a href="{{ route('driver.create.step.one') }}" class="btn btn-primary">
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
                            <th>Nama Driver</th>
                            <th>No Telepon</th>
                            <th>Plat Nomor</th>
                            <th>Status</th>
                            <th width="150px">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        {{-- Contoh data statis (nanti bisa diganti pakai loop dari database) --}}
                        <tr>
                            <td>1</td>
                            <td>Ahmad Yusuf</td>
                            <td>08123456789</td>
                            <td>B 1234 XYZ</td>
                            <td><span class="badge bg-success">Aktif</span></td>
                            <td class="text-center">
                                <a href="{{ route('driver.detail', 1) }}" class="btn btn-sm btn-info text-white"><i class="bi bi-eye"></i></a>
                                <a href="{{ route('driver.edit', 1) }}" class="btn btn-sm btn-warning text-white"><i class="bi bi-pencil"></i></a>
                            </td>
                        </tr>
                        <tr>
                            <td>2</td>
                            <td>Rudi Hartono</td>
                            <td>08987654321</td>
                            <td>B 9876 ABC</td>
                            <td><span class="badge bg-danger">Nonaktif</span></td>
                            <td class="text-center">
                                <a href="{{ route('driver.detail', 2) }}" class="btn btn-sm btn-info text-white"><i class="bi bi-eye"></i></a>
                                <a href="{{ route('driver.edit', 2) }}" class="btn btn-sm btn-warning text-white"><i class="bi bi-pencil"></i></a>
                            </td>
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
            }
        });
    });
</script>
@endsection
