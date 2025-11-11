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
        background: #fff3cd;
        color: #856404;
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
        color: #495057;
    }

    .btn-action {
        padding: 6px 12px;
        border-radius: 4px;
        font-size: 13px;
        border: none;
        cursor: pointer;
        transition: all 0.3s;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: #2196F3;
        color: white;
    }

    .btn-action:hover {
        background: #1976D2;
        color: white;
        text-decoration: none;
    }

    .btn-action i {
        font-size: 14px;
    }

    .badge-reference {
        background: #e3f2fd;
        color: #2196F3;
        padding: 4px 10px;
        border-radius: 4px;
        font-size: 12px;
        font-weight: 600;
        display: inline-block;
    }

    .badge-type {
        background: #f8f9fa;
        color: #495057;
        padding: 4px 10px;
        border-radius: 4px;
        font-size: 11px;
        font-weight: 600;
        display: inline-block;
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
 
<div class="row mb-4 dashboard-header">
    <div class="col-12">
        <h4 class="mb-0">Change Requests</h4>
        <p class="text-muted mb-0">Review and manage document change requests</p>
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-lg-3 col-md-6">
        <div class="dashboard-card pending">
            <div class="icon-circle">
                <i class="fa fa-clock-o"></i>
            </div>
            <h2>{{count($change_for_approvals->where('status','Pending'))}}</h2>
            <p>Pending Approvals</p>
        </div>
    </div>
</div>

<div class="requests-section mb-5">
    <div class="section-header">
        <h5 class="section-title">Pending Change Requests</h5>
    </div>

     

    
    <div class="table-container">
        <table class="modern-table tables">
            <thead>
                <tr>
                    <th>Action</th>
                    <th>Reference Number</th>
                    <th>Date Requested</th>
                    <th>Document</th>
                    <th>Document Type</th>
                    <th>Requested By</th>
                    <th>Request Type</th>
                </tr>
            </thead>
            <tbody>
                @foreach($change_for_approvals->where('status','Pending') as $change_approval)
                @php
                    $request = $change_approval->change_request;
                @endphp
                <tr>
                    <td>
                        <div class="dropdown">
                            <button class="btn btn-sm btn-outline-secondary" type="button" id="requestDropdown{{$request->id}}" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="ri-more-2-fill"></i>
                            </button>
                            <ul class="dropdown-menu" aria-labelledby="requestDropdown{{$request->id}}">
                                <li>
                                    <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#view_request_change{{$request->id}}">
                                        <i class="ri-eye-line me-2"></i>View Request
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </td>
                    <td>
                        <span class="badge-reference">DICR-{{str_pad($request->id, 5, '0', STR_PAD_LEFT)}}</span>
                    </td>
                    <td>{{date('M d, Y',strtotime($request->created_at))}}</td>
                    <td>
                        <small>
                            <strong>{{$request->control_code}} Rev. {{$request->revision}}</strong><br>
                            {{$request->title}}<br>
                            <span class="badge-type">{{$request->type_of_document}}</span>
                        </small>
                    </td>
                    <td>{{$request->type_of_document}}</td>
                    <td>{{$request->user->name}}</td>
                    <td>{{$request->request_type}}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

@foreach($change_for_approvals->where('status','Pending') as $change_approval)
@php
    $request = $change_approval->change_request;
@endphp
@include('view_removal_approvers')
@endforeach
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
            order: [],
            dom: '<"html5buttons"B>lTfg<"bottom-controls"t<"info-paginate"ip>>', 
            buttons: [
                {extend: 'copy'},
                {extend: 'csv'},
                {extend: 'excel', title: 'ChangeRequests'},
                {extend: 'pdf', title: 'ChangeRequests'},
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