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

                    {{-- <div class="col-12 col-md-6">
                        <div class="d-flex align-items-start gap-3">
                            <div class="text-primary" style="font-size: 1.5rem;">
                                <i class="ri-git-branch-line"></i>
                            </div>
                            <div>
                                <div class="text-muted small mb-1">VERSION</div>
                                <div class="fw-medium">Version 2.0</div>
                            </div>
                        </div>
                    </div> --}}

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

                <div class="mb-4">
                    <h6 class="fw-semibold text-dark mb-3">
                        <i class="ri-shield-check-line text-success me-2"></i>
                        Approval Details
                    </h6>
                    <div class="row g-3">
                        <div class="col-12 col-md-6">
                            <div class="info-box">
                                <div class="d-flex align-items-start gap-3">
                                    <div class="text-success" style="font-size: 1.5rem;">
                                        <i class="ri-user-star-line"></i>
                                    </div>
                                    <div>
                                        <div class="text-muted small mb-1">APPROVED BY</div>
                                        <div class="fw-semibold">Jane Smith</div>
                                        <div class="text-muted small">Quality Manager</div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-12 col-md-6">
                            <div class="info-box">
                                <div class="d-flex align-items-start gap-3">
                                    <div class="text-success" style="font-size: 1.5rem;">
                                        <i class="ri-calendar-check-line"></i>
                                    </div>
                                    <div>
                                        <div class="text-muted small mb-1">APPROVAL DATE</div>
                                        <div class="fw-semibold">November 09, 2024</div>
                                        <div class="text-muted small">02:30 PM</div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="info-box">
                                <div class="d-flex align-items-start gap-3">
                                    <div class="text-success" style="font-size: 1.5rem;">
                                        <i class="ri-chat-check-line"></i>
                                    </div>
                                    <div>
                                        <div class="text-muted small mb-1">APPROVAL REMARKS</div>
                                        <div class="fw-medium">Document reviewed and approved. All quality standards met and procedures are compliant with ISO 9001:2015 requirements.</div>
                                    </div>
                                </div>
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