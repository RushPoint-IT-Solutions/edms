@extends('layouts.header')

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
    }

    .view-btn:hover {
        background: #f3f4f6;
        color: #111827;
    }

    .view-btn.active {
        color: #0078d4;
        background: #e6f4ff;
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
        cursor: pointer;
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

    .sidebar-folder-item {
        display: flex;
        align-items: center;
        padding: 0.5rem 1rem;
        color: #374151;
        text-decoration: none;
        border-radius: 6px;
        margin-bottom: 0.25rem;
        transition: background 0.15s;
    }

    .sidebar-folder-item:hover {
        background: #f3f4f6;
        color: #1f2937;
    }

    .sidebar-folder-item.active {
        background: #e6f4ff;
        color: #0078d4;
    }

    .sidebar-folder-item i {
        margin-right: 0.75rem;
        font-size: 1.125rem;
    }

    .others-folder {
        color: #9ca3af !important;
        font-style: italic;
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
</style>
@endsection

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
                </div>

                @php
                    $allFolders = $document_folders->where('parent_id', null);
                    $hasOthers = count($documents->where('folder_id', null)) > 0;
                    $totalFolders = count($allFolders) + ($hasOthers ? 1 : 0);
                @endphp

                @if($totalFolders > 0)
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
                            @foreach ($allFolders as $folder)
                                <tr class="demoTableRow folder-row" 
                                    data-folder-id="{{ $folder->id }}" 
                                    data-folder-name="{{ strtolower($folder->name) }}"
                                    onclick="window.location='{{ url('documents/folder2/'.$folder->id) }}'">
                                    <td class="checkbox-cell" onclick="event.stopPropagation()">
                                        <input type="checkbox" class="form-check-input">
                                    </td>
                                    <td>
                                        <div class="name-cell">
                                            <i class="ri-folder-2-fill item-icon"></i>
                                            <span class="item-name">{{ $folder->name }}</span>
                                        </div>
                                    </td>
                                    <td>Folder</td>
                                    <td>—</td>
                                    <td>{{ date('M d, Y', strtotime($folder->updated_at)) }}</td>
                                    <td class="actions-cell" onclick="event.stopPropagation()">
                                        <button class="action-btn">
                                            <i class="ri-more-2-fill"></i>
                                        </button>
                                    </td>
                                </tr>
                            @endforeach

                            @if($hasOthers)
                                <tr class="folder-row" 
                                    data-folder-name="others"
                                    onclick="window.location='{{ url('documents/folder2/others') }}'">
                                    <td class="checkbox-cell" onclick="event.stopPropagation()">
                                        <input type="checkbox" class="form-check-input">
                                    </td>
                                    <td>
                                        <div class="name-cell">
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
                            @endif
                        </tbody>
                    </table>

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

    $(document).ready(function() {
        $('.cat').chosen({width: "100%"});

        $('.select2').select2({
            dropdownParent: $('#addDocumentInFolder'),
            theme: "classic"
        })

        $('#selectAll').on('change', function() {
            $('.form-check-input').prop('checked', $(this).prop('checked'));
        });

        const searchInput = $('#folderSearch');
        const clearBtn = $('#clearSearch');
        const noResults = $('#noResults');
        const folderCount = $('#folderCount');
        const tableBody = $('#foldersTableBody');

        searchInput.on('input', function() {
            const searchTerm = $(this).val().toLowerCase().trim();
            
            if (searchTerm.length > 0) {
                clearBtn.show();
            } else {
                clearBtn.hide();
            }
            
            let visibleCount = 0;
            
            $('.folder-row').each(function() {
                const folderName = $(this).data('folder-name');
                
                if (folderName.includes(searchTerm)) {
                    $(this).show();
                    visibleCount++;
                } else {
                    $(this).hide();
                }
            });
            
            $('#visibleCount').text(visibleCount);
            
            if (visibleCount === 0 && searchTerm.length > 0) {
                tableBody.hide();
                folderCount.hide();
                noResults.show();
            } else {
                tableBody.show();
                folderCount.show();
                noResults.hide();
            }
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