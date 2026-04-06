<div class="modal fade" id="new_account" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-bottom">
                <h6 class="modal-title fw-semibold mb-2">New Account</h6>
                <button type="button" class="btn-close mb-2" data-bs-dismiss="modal"></button>
            </div>
            <form id="newAccountForm" action="{{ url('users/new-account') }}">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label form-label-sm">Name <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control form-control-sm" placeholder="Enter full name" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label form-label-sm">Email <span class="text-danger">*</span></label>
                        <input type="email" name="email" class="form-control form-control-sm" placeholder="Enter email address" required>
                    </div>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label form-label-sm">Department <span class="text-danger">*</span></label>
                            <select name="department" class="form-select form-select-sm cat" required>
                                <option value="">Select department...</option>
                                @foreach($departments->where('status', 1) as $dep)
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
                    <button type="submit" class="btn btn-sm btn-primary mt-3" id="createAccountBtn">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>