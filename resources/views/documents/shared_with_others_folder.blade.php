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
        transition: box-shadow 0.2s, border-color 0.2s;
    }

    .itemCard:hover {
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        border-color: #cbd5e1;
    }

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

    .actionMenuBtn:hover {
        background: #f3f4f6;
        color: #1a1a2e;
    }

    .itemsTable tr:hover .actionMenuBtn {
        opacity: 1;
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

    .infoPanel.open {
        right: 0;
    }

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

    .infoPanelClose:hover {
        background: #f3f4f6;
        color: #374151;
    }

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

    .infoPanelTab:hover:not(.active) {
        color: #374151;
    }

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

    .detailRow:last-child {
        border-bottom: none;
    }

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

    .activityItem:last-child {
        border-bottom: none;
    }

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

    .activityContent {
        flex: 1;
    }

    .activityText {
        font-size: 0.8rem;
        color: #374151;
        line-height: 1.4;
    }

    .activityText strong {
        color: #1a1a2e;
    }

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

    .infoPanelOverlay.open {
        display: block;
    }
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
                <div class="miniAvatar" style="background:{{ $share->avatarColor }};width:22px;height:22px;font-size:0.62rem;margin-left:-4px;" title="{{ $share->user->name ?? '—' }}">
                    {{ strtoupper(substr($share->user->name ?? '?', 0, 1)) }}
                </div>
                @endforeach
                <span style="font-size:0.78rem;font-weight:600;color:#1a1a2e;">
                    {{ $sharedUsers->count() }} user{{ $sharedUsers->count() !== 1 ? 's' : '' }}
                </span>
            </div>
            @endif
            <button class="toggleBtn active" id="listViewBtn" title="List view"><i class="ri-list-check"></i></button>
            <button class="toggleBtn" id="gridViewBtn" title="Grid view"><i class="ri-grid-fill"></i></button>
        </div>
    </div>
</div>

<div class="pathCrumb">
    <a href="{{ route('shared-with-others') }}"><i class="ri-user-shared-line me-1"></i>Shared with Others</a>
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
            <span style="font-size:0.78rem;font-weight:600;color:#6b7280;"><i class="ri-group-line me-1"></i>People with access:</span>
            @foreach($sharedUsers as $share)
            <span class="badge d-inline-flex align-items-center gap-1"
                style="background:#f0f4ff;color:#1a1a2e;border:1px solid #d0d9f0;font-size:0.72rem;padding:4px 8px;">
                <div class="miniAvatar" style="background:{{ $share->avatarColor }};width:18px;height:18px;font-size:0.55rem;">
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

        <div id="listView" style="display:none;">
            <table class="itemsTable">
                <thead>
                    <tr>
                        <th style="width:34%;">Name</th>
                        <th style="width:14%;">Owner</th>
                        <th style="width:22%;">Shared with</th>
                        <th style="width:13%;">Modified</th>
                        <th style="width:17%;"></th>
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
                                <i class="ri-folder-2-fill" style="font-size:1.2rem;color:#e67e22;flex-shrink:0;"></i>
                                <span style="font-weight:500;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;max-width:200px;" title="{{ $subFolder->name }}">{{ $subFolder->name }}</span>
                                <span style="font-size:0.68rem;background:#fff3cd;color:#856404;padding:1px 7px;border-radius:10px;flex-shrink:0;">Folder</span>
                            </div>
                        </td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <div class="userAvatar" style="background:{{ $ownerColor }};">{{ strtoupper(substr($ownerName, 0, 1)) }}</div>
                                <span style="font-size:0.82rem;white-space:nowrap;">{{ $ownerName }}</span>
                            </div>
                        </td>
                        <td class="noClick">
                            <div class="d-flex flex-wrap gap-1">
                                @foreach($sharedUsers as $share)
                                <span class="badge" style="background:#f0f4ff;color:#1a1a2e;border:1px solid #d0d9f0;font-size:0.68rem;">
                                    {{ $share->user->name ?? '—' }}
                                </span>
                                @endforeach
                                @if($sharedUsers->isEmpty())<span style="font-size:0.75rem;color:#9ca3af;">—</span>@endif
                            </div>
                        </td>
                        <td style="color:#6b7280;font-size:0.82rem;white-space:nowrap;">{{ $subFolder->updated_at->format('M d, Y') }}</td>
                        <td class="noClick" style="text-align:right;">
                            <div class="dropdown d-inline-block">
                                <button class="actionMenuBtn noClick dropdown-toggle" data-bs-toggle="dropdown" onclick="event.stopPropagation()">
                                    <i class="ri-more-2-fill"></i>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end" style="font-size:0.82rem;min-width:160px;">
                                    <li>
                                        <a class="dropdown-item" href="{{ route('shared-with-others.folder', $subFolder->id) }}" onclick="event.stopPropagation()">
                                            <i class="ri-folder-open-line me-2"></i>Open folder
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="javascript:void(0)"
                                            onclick="event.stopPropagation(); openInfoPanel(this)"
                                            data-item-type="folder"
                                            data-item-id="{{ $subFolder->id }}"
                                            data-item-name="{{ $subFolder->name }}"
                                            data-item-owner="{{ $ownerName }}"
                                            data-item-owner-color="{{ $ownerColor }}"
                                            data-item-date="{{ $subFolder->updated_at->format('M d, Y') }}"
                                            data-item-icon="ri-folder-2-fill"
                                            data-item-preview="folder-preview"
                                            data-item-shared-with="{{ $sharedWithNames }}">
                                            <i class="ri-information-line me-2"></i>View info
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </td>
                    </tr>
                    @endforeach

                    @foreach($childDocuments as $doc)
                    @php
                        $docSharedUsers     = $doc->share_document;
                        $docSharedWithNames = $docSharedUsers->pluck('user.name')->filter()->implode(', ');
                    @endphp
                    <tr class="rowClick listItem"
                        data-type="document"
                        data-href-folder=""
                        data-href-doc="{{ url('documents/view-document/' . $doc->id) }}">
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <i class="{{ $doc->iconClass }}" style="font-size:1.2rem;color:#6b7280;flex-shrink:0;"></i>
                                <div style="overflow:hidden;">
                                    <div style="font-weight:500;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;max-width:180px;" title="{{ $doc->control_code }} - {{ $doc->title }}">{{ $doc->title }}</div>
                                    <div style="font-size:0.72rem;color:#9ca3af;">{{ $doc->control_code }}</div>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <div class="userAvatar" style="background:{{ $ownerColor }};">{{ strtoupper(substr($ownerName, 0, 1)) }}</div>
                                <span style="font-size:0.82rem;white-space:nowrap;">{{ $ownerName }}</span>
                            </div>
                        </td>
                        <td class="noClick">
                            <div class="d-flex flex-wrap gap-1">
                                @foreach($docSharedUsers as $share)
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
                        <td style="color:#6b7280;font-size:0.82rem;white-space:nowrap;">{{ $doc->updated_at->format('M d, Y') }}</td>
                        <td class="noClick" style="text-align:right;">
                            <div class="dropdown d-inline-block">
                                <button class="actionMenuBtn noClick dropdown-toggle" data-bs-toggle="dropdown" onclick="event.stopPropagation()">
                                    <i class="ri-more-2-fill"></i>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end" style="font-size:0.82rem;min-width:160px;">
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
                                            data-item-name="{{ $doc->control_code }} - {{ $doc->title }}"
                                            data-item-owner="{{ $ownerName }}"
                                            data-item-owner-color="{{ $ownerColor }}"
                                            data-item-date="{{ $doc->updated_at->format('M d, Y') }}"
                                            data-item-icon="{{ $doc->iconClass }}"
                                            data-item-preview="{{ $doc->previewClass }}"
                                            data-item-shared-with="{{ $docSharedWithNames }}">
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
                    <div class="itemCard gridItem d-flex align-items-center gap-2"
                        style="padding:8px 12px;border-radius:8px;min-width:160px;max-width:220px;flex:0 0 auto;"
                        data-type="folder"
                        data-href-folder="{{ route('shared-with-others.folder', $subFolder->id) }}"
                        data-href-doc="">
                        <i class="ri-folder-2-fill" style="color:#e67e22;font-size:1.2rem;flex-shrink:0;"></i>
                        <span style="font-size:0.8rem;font-weight:600;color:#1a1a2e;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;flex:1;" title="{{ $subFolder->name }}">{{ $subFolder->name }}</span>
                        <div class="dropdown noClick">
                            <button class="btn btn-sm py-0 px-1 noClick"
                                style="font-size:0.75rem;color:#9ca3af;background:none;border:none;"
                                data-bs-toggle="dropdown"
                                onclick="event.stopPropagation()">
                                <i class="ri-more-2-fill"></i>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end" style="font-size:0.82rem;min-width:150px;">
                                <li>
                                    <a class="dropdown-item" href="{{ route('shared-with-others.folder', $subFolder->id) }}" onclick="event.stopPropagation()">
                                        <i class="ri-folder-open-line me-2"></i>Open folder
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="javascript:void(0)"
                                        onclick="event.stopPropagation(); openInfoPanel(this)"
                                        data-item-type="folder"
                                        data-item-id="{{ $subFolder->id }}"
                                        data-item-name="{{ $subFolder->name }}"
                                        data-item-owner="{{ $ownerName }}"
                                        data-item-owner-color="{{ $ownerColor }}"
                                        data-item-date="{{ $subFolder->updated_at->format('M d, Y') }}"
                                        data-item-icon="ri-folder-2-fill"
                                        data-item-preview="folder-preview"
                                        data-item-shared-with="{{ $sharedWithNames }}">
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
            <div class="itemsGrid">
                @foreach($childDocuments as $doc)
                @php $docSharedWithNames = $doc->share_document->pluck('user.name')->filter()->implode(', '); @endphp
                <div class="itemCard gridItem"
                    data-type="document"
                    data-href-folder=""
                    data-href-doc="{{ url('documents/view-document/' . $doc->id) }}">
                    <div class="cardTop">
                        <i class="{{ $doc->iconClass }}" style="color:#6b7280;font-size:1rem;"></i>
                        <div class="dropdown noClick">
                            <button class="btn btn-sm py-0 px-1 noClick"
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
                                        data-item-name="{{ $doc->control_code }} - {{ $doc->title }}"
                                        data-item-owner="{{ $ownerName }}"
                                        data-item-owner-color="{{ $ownerColor }}"
                                        data-item-date="{{ $doc->updated_at->format('M d, Y') }}"
                                        data-item-icon="{{ $doc->iconClass }}"
                                        data-item-preview="{{ $doc->previewClass }}"
                                        data-item-shared-with="{{ $docSharedWithNames }}">
                                        <i class="ri-information-line me-2"></i>View info
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="previewArea {{ $doc->previewClass }}">
                        <i class="{{ $doc->iconClass }}"></i>
                    </div>
                    <div class="cardBottom">
                        <div class="cardName" title="{{ $doc->control_code }} - {{ $doc->title }}">{{ $doc->control_code }} - {{ $doc->title }}</div>
                        <div class="cardMeta">
                            <div class="d-flex align-items-center gap-1 mt-1">
                                <div style="background:{{ $ownerColor }};width:16px;height:16px;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:0.55rem;font-weight:700;color:#fff;flex-shrink:0;">
                                    {{ strtoupper(substr($ownerName, 0, 1)) }}
                                </div>
                                <span style="font-size:0.65rem;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">{{ $ownerName }}</span>
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
<script src="{{ asset('login_css/js/plugins/sweetalert/sweetalert.min.js') }}"></script>
<script>
$(document).ready(function () {

    $('#listViewBtn').on('click', function () {
        $('#listView').show();
        $('#gridView').hide();
        $(this).addClass('active');
        $('#gridViewBtn').removeClass('active');
        localStorage.setItem('sharedOthersFolderView', 'list');
    });
    $('#gridViewBtn').on('click', function () {
        $('#gridView').show();
        $('#listView').hide();
        $(this).addClass('active');
        $('#listViewBtn').removeClass('active');
        localStorage.setItem('sharedOthersFolderView', 'grid');
    });
    if (localStorage.getItem('sharedOthersFolderView') !== 'list') {
        $('#gridViewBtn').trigger('click');
    }

    var hintTimer = null;
    function showHint() {
        clearTimeout(hintTimer);
        $('#floatHint').fadeIn(150);
        hintTimer = setTimeout(function () { $('#floatHint').fadeOut(300); }, 2500);
    }

    $(document).on('click', 'tr.listItem', function (e) {
        if ($(e.target).closest('.noClick, .revokeDoc, .revokeFolder, a, button, .dropdown').length) return;
        $('tr.listItem').removeClass('rowActive');
        $(this).addClass('rowActive');
        showHint();
    });

    $(document).on('dblclick', 'tr.listItem', function (e) {
        if ($(e.target).closest('.noClick, .revokeDoc, .revokeFolder, a, button, .dropdown').length) return;
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
        if ($(e.target).closest('.noClick, .revokeDoc, .revokeFolder, a, button, .dropdown').length) return;
        $('.gridItem').removeClass('cardSelected');
        $(this).addClass('cardSelected');
        showHint();
    });

    $(document).on('dblclick', '.gridItem', function (e) {
        if ($(e.target).closest('.noClick, .revokeDoc, .revokeFolder, a, button, .dropdown').length) return;
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

var currentPanelData = {};

function openInfoPanel(el) {
    var $el = $(el);

    currentPanelData = {
        type : $el.data('item-type'),
        id : $el.data('item-id'),
        name : $el.data('item-name'),
        owner : $el.data('item-owner') || '—',
        ownerColor : $el.data('item-owner-color') || '#9ca3af',
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
            '<div class="detailLabel">Parent folder</div>' +
            '<div class="detailValue">{{ $folder->name }}</div>' +
        '</div>' +
        '<div class="detailRow">' +
            '<div class="detailLabel">Owner</div>' +
            '<div class="detailAvatarRow">' +
                '<div class="userAvatar" style="background:' + currentPanelData.ownerColor + ';width:24px;height:24px;font-size:0.72rem;">' + initials + '</div>' +
                '<div class="detailValue">' + currentPanelData.owner + '</div>' +
            '</div>' +
        '</div>' +
        '<div class="detailRow">' +
            '<div class="detailLabel">Last modified</div>' +
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
                html +=
                    '<div class="activityItem">' +
                        '<div class="activityDot" style="background:' + (item.color || '#e5e7eb') + ';color:#fff;">' +
                            '<i class="' + (item.icon || 'ri-share-line') + '"></i>' +
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