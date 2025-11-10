@extends('layouts.header')
@section('css')
<link href="{{ asset('login_css/css/plugins/chosen/bootstrap-chosen.css') }}" rel="stylesheet">
<link href="{{ asset('login_css/css/plugins/dataTables/datatables.min.css') }}" rel="stylesheet">
<link href="{{ asset('login_css/css/plugins/sweetalert/sweetalert.css') }}" rel="stylesheet">

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
        color: #1976d2;
    }

    .dashboard-card.active .icon-circle {
        background: #e8f5e9;
        color: #388e3c;
    }

    .dashboard-card.inactive .icon-circle {
        background: #ffebee;
        color: #d32f2f;
    }

    .dashboard-card.no-dco .icon-circle {
        background: #fff3e0;
        color: #f57c00;
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

    .table-section {
        background: white;
        border-radius: 10px;
        padding: 25px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        margin-bottom: 30px;
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

    .btn-action {
        padding: 6px 12px;
        border-radius: 4px;
        font-size: 13px;
        border: none;
        cursor: pointer;
        transition: all 0.3s;
        text-decoration: none;
        display: inline-block;
        margin-right: 4px;
    }

    .btn-action.edit {
        background: #2196F3;
        color: white;
    }

    .btn-action.edit:hover {
        background: #1976D2;
        color: white;
    }

    .btn-action.activate {
        background: #4CAF50;
        color: white;
    }

    .btn-action.activate:hover {
        background: #388E3C;
        color: white;
    }

    .badge-labels {
        padding: 5px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
        display: inline-block;
        margin: 2px;
    }

    .badge-labels.primary {
        background: #e3f2fd;
        color: #1976d2;
    }

    .badge-labels.danger {
        background: #ffebee;
        color: #d32f2f;
    }

    .badge-labels.success {
        background: #e8f5e9;
        color: #388e3c;
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
</style>
@endsection

@section('content')
@include('error')

<div class="row mb-4 dashboard-header">
    <div class="col-12">
        <h4 class="mb-0">Document Control Officers</h4>
        <p class="text-muted mb-0">Manage DCO and department assignments</p>
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-xl-3 col-md-6">
        <div class="dashboard-card total">
            <div class="icon-circle">
                <i class="fa fa-users"></i>
            </div>
            <h2>{{count($users)}}</h2>
            <p>Total DCO</p>
        </div>
    </div>
    
    <div class="col-xl-3 col-md-6">
        <div class="dashboard-card active">
            <div class="icon-circle">
                <i class="fa fa-check-circle"></i>
            </div>
            <h2>{{count($users->where('status',""))}}</h2>
            <p>Active</p>
        </div>
    </div>
    
    <div class="col-xl-3 col-md-6">
        <div class="dashboard-card inactive">
            <div class="icon-circle">
                <i class="fa fa-times-circle"></i>
            </div>
            <h2>{{count($users->where('status','Deactivated'))}}</h2>
            <p>Deactivated</p>
        </div>
    </div>
    
    <div class="col-xl-3 col-md-6">
        <div class="dashboard-card no-dco">
            <div class="icon-circle">
                <i class="fa fa-exclamation-triangle"></i>
            </div>
            <h2>{{count($departments->where('dco_count','=',0))}}</h2>
            <p>Departments No DCO</p>
        </div>
    </div>
</div>

<div class="table-section">
    <div class="section-header">
        <h5 class="section-title">Departments</h5>
    </div>

    <div class="table-container">
        <table class="modern-table tables">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Code</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($departments as $department)
                <tr>
                    <td>{{$department->name}}</td>
                    <td>{{$department->code}}</td>
                    <td>
                        @if(count($department->dco) == 0) 
                            <span class="badge-labels danger">No DCO</span>  
                        @else 
                            @foreach($department->dco as $dco) 
                                <span class="badge-labels primary">{{$dco->user->name}}</span>
                            @endforeach 
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<div class="table-section">
    <div class="section-header">
        <h5 class="section-title">Document Control Officers</h5>
    </div>

    <div class="table-container">
        <table class="modern-table tables">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Departments</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($users as $user)
                <tr>
                    <td>{{$user->name}}</td>
                    <td>
                        @if(count($user->dco) == 0) 
                            <span class="badge-labels danger">No assigned Department</span> 
                        @else 
                            @foreach($user->dco as $dco) 
                                <span class="badge-labels primary">{{$dco->department->name}}</span> 
                            @endforeach 
                        @endif
                    </td>
                    <td id='statususer{{$user->id}}'>
                        @if($user->status) 
                            <span class="badge-labels danger">Inactive</span>  
                        @else 
                            <span class="badge-labels success">Active</span> 
                        @endif
                    </td>
                    <td data-id='{{$user->id}}' id='actionuser{{$user->id}}'>
                        <div class="dropdown">
                            <button class="btn btn-sm btn-outline-secondary" type="button" id="userDropdown{{$user->id}}" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="ri-more-2-fill"></i>
                            </button>
                            <ul class="dropdown-menu" aria-labelledby="userDropdown{{$user->id}}">
                                @if($user->status)
                                    <li>
                                        <button class="dropdown-item activate-user" data-bs-toggle="modal" data-bs-target="#editUser{{$user->id}}" id='{{$user->id}}'>
                                            <i class="fa fa-user-o me-2"></i>Activate
                                        </button>
                                    </li>
                                @else
                                    <li>
                                        <button class="dropdown-item edit" data-bs-toggle="modal" data-bs-target="#edit{{$user->id}}">
                                            <i class="fa fa-edit me-2"></i>Edit Departments
                                        </button>
                                    </li>
                                @endif
                            </ul>
                        </div>
                    </td>
                </tr>
                @include('edit_department_dco') 
                @endforeach
            </tbody>
        </table>
    </div>
</div>

@endsection
@section('js')
<script src="{{ asset('login_css/js/plugins/dataTables/datatables.min.js')}}"></script>
<script src="{{ asset('login_css/js/plugins/chosen/chosen.jquery.js') }}"></script>
<script src="{{ asset('login_css/js/plugins/sweetalert/sweetalert.min.js') }}"></script>

<script>
    $(document).ready(function(){
        $('.deactivate-user').click(function () {
        
        var id = this.id;
            swal({
                title: "Are you sure?",
                text: "This user will be deactivated!",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "Yes, deactivated it!",
                closeOnConfirm: false
            }, function (){
                $.ajax({
                    dataType: 'json',
                    type:'POST',
                    url:  '{{url("deactivate-user")}}',
                    data:{id:id},
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                }).done(function(data){
                    console.log(data);
                    swal("Deactivated!", "User is now deactivated.", "success");
                    location.reload();
                }).fail(function(data)
                {
                    
                    swal("Deactivated!", "User is now deactivated.", "success");
                location.reload();
                });
            });
        });
        $('.activate-user').click(function () {
        
        var id = this.id;
            swal({
                title: "Are you sure?",
                text: "This user will be activated!",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "Yes, Activated it!",
                closeOnConfirm: false
            }, function (){
                $.ajax({
                    dataType: 'json',
                    type:'POST',
                    url:  '{{url("activate-user")}}',
                    data:{id:id},
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                }).done(function(data){
                    console.log(data);
                    swal("Activated!", "User is now activated.", "success");
                    location.reload();
                }).fail(function(data)
                {
                    
                    swal("Activated!", "User is now activated.", "success");
                location.reload();
                });
            });
        });
        
        $('.cat').chosen({width: "100%"});
        $('.tables').DataTable({
            pageLength: 25,
            responsive: true,
            dom: '<"html5buttons"B>lfrtip',
            buttons: [
                { extend: 'copy'},
                {extend: 'csv'},
                {extend: 'excel', title: 'ExampleFile'},
                {extend: 'pdf', title: 'ExampleFile'},

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

    });

</script>
@endsection