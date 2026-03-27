<div class="modal fade" id="uploadDocument" tabindex="-1" aria-labelledby="uploadDocumentLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">

            <div class="modal-header border-bottom border-2">
                <h5 class="modal-title mb-3">Upload Document</h5>
                <button type="button" class="btn-close mb-3" data-bs-dismiss="modal"></button>
            </div>

            <form method="POST" action="{{ url('/documents/upload-document') }}" enctype="multipart/form-data" id="uploadDocumentForm">
                @csrf

                <div class="modal-body">
                    <input type="hidden" name="is_revision" id="isRevision" value="0">
                    <input type="hidden" name="control_code_existing" id="selectedControlCode" value="">
                    <input type="hidden" name="control_code" id="finalNewControlCode" value="">

                    <div class="row g-3">

                        <div class="col-md-6">
                            <label class="form-label fw-semibold">
                                Control Code *
                                <span id="newDocBadge" class="badge bg-success text-white ms-1" style="display:none; font-size:0.7rem;">New</span>
                                <span id="revisionBadge" class="badge bg-warning text-dark ms-1" style="display:none; font-size:0.7rem;">Revision</span>
                            </label>
                            <select id="controlCodeTypePicker" class="form-select">
                                <option value="">— Select —</option>
                                <option value="new">New</option>
                                <option value="existing">Existing</option>
                            </select>
                        </div>

                        <div class="col-md-6" id="controlCodeResultField" style="display:none;">

                            <div id="newDocCodeDisplay" style="display:none;">
                                <label class="form-label fw-semibold">Select Control Code</label>
                                <select id="newControlCodePicker" name="_new_control_code" class="form-select">
                                    <option value="">— Select a control code —</option>
                                    @foreach($controlCodes ?? [] as $cc)
                                        <option value="{{ $cc->code }}"
                                            data-description="{{ $cc->description }}">
                                            {{ preg_replace('/-\d+$/', '-????', $cc->code) }}
                                        </option>
                                    @endforeach
                                </select>
                                <small class="text-muted">
                                    <i class="ri-information-line me-1"></i>
                                    These are pre-generated control codes available for new documents.
                                </small>
                            </div>

                            <div id="existingDocCodeDisplay" style="display:none;">
                                <label class="form-label fw-semibold">Select Existing Document</label>
                                <select id="existingControlCodePicker" name="_existing_control_code" class="form-select">
                                    <option value="">— Search or select a control code —</option>
                                    @foreach($existingDocuments ?? [] as $ed)
                                        <option value="{{ $ed->control_code }}"
                                            data-title="{{ $ed->title }}"
                                            data-folder="{{ $ed->folder_id }}"
                                            data-other="{{ $ed->other_category }}"
                                            data-request="{{ $ed->type_of_request }}"
                                            data-revision="{{ $ed->latest_revision ?? 0 }}"
                                            data-office="{{ $ed->office_id }}"
                                            data-doctypes="{{ $ed->document_type_list->pluck('type')->implode(',') }}"
                                            data-tags="{{ $ed->document_tags->pluck('name')->implode(',') }}">
                                            {{ $ed->control_code }} — {{ \Illuminate\Support\Str::limit($ed->title, 50) }}
                                        </option>
                                    @endforeach
                                </select>
                                <small class="text-muted">
                                    <i class="ri-information-line me-1"></i>
                                    Select the document you are uploading a revision for.
                                </small>
                            </div>
                        </div>

                        <div class="col-md-8">
                            <label class="form-label fw-semibold">Type of Document *</label>
                            <select id="documentTypeField" name="document_type[]" class="form-control chosen-select-doc" multiple required>
                                @foreach($document_types as $types)
                                    <option value="{{ $types->id }}" data-name="{{ $types->name }}">
                                        {{ $types->code }} - {{ $types->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Office *</label>
                            <select id="newDocDeptField" name="office_id" class="form-select">
                                <option value="">— Select office —</option>
                                @foreach($teams ?? [] as $team)
                                    <option value="{{ $team->id }}" data-code="{{ $team->name }}">
                                        {{ $team->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-12">
                            <label class="form-label fw-semibold">Title *</label>
                            <input type="text" id="titleField" name="title" class="form-control" value="{{ old('title') }}" required>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Revision *</label>
                            <div class="input-group">
                                <input type="number" id="revisionField" name="version"
                                       class="form-control"
                                       value="{{ old('version', 0) }}"
                                       style="background:#f8f9fa; cursor:not-allowed;"
                                       readonly>
                                <span class="input-group-text" id="revisionAutoIcon"
                                      style="display:none;" title="Auto-set">
                                    <i class="ri-magic-line text-muted"></i>
                                </span>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Released Date *</label>
                            <input type="date" class="form-control" name="effective_date"
                                   value="{{ old('effective_date') }}" required>
                        </div>

                        <div class="col-md-4 d-flex align-items-end pb-2">
                            <div class="form-check ms-1">
                                <input class="form-check-input" type="checkbox"
                                       name="public" value="1" id="uploadPublic">
                                <label class="form-check-label" for="uploadPublic">Public</label>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Choose Folder</label>
                            <select id="folderField" name="folder" class="form-select">
                                <option value="">— No folder —</option>
                                @foreach($document_folders as $folder)
                                    @if($folder->parent_id == null)
                                        @include("documents.option", ['folder' => $folder, 'level' => 0])
                                    @endif
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Tags *</label>
                            <select name="tags[]" class="form-control chosen-select-tags" multiple required>
                                <option value="Confidential">Confidential</option>
                                <option value="Urgent">Urgent</option>
                                <option value="Final">Final</option>
                                <option value="Important">Important</option>
                                <option value="Legal">Legal</option>
                            </select>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Type of Request *</label>
                            <select id="typeOfRequestField" name="type_of_request" class="form-select" required>
                                <option value=""></option>
                                <option value="New">New</option>
                                <option value="Revision">Revision</option>
                                <option value="Discontinuance">Discontinuance</option>
                                <option value="Obsolete">Obsolete</option>
                            </select>
                        </div>

                        <div class="col-12"><hr class="my-1"></div>

                        <div class="col-md-4">
                            <label class="form-label fw-semibold">SOFT Copy</label>
                            <input type="file" class="form-control" name="attachment[soft_copy]" accept=".docx,.doc">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label fw-semibold">PDF Copy *</label>
                            <input type="file" class="form-control" name="attachment[pdf_copy]" accept=".pdf" required>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Fillable Copy</label>
                            <input type="file" class="form-control" name="attachment[fillable_copy]">
                        </div>

                        <div class="col-12" id="revisionInfoBox" style="display:none;">
                            <div class="alert alert-light border mb-0">
                                <i class="ri-information-line me-1 text-primary"></i>
                                <span id="revisionInfoText"></span>
                            </div>
                        </div>

                    </div>
                </div>

                <div class="modal-footer border-top border-2">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" id="uploadSubmitBtn">Submit</button>
                </div>

            </form>
        </div>
    </div>
</div>