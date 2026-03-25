<div class="modal fade" id="addDocumentTypeModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-bottom">
                <h5 class="modal-title mb-3">Add Type of Document</h5>
                <button type="button" class="btn-close mb-3" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ url('documents_type/store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Name <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control" placeholder="Enter document type name" required>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Category <span class="text-danger">*</span></label>
                        <input type="text" name="category" class="form-control" placeholder="Enter category" required>
                    </div>
                </div>

                <div class="modal-footer border-top">
                    <button type="button" class="btn btn-secondary mt-3" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary mt-3">
                        <i class="ri-save-line me-1"></i>Save
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>