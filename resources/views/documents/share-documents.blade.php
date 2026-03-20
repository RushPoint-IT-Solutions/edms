<div class="modal fade" id="share" tabindex="-1" aria-labelledby="shareModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header border-bottom">
                <div>
                    <h5 class="modal-title mb-0">Share with Others</h5>
                    <p class="text-muted mb-0" style="font-size:0.78rem;">Choose what to share and with whom</p>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <form action="{{ url('/documents/share') }}" method="post" onsubmit="show()" id="shareForm">
                @csrf

                <div class="modal-body">
                    <div class="row g-3">

                        <div class="col-12">
                            <label class="form-label fw-semibold">Share Type</label>
                            <div class="d-flex gap-3">
                                <div class="form-check share-type-card flex-fill p-3 border rounded" style="cursor:pointer;">
                                    <input class="form-check-input" type="radio" name="share_type" id="shareTypeFolder" value="folder" required>
                                    <label class="form-check-label w-100" for="shareTypeFolder" style="cursor:pointer;">
                                        <div class="d-flex align-items-center gap-2">
                                            <div style="width:36px;height:36px;background:#fff3cd;border-radius:8px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                                                <i class="ri-folder-2-fill" style="color:#e67e22;font-size:1.1rem;"></i>
                                            </div>
                                            <div>
                                                <div class="fw-semibold" style="font-size:0.875rem;">Entire Folder</div>
                                                <div class="text-muted" style="font-size:0.72rem;">Share a folder and all its documents</div>
                                            </div>
                                        </div>
                                    </label>
                                </div>
                                <div class="form-check share-type-card flex-fill p-3 border rounded" style="cursor:pointer;">
                                    <input class="form-check-input" type="radio" name="share_type" id="shareTypeDocument" value="document" required>
                                    <label class="form-check-label w-100" for="shareTypeDocument" style="cursor:pointer;">
                                        <div class="d-flex align-items-center gap-2">
                                            <div style="width:36px;height:36px;background:#e0f2fe;border-radius:8px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                                                <i class="ri-file-text-line" style="color:#0284c7;font-size:1.1rem;"></i>
                                            </div>
                                            <div>
                                                <div class="fw-semibold" style="font-size:0.875rem;">Specific Documents</div>
                                                <div class="text-muted" style="font-size:0.72rem;">Pick one or more documents to share</div>
                                            </div>
                                        </div>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="col-12" id="folderSelectionField" style="display:none;">
                            <label class="form-label fw-semibold">Select Folder</label>
                            <select name="folder_id" class="cat form-control" id="shareFolderSelect">
                                <option value="">— Select a folder —</option>
                                @foreach ($document_folders->where('parent_id', null) as $folder)
                                    <option value="{{ $folder->id }}">
                                        {{ $folder->name }} ({{ $folder->document->count() }} file{{ $folder->document->count() !== 1 ? 's' : '' }})
                                    </option>
                                    @foreach ($folder->childrenFolder as $child)
                                        <option value="{{ $child->id }}">
                                            &nbsp;&nbsp;&nbsp;↳ {{ $child->name }} ({{ $child->document->count() }} file{{ $child->document->count() !== 1 ? 's' : '' }})
                                        </option>
                                    @endforeach
                                @endforeach
                            </select>

                            <div id="folderPreview" class="mt-2" style="display:none;">
                                <div class="border rounded p-2" style="background:#fafafa;font-size:0.8rem;">
                                    <div class="d-flex align-items-center gap-2 mb-2">
                                        <i class="ri-folder-open-line" style="color:#e67e22;"></i>
                                        <span class="fw-semibold" id="folderPreviewName">—</span>
                                        <span class="badge bg-warning text-dark ms-auto" id="folderPreviewCount">0 files</span>
                                    </div>
                                    <div id="folderPreviewList" style="max-height:120px;overflow-y:auto;"></div>
                                </div>
                            </div>
                        </div>

                        <div class="col-12" id="documentSelectionField" style="display:none;">
                            <label class="form-label fw-semibold">Select Documents</label>
                            <select name="documents[]" class="cat form-control" id="shareDocumentSelect" multiple>
                                @foreach ($documents as $document)
                                    <option value="{{ $document->id }}">
                                        {{ $document->control_code }} - {{ $document->title }}
                                    </option>
                                @endforeach
                            </select>
                            <div class="text-muted mt-1" style="font-size:0.72rem;">
                                <i class="ri-information-line"></i> Hold Ctrl / Cmd to select multiple documents
                            </div>
                        </div>

                        <div class="col-12" id="usersField" style="display:none;">
                            <label class="form-label fw-semibold">Share With (Users)</label>
                            <select name="users[]" id="shareUsersSelect" class="cat form-control" multiple required>
                                @foreach ($users as $user)
                                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div id="peopleAccessContainer" class="col-12" style="display:none;">
                            <hr class="my-1">
                            <p class="fw-semibold mb-2" style="font-size:0.78rem;">
                                <i class="ri-group-line me-1"></i>People currently with access
                            </p>
                            {{-- <a href="javascript:void(0);" class="list-group-item list-group-item-action active">
                                <div class="d-flex mb-2 align-items-center">
                                    <div class="flex-shrink-0">
                                        <img src="{{ asset("images/no_image.png") }}" alt="" class="avatar-sm rounded-circle" />
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <h5 class="list-title fs-15 mb-1 text-dark" id="Name"></h5>
                                        <p class="list-text mb-0 fs-12 text-dark" id="Email"></p>
                                    </div>
                                </div>
                            </a> --}}
                        </div>

                    </div>
                </div>

                <div class="modal-footer border-top">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary btn-sm" id="shareSubmitBtn" disabled>
                        <i class="ri-share-line me-1"></i> Share
                    </button>
                </div>
            </form>

        </div>
    </div>
</div>