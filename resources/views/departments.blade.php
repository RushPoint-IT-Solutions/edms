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

    .dashboard-card.active .icon-circle {
        background: #d1e7dd;
        color: #0f5132;
    }

    .dashboard-card.inactive .icon-circle {
        background: #f8d7da;
        color: #842029;
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

    .departments-section {
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

    .btn-action {
        padding: 6px 12px;
        border-radius: 4px;
        font-size: 13px;
        border: none;
        cursor: pointer;
        transition: all 0.3s;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        margin-right: 4px;
        margin-bottom: 4px;
    }

    .btn-action.activate {
        background: #2196F3;
        color: white;
    }

    .btn-action.activate:hover {
        background: #1976D2;
    }

    .btn-action.edit {
        background: #0dcaf0;
        color: white;
    }

    .btn-action.edit:hover {
        background: #31d2f2;
    }

    .btn-action.deactivate {
        background: #dc3545;
        color: white;
    }

    .btn-action.deactivate:hover {
        background: #bb2d3b;
    }

    .btn-action i {
        font-size: 14px;
    }

    .badge-status {
        padding: 5px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
    }

    .badge-status.active {
        background: #e8f5e9;
        color: #4caf50;
    }

    .badge-status.inactive {
        background: #ffebee;
        color: #f44336;
    }

    .badge-info {
        background: #e3f2fd;
        color: #2196F3;
        padding: 4px 10px;
        border-radius: 4px;
        font-size: 11px;
        font-weight: 600;
        display: inline-block;
        margin: 2px;
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
        <h4 class="mb-0">Departments</h4>
        <p class="text-muted mb-0">Manage and track department information</p>
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-xl-4 col-md-6">
        <div class="dashboard-card total">
            <div class="icon-circle">
                <i class="ri-community-line"></i>
            </div>
            <h2>{{count($departments)}}</h2>
            <p>Total Departments</p>
        </div>
    </div>

    <div class="col-xl-4 col-md-6">
        <div class="dashboard-card active">
            <div class="icon-circle">
                <i class="ri-checkbox-circle-line"></i>
            </div>
            <h2>{{count($departments->where('status',null))}}</h2>
            <p>Active</p>
        </div>
    </div>

    <div class="col-xl-4 col-md-6">
        <div class="dashboard-card inactive">
            <div class="icon-circle">
                <i class="ri-close-circle-line"></i>
            </div>
            <h2>{{count($departments->where('status','!=',null))}}</h2>
            <p>Deactivated</p>
        </div>
    </div>
</div>

<div class="departments-section mb-5">
    <div class="section-header">
        <h5 class="section-title">All Departments</h5>
        <button class="btn-new" data-target="#new_department" data-toggle="modal" data-bs-toggle="modal" data-bs-target="#new_department" type="button">
            <i class="fa fa-plus"></i> New
        </button>
    </div>

     

    
    <div class="table-container">
        <table class="modern-table tables">
            <thead>
                <tr>
                    <th>Code</th>
                    <th>Name</th>
                    <th>Department Head</th>
                    <th>Permit Accountable Person</th>
                    <th>Approver</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($departments as $department)
                <tr>
                    <td><strong>{{$department->code}}</strong></td>
                    <td>{{$department->name}}</td>
                    <td>
                        @if($department->dep_head != null)
                            <span class="badge-info">{{$department->dep_head->name}}</span>
                        @else
                            <span class="text-muted">No Head</span>
                        @endif
                    </td>
                    <td>
                        @foreach($department->permit_accounts as $accountable)
                            <span class="badge-info">{{$accountable->user->name}}</span>
                        @endforeach
                    </td>
                    <td>
                        <small>
                            @foreach($department->approvers as $approver)
                                {{$approver->level}}. {{$approver->user->name}}<br>
                            @endforeach
                        </small>
                    </td>
                    <td>
                        @if($department->status)
                            <span class="badge-status inactive">Inactive</span>
                        @else
                            <span class="badge-status active">Active</span>
                        @endif
                    </td>
                    <td data-id='{{$department->id}}' id='actioncompanytd{{$department->id}}'>
                        @if($department->status)
                            <button class="btn-action activate activate-department" id='{{$department->id}}' title="Activate">
                                <i class="fa fa-check"></i> Activate
                            </button>
                        @else
                            <button class="btn-action edit" title='Edit' data-target="#editDepartment{{$department->id}}" data-toggle="modal" data-bs-toggle="modal" data-bs-target="#editDepartment{{$department->id}}">
                                <i class="fa fa-edit"></i> Edit
                            </button>
                            <button class="btn-action deactivate deactivate-department" id='{{$department->id}}' title='Deactivate'>
                                <i class="fa fa-trash"></i> Deactivate
                            </button>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

@include('new_department')
@foreach($departments as $department)
@include('edit_department')
@endforeach
@endsection

@section('js')
<script src="{{ asset('login_css/js/plugins/dataTables/datatables.min.js')}}"></script>
<script src="{{ asset('login_css/js/plugins/chosen/chosen.jquery.js') }}"></script>
<script src="{{ asset('login_css/js/plugins/sweetalert/sweetalert.min.js') }}"></script>

<script>
    $(document).ready(function(){
        $('.deactivate-department').click(function () {
            var id = this.id;
            swal({
                title: "Are you sure?",
                text: "This department will be deactivated!",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "Yes, deactivate it!",
                closeOnConfirm: false
            }, function (){
                $.ajax({
                    dataType: 'json',
                    type:'POST',
                    url:  '{{url("deactivate-department")}}',
                    data:{id:id},
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                }).done(function(data){
                    console.log(data);
                    swal("Deactivated!", "Department is now deactivated.", "success");
                    location.reload();
                }).fail(function(data){
                    swal("Deactivated!", "Department is now deactivated.", "success");
                    location.reload();
                });
            });
        });

        $('.activate-department').click(function () {
            var id = this.id;
            swal({
                title: "Are you sure?",
                text: "This department will be activated!",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "Yes, activate it!",
                closeOnConfirm: false
            }, function (){
                $.ajax({
                    dataType: 'json',
                    type:'POST',
                    url:  '{{url("activate-department")}}',
                    data:{id:id},
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                }).done(function(data){
                    console.log(data);
                    swal("Activated!", "Department is now activated.", "success");
                    location.reload();
                }).fail(function(data){
                    swal("Activated!", "Department is now activated.", "success");
                    location.reload();
                });
            });
        });
        
        $('.cat').chosen({width: "100%"});
        $('.locations').chosen({width: "100%"});
        $('.tables').DataTable({
            pageLength: 25,
            responsive: true,
            stateSave: true,
            dom: '<"html5buttons"B>lTfg<"bottom-controls"t<"info-paginate"ip>>', 
            buttons: [
                {extend: 'copy'},
                {extend: 'csv'},
                {extend: 'excel', title: 'Departments'},
                {extend: 'pdf', title: 'Departments'},
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