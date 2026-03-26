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
    <div class="card-header bg-white d-flex justify-content-end align-items-center py-3 gap-1">
        <div class="dropdown">
            <button type="button" class="btn btn-first btn-sm" data-bs-toggle="dropdown">
                <i class="ri-add-line"></i> Create
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
        {{-- <div class="dropdown">
            <button type="button" class="btn btn-info btn-sm" data-bs-toggle="dropdown">
                <i class="ri-more-2-line"></i>
            </button>
            <div class="dropdown-menu dropdown-menu-end">
                <a class="dropdown-item" href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#share">
                    <i class="ri-user-add-line"></i> Share with others
                </a>
            </div>
        </div> --}}
        <a type="button" class="btn btn-second btn-sm" data-bs-toggle="modal" data-bs-target="#share">
            <i class="ri-user-add-line"></i> Share with others
        </a>
    </div>
    <div class="card-body">

        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
            <div class="d-flex align-items-center gap-2 flex-wrap">
                <div style="position:relative;">
                    <i class="ri-search-line" style="position:absolute; left:0.75rem; top:50%; transform:translateY(-50%); color:#6b7280; font-size:1rem;"></i>
                    <input type="text" id="folderSearch" placeholder="Search folders..." autocomplete="off"
                        style="padding:0.375rem 2.5rem 0.375rem 2.25rem;border:1px solid #dee2e6;border-radius:4px;font-size:0.875rem;width:250px;">
                    <button id="clearSearch" style="display:none; position:absolute; right:0.5rem; top:50%;
                            transform:translateY(-50%); background:#f3f4f6; border:none; border-radius:4px; padding:0.15rem 0.4rem; color:#6b7280; cursor:pointer;">
                        <i class="ri-close-line"></i>
                    </button>
                    {{-- &nbsp;&nbsp;<span id="visibleCount" style="font-size:0.875rem;color:#6b7280;">0</span> folders --}}
                </div>
            </div>
            <div class="view-toggle">
                <button class="view-toggle-btn" id="listViewBtn" title="List view">
                    <i class="ri-list-check"></i>
                </button>
                <button class="view-toggle-btn active" id="gridViewBtn" title="Grid view">
                    <i class="ri-grid-fill"></i>
                </button>
            </div>
        </div>

        <div class="bulk-action-toolbar" id="bulkActionToolbar" style="display:none;">
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
@include("documents.share-documents")
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
    let currentView = 'grid';

    var uploadSelect2Inited = false;
    var uploadChosenInited  = false;

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
                    if (!exists) selected.push({
                        id: docId,
                        type: 'document',
                        name: row.find('.item-name').text().trim()
                    });
                }
            }
        });

        $('#gridContainer .grid-item-checkbox:checked:not(:disabled)').each(function () {
            var $cb = $(this);
            var type = $cb.data('type') || 'folder';
            var id = $cb.data('id');
            if (id && type !== 'others') {
                var exists = selected.some(function (i) { return String(i.id) === String(id) && i.type === type; });
                if (!exists) selected.push({
                    id: id,
                    type: type,
                    name: $cb.data('name') || $cb.closest('.grid-item').find('.grid-item-name').text().trim()
                });
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

    function updateControlCodePreview() {
        var firstOpt = $('#documentTypeField option:selected').first();
        var docTypeName = firstOpt.length ? (firstOpt.data('name') || '????') : '????';
        var deptOpt = $('#newDocDeptField option:selected');
        var deptCode  = deptOpt.val() ? (deptOpt.data('code') || '????') : '????';
        var year = new Date().getFullYear();

        $('#controlCodePreviewText').val('MarSU-' + deptCode + '-' + docTypeName + '-' + year + '-????');
    }

    function resetUploadForm() {
        resetUploadFormFields();

        $('#titleField').val('').prop('readonly', false);
        $('#revisionField').val(0)
            .prop('readonly', true)
            .css({ background: '#f8f9fa', cursor: 'not-allowed' });
        $('#revisionAutoIcon').hide();
        $('#revisionInfoBox').hide();

        $('#controlCodeResultField').hide();
        $('#newDocCodeDisplay').hide();
        $('#existingDocCodeDisplay').hide();

        if ($('#newControlCodePicker').data('select2')) {
            $('#newControlCodePicker').val(null).trigger('change');
        }
        if ($('#existingControlCodePicker').data('select2')) {
            $('#existingControlCodePicker').val(null).trigger('change');
        }

        $('#controlCodeTypePicker').val('');

        $('#isRevision').val('0');
        $('#selectedControlCode').val('');
        $('#finalNewControlCode').val('');
        $('#selectedControlCode').attr('name', 'control_code_existing');
        $('#finalNewControlCode').attr('name', 'control_code');

        $('#newDocBadge').hide();
        $('#revisionBadge').hide();
        $('#folderField').val('');
        $('#typeOfRequestField').val('');
    }

    function resetUploadFormFields() {
        $('#titleField').val('').prop('readonly', false);
        $('#revisionField').val(0)
            .prop('readonly', true)
            .css({ background: '#f8f9fa', cursor: 'not-allowed' });
        $('#revisionAutoIcon').hide();
        $('#revisionInfoBox').hide();

        if ($('#newControlCodePicker').data('select2')) {
            $('#newControlCodePicker').val(null).trigger('change');
        }
        if ($('#existingControlCodePicker').data('select2')) {
            $('#existingControlCodePicker').val(null).trigger('change');
        }

        $('#isRevision').val('0');
        $('#selectedControlCode').val('');
        $('#finalNewControlCode').val('');
        $('#selectedControlCode').attr('name', 'control_code_existing');
        $('#finalNewControlCode').attr('name', 'control_code');
        $('#folderField').val('');
        $('#typeOfRequestField').val('');
        $('#documentTypeField').val([]).trigger('chosen:updated');
        $('#newDocDeptField').val('');
        $('select[name="tags[]"]', '#uploadDocument').val([]).trigger('chosen:updated');
    }

    function initUploadModalPlugins() {
        if (!uploadSelect2Inited) {
            $('#newControlCodePicker').select2({
                dropdownParent: $('#uploadDocument'),
                placeholder: '— Select a control code —',
                allowClear: true,
                width: '100%',
            });

            $('#existingControlCodePicker').select2({
                dropdownParent: $('#uploadDocument'),
                placeholder: '— Search or select a control code —',
                allowClear: true,
                width: '100%',
            });

            $('#newControlCodePicker').on('select2:select', function () {
                var val = $(this).val();
                $('#selectedControlCode').val('');
                $('#finalNewControlCode').val(val || '');
            });

            $('#newControlCodePicker').on('select2:clear', function () {
                $('#selectedControlCode').val('');
                $('#finalNewControlCode').val('');
            });

            $('#existingControlCodePicker').on('select2:select', function () {
                var val       = $(this).val();
                var $selected = $(this).find('option[value="' + val + '"]');

                if (!val) return;

                var title = $selected.data('title') || '';
                var folderId = $selected.data('folder') || '';
                var curRevision = parseInt($selected.data('revision') || 0);
                var nextRevision = curRevision + 1;
                var officeId = $selected.data('office') || '';
                var docTypes = $selected.data('doctypes') || '';
                var tags = $selected.data('tags') || '';


                $('#selectedControlCode').val(val);
                $('#finalNewControlCode').val('');
                $('#titleField').val(title).prop('readonly', true);
                $('#newDocDeptField').val(officeId);

                if (docTypes) {
                    var typeIds = String(docTypes).split(',').map(function (t) { return t.trim(); });
                    $('#documentTypeField').val(typeIds).trigger('chosen:updated');
                } else {
                    $('#documentTypeField').val([]).trigger('chosen:updated');
                }

                if (tags) {
                    var tagValues = String(tags).split(',').map(function(t) { return t.trim(); });
                    $('select[name="tags[]"]', '#uploadDocument').val(tagValues).trigger('chosen:updated');
                } else {
                    $('select[name="tags[]"]', '#uploadDocument').val([]).trigger('chosen:updated');
                }

                $('#folderField').val(folderId);
                $('#typeOfRequestField').val('Revision');

                $('#revisionField').val(nextRevision)
                    .prop('readonly', true)
                    .css({ background: '#f8f9fa', cursor: 'not-allowed' });
                $('#revisionAutoIcon').show();

                $('#revisionInfoText').html(
                    'You are uploading <strong>Revision ' + nextRevision + '</strong> of ' +
                    '<strong>' + val + '</strong>. ' +
                    'Previous revision: <strong>' + curRevision + '</strong>.'
                );
                $('#revisionInfoBox').show();
            });

            $('#existingControlCodePicker').on('select2:clear', function () {
                $('#selectedControlCode').val('');
                $('#titleField').val('').prop('readonly', false);
                $('#revisionField').val(0).css({ background: '#f8f9fa', cursor: 'not-allowed' });
                $('#revisionAutoIcon').hide();
                $('#revisionInfoBox').hide();
                $('#newDocDeptField').val('');
                $('#documentTypeField').val([]).trigger('chosen:updated');
                $('select[name="tags[]"]', '#uploadDocument').val([]).trigger('chosen:updated');
                $('#folderField').val('');
                $('#typeOfRequestField').val('Revision');
            });

            uploadSelect2Inited = true;
        }

        if (!uploadChosenInited) {
            $('#documentTypeField').chosen({ width: '100%' });
            $('select[name="tags[]"]', '#uploadDocument').chosen({ width: '100%' });
            uploadChosenInited = true;
        }
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

        $('#bulkShareBtn').on('click', function () {
            var selected = getSelectedItems();
            if (selected.length === 0) return;

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

        $('#bulkDeleteBtn').on('click', function () {
            var selected = getSelectedItems();
            if (selected.length === 0) return;

            var folderIds = selected.filter(function (i) { return i.type === 'folder'; }).map(function (i) { return i.id; });
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
                    var level = $(this).data('level');

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

        $(document).on('change', '#controlCodeTypePicker', function () {
            var val = $(this).val();

            $('#controlCodeResultField').hide();
            $('#newDocCodeDisplay').hide();
            $('#existingDocCodeDisplay').hide();
            $('#newDocBadge').hide();
            $('#revisionBadge').hide();
            resetUploadFormFields();

            if (!val) return;

            $('#controlCodeResultField').show();

            if (val === 'new') {
                $('#newDocBadge').show();
                $('#newDocCodeDisplay').show();
                $('#isRevision').val('0');
                $('#typeOfRequestField').val('New');
            } else if (val === 'existing') {
                $('#revisionBadge').show();
                $('#existingDocCodeDisplay').show();
                $('#isRevision').val('1');
                $('#typeOfRequestField').val('Revision');
            }
        });

        $(document).on('change', '#newDocDeptField', function () {
            updateControlCodePreview();
        });

        $(document).on('change', '#documentTypeField', function () {
            if ($('#newControlCodeSection').is(':visible')) {
                updateControlCodePreview();
            }
        });

        $('#uploadDocumentForm').on('submit', function (e) {
            var type = $('#controlCodeTypePicker').val();

            if (type === 'new') {
                if (!$('#newControlCodePicker').val()) {
                    e.preventDefault();
                    alert('Please select a control code for the new document.');
                    return false;
                }
                $('#selectedControlCode').removeAttr('name');

            } else if (type === 'existing') {
                if (!$('#existingControlCodePicker').val()) {
                    e.preventDefault();
                    alert('Please select an existing document.');
                    return false;
                }
                $('#finalNewControlCode').removeAttr('name');

            } else {
                e.preventDefault();
                alert('Please select New or Existing.');
                return false;
            }
        });

        $('#uploadDocument').on('shown.bs.modal', function () {
            initUploadModalPlugins();
        });

        $('#uploadDocument').on('hidden.bs.modal', function () {
            resetUploadForm();
        });

    });

    (function () {
        var docTree = window._shareDocTree || [];
        var othersDocs = window._shareOthersDocs || [];
        var navStack = [];
        var selectedDocs = {};
        var folderData = {!! json_encode($folderData) !!};

        function findNode(tree, id) {
            for (var i = 0; i < tree.length; i++) {
                if (String(tree[i].id) === String(id)) return tree[i];
                var found = findNode(tree[i].children || [], id);
                if (found) return found;
            }
            return null;
        }

        function currentNode() {
            if (navStack.length === 0) return null;
            return navStack[navStack.length - 1].node;
        }

        function currentChildren() {
            var node = currentNode();
            if (!node) return docTree;
            return node.children || [];
        }

        function currentDocs() {
            var node = currentNode();
            if (!node) return [];
            return node.docs || [];
        }

        function escHtml(str) {
            return String(str)
                .replace(/&/g, '&amp;').replace(/</g, '&lt;')
                .replace(/>/g, '&gt;').replace(/"/g, '&quot;');
        }

        function escAttr(str) { return escHtml(str); }

        function countAllDocs(node) {
            var count = 0;
            (node.children || []).forEach(function (c) {
                count += (c.docs || []).length + countAllDocs(c);
            });
            return count;
        }

        function renderBrowser() {
            var pane  = document.getElementById('docBrowserPane');
            var crumb = document.getElementById('docBrowserCrumb');

            var crumbHtml = '<span class="crumb-item" style="cursor:pointer;font-weight:600;" data-crumb="-1">'
                          + '<i class="ri-home-4-line"></i> Root</span>';
            navStack.forEach(function (step, idx) {
                crumbHtml += '<span class="crumb-sep">/</span>';
                var isLast = idx === navStack.length - 1;
                crumbHtml += '<span class="crumb-item' + (isLast ? ' active' : '') + '"'
                           + ' data-crumb="' + idx + '">' + escHtml(step.name) + '</span>';
            });
            crumb.innerHTML = crumbHtml;

            var html = '';
            var folders = currentChildren();
            var docs = currentDocs();

            if (navStack.length > 0) {
                html += '<div class="doc-browser-row is-folder" data-nav="back" title="Go up">'
                      + '<i class="ri-arrow-left-line" style="color:#9ca3af;"></i>'
                      + '<span style="color:#9ca3af;font-style:italic;">.. up one level</span>'
                      + '</div>';
            }

            if (folders.length === 0 && docs.length === 0 && navStack.length > 0) {
                html += '<div style="padding:40px;text-align:center;color:#9ca3af;font-size:0.82rem;">'
                      + '<i class="ri-folder-open-line" style="font-size:1.4rem;display:block;margin-bottom:6px;"></i>'
                      + 'This folder is empty.</div>';
            }

            folders.forEach(function (f) {
                var docCount = f.docs.length + countAllDocs(f);
                html += '<div class="doc-browser-row is-folder" data-nav-folder="' + f.id + '">'
                      + '<i class="ri-folder-2-fill" style="color:#e67e22;flex-shrink:0;"></i>'
                      + '<span style="flex:1;">' + escHtml(f.name) + '</span>'
                      + '<small class="text-muted">' + docCount + ' doc' + (docCount !== 1 ? 's' : '') + '</small>'
                      + '<i class="ri-arrow-right-s-line text-muted"></i>'
                      + '</div>';
            });

            if (navStack.length === 0 && othersDocs.length > 0) {
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
                          + escHtml(d.label) + '</span>'
                          + '</div>';
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
                      + escHtml(d.label) + '</span>'
                      + '</div>';
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
                    var id = cb.getAttribute('data-doc-id');
                    var label = cb.getAttribute('data-doc-label');
                    if (cb.checked) {
                        selectedDocs[id] = label;
                    } else {
                        delete selectedDocs[id];
                    }
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
                        var node = findNode(docTree, fid);
                        if (node) navStack.push({ id: node.id, name: node.name, node: node });
                    }
                    renderBrowser();
                });
            });

            pane.querySelectorAll('[data-nav="back"]').forEach(function (el) {
                el.addEventListener('click', function () {
                    navStack.pop();
                    renderBrowser();
                });
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
            var noSel = document.getElementById('docNoSelected');
            var hidden = document.getElementById('docHiddenInputs');
            var ids = Object.keys(selectedDocs);

            container.innerHTML = '';
            hidden.innerHTML = '';

            if (ids.length === 0) {
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
            navStack = [];
            selectedDocs = {};
            $('#docBrowserSearch').val('');
            $('#docSearchPane').hide();
            $('#docBrowserWrapper').show();
            $('#docBrowserSearchClear').hide();
            renderBrowser();
        }

        function flattenAllDocs(tree, folderPath) {
            var results = [];
            folderPath = folderPath || 'Root';
            tree.forEach(function (node) {
                (node.docs || []).forEach(function (d) {
                    results.push({ id: d.id, label: d.label, path: folderPath + ' / ' + node.name });
                });
                results = results.concat(flattenAllDocs(node.children || [], folderPath + ' / ' + node.name));
            });
            return results;
        }

        function getAllDocs() {
            var all = flattenAllDocs(docTree, 'Root');
            othersDocs.forEach(function (d) {
                all.push({ id: d.id, label: d.label, path: 'Root / Others' });
            });
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

            if (matches.length === 0) {
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
                      +   '<div style="overflow:hidden;text-overflow:ellipsis;white-space:nowrap;font-size:0.82rem;">' + escHtml(d.label) + '</div>'
                      +   '<div style="font-size:0.7rem;color:#9ca3af;margin-top:1px;">'
                      +     '<i class="ri-folder-line" style="font-size:0.7rem;"></i> ' + escHtml(d.path)
                      +   '</div>'
                      + '</div>'
                      + '</div>';
            });
            pane.innerHTML = html;

            bindBrowserCheckboxes(pane);
        }

        function checkShareReady() {
            var type = document.querySelector('input[name="share_type"]:checked');
            var usersOk  = $('#shareUsersSelect').val() && $('#shareUsersSelect').val().length > 0;
            var targetOk = false;

            if (type && type.value === 'folder') {
                targetOk = !!$('#shareFolderSelect').val();
            } else if (type && type.value === 'document') {
                targetOk = Object.keys(selectedDocs).length > 0;
            }

            $('#shareSubmitBtn').prop('disabled', !(usersOk && targetOk));
        }

        function renderPeopleWithAccess(res) {
            $('#peopleAccessContainer').find('a').remove();
            if (res.length > 0) {
                $('#peopleAccessContainer').show();
                res.forEach(function (shareDocs) {
                    $('#peopleAccessContainer').append(
                        '<a href="javascript:void(0);" class="list-group-item list-group-item-action active">' +
                            '<div class="d-flex mb-2 align-items-center">' +
                                '<div class="flex-shrink-0">' +
                                    '<img src="/images/no_image.png" class="avatar-sm rounded-circle" />' +
                                '</div>' +
                                '<div class="flex-grow-1 ms-3">' +
                                    '<h5 class="list-title fs-15 mb-1 text-dark">' + shareDocs.user.name + '</h5>' +
                                    '<p class="list-text mb-0 fs-12 text-dark">' + shareDocs.user.email + '</p>' +
                                '</div>' +
                            '</div>' +
                        '</a>'
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
                                     + '<span class="text-truncate">' + d.title + '</span>'
                                     + '</div>';
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
            items.forEach(function (item) {
                selectedDocs[item.id] = item.name;
            });
            renderBrowser();
            renderChips();
            checkShareReady();
        };
    }());
</script>
@endsection