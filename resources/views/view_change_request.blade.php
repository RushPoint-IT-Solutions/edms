
<div class="modal" id="view_request{{$request->id}}" tabindex="-1" role="dialog"  >
    <div class="modal-dialog modal-lg " role="document">
        <div class="modal-content">
            <div class="modal-header">
                <div class='col-md-10'>
                    <h5 class="modal-title" id="exampleModalLabel">Change Request ({{$request->status}})</h5>
                </div>
                {{-- <div class='col-md-2'>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div> --}}
            </div>
            <div class="modal-body">
                <div class='row '>
                    <div class='col-md-6'>
                        DOC ID : DOC-{{ date('Y', strtotime($request->created_at)) }}-{{ str_pad($request->id,3,'0',STR_PAD_LEFT) }}
                    </div>
                    <div class='col-md-6'>
                        Type of Document : {{$request->type}}
                    </div>
                    <div class='col-md-6'>
                        Category : {{ $request->category }}
                    </div>
                    <div class='col-md-6'>
                        Privacy : {{ $request->privacy }}
                    </div>
                </div>
                <hr>
                <div class='row '>
                    <div class='col-md-6'>
                        Control Code : {{$request->control_code}} Rev. {{$request->revision}}
                    </div>
                    <div class='col-md-6'>
                        Title : {{$request->title}}
                    </div>
                    <div class='col-md-6'>
                        Requested By : {{$request->user->name}} 
                    </div>
                    <div class='col-md-6'>
                        Date Requested : {{date('M d, Y',strtotime($request->created_at))}}
                    </div>
                </div>
                <hr>
                @if($request->request_type == "Revision")
                <div class='row '>
                    <div class='col-md-6 '>
                        <div class="panel panel-primary">
                            <div class="panel-heading">
                                From (Indicate clause)
                            </div>
                            <div class="panel-body">
                                {!! nl2br(e($request->indicate_clause)) !!}
                            </div>
                        </div>
                    </div>
                    <div class='col-md-6'>
                        <div class="panel panel-primary">
                            <div class="panel-heading">
                                To (Indicate the changes)
                            </div>
                            <div class="panel-body">
                                {!! nl2br(e($request->indicate_changes)) !!}
                            </div>
                        </div>
                    </div>
                </div>
                <hr>
                @endif
                <div class="panel panel-primary">
                    <div class="panel-heading">
                        Approvers
                    </div>
                    <div class="panel-body">
                        <div class='row'>
                            <div class='col-md-3  border border-primary border-top-bottom border-left-right'>
                                Name
                            </div>
                            <div class='col-md-3  border border-primary border-top-bottom border-left-right'>
                                Status
                            </div>
                            <div class='col-md-2 border border-primary border-top-bottom border-left-right'>
                                Start Date
                            </div>
                            <div class='col-md-2  border border-primary border-top-bottom border-left-right'>
                                Action Date
                            </div>
                            <div class='col-md-2 border border-primary border-top-bottom border-left-right'>
                                Remarks
                            </div>
                        </div>
                        @foreach($request->approvers as $approver)
                        <div class='row'>
                            <div class='col-md-3 border border-primary border-top-bottom border-left-right'>
                                {{$approver->user->name}}
                            </div>
                            <div class='col-md-3 border border-primary border-top-bottom border-left-right'>
                                {{$approver->status}}
                            </div>
                            <div class='col-md-2 border border-primary border-top-bottom border-left-right'>
                                @if($approver->start_date != null){{$approver->start_date}}@endif &nbsp;
                            </div>
                            <div class='col-md-2 border border-primary border-top-bottom border-left-right'>
                                {{-- @if($approver->status != "Waiting"){{date('Y-m-d',strtotime($approver->updated_at))}}@endif &nbsp; --}}
                                @if($approver->status != "Waiting" && $approver->status != "Pending")
                                    {{date('Y-m-d',strtotime($approver->updated_at))}}
                                @endif
                            </div>
                            <div class='col-md-2 border border-primary border-top-bottom border-left-right'>
                                {!! nl2br(e($approver->remarks))!!}&nbsp;
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
