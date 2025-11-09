@extends('layouts.header')

@section('css')
<link href="{{ asset('login_css/css/plugins/chosen/bootstrap-chosen.css') }}" rel="stylesheet">
<link href="{{ asset('login_css/css/plugins/dataTables/datatables.min.css') }}" rel="stylesheet">

<style>
    .dashboard-header {
        margin-bottom: 30px;
    }

    .audit-section {
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

    .badge-event {
        padding: 5px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
        display: inline-block;
    }

    .badge-event.created {
        background: #e8f5e9;
        color: #4caf50;
    }

    .badge-event.updated {
        background: #e3f2fd;
        color: #2196F3;
    }

    .badge-event.deleted {
        background: #ffebee;
        color: #f44336;
    }

    .badge-event.restored {
        background: #fff3e0;
        color: #ff9800;
    }

    .truncated-text {
        max-width: 150px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        display: inline-block;
        vertical-align: middle;
        cursor: help;
    }

    .model-type {
        font-size: 12px;
        background: #f8f9fa;
        padding: 4px 8px;
        border-radius: 4px;
        color: #495057;
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
        <h4 class="mb-0">Audit Logs</h4>
        <p class="text-muted mb-0">Track all system activities and changes</p>
    </div>
</div>

<div class="audit-section mb-5">
    <div class="section-header">
        <h5 class="section-title">Activity History</h5>
    </div>

    <div class="table-container">
        <table class="modern-table tables">
            <thead>
                <tr>
                    <th>Date & Time</th>
                    <th>Model</th>
                    <th>User</th>
                    <th>Event</th>
                    <th>ID</th>
                    <th>Old Values</th>
                    <th>New Values</th>
                    <th>IP Address</th>
                    <th>User Agent</th>
                </tr>
            </thead>
            <tbody>
                @foreach($audits as $audit)
                <tr>
                    <td>
                        <strong>{{date('M d, Y',strtotime($audit->created_at))}}</strong><br>
                        <small class="text-muted">{{date('h:i:s A',strtotime($audit->created_at))}}</small>
                    </td>
                    <td>
                        <span class="model-type">
                            {{str_replace('App\\Models\\', '', $audit->auditable_type)}}
                        </span>
                    </td>
                    <td>
                        @if($audit->user)
                            <strong>{{$audit->user->name}}</strong>
                        @else
                            <span class="text-muted">System</span>
                        @endif
                    </td>
                    <td>
                        @if($audit->event == 'created')
                            <span class="badge-event created">Created</span>
                        @elseif($audit->event == 'updated')
                            <span class="badge-event updated">Updated</span>
                        @elseif($audit->event == 'deleted')
                            <span class="badge-event deleted">Deleted</span>
                        @elseif($audit->event == 'restored')
                            <span class="badge-event restored">Restored</span>
                        @else
                            <span class="badge-event updated">{{ucfirst($audit->event)}}</span>
                        @endif
                    </td>
                    <td><code>{{$audit->auditable_id}}</code></td>
                    <td>
                        @if($audit->old_values)
                            <span class="truncated-text" title="{{$audit->old_values}}">
                                {{strlen($audit->old_values) > 50 ? substr($audit->old_values, 0, 50) . '...' : $audit->old_values}}
                            </span>
                        @else
                            <span class="text-muted">-</span>
                        @endif
                    </td>
                    <td>
                        @if($audit->new_values)
                            <span class="truncated-text" title="{{$audit->new_values}}">
                                {{strlen($audit->new_values) > 50 ? substr($audit->new_values, 0, 50) . '...' : $audit->new_values}}
                            </span>
                        @else
                            <span class="text-muted">-</span>
                        @endif
                    </td>
                    <td>
                        <code style="font-size: 11px;">{{$audit->ip_address}}</code>
                    </td>
                    <td>
                        <span class="truncated-text" title="{{$audit->user_agent}}">
                            {{strlen($audit->user_agent) > 30 ? substr($audit->user_agent, 0, 30) . '...' : $audit->user_agent}}
                        </span>
                    </td>
                </tr>
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
        $('.locations').chosen({width: "100%"});
        
        $('.tables').DataTable({
            pageLength: 10,
            responsive: true,
            order: [[0, 'desc']],
            dom: '<"html5buttons"B>lfr<"bottom-controls"t<"info-paginate"ip>>',
            buttons: [
                {extend: 'copy'},
                {extend: 'csv'},
                {extend: 'excel', title: 'Audit Logs'},
                {extend: 'pdf', title: 'Audit Logs'},
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