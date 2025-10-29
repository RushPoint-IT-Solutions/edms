<div class="modal" id="edit{{$memo->id}}">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit memorandum</h5>
            </div>
            <form method="POST" action="{{url('update_memorandum/'.$memo->id)}}" enctype="multipart/form-data" onsubmit="show()">
                @csrf 

                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            Memo Number :
                            <input type="text" name="memo_number" class="form-control input-sm" value="{{$memo->memo_number}}" readonly>
                        </div>
                        <div class="col-md-12">
                            Title :
                            <input type="text" name="title" class="form-control input-sm" value="{{$memo->title}}" required>
                        </div>
                        <div class="col-md-12">
                            Released Date :
                            <input type="date" name="released_date" class="form-control input-sm" min="{{date('Y-m-d')}}" value="{{$memo->released_date}}" required>
                        </div>
                        <div class="col-md-12">
                            Align Policy :
                            <select data-placeholder="Choose policy" name="document" class="form-control cat" required>
                                <option value=""></option>
                                @foreach ($documents as $document)
                                    <option value="{{$document->id}}" @if($document->id == $memo->document_id) selected @endif>{{$document->control_code .' - '.$document->title}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-12">
                            Upload Memo :
                            <input type="file" name="memo_file" class="form-control" >
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>