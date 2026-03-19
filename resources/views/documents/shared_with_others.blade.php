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
        white-space: nowrap;
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

    .groupHeading {
        font-size: 0.78rem;
        font-weight: 600;
        color: #6b7280;
        padding: 14px 12px 6px;
        border-bottom: 1px solid #f0f0f0;
        background: #fff;
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
        position: relative;
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

    .groupHeadingGrid {
        font-size: 0.78rem;
        font-weight: 600;
        color: #6b7280;
        padding: 14px 0 6px;
    }

    .miniAvatar {
        width: 24px;
        height: 24px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.65rem;
        font-weight: 700;
        color: #fff;
        flex-shrink: 0;
    }

    .avatarRow {
        display: flex;
    }

    .avatarRow .miniAvatar {
        margin-left: -6px;
        border: 2px solid #fff;
    }

    .avatarRow .miniAvatar:first-child {
        margin-left: 0;
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
            <h4 class="mb-0">Shared with Others</h4>
            <p class="text-muted mb-0" style="font-size:0.85rem;">
                Your documents and folders shared with other users
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
            <i class="ri-user-shared-line" style="font-size:3rem;color:#9ca3af;"></i>
            <h5 class="mt-3 text-muted">You haven't shared anything yet</h5>
            <p class="text-muted" style="font-size:0.85rem;">Documents and folders you share will appear here.</p>
        </div>
    </div>
@else

<div id="listView">
    <div class="card">
        <div class="card-body p-0">
            <table class="itemsTable">
                <thead>
                    <tr>
                        <th style="width:40%;">Name</th>
                        <th>Shared With</th>
                        <th>Date</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($groupedByDate as $label => $items)
                        <tr>
                            <td colspan="4" class="groupHeading">{{ $label }}</td>
                        </tr>
                        @foreach($items as $item)
                        <tr class="rowClick listItem"
                            data-href-folder="{{ $item['type'] === 'folder' ? route('shared-with-others.folder', $item['id']) : '' }}"
                            data-href-doc="{{ $item['type'] !== 'folder' ? url('documents/view-document/' . ($item['docId'] ?? $item['id'])) : '' }}"
                            data-type="{{ $item['type'] }}">
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    @if($item['type'] === 'folder')
                                        <i class="ri-folder-2-fill" style="font-size:1.2rem;color:#e67e22;"></i>
                                    @else
                                        <i class="{{ $item['iconClass'] }}" style="font-size:1.2rem;color:#6b7280;"></i>
                                    @endif
                                    <span style="font-weight:500;">{{ $item['name'] }}</span>
                                    @if($item['type'] === 'folder')
                                        <span style="font-size:0.68rem;background:#fff3cd;color:#856404;padding:1px 6px;border-radius:10px;">Folder</span>
                                    @endif
                                </div>
                            </td>
                            <td class="noClick">
                                <div class="d-flex align-items-center gap-2 flex-wrap">
                                    <div class="avatarRow">
                                        @foreach($item['sharedUsers']->take(4) as $share)
                                        <div class="miniAvatar"
                                            style="background:{{ $share->avatarColor }};"
                                            title="{{ $share->user->name ?? '—' }}">
                                            {{ strtoupper(substr($share->user->name ?? '?', 0, 1)) }}
                                        </div>
                                        @endforeach
                                        @if($item['sharedUsers']->count() > 4)
                                        <div class="miniAvatar" style="background:#6b7280;">
                                            +{{ $item['sharedUsers']->count() - 4 }}
                                        </div>
                                        @endif
                                    </div>
                                    <div class="d-flex flex-wrap gap-1">
                                        @foreach($item['sharedUsers'] as $share)
                                        <span class="badge d-inline-flex align-items-center gap-1"
                                            style="background:#f0f4ff;color:#1a1a2e;border:1px solid #d0d9f0;font-size:0.7rem;padding:3px 7px;">
                                            {{ $share->user->name ?? '—' }}
                                            <button type="button"
                                                class="btn-close btn-close-sm revokeBtn ms-1"
                                                style="font-size:0.5rem;opacity:0.6;"
                                                title="Remove access"
                                                data-item-type="{{ $item['type'] }}"
                                                data-item-id="{{ $item['type'] === 'folder' ? $item['id'] : ($item['docId'] ?? $item['id']) }}"
                                                data-item-name="{{ $item['name'] }}"
                                                data-user-id="{{ $share->user_id }}"
                                                data-user-name="{{ $share->user->name ?? '' }}">
                                            </button>
                                        </span>
                                        @endforeach
                                    </div>
                                </div>
                            </td>
                            <td style="font-size:0.82rem;color:#6b7280;white-space:nowrap;">{{ $item['dateLabel'] }}</td>
                            <td class="noClick">
                                @if($item['type'] === 'folder')
                                <a href="{{ route('shared-with-others.folder', $item['id']) }}"
                                   class="btn btn-sm btn-outline-secondary py-0 px-2"
                                   title="Open folder">
                                    <i class="ri-folder-open-line"></i>
                                </a>
                                @else
                                <a href="{{ url('documents/view-document/' . ($item['docId'] ?? $item['id'])) }}"
                                   target="_blank"
                                   class="btn btn-sm btn-outline-primary py-0 px-2"
                                   title="View document">
                                    <i class="ri-eye-line"></i>
                                </a>
                                @endif
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
        <div class="groupHeadingGrid">{{ $label }}</div>
        <div class="itemsGrid">
            @foreach($items as $item)
            <div class="itemCard gridItem"
                data-href-folder="{{ $item['type'] === 'folder' ? route('shared-with-others.folder', $item['id']) : '' }}"
                data-href-doc="{{ $item['type'] !== 'folder' ? url('documents/view-document/' . ($item['docId'] ?? $item['id'])) : '' }}"
                data-type="{{ $item['type'] }}">
                <div class="cardTop">
                    @if($item['type'] === 'folder')
                        <i class="ri-folder-2-fill" style="color:#e67e22;font-size:1rem;"></i>
                    @else
                        <i class="{{ $item['iconClass'] }}" style="color:#6b7280;font-size:1rem;"></i>
                    @endif
                    <div class="avatarRow noClick">
                        @foreach($item['sharedUsers']->take(3) as $share)
                        <div class="miniAvatar"
                            style="background:{{ $share->avatarColor }};width:20px;height:20px;font-size:0.6rem;"
                            title="{{ $share->user->name ?? '—' }}">
                            {{ strtoupper(substr($share->user->name ?? '?', 0, 1)) }}
                        </div>
                        @endforeach
                        @if($item['sharedUsers']->count() > 3)
                        <div class="miniAvatar" style="background:#6b7280;width:20px;height:20px;font-size:0.6rem;">
                            +{{ $item['sharedUsers']->count() - 3 }}
                        </div>
                        @endif
                    </div>
                </div>
                <div class="previewArea {{ $item['type'] === 'folder' ? 'folder-preview' : $item['previewClass'] }}">
                    @if($item['type'] === 'folder')
                        <i class="ri-folder-2-fill"></i>
                    @else
                        <i class="{{ $item['iconClass'] }}"></i>
                    @endif
                </div>
                <div class="cardBottom">
                    <div class="cardName">{{ $item['name'] }}</div>
                    <div class="cardMeta">
                        {{ $item['sharedUsers']->count() }} user{{ $item['sharedUsers']->count() !== 1 ? 's' : '' }}
                        &bull; {{ $item['dateLabel'] }}
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
        $('#listView').show(); $('#gridView').hide();
        $(this).addClass('active'); $('#gridViewBtn').removeClass('active');
        localStorage.setItem('sharedWithOthersView', 'list');
    });
    $('#gridViewBtn').on('click', function () {
        $('#gridView').show(); $('#listView').hide();
        $(this).addClass('active'); $('#listViewBtn').removeClass('active');
        localStorage.setItem('sharedWithOthersView', 'grid');
    });
    if (localStorage.getItem('sharedWithOthersView') === 'grid') {
        $('#gridViewBtn').trigger('click');
    }

    var hintTimer = null;
    function showHint() {
        clearTimeout(hintTimer);
        $('#floatHint').fadeIn(150);
        hintTimer = setTimeout(function () { $('#floatHint').fadeOut(300); }, 2500);
    }

    $(document).on('click', 'tr.listItem', function (e) {
        if ($(e.target).closest('.noClick, .revokeBtn, a, button').length) return;

        $('tr.listItem').removeClass('rowActive');
        $(this).addClass('rowActive');
        showHint();
    });

    $(document).on('dblclick', 'tr.listItem', function (e) {
        if ($(e.target).closest('.noClick, .revokeBtn, a, button').length) return;

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
        if ($(e.target).closest('.noClick, .revokeBtn, a, button').length) return;

        $('.gridItem').removeClass('cardSelected');
        $(this).addClass('cardSelected');
        showHint();
    });

    $(document).on('dblclick', '.gridItem', function (e) {
        if ($(e.target).closest('.noClick, .revokeBtn, a, button').length) return;

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

    $(document).on('click', '.revokeBtn', function (e) {
        e.stopPropagation();
        e.preventDefault();

        var itemType = $(this).data('item-type');
        var itemId = $(this).data('item-id');
        var itemName = $(this).data('item-name');
        var userId = $(this).data('user-id');
        var userName = $(this).data('user-name');

        setTimeout(function () {
            swal({
                title: 'Remove Access?',
                text: 'Remove "' + userName + '" from "' + itemName + '"?',
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc2626',
                confirmButtonText: 'Remove',
                cancelButtonText: 'Cancel',
                closeOnConfirm: false,
            }, function (confirmed) {
                if (!confirmed) return;

                var url = itemType === 'folder'
                    ? '{{ url("/documents/revoke-folder-access") }}'
                    : '{{ url("/documents/revoke-access") }}';
                var data = itemType === 'folder'
                    ? { folder_id: itemId, user_id: userId }
                    : { document_id: itemId, user_id: userId };

                $.ajax({
                    type: 'POST',
                    url: url,
                    headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                    data: data,
                    success: function (res) {
                        if (res.success) {
                            swal('Removed!', res.message, 'success');
                            setTimeout(function () { location.reload(); }, 1200);
                        } else {
                            swal('Error!', res.message, 'error');
                        }
                    },
                    error: function () {
                        swal('Error!', 'Something went wrong.', 'error');
                    }
                });
            });
        }, 0);
    });

});
</script>
@endsection