@extends('layouts.header')

@section('css')
<link href="{{ asset('login_css/css/plugins/chosen/bootstrap-chosen.css') }}" rel="stylesheet">
<link href="{{ asset('login_css/css/plugins/sweetalert/sweetalert.css') }}" rel="stylesheet">

<style>
    .dashboard-header {
        margin-bottom: 30px;
    }

    .dashboard-card {
        background: white;
        border-radius: 10px;
        padding: 20px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        transition: all 0.3s;
        height: 100%;
        display: flex;
        flex-direction: column;
    }

    .dashboard-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.12);
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

    .dashboard-card.primary .icon-circle {
        background: #e3f2fd;
        color: #2196F3;
    }

    .dashboard-card.success .icon-circle {
        background: #e8f5e9;
        color: #4caf50;
    }

    .dashboard-card.warning .icon-circle {
        background: #fff3e0;
        color: #ff9800;
    }

    .dashboard-card.danger .icon-circle {
        background: #ffebee;
        color: #f44336;
    }

    .dashboard-card h2 {
        font-size: 28px;
        font-weight: 700;
        margin: 0 0 4px 0;
        color: #2c3e50;
        line-height: 1;
    }

    .dashboard-card h2 a {
        color: #2c3e50;
        text-decoration: none;
    }

    .dashboard-card h2 a:hover {
        color: #0d6efd;
    }

    .dashboard-card p {
        margin: 0;
        font-size: 13px;
        color: #6c757d;
        font-weight: 500;
    }

    .documents-section {
        background: white;
        border-radius: 10px;
        padding: 25px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
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

    .header-actions {
        display: flex;
        gap: 10px;
    }

    .btn-upload {
        background: #8B0000;
        color: white;
        border: none;
        padding: 10px 20px;
        border-radius: 5px;
        font-size: 14px;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        cursor: pointer;
        transition: all 0.3s;
    }

    .btn-upload:hover {
        background: #6B0000;
        color: white;
    }

    .search-filter-bar {
        display: flex;
        gap: 15px;
        margin-bottom: 25px;
        flex-wrap: wrap;
    }

    .filter-group {
        display: flex;
        flex-direction: column;
        gap: 5px;
    }

    .filter-group label {
        font-size: 13px;
        color: #495057;
        font-weight: 500;
        margin: 0;
    }

    .filter-group select {
        padding: 8px 12px;
        border: 1px solid #dee2e6;
        border-radius: 5px;
        font-size: 14px;
        min-width: 200px;
    }

    .btn-search {
        background: #2196F3;
        color: white;
        border: none;
        padding: 8px 24px;
        border-radius: 5px;
        font-size: 14px;
        cursor: pointer;
        transition: all 0.3s;
        align-self: flex-end;
        height: fit-content;
    }

    .btn-search:hover {
        background: #1976D2;
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

    .btn-action {
        padding: 6px 12px;
        border-radius: 4px;
        font-size: 13px;
        border: none;
        cursor: pointer;
        transition: all 0.3s;
        text-decoration: none;
        display: inline-block;
    }

    .btn-action.view {
        background: #2196F3;
        color: white;
    }

    .btn-action.view:hover {
        background: #1976D2;
        color: white;
    }

    .badge-status {
        padding: 5px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
    }

    .badge-status.active {
        background: #e8f5e9;
        color: #4caf50;
    }

    .badge-status.obsolete {
        background: #ffebee;
        color: #f44336;
    }

    .badge-info {
        background: #e3f2fd;
        color: #2196F3;
        padding: 4px 10px;
        border-radius: 4px;
        font-size: 11px;
        font-weight: 600;
        display: inline-block;
        margin: 2px;
    }

    .dataTables_wrapper {
        padding-top: 20px;
    }

    .dataTables_wrapper .dataTables_length {
        float: right;
        margin-bottom: 15px;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }

    .dataTables_wrapper .dataTables_length label {
        margin: 0;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .dataTables_wrapper .dataTables_length select {
        padding: 6px 30px 6px 10px;
        border: 1px solid #dee2e6;
        border-radius: 4px;
        margin: 0;
    }

    .dataTables_wrapper .dataTables_filter {
        float: left;
        margin-bottom: 15px;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }

    .dataTables_wrapper .dataTables_filter label {
        margin: 0;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .dataTables_wrapper .dataTables_filter input {
        padding: 6px 12px;
        border: 1px solid #dee2e6;
        border-radius: 4px;
        margin: 0;
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
        display: inline-flex;
        align-items: center;
        gap: 5px;
    }

    .dt-button {
        background: white !important;
        border: 1px solid #dee2e6 !important;
        color: #495057 !important;
        padding: 6px 12px !important;
        border-radius: 4px !important;
        margin: 0 !important;
        font-size: 13px !important;
        height: 34px;
        display: inline-flex;
        align-items: center;
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
</style>
@endsection

@section('content')
<div class="chat-wrapper d-lg-flex gap-1 mx-n4 mt-n4 p-1">
    {{-- <div class="file-manager-sidebar minimal-border">
        <div class="p-3 d-flex flex-column h-100">
            <div class="mb-3">
                <h5 class="mb-0 fw-semibold">Documents</h5>
            </div>
            <div class="search-box">
                <input type="text" class="form-control bg-light border-light"
                    placeholder="Search here...">
                <i class="ri-search-2-line search-icon"></i>
            </div>
            <div class="mt-3 mx-n4 px-4 file-menu-sidebar-scroll" data-simplebar>
                <ul class="list-unstyled file-manager-menu">
                    <li>
                        <a data-bs-toggle="collapse" href="#collapseExample" role="button"
                            aria-expanded="true" aria-controls="collapseExample">
                            <i class="ri-folder-2-line align-bottom me-2"></i> <span
                                class="file-list-link">My
                                Drive</span>
                        </a>
                        <div class="collapse show" id="collapseExample">
                            <ul class="sub-menu list-unstyled">
                                <li>
                                    <a href="#!">Assets</a>
                                </li>
                                <li>
                                    <a href="#!">Marketing</a>
                                </li>
                                <li>
                                    <a href="#!">Personal</a>
                                </li>
                                <li>
                                    <a href="#!">Projects</a>
                                </li>
                                <li>
                                    <a href="#!">Templates</a>
                                </li>
                            </ul>
                        </div>
                    </li>
                    <li>
                        <a href="#!"><i class="ri-file-list-2-line align-bottom me-2"></i> <span
                                class="file-list-link">Documents</span></a>
                    </li>
                    <li>
                        <a href="#!"><i class="ri-image-2-line align-bottom me-2"></i> <span
                                class="file-list-link">Media</span></a>
                    <li>
                        <a href="#!"><i class="ri-history-line align-bottom me-2"></i> <span
                                class="file-list-link">Recent</span></a>
                    </li>
                    <li>
                        <a href="#!"><i class="ri-star-line align-bottom me-2"></i> <span
                                class="file-list-link">Important</span></a>
                    </li>
                    </li>
                    <li>
                        <a href="#!"><i class="ri-delete-bin-line align-bottom me-2"></i> <span
                                class="file-list-link">Deleted</span></a>
                    </li>
                </ul>
            </div>


            <div class="mt-auto">
                <h6 class="fs-11 text-muted text-uppercase mb-3">Storage Status</h6>
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <i class="ri-database-2-line fs-17"></i>
                    </div>
                    <div class="flex-grow-1 ms-3 overflow-hidden">
                        <div class="progress mb-2 progress-sm">
                            <div class="progress-bar bg-success" role="progressbar"
                                style="width: 25%" aria-valuenow="25" aria-valuemin="0"
                                aria-valuemax="100"></div>
                        </div>
                        <span class="text-muted fs-12 d-block text-truncate"><b>47.52</b>GB used of
                            <b>119</b>GB</span>
                    </div>
                </div>
            </div>
        </div>
    </div> --}}
    <div class="file-manager-content minimal-border w-100 p-3 py-0">
        <div class="mx-n3 pt-4 px-4 file-manager-content-scroll" data-simplebar>
            <div id="folder-list" class="mb-2">
                <div class="row justify-content-beetwen g-2 mb-3">

                    <div class="col">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0 me-2 d-block d-lg-none">
                                <button type="button"
                                    class="btn btn-soft-success btn-icon btn-sm fs-16 file-menu-btn">
                                    <i class="ri-menu-2-fill align-bottom"></i>
                                </button>
                            </div>
                            <div class="flex-grow-1">
                                <h5 class="fs-16 mb-0">Documents</h5>
                            </div>
                        </div>
                    </div>
                    <!--end col-->
                    <div class="col-auto">
                        <div class="d-flex gap-2 align-items-start">
                            <select class="form-control" data-choices data-choices-search-false
                                name="choices-single-default" id="file-type">
                                <option value="">File Type</option>
                                <option value="All" selected>All</option>
                                <option value="Video">Video</option>
                                <option value="Images">Images</option>
                                <option value="Music">Music</option>
                                <option value="Documents">Documents</option>
                            </select>

                            <button class="btn btn-success w-sm create-folder-modal flex-shrink-0"
                                data-bs-toggle="modal" data-bs-target="#createFolderModal"><i
                                    class="ri-add-line align-bottom me-1"></i> Create
                                Folders</button>
                        </div>
                    </div>
                    <!--end col-->
                </div>
                <!--end row-->
                <div class="row" id="folderlist-data">
                    @foreach ($document_folders as $folder)
                    <div class="col-xxl-3 col-6 folder-card">
                        <div class="card bg-light shadow-none" id="folder-1">
                            <div class="card-body">
                                <div class="d-flex mb-1">
                                    <div
                                        class="form-check form-check-danger mb-3 fs-15 flex-grow-1">
                                        <input class="form-check-input" type="checkbox" value=""
                                            id="folderlistCheckbox_1" checked>
                                        <label class="form-check-label"
                                            for="folderlistCheckbox_1"></label>
                                    </div>
                                    <div class="dropdown">
                                        <button
                                            class="btn btn-ghost-primary btn-icon btn-sm dropdown material-shadow-none"
                                            type="button" data-bs-toggle="dropdown"
                                            aria-expanded="false">
                                            <i class="ri-more-2-fill fs-16 align-bottom"></i>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end">
                                            <li><a class="dropdown-item view-item-btn"
                                                    href="{{ url('documents/folder/'.$folder->id) }}">Open</a></li>
                                            <li><a class="dropdown-item edit-folder-list"
                                                    href="#createFolderModal" data-bs-toggle="modal"
                                                    role="button">Rename</a></li>
                                            <li><a class="dropdown-item" href="#removeFolderModal"
                                                    data-bs-toggle="modal" role="button">Delete</a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>

                                <div class="text-center">
                                    <div class="mb-2">
                                        <i
                                            class="ri-folder-2-fill align-bottom text-warning display-5"></i>
                                    </div>
                                    <h6 class="fs-15 folder-name">{{ $folder->name }}</h6>
                                </div>
                                <div class="hstack mt-4 text-muted">
                                    <span class="me-auto"><b>{{ count($folder->document) }}</b> Files</span>
                                    <span><b>0</b>GB</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                    <!--end col-->
                </div>
                <!--end row-->
            </div>
            <div>
                <div class="d-flex align-items-center mb-3">
                    <h5 class="flex-grow-1 fs-16 mb-0" id="filetype-title">Recent File</h5>
                    <div class="flex-shrink-0">
                        <button class="btn btn-success createFile-modal" data-bs-toggle="modal"
                            data-bs-target="#uploadDocument"><i
                                class="ri-add-line align-bottom me-1"></i>
                            Create File</button>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table modern-table tables mb-0">
                        <thead class="table-active">
                            <tr>
                                <th scope="col">Actions</th>
                                <th scope="col">Name</th>
                                <th scope="col">File Item</th>
                                <th scope="col">File Size</th>
                                <th scope="col">Recent Date</th>
                                <th scope="col">QR Code</th>
                            </tr>
                        </thead>
                        <tbody id="file-list">
                            @foreach ($documents as $document)
                                @php
                                    $attachment = $document->attachments->where('type','pdf_copy')->first();
                                @endphp
                                <tr>
                                    <td>
                                        <div class="dropdown">
                                            <button class="btn btn-sm btn-outline-secondary" type="button" id="documentDropdown{{$document->id}}" data-bs-toggle="dropdown" aria-expanded="false">
                                                <i class="ri-more-2-fill"></i>
                                            </button>
                                            <ul class="dropdown-menu" aria-labelledby="documentDropdown{{$document->id}}">
                                                <li>
                                                    <a class="dropdown-item" href="{{ url('view-document/'.$document->id) }}" target="_blank">
                                                        <i class="ri-eye-line me-2"></i>View Document
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>
                                    </td>
                                    <td>{{ $document->title }}</td>
                                    <td>
                                        @if ($attachment)
                                        <a href="{{ url($attachment->attachment) }}" target="_blank">
                                            <i class="fa fa-file-pdf-o"></i>
                                        </a>
                                        @endif
                                    </td>
                                    <td>
                                        @php
                                            $size=0;
                                            if($attachment)
                                            {
                                                $path = public_path().$attachment->attachment;
                                                if (file_exists($path))
                                                {
                                                    $size = filesize($path)/1024;
                                                }
                                            }
                                        @endphp
                                        {{ round($size,2). "KB"}}
                                    </td>
                                    <td>
                                        {{ date('M d Y', strtotime($document->updated_at)) }}
                                    </td>
                                    <td>
                                        <button class="btn btn-sm btn-outline-primary view-qr-btn" data-doc-id="{{ $document->control_code }}" data-doc-title="{{ $document->title }}">
                                            <i class="ri-qr-code-line"></i> View QR
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>    
    <div class="file-manager-detail-content minimal-border p-3 py-0" style="display: block;">
        <div class="mx-n3 pt-3 px-3 file-detail-content-scroll" data-simplebar>
            <div id="folder-overview">
                <div class="d-flex align-items-center pb-3 border-bottom border-bottom-dashed">
                    <h5 class="flex-grow-1 fw-semibold mb-0">Overview</h5>
                    <div>
                        <button type="button"
                            class="btn btn-soft-danger btn-icon btn-sm fs-16 close-btn-overview">
                            <i class="ri-close-fill align-bottom"></i>
                        </button>
                    </div>
                </div>
                <div id="simple_dount_chart"
                    data-colors='["--vz-info", "--vz-danger", "--vz-primary", "--vz-success"]'
                    class="apex-charts mt-3" dir="ltr"></div>
                <div class="mt-4">
                    <ul class="list-unstyled vstack gap-4">
                        <li>
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0">
                                    <div class="avatar-xs">
                                        <div
                                            class="avatar-title rounded bg-secondary-subtle text-secondary">
                                            <i class="ri-file-text-line fs-17"></i>
                                        </div>
                                    </div>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h5 class="mb-1 fs-15">Documents</h5>
                                    <p class="mb-0 fs-12 text-muted">2348 files</p>
                                </div>
                                <b>27.01 GB</b>
                            </div>
                        </li>
                        <li>
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0">
                                    <div class="avatar-xs">
                                        <div
                                            class="avatar-title rounded bg-success-subtle text-success">
                                            <i class="ri-gallery-line fs-17"></i>
                                        </div>
                                    </div>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h5 class="mb-1 fs-15">Media</h5>
                                    <p class="mb-0 fs-12 text-muted">12480 files</p>
                                </div>
                                <b>20.87 GB</b>
                            </div>
                        </li>
                        <li>
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0">
                                    <div class="avatar-xs">
                                        <div
                                            class="avatar-title rounded bg-warning-subtle text-warning">
                                            <i class="ri-folder-2-line fs-17"></i>
                                        </div>
                                    </div>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h5 class="mb-1 fs-15">Projects</h5>
                                    <p class="mb-0 fs-12 text-muted">349 files</p>
                                </div>
                                <b>4.10 GB</b>
                            </div>
                        </li>
                        <li>
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0">
                                    <div class="avatar-xs">
                                        <div
                                            class="avatar-title rounded bg-primary-subtle text-primary">
                                            <i class="ri-error-warning-line fs-17"></i>
                                        </div>
                                    </div>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h5 class="mb-1 fs-15">Others</h5>
                                    <p class="mb-0 fs-12 text-muted">9873 files</p>
                                </div>
                                <b>33.54 GB</b>
                            </div>
                        </li>
                    </ul>
                </div>
                <div class="pb-3 mt-auto">
                    <div class="alert alert-danger d-flex align-items-center mb-0">
                        <div class="flex-shrink-0">
                            <i class="ri-cloud-line text-danger align-bottom display-5"></i>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h5 class="text-danger fs-14">Upgrade to Pro</h5>
                            <p class="text-muted mb-2">Get more space for your...</p>
                            <button class="btn btn-sm btn-danger"><i
                                    class="ri-upload-cloud-line align-bottom"></i> Upgrade
                                Now</button>
                        </div>
                    </div>
                </div>
            </div>
            <div id="file-overview" class="h-100">
                <div class="d-flex h-100 flex-column">
                    <div
                        class="d-flex align-items-center pb-3 border-bottom border-bottom-dashed mb-3 gap-2">
                        <h5 class="flex-grow-1 fw-semibold mb-0">File Preview</h5>
                        <div>
                            <button type="button"
                                class="btn btn-ghost-primary btn-icon btn-sm fs-16 favourite-btn">
                                <i class="ri-star-fill align-bottom"></i>
                            </button>
                            <button type="button"
                                class="btn btn-soft-danger btn-icon btn-sm fs-16 close-btn-overview">
                                <i class="ri-close-fill align-bottom"></i>
                            </button>
                        </div>
                    </div>
    
                    <div class="pb-3 border-bottom border-bottom-dashed mb-3">
                        <div
                            class="file-details-box bg-light p-3 text-center rounded-3 border border-light mb-3">
                            <div class="display-4 file-icon">
                                <i class="ri-file-text-fill text-secondary"></i>
                            </div>
                        </div>
                        <button type="button"
                            class="btn btn-icon btn-sm btn-ghost-success float-end fs-16"><i
                                class="ri-share-forward-line"></i></button>
                        <h5 class="fs-16 mb-1 file-name">html.docx</h5>
                        <p class="text-muted mb-0 fs-12"><span class="file-size">0.3 KB</span>,
                            <span class="create-date">19 Apr, 2022</span></p>
                    </div>
                    <div>
                        <h5 class="fs-12 text-uppercase text-muted mb-3">File Description :</h5>
    
                        <div class="table-responsive">
                            <table class="table table-borderless table-nowrap table-sm">
                                <tbody>
                                    <tr>
                                        <th scope="row" style="width: 35%;">File Name :</th>
                                        <td class="file-name">html.docx</td>
                                    </tr>
                                    <tr>
                                        <th scope="row">File Type :</th>
                                        <td class="file-type">Documents</td>
                                    </tr>
                                    <tr>
                                        <th scope="row">Size :</th>
                                        <td class="file-size">0.3 KB</td>
                                    </tr>
                                    <tr>
                                        <th scope="row">Created :</th>
                                        <td class="create-date">19 Apr, 2022</td>
                                    </tr>
                                    <tr>
                                        <th scope="row">Path :</th>
                                        <td class="file-path">
                                            <div class="user-select-all text-truncate">
                                                *:\projects\src\assets\images</div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
    
                        <div>
                            <h5 class="fs-12 text-uppercase text-muted mb-3">Share Information:</h5>
                            <div class="table-responsive">
                                <table class="table table-borderless table-nowrap table-sm">
                                    <tbody>
                                        <tr>
                                            <th scope="row" style="width: 35%;">Share Name :</th>
                                            <td class="share-name">\\*\Projects</td>
                                        </tr>
                                        <tr>
                                            <th scope="row">Share Path :</th>
                                            <td class="share-path">velzon:\Documents\</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
    
                    <div class="mt-auto border-top border-top-dashed py-3">
                        <div class="hstack gap-2">
                            <button type="button" class="btn btn-soft-primary w-100"><i
                                    class="ri-download-2-line align-bottom me-1"></i>
                                Download</button>
                            <button type="button"
                                class="btn btn-soft-danger w-100 remove-file-overview"
                                data-remove-id="" data-bs-toggle="modal"
                                data-bs-target="#removeFileItemModal"><i
                                    class="ri-close-fill align-bottom me-1"></i> Delete</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

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
                <div class="alert alert-info mb-3" role="alert">
                    <i class="ri-information-line"></i> Scan this QR code to access document details
                </div>
                <div class="mb-2">
                    <strong>Document ID:</strong> <span id="qrDocId" class="text-primary">Doc-2024-001</span>
                </div>
                <div class="mb-2">
                    <strong>Document Title:</strong> <span id="qrDocTitle">Quality Management System Manual</span>
                </div>
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

@include('documents.upload_document')
@include('documents.add_folder')
@endsection

@section('js')
<script src="{{ asset('login_css/js/plugins/dataTables/datatables.min.js')}}"></script>
<script src="{{ asset('login_css/js/plugins/chosen/chosen.jquery.js') }}"></script>
<script src="{{ asset('login_css/js/plugins/sweetalert/sweetalert.min.js') }}"></script>
{{-- <script src="{{ asset('assets/js/pages/file-manager.init.js') }}"></script> --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>

<script>
    document.addEventListener("DOMContentLoaded", () => {
        const qrModalElement = document.getElementById('qrCodeModal');
        const qrModal = new bootstrap.Modal(qrModalElement);
        
        const viewQrButtons = document.querySelectorAll('.view-qr-btn');
        
        viewQrButtons.forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();

                console.log('asdasd');
                
                
                const docId = this.getAttribute('data-doc-id');
                const docTitle = this.getAttribute('data-doc-title');
                
                const docUrl = window.location.origin + '/document/' + docId;
                
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
            document.getElementById('qrPrintDocUrl').textContent = docUrl;
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
            
            setTimeout(() => {
                const printContents = document.getElementById('qrPrintTemplate').innerHTML;
                const originalContents = document.body.innerHTML;
                document.body.innerHTML = printContents;
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
    })
</script>

<script>
    function public_info(value, id) {
        console.log(value.checked);
        $.ajax({
            dataType: 'json',
            type: 'POST',
            url: '{{url("/change-public")}}',
            data: {id: id, value: value.checked},
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        }).done(function(data) {
            console.log(data);
        }).fail(function(data) {
            console.error(data);
        });
    }

    $(document).ready(function() {
        $('.cat').chosen({width: "100%"});
        
        $('.tables').DataTable({
            pageLength: 10,
            responsive: true,
            // dom: '<"html5buttons"B>lTfg<"bottom-controls"t<"info-paginate"ip>>', 
            // buttons: [
            //     {extend: 'copy'},
            //     {extend: 'csv'},
            //     {extend: 'excel', title: 'Documents'},
            //     {extend: 'pdf', title: 'Documents'},
            //     {extend: 'print',
            //      customize: function (win) {
            //         $(win.document.body).addClass('white-bg');
            //         $(win.document.body).css('font-size', '10px');
            //         $(win.document.body).find('table')
            //             .addClass('compact')
            //             .css('font-size', 'inherit');
            //     }
            //     }
            // ]
        });
    });
</script>
@endsection