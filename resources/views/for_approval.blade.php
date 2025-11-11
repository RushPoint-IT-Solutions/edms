@extends('layouts.header')

@section('css')
<link href="{{ asset('login_css/css/plugins/chosen/bootstrap-chosen.css') }}" rel="stylesheet">
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

    .dashboard-card.pending .icon-circle {
        background: #fff3cd;
        color: #856404;
    }

    .dashboard-card.approved .icon-circle {
        background: #cff4fc;
        color: #055160;
    }

    .dashboard-card.declined .icon-circle {
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
        margin-bottom: 20px;
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
        color: #6c757d;
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

    .btn-action:last-child {
        margin-right: 0;
    }

    .btn-action.btn-info {
        background: #0dcaf0;
        color: white;
    }

    .btn-action.btn-info:hover {
        background: #31d2f2;
        color: white;
    }

    .btn-action.btn-warning {
        background: #ffc107;
        color: #000;
    }

    .btn-action.btn-warning:hover {
        background: #ffcd39;
    }

    .ref-badge {
        background: #e7f3ff;
        color: #0066cc;
        padding: 4px 10px;
        border-radius: 4px;
        font-size: 12px;
        font-weight: 600;
        display: inline-block;
    }

    .type-badge {
        padding: 4px 10px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 600;
        display: inline-block;
    }

    .type-badge.new {
        background: #d1e7dd;
        color: #0f5132;
    }

    .type-badge.revision {
        background: #cff4fc;
        color: #055160;
    }

    .type-badge.obsolete {
        background: #f8d7da;
        color: #842029;
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
 
<div class="row mb-4">
    <div class="col-12">
        <h4 class="mb-0">Request Approvals</h4>
        <p class="text-muted mb-0">Manage copy and change requests</p>
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-xl-4 col-md-6">
        <div class="dashboard-card pending">
            <div class="icon-circle">
                <i class="fa fa-clock-o"></i>
            </div>
            <h2>{{count($copy_for_approvals->where('status','Pending'))+count($change_for_approvals->where('status','Pending'))}}</h2>
            <p>For Approval</p>
        </div>
    </div>
    
    <div class="col-xl-4 col-md-6">
        <div class="dashboard-card approved">
            <div class="icon-circle">
                <i class="fa fa-check-circle"></i>
            </div>
            <h2>{{count($copy_for_approvals->where('status','Approved'))+count($change_for_approvals->where('status','Approved'))}}</h2>
            <p>Approved</p>
        </div>
    </div>
    
    <div class="col-xl-4 col-md-6">
        <div class="dashboard-card declined">
            <div class="icon-circle">
                <i class="fa fa-times-circle"></i>
            </div>
            <h2>{{count($copy_for_approvals->where('status','Declined'))+count($change_for_approvals->where('status','Declined'))}}</h2>
            <p>Declined</p>
        </div>
    </div>
</div>

<div class="requests-section">
    <div class="section-header">
        <h5 class="section-title">Copy Requests</h5>
    </div>

    <div class="table-container">
        <table class="modern-table tables">
            <thead>
                <tr>
                    <th>Actions</th>
                    <th>Reference</th>
                    <th>Date</th>
                    <th>Document</th>
                    <th>Requested By</th>
                </tr>
            </thead>
            <tbody>
                @foreach($copy_for_approvals->where('status','Pending') as $copy_approval)
                @php
                    $request = $copy_approval->copy_request;
                @endphp
                <tr>
                    <td>
                        <div class="dropdown">
                            <button class="btn btn-sm btn-outline-secondary" type="button" id="copyRequestDropdown{{$copy_approval->copy_request->id}}" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="ri-more-2-fill"></i>
                            </button>
                            <ul class="dropdown-menu" aria-labelledby="copyRequestDropdown{{$copy_approval->copy_request->id}}">
                                <li>
                                    <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#view_request_copy{{$copy_approval->copy_request->id}}">
                                        <i class="ri-eye-line me-2"></i>View Request
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </td>
                    <td>
                        <span class="ref-badge">CR-{{str_pad($request->id, 5, '0', STR_PAD_LEFT)}}</span>
                    </td>
                    <td>{{date('M d, Y',strtotime($copy_approval->copy_request->created_at))}}</td>
                    <td>
                        <small>
                            <strong>{{$copy_approval->copy_request->control_code}} Rev. {{$copy_approval->copy_request->revision}}</strong><br>
                            {{$copy_approval->copy_request->title}}<br>
                            {{$copy_approval->copy_request->type_of_document}}
                        </small>
                    </td>
                    <td>{{$copy_approval->copy_request->user->name}}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<div class="requests-section">
    <div class="section-header">
        <h5 class="section-title">Change Requests</h5>
    </div>

    <div class="table-container">
        <table class="modern-table tables">
            <thead>
                <tr>
                    <th>Actions</th>
                    <th>Reference</th>
                    <th>Date</th>
                    <th>Document</th>
                    <th>Requested By</th>
                    <th>Type</th>
                </tr>
            </thead>
            <tbody>
                @foreach($change_for_approvals->where('status','Pending') as $change_approval)
                @php
                    $request = $change_approval->change_request;
                @endphp
                <tr>
                    <td>
                        <a href="#" data-bs-target="#view_request_change{{$request->id}}" data-bs-toggle="modal" class="btn btn-sm btn-outline-info me-1" title="View Request">
                            <i class="ri-eye-line"></i>
                        </a>

                        @if(auth()->user()->role == "Document Control Officer")
                            <a href="#" data-bs-target="#edit_title{{$request->id}}" data-bs-toggle="modal" class="btn btn-sm btn-outline-primary me-1" title="Edit">
                                <i class="ri-pencil-line"></i>
                            </a>
                        @endif
                    </td>
                    <td>
                        <span class="ref-badge">DICR-{{str_pad($request->id, 5, '0', STR_PAD_LEFT)}}</span>
                    </td>
                    <td>{{date('M d, Y',strtotime($request->created_at))}}</td>
                    <td>
                        <small>
                            <strong>{{$request->control_code}} Rev. {{$request->revision}}</strong><br>
                            {{$request->title}}<br>
                            {{$request->type_of_document}}
                        </small>
                    </td>
                    <td>{{$request->user->name}}</td>
                    <td>
                        @if($request->request_type == 'New')
                            <span class="type-badge new">New</span>
                        @elseif($request->request_type == 'Revision')
                            <span class="type-badge revision">Revision</span>
                        @elseif($request->request_type == 'Obsolete')
                            <span class="type-badge obsolete">Obsolete</span>
                        @else
                            <span class="type-badge">{{$request->request_type}}</span>
                        @endif
                    </td>
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
@include('view_approval_change')
@include('edit_title')
@endforeach

@foreach($copy_for_approvals->where('status','Pending') as $copy_approval)
@php
$request = $copy_approval->copy_request;
@endphp
@include('view_approval_copy')
@endforeach
@endsection

@section('js')
<script src="{{ asset('login_css/js/plugins/dataTables/datatables.min.js')}}"></script>
<script src="{{ asset('login_css/js/plugins/chosen/chosen.jquery.js') }}"></script>

<script>
    function remove_required(id,value)
    {
        if(value=="Declined")
        {
            $('#soft_copy_'+id).prop('required',false);
            $('#pdf_copy_'+id).prop('required',false);

            $('.returnOptions'+id).css('display', 'none');
            $("#returned_to"+id).prop('required', false);
        }
        else if(value=="Returned")
        {
            $('#soft_copy_'+id).prop('required',false);
            $('#pdf_copy_'+id).prop('required',false);
            
            $('.returnOptions'+id).css('display', 'block');
            $("#returned_to"+id).prop('required', true);
        }
        else
        {
            $('#soft_copy_'+id).prop('required',true);
            $('#pdf_copy_'+id).prop('required',true);

            $('.returnOptions'+id).css('display', 'none');
            $("#returned_to"+id).prop('required', false);
        }
    }
    
    $(document).ready(function(){
        $('.cat').chosen({width: "100%"});
        
        $('.tables').DataTable({
            pageLength: 25,
            responsive: true,
            sorting: false,
            dom: '<"html5buttons"B>lTfg<"bottom-controls"t<"info-paginate"ip>>', 
            buttons: [
                {extend: 'copy'},
                {extend: 'csv'},
                {extend: 'excel', title: 'Request Approvals'},
                {extend: 'pdf', title: 'Request Approvals'},
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