
<div class="modal" id="uploadDocument" tabindex="-1" role="dialog"  >
    <div class="modal-dialog modal-lg " role="document">
        <div class="modal-content">
            <div class="modal-header">
                <div class='col-md-10'>
                    <h5 class="modal-title" id="exampleModalLabel">Upload Document</h5>
                </div>
                <div class='col-md-2'>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close" >
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            </div>
            <form method='post' action='upload-document' onsubmit='show();'  enctype="multipart/form-data" >
                <div class="modal-body">
                    {{ csrf_field() }}
                   <div class='row'>
                    <div class='col-md-12'>
                        Control Code :
                        <input type="text" class="form-control-sm form-control "  value="{{ old('control_code') }}"  name="control_code" required/>
                     </div>
                    <div class='col-md-12'>
                        Title :
                        <input type="text" class="form-control-sm form-control "  value="{{ old('title') }}"  name="title" required/>
                    </div>
                    <div class='col-md-12'>
                    <hr>
                    </div>
                    <div class='col-md-5'>
                        Company :
                        <select name='company' class='form-control-sm form-control cat' required>
                            <option value=""></option>
                            @foreach($companies->where('status',null) as $company)
                                <option value='{{$company->id}}' @if(old('company') == $company->id) selected @endif>{{$company->code}} - {{$company->name}}</option>
                            @endforeach
                        </select>
                     </div>
                    <div class='col-md-5'>
                        Department :
                        <select name='department' class='form-control-sm form-control cat' required>
                            <option value=""></option>
                            @foreach($departments->where('status',null) as $dep)
                                <option value='{{$dep->id}}' @if(old('department') == $dep->id) selected @endif>{{$dep->code}} - {{$dep->name}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class='col-md-2'>
                        <br>
                         <input type="checkbox" name='public' value='1' id='public' class="form-control-sm i-checks"><label for='public'> Public </label>
                    
                    </div>
                    <div class='col-md-4'>
                        Type of Document :
                        <select name='document_type' class='form-control-sm form-control cat' required>
                            <option value=""></option>
                            @foreach($document_types as $types)
                                <option value='{{$types->name}}' @if(old('types') == $types->name) selected @endif>{{$types->code}} - {{$types->name}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class='col-md-4'>
                        Effective Date :
                        <input type="date" class="form-control-sm form-control "  name="effective_date" required/>
                    </div>
                    <div class='col-md-4'>
                        Revision :
                        <input type="number" class="form-control-sm form-control "  value="{{ old('version') }}" min='0'  name="version" required/>
                    </div>
                    <div class='col-md-12'>
                        <hr>
                    </div>
                    <div class='col-md-4'>
                        SOFT Copy <small><i>(.word,.csv,.ppt,etc)</i></small>
                        <input type="file" class="form-control-sm form-control " accept="application/msword, application/vnd.ms-excel, application/vnd.ms-powerpoint"  name="attachment[soft_copy]" required/>
                    </div>
                    <div class='col-md-4'>
                        PDF/Scanned Copy <small><i>(.pdf)</i></small>
                        <input type="file" class="form-control-sm form-control " accept="application/pdf"  name="attachment[pdf_copy]" required/>
                    </div>
                    <div class='col-md-4'>
                        FILLABLE Copy <small><i>(.pdf)</i><small>
                        <input type="file" class="form-control-sm form-control "  name="attachment[fillable_copy]" />
                    </div>
                   </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type='submit'  class="btn btn-primary" >Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>
