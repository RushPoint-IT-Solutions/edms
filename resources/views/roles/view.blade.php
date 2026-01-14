<div class="modal" id="view{{ $role->id }}">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title">Add permissions</h6>
            </div>
            <form method="POST" action="{{ url('/roles/add-permission/'.$role->id) }}">
                @csrf
                <div class="modal-body">
                    @php
                        $rolePermission = $role->permissions->pluck('id')->toArray();
                    @endphp
                    @foreach ($permissions as $permission)
                    <div class="form-check notification-check">
                        <input class="form-check-input" type="checkbox" name="permission[]" value="{{ $permission->name }}" id="{{ $permission->name.$role->id }}" @if(in_array($permission->id, $rolePermission)) checked @endif>
                        <label class="form-check-label" for="{{ $permission->name.$role->id }}">{{ $permission->name }}</label>
                    </div>
                    @endforeach
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>