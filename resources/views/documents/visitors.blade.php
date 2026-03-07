@extends("layouts.header")

@section('css')

<style>
    .dashboard-header {
        margin-bottom: 30px;
    }

    .dashboard-card {
        background: white;
        border-radius: 10px;
        padding: 20px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        transition: all 0.3s;
        height: 100%;
        display: flex;
        flex-direction: column;
    }

    .dashboard-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.12);
    }

    .dashboard-card .icon-circle {
        width: 45px;
        height: 45px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 12px;
        font-size: 20px;
        flex-shrink: 0;
    }

    .dashboard-card.total .icon-circle {
        background: #e3f2fd;
        color: #2196F3;
    }

    .dashboard-card.active .icon-circle {
        background: #d1e7dd;
        color: #0f5132;
    }

    .dashboard-card.inactive .icon-circle {
        background: #f8d7da;
        color: #842029;
    }

    .dashboard-card h2 {
        font-size: 28px;
        font-weight: 700;
        margin: 0 0 4px 0;
        color: #2c3e50;
        line-height: 1;
    }

    .dashboard-card p {
        margin: 0;
        font-size: 13px;
        color: #6c757d;
        font-weight: 500;
    }

    .users-section {
        background: white;
        border-radius: 10px;
        padding: 25px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }

    .section-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 25px;
        flex-wrap: wrap;
        gap: 15px;
    }

    .section-title {
        font-size: 20px;
        font-weight: 600;
        margin: 0;
        color: #2c3e50;
    }

    .btn-new-account {
        background: #800000;
        color: white;
        border: none;
        padding: 8px 16px;
        border-radius: 4px;
        font-size: 14px;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.3s;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .btn-new-account:hover {
        background: #6B0000;
        transform: translateY(-1px);
        color: white;
    }

    .btn-new-account i {
        margin-right: 6px;
    }

    .table-container {
        overflow-x: auto;
    }

    .modern-table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
    }

    .modern-table thead th {
        background: #f8f9fa;
        color: #495057;
        font-weight: 600;
        font-size: 13px;
        text-transform: uppercase;
        padding: 15px 12px;
        border-bottom: 2px solid #8B0000;
        white-space: nowrap;
    }

    .modern-table tbody td {
        padding: 12px;
        border-bottom: 1px solid #e9ecef;
        vertical-align: middle;
        font-size: 14px;
    }

    .modern-table tbody tr:hover {
        background: #f8f9fa;
    }

    .badge-status {
        padding: 5px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
        display: inline-block;
    }

    .badge-status.active {
        background: #e8f5e9;
        color: #4caf50;
    }

    .badge-status.inactive {
        background: #ffebee;
        color: #f44336;
    }

    .btn-action {
        padding: 6px 10px;
        border-radius: 4px;
        font-size: 13px;
        border: none;
        cursor: pointer;
        transition: all 0.3s;
        margin-right: 4px;
    }

    .btn-action.activate {
        background: #2196F3;
        color: white;
    }

    .btn-action.activate:hover {
        background: #1976D2;
    }

    .btn-action.change-pass {
        background: #ff9800;
        color: white;
    }

    .btn-action.change-pass:hover {
        background: #f57c00;
    }

    .btn-action.edit {
        background: #2196F3;
        color: white;
    }

    .btn-action.edit:hover {
        background: #1976D2;
    }

    .btn-action.deactivate {
        background: #f44336;
        color: white;
    }

    .btn-action.deactivate:hover {
        background: #d32f2f;
    }

    .dept-list {
        font-size: 12px;
        color: #6c757d;
        line-height: 1.6;
    }

    .dataTables_wrapper {
        padding-top: 20px;
    }

    .dataTables_wrapper .dataTables_length {
        float: right;
        margin-bottom: 15px;
    }

    .dataTables_wrapper .dataTables_length select {
        padding: 6px 30px 6px 10px;
        border: 1px solid #dee2e6;
        border-radius: 4px;
        margin: 0 5px;
    }

    .dataTables_wrapper .dataTables_filter {
        float: left;
        margin-bottom: 15px;
    }

    .dataTables_wrapper .dataTables_filter input {
        padding: 6px 12px;
        border: 1px solid #dee2e6;
        border-radius: 4px;
        margin-left: 5px;
    }

    .dataTables_wrapper .dataTables_info {
        float: left;
        padding-top: 8px;
    }

    .dataTables_wrapper .dataTables_paginate {
        float: right;
        margin-top: 15px;
    }

    .dataTables_wrapper .dataTables_paginate .paginate_button {
        padding: 6px 12px;
        margin: 0 2px;
        border: 1px solid #dee2e6;
        border-radius: 4px;
        background: white;
        cursor: pointer;
    }

    .dataTables_wrapper .dataTables_paginate .paginate_button:hover {
        background: #f8f9fa;
    }

    .dataTables_wrapper .dataTables_paginate .paginate_button.current {
        background: #8B0000;
        color: white !important;
        border-color: #8B0000;
    }

    div.dt-buttons {
        float: right;
        margin-bottom: 15px;
        margin-right: 10px;
    }

    .dt-button {
        background: white !important;
        border: 1px solid #dee2e6 !important;
        color: #495057 !important;
        padding: 6px 12px !important;
        border-radius: 4px !important;
        margin-right: 5px !important;
        font-size: 13px !important;
    }

    .dt-button:hover {
        background: #f8f9fa !important;
        border-color: #8B0000 !important;
    }

    .dataTables_wrapper:after {
        content: "";
        display: table;
        clear: both;
    }

    .table-container {
        clear: both;
    }

    .dataTables_processing {
        position: absolute;
        top: 50%;
        left: 50%;
        width: 200px;
        margin-left: -100px;
        margin-top: -26px;
        text-align: center;
        padding: 20px;
        background: white;
        border: 1px solid #ddd;
        border-radius: 4px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }

    @media (max-width: 768px) {
        .dashboard-card {
            margin-bottom: 15px;
        }
    }
</style>
@endsection

@section("content")
<div class="row mb-4 dashboard-header">
    <div class="col-12">
        <h4 class="mb-0">Document Visitors — {{ $document->control_code }}</h4>
        <p class="text-muted mb-0">List of users who viewed this document</p>
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-xl-4 col-md-6">
        <div class="dashboard-card active">
            <div class="icon-circle"><i class="fa fa-eye"></i></div>
            <h2>{{ $document->visitor->count() }}</h2>
            <p>Total Visits</p>
        </div>
    </div>
    <div class="col-xl-4 col-md-6">
        <div class="dashboard-card total">
            <div class="icon-circle"><i class="fa fa-users"></i></div>
            <h2>{{ $document->visitor->unique('user_id')->count() }}</h2>
            <p>Unique Visitors</p>
        </div>
    </div>
</div>

<div class="users-section mb-5">
    <div class="section-header">
        <h5 class="section-title">Visitors</h5>
    </div>

    <div class="table-container">
        <table class="modern-table" id="visitorsTable">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Time</th>
                    <th>Name</th>
                    <th>Department</th>
                    <th>Count Visits</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($document->visitor->groupBy('user_id') as $userId => $visits)
                @php $sortedVisits = $visits->sortByDesc('created_at'); @endphp
                <tr>
                    <td><small>{{ date('M d, Y', strtotime($sortedVisits->first()->created_at)) }}</small></td>
                    <td><small>{{ date('h:i A', strtotime($sortedVisits->first()->created_at)) }}</small></td>
                    <td>
                        <div class="d-flex align-items-center gap-2">
                            <div class="avatar-sm">
                                <img src="{{ asset("images/no_image.png") }}" alt="" class="rounded-circle img-fluid" style="width:32px;height:32px;object-fit:cover;">
                            </div>
                            <span>{{ $sortedVisits->first()->user->name }}</span>
                        </div>
                    </td>
                    <td><small class="text-muted">{{ $sortedVisits->first()->user->department->name ?? '—' }}</small></td>
                    <td>
                        <span class="badge bg-primary visit-badge" 
                            style="cursor: pointer;" 
                            data-name="{{ $sortedVisits->first()->user->name }}"
                            data-visits='@json($sortedVisits->pluck('created_at')->map(fn($d) => date('M d, Y h:i A', strtotime($d))))'>
                            {{ $visits->count() }} {{ $visits->count() > 1 ? 'visits' : 'visit' }}
                        </span>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<div class="modal fade" id="visitsModal" tabindex="-1" aria-labelledby="visitsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content border-0 shadow">
            <div class="modal-header text-black">
                <h5 class="modal-title" id="visitsModalLabel">
                    <i class="ri-history-line me-2"></i>Visit History — <span id="modalUserName"></span>
                </h5>
                <button type="button" class="btn-close btn-close-black" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-0">
                <table class="table table-hover table-bordered mb-0" id="visitsHistoryTable">
                    <thead class="table-light">
                        <tr>
                            <th style="width: 60px;">#</th>
                            <th>Date & Time</th>
                            <th style="width: 100px;">Status</th>
                        </tr>
                    </thead>
                    <tbody id="visitsHistoryList"></tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section("js")
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const modal = document.getElementById('visitsModal');
        if (modal) document.body.appendChild(modal);
    });

    $(document).on('click', '.visit-badge', function() {
        const name = $(this).attr('data-name');
        const visitsRaw = $(this).attr('data-visits');
        
        let visits = [];
        try {
            visits = JSON.parse(visitsRaw);
        } catch(e) {
            console.log('parse error', e);
            return;
        }

        $('#modalUserName').text(name);

        const list = document.getElementById('visitsHistoryList');
        list.innerHTML = '';

        if (visits.length === 0) {
            list.innerHTML = '<li class="list-group-item text-muted text-center py-3">No visit records found.</li>';
        } else {
            visits.forEach(function(date, index) {
                list.innerHTML += `
                    <tr>
                        <td><span class="badge bg-secondary">${index + 1}</span></td>
                        <td>${date}</td>
                        <td>${index === 0 ? '<span class="badge bg-success">Latest</span>' : '<span class="badge bg-light text-muted">—</span>'}</td>
                    </tr>
                `;
            });
        }

        var modal = new bootstrap.Modal(document.getElementById('visitsModal'));
        modal.show();
    });
</script>
@endsection