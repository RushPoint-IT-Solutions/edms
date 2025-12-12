@extends('layouts.header')

@section('css')
<link href="{{ asset('login_css/css/plugins/chosen/bootstrap-chosen.css') }}" rel="stylesheet">
<link href="{{ asset('login_css/css/plugins/sweetalert/sweetalert.css') }}" rel="stylesheet">

<style>
    .page-header {
        background: white;
        padding: 20px;
        border-radius: 10px;
        margin-bottom: 20px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.05);
    }

    .page-title {
        font-size: 24px;
        font-weight: 600;
        color: #2c3e50;
        margin: 0;
    }

    .folders-section,
    .files-section {
        background: white;
        padding: 25px;
        border-radius: 10px;
        margin-bottom: 20px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.05);
    }

    .section-title {
        font-size: 18px;
        font-weight: 600;
        color: #2c3e50;
        margin: 0;
    }

    .btn-create {
        background: #10b981;
        color: white;
        border: none;
        padding: 10px 20px;
        border-radius: 6px;
        font-size: 14px;
        cursor: pointer;
        transition: all 0.3s;
    }

    .btn-create:hover {
        background: #059669;
        color: white;
    }

    .filter-select {
        padding: 8px 12px;
        border: 1px solid #dee2e6;
        border-radius: 6px;
        min-width: 150px;
        font-size: 14px;
    }

    .folder-card {
        background: #f8f9fa;
        border: 1px solid #e9ecef;
        border-radius: 8px;
        padding: 20px;
        transition: all 0.3s;
        cursor: pointer;
        height: 100%;
    }

    .folder-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    }

    .folder-icon {
        font-size: 48px;
        color: #f59e0b;
        margin-bottom: 10px;
    }

    .folder-name {
        font-size: 15px;
        font-weight: 600;
        color: #2c3e50;
        margin: 0 0 8px 0;
    }

    .folder-info {
        display: flex;
        justify-content: space-between;
        font-size: 12px;
        color: #6c757d;
    }

    .control-label {
        font-size: 14px;
        color: #495057;
        margin: 0;
    }

    .control-input {
        padding: 8px 12px;
        border: 1px solid #dee2e6;
        border-radius: 6px;
        font-size: 14px;
        min-width: 80px;
    }

    .data-table {
        width: 100%;
        border-collapse: collapse;
    }

    .data-table thead th {
        background: #f8f9fa;
        padding: 12px;
        text-align: left;
        font-size: 13px;
        font-weight: 600;
        color: #495057;
        border-bottom: 2px solid #8B0000;
        white-space: nowrap;
    }

    .data-table tbody td {
        padding: 12px;
        border-bottom: 1px solid #e9ecef;
        font-size: 14px;
        vertical-align: middle;
    }

    .data-table tbody tr:hover {
        background: #f8f9fa;
    }

    .btn-view-qr {
        padding: 6px 12px;
        border-radius: 4px;
        font-size: 13px;
        border: 1px solid #2196F3;
        background: white;
        color: #2196F3;
        cursor: pointer;
        transition: all 0.3s;
        display: inline-flex;
        align-items: center;
        gap: 4px;
    }

    .btn-view-qr:hover {
        background: #2196F3;
        color: white;
    }

    .overview-card {
        background: white;
        border-radius: 10px;
        padding: 25px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        position: sticky;
        top: 20px;
    }

    .overview-header {
        padding-bottom: 15px;
        border-bottom: 2px dashed #e9ecef;
        margin-bottom: 20px;
    }

    .overview-title {
        font-size: 18px;
        font-weight: 600;
        color: #2c3e50;
        margin: 0;
    }

    .storage-item {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 15px 0;
        border-bottom: 1px solid #f0f0f0;
    }

    .storage-item:last-child {
        border-bottom: none;
    }

    .storage-icon {
        width: 40px;
        height: 40px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 18px;
        flex-shrink: 0;
    }

    .storage-icon.documents {
        background: #e8eaf6;
        color: #5c6bc0;
    }

    .storage-icon.media {
        background: #e8f5e9;
        color: #66bb6a;
    }

    .storage-icon.projects {
        background: #fff3e0;
        color: #ffa726;
    }

    .storage-icon.others {
        background: #e3f2fd;
        color: #42a5f5;
    }

    .storage-details {
        flex: 1;
    }

    .storage-name {
        font-size: 15px;
        font-weight: 600;
        color: #2c3e50;
        margin: 0 0 4px 0;
    }

    .storage-count {
        font-size: 12px;
        color: #6c757d;
        margin: 0;
    }

    .storage-size {
        font-weight: 600;
        color: #2c3e50;
        font-size: 14px;
    }

    .upgrade-alert {
        background: #8B0000;
        border-radius: 8px;
        padding: 20px;
        margin-top: 20px;
        color: white !important;
    }

    .upgrade-alert h5 {
        font-size: 16px;
        font-weight: 600;
        margin: 0 0 8px 0;
        color: white !important;
    }

    .upgrade-alert p {
        font-size: 13px;
        margin: 0 0 12px 0;
        opacity: 0.9;
    }

    .btn-upgrade {
        background: white;
        color: #8B0000;
        border: none;
        padding: 8px 16px;
        border-radius: 6px;
        font-size: 13px;
        font-weight: 600;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }

    .btn-upgrade:hover {
        background: #f5f5f5;
    }

    .dataTables_wrapper .dataTables_length,
    .dataTables_wrapper .dataTables_filter,
    .dataTables_wrapper .dataTables_info,
    .dataTables_wrapper .dataTables_paginate {
        display: none !important;
    }

    .dataTables_wrapper {
        padding-top: 0;
    }
</style>
@endsection

@section('content')
<div class="container-fluid py-4">
    <div class="row g-3">
        <!-- Main Content Area -->
        <div class="col-12 col-xl-12">
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
            
            <!-- Page Header -->
            <div class="page-header">
                <h1 class="page-title">Documents</h1>
            </div>

            <!-- Folders Section -->
            <div class="folders-section">
                <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">
                    <h2 class="section-title">Folders</h2>
                    <div class="d-flex gap-2 align-items-center">
                        <select class="filter-select form-select" data-choices data-choices-search-false name="choices-single-default" id="file-type">
                            <option value="">File Type</option>
                            <option value="All" selected>All</option>
                            <option value="Video">Video</option>
                            <option value="Images">Images</option>
                            <option value="Music">Music</option>
                            <option value="Documents">Documents</option>
                        </select>
                        <button class="btn-create" data-bs-toggle="modal" data-bs-target="#createFolderModal" style="width:280px;">
                            <i class="ri-add-line"></i> Create Folders
                        </button>
                    </div>
                </div>

                <div class="row g-3" id="folderlist-data">
                    @foreach ($document_folders as $folder)
                    <div class="col-12 col-sm-6 col-md-4 col-lg-3">
                        <div class="folder-card">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <input class="form-check-input" type="checkbox">
                                <div class="dropdown">
                                    <button class="btn btn-sm" type="button" data-bs-toggle="dropdown">
                                        <i class="ri-more-2-fill"></i>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end">
                                        <li><a class="dropdown-item" href="{{ url('documents/folder/'.$folder->id) }}">Open</a></li>
                                        <li><a class="dropdown-item" href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#renameFolderModal{{ $folder->id }}">Rename</a></li>
                                        <li>
                                            <form action="{{ url('documents/delete-folder/'.$folder->id) }}" method="POST">
                                                @csrf
                                                <button class="dropdown-item text-danger" type="submit">Delete</button>
                                            </form>
                                        </li>
                                    </ul>
                                </div>
                            </div>

                            <div class="text-center">
                                <div class="folder-icon">
                                    <i class="ri-folder-2-fill"></i>
                                </div>
                                <h6 class="folder-name">{{ $folder->name }}</h6>
                                <div class="folder-info">
                                    <span><b>{{ count($folder->document) }}</b> Files</span>
                                    <span><b>0</b>GB</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- Files Section -->
            <div class="files-section">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2 class="section-title">Recent Files</h2>
                    <button class="btn-create" data-bs-toggle="modal" data-bs-target="#uploadDocument">
                        <i class="ri-add-line"></i> Create File
                    </button>
                </div>

                <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-3">
                    <div class="d-flex align-items-center gap-2">
                        <span class="control-label">Show</span>
                        <select class="control-input form-select form-select-sm" id="entriesPerPage" style="width: auto;">
                            <option value="10">10</option>
                            <option value="25">25</option>
                            <option value="50">50</option>
                            <option value="100">100</option>
                        </select>
                        <span class="control-label">entries</span>
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        <span class="control-label">Search:</span>
                        <input type="text" class="control-input form-control form-control-sm" id="tableSearch" placeholder="" style="width: auto; min-width: 200px;">
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="data-table tables table">
                        <thead>
                            <tr>
                                <th>Actions</th>
                                <th>Name</th>
                                <th>File Item</th>
                                <th>File Size</th>
                                <th>Recent Date</th>
                                <th>Tags</th>
                                <th>QR Code</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($documents->sortByDesc('id') as $document)
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
                                                    <a class="dropdown-item" href="{{ url('documents/view-document/'.$document->id) }}" target="_blank">
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
                                        @foreach ($document->document_tags as $tag)
                                            <span class="badge bg-primary">{{ $tag->name }}</span>
                                        @endforeach
                                    </td>
                                    <td>
                                        <button class="btn-view-qr view-qr-btn" data-doc-id="{{ $document->control_code }}" data-doc-title="{{ $document->title }}">
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

        <!-- Overview Sidebar -->
        {{-- <div class="col-12 col-xl-3">
            <div class="overview-card">
                <div class="overview-header">
                    <h5 class="overview-title">Overview</h5>
                </div>

                <div class="mb-4">
                    <div id="simple_dount_chart"
                        data-colors='["--vz-info", "--vz-danger", "--vz-primary", "--vz-success"]'
                        class="apex-charts" dir="ltr"></div>
                </div>

                <div>
                    <div class="storage-item">
                        <div class="storage-icon documents">
                            <i class="ri-file-text-line"></i>
                        </div>
                        <div class="storage-details">
                            <h5 class="storage-name">Documents</h5>
                            <p class="storage-count">2348 files</p>
                        </div>
                        <div class="storage-size">27.01 GB</div>
                    </div>

                    <div class="storage-item">
                        <div class="storage-icon media">
                            <i class="ri-gallery-line"></i>
                        </div>
                        <div class="storage-details">
                            <h5 class="storage-name">Media</h5>
                            <p class="storage-count">12480 files</p>
                        </div>
                        <div class="storage-size">20.87 GB</div>
                    </div>

                    <div class="storage-item">
                        <div class="storage-icon projects">
                            <i class="ri-folder-2-line"></i>
                        </div>
                        <div class="storage-details">
                            <h5 class="storage-name">Projects</h5>
                            <p class="storage-count">349 files</p>
                        </div>
                        <div class="storage-size">4.10 GB</div>
                    </div>

                    <div class="storage-item">
                        <div class="storage-icon others">
                            <i class="ri-error-warning-line"></i>
                        </div>
                        <div class="storage-details">
                            <h5 class="storage-name">Others</h5>
                            <p class="storage-count">9873 files</p>
                        </div>
                        <div class="storage-size">33.54 GB</div>
                    </div>
                </div>

                <div class="upgrade-alert">
                    <h5><i class="ri-cloud-line"></i> Upgrade to Pro</h5>
                    <p>Get more space for your documents...</p>
                    <button class="btn-upgrade">
                        <i class="ri-upload-cloud-line"></i> Upgrade Now
                    </button>
                </div>
            </div>
        </div> --}}
    </div>
</div>

@foreach ($documents->sortByDesc('id') as $document)
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
                    <svg class="barcode"
                        jsbarcode-format="Code39"
                        jsbarcode-value="{{ $document->control_code }}"
                        jsbarcode-textmargin="0"
                        jsbarcode-fontoptions="bold"
                        jsbarcode-displayvalue="false"
                        >
                    </svg>
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
@endforeach

@include('documents.upload_document')
@include('documents.add_folder')
@foreach ($document_folders as $folder)
    @include('documents.rename_folder')
@endforeach
@endsection

@section('js')
<script src="{{ asset('login_css/js/plugins/dataTables/datatables.min.js')}}"></script>
<script src="{{ asset('login_css/js/plugins/chosen/chosen.jquery.js') }}"></script>
<script src="{{ asset('login_css/js/plugins/sweetalert/sweetalert.min.js') }}"></script>
{{-- <script src="{{ asset('assets/js/pages/file-manager.init.js') }}"></script> --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
<script src="{{ asset('barcode/JsBarcode.all.min.js') }}"></script>

<script>
    JsBarcode(".barcode").init();

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

    function deleteFolder() {
        event.preventDefault()
        document.getElementById('deleteFolderForm').submit()
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

        // Custom search functionality
        $('#tableSearch').on('keyup', function() {
            $('.tables').DataTable().search(this.value).draw();
        });

        // Custom entries per page
        $('#entriesPerPage').on('change', function() {
            $('.tables').DataTable().page.len(this.value).draw();
        });
    });
</script>
@endsection