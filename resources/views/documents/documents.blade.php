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
                                        data-level="0">
                                        <td class="checkbox-cell" onclick="event.stopPropagation()">
                                            <input type="checkbox" class="form-check-input">
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
                                        <tr class="child-row" data-parent-id="others" data-level="1"
                                            onclick="window.location='{{ url('documents/view-document/'.$doc->id) }}'">
                                            <td class="checkbox-cell" onclick="event.stopPropagation()">
                                                <input type="checkbox" class="form-check-input">
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

                            @if($hasOthers)
                                <div class="grid-item" 
                                     data-folder-name="others"
                                     data-type="folder"
                                     onclick="window.location='{{ url('documents/folder/others') }}'">
                                    <div class="grid-item-header">
                                        <input type="checkbox" class="form-check-input grid-item-checkbox" onclick="event.stopPropagation()">
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

    .grid-item-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 0.75rem;
    }

    .grid-item-checkbox {
        cursor: pointer;
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

    .grid-item-icon {
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 0.75rem;
    }

    .grid-item-icon i {
        font-size: 4rem;
        color: #0078d4;
    }

    .grid-item-icon.document-icon i {
        color: #6b7280;
    }

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

    .grid-item.selected-item {
        border-color: #0078d4;
        background: #e6f4ff;
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
    function deleteFolder() {
        event.preventDefault()
        document.getElementById('deleteFolderForm').submit()
    }

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
        const folderId = row.data('folder-id') || 'others';
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

    $(document).ready(function() {
        $('.cat').chosen({width: "100%"});

        $('.select2').select2({
            dropdownParent: $('#addDocumentInFolder'),
            theme: "classic"
        })

        const savedView = localStorage.getItem('documentViewPreference');
        if (savedView === 'grid') {
            switchToGridView();
        }

        $('#listViewBtn').on('click', function() {
            switchToListView();
        });

        $('#gridViewBtn').on('click', function() {
            switchToGridView();
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

        const searchInput = $('#folderSearch');
        const clearBtn = $('#clearSearch');
        const noResults = $('#noResults');
        const folderCount = $('#folderCount');
        const tableBody = $('#foldersTableBody');
        const gridContainer = $('#gridContainer');

        searchInput.on('input', function() {
            const searchTerm = $(this).val().toLowerCase().trim();
            
            if (searchTerm.length > 0) {
                clearBtn.show();
            } else {
                clearBtn.hide();
            }
            
            let visibleCount = 0;
            
            if (currentView === 'list') {
                $('.folder-tree-row').each(function() {
                    const folderName = $(this).data('folder-name');
                    const level = $(this).data('level');
                    
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
                $('.grid-item[data-type="folder"]').each(function() {
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