@extends('layouts.header')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <h4 class="mb-0">Departments</h4>
        <p class="text-muted mb-0">Manage and track department information</p>
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-xl-4 col-md-6">
        <div class="dashboard-card total">
            <div class="icon-circle"><i class="ri-community-line"></i></div>
            <h2 class="mb-0 font-weight-bold">{{ $totalDepartments }}</h2>
            <p>Total Departments</p>
        </div>
    </div>
    <div class="col-xl-4 col-md-6">
        <div class="dashboard-card active">
            <div class="icon-circle"><i class="ri-checkbox-circle-line"></i></div>
            <h2 class="mb-0 font-weight-bold filter-btn" data-filter="Active" style="cursor:pointer;">{{ $activeDepartments }}</h2>
            <p>Active</p>
        </div>
    </div>
    <div class="col-xl-4 col-md-6">
        <div class="dashboard-card inactive">
            <div class="icon-circle"><i class="ri-close-circle-line"></i></div>
            <h2 class="mb-0 font-weight-bold filter-btn" data-filter="Inactive" style="cursor:pointer;">{{ $inactiveDepartments }}</h2>
            <p>Deactivated</p>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12 mb-5">
        <div class="card shadow-sm">
            <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
                <h5 class="mb-0">All Departments</h5>
                <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#new_department">
                    <i class="fa fa-plus"></i> New
                </button>
            </div>
            <div class="card-body">
                <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:8px;">
                    <select id="statusFilter" class="form-select form-select-sm" style="width:auto;min-width:160px;">
                        <option value="">All Status</option>
                        <option value="Active">Active</option>
                        <option value="Inactive">Inactive</option>
                    </select>
                    <div id="table-filter-control"></div>
                </div>

                <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:15px;">
                    <div id="table-length-control"></div>
                    <div id="table-buttons-control"></div>
                </div>

                <div class="table-scroll-container">
                    <table class="table table-hover table-bordered" id="departmentsTable">
                        <thead class="table-light">
                            <tr>
                                <th>Action</th>
                                <th>Code</th>
                                <th>Name</th>
                                <th>Department Head</th>
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

@include('departments.new_department')
@foreach($departments as $department)
    @include('departments.edit_department')
@endforeach

@endsection

@section('js')
<script>
$(document).ready(function () {

    $('#new_department').on('shown.bs.modal', function () {
        $(this).find('.select2-dept-head').select2({
            theme: 'bootstrap-5',
            dropdownParent: $(this),
            placeholder: 'Select department head...',
            allowClear: true
        });
    });

    $('#new_department').on('hide.bs.modal', function () {
        $(this).find('.select2-dept-head').select2('close');
    });

    @foreach($departments as $department)
    $('#editDepartment{{ $department->id }}').on('shown.bs.modal', function () {
        var $select = $(this).find('.select2-dept-head');
        if ($select.hasClass('select2-hidden-accessible')) {
            $select.select2('destroy');
        }
        $select.select2({
            theme: 'bootstrap-5',
            dropdownParent: $(this),
            placeholder: 'Select department head...',
            allowClear: true
        });
    });

    $('#editDepartment{{ $department->id }}').on('hide.bs.modal', function () {
        $(this).find('.select2-dept-head').select2('close');
    });
    @endforeach

    var currentFilter = '';

    var table = $('#departmentsTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: '{{ route("departments.data") }}',
            type: 'GET',
            data: function (d) { d.status_filter = currentFilter; },
            error: function (xhr) {
                console.error(xhr.status, xhr.responseText);
            }
        },
        columns: [
            { data: 'action', orderable: false, searchable: false },
            { data: 'code', name: 'code' },
            { data: 'name', name: 'name' },
            { data: 'dep_head', orderable: false, searchable: false },
            { data: 'status', orderable: false, searchable: false },
        ],
        pageLength: 25,
        lengthMenu: [[10, 25, 50, 100], [10, 25, 50, 100]],
        dom: 'lBfrtip',
        buttons: [
            { extend: 'copy', text: 'Copy' },
            { extend: 'excel', text: 'Excel', title: 'Departments' },
        ],
        order: [[0, 'desc']],
        language: {
            processing: '<div style="text-align:center;"><i class="fa fa-spinner fa-spin fa-2x"></i><br><span style="margin-top:10px;display:block;">Loading...</span></div>',
            emptyTable: "No departments found",
            zeroRecords: "No matching departments found",
            lengthMenu: "Show _MENU_ entries",
            info: "Showing _START_ to _END_ of _TOTAL_ entries",
            infoEmpty: "Showing 0 to 0 of 0 entries",
            infoFiltered: "(filtered from _MAX_ total entries)",
            search: "Search:",
            paginate: { first: "First", last: "Last", next: "Next", previous: "Previous" }
        },
        drawCallback: function () { moveControls(); },
        initComplete: function () {
            var inp = $('#departmentsTable_filter input');
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
        var wrapper = $('#departmentsTable_wrapper');

        var length = wrapper.find('.dataTables_length');
        if (length.length) $('#table-length-control').empty().append(length.detach());

        var filter = wrapper.find('.dataTables_filter');
        if (filter.length) {
            var inp = filter.find('input');
            var hasFocus = inp.is(':focus');
            var curPos = inp[0] ? inp[0].selectionStart : null;
            $('#table-filter-control').empty().append(filter.detach());
            if (hasFocus) {
                var newInp = $('#departmentsTable_filter input');
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

        $('#departmentsTable tbody').on('click', '.deactivate-department', function () {
        var id = $(this).data('id');
        swal({
            title: "Are you sure?",
            text: "This department will be deactivated!",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Yes, deactivate it!",
            closeOnConfirm: false
        }, function () {
            $.ajax({
                type: 'POST',
                url: '{{ url("/departments/deactivate") }}',
                data: { id: id },
                headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                success: function () {
                    swal("Deactivated!", "Department is now deactivated.", "success");
                    table.ajax.reload();
                },
                error: function () {
                    swal("Error!", "Something went wrong.", "error");
                }
            });
        });
    });

    $('#departmentsTable tbody').on('click', '.activate-department', function () {
        var id = $(this).data('id');
        swal({
            title: "Are you sure?",
            text: "This department will be activated!",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Yes, activate it!",
            closeOnConfirm: false
        }, function () {
            $.ajax({
                type: 'POST',
                url: '{{ url("/departments/activate") }}',
                data: { id: id },
                headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                success: function () {
                    swal("Activated!", "Department is now activated.", "success");
                    table.ajax.reload();
                },
                error: function () {
                    swal("Error!", "Something went wrong.", "error");
                }
            });
        });
    });

});
</script>
@endsection