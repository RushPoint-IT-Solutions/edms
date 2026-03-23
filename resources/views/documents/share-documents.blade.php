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

            <form action="{{ url('/documents/share') }}" method="post" id="shareForm">
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
                                                <div class="text-muted" style="font-size:0.72rem;">Browse folders and pick documents</div>
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
                            <label class="form-label fw-semibold">Browse & Select Documents</label>

                            <div style="position:relative;margin-bottom:8px;">
                                <i class="ri-search-line" style="position:absolute;left:9px;top:50%;transform:translateY(-50%);color:#9ca3af;font-size:0.9rem;pointer-events:none;"></i>
                                <input type="text" id="docBrowserSearch" placeholder="Search documents by title or control code..."
                                    autocomplete="off"
                                    style="width:100%;padding:6px 30px 6px 30px;border:1px solid #dee2e6;border-radius:6px;font-size:0.82rem;">
                                <button id="docBrowserSearchClear" type="button"
                                        style="display:none;position:absolute;right:8px;top:50%;transform:translateY(-50%);
                                            background:none;border:none;color:#9ca3af;cursor:pointer;padding:0;font-size:0.9rem;">
                                    <i class="ri-close-line"></i>
                                </button>
                            </div>

                            <div id="docSearchPane" style="display:none;border:1px solid #dee2e6;border-radius:6px;
                                                            max-height:220px;overflow-y:auto;background:#fff;margin-bottom:8px;">
                            </div>

                            <div id="docBrowserWrapper">
                                <div id="docBrowserCrumb"
                                    style="display:flex;align-items:center;flex-wrap:wrap;gap:4px;font-size:0.78rem;
                                            background:#f8f9fa;border:1px solid #dee2e6;border-bottom:none;
                                            border-radius:6px 6px 0 0;padding:6px 10px;min-height:32px;">
                                    <span class="crumb-item text-primary"
                                        style="cursor:pointer;font-weight:600;"
                                        data-index="-1">
                                        <i class="ri-home-4-line"></i> Root
                                    </span>
                                </div>

                                <div id="docBrowserPane"
                                    style="border:1px solid #dee2e6;border-radius:0 0 6px 6px;
                                            height:220px;overflow-y:auto;background:#fff;">
                                </div>
                            </div>

                            <div style="margin-top:8px;">
                                <div id="docSelectedChips" style="display:flex;flex-wrap:wrap;gap:6px;min-height:28px;"></div>
                                <p id="docNoSelected" class="text-muted mb-0" style="font-size:0.72rem;margin-top:4px;">
                                    <i class="ri-information-line"></i>
                                    No documents selected yet — search or navigate folders and check documents.
                                </p>
                            </div>

                            <div id="docHiddenInputs"></div>
                        </div>

                        {{-- Users --}}
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

<style>
.doc-browser-row {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 7px 12px;
    font-size: 0.82rem;
    border-bottom: 1px solid #f0f0f0;
    cursor: pointer;
    transition: background 0.1s;
}

.doc-browser-row:hover { 
    background: #f5f8ff; 
}

.doc-browser-row.is-folder { 
    font-weight: 500; 
}

.doc-browser-row.is-folder:hover { 
    background: #fff8e8; 
}

.doc-chip {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    background: #e0f2fe;
    color: #0369a1;
    border-radius: 20px;
    padding: 2px 10px 2px 8px;
    font-size: 0.75rem;
    max-width: 260px;
}

.doc-chip span { 
    overflow: hidden; 
    text-overflow: ellipsis; 
    white-space: nowrap; 
}

.doc-chip button {
    background: none; 
    border: none; 
    padding: 0; 
    line-height: 1;
    color: #0369a1; 
    cursor: pointer;
     font-size: 0.85rem; 
     flex-shrink: 0;
}

.doc-chip button:hover { 
    color: #dc2626; 
}

.crumb-sep { 
    color: #adb5bd; 
}

.crumb-item { 
    color: #0284c7;
}

.crumb-item.active { 
    color: #6b7280; 
    cursor: default;
}
</style>

<script>
    window._shareDocTree = @json($shareTree);
    window._shareOthersDocs = @json($shareOthersDocs);
</script>