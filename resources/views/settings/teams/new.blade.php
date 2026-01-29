<div class="modal fade" id="new_team" tabindex="-1" aria-labelledby="newTeamLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header" style="border-bottom: 1px solid #000000;">
                <h5 class="modal-title" id="newTeamLabel">
                    <i class="fa fa-plus me-2"></i>Create New Team
                </h5>
                <button type="button" class="btn-close btn-close-black" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="newTeamForm">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="team_name" class="form-label">Team Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="team_name" name="team_name" placeholder="Enter team name" required>
                        <div class="invalid-feedback" id="team_name_error"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fa fa-times me-1"></i>Cancel
                    </button>
                    <button type="submit" class="btn btn-primary" id="createTeamBtn">
                        <i class="fa fa-save"></i> Create Team
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>