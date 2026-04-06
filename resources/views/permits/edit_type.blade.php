<div class="modal fade" id="changeType{{$permit->id}}" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-md">
        <div class="modal-content">
            <div class="modal-header border-bottom">
                <h6 class="modal-title fw-semibold mb-2">Change Type</h6>
                <button type="button" class="btn-close mb-2" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="{{ url('permits/change-type/'.$permit->id) }}" onsubmit="show();" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label form-label-sm">Title <span class="text-danger">*</span></label>
                        <input type="text" name="title" value="{{ $permit->title }}" class="form-control form-control-sm" required>
                    </div>
                    <div class="mb-0">
                        <label class="form-label form-label-sm">Type <span class="text-danger">*</span></label>
                        <select name="type" class="form-select form-select-sm" required>
                            <option value="">Select type</option>
                            <option value="License" @if($permit->type == 'License') selected @endif>License</option>
                            <option value="Permit" @if($permit->type == 'Permit') selected @endif>Permit</option>
                            <option value="Certification" @if($permit->type == 'Certification') selected @endif>Certification</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer border-top">
                    <button type="button" class="btn btn-sm btn-secondary mt-3" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-sm btn-primary mt-3">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>