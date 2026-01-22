@extends('layouts.header')

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
</style>
@endsection

@section('content')
<div class="document-manager mb-5">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="breadcrumb">
                    <a href="{{ url('documents2') }}">
                        <i class="ri-folder-line"></i> Documents
                    </a>
                    
                    @if(!isset($is_others_folder) || !$is_others_folder)
                        @php
                            $breadcrumbs = [];
                            $current = $folder_data;
                            
                            while($current) {
                                array_unshift($breadcrumbs, $current);
                                $current = $current->parent ?? null;
                            }
                        @endphp
                        
                        @foreach($breadcrumbs as $crumb)
                            <span class="breadcrumb-separator">/</span>
                            @if($loop->last)
                                <span>{{ $crumb->name }}</span>
                            @else
                                <a href="{{ url('documents/folder2/'.$crumb->id) }}">{{ $crumb->name }}</a>
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
                </div>

                <div id="activeFiltersContainer" class="px-4 py-2 border-bottom" style="display: none;">
                    <div class="active-filters" id="activeFilters"></div>
                </div>

                @php
                    $totalItems = count($folder_data->childrenFolder) + count($folder_data->document);
                @endphp

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
                            @foreach ($folders as $item)
                                @if($item->type === 'folder')
                                    <tr class="demoTableRow document-row" 
                                        data-folder-id="{{ $item->id }}" 
                                        data-type="folder" 
                                        data-modified="{{ $item->updated_at }}"
                                        onclick="window.location='{{ url('documents/folder2/'.$item->id) }}'">
                                        <td class="checkbox-cell" onclick="event.stopPropagation()">
                                            <input type="checkbox" class="form-check-input">
                                        </td>
                                        <td>
                                            <div class="name-cell">
                                                <i class="ri-folder-2-fill item-icon"></i>
                                                <span class="item-name">{{ $item->name }}</span>
                                            </div>
                                        </td>
                                        <td>Folder</td>
                                        <td>—</td>
                                        <td>{{ date('M d, Y', strtotime($item->updated_at)) }}</td>
                                        <td class="actions-cell" onclick="event.stopPropagation()">
                                            <button class="action-btn">
                                                <i class="ri-more-2-fill"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @else
                                    @php
                                        $document = \App\Document::with('attachments')->find($item->id);
                                        $fileType = 'document';
                                        if ($document && $document->attachments->count() > 0) {
                                            $attachment = $document->attachments->first()->attachment;
                                            $extension = pathinfo($attachment, PATHINFO_EXTENSION);
                                            $fileType = strtolower($extension);
                                        }
                                    @endphp
                                    <tr class="document-row" 
                                        data-type="{{ $fileType }}" 
                                        data-modified="{{ $item->updated_at }}"
                                        onclick="window.open('{{ url('/documents/view-document/'.$item->id) }}', '_blank')">
                                        <td class="checkbox-cell" onclick="event.stopPropagation()">
                                            <input type="checkbox" class="form-check-input">
                                        </td>
                                        <td>
                                            <div class="name-cell">
                                                <i class="ri-file-list-line item-icon"></i>
                                                <span class="item-name">{{ $item->control_code }} - {{ $item->title }}</span>
                                            </div>
                                        </td>
                                        <td>{{ strtoupper($fileType) }}</td>
                                        <td>—</td>
                                        <td>{{ date('M d, Y', strtotime($item->updated_at)) }}</td>
                                        <td class="actions-cell" onclick="event.stopPropagation()">
                                            <button class="action-btn">
                                                <i class="ri-more-2-fill"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @endif
                            @endforeach
                        </tbody>
                    </table>

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

@section('js')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="{{ asset('js/BootstrapMenu.min.js') }}"></script>

<script>
    $(document).ready(function() {
        $('.select2').select2({
            dropdownParent: $('#addDocumentInFolder'),
            theme: "classic"
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

            $('.document-row').each(function() {
                const $row = $(this);
                const rowType = $row.data('type');
                const rowModified = new Date($row.data('modified'));
                const now = new Date();
                
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
                }
            });
            
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