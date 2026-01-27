@extends('layouts.header')

@section('content')
<div class="document-manager mb-5">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="breadcrumb">
                    <a href="{{ url('documents') }}">
                        <i class="ri-folder-line"></i> Documents
                    </a>
                    
                    @if(!isset($is_others_folder) || !$is_others_folder)
                        @foreach($breadcrumbs as $crumb)
                            <span class="breadcrumb-separator">/</span>
                            @if($loop->last)
                                <span>{{ $crumb->name }}</span>
                            @else
                                <a href="{{ url('documents/folder/'.$crumb->id) }}">{{ $crumb->name }}</a>
                            @endif
                        @endforeach
                    @else
                        <span class="breadcrumb-separator">/</span>
                        <span>Others</span>
                    @endif
                </div>

                <div class="top-toolbar">
                    <div class="view-options">
                        <div style="position: relative;">
                            <button class="view-btn" id="typeFilterBtn" title="Type">
                                <i class="ri-file-list-line"></i>
                                <span class="ms-1">Type</span>
                            </button>
                            <div class="filter-dropdown" id="typeFilterDropdown">
                                <div class="filter-option" data-type="all">
                                    <input type="checkbox" id="type-all" checked>
                                    <label for="type-all" style="cursor: pointer; margin: 0;">All Types</label>
                                </div>
                                <div class="filter-option" data-type="folder">
                                    <input type="checkbox" id="type-folder" checked>
                                    <label for="type-folder" style="cursor: pointer; margin: 0;">Folders</label>
                                </div>
                                <div class="filter-option" data-type="pdf">
                                    <input type="checkbox" id="type-pdf" checked>
                                    <label for="type-pdf" style="cursor: pointer; margin: 0;">PDF</label>
                                </div>
                                <div class="filter-option" data-type="docx">
                                    <input type="checkbox" id="type-docx" checked>
                                    <label for="type-docx" style="cursor: pointer; margin: 0;">Word Document</label>
                                </div>
                                <div class="filter-option" data-type="xlsx">
                                    <input type="checkbox" id="type-xlsx" checked>
                                    <label for="type-xlsx" style="cursor: pointer; margin: 0;">Excel</label>
                                </div>
                            </div>
                        </div>
                        <div style="position: relative;">
                            <button class="view-btn" id="modifiedFilterBtn" title="Modified">
                                <i class="ri-calendar-line"></i>
                                <span class="ms-1">Modified</span>
                            </button>
                            <div class="filter-dropdown" id="modifiedFilterDropdown">
                                <div class="filter-option" data-days="all">
                                    <span>All Time</span>
                                </div>
                                <div class="filter-option" data-days="1">
                                    <span>Last 24 Hours</span>
                                </div>
                                <div class="filter-option" data-days="7">
                                    <span>Last 7 Days</span>
                                </div>
                                <div class="filter-option" data-days="30">
                                    <span>Last 30 Days</span>
                                </div>
                                <div class="filter-option" data-days="90">
                                    <span>Last 90 Days</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="search-wrapper ms-3">
                        <form method="GET" action="" class="d-flex align-items-center">
                            <div class="position-relative">
                                <input type="text" 
                                       name="search" 
                                       class="form-control" 
                                       placeholder="Search files and folders..." 
                                       value="{{ request('search') }}"
                                       style="padding-left: 2.5rem; min-width: 300px;">
                                <i class="ri-search-line position-absolute" style="left: 0.75rem; top: 50%; transform: translateY(-50%); color: #6b7280;"></i>
                            </div>
                            @if(request('search'))
                                <a href="{{ url()->current() }}" class="btn btn-sm btn-light ms-2">
                                    <i class="ri-close-line"></i>
                                </a>
                            @endif
                        </form>
                    </div>
                    <div class="d-flex align-items-center ms-auto gap-2">
                        @if(!isset($is_others_folder) || !$is_others_folder)
                        <div class="ms-auto">
                            <div class="dropdown">
                                <button type="button" class="new-btn" data-bs-toggle="dropdown">
                                    <i class="ri-add-line"></i>
                                    New
                                </button>
                                <div class="dropdown-menu">
                                    <a class="dropdown-item" href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#uploadDocument">
                                        <i class="ri-file-add-line me-2"></i>New file
                                    </a>
                                    <a class="dropdown-item" href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#createFolderModal">
                                        <i class="ri-folder-add-line me-2"></i>New folder
                                    </a>
                                </div>
                            </div>
                        </div>
                        @endif

                        <div class="view-toggle">
                            <button class="view-toggle-btn active" id="listViewBtn" title="List view">
                                <i class="ri-list-check"></i>
                            </button>
                            <button class="view-toggle-btn" id="gridViewBtn" title="Grid view">
                                <i class="ri-grid-fill"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <div id="activeFiltersContainer" class="px-4 py-2 border-bottom" style="display: none;">
                    <div class="active-filters" id="activeFilters"></div>
                </div>

                @if($totalItems > 0)
                    <div class="d-flex justify-content-between align-items-center px-4 py-2 border-bottom">
                        <div class="text-muted small">
                            Showing <span id="showingFrom">{{ $folders->firstItem() ?? 0 }}</span> to <span id="showingTo">{{ $folders->lastItem() ?? 0 }}</span> of <span id="totalEntries">{{ $folders->total() }}</span> entries
                        </div>
                        <div class="d-flex align-items-center gap-2">
                            <label class="text-muted small mb-0">Show</label>
                            <select class="form-select form-select-sm" style="width: auto;" onchange="window.location.href='?per_page='+this.value+'&search={{ request('search') }}'">
                                <option value="10" {{ request('per_page', 10) == 10 ? 'selected' : '' }}>10</option>
                                <option value="25" {{ request('per_page', 10) == 25 ? 'selected' : '' }}>25</option>
                                <option value="50" {{ request('per_page', 10) == 50 ? 'selected' : '' }}>50</option>
                                <option value="100" {{ request('per_page', 10) == 100 ? 'selected' : '' }}>100</option>
                            </select>
                            <label class="text-muted small mb-0">entries</label>
                        </div>
                    </div>

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
                            <tbody id="documentTableBody">
                                {!! $folderTreeHtml !!}
                                
                                 @foreach($folder_data->document as $doc)
                                    <tr class="document-row" 
                                        data-type="{{ $doc->fileType }}" 
                                        data-modified="{{ $doc->updated_at }}"
                                        data-document-id="{{ $doc->id }}"
                                        onclick="window.open('{{ url('/documents/view-document/'.$doc->id) }}', '_blank')">
                                        <td class="checkbox-cell" onclick="event.stopPropagation()">
                                            <input type="checkbox" class="form-check-input">
                                        </td>
                                        <td>
                                            <div class="name-cell">
                                                <i class="{{ $doc->iconClass }} item-icon"></i>
                                                <span class="item-name">{{ $doc->control_code }} - {{ $doc->title }}</span>
                                            </div>
                                        </td>
                                        <td>{{ strtoupper($doc->fileType) }}</td>
                                        <td>—</td>
                                        <td>{{ date('M d, Y', strtotime($doc->updated_at)) }}</td>
                                        <td class="actions-cell" onclick="event.stopPropagation()">
                                            <button class="action-btn">
                                                <i class="ri-more-2-fill"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="grid-view" id="gridView">
                        <div class="grid-container" id="gridContainer">
                            @foreach($folder_data->childrenFolder as $folder)
                                <div class="grid-item folder-item" 
                                    data-folder-id="{{ $folder->id }}"
                                    data-type="folder"
                                    data-modified="{{ $folder->updated_at }}"
                                    onclick="window.location='{{ url('documents/folder/'.$folder->id) }}'">
                                    <div class="grid-item-header">
                                        <input type="checkbox" class="form-check-input grid-item-checkbox" onclick="event.stopPropagation()">
                                        <button class="grid-item-menu" onclick="event.stopPropagation()">
                                            <i class="ri-more-2-fill"></i>
                                        </button>
                                    </div>
                                    <div class="grid-item-icon">
                                        <i class="ri-folder-2-fill"></i>
                                    </div>
                                    <div class="grid-item-name">{{ $folder->name }}</div>
                                    <div class="grid-item-meta">{{ date('M d, Y', strtotime($folder->updated_at)) }}</div>
                                </div>
                            @endforeach

                            @foreach($folder_data->document as $doc)
                                <div class="grid-item file-item" 
                                    data-type="{{ $doc->fileType }}"
                                    data-modified="{{ $doc->updated_at }}"
                                    data-document-id="{{ $doc->id }}"
                                    onclick="window.open('{{ url('/documents/view-document/'.$doc->id) }}', '_blank')">
                                    <div class="grid-item-header">
                                        <input type="checkbox" class="form-check-input grid-item-checkbox" onclick="event.stopPropagation()">
                                        <button class="grid-item-menu" onclick="event.stopPropagation()">
                                            <i class="ri-more-2-fill"></i>
                                        </button>
                                    </div>
                                    <div class="grid-item-preview {{ $doc->previewClass }}">
                                        <i class="{{ $doc->iconClass }} grid-item-icon"></i>
                                    </div>
                                    <div class="grid-item-info">
                                        <div class="grid-item-name">{{ $doc->control_code }} - {{ $doc->title }}</div>
                                        <div class="grid-item-meta">
                                            <span class="file-type-badge {{ $doc->badgeClass }}">{{ strtoupper($doc->fileType) }}</span>
                                            <span>{{ date('M d', strtotime($doc->updated_at)) }}</span>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="d-flex justify-content-between align-items-center px-4 py-3 border-top">
                        <div class="folder-count">
                            Total: <span id="visibleFolders">{{ $totalFolders }}</span> folders, <span id="visibleDocuments">{{ $totalDocuments }}</span> files
                        </div>
                        <div>
                            {{ $folders->appends(['search' => request('search'), 'per_page' => request('per_page', 10)])->links() }}
                        </div>
                    </div>
                @else
                    <div class="empty-state">
                        <div class="empty-icon">
                            <i class="ri-folders-line"></i>
                        </div>
                        <h3 class="empty-title">No files in here</h3>
                        <p class="empty-text">Upload some content</p>
                        @if(!isset($is_others_folder) || !$is_others_folder)
                        <button type="button" class="new-btn" data-bs-toggle="modal" data-bs-target="#uploadDocument">
                            <i class="ri-upload-line"></i>
                            Upload Files
                        </button>
                        @endif
                    </div>
                @endif
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
    }

    .view-options {
        display: flex;
        gap: 1rem;
        align-items: center;
    }

    .view-btn {
        background: transparent;
        border: none;
        padding: 0.5rem;
        cursor: pointer;
        color: #6b7280;
        border-radius: 4px;
        transition: all 0.2s;
        position: relative;
    }

    .view-btn:hover {
        background: #f3f4f6;
        color: #111827;
    }

    .view-btn.active {
        color: #0078d4;
        background: #e6f4ff;
    }

    .view-toggle {
        display: flex;
        gap: 0.25rem;
        background: #f3f4f6;
        padding: 0.25rem;
        border-radius: 6px;
        margin-left: auto;
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

    .view-toggle-btn:hover {
        color: #1f2937;
    }

    .view-toggle-btn.active {
        background: white;
        color: #0078d4;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    }

    .view-toggle-btn i {
        font-size: 1.125rem;
    }

    .filter-dropdown {
        position: absolute;
        top: 100%;
        left: 0;
        margin-top: 0.5rem;
        background: white;
        border: 1px solid #e5e7eb;
        border-radius: 8px;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        min-width: 200px;
        z-index: 1000;
        display: none;
    }

    .filter-dropdown.show {
        display: block;
    }

    .filter-option {
        padding: 0.75rem 1rem;
        cursor: pointer;
        transition: background 0.15s;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .filter-option:hover {
        background: #f3f4f6;
    }

    .filter-option.active {
        background: #e6f4ff;
        color: #0078d4;
    }

    .filter-option input[type="checkbox"] {
        cursor: pointer;
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

    .list-view {
        display: block;
    }

    .grid-view {
        display: none;
    }

    .document-table {
        width: 100%;
        border-collapse: collapse;
    }

    .document-table thead {
        border-bottom: 1px solid #e5e7eb;
    }

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

    .document-table tbody tr:hover {
        background: #f9fafb;
    }

    .document-table td {
        padding: 0.875rem 1.5rem;
        font-size: 0.875rem;
        color: #1f2937;
    }

    .checkbox-cell {
        width: 40px;
    }

    .checkbox-cell input[type="checkbox"] {
        cursor: pointer;
    }

    .name-cell {
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .item-icon {
        font-size: 1.25rem;
        color: #0078d4;
    }

    .item-name {
        font-weight: 500;
        color: #1f2937;
        text-decoration: none;
    }

    .item-name:hover {
        text-decoration: underline;
        color: #0078d4;
    }

    .actions-cell {
        width: 50px;
        text-align: center;
    }

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

    .document-table tbody tr:hover .action-btn {
        opacity: 1;
    }

    .action-btn:hover {
        background: #f3f4f6;
        color: #111827;
    }

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
    }

    .grid-item:hover {
        border-color: #0078d4;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        transform: translateY(-2px);
    }

    .grid-item.folder-item {
        padding: 1rem;
    }

    .grid-item.folder-item .grid-item-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 0.75rem;
    }

    .grid-item.folder-item .grid-item-icon {
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 0.75rem;
    }

    .grid-item.folder-item .grid-item-icon i {
        font-size: 4rem;
        color: #0078d4;
    }

    .grid-item.folder-item .grid-item-name {
        font-size: 0.875rem;
        font-weight: 500;
        color: #1f2937;
        text-align: center;
        word-break: break-word;
        line-height: 1.4;
    }

    .grid-item.folder-item .grid-item-meta {
        font-size: 0.75rem;
        color: #6b7280;
        text-align: center;
        margin-top: 0.5rem;
    }

    .grid-item.file-item {
        overflow: hidden;
        padding: 0;
        display: flex;
        flex-direction: column;
    }

    .grid-item-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .grid-item-checkbox {
        cursor: pointer;
        width: 18px;
        height: 18px;
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

    .grid-item:hover .grid-item-menu {
        opacity: 1;
    }

    .grid-item-menu:hover {
        background: #f3f4f6;
        color: #111827;
    }

    .file-item .grid-item-header {
        padding: 0.5rem;
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        z-index: 10;
        background: linear-gradient(to bottom, rgba(0,0,0,0.3), transparent);
        opacity: 0;
        transition: opacity 0.2s;
    }

    .file-item:hover .grid-item-header {
        opacity: 1;
    }

    .file-item .grid-item-menu {
        background: rgba(255, 255, 255, 0.9);
    }

    .file-item .grid-item-menu:hover {
        background: white;
    }

    .grid-item-preview {
        width: 100%;
        height: 140px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #f9fafb;
        position: relative;
        overflow: hidden;
    }

    .grid-item-preview img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .grid-item-preview.pdf-preview {
        background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
    }

    .grid-item-preview.docx-preview {
        background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
    }

    .grid-item-preview.xlsx-preview {
        background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);
    }

    .grid-item-preview.default-preview {
        background: linear-gradient(135deg, #a8edea 0%, #fed6e3 100%);
    }

    .grid-item-preview .grid-item-icon {
        font-size: 4rem;
        color: white;
        opacity: 0.9;
    }

    .grid-item-info {
        padding: 0.75rem;
        background: white;
    }

    .grid-item-name {
        font-size: 0.8125rem;
        font-weight: 500;
        color: #1f2937;
        word-break: break-word;
        line-height: 1.3;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
        margin-bottom: 0.25rem;
    }

    .grid-item-meta {
        font-size: 0.6875rem;
        color: #6b7280;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .file-type-badge {
        background: #f3f4f6;
        padding: 0.125rem 0.375rem;
        border-radius: 3px;
        font-size: 0.625rem;
        font-weight: 600;
        text-transform: uppercase;
        color: #6b7280;
    }

    .pdf-badge { background: #fee; color: #c00; }
    .docx-badge { background: #e3f2fd; color: #1976d2; }
    .xlsx-badge { background: #e8f5e9; color: #2e7d32; }

    .empty-state {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        padding: 6rem 2rem;
        text-align: center;
    }

    .empty-icon {
        font-size: 5rem;
        color: #9ca3af;
        margin-bottom: 1.5rem;
    }

    .empty-title {
        font-size: 1.5rem;
        font-weight: 600;
        color: #1f2937;
        margin-bottom: 0.5rem;
    }

    .empty-text {
        color: #6b7280;
        margin-bottom: 1.5rem;
    }

    .folder-count {
        padding: 1rem 2rem;
        font-size: 0.875rem;
        color: #6b7280;
        border-top: 1px solid #e5e7eb;
    }

    .breadcrumb {
        padding: 1rem 2rem;
        background: #f9fafb;
        border-bottom: 1px solid #e5e7eb;
    }

    .breadcrumb a {
        color: #0078d4;
        text-decoration: none;
    }

    .breadcrumb a:hover {
        text-decoration: underline;
    }

    .breadcrumb-separator {
        margin: 0 0.5rem;
        color: #6b7280;
    }

    .active-filters {
        display: flex;
        gap: 0.5rem;
        flex-wrap: wrap;
        margin-top: 0.5rem;
    }

    .filter-tag {
        background: #e6f4ff;
        color: #0078d4;
        padding: 0.25rem 0.75rem;
        border-radius: 12px;
        font-size: 0.75rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .filter-tag button {
        background: none;
        border: none;
        color: #0078d4;
        cursor: pointer;
        padding: 0;
        font-size: 1rem;
        line-height: 1;
    }

    .folder-tree-row {
        transition: background 0.15s;
        cursor: pointer;
    }

    .folder-tree-row.has-children {
        cursor: pointer;
    }

    .folder-tree-row.expanded > td {
        background: #f9fafb;
    }

    .folder-indent {
        display: inline-block;
    }

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

    .folder-toggle i {
        font-size: 0.875rem;
    }

    .folder-toggle.expanded {
        transform: rotate(90deg);
    }

    .child-row {
        display: none;
        cursor: pointer;
    }

    .child-row.show {
        display: table-row;
    }

    .folder-name-cell {
        cursor: pointer;
    }

    .document-row {
        cursor: pointer;
    }

    .folder-tree-row.selected-row {
        background: #e6f4ff !important;
    }

    .folder-tree-row {
        outline: none;
    }

    .grid-item.selected-item {
        border-color: #0078d4;
        background: #e6f4ff;
    }

    .preview-thumbnail {
        width: 100%;
        height: 100%;
        object-fit: cover;
        background: white;
    }
</style>
@endsection

@section('js')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="{{ asset('js/BootstrapMenu.min.js') }}"></script>

<script>
    let clickTimer = null;
    let selectedRow = null;
    let currentView = 'list';
    
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
        const row = $(element).closest('tr');
        const folderId = row.data('folder-id');
        const toggle = row.find('.folder-toggle');
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
        localStorage.setItem('folderViewPreference', 'list');
    }

    function switchToGridView() {
        currentView = 'grid';
        $('#listView').css('display', 'none');
        $('#gridView').css('display', 'block');
        $('#listViewBtn').removeClass('active');
        $('#gridViewBtn').addClass('active');
        localStorage.setItem('folderViewPreference', 'grid');
    }

    $(document).ready(function() {
        $('.select2').select2({
            dropdownParent: $('#addDocumentInFolder'),
            theme: "classic"
        });

        const savedView = localStorage.getItem('folderViewPreference');
        if (savedView === 'grid') {
            switchToGridView();
        }

        $('#listViewBtn').on('click', function() {
            switchToListView();
        });

        $('#gridViewBtn').on('click', function() {
            switchToGridView();
        });

        $('#uploadDocument').on('shown.bs.modal', function () {
            $('#uploadDocument .cat').each(function() {
                if ($(this).data('select2')) {
                    $(this).select2('destroy');
                }
            });
            
            $('#uploadDocument .cat').select2({
                dropdownParent: $('#uploadDocument'),
                theme: "classic",
                placeholder: "Select an option",
                allowClear: true
            });
        });

        $('#uploadDocumentForm').on('submit', function(e) {
            var tags = $('select[name="tags[]"]').val();
            if (!tags || tags.length === 0) {
                e.preventDefault();
                alert('Please select at least one tag');
                return false;
            }
            
            var submitBtn = $(this).find('button[type="submit"]');
            submitBtn.prop('disabled', true).html('<i class="ri-loader-4-line"></i> Uploading...');
            
            return true;
        });

        $('#selectAll').on('change', function() {
            $('.form-check-input').prop('checked', $(this).prop('checked'));
        });

        $(document).on('keydown', function(e) {
            if (e.key === 'Enter' && selectedRow && selectedRow.hasClass('folder-tree-row')) {
                e.preventDefault();
                const folderUrl = selectedRow.find('.folder-name-cell').data('folder-url');
                if (folderUrl) {
                    window.location = folderUrl;
                }
            }
        });

        let filters = {
            types: ['all', 'folder', 'pdf', 'docx', 'xlsx'],
            modifiedDays: 'all'
        };

        $('#typeFilterBtn').on('click', function(e) {
            e.stopPropagation();
            $('#typeFilterDropdown').toggleClass('show');
            $('#modifiedFilterDropdown').removeClass('show');
        });

        $('#modifiedFilterBtn').on('click', function(e) {
            e.stopPropagation();
            $('#modifiedFilterDropdown').toggleClass('show');
            $('#typeFilterDropdown').removeClass('show');
        });

        $(document).on('click', function() {
            $('.filter-dropdown').removeClass('show');
        });

        $('.filter-dropdown').on('click', function(e) {
            e.stopPropagation();
        });

        $('#typeFilterDropdown .filter-option').on('click', function() {
            const checkbox = $(this).find('input[type="checkbox"]');
            const type = $(this).data('type');
            
            if (type === 'all') {
                const isChecked = !checkbox.prop('checked');
                checkbox.prop('checked', isChecked);
                $('#typeFilterDropdown input[type="checkbox"]').prop('checked', isChecked);
                
                if (isChecked) {
                    filters.types = ['all', 'folder', 'pdf', 'docx', 'xlsx'];
                } else {
                    filters.types = [];
                }
            } else {
                checkbox.prop('checked', !checkbox.prop('checked'));
                
                if (checkbox.prop('checked')) {
                    if (!filters.types.includes(type)) {
                        filters.types.push(type);
                    }
                } else {
                    filters.types = filters.types.filter(t => t !== type);
                    $('#type-all').prop('checked', false);
                    filters.types = filters.types.filter(t => t !== 'all');
                }
                
                const allTypesChecked = ['folder', 'pdf', 'docx', 'xlsx'].every(t => 
                    $(`#type-${t}`).prop('checked')
                );
                
                if (allTypesChecked) {
                    $('#type-all').prop('checked', true);
                    if (!filters.types.includes('all')) {
                        filters.types.push('all');
                    }
                }
            }
            
            applyFilters();
        });

        $('#modifiedFilterDropdown .filter-option').on('click', function() {
            $('#modifiedFilterDropdown .filter-option').removeClass('active');
            $(this).addClass('active');
            
            filters.modifiedDays = $(this).data('days');
            $('#modifiedFilterDropdown').removeClass('show');
            
            applyFilters();
        });

        function applyFilters() {
            let visibleCount = 0;
            let visibleFolders = 0;
            let visibleDocuments = 0;

            if (currentView === 'list') {
                $('.document-row').each(function() {
                    const $row = $(this);
                    const rowType = $row.data('type');
                    const rowModified = new Date($row.data('modified'));
                    const now = new Date();
                    const level = $row.data('level') || 0;
                    
                    if (level > 0) {
                        return;
                    }
                    
                    let typeMatch = filters.types.includes('all') || filters.types.includes(rowType);
                    
                    let modifiedMatch = true;
                    if (filters.modifiedDays !== 'all') {
                        const daysDiff = Math.floor((now - rowModified) / (1000 * 60 * 60 * 24));
                        modifiedMatch = daysDiff <= parseInt(filters.modifiedDays);
                    }
                    
                    if (typeMatch && modifiedMatch) {
                        $row.show();
                        visibleCount++;
                        
                        if (rowType === 'folder') {
                            visibleFolders++;
                        } else {
                            visibleDocuments++;
                        }
                    } else {
                        $row.hide();
                        const folderId = $row.data('folder-id');
                        if (folderId) {
                            hideChildren(folderId);
                        }
                    }
                });
            } else {
                $('.grid-item').each(function() {
                    const $item = $(this);
                    const itemType = $item.data('type');
                    const itemModified = new Date($item.data('modified'));
                    const now = new Date();
                    
                    let typeMatch = filters.types.includes('all') || filters.types.includes(itemType);
                    
                    let modifiedMatch = true;
                    if (filters.modifiedDays !== 'all') {
                        const daysDiff = Math.floor((now - itemModified) / (1000 * 60 * 60 * 24));
                        modifiedMatch = daysDiff <= parseInt(filters.modifiedDays);
                    }
                    
                    if (typeMatch && modifiedMatch) {
                        $item.show();
                        visibleCount++;
                        
                        if (itemType === 'folder') {
                            visibleFolders++;
                        } else {
                            visibleDocuments++;
                        }
                    } else {
                        $item.hide();
                    }
                });
            }
            
            $('#visibleFolders').text(visibleFolders);
            $('#visibleDocuments').text(visibleDocuments);
            $('#totalEntries').text(visibleCount);
            $('#showingTo').text(visibleCount);
            if (visibleCount > 0) {
                $('#showingFrom').text('1');
            } else {
                $('#showingFrom').text('0');
            }
            
            updateActiveFilters();
        }

        function updateActiveFilters() {
            const $container = $('#activeFiltersContainer');
            const $filters = $('#activeFilters');
            $filters.empty();
            
            let hasActiveFilters = false;
            
            if (!filters.types.includes('all')) {
                filters.types.forEach(type => {
                    if (type !== 'all') {
                        hasActiveFilters = true;
                        const typeName = type.charAt(0).toUpperCase() + type.slice(1);
                        $filters.append(`
                            <div class="filter-tag">
                                <span>Type: ${typeName}</span>
                                <button onclick="removeTypeFilter('${type}')">&times;</button>
                            </div>
                        `);
                    }
                });
            }
            
            if (filters.modifiedDays !== 'all') {
                hasActiveFilters = true;
                const dayText = filters.modifiedDays == 1 ? 'Last 24 Hours' : `Last ${filters.modifiedDays} Days`;
                $filters.append(`
                    <div class="filter-tag">
                        <span>Modified: ${dayText}</span>
                        <button onclick="removeModifiedFilter()">&times;</button>
                    </div>
                `);
            }
            
            if (hasActiveFilters) {
                $container.show();
            } else {
                $container.hide();
            }
        }

        window.removeTypeFilter = function(type) {
            $(`#type-${type}`).prop('checked', false);
            filters.types = filters.types.filter(t => t !== type);
            $('#type-all').prop('checked', false);
            filters.types = filters.types.filter(t => t !== 'all');
            applyFilters();
        };

        window.removeModifiedFilter = function() {
            filters.modifiedDays = 'all';
            $('#modifiedFilterDropdown .filter-option').removeClass('active');
            applyFilters();
        };

        var menu = new BootstrapMenu('.demoTableRow', {
            fetchElementData: function ($rowElem) {
                return {
                    id: $rowElem.data('folder-id')
                };
            },
            actions: {
                renameFolder: {
                    name: 'Rename folder',
                    iconClass: 'fa-pencil',
                    onClick: function (folder) {
                        $("#renameFolderModal"+folder.id).modal("show")
                    }
                },
                moveFileFolder: {
                    name: 'Move file',
                    iconClass: "ri-drag-move-2-line",
                    onClick: function(folder) {
                        $("#addDocumentInFolder").modal("show")
                        $("#moveDocumentFolder").val(folder.id)
                    }
                }
            }
        });
    });
</script>
@endsection