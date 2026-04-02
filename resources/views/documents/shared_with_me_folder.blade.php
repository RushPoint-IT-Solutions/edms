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
    .folder-crumb {
        display: flex;
        align-items: center;
        flex-wrap: wrap;
        gap: 4px;
        font-size: 0.85rem;
        margin-bottom: 1rem;
    }
    .folder-crumb a { color: #0284c7; text-decoration: none; font-weight: 500; }
    .folder-crumb a:hover { text-decoration: underline; }
    .folder-crumb .sep { color: #9ca3af; }
    .folder-crumb .active-crumb { color: #1a1a2e; font-weight: 600; }
    .list-table { width: 100%; border-collapse: collapse; }
    .list-table th {
        font-size: 0.75rem;
        font-weight: 600;
        color: #6b7280;
        padding: 8px 12px;
        border-bottom: 1px solid #e5e7eb;
        text-align: left;
        white-space: nowrap;
        background: #f8f9fa;
    }
    .list-table td {
        padding: 10px 12px;
        border-bottom: 1px solid #f3f4f6;
        vertical-align: middle;
        font-size: 0.875rem;
    }
    .list-table tr.row-item { cursor: pointer; }
    .list-table tr.row-item:hover td { background: #f9fafb; }
    .list-table tr.row-item.row-active td { background: #eff6ff !important; }
    .list-table tr.row-item.row-active td:first-child { border-left: 3px solid #3b82f6; }
    .list-table tr:last-child td { border-bottom: none; }
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
        height: 100px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2.5rem;
    }
    .folder-preview { background: #fff8f0; color: #e67e22; }
    .pdf-preview { background: #fff0f0; color: #dc2626; }
    .docx-preview { background: #eff6ff; color: #2563eb; }
    .xlsx-preview { background: #f0fdf4; color: #16a34a; }
    .default-preview { background: #f8f9fa; color: #6b7280; }
    .grid-card .card-top {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 6px 8px 0;
    }
    .grid-card .card-footer { 
        padding: 6px 10px 10px; 
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

    .avatar-stack { 
        display: flex; 
        align-items: center; 
    }

    .avatar-stack .user-avatar {
        margin-left: -6px;
        border: 2px solid #fff;
        width: 24px;
        height: 24px;
        font-size: 0.65rem;
    }
    
    .avatar-stack .user-avatar:first-child { 
        margin-left: 0; 
    }

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
    .action-btn:hover { 
        background: #f3f4f6; 
        color: #1a1a2e; 
    }

    .list-table tr:hover .action-btn { 
        opacity: 1; 
    }
    
    .empty-folder { 
        text-align: center; 
        padding: 60px 20px; 
        color: #9ca3af; 
    }

    .empty-folder i { 
        font-size: 3rem; 
        margin-bottom: 1rem; 
        display: block; 
    }
    .dbl-hint { 
        font-size: 0.68rem; 
        color: #9ca3af; 
        font-style: italic; 
    }
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

    .info-panel.open { 
        right: 0; 
    }

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

    .panel-close:hover { 
        background: #f3f4f6; 
        color: #374151; 
    }

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

    .panel-tab.active { 
        color: #2563eb; 
        border-bottom-color: #2563eb; 
    }

    .panel-tab:hover:not(.active) { 
        color: #374151; 
    }

    .panel-body { 
        flex: 1; 
        overflow-y: auto; 
        padding: 16px 20px; 
    }
    
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
    .detail-row:last-child { 
        border-bottom: none; 
    }

    .detail-label { 
        font-size: 0.7rem; 
        font-weight: 600; 
        color: #9ca3af; 
        text-transform: uppercase; 
        letter-spacing: 0.04em; 
    }

    .detail-value { 
        font-size: 0.82rem; 
        color: #374151; 
        font-weight: 500; 
    }

    .detail-avatar-row { 
        display: flex; 
        align-items: center; 
        gap: 8px;
        margin-top: 4px; 
    }

    .activity-item { 
        display: flex; 
        gap: 12px; 
        padding: 12px 0; 
        border-bottom: 1px solid #f3f4f6; 
    }

    .activity-item:last-child { 
        border-bottom: none; 
    }

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

    .activity-content { 
        flex: 1; 
    }

    .activity-text { 
        font-size: 0.8rem; 
        color: #374151; 
        line-height: 1.4; 
    }

    .activity-text strong { 
        color: #1a1a2e; 
    }

    .activity-time { 
        font-size: 0.7rem;
        color: #9ca3af; 
        margin-top: 3px; 
    }

    .panel-overlay {
        display: none;
        position: fixed;
        inset: 0;
        z-index: 1040;
        background: transparent;
    }

    .panel-overlay.open { 
        display: block; 
    }
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

<div class="row mb-2">
    <div class="col-12 d-flex justify-content-between align-items-center">
        <div>
            <h4 class="mb-0">{{ $folder->name }}</h4>
            <p class="text-muted mb-0" style="font-size:0.82rem;">
                Shared with me &rsaquo; {{ $folder->name }}
                <span class="dbl-hint">&nbsp;— Click to select &bull; Double-click to open</span>
            </p>
        </div>
        <div class="d-flex gap-1">
            <button class="btn btn-light btn-sm toggle-btn" id="listViewBtn" title="List view">
                <i class="ri-list-check"></i>
            </button>
            <button class="btn btn-light btn-sm toggle-btn active" id="gridViewBtn" title="Grid view">
                <i class="ri-grid-fill"></i>
            </button>
        </div>
    </div>
</div>

<div class="folder-crumb">
    <a href="{{ route('shared-with-me') }}">
        <i class="ri-share-line me-1"></i>Shared with Me
    </a>
    @foreach($breadcrumbs as $crumb)
    <span class="sep"><i class="ri-arrow-right-s-line"></i></span>
    @if(!$loop->last)
    <a href="{{ route('shared-with-me.folder', $crumb->id) }}">{{ $crumb->name }}</a>
    @else
    <span class="active-crumb">{{ $crumb->name }}</span>
    @endif
    @endforeach
</div>

<div class="card">
    <div class="card-body p-0">
        @if($childFolders->isEmpty() && $childDocuments->isEmpty())
        <div class="empty-folder">
            <i class="ri-folder-open-line"></i>
            <p class="fw-semibold mb-1">This folder is empty</p>
            <p style="font-size:0.82rem;">No documents available in this folder.</p>
        </div>
        @else

        <div id="listView" style="display: none;">
            <table class="list-table">
                <thead>
                    <tr>
                        <th style="width:36%;">Name</th>
                        <th style="width:15%;">Owner</th>
                        <th style="width:18%;">Shared with</th>
                        <th style="width:13%;">Modified</th>
                        <th style="width:18%;"></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($childFolders as $subFolder)
                    <tr class="row-item list-item"
                        data-type="folder"
                        data-id="{{ $subFolder->id }}"
                        data-href-folder="{{ route('shared-with-me.folder', $subFolder->id) }}"
                        data-href-doc="">
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <i class="ri-folder-2-fill" style="font-size:1.2rem;color:#e67e22;flex-shrink:0;"></i>
                                <span style="font-weight:500;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;max-width:240px;" title="{{ $subFolder->name }}">
                                    {{ $subFolder->name }}
                                </span>
                                <span style="font-size:0.68rem;background:#fff3cd;color:#856404;padding:1px 7px;border-radius:10px;flex-shrink:0;">Folder</span>
                            </div>
                        </td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <div class="user-avatar" style="background:{{ $folderOwnerColor }};">
                                    {{ strtoupper(substr($folderOwnerName, 0, 1)) }}
                                </div>
                                <span style="font-size:0.82rem;white-space:nowrap;">{{ $folderOwnerName }}</span>
                            </div>
                        </td>
                        <td>
                            @php $max = 4; @endphp
                            <div class="avatar-stack" title="{{ $folderSharedUsers->pluck('user.name')->implode(', ') }}">
                                @foreach($folderSharedUsers->take($max) as $share)
                                <div class="user-avatar" style="background:{{ $share->avatarColor ?? '#9ca3af' }};" title="{{ $share->user->name ?? '' }}">
                                    {{ strtoupper(substr($share->user->name ?? '?', 0, 1)) }}
                                </div>
                                @endforeach
                                @if($folderSharedUsers->count() > $max)
                                <div class="avatar-more">+{{ $folderSharedUsers->count() - $max }}</div>
                                @endif
                                @if($folderSharedUsers->isEmpty())
                                <span style="font-size:0.75rem;color:#9ca3af;">—</span>
                                @endif
                            </div>
                        </td>
                        <td style="font-size:0.82rem;color:#6b7280;white-space:nowrap;">{{ $subFolder->updated_at->format('M d, Y') }}</td>
                        <td class="no-click" style="text-align:right;">
                            <div class="dropdown d-inline-block">
                                <button class="action-btn no-click dropdown-toggle"
                                    data-bs-toggle="dropdown"
                                    onclick="event.stopPropagation()">
                                    <i class="ri-more-2-fill"></i>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end" style="font-size:0.82rem;min-width:160px;">
                                    <li>
                                        <a class="dropdown-item" href="{{ route('shared-with-me.folder', $subFolder->id) }}"
                                            onclick="event.stopPropagation()">
                                            <i class="ri-folder-open-line me-2"></i>Open folder
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="javascript:void(0)"
                                            onclick="event.stopPropagation(); openInfoPanel(this)"
                                            data-item-type="folder"
                                            data-item-id="{{ $subFolder->id }}"
                                            data-item-name="{{ $subFolder->name }}"
                                            data-item-owner="{{ $folderOwnerName }}"
                                            data-item-owner-color="{{ $folderOwnerColor }}"
                                            data-item-date="{{ $subFolder->updated_at->format('M d, Y') }}"
                                            data-item-icon="ri-folder-2-fill"
                                            data-item-preview="folder-preview"
                                            data-item-shared-with="{{ $folderSharedWithNames }}">
                                            <i class="ri-information-line me-2"></i>View info
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </td>
                    </tr>
                    @endforeach

                    @foreach($childDocuments as $doc)
                    <tr class="row-item list-item"
                        data-type="document"
                        data-id="{{ $doc->id }}"
                        data-href-folder=""
                        data-href-doc="{{ url('documents/view-document/' . $doc->id) }}">
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <i class="{{ $doc->iconClass }}" style="font-size:1.2rem;color:#6b7280;flex-shrink:0;"></i>
                                <div style="overflow:hidden;">
                                    <div style="font-weight:500;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;max-width:220px;" title="{{ $doc->displayName }}">{{ $doc->displayName }}</div>
                                    <div style="font-size:0.72rem;color:#9ca3af;">{{ $doc->id }}</div>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <div class="user-avatar" style="background:{{ $doc->ownerColor }};">
                                    {{ strtoupper(substr($doc->ownerName, 0, 1)) }}
                                </div>
                                <span style="font-size:0.82rem;white-space:nowrap;">{{ $doc->ownerName }}</span>
                            </div>
                        </td>
                        <td>
                            @php $max = 4; @endphp
                            <div class="avatar-stack" title="{{ $folderSharedUsers->pluck('user.name')->implode(', ') }}">
                                @foreach($folderSharedUsers->take($max) as $share)
                                <div class="user-avatar" style="background:{{ $share->avatarColor ?? '#9ca3af' }};" title="{{ $share->user->name ?? '' }}">
                                    {{ strtoupper(substr($share->user->name ?? '?', 0, 1)) }}
                                </div>
                                @endforeach
                                @if($folderSharedUsers->count() > $max)
                                <div class="avatar-more">+{{ $folderSharedUsers->count() - $max }}</div>
                                @endif
                                @if($folderSharedUsers->isEmpty())
                                <span style="font-size:0.75rem;color:#9ca3af;">—</span>
                                @endif
                            </div>
                        </td>
                        <td style="font-size:0.82rem;color:#6b7280;white-space:nowrap;">{{ $doc->updated_at->format('M d, Y') }}</td>
                        <td class="no-click" style="text-align:right;">
                            <div class="dropdown d-inline-block">
                                <button class="action-btn no-click dropdown-toggle"
                                    data-bs-toggle="dropdown"
                                    onclick="event.stopPropagation()">
                                    <i class="ri-more-2-fill"></i>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end" style="font-size:0.82rem;min-width:160px;">
                                    <li>
                                        <a class="dropdown-item" href="{{ url('documents/view-document/' . $doc->id) }}"
                                            target="_blank"
                                            onclick="event.stopPropagation()">
                                            <i class="ri-eye-line me-2"></i>Open document
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="javascript:void(0)"
                                            onclick="event.stopPropagation(); openInfoPanel(this)"
                                            data-item-type="document"
                                            data-item-id="{{ $doc->id }}"
                                            data-item-name="{{ $doc->displayName }}"
                                            data-item-owner="{{ $doc->ownerName }}"
                                            data-item-owner-color="{{ $doc->ownerColor }}"
                                            data-item-date="{{ $doc->updated_at->format('M d, Y') }}"
                                            data-item-icon="{{ $doc->iconClass }}"
                                            data-item-preview="{{ $doc->previewClass }}"
                                            data-item-shared-with="{{ $folderSharedWithNames }}">
                                            <i class="ri-information-line me-2"></i>View info
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div id="gridView" style="padding:12px 16px;">

            @if($childFolders->isNotEmpty())
            <div style="margin-bottom:16px;">
                <div style="font-size:0.72rem;font-weight:600;color:#9ca3af;text-transform:uppercase;letter-spacing:0.05em;margin-bottom:8px;">Folders</div>
                <div style="display:flex;flex-wrap:wrap;gap:8px;">
                    @foreach($childFolders as $subFolder)
                    <div class="grid-card grid-item d-flex align-items-center gap-2"
                        style="padding:8px 12px;border-radius:8px;min-width:160px;max-width:220px;flex:0 0 auto;"
                        data-type="folder"
                        data-id="{{ $subFolder->id }}"
                        data-href-folder="{{ route('shared-with-me.folder', $subFolder->id) }}"
                        data-href-doc="">
                        <i class="ri-folder-2-fill" style="color:#e67e22;font-size:1.2rem;flex-shrink:0;"></i>
                        <span style="font-size:0.8rem;font-weight:600;color:#1a1a2e;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;flex:1;" title="{{ $subFolder->name }}">{{ $subFolder->name }}</span>
                        <div class="dropdown no-click">
                            <button class="btn btn-sm py-0 px-1 no-click"
                                style="font-size:0.75rem;color:#9ca3af;background:none;border:none;"
                                data-bs-toggle="dropdown"
                                onclick="event.stopPropagation()">
                                <i class="ri-more-2-fill"></i>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end" style="font-size:0.82rem;min-width:150px;">
                                <li>
                                    <a class="dropdown-item" href="{{ route('shared-with-me.folder', $subFolder->id) }}" onclick="event.stopPropagation()">
                                        <i class="ri-folder-open-line me-2"></i>Open folder
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="javascript:void(0)"
                                        onclick="event.stopPropagation(); openInfoPanel(this)"
                                        data-item-type="folder"
                                        data-item-id="{{ $subFolder->id }}"
                                        data-item-name="{{ $subFolder->name }}"
                                        data-item-owner="{{ $folderOwnerName }}"
                                        data-item-owner-color="{{ $folderOwnerColor }}"
                                        data-item-date="{{ $subFolder->updated_at->format('M d, Y') }}"
                                        data-item-icon="ri-folder-2-fill"
                                        data-item-preview="folder-preview"
                                        data-item-shared-with="{{ $folderSharedWithNames }}">
                                        <i class="ri-information-line me-2"></i>View info
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            @if($childDocuments->isNotEmpty())
            <div style="font-size:0.72rem;font-weight:600;color:#9ca3af;text-transform:uppercase;letter-spacing:0.05em;margin-bottom:8px;">Files</div>
            <div class="grid-wrap">
                @foreach($childDocuments as $doc)
                <div class="grid-card grid-item"
                    data-type="document"
                    data-id="{{ $doc->id }}"
                    data-href-folder=""
                    data-href-doc="{{ url('documents/view-document/' . $doc->id) }}">
                    <div class="card-top">
                        <i class="{{ $doc->iconClass }}" style="color:#6b7280;font-size:1rem;"></i>
                        <div class="dropdown no-click">
                            <button class="btn btn-sm py-0 px-1 no-click"
                                style="font-size:0.75rem;color:#9ca3af;background:none;border:none;"
                                data-bs-toggle="dropdown"
                                onclick="event.stopPropagation()">
                                <i class="ri-more-2-fill"></i>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end" style="font-size:0.82rem;min-width:150px;">
                                <li>
                                    <a class="dropdown-item" href="{{ url('documents/view-document/' . $doc->id) }}" target="_blank" onclick="event.stopPropagation()">
                                        <i class="ri-eye-line me-2"></i>Open document
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="javascript:void(0)"
                                        onclick="event.stopPropagation(); openInfoPanel(this)"
                                        data-item-type="document"
                                        data-item-id="{{ $doc->id }}"
                                        data-item-name="{{ $doc->displayName }}"
                                        data-item-owner="{{ $doc->ownerName }}"
                                        data-item-owner-color="{{ $doc->ownerColor }}"
                                        data-item-date="{{ $doc->updated_at->format('M d, Y') }}"
                                        data-item-icon="{{ $doc->iconClass }}"
                                        data-item-preview="{{ $doc->previewClass }}"
                                        data-item-shared-with="{{ $folderSharedWithNames }}">
                                        <i class="ri-information-line me-2"></i>View info
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="card-preview {{ $doc->previewClass }}">
                        <i class="{{ $doc->iconClass }}"></i>
                    </div>
                    <div class="card-footer">
                        <div class="card-title" title="{{ $doc->displayName }}">{{ $doc->displayName }}</div>
                        <div class="card-meta">
                            <div class="d-flex align-items-center gap-1 mt-1">
                                <div class="user-avatar" style="background:{{ $doc->ownerColor }};width:16px;height:16px;font-size:0.55rem;flex-shrink:0;">
                                    {{ strtoupper(substr($doc->ownerName, 0, 1)) }}
                                </div>
                                <span style="font-size:0.65rem;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">{{ $doc->ownerName }}</span>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            @endif

        </div>

        @endif
    </div>
</div>
@endsection

@section('js')
<script>
$(document).ready(function () {
    $('#listViewBtn').on('click', function () {
        $('#listView').show();
        $('#gridView').hide();
        $(this).addClass('active');
        $('#gridViewBtn').removeClass('active');
        localStorage.setItem('sharedFolderView', 'list');
    });
    $('#gridViewBtn').on('click', function () {
        $('#gridView').show();
        $('#listView').hide();
        $(this).addClass('active');
        $('#listViewBtn').removeClass('active');
        localStorage.setItem('sharedFolderView', 'grid');
    });

    var savedView = localStorage.getItem('sharedFolderView');
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
        owner: $el.data('item-owner') || '—',
        ownerColor: $el.data('item-owner-color') || '#9ca3af',
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
            '<div class="detail-label">Parent folder</div>' +
            '<div class="detail-value">{{ $folder->name }}</div>' +
        '</div>' +
        '<div class="detail-row">' +
            '<div class="detail-label">Owner</div>' +
            '<div class="detail-avatar-row">' +
                '<div class="user-avatar" style="background:' + panelData.ownerColor + ';width:24px;height:24px;font-size:0.72rem;">' + initials + '</div>' +
                '<div class="detail-value">' + panelData.owner + '</div>' +
            '</div>' +
        '</div>' +
        '<div class="detail-row">' +
            '<div class="detail-label">Last modified</div>' +
            '<div class="detail-value">' + panelData.date + '</div>' +
        '</div>' +
        (sharedWithHtml !== '—'
            ? '<div class="detail-row">' +
                  '<div class="detail-label">Also shared with</div>' +
                  '<div class="detail-value">' + sharedWithHtml + '</div>' +
              '</div>'
            : '')
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
</script>
@endsection