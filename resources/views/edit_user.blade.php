<div class="modal fade" id="editUser{{$user->id}}" tabindex="-1" role="dialog" aria-labelledby="editUserModalLabel{{$user->id}}" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editUserModalLabel{{$user->id}}">Edit User</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            
            <form method='post' action='edit-user/{{$user->id}}' onsubmit='show();' enctype="multipart/form-data">
                <div class="modal-body">
                    {{ csrf_field() }}
                    <div class='row'>
                        <div class='col-md-12'>
                            <label>Name :</label>
                            <input type="text" class="form-control-sm form-control bg-light" value="{{$user->name}}" readonly name="name" required/>
                            <small class="text-muted">Name cannot be changed</small>
                        </div>
                        <div class='col-md-12'>
                            <label>Email :</label>
                            <input type="email" class="form-control-sm form-control bg-light" value="{{$user->email}}" readonly name="email" required/>
                            <small class="text-muted">Email cannot be changed</small>
                        </div>
                        <div class='col-md-12'>
                            <label>Company :</label>
                            <select name='company' class='form-control-sm form-control cat' required>
                                <option value="">Select company...</option>
                                @foreach($companies as $company)
                                    <option value='{{$company->id}}' @if($user->company_id == $company->id) selected @endif>{{$company->name}} - {{$company->code}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class='col-md-12'>
                            <label>Department :</label>
                            <select name='department' class='form-control-sm form-control cat' required>
                                <option value="">Select department...</option>
                                @foreach($departments as $dep)
                                    <option value='{{$dep->id}}' @if($user->department_id == $dep->id) selected @endif>{{$dep->code}} - {{$dep->name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class='col-md-12'>
                            <label>Share Department :</label>
                            <select name='share_department[]' class='form-control-sm form-control cat' multiple>
                                <option value="">Select departments...</option>
                                @foreach($departments->where('id','!=',$user->department_id) as $dep)
                                    <option value='{{$dep->id}}' @foreach($user->departments as $d ) @if($d->department_id == $dep->id) selected @endif @endforeach>{{$dep->code}} - {{$dep->name}}</option>
                                @endforeach
                            </select>
                            <small class="text-muted">Hold Ctrl/Cmd to select multiple departments</small>
                        </div>
                        <div class='col-md-12'>
                            <label>Role :</label>
                            <select name='role' class='form-control-sm form-control cat' required>
                                <option value="">Select role...</option>
                                @foreach($roles as $role)
                                    <option value='{{$role}}' @if($user->role == $role) selected @endif>{{$role}}</option>
                                @endforeach
                            </select>
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