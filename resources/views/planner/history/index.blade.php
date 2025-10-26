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
                            <th>No</th>
                            <th>Surat Jalan</th>
                            <th>ID Order</th>
                            <th>Tanggal Selesai</th>
                            <th>Customer</th>
                            <th>Route</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($orders as $i => $o)
                           @php
                                $noSp = data_get($o, 'Value', '-');
                                $etaRaw = data_get($o, 'ETA');
                                $etdRaw = data_get($o, 'ETD');
                                $customerName = data_get($o, 'Customer_Name');
                                $route = data_get($o, 'route');
                                $orderId = data_get($o, 'XX_TransOrder_ID');
                                $status = data_get($o, 'Status');

                                $etaClean = $etaRaw ? str_replace('.0', '', $etaRaw) : null;

                                try {
                                    $tglSelesai = $etaClean ? \Carbon\Carbon::parse($etaClean)->translatedFormat('d M Y') : '-';
                                } catch (\Exception $e) {
                                    $tglSelesai = '-';
                                }

                                // ✅ Versi aman PHP 7+
                                if (strtoupper($status) === 'FINISHED') {
                                    $badgeClass = 'success';
                                } elseif (in_array(strtoupper($status), ['ON GOING', 'IN PROGRESS'])) {
                                    $badgeClass = 'warning';
                                } elseif (strtoupper($status) === 'DRAFT') {
                                    $badgeClass = 'secondary';
                                } elseif (strtoupper($status) === 'CANCELLED') {
                                    $badgeClass = 'danger';
                                } else {
                                    $badgeClass = 'info';
                                }
                            @endphp


                            <tr>
                                <td class="text-center">{{ $i + 1 }}</td>
                                <td>{{ $noSp }}</td>
                                <td>{{ $orderId }}</td>
                                <td class="text-center">{{ $tglSelesai }}</td>
                                <td>{{ $customerName ?? '-' }}</td>
                                <td>{{ $route ?? '-' }}</td>
                                <td class="text-center">
                                    <span class="badge bg-{{ $badgeClass }}">{{ $status ?? '-' }}</span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted">Belum ada histori pengiriman.</td>
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
