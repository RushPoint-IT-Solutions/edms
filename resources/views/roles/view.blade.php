<div class="modal fade" id="view{{ $role->id }}" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header border-bottom border-2">
                <h5 class="modal-title">Permissions — {{ $role->name }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="{{ url('/roles/add-permission/'.$role->id) }}" onsubmit="show();">
                @csrf
                <div class="modal-body">
                    @php $rolePermission = $role->permissions->pluck('id')->toArray(); @endphp
                    <div class="row">
                        @foreach($permissions as $permission)
                        <div class="col-md-4">
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" name="permission[]" value="{{ $permission->name }}" id="{{ $permission->name.$role->id }}" @if(in_array($permission->id, $rolePermission)) checked @endif>
                                <label class="form-check-label" for="{{ $permission->name.$role->id }}">{{ $permission->name }}</label>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                <div class="modal-footer border-top border-2">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>