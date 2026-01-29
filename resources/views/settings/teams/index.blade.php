@extends('layouts.header')

@section('css')
<link href="{{ asset('login_css/css/plugins/chosen/bootstrap-chosen.css') }}" rel="stylesheet">
<link href="{{ asset('login_css/css/plugins/sweetalert/sweetalert.css') }}" rel="stylesheet">
<link href="{{ asset('login_css/css/plugins/dataTables/datatables.min.css') }}" rel="stylesheet">

<style>
    .dashboard-header {
        margin-bottom: 30px;
    }

    .dashboard-card {
        background: white;
        border-radius: 10px;
        padding: 20px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        transition: all 0.3s;
        height: 100%;
        display: flex;
        flex-direction: column;
    }

    .dashboard-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.12);
    }

    .dashboard-card .icon-circle {
        width: 45px;
        height: 45px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 12px;
        font-size: 20px;
        flex-shrink: 0;
    }

    .dashboard-card.total .icon-circle {
        background: #e3f2fd;
        color: #2196F3;
    }

    .dashboard-card h2 {
        font-size: 28px;
        font-weight: 700;
        margin: 0 0 4px 0;
        color: #2c3e50;
        line-height: 1;
    }

    .dashboard-card p {
        margin: 0;
        font-size: 13px;
        color: #6c757d;
        font-weight: 500;
    }

    .teams-section {
        background: white;
        border-radius: 10px;
        padding: 25px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }

    .section-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 25px;
        flex-wrap: wrap;
        gap: 15px;
    }

    .section-title {
        font-size: 20px;
        font-weight: 600;
        margin: 0;
        color: #2c3e50;
    }

    .btn-new {
        background: #800000;
        color: white;
        border: none;
        padding: 8px 16px;
        border-radius: 4px;
        font-size: 14px;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.3s;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .btn-new:hover {
        background: #6B0000;
        color: white;
    }

    .btn-new i {
        font-size: 14px;
    }

    .table-container {
        overflow-x: auto;
    }

    .modern-table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
    }

    .modern-table thead th {
        background: #f8f9fa;
        color: #495057;
        font-weight: 600;
        font-size: 13px;
        text-transform: uppercase;
        padding: 15px 12px;
        border-bottom: 2px solid #8B0000;
        white-space: nowrap;
    }

    .modern-table tbody td {
        padding: 12px;
        border-bottom: 1px solid #e9ecef;
        vertical-align: middle;
        font-size: 14px;
    }

    .modern-table tbody tr:hover {
        background: #f8f9fa;
    }

    .modern-table tbody td small {
        display: block;
        line-height: 1.6;
        color: #495057;
    }

    .dataTables_wrapper {
        padding-top: 20px;
    }

    .dataTables_wrapper .dataTables_length {
        float: right;
        margin-bottom: 15px;
    }

    .dataTables_wrapper .dataTables_length select {
        padding: 6px 30px 6px 10px;
        border: 1px solid #dee2e6;
        border-radius: 4px;
        margin: 0 5px;
    }

    .dataTables_wrapper .dataTables_filter {
        float: left;
        margin-bottom: 15px;
    }

    .dataTables_wrapper .dataTables_filter input {
        padding: 6px 12px;
        border: 1px solid #dee2e6;
        border-radius: 4px;
        margin-left: 5px;
    }

    .dataTables_wrapper .dataTables_info {
        float: left;
        padding-top: 8px;
    }

    .dataTables_wrapper .dataTables_paginate {
        float: right;
        margin-top: 15px;
    }

    .dataTables_wrapper .dataTables_paginate .paginate_button {
        padding: 6px 12px;
        margin: 0 2px;
        border: 1px solid #dee2e6;
        border-radius: 4px;
        background: white;
        cursor: pointer;
    }

    .dataTables_wrapper .dataTables_paginate .paginate_button:hover {
        background: #f8f9fa;
    }

    .dataTables_wrapper .dataTables_paginate .paginate_button.current {
        background: #8B0000;
        color: white !important;
        border-color: #8B0000;
    }

    div.dt-buttons {
        float: right;
        margin-bottom: 15px;
        margin-right: 10px;
    }

    .dt-button {
        background: white !important;
        border: 1px solid #dee2e6 !important;
        color: #495057 !important;
        padding: 6px 12px !important;
        border-radius: 4px !important;
        margin-right: 5px !important;
        font-size: 13px !important;
    }

    .dt-button:hover {
        background: #f8f9fa !important;
        border-color: #8B0000 !important;
    }

    .dataTables_wrapper:after {
        content: "";
        display: table;
        clear: both;
    }

    .table-container {
        clear: both;
    }

    @media (max-width: 768px) {
        .dashboard-card {
            margin-bottom: 15px;
        }
    }
</style>
@endsection

@section('content')
@include('error')

<div class="row mb-4 dashboard-header">
    <div class="col-12">
        <h4 class="mb-0">Teams</h4>
        <p class="text-muted mb-0">Manage and track team information</p>
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-3">
        <div class="dashboard-card total">
            <div class="icon-circle">
                <i class="ri-folder-line"></i>
            </div>
            <h2 id="totalTeamsCount">{{ $totalTeams ?? 0 }}</h2>
            <p>Total Teams</p>
        </div>
    </div>
</div>

<div class="teams-section mb-5">
    <div class="section-header">
        <h5 class="section-title">All Teams</h5>
        <button class="btn-new" data-bs-toggle="modal" data-bs-target="#new_team" type="button">
            <i class="fa fa-plus"></i> New Team
        </button>
    </div>

    <div class="table-container">
        <table class="modern-table tables" id="teamsTable">
            <thead>
                <tr>
                    <th>Team Name</th>
                    <th>Created By</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($teams as $team)
                <tr id="team-row-{{ $team->id }}">
                    <td>
                        <div style="display: flex; align-items: center; gap: 10px;">
                            <strong class="team-name">{{ $team->name }}</strong>
                        </div>
                    </td>
                    <td>
                        <div>
                            {{ $team->creator ? $team->creator->name : 'Unknown' }}
                            <br>
                            <small class="text-muted">{{ $team->created_at->format('M d, Y') }}</small>
                        </div>
                    </td>
                    <td>
                        <div class="dropdown">
                            <button class="btn btn-sm btn-outline-secondary" type="button" id="teamDropdown{{ $team->id }}" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="ri-more-2-fill"></i>
                            </button>
                            <ul class="dropdown-menu" aria-labelledby="teamDropdown{{ $team->id }}">
                                <li>
                                    <button class="dropdown-item" data-bs-toggle="modal" data-bs-target="#editTeam{{ $team->id }}">
                                        <i class="ri-pencil-line me-2"></i>Edit
                                    </button>
                                </li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <button class="dropdown-item text-danger delete-team" data-id='{{ $team->id }}'>
                                        <i class="ri-delete-bin-line me-2"></i>Delete
                                    </button>
                                </li>
                            </ul>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

@include('settings.teams.new')
@include('settings.teams.edit')
@endsection

@section('js')
<script src="{{ asset('login_css/js/plugins/dataTables/datatables.min.js')}}"></script>
<script src="{{ asset('login_css/js/plugins/chosen/chosen.jquery.js') }}"></script>
<script src="{{ asset('login_css/js/plugins/sweetalert/sweetalert.min.js') }}"></script>

<script>
    $(document).ready(function(){
        var table = $('.tables').DataTable({
            pageLength: 25,
            responsive: true,
            stateSave: true,
            deferRender: true,
            dom: '<"html5buttons"B>lTfg<"bottom-controls"t<"info-paginate"ip>>', 
            buttons: [
                {extend: 'copy'},
                {extend: 'csv'},
                {extend: 'excel', title: 'Teams'},
                {extend: 'pdf', title: 'Teams'},
                {extend: 'print',
                 customize: function (win){
                    $(win.document.body).addClass('white-bg');
                    $(win.document.body).css('font-size', '10px');
                    $(win.document.body).find('table')
                        .addClass('compact')
                        .css('font-size', 'inherit');
                 }
                }
            ]
        });

        $('#newTeamForm').on('submit', function(e) {
            e.preventDefault();
            
            var teamName = $('#team_name').val().trim();
            
            if (!teamName) {
                swal("Error!", "Please enter a team name", "error");
                return;
            }
            
            var btn = $('#createTeamBtn');
            btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Creating...');
            
            $.ajax({
                dataType: 'json',
                type: 'POST',
                url: '{{ url("teams") }}',
                data: {team_name: teamName},
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            }).done(function(data){
                swal("Success!", "Team created successfully!", "success");
                $('#new_team').modal('hide');
                location.reload();
            }).fail(function(data){
                btn.prop('disabled', false).html('<i class="fa fa-save"></i> Create Team');
                var message = 'Something went wrong.';
                if(data.responseJSON && data.responseJSON.message) {
                    message = data.responseJSON.message;
                }
                swal("Error!", message, "error");
            });
        });

        // UPDATE TEAM
        $('.edit-team-form').on('submit', function(e) {
            e.preventDefault();
            
            var form = $(this);
            var teamId = form.data('id');
            var teamName = form.find('input[name="team_name"]').val().trim();
            
            if (!teamName) {
                swal("Error!", "Please enter a team name", "error");
                return;
            }
            
            // Disable button and show loading
            var btn = form.find('.update-team-btn');
            btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Updating...');
            
            $.ajax({
                dataType: 'json',
                type: 'POST',
                url: '{{ url("teams") }}/' + teamId,
                data: {
                    _method: 'PUT',
                    team_name: teamName
                },
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            }).done(function(data){
                swal("Success!", "Team updated successfully!", "success");
                $('#editTeam' + teamId).modal('hide');
                location.reload();
            }).fail(function(data){
                btn.prop('disabled', false).html('<i class="fa fa-save"></i> Update Team');
                var message = 'Something went wrong.';
                if(data.responseJSON && data.responseJSON.message) {
                    message = data.responseJSON.message;
                }
                swal("Error!", message, "error");
            });
        });

        // DELETE TEAM
        $(document).on('click', '.delete-team', function () {
            var id = $(this).data('id');
            
            swal({
                title: "Are you sure?",
                text: "This team will be permanently deleted!",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "Yes, delete it!",
                closeOnConfirm: false,
                showLoaderOnConfirm: true
            }, function (){
                $.ajax({
                    dataType: 'json',
                    type: 'POST',
                    url: '{{ url("teams") }}/' + id,
                    data: {_method: 'DELETE'},
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                }).done(function(data){
                    swal("Deleted!", "Team deleted successfully!", "success");
                    location.reload();
                }).fail(function(data){
                    swal("Error!", "Something went wrong.", "error");
                });
            });
        });

        $('.cat, .locations').chosen({width: "100%"});
    });
</script>
@endsection