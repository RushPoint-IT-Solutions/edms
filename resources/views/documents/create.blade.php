@extends('layouts.header')
@section('css')
 <link href="{{asset('/assets/libs/dropzone/dropzone.css')}}" rel="stylesheet" type="text/css" />
 <link rel="stylesheet" href="{{ asset('login_design/css/file-input-preview.css') }}">
@endsection
@section('content')
<form method="POST" action="{{ url('change-request/store') }}" enctype="multipart/form-data" onsubmit="show()">
    @csrf 

    <div class="row">
        <div class="col-lg-8">
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
                        <input type="text" name="type" class="form-control" id="document-type-input" value="{{ old('type') }}" placeholder="Enter document type" required>
                    </div>
    
                    <div class="mb-3">
                        <label class="form-label">Document Description <span class="text-danger">*</span></label>
                        <textarea name="description" class="form-control" cols="30" rows="10" placeholder="Enter document description" required>{{ old('description') }}</textarea>
                    </div>
    
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="mb-3 mb-lg-0">
                                <label for="choices-category-input" class="form-label">Category <span class="text-danger">*</span></label>
                                <select name="category" class="form-select" data-choices data-choices-search-false id="choices-category-input" required>
                                    <option value="">-- Select Category --</option>
                                    <option value="Personal" @if(old('category') == "Personal") selected @endif>Personal</option>
                                    <option value="Departmental" @if(old('category') == "Departmental") selected @endif>Departmental</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="mb-3 mb-lg-0">
                                <label for="choices-status-input" class="form-label">Status <span class="text-danger">*</span></label>
                                <select name="status" class="form-select" data-choices data-choices-search-false id="choices-status-input" required>
                                    <option value="Draft" selected @if(old('status') == "Draft") selected @endif>Draft</option>
                                    <option value="For Approval" @if(old('status') == "For Approval") selected @endif>For Approval</option>
                                    <option value="Approved" @if(old('status') == "Approved") selected @endif>Approved</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
    
            <!-- Approvers Section -->
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
    
            <!-- Attached Files Section -->
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Attached Files <span class="text-danger">*</span></h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <p class="text-muted">Add attachments or supporting documents.</p>
            
                            <div class="dropzone">
                                <div class="fallback">
                                    <input name="file" type="file" accept=".pdf" multiple="multiple" required>
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
                    </div>
                </div>
            </div>
    
            <!-- Submit Buttons -->
            <div class="text-end mb-4">
                <button type="button" class="btn btn-secondary w-sm">Save as Draft</button>
                <button type="submit" class="btn btn-success w-sm">Upload</button>
            </div>
        </div>
    
        <!-- Right Column -->
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Privacy</h5>
                </div>
                <div class="card-body">
                    <label for="choices-privacy-status-input" class="form-label">Access</label>
                    <select name="privacy" class="form-select" data-choices data-choices-search-false id="choices-privacy-status-input">
                        <option value="Private" selected @if(old('privacy') == "Private") selected @endif>Private</option>
                        <option value="Team" @if(old('privacy') == "Team") selected @endif>Team</option>
                        <option value="Public" @if(old('privacy') == "Public") selected @endif>Public</option>
                    </select>
                </div>
            </div>
        </div>
    </div>
</form>
    <!-- ckeditor -->
  
<!-- Approver Add/Remove Script -->
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
    <script src="{{ asset('login_design/js/file-input-preview.js') }}"></script>

    <!-- dropzone js -->
    <script src="{{asset('assets/libs/dropzone/dropzone-min.js')}}"></script>
@endsection
