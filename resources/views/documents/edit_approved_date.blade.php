<div class="modal" id="editApprovedDate">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title">Edit approved date</h6>
            </div>
            <form method="POST" action="{{ url('/documents/edit_date_approved/'.$document->id) }}">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <label for="" class="form-label">Date</label>
                            <input type="date" name="date_approved" class="form-control" value="{{ $document->date_approved }}">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>