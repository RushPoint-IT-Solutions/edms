
<div class="modal" id="obsoleteRequest" tabindex="-1" role="dialog"  >
    <div class="modal-dialog modal-lg " role="document">
        <div class="modal-content">
            <div class="modal-header">
                <div class='col-md-10'>
                    <h5 class="modal-title" id="exampleModalLabel">Obsolete Request</h5>
                </div>
                <div class='col-md-2'>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close" >
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            </div>
            @php
                $submit = 0; 
            @endphp
            <form method='post' action='{{url('change-request')}}' onsubmit='show();' class="form-horizontal"  enctype="multipart/form-data" >
                <div class="modal-body">
                        <input type="hidden" class="form-control-sm form-control " name="id" value='{{$document->id}}'  />
                        <input type="hidden" class="form-control-sm form-control " name="control_code" value='{{$document->control_code}}'  />
                        <input type="hidden" class="form-control-sm form-control " name="title" value='{{$document->title}}'  />
                        <input type="hidden" class="form-control-sm form-control " name="revision" value='{{$document->version}}'  />
                        <input type="hidden" class="form-control-sm form-control " name="request_type" value='Obsolete'  />
                    <div class='row '>
                        {{ csrf_field() }}
                        <div class='col-md-4 border'>
                            Control No.
                        </div>
                        <div class='col-md-4 border border-primary'>
                            Document Title
                        </div>
                        <div class='col-md-4 border border-primary'>
                            Revision No
                        </div>
                        <div class='col-md-4 border border-primary'>
                            <strong>{{$document->control_code}}</strong>
                        </div>
                        <div class='col-md-4 border border-primary'>
                            <strong>{{$document->title}} </strong>
                        </div>
                        <div class='col-md-4 border border-primary'>
                            <strong>{{$document->version}}</strong>
                        </div>
                    </div>
                    <div class='row '>
                        <div class='col-md-12 border'>
                            <hr>
                        </div>
                    </div>
                    <div class='row'>
                        <div class='col-md-4' >
                            Proposed Effective Date :
                            <input type="date" class="form-control-sm form-control " min='{{date('Y-m-d')}}' name="effective_date" required/>
                        </div>
                    </div>
                    <div class='row'>
                        <div class='col-md-12' >
                            Remarks:
                            <textarea name='description' rows="5" cols="100" charswidth="23" class="form-control-sm form-control " required></textarea>
                        </div>
                    </div>
                    <div class='row '>
                        <div class='col-md-12 border'>
                            <hr>
                        </div>
                    </div>
                    <div class='row'>
                        <div class='col-md-12'>
                            <b>Requestor : {{auth()->user()->name}}</b>
                        </div>
                    </div>
                    <hr>
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            Approvers
                        </div>
                        <div class="panel-body">
                            @foreach($document->department->approvers as $approver)
                            
                        
                                        <div class='row'>
                                            <div class='col-md-1 text-right border border-primary border-top-bottom border-left-right'>
                                                {{$approver->level}}
                                            </div>
                                            <div class='col-md-11 border border-primary border-top-bottom border-left-right'>
                                                {{$approver->user->name}}
                                            </div>
                                        </div>
                            
                            @endforeach
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type='submit'  class="btn btn-primary" @if(count($document->department->approvers) == 0) disabled @endif >Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>
