<div class="modal fade" id="editTeam{{ $team->id }}" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-bottom border-2">
                <h5 class="modal-title">Edit Team</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form class="edit-team-form" data-id="{{ $team->id }}">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Department <span class="text-danger">*</span></label>
                        <select name="department" class="form-select select2-team" required>
                            <option value="">Select department...</option>
                            @foreach($departments as $department)
                                <option value="{{ $department->id }}" @if($department->id == $team->department_id) selected @endif>{{ $department->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Team Name <span class="text-danger">*</span></label>
                        <input type="text" name="team_name" class="form-control" value="{{ $team->name }}" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Campus <span class="text-danger">*</span></label>
                        <select name="campus" class="form-select edit-campus-select" 
                            data-current="{{ $team->campus }}" required>
                            <option value="">Select campus...</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer border-top border-2">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary update-team-btn">
                        <i class="fa fa-save"></i> Update Team
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>