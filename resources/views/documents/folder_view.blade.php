@extends('layouts.header')

@section('css')
<link href="{{ asset('login_css/css/plugins/chosen/bootstrap-chosen.css') }}" rel="stylesheet">
<link href="{{ asset('/assets/css/docs_style.css') }}" rel="stylesheet">
@endsection

@section('content')
<div class="document-manager mb-5" data-current-folder="{{ $folder_data->id ?? '' }}">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="breadcrumb d-flex align-items-center gap-2">
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

                    <div class="ms-auto d-flex align-items-center gap-2">
                        @if(!isset($is_others_folder) || !$is_others_folder)
                        <div class="dropdown">
                            <button type="button" class="btn btn-first btn-sm" data-bs-toggle="dropdown">
                                <i class="ri-add-line"></i>
                                New
                            </button>
                            <div class="dropdown-menu dropdown-menu-end">
                                <button class="dropdown-item" data-bs-toggle="modal" data-bs-target="#uploadDocument">
                                    <i class="ri-file-add-line me-2"></i>New file
                                </button>
                                <button class="dropdown-item" data-bs-toggle="modal" data-bs-target="#createFolderModal">
                                    <i class="ri-folder-add-line me-2"></i>New folder
                                </button>
                            </div>
                        </div>
                        @endif

                        <button type="button" class="btn btn-second btn-sm" data-bs-toggle="modal" data-bs-target="#share">
                             <i class="ri-user-add-line"></i> Share with others
                        </button>
                    </div>
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
                        <button class="btn btn-sm btn-warning" id="bulkShareBtn">
                            <i class="ri-share-line"></i>
                            Share Selected
                        </button>
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
                                    <th>Version</th>
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
@include('documents.share-documents')
@endsection

@section('js')
<script src="{{ asset('login_css/js/plugins/chosen/chosen.jquery.js') }}"></script>
<script src="{{ asset('login_css/js/plugins/sweetalert/sweetalert.min.js') }}"></script>
<script src="{{ asset('login_css/js/plugins/dataTables/datatables.min.js') }}"></script>
<script src="{{ asset('js/BootstrapMenu.min.js') }}"></script>

<script>
    let clickTimer  = null;
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
                    if (!exists) selected.push({ id: folderId, type: 'folder', name: $row.find('.item-name').text().trim() });
                }
            } else if ($row.hasClass('document-row') || $row.hasClass('child-row')) {
                const docId = $row.data('document-id');
                if (docId) {
                    const exists = selected.some(i => String(i.id) === String(docId) && i.type === 'document');
                    if (!exists) selected.push({ id: docId, type: 'document', name: $row.find('.item-name').text().trim() });
                }
            }
        });

        $('.grid-item.selected-item').each(function () {
            const folderId = $(this).data('folder-id');
            const documentId = $(this).data('document-id');
            const id = folderId || documentId;
            const type = folderId ? 'folder' : 'document';
            const name = $(this).find('.grid-item-name').text().trim();

            if (id) {
                const exists = selected.some(i => String(i.id) === String(id) && i.type === type);
                if (!exists) selected.push({ id, type, name });
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
        let visibleFolders = 0;
        let visibleDocuments = 0;

        if (currentView === 'list') {
            $('.document-row, .folder-tree-row').each(function () {
                const $row   = $(this);
                if (parseInt($row.data('level') || 0) > 0) return;

                const rowType = $row.data('type');
                const rowMod  = new Date($row.data('modified'));
                const typeOk  = filters.types.includes('all') || filters.types.includes(rowType);
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
                const $item  = $(this);
                const iType  = $item.data('type');
                const iMod   = new Date($item.data('modified'));
                const typeOk = filters.types.includes('all') || filters.types.includes(iType);
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
        const $filters   = $('#activeFilters');
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

        const fileInput = document.getElementById('fileInput');
        if (!fileInput) { alert('Upload form not found'); return; }

        const dt = new DataTransfer();
        Array.from(files).forEach(f => dt.items.add(f));
        fileInput.files = dt.files;
        fileInput.dispatchEvent(new Event('change'));
        $('#uploadDocument').modal('show');
    }

    $(document).ready(function () {
        $('.select2').select2({ dropdownParent: $('#addDocumentInFolder'), theme: 'classic' });

        if (localStorage.getItem('folderViewPreference') === 'grid') currentView = 'grid';
        loadFolderContents();

        var fileInput = document.getElementById('fileInput');
        if (fileInput) {
            fileInput.addEventListener('change', function () {
                var files   = this.files;
                var list    = document.getElementById('filePreviewItems');
                var wrapper = document.getElementById('filePreviewList');

                list.innerHTML = '';

                if (!files || files.length === 0) {
                    wrapper.style.display = 'none';
                    return;
                }

                wrapper.style.display = 'block';

                var iconMap = {
                    'pdf'  : 'ri-file-pdf-line',
                    'doc'  : 'ri-file-word-line',
                    'docx' : 'ri-file-word-line',
                    'xls'  : 'ri-file-excel-line',
                    'xlsx' : 'ri-file-excel-line',
                    'ppt'  : 'ri-file-ppt-line',
                    'pptx' : 'ri-file-ppt-line',
                    'png'  : 'ri-image-line',
                    'jpg'  : 'ri-image-line',
                    'jpeg' : 'ri-image-line',
                    'gif'  : 'ri-image-line',
                    'zip'  : 'ri-folder-zip-line',
                    'rar'  : 'ri-folder-zip-line',
                    'txt'  : 'ri-file-text-line',
                };

                Array.from(files).forEach(function (file) {
                    var ext  = file.name.split('.').pop().toLowerCase();
                    var icon = iconMap[ext] || 'ri-file-line';
                    var size = file.size < 1024 * 1024
                        ? (file.size / 1024).toFixed(1) + ' KB'
                        : (file.size / (1024 * 1024)).toFixed(1) + ' MB';

                    var li = document.createElement('li');
                    li.className = 'list-group-item d-flex align-items-center gap-2 py-2';
                    li.innerHTML = '<i class="' + icon + ' fs-5 text-muted"></i>'
                        + '<span class="flex-grow-1 text-truncate" style="font-size:0.875rem;">' + file.name + '</span>'
                        + '<small class="text-muted text-nowrap">' + size + '</small>';
                    list.appendChild(li);
                });
            });
        }

        $('#uploadDocument').on('shown.bs.modal', function () {
            var currentFolderId = '{{ $folder_data->id ?? "" }}';
            if (currentFolderId && currentFolderId !== 'others') {
                $('#folderField').val(currentFolderId);
            }
        });

        $('#uploadDocument').on('hidden.bs.modal', function () {
            $('#uploadDocumentForm')[0].reset();
            $('#titleField').val('');
            $('#filePreviewItems').html('');
            $('#filePreviewList').hide();
        });

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
            const $row    = $(this).closest('tr');
            const checked = $(this).is(':checked');
            $row.toggleClass('row-selected', checked);

            if ($row.hasClass('folder-tree-row')) {
                checkAllDescendants($row.data('folder-id'), checked);
            }

            const total    = $('#documentTableBody tr .item-checkbox').length;
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
                const $item    = $(this);
                const selected = $item.toggleClass('selected-item').hasClass('selected-item');
                $item.find('.item-checkbox').prop('checked', selected);
                updateBulkToolbar();
                return false;
            }
        });

        $('#bulkCancelBtn').on('click', clearAllSelections);

        $('#bulkDeleteBtn').on('click', function () {
            const selected    = getSelectedItems();
            if (!selected.length) return;

            const folderIds   = selected.filter(i => i.type === 'folder').map(i => i.id);
            const documentIds = selected.filter(i => i.type === 'document').map(i => i.id);
            let msg = 'You are about to delete ' + selected.length + ' item(s)';
            if (folderIds.length) msg += ' including ' + folderIds.length + ' folder(s) and all their contents';
            msg += '. This cannot be undone.';

            swal({
                title: 'Are you sure?',
                text: msg,
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
                    data: {
                        _token: '{{ csrf_token() }}',
                        folder_ids: folderIds.join(','),
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
            }, function (confirmed) {
                if (!confirmed) return;
                $.ajax({
                    url: '{{ url("documents/delete-folder") }}/' + id,
                    type: 'POST',
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
            const $cb  = $(this).find('input[type="checkbox"]');
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
            e.preventDefault();
            e.stopPropagation();
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

        $('#bulkShareBtn').on('click', function () {
            var selected = getSelectedItems();
            if (!selected.length) return;

            var documentItems = selected
                .filter(function (i) { return i.type === 'document'; })
                .map(function (i) { return { id: i.id, name: i.name }; });

            var folderItems = selected.filter(function (i) { return i.type === 'folder'; });

            var shareModal = new bootstrap.Modal(document.getElementById('share'));
            shareModal.show();

            $('#share').one('shown.bs.modal', function () {
                if (documentItems.length > 0) {
                    window.bulkPreSelectDocs(documentItems);
                } else if (folderItems.length === 1) {
                    $('input[name="share_type"][value="folder"]').prop('checked', true).trigger('change');
                    setTimeout(function () {
                        $('#shareFolderSelect').val(folderItems[0].id).trigger('chosen:updated').trigger('change');
                    }, 100);
                }
            });
        });
    });

    window.preSingleDocShare = function (id, name) {
        var shareModal = new bootstrap.Modal(document.getElementById('share'));
        shareModal.show();
        $('#share').one('shown.bs.modal', function () {
            window.bulkPreSelectDocs([{ id: id, name: name }]);
        });
    };

    window.preSingleFolderShare = function (folderId) {
        var shareModal = new bootstrap.Modal(document.getElementById('share'));
        shareModal.show();
        $('#share').one('shown.bs.modal', function () {
            $('input[name="share_type"][value="folder"]').prop('checked', true).trigger('change');
            setTimeout(function () {
                $('#shareFolderSelect').val(folderId).trigger('chosen:updated').trigger('change');
            }, 100);
        });
    };

    (function () {
        var fullTree   = window._shareDocTree   || [];
        var othersDocs = window._shareOthersDocs || [];
        var navStack   = [];
        var selectedDocs = {};
        var folderData = {!! json_encode($folderData) !!};

        function findNodeInTree(tree, id) {
            for (var i = 0; i < tree.length; i++) {
                if (String(tree[i].id) === String(id)) return tree[i];
                var found = findNodeInTree(tree[i].children || [], id);
                if (found) return found;
            }
            return null;
        }

        var scopedNode = (FOLDER_ID && FOLDER_ID !== 'others')
            ? findNodeInTree(fullTree, FOLDER_ID)
            : null;

        var docTree  = scopedNode ? (scopedNode.children || []) : fullTree;
        var rootDocs = scopedNode ? (scopedNode.docs    || []) : [];

        function currentNode()     { return navStack.length ? navStack[navStack.length - 1].node : null; }
        function currentChildren() { var n = currentNode(); return n ? (n.children || []) : docTree; }
        function currentDocs()     { var n = currentNode(); return n ? (n.docs || []) : rootDocs; }

        function escHtml(str) {
            return String(str)
                .replace(/&/g, '&amp;').replace(/</g, '&lt;')
                .replace(/>/g, '&gt;').replace(/"/g, '&quot;');
        }
        function escAttr(str) { return escHtml(str); }

        function countAllDocs(node) {
            var c = 0;
            (node.children || []).forEach(function (ch) {
                c += (ch.docs || []).length + countAllDocs(ch);
            });
            return c;
        }

        function renderBrowser() {
            var pane      = document.getElementById('docBrowserPane');
            var crumb     = document.getElementById('docBrowserCrumb');
            var rootLabel = scopedNode ? escHtml(scopedNode.name) : 'Root';

            var crumbHtml = '<span class="crumb-item" style="cursor:pointer;font-weight:600;" data-crumb="-1">'
                          + '<i class="ri-home-4-line"></i> ' + rootLabel + '</span>';

            navStack.forEach(function (step, idx) {
                crumbHtml += '<span class="crumb-sep">/</span>';
                var isLast = (idx === navStack.length - 1);
                crumbHtml += '<span class="crumb-item' + (isLast ? ' active' : '') + '" data-crumb="' + idx + '">'
                           + escHtml(step.name) + '</span>';
            });
            crumb.innerHTML = crumbHtml;

            var html    = '';
            var folders = currentChildren();
            var docs    = currentDocs();

            if (navStack.length) {
                html += '<div class="doc-browser-row is-folder" data-nav="back">'
                      + '<i class="ri-arrow-left-line" style="color:#9ca3af;"></i>'
                      + '<span style="color:#9ca3af;font-style:italic;">.. up one level</span>'
                      + '</div>';
            }

            if (!folders.length && !docs.length && navStack.length) {
                html += '<div style="padding:40px;text-align:center;color:#9ca3af;font-size:0.82rem;">'
                      + '<i class="ri-folder-open-line" style="font-size:1.4rem;display:block;margin-bottom:6px;"></i>'
                      + 'This folder is empty.</div>';
            }

            folders.forEach(function (f) {
                var cnt = f.docs.length + countAllDocs(f);
                html += '<div class="doc-browser-row is-folder" data-nav-folder="' + f.id + '">'
                      + '<i class="ri-folder-2-fill" style="color:#e67e22;flex-shrink:0;"></i>'
                      + '<span style="flex:1;">' + escHtml(f.name) + '</span>'
                      + '<small class="text-muted">' + cnt + ' doc' + (cnt !== 1 ? 's' : '') + '</small>'
                      + '<i class="ri-arrow-right-s-line text-muted"></i>'
                      + '</div>';
            });

            if (navStack.length === 0 && !scopedNode && othersDocs.length) {
                html += '<div class="doc-browser-row is-folder" data-nav-folder="__others__">'
                      + '<i class="ri-folder-2-fill" style="color:#9ca3af;flex-shrink:0;"></i>'
                      + '<span style="flex:1;color:#9ca3af;font-style:italic;">Others</span>'
                      + '<small class="text-muted">' + othersDocs.length + ' doc' + (othersDocs.length !== 1 ? 's' : '') + '</small>'
                      + '<i class="ri-arrow-right-s-line text-muted"></i>'
                      + '</div>';
            }

            if (navStack.length === 1 && navStack[0].id === '__others__') {
                othersDocs.forEach(function (d) {
                    var checked = selectedDocs[d.id] ? 'checked' : '';
                    html += '<div class="doc-browser-row" data-doc-id="' + d.id + '">'
                          + '<input type="checkbox" class="form-check-input doc-browser-cb" '
                          + 'data-doc-id="' + d.id + '" data-doc-label="' + escAttr(d.label) + '" '
                          + checked + ' style="flex-shrink:0;">'
                          + '<i class="ri-file-text-line" style="color:#6b7280;flex-shrink:0;"></i>'
                          + '<span style="flex:1;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">'
                          + escHtml(d.label) + '</span></div>';
                });
            }

            docs.forEach(function (d) {
                var checked = selectedDocs[d.id] ? 'checked' : '';
                html += '<div class="doc-browser-row" data-doc-id="' + d.id + '">'
                      + '<input type="checkbox" class="form-check-input doc-browser-cb" '
                      + 'data-doc-id="' + d.id + '" data-doc-label="' + escAttr(d.label) + '" '
                      + checked + ' style="flex-shrink:0;">'
                      + '<i class="ri-file-text-line" style="color:#6b7280;flex-shrink:0;"></i>'
                      + '<span style="flex:1;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">'
                      + escHtml(d.label) + '</span></div>';
            });

            pane.innerHTML = html;
            bindBrowserEvents();
            renderChips();
        }

        function bindBrowserCheckboxes(container) {
            container.querySelectorAll('.doc-browser-row[data-doc-id]').forEach(function (row) {
                row.addEventListener('click', function (e) {
                    if (e.target.classList.contains('doc-browser-cb')) return;
                    var cb = row.querySelector('.doc-browser-cb');
                    if (cb) { cb.checked = !cb.checked; cb.dispatchEvent(new Event('change')); }
                });
            });
            container.querySelectorAll('.doc-browser-cb').forEach(function (cb) {
                cb.addEventListener('change', function () {
                    var id    = cb.getAttribute('data-doc-id');
                    var label = cb.getAttribute('data-doc-label');
                    if (cb.checked) { selectedDocs[id] = label; } else { delete selectedDocs[id]; }
                    renderChips();
                    checkShareReady();
                });
            });
        }

        function bindBrowserEvents() {
            var pane = document.getElementById('docBrowserPane');

            pane.querySelectorAll('[data-nav-folder]').forEach(function (el) {
                el.addEventListener('click', function () {
                    var fid = el.getAttribute('data-nav-folder');
                    if (fid === '__others__') {
                        navStack.push({ id: '__others__', name: 'Others', node: null });
                    } else {
                        var node = findNodeInTree(docTree, fid);
                        if (node) navStack.push({ id: node.id, name: node.name, node: node });
                    }
                    renderBrowser();
                });
            });

            pane.querySelectorAll('[data-nav="back"]').forEach(function (el) {
                el.addEventListener('click', function () { navStack.pop(); renderBrowser(); });
            });

            bindBrowserCheckboxes(pane);

            document.getElementById('docBrowserCrumb').querySelectorAll('[data-crumb]').forEach(function (el) {
                el.addEventListener('click', function () {
                    var idx = parseInt(el.getAttribute('data-crumb'), 10);
                    navStack = idx === -1 ? [] : navStack.slice(0, idx + 1);
                    renderBrowser();
                });
            });
        }

        function renderChips() {
            var container = document.getElementById('docSelectedChips');
            var noSel     = document.getElementById('docNoSelected');
            var hidden    = document.getElementById('docHiddenInputs');
            var ids       = Object.keys(selectedDocs);

            container.innerHTML = '';
            hidden.innerHTML    = '';

            if (!ids.length) {
                noSel.style.display = 'block';
            } else {
                noSel.style.display = 'none';
                ids.forEach(function (id) {
                    var chip = document.createElement('span');
                    chip.className = 'doc-chip';
                    chip.innerHTML = '<i class="ri-file-text-line" style="flex-shrink:0;"></i>'
                                   + '<span title="' + escAttr(selectedDocs[id]) + '">' + escHtml(selectedDocs[id]) + '</span>'
                                   + '<button type="button" title="Remove"><i class="ri-close-line"></i></button>';
                    chip.querySelector('button').addEventListener('click', function () {
                        delete selectedDocs[id];
                        var cb = document.querySelector('.doc-browser-cb[data-doc-id="' + id + '"]');
                        if (cb) cb.checked = false;
                        renderChips();
                        checkShareReady();
                    });
                    container.appendChild(chip);

                    var inp = document.createElement('input');
                    inp.type  = 'hidden';
                    inp.name  = 'documents[]';
                    inp.value = id;
                    hidden.appendChild(inp);
                });
            }
        }

        function resetDocBrowser() {
            navStack     = [];
            selectedDocs = {};
            $('#docBrowserSearch').val('');
            $('#docSearchPane').hide();
            $('#docBrowserWrapper').show();
            $('#docBrowserSearchClear').hide();
            renderBrowser();
        }

        function flattenAllDocs(tree, folderPath) {
            var results = [];
            folderPath  = folderPath || 'Root';
            tree.forEach(function (node) {
                (node.docs || []).forEach(function (d) {
                    results.push({ id: d.id, label: d.label, path: folderPath + ' / ' + node.name });
                });
                results = results.concat(flattenAllDocs(node.children || [], folderPath + ' / ' + node.name));
            });
            return results;
        }

        function getAllDocs() {
            var rootLabel = scopedNode ? escHtml(scopedNode.name) : 'Root';
            var all = flattenAllDocs(docTree, rootLabel);

            rootDocs.forEach(function (d) {
                var exists = all.some(function (x) { return String(x.id) === String(d.id); });
                if (!exists) all.push({ id: d.id, label: d.label, path: rootLabel });
            });

            if (!scopedNode) {
                othersDocs.forEach(function (d) {
                    all.push({ id: d.id, label: d.label, path: 'Root / Others' });
                });
            }

            return all;
        }

        function renderSearchPane(term) {
            var pane = document.getElementById('docSearchPane');
            term = term.toLowerCase().trim();

            if (!term) {
                pane.style.display = 'none';
                document.getElementById('docBrowserWrapper').style.display = 'block';
                document.getElementById('docBrowserSearchClear').style.display = 'none';
                return;
            }

            document.getElementById('docBrowserWrapper').style.display = 'none';
            document.getElementById('docBrowserSearchClear').style.display = 'block';
            pane.style.display = 'block';

            var matches = getAllDocs().filter(function (d) {
                return d.label.toLowerCase().includes(term);
            });

            if (!matches.length) {
                pane.innerHTML = '<div style="padding:32px;text-align:center;font-size:0.82rem;color:#9ca3af;">'
                               + '<i class="ri-search-line" style="font-size:1.4rem;display:block;margin-bottom:6px;"></i>'
                               + 'No documents found for <strong>"' + escHtml(term) + '"</strong></div>';
                return;
            }

            var html = '';
            matches.forEach(function (d) {
                var checked = selectedDocs[d.id] ? 'checked' : '';
                html += '<div class="doc-browser-row" data-doc-id="' + d.id + '">'
                      + '<input type="checkbox" class="form-check-input doc-browser-cb" '
                      + 'data-doc-id="' + d.id + '" data-doc-label="' + escAttr(d.label) + '" '
                      + checked + ' style="flex-shrink:0;">'
                      + '<i class="ri-file-text-line" style="color:#6b7280;flex-shrink:0;"></i>'
                      + '<div style="flex:1;overflow:hidden;">'
                      + '<div style="overflow:hidden;text-overflow:ellipsis;white-space:nowrap;font-size:0.82rem;">' + escHtml(d.label) + '</div>'
                      + '<div style="font-size:0.7rem;color:#9ca3af;margin-top:1px;"><i class="ri-folder-line" style="font-size:0.7rem;"></i> ' + escHtml(d.path) + '</div>'
                      + '</div></div>';
            });
            pane.innerHTML = html;
            bindBrowserCheckboxes(pane);
        }

        function checkShareReady() {
            var type    = document.querySelector('input[name="share_type"]:checked');
            var usersOk = $('#shareUsersSelect').val() && $('#shareUsersSelect').val().length > 0;
            var targetOk = false;

            if (type && type.value === 'folder')   targetOk = !!$('#shareFolderSelect').val();
            if (type && type.value === 'document') targetOk = Object.keys(selectedDocs).length > 0;

            $('#shareSubmitBtn').prop('disabled', !(usersOk && targetOk));
        }

        function renderPeopleWithAccess(res) {
            $('#peopleAccessContainer').find('a').remove();
            if (res.length) {
                $('#peopleAccessContainer').show();
                res.forEach(function (s) {
                    $('#peopleAccessContainer').append(
                        '<a href="javascript:void(0);" class="list-group-item list-group-item-action active">'
                      + '<div class="d-flex mb-2 align-items-center">'
                      + '<div class="flex-shrink-0"><img src="/images/no_image.png" class="avatar-sm rounded-circle"/></div>'
                      + '<div class="flex-grow-1 ms-3">'
                      + '<h5 class="list-title fs-15 mb-1 text-dark">' + s.user.name + '</h5>'
                      + '<p class="list-text mb-0 fs-12 text-dark">' + s.user.email + '</p>'
                      + '</div></div></a>'
                    );
                });
            } else {
                $('#peopleAccessContainer').hide();
            }
        }

        $('#share').on('shown.bs.modal', function () {
            if (!$('#shareFolderSelect').data('chosen-init')) {
                $('#shareFolderSelect').chosen({ width: '100%' }).on('change', function () {
                    var folderId = $(this).val();
                    $('#peopleAccessContainer').hide().find('a').remove();
                    $('#folderPreview').hide();
                    if (!folderId) { checkShareReady(); return; }

                    var folder = folderData.find(function (f) { return String(f.id) === String(folderId); });
                    if (folder) {
                        $('#folderPreviewName').text(folder.name);
                        $('#folderPreviewCount').text(folder.docs.length + ' file' + (folder.docs.length !== 1 ? 's' : ''));
                        var listHtml = folder.docs.length
                            ? folder.docs.map(function (d) {
                                return '<div class="d-flex align-items-center gap-2 py-1 border-bottom">'
                                     + '<i class="ri-file-text-line text-muted"></i>'
                                     + '<span class="text-truncate">' + d.title + '</span></div>';
                            }).join('')
                            : '<div class="text-muted fst-italic">This folder has no documents yet.</div>';
                        $('#folderPreviewList').html(listHtml);
                        $('#folderPreview').show();
                    }

                    $.ajax({
                        type: 'POST',
                        url: '{{ url("/documents/share-folder") }}',
                        dataType: 'json',
                        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                        data: { folder_id: folderId },
                        success: function (res) { renderPeopleWithAccess(res); }
                    });
                    checkShareReady();
                });
                $('#shareFolderSelect').data('chosen-init', true);
            }

            if (!$('#shareUsersSelect').data('chosen-init')) {
                $('#shareUsersSelect').chosen({ width: '100%' }).on('change', checkShareReady);
                $('#shareUsersSelect').data('chosen-init', true);
            }

            renderBrowser();

            if (!$('#docBrowserSearch').data('search-init')) {
                $('#docBrowserSearch').on('input', function () {
                    renderSearchPane($(this).val());
                    setTimeout(function () {
                        document.querySelectorAll('#docSearchPane .doc-browser-cb').forEach(function (cb) {
                            cb.checked = !!selectedDocs[cb.getAttribute('data-doc-id')];
                        });
                    }, 0);
                });
                $('#docBrowserSearchClear').on('click', function () {
                    $('#docBrowserSearch').val('').trigger('input').focus();
                });
                $('#docBrowserSearch').data('search-init', true);
            }
        });

        $('#share').on('hidden.bs.modal', function () {
            $('input[name="share_type"]').prop('checked', false);
            $('.share-type-card').removeClass('border-primary bg-light');
            $('#folderSelectionField, #documentSelectionField, #usersField').hide();
            $('#folderPreview').hide();
            $('#shareFolderSelect').val('').trigger('chosen:updated');
            $('#shareUsersSelect').val(null).trigger('chosen:updated');
            $('#shareSubmitBtn').prop('disabled', true);
            $('#peopleAccessContainer').hide().find('a').remove();
            resetDocBrowser();
        });

        $('input[name="share_type"]').on('change', function () {
            var val = $(this).val();
            $('.share-type-card').removeClass('border-primary bg-light');
            $(this).closest('.share-type-card').addClass('border-primary bg-light');
            $('#peopleAccessContainer').hide().find('a').remove();
            $('#folderPreview').hide();

            if (val === 'folder') {
                $('#folderSelectionField').show();
                $('#documentSelectionField').hide();
                resetDocBrowser();
            } else {
                $('#documentSelectionField').show();
                $('#folderSelectionField').hide();
                $('#shareFolderSelect').val('').trigger('chosen:updated');
                renderBrowser();
            }

            $('#usersField').show();
            checkShareReady();
        });

        window.bulkPreSelectDocs = function (items) {
            resetDocBrowser();
            $('input[name="share_type"][value="document"]').prop('checked', true).trigger('change');
            items.forEach(function (item) { selectedDocs[item.id] = item.name; });
            renderBrowser();
            renderChips();
            checkShareReady();
        };
    }());
</script>
@endsection