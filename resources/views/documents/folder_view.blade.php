@extends('layouts.header')

@section('css')
<link href="{{ asset('/assets/css/docs_style.css') }}" rel="stylesheet">
@endsection

@section('content')
<div class="document-manager mb-5" data-current-folder="{{ $folder_data->id ?? '' }}">
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
                        <div class="d-flex align-items-center">
                            <div class="position-relative">
                                <input type="text"
                                       id="folderSearch"
                                       class="form-control"
                                       placeholder="Search files and folders..."
                                       value="{{ request('search') }}"
                                       autocomplete="off"
                                       style="padding-left: 2.5rem; min-width: 300px;">
                                <i class="ri-search-line position-absolute" style="left: 0.75rem; top: 50%; transform: translateY(-50%); color: #6b7280;"></i>
                                <button id="clearSearch" style="display:none; position:absolute; right:0.5rem; top:50%; transform:translateY(-50%); background:#f3f4f6; border:none; border-radius:4px; padding:0.15rem 0.4rem; color:#6b7280; cursor:pointer;">
                                    <i class="ri-close-line"></i>
                                </button>
                            </div>
                        </div>
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
                                    <a class="dropdown-item" href="{{ route("documents.create") }}">
                                        <i class="ri-folder-add-line me-2"></i>Request document
                                    </a>
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

                <div class="bulk-action-toolbar" id="bulkActionToolbar" style="display: none;">
                    <div class="bulk-info">
                        <i class="ri-checkbox-multiple-line"></i>
                        <span id="selectedCount">0</span> item(s) selected
                    </div>
                    <div class="bulk-actions">
                        @if(canDelete('documents'))
                        <button class="bulk-delete-btn" id="bulkDeleteBtn">
                            <i class="ri-delete-bin-line"></i>
                            Delete Selected
                        </button>
                        @endif
                        <button class="bulk-cancel-btn" id="bulkCancelBtn">
                            <i class="ri-close-line"></i>
                            Cancel
                        </button>
                    </div>
                </div>

                <div class="d-flex justify-content-between align-items-center px-4 py-2 border-bottom" id="statsBar" style="display:none !important;">
                    <div class="text-muted small">
                        Showing <span id="showingFrom">0</span> to <span id="showingTo">0</span>
                        of <span id="totalEntries">0</span> entries
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        <label class="text-muted small mb-0">Show</label>
                        <select class="form-select form-select-sm" style="width: auto;"
                            onchange="window.location.href='?per_page='+this.value+'&search={{ request('search') }}'">
                            <option value="10"  {{ request('per_page', 10) == 10  ? 'selected' : '' }}>10</option>
                            <option value="25"  {{ request('per_page', 10) == 25  ? 'selected' : '' }}>25</option>
                            <option value="50"  {{ request('per_page', 10) == 50  ? 'selected' : '' }}>50</option>
                            <option value="100" {{ request('per_page', 10) == 100 ? 'selected' : '' }}>100</option>
                        </select>
                        <label class="text-muted small mb-0">entries</label>
                    </div>
                </div>

                <div id="contentWrapper" style="display:none;">

                    <div class="list-view" id="listView" style="display:none;">
                        <table class="document-table">
                            <thead>
                                <tr>
                                    <th class="checkbox-cell"><input type="checkbox" id="selectAll"></th>
                                    <th style="width:45%;">Name</th>
                                    <th>File type</th>
                                    <th>Size</th>
                                    <th>Modified</th>
                                    <th class="actions-cell"></th>
                                </tr>
                            </thead>
                            <tbody id="documentTableBody"></tbody>
                        </table>
                    </div>

                    <div class="grid-view" id="gridView" style="display:none;">
                        <div class="grid-container" id="gridContainer"></div>
                    </div>

                    <div class="d-flex justify-content-between align-items-center px-4 py-3 border-top">
                        <div class="folder-count">
                            Total: <span id="visibleFolders">0</span> folders,
                            <span id="visibleDocuments">0</span> files
                        </div>
                    </div>
                </div>

                <div id="loadingState" class="text-center py-5">
                    <div class="d-flex align-items-center justify-content-center gap-2 text-muted">
                        <div class="spinner-border spinner-border-sm" role="status"></div>
                        <span>Loading contents...</span>
                    </div>
                </div>

                <div class="empty-state" id="emptyState" style="display:none;">
                    <div class="empty-icon"><i class="ri-folders-line"></i></div>
                    <h3 class="empty-title">No files in here</h3>
                    <p class="empty-text">You drag and drop file to upload some content</p>
                    @if(!isset($is_others_folder) || !$is_others_folder)
                    <button type="button" class="new-btn" data-bs-toggle="modal" data-bs-target="#uploadDocument">
                        <i class="ri-upload-line"></i>
                        Upload Files
                    </button>
                    @endif
                </div>
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
<script>
    let clickTimer = null;
    let selectedRow = null;
    let currentView = 'list';
    let dragCounter = 0;

    const FOLDER_ID = '{{ $folder_data->id ?? "others" }}';

    function loadFolderContents() {
        $('#loadingState').show();
        $('#contentWrapper').hide();
        $('#emptyState').hide();

        const search  = $('#folderSearch').val() || '';
        const perPage = new URLSearchParams(window.location.search).get('per_page') || 10;

        $.ajax({
            url: '{{ url("documents/folder-view-tree") }}/' + FOLDER_ID,
            type: 'GET',
            data: { search: search, per_page: perPage },
            success: function (response) {
                const total = response.totalItems || 0;
                const folders = response.totalFolders || 0;
                const documents = response.totalDocuments || 0;

                $('#loadingState').hide();

                if (total > 0) {
                    $('#documentTableBody').html(response.listHtml || '');
                    $('#gridContainer').html(response.gridHtml || '');

                    $('#visibleFolders').text(folders);
                    $('#visibleDocuments').text(documents);
                    $('#totalEntries').text(total);
                    $('#showingFrom').text(1);
                    $('#showingTo').text(total);

                    $('#contentWrapper').show();
                    $('#emptyState').hide();

                    if (currentView === 'list') {
                        $('#listView').show();
                        $('#gridView').hide();
                    } else {
                        $('#listView').hide();
                        $('#gridView').show();
                    }
                } else {
                    $('#contentWrapper').hide();
                    $('#emptyState').show();
                }
            },
            error: function () {
                $('#loadingState').hide();
                $('#documentTableBody').html(
                    '<tr><td colspan="6" class="text-center py-4 text-danger">' +
                    '<i class="ri-error-warning-line me-1"></i>' +
                    'Failed to load contents. ' +
                    '<a href="javascript:void(0)" onclick="loadFolderContents()">Retry</a>' +
                    '</td></tr>'
                );
                $('#contentWrapper').show();
                $('#listView').show();
                $('#gridView').hide();
            }
        });
    }

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
        }, function (confirmed) {
            if (!confirmed) return;
            $.ajax({
                url: '{{ url("documents/bulk-delete") }}',
                type: 'POST',
                data: { _token: '{{ csrf_token() }}', folder_ids: '', document_ids: id },
                success: function (response) {
                    if (response.success) {
                        swal('Deleted!', 'Document successfully deleted.', 'success');
                        setTimeout(function () { loadFolderContents(); swal.close(); }, 1500);
                    } else {
                        swal('Cannot Delete!', response.message, 'error');
                    }
                },
                error: function () {
                    swal('Error!', 'Something went wrong. Please try again.', 'error');
                }
            });
        });
    }

    function getSelectedItems() {
        const selected = [];

        $('#documentTableBody tr').each(function () {
            const $row = $(this);
            const $cb = $row.find('input[type="checkbox"]');
            if (!$cb.is(':checked')) return;

            if ($row.hasClass('folder-tree-row')) {
                const folderId = $row.data('folder-id');
                if (folderId) {
                    const exists = selected.some(i => String(i.id) === String(folderId) && i.type === 'folder');
                    if (!exists) selected.push({ id: folderId, type: 'folder' });
                }
            } else if ($row.hasClass('document-row') || $row.hasClass('child-row')) {
                const docId = $row.data('document-id');
                if (docId) {
                    const exists = selected.some(i => String(i.id) === String(docId) && i.type === 'document');
                    if (!exists) selected.push({ id: docId, type: 'document' });
                }
            }
        });

        $('.grid-item.selected-item').each(function () {
            const folderId = $(this).data('folder-id');
            const documentId = $(this).data('document-id');
            const id = folderId || documentId;
            const type = folderId ? 'folder' : 'document';
            if (id) {
                const exists = selected.some(i => String(i.id) === String(id) && i.type === type);
                if (!exists) selected.push({ id, type });
            }
        });

        return selected;
    }

    function updateBulkToolbar() {
        const count = getSelectedItems().length;
        if (count > 0) {
            $('#bulkActionToolbar').slideDown(150);
            $('#selectedCount').text(count);
        } else {
            $('#bulkActionToolbar').slideUp(150);
        }
    }

    function clearAllSelections() {
        $('.item-checkbox').prop('checked', false);
        $('#selectAll').prop('checked', false).prop('indeterminate', false);
        $('.document-table tbody tr').removeClass('row-selected selected-row');
        $('.grid-item').removeClass('selected-item');
        updateBulkToolbar();
    }

    function handleGridCheckbox(checkbox) {
        const $item = $(checkbox).closest('.grid-item');
        $item.toggleClass('selected-item', $(checkbox).is(':checked'));
        updateBulkToolbar();
    }

    function handleFolderCheckbox(checkbox) {
        const $row = $(checkbox).closest('tr');
        $row.toggleClass('row-selected', $(checkbox).is(':checked'));
        updateBulkToolbar();
    }

    function handleFolderClick(element, hasChildren) {
        event.stopPropagation();
        const row = $(element).closest('tr');
        $('.folder-tree-row').removeClass('selected-row');
        row.addClass('selected-row');
        selectedRow = row;

        if (clickTimer === null) {
            clickTimer = setTimeout(function () {
                if (hasChildren) toggleFolder(element);
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

        if (toggle.hasClass('expanded')) {
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
        $('tr[data-parent-id="' + parentId + '"]').each(function () {
            $(this).addClass('show');
            const childId = $(this).data('folder-id');
            if (childId && $(this).find('.folder-toggle').hasClass('expanded')) {
                showChildren(childId);
            }
        });
    }

    function hideChildren(parentId) {
        $('tr[data-parent-id="' + parentId + '"]').each(function () {
            $(this).removeClass('show');
            const childId = $(this).data('folder-id');
            if (childId) {
                $(this).find('.folder-toggle').removeClass('expanded');
                hideChildren(childId);
            }
        });
    }

    function checkAllDescendants(parentId, checked) {
        $('tr[data-parent-id="' + parentId + '"]').each(function () {
            const $row = $(this);
            $row.find('.item-checkbox').prop('checked', checked);
            $row.toggleClass('row-selected', checked);
            const childId = $row.data('folder-id');
            if (childId) checkAllDescendants(childId, checked);
        });
    }

    function switchToListView() {
        currentView = 'list';
        $('#listView').show();
        $('#gridView').hide();
        $('#listViewBtn').addClass('active');
        $('#gridViewBtn').removeClass('active');
        localStorage.setItem('folderViewPreference', 'list');
    }

    function switchToGridView() {
        currentView = 'grid';
        $('#listView').hide();
        $('#gridView').show();
        $('#listViewBtn').removeClass('active');
        $('#gridViewBtn').addClass('active');
        localStorage.setItem('folderViewPreference', 'grid');
    }

    let filters = {
        types: ['all', 'folder', 'pdf', 'docx', 'xlsx'],
        modifiedDays: 'all'
    };

    function applyFilters() {
        let visibleFolders = 0, visibleDocuments = 0;

        if (currentView === 'list') {
            $('.document-row, .folder-tree-row').each(function () {
                const $row    = $(this);
                if (parseInt($row.data('level') || 0) > 0) return;
                const rowType = $row.data('type');
                const rowMod = new Date($row.data('modified'));
                const typeOk = filters.types.includes('all') || filters.types.includes(rowType);
                let modOk = true;
                if (filters.modifiedDays !== 'all') {
                    modOk = Math.floor((new Date() - rowMod) / 86400000) <= parseInt(filters.modifiedDays);
                }
                if (typeOk && modOk) {
                    $row.show();
                    rowType === 'folder' ? visibleFolders++ : visibleDocuments++;
                } else {
                    $row.hide();
                    const fid = $row.data('folder-id');
                    if (fid) hideChildren(fid);
                }
            });
        } else {
            $('.grid-item').each(function () {
                const $item = $(this);
                const iType = $item.data('type');
                const iMod = new Date($item.data('modified'));
                const typeOk  = filters.types.includes('all') || filters.types.includes(iType);
                let modOk = true;
                if (filters.modifiedDays !== 'all') {
                    modOk = Math.floor((new Date() - iMod) / 86400000) <= parseInt(filters.modifiedDays);
                }
                if (typeOk && modOk) {
                    $item.show();
                    iType === 'folder' ? visibleFolders++ : visibleDocuments++;
                } else {
                    $item.hide();
                }
            });
        }

        $('#visibleFolders').text(visibleFolders);
        $('#visibleDocuments').text(visibleDocuments);
        $('#totalEntries').text(visibleFolders + visibleDocuments);
        updateActiveFilters();
    }

    function updateActiveFilters() {
        const $container = $('#activeFiltersContainer');
        const $filters = $('#activeFilters');
        $filters.empty();
        let hasActive = false;

        if (!filters.types.includes('all')) {
            filters.types.filter(t => t !== 'all').forEach(type => {
                hasActive = true;
                $filters.append(
                    '<div class="filter-tag"><span>Type: ' + type.toUpperCase() + '</span>' +
                    '<button onclick="removeTypeFilter(\'' + type + '\')">&times;</button></div>'
                );
            });
        }
        if (filters.modifiedDays !== 'all') {
            hasActive = true;
            const label = filters.modifiedDays == 1 ? 'Last 24 Hours' : 'Last ' + filters.modifiedDays + ' Days';
            $filters.append(
                '<div class="filter-tag"><span>Modified: ' + label + '</span>' +
                '<button onclick="removeModifiedFilter()">&times;</button></div>'
            );
        }
        $container.toggle(hasActive);
    }

    window.removeTypeFilter = function (type) {
        $('#type-' + type).prop('checked', false);
        filters.types = filters.types.filter(t => t !== type && t !== 'all');
        $('#type-all').prop('checked', false);
        applyFilters();
    };

    window.removeModifiedFilter = function () {
        filters.modifiedDays = 'all';
        $('#modifiedFilterDropdown .filter-option').removeClass('active');
        applyFilters();
    };

    function handleFileDrop(files) {
        const isOthers = {{ isset($is_others_folder) && $is_others_folder ? 'true' : 'false' }};
        if (isOthers) { alert('Cannot upload files to this folder'); return; }

        const softCopyInput = $('input[name="attachment[soft_copy]"]')[0];
        if (!softCopyInput) { alert('Upload form not found'); return; }

        const supported = [
            'application/msword',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            'application/vnd.ms-excel',
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'application/vnd.ms-powerpoint',
            'application/vnd.openxmlformats-officedocument.presentationml.presentation'
        ];

        const valid = Array.from(files).filter(f => supported.includes(f.type));
        if (!valid.length) { alert('Please drop supported file types (.doc, .docx, .xls, .xlsx, .ppt, .pptx)'); return; }

        const dt = new DataTransfer();
        dt.items.add(valid[0]);
        softCopyInput.files = dt.files;
        $(softCopyInput).next('.file-selected-indicator').remove()
            .end().after('<small class="file-selected-indicator text-success d-block mt-1">✓ ' + valid[0].name + '</small>');
        $('#uploadDocument').modal('show');
    }

    $(document).ready(function () {
        $('.select2').select2({ dropdownParent: $('#addDocumentInFolder'), theme: "classic" });

        if (localStorage.getItem('folderViewPreference') === 'grid') currentView = 'grid';
        loadFolderContents();

        var searchTimer = null;
        $('#folderSearch').on('input', function () {
            var val = $(this).val();
            $('#clearSearch').toggle(val.length > 0);
            clearTimeout(searchTimer);
            searchTimer = setTimeout(function () { loadFolderContents(); }, 350);
        });

        $('#clearSearch').on('click', function () {
            $('#folderSearch').val('').trigger('input').focus();
        });

        $('#folderSearch').on('keydown', function (e) {
            if (e.key === 'Escape') $(this).val('').trigger('input');
        });

        $('#listViewBtn').on('click', switchToListView);
        $('#gridViewBtn').on('click', switchToGridView);

        $('#uploadDocument').on('shown.bs.modal', function () {
            $('#uploadDocument .cat').each(function () {
                if ($(this).data('select2')) $(this).select2('destroy');
            });
            $('#uploadDocument .cat').select2({
                dropdownParent: $('#uploadDocument'), theme: "classic",
                placeholder: "Select an option", allowClear: true
            });
        });

        $('#uploadDocumentForm').on('submit', function (e) {
            var tags = $('select[name="tags[]"]').val();
            if (!tags || !tags.length) {
                e.preventDefault();
                alert('Please select at least one tag');
                return false;
            }
            $(this).find('button[type="submit"]')
                .prop('disabled', true)
                .html('<i class="ri-loader-4-line"></i> Uploading...');
        });

        $(document).on('keydown', function (e) {
            if (e.key === 'Escape') clearAllSelections();
            if (e.key === 'Enter' && selectedRow && selectedRow.hasClass('folder-tree-row')) {
                e.preventDefault();
                const url = selectedRow.find('.folder-name-cell').data('folder-url');
                if (url) window.location = url;
            }
        });

        $(document).on('change', '#selectAll', function () {
            const checked = $(this).prop('checked');
            $('#documentTableBody tr').each(function () {
                const $row = $(this);
                $row.find('input[type="checkbox"]').prop('checked', checked);
                $row.toggleClass('row-selected', checked);
                if (checked && $row.hasClass('folder-tree-row')) {
                    checkAllDescendants($row.data('folder-id'), true);
                }
            });
            updateBulkToolbar();
        });

        $(document).on('change', '#documentTableBody .form-check-input', function () {
            const $row = $(this).closest('tr');
            const checked = $(this).is(':checked');
            $row.toggleClass('row-selected', checked);

            if ($row.hasClass('folder-tree-row')) {
                checkAllDescendants($row.data('folder-id'), checked);
            }

            const total = $('#documentTableBody tr .item-checkbox').length;
            const checkedN = $('#documentTableBody tr .item-checkbox:checked').length;
            $('#selectAll')
                .prop('indeterminate', checkedN > 0 && checkedN < total)
                .prop('checked', total > 0 && checkedN === total);

            updateBulkToolbar();
        });

        $(document).on('click', '.grid-item.file-item, .grid-item.folder-item', function (e) {
            if ($(e.target).closest('.grid-item-menu, .dropdown-menu, input[type="checkbox"]').length) return;
            if ($('.grid-item.selected-item').length > 0 || $('#bulkActionToolbar').is(':visible')) {
                e.preventDefault();
                e.stopImmediatePropagation();
                const $item = $(this);
                const selected = $item.toggleClass('selected-item').hasClass('selected-item');
                $item.find('.item-checkbox').prop('checked', selected);
                updateBulkToolbar();
                return false;
            }
        });

        $('#bulkCancelBtn').on('click', clearAllSelections);

        $('#bulkDeleteBtn').on('click', function () {
            const selected = getSelectedItems();
            if (!selected.length) return;

            const folderIds = selected.filter(i => i.type === 'folder').map(i => i.id);
            const documentIds = selected.filter(i => i.type === 'document').map(i => i.id);
            let msg = 'You are about to delete ' + selected.length + ' item(s)';
            if (folderIds.length) msg += ' including ' + folderIds.length + ' folder(s) and all their contents';
            msg += '. This cannot be undone.';

            swal({
                title: 'Are you sure?', text: msg, type: 'warning',
                showCancelButton: true, confirmButtonColor: '#dc2626',
                confirmButtonText: 'Delete', cancelButtonText: 'Cancel',
                closeOnConfirm: false, closeOnCancel: true
            }, function (confirmed) {
                if (!confirmed) return;
                $.ajax({
                    url : '{{ url("documents/bulk-delete") }}', type: 'POST',
                    data: {
                        _token : '{{ csrf_token() }}',
                        folder_ids : folderIds.join(','),
                        document_ids: documentIds.join(',')
                    },
                    success: function () {
                        swal('Deleted!', 'Items successfully deleted.', 'success');
                        clearAllSelections();
                        setTimeout(function () { loadFolderContents(); swal.close(); }, 1500);
                    },
                    error: function () {
                        swal('Error!', 'Something went wrong. Please try again.', 'error');
                    }
                });
            });
        });

        $(document).on('click', '.delete-folder-btn', function (e) {
            e.stopPropagation();
            e.preventDefault();
            const id = $(this).data('id');
            const name = $(this).data('name');

            swal({
                title: 'Are you sure?',
                text: 'Delete folder "' + name + '"? This action cannot be undone.',
                type: 'warning', showCancelButton: true, confirmButtonColor: '#dc2626',
                confirmButtonText: 'Delete', cancelButtonText: 'Cancel',
                closeOnConfirm: false, closeOnCancel: true
            }, function (confirmed) {
                if (!confirmed) return;
                $.ajax({
                    url : '{{ url("documents/delete-folder") }}/' + id, type: 'POST',
                    data: { _token: '{{ csrf_token() }}', _method: 'DELETE' },
                    success: function (r) {
                        if (r.success) {
                            swal('Deleted!', 'Folder successfully deleted.', 'success');
                            setTimeout(function () { loadFolderContents(); swal.close(); }, 1500);
                        } else {
                            swal('Cannot Delete!', r.message, 'error');
                        }
                    },
                    error: function () {
                        swal('Error!', 'Something went wrong. Please try again.', 'error');
                    }
                });
            });
        });

        $('#typeFilterBtn').on('click', function (e) {
            e.stopPropagation();
            $('#typeFilterDropdown').toggleClass('show');
            $('#modifiedFilterDropdown').removeClass('show');
        });

        $('#typeFilterDropdown .filter-option').on('click', function () {
            const $cb = $(this).find('input[type="checkbox"]');
            const type = $(this).data('type');

            if (type === 'all') {
                const nowChecked = !$cb.prop('checked');
                $('#typeFilterDropdown input[type="checkbox"]').prop('checked', nowChecked);
                filters.types = nowChecked ? ['all', 'folder', 'pdf', 'docx', 'xlsx'] : [];
            } else {
                $cb.prop('checked', !$cb.prop('checked'));
                if ($cb.prop('checked')) {
                    if (!filters.types.includes(type)) filters.types.push(type);
                } else {
                    filters.types = filters.types.filter(t => t !== type && t !== 'all');
                    $('#type-all').prop('checked', false);
                }
                const allChecked = ['folder', 'pdf', 'docx', 'xlsx'].every(t => $('#type-' + t).prop('checked'));
                if (allChecked) {
                    $('#type-all').prop('checked', true);
                    if (!filters.types.includes('all')) filters.types.push('all');
                }
            }
            applyFilters();
        });

        $('#modifiedFilterBtn').on('click', function (e) {
            e.stopPropagation();
            $('#modifiedFilterDropdown').toggleClass('show');
            $('#typeFilterDropdown').removeClass('show');
        });

        $('#modifiedFilterDropdown .filter-option').on('click', function () {
            $('#modifiedFilterDropdown .filter-option').removeClass('active');
            $(this).addClass('active');
            filters.modifiedDays = $(this).data('days');
            $('#modifiedFilterDropdown').removeClass('show');
            applyFilters();
        });

        $(document).on('click', function () { $('.filter-dropdown').removeClass('show'); });
        $('.filter-dropdown').on('click', function (e) { e.stopPropagation(); });

        $(document).on('drag dragstart dragend dragover dragenter dragleave drop', function (e) {
            e.preventDefault(); e.stopPropagation();
        });
        $(document).on('dragenter', function () {
            if (++dragCounter === 1) $('.document-manager').addClass('drag-over');
        });
        $(document).on('dragleave', function () {
            if (--dragCounter === 0) $('.document-manager').removeClass('drag-over');
        });
        $(document).on('drop', function (e) {
            dragCounter = 0;
            $('.document-manager').removeClass('drag-over');
            const files = e.originalEvent.dataTransfer.files;
            if (files.length) handleFileDrop(files);
        });
    });
</script>
@endsection