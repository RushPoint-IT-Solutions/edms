@extends('layouts.header')
@section('css')
<link href="{{ asset('login_css/css/plugins/chosen/bootstrap-chosen.css') }}" rel="stylesheet">
<link href="{{ asset('login_css/css/plugins/sweetalert/sweetalert.css') }}" rel="stylesheet">
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
                        {{-- {{$permits_count}} --}}
                        <a href="{{url('permits')}}">{{$permits_count}}</a>
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
                    <form action="" method="get">
                        {{-- <h1 class="no-margins">
                            {{count($permits->where('expiration_date','!=',null)->where('expiration_date','<',date('Y-m-d', strtotime("+3 months", strtotime(date('Y-m-d'))))))-count($permits->where('expiration_date','!=',null)->where('expiration_date','<',date('Y-m-d')))}}
                        </h1> --}}
                        <h1 class="no-margins">
                            <input type="hidden" name="active_permits_filter" value="Active">
                            <input type="submit" value="{{$active_permits_count}}" style="background: none; border: none;" class="text-success">
                        </h1>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-lg-2">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>For Renewal</h5>
                </div>
                <div class="ibox-content">
                    <form action="" method="get">
                        {{-- <h1 class="no-margins">
                            {{count($permits->where('expiration_date','!=',null)->where('expiration_date','<',date('Y-m-d', strtotime("+3 months", strtotime(date('Y-m-d'))))))-count($permits->where('expiration_date','!=',null)->where('expiration_date','<',date('Y-m-d')))}}
                        </h1> --}}
                        <h1 class="no-margins">
                            <input type="hidden" name="renewal_filter" value="For Renewal">
                            {{-- <input type="submit" class="text-success" value="{{count($permits->where('expiration_date','!=',null)->where('expiration_date','<',date('Y-m-d', strtotime("+3 months", strtotime(date('Y-m-d'))))))-count($permits->where('expiration_date','!=',null)->where('expiration_date','<',date('Y-m-d')))}}" style="background: none; border: none;"> --}}
                            <input type="submit" value="{{$for_renewal_count}}" style="background: none; border: none;" class="text-success">
                        </h1>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-lg-2">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>Overdue</h5>
                </div>
                <div class="ibox-content">
                    {{-- <h1 class="no-margins">{{count($permits->where('expiration_date','!=',null)->where('expiration_date','<',date('Y-m-d')))}}</h1> --}}
                    <form method="GET">
                        <h1 class="no-margins">
                            <input type="hidden" name="overdue_filter" value="Overdue">
                            {{-- <input type="submit" class="text-success" value="{{count($permits->where('expiration_date','!=',null)->where('expiration_date','<',date('Y-m-d')))}}" style="background: none; border:none;"> --}}
                            <input type="submit" value="{{$overdue_count}}" style="background: none; border: none;" class="text-success">
                        </h1>
                    </form>
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
                        <form method="GET">
                            <h1 class="no-margins">
                                <input type="hidden" name="inactive_filter" value="Inactive">
                                {{-- <input type="submit" class="text-success" value="{{count($permits->where('expiration_date','!=',null)->where('expiration_date','<',date('Y-m-d')))}}" style="background: none; border:none;"> --}}
                                <input type="submit" value="{{$inactive_permits_count}}" style="background: none; border: none;" class="text-success">
                            </h1>
                        </form>
                    </h1>
                </div>
            </div>
        </div>
    </div>
    <div class='row'>
        <div class="col-lg-12">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>Permits & Licenses <button class="btn btn-success "  data-target="#new_permit" data-toggle="modal" type="button"><i class="fa fa-plus"></i>&nbsp;New </button></h5>
                  
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
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($permits as $permit)
                                <tr>
                                    <td>{{$permit->title}}</td>
                                    <td>{{$permit->description}}</td>
                                    <td>{{$permit->company->name}}</td>
                                    <td>{{$permit->department->name}}</td>
                                    <td><small>@foreach($permit->department->permit_accounts as $accountable)
                                        {{$accountable->user->name}} <hr>
                                    @endforeach</small></td>
                                    <td>{{date('M d, Y',strtotime($permit->created_at))}}</td>
                                    <td><a href='{{url($permit->file)}}' target='_blank'><i class='fa fa-file'></i></a></td>
                                    <td>{{$permit->type}}</td>
                                    <td>@if($permit->expiration_date != null){{date('M d Y',strtotime($permit->expiration_date))}}@endif</td>
                                    <td>
                                        @if($permit->status != null)
                                            @if($permit->status == "Inactive")
                                            <small class="label label-danger">{{$permit->status}}</small>
                                            @endif
                                        @else
                                            @if($permit->expiration_date != null)
                                                @if($permit->expiration_date < date("Y-m-d")) 
                                                <small class="label label-danger">For Renewal (Overdue)</small> 
                                                @elseif($permit->expiration_date < date('Y-m-d', strtotime("+3 months", strtotime(date('Y-m-d'))))) 
                                                <small class="label label-warning">For Renewal</small> 
                                                @else 
                                                <small class="label label-primary">Active</small> 
                                                @endif 
                                            @endif
                                        @endif
                                    </td>
                                    <td>
                                        {{-- {{auth()->user()->role}} --}}
                                        <button class="btn btn-sm btn-primary "  title="Upload " data-target="#upload{{$permit->id}}" data-toggle="modal"><i class="fa fa-upload"></i></button>
                                       @if((auth()->user()->role != "User") && (auth()->user()->role != "Department Head") && (auth()->user()->role != "Documents and Records Controller") )
                                       <button class="btn btn-sm btn-warning "  title="Transfer Department" data-target="#change{{$permit->id}}" data-toggle="modal"><i class="fa fa-users"></i></button>
                                       <button class="btn btn-sm btn-info "  title="Change Types" data-target="#changeType{{$permit->id}}" data-toggle="modal"><i class="fa fa-edit"></i></button>
                                       @if($permit->status == null)
                                       <form method="POST" action="{{url('inactive-permits/'.$permit->id)}}">
                                            @csrf
                                           <button class="btn btn-sm btn-danger inactiveBtn" type="button" title="Inactive Permits">
                                                <i class="fa fa-trash"></i>
                                           </button>
                                       </form>
                                       @endif
                                       @if($permit->status == "Inactive")
                                       <form method="POST" action="{{url('activate-permits/'.$permit->id)}}">
                                            @csrf
                                           <button class="btn btn-sm btn-success activatePermitsBtn" type="button" title="Activate Permits">
                                                <i class="fa fa-check"></i>
                                           </button>
                                       </form>
                                       @endif
                                       @endif
                                        {{-- <button class="btn btn-sm btn-warning "  title="View History "><i class="fa fa-eye"></i></button> --}}
                                    </td>
                                </tr>
                                @include('upload_permit')
                                @include('transfer_department')
                                @include('edit_type')
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>

    </div>
</div>
@include('new_permit')
@endsection
@section('js')
<script src="{{ asset('login_css/js/plugins/dataTables/datatables.min.js')}}"></script>
<script src="{{ asset('login_css/js/plugins/chosen/chosen.jquery.js') }}"></script>
<script src="{{ asset('login_css/js/plugins/sweetalert/sweetalert.min.js') }}"></script>
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

        $('.inactiveBtn').on('click', function() {
            swal({
                title: "Are you sure?",
                text: "This permits will be inactive!",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "Yes, Inactive it!",
                closeOnConfirm: false
            }, function (){
                $('.inactiveBtn').closest('form').submit()
            });
        })

        $('.activatePermitsBtn').on('click', function() {
            
            swal({
                title: "Are you sure?",
                text: "This permits will be activate!",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "Yes, Activate it!",
                closeOnConfirm: false
            }, function (){
                $('.activatePermitsBtn').closest('form').submit()
            });
        })
    });

</script>
@endsection
