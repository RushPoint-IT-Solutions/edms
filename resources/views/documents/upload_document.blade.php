<div class="modal fade" id="uploadDocument" tabindex="-1" aria-labelledby="uploadDocumentLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">

            <div class="modal-header border-bottom border-2">
                <h5 class="modal-title mb-3">Upload Document</h5>
                <button type="button" class="btn-close mb-3" data-bs-dismiss="modal"></button>
            </div>

            <form method="POST" action="{{ url('/documents/upload-document') }}" onsubmit="show();" enctype="multipart/form-data" id="uploadDocumentForm">
                @csrf

                <div class="modal-body">
                    <input type="hidden" name="is_revision" id="isRevision" value="0">

                    <div class="row g-3">

                        <div class="col-md-6">
                            <label class="form-label">
                                Control Code *
                                <span id="newDocBadge" class="badge bg-light text-dark border ms-1" style="display:none;">New</span>
                                <span id="revisionBadge" class="badge bg-light text-dark border ms-1" style="display:none;">Revision</span>
                            </label>

                            <select id="controlCodeSelect" name="_control_code_picker" class="form-select">
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

                        <div class="col-md-6" id="manualControlCodeWrapper" style="display:none;">
                            <label class="form-label">New Control Code *</label>
                            <input type="text"
                                id="manualControlCode"
                                name="control_code"
                                class="form-control @if($errors->has('control_code')) is-invalid @endif"
                                placeholder="Enter new control code (e.g. CT-00600)"
                                value="{{ old('control_code') }}">
                            @if($errors->has('control_code'))
                                <span class="invalid-feedback">{{ $errors->first('control_code') }}</span>
                            @endif
                        </div>

                        <div class="col-md-12">
                            <label class="form-label">Title *</label>
                            <input type="text" id="titleField" name="title" class="form-control" value="{{ old('title') }}" required>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Type of Document *</label>
                            <select id="documentTypeField" name="document_type[]" class="form-control cat" multiple required>
                                @foreach($document_types as $types)
                                    <option value="{{ $types->id }}">
                                        {{ $types->code }} - {{ $types->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Released Date *</label>
                            <input type="date" class="form-control" name="effective_date" value="{{ old('effective_date') }}" required>
                        </div>

                        <div class="col-md-4 align-items-end mt-5">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="public" value="1" id="public">
                                <label class="form-check-label" for="public">Public</label>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">
                                Revision *
                                <small id="revisionHint" class="text-muted ms-1" style="display:none;"></small>
                            </label>
                            <input type="number" id="revisionField" name="version"
                                class="form-control"
                                value="{{ old('version', 0) }}"
                                readonly>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Choose Folder *</label>
                            <select id="folderField" name="folder" class="form-select" required>
                                @foreach($document_folders as $folder)
                                    @if($folder->parent_id == null)
                                        @include("documents.option", ['folder' => $folder, 'level' => 0])
                                    @endif  
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Tags *</label>
                            <select name="tags[]" class="form-control cat" multiple required>
                                <option value="Confidential">Confidential</option>
                                <option value="Urgent">Urgent</option>
                                <option value="Final">Final</option>
                                <option value="Important">Important</option>
                                <option value="Legal">Legal</option>
                            </select>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Type of request *</label>
                            <select id="typeOfRequestField" name="type_of_request" class="form-select" required>
                                <option value=""></option>
                                <option value="New">New</option>
                                <option value="Revision">Revision</option>
                                <option value="Discontinuance">Discontinuance</option>
                                <option value="Obsolete">Obsolete</option>
                            </select>
                        </div>

                        <div class="col-12">
                            <hr>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">SOFT Copy *</label>
                            <input type="file" class="form-control" name="attachment[soft_copy]">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">PDF Copy *</label>
                            <input type="file" class="form-control" name="attachment[pdf_copy]" required>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Fillable Copy</label>
                            <input type="file" class="form-control" name="attachment[fillable_copy]">
                        </div>

                        <div class="col-12" id="revisionInfoBox" style="display:none;">
                            <div class="alert alert-light border">
                                <strong>Uploading a Revision</strong><br>
                                <span id="revisionInfoText"></span>
                            </div>
                        </div>

                    </div>
                </div>

                <div class="modal-footer border-top border-2">
                    <button type="button" class="btn btn-secondary mt-3" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary mt-3">Submit</button>
                </div>

            </form>
        </div>
    </div>
</div>