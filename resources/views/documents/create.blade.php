@extends('layouts.header')
@section('css')
 {{-- <link href="{{asset('/assets/libs/dropzone/dropzone.css')}}" rel="stylesheet" type="text/css" /> --}}
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
                        <input type="text" name="title" class="form-control" id="document-title-input" value="{{ old('title', $change_request->title ?? '' ) }}" placeholder="Enter document title" required>
                    </div>
    
                    <div class="mb-3">
                        <label class="form-label" for="document-type-input">Document Type <span class="text-danger">*</span></label>
                        <select name="type" class="form-select cat" data-choices data-choices-search-false id="choices-category-input" required>
                            <option value=""></option>
                            @foreach ($document_types as $type)
                                <option value="{{ $type->id }}" @if(old('type', $change_request->type ?? '') == $type->id) selected @endif>{{ $type->name }}</option>
                            @endforeach
                        </select>
                    </div>
    
                    <div class="mb-3">
                        <label class="form-label">Document Description <span class="text-danger">*</span></label>
                        <textarea name="description" class="form-control" cols="30" rows="5" placeholder="Enter document description" required>{{ old('description', $change_request->description ?? '') }}</textarea>
                    </div>
    
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="mb-3">
                                <label for="choices-category-input" class="form-label">Category <span class="text-danger">*</span></label>
                                <select name="category" class="form-select" data-choices data-choices-search-false id="choices-category-input" required>
                                    <option value="">-- Select Category --</option>
                                    <option value="Personal" @if(old('category', $change_request->category ?? '') == "Personal") selected @endif>Personal</option>
                                    <option value="Departmental" @if(old('category', $change_request->category ?? '') == "Departmental") selected @endif>Departmental</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="mb-3">
                                <label for="choices-status-input" class="form-label">Status <span class="text-danger">*</span></label>
                                <select name="status" class="form-select" data-choices data-choices-search-false id="choices-status-input" required>
                                    <option value="Draft" selected @if(old('status', $change_request->status ?? '') == "Draft") selected @endif>Draft</option>
                                    <option value="For Approval" @if(old('status', $change_request->status ?? '') == "For Approval") selected @endif>For Approval</option>
                                    <option value="Approved" @if(old('status', $change_request->status ?? '') == "Approved") selected @endif>Approved</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="choices-privacy-status-input" class="form-label">Access</label>
                        <select name="privacy" class="form-select" data-choices data-choices-search-false id="choices-privacy-status-input">
                            <option value="Private" selected @if(old('privacy', $change_request->privacy ?? '') == "Private") selected @endif>Private</option>
                            <option value="Team" @if(old('privacy', $change_request->privacy ?? '') == "Team") selected @endif>Team</option>
                            <option value="Public" @if(old('privacy', $change_request->privacy ?? '') == "Public") selected @endif>Public</option>
                        </select>
                    </div>
                </div>
            </div>
    
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Approvers <span class="text-danger">*</span></h5>
                </div>
                <div class="card-body">
                    <div id="approvers-wrapper">
                        <div class="approver-row mb-2 d-flex align-items-center gap-2">
                            <span class="approver-level badge bg-primary">1</span>
                            <select name="approvers[]" class="form-select approver-select chosen-select" style="flex: 1;" required>
                                <option value="">-- Select Approver --</option>
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
    
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">Attached Files <span class="text-danger">*</span></h5>
                </div>
                <div class="card-body">
                    <p class="text-muted mb-3">Add PDF file to preview on the right</p>
                    
                    <div class="mb-3">
                        <label class="form-label">Main Document (PDF)</label>
                        <input name="file" type="file" class="form-control" id="pdf-file-input" accept=".pdf" @if(!$change_request) required @endif>
                    </div>

                    <div>
                        <label class="form-label">Supporting Documents</label>
                        <p class="text-muted small mb-2">You can select multiple files (hold Ctrl/Cmd to select multiple)</p>
                        <input type="file" name="supporting_documents[]" class="form-control" id="supporting-docs-input" accept=".pdf,.doc,.docx,.xls,.xlsx,.png,.jpg,.jpeg" multiple>
                        <div id="selected-files-list" class="mt-3"></div>
                    </div>
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
                                <i class="ri-file-pdf-line" style="font-size: 48px;"></i>
                                <p class="mt-2">Upload a PDF to preview</p>
                            </div>
                        </div>
                        <div id="pdf-container" style="display: none;"></div>
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
                                <i class="ri-folder-open-line" style="font-size: 48px;"></i>
                                <p class="mt-2">No supporting documents uploaded</p>
                            </div>
                        </div>
                        @endif
                        <div class="supporting-docs-list" id="supporting-docs-list" style="display: none;"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>

<script src="https://cdn.jsdelivr.net/npm/pdfjs-dist@3.11.174/build/pdf.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
let pdfDoc = null;
let scale = 1.0;
let placingLevel = null;
let approverBoxes = {};

function prepareSubmit(event) {
    // event.preventDefault()

    const signatureData = [];
    const pdfContainer = document.getElementById('pdf-container');
    
    Object.keys(approverBoxes).forEach(level => {
        approverBoxes[level].forEach(box => {
            const approverRow = document.querySelector(`[data-level="${level}"]`).closest('.approver-row');
            const userId = approverRow.querySelector('.approver-select').value;
            
            const canvases = pdfContainer.querySelectorAll('canvas.pdf-page');
            let pageNumber = 1;
            let cumulativeHeight = 0;
            
            const boxTop = parseFloat(box.style.top);
            
            for (let i = 0; i < canvases.length; i++) {
                const canvasHeight = canvases[i].height;
                const canvasMargin = 10;
                
                if (boxTop < cumulativeHeight + canvasHeight) {
                    pageNumber = i + 1;
                    break;
                }
                cumulativeHeight += canvasHeight + canvasMargin;
            }
            
            signatureData.push({
                user_id: userId,
                page_number: pageNumber,
                x_position: parseFloat(box.style.left) / scale,
                y_position: parseFloat(box.style.top) / scale,
                width: 180 / scale,
                height: 80 / scale
            });
        });
    });
    
    const dataSignature = document.getElementById('signature-positions-input')
    dataSignature.value = JSON.stringify(signatureData);
    
    if (dataSignature.value == "[]") {
        alert('Signature placement is required.')
        return false;
    }
    else 
    {
        if (typeof show === 'function') {
            show();
        }
        return true;
    }
}

document.addEventListener('DOMContentLoaded', function () {
    const pdfInput = document.getElementById('pdf-file-input');
    const supportingDocsInput = document.getElementById('supporting-docs-input');
    const mainDocPreview = document.getElementById('main-doc-preview');
    const supportingDocsGrid = document.getElementById('supporting-docs-list');
    const supportingDocsEmpty = document.getElementById('supporting-docs-empty');
    const pdfContainer = document.getElementById('pdf-container');
    
    let supportingFiles = [];

    document.querySelectorAll('.preview-tab').forEach(tab => {
        tab.addEventListener('click', function() {
            const tabName = this.getAttribute('data-tab');
            
            document.querySelectorAll('.preview-tab').forEach(t => t.classList.remove('active'));
            this.classList.add('active');
            
            document.querySelectorAll('.tab-content-item').forEach(c => c.classList.remove('active'));
            document.getElementById(tabName + '-content').classList.add('active');
        });
    });

    pdfInput.addEventListener('change', async function(e) {
        const file = e.target.files[0];
        
        if (file && file.type === 'application/pdf') {
            const fileURL = URL.createObjectURL(file);
            
            if (mainDocPreview) {
                mainDocPreview.style.display = 'none';
            }
            pdfContainer.style.display = 'block';
            
            await loadPdf(fileURL);
        } else {
            if (mainDocPreview) {
                mainDocPreview.style.display = 'flex';
                pdfContainer.style.display = 'none';
            }
        }
    });

    async function loadPdf(url) {
        try {
            const loadingTask = pdfjsLib.getDocument(url);
            pdfDoc = await loadingTask.promise;

            pdfContainer.innerHTML = "";
            
            approverBoxes = {};
            
            const containerWidth = pdfContainer.offsetWidth;
            
            for (let pageNum = 1; pageNum <= pdfDoc.numPages; pageNum++) {
                const page = await pdfDoc.getPage(pageNum);
                
                const unscaledViewport = page.getViewport({ scale: 1 });
                const pageScale = (containerWidth - 40) / unscaledViewport.width;
                scale = pageScale;
                
                const viewport = page.getViewport({ scale: pageScale });
                const canvas = document.createElement("canvas");
                canvas.className = "pdf-page";
                const context = canvas.getContext("2d");
                canvas.height = viewport.height;
                canvas.width = viewport.width;
                await page.render({ canvasContext: context, viewport }).promise;
                pdfContainer.appendChild(canvas);
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
            Swal.fire({
                icon: 'error',
                title: 'PDF Loading Error',
                text: 'Error loading PDF: ' + error.message,
                confirmButtonColor: '#0ab39c'
            });
        }
    }

    function addSignatureBox(e, level) {
        const rect = pdfContainer.getBoundingClientRect();
        const x = e.clientX - rect.left + pdfContainer.parentElement.scrollLeft;
        const y = e.clientY - rect.top + pdfContainer.parentElement.scrollTop;

        const sigBox = document.createElement("div");
        sigBox.classList.add("signature-box");
        sigBox.style.left = x - 90 + "px";
        sigBox.style.top = y - 40 + "px";
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
                const index = approverBoxes[level].indexOf(sigBox);
                if (index > -1) {
                    approverBoxes[level].splice(index, 1);
                }
            }
        };
        sigBox.appendChild(removeBtn);

        makeDraggable(sigBox);
        pdfContainer.appendChild(sigBox);

        if (!approverBoxes[level]) {
            approverBoxes[level] = [];
        }
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
            
            document.onmousemove = (moveEvent) => {
                const newLeft = moveEvent.clientX - rect.left + pdfContainer.parentElement.scrollLeft - offsetX;
                const newTop = moveEvent.clientY - rect.top + pdfContainer.parentElement.scrollTop - offsetY;
                
                el.style.left = newLeft + "px";
                el.style.top = newTop + "px";
            };
            document.onmouseup = () => {
                document.onmousemove = null;
                document.onmouseup = null;
            };
        });
    }

    const selectedFilesList = document.getElementById('selected-files-list');
    
    function updateFilesList() {
        if (supportingFiles.length > 0) {
            selectedFilesList.innerHTML = `
                <div class="alert alert-info">
                    <strong>${supportingFiles.length} file(s) selected:</strong>
                    <ul class="mb-0 mt-2" style="max-height: 150px; overflow-y: auto;">
                        ${supportingFiles.map(f => `<li>${f.name} (${(f.size / 1024).toFixed(2)} KB)</li>`).join('')}
                    </ul>
                </div>
            `;
        } else {
            selectedFilesList.innerHTML = '';
        }
    }
    
    supportingDocsInput.addEventListener('change', function(e) {
        supportingFiles = Array.from(e.target.files);
        updateFilesList();
        
        if (supportingFiles.length > 0) {
            supportingDocsEmpty.style.display = 'none';
            supportingDocsGrid.style.display = 'block';
            
            supportingDocsGrid.innerHTML = '';
            
            supportingFiles.forEach((file, index) => {
                if (file.type === 'application/pdf') {
                    const fileURL = URL.createObjectURL(file);
                    const viewer = document.createElement('div');
                    viewer.className = 'supporting-doc-viewer mb-3';
                    viewer.style.height = '600px';
                    viewer.innerHTML = `
                        <div class="mb-2 px-3 py-2 bg-light rounded">
                            <small class="text-muted"><i class="ri-file-pdf-line me-1"></i>${file.name}</small>
                        </div>
                        <iframe src="${fileURL}" type="application/pdf"></iframe>
                    `;
                    supportingDocsGrid.appendChild(viewer);
                }
            });
        } else {
            supportingDocsEmpty.style.display = 'flex';
            supportingDocsGrid.style.display = 'none';
        }
    });

    @if($change_request)
    loadPdf('{{ url($change_request->file) }}');
    @endif
});
</script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const wrapper = document.getElementById('approvers-wrapper');
    const addBtn = document.getElementById('add-approver');
    let approverCount = 1;

    function initializeChosenSelects() {
        $('.chosen-select').chosen({
            width: "100%",
            placeholder_text_single: "-- Select Approver --"
        });
    }

    initializeChosenSelects();

    function updateLevels() {
        const rows = wrapper.querySelectorAll('.approver-row');
        rows.forEach((row, index) => {
            const level = index + 1;
            row.querySelector('.approver-level').textContent = level;
            row.querySelector('.place-signature-btn').dataset.level = level;
            
            const oldLevel = row.dataset.oldLevel;
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
        const count = wrapper.querySelectorAll('.approver-row').length;
        const level = count + 1;
        approverCount++;
        
        const newRow = document.createElement('div');
        newRow.classList.add('approver-row', 'mb-2', 'd-flex', 'align-items-center', 'gap-2');
        newRow.dataset.oldLevel = level;
        newRow.innerHTML = `
            <span class="approver-level badge bg-primary">${level}</span>
            <select name="approvers[]" class="form-select approver-select chosen-select-${approverCount}" style="flex: 1;" required>
                <option value="">-- Select Approver --</option>
                @foreach($approvers as $user)
                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                @endforeach
            </select>
            <button type="button" class="btn btn-success btn-sm place-signature-btn" data-level="${level}">
                <i class="ri-add-circle-line"></i>
            </button>
            <button type="button" class="btn btn-danger btn-sm remove-approver">
                <i class="ri-delete-bin-2-line"></i>
            </button>
        `;
        wrapper.appendChild(newRow);
        
        $(`.chosen-select-${approverCount}`).chosen({
            width: "100%",
            placeholder_text_single: "-- Select Approver --"
        });
        
        updateLevels();
    });

    wrapper.addEventListener('click', function (e) {
        if (e.target.closest('.remove-approver')) {
            const row = e.target.closest('.approver-row');
            const level = row.querySelector('.place-signature-btn').dataset.level;
            
            if (approverBoxes[level]) {
                approverBoxes[level].forEach(box => {
                    if (box.parentElement) {
                        box.parentElement.removeChild(box);
                    }
                });
                delete approverBoxes[level];
            }
            
            const selectElement = row.querySelector('.approver-select');
            $(selectElement).chosen('destroy');
            
            row.remove();
            updateLevels();
        }

        if (e.target.closest('.place-signature-btn')) {
            const btn = e.target.closest('.place-signature-btn');
            const level = btn.dataset.level;
            placingLevel = level;
            
            document.querySelectorAll('.place-signature-btn').forEach(b => {
                b.classList.remove('btn-warning');
                b.classList.add('btn-success');
            });
            btn.classList.remove('btn-success');
            btn.classList.add('btn-warning');
            
            Swal.fire({
                icon: 'info',
                title: 'Place Signature Box',
                html: `Click on the PDF to place signature box for <strong>Approver ${level}</strong>`,
                confirmButtonText: 'OK',
                confirmButtonColor: '#0ab39c'
            });
        }
    });

    const rows = wrapper.querySelectorAll('.approver-row');
    rows.forEach((row, index) => {
        row.dataset.oldLevel = index + 1;
    });
});
</script>
@endsection
@section('js')
    <script src="{{asset('assets/libs/@ckeditor/ckeditor5-build-classic/build/ckeditor.js')}}"></script>
    <script src="{{ asset('login_css/js/plugins/chosen/chosen.jquery.js') }}"></script>
    {{-- <script src="{{asset('assets/libs/dropzone/dropzone-min.js')}}"></script> --}}

    <script>
        $(document).ready(function() {
            $(".cat").chosen({
                width: "100%"
            });
        })
    </script>
@endsection