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
                                    <label class="col-sm-4 col-form-label text-right text-white">Select BPO</label>
                                    <div class="col-sm-6">
                                        <select name='dco' class='form-control-sm form-control cat' required>
                                            <option value=""></option>
                                            @foreach($dcos->where('status',null) as $dc)
                                                <option value='{{$dc->id}}' @if($dco == $dc->id) selected @endif>{{$dc->name}}</option>
                                            @endforeach
                                        </select>
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
                    <h5>Change DCO Request Reports</h5>
                  
                </div>
                <div class="ibox-content">

                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover tables" >
                            <thead>
                                <tr>
                                    <th>Reference No.</th>
                                    <th>Type of Request</th>
                                    <th>Date Requested</th>
                                    <th>Control Code</th>
                                    <th>Company</th>
                                    <th>Department</th>
                                    <th>Title</th>
                                    <th>Request By</th>
                                    <th>Effective Date</th>
                                    <th>Start Date</th>
                                    <th>End Date</th>
                                    <th>TAT</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($requests as $request)
                                    @php
                                    $difference =0;
                                    if($request->status == "Pending")
                                    {
                                        $date_after = date('Y-m-d');
                                        $date_before = date('Y-m-d',strtotime($request->created_at));
                                        $difference = strtotime($date_after)-strtotime($date_before);
                                    }
                                    else 
                                    {
                                        $date_after = date('Y-m-d',strtotime($request->updated_at));
                                        $date_before = date('Y-m-d',strtotime($request->created_at));
                                        $difference = strtotime($date_after)-strtotime($date_before);
                                        
                                    }
                                @endphp
                                    <tr>
                                        <td>DICR-{{str_pad($request->id, 5, '0', STR_PAD_LEFT)}}</td>
                                        <td>{{$request->request_type}}</td>
                                        <td>{{date('M d Y',strtotime($request->created_at))}}</td>
                                        <td>{{$request->control_code}}</td>
                                        <td>{{$request->company->name}}</td>
                                        <td>{{$request->department->name    }}</td>
                                        <td><small>{{$request->title}}</small></td>
                                        <td>{{$request->user->name}}</td>
                                        <td>{{date('M d, Y',strtotime($request->effective_date))}}</td>
                                        <td>{{date('M d Y',strtotime($request->created_at))}}</td>
                                        <td>@if($request->status != "Pending") {{date('M d Y',strtotime($request->updated_at))}} @endif</td>
                                        <td>{{$difference/(24*60*60)}} day/s</td>
                                        <td> @if($request->status == "Pending")
                                            <span class='label label-warning'>
                                        @elseif($request->status ==  "Approved")
                                            <span class='label label-info'>    
                                        @elseif($request->status ==  "Declined")
                                                <span class='label label-danger'>
                                        @else<span class='label label-success'>
                                            @endif
                                            {{$request->status}}</span>  </td>
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
{{-- @include('properties.create') --}}
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
