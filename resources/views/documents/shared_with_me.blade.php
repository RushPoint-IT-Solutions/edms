@extends('layouts.header')

@section('css')
<style>
    .toggle-btn {
        background: none;
        border: 1px solid #dee2e6;
        padding: 5px 10px;
        border-radius: 4px;
        color: #6b7280;
        cursor: pointer;
        transition: all 0.2s;
    }
    .toggle-btn.active {
        background: #f3f4f6;
        color: #1a1a2e;
        border-color: #adb5bd;
    }
    .list-table {
        width: 100%;
        border-collapse: collapse;
    }
    .list-table th {
        font-size: 0.75rem;
        font-weight: 600;
        color: #6b7280;
        padding: 8px 12px;
        border-bottom: 1px solid #e5e7eb;
        text-align: left;
        white-space: nowrap;
    }
    .list-table td {
        padding: 10px 12px;
        border-bottom: 1px solid #f3f4f6;
        vertical-align: middle;
    }
    .list-table tr.row-item { cursor: pointer; }
    .list-table tr.row-item:hover td { background: #f9fafb; }
    .list-table tr.row-item.row-active td { background: #eff6ff !important; }
    .list-table tr.row-item.row-active td:first-child { border-left: 3px solid #3b82f6; }
    .list-table tr:last-child td { border-bottom: none; }
    .date-label {
        font-size: 0.78rem;
        font-weight: 600;
        color: #6b7280;
        padding: 14px 12px 6px;
        border-bottom: 1px solid #f0f0f0;
        background: #fff;
    }
    .grid-wrap {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(160px, 1fr));
        gap: 12px;
        padding: 12px 0;
    }
    .grid-card {
        border: 1px solid #e5e7eb;
        border-radius: 10px;
        overflow: hidden;
        cursor: pointer;
        transition: box-shadow 0.2s, border-color 0.2s;
        background: #fff;
    }
    .grid-card:hover { box-shadow: 0 4px 12px rgba(0,0,0,0.1); border-color: #cbd5e1; }
    .grid-card.card-selected {
        border-color: #3b82f6 !important;
        background: #eff6ff !important;
        box-shadow: 0 0 0 2px rgba(59,130,246,0.25) !important;
    }
    .grid-card .card-preview {
        height: 90px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2.2rem;
    }
    .folder-preview { background: #fff8f0; color: #e67e22; }
    .pdf-preview { background: #fff0f0; color: #dc2626; }
    .docx-preview { background: #eff6ff; color: #2563eb; }
    .xlsx-preview { background: #f0fdf4; color: #16a34a; }
    .default-preview { background: #f8f9fa; color: #6b7280; }
    .grid-card .card-footer {
        padding: 8px 10px;
        border-top: 1px solid #f0f0f0;
    }
    .grid-card .card-title {
        font-size: 0.78rem;
        font-weight: 600;
        color: #1a1a2e;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    .grid-card .card-meta {
        font-size: 0.68rem;
        color: #9ca3af;
        margin-top: 2px;
    }
    .grid-card .card-top {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 6px 8px 4px;
    }
    .date-label-grid {
        font-size: 0.78rem;
        font-weight: 600;
        color: #6b7280;
        padding: 14px 0 6px;
    }
    .user-avatar {
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
    .avatar-stack { display: flex; align-items: center; }
    .avatar-stack .user-avatar {
        margin-left: -6px;
        border: 2px solid #fff;
        width: 24px;
        height: 24px;
        font-size: 0.65rem;
    }
    .avatar-stack .user-avatar:first-child { margin-left: 0; }
    .avatar-more {
        width: 24px;
        height: 24px;
        border-radius: 50%;
        background: #e5e7eb;
        color: #6b7280;
        font-size: 0.6rem;
        font-weight: 700;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-left: -6px;
        border: 2px solid #fff;
        flex-shrink: 0;
    }
    .action-btn {
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
    .action-btn:hover { background: #f3f4f6; color: #1a1a2e; }
    .list-table tr:hover .action-btn { opacity: 1; }
    .dbl-hint { font-size: 0.68rem; color: #9ca3af; font-style: italic; }
    .float-hint {
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
    .info-panel {
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
    .info-panel.open { right: 0; }
    .panel-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 16px 20px 0;
        flex-shrink: 0;
    }
    .panel-header .panel-title {
        font-size: 0.95rem;
        font-weight: 600;
        color: #1a1a2e;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
        max-width: 290px;
    }
    .panel-close {
        background: none;
        border: none;
        cursor: pointer;
        color: #9ca3af;
        font-size: 1.2rem;
        padding: 2px 6px;
        border-radius: 4px;
        flex-shrink: 0;
    }
    .panel-close:hover { background: #f3f4f6; color: #374151; }
    .panel-tabs {
        display: flex;
        border-bottom: 1px solid #e5e7eb;
        margin: 12px 20px 0;
        flex-shrink: 0;
    }
    .panel-tab {
        padding: 8px 16px;
        font-size: 0.82rem;
        font-weight: 500;
        color: #6b7280;
        cursor: pointer;
        border-bottom: 2px solid transparent;
        margin-bottom: -1px;
        transition: all 0.15s;
    }
    .panel-tab.active { color: #2563eb; border-bottom-color: #2563eb; }
    .panel-tab:hover:not(.active) { color: #374151; }
    .panel-body { flex: 1; overflow-y: auto; padding: 16px 20px; }
    .detail-icon-box {
        width: 64px;
        height: 64px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2rem;
        margin: 0 auto 14px;
    }
    .detail-row {
        display: flex;
        flex-direction: column;
        gap: 2px;
        padding: 10px 0;
        border-bottom: 1px solid #f3f4f6;
    }
    .detail-row:last-child { border-bottom: none; }
    .detail-label { font-size: 0.7rem; font-weight: 600; color: #9ca3af; text-transform: uppercase; letter-spacing: 0.04em; }
    .detail-value { font-size: 0.82rem; color: #374151; font-weight: 500; }
    .detail-avatar-row { display: flex; align-items: center; gap: 8px; margin-top: 4px; }
    .activity-item {
        display: flex;
        gap: 12px;
        padding: 12px 0;
        border-bottom: 1px solid #f3f4f6;
    }
    .activity-item:last-child { border-bottom: none; }
    .activity-dot {
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
    .activity-content { flex: 1; }
    .activity-text { font-size: 0.8rem; color: #374151; line-height: 1.4; }
    .activity-text strong { color: #1a1a2e; }
    .activity-time { font-size: 0.7rem; color: #9ca3af; margin-top: 3px; }
    .panel-overlay {
        display: none;
        position: fixed;
        inset: 0;
        z-index: 1040;
        background: transparent;
    }
    .panel-overlay.open { display: block; }
</style>
@endsection

@section('content')

<div class="float-hint" id="floatHint">
    <i class="ri-mouse-line me-1"></i> Double-click to open
</div>

<div class="panel-overlay" id="panelOverlay"></div>

<div class="info-panel" id="infoPanel">
    <div class="panel-header">
        <span class="panel-title" id="panelTitle">—</span>
        <button class="panel-close" id="panelClose"><i class="ri-close-line"></i></button>
    </div>
    <div class="panel-tabs">
        <div class="panel-tab active" data-tab="details">Details</div>
        <div class="panel-tab" data-tab="activity">Activity</div>
    </div>
    <div class="panel-body">
        <div id="tabDetails">
            <div class="text-center mb-3">
                <div class="detail-icon-box" id="detailIconBox" style="background:#fff8f0;">
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
            <h4 class="mb-0">Shared with Me</h4>
            <p class="text-muted mb-0" style="font-size:0.85rem;">
                Documents and folders others have shared with you
                <span class="dbl-hint">&nbsp;— Click to select &bull; Double-click to open</span>
            </p>
        </div>
        <div class="d-flex gap-1">
            <button class="btn btn-light btn-sm toggle-btn active" id="listViewBtn" title="List view">
                <i class="ri-list-check"></i>
            </button>
            <button class="btn btn-light btn-sm toggle-btn" id="gridViewBtn" title="Grid view">
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
        <p class="text-muted" style="font-size:0.85rem;">When someone shares a document or folder with you, it will appear here.</p>
    </div>
</div>
@else

<div id="listView" style="display: none;">
    <div class="card">
        <div class="card-body p-0">
            <table class="list-table">
                <thead>
                    <tr>
                        <th style="width:36%;">Name</th>
                        <th style="width:15%;">Owner</th>
                        <th style="width:18%;">Shared with</th>
                        <th style="width:13%;">Date shared</th>
                        <th style="width:18%;"></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($groupedByDate as $label => $items)
                    <tr>
                        <td colspan="5" class="date-label">{{ $label }}</td>
                    </tr>
                    @foreach($items as $item)
                    <tr class="row-item list-item"
                        data-type="{{ $item['type'] }}"
                        data-id="{{ $item['id'] }}"
                        data-name="{{ $item['name'] }}"
                        data-owner="{{ $item['ownerName'] }}"
                        data-owner-color="{{ $item['ownerColor'] }}"
                        data-date-shared="{{ $item['dateShared'] }}"
                        data-icon="{{ $item['type'] === 'folder' ? 'ri-folder-2-fill' : $item['iconClass'] }}"
                        data-preview="{{ $item['type'] === 'folder' ? 'folder-preview' : $item['previewClass'] }}"
                        data-href-folder="{{ $item['type'] === 'folder' ? route('shared-with-me.folder', $item['id']) : '' }}"
                        data-href-doc="{{ $item['type'] === 'document' ? url('documents/view-document/' . $item['id']) : '' }}">
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                @if($item['type'] === 'folder')
                                <i class="ri-folder-2-fill" style="font-size:1.2rem;color:#e67e22;flex-shrink:0;"></i>
                                @else
                                <i class="{{ $item['iconClass'] }}" style="font-size:1.2rem;color:#6b7280;flex-shrink:0;"></i>
                                @endif
                                <span style="font-size:0.875rem;font-weight:500;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;max-width:280px;" title="{{ $item['name'] }}">
                                    {{ $item['name'] }}
                                </span>
                                @if($item['type'] === 'folder')
                                <span style="font-size:0.68rem;background:#fff3cd;color:#856404;padding:1px 7px;border-radius:10px;flex-shrink:0;">Folder</span>
                                @endif
                            </div>
                        </td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <div class="user-avatar" style="background:{{ $item['ownerColor'] }};">
                                    {{ strtoupper(substr($item['ownerName'], 0, 1)) }}
                                </div>
                                <span style="font-size:0.82rem;white-space:nowrap;">{{ $item['ownerName'] }}</span>
                            </div>
                        </td>
                        <td>
                            @php $sharedUsers = $item['sharedUsers']; $max = 4; @endphp
                            <div class="avatar-stack" title="{{ $sharedUsers->pluck('user.name')->implode(', ') }}">
                                @foreach($sharedUsers->take($max) as $share)
                                <div class="user-avatar" style="background:{{ $share->avatarColor ?? '#9ca3af' }};" title="{{ $share->user->name ?? '' }}">
                                    {{ strtoupper(substr($share->user->name ?? '?', 0, 1)) }}
                                </div>
                                @endforeach
                                @if($sharedUsers->count() > $max)
                                <div class="avatar-more">+{{ $sharedUsers->count() - $max }}</div>
                                @endif
                                @if($sharedUsers->isEmpty())
                                <span style="font-size:0.75rem;color:#9ca3af;">—</span>
                                @endif
                            </div>
                        </td>
                        <td style="font-size:0.82rem;color:#6b7280;white-space:nowrap;">{{ $item['dateShared'] }}</td>
                        <td class="no-click" style="text-align:right;">
                            <div class="dropdown d-inline-block">
                                <button class="action-btn no-click dropdown-toggle"
                                    data-bs-toggle="dropdown"
                                    onclick="event.stopPropagation()">
                                    <i class="ri-more-2-fill"></i>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end" style="font-size:0.82rem;min-width:160px;">
                                    <li>
                                        <a class="dropdown-item" href="javascript:void(0)"
                                            onclick="event.stopPropagation(); openInfoPanel(this)"
                                            data-item-type="{{ $item['type'] }}"
                                            data-item-id="{{ $item['id'] }}"
                                            data-item-name="{{ $item['name'] }}"
                                            data-item-owner="{{ $item['ownerName'] }}"
                                            data-item-owner-color="{{ $item['ownerColor'] }}"
                                            data-item-date="{{ $item['dateShared'] }}"
                                            data-item-icon="{{ $item['type'] === 'folder' ? 'ri-folder-2-fill' : $item['iconClass'] }}"
                                            data-item-preview="{{ $item['type'] === 'folder' ? 'folder-preview' : $item['previewClass'] }}"
                                            data-item-shared-with="{{ $sharedUsers->pluck('user.name')->implode(', ') }}">
                                            <i class="ri-information-line me-2"></i>View info
                                        </a>
                                    </li>
                                    <li><hr class="dropdown-divider my-1"></li>
                                    <li>
                                        <a class="dropdown-item text-danger" href="javascript:void(0)"
                                            onclick="event.stopPropagation(); leaveShare(this)"
                                            data-item-type="{{ $item['type'] }}"
                                            data-item-id="{{ $item['id'] }}"
                                            data-item-name="{{ $item['name'] }}">
                                            <i class="ri-logout-box-line me-2"></i>Leave
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

<div id="gridView">
    @foreach($groupedByDate as $label => $items)
    <div class="date-label-grid">{{ $label }}</div>
    <div class="grid-wrap">
        @foreach($items as $item)
        @php $sharedUsers = $item['sharedUsers']; @endphp
        <div class="grid-card grid-item"
            data-type="{{ $item['type'] }}"
            data-id="{{ $item['id'] }}"
            data-href-folder="{{ $item['type'] === 'folder' ? route('shared-with-me.folder', $item['id']) : '' }}"
            data-href-doc="{{ $item['type'] === 'document' ? url('documents/view-document/' . $item['id']) : '' }}">
            <div class="card-top">
                @if($item['type'] === 'folder')
                <i class="ri-folder-2-fill" style="color:#e67e22;font-size:1rem;"></i>
                @else
                <i class="{{ $item['iconClass'] }}" style="color:#6b7280;font-size:1rem;"></i>
                @endif
                <div class="dropdown no-click">
                    <button class="btn btn-sm py-0 px-1 no-click"
                        style="font-size:0.75rem;color:#9ca3af;background:none;border:none;"
                        data-bs-toggle="dropdown"
                        onclick="event.stopPropagation()">
                        <i class="ri-more-2-fill"></i>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end" style="font-size:0.82rem;min-width:150px;">
                        <li>
                            <a class="dropdown-item" href="javascript:void(0)"
                                onclick="event.stopPropagation(); openInfoPanel(this)"
                                data-item-type="{{ $item['type'] }}"
                                data-item-id="{{ $item['id'] }}"
                                data-item-name="{{ $item['name'] }}"
                                data-item-owner="{{ $item['ownerName'] }}"
                                data-item-owner-color="{{ $item['ownerColor'] }}"
                                data-item-date="{{ $item['dateShared'] }}"
                                data-item-icon="{{ $item['type'] === 'folder' ? 'ri-folder-2-fill' : $item['iconClass'] }}"
                                data-item-preview="{{ $item['type'] === 'folder' ? 'folder-preview' : $item['previewClass'] }}"
                                data-item-shared-with="{{ $sharedUsers->pluck('user.name')->implode(', ') }}">
                                <i class="ri-information-line me-2"></i>View info
                            </a>
                        </li>
                        <li><hr class="dropdown-divider my-1"></li>
                        <li>
                            <a class="dropdown-item text-danger" href="javascript:void(0)"
                                onclick="event.stopPropagation(); leaveShare(this)"
                                data-item-type="{{ $item['type'] }}"
                                data-item-id="{{ $item['id'] }}"
                                data-item-name="{{ $item['name'] }}">
                                <i class="ri-logout-box-line me-2"></i>Leave
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="card-preview {{ $item['type'] === 'folder' ? 'folder-preview' : $item['previewClass'] }}">
                @if($item['type'] === 'folder')
                <i class="ri-folder-2-fill"></i>
                @else
                <i class="{{ $item['iconClass'] }}"></i>
                @endif
            </div>
            <div class="card-footer">
                <div class="card-title" title="{{ $item['name'] }}">{{ $item['name'] }}</div>
                <div class="card-meta">
                    <div class="d-flex align-items-center gap-1 mt-1">
                        <div class="user-avatar" style="background:{{ $item['ownerColor'] }};width:16px;height:16px;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:0.55rem;font-weight:700;color:#fff;flex-shrink:0;">
                            {{ strtoupper(substr($item['ownerName'], 0, 1)) }}
                        </div>
                        <span style="font-size:0.65rem;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">{{ $item['ownerName'] }}</span>
                    </div>
                    <div style="margin-top:3px;">
                        {{ $sharedUsers->count() }} user{{ $sharedUsers->count() !== 1 ? 's' : '' }}
                        &bull; {{ $item['dateShared'] }}
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

    $(document).on('click', 'tr.list-item', function (e) {
        if ($(e.target).closest('.no-click, a, button, .dropdown').length) return;
        $('tr.list-item').removeClass('row-active');
        $(this).addClass('row-active');
        showHint();
    });

    $(document).on('dblclick', 'tr.list-item', function (e) {
        if ($(e.target).closest('.no-click, a, button, .dropdown').length) return;
        var type = $(this).data('type');
        var folderUrl = $(this).data('href-folder');
        var docUrl = $(this).data('href-doc');
        if (type === 'folder' && folderUrl) {
            window.location.href = folderUrl;
        } else if (docUrl) {
            window.open(docUrl, '_blank');
        }
    });

    $(document).on('click', '.grid-item', function (e) {
        if ($(e.target).closest('.no-click, a, button, .dropdown').length) return;
        $('.grid-item').removeClass('card-selected');
        $(this).addClass('card-selected');
        showHint();
    });

    $(document).on('dblclick', '.grid-item', function (e) {
        if ($(e.target).closest('.no-click, a, button, .dropdown').length) return;
        var type = $(this).data('type');
        var folderUrl = $(this).data('href-folder');
        var docUrl = $(this).data('href-doc');
        if (type === 'folder' && folderUrl) {
            window.location.href = folderUrl;
        } else if (docUrl) {
            window.open(docUrl, '_blank');
        }
    });

    $(document).on('click', function (e) {
        if (!$(e.target).closest('tr.list-item, .grid-item').length) {
            $('tr.list-item').removeClass('row-active');
            $('.grid-item').removeClass('card-selected');
        }
    });

    $(document).on('click', '.panel-tab', function () {
        $('.panel-tab').removeClass('active');
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

    $('#panelClose, #panelOverlay').on('click', function () {
        closeInfoPanel();
    });

    $(document).on('keydown', function (e) {
        if (e.key === 'Escape') { closeInfoPanel(); }
    });
});

var panelData = {};

function openInfoPanel(el) {
    var $el = $(el);
    panelData = {
        type: $el.data('item-type'),
        id: $el.data('item-id'),
        name: $el.data('item-name'),
        owner: $el.data('item-owner'),
        ownerColor: $el.data('item-owner-color'),
        date: $el.data('item-date'),
        icon: $el.data('item-icon'),
        preview: $el.data('item-preview'),
        sharedWith: $el.data('item-shared-with') || ''
    };

    $('#panelTitle').text(panelData.name);

    var bgMap = {
        'folder-preview': '#fff8f0',
        'pdf-preview': '#fff0f0',
        'docx-preview': '#eff6ff',
        'xlsx-preview': '#f0fdf4',
        'default-preview': '#f8f9fa'
    };
    var colorMap = {
        'folder-preview': '#e67e22',
        'pdf-preview': '#dc2626',
        'docx-preview': '#2563eb',
        'xlsx-preview': '#16a34a',
        'default-preview': '#6b7280'
    };
    $('#detailIconBox').css('background', bgMap[panelData.preview] || '#f8f9fa');
    $('#detailIcon').attr('class', panelData.icon).css('color', colorMap[panelData.preview] || '#6b7280');

    var typeLabel = panelData.type === 'folder' ? 'Folder' : 'Document';
    var initials = panelData.owner ? panelData.owner.charAt(0).toUpperCase() : '?';
    var sharedWithHtml = '—';
    if (panelData.sharedWith) {
        var names = panelData.sharedWith.split(', ').filter(function (n) { return n.trim(); });
        if (names.length > 0) sharedWithHtml = names.join(', ');
    }

    $('#detailRows').html(
        '<div class="detail-row">' +
            '<div class="detail-label">Type</div>' +
            '<div class="detail-value">' + typeLabel + '</div>' +
        '</div>' +
        '<div class="detail-row">' +
            '<div class="detail-label">Owner</div>' +
            '<div class="detail-avatar-row">' +
                '<div class="user-avatar" style="background:' + panelData.ownerColor + ';width:24px;height:24px;font-size:0.72rem;">' + initials + '</div>' +
                '<div class="detail-value">' + panelData.owner + '</div>' +
            '</div>' +
        '</div>' +
        '<div class="detail-row">' +
            '<div class="detail-label">Date shared</div>' +
            '<div class="detail-value">' + panelData.date + '</div>' +
        '</div>' +
        '<div class="detail-row">' +
            '<div class="detail-label">Also shared with</div>' +
            '<div class="detail-value">' + sharedWithHtml + '</div>' +
        '</div>'
    );

    $('.panel-tab').removeClass('active');
    $('.panel-tab[data-tab="details"]').addClass('active');
    $('#tabDetails').show();
    $('#tabActivity').hide();
    $('#activityList').html(
        '<div class="text-center py-4 text-muted" style="font-size:0.82rem;">' +
        '<div class="spinner-border spinner-border-sm mb-2" role="status"></div>' +
        '<div>Loading activity...</div></div>'
    );
    $('#infoPanel').addClass('open');
    $('#panelOverlay').addClass('open');
}

function closeInfoPanel() {
    $('#infoPanel').removeClass('open');
    $('#panelOverlay').removeClass('open');
    panelData = {};
}

function loadActivity() {
    if (!panelData.id) return;
    $.ajax({
        type: 'GET',
        url: '{{ url("/documents/share-activity") }}',
        data: { type: panelData.type, id: panelData.id },
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
                html += '<div class="activity-item">' +
                    '<div class="activity-dot" style="background:' + (item.color || '#e5e7eb') + ';color:#fff;">' +
                        '<i class="' + (item.icon || 'ri-share-line') + '"></i>' +
                    '</div>' +
                    '<div class="activity-content">' +
                        '<div class="activity-text">' + item.text + '</div>' +
                        '<div class="activity-time">' + item.time + '</div>' +
                    '</div>' +
                '</div>';
            });
            $('#activityList').html(html);
        },
        error: function () {
            $('#activityList').html('<div class="text-center py-4 text-muted" style="font-size:0.82rem;">Failed to load activity.</div>');
        }
    });
}

function leaveShare(btn) {
    var $btn = $(btn);
    var itemType = $btn.data('item-type');
    var itemId = $btn.data('item-id');
    var itemName = $btn.data('item-name');

    swal({
        title: 'Leave Share?',
        text: 'Remove yourself from "' + itemName + '"? You will lose access.',
        type: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc2626',
        confirmButtonText: 'Leave',
        cancelButtonText: 'Cancel',
        closeOnConfirm: false
    }, function (confirmed) {
        if (!confirmed) return;
        var url = itemType === 'folder'
            ? '{{ url("/documents/leave-share-folder") }}'
            : '{{ url("/documents/leave-share") }}';
        var data = itemType === 'folder'
            ? { folder_id: itemId }
            : { document_id: itemId };
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