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
                    <h1 class="no-margins">{{count($change_for_approvals->where('status','Pending'))}}</h1>
                </div>
            </div>
        </div>
    </div>
    <div class='row'>
    
        <div class="col-lg-12">
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
                            <th>Document Type</th>
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
                                
                                <td><a href="#"  data-target="#view_request_change{{$request->id}}" data-toggle="modal" class='btn btn-sm btn-info'><i class="fa fa-eye"></i></a></td>
                                <td>DICR-{{str_pad($request->id, 5, '0', STR_PAD_LEFT)}}</td>
                                <td>{{date('M d Y',strtotime($request->created_at))}}</td>
                                <td><small>
                                    {{$request->control_code}} Rev. {{$request->revision}}<br>
                                    {{$request->title}} <br>
                                    {{$request->type_of_document}}
                                </small></td>
                                <td>{{$request->type_of_document}}</td>
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
@include('view_removal_approvers')
@endforeach
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
