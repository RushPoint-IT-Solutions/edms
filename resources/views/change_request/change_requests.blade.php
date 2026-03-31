@extends('layouts.header')

@section('css')
<style>
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

.approver-chain-list {
    display: flex;
    flex-direction: column;
}
.approver-step-row {
    display: flex;
    align-items: flex-start;
    gap: 12px;
    position: relative;
}
.approver-step-row:not(:last-child)::after {
    content: '';
    position: absolute;
    left: 15px;
    top: 36px;
    width: 1px;
    height: calc(100% - 6px);
    background: #dee2e6;
}
.approver-step-icon {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    margin-top: 2px;
    position: relative;
    z-index: 1;
    font-size: 0.8rem;
}
.approver-step-body {
    flex: 1;
    padding-bottom: 18px;
}
.step-remark-box {
    font-size: 0.78rem;
    color: #6c757d;
    margin-top: 5px;
    padding: 5px 8px;
    background: #fff8f0;
    border-left: 2px solid #fd7e14;
    border-radius: 0 4px 4px 0;
}
</style>
@endsection

@section('content')
<div class="row mb-4">
    <div class="col-12">
        @if((auth()->user()->role == "Administrator"))
        <h4 class="mb-0">Files</h4>
        @else
        <h4 class="mb-0">My files</h4>
        @endif
        <p class="text-muted mb-0">Manage and track document change requests</p>
    </div>
</div>

<div class="row g-3 mb-4 h-100">
    <div class="col-xl-3 col-md-4">
        <div class="dashboard-card pending">
            <div class="icon-circle">
                <i class="fa fa-clock-o"></i>
            </div>
            <h2 class="mb-0 font-weight-bold">{{ $forApprovalCount ?? 0 }}</h2>
            <p>For Approval</p>
        </div>
    </div>

    <div class="col-xl-3 col-md-4">
        <div class="dashboard-card declined">
            <div class="icon-circle">
                <i class="fa fa-times-circle"></i>
            </div>
            <h2 class="mb-0 font-weight-bold">{{ $declinedCount ?? 0 }}</h2>
            <p>Declined</p>
        </div>
    </div>

    <div class="col-xl-3 col-md-4">
        <div class="dashboard-card approved">
            <div class="icon-circle">
                <i class="fa fa-check-circle"></i>
            </div>
            <h2 class="mb-0 font-weight-bold">{{ $approvedCount ?? 0 }}</h2>
            <p>Approved</p>
        </div>
    </div>

    <div class="col-xl-3 col-md-4">
        <div class="dashboard-card returned">
            <div class="icon-circle">
                <i class="fa fa-undo"></i>
            </div>
            <h2 class="mb-0 font-weight-bold">{{ $returnedCount ?? 0 }}</h2>
            <p>Returned</p>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="card shadow-sm">
            <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
                @if((auth()->user()->role == "Administrator"))
                <h4 class="mb-0">Files</h4>
                @else
                <h4 class="mb-0">My files</h4>
                @endif
                <a href="{{ route('documents.create') }}" class="btn btn-first btn-sm">
                    <i class="ri-file-add-line me-2"></i>New Request Document
                </a>
                {{-- <div class="dropdown">
                    <button type="button" class="btn btn-first btn-sm" data-bs-toggle="dropdown">
                        <i class="ri-add-line"></i> New
                    </button>
                    <div class="dropdown-menu dropdown-menu-end">
                        <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#uploadRevisionModal">
                            <i class="ri-upload-2-line me-2"></i>Upload file
                        </a>
                        <a href="{{ route('documents.create') }}" class="dropdown-item">
                            <i class="ri-file-add-line me-2"></i>New Request Document
                        </a>
                    </div>
                </div> --}}
            </div>

            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
                    <div class="d-flex align-items-center gap-2 flex-wrap">
                        <div id="table-filter-control"></div>
                        <select id="statusFilter" class="form-select form-select-sm" style="width:auto;min-width:140px;">
                            <option value="">All Status</option>
                            <option value="For Approval">For Approval</option>
                            <option value="Declined">Declined</option>
                            <option value="Approved">Approved</option>
                            <option value="Draft">Draft</option>
                            <option value="Returned">Returned</option>
                        </select>
                        <div id="table-length-control"></div>
                    </div>
                    <div id="table-buttons-control"></div>
                </div>

                <div class="table-scroll-container">
                    <table class="table table-hover table-bordered" id="changeRequestsTable">
                        <thead class="table-light">
                            <tr>
                                <th>Actions</th>
                                <th>Doc&nbsp;ID</th>
                                <th>Title</th>
                                <th>Description</th>
                                <th>Category</th>
                                <th>Privacy</th>
                                <th>Revision</th>
                                <th>Requested&nbsp;By</th>
                                <th>Date&nbsp;Requested</th>
                                <th>Approvers</th>
                                <th>QR Code</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>

                <div class="bottom-controls-container">
                    <div id="table-info-control"></div>
                    <div id="table-pagination-control"></div>
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
                    <div id="qrCodeContainer" class="d-flex justify-content-center"></div>
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
    <div style="text-align: center; padding: 40px; font-family: Arial, sans-serif; max-width: 800px; margin: 0 auto;">
        <h2 style="margin-bottom: 30px; color: #333;">Document QR Code</h2>

        <div style="display: flex; justify-content: center; margin: 30px auto;">
            <div id="qrPrintCode" style="display: inline-block;"></div>
        </div>

        <div style="margin-top: 40px; text-align: center;">
            <p style="font-size: 18px; margin: 15px 0;"><strong>Document ID:</strong> <span id="qrPrintDocId"></span></p>
            <p style="font-size: 18px; margin: 15px 0;"><strong>Title:</strong> <span id="qrPrintDocTitle"></span></p>
        </div>

        <div style="margin-top: 50px; padding-top: 20px; border-top: 2px solid #ddd;">
            <p style="font-size: 12px; color: #999; margin: 10px 0;">Scan this QR code to access document details</p>
            <p style="font-size: 12px; color: #999; margin: 10px 0;">Generated on: <span id="qrPrintDate"></span></p>
        </div>
    </div>
</div>

@include('change_request.upload_revision_modal')

@endsection

@section('js')

<script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
<script>
$(document).ready(function () {

    var currentStatus = '';

    var table = $('#changeRequestsTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: '{{ route("change-requests.data") }}',
            type: 'GET',
            data: function (d) { d.status = currentStatus; },
            error: function (xhr, error, code) {
                console.log(xhr, error, code);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Failed to load data. Please refresh the page.'
                });
            }
        },
        columns: [
            { data: 'action', name: 'action', orderable: false, searchable: false },
            { data: 'doc_id', name: 'doc_id', orderable: false, searchable: false },
            { data: 'title', name: 'title' },
            { data: 'description', name: 'description' },
            { data: 'category', name: 'category' },
            { data: 'privacy', name: 'privacy' },
            { data: 'revision', name: 'revision' },
            { data: 'requested_by', name: 'requested_by', orderable: false, searchable: false },
            { data: 'created_at', name: 'created_at' },
            { data: 'approvers', name: 'approvers', orderable: false, searchable: false },
            { data: 'qr_code', name: 'qr_code', orderable: false, searchable: false },
            { data: 'status', name: 'status' },
        ],
        pageLength: 10,
        lengthMenu: [[10, 25, 50, 100], [10, 25, 50, 100]],
        responsive: true,
        dom: 'lBfrtip',
        buttons: [
            { extend: 'copy',  text: 'Copy',  titleAttr: 'Copy to clipboard', className: 'btn btn-secondary btn-sm'  },
            { extend: 'excel', text: 'Excel', title: 'Change Requests', className: 'btn btn-secondary btn-sm' }
        ],
        order: [[8, 'desc']],
        language: {
            processing: '<div style="text-align:center;"><i class="fa fa-spinner fa-spin fa-2x"></i><br><span style="margin-top:10px;display:block;">Loading...</span></div>',
            emptyTable: "No change requests found",
            zeroRecords: "No matching change requests found",
            lengthMenu: "Show _MENU_ entries",
            info: "Showing _START_ to _END_ of _TOTAL_ entries",
            infoEmpty: "Showing 0 to 0 of 0 entries",
            infoFiltered: "(filtered from _MAX_ total entries)",
            search: "Search:",
            paginate: { first: "First", last: "Last", next: "Next", previous: "Previous" }
        },
        drawCallback: function () {
            moveControls();
        },
        initComplete: function () {
            var searchInput = $('#changeRequestsTable_filter input');
            searchInput.unbind();
            var searchTimer;
            searchInput.on('input', function () {
                var val = $(this).val();
                clearTimeout(searchTimer);
                searchTimer = setTimeout(function () { table.search(val).draw(); }, 500);
            });
        }
    });

    function moveControls() {
        var searchInput = $('#changeRequestsTable_filter input');
        var searchHasFocus = searchInput.is(':focus');
        var cursorPos = searchInput[0] ? searchInput[0].selectionStart : null;

        var lengthControl = $('.dataTables_length').detach();
        $('#table-length-control').empty().append(lengthControl);

        var filterControl = $('.dataTables_filter').detach();
        $('#table-filter-control').empty().append(filterControl);

        var buttons = $('.dt-buttons').detach();
        $('#table-buttons-control').empty().append(buttons);

        var info = $('.dataTables_info').detach();
        $('#table-info-control').empty().append(info);

        var pagination = $('.dataTables_paginate').detach();
        $('#table-pagination-control').empty().append(pagination);

        if (searchHasFocus) {
            var newSearchInput = $('#changeRequestsTable_filter input');
            newSearchInput.focus();
            if (cursorPos !== null) {
                newSearchInput[0].setSelectionRange(cursorPos, cursorPos);
            }
        }
    }

    setTimeout(function () { moveControls(); }, 100);
    $(window).on('resize', function () { moveControls(); });

    $('#statusFilter').on('change', function () {
        currentStatus = $(this).val();
        table.ajax.reload();
    });

    $(document).on('click', '.view-qr-btn', function () {
        var docId    = $(this).data('doc-id');
        var docTitle = $(this).data('doc-title');
        var crId     = $(this).data('change-request-id');
        var docUrl   = window.location.origin + '/change-request/' + crId;

        $('#qrDocId').text(docId);
        $('#qrDocTitle').text(docTitle);
        $('#qrDocUrl').val(docUrl);

        $('#qrCodeContainer').empty();
        new QRCode(document.getElementById('qrCodeContainer'), {
            text: docUrl,
            width: 200,
            height: 200,
            colorDark: "#000000",
            colorLight: "#ffffff",
            correctLevel: QRCode.CorrectLevel.H
        });

        var modal = new bootstrap.Modal(document.getElementById('qrCodeModal'));
        modal.show();
    });

    $('#copyUrlBtn').on('click', function () {
        var urlInput = document.getElementById('qrDocUrl');
        urlInput.select();
        document.execCommand('copy');
        var btn = $(this);
        btn.html('<i class="ri-check-line"></i> Copied!');
        setTimeout(function () { btn.html('<i class="ri-file-copy-line"></i> Copy'); }, 2000);
    });

    $('#downloadQrBtn').on('click', function () {
        var canvas = document.querySelector('#qrCodeContainer canvas');
        if (canvas) {
            var docId = $('#qrDocId').text();
            var link  = document.createElement('a');
            link.download = 'QR_' + docId + '.png';
            link.href     = canvas.toDataURL('image/png');
            link.click();
        }
    });

    $('#printQrBtn').on('click', function () {
        var docId    = $('#qrDocId').text();
        var docTitle = $('#qrDocTitle').text();
        var docUrl   = $('#qrDocUrl').val();

        document.getElementById('qrPrintDocId').textContent    = docId;
        document.getElementById('qrPrintDocTitle').textContent = docTitle;
        document.getElementById('qrPrintDate').textContent     = new Date().toLocaleString();

        var printQrContainer = document.getElementById('qrPrintCode');
        printQrContainer.innerHTML = '';
        new QRCode(printQrContainer, {
            text: docUrl,
            width: 256,
            height: 256,
            colorDark: "#000000",
            colorLight: "#ffffff",
            correctLevel: QRCode.CorrectLevel.H
        });

        setTimeout(function () {
            var printContents = document.getElementById('qrPrintTemplate').innerHTML;
            var originalBody  = document.body.innerHTML;

            document.body.innerHTML = printContents;
            window.print();

            document.body.innerHTML = originalBody;
            location.reload();
        }, 500);
    });
});

$(document).on('click','.view-revision-status-btn',function(){
    var crId=$(this).data('change-request-id'),
        docId=$(this).data('doc-id'),
        title=$(this).data('title'),
        status=$(this).data('status'),
        category=$(this).data('category'),
        revision=$(this).data('revision'),
        dateReq=$(this).data('date-requested'),
        reqBy=$(this).data('requested-by'),
        approvers=$(this).data('approvers-json');

    $('#rsDocId').text(docId);
    $('#rsTitle').text(title);
    $('#rsCategory').text(category);
    $('#rsRevision').text('Rev. '+revision);
    $('#rsDateRequested').text(dateReq);
    $('#rsRequestedBy').text(reqBy);

    var statusMap={
        'Approved':'bg-success',
        'For Approval':'bg-primary',
        'Returned':'bg-warning text-dark',
        'Declined':'bg-danger',
        'Draft':'bg-secondary'
    };

    $('#rsStatus').html('<span class="badge '+(statusMap[status]||'bg-secondary')+'">'+status+'</span>');

    var chain='';
    $.each(approvers,function(i,approver){
        var icon,iconBg,iconBorder,badgeClass,badgeText;

        switch(approver.status){
            case 'Approved':
                icon='<i class="ri-checkbox-circle-fill text-success" style="font-size:1rem;"></i>';
                iconBg='#d1e7dd';iconBorder='#198754';
                badgeClass='bg-success';badgeText='Approved';
                break;
            case 'Returned':
                icon='<i class="ri-arrow-go-back-fill" style="font-size:1rem;color:#fd7e14;"></i>';
                iconBg='#fff3cd';iconBorder='#fd7e14';
                badgeClass='bg-warning text-dark';badgeText='Returned';
                break;
            case 'Pending':
                icon='<i class="ri-time-line" style="font-size:1rem;color:#0d6efd;"></i>';
                iconBg='#cfe2ff';iconBorder='#0d6efd';
                badgeClass='bg-primary';badgeText='Pending';
                break;
            case 'Declined':
                icon='<i class="ri-close-circle-fill text-danger" style="font-size:1rem;"></i>';
                iconBg='#f8d7da';iconBorder='#dc3545';
                badgeClass='bg-danger';badgeText='Declined';
                break;
            default:
                icon='<i class="ri-checkbox-blank-circle-line text-muted" style="font-size:1rem;"></i>';
                iconBg='#f8f9fa';iconBorder='#adb5bd';
                badgeClass='bg-secondary';badgeText='Waiting';
        }

        var isCurrentApprover=(approver.status==='Returned'||approver.status==='Pending'),
            remarkBox=(approver.remarks&&approver.status==='Returned')
                ? '<div class="step-remark-box">'+approver.remarks+'</div>'
                : '',
            dateInfo=approver.date_approved
                ? '<div class="small text-muted mt-1">'+approver.date_approved+'</div>'
                : '<div class="small text-muted mt-1">Waiting for previous approval</div>';

        chain+=`
            <div class="approver-step-row">
                <div class="approver-step-icon" style="background:${iconBg};border:1.5px solid ${iconBorder};">${icon}</div>
                <div class="approver-step-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <div class="fw-semibold" style="font-size:0.875rem;">${approver.name}</div>
                            <div class="text-muted" style="font-size:0.75rem;">
                                ${approver.role||''} · Level ${approver.level}
                                ${isCurrentApprover?' · <strong class="text-dark">Current approver</strong>':''}
                            </div>
                        </div>
                        <span class="badge ${badgeClass}" style="font-size:0.7rem;">${badgeText}</span>
                    </div>
                    ${dateInfo}
                    ${remarkBox}
                </div>
            </div>`;
    });

    $('#rsApproverChain').html(chain);
    new bootstrap.Modal(document.getElementById('revisionStatusModal')).show();
});
</script>
@endsection