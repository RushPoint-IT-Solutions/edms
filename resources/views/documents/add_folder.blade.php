<div class="modal fade" id="createFolderModal" tabindex="-1" aria-labelledby="createFolderModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0">
            <div class="modal-header p-3">
                <h5 class="modal-title" id="createFolderModalLabel">Create Folder</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" id="addFolderBtn-close" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form autocomplete="off" method="POST" action="{{ url('documents/store-folder') }}" class="needs-validation createfolder-form" id="createfolder-form" onsubmit="show()">
                    @csrf
                    {{-- @dd($folder) --}}
                    @if(isset($folder_data))
                    <input type="hidden" name="folder_id" value="{{ $folder_data->id }}">
                    @endif

                    <div class="mb-4">
                        <label for="foldername-input" class="form-label">Folder Name</label>
                        <input type="text" name="name" class="form-control" id="foldername-input" required placeholder="Enter folder name">
                    </div>
                    <div class="hstack gap-2 justify-content-end">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal"> Close</button>
                        <button type="submit" class="btn btn-primary" id="addNewFolder">Add Folder</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>