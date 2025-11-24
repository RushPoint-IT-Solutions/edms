@extends('layouts.header')

@section('content')
<div class="row">
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
</div>
@endsection