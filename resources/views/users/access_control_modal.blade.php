<div class="modal fade" id="accessControlModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header text-black border-bottom">
                <div class="d-flex align-items-center gap-2">
                    <i class="ri-shield-user-line fs-5"></i>
                    <div>
                        <h5 class="modal-title mb-0" id="acModalTitle">Access Control</h5>
                        <small class="text-black-50" id="acModalRole"></small>
                    </div>
                </div>
                <button type="button" class="btn-close btn-close-black" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body p-3" id="acModalBody">
                <div class="text-center py-5">
                    <div class="spinner-border text-secondary" role="status"></div>
                    <p class="text-muted mt-2">Loading permissions...</p>
                </div>
            </div>

            <div class="modal-footer bg-light">
                <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">
                    Close
                </button>
                <button type="button" class="btn btn-sm btn-primary" id="acSaveBtn">
                    <i class="ri-save-line me-1"></i> Save Access
                </button>
            </div>
        </div>
    </div>
</div>