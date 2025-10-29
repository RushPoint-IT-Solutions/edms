@extends('layouts.header')

@section('css')
<link href="{{ asset('login_css/css/plugins/chosen/bootstrap-chosen.css') }}" rel="stylesheet">
<link href="{{ asset('login_css/css/plugins/sweetalert/sweetalert.css') }}" rel="stylesheet">
@endsection

@section('content')
    <div class="wrapper wrapper-content">
        <div class="row">
            <div class="col-lg-3">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5>Memorandum</h5>
                    </div>
                    <div class="ibox-content">
                        <h1 class="no-margins">{{count($memos)}}</h1>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5>Memorandum
                            {{-- @if(auth()->user()->role == 'User' || auth()->user()->role == 'Document Control Officer' || auth()->user()->role == 'Administrator' || auth()->user()->role == 'Business Process Manager') --}}
                            <button type="button" class="btn btn-success" data-toggle="modal" data-target="#new">
                                <i class="fa fa-plus"></i>
                                &nbsp;
                                Upload
                            </button>
                            {{-- @endif --}}
                        </h5>
                    </div>
                    <div class="ibox-content">
                        <div class="table table-responsive">
                            <table class="table table-striped table-bordered table-hover tables" >
                                <thead>
                                    <tr>
                                        <th>Action</th>
                                        <th>Public</th>
                                        <th>Department</th>
                                        <th>Memo Number</th>
                                        <th>Title</th>
                                        <th>Released Date</th>
                                        <th>Uploaded By</th>
                                        <th>Align Policy</th>
                                        <th>Attachment</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($memos as $memo)
                                        <tr>
                                            <td>
                                                {{-- <button type="button" class="btn btn-sm btn-info" title="View" data-toggle="modal" data-target="#view{{$memo->id}}">
                                                    <i class="fa fa-eye"></i>
                                                </button> --}}
                                                <button type="button" class="btn btn-sm btn-warning" title="Edit" data-toggle="modal" data-target="#edit{{$memo->id}}">
                                                    <i class="fa fa-pencil-square-o"></i>
                                                </button>
                                                @if(auth()->user()->role == 'Document Control Officer')
                                                {{-- <form method="POST" action="{{ url('delete_memo/'.$memo->id) }}" onsubmit="show()">
                                                    @csrf

                                                </form> --}}
                                                <button type="button" class="btn btn-sm btn-danger deleteMemo" id="{{ $memo->id }}">
                                                    <i class="fa fa-trash"></i>
                                                </button>
                                                
                                                @endif
                                            </td>
                                            <td>
                                                <form method="POST" action="{{url('update_status/'.$memo->id)}}" onsubmit="show()" id="updateStatusForm{{$memo->id}}">
                                                    @csrf 

                                                    <input type="checkbox" name="status" class="form-check" onchange="updateStatus({{$memo->id}})" value="Public" @if($memo->status == 'Public') checked @endif>
                                                </form>
                                            </td>
                                            <td>{{$memo->department->name}}</td>
                                            <td>{{$memo->memo_number}}</td>
                                            <td>{{$memo->title}}</td>
                                            <td>{{date('M d Y', strtotime($memo->released_date))}}</td>
                                            <td>{{$memo->user->name}}</td>
                                            <td>
                                                @foreach ($memo->memorandum_document as $memo_docs)
                                                    <a href="{{url('view-document/'.$memo_docs->document->id)}}">{{$memo_docs->document->control_code}}</a> <br>
                                                @endforeach
                                            </td>
                                            <td>
                                                <a href="{{url($memo->file_memo)}}" target="_blank">
                                                    <i class="fa fa-file"></i>
                                                </a>
                                            </td>
                                            <td>
                                                @if($memo->status == 'Private')
                                                    <span class="label label-danger">Private</span>
                                                @else
                                                    <span class="label label-primary">Public</span>
                                                @endif
                                            </td>
                                        </tr>

                                        @include('edit_memorandum')
                                        {{-- @include('view_memorandum') --}}
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('new_memorandum')
@endsection

@section('js')
<script src="{{ asset('login_css/js/plugins/dataTables/datatables.min.js')}}"></script>
<script src="{{ asset('login_css/js/plugins/chosen/chosen.jquery.js') }}"></script>
<script src="{{ asset('login_css/js/plugins/sweetalert/sweetalert.min.js') }}"></script>
<script>
    function updateStatus(id)
    {
        $('#updateStatusForm'+id).submit()
        
    }

    $(document).ready(function(){
        $(".cat").chosen({width:"100%"})

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

        $("#type").on('change', function() {
            if($(this).val() == 'Align Policy')
            {
                $("#policySelectOption").removeAttr('hidden')
                $("[name='document[]']").prop('required', true)
            }
            else
            {
                $("#policySelectOption").prop('hidden', true)
                $("[name='document[]']").removeAttr('required')
            }
        })

        $('.deleteMemo').click(function () {
            var id = this.id;
            
            swal({
                title: "Are you sure?",
                text: "This memo will be deleted!",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "Yes, delete it!",
                closeOnConfirm: false
            }, function (){
                $.ajax({
                    dataType: 'json',
                    type:'POST',
                    url:  '{{url("delete_memo")}}',
                    data:{id:id},
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                }).done(function(data){
                    console.log(data);
                    swal("Deleted!", "Memo is now deleted.", "success");
                    location.reload();
                }).fail(function(data)
                {
                    
                    swal("Deleted!", "Memo is now deleted.", "success");
                location.reload();
                });
            });
        });
    });

</script>
@endsection