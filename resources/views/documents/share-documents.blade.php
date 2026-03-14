<div class="modal" id="share">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title">Share with others</h6>
            </div>
            <form action="{{ url('/documents/share') }}" method="post" onsubmit="show()">
                @csrf

                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-12">
                            <label for="" class="form-label">Users</label>
                            <select name="users[]" class="cat form-control" multiple required>
                                <option value=""></option>
                                @foreach ($users as $user)
                                    <option value="{{ $user->id }}">{{$user->name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-12">
                            <label for="" class="form-label">Documents</label>
                            <select name="documents" class="cat form-control" required>
                                <option value=""></option>
                                @foreach ($documents as $document)
                                <option value="{{ $document->id }}">{{$document->control_code." ".$document->title}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>