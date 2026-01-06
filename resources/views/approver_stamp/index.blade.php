@extends('layouts.header')

@section('content')
<div class="row">
    <div class="col-md-5">
        <div class="card">
            <div class="card-header">
                <h6 class="card-title m-0">Upload stamp</h6>
            </div>
            <form method="POST" action="{{ url('approver-stamp/store') }}" enctype="multipart/form-data">
                @csrf

                <div class="card-body">
                    <div class="row g-4">
                        <div class="col-md-12">
                            <label for="" class="form-label">Attachment</label>
                            <input type="file" name="attachment" class="form-control @if($errors->has('attachment')) is-invalid @endif">
                            @if($errors->has('attachment'))
                            <p class="invalid-feedback">{{ $errors->first('attachment') }}</p>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <div class="float-end mb-2">
                        <button type="submit" class="btn btn-primary">Save</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <div class="col-md-7">
        <div class="card">
            <div class="card-header">
                <h6 class="card-title m-0">View stamp</h6>
            </div>
            <div class="card-body">
                @if($approver)
                <a href='#' class="text-decoration-none" onclick="return false;">
                    <img src="{{url($approver->file)}}" class="card-img-top" alt="Cover of the book 'Sp ark'" style="height: 100%; object-fit: fit;">
                    <div class="card-body p-2 text-start">
                        <div class="docu d-flex align-items-center gap-2">
                            <i class="ri-file-pdf-line text-danger" style="font-size: 1rem;"></i>
                            {{-- @php
                                $file = $change_request->file;
                                $filename = explode('/',$file);
                            @endphp  --}}
                            <div class="fw-semibold text-dark text-truncate" style="font-size: 0.75rem;">{{ $approver->file_name }}</div>
                        </div>
                    </div>
                </a>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection