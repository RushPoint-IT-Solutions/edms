@extends('layouts.header')

@section('content')
<div class="document-manager mb-5">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="top-toolbar d-flex align-items-center">
                    <div class="search-wrapper">
                        <div class="search-input-wrapper">
                            <i class="ri-search-line"></i>
                            <input 
                                type="text" 
                                id="folderSearch" 
                                placeholder="Search folders..." 
                                autocomplete="off"
                            >
                            <button class="clear-search-btn" id="clearSearch" style="display: none;">
                                <i class="ri-close-line"></i>
                            </button>
                        </div>
                    </div>

                    <div class="dropdown ms-auto">
                        <button type="button" class="new-btn" data-bs-toggle="dropdown">
                            <i class="ri-add-line"></i>
                            New
                        </button>
                        <div class="dropdown-menu dropdown-menu-end">
                            <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#uploadDocument">
                                <i class="ri-file-add-line me-2"></i>New file
                            </a>
                            <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#createFolderModal">
                                <i class="ri-folder-add-line me-2"></i>New folder
                            </a>
                        </div>
                    </div>

                    <div class="view-toggle">
                        <button class="view-toggle-btn active" id="listViewBtn" title="List view">
                            <i class="ri-list-check"></i>
                        </button>
                        <button class="view-toggle-btn" id="gridViewBtn" title="Grid view">
                            <i class="ri-grid-fill"></i>
                        </button>
                    </div>
                </div>

                <div class="bulk-action-toolbar" id="bulkActionToolbar" style="display: none;">
                    <div class="bulk-info">
                        <i class="ri-checkbox-multiple-line"></i>
                        <span id="selectedCount">0</span> item(s) selected
                    </div>
                    <div class="bulk-actions">
                        <button class="bulk-delete-btn" id="bulkDeleteBtn">
                            <i class="ri-delete-bin-line"></i>
                            Delete Selected
                        </button>
                        <button class="bulk-cancel-btn" id="bulkCancelBtn">
                            <i class="ri-close-line"></i>
                            Cancel
                        </button>
                    </div>
                </div>

                <form id="bulkDeleteForm" method="POST" action="{{ url('documents/bulk-delete') }}" style="display:none;">
                    @csrf
                    @method('DELETE')
                    <input type="hidden" name="selected_ids" id="bulkDeleteIds">
                    <input type="hidden" name="selected_types" id="bulkDeleteTypes">
                </form>

                @if($totalFolders > 0)
                    <div class="list-view" id="listView">
                        <table class="document-table">
                            <thead>
                                <tr>
                                    <th class="checkbox-cell">
                                        <input type="checkbox" id="selectAll">
                                    </th>
                                    <th style="width: 45%;">Name</th>
                                    <th>File type</th>
                                    <th>Size</th>
                                    <th>Modified</th>
                                    <th class="actions-cell"></th>
                                </tr>
                            </thead>
                            <tbody id="foldersTableBody">
                                {!! $folderTreeHtml !!}

                                @if($hasOthers)
                                    @php
                                        $othersDocuments = $documents->where('folder_id', null);
                                        $hasOthersChildren = count($othersDocuments) > 0;
                                    @endphp
                                    <tr class="folder-tree-row {{ $hasOthersChildren ? 'has-children' : '' }}" 
                                        data-folder-name="others"
                                        data-folder-id="others"
                                        data-level="0">
                                        <td class="checkbox-cell" onclick="event.stopPropagation()">
                                            <input type="checkbox" class="item-checkbox form-check-input" data-type="others" data-id="others" data-name="Others" disabled title="System folder — cannot be deleted" style="opacity: 0.35; cursor: not-allowed;">
                                        </td>
                                        <td class="folder-name-cell" data-folder-url="{{ url('documents/folder/others') }}" onclick="handleFolderClick(this, {{ $hasOthersChildren ? 'true' : 'false' }})">
                                            <div class="name-cell">
                                                @if($hasOthersChildren)
                                                    <span class="folder-toggle"><i class="ri-arrow-right-s-line"></i></span>
                                                @else
                                                    <span style="width: 20px; display: inline-block;"></span>
                                                @endif
                                                <i class="ri-folder-2-fill item-icon" style="color: #9ca3af;"></i>
                                                <span class="item-name" style="color: #9ca3af; font-style: italic;">Others</span>
                                            </div>
                                        </td>
                                        <td>Folder</td>
                                        <td>—</td>
                                        <td>—</td>
                                        <td class="actions-cell" onclick="event.stopPropagation()">
                                            <button class="action-btn">
                                                <i class="ri-more-2-fill"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    
                                    @foreach($othersDocuments as $doc)
                                        <tr class="child-row" data-parent-id="others" data-level="1" data-document-id="{{ $doc->id }}"
                                            onclick="window.location='{{ url('documents/view-document/'.$doc->id) }}'">
                                            <td class="checkbox-cell" onclick="event.stopPropagation()">
                                                <input type="checkbox" class="item-checkbox form-check-input" data-type="document" data-id="{{ $doc->id }}" data-name="{{ $doc->control_code }} - {{ $doc->title }}">
                                            </td>
                                            <td>
                                                <div class="name-cell">
                                                    <span class="folder-indent" style="width: 24px;"></span>
                                                    <span style="width: 20px; display: inline-block;"></span>
                                                    <i class="ri-file-text-line item-icon" style="color: #6b7280;"></i>
                                                    <span class="item-name">{{ $doc->control_code }} - {{ $doc->title }}</span>
                                                </div>
                                            </td>
                                            <td>Document</td>
                                            <td>—</td>
                                            <td>{{ date('M d, Y', strtotime($doc->updated_at)) }}</td>
                                            <td class="actions-cell" onclick="event.stopPropagation()">
                                                <button class="action-btn"><i class="ri-more-2-fill"></i></button>
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif
                            </tbody>
                        </table>
                    </div>

                    <div class="grid-view" id="gridView">
                        <div class="grid-container" id="gridContainer">
                            @foreach($allFolders->where('parent_id', null) as $folder)
                                <div class="grid-item" 
                                    data-folder-id="{{ $folder->id }}"
                                    data-folder-name="{{ strtolower($folder->name) }}"
                                    data-type="folder"
                                    data-id="{{ $folder->id }}"
                                    onclick="handleGridItemClick(event, this, '{{ url('documents/folder/'.$folder->id) }}')">
                                    <div class="grid-item-header">
                                        <input type="checkbox"
                                            class="grid-item-checkbox item-checkbox form-check-input"
                                            data-type="folder"
                                            data-id="{{ $folder->id }}"
                                            data-name="{{ $folder->name }}"
                                            onclick="event.stopPropagation(); handleGridCheckbox(this)">
                                        <button class="grid-item-menu" onclick="event.stopPropagation()" data-bs-toggle="dropdown" aria-expanded="false">
                                            <i class="ri-more-2-fill"></i>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end">
                                            <li>
                                                <a class="dropdown-item" href="javascript:void(0)" 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#renameFolderModal{{ $folder->id }}"
                                                onclick="event.stopPropagation()">
                                                    <i class="ri-pencil-line me-2"></i>Rename folder
                                                </a>
                                            </li>
                                            <li>
                                                <a class="dropdown-item text-danger delete-folder-btn" href="javascript:void(0)"
                                                data-id="{{ $folder->id }}" 
                                                data-name="{{ $folder->name }}">
                                                    <i class="ri-delete-bin-line me-2"></i>Delete folder
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                    <div class="grid-item-icon">
                                        <i class="ri-folder-2-fill"></i>
                                    </div>
                                    <div class="grid-item-name">{{ $folder->name }}</div>
                                    <div class="grid-item-meta">{{ date('M d, Y', strtotime($folder->updated_at)) }}</div>
                                </div>
                            @endforeach

                            @if($hasOthers)
                                <div class="grid-item" 
                                    data-folder-name="others"
                                    data-type="others"
                                    data-id="others"
                                    onclick="handleGridItemClick(event, this, '{{ url('documents/folder/others') }}')">
                                    <div class="grid-item-header">
                                        <input type="checkbox"
                                            class="grid-item-checkbox item-checkbox form-check-input"
                                            data-type="others"
                                            data-id="others"
                                            data-name="Others"
                                            disabled
                                            title="System folder — cannot be deleted"
                                            style="opacity: 0.35; cursor: not-allowed;"
                                            onclick="event.stopPropagation()">
                                        <button class="grid-item-menu" onclick="event.stopPropagation()">
                                            <i class="ri-more-2-fill"></i>
                                        </button>
                                    </div>
                                    <div class="grid-item-icon">
                                        <i class="ri-folder-2-fill" style="color: #9ca3af;"></i>
                                    </div>
                                    <div class="grid-item-name" style="color: #9ca3af; font-style: italic;">Others</div>
                                    <div class="grid-item-meta">—</div>
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="no-results" id="noResults">
                        <i class="ri-search-line"></i>
                        <p class="mb-0">No folders found matching your search.</p>
                    </div>

                    <div class="folder-count" id="folderCount">
                        <span id="visibleCount">{{ $totalFolders }}</span> folders
                    </div>
                @else
                    <div class="empty-state">
                        <div class="empty-icon">
                            <i class="ri-folder-user-line"></i>
                        </div>
                        <h3 class="empty-title">No folders found</h3>
                        <p class="empty-text">Folders you create will show up here.</p>
                        <button type="button" class="new-btn" data-bs-toggle="modal" data-bs-target="#createFolderModal">
                            <i class="ri-add-line"></i>
                            New Folder
                        </button>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="deleteConfirmModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content delete-modal">
            <div class="modal-header border-0 pb-0">
                <div class="delete-modal-icon">
                    <i class="ri-error-warning-line"></i>
                </div>
            </div>
            <div class="modal-body text-center pt-2">
                <h5 class="delete-modal-title">Delete Items</h5>
                <p class="delete-modal-desc" id="deleteModalDesc">
                    Are you sure you want to delete the selected items? This action cannot be undone.
                </p>
                <div id="deleteWarning" class="delete-warning" style="display:none;">
                    <i class="ri-alert-line"></i>
                    <span id="deleteWarningText"></span>
                </div>
            </div>
            <div class="modal-footer border-0 justify-content-center gap-2 pt-0">
                <button type="button" class="btn btn-outline-secondary px-4" data-bs-dismiss="modal">Cancel</button>
                <input type="hidden" id="deleteFolderIds">
                <input type="hidden" id="deleteDocumentIds">
                <button type="button" class="btn btn-danger px-4" id="confirmDeleteBtn">
                    <i class="ri-delete-bin-line me-1"></i> Delete
                </button>
            </div>
        </div>
    </div>
</div>

@include('documents.upload_document')
@include('documents.add_folder')
@include('documents.add_documents_in_folder')
@foreach ($document_folders as $folder)
    @include('documents.rename_folder')
@endforeach
@endsection

@section('css')
<link href="{{ asset('login_css/css/plugins/chosen/bootstrap-chosen.css') }}" rel="stylesheet">
<link href="{{ asset('login_css/css/plugins/sweetalert/sweetalert.css') }}" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<style>
    .document-manager {
        background: #fff;
        min-height: 100vh;
    }

    .top-toolbar {
        display: flex;
        align-items: center;
        gap: 1.5rem;
        padding: 1.5rem 2rem;
        border-bottom: 1px solid #e5e7eb;
        flex-wrap: wrap;
    }

    .new-btn {
        background: #0078d4;
        color: white;
        border: none;
        padding: 0.5rem 1.25rem;
        border-radius: 4px;
        font-weight: 500;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        transition: background 0.2s;
    }

    .new-btn:hover {
        background: #006cc2;
    }

    .dropdown-menu {
        z-index: 1000;
    }

    .bulk-actions-bar {
        display: none;
        align-items: center;
        gap: 0.75rem;
        padding: 0.6rem 2rem;
        background: #f0f7ff;
        border-bottom: 1px solid #bae0ff;
    }

    .bulk-actions-bar.visible {
        display: flex;
    }

    .selected-badge {
        background: #e6f4ff;
        color: #0078d4;
        border: 1px solid #bae0ff;
        border-radius: 20px;
        padding: 0.2rem 0.7rem;
        font-size: 0.8rem;
        font-weight: 600;
        white-space: nowrap;
    }

    .delete-btn {
        background: #dc2626;
        color: white;
        border: none;
        padding: 0.4rem 0.9rem;
        border-radius: 4px;
        font-weight: 500;
        font-size: 0.825rem;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 0.4rem;
        transition: background 0.2s;
    }

    .delete-btn:hover {
        background: #b91c1c;
    }

    .cancel-select-btn {
        background: transparent;
        color: #6b7280;
        border: 1px solid #d1d5db;
        padding: 0.4rem 0.9rem;
        border-radius: 4px;
        font-weight: 500;
        font-size: 0.825rem;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 0.4rem;
        transition: all 0.2s;
    }

    .cancel-select-btn:hover {
        background: #f3f4f6;
        color: #111827;
    }

    .document-table tbody tr.row-selected {
        background: #e6f4ff !important;
    }

    .document-table tbody tr.row-selected td {
        border-bottom-color: #bae0ff;
    }

    .grid-item.grid-selected {
        border-color: #0078d4;
        background: #e6f4ff;
        box-shadow: 0 0 0 2px rgba(0,120,212,0.25);
    }

    .grid-item-checkbox {
        opacity: 0;
        transition: opacity 0.15s;
        cursor: pointer;
        width: 16px;
        height: 16px;
    }

    .grid-item:hover .grid-item-checkbox,
    .grid-item.grid-selected .grid-item-checkbox {
        opacity: 1;
    }

    .delete-modal {
        border-radius: 12px;
        border: none;
        box-shadow: 0 20px 60px rgba(0,0,0,0.15);
    }

    .delete-modal-icon {
        width: 56px;
        height: 56px;
        background: #fef2f2;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 1rem auto 0;
    }

    .delete-modal-icon i {
        font-size: 1.75rem;
        color: #dc2626;
    }

    .delete-modal-title {
        font-size: 1.1rem;
        font-weight: 700;
        color: #111827;
        margin-bottom: 0.5rem;
    }

    .delete-modal-desc {
        color: #6b7280;
        font-size: 0.9rem;
        margin-bottom: 0.5rem;
    }

    .delete-warning {
        background: #fffbeb;
        border: 1px solid #fde68a;
        border-radius: 6px;
        padding: 0.6rem 0.9rem;
        font-size: 0.825rem;
        color: #92400e;
        display: flex;
        align-items: flex-start;
        gap: 0.5rem;
        text-align: left;
        margin-top: 0.5rem;
    }

    .delete-warning i {
        flex-shrink: 0;
        margin-top: 1px;
    }

    .search-wrapper {
        flex: 1;
        max-width: 400px;
    }

    .search-input-wrapper {
        position: relative;
        width: 100%;
    }

    .search-input-wrapper input {
        width: 100%;
        padding: 0.5rem 1rem 0.5rem 2.5rem;
        border: 1px solid #e5e7eb;
        border-radius: 6px;
        font-size: 0.875rem;
        transition: all 0.2s;
    }

    .search-input-wrapper input:focus {
        outline: none;
        border-color: #0078d4;
        box-shadow: 0 0 0 3px rgba(0, 120, 212, 0.1);
    }

    .search-input-wrapper i {
        position: absolute;
        left: 0.75rem;
        top: 50%;
        transform: translateY(-50%);
        color: #6b7280;
        font-size: 1.125rem;
    }

    .clear-search-btn {
        position: absolute;
        right: 0.5rem;
        top: 50%;
        transform: translateY(-50%);
        background: #f3f4f6;
        border: none;
        border-radius: 4px;
        padding: 0.25rem 0.5rem;
        color: #6b7280;
        cursor: pointer;
        font-size: 0.75rem;
        transition: all 0.2s;
    }

    .clear-search-btn:hover {
        background: #e5e7eb;
        color: #1f2937;
    }

    .view-toggle {
        display: flex;
        gap: 0.25rem;
        background: #f3f4f6;
        padding: 0.25rem;
        border-radius: 6px;
    }

    .view-toggle-btn {
        background: transparent;
        border: none;
        padding: 0.5rem 0.75rem;
        border-radius: 4px;
        cursor: pointer;
        color: #6b7280;
        transition: all 0.2s;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .view-toggle-btn:hover { color: #1f2937; }

    .view-toggle-btn.active {
        background: white;
        color: #0078d4;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    }

    .view-toggle-btn i { font-size: 1.125rem; }

    .bulk-action-toolbar {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 0.75rem 2rem;
        background: #1e3a5f;
        color: white;
        border-bottom: 1px solid #164078;
        animation: slideDown 0.2s ease;
    }

    @keyframes slideDown {
        from { transform: translateY(-100%); opacity: 0; }
        to { transform: translateY(0); opacity: 1; }
    }

    .bulk-info {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 0.875rem;
        font-weight: 500;
    }

    .bulk-info i {
        font-size: 1.125rem;
    }

    .bulk-actions {
        display: flex;
        gap: 0.75rem;
    }

    .bulk-delete-btn {
        background: #dc2626;
        color: white;
        border: none;
        padding: 0.4rem 1rem;
        border-radius: 4px;
        font-size: 0.875rem;
        font-weight: 500;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 0.4rem;
        transition: background 0.2s;
    }

    .bulk-delete-btn:hover {
        background: #b91c1c;
    }

    .bulk-cancel-btn {
        background: transparent;
        color: white;
        border: 1px solid rgba(255,255,255,0.4);
        padding: 0.4rem 1rem;
        border-radius: 4px;
        font-size: 0.875rem;
        font-weight: 500;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 0.4rem;
        transition: all 0.2s;
    }

    .bulk-cancel-btn:hover {
        background: rgba(255,255,255,0.1);
        border-color: rgba(255,255,255,0.7);
    }

    .list-view {
        display: block;
    }

    .grid-view {
        display: none;
    }

    .folder-tree-row {
        transition: background 0.15s;
        cursor: pointer;
    }

    .folder-tree-row { transition: background 0.15s; cursor: pointer; }
    .folder-tree-row.has-children { cursor: pointer; }
    .folder-tree-row.expanded > td { background: #f9fafb; }
    .folder-indent { display: inline-block; }

    .folder-toggle {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 20px;
        height: 20px;
        margin-right: 0.5rem;
        cursor: pointer;
        color: #6b7280;
        transition: transform 0.2s;
    }

    .folder-toggle i { font-size: 0.875rem; }
    .folder-toggle.expanded { transform: rotate(90deg); }

    .child-row { display: none; cursor: pointer; }
    .child-row.show { display: table-row; }
    .folder-name-cell { cursor: pointer; }

    .document-table { width: 100%; border-collapse: collapse; }
    .document-table thead { border-bottom: 1px solid #e5e7eb; }

    .document-table th {
        text-align: left;
        padding: 1rem 1.5rem;
        font-weight: 500;
        font-size: 0.875rem;
        color: #6b7280;
        background: transparent;
    }

    .document-table tbody tr {
        border-bottom: 1px solid #f3f4f6;
        transition: background 0.15s;
    }

    .document-table tbody tr:hover { background: #f9fafb; }

    .document-table td {
        padding: 0.875rem 1.5rem;
        font-size: 0.875rem;
        color: #1f2937;
    }

    .checkbox-cell { width: 40px; }
    .checkbox-cell input[type="checkbox"] { cursor: pointer; }

    .name-cell { display: flex; align-items: center; gap: 0.75rem; }

    .item-icon { font-size: 1.25rem; color: #0078d4; }

    .item-name {
        font-weight: 500;
        color: #1f2937;
        text-decoration: none;
    }

    .item-name:hover {
        text-decoration: underline;
        color: #0078d4;
    }

    .actions-cell { width: 50px; text-align: center; }

    .action-btn {
        background: transparent;
        border: none;
        padding: 0.5rem;
        cursor: pointer;
        color: #6b7280;
        border-radius: 4px;
        opacity: 0;
        transition: all 0.2s;
    }

    .document-table tbody tr:hover .action-btn { opacity: 1; }
    .action-btn:hover { background: #f3f4f6; color: #111827; }

    .grid-container {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
        gap: 1.5rem;
        padding: 2rem;
    }

    .grid-item {
        background: white;
        border: 1px solid #e5e7eb;
        border-radius: 8px;
        padding: 1rem;
        cursor: pointer;
        transition: all 0.2s;
        position: relative;
        overflow: visible;
    }

    .grid-item .dropdown-menu {
        position: absolute !important;
        z-index: 1000 !important;
        min-width: 160px;
    }

    .grid-item:hover {
        border-color: #0078d4;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        transform: translateY(-2px);
    }

    .grid-item.selected-item {
        border-color: #0078d4;
        background: #e6f4ff;
        box-shadow: 0 0 0 2px #0078d4;
        transform: translateY(-2px);
    }

    .grid-item-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 0.75rem;
    }

    .grid-item-menu {
        background: transparent;
        border: none;
        padding: 0.25rem;
        cursor: pointer;
        color: #6b7280;
        border-radius: 4px;
        opacity: 0;
        transition: all 0.2s;
    }

    .grid-item:hover .grid-item-menu { opacity: 1; }
    .grid-item-menu:hover { background: #f3f4f6; color: #111827; }

    .grid-item-icon {
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 0.75rem;
    }

    .grid-item-icon i { font-size: 4rem; color: #0078d4; }
    .grid-item-icon.document-icon i { color: #6b7280; }

    .grid-item-name {
        font-size: 0.875rem;
        font-weight: 500;
        color: #1f2937;
        text-align: center;
        word-break: break-word;
        line-height: 1.4;
    }

    .grid-item-meta {
        font-size: 0.75rem;
        color: #6b7280;
        text-align: center;
        margin-top: 0.5rem;
    }

    .empty-state {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        padding: 6rem 2rem;
        text-align: center;
    }

    .empty-icon { font-size: 5rem; color: #9ca3af; margin-bottom: 1.5rem; }
    .empty-title { font-size: 1.5rem; font-weight: 600; color: #1f2937; margin-bottom: 0.5rem; }
    .empty-text { color: #6b7280; margin-bottom: 1.5rem; }

    .folder-count {
        padding: 1rem 2rem;
        font-size: 0.875rem;
        color: #6b7280;
        border-top: 1px solid #e5e7eb;
    }

    .no-results {
        display: none;
        padding: 3rem 2rem;
        text-align: center;
        color: #6b7280;
    }

    .no-results i {
        font-size: 3rem;
        margin-bottom: 1rem;
        color: #9ca3af;
    }

    .folder-tree-row.selected-row {
        background: #e6f4ff !important;
    }

    .folder-tree-row {
        outline: none;
    }
</style>
@endsection

@section('js')
<script src="{{ asset('login_css/js/plugins/dataTables/datatables.min.js')}}"></script>
<script src="{{ asset('login_css/js/plugins/chosen/chosen.jquery.js') }}"></script>
<script src="{{ asset('login_css/js/plugins/sweetalert/sweetalert.min.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="{{ asset('js/BootstrapMenu.min.js') }}"></script>

<script>
    let selectedItems = {};
    let clickTimer    = null;
    let selectedRow   = null;
    let currentView   = 'list';

    function deleteDocument(id, name) {
        swal({
            title: 'Are you sure?',
            text: 'Delete "' + name + '"? This action cannot be undone.',
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc2626',
            confirmButtonText: 'Delete',
            cancelButtonText: 'Cancel',
            closeOnConfirm: false,
            closeOnCancel: true
        }, function(confirmed) {
            if (!confirmed) return;
            $.ajax({
                url: '{{ url("documents/bulk-delete") }}',
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    folder_ids: '',
                    document_ids: id
                },
                success: function(response) {
                    if (response.success) {
                        swal('Deleted!', 'Document successfully deleted.', 'success');
                        setTimeout(function() { window.location.reload(); }, 1500);
                    } else {
                        swal('Cannot Delete!', response.message, 'error');
                    }
                },
                error: function() {
                    swal('Error!', 'Something went wrong. Please try again.', 'error');
                }
            });
        });
    }

    function getSelectedCount() {
        return Object.keys(selectedItems).length;
    }

    function syncBulkBar() {
        const count = getSelectedCount();
        if (count > 0) {
            $('#bulkActionsBar').addClass('visible');
            $('#selectedCountBadge').text(count + ' selected');
        } else {
            $('#bulkActionsBar').removeClass('visible');
        }
    }

    function selectItem(type, id, name) {
        selectedItems[type + '_' + id] = { type, id, name };
        syncBulkBar();
    }

    function deselectItem(type, id) {
        delete selectedItems[type + '_' + id];
        syncBulkBar();
    }

    function clearAllSelections() {
        selectedItems = {};
        $('.item-checkbox:not(:disabled)').prop('checked', false);
        $('#selectAll').prop('checked', false).prop('indeterminate', false);
        $('.folder-tree-row, .child-row').removeClass('selected-row row-selected');
        $('.grid-item').removeClass('selected-item grid-selected');
        updateBulkToolbar();
    }

    function handleListCheckbox(checkbox) {
        const $cb  = $(checkbox);
        const type = $cb.data('type');
        const id   = $cb.data('id');
        const name = $cb.data('name') || $cb.closest('tr').find('.item-name').text().trim();
        const $row = $cb.closest('tr');

        if ($cb.is(':checked')) {
            selectItem(type, id, name);
            $row.addClass('row-selected');
        } else {
            deselectItem(type, id);
            $row.removeClass('row-selected');
        }
    }

    function handleGridCheckbox(checkbox) {
        const $cb   = $(checkbox);
        const type  = $cb.data('type');
        const id    = $cb.data('id');
        const name  = $cb.data('name') || $cb.closest('.grid-item').find('.grid-item-name').text().trim();
        const $item = $cb.closest('.grid-item');

        if ($cb.is(':checked')) {
            selectItem(type, id, name);
            $item.addClass('selected-item grid-selected');
        } else {
            deselectItem(type, id);
            $item.removeClass('selected-item grid-selected');
        }

        updateBulkToolbar();
    }

    function handleGridItemClick(event, element, url) {
        if ($(event.target).is('input[type="checkbox"]') ||
            $(event.target).closest('.grid-item-menu').length ||
            $(event.target).closest('.dropdown-menu').length) {
            return;
        }
        window.location = url;
    }

    function openDeleteModal() {
        const folderIds   = [];
        const documentIds = [];
        const names       = [];
        let hasOthers     = false;

        for (const key in selectedItems) {
            const item = selectedItems[key];
            names.push('"' + item.name + '"');

            if (item.type === 'folder') {
                folderIds.push(item.id);
            } else if (item.type === 'document') {
                documentIds.push(item.id);
            } else if (item.type === 'others') {
                hasOthers = true;
            }
        }

        const count = getSelectedCount();
        const desc  = count === 1
            ? 'Are you sure you want to delete ' + names[0] + '? This action cannot be undone.'
            : 'Are you sure you want to delete ' + count + ' selected items? This action cannot be undone.';

        $('#deleteModalDesc').text(desc);
        $('#deleteFolderIds').val(folderIds.join(','));
        $('#deleteDocumentIds').val(documentIds.join(','));

        const warnings = [];
        if (folderIds.length > 0) {
            warnings.push('Folders that contain files cannot be deleted. Empty them first.');
        }
        if (hasOthers) {
            warnings.push('"Others" is a system folder and cannot be deleted.');
        }

        if (warnings.length > 0) {
            $('#deleteWarning').show();
            $('#deleteWarningText').text(warnings.join(' '));
            if (folderIds.length === 0 && documentIds.length === 0 && hasOthers) {
                $('#confirmDeleteBtn').prop('disabled', true);
            } else {
                $('#confirmDeleteBtn').prop('disabled', false);
            }
        } else {
            $('#deleteWarning').hide();
            $('#confirmDeleteBtn').prop('disabled', false);
        }

        $('#deleteConfirmModal').modal('show');
    }

    function handleFolderClick(element, hasChildren) {
        event.stopPropagation();

        const row = $(element).closest('tr');

        $('.folder-tree-row').removeClass('selected-row');
        row.addClass('selected-row');
        selectedRow = row;

        if (clickTimer === null) {
            clickTimer = setTimeout(function() {
                if (hasChildren) {
                    toggleFolder(element);
                }
                clickTimer = null;
            }, 250);
        } else {
            clearTimeout(clickTimer);
            clickTimer = null;
            window.location = $(element).data('folder-url');
        }
    }

    function toggleFolder(element) {
        const row        = $(element).closest('tr');
        const folderId   = row.data('folder-id') || 'others';
        const toggle     = row.find('.folder-toggle');
        const isExpanded = toggle.hasClass('expanded');

        if (isExpanded) {
            toggle.removeClass('expanded');
            row.removeClass('expanded');
            hideChildren(folderId);
        } else {
            toggle.addClass('expanded');
            row.addClass('expanded');
            showChildren(folderId);
        }
    }

    function showChildren(parentId) {
        $('tr[data-parent-id="' + parentId + '"]').each(function() {
            $(this).addClass('show');
            const childFolderId = $(this).data('folder-id');
            if (childFolderId && $(this).find('.folder-toggle').hasClass('expanded')) {
                showChildren(childFolderId);
            }
        });
    }

    function hideChildren(parentId) {
        $('tr[data-parent-id="' + parentId + '"]').each(function() {
            $(this).removeClass('show');
            const childFolderId = $(this).data('folder-id');
            if (childFolderId) {
                $(this).find('.folder-toggle').removeClass('expanded');
                hideChildren(childFolderId);
            }
        });
    }

    function switchToListView() {
        currentView = 'list';
        $('#listView').css('display', 'block');
        $('#gridView').css('display', 'none');
        $('#listViewBtn').addClass('active');
        $('#gridViewBtn').removeClass('active');
        localStorage.setItem('documentViewPreference', 'list');
    }

    function switchToGridView() {
        currentView = 'grid';
        $('#listView').css('display', 'none');
        $('#gridView').css('display', 'block');
        $('#listViewBtn').removeClass('active');
        $('#gridViewBtn').addClass('active');
        localStorage.setItem('documentViewPreference', 'grid');
    }

    function getSelectedItems() {
        const selected = [];

        $('#foldersTableBody tr').each(function() {
            const checkbox = $(this).find('.form-check-input');
            if (!checkbox.is(':checked')) return;

            const row = $(this);

            if (row.hasClass('folder-tree-row')) {
                const folderId = row.data('folder-id');
                if (folderId && folderId !== 'others') {
                    const exists = selected.some(i => String(i.id) === String(folderId) && i.type === 'folder');
                    if (!exists) selected.push({ id: folderId, type: 'folder' });
                }
            } else if (row.hasClass('child-row')) {
                const docId = row.data('document-id');
                if (docId) {
                    const exists = selected.some(i => String(i.id) === String(docId) && i.type === 'document');
                    if (!exists) selected.push({ id: docId, type: 'document' });
                }
            }
        });

        $('#gridContainer .grid-item-checkbox:checked:not(:disabled)').each(function() {
            const $cb = $(this);
            const type = $cb.data('type') || 'folder';
            const id   = $cb.data('id');
            if (id && type !== 'others') {
                const exists = selected.some(i => String(i.id) === String(id) && i.type === type);
                if (!exists) selected.push({ id, type });
            }
        });

        return selected;
    }

    function updateBulkToolbar() {
        const selected = getSelectedItems();
        const count = selected.length;

        if (count > 0) {
            $('#bulkActionToolbar').slideDown(150);
            $('#selectedCount').text(count);
        } else {
            $('#bulkActionToolbar').slideUp(150);
        }
    }

    function setChosenValue(selector, value) {
        $(selector).val(value).trigger('chosen:updated');
    }

    function resetUploadForm() {
        $('#titleField').val('');
        $('#revisionField').val('');
        $('#revisionAutoIcon').hide();
        $('#revisionHint').hide().text('');
        $('#revisionInfoBox').hide();
        $('#manualControlCodeWrapper').hide();
        $('#manualControlCode').val('').removeAttr('required');
        $('#selectedControlCode').val('');
        $('#isRevision').val('0');
        $('#newDocBadge').hide();
        $('#revisionBadge').hide();
        setChosenValue('#documentTypeField', '');
        setChosenValue('#folderField', '');
        setChosenValue('#typeOfRequestField', '');
    }

    $(document).ready(function() {

        // ---- Existing page JS ----
        $('.cat').chosen({width: "100%"});

        $('.select2').select2({
            dropdownParent: $('#addDocumentInFolder'),
            theme: "classic"
        });

        const savedView = localStorage.getItem('documentViewPreference');
        if (savedView === 'grid') {
            switchToGridView();
        }

        $('#listViewBtn').on('click', switchToListView);
        $('#gridViewBtn').on('click', switchToGridView);

        $('#selectAll').on('change', function() {
            const checked = $(this).prop('checked');
            if (currentView === 'list') {
                $('.folder-tree-row:visible .item-checkbox:not(:disabled)').each(function() {
                    $(this).prop('checked', checked);
                    handleListCheckbox(this);
                });
            }
            if (!checked) {
                clearAllSelections();
            }
        });

        $(document).on('change', '.folder-tree-row .item-checkbox:not(:disabled), .child-row .item-checkbox:not(:disabled)', function() {
            handleListCheckbox(this);
            const total   = $('.folder-tree-row:visible .item-checkbox:not(:disabled), .child-row:visible .item-checkbox:not(:disabled)').length;
            const checked = $('.folder-tree-row:visible .item-checkbox:not(:disabled):checked, .child-row:visible .item-checkbox:not(:disabled):checked').length;
            $('#selectAll').prop('indeterminate', checked > 0 && checked < total);
            $('#selectAll').prop('checked', checked === total && total > 0);
        });

        $('#selectAll').on('change', function() {
            const checked = $(this).prop('checked');
            $('#foldersTableBody tr:visible .form-check-input').prop('checked', checked);
            updateBulkToolbar();
        });

        $(document).on('change', '#foldersTableBody .form-check-input', function() {
            updateBulkToolbar();
        });

        $(document).on('keydown', function(e) {
            if (e.key === 'Escape') {
                clearAllSelections();
            }
            if (e.key === 'Enter' && selectedRow && selectedRow.hasClass('folder-tree-row')) {
                e.preventDefault();
                const folderUrl = selectedRow.find('.folder-name-cell').data('folder-url');
                if (folderUrl) {
                    window.location = folderUrl;
                }
            }
        });

        $(document).on('click', '.grid-item', function(e) {
            if (
                $(e.target).hasClass('grid-item-menu') ||
                $(e.target).closest('.grid-item-menu').length ||
                $(e.target).hasClass('grid-item-checkbox') ||
                $(e.target).closest('.dropdown-menu').length ||
                $(e.target).hasClass('delete-folder-btn') ||
                $(e.target).closest('.delete-folder-btn').length
            ) {
                return;
            }

            if ($('#bulkActionToolbar').is(':visible') || $('.grid-item.selected-item').length > 0) {
                e.preventDefault();
                e.stopImmediatePropagation();
                $(this).toggleClass('selected-item');
                const isSelected = $(this).hasClass('selected-item');
                $(this).find('.grid-item-checkbox').prop('checked', isSelected);
                updateBulkToolbar();
                return false;
            }
        });

        $('#bulkCancelBtn').on('click', function() {
            clearAllSelections();
        });

        $('#bulkDeleteBtn').on('click', function() {
            const selected = getSelectedItems();
            if (selected.length === 0) return;

            const folderIds   = selected.filter(i => i.type === 'folder').map(i => i.id);
            const documentIds = selected.filter(i => i.type === 'document').map(i => i.id);

            let message = 'You are about to delete ' + selected.length + ' item(s)';
            if (folderIds.length > 0) {
                message += ' including ' + folderIds.length + ' folder(s) and all their contents';
            }
            message += '. This cannot be undone.';

            swal({
                title: 'Are you sure?',
                text: message,
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc2626',
                confirmButtonText: 'Delete',
                cancelButtonText: 'Cancel',
                closeOnConfirm: false,
                closeOnCancel: true
            }, function(confirmed) {
                if (confirmed) {
                    $.ajax({
                        url: '{{ url("documents/bulk-delete") }}',
                        type: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}',
                            folder_ids: folderIds.join(','),
                            document_ids: documentIds.join(',')
                        },
                        success: function(response) {
                            swal('Deleted!', 'Items successfully deleted.', 'success');
                            setTimeout(function() {
                                window.location.reload();
                            }, 1500);
                        },
                        error: function(xhr) {
                            swal('Error!', 'Something went wrong. Please try again.', 'error');
                        }
                    });
                }
            });
        });

        $(document).on('click', '.delete-folder-btn', function(e) {
            e.stopPropagation();
            e.preventDefault();
            
            const id   = $(this).data('id');
            const name = $(this).data('name');

            swal({
                title: 'Are you sure?',
                text: 'Delete folder "' + name + '"? This action cannot be undone.',
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc2626',
                confirmButtonText: 'Delete',
                cancelButtonText: 'Cancel',
                closeOnConfirm: false,
                closeOnCancel: true
            }, function(confirmed) {
                if (!confirmed) return;
                $.ajax({
                    url: '{{ url("documents/delete-folder") }}/' + id,
                    type: 'POST',
                    data: { _token: '{{ csrf_token() }}', _method: 'DELETE' },
                    success: function(response) {
                        if (response.success) {
                            swal('Deleted!', 'Folder successfully deleted.', 'success');
                            setTimeout(function() { window.location.reload(); }, 1500);
                        } else {
                            swal('Cannot Delete!', response.message, 'error');
                        }
                    },
                    error: function() {
                        swal('Error!', 'Something went wrong. Please try again.', 'error');
                    }
                });
            });
        });


        const searchInput = $('#folderSearch');
        const clearBtn = $('#clearSearch');
        const noResults = $('#noResults');
        const folderCount = $('#folderCount');
        const tableBody = $('#foldersTableBody');
        const gridContainer = $('#gridContainer');

        searchInput.on('input', function() {
            const searchTerm = $(this).val().toLowerCase().trim();

            clearBtn.toggle(searchTerm.length > 0);

            let visibleCount = 0;

            if (currentView === 'list') {
                $('.folder-tree-row').each(function() {
                    const folderName = $(this).data('folder-name');
                    const level      = $(this).data('level');

                    if (level === 0 && folderName && folderName.includes(searchTerm)) {
                        $(this).show();
                        visibleCount++;
                    } else if (level === 0) {
                        $(this).hide();
                    }
                });

                if (searchTerm.length > 0) {
                    $('.child-row').hide();
                    $('.folder-toggle').removeClass('expanded');
                }

                if (visibleCount === 0 && searchTerm.length > 0) {
                    tableBody.hide();
                    folderCount.hide();
                    noResults.show();
                } else {
                    tableBody.show();
                    folderCount.show();
                    noResults.hide();
                }
            } else {
                $('.grid-item[data-type="folder"], .grid-item[data-type="others"]').each(function() {
                    const folderName = $(this).data('folder-name');
                    if (folderName && folderName.includes(searchTerm)) {
                        $(this).show();
                        visibleCount++;
                    } else {
                        $(this).hide();
                    }
                });

                if (visibleCount === 0 && searchTerm.length > 0) {
                    gridContainer.hide();
                    folderCount.hide();
                    noResults.show();
                } else {
                    gridContainer.show();
                    folderCount.show();
                    noResults.hide();
                }
            }

            $('#visibleCount').text(visibleCount);
        });

        clearBtn.on('click', function() {
            searchInput.val('').trigger('input');
            searchInput.focus();
        });

        searchInput.on('keydown', function(e) {
            if (e.key === 'Escape') {
                $(this).val('').trigger('input');
            }
        });

        var menu = new BootstrapMenu('.demoTableRow', {
            fetchElementData: function($rowElem) {
                return { id: $rowElem.data('folder-id') };
            },
            onBeforeShow: function($rowElem, event) {
                if ($(event.target).closest('.actions-cell').length > 0 ||
                    $(event.target).closest('.dropdown-menu').length > 0 ||
                    $(event.target).closest('.dropdown').length > 0) {
                    return false;
                }
            },
            actions: {
                renameFolder: {
                    name: 'Rename folder',
                    iconClass: 'fa-pencil',
                    onClick: function(folder) {
                        $("#renameFolderModal" + folder.id).modal("show");
                    }
                },
                moveFileFolder: {
                    name: 'Move file',
                    iconClass: "ri-drag-move-2-line",
                    onClick: function(folder) {
                        $("#addDocumentInFolder").modal("show");
                        $("#moveDocumentFolder").val(folder.id);
                    }
                }
            }
        });

        $('#controlCodeSelect').select2({
            dropdownParent: $('#uploadDocument'),
            placeholder: '— Search or select a control code —',
            allowClear: true,
            width: '100%',
        });

        $('#controlCodeSelect').on('change', function () {
            var val       = $(this).val();
            var $selected = $(this).find('option:selected');

            if (!val) {
                resetUploadForm();
                return;
            }

            if (val === '__OTHER__') {
                resetUploadForm();
                $('#manualControlCodeWrapper').show();
                $('#manualControlCode').attr('required', true);
                $('#newDocBadge').show();
                return;
            }

            resetUploadForm();

            var title        = $selected.data('title')    || '';
            var docType      = $selected.data('type')     || '';
            var folderId     = $selected.data('folder')   || '';
            var other        = $selected.data('other')    || '';
            var curRevision  = parseInt($selected.data('revision') || 0);
            var nextRevision = curRevision + 1;

            $('#selectedControlCode').val(val);
            $('#titleField').val(title);
            $('#otherField').val(other);
            $('#isRevision').val('1');
            $('#revisionBadge').show();

            setChosenValue('#documentTypeField', docType);
            setChosenValue('#folderField', folderId);
            setChosenValue('#typeOfRequestField', 'Revision');

            $('#revisionField').val(nextRevision);
            $('#revisionAutoIcon').show();
            $('#revisionHint').show().text('(was ' + curRevision + ', auto-incremented to ' + nextRevision + ')');

            $('#revisionInfoText').html(
                'You are uploading <strong>Revision ' + nextRevision + '</strong> of ' +
                '<strong>' + val + '</strong>. ' +
                'Previous revision: <strong>' + curRevision + '</strong>.'
            );
            $('#revisionInfoBox').show();
        });

        $('#uploadDocument').on('hidden.bs.modal', function () {
            $('#controlCodeSelect').val(null).trigger('change');
            resetUploadForm();
        });

        $('#uploadDocumentForm').on('submit', function () {
            var val = $('#controlCodeSelect').val();
            if (val && val !== '__OTHER__') {
                $('#manualControlCode').removeAttr('name');
            } else {
                $('#manualControlCode').attr('name', 'control_code');
                $('#selectedControlCode').removeAttr('name');
            }
        });

    });
</script>
@endsection