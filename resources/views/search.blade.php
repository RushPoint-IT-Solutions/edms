@extends('layouts.header')
@section('css')
<link href="{{ asset('login_css/css/plugins/chosen/bootstrap-chosen.css') }}" rel="stylesheet">

<link href="{{ asset('login_css/css/plugins/sweetalert/sweetalert.css') }}" rel="stylesheet">
@endsection
@section('content')

<div class="wrapper wrapper-content ">
   
    <div class="row ">
        <div class="col-lg-8 stretch-card">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>Search Documents</h5>
                </div>
                <div class="ibox-content stretch-card">
                    <div class="search-form">
                        <form action="" method="get">
                            <div class='row'>
                                <div class='col-md-3'>
                                    Company :
                                    <select name='company' class='form-control-sm form-control cat' >
                                        <option value="">N/A</option>
                                        @foreach($companies as $company)
                                            <option value='{{$company->id}}' @if($comp == $company->id) selected @endif>{{$company->name}} - {{$company->code}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class='col-md-3'>
                                    Department :
                                    <select name='department' class='form-control-sm form-control cat' >
                                        <option value="">N/A</option>
                                        @foreach($departments as $dep)
                                            <option value='{{$dep->id}}' @if($dept == $dep->id) selected @endif>{{$dep->code}} - {{$dep->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class='col-md-3'>
                                   &nbsp;
                                <input type="text" placeholder="Document TItle / Control Code / Old Code" name="search" value="{{$search}}"  class="form-control" >
                                </div>
                                <div class='col-md-3'>
                                    <button class="btn btn-lg btn-primary" type="submit">
                                        Filter
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="hr-line-dashed"></div>
                    @if($documents)
                        @if($comp == 2 || $whiDept != null)
                            @foreach($documents->where('category', 'PROCEDURE') as $document)
                                <div class="search-result">
                                    <h3><a href="{{url('view-document/'.$document->id)}}" target="_blank"><i>({{$document->old_control_code}})</i> {{$document->control_code}} Rev. {{$document->version}}</a> @if($document->public == null)<span class="label label-danger">Private</span>@else<span class="label label-primary">Public</span>@endif</h3>
                                    Title : {{$document->title}}<br>
                                    Process Owner : 
                                    {{-- @if(count($document->department->drc) != 0) @foreach($document->department->drc as $drc) <small class="label label-info"> {{$drc->name}} </small> @endforeach @else <small class="label label-danger">No Process Owner</small>  @endif --}}
                                    @if($document->process_owner != null)
                                        <small class="label label-info">{{$document->processOwner->name}}</small>
                                    @else 
                                        <small class="label label-info">{{$document->department->dep_head->name}}</small>
                                    @endif
                                    <p>
                                        Date Effective : {{date('M d, Y',strtotime($document->updated_at))}} <br>
                                        Company : {{$document->department->name}}
                                        
                                    </p>
                                </div>
                                <div class="hr-line-dashed"></div>
                            @endforeach
                        @endif
                        
                        {{-- POLICY --}}
                        @foreach($documents->where('category', 'POLICY') as $document)
                            <div class="search-result">
                                <h3><a href="{{url('view-document/'.$document->id)}}" target="_blank"><i>({{$document->old_control_code}})</i> {{$document->control_code}} Rev. {{$document->version}}</a> @if($document->public == null)<span class="label label-danger">Private</span>@else<span class="label label-primary">Public</span>@endif</h3>
                                Title : {{$document->title}}<br>
                                {{-- Process Owner : @if(count($document->department->drc) != 0) @foreach($document->department->drc as $drc) <small class="label label-info"> {{$drc->name}} </small> @endforeach @else <small class="label label-danger">No Process Owner</small>  @endif --}}
                                Process Owner : 
                                @if($document->process_owner != null)
                                    <small class="label label-info">{{$document->processOwner->name}}</small>
                                @else 
                                    <small class="label label-info">{{$document->department->dep_head->name}}</small>
                                @endif
                                <p>
                                    Date Effective : {{date('M d, Y',strtotime($document->updated_at))}} <br>
                                    Company : {{$document->department->name}}
                                    
                                </p>
                            </div>
                            <div class="hr-line-dashed"></div>
                        @endforeach

                        {{-- FORM --}}
                        @foreach($documents->where('category', 'FORM') as $document)
                            <div class="search-result">
                                <h3><a href="{{url('view-document/'.$document->id)}}" target="_blank"><i>({{$document->old_control_code}})</i> {{$document->control_code}} Rev. {{$document->version}}</a> @if($document->public == null)<span class="label label-danger">Private</span>@else<span class="label label-primary">Public</span>@endif</h3>
                                Title : {{$document->title}}<br>
                                {{-- Process Owner : @if(count($document->department->drc) != 0) @foreach($document->department->drc as $drc) <small class="label label-info"> {{$drc->name}} </small> @endforeach @else <small class="label label-danger">No Process Owner</small>  @endif --}}
                                Process Owner : 
                                @if($document->process_owner != null)
                                    <small class="label label-info">{{$document->processOwner->name}}</small>
                                @else 
                                    <small class="label label-info">{{$document->department->dep_head->name}}</small>
                                @endif
                                <p>
                                    Date Effective : {{date('M d, Y',strtotime($document->updated_at))}} <br>
                                    Company : {{$document->department->name}}
                                    
                                </p>
                            </div>
                            <div class="hr-line-dashed"></div>
                        @endforeach

                        {{-- TEMPLATE --}}
                        @foreach($documents->where('category', 'TEMPLATE') as $document)
                            <div class="search-result">
                                <h3><a href="{{url('view-document/'.$document->id)}}" target="_blank"><i>({{$document->old_control_code}})</i> {{$document->control_code}} Rev. {{$document->version}}</a> @if($document->public == null)<span class="label label-danger">Private</span>@else<span class="label label-primary">Public</span>@endif</h3>
                                Title : {{$document->title}}<br>
                                {{-- Process Owner : @if(count($document->department->drc) != 0) @foreach($document->department->drc as $drc) <small class="label label-info"> {{$drc->name}} </small> @endforeach @else <small class="label label-danger">No Process Owner</small>  @endif --}}
                                Process Owner : 
                                @if($document->process_owner != null)
                                    <small class="label label-info">{{$document->processOwner->name}}</small>
                                @else 
                                    <small class="label label-info">{{$document->department->dep_head->name}}</small>
                                @endif
                                <p>
                                    Date Effective : {{date('M d, Y',strtotime($document->updated_at))}} <br>
                                    Company : {{$document->department->name}}
                                    
                                </p>
                            </div>
                            <div class="hr-line-dashed"></div>
                        @endforeach

                        {{-- ANNEX --}}
                        @foreach($documents->where('category', 'ANNEX') as $document)
                            <div class="search-result">
                                <h3><a href="{{url('view-document/'.$document->id)}}" target="_blank"><i>({{$document->old_control_code}})</i> {{$document->control_code}} Rev. {{$document->version}}</a> @if($document->public == null)<span class="label label-danger">Private</span>@else<span class="label label-primary">Public</span>@endif</h3>
                                Title : {{$document->title}}<br>
                                {{-- Process Owner : @if(count($document->department->drc) != 0) @foreach($document->department->drc as $drc) <small class="label label-info"> {{$drc->name}} </small> @endforeach @else <small class="label label-danger">No Process Owner</small>  @endif --}}
                                Process Owner : 
                                @if($document->process_owner != null)
                                    <small class="label label-info">{{$document->processOwner->name}}</small>
                                @else 
                                    <small class="label label-info">{{$document->department->dep_head->name}}</small>
                                @endif
                                <p>
                                    Date Effective : {{date('M d, Y',strtotime($document->updated_at))}} <br>
                                    Company : {{$document->department->name}}
                                    
                                </p>
                            </div>
                            <div class="hr-line-dashed"></div>
                        @endforeach

                        {{-- ALL --}}
                        @php
                            $category = array('POLICY', 'FORM', 'TEMPLATE', 'ANNEX');
                        @endphp
                        @foreach($documents->whereNotIn('category', $category) as $document)
                            <div class="search-result">
                                <h3><a href="{{url('view-document/'.$document->id)}}" target="_blank"><i>({{$document->old_control_code}})</i> {{$document->control_code}} Rev. {{$document->version}}</a> @if($document->public == null)<span class="label label-danger">Private</span>@else<span class="label label-primary">Public</span>@endif</h3>
                                Title : {{$document->title}}<br>
                                {{-- Process Owner : @if(count($document->department->drc) != 0) @foreach($document->department->drc as $drc) <small class="label label-info"> {{$drc->name}} </small> @endforeach @else <small class="label label-danger">No Process Owner</small>  @endif --}}
                                Process Owner : 
                                @if($document->process_owner != null)
                                    <small class="label label-info">{{$document->processOwner->name}}</small>
                                @else 
                                    <small class="label label-info">{{$document->department->dep_head->name}}</small>
                                @endif
                                <p>
                                    Date Effective : {{date('M d, Y',strtotime($document->updated_at))}} <br>
                                    Company : {{$document->department->name}}
                                    
                                </p>
                            </div>
                            <div class="hr-line-dashed"></div>
                        @endforeach
                    @endif
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>Public Documents </h5>
                </div>
                <div class="ibox-content">
                    <table class="table table-striped table-bordered table-hover tables">
                        <thead>
                            <tr>
                                <th>Document</th>
                                <th>Department</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($request_documents as $req_doc)
                                <tr>
                                    @php
                                        $attchment = ($req_doc->attachments)->where('type','pdf_copy')->first();
                                    @endphp
                                    <td>
                                        @if($attchment)
                                        <a href="{{url($attchment->attachment)}}" target="_blank"><i class="fa fa-file"></i> {{$req_doc->title}}</a>
                                        @endif
                                    </td>
                                    <td>
                                        {{$req_doc->department->code}}
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

@endsection

@section('js')
<script src="{{ asset('login_css/js/plugins/dataTables/datatables.min.js')}}"></script>

<script src="{{ asset('login_css/js/plugins/chosen/chosen.jquery.js') }}"></script>
<script>
   
    $(document).ready(function(){
        
        $('.cat').chosen({width: "100%"});
        $('.tables').DataTable({
            pageLength: 10,
            responsive: true,
            dom: '<"html5buttons"B>lTfgitp',
            buttons: [
                
            ]

        });

    });

</script>
@endsection

