@extends('layouts.header')
@section('css')
<link href="{{ asset('login_css/css/plugins/chosen/bootstrap-chosen.css') }}" rel="stylesheet">
@endsection
@section('content')

<div class="wrapper wrapper-content">
    @include('error')
   
    <div class="row">
        <div class="col-lg-12">
            <div class="wrapper wrapper-content ">
                <div class="ibox">
                    <div class="ibox-title">
                        
                        <h5>{{$document->title}} </h5>
                        
                        @if($document->status == null)<span class="label label-primary">Active</span>  @else<span class="label label-danger">Obsolete</span> @endif
                        <div class='float-right text-right'>
                            @if(auth()->user()->role == "Document Control Officer")
                        <button class="btn btn-sm btn-info "  title='Edit' data-target="#edit_document" data-toggle="modal"><i class="fa fa-edit"></i></button> 
                        @endif
                        </div>
                    </div>
                    <div class="ibox-content">
                        
                        @if($document->status != "Obsolete")
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="m-b-md">
                                        @if((auth()->user()->role == "User") || (auth()->user()->role == "Documents and Records Controller") || (auth()->user()->role == "Document Control Officer") || (auth()->user()->role == "Department Head"))
                                            @if($document->department_id == 8)
                                                <a href="#" data-target="#changeRequest" data-toggle="modal" class="btn btn-warning btn-sm ">Change Request </a>
                                                <a href="#"  data-target="#obsoleteRequest" data-toggle="modal"  class="btn btn-danger btn-sm ">Obsolete Request </a>
                                            @endif 
                                            @if(auth()->user()->role == "Documents and Records Controller")
                                                @if(auth()->user()->department_id != $document->department_id)
                                                    @if(auth()->user()->audit_role == null)
                                                        <a href="#" data-target="#copyRequest" data-toggle="modal"  class="btn btn-success btn-sm ">Copy Request </a>
                                                    @endif
                                                @endif
                                            @elseif(auth()->user()->role == "Document Control Officer") 
                                                @php
                                                    $dep = (auth()->user()->dco)->where('department_id',$document->department_id);
                                                @endphp
                                                @if(count($dep) == 0)
                                                    @if(auth()->user()->audit_role == null)
                                                        <a href="#" data-target="#copyRequest" data-toggle="modal"  class="btn btn-success btn-sm ">Copy Request </a>
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
                                                        <a href="#" data-target="#copyRequest" data-toggle="modal"  class="btn btn-success btn-sm ">Copy Request </a>
                                                    @endif
                                                @endif
                                            @else
                                                @if(auth()->user()->audit_role == null)
                                                    <a href="#" data-target="#copyRequest" data-toggle="modal"  class="btn btn-success btn-sm ">Copy Request </a>
                                                    @if($document->department_id == auth()->user()->department_id)
                                                    <a href="#" data-target="#changeRequest" data-toggle="modal" class="btn btn-warning btn-sm ">Change Request </a>
                                                    <a href="#"  data-target="#obsoleteRequest" data-toggle="modal"  class="btn btn-danger btn-sm ">Obsolete Request </a>
                                                    @endif
                                                @endif
                                            @endif
                                        @endif
                                    @if(auth()->user()->role == "Documents and Records Controller")
                                        @if(auth()->user()->department_id == $document->department_id)
                                            <a href="#" data-target="#changeRequest" data-toggle="modal" class="btn btn-warning btn-sm ">Change Request </a>
                                            <a href="#"  data-target="#obsoleteRequest" data-toggle="modal"  class="btn btn-danger btn-sm ">Obsolete Request </a>
                                        @endif
                                    @endif
                                    @if(auth()->user()->role == "Document Control Officer")
                                        @php
                                            $dep = (auth()->user()->dco)->where('department_id',$document->department_id);
                                        @endphp
                                        @if(count($dep) == 1)
                                            {{-- <a href="#" data-target="#changeRequest" data-toggle="modal" class="btn btn-warning btn-sm ">Change Request </a> --}}
                                            <a href="#"  data-target="#obsoleteRequest" data-toggle="modal"  class="btn btn-danger btn-sm ">Obsolete Request </a>
                                        @endif
                                    @endif
                                    
                                </div>
                            </div>
                        </div>
                        
                        @endif
                        <div class="row">
                            <div class="col-lg-6">
                                <dl class="dl-horizontal">
                                    <dt>Uploaded by:</dt> <dd>{{$document->user->name}}</dd>
                                    <dt>Control Code:</dt> <dd>  {{$document->control_code}}</dd>
                                    <dt>Revisions:</dt> <dd>  {{$document->version}}</dd>
                                    <dt>Type of Document:</dt> <dd>  {{$document->category}}</dd>
                                    <dt>Company:</dt> <dd>{{$document->company->name}}</dd>
                                    <dt>Department:</dt> <dd> 	{{$document->department->name}}</dd>
                                    <dt>Department Head:</dt> <dd> 	@if($document->department->dep_head){{$document->department->dep_head->name}}@else <small class='label label-danger'>No Department Head</small>@endif</dd>
                                </dl>
                            </div>
                            <div class="col-lg-6" >
                                <dl class="dl-horizontal" >

                                    <dt>Created:</dt> <dd> 	{{date('M, d Y',strtotime($document->created_at))}} </dd>
                                    <dt>Effective Updated:</dt> <dd>{{date('M, d Y',strtotime($document->updated_at))}}</dd>
                                    {{-- <dt>Effective Date:</dt> <dd>{{date('M, d Y',strtotime($document->effective_date))}}</dd> --}}
                                    {{-- <dt>Process Owner:</dt> <dd>@if(count($document->department->drc) != 0) @foreach($document->department->drc as $drc) <small class="label label-info"> {{$drc->name}} </small> @endforeach @else <small class="label label-danger">No Process Owner</small>  @endif</dd> --}}
                                    <dt>Process Owner:</dt> <dd>@if($document->process_owner != null) <small class="label label-info">{{$document->processOwner->name}}</small> @else <small class="label label-info">{{$document->department->dep_head->name}}</small> @endif</dd>
                                    {{-- <dt>Access:</dt>
                                    <dd class="project-people">
                                        <a href=""><img alt="image" class="img-circle" src="{{asset('login_css/img/a3.jpg')}}"></a>
                                        <a href=""><img alt="image" class="img-circle" src="{{asset('login_css/img/a1.jpg')}}"></a>
                                        <a href=""><img alt="image" class="img-circle" src="{{asset('login_css/img/a2.jpg')}}"></a>
                                        <a href=""><img alt="image" class="img-circle" src="{{asset('login_css/img/a4.jpg')}}"></a>
                                        <a href=""><img alt="image" class="img-circle" src="{{asset('login_css/img/a5.jpg')}}"></a>
                                    </dd> --}}
                                </dl>
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
                        <div class="row">
                            <div class="col-lg-12">
                                <dl class="dl-horizontal">

                                    <dt>Attachments</dt>
                                        @foreach($document->attachments as $attachment)
                                            @if($attachment->attachment != null)
                                                @if(($attachment->type == "soft_copy") && ($document->department_id == 8))
                                                <dd><a href='{{url($attachment->attachment)}}' target="_blank" ><i class="fa fa-file-word-o"></i> Editable Copy</a> 
                                                    @if((auth()->user()->role == 'Administrator') || (auth()->user()->role == 'Business Process Manager') || (auth()->user()->role == 'Management Representative') || (auth()->user()->role == "Document Control Officer"))
                                                    <a href='#' class='text-danger'  data-target="#edit{{$attachment->id}}"  data-toggle="modal" ><i class="fa fa-edit"></i> </a>
                                                    @endif
                                                </dd>
                                                @endif
                                                @if(($attachment->type == "soft_copy") && (auth()->user()->audit_role == null))
                                                <dd><a href='{{url($attachment->attachment)}}' target="_blank" ><i class="fa fa-file-word-o"></i> Editable Copy</a> 
                                                    @if((auth()->user()->role == 'Administrator') || (auth()->user()->role == 'Business Process Manager') || (auth()->user()->role == 'Management Representative') || (auth()->user()->role == "Document Control Officer"))
                                                    <a href='#' class='text-danger'  data-target="#edit{{$attachment->id}}"  data-toggle="modal" ><i class="fa fa-edit"></i> </a>
                                                    @endif
                                                </dd>
                                                @elseif($attachment->type == "pdf_copy")
                                                    @if(($document->category == "FORM") || ($document->category == "TEMPLATE"))
                                                    <dd><a href='{{url($attachment->attachment)}}' target="_blank" ><i class="fa fa-file-pdf-o"></i> PDF Copy</a>    @if((auth()->user()->role == 'Administrator') || (auth()->user()->role == 'Business Process Manager') || (auth()->user()->role == 'Management Representative') || (auth()->user()->role == "Document Control Officer"))
                                                        <a href='#' class='text-danger'  data-target="#edit{{$attachment->id}}" data-toggle="modal"  ><i class="fa fa-edit"></i> </a>
                                                        @endif</dd>
                                                    @else
                                                    <dd><a href='{{url('view-pdf/'.$attachment->id)}}' target="_blank" ><i class="fa fa-file-pdf-o"></i> PDF Copy</a>    @if((auth()->user()->role == 'Administrator') || (auth()->user()->role == 'Business Process Manager') || (auth()->user()->role == 'Management Representative') || (auth()->user()->role == "Document Control Officer"))
                                                        <a href='#' class='text-danger' data-target="#edit{{$attachment->id}}" data-toggle="modal"   ><i class="fa fa-edit"></i> </a>
                                                        @endif</dd>
                                                    @endif
                                                    
                                                @else
                                                @if((auth()->user()->audit_role == null))
                                               
                                                <dd><a href='{{url($attachment->attachment)}}' target="_blank" ><i class="fa fa-file-pdf-o"></i> Fillable Copy</a></dd>
                                                @endif
                                                @endif
                                            
                                             @endif
                                             @include('change_file')
                                        @endforeach
                                   
                                </dl>
                            </div>
                        </div>
                        {{-- @endif --}}
                        @if((auth()->user()->audit_role == null))
                        <div class="row m-t-sm">
                            <div class="col-lg-12">
                            <div class="panel blank-panel">
                            <div class="panel-heading">
                                <div class="panel-options">
                                    <ul class="nav nav-tabs">
                                        <li class="active"><a href="#tab-1" data-toggle="tab">Revisions</a></li>
                                        <li class=""><a href="#tab-2" data-toggle="tab">Change Requests</a></li>
                                        <li class=""><a href="#tab-3" data-toggle="tab">Copy Requests</a></li>
                                        <li class=""><a href="#tab-4" data-toggle="tab">Memo</a></li>
                                    </ul>
                                </div>
                            </div>
                            
                            <div class="panel-body">

                            <div class="tab-content">
                            <div class="tab-pane active" id="tab-1">
                                <table class="table table-striped tables">
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
                            <div class="tab-pane" id="tab-2">

                                <table class="table table-striped tables">
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
                                                <td>@if($change_req->status == "Pending")
                                                    <span class='label label-warning'>
                                                @elseif($change_req->status ==  "Approved")
                                                    <span class='label label-info'>    
                                                @elseif($change_req->status ==  "Declined")
                                                        <span class='label label-danger'>
                                                @else<span class='label label-success'>
                                                    @endif
                                                    {{$change_req->status}}</span> </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>

                            </div>
                            <div class="tab-pane" id="tab-3">

                                <table class="table table-striped tables">
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
                            <div class="tab-pane" id="tab-4">
                                <table class="table table-striped tables">
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
                                                        <span class="label label-danger">Private</span>
                                                    @else
                                                        <span class="label label-primary">Public</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <a href="{{url($memo_docs->memorandum->file_memo)}}" target="_blank">
                                                        <i class="fa fa-file"></i>
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
                        </div>
                        @endif
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@include('copy_request')
@include('change_request')
@include('obsolete_request')
@endsection

<div class="modal" id="edit_document" tabindex="-1" role="dialog"  >
    <div class="modal-dialog modal-lg " role="document">
        <div class="modal-content">
            <div class="modal-header">
                <div class='col-md-10'>
                    <h5 class="modal-title" id="exampleModalLabel">Change Document</h5>
                </div>
                <div class='col-md-2'>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close" >
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            </div>
            <form method='post' action='edit-document/{{$document->id}}' onsubmit='show();'  enctype="multipart/form-data" >
                <div class="modal-body">
                    {{ csrf_field() }}
                    <div class='row'>
                        <div class='col-md-12'>
                           Title :
                            <input type="text" class="form-control-sm form-control "  value='{{$document->title}}' name="title" required/>
                        </div>
                        <div class='col-md-12'>
                            Revision # :
                            <input type="text" class="form-control-sm form-control "  value='{{$document->version}}'  name="revision" required/>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type='submit'  class="btn btn-primary" >Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>
@include('obsolete_request_image')
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
