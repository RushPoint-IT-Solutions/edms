<div class="modal" id="confirmPassword{{ $change_request->id }}">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Before proceeding with your signature, please confirm your password first.</h5>
            </div>
            <form method="POST" action="{{ url('/change-request/confirm-password') }}" onsubmit="show()">
                @csrf

                <input type="hidden" name="change_request_id" value="{{ $change_request->id }}">

                <div class="modal-body">
                    <div class="row">
                        Password :
                        <input type="password" name="password" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-success">Confirm</button>
                </div>

            </form>
        </div>
    </div>
</div>