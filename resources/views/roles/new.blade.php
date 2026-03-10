<div class="modal fade" id="new" tabindex="-1" aria-labelledby="newModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-bottom border-2">
                <h5 class="modal-title mb-3">Add New Role</h5>
                <button type="button" class="btn-close mb-3" data-bs-dismiss="modal"></button>
            </div>
            <form autocomplete="off" method="POST" action="{{ url('/roles/store') }}" onsubmit="show()">
                @csrf

                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-12">
                            <label for="" class="form-label">Name</label>
                            <input type="text" name="name" class="form-control @if($errors->has('name')) is-invalid @endif">
                            @if($errors->has('name'))
                            <span class="invalid-feedback">{{ $errors->first('name') }}</span>
                            @endif
                        </div>
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