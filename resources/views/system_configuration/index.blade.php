@extends('layouts.header')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <h4 class="mb-0">System Configuration</h4>
        <p class="text-muted mb-0">Manage departments, offices, document types, and control codes</p>
    </div>
</div>

<div class="row g-3 mb-3">
    <div class="col-12 col-md-3">
        <div class="section-nav-card shadow-sm active" data-target="pane-departments">
            <div class="nav-icon"><i class="ri-community-line"></i></div>
            <div class="nav-label">Departments</div>
            <div class="nav-count">Manage departments</div>
            <button class="btn btn-primary btn-sm mt-2 nav-card-btn" data-bs-toggle="modal" data-bs-target="#new_department">
                <i class="fa fa-plus"></i> New Department
            </button>
        </div>
    </div>
    <div class="col-12 col-md-3">
        <div class="section-nav-card shadow-sm" data-target="pane-teams">
            <div class="nav-icon"><i class="ri-folder-line"></i></div>
            <div class="nav-label">Offices</div>
            <div class="nav-count">Manage offices</div>
            <button class="btn btn-primary btn-sm mt-2 nav-card-btn" data-bs-toggle="modal" data-bs-target="#new_team">
                <i class="fa fa-plus"></i> New Office
            </button>
        </div>
    </div>
    <div class="col-12 col-md-3">
        <div class="section-nav-card shadow-sm" data-target="pane-doctypes">
            <div class="nav-icon"><i class="ri-file-text-line"></i></div>
            <div class="nav-label">Document Types</div>
            <div class="nav-count">Manage document types</div>
            <button class="btn btn-primary btn-sm mt-2 nav-card-btn" data-bs-toggle="modal" data-bs-target="#addDocumentTypeModal">
                <i class="fa fa-plus"></i> Add Type
            </button>
        </div>
    </div>
    <div class="col-12 col-md-3">
        <div class="section-nav-card shadow-sm" data-target="pane-controlcodes">
            <div class="nav-icon"><i class="ri-barcode-line"></i></div>
            <div class="nav-label">Control Codes</div>
            <div class="nav-count">Manage document control codes</div>
            <button class="btn btn-primary btn-sm mt-2 nav-card-btn" data-bs-toggle="modal" data-bs-target="#new_control_code">
                <i class="fa fa-plus"></i> New Control Code
            </button>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">

        <div class="card shadow-sm config-panel active" id="pane-departments">
            <div class="panel-header">
                <div class="panel-icon"><i class="ri-community-line"></i></div>
                <div>
                    <h6>Departments</h6>
                    <p>View and manage all departments</p>
                </div>
            </div>
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div class="d-flex align-items-center gap-2 flex-wrap">
                        <div id="dept-filter-control"></div>
                        <div id="dept-length-control"></div>
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        <div id="dept-buttons-control"></div>
                    </div>
                </div>
                <div class="table-scroll-container">
                    <table class="table table-hover table-bordered" id="departmentsTable">
                        <thead class="table-light">
                            <tr>
                                <th>Action</th>
                                <th>Code</th>
                                <th>Name</th>
                                <th>Department Head</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
                <div class="bottom-controls-container">
                    <div id="dept-info-control"></div>
                    <div id="dept-pagination-control"></div>
                </div>
            </div>
        </div>

        <div class="card shadow-sm config-panel" id="pane-teams">
            <div class="panel-header">
                <div class="panel-icon"><i class="ri-folder-line"></i></div>
                <div>
                    <h6>Offices</h6>
                    <p>View and manage all offices</p>
                </div>
            </div>
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div class="d-flex align-items-center gap-2 flex-wrap">
                        <div id="teams-filter-control"></div>
                        <div id="teams-length-control"></div>
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        <div id="teams-buttons-control"></div>
                    </div>
                </div>
                <div class="table-scroll-container">
                    <table class="table table-hover table-bordered" id="teamsTable">
                        <thead class="table-light">
                            <tr>
                                <th>Action</th>
                                <th>Team Name</th>
                                <th>Created By</th>
                                <th>Department</th>
                                <th>Campus</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
                <div class="bottom-controls-container">
                    <div id="teams-info-control"></div>
                    <div id="teams-pagination-control"></div>
                </div>
            </div>
        </div>

        <div class="card shadow-sm config-panel" id="pane-doctypes">
            <div class="panel-header">
                <div class="panel-icon"><i class="ri-file-text-line"></i></div>
                <div>
                    <h6>Document Types</h6>
                    <p>View and manage document types</p>
                </div>
            </div>
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div class="d-flex align-items-center gap-2 flex-wrap">
                        <div id="doctype-filter-control"></div>
                        <div id="doctype-length-control"></div>
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        <div id="doctype-buttons-control"></div>
                    </div>
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
                    <div id="doctype-info-control"></div>
                    <div id="doctype-pagination-control"></div>
                </div>
            </div>
        </div>

        <div class="card shadow-sm config-panel" id="pane-controlcodes">
            <div class="panel-header">
                <div class="panel-icon"><i class="ri-barcode-line"></i></div>
                <div>
                    <h6>Control Codes</h6>
                    <p>View and manage document control codes</p>
                </div>
            </div>
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div class="d-flex align-items-center gap-2 flex-wrap">
                        <div id="cc-filter-control"></div>
                        <div id="cc-length-control"></div>
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        <div id="cc-buttons-control"></div>
                    </div>
                </div>
                <div class="table-scroll-container">
                    <table class="table table-hover table-bordered" id="controlCodesTable">
                        <thead class="table-light">
                            <tr>
                                <th>Action</th>
                                <th>Code</th>
                                <th>Description</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
                <div class="bottom-controls-container">
                    <div id="cc-info-control"></div>
                    <div id="cc-pagination-control"></div>
                </div>
            </div>
        </div>

    </div>
</div>

@include('departments.new_department')
@foreach($departments as $department)
    @include('departments.edit_department')
@endforeach

@include('settings.teams.new')
@foreach($teams as $team)
    @include('settings.teams.edit')
@endforeach

@include('settings.documents_type.edit')
@include('settings.documents_type.new')

@include('settings.control_codes.new')
@include('settings.control_codes.edit')

@endsection

@section('js')
<script>
$(document).ready(function () {

    $('.nav-card-btn').on('click', function (e) { e.stopPropagation(); });

    var STORAGE_KEY = 'systemConfig_activePane';

    function activatePane(target) {
        $('.section-nav-card').removeClass('active');
        $('.section-nav-card[data-target="' + target + '"]').addClass('active');
        $('.config-panel').removeClass('active');
        $('#' + target).addClass('active');
    }

    var savedPane = localStorage.getItem(STORAGE_KEY) || 'pane-departments';
    activatePane(savedPane);

    setTimeout(function () {
        if (savedPane === 'pane-departments') { 
            moveDeptControls();
            deptTable.columns.adjust();
        }

        if (savedPane === 'pane-teams') { 
            moveTeamsControls();
            teamsTable.columns.adjust();
        }

        if (savedPane === 'pane-doctypes') {
            moveDoctypeControls(); 
            docTypesTable.columns.adjust();
        }
        
        if (savedPane === 'pane-controlcodes') {
            moveCcControls();
            controlCodesTable.columns.adjust(); 
        }
    }, 150);

    $('.section-nav-card').on('click', function () {
        var target = $(this).data('target');
        activatePane(target);
        localStorage.setItem(STORAGE_KEY, target);
        setTimeout(function () {
            if (target === 'pane-departments') { 
                moveDeptControls();    
                deptTable.columns.adjust();         
            }

            if (target === 'pane-teams') { 
                moveTeamsControls();   
                teamsTable.columns.adjust();        
            }

            if (target === 'pane-doctypes') { 
                moveDoctypeControls(); 
                docTypesTable.columns.adjust();
            }

            if (target === 'pane-controlcodes') { 
                moveCcControls();
                controlCodesTable.columns.adjust(); 
            }
        }, 50);
    });

    function makeTable(tableId, ajaxUrl, columns, extraData, opts) {
        opts = opts || {};
        return $('#' + tableId).DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: ajaxUrl,
                type: 'GET',
                data: extraData || function (d) { return d; },
                error: function (xhr) { console.error(xhr.status, xhr.responseText); }
            },
            columns: columns,
            pageLength: opts.pageLength || 25,
            lengthMenu: [[10, 25, 50, 100], [10, 25, 50, 100]],
            dom: 'lBfrtip',
            buttons: [
                { extend: 'copy', text: 'Copy'  },
                { extend: 'excel', text: 'Excel', title: opts.excelTitle || '' },
            ],
            order: opts.order || [[0, 'desc']],
            language: {
                processing: '<div style="text-align:center;"><i class="fa fa-spinner fa-spin fa-2x"></i><br><span style="margin-top:10px;display:block;">Loading...</span></div>',
                emptyTable: opts.emptyTable  || 'No records found',
                zeroRecords: opts.zeroRecords || 'No matching records found',
                lengthMenu: 'Show _MENU_ entries',
                info: 'Showing _START_ to _END_ of _TOTAL_ entries',
                infoEmpty: 'Showing 0 to 0 of 0 entries',
                infoFiltered: '(filtered from _MAX_ total entries)',
                search: 'Search:',
                paginate: { first: 'First', last: 'Last', next: 'Next', previous: 'Previous' }
            },
            drawCallback: function () { opts.moveControls && opts.moveControls(); },
            initComplete: function () {
                var self = this;
                var inp  = $('#' + tableId + '_filter input');
                inp.unbind();
                var t;
                inp.on('input', function () {
                    var v = $(this).val();
                    clearTimeout(t);
                    t = setTimeout(function () { self.api().search(v).draw(); }, 500);
                });
            }
        });
    }

    function makeMoveControls(tableId, prefix) {
        return function () {
            var wrapper = $('#' + tableId + '_wrapper');

            var length = wrapper.find('.dataTables_length');
            if (length.length) $('#' + prefix + '-length-control').empty().append(length.detach());

            var filter = wrapper.find('.dataTables_filter');
            if (filter.length) {
                var inp      = filter.find('input');
                var hasFocus = inp.is(':focus');
                var curPos   = inp[0] ? inp[0].selectionStart : null;
                $('#' + prefix + '-filter-control').empty().append(filter.detach());
                if (hasFocus) {
                    var newInp = $('#' + tableId + '_filter input');
                    newInp.focus();
                    if (curPos !== null) newInp[0].setSelectionRange(curPos, curPos);
                }
            }

            var buttons = wrapper.find('.dt-buttons');
            if (buttons.length) $('#' + prefix + '-buttons-control').empty().append(buttons.detach());

            var info = wrapper.find('.dataTables_info');
            if (info.length) $('#' + prefix + '-info-control').empty().append(info.detach());

            var paginate = wrapper.find('.dataTables_paginate');
            if (paginate.length) $('#' + prefix + '-pagination-control').empty().append(paginate.detach());
        };
    }

    var moveDeptControls = makeMoveControls('departmentsTable', 'dept');

    var deptTable = makeTable(
        'departmentsTable',
        '{{ route("departments.data") }}',
        [
            { data: 'action', orderable: false, searchable: false },
            { data: 'code', name: 'code' },
            { data: 'name', name: 'name' },
            { data: 'dep_head', orderable: false, searchable: false },
        ],
        null,
        { excelTitle: 'Departments', emptyTable: 'No departments found', zeroRecords: 'No matching departments found', moveControls: moveDeptControls }
    );

    setTimeout(moveDeptControls, 100);

    $('#new_department').on('shown.bs.modal', function () {
        $(this).find('.select2-dept-head').select2({ dropdownParent: $(this), placeholder: 'Select department head...', allowClear: true });
    });
    $('#new_department').on('hide.bs.modal', function () {
        $(this).find('.select2-dept-head').select2('close');
    });

    @foreach($departments as $department)
    $('#editDepartment{{ $department->id }}').on('shown.bs.modal', function () {
        var $s = $(this).find('.select2-dept-head');
        if ($s.hasClass('select2-hidden-accessible')) $s.select2('destroy');
        $s.select2({ dropdownParent: $(this), placeholder: 'Select department head...', allowClear: true });
    });
    $('#editDepartment{{ $department->id }}').on('hide.bs.modal', function () {
        $(this).find('.select2-dept-head').select2('close');
    });
    @endforeach

    $('#departmentsTable tbody').on('click', '.delete-department', function () {
        var id = $(this).data('id');
        var name = $(this).data('name');
        
        swal({ title: 'Delete "' + name + '"?', text: 'This action cannot be undone.', type: 'warning', showCancelButton: true, confirmButtonColor: '#d33', confirmButtonText: 'Yes, delete it!', cancelButtonText: 'Cancel', closeOnConfirm: false }, function () {
            var form = $('<form method="POST" style="display:none;"><input type="hidden" name="_token" value="{{ csrf_token() }}"><input type="hidden" name="_method" value="DELETE"></form>');
            form.attr('action', '{{ url("departments") }}/' + id);
            $('body').append(form);
            form.submit();
        });
    });

    var moveTeamsControls = makeMoveControls('teamsTable', 'teams');

    var teamsTable = makeTable(
        'teamsTable',
        '{{ route("teams.data") }}',
        [
            { data: 'action', orderable: false, searchable: false },
            { data: 'name', name: 'name' },
            { data: 'created_by', orderable: false, searchable: false },
            { data: 'department', orderable: false, searchable: false },
            { data: 'campus', orderable: false, searchable: false },
        ],
        null,
        { excelTitle: 'Offices', emptyTable: 'No offices found', zeroRecords: 'No matching offices found', moveControls: moveTeamsControls }
    );

    setTimeout(moveTeamsControls, 100);

    $('#new_team').on('shown.bs.modal', function () {
        $(this).find('.select2-team').select2({ dropdownParent: $(this), placeholder: 'Select department...', allowClear: true });
    });
    $('#new_team').on('hide.bs.modal', function () {
        $(this).find('.select2-team').select2('close');
    });

    @foreach($teams as $team)
    $('#editTeam{{ $team->id }}').on('shown.bs.modal', function () {
        var $s = $(this).find('.select2-team');
        if ($s.hasClass('select2-hidden-accessible')) $s.select2('destroy');
        $s.select2({ dropdownParent: $(this), placeholder: 'Select department...', allowClear: true });
    });
    $('#editTeam{{ $team->id }}').on('hide.bs.modal', function () {
        $(this).find('.select2-team').select2('close');
    });
    @endforeach

    $('#newTeamForm').on('submit', function (e) {
        e.preventDefault();
        var teamName = $('#team_name').val().trim();
        var department = $('#new_department_id').val();
        var campus = $('#campus').val().trim();

        if (!teamName) { swal('Error!', 'Please enter an office name', 'error'); return; }
        
        var btn = $('#createTeamBtn');
        btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Creating...');
        $.ajax({
            type: 'POST', url: '{{ url("teams") }}',
            data: { team_name: teamName, department: department, campus: campus },
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            success: function () {
                swal('Success!', 'Office created successfully!', 'success');
                $('#new_team').modal('hide');
                teamsTable.ajax.reload();
                btn.prop('disabled', false).html('<i class="fa fa-save"></i> Create Office');
            },
            error: function (data) {
                btn.prop('disabled', false).html('<i class="fa fa-save"></i> Create Office');
                swal('Error!', data.responseJSON?.message || 'Something went wrong.', 'error');
            }
        });
    });

    $(document).on('submit', '.edit-team-form', function (e) {
        e.preventDefault();
        var form = $(this);
        var teamId = form.data('id');
        var teamName = form.find('input[name="team_name"]').val().trim();
        var department = form.find('select[name="department"]').val();
        var campus = form.find('input[name="campus"]').val().trim();

        if (!teamName) { swal('Error!', 'Please enter an office name', 'error'); return; }

        var btn = form.find('.update-team-btn');
        btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Updating...');
        $.ajax({
            type: 'POST', url: '{{ url("teams") }}/' + teamId,
            data: { _method: 'PUT', team_name: teamName, department: department, campus: campus },
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            success: function () {
                swal('Success!', 'Office updated successfully!', 'success');
                $('#editTeam' + teamId).modal('hide');
                teamsTable.ajax.reload();
                btn.prop('disabled', false).html('<i class="fa fa-save"></i> Update Office');
            },
            error: function (data) {
                btn.prop('disabled', false).html('<i class="fa fa-save"></i> Update Office');
                swal('Error!', data.responseJSON?.message || 'Something went wrong.', 'error');
            }
        });
    });

    $(document).on('click', '.delete-team', function () {
        var id = $(this).data('id');
        var name = $(this).data('name');
        swal({ title: 'Delete "' + name + '"?', text: 'This action cannot be undone.', type: 'warning', showCancelButton: true, confirmButtonColor: '#d33', confirmButtonText: 'Yes, delete it!', cancelButtonText: 'Cancel', closeOnConfirm: false }, function () {
            $.ajax({
                type: 'POST',
                url: '{{ url("teams") }}/' + id,
                data: { _method: 'DELETE' },
                headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                success: function () { swal('Deleted!', 'Office deleted successfully.', 'success'); teamsTable.ajax.reload(); },
                error:   function () { swal('Error!', 'Something went wrong.', 'error'); }
            });
        });
    });

    var moveDoctypeControls = makeMoveControls('documentTypesTable', 'doctype');

    var docTypesTable = makeTable(
        'documentTypesTable',
        '{{ route("document-types.data") }}',
        [
            { data: 'action', orderable: false, searchable: false },
            { data: 'name',   name: 'name' },
        ],
        null,
        { pageLength: 10, excelTitle: 'Document Types', emptyTable: 'No document types found', zeroRecords: 'No matching records found', order: [[1, 'asc']], moveControls: moveDoctypeControls }
    );

    setTimeout(moveDoctypeControls, 100);

    $(document).on('click', '.edit-btn', function () {
        var id = $(this).data('id');
        var name = $(this).data('name');
        $('#editName').val(name);
        $('#editDocumentTypeForm').attr('action', '/documents_type/update/' + id);
    });

    $(document).on('click', '.delete-btn', function () {
        var id = $(this).data('id');
        var name = $(this).data('name');
        swal({
            title: 'Delete "' + name + '"?',
            text: 'This action cannot be undone.',
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!',
            cancelButtonText: 'Cancel',
            closeOnConfirm: false
        }, function () {
            var form = $('<form method="POST" style="display:none;"><input type="hidden" name="_token" value="{{ csrf_token() }}"><input type="hidden" name="_method" value="DELETE"></form>');
            form.attr('action', '{{ url("documents_type/delete") }}/' + id);
            $('body').append(form);
            form.submit();
        });
    });

    var moveCcControls = makeMoveControls('controlCodesTable', 'cc');

    var controlCodesTable = makeTable(
        'controlCodesTable',
        '{{ route("control-codes.data") }}',
        [
            { data: 'action', orderable: false, searchable: false },
            { data: 'code', name: 'code' },
            { data: 'description', name: 'description' },
        ],
        null,
        { excelTitle: 'Control Codes', emptyTable: 'No control codes found', zeroRecords: 'No matching control codes found', moveControls: moveCcControls }
    );

    setTimeout(moveCcControls, 100);

    $(document).on('click', '.edit-control-code', function () {
        $('#editControlCodeValue').val($(this).data('code'));
        $('#editControlCodeDescription').val($(this).data('description'));
        $('#editControlCodeForm').attr('action', '{{ url("control-codes/update") }}/' + $(this).data('id'));
        $('#edit_control_code').modal('show');
    });

    $(document).on('click', '.delete-control-code', function () {
        var id = $(this).data('id');
        var code = $(this).data('code');
        swal({
            title: 'Delete "' + code + '"?',
            text: 'This action cannot be undone.',
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!',
            cancelButtonText: 'Cancel',
            closeOnConfirm: false
        }, function () {
            var form = $('<form method="POST" style="display:none;"><input type="hidden" name="_token" value="{{ csrf_token() }}"></form>');
            form.attr('action', '{{ url("control-codes/delete") }}/' + id);
            $('body').append(form);
            form.submit();
        });
    });

    $(window).on('resize', function () {
        moveDeptControls();
        moveTeamsControls();
        moveDoctypeControls();
        moveCcControls();
    });

});
</script>
@endsection