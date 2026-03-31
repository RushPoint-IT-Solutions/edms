<div class="modal fade" id="uploadDocument" tabindex="-1" aria-labelledby="uploadDocumentLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">

            <div class="modal-header border-bottom border-2">
                <h5 class="modal-title mb-3">Upload File</h5>
                <button type="button" class="btn-close mb-3" data-bs-dismiss="modal"></button>
            </div>

            <form method="POST" action="{{ url('/documents/upload-document') }}" enctype="multipart/form-data" id="uploadDocumentForm">
                @csrf

                <div class="modal-body">
                    <div class="row g-3">

                        <div class="col-md-8">
                            <label class="form-label fw-semibold">Title *</label>
                            <input type="text" id="titleField" name="title" class="form-control" value="{{ old('title') }}" required>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Choose Folder</label>
                            <select name="folder" class="form-select">
                                <option value="">— No folder —</option>
                                @foreach($upload_folders as $folder)
                                    @if($folder->parent_id == null)
                                        @include("documents.option", ['folder' => $folder, 'level' => 0])
                                    @endif
                                @endforeach
                            </select>
                        </div>

                        <div class="col-12"><hr class="my-1"></div>

                        <div class="col-md-12">
                            <label class="form-label fw-semibold">Files *</label>
                            <input type="file" class="form-control" name="attachments[]" id="fileInput" multiple required>
                            <small class="text-muted">
                                <i class="ri-information-line me-1"></i>
                                All file types are accepted. You can select multiple files at once.
                            </small>
                        </div>

                        <div class="col-md-12" id="filePreviewList" style="display:none;">
                            <label class="form-label fw-semibold">Selected Files</label>
                            <ul class="list-group" id="filePreviewItems"></ul>
                        </div>

                    </div>
                </div>

                <div class="modal-footer border-top border-2">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" id="uploadSubmitBtn">Submit</button>
                </div>

            </form>
        </div>
    </div>
</div>