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
    .itemsTable { width: 100%; border-collapse: collapse; }
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
    .itemsTable tr.rowClick { cursor: pointer; }
    .itemsTable tr.rowClick:hover td { background: #f9fafb; }
    .itemsTable tr.rowClick.rowActive td { background: #eff6ff !important; }
    .itemsTable tr.rowClick.rowActive td:first-child { border-left: 3px solid #3b82f6; }
    .itemsTable tr:last-child td { border-bottom: none; }
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
    }
    .itemCard:hover { box-shadow: 0 4px 12px rgba(0,0,0,0.1); border-color: #cbd5e1; }
    .itemCard.cardSelected {
        border-color: #3b82f6 !important;
        background: #eff6ff !important;
        box-shadow: 0 0 0 2px rgba(59,130,246,0.25) !important;
    }
    .itemCard .previewArea {
        height: 100px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2.5rem;
    }
    .folder-preview  { background: #fff8f0; color: #e67e22; }
    .pdf-preview     { background: #fff0f0; color: #dc2626; }
    .docx-preview    { background: #eff6ff; color: #2563eb; }
    .xlsx-preview    { background: #f0fdf4; color: #16a34a; }
    .default-preview { background: #f8f9fa; color: #6b7280; }
    .itemCard .cardTop {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 6px 8px 0;
    }
    .itemCard .cardBottom { padding: 6px 10px 10px; border-top: 1px solid #f0f0f0; }
    .itemCard .cardName {
        font-size: 0.78rem;
        font-weight: 600;
        color: #1a1a2e;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    .itemCard .cardMeta { font-size: 0.68rem; color: #9ca3af; margin-top: 2px; }
    .groupHeadingGrid { font-size: 0.78rem; font-weight: 600; color: #6b7280; padding: 14px 0 6px; }

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
    .avatarRow { display: flex; }
    .avatarRow .miniAvatar { margin-left: -6px; border: 2px solid #fff; }
    .avatarRow .miniAvatar:first-child { margin-left: 0; }

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

    .actionMenuBtn {
        opacity: 0;
        transition: opacity 0.15s;
        background: none;
        border: 1px solid #dee2e6;
        border-radius: 6px;
        padding: 3px 8px;
        color: #6b7280;
        cursor: pointer;
        font-size: 0.82rem;
        line-height: 1.4;
    }
    .actionMenuBtn:hover { background: #f3f4f6; color: #1a1a2e; }
    .itemsTable tr:hover .actionMenuBtn { opacity: 1; }

    .dblHint { font-size: 0.68rem; color: #9ca3af; font-style: italic; }
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
        box-shadow: 0 4px 12px rgba(0,0,0,0.25);
        pointer-events: none;
        white-space: nowrap;
    }

    .infoPanel {
        position: fixed;
        top: 0;
        right: -420px;
        width: 380px;
        height: 100vh;
        background: #fff;
        border-left: 1px solid #e5e7eb;
        z-index: 1050;
        display: flex;
        flex-direction: column;
        transition: right 0.25s ease;
        box-shadow: -4px 0 20px rgba(0,0,0,0.08);
    }
    .infoPanel.open { right: 0; }

    .infoPanelHeader {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 16px 20px 0;
        flex-shrink: 0;
    }
    .infoPanelHeader .itemTitle {
        font-size: 0.95rem;
        font-weight: 600;
        color: #1a1a2e;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
        max-width: 290px;
    }
    .infoPanelClose {
        background: none;
        border: none;
        cursor: pointer;
        color: #9ca3af;
        font-size: 1.2rem;
        padding: 2px 6px;
        border-radius: 4px;
        flex-shrink: 0;
    }
    .infoPanelClose:hover { background: #f3f4f6; color: #374151; }

    .infoPanelTabs {
        display: flex;
        border-bottom: 1px solid #e5e7eb;
        margin: 12px 20px 0;
        flex-shrink: 0;
    }
    .infoPanelTab {
        padding: 8px 16px;
        font-size: 0.82rem;
        font-weight: 500;
        color: #6b7280;
        cursor: pointer;
        border-bottom: 2px solid transparent;
        margin-bottom: -1px;
        transition: all 0.15s;
    }
    .infoPanelTab.active {
        color: #2563eb;
        border-bottom-color: #2563eb;
    }
    .infoPanelTab:hover:not(.active) { color: #374151; }

    .infoPanelBody {
        flex: 1;
        overflow-y: auto;
        padding: 16px 20px;
    }

    .detailIconBox {
        width: 64px;
        height: 64px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2rem;
        margin: 0 auto 14px;
    }
    .detailRow {
        display: flex;
        flex-direction: column;
        gap: 2px;
        padding: 10px 0;
        border-bottom: 1px solid #f3f4f6;
    }
    .detailRow:last-child { border-bottom: none; }
    .detailLabel {
        font-size: 0.7rem;
        font-weight: 600;
        color: #9ca3af;
        text-transform: uppercase;
        letter-spacing: 0.04em;
    }
    .detailValue {
        font-size: 0.82rem;
        color: #374151;
        font-weight: 500;
    }
    .detailAvatarRow {
        display: flex;
        align-items: center;
        gap: 8px;
        margin-top: 4px;
    }

    .activityItem {
        display: flex;
        gap: 12px;
        padding: 12px 0;
        border-bottom: 1px solid #f3f4f6;
    }
    .activityItem:last-child { border-bottom: none; }
    .activityDot {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.85rem;
        flex-shrink: 0;
        margin-top: 2px;
    }
    .activityContent { flex: 1; }
    .activityText {
        font-size: 0.8rem;
        color: #374151;
        line-height: 1.4;
    }
    .activityText strong { color: #1a1a2e; }
    .activityTime {
        font-size: 0.7rem;
        color: #9ca3af;
        margin-top: 3px;
    }

    .infoPanelOverlay {
        display: none;
        position: fixed;
        inset: 0;
        z-index: 1040;
        background: transparent;
    }
    .infoPanelOverlay.open { display: block; }
</style>
@endsection

@section('content')

<div class="floatHint" id="floatHint">
    <i class="ri-mouse-line me-1"></i> Double-click to open
</div>

<div class="infoPanelOverlay" id="infoPanelOverlay"></div>

<div class="infoPanel" id="infoPanel">
    <div class="infoPanelHeader">
        <span class="itemTitle" id="infoPanelTitle">—</span>
        <button class="infoPanelClose" id="infoPanelClose"><i class="ri-close-line"></i></button>
    </div>
    <div class="infoPanelTabs">
        <div class="infoPanelTab active" data-tab="details">Details</div>
        <div class="infoPanelTab" data-tab="activity">Activity</div>
    </div>
    <div class="infoPanelBody">
        <div id="tabDetails">
            <div class="text-center mb-3">
                <div class="detailIconBox" id="detailIconBox" style="background:#fff8f0;">
                    <i id="detailIcon" class="ri-folder-2-fill" style="color:#e67e22;"></i>
                </div>
            </div>
            <div id="detailRows"></div>
        </div>
        <div id="tabActivity" style="display:none;">
            <div id="activityList">
                <div class="text-center py-4 text-muted" style="font-size:0.82rem;">
                    <div class="spinner-border spinner-border-sm mb-2" role="status"></div>
                    <div>Loading activity...</div>
                </div>
            </div>
        </div>
    </div>
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
        <div class="d-flex gap-1">
            <button class="btn btn-light btn-sm toggleBtn" id="listViewBtn" title="List view">
                <i class="ri-list-check"></i>
            </button>
            <button class="btn btn-light btn-sm toggleBtn active" id="gridViewBtn" title="Grid view">
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

<div id="listView" style="display:none;">
    <div class="card">
        <div class="card-body p-0">
            <table class="itemsTable">
                <thead>
                    <tr>
                        <th style="width:34%;">Name</th>
                        <th style="width:15%;">Owner</th>
                        <th style="width:22%;">Shared with</th>
                        <th style="width:12%;">Date</th>
                        <th style="width:17%;"></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($groupedByDate as $label => $items)
                        <tr>
                            <td colspan="5" class="groupHeading">{{ $label }}</td>
                        </tr>
                        @foreach($items as $item)
                        @php
                            $itemId  = $item['type'] === 'folder' ? $item['id'] : ($item['docId'] ?? $item['id']);
                            $hrefDoc = $item['type'] !== 'folder' ? url('documents/view-document/' . $itemId) : '';
                            $hrefFol = $item['type'] === 'folder' ? route('shared-with-others.folder', $item['id']) : '';
                        @endphp
                        <tr class="rowClick listItem"
                            data-type="{{ $item['type'] }}"
                            data-href-folder="{{ $hrefFol }}"
                            data-href-doc="{{ $hrefDoc }}">
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    @if($item['type'] === 'folder')
                                        <i class="ri-folder-2-fill" style="font-size:1.2rem;color:#e67e22;flex-shrink:0;"></i>
                                    @else
                                        <i class="{{ $item['iconClass'] }}" style="font-size:1.2rem;color:#6b7280;flex-shrink:0;"></i>
                                    @endif
                                    <span style="font-weight:500;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;max-width:220px;" title="{{ $item['name'] }}">
                                        {{ $item['name'] }}
                                    </span>
                                    @if($item['type'] === 'folder')
                                        <span style="font-size:0.68rem;background:#fff3cd;color:#856404;padding:1px 7px;border-radius:10px;flex-shrink:0;">Folder</span>
                                    @endif
                                </div>
                            </td>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <div class="userAvatar" style="background:{{ $item['ownerColor'] }};">
                                        {{ strtoupper(substr($item['ownerName'], 0, 1)) }}
                                    </div>
                                    <span style="font-size:0.82rem;white-space:nowrap;">{{ $item['ownerName'] }}</span>
                                </div>
                            </td>
                            <td class="noClick">
                                <div class="d-flex align-items-center gap-2 flex-wrap">
                                    <div class="avatarRow">
                                        @foreach($item['sharedUsers']->take(4) as $share)
                                        <div class="miniAvatar" style="background:{{ $share->avatarColor }};" title="{{ $share->user->name ?? '—' }}">
                                            {{ strtoupper(substr($share->user->name ?? '?', 0, 1)) }}
                                        </div>
                                        @endforeach
                                        @if($item['sharedUsers']->count() > 4)
                                        <div class="miniAvatar" style="background:#6b7280;">+{{ $item['sharedUsers']->count() - 4 }}</div>
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
                                                data-item-id="{{ $itemId }}"
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
                            <td class="noClick" style="text-align:right;">
                                <div class="dropdown d-inline-block">
                                    <button class="actionMenuBtn noClick dropdown-toggle"
                                        data-bs-toggle="dropdown"
                                        onclick="event.stopPropagation()">
                                        <i class="ri-more-2-fill"></i>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end" style="font-size:0.82rem;min-width:160px;">
                                        @if($item['type'] === 'folder')
                                        <li>
                                            <a class="dropdown-item" href="{{ $hrefFol }}" onclick="event.stopPropagation()">
                                                <i class="ri-folder-open-line me-2"></i>Open folder
                                            </a>
                                        </li>
                                        @else
                                        <li>
                                            <a class="dropdown-item" href="{{ $hrefDoc }}" target="_blank" onclick="event.stopPropagation()">
                                                <i class="ri-eye-line me-2"></i>Open document
                                            </a>
                                        </li>
                                        @endif
                                        <li>
                                            <a class="dropdown-item" href="javascript:void(0)"
                                                onclick="event.stopPropagation(); openInfoPanel(this)"
                                                data-item-type="{{ $item['type'] }}"
                                                data-item-id="{{ $itemId }}"
                                                data-item-name="{{ $item['name'] }}"
                                                data-item-owner="{{ $item['ownerName'] }}"
                                                data-item-owner-color="{{ $item['ownerColor'] }}"
                                                data-item-date="{{ $item['dateLabel'] }}"
                                                data-item-icon="{{ $item['type'] === 'folder' ? 'ri-folder-2-fill' : $item['iconClass'] }}"
                                                data-item-preview="{{ $item['type'] === 'folder' ? 'folder-preview' : $item['previewClass'] }}"
                                                data-item-shared-with="{{ $item['sharedWithNames'] }}">
                                                <i class="ri-information-line me-2"></i>View info
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<div id="gridView" style="padding:12px 16px;">
    @foreach($groupedByDate as $label => $items)
        <div class="groupHeadingGrid">{{ $label }}</div>
        <div class="itemsGrid">
            @foreach($items as $item)
            @php
                $itemId  = $item['type'] === 'folder' ? $item['id'] : ($item['docId'] ?? $item['id']);
                $hrefDoc = $item['type'] !== 'folder' ? url('documents/view-document/' . $itemId) : '';
                $hrefFol = $item['type'] === 'folder' ? route('shared-with-others.folder', $item['id']) : '';
            @endphp
            <div class="itemCard gridItem"
                data-type="{{ $item['type'] }}"
                data-href-folder="{{ $hrefFol }}"
                data-href-doc="{{ $hrefDoc }}">
                <div class="cardTop">
                    @if($item['type'] === 'folder')
                        <i class="ri-folder-2-fill" style="color:#e67e22;font-size:1rem;"></i>
                    @else
                        <i class="{{ $item['iconClass'] }}" style="color:#6b7280;font-size:1rem;"></i>
                    @endif
                    <div class="dropdown noClick">
                        <button class="btn btn-sm py-0 px-1 noClick"
                            style="font-size:0.75rem;color:#9ca3af;background:none;border:none;"
                            data-bs-toggle="dropdown"
                            onclick="event.stopPropagation()">
                            <i class="ri-more-2-fill"></i>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end" style="font-size:0.82rem;min-width:150px;">
                            @if($item['type'] === 'folder')
                            <li>
                                <a class="dropdown-item" href="{{ $hrefFol }}" onclick="event.stopPropagation()">
                                    <i class="ri-folder-open-line me-2"></i>Open folder
                                </a>
                            </li>
                            @else
                            <li>
                                <a class="dropdown-item" href="{{ $hrefDoc }}" target="_blank" onclick="event.stopPropagation()">
                                    <i class="ri-eye-line me-2"></i>Open document
                                </a>
                            </li>
                            @endif
                            <li>
                                <a class="dropdown-item" href="javascript:void(0)"
                                    onclick="event.stopPropagation(); openInfoPanel(this)"
                                    data-item-type="{{ $item['type'] }}"
                                    data-item-id="{{ $itemId }}"
                                    data-item-name="{{ $item['name'] }}"
                                    data-item-owner="{{ $item['ownerName'] }}"
                                    data-item-owner-color="{{ $item['ownerColor'] }}"
                                    data-item-date="{{ $item['dateLabel'] }}"
                                    data-item-icon="{{ $item['type'] === 'folder' ? 'ri-folder-2-fill' : $item['iconClass'] }}"
                                    data-item-preview="{{ $item['type'] === 'folder' ? 'folder-preview' : $item['previewClass'] }}"
                                    data-item-shared-with="{{ $item['sharedWithNames'] }}">
                                    <i class="ri-information-line me-2"></i>View info
                                </a>
                            </li>
                        </ul>
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
                    <div class="cardName" title="{{ $item['name'] }}">{{ $item['name'] }}</div>
                    <div class="cardMeta">
                        <div class="d-flex align-items-center gap-1 mt-1">
                            <div style="background:{{ $item['ownerColor'] }};width:16px;height:16px;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:0.55rem;font-weight:700;color:#fff;flex-shrink:0;">
                                {{ strtoupper(substr($item['ownerName'], 0, 1)) }}
                            </div>
                            <span style="font-size:0.65rem;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">{{ $item['ownerName'] }}</span>
                        </div>
                        <div style="margin-top:3px;">
                            {{ $item['sharedUsers']->count() }} user{{ $item['sharedUsers']->count() !== 1 ? 's' : '' }}
                            &bull; {{ $item['dateLabel'] }}
                        </div>
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
        localStorage.setItem('sharedWithOthersView', 'list');
    });

    $('#gridViewBtn').on('click', function () {
        $('#gridView').show();
        $('#listView').hide();
        $(this).addClass('active');
        $('#listViewBtn').removeClass('active');
        localStorage.setItem('sharedWithOthersView', 'grid');
    });

    var savedView = localStorage.getItem('sharedWithOthersView');
    if (savedView === 'list') {
        $('#listViewBtn').trigger('click');
    } else {
        $('#gridViewBtn').trigger('click');
    }

    var hintTimer = null;
    function showHint() {
        clearTimeout(hintTimer);
        $('#floatHint').fadeIn(150);
        hintTimer = setTimeout(function () { $('#floatHint').fadeOut(300); }, 2500);
    }

    $(document).on('click', 'tr.listItem', function (e) {
        if ($(e.target).closest('.noClick, .revokeBtn, a, button, .dropdown').length) return;
        $('tr.listItem').removeClass('rowActive');
        $(this).addClass('rowActive');
        showHint();
    });

    $(document).on('dblclick', 'tr.listItem', function (e) {
        if ($(e.target).closest('.noClick, .revokeBtn, a, button, .dropdown').length) return;
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
        if ($(e.target).closest('.noClick, .revokeBtn, a, button, .dropdown').length) return;
        $('.gridItem').removeClass('cardSelected');
        $(this).addClass('cardSelected');
        showHint();
    });

    $(document).on('dblclick', '.gridItem', function (e) {
        if ($(e.target).closest('.noClick, .revokeBtn, a, button, .dropdown').length) return;
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

    $(document).on('click', '.infoPanelTab', function () {
        $('.infoPanelTab').removeClass('active');
        $(this).addClass('active');
        var tab = $(this).data('tab');
        $('#tabDetails, #tabActivity').hide();
        if (tab === 'details') {
            $('#tabDetails').show();
        } else {
            $('#tabActivity').show();
            loadActivity();
        }
    });

    $('#infoPanelClose, #infoPanelOverlay').on('click', function () {
        closeInfoPanel();
    });

    $(document).on('keydown', function (e) {
        if (e.key === 'Escape') { closeInfoPanel(); }
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
                    error: function () { swal('Error!', 'Something went wrong.', 'error'); }
                });
            });
        }, 0);
    });
});

var currentPanelData = {};

function openInfoPanel(el) {
    var $el = $(el);

    currentPanelData = {
        type : $el.data('item-type'),
        id : $el.data('item-id'),
        name : $el.data('item-name'),
        owner : $el.data('item-owner'),
        ownerColor : $el.data('item-owner-color'),
        date : $el.data('item-date'),
        icon : $el.data('item-icon'),
        preview : $el.data('item-preview'),
        sharedWith : $el.data('item-shared-with') || ''
    };

    $('#infoPanelTitle').text(currentPanelData.name);

    var previewCls = currentPanelData.preview;
    var bgMap = {
        'folder-preview' : '#fff8f0',
        'pdf-preview' : '#fff0f0',
        'docx-preview' : '#eff6ff',
        'xlsx-preview' : '#f0fdf4',
        'default-preview' : '#f8f9fa'
    };
    var colorMap = {
        'folder-preview' : '#e67e22',
        'pdf-preview' : '#dc2626',
        'docx-preview' : '#2563eb',
        'xlsx-preview' : '#16a34a',
        'default-preview' : '#6b7280'
    };
    $('#detailIconBox').css('background', bgMap[previewCls] || '#f8f9fa');
    $('#detailIcon').attr('class', currentPanelData.icon).css('color', colorMap[previewCls] || '#6b7280');

    var typeLabel = currentPanelData.type === 'folder' ? 'Folder' : 'Document';
    var initials = currentPanelData.owner ? currentPanelData.owner.charAt(0).toUpperCase() : '?';

    var sharedWithHtml = '—';
    if (currentPanelData.sharedWith) {
        var names = currentPanelData.sharedWith.split(', ').filter(function(n) { return n.trim(); });
        if (names.length > 0) {
            sharedWithHtml = names.join(', ');
        }
    }

    $('#detailRows').html(
        '<div class="detailRow">' +
            '<div class="detailLabel">Type</div>' +
            '<div class="detailValue">' + typeLabel + '</div>' +
        '</div>' +
        '<div class="detailRow">' +
            '<div class="detailLabel">Owner</div>' +
            '<div class="detailAvatarRow">' +
                '<div class="userAvatar" style="background:' + currentPanelData.ownerColor + ';width:24px;height:24px;font-size:0.72rem;">' + initials + '</div>' +
                '<div class="detailValue">' + currentPanelData.owner + '</div>' +
            '</div>' +
        '</div>' +
        '<div class="detailRow">' +
            '<div class="detailLabel">Date shared</div>' +
            '<div class="detailValue">' + currentPanelData.date + '</div>' +
        '</div>' +
        '<div class="detailRow">' +
            '<div class="detailLabel">Also shared with</div>' +
            '<div class="detailValue">' + sharedWithHtml + '</div>' +
        '</div>'
    );

    $('.infoPanelTab').removeClass('active');
    $('.infoPanelTab[data-tab="details"]').addClass('active');
    $('#tabDetails').show();
    $('#tabActivity').hide();

    $('#activityList').html(
        '<div class="text-center py-4 text-muted" style="font-size:0.82rem;">' +
        '<div class="spinner-border spinner-border-sm mb-2" role="status"></div>' +
        '<div>Loading activity...</div>' +
        '</div>'
    );

    $('#infoPanel').addClass('open');
    $('#infoPanelOverlay').addClass('open');
}

function closeInfoPanel() {
    $('#infoPanel').removeClass('open');
    $('#infoPanelOverlay').removeClass('open');
    currentPanelData = {};
}

function loadActivity() {
    if (!currentPanelData.id) return;

    $.ajax({
        type: 'GET',
        url: '{{ url("/documents/share-activity") }}',
        data: {
            type : currentPanelData.type,
            id   : currentPanelData.id
        },
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        success: function (res) {
            if (!res || res.length === 0) {
                $('#activityList').html(
                    '<div class="text-center py-4 text-muted" style="font-size:0.82rem;">' +
                    '<i class="ri-history-line" style="font-size:1.6rem;display:block;margin-bottom:6px;"></i>' +
                    'No activity yet.</div>'
                );
                return;
            }

            var html = '';
            res.forEach(function (item) {
                var dotBg = item.color || '#e5e7eb';
                var dotColor = '#fff';
                var icon = item.icon || 'ri-share-line';
                html +=
                    '<div class="activityItem">' +
                        '<div class="activityDot" style="background:' + dotBg + ';color:' + dotColor + ';">' +
                            '<i class="' + icon + '"></i>' +
                        '</div>' +
                        '<div class="activityContent">' +
                            '<div class="activityText">' + item.text + '</div>' +
                            '<div class="activityTime">' + item.time + '</div>' +
                        '</div>' +
                    '</div>';
            });
            $('#activityList').html(html);
        },
        error: function () {
            $('#activityList').html(
                '<div class="text-center py-4 text-muted" style="font-size:0.82rem;">Failed to load activity.</div>'
            );
        }
    });
}
</script>
@endsection