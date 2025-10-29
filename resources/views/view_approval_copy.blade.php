
<div class="modal" id="view_request_copy{{$request->id}}" tabindex="-1" role="dialog"  >
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
            <form method='post' action='{{url('copy-request-action/'.$copy_approval->id)}}' onsubmit='show();' class="form-horizontal"  enctype="multipart/form-data" >
                {{ csrf_field() }}
                <input type="hidden" name="id" value="{{$copy_approval->copy_request->document_id}}">
                <input type="hidden" name="user_id" value="{{$request->user->id}}">
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
                    @php
                        $document =$request->document;
                    @endphp
                    @if($request->level > 1)
                    <hr>
                     <div class="row">
                            <div class="col-lg-12">
                               Document Request : 
                                        @foreach($document->attachments as $attachment)
                                            @if($attachment->attachment != null)
                                               @if($attachment->type == "pdf_copy")
                                                    @if(($document->category == "FORM") || ($document->category == "TEMPLATE"))<a href='{{url($attachment->attachment)}}' target="_blank" ><i class="fa fa-file-pdf-o"></i> PDF Copy</a>
                                                    @else<a href='{{url('view-pdf/'.$attachment->id)}}' target="_blank" ><i class="fa fa-file-pdf-o"></i> PDF Copy</a>
                                                @endif
                                                @endif
                                             @endif
                                        @endforeach
                                   
                                </dl>
                            </div>
                    </div>
                    @endif
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
                            {{$approver->user->name}} &nbsp;
                            </div>
                            <div class='col-md-3 border border-primary border-top-bottom border-left-right'>
                                {{$approver->status}} &nbsp;
                            </div>
                            <div class='col-md-2 border border-primary border-top-bottom border-left-right'>
                                @if($approver->start_date != null){{date('M d, Y',strtotime($approver->start_date))}}@endif &nbsp;
                            </div>
                            <div class='col-md-2 border border-primary border-top-bottom border-left-right'>
                                @if($approver->level < $request->level){{date('M d, Y',strtotime($approver->updated_at))}}@endif &nbsp;
                            </div>
                            <div class='col-md-2 border border-primary border-top-bottom border-left-right'>
                                {!! nl2br(e($approver->remarks))!!} &nbsp;
                            </div>
                        </div>
                    @endforeach
                    <hr>
                    <div class='row'>
                        <div class='col-md-4'>
                            Action :
                            <select name='action' class='form-control-sm form-control cat' required>
                                <option value=""></option>
                                <option value="Approved" >Approve</option>
                                <option value="Declined" >Decline</option>
                            </select>
                        </div>
                        <div class='col-md-8'>
                            Remarks :
                            <textarea name='remarks' class='form-control-sm form-control' required></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type='submit'  class="btn btn-primary">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>
