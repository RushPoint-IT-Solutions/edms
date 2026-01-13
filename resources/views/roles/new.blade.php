<div class="modal fade" id="new" tabindex="-1" aria-labelledby="newModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header p-3">
                <h5 class="modal-title" id="newModalLabel">Add new role</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" id="addFolderBtn-close" aria-label="Close"></button>
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
                <div class="modal-footer">
                    <button class="btn btn-light" type="button" data-bs-dismiss="modal">
                        Close
                    </button>
                    <button class="btn btn-primary" type="submit">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>