<div class="modal fade" id="new_control_code" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header border-bottom">
                <h5 class="modal-title mb-3">
                    New Control Code
                </h5>
                <button type="button" class="btn-close mb-3" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('control-codes.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Code <span class="text-danger">*</span></label>
                        <input type="text" name="code" class="form-control" placeholder="e.g. QA-001" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <input type="text" name="description" class="form-control" placeholder="Optional description">
                    </div>
                </div>
                <div class="modal-footer border-top">
                    <button type="button" class="btn btn-secondary mt-3" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary mt-3"><i class="fa fa-save me-1"></i> Save</button>
                </div>
            </form>
        </div>
    </div>
</div>