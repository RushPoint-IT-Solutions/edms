<div class="modal fade" id="uploadDocument" tabindex="-1" role="dialog" aria-labelledby="uploadDocumentLabel">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="uploadDocumentLabel">Upload Document</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method='post' action='{{ url('/documents/upload-document') }}' onsubmit='show();' enctype="multipart/form-data" id="uploadDocumentForm">
                <div class="modal-body">
                    {{ csrf_field() }}

                    <input type="hidden" name="is_revision" id="isRevision" value="0">

                    <div class='row g-3'>

                        <div class='col-md-6'>
                            <label class="form-label">Control Code *
                                <span class="badge-new-doc" id="newDocBadge" style="display:none;">
                                    <i class="ri-add-circle-line"></i> New
                                </span>
                                <span class="badge-revision-doc" id="revisionBadge" style="display:none;">
                                    <i class="ri-history-line"></i> Revision
                                </span>
                            </label>

                            <select id="controlCodeSelect" name="_control_code_picker" class="form-control" style="width:100%;">
                                <option value="">— Search or select a control code —</option>
                                <option value="__OTHER__">Other (New)</option>
                                @foreach($existingDocuments ?? [] as $existingDoc)
                                    <option value="{{ $existingDoc->control_code }}"
                                            data-title="{{ $existingDoc->title }}"
                                            data-type="{{ $existingDoc->category }}"
                                            data-folder="{{ $existingDoc->folder_id }}"
                                            data-other="{{ $existingDoc->other_category }}"
                                            data-request="{{ $existingDoc->type_of_request }}"
                                            data-revision="{{ $existingDoc->latest_revision ?? 0 }}">
                                        {{ $existingDoc->control_code }} — {{ \Illuminate\Support\Str::limit($existingDoc->title, 50) }}
                                    </option>
                                @endforeach
                            </select>

                            <input type="hidden" id="selectedControlCode" name="control_code_existing">
                        </div>

                        <div class='col-md-6' id="manualControlCodeWrapper" style="display:none;">
                            <label class="form-label">New Control Code *</label>
                            <input type="text"
                                id="manualControlCode"
                                name="control_code"
                                class="form-control @if($errors->has('control_code')) is-invalid @endif"
                                placeholder="Enter new control code (e.g. CT-00600)"
                                value="{{ old('control_code') }}" />
                            @if($errors->has('control_code'))
                                {{ $errors->first('control_code') }}
                            @endif
                        </div>

                        <div class='col-md-12'>
                            <label class="form-label">Title *</label>
                            <input type="text" id="titleField" class="form-control" value="{{ old('title') }}" name="title" required/>
                        </div>

                        {{-- <div class="col-12"><hr class="divider"></div> --}}
                        
                        {{-- <div class='col-md-5'>
                            <label class="form-label">Company *</label>
                            <select name='company' class='form-control cat' required>
                                <option value=""></option>
                                @foreach($companies->where('status',null) as $company)
                                    <option value='{{$company->id}}' @if(old('company') == $company->id) selected @endif>
                                        {{$company->code}} - {{$company->name}}
                                    </option>
                                @endforeach
                            </select>
                        </div> --}}
                        {{-- <div class='col-md-5'>
                            <label class="form-label">Department *</label>
                            <select name='department' class='form-control cat' required>
                                <option value=""></option>
                                @foreach($departments->where('status',null) as $dep)
                                    <option value='{{$dep->id}}' @if(old('department') == $dep->id) selected @endif>
                                        {{$dep->code}} - {{$dep->name}}
                                    </option>
                                @endforeach
                            </select>
                        </div> --}}
                        <div class='col-md-4'>
                            <label class="form-label">Type of Document *</label>
                            <select id="documentTypeField" name='document_type' class='form-control cat' required>
                                <option value=""></option>
                                @foreach($document_types as $types)
                                    <option value='{{$types->name}}' @if(old('document_type') == $types->name) selected @endif>
                                        {{$types->code}} - {{$types->name}}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class='col-md-4'>
                            <label class="form-label">Released Date *</label>
                            <input type="date" class="form-control" name="effective_date" value="{{ old('effective_date') }}" required/>
                        </div>

                        <div class='col-md-4'>
                            <div class="checkbox-label">
                                <input type="checkbox" name='public' value='1' id='public'>
                                <label for='public' class="form-label mb-0">Public</label>
                            </div>
                        </div>

                        <div class='col-md-4'>
                            <label class="form-label">
                                Revision *
                                <small id="revisionHint" class="text-muted ms-1" style="display:none;"></small>
                            </label>
                            <div class="input-group">
                                <input type="number" id="revisionField" class="form-control" value="{{ old('version', 0) }}" min='0' name="version" style="background:#f8f9fa; cursor:not-allowed;" readonly/>
                            </div>
                        </div>

                        <div class='col-md-4'>
                            <label class="form-label">Choose Folder *</label>
                            <select id="folderField" name='folder' class='form-control cat' required>
                                <option value=""></option>
                                @foreach($document_folders as $folder)
                                    <option value='{{$folder->id}}' @if(old('folder') == $folder->id) selected @endif>
                                        {{$folder->name}}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Tags *</label>
                            <select name="tags[]" class="form-control cat" multiple="multiple" required>
                                <option value=""></option>
                                <option value="Confidential">Confidential</option>
                                <option value="Urgent">Urgent</option>
                                <option value="Draft">Draft</option>
                                <option value="Final">Final</option>
                                <option value="Important">Important</option>
                                <option value="Legal">Legal</option>
                            </select>
                        </div>

                        <div class='col-md-4'>
                            <label class="form-label">Type of request *</label>
                            <select id="typeOfRequestField" name="type_of_request" class="form-control cat" required>
                                <option value=""></option>
                                <option value="New">New</option>
                                <option value="Revision">Revision</option>
                                <option value="Discontinuance">Discontinuance</option>
                                <option value="Obsolete">Obsolete</option>
                                <option value="Policy">Policy</option>
                                <option value="Procedure">Procedure</option>
                                <option value="Form">Form</option>
                                <option value="Others">Others</option>
                            </select>
                        </div>

                        <div class="col-12"><hr class="divider"></div>

                        <div class='col-md-4'>
                            <label class="form-label">SOFT Copy * <small class="text-muted">(.word,.csv,.ppt,etc)</small></label>
                            <input type="file" class="form-control" accept="application/msword, application/vnd.ms-excel, application/vnd.ms-powerpoint" name="attachment[soft_copy]"/>
                        </div>
                        <div class='col-md-4'>
                            <label class="form-label">PDF/Scanned Copy * <small class="text-muted">(.pdf)</small></label>
                            <input type="file" class="form-control" accept="application/pdf" name="attachment[pdf_copy]" required/>
                        </div>
                        <div class='col-md-4'>
                            <label class="form-label">FILLABLE Copy <small class="text-muted">(.pdf)</small></label>
                            <input type="file" class="form-control" name="attachment[fillable_copy]"/>
                        </div>

                        <div class="col-12" id="revisionInfoBox" style="display:none;">
                            <div class="revision-info-alert">
                                <i class="ri-information-line"></i>
                                <div>
                                    <strong>Uploading a Revision</strong><br>
                                    <span id="revisionInfoText"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btns btn-secondary" data-dismiss="modal" data-bs-dismiss="modal">Close</button>
                    <button type='submit' class="btn btnss btn-primary">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    .file-selected-indicator {
        font-weight: 600;
        padding: 5px 10px;
        background: #d4edda;
        border-radius: 4px;
        display: inline-block;
    }

    .form-control[type="file"] {
        cursor: pointer;
    }
    .btns {
        background-color:#495057;
        border: none;
    }

    .btns:hover {
        background-color:#282c30;
    }
    .btnss {
        background-color: #800000;
        border: none;
    }
    .btnss:hover {
        background-color: #6B0000;
    }
    .modal-content {
        border-radius: 10px;
        border: none;
        box-shadow: 0 5px 20px rgba(0,0,0,0.2);
    }

    .modal-header {
        background: #f8f9fa;
        border-bottom: 2px solid #8B0000;
        border-radius: 10px 10px 0 0;
        padding: 20px 25px;
    }

    .modal-title {
        font-size: 18px;
        font-weight: 600;
        color: #2c3e50;
    }

    .modal-body {
        padding: 25px;
    }

    .modal-footer {
        border-top: 1px solid #e9ecef;
        padding: 15px 25px;
    }

    .form-label {
        font-size: 13px;
        font-weight: 600;
        color: #495057;
        margin-bottom: 5px;
    }

    .form-control {
        border-radius: 5px;
        border: 1px solid #dee2e6;
    }

    .form-control:focus {
        border-color: #8B0000;
        box-shadow: 0 0 0 0.2rem rgba(139, 0, 0, 0.1);
    }

    .checkbox-label {
        display: flex;
        align-items: center;
        gap: 8px;
        margin-top: 30px;
    }

    hr.divider {
        border: none;
        border-top: 1px solid #e9ecef;
        margin: 20px 0;
    }

    .text-muted {
        font-style: italic;
    }

    .badge-new-doc {
        background: #d1fae5;
        color: #065f46;
        border: 1px solid #6ee7b7;
        font-size: 11px;
        font-weight: 600;
        padding: 2px 8px;
        border-radius: 20px;
        vertical-align: middle;
    }

    .badge-revision-doc {
        background: #dbeafe;
        color: #1e40af;
        border: 1px solid #93c5fd;
        font-size: 11px;
        font-weight: 600;
        padding: 2px 8px;
        border-radius: 20px;
        vertical-align: middle;
    }

    .revision-info-alert {
        background: #eff6ff;
        border: 1px solid #bfdbfe;
        border-radius: 8px;
        padding: 12px 16px;
        display: flex;
        align-items: flex-start;
        gap: 10px;
        font-size: 13px;
        color: #1e40af;
    }

    .revision-info-alert i {
        font-size: 1.2rem;
        flex-shrink: 0;
        margin-top: 1px;
    }

    #uploadDocument .select2-container--default .select2-selection--single {
        height: 38px;
        border: 1px solid #dee2e6;
        border-radius: 5px;
    }

    #uploadDocument .select2-container--default .select2-selection--single .select2-selection__rendered {
        line-height: 36px;
        color: #495057;
        font-size: 14px;
    }

    #uploadDocument .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: 36px;
    }

    #uploadDocument .select2-container--default.select2-container--focus .select2-selection--single {
        border-color: #8B0000;
        box-shadow: 0 0 0 0.2rem rgba(139, 0, 0, 0.1);
    }
</style>