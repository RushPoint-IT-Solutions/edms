<div class="modal fade" id="upload{{$permit->id}}" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-md">
        <div class="modal-content">
            <div class="modal-header border-bottom">
                <h6 class="modal-title fw-semibold mb-2">Upload Permit / License</h6>
                <button type="button" class="btn-close mb-2" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="{{ url('permits/upload/'.$permit->id) }}" onsubmit="show();" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label form-label-sm">File <span class="text-danger">*</span></label>
                        <input type="file" name="file" class="form-control form-control-sm" required>
                    </div>
                    <div class="mb-0">
                        <label class="form-label form-label-sm">Expiration Date</label>
                        <input type="date" name="expiration_date" min="{{ date('Y-m-d') }}" class="form-control form-control-sm">
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