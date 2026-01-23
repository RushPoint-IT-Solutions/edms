<div class="modal fade" id="new_permit" tabindex="-1" role="dialog" aria-labelledby="newPermitLabel">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="newPermitModalLabel">New Permit/License</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="post" action="{{ url('permits/store') }}" onsubmit="show();" enctype="multipart/form-data">
                <div class="modal-body">
                    <div class='row gy-3'>
                        {{ csrf_field() }}
                        <div class='col-md-12'>
                            <label class="form-label">Title :</label>
                            <input type="text" class="form-control-sm form-control @if($errors->has('title')) is-invalid @endif" value="{{ old('title') }}" name="title"/>
                            @if($errors->has('title'))
                            <span class="invalid-feedback">{{$errors->first('title')}}</span>
                            @endif
                        </div>
                        <div class='col-md-12'>
                            <label>Description :</label>
                            <textarea type="text" class="form-control-sm form-control @if($errors->has('description')) is-invalid @endif" name="description">{{ old('description') }}</textarea>
                            @if($errors->has('description'))
                            <span class="invalid-feedback">{{$errors->first('description')}}</span>
                            @endif
                        </div>
                        {{-- <div class='col-md-12'>
                            <label>Company :</label>
                            <select name='company' class='form-control-sm form-control cat'>
                                <option value=""></option>
                                @foreach($companies as $company)
                                    <option value='{{$company->id}}' @if(old('company') == $company->id) selected @endif>{{$company->code}} - {{$company->name}}</option>
                                @endforeach
                            </select>
                        </div> --}}
                        {{-- <div class='col-md-12'>
                            <label>Department :</label>
                            <select name='department' class='form-control-sm form-control cat'>
                                <option value=""></option>
                                @foreach($departments as $department)
                                    <option value='{{$department->id}}' @if(old('department') == $department->id) selected @endif>{{$department->code}} - {{$department->name}}</option>
                                @endforeach
                            </select>
                        </div> --}}
                        <div class='col-md-12'>
                            <label>Type :</label>
                            <select name='type' class='form-control-sm form-control cat @if($errors->has('type')) is-invalid @endif'>
                                <option value=""></option>
                                <option value="License" @if(old('type') == "License") selected @endif>License</option>
                                <option value="Permit" @if(old('type') == "Permit") selected @endif>Permit</option>
                                <option value="Certification" @if(old('type') == "Certification") selected @endif>Certification</option>
                            </select>
                            @if($errors->has('type'))
                            <span class="invalid-feedback">{{$errors->first('type')}}</span>
                            @endif
                        </div>
                        <div class='col-md-12'>
                            <label>File :</label>
                            <input type="file" class="form-control-sm form-control @if($errors->has('file')) is-invalid @endif" name="file"/>
                            @if($errors->has('file'))
                            <span class="invalid-feedback">{{$errors->first('file')}}</span>
                            @endif
                        </div>
                        <div class='col-md-12'>
                            <label>Expiration Date :</label>
                            <input type="date" class="form-control-sm form-control @if($errors->has('expiration_date')) is-invalid @endif" min='{{date('Y-m-d')}}' name="expiration_date" />
                            @if($errors->has('expiration_date'))
                            <span class="invalid-feedback">{{$errors->first('expiration_date')}}</span>
                            @endif
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
        border-bottom: solid 2px #8B0000;
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

    .modal-body textarea.form-control {
        min-height: 80px;
        resize: vertical;
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

    .chosen-container {
        width: 100% !important;
        margin-top: 5px;
        margin-bottom: 15px;
    }

    .chosen-container-single .chosen-single {
        height: 38px;
        line-height: 36px;
        border: 1px solid #dee2e6;
        border-radius: 5px;
        padding: 0 12px;
        background: white;
        box-shadow: none;
    }

    .chosen-container-active .chosen-single {
        border-color: #8B0000;
        box-shadow: 0 0 0 0.2rem rgba(139, 0, 0, 0.15);
    }

    .chosen-container .chosen-drop {
        border: 1px solid #dee2e6;
        border-radius: 5px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
</style>