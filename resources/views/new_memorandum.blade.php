<div class="modal fade" id="new" tabindex="-1" role="dialog" aria-labelledby="newModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="newModalLabel">New Memorandum</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST" action="{{url('store_memorandum')}}" enctype="multipart/form-data" onsubmit="show()">
                @csrf 

                <div class="modal-body">
                    <div class='row'>
                        <div class='col-md-12'>
                            <label>Memo Number :</label>
                            <input type="text" name="memo_number" class="form-control-sm form-control" placeholder="Enter memo number" required>
                        </div>
                        <div class='col-md-12'>
                            <label>Title :</label>
                            <input type="text" name="title" class="form-control-sm form-control" placeholder="Enter memorandum title" required>
                        </div>
                        <div class='col-md-12'>
                            <label>Released Date :</label>
                            <input type="date" name="released_date" class="form-control-sm form-control" max="{{date('Y-m-d')}}" required>
                        </div>
                        <div class='col-md-12'>
                            <label>Type :</label>
                            <select data-placeholder="Select type" name="type" id="type" class="form-control-sm form-control cat" required>
                                <option value="">Choose type...</option>
                                <option value="Informative">Informative</option>
                                <option value="Align Policy">Align Policy</option>
                            </select>
                        </div>
                        <div class='col-md-12' id="policySelectOption" hidden>
                            <label>Align Policy :</label>
                            <select data-placeholder="Choose policy" name="document[]" class="form-control-sm form-control cat" multiple>
                                <option value="">Select policies...</option>
                                @foreach ($documents as $document)
                                    <option value="{{$document->id}}">{{$document->control_code .' - '.$document->title}}</option>
                                @endforeach
                            </select>
                            <small class="text-muted">Hold Ctrl/Cmd to select multiple policies</small>
                        </div>
                        <div class='col-md-12'>
                            <label>Upload Memo File :</label>
                            <input type="file" name="memo_file" class="form-control-sm form-control" accept=".pdf,.doc,.docx" required>
                            <small class="text-muted">Accepted formats: PDF, DOC, DOCX</small>
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