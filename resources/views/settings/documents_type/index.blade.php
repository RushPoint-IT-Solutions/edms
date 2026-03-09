@extends('layouts.header')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <h4 class="mb-0">Type of Documents</h4>
        <p class="text-muted mb-0">Manage document types for categorization and organization</p>
    </div>
</div>

<div class="row">
    <div class="col-md-12 mb-5">
        <div class="card shadow-sm">
            <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
                <h5 class="mb-0">Type of Documents</h5>
                <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addDocumentTypeModal"><i class="fa fa-plus"></i> Add Type</button>
            </div>
        </div>
    </div>
</div>
@endsection