@extends('layouts.template-planner')


@section('content')
    <div class="container-fluid mt-4">
        <h4 class="mb-4">Transport Status History</h4>
        <!-- ✅ Search Form -->
        <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <form action="{{ route('histori.planner') }}" method="GET" class="row g-3 align-items-center">
                        <div class="col-md-8">
                            <div class="input-group">
                                <input type="text" 
                                        name="search" 
                                        class="form-control" 
                                        placeholder="Cari berdasarkan Search Key, Customer Name, Fleet Name, atau PO Number..." 
                                        value="{{ request('search') }}"
                                        aria-label="Search">
                                <button class="btn btn-primary" type="submit">
                                    <i class="fas fa-search"></i> Search
                                </button>
                            </div>
                        </div>
                        <div class="col-md-4">
                            @if(request('search'))
                            <div class="d-flex align-items-center">
                                <span class="badge bg-info me-2">
                                    {{ $data->total() }} hasil ditemukan
                                </span>
                                <a href="{{ route('histori.planner') }}" class="btn btn-sm btn-outline-secondary">
                                    <i class="fas fa-times"></i> Clear
                                </a>
                            </div>
                            @endif
                        </div>
                    </form>
                </div>
            </div>

        <div class="card shadow-sm">
            <div class="card-body">
                @if(request('search') && $data->total() == 0)
                    <div class="alert alert-warning mb-4">
                        <i class="fas fa-exclamation-triangle"></i> 
                        Tidak ada data yang ditemukan untuk pencarian "<strong>{{ request('search') }}</strong>"
                    </div>
                    @endif
                <div class="table-responsive">
                    <table class="table table-bordered table-striped align-middle">
                        <thead class="table-primary text-center">
                            <tr>
                                <th>#</th>
                                <th>Search Key</th>
                                <th>CO Number</th>
                                <th>Customer</th>
                                <th>Route</th>
                                <th>ETA</th>
                                <th>ETD</th>
                                <th>Area Type</th>
                                <th>Fleet</th>
                                <th>Status</th>
                                <th>Action</th>

                            </tr>
                        </thead>
                        <tbody>
                            @forelse($data as $i => $r)
                                <tr>
                                    <td class="text-center">{{ (($data->currentPage()-1)*$data->perPage())+$i+1 }}</td>
                                    <td>{{ $r['value'] ?? '-' }}</td>
                                    <td>{{ $r['ponumber'] ?? '-' }}</td>
                                    <td>{{ $r->bpartner->name ?? '-' }}</td>
                                    <td>{{ $r['route'] ?? '-' }}</td>
                                    <td>{{ isset($r['eta']) ? explode(' ', $r['eta'])[0] : '-' }}</td>
                                    <td>{{ isset($r['etd']) ? explode(' ', $r['etd'])[0] : '-' }}</td>
                                    <td>{{ $r['areatype'] ?? '-' }}</td>
                                    <td>
                                            {{ $r['fleet']['name'] ?? '-' }}
                                    </td>
                                    <td>
                                        @if(request('search') && stripos($r['Status'] ?? '', request('search')) !== false)
                                            {!! highlightText($r['Status'] ?? '-', request('search')) !!}
                                        @else
                                            {{ $r['Status'] ?? '-' }}
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <a href="{{ route('histori.planner.detail', ['id' => $r['xx_transorder_id']]) }}"
                                            class="btn btn-sm btn-primary">
                                            Detail
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="10" class="text-center text-muted">
                                        @if(request('search'))
                                            Tidak ada data yang cocok dengan pencarian Anda.
                                        @else
                                            Tidak ada data ditemukan.
                                        @endif
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{ $data->links('vendor.pagination.bootstrap-4') }}
            </div>
        </div>
    </div>
@endsection