@extends('layouts.header')

@section('css')
<style>
.approvers-chain {
    min-width: 160px;
}

.approver-step {
    padding: 2px 0;
}

#changeApprovalTable td:last-child {
    min-width: 180px;
}
</style>
@endsection

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <h4 class="mb-0">Request Approvals</h4>
        <p class="text-muted mb-0">Manage copy and change requests</p>
    </div>
</div>

<div class="row g-3 mb-4 h-100">
    <div class="col-xl-4 col-md-4">
        <div class="dashboard-card pending">
            <div class="icon-circle"><i class="fa fa-clock-o"></i></div>
            <h2 class="mb-0 font-weight-bold">
                {{ count($change_for_approvals->where('status','Pending')) }}
            </h2>
            <p>For Approval</p>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12 mb-4">
        <div class="card shadow-sm">
            <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
                <h5 class="mb-0">For Approval</h5>
            </div>
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
                    <div class="d-flex align-items-center gap-2 flex-wrap">
                        <div id="change-filter-control"></div>
                        <div id="change-length-control"></div>
                    </div>
                    <div id="change-buttons-control"></div>
                </div>
                <div class="table-scroll-container">
                    <table class="table table-hover table-bordered" id="changeApprovalTable">
                        <thead class="table-light">
                            <tr>
                                <th>Actions</th>
                                <th>Reference</th>
                                <th>Date</th>
                                <th>Title</th>
                                <th>Requested&nbsp;By</th>
                                {{-- <th>Type</th> --}}
                                <th>Revision</th>
                                <th>Approvers</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
                <div class="bottom-controls-container">
                    <div id="change-info-control"></div>
                    <div id="change-pagination-control"></div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="sharedConfirmPasswordModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header border-bottom">
                <h5 class="modal-title mb-3"><i class="ri-lock-line me-2"></i>Confirm Password</h5>
                <button type="button" class="btn-close mb-3" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="signChangeRequestId">
                <div class="mb-3">
                    <label class="form-label">Enter your password to proceed</label>
                    <input type="password" id="signPassword" class="form-control" placeholder="Password">
                </div>
                <div id="signError" class="text-danger small d-none">Incorrect password. Please try again.</div>
            </div>
            <div class="modal-footer border-top">
                <button type="button" class="btn btn-secondary mt-3" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary mt-3" id="signConfirmBtn">
                    <i class="ri-quill-pen-line me-1"></i>Confirm & Sign
                </button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="sharedReturnModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" id="returnForm" action="">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title"><i class="ri-arrow-go-back-line me-2"></i>Return Document</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="action" value="Returned">
                    <input type="hidden" name="old_status" value="For Approval">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Remarks <span class="text-danger">*</span></label>
                        <textarea name="remarks" class="form-control" rows="4" 
                            placeholder="Please provide reason for returning..." required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">
                        <i class="ri-arrow-go-back-line me-1"></i>Return Document
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- <div class="row">
    <div class="col-md-12 mb-5">
        <div class="card shadow-sm">
            <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
                <h5 class="mb-0">Copy Requests</h5>
            </div>
            <div class="card-body">
                <div class="top-controls-container">
                    <div class="left-controls"><div id="copy-length-control"></div></div>
                    <div class="right-controls">
                        <div class="search-wrapper"><div id="copy-filter-control"></div></div>
                        <div class="buttons-wrapper"><div id="copy-buttons-control"></div></div>
                    </div>
                </div>
                <div class="table-scroll-container">
                    <table class="table table-hover table-bordered" id="copyApprovalTable">
                        <thead class="table-light">
                            <tr>
                                <th>Actions</th>
                                <th>Reference</th>
                                <th>Date</th>
                                <th>Document</th>
                                <th>Requested&nbsp;By</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
                <div class="bottom-controls-container">
                    <div id="copy-info-control"></div>
                    <div id="copy-pagination-control"></div>
                </div>
            </div>
        </div>
    </div>
</div> --}}

{{-- @foreach($copy_for_approvals->where('status','Pending') as $copy_approval)
@php $request = $copy_approval->copy_request; @endphp
@include('copy_request.view_approval_copy')
@endforeach --}}

@endsection

@section('js')
<script>
$(document).ready(function () {

    var dtConfig = {
        processing: true,
        serverSide: true,
        pageLength: 10,
        lengthMenu: [[10, 25, 50], [10, 25, 50]],
        responsive: true,
        dom: 'lBfrtip',
        buttons: [
            { extend: 'copy',  text: 'Copy', className: 'btn btn-secondary btn-sm', titleAttr: 'Copy to clipboard' },
            { extend: 'excel', text: 'Excel', className: 'btn btn-secondary btn-sm', title: 'Change Requests' }
        ],
        language: {
            processing: '<div style="text-align:center;"><i class="fa fa-spinner fa-spin fa-2x"></i><br><span style="margin-top:10px;display:block;">Loading...</span></div>',
            emptyTable: "No records found",
            zeroRecords: "No matching records found",
            lengthMenu: "Show _MENU_ entries",
            info: "Showing _START_ to _END_ of _TOTAL_ entries",
            infoEmpty: "Showing 0 to 0 of 0 entries",
            infoFiltered: "(filtered from _MAX_ total entries)",
            search: "Search:",
            paginate: { first: "First", last: "Last", next: "Next", previous: "Previous" }
        }
    };

    var changeTable = $('#changeApprovalTable').DataTable($.extend(true, {}, dtConfig, {
        ajax: {
            url: '{{ route("for-approval.change.data") }}',
            type: 'GET',
            error: function (xhr) { console.error(xhr.status, xhr.responseText); }
        },
        columns: [
            { data: 'action', orderable: false, searchable: false },
            { data: 'reference', orderable: false, searchable: false },
            { data: 'date', name: 'created_at' },
            { data: 'title', name: 'title' },
            { data: 'requested_by', orderable: false, searchable: false },
            // { data: 'type', orderable: false, searchable: false },
            { data: 'revision_count', orderable: false, searchable: false },
            { data: 'approvers', orderable: false, searchable: false },
            { data: 'status', orderable: false, searchable: false },
        ],
        order: [[2, 'desc']],
        drawCallback: function () { moveControls('changeApprovalTable', 'change'); },
        initComplete: function () {
            var inp = $('#changeApprovalTable_filter input');
            inp.unbind();
            var t;
            inp.on('input', function () {
                var v = $(this).val();
                clearTimeout(t);
                t = setTimeout(function () { changeTable.search(v).draw(); }, 500);
            });
        }
    }));

    var copyTable = $('.tables').DataTable();

    function moveControls(tableId, prefix) {
        var wrapper = $('#' + tableId + '_wrapper');

        var length = wrapper.find('.dataTables_length');
        if (length.length) {
            $('#' + prefix + '-length-control').empty().append(length.detach());
        }

        var filter = wrapper.find('.dataTables_filter');
        if (filter.length) {
            var searchInput = filter.find('input');
            var hasFocus  = searchInput.is(':focus');
            var cursorPos = searchInput[0] ? searchInput[0].selectionStart : null;
            $('#' + prefix + '-filter-control').empty().append(filter.detach());
            if (hasFocus) {
                var newInp = $('#' + tableId + '_filter input');
                newInp.focus();
                if (cursorPos !== null) newInp[0].setSelectionRange(cursorPos, cursorPos);
            }
        }

        var buttons = wrapper.find('.dt-buttons');
        if (buttons.length) {
            $('#' + prefix + '-buttons-control').empty().append(buttons.detach());
        }

        var info = wrapper.find('.dataTables_info');
        if (info.length) {
            $('#' + prefix + '-info-control').empty().append(info.detach());
        }

        var paginate = wrapper.find('.dataTables_paginate');
        if (paginate.length) {
            $('#' + prefix + '-pagination-control').empty().append(paginate.detach());
        }
    }

    setTimeout(function () {
        moveControls('changeApprovalTable', 'change');
        moveControls('copyApprovalTable', 'copy');
    }, 100);

    $(window).on('resize', function () {
        moveControls('changeApprovalTable', 'change');
        moveControls('copyApprovalTable', 'copy');
    });
});

function openSignModal(changeRequestId) {
    document.getElementById('signChangeRequestId').value = changeRequestId;
    document.getElementById('signPassword').value = '';
    document.getElementById('signError').classList.add('d-none');
    var modal = new bootstrap.Modal(document.getElementById('sharedConfirmPasswordModal'));
    modal.show();
}

document.getElementById('signConfirmBtn').addEventListener('click', function () {
    const changeRequestId = document.getElementById('signChangeRequestId').value;
    const password = document.getElementById('signPassword').value;

    if (!password) {
        document.getElementById('signError').textContent = 'Please enter your password.';
        document.getElementById('signError').classList.remove('d-none');
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
        if (data.status == "success") {
            window.location.href = data.redirect;
        } else {
            document.getElementById('signError').textContent = data.message ?? 'Incorrect password. Please try again.';
            document.getElementById('signError').classList.remove('d-none');
        }
    })
    .catch(() => {
        document.getElementById('signError').textContent = 'An error occurred. Please try again.';
        document.getElementById('signError').classList.remove('d-none');
    });
});

function openReturnModal(changeRequestId) {
    document.getElementById('returnForm').action = '{{ url("change-request/change-request-action") }}/' + changeRequestId
    var modal = new bootstrap.Modal(document.getElementById('sharedReturnModal'));
    modal.show();
}
</script>
@endsection