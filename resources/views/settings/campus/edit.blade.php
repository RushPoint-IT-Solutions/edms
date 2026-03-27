<div class="modal fade" id="editCampusModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-bottom">
                <h5 class="modal-title mb-2">Edit Campus</h5>
                <button type="button" class="btn-close mb-2" data-bs-dismiss="modal"></button>
            </div>
            <form id="editCampusForm">
                <input type="hidden" id="edit_campus_id">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Campus Name <span class="text-danger">*</span></label>
                        <input type="text" name="name" id="edit_campus_name" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer border-top">
                    <button type="button" class="btn btn-secondary mt-2" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary mt-2" id="updateCampusBtn">
                        <i class="fa fa-save"></i> Update Campus
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>