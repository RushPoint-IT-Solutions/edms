@extends('layouts.header')
@section('css')
<link href="{{ asset('login_css/css/plugins/chosen/bootstrap-chosen.css') }}" rel="stylesheet">
<link href="{{ asset('login_css/css/plugins/sweetalert/sweetalert.css') }}" rel="stylesheet">
@endsection
@section('content')

<div class="wrapper wrapper-content">
    @include('error')
    <div class="row">
        <div class="col-lg-3">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>Departments</h5>
                </div>
                <div class="ibox-content">
                    <h1 class="no-margins">{{count($departments)}}</h1>
                </div>
            </div>
        </div>
        <div class="col-lg-3">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>Active</h5>
                </div>
                <div class="ibox-content">
                    <h1 class="no-margins">{{count($departments->where('status',null))}}</h1>
                </div>
            </div>
        </div>
        <div class="col-lg-3">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>Deactivated</h5>
                </div>
                <div class="ibox-content">
                    <h1 class="no-margins">{{count($departments->where('status','!=',null))}}</h1>
                </div>
            </div>
        </div>
        
    </div>
    <div class='row'>
        <div class="col-lg-12">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>Departments <button class="btn btn-success "  data-target="#new_department" data-toggle="modal" type="button"><i class="fa fa-plus"></i>&nbsp;New </button></h5>
                  
                </div>
                <div class="ibox-content">

                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover tables" >
                        <thead>
                            <tr>
                                
                                <th>Code</th>
                                <th>Name</th>
                                <th>Department Head</th>
                                <th>Permit Accountable Person</th>
                                <th>Approver</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($departments as $department)
                            <tr>
                                
                                <td>{{$department->code}}</td>
                                <td>{{$department->name}}</td>
                                <td>{{($department->dep_head != null) ? $department->dep_head->name : "No Head"}}</td>
                                <td>@foreach($department->permit_accounts as $accountable)
                                    {{$accountable->user->name}}<br>
                                @endforeach</td>
                                <td>
                                    @foreach($department->approvers as $approver)
                                        {{$approver->level.". ".$approver->user->name}}<br>
                                    @endforeach
                                </td>
                                <td>@if($department->status) <small class="label label-danger">Inactive</small>  @else <small class="label label-primary">Active</small> @endif</td>
                               
                                <td data-id='{{$department->id}}' id='actioncompanytd{{$department->id}}'>
                                    @if($department->status)
                                    <button class="btn btn-sm btn-primary activate-department" id='{{$department->id}}' title="Activate"><i class="fa fa-check"></i></button>
                                    @else
                                    <button class="btn btn-sm btn-info"  title='Edit' data-target="#editDepartment{{$department->id}}" data-toggle="modal"><i class="fa fa-edit"></i></button>
                                    <button class="btn btn-sm btn-danger deactivate-department" id='{{$department->id}}' title='Deactivate' ><i class="fa fa-trash"></i></button>
                                    @endif
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
</div>
@include('new_department')
@foreach($departments as $department)
@include('edit_department')
@endforeach
@endsection
@section('js')
<script src="{{ asset('login_css/js/plugins/dataTables/datatables.min.js')}}"></script>
<script src="{{ asset('login_css/js/plugins/chosen/chosen.jquery.js') }}"></script>
<script src="{{ asset('login_css/js/plugins/sweetalert/sweetalert.min.js') }}"></script>
<script>
    $(document).ready(function(){
        $('.deactivate-department').click(function () {
        
        var id = this.id;
            swal({
                title: "Are you sure?",
                text: "This department will be deactivated!",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "Yes, deactivated it!",
                closeOnConfirm: false
            }, function (){
                $.ajax({
                    dataType: 'json',
                    type:'POST',
                    url:  '{{url("deactivate-department")}}',
                    data:{id:id},
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                }).done(function(data){
                    console.log(data);
                    swal("Deactivated!", "Department is now deactivated.", "success");
                    location.reload();
                }).fail(function(data)
                {
                    
                    swal("Deactivated!", "Department is now deactivated.", "success");
                location.reload();
                });
            });
        });
        $('.activate-department').click(function () {
        
        var id = this.id;
            swal({
                title: "Are you sure?",
                text: "This department will be activated!",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "Yes, Activated it!",
                closeOnConfirm: false
            }, function (){
                $.ajax({
                    dataType: 'json',
                    type:'POST',
                    url:  '{{url("activate-department")}}',
                    data:{id:id},
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                }).done(function(data){
                    console.log(data);
                    swal("Activated!", "Department is now activated.", "success");
                    location.reload();
                }).fail(function(data)
                {
                    
                    swal("Activated!", "Department is now activated.", "success");
                location.reload();
                });
            });
        });
        
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
