@extends('layouts.header')

@section('css')
<style>
.qms-kpi-card {
    display: flex;
    align-items: center;
    gap: 14px;
    height: 100%;
    padding: 18px 16px;

    border: 1px solid transparent;
    border-radius: 12px;

    transition: transform 0.2s, box-shadow 0.2s;
}

.qms-kpi-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 18px rgba(0, 0, 0, 0.1);
}

.qms-kpi-icon {
    display: flex;
    align-items: center;
    justify-content: center;

    width: 46px;
    height: 46px;

    border-radius: 12px;
    font-size: 1.3rem;
    flex-shrink: 0;
}

.qms-kpi-total {
    background: #fff8f8;
    border-color: #f5d0d0;
}

.qms-kpi-total .qms-kpi-icon {
    background: #8b0000;
    color: #ffffff;
}

.qms-kpi-pending {
    background: #fffbf0;
    border-color: #fde8b0;
}

.qms-kpi-pending .qms-kpi-icon {
    background: #e67e22;
    color: #ffffff;
}

.qms-kpi-approved {
    background: #f0faf4;
    border-color: #b7e4c7;
}

.qms-kpi-approved .qms-kpi-icon {
    background: #27ae60;
    color: #ffffff;
}

.qms-kpi-declined {
    background: #f8f9fa;
    border-color: #dee2e6;
}

.qms-kpi-declined .qms-kpi-icon {
    background: #6c757d;
    color: #ffffff;
}

.qms-kpi-value {
    font-size: 1.8rem;
    font-weight: 800;
    line-height: 1;

    color: #1a1a2e;
    font-variant-numeric: tabular-nums;
}

.qms-kpi-label {
    margin-top: 3px;

    font-size: 0.78rem;
    font-weight: 500;
    color: #6c757d;
}

.qms-kpi-trend {
    margin-top: 5px;

    font-size: 0.72rem;
    font-weight: 600;
}

.qms-kpi-trend.up {
    color: #27ae60;
}

.qms-kpi-trend.down {
    color: #c0392b;
}

.qms-kpi-trend.neutral {
    color: #95a5a6;
}

.qms-chart-card {
    border: 1px solid #f0f0f0 !important;
    border-radius: 7px !important;
}

.qms-chart-wrap {
    position: relative;
    width: 100%;
}

.qms-chart-wrap canvas {
    width: 100% !important;
}

.qms-legend-dot {
    display: flex;
    align-items: center;
    gap: 5px;

    font-size: 0.75rem;
    color: #6c757d;
}

.qms-legend-dot::before {
    content: "";

    width: 8px;
    height: 8px;

    border-radius: 50%;
    background: var(--dot);
    display: inline-block;
}

.qms-donut-legend {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
}

.qms-donut-item {
    display: flex;
    align-items: center;
    gap: 6px;

    flex: 1 1 calc(50% - 4px);

    font-size: 0.75rem;
    color: #495057;
}

.qms-donut-item span {
    width: 10px;
    height: 10px;

    border-radius: 3px;
    display: inline-block;
    flex-shrink: 0;
}

.qms-donut-item strong {
    margin-left: auto;
    color: #1a1a2e;
}

.qms-weekly-stats {
    padding-top: 12px;
    border-top: 1px solid #f0f0f0;
}

.file-card {
    position: relative;
    z-index: 1;
    width: 100%;

    transition: all 0.3s ease;
}

.file-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
}

.file-card .more-btn {
    opacity: 0;
    transition: opacity 0.3s ease;
}

.file-card:hover .more-btn,
.file-card.dropdown-open .more-btn {
    opacity: 1;
}

.file-more-btn {
    background-color: #ffffff !important;

    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    transition: all 0.2s ease;
}

.file-more-btn:hover {
    background-color: #f8f9fa !important;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.15);
}

.file-more-btn:active {
    background-color: #e9ecef !important;

    transform: scale(0.95);
    box-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);
}

.file-dropdown-menu {
    position: fixed;

    min-width: 200px;
    display: none;

    background: #ffffff;
    border-radius: 12px;
    overflow: hidden;

    z-index: 1025;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);

    animation: dropdownFadeIn 0.15s ease-out;
}

.file-dropdown-menu.show {
    display: block;
}

@keyframes dropdownFadeIn {

    from {
        opacity: 0;
        transform: translateY(-5px);
    }

    to {
        opacity: 1;
        transform: translateY(0);
    }

}

.file-dropdown-item {
    display: flex;
    align-items: center;
    gap: 12px;

    width: 100%;
    padding: 12px 16px;

    border: none;
    background: none;

    cursor: pointer;
    user-select: none;

    font-size: 0.875rem;
    font-weight: 500;
    text-align: left;
    color: #212529;

    transition: all 0.2s ease;
}

.file-dropdown-item:hover {
    background-color: #f8f9fa;
}

.file-dropdown-item:active {
    background-color: #e9ecef;
    transform: scale(0.98);
}

.file-dropdown-item i {
    width: 20px;
    text-align: center;
    transition: transform 0.2s ease;
}

.file-dropdown-item:hover i {
    transform: scale(1.1);
}

.file-dropdown-divider {
    height: 1px;
    margin: 4px 0;
    background-color: #dee2e6;
}

.file-dropdown-item.danger {
    color: #dc3545;
}

.file-dropdown-item.danger:hover {
    background-color: #fee;
}

.drive-list-container {
    background: #ffffff;
    border-radius: 8px;
    overflow: hidden;
}

.drive-list-header {
    padding: 8px 16px;

    background: #f8f9fa;
    border-bottom: 1px solid #e0e0e0;

    font-size: 0.75rem;
    font-weight: 600;

    color: #5f6368;
    text-transform: uppercase;
    letter-spacing: 0.3px;
}

.drive-list-row {
    display: flex;
    align-items: center;
    gap: 16px;

    width: 100%;
}

.drive-list-item {
    padding: 8px 16px;

    border-bottom: 1px solid #f0f0f0;

    cursor: pointer;
    position: relative;

    transition: background-color 0.2s ease;
}

.drive-list-item:hover {
    background-color: #f8f9fa;
}

.drive-list-item:last-child {
    border-bottom: none;
}

.drive-col-name {
    flex: 1;
    min-width: 0;
    padding-right: 12px;
}

.drive-col-owner {
    width: 110px;
    flex-shrink: 0;
}

.drive-col-dept {
    width: 180px;
    flex-shrink: 0;
}

.drive-col-modified {
    width: 120px;
    flex-shrink: 0;
}

.drive-col-size {
    width: 80px;
    text-align: right;
    flex-shrink: 0;
}

.drive-col-actions {
    width: 48px;

    display: flex;
    justify-content: center;
    align-items: center;

    flex-shrink: 0;
}

.file-name {
    font-size: 0.875rem;
    font-weight: 500;

    color: #202124;

    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.file-subtitle {
    font-size: 0.75rem;
    color: #5f6368;

    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.modern-table {
    width: 100%;
    border-collapse: separate;
    border-spacing: 0;
}

.modern-table thead th {
    padding: 15px 12px;

    font-size: 13px;
    font-weight: 600;

    text-transform: uppercase;

    color: #495057;
    background: #f8f9fa;

    border-bottom: 2px solid #8b0000;
}

.modern-table tbody td {
    padding: 12px;

    font-size: 14px;
    vertical-align: middle;

    border-bottom: 1px solid #e9ecef;
}

.modern-table tbody tr:hover {
    background: #f8f9fa;
}

.meta-tag {
    display: inline-flex;
    align-items: center;
    gap: 3px;

    padding: 1px 5px;

    font-size: 0.65rem;

    color: #495057;
    background: #f1f3f5;

    border-radius: 4px;

    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

#gridView,
#listView {
    animation: fadeIn 0.2s ease-in;
}

@keyframes fadeIn {

    from {
        opacity: 0;
    }

    to {
        opacity: 1;
    }

}

@media (max-width: 992px) {

    .drive-col-dept,
    .drive-col-owner,
    .drive-col-size {
        display: none;
    }

}

@media (max-width: 768px) {

    .drive-list-header {
        display: none;
    }

    .drive-list-row {
        flex-direction: column;
        align-items: flex-start;
        gap: 8px;
    }

}

@media print {

    body {
        margin: 0;
        padding: 20px;
    }

    #qrPrintTemplate {
        display: block !important;
    }

    @page {
        margin: 1cm;
    }

}

.empty-state {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    /* padding: 6rem 2rem; */
    text-align: center;
}

.empty-icon {
    font-size: 4rem;
    color: #9ca3af;
    margin-bottom: 1.5rem;
}

.empty-title {
    font-size: 1.3rem;
    font-weight: 600;
    color: #1f2937;
    margin-bottom: 0.5rem;
}

.empty-text {
    color: #6b7280;
    margin-bottom: 1.3rem;
}
</style>
@endsection

@section('content')

<div class="mb-4">
    <h4 class="fs-2 fw-semibold mb-1">Dashboard</h4>
    <p class="text-muted">Overview of your documents</p>
</div>

<div class="row g-3 mb-4 h-100">
    <div class="col-xl-3 col-md-4">
        <div class="dashboard-card pending">
            <div class="icon-circle">
                <i class="ri-file-list-3-line"></i>
            </div>
            <h2 class="mb-0 font-weight-bold">{{ $forApprovalCount ?? 55 }}</h2>
            <p>Total Documents</p>
        </div>
    </div>
    <div class="col-xl-3 col-md-4">
        <div class="dashboard-card declined">
            <div class="icon-circle">
                <i class="ri-time-line"></i>
            </div>
            <h2 class="mb-0 font-weight-bold">{{ $declinedCount ?? 12 }}</h2>
            <p>Pending Approval</p>
        </div>
    </div>
    <div class="col-xl-3 col-md-4">
        <div class="dashboard-card approved">
            <div class="icon-circle">
                <i class="ri-checkbox-circle-line"></i>
            </div>
            <h2 class="mb-0 font-weight-bold">{{ $approvedCount ?? 20 }}</h2>
            <p>Approved</p>
        </div>
    </div>

    <div class="col-xl-3 col-md-4">
        <div class="dashboard-card returned">
            <div class="icon-circle">
                <i class="ri-close-circle-line"></i>
            </div>
            <h2 class="mb-0 font-weight-bold">{{ $returnedCount ?? 2 }}</h2>
            <p>Declined</p>
        </div>
    </div>
</div>

<div class="row g-4 mb-4">
    <div class="col-12 col-lg-8">
        <div class="card shadow-sm h-100 qms-chart-card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div>
                        <h6 class="fw-semibold mb-0 text-dark">Monthly Document Submissions</h6>
                        <small class="text-muted">Jan – Dec {{ date('Y') }}</small>
                    </div>
                    <div class="d-flex gap-3 flex-wrap justify-content-end">
                        <span class="qms-legend-dot" style="--dot:#8B0000;">Submitted</span>
                        <span class="qms-legend-dot" style="--dot:#c0392b;">Approved</span>
                        <span class="qms-legend-dot" style="--dot:#e0b0b0;">Declined</span>
                    </div>
                </div>
                <div class="qms-chart-wrap" style="height:240px;">
                    <canvas id="chartMonthly"></canvas>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12 col-lg-4">
        <div class="card shadow-sm h-100 qms-chart-card">
            <div class="card-body d-flex flex-column">
                <div class="mb-3">
                    <h6 class="fw-semibold mb-0 text-dark">Document Status</h6>
                    <small class="text-muted">Current breakdown</small>
                </div>
                <div class="qms-chart-wrap flex-grow-1 d-flex align-items-center justify-content-center" style="height:200px;">
                    <canvas id="chartStatus"></canvas>
                </div>
                <div class="qms-donut-legend mt-3">
                    <div class="qms-donut-item"><span style="background:#8B0000"></span>Approved <strong>81%</strong></div>
                    <div class="qms-donut-item"><span style="background:#e67e22"></span>Pending <strong>6%</strong></div>
                    <div class="qms-donut-item"><span style="background:#c0392b"></span>Declined <strong>12%</strong></div>
                    <div class="qms-donut-item"><span style="background:#bdc3c7"></span>Draft <strong>1%</strong></div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4 mb-4">
    <div class="col-12 col-lg-6">
        <div class="card shadow-sm h-100 qms-chart-card">
            <div class="card-body">
                <div class="mb-3">
                    <h6 class="fw-semibold mb-0 text-dark">Documents by Department</h6>
                    <small class="text-muted">Top 8 departments</small>
                </div>
                <div class="qms-chart-wrap" style="height:240px;">
                    <canvas id="chartDept"></canvas>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12 col-lg-3">
        <div class="card shadow-sm h-100 qms-chart-card">
            <div class="card-body d-flex flex-column">
                <div class="mb-3">
                    <h6 class="fw-semibold mb-0 text-dark">Document Types</h6>
                    <small class="text-muted">By category</small>
                </div>
                <div class="qms-chart-wrap flex-grow-1" style="height:220px;">
                    <canvas id="chartTypes"></canvas>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12 col-lg-3">
        <div class="card shadow-sm h-100 qms-chart-card">
            <div class="card-body d-flex flex-column">
                <div class="mb-3">
                    <h6 class="fw-semibold mb-0 text-dark">This Week's Activity</h6>
                    <small class="text-muted">Uploads vs Approvals</small>
                </div>
                <div class="qms-chart-wrap flex-grow-1" style="height:140px;">
                    <canvas id="chartWeekly"></canvas>
                </div>
                <div class="qms-weekly-stats mt-3 d-flex justify-content-around text-center">
                    <div>
                        <div class="fw-bold text-dark" style="font-size:1.4rem;">24</div>
                        <small class="text-muted">Uploaded</small>
                    </div>
                    <div style="border-left:1px solid #eee;"></div>
                    <div>
                        <div class="fw-bold text-dark" style="font-size:1.4rem;">17</div>
                        <small class="text-muted">Approved</small>
                    </div>
                    <div style="border-left:1px solid #eee;"></div>
                    <div>
                        <div class="fw-bold text-dark" style="font-size:1.4rem;">3</div>
                        <small class="text-muted">Declined</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4 align-items-stretch">
    <div class="col-12 col-lg-12 d-flex flex-column">
        <div class="card shadow-sm w-100 qms-chart-card">
            <div class="card-body d-flex flex-column">
                <div class="mb-4">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="fw-semibold text-dark mb-0">For Approval</h5>
                        <div class="d-flex align-items-center gap-2">
                            <div class="btn-group" role="group">
                                <button type="button" class="btn btn-outline-secondary btn-sm view-toggle active" data-view="grid">
                                    <i class="ri-grid-line"></i>
                                </button>
                                <button type="button" class="btn btn-outline-secondary btn-sm view-toggle" data-view="list">
                                    <i class="ri-list-check"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <form action="{{ route('home') }}" method="GET" id="pendingSearchForm">
                        @foreach(request()->except(['pending_search','pending_date','pending_dept','pending_office','pending_page']) as $key => $val)
                            <input type="hidden" name="{{ $key }}" value="{{ $val }}">
                        @endforeach
                        <div class="d-flex gap-2 align-items-center">
                            <div class="position-relative flex-grow-1">
                                <i class="ri-search-line position-absolute top-50 translate-middle-y ms-3 text-muted" style="z-index:2;"></i>
                                <input type="text"
                                    name="pending_search"
                                    id="pendingSearchInput"
                                    value="{{ request('pending_search') }}"
                                    placeholder="Search title, department, or date (e.g. Jan 2025)..."
                                    class="form-control form-control-sm ps-5 pe-5"
                                    autocomplete="off">
                                @if(request('pending_search'))
                                <a href="{{ route('home') }}"
                                   class="position-absolute top-50 translate-middle-y end-0 me-2 text-muted text-decoration-none"
                                   style="z-index:2;">
                                    <i class="ri-close-circle-fill"></i>
                                </a>
                                @endif
                            </div>
                            <input type="hidden" name="pending_date" value="">
                            <input type="hidden" name="pending_dept" value="">
                            <input type="hidden" name="pending_office" value="">
                            <button type="submit" class="btn btn-primary btn-sm flex-shrink-0">
                                <i class="ri-search-line"></i>
                            </button>
                        </div>
                    </form>
                </div>

                <div id="gridView" class="row row-cols-1 row-cols-sm-4 g-2">
                    @foreach ($pending_cards as $approvers)
                    @php
                        $change_request = $approvers->change_request;
                    @endphp
                    <div class="col">
                        <div class="card border file-card position-relative" data-card-id="{{ $change_request->id }}">
                            <div class="position-absolute top-0 end-0 m-2 more-btn">
                                <button class="btn btn-sm btn-light p-1 file-more-btn" style="width: 28px; height: 28px; line-height: 1; border-radius: 6px;">
                                    <i class="ri-more-2-fill"></i>
                                </button>
                            </div>

                            <div class="file-dropdown-menu" data-card-id="{{ $change_request->id }}">
                                <button class="file-dropdown-item" data-action="display">
                                    <i class="ri-file-text-line"></i>
                                    <input type="hidden" class="file-path" value="{{ $change_request->file }}" />
                                    <span>View</span>
                                </button>
                                <div class="file-dropdown-divider"></div>
                                @php
                                    $Approver = $change_request->approvers->firstWhere('user_id', auth()->user()->id);
                                    $Status   = $Approver ? $Approver->status : 'Waiting';
                                @endphp
                                <button class="file-dropdown-item" data-action="approve" 
                                    data-id="{{ $change_request->id }}"
                                    data-my-status="{{ $Status }}">
                                    <i class="ri-checkbox-circle-line"></i>
                                    <span>Sign & Approve</span>
                                </button>
                            </div>

                            <a href='#' class="text-decoration-none" onclick="return false;">
                                <iframe src="https://docs.google.com/gview?url={{ urlencode(asset($change_request->file)) }}&embedded=true"
                                        loading="lazy"
                                        class="card-img-top document-preview-iframe"
                                        scrolling="no"
                                        frameborder="0"></iframe>
                                <div class="card-body p-2 text-start">
                                    @php
                                        $myApprover = $change_request->approvers->firstWhere('user_id', auth()->user()->id);
                                        $myStatus   = $myApprover ? $myApprover->status : null;
                                    @endphp
                                    <div class="docu d-flex align-items-center gap-2">
                                        <i class="ri-file-pdf-line text-danger" style="font-size: 1rem;"></i>
                                        @php
                                            $file = $change_request->file;
                                            $filename = explode('/',$file);
                                        @endphp
                                        <div class="fw-semibold text-dark text-truncate" style="font-size: 0.75rem;">{{ $filename[2] }}</div>
                                    </div>
                                    @if($myStatus === 'Pending')
                                        <div class="mt-1">
                                            <span class="badge bg-warning text-dark" style="font-size:0.65rem;">
                                                <i class="ri-quill-pen-line me-1"></i>Your Turn to Sign
                                            </span>
                                        </div>
                                    @elseif($myStatus === 'Waiting')
                                        <div class="mt-1">
                                            <span class="badge bg-secondary" style="font-size:0.65rem;">
                                                <i class="ri-hourglass-line me-1"></i>Waiting for the first Approver to sign
                                            </span>
                                        </div>
                                    @endif
                                    <div class="d-flex flex-wrap gap-1 mt-1">
                                        @if($change_request->department)
                                            <span class="meta-tag"><i class="ri-building-line"></i> {{ $change_request->department->code }}</span>
                                        @endif
                                        @if($change_request->department && $change_request->department->office)
                                            <span class="meta-tag"><i class="ri-home-office-line"></i> {{ $change_request->department->office->name ?? $change_request->department->office->code }}</span>
                                        @endif
                                        <span class="meta-tag"><i class="ri-calendar-line"></i> {{ date('M d, Y', strtotime($change_request->created_at)) }}</span>
                                    </div>
                                    @php
                                        $dateCreated = new DateTime($change_request->updated_at);
                                        $now = new DateTime();
                                        $count = $now->diff($dateCreated);
                                        $dayName = $count->d > 1 ? "days" : "day"
                                    @endphp
                                    <small class="text-dark text-truncated">
                                        <i class="ri-time-line" style="font-size: 1rem;"></i>
                                        <span>{{ $count->d }} {{$dayName}}</span>
                                    </small>
                                </div>
                            </a>
                        </div>
                    </div>
                    @endforeach
                </div>

                <div id="listView" class="d-none">
                    <div class="overflow-auto">
                        <div class="drive-list-container" style="min-width: 700px;">
                            <div class="drive-list-header">
                                <div class="drive-list-row">
                                    <div class="drive-col-name"><span>Name</span></div>
                                    <div class="drive-col-owner"><span>Owner</span></div>
                                    <div class="drive-col-dept"><span>Department</span></div>
                                    <div class="drive-col-modified"><span>Last modified</span></div>
                                    <div class="drive-col-size"><span>File size</span></div>
                                    <div class="drive-col-actions"></div>
                                </div>
                            </div>

                            <div class="drive-list-body">
                                @foreach ($pending_cards as $approvers)
                                @php
                                    $change_request = $approvers->change_request;
                                @endphp
                                @php
                                    $file = $change_request->file;
                                    $filename = explode('/',$file);
                                    $filesize = file_exists(public_path($file)) ? filesize(public_path($file)) : 0;
                                    $filesizeFormatted = $filesize > 0 ? number_format($filesize / 1024 / 1024, 2) . ' MB' : '--';
                                @endphp
                                <div class="drive-list-item file-card" data-card-id="{{ $change_request->id }}">
                                    <div class="drive-list-row">
                                        <div class="drive-col-name">
                                            <div class="d-flex align-items-center gap-3">
                                                <div class="file-icon-wrapper">
                                                    <i class="ri-file-pdf-line text-danger"></i>
                                                </div>
                                                <div class="file-info">
                                                    <div class="file-name">{{ $filename[count($filename)-1] }}</div>
                                                    <div class="file-subtitle text-muted">{{ $change_request->title }}</div>
                                                    @php
                                                        $myApproverList = $change_request->approvers->firstWhere('user_id', auth()->user()->id);
                                                        $myStatusList   = $myApproverList ? $myApproverList->status : null;
                                                    @endphp
                                                    @if($myStatusList === 'Pending')
                                                        <span class="badge bg-warning text-dark mt-1" style="font-size:0.62rem;">
                                                            <i class="ri-quill-pen-line me-1"></i>Your Turn to Sign
                                                        </span>
                                                    @elseif($myStatusList === 'Waiting')
                                                        <span class="badge bg-secondary mt-1" style="font-size:0.62rem;">
                                                            <i class="ri-hourglass-line me-1"></i>Waiting for the first Approver to sign
                                                        </span>
                                                    @endif
                                                    <div class="d-flex flex-wrap gap-1 mt-1">
                                                        @if($change_request->department && $change_request->department->office)
                                                            <div class="d-flex flex-wrap gap-1 mt-1">
                                                                <span class="meta-tag"><i class="ri-home-office-line"></i> {{ $change_request->department->office->name ?? $change_request->department->office->code }}</span>
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="drive-col-owner">
                                            <span>{{ $change_request->user->name }}</span>
                                        </div>
                                        <div class="drive-col-dept">
                                            @if($change_request->department)
                                                <span class="meta-tag"><i class="ri-building-line"></i> {{ $change_request->department->name }}</span>
                                            @else
                                                <span class="text-muted" style="font-size:0.8rem;">—</span>
                                            @endif
                                        </div>
                                        <div class="drive-col-modified">
                                            <span>{{ date('M d, Y', strtotime($change_request->created_at)) }}</span>
                                        </div>
                                        <div class="drive-col-size">
                                            <span>{{ $filesizeFormatted }}</span>
                                        </div>
                                        <div class="drive-col-actions">
                                            <button class="btn btn-sm btn-light file-more-btn drive-more-btn">
                                                <i class="ri-more-2-fill"></i>
                                            </button>
                                        </div>
                                    </div>

                                    <div class="file-dropdown-menu" data-card-id="{{ $change_request->id }}">
                                        <button class="file-dropdown-item" data-action="display">
                                            <i class="ri-eye-line"></i>
                                            <input type="hidden" class="file-path" value="{{ $change_request->file }}" />
                                            <span>View</span>
                                        </button>
                                        <div class="file-dropdown-divider"></div>
                                        <button class="file-dropdown-item" data-action="approve" data-id="{{ $change_request->id }}">
                                            <i class="ri-checkbox-circle-line"></i>
                                            <span>Approve</span>
                                        </button>
                                        <div class="file-dropdown-divider"></div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

                @if($pending_cards->isEmpty())
                    <div class="empty-state" id="emptyState">
                        <div class="empty-icon">
                            <i class="ri-file-line"></i>
                        </div>
                        <h3 class="empty-title">No for approval in here</h3>
                        <p class="empty-text">No items are currently waiting for approval.</p>
                    </div>
                @endif

                @if($pending_cards->hasPages())
                <div class="d-flex justify-content-between align-items-center pt-3 border-top">
                    <div class="text-muted" style="font-size: 0.875rem;">
                        Showing <strong>{{ $pending_cards->firstItem() }}</strong> to <strong>{{ $pending_cards->lastItem() }}</strong> of <strong>{{ $pending_cards->total() }}</strong> pending documents
                    </div>
                    <nav aria-label="Pending documents pagination">
                        <ul class="pagination pagination-sm mb-0">
                            @if ($pending_cards->onFirstPage())
                                <li class="page-item disabled"><span class="page-link">Previous</span></li>
                            @else
                                <li class="page-item">
                                    <a class="page-link" href="{{ $pending_cards->appends(request()->except('pending_page'))->previousPageUrl() }}" rel="prev">Previous</a>
                                </li>
                            @endif

                            @foreach ($pending_cards->getUrlRange(1, $pending_cards->lastPage()) as $page => $url)
                                @if ($page == $pending_cards->currentPage())
                                    <li class="page-item active" aria-current="page"><span class="page-link">{{ $page }}</span></li>
                                @else
                                    <li class="page-item">
                                        <a class="page-link" href="{{ $pending_cards->appends(request()->except('pending_page'))->url($page) }}">{{ $page }}</a>
                                    </li>
                                @endif
                            @endforeach

                            @if ($pending_cards->hasMorePages())
                                <li class="page-item">
                                    <a class="page-link" href="{{ $pending_cards->appends(request()->except('pending_page'))->nextPageUrl() }}" rel="next">Next</a>
                                </li>
                            @else
                                <li class="page-item disabled"><span class="page-link">Next</span></li>
                            @endif
                        </ul>
                    </nav>
                </div>
                @endif

            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12 col-lg-6">
        <div class="card shadow-sm w-100 qms-chart-card" style="max-height: 450px; overflow: hidden;">
            <div class="card-body" style="display: flex; flex-direction: column; height: 450px;">
                <h5 class="fw-semibold text-dark mb-3">Private Documents</h5>
                <form action="{{ route('home') }}" method="GET" class="mb-3">
                    @if(request('pending_search'))
                        <input type="hidden" name="pending_search" value="{{ request('pending_search') }}">
                    @endif
                    <div class="d-flex gap-2 align-items-center mt-1">
                        <div class="position-relative flex-grow-1">
                            <i class="ri-search-line position-absolute top-50 translate-middle-y ms-3 text-muted" style="z-index:2;"></i>
                            <input type="text"
                                name="private_search"
                                value="{{ request('private_search') }}"
                                placeholder="Search title, dept, office, date..."
                                class="form-control form-control-sm ps-5 pe-5"
                                autocomplete="off">
                            @if(request('private_search'))
                            <a href="{{ route('home') }}"
                                class="position-absolute top-50 translate-middle-y end-0 me-2 text-muted text-decoration-none"
                                style="z-index:2;">
                                <i class="ri-close-circle-fill"></i>
                            </a>
                            @endif
                        </div>
                        <input type="hidden" name="doc_office_date" value="">
                        <input type="hidden" name="doc_office_dept" value="">
                        <input type="hidden" name="doc_office_office" value="">
                        <button type="submit" class="btn btn-primary btn-sm flex-shrink-0">
                            <i class="ri-search-line"></i>
                        </button>
                    </div>
                </form>

                <div style="overflow-y: scroll; flex-grow: 1; min-height: 0;">
                    <ul class="list-group">
                        {{-- @forelse ($private_documents as $private_document)
                            @php
                                $pvFile = $private_document->file;
                                $pvFilename = $pvFile ? explode('/', $pvFile) : [];
                                $pvName = count($pvFilename) ? end($pvFilename) : '—';
                            @endphp
                            <li class="list-group-item px-2 py-2">
                                <div class="d-flex align-items-start gap-2">
                                    <div class="flex-shrink-0 pt-1">
                                        @if($pvFile)
                                        <a href="{{ url($pvFile) }}" target="_blank" onclick="recordCRView({{ $private_document->id }})">
                                            <div class="avatar-title bg-danger-subtle text-danger rounded" style="width:28px;height:28px;display:flex;align-items:center;justify-content:center;">
                                                <i class="ri-file-pdf-line"></i>
                                            </div>
                                        </a>
                                        @endif
                                    </div>
                                    <div class="flex-grow-1 overflow-hidden">
                                        <h6 class="fs-14 mb-0 text-truncate fw-semibold">{{ $pvName }}</h6>
                                        <small class="text-muted text-truncate d-block" title="{{ $private_document->title }}">{{ $private_document->title }}</small>
                                        <div class="d-flex flex-wrap gap-1 mt-1">
                                            @if($private_document->department)
                                                <span class="meta-tag"><i class="ri-building-line"></i> {{ $private_document->department->code }}</span>
                                            @endif
                                            @if($private_document->department && $private_document->department->office)
                                                <span class="meta-tag"><i class="ri-home-office-line"></i> {{ $private_document->department->office->name ?? $private_document->department->office->code }}</span>
                                            @endif
                                            @if($private_document->user)
                                                <span class="meta-tag"><i class="ri-user-line"></i> {{ $private_document->user->name }}</span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="flex-shrink-0 text-end d-flex flex-column align-items-end gap-1" style="min-width: 70px;">
                                        <small class="text-muted" style="font-size: 0.65rem; white-space: nowrap;">
                                            <i class="ri-calendar-line"></i> {{ date('M d, Y', strtotime($private_document->created_at)) }}
                                        </small>
                                        <span class="badge" style="font-size: 0.6rem; background:#fdecea; color:#c0392b;">
                                            {{ $private_document->status ?? 'Pending' }}
                                        </span>
                                        <a href="{{ route('change-request.visitors', $private_document->id) }}" target="_blank" class="text-decoration-none">
                                            <span class="badge bg-danger-subtle text-danger" style="font-size: 0.6rem;">
                                                <i class="ri-eye-line"></i> {{ $private_document->visitors ? $private_document->visitors->count() : 0 }}
                                            </span>
                                        </a>
                                    </div>
                                </div>
                                <div class="flex-grow-1 overflow-hidden">
                                    @if(count($private_document->document_request_access->where("status", 1)->where("requestor_id", auth()->id())) > 0)
                                    <h6 class="fs-14 mb-0 text-truncate fw-semibold">
                                        <i class="ri-file-text-line"></i>
                                        {{ $private_document->title }}
                                    </h6>
                                    @else
                                    <h6 class="fs-14 mb-0 text-truncate fw-semibold text-muted" style="font-style:italic;">
                                        <i class="ri-git-repository-private-line"></i>
                                        {{ $private_document->title }}
                                    </h6>
                                    @endif
                                    <small class="text-muted text-truncate d-block" title="{{ $private_document->control_code }}">{{ $private_document->control_code }}</small>
                                    <small class="text-muted text-truncate d-block">Owner: {{ $private_document->owner->name }}</small>
                                    <div class="d-flex flex-wrap gap-1 mt-1">
                                        @if($private_document->department)
                                            <span class="meta-tag"><i class="ri-building-line"></i> {{ $private_document->department->code }}</span>
                                        @endif
                                        @if($private_document->department && $private_document->department->office)
                                            <span class="meta-tag"><i class="ri-home-office-line"></i> {{ $private_document->department->office->name ?? $private_document->department->office->code }}</span>
                                        @endif
                                    </div>
                                </div>
                            </li>
                        @empty
                            <li class="list-group-item text-center text-muted">No documents found.</li>
                        @endforelse --}}

                        @forelse ($private_documents as $private_document)
                            <li class="list-group-item px-2 py-2 @if($private_document->has_pending_request) bg-warning @endif">
                                <div class="d-flex align-items-start gap-2">

                                    <div class="flex-shrink-0 pt-1">
                                        @if($private_document->has_valid_access)
                                            @foreach($private_document->attachments->where('type', 'pdf_copy') as $attachment)
                                                <a href="{{ url($attachment->attachment) }}" target="_blank">
                                                    <div class="avatar-title bg-danger-subtle text-danger rounded"
                                                        style="width:28px;height:28px;display:flex;align-items:center;justify-content:center;">
                                                        <i class="ri-file-text-line"></i>
                                                    </div>
                                                </a>
                                                <form action="{{ url('/documents/user-view') }}" method="post"
                                                    id="userView{{ $attachment->id }}"
                                                    onsubmit="userView({{ $attachment->id }})">
                                                    @csrf
                                                    <input type="hidden" name="document_id" value="{{ $attachment->document_id }}">
                                                </form>
                                            @endforeach
                                        @else
                                            <a href="javascript:void(0)">
                                                <div class="avatar-title bg-danger-subtle text-danger rounded"
                                                    style="width:28px;height:28px;display:flex;align-items:center;justify-content:center;">
                                                    <i class="ri-git-repository-private-line"></i>
                                                </div>
                                            </a>
                                        @endif
                                    </div>

                                    <div class="flex-grow-1 overflow-hidden">
                                        @if($private_document->has_valid_access)
                                            <h6 class="fs-14 mb-0 text-truncate fw-semibold">
                                                <i class="ri-file-text-line"></i>
                                                {{ $private_document->title }}
                                            </h6>
                                            @if($private_document->access_expiry)
                                                <small class="text-success" style="font-size:0.65rem;">
                                                    <i class="ri-calendar-check-line"></i>
                                                    Access until: {{ \Carbon\Carbon::parse($private_document->access_expiry)->format('M d, Y') }}
                                                </small>
                                            @else
                                                <small class="text-success" style="font-size:0.65rem;">
                                                    <i class="ri-infinity-line"></i> Indefinite access
                                                </small>
                                            @endif
                                        @else
                                            <h6 class="fs-14 mb-0 text-truncate fw-semibold text-muted" style="font-style:italic;">
                                                <i class="ri-git-repository-private-line"></i>
                                                {{ $private_document->title }}
                                            </h6>
                                        @endif

                                        <small class="text-muted text-truncate d-block"
                                            title="{{ $private_document->control_code }}">{{ $private_document->control_code }}</small>
                                        <small class="text-muted text-truncate d-block">Owner: {{ $private_document->owner->name }}</small>
                                    </div>

                                    <div class="flex-shrink-0 text-end d-flex flex-column align-items-end gap-1" style="min-width: 70px;">
                                        <div class="dropdown">
                                            <a href="javascript:void(0)" class="text-decoration-none"
                                                data-bs-toggle="dropdown" aria-expanded="false">
                                                <span class="badge bg-secondary-subtle text-secondary" style="font-size: 0.6rem;">
                                                    <i class="ri-more-2-fill"></i>
                                                </span>
                                            </a>
                                            <ul class="dropdown-menu dropdown-menu-end">
                                                @if(!$private_document->has_valid_access)
                                                    <li>
                                                        <a href="javascript:void(0)" class="dropdown-item"
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#requestAccess{{ $private_document->id }}">
                                                            <i class="ri-lock-unlock-line me-2"></i> Request Access
                                                        </a>
                                                    </li>
                                                @else
                                                    <li>
                                                        <span class="dropdown-item text-success disabled">
                                                            <i class="ri-checkbox-circle-line me-2"></i> Access Granted
                                                        </span>
                                                    </li>
                                                @endif
                                                <li>
                                                    <a href="{{ url('/documents/visitors/' . $private_document->id) }}"
                                                        target="_blank" class="dropdown-item">
                                                        <i class="ri-eye-line me-2"></i> View Visitors
                                                        <span class="badge bg-primary-subtle text-primary ms-1">
                                                            {{ $private_document->visitor->count() }}
                                                        </span>
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>

                                </div>
                            </li>

                        @empty
                            <li class="list-group-item text-center text-muted">No documents found.</li>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12 col-lg-6">
        <div class="card shadow-sm w-100 qms-chart-card" style="max-height: 450px; overflow: hidden;">
            <div class="card-body" style="display: flex; flex-direction: column; height: 450px;">
                <h5 class="fw-semibold text-dark mb-3">Public Documents</h5>
                <form action="{{ route('home') }}" method="GET" class="mb-3">
                    @if(request('pending_search'))
                        <input type="hidden" name="pending_search" value="{{ request('pending_search') }}">
                    @endif
                    <div class="d-flex gap-2 align-items-center mt-1">
                        <div class="position-relative flex-grow-1">
                            <i class="ri-search-line position-absolute top-50 translate-middle-y ms-3 text-muted" style="z-index:2;"></i>
                            <input type="text"
                                name="public_search"
                                value="{{ request('public_search') }}"
                                placeholder="Search title, dept, office, date..."
                                class="form-control form-control-sm ps-5 pe-5"
                                autocomplete="off">
                            @if(request('public_search'))
                            <a href="{{ route('home') }}"
                                class="position-absolute top-50 translate-middle-y end-0 me-2 text-muted text-decoration-none"
                                style="z-index:2;">
                                <i class="ri-close-circle-fill"></i>
                            </a>
                            @endif
                        </div>
                        <input type="hidden" name="doc_office_date" value="">
                        <input type="hidden" name="doc_office_dept" value="">
                        <input type="hidden" name="doc_office_office" value="">
                        <button type="submit" class="btn btn-primary btn-sm flex-shrink-0">
                            <i class="ri-search-line"></i>
                        </button>
                    </div>
                </form>

                <div style="overflow-y: scroll; flex-grow: 1; min-height: 0;">
                    <ul class="list-group">
                        @forelse ($documents as $document)
                        <li class="list-group-item px-2 py-2">
                            <div class="d-flex align-items-start gap-2">
                                <div class="flex-shrink-0 pt-1">
                                    @foreach($document->attachments->where('type','pdf_copy') as $attachment)
                                    <a href="{{ url($attachment->attachment) }}" target="_blank" onclick="userView({{ $attachment->document_id }})">
                                        <div class="avatar-title bg-danger-subtle text-danger rounded" style="width:28px;height:28px;display:flex;align-items:center;justify-content:center;">
                                            <i class="ri-file-text-line"></i>
                                        </div>
                                    </a>
                                    @endforeach
                                </div>
                                <div class="flex-grow-1 overflow-hidden">
                                    <h6 class="fs-14 mb-0 text-truncate fw-semibold">{{ $document->title }}</h6>
                                    <small class="text-muted text-truncate d-block" title="{{ $document->control_code }}">{{ $document->control_code }}</small>
                                    <div class="d-flex flex-wrap gap-1 mt-1">
                                        @if($document->department)
                                            <span class="meta-tag"><i class="ri-building-line"></i> {{ $document->department->code }}</span>
                                        @endif
                                        @if($document->department && $document->department->office)
                                            <span class="meta-tag"><i class="ri-home-office-line"></i> {{ $document->department->office->name ?? $document->department->office->code }}</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="flex-shrink-0 text-end d-flex flex-column align-items-end gap-1" style="min-width: 70px;">
                                    <small class="text-muted" style="font-size: 0.65rem; white-space: nowrap;">
                                        <i class="ri-calendar-line"></i> {{ date('M d, Y', strtotime($document->created_at)) }}
                                    </small>
                                    <a href="{{ url("/documents/visitors/".$document->id) }}" target="_blank" class="text-decoration-none">
                                        <span class="badge bg-primary-subtle text-primary" style="font-size: 0.6rem;">
                                            <i class="ri-eye-line"></i> {{ $document->visitor->count() }}
                                        </span>
                                    </a>
                                </div>
                            </div>
                        </li>
                        @empty
                            <li class="list-group-item text-center text-muted">No documents found.</li>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="dashboardSignModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="ri-lock-line me-2"></i>Confirm Password</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p class="text-muted small mb-3">Enter your password to proceed to the signing page.</p>
                <input type="password" id="dashboardSignPassword" class="form-control" placeholder="Password" />
                <div id="dashboardSignError" class="text-danger small mt-2 d-none">Incorrect password. Please try again.</div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="dashboardSignConfirm">
                    <i class="ri-quill-pen-line me-1"></i>Confirm & Sign
                </button>
            </div>
        </div>
    </div>
</div>

@foreach ($private_documents as $private_document)
@include("dashboard.request_access")
@endforeach
@endsection

@section('js')
<script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
<script src="{{ asset('barcode/JsBarcode.all.min.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    function userView(documentId) {
        fetch("{{ url('/documents/user-view') }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ document_id: documentId })
        });
    }

    document.addEventListener('DOMContentLoaded', function () {

        JsBarcode(".barcode").init();

        const viewToggles = document.querySelectorAll('.view-toggle');
        const gridView = document.getElementById('gridView');
        const listView = document.getElementById('listView');

        const savedView = localStorage.getItem('pendingDocsView') || 'grid';
        setActiveView(savedView);

        viewToggles.forEach(button => {
            button.addEventListener('click', function () {
                const view = this.getAttribute('data-view');
                setActiveView(view);
                localStorage.setItem('pendingDocsView', view);
            });
        });

        function setActiveView(view) {
            viewToggles.forEach(btn => {
                btn.classList.toggle('active', btn.getAttribute('data-view') === view);
            });
            if (view === 'grid') {
                listView.classList.add('d-none');
                setTimeout(() => gridView.classList.remove('d-none'), 50);
            } else {
                gridView.classList.add('d-none');
                setTimeout(() => listView.classList.remove('d-none'), 50);
            }
        }

        document.querySelectorAll('.file-dropdown-menu').forEach(menu => {
            document.body.appendChild(menu);
        });

        function closeAllDropdowns() {
            document.querySelectorAll('.file-dropdown-menu').forEach(menu => menu.classList.remove('show'));
            document.querySelectorAll('.file-card').forEach(c => c.classList.remove('dropdown-open'));
        }

        document.querySelectorAll('.file-more-btn').forEach(button => {
            button.addEventListener('click', function (e) {
                e.preventDefault();
                e.stopPropagation();

                const fileCard = this.closest('.file-card');
                const cardId   = fileCard.dataset.cardId;
                const dropdown = document.querySelector(`.file-dropdown-menu[data-card-id="${cardId}"]`);

                document.querySelectorAll('.file-dropdown-menu').forEach(menu => {
                    if (menu !== dropdown) menu.classList.remove('show');
                });
                document.querySelectorAll('.file-card').forEach(c => c.classList.remove('dropdown-open'));

                const rect = this.getBoundingClientRect();
                const dropdownWidth = 200;
                let left = rect.right - dropdownWidth;
                let top  = rect.bottom + 4;

                if (left < 8) left = 8;
                if (left + dropdownWidth > window.innerWidth - 8) left = window.innerWidth - dropdownWidth - 8;

                dropdown.style.top = top + 'px';
                dropdown.style.left = left + 'px';
                dropdown.style.position = 'fixed';

                dropdown.classList.toggle('show');
                if (dropdown.classList.contains('show')) {
                    fileCard.classList.add('dropdown-open');
                    dropdown.dataset.fileCard = cardId;
                }
            });
        });

        document.addEventListener('click', function (e) {
            const item = e.target.closest('.file-dropdown-item');
            if (!item) return;

            e.preventDefault();
            e.stopPropagation();

            const action   = item.getAttribute('data-action');
            const filePath = item.querySelector('.file-path')?.value;

            switch (action) {
                case 'display':
                    if (filePath) window.open("{{ url('') }}/" + filePath, '_blank');
                    break;
                case 'download':
                    if (filePath) {
                        const link = document.createElement('a');
                        link.href = "{{ url('') }}/" + filePath;
                        link.download = filePath.split('/').pop();
                        document.body.appendChild(link);
                        link.click();
                        document.body.removeChild(link);
                    }
                    break;
                case 'approve':
                const changeRequestId = item.getAttribute('data-id');
                const Status = item.getAttribute('data-my-status');

                if (!changeRequestId) break;

                if (Status === 'Waiting') {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Not Your Turn Yet',
                        html: 'The previous approver must sign the document first before you can proceed.',
                        confirmButtonColor: '#8B0000',
                        confirmButtonText: 'Got it',
                    });
                    break;
                }

                document.getElementById('dashboardSignPassword').value = '';
                document.getElementById('dashboardSignError').classList.add('d-none');

                var signModal = new bootstrap.Modal(document.getElementById('dashboardSignModal'));
                signModal.show();

                document.getElementById('dashboardSignConfirm').onclick = function () {
                    const password = document.getElementById('dashboardSignPassword').value;
                    if (!password) {
                        document.getElementById('dashboardSignError').textContent = 'Please enter your password.';
                        document.getElementById('dashboardSignError').classList.remove('d-none');
                        return;
                    }

                    fetch("{{ url('change-request/confirm-password') }}", {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({ password: password, change_request_id: changeRequestId })
                    })
                    .then(r => r.json())
                    .then(data => {
                        if (data.success) {
                            signModal.hide();
                            window.location.href = "{{ route('documents.signature', '') }}/" + changeRequestId;
                        } else {
                            document.getElementById('dashboardSignError').textContent = 'Incorrect password. Please try again.';
                            document.getElementById('dashboardSignError').classList.remove('d-none');
                        }
                    });
                };
                break;
            }
            closeAllDropdowns();
        });

        document.addEventListener('click', function (e) {
            if (!e.target.closest('.file-more-btn') && !e.target.closest('.file-dropdown-menu')) {
                closeAllDropdowns();
            }
        });

        document.querySelectorAll('.file-dropdown-menu').forEach(menu => {
            menu.addEventListener('click', e => {
                if (!e.target.closest('.file-dropdown-item')) e.stopPropagation();
            });
        });

        window.addEventListener('scroll', closeAllDropdowns, { passive: true });
        window.addEventListener('resize', closeAllDropdowns, { passive: true });

        const listItems = document.querySelectorAll('#listView .drive-list-item');
        listItems.forEach(item => {
            item.addEventListener('click', function (e) {
                if (e.target.closest('.file-more-btn') || e.target.closest('.file-dropdown-menu')) return;
                listItems.forEach(i => i.classList.remove('selected'));
                this.classList.add('selected');
            });
            item.addEventListener('dblclick', function (e) {
                if (e.target.closest('.file-more-btn')) return;
                const filePath = this.querySelector('.file-path')?.value;
                if (filePath) window.open("{{ url('') }}/" + filePath, '_blank');
            });
        });

        const MAROON = '#8B0000';
        const MAROON_L = 'rgba(139,0,0,0.08)';
        const RED2 = '#c0392b';
        const ORANGE = '#e67e22';
        const GREEN = '#27ae60';
        const GRAY = '#bdc3c7';

        Chart.defaults.font.family = "'Helvetica Neue', Arial, sans-serif";
        Chart.defaults.color       = '#6c757d';

        new Chart(document.getElementById('chartMonthly'), {
            type: 'bar',
            data: {
                labels: ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'],
                datasets: [
                    {
                        label: 'Submitted',
                        data: [22,18,30,25,28,35,20,24,31,19,27,45],
                        backgroundColor: MAROON,
                        borderRadius: 4, borderSkipped: false,
                    },
                    {
                        label: 'Approved',
                        data: [18,15,26,21,24,30,17,20,27,16,22,38],
                        backgroundColor: RED2,
                        borderRadius: 4, borderSkipped: false,
                    },
                    {
                        label: 'Declined',
                        data: [2,2,3,3,2,4,2,3,3,2,3,5],
                        backgroundColor: '#e0b0b0',
                        borderRadius: 4, borderSkipped: false,
                    }
                ]
            },
            options: {
                responsive: true, maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: { mode: 'index', intersect: false }
                },
                scales: {
                    x: { grid: { display: false }, border: { display: false } },
                    y: {
                        grid: { color: '#f0f0f0' },
                        border: { display: false, dash: [4,4] },
                        ticks: { stepSize: 10 }
                    }
                }
            }
        });

        new Chart(document.getElementById('chartStatus'), {
            type: 'doughnut',
            data: {
                labels: ['Approved','Pending','Declined','Draft'],
                datasets: [{
                    data: [231, 18, 35, 3],
                    backgroundColor: [MAROON, ORANGE, RED2, GRAY],
                    borderWidth: 2, borderColor: '#fff', hoverOffset: 8,
                }]
            },
            options: {
                responsive: true, maintainAspectRatio: false,
                cutout: '72%',
                plugins: {
                    legend: { display: false },
                    tooltip: { callbacks: { label: ctx => ` ${ctx.label}: ${ctx.parsed} docs` } }
                }
            }
        });

        new Chart(document.getElementById('chartDept'), {
            type: 'bar',
            data: {
                labels: ['ADMIN','FINANCE','HR','IT','LEGAL','OPERATIONS','PROCUREMENT','QMO'],
                datasets: [{
                    label: 'Documents',
                    data: [45, 38, 52, 29, 34, 61, 41, 47],
                    backgroundColor: [
                        '#8B0000','#9a1010','#a92020','#8B0000',
                        '#9a1010','#a92020','#b83030','#8B0000'
                    ],
                    borderRadius: 4, borderSkipped: false,
                }]
            },
            options: {
                indexAxis: 'y',
                responsive: true, maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: { callbacks: { label: c => ` ${c.parsed.x} documents` } }
                },
                scales: {
                    x: {
                        grid: { color: '#f0f0f0' },
                        border: { display: false, dash: [4,4] },
                        ticks: { stepSize: 15 }
                    },
                    y: {
                        grid: { display: false },
                        border: { display: false },
                        ticks: { font: { size: 11 } }
                    }
                }
            }
        });

        new Chart(document.getElementById('chartTypes'), {
            type: 'polarArea',
            data: {
                labels: ['Memo','Policy','SOP','Manual','Form','Report'],
                datasets: [{
                    data: [42, 28, 65, 19, 83, 37],
                    backgroundColor: [
                        'rgba(139,0,0,.75)','rgba(192,57,43,.75)',
                        'rgba(230,126,34,.75)','rgba(39,174,96,.75)',
                        'rgba(52,152,219,.75)','rgba(149,165,166,.75)'
                    ],
                    borderColor: '#fff', borderWidth: 2,
                }]
            },
            options: {
                responsive: true, maintainAspectRatio: false,
                plugins: {
                    legend: { position: 'bottom', labels: { font: { size: 10 }, padding: 8 } }
                },
                scales: { r: { ticks: { display: false }, grid: { color: '#eee' } } }
            }
        });

        new Chart(document.getElementById('chartWeekly'), {
            type: 'line',
            data: {
                labels: ['Mon','Tue','Wed','Thu','Fri','Sat','Sun'],
                datasets: [
                    {
                        label: 'Uploaded',
                        data: [4,6,5,3,4,1,1],
                        borderColor: MAROON, backgroundColor: MAROON_L,
                        fill: true, tension: .4, pointRadius: 3,
                        pointBackgroundColor: MAROON, borderWidth: 2,
                    },
                    {
                        label: 'Approved',
                        data: [3,4,4,2,3,1,0],
                        borderColor: GREEN, backgroundColor: 'rgba(39,174,96,0.07)',
                        fill: true, tension: .4, pointRadius: 3,
                        pointBackgroundColor: GREEN, borderWidth: 2,
                    }
                ]
            },
            options: {
                responsive: true, maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: { mode: 'index', intersect: false }
                },
                scales: {
                    x: { grid: { display: false }, border: { display: false }, ticks: { font: { size: 10 } } },
                    y: { display: false }
                }
            }
        });

        const counters = document.querySelectorAll('.qms-kpi-value[data-target]');
        const observer = new IntersectionObserver(entries => {
            entries.forEach(entry => {
                if (!entry.isIntersecting) return;
                const el     = entry.target;
                const target = +el.dataset.target;
                let start    = 0;
                const step   = target / (1200 / 16);
                const tick   = () => {
                    start = Math.min(start + step, target);
                    el.textContent = Math.floor(start).toLocaleString();
                    if (start < target) requestAnimationFrame(tick);
                };
                tick();
                observer.unobserve(el);
            });
        }, { threshold: 0.2 });
        counters.forEach(c => observer.observe(c));

    });

    function recordCRView(changeRequestId) {
        fetch("{{ url('change-request/record-view') }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ change_request_id: changeRequestId })
        });
    }
</script>
@endsection