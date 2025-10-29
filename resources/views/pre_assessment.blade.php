@extends('layouts.header')

@section('css')
    <link href="{{ asset('login_css/css/plugins/chosen/bootstrap-chosen.css') }}" rel="stylesheet">
@endsection

@section('content')
<div class="wrapper wrapper-content">
    <div class="row">
        <div class="col-lg-2">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>Pending</h5>
                </div>
                {{-- <div class="ibox-content">
                    <form method="GET">
                        <h1 class="no-margins">
                            <input type="hidden" name="status" value="Pending">
                            <input type="submit" class="text-success" value="{{count($pre_assessment->where('status', 'Pending'))}}" style="background: none; border: none;">
                        </h1>
                    </form>
                </div> --}}
                <div class="ibox-content">
                    <form method="GET">
                        <h1 class="no-margins">
                            <input type="hidden" name="status" value="NotDelayed">
                            <input type="submit" class="text-success" value="{{ $notDelayedCount }}" style="background: none; border: none;">
                        </h1>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-lg-2">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>Declined</h5>
                </div>
                <div class="ibox-content">
                    <form method="GET">
                        <h1 class="no-margins">
                            <input type="hidden" name="status" value="Declined">
                            <input type="submit" class="text-success" value="{{ $declinedCount }}" style="background: none; border: none;">
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
                    <form method="GET">
                        <h1 class="no-margins">
                            <input type="hidden" name="status" value="Approved">
                            <input type="submit" class="text-success" value="{{ $approvedCount }}" style="background: none; border: none;">
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
                    <form method="GET">
                        <h1 class="no-margins">
                            <input type="hidden" name="status" value="Delayed">
                            <input type="submit" class="text-success" value="{{ $delayedCount }}" style="background: none; border: none;">
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
                    <h5>Pre Assessment</h5>
                </div>
                <div class="ibox-content">

                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover tables" >
                            <thead>
                                <tr>
                                    
                                    <th>Actions</th>
                                    {{-- <th>Reference No.</th> --}}
                                    <th>Date Requested</th>
                                    <th>Code</th>
                                    <th>Title</th>
                                    <th>Revision</th>
                                    <th>Type</th>
                                    <th>Requested By</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $delayed = 0;
                                @endphp
                                @foreach($pre_assessment as $pa)
                                    @php
                                        $targetDate = date('Y-m-d', strtotime('+10 days', strtotime($pa->created_at)));
                                    @endphp
                                    <tr>
                                        
                                        <td>
                                            <button type="button" data-target="#viewPreAssessmentModal-{{$pa->id}}" data-toggle="modal" class='btn btn-sm btn-info'>
                                                <i class="fa fa-eye"></i>
                                            </button>
                                        </td>
                                        {{-- <td>CR-{{str_pad($request->id, 5, '0', STR_PAD_LEFT)}}</td> --}}
                                        <td>{{date('M d Y',strtotime($pa->created_at))}}</td>
                                        <td>{{$pa->control_code}}</td>
                                        <td>{{$pa->title}}</td>
                                        <td>{{$pa->revision}}</td>
                                        <td>{{$pa->type_of_document}}</td>
                                        <td>{{$pa->user->name}}</td>
                                        <td> 
                                            @if($pa->status == "Pending")
                                                @if($targetDate < date('Y-m-d'))
                                                    @php
                                                        $delayed++;
                                                    @endphp
                                                    <span class='label label-danger'>
                                                        Delayed - 
                                                @else
                                                    <span class='label label-success'>
                                                @endif
                                            @elseif($pa->status ==  "Approved")
                                                <span class='label label-info'>    
                                            @elseif($pa->status ==  "Declined")
                                                <span class='label label-warning'>
                                            @else
                                                <span class='label label-success'>
                                            @endif
                                            {{$pa->status}}</span>  
                                        </td>
                                    </tr>
                                    @include('view_pre_assessment')
                                @endforeach
                            </tbody>
                        </table>
                    </div>
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
        // var delayed = {!! json_encode($delayed) !!};
        // document.getElementById('delayed').innerText = delayed;

        $('.cat').chosen({width: "100%"});
        $('.tables').DataTable({
            pageLength: 25,
            responsive: true,
            sorting:false,
            dom: '<"html5buttons"B>lTfgitp',
            buttons: [
                {extend: 'copy'},
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