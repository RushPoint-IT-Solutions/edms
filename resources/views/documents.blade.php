@extends('layouts.header')

@section('css')
<link href="{{ asset('login_css/css/plugins/chosen/bootstrap-chosen.css') }}" rel="stylesheet">
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

    .dashboard-card.primary .icon-circle {
        background: #e3f2fd;
        color: #2196F3;
    }

    .dashboard-card.success .icon-circle {
        background: #e8f5e9;
        color: #4caf50;
    }

    .dashboard-card.warning .icon-circle {
        background: #fff3e0;
        color: #ff9800;
    }

    .dashboard-card.danger .icon-circle {
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

    .documents-section {
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

    .header-actions {
        display: flex;
        gap: 10px;
    }

    .btn-upload {
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

    .btn-upload:hover {
        background: #6B0000;
        color: white;
    }

    .search-filter-bar {
        display: flex;
        gap: 15px;
        margin-bottom: 25px;
        flex-wrap: wrap;
    }

    .filter-group {
        display: flex;
        flex-direction: column;
        gap: 5px;
    }

    .filter-group label {
        font-size: 13px;
        color: #495057;
        font-weight: 500;
        margin: 0;
    }

    .filter-group select {
        padding: 8px 12px;
        border: 1px solid #dee2e6;
        border-radius: 5px;
        font-size: 14px;
        min-width: 200px;
    }

    .btn-search {
        background: #2196F3;
        color: white;
        border: none;
        padding: 8px 24px;
        border-radius: 5px;
        font-size: 14px;
        cursor: pointer;
        transition: all 0.3s;
        align-self: flex-end;
        height: fit-content;
    }

    .btn-search:hover {
        background: #1976D2;
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

    .badge-status.active {
        background: #e8f5e9;
        color: #4caf50;
    }

    .badge-status.obsolete {
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
</style>
@endsection

@section('content')
<div class="row mb-4 dashboard-header">
    <div class="col-12">
        <h4 class="mb-0">Documents</h4>
        <p class="text-muted mb-0">Manage and track your document library</p>
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-lg-3 col-md-6">
        <div class="dashboard-card primary">
            <div class="icon-circle">
                <i class="fa fa-file-text-o"></i>
            </div>
            <h2>{{count($documents->where('status',null))}}</h2>
            <p>Total Documents</p>
        </div>
    </div>

    <div class="col-lg-3 col-md-6">
        <div class="dashboard-card success">
            <div class="icon-circle">
                <i class="fa fa-file-o"></i>
            </div>
            <h2>{{count($documents->whereBetween('created_at',[date('Y-m-01'), date('Y-m-t')]))}}</h2>
            <p>New This {{date('M Y')}}</p>
        </div>
    </div>

    <div class="col-lg-3 col-md-6">
        <div class="dashboard-card warning">
            <div class="icon-circle">
                <i class="fa fa-refresh"></i>
            </div>
            <h2>{{count($documents->whereBetween('updated_at',[date('Y-m-01'), date('Y-m-t')]))}}</h2>
            <p>Revised This {{date('M Y')}}</p>
        </div>
    </div>

    <div class="col-lg-3 col-md-6">
        <div class="dashboard-card danger">
            <div class="icon-circle">
                <i class="fa fa-ban"></i>
            </div>
            <h2>{{count($obsoletes)+count($documents->where('status',"Obsolete"))}}</h2>
            <p>Obsolete</p>
        </div>
    </div>
</div>

<div class="documents-section mb-5">
    <div class="section-header">
        <h5 class="section-title">Document Library</h5>
        <div class="header-actions">
            @if((auth()->user()->role == 'Administrator') || (auth()->user()->role == 'Business Process Manager') || (auth()->user()->role == 'Management Representative') ||(auth()->user()->role == 'Document Control Officer') )
            <button class="btn btn-default btn-upload" data-bs-toggle="modal" data-bs-target="#uploadDocument" type="button">
                <i class="fa fa-plus"></i>&nbsp;Upload Document
            </button>
            @endif
        </div>
    </div>

    <form method="GET" action="" class="custom_form" enctype="multipart/form-data">
        <div class="search-filter-bar">
            <div class="filter-group">
                <label>Department</label>
                            {{-- <div class="col-md-3">
                                <input type='text' class='form-control' name='search' placeholder="Search Control Code,Title,Type of Document" >
                            </div> --}}
                <select class='form-control cat' name='department'>
                    <option value=''>Select All Departments</option>
                    @foreach($departments as $department)
                        <option value='{{$department->id}}' {{ ($department->id == $dep) ? 'selected="selected"' : '' }}>
                            {{$department->name}}
                        </option>
                    @endforeach
                </select>
            </div>
            <button class="btn-search" type="submit">
                <i class="ri-search-line"></i> Search
            </button>
        </div>
    </form>

     

    
    <div class="table-container">
        <table class="modern-table tables">
            <thead>
                <tr>
                    <th>Action</th>
                    <th>Control Code</th>
                    <th>Old Code</th>
                    @if((auth()->user()->role == 'Administrator') || (auth()->user()->role == 'Business Process Manager') || (auth()->user()->role == 'Management Representative') || (auth()->user()->role == "Document Control Officer"))
                    <th>Public</th>
                    @endif
                    <th>Revisions</th>
                    <th>Company</th>
                    <th>Department</th>
                    <th>Document</th>
                    <th>Type of Document</th>
                    <th>Effective Date</th>
                    <th>Process Owner</th>
                    <th>Uploaded By</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($documents_na as $document)
                <tr>
                    <td>
                        <a href="{{url('view-document/'.$document->id)}}" target="_blank" class='btn-action view'>
                            <i class="ri-eye-line"></i>
                        </a>
                    </td>
                    <td><strong>{{$document->control_code}}</strong></td>
                    <td>{{$document->old_control_code}}</td>
                    @if((auth()->user()->role == 'Administrator') || (auth()->user()->role == 'Business Process Manager') || (auth()->user()->role == 'Management Representative') || (auth()->user()->role == "Document Control Officer"))
                    <td>
                        <input type='checkbox' name='public' onchange='public_info(this,{{$document->id}})' @if($document->public != null) checked @endif>
                    </td>
                    @endif
                    <td>{{$document->version}}</td>
                    <td>{{$document->company->name}}</td>
                    <td>{{$document->department->name}}</td>
                    <td>{{$document->title}}</td>
                    <td>{{$document->category}}</td>
                    <td>{{date('M d, Y',strtotime($document->updated_at))}}</td>
                    <td>
                        @if($document->process_owner != null)
                            <span class="badge-info">{{$document->processOwner->name}}</span>
                        @else 
                            <span class="badge-info">{{$document->department->dep_head->name}}</span>
                        @endif
                    </td>
                    <td>{{$document->user->name}}</td>
                    <td>
                        @if($document->status == null)
                            <span class="badge-status active">Active</span>
                        @else
                            <span class="badge-status obsolete">Obsolete</span>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

@include('upload_document')
@endsection

@section('js')
<script src="{{ asset('login_css/js/plugins/dataTables/datatables.min.js')}}"></script>
<script src="{{ asset('login_css/js/plugins/chosen/chosen.jquery.js') }}"></script>
<script src="{{ asset('login_css/js/plugins/sweetalert/sweetalert.min.js') }}"></script>

<script>
    function public_info(value, id) {
        console.log(value.checked);
        $.ajax({
            dataType: 'json',
            type: 'POST',
            url: '{{url("/change-public")}}',
            data: {id: id, value: value.checked},
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        }).done(function(data) {
            console.log(data);
        }).fail(function(data) {
            console.error(data);
        });
    }

    $(document).ready(function() {
        $('.cat').chosen({width: "100%"});
        
        $('.tables').DataTable({
            pageLength: 25,
            responsive: true,
            dom: '<"html5buttons"B>lTfg<"bottom-controls"t<"info-paginate"ip>>', 
            buttons: [
                {extend: 'copy'},
                {extend: 'csv'},
                {extend: 'excel', title: 'Documents'},
                {extend: 'pdf', title: 'Documents'},
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