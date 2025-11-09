<div class="modal fade" id="uploadDocument" tabindex="-1" role="dialog" aria-labelledby="uploadDocumentLabel">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="uploadDocumentLabel">Upload Document</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method='post' action='upload-document' onsubmit='show();' enctype="multipart/form-data">
                <div class="modal-body">
                    {{ csrf_field() }}
                    <div class='row g-3'>
                        <div class='col-md-12'>
                            <label class="form-label">Control Code *</label>
                            <input type="text" class="form-control" value="{{ old('control_code') }}" name="control_code" required/>
                        </div>
                        <div class='col-md-12'>
                            <label class="form-label">Title *</label>
                            <input type="text" class="form-control" value="{{ old('title') }}" name="title" required/>
                        </div>
                        
                        <div class="col-12"><hr class="divider"></div>
                        
                        <div class='col-md-5'>
                            <label class="form-label">Company *</label>
                            <select name='company' class='form-control cat' required>
                                <option value=""></option>
                                @foreach($companies->where('status',null) as $company)
                                    <option value='{{$company->id}}' @if(old('company') == $company->id) selected @endif>
                                        {{$company->code}} - {{$company->name}}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class='col-md-5'>
                            <label class="form-label">Department *</label>
                            <select name='department' class='form-control cat' required>
                                <option value=""></option>
                                @foreach($departments->where('status',null) as $dep)
                                    <option value='{{$dep->id}}' @if(old('department') == $dep->id) selected @endif>
                                        {{$dep->code}} - {{$dep->name}}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class='col-md-2'>
                            <div class="checkbox-label">
                                <input type="checkbox" name='public' value='1' id='public'>
                                <label for='public' class="form-label mb-0">Public</label>
                            </div>
                        </div>
                        
                        <div class='col-md-4'>
                            <label class="form-label">Type of Document *</label>
                            <select name='document_type' class='form-control cat' required>
                                <option value=""></option>
                                @foreach($document_types as $types)
                                    <option value='{{$types->name}}' @if(old('types') == $types->name) selected @endif>
                                        {{$types->code}} - {{$types->name}}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class='col-md-4'>
                            <label class="form-label">Effective Date *</label>
                            <input type="date" class="form-control" name="effective_date" required/>
                        </div>
                        <div class='col-md-4'>
                            <label class="form-label">Revision *</label>
                            <input type="number" class="form-control" value="{{ old('version') }}" min='0' name="version" required/>
                        </div>
                        
                        <div class="col-12"><hr class="divider"></div>
                        
                        <div class='col-md-4'>
                            <label class="form-label">SOFT Copy * <small class="text-muted">(.word,.csv,.ppt,etc)</small></label>
                            <input type="file" class="form-control" accept="application/msword, application/vnd.ms-excel, application/vnd.ms-powerpoint" name="attachment[soft_copy]" required/>
                        </div>
                        <div class='col-md-4'>
                            <label class="form-label">PDF/Scanned Copy * <small class="text-muted">(.pdf)</small></label>
                            <input type="file" class="form-control" accept="application/pdf" name="attachment[pdf_copy]" required/>
                        </div>
                        <div class='col-md-4'>
                            <label class="form-label">FILLABLE Copy <small class="text-muted">(.pdf)</small></label>
                            <input type="file" class="form-control" name="attachment[fillable_copy]"/>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btns btn-secondary" data-dismiss="modal" data-bs-dismiss="modal">Close</button>
                    <button type='submit' class="btn btnss btn-primary">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    .btns {
        background-color:#495057;
        border: none;
    }

    .btns:hover {
        background-color:#282c30;
    }
    .btnss {
        background-color: #800000;
        border: none;
    }
    .btnss:hover {
        background-color: #6B0000;
    }
    .modal-content {
        border-radius: 10px;
        border: none;
        box-shadow: 0 5px 20px rgba(0,0,0,0.2);
    }

    .modal-header {
        background: #f8f9fa;
        border-bottom: 2px solid #8B0000;
        border-radius: 10px 10px 0 0;
        padding: 20px 25px;
    }

    .modal-title {
        font-size: 18px;
        font-weight: 600;
        color: #2c3e50;
    }

    .modal-body {
        padding: 25px;
    }

    .modal-footer {
        border-top: 1px solid #e9ecef;
        padding: 15px 25px;
    }

    .form-label {
        font-size: 13px;
        font-weight: 600;
        color: #495057;
        margin-bottom: 5px;
    }

    .form-control {
        border-radius: 5px;
        border: 1px solid #dee2e6;
    }

    .form-control:focus {
        border-color: #8B0000;
        box-shadow: 0 0 0 0.2rem rgba(139, 0, 0, 0.1);
    }

    .checkbox-label {
        display: flex;
        align-items: center;
        gap: 8px;
        margin-top: 30px;
    }

    hr.divider {
        border: none;
        border-top: 1px solid #e9ecef;
        margin: 20px 0;
    }

    .text-muted {
        font-style: italic;
    }
</style>