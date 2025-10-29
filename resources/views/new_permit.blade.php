
<div class="modal" id="new_permit" tabindex="-1" role="dialog"  >
    <div class="modal-dialog modal-lg " role="document">
        <div class="modal-content">
            <div class="modal-header">
                <div class='col-md-10'>
                    <h5 class="modal-title" id="exampleModalLabel">New Permit/License</h5>
                </div>
                <div class='col-md-2'>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close" >
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            </div>
            <form method='post' action='new-permit' onsubmit='show();'  enctype="multipart/form-data" >
                <div class="modal-body">
                    <div class='row'>
                        {{ csrf_field() }}
                        <div class='col-md-12'>
                            Title :
                            <input type="text" class="form-control-sm form-control "  value="{{ old('title') }}"  name="title" required/>
                        </div>
                        <div class='col-md-12'>
                            Description :
                            <textarea type="text" class="form-control-sm form-control " name="description" required>{{ old('description') }}</textarea>
                        </div>
                        <div class='col-md-12'>
                            Company :
                            <select name='company' class='form-control-sm form-control cat' required>
                                <option value=""></option>
                                @foreach($companies as $company)
                                    <option value='{{$company->id}}' @if(old('company') == $company->id) selected @endif>{{$company->code}} - {{$company->name}}</option>
                                @endforeach
                            </select>
                         </div>
                        <div class='col-md-12'>
                            Department :
                            <select name='department' class='form-control-sm form-control cat' required>
                                <option value=""></option>
                                @foreach($departments as $department)
                                    <option value='{{$department->id}}' @if(old('department') == $department->id) selected @endif>{{$department->code}} - {{$department->name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class='col-md-12'>
                            Type :
                            <select name='type' class='form-control-sm form-control cat'  required>
                                <option value=""></option>
                                <option value="License">License</option>
                                <option value="Permit">Permit</option>
                                <option value="Certification">Certification</option>
                                
                            </select>
                        </div>
                        <div class='col-md-12'>
                            File :
                            <input type="file" class="form-control-sm form-control "   name="file" required/>
                        </div>
                        <div class='col-md-12'>
                            Expiration Date :
                            <input type="date" class="form-control-sm form-control "  min='{{date('Y-m-d')}}'  name="expiration_date" />
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
