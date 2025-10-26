@extends('layouts.template-planner')

@section('title', 'Histori Transport Planner')

@section('content')
<div class="container py-3">
    <div class="d-flex align-items-center mb-4">
        <button class="btn btn-outline-primary me-3" onclick="window.history.back()" aria-label="Kembali">
            <i class="bi bi-chevron-left"></i>
        </button>
        <h4 class="fw-bold text-primary mb-0">Histori Pengiriman - Planner</h4>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body">
            <div class="table-responsive">
                <table id="plannerHistoryTable" class="table table-bordered table-hover align-middle">
                    <thead class="table-primary text-center">
                        <tr>
                            <th>Transport Sales</th>
                            <th>Reference</th>
                            <th>Status</th>
                            <th>Document Date</th>
                            <th>Note</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($orders as $row)
                            @php
                                $transportSales = data_get($row, 'Value', '-');
                                $reference = data_get($row, 'Reference', '-');
                                $status = data_get($row, 'Status', '-');
                                $documentDate = data_get($row, 'DocumentDate', '-');
                                $note = data_get($row, 'Note', '-');
                            @endphp
                            <tr>
                                <td>{{ $transportSales }}</td>
                                <td>{{ $reference }}</td>
                                <td>
                                    @php
                                        $badgeClass = 'secondary';
                                        switch (strtoupper($status)) {
                                            case 'FINISHED': $badgeClass = 'success'; break;
                                            case 'SHIPMENT': $badgeClass = 'info'; break;
                                            case 'LOAD': $badgeClass = 'warning'; break;
                                            case 'UNLOAD': $badgeClass = 'primary'; break;
                                            case 'CANCELLED': $badgeClass = 'danger'; break;
                                        }
                                    @endphp
                                    <span class="badge bg-{{ $badgeClass }}">{{ $status }}</span>
                                </td>
                                <td>{{ $documentDate }}</td>
                                <td>{{ $note }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted">Tidak ada data histori untuk order ini.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- DataTables -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

<script>
    $(document).ready(function () {
        $('#plannerHistoryTable').DataTable({
            pageLength: 10,
            order: [[3, 'desc']], // urut berdasarkan tanggal selesai
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
