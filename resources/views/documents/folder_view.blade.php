@extends('layouts.header')

@section('css')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
{{-- <style>
    .file-dropdown-menu {
        position: absolute;
        top: 0;
        left: 100%;
        background: white;
        border-radius: 12px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        min-width: 200px;
        z-index: 9999;
        display: none;
        margin-left: 8px;
        overflow: hidden;
        animation: dropdownFadeIn 0.15s ease-out;
    }

    .file-dropdown-menu.show {
        display: block;
    }

    @keyframes dropdownFadeIn {
        from {
            opacity: 0;
            transform: translateY(-5px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .file-dropdown-item {
        padding: 12px 16px;
        display: flex;
        align-items: center;
        gap: 12px;
        cursor: pointer;
        transition: all 0.2s ease;
        border: none;
        background: none;
        width: 100%;
        text-align: left;
        font-size: 0.875rem;
        color: #212529;
        position: relative;
        font-weight: 500;
        user-select: none;
    }

    .file-dropdown-item:hover {
        background-color: #f8f9fa;
    }

    .file-dropdown-item:active {
        background-color: #e9ecef;
        transform: scale(0.98);
    }

    .file-dropdown-item i {
        width: 20px;
        text-align: center;
        transition: transform 0.2s ease;
    }

    .file-dropdown-item:hover i {
        transform: scale(1.1);
    }

    .file-dropdown-item .shortcut {
        margin-left: auto;
        font-size: 0.75rem;
        color: #6c757d;
    }

    .file-dropdown-divider {
        height: 1px;
        background-color: #dee2e6;
        margin: 4px 0;
    }

    .file-dropdown-item.submenu {
        justify-content: space-between;
    }

    .file-dropdown-item.danger {
        color: #dc3545;
    }

    .file-dropdown-item.danger:hover {
        background-color: #fee;
    }

    .folder-card {
        background: #f8f9fa;
        border: 1px solid #e9ecef;
        border-radius: 8px;
        padding: 20px;
        transition: all 0.3s;
        cursor: pointer;
        height: 100%;
    }

    .folder-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    }

    .folder-icon {
        font-size: 48px;
        color: #f59e0b;
        margin-bottom: 10px;
    }

    .folder-name {
        font-size: 15px;
        font-weight: 600;
        color: #2c3e50;
        margin: 0 0 8px 0;
    }

    .folder-info {
        display: flex;
        justify-content: space-between;
        font-size: 12px;
        color: #6c757d;
    }

    .control-label {
        font-size: 14px;
        color: #495057;
        margin: 0;
    }

    .control-input {
        padding: 8px 12px;
        border: 1px solid #dee2e6;
        border-radius: 6px;
        font-size: 14px;
        min-width: 80px;
    }
</style> --}}
@endsection

@section('content')
<div class="container-fluid py-4">
    <div class="chat-wrapper d-lg-flex gap-1 mx-n4 mt-n4 p-1">
        <div class="file-manager-sidebar minimal-border">
            <div class="p-3 d-flex flex-column h-100">
                <div class="mb-3 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-semibold">Documents</h5>
                    <button type="button" class="btn btn-sm btn-success" data-bs-toggle="dropdown">
                        <i class="ri-add-line"></i>
                        New
                    </button>
                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton1" style="">
                        <a class="dropdown-item" href="#">New file</a>
                        <a class="dropdown-item" href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#createFolderModal">New folder</a>
                    </div>
                </div>
                <div class="search-box">
                    <input type="text" class="form-control bg-light border-light"
                        placeholder="Search here...">
                    <i class="ri-search-2-line search-icon"></i>
                </div>
                <div class="mt-3 mx-n4 px-4 file-menu-sidebar-scroll" data-simplebar>
                    {{-- <ul class="list-unstyled file-manager-menu"> --}}
                    <ul class="list-unstyled file-manager-menu">
                        @foreach($document_folders->where('parent_id', null) as $folder)
                            @php
                                $isActive = request()->is('documents/folder/'.$folder->id);
                            @endphp
                            <li>
                                <div class="d-flex justify-content-between align-items-center flex-row">
                                    <a href="{{ url('/documents/folder/'.$folder->id) }}" class="fs-5 text-dark">
                                        <i class="ri-folder-2-line align-bottom me-2"></i> 
                                        <span class="file-list-link">{{ $folder->name }}</span>
                                    </a>
                                    
                                    @if(count($folder->childrenFolder) > 0 || count($folder->document) > 0)
                                    <a data-bs-toggle="collapse" href="#folder{{ $folder->id }}">
                                        <i class="ri-arrow-down-s-line fs-5 arrow-icon ms-5"></i>
                                    </a>
                                    @endif
                                </div>
                                
                                <div class="collapse @if($isActive) show @endif" id="folder{{ $folder->id }}">
                                    <ul class="sub-menu list-unstyled">
                                        @foreach ($folder->childrenFolder as $childrenFolder)
                                            @include('documents.document_subfolder', ['folder' => $childrenFolder])
                                        @endforeach

                                        @foreach ($folder->document as $document)
                                            <li>
                                                <a href="{{ url('/documents/view-document/'.$document->id) }}" target="_blank">
                                                    <i class="ri-file-list-2-line align-bottom me-2"></i> 
                                                    <span class="file-list-link">{{ $document->control_code." - ".$document->title }}</span>
                                                </a>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </li>
                        @endforeach
                        @foreach ($documents->where('folder_id', null) as $document)
                            <li>
                                <a href="{{ url('/documents/view-document/'.$document->id) }}" target="_blank">
                                    <i class="ri-file-list-2-line align-bottom me-2"></i> 
                                    <span class="file-list-link">{{ $document->control_code." - ".$document->title }}</span>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
        <div class="file-manager-content minimal-border w-100 p-3 py-0">
            <div class="mx-n3 pt-4 px-4 file-manager-content-scroll" data-simplebar>
                <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">
                    {{-- <div class="d-flex gap-2 align-items-center">
                        <select class="filter-select form-select" data-choices data-choices-search-false name="choices-single-default" id="file-type">
                            <option value="">File Type</option>
                            <option value="All" selected>All</option>
                            <option value="Video">Video</option>
                            <option value="Images">Images</option>
                            <option value="Music">Music</option>
                            <option value="Documents">Documents</option>
                        </select>
                        <button class="btn-create" data-bs-toggle="modal" data-bs-target="#createFolderModal" style="width:280px;">
                            <i class="ri-add-line"></i> Create Folders
                        </button>
                    </div> --}}
                </div>
                
                <div class="row g-3" id="folderlist-data">
                    @if(count($folder_data->childrenFolder) > 0 || count($folder_data->document) > 0)
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th></th>
                                <th>Name</th>
                                <th>File type</th>
                                <th>Modified</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($folder_data->childrenFolder as $folder)
                                <tr class="demoTableRow" data-folder-id="{{ $folder->id }}">
                                    <td>
                                        <div class="d-flex justify-content-between align-items-start">
                                            <div class="form-check mb-2">
                                                <input class="form-check-input" type="checkbox" id="formCheck1">
                                            </div>
                                            <i class="ri-folder-2-fill"></i>
                                        </div>
                                    </td>
                                    <td>
                                        <a href="{{ url('documents/folder/'.$folder->id) }}">
                                            {{ $folder->name }}
                                        </a>
                                    </td>
                                    <td>Folder</td>
                                    <td>{{ date('M d, Y', strtotime($folder->updated_at)) }}</td>
                                </tr>
                            @endforeach
                            @foreach ($folder_data->document as $document)
                                <tr>
                                    <td>
                                        <div class="d-flex justify-content-between align-items-start">
                                            <div class="form-check mb-2">
                                                <input class="form-check-input" type="checkbox" id="formCheck1">
                                            </div>
                                            <i class=" ri-file-list-line"></i>
                                        </div>
                                    </td>
                                    <td>
                                        <a href="{{ url('/documents/view-document/'.$document->id) }}"  target="_blank">
                                            {{ $document->title }}
                                        </a>
                                    </td>
                                    <td>Docx</td>
                                    <td>{{ date('M d, Y', strtotime($document->updated_at)) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    @else 
                    <div style="display: flex; justify-content:center; align-items:center; flex-direction:column; height:430px;" >
                        <i class="ri-folders-line" style="font-size:50px;"></i>
                        <p class="fs-3">No files in here</p>
                        <p>Upload some content</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@include('documents.add_folder')
@include('documents.add_documents_in_folder')
@foreach ($document_folders as $folder)
    @include('documents.rename_folder')
@endforeach
@endsection

@section('js')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="{{ asset('js/BootstrapMenu.min.js') }}"></script>

<script>
    $(document).ready(function() {
        $('.select2').select2({
            dropdownParent: $('#addDocumentInFolder'),
            theme: "classic"
        })
    })

    document.addEventListener("DOMContentLoaded", () => {
        const moreButtons = document.querySelectorAll('.file-more-btn');
    
        moreButtons.forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                
                const dropdown = this.parentElement.nextElementSibling;
                const fileCard = this.closest('.file-card');
                
                document.querySelectorAll('.file-dropdown-menu').forEach(menu => {
                    if (menu !== dropdown) {
                        menu.classList.remove('show');
                        menu.closest('.file-card')?.classList.remove('dropdown-open');
                    }
                });
                
                dropdown.classList.toggle('show');
                
                if (dropdown.classList.contains('show')) {
                    fileCard.classList.add('dropdown-open');
                } else {
                    fileCard.classList.remove('dropdown-open');
                }
            });
        });

        document.querySelectorAll('.file-dropdown-item').forEach(item => {
            item.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                
                const action = this.getAttribute('data-action');
                const actionText = this.querySelector('span').textContent.trim();
                const filePath = this.querySelector("#file").value
                
                switch(action) {
                    case 'display':
                        window.location.href = "{{ url('documents/view-document') }}/" + filePath;
                        break;
                    case 'approve':
                        // @php
                        // window.location.href = '{{ route("documents.signature") }}';
                        // @endphp
                        break;
                    case 'view':
                        window.location.href = filePath;
                        break;
                }
                
                const menu = this.closest('.file-dropdown-menu');
                menu.classList.remove('show');
                menu.closest('.file-card')?.classList.remove('dropdown-open');
            });
        });

        document.addEventListener('click', function(e) {
            if (!e.target.closest('.file-more-btn') && 
                !e.target.closest('.file-dropdown-menu')) {
                document.querySelectorAll('.file-dropdown-menu').forEach(menu => {
                    menu.classList.remove('show');
                    menu.closest('.file-card')?.classList.remove('dropdown-open');
                });
            }
        });

        document.querySelectorAll('.file-dropdown-menu').forEach(menu => {
            menu.addEventListener('click', e => e.stopPropagation());
        });

        var menu = new BootstrapMenu('.demoTableRow', {
            fetchElementData: function ($rowElem) {
                return {
                    id: $rowElem.data('folder-id')
                };
            },

            actions: {
                renameFolder: {
                    name: 'Rename folder',
                    iconClass: 'fa-pencil',
                    onClick: function (folder) {
                        // renameFolder(folder.id);
                        $("#renameFolderModal"+folder.id).modal("show")
                    }
                },
                moveFileFolder: {
                    name: 'Move file',
                    iconClass: "ri-drag-move-2-line",
                    onClick: function(folder) {
                        $("#addDocumentInFolder").modal("show")
                        $("#moveDocumentFolder").val(folder.id)
                    }
                }
            }
        });
    })
</script>
@endsection