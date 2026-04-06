<div class="modal fade" id="editUserModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-bottom">
                <h6 class="modal-title fw-semibold mb-2">Edit User</h6>
                <button type="button" class="btn-close mb-2" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" id="EditUserForm">
                @csrf
                <div class="modal-body">
                    <input type="hidden" name="id">
                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label form-label-sm">Name</label>
                            <input type="text" name="name" class="form-control form-control-sm bg-light" readonly>
                            <small class="text-muted">Cannot be changed</small>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label form-label-sm">Email</label>
                            <input type="email" name="email" class="form-control form-control-sm bg-light" readonly>
                            <small class="text-muted">Cannot be changed</small>
                        </div>
                    </div>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label form-label-sm">Department <span class="text-danger">*</span></label>
                            <select name="department" class="form-select form-select-sm cat" required>
                                <option value="">Select department...</option>
                                @foreach($departments as $dep)
                                    <option value="{{ $dep->id }}">{{ $dep->code }} - {{ $dep->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label form-label-sm">Role <span class="text-danger">*</span></label>
                            <select name="role" class="form-select form-select-sm cat" required>
                                <option value="">Select role...</option>
                                @foreach($roles as $role)
                                    <option value="{{ $role->name }}">{{ $role->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-top">
                    <button type="button" class="btn btn-sm btn-secondary mt-3" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-sm btn-primary mt-3" id="EditUpdate">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>