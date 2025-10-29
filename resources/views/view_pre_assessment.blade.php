
<div class="modal" id="viewPreAssessmentModal-{{$pa->id}}" tabindex="-1" role="dialog"  >
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <div class='col-md-10'>
                    <h5 class="modal-title" id="exampleModalLabel">View Pre-assessment - {{$pa->status}}</h5>
                </div>
                <div class='col-md-2'>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close" >
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            </div>
            <form method="POST" action="{{url('approve_pre_assessment/'.$pa->id)}}" onsubmit="show()">
                {{csrf_field()}}
                
                <div class="modal-body">
                    <div class="row">
                        <div class="col-lg-6">
                            {{-- Effective Date : {{$pa->effective_date}} --}}
                        </div>
                        <div class="col-lg-6">
                            Type of Document : {{$pa->type_of_document}}
                        </div>
                        @if($pa->request_type != 'Obsolete')
                        <div class="col-lg-6">
                            Draft Link : <a href="{{$pa->link_draft}}" target="_blank">Draft Link</a>
                        </div>
                        @endif
                        @if($pa->original_attachment_pdf != null)
                            <div class="col-lg-6">
                                Original PDF Link : <a href='{{url($pa->original_attachment_pdf."?page=hsn#toolbar=0")}}' target="_blank">Link</a> <br>
                            </div>
                        @endif
                        @if($pa->supporting_documents != null)
                        <div class="col-lg-6">
                            Supporting Documents : <a href="{{url($pa->supporting_documents)}}" target="_blank">Link</a>
                        </div>
                        @endif
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-lg-6">
                            Title : {{$pa->title}}
                        </div>
                        <div class="col-lg-6">
                            &nbsp;
                        </div>
                        <div class="col-lg-6">
                            Requested By : {{$pa->user->name}} 
                        </div>
                        <div class="col-lg-6">
                            Date Requested : {{date('M d, Y', strtotime($pa->created_at))}}
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-lg-12">
                            Request Type : <strong>{{$pa->request_type}}</strong> <br>
                            Reason for changes : <strong>{{$pa->reason_for_changes}}</strong>
                            <div class="panel panel-primary">
                                <div class="panel-heading">
                                    @if($pa->request_type != "Revision")
                                        Descriptions/Remarks
                                    @else
                                    Reason/s for Change
                                    @endif
                                </div>
                                <div class="panel-body">
                                    {!!nl2br(e($pa->change_request))!!}
                                </div>
                            </div>
                            @if($pa->request_type == "Revision")
                            <div class='row '>
                                <div class='col-md-6 '>
                                    <div class="panel panel-primary">
                                        <div class="panel-heading">
                                            From (Indicate clause)
                                        </div>
                                        <div class="panel-body">
                                            {!! nl2br(e($pa->indicate_clause)) !!}
                                        </div>
                                    </div>
                                </div>
                                <div class='col-md-6'>
                                    <div class="panel panel-primary">
                                        <div class="panel-heading">
                                            To (Indicate the changes)
                                        </div>
                                        <div class="panel-body">
                                            {!! nl2br(e($pa->indicate_changes)) !!}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
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
                            @if($pa->approvers)
                            <div class='row'>
                                <div class='col-md-3 border border-primary border-top-bottom border-left-right'>
                                    {{$pa->approvers->user->name}}
                                </div>
                                <div class='col-md-3 border border-primary border-top-bottom border-left-right'>
                                    {{$pa->approvers->status}}
                                </div>
                                <div class='col-md-2 border border-primary border-top-bottom border-left-right'>
                                    @if($pa->approvers->start_date != null){{$pa->approvers->start_date}}@endif &nbsp;
                                </div>
                                <div class='col-md-2 border border-primary border-top-bottom border-left-right'>
                                    @if($pa->approvers->status != "Waiting" && $pa->approvers->status != "Pending")
                                        {{date('Y-m-d',strtotime($pa->approvers->updated_at))}}
                                    @endif
                                </div>
                                <div class='col-md-2 border border-primary border-top-bottom border-left-right'>
                                    {!! nl2br(e($pa->approvers->remarks))!!}&nbsp;
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                    @if($pa->approvers)
                        @if(auth()->user()->id == $pa->approvers->user_id)
                            @if($pa->status == "Pending")
                                <hr>
                                <div class="row">
                                    <div class="col-md-4">
                                        Action : 
                                        <select name="action" id="action" class="form-control cat" required>
                                            <option value=""></option>
                                            <option value="Approved">Approved</option>
                                            <option value="Declined">Declined</option>
                                        </select>
                                    </div>
                                    <div class='col-md-8'>
                                        Remarks :
                                        <textarea name='remarks' class='form-control-sm form-control' required></textarea>
                                    </div>
                                </div>
                            @endif
                        @endif
                    @endif
                    @if(auth()->user()->role == "Administrator")
                        @if($pa->status == "Pending")
                            <hr>
                            <div class="row">
                                <div class="col-md-4">
                                    Action : 
                                    <select name="action" id="action" class="form-control cat" required>
                                        <option value=""></option>
                                        <option value="Approved">Approved</option>
                                        <option value="Declined">Declined</option>
                                    </select>
                                </div>
                                <div class='col-md-8'>
                                    Remarks :
                                    <textarea name='remarks' class='form-control-sm form-control' required></textarea>
                                </div>
                            </div>
                        @endif
                    @endif
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    @if((auth()->user()->role == "Document Control Officer") || (auth()->user()->role == "Administrator"))
                        @if($pa->status == "Pending")
                        <button type="submit" class="btn btn-primary">Submit</button>
                        @endif
                    @endif
                </div>
            </form>
        </div>
    </div>
</div>
