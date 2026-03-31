<div class="modal fade" id="new_permit" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header border-bottom border-2">
                <h5 class="modal-title mb-3">New Permit/License</h5>
                <button type="button" class="btn-close mb-3" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" id="AddPermitForm">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Title <span class="text-danger">*</span></label>
                        <input type="text" name="title" class="form-control">
                        <div class="invalid-feedback"></div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Description <span class="text-danger">*</span></label>
                        <textarea name="description" class="form-control" rows="3"></textarea>
                        <div class="invalid-feedback"></div>
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
                    <div class="mb-3">
                        <label class="form-label">Type <span class="text-danger">*</span></label>
                        <select name="type" class="form-select">
                            <option value="">Select type</option>
                            <option value="License">License</option>
                            <option value="Permit">Permit</option>
                            <option value="Certification">Certification</option>
                        </select>
                        <div class="invalid-feedback"></div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">File <span class="text-danger">*</span></label>
                        <input type="file" name="file" class="form-control">
                        <div class="invalid-feedback"></div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Expiration Date <span class="text-danger">*</span></label>
                        <input type="date" name="expiration_date" min="{{ date('Y-m-d') }}" class="form-control">
                        <div class="invalid-feedback"></div>
                    </div>
                </div>
                <div class="modal-footer border-top border-2">
                    <button type="button" class="btn btn-secondary mt-3" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary mt-3" id="AddPermitBtn">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>