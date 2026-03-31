<div class="modal fade" id="newTagModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-bottom">
                <h5 class="modal-title mb-2">New Tag</h5>
                <button type="button" class="btn-close mb-2" data-bs-dismiss="modal"></button>
            </div>
            <form id="newTagForm">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Tag Name <span class="text-danger">*</span></label>
                        <input type="text" name="name" id="new_tag_name" class="form-control" placeholder="Enter tag name" required>
                    </div>
                </div>
                <div class="modal-footer border-top">
                    <button type="button" class="btn btn-secondary mt-2" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary mt-2" id="createTagBtn">
                        <i class="fa fa-save"></i> Create Tag
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>