<div class="modal fade" id="view_request{{$request->id}}" tabindex="-1" aria-labelledby="viewRequestLabel{{$request->id}}" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-light border-bottom">
                <div>
                    <h5 class="modal-title fw-bold" id="viewRequestLabel{{$request->id}}">
                        <i class="ri-file-copy-line me-2"></i>Copy Request Details
                    </h5>
                    <small class="text-muted">
                        Status: 
                        @if($request->status == "Approved")
                            <span class="badge bg-success">{{$request->status}}</span>
                        @elseif($request->status == "Pending")
                            <span class="badge bg-warning">{{$request->status}}</span>
                        @elseif($request->status == "Rejected")
                            <span class="badge bg-danger">{{$request->status}}</span>
                        @else
                            <span class="badge bg-secondary">{{$request->status}}</span>
                        @endif
                    </small>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body p-4">
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-primary bg-opacity-10 border-0">
                        <h6 class="mb-0 fw-semibold text-primary">
                            <i class="ri-information-line me-2"></i>Request Information
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="text-muted small">Reference Number</label>
                                <div class="fw-bold">CR-{{str_pad($request->id, 5, '0', STR_PAD_LEFT)}}</div>
                            </div>
                            <div class="col-md-6">
                                <label class="text-muted small">Type of Document</label>
                                <div class="fw-semibold">{{$request->type_of_document}}</div>
                            </div>
                            <div class="col-md-6">
                                <label class="text-muted small">Date Needed</label>
                                <div class="fw-semibold">
                                    <i class="ri-calendar-line me-1 text-primary"></i>
                                    {{date('M d, Y', strtotime($request->date_needed))}}
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="text-muted small">Copies Needed</label>
                                <div class="fw-semibold">
                                    <i class="ri-file-copy-2-line me-1 text-primary"></i>
                                    {{$request->copy_count}}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-info bg-opacity-10 border-0">
                        <h6 class="mb-0 fw-semibold text-info">
                            <i class="ri-file-text-line me-2"></i>Document Details
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="text-muted small">Control Code</label>
                                <div class="fw-semibold">{{$request->control_code}} <span class="badge bg-secondary">Rev. {{$request->revision}}</span></div>
                            </div>
                            <div class="col-md-6">
                                <label class="text-muted small">Title</label>
                                <div class="fw-semibold">{{$request->title}}</div>
                            </div>
                            <div class="col-md-6">
                                <label class="text-muted small">Requested By</label>
                                <div class="fw-semibold">
                                    <i class="ri-user-line me-1 text-info"></i>
                                    {{$request->user->name}}
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="text-muted small">Date Requested</label>
                                <div class="fw-semibold">
                                    <i class="ri-calendar-check-line me-1 text-info"></i>
                                    {{date('M d, Y', strtotime($request->created_at))}}
                                </div>
                            </div>
                            <div class="col-md-12">
                                <label class="text-muted small">Purpose</label>
                                <div class="fw-semibold">{{$request->purpose}}</div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-warning bg-opacity-10 border-0">
                        <h6 class="mb-0 fw-semibold text-warning">
                            <i class="ri-user-follow-line me-2"></i>Approval Timeline
                        </h6>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th class="fw-semibold">Approver</th>
                                        <th class="fw-semibold">Status</th>
                                        <th class="fw-semibold">Start Date</th>
                                        <th class="fw-semibold">Action Date</th>
                                        <th class="fw-semibold">Remarks</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($request->approvers as $approver)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="avatar-xs me-2">
                                                    <div class="avatar-title rounded-circle bg-soft-primary text-primary">
                                                        {{substr($approver->user->name, 0, 1)}}
                                                    </div>
                                                </div>
                                                <span class="fw-semibold">{{$approver->user->name}}</span>
                                            </div>
                                        </td>
                                        <td>
                                            @if($approver->status == "Approved")
                                                <span class="badge bg-success">{{$approver->status}}</span>
                                            @elseif($approver->status == "Pending")
                                                <span class="badge bg-warning">{{$approver->status}}</span>
                                            @elseif($approver->status == "Waiting")
                                                <span class="badge bg-info">{{$approver->status}}</span>
                                            @elseif($approver->status == "Rejected")
                                                <span class="badge bg-danger">{{$approver->status}}</span>
                                            @else
                                                <span class="badge bg-secondary">{{$approver->status}}</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($approver->start_date != null)
                                                <small>{{date('M d, Y', strtotime($approver->start_date))}}</small>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($approver->status != "Waiting" && $approver->status != "Pending")
                                                <small>{{date('M d, Y', strtotime($approver->updated_at))}}</small>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($approver->remarks)
                                                <small class="text-muted">{!! nl2br(e($approver->remarks)) !!}</small>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                @if($request->status == "Approved")
                    @if(strtotime($request->expiration_date) >= strtotime(date('Y-m-d')))
                        @if($request->type_of_document == "Hard Copy")
                            <div class="alert alert-info border-0 shadow-sm" role="alert">
                                <div class="d-flex align-items-start">
                                    <div class="flex-shrink-0">
                                        <i class="ri-printer-line fs-3 text-info"></i>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <h6 class="alert-heading fw-bold">Hard Copy Request</h6>
                                        <p class="mb-0">Please get your hard copy from DRC.</p>
                                        <small class="text-muted">
                                            <i class="ri-time-line me-1"></i>
                                            This request will expire on {{date("M d, Y", strtotime($request->expiration_date))}}
                                        </small>
                                    </div>
                                </div>
                            </div>
                        @else
                            @if($request->document_access)
                                <div class="card border-0 shadow-sm">
                                    <div class="card-header bg-success bg-opacity-10 border-0 d-flex justify-content-between align-items-center">
                                        <div>
                                            <h6 class="mb-0 fw-semibold text-success">
                                                <i class="ri-file-download-line me-2"></i>E-Copy Access
                                            </h6>
                                            <small class="text-muted">
                                                <i class="ri-time-line me-1"></i>
                                                Expires: {{date("M d, Y", strtotime($request->expiration_date))}}
                                            </small>
                                        </div>
                                        @if($request->document_access->attachment != null)
                                            @if(($request->document->category == "FORM") || ($request->document->category == "TEMPLATE"))
                                                <a href="{{url($request->document_access->attachment->attachment)}}" target="_blank" class="btn btn-success btn-sm">
                                                    <i class="ri-download-2-line me-1"></i>Download
                                                </a>
                                            @else
                                                <a href="{{url('view-pdf/'.$request->document_access->attachment_id)}}" target="_blank" class="btn btn-success btn-sm">
                                                    <i class="ri-download-2-line me-1"></i>Download
                                                </a>
                                            @endif
                                        @endif
                                    </div>
                                    <div class="card-body p-0">
                                        @if($request->document_access->attachment != null)
                                            <div class="ratio ratio-16x9" style="max-height: 600px;">
                                                @if(($request->document->category == "FORM") || ($request->document->category == "TEMPLATE"))
                                                    <iframe src="{{url($request->document_access->attachment->attachment)}}" 
                                                        title="Document Preview" class="border-0"></iframe>
                                                @else
                                                    <iframe src="{{url('view-pdf/'.$request->document_access->attachment_id.'?page=hsn#toolbar=0')}}" 
                                                        title="Document Preview" class="border-0"></iframe>
                                                @endif
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @endif
                        @endif
                    @else
                        <div class="alert alert-danger border-0 shadow-sm" role="alert">
                            <div class="d-flex align-items-start">
                                <div class="flex-shrink-0">
                                    <i class="ri-error-warning-line fs-3 text-danger"></i>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h6 class="alert-heading fw-bold">Expired Request</h6>
                                    <p class="mb-0">Your request or access to this request has expired.</p>
                                    <small class="text-muted">
                                        <i class="ri-calendar-close-line me-1"></i>
                                        Expiration Date: {{date("M d, Y", strtotime($request->expiration_date))}}
                                    </small>
                                </div>
                            </div>
                        </div>
                    @endif
                @endif
            </div>

            <div class="modal-footer bg-light border-top">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="ri-close-line me-1"></i>Close
                </button>
            </div>
        </div>
    </div>
</div>

<style>
.avatar-xs {
    height: 2rem;
    width: 2rem;
}

.avatar-title {
    align-items: center;
    display: flex;
    height: 100%;
    justify-content: center;
    width: 100%;
    font-weight: 600;
    font-size: 0.875rem;
}

.bg-soft-primary {
    background-color: rgba(13, 110, 253, 0.1);
}

.table > :not(caption) > * > * {
    padding: 0.75rem;
}
</style>