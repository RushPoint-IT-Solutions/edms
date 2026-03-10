@extends('layouts.header')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <h4 class="mb-0">Approver Stamp</h4>
        <p class="text-muted mb-0">Manage approver stamps</p>
    </div>
</div>

<div class="row">
    <div class="col-md-12 mb-5">
        <div class="card shadow-sm">
            <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
                <h5 class="mb-0">Approver Stamps</h5>
                <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#upload_stamp">
                    <i class="ri-upload-line"></i> Upload Stamp
                </button>
            </div>
            <div class="card-body">
                <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:8px;">
                    <div></div>
                    <div id="table-filter-control"></div>
                </div>

                <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:15px;">
                    <div id="table-length-control"></div>
                    <div id="table-buttons-control"></div>
                </div>

                <div class="table-scroll-container">
                    <table class="table table-hover table-bordered" id="stampsTable">
                        <thead class="table-light">
                            <tr>
                                <th>Preview</th>
                                <th>File Name</th>
                                <th>User</th>
                                <th>Date Uploaded</th>
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

@include('approver_stamp.upload_stamp')

@endsection

@section('js')
<script>
$(document).ready(function () {

    var table = $('#stampsTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: '{{ route("approver-stamp.data") }}',
            type: 'GET',
            error: function (xhr) {
                console.error(xhr.status, xhr.responseText);
            }
        },
        columns: [
            { data: 'preview', orderable: false, searchable: false },
            { data: 'file_name', name: 'file_name' },
            { data: 'user', orderable: false, searchable: false },
            { data: 'created_at', name: 'created_at' },
        ],
        pageLength: 25,
        lengthMenu: [[10, 25, 50, 100], [10, 25, 50, 100]],
        dom: 'lBfrtip',
        buttons: [
            { extend: 'copy', text: 'Copy' },
            { extend: 'excel', text: 'Excel', title: 'Approver Stamps' },
        ],
        order: [[0, 'desc']],
        language: {
            processing: '<div style="text-align:center;"><i class="fa fa-spinner fa-spin fa-2x"></i><br><span style="margin-top:10px;display:block;">Loading...</span></div>',
            emptyTable: "No stamps found",
            zeroRecords: "No matching stamps found",
            lengthMenu: "Show _MENU_ entries",
            info: "Showing _START_ to _END_ of _TOTAL_ entries",
            infoEmpty: "Showing 0 to 0 of 0 entries",
            infoFiltered: "(filtered from _MAX_ total entries)",
            search: "Search:",
            paginate: { first: "First", last: "Last", next: "Next", previous: "Previous" }
        },
        drawCallback: function () { moveControls(); },
        initComplete: function () {
            var inp = $('#stampsTable_filter input');
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
        var wrapper = $('#stampsTable_wrapper');

        var length = wrapper.find('.dataTables_length');
        if (length.length) $('#table-length-control').empty().append(length.detach());

        var filter = wrapper.find('.dataTables_filter');
        if (filter.length) {
            var inp = filter.find('input');
            var hasFocus = inp.is(':focus');
            var curPos = inp[0] ? inp[0].selectionStart : null;
            $('#table-filter-control').empty().append(filter.detach());
            if (hasFocus) {
                var newInp = $('#stampsTable_filter input');
                newInp.focus();
                if (curPos !== null) newInp[0].setSelectionRange(curPos, curPos);
            }
        }

        var buttons = wrapper.find('.dt-buttons');
        if (buttons.length) $('#table-buttons-control').empty().append(buttons.detach());

        var info = wrapper.find('.dataTables_info');
        if (info.length) $('#table-info-control').empty().append(info.detach());

        var paginate = wrapper.find('.dataTables_paginate');
        if (paginate.length) $('#table-pagination-control').empty().append(paginate.detach());
    }

    setTimeout(function () { moveControls(); }, 100);
    $(window).on('resize', function () { moveControls(); });

});
</script>
@endsection