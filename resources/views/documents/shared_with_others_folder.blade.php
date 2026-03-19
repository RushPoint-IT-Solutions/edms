@extends('layouts.header')

@section('css')
<style>
    .toggleBtn {
        background: none;
        border: 1px solid #dee2e6;
        padding: 5px 10px;
        border-radius: 4px;
        color: #6b7280;
        cursor: pointer;
        transition: all 0.2s;
    }

    .toggleBtn.active {
        background: #f3f4f6;
        color: #1a1a2e;
        border-color: #adb5bd;
    }

    .pathCrumb {
        display: flex;
        align-items: center;
        flex-wrap: wrap;
        gap: 4px;
        font-size: 0.85rem;
        margin-bottom: 1rem;
    }

    .pathCrumb a {
        color: #0284c7;
        text-decoration: none;
        font-weight: 500;
    }

    .pathCrumb a:hover {
        text-decoration: underline;
    }

    .pathCrumb .sep {
        color: #9ca3af;
    }

    .pathCrumb .currentPage {
        color: #1a1a2e;
        font-weight: 600;
    }

    .itemsTable {
        width: 100%;
        border-collapse: collapse;
    }

    .itemsTable th {
        font-size: 0.75rem;
        font-weight: 600;
        color: #6b7280;
        padding: 8px 12px;
        border-bottom: 1px solid #e5e7eb;
        text-align: left;
        background: #f8f9fa;
    }

    .itemsTable td {
        padding: 10px 12px;
        border-bottom: 1px solid #f3f4f6;
        vertical-align: middle;
        font-size: 0.875rem;
        transition: background 0.15s;
    }

    .itemsTable tr.rowClick {
        cursor: pointer;
    }

    .itemsTable tr.rowClick:hover td {
        background: #f9fafb;
    }

    .itemsTable tr.rowClick.rowActive td {
        background: #eff6ff !important;
    }

    .itemsTable tr.rowClick.rowActive td:first-child {
        border-left: 3px solid #3b82f6;
    }

    .itemsTable tr:last-child td {
        border-bottom: none;
    }

    .itemsGrid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(160px, 1fr));
        gap: 12px;
        padding: 12px 0;
    }

    .itemCard {
        border: 1px solid #e5e7eb;
        border-radius: 10px;
        overflow: hidden;
        cursor: pointer;
        background: #fff;
        transition: box-shadow 0.2s, border-color 0.2s, background 0.15s;
    }

    .itemCard:hover {
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        border-color: #cbd5e1;
    }

    .itemCard.cardSelected {
        border-color: #3b82f6 !important;
        background: #eff6ff !important;
        box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.25) !important;
    }

    .itemCard .previewArea {
        height: 100px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2.5rem;
    }

    .folder-preview {
        background: #fff8f0;
        color: #e67e22;
    }

    .pdf-preview {
        background: #fff0f0;
        color: #dc2626;
    }

    .docx-preview {
        background: #eff6ff;
        color: #2563eb;
    }

    .xlsx-preview {
        background: #f0fdf4;
        color: #16a34a;
    }

    .default-preview {
        background: #f8f9fa;
        color: #6b7280;
    }

    .itemCard .cardTop {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 6px 8px 0;
    }

    .itemCard .cardBottom {
        padding: 6px 10px 10px;
        border-top: 1px solid #f0f0f0;
    }

    .itemCard .cardName {
        font-size: 0.78rem;
        font-weight: 600;
        color: #1a1a2e;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .itemCard .cardMeta {
        font-size: 0.68rem;
        color: #9ca3af;
        margin-top: 2px;
    }

    .miniAvatar {
        width: 26px;
        height: 26px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.7rem;
        font-weight: 700;
        color: #fff;
        flex-shrink: 0;
    }

    .emptyFolder {
        text-align: center;
        padding: 60px 20px;
        color: #9ca3af;
    }

    .emptyFolder i {
        font-size: 3rem;
        margin-bottom: 1rem;
        display: block;
    }

    .dblHint {
        font-size: 0.68rem;
        color: #9ca3af;
        font-style: italic;
    }

    .floatHint {
        display: none;
        position: fixed;
        bottom: 24px;
        left: 50%;
        transform: translateX(-50%);
        background: #1a1a2e;
        color: #fff;
        padding: 7px 18px;
        border-radius: 20px;
        font-size: 0.78rem;
        z-index: 9999;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.25);
        pointer-events: none;
        white-space: nowrap;
    }
</style>
@endsection

@section('content')

<div class="floatHint" id="floatHint">
    <i class="ri-mouse-line me-1"></i> Double-click to open
</div>

<div class="row mb-2">
    <div class="col-12 d-flex justify-content-between align-items-center">
        <div>
            <h4 class="mb-0">{{ $folder->name }}</h4>
            <p class="text-muted mb-0" style="font-size:0.82rem;">
                Shared with others &rsaquo; {{ $folder->name }}
                <span class="dblHint">&nbsp;— Click to select &bull; Double-click to open</span>
            </p>
        </div>
        <div class="d-flex align-items-center gap-2">
            @if($sharedUsers->count() > 0)
            <div class="d-flex align-items-center gap-2 me-2"
                style="background:#f8f9fa;border:1px solid #e5e7eb;border-radius:8px;padding:5px 10px;">
                <i class="ri-group-line" style="color:#6b7280;font-size:0.9rem;"></i>
                <span style="font-size:0.78rem;color:#6b7280;">Shared with</span>
                @foreach($sharedUsers as $share)
                <div class="miniAvatar"
                    style="background:{{ $share->avatarColor }};width:22px;height:22px;font-size:0.62rem;margin-left:-4px;"
                    title="{{ $share->user->name ?? '—' }}">
                    {{ strtoupper(substr($share->user->name ?? '?', 0, 1)) }}
                </div>
                @endforeach
                <span style="font-size:0.78rem;font-weight:600;color:#1a1a2e;">
                    {{ $sharedUsers->count() }} user{{ $sharedUsers->count() !== 1 ? 's' : '' }}
                </span>
            </div>
            @endif
            <button class="toggleBtn active" id="listViewBtn" title="List view">
                <i class="ri-list-check"></i>
            </button>
            <button class="toggleBtn" id="gridViewBtn" title="Grid view">
                <i class="ri-grid-fill"></i>
            </button>
        </div>
    </div>
</div>

<div class="pathCrumb">
    <a href="{{ route('shared-with-others') }}">
        <i class="ri-user-shared-line me-1"></i>Shared with Others
    </a>
    @foreach($breadcrumbs as $crumb)
        <span class="sep"><i class="ri-arrow-right-s-line"></i></span>
        @if(!$loop->last)
            <a href="{{ route('shared-with-others.folder', $crumb->id) }}">{{ $crumb->name }}</a>
        @else
            <span class="currentPage">{{ $crumb->name }}</span>
        @endif
    @endforeach
</div>

@if($sharedUsers->count() > 0)
<div class="card mb-3">
    <div class="card-body py-2 px-3">
        <div class="d-flex align-items-center flex-wrap gap-2">
            <span style="font-size:0.78rem;font-weight:600;color:#6b7280;">
                <i class="ri-group-line me-1"></i>People with access:
            </span>
            @foreach($sharedUsers as $share)
            <span class="badge d-inline-flex align-items-center gap-1"
                style="background:#f0f4ff;color:#1a1a2e;border:1px solid #d0d9f0;font-size:0.72rem;padding:4px 8px;">
                <div class="miniAvatar"
                    style="background:{{ $share->avatarColor }};width:18px;height:18px;font-size:0.55rem;">
                    {{ strtoupper(substr($share->user->name ?? '?', 0, 1)) }}
                </div>
                {{ $share->user->name ?? '—' }}
                <button type="button"
                    class="btn-close btn-close-sm revokeFolder ms-1"
                    style="font-size:0.5rem;opacity:0.6;"
                    title="Remove access"
                    data-folder-id="{{ $folder->id }}"
                    data-user-id="{{ $share->user_id }}"
                    data-user-name="{{ $share->user->name ?? '' }}"
                    data-folder-name="{{ $folder->name }}">
                </button>
            </span>
            @endforeach
        </div>
    </div>
</div>
@endif

<div class="card">
    <div class="card-body p-0">

        @if($childFolders->isEmpty() && $childDocuments->isEmpty())
            <div class="emptyFolder">
                <i class="ri-folder-open-line"></i>
                <p class="fw-semibold mb-1">This folder is empty</p>
                <p style="font-size:0.82rem;">No documents in this folder yet.</p>
            </div>
        @else

        <div id="listView">
            <table class="itemsTable">
                <thead>
                    <tr>
                        <th style="width:40%;">Name</th>
                        <th>Type</th>
                        <th>Shared With</th>
                        <th>Modified</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($childFolders as $subFolder)
                    <tr class="rowClick listItem"
                        data-type="folder"
                        data-href-folder="{{ route('shared-with-others.folder', $subFolder->id) }}"
                        data-href-doc="">
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <i class="ri-folder-2-fill" style="font-size:1.2rem;color:#e67e22;"></i>
                                <span>{{ $subFolder->name }}</span>
                            </div>
                        </td>
                        <td><span class="badge bg-warning text-dark" style="font-size:0.68rem;">Folder</span></td>
                        <td class="noClick">
                            <div class="d-flex flex-wrap gap-1">
                                @foreach($sharedUsers as $share)
                                <span class="badge"
                                    style="background:#f0f4ff;color:#1a1a2e;border:1px solid #d0d9f0;font-size:0.68rem;">
                                    {{ $share->user->name ?? '—' }}
                                </span>
                                @endforeach
                                @if($sharedUsers->isEmpty())
                                    <span style="font-size:0.75rem;color:#9ca3af;">—</span>
                                @endif
                            </div>
                        </td>
                        <td style="color:#6b7280;">{{ $subFolder->updated_at->format('M d, Y') }}</td>
                        <td class="noClick"></td>
                    </tr>
                    @endforeach

                    @foreach($childDocuments as $doc)
                    <tr class="rowClick listItem"
                        data-type="document"
                        data-href-folder=""
                        data-href-doc="{{ url('documents/view-document/' . $doc->id) }}">
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <i class="{{ $doc->iconClass }}" style="font-size:1.2rem;color:#6b7280;"></i>
                                <div>
                                    <div style="font-weight:500;">{{ $doc->title }}</div>
                                    <div style="font-size:0.72rem;color:#9ca3af;">{{ $doc->control_code }}</div>
                                </div>
                            </div>
                        </td>
                        <td><span class="badge bg-secondary" style="font-size:0.68rem;">{{ strtoupper($doc->fileType) }}</span></td>
                        <td class="noClick">
                            <div class="d-flex flex-wrap gap-1">
                                @foreach($doc->share_document as $share)
                                <span class="badge d-inline-flex align-items-center gap-1"
                                    style="background:#f0f4ff;color:#1a1a2e;border:1px solid #d0d9f0;font-size:0.7rem;padding:3px 7px;">
                                    {{ $share->user->name ?? '—' }}
                                    <button type="button"
                                        class="btn-close btn-close-sm revokeDoc ms-1"
                                        style="font-size:0.5rem;opacity:0.6;"
                                        title="Remove access"
                                        data-document-id="{{ $doc->id }}"
                                        data-user-id="{{ $share->user_id }}"
                                        data-user-name="{{ $share->user->name ?? '' }}"
                                        data-doc-title="{{ $doc->title }}">
                                    </button>
                                </span>
                                @endforeach
                            </div>
                        </td>
                        <td style="color:#6b7280;">{{ $doc->updated_at->format('M d, Y') }}</td>
                        <td class="noClick">
                            <a href="{{ url('documents/view-document/' . $doc->id) }}"
                               target="_blank"
                               class="btn btn-sm btn-outline-primary py-0 px-2">
                                <i class="ri-eye-line"></i>
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div id="gridView" style="display:none;padding:12px 16px;">
            <div class="itemsGrid">

                @foreach($childFolders as $subFolder)
                <div class="itemCard gridItem"
                    data-type="folder"
                    data-href-folder="{{ route('shared-with-others.folder', $subFolder->id) }}"
                    data-href-doc="">
                    <div class="cardTop">
                        <i class="ri-folder-2-fill" style="color:#e67e22;font-size:1rem;"></i>
                    </div>
                    <div class="previewArea folder-preview">
                        <i class="ri-folder-2-fill"></i>
                    </div>
                    <div class="cardBottom">
                        <div class="cardName">{{ $subFolder->name }}</div>
                        <div class="cardMeta">{{ $subFolder->updated_at->format('M d, Y') }}</div>
                    </div>
                </div>
                @endforeach

                @foreach($childDocuments as $doc)
                <div class="itemCard gridItem"
                    data-type="document"
                    data-href-folder=""
                    data-href-doc="{{ url('documents/view-document/' . $doc->id) }}">
                    <div class="cardTop">
                        <i class="{{ $doc->iconClass }}" style="color:#6b7280;font-size:1rem;"></i>
                    </div>
                    <div class="previewArea {{ $doc->previewClass }}">
                        <i class="{{ $doc->iconClass }}"></i>
                    </div>
                    <div class="cardBottom">
                        <div class="cardName">{{ $doc->control_code }} - {{ $doc->title }}</div>
                        <div class="cardMeta">
                            <span class="badge bg-secondary" style="font-size:0.62rem;">{{ strtoupper($doc->fileType) }}</span>
                            {{ $doc->updated_at->format('M d') }}
                        </div>
                    </div>
                </div>
                @endforeach

            </div>
        </div>

        @endif
    </div>
</div>
@endsection

@section('js')
<script src="{{ asset('login_css/js/plugins/sweetalert/sweetalert.min.js') }}"></script>
<script>
$(document).ready(function () {

    $('#listViewBtn').on('click', function () {
        $('#listView').show(); $('#gridView').hide();
        $(this).addClass('active'); $('#gridViewBtn').removeClass('active');
        localStorage.setItem('sharedOthersFolderView', 'list');
    });
    $('#gridViewBtn').on('click', function () {
        $('#gridView').show(); $('#listView').hide();
        $(this).addClass('active'); $('#listViewBtn').removeClass('active');
        localStorage.setItem('sharedOthersFolderView', 'grid');
    });
    if (localStorage.getItem('sharedOthersFolderView') === 'grid') {
        $('#gridViewBtn').trigger('click');
    }

    var hintTimer = null;
    function showHint() {
        clearTimeout(hintTimer);
        $('#floatHint').fadeIn(150);
        hintTimer = setTimeout(function () { $('#floatHint').fadeOut(300); }, 2500);
    }

    $(document).on('click', 'tr.listItem', function (e) {
        if ($(e.target).closest('.noClick, .revokeDoc, .revokeFolder, a, button').length) return;

        $('tr.listItem').removeClass('rowActive');
        $(this).addClass('rowActive');
        showHint();
    });

    $(document).on('dblclick', 'tr.listItem', function (e) {
        if ($(e.target).closest('.noClick, .revokeDoc, .revokeFolder, a, button').length) return;

        var type = $(this).data('type');
        var folder = $(this).data('href-folder');
        var doc = $(this).data('href-doc');

        if (type === 'folder' && folder) {
            window.location.href = folder;
        } else if (doc) {
            window.open(doc, '_blank');
        }
    });

    $(document).on('click', '.gridItem', function (e) {
        if ($(e.target).closest('.revokeDoc, .revokeFolder, a, button').length) return;

        $('.gridItem').removeClass('cardSelected');
        $(this).addClass('cardSelected');
        showHint();
    });

    $(document).on('dblclick', '.gridItem', function (e) {
        if ($(e.target).closest('.revokeDoc, .revokeFolder, a, button').length) return;

        var type = $(this).data('type');
        var folder = $(this).data('href-folder');
        var doc = $(this).data('href-doc');

        if (type === 'folder' && folder) {
            window.location.href = folder;
        } else if (doc) {
            window.open(doc, '_blank');
        }
    });

    $(document).on('click', function (e) {
        if (!$(e.target).closest('tr.listItem, .gridItem').length) {
            $('tr.listItem').removeClass('rowActive');
            $('.gridItem').removeClass('cardSelected');
        }
    });

    $(document).on('click', '.revokeFolder', function (e) {
        e.stopPropagation();
        e.preventDefault();

        var folderId = $(this).data('folder-id');
        var userId = $(this).data('user-id');
        var userName = $(this).data('user-name');
        var folderName = $(this).data('folder-name');

        setTimeout(function () {
            swal({
                title: 'Remove Folder Access?',
                text: 'Remove "' + userName + '" from all documents in "' + folderName + '"?',
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc2626',
                confirmButtonText: 'Remove',
                cancelButtonText: 'Cancel',
                closeOnConfirm: false,
            }, function (confirmed) {
                if (!confirmed) return;
                $.ajax({
                    type: 'POST',
                    url: '{{ url("/documents/revoke-folder-access") }}',
                    headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                    data: { folder_id: folderId, user_id: userId },
                    success: function (res) {
                        if (res.success) {
                            swal('Removed!', res.message, 'success');
                            setTimeout(function () { location.reload(); }, 1200);
                        } else {
                            swal('Error!', res.message, 'error');
                        }
                    },
                    error: function () { swal('Error!', 'Something went wrong.', 'error'); }
                });
            });
        }, 0);
    });

    $(document).on('click', '.revokeDoc', function (e) {
        e.stopPropagation();
        e.preventDefault();

        var documentId = $(this).data('document-id');
        var userId = $(this).data('user-id');
        var userName = $(this).data('user-name');
        var docTitle = $(this).data('doc-title');

        setTimeout(function () {
            swal({
                title: 'Remove Access?',
                text: 'Remove "' + userName + '" from "' + docTitle + '"?',
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc2626',
                confirmButtonText: 'Remove',
                cancelButtonText: 'Cancel',
                closeOnConfirm: false,
            }, function (confirmed) {
                if (!confirmed) return;
                $.ajax({
                    type: 'POST',
                    url: '{{ url("/documents/revoke-access") }}',
                    headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                    data: { document_id: documentId, user_id: userId },
                    success: function (res) {
                        if (res.success) {
                            swal('Removed!', res.message, 'success');
                            setTimeout(function () { location.reload(); }, 1200);
                        } else {
                            swal('Error!', res.message, 'error');
                        }
                    },
                    error: function () { swal('Error!', 'Something went wrong.', 'error'); }
                });
            });
        }, 0);
    });

});
</script>
@endsection