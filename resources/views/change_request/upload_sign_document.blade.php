<div class="modal" id="uploadSignDocument{{ $change_request->id }}">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-bottom">
                <h5 class="modal-title mb-2">Upload sign document</h5>
                <button type="button" class="btn-close mb-2" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" id="uploadSignDocumentForm">
                @csrf

                <input type="hidden" name="id" value="{{ $change_request->id }}">

                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-12">
                            <label for="">Attachments</label>
                            <input type="file" name="sign_document" class="form-control">
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="col-md-12">
                            <label for="">Remarks</label>
                            <textarea name="remarks" class="form-control" cols="30" rows="10"></textarea>
                            <div class="invalid-feedback"></div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-top">
                    <button type="button" class="btn btn-sm btn-primary mt-2" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-sm btn-secondary mt-2" id="UploadSignDocsBtn">Upload</button>
                </div>
            </form>
        </div>
    </div>
</div>