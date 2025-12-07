@extends('layouts.header')

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
                
                            {{-- <div class="file-dropdown-menu">
                                <button class="file-dropdown-item" data-action="display">
                                    <i class="ri-file-text-line"></i>
                                    <input type="hidden" id="file" value="{{ $change_request->file }}" />
                                    <span>View</span>
                                </button>
                                <div class="file-dropdown-divider"></div>
                                <button class="file-dropdown-item" data-action="approve">
                                    <i class="ri-checkbox-circle-line"></i>
                                    <span>Approve</span>
                                </button>
                            </div> --}}
                            @foreach ($document->attachments->where('type','pdf_copy') as $file)
                            <a href='{{ url($file->attachment) }}' class="text-decoration-none" target="_blank">
                                <iframe
                                    src="https://docs.google.com/gview?url={{ urlencode(asset($file->attachment)) }}&embedded=true"
                                    loading="lazy" class="card-img-top" style="height: 100%;" scrolling="no" frameborder="0"></iframe>
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