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

@include('users.access_control_modal')

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

var currentAcUserId = null;

$(document).on('click', '[id^="accessControlBtn"]', function (e) {
    e.preventDefault();
    var userId = $(this).data('user-id');
    var userName = $(this).data('user-name');
    var userRole = $(this).data('user-role');

    currentAcUserId = userId;
    $('#acModalTitle').text(userName || 'Access Control');
    $('#acModalRole').text(userRole || '');
    $('#acModalBody').html(`
        <div class="text-center py-5">
            <i class="ri-loader-4-line fs-1 text-muted spin-anim"></i>
            <p class="text-muted mt-2">Loading permissions...</p>
        </div>
    `);
    $('#accessControlModal').modal('show');

    $.get('{{ url("/users/access-control") }}/' + userId + '/json', function (res) {
        renderAcModal(res.permissions, res.userPermissions);
    });
});

function renderAcModal(permissions, userPermissions) {
    var groupOrder = [
        { key: 'dashboard', label: 'Dashboard', icon: 'ri-dashboard-2-line' },
        { key: 'monitoring', label: 'Monitoring', icon: 'ri-bar-chart-box-line' },
        { key: 'files', label: 'My Files', icon: 'ri-edit-line' },
        { key: 'document_approvals',label: 'Document Approvals',  icon: 'ri-checkbox-line' },
        { key: 'access_request', label: 'Access Requests', icon: 'ri-key-line' },
        { key: 'personal', label: 'Personal Documents', icon: 'ri-folder-2-line' },
        { key: 'share_with_me', label: 'Shared with Me', icon: 'ri-folder-received-line' },
        { key: 'share_with_others', label: 'Shared with Others',  icon: 'ri-share-line' },
        { key: 'permits_and_license', label: 'Permits & Licenses', icon: 'ri-file-shield-line' },
        { key: 'approver_stamp', label: 'Approver Stamp', icon: 'mdi mdi-stamper' },
        { key: 'users', label: 'Users', icon: 'ri-group-line' },
        { key: 'roles', label: 'Roles', icon: 'ri-shield-check-line' },
        { key: 'system_configuration', label: 'System Configuration', icon: 'ri-settings-3-line' },
        { key: 'reports', label: 'Reports', icon: 'mdi mdi-file-chart' },
    ];

    var serverGroups = {};
    $.each(permissions, function (module, actions) {
        serverGroups[module] = { actions: actions };
    });

    var orderedKeys = [];
    $.each(groupOrder, function (_, g) {
        if (serverGroups[g.key]) orderedKeys.push(g.key);
    });
    $.each(serverGroups, function (key) {
        if (orderedKeys.indexOf(key) === -1) orderedKeys.push(key);
    });

    var iconMap = {};
    $.each(groupOrder, function (_, g) { iconMap[g.key] = g.icon; });

    var labelMap = {};
    $.each(groupOrder, function (_, g) { labelMap[g.key] = g.label; });

    var html = '<div class="ac-groups">';

    $.each(orderedKeys, function (idx, module) {
        var actions = serverGroups[module].actions;
        var modLabel = labelMap[module] || module.replace(/_/g, ' ').replace(/\b\w/g, function(c){ return c.toUpperCase(); });
        var icon = iconMap[module] || 'ri-settings-line';
        var safeId = 'acMod_' + module.replace(/\W/g, '_');
        var collapseId = 'acCollapse_' + module.replace(/\W/g, '_');

        var hasChecked = false;
        $.each(actions, function (action, id) {
            if (userPermissions.indexOf(id) !== -1) hasChecked = true;
        });

        html += `
        <div class="ac-group mb-2">
            <button class="ac-group-header w-100 d-flex align-items-center gap-2 px-3 py-2 border-0 bg-transparent text-start"
                    type="button"
                    data-bs-toggle="collapse"
                    data-bs-target="#${collapseId}"
                    aria-expanded="${hasChecked ? 'true' : 'false'}"
                    aria-controls="${collapseId}">
                <i class="${icon} fs-5 text-muted ac-group-icon"></i>
                <span class="fw-semibold small flex-grow-1" style="color: var(--bs-body-color);">${modLabel}</span>
                <span class="ac-checked-count badge bg-light text-muted me-1 small" id="badge_${safeId}"></span>
                <div class="form-check form-check-inline mb-0 me-1" onclick="event.stopPropagation()">
                    <input class="form-check-input" type="checkbox" id="selectAll_${safeId}"
                        onchange="acToggleAll('${safeId}', this)">
                    <label class="form-check-label small text-muted" for="selectAll_${safeId}">All</label>
                </div>
                <i class="ri-arrow-down-s-line ac-chevron text-muted fs-5"></i>
            </button>

            <div class="collapse ${hasChecked ? 'show' : ''}" id="${collapseId}">
                <div class="px-3 pb-3 pt-1" id="${safeId}">
                    <div class="row g-2">`;

        $.each(actions, function (action, id) {
            var checked = userPermissions.indexOf(id) !== -1;
            var label   = action.replace(/_/g, ' ').replace(/\b\w/g, function(c){ return c.toUpperCase(); });
            html += `
                        <div class="col-6 col-md-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="permission[]"
                                    value="${id}" id="perm_${id}" ${checked ? 'checked' : ''}
                                    onchange="acSyncAll('${safeId}')">
                                <label class="form-check-label small" for="perm_${id}">${label}</label>
                            </div>
                        </div>`;
        });

        html += `   </div>
                </div>
            </div>
        </div>`;
    });

    html += '</div>';

    if (!document.getElementById('ac-group-styles')) {
        var style = document.createElement('style');
        style.id  = 'ac-group-styles';
        style.textContent = `
            .ac-group { border: 1px solid #e9ecef; border-radius: 8px; overflow: hidden; }
            .ac-group-header { border-radius: 8px; transition: background .15s; cursor: pointer; }
            .ac-group-header:hover { background: #f8f9fa !important; }
            .ac-group-header[aria-expanded="true"] { background: #f8f9fa !important; border-bottom: 1px solid #e9ecef; border-radius: 8px 8px 0 0; }
            .ac-chevron { transition: transform .2s ease; flex-shrink: 0; }
            .ac-group-header[aria-expanded="true"] .ac-chevron { transform: rotate(180deg); }
        `;
        document.head.appendChild(style);
    }

    $('#acModalBody').html(html);

    $.each(orderedKeys, function (_, module) {
        var safeId = 'acMod_' + module.replace(/\W/g, '_');
        acSyncAll(safeId);
    });
}

window.acToggleAll = function (safeId, el) {
    $('#' + safeId + ' [name="permission[]"]').each(function () {
        $(this).prop('checked', el.checked);
        $(this).closest('.perm-chip').toggleClass('checked', el.checked);
    });
};

window.acSyncAll = function (safeId) {
    var total = $('#' + safeId + ' [name="permission[]"]').length;
    var checked = $('#' + safeId + ' [name="permission[]"]:checked').length;

    $('#selectAll_' + safeId).prop('checked', total > 0 && total === checked);
    $('#selectAll_' + safeId).prop('indeterminate', checked > 0 && checked < total);

    var badge = $('#badge_' + safeId);
    if (checked > 0) {
        badge.text(checked + '/' + total).removeClass('bg-light text-muted').addClass('bg-primary text-white');
    } else {
        badge.text('').removeClass('bg-primary text-white').addClass('bg-light text-muted');
    }
};

$('#acSaveBtn').on('click', function () {
    if (!currentAcUserId) return;
    var btn = $(this);
    var perms = $('#acModalBody [name="permission[]"]:checked').map(function () { return $(this).val(); }).get();

    btn.prop('disabled', true).html('<i class="ri-loader-4-line me-1 spin-anim"></i> Saving...');
    ajaxRequest({
        type: 'POST',
        url: '{{ url("/users/access-control/update") }}',
        data: { user_id: currentAcUserId, permission: perms, _token: '{{ csrf_token() }}' },
        success: function (res) {
            swal('Saved!', res.message, 'success');
        },
        complete: function () {
            btn.prop('disabled', false).html('<i class="ri-save-line me-1"></i> Save Access');
        }
    });
});
</script>
@endsection