@extends('layouts.header')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <h4 class="mb-0">Roles & Permissions</h4>
        <p class="text-muted mb-0">Manage roles and permissions</p>
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-xl-4 col-md-6">
        <div class="dashboard-card total">
            <div class="icon-circle"><i class="ri-shield-user-line"></i></div>
            <h2 class="mb-0 font-weight-bold">{{ $totalRoles }}</h2>
            <p>Total Roles</p>
        </div>
    </div>
    <div class="col-xl-4 col-md-6">
        <div class="dashboard-card active">
            <div class="icon-circle"><i class="ri-key-2-line"></i></div>
            <h2 class="mb-0 font-weight-bold">{{ $totalPermissions }}</h2>
            <p>Total Permissions</p>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="card shadow-sm">
            <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
                <h5 class="mb-0">Roles</h5>
                <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#new">
                    <i class="fa fa-plus"></i> Add New Role
                </button>
            </div>
            <div class="card-body">
                <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:8px;">
                    <div></div>
                    <div id="roles-filter-control"></div>
                </div>
                <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:15px;">
                    <div id="roles-length-control"></div>
                    <div id="roles-buttons-control"></div>
                </div>
                <div class="table-scroll-container">
                    <table class="table table-hover table-bordered" id="rolesTable">
                        <thead class="table-light">
                            <tr>
                                <th>Action</th>
                                <th>Role</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
                <div class="bottom-controls-container">
                    <div id="roles-info-control"></div>
                    <div id="roles-pagination-control"></div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-12">
        <div class="card shadow-sm">
            <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
                <h5 class="mb-0">Permissions</h5>
                <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#newPermission">
                    <i class="fa fa-plus"></i> Add New Permission
                </button>
            </div>
            <div class="card-body">
                <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:8px;">
                    <div></div>
                    <div id="perms-filter-control"></div>
                </div>
                <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:15px;">
                    <div id="perms-length-control"></div>
                    <div id="perms-buttons-control"></div>
                </div>
                <div class="table-scroll-container">
                    <table class="table table-hover table-bordered" id="permissionTable">
                        <thead class="table-light">
                            <tr>
                                <th>Action</th>
                                <th>Name</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
                <div class="bottom-controls-container">
                    <div id="perms-info-control"></div>
                    <div id="perms-pagination-control"></div>
                </div>
            </div>
        </div>
    </div>
</div>

@include('roles.new')
@foreach($roles as $role)
    @include('roles.edit')
    @include('roles.view')
@endforeach
@foreach($permissions as $permission)
    @include('permissions.editPermission')
@endforeach
@include('permissions.newPermission')
@endsection

@section('js')
<script>
$(document).ready(function () {

    var rolesTable = $('#rolesTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: '{{ route("roles.data") }}',
            type: 'GET',
            error: function (xhr) { console.error(xhr.status, xhr.responseText); }
        },
        columns: [
            { data: 'action', orderable: false, searchable: false },
            { data: 'name', name: 'name' },
        ],
        pageLength: 25,
        lengthMenu: [[10, 25, 50, 100], [10, 25, 50, 100]],
        dom: 'lBfrtip',
        buttons: [
            { extend: 'copy', text: 'Copy' },
            { extend: 'excel', text: 'Excel', title: 'Roles' },
        ],
        order: [[1, 'asc']],
        language: {
            processing: '<div style="text-align:center;"><i class="fa fa-spinner fa-spin fa-2x"></i><br><span style="margin-top:10px;display:block;">Loading...</span></div>',
            emptyTable: "No roles found",
            zeroRecords: "No matching roles found",
            lengthMenu: "Show _MENU_ entries",
            info: "Showing _START_ to _END_ of _TOTAL_ entries",
            infoEmpty: "Showing 0 to 0 of 0 entries",
            infoFiltered: "(filtered from _MAX_ total entries)",
            search: "Search:",
            paginate: { first: "First", last: "Last", next: "Next", previous: "Previous" }
        },
        drawCallback: function () { moveRolesControls(); },
        initComplete: function () {
            var inp = $('#rolesTable_filter input');
            inp.unbind();
            var t;
            inp.on('input', function () {
                var v = $(this).val();
                clearTimeout(t);
                t = setTimeout(function () { rolesTable.search(v).draw(); }, 500);
            });
        }
    });

    function moveRolesControls() {
        var wrapper = $('#rolesTable_wrapper');

        var length = wrapper.find('.dataTables_length');
        if (length.length) $('#roles-length-control').empty().append(length.detach());

        var filter = wrapper.find('.dataTables_filter');
        if (filter.length) $('#roles-filter-control').empty().append(filter.detach());

        var buttons = wrapper.find('.dt-buttons');
        if (buttons.length) $('#roles-buttons-control').empty().append(buttons.detach());

        var info = wrapper.find('.dataTables_info');
        if (info.length) $('#roles-info-control').empty().append(info.detach());

        var paginate = wrapper.find('.dataTables_paginate');
        if (paginate.length) $('#roles-pagination-control').empty().append(paginate.detach());
    }

    setTimeout(function () { moveRolesControls(); }, 100);

    var permsTable = $('#permissionTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: '{{ route("permissions.data") }}',
            type: 'GET',
            error: function (xhr) { console.error(xhr.status, xhr.responseText); }
        },
        columns: [
            { data: 'action', orderable: false, searchable: false },
            { data: 'name', name: 'name' },
        ],
        pageLength: 25,
        lengthMenu: [[10, 25, 50, 100], [10, 25, 50, 100]],
        dom: 'lBfrtip',
        buttons: [
            { extend: 'copy', text: 'Copy' },
            { extend: 'excel', text: 'Excel', title: 'Permissions' },
        ],
        order: [[1, 'asc']],
        language: {
            processing: '<div style="text-align:center;"><i class="fa fa-spinner fa-spin fa-2x"></i><br><span style="margin-top:10px;display:block;">Loading...</span></div>',
            emptyTable: "No permissions found",
            zeroRecords: "No matching permissions found",
            lengthMenu: "Show _MENU_ entries",
            info: "Showing _START_ to _END_ of _TOTAL_ entries",
            infoEmpty: "Showing 0 to 0 of 0 entries",
            infoFiltered: "(filtered from _MAX_ total entries)",
            search: "Search:",
            paginate: { first: "First", last: "Last", next: "Next", previous: "Previous" }
        },
        drawCallback: function () { movePermsControls(); },
        initComplete: function () {
            var inp = $('#permissionTable_filter input');
            inp.unbind();
            var t;
            inp.on('input', function () {
                var v = $(this).val();
                clearTimeout(t);
                t = setTimeout(function () { permsTable.search(v).draw(); }, 500);
            });
        }
    });

    function movePermsControls() {
        var wrapper = $('#permissionTable_wrapper');

        var length = wrapper.find('.dataTables_length');
        if (length.length) $('#perms-length-control').empty().append(length.detach());

        var filter = wrapper.find('.dataTables_filter');
        if (filter.length) $('#perms-filter-control').empty().append(filter.detach());

        var buttons = wrapper.find('.dt-buttons');
        if (buttons.length) $('#perms-buttons-control').empty().append(buttons.detach());

        var info = wrapper.find('.dataTables_info');
        if (info.length) $('#perms-info-control').empty().append(info.detach());

        var paginate = wrapper.find('.dataTables_paginate');
        if (paginate.length) $('#perms-pagination-control').empty().append(paginate.detach());
    }

    setTimeout(function () { movePermsControls(); }, 100);

    $(window).on('resize', function () {
        moveRolesControls();
        movePermsControls();
    });

});
</script>
@endsection