<div class="modal fade" id="viewPreAssessmentModal-{{$pa->id}}" tabindex="-1" aria-labelledby="viewPreAssessmentModalLabel-{{$pa->id}}" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header" style="border-bottom: solid 2px #800000">
                <h5 class="modal-title" id="viewPreAssessmentModalLabel-{{$pa->id}}">View Pre-assessment - {{$pa->status}}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST" action="{{url('approve_pre_assessment/'.$pa->id)}}" onsubmit="show()">
                {{csrf_field()}}
                
                <div class="modal-body">
                    <div class='row'>
                        <div class='col-md-6'>
                            {{-- Effective Date : {{$pa->effective_date}} --}}
                        </div>
                        <div class='col-md-6'>
                            Type of Document : {{$pa->type_of_document}}
                        </div>
                        @if($pa->request_type != 'Obsolete')
                        <div class='col-md-6'>
                            Draft Link : <a href="{{$pa->link_draft}}" target="_blank">Draft Link</a>
                        </div>
                        @endif
                        @if($pa->original_attachment_pdf != null)
                            <div class='col-md-6'>
                                Original PDF Link : <a href='{{url($pa->original_attachment_pdf."?page=hsn#toolbar=0")}}' target="_blank">Link</a> <br>
                            </div>
                        @endif
                        @if($pa->supporting_documents != null)
                        <div class='col-md-6'>
                            Supporting Documents : <a href="{{url($pa->supporting_documents)}}" target="_blank">Link</a>
                        </div>
                        @endif
                    </div>
                    <hr>
                    <div class='row'>
                        <div class='col-md-6'>
                            Title : {{$pa->title}}
                        </div>
                        <div class='col-md-6'>
                            &nbsp;
                        </div>
                        <div class='col-md-6'>
                            Requested By : {{$pa->user->name}} 
                        </div>
                        <div class='col-md-6'>
                            Date Requested : {{date('M d, Y', strtotime($pa->created_at))}}
                        </div>
                    </div>
                    <hr>
                    <div class='row'>
                        <div class='col-md-12'>
                            Request Type : <strong>{{$pa->request_type}}</strong> <br>
                            Reason for changes : <strong>{{$pa->reason_for_changes}}</strong>
                            <div class="card border-primary mt-2">
                                <div class="card-header text-white" style="background-color: #800000;">
                                    @if($pa->request_type != "Revision")
                                        Descriptions/Remarks
                                    @else
                                    Reason/s for Change
                                    @endif
                                </div>
                                <div class="card-body">
                                    {!!nl2br(e($pa->change_request))!!}
                                </div>
                            </div>
                            @if($pa->request_type == "Revision")
                            <div class='row mt-2'>
                                <div class='col-md-6'>
                                    <div class="card border-primary">
                                        <div class="card-header bg-primary text-white">
                                            From (Indicate clause)
                                        </div>
                                        <div class="card-body">
                                            {!! nl2br(e($pa->indicate_clause)) !!}
                                        </div>
                                    </div>
                                </div>
                                <div class='col-md-6'>
                                    <div class="card border-primary">
                                        <div class="card-header bg-primary text-white">
                                            To (Indicate the changes)
                                        </div>
                                        <div class="card-body">
                                            {!! nl2br(e($pa->indicate_changes)) !!}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                    <div class="card border-primary mt-2">
                        <div class="card-header text-white" style="background-color: #800000;">
                            Approvers
                        </div>
                        <div class="card-body">
                            <div class='row'>
                                <div class='col-md-3 border border-primary'>
                                    Name
                                </div>
                                <div class='col-md-3 border border-primary'>
                                    Status
                                </div>
                                <div class='col-md-2 border border-primary'>
                                    Start Date
                                </div>
                                <div class='col-md-2 border border-primary'>
                                    Action Date
                                </div>
                                <div class='col-md-2 border border-primary'>
                                    Remarks
                                </div>
                            </div>
                            @if($pa->approvers)
                            <div class='row'>
                                <div class='col-md-3 border border-primary'>
                                    {{$pa->approvers->user->name}}
                                </div>
                                <div class='col-md-3 border border-primary'>
                                    {{$pa->approvers->status}}
                                </div>
                                <div class='col-md-2 border border-primary'>
                                    @if($pa->approvers->start_date != null){{$pa->approvers->start_date}}@endif &nbsp;
                                </div>
                                <div class='col-md-2 border border-primary'>
                                    @if($pa->approvers->status != "Waiting" && $pa->approvers->status != "Pending")
                                        {{date('Y-m-d',strtotime($pa->approvers->updated_at))}}
                                    @endif
                                </div>
                                <div class='col-md-2 border border-primary'>
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
                                <div class='row'>
                                    <div class='col-md-4'>
                                        Action : 
                                        <select name="action" id="action" class="form-control form-control-sm cat" required>
                                            <option value=""></option>
                                            <option value="Approved">Approved</option>
                                            <option value="Declined">Declined</option>
                                        </select>
                                    </div>
                                    <div class='col-md-8'>
                                        Remarks :
                                        <textarea name='remarks' class='form-control form-control-sm' required></textarea>
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
                                    <select name="action" id="action" class="form-control form-control-sm cat" required>
                                        <option value=""></option>
                                        <option value="Approved">Approved</option>
                                        <option value="Declined">Declined</option>
                                    </select>
                                </div>
                                <div class='col-md-8'>
                                    Remarks :
                                    <textarea name='remarks' class='form-control form-control-sm' required></textarea>
                                </div>
                            </div>
                        @endif
                    @endif
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" style="background-color: #3f3b3b; color: #ffff; border: none;">Close</button>
                    @if((auth()->user()->role == "Document Control Officer") || (auth()->user()->role == "Administrator"))
                        @if($pa->status == "Pending")
                        <button type="submit" class="btn" style="background-color: #800000; color: #ffff; border: none;">Submit</button>
                        @endif
                    @endif
                </div>
            </form>
        </div>
    </div>
</div>