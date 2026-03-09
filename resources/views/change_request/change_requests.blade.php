@extends('layouts.header')

@section('css')
<style>
    
</style>
@endsection

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <h4 class="mb-0">My Files</h4>
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
    <div class="col-md-12 mb-5">
        <div class="card shadow-sm">
            <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
                <h5 class="mb-0">My Files</h5>
            </div>

            <div class="card-body">
                <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:8px;">
                    <select id="statusFilter" class="form-select form-select-sm" style="width:auto;min-width:140px;">
                        <option value="">All Status</option>
                        <option value="For Approval">For Approval</option>
                        <option value="Declined">Declined</option>
                        <option value="Approved">Approved</option>
                        <option value="Draft">Draft</option>
                        <option value="Returned">Returned</option>
                    </select>
                    <div id="table-filter-control"></div>
                </div>

                <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:15px;">
                    <div id="table-length-control"></div>
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
@endsection

@section('js')
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
            { data: 'status', name: 'status' },
        ],
        pageLength: 10,
        lengthMenu: [[10, 25, 50, 100], [10, 25, 50, 100]],
        responsive: true,
        dom: 'lBfrtip',
        buttons: [
            { extend: 'copy',  text: 'Copy',  titleAttr: 'Copy to clipboard' },
            { extend: 'excel', text: 'Excel', title: 'Change Requests' }
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

});
</script>
@endsection