@extends('layouts.header')

@section('css')
<link href="{{ asset('login_css/css/plugins/chosen/bootstrap-chosen.css') }}" rel="stylesheet">
<link href="{{ asset('login_css/css/plugins/sweetalert/sweetalert.css') }}" rel="stylesheet">
<link href="{{ asset('login_css/css/plugins/dataTables/datatables.min.css') }}" rel="stylesheet">

<style>
    .btn-md {
        border: none !important;
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
        background: #e7f3ff;
        color: #0066cc;
    }

    .dashboard-card.active .icon-circle {
        background: #d1e7dd;
        color: #0f5132;
    }

    .dashboard-card.renewal .icon-circle {
        background: #fff3cd;
        color: #856404;
    }

    .dashboard-card.overdue .icon-circle {
        background: #f8d7da;
        color: #842029;
    }

    .dashboard-card.archived .icon-circle {
        background: #e9ecef;
        color: #495057;
    }

    .dashboard-card.inactive .icon-circle {
        background: #f1f3f5;
        color: #6c757d;
    }

    .dashboard-card h2 {
        font-size: 28px;
        font-weight: 700;
        margin: 0 0 4px 0;
        color: #2c3e50;
        line-height: 1;
    }

    .dashboard-card h2 a,
    .dashboard-card h2 input[type="submit"] {
        color: #2c3e50;
        text-decoration: none;
        background: none;
        border: none;
        padding: 0;
        cursor: pointer;
        font-size: 28px;
        font-weight: 700;
    }

    .dashboard-card h2 a:hover,
    .dashboard-card h2 input[type="submit"]:hover {
        color: #0d6efd;
    }

    .dashboard-card p {
        margin: 0;
        font-size: 13px;
        color: #6c757d;
        font-weight: 500;
    }

    .requests-section {
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
        line-height: 1.5;
        color: #6c757d;
    }

    .modern-table tbody td small hr {
        margin: 4px 0;
        border-color: #e9ecef;
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
        margin-bottom: 4px;
    }

    .btn-action:last-child {
        margin-right: 0;
    }

    .btn-action.btn-primary {
        background: #0d6efd;
        color: white;
    }

    .btn-action.btn-primary:hover {
        background: #0b5ed7;
    }

    .btn-action.btn-warning {
        background: #ffc107;
        color: #000;
    }

    .btn-action.btn-warning:hover {
        background: #ffcd39;
    }

    .btn-action.btn-info {
        background: #0dcaf0;
        color: white;
    }

    .btn-action.btn-info:hover {
        background: #31d2f2;
    }

    .btn-action.btn-danger {
        background: #dc3545;
        color: white;
    }

    .btn-action.btn-danger:hover {
        background: #bb2d3b;
    }

    .btn-action.btn-success {
        background: #198754;
        color: white;
    }

    .btn-action.btn-success:hover {
        background: #157347;
    }

    .btn-action i {
        font-size: 14px;
    }

    /* Status Badges */
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

    .badge-status.renewal {
        background: #fff3e0;
        color: #ff9800;
    }

    .badge-status.overdue {
        background: #ffebee;
        color: #f44336;
    }

    .badge-status.inactive {
        background: #ffebee;
        color: #f44336;
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

 
<div class="row mb-4">
    <div class="col-12">
        <h4 class="mb-0">Permits & Licenses</h4>
        <p class="text-muted mb-0">Manage and track all permits and licenses</p>
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-lg-2 col-md-4 col-sm-6">
        <div class="dashboard-card total">
            <div class="icon-circle">
                <i class="ri-file-list-3-line"></i>
            </div>
            <h2>
                <a href="{{url('permits')}}">{{$permits_count}}</a>
            </h2>
            <p>Total</p>
        </div>
    </div>
    <div class="col-lg-2 col-md-4 col-sm-6">
        <div class="dashboard-card active">
            <div class="icon-circle">
                <i class="ri-checkbox-circle-line"></i>
            </div>
            <form action="" method="get">
                <h2>
                    <input type="hidden" name="active_permits_filter" value="Active">
                    <input type="submit" value="{{$active_permits_count}}">
                </h2>
            </form>
            <p>Active</p>
        </div>
    </div>
    <div class="col-lg-2 col-md-4 col-sm-6">
        <div class="dashboard-card renewal">
            <div class="icon-circle">
                <i class="ri-refresh-line"></i>
            </div>
            <form action="" method="get">
                <h2>
                    <input type="hidden" name="renewal_filter" value="For Renewal">
                    <input type="submit" value="{{$for_renewal_count}}">
                </h2>
            </form>
            <p>For Renewal</p>
        </div>
    </div>
    <div class="col-lg-2 col-md-4 col-sm-6">
        <div class="dashboard-card overdue">
            <div class="icon-circle">
                <i class="ri-alert-line"></i>
            </div>
            <form method="GET">
                <h2>
                    <input type="hidden" name="overdue_filter" value="Overdue">
                    <input type="submit" value="{{$overdue_count}}">
                </h2>
            </form>
            <p>Overdue</p>
        </div>
    </div>
    <div class="col-lg-2 col-md-4 col-sm-6">
        <div class="dashboard-card archived">
            <div class="icon-circle">
                <i class="ri-archive-line"></i>
            </div>
            <h2>
                <a href="{{url('archive_permits')}}">{{count($archives)}}</a>
            </h2>
            <p>Archived</p>
        </div>
    </div>
    <div class="col-lg-2 col-md-4 col-sm-6">
        <div class="dashboard-card inactive">
            <div class="icon-circle">
                <i class="ri-close-circle-line"></i>
            </div>
            <form method="GET">
                <h2>
                    <input type="hidden" name="inactive_filter" value="Inactive">
                    <input type="submit" value="{{$inactive_permits_count}}">
                </h2>
            </form>
            <p>Inactive</p>
        </div>
    </div>
</div>

<div class="requests-section mb-5">
    <div class="section-header">
        <h5 class="section-title">Permits & Licenses</h5>
        <button class="btn-new" data-bs-toggle="modal" data-bs-target="#new_permit" type="button">
            <i class="fa fa-plus"></i> New
        </button>
    </div>

    <div class="table-container">
        <table class="modern-table tables">
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Description</th>
                    <th>Company</th>
                    <th>Department</th>
                    <th>Accountable Person</th>
                    <th>Date Uploaded</th>
                    <th>File</th>
                    <th>Type</th>
                    <th>Expiration Date</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($permits as $permit)
                <tr>
                    <td>{{$permit->title}}</td>
                    <td>{{$permit->description}}</td>
                    <td>{{$permit->company->name}}</td>
                    <td>{{$permit->department->name}}</td>
                    <td>
                        <small>
                            @foreach($permit->department->permit_accounts as $accountable)
                                {{$accountable->user->name}} <hr>
                            @endforeach
                        </small>
                    </td>
                    <td>{{date('M d, Y',strtotime($permit->created_at))}}</td>
                    <td>
                        <a href='{{url($permit->file)}}' target='_blank'>
                            <i class='fa fa-file'></i>
                        </a>
                    </td>
                    <td>{{$permit->type}}</td>
                    <td>@if($permit->expiration_date != null){{date('M d, Y',strtotime($permit->expiration_date))}}@endif</td>
                    <td>
                        @if($permit->status != null)
                            @if($permit->status == "Inactive")
                            <span class="badge-status inactive">{{$permit->status}}</span>
                            @endif
                        @else
                            @if($permit->expiration_date != null)
                                @if($permit->expiration_date < date("Y-m-d")) 
                                <span class="badge-status overdue">For Renewal (Overdue)</span> 
                                @elseif($permit->expiration_date < date('Y-m-d', strtotime("+3 months", strtotime(date('Y-m-d'))))) 
                                <span class="badge-status renewal">For Renewal</span> 
                                @else 
                                <span class="badge-status active">Active</span> 
                                @endif 
                            @endif
                        @endif
                    </td>
                    <td>
                        <button class="btn btn-primary btn-action" title="Upload" data-bs-toggle="modal" data-bs-target="#upload{{$permit->id}}">
                            <i class="fa fa-upload"></i>
                        </button>

                        @if((auth()->user()->role != "User") && (auth()->user()->role != "Department Head") && (auth()->user()->role != "Documents and Records Controller"))
                            <button class="btn btn-warning btn-action" title="Transfer Department" data-bs-toggle="modal" data-bs-target="#change{{$permit->id}}">
                                <i class="fa fa-users"></i>
                            </button>

                            <button class="btn btn-info btn-action" title="Change Types" data-bs-toggle="modal" data-bs-target="#changeType{{$permit->id}}">
                                <i class="fa fa-edit"></i>
                            </button>

                            @if($permit->status == null)
                                <form method="POST" action="{{ url('inactive-permits/'.$permit->id) }}" style="display: inline;">
                                    @csrf
                                    <button type="button" class="btn btn-danger btn-action inactiveBtn" title="Inactive Permits">
                                        <i class="fa fa-trash" style="font-size: 17px;"></i>
                                    </button>
                                </form>
                            @endif

                            @if($permit->status == "Inactive")
                                <form method="POST" action="{{ url('activate-permits/'.$permit->id) }}" style="display: inline;">
                                    @csrf
                                    <button type="button" class="btn btn-success btn-action activatePermitsBtn" title="Activate Permits">
                                        <i class="fa fa-check"></i>
                                    </button>
                                </form>
                            @endif
                        @endif
                    </td>
                </tr>
                @include('upload_permit')
                @include('transfer_department')
                @include('edit_type')
                @endforeach
            </tbody>
        </table>
    </div>
</div>

@include('new_permit')
@endsection

@section('js')
<script src="{{ asset('login_css/js/plugins/dataTables/datatables.min.js')}}"></script>
<script src="{{ asset('login_css/js/plugins/chosen/chosen.jquery.js') }}"></script>
<script src="{{ asset('login_css/js/plugins/sweetalert/sweetalert.min.js') }}"></script>

<script>
    $(document).ready(function(){
        
        $('.cat').chosen({width: "100%"});
        $('.locations').chosen({width: "100%"});
        $('.tables').DataTable({
            pageLength: 25,
            responsive: true,
            stateSave: true,
            sorting: false,
            dom: '<"html5buttons"B>lTfg<"bottom-controls"t<"info-paginate"ip>>', 
            buttons: [
                {extend: 'copy'},
                {extend: 'csv'},
                {extend: 'excel', title: 'Permits & Licenses'},
                {extend: 'pdf', title: 'Permits & Licenses'},
                {extend: 'print',
                 customize: function (win) {
                    $(win.document.body).addClass('white-bg');
                    $(win.document.body).css('font-size', '10px');
                    $(win.document.body).find('table')
                        .addClass('compact')
                        .css('font-size', 'inherit');
                 }
                }
            ]

        });

        $('.inactiveBtn').on('click', function() {
            swal({
                title: "Are you sure?",
                text: "This permits will be inactive!",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "Yes, Inactive it!",
                closeOnConfirm: false
            }, function (){
                $('.inactiveBtn').closest('form').submit()
            });
        })

        $('.activatePermitsBtn').on('click', function() {
            
            swal({
                title: "Are you sure?",
                text: "This permits will be activate!",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "Yes, Activate it!",
                closeOnConfirm: false
            }, function (){
                $('.activatePermitsBtn').closest('form').submit()
            });
        })
    });
</script>
@endsection