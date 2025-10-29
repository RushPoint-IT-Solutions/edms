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
                    <h5>Users</h5>
                </div>
                <div class="ibox-content">
                    <h1 class="no-margins">{{count($users)}}</h1>
                </div>
            </div>
        </div>
        <div class="col-lg-3">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>Active</h5>
                </div>
                <div class="ibox-content">
                    <h1 class="no-margins">{{count($users->where('status',""))}}</h1>
                </div>
            </div>
        </div>
        <div class="col-lg-3">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>Deactivated</h5>
                </div>
                <div class="ibox-content">
                    <h1 class="no-margins">{{count($users->where('status','1'))}}</h1>
                </div>
            </div>
        </div>
        
    </div>
    <div class='row'>
        <div class="col-lg-12">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>Users <button class="btn btn-success "  data-target="#new_account" data-toggle="modal" type="button"><i class="fa fa-plus"></i>&nbsp;New Account</button></h5>
                  
                </div>
                <div class="ibox-content">

                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover tables" >
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Company</th>
                                <th>Department</th>
                                
                                <th>Share Department</th>
                                <th>Role</th>
                                
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($users as $user)
                            <tr>
                                <td>{{$user->name}}</td>
                                <td>{{$user->email}}</td>
                                <td>{{$user->company->name}}</td>
                                <td>{{optional($user->department)->name}}</td>
                                
                                <td>
                                    <small>
                                    @foreach($user->departments as $department)
                                    {{$department->dep->name}}  <br>
                                    @endforeach
                                    </small>
                                </td>
                                <td>{{$user->role}}</td>
                                <td  id='statususer{{$user->id}}'>@if($user->status) <small class="label label-danger">Inactive</small>  @else <small class="label label-primary">Active</small> @endif</td>
                                <td data-id='{{$user->id}}' id='actionuser{{$user->id}}'>

                                        @if($user->status)
                                            <button class="btn btn-sm btn-primary activate-user" id='{{$user->id}}' title="Activate"><i class="fa fa-check"></i></button>
                                        @else
                                            <button class="btn btn-sm btn-warning" data-target="#change_pass{{$user->id}}" data-toggle="modal" title='change password' ><i class="fa fa-key"></i></button>
                                            <button class="btn btn-sm btn-info"  title='Edit' data-target="#editUser{{$user->id}}" data-toggle="modal"><i class="fa fa-edit"></i></button>
                                        @if(Auth::user()->id != $user->id)
                                            <button class="btn btn-sm btn-danger deactivate-user" id='{{$user->id}}' title='Deactivate' ><i class="fa fa-trash"></i></button>@endif
                                        @endif
                                </td>
                            </tr>
                            @include('edit_user') 
                            @include('changepassword') 
                            @endforeach
                        </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>

    </div>
</div>
@include('new_account')
@endsection
@section('js')
<script src="{{ asset('login_css/js/plugins/dataTables/datatables.min.js')}}"></script>
<script src="{{ asset('login_css/js/plugins/chosen/chosen.jquery.js') }}"></script>
<script src="{{ asset('login_css/js/plugins/sweetalert/sweetalert.min.js') }}"></script>

<script>
    $(document).ready(function(){
        $('.deactivate-user').click(function () {
        
        var id = this.id;
            swal({
                title: "Are you sure?",
                text: "This user will be deactivated!",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "Yes, deactivated it!",
                closeOnConfirm: false
            }, function (){
                $.ajax({
                    dataType: 'json',
                    type:'POST',
                    url:  '{{url("deactivate-user")}}',
                    data:{id:id},
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                }).done(function(data){
                    console.log(data);
                    swal("Deactivated!", "User is now deactivated.", "success");
                    location.reload();
                }).fail(function(data)
                {
                    
                    swal("Deactivated!", "User is now deactivated.", "success");
                location.reload();
                });
            });
        });
        $('.activate-user').click(function () {
        
        var id = this.id;
            swal({
                title: "Are you sure?",
                text: "This user will be activated!",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "Yes, Activated it!",
                closeOnConfirm: false
            }, function (){
                $.ajax({
                    dataType: 'json',
                    type:'POST',
                    url:  '{{url("activate-user")}}',
                    data:{id:id},
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                }).done(function(data){
                    console.log(data);
                    swal("Activated!", "User is now activated.", "success");
                    location.reload();
                }).fail(function(data)
                {
                    
                    swal("Activated!", "User is now activated.", "success");
                location.reload();
                });
            });
        });
        
        $('.cat').chosen({width: "100%"});
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
