@extends('layouts.header')
@section("css")
<link href="{{ asset('login_css/css/plugins/chosen/bootstrap-chosen.css') }}" rel="stylesheet">
@endsection

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <h4 class="mb-0">Activity Logs</h4>
        <p class="text-muted mb-0">Manage user logs</p>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="card shadow-sm">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3">
                        <label for="" class="form-label">Date</label>
                        <input type="date" name="date" class="form-control">
                    </div>
                    <div class="col-md-3">
                        <label for="" class="form-label">Event</label>
                        <select name="event" class="form-control">
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="" class="form-label">User</label>
                        <select name="user" class="form-control">
                            <option value=""></option>
                        </select>
                    </div>
                    <div class="col-md-1">
                        <label for="" class="form-label">&nbsp;</label>
                        <input type="button" value="Filter" id="filterButton" class="form-control btn btn-primary">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-12 mb-5">
        <div class="card shadow-sm">
            <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
                <h5 class="mb-0">Activity Logs</h5>
            </div>
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
                    <div class="d-flex align-items-center gap-2 flex-wrap">
                        <div id="table-filter-control"></div>
                        {{-- <select id="roleFilter" class="form-select form-select-sm" style="width:auto;min-width:160px;">
                            <option value="">All Roles</option>
                            @foreach($roles as $role)
                                <option value="{{ $role->name }}">{{ $role->name }}</option>
                            @endforeach
                        </select> --}}
                        <div id="table-length-control"></div>
                    </div>
                    <div id="table-buttons-control"></div>
                </div>

                <div class="table-responsive">
                    <table class="table table-hover table-bordered" id="activityLogsTable">
                        <thead class="table-light">
                            <tr>
                                <th>Timestamp</th>
                                <th>User</th>
                                <th>Action</th>
                                {{-- <th>Field</th> --}}
                                <th>Old Value</th>
                                <th>New Value</th>
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
@endsection

@section('js')
<script src="{{ asset("js/ajaxRequest.js") }}"></script>
<script src="{{ asset('login_css/js/plugins/chosen/chosen.jquery.js') }}"></script>
<script>
function getEvents() {
    ajaxRequest({
        type:"GET",
        url: "{{ url('get-events') }}",
        success: function(response) {
            var option = '<option></option>'
            response.forEach((res) => {
                option+= `<option value="${res}">${res}</option>`
            })
            $("[name='event']").html(option)
            $("[name='event']").chosen({width: "100%"})
        }
    })
}

function getActiveUsers() {
    ajaxRequest({
        type:"GET",
        url: "{{ url('get-active-users') }}",
        success: function(response) {
            var option = '<option></option>'
            response.forEach((res, key) => {
                option+= `<option value="${res.id}">${res.name}</option>`
            })
            $("[name='user']").html(option)
            $("[name='user']").chosen({width: "100%"})
        }
    })
}

$(document).ready(function () {
    getEvents()
    getActiveUsers()

    var currentRoleFilter = '';

    var table = $('#activityLogsTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: '{{ route("logs-data") }}',
            type: 'GET',
            data: function (d) { 
                d.date_filter = $("[name='date']").val(),
                d.event_filter = $("[name='event']").val(),
                d.user_filter = $("[name='user']").val()
            },
            // error: function (xhr) { console.error(xhr.status, xhr.responseText); }
        },
        columns: [
            {
                data: 'created_at', name: 'created_at',
                render: function(data, type, row) {
                    return row.created_at
                }
            },
            { 
                data: 'user', 
                name: 'user',
                render: function(data, type,row) {
                    if (data) {
                        return data
                    }
                    else {
                        return ""
                    }
                    
                }
            },
            { data: 'event', name: 'event'},
            { data: 'old_values', name: 'old_values' },
            { data: 'new_values', name: 'new_values' },
        ],
        pageLength: 10,
        lengthMenu: [[10, 25, 50, 100], [10, 25, 50, 100]],
        dom: 'lBfrtip',
        buttons: [
            { extend: 'copy', text: 'Copy', className: 'btn btn-sm btn-secondary' },
            { extend: 'excel', text: 'Excel', title: 'Users', className: 'btn btn-sm btn-secondary' },
        ],
        order: [[0, 'desc']],
        language: {
            processing: '<div style="text-align:center;"><i class="fa fa-spinner fa-spin fa-2x"></i><br><span style="margin-top:10px;display:block;">Loading...</span></div>',
            emptyTable: "No activity logs found",
            zeroRecords: "No matching activity logs found",
            lengthMenu: "Show _MENU_ entries",
            info: "Showing _START_ to _END_ of _TOTAL_ entries",
            infoEmpty: "Showing 0 to 0 of 0 entries",
            infoFiltered: "(filtered from _MAX_ total entries)",
            search: "Search:",
            paginate: { first: "First", last: "Last", next: "Next", previous: "Previous" }
        },
        drawCallback: function () {
            moveControls();
        },
        initComplete: function () {
            var inp = $('#activityLogsTable_filter input');
            inp.unbind();
            var t;
            inp.on('input', function () {
                var v = $(this).val();
                clearTimeout(t);
                t = setTimeout(function () { table.search(v).draw(); }, 500);
            });
        },
    });

    function moveControls() {
        var wrapper = $('#activityLogsTable_wrapper');

        var length = wrapper.find('.dataTables_length');
        if (length.length) $('#table-length-control').empty().append(length.detach());

        var filter = wrapper.find('.dataTables_filter');
        if (filter.length) {
            var inp = filter.find('input');
            var hasFocus = inp.is(':focus');
            var curPos = inp[0] ? inp[0].selectionStart : null;
            $('#table-filter-control').empty().append(filter.detach());
            if (hasFocus) {
                var newInp = $('#activityLogsTable_filter input');
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

    // $('#roleFilter').on('change', function () {
    //     currentRoleFilter = $(this).val();
    //     table.ajax.reload();
    // });

    setTimeout(function () { moveControls(); }, 100);
    $(window).on('resize', function () { moveControls(); });

    // $('.cat').chosen({ width: "100%" });

    $("#filterButton").on("click", function() {
        table.ajax.reload()
    })
});
</script>
@endsection