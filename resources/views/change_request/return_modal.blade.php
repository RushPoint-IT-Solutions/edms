<div class="modal" id="return{{ $change_request->id }}">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Are you sure you want to return?</h5>
            </div>
            <form method="POST" action="{{ url('change-request/change-request-action/'.$change_request->id) }}">
                @csrf

                @php
                    $status = ($change_request->approvers)->where('user_id', auth()->user()->id)->first();
                @endphp
                
                <input type="hidden" name="action" value="Returned">
                <input type="hidden" name="old_status" value="{{ $status->status }}">
                
                <div class="modal-body">
                    <div class="row">
                        Remarks :
                        <div class="col-md-12">
                            <textarea name="remarks" class="form-control" cols="30" rows="10" placeholder="Write your remarks"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Dismiss</button>
                    <button type="submit" class="btn btn-success">Return</button>
                </div>

            </form>
        </div>
    </div>
</div>