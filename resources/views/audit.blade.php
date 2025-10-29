@extends('layouts.header')
@section('css')
<link href="{{ asset('login_css/css/plugins/chosen/bootstrap-chosen.css') }}" rel="stylesheet">
@endsection
@section('content')

<div class="wrapper wrapper-content">
    <div class='row'>
        <div class="col-lg-12">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>Audit Logs </h5>
                  
                </div>
                <div class="ibox-content">

                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover tables" >
                        <thead>
                                <tr>
                                    
                                    <th>Created Date</th>
                                    <th>Model</th>
                                    <th>User</th>
                                    <th>Event</th>
                                    <th>Auditable Id</th>
                                    <th>Old Values</th>
                                    <th>New Values</th>
                                    <th>Ip Address</th>
                                    <th>Agent</th>
                                </tr>
                        </thead>
                        <tbody>
                            @foreach($audits as $audit)
                                <tr>
                                    
                                    <td>{{date('Y-m-d h:i:s',strtotime($audit->created_at))}}</td>
                                    <td>{{$audit->auditable_type}}</td>
                                    <td>@if($audit->user){{$audit->user->name}}@endif</td>
                                    <td>{{$audit->event}}</td>
                                    <td>{{$audit->auditable_id}}</td>
                                    <td title='{{$audit->old_values}}'>{{substr($audit->old_values,0,10)}}</td>
                                    <td title='{{$audit->new_values}}'>{{substr($audit->new_values,0,10)}}</td>
                                    <td>{{$audit->ip_address}}</td>
                                    <td>{{$audit->user_agent}}</td>
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
        

        $('.locations').chosen({width: "100%"});
        $('.tables').DataTable({
            pageLength: 10,
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
