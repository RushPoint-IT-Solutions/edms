@extends('layouts.header')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <h4 class="mb-0">User Management</h4>
        <p class="text-muted mb-0">Manage and monitor user accounts</p>
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-xl-3 col-md-6">
        <div class="dashboard-card total">
            <div class="icon-circle"><i class="fa fa-users"></i></div>
            <h2 class="mb-0 font-weight-bold">{{ $totalUsers }}</h2>
            <p>Total Users</p>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="dashboard-card active">
            <div class="icon-circle"><i class="fa fa-check-circle"></i></div>
            <h2 class="mb-0 font-weight-bold">{{ $activeUsers }}</h2>
            <p>Active Users</p>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="dashboard-card active">
            <div class="icon-circle"><i class="fa fa-shield" aria-hidden="true"></i></div>
            <h2 class="mb-0 font-weight-bold">{{ $ssoUsers }}</h2>
            <p>SSO Connected</p>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="dashboard-card inactive">
            <div class="icon-circle"><i class="fa fa-times-circle"></i></div>
            <h2 class="mb-0 font-weight-bold">{{ $inactiveUsers }}</h2>
            <p>Deactivated Users</p>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12 mb-5">
        <div class="card shadow-sm">
            <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
                <h5 class="mb-0">Users List</h5>
                @if(canCreate('users.create'))
                <button class="btn btn-first btn-sm" data-bs-toggle="modal" data-bs-target="#new_account">
                    <i class="fa fa-plus"></i> New Account
                </button>
                @endif
            </div>
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
                    <div class="d-flex align-items-center gap-2 flex-wrap">
                        <div id="table-filter-control"></div>
                        <select id="roleFilter" class="form-select form-select-sm" style="width:auto;min-width:160px;">
                            <option value="">All Roles</option>
                            @foreach($roles as $role)
                                <option value="{{ $role->name }}">{{ $role->name }}</option>
                            @endforeach
                        </select>
                        <div id="table-length-control"></div>
                    </div>
                    <div id="table-buttons-control"></div>
                </div>

                <div class="table-responsive">
                    <table class="table table-hover table-bordered" id="usersTable">
                        <thead class="table-light">
                            <tr>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Offices</th>
                                <th>Role</th>
                                <th>Status</th>
                                <th>Action</th>
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

<div id="modalsContainer"></div>

@include('users.new_account')
@include('users.edit_user')
@include('users.changepassword')
{{-- @include("settings.access_control.access_control") --}}
@endsection

@section('js')
<script src="{{ asset("js/ajaxRequest.js") }}"></script>
<script>
$(document).ready(function () {

    var currentRoleFilter = '';

    var table = $('#usersTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: '{{ route("users.data") }}',
            type: 'GET',
            data: function (d) { d.role_filter = currentRoleFilter; },
            error: function (xhr) { console.error(xhr.status, xhr.responseText); }
        },
        columns: [
            {
                data: 'name', name: 'name',
                render: function (data, type, row) {
                    return row.google_id == null ? row.name : row.name + ' <span class="badge bg-info">SSO</span>';
                }
            },
            { data: 'email', name: 'email' },
            { data: 'department', name: 'department', orderable: false },
            { data: 'role', name: 'role' },
            { data: 'status', name: 'status' },
            { data: 'action', name: 'action', orderable: false, searchable: false },
        ],
        pageLength: 10,
        lengthMenu: [[10, 25, 50, 100], [10, 25, 50, 100]],
        dom: 'lBfrtip',
        buttons: [
            { extend: 'copy', text: 'Copy', className: 'btn btn-sm btn-secondary' },
            { extend: 'excel', text: 'Excel', title: 'Users', className: 'btn btn-sm btn-secondary' },
        ],
        order: [[0, 'desc']],
        language: {
            processing: '<div style="text-align:center;"><i class="fa fa-spinner fa-spin fa-2x"></i><br><span style="margin-top:10px;display:block;">Loading...</span></div>',
            emptyTable: "No users found",
            zeroRecords: "No matching users found",
            lengthMenu: "Show _MENU_ entries",
            info: "Showing _START_ to _END_ of _TOTAL_ entries",
            infoEmpty: "Showing 0 to 0 of 0 entries",
            infoFiltered: "(filtered from _MAX_ total entries)",
            search: "Search:",
            paginate: { first: "First", last: "Last", next: "Next", previous: "Previous" }
        },
        drawCallback: function () {
            moveControls();
            // loadModalsForVisibleUsers();
        },
        initComplete: function () {
            var inp = $('#usersTable_filter input');
            inp.unbind();
            var t;
            inp.on('input', function () {
                var v = $(this).val();
                clearTimeout(t);
                t = setTimeout(function () { table.search(v).draw(); }, 500);
            });
        },
        rowCallback: function (row, data) {
            $(row).find('#editUserBtn').on('click', function () {
                console.log(data);
                
                $('#editUserModal').modal('show');
                $("[name='id']").val(data.user_id);
                $("[name='name']").val(data.name);
                $("[name='email']").val(data.email);
                $("[name='department']").val(data.department_id).trigger('chosen:updated');
                $("[name='role']").val(data.role).trigger('chosen:updated');
            });
            $(row).find('.change-pass').on('click', function () {
                $('#change_pass').modal('show');
                $("[name='user_id']").val(data.user_id);
            });
            $(row).find("#accessControlBtn"+data.user_id).on("click", function() {
                console.log(data);
                $("#accessControl").modal("show")
                $("#accessControl .modal-title").text(data.name)
                
            })
        }
    });

    function moveControls() {
        var wrapper = $('#usersTable_wrapper');

        var length = wrapper.find('.dataTables_length');
        if (length.length) $('#table-length-control').empty().append(length.detach());

        var filter = wrapper.find('.dataTables_filter');
        if (filter.length) {
            var inp = filter.find('input');
            var hasFocus = inp.is(':focus');
            var curPos = inp[0] ? inp[0].selectionStart : null;
            $('#table-filter-control').empty().append(filter.detach());
            if (hasFocus) {
                var newInp = $('#usersTable_filter input');
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

    $('#roleFilter').on('change', function () {
        currentRoleFilter = $(this).val();
        table.ajax.reload();
    });

    setTimeout(function () { moveControls(); }, 100);
    $(window).on('resize', function () { moveControls(); });

    // function loadModalsForVisibleUsers() {
    //     var data = table.rows({ page: 'current' }).data();
    //     var userIds = [];
    //     data.each(function (row) {
    //         if (row.user_id) userIds.push(row.user_id);
    //     });
    //     if (userIds.length > 0) {
    //         $.ajax({
    //             url: '{{ route("users.modals") }}',
    //             type: 'POST',
    //             data: { user_ids: userIds, _token: '{{ csrf_token() }}' },
    //             success: function (response) { $('#modalsContainer').html(response); },
    //             error: function (xhr) { console.error('Error loading modals:', xhr.responseText); }
    //         });
    //     }
    // }

    $('#newAccountForm').on('submit', function (e) {
        e.preventDefault();
        var form = $(this);
        var btn = $('#createAccountBtn');
        btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Saving...');
        $.ajax({
            type: 'POST',
            url: form.attr('action'),
            data: form.serialize(),
            success: function () {
                swal("Success!", "Account created successfully!", "success");
                $('#new_account').modal('hide');
                form[0].reset();
                form.find('.cat').trigger('chosen:updated');
                table.ajax.reload(null, false);
                btn.prop('disabled', false).html('Submit');
            },
            error: function (xhr) {
                btn.prop('disabled', false).html('Submit');
                var message = 'Something went wrong.';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    message = xhr.responseJSON.message;
                }
                swal("Error!", message, "error");
            }
        });
    });

    $("#EditUserForm").on("submit", function(e) {
        e.preventDefault()
        var form = $(this)
        var btn = $("#EditUpdate")

        ajaxRequest({
            type:"POST",
            url:"{{ url('/users/edit-user') }}",
            data: form.serialize(),
            beforeSend: function() {
                btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Updating...');
            },
            success: function(response) {
                if (response.status == "error") {
                    $('#EditUserForm .form-control').removeClass('is-invalid is-valid');
                    $('#EditUserForm .invalid-feedback').text('');
                    
                    $.each(response.errors, function (key, value) {
                        let input = $('[name="' + key + '"]');
                        input.addClass('is-invalid');
                        input.next('.invalid-feedback').text(value[0]);
                    });
                }
                else {
                    swal("Success", response.message, response.status);
                    form[0].reset();
                    form.find('.cat').trigger('chosen:updated');
                    table.ajax.reload(null, false);
                }
            },
            complete: function(){
                btn.prop('disabled', false).html('Submit');
                $('#editUserModal').modal('hide');
            },
            error: function (xhr) {
                btn.prop('disabled', false).html('Submit');
                var message = 'Something went wrong.';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    message = xhr.responseJSON.message;
                }
                swal("Error!", message, "error");
            }
        })
    })

    $(document).on('click', '.deactivate-user', function (e) {
        e.preventDefault();
        var id = $(this).data('id');
        swal({
            title: "Are you sure?",
            text: "This user will be deactivated!",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Yes, deactivate it!",
            closeOnConfirm: false
        }, function () {
            $.ajax({
                type: 'POST',
                url: '{{ url("users/deactivate-user") }}',
                data: { id: id },
                headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                success: function () {
                    swal("Deactivated!", "User is now deactivated.", "success");
                    table.ajax.reload(null, false);
                },
                error: function () {
                    swal("Error!", "Something went wrong.", "error");
                }
            });
        });
    });

    $(document).on('click', '.activate-user', function (e) {
        e.preventDefault();
        var id = $(this).data('id');
        swal({
            title: "Are you sure?",
            text: "This user will be activated!",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Yes, activate it!",
            closeOnConfirm: false
        }, function () {
            $.ajax({
                type: 'POST',
                url: '{{ url("users/activate-user") }}',
                data: { id: id },
                headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                success: function () {
                    swal("Activated!", "User is now activated.", "success");
                    table.ajax.reload(null, false);
                },
                error: function () {
                    swal("Error!", "Something went wrong.", "error");
                }
            });
        });
    });

    $("#changePasswordForm").on("submit", function(e) {
        e.preventDefault()

        var form = $(this).serialize()
        var btn = $("#ChangePasswordBtn")

        ajaxRequest({
            type:"POST",
            url:"{{ url('users/change-password') }}",
            data: form,
            beforeSend: function() {
                btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Changing...');
            },
            success: function(response) {
                if (response.status == "error") {
                    $('#changePasswordForm .form-control').removeClass('is-invalid is-valid');
                    $('#changePasswordForm .invalid-feedback').text('');
                    
                    $.each(response.errors, function (key, value) {
                        let input = $('[name="' + key + '"]');
                        input.addClass('is-invalid');
                        input.next('.invalid-feedback').text(value[0]);
                    });
                }
                else {
                    swal("Success", response.message, response.status);
                    $("#changePasswordForm").trigger("reset")
                    table.ajax.reload(null, false);
                    $("#change_pass").modal("hide")
                }
            },
            complete: function() {
                btn.prop('disabled', false).text('Change');
            }
        })
    })

    // $(document).on('hidden.bs.modal', '#new_account, [id^="editUser"], [id^="change_pass"]', function () {
    //     table.ajax.reload(null, false);
    // });

    $('.cat').chosen({ width: "100%" });
});
</script>
@endsection