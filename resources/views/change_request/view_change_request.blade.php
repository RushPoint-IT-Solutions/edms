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
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
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
        <a href="{{ url('change-requests') }}" class="btn btn-secondary">
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
                    <div class="info-value">DOC-{{ date('Y', strtotime($change_request->created_at)) }}-{{
                        str_pad($change_request->id,3,'0',STR_PAD_LEFT) }}</div>

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
                            <div class="info-value">{{ $change_request->revision_count }}</div>
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
                    <i class="ri-user-follow-line me-2"></i>History
                </div>
                <div class="card-body">
                    <div data-simplebar style="max-height: 300px;">
                        @if(count($change_request->history) > 0)
                            @foreach($change_request->history as $history)
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
                        @else 
                            <p class="text-muted text-center py-4" style="font-style: italic;">No histories yet...</p>
                        @endif
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
                        <span>{{ $approver->user->name }} -
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
                        </span>

                        @can('edit date approved')
                            @if($approver->status == "Approved")
                                <a href='javascript:void(0)' class='text-danger ms-2' data-bs-target="#editApprovedDate{{ $approver->id }}" data-bs-toggle="modal">
                                    <i class="fa fa-calendar fs-3"></i>
                                </a>
                                @include('documents.edit_approved_date')
                            @endif
                        @endcan
                    </div>
                    @endforeach
                </div>
            </div>

            <div class="card section-card">
                <div class="card-header text-uppercase d-flex align-items-center">
                    <i class="ri-chat-3-line me-2"></i>
                    <span class="flex-grow-1">Comments</span>
                </div>
                <div class="card-body">
                    {{-- <div data-simplebar style="max-height: 400px;" class="mb-3">
                        @if(count($change_request->comments) > 0)
                            @foreach ($change_request->comments as $comment)
                            <div class="comment-item">
                                <div class="d-flex align-items-start gap-2">
                                    <img src="{{ asset('images/no_image.png') }}" alt="" class="rounded-circle"
                                        style="width: 32px; height: 32px;">
                                    <div class="flex-grow-1">
                                        <div class="comment-author">{{ $comment->user->name }}</div>
                                        <div class="comment-time">{{ date('d M Y - h:i A', strtotime($comment->created_at))
                                            }}</div>
                                        <div class="mt-2 text-muted">{!! $comment->comment !!}</div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        @else
                        <p class="text-muted text-center py-4" style="font-style: italic;">No comments yet...</p>
                        @endif
                    </div> --}}
                    <div data-simplebar style="max-height: 400px; overflow-y:auto;" class="mb-3" id="CommentContainer">
                        
                    </div>

                    @php
                        $if_return = ($change_request->approvers)->whereIn('status', 'Returned');
                    @endphp

                    @if(count($if_return) > 0 && (auth()->user()->id == $change_request->user_id))
                        {{-- <form method="POST" action="{{ url('change-request/comments') }}" class="mb-3" onsubmit="show()">
                            @csrf
                            <input type="hidden" name="change_request_id" value="{{ $change_request->id }}">
                            
                            <label class="form-label small fw-semibold">Add a Comment</label>
                            <textarea name="comment" class="form-control {{ $errors->has('comment') ? 'is-invalid' : '' }}" 
                                id="summernote" rows="3" placeholder="Write your comment..."></textarea>
                            @if($errors->has('comment'))
                                <p class="text-danger small mt-1">{{ $errors->first('comment') }}</p>
                            @endif
                            
                            <button type="submit" class="btn btn-primary w-100 mt-3">
                                <i class="ri-send-plane-fill me-1"></i> Post Comment
                            </button>
                        </form> --}}
                        
                        <form method="POST" id="CommentForm" class="mb-3">
                            @csrf
                            <input type="hidden" name="change_request_id" value="{{ $change_request->id }}">
                            
                            <label class="form-label small fw-semibold">Add a Comment</label>
                            <textarea name="comment" class="form-control" id="summernote" rows="3" placeholder="Write your comment..."></textarea>
                            <div class="invalid-feedback"></div>
                            
                            <button type="submit" class="btn btn-primary w-100 mt-3" id="CommentBtn">
                                <i class="ri-send-plane-fill me-1"></i> Post Comment
                            </button>
                        </form>

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

        <div class="col-lg-8">
            <div class="card section-card">
                <div class="card-header text-uppercase">
                    <i class="ri-attachment-2 me-2"></i>Attachments
                </div>
                <div class="card-body">
                    <div class="mb-4">
                        <h6 class="text-muted mb-3">Main Document</h6>
                        <div class="card border file-preview-card">
                            @php
                                $url = "";
                                if($change_request->status == "Approved") {
                                    $attachment = ($change_request->document->attachments)->where("type","pdf_copy")->first();
                                    $url = '/documents/view-pdf/'.$attachment->id;
                                }
                                else {
                                    $url = $change_request->file;
                                }
                            @endphp
                            <a href='{{ url($url) }}' class="text-decoration-none" target="_blank">
                                <div class="position-relative"
                                    style="height: 200px; overflow: hidden; background: #f8f9fa;">
                                    <iframe
                                        src="https://docs.google.com/gview?url={{ urlencode(url($change_request->file)) }}&embedded=true"
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
                                        <div class="position-relative"
                                            style="height: 150px; overflow: hidden; background: #f8f9fa;">
                                            <iframe
                                                src="https://docs.google.com/gview?url={{ urlencode(url($document->file)) }}&embedded=true"
                                                class="w-100 h-100 border-0"></iframe>
                                        </div>
                                        <div class="card-body p-2 bg-light">
                                            <div class="d-flex align-items-center gap-2">
                                                <i class="ri-file-pdf-line text-danger"></i>
                                                @php
                                                $file = $document->file;
                                                $filename = explode('/',$file);
                                                @endphp
                                                <div class="fw-semibold text-dark text-truncate small flex-grow-1">{{
                                                    $filename[2] }}</div>
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

            @if($change_request->revision_count > 0)
            <div class="card section-card" id="revisionHistoryCard">
                <div class="card-header text-uppercase d-flex align-items-center justify-content-between">
                    <span><i class="ri-git-branch-line me-2"></i>Revision History</span>
                    <span class="badge bg-warning text-dark">
                        {{ $change_request->revision_count }} revision(s)
                    </span>
                </div>
                <div class="card-body p-0">
                    <div class="px-3 pt-3 pb-2 border-bottom d-flex align-items-center gap-2 flex-wrap" id="snapshotNav">
                        <span class="text-muted small me-1">View:</span>
                        <div class="d-flex gap-2 flex-wrap" id="snapshotNavBtns">
                            <span class="spinner-border spinner-border-sm text-secondary"></span>
                        </div>
                    </div>

                    <div class="p-3" id="snapshotPane">
                        <div class="text-center text-muted py-4" id="snapshotLoading">
                            <i class="fa fa-spinner fa-spin fa-2x"></i>
                            <p class="mt-2 mb-0">Loading revision history…</p>
                        </div>
                        <div id="snapshotContent" style="display:none;"></div>
                    </div>
                </div>
            </div>
            @endif
        </div>
        
    </div>
</div>
@endsection

@section('js')
<script src="{{ asset('assets/js/pages/form-editor.init.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/summernote@0.9.0/dist/summernote-bs4.min.js"></script>
<script src="{{ asset('js/ajaxRequest.js') }}"></script>
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
                if (response.length > 0) {
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
                } else {
                    $("#CommentContainer").html("<i>No comment...</i>")
                }
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
                    $('#summernote').summernote('reset');
                }, 
                complete: function() {
                    $("#CommentBtn").prop("disabled", false).text("Comment")
                    getComments()
                }
            })
        })
    });

    (function () {
        var card = document.getElementById('revisionHistoryCard');
        if (!card) return;

        var navBtns = document.getElementById('snapshotNavBtns');
        var paneLoading = document.getElementById('snapshotLoading');
        var paneContent = document.getElementById('snapshotContent');
        var docStatus = '{{ $change_request->status }}';
        var allData = null;
        var sortedSnaps = [];

        fetch('{{ url("change-request/" . $change_request->id . "/revisions") }}', {
            headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
        })
        .then(function(r) { return r.json(); })
        .then(function(data) {
            allData = data;
            sortedSnaps = data.snapshots.slice().sort(function(a,b){ return a.revision_number - b.revision_number; });

            paneLoading.style.display = 'none';
            buildNav();

            var btns = navBtns.querySelectorAll('button');
            if (btns.length > 0) {
                btns[btns.length - 1].click();
            }
        })
        .catch(function(err) {
            paneLoading.innerHTML = '<span class="text-danger">Failed to load history.</span>';
            console.error(err);
        });

        function buildNav() {
            navBtns.innerHTML = '';

            var totalRevisions = sortedSnaps.length;

            for (var i = 1; i <= totalRevisions; i++) {
                (function(revNum) {
                    var isLast = (revNum === totalRevisions);
                    var isCurrent = isLast && docStatus !== 'Approved';

                    var btn = document.createElement('button');
                    btn.type = 'button';
                    btn.className = 'btn btn-sm btn-outline-secondary';

                    var icon = isCurrent ? 'ri-git-branch-line' : 'ri-file-text-line';
                    var label = 'Revision ' + revNum + (isCurrent ? ' (Current)' : '');
                    btn.innerHTML = '<i class="' + icon + ' me-1"></i>' + label;

                    btn.addEventListener('click', function() {
                        highlightBtn(this);

                        var leftData, leftLabel, rightData, rightLabel;

                        if (revNum === 1) {
                            leftData = sortedSnaps[0];
                            leftLabel = 'Original';
                            if (totalRevisions > 1) {
                                rightData = sortedSnaps[1];
                                rightLabel = 'Revision 1';
                            } else {
                                rightData = allData.current;
                                rightLabel = isCurrent ? 'Revision 1 (Current)' : 'Approved Version';
                            }
                        } else {
                            leftData = sortedSnaps[revNum - 1];
                            leftLabel = 'Revision ' + (revNum - 1);
                            if (isLast) {
                                rightData = allData.current;
                                rightLabel = isCurrent ? 'Revision ' + revNum + ' (Current)' : 'Approved Version';
                            } else {
                                rightData = sortedSnaps[revNum];
                                rightLabel = 'Revision ' + revNum;
                            }
                        }

                        renderPanel(leftData, leftLabel, rightData, rightLabel);
                    });

                    navBtns.appendChild(btn);
                })(i);
            }
        }

        function highlightBtn(activeBtn) {
            navBtns.querySelectorAll('button').forEach(function(b) {
                b.className = 'btn btn-sm btn-outline-secondary';
            });
            activeBtn.className = 'btn btn-sm btn-warning';
        }

        function renderPanel(leftData, leftLabel, rightData, rightLabel) {
        paneContent.style.display = 'block';

        var isApprovedRight = (rightLabel === 'Approved Version');
        var leftAlert = 'alert-secondary';
        var leftIcon = 'ri-history-line';
        var rightAlert = (isApprovedRight || rightData.is_current) ? 'alert-success' : 'alert-warning';
        var rightIcon = (isApprovedRight || rightData.is_current) ? 'ri-checkbox-circle-line' : 'ri-git-branch-line';

        var html = '<div class="row g-3">';

        // Left
        html += '<div class="col-md-6">';
        html += '<div class="alert ' + leftAlert + ' py-2 mb-2 d-flex align-items-center gap-2">';
        html += '<i class="' + leftIcon + '"></i><strong>' + escHtml(leftLabel) + '</strong>';
        html += smallMuted('submitted ' + fmtDate(leftData.submitted_at) + ' by ' + escHtml(leftData.submitted_by));
        html += '</div>';
        html += revFields(leftData, null, false);
        html += '</div>';

        // Right
        html += '<div class="col-md-6">';
        html += '<div class="alert ' + rightAlert + ' py-2 mb-2 d-flex align-items-center gap-2">';
        html += '<i class="' + rightIcon + '"></i><strong>' + escHtml(rightLabel) + '</strong>';
        html += smallMuted('submitted ' + fmtDate(rightData.submitted_at) + ' by ' + escHtml(rightData.submitted_by));
        html += '</div>';
        html += revFields(rightData, leftData, rightData.is_current || isApprovedRight);
        html += '</div>';

        html += '</div>';
        paneContent.innerHTML = html;
    }

    function revFields(rev, baseline, isNewest) {
        var fields = [
            { key: 'title', label: 'Title' },
            { key: 'description', label: 'Description' },
            { key: 'category', label: 'Category' },
            { key: 'remarks', label: 'Notes / Remarks' },
            { key: 'due_date', label: 'Due Date' },
        ];

        var html = '';
        fields.forEach(function(f) {
            var val = rev[f.key]      || '—';
            var prev = baseline ? (baseline[f.key] || '—') : null;
            var changed = prev !== null && String(val) !== String(prev);

            html += '<div class="mb-2 pb-2 border-bottom">';
            html += '<div class="text-muted" style="font-size:0.75rem;text-transform:uppercase;letter-spacing:0.5px;">' + f.label + '</div>';
            html += '<div class="fw-semibold" style="font-size:0.88rem;' + (changed ? 'background:#fff3cd;border-radius:3px;padding:0 4px;' : '') + '">';
            html += escHtml(String(val));
            if (changed) html += ' <span class="badge bg-warning text-dark ms-1" style="font-size:0.65rem;vertical-align:middle;">changed</span>';
            html += '</div></div>';
        });

        var depts = (rev.departments || []).join(', ') || '—';
        var prevDepts = baseline ? ((baseline.departments || []).join(', ') || '—') : null;
        var deptsChanged = prevDepts !== null && depts !== prevDepts;
        html += '<div class="mb-2 pb-2 border-bottom">';
        html += '<div class="text-muted" style="font-size:0.75rem;text-transform:uppercase;">Offices</div>';
        html += '<div class="fw-semibold" style="font-size:0.88rem;' + (deptsChanged ? 'background:#fff3cd;border-radius:3px;padding:0 4px;' : '') + '">';
        html += escHtml(depts);
        if (deptsChanged) html += ' <span class="badge bg-warning text-dark ms-1" style="font-size:0.65rem;">changed</span>';
        html += '</div></div>';

        html += '<div class="mb-2">';
        html += '<div class="text-muted mb-1" style="font-size:0.75rem;text-transform:uppercase;">Approvers</div>';
        var approvers = rev.approvers || [];
        if (approvers.length === 0) {
            html += '<span class="text-muted fst-italic">None</span>';
        } else {
            approvers.forEach(function(a) {
                var name = a.user_name || a.name || 'N/A';
                html += '<div class="d-flex align-items-center gap-2 mb-1">';
                html += '<span class="badge bg-primary" style="font-size:0.65rem;">L' + a.level + '</span>';
                html += '<span style="font-size:0.82rem;">' + escHtml(name) + '</span>';
                html += '<span class="text-muted" style="font-size:0.75rem;">· ' + escHtml(a.request_type || '') + '</span>';
                html += '</div>';
            });
        }
        html += '</div>';

        if (rev.file) {
            var prevFile = baseline ? baseline.file : null;
            var fileChanged = isNewest && prevFile !== null && rev.file !== prevFile;
            var fileUrl = '{{ url("/") }}' + '/' + rev.file.replace(/^\/+/, '');
            html += '<div class="mt-2 pt-2 border-top">';
            html += '<div class="text-muted mb-1" style="font-size:0.75rem;text-transform:uppercase;">File</div>';
            html += '<a href="' + escHtml(fileUrl) + '" target="_blank" class="btn btn-outline-danger btn-sm">';
            html += '<i class="ri-file-pdf-line me-1"></i>View PDF';
            if (fileChanged) html += ' <span class="badge bg-warning text-dark ms-1" style="font-size:0.65rem;">new file</span>';
            html += '</a></div>';
        }

        return html;
    }

        function escHtml(str) {
            var d = document.createElement('div');
            d.appendChild(document.createTextNode(String(str)));
            return d.innerHTML;
        }

        function smallMuted(txt) {
            return '<span class="text-muted ms-auto" style="font-size:0.72rem;">' + txt + '</span>';
        }

        function fmtDate(dt) {
            if (!dt) return '—';
            var d = new Date(dt);
            return isNaN(d) ? dt : d.toLocaleDateString('en-US', { month:'short', day:'numeric', year:'numeric' });
        }
    }());
</script>
@endsection