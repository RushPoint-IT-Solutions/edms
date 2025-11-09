<div class="modal fade" id="edit{{$user->id}}" tabindex="-1" role="dialog" aria-labelledby="editModalLabel{{$user->id}}" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editModalLabel{{$user->id}}">Edit DCO - {{$user->name}}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method='post' action='edit-dco/{{$user->id}}' onsubmit='show();' enctype="multipart/form-data">
                <div class="modal-body">
                    {{ csrf_field() }}
                    <div class='row'>
                        <div class='col-md-12'>
                            <label>Assign Departments:</label>
                            <select name='department[]' class='form-control-sm form-control cat' multiple required>
                                <option value=""></option>
                                @foreach($departments->where('status',null) as $dep)
                                    <option value='{{$dep->id}}' @if(count(($user->dco)->where('department_id',$dep->id)) == 1) selected @endif>
                                        {{$dep->code}} - {{$dep->name}}
                                    </option>
                                @endforeach
                            </select>
                            <small class="form-text text-muted">Select one or more departments to assign to this DCO</small>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btns btn-secondary" style="background-color:#495057; border: none;" data-dismiss="modal" data-bs-dismiss="modal">Close</button>
                    <button type='submit' class="btn btnss btn-primary" style="background-color: #800000 !important; border: none !important;" >Submit</button>
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
        color: #2c3e50;
        border-radius: 10px 10px 0 0;
        padding: 20px 25px;
    }
    
    .modal-header h5 {
        font-size: 18px;
        font-weight: 600;
        margin: 0;
        color: #2c3e50;
    }

    .modal-header .close {
        color: #2c3e50;
        opacity: 1;
        font-size: 24px;
        font-weight: 300;
        text-shadow: none;
        padding: 0;
        margin: 0;
    }

    .modal-header .close:hover {
        color: #800000;
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

    .modal-body label {
        font-size: 14px;
        font-weight: 500;
        color: #495057;
        margin-bottom: 5px;
        display: block;
    }

    .current-assignments-box {
        padding: 15px;
        background: #f8f9fa;
        border-radius: 5px;
        border: 1px solid #dee2e6;
        margin-top: 5px;
    }

    .current-assignments-box .badge {
        margin-right: 5px;
        margin-bottom: 5px;
        padding: 6px 12px;
        font-size: 13px;
        font-weight: 500;
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