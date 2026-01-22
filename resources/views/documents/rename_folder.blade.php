<div class="modal fade" id="renameFolderModal{{ $folder->id }}" tabindex="-1" aria-labelledby="renameFolderModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0">
            <div class="modal-header p-3 bg-success-subtle">
                <h5 class="modal-title" id="renameFolderModalLabel">Rename Folder</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" id="addFolderBtn-close" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form autocomplete="off" method="POST" action="{{ url('documents/rename-folder/'.$folder->id) }}" onsubmit="show()">
                    @csrf

                    <div class="mb-4">
                        <label for="foldername-input" class="form-label">Folder Name</label>
                        <input type="text" name="name" class="form-control" id="foldername-input" value="{{ $folder->name }}" required placeholder="Enter folder name">
                    </div>
                    <div class="hstack gap-2 justify-content-end">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal"><i class="ri-close-line align-bottom"></i> Close</button>
                        <button type="submit" class="btn btn-primary" id="addNewFolder">Rename Folder</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>