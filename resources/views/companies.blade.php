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
                    <h5>Companies</h5>
                </div>
                <div class="ibox-content">
                    <h1 class="no-margins">{{count($companies)}}</h1>
                </div>
            </div>
        </div>
        <div class="col-lg-3">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>Active</h5>
                </div>
                <div class="ibox-content">
                    <h1 class="no-margins">{{count($companies->where('status',null))}}</h1>
                </div>
            </div>
        </div>
        <div class="col-lg-3">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>Deactivated</h5>
                </div>
                <div class="ibox-content">
                    <h1 class="no-margins">{{count($companies->where('status','!=',null))}}</h1>
                </div>
            </div>
        </div>
        
    </div>
    <div class='row'>
        <div class="col-lg-12">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>Company <button class="btn btn-success "  data-target="#new_company" data-toggle="modal" type="button"><i class="fa fa-plus"></i>&nbsp;New </button></h5>
                  
                </div>
                <div class="ibox-content">

                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover tables" >
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Code</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($companies as $company)
                            <tr>
                                <td>{{$company->name}}</td>
                                <td>{{$company->code}}</td>
                                <td id='statuscompanytd{{$company->id}}'>@if($company->status) <small class="label label-danger">Inactive</small>  @else <small class="label label-primary">Active</small> @endif</td>
                                <td data-id='{{$company->id}}' id='actioncompanytd{{$company->id}}'>
                                    @if($company->status)
                                    <button class="btn btn-sm btn-primary activate-company" id='{{$company->id}}' title="Activate"><i class="fa fa-check"></i></button>
                                    @else
                                    {{-- <button class="btn btn-sm btn-info"  title='Edit'  data-target="#editCompany{{$company->id}}" data-toggle="modal"><i class="fa fa-edit"></i></button> --}}
                                    <button class="btn btn-sm btn-danger deactivate-company" id='{{$company->id}}' title='Deactivate' ><i class="fa fa-trash"></i></button>
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
@include('new_company')
@endsection
@section('js')
<script src="{{ asset('login_css/js/plugins/dataTables/datatables.min.js')}}"></script>
<script src="{{ asset('login_css/js/plugins/chosen/chosen.jquery.js') }}"></script>
<script src="{{ asset('login_css/js/plugins/sweetalert/sweetalert.min.js') }}"></script>
<script>
    $(document).ready(function(){
        $('.deactivate-company').click(function () {
        
        var id = this.id;
            swal({
                title: "Are you sure?",
                text: "This company will be deactivated!",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "Yes, deactivated it!",
                closeOnConfirm: false
            }, function (){
                $.ajax({
                    dataType: 'json',
                    type:'POST',
                    url:  '{{url("deactivate-company")}}',
                    data:{id:id},
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                }).done(function(data){
                    console.log(data);
                    swal("Deactivated!", "Company is now deactivated.", "success");
                    location.reload();
                }).fail(function(data)
                {
                    
                    swal("Deactivated!", "Company is now deactivated.", "success");
                location.reload();
                });
            });
        });
        $('.activate-company').click(function () {
        
        var id = this.id;
            swal({
                title: "Are you sure?",
                text: "This company will be activated!",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "Yes, Activated it!",
                closeOnConfirm: false
            }, function (){
                $.ajax({
                    dataType: 'json',
                    type:'POST',
                    url:  '{{url("activate-company")}}',
                    data:{id:id},
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                }).done(function(data){
                    console.log(data);
                    swal("Activated!", "Company is now activated.", "success");
                    location.reload();
                }).fail(function(data)
                {
                    
                    swal("Activated!", "Company is now activated.", "success");
                location.reload();
                });
            });
        });

        $('.locations').chosen({width: "100%"});
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
