@extends('layouts.header')

@section('css')
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
</style>
@endsection

@section('content')
{{-- <div class="row">
    <div class="col-md-12">
        <div class="card shadow-none">
            <div class="card-header">
                <div class="d-flex gap-3 justify-content-start align-items-center">
                    <a href="{{ url('documents') }}" class="btn btn-sm btn-danger">Back</a>
                    <h3>{{ $folder->name }}</h3>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table-striped table">
                        <thead>
                            <tr>
                                <th>Control Code</th>
                                <th>Title</th>
                                <th>Effective Date</th>
                                <th>Category</th>
                                <th>Uploaded By</th>
                                <th>File</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($folder->document as $document)
                            <tr>
                                <td>{{ $document->control_code }}</td>
                                <td>{{ $document->title }}</td>
                                <td>{{ date('M d Y', strtotime($document->effective_date)) }}</td>
                                <td>{{ $document->category }}</td>
                                <td>{{ $document->user->name }}</td>
                                <td>
                                    @foreach ($document->attachments->where('type','pdf_copy') as $file)
                                    <a href="{{ url($file->attachment) }}" target="_blank">
                                        <i class="fa fa-file-pdf-o"></i>
                                    </a>
                                    @endforeach
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div> --}}
<div class="row">
    <div class="col-lg-12">
        <ol class="breadcrumb p-3 py-2 bg-light">
            <li class="breadcrumb-item active" aria-current="page">Document</li>
            <li class="breadcrumb-item active" aria-current="page">{{ $folder->name }}</li>
        </ol>
        <div class="card shadow-sm">
            <div class="card-header">
                <a href="{{ url('documents') }}" class="btn btn-sm btn-danger">Back</a>
            </div>
            <div class="card-body">
                <div class="row row-cols-3 row-cols-sm-6 row-cols-md-4 row-cols-xl-5 g-3">
                    @foreach ($folder->document as $document)
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
                                {{-- <div class="file-dropdown-divider"></div>
                                <button class="file-dropdown-item" data-action="approve">
                                    <i class="ri-checkbox-circle-line"></i>
                                    <span>Approve</span>
                                </button> --}}
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
@endsection

@section('js')
<script>
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