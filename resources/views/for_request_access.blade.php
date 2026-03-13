@extends('layouts.header')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <h4 class="mb-0">Request Access Approvals</h4>
        <p class="text-muted mb-0">Manage document access requests</p>
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-xl-4 col-md-4">
        <div class="dashboard-card pending">
            <div class="icon-circle"><i class="fa fa-clock-o"></i></div>
            <h2 class="mb-0 font-weight-bold">
                {{ $document_request_access->where('status', 0)->count() }}
            </h2>
            <p>For Approval</p>
        </div>
    </div>
    <div class="col-xl-4 col-md-4">
        <div class="dashboard-card approved">
            <div class="icon-circle"><i class="fa fa-check-circle"></i></div>
            <h2 class="mb-0 font-weight-bold">
                {{ $document_request_access->where('status', 1)->count() }}
            </h2>
            <p>Approve</p>
        </div>
    </div>
    <div class="col-xl-4 col-md-4">
        <div class="dashboard-card declined">
            <div class="icon-circle"><i class="fa fa-times-circle"></i></div>
            <h2 class="mb-0 font-weight-bold">
                {{ $document_request_access->where('status', 3)->count() }}
            </h2>
            <p>Decline</p>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12 mb-4">
        <div class="card shadow-sm">
            <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
                <h5 class="mb-0">For Request Access</h5>
            </div>
            <div class="card-body">
                <div class="table-scroll-container" style="min-height: 450px;">
                    <table class="table table-hover table-bordered">
                        <thead class="table-light">
                            <tr>
                                <th>Actions</th>
                                <th>Requested By</th>
                                <th>Department</th>
                                <th>Title</th>
                                <th>Date</th>
                                <th>Reason</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($document_request_access->where('status', 0) as $access)
                                <tr>
                                    <td>
                                        <div class="dropdown">
                                            <button class="btn btn-sm btn-outline-secondary"
                                                type="button" data-bs-toggle="dropdown">
                                                <i class="ri-more-2-fill"></i>
                                            </button>
                                            <ul class="dropdown-menu">
                                                <li>
                                                    <form action="{{ url('request_access_approved/' . $access->id) }}" method="post">
                                                        @csrf
                                                        <input type="hidden" name="status" value="1">
                                                        <button type="submit" class="dropdown-item">
                                                            <i class="ri-check-line me-2"></i> Approved
                                                        </button>
                                                    </form>
                                                </li>
                                                <li>
                                                    <form action="{{ url('request_access_declined/' . $access->id) }}" method="post">
                                                        @csrf
                                                        <input type="hidden" name="status" value="3">
                                                        <button type="submit" class="dropdown-item">
                                                            <i class="ri-close-line me-2"></i> Declined
                                                        </button>
                                                    </form>
                                                </li>
                                            </ul>
                                        </div>
                                    </td>
                                    <td>
                                        {{ $access->requestor->name ?? '—' }}
                                    </td>
                                    <td>
                                        @if($access->requestor && $access->requestor->department)
                                            <span class="badge bg-info-subtle text-info">
                                                <i class="ri-building-line me-1"></i>
                                                {{ $access->requestor->department->name }}
                                            </span>
                                        @else
                                            <span class="text-muted">—</span>
                                        @endif
                                    </td>
                                    <td>{{ $access->document->title ?? '—' }}</td>
                                    <td>{{ date('M d Y', strtotime($access->date)) }}</td>
                                    <td>{{ $access->reason }}</td>
                                </tr>
                            @endforeach

                            @if($document_request_access->where('status', 0)->isEmpty())
                                <tr>
                                    <td colspan="6" class="text-center text-muted py-4">
                                        No pending access requests.
                                    </td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection