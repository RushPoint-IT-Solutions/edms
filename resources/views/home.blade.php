@extends('layouts.header')

@section('css')
{{-- <link href="{{ asset('login_css/css/plugins/c3/c3.min.css') }}" rel="stylesheet">
<link href="{{ asset('login_css/css/plugins/morris/morris-0.4.3.min.css') }}" rel="stylesheet"> --}}
<style>
.file-card {
    position: relative;
    z-index: 1;
    transition: all 0.3s ease;
    width: 100%;
}

.file-card.dropdown-open {
    z-index: 9999;
}

.file-card:hover {
    box-shadow: 0 0.5rem 1rem rgba(0,0,0,0.15);
    transform: translateY(-2px);
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
    transition: all 0.2s ease;
    background-color: white !important;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.file-more-btn:hover {
    background-color: #f8f9fa !important;
    box-shadow: 0 4px 6px rgba(0,0,0,0.15);
}

.file-more-btn:active {
    background-color: #e9ecef !important;
    box-shadow: 0 1px 2px rgba(0,0,0,0.1);
    transform: scale(0.95);
}

.file-dropdown-menu {
    position: absolute;
    top: 0;
    left: 100%;
    background: white;
    border-radius: 12px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    min-width: 200px;
    z-index: 9999;
    display: none;
    margin-left: 8px;
    overflow: hidden;
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
    padding: 12px 16px;
    display: flex;
    align-items: center;
    gap: 12px;
    cursor: pointer;
    transition: all 0.2s ease;
    border: none;
    background: none;
    width: 100%;
    text-align: left;
    font-size: 0.875rem;
    color: #212529;
    position: relative;
    font-weight: 500;
    user-select: none;
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

.file-dropdown-item .shortcut {
    margin-left: auto;
    font-size: 0.75rem;
    color: #6c757d;
}

.file-dropdown-divider {
    height: 1px;
    background-color: #dee2e6;
    margin: 4px 0;
}

.file-dropdown-item.submenu {
    justify-content: space-between;
}

.file-dropdown-item.danger {
    color: #dc3545;
}

.file-dropdown-item.danger:hover {
    background-color: #fee;
}

.file-submenu {
    position: absolute;
    left: 100%;
    top: 0;
    background: white;
    border-radius: 12px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    min-width: 200px;
    z-index: 10000;
    display: none;
    margin-left: 5px;
    overflow: hidden;
}

.file-submenu.show {
    display: block;
}

.file-dropdown-item.submenu:hover .file-submenu {
    display: block;
}

.file-preview-menu {
    position: absolute;
    top: 100%;
    left: 20%;
    background: white;
    border-radius: 12px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    min-width: 200px;
    z-index: 9999;
    display: none;
    margin-top: 5px;
    overflow: hidden;
}

.file-preview-menu.show {
    display: block;
}

.file-share-menu {
    position: absolute;
    top: 100%;
    left: 20%;
    background: white;
    border-radius: 12px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    min-width: 200px;
    z-index: 9999;
    display: none;
    margin-top: 5px;
    overflow: hidden;
}

.file-share-menu.show {
    display: block;
}

.drive-list-container {
    background: white;
    border-radius: 8px;
    overflow: hidden;
}

.drive-list-header {
    background: #f8f9fa;
    border-bottom: 1px solid #e0e0e0;
    padding: 8px 16px;
    font-size: 0.75rem;
    font-weight: 600;
    color: #5f6368;
    text-transform: uppercase;
    letter-spacing: 0.3px;
}

.drive-list-body {
    background: white;
}

.drive-list-row {
    display: flex;
    align-items: center;
    width: 100%;
    gap: 16px;
}

.drive-list-header .drive-list-row {
    align-items: center;
    height: 40px;
}

.drive-list-item {
    border-bottom: 1px solid #f0f0f0;
    padding: 8px 16px;
    transition: background-color 0.2s ease;
    cursor: pointer;
    position: relative;
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
    padding-right: 24px;
}

.drive-col-owner {
    width: 180px;
    flex-shrink: 0;
}

.drive-col-modified {
    width: 150px;
    flex-shrink: 0;
}

.drive-col-size {
    width: 100px;
    flex-shrink: 0;
    text-align: right;
}

.drive-col-actions {
    width: 48px;
    flex-shrink: 0;
    display: flex;
    justify-content: center;
    align-items: center;
}

.file-icon-wrapper {
    width: 32px;
    height: 32px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}

.file-icon-wrapper i {
    font-size: 24px;
}

.file-info {
    min-width: 0;
    flex: 1;
}

.file-name {
    font-size: 0.875rem;
    font-weight: 500;
    color: #202124;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    line-height: 1.4;
}

.file-subtitle {
    font-size: 0.75rem;
    color: #5f6368;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    line-height: 1.3;
}

.drive-col-owner span,
.drive-col-modified span,
.drive-col-size span {
    font-size: 0.8125rem;
    color: #5f6368;
}

.drive-more-btn {
    opacity: 0;
    transition: opacity 0.2s ease, background-color 0.2s ease;
    border-radius: 50%;
    width: 32px;
    height: 32px;
    padding: 0;
    display: flex;
    align-items: center;
    justify-content: center;
    border: none;
    background: transparent;
}

.drive-list-item:hover .drive-more-btn {
    opacity: 1;
}

.drive-more-btn:hover {
    background-color: #e8eaed !important;
}

.drive-more-btn:active {
    background-color: #dadce0 !important;
}

.drive-more-btn i {
    font-size: 18px;
    color: #5f6368;
}

#listView .file-dropdown-menu {
    position: absolute;
    right: 48px;
    top: 50%;
    transform: translateY(-50%);
    left: auto;
    margin-right: 8px;
}

#listView .file-card.dropdown-open {
    background-color: #e8f0fe;
    z-index: 1050;
}

.view-toggle {
    transition: all 0.2s ease;
    border-radius: 4px;
}

.view-toggle.active {
    background-color: #0d6efd;
    color: white;
    border-color: #0d6efd;
}

.view-toggle:hover:not(.active) {
    background-color: #f8f9fa;
    border-color: #dee2e6;
}

.view-toggle i {
    font-size: 1rem;
}

@media (max-width: 1200px) {
    .drive-col-owner {
        width: 140px;
    }
    
    .drive-col-modified {
        width: 120px;
    }
    
    .drive-col-size {
        width: 80px;
    }
}

@media (max-width: 992px) {
    .drive-col-owner {
        display: none;
    }
    
    .drive-col-size {
        display: none;
    }
    
    .drive-list-header .drive-col-owner,
    .drive-list-header .drive-col-size {
        display: none;
    }
    
    .drive-col-modified {
        width: 100px;
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
    
    .drive-list-item {
        padding: 12px 16px;
    }
    
    .drive-col-name,
    .drive-col-owner,
    .drive-col-modified,
    .drive-col-size {
        width: 100%;
        text-align: left;
        padding-right: 0;
    }
    
    .drive-col-actions {
        position: absolute;
        top: 12px;
        right: 12px;
        width: auto;
    }
    
    .drive-more-btn {
        opacity: 1;
    }
    
    #listView .file-dropdown-menu {
        right: 0;
        top: 100%;
        transform: none;
        margin-top: 4px;
        margin-right: 0;
    }
    
    .file-info {
        padding-right: 40px;
    }
}

.drive-list-item .drive-list-row {
    border-radius: 4px;
}

.drive-list-item.selected {
    background-color: #e8f0fe;
}

.drive-list-item.loading {
    opacity: 0.6;
    pointer-events: none;
}

.drive-list-empty {
    padding: 48px 24px;
    text-align: center;
    color: #5f6368;
}

.drive-list-empty i {
    font-size: 48px;
    color: #dadce0;
    margin-bottom: 16px;
}

.drive-list-empty p {
    font-size: 0.875rem;
    margin: 0;
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

#listView .file-dropdown-menu.show {
    display: block;
    z-index: 1051;
}

.hover-effect {
    transition: all 0.2s ease;
}

.hover-effect:hover {
    transform: translateX(5px);
}

.hover-effect:hover span {
    color: #0d6efd !important;
    text-decoration: underline;
}

.table-container {
    overflow: visible;
}

@media (max-width: 991px) {
    .table-container {
        overflow-x: auto;
        overflow-y: visible;
    }
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

.document-preview-iframe {
    height: 200px;
    border: none;
    pointer-events: none;
    background: #f8f9fa;
    object-fit: cover;
}

@media (max-width: 768px) {
    .file-dropdown-menu {
        left: auto;
        right: 0;
        top: 100%;
        margin-left: 0;
        margin-top: 5px;
    }
    
    .file-preview-menu,
    .file-share-menu {
        left: auto;
        right: 0;
        min-width: 180px;
    }
    
    .file-submenu {
        left: auto;
        right: 100%;
        margin-left: 0;
        margin-right: 5px;
    }
    
    .document-preview-iframe {
        height: 150px;
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
</style>
@endsection

@section('content')
<div class="mb-4">
    <h4 class="fs-2 fw-semibold mb-1">Dashboard</h4>
    <p class="text-muted">Overview of your documents</p>
</div>

{{-- <div class="row g-3 mb-4">
    <div class="col-12 col-md-6 col-lg-3">
        <div class="card shadow-sm h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="card-title text-muted fw-semibold mb-0" style="font-size: 0.875rem;">Total Documents</h5>
                    <span class="badge bg-success" style="font-size: 0.75rem;">as of Today</span>
                </div>
                <h1 class="display-4 fw-bold text-dark">{{ count($documents) }}</h1>
                <div class="text-muted" style="font-size: 0.75rem;">&nbsp;</div>
            </div>
        </div>
    </div>

    <div class="col-12 col-md-6 col-lg-3">
        <div class="card shadow-sm h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="card-title text-muted fw-semibold mb-0" style="font-size: 0.875rem;">New Requests</h5>
                    <span class="badge bg-success" style="font-size: 0.75rem;">as of Today</span>
                </div>
                <h1 class="display-4 fw-bold text-dark">
                    {{ count($change_requests->where('created_at','>=',date('Y-m-d'))) + count($copy_requests->where('created_at','>=',date('Y-m-d'))) }}
                </h1>
                <div class="text-muted" style="font-size: 0.75rem;">&nbsp;</div>
            </div>
        </div>
    </div>

    <div class="col-12 col-md-6 col-lg-3">
        <div class="card shadow-sm h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="card-title text-muted fw-semibold mb-0" style="font-size: 0.875rem;">Pending</h5>
                    <span class="badge bg-success" style="font-size: 0.75rem;">as of Today</span>
                </div>
                <h1 class="display-4 fw-bold text-dark">
                    {{ count($change_requests->where('status','Pending')) + count($copy_requests->where('status','Pending')) }}
                </h1>
                <div class="text-muted" style="font-size: 0.75rem;">&nbsp;</div>
            </div>
        </div>
    </div>

    <div class="col-12 col-md-6 col-lg-3">
        <div class="card shadow-sm h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="card-title text-muted fw-semibold mb-0" style="font-size: 0.875rem;">Approved</h5>
                    <span class="badge bg-success" style="font-size: 0.75rem;">as of {{ date('M. Y') }}</span>
                </div>
                <h1 class="display-4 fw-bold text-dark">
                    {{ count($change_requests->where('status','Approved')) + count($copy_requests->where('status','Approved')) }}
                </h1>
                <div class="text-muted" style="font-size: 0.75rem;">&nbsp;</div>
            </div>
        </div>
    </div>
</div> --}}

<div class="row g-4">
    <div class="col-12 col-lg-9">
        <div class="card shadow-sm">
            <div class="card-body">
                <div class="mb-4">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="fw-semibold text-dark mb-0">For Approval</h5>
                        <a href="{{ route('documents.create') }}" class="btn btn-outline-primary btn-sm d-inline-flex align-items-center gap-1 d-none d-md-inline-flex">
                            <i class="ri-file-add-line"></i> New Document
                        </a>
                    </div>
                    
                    <div class="row g-2">
                        <div class="col-12 col-md-auto flex-md-grow-1">
                            <form action="{{ route('home') }}" method="GET" class="d-flex gap-2">
                                <div class="position-relative flex-grow-1">
                                    <i class="ri-search-line position-absolute top-50 translate-middle-y ms-3 text-muted"></i>
                                    <input type="text" name="pending_search" value="{{ request('pending_search') }}" 
                                        placeholder="Search pending..." class="form-control form-control-sm ps-5 w-100" style="height: 35px;">
                                </div>
                                <button type="submit" class="btn btn-outline-secondary btn-sm">
                                    <i class="ri-search-line"></i>
                                </button>
                                @if(request('pending_search'))
                                <a href="{{ route('home') }}" class="btn btn-outline-danger btn-sm">
                                    <i class="ri-close-line"></i>
                                </a>
                                @endif
                            </form>
                        </div>
                        
                        <div class="col-auto">
                            <div class="btn-group" role="group" aria-label="View toggle">
                                <button type="button" class="btn btn-outline-secondary btn-sm view-toggle active" data-view="grid">
                                    <i class="ri-grid-line"></i>
                                </button>
                                <button type="button" class="btn btn-outline-secondary btn-sm view-toggle" data-view="list">
                                    <i class="ri-list-check"></i>
                                </button>
                            </div>
                        </div>
                        
                        <div class="col-12 d-md-none">
                            <a href="{{ route('documents.create') }}" class="btn btn-outline-primary btn-sm d-flex align-items-center justify-content-center gap-1 w-100">
                                <i class="ri-file-add-line"></i> New Document
                            </a>
                        </div>
                    </div>
                </div>

                <div id="gridView" class="row row-cols-1 row-cols-sm-4 g-2">
                    @foreach ($pending_cards as $change_request)
                    <div class="col">
                        <div class="card border file-card position-relative">
                            <div class="position-absolute top-0 end-0 m-2 more-btn">
                                <button class="btn btn-sm btn-light p-1 file-more-btn" style="width: 28px; height: 28px; line-height: 1; border-radius: 6px;">
                                    <i class="ri-more-2-fill"></i>
                                </button>
                            </div>

                            <div class="file-dropdown-menu">
                                <button class="file-dropdown-item" data-action="display">
                                    <i class="ri-file-text-line"></i>
                                    <input type="hidden" class="file-path" value="{{ $change_request->file }}" />
                                    <span>View</span>
                                </button>
                                <div class="file-dropdown-divider"></div>
                                <button class="file-dropdown-item" data-action="approve" data-id="{{ $change_request->id }}">
                                    <i class="ri-checkbox-circle-line"></i>
                                    <span>Approve</span>
                                </button>
                            </div>

                            <a href='#' class="text-decoration-none" onclick="return false;">
                                <iframe src="https://docs.google.com/gview?url={{ urlencode(asset($change_request->file)) }}&embedded=true" 
                                        loading="lazy" 
                                        class="card-img-top document-preview-iframe" 
                                        scrolling="no" 
                                        frameborder="0"></iframe>
                                <div class="card-body p-2 text-start">
                                    <div class="docu d-flex align-items-center gap-2">
                                        <i class="ri-file-pdf-line text-danger" style="font-size: 1rem;"></i>
                                        @php
                                            $file = $change_request->file;
                                            $filename = explode('/',$file);
                                        @endphp 
                                        <div class="fw-semibold text-dark text-truncate" style="font-size: 0.75rem;">{{ $filename[2] }}</div>
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

                <div id="listView" class="d-none mb-2">
                    <div class="drive-list-container">
                        <div class="drive-list-header">
                            <div class="drive-list-row">
                                <div class="drive-col-name">
                                    <span>Name</span>
                                </div>
                                <div class="drive-col-owner">
                                    <span>Owner</span>
                                </div>
                                <div class="drive-col-modified">
                                    <span>Last modified</span>
                                </div>
                                <div class="drive-col-size">
                                    <span>File size</span>
                                </div>
                                <div class="drive-col-actions">
                                </div>
                            </div>
                        </div>
                        
                        <div class="drive-list-body">
                            @foreach ($pending_cards as $change_request)
                            @php
                                $file = $change_request->file;
                                $filename = explode('/',$file);
                                $filesize = file_exists(public_path($file)) ? filesize(public_path($file)) : 0;
                                $filesizeFormatted = $filesize > 0 ? number_format($filesize / 1024 / 1024, 2) . ' MB' : '--';
                            @endphp
                            <div class="drive-list-item file-card">
                                <div class="drive-list-row">
                                    <div class="drive-col-name">
                                        <div class="d-flex align-items-center gap-3">
                                            <div class="file-icon-wrapper">
                                                <i class="ri-file-pdf-line text-danger"></i>
                                            </div>
                                            <div class="file-info">
                                                <div class="file-name">{{ $filename[count($filename)-1] }}</div>
                                                <div class="file-subtitle text-muted">{{ $change_request->title }}</div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="drive-col-owner">
                                        <span>{{ $change_request->user->name }}</span>
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

                                <div class="file-dropdown-menu">
                                    <button class="file-dropdown-item" data-action="display">
                                        <i class="ri-eye-line"></i>
                                        <input type="hidden" class="file-path" value="{{ $change_request->file }}" />
                                        <span>Open</span>
                                    </button>
                                    <button class="file-dropdown-item" data-action="download">
                                        <i class="ri-download-line"></i>
                                        <span>Download</span>
                                    </button>
                                    <div class="file-dropdown-divider"></div>
                                    <button class="file-dropdown-item" data-action="approve" data-id="{{ $change_request->id }}">
                                        <i class="ri-checkbox-circle-line"></i>
                                        <span>Approve</span>
                                    </button>
                                    <div class="file-dropdown-divider"></div>
                                    <button class="file-dropdown-item" data-action="details">
                                        <i class="ri-information-line"></i>
                                        <span>Details</span>
                                    </button>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                @if($pending_cards->hasPages())
                <div class="d-flex justify-content-between align-items-center pt-3 border-top">
                    <div class="text-muted" style="font-size: 0.875rem;">
                        Showing <strong>{{ $pending_cards->firstItem() }}</strong> to <strong>{{ $pending_cards->lastItem() }}</strong> of <strong>{{ $pending_cards->total() }}</strong> pending documents
                    </div>
                    <nav aria-label="Pending documents pagination">
                        <ul class="pagination pagination-sm mb-0">
                            @if ($pending_cards->onFirstPage())
                                <li class="page-item disabled">
                                    <span class="page-link">Previous</span>
                                </li>
                            @else
                                <li class="page-item">
                                    <a class="page-link" href="{{ $pending_cards->appends(request()->except('pending_page'))->previousPageUrl() }}" rel="prev">
                                        Previous
                                    </a>
                                </li>
                            @endif

                            @foreach ($pending_cards->getUrlRange(1, $pending_cards->lastPage()) as $page => $url)
                                @if ($page == $pending_cards->currentPage())
                                    <li class="page-item active" aria-current="page">
                                        <span class="page-link">{{ $page }}</span>
                                    </li>
                                @else
                                    <li class="page-item">
                                        <a class="page-link" href="{{ $pending_cards->appends(request()->except('pending_page'))->url($page) }}">{{ $page }}</a>
                                    </li>
                                @endif
                            @endforeach

                            @if ($pending_cards->hasMorePages())
                                <li class="page-item">
                                    <a class="page-link" href="{{ $pending_cards->appends(request()->except('pending_page'))->nextPageUrl() }}" rel="next">
                                        Next
                                    </a>
                                </li>
                            @else
                                <li class="page-item disabled">
                                    <span class="page-link">Next</span>
                                </li>
                            @endif
                        </ul>
                    </nav>
                </div>
                @endif
            </div>
        </div>
    </div>

    <div class="col-12 col-lg-3">
        <div class="card shadow-sm" style="height: 449px; overflow-y:scroll;">
            <div class="card-body">
                <h5 class="fw-semibold text-dark mb-3">Public Form</h5>
                
                {{-- <div class="d-flex flex-column gap-3">
                    @foreach ($documents as $document)
                    <div class="d-flex align-items-center justify-content-between p-3 border rounded">
                        <div class="d-flex align-items-center gap-2">
                            <i class="ri-file-text-line" style="font-size: 1.5rem;"></i>
                            <p class="fs-5 text">{{ $document->control_code }}</p>
                            <small>{{ $document->title }}</small>
                        </div>
                    </div>
                    @endforeach
                </div> --}}
                <ul class="list-group">
                    @foreach ($documents->sortByDesc("id") as $document)
                    <li class="list-group-item">
                        <div class="d-flex align-items-center">
                            <div class="flex-grow-1">
                                <div class="d-flex">
                                    <div class="flex-shrink-0 avatar-xs">
                                        @foreach($document->attachments->where('type','pdf_copy') as $attachment)
                                        <a href="{{ url($attachment->attachment) }}" target="_blank" onclick="userView({{ $attachment->id }})">
                                            <div class="avatar-title bg-danger-subtle text-danger rounded">
                                                <i class="ri-file-text-line"></i>
                                            </div>
                                        </a>

                                        <form action="{{ url("/documents/user-view") }}" method="post" id="userView{{ $attachment->id }}" onsubmit="userView({{ $attachment->id }})">
                                            @csrf

                                            <input type="hidden" name="document_id" value="{{ $attachment->document_id }}">
                                        </form>
                                        @endforeach
                                    </div>
                                    <div class="flex-shrink-0 ms-2">
                                        <h6 class="fs-14 mb-0">{{ $document->control_code }}</h6>
                                        <small class="text-dark">Title: {{ $document->title }}</small> <br>
                                        <small class="text-dark">Date Added: {{ date("M d, Y", strtotime($document->created_at)) }}</small><br>
                                        @if(count($document->visitor) > 0)
                                        <small class="text=dark">Viewed by :</small>
                                        @endif
                                        <div class="avatar-group">
                                            @foreach ($document->visitor as $visitor)
                                                <a href="javascript: void(0);" class="avatar-group-item material-shadow" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-placement="top" title="{{ $visitor->user->name }}">
                                                    <div class="avatar-xxs">
                                                        <img src="{{ asset("images/no_image.png") }}" alt="" class="rounded-circle img-fluid">
                                                    </div>
                                                </a>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                            {{-- <div class="flex-shrink-0">
                                <span class="text-danger">-$25.50</span>
                            </div> --}}
                        </div>
                    </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
</div>

<!-- Documents Section -->
<div class="card shadow-sm mb-5" style="overflow: visible;">
    <div class="card-body" style="overflow: visible;">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h5 class="fw-semibold text-dark mb-0">Documents</h5>
        </div>

        <form action="{{ route('home') }}" method="GET">
            @if(request('pending_search'))
                <input type="hidden" name="pending_search" value="{{ request('pending_search') }}">
            @endif
            <div class="row g-3 mb-4">
                <div class="col-12 col-lg-auto flex-lg-grow-1">
                    <div class="position-relative">
                        <i class="ri-search-line position-absolute top-50 translate-middle-y ms-3 text-muted"></i>
                        <input type="text" name="doc_search" value="{{ request('doc_search') }}" placeholder="Search Document" class="form-control form-control-sm ps-5" style="min-width: 250px;">
                    </div>
                </div>
                <div class="col-auto d-flex align-items-center gap-2">
                    <label class="mb-0 text-nowrap" style="font-size: 0.875rem;">Sort by</label>
                    <select name="doc_sort" class="form-select form-select-sm" style="min-width: 120px;" onchange="this.form.submit()">
                        <option value="creation" {{ request('doc_sort', 'creation') == 'creation' ? 'selected' : '' }}>Creation</option>
                        <option value="name" {{ request('doc_sort') == 'name' ? 'selected' : '' }}>Name</option>
                        <option value="date" {{ request('doc_sort') == 'date' ? 'selected' : '' }}>Date</option>
                    </select>
                </div>
                <div class="col-auto d-flex align-items-center gap-2">
                    <label class="mb-0 text-nowrap" style="font-size: 0.875rem;">Status</label>
                    <select name="doc_status" class="form-select form-select-sm" style="min-width: 120px;" onchange="this.form.submit()">
                        <option value="Default" {{ request('doc_status', 'Default') == 'Default' ? 'selected' : '' }}>Default</option>
                        <option value="Approved" {{ request('doc_status') == 'Approved' ? 'selected' : '' }}>Approved</option>
                        <option value="Pending" {{ request('doc_status') == 'Pending' ? 'selected' : '' }}>Pending</option>
                        <option value="Declined" {{ request('doc_status') == 'Declined' ? 'selected' : '' }}>Declined</option>
                    </select>
                </div>
                <div class="col-auto d-flex align-items-center gap-2">
                    <label class="mb-0 text-nowrap" style="font-size: 0.875rem;">Show entries</label>
                    <select name="doc_per_page" class="form-select form-select-sm" style="min-width: 120px;" onchange="this.form.submit()">
                        <option value="10" {{ request('doc_per_page', '10') == '10' ? 'selected' : '' }}>10</option>
                        <option value="25" {{ request('doc_per_page') == '25' ? 'selected' : '' }}>25</option>
                        <option value="50" {{ request('doc_per_page') == '50' ? 'selected' : '' }}>50</option>
                        <option value="100" {{ request('doc_per_page') == '100' ? 'selected' : '' }}>100</option>
                    </select>
                </div>
                <div class="col-auto d-flex align-items-center gap-2">
                    <button type="submit" class="btn btn-sm btn-primary"><i class="ri-search-line"></i> Search</button>
                    @if(request()->hasAny(['doc_search','doc_sort','doc_status','doc_per_page']))
                    <a href="{{ route('home') }}" class="btn btn-sm btn-outline-danger"><i class="ri-close-line"></i> Reset</a>
                    @endif
                </div>
            </div>
        </form>

        <div class="table-container">
            <table class="modern-table tables">
                <thead class="table-light">
                    <tr>
                        <th style="font-size: 0.875rem; font-weight: 600;">Title</th>
                        <th style="font-size: 0.875rem; font-weight: 600;">Attachment</th>
                        <th style="font-size: 0.875rem; font-weight: 600;">Created By</th>
                        <th style="font-size: 0.875rem; font-weight: 600;">Status</th>
                        <th style="font-size: 0.875rem; font-weight: 600;">QR Code</th>
                        <th style="font-size: 0.875rem; font-weight: 600;" class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($change_requests as $change_request)
                    @php
                        $code = "DOC-".date('Y', strtotime($change_request->created_at)).'-'.str_pad($change_request->id,3,'0',STR_PAD_LEFT);
                        $file = $change_request->file;
                        $filename = explode('/',$file);
                    @endphp
                    <tr>
                        <td>
                            <div class="fw-semibold text-dark" style="font-size: 0.875rem;">{{ $change_request->title }}</div>
                            <small class="text-muted">{{ $code }}</small>
                        </td>
                        <td>
                            <a href="{{ url($change_request->file) }}" target="_blank" class="text-decoration-none d-flex align-items-center gap-2 hover-effect">
                                <i class="ri-file-pdf-line text-danger" style="font-size: 1.25rem;"></i>
                                <span style="font-size: 0.875rem;" class="text-dark">{{ $filename[count($filename)-1] }}</span>
                            </a>
                        </td>
                        <td>
                            <div style="font-size: 0.875rem;">{{ $change_request->user->name }}</div>
                            <small class="text-muted">{{ date('M d Y', strtotime($change_request->created_at)) }}</small>
                        </td>
                        <td>
                            @if($change_request->status == 'Approved')
                                <span class="badge bg-success" style="font-size: 0.75rem;">Approved</span>
                            @elseif($change_request->status == 'Pending')
                                <span class="badge bg-warning" style="font-size: 0.75rem;">Pending</span>
                            @elseif($change_request->status == 'Declined')
                                <span class="badge bg-danger" style="font-size: 0.75rem;">Declined</span>
                            @else
                                <span class="badge bg-secondary" style="font-size: 0.75rem;">Draft</span>
                            @endif
                        </td>
                        <td>
                            <button class="btn btn-sm btn-outline-primary view-qr-btn" 
                                    data-doc-id="{{ $code }}" 
                                    data-doc-title="{{ $change_request->title }}"
                                    data-change-request-id="{{ $change_request->id }}">
                                <i class="ri-qr-code-line"></i> View QR
                            </button>
                        </td>
                        <td class="text-center">
                            <div class="dropdown">
                                <button class="btn btn-sm btn-outline-secondary" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="ri-more-2-fill"></i>
                                </button>
                                <ul class="dropdown-menu">
                                    <li>
                                        <a href="{{ url($change_request->file) }}" class="dropdown-item" target="_blank">
                                            <i class="ri-eye-line me-2"></i>View Document
                                        </a>
                                    </li>
                                    @if($change_request->status == 'Pending')
                                    <li>
                                        <a href="{{ route('documents.signature', $change_request->id) }}" class="dropdown-item">
                                            <i class="ri-checkbox-circle-line me-2"></i>Approve
                                        </a>
                                    </li>
                                    @endif
                                    <li>
                                        <button class="dropdown-item print-doc-btn">
                                            <i class="ri-printer-line me-2"></i>Print
                                        </button>
                                    </li>
                                </ul>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted py-4">No documents found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($change_requests->hasPages())
        <div class="d-flex justify-content-between align-items-center mt-3">
            <div class="text-muted" style="font-size: 0.875rem;">
                Showing <strong>{{ $change_requests->firstItem() }}</strong> to <strong>{{ $change_requests->lastItem() }}</strong> of <strong>{{ $change_requests->total() }}</strong> entries
            </div>
            <nav>
                <ul class="pagination pagination-sm mb-0">
                    @if($change_requests->onFirstPage())
                        <li class="page-item disabled"><span class="page-link">Previous</span></li>
                    @else
                        <li class="page-item">
                            <a class="page-link" href="{{ $change_requests->appends(request()->except('table_page'))->previousPageUrl() }}">Previous</a>
                        </li>
                    @endif

                    @foreach($change_requests->getUrlRange(1, $change_requests->lastPage()) as $page => $url)
                        @if($page == $change_requests->currentPage())
                            <li class="page-item active"><span class="page-link">{{ $page }}</span></li>
                        @else
                            <li class="page-item">
                                <a class="page-link" href="{{ $change_requests->appends(request()->except('table_page'))->url($page) }}">{{ $page }}</a>
                            </li>
                        @endif
                    @endforeach

                    @if($change_requests->hasMorePages())
                        <li class="page-item">
                            <a class="page-link" href="{{ $change_requests->appends(request()->except('table_page'))->nextPageUrl() }}">Next</a>
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

@if($change_requests->count() > 0)
<div class="modal fade" id="qrCodeModal" tabindex="-1" aria-labelledby="qrCodeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="qrCodeModalLabel">Document QR Code</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                <div class="card border-0 bg-light p-4 mb-3">
                    <div id="qrCodeContainer" class="d-flex justify-content-center">
                    </div>
                </div>
                <div class="mb-2">
                    <svg class="barcode-display"
                        jsbarcode-format="Code39"
                        jsbarcode-textmargin="0"
                        jsbarcode-fontoptions="bold"
                        jsbarcode-displayvalue="true">
                    </svg>
                </div>
                <div class="alert alert-info mb-3" role="alert">
                    <i class="ri-information-line"></i> Scan this QR code to access document details
                </div>
                <div class="mb-2">
                    <strong>Document ID:</strong> <span id="qrDocId" class="text-primary"></span>
                </div>
                <div class="mb-2">
                    <strong>Document Title:</strong> <span id="qrDocTitle"></span>
                </div>
                {{-- <div class="mb-2">
                    <strong>Category:</strong> <span id="qrDocCategory"></span>
                </div>
                <div class="mb-2">
                    <strong>Status:</strong> <span id="qrDocStatus"></span>
                </div> --}}
                <div>
                    <strong>URL:</strong> 
                    <div class="input-group input-group-sm mt-1">
                        <input type="text" class="form-control" id="qrDocUrl" readonly>
                        <button class="btn btn-outline-secondary" type="button" id="copyUrlBtn">
                            <i class="ri-file-copy-line"></i> Copy
                        </button>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="printQrBtn">
                    <i class="ri-printer-line"></i> Print QR
                </button>
                <button type="button" class="btn btn-success" id="downloadQrBtn">
                    <i class="ri-download-line"></i> Download QR
                </button>
            </div>
        </div>
    </div>
</div>
@endif

<div id="qrPrintTemplate" style="display: none;">
    <div style="text-align: center; padding: 40px; font-family: Arial, sans-serif; max-width: 800px; margin: 0 auto;">
        <h2 style="margin-bottom: 30px; color: #333;">Document QR Code</h2>
        
        <div style="display: flex; justify-content: center; margin: 30px auto;">
            <div id="qrPrintCode" style="display: inline-block;"></div>
        </div>
        
        <div style="display: flex; justify-content: center; margin: 30px auto;">
            <svg class="barcode-print"
                jsbarcode-format="CODE39"
                jsbarcode-textmargin="0"
                jsbarcode-fontoptions="bold"
                jsbarcode-displayvalue="true"
                style="max-width: 100%;">
            </svg>
        </div>
        
        <div style="margin-top: 40px; text-align: center;">
            <p style="font-size: 18px; margin: 15px 0;"><strong>Document ID:</strong> <span id="qrPrintDocId"></span></p>
            <p style="font-size: 18px; margin: 15px 0;"><strong>Title:</strong> <span id="qrPrintDocTitle"></span></p>
            {{-- <p style="font-size: 14px; margin: 20px 0; color: #666; word-break: break-all;"><strong>URL:</strong> <span id="qrPrintDocUrl"></span></p> --}}
        </div>
        
        <div style="margin-top: 50px; padding-top: 20px; border-top: 2px solid #ddd;">
            <p style="font-size: 12px; color: #999; margin: 10px 0;">Scan this QR code or barcode to access document details</p>
            <p style="font-size: 12px; color: #999; margin: 10px 0;">Generated on: <span id="qrPrintDate"></span></p>
        </div>
    </div>
</div>

{{-- Documents Library Chart --}}
{{-- <div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Documents Library</h4>
                <div id="stocked"></div>
            </div>
        </div>
    </div>
</div> --}}

{{-- Requests Bar Chart --}}
{{-- @if((auth()->user()->role == "Administrator") || (auth()->user()->role == "Management Representative") || (auth()->user()->role == "Business Process Manager"))
<div class="row mt-3">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Requests</h4>
                <div id="morris-bar-chart"></div>
            </div>
        </div>
    </div>
</div>
@endif --}}

{{-- Permits and Licenses Donut Chart --}}
{{-- @if(count($permits) != 0)
<div class="row mt-3">
    <div class="col-lg-4">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Permits and licenses ({{ count($permits) }})</h4>
                <div id="morris-donut-chart"></div>
            </div>
        </div>
    </div>
</div>
@endif --}}

{{-- Document Requests Status Pie Chart --}}
{{-- @if((auth()->user()->role == "Administrator") || (auth()->user()->role == "Management Representative") || (auth()->user()->role == "Business Process Manager"))
<div class="row mt-3">
    <div class="col-lg-4">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Document Requests Status this {{ date('Y') }}</h4>
                <div id="pie"></div>
            </div>
        </div>
    </div>
</div>
@endif --}}

@endsection

@section('js')
<script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
<script src="{{ asset('barcode/JsBarcode.all.min.js') }}"></script>

<script>
    function userView(id) {
        document.getElementById("userView"+id).submit()
    }

    document.addEventListener('DOMContentLoaded', function() {
        const viewToggles = document.querySelectorAll('.view-toggle');
        const gridView = document.getElementById('gridView');
        const listView = document.getElementById('listView');
        
        const savedView = localStorage.getItem('pendingDocsView') || 'grid';
        setActiveView(savedView);
        
        viewToggles.forEach(button => {
            button.addEventListener('click', function() {
                const view = this.getAttribute('data-view');
                setActiveView(view);
                localStorage.setItem('pendingDocsView', view);
            });
        });
        
        function setActiveView(view) {
            viewToggles.forEach(btn => {
                if (btn.getAttribute('data-view') === view) {
                    btn.classList.add('active');
                } else {
                    btn.classList.remove('active');
                }
            });
            
            if (view === 'grid') {
                listView.classList.add('d-none');
                setTimeout(() => {
                    gridView.classList.remove('d-none');
                }, 50);
            } else {
                gridView.classList.add('d-none');
                setTimeout(() => {
                    listView.classList.remove('d-none');
                }, 50);
            }
        }
    });

    document.addEventListener('DOMContentLoaded', function() {
        const moreButtons = document.querySelectorAll('.file-more-btn');
        
        moreButtons.forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                
                const fileCard = this.closest('.file-card');
                const dropdown = fileCard.querySelector('.file-dropdown-menu');
                
                document.querySelectorAll('.file-dropdown-menu').forEach(menu => {
                    if (menu !== dropdown) {
                        menu.classList.remove('show');
                        const parentCard = menu.closest('.file-card');
                        if (parentCard) {
                            parentCard.classList.remove('dropdown-open');
                        }
                    }
                });
                
                dropdown.classList.toggle('show');
                
                if (dropdown.classList.contains('show')) {
                    fileCard.classList.add('dropdown-open');
                } else {
                    fileCard.classList.remove('dropdown-open');
                }
            });
        });

        document.addEventListener('click', function(e) {
            if (e.target.closest('.file-dropdown-item')) {
                const item = e.target.closest('.file-dropdown-item');
                e.preventDefault();
                e.stopPropagation();
                
                const action = item.getAttribute('data-action');
                const filePath = item.querySelector(".file-path")?.value;
                const fileCard = item.closest('.file-card');
                
                switch(action) {
                    case 'display':
                        if (filePath) {
                            window.open("{{ url('') }}/" + filePath, '_blank');
                        }
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
                        if (changeRequestId) {
                            window.location.href = "{{ route('documents.signature', '') }}/" + changeRequestId;
                        }
                        break;
                        
                    case 'details':
                        console.log('Show details for:', filePath);
                        alert('Details feature - to be implemented');
                        break;
                }
                
                const menu = item.closest('.file-dropdown-menu');
                menu.classList.remove('show');
                fileCard.classList.remove('dropdown-open');
            }
        });

        document.addEventListener('click', function(e) {
            if (!e.target.closest('.file-more-btn') && 
                !e.target.closest('.file-dropdown-menu')) {
                document.querySelectorAll('.file-dropdown-menu').forEach(menu => {
                    menu.classList.remove('show');
                    const parentCard = menu.closest('.file-card');
                    if (parentCard) {
                        parentCard.classList.remove('dropdown-open');
                    }
                });
            }
        });

        document.querySelectorAll('.file-dropdown-menu').forEach(menu => {
            menu.addEventListener('click', e => {
                if (!e.target.closest('.file-dropdown-item')) {
                    e.stopPropagation();
                }
            });
        });
    });

    document.addEventListener('DOMContentLoaded', function() {
        const listItems = document.querySelectorAll('#listView .drive-list-item');
        
        listItems.forEach(item => {
            item.addEventListener('click', function(e) {
                if (e.target.closest('.file-more-btn') || 
                    e.target.closest('.file-dropdown-menu')) {
                    return;
                }
                
                listItems.forEach(i => i.classList.remove('selected'));
                
                this.classList.add('selected');
            });
        });
    });

    document.addEventListener('DOMContentLoaded', function() {
        const listItems = document.querySelectorAll('#listView .drive-list-item');
        
        listItems.forEach(item => {
            item.addEventListener('dblclick', function(e) {
                if (e.target.closest('.file-more-btn')) {
                    return;
                }
                
                const filePath = this.querySelector('.file-path')?.value;
                if (filePath) {
                    window.open("{{ url('') }}/" + filePath, '_blank');
                }
            });
        });
    });
</script>

<script>
JsBarcode(".barcode").init();

document.addEventListener('DOMContentLoaded', function() {
    const moreButtons = document.querySelectorAll('.file-more-btn');
    
    moreButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            const dropdown = this.parentElement.nextElementSibling;
            const fileCard = this.closest('.file-card');
            
            document.querySelectorAll('.file-dropdown-menu').forEach(menu => {
                if (menu !== dropdown) {
                    menu.classList.remove('show');
                    menu.closest('.file-card')?.classList.remove('dropdown-open');
                }
            });
            
            dropdown.classList.toggle('show');
            
            if (dropdown.classList.contains('show')) {
                fileCard.classList.add('dropdown-open');
            } else {
                fileCard.classList.remove('dropdown-open');
            }
        });
    });

    document.querySelectorAll('.file-dropdown-item').forEach(item => {
        item.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            const action = this.getAttribute('data-action');
            const actionText = this.querySelector('span').textContent.trim();
            const filePath = this.querySelector("#file").value
            
            switch(action) {
                case 'display':
                    window.location.href = "{{ url('') }}/" + filePath;
                    break;
                case 'approve':
                    // @php
                    // window.location.href = '{{ route("documents.signature") }}';
                    // @endphp
                    break;
                case 'view':
                    window.location.href = filePath;
                    break;
            }
            
            const menu = this.closest('.file-dropdown-menu');
            menu.classList.remove('show');
            menu.closest('.file-card')?.classList.remove('dropdown-open');
        });
    });

    document.addEventListener('click', function(e) {
        if (!e.target.closest('.file-more-btn') && 
            !e.target.closest('.file-dropdown-menu')) {
            document.querySelectorAll('.file-dropdown-menu').forEach(menu => {
                menu.classList.remove('show');
                menu.closest('.file-card')?.classList.remove('dropdown-open');
            });
        }
    });

    document.querySelectorAll('.file-dropdown-menu').forEach(menu => {
        menu.addEventListener('click', e => e.stopPropagation());
    });
});
</script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const qrModalElement = document.getElementById('qrCodeModal');
    const qrModal = new bootstrap.Modal(qrModalElement);
    
    const viewQrButtons = document.querySelectorAll('.view-qr-btn');
    
    viewQrButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            
            const docId = this.getAttribute('data-doc-id');
            const docTitle = this.getAttribute('data-doc-title');
            const changeRequestId = this.getAttribute('data-change-request-id');
            
            const docUrl = window.location.origin + '/change-request/' + changeRequestId;
            
            document.getElementById('qrDocId').textContent = docId;
            document.getElementById('qrDocTitle').textContent = docTitle;
            document.getElementById('qrDocUrl').value = docUrl;
            
            const qrContainer = document.getElementById('qrCodeContainer');
            qrContainer.innerHTML = '';
            
            new QRCode(qrContainer, {
                text: docUrl,
                width: 256,
                height: 256,
                colorDark: "#000000",
                colorLight: "#ffffff",
                correctLevel: QRCode.CorrectLevel.H
            });
            
            JsBarcode(".barcode-display", docId, {
                format: "CODE39",
                textMargin: 0,
                fontOptions: "bold",
                displayValue: true
            });
            
            qrModal.show();
        });
    });
    
    document.getElementById('copyUrlBtn').addEventListener('click', function() {
        const urlInput = document.getElementById('qrDocUrl');
        urlInput.select();
        document.execCommand('copy');
        
        const originalText = this.innerHTML;
        this.innerHTML = '<i class="ri-check-line"></i> Copied!';
        setTimeout(() => {
            this.innerHTML = originalText;
        }, 2000);
    });
    
    document.getElementById('printQrBtn').addEventListener('click', function() {
        const docId = document.getElementById('qrDocId').textContent;
        const docTitle = document.getElementById('qrDocTitle').textContent;
        const docUrl = document.getElementById('qrDocUrl').value;
        
        document.getElementById('qrPrintDocId').textContent = docId;
        document.getElementById('qrPrintDocTitle').textContent = docTitle;
        // document.getElementById('qrPrintDocUrl').textContent = docUrl;
        document.getElementById('qrPrintDate').textContent = new Date().toLocaleString();
        
        const printQrContainer = document.getElementById('qrPrintCode');
        printQrContainer.innerHTML = '';
        new QRCode(printQrContainer, {
            text: docUrl,
            width: 256,
            height: 256,
            colorDark: "#000000",
            colorLight: "#ffffff",
            correctLevel: QRCode.CorrectLevel.H
        });
        
        JsBarcode(".barcode-print", docId, {
            format: "CODE39",
            width: 2,
            height: 80,
            textMargin: 0,
            fontOptions: "bold",
            displayValue: true,
            fontSize: 14
        });
        
        setTimeout(() => {
            const printContents = document.getElementById('qrPrintTemplate').innerHTML;
            const originalContents = document.body.innerHTML;
            
            document.body.innerHTML = printContents;
            document.body.style.display = 'flex';
            document.body.style.justifyContent = 'center';
            document.body.style.alignItems = 'center';
            
            window.print();
            
            document.body.innerHTML = originalContents;
            location.reload();
        }, 500);
    });
    
    document.getElementById('downloadQrBtn').addEventListener('click', function() {
        const qrCanvas = document.querySelector('#qrCodeContainer canvas');
        if (qrCanvas) {
            const docId = document.getElementById('qrDocId').textContent;
            const url = qrCanvas.toDataURL('image/png');
            const link = document.createElement('a');
            link.download = `QR_${docId}.png`;
            link.href = url;
            link.click();
        }
    });
    
    const moreButtons = document.querySelectorAll('.file-more-btn');
    
    moreButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.stopPropagation();
            
            const dropdown = this.nextElementSibling;
            const previewMenu = dropdown.nextElementSibling;
            const shareMenu = previewMenu.nextElementSibling;
            const fileCard = this.closest('.file-card');
            
            document.querySelectorAll('.file-dropdown-menu, .file-preview-menu, .file-share-menu').forEach(menu => {
                if (menu !== dropdown && menu !== previewMenu && menu !== shareMenu) {
                    menu.classList.remove('show');
                    menu.closest('.file-card')?.classList.remove('dropdown-open');
                }
            });
            
            dropdown.classList.toggle('show');
            previewMenu.classList.remove('show');
            shareMenu.classList.remove('show');
            
            if (dropdown.classList.contains('show')) {
                fileCard.classList.add('dropdown-open');
            } else {
                fileCard.classList.remove('dropdown-open');
            }
        });
    });

    document.querySelectorAll('.file-dropdown-menu, .file-preview-menu, .file-share-menu')
        .forEach(menu => {
            menu.addEventListener('click', e => e.stopPropagation());
        });

    document.addEventListener('click', function(e) {
        if (!e.target.closest('.file-more-btn') &&
            !e.target.closest('.file-dropdown-menu') &&
            !e.target.closest('.file-preview-menu') &&
            !e.target.closest('.file-share-menu')) {
            document.querySelectorAll('.file-dropdown-menu, .file-preview-menu, .file-share-menu').forEach(menu => {
                menu.classList.remove('show');
                menu.closest('.file-card')?.classList.remove('dropdown-open');
            });
        }
    });

    document.querySelectorAll('.file-dropdown-item').forEach(item => {
        item.addEventListener('click', function(e) {
            e.stopPropagation();
            const action = this.getAttribute('data-action');
            
            if (action === 'preview') {
                const dropdown = this.closest('.file-dropdown-menu');
                const previewMenu = dropdown.nextElementSibling;
                dropdown.classList.remove('show');
                previewMenu.classList.add('show');
                return;
            }
            if (action === 'share') {
                const dropdown = this.closest('.file-dropdown-menu');
                const previewMenu = dropdown.nextElementSibling;
                const shareMenu = previewMenu.nextElementSibling;
                dropdown.classList.remove('show');
                shareMenu.classList.add('show');
                return;
            }
            if (action === 'back') {
                const previewMenu = this.closest('.file-preview-menu');
                const dropdown = previewMenu.previousElementSibling;
                previewMenu.classList.remove('show');
                dropdown.classList.add('show');
                return;
            }
            if (action === 'back-share') {
                const shareMenu = this.closest('.file-share-menu');
                const previewMenu = shareMenu.previousElementSibling;
                const dropdown = previewMenu.previousElementSibling;
                shareMenu.classList.remove('show');
                dropdown.classList.add('show');
                return;
            }

            const actionText = this.querySelector('span').textContent.trim();
            console.log('Action clicked:', actionText);
            
            const menu = this.closest('.file-dropdown-menu, .file-preview-menu, .file-share-menu');
            menu.classList.remove('show');
            menu.closest('.file-card')?.classList.remove('dropdown-open');
        });
    });
});
</script>

{{-- Chart Scripts (COMMENTED OUT) --}}
{{-- 
<script src="{{ asset('login_css/js/plugins/dataTables/datatables.min.js')}}"></script>
<script src="{{ asset('login_css/js/plugins/chosen/chosen.jquery.js') }}"></script>
<script src="{{ asset('login_css/js/plugins/chartJs/Chart.min.js') }}"></script>
<script src="{{ asset('login_css/js/plugins/morris/raphael-2.1.0.min.js') }}"></script>
<script src="{{ asset('login_css/js/plugins/morris/morris.js') }}"></script>
<script src="{{ asset('login_css/js/plugins/d3/d3.min.js') }}"></script>
<script src="{{ asset('login_css/js/plugins/c3/c3.min.js') }}"></script>

<script>
    var departments = {!! json_encode(($departments)->toArray()) !!};
    var for_renewal = {!! json_encode((count($permits->where('expiration_date','!=',null)->where('expiration_date','<',date('Y-m-d', strtotime("+3 months", strtotime(date('Y-m-d')))))))) !!};
    var over_due = {!! json_encode((count($permits->where('expiration_date','!=',null)->where('expiration_date','<',date('Y-m-d'))))) !!};
    var active = {!! json_encode((count($permits->where('expiration_date','!=',null)->where('expiration_date','>=',date('Y-m-d', strtotime("+3 months", strtotime(date('Y-m-d')))))))) !!};
    var no_expiration = {!! json_encode((count($permits->where('expiration_date','==',null)))) !!};
    var types = {!! json_encode(($categories->pluck('name'))->toArray()) !!};
    var obsoletes = {!! json_encode(($departments->pluck('obsoletes_count'))->toArray()) !!};
    var months = {!! json_encode(($months)) !!};

    var pending = {!!json_encode(($yearChangeRequests->where('status','Pending')->count()))!!}
    var approved = {!!json_encode(($yearChangeRequests->where('status','Approved')->count()))!!}
    var declined = {!!json_encode(($yearChangeRequests->where('status','Declined')->count()))!!}
    
    $(function() {
        // Morris Donut Chart
        Morris.Donut({
            element: 'morris-donut-chart',
            data: [
                { label: "For Renewal", value: for_renewal-over_due },
                { label: "Overdue", value: over_due },
                { label: "Active", value: active },
                { label: "No Expiration", value: no_expiration }
            ],
            resize: true,
            colors: ['#FFA500','#f44336', '#54cdb4','#1ab394'],
        });

        // Morris Bar Chart
        var aaa = months;
        Morris.Bar({
            element: 'morris-bar-chart',
            data: aaa,
            xkey: 'y',
            ykeys: ['a', 'b'],
            labels: ['Change Requests', 'Copy Requests'],
            hideHover: 'auto',
            resize: true,
            barColors: ['#1ab394', '#cacaca'],
        });
    });

    $(document).ready(function(){
        var types_names = {!! json_encode(($categories)->toArray()) !!};
        var colors ={};
        var column = ['x'];
 
        for(y=0;y<departments.length;y++) {
            column.push(departments[y].code+"("+departments[y].documents_count+")");
        }
        
        var types = [];
        var columns = [column];
        
        for(i = 0; i < types_names.length; i++) {
            type_column = [types_names[i].code];
            for(z = 0; z < departments.length; z++) {
                var doc = departments[z].documents;
                var count = doc.filter(o => o.category === types_names[i].name);
                type_column.push(count.length)
            }
            columns.push(type_column);
            colors[types_names[i].code] = types_names[i].color;
            types.push(types_names[i].code);
        }
        
        final_types = [types];
        
        // C3 Stacked Bar Chart
        c3.generate({
            bindto: '#stocked',
            data:{
                x : 'x',
                columns: columns,
                colors: colors,
                type: 'bar',
                groups: final_types,
            },
            axis: {
                x: {
                    show: true,
                    type: 'categorized',
                },
                y2: {
                    show: true,
                    label: 'Counts'
                },
                y: {
                    show: true,
                    label: 'Counts'
                },
            }
        });

        // C3 Pie Chart
        c3.generate({
            bindto: '#pie',
            data:{
                columns: [
                    ['Approved', approved],
                    ['Declined', declined],
                    ['Pending', pending]
                ],
                colors:{
                    Approved: '#54cdb4',
                    Declined: '#f44336',
                    Pending: '#BABABA',
                },
                type : 'pie'
            }
        });

        $('.locations').chosen({width: "100%"});
        $('.tables').DataTable({
            pageLength: 10,
            responsive: true,
            dom: '<"html5buttons"B>lTfgitp',
            buttons: []
        });
    });
</script>
--}}
@endsection