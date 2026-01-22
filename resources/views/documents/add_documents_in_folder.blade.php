<div class="modal fade" id="addDocumentInFolder" tabindex="-1" aria-labelledby="addDocumentInFolderLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0">
            <div class="modal-header p-3">
                <h5 class="modal-title" id="addDocumentInFolderLabel">Upload document</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" id="addFolderBtn-close" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form autocomplete="off" method="POST" action="{{ url('documents/upload-document-folder') }}" id="createfolder-form" onsubmit="show()">
                    @csrf
                    
                    <input type="hidden" name="folder_id" id="moveDocumentFolder">
                    
                    <div class="mb-4">
                        <label for="foldername-input" class="form-label">Select documents</label>
                        <select data-placeholder="Select document" name="documents[]" class="select2" multiple>
                            <option value=""></option>
                            @foreach ($documents as $document_data)
                                <option value="{{ $document_data->id }}">{{ $document_data->control_code." ".$document_data->title }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="hstack gap-2 justify-content-end">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal"> Close</button>
                        <button type="submit" class="btn btn-primary" id="addNewFolder">Upload</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>