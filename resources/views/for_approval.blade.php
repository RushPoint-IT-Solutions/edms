@extends('layouts.header')
@section('css')
<link href="{{ asset('login_css/css/plugins/chosen/bootstrap-chosen.css') }}" rel="stylesheet">
@endsection
@section('content')

<div class="wrapper wrapper-content">
    <div class="row">
        <div class="col-lg-3">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>For Approval</h5>
                </div>
                <div class="ibox-content">
                    <h1 class="no-margins">{{count($copy_for_approvals->where('status','Pending'))+count($change_for_approvals->where('status','Pending'))}}</h1>
                </div>
            </div>
        </div>
        <div class="col-lg-3">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>Approved</h5>
                </div>
                <div class="ibox-content">
                    <h1 class="no-margins">{{count($copy_for_approvals->where('status','Approved'))+count($change_for_approvals->where('status','Approved'))}}</h1>
                </div>
            </div>
        </div>
        <div class="col-lg-3">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>Declined</h5>
                </div>
                <div class="ibox-content">
                    <h1 class="no-margins">{{count($copy_for_approvals->where('status','Declined'))+count($change_for_approvals->where('status','Declined'))}}</h1>
                </div>
            </div>
        </div>
    </div>
    <div class='row'>
        <div class="col-lg-6">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>Copy Requests</h5>
                </div>
                <div class="ibox-content">

                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover tables" >
                            <thead>
                                <tr>
                                    
                                    <th>Actions</th>
                                    <th>Reference Number</th>
                                    <th>Date Requested</th>
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
                                    
                                    <td><a href="#"  data-target="#view_request_copy{{$copy_approval->copy_request->id}}" data-toggle="modal" class='btn btn-sm btn-info'><i class="fa fa-eye"></i></a></td>
                                    <td>CR-{{str_pad($request->id, 5, '0', STR_PAD_LEFT)}}</td>
                                    <td>{{date('M d Y',strtotime($copy_approval->copy_request->created_at))}}</td>
                                    <td><small>
                                        {{$copy_approval->copy_request->control_code}} Rev. {{$copy_approval->copy_request->revision}}<br>
                                        {{$copy_approval->copy_request->title}} <br>
                                        {{$copy_approval->copy_request->type_of_document}}
                                    </small></td>
                                    <td>{{$copy_approval->copy_request->user->name}}</td>
                                </tr>
                               
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>Change Requests</h5>
                  
                </div>
                <div class="ibox-content">

                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover tables" >
                        <thead>
                        <tr>
                            <th>Actions</th>
                            <th>Reference Number</th>
                            <th>Date Requested</th>
                            <th>Document</th>
                            <th>Requested By</th>
                            <th>Request Type</th>
                        </tr>
                        </thead>
                        <tbody>
                            @foreach($change_for_approvals->where('status','Pending') as $change_approval)
                            @php
                                $request = $change_approval->change_request;
                            @endphp
                            <tr>
                                
                                <td>
                                    <a href="#"  data-target="#view_request_change{{$request->id}}" data-toggle="modal" class='btn btn-sm btn-info'><i class="fa fa-eye"></i></a>
                                    @if(auth()->user()->role == "Document Control Officer")
                                    <a href="#"  data-target="#edit_title{{$request->id}}" data-toggle="modal" class='btn btn-sm btn-warning'><i class="fa fa-edit"></i></a>
                                    @endif
                                </td>
                                <td>DICR-{{str_pad($request->id, 5, '0', STR_PAD_LEFT)}}</td>
                                <td>{{date('M d Y',strtotime($request->created_at))}}</td>
                                <td><small>
                                    {{$request->control_code}} Rev. {{$request->revision}}<br>
                                    {{$request->title}} <br>
                                    {{$request->type_of_document}}
                                </small></td>
                                <td>{{$request->user->name}}</td>
                                <td>{{$request->request_type}}</td>
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
            dom: '<"html5buttons"B>lTfgitp',
            buttons: [
                { extend: 'copy'},
                {extend: 'csv'},
                {extend: 'excel', title: 'ExampleFile'},
                {extend: 'pdf', title: 'ExampleFile'},

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
