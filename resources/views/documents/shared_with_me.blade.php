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

    .listTable {
        width: 100%;
        border-collapse: collapse;
    }

    .listTable th {
        font-size: 0.75rem;
        font-weight: 600;
        color: #6b7280;
        padding: 8px 12px;
        border-bottom: 1px solid #e5e7eb;
        text-align: left;
        white-space: nowrap;
    }

    .listTable td {
        padding: 10px 12px;
        border-bottom: 1px solid #f3f4f6;
        vertical-align: middle;
    }

    .listTable tr.rowClick {
        cursor: pointer;
    }

    .listTable tr.rowClick:hover td {
        background: #f9fafb;
    }

    .listTable tr.rowClick.rowActive td {
        background: #eff6ff !important;
    }

    .listTable tr.rowClick.rowActive td:first-child {
        border-left: 3px solid #3b82f6;
    }

    .listTable tr:last-child td {
        border-bottom: none;
    }

    .dateLabel {
        font-size: 0.78rem;
        font-weight: 600;
        color: #6b7280;
        padding: 14px 12px 6px;
        border-bottom: 1px solid #f0f0f0;
        background: #fff;
    }

    .gridWrapper {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(160px, 1fr));
        gap: 12px;
        padding: 12px 0;
    }

    .gridCard {
        border: 1px solid #e5e7eb;
        border-radius: 10px;
        overflow: hidden;
        cursor: pointer;
        transition: box-shadow 0.2s, border-color 0.2s;
        background: #fff;
        position: relative;
    }

    .gridCard:hover {
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        border-color: #cbd5e1;
    }

    .gridCard.cardSelected {
        border-color: #3b82f6 !important;
        background: #eff6ff !important;
        box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.25) !important;
    }

    .gridCard .previewBox {
        height: 100px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2.5rem;
        background: #f8f9fa;
    }

    .gridCard .previewBox.folder-preview {
        background: #fff8f0;
        color: #e67e22;
    }

    .gridCard .previewBox.pdf-preview {
        background: #fff0f0;
        color: #dc2626;
    }

    .gridCard .previewBox.docx-preview {
        background: #eff6ff;
        color: #2563eb;
    }

    .gridCard .previewBox.xlsx-preview {
        background: #f0fdf4;
        color: #16a34a;
    }

    .gridCard .previewBox.default-preview {
        background: #f8f9fa;
        color: #6b7280;
    }

    .gridCard .cardBottom {
        padding: 8px 10px;
        border-top: 1px solid #f0f0f0;
    }

    .gridCard .cardTitle {
        font-size: 0.78rem;
        font-weight: 600;
        color: #1a1a2e;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .gridCard .cardMeta {
        font-size: 0.68rem;
        color: #9ca3af;
        margin-top: 2px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .gridCard .cardTop {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 6px 8px 4px;
    }

    .gridCard .cardTop .fileIconSmall {
        font-size: 1rem;
    }

    .dateLabelGrid {
        font-size: 0.78rem;
        font-weight: 600;
        color: #6b7280;
        padding: 14px 0 6px;
    }

    .userAvatar {
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

    .leaveBtn {
        opacity: 0;
        transition: opacity 0.2s;
    }

    .listTable tr:hover .leaveBtn {
        opacity: 1;
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

<div class="row mb-3">
    <div class="col-12 d-flex justify-content-between align-items-center">
        <div>
            <h4 class="mb-0">Shared with Me</h4>
            <p class="text-muted mb-0" style="font-size:0.85rem;">
                Documents and folders others have shared with you
                <span class="dblHint">&nbsp;— Click to select &bull; Double-click to open</span>
            </p>
        </div>
        <div class="d-flex gap-2">
            <button class="toggleBtn active" id="listViewBtn" title="List view">
                <i class="ri-list-check"></i>
            </button>
            <button class="toggleBtn" id="gridViewBtn" title="Grid view">
                <i class="ri-grid-fill"></i>
            </button>
        </div>
    </div>
</div>

@if($groupedByDate->isEmpty())
    <div class="card">
        <div class="card-body text-center py-5">
            <i class="ri-share-line" style="font-size:3rem;color:#9ca3af;"></i>
            <h5 class="mt-3 text-muted">No documents shared with you yet</h5>
            <p class="text-muted" style="font-size:0.85rem;">When someone shares a document with you, it will appear here.</p>
        </div>
    </div>
@else

<div id="listView">
    <div class="card">
        <div class="card-body p-0">
            <table class="listTable">
                <thead>
                    <tr>
                        <th style="width:50%;">Name</th>
                        <th>Shared by</th>
                        <th>Date shared</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($groupedByDate as $label => $items)
                        <tr>
                            <td colspan="4" class="dateLabel">{{ $label }}</td>
                        </tr>
                        @foreach($items as $item)
                        <tr class="rowClick listItem"
                            data-type="{{ $item['type'] }}"
                            data-href-folder="{{ $item['type'] === 'folder' ? route('shared-with-me.folder', $item['id']) : '' }}"
                            data-href-doc="{{ $item['type'] !== 'folder' ? url('documents/view-document/' . $item['id']) : '' }}">
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    @if($item['type'] === 'folder')
                                        <i class="ri-folder-2-fill" style="font-size:1.2rem;color:#e67e22;"></i>
                                    @else
                                        <i class="{{ $item['iconClass'] }}" style="font-size:1.2rem;color:#6b7280;"></i>
                                    @endif
                                    <span style="font-size:0.875rem;font-weight:500;">{{ $item['name'] }}</span>
                                    @if($item['type'] === 'folder')
                                        <span style="font-size:0.68rem;background:#fff3cd;color:#856404;padding:1px 6px;border-radius:10px;">Folder</span>
                                    @endif
                                </div>
                            </td>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <div class="userAvatar" style="background:{{ $item['ownerColor'] }};">
                                        {{ strtoupper(substr($item['ownerName'], 0, 1)) }}
                                    </div>
                                    <span style="font-size:0.82rem;">{{ $item['ownerName'] }}</span>
                                </div>
                            </td>
                            <td style="font-size:0.82rem;color:#6b7280;">{{ $item['dateShared'] }}</td>
                            <td class="noClick">
                                <button class="btn btn-sm btn-outline-danger leaveBtn"
                                    title="Leave share"
                                    data-document-id="{{ $item['type'] !== 'folder' ? $item['id'] : '' }}"
                                    data-folder-id="{{ $item['type'] === 'folder' ? $item['id'] : '' }}"
                                    data-item-name="{{ $item['name'] }}"
                                    data-item-type="{{ $item['type'] }}"
                                    onclick="leaveShare(this)">
                                    <i class="ri-logout-box-line"></i>
                                </button>
                            </td>
                        </tr>
                        @endforeach
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<div id="gridView" style="display:none;">
    @foreach($groupedByDate as $label => $items)
        <div class="dateLabelGrid">{{ $label }}</div>
        <div class="gridWrapper">
            @foreach($items as $item)
            <div class="gridCard gridItem"
                data-type="{{ $item['type'] }}"
                data-href-folder="{{ $item['type'] === 'folder' ? route('shared-with-me.folder', $item['id']) : '' }}"
                data-href-doc="{{ $item['type'] !== 'folder' ? url('documents/view-document/' . $item['id']) : '' }}">
                <div class="cardTop">
                    @if($item['type'] === 'folder')
                        <i class="ri-folder-2-fill fileIconSmall" style="color:#e67e22;"></i>
                    @else
                        <i class="{{ $item['iconClass'] }} fileIconSmall" style="color:#6b7280;"></i>
                    @endif
                    <button class="btn btn-sm btn-outline-danger py-0 px-1 noClick"
                        style="font-size:0.65rem;"
                        title="Leave"
                        data-document-id="{{ $item['type'] !== 'folder' ? $item['id'] : '' }}"
                        data-folder-id="{{ $item['type'] === 'folder' ? $item['id'] : '' }}"
                        data-item-name="{{ $item['name'] }}"
                        data-item-type="{{ $item['type'] }}"
                        onclick="event.stopPropagation(); leaveShare(this)">
                        <i class="ri-logout-box-line"></i>
                    </button>
                </div>
                <div class="previewBox {{ $item['type'] === 'folder' ? 'folder-preview' : $item['previewClass'] }}">
                    @if($item['type'] === 'folder')
                        <i class="ri-folder-2-fill"></i>
                    @else
                        <i class="{{ $item['iconClass'] }}"></i>
                    @endif
                </div>
                <div class="cardBottom">
                    <div class="cardTitle">{{ $item['name'] }}</div>
                    <div class="cardMeta">
                        <i class="ri-user-line"></i> {{ $item['ownerName'] }}
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    @endforeach
</div>

@endif
@endsection

@section('js')
<script src="{{ asset('login_css/js/plugins/sweetalert/sweetalert.min.js') }}"></script>
<script>
$(document).ready(function () {

    $('#listViewBtn').on('click', function () {
        $('#listView').show();
        $('#gridView').hide();
        $(this).addClass('active');
        $('#gridViewBtn').removeClass('active');
        localStorage.setItem('sharedWithMeView', 'list');
    });
    $('#gridViewBtn').on('click', function () {
        $('#gridView').show();
        $('#listView').hide();
        $(this).addClass('active');
        $('#listViewBtn').removeClass('active');
        localStorage.setItem('sharedWithMeView', 'grid');
    });
    if (localStorage.getItem('sharedWithMeView') === 'grid') {
        $('#gridViewBtn').trigger('click');
    }

    var hintTimer = null;
    function showHint() {
        clearTimeout(hintTimer);
        $('#floatHint').fadeIn(150);
        hintTimer = setTimeout(function () { $('#floatHint').fadeOut(300); }, 2500);
    }

    $(document).on('click', 'tr.listItem', function (e) {
        if ($(e.target).closest('.noClick, .leaveBtn, a, button').length) return;
        $('tr.listItem').removeClass('rowActive');
        $(this).addClass('rowActive');
        showHint();
    });

    $(document).on('dblclick', 'tr.listItem', function (e) {
        if ($(e.target).closest('.noClick, .leaveBtn, a, button').length) return;
        var type   = $(this).data('type');
        var folder = $(this).data('href-folder');
        var doc    = $(this).data('href-doc');
        if (type === 'folder' && folder) {
            window.location.href = folder;
        } else if (doc) {
            window.open(doc, '_blank');
        }
    });

    $(document).on('click', '.gridItem', function (e) {
        if ($(e.target).closest('.noClick, .leaveBtn, a, button').length) return;
        $('.gridItem').removeClass('cardSelected');
        $(this).addClass('cardSelected');
        showHint();
    });

    $(document).on('dblclick', '.gridItem', function (e) {
        if ($(e.target).closest('.noClick, .leaveBtn, a, button').length) return;
        var type   = $(this).data('type');
        var folder = $(this).data('href-folder');
        var doc    = $(this).data('href-doc');
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

});

function leaveShare(btn) {
    var $btn       = $(btn);
    var itemType   = $btn.data('item-type');
    var itemName   = $btn.data('item-name');
    var documentId = $btn.data('document-id');
    var folderId   = $btn.data('folder-id');

    swal({
        title: 'Leave Share?',
        text: 'Remove yourself from "' + itemName + '"? You will lose access.',
        type: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc2626',
        confirmButtonText: 'Leave',
        cancelButtonText: 'Cancel',
        closeOnConfirm: false,
    }, function (confirmed) {
        if (!confirmed) return;

        var url  = itemType === 'folder'
            ? '{{ url("/documents/leave-share-folder") }}'
            : '{{ url("/documents/leave-share") }}';
        var data = itemType === 'folder'
            ? { folder_id: folderId }
            : { document_id: documentId };

        $.ajax({
            type: 'POST',
            url: url,
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            data: data,
            success: function (res) {
                if (res.success) {
                    swal('Done!', res.message, 'success');
                    setTimeout(function () { location.reload(); }, 1200);
                } else {
                    swal('Error!', res.message, 'error');
                }
            },
            error: function () { swal('Error!', 'Something went wrong.', 'error'); }
        });
    });
}
</script>
@endsection