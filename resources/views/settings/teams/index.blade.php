@extends('layouts.header')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <h4 class="mb-0">Teams</h4>
        <p class="text-muted mb-0">Manage and track team information</p>
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-xl-4 col-md-6">
        <div class="dashboard-card total">
            <div class="icon-circle"><i class="ri-folder-line"></i></div>
            <h2 class="mb-0 font-weight-bold">{{ $totalTeams }}</h2>
            <p>Total Teams</p>
        </div>
    </div>
    <div class="col-xl-4 col-md-6">
        <div class="dashboard-card active">
            <div class="icon-circle"><i class="ri-checkbox-circle-line"></i></div>
            <h2 class="mb-0 font-weight-bold filter-btn" data-filter="Active" style="cursor:pointer;">{{ $activeTeams }}</h2>
            <p>Active</p>
        </div>
    </div>
    <div class="col-xl-4 col-md-6">
        <div class="dashboard-card inactive">
            <div class="icon-circle"><i class="ri-close-circle-line"></i></div>
            <h2 class="mb-0 font-weight-bold filter-btn" data-filter="Inactive" style="cursor:pointer;">{{ $inactiveTeams }}</h2>
            <p>Deactivated</p>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12 mb-5">
        <div class="card shadow-sm">
            <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
                <h5 class="mb-0">All Teams</h5>
                <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#new_team">
                    <i class="fa fa-plus"></i> New
                </button>
            </div>
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
                    <div class="d-flex align-items-center gap-2 flex-wrap">
                        <div id="table-length-control"></div>
                        <select id="statusFilter" class="form-select form-select-sm" style="width:auto;min-width:160px;">
                            <option value="">All Status</option>
                            <option value="Active">Active</option>
                            <option value="Inactive">Inactive</option>
                        </select>
                        <div id="table-filter-control"></div>
                    </div>
                    <div id="table-buttons-control"></div>
                </div>

                <div class="table-scroll-container">
                    <table class="table table-hover table-bordered" id="teamsTable">
                        <thead class="table-light">
                            <tr>
                                <th>Action</th>
                                <th>Team Name</th>
                                <th>Created By</th>
                                <th>Department</th>
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

@include('settings.teams.new')
@foreach($teams as $team)
    @include('settings.teams.edit')
@endforeach

@endsection

@section('js')
<script>
$(document).ready(function () {

    var currentFilter = '';

    var table = $('#teamsTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: '{{ route("teams.data") }}',
            type: 'GET',
            data: function (d) { d.status_filter = currentFilter; },
            error: function (xhr) { console.error(xhr.status, xhr.responseText); }
        },
        columns: [
            { data: 'action', orderable: false, searchable: false },
            { data: 'name', name: 'name' },
            { data: 'created_by', orderable: false, searchable: false },
            { data: 'department', orderable: false, searchable: false },
            { data: 'status', orderable: false, searchable: false },
        ],
        pageLength: 25,
        lengthMenu: [[10, 25, 50, 100], [10, 25, 50, 100]],
        dom: 'lBfrtip',
        buttons: [
            { extend: 'copy', text: 'Copy' },
            { extend: 'excel', text: 'Excel', title: 'Teams' },
        ],
        order: [[0, 'desc']],
        language: {
            processing: '<div style="text-align:center;"><i class="fa fa-spinner fa-spin fa-2x"></i><br><span style="margin-top:10px;display:block;">Loading...</span></div>',
            emptyTable: "No teams found",
            zeroRecords: "No matching teams found",
            lengthMenu: "Show _MENU_ entries",
            info: "Showing _START_ to _END_ of _TOTAL_ entries",
            infoEmpty: "Showing 0 to 0 of 0 entries",
            infoFiltered: "(filtered from _MAX_ total entries)",
            search: "Search:",
            paginate: { first: "First", last: "Last", next: "Next", previous: "Previous" }
        },
        drawCallback: function () { moveControls(); },
        initComplete: function () {
            var inp = $('#teamsTable_filter input');
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
        var wrapper = $('#teamsTable_wrapper');

        var length = wrapper.find('.dataTables_length');
        if (length.length) $('#table-length-control').empty().append(length.detach());

        var filter = wrapper.find('.dataTables_filter');
        if (filter.length) {
            var inp = filter.find('input');
            var hasFocus = inp.is(':focus');
            var curPos = inp[0] ? inp[0].selectionStart : null;
            $('#table-filter-control').empty().append(filter.detach());
            if (hasFocus) {
                var newInp = $('#teamsTable_filter input');
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

    $('#newTeamForm').on('submit', function (e) {
        e.preventDefault();
        var teamName = $('#team_name').val().trim();
        var department = $('#new_department_id').val();
        if (!teamName) { swal("Error!", "Please enter a team name", "error"); return; }
        var btn = $('#createTeamBtn');
        btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Creating...');
        $.ajax({
            type: 'POST',
            url: '{{ url("teams") }}',
            data: { team_name: teamName, department: department },
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            success: function () {
                swal("Success!", "Team created successfully!", "success");
                $('#new_team').modal('hide');
                table.ajax.reload();
                btn.prop('disabled', false).html('<i class="fa fa-save"></i> Create Team');
            },
            error: function (data) {
                btn.prop('disabled', false).html('<i class="fa fa-save"></i> Create Team');
                var message = data.responseJSON && data.responseJSON.message ? data.responseJSON.message : 'Something went wrong.';
                swal("Error!", message, "error");
            }
        });
    });

    $(document).on('submit', '.edit-team-form', function (e) {
        e.preventDefault();
        var form = $(this);
        var teamId = form.data('id');
        var teamName = form.find('input[name="team_name"]').val().trim();
        var department = form.find('select[name="department"]').val();
        if (!teamName) { swal("Error!", "Please enter a team name", "error"); return; }
        var btn = form.find('.update-team-btn');
        btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Updating...');
        $.ajax({
            type: 'POST',
            url: '{{ url("teams") }}/' + teamId,
            data: { _method: 'PUT', team_name: teamName, department: department },
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            success: function () {
                swal("Success!", "Team updated successfully!", "success");
                $('#editTeam' + teamId).modal('hide');
                table.ajax.reload();
                btn.prop('disabled', false).html('<i class="fa fa-save"></i> Update Team');
            },
            error: function (data) {
                btn.prop('disabled', false).html('<i class="fa fa-save"></i> Update Team');
                var message = data.responseJSON && data.responseJSON.message ? data.responseJSON.message : 'Something went wrong.';
                swal("Error!", message, "error");
            }
        });
    });

    $(document).on('click', '.deactivate-team', function () {
        var id = $(this).data('id');
        swal({
            title: "Are you sure?",
            text: "This team will be deactivated!",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Yes, deactivate it!",
            closeOnConfirm: false
        }, function () {
            $.ajax({
                type: 'POST',
                url: '{{ url("/teams/deactivate") }}',
                data: { id: id },
                headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                success: function () {
                    swal("Deactivated!", "Team is now deactivated.", "success");
                    location.reload();
                },
                error: function () { swal("Error!", "Something went wrong.", "error"); }
            });
        });
    });

    $(document).on('click', '.activate-team', function () {
        var id = $(this).data('id');
        swal({
            title: "Are you sure?",
            text: "This team will be activated!",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Yes, activate it!",
            closeOnConfirm: false
        }, function () {
            $.ajax({
                type: 'POST',
                url: '{{ url("/teams/activate") }}',
                data: { id: id },
                headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                success: function () {
                    swal("Activated!", "Team is now activated.", "success");
                    location.reload();
                },
                error: function () { swal("Error!", "Something went wrong.", "error"); }
            });
        });
    });

    $('#new_team').on('shown.bs.modal', function () {
        $(this).find('.select2-team').select2({
            dropdownParent: $(this),
            placeholder: 'Select department...',
            allowClear: true
        });
    });
    $('#new_team').on('hide.bs.modal', function () {
        $(this).find('.select2-team').select2('close');
    });

    @foreach($teams as $team)
    $('#editTeam{{ $team->id }}').on('shown.bs.modal', function () {
        var $select = $(this).find('.select2-team');
        if ($select.hasClass('select2-hidden-accessible')) { $select.select2('destroy'); }
        $select.select2({
            dropdownParent: $(this),
            placeholder: 'Select department...',
            allowClear: true
        });
    });
    $('#editTeam{{ $team->id }}').on('hide.bs.modal', function () {
        $(this).find('.select2-team').select2('close');
    });
    @endforeach

});
</script>
@endsection