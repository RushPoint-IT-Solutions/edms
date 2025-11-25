@extends('layouts.header')
@section('css')
 <link href="{{asset('/assets/libs/dropzone/dropzone.css')}}" rel="stylesheet" type="text/css" />
 <link rel="stylesheet" href="{{ asset('login_design/css/file-input-preview.css') }}">
 <link href="{{ asset('login_css/css/plugins/chosen/bootstrap-chosen.css') }}" rel="stylesheet">
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
        text-align: center;
    }
    .pdf-preview-content iframe {
        width: 100%;
        height: 100%;
        border: none;
        border-radius: 4px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }
    .no-preview {
        display: flex;
        align-items: center;
        justify-content: center;
        height: 100%;
        color: #adb5bd;
        font-size: 16px;
    }
 </style>
@endsection
@section('content')
<form method="POST" action="{{ url('change-request/store') }}" enctype="multipart/form-data" onsubmit="show()">
    @csrf 

    <div class="row">
        <div class="col-lg-5">
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
                        <input type="text" name="title" class="form-control" id="document-title-input" value="{{ old('title') }}" placeholder="Enter document title" required>
                    </div>
    
                    <div class="mb-3">
                        <label class="form-label" for="document-type-input">Document Type <span class="text-danger">*</span></label>
                        {{-- <input type="text" name="type" class="form-control" id="document-type-input" value="{{ old('type') }}" placeholder="Enter document type" readonly> --}}
                        <select name="type" class="form-select cat" data-choices data-choices-search-false id="choices-category-input" required>
                            <option value=""></option>
                            @foreach ($document_types as $type)
                                <option value="{{ $type->id }}" @if(old('type') == $type->name) selected @endif>{{ $type->name }}</option>
                            @endforeach
                        </select>
                    </div>
    
                    <div class="mb-3">
                        <label class="form-label">Document Description <span class="text-danger">*</span></label>
                        <textarea name="description" class="form-control" cols="30" rows="5" placeholder="Enter document description" required>{{ old('description') }}</textarea>
                    </div>
    
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="mb-3">
                                <label for="choices-category-input" class="form-label">Category <span class="text-danger">*</span></label>
                                <select name="category" class="form-select" data-choices data-choices-search-false id="choices-category-input" required>
                                    <option value="">-- Select Category --</option>
                                    <option value="Personal" @if(old('category') == "Personal") selected @endif>Personal</option>
                                    <option value="Departmental" @if(old('category') == "Departmental") selected @endif>Departmental</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="mb-3">
                                <label for="choices-status-input" class="form-label">Status <span class="text-danger">*</span></label>
                                <select name="status" class="form-select" data-choices data-choices-search-false id="choices-status-input" required>
                                    <option value="Draft" selected @if(old('status') == "Draft") selected @endif>Draft</option>
                                    <option value="For Approval" @if(old('status') == "For Approval") selected @endif>For Approval</option>
                                    <option value="Approved" @if(old('status') == "Approved") selected @endif>Approved</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="choices-privacy-status-input" class="form-label">Access</label>
                        <select name="privacy" class="form-select" data-choices data-choices-search-false id="choices-privacy-status-input">
                            <option value="Private" selected @if(old('privacy') == "Private") selected @endif>Private</option>
                            <option value="Team" @if(old('privacy') == "Team") selected @endif>Team</option>
                            <option value="Public" @if(old('privacy') == "Public") selected @endif>Public</option>
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
                            <span class="approver-level badge bg-primary">Level 1</span>
                            <select name="approvers[]" class="form-select w-50" required>
                                @foreach($approvers as $user)
                                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                                @endforeach
                            </select>
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
                    <div class="row">
                        <div class="col-md-12">
                            <p class="text-muted">Add attachments or supporting documents.</p>
            
                            <div class="dropzone">
                                <div class="fallback">
                                    <input name="file" type="file" multiple="multiple" accept=".pdf" required>
                                </div>
                                <div class="dz-message needsclick">
                                    <div class="mb-3">
                                        <i class="display-4 text-muted ri-upload-cloud-2-fill"></i>
                                    </div>
                                    <h5>Drop files here or click to upload.</h5>
                                </div>
                            </div>
                        </div>
        
                        <div class="col-md-12">
                            Supporting Documents :
                            <input type="file" name="supporting_documents[]" class="form-control" multiple>
                        </div>
                    <p class="text-muted mb-3">Add PDF file to preview on the right</p>
                    
                    <div class="mb-3">
                        <label class="form-label">Main Document (PDF)</label>
                        <input name="file" type="file" class="form-control" id="pdf-file-input" accept=".pdf" required>
                    </div>

                    <div>
                        <label class="form-label">Supporting Documents</label>
                        <input type="file" name="supporting_documents[]" class="form-control" id="supporting-docs-input" accept=".pdf" multiple>
                    </div>
                </div>
            </div>
    
            <div class="text-end mb-4">
                <button type="button" class="btn btn-secondary w-sm">Save as Draft</button>
                <button type="submit" class="btn btn-success w-sm">Upload</button>
            </div>
        </div>
    
        <div class="col-lg-7 mb-4">
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
                        <div class="no-preview" id="main-doc-preview">
                            <div>
                                <i class="ri-file-pdf-line" style="font-size: 48px;"></i>
                                <p class="mt-2">Upload a PDF to preview</p>
                            </div>
                        </div>
                    </div>

                    <div class="tab-content-item" id="supporting-docs-content">
                        <div class="no-preview" id="supporting-docs-empty">
                            <div>
                                <i class="ri-folder-open-line" style="font-size: 48px;"></i>
                                <p class="mt-2">No supporting documents uploaded</p>
                            </div>
                        </div>
                        <div class="supporting-docs-list" id="supporting-docs-list" style="display: none;"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const pdfInput = document.getElementById('pdf-file-input');
    const supportingDocsInput = document.getElementById('supporting-docs-input');
    const mainDocPreview = document.getElementById('main-doc-preview');
    const supportingDocsGrid = document.getElementById('supporting-docs-list');
    const supportingDocsEmpty = document.getElementById('supporting-docs-empty');
    const supportingCount = document.getElementById('supporting-count');
    
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

    pdfInput.addEventListener('change', function(e) {
        const file = e.target.files[0];
        
        if (file && file.type === 'application/pdf') {
            const fileURL = URL.createObjectURL(file);
            mainDocPreview.innerHTML = `<iframe src="${fileURL}" type="application/pdf"></iframe>`;
        } else {
            mainDocPreview.innerHTML = `
                <div class="no-preview">
                    <div>
                        <i class="ri-file-pdf-line" style="font-size: 48px;"></i>
                        <p class="mt-2">Upload a PDF to preview</p>
                    </div>
                </div>
            `;
        }
    });

    supportingDocsInput.addEventListener('change', function(e) {
        supportingFiles = Array.from(e.target.files);
        
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
});
</script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const wrapper = document.getElementById('approvers-wrapper');
    const addBtn = document.getElementById('add-approver');

    function updateLevels() {
        const rows = wrapper.querySelectorAll('.approver-row');
        rows.forEach((row, index) => {
            row.querySelector('.approver-level').textContent = `Level ${index + 1}`;
        });
    }

    addBtn.addEventListener('click', function () {
        const count = wrapper.querySelectorAll('.approver-row').length;
        const newRow = document.createElement('div');
        newRow.classList.add('approver-row', 'mb-2', 'd-flex', 'align-items-center', 'gap-2');
        newRow.innerHTML = `
            <span class="approver-level badge bg-primary">Level ${count + 1}</span>
            <select name="approvers[]" class="form-select w-50" required>
                @foreach($approvers as $user)
                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                @endforeach
            </select>
            <button type="button" class="btn btn-danger btn-sm remove-approver">
                <i class="ri-delete-bin-2-line"></i>
            </button>
        `;
        wrapper.appendChild(newRow);
        updateLevels();
    });

    wrapper.addEventListener('click', function (e) {
        if (e.target.closest('.remove-approver')) {
            e.target.closest('.approver-row').remove();
            updateLevels();
        }
    });
});
</script>
@endsection
@section('js')
    <script src="{{asset('assets/libs/@ckeditor/ckeditor5-build-classic/build/ckeditor.js')}}"></script>
    <script src="{{ asset('login_css/js/plugins/chosen/chosen.jquery.js') }}"></script>
    <script src="{{asset('assets/libs/dropzone/dropzone-min.js')}}"></script>

    <script>
        $(document).ready(function() {
            $(".cat").chosen({
                width: "100%"
            });
        })
    </script>
@endsection
