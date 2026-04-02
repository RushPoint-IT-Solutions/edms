@extends('layouts.header')
@section('css')
<link href="{{ asset('login_css/css/plugins/chosen/bootstrap-chosen.css') }}" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
<style>
    .pdf-preview-container {
        position: sticky;
        top: 20px;
        height: calc(100vh - 40px);
        background: #f8f9fa;
        border-radius: 8px;
        overflow: hidden;
        border: 2px solid #e9ecef;
    }
    .pdf-preview-header {
        background: #fff;
        padding: 0;
        border-bottom: 1px solid #e9ecef;
    }
    .preview-tabs {
        display: flex;
        list-style: none;
        margin: 0;
        padding: 0;
    }
    .preview-tab {
        flex: 1;
        padding: 15px;
        text-align: center;
        cursor: pointer;
        border-bottom: 3px solid transparent;
        font-weight: 500;
        transition: all 0.3s;
    }
    .preview-tab:hover {
        background: #f8f9fa;
    }
    .preview-tab.active {
        border-bottom-color: #0ab39c;
        color: #0ab39c;
    }
    .tab-content-item {
        display: none;
        height: 100%;
    }
    .tab-content-item.active {
        display: block;
    }
    .supporting-docs-list {
        height: 100%;
        overflow-y: auto;
    }
    .supporting-doc-viewer {
        height: 100%;
    }
    .supporting-doc-viewer iframe {
        width: 100%;
        height: 100%;
        border: none;
        border-radius: 4px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }
    .pdf-preview-content {
        height: calc(100% - 60px);
        overflow-y: auto;
        padding: 20px;
        position: relative;
        text-align: center;
    }
    .pdf-preview-content iframe {
        width: 100%;
        height: 100%;
        border: none;
        border-radius: 4px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }

    #pdf-container {
        max-width: 100%;
        margin: 0 auto;
    }
    .no-preview {
        display: flex;
        align-items: center;
        justify-content: center;
        height: 100%;
        color: #adb5bd;
        font-size: 16px;
    }

    #pdf-container {
        position: relative;
        display: inline-block;
        width: 100%;
    }

    canvas.pdf-page {
        display: block;
        margin: 10px auto;
        box-shadow: 0 0.125rem 0.25rem rgba(0,0,0,0.075);
    }

    .signature-box {
        position: absolute;
        border: 2px dashed #0d6efd;
        background: rgba(13,110,253,0.1);
        width: 180px;
        height: 80px;
        cursor: move;
        user-select: none;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .signature-box .box-number {
        font-size: 24px;
        color: #0d6efd;
        font-weight: bold;
    }

    .remove-btn {
        position: absolute;
        top: -10px;
        right: -10px;
        width: 24px;
        height: 24px;
        background: #dc3545;
        color: white;
        border-radius: 50%;
        font-size: 16px;
        font-weight: bold;
        line-height: 22px;
        text-align: center;
        cursor: pointer;
        border: 2px solid white;
    }
    .approver-role-select { width: 140px; flex-shrink: 0; }
    .approver-select-wrap { flex: 1; }
    @media (min-width: 992px) {
        .form-column {
            max-width: 45%;
            flex: 0 0 45%;
        }
        .preview-column {
            max-width: 55%;
            flex: 0 0 55%;
        }
    }
</style>
@endsection

@section('content')
<form method="POST" action="{{ url('change-request/store') }}" enctype="multipart/form-data" onsubmit="return prepareSubmit(event)">
    @csrf

    @if($change_request)
        <input type="hidden" name="id" value="{{ $change_request->id }}">
    @endif

    <input type="hidden" name="signature_positions" id="signature-positions-input">

    <div class="row">
        <div class="col-lg-5 form-column">

            <div class="card">
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label" for="document-title-input">Document Title <span class="text-danger">*</span></label>
                        <input type="text" name="title" class="form-control" id="document-title-input"
                            value="{{ old('title', $change_request->title ?? '') }}"
                            placeholder="Enter document title" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label" for="choices-category-input">Document Type <span class="text-danger">*</span></label>
                        <select name="type[]" class="form-select cat" data-choices data-choices-search-false id="choices-category-input" multiple required>
                            <option value=""></option>
                            @foreach ($document_types as $type)
                                <option value="{{ $type->id }}" @if(old('type', $change_request->type ?? '') == $type->id) selected @endif>
                                    {{ $type->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Document Description <span class="text-danger">*</span></label>
                        <textarea name="description" class="form-control" cols="30" rows="5"
                            placeholder="Enter document description" required>{{ old('description', $change_request->description ?? '') }}</textarea>
                        <p class="mt-1 text-end"><small><span id="countPerWord">0</span>/1000</small></p>
                    </div>

                    <div class="row">
                        <div class="col-lg-6">
                            <div class="mb-3">
                                <label for="choices-category-input" class="form-label">Category <span class="text-danger">*</span></label>
                                <select name="category" class="form-select" data-choices data-choices-search-false id="choices-category-input" required>
                                    <option value="">-- Select Category --</option>
                                    <option value="Private" @if(old('category', $change_request->category ?? '') == 'Private') selected @endif>Private</option>
                                    <option value="Public"  @if(old('category', $change_request->category ?? '') == 'Public')  selected @endif>Public</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="mb-3">
                                <label for="choices-status-input" class="form-label">Status <span class="text-danger">*</span></label>
                                <select name="status" class="form-select" data-choices data-choices-search-false id="choices-status-input" required>
                                    <option value="Draft"        @if(old('status', $change_request->status ?? '') == 'Draft')        selected @endif>Draft</option>
                                    <option value="For Approval" @if(old('status', $change_request->status ?? '') == 'For Approval') selected @endif>For Approval</option>
                                    <option value="Approved"     @if(old('status', $change_request->status ?? '') == 'Approved')     selected @endif>Approved</option>
                                </select>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label" for="due-date-input">
                                Due Date <span class="text-muted" style="font-size:0.8rem;">(optional)</span>
                            </label>
                            <input type="date" name="due_date" id="due-date-input" class="form-control"
                                value="{{ old('due_date', optional($change_request)->due_date ? \Carbon\Carbon::parse($change_request->due_date)->format('Y-m-d') : '') }}"
                                min="{{ date('Y-m-d') }}">
                            <div class="form-text text-muted">
                                <i class="ri-information-line"></i>
                                You will receive an alert when this date is within 3 days or has passed.
                            </div>
                        </div>
                    </div>

                    <div class="mb-3" id="department-field" style="display:none;">
                        <label for="department-select" class="form-label">
                            Offices <span class="text-muted" style="font-size:0.8rem;">(optional)</span>
                        </label>
                        <select name="department_id[]" class="form-select cat" id="department-select" multiple>
                            @foreach($departments as $department)
                                <option value="{{ $department->id }}"
                                    @if(in_array($department->id, old('department_id', $change_request ? $change_request->departments->pluck('id')->toArray() : []))) selected @endif>
                                    {{ $department->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="notes" class="form-label">Notes</label>
                        <textarea name="notes" id="notes" class="form-control" cols="30" rows="10"
                            placeholder="Enter remarks of document"></textarea>
                    </div>
                </div>
            </div>

            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">Attached Files <span class="text-danger">*</span></h5>
                </div>
                <div class="card-body">
                    <p class="text-muted mb-3">Add PDF file to preview on the right</p>

                    <div class="mb-3">
                        <label class="form-label">Main Document (PDF)</label>
                        <input name="file" type="file" class="form-control" id="pdf-file-input"
                            accept=".pdf" @if(!$change_request) required @endif>
                    </div>

                    <div>
                        <label class="form-label">Supporting Documents</label>
                        <p class="text-muted small mb-2">You can select multiple files (hold Ctrl/Cmd to select multiple)</p>
                        <input type="file" name="supporting_documents[]" class="form-control"
                            id="supporting-docs-input"
                            accept=".pdf,.doc,.docx,.xls,.xlsx,.png,.jpg,.jpeg" multiple>
                        <div id="selected-files-list" class="mt-3"></div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Signatories <span class="text-danger">*</span></h5>
                </div>
                <div class="card-body">
                    <div id="approvers-wrapper">
                        <div class="approver-row mb-2 d-flex align-items-center gap-2" data-old-level="1">
                            <span class="approver-level badge bg-primary">1</span>
                            <select name="approvers[]" class="form-select approver-select chosen-select approver-select-wrap">
                                <option value="">-- Select Signatories --</option>
                                @if($change_request)
                                    @foreach ($change_request->approvers as $approver)
                                        <option value="{{ $approver->user_id }}" selected>{{ $approver->user->name }}</option>
                                    @endforeach
                                @else
                                    @foreach($approvers as $user)
                                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                                    @endforeach
                                @endif
                            </select>
                            <select name="approver_roles[]" class="form-select approver-role-select">
                                <option value="For Signature">For Signature</option>
                                <option value="For Receiving">For Receiving</option>
                            </select>
                            <button type="button" class="btn btn-success btn-sm place-signature-btn" data-level="1">
                                <i class="ri-add-circle-line"></i>
                            </button>
                            <button type="button" class="btn btn-danger btn-sm remove-approver">
                                <i class="ri-delete-bin-2-line"></i>
                            </button>
                        </div>
                    </div>
                    <button type="button" id="add-approver" class="btn btn-outline-primary btn-sm mt-2">
                        <i class="ri-add-line"></i> Add Approver
                    </button>
                </div>
            </div>

            <div class="text-end mb-4">
                <button type="submit" class="btn btn-secondary w-sm" name="save_as_draft">Save as Draft</button>
                <button type="submit" class="btn btn-success w-sm">Upload</button>
            </div>
        </div>

        <div class="col-lg-7 preview-column mb-4">
            <div class="pdf-preview-container">
                <div class="pdf-preview-header">
                    <ul class="preview-tabs">
                        <li class="preview-tab active" data-tab="main-doc">
                            <i class="ri-file-pdf-line me-1"></i> Main Document
                        </li>
                        <li class="preview-tab" data-tab="supporting-docs">
                            <i class="ri-folder-line me-1"></i> Supporting Docs
                        </li>
                    </ul>
                </div>
                <div class="pdf-preview-content">
                    <div class="tab-content-item active" id="main-doc-content">
                        @if($change_request)
                            <div id="pdf-container">
                                <iframe src="{{ url($change_request->file) }}" type="application/pdf"></iframe>
                            </div>
                        @else
                            <div class="no-preview" id="main-doc-preview">
                                <div>
                                    <i class="ri-file-pdf-line" style="font-size:48px;"></i>
                                    <p class="mt-2">Upload a PDF to preview</p>
                                </div>
                            </div>
                            <div id="pdf-container" style="display:none;"></div>
                        @endif
                    </div>

                    <div class="tab-content-item" id="supporting-docs-content">
                        @if($change_request)
                            @foreach ($change_request->supporting_documents as $supporting_docs)
                                <iframe src="{{ url($supporting_docs->file) }}" type="application/pdf"></iframe>
                            @endforeach
                        @else
                            <div class="no-preview" id="supporting-docs-empty">
                                <div>
                                    <i class="ri-folder-open-line" style="font-size:48px;"></i>
                                    <p class="mt-2">No supporting documents uploaded</p>
                                </div>
                            </div>
                        @endif
                        <div class="supporting-docs-list" id="supporting-docs-list" style="display:none;"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection

@section('js')
<script src="{{ asset('assets/libs/@ckeditor/ckeditor5-build-classic/build/ckeditor.js') }}" defer></script>
<script src="{{ asset('login_css/js/plugins/chosen/chosen.jquery.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/pdfjs-dist@3.11.174/build/pdf.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
let pdfDoc = null;
let scale = 1.0;
let placingLevel = null;
let approverBoxes = {};
let approverCount = 1;

$("[name='description']").on("input", function () {
    countToWords($(this).val());
});

function countToWords(value) {
    const trimVal = value.trim();
    const wordsArray = trimVal.split(/\s+/);
    const count = trimVal === "" ? 0 : wordsArray.length;
    $("#countPerWord").text(count);
    if (wordsArray.length > 1000) alert("Word limit is exceeded");
}

function prepareSubmit(event) {
    event.preventDefault();

    $('select[name="approvers[]"]').trigger('chosen:updated');

    const selectedStatus = document.querySelector('select[name="status"]').value;
    const isApproved = selectedStatus === 'Approved';

    const approverSelects = document.querySelectorAll('[name="approvers[]"]');
    let hasApprover = false;
    approverSelects.forEach(s => { if (s.value) hasApprover = true; });

    $('.chosen-select, [class^="chosen-select-"]').each(function() {
        $(this).trigger('chosen:updated');
    });

    if (isApproved) {
        if (!hasApprover) {
            Swal.fire({
                icon: 'warning', title: 'Approver Required!',
                html: `Please select an <strong>approver</strong> before submitting.<br><br>
                       An approver is required for <strong>accountability purposes</strong>
                       even if the document is already marked as <strong>Approved</strong>.`,
                confirmButtonText: "OK, I'll select one", confirmButtonColor: '#0ab39c'
            });
            return false;
        }
        document.getElementById('signature-positions-input').value = JSON.stringify([]);
        event.target.submit();
        return false;
    }

    if (!hasApprover) {
        Swal.fire({
            icon: 'warning', title: 'Approver Required!',
            html: `Please select an <strong>approver</strong> before submitting.`,
            confirmButtonText: "OK, I'll select one", confirmButtonColor: '#0ab39c'
        });
        return false;
    }

    const signatureData = [];
    const pdfContainer  = document.getElementById('pdf-container');

    document.querySelectorAll('[name="approvers[]"]').forEach(sel => {
        $(sel).trigger('chosen:updated');
    });

    Object.keys(approverBoxes).forEach(level => {
        approverBoxes[level].forEach(box => {
            const btn = document.querySelector(`.place-signature-btn[data-level="${level}"]`);
            if (!btn) return;
            const approverRow = btn.closest('.approver-row');
            const selectEl = approverRow.querySelector('[name="approvers[]"]');
            
            const userId = selectEl.value || $(selectEl).val();
            
            if (!userId) return;
            const canvases = pdfContainer.querySelectorAll('canvas.pdf-page');
            let pageNumber = 1, cumulativeHeight = 0;
            const boxTop = parseFloat(box.style.top);

            for (let i = 0; i < canvases.length; i++) {
                if (boxTop < cumulativeHeight + canvases[i].height) { pageNumber = i + 1; break; }
                cumulativeHeight += canvases[i].height + 10;
            }

            signatureData.push({
                user_id: userId, page_number: pageNumber,
                x_position: parseFloat(box.style.left) / scale,
                y_position: parseFloat(box.style.top)  / scale,
                width: 180 / scale, height: 80 / scale
            });
        });
    });

    const dataSignature = document.getElementById('signature-positions-input');
    dataSignature.value = JSON.stringify(signatureData);

    if (dataSignature.value === '[]') {
        Swal.fire({
            icon: 'warning', title: 'Signature Placement Required',
            text: 'Please place at least one signature box on the document before submitting.',
            confirmButtonColor: '#0ab39c'
        });
        return false;
    }

    event.target.submit();
    return false;
}

document.addEventListener('DOMContentLoaded', function () {

    const pdfInput = document.getElementById('pdf-file-input');
    const supportingDocsInput = document.getElementById('supporting-docs-input');
    const mainDocPreview = document.getElementById('main-doc-preview');
    const supportingDocsGrid  = document.getElementById('supporting-docs-list');
    const supportingDocsEmpty = document.getElementById('supporting-docs-empty');
    const pdfContainer = document.getElementById('pdf-container');
    const selectedFilesList = document.getElementById('selected-files-list');
    let supportingFiles = [];

    document.querySelectorAll('.preview-tab').forEach(tab => {
        tab.addEventListener('click', function () {
            const tabName = this.getAttribute('data-tab');
            document.querySelectorAll('.preview-tab').forEach(t => t.classList.remove('active'));
            this.classList.add('active');
            document.querySelectorAll('.tab-content-item').forEach(c => c.classList.remove('active'));
            document.getElementById(tabName + '-content').classList.add('active');
        });
    });

    pdfInput.addEventListener('change', async function (e) {
        const file = e.target.files[0];
        if (file && file.type === 'application/pdf') {
            if (mainDocPreview) mainDocPreview.style.display = 'none';
            pdfContainer.style.display = 'block';
            await loadPdf(URL.createObjectURL(file));
        } else {
            if (mainDocPreview) { mainDocPreview.style.display = 'flex'; pdfContainer.style.display = 'none'; }
        }
    });

    async function loadPdf(url) {
        try {
            pdfDoc = await pdfjsLib.getDocument(url).promise;
            pdfContainer.innerHTML = "";
            approverBoxes = {};

            const containerWidth = pdfContainer.offsetWidth;

            for (let pageNum = 1; pageNum <= pdfDoc.numPages; pageNum++) {
                await renderPage(pageNum, containerWidth);
            }

            pdfContainer.onclick = (e) => {
                if (placingLevel === null) return;
                addSignatureBox(e, placingLevel);
                placingLevel = null;
                document.querySelectorAll('.place-signature-btn').forEach(b => {
                    b.classList.remove('btn-warning');
                    b.classList.add('btn-success');
                });
            };
        } catch (error) {
            console.error("Error loading PDF:", error);
            Swal.fire({ icon: 'error', title: 'PDF Loading Error', text: 'Error loading PDF: ' + error.message, confirmButtonColor: '#0ab39c' });
        }
    }

    async function renderPage(pageNum, containerWidth) {
        const page = await pdfDoc.getPage(pageNum);
        const unscaledViewport = page.getViewport({ scale: 1 });
        const pageScale = (containerWidth - 40) / unscaledViewport.width;
        scale = pageScale;

        const viewport = page.getViewport({ scale: pageScale });
        const canvas = document.createElement("canvas");
        canvas.className = "pdf-page";
        canvas.height = viewport.height;
        canvas.width = viewport.width;

        await page.render({ canvasContext: canvas.getContext("2d"), viewport }).promise;
        pdfContainer.appendChild(canvas);
    }

    function addSignatureBox(e, level) {
        const rect = pdfContainer.getBoundingClientRect();
        const x = e.clientX - rect.left + pdfContainer.parentElement.scrollLeft;
        const y = e.clientY - rect.top + pdfContainer.parentElement.scrollTop;

        const sigBox = document.createElement("div");
        sigBox.classList.add("signature-box");
        sigBox.style.left = (x - 90) + "px";
        sigBox.style.top = (y - 40) + "px";
        sigBox.dataset.level = level;

        const number = document.createElement('span');
        number.className = 'box-number';
        number.textContent = level;
        sigBox.appendChild(number);

        const removeBtn = document.createElement("div");
        removeBtn.classList.add("remove-btn");
        removeBtn.textContent = "×";
        removeBtn.onclick = (ev) => {
            ev.stopPropagation();
            pdfContainer.removeChild(sigBox);
            if (approverBoxes[level]) {
                const idx = approverBoxes[level].indexOf(sigBox);
                if (idx > -1) approverBoxes[level].splice(idx, 1);
            }
        };
        sigBox.appendChild(removeBtn);
        makeDraggable(sigBox);
        pdfContainer.appendChild(sigBox);

        if (!approverBoxes[level]) approverBoxes[level] = [];
        approverBoxes[level].push(sigBox);
    }

    function makeDraggable(el) {
        let offsetX, offsetY;
        el.addEventListener("mousedown", (e) => {
            if (e.target.classList.contains("remove-btn")) return;
            const rect = pdfContainer.getBoundingClientRect();
            const boxRect = el.getBoundingClientRect();
            offsetX = e.clientX - boxRect.left;
            offsetY = e.clientY - boxRect.top;

            document.onmousemove = (mv) => {
                el.style.left = (mv.clientX - rect.left  + pdfContainer.parentElement.scrollLeft - offsetX) + "px";
                el.style.top = (mv.clientY - rect.top   + pdfContainer.parentElement.scrollTop  - offsetY) + "px";
            };
            document.onmouseup = () => { document.onmousemove = null; document.onmouseup = null; };
        });
    }

    function syncFilesToInput() {
        const dt = new DataTransfer();
        supportingFiles.forEach(f => dt.items.add(f));
        supportingDocsInput.files = dt.files;
    }

    function renderSupportingPreviews() {
        supportingDocsGrid.innerHTML = '';

        if (supportingFiles.length === 0) {
            if (supportingDocsEmpty) supportingDocsEmpty.style.display = 'flex';
            supportingDocsGrid.style.display = 'none';
            selectedFilesList.innerHTML = '';
            return;
        }

        if (supportingDocsEmpty) supportingDocsEmpty.style.display = 'none';
        supportingDocsGrid.style.display = 'block';

        selectedFilesList.innerHTML = `
            <div class="alert alert-info">
                <strong>${supportingFiles.length} file(s) selected:</strong>
                <ul class="mb-0 mt-2" style="max-height:200px;overflow-y:auto;list-style:none;padding-left:0;">
                    ${supportingFiles.map((f, i) => `
                        <li class="d-flex align-items-center justify-content-between py-1 border-bottom" data-index="${i}">
                            <span style="font-size:13px;"><i class="ri-file-line me-1"></i>${f.name}
                                <span class="text-muted">(${(f.size/1024).toFixed(2)} KB)</span>
                            </span>
                            <button type="button" class="btn btn-danger btn-xs py-0 px-1 ms-2 remove-supporting-file"
                                data-index="${i}" style="font-size:11px;line-height:1.5;">
                                <i class="ri-close-line"></i> Remove
                            </button>
                        </li>`).join('')}
                </ul>
            </div>`;

        supportingFiles.forEach((file, index) => {
            if (file.type === 'application/pdf') {
                const viewer = document.createElement('div');
                viewer.className = 'supporting-doc-viewer mb-3';
                viewer.style.height = '600px';
                viewer.dataset.index = index;
                viewer.innerHTML = `
                    <div class="mb-2 px-3 py-2 bg-light rounded d-flex align-items-center justify-content-between">
                        <small class="text-muted"><i class="ri-file-pdf-line me-1"></i>${file.name}</small>
                        <button type="button" class="btn btn-danger btn-xs py-0 px-2 remove-supporting-file"
                            data-index="${index}" style="font-size:11px;">
                            <i class="ri-close-line"></i> Remove
                        </button>
                    </div>
                    <iframe src="${URL.createObjectURL(file)}" type="application/pdf"></iframe>`;
                supportingDocsGrid.appendChild(viewer);
            }
        });

        supportingDocsGrid.querySelectorAll('.remove-supporting-file').forEach(btn => {
            btn.addEventListener('click', function () {
                supportingFiles.splice(parseInt(this.dataset.index), 1);
                syncFilesToInput();
                renderSupportingPreviews();
            });
        });
        selectedFilesList.querySelectorAll('.remove-supporting-file').forEach(btn => {
            btn.addEventListener('click', function () {
                supportingFiles.splice(parseInt(this.dataset.index), 1);
                syncFilesToInput();
                renderSupportingPreviews();
            });
        });
    }

    supportingDocsInput.addEventListener('change', function (e) {
        Array.from(e.target.files).forEach(newFile => {
            if (!supportingFiles.some(f => f.name === newFile.name && f.size === newFile.size)) {
                supportingFiles.push(newFile);
            }
        });
        syncFilesToInput();
        renderSupportingPreviews();
    });

    const wrapper = document.getElementById('approvers-wrapper');
    const addBtn = document.getElementById('add-approver');

    $('.chosen-select').chosen({ width: "100%", placeholder_text_single: "-- Select Approver --" });

    function updateLevels() {
        wrapper.querySelectorAll('.approver-row').forEach((row, index) => {
            const level = index + 1;
            const oldLevel = row.dataset.oldLevel;
            row.querySelector('.approver-level').textContent = level;
            row.querySelector('.place-signature-btn').dataset.level = level;

            if (oldLevel && approverBoxes[oldLevel]) {
                approverBoxes[level] = approverBoxes[oldLevel];
                delete approverBoxes[oldLevel];
                approverBoxes[level].forEach(box => {
                    box.dataset.level = level;
                    box.querySelector('.box-number').textContent = level;
                });
            }
            row.dataset.oldLevel = level;
        });
    }

    addBtn.addEventListener('click', function () {
        const level = wrapper.querySelectorAll('.approver-row').length + 1;
        approverCount++;

        const newRow = document.createElement('div');
        newRow.classList.add('approver-row', 'mb-2', 'd-flex', 'align-items-center', 'gap-2');
        newRow.dataset.oldLevel = level;
        newRow.innerHTML = `
            <span class="approver-level badge bg-primary">${level}</span>
            <select name="approvers[]" class="form-select approver-select chosen-select-${approverCount} approver-select-wrap">
                <option value="">-- Select Approver --</option>
                @foreach($approvers as $user)
                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                @endforeach
            </select>
            <select name="approver_roles[]" class="form-select approver-role-select">
                <option value="For Signature">For Signature</option>
                <option value="For Receiving">For Receiving</option>
            </select>
            <button type="button" class="btn btn-success btn-sm place-signature-btn" data-level="${level}">
                <i class="ri-add-circle-line"></i>
            </button>
            <button type="button" class="btn btn-danger btn-sm remove-approver">
                <i class="ri-delete-bin-2-line"></i>
            </button>`;
        wrapper.appendChild(newRow);

        $(`.chosen-select-${approverCount}`).chosen({ width: "100%", placeholder_text_single: "-- Select Approver --" });
        updateLevels();
    });

    wrapper.addEventListener('click', function (e) {
        if (e.target.closest('.remove-approver')) {
            const row = e.target.closest('.approver-row');
            const level = row.querySelector('.place-signature-btn').dataset.level;
            if (approverBoxes[level]) {
                approverBoxes[level].forEach(box => { if (box.parentElement) box.parentElement.removeChild(box); });
                delete approverBoxes[level];
            }
            $(row.querySelector('.approver-select')).chosen('destroy');
            row.remove();
            updateLevels();
        }

        if (e.target.closest('.place-signature-btn')) {
            const btn = e.target.closest('.place-signature-btn');
            placingLevel = btn.dataset.level;
            document.querySelectorAll('.place-signature-btn').forEach(b => {
                b.classList.remove('btn-warning');
                b.classList.add('btn-success');
            });
            btn.classList.replace('btn-success', 'btn-warning');
            Swal.fire({
                icon: 'info', title: 'Place Signature Box',
                html: `Click on the PDF to place signature box for <strong>Approver ${placingLevel}</strong>`,
                confirmButtonText: 'OK', confirmButtonColor: '#0ab39c'
            });
        }
    });

    wrapper.querySelectorAll('.approver-row').forEach((row, index) => { row.dataset.oldLevel = index + 1; });

    const categorySelect  = document.querySelector('select[name="category"]');
    const departmentField = document.getElementById('department-field');

    function toggleDepartmentField() {
        const show = categorySelect && categorySelect.value === 'Public';
        departmentField.style.display = show ? 'block' : 'none';
        if (!show) $('#department-select').val([]).trigger('chosen:updated');
    }

    setTimeout(toggleDepartmentField, 100);
    if (categorySelect) categorySelect.addEventListener('change', toggleDepartmentField);
    $('select[name="category"]').on('change', toggleDepartmentField);

    $(document).on("change", "[name='approver_roles[]']", function () {
        const btn = $(this).closest(".approver-row").find(".place-signature-btn");
        $(this).val() === "For Receiving" ? btn.hide() : btn.show();
    });

    $(".cat").chosen({ width: "100%" });

    @if($change_request)
    loadPdf('{{ url($change_request->file) }}');
    @endif
});
</script>
@endsection