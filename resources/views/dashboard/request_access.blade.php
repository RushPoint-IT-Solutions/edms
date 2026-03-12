<div class="modal" id="requestAccess{{ $private_document->id }}">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title">Request access</h6>
            </div>
            <form action="{{ url("request_access/".$private_document->id) }}" method="post" onsubmit="show()">
                @csrf

                <input type="hidden" name="user_id" value="{{ $private_document->user_id }}">

                <div class="modal-body">
                    <div class="row g-2">
                        <div class="col-md-12">
                            <label for="" class="form-label">Reason <span class="text-danger">*</span></label>
                            <textarea name="reason" class="form-control" cols="30" rows="10" placeholder="Write a reason" required></textarea>
                        </div>
                        <div class="col-md-12">
                            <label for="" class="form-label">Until when? <small>(optional)</small></label>
                            <input type="date" name="date" min="{{ date("Y-m-d") }}" class="form-control">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-success">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>