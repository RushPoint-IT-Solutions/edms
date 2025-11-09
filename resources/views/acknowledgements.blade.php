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

    .dashboard-card.upload .icon-circle {
        background: #fff3cd;
        color: #856404;
    }

    .dashboard-card.uploaded .icon-circle {
        background: #d1e7dd;
        color: #0f5132;
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

    /* Reference Number Badge */
    .ref-badge {
        background: #e7f3ff;
        color: #0066cc;
        padding: 4px 10px;
        border-radius: 4px;
        font-size: 12px;
        font-weight: 600;
        display: inline-block;
    }

    /* Status Badges */
    .badge-status {
        padding: 5px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
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

    .badge-status.success {
        background: #e8f5e9;
        color: #4caf50;
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
        <h4 class="mb-0">Acknowledgement Management</h4>
        <p class="text-muted mb-0">Upload and manage document acknowledgements</p>
    </div>
</div>

<div class="row g-3 mb-4">
    @if(auth()->user()->department_id != 8)
    <div class="col-xl-3 col-md-6">
        <div class="dashboard-card upload">
            <div class="icon-circle">
                <i class="ri-upload-cloud-line"></i>
            </div>
            <h2><a href='{{url("acknowledgement")}}'>{{count($requests->where('acknowledgement',null))}}</a></h2>
            <p>For Upload</p>
        </div>
    </div>
    @endif
    <div class="col-xl-3 col-md-6">
        <div class="dashboard-card uploaded">
            <div class="icon-circle">
                <i class="ri-checkbox-circle-line"></i>
            </div>
            <h2><a href='{{url("uploaded-acknowledgement")}}'>{{count($requests->where('acknowledgement','!=',null))}}</a></h2>
            <p>Uploaded</p>
        </div>
    </div>
</div>

<div class="requests-section mb-5">
    <div class="section-header">
        <h5 class="section-title">For Upload</h5>
    </div>

    <div class="table-container">
        <table class="modern-table tables">
            <thead>
                <tr>
                    <th>Actions</th>
                    <th>Reference No.</th>
                    <th>Request Type</th>
                    <th>Date Requested</th>
                    <th>Code</th>
                    <th>Title</th>
                    <th>Date Cascade</th>
                    <th>Type</th>
                    <th>Requested By</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @if(auth()->user()->department_id == 8)
                    @foreach($requests->where('acknowledgement', '!=', null) as $request)
                    <tr>
                        <td>
                            <a href="#" data-target="#view_request{{$request->id}}" data-toggle="modal"
                                class='btn-action btn-info' title="View Request">
                                <i class="fa fa-eye"></i>
                            </a>
                            @if(auth()->user()->department_id != 8)
                            <a href="#" data-target="#upload{{$request->id}}" data-toggle="modal"
                                class='btn-action btn-warning' title="Upload">
                                <i class="fa fa-upload"></i>
                            </a>
                            @endif
                        </td>
                        <td>
                            <span class="ref-badge">DICR-{{str_pad($request->id, 5, '0', STR_PAD_LEFT)}}</span>
                        </td>
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
                        <td>{{date('M d, Y',strtotime($request->created_at))}}</td>

                        @if($request->document_id != null)
                        <td>{{$request->control_code}}</td>
                        <td>{{$request->title}}</td>
                        <td>{{date('M d, Y',strtotime($request->updated_at))}}</td>
                        @else
                        <td>-</td>
                        <td>{{$request->title}}</td>
                        <td>-</td>
                        @endif

                        <td>{{$request->type_of_document}}</td>
                        <td>{{$request->user->name}}</td>
                        <td>
                            @if($request->status == "Pending")
                                <span class='badge-status pending'>Pending</span>
                            @elseif($request->status == "Approved")
                                <span class='badge-status approved'>Approved</span>
                            @elseif($request->status == "Declined")
                                <span class='badge-status declined'>Declined</span>
                            @else
                                <span class='badge-status success'>{{$request->status}}</span>
                            @endif
                        </td>
                    </tr>
                    @include('view_change_request')
                    @include('upload')
                    @endforeach
                @else
                    @foreach($requests->where('acknowledgement',null) as $request)
                    <tr>
                        <td>
                            <a href="#" data-target="#view_request{{$request->id}}" data-toggle="modal"
                                class='btn-action btn-info' title="View Request">
                                <i class="fa fa-eye"></i>
                            </a>
                            @if(auth()->user()->department_id != 8)
                            <a href="#" data-target="#upload{{$request->id}}" data-toggle="modal"
                                class='btn-action btn-warning' title="Upload">
                                <i class="fa fa-upload"></i>
                            </a>
                            @endif
                        </td>
                        <td>
                            <span class="ref-badge">DICR-{{str_pad($request->id, 5, '0', STR_PAD_LEFT)}}</span>
                        </td>
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
                        <td>{{date('M d, Y',strtotime($request->created_at))}}</td>

                        @if($request->document_id != null)
                        <td>{{$request->control_code}}</td>
                        <td>{{$request->title}}</td>
                        <td>{{date('M d, Y',strtotime($request->updated_at))}}</td>
                        @else
                        <td>-</td>
                        <td>{{$request->title}}</td>
                        <td>-</td>
                        @endif

                        <td>{{$request->type_of_document}}</td>
                        <td>{{$request->user->name}}</td>
                        <td>
                            @if($request->status == "Pending")
                                <span class='badge-status pending'>Pending</span>
                            @elseif($request->status == "Approved")
                                <span class='badge-status approved'>Approved</span>
                            @elseif($request->status == "Declined")
                                <span class='badge-status declined'>Declined</span>
                            @else
                                <span class='badge-status success'>{{$request->status}}</span>
                            @endif
                        </td>
                    </tr>
                    @include('view_change_request')
                    @include('upload')
                    @endforeach
                @endif
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
        $('.locations').chosen({width: "100%"});
        
        $('.tables').DataTable({
            pageLength: 25,
            responsive: true,
            sorting: false,
            dom: '<"html5buttons"B>lTfg<"bottom-controls"t<"info-paginate"ip>>', 
            buttons: [
                {extend: 'copy'},
                {extend: 'csv'},
                {extend: 'excel', title: 'Acknowledgement Management'},
                {extend: 'pdf', title: 'Acknowledgement Management'},
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