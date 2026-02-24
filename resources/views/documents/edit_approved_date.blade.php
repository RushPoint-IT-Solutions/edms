<div class="modal" id="editApprovedDate">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title">Date</h6>
            </div>
            <form method="POST" action="{{ url('/documents/edit_date_approved/'.$approver->id) }}" onsubmit="show()">
                @csrf

                <input type="hidden" name="document_id" value="{{ $change_request->document_id }}">

                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <b>Approver: </b>
                            {{ $approver->user->name }}
                        </div>
                        <div class="col-md-12">
                            <b>Level: </b>
                            {{ $approver->level }}
                        </div>
                        <div class="col-md-12">
                            <b>Start Date: </b>
                            {{ date('M d Y', strtotime($approver->start_date)) }}
                        </div>
                        <div class="col-md-12">
                            <b>Approved Date: </b>
                            @if($approver->date_approved)
                            {{ date('M d Y h:i A', strtotime($approver->date_approved)) }}
                            @else 
                            {{ date('M d Y h:i A', strtotime($approver->updated_at)) }}
                            @endif
                        </div>
                    </div>
                    <hr>
                    @php
                        $dateLogs = $dateLogs->sortByDesc('id')->first();
                    @endphp
                    @if($dateLogs)
                    <div class="row">
                        <div class="col-md-12">
                            <b>Edited by:</b>
                            {{ $dateLogs->user->name }}
                        </div>
                    </div>
                    <hr>
                    @endif
                    <div class="row">
                        <div class="col-md-12">
                            <label for="" class="form-label">Date</label>
                            <input type="date" name="date_approved" class="form-control">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>