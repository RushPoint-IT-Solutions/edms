<div class="modal fade" id="new_team" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-bottom border-2">
                <h5 class="modal-title mb-3">Create New Team</h5>
                <button type="button" class="btn-close mb-3" data-bs-dismiss="modal"></button>
            </div>
            <form id="newTeamForm">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Department <span class="text-danger">*</span></label>
                        <select name="department" id="new_department_id" class="form-select select2-team" required>
                            <option value="">Select department...</option>
                            @foreach($departments as $department)
                                <option value="{{ $department->id }}">{{ $department->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Office name <span class="text-danger">*</span></label>
                        <input type="text" id="team_name" name="team_name" class="form-control" placeholder="Enter team name" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Campus <span class="text-danger">*</span></label>
                        <select id="campus" name="campus" class="form-select" required>
                            <option value="">Select campus...</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer border-top border-2">
                    <button type="button" class="btn btn-secondary mt-3" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary mt-3" id="createTeamBtn">
                        <i class="fa fa-save"></i> Create Office
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>