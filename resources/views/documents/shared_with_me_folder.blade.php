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

    .folderCrumb {
        display: flex;
        align-items: center;
        flex-wrap: wrap;
        gap: 4px;
        font-size: 0.85rem;
        margin-bottom: 1rem;
    }

    .folderCrumb a {
        color: #0284c7;
        text-decoration: none;
        font-weight: 500;
    }

    .folderCrumb a:hover {
        text-decoration: underline;
    }

    .folderCrumb .sep {
        color: #9ca3af;
    }

    .folderCrumb .activeCrumb {
        color: #1a1a2e;
        font-weight: 600;
    }

    .fileListTable {
        width: 100%;
        border-collapse: collapse;
    }

    .fileListTable th {
        font-size: 0.75rem;
        font-weight: 600;
        color: #6b7280;
        padding: 8px 12px;
        border-bottom: 1px solid #e5e7eb;
        text-align: left;
        white-space: nowrap;
        background: #f8f9fa;
    }

    .fileListTable td {
        padding: 10px 12px;
        border-bottom: 1px solid #f3f4f6;
        vertical-align: middle;
        font-size: 0.875rem;
    }

    .fileListTable tr.rowClick {
        cursor: pointer;
    }

    .fileListTable tr.rowClick:hover td {
        background: #f9fafb;
    }

    .fileListTable tr.rowClick.rowActive td {
        background: #eff6ff !important;
    }

    .fileListTable tr.rowClick.rowActive td:first-child {
        border-left: 3px solid #3b82f6;
    }

    .fileListTable tr:last-child td {
        border-bottom: none;
    }

    .fileGridWrapper {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(160px, 1fr));
        gap: 12px;
        padding: 12px 0;
    }

    .fileGridCard {
        border: 1px solid #e5e7eb;
        border-radius: 10px;
        overflow: hidden;
        cursor: pointer;
        transition: box-shadow 0.2s, border-color 0.2s;
        background: #fff;
        position: relative;
    }

    .fileGridCard:hover {
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        border-color: #cbd5e1;
    }

    .fileGridCard.cardSelected {
        border-color: #3b82f6 !important;
        background: #eff6ff !important;
        box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.25) !important;
    }

    .fileGridCard .previewArea {
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

    .fileGridCard .cardHeader {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 6px 8px 0;
    }

    .fileGridCard .cardFooter {
        padding: 6px 10px 10px;
        border-top: 1px solid #f0f0f0;
    }

    .fileGridCard .cardName {
        font-size: 0.78rem;
        font-weight: 600;
        color: #1a1a2e;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .fileGridCard .cardSub {
        font-size: 0.68rem;
        color: #9ca3af;
        margin-top: 2px;
    }

    .emptyMsg {
        text-align: center;
        padding: 60px 20px;
        color: #9ca3af;
    }

    .emptyMsg i {
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
                Shared with me &rsaquo; {{ $folder->name }}
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

<div class="folderCrumb">
    <a href="{{ route('shared-with-me') }}">
        <i class="ri-share-line me-1"></i>Shared with Me
    </a>
    @foreach($breadcrumbs as $crumb)
        <span class="sep"><i class="ri-arrow-right-s-line"></i></span>
        @if(!$loop->last)
            <a href="{{ route('shared-with-me.folder', $crumb->id) }}">{{ $crumb->name }}</a>
        @else
            <span class="activeCrumb">{{ $crumb->name }}</span>
        @endif
    @endforeach
</div>

<div class="card">
    <div class="card-body p-0">

        @if($childFolders->isEmpty() && $childDocuments->isEmpty())
            <div class="emptyMsg">
                <i class="ri-folder-open-line"></i>
                <p class="fw-semibold mb-1">This folder is empty</p>
                <p style="font-size:0.82rem;">No documents available in this folder.</p>
            </div>
        @else

        <div id="listView">
            <table class="fileListTable">
                <thead>
                    <tr>
                        <th style="width:50%;">Name</th>
                        <th>Type</th>
                        <th>Modified</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($childFolders as $subFolder)
                    <tr class="rowClick listItem"
                        data-type="folder"
                        data-href-folder="{{ route('shared-with-me.folder', $subFolder->id) }}"
                        data-href-doc="">
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <i class="ri-folder-2-fill" style="font-size:1.2rem;color:#e67e22;"></i>
                                <span>{{ $subFolder->name }}</span>
                            </div>
                        </td>
                        <td><span class="badge bg-warning text-dark" style="font-size:0.68rem;">Folder</span></td>
                        <td style="color:#6b7280;">{{ $subFolder->updated_at->format('M d, Y') }}</td>
                        <td></td>
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
                        <td>
                            <span class="badge bg-secondary" style="font-size:0.68rem;">
                                {{ strtoupper($doc->fileType) }}
                            </span>
                        </td>
                        <td style="color:#6b7280;">{{ $doc->updated_at->format('M d, Y') }}</td>
                        <td class="noClick">
                            <a href="{{ url('documents/view-document/' . $doc->id) }}"
                               target="_blank"
                               class="btn btn-sm btn-outline-primary py-0 px-2"
                               title="View document">
                                <i class="ri-eye-line"></i>
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div id="gridView" style="display:none;padding:12px 16px;">
            <div class="fileGridWrapper">

                @foreach($childFolders as $subFolder)
                <div class="fileGridCard gridItem"
                    data-type="folder"
                    data-href-folder="{{ route('shared-with-me.folder', $subFolder->id) }}"
                    data-href-doc="">
                    <div class="cardHeader">
                        <i class="ri-folder-2-fill" style="color:#e67e22;font-size:1rem;"></i>
                    </div>
                    <div class="previewArea folder-preview">
                        <i class="ri-folder-2-fill"></i>
                    </div>
                    <div class="cardFooter">
                        <div class="cardName">{{ $subFolder->name }}</div>
                        <div class="cardSub">{{ $subFolder->updated_at->format('M d, Y') }}</div>
                    </div>
                </div>
                @endforeach

                @foreach($childDocuments as $doc)
                <div class="fileGridCard gridItem"
                    data-type="document"
                    data-href-folder=""
                    data-href-doc="{{ url('documents/view-document/' . $doc->id) }}">
                    <div class="cardHeader">
                        <i class="{{ $doc->iconClass }}" style="color:#6b7280;font-size:1rem;"></i>
                    </div>
                    <div class="previewArea {{ $doc->previewClass }}">
                        <i class="{{ $doc->iconClass }}"></i>
                    </div>
                    <div class="cardFooter">
                        <div class="cardName">{{ $doc->control_code }} - {{ $doc->title }}</div>
                        <div class="cardSub">
                            <span class="badge bg-secondary" style="font-size:0.62rem;">
                                {{ strtoupper($doc->fileType) }}
                            </span>
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
    if (localStorage.getItem('sharedFolderView') === 'grid') {
        $('#gridViewBtn').trigger('click');
    }

    var hintTimer = null;
    function showHint() {
        clearTimeout(hintTimer);
        $('#floatHint').fadeIn(150);
        hintTimer = setTimeout(function () { $('#floatHint').fadeOut(300); }, 2500);
    }

    $(document).on('click', 'tr.listItem', function (e) {
        if ($(e.target).closest('.noClick, a, button').length) return;
        $('tr.listItem').removeClass('rowActive');
        $(this).addClass('rowActive');
        showHint();
    });

    $(document).on('dblclick', 'tr.listItem', function (e) {
        if ($(e.target).closest('.noClick, a, button').length) return;
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
        if ($(e.target).closest('.noClick, a, button').length) return;
        $('.gridItem').removeClass('cardSelected');
        $(this).addClass('cardSelected');
        showHint();
    });

    $(document).on('dblclick', '.gridItem', function (e) {
        if ($(e.target).closest('.noClick, a, button').length) return;
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
</script>
@endsection