<div class="modal fade" id="EditModal" tabindex="-1" aria-labelledby="newModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-bottom border-2">
                <h5 class="modal-title mb-3">Edit Role</h5>
                <button type="button" class="btn-close mb-3" data-bs-dismiss="modal"></button>
            </div>
            <form autocomplete="off" method="POST" id="EditRoleForm">
                @csrf

                <input type="hidden" name="id" id="id">

                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-12">
                            <label for="" class="form-label">Name</label>
                            <input type="text" name="name" id="EditName" class="form-control">
                            <div class="invalid-feedback"></div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-top border-2">
                    <button type="button" class="btn btn-secondary mt-3" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary mt-3" id="UpdateRoleBtn">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>