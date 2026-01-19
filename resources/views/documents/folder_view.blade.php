@extends('layouts.header')

@section('css')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<style>
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
</style>
@endsection

@section('content')
<div class="container-fluid">
    <div class="chat-wrapper d-lg-flex gap-1 mx-n4 mt-n4 p-1">
        <div class="file-manager-sidebar minimal-border">
            <div class="p-3 d-flex flex-column h-100">
                <div class="mb-3">
                    <h5 class="mb-0 fw-semibold">Documents</h5>
                </div>
                <div class="search-box">
                    <input type="text" class="form-control bg-light border-light"
                        placeholder="Search here...">
                    <i class="ri-search-2-line search-icon"></i>
                </div>
                <div class="mt-3 mx-n4 px-4 file-menu-sidebar-scroll" data-simplebar>
                    <ul class="list-unstyled file-manager-menu">
                        @foreach($document_folders->where('parent_id', null) as $folder)
                            @if(count($folder->childrenFolder) > 0)
                                <li>
                                    <a data-bs-toggle="collapse" href="#folder{{ $folder->id }}" role="button" aria-expanded="true" aria-controls="folder{{ $folder->id }}">
                                        <i class="ri-folder-2-line align-bottom me-2"></i> 
                                        <span class="file-list-link">{{ $folder->name }}</span>
                                    </a>
                                    <div class="collapse" id="folder{{ $folder->id }}">
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
                            @endif
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
            <div class="card shadow-sm">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <a href="{{ url('documents') }}" class="btn btn-danger">
                        <i class="ri-arrow-left-line"></i>
                        Back
                    </a>

                    <div>
                        <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#createFolderModal">
                            <i class="ri-add-line"></i>
                            Add folder
                        </button>
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addDocumentInFolder">
                            <i class="ri-add-line"></i>
                            Add documents
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-12">
                            <ol class="breadcrumb p-3 py-2 bg-white">
                                <li class="breadcrumb-item active" aria-current="page">Document</li>
                                <li class="breadcrumb-item active" aria-current="page">{{ $folder_data->name }}</li>
                            </ol>
                        </div>
                    </div>
                    <div class="row row-cols-3 row-cols-sm-6 row-cols-md-4 row-cols-xl-5 g-3">
                        @foreach ($folder_data->childrenFolder as $childrenFolder)
                            <div class="col-12 col-sm-6 col-md-4 col-lg-3">
                                <div class="folder-card">
                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                        <input class="form-check-input" type="checkbox">
                                        <div class="dropdown">
                                            <button class="btn btn-sm" type="button" data-bs-toggle="dropdown">
                                                <i class="ri-more-2-fill"></i>
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-end">
                                                <li><a class="dropdown-item" href="{{ url('documents/folder/'.$childrenFolder->id) }}">Open</a></li>
                                                <li><a class="dropdown-item" href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#renameFolderModal{{ $childrenFolder->id }}">Rename</a></li>
                                                <li>
                                                    <form action="{{ url('documents/delete-folder/'.$childrenFolder->id) }}" method="POST">
                                                        @csrf
                                                        <button class="dropdown-item text-danger" type="submit">Delete</button>
                                                    </form>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>

                                    <div class="text-center">
                                        <div class="folder-icon">
                                            <i class="ri-folder-2-fill"></i>
                                        </div>
                                        <h6 class="folder-name">{{ $childrenFolder->name }}</h6>
                                        <div class="folder-info">
                                            <span><b>{{ count($childrenFolder->document) }}</b> Files</span>
                                            <span><b>0</b>GB</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                        @foreach ($folder_data->document as $document)
                        <div class="col">
                            <div class="card border file-card position-relative">
                                <div class="position-absolute top-0 end-0 m-2 more-btn">
                                    <button class="btn btn-sm btn-light p-1 file-more-btn"
                                        style="width: 28px; height: 28px; line-height: 1; border-radius: 6px;">
                                        <i class="ri-more-2-fill"></i>
                                    </button>
                                </div>

                                <div class="file-dropdown-menu">
                                    <button class="file-dropdown-item" data-action="display">
                                        <i class="ri-file-text-line"></i>
                                        <input type="hidden" id="file" value="{{ $document->id }}" />
                                        <span>View</span>
                                    </button>
                                </div>

                                @foreach ($document->attachments->where('type','pdf_copy') as $file)
                                <a href='{{ url($file->attachment) }}' class="text-decoration-none" target="_blank">
                                    <iframe
                                        src="https://docs.google.com/gview?url={{ urlencode(asset($file->attachment)) }}&embedded=true"
                                        loading="lazy" class="card-img-top" style="height: 100%;" scrolling="no"
                                        frameborder="0"></iframe>
                                    <div class="card-body p-2 text-start">
                                        <div class="docu d-flex align-items-center gap-2">
                                            <i class="ri-file-pdf-line text-danger" style="font-size: 1rem;"></i>

                                            <div class="fw-semibold text-dark text-truncate" style="font-size: 0.75rem;">
                                                {{ $document->control_code }} <br>
                                                {{ $document->title }}
                                            </div>
                                        </div>
                                    </div>
                                </a>
                                @endforeach

                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


{{-- <div class="row">
    <div class="col-lg-12">
        <ol class="breadcrumb p-3 py-2 bg-light">
            <li class="breadcrumb-item active" aria-current="page">Document</li>
            <li class="breadcrumb-item active" aria-current="page">{{ $folder_data->name }}</li>
        </ol>
        <div class="card shadow-sm">
            <div class="card-header d-flex justify-content-between align-items-center">
                <a href="{{ url('documents') }}" class="btn btn-danger">
                    <i class="ri-arrow-left-line"></i>
                    Back
                </a>

                <div>
                    <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#createFolderModal">
                        <i class="ri-add-line"></i>
                        Add folder
                    </button>
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addDocumentInFolder">
                        <i class="ri-add-line"></i>
                        Add documents
                    </button>
                </div>
            </div>
            <div class="card-body">
                <div class="row row-cols-3 row-cols-sm-6 row-cols-md-4 row-cols-xl-5 g-3">
                    @foreach ($folder_data->childrenFolder as $childrenFolder)
                        <div class="col-12 col-sm-6 col-md-4 col-lg-3">
                            <div class="folder-card">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <input class="form-check-input" type="checkbox">
                                    <div class="dropdown">
                                        <button class="btn btn-sm" type="button" data-bs-toggle="dropdown">
                                            <i class="ri-more-2-fill"></i>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end">
                                            <li><a class="dropdown-item" href="{{ url('documents/folder/'.$childrenFolder->id) }}">Open</a></li>
                                            <li><a class="dropdown-item" href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#renameFolderModal{{ $childrenFolder->id }}">Rename</a></li>
                                            <li>
                                                <form action="{{ url('documents/delete-folder/'.$childrenFolder->id) }}" method="POST">
                                                    @csrf
                                                    <button class="dropdown-item text-danger" type="submit">Delete</button>
                                                </form>
                                            </li>
                                        </ul>
                                    </div>
                                </div>

                                <div class="text-center">
                                    <div class="folder-icon">
                                        <i class="ri-folder-2-fill"></i>
                                    </div>
                                    <h6 class="folder-name">{{ $childrenFolder->name }}</h6>
                                    <div class="folder-info">
                                        <span><b>{{ count($childrenFolder->document) }}</b> Files</span>
                                        <span><b>0</b>GB</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                    @foreach ($folder_data->document as $document)
                    <div class="col">
                        <div class="card border file-card position-relative">
                            <div class="position-absolute top-0 end-0 m-2 more-btn">
                                <button class="btn btn-sm btn-light p-1 file-more-btn"
                                    style="width: 28px; height: 28px; line-height: 1; border-radius: 6px;">
                                    <i class="ri-more-2-fill"></i>
                                </button>
                            </div>

                            <div class="file-dropdown-menu">
                                <button class="file-dropdown-item" data-action="display">
                                    <i class="ri-file-text-line"></i>
                                    <input type="hidden" id="file" value="{{ $document->id }}" />
                                    <span>View</span>
                                </button>
                            </div>

                            @foreach ($document->attachments->where('type','pdf_copy') as $file)
                            <a href='{{ url($file->attachment) }}' class="text-decoration-none" target="_blank">
                                <iframe
                                    src="https://docs.google.com/gview?url={{ urlencode(asset($file->attachment)) }}&embedded=true"
                                    loading="lazy" class="card-img-top" style="height: 100%;" scrolling="no"
                                    frameborder="0"></iframe>
                                <div class="card-body p-2 text-start">
                                    <div class="docu d-flex align-items-center gap-2">
                                        <i class="ri-file-pdf-line text-danger" style="font-size: 1rem;"></i>

                                        <div class="fw-semibold text-dark text-truncate" style="font-size: 0.75rem;">
                                            {{ $document->control_code }} <br>
                                            {{ $document->title }}
                                        </div>
                                    </div>
                                </div>
                            </a>
                            @endforeach

                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div> --}}

@include('documents.add_folder')
@include('documents.add_documents_in_folder')
@endsection

@section('js')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
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
    })
</script>
@endsection