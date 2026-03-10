<div class="modal fade" id="changeType{{$permit->id}}" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header border-bottom border-2">
                <h5 class="modal-title">Change Type</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="{{ url('permits/change-type/'.$permit->id) }}" onsubmit="show();" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Title <span class="text-danger">*</span></label>
                        <input type="text" name="title" value="{{ $permit->title }}" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Type <span class="text-danger">*</span></label>
                        <select name="type" class="form-select" required>
                            <option value="">Select type</option>
                            <option value="License" @if($permit->type == 'License') selected @endif>License</option>
                            <option value="Permit" @if($permit->type == 'Permit') selected @endif>Permit</option>
                            <option value="Certification" @if($permit->type == 'Certification') selected @endif>Certification</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer border-top border-2">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>