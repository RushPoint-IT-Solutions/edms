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
                <h5>Pending</h5>
            </div>
            <div class="ibox-content">
                <form method="GET">
                    <h1 class="no-margins">
                        <input type="hidden" name="status" value="NotDelayed">
                        <input type="submit" class="text-success" value="{{$notDelayedCount}}" style="background: none; border: none;">
                    </h1>
                </form>
            </div>
        </div>
    </div>
    {{-- <div class="col-lg-2">
        <div class="ibox float-e-margins">
            <div class="ibox-title">
                <h5>Cancelled</h5>
            </div>
            <div class="ibox-content">
                <form action="" method="get">
                    <h1 class="no-margins">
                        <input type="hidden" name="status" value="Cancelled">
                        <input type="submit" class="text-success" value="{{count($requests->where('status','Cancelled'))}}" style="background: none; border: none;">
                    </h1>
                </form>
            </div>
        </div>
    </div> --}}
    <div class="col-lg-2">
        <div class="ibox float-e-margins">
            <div class="ibox-title">
                <h5>Declined</h5>
            </div>
            <div class="ibox-content">
                <form method="get" action="">
                    <h1 class="no-margins">
                        <input type="hidden" name="status" value="Declined">
                        <input type="submit" class="text-success" value="{{$declinedCount}}" style="background: none; border: none;">
                    </h1>
                </form>
            </div>
        </div>
    </div>
    <div class="col-lg-2">
        <div class="ibox float-e-margins">
            <div class="ibox-title">
                <h5>Approved</h5>
            </div>
            <div class="ibox-content">
                <form action="" method="get">
                    <input type="hidden" name="status" value="Approved">
                    <h1 class="no-margins">
                        <input type="submit" class="text-success" value="{{$approvedCount}}"  style="background: none; border: none;">
                    </h1>
                </form>
            </div>
        </div>
    </div>
    <div class="col-lg-2">
        <div class="ibox float-e-margins">
            <div class="ibox-title">
                <h5>Delayed</h5>
            </div>
            <div class="ibox-content">
                {{-- <form action="" method="get">
                    <input type="hidden" name="status" value="Pending">
                    <h1 class="no-margins">
                        <a href="{{url('delayed_request')}}">{{ $delayedCount }}</a>
                        <a href="{{url('delayed_request')}}" id="delayed">0</a>
                    </h1>
                </form> --}}
                <form action="" method="get">
                    <input type="hidden" name="status" value="Delayed">
                    <h1 class="no-margins">
                        <input type="submit" class="text-success" value="{{ $delayedCount }}"  style="background: none; border: none;">
                    </h1>
                </form>
            </div>
        </div>
    </div>
</div>
    <div class='row'>
        <div class="col-lg-12">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>Change Requests
                        {{-- @if(auth()->user()->role == "Documents and Records Controller")
                        <button class="btn btn-success "  data-target="#newRequest" data-toggle="modal" type="button"><i class="fa fa-plus"></i>&nbsp;New </button>
                        @endif
                        @if(auth()->user()->role == "Document Control Officer")
                        <button class="btn btn-success "  data-target="#newRequest" data-toggle="modal" type="button"><i class="fa fa-plus"></i>&nbsp;New </button>
                        @endif --}}
                        @if(auth()->user()->role == "User")
                        <button class="btn btn-success "  data-target="#newRequest" data-toggle="modal" type="button"><i class="fa fa-plus"></i>&nbsp;New </button>
                        @endif
                    </h5>
                  
                </div>
                <div class="ibox-content">

                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover tables">
                            <thead>
                                <tr>
                                    
                                    <th>Actions</th>
                                    <th>Reference No.</th>
                                    <th>Request Type</th>
                                    <th>Date Requested</th>
                                    <th>Code</th>
                                    <th>Title</th>
                                    <th>Revision</th>
                                    <th>Type</th>
                                    <th>Requested By</th>
                                    <th>Target Date</th>
                                    <th>Approved Date</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                        <tbody>
                            @php
                                $delayed = 0;
                            @endphp
                            @foreach($requests as $request)
                            
                            @if(($request->type_of_document == "FORM") || ($request->type_of_document == "ANNEX") ||($request->type_of_document == "TEMPLATE"))
                                @php
                                    $date_push = date('Y-m-d', strtotime('2024-08-22'));
                                    if ($date_push > date('Y-m-d', strtotime($request->created_at)))
                                    {
                                        $target = date('Y-m-d', strtotime("+7 days", strtotime($request->created_at))); 
                                    }
                                    else
                                    {
                                        // $departmentHeadApproval = date('Y-m-d', strtotime($request->department_head_approved));
                                        // if ($departmentHeadApproval !=  null) {
                                        //     $target = date('Y-m-d', strtotime("+7 days", strtotime($departmentHeadApproval)));
                                        // }
                                        // else
                                        // {
                                        //     $target = date('Y-m-d');
                                        // }
                                        if ($request->department_head_approved != null)
                                        {
                                            $target = date('Y-m-d', strtotime("+7 days", strtotime($request->department_head_approved)));
                                        }
                                        else
                                        {
                                            if ($request->preAssessment != null)
                                            {
                                                if ($request->preAssessment->status == 'Approved')
                                                {
                                                    // $target = date('Y-m-d', strtotime("+7 days", strtotime($request->created_at)));
                                                    $target = "";
                                                }
                                                else
                                                {
                                                    $target = date('Y-m-d', strtotime("+10 days", strtotime($request->preAssessment->created_at)));
                                                }
                                            }
                                            else
                                            {
                                                // For old data that does not have pre assessment
                                                $target = date('Y-m-d', strtotime("+7 days", strtotime($request->created_at))); 
                                            }
                                        }
                                    }
                                @endphp
                            @else
                                @php
                                    $date_push = '2024-08-22';
                                    if ($date_push > date('Y-m-d', strtotime($request->created_at)))
                                    {
                                        $target = date('Y-m-d', strtotime("+1 month", strtotime($request->created_at))); 
                                    }
                                    else
                                    {
                                        // $departmentHeadApproval = date('Y-m-d', strtotime($request->department_head_approved));
                                        // if ($departmentHeadApproval != null) {
                                        //     $target = date('Y-m-d', strtotime("+1 month", strtotime($departmentHeadApproval)));
                                        // }
                                        // else
                                        // {
                                        //     $target = date('Y-m-d');
                                        // }
                                        if ($request->department_head_approved != null)
                                        {
                                            $target = date('Y-m-d', strtotime("+1 month", strtotime($request->department_head_approved))); 
                                        }
                                        else
                                        {
                                            if ($request->preAssessment != null)
                                            {
                                                if ($request->preAssessment->status == 'Approved')
                                                {
                                                    // $target = date('Y-m-d', strtotime("+1 month", strtotime($request->created_at)));
                                                    $target = "";
                                                }
                                                else
                                                {
                                                    $target = date('Y-m-d', strtotime("+10 days", strtotime($request->preAssessment->created_at)));
                                                }
                                            }
                                            else
                                            {
                                                // For old data that does not have pre assessment
                                                $target = date('Y-m-d', strtotime("+1 month", strtotime($request->created_at))); 
                                            }
                                        }
                                    } 
                                @endphp
                            @endif
                                    <tr>
                                        
                                        <td><a href="#"  data-target="#view_request{{$request->id}}" data-toggle="modal" class='btn btn-sm btn-info'><i class="fa fa-eye"></i></a>
                                            @if((auth()->user()->role == "Document Control Officer") || (auth()->user()->role == "Administrator"))
                                            @if($request->status == "Pending")
                                            @if($request->request_type == "Revision")
                                            <a href="#"  data-target="#edit_request{{$request->id}}" data-toggle="modal" class='btn btn-sm btn-warning'><i class="fa fa-edit"></i></a>
                                            @endif
                                            @endif
                                            @endif
                                        </td>
                                        <td>
                                            @if(optional($request->preAssessment)->status != "Pending")
                                            DICR-{{str_pad($request->id, 5, '0', STR_PAD_LEFT)}}
                                            @endif
                                        </td>
                                        <td>{{$request->request_type}}</td>
                                        <td>{{date('Y-m-d',strtotime($request->created_at))}}</td>
                                     
                                            @if($request->document_id != null)
                                            <td>
                                                {{$request->control_code}}
                                            </td>   
                                            <td>
                                                {{$request->title}}
                                            </td>   
                                            <td>
                                                {{$request->revision}}
                                            </td>   
                                           
                                            @else
                                            <td></td>
                                            <td>{{$request->title}}</td>
                                            <td></td>
                                            @endif
                                            <td>
                                                {{$request->type_of_document}}
                                            </td>   
                                        <td>{{$request->user->name}}</td>
                                        <td>
                                            @if($target != null)
                                                {{date('Y-m-d', strtotime($target))}}
                                            @endif
                                        </td>
                                        <td>
                                            @if($request->status == 'Approved')
                                                {{date('Y-m-d', strtotime($request->updated_at))}}
                                            @endif
                                        </td>
                                        <td> 
                                            @if(optional($request->preAssessment)->status == "Pending")
                                                <span style="background-color: #b9ff66; font-weight: bold;" class="label"> Pre-Assessment
                                                {{-- <span class="label label-primary"> Pre-Assessment --}}
                                            @else
                                                @if($request->status == "Pending")
                                                    @if($target != null)
                                                        @if($target < date('Y-m-d'))
                                                        @php
                                                            $delayed++;
                                                        @endphp
                                                        <span class='label label-danger'>
                                                            Delayed - 
                                                        @else
                                                        <span class='label label-success'>
                                                        @endif
                                                    @else
                                                        <span class='label label-success'>
                                                    @endif
                                                @elseif($request->status ==  "Approved")
                                                    <span class='label label-info'>    
                                                @elseif($request->status ==  "Declined")
                                                        <span class='label label-warning'>
                                                @else<span class='label label-success'>
                                                @endif

                                                {{$request->status}} 
                                            @endif

                                            </span>  
                                        </td>
                                    </tr>
                                    @include('view_change_request')
                                    @include('edit_change_request')
                                @endforeach
                            
                        </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>

    </div>
</div>
@include('new_change_request_image')
@include('new_change_request')
@endsection
@section('js')
<script src="{{ asset('login_css/js/plugins/dataTables/datatables.min.js')}}"></script>
<script src="{{ asset('login_css/js/plugins/chosen/chosen.jquery.js') }}"></script>
@if($status == null)
<script type="text/javascript">
    $(window).on('load', function() {
        $('#myModal').modal('show');
    });
</script>
@endif
<script>
    // var delayed = {!! json_encode($delayed) !!};
    // document.getElementById('delayed').innerText = delayed;
    // document.getElementById('delayed').value = delayed;
    $(document).ready(function(){
        

        $('.cat').chosen({width: "100%"});
        $('.tables').DataTable({
            pageLength: 25,
            responsive: true,
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




