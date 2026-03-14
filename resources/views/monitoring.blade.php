@extends('layouts.header')

@section('content')

<div class="mb-4">
    <h4 class="fs-2 fw-semibold mb-1">Monitoring</h4>
    <p class="text-muted">Track document approvals and document access</p>
</div>

<div id="for-approval" class="row g-4 align-items-stretch">
    <div class="col-12 d-flex flex-column">
        <div class="card shadow-sm w-100 qms-chart-card">
            <div class="card-body d-flex flex-column">
                <div class="mb-4">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="fw-semibold text-dark mb-0">For Approval</h5>
                        <div class="d-flex align-items-center gap-2">
                            <div class="btn-group" role="group">
                                <button type="button" class="btn btn-outline-secondary btn-sm view-toggle active" data-view="grid">
                                    <i class="ri-grid-line"></i>
                                </button>
                                <button type="button" class="btn btn-outline-secondary btn-sm view-toggle" data-view="list">
                                    <i class="ri-list-check"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <form action="{{ route('monitoring') }}" method="GET" id="pendingSearchForm">
                        @foreach(request()->except(['pending_search','pending_date','pending_dept','pending_office','pending_page']) as $key => $val)
                            <input type="hidden" name="{{ $key }}" value="{{ $val }}">
                        @endforeach
                        <div class="d-flex gap-2 align-items-center">
                            <div class="position-relative flex-grow-1">
                                <i class="ri-search-line position-absolute top-50 translate-middle-y ms-3 text-muted" style="z-index:2;"></i>
                                <input type="text"
                                    name="pending_search"
                                    id="pendingSearchInput"
                                    value="{{ request('pending_search') }}"
                                    placeholder="Search title, department, or date (e.g. Jan 2025)..."
                                    class="form-control form-control-sm ps-5 pe-5"
                                    autocomplete="off">
                                @if(request('pending_search'))
                                <a href="{{ route('monitoring') }}"
                                   class="position-absolute top-50 translate-middle-y end-0 me-2 text-muted text-decoration-none"
                                   style="z-index:2;">
                                    <i class="ri-close-circle-fill"></i>
                                </a>
                                @endif
                            </div>
                            <input type="hidden" name="pending_date" value="">
                            <input type="hidden" name="pending_dept" value="">
                            <input type="hidden" name="pending_office" value="">
                            <button type="submit" class="btn btn-primary btn-sm flex-shrink-0">
                                <i class="ri-search-line"></i>
                            </button>
                        </div>
                    </form>
                </div>

                <div id="gridView" class="row row-cols-1 row-cols-sm-4 g-2">
                    @foreach ($pending_cards as $approvers)
                    @php $change_request = $approvers->change_request; @endphp
                    <div class="col">
                        <div class="card border file-card position-relative" data-card-id="{{ $change_request->id }}">
                            <div class="position-absolute top-0 end-0 m-2 more-btn">
                                <button class="btn btn-sm btn-light p-1 file-more-btn" style="width:28px;height:28px;line-height:1;border-radius:6px;">
                                    <i class="ri-more-2-fill"></i>
                                </button>
                            </div>

                            <div class="file-dropdown-menu" data-card-id="{{ $change_request->id }}">
                                <button class="file-dropdown-item" data-action="display">
                                    <i class="ri-file-text-line"></i>
                                    <input type="hidden" class="file-path" value="{{ $change_request->file }}" />
                                    <span>View</span>
                                </button>
                                <div class="file-dropdown-divider"></div>
                                @php
                                    $Approver = $change_request->approvers->firstWhere('user_id', auth()->user()->id);
                                    $Status   = $Approver ? $Approver->status : 'Waiting';
                                @endphp
                                <button class="file-dropdown-item" data-action="approve"
                                    data-id="{{ $change_request->id }}"
                                    data-my-status="{{ $Status }}">
                                    <i class="ri-checkbox-circle-line"></i>
                                    <span>Sign & Approve</span>
                                </button>
                            </div>

                            <a href='#' class="text-decoration-none" onclick="return false;">
                                <iframe src="https://docs.google.com/gview?url={{ urlencode(asset($change_request->file)) }}&embedded=true"
                                        loading="lazy"
                                        class="card-img-top document-preview-iframe"
                                        scrolling="no"
                                        frameborder="0"></iframe>
                                <div class="card-body p-2 text-start">
                                    @php
                                        $myApprover = $change_request->approvers->firstWhere('user_id', auth()->user()->id);
                                        $myStatus   = $myApprover ? $myApprover->status : null;
                                    @endphp
                                    <div class="docu d-flex align-items-center gap-2">
                                        <i class="ri-file-pdf-line text-danger" style="font-size:1rem;"></i>
                                        @php
                                            $file = $change_request->file;
                                            $filename = explode('/', $file);
                                        @endphp
                                        <div class="fw-semibold text-dark text-truncate" style="font-size:0.75rem;">{{ $filename[2] }}</div>
                                    </div>
                                    @if($myStatus === 'Pending')
                                        <div class="mt-1">
                                            <span class="badge bg-warning text-dark" style="font-size:0.65rem;">
                                                <i class="ri-quill-pen-line me-1"></i>Your Turn to Sign
                                            </span>
                                        </div>
                                    @elseif($myStatus === 'Waiting')
                                        <div class="mt-1">
                                            <span class="badge bg-secondary" style="font-size:0.65rem;">
                                                <i class="ri-hourglass-line me-1"></i>Waiting for the first Approver to sign
                                            </span>
                                        </div>
                                    @endif
                                    <div class="d-flex flex-wrap gap-1 mt-1">
                                        @if($change_request->department)
                                            <span class="meta-tag"><i class="ri-building-line"></i> {{ $change_request->department->code }}</span>
                                        @endif
                                        @if($change_request->department && $change_request->department->office)
                                            <span class="meta-tag"><i class="ri-home-office-line"></i> {{ $change_request->department->office->name ?? $change_request->department->office->code }}</span>
                                        @endif
                                        <span class="meta-tag"><i class="ri-calendar-line"></i> {{ date('M d, Y', strtotime($change_request->created_at)) }}</span>
                                    </div>
                                    @php
                                        $dateCreated = new DateTime($change_request->updated_at);
                                        $now = new DateTime();
                                        $count = $now->diff($dateCreated);
                                        $dayName = $count->d > 1 ? "days" : "day";
                                    @endphp
                                    <small class="text-dark text-truncated">
                                        <i class="ri-time-line" style="font-size:1rem;"></i>
                                        <span>{{ $count->d }} {{ $dayName }}</span>
                                    </small>
                                </div>
                            </a>
                        </div>
                    </div>
                    @endforeach
                </div>

                <div id="listView" class="d-none">
                    <div class="overflow-auto">
                        <div class="drive-list-container" style="min-width:700px;">
                            <div class="drive-list-header">
                                <div class="drive-list-row">
                                    <div class="drive-col-name"><span>Name</span></div>
                                    <div class="drive-col-owner"><span>Owner</span></div>
                                    <div class="drive-col-dept"><span>Department</span></div>
                                    <div class="drive-col-modified"><span>Last modified</span></div>
                                    <div class="drive-col-size"><span>File size</span></div>
                                    <div class="drive-col-actions"></div>
                                </div>
                            </div>
                            <div class="drive-list-body">
                                @foreach ($pending_cards as $approvers)
                                @php
                                    $change_request = $approvers->change_request;
                                    $file = $change_request->file;
                                    $filename = explode('/', $file);
                                    $filesize = file_exists(public_path($file)) ? filesize(public_path($file)) : 0;
                                    $filesizeFormatted = $filesize > 0 ? number_format($filesize / 1024 / 1024, 2) . ' MB' : '--';
                                @endphp
                                <div class="drive-list-item file-card" data-card-id="{{ $change_request->id }}">
                                    <div class="drive-list-row">
                                        <div class="drive-col-name">
                                            <div class="d-flex align-items-center gap-3">
                                                <div class="file-icon-wrapper">
                                                    <i class="ri-file-pdf-line text-danger"></i>
                                                </div>
                                                <div class="file-info">
                                                    <div class="file-name">{{ $filename[count($filename)-1] }}</div>
                                                    <div class="file-subtitle text-muted">{{ $change_request->title }}</div>
                                                    @php
                                                        $myApproverList = $change_request->approvers->firstWhere('user_id', auth()->user()->id);
                                                        $myStatusList   = $myApproverList ? $myApproverList->status : null;
                                                    @endphp
                                                    @if($myStatusList === 'Pending')
                                                        <span class="badge bg-warning text-dark mt-1" style="font-size:0.62rem;">
                                                            <i class="ri-quill-pen-line me-1"></i>Your Turn to Sign
                                                        </span>
                                                    @elseif($myStatusList === 'Waiting')
                                                        <span class="badge bg-secondary mt-1" style="font-size:0.62rem;">
                                                            <i class="ri-hourglass-line me-1"></i>Waiting for the first Approver to sign
                                                        </span>
                                                    @endif
                                                    <div class="d-flex flex-wrap gap-1 mt-1">
                                                        @if($change_request->department && $change_request->department->office)
                                                            <span class="meta-tag"><i class="ri-home-office-line"></i> {{ $change_request->department->office->name ?? $change_request->department->office->code }}</span>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="drive-col-owner">
                                            <span>{{ $change_request->user->name }}</span>
                                        </div>
                                        <div class="drive-col-dept">
                                            @if($change_request->department)
                                                <span class="meta-tag"><i class="ri-building-line"></i> {{ $change_request->department->name }}</span>
                                            @else
                                                <span class="text-muted" style="font-size:0.8rem;">—</span>
                                            @endif
                                        </div>
                                        <div class="drive-col-modified">
                                            <span>{{ date('M d, Y', strtotime($change_request->created_at)) }}</span>
                                        </div>
                                        <div class="drive-col-size">
                                            <span>{{ $filesizeFormatted }}</span>
                                        </div>
                                        <div class="drive-col-actions">
                                            <button class="btn btn-sm btn-light file-more-btn drive-more-btn">
                                                <i class="ri-more-2-fill"></i>
                                            </button>
                                        </div>
                                    </div>

                                    <div class="file-dropdown-menu" data-card-id="{{ $change_request->id }}">
                                        <button class="file-dropdown-item" data-action="display">
                                            <i class="ri-eye-line"></i>
                                            <input type="hidden" class="file-path" value="{{ $change_request->file }}" />
                                            <span>View</span>
                                        </button>
                                        <div class="file-dropdown-divider"></div>
                                        <button class="file-dropdown-item" data-action="approve" data-id="{{ $change_request->id }}">
                                            <i class="ri-checkbox-circle-line"></i>
                                            <span>Approve</span>
                                        </button>
                                        <div class="file-dropdown-divider"></div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

                @if($pending_cards->isEmpty())
                    <div class="empty-state" id="emptyState">
                        <div class="empty-icon"><i class="ri-file-line"></i></div>
                        <h3 class="empty-title">No items for approval</h3>
                        <p class="empty-text">No documents are currently waiting for approval.</p>
                    </div>
                @endif

                @if($pending_cards->hasPages())
                <div class="d-flex justify-content-between align-items-center pt-3 border-top">
                    <div class="text-muted" style="font-size:0.875rem;">
                        Showing <strong>{{ $pending_cards->firstItem() }}</strong> to <strong>{{ $pending_cards->lastItem() }}</strong> of <strong>{{ $pending_cards->total() }}</strong> pending documents
                    </div>
                    <nav aria-label="Pending documents pagination">
                        <ul class="pagination pagination-sm mb-0">
                            @if ($pending_cards->onFirstPage())
                                <li class="page-item disabled"><span class="page-link">Previous</span></li>
                            @else
                                <li class="page-item">
                                    <a class="page-link" href="{{ $pending_cards->appends(request()->except('pending_page'))->previousPageUrl() }}" rel="prev">Previous</a>
                                </li>
                            @endif

                            @foreach ($pending_cards->getUrlRange(1, $pending_cards->lastPage()) as $page => $url)
                                @if ($page == $pending_cards->currentPage())
                                    <li class="page-item active" aria-current="page"><span class="page-link">{{ $page }}</span></li>
                                @else
                                    <li class="page-item">
                                        <a class="page-link" href="{{ $pending_cards->appends(request()->except('pending_page'))->url($page) }}">{{ $page }}</a>
                                    </li>
                                @endif
                            @endforeach

                            @if ($pending_cards->hasMorePages())
                                <li class="page-item">
                                    <a class="page-link" href="{{ $pending_cards->appends(request()->except('pending_page'))->nextPageUrl() }}" rel="next">Next</a>
                                </li>
                            @else
                                <li class="page-item disabled"><span class="page-link">Next</span></li>
                            @endif
                        </ul>
                    </nav>
                </div>
                @endif

            </div>
        </div>
    </div>
</div>

<div id="private-public-documents" class="row g-4">
    <div id="private-documents" class="col-12 col-lg-6">
        <div class="card shadow-sm w-100 qms-chart-card" style="max-height:450px;overflow:hidden;">
            <div class="card-body d-flex flex-column" style="height:450px;">
                <h5 class="fw-semibold text-dark mb-3">Private Documents</h5>
                <form action="{{ route('monitoring') }}" method="GET" class="mb-3">
                    @if(request('pending_search'))
                        <input type="hidden" name="pending_search" value="{{ request('pending_search') }}">
                    @endif
                    <div class="d-flex gap-2 align-items-center mt-1">
                        <div class="position-relative flex-grow-1">
                            <i class="ri-search-line position-absolute top-50 translate-middle-y ms-3 text-muted" style="z-index:2;"></i>
                            <input type="text"
                                name="private_search"
                                value="{{ request('private_search') }}"
                                placeholder="Search title, dept, office, date..."
                                class="form-control form-control-sm ps-5 pe-5"
                                autocomplete="off">
                            @if(request('private_search'))
                            <a href="{{ route('monitoring') }}"
                                class="position-absolute top-50 translate-middle-y end-0 me-2 text-muted text-decoration-none"
                                style="z-index:2;">
                                <i class="ri-close-circle-fill"></i>
                            </a>
                            @endif
                        </div>
                        <button type="submit" class="btn btn-primary btn-sm flex-shrink-0">
                            <i class="ri-search-line"></i>
                        </button>
                    </div>
                </form>

                <div class="d-flex flex-wrap gap-2 mb-2">
                    <span class="priv-legend" style="--lc:#27ae60;"><span></span> Access Granted</span>
                    <span class="priv-legend" style="--lc:#e67e22;"><span></span> Pending Request</span>
                    <span class="priv-legend" style="--lc:#adb5bd;"><span></span> No Access / Not Requested</span>
                </div>

                <div style="overflow-y:scroll;flex-grow:1;min-height:0;">
                    <ul class="list-group list-group-flush" style="overflow:visible;">
                        @forelse ($private_documents as $private_document)
                        @php
                            if ($private_document->has_valid_access) {
                                $stateClass  = 'priv-granted';
                                $borderColor = '#27ae60';
                                $iconBg      = '#d1fae5';
                                $iconColor   = '#27ae60';
                                $iconClass   = 'ri-shield-check-line';
                            } elseif ($private_document->has_pending_request) {
                                $stateClass  = 'priv-pending';
                                $borderColor = '#e67e22';
                                $iconBg      = '#fff3e0';
                                $iconColor   = '#e67e22';
                                $iconClass   = 'ri-time-line';
                            } else {
                                $stateClass  = 'priv-none';
                                $borderColor = '#dee2e6';
                                $iconBg      = '#f1f3f5';
                                $iconColor   = '#adb5bd';
                                $iconClass   = 'ri-git-repository-private-line';
                            }
                        @endphp
                        @php
                            $pdfUrl = null;
                            if ($private_document->has_valid_access) {
                                $pdfAttachment = $private_document->attachments->where('type', 'pdf_copy')->first();
                                $pdfUrl = $pdfAttachment ? url($pdfAttachment->attachment) : null;
                            }
                        @endphp
                        <li class="list-group-item px-2 py-2 priv-item {{ $stateClass }} priv-row-clickable"
                            style="border-left: 4px solid {{ $borderColor }}; border-radius: 6px; margin-bottom: 4px; cursor: pointer;"
                            @if($private_document->has_valid_access && $pdfUrl)
                                data-action="open-pdf"
                                data-url="{{ $pdfUrl }}"
                                data-doc-id="{{ $private_document->id }}"
                            @elseif(!$private_document->has_valid_access)
                                data-action="request-access"
                                data-modal="#requestAccess{{ $private_document->id }}"
                            @endif
                            title="@if($private_document->has_valid_access) Click to open document @elseif($private_document->has_pending_request) Request already submitted — awaiting approval @else Click to request access @endif">

                            <div class="d-flex align-items-start gap-2">

                                <div class="flex-shrink-0 pt-1">
                                    <div class="avatar-title rounded"
                                        style="width:30px;height:30px;display:flex;align-items:center;justify-content:center;background:{{ $iconBg }};color:{{ $iconColor }};">
                                        <i class="{{ $iconClass }}"></i>
                                    </div>
                                    @if($private_document->has_valid_access)
                                        @foreach($private_document->attachments->where('type', 'pdf_copy') as $attachment)
                                            <form action="{{ url('/documents/user-view') }}" method="post"
                                                id="userView{{ $attachment->id }}">
                                                @csrf
                                                <input type="hidden" name="document_id" value="{{ $attachment->document_id }}">
                                            </form>
                                        @endforeach
                                    @endif
                                </div>

                                <div class="flex-grow-1 overflow-hidden">
                                    @if($private_document->has_valid_access)
                                        <h6 class="fs-14 mb-0 text-truncate fw-semibold text-dark">{{ $private_document->title }}</h6>
                                        <div class="mt-1">
                                            @if($private_document->access_expiry)
                                                <span class="priv-status-badge" style="--bc:#d1fae5;--tc:#065f46;">
                                                    <i class="ri-calendar-check-line"></i>
                                                    Until {{ \Carbon\Carbon::parse($private_document->access_expiry)->format('M d, Y') }}
                                                </span>
                                            @else
                                                <span class="priv-status-badge" style="--bc:#d1fae5;--tc:#065f46;">
                                                    <i class="ri-infinity-line"></i> Indefinite Access
                                                </span>
                                            @endif
                                        </div>
                                    @elseif($private_document->has_pending_request)
                                        <h6 class="fs-14 mb-0 text-truncate fw-semibold text-dark">{{ $private_document->title }}</h6>
                                        <div class="mt-1">
                                            <span class="priv-status-badge" style="--bc:#fff3e0;--tc:#92400e;">
                                                <i class="ri-time-line"></i> Awaiting Approval
                                            </span>
                                        </div>
                                    @else
                                        <h6 class="fs-14 mb-0 text-truncate fw-semibold text-muted" style="font-style:italic;">{{ $private_document->title }}</h6>
                                        <div class="mt-1">
                                            <span class="priv-status-badge" style="--bc:#f1f3f5;--tc:#6c757d;">
                                                <i class="ri-lock-line"></i> No Access — Click to Request
                                            </span>
                                        </div>
                                    @endif
                                    <small class="text-muted d-block text-truncate mt-1" title="{{ $private_document->control_code }}">
                                        {{ $private_document->control_code }}
                                    </small>
                                    <small class="text-muted d-block text-truncate">Owner: {{ $private_document->owner->name }}</small>
                                </div>

                                <div class="flex-shrink-0 text-end d-flex flex-column align-items-end gap-1"
                                     style="min-width:70px;"
                                     onclick="event.stopPropagation();">
                                    <small class="text-muted" style="font-size:0.65rem;white-space:nowrap;">
                                        <i class="ri-calendar-line"></i> {{ date('M d, Y', strtotime($private_document->created_at)) }}
                                    </small>
                                    <div class="dropdown mt-1">
                                        <a href="javascript:void(0)" class="text-decoration-none"
                                            data-bs-toggle="dropdown"
                                            aria-expanded="false">
                                            <span class="badge bg-secondary-subtle text-secondary" style="font-size:0.6rem;">
                                                <i class="ri-more-2-fill"></i>
                                            </span>
                                        </a>
                                        <ul class="dropdown-menu dropdown-menu-end">
                                            @if($private_document->has_valid_access)
                                                <li>
                                                    <span class="dropdown-item text-success disabled">
                                                        <i class="ri-checkbox-circle-line me-2"></i> Access Granted
                                                    </span>
                                                </li>
                                            @elseif($private_document->has_pending_request)
                                                <li>
                                                    <span class="dropdown-item text-warning disabled">
                                                        <i class="ri-time-line me-2"></i> Request Pending
                                                    </span>
                                                </li>
                                            @else
                                                <li>
                                                    <a href="javascript:void(0)" class="dropdown-item"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#requestAccess{{ $private_document->id }}">
                                                        <i class="ri-lock-unlock-line me-2"></i> Request Access
                                                    </a>
                                                </li>
                                            @endif
                                            <li>
                                                <a href="{{ url('/documents/visitors/' . $private_document->id) }}"
                                                    target="_blank" class="dropdown-item">
                                                    <i class="ri-eye-line me-2"></i> View Visitors
                                                    <span class="badge bg-primary-subtle text-primary ms-1">
                                                        {{ $private_document->visitor->count() }}
                                                    </span>
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>

                            </div>
                        </li>
                        @empty
                            <li class="list-group-item text-center text-muted py-4">
                                <i class="ri-folder-lock-line d-block mb-1" style="font-size:1.5rem;"></i>
                                No private documents found.
                            </li>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <div id="public-documents" class="col-12 col-lg-6">
        <div class="card shadow-sm w-100 qms-chart-card" style="max-height:450px;overflow:hidden;">
            <div class="card-body d-flex flex-column" style="height:450px;">
                <h5 class="fw-semibold text-dark mb-3">Public Documents</h5>

                <form action="{{ route('monitoring') }}" method="GET" class="mb-3">
                    @if(request('pending_search'))
                        <input type="hidden" name="pending_search" value="{{ request('pending_search') }}">
                    @endif
                    <div class="d-flex gap-2 align-items-center mt-1">
                        <div class="position-relative flex-grow-1">
                            <i class="ri-search-line position-absolute top-50 translate-middle-y ms-3 text-muted" style="z-index:2;"></i>
                            <input type="text"
                                name="public_search"
                                value="{{ request('public_search') }}"
                                placeholder="Search title, dept, office, date..."
                                class="form-control form-control-sm ps-5 pe-5"
                                autocomplete="off">
                            @if(request('public_search'))
                            <a href="{{ route('monitoring') }}"
                                class="position-absolute top-50 translate-middle-y end-0 me-2 text-muted text-decoration-none"
                                style="z-index:2;">
                                <i class="ri-close-circle-fill"></i>
                            </a>
                            @endif
                        </div>
                        <button type="submit" class="btn btn-primary btn-sm flex-shrink-0">
                            <i class="ri-search-line"></i>
                        </button>
                    </div>
                </form>

                <div class="mb-2" style="height:22px;">
                    <span class="priv-legend" style="--lc:#3b82f6;">
                        <span></span> Open to everyone — no access required
                    </span>
                </div>

                <div style="overflow-y:scroll;flex-grow:1;min-height:0;">
                    <ul class="list-group list-group-flush">
                        @forelse ($documents as $document)
                        @php
                            $pubAttachment = $document->attachments->where('type', 'pdf_copy')->first();
                            $pubUrl        = $pubAttachment ? url($pubAttachment->attachment) : null;
                        @endphp
                        <li class="list-group-item px-2 py-2 priv-item pub-item"
                            style="border-left: 4px solid #3b82f6; border-radius: 6px; margin-bottom: 4px; cursor: {{ $pubUrl ? 'pointer' : 'default' }};"
                            @if($pubUrl)
                                data-pub-url="{{ $pubUrl }}"
                                data-pub-doc-id="{{ $document->id }}"
                            @endif
                            title="{{ $pubUrl ? 'Click to open document' : '' }}">

                            <div class="d-flex align-items-start gap-2">

                                <div class="flex-shrink-0 pt-1">
                                    <div class="avatar-title rounded"
                                        style="width:30px;height:30px;display:flex;align-items:center;justify-content:center;background:#dbeafe;color:#3b82f6;">
                                        <i class="ri-file-text-line"></i>
                                    </div>
                                </div>

                                <div class="flex-grow-1 overflow-hidden">
                                    <h6 class="fs-14 mb-0 text-truncate fw-semibold text-dark">{{ $document->title }}</h6>
                                    <div class="mt-1">
                                        <span class="priv-status-badge" style="--bc:#dbeafe;--tc:#1e40af;">
                                            <i class="ri-global-line"></i> Public
                                        </span>
                                    </div>
                                    <small class="text-muted d-block text-truncate mt-1" title="{{ $document->control_code }}">
                                        {{ $document->control_code }}
                                    </small>
                                    <div class="d-flex flex-wrap gap-1 mt-1">
                                        @if($document->department)
                                            <span class="meta-tag"><i class="ri-building-line"></i> {{ $document->department->code }}</span>
                                        @endif
                                        @if($document->department && $document->department->office)
                                            <span class="meta-tag"><i class="ri-home-office-line"></i> {{ $document->department->office->name ?? $document->department->office->code }}</span>
                                        @endif
                                    </div>
                                </div>

                                <div class="flex-shrink-0 text-end d-flex flex-column align-items-end gap-1"
                                     style="min-width:70px;">
                                    <small class="text-muted" style="font-size:0.65rem;white-space:nowrap;">
                                        <i class="ri-calendar-line"></i> {{ date('M d, Y', strtotime($document->created_at)) }}
                                    </small>
                                    <a href="{{ url('/documents/visitors/'.$document->id) }}"
                                        target="_blank"
                                        class="text-decoration-none"
                                        onclick="event.stopPropagation();">
                                        <span class="badge bg-primary-subtle text-primary" style="font-size:0.6rem;">
                                            <i class="ri-eye-line"></i> {{ $document->visitor->count() }}
                                        </span>
                                    </a>
                                </div>

                            </div>
                        </li>
                        @empty
                            <li class="list-group-item text-center text-muted py-4">
                                <i class="ri-folder-open-line d-block mb-1" style="font-size:1.5rem;"></i>
                                No public documents found.
                            </li>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>
    </div>

</div>

<div class="modal fade" id="dashboardSignModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="ri-lock-line me-2"></i>Confirm Password</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p class="text-muted small mb-3">Enter your password to proceed to the signing page.</p>
                <input type="password" id="dashboardSignPassword" class="form-control" placeholder="Password" />
                <div id="dashboardSignError" class="text-danger small mt-2 d-none">Incorrect password. Please try again.</div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="dashboardSignConfirm">
                    <i class="ri-quill-pen-line me-1"></i>Confirm & Sign
                </button>
            </div>
        </div>
    </div>
</div>

@foreach ($private_documents as $private_document)
    @include('dashboard.request_access')
@endforeach

@endsection

@section('js')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="{{ asset('barcode/JsBarcode.all.min.js') }}"></script>

<script>
    function userView(documentId) {
        fetch("{{ url('/documents/user-view') }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ document_id: documentId })
        });
    }

    document.addEventListener('DOMContentLoaded', function () {

        JsBarcode(".barcode").init();

        const viewToggles = document.querySelectorAll('.view-toggle');
        const gridView    = document.getElementById('gridView');
        const listView    = document.getElementById('listView');

        const savedView = localStorage.getItem('monitoringDocsView') || 'grid';
        setActiveView(savedView);

        viewToggles.forEach(button => {
            button.addEventListener('click', function () {
                const view = this.getAttribute('data-view');
                setActiveView(view);
                localStorage.setItem('monitoringDocsView', view);
            });
        });

        function setActiveView(view) {
            viewToggles.forEach(btn => {
                btn.classList.toggle('active', btn.getAttribute('data-view') === view);
            });
            if (view === 'grid') {
                listView.classList.add('d-none');
                setTimeout(() => gridView.classList.remove('d-none'), 50);
            } else {
                gridView.classList.add('d-none');
                setTimeout(() => listView.classList.remove('d-none'), 50);
            }
        }

        document.querySelectorAll('.file-dropdown-menu').forEach(menu => {
            document.body.appendChild(menu);
        });

        function closeAllDropdowns() {
            document.querySelectorAll('.file-dropdown-menu').forEach(menu => menu.classList.remove('show'));
            document.querySelectorAll('.file-card').forEach(c => c.classList.remove('dropdown-open'));
        }

        document.querySelectorAll('.file-more-btn').forEach(button => {
            button.addEventListener('click', function (e) {
                e.preventDefault();
                e.stopPropagation();

                const fileCard   = this.closest('.file-card');
                const cardId     = fileCard.dataset.cardId;
                const dropdown   = document.querySelector(`.file-dropdown-menu[data-card-id="${cardId}"]`);

                document.querySelectorAll('.file-dropdown-menu').forEach(menu => {
                    if (menu !== dropdown) menu.classList.remove('show');
                });
                document.querySelectorAll('.file-card').forEach(c => c.classList.remove('dropdown-open'));

                const rect          = this.getBoundingClientRect();
                const dropdownWidth = 200;
                let left = rect.right - dropdownWidth;
                let top  = rect.bottom + 4;

                if (left < 8) left = 8;
                if (left + dropdownWidth > window.innerWidth - 8) left = window.innerWidth - dropdownWidth - 8;

                dropdown.style.top      = top + 'px';
                dropdown.style.left     = left + 'px';
                dropdown.style.position = 'fixed';

                dropdown.classList.toggle('show');
                if (dropdown.classList.contains('show')) {
                    fileCard.classList.add('dropdown-open');
                }
            });
        });

        document.addEventListener('click', function (e) {
            const item = e.target.closest('.file-dropdown-item');
            if (!item) return;

            e.preventDefault();
            e.stopPropagation();

            const action   = item.getAttribute('data-action');
            const filePath = item.querySelector('.file-path')?.value;

            switch (action) {
                case 'display':
                    if (filePath) window.open("{{ url('') }}/" + filePath, '_blank');
                    break;

                case 'approve':
                    const changeRequestId = item.getAttribute('data-id');
                    const Status          = item.getAttribute('data-my-status');

                    if (!changeRequestId) break;

                    if (Status === 'Waiting') {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Not Your Turn Yet',
                            html: 'The previous approver must sign the document first before you can proceed.',
                            confirmButtonColor: '#8B0000',
                            confirmButtonText: 'Got it',
                        });
                        break;
                    }

                    document.getElementById('dashboardSignPassword').value = '';
                    document.getElementById('dashboardSignError').classList.add('d-none');

                    var signModal = new bootstrap.Modal(document.getElementById('dashboardSignModal'));
                    signModal.show();

                    document.getElementById('dashboardSignConfirm').onclick = function () {
                        const password = document.getElementById('dashboardSignPassword').value;
                        if (!password) {
                            document.getElementById('dashboardSignError').textContent = 'Please enter your password.';
                            document.getElementById('dashboardSignError').classList.remove('d-none');
                            return;
                        }

                        fetch("{{ url('change-request/confirm-password') }}", {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify({ password: password, change_request_id: changeRequestId })
                        })
                        .then(r => r.json())
                        .then(data => {
                            if (data.success) {
                                signModal.hide();
                                window.location.href = "{{ route('documents.signature', '') }}/" + changeRequestId;
                            } else {
                                document.getElementById('dashboardSignError').textContent = 'Incorrect password. Please try again.';
                                document.getElementById('dashboardSignError').classList.remove('d-none');
                            }
                        });
                    };
                    break;
            }
            closeAllDropdowns();
        });

        document.addEventListener('click', function (e) {
            if (!e.target.closest('.file-more-btn') && !e.target.closest('.file-dropdown-menu')) {
                closeAllDropdowns();
            }
        });

        window.addEventListener('scroll', closeAllDropdowns, { passive: true });
        window.addEventListener('resize', closeAllDropdowns, { passive: true });

        const listItems = document.querySelectorAll('#listView .drive-list-item');
        listItems.forEach(item => {
            item.addEventListener('click', function (e) {
                if (e.target.closest('.file-more-btn') || e.target.closest('.file-dropdown-menu')) return;
                listItems.forEach(i => i.classList.remove('selected'));
                this.classList.add('selected');
            });
            item.addEventListener('dblclick', function (e) {
                if (e.target.closest('.file-more-btn')) return;
                const filePath = this.querySelector('.file-path')?.value;
                if (filePath) window.open("{{ url('') }}/" + filePath, '_blank');
            });
        });

        document.querySelectorAll('.priv-row-clickable').forEach(row => {
            row.addEventListener('click', function (e) {
                if (e.target.closest('.dropdown')) return;

                const action = this.dataset.action;
                const url    = this.dataset.url;
                const docId  = this.dataset.docId;
                const modal  = this.dataset.modal;

                if (action === 'open-pdf' && url) {
                    userView(docId);
                    window.open(url, '_blank');
                } else if (action === 'request-access' && modal) {
                    const modalEl = document.querySelector(modal);
                    if (modalEl) new bootstrap.Modal(modalEl).show();
                }
            });
        });

        document.querySelectorAll('.pub-item[data-pub-url]').forEach(row => {
            row.addEventListener('click', function (e) {
                if (e.target.closest('.dropdown')) return;
                const url   = this.dataset.pubUrl;
                const docId = this.dataset.pubDocId;
                if (url) {
                    userView(docId);
                    window.open(url, '_blank');
                }
            });
        });

    });
</script>
@endsection