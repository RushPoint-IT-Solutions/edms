<div class="modal fade" id="editDepartment{{ $department->id }}" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-bottom border-2">
                <h5 class="modal-title mb-3">Edit Department</h5>
                <button type="button" class="btn-close mb-3" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="{{ url('/departments/update/'.$department->id) }}" onsubmit="show();">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Department Code <span class="text-danger">*</span></label>
                        <input type="text" name="code" value="{{ $department->code }}" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Department Name <span class="text-danger">*</span></label>
                        <input type="text" name="name" value="{{ $department->name }}" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Department Head <span class="text-danger">*</span></label>
                        <select name="user_id" class="form-select select2-dept-head" required>
                            <option value="">Select department head...</option>
                            @foreach($employees as $employee)
                                <option value="{{ $employee->id }}" @if($department->user_id == $employee->id) selected @endif>{{ $employee->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer border-top border-2">
                    <button type="button" class="btn btn-secondary mt-3" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary mt-3">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>