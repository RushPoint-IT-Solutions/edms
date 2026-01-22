@extends('layouts.header')

@section('css')
<link href="{{ asset('login_css/css/plugins/chosen/bootstrap-chosen.css') }}" rel="stylesheet">
<link href="{{ asset('login_css/css/plugins/sweetalert/sweetalert.css') }}" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
@endsection

@section('content')
<div class="container-fluid py-4">
    <div class="chat-wrapper d-lg-flex gap-1 mx-n4 mt-n4 p-1">
        <div class="file-manager-sidebar minimal-border">
            <div class="p-3 d-flex flex-column h-100">
                <div class="mb-3 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-semibold">Documents</h5>
                    <button type="button" class="btn btn-sm btn-success" data-bs-toggle="dropdown">
                        <i class="ri-add-line"></i>
                        New
                    </button>
                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                        <a class="dropdown-item" href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#uploadDocument">New file</a>
                        <a class="dropdown-item" href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#createFolderModal">New folder</a>
                    </div>
                </div>
                <div class="search-box">
                    <input type="text" class="form-control bg-light border-light"
                        placeholder="Search here...">
                    <i class="ri-search-2-line search-icon"></i>
                </div>
                <div class="mt-3 mx-n4 px-4 file-menu-sidebar-scroll" data-simplebar>
                    <ul class="list-unstyled file-manager-menu">
                        @foreach($document_folders->where('parent_id', null) as $folder)
                            {{-- <li>
                                <a class="fs-4 text-dark" data-bs-toggle="collapse" href="#folder{{ $folder->id }}">
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
                            </li> --}}
                            <li>
                                <div class="d-flex justify-content-between align-items-center flex-row">
                                    <a href="{{ url('/documents/folder/'.$folder->id) }}" class="fs-5 text-dark">
                                        <i class="ri-folder-2-line align-bottom me-2"></i> 
                                        <span class="file-list-link">{{ $folder->name }}</span>
                                    </a>
                                    
                                    @if(count($folder->childrenFolder) > 0 || count($folder->document) > 0)
                                    <a data-bs-toggle="collapse" href="#folder{{ $folder->id }}">
                                        <i class="ri-arrow-down-s-line fs-5 arrow-icon ms-5"></i>
                                    </a>
                                    @endif
                                </div>
                                
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
            <div class="mx-n3 pt-4 px-4 file-manager-content-scroll" data-simplebar>
                <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">
                    {{-- <div class="d-flex gap-2 align-items-center">
                        <select class="filter-select form-select" data-choices data-choices-search-false name="choices-single-default" id="file-type">
                            <option value="">File Type</option>
                            <option value="All" selected>All</option>
                            <option value="Video">Video</option>
                            <option value="Images">Images</option>
                            <option value="Music">Music</option>
                            <option value="Documents">Documents</option>
                        </select>
                        <button class="btn-create" data-bs-toggle="modal" data-bs-target="#createFolderModal" style="width:280px;">
                            <i class="ri-add-line"></i> Create Folders
                        </button>
                    </div> --}}
                </div>
                
                <div class="row g-3" id="folderlist-data">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th></th>
                                <th>Name</th>
                                <th>File type</th>
                                <th>Modified</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($document_folders->where('parent_id', null) as $folder)
                                <tr class="demoTableRow" data-folder-id="{{ $folder->id }}">
                                    <td>
                                        <div class="d-flex justify-content-between align-items-start">
                                            <div class="form-check mb-2">
                                                <input class="form-check-input" type="checkbox" id="formCheck1">
                                            </div>
                                            <i class="ri-folder-2-fill"></i>
                                        </div>
                                    </td>
                                    <td>
                                        <a href="{{ url('documents/folder/'.$folder->id) }}">
                                            {{ $folder->name }}
                                        </a>
                                    </td>
                                    <td>Folder</td>
                                    <td>{{ date('M d, Y', strtotime($folder->updated_at)) }}</td>
                                </tr>
                            @endforeach
                            @foreach ($documents->where('folder_id', null) as $document)
                                <tr>
                                    <td>
                                        <div class="d-flex justify-content-between align-items-start">
                                            <div class="form-check mb-2">
                                                <input class="form-check-input" type="checkbox" id="formCheck1">
                                            </div>
                                            <i class=" ri-file-list-line"></i>
                                        </div>
                                    </td>
                                    <td>
                                        <a href="{{ url('/documents/view-document/'.$document->id) }}"  target="_blank">
                                            {{ $document->title }}
                                        </a>
                                    </td>
                                    <td>Docx</td>
                                    <td>{{ date('M d, Y', strtotime($document->updated_at)) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@include('documents.upload_document')
@include('documents.add_folder')
@include('documents.add_documents_in_folder')
@foreach ($document_folders as $folder)
    @include('documents.rename_folder')
@endforeach
@endsection

@section('js')
<script src="{{ asset('login_css/js/plugins/dataTables/datatables.min.js')}}"></script>
<script src="{{ asset('login_css/js/plugins/chosen/chosen.jquery.js') }}"></script>
<script src="{{ asset('login_css/js/plugins/sweetalert/sweetalert.min.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="{{ asset('js/BootstrapMenu.min.js') }}"></script>

<script>
    // function public_info(value, id) {
    //     console.log(value.checked);
    //     $.ajax({
    //         dataType: 'json',
    //         type: 'POST',
    //         url: '{{url("/change-public")}}',
    //         data: {id: id, value: value.checked},
    //         headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
    //     }).done(function(data) {
    //         console.log(data);
    //     }).fail(function(data) {
    //         console.error(data);
    //     });
    // }

    function deleteFolder() {
        event.preventDefault()
        document.getElementById('deleteFolderForm').submit()
    }

    $(document).ready(function() {
        $('.cat').chosen({width: "100%"});
        
        $('.tables').DataTable({
            pageLength: 10,
            responsive: true,
            // dom: '<"html5buttons"B>lTfg<"bottom-controls"t<"info-paginate"ip>>', 
            // buttons: [
            //     {extend: 'copy'},
            //     {extend: 'csv'},
            //     {extend: 'excel', title: 'Documents'},
            //     {extend: 'pdf', title: 'Documents'},
            //     {extend: 'print',
            //      customize: function (win) {
            //         $(win.document.body).addClass('white-bg');
            //         $(win.document.body).css('font-size', '10px');
            //         $(win.document.body).find('table')
            //             .addClass('compact')
            //             .css('font-size', 'inherit');
            //     }
            //     }
            // ]
        });

        // Custom search functionality
        $('#tableSearch').on('keyup', function() {
            $('.tables').DataTable().search(this.value).draw();
        });

        // Custom entries per page
        $('#entriesPerPage').on('change', function() {
            $('.tables').DataTable().page.len(this.value).draw();
        });

        $('.select2').select2({
            dropdownParent: $('#addDocumentInFolder'),
            theme: "classic"
        })

        var menu = new BootstrapMenu('.demoTableRow', {
            fetchElementData: function ($rowElem) {
                return {
                    id: $rowElem.data('folder-id')
                };
            },

            actions: {
                renameFolder: {
                    name: 'Rename folder',
                    iconClass: 'fa-pencil',
                    onClick: function (folder) {
                        // renameFolder(folder.id);
                        $("#renameFolderModal"+folder.id).modal("show")
                    }
                },
                moveFileFolder: {
                    name: 'Move file',
                    iconClass: "ri-drag-move-2-line",
                    onClick: function(folder) {
                        $("#addDocumentInFolder").modal("show")
                        $("#moveDocumentFolder").val(folder.id)
                    }
                }
            }
        });
    });
</script>
@endsection