<div class="modal fade" id="newRequest" tabindex="-1" role="dialog" aria-labelledby="newRequestModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="newRequestModalLabel">New Request</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            @php
                $submit = 0; 
            @endphp
            <form method='post' action='{{url('new-change-request')}}' onsubmit='show();' enctype="multipart/form-data">
                {{ csrf_field() }}
                <div class="modal-body">
                    <input type="hidden" class="form-control-sm form-control" name="request_type" value='New'/>
                    
                    <div class='row'>
                        <div class='col-md-12'>
                            <label>Title:</label>
                            <input type="text" class="form-control-sm form-control" value="{{ old('title') }}" name="title" placeholder="Enter request title" required/>
                        </div>
                    </div>

                    <div class='row'>
                        <div class='col-md-4'>
                            <label>Company:</label>
                            <select name='company' class='form-control-sm form-control' required>
                                <option value="">Select Company</option>
                                @foreach($companies as $company)
                                    @if(auth()->user()->company_id == $company->id)
                                    <option value='{{$company->id}}' @if(old('company') == $company->id) selected @endif>{{$company->code}} - {{$company->name}}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                        <div class='col-md-4'>
                            <label>Department:</label>
                            <select name='department' class='form-control-sm form-control' required>
                                <option value="">Select Department</option>
                                @foreach($departments as $dep)
                                    @if(auth()->user()->department_id == $dep->id)
                                    <option value='{{$dep->id}}' @if(old('department') == $dep->id) selected @endif>{{$dep->code}} - {{$dep->name}}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                        <div class='col-md-4'>
                            <label>Document Type:</label>
                            <select name='category' class='form-control-sm form-control' required>
                                <option value="">Select Document Type</option>
                                @foreach($document_types as $type)
                                    <option value='{{$type->name}}' @if(old('category') == $type->name) selected @endif>{{$type->code}} - {{$type->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    
                    <div class='row'>
                        <div class='col-md-12'>
                            <hr class="section-divider">
                        </div>
                    </div>
                    
                    <div class='row'>
                        {{-- <div class='col-md-4' >
                            Proposed Effective Date :
                            <input type="date" class="form-control-sm form-control " min='{{date('Y-m-d')}}' name="effective_date" required/>
                        </div> --}}
                        <div class='col-md-6'>
                            <label>Draft Link <small class="text-muted">(Google Link)</small>:</label>
                            <input type="url" class="form-control-sm form-control" value="{{old('draft_link')}}" name="draft_link" placeholder="Enter Google Drive link" required/>
                        </div>
                        <div class='col-md-6'>
                            <label>Supporting Document <small class="text-muted">(PSF, Executive Summary, etc.)</small>:</label>
                            <input type="file" class="form-control-sm form-control" accept="application/pdf" name="supporting_document" required/>
                        </div>
                    </div>

                    <div class='row'>
                        <div class="col-md-6">
                            <label>Reason for New Request:</label>
                            <select name="reason_for_new_request" class="form-control-sm form-control" id="reason-for-new-request" required>
                                <option value="">Select Reason</option>
                                <option value="Updated Regulations or Standards">Updated Regulations or Standards (Legal Compliance and ISO standards)</option>
                                <option value="Process Improvement">Process Improvement (Technological Advancements & Operational Processes)</option>
                                <option value="Nonconformities">Nonconformities (External and Internal Findings)</option>
                                <option value="Document Modification">Document Modification (error correction, change in scope and objective, revision and new forms, minimal modifications such as adding columns, changes in formats, etc.)</option>
                                <option value="Top Management Directive">Top Management Directive</option>
                            </select>                            
                        </div>
                        <div class='col-md-6'>
                            <label>Description:</label>
                            <textarea name='description' rows="5" class="form-control-sm form-control" placeholder="Enter detailed description" required>{{old('description')}}</textarea>
                        </div>
                    </div>

                    @if((auth()->user()->role == "Document Control Officer"))
                        <div class='row'>
                            <div class='col-md-4'>
                                <label>SOFT Copy <small class="text-muted">(.word, .csv, .ppt, etc)</small>:</label>
                                <input type="file" class="form-control-sm form-control" accept="application/msword, application/vnd.ms-excel, application/vnd.ms-powerpoint" name="soft_copy" required/>
                            </div>
                            <div class='col-md-4'>
                                <label>PDF/Scanned Copy <small class="text-muted">(.pdf)</small>:</label>
                                <input type="file" class="form-control-sm form-control" accept="application/pdf" name="pdf_copy" required/>
                            </div>
                            <div class='col-md-4'>
                                <label>FILLABLE Copy <small class="text-muted">(.pdf)</small>:</label>
                                <input type="file" class="form-control-sm form-control" name="fillable_copy"/>
                            </div>
                        </div>
                    @endif

                    <div class='row'>
                        <div class='col-md-12'>
                            <hr class="section-divider">
                        </div>
                    </div>

                    <div class='row'>
                        <div class='col-md-12'>
                            <p class="requestor-info"><strong>Requestor:</strong> {{auth()->user()->name}}</p>
                        </div>
                    </div>

                    <div class="approvers-section">
                        <div class="approvers-header">
                            <strong>Action: Pre-Assessment</strong>
                        </div>
                        <div class="panel panel-primary">
                            <div class="panel-heading">
                                Approvers
                            </div>
                            <div class="panel-body">
                                @foreach ($pre_assessment_approvers as $key=>$approver)
                                    <div class='approver-row'>
                                        <div class='approver-number'>
                                            {{-- {{$approver->level}} --}}
                                            {{$key+1}}
                                        </div>
                                        <div class='approver-name'>
                                            {{$approver->user->name}}
                                        </div>
                                    </div>
                                @endforeach
                                {{-- @foreach($approvers as $approver)
                                    <div class='row'>
                                        <div class='col-md-1 text-right border border-primary border-top-bottom border-left-right'>
                                            {{$approver->level}}
                                        </div>
                                        <div class='col-md-11 border border-primary border-top-bottom border-left-right'>
                                            {{$approver->user->name}}
                                        </div>
                                    </div>
                                @endforeach --}}
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal" data-bs-dismiss="modal">Close</button>
                    <button type='submit' class="btn btn-primary" @if(count($approvers) == 0) disabled @endif>Submit</button>
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
    .section-divider {
        border: 0;
        border-top: 1px solid #dee2e6;
        margin: 20px 0;
    }

    .requestor-info {
        background: #f8f9fa;
        padding: 10px 15px;
        border-radius: 5px;
        margin-bottom: 15px;
        font-size: 14px;
    }

    .approvers-section {
        margin-top: 15px;
    }

    .approvers-header {
        font-size: 14px;
        margin-bottom: 10px;
        color: #495057;
    }

    .panel {
        border: 1px solid #dee2e6;
        border-radius: 5px;
        margin-bottom: 20px;
    }

    .panel-heading {
        background: #f8f9fa;
        padding: 12px 15px;
        border-bottom: 1px solid #dee2e6;
        border-radius: 5px 5px 0 0;
        font-weight: 500;
        font-size: 14px;
        color: #495057;
    }

    .panel-body {
        padding: 15px;
    }

    .approver-row {
        display: flex;
        align-items: center;
        border: 1px solid #8B0000;
        border-radius: 5px;
        margin-bottom: 8px;
        overflow: hidden;
    }

    .approver-number {
        background: #8B0000;
        color: white;
        padding: 10px 15px;
        font-weight: 600;
        min-width: 50px;
        text-align: center;
        font-size: 14px;
    }

    .approver-name {
        padding: 10px 15px;
        flex: 1;
        font-size: 14px;
        color: #495057;
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

    .modal-footer .btn-primary:disabled {
        background: #cccccc;
        cursor: not-allowed;
    }
</style>