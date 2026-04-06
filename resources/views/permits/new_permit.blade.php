<div class="modal fade" id="new_permit" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-bottoms">
                <h6 class="modal-title fw-semibold mb-2">New Permit / License</h6>
                <button type="button" class="btn-close mb-2" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" id="AddPermitForm">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label form-label-sm">Title <span class="text-danger">*</span></label>
                        <input type="text" name="title" class="form-control form-control-sm">
                        <div class="invalid-feedback"></div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label form-label-sm">Description <span class="text-danger">*</span></label>
                        <textarea name="description" class="form-control form-control-sm" rows="3"></textarea>
                        <div class="invalid-feedback"></div>
                    </div>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label form-label-sm">Type <span class="text-danger">*</span></label>
                            <select name="type" class="form-select form-select-sm">
                                <option value="">Select type</option>
                                <option value="License">License</option>
                                <option value="Permit">Permit</option>
                                <option value="Certification">Certification</option>
                            </select>
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label form-label-sm">Expiration Date <span class="text-danger">*</span></label>
                            <input type="date" name="expiration_date" min="{{ date('Y-m-d') }}" class="form-control form-control-sm">
                            <div class="invalid-feedback"></div>
                        </div>
                    </div>
                    <div class="mt-3">
                        <label class="form-label form-label-sm">File <span class="text-danger">*</span></label>
                        <input type="file" name="file" class="form-control form-control-sm">
                        <div class="invalid-feedback"></div>
                    </div>
                </div>
                <div class="modal-footer border-top">
                    <button type="button" class="btn btn-sm btn-secondary mt-3" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-sm btn-primary mt-3" id="AddPermitBtn">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>