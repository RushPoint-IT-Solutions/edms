@extends('layouts.header')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <h4 class="mb-0">Request Access Approvals</h4>
        <p class="text-muted mb-0">Manage document access requests</p>
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-xl-4 col-md-4">
        <div class="dashboard-card pending">
            <div class="icon-circle"><i class="fa fa-clock-o"></i></div>
            <h2 class="mb-0 font-weight-bold filter-btn" data-filter="0" style="cursor:pointer;">{{ $forApproval }}</h2>
            <p>For Approval</p>
        </div>
    </div>
    <div class="col-xl-4 col-md-4">
        <div class="dashboard-card approved">
            <div class="icon-circle"><i class="fa fa-check-circle"></i></div>
            <h2 class="mb-0 font-weight-bold filter-btn" data-filter="1" style="cursor:pointer;">{{ $approved }}</h2>
            <p>Approved</p>
        </div>
    </div>
    <div class="col-xl-4 col-md-4">
        <div class="dashboard-card declined">
            <div class="icon-circle"><i class="fa fa-times-circle"></i></div>
            <h2 class="mb-0 font-weight-bold filter-btn" data-filter="3" style="cursor:pointer;">{{ $declined }}</h2>
            <p>Declined</p>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12 mb-5">
        <div class="card shadow-sm">
            <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
                <h5 class="mb-0">All Request Access</h5>
            </div>
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
                    <div class="d-flex align-items-center gap-2 flex-wrap">
                        <div id="table-length-control"></div>
                        <select id="statusFilter" class="form-select form-select-sm" style="width:auto;min-width:160px;">
                            <option value="">All Status</option>
                            <option value="0">For Approval</option>
                            <option value="1">Approved</option>
                            <option value="3">Declined</option>
                        </select>
                        <div id="table-filter-control"></div>
                    </div>
                    <div id="table-buttons-control"></div>
                </div>

                <div class="table-scroll-container">
                    <table class="table table-hover table-bordered" id="requestAccessTable">
                        <thead class="table-light">
                            <tr>
                                <th>Actions</th>
                                <th>Requested By</th>
                                <th>Department</th>
                                <th>Title</th>
                                <th>Date</th>
                                <th>Reason</th>
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

<div id="modals-container"></div>

@endsection

@section('js')
<script>
$(document).ready(function () {

    var currentFilter = '';

    var table = $('#requestAccessTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: '{{ route("request_access.data") }}',
            type: 'GET',
            data: function (d) { d.status_filter = currentFilter; },
            error: function (xhr) { console.error(xhr.status, xhr.responseText); }
        },
        columns: [
            { data: 'action',       orderable: false, searchable: false },
            { data: 'requested_by', name: 'requestor.name' },
            { data: 'department',   orderable: false, searchable: false },
            { data: 'title',        name: 'document.title' },
            { data: 'date',         name: 'request_date' },
            { data: 'reason',       name: 'reason' },
            { data: 'status',       orderable: false, searchable: false },
        ],
        pageLength: 25,
        lengthMenu: [[10, 25, 50, 100], [10, 25, 50, 100]],
        dom: 'lBfrtip',
        buttons: [
            { extend: 'copy',  text: 'Copy' },
            { extend: 'excel', text: 'Excel', title: 'Request Access' },
        ],
        order: [[0, 'desc']],
        language: {
            processing: '<div style="text-align:center;"><i class="fa fa-spinner fa-spin fa-2x"></i><br><span style="margin-top:10px;display:block;">Loading...</span></div>',
            emptyTable:   "No access requests found",
            zeroRecords:  "No matching records found",
            lengthMenu:   "Show _MENU_ entries",
            info:         "Showing _START_ to _END_ of _TOTAL_ entries",
            infoEmpty:    "Showing 0 to 0 of 0 entries",
            infoFiltered: "(filtered from _MAX_ total entries)",
            search:       "Search:",
            paginate: { first: "First", last: "Last", next: "Next", previous: "Previous" }
        },
        drawCallback: function () {
            moveControls();
            $('#modals-container').empty();
            table.rows().every(function () {
                var d = this.data();
                if (d.modal_html) {
                    $('#modals-container').append(d.modal_html);
                }
            });
        },
        initComplete: function () {
            var inp = $('#requestAccessTable_filter input');
            inp.unbind();
            var t;
            inp.on('input', function () {
                var v = $(this).val();
                clearTimeout(t);
                t = setTimeout(function () { table.search(v).draw(); }, 500);
            });
        }
    });

    function moveControls() {
        var wrapper = $('#requestAccessTable_wrapper');

        var length = wrapper.find('.dataTables_length');
        if (length.length) $('#table-length-control').empty().append(length.detach());

        var filter = wrapper.find('.dataTables_filter');
        if (filter.length) {
            var inp = filter.find('input');
            var hasFocus = inp.is(':focus');
            var curPos = inp[0] ? inp[0].selectionStart : null;
            $('#table-filter-control').empty().append(filter.detach());
            if (hasFocus) {
                var newInp = $('#requestAccessTable_filter input');
                newInp.focus();
                if (curPos !== null) newInp[0].setSelectionRange(curPos, curPos);
            }
        }

        var buttons = wrapper.find('.dt-buttons');
        if (buttons.length) $('#table-buttons-control').empty().append(buttons.detach());

        var info = wrapper.find('.dataTables_info');
        if (info.length) $('#table-info-control').empty().append(info.detach());

        var paginate = wrapper.find('.dataTables_paginate');
        if (paginate.length) $('#table-pagination-control').empty().append(paginate.detach());
    }

    setTimeout(function () { moveControls(); }, 100);
    $(window).on('resize', function () { moveControls(); });

    $('#statusFilter').on('change', function () {
        currentFilter = $(this).val();
        table.ajax.reload();
    });

    $('.filter-btn').on('click', function () {
        currentFilter = String($(this).data('filter'));
        $('#statusFilter').val(currentFilter);
        table.ajax.reload();
    });

});
</script>
@endsection