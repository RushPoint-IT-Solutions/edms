@extends('layouts.header')
@section('css')
<link href="{{ asset('login_css/css/plugins/chosen/bootstrap-chosen.css') }}" rel="stylesheet">
@endsection
@section('content')

<div class="wrapper wrapper-content">
    <div class='row'>
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <form method='GET' onsubmit='show();' enctype="multipart/form-data">
                        <div class='row'>
                            <div class="col-lg-3">
                                <div class="form-group row">
                                    <label class="col-sm-4 col-form-label text-right text-white">Select Month</label>
                                    <div class="col-sm-8">
                                        <input class='form-control-sm form-control' name='yearmonth' value='{{$search}}' max='{{date('Y-m')}}' type='month' required>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <button type="submit" class="btn btn-primary col-sm-3 col-lg-3 col-md-3">Search</button>
                            </div>
                        </div>
                    </form> 
                </div>
            </div>
        </div>
    </div> 
    <div class='row'>
        <div class="col-lg-12">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>Copy Request Reports</h5>
                  
                </div>
                <div class="ibox-content">

                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover tables" >
                            <thead>
                                <tr>
                                    <th>Reference No.</th>
                                    <th>Date Requested</th>
                                    <th>Control Code</th>
                                    <th>Title</th>
                                    <th>Request By</th>
                                    <th>Date needed</th>
                                    <th>Expiration Date</th>
                                    
                                    <th>Status</th>
                                    
                                    <th>Approver</th>
                                    <th>Start Date</th>
                                    <th>Action Date</th>
                                    <th>TAT</th>
                                    <th>Remarks</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                            
                                @foreach($requests as $request)
                                <tr>
                                    <td  rowspan='{{count($request->approvers)}}'><b>CR-{{str_pad($request->id, 5, '0', STR_PAD_LEFT)}}</b></td>
                                    <td rowspan='{{count($request->approvers)}}'>{{date('M. d, Y',strtotime($request->created_at))}}</td>
                                    <td rowspan='{{count($request->approvers)}}'>{{$request->control_code}}</td>
                                    <td rowspan='{{count($request->approvers)}}'>{{$request->title}}</td>
                                    <td rowspan='{{count($request->approvers)}}'>{{$request->user->name}}</td>
                                    <td rowspan='{{count($request->approvers)}}'>{{date('M. d, Y',strtotime($request->date_needed))}}</td>
                                    <td rowspan='{{count($request->approvers)}}' >@if($request->expiration_date != null){{date('M. d, Y',strtotime($request->expiration_date))}}@endif</td>
                                    <td rowspan='{{count($request->approvers)}}' >@if($request->status == "Pending")
                                        <span class='label label-warning'>
                                    @elseif($request->status ==  "Approved")
                                        <span class='label label-info'>    
                                    @elseif($request->status ==  "Declined")
                                            <span class='label label-danger'>
                                    @else<span class='label label-success'>
                                        @endif
                                        {{$request->status}}</span> </td>
                                    @foreach($request->approvers as $key => $approver)
                                    @if($key != 0)
                                    <tr>
                                    @endif
                                        <td>{{$approver->user->name}}</td>
                                        <td>{{$approver->start_date}}</td>
                                        <td>@if(($approver->status != "Waiting") && ($approver->status != "Pending")){{date('Y-m-d',strtotime($approver->updated_at))}}@endif</td>
                                        <td>
                                        @php
                                            $difference =0;
                                            if($approver->status == "Pending")
                                            {
                                                $date_after = date('Y-m-d');
                                                $date_before = date('Y-m-d',strtotime($approver->start_date));
                                                $difference = strtotime($date_after)-strtotime($date_before);
                                            }
                                            else 
                                            {
                                                $date_after = date('Y-m-d',strtotime($approver->updated_at));
                                                $date_before = date('Y-m-d',strtotime($approver->start_date));
                                                $difference = strtotime($date_after)-strtotime($date_before);
                                                
                                            }
                                        @endphp
                                        @if($approver->status != "Waiting")
                                        {{$difference/(24*60*60)}} day/s
                                        @endif
                                        </td>
                                        <td>@if($approver->status != "Waiting"){{$approver->remarks}}@endif</td>
                                        <td>
                                            @if($approver->status == "Pending")
                                                <span class='label label-warning'>
                                            @elseif($approver->status ==  "Approved")
                                                <span class='label label-info'>    
                                            @elseif($approver->status ==  "Declined")
                                                    <span class='label label-danger'>
                                            @else<span class='label label-success'>
                                                @endif
                                                {{$approver->status}}</span>  
                                        </td>
                                    </tr>
                                    
                                    @endforeach
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>

    </div>
</div>
{{-- @include('properties.create') --}}
@endsection
@section('js')
<script src="{{ asset('login_css/js/plugins/dataTables/datatables.min.js')}}"></script>
<script src="{{ asset('login_css/js/plugins/chosen/chosen.jquery.js') }}"></script>
<script>
    $(document).ready(function(){
        $('.tables').DataTable({
            pageLength: 25,
            responsive: true,
            sorting:false,
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
