
<div class="modal" id="edit_request{{$request->id}}" tabindex="-1" role="dialog"  >
    <div class="modal-dialog modal-lg " role="document">
        <div class="modal-content">
            <div class="modal-header">
                <div class='col-md-10'>
                    <h5 class="modal-title" id="exampleModalLabel">Edit Change Request ({{$request->status}})</h5>
                </div>
                <div class='col-md-2'>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close" >
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            </div>
            <form method='post' action='{{url('change-request-edit/'.$request->id)}}' onsubmit='show();' class="form-horizontal"  enctype="multipart/form-data" >
                <div class="modal-body">
                    {{ csrf_field() }}
                    <div class='row'>
                        <div class='col-md-12' >
                            Reason/s for Change:
                            <textarea name='description' rows="5" cols="100" charswidth="23" class="form-control-sm form-control " required>{{$request->change_request}}</textarea>
                        </div>
                    </div>
                    <div class='row'>
                        <div class='col-md-6' >
                            From (Indicate clause)
                            <textarea placeholder="From (Indicate clause)" rows="8" cols="50" charswidth="23" class="form-control-sm form-control " name='from_clause'>{{$request->indicate_clause}}</textarea>
                        </div>
                        <div class='col-md-6' >
                            To (Indicate the changes)
                            <textarea placeholder="To (Indicate the changes)" rows="8" cols="50" charswidth="23" class="form-control-sm form-control " name='to_changes'>{{$request->indicate_changes}}</textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type='submit'  class="btn btn-primary"  >Submit</button>
                </div>
                
            </form>
        </div>
    </div>
</div>
