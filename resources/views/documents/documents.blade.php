@extends('layouts.header')

@section('css')
<link href="{{ asset('login_css/css/plugins/chosen/bootstrap-chosen.css') }}" rel="stylesheet">
<link href="{{ asset('login_css/css/plugins/sweetalert/sweetalert.css') }}" rel="stylesheet">
<link href="{{ asset('/assets/css/docs_style.css') }}" rel="stylesheet">
@endsection

@section('content')

<div class="row mb-3">
    <div class="col-12 d-flex justify-content-between align-items-center">
        <div>
            <h4 class="mb-0">Documents</h4>
            <p class="text-muted mb-0">Manage folders and files</p>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
        <h5 class="mb-0">Documents</h5>
        @if(canCreate('documents'))
        <div class="dropdown">
            <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="dropdown">
                <i class="ri-add-line"></i> New
            </button>
            <div class="dropdown-menu dropdown-menu-end">
                <a class="dropdown-item" href="{{ route('documents.create') }}">
                    <i class="ri-file-add-line me-2"></i>Request document
                </a>
                <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#uploadDocument">
                    <i class="ri-upload-2-line me-2"></i>Upload file
                </a>
                <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#createFolderModal">
                    <i class="ri-folder-add-line me-2"></i>New folder
                </a>
            </div>
        </div>
        @endif
    </div>
    <div class="card-body">

        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:8px;">
            <div style="font-size:0.875rem;color:#6b7280;">
                <span id="visibleCount">0</span> folders
            </div>
            <div style="display:flex; align-items:center; gap:10px;">
                <div style="position:relative;">
                    <i class="ri-search-line" style="position:absolute; left:0.75rem; top:50%; transform:translateY(-50%); color:#6b7280; font-size:1rem;"></i>
                    <input type="text" id="folderSearch" placeholder="Search folders..." autocomplete="off"
                        style="padding:0.375rem 2.5rem 0.375rem 2.25rem;border:1px solid #dee2e6;border-radius:4px;font-size:0.875rem;width:250px;">
                    <button id="clearSearch" style="display:none; position:absolute; right:0.5rem; top:50%; 
                            transform:translateY(-50%); background:#f3f4f6; border:none; border-radius:4px; padding:0.15rem 0.4rem; color:#6b7280; cursor:pointer;">
                        <i class="ri-close-line"></i>
                    </button>
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
        </div>

        <div class="bulk-action-toolbar" id="bulkActionToolbar" style="display:none;">
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

        <form id="bulkDeleteForm" method="POST" action="{{ url('documents/bulk-delete') }}" style="display:none;">
            @csrf
            @method('DELETE')
            <input type="hidden" name="selected_ids" id="bulkDeleteIds">
            <input type="hidden" name="selected_types" id="bulkDeleteTypes">
        </form>

        <div class="list-view" id="listView" style="display:none;">
            <table class="document-table">
                <thead>
                    <tr>
                        <th class="checkbox-cell">
                            <input type="checkbox" id="selectAll">
                        </th>
                        <th style="width:45%;">Name</th>
                        <th>File type</th>
                        <th>Size</th>
                        <th>Modified</th>
                        <th class="actions-cell"></th>
                    </tr>
                </thead>
                <tbody id="foldersTableBody">
                    <tr id="tableLoadingRow">
                        <td colspan="6" class="text-center py-4">
                            <div class="d-flex align-items-center justify-content-center gap-2 text-muted">
                                <div class="spinner-border spinner-border-sm" role="status"></div>
                                <span>Loading folders...</span>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="grid-view" id="gridView" style="display:none;">
            <div class="grid-container" id="gridContainer">
                {{-- popolated by ajax --}}
            </div>
        </div>

        <div class="empty-state" id="emptyState" style="display:none;">
            <div class="empty-icon">
                <i class="ri-folder-user-line"></i>
            </div>
            <h3 class="empty-title">No folders found</h3>
            <p class="empty-text">Folders you create will show up here.</p>
            @if(canCreate('documents'))
            <button type="button" class="new-btn" data-bs-toggle="modal" data-bs-target="#createFolderModal">
                <i class="ri-add-line"></i>
                New Folder
            </button>
            @endif
        </div>

        <div class="no-results" id="noResults" style="display:none;">
            <i class="ri-search-line"></i>
            <p class="mb-0">No folders found matching your search.</p>
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

@section('js')
<script src="{{ asset('login_css/js/plugins/dataTables/datatables.min.js')}}"></script>
<script src="{{ asset('login_css/js/plugins/chosen/chosen.jquery.js') }}"></script>
<script src="{{ asset('login_css/js/plugins/sweetalert/sweetalert.min.js') }}"></script>
<script src="{{ asset('js/BootstrapMenu.min.js') }}"></script>

<script>
    let selectedItems = {};
    let clickTimer = null;
    let selectedRow = null;
    let currentView = 'list';

    function loadFolderTree() {
        $('#listView').show();
        $('#gridView').hide();
        $('#emptyState').hide();
        $('#noResults').hide();

        $('#foldersTableBody').html(
            '<tr id="tableLoadingRow">' +
            '<td colspan="6" class="text-center py-4">' +
            '<div class="d-flex align-items-center justify-content-center gap-2 text-muted">' +
            '<div class="spinner-border spinner-border-sm" role="status"></div>' +
            '<span>Loading folders...</span>' +
            '</div></td></tr>'
        );
        $('#gridContainer').html('');

        $.ajax({
            url: '{{ url("documents/folder-tree") }}',
            type: 'GET',
            success: function (response) {
                var total = response.totalFolders || 0;
                $('#visibleCount').text(total);

                if (total > 0) {
                    $('#foldersTableBody').html(response.listHtml || '');
                    $('#gridContainer').html(response.gridHtml || '');
                    if (currentView === 'list') {
                        $('#listView').show();
                        $('#gridView').hide();
                    } else {
                        $('#listView').hide();
                        $('#gridView').show();
                    }
                    $('#emptyState').hide();
                } else {
                    $('#listView').hide();
                    $('#gridView').hide();
                    $('#emptyState').show();
                }
            },
            error: function () {
                $('#foldersTableBody').html(
                    '<tr><td colspan="6" class="text-center py-4 text-danger">' +
                    '<i class="ri-error-warning-line me-1"></i>' +
                    'Failed to load folders. <a href="javascript:void(0)" onclick="loadFolderTree()">Retry</a>' +
                    '</td></tr>'
                );
                $('#listView').show();
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
                data: {
                    _token: '{{ csrf_token() }}',
                    folder_ids: '',
                    document_ids: id
                },
                success: function (response) {
                    if (response.success) {
                        swal('Deleted!', 'Document successfully deleted.', 'success');
                        setTimeout(function () { loadFolderTree(); swal.close(); }, 1500);
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

    function getSelectedCount() { return Object.keys(selectedItems).length; }

    function selectItem(type, id, name) {
        selectedItems[type + '_' + id] = { type, id, name };
    }

    function deselectItem(type, id) {
        delete selectedItems[type + '_' + id];
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
        var $cb = $(checkbox);
        var type = $cb.data('type');
        var id = $cb.data('id');
        var name = $cb.data('name') || $cb.closest('tr').find('.item-name').text().trim();
        var $row = $cb.closest('tr');

        if ($cb.is(':checked')) {
            selectItem(type, id, name);
            $row.addClass('row-selected');
        } else {
            deselectItem(type, id);
            $row.removeClass('row-selected');
        }
    }

    function handleFolderCheckbox(checkbox) {
        handleListCheckbox(checkbox);
        var total = $('.folder-tree-row:visible .item-checkbox:not(:disabled), .child-row:visible .item-checkbox:not(:disabled)').length;
        var checked = $('.folder-tree-row:visible .item-checkbox:not(:disabled):checked, .child-row:visible .item-checkbox:not(:disabled):checked').length;
        $('#selectAll').prop('indeterminate', checked > 0 && checked < total);
        $('#selectAll').prop('checked', checked === total && total > 0);
        updateBulkToolbar();
    }

    function handleGridCheckbox(checkbox) {
        var $cb = $(checkbox);
        var type = $cb.data('type');
        var id = $cb.data('id');
        var name = $cb.data('name') || $cb.closest('.grid-item').find('.grid-item-name').text().trim();
        var $item = $cb.closest('.grid-item');

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

    function getSelectedItems() {
        var selected = [];

        $('#foldersTableBody tr').each(function () {
            var checkbox = $(this).find('.form-check-input');
            if (!checkbox.is(':checked')) return;
            var row = $(this);

            if (row.hasClass('folder-tree-row')) {
                var folderId = row.data('folder-id');
                if (folderId && folderId !== 'others') {
                    var exists = selected.some(function (i) { return String(i.id) === String(folderId) && i.type === 'folder'; });
                    if (!exists) selected.push({ id: folderId, type: 'folder' });
                }
            } else if (row.hasClass('child-row')) {
                var docId = row.data('document-id');
                if (docId) {
                    var exists = selected.some(function (i) { return String(i.id) === String(docId) && i.type === 'document'; });
                    if (!exists) selected.push({ id: docId, type: 'document' });
                }
            }
        });

        $('#gridContainer .grid-item-checkbox:checked:not(:disabled)').each(function () {
            var $cb = $(this);
            var type = $cb.data('type') || 'folder';
            var id = $cb.data('id');
            if (id && type !== 'others') {
                var exists = selected.some(function (i) { return String(i.id) === String(id) && i.type === type; });
                if (!exists) selected.push({ id: id, type: type });
            }
        });

        return selected;
    }

    function updateBulkToolbar() {
        var selected = getSelectedItems();
        var count = selected.length;
        if (count > 0) {
            $('#bulkActionToolbar').slideDown(150);
            $('#selectedCount').text(count);
        } else {
            $('#bulkActionToolbar').slideUp(150);
        }
    }

    function handleFolderClick(element, hasChildren) {
        event.stopPropagation();

        var row = $(element).closest('tr');
        $('.folder-tree-row').removeClass('selected-row');
        row.addClass('selected-row');
        selectedRow = row;

        if (clickTimer === null) {
            clickTimer = setTimeout(function () {
                if (hasChildren) { toggleFolder(element); }
                clickTimer = null;
            }, 250);
        } else {
            clearTimeout(clickTimer);
            clickTimer = null;
            window.location = $(element).data('folder-url');
        }
    }

    function toggleFolder(element) {
        var row = $(element).closest('tr');
        var folderId = row.data('folder-id') || 'others';
        var toggle = row.find('.folder-toggle');
        var isExpanded = toggle.hasClass('expanded');

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
        $('tr[data-parent-id="' + parentId + '"]').each(function () {
            $(this).addClass('show');
            var childFolderId = $(this).data('folder-id');
            if (childFolderId && $(this).find('.folder-toggle').hasClass('expanded')) {
                showChildren(childFolderId);
            }
        });
    }

    function hideChildren(parentId) {
        $('tr[data-parent-id="' + parentId + '"]').each(function () {
            $(this).removeClass('show');
            var childFolderId = $(this).data('folder-id');
            if (childFolderId) {
                $(this).find('.folder-toggle').removeClass('expanded');
                hideChildren(childFolderId);
            }
        });
    }

    function switchToListView() {
        currentView = 'list';
        var hasItems = $('#foldersTableBody tr:not(#tableLoadingRow)').length > 0;
        $('#listView').css('display', hasItems ? 'block' : 'none');
        $('#gridView').css('display', 'none');
        $('#listViewBtn').addClass('active');
        $('#gridViewBtn').removeClass('active');
        localStorage.setItem('documentViewPreference', 'list');
    }

    function switchToGridView() {
        currentView = 'grid';
        var hasItems = $('#gridContainer .grid-item').length > 0;
        $('#listView').css('display', 'none');
        $('#gridView').css('display', hasItems ? 'block' : 'none');
        $('#listViewBtn').removeClass('active');
        $('#gridViewBtn').addClass('active');
        localStorage.setItem('documentViewPreference', 'grid');
    }

    function setChosenValue(selector, value) {
        $(selector).val(value).trigger('chosen:updated');
    }

    function resetUploadForm() {
        $('#titleField').val('').prop('readonly', false);
        $('#revisionField').val(0).prop('readonly', true).css({ background: '#f8f9fa', cursor: 'not-allowed' });
        $('#revisionAutoIcon').hide();
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

    $(document).ready(function () {

        var savedView = localStorage.getItem('documentViewPreference');
        if (savedView === 'grid') { currentView = 'grid'; }

        loadFolderTree();

        $('.cat').chosen({ width: "100%" });
        $('.select2').select2({
            dropdownParent: $('#addDocumentInFolder'),
            theme: "classic"
        });

        $('#listViewBtn').on('click', switchToListView);
        $('#gridViewBtn').on('click', switchToGridView);

        $('#selectAll').on('change', function () {
            var checked = $(this).prop('checked');

            if (!checked) {
                clearAllSelections();
                return;
            }

            $('#foldersTableBody tr:visible .item-checkbox:not(:disabled)').each(function () {
                var $cb = $(this);
                var type = $cb.data('type');
                var id = $cb.data('id');
                var name = $cb.data('name') || $cb.closest('tr').find('.item-name').text().trim();

                if (type === 'others') return;

                $cb.prop('checked', true);
                $cb.closest('tr').addClass('row-selected');
                selectedItems[type + '_' + id] = { type, id, name };
            });

            updateBulkToolbar();
        });

        $(document).on('change', '#foldersTableBody .form-check-input:not(:disabled)', function () {
            var $cb = $(this);
            var type = $cb.data('type');
            var id = $cb.data('id');
            var name = $cb.data('name') || $cb.closest('tr').find('.item-name').text().trim();
            var $row = $cb.closest('tr');

            if ($cb.is(':checked')) {
                selectedItems[type + '_' + id] = { type, id, name };
                $row.addClass('row-selected');
            } else {
                delete selectedItems[type + '_' + id];
                $row.removeClass('row-selected');
            }

            var total = $('#foldersTableBody tr:visible .item-checkbox:not(:disabled)').length;
            var checked = $('#foldersTableBody tr:visible .item-checkbox:not(:disabled):checked').length;
            $('#selectAll').prop('indeterminate', checked > 0 && checked < total);
            $('#selectAll').prop('checked', checked === total && total > 0);

            updateBulkToolbar();
        });

        $(document).on('keydown', function (e) {
            if (e.key === 'Escape') { clearAllSelections(); }
            if (e.key === 'Enter' && selectedRow && selectedRow.hasClass('folder-tree-row')) {
                e.preventDefault();
                var folderUrl = selectedRow.find('.folder-name-cell').data('folder-url');
                if (folderUrl) { window.location = folderUrl; }
            }
        });

        $(document).on('click', '.grid-item', function (e) {
            if (
                $(e.target).hasClass('grid-item-menu') ||
                $(e.target).closest('.grid-item-menu').length ||
                $(e.target).hasClass('grid-item-checkbox') ||
                $(e.target).closest('.dropdown-menu').length ||
                $(e.target).hasClass('delete-folder-btn') ||
                $(e.target).closest('.delete-folder-btn').length
            ) { return; }

            if ($('#bulkActionToolbar').is(':visible') || $('.grid-item.selected-item').length > 0) {
                e.preventDefault();
                e.stopImmediatePropagation();
                $(this).toggleClass('selected-item');
                var isSelected = $(this).hasClass('selected-item');
                $(this).find('.grid-item-checkbox').prop('checked', isSelected);
                updateBulkToolbar();
                return false;
            }
        });

        $('#bulkCancelBtn').on('click', function () { clearAllSelections(); });

        $('#bulkDeleteBtn').on('click', function () {
            var selected = getSelectedItems();
            if (selected.length === 0) return;

            var folderIds = selected.filter(function (i) { return i.type === 'folder';   }).map(function (i) { return i.id; });
            var documentIds = selected.filter(function (i) { return i.type === 'document'; }).map(function (i) { return i.id; });

            var message = 'You are about to delete ' + selected.length + ' item(s)';
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
            }, function (confirmed) {
                if (confirmed) {
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
                            setTimeout(function () { loadFolderTree(); swal.close(); }, 1500);
                        },
                        error: function () {
                            swal('Error!', 'Something went wrong. Please try again.', 'error');
                        }
                    });
                }
            });
        });

        $(document).on('click', '.delete-folder-btn', function (e) {
            e.stopPropagation();
            e.preventDefault();

            var id = $(this).data('id');
            var name = $(this).data('name');

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
                    success: function (response) {
                        if (response.success) {
                            swal('Deleted!', 'Folder successfully deleted.', 'success');
                            setTimeout(function () { loadFolderTree(); swal.close(); }, 1500);
                        } else {
                            swal('Cannot Delete!', response.message, 'error');
                        }
                    },
                    error: function () {
                        swal('Error!', 'Something went wrong. Please try again.', 'error');
                    }
                });
            });
        });

        var searchInput = $('#folderSearch');
        var clearBtn = $('#clearSearch');
        var noResults = $('#noResults');
        var tableBody = $('#foldersTableBody');
        var gridContainer = $('#gridContainer');

        searchInput.on('input', function () {
            var searchTerm = $(this).val().toLowerCase().trim();
            clearBtn.toggle(searchTerm.length > 0);
            var visibleCount = 0;

            if (currentView === 'list') {
                $('.folder-tree-row').each(function () {
                    var folderName = $(this).data('folder-name');
                    var level      = $(this).data('level');

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
                    noResults.show();
                } else {
                    tableBody.show();
                    noResults.hide();
                }
            } else {
                $('.grid-item[data-type="folder"], .grid-item[data-type="others"]').each(function () {
                    var folderName = $(this).data('folder-name');
                    if (folderName && folderName.includes(searchTerm)) {
                        $(this).show();
                        visibleCount++;
                    } else {
                        $(this).hide();
                    }
                });

                if (visibleCount === 0 && searchTerm.length > 0) {
                    gridContainer.hide();
                    noResults.show();
                } else {
                    gridContainer.show();
                    noResults.hide();
                }
            }

            $('#visibleCount').text(visibleCount);
        });

        clearBtn.on('click', function () {
            searchInput.val('').trigger('input');
            searchInput.focus();
        });

        searchInput.on('keydown', function (e) {
            if (e.key === 'Escape') { $(this).val('').trigger('input'); }
        });

        $('#controlCodeSelect').select2({
            dropdownParent: $('#uploadDocument'),
            placeholder: '— Search or select a control code —',
            allowClear: true,
            width: '100%',
        });

        $('#controlCodeSelect').on('change', function () {
            var val = $(this).val();
            var $selected = $(this).find('option:selected');

            if (!val) { resetUploadForm(); return; }

            if (val === '__OTHER__') {
                resetUploadForm();
                $('#manualControlCodeWrapper').show();
                $('#manualControlCode').attr('required', true);
                $('#newDocBadge').show();
                $('#revisionField').val(0).prop('readonly', true).css({ background: '#f8f9fa', cursor: 'not-allowed' });
                return;
            }

            resetUploadForm();

            var title = $selected.data('title') || '';
            var docType = $selected.data('type') || '';
            var folderId = $selected.data('folder') || '';
            var other = $selected.data('other') || '';
            var curRevision = parseInt($selected.data('revision') || 0);
            var nextRevision = curRevision + 1;

            $('#selectedControlCode').val(val);
            $('#titleField').val(title).prop('readonly', true);
            $('#otherField').val(other);
            $('#isRevision').val('1');
            $('#revisionBadge').show();

            setChosenValue('#documentTypeField', docType);
            setChosenValue('#folderField', folderId);
            setChosenValue('#typeOfRequestField', 'Revision');

            $('#revisionField').val(nextRevision).prop('readonly', true).css({ background: '#f8f9fa', cursor: 'not-allowed' });
            $('#revisionAutoIcon').show();

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