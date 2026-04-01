<div class="modal" id="uploadSignDocument{{ $change_request->id }}">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Upload sign document</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
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
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" id="UploadSignDocsBtn">Upload</button>
                </div>
            </form>
        </div>
    </div>
</div>