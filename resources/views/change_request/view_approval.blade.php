@extends('layouts.header')

@section('css')
<link href="https://cdn.jsdelivr.net/npm/summernote@0.9.0/dist/summernote-bs4.min.css" rel="stylesheet">
@endsection

@section('content')
<div class="row">
    <div class="col-md-4">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        DOCUMENT INFORMATION
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12">DOC ID: <strong>DOC-{{ date('Y',
                                    strtotime($change_request->created_at)) }}-{{
                                    str_pad($change_request->id,3,'0',STR_PAD_LEFT) }}</strong></div>
                            <div class="col-md-12">Title: <strong>{{ $change_request->title }}</strong></div>
                            <div class="col-md-12">Description: <strong>{!! nl2br(e($change_request->description))
                                    !!}</strong></div>
                            <div class="col-md-12">Category: <strong>{{ $change_request->category }}</strong></div>
                            <div class="col-md-12">Privacy: <strong>{{ $change_request->privacy }}</strong></div>
                            <div class="col-md-12">Revision: <strong>{{ $change_request->revision }}</strong></div>
                            <div class="col-md-12">Requested By: <strong>{{ $change_request->user->name }}</strong>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        APPROVER
                    </div>
                    <div class="card-body">
                        @foreach ($change_request->approvers as $approver)
                        {{ $approver->user->name }} -
                        @if($approver->status == "Pending")
                        <span class="badge bg-warning">
                        @elseif($approver->status == "Approved")
                        <span class="badge bg-success">
                        @elseif($approver->status == "Returned")
                        <span class="badge bg-danger">
                        @elseif($approver->status == "Waiting")
                        <span class="badge bg-info">
                        @endif
                            {{ $approver->status }}
                        </span>
                        <br>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        ATTACHMENTS
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <b>Document</b>
                                <div class="card border file-card position-relative">
                                    <a href='{{ url($change_request->file) }}' class="text-decoration-none"
                                        target="_blank">
                                        <iframe src="https://docs.google.com/gview?url={{ urlencode(asset($change_request->file)) }}&embedded=true" loading="lazy"
                                            class="card-img-top" style="height: 100%;" scrolling="no"
                                            frameborder="0"></iframe>
                                        <div class="card-body p-2 text-start">
                                            <div class="docu d-flex align-items-center gap-2">
                                                <i class="ri-file-pdf-line text-danger" style="font-size: 1rem;"></i>
                                                @php
                                                $file = $change_request->file;
                                                $filename = explode('/',$file);
                                                @endphp
                                                <div class="fw-semibold text-dark text-truncate"
                                                    style="font-size: 0.75rem;">{{ $filename[2] }}</div>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            </div>

                            <b>Supporting Documents</b>
                            @foreach ($change_request->supporting_documents as $key=>$document)
                            {{-- {{ $key+1 }}.
                            <a href="{{ url($document->file) }}" target="_blank">
                                <i class="fa fa-file"></i>
                            </a>
                            <br> --}}
                            <div class="col-md-6">
                                <div class="card border file-card position-relative">
                                    <a href='{{ url($document->file) }}' class="text-decoration-none" target="_blank">
                                        <iframe src="https://docs.google.com/gview?url={{ urlencode(asset($document->file)) }}&embedded=true" loading="lazy" class="card-img-top"
                                            style="height: 100%;" scrolling="no" frameborder="0"></iframe>
                                        <div class="card-body p-2 text-start">
                                            <div class="docu d-flex align-items-center gap-2">
                                                <i class="ri-file-pdf-line text-danger" style="font-size: 1rem;"></i>
                                                @php
                                                $file = $document->file;
                                                $filename = explode('/',$file);
                                                @endphp
                                                <div class="fw-semibold text-dark text-truncate"
                                                    style="font-size: 0.75rem;">{{ $filename[2] }}</div>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card">
            <div class="card-header align-items-center d-flex">
                <h4 class="card-title mb-0 flex-grow-1">Comments</h4>
                <div class="flex-shrink-0">
                    {{-- <div class="dropdown card-header-dropdown">
                        <a class="text-reset dropdown-btn" href="#" data-bs-toggle="dropdown" aria-haspopup="true"
                            aria-expanded="false">
                            <span class="text-muted">Recent<i class="mdi mdi-chevron-down ms-1"></i></span>
                        </a>
                        <div class="dropdown-menu dropdown-menu-end">
                            <a class="dropdown-item" href="#">Recent</a>
                            <a class="dropdown-item" href="#">Top Rated</a>
                            <a class="dropdown-item" href="#">Previous</a>
                        </div>
                    </div> --}}
                </div>
            </div><!-- end card header -->

            <div class="card-body">
                <div data-simplebar style="height: 300px;" class="px-3 mx-n3 mb-2 simplebar-scrollable-y">
                    <div class="simplebar-wrapper" style="margin: 0px -16px;">
                        <div class="simplebar-height-auto-observer-wrapper">
                            <div class="simplebar-height-auto-observer"></div>
                        </div>
                        <div class="simplebar-mask">
                            <div class="simplebar-offset" style="right: 0px; bottom: 0px;">
                                <div class="simplebar-content-wrapper" tabindex="0" role="region"
                                    aria-label="scrollable content" style="height: 100%; overflow: hidden scroll;">
                                    <div class="simplebar-content" style="padding: 0px 16px;">
                                        @if(count($change_request->comments) > 0)
                                            @foreach ($change_request->comments as $comment)
                                            <div class="d-flex mb-4">
                                                <div class="flex-shrink-0">
                                                    <img src="{{ asset('images/no_image.png') }}" alt=""
                                                        class="avatar-xs rounded-circle material-shadow">
                                                </div>
                                                <div class="flex-grow-1 ms-3">
                                                    <h5 class="fs-13">{{ $comment->user->name }} <small class="text-muted ms-2">{{ date('d M Y - h:i A', strtotime($comment->created_at)) }} </small></h5>
                                                    <p class="text-muted">{!! $comment->comment !!}</p>
                                                </div>
                                            </div>
                                            @endforeach
                                        @else 
                                            <p style="font-style: italic;">No comment...</p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="simplebar-placeholder" style="width: 717px; height: 633px;"></div>
                    </div>
                    <div class="simplebar-track simplebar-horizontal" style="visibility: hidden;">
                        <div class="simplebar-scrollbar" style="width: 0px; display: none;"></div>
                    </div>
                    <div class="simplebar-track simplebar-vertical" style="visibility: visible;">
                        <div class="simplebar-scrollbar"
                            style="height: 142px; transform: translate3d(0px, 0px, 0px); display: block;"></div>
                    </div>
                </div>
                <form class="mt-4" method="POST" action="{{ url('change-request/comments') }}" onsubmit="show()">
                    @csrf
                    <input type="hidden" name="change_request_id" value="{{ $change_request->id }}">

                    <div class="row g-3">
                        <div class="col-12">
                            <label for="exampleFormControlTextarea1" class="form-label text-body">Leave a
                                Comments</label>
                            <textarea name="comment" class="form-control {{ $errors->has('comment') ? 'is-invalid' : '' }}" id="summernote"
                                rows="3" placeholder="Enter your comment..."></textarea>
                            @if($errors->has('comment'))
                                <p class="text-danger">{{ $errors->first('comment') }}</p>
                            @endif
                        </div>
                        <div class="col-12 d-grid">
                            <button type="submit" class="btn btn-success">Post Comments</button>
                        </div>
                    </div>
                </form>
                <a href="{{ url('for-approval') }}" class="btn btn-danger w-100 mt-2">Cancel</a>
                @php
                $request = ($change_request->approvers)->where('user_id', auth()->user()->id)->where('status',
                'Pending');
                $if_return = ($change_request->approvers)->whereIn('status', 'Returned');
                @endphp
                @if(count($request) > 0)
                <div class="col-md-12">
                    <a href="{{ url('documents/signature/'.$change_request->id) }}" target="_blank"
                        class="btn btn-primary w-100 mt-2">Sign Documents</a>
                </div>

                <div class="col-md-12">
                    <button type="submit" class="btn btn-danger w-100 mt-2" data-bs-toggle="modal"
                        data-bs-target="#return{{ $change_request->id }}">Return Documents</button>

                    @include('change_request.return_modal')
                </div>
                @endif
                @if(count($if_return) > 0 && (auth()->user()->id == $change_request->user_id))
                <form action="{{ url('change-request/change-request-action/'.$change_request->id) }}" method="POST">
                    @csrf

                    <input type="hidden" name="action" value="Submit">

                    <div class="col-md-12">
                        <button type="submit" class="btn btn-success w-100 mt-2">Submit Documents</button>
                    </div>
                </form>
                @endif
            </div>
            <!-- end card body -->
        </div>
    </div>
</div>
@endsection

@section('js')
{{-- <script src="{{ asset('assets/libs/@ckeditor/ckeditor5-build-classic/build/ckeditor.js') }}"></script> --}}
<script src="{{ asset('assets/js/pages/form-editor.init.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/summernote@0.9.0/dist/summernote-bs4.min.js"></script>
<script>
    $(document).ready(function() {
        $('#summernote').summernote({
            height: 300,
            placeholder:"Write a comment"
        });
    });
</script>
@endsection