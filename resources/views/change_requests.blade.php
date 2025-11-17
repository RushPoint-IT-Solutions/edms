@extends('layouts.header')

@section('css')
<link href="{{ asset('login_css/css/plugins/chosen/bootstrap-chosen.css') }}" rel="stylesheet">
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

    .dashboard-card.pending .icon-circle {
        background: #e8f5e9;
        color: #4caf50;
    }

    .dashboard-card.declined .icon-circle {
        background: #fff3e0;
        color: #ff9800;
    }

    .dashboard-card.approved .icon-circle {
        background: #e3f2fd;
        color: #2196F3;
    }

    .dashboard-card.delayed .icon-circle {
        background: #ffebee;
        color: #f44336;
    }

    .dashboard-card h2 {
        font-size: 28px;
        font-weight: 700;
        margin: 0 0 4px 0;
        color: #2c3e50;
        line-height: 1;
    }

    .dashboard-card h2 a {
        color: #2c3e50;
        text-decoration: none;
    }

    .dashboard-card h2 a:hover {
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
        background: #8B0000;
        color: white;
        border: none;
        padding: 10px 20px;
        border-radius: 5px;
        font-size: 14px;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        cursor: pointer;
        transition: all 0.3s;
    }

    .btn-new:hover {
        background: #6B0000;
        color: white;
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
        margin-right: 3px;
    }

    .btn-action.view {
        background: #2196F3;
        color: white;
    }

    .btn-action.view:hover {
        background: #1976D2;
        color: white;
    }

    .btn-action.edit {
        background: #ff9800;
        color: white;
    }

    .btn-action.edit:hover {
        background: #f57c00;
        color: white;
    }

    .badge-status {
        padding: 5px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
    }

    .badge-status.pending {
        background: #e8f5e9;
        color: #4caf50;
    }

    .badge-status.approved {
        background: #e3f2fd;
        color: #2196F3;
    }

    .badge-status.declined {
        background: #fff3e0;
        color: #ff9800;
    }

    .badge-status.delayed {
        background: #ffebee;
        color: #f44336;
    }

    .badge-status.pre-assessment {
        background: #b9ff66;
        color: #2c3e50;
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
{{-- @include('error') --}}

<div class="row mb-4 dashboard-header">
    <div class="col-12">
        <h4 class="mb-0">Change Requests</h4>
        <p class="text-muted mb-0">Manage and track document change requests</p>
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-xl-3 col-md-6">
        <div class="dashboard-card pending">
            <div class="icon-circle">
                <i class="fa fa-clock-o"></i>
            </div>
            <h2>
                0
            </h2>
            <p>Pending</p>
        </div>
    </div>
    
    <div class="col-xl-3 col-md-6">
        <div class="dashboard-card declined">
            <div class="icon-circle">
                <i class="fa fa-times-circle"></i>
            </div>
            <h2>
                0
            </h2>
            <p>Declined</p>
        </div>
    </div>
    
    <div class="col-xl-3 col-md-6">
        <div class="dashboard-card approved">
            <div class="icon-circle">
                <i class="fa fa-check-circle"></i>
            </div>
            <h2>
                0
            </h2>
            <p>Approved</p>
        </div>
    </div>
    
    <div class="col-xl-3 col-md-6">
        <div class="dashboard-card delayed">
            <div class="icon-circle">
                <i class="fa fa-exclamation-triangle"></i>
            </div>
            <h2>
                0
            </h2>
            <p>Delayed</p>
        </div>
    </div>
</div>

<!-- Requests Section -->
<div class="requests-section mb-5">
    <div class="section-header">
        <h5 class="section-title">Change Requests</h5>
        <div class="header-actions">
            {{-- @if(auth()->user()->role == "Documents and Records Controller")
            <button class="btn btn-success "  data-target="#newRequest" data-toggle="modal" type="button"><i class="fa fa-plus"></i>&nbsp;New </button>
            @endif
            @if(auth()->user()->role == "Document Control Officer")
            <button class="btn btn-success "  data-target="#newRequest" data-toggle="modal" type="button"><i class="fa fa-plus"></i>&nbsp;New </button>
            @endif --}}
            {{-- @if(auth()->user()->role == "User")
                <button class="btn-new" data-target="#newRequest" data-toggle="modal" data-bs-toggle="modal" data-bs-target="#newRequest" type="button">
                    <i class="fa fa-plus"></i> New Request
                </button>
            @endif --}}
        </div>
    </div>

    <div class="table-container">
        <table class="modern-table tables">
            <thead>
                <tr>
                    <th>Actions</th>
                    <th>Doc ID</th>
                    <th>Title</th>
                    <th>Description</th>
                    <th>Category</th>
                    <th>Privacy</th>
                    <th>Revision</th>
                    <th>Requested By</th>
                    <th>Date Requested</th>
                    <th>Status</th>
                    {{-- <th>Request Status</th> --}}
                </tr>
            </thead>
            <tbody>
                @foreach($requests as $request)
                <tr>
                    <td>
                        {{-- <div class="dropdown">
                            <button class="btn btn-sm btn-outline-secondary" type="button" id="requestDropdown{{$request->id}}" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="ri-more-2-fill"></i>
                            </button>
                            <ul class="dropdown-menu" aria-labelledby="requestDropdown{{$request->id}}">
                                <li>
                                    <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#view_request{{$request->id}}">
                                        <i class="ri-eye-line me-2"></i>View
                                    </a>
                                </li>
                                @if((auth()->user()->role == "Document Control Officer") || (auth()->user()->role == "Administrator"))
                                    @if($request->status == "Pending" && $request->request_type == "Revision")
                                        <li>
                                            <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#edit_request{{$request->id}}">
                                                <i class="ri-pencil-line me-2"></i>Edit
                                            </a>
                                        </li>
                                    @endif
                                @endif
                            </ul>
                        </div> --}}
                        <a href="{{ url('change-request/for_approval/'.$request->id) }}" class="btn btn-sm btn-outline-info me-1">
                            <i class="ri-eye-line"></i>
                        </a>
                    </td>
                    <td>DOC-{{ date('Y', strtotime($request->created_at)) }}-{{ str_pad($request->id,3,'0',STR_PAD_LEFT) }}</td>
                    <td>{{ $request->title }}</td>
                    <td>{{ $request->description }}</td>
                    <td>{{ $request->category }}</td>
                    <td>{{ $request->privacy }}</td>
                    <td>{{ $request->revision }}</td>
                    <td>{{ $request->user->name }}</td>
                    <td>{{ date('Y-m-d', strtotime($request->created_at)) }}</td>
                    <td>{{ $request->status }}</td>
                    {{-- <td>{{ $request->request_status }}</td> --}}
                </tr>
                {{-- @include('view_change_request') --}}
                {{-- @include('edit_change_request') --}}
                @endforeach
            </tbody>
        </table>
    </div>
</div>

{{-- @include('new_change_request_image')
@include('new_change_request') --}}
@endsection

@section('js')
<script src="{{ asset('login_css/js/plugins/dataTables/datatables.min.js')}}"></script>
<script src="{{ asset('login_css/js/plugins/chosen/chosen.jquery.js') }}"></script>
<script>
    $(document).ready(function(){
        $('.cat').chosen({width: "100%"});
        
        $('.tables').DataTable({
            pageLength: 25,
            responsive: true,
            dom: '<"html5buttons"B>lTfg<"bottom-controls"t<"info-paginate"ip>>', 
            buttons: [
                {extend: 'copy'},
                {extend: 'csv'},
                {extend: 'excel', title: 'Change Requests'},
                {extend: 'pdf', title: 'Change Requests'},
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
    });
</script>
@endsection