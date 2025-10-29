
<div class="modal" id="view_pa_approval{{$pa_approval->id}}" tabindex="-1" role="dialog"  >
    <div class="modal-dialog modal-lg " role="document">
        <div class="modal-content">
            <div class="modal-header">
                <div class='col-md-10'>
                    <h5 class="modal-title" id="exampleModalLabel">Pre-assessment ({{$pa_approval->pre_assessment->status}}) - <b class='label label-danger'>{{$pa_approval->pre_assessment->request_type}}</b></h5>
                </div>
                <div class='col-md-2'>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close" >
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            </div>
            <form method='post' action='{{url('approve_pre_assessment/'.$pa_approval->id)}}' onsubmit='show();' class="form-horizontal"  enctype="multipart/form-data" >
                {{ csrf_field() }}
                <div class="modal-body">
                    <div class='row '>
                        <div class='col-md-6'>
                            Type of Document : {{$pa_approval->pre_assessment->type_of_document}}
                        </div>
                        <div class='col-md-6'>
                            Effective Date : {{date('M d Y',strtotime($pa_approval->pre_assessment->effective_date))}}
                        </div>
                        <div class='col-md-6'>
                            @if($pa_approval->pre_assessment->request_type != "Obsolete")
                                Draft Link : <a href='{{$pa_approval->pre_assessment->link_draft}}' target="_blank">Draft Link</a> <br>
                            @endif
                            @if($pa_approval->pre_assessment->original_attachment_pdf != null)
                            Original PDF Link : <a href='{{url($pa_approval->pre_assessment->original_attachment_pdf)}}' target="_blank">Link</a> <br>
                            @endif
                        </div>
                    </div>
                    <hr>
                    <div class='row '>
                        @if($pa_approval->pre_assessment->request_type != "New")
                        <div class='col-md-6'>
                            Control Code : {{$pa_approval->pre_assessment->control_code}} Rev. {{$pa_approval->pre_assessment->revision}}
                        </div>
                        @endif
                        <div class='col-md-6'>
                            Title : {{$pa_approval->pre_assessment->title}}
                        </div>
                    </div>
                    <div class='row '>
                        <div class='col-md-6'>
                            Requested By : {{$pa_approval->pre_assessment->user->name}} 
                        </div>
                        <div class='col-md-6'>
                            Date Requested : {{date('M d, Y',strtotime($pa_approval->pre_assessment->created_at))}}
                        </div>
                    </div>
                    <hr>
                    <div class='row'>
                        <div class='col-md-12 '>
                            <div class="panel panel-primary">
                                <div class="panel-heading">
                                    @if($pa_approval->pre_assessment->request_type != "Revision")
                                        Descriptions/Remarks
                                    @else
                                    Reason/s for Change
                                    @endif
                                </div>
                                <div class="panel-body">
                                    {!!nl2br(e($pa_approval->pre_assessment->description))!!}
                                </div>
                            </div>
                        </div>
                    </div>
                    @if($pa_approval->pre_assessment->request_type == "Revision")
                    <div class='row '>
                        <div class='col-md-6 '>
                            <div class="panel panel-primary">
                                <div class="panel-heading">
                                    From (Indicate clause)
                                </div>
                                <div class="panel-body">
                                    {!! nl2br(e($pa_approval->pre_assessment->indicate_clause)) !!}
                                </div>
                            </div>
                        </div>
                        <div class='col-md-6'>
                            <div class="panel panel-primary">
                                <div class="panel-heading">
                                    To (Indicate the changes)
                                </div>
                                <div class="panel-body">
                                    {!! nl2br(e($pa_approval->pre_assessment->indicate_changes)) !!}
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                    <hr>
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
                            <div class='row'>
                                <div class='col-md-3 border border-primary border-top-bottom border-left-right'>
                                    {{$pa_approval->user->name}}
                                </div>
                                <div class='col-md-3 border border-primary border-top-bottom border-left-right'>
                                    {{$pa_approval->status}}
                                </div>
                                <div class='col-md-2 border border-primary border-top-bottom border-left-right'>
                                    @if($pa_approval->start_date != null){{$pa_approval->start_date}}@endif &nbsp;
                                </div>
                                <div class='col-md-2 border border-primary border-top-bottom border-left-right'>
                                    @if($pa_approval->status != "Waiting" && $pa_approval->status != "Pending")
                                        {{date('Y-m-d',strtotime($pa_approval->updated_at))}}
                                    @else
                                        No action date
                                    @endif
                                </div>
                                <div class='col-md-2 border border-primary border-top-bottom border-left-right'>
                                    {!! nl2br(e($pa_approval->remarks))!!}&nbsp;
                                </div>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class='row'>
                        @if($pa_approval->pre_assessment->soft_copy != null)
                        <div class='col-md-4'>
                            SOFT Copy : <a href='{{url($pa_approval->pre_assessment->soft_copy)}}' target="_blank" ><i class="fa fa-file-word-o"></i> Editable Copy</a>
                        </div>
                        @endif
                        @if($pa_approval->pre_assessment->pdf_copy != null)
                        <div class='col-md-4'>
                            PDF/Scanned Copy : <a href='{{url($pa_approval->pre_assessment->pdf_copy)}}' target="_blank" ><i class="fa fa-file-pdf-o"></i> PDF Copy</a>
                        </div>
                        @endif
                        @if($pa_approval->pre_assessment->fillable_copy != null)
                        <div class='col-md-4'>
                            FILLABLE Copy : <a href='{{url($pa_approval->pre_assessment->fillable_copy)}}' target="_blank" ><i class="fa fa-file-pdf-o"></i> Fillable Copy</a>
                        </div>
                        @endif
                    </div>
                    <hr>
                    @if((auth()->user()->role == "Document Control Officer") && ($request->request_type != "Obsolete"))
                        <div class='row'>
                            <div class='col-md-12'>
                                <i class='text-danger'>Note : No need to protect the PDF/Scanned Copy for all document types except (Forms / Template).</i>
                            </div>
                        </div>
                        <div class='row'>
                            <div class='col-md-4'>
                                SOFT Copy <small><i>(.word,.csv,.ppt,etc)</i></small>
                                <input type="file" class="form-control-sm form-control " id='soft_copy_{{$request->id}}'  name="soft_copy" required/>
                            </div>
                            <div class='col-md-4'>
                                FILLABLE/SCANNED Copy <small><i>(.pdf,excel,word)</i></small>
                                <input type="file" class="form-control-sm form-control " id='pdf_copy_{{$request->id}}'  name="pdf_copy" required/>
                            </div>
                        </div>
                    @endif
                    @if((auth()->user()->role == "Document Control Officer") && ($request->request_type != "Obsolete"))
                    <div class='row'>
                        <div class='col-md-4'>
                            Action :
                            <select name='action' class='form-control-sm form-control cat'  @if((auth()->user()->role == "Document Control Officer") && ($request->request_type != "Obsolete")) onchange='remove_required({{$request->id}},this.value)' @endif required>
                                <option value=""></option>
                                <option value="Approved" >Approve</option>
                                <option value="Declined" >Decline</option>
                                <option value="Returned" >Return</option>
                            </select>
                        </div>
                        <div class='col-md-8'>
                            Remarks :
                            <textarea name='remarks' class='form-control-sm form-control' required></textarea>
                        </div>
                    </div>
                    @endif
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" data-dismiss="modal" type="button">Close</button>
                    @if(auth()->user()->role == "Document Control Officer")
                    <button type='submit'  class="btn btn-primary">Submit</button>
                    @endif
                </div>
            </form>
        </div>
    </div>
</div>
