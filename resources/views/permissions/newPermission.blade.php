<div class="modal fade" id="newPermission" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-md">
        <div class="modal-content">
            <div class="modal-header border-bottom">
                <h6 class="modal-title fw-semibold mb-2">Add Permission</h6>
                <button type="button" class="btn-close mb-2" data-bs-dismiss="modal"></button>
            </div>
            <form autocomplete="off" method="POST" action="{{ url('/permission/store') }}" onsubmit="show()">
                @csrf
                <div class="modal-body">
                    <label class="form-label form-label-sm">Name</label>
                    <input type="text" name="name" class="form-control form-control-sm @if($errors->has('name')) is-invalid @endif">
                    @if($errors->has('name'))
                        <span class="invalid-feedback">{{ $errors->first('name') }}</span>
                    @endif
                </div>
                <div class="modal-footer border-top">
                    <button type="button" class="btn btn-sm btn-secondary mt-3" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-sm btn-primary mt-3">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>