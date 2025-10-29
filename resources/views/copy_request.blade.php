
<div class="modal" id="copyRequest" tabindex="-1" role="dialog"  >
    <div class="modal-dialog modal-lg " role="document">
        <div class="modal-content">
            <div class="modal-header">
                <div class='col-md-10'>
                    <h5 class="modal-title" id="exampleModalLabel">Copy Request</h5>
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
            <form method='post' action='{{url('copy-request')}}' onsubmit='show();' class="form-horizontal"  enctype="multipart/form-data" >
                <div class="modal-body">
                        <input type="hidden" class="form-control-sm form-control " name="id" value='{{$document->id}}'  />
                        <input type="hidden" class="form-control-sm form-control " name="control_code" value='{{$document->control_code}}'  />
                        <input type="hidden" class="form-control-sm form-control " name="title" value='{{$document->title}}'  />
                        <input type="hidden" class="form-control-sm form-control " name="revision" value='{{$document->version}}'  />
                    @if(count($document->department->dco) > 0)
                        @foreach ($document->department->dco as $dco)
                            <input type="hidden" name="dco[]" value="{{$dco->user_id}}">
                        @endforeach
                    @endif
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
                        <div class='col-md-4'>
                            Type of Document :
                            <input type="text" class="form-control-sm form-control " name="type_of_document" value='E-Copy' readonly required/>
                            {{-- <select name='type_of_document' onchange='select_type(this.value);' class='form-control-sm form-control cat' required>
                                <option value=""></option>
                                <option value="Hard Copy" >Hard Copy</option>
                                <option value="E-Copy" >E-Copy</option>
                            </select> --}}
                        </div>
                        <div class='col-md-4' >
                            Number of Copy :
                            <input type="text" class="form-control-sm form-control " id='number_copy' name="name" value='1' readonly required/>
                        </div>
                        <div class='col-md-4' >
                            Date Needed :
                            <input type="date" class="form-control-sm form-control " min='{{date('Y-m-d')}}' name="date_needed" required/>
                        </div>
                    </div>
                    <div class='row'>
                        <div class='col-md-12'>
                            Purpose :
                            <textarea name='purpose'  rows="5" cols="100" charswidth="23" class="form-control-sm form-control " required></textarea>
                            
                        </div>
                    </div>
                    <div class='row '>
                        <div class='col-md-12 border'>
                            <hr>
                        </div>
                    </div>
                    <div class='row'>
                        <div class='col-md-6'>
                            Requestor : {{auth()->user()->name}}
                        </div>
                        <div class='col-md-6'>
                            Immediate Head : 
                            @if(auth()->user()->department->dep_head != null)
                                {{auth()->user()->department->dep_head->name}}
                            <input type='hidden' name='immediate_head' value='{{auth()->user()->department->user_id}}'>
                            @else 
                                @php $submit = 1; 
                                @endphp No Head
                                 <br> <span class='text-danger'>Please contact Administrator to update your Immediate Head</span> 
                            @endif
                        </div>
                        <div class='col-md-6'>
                            Document Department Head: 
                            @if($document->department->dep_head != null)
                                {{ $document->department->dep_head->name }}
                                <input type='hidden' name='immediate_head_document' value='{{ $document->department->dep_head->id }}'>
                            @else 
                                @php $submit = 1; @endphp
                                No Head
                                <br>
                                <span class='text-danger'>Please contact Administrator to update the Immediate Head of the document's department.</span>
                            @endif
                        </div>
                    </div>
                    {{-- <div class='row'>
                        <div class='col-md-6'>
                            DRC : 
                            @if(count($document->department->drc) != 0) 
                                @foreach($document->department->drc as $drc) 
                                     {{$drc->name}}
                                     <input type='hidden' name='drc' value='{{$drc->id}}'>
                                     @break;
                                @endforeach
                          
                            @else 
                                @php $submit = 1; 
                                @endphp
                                <small class="label label-danger">No Process Owner</small>
                            @endif
                        </div>
                        <div class='col-md-6'>
                            DRC Immediate Head : 
                                @if(count($document->department->drc) != 0) 
                                    @foreach($document->department->drc as $drc)
                                        {{$drc->department->dep_head->name}}
                                        <input type='hidden' name='drc_head' value='{{$drc->department->user_id}}'>
                                        @break;
                                    @endforeach
                                    
                            @else 
                                @php $submit = 1; 
                                @endphp
                                <small class="label label-danger">No Process Owner</small>
                            @endif
                        </div>
                    </div> --}}
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type='submit'  class="btn btn-primary" @if($submit==1) disabled @endif >Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
    function select_type(value)
    {
        if(value == "Hard Copy")
        {
            document.getElementById("number_copy").readOnly = false;
            document.getElementById("number_copy").value = "1";
        }
        else
        {
            document.getElementById("number_copy").readOnly = true;
            document.getElementById("number_copy").value = "1";
        }
    }
</script>
