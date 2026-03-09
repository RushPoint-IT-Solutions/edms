@extends('layouts.header')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <h4 class="mb-0">Permits & Licenses</h4>
        <p class="text-muted mb-0">Manage and track all permits and licenses</p>
    </div>
</div>

<div class="row g-3 mb-4 h-100">
    <div class="col-lg-2 col-md-4 col-sm-6">
        <div class="dashboard-card total">
            <div class="icon-circle"><i class="ri-file-list-3-line"></i></div>
            <h2 class="mb-0 font-weight-bold">
                <a href="{{ url('permits') }}">{{ $permits_count }}</a>
            </h2>
            <p>Total</p>
        </div>
    </div>
    <div class="col-lg-2 col-md-4 col-sm-6">
        <div class="dashboard-card active">
            <div class="icon-circle"><i class="ri-checkbox-circle-line"></i></div>
            <h2 class="mb-0 font-weight-bold filter-btn" data-filter="Active" style="cursor:pointer;">
                {{ $active_permits_count }}
            </h2>
            <p>Active</p>
        </div>
    </div>
    <div class="col-lg-2 col-md-4 col-sm-6">
        <div class="dashboard-card renewal">
            <div class="icon-circle"><i class="ri-refresh-line"></i></div>
            <h2 class="mb-0 font-weight-bold filter-btn" data-filter="For Renewal" style="cursor:pointer;">
                {{ $for_renewal_count }}
            </h2>
            <p>For Renewal</p>
        </div>
    </div>
    <div class="col-lg-2 col-md-4 col-sm-6">
        <div class="dashboard-card overdue">
            <div class="icon-circle"><i class="ri-alert-line"></i></div>
            <h2 class="mb-0 font-weight-bold filter-btn" data-filter="Overdue" style="cursor:pointer;">
                {{ $overdue_count }}
            </h2>
            <p>Overdue</p>
        </div>
    </div>
    {{-- <div class="col-lg-2 col-md-4 col-sm-6">
        <div class="dashboard-card archived">
            <div class="icon-circle"><i class="ri-archive-line"></i></div>
            <h2 class="mb-0 font-weight-bold">
                <a href="{{ url('archive_permits') }}">{{ count($archives) }}</a>
            </h2>
            <p>Archived</p>
        </div>
    </div> --}}
    <div class="col-lg-2 col-md-4 col-sm-6">
        <div class="dashboard-card inactive">
            <div class="icon-circle"><i class="ri-close-circle-line"></i></div>
            <h2 class="mb-0 font-weight-bold filter-btn" data-filter="Inactive" style="cursor:pointer;">
                {{ $inactive_permits_count }}
            </h2>
            <p>Inactive</p>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12 mb-5">
        <div class="card shadow-sm">
            <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
                <h5 class="mb-0">Permits & Licenses</h5>
                <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#new_permit">
                    <i class="fa fa-plus"></i> New
                </button>
            </div>
            <div class="card-body">
                <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:8px;">
                    <select id="statusFilter" class="form-select form-select-sm" style="width:auto;min-width:160px;">
                        <option value="">All Status</option>
                        <option value="Active">Active</option>
                        <option value="For Renewal">For Renewal</option>
                        <option value="Overdue">Overdue</option>
                        <option value="Inactive">Inactive</option>
                    </select>
                    <div id="table-filter-control"></div>
                </div>

                <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:15px;">
                    <div id="table-length-control"></div>
                    <div id="table-buttons-control"></div>
                </div>

                <div class="table-scroll-container">
                    <table class="table table-hover table-bordered" id="permitsTable">
                        <thead class="table-light">
                            <tr>
                                <th>Action</th>
                                <th>Title</th>
                                <th>Description</th>
                                {{-- <th>Company</th> --}}
                                {{-- <th>Department</th> --}}
                                {{-- <th>Accountable Person</th> --}}
                                <th>Date&nbsp;Uploaded</th>
                                <th>File</th>
                                <th>Type</th>
                                <th>Expiration&nbsp;Date</th>
                                <th>Status</th>
                                <th>Created&nbsp;By</th>
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

@include('permits.new_permit')

@endsection

@section('js')
<script>
$(document).ready(function () {

    var currentFilter = '';

    var table = $('#permitsTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: '{{ url("permits/data") }}',
            type: 'GET',
            data: function (d) { d.status_filter = currentFilter; },
            error: function (xhr) {
                console.error(xhr.status, xhr.responseText);
                Swal.fire({ icon: 'error', title: 'Error', text: 'Failed to load data.' });
            }
        },
        columns: [
            { data: 'action', orderable: false, searchable: false },
            { data: 'title', name: 'title' },
            { data: 'description', name: 'description' },
            {{-- { data: 'company', name: 'company' }, --}}
            {{-- { data: 'department', name: 'department' }, --}}
            { data: 'date_uploaded', name: 'created_at' },
            { data: 'file', orderable: false, searchable: false },
            { data: 'type', name: 'type' },
            { data: 'expiration_date', name: 'expiration_date' },
            { data: 'status', orderable: false, searchable: false },
            { data: 'created_by', orderable: false, searchable: false },
        ],
        pageLength: 25,
        lengthMenu: [[10, 25, 50, 100], [10, 25, 50, 100]],
        responsive: true,
        dom: 'lBfrtip',
        buttons: [
            { extend: 'copy', text: 'Copy' },
            { extend: 'excel', text: 'Excel', title: 'Permits & Licenses' },
            { extend: 'pdf', text: 'PDF', title: 'Permits & Licenses' },
        ],
        order: [[0, 'desc']],
        language: {
            processing: '<div style="text-align:center;"><i class="fa fa-spinner fa-spin fa-2x"></i><br><span style="margin-top:10px;display:block;">Loading...</span></div>',
            emptyTable: "No permits found",
            zeroRecords: "No matching permits found",
            lengthMenu: "Show _MENU_ entries",
            info: "Showing _START_ to _END_ of _TOTAL_ entries",
            infoEmpty: "Showing 0 to 0 of 0 entries",
            infoFiltered: "(filtered from _MAX_ total entries)",
            search: "Search:",
            paginate: { first: "First", last: "Last", next: "Next", previous: "Previous" }
        },
        drawCallback: function () { moveControls(); },
        initComplete: function () {
            var inp = $('#permitsTable_filter input');
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
        var wrapper = $('#permitsTable_wrapper');

        var length = wrapper.find('.dataTables_length');
        if (length.length) $('#table-length-control').empty().append(length.detach());

        var filter = wrapper.find('.dataTables_filter');
        if (filter.length) {
            var inp = filter.find('input');
            var hasFocus = inp.is(':focus');
            var curPos = inp[0] ? inp[0].selectionStart : null;
            $('#table-filter-control').empty().append(filter.detach());
            if (hasFocus) {
                var newInp = $('#permitsTable_filter input');
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
        currentFilter = $(this).data('filter');
        $('#statusFilter').val(currentFilter);
        table.ajax.reload();
    });

    $(document).on('click', '.inactiveBtn', function () {
        var form = $(this).closest('form');
        swal({
            title: "Are you sure?",
            text: "This permits will be inactive!",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Yes, Inactive it!",
            closeOnConfirm: false
        }, function () { form.submit(); });
    });

    $(document).on('click', '.activatePermitsBtn', function () {
        var form = $(this).closest('form');
        swal({
            title: "Are you sure?",
            text: "This permits will be activate!",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Yes, Activate it!",
            closeOnConfirm: false
        }, function () { form.submit(); });
    });

});
</script>
@endsection