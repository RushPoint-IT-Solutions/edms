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
                                <button type="button" class="btn btn-light btn-sm view-toggle active" data-view="grid">
                                    <i class="ri-grid-line"></i>
                                </button>
                                <button type="button" class="btn btn-light btn-sm view-toggle" data-view="list">
                                    <i class="ri-list-check"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex gap-2 align-items-center">
                        <div class="position-relative flex-grow-1">
                            <i class="ri-search-line position-absolute top-50 translate-middle-y ms-3 text-muted" style="z-index:2;"></i>
                            <input type="text"
                                id="pendingSearchInput"
                                placeholder="Search title, department, or date (e.g. Jan 2025)..."
                                class="form-control form-control-sm ps-5 pe-5"
                                autocomplete="off">
                            <a href="javascript:void(0)" id="pendingSearchClear"
                               class="position-absolute top-50 translate-middle-y end-0 me-2 text-muted text-decoration-none d-none"
                               style="z-index:2;">
                                <i class="ri-close-circle-fill"></i>
                            </a>
                        </div>
                        <button type="button" id="pendingSearchBtn" class="btn btn-first btn-sm flex-shrink-0">
                            <i class="ri-search-line"></i>
                        </button>
                    </div>
                </div>

                <div id="gridView" class="row row-cols-1 row-cols-sm-4 g-2"></div>

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
                            <div class="drive-list-body" id="listViewBody"></div>
                        </div>
                    </div>
                </div>

                <div class="empty-state d-none" id="pendingEmptyState">
                    <div class="empty-icon"><i class="ri-file-line"></i></div>
                    <h3 class="empty-title">No items for approval</h3>
                    <p class="empty-text">No documents are currently waiting for approval.</p>
                </div>

                <div id="pendingPagination" class="d-none">
                    <div class="d-flex justify-content-between align-items-center pt-3 border-top">
                        <div class="text-muted" style="font-size:0.875rem;" id="pendingPaginationInfo"></div>
                        <nav aria-label="Pending documents pagination">
                            <ul class="pagination pagination-sm mb-0" id="pendingPaginationLinks"></ul>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="private-public-documents" class="row g-4">
    <div id="private-documents" class="col-12 col-lg-4">
        <div class="card shadow-sm w-100 qms-chart-card" style="max-height:450px;overflow:hidden;">
            <div class="card-body d-flex flex-column" style="height:450px;">
                <h5 class="fw-semibold text-dark mb-3">Private Documents</h5>
                <div class="d-flex gap-2 align-items-center mb-3">
                    <div class="position-relative flex-grow-1">
                        <i class="ri-search-line position-absolute top-50 translate-middle-y ms-3 text-muted" style="z-index:2;"></i>
                        <input type="text" id="privateSearchInput"
                            placeholder="Search title, dept, office, date..."
                            class="form-control form-control-sm ps-5 pe-5"
                            autocomplete="off">
                        <a href="javascript:void(0)" id="privateSearchClear"
                            class="position-absolute top-50 translate-middle-y end-0 me-2 text-muted text-decoration-none d-none"
                            style="z-index:2;">
                            <i class="ri-close-circle-fill"></i>
                        </a>
                    </div>
                    <button type="button" id="privateSearchBtn" class="btn btn-first btn-sm flex-shrink-0">
                        <i class="ri-search-line"></i>
                    </button>
                </div>
                <div class="d-flex flex-wrap gap-2 mb-2">
                    <span class="priv-legend" style="--lc:#27ae60;"><span></span> Access Granted</span>
                    <span class="priv-legend" style="--lc:#e67e22;"><span></span> Pending Request</span>
                    <span class="priv-legend" style="--lc:#adb5bd;"><span></span> No Access</span>
                </div>
                <div style="overflow-y:scroll;flex-grow:1;min-height:0;">
                    <ul class="list-group list-group-flush" id="privateDocsList" style="overflow:visible;">
                        <li class="list-group-item text-center text-muted py-4" id="privateDocsLoading">
                            <i class="fa fa-spinner fa-spin"></i> Loading...
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <div id="public-documents" class="col-12 col-lg-4">
        <div class="card shadow-sm w-100 qms-chart-card" style="max-height:450px;overflow:hidden;">
            <div class="card-body d-flex flex-column" style="height:450px;">
                <h5 class="fw-semibold text-dark mb-3">Public Documents</h5>
                <div class="d-flex gap-2 align-items-center mb-3">
                    <div class="position-relative flex-grow-1">
                        <i class="ri-search-line position-absolute top-50 translate-middle-y ms-3 text-muted" style="z-index:2;"></i>
                        <input type="text" id="publicSearchInput"
                            placeholder="Search title, dept, office, date..."
                            class="form-control form-control-sm ps-5 pe-5"
                            autocomplete="off">
                        <a href="javascript:void(0)" id="publicSearchClear"
                            class="position-absolute top-50 translate-middle-y end-0 me-2 text-muted text-decoration-none d-none"
                            style="z-index:2;">
                            <i class="ri-close-circle-fill"></i>
                        </a>
                    </div>
                    <button type="button" id="publicSearchBtn" class="btn btn-first btn-sm flex-shrink-0">
                        <i class="ri-search-line"></i>
                    </button>
                </div>
                <div class="mb-2" style="height:22px;">
                    <span class="priv-legend" style="--lc:#3b82f6;">
                        <span></span> Open to everyone — no access required
                    </span>
                </div>
                <div style="overflow-y:scroll;flex-grow:1;min-height:0;">
                    <ul class="list-group list-group-flush" id="publicDocsList">
                        <li class="list-group-item text-center text-muted py-4" id="publicDocsLoading">
                            <i class="fa fa-spinner fa-spin"></i> Loading...
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12 col-lg-4">
        <div class="card shadow-sm w-100 qms-chart-card" style="max-height:450px;overflow:hidden;">
            <div class="card-body d-flex flex-column" style="height:450px;">
                <h5 class="fw-semibold text-dark mb-3">Document Tracking</h5>
                <div class="d-flex gap-2 align-items-center mb-3">
                    <div class="position-relative flex-grow-1">
                        <i class="ri-search-line position-absolute top-50 translate-middle-y ms-3 text-muted" style="z-index:2;"></i>
                        <input type="text" id="trackingSearchInput"
                            placeholder="Search title, dept, office, date..."
                            class="form-control form-control-sm ps-5 pe-5"
                            autocomplete="off">
                        <a href="javascript:void(0)" id="trackingSearchClear"
                            class="position-absolute top-50 translate-middle-y end-0 me-2 text-muted text-decoration-none d-none"
                            style="z-index:2;">
                            <i class="ri-close-circle-fill"></i>
                        </a>
                    </div>
                    <button type="button" id="trackingSearchBtn" class="btn btn-first btn-sm flex-shrink-0">
                        <i class="ri-search-line"></i>
                    </button>
                </div>
                <div class="mb-2" style="height:22px;">
                    <span class="priv-legend" style="--lc:#e0f0e3;">
                        <span></span> Track your documents here
                    </span>
                </div>
                <div style="overflow-y:scroll;flex-grow:1;min-height:0;">
                    <ul class="list-group list-group-flush" id="trackingList">
                        <li class="list-group-item text-center text-muted py-4" id="trackingLoading">
                            <i class="fa fa-spinner fa-spin"></i> Loading...
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="dashboardSignModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-md modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-bottom">
                <h5 class="modal-title"><i class="ri-lock-line me-2 mb-2"></i>Confirm Password</h5>
                <button type="button" class="btn-close mb-2" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p class="text-muted small mb-3">Enter your password to proceed to the signing page.</p>
                <input type="password" id="dashboardSignPassword" class="form-control" placeholder="Password" />
                <div id="dashboardSignError" class="text-danger small mt-2 d-none">Incorrect password. Please try again.</div>
            </div>
            <div class="modal-footer border-top">
                <button type="button" class="btn btn-secondary mt-2" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary mt-2" id="dashboardSignConfirm">
                    <i class="ri-quill-pen-line me-1"></i>Confirm & Sign
                </button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="viewApproversModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-bottom">
                <h5 class="modal-title fw-semibold" id="vatTitle"></h5>
                <button type="button" class="btn-close mb-2" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="vatBody"></div>
            <div class="modal-footer border-top">
                <button type="button" class="btn btn-danger mt-2" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-success mt-2" data-bs-dismiss="modal">Okay</button>
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
const CSRF   = '{{ csrf_token() }}';
const BASE   = '{{ url('') }}';
const ROUTES = {
    pending : '{{ route('monitoring.pending') }}',
    private : '{{ route('monitoring.private') }}',
    public : '{{ route('monitoring.public') }}',
    tracking : '{{ route('monitoring.tracking') }}',
    confirmPassword : '{{ url('change-request/confirm-password') }}',
    signature : '{{ route('documents.signature', '') }}',
    privateView : '{{ url('/change-request/private-user-view') }}',
};

let pendingPage = 1;

function ajaxGet(url, params = {}) {
    const qs = new URLSearchParams(params).toString();
    return fetch(`${url}?${qs}`, {
        headers: { 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' }
    }).then(r => r.json());
}

function loadPendingCards(page = 1) {
    pendingPage = page;
    const search = document.getElementById('pendingSearchInput').value;

    document.getElementById('gridView').innerHTML = '';
    document.getElementById('listViewBody').innerHTML = '';
    document.getElementById('pendingEmptyState').classList.add('d-none');
    document.getElementById('pendingPagination').classList.add('d-none');

    ajaxGet(ROUTES.pending, { pending_search: search, page }).then(res => {
        renderPendingGrid(res);
        renderPendingList(res);
        renderPendingPagination(res, search);
        rebindDropdowns();
        JsBarcode(".barcode").init();
    });
}

function renderPendingGrid(res) {
    const grid = document.getElementById('gridView');

    if (!res.data.length) {
        document.getElementById('pendingEmptyState').classList.remove('d-none');
        return;
    }

    grid.innerHTML = res.data.map(item => {
        const iframeSrc = `https://docs.google.com/gview?url=${encodeURIComponent(BASE + '/' + item.file)}&embedded=true`;
        const statusBadge = item.my_status === 'Pending'
            ? `<div class="mt-1"><span class="badge bg-warning text-dark" style="font-size:0.65rem;"><i class="ri-quill-pen-line me-1"></i>Your Turn to Sign</span></div>`
            : item.my_status === 'Waiting'
            ? `<div class="mt-1"><span class="badge bg-secondary" style="font-size:0.65rem;"><i class="ri-hourglass-line me-1"></i>Waiting for the first Approver to sign</span></div>`
            : '';
        const metaDept = item.dept_code   ? `<span class="meta-tag"><i class="ri-building-line"></i> ${item.dept_code}</span>` : '';
        const metaOffice = item.office_name ? `<span class="meta-tag"><i class="ri-home-office-line"></i> ${item.office_name}</span>` : '';
        const dayName = item.days_ago === 1 ? 'day' : 'days';

        return `
        <div class="col">
            <div class="card border file-card position-relative" data-card-id="${item.id}">
                <div class="position-absolute top-0 end-0 m-2 more-btn">
                    <button class="btn btn-sm btn-light p-1 file-more-btn" style="width:28px;height:28px;line-height:1;border-radius:6px;">
                        <i class="ri-more-2-fill"></i>
                    </button>
                </div>
                <div class="file-dropdown-menu" data-card-id="${item.id}">
                    <button class="file-dropdown-item" data-action="display">
                        <i class="ri-file-text-line"></i>
                        <input type="hidden" class="file-path" value="${item.file}" />
                        <span>View</span>
                    </button>
                    <div class="file-dropdown-divider"></div>
                    <button class="file-dropdown-item" data-action="approve"
                        data-id="${item.id}"
                        data-my-status="${item.my_status || ''}">
                        <i class="ri-checkbox-circle-line"></i>
                        <span>Sign & Approve</span>
                    </button>
                </div>
                <a href="#" class="text-decoration-none" onclick="return false;">
                    <iframe src="${iframeSrc}"
                            loading="lazy"
                            class="card-img-top document-preview-iframe"
                            scrolling="no"
                            frameborder="0"></iframe>
                    <div class="card-body p-2 text-start">
                        <div class="docu d-flex align-items-center gap-2">
                            <i class="ri-file-pdf-line text-danger" style="font-size:1rem;"></i>
                            <div class="fw-semibold text-dark text-truncate" style="font-size:0.75rem;">${item.filename}</div>
                        </div>
                        ${statusBadge}
                        <div class="d-flex flex-wrap gap-1 mt-1">
                            ${metaDept}${metaOffice}
                            <span class="meta-tag"><i class="ri-calendar-line"></i> ${item.created_at}</span>
                        </div>
                        <small class="text-dark text-truncated">
                            <i class="ri-time-line" style="font-size:1rem;"></i>
                            <span>${item.days_ago} ${dayName}</span>
                        </small>
                    </div>
                </a>
            </div>
        </div>`;
    }).join('');
}

function renderPendingList(res) {
    const body = document.getElementById('listViewBody');
    if (!res.data.length) { body.innerHTML = ''; return; }

    body.innerHTML = res.data.map(item => {
        const statusBadge = item.my_status === 'Pending'
            ? `<span class="badge bg-warning text-dark mt-1" style="font-size:0.62rem;"><i class="ri-quill-pen-line me-1"></i>Your Turn to Sign</span>`
            : item.my_status === 'Waiting'
            ? `<span class="badge bg-secondary mt-1" style="font-size:0.62rem;"><i class="ri-hourglass-line me-1"></i>Waiting for the first Approver to sign</span>`
            : '';
        const metaOffice = item.office_name ? `<span class="meta-tag"><i class="ri-home-office-line"></i> ${item.office_name}</span>` : '';
        const metaDept = item.dept_code ? `<span class="meta-tag"><i class="ri-building-line"></i> ${item.dept_code}</span>` : '';

        return `
        <div class="drive-list-item file-card" data-card-id="${item.id}">
            <div class="drive-list-row">
                <div class="drive-col-name">
                    <div class="d-flex align-items-center gap-3">
                        <div class="file-icon-wrapper"><i class="ri-file-pdf-line text-danger"></i></div>
                        <div class="file-info">
                            <div class="file-name">${item.filename}</div>
                            ${statusBadge}
                            <div class="d-flex flex-wrap gap-1 mt-1">${metaOffice}</div>
                        </div>
                    </div>
                </div>
                <div class="drive-col-owner"><span>—</span></div>
                <div class="drive-col-dept">${metaDept || '<span class="text-muted" style="font-size:0.8rem;">—</span>'}</div>
                <div class="drive-col-modified"><span>${item.created_at}</span></div>
                <div class="drive-col-size"><span>—</span></div>
                <div class="drive-col-actions">
                    <button class="btn btn-sm btn-light file-more-btn drive-more-btn">
                        <i class="ri-more-2-fill"></i>
                    </button>
                </div>
            </div>
            <div class="file-dropdown-menu" data-card-id="${item.id}">
                <button class="file-dropdown-item" data-action="display">
                    <i class="ri-eye-line"></i>
                    <input type="hidden" class="file-path" value="${item.file}" />
                    <span>View</span>
                </button>
                <div class="file-dropdown-divider"></div>
                <button class="file-dropdown-item" data-action="approve"
                    data-id="${item.id}"
                    data-my-status="${item.my_status || ''}">
                    <i class="ri-checkbox-circle-line"></i>
                    <span>Approve</span>
                </button>
                <div class="file-dropdown-divider"></div>
            </div>
        </div>`;
    }).join('');
}

function renderPendingPagination(res, search) {
    const wrap = document.getElementById('pendingPagination');
    if (res.last_page <= 1) { wrap.classList.add('d-none'); return; }

    wrap.classList.remove('d-none');
    document.getElementById('pendingPaginationInfo').innerHTML =
        `Showing <strong>${res.first_item}</strong> to <strong>${res.last_item}</strong> of <strong>${res.total}</strong> pending documents`;

    const ul = document.getElementById('pendingPaginationLinks');
    const cur = res.current_page;
    const last = res.last_page;
    let html   = '';

    html += cur === 1
        ? `<li class="page-item disabled"><span class="page-link">Previous</span></li>`
        : `<li class="page-item"><a class="page-link" href="javascript:void(0)" data-page="${cur - 1}">Previous</a></li>`;

    for (let p = 1; p <= last; p++) {
        html += p === cur
            ? `<li class="page-item active"><span class="page-link">${p}</span></li>`
            : `<li class="page-item"><a class="page-link" href="javascript:void(0)" data-page="${p}">${p}</a></li>`;
    }

    html += cur === last
        ? `<li class="page-item disabled"><span class="page-link">Next</span></li>`
        : `<li class="page-item"><a class="page-link" href="javascript:void(0)" data-page="${cur + 1}">Next</a></li>`;

    ul.innerHTML = html;
    ul.querySelectorAll('[data-page]').forEach(link => {
        link.addEventListener('click', () => loadPendingCards(parseInt(link.dataset.page)));
    });
}

function loadPrivateDocs() {
    const search = document.getElementById('privateSearchInput').value;
    const list = document.getElementById('privateDocsList');
    list.innerHTML = `<li class="list-group-item text-center text-muted py-4"><i class="fa fa-spinner fa-spin"></i> Loading...</li>`;

    ajaxGet(ROUTES.private, { private_search: search }).then(res => {
        if (!res.data.length) {
            list.innerHTML = `
                <li class="list-group-item text-center text-muted py-4">
                    <i class="ri-folder-lock-line d-block mb-1" style="font-size:1.5rem;"></i>
                    No private documents found.
                </li>`;
            return;
        }

        list.innerHTML = res.data.map(doc => {
            let stateClass, borderColor, iconBg, iconColor, iconClass;
            let dataAttrs = '', titleAttr = '';

            if (doc.has_valid_access) {
                stateClass = 'priv-granted'; borderColor = '#27ae60';
                iconBg = '#d1fae5'; iconColor = '#27ae60'; iconClass = 'ri-shield-check-line';
                const fileUrl = doc.file ? `${BASE}/${doc.file}` : null;
                if (fileUrl) {
                    dataAttrs = `data-action="open-pdf" data-url="${fileUrl}" data-doc-id="${doc.id}"`;
                }
                titleAttr = 'Click to open document';
            } else if (doc.has_pending_request) {
                stateClass = 'priv-pending'; borderColor = '#e67e22';
                iconBg = '#fff3e0'; iconColor = '#e67e22'; iconClass = 'ri-time-line';
                dataAttrs = `data-action="pending"`;
                titleAttr = 'Request already submitted — awaiting approval';
            } else {
                stateClass = 'priv-none'; borderColor = '#dee2e6';
                iconBg = '#f1f3f5'; iconColor = '#adb5bd'; iconClass = 'ri-git-repository-private-line';
                dataAttrs = `data-action="request-access" data-modal="#requestAccess${doc.id}"`;
                titleAttr = 'Click to request access';
            }

            const accessBadge = doc.has_valid_access
                ? (doc.access_expiry
                    ? `<span class="priv-status-badge" style="--bc:#d1fae5;--tc:#065f46;"><i class="ri-calendar-check-line"></i> Until ${doc.access_expiry}</span>`
                    : `<span class="priv-status-badge" style="--bc:#d1fae5;--tc:#065f46;"><i class="ri-infinity-line"></i> Indefinite Access</span>`)
                : doc.has_pending_request
                ? `<span class="priv-status-badge" style="--bc:#fff3e0;--tc:#92400e;"><i class="ri-time-line"></i> Awaiting Approval</span>`
                : `<span class="priv-status-badge" style="--bc:#f1f3f5;--tc:#6c757d;"><i class="ri-lock-line"></i> No Access — Click to Request</span>`;

            const titleStyle = !doc.has_valid_access && !doc.has_pending_request
                ? 'class="fs-14 mb-0 text-truncate fw-semibold text-muted" style="font-style:italic;"'
                : 'class="fs-14 mb-0 text-truncate fw-semibold text-dark"';

            const dropdownAction = doc.has_valid_access
                ? `<li><span class="dropdown-item text-success disabled"><i class="ri-checkbox-circle-line me-2"></i> Access Granted</span></li>`
                : doc.has_pending_request
                ? `<li><span class="dropdown-item text-warning disabled"><i class="ri-time-line me-2"></i> Request Pending</span></li>`
                : `<li><a href="javascript:void(0)" class="dropdown-item" data-bs-toggle="modal" data-bs-target="#requestAccess${doc.id}"><i class="ri-lock-unlock-line me-2"></i> Request Access</a></li>`;

            return `
            <li class="list-group-item px-2 py-2 priv-item ${stateClass} priv-row-clickable"
                style="border-left: 4px solid ${borderColor}; border-radius: 6px; margin-bottom: 4px; cursor: pointer;"
                ${dataAttrs} title="${titleAttr}">
                <div class="d-flex align-items-start gap-2">
                    <div class="flex-shrink-0 pt-1">
                        <div class="avatar-title rounded"
                            style="width:30px;height:30px;display:flex;align-items:center;justify-content:center;background:${iconBg};color:${iconColor};">
                            <i class="${iconClass}"></i>
                        </div>
                    </div>
                    <div class="flex-grow-1 overflow-hidden">
                        <h6 ${titleStyle}>${doc.title}</h6>
                        <div class="mt-1">${accessBadge}</div>
                        <small class="text-muted d-block text-truncate mt-1" title="${doc.control_code}">${doc.control_code}</small>
                        <small class="text-muted d-block text-truncate">Owner: ${doc.owner_name}</small>
                    </div>
                    <div class="flex-shrink-0 text-end d-flex flex-column align-items-end gap-1"
                         style="min-width:70px;" onclick="event.stopPropagation();">
                        <small class="text-muted" style="font-size:0.65rem;white-space:nowrap;">
                            <i class="ri-calendar-line"></i> ${doc.created_at}
                        </small>
                        <div class="dropdown mt-1">
                            <a href="javascript:void(0)" class="text-decoration-none"
                                data-bs-toggle="dropdown" aria-expanded="false">
                                <span class="badge bg-secondary-subtle text-secondary" style="font-size:0.6rem;">
                                    <i class="ri-more-2-fill"></i>
                                </span>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                ${dropdownAction}
                                <li>
                                    <a href="${BASE}/change-request/visitors/${doc.id}"
                                        target="_blank" class="dropdown-item">
                                        <i class="ri-eye-line me-2"></i> View Visitors
                                        <span class="badge bg-primary-subtle text-primary ms-1">${doc.visitor_count}</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </li>`;
        }).join('');

        bindPrivateRowClicks();
    });
}

function bindPrivateRowClicks() {
    document.querySelectorAll('#privateDocsList .priv-row-clickable').forEach(row => {
        row.addEventListener('click', function (e) {
            if (e.target.closest('.dropdown')) return;
            const action = this.dataset.action;
            const url = this.dataset.url;
            const docId = this.dataset.docId;
            const modal = this.dataset.modal;

            if (action === 'open-pdf' && url) {
                fetch(ROUTES.privateView, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF },
                    body: JSON.stringify({ change_request_id: docId })
                });
                window.open(url, '_blank');
            } else if (action === 'request-access' && modal) {
                const el = document.querySelector(modal);
                if (el) new bootstrap.Modal(el).show();
            }
        });
    });
}

function loadPublicDocs() {
    const search = document.getElementById('publicSearchInput').value;
    const list = document.getElementById('publicDocsList');
    list.innerHTML = `<li class="list-group-item text-center text-muted py-4"><i class="fa fa-spinner fa-spin"></i> Loading...</li>`;

    ajaxGet(ROUTES.public, { public_search: search }).then(res => {
        if (!res.data.length) {
            list.innerHTML = `
                <li class="list-group-item text-center text-muted py-4">
                    <i class="ri-folder-open-line d-block mb-1" style="font-size:1.5rem;"></i>
                    No public documents found.
                </li>`;
            return;
        }

        list.innerHTML = res.data.map(doc => {
            const metaDept = doc.dept_code ? `<span class="meta-tag"><i class="ri-building-line"></i> ${doc.dept_code}</span>` : '';
            const metaOffice = doc.office_name ? `<span class="meta-tag"><i class="ri-home-office-line"></i> ${doc.office_name}</span>` : '';
            const officeBadge = doc.publish_office_ids
                ? `<span class="priv-status-badge ms-1" style="--bc:#fef9c3;--tc:#854d0e;"><i class="ri-building-line"></i> Office Restricted</span>`
                : '';
            const cursor = doc.file_url ? 'pointer' : 'default';

            return `
            <li class="list-group-item px-2 py-2 priv-item pub-item"
                style="border-left: 4px solid #3b82f6; border-radius: 6px; margin-bottom: 4px; cursor: ${cursor};"
                ${doc.file_url ? `data-pub-url="${doc.file_url}" data-pub-doc-id="${doc.id}"` : ''}
                title="${doc.file_url ? 'Click to open document' : ''}">
                <div class="d-flex align-items-start gap-2">
                    <div class="flex-shrink-0 pt-1">
                        <div class="avatar-title rounded"
                            style="width:30px;height:30px;display:flex;align-items:center;justify-content:center;background:#dbeafe;color:#3b82f6;">
                            <i class="ri-file-text-line"></i>
                        </div>
                    </div>
                    <div class="flex-grow-1 overflow-hidden">
                        <h6 class="fs-14 mb-0 text-truncate fw-semibold text-dark">${doc.title}</h6>
                        <div class="mt-1">
                            <span class="priv-status-badge" style="--bc:#dbeafe;--tc:#1e40af;"><i class="ri-global-line"></i> Public</span>
                            ${officeBadge}
                        </div>
                        <small class="text-muted d-block text-truncate mt-1">${doc.control_code}</small>
                        <div class="d-flex flex-wrap gap-1 mt-1">${metaDept}${metaOffice}</div>
                    </div>
                    <div class="flex-shrink-0 text-end d-flex flex-column align-items-end gap-1" style="min-width:70px;">
                        <small class="text-muted" style="font-size:0.65rem;white-space:nowrap;">
                            <i class="ri-calendar-line"></i> ${doc.published_at}
                        </small>
                        <a href="${BASE}/change-request/visitors/${doc.id}"
                            target="_blank" class="text-decoration-none" onclick="event.stopPropagation();">
                            <span class="badge bg-primary-subtle text-primary" style="font-size:0.6rem;">
                                <i class="ri-eye-line"></i> ${doc.visitor_count}
                            </span>
                        </a>
                    </div>
                </div>
            </li>`;
        }).join('');

        document.querySelectorAll('#publicDocsList .pub-item[data-pub-url]').forEach(row => {
            row.addEventListener('click', function (e) {
                if (e.target.closest('.dropdown')) return;
                const url = this.dataset.pubUrl;
                const docId = this.dataset.pubDocId;
                if (url) {
                    fetch(ROUTES.privateView, {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF },
                        body: JSON.stringify({ change_request_id: docId })
                    });
                    window.open(url, '_blank');
                }
            });
        });
    });
}

function loadTracking() {
    const search = document.getElementById('trackingSearchInput').value;
    const list = document.getElementById('trackingList');
    list.innerHTML = `<li class="list-group-item text-center text-muted py-4"><i class="fa fa-spinner fa-spin"></i> Loading...</li>`;

    ajaxGet(ROUTES.tracking, { tracking_search: search }).then(res => {
        if (!res.data.length) {
            list.innerHTML = `
                <li class="list-group-item text-center text-muted py-4">
                    <i class="ri-folder-open-line d-block mb-1" style="font-size:1.5rem;"></i>
                    No documents found.
                </li>`;
            return;
        }

        list.innerHTML = res.data.map(cr => {
            const metaDept = cr.dept_name ? `<span class="meta-tag"><i class="ri-building-line"></i> ${cr.dept_name}</span>` : '';
            const metaOffice = cr.office_name ? `<span class="meta-tag"><i class="ri-home-office-line"></i> ${cr.office_name}</span>` : '';
            const encoded = encodeURIComponent(JSON.stringify(cr));

            return `
            <li class="list-group-item px-2 py-2 priv-item pub-item"
                style="border-left: 4px solid #e0f0e3; border-radius: 6px; margin-bottom: 4px; cursor: pointer;"
                data-action="view-approvers"
                data-cr="${encoded}">
                <div class="d-flex align-items-start gap-2">
                    <div class="flex-shrink-0 pt-1">
                        <div class="avatar-title rounded"
                            style="width:30px;height:30px;display:flex;align-items:center;justify-content:center;background:#e0f0e3;color:#559E83;">
                            <i class="ri-file-text-line"></i>
                        </div>
                    </div>
                    <div class="flex-grow-1 overflow-hidden">
                        <h6 class="fs-14 mb-0 text-truncate fw-semibold text-dark">${cr.title}</h6>
                        <div class="mt-1">
                            <span class="priv-status-badge" style="--bc:#e0f0e3;--tc:#559E83;">
                                <i class="ri-global-line"></i> ${cr.status}
                            </span>
                        </div>
                        <small class="text-muted d-block text-truncate mt-1">${cr.doc_code}</small>
                        <div class="d-flex flex-wrap gap-1 mt-1">${metaDept}${metaOffice}</div>
                    </div>
                    <div class="flex-shrink-0 text-end d-flex flex-column align-items-end gap-1" style="min-width:70px;">
                        <small class="text-muted" style="font-size:0.65rem;white-space:nowrap;">
                            <i class="ri-calendar-line"></i> ${cr.created_at}
                        </small>
                    </div>
                </div>
            </li>`;
        }).join('');

        document.querySelectorAll('#trackingList [data-action="view-approvers"]').forEach(row => {
            row.addEventListener('click', function () {
                const cr = JSON.parse(decodeURIComponent(this.dataset.cr));
                openTrackingModal(cr);
            });
        });
    });
}

function openTrackingModal(cr) {
    document.getElementById('vatTitle').textContent =
        `Document Tracking — ${cr.title} — ${cr.id}`;

    const approverRows = cr.approvers && cr.approvers.length
        ? cr.approvers.map(a => {
            let statusBadge;
            if (a.status === 'Pending') {
                statusBadge = `<span class="badge bg-warning">${a.status}</span>`;
            } else if (a.status === 'Approved') {
                statusBadge = `<span class="badge bg-success">${a.status}</span>`;
            } else {
                statusBadge = `<span class="badge bg-danger">${a.status ?? '—'}</span>`;
            }

            const remarks = a.remarks
                ? a.remarks.replace(/\r\n|\r|\n/g, '<br>')
                : '—';

            return `
            <tr>
                <td>${a.level ?? '—'}</td>
                <td>${a.name ?? '—'}</td>
                <td>${a.office}</td>
                <td>${a.start_date}</td>
                <td>${a.transaction_date}</td>
                <td>${remarks}</td>
                <td>${statusBadge}</td>
            </tr>`;
        }).join('')
        : `<tr><td colspan="7" class="text-center text-muted py-3">No approvers assigned.</td></tr>`;

    document.getElementById('vatBody').innerHTML = `
        <p class="mb-1"><strong>Total pages of document:</strong> ${cr.page_count}</p>
        <p class="mb-1"><strong>Office:</strong> ${cr.office_name ?? '—'}</p>
        <p class="mb-3"><strong>Number of supporting documents:</strong> ${cr.supporting_doc_count}</p>
        <div class="card mb-3" style="border: 1px solid #842029;">
            <div class="card-header" style="background-color: #842029;">
                <h6 class="card-title mb-0" style="color: white;">Description</h6>
            </div>
            <div class="card-body">
                ${cr.description
                    ? cr.description.replace(/\r\n|\r|\n/g, '<br>')
                    : '<span class="text-muted">No description provided.</span>'}
            </div>
        </div>
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th>Level</th>
                        <th>Name</th>
                        <th>Offices</th>
                        <th>Start Date</th>
                        <th>Transaction Date</th>
                        <th>Remarks</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    ${approverRows}
                </tbody>
            </table>
        </div>`;

    new bootstrap.Modal(document.getElementById('viewApproversModal')).show();
}

function closeAllDropdowns() {
    document.querySelectorAll('.file-dropdown-menu').forEach(m => m.classList.remove('show'));
    document.querySelectorAll('.file-card').forEach(c => c.classList.remove('dropdown-open'));
}

function rebindDropdowns() {
    document.querySelectorAll('.file-dropdown-menu').forEach(menu => {
        document.body.appendChild(menu);
    });

    document.querySelectorAll('.file-more-btn').forEach(button => {
        button.addEventListener('click', function (e) {
            e.preventDefault();
            e.stopPropagation();

            const fileCard = this.closest('.file-card');
            const cardId = fileCard.dataset.cardId;
            const dropdown = document.querySelector(`.file-dropdown-menu[data-card-id="${cardId}"]`);

            document.querySelectorAll('.file-dropdown-menu').forEach(m => {
                if (m !== dropdown) m.classList.remove('show');
            });
            document.querySelectorAll('.file-card').forEach(c => c.classList.remove('dropdown-open'));

            const rect = this.getBoundingClientRect();
            const dropdownWidth = 200;
            let left = rect.right - dropdownWidth;
            let top = rect.bottom + 4;
            if (left < 8) left = 8;
            if (left + dropdownWidth > window.innerWidth - 8) left = window.innerWidth - dropdownWidth - 8;

            dropdown.style.top = top + 'px';
            dropdown.style.left = left + 'px';
            dropdown.style.position = 'fixed';
            dropdown.classList.toggle('show');
            if (dropdown.classList.contains('show')) fileCard.classList.add('dropdown-open');
        });
    });
}

document.addEventListener('DOMContentLoaded', function () {

    JsBarcode(".barcode").init();

    const viewToggles = document.querySelectorAll('.view-toggle');
    const gridView = document.getElementById('gridView');
    const listView = document.getElementById('listView');

    function setActiveView(view) {
        viewToggles.forEach(btn => btn.classList.toggle('active', btn.dataset.view === view));
        if (view === 'grid') {
            listView.classList.add('d-none');
            setTimeout(() => gridView.classList.remove('d-none'), 50);
        } else {
            gridView.classList.add('d-none');
            setTimeout(() => listView.classList.remove('d-none'), 50);
        }
    }

    const savedView = localStorage.getItem('monitoringDocsView') || 'grid';
    setActiveView(savedView);

    viewToggles.forEach(btn => {
        btn.addEventListener('click', function () {
            const view = this.dataset.view;
            setActiveView(view);
            localStorage.setItem('monitoringDocsView', view);
        });
    });

    function bindSearch(inputId, clearId, btnId, loadFn) {
        const input = document.getElementById(inputId);
        const clear = document.getElementById(clearId);
        const btn = document.getElementById(btnId);
        let timer;
        input.addEventListener('input', function () {
            clear.classList.toggle('d-none', !this.value);
            clearTimeout(timer);
            timer = setTimeout(loadFn, 500);
        });
        clear.addEventListener('click', function () {
            input.value = '';
            clear.classList.add('d-none');
            loadFn();
        });
        btn.addEventListener('click', loadFn);
        input.addEventListener('keydown', e => { if (e.key === 'Enter') loadFn(); });
    }

    bindSearch('pendingSearchInput', 'pendingSearchClear', 'pendingSearchBtn', () => loadPendingCards(1));
    bindSearch('privateSearchInput', 'privateSearchClear', 'privateSearchBtn', loadPrivateDocs);
    bindSearch('publicSearchInput', 'publicSearchClear', 'publicSearchBtn', loadPublicDocs);
    bindSearch('trackingSearchInput', 'trackingSearchClear', 'trackingSearchBtn', loadTracking);

    document.addEventListener('click', function (e) {
        const item = e.target.closest('.file-dropdown-item');
        if (!item) return;
        e.preventDefault();
        e.stopPropagation();

        const action   = item.dataset.action;
        const filePath = item.querySelector('.file-path')?.value;

        if (action === 'display') {
            if (filePath) window.open(`${BASE}/${filePath}`, '_blank');
        }

        if (action === 'approve') {
            const changeRequestId = item.dataset.id;
            const Status          = item.dataset.myStatus;
            if (!changeRequestId) { closeAllDropdowns(); return; }

            if (Status === 'Waiting') {
                Swal.fire({
                    icon: 'warning',
                    title: 'Not Your Turn Yet',
                    html: 'The previous approver must sign the document first before you can proceed.',
                    confirmButtonColor: '#8B0000',
                    confirmButtonText: 'Got it',
                });
                closeAllDropdowns();
                return;
            }

            document.getElementById('dashboardSignPassword').value = '';
            document.getElementById('dashboardSignError').classList.add('d-none');
            const signModal = new bootstrap.Modal(document.getElementById('dashboardSignModal'));
            signModal.show();

            document.getElementById('dashboardSignConfirm').onclick = function () {
                const password = document.getElementById('dashboardSignPassword').value;
                if (!password) {
                    document.getElementById('dashboardSignError').textContent = 'Please enter your password.';
                    document.getElementById('dashboardSignError').classList.remove('d-none');
                    return;
                }
                fetch(ROUTES.confirmPassword, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF },
                    body: JSON.stringify({ password, change_request_id: changeRequestId })
                })
                .then(r => r.json())
                .then(data => {
                    if (data.status === 'success') {
                        signModal.hide();
                        window.location.href = `${ROUTES.signature}/${changeRequestId}`;
                    } else {
                        document.getElementById('dashboardSignError').textContent = 'Incorrect password. Please try again.';
                        document.getElementById('dashboardSignError').classList.remove('d-none');
                    }
                });
            };
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

    document.getElementById('listViewBody').addEventListener('click', function (e) {
        if (e.target.closest('.file-more-btn') || e.target.closest('.file-dropdown-menu')) return;
        document.querySelectorAll('#listView .drive-list-item').forEach(i => i.classList.remove('selected'));
        e.target.closest('.drive-list-item')?.classList.add('selected');
    });

    document.getElementById('listViewBody').addEventListener('dblclick', function (e) {
        if (e.target.closest('.file-more-btn')) return;
        const filePath = e.target.closest('.drive-list-item')?.querySelector('.file-path')?.value;
        if (filePath) window.open(`${BASE}/${filePath}`, '_blank');
    });

    document.addEventListener('show.bs.modal', function () {
        document.querySelectorAll('.dropdown-menu.show').forEach(function (menu) {
            const toggle = menu.previousElementSibling;
            if (toggle) bootstrap.Dropdown.getOrCreateInstance(toggle).hide();
        });
    });

    loadPendingCards(1);
    loadPrivateDocs();
    loadPublicDocs();
    loadTracking();
});

document.addEventListener('keydown', function (e) {
    if (e.key === 'Escape') {
        const openModal = document.querySelector('.modal.show');
        if (openModal) {
            const modalInstance = bootstrap.Modal.getInstance(openModal);
            if (modalInstance) modalInstance.hide();
        }
    }
});
</script>
@endsection