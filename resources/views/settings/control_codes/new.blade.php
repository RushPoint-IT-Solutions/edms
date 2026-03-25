<div class="modal fade" id="new_control_code" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header border-bottom">
                <h5 class="modal-title mb-3">New Control Code</h5>
                <button type="button" class="btn-close mb-3" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('control-codes.store') }}" method="POST">
                @csrf
                <div class="modal-body">

                    <div class="mb-3">
                        <label class="form-label">Document Type <span class="text-danger">*</span></label>
                        <select name="document_type_id" id="select_doc_type" class="form-select" required>
                            <option value="">-- Select Type --</option>
                            @foreach($documentTypes as $type)
                                <option value="{{ $type->id }}" data-name="{{ $type->name }}">{{ $type->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Offices <span class="text-danger">*</span></label>
                        <select name="department_id" id="select_department" class="form-select" required>
                            <option value="">-- Select Office --</option>
                            @foreach($offices as $office)
                                <option value="{{ $office->id }}" data-code="{{ $office->code }}">{{ $office->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Code Preview</label>
                        <input type="text" id="code_preview" class="form-control bg-light" readonly
                            placeholder="Select type and department first">
                        <small class="text-muted">
                            <i class="ri-information-line me-1"></i>
                            Exact series number (e.g. <strong>0001</strong>) will be assigned upon saving.
                        </small>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <input type="text" name="description" class="form-control" placeholder="Optional description">
                    </div>

                </div>
                <div class="modal-footer border-top">
                    <button type="button" class="btn btn-secondary mt-3" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary mt-3">
                        <i class="fa fa-save me-1"></i> Save
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function updateCodePreview() {
        const docType = document.querySelector('#select_doc_type option:checked')?.dataset.name  ?? '';
        const deptCode = document.querySelector('#select_department option:checked')?.dataset.code ?? '';
        const year = new Date().getFullYear();

        if (docType && deptCode) {
            document.getElementById('code_preview').value = `MarSU-${deptCode}-${docType}-${year}-????`;
        } else {
            document.getElementById('code_preview').value = '';
        }
    }

    document.getElementById('select_doc_type').addEventListener('change', updateCodePreview);
    document.getElementById('select_department').addEventListener('change', updateCodePreview);
</script>