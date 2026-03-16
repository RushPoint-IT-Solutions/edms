@extends('layouts.header')

@section('content')

<div class="d-flex justify-content-between align-items-start flex-wrap gap-3 mb-4">
    <div>
        <h4 class="fw-semibold mb-1">Reports</h4>
        <p class="text-muted mb-0">Analyze, filter and export system data</p>
    </div>
    <div class="d-flex align-items-center gap-2 flex-wrap">
        <span class="text-muted small">From</span>
        <input type="date" id="global-from" class="form-control form-control-sm" style="width:150px;">
        <span class="text-muted small">To</span>
        <input type="date" id="global-to" class="form-control form-control-sm" style="width:150px;">
        <button class="btn btn-sm btn-dark" id="global-filter-btn">
            <i class="ri-filter-line me-1"></i>Apply
        </button>
        <button class="btn btn-sm btn-outline-secondary" id="global-reset-btn">
            <i class="ri-refresh-line"></i>
        </button>
    </div>
</div>

<div class="row g-3 mb-4 h-100">
    <div class="col-xl-2 col-md-4">
        <div class="dashboard-card pending">
            <div class="icon-circle">
                <i class="ri-file-list-3-line"></i>
            </div>
            <h2 class="mb-0 font-weight-bold" id="stat-total">—</h2>
            <p>Total</p>
        </div>
    </div>
    <div class="col-xl-2 col-md-4">
        <div class="dashboard-card approved">
            <div class="icon-circle">
                <i class="ri-checkbox-circle-line"></i>
            </div>
            <h2 class="mb-0 font-weight-bold" id="stat-approved">—</h2>
            <p>Approved</p>
        </div>
    </div>
    <div class="col-xl-2 col-md-4">
        <div class="dashboard-card declined">
            <div class="icon-circle">
                <i class="ri-time-line"></i>
            </div>
            <h2 class="mb-0 font-weight-bold" id="stat-for-approval">—</h2>
            <p>For Approval</p>
        </div>
    </div>
    <div class="col-xl-2 col-md-4">
        <div class="dashboard-card returned">
            <div class="icon-circle">
                <i class="ri-close-circle-line"></i>
            </div>
            <h2 class="mb-0 font-weight-bold" id="stat-declined">—</h2>
            <p>Declined</p>
        </div>
    </div>
    <div class="col-xl-2 col-md-4">
        <div class="dashboard-card returned">
            <div class="icon-circle">
                <i class="ri-arrow-go-back-line"></i>
            </div>
            <h2 class="mb-0 font-weight-bold" id="stat-returned">—</h2>
            <p>Returned</p>
        </div>
    </div>
    <div class="col-xl-2 col-md-4">
        <div class="dashboard-card pending">
            <div class="icon-circle">
                <i class="ri-draft-line"></i>
            </div>
            <h2 class="mb-0 font-weight-bold" id="stat-draft">—</h2>
            <p>Draft</p>
        </div>
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-lg-3">
        <div class="card shadow-sm h-100">
            <div class="card-body">
                <h6 class="fw-semibold mb-1">Status Breakdown</h6>
                <p class="text-muted small mb-3">Current distribution</p>
                <div style="height:200px; display:flex; align-items:center; justify-content:center;">
                    <canvas id="statusDonutChart"></canvas>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-5">
        <div class="card shadow-sm h-100">
            <div class="card-body">
                <h6 class="fw-semibold mb-1">Monthly Submissions</h6>
                <p class="text-muted small mb-3">Submitted vs Approved — {{ date('Y') }}</p>
                <div style="height:200px;">
                    <canvas id="monthlyBarChart"></canvas>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card shadow-sm h-100">
            <div class="card-body">
                <h6 class="fw-semibold mb-1">By Department</h6>
                <p class="text-muted small mb-3">Top 6 departments</p>
                <div style="height:200px;">
                    <canvas id="deptBarChart"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="d-flex gap-2 flex-wrap mb-3">
    <button class="btn btn-sm btn-outline-secondary report-tab-btn active" data-tab="change_requests">
        <i class="ri-file-list-3-line me-1"></i>Change Requests
    </button>
    <button class="btn btn-sm btn-outline-secondary report-tab-btn" data-tab="documents">
        <i class="ri-folder-2-line me-1"></i>Documents
    </button>
    <button class="btn btn-sm btn-outline-secondary report-tab-btn" data-tab="approver_activity">
        <i class="ri-shield-check-line me-1"></i>Approver Activity
    </button>
    <button class="btn btn-sm btn-outline-secondary report-tab-btn" data-tab="department_summary">
        <i class="ri-building-2-line me-1"></i>Dept. Summary
    </button>
</div>

<div class="report-section active" id="section-change_requests">
    <div class="card shadow-sm mb-3">
        <div class="card-body py-3">
            <div class="row g-2 align-items-end">
                <div class="col-md-2 col-6">
                    <label class="form-label small mb-1">Date From</label>
                    <input type="date" id="cr-from" class="form-control form-control-sm">
                </div>
                <div class="col-md-2 col-6">
                    <label class="form-label small mb-1">Date To</label>
                    <input type="date" id="cr-to" class="form-control form-control-sm">
                </div>
                <div class="col-md-2 col-6">
                    <label class="form-label small mb-1">Status</label>
                    <select id="cr-status" class="form-select form-select-sm">
                        <option value="">All Status</option>
                        <option>For Approval</option><option>Approved</option>
                        <option>Declined</option><option>Returned</option><option>Draft</option>
                    </select>
                </div>
                <div class="col-md-2 col-6">
                    <label class="form-label small mb-1">Department</label>
                    <select id="cr-department" class="form-select form-select-sm">
                        <option value="">All Departments</option>
                        @foreach($departments as $dept)
                            <option value="{{ $dept->id }}">{{ $dept->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2 col-6">
                    <label class="form-label small mb-1">Requested By</label>
                    <select id="cr-requested-by" class="form-select form-select-sm">
                        <option value="">All Users</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2 col-6">
                    <div class="d-flex gap-2">
                        <button class="btn btn-sm btn-dark flex-fill" id="cr-filter-btn"><i class="ri-filter-line me-1"></i>Filter</button>
                        <button class="btn btn-sm btn-outline-secondary" id="cr-reset-btn"><i class="ri-refresh-line"></i></button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="card shadow-sm">
        <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
            <h6 class="fw-semibold mb-0"><i class="ri-file-list-3-line me-2 text-muted"></i>Change Requests</h6>
            <div class="d-flex gap-2">
                <button class="btn btn-sm btn-outline-success" id="cr-export-csv"><i class="ri-file-excel-line me-1"></i>CSV</button>
                <button class="btn btn-sm btn-outline-danger" onclick="window.print()"><i class="ri-printer-line me-1"></i>Print</button>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover table-bordered mb-0" id="crTable">
                    <thead class="table-light">
                        <tr>
                            <th>Doc ID</th><th>Title</th><th>Category</th><th>Department</th>
                            <th>Requested By</th><th>Approvers</th><th>Date</th><th>Updated</th><th>Status</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
        <div class="card-footer bg-white d-flex justify-content-between align-items-center flex-wrap gap-2 py-2">
            <div id="cr-info-control"></div>
            <div id="cr-pagination-control"></div>
        </div>
    </div>
</div>

<div class="report-section" id="section-documents">
    <div class="card shadow-sm mb-3">
        <div class="card-body py-3">
            <div class="row g-2 align-items-end">
                <div class="col-md-2 col-6">
                    <label class="form-label small mb-1">Date From</label>
                    <input type="date" id="doc-from" class="form-control form-control-sm">
                </div>
                <div class="col-md-2 col-6">
                    <label class="form-label small mb-1">Date To</label>
                    <input type="date" id="doc-to" class="form-control form-control-sm">
                </div>
                <div class="col-md-3 col-6">
                    <label class="form-label small mb-1">Department</label>
                    <select id="doc-department" class="form-select form-select-sm">
                        <option value="">All Departments</option>
                        @foreach($departments as $dept)
                            <option value="{{ $dept->id }}">{{ $dept->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2 col-6">
                    <div class="d-flex gap-2">
                        <button class="btn btn-sm btn-dark flex-fill" id="doc-filter-btn"><i class="ri-filter-line me-1"></i>Filter</button>
                        <button class="btn btn-sm btn-outline-secondary" id="doc-reset-btn"><i class="ri-refresh-line"></i></button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="card shadow-sm">
        <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
            <h6 class="fw-semibold mb-0"><i class="ri-folder-2-line me-2 text-muted"></i>Documents</h6>
            <div class="d-flex gap-2">
                <button class="btn btn-sm btn-outline-success" id="doc-export-csv"><i class="ri-file-excel-line me-1"></i>CSV</button>
                <button class="btn btn-sm btn-outline-danger" onclick="window.print()"><i class="ri-printer-line me-1"></i>Print</button>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover table-bordered mb-0" id="docTable">
                    <thead class="table-light">
                        <tr>
                            <th>Control Code</th><th>Title</th><th>Category</th><th>Department</th>
                            <th>Uploaded By</th><th>Version</th><th>Date Approved</th><th>Date Created</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
        <div class="card-footer bg-white d-flex justify-content-between align-items-center flex-wrap gap-2 py-2">
            <div id="doc-info-control"></div>
            <div id="doc-pagination-control"></div>
        </div>
    </div>
</div>

<div class="report-section" id="section-approver_activity">
    <div class="card shadow-sm mb-3">
        <div class="card-body py-3">
            <div class="row g-2 align-items-end">
                <div class="col-md-2 col-6">
                    <label class="form-label small mb-1">Date From</label>
                    <input type="date" id="ap-from" class="form-control form-control-sm">
                </div>
                <div class="col-md-2 col-6">
                    <label class="form-label small mb-1">Date To</label>
                    <input type="date" id="ap-to" class="form-control form-control-sm">
                </div>
                <div class="col-md-2 col-6">
                    <label class="form-label small mb-1">Action</label>
                    <select id="ap-status" class="form-select form-select-sm">
                        <option value="">All Actions</option>
                        <option>Approved</option><option>Declined</option><option>Returned</option>
                    </select>
                </div>
                <div class="col-md-2 col-6">
                    <label class="form-label small mb-1">Approver</label>
                    <select id="ap-approver" class="form-select form-select-sm">
                        <option value="">All Approvers</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2 col-6">
                    <div class="d-flex gap-2">
                        <button class="btn btn-sm btn-dark flex-fill" id="ap-filter-btn"><i class="ri-filter-line me-1"></i>Filter</button>
                        <button class="btn btn-sm btn-outline-secondary" id="ap-reset-btn"><i class="ri-refresh-line"></i></button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="card shadow-sm">
        <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
            <h6 class="fw-semibold mb-0"><i class="ri-shield-check-line me-2 text-muted"></i>Approver Activity</h6>
            <div class="d-flex gap-2">
                <button class="btn btn-sm btn-outline-success" id="ap-export-csv"><i class="ri-file-excel-line me-1"></i>CSV</button>
                <button class="btn btn-sm btn-outline-danger" onclick="window.print()"><i class="ri-printer-line me-1"></i>Print</button>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover table-bordered mb-0" id="apTable">
                    <thead class="table-light">
                        <tr>
                            <th>Doc ID</th><th>Document Title</th><th>Approver</th><th>Department</th>
                            <th>Level</th><th>Action</th><th>Date</th><th>Remarks</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
        <div class="card-footer bg-white d-flex justify-content-between align-items-center flex-wrap gap-2 py-2">
            <div id="ap-info-control"></div>
            <div id="ap-pagination-control"></div>
        </div>
    </div>
</div>

<div class="report-section" id="section-department_summary">
    <div class="card shadow-sm mb-3">
        <div class="card-body py-3">
            <div class="row g-2 align-items-end">
                <div class="col-md-2 col-6">
                    <label class="form-label small mb-1">Date From</label>
                    <input type="date" id="ds-from" class="form-control form-control-sm">
                </div>
                <div class="col-md-2 col-6">
                    <label class="form-label small mb-1">Date To</label>
                    <input type="date" id="ds-to" class="form-control form-control-sm">
                </div>
                <div class="col-md-2 col-6">
                    <div class="d-flex gap-2">
                        <button class="btn btn-sm btn-dark flex-fill" id="ds-filter-btn"><i class="ri-filter-line me-1"></i>Filter</button>
                        <button class="btn btn-sm btn-outline-secondary" id="ds-reset-btn"><i class="ri-refresh-line"></i></button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="card shadow-sm">
        <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
            <h6 class="fw-semibold mb-0"><i class="ri-building-2-line me-2 text-muted"></i>Department Summary</h6>
            <div class="d-flex gap-2">
                <button class="btn btn-sm btn-outline-success" id="ds-export-csv"><i class="ri-file-excel-line me-1"></i>CSV</button>
                <button class="btn btn-sm btn-outline-danger" onclick="window.print()"><i class="ri-printer-line me-1"></i>Print</button>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover table-bordered mb-0" id="dsTable">
                    <thead class="table-light">
                        <tr>
                            <th>Department</th><th>Code</th><th>Total</th>
                            <th>Approved</th><th>For Approval</th><th>Declined</th><th>Returned</th><th>Draft</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
        <div class="card-footer bg-white d-flex justify-content-between align-items-center flex-wrap gap-2 py-2">
            <div id="ds-info-control"></div>
            <div id="ds-pagination-control"></div>
        </div>
    </div>
</div>

@endsection

@section('js')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
    $(document).ready(function () {

        let statusChart = null;
        let monthlyChart = null;
        let deptChart = null;

        function initCharts(d) {
            if (statusChart) statusChart.destroy();
            if (monthlyChart) monthlyChart.destroy();
            if (deptChart) deptChart.destroy();

            statusChart = new Chart(document.getElementById('statusDonutChart'), {
                type: 'doughnut',
                data: {
                    labels: d.statusLabels,
                    datasets: [{
                        data: d.statusCounts,
                        backgroundColor: ['#198754', '#0d6efd', '#dc3545', '#ffc107', '#6c757d'],
                        borderWidth: 2,
                        borderColor: '#fff',
                        hoverOffset: 5
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    cutout: '65%',
                    plugins: {
                        legend: { position: 'bottom', labels: { font: { size: 10 }, padding: 8 } },
                        tooltip: { callbacks: { label: ctx => '  ' + ctx.label + ': ' + ctx.parsed } }
                    }
                }
            });

            monthlyChart = new Chart(document.getElementById('monthlyBarChart'), {
                type: 'bar',
                data: {
                    labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                    datasets: [
                        { label: 'Submitted', data: d.monthly,         backgroundColor: '#8B0000', borderRadius: 4, borderSkipped: false },
                        { label: 'Approved',  data: d.monthlyApproved, backgroundColor: '#198754', borderRadius: 4, borderSkipped: false }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { position: 'top', labels: { font: { size: 10 } } },
                        tooltip: { mode: 'index', intersect: false }
                    },
                    scales: {
                        x: { grid: { display: false }, border: { display: false } },
                        y: { grid: { color: '#f0f0f0' }, border: { display: false }, ticks: { stepSize: 1 } }
                    }
                }
            });

            deptChart = new Chart(document.getElementById('deptBarChart'), {
                type: 'bar',
                data: {
                    labels: d.deptLabels,
                    datasets: [{
                        label: 'Submissions',
                        data: d.deptCounts,
                        backgroundColor: '#8B0000',
                        borderRadius: 4,
                        borderSkipped: false
                    }]
                },
                options: {
                    indexAxis: 'y',
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { display: false } },
                    scales: {
                        x: { grid: { color: '#f0f0f0' }, border: { display: false } },
                        y: { grid: { display: false }, border: { display: false } }
                    }
                }
            });
        }

        function loadSummary(from, to) {
            ['stat-total', 'stat-approved', 'stat-for-approval', 'stat-declined', 'stat-returned', 'stat-draft']
                .forEach(id => $('#' + id).text('—'));

            $.get('{{ url("reports/summary-stats") }}', { from, to }, function (d) {
                $('#stat-total').text(d.total);
                $('#stat-approved').text(d.approved);
                $('#stat-for-approval').text(d.forApproval);
                $('#stat-declined').text(d.declined);
                $('#stat-returned').text(d.returned);
                $('#stat-draft').text(d.draft);
                initCharts(d);
            });
        }

        loadSummary('', '');

        $('#global-filter-btn').on('click', () => loadSummary($('#global-from').val(), $('#global-to').val()));
        $('#global-reset-btn').on('click', () => { $('#global-from, #global-to').val(''); loadSummary('', ''); });

        $('.report-tab-btn').on('click', function () {
            $('.report-tab-btn').removeClass('active');
            $(this).addClass('active');
            $('.report-section').removeClass('active');
            $('#section-' + $(this).data('tab')).addClass('active');
        });

        function exportCsv(type, filters) {
            window.location.href = '{{ url("reports/export-csv") }}?' + new URLSearchParams({ type, ...filters }).toString();
        }

        function moveControls(tableId, prefix) {
            var w    = $('#' + tableId + '_wrapper');
            var info = w.find('.dataTables_info');
            var pag  = w.find('.dataTables_paginate');

            if (info.length) $('#' + prefix + '-info-control').empty().append(info.detach());
            if (pag.length)  $('#' + prefix + '-pagination-control').empty().append(pag.detach());
        }

        function makeTable(id, url, columns, prefix, order) {
            var filters = {};
            var dt = $('#' + id).DataTable({
                processing: true,
                serverSide: true,
                dom: 'rtip',
                ajax: { url: url, type: 'GET', data: d => $.extend(d, filters) },
                columns: columns,
                pageLength: 25,
                order: order || [[0, 'asc']],
                language: {
                    processing: '<div class="text-center py-3 text-muted small"><i class="fa fa-spinner fa-spin me-2"></i>Loading…</div>',
                    emptyTable:  '<div class="text-center py-3 text-muted small">No records found</div>',
                    zeroRecords: '<div class="text-muted small">No matching records</div>',
                },
                drawCallback: function () { moveControls(id, prefix); }
            });

            return {
                dt,
                setFilters: function (f) { Object.assign(filters, f); dt.ajax.reload(); },
                getFilters: function ()   { return filters; }
            };
        }

        var cr = makeTable('crTable', '{{ url("reports/change-requests") }}', [
            { data: 'doc_id' },
            { data: 'title' },
            { data: 'category' },
            { data: 'department' },
            { data: 'requested_by' },
            { data: 'approvers' },
            { data: 'created_at' },
            { data: 'updated_at' },
            { data: 'status', orderable: false }
        ], 'cr', [[6, 'desc']]);

        $('#cr-filter-btn').on('click', () => cr.setFilters({
            from: $('#cr-from').val(),
            to: $('#cr-to').val(),
            status: $('#cr-status').val(),
            department_id: $('#cr-department').val(),
            requested_by: $('#cr-requested-by').val()
        }));
        $('#cr-reset-btn').on('click', () => {
            $('#cr-from, #cr-to').val('');
            $('#cr-status, #cr-department, #cr-requested-by').val('');
            cr.setFilters({ from: '', to: '', status: '', department_id: '', requested_by: '' });
        });
        $('#cr-export-csv').on('click', () => exportCsv('change_requests', cr.getFilters()));

        var doc = makeTable('docTable', '{{ url("reports/documents") }}', [
            { data: 'control_code' },
            { data: 'title' },
            { data: 'category' },
            { data: 'department' },
            { data: 'uploaded_by' },
            { data: 'version' },
            { data: 'date_approved' },
            { data: 'created_at' }
        ], 'doc', [[7, 'desc']]);

        $('#doc-filter-btn').on('click', () => doc.setFilters({
            from: $('#doc-from').val(),
            to: $('#doc-to').val(),
            department_id: $('#doc-department').val()
        }));
        $('#doc-reset-btn').on('click', () => {
            $('#doc-from, #doc-to').val('');
            $('#doc-department').val('');
            doc.setFilters({ from: '', to: '', department_id: '' });
        });
        $('#doc-export-csv').on('click', () => exportCsv('documents', doc.getFilters()));

        var ap = makeTable('apTable', '{{ url("reports/approver-activity") }}', [
            { data: 'doc_id' },
            { data: 'document' },
            { data: 'approver' },
            { data: 'department' },
            { data: 'level' },
            { data: 'action', orderable: false },
            { data: 'date' },
            { data: 'remarks' }
        ], 'ap', [[6, 'desc']]);

        $('#ap-filter-btn').on('click', () => ap.setFilters({
            from: $('#ap-from').val(),
            to: $('#ap-to').val(),
            status: $('#ap-status').val(),
            approver_id: $('#ap-approver').val()
        }));
        $('#ap-reset-btn').on('click', () => {
            $('#ap-from, #ap-to').val('');
            $('#ap-status, #ap-approver').val('');
            ap.setFilters({ from: '', to: '', status: '', approver_id: '' });
        });
        $('#ap-export-csv').on('click', () => exportCsv('approver_activity', ap.getFilters()));

        var ds = makeTable('dsTable', '{{ url("reports/department-summary") }}', [
            { data: 'department' },
            { data: 'code' },
            { data: 'total' },
            { data: 'approved',    orderable: false },
            { data: 'for_approval', orderable: false },
            { data: 'declined',    orderable: false },
            { data: 'returned',    orderable: false },
            { data: 'draft',       orderable: false }
        ], 'ds', [[0, 'asc']]);

        $('#ds-filter-btn').on('click', () => ds.setFilters({
            from: $('#ds-from').val(),
            to: $('#ds-to').val()
        }));
        $('#ds-reset-btn').on('click', () => {
            $('#ds-from, #ds-to').val('');
            ds.setFilters({ from: '', to: '' });
        });
        $('#ds-export-csv').on('click', () => exportCsv('department_summary', ds.getFilters()));

    });
</script>
@endsection