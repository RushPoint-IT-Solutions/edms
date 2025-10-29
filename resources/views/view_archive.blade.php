@extends('layouts.header')
@section('css')
<link href="{{ asset('login_css/css/plugins/chosen/bootstrap-chosen.css') }}" rel="stylesheet">
@endsection
@section('content')

<div class="wrapper wrapper-content">
    @include('error')
    <div class="row">
        <div class="col-lg-2">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>Total</h5>
                </div>
                <div class="ibox-content">
                    {{-- <h1 class="no-margins">{{count($permits)}}</h1> --}}
                    <h1 class="no-margins">
                        <a href="{{url('permits')}}">{{count($permits)}}</a>
                    </h1>
                </div>
            </div>
        </div>
        <div class="col-lg-2">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>Active</h5>
                </div>
                <div class="ibox-content">
                    <h1 class="no-margins">
                        <a href="{{url('permits?active_permits_filter=Active')}}">{{$active_permits_count}}</a>
                    </h1>
                </div>
            </div>
        </div>
        <div class="col-lg-2">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>For Renewal</h5>
                </div>
                <div class="ibox-content">
                    <h1 class="no-margins">
                        {{-- <a href="{{url('permits?renewal_filter=For+Renewal')}}">{{count($permits->where('expiration_date','!=',null)->where('expiration_date','<',date('Y-m-d', strtotime("+3 months", strtotime(date('Y-m-d'))))))-count($permits->where('expiration_date','!=',null)->where('expiration_date','<',date('Y-m-d')))}}</a> --}}
                        <a href="{{url('permits?renewal_filter=For+Renewal')}}">{{$for_renewal_count}}</a>
                    </h1>
                </div>
            </div>
        </div>
        <div class="col-lg-2">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>Overdue</h5>
                </div>
                <div class="ibox-content">
                    <h1 class="no-margins"><a href="{{url('permits?overdue_filter=Overdue')}}">{{$overdue_count}}</a></h1>
                </div>
            </div>
        </div>
        <div class="col-lg-2">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>Archived</h5>
                </div>
                <div class="ibox-content">
                    <h1 class="no-margins">
                        <a href="{{url('archive_permits')}}">{{count($archives)}}</a>
                    </h1>
                </div>
            </div>
        </div>
        <div class="col-lg-2">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>Inactive</h5>
                </div>
                <div class="ibox-content">
                    <h1 class="no-margins">
                        <a href="{{url('permits?inactive_filter=Inactive')}}">{{count($inactive_count)}}</a>
                    </h1>
                </div>
            </div>
        </div>
    </div>
    <div class='row'>
        <div class="col-lg-12">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    {{-- <h5>Permits & Licenses <button class="btn btn-success "  data-target="#new_permit" data-toggle="modal" type="button"><i class="fa fa-plus"></i>&nbsp;New </button></h5> --}}
                    <h5>Permits & Licenses</h5>
                  
                </div>
                <div class="ibox-content">

                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover tables" >
                            <thead>
                                <tr>
                                    <th>Title</th>
                                    <th>Description</th>
                                    <th>Company</th>
                                    <th>Department</th>
                                    <th>Accountable Person</th>
                                    <th>Date Uploaded</th>
                                    <th>File</th>
                                    <th>Type</th>
                                    <th>Expiration Date</th>
                                    <th>Status</th>
                                    {{-- <th>Action</th> --}}
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($archives as $archive)
                                <tr>
                                    <td>{{$archive->title}}</td>
                                    <td>{{$archive->description}}</td>
                                    <td>
                                        {{$archive->company->name}}
                                    </td>
                                    <td>
                                        {{$archive->department->name}}
                                    </td>
                                    <td>
                                        <small>
                                            @foreach($archive->department->permit_accounts as $accountable)
                                                {{$accountable->user->name}} <hr>
                                            @endforeach
                                        </small>
                                    </td>
                                    <td>{{date('M d, Y',strtotime($archive->created_at))}}</td>
                                    <td>
                                        <a href='{{url($archive->file)}}' target='_blank'><i class='fa fa-file'></i></a>
                                    </td>
                                    <td>{{$archive->type}}</td>
                                    <td>@if($archive->expiration_date != null){{date('M d Y',strtotime($archive->expiration_date))}}@endif</td>
                                    <td>
                                        {{-- @if($archive->expiration_date != null)@if($archive->expiration_date < date("Y-m-d")) <small class="label label-danger">For Renewal (Overdue)</small> @elseif($archive->expiration_date < date('Y-m-d', strtotime("+3 months", strtotime(date('Y-m-d'))))) <small class="label label-warning">For Renewal</small> @else <small class="label label-primary">Active</small> @endif @endif --}}
                                        <div class="label label-danger">Obsolete</div>
                                    </td>
                                    {{-- <td>
                                        <button class="btn btn-sm btn-primary "  title="Upload " data-target="#upload{{$archive->id}}" data-toggle="modal"><i class="fa fa-upload"></i></button>
                                        @if((auth()->user()->role != "User") && (auth()->user()->role != "Department Head") && (auth()->user()->role != "Documents and Records Controller") )
                                        <button class="btn btn-sm btn-warning "  title="Transfer Department" data-target="#change{{$archive->id}}" data-toggle="modal"><i class="fa fa-users"></i></button>
                                        <button class="btn btn-sm btn-info "  title="Change Types" data-target="#changeType{{$archive->id}}" data-toggle="modal"><i class="fa fa-edit"></i></button>
                                        
                                        @endif
                                    </td> --}}
                                </tr>
                                {{-- @include('upload_permit')
                                @include('transfer_department')
                                @include('edit_type') --}}
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>

    </div>
</div>
{{-- @include('new_permit') --}}
@endsection
@section('js')
<script src="{{ asset('login_css/js/plugins/dataTables/datatables.min.js')}}"></script>
<script src="{{ asset('login_css/js/plugins/chosen/chosen.jquery.js') }}"></script>
<script>
    $(document).ready(function(){
        
        $('.cat').chosen({width: "100%"});
        $('.locations').chosen({width: "100%"});
        $('.tables').DataTable({
            pageLength: 25,
            responsive: true,
            stateSave: true,
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
