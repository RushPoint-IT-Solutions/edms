@extends('layouts.header')
@section('css')
 <link href="{{asset('/assets/libs/dropzone/dropzone.css')}}" rel="stylesheet" type="text/css" />
@endsection
@section('content')
<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-body">
                <div class="mb-3">
                    <label class="form-label" for="document-title-input">Document Title</label>
                    <input type="text" name="title" class="form-control" id="document-title-input" placeholder="Enter document title" required>
                </div>

                <div class="mb-3">
                    <label class="form-label" for="document-type-input">Document Type</label>
                    <input type="text" name="type" class="form-control" id="document-type-input" placeholder="Enter document type" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Document Description</label>
                    <div id="ckeditor-classic">
                        <p>Enter document notes, purpose, or related details here.</p>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-6">
                        <div class="mb-3 mb-lg-0">
                            <label for="choices-category-input" class="form-label">Category</label>
                            <select name="category" class="form-select" data-choices data-choices-search-false id="choices-category-input" required>
                                <option value="">-- Select Category --</option>
                                <option value="Personal">Personal</option>
                                <option value="Departmental">Departmental</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="mb-3 mb-lg-0">
                            <label for="choices-status-input" class="form-label">Status</label>
                            <select name="status" class="form-select" data-choices data-choices-search-false id="choices-status-input">
                                <option value="Draft" selected>Draft</option>
                                <option value="For Approval">For Approval</option>
                                <option value="Approved">Approved</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Approvers Section -->
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Approvers</h5>
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
                <h5 class="card-title mb-0">Attached Files</h5>
            </div>
            <div class="card-body">
                <p class="text-muted">Add attachments or supporting documents.</p>

                <div class="dropzone">
                    <div class="fallback">
                        <input name="file" type="file" multiple="multiple">
                    </div>
                    <div class="dz-message needsclick">
                        <div class="mb-3">
                            <i class="display-4 text-muted ri-upload-cloud-2-fill"></i>
                        </div>
                        <h5>Drop files here or click to upload.</h5>
                    </div>
                </div>

                <ul class="list-unstyled mb-0" id="dropzone-preview">
                    <li class="mt-2" id="dropzone-preview-list">
                        <div class="border rounded">
                            <div class="d-flex p-2">
                                <div class="flex-shrink-0 me-3">
                                    <div class="avatar-sm bg-light rounded">
                                        <img src="#" alt="File" data-dz-thumbnail class="img-fluid rounded d-block" />
                                    </div>
                                </div>
                                <div class="flex-grow-1">
                                    <div class="pt-1">
                                        <h5 class="fs-14 mb-1" data-dz-name>&nbsp;</h5>
                                        <p class="fs-13 text-muted mb-0" data-dz-size></p>
                                        <strong class="error text-danger" data-dz-errormessage></strong>
                                    </div>
                                </div>
                                <div class="flex-shrink-0 ms-3">
                                    <button data-dz-remove class="btn btn-sm btn-danger">Delete</button>
                                </div>
                            </div>
                        </div>
                    </li>
                </ul>
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
                    <option value="Private" selected>Private</option>
                    <option value="Team">Team</option>
                    <option value="Public">Public</option>
                </select>
            </div>
        </div>
    </div>
</div>
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

    <!-- dropzone js -->
    <script src="{{asset('assets/libs/dropzone/dropzone-min.js')}}"></script>
@endsection
