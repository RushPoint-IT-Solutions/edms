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

.publish-option-card {
    border: 2px solid #dee2e6;
    border-radius: 10px;
    padding: 16px;
    cursor: pointer;
    transition: all 0.2s ease;
    background: #fff;
}
.publish-option-card:hover {
    border-color: #0d6efd;
    background: #f0f6ff;
}
.publish-option-card.selected {
    border-color: #0d6efd;
    background: #e7f0ff;
    box-shadow: 0 0 0 3px rgba(13,110,253,.15);
}
.publish-option-card .option-icon {
    width: 44px;
    height: 44px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.25rem;
    margin-bottom: 10px;
}
.publish-option-card h6 {
    font-weight: 700;
    margin-bottom: 4px;
}
.publish-option-card p {
    font-size: 0.78rem;
    color: #6c757d;
    margin: 0;
}
.office-tag {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    background: #e7f0ff;
    color: #0d6efd;
    border-radius: 20px;
    padding: 3px 10px;
    font-size: 0.75rem;
    font-weight: 500;
    cursor: pointer;
    border: 1px solid #b6d0fb;
    transition: background 0.15s;
}
.office-tag:hover {
    background: #cfe2ff;
}
.office-tag .remove-office {
    font-size: 0.85rem;
    color: #0d6efd;
    margin-left: 2px;
}
#officeSearch {
    border-radius: 8px;
}
.office-list-item {
    cursor: pointer;
    border-radius: 6px;
    padding: 6px 10px;
    font-size: 0.82rem;
    transition: background 0.12s;
}
.office-list-item:hover {
    background: #f0f6ff;
}
.office-list-item.selected-office {
    background: #e7f0ff;
    color: #0d6efd;
    font-weight: 600;
}
.publish-status-pill {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    font-size: 0.72rem;
    font-weight: 600;
    border-radius: 20px;
    padding: 2px 9px;
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

<div class="modal fade" id="publishDocModal" tabindex="-1" aria-labelledby="publishDocModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header border-bottom-0 pb-0">
                <div>
                    <h5 class="modal-title fw-bold" id="publishDocModalLabel">
                        <i class="ri-send-plane-line me-2 text-success"></i>Publish Document
                    </h5>
                    <p class="text-muted mb-0" style="font-size:0.82rem;" id="publishDocSubtitle"></p>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body pt-3">

                <input type="hidden" id="publishChangeRequestId">
                <input type="hidden" id="publishDocumentId">

                <div class="mb-4">
                    <label class="form-label fw-semibold mb-2">
                        <span class="badge bg-primary me-1" style="font-size:0.7rem;">1</span>
                        When should this document be published?
                    </label>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="publish-option-card selected" id="publishOptionImmediate" onclick="selectPublishOption('immediate')">
                                <div class="option-icon bg-success bg-opacity-10 text-success">
                                    <i class="ri-flashlight-line"></i>
                                </div>
                                <h6>Publish Now</h6>
                                <p>Make the document publicly visible immediately in the Monitoring module.</p>
                                <div class="mt-2">
                                    <span class="publish-status-pill" style="background:#d1fae5;color:#065f46;">
                                        <i class="ri-check-line"></i> Effective today
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="publish-option-card" id="publishOptionScheduled" onclick="selectPublishOption('scheduled')">
                                <div class="option-icon bg-primary bg-opacity-10 text-primary">
                                    <i class="ri-calendar-schedule-line"></i>
                                </div>
                                <h6>Schedule for Later</h6>
                                <p>Pick a future date — the document will auto-publish overnight on that date.</p>
                                <div class="mt-2">
                                    <span class="publish-status-pill" style="background:#e0e7ff;color:#3730a3;">
                                        <i class="ri-time-line"></i> Set a date below
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div id="scheduledDateWrap" class="mt-3 d-none">
                        <label class="form-label fw-semibold" for="publishDate">Publish Date</label>
                        <input type="date"
                               id="publishDate"
                               class="form-control"
                               min="{{ date('Y-m-d', strtotime('+1 day')) }}">
                        <div class="form-text">The document will become visible at the start of this day.</div>
                    </div>
                </div>

                <hr class="my-3">

                <div class="mb-2">
                    <label class="form-label fw-semibold mb-1">
                        <span class="badge bg-primary me-1" style="font-size:0.7rem;">2</span>
                        Target Offices <span class="text-muted fw-normal">(optional)</span>
                    </label>
                    <p class="text-muted mb-2" style="font-size:0.8rem;">
                        Leave empty to make the document visible to <strong>all users</strong>.
                        Select specific offices to restrict visibility to only users in those offices.
                    </p>

                    <div id="selectedOfficesTags" class="d-flex flex-wrap gap-2 mb-2 min-height: 28px;"></div>

                    <div class="position-relative mb-2">
                        <i class="ri-search-line position-absolute top-50 translate-middle-y ms-3 text-muted" style="z-index:2;"></i>
                        <input type="text"
                               id="officeSearch"
                               class="form-control form-control-sm ps-5"
                               placeholder="Search offices..."
                               autocomplete="off">
                    </div>

                    <div id="officeListWrap"
                         style="max-height:180px;overflow-y:auto;border:1px solid #dee2e6;border-radius:8px;padding:6px;">
                        <div class="text-center text-muted py-3" id="officeListLoading">
                            <i class="fa fa-spinner fa-spin"></i> Loading offices...
                        </div>
                        <div id="officeList"></div>
                    </div>

                    <div class="form-text mt-1">
                        <i class="ri-information-line"></i>
                        Users are matched to offices via their assigned department.
                    </div>
                </div>

            </div>

            <div class="modal-footer border-top-0">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-success px-4" id="publishConfirmBtn" onclick="submitPublish()">
                    <i class="ri-send-plane-line me-1"></i>
                    <span id="publishBtnLabel">Publish Now</span>
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
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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
                Swal.fire({ icon: 'error', title: 'Error', text: 'Failed to load data. Please refresh the page.' });
            }
        },
        columns: [
            { data: 'action',       name: 'action',       orderable: false, searchable: false },
            { data: 'doc_id',       name: 'doc_id',       orderable: false, searchable: false },
            { data: 'title',        name: 'title' },
            { data: 'description',  name: 'description' },
            { data: 'category',     name: 'category' },
            { data: 'privacy',      name: 'privacy' },
            { data: 'revision',     name: 'revision' },
            { data: 'requested_by', name: 'requested_by', orderable: false, searchable: false },
            { data: 'created_at',   name: 'created_at' },
            { data: 'approvers',    name: 'approvers',    orderable: false, searchable: false },
            { data: 'qr_code',      name: 'qr_code',      orderable: false, searchable: false },
            { data: 'status',       name: 'status' },
        ],
        pageLength: 10,
        lengthMenu: [[10, 25, 50, 100], [10, 25, 50, 100]],
        responsive: true,
        dom: 'lBfrtip',
        buttons: [
            { extend: 'copy',  text: 'Copy',  titleAttr: 'Copy to clipboard', className: 'btn btn-secondary btn-sm' },
            { extend: 'excel', text: 'Excel', title: 'Change Requests',        className: 'btn btn-secondary btn-sm' }
        ],
        order: [[8, 'desc']],
        language: {
            processing:  '<div style="text-align:center;"><i class="fa fa-spinner fa-spin fa-2x"></i><br><span style="margin-top:10px;display:block;">Loading...</span></div>',
            emptyTable:  "No change requests found",
            zeroRecords: "No matching change requests found",
            lengthMenu:  "Show _MENU_ entries",
            info:        "Showing _START_ to _END_ of _TOTAL_ entries",
            infoEmpty:   "Showing 0 to 0 of 0 entries",
            infoFiltered:"(filtered from _MAX_ total entries)",
            search:      "Search:",
            paginate:    { first: "First", last: "Last", next: "Next", previous: "Previous" }
        },
        drawCallback: function () { moveControls(); },
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

        $('#table-length-control').empty().append($('.dataTables_length').detach());
        $('#table-filter-control').empty().append($('.dataTables_filter').detach());
        $('#table-buttons-control').empty().append($('.dt-buttons').detach());
        $('#table-info-control').empty().append($('.dataTables_info').detach());
        $('#table-pagination-control').empty().append($('.dataTables_paginate').detach());

        if (searchHasFocus) {
            var newSearchInput = $('#changeRequestsTable_filter input');
            newSearchInput.focus();
            if (cursorPos !== null) newSearchInput[0].setSelectionRange(cursorPos, cursorPos);
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
            text: docUrl, width: 200, height: 200,
            colorDark: "#000000", colorLight: "#ffffff",
            correctLevel: QRCode.CorrectLevel.H
        });

        new bootstrap.Modal(document.getElementById('qrCodeModal')).show();
    });

    $('#copyUrlBtn').on('click', function () {
        document.getElementById('qrDocUrl').select();
        document.execCommand('copy');
        var btn = $(this);
        btn.html('<i class="ri-check-line"></i> Copied!');
        setTimeout(function () { btn.html('<i class="ri-file-copy-line"></i> Copy'); }, 2000);
    });

    $('#downloadQrBtn').on('click', function () {
        var canvas = document.querySelector('#qrCodeContainer canvas');
        if (canvas) {
            var link = document.createElement('a');
            link.download = 'QR_' + $('#qrDocId').text() + '.png';
            link.href = canvas.toDataURL('image/png');
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
            text: docUrl, width: 256, height: 256,
            colorDark: "#000000", colorLight: "#ffffff",
            correctLevel: QRCode.CorrectLevel.H
        });

        setTimeout(function () {
            var printContents = document.getElementById('qrPrintTemplate').innerHTML;
            document.body.innerHTML = printContents;
            window.print();
            document.body.innerHTML = printContents;
            location.reload();
        }, 500);
    });
});

$(document).on('click', '.view-revision-status-btn', function () {
    var crId       = $(this).data('change-request-id'),
        docId      = $(this).data('doc-id'),
        title      = $(this).data('title'),
        status     = $(this).data('status'),
        category   = $(this).data('category'),
        revision   = $(this).data('revision'),
        dateReq    = $(this).data('date-requested'),
        reqBy      = $(this).data('requested-by'),
        approvers  = $(this).data('approvers-json');

    $('#rsDocId').text(docId);
    $('#rsTitle').text(title);
    $('#rsCategory').text(category);
    $('#rsRevision').text('Rev. ' + revision);
    $('#rsDateRequested').text(dateReq);
    $('#rsRequestedBy').text(reqBy);

    var statusMap = {
        'Approved'   : 'bg-success',
        'For Approval': 'bg-primary',
        'Returned'   : 'bg-warning text-dark',
        'Declined'   : 'bg-danger',
        'Draft'      : 'bg-secondary'
    };
    $('#rsStatus').html('<span class="badge ' + (statusMap[status] || 'bg-secondary') + '">' + status + '</span>');

    var chain = '';
    $.each(approvers, function (i, approver) {
        var icon, iconBg, iconBorder, badgeClass, badgeText;
        switch (approver.status) {
            case 'Approved':
                icon='<i class="ri-checkbox-circle-fill text-success" style="font-size:1rem;"></i>';
                iconBg='#d1e7dd'; iconBorder='#198754'; badgeClass='bg-success'; badgeText='Approved'; break;
            case 'Returned':
                icon='<i class="ri-arrow-go-back-fill" style="font-size:1rem;color:#fd7e14;"></i>';
                iconBg='#fff3cd'; iconBorder='#fd7e14'; badgeClass='bg-warning text-dark'; badgeText='Returned'; break;
            case 'Pending':
                icon='<i class="ri-time-line" style="font-size:1rem;color:#0d6efd;"></i>';
                iconBg='#cfe2ff'; iconBorder='#0d6efd'; badgeClass='bg-primary'; badgeText='Pending'; break;
            case 'Declined':
                icon='<i class="ri-close-circle-fill text-danger" style="font-size:1rem;"></i>';
                iconBg='#f8d7da'; iconBorder='#dc3545'; badgeClass='bg-danger'; badgeText='Declined'; break;
            default:
                icon='<i class="ri-checkbox-blank-circle-line text-muted" style="font-size:1rem;"></i>';
                iconBg='#f8f9fa'; iconBorder='#adb5bd'; badgeClass='bg-secondary'; badgeText='Waiting';
        }

        var isCurrentApprover = (approver.status === 'Returned' || approver.status === 'Pending');
        var remarkBox = (approver.remarks && approver.status === 'Returned')
            ? '<div class="step-remark-box">' + approver.remarks + '</div>' : '';
        var dateInfo = approver.date_approved
            ? '<div class="small text-muted mt-1">' + approver.date_approved + '</div>'
            : '<div class="small text-muted mt-1">Waiting for previous approval</div>';

        chain += `
            <div class="approver-step-row">
                <div class="approver-step-icon" style="background:${iconBg};border:1.5px solid ${iconBorder};">${icon}</div>
                <div class="approver-step-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <div class="fw-semibold" style="font-size:0.875rem;">${approver.name}</div>
                            <div class="text-muted" style="font-size:0.75rem;">
                                ${approver.role || ''} · Level ${approver.level}
                                ${isCurrentApprover ? ' · <strong class="text-dark">Current approver</strong>' : ''}
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

var _publishOffices = [];
var _selectedOfficeIds = [];
var _publishType = 'immediate';

$(document).on('click', '.publish-doc-btn', function (e) {
    e.preventDefault();

    var crId = $(this).data('change-request-id');
    var docId = $(this).data('document-id');
    var docTitle = $(this).data('doc-title');
    var isPublished = $(this).data('is-published') == '1';

    _selectedOfficeIds = [];
    _publishType = 'immediate';
    selectPublishOption('immediate');
    $('#publishDate').val('');
    $('#publishChangeRequestId').val(crId);
    $('#publishDocumentId').val(docId);
    $('#publishDocSubtitle').text(docTitle);
    renderSelectedTags();

    loadOffices();

    new bootstrap.Modal(document.getElementById('publishDocModal')).show();
});

function selectPublishOption(type) {
    _publishType = type;

    if (type === 'immediate') {
        $('#publishOptionImmediate').addClass('selected');
        $('#publishOptionScheduled').removeClass('selected');
        $('#scheduledDateWrap').addClass('d-none');
        $('#publishBtnLabel').text('Publish Now');
    } else {
        $('#publishOptionScheduled').addClass('selected');
        $('#publishOptionImmediate').removeClass('selected');
        $('#scheduledDateWrap').removeClass('d-none');
        $('#publishBtnLabel').text('Schedule Publish');
    }
}

/** Load all offices from server (once) */
function loadOffices() {
    if (_publishOffices.length > 0) {
        renderOfficeList(_publishOffices, '');
        return;
    }

    $('#officeListLoading').show();
    $('#officeList').html('');

    $.get('{{ route("change-request.offices") }}', function (data) {
        _publishOffices = data;
        $('#officeListLoading').hide();
        renderOfficeList(_publishOffices, '');
    }).fail(function () {
        $('#officeListLoading').hide();
        $('#officeList').html('<div class="text-danger text-center py-2" style="font-size:0.8rem;">Failed to load offices.</div>');
    });
}

/** Render filtered office list */
function renderOfficeList(offices, search) {
    var filtered = search
        ? offices.filter(function (o) {
            return o.name.toLowerCase().includes(search.toLowerCase()) ||
                   (o.code && o.code.toLowerCase().includes(search.toLowerCase()));
          })
        : offices;

    if (filtered.length === 0) {
        $('#officeList').html('<div class="text-muted text-center py-2" style="font-size:0.8rem;">No offices found.</div>');
        return;
    }

    var html = '';
    filtered.forEach(function (o) {
        var isSelected = _selectedOfficeIds.includes(o.id);
        html += '<div class="office-list-item ' + (isSelected ? 'selected-office' : '') + '" data-office-id="' + o.id + '" data-office-name="' + o.name + '" onclick="toggleOffice(' + o.id + ', \'' + o.name.replace(/'/g,"\'") + '\')">'
                + (isSelected ? '<i class="ri-checkbox-circle-fill me-2 text-primary"></i>' : '<i class="ri-checkbox-blank-circle-line me-2 text-muted"></i>')
                + o.name
                + (o.code ? ' <span class="text-muted" style="font-size:0.75rem;">(' + o.code + ')</span>' : '')
                + '</div>';
    });
    $('#officeList').html(html);
}

/** Toggle office selection */
function toggleOffice(id, name) {
    var idx = _selectedOfficeIds.indexOf(id);
    if (idx === -1) {
        _selectedOfficeIds.push(id);
    } else {
        _selectedOfficeIds.splice(idx, 1);
    }
    renderSelectedTags();
    renderOfficeList(_publishOffices, $('#officeSearch').val());
}

/** Render selected office tags above the list */
function renderSelectedTags() {
    var container = $('#selectedOfficesTags');
    container.empty();

    if (_selectedOfficeIds.length === 0) {
        container.append('<span class="text-muted" style="font-size:0.78rem;"><i class="ri-global-line me-1"></i>All users (no restriction)</span>');
        return;
    }

    _selectedOfficeIds.forEach(function (id) {
        var office = _publishOffices.find(function (o) { return o.id === id; });
        if (!office) return;
        container.append(
            '<span class="office-tag" onclick="toggleOffice(' + id + ', \'' + office.name.replace(/'/g,"\'") + '\')">'
            + '<i class="ri-building-line"></i>'
            + office.name
            + '<span class="remove-office">&times;</span>'
            + '</span>'
        );
    });
}

/** Live search in office list */
$('#officeSearch').on('input', function () {
    renderOfficeList(_publishOffices, $(this).val());
});

/** Submit the publish form */
function submitPublish() {
    var crId     = $('#publishChangeRequestId').val();
    var publishType = _publishType;
    var publishDate = $('#publishDate').val();

    if (publishType === 'scheduled' && !publishDate) {
        Swal.fire({ icon: 'warning', title: 'Date required', text: 'Please select a publish date.', confirmButtonColor: '#0d6efd' });
        return;
    }

    var $btn = $('#publishConfirmBtn');
    $btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin me-1"></i> Processing...');

    $.ajax({
        url: '{{ route("change-request.publish") }}',
        method: 'POST',
        data: (function () {
            var payload = {
                _token:            '{{ csrf_token() }}',
                change_request_id: crId,
                publish_type:      publishType,
                office_ids:        _selectedOfficeIds
            };
            if (publishDate) payload.publish_date = publishDate;
            return payload;
        })(),
        success: function (res) {
            bootstrap.Modal.getInstance(document.getElementById('publishDocModal')).hide();
            Swal.fire({
                icon: 'success',
                title: 'Done!',
                text: res.message,
                confirmButtonColor: '#198754'
            }).then(function () {
                $('#changeRequestsTable').DataTable().ajax.reload(null, false);
            });
        },
        error: function (xhr) {
            var msg = 'An error occurred. Please try again.';
            if (xhr.responseJSON) {
                if (xhr.responseJSON.errors) {
                    var fieldErrors = Object.values(xhr.responseJSON.errors).flat().join('\n');
                    msg = fieldErrors;
                } else if (xhr.responseJSON.message) {
                    msg = xhr.responseJSON.message;
                }
            }
            Swal.fire({ icon: 'error', title: 'Validation Error', text: msg, confirmButtonColor: '#dc3545' });
        },
        complete: function () {
            $btn.prop('disabled', false).html('<i class="ri-send-plane-line me-1"></i><span id="publishBtnLabel">' + (_publishType === 'immediate' ? 'Publish Now' : 'Schedule Publish') + '</span>');
        }
    });
}
</script>
@endsection