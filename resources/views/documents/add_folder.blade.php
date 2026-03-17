<div class="modal fade" id="createFolderModal" tabindex="-1" aria-labelledby="createFolderModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0">
            <div class="modal-header border-bottom border-2">
                <h5 class="modal-title mb-3" id="createFolderModalLabel">Create Folder</h5>
                <button type="button" class="btn-close mb-3" data-bs-dismiss="modal" id="addFolderBtn-close" aria-label="Close"></button>
            </div>

            <form autocomplete="off" method="POST" action="{{ url('documents/store-folder') }}" id="createfolder-form" onsubmit="show()">
                <div class="modal-body">
                    @csrf
                    {{-- @dd($folder) --}}
                    @if(isset($folder_data))
                    <input type="hidden" name="folder_id" value="{{ $folder_data->id }}">
                    @endif

                    <div class="mb-4">
                        <label for="foldername-input" class="form-label">Folder Name</label>
                        <input type="text" name="name" class="form-control" id="foldername-input" required placeholder="Enter folder name">
                    </div>
                </div>
                <div class="modal-footer border-top border-2">
                    <button type="button" class="btn btn-secondary mt-3" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary mt-3">Add Folder</button>
                </div>
            </form>
        </div>
    </div>
</div>