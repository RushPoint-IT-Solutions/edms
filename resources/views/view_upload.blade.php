
<div class="modal" id="view_upload{{$request->id}}" tabindex="-1" role="dialog"  >
    <div class="modal-dialog modal-lg " role="document">
        <div class="modal-content">
            <div class="modal-header">
                <div class='col-md-10'>
                    <h5 class="modal-title" id="exampleModalLabel">Accomplished Form</h5>
                </div>
                <div class='col-md-2'>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close" >
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            </div>
            
                <div class="modal-body">
                    <div class='row '>
                        <div class='col-md-12'>
                           Attachment : <a href="{{url($request->acknowledgement->file)}}" target='_blank'><i class="fa fa-file-text-o"></i> </a>
                           <a href="#" class="text-danger" data-target="#upload_acknowledgement{{$request->id}}" data-toggle="modal" data-dismiss="modal"><i class="fa fa-edit"></i></a>
                        </div>
                    </div>
                    <div class='row '>
                        <div class='col-md-12'>
                           Date Uploaded : {{date('F d, Y', strtotime($request->acknowledgement->created_at))}} </a>
                        </div>
                    </div>
                    <div class='row '>
                        <div class='col-md-12'>
                          Uploaded By : {{$request->acknowledgement->user->name}} </a>
                        </div>
                    </div>
                  
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
        </div>
    </div>
</div>
@include('upload_acknowledgement')
