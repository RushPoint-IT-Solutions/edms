
<div class="modal" id="upload_acknowledgement{{$request->id}}" tabindex="-1" role="dialog"  >
    <div class="modal-dialog modal-xl " role="document">
        <div class="modal-content">
            <div class="modal-header">
                <div class='col-md-10'>
                    <h5 class="modal-title" id="exampleModalLabel">Edit Attachment</h5>
                </div>
                <div class='col-md-2'>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close" >
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            </div>
            
            <form method="post" action="{{url('edit_upload')}}" onsubmit="show()" enctype="multipart/form-data">
                {{csrf_field()}}
                
                <input type="hidden" name="change_request_id" value="{{$request->id}}">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-lg-12">
                            File :
                            <input type="file" name="acknowledgement_file" class="form-control" required>
                        </div>
                    </div>
                
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" >Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>
