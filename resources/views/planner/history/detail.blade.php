@extends('layouts.template-planner')

@section('content')
<div class="container">
    <h4 class="mb-4">Transport Detail (Dummy)</h4>

    <form>
        <div class="row mb-3">
            <div class="col-md-6">
                <label class="form-label">Search Key</label>
                <input type="text" class="form-control"  value="{{ $data['Value'] ?? '-' }}" readonly>
            </div>
            <div class="col-md-6">
                <label class="form-label">CO Number</label>
                <input type="text" class="form-control" value="{{ $data['PONumber'] ?? '-' }}" readonly>
            </div>
        </div>

        {{-- Tambahkan kolom Product di bawah CO Number --}}
        <div class="row mb-3">
            <div class="col-md-6">
                <label class="form-label">Customer <span class="text-danger">*</span></label>
                <input type="text" class="form-control" value="{{ $data['Customer_ID'] ?? '-' }}" readonly>
            </div>
            <div class="col-md-6">
                <label class="form-label">Product</label>
                <input type="text" class="form-control" value="{{ $data['M_Product_ID'] ?? '-' }}" readonly>
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-md-6">
                <label class="form-label">Fleet <span class="text-danger">*</span></label>
                <input type="text" class="form-control" value="{{ $data['XM_Fleet_ID'] ?? '-' }}" readonly>
            </div>
            <div class="col-md-6">
                <label class="form-label">Driver <span class="text-danger">*</span></label>
                <input type="text" class="form-control" value="{{ $data['XM_Driver_ID'] ?? '-' }}" readonly>
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-md-6">
                <label class="form-label">Route</label>
                <input type="text" class="form-control" value="{{ $data['Route'] ?? '-' }}" readonly>
            </div>
            <div class="col-md-6">
                <label class="form-label">Status</label>
                <input type="text" class="form-control" value="{{ $data['Status'] ?? '-' }}" readonly>
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-md-6">
                <label class="form-label">Area Type</label>
                <input type="text" class="form-control" value="{{ $data['AreaType'] ?? '-' }}" readonly>
            </div>
            <div class="col-md-6">
            <label class="form-label">ETD</label>
            <input type="text" class="form-control" 
                value="{{ isset($data['ETD']) ? \Carbon\Carbon::parse($data['ETD'])->format('Y-m-d') : '-' }}" 
                readonly>
        </div>
        </div>

        <div class="row mb-3">
            <div class="col-md-6">
                <label class="form-label">Loading Date</label>
                <input type="date" class="form-control" value="">
            </div>
            <div class="col-md-6">
                <label class="form-label">Unloading Date</label>
                <input type="date" class="form-control" value="">
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-md-6">
                <label class="form-label">Loading Date (Start)</label>
                <input type="date" class="form-control" value="">
            </div>
            <div class="col-md-6">
                <label class="form-label">Unloading Date (Start)</label>
                <input type="date" class="form-control" value="">
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-md-6">
                <label class="form-label">Loading Date (Out)</label>
                <input type="date" class="form-control" value="">
            </div>
            <div class="col-md-6">
                <label class="form-label">Unloading Date (Out)</label>
                <input type="date" class="form-control" value="">
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-md-6">
                <label class="form-label">Loading Standby</label>
                <input type="text" class="form-control" value="">
            </div>
            <div class="col-md-6">
                <label class="form-label">Unloading Standby</label>
                <input type="text" class="form-control" value="">
            </div>
        </div>

        <!-- <div class="row mb-4">
            <div class="col-md-6">
                <button type="button" class="btn btn-primary w-100">Add Transport Status</button>
            </div>
            <div class="col-md-6">
                <button type="button" class="btn btn-success w-100">Release Driver Fleet</button>
            </div>
        </div> -->

        <div class="row mb-3">
            <div class="col-md-6">
                <label class="form-label">Client <span class="text-danger">*</span></label>
                <input type="text" class="form-control" value="MZL" readonly>
            </div>
            <div class="col-md-6">
                <label class="form-label">Organization <span class="text-danger">*</span></label>
                <input type="text" class="form-control" value="*" readonly>
            </div>
        </div>

        <div class="form-check mb-3">
            <input type="checkbox" class="form-check-input" id="send">
            <label class="form-check-label" for="send">Send</label>
        </div>

        <a href="{{ route('histori.planner.all') }}" class="btn btn-secondary">Back</a>
    </form>

    <hr class="my-5">

    <div class="card">
        <div class="card-header bg-light">
            <h5 class="mb-0">Transport Status History</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle">
                    <thead class="table-secondary text-center">
                        <tr>
                            <th>Transport Sales</th>
                            <th>Reference</th>
                            <th>Status</th>
                            <th>Document Date</th>
                            <th>Note</th>
                            <th>Document Directory</th>
                            <th>Document Directory 2</th>
                        </tr>
                    </thead>
                    <tbody>
                        {{-- Dummy data --}}
                        <tr>
                            <td>11866/MZL-TS/IX/2025</td>
                            <td>AUD</td>
                            <td>FINISHED</td>
                            <td>Sep 30, 2025 6:33:01 PM WIB</td>
                            <td>driver confirmation</td>
                            <td><a href="https://ops-mzl.karyakoe.id/storage/foto-surat-jalan/UWos" target="_blank">View File</a></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>11866/MZL-TS/IX/2025</td>
                            <td>AUD</td>
                            <td>UNLOAD</td>
                            <td>Sep 30, 2025 6:32:52 PM WIB</td>
                            <td>driver confirmation</td>
                            <td><a href="https://ops-mzl.karyakoe.id/storage/mua1" target="_blank">View File</a></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>11866/MZL-TS/IX/2025</td>
                            <td>AUD</td>
                            <td>WAIT FOR UNLOAD</td>
                            <td>Sep 30, 2025 6:32:44 PM WIB</td>
                            <td>driver confirmation</td>
                            <td></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>11866/MZL-TS/IX/2025</td>
                            <td>AUD</td>
                            <td>SHIPMENT</td>
                            <td>Sep 30, 2025 6:32:41 PM WIB</td>
                            <td>driver confirmation</td>
                            <td><a href="https://ops-mzl.karyakoe.id/storage/supir" target="_blank">View File</a></td>
                            <td><a href="https://ops-mzl.karyakoe.id/storage/dokumen" target="_blank">View File</a></td>
                        </tr>
                        <tr>
                            <td>11866/MZL-TS/IX/2025</td>
                            <td>AUD</td>
                            <td>LOAD</td>
                            <td>Sep 30, 2025 5:06:09 PM WIB</td>
                            <td>driver confirmation</td>
                            <td></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>11866/MZL-TS/IX/2025</td>
                            <td>AUD</td>
                            <td>WAIT FOR LOAD</td>
                            <td>Sep 30, 2025 5:06:07 PM WIB</td>
                            <td>driver confirmation</td>
                            <td></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>11866/MZL-TS/IX/2025</td>
                            <td>AUD</td>
                            <td>ON THE WAY LOAD</td>
                            <td>Sep 30, 2025 5:06:04 PM WIB</td>
                            <td>driver confirmation</td>
                            <td></td>
                            <td></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
