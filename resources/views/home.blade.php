@extends('layouts.header')

@section('css')
{{-- <link href="{{ asset('login_css/css/plugins/c3/c3.min.css') }}" rel="stylesheet">
<link href="{{ asset('login_css/css/plugins/morris/morris-0.4.3.min.css') }}" rel="stylesheet"> --}}
<style>
    .file-card {
        position: relative;
        z-index: 1;
    }
    .file-card.dropdown-open {
        z-index: 9999;
    }
    .file-card:hover {
        box-shadow: 0 0.5rem 1rem rgba(0,0,0,0.15);
        transform: translateY(-2px);
    }
    .file-card .more-btn {
        opacity: 0;
        transition: opacity 0.3s ease;
    }
    .file-card:hover .more-btn,
    .file-card.dropdown-open .more-btn {
        opacity: 1;
    }

    .file-dropdown-menu {
        position: absolute;
        top: 100%;
        left: 20%;
        background: white;
        border-radius: 12px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        min-width: 200px;
        z-index: 9999;
        display: none;
        margin-top: 5px;
        overflow: hidden;
    }
    .file-dropdown-menu.show {
        display: block;
    }
    
    .file-dropdown-item {
        padding: 12px 16px;
        display: flex;
        align-items: center;
        gap: 12px;
        cursor: pointer;
        transition: background-color 0.2s;
        border: none;
        background: none;
        width: 100%;
        text-align: left;
        font-size: 0.875rem;
        color: #212529;
        position: relative;
    }
    .file-dropdown-item:hover {
        background-color: #f8f9fa;
    }
    .file-dropdown-item i {
        font-size: 1.25rem;
        width: 20px;
        text-align: center;
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

    .file-submenu {
        position: absolute;
        left: 100%;
        top: 0;
        background: white;
        border-radius: 12px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        min-width: 200px;
        z-index: 10000;
        display: none;
        margin-left: 5px;
        overflow: hidden;
    }
    .file-submenu.show {
        display: block;
    }
    .file-dropdown-item.submenu:hover .file-submenu {
        display: block;
    }

    .file-preview-menu {
        position: absolute;
        top: 100%;
        left: 20%;
        background: white;
        border-radius: 12px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        min-width: 200px;
        z-index: 9999;
        display: none;
        margin-top: 5px;
        overflow: hidden;
    }
    .file-preview-menu.show {
        display: block;
    }

    .file-share-menu {
        position: absolute;
        top: 100%;
        left: 20%;
        background: white;
        border-radius: 12px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        min-width: 200px;
        z-index: 9999;
        display: none;
        margin-top: 5px;
        overflow: hidden;
    }
    .file-share-menu.show {
        display: block;
    }
</style>
@endsection

@section('content')
<div class="mb-4">
    <h4 class="fs-2 fw-semibold mb-1">Dashboard</h4>
    <p class="text-muted">Overview of your library management system</p>
</div>

{{-- <div class="row g-3 mb-4">
    <div class="col-12 col-md-6 col-lg-3">
        <div class="card shadow-sm h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="card-title text-muted fw-semibold mb-0" style="font-size: 0.875rem;">Total Documents</h5>
                    <span class="badge bg-success" style="font-size: 0.75rem;">as of Today</span>
                </div>
                <h1 class="display-4 fw-bold text-dark">{{ count($documents) }}</h1>
                <div class="text-muted" style="font-size: 0.75rem;">&nbsp;</div>
            </div>
        </div>
    </div>

    <div class="col-12 col-md-6 col-lg-3">
        <div class="card shadow-sm h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="card-title text-muted fw-semibold mb-0" style="font-size: 0.875rem;">New Requests</h5>
                    <span class="badge bg-success" style="font-size: 0.75rem;">as of Today</span>
                </div>
                <h1 class="display-4 fw-bold text-dark">
                    {{ count($change_requests->where('created_at','>=',date('Y-m-d'))) + count($copy_requests->where('created_at','>=',date('Y-m-d'))) }}
                </h1>
                <div class="text-muted" style="font-size: 0.75rem;">&nbsp;</div>
            </div>
        </div>
    </div>

    <div class="col-12 col-md-6 col-lg-3">
        <div class="card shadow-sm h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="card-title text-muted fw-semibold mb-0" style="font-size: 0.875rem;">Pending</h5>
                    <span class="badge bg-success" style="font-size: 0.75rem;">as of Today</span>
                </div>
                <h1 class="display-4 fw-bold text-dark">
                    {{ count($change_requests->where('status','Pending')) + count($copy_requests->where('status','Pending')) }}
                </h1>
                <div class="text-muted" style="font-size: 0.75rem;">&nbsp;</div>
            </div>
        </div>
    </div>

    <div class="col-12 col-md-6 col-lg-3">
        <div class="card shadow-sm h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="card-title text-muted fw-semibold mb-0" style="font-size: 0.875rem;">Approved</h5>
                    <span class="badge bg-success" style="font-size: 0.75rem;">as of {{ date('M. Y') }}</span>
                </div>
                <h1 class="display-4 fw-bold text-dark">
                    {{ count($change_requests->where('status','Approved')) + count($copy_requests->where('status','Approved')) }}
                </h1>
                <div class="text-muted" style="font-size: 0.75rem;">&nbsp;</div>
            </div>
        </div>
    </div>
</div> --}}

<div class="row g-4 mb-4">
    <div class="col-12 col-lg-8">
        <div class="card shadow-sm h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="fw-semibold text-dark mb-0">Files</h5>
                    <div class="d-flex gap-2">
                        <button class="btn btn-outline-secondary btn-sm d-inline-flex align-items-center gap-1">
                            <i class="ri-upload-line"></i> Upload
                        </button>
                        <button class="btn btn-outline-secondary btn-sm">
                            <i class="ri-list-check"></i>
                        </button>
                        <button class="btn btn-outline-secondary btn-sm">
                            <i class="ri-grid-line"></i>
                        </button>
                    </div>
                </div>

                <div class="row row-cols-2 row-cols-sm-3 row-cols-md-4 row-cols-xl-5 g-3 mb-3">
                    <div class="col">
                        <div class="card border file-card position-relative">
                            <div class="position-absolute top-0 end-0 m-2 more-btn">
                                <button class="btn btn-sm btn-light p-1 file-more-btn" style="width: 24px; height: 24px; line-height: 1;">
                                    <i class="ri-more-2-fill"></i>
                                </button>
                                <div class="file-dropdown-menu">
                                    <button class="file-dropdown-item submenu" data-action="preview">
                                        <i class="ri-external-link-line"></i>
                                        <span>Preview</span>
                                        <i class="ri-arrow-right-s-line ms-auto"></i>
                                    </button>
                                    <button class="file-dropdown-item">
                                        <i class="ri-download-line"></i>
                                        <span>Download</span>
                                    </button>
                                    <button class="file-dropdown-item">
                                        <i class="ri-pencil-line"></i>
                                        <span>Rename</span>
                                        <span class="shortcut">Ctrl+Alt+E</span>
                                    </button>
                                    <button class="file-dropdown-item">
                                        <i class="ri-file-copy-line"></i>
                                        <span>Duplicate</span>
                                        <span class="shortcut">Ctrl+C Ctrl+V</span>
                                    </button>
                                    <button class="file-dropdown-item" data-action="share">
                                        <i class="ri-share-line"></i>
                                        <span>Share</span>
                                        <i class="ri-arrow-right-s-line ms-auto"></i>
                                    </button>
                                    <button class="file-dropdown-item submenu">
                                        <i class="ri-archive-line"></i>
                                        <span>Organize</span>
                                        <i class="ri-arrow-right-s-line ms-auto"></i>
                                    </button>
                                    <div class="file-dropdown-divider"></div>
                                    <button class="file-dropdown-item danger">
                                        <i class="ri-delete-bin-line"></i>
                                        <span>Move to trash</span>
                                    </button>
                                </div>
                                <div class="file-preview-menu">
                                    <button class="file-dropdown-item" data-action="back">
                                        <i class="ri-arrow-left-line"></i>
                                        <span>Back</span>
                                    </button>
                                    <div class="file-dropdown-divider"></div>
                                    <button class="file-dropdown-item">
                                        <i class="ri-zoom-in-line"></i>
                                        <span>Preview</span>
                                        <span class="shortcut">Ctrl+Alt+P</span>
                                    </button>
                                    <button class="file-dropdown-item">
                                        <i class="ri-external-link-line"></i>
                                        <span>Open in new tab</span>
                                    </button>
                                    <button class="file-dropdown-item">
                                        <i class="ri-pencil-line"></i>
                                        <span>Edit</span>
                                    </button>
                                    <button class="file-dropdown-item">
                                        <i class="ri-link"></i>
                                        <span>Link</span>
                                    </button>
                                    <button class="file-dropdown-item">
                                        <i class="ri-book-open-line"></i>
                                        <span>Archive</span>
                                    </button>
                                </div>
                                <div class="file-share-menu">
                                    <button class="file-dropdown-item" data-action="back-share">
                                        <i class="ri-arrow-left-line"></i>
                                        <span>Back</span>
                                    </button>
                                    <div class="file-dropdown-divider"></div>
                                    <button class="file-dropdown-item">
                                        <i class="ri-user-add-line"></i>
                                        <span>Share</span>
                                        <span class="shortcut">Ctrl+Alt+A</span>
                                    </button>
                                    <button class="file-dropdown-item">
                                        <i class="ri-link"></i>
                                        <span>Copy link</span>
                                    </button>
                                </div>
                            </div>
                            <img src="{{asset('assets/images/book1.jpg')}}" class="card-img-top" alt="Cover of the book 'Spark'" style="height: 120px; object-fit: fit;">
                            <div class="card-body p-2 text-start">
                                <div class="docu d-flex align-items-center gap-2">
                                    <i class="ri-file-pdf-line text-danger" style="font-size: 1rem;"></i>
                                    <div class="fw-semibold text-dark text-truncate" style="font-size: 0.75rem;">Docu.jpg</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col">
                        <div class="card border file-card position-relative">
                            <div class="position-absolute top-0 end-0 m-2 more-btn">
                                <button class="btn btn-sm btn-light p-1 file-more-btn" style="width: 24px; height: 24px; line-height: 1;">
                                    <i class="ri-more-2-fill"></i>
                                </button>
                                <div class="file-dropdown-menu">
                                    <button class="file-dropdown-item submenu" data-action="preview">
                                        <i class="ri-external-link-line"></i>
                                        <span>Preview</span>
                                        <i class="ri-arrow-right-s-line ms-auto"></i>
                                    </button>
                                    <button class="file-dropdown-item">
                                        <i class="ri-download-line"></i>
                                        <span>Download</span>
                                    </button>
                                    <button class="file-dropdown-item">
                                        <i class="ri-pencil-line"></i>
                                        <span>Rename</span>
                                        <span class="shortcut">Ctrl+Alt+E</span>
                                    </button>
                                    <button class="file-dropdown-item">
                                        <i class="ri-file-copy-line"></i>
                                        <span>Duplicate</span>
                                        <span class="shortcut">Ctrl+C Ctrl+V</span>
                                    </button>
                                    <button class="file-dropdown-item" data-action="share">
                                        <i class="ri-share-line"></i>
                                        <span>Share</span>
                                        <i class="ri-arrow-right-s-line ms-auto"></i>
                                    </button>
                                    <button class="file-dropdown-item submenu">
                                        <i class="ri-archive-line"></i>
                                        <span>Organize</span>
                                        <i class="ri-arrow-right-s-line ms-auto"></i>
                                    </button>
                                    <div class="file-dropdown-divider"></div>
                                    <button class="file-dropdown-item danger">
                                        <i class="ri-delete-bin-line"></i>
                                        <span>Move to trash</span>
                                    </button>
                                </div>
                                <div class="file-preview-menu">
                                    <button class="file-dropdown-item" data-action="back">
                                        <i class="ri-arrow-left-line"></i>
                                        <span>Back</span>
                                    </button>
                                    <div class="file-dropdown-divider"></div>
                                    <button class="file-dropdown-item">
                                        <i class="ri-zoom-in-line"></i>
                                        <span>Preview</span>
                                        <span class="shortcut">Ctrl+Alt+P</span>
                                    </button>
                                    <button class="file-dropdown-item">
                                        <i class="ri-external-link-line"></i>
                                        <span>Open in new tab</span>
                                    </button>
                                    <button class="file-dropdown-item">
                                        <i class="ri-pencil-line"></i>
                                        <span>Edit</span>
                                    </button>
                                    <button class="file-dropdown-item">
                                        <i class="ri-link"></i>
                                        <span>Link</span>
                                    </button>
                                    <button class="file-dropdown-item">
                                        <i class="ri-book-open-line"></i>
                                        <span>Archive</span>
                                    </button>
                                </div>
                                <div class="file-share-menu">
                                    <button class="file-dropdown-item" data-action="back-share">
                                        <i class="ri-arrow-left-line"></i>
                                        <span>Back</span>
                                    </button>
                                    <div class="file-dropdown-divider"></div>
                                    <button class="file-dropdown-item">
                                        <i class="ri-user-add-line"></i>
                                        <span>Share</span>
                                        <span class="shortcut">Ctrl+Alt+A</span>
                                    </button>
                                    <button class="file-dropdown-item">
                                        <i class="ri-link"></i>
                                        <span>Copy link</span>
                                    </button>
                                </div>
                            </div>
                            <img src="{{asset('assets/images/book2.jpg')}}" class="card-img-top" alt="Cover of the book 'Spark'" style="height: 120px; object-fit: fit;">
                            <div class="card-body p-2 text-start">
                                <div class="d-flex align-items-center gap-2">
                                    <i class="ri-file-text-line text-primary" style="font-size: 1rem;"></i>
                                    <div class="fw-semibold text-dark text-truncate" style="font-size: 0.75rem;">Docu.jpg</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col">
                        <div class="card border file-card position-relative">
                            <div class="position-absolute top-0 end-0 m-2 more-btn">
                                <button class="btn btn-sm btn-light p-1 file-more-btn" style="width: 24px; height: 24px; line-height: 1;">
                                    <i class="ri-more-2-fill"></i>
                                </button>
                                <div class="file-dropdown-menu">
                                    <button class="file-dropdown-item submenu" data-action="preview">
                                        <i class="ri-external-link-line"></i>
                                        <span>Preview</span>
                                        <i class="ri-arrow-right-s-line ms-auto"></i>
                                    </button>
                                    <button class="file-dropdown-item">
                                        <i class="ri-download-line"></i>
                                        <span>Download</span>
                                    </button>
                                    <button class="file-dropdown-item">
                                        <i class="ri-pencil-line"></i>
                                        <span>Rename</span>
                                        <span class="shortcut">Ctrl+Alt+E</span>
                                    </button>
                                    <button class="file-dropdown-item">
                                        <i class="ri-file-copy-line"></i>
                                        <span>Duplicate</span>
                                        <span class="shortcut">Ctrl+C Ctrl+V</span>
                                    </button>
                                    <button class="file-dropdown-item" data-action="share">
                                        <i class="ri-share-line"></i>
                                        <span>Share</span>
                                        <i class="ri-arrow-right-s-line ms-auto"></i>
                                    </button>
                                    <button class="file-dropdown-item submenu">
                                        <i class="ri-archive-line"></i>
                                        <span>Organize</span>
                                        <i class="ri-arrow-right-s-line ms-auto"></i>
                                    </button>
                                    <div class="file-dropdown-divider"></div>
                                    <button class="file-dropdown-item danger">
                                        <i class="ri-delete-bin-line"></i>
                                        <span>Move to trash</span>
                                    </button>
                                </div>
                                <div class="file-preview-menu">
                                    <button class="file-dropdown-item" data-action="back">
                                        <i class="ri-arrow-left-line"></i>
                                        <span>Back</span>
                                    </button>
                                    <div class="file-dropdown-divider"></div>
                                    <button class="file-dropdown-item">
                                        <i class="ri-zoom-in-line"></i>
                                        <span>Preview</span>
                                        <span class="shortcut">Ctrl+Alt+P</span>
                                    </button>
                                    <button class="file-dropdown-item">
                                        <i class="ri-external-link-line"></i>
                                        <span>Open in new tab</span>
                                    </button>
                                    <button class="file-dropdown-item">
                                        <i class="ri-pencil-line"></i>
                                        <span>Edit</span>
                                    </button>
                                    <button class="file-dropdown-item">
                                        <i class="ri-link"></i>
                                        <span>Link</span>
                                    </button>
                                    <button class="file-dropdown-item">
                                        <i class="ri-book-open-line"></i>
                                        <span>Archive</span>
                                    </button>
                                </div>
                                <div class="file-share-menu">
                                    <button class="file-dropdown-item" data-action="back-share">
                                        <i class="ri-arrow-left-line"></i>
                                        <span>Back</span>
                                    </button>
                                    <div class="file-dropdown-divider"></div>
                                    <button class="file-dropdown-item">
                                        <i class="ri-user-add-line"></i>
                                        <span>Share</span>
                                        <span class="shortcut">Ctrl+Alt+A</span>
                                    </button>
                                    <button class="file-dropdown-item">
                                        <i class="ri-link"></i>
                                        <span>Copy link</span>
                                    </button>
                                </div>
                            </div>
                            <img src="{{asset('assets/images/book3.jpg')}}" class="card-img-top" alt="Cover of the book 'Spark'" style="height: 120px; object-fit: fit;">
                            <div class="card-body p-2 text-start">
                                <div class="d-flex align-items-center gap-2">
                                    <i class="ri-file-pdf-line text-danger" style="font-size: 1rem;"></i>
                                    <div class="fw-semibold text-dark text-truncate" style="font-size: 0.75rem;">Docu.jpg</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col">
                        <div class="card border file-card position-relative">
                            <div class="position-absolute top-0 end-0 m-2 more-btn">
                                <button class="btn btn-sm btn-light p-1 file-more-btn" style="width: 24px; height: 24px; line-height: 1;">
                                    <i class="ri-more-2-fill"></i>
                                </button>
                                <div class="file-dropdown-menu">
                                    <button class="file-dropdown-item submenu" data-action="preview">
                                        <i class="ri-external-link-line"></i>
                                        <span>Preview</span>
                                        <i class="ri-arrow-right-s-line ms-auto"></i>
                                    </button>
                                    <button class="file-dropdown-item">
                                        <i class="ri-download-line"></i>
                                        <span>Download</span>
                                    </button>
                                    <button class="file-dropdown-item">
                                        <i class="ri-pencil-line"></i>
                                        <span>Rename</span>
                                        <span class="shortcut">Ctrl+Alt+E</span>
                                    </button>
                                    <button class="file-dropdown-item">
                                        <i class="ri-file-copy-line"></i>
                                        <span>Duplicate</span>
                                        <span class="shortcut">Ctrl+C Ctrl+V</span>
                                    </button>
                                    <button class="file-dropdown-item" data-action="share">
                                        <i class="ri-share-line"></i>
                                        <span>Share</span>
                                        <i class="ri-arrow-right-s-line ms-auto"></i>
                                    </button>
                                    <button class="file-dropdown-item submenu">
                                        <i class="ri-archive-line"></i>
                                        <span>Organize</span>
                                        <i class="ri-arrow-right-s-line ms-auto"></i>
                                    </button>
                                    <div class="file-dropdown-divider"></div>
                                    <button class="file-dropdown-item danger">
                                        <i class="ri-delete-bin-line"></i>
                                        <span>Move to trash</span>
                                    </button>
                                </div>
                                <div class="file-preview-menu">
                                    <button class="file-dropdown-item" data-action="back">
                                        <i class="ri-arrow-left-line"></i>
                                        <span>Back</span>
                                    </button>
                                    <div class="file-dropdown-divider"></div>
                                    <button class="file-dropdown-item">
                                        <i class="ri-zoom-in-line"></i>
                                        <span>Preview</span>
                                        <span class="shortcut">Ctrl+Alt+P</span>
                                    </button>
                                    <button class="file-dropdown-item">
                                        <i class="ri-external-link-line"></i>
                                        <span>Open in new tab</span>
                                    </button>
                                    <button class="file-dropdown-item">
                                        <i class="ri-pencil-line"></i>
                                        <span>Edit</span>
                                    </button>
                                    <button class="file-dropdown-item">
                                        <i class="ri-link"></i>
                                        <span>Link</span>
                                    </button>
                                    <button class="file-dropdown-item">
                                        <i class="ri-book-open-line"></i>
                                        <span>Archive</span>
                                    </button>
                                </div>
                                <div class="file-share-menu">
                                    <button class="file-dropdown-item" data-action="back-share">
                                        <i class="ri-arrow-left-line"></i>
                                        <span>Back</span>
                                    </button>
                                    <div class="file-dropdown-divider"></div>
                                    <button class="file-dropdown-item">
                                        <i class="ri-user-add-line"></i>
                                        <span>Share</span>
                                        <span class="shortcut">Ctrl+Alt+A</span>
                                    </button>
                                    <button class="file-dropdown-item">
                                        <i class="ri-link"></i>
                                        <span>Copy link</span>
                                    </button>
                                </div>
                            </div>
                            <img src="{{asset('assets/images/book4.jpg')}}" class="card-img-top" alt="Cover of the book 'Spark'" style="height: 120px; object-fit: fit;">
                            <div class="card-body p-2 text-start">
                                <div class="d-flex align-items-center gap-2">
                                    <i class="ri-file-pdf-line text-danger" style="font-size: 1rem;"></i>
                                    <div class="fw-semibold text-dark text-truncate" style="font-size: 0.75rem;">Docu.jpg</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col">
                        <div class="card border file-card position-relative">
                            <div class="position-absolute top-0 end-0 m-2 more-btn">
                                <button class="btn btn-sm btn-light p-1 file-more-btn" style="width: 24px; height: 24px; line-height: 1;">
                                    <i class="ri-more-2-fill"></i>
                                </button>
                                <div class="file-dropdown-menu">
                                    <button class="file-dropdown-item submenu" data-action="preview">
                                        <i class="ri-external-link-line"></i>
                                        <span>Preview</span>
                                        <i class="ri-arrow-right-s-line ms-auto"></i>
                                    </button>
                                    <button class="file-dropdown-item">
                                        <i class="ri-download-line"></i>
                                        <span>Download</span>
                                    </button>
                                    <button class="file-dropdown-item">
                                        <i class="ri-pencil-line"></i>
                                        <span>Rename</span>
                                        <span class="shortcut">Ctrl+Alt+E</span>
                                    </button>
                                    <button class="file-dropdown-item">
                                        <i class="ri-file-copy-line"></i>
                                        <span>Duplicate</span>
                                        <span class="shortcut">Ctrl+C Ctrl+V</span>
                                    </button>
                                    <button class="file-dropdown-item" data-action="share">
                                        <i class="ri-share-line"></i>
                                        <span>Share</span>
                                        <i class="ri-arrow-right-s-line ms-auto"></i>
                                    </button>
                                    <button class="file-dropdown-item submenu">
                                        <i class="ri-archive-line"></i>
                                        <span>Organize</span>
                                        <i class="ri-arrow-right-s-line ms-auto"></i>
                                    </button>
                                    <div class="file-dropdown-divider"></div>
                                    <button class="file-dropdown-item danger">
                                        <i class="ri-delete-bin-line"></i>
                                        <span>Move to trash</span>
                                    </button>
                                </div>
                                <div class="file-preview-menu">
                                    <button class="file-dropdown-item" data-action="back">
                                        <i class="ri-arrow-left-line"></i>
                                        <span>Back</span>
                                    </button>
                                    <div class="file-dropdown-divider"></div>
                                    <button class="file-dropdown-item">
                                        <i class="ri-zoom-in-line"></i>
                                        <span>Preview</span>
                                        <span class="shortcut">Ctrl+Alt+P</span>
                                    </button>
                                    <button class="file-dropdown-item">
                                        <i class="ri-external-link-line"></i>
                                        <span>Open in new tab</span>
                                    </button>
                                    <button class="file-dropdown-item">
                                        <i class="ri-pencil-line"></i>
                                        <span>Edit</span>
                                    </button>
                                    <button class="file-dropdown-item">
                                        <i class="ri-link"></i>
                                        <span>Link</span>
                                    </button>
                                    <button class="file-dropdown-item">
                                        <i class="ri-book-open-line"></i>
                                        <span>Archive</span>
                                    </button>
                                </div>
                                <div class="file-share-menu">
                                    <button class="file-dropdown-item" data-action="back-share">
                                        <i class="ri-arrow-left-line"></i>
                                        <span>Back</span>
                                    </button>
                                    <div class="file-dropdown-divider"></div>
                                    <button class="file-dropdown-item">
                                        <i class="ri-user-add-line"></i>
                                        <span>Share</span>
                                        <span class="shortcut">Ctrl+Alt+A</span>
                                    </button>
                                    <button class="file-dropdown-item">
                                        <i class="ri-link"></i>
                                        <span>Copy link</span>
                                    </button>
                                </div>
                            </div>
                            <img src="{{asset('assets/images/book5.jpg')}}" class="card-img-top" alt="Cover of the book 'Spark'" style="height: 120px; object-fit: fit;">
                            <div class="card-body p-2 text-start">
                                <div class="d-flex align-items-center gap-2">
                                    <i class="ri-file-text-line text-primary" style="font-size: 1rem;"></i>
                                    <div class="fw-semibold text-dark text-truncate" style="font-size: 0.75rem;">Docu.jpg</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Loop through actual files --}}
                    {{-- @foreach($files as $file)
                    <div class="col">
                        <div class="card border file-card position-relative">
                            <div class="position-absolute top-0 end-0 m-2 more-btn">
                                <button class="btn btn-sm btn-light p-1" style="width: 24px; height: 24px; line-height: 1;">
                                    <i class="ri-more-2-fill"></i>
                                </button>
                            </div>
                            <img src="{{ $file->preview_url }}" class="card-img-top" alt="{{ $file->name }}" style="height: 120px; object-fit: cover;">
                            <div class="card-body p-2 text-start">
                                <div class="d-flex align-items-center gap-2">
                                    <i class="ri-file-{{ $file->type }}-line text-{{ $file->type == 'pdf' ? 'danger' : 'primary' }}" style="font-size: 1rem;"></i>
                                    <div class="fw-semibold text-dark text-truncate" style="font-size: 0.75rem;">{{ $file->name }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach --}}
                </div>

                <div class="border rounded p-4 text-center" style="border-style: dashed !important; border-color: #dee2e6 !important;">
                    <i class="ri-upload-cloud-line text-muted mb-2" style="font-size: 2.5rem;"></i>
                    <h6 class="fw-semibold mb-1" style="font-size: 0.875rem;">Upload a New Document</h6>
                    <p class="text-muted mb-0" style="font-size: 0.75rem;">Or add from <span class="text-primary">Google Drive</span></p>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12 col-lg-4">
        <div class="card shadow-sm h-100">
            <div class="card-body">
                <h5 class="fw-semibold text-dark mb-3">Public Form</h5>
                
                <div class="d-flex flex-column gap-3">
                    <div class="d-flex align-items-center justify-content-between p-3 border rounded">
                        <div class="d-flex align-items-center gap-2">
                            <i class="ri-file-text-line" style="font-size: 1.5rem;"></i>
                            <span class="fw-medium" style="font-size: 0.875rem;">Billing Statement</span>
                        </div>
                        <button class="btn btn-sm btn-light">
                            <i class="ri-more-2-fill"></i>
                        </button>
                    </div>

                    <div class="d-flex align-items-center justify-content-between p-3 border rounded">
                        <div class="d-flex align-items-center gap-2">
                            <i class="ri-file-text-line" style="font-size: 1.5rem;"></i>
                            <span class="fw-medium" style="font-size: 0.875rem;">Billing Statement</span>
                        </div>
                        <button class="btn btn-sm btn-light">
                            <i class="ri-more-2-fill"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Documents Section -->
<div class="card shadow-sm mb-3">
    <div class="card-body">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h5 class="fw-semibold text-dark mb-0">Documents</h5>
        </div>

        <div class="row g-3 mb-4">
            <div class="col-12 col-lg-auto flex-lg-grow-1">
                <div class="position-relative">
                    <i class="ri-search-line position-absolute top-50 translate-middle-y ms-3 text-muted"></i>
                    <input type="text" placeholder="Search Product" class="form-control form-control-sm ps-5" style="min-width: 250px;">
                </div>
            </div>
            <div class="col-auto d-flex align-items-center gap-2">
                <label class="mb-0 text-nowrap" style="font-size: 0.875rem;">Sort by</label>
                <select class="form-select form-select-sm" style="min-width: 120px;">
                    <option>creation</option>
                    <option>name</option>
                    <option>date</option>
                </select>
            </div>
            <div class="col-auto d-flex align-items-center gap-2">
                <label class="mb-0 text-nowrap" style="font-size: 0.875rem;">Status</label>
                <select class="form-select form-select-sm" style="min-width: 120px;">
                    <option>Default</option>
                    <option>Active</option>
                    <option>Archived</option>
                </select>
            </div>
            <div class="col-auto d-flex align-items-center gap-2">
                <label class="mb-0 text-nowrap" style="font-size: 0.875rem;">Show entries</label>
                <select class="form-select form-select-sm" style="min-width: 120px;">
                    <option>25</option>
                    <option>50</option>
                    <option>100</option>
                </select>
            </div>
        </div>

        <div class="border-top" style="border-color: #7f1d1d !important; border-width: 2px !important;">
            <div class="text-center py-5 px-3 text-muted">
                <i class="ri-file-list-line" style="font-size: 3rem; opacity: 0.3;"></i>
                <p class="mt-3 mb-0">No documents found</p>
            </div>
        </div>
    </div>
</div>

{{-- Documents Library Chart --}}
{{-- <div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Documents Library</h4>
                <div id="stocked"></div>
            </div>
        </div>
    </div>
</div> --}}

{{-- Requests Bar Chart --}}
{{-- @if((auth()->user()->role == "Administrator") || (auth()->user()->role == "Management Representative") || (auth()->user()->role == "Business Process Manager"))
<div class="row mt-3">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Requests</h4>
                <div id="morris-bar-chart"></div>
            </div>
        </div>
    </div>
</div>
@endif --}}

{{-- Permits and Licenses Donut Chart --}}
{{-- @if(count($permits) != 0)
<div class="row mt-3">
    <div class="col-lg-4">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Permits and licenses ({{ count($permits) }})</h4>
                <div id="morris-donut-chart"></div>
            </div>
        </div>
    </div>
</div>
@endif --}}

{{-- Document Requests Status Pie Chart --}}
{{-- @if((auth()->user()->role == "Administrator") || (auth()->user()->role == "Management Representative") || (auth()->user()->role == "Business Process Manager"))
<div class="row mt-3">
    <div class="col-lg-4">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Document Requests Status this {{ date('Y') }}</h4>
                <div id="pie"></div>
            </div>
        </div>
    </div>
</div>
@endif --}}

@endsection

@section('js')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const moreButtons = document.querySelectorAll('.file-more-btn');
        
        moreButtons.forEach(button => {
            button.addEventListener('click', function(e) {
                e.stopPropagation();
                
                const dropdown = this.nextElementSibling;
                const previewMenu = dropdown.nextElementSibling;
                const shareMenu = previewMenu.nextElementSibling;
                const fileCard = this.closest('.file-card');
                
                document.querySelectorAll('.file-dropdown-menu, .file-preview-menu, .file-share-menu').forEach(menu => {
                    if (menu !== dropdown && menu !== previewMenu && menu !== shareMenu) {
                        menu.classList.remove('show');
                        menu.closest('.file-card')?.classList.remove('dropdown-open');
                    }
                });
                
                dropdown.classList.toggle('show');
                previewMenu.classList.remove('show');
                shareMenu.classList.remove('show');
                
                if (dropdown.classList.contains('show')) {
                    fileCard.classList.add('dropdown-open');
                } else {
                    fileCard.classList.remove('dropdown-open');
                }
            });
        });

        document.querySelectorAll('.file-dropdown-menu, .file-preview-menu, .file-share-menu')
            .forEach(menu => {
                menu.addEventListener('click', e => e.stopPropagation());
            });

        document.addEventListener('click', function(e) {
            if (!e.target.closest('.file-more-btn') &&
                !e.target.closest('.file-dropdown-menu') &&
                !e.target.closest('.file-preview-menu') &&
                !e.target.closest('.file-share-menu')) {
                document.querySelectorAll('.file-dropdown-menu, .file-preview-menu, .file-share-menu').forEach(menu => {
                    menu.classList.remove('show');
                    menu.closest('.file-card')?.classList.remove('dropdown-open');
                });
            }
        });

        document.querySelectorAll('.file-dropdown-item').forEach(item => {
            item.addEventListener('click', function(e) {
                e.stopPropagation();
                const action = this.getAttribute('data-action');
                
                if (action === 'preview') {
                    const dropdown = this.closest('.file-dropdown-menu');
                    const previewMenu = dropdown.nextElementSibling;
                    dropdown.classList.remove('show');
                    previewMenu.classList.add('show');
                    return;
                }
                if (action === 'share') {
                    const dropdown = this.closest('.file-dropdown-menu');
                    const previewMenu = dropdown.nextElementSibling;
                    const shareMenu = previewMenu.nextElementSibling;
                    dropdown.classList.remove('show');
                    shareMenu.classList.add('show');
                    return;
                }
                if (action === 'back') {
                    const previewMenu = this.closest('.file-preview-menu');
                    const dropdown = previewMenu.previousElementSibling;
                    previewMenu.classList.remove('show');
                    dropdown.classList.add('show');
                    return;
                }
                if (action === 'back-share') {
                    const shareMenu = this.closest('.file-share-menu');
                    const previewMenu = shareMenu.previousElementSibling;
                    const dropdown = previewMenu.previousElementSibling;
                    shareMenu.classList.remove('show');
                    dropdown.classList.add('show');
                    return;
                }

                const actionText = this.querySelector('span').textContent.trim();
                console.log('Action clicked:', actionText);
                
                const menu = this.closest('.file-dropdown-menu, .file-preview-menu, .file-share-menu');
                menu.classList.remove('show');
                menu.closest('.file-card')?.classList.remove('dropdown-open');
            });
        });
    });
</script>

{{-- Chart Scripts (COMMENTED OUT) --}}
{{-- 
<script src="{{ asset('login_css/js/plugins/dataTables/datatables.min.js')}}"></script>
<script src="{{ asset('login_css/js/plugins/chosen/chosen.jquery.js') }}"></script>
<script src="{{ asset('login_css/js/plugins/chartJs/Chart.min.js') }}"></script>
<script src="{{ asset('login_css/js/plugins/morris/raphael-2.1.0.min.js') }}"></script>
<script src="{{ asset('login_css/js/plugins/morris/morris.js') }}"></script>
<script src="{{ asset('login_css/js/plugins/d3/d3.min.js') }}"></script>
<script src="{{ asset('login_css/js/plugins/c3/c3.min.js') }}"></script>

<script>
    var departments = {!! json_encode(($departments)->toArray()) !!};
    var for_renewal = {!! json_encode((count($permits->where('expiration_date','!=',null)->where('expiration_date','<',date('Y-m-d', strtotime("+3 months", strtotime(date('Y-m-d')))))))) !!};
    var over_due = {!! json_encode((count($permits->where('expiration_date','!=',null)->where('expiration_date','<',date('Y-m-d'))))) !!};
    var active = {!! json_encode((count($permits->where('expiration_date','!=',null)->where('expiration_date','>=',date('Y-m-d', strtotime("+3 months", strtotime(date('Y-m-d')))))))) !!};
    var no_expiration = {!! json_encode((count($permits->where('expiration_date','==',null)))) !!};
    var types = {!! json_encode(($categories->pluck('name'))->toArray()) !!};
    var obsoletes = {!! json_encode(($departments->pluck('obsoletes_count'))->toArray()) !!};
    var months = {!! json_encode(($months)) !!};

    var pending = {!!json_encode(($yearChangeRequests->where('status','Pending')->count()))!!}
    var approved = {!!json_encode(($yearChangeRequests->where('status','Approved')->count()))!!}
    var declined = {!!json_encode(($yearChangeRequests->where('status','Declined')->count()))!!}
    
    $(function() {
        // Morris Donut Chart
        Morris.Donut({
            element: 'morris-donut-chart',
            data: [
                { label: "For Renewal", value: for_renewal-over_due },
                { label: "Overdue", value: over_due },
                { label: "Active", value: active },
                { label: "No Expiration", value: no_expiration }
            ],
            resize: true,
            colors: ['#FFA500','#f44336', '#54cdb4','#1ab394'],
        });

        // Morris Bar Chart
        var aaa = months;
        Morris.Bar({
            element: 'morris-bar-chart',
            data: aaa,
            xkey: 'y',
            ykeys: ['a', 'b'],
            labels: ['Change Requests', 'Copy Requests'],
            hideHover: 'auto',
            resize: true,
            barColors: ['#1ab394', '#cacaca'],
        });
    });

    $(document).ready(function(){
        var types_names = {!! json_encode(($categories)->toArray()) !!};
        var colors ={};
        var column = ['x'];
 
        for(y=0;y<departments.length;y++) {
            column.push(departments[y].code+"("+departments[y].documents_count+")");
        }
        
        var types = [];
        var columns = [column];
        
        for(i = 0; i < types_names.length; i++) {
            type_column = [types_names[i].code];
            for(z = 0; z < departments.length; z++) {
                var doc = departments[z].documents;
                var count = doc.filter(o => o.category === types_names[i].name);
                type_column.push(count.length)
            }
            columns.push(type_column);
            colors[types_names[i].code] = types_names[i].color;
            types.push(types_names[i].code);
        }
        
        final_types = [types];
        
        // C3 Stacked Bar Chart
        c3.generate({
            bindto: '#stocked',
            data:{
                x : 'x',
                columns: columns,
                colors: colors,
                type: 'bar',
                groups: final_types,
            },
            axis: {
                x: {
                    show: true,
                    type: 'categorized',
                },
                y2: {
                    show: true,
                    label: 'Counts'
                },
                y: {
                    show: true,
                    label: 'Counts'
                },
            }
        });

        // C3 Pie Chart
        c3.generate({
            bindto: '#pie',
            data:{
                columns: [
                    ['Approved', approved],
                    ['Declined', declined],
                    ['Pending', pending]
                ],
                colors:{
                    Approved: '#54cdb4',
                    Declined: '#f44336',
                    Pending: '#BABABA',
                },
                type : 'pie'
            }
        });

        $('.locations').chosen({width: "100%"});
        $('.tables').DataTable({
            pageLength: 10,
            responsive: true,
            dom: '<"html5buttons"B>lTfgitp',
            buttons: []
        });
    });
</script>
--}}
@endsection