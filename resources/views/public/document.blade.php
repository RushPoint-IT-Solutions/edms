<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document Details</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/remixicon@3.5.0/fonts/remixicon.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            min-height: 100vh;
            padding: 40px 20px;
        }
        .document-container {
            max-width: 900px;
            margin: 0 auto;
        }
        .card {
            border: none;
            border-radius: 16px;
        }
        .info-box {
            background: #f8f9fc;
            padding: 20px;
            border-radius: 12px;
            transition: all 0.3s ease;
        }
        .info-box:hover {
            background: #ffffff;
            box-shadow: 0 4px 12px rgba(0,0,0,0.08);
            transform: translateY(-2px);
        }
        .icon-circle {
            width: 48px;
            height: 48px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.5rem;
            flex-shrink: 0;
        }
        .hover-effect:hover {
            background: #f8f9fc;
            transform: translateX(5px);
        }
        .hover-effect {
            transition: all 0.3s ease;
        }
        .approver-card {
            border: 1px solid #e9ecef;
            border-radius: 8px;
            padding: 12px 16px;
            margin-bottom: 12px;
        }
        .approver-card:last-child {
            margin-bottom: 0;
        }
    </style>
</head>
<body>
    <div class="document-container">
        <div class="mb-4">
            <h4 class="fs-2 fw-semibold mb-1">Document Details</h4>
            <p class="text-muted">Scanned document information</p>
        </div>

        <div class="card shadow-sm">
            <div class="card-body p-4">
                <div class="mb-4">
                    <h5 class="fw-semibold text-dark mb-2">Quality Management System Manual</h5>
                    <span class="badge bg-primary">Doc-2024-001</span>
                </div>

                <hr>

                <div class="row g-4">
                    <div class="col-12 col-md-6">
                        <div class="d-flex align-items-start gap-3">
                            <div class="text-primary" style="font-size: 1.5rem;">
                                <i class="ri-checkbox-circle-line"></i>
                            </div>
                            <div>
                                <div class="text-muted small mb-1">STATUS</div>
                                <span class="badge bg-success">Active</span>
                            </div>
                        </div>
                    </div>

                    <div class="col-12 col-md-6">
                        <div class="d-flex align-items-start gap-3">
                            <div class="text-primary" style="font-size: 1.5rem;">
                                <i class="ri-user-line"></i>
                            </div>
                            <div>
                                <div class="text-muted small mb-1">CREATED BY</div>
                                <div class="fw-medium">John Doe</div>
                            </div>
                        </div>
                    </div>

                    <div class="col-12 col-md-6">
                        <div class="d-flex align-items-start gap-3">
                            <div class="text-primary" style="font-size: 1.5rem;">
                                <i class="ri-building-line"></i>
                            </div>
                            <div>
                                <div class="text-muted small mb-1">DEPARTMENT</div>
                                <div class="fw-medium">Quality Assurance</div>
                            </div>
                        </div>
                    </div>

                    <div class="col-12 col-md-6">
                        <div class="d-flex align-items-start gap-3">
                            <div class="text-primary" style="font-size: 1.5rem;">
                                <i class="ri-folder-line"></i>
                            </div>
                            <div>
                                <div class="text-muted small mb-1">CATEGORY</div>
                                <div class="fw-medium">Quality Management</div>
                            </div>
                        </div>
                    </div>

                    <div class="col-12 col-md-6">
                        <div class="d-flex align-items-start gap-3">
                            <div class="text-primary" style="font-size: 1.5rem;">
                                <i class="ri-calendar-line"></i>
                            </div>
                            <div>
                                <div class="text-muted small mb-1">CREATED DATE</div>
                                <div class="fw-medium">November 08, 2024</div>
                            </div>
                        </div>
                    </div>

                    <div class="col-12 col-md-6">
                        <div class="d-flex align-items-start gap-3">
                            <div class="text-primary" style="font-size: 1.5rem;">
                                <i class="ri-time-line"></i>
                            </div>
                            <div>
                                <div class="text-muted small mb-1">LAST UPDATED</div>
                                <div class="fw-medium">Nov 10, 2024</div>
                            </div>
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="d-flex align-items-start gap-3">
                            <div class="text-primary" style="font-size: 1.5rem;">
                                <i class="ri-file-list-line"></i>
                            </div>
                            <div>
                                <div class="text-muted small mb-1">DESCRIPTION</div>
                                <div class="fw-medium">Comprehensive quality management system manual containing all standard operating procedures, policies, and quality control measures.</div>
                            </div>
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="d-flex align-items-start gap-3">
                            <div class="text-primary" style="font-size: 1.5rem;">
                                <i class="ri-attachment-line"></i>
                            </div>
                            <div class="flex-grow-1">
                                <div class="text-muted small mb-2">ATTACHMENT</div>
                                <a href="#" class="text-decoration-none d-flex align-items-center gap-2 p-3 border rounded hover-effect" onclick="alert('Hindi pa available to sah'); return false;">
                                    <i class="ri-file-pdf-line text-danger" style="font-size: 1.25rem;"></i>
                                    <span class="text-dark fw-medium" style="font-size: 0.875rem;">DMS_Manual.pdf</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                
                <hr class="my-4">

                <div class="mb-3">
                    <div class="d-flex align-items-center gap-2 mb-3">
                        <i class="ri-shield-check-line text-success" style="font-size: 1.5rem;"></i>
                        <h6 class="fw-semibold text-dark mb-0">Approval Details</h6>
                    </div>

                    <div class="approver-card">
                        <div class="d-flex align-items-center justify-content-between">
                            <div class="flex-grow-1">
                                <div class="d-flex align-items-center gap-2 mb-1">
                                    <div class="fw-semibold text-dark" style="font-size: 0.9rem;">Jane Smith</div>
                                    <span class="badge bg-success" style="font-size: 0.7rem;">Approved</span>
                                </div>
                                <div class="text-muted" style="font-size: 0.75rem;">Quality Manager • Nov 09, 2024 at 02:30 PM</div>
                            </div>
                            <button class="btn btn-link text-decoration-none p-0" 
                                    type="button" 
                                    data-bs-toggle="collapse" 
                                    data-bs-target="#approver1" 
                                    aria-expanded="false" 
                                    aria-controls="approver1"
                                    onclick="this.querySelector('i').classList.toggle('ri-arrow-down-s-line'); this.querySelector('i').classList.toggle('ri-arrow-up-s-line');">
                                <i class="ri-arrow-down-s-line text-muted" style="font-size: 1.25rem;"></i>
                            </button>
                        </div>
                        
                        <div class="collapse mt-2" id="approver1">
                            <div class="border-top pt-2 mt-2">
                                <div class="text-muted small fw-semibold mb-1">
                                    <i class="ri-chat-check-line me-1"></i>REMARKS
                                </div>
                                <div class="text-muted" style="font-size: 0.8rem;">Document reviewed and approved. All quality standards met and procedures are compliant with ISO 9001:2015 requirements.</div>
                            </div>
                        </div>
                    </div>

                    <div class="approver-card">
                        <div class="d-flex align-items-center justify-content-between">
                            <div class="flex-grow-1">
                                <div class="d-flex align-items-center gap-2 mb-1">
                                    <div class="fw-semibold text-dark" style="font-size: 0.9rem;">Michael Johnson</div>
                                    <span class="badge bg-success" style="font-size: 0.7rem;">Approved</span>
                                </div>
                                <div class="text-muted" style="font-size: 0.75rem;">Operations Director • Nov 10, 2024 at 09:15 AM</div>
                            </div>
                            <button class="btn btn-link text-decoration-none p-0" 
                                    type="button" 
                                    data-bs-toggle="collapse" 
                                    data-bs-target="#approver2" 
                                    aria-expanded="false" 
                                    aria-controls="approver2"
                                    onclick="this.querySelector('i').classList.toggle('ri-arrow-down-s-line'); this.querySelector('i').classList.toggle('ri-arrow-up-s-line');">
                                <i class="ri-arrow-down-s-line text-muted" style="font-size: 1.25rem;"></i>
                            </button>
                        </div>
                        
                        <div class="collapse mt-2" id="approver2">
                            <div class="border-top pt-2 mt-2">
                                <div class="text-muted small fw-semibold mb-1">
                                    <i class="ri-chat-check-line me-1"></i>REMARKS
                                </div>
                                <div class="text-muted" style="font-size: 0.8rem;">Procedures align with operational requirements. Document is comprehensive and ready for implementation.</div>
                            </div>
                        </div>
                    </div>
                </div>

                <hr class="my-4">
                <div class="text-center text-muted small">
                    <i class="ri-information-line"></i> This is an official document from the Library Management System<br>
                    <small>Scanned on: <span id="currentDateTime"></span></small>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const now = new Date();
        const options = { 
            year: 'numeric', 
            month: 'long', 
            day: 'numeric', 
            hour: '2-digit', 
            minute: '2-digit',
            hour12: true 
        };
        document.getElementById('currentDateTime').textContent = now.toLocaleString('en-US', options);
    </script>
</body>
</html>