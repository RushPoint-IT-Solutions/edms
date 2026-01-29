@foreach($teams as $team)
<div class="modal fade" id="editTeam{{ $team->id }}" tabindex="-1" aria-labelledby="editTeamLabel{{ $team->id }}" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header" style="border-bottom: 1px solid #000000;">
                <h5 class="modal-title" id="editTeamLabel{{ $team->id }}">
                    <i class="ri-pencil-line me-2"></i>Edit Team
                </h5>
                <button type="button" class="btn-close btn-close-black" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editTeamForm{{ $team->id }}" class="edit-team-form" data-id="{{ $team->id }}">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="edit_team_name{{ $team->id }}" class="form-label">Team Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control edit-team-name" id="edit_team_name{{ $team->id }}" name="team_name" value="{{ $team->name }}" required>
                        <div class="invalid-feedback edit-team-error"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fa fa-times me-1"></i>Cancel
                    </button>
                    <button type="submit" class="btn btn-primary update-team-btn">
                        <i class="fa fa-save"></i>Update Team
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endforeach