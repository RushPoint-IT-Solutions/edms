<div class="modal fade" id="revisionStatusModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="ri-git-branch-line me-2"></i>Revision status
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <label class="form-label text-muted small fw-semibold text-uppercase">Request ID</label>
                        <div class="form-control-plaintext border rounded px-3 py-2 bg-light" id="rsDocId"></div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label text-muted small fw-semibold text-uppercase">Status</label>
                        <div class="form-control-plaintext border rounded px-3 py-2 bg-light" id="rsStatus"></div>
                    </div>
                    <div class="col-12">
                        <label class="form-label text-muted small fw-semibold text-uppercase">Title</label>
                        <div class="form-control-plaintext border rounded px-3 py-2 bg-light" id="rsTitle"></div>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label text-muted small fw-semibold text-uppercase">Category</label>
                        <div class="form-control-plaintext border rounded px-3 py-2 bg-light" id="rsCategory"></div>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label text-muted small fw-semibold text-uppercase">Revision</label>
                        <div class="form-control-plaintext border rounded px-3 py-2 bg-light" id="rsRevision"></div>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label text-muted small fw-semibold text-uppercase">Date requested</label>
                        <div class="form-control-plaintext border rounded px-3 py-2 bg-light" id="rsDateRequested"></div>
                    </div>
                    <div class="col-12">
                        <label class="form-label text-muted small fw-semibold text-uppercase">Requested by</label>
                        <div class="form-control-plaintext border rounded px-3 py-2 bg-light" id="rsRequestedBy"></div>
                    </div>
                </div>

                <p class="text-muted small fw-semibold text-uppercase mb-3">Approval chain</p>
                <div id="rsApproverChain" class="approver-chain-list"></div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>