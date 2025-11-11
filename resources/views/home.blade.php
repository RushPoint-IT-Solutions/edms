@extends('layouts.header')

@section('css')
{{-- <link href="{{ asset('login_css/css/plugins/c3/c3.min.css') }}" rel="stylesheet">
<link href="{{ asset('login_css/css/plugins/morris/morris-0.4.3.min.css') }}" rel="stylesheet"> --}}
<style>
    .file-card {
        position: relative;
        z-index: 1;
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

    .file-dropdown-menu {
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
    .file-dropdown-menu.show {
        display: block;
    }
    
    .file-dropdown-item {
        padding: 12px 16px;
        display: flex;
        align-items: center;
        gap: 12px;
        cursor: pointer;
        transition: background-color 0.2s;
        border: none;
        background: none;
        width: 100%;
        text-align: left;
        font-size: 0.875rem;
        color: #212529;
        position: relative;
    }
    .file-dropdown-item:hover {
        background-color: #f8f9fa;
    }
    .file-dropdown-item i {
        font-size: 1.25rem;
        width: 20px;
        text-align: center;
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
        overflow-x: auto;
        overflow-y: visible;
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

<div class="row g-4 mb-4">
    <div class="col-12 col-lg-8">
        <div class="card shadow-sm h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="fw-semibold text-dark mb-0">My Documents</h5>
                    <div class="d-flex gap-2">
                        <button class="btn btn-outline-secondary btn-sm d-inline-flex align-items-center gap-1">
                            <i class="ri-upload-line"></i> Upload
                        </button>
                        <button class="btn btn-outline-secondary btn-sm">
                            <i class="ri-list-check"></i>
                        </button>
                        <button class="btn btn-outline-secondary btn-sm">
                            <i class="ri-grid-line"></i>
                        </button>
                    </div>
                </div>

                <div class="row row-cols-2 row-cols-sm-3 row-cols-md-4 row-cols-xl-5 g-3 mb-3">
                    <a href='#'>
                    <div class="col">
                        <div class="card border file-card position-relative">
                          
                            <img src="{{asset('assets/images/book1.jpg')}}" class="card-img-top" alt="Cover of the book 'Spark'" style="height: 120px; object-fit: fit;">
                            <div class="card-body p-2 text-start">
                                <div class="docu d-flex align-items-center gap-2">
                                    <i class="ri-file-pdf-line text-danger" style="font-size: 1rem;"></i>
                                    <div class="fw-semibold text-dark text-truncate" style="font-size: 0.75rem;">Docu.pdf</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    </a>

                  
                    {{-- Loop through actual files --}}
                    {{-- @foreach($files as $file)
                    <div class="col">
                        <div class="card border file-card position-relative">
                            <div class="position-absolute top-0 end-0 m-2 more-btn">
                                <button class="btn btn-sm btn-light p-1" style="width: 24px; height: 24px; line-height: 1;">
                                    <i class="ri-more-2-fill"></i>
                                </button>
                            </div>
                            <img src="{{ $file->preview_url }}" class="card-img-top" alt="{{ $file->name }}" style="height: 120px; object-fit: cover;">
                            <div class="card-body p-2 text-start">
                                <div class="d-flex align-items-center gap-2">
                                    <i class="ri-file-{{ $file->type }}-line text-{{ $file->type == 'pdf' ? 'danger' : 'primary' }}" style="font-size: 1rem;"></i>
                                    <div class="fw-semibold text-dark text-truncate" style="font-size: 0.75rem;">{{ $file->name }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach --}}
                </div>
            </div>
        </div>
    </div>

    <div class="col-12 col-lg-4">
        <div class="card shadow-sm h-100">
            <div class="card-body">
                <h5 class="fw-semibold text-dark mb-3">Public Form</h5>
                
                <div class="d-flex flex-column gap-3">
                    <div class="d-flex align-items-center justify-content-between p-3 border rounded">
                        <div class="d-flex align-items-center gap-2">
                            <i class="ri-file-text-line" style="font-size: 1.5rem;"></i>
                            <span class="fw-medium" style="font-size: 0.875rem;">Billing Statement</span>
                        </div>
                        <button class="btn btn-sm btn-light">
                            <i class="ri-more-2-fill"></i>
                        </button>
                    </div>

                    <div class="d-flex align-items-center justify-content-between p-3 border rounded">
                        <div class="d-flex align-items-center gap-2">
                            <i class="ri-file-text-line" style="font-size: 1.5rem;"></i>
                            <span class="fw-medium" style="font-size: 0.875rem;">Billing Statement</span>
                        </div>
                        <button class="btn btn-sm btn-light">
                            <i class="ri-more-2-fill"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Documents Section -->
<div class="card shadow-sm mb-3" style="overflow: visible;">
    <div class="card-body" style="overflow: visible;">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h5 class="fw-semibold text-dark mb-0">Documents</h5>
        </div>

        <div class="row g-3 mb-4">
            <div class="col-12 col-lg-auto flex-lg-grow-1">
                <div class="position-relative">
                    <i class="ri-search-line position-absolute top-50 translate-middle-y ms-3 text-muted"></i>
                    <input type="text" placeholder="Search Document" class="form-control form-control-sm ps-5" style="min-width: 250px;">
                </div>
            </div>
            <div class="col-auto d-flex align-items-center gap-2">
                <label class="mb-0 text-nowrap" style="font-size: 0.875rem;">Sort by</label>
                <select class="form-select form-select-sm" style="min-width: 120px;">
                    <option>creation</option>
                    <option>name</option>
                    <option>date</option>
                </select>
            </div>
            <div class="col-auto d-flex align-items-center gap-2">
                <label class="mb-0 text-nowrap" style="font-size: 0.875rem;">Status</label>
                <select class="form-select form-select-sm" style="min-width: 120px;">
                    <option>Default</option>
                    <option>Active</option>
                    <option>Archived</option>
                </select>
            </div>
            <div class="col-auto d-flex align-items-center gap-2">
                <label class="mb-0 text-nowrap" style="font-size: 0.875rem;">Show entries</label>
                <select class="form-select form-select-sm" style="min-width: 120px;">
                    <option>25</option>
                    <option>50</option>
                    <option>100</option>
                </select>
            </div>
        </div>

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
                    <tr>
                        <td>
                            <div class="fw-semibold text-dark" style="font-size: 0.875rem;">Quality Management System Manual</div>
                            <small class="text-muted">Doc-2024-001</small>
                        </td>
                        <td>
                            <a href="{{ asset('document_attachments/1722316806_sample.pdf') }}" target="_blank" class="text-decoration-none d-flex align-items-center gap-2 hover-effect">
                                <i class="ri-file-pdf-line text-danger" style="font-size: 1.25rem;"></i>
                                <span style="font-size: 0.875rem;" class="text-dark">QMS_Manual.pdf</span>
                            </a>
                        </td>
                        <td>
                            <div style="font-size: 0.875rem;">John Doe</div>
                            <small class="text-muted">Nov 8, 2024</small>
                        </td>
                        <td>
                            <span class="badge bg-success" style="font-size: 0.75rem;">Active</span>
                        </td>
                        <td>
                            <button class="btn btn-sm btn-outline-primary view-qr-btn" data-doc-id="Doc-2024-001" data-doc-title="Quality Management System Manual">
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
                                        <button class="dropdown-item print-doc-btn">
                                            <i class="ri-printer-line me-2"></i>Print
                                        </button>
                                    </li>
                                    <li>
                                        <button class="dropdown-item">
                                            <i class="ri-pencil-line me-2"></i>Edit
                                        </button>
                                    </li>
                                    <li>
                                        <button class="dropdown-item">
                                            <i class="ri-delete-bin-line me-2"></i>Delete
                                        </button>
                                    </li>
                                </ul>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="d-flex justify-content-between align-items-center mt-3">
            <div class="text-muted" style="font-size: 0.875rem;">
                Showing 1 to 1 of 1 entries
            </div>
            <nav>
                <ul class="pagination pagination-sm mb-0">
                    <li class="page-item disabled">
                        <a class="page-link" href="#" tabindex="-1">Previous</a>
                    </li>
                    <li class="page-item active"><a class="page-link" href="#">1</a></li>
                    <li class="page-item disabled">
                        <a class="page-link" href="#">Next</a>
                    </li>
                </ul>
            </nav>
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

<div id="qrPrintTemplate" style="display: none;">
    <div style="text-align: center; padding: 40px; font-family: Arial, sans-serif;">
        <h2 style="margin-bottom: 20px;">Document QR Code</h2>
        <div id="qrPrintCode" style="margin: 30px auto;"></div>
        <div style="margin-top: 30px;">
            <p style="font-size: 18px; margin: 10px 0;"><strong>Document ID:</strong> <span id="qrPrintDocId"></span></p>
            <p style="font-size: 18px; margin: 10px 0;"><strong>Title:</strong> <span id="qrPrintDocTitle"></span></p>
            <p style="font-size: 14px; margin: 20px 0; color: #666;"><strong>URL:</strong> <span id="qrPrintDocUrl"></span></p>
        </div>
        <div style="margin-top: 40px; padding-top: 20px; border-top: 1px solid #ddd;">
            <p style="font-size: 12px; color: #999;">Scan this QR code to access document details</p>
            <p style="font-size: 12px; color: #999;">Generated on: <span id="qrPrintDate"></span></p>
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