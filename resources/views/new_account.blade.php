<div class="modal fade" id="new_account" tabindex="-1" role="dialog" aria-labelledby="newAccountModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="newAccountModalLabel">New Account</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method='post' action='new-account' onsubmit='show();' enctype="multipart/form-data">
                <div class="modal-body">
                    {{ csrf_field() }}
                    <div class='row'>
                        <div class='col-md-12'>
                            <label>Name :</label>
                            <input type="text" class="form-control-sm form-control" value="{{ old('name') }}" name="name" placeholder="Enter full name" required/>
                        </div>
                        <div class='col-md-12'>
                            <label>Email :</label>
                            <input type="email" class="form-control-sm form-control" value="{{ old('email') }}" name="email" placeholder="Enter email address" required/>
                        </div>
                        <div class='col-md-12'>
                            <label>Company :</label>
                            <select name='company' class='form-control-sm form-control cat' required>
                                <option value="">Select company...</option>
                                @foreach($companies->where('status',null) as $company)
                                    <option value='{{$company->id}}' @if(old('company') == $company->id) selected @endif>{{$company->code}} - {{$company->name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class='col-md-12'>
                            <label>Department :</label>
                            <select name='department' class='form-control-sm form-control cat' required>
                                <option value="">Select department...</option>
                                @foreach($departments->where('status',null) as $dep)
                                    <option value='{{$dep->id}}' @if(old('department') == $dep->id) selected @endif>{{$dep->code}} - {{$dep->name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class='col-md-12'>
                            <label>Role :</label>
                            <select name='role' class='form-control-sm form-control cat' required>
                                <option value="">Select role...</option>
                                @foreach($roles as $role)
                                    <option value='{{$role}}' @if(old('role') == $role) selected @endif>{{$role}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class='col-md-12'>
                            <label>Password :</label>
                            <input type="password" class="form-control-sm form-control" name="password" placeholder="Enter password" required/>
                        </div>
                        <div class='col-md-12'>
                            <label>Password Confirmation :</label>
                            <input type="password" class="form-control-sm form-control" name="password_confirmation" placeholder="Confirm password" required/>
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

    .modal-body label {
        font-size: 14px;
        font-weight: 500;
        color: #495057;
        margin-bottom: 5px;
        display: block;
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