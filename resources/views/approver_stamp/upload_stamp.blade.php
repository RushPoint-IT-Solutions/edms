<div class="modal fade" id="upload_stamp" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header border-bottom border-2">
                <h5 class="modal-title mb-3">Upload Stamp</h5>
                <button type="button" class="btn-close mb-3" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="{{ url('approver-stamp/store') }}" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Attachment <span class="text-danger">*</span></label>
                        <input type="file" name="attachment" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer border-top border-2">
                    <button type="button" class="btn btn-secondary mt-3" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary mt-3">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>