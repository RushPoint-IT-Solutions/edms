<div class="modal" id="requestAccess{{ $private_document->id }}">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-bottom">
                <h5 class="modal-title mb-2">
                    <i class="ri ri-lock-line fs-7 text-muted me-2"></i>
                    Request access
                </h5>
                <button type="button" class="btn-close mb-2" data-bs-dismiss="modal"></button>
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
                <div class="modal-footer border-top">
                    <button type="button" class="btn btn-secondary mt-2" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary mt-2">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>