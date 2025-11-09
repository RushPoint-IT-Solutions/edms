<div class="modal fade" id="editDepartment{{$department->id}}" tabindex="-1" role="dialog" aria-labelledby="editDepartmentModalLabel{{$department->id}}" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editDepartmentModalLabel{{$department->id}}">Edit Department</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method='post' action='edit-department/{{$department->id}}' onsubmit='show();' enctype="multipart/form-data">
                <div class="modal-body">
                    {{ csrf_field() }}
                    <div class='row'>
                        <div class='col-md-12'>
                            <label>Department Code :</label>
                            <input type="text" class="form-control-sm form-control bg-light" value="{{$department->code}}" readonly name="code" required/>
                            <small class="text-muted">Department code cannot be changed</small>
                        </div>
                        <div class='col-md-12'>
                            <label>Department Name :</label>
                            <input type="text" class="form-control-sm form-control" value="{{$department->name}}" name="name" required/>
                        </div>
                        <div class='col-md-12'>
                            <label>Department Head :</label>
                            <select name='user_id' class='form-control-sm form-control cat'>
                                <option value="">Select department head...</option>
                                @foreach($employees as $employee)
                                    <option value='{{$employee->id}}' @if($department->user_id == $employee->id) selected @endif>{{$employee->name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class='col-md-12'>
                            <label>Permits Accountable :</label>
                            <select name='permit_id[]' class='form-control-sm form-control cat' multiple>
                                <option value="">Select employees...</option>
                                @foreach($employees as $employee)
                                    <option value='{{$employee->id}}' @if(count(($department->permit_accounts)->where('user_id',$employee->id)) == 1) selected @endif>{{$employee->name}}</option>
                                @endforeach
                            </select>
                            <small class="text-muted mt-1">Hold Ctrl/Cmd to select multiple employees (optional)</small>
                        </div>
                        
                        <div class='col-md-12'>
                            <label>Approvers :</label>
                            <div class="d-flex align-items-center mb-2">
                                <button type="button" onclick='add_edit_approver({{$department->id}})' class="btn btn-primary btn-sm me-2">
                                    <i class='fa fa-plus-square-o'></i> Add
                                </button>
                                <button type="button" onclick='remove_edit_approver({{$department->id}})' class="btn btn-danger btn-sm">
                                    <i class='fa fa-minus-square-o'></i> Remove
                                </button>
                            </div>
                            <div class='approvers-data-{{$department->id}}'>
                                @foreach($department->approvers as $approver)
                                <div class='row mb-2' id='approver_{{$department->id}}_{{$approver->level}}'>
                                    <div class='col-md-2'>
                                        <label class="small">Level {{$approver->level}}:</label>
                                    </div>
                                    <div class='col-md-10'>
                                        <select name='edit_approvers[]' class='form-control-sm form-control cat' required>
                                            <option value="">Select approver...</option>
                                            @foreach($employees as $employee)
                                                <option value='{{$employee->id}}' @if($approver->user_id == $employee->id) selected @endif>{{$employee->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal" data-bs-dismiss="modal">Close</button>
                    <button type='submit' class="btn btn-primary">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
     
    .modal-content {
        border: none;
        border-radius: 10px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }

    .modal-header {
        border-bottom: solid 2px #800000;
        color: white;
        border-radius: 10px 10px 0 0;
        padding: 20px 25px;
    }
    
    .modal-header h5 {
        font-size: 18px;
        font-weight: 600;
        margin: 0;
    }

    .modal-header .close {
        color: white;
        opacity: 1;
        font-size: 24px;
        font-weight: 300;
        text-shadow: none;
        padding: 0;
        margin: 0;
    }

    .modal-header .close:hover {
        color: white;
        opacity: 0.8;
    }

    .modal-body {
        padding: 25px;
        background: white;
    }

    .modal-body .form-control,
    .modal-body .form-control-sm {
        padding: 8px 12px;
        border: 1px solid #dee2e6;
        border-radius: 5px;
        font-size: 14px;
        margin-top: 5px;
        margin-bottom: 15px;
    }

    .modal-body .form-control:focus,
    .modal-body .form-control-sm:focus {
        border-color: #8B0000;
        outline: none;
        box-shadow: 0 0 0 0.2rem rgba(139, 0, 0, 0.15);
    }

    .modal-body .bg-light {
        background-color: #f8f9fa !important;
    }

    .modal-body label {
        font-size: 14px;
        font-weight: 500;
        color: #495057;
        margin-bottom: 5px;
        display: block;
    }

    .modal-body small {
        display: block;
        margin-top: -10px;
        margin-bottom: 10px;
    }

    .modal-body .btn-sm {
        padding: 6px 12px;
        font-size: 13px;
        border-radius: 5px;
    }

    .modal-footer {
        padding: 20px 25px;
        background: #f8f9fa;
        border-top: 1px solid #dee2e6;
        border-radius: 0 0 10px 10px;
    }

    .modal-footer .btn {
        padding: 8px 20px;
        border-radius: 5px;
        font-size: 14px;
        border: none;
        cursor: pointer;
        transition: all 0.3s;
    }

    .modal-footer .btn-secondary {
        background: #6c757d;
        color: white;
    }

    .modal-footer .btn-secondary:hover {
        background: #5a6268;
    }

    .modal-footer .btn-primary {
        background: #8B0000;
        color: white;
    }

    .modal-footer .btn-primary:hover {
        background: #6B0000;
    }
</style>