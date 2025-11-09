@extends('layouts.header')

@section('css')
<link href="{{ asset('login_css/css/plugins/chosen/bootstrap-chosen.css') }}" rel="stylesheet">
<link href="{{ asset('login_css/css/plugins/dataTables/datatables.min.css') }}" rel="stylesheet">

<style>
    .document-header-card {
        background: white;
        border-radius: 10px;
        padding: 25px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        margin-bottom: 20px;
    }

    .document-title-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
        padding-bottom: 15px;
        border-bottom: 2px solid #f1f3f5;
        flex-wrap: wrap;
        gap: 15px;
    }

    .document-title-row h4 {
        margin: 0;
        font-size: 22px;
        font-weight: 600;
        color: #2c3e50;
        flex: 1;
    }

    .status-badge {
        padding: 6px 16px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.3px;
    }

    .status-badge.active {
        background: #d1e7dd;
        color: #0f5132;
    }

    .status-badge.obsolete {
        background: #f8d7da;
        color: #842029;
    }

    .action-buttons {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
    }

    .btn-modern {
        padding: 8px 16px;
        border-radius: 6px;
        font-size: 13px;
        font-weight: 500;
        border: none;
        cursor: pointer;
        transition: all 0.3s;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }

    .btn-modern:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.15);
    }

    .btn-modern.warning {
        background: #ffc107;
        color: #000;
    }

    .btn-modern.danger {
        background: #dc3545;
        color: white;
    }

    .btn-modern.success {
        background: #28a745;
        color: white;
    }

    .btn-modern.info {
        background: #17a2b8;
        color: white;
    }

    .details-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 25px;
        margin-bottom: 25px;
    }

    .detail-item {
        display: flex;
        flex-direction: column;
        gap: 6px;
    }

    .detail-label {
        font-size: 12px;
        font-weight: 600;
        color: #6c757d;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .detail-value {
        font-size: 14px;
        color: #2c3e50;
        font-weight: 500;
    }

    .owner-badge {
        background: #e7f3ff;
        color: #0066cc;
        padding: 4px 12px;
        border-radius: 4px;
        font-size: 12px;
        font-weight: 500;
        display: inline-block;
    }

    .danger-badge {
        background: #f8d7da;
        color: #842029;
        padding: 4px 12px;
        border-radius: 4px;
        font-size: 11px;
        font-weight: 600;
    }

    .attachments-card {
        background: white;
        border-radius: 10px;
        padding: 25px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        margin-bottom: 20px;
    }

    .attachments-header {
        font-size: 16px;
        font-weight: 600;
        color: #2c3e50;
        margin-bottom: 15px;
        padding-bottom: 10px;
        border-bottom: 2px solid #f1f3f5;
    }

    .attachment-item {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 12px;
        border-radius: 6px;
        transition: all 0.3s;
        margin-bottom: 8px;
    }

    .attachment-item:hover {
        background: #f8f9fa;
    }

    .attachment-item a {
        color: #0d6efd;
        text-decoration: none;
        font-size: 14px;
        font-weight: 500;
        display: flex;
        align-items: center;
        gap: 8px;
        flex: 1;
    }

    .attachment-item a:hover {
        color: #0b5ed7;
    }

    .attachment-item a i {
        font-size: 18px;
    }

    .attachment-item .text-danger {
        color: #dc3545;
        font-size: 16px;
        cursor: pointer;
        transition: all 0.3s;
    }

    .attachment-item .text-danger:hover {
        transform: scale(1.1);
    }
    .tabs-card {
        background: white;
        border-radius: 10px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        overflow: hidden;
    }

    .modern-tabs {
        display: flex;
        background: #f8f9fa;
        border-bottom: 2px solid #dee2e6;
        padding: 0;
        margin: 0;
        list-style: none;
        overflow-x: auto;
    }

    .modern-tabs li {
        flex: 1;
        min-width: 120px;
    }

    .modern-tabs li a {
        display: block;
        padding: 15px 20px;
        text-decoration: none;
        color: #6c757d;
        font-weight: 500;
        font-size: 14px;
        text-align: center;
        transition: all 0.3s;
        border-bottom: 3px solid transparent;
    }

    .modern-tabs li.active a,
    .modern-tabs li a:hover {
        color: #0d6efd;
        background: white;
        border-bottom-color: #0d6efd;
    }

    .tab-content-modern {
        padding: 25px;
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
        font-size: 12px;
        text-transform: uppercase;
        padding: 12px;
        border-bottom: 2px solid #dee2e6;
        white-space: nowrap;
    }

    .modern-table tbody td {
        padding: 12px;
        border-bottom: 1px solid #f1f3f5;
        vertical-align: middle;
        font-size: 13px;
    }

    .modern-table tbody tr:hover {
        background: #f8f9fa;
    }

    .modern-table a {
        color: #0d6efd;
        text-decoration: none;
        transition: color 0.3s;
    }

    .modern-table a:hover {
        color: #0b5ed7;
    }

    .label-modern {
        padding: 4px 10px;
        border-radius: 4px;
        font-size: 11px;
        font-weight: 600;
        text-transform: uppercase;
        display: inline-block;
    }

    .label-modern.warning {
        background: #fff3cd;
        color: #856404;
    }

    .label-modern.info {
        background: #d1ecf1;
        color: #0c5460;
    }

    .label-modern.danger {
        background: #f8d7da;
        color: #842029;
    }

    .label-modern.success {
        background: #d1e7dd;
        color: #0f5132;
    }

    .label-modern.primary {
        background: #cfe2ff;
        color: #084298;
    }

    @media (max-width: 768px) {
        .document-title-row {
            flex-direction: column;
            align-items: flex-start;
        }

        .action-buttons {
            width: 100%;
        }

        .btn-modern {
            flex: 1;
            justify-content: center;
        }

        .details-grid {
            grid-template-columns: 1fr;
        }

        .modern-tabs {
            flex-direction: column;
        }

        .modern-tabs li {
            min-width: unset;
        }
    }
</style>
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        @include('error')
        
        <div class="document-header-card">
            <div class="document-title-row">
                <h4>{{$document->title}}</h4>
                <div style="display: flex; align-items: center; gap: 15px;">
                    @if($document->status == null)
                        <span class="status-badge active">Active</span>
                    @else
                        <span class="status-badge obsolete">Obsolete</span>
                    @endif
                    @if(auth()->user()->role == "Document Control Officer")
                        <button class="btn-modern info" title='Edit' data-target="#edit_document" data-toggle="modal">
                            <i class="fa fa-edit"></i> Edit
                        </button>
                    @endif
                </div>
            </div>

            @if($document->status != "Obsolete")
            <div class="action-buttons">
                @if((auth()->user()->role == "User") || (auth()->user()->role == "Documents and Records Controller") || (auth()->user()->role == "Document Control Officer") || (auth()->user()->role == "Department Head"))
                    @if($document->department_id == 8)
                        <button class="btn-modern warning" data-target="#changeRequest" data-toggle="modal">
                            <i class="fa fa-exchange"></i> Change Request
                        </button>
                        <button class="btn-modern danger" data-target="#obsoleteRequest" data-toggle="modal">
                            <i class="fa fa-ban"></i> Obsolete Request
                        </button>
                    @endif 
                    @if(auth()->user()->role == "Documents and Records Controller")
                        @if(auth()->user()->department_id != $document->department_id)
                            @if(auth()->user()->audit_role == null)
                                <button class="btn-modern success" data-target="#copyRequest" data-toggle="modal">
                                    <i class="fa fa-copy"></i> Copy Request
                                </button>
                            @endif
                        @endif
                    @elseif(auth()->user()->role == "Document Control Officer") 
                        @php
                            $dep = (auth()->user()->dco)->where('department_id',$document->department_id);
                        @endphp
                        @if(count($dep) == 0)
                            @if(auth()->user()->audit_role == null)
                                <button class="btn-modern success" data-target="#copyRequest" data-toggle="modal">
                                    <i class="fa fa-copy"></i> Copy Request
                                </button>
                            @endif
                        @endif
                    @elseif(auth()->user()->role == "Department Head")
                        @php
                            $depd = 0;
                        @endphp
                        @foreach(auth()->user()->department_head as $dep)
                            @if($dep->id == $document->department_id)
                                @php
                                    $depd = 1;
                                @endphp
                            @endif
                        @endforeach
                        @if($depd == 0)
                            @if(auth()->user()->audit_role == null)
                                <button class="btn-modern success" data-target="#copyRequest" data-toggle="modal">
                                    <i class="fa fa-copy"></i> Copy Request
                                </button>
                            @endif
                        @endif
                    @else
                        @if(auth()->user()->audit_role == null)
                            <button class="btn-modern success" data-target="#copyRequest" data-toggle="modal">
                                <i class="fa fa-copy"></i> Copy Request
                            </button>
                            @if($document->department_id == auth()->user()->department_id)
                                <button class="btn-modern warning" data-target="#changeRequest" data-toggle="modal">
                                    <i class="fa fa-exchange"></i> Change Request
                                </button>
                                <button class="btn-modern danger" data-target="#obsoleteRequest" data-toggle="modal">
                                    <i class="fa fa-ban"></i> Obsolete Request
                                </button>
                            @endif
                        @endif
                    @endif
                @endif
                @if(auth()->user()->role == "Documents and Records Controller")
                    @if(auth()->user()->department_id == $document->department_id)
                        <button class="btn-modern warning" data-target="#changeRequest" data-toggle="modal">
                            <i class="fa fa-exchange"></i> Change Request
                        </button>
                        <button class="btn-modern danger" data-target="#obsoleteRequest" data-toggle="modal">
                            <i class="fa fa-ban"></i> Obsolete Request
                        </button>
                    @endif
                @endif
                @if(auth()->user()->role == "Document Control Officer")
                    @php
                        $dep = (auth()->user()->dco)->where('department_id',$document->department_id);
                    @endphp
                    @if(count($dep) == 1)
                        {{-- <button class="btn-modern warning" data-target="#changeRequest" data-toggle="modal">
                            <i class="fa fa-exchange"></i> Change Request
                        </button> --}}
                        <button class="btn-modern danger" data-target="#obsoleteRequest" data-toggle="modal">
                            <i class="fa fa-ban"></i> Obsolete Request
                        </button>
                    @endif
                @endif
            </div>
            @endif

            <!-- Document Details Grid -->
            <div class="details-grid" style="margin-top: 25px;">
                <div class="detail-item">
                    <span class="detail-label">Uploaded By</span>
                    <span class="detail-value">{{$document->user->name}}</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Control Code</span>
                    <span class="detail-value">{{$document->control_code}}</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Revisions</span>
                    <span class="detail-value">{{$document->version}}</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Type of Document</span>
                    <span class="detail-value">{{$document->category}}</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Company</span>
                    <span class="detail-value">{{$document->company->name}}</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Department</span>
                    <span class="detail-value">{{$document->department->name}}</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Department Head</span>
                    <span class="detail-value">
                        @if($document->department->dep_head)
                            <span class="owner-badge">{{$document->department->dep_head->name}}</span>
                        @else
                            <span class="danger-badge">No Department Head</span>
                        @endif
                    </span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Created</span>
                    <span class="detail-value">{{date('M, d Y',strtotime($document->created_at))}}</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Effective Updated</span>
                    <span class="detail-value">{{date('M, d Y',strtotime($document->updated_at))}}</span>
                </div>
                {{-- <div class="detail-item">
                    <span class="detail-label">Effective Date</span>
                    <span class="detail-value">{{date('M, d Y',strtotime($document->effective_date))}}</span>
                </div> --}}
                {{-- <div class="detail-item">
                    <span class="detail-label">Process Owner</span>
                    <span class="detail-value">
                        @if(count($document->department->drc) != 0)
                            @foreach($document->department->drc as $drc)
                                <span class="owner-badge">{{$drc->name}}</span>
                            @endforeach
                        @else
                            <span class="danger-badge">No Process Owner</span>
                        @endif
                    </span>
                </div> --}}
                <div class="detail-item">
                    <span class="detail-label">Process Owner</span>
                    <span class="detail-value">
                        @if($document->process_owner != null)
                            <span class="owner-badge">{{$document->processOwner->name}}</span>
                        @else
                            <span class="owner-badge">{{$document->department->dep_head->name}}</span>
                        @endif
                    </span>
                </div>
                {{-- <div class="detail-item">
                    <span class="detail-label">Access</span>
                    <span class="detail-value project-people">
                        <a href=""><img alt="image" class="img-circle" src="{{asset('login_css/img/a3.jpg')}}"></a>
                        <a href=""><img alt="image" class="img-circle" src="{{asset('login_css/img/a1.jpg')}}"></a>
                        <a href=""><img alt="image" class="img-circle" src="{{asset('login_css/img/a2.jpg')}}"></a>
                        <a href=""><img alt="image" class="img-circle" src="{{asset('login_css/img/a4.jpg')}}"></a>
                        <a href=""><img alt="image" class="img-circle" src="{{asset('login_css/img/a5.jpg')}}"></a>
                    </span>
                </div> --}}
            </div>
        </div>

        @php
            $allow = 0;
        @endphp
        @foreach($document->department->drc as $drc)
            @if($drc->id == auth()->user()->id)
                @php
                    $allow = 1;
                @endphp
            @endif
        @endforeach
        @foreach(auth()->user()->department_head as $dep)
            @if($dep->id == $document->department_id)
                @php
                    $allow = 1;
                @endphp
            @endif
        @endforeach
        @foreach($document->department->dco as $dco)
            @if($dco->user_id == auth()->user()->id)
                @php
                    $allow = 1;
                @endphp
            @endif
        @endforeach
        @foreach($document->department->departments as $depa)
            @if($depa->user_id == auth()->user()->id)
                @php
                    $allow = 1;
                @endphp
            @endif
        @endforeach
        
        @if((auth()->user()->role == "Administrator") || (auth()->user()->role == "Business Process Manager") || (auth()->user()->role == "Management Representative"))
            @php
                $allow = 1;
            @endphp
        @endif
        @foreach(auth()->user()->department_head as $head)
            @if($head->user_id == $document->department_id)
                @php
                    $allow = 1;
                @endphp
            @endif
        @endforeach
        @if((auth()->user()->audit_role != null))
            @php
                $allow = 1;
            @endphp
        @endif
        @php
        if(auth()->user()->department_id == $document->department_id)
        {
            $allow =1;
        }
        @endphp

        @if($allow == 1)
        {{-- @if($document->status == null) --}}
        <div class="attachments-card">
            <div class="attachments-header">
                <i class="fa fa-paperclip"></i> Attachments
            </div>
            
            @foreach($document->attachments as $attachment)
                @if($attachment->attachment != null)
                    @if(($attachment->type == "soft_copy") && ($document->department_id == 8))
                    <div class="attachment-item">
                        <a href='{{url($attachment->attachment)}}' target="_blank">
                            <i class="fa fa-file-word-o"></i>
                            <span>Editable Copy</span>
                        </a>
                        @if((auth()->user()->role == 'Administrator') || (auth()->user()->role == 'Business Process Manager') || (auth()->user()->role == 'Management Representative') || (auth()->user()->role == "Document Control Officer"))
                        <a href='#' class='text-danger' data-target="#edit{{$attachment->id}}" data-toggle="modal">
                            <i class="fa fa-edit"></i>
                        </a>
                        @endif
                    </div>
                    @endif
                    @if(($attachment->type == "soft_copy") && (auth()->user()->audit_role == null))
                    <div class="attachment-item">
                        <a href='{{url($attachment->attachment)}}' target="_blank">
                            <i class="fa fa-file-word-o"></i>
                            <span>Editable Copy</span>
                        </a>
                        @if((auth()->user()->role == 'Administrator') || (auth()->user()->role == 'Business Process Manager') || (auth()->user()->role == 'Management Representative') || (auth()->user()->role == "Document Control Officer"))
                        <a href='#' class='text-danger' data-target="#edit{{$attachment->id}}" data-toggle="modal">
                            <i class="fa fa-edit"></i>
                        </a>
                        @endif
                    </div>
                    @elseif($attachment->type == "pdf_copy")
                        @if(($document->category == "FORM") || ($document->category == "TEMPLATE"))
                        <div class="attachment-item">
                            <a href='{{url($attachment->attachment)}}' target="_blank">
                                <i class="fa fa-file-pdf-o"></i>
                                <span>PDF Copy</span>
                            </a>
                            @if((auth()->user()->role == 'Administrator') || (auth()->user()->role == 'Business Process Manager') || (auth()->user()->role == 'Management Representative') || (auth()->user()->role == "Document Control Officer"))
                            <a href='#' class='text-danger' data-target="#edit{{$attachment->id}}" data-toggle="modal">
                                <i class="fa fa-edit"></i>
                            </a>
                            @endif
                        </div>
                        @else
                        <div class="attachment-item">
                            <a href='{{url('view-pdf/'.$attachment->id)}}' target="_blank">
                                <i class="fa fa-file-pdf-o"></i>
                                <span>PDF Copy</span>
                            </a>
                            @if((auth()->user()->role == 'Administrator') || (auth()->user()->role == 'Business Process Manager') || (auth()->user()->role == 'Management Representative') || (auth()->user()->role == "Document Control Officer"))
                            <a href='#' class='text-danger' data-target="#edit{{$attachment->id}}" data-toggle="modal">
                                <i class="fa fa-edit"></i>
                            </a>
                            @endif
                        </div>
                        @endif
                    @else
                        @if((auth()->user()->audit_role == null))
                        <div class="attachment-item">
                            <a href='{{url($attachment->attachment)}}' target="_blank">
                                <i class="fa fa-file-pdf-o"></i>
                                <span>Fillable Copy</span>
                            </a>
                        </div>
                        @endif
                    @endif
                @endif
                @include('change_file')
            @endforeach
        </div>
        {{-- @endif --}}

        @if((auth()->user()->audit_role == null))
        <div class="tabs-card mb-5">
            <ul class="modern-tabs">
                <li class="active">
                    <a href="#tab-1" data-toggle="tab">Revisions</a>
                </li>
                <li>
                    <a href="#tab-2" data-toggle="tab">Change Requests</a>
                </li>
                <li>
                    <a href="#tab-3" data-toggle="tab">Copy Requests</a>
                </li>
                <li>
                    <a href="#tab-4" data-toggle="tab">Memo</a>
                </li>
            </ul>

            <div class="tab-content">
                <!-- Revisions Tab -->
                <div class="tab-pane active" id="tab-1">
                    <div class="tab-content-modern">
                        <div class="table-responsive">
                            <table class="modern-table tables">
                                <thead>
                                    <tr>
                                        <th>Control Code</th>
                                        <th>Company</th>
                                        <th>Department</th>
                                        <th>Revision</th>
                                        <th>Date Obsolete</th>
                                        <th>Obsolete By</th>
                                        <th>Attachments</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($document->revisions as $revision)
                                        <tr>
                                            <td>{{$revision->control_code}}</td>
                                            <td>{{$revision->company->name}}</td>
                                            <td>{{$revision->department->name}}</td>
                                            <td>{{$revision->version}}</td>
                                            <td>{{date('M d, Y',strtotime($revision->created_at))}}</td>
                                            <td>{{$revision->user->name}}</td>
                                            <td>
                                                @foreach($revision->attachments as $att)
                                                <a href='{{url($att->attachment)}}' target='_blank'>{{$att->type}}</a>
                                                <br>
                                                @endforeach
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="tab-pane" id="tab-2">
                    <div class="tab-content-modern">
                        <div class="table-responsive">
                            <table class="modern-table tables">
                                <thead>
                                    <tr>
                                        <th>DICR #</th>
                                        <th>Type of Request</th>
                                        <th>Requested Date</th>
                                        <th>Requestor</th>
                                        <th>Department</th>
                                        <th>Proposed Effective Date</th>
                                        <th>Type of Document</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($document->change_requests as $change_req)
                                        <tr>
                                            <td>DICR-{{str_pad($change_req->id, 5, '0', STR_PAD_LEFT)}}</td>
                                            <td>{{$change_req->request_type}}</td>
                                            <td>{{date('M d Y',strtotime($change_req->created_at))}}</td>
                                            <td>{{$change_req->user->name}}</td>
                                            <td>{{$change_req->department->name}}</td>
                                            <td>{{date('M d, Y',strtotime($change_req->effective_date))}}</td>
                                            <td>{{$change_req->type_of_document}}</td>
                                            <td>
                                                @if($change_req->status == "Pending")
                                                    <span class='label-modern warning'>
                                                @elseif($change_req->status ==  "Approved")
                                                    <span class='label-modern info'>
                                                @elseif($change_req->status ==  "Declined")
                                                    <span class='label-modern danger'>
                                                @else
                                                    <span class='label-modern success'>
                                                @endif
                                                {{$change_req->status}}</span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="tab-pane" id="tab-3">
                    <div class="tab-content-modern">
                        <div class="table-responsive">
                            <table class="modern-table tables">
                                <thead>
                                    <tr>
                                        <th>Reference #</th>
                                        <th>Type of Document</th>
                                        <th>Date Requested</th>
                                        <th>Date Needed</th>
                                        <th>Requestor</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($document->copy_requests as $copy_request)
                                    <tr>
                                        <td>CR-{{str_pad($copy_request->id, 5, '0', STR_PAD_LEFT)}}</td>
                                        <td>{{$copy_request->type_of_document}}</td>
                                        <td>{{date('M d Y',strtotime($copy_request->created_at))}}</td>
                                        <td>{{date('M d Y',strtotime($copy_request->date_needed))}}</td>
                                        <td>{{$copy_request->user->name}}</td>
                                        <td>{{$copy_request->status}}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="tab-pane" id="tab-4">
                    <div class="tab-content-modern">
                        <div class="table-responsive">
                            <table class="modern-table tables">
                                <thead>
                                    <tr>
                                        <th>Memo #</th>
                                        <th>Title</th>
                                        <th>Status</th>
                                        <th>Attachment</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($document->memo_document as $memo_docs)
                                        <tr>
                                            <td>{{$memo_docs->memorandum->memo_number}}</td>
                                            <td>{{$memo_docs->memorandum->title}}</td>
                                            <td>
                                                @if($memo_docs->memorandum->status == 'Private')
                                                    <span class="label-modern danger">Private</span>
                                                @else
                                                    <span class="label-modern primary">Public</span>
                                                @endif
                                            </td>
                                            <td>
                                                <a href="{{url($memo_docs->memorandum->file_memo)}}" target="_blank">
                                                    <i class="fa fa-file"></i> View
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif
        @endif
    </div>
</div>

<!-- Edit Document Modal -->
<div class="modal" id="edit_document" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <div class='col-md-10'>
                    <h5 class="modal-title" id="exampleModalLabel">Change Document</h5>
                </div>
                <div class='col-md-2'>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            </div>
            <form method='post' action='edit-document/{{$document->id}}' onsubmit='show();' enctype="multipart/form-data">
                <div class="modal-body">
                    {{ csrf_field() }}
                    <div class='row'>
                        <div class='col-md-12'>
                            Title :
                            <input type="text" class="form-control-sm form-control" value='{{$document->title}}' name="title" required/>
                        </div>
                        <div class='col-md-12'>
                            Revision # :
                            <input type="text" class="form-control-sm form-control" value='{{$document->version}}' name="revision" required/>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type='submit' class="btn btn-primary">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>

@include('obsolete_request_image')
@include('copy_request')
@include('change_request')
@include('obsolete_request')
@endsection



@section('js')
<script src="{{ asset('login_css/js/plugins/dataTables/datatables.min.js')}}"></script>
<script src="{{ asset('login_css/js/plugins/chosen/chosen.jquery.js') }}"></script>
<script type="text/javascript">
    $(window).on('load', function() {
        $('#myModal').modal('show');
    });
</script>
<script>
    $(document).ready(function(){
        $('.cat').chosen({width: "100%"});
        $('.tables').DataTable({
            pageLength: 25,
            responsive: true,
            bPaginate: false,
            bInfo : false,
            dom: '<"html5buttons"B>lTfgitp',
            buttons: [
                {extend: 'pdf', title: 'Histories'},
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