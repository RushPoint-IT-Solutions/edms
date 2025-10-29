
<div class="modal" id="view_request{{$request->id}}" tabindex="-1" role="dialog"  >
    <div class="modal-dialog modal-lg " role="document">
        <div class="modal-content">
            <div class="modal-header">
                <div class='col-md-10'>
                    <h5 class="modal-title" id="exampleModalLabel">View Copy Request ({{$request->status}})</h5>
                </div>
                <div class='col-md-2'>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close" >
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            </div>
                <div class="modal-body">
                    <div class='row '>
                        <div class='col-md-6'>
                            Reference Number : <b>CR-{{str_pad($request->id, 5, '0', STR_PAD_LEFT)}}</b>
                        </div>
                        <div class='col-md-6'>
                            Type of Document : {{$request->type_of_document}}
                        </div>
                        <div class='col-md-6'>
                            Date Needed : {{date('M d Y',strtotime($request->date_needed))}}
                        </div>
                        <div class='col-md-6'>
                           Copy Needed : {{$request->copy_count}}
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
                    </div>
                    <div class='row '>
                        <div class='col-md-6'>
                            Requested By : {{$request->user->name}} 
                        </div>
                        <div class='col-md-6'>
                            Date Requested : {{date('M d, Y',strtotime($request->created_at))}}
                        </div>
                    </div>
                    <div class='row '>
                        <div class='col-md-12'>
                            Purpose : {{$request->purpose}} 
                        </div>
                    </div>
                    <hr>
                    <div class='row text-center'>
                        <div class='col-md-3 border  border-primary border-top-bottom border-left-right'>
                           Approver
                        </div>
                        <div class='col-md-3 border  border-primary border-top-bottom border-left-right'>
                           Status
                        </div>
                        <div class='col-md-2 border  border-primary border-top-bottom border-left-right'>
                           Start Date
                        </div>
                        <div class='col-md-2 border  border-primary border-top-bottom border-left-right'>
                           Action Date
                        </div>
                        <div class='col-md-2 border  border-primary border-top-bottom border-left-right'>
                           Remarks
                        </div>
                    </div>
                    @foreach($request->approvers as $approver)
                    <div class='row text-left'>
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
                    <hr>
                    <div class="row">
                        <div class="col-md-12">
                            @if($request->status == "Approved")
                                @if(strtotime($request->expiration_date) >= strtotime(date('Y-m-d')))
                                    @if($request->type_of_document == "Hard Copy")
                                    <div class="panel panel-primary">
                                        <div class="panel-heading">
                                            Hard Copy Request
                                        </div>
                                        <div class="panel-body">
                                            <p>Please get your hard copy from DRC. This request will expire by {{date("M. d, Y",strtotime($request->expiration_date))}}</p>
                                        </div>
                                    </div>
                                    @else
                                    @if($request->document_access)
                                        <div class="panel panel-primary">
                                            <div class="panel-heading">
                                                E-Copy Request, Expiration Date : {{date("M. d, Y",strtotime($request->expiration_date))}} 
                                                @if($request->document_access->attachment != null)
                                                    @if(($request->document->category == "FORM") || ($request->document->category == "TEMPLATE"))
                                                        <a href="{{url($request->document_access->attachment->attachment)}}" target="_blank"><button type="button" class="btn btn-w-m btn-danger">Download</button></a>
                                                    @else
                                                        <a href="{{url('view-pdf/'.$request->document_access->attachment_id)}}" target="_blank"><button type="button" class="btn btn-w-m btn-danger">Download</button></a>
                                                    @endif
                                                @endif
                                            </div>
                                            <div class="panel-body">
                                                @if($request->document_access->attachment != null)
                                                    @if(($request->document->category == "FORM") || ($request->document->category == "TEMPLATE"))
                                                        <iframe width='100%' height='500px;'  src="{{url($request->document_access->attachment->attachment)}}" title="Access"></iframe>
                                                    @else
                                                        <iframe width='100%' height='500px;'  src="{{url('view-pdf/'.$request->document_access->attachment_id.'?page=hsn#toolbar=0')}}" title="Access"></iframe>
                                                    @endif
                                                 @endif
                                                <p></p>
                                            </div>
                                        </div>
                                       @endif
                                    @endif
                                @else
                                    <div class="panel panel-danger">
                                        <div class="panel-heading">
                                           Expired Request
                                        </div>
                                        <div class="panel-body">
                                            <p>Your request or access to this request is expired. Expiration Date : {{date("M. d, Y",strtotime($request->expiration_date))}}</p>
                                        </div>
                                    </div>
                                @endif
                            @endif
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </form>
        </div>
    </div>
</div>
