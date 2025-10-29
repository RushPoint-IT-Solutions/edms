
<div class="modal" id="edit_title{{$request->id}}" tabindex="-1" role="dialog"  >
    <div class="modal-dialog modal-lg " role="document">
        <div class="modal-content">
            <div class="modal-header">
                <div class='col-md-10'>
                    <h5 class="modal-title" id="exampleModalLabel">Change Title</h5>
                </div>
                <div class='col-md-2'>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close" >
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            </div>
            <form method='post' action='edit-title/{{$request->id}}' onsubmit='show();'  enctype="multipart/form-data" >
                <div class="modal-body">
                    {{ csrf_field() }}
                    <div class='row'>
                        <div class='col-md-12'>
                           Title :
                            <input type="text" class="form-control-sm form-control "  value='{{$request->title}}' name="title" required/>
                        </div>
                        {{-- @if($request->request_type == "New") --}}
                            <div class='col-md-12'>
                            Document Type :
                            <select name='document_type' class='form-control-sm form-control cat' required>
                                <option value=""></option>
                                @foreach($document_types as $types)
                                    <option value='{{$types->name}}' @if($request->type_of_document == $types->name) selected @endif>{{$types->code}} - {{$types->name}}</option>
                                @endforeach
                            </select>
                            </div>
                        {{-- @endif --}}
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type='submit'  class="btn btn-primary" >Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>