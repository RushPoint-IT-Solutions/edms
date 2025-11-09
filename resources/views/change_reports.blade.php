@extends('layouts.header')

@section('css')
<link href="{{ asset('login_css/css/plugins/chosen/bootstrap-chosen.css') }}" rel="stylesheet">
<link href="{{ asset('login_css/css/plugins/dataTables/datatables.min.css') }}" rel="stylesheet">

<style>
    .dashboard-header {
        margin-bottom: 30px;
    }

    .filter-section {
        background: white;
        border-radius: 10px;
        padding: 25px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        margin-bottom: 20px;
    }

    .filter-section .form-label {
        font-size: 14px;
        font-weight: 600;
        color: #495057;
        margin-bottom: 8px;
    }

    .filter-section .form-control,
    .filter-section .form-control-sm {
        border: 1px solid #dee2e6;
        border-radius: 5px;
        padding: 8px 12px;
    }

    .filter-section .form-control:focus,
    .filter-section .form-control-sm:focus {
        border-color: #8B0000;
        box-shadow: 0 0 0 0.2rem rgba(139, 0, 0, 0.15);
    }

    .btn-search {
        background: #8B0000;
        color: white;
        border: none;
        padding: 10px 24px;
        border-radius: 5px;
        font-weight: 600;
        transition: all 0.3s;
    }

    .btn-search:hover {
        background: #6B0000;
        color: white;
        transform: translateY(-1px);
        box-shadow: 0 4px 8px rgba(139, 0, 0, 0.3);
    }

    .reports-section {
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

    .badge-status {
        padding: 5px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
        display: inline-block;
    }

    .badge-status.pending {
        background: #fff3e0;
        color: #ff9800;
    }

    .badge-status.approved {
        background: #e3f2fd;
        color: #2196F3;
    }

    .badge-status.declined {
        background: #ffebee;
        color: #f44336;
    }

    .badge-status.completed {
        background: #e8f5e9;
        color: #4caf50;
    }

    .badge-status.waiting {
        background: #f5f5f5;
        color: #9e9e9e;
    }

    .reference-no {
        font-family: 'Courier New', monospace;
        font-weight: 700;
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
        padding-top: 15px;
        padding-bottom: 15px;
    }

    .dataTables_wrapper .dataTables_paginate {
        float: right;
        margin-top: 15px;
        padding-bottom: 15px;
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
        <h4 class="mb-0">Change Request Reports</h4>
        <p class="text-muted mb-0">View and filter change request history</p>
    </div>
</div>

<div class="filter-section mb-3">
    <form method='GET' onsubmit='show();' enctype="multipart/form-data">
        <div class='row align-items-end'>
            <div class="col-lg-3 col-md-4">
                <label class="form-label">From Date</label>
                <input class='form-control-sm form-control' name='from' value='{{$from}}' max='{{date('Y-m-d')}}' type='date' required>
            </div>
            <div class="col-lg-3 col-md-4">
                <label class="form-label">To Date</label>
                <input class='form-control-sm form-control' name='to' value='{{$from}}' max='{{date('Y-m-d')}}' type='date' required>
            </div>
            <div class="col-lg-2 col-md-4">
                <button type="submit" class="btn-search w-100">
                    <i class="fa fa-search"></i> Search
                </button>
            </div>
        </div>
    </form>
</div>

<div class="reports-section mb-5">
    <div class="section-header">
        <h5 class="section-title">Request History</h5>
    </div>

    <div class="table-container">
        <table class="modern-table tables">
            <thead>
                <tr>
                    <th>Reference No.</th>
                    <th>Date Requested</th>
                    <th>Control Code</th>
                    <th>Title</th>
                    <th>Request By</th>
                    <th>Status</th>
                    <th>Approver</th>
                    <th>Start Date</th>
                    <th>Action Date</th>
                    <th>TAT</th>
                    <th>Remarks</th>
                    <th>Approval Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($requests as $request)
                    @foreach($request->approvers as $key => $approver)
                    <tr>
                        <td>
                            <span class="reference-no">DICR-{{str_pad($request->id, 5, '0', STR_PAD_LEFT)}}</span>
                        </td>
                        <td>{{date('M. d, Y',strtotime($request->created_at))}}</td>
                        <td><strong>{{$request->control_code}}</strong></td>
                        <td>{{$request->title}}</td>
                        <td>{{$request->user->name}}</td>
                        <td>
                            @if($request->status == "Pending")
                                <span class='badge-status pending'>Pending</span>
                            @elseif($request->status == "Approved")
                                <span class='badge-status approved'>Approved</span>
                            @elseif($request->status == "Declined")
                                <span class='badge-status declined'>Declined</span>
                            @else
                                <span class='badge-status completed'>{{$request->status}}</span>
                            @endif
                        </td>
                        <td>{{$approver->user->name}}</td>
                        <td>
                            @if($approver->start_date)
                                {{date('M. d, Y', strtotime($approver->start_date))}}
                            @endif
                        </td>
                        <td>
                            @if(($approver->status != "Waiting") && ($approver->status != "Pending"))
                                {{date('M. d, Y',strtotime($approver->updated_at))}}
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td>
                            @php
                                $difference = 0;
                                if($approver->status == "Pending")
                                {
                                    $date_after = date('Y-m-d');
                                    $date_before = date('Y-m-d',strtotime($approver->start_date));
                                    $difference = strtotime($date_after)-strtotime($date_before);
                                }
                                else 
                                {
                                    $date_after = date('Y-m-d',strtotime($approver->updated_at));
                                    $date_before = date('Y-m-d',strtotime($approver->start_date));
                                    $difference = strtotime($date_after)-strtotime($date_before);
                                }
                            @endphp
                            @if($approver->status != "Waiting")
                                <strong>{{$difference/(24*60*60)}}</strong> day/s
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td>
                            @if($approver->status != "Waiting")
                                {{$approver->remarks}}
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td>
                            @if($approver->status == "Pending")
                                <span class='badge-status pending'>Pending</span>
                            @elseif($approver->status == "Approved")
                                <span class='badge-status approved'>Approved</span>
                            @elseif($approver->status == "Declined")
                                <span class='badge-status declined'>Declined</span>
                            @elseif($approver->status == "Waiting")
                                <span class='badge-status waiting'>Waiting</span>
                            @else
                                <span class='badge-status completed'>{{$approver->status}}</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                @endforeach
            </tbody>
        </table>
    </div>
</div>

@endsection

@section('js')
<script src="{{ asset('login_css/js/plugins/dataTables/datatables.min.js')}}"></script>
<script src="{{ asset('login_css/js/plugins/chosen/chosen.jquery.js') }}"></script>

<script>
    $(document).ready(function(){
        $('.tables').DataTable({
            pageLength: 25,
            responsive: true,
            sorting: false,
            dom: '<"html5buttons"B>lfr<"bottom-controls"t<"info-paginate"ip>>',
            buttons: [
                {extend: 'copy'},
                {extend: 'csv'},
                {extend: 'excel', title: 'Change Request Reports'},
                {extend: 'pdf', title: 'Change Request Reports'},
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