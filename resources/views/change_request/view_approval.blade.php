@extends('layouts.header')

@section('css')
<link href="https://cdn.jsdelivr.net/npm/summernote@0.9.0/dist/summernote-bs4.min.css" rel="stylesheet">
<style>
    .info-label {
        color: #6c757d;
        font-size: 0.875rem;
        margin-bottom: 0.25rem;
    }
    .info-value {
        font-weight: 600;
        margin-bottom: 1rem;
    }
    .file-preview-card {
        transition: transform 0.2s;
        height: 100%;
    }
    .file-preview-card:hover {
        transform: translateY(-2px);
    }
    .comment-item {
        padding: 1rem;
        background: #f8f9fa;
        border-radius: 0.5rem;
        margin-bottom: 1rem;
    }
    .comment-author {
        font-weight: 600;
        color: #212529;
    }
    .comment-time {
        font-size: 0.75rem;
        color: #6c757d;
    }
    .status-badge {
        font-size: 0.8rem;
        padding: 0.35rem 0.75rem;
    }
    .section-card {
        box-shadow: 0 0.125rem 0.25rem rgba(0,0,0,0.075);
        border: none;
        margin-bottom: 1.5rem;
    }
    .card-header {
        background: #f8f9fa;
        border-bottom: 2px solid #e9ecef;
        font-weight: 600;
        font-size: 0.875rem;
        letter-spacing: 0.5px;
        padding: 1rem 1.25rem;
    }
</style>
@endsection

@section('content')
<div class="container-fluid">
    <div class="mb-4">
        <a href="{{ url('for-approval') }}" class="btn btn-secondary">
            <i class="ri-arrow-left-line me-1"></i> Back to List
        </a>
    </div>
    
    <div class="row g-3">
        <div class="col-lg-4">
            <div class="card section-card">
                <div class="card-header text-uppercase">
                    <i class="ri-file-text-line me-2"></i>Document Information
                </div>
                <div class="card-body">
                    <div class="info-label">Document ID</div>
                    <div class="info-value">DOC-{{ date('Y', strtotime($change_request->created_at)) }}-{{ str_pad($change_request->id,3,'0',STR_PAD_LEFT) }}</div>

                    <div class="info-label">Title</div>
                    <div class="info-value">{{ $change_request->title }}</div>

                    <div class="info-label">Description</div>
                    <div class="info-value">{!! nl2br(e($change_request->description)) !!}</div>

                    <div class="row">
                        <div class="col-6">
                            <div class="info-label">Category</div>
                            <div class="info-value">{{ $change_request->category }}</div>
                        </div>
                        <div class="col-6">
                            <div class="info-label">Privacy</div>
                            <div class="info-value">{{ $change_request->privacy }}</div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-6">
                            <div class="info-label">Revision</div>
                            <div class="info-value">{{ $change_request->revision }}</div>
                        </div>
                        <div class="col-6">
                            <div class="info-label">Requested By</div>
                            <div class="info-value">{{ $change_request->user->name }}</div>
                        </div>
                        <div class="col-6">
                            <div class="info-label">Remarks</div>
                            <div class="info-value">{{ $change_request->remarks }}</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card section-card">
                <div class="card-header text-uppercase">
                    <i class="ri-user-follow-line me-2"></i>Approvers
                </div>
                <div class="card-body">
                    @foreach ($change_request->approvers as $approver)
                    <div class="d-flex justify-content-between align-items-center mb-2 pb-2 border-bottom">
                        <span>{{ $approver->user->name }}</span>
                        @if($approver->status == "Pending")
                        <span class="badge bg-warning status-badge">
                        @elseif($approver->status == "Approved")
                        <span class="badge bg-success status-badge">
                        @elseif($approver->status == "Returned")
                        <span class="badge bg-danger status-badge">
                        @elseif($approver->status == "Waiting")
                        <span class="badge bg-info status-badge">
                        @endif
                            {{ $approver->status }}
                        </span>
                    </div>
                    @endforeach
                </div>
            </div>

            <div class="card section-card">
                <div class="card-header text-uppercase">
                    <i class="ri-history-line me-2"></i>History
                </div>
                <div class="card-body">
                    @if(count($change_request->history) > 0)
                        <div data-simplebar style="max-height: 300px;">
                            @foreach ($change_request->history as $history)
                            {{-- <div class="d-flex align-items-start gap-2 mb-3 pb-3 border-bottom">
                                <div class="flex-shrink-0">
                                    <span class="badge bg-secondary rounded-circle p-2">
                                        <i class="ri-calendar-check-line"></i>
                                    </span>
                                </div>
                                <div class="flex-grow-1">
                                    <div class="fw-semibold text-dark">{{ $log->user->name }}</div>
                                    <div class="text-muted small">
                                        <i class="ri-time-line me-1"></i>
                                        {{ date('d M Y - h:i A', strtotime($log->created_at)) }}
                                    </div>
                                    @if($log->date)
                                    <div class="mt-1 small">
                                        <span class="text-muted">Antidate:</span>
                                        <span class="fw-semibold text-primary">{{ date('d M Y', strtotime($log->date)) }}</span>
                                    </div>
                                    @endif
                                    @if($log->remarks)
                                    <div class="mt-1 text-muted small">{{ $log->remarks }}</div>
                                    @endif
                                </div>
                            </div> --}}
                            <div class="comment-item">
                                <div class="d-flex align-items-start gap-2">
                                    <img src="{{ asset('images/no_image.png') }}" alt="" class="rounded-circle"
                                        style="width: 32px; height: 32px;">
                                    <div class="flex-grow-1">
                                        <div class="comment-author">{{$history->user->name }}</div>
                                        <div class="comment-time">{{ date('d M Y - h:i A', strtotime($history->created_at))}}</div>
                                        <div class="mt-2 text-muted">{!! $history->comment !!}</div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-muted text-center py-4" style="font-style: italic;">No history yet...</p>
                    @endif
                </div>
            </div>

            <div class="card section-card">
                <div class="card-header text-uppercase d-flex align-items-center">
                    <i class="ri-chat-3-line me-2"></i>
                    <span class="flex-grow-1">Comments</span>
                </div>
                <div class="card-body">
                    <div data-simplebar style="max-height: 400px; overflow-y:auto;" class="mb-3" id="CommentContainer">
                        
                    </div>

                    <form method="POST" id="CommentForm">
                        @csrf
                        <input type="hidden" name="change_request_id" value="{{ $change_request->id }}">
                        
                        <label class="form-label small fw-semibold">Add a Comment</label>
                        <textarea name="comment" class="form-control" id="summernote" rows="3" placeholder="Write your comment..."></textarea>
                        <div class="invalid-feedback"></div>
                        
                        <button type="submit" class="btn btn-success w-100 mt-3" id="CommentBtn">
                            <i class="ri-send-plane-fill me-1"></i> Post Comment
                        </button>
                    </form>

                    <div class="mt-3 d-grid gap-2">
                        @php
                        $request = ($change_request->approvers)->where('user_id', auth()->user()->id)->where('status', 'Pending');
                        $if_return = ($change_request->approvers)->whereIn('status', 'Returned');
                        $approver = ($change_request->approvers)->where('user_id', auth()->user()->id)->where('status', 'Pending')->first();
                        @endphp

                        @if(count($request) > 0)
                        {{-- <a href="{{ url('documents/signature/'.$change_request->id) }}" target="_blank" class="btn btn-primary">
                            <i class="ri-quill-pen-line me-1"></i> Sign Documents
                        </a> --}}
                        @if($approver->request_type == "For Receiving")
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#uploadSignDocument{{ $change_request->id }}">
                            <i class="ri-quill-pen-line me-1"></i> Upload documents
                        </button>
                        @else
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#confirmPassword{{ $change_request->id }}">
                            <i class="ri-quill-pen-line me-1"></i> Sign Documents
                        </button>
                        @endif

                        <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#return{{ $change_request->id }}">
                            <i class="ri-arrow-go-back-line me-1"></i> Return Documents
                        </button>
                        @include('change_request.return_modal')
                        @include("change_request.upload_sign_document")
                        @endif

                        @if(count($if_return) > 0 && (auth()->user()->id == $change_request->user_id))
                        <form action="{{ url('change-request/change-request-action/'.$change_request->id) }}" method="POST">
                            @csrf
                            <input type="hidden" name="action" value="Submit">
                            <button type="submit" class="btn btn-success w-100">
                                <i class="ri-check-line me-1"></i> Submit Documents
                            </button>
                        </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-8">
            <div class="card section-card">
                <div class="card-header text-uppercase">
                    <i class="ri-attachment-2 me-2"></i>Attachments
                </div>
                <div class="card-body">
                    <div class="mb-4">
                        <h6 class="text-muted mb-3">Main Document</h6>
                        <div class="card border file-preview-card">
                            <a href='{{ url($change_request->file) }}' class="text-decoration-none" target="_blank">
                                <div class="position-relative" style="height: 200px; overflow: hidden; background: #f8f9fa;">
                                    <iframe src="https://docs.google.com/gview?url={{ urlencode(url($change_request->file)) }}&embedded=true" 
                                        class="w-100 h-100 border-0"></iframe>
                                </div>
                                <div class="card-body p-3 bg-light">
                                    <div class="d-flex align-items-center gap-2">
                                        <i class="ri-file-pdf-line text-danger fs-4"></i>
                                        @php
                                        $file = $change_request->file;
                                        $filename = explode('/',$file);
                                        @endphp
                                        <div class="flex-grow-1">
                                            <div class="fw-semibold text-dark text-truncate">{{ $filename[2] }}</div>
                                            <small class="text-muted">Click to view full document</small>
                                        </div>
                                        <i class="ri-external-link-line text-primary"></i>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>

                    @if(count($change_request->supporting_documents) > 0)
                    <div>
                        <h6 class="text-muted mb-3">Supporting Documents</h6>
                        <div class="row g-2">
                            @foreach ($change_request->supporting_documents as $key=>$document)
                            <div class="col-md-6">
                                <div class="card border file-preview-card">
                                    <a href='{{ url($document->file) }}' class="text-decoration-none" target="_blank">
                                        <div class="position-relative" style="height: 150px; overflow: hidden; background: #f8f9fa;">
                                            <iframe src="https://docs.google.com/gview?url={{ urlencode(url($document->file)) }}&embedded=true" 
                                                class="w-100 h-100 border-0"></iframe>
                                        </div>
                                        <div class="card-body p-2 bg-light">
                                            <div class="d-flex align-items-center gap-2">
                                                <i class="ri-file-pdf-line text-danger"></i>
                                                @php
                                                $file = $document->file;
                                                $filename = explode('/',$file);
                                                @endphp
                                                <div class="fw-semibold text-dark text-truncate small flex-grow-1">{{ $filename[2] }}</div>
                                                <i class="ri-external-link-line text-primary small"></i>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@include('change_request.confirm_password')
@endsection

@section('js')
<script src="{{ asset('js/ajaxRequest.js') }}"></script>
<script src="{{ asset('js/errorDisplay.js') }}"></script>
<script src="{{ asset('assets/js/pages/form-editor.init.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/summernote@0.9.0/dist/summernote-bs4.min.js"></script>
<script>
    function getComments() {
        ajaxRequest({
            type:"GET",
            url: "{{ url('change-request/get-comments') }}",
            data: {
                change_request_id: "{{ $change_request->id }}"
            },
            success: function(response) {
                var commentList = ''
                response.forEach(comment => {
                    commentList += `
                        <div class="comment-item">
                            <div class="d-flex align-items-start gap-2">
                                <img src="{{ asset('images/no_image.png') }}" alt="" class="rounded-circle" style="width: 32px; height: 32px;">
                                <div class="flex-grow-1">
                                    <div class="comment-author">${comment.user.name}</div>
                                    <div class="comment-time">${comment.created_at}</div>
                                    <div class="mt-2 text-muted">${comment.user_comment}</div>
                                </div>
                            </div>
                        </div>
                    `
                })
                
                $("#CommentContainer").html(commentList)
            }
        })
    }

    $(document).ready(function() {
        getComments()

        $('#summernote').summernote({
            height: 200,
            placeholder: "Write a comment..."
        });

        $("#CommentForm").on("submit", function(e) {
            e.preventDefault()

            if ($('#summernote').summernote('isEmpty')) {
                swal("Error", "Comment cannot be empty", "error");
                return;
            }

            var form = $(this).serializeArray()

            ajaxRequest({
                type:"POST",
                url:"{{ url('change-request/comments') }}",
                data: form,
                beforeSend: function() {
                    $("#CommentBtn").prop("disabled", true).text("Commenting...")
                },
                success: function(response) {
                    swal("Success", response.message, response.status)
                    $('#summernote').summernote('reset'); // clear editor
                }, 
                complete: function() {
                    $("#CommentBtn").prop("disabled", false).text("Comment")
                    getComments()
                }
            })
        })

        $("#uploadSignDocumentForm").on("submit", function(e) {
            e.preventDefault()

            var formData = new FormData($(this)[0])

            ajaxRequest({
                type:"POST",
                url:"{{ url('change-request/upload-sign-documents') }}",
                data: formData,
                contentType: false,
                processData: false,
                beforeSend: function() {
                    $("#UploadSignDocsBtn").prop("disabled", true).text("Submiting...")
                },
                success: function(response) {
                    if (response.status == "success") {
                        swal("Success", response.message, response.status)
                        $("#uploadSignDocument"+"{{ $change_request->id }}").modal("hide")
                    }
                },
                complete: function() {
                    $("#UploadSignDocsBtn").prop("disabled", false).text("Submit")
                },
                error: function(error) {
                    var errors = error.responseJSON
                    displayError("uploadSignDocumentForm", errors.errors)
                }
            })
            
        })

        $("#ConfirmPasswordForm").on("submit", function(e) {
            e.preventDefault()

            var formData = $(this).serializeArray()

            ajaxRequest({
                type:"POST",
                url:"{{ url('change-request/confirm-password') }}",
                data: formData,
                beforeSend: function() {
                    $("#ChangePassBtn").prop("disabled", true).text("Submiting...")
                },
                success: function(response) {
                    if (response.status == "success") {
                        window.location.href = response.redirect
                    }
                },
                complete: function() {
                    $("#ChangePassBtn").prop("disabled", false).text("Submit")
                },
                error: function(error) {
                    var errors = error.responseJSON
                    
                    swal("Error", errors.message, errors.status)
                }
            })
            
        })
    });
</script>
@endsection