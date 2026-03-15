<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document Details</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/remixicon@3.5.0/fonts/remixicon.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            min-height: 100vh;
            padding: 40px 20px;
        }
        .document-container {
            max-width: 900px;
            margin: 0 auto;
        }
        .card {
            border: none;
            border-radius: 16px;
        }
        .info-box {
            background: #f8f9fc;
            padding: 20px;
            border-radius: 12px;
            transition: all 0.3s ease;
        }
        .info-box:hover {
            background: #ffffff;
            box-shadow: 0 4px 12px rgba(0,0,0,0.08);
            transform: translateY(-2px);
        }
        .hover-effect:hover {
            background: #f8f9fc;
            transform: translateX(5px);
        }
        .hover-effect {
            transition: all 0.3s ease;
        }
        .approver-card {
            border: 1px solid #e9ecef;
            border-radius: 8px;
            padding: 12px 16px;
            margin-bottom: 12px;
        }
        .approver-card:last-child {
            margin-bottom: 0;
        }
    </style>
</head>
<body>
    <div class="document-container">
        <div class="mb-4">
            <h4 class="fs-2 fw-semibold mb-1">Document Details</h4>
            <p class="text-muted">Document change request information</p>
        </div>

        <div class="card shadow-sm">
            <div class="card-body p-4">
                <div class="mb-4">
                    <h5 class="fw-semibold text-dark mb-2">{{ $change_request->title }}</h5>
                    @php
                        $code = "DOC-".date('Y', strtotime($change_request->created_at)).'-'.str_pad($change_request->id,3,'0',STR_PAD_LEFT);
                    @endphp
                    <span class="badge bg-primary">{{ $code }}</span>
                </div>

                <hr>

                <div class="row g-4">
                    <div class="col-12 col-md-6">
                        <div class="d-flex align-items-start gap-3">
                            <div class="text-primary" style="font-size: 1.5rem;">
                                <i class="ri-checkbox-circle-line"></i>
                            </div>
                            <div>
                                <div class="text-muted small mb-1">STATUS</div>
                                @php
                                    $currentApprover = $change_request->approvers->sortBy('level')->firstWhere('status', 'Pending');
                                @endphp

                                @if($change_request->status == 'Approved')
                                    <span class="badge bg-success">Approved</span>
                                @elseif($change_request->status == 'Returned')
                                    <span class="badge bg-warning text-dark">Returned</span>
                                @elseif($change_request->status == 'Declined')
                                    <span class="badge bg-danger">Declined</span>
                                @elseif($currentApprover)
                                    <span class="badge bg-primary">For Approval</span>
                                    <div class="text-muted mt-1" style="font-size: 0.75rem;">
                                        Pending with: {{ $currentApprover->user->name }}
                                    </div>
                                @else
                                    <span class="badge bg-secondary">{{ $change_request->status }}</span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="col-12 col-md-6">
                        <div class="d-flex align-items-start gap-3">
                            <div class="text-primary" style="font-size: 1.5rem;">
                                <i class="ri-user-line"></i>
                            </div>
                            <div>
                                <div class="text-muted small mb-1">CREATED BY</div>
                                <div class="fw-medium">{{ $change_request->user->name }}</div>
                            </div>
                        </div>
                    </div>

                    <div class="col-12 col-md-6">
                        <div class="d-flex align-items-start gap-3">
                            <div class="text-primary" style="font-size: 1.5rem;">
                                <i class="ri-folder-line"></i>
                            </div>
                            <div>
                                <div class="text-muted small mb-1">CATEGORY</div>
                                <div class="fw-medium">{{ $change_request->category }}</div>
                            </div>
                        </div>
                    </div>

                    <div class="col-12 col-md-6">
                        <div class="d-flex align-items-start gap-3">
                            <div class="text-primary" style="font-size: 1.5rem;">
                                <i class="ri-calendar-line"></i>
                            </div>
                            <div>
                                <div class="text-muted small mb-1">CREATED DATE</div>
                                <div class="fw-medium">{{ date('F d, Y', strtotime($change_request->created_at)) }}</div>
                            </div>
                        </div>
                    </div>

                    <div class="col-12 col-md-6">
                        <div class="d-flex align-items-start gap-3">
                            <div class="text-primary" style="font-size: 1.5rem;">
                                <i class="ri-time-line"></i>
                            </div>
                            <div>
                                <div class="text-muted small mb-1">LAST UPDATED</div>
                                <div class="fw-medium">{{ date('F d, Y', strtotime($change_request->updated_at)) }}</div>
                            </div>
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="d-flex align-items-start gap-3">
                            <div class="text-primary" style="font-size: 1.5rem;">
                                <i class="ri-file-list-line"></i>
                            </div>
                            <div>
                                <div class="text-muted small mb-1">DESCRIPTION</div>
                                <div class="fw-medium">{{ $change_request->description ?? 'No description provided' }}</div>
                            </div>
                        </div>
                    </div>

                    @if($change_request->file)
                        <div class="col-12">
                            <div class="d-flex align-items-start gap-3">
                                <div class="text-primary" style="font-size: 1.5rem;">
                                    <i class="ri-attachment-line"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <div class="text-muted small mb-2">ATTACHMENT</div>

                                    <a href="{{ url($change_request->file) }}" target="_blank"
                                    class="text-decoration-none d-flex align-items-center gap-2 p-3 border rounded hover-effect">
                                        <i class="ri-file-pdf-line text-danger" style="font-size: 1.25rem;"></i>
                                        <span class="text-dark fw-medium" style="font-size: 0.875rem;">{{ $filename }}</span>

                                        @if($signedApprovers === 0)
                                            <span class="badge bg-secondary ms-auto">
                                                <i class="ri-edit-line me-1"></i>Unsigned
                                            </span>
                                        @elseif($signedApprovers < $totalApprovers)
                                            <span class="badge bg-warning text-dark ms-auto">
                                                <i class="ri-quill-pen-line me-1"></i>Partially Signed ({{ $signedApprovers }}/{{ $totalApprovers }})
                                            </span>
                                        @else
                                            <span class="badge bg-success ms-auto">
                                                <i class="ri-checkbox-circle-line me-1"></i>Fully Signed
                                            </span>
                                        @endif
                                    </a>

                                    <div class="mt-2 ps-1">
                                        @foreach($change_request->approvers->sortBy('level') as $approver)
                                            <div class="d-flex align-items-center gap-2 py-1" style="font-size: 0.8rem;">
                                                @if($approver->status === 'Approved')
                                                    <i class="ri-checkbox-circle-fill text-success"></i>
                                                    <span class="text-dark fw-medium">{{ $approver->user->name }}</span>
                                                    <span class="text-muted">— Signed on {{ \Carbon\Carbon::parse($approver->updated_at)->format('M d, Y h:i A') }}</span>
                                                @else
                                                    <i class="ri-checkbox-blank-circle-line text-muted"></i>
                                                    <span class="text-muted">{{ $approver->user->name }} — Not yet signed</span>
                                                @endif
                                            </div>
                                        @endforeach
                                    </div>

                                </div>
                            </div>
                        </div>
                        @endif

                    @if($change_request->supporting_documents && count($change_request->supporting_documents) > 0)
                    <div class="col-12">
                        <div class="d-flex align-items-start gap-3">
                            <div class="text-primary" style="font-size: 1.5rem;">
                                <i class="ri-folder-2-line"></i>
                            </div>
                            <div class="flex-grow-1">
                                <div class="text-muted small mb-2">SUPPORTING DOCUMENTS</div>
                                @foreach($change_request->supporting_documents as $doc)
                                <a href="{{ url($doc->file) }}" target="_blank" class="text-decoration-none d-flex align-items-center gap-2 p-2 border rounded hover-effect mb-2">
                                    <i class="ri-file-line text-primary" style="font-size: 1rem;"></i>
                                    <span class="text-dark" style="font-size: 0.875rem;">{{ basename($doc->file) }}</span>
                                </a>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    @endif
                </div>

                @if($change_request->approvers && count($change_request->approvers) > 0)
                <hr class="my-4">

                <div class="mb-3">
                    <div class="d-flex align-items-center gap-2 mb-3">
                        <i class="ri-shield-check-line text-success" style="font-size: 1.5rem;"></i>
                        <h6 class="fw-semibold text-dark mb-0">Approval Details</h6>
                    </div>

                   @foreach($change_request->approvers->sortBy('level') as $approver)
                    <div class="approver-card">
                        <div class="d-flex align-items-center justify-content-between">
                            <div class="flex-grow-1">
                                <div class="d-flex align-items-center gap-2 mb-1">
                                    <div class="fw-semibold text-dark" style="font-size: 0.9rem;">{{ $approver->user->name }}</div>
                                    @if($approver->status == 'Approved')
                                        <span class="badge bg-success" style="font-size: 0.7rem;">Approved</span>
                                    @elseif($approver->status == 'Pending')
                                        <span class="badge bg-primary" style="font-size: 0.7rem;">For Approval</span>
                                    @elseif($approver->status == 'Waiting')
                                        <span class="badge bg-secondary" style="font-size: 0.7rem;">Waiting</span>
                                    @elseif($approver->status == 'Returned')
                                        <span class="badge bg-warning text-dark" style="font-size: 0.7rem;">Returned</span>
                                    @elseif($approver->status == 'Declined')
                                        <span class="badge bg-danger" style="font-size: 0.7rem;">Declined</span>
                                    @endif
                                </div>
                                <div class="text-muted" style="font-size: 0.75rem;">
                                    {{ $approver->user->role }} • 
                                    @if($approver->status == 'Waiting')
                                        Not yet reached
                                    @else
                                        {{ date('M d, Y', strtotime($approver->updated_at)) }} at {{ date('h:i A', strtotime($approver->updated_at)) }}
                                    @endif
                                </div>
                            </div>
                            <button class="btn btn-link text-decoration-none p-0" 
                                    type="button" 
                                    data-bs-toggle="collapse" 
                                    data-bs-target="#approver{{ $approver->id }}" 
                                    aria-expanded="false" 
                                    aria-controls="approver{{ $approver->id }}"
                                    onclick="this.querySelector('i').classList.toggle('ri-arrow-down-s-line'); this.querySelector('i').classList.toggle('ri-arrow-up-s-line');">
                                <i class="ri-arrow-down-s-line text-muted" style="font-size: 1.25rem;"></i>
                            </button>
                        </div>
                        
                        <div class="collapse mt-2" id="approver{{ $approver->id }}">
                            <div class="border-top pt-2 mt-2">
                                <div class="text-muted small fw-semibold mb-1">
                                    <i class="ri-chat-check-line me-1"></i>REMARKS
                                </div>
                                <div class="text-muted" style="font-size: 0.8rem;">
                                    @if($approver->remarks)
                                        {!! nl2br(e($approver->remarks)) !!}
                                    @else
                                        No remarks
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                @endif

                <hr class="my-4">
                <div class="text-center text-muted small">
                    <i class="ri-information-line"></i> This is an official change request from the Document Management System<br>
                    <small>Scanned on: <span id="currentDateTime"></span></small>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const now = new Date();
        const options = { 
            year: 'numeric', 
            month: 'long', 
            day: 'numeric', 
            hour: '2-digit', 
            minute: '2-digit',
            hour12: true 
        };
        document.getElementById('currentDateTime').textContent = now.toLocaleString('en-US', options);
    </script>
</body>
</html>