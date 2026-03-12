@extends('layouts.header')

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
    <div class="col-xl-4 col-md-4">
        <div class="dashboard-card approved">
            <div class="icon-circle"><i class="fa fa-check-circle"></i></div>
            <h2 class="mb-0 font-weight-bold">
                {{ count($change_for_approvals->where('status','Approved')) }}
            </h2>
            <p>Approved</p>
        </div>
    </div>
    <div class="col-xl-4 col-md-4">
        <div class="dashboard-card declined">
            <div class="icon-circle"><i class="fa fa-times-circle"></i></div>
            <h2 class="mb-0 font-weight-bold">
                {{ count($change_for_approvals->where('status','Declined')) }}
            </h2>
            <p>Declined</p>
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
                <div class="top-controls-container">
                    <div class="left-controls"><div id="change-length-control"></div></div>
                    <div class="right-controls">
                        <div class="search-wrapper"><div id="change-filter-control"></div></div>
                        <div class="buttons-wrapper"><div id="change-buttons-control"></div></div>
                    </div>
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
                                <th>Type</th>
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
            { extend: 'copy',  text: 'Copy'  },
            { extend: 'excel', text: 'Excel' }
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
            { data: 'type', orderable: false, searchable: false },
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

    // var copyTable = $('#copyApprovalTable').DataTable($.extend(true, {}, dtConfig, {
    //     ajax: {
    //         url: '{{ route("for-approval.copy.data") }}',
    //         type: 'GET',
    //         error: function (xhr) { console.error(xhr.status, xhr.responseText); }
    //     },
    //     columns: [
    //         { data: 'action', orderable: false, searchable: false },
    //         { data: 'reference', orderable: false, searchable: false },
    //         { data: 'date', name: 'created_at' },
    //         { data: 'document', name: 'document' },
    //         { data: 'requested_by', orderable: false, searchable: false },
    //     ],
    //     order: [[2, 'desc']],
    //     drawCallback: function () { moveControls('copyApprovalTable', 'copy'); },
    //     initComplete: function () {
    //         var inp = $('#copyApprovalTable_filter input');
    //         inp.unbind();
    //         var t;
    //         inp.on('input', function () {
    //             var v = $(this).val();
    //             clearTimeout(t);
    //             t = setTimeout(function () { copyTable.search(v).draw(); }, 500);
    //         });
    //     }
    // }));

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
</script>
@endsection