@extends('layouts.header')

@section('css')
<link href="{{ asset('login_css/css/plugins/chosen/bootstrap-chosen.css') }}" rel="stylesheet">
<link href="{{ asset('login_css/css/plugins/sweetalert/sweetalert.css') }}" rel="stylesheet">
<link href="{{ asset('login_css/css/plugins/dataTables/datatables.min.css') }}" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

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

    .content-section {
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

    .btn-upload {
        background: #8B0000;
        color: white;
        border: none;
        padding: 10px 20px;
        border-radius: 5px;
        font-weight: 500;
        transition: all 0.3s;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }

    .btn-upload:hover {
        background: #6B0000;
        color: white;
        transform: translateY(-1px);
        box-shadow: 0 4px 8px rgba(40, 167, 69, 0.3);
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

    .badge-status.private {
        background: #ffebee;
        color: #f44336;
    }

    .badge-status.public {
        background: #e3f2fd;
        color: #2196F3;
    }

    .btn-action {
        padding: 6px 10px;
        border: none;
        border-radius: 4px;
        font-size: 13px;
        cursor: pointer;
        transition: all 0.2s;
        margin-right: 4px;
    }

    .btn-action.btn-edit {
        background: #ffc107;
        color: #000;
    }

    .btn-action.btn-edit:hover {
        background: #e0a800;
    }

    .btn-action.btn-delete {
        background: #dc3545;
        color: white;
    }

    .btn-action.btn-delete:hover {
        background: #c82333;
    }

    .file-link {
        color: #8B0000;
        text-decoration: none;
        font-size: 18px;
        transition: all 0.2s;
    }

    .file-link:hover {
        color: #6B0000;
        transform: scale(1.1);
    }

    .policy-link {
        color: #2196F3;
        text-decoration: none;
        transition: all 0.2s;
    }

    .policy-link:hover {
        color: #1976D2;
        text-decoration: underline;
    }

    .checkbox-public {
        width: 18px;
        height: 18px;
        cursor: pointer;
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

    @media (max-width: 768px) {
        .dashboard-card {
            margin-bottom: 15px;
        }
    }
</style>
@endsection

@section('content')
 
<div class="row mb-4 dashboard-header">
    <div class="col-12">
        <h4 class="mb-0">Memorandum Management</h4>
        <p class="text-muted mb-0">Manage and track company memorandums</p>
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-lg-3 col-md-6">
        <div class="dashboard-card total">
            <div class="icon-circle">
                <i class="fa fa-file-text"></i>
            </div>
            <h2>{{count($memos)}}</h2>
            <p>Total Memorandums</p>
        </div>
    </div>
</div>

<!-- Main Content Section -->
<div class="row">
    <div class="col-lg-12">
        <div class="content-section">
            <div class="section-header">
                <h5 class="section-title">Memorandum List</h5>
                <button type="button" class="btn-upload" data-toggle="modal" data-bs-toggle="modal" data-target="#new" data-bs-target="#new">
                    <i class="fa fa-plus"></i>
                    Upload Memorandum
                </button>
            </div>

            
            <div class="table-container">
                <table class="modern-table tables">
                    <thead>
                        <tr>
                            <th>Action</th>
                            <th>Public</th>
                            <th>Department</th>
                            <th>Memo Number</th>
                            <th>Title</th>
                            <th>Released Date</th>
                            <th>Uploaded By</th>
                            <th>Align Policy</th>
                            <th>Attachment</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($memos as $memo)
                            <tr>
                                <td>
                                    <div class="dropdown">
                                        <button class="btn btn-sm btn-outline-secondary" type="button" id="memoDropdown{{$memo->id}}" data-bs-toggle="dropdown" aria-expanded="false">
                                            <i class="ri-more-2-fill"></i>
                                        </button>
                                        <ul class="dropdown-menu" aria-labelledby="memoDropdown{{$memo->id}}">
                                            <li>
                                                <button class="dropdown-item" data-bs-toggle="modal" data-bs-target="#edit{{$memo->id}}">
                                                    <i class="fa fa-pencil-square-o me-2"></i>Edit
                                                </button>
                                            </li>
                                            @if(auth()->user()->role == 'Document Control Officer')
                                                <li>
                                                    <button class="dropdown-item deleteMemo" id="{{ $memo->id }}">
                                                        <i class="fa fa-trash me-2"></i>Delete
                                                    </button>
                                                </li>
                                            @endif
                                        </ul>
                                    </div>
                                </td>
                                <td>
                                    <form method="POST" action="{{url('update_status/'.$memo->id)}}" onsubmit="show()" id="updateStatusForm{{$memo->id}}">
                                        @csrf 
                                        <input type="checkbox" name="status" class="checkbox-public" onchange="updateStatus({{$memo->id}})" value="Public" @if($memo->status == 'Public') checked @endif>
                                    </form>
                                </td>
                                <td>{{$memo->department->name}}</td>
                                <td><strong>{{$memo->memo_number}}</strong></td>
                                <td>{{$memo->title}}</td>
                                <td>{{date('M. d, Y', strtotime($memo->released_date))}}</td>
                                <td>{{$memo->user->name}}</td>
                                <td>
                                    @foreach ($memo->memorandum_document as $memo_docs)
                                        <a href="{{url('view-document/'.$memo_docs->document->id)}}" class="policy-link">
                                            {{$memo_docs->document->control_code}}
                                        </a><br>
                                    @endforeach
                                </td>
                                <td class="text-center">
                                    <a href="{{url($memo->file_memo)}}" target="_blank" class="file-link">
                                        <i class="fa fa-file"></i>
                                    </a>
                                </td>
                                <td>
                                    @if($memo->status == 'Private')
                                        <span class="badge-status private">Private</span>
                                    @else
                                        <span class="badge-status public">Public</span>
                                    @endif
                                </td>
                            </tr>

                            @include('edit_memorandum')
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@include('new_memorandum')
@endsection

@section('js')
<script src="{{ asset('login_css/js/plugins/dataTables/datatables.min.js')}}"></script>
<script src="{{ asset('login_css/js/plugins/chosen/chosen.jquery.js') }}"></script>
<script src="{{ asset('login_css/js/plugins/sweetalert/sweetalert.min.js') }}"></script>
<script>
    function updateStatus(id)
    {
        $('#updateStatusForm'+id).submit()
    }

    $(document).ready(function(){
        $(".cat").chosen({width:"100%"})

        $('.tables').DataTable({
            pageLength: 25,
            responsive: true,
            sorting: false,
            dom: '<"html5buttons"B>lfr<"bottom-controls"t<"info-paginate"ip>>',
            buttons: [
                {extend: 'copy'},
                {extend: 'csv'},
                {extend: 'excel', title: 'Memorandum List'},
                {extend: 'pdf', title: 'Memorandum List'},
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

        $("#type").on('change', function() {
            if($(this).val() == 'Align Policy')
            {
                $("#policySelectOption").removeAttr('hidden')
                $("[name='document[]']").prop('required', true)
            }
            else
            {
                $("#policySelectOption").prop('hidden', true)
                $("[name='document[]']").removeAttr('required')
            }
        })

        $('.deleteMemo').click(function () {
            var id = this.id;
            
            swal({
                title: "Are you sure?",
                text: "This memo will be deleted!",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "Yes, delete it!",
                closeOnConfirm: false
            }, function (){
                $.ajax({
                    dataType: 'json',
                    type:'POST',
                    url:  '{{url("delete_memo")}}',
                    data:{id:id},
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                }).done(function(data){
                    console.log(data);
                    swal("Deleted!", "Memo is now deleted.", "success");
                    location.reload();
                }).fail(function(data)
                {
                    swal("Deleted!", "Memo is now deleted.", "success");
                    location.reload();
                });
            });
        });
    });
</script>
@endsection