@extends('layouts.header')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <h4 class="mb-0">Type of Documents</h4>
        <p class="text-muted mb-0">Manage document types for categorization and organization</p>
    </div>
</div>

<div class="row g-3 mb-4 h-100">
    <div class="col-xl-4 col-md-4">
        <div class="dashboard-card pending">
            <div class="icon-circle"><i class="fa fa-file-text-o"></i></div>
            <h2 class="mb-0 font-weight-bold">{{ $total }}</h2>
            <p>Total Type of Documents</p>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12 mb-5">
        <div class="card shadow-sm">
            <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
                <h5 class="mb-0">Type of Documents</h5>
                <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addDocumentTypeModal">
                    <i class="fa fa-plus"></i> Add Type
                </button>
            </div>
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
                    <div class="d-flex align-items-center gap-2 flex-wrap">
                        <div id="table-filter-control"></div>
                        <div id="table-length-control"></div>
                    </div>
                    <div id="table-buttons-control"></div>
                </div>

                <div class="table-scroll-container">
                    <table class="table table-hover table-bordered" id="documentTypesTable">
                        <thead class="table-light">
                            <tr>
                                <th>Actions</th>
                                <th>Name</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>

                <div class="bottom-controls-container">
                    <div id="table-info-control"></div>
                    <div id="table-pagination-control"></div>
                </div>
            </div>
        </div>
    </div>
</div>

@include('settings.documents_type.edit');
@include('settings.documents_type.new');
@endsection

@section('js')
<script>
$(document).ready(function () {

    var table = $('#documentTypesTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: '{{ route("document-types.data") }}',
            type: 'GET',
            error: function (xhr) {
                console.error(xhr.status, xhr.responseText);
                Swal.fire({ icon: 'error', title: 'Error', text: 'Failed to load data.' });
            }
        },
        columns: [
            { data: 'action', orderable: false, searchable: false },
            { data: 'name',   name: 'name' },
        ],
        pageLength: 10,
        lengthMenu: [[10, 25, 50], [10, 25, 50]],
        responsive: true,
        dom: 'lBfrtip',
        buttons: [
            { extend: 'copy',  text: 'Copy'  },
            { extend: 'excel', text: 'Excel' }
        ],
        order: [[1, 'asc']],
        language: {
            processing:   '<div style="text-align:center;"><i class="fa fa-spinner fa-spin fa-2x"></i><br><span style="margin-top:10px;display:block;">Loading...</span></div>',
            emptyTable:   "No document types found",
            zeroRecords:  "No matching records found",
            lengthMenu:   "Show _MENU_ entries",
            info:         "Showing _START_ to _END_ of _TOTAL_ entries",
            infoEmpty:    "Showing 0 to 0 of 0 entries",
            infoFiltered: "(filtered from _MAX_ total entries)",
            search:       "Search:",
            paginate: { first: "First", last: "Last", next: "Next", previous: "Previous" }
        },
        drawCallback: function () { moveControls(); },
        initComplete: function () {
            var inp = $('#documentTypesTable_filter input');
            inp.unbind();
            var t;
            inp.on('input', function () {
                var v = $(this).val();
                clearTimeout(t);
                t = setTimeout(function () { table.search(v).draw(); }, 500);
            });
        }
    });

    function moveControls() {
        var wrapper = $('#documentTypesTable_wrapper');

        var length = wrapper.find('.dataTables_length');
        if (length.length) $('#table-length-control').empty().append(length.detach());

        var filter = wrapper.find('.dataTables_filter');
        if (filter.length) {
            var inp = filter.find('input');
            var hasFocus  = inp.is(':focus');
            var cursorPos = inp[0] ? inp[0].selectionStart : null;
            $('#table-filter-control').empty().append(filter.detach());
            if (hasFocus) {
                var newInp = $('#documentTypesTable_filter input');
                newInp.focus();
                if (cursorPos !== null) newInp[0].setSelectionRange(cursorPos, cursorPos);
            }
        }

        var buttons = wrapper.find('.dt-buttons');
        if (buttons.length) $('#table-buttons-control').empty().append(buttons.detach());

        var info = wrapper.find('.dataTables_info');
        if (info.length) $('#table-info-control').empty().append(info.detach());

        var paginate = wrapper.find('.dataTables_paginate');
        if (paginate.length) $('#table-pagination-control').empty().append(paginate.detach());
    }

    setTimeout(function () { moveControls(); }, 100)
    $(window).on('resize', function () { moveControls(); });

    $(document).on('click', '.edit-btn', function () {
        var id   = $(this).data('id');
        var name = $(this).data('name');
        $('#editName').val(name);
        $('#editDocumentTypeForm').attr('action', '/documents_type/update/' + id);
    });

    $(document).on('click', '.delete-btn', function () {
        var id   = $(this).data('id');
        var name = $(this).data('name');

        Swal.fire({
            title: 'Delete "' + name + '"?',
            text: 'This action cannot be undone.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Yes, delete it!',
            cancelButtonText: 'Cancel'
        }).then(function (result) {
            if (result.isConfirmed) {
                var form = $('<form method="POST" style="display:none;">'
                    + '<input type="hidden" name="_token" value="{{ csrf_token() }}">'
                    + '</form>');
                form.attr('action', '/documents_type/delete/' + id);
                $('body').append(form);
                form.submit();
            }
        });
    });

});
</script>
@endsection