@extends('layouts.header')

@section('css')
<link href="{{ asset('login_css/css/plugins/chosen/bootstrap-chosen.css') }}" rel="stylesheet">
<link href="{{ asset('login_css/css/plugins/sweetalert/sweetalert.css') }}" rel="stylesheet">
<link href="{{ asset('login_css/css/plugins/dataTables/datatables.min.css') }}" rel="stylesheet">

<style>
    .btn-md {
        border: none !important;
    }

    .filter-card {
        background: white;
        border-radius: 10px;
        padding: 25px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        margin-bottom: 20px;
    }

    .filter-row {
        display: flex;
        gap: 35px;
        align-items: flex-end;
    }

    .filter-col {
        flex: 1;
        min-width: 180px;
    }

    .filter-col label {
        display: block;
        font-size: 12px;
        font-weight: 500;
        color: #495057;
        margin-bottom: 6px;
    }

    .filter-col .form-control,
    .filter-col .chosen-container {
        width: 200px !important;
    }

    .filter-col .form-control {
        padding: 8px 12px;
        border: 1px solid #dee2e6;
        border-radius: 4px;
        font-size: 14px;
        height: 38px;
    }

    .filter-col .chosen-container-single .chosen-single {
        height: 38px;
        line-height: 38px;
        padding: 0 12px;
        border-radius: 4px;
        border: 1px solid #dee2e6;
        font-size: 14px;
    }

    .btn-search-primary {
        background: #0d6efd;
        color: white;
        border: none;
        padding: 8px 20px;
        border-radius: 4px;
        font-size: 14px;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.3s;
        display: flex;
        align-items: center;
        gap: 6px;
        white-space: nowrap;
        height: 38px;
    }

    .btn-search-primary:hover {
        background: #0b5ed7;
    }

    .btn-search-primary i {
        font-size: 16px;
    }

    .results-card {
        background: white;
        border-radius: 10px;
        padding: 25px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        margin-bottom: 20px;
    }

    .results-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
        padding-bottom: 15px;
        border-bottom: 2px solid #f1f3f5;
    }

    .results-header h5 {
        margin: 0;
        font-size: 18px;
        font-weight: 600;
        color: #2c3e50;
    }

    .result-item {
        padding: 20px;
        border-bottom: 1px solid #f1f3f5;
        transition: all 0.3s;
    }

    .result-item:hover {
        background: #f8f9fa;
        border-radius: 8px;
    }

    .result-item:last-child {
        border-bottom: none;
    }

    .result-title-row {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-bottom: 12px;
        flex-wrap: wrap;
    }

    .result-link {
        font-size: 16px;
        font-weight: 600;
        color: #0d6efd;
        text-decoration: none;
        transition: color 0.3s;
    }

    .result-link:hover {
        color: #0b5ed7;
    }

    .old-code {
        color: #6c757d;
        font-style: italic;
        font-size: 14px;
        font-weight: 400;
    }

    .access-badge {
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.3px;
    }

    .access-badge.private {
        background: #fff3cd;
        color: #856404;
    }

    .access-badge.public {
        background: #d1e7dd;
        color: #0f5132;
    }

    .result-details {
        display: grid;
        gap: 8px;
        color: #495057;
        font-size: 14px;
        line-height: 1.6;
    }

    .detail-row {
        display: flex;
        gap: 8px;
    }

    .detail-label {
        font-weight: 600;
        color: #2c3e50;
        min-width: 140px;
    }

    .owner-badge {
        background: #e7f3ff;
        color: #0066cc;
        padding: 3px 10px;
        border-radius: 4px;
        font-size: 12px;
        font-weight: 500;
        display: inline-block;
    }

    .sidebar-card {
        background: white;
        border-radius: 10px;
        padding: 25px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        position: sticky;
        top: 20px;
    }

    .sidebar-header {
        margin-bottom: 20px;
        padding-bottom: 15px;
        border-bottom: 2px solid #f1f3f5;
    }

    .sidebar-header h5 {
        margin: 0;
        font-size: 18px;
        font-weight: 600;
        color: #2c3e50;
    }

    .table-responsive {
        overflow-x: auto;
        margin-top: 15px;
    }

    .modern-table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
    }

    .modern-table thead th {
        background: #f8f9fa;
        color: #495057;
        font-weight: 600;
        font-size: 12px;
        text-transform: uppercase;
        padding: 12px;
        border-bottom: 2px solid #dee2e6;
        white-space: nowrap;
    }

    .modern-table tbody td {
        padding: 12px;
        border-bottom: 1px solid #f1f3f5;
        vertical-align: middle;
        font-size: 13px;
    }

    .modern-table tbody tr:hover {
        background: #f8f9fa;
    }

    .modern-table a {
        color: #0d6efd;
        text-decoration: none;
        transition: color 0.3s;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .modern-table a:hover {
        color: #0b5ed7;
    }

    .modern-table a i {
        font-size: 14px;
    }

    /* Empty State */
    .empty-state {
        text-align: center;
        padding: 60px 20px;
        color: #6c757d;
    }

    .empty-state i {
        font-size: 64px;
        margin-bottom: 20px;
        color: #dee2e6;
    }

    .empty-state h6 {
        font-size: 16px;
        font-weight: 600;
        margin-bottom: 8px;
        color: #495057;
    }

    .empty-state p {
        font-size: 14px;
        margin: 0;
    }

    .dataTables_wrapper .dataTables_length,
    .dataTables_wrapper .dataTables_filter,
    .dataTables_wrapper .dataTables_info,
    .dataTables_wrapper .dataTables_paginate {
        font-size: 13px;
    }

    .dataTables_wrapper .dataTables_length select,
    .dataTables_wrapper .dataTables_filter input {
        padding: 6px 12px;
        border: 1px solid #dee2e6;
        border-radius: 4px;
        font-size: 13px;
    }

    .dataTables_wrapper .dataTables_paginate .paginate_button {
        padding: 6px 12px;
        margin: 0 2px;
        border: 1px solid #dee2e6;
        border-radius: 4px;
        background: white;
    }

    .dataTables_wrapper .dataTables_paginate .paginate_button.current {
        background: #0d6efd;
        color: white !important;
        border-color: #0d6efd;
    }

    .dataTables_wrapper .dataTables_paginate .paginate_button:hover {
        background: #e9ecef;
        border-color: #dee2e6;
        color: #0d6efd !important;
    }

     
    @media (max-width: 768px) {
        .filter-row {
            flex-direction: column;
            gap: 20px;
        }

        .filter-col {
            min-width: unset;
            width: 100%;
        }

        .filter-col .form-control,
        .filter-col .chosen-container {
            width: 100% !important;
        }

        .btn-search-primary {
            width: 100%;
            justify-content: center;
        }

        .results-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 10px;
        }

        .result-title-row {
            flex-direction: column;
            align-items: flex-start;
            gap: 8px;
        }

        .detail-row {
            flex-direction: column;
            gap: 4px;
        }

        .detail-label {
            min-width: unset;
        }

        .sidebar-card {
            position: static;
        }
    }
</style>
@endsection

@section('content')
 
<div class="row mb-4">
    <div class="col-12">
        <h4 class="mb-0">Search Documents</h4>
        <p class="text-muted mb-0">Find and access your documents quickly</p>
    </div>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="filter-card">
            <form action="" method="get">
                <div class="filter-row">
                    <div class="filter-col">
                        <label>Company</label>
                        <select name='company' class="form-control cat">
                            <option value="">All Companies</option>
                            @foreach($companies as $company)
                                <option value='{{$company->id}}' @if($comp == $company->id) selected @endif>
                                    {{$company->name}} - {{$company->code}}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="filter-col">
                        <label>Department</label>
                        <select name='department' class="form-control cat">
                            <option value="">All Departments</option>
                            @foreach($departments as $dep)
                                <option value='{{$dep->id}}' @if($dept == $dep->id) selected @endif>
                                    {{$dep->code}} - {{$dep->name}}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="filter-col">
                        <label>Search Text</label>
                        <input type="text" placeholder="Document Title / Control Code / Old Code" 
                               name="search" value="{{$search}}" class="form-control">
                    </div>

                    <div class="filter-col" style="flex: 0 0 auto;">
                        <button class="btn-search-primary" type="submit">
                            <i class="ri-search-line"></i>
                            Search
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <div class="results-card">
            <div class="results-header">
                <h5>Search Results</h5>
            </div>

            @if($documents)
                @php
                    $hasResults = false;
                @endphp

                {{-- PROCEDURE --}}
                @if($comp == 2 || $whiDept != null)
                    @foreach($documents->where('category', 'PROCEDURE') as $document)
                        @php $hasResults = true; @endphp
                        <div class="result-item">
                            <div class="result-title-row">
                                <a href="{{url('view-document/'.$document->id)}}" target="_blank" class="result-link">
                                    <span class="old-code">({{$document->old_control_code}})</span> 
                                    {{$document->control_code}} Rev. {{$document->version}}
                                </a>
                                @if($document->public == null)
                                    <span class="access-badge private">Private</span>
                                @else
                                    <span class="access-badge public">Public</span>
                                @endif
                            </div>
                            <div class="result-details">
                                <div class="detail-row">
                                    <span class="detail-label">Title:</span>
                                    <span>{{$document->title}}</span>
                                </div>
                                <div class="detail-row">
                                    <span class="detail-label">Process Owner:</span>
                                    <span>
                                        @if($document->process_owner != null)
                                            <span class="owner-badge">{{$document->processOwner->name}}</span>
                                        @else 
                                            <span class="owner-badge">{{$document->department->dep_head->name}}</span>
                                        @endif
                                    </span>
                                </div>
                                <div class="detail-row">
                                    <span class="detail-label">Date Effective:</span>
                                    <span>{{date('M d, Y',strtotime($document->updated_at))}}</span>
                                </div>
                                <div class="detail-row">
                                    <span class="detail-label">Company:</span>
                                    <span>{{$document->department->name}}</span>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @endif

                {{-- POLICY --}}
                @foreach($documents->where('category', 'POLICY') as $document)
                    @php $hasResults = true; @endphp
                    <div class="result-item">
                        <div class="result-title-row">
                            <a href="{{url('view-document/'.$document->id)}}" target="_blank" class="result-link">
                                <span class="old-code">({{$document->old_control_code}})</span> 
                                {{$document->control_code}} Rev. {{$document->version}}
                            </a>
                            @if($document->public == null)
                                <span class="access-badge private">Private</span>
                            @else
                                <span class="access-badge public">Public</span>
                            @endif
                        </div>
                        <div class="result-details">
                            <div class="detail-row">
                                <span class="detail-label">Title:</span>
                                <span>{{$document->title}}</span>
                            </div>
                            <div class="detail-row">
                                <span class="detail-label">Process Owner:</span>
                                <span>
                                    @if($document->process_owner != null)
                                        <span class="owner-badge">{{$document->processOwner->name}}</span>
                                    @else 
                                        <span class="owner-badge">{{$document->department->dep_head->name}}</span>
                                    @endif
                                </span>
                            </div>
                            <div class="detail-row">
                                <span class="detail-label">Date Effective:</span>
                                <span>{{date('M d, Y',strtotime($document->updated_at))}}</span>
                            </div>
                            <div class="detail-row">
                                <span class="detail-label">Company:</span>
                                <span>{{$document->department->name}}</span>
                            </div>
                        </div>
                    </div>
                @endforeach

                {{-- FORM --}}
                @foreach($documents->where('category', 'FORM') as $document)
                    @php $hasResults = true; @endphp
                    <div class="result-item">
                        <div class="result-title-row">
                            <a href="{{url('view-document/'.$document->id)}}" target="_blank" class="result-link">
                                <span class="old-code">({{$document->old_control_code}})</span> 
                                {{$document->control_code}} Rev. {{$document->version}}
                            </a>
                            @if($document->public == null)
                                <span class="access-badge private">Private</span>
                            @else
                                <span class="access-badge public">Public</span>
                            @endif
                        </div>
                        <div class="result-details">
                            <div class="detail-row">
                                <span class="detail-label">Title:</span>
                                <span>{{$document->title}}</span>
                            </div>
                            <div class="detail-row">
                                <span class="detail-label">Process Owner:</span>
                                <span>
                                    @if($document->process_owner != null)
                                        <span class="owner-badge">{{$document->processOwner->name}}</span>
                                    @else 
                                        <span class="owner-badge">{{$document->department->dep_head->name}}</span>
                                    @endif
                                </span>
                            </div>
                            <div class="detail-row">
                                <span class="detail-label">Date Effective:</span>
                                <span>{{date('M d, Y',strtotime($document->updated_at))}}</span>
                            </div>
                            <div class="detail-row">
                                <span class="detail-label">Company:</span>
                                <span>{{$document->department->name}}</span>
                            </div>
                        </div>
                    </div>
                @endforeach

                {{-- TEMPLATE --}}
                @foreach($documents->where('category', 'TEMPLATE') as $document)
                    @php $hasResults = true; @endphp
                    <div class="result-item">
                        <div class="result-title-row">
                            <a href="{{url('view-document/'.$document->id)}}" target="_blank" class="result-link">
                                <span class="old-code">({{$document->old_control_code}})</span> 
                                {{$document->control_code}} Rev. {{$document->version}}
                            </a>
                            @if($document->public == null)
                                <span class="access-badge private">Private</span>
                            @else
                                <span class="access-badge public">Public</span>
                            @endif
                        </div>
                        <div class="result-details">
                            <div class="detail-row">
                                <span class="detail-label">Title:</span>
                                <span>{{$document->title}}</span>
                            </div>
                                {{-- Process Owner : @if(count($document->department->drc) != 0) @foreach($document->department->drc as $drc) <small class="label label-info"> {{$drc->name}} </small> @endforeach @else <small class="label label-danger">No Process Owner</small>  @endif --}}
                            <div class="detail-row">
                                <span class="detail-label">Process Owner:</span>
                                <span>
                                    @if($document->process_owner != null)
                                        <span class="owner-badge">{{$document->processOwner->name}}</span>
                                    @else 
                                        <span class="owner-badge">{{$document->department->dep_head->name}}</span>
                                    @endif
                                </span>
                            </div>
                            <div class="detail-row">
                                <span class="detail-label">Date Effective:</span>
                                <span>{{date('M d, Y',strtotime($document->updated_at))}}</span>
                            </div>
                            <div class="detail-row">
                                <span class="detail-label">Company:</span>
                                <span>{{$document->department->name}}</span>
                            </div>
                        </div>
                    </div>
                @endforeach

                {{-- ANNEX --}}
                @foreach($documents->where('category', 'ANNEX') as $document)
                    @php $hasResults = true; @endphp
                    <div class="result-item">
                        <div class="result-title-row">
                            <a href="{{url('view-document/'.$document->id)}}" target="_blank" class="result-link">
                                <span class="old-code">({{$document->old_control_code}})</span> 
                                {{$document->control_code}} Rev. {{$document->version}}
                            </a>
                            @if($document->public == null)
                                <span class="access-badge private">Private</span>
                            @else
                                <span class="access-badge public">Public</span>
                            @endif
                        </div>
                        <div class="result-details">
                            <div class="detail-row">
                                <span class="detail-label">Title:</span>
                                <span>{{$document->title}}</span>
                            </div>
                                {{-- Process Owner : @if(count($document->department->drc) != 0) @foreach($document->department->drc as $drc) <small class="label label-info"> {{$drc->name}} </small> @endforeach @else <small class="label label-danger">No Process Owner</small>  @endif --}}
                            <div class="detail-row">
                                <span class="detail-label">Process Owner:</span>
                                <span>
                                    @if($document->process_owner != null)
                                        <span class="owner-badge">{{$document->processOwner->name}}</span>
                                    @else 
                                        <span class="owner-badge">{{$document->department->dep_head->name}}</span>
                                    @endif
                                </span>
                            </div>
                            <div class="detail-row">
                                <span class="detail-label">Date Effective:</span>
                                <span>{{date('M d, Y',strtotime($document->updated_at))}}</span>
                            </div>
                            <div class="detail-row">
                                <span class="detail-label">Company:</span>
                                <span>{{$document->department->name}}</span>
                            </div>
                        </div>
                    </div>
                @endforeach

                {{-- ALL --}}
                @php
                    $category = array('POLICY', 'FORM', 'TEMPLATE', 'ANNEX');
                @endphp
                @foreach($documents->whereNotIn('category', $category) as $document)
                    @php $hasResults = true; @endphp
                    <div class="result-item">
                        <div class="result-title-row">
                            <a href="{{url('view-document/'.$document->id)}}" target="_blank" class="result-link">
                                <span class="old-code">({{$document->old_control_code}})</span> 
                                {{$document->control_code}} Rev. {{$document->version}}
                            </a>
                            @if($document->public == null)
                                <span class="access-badge private">Private</span>
                            @else
                                <span class="access-badge public">Public</span>
                            @endif
                        </div>
                        <div class="result-details">
                            <div class="detail-row">
                                <span class="detail-label">Title:</span>
                                <span>{{$document->title}}</span>
                            </div>
                                {{-- Process Owner : @if(count($document->department->drc) != 0) @foreach($document->department->drc as $drc) <small class="label label-info"> {{$drc->name}} </small> @endforeach @else <small class="label label-danger">No Process Owner</small>  @endif --}}
                            <div class="detail-row">
                                <span class="detail-label">Process Owner:</span>
                                <span>
                                    @if($document->process_owner != null)
                                        <span class="owner-badge">{{$document->processOwner->name}}</span>
                                    @else 
                                        <span class="owner-badge">{{$document->department->dep_head->name}}</span>
                                    @endif
                                </span>
                            </div>
                            <div class="detail-row">
                                <span class="detail-label">Date Effective:</span>
                                <span>{{date('M d, Y',strtotime($document->updated_at))}}</span>
                            </div>
                            <div class="detail-row">
                                <span class="detail-label">Company:</span>
                                <span>{{$document->department->name}}</span>
                            </div>
                        </div>
                    </div>
                @endforeach

                @if(!$hasResults)
                    <div class="empty-state">
                        <i class="ri-search-line"></i>
                        <h6>No documents found</h6>
                        <p>No documents match your search criteria. Try adjusting your filters.</p>
                    </div>
                @endif
            @else
                <div class="empty-state">
                    <i class="ri-folder-open-line"></i>
                    <h6>Start your search</h6>
                    <p>Use the filters above to search for documents.</p>
                </div>
            @endif
        </div>
    </div>

    <div class="col-lg-4 mb-5">
        <div class="sidebar-card">
            <div class="sidebar-header">
                <h5>Public Documents</h5>
            </div>

            <div class="table-responsive">
                <table class="modern-table tables">
                    <thead>
                        <tr>
                            <th>Document</th>
                            <th>Department</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($request_documents as $req_doc)
                            <tr>
                                @php
                                    $attchment = ($req_doc->attachments)->where('type','pdf_copy')->first();
                                @endphp
                                <td>
                                    @if($attchment)
                                        <a href="{{url($attchment->attachment)}}" target="_blank">
                                            <i class="ri-file-pdf-line"></i> 
                                            {{$req_doc->title}}
                                        </a>
                                    @endif
                                </td>
                                <td>
                                    {{$req_doc->department->code}}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
<script src="{{ asset('login_css/js/plugins/dataTables/datatables.min.js')}}"></script>
<script src="{{ asset('login_css/js/plugins/chosen/chosen.jquery.js') }}"></script>

<script>
    $(document).ready(function(){
        $('.tables').DataTable({
            pageLength: 10,
            responsive: true,
            dom: '<"html5buttons"B>lTfg<"bottom-controls"t<"info-paginate"ip>>', 
            buttons: []
        });
    });
</script>
@endsection