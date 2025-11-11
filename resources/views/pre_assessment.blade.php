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

    .assessment-section {
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

    .btn-action {
        padding: 6px 12px;
        border-radius: 4px;
        font-size: 13px;
        border: none;
        cursor: pointer;
        transition: all 0.3s;
        text-decoration: none;
        display: inline-block;
    }

    .btn-action.view {
        background: #2196F3;
        color: white;
    }

    .btn-action.view:hover {
        background: #1976D2;
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
        <h4 class="mb-0">Pre Assessment</h4>
        <p class="text-muted mb-0">Track and manage document pre-assessment requests</p>
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-xl-3 col-md-6">
        <div class="dashboard-card pending">
            <div class="icon-circle">
                <i class="fa fa-clock-o"></i>
            </div>
            <h2>
                <form method="GET" style="display: inline;">
                    <input type="hidden" name="status" value="NotDelayed">
                    <input type="submit" value="{{ $notDelayedCount }}" style="background: none; border: none; cursor: pointer; padding: 0; color: #2c3e50; font-size: 28px; font-weight: 700;">
                </form>
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
                <form method="GET" style="display: inline;">
                    <input type="hidden" name="status" value="Declined">
                    <input type="submit" value="{{ $declinedCount }}" style="background: none; border: none; cursor: pointer; padding: 0; color: #2c3e50; font-size: 28px; font-weight: 700;">
                </form>
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
                <form method="GET" style="display: inline;">
                    <input type="hidden" name="status" value="Approved">
                    <input type="submit" value="{{ $approvedCount }}" style="background: none; border: none; cursor: pointer; padding: 0; color: #2c3e50; font-size: 28px; font-weight: 700;">
                </form>
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
                <form method="GET" style="display: inline;">
                    <input type="hidden" name="status" value="Delayed">
                    <input type="submit" value="{{ $delayedCount }}" style="background: none; border: none; cursor: pointer; padding: 0; color: #2c3e50; font-size: 28px; font-weight: 700;">
                </form>
            </h2>
            <p>Delayed</p>
        </div>
    </div>
</div>

<div class="assessment-section mb-5">
    <div class="section-header">
        <h5 class="section-title">Pre Assessment List</h5>
    </div>

     

    
    <div class="table-container">
        <table class="modern-table tables">
            <thead>
                <tr>
                    <th>Actions</th>
                    <th>Date Requested</th>
                    <th>Code</th>
                    <th>Title</th>
                    <th>Revision</th>
                    <th>Type</th>
                    <th>Requested By</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $delayed = 0;
                @endphp
                @foreach($pre_assessment as $pa)
                    @php
                        $targetDate = date('Y-m-d', strtotime('+10 days', strtotime($pa->created_at)));
                    @endphp
                    <tr>
                        <td>
                            <div class="dropdown">
                                <button class="btn btn-sm btn-outline-secondary" type="button" id="preAssessmentDropdown{{$pa->id}}" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="ri-more-2-fill"></i>
                                </button>
                                <ul class="dropdown-menu" aria-labelledby="preAssessmentDropdown{{$pa->id}}">
                                    <li>
                                        <button class="dropdown-item" data-bs-toggle="modal" data-bs-target="#viewPreAssessmentModal-{{$pa->id}}">
                                            <i class="ri-eye-line me-2"></i>View
                                        </button>
                                    </li>
                                </ul>
                            </div>
                        </td>
                        <td>{{date('M d Y',strtotime($pa->created_at))}}</td>
                        <td><strong>{{$pa->control_code}}</strong></td>
                        <td>{{$pa->title}}</td>
                        <td>{{$pa->revision}}</td>
                        <td>{{$pa->type_of_document}}</td>
                        <td>{{$pa->user->name}}</td>
                        <td>
                            @if($pa->status == "Pending")
                                @if($targetDate < date('Y-m-d'))
                                    @php
                                        $delayed++;
                                    @endphp
                                    <span class='badge-status delayed'>Delayed - {{$pa->status}}</span>
                                @else
                                    <span class='badge-status pending'>{{$pa->status}}</span>
                                @endif
                            @elseif($pa->status == "Approved")
                                <span class='badge-status approved'>{{$pa->status}}</span>
                            @elseif($pa->status == "Declined")
                                <span class='badge-status declined'>{{$pa->status}}</span>
                            @else
                                <span class='badge-status pending'>{{$pa->status}}</span>
                            @endif
                        </td>
                    </tr>
                    @include('view_pre_assessment')
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
        $('.cat').chosen({width: "100%"});
        
        $('.tables').DataTable({
            pageLength: 25,
            responsive: true,
            sorting: false,
            dom: '<"html5buttons"B>lTfg<"bottom-controls"t<"info-paginate"ip>>', 
            buttons: [
                {extend: 'copy'},
                {extend: 'csv'},
                {extend: 'excel', title: 'Pre Assessment'},
                {extend: 'pdf', title: 'Pre Assessment'},
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