@extends('layouts.header')

@section('content')

<div class="mb-4">
    <h4 class="fs-2 fw-semibold mb-1">Dashboard</h4>
    <p class="text-muted">Overview of your documents</p>
</div>

<div class="row g-3 mb-4 h-100">
    <div class="col-xl-3 col-md-4">
        <div class="dashboard-card pending">
            <div class="icon-circle">
                <i class="ri-file-list-3-line"></i>
            </div>
            <h2 class="mb-0 font-weight-bold">{{ $totalCount }}</h2>
            <p>Total Documents</p>
        </div>
    </div>
    <div class="col-xl-3 col-md-4">
        <div class="dashboard-card declined">
            <div class="icon-circle">
                <i class="ri-time-line"></i>
            </div>
            <h2 class="mb-0 font-weight-bold">{{ $pendingCount }}</h2>
            <p>Pending Approval</p>
        </div>
    </div>
    <div class="col-xl-3 col-md-4">
        <div class="dashboard-card approved">
            <div class="icon-circle">
                <i class="ri-checkbox-circle-line"></i>
            </div>
            <h2 class="mb-0 font-weight-bold">{{ $approvedCount }}</h2>
            <p>Approved</p>
        </div>
    </div>
    <div class="col-xl-3 col-md-4">
        <div class="dashboard-card returned">
            <div class="icon-circle">
                <i class="ri-close-circle-line"></i>
            </div>
            <h2 class="mb-0 font-weight-bold">{{ $declinedCount }}</h2>
            <p>Declined</p>
        </div>
    </div>
</div>

<div class="row g-4 mb-4">
    <div class="col-12 col-lg-8">
        <div class="card shadow-sm h-100 chart-card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div>
                        <h6 class="fw-semibold mb-0 text-dark">Monthly Document Submissions</h6>
                        <small class="text-muted">Jan – Dec {{ date('Y') }}</small>
                    </div>
                    <div class="d-flex gap-3 flex-wrap justify-content-end">
                        <span class="legend-dot" style="--dot:#17a2b8;">Submitted</span>
                        <span class="legend-dot" style="--dot:#ffc107;">Approved</span>
                        <span class="legend-dot" style="--dot:#dc3545;">Declined</span>
                    </div>
                </div>
                <div class="chart-wrap" style="height:240px;">
                    <canvas id="chartMonthly"></canvas>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12 col-lg-4">
        <div class="card shadow-sm h-100 chart-card">
            <div class="card-body d-flex flex-column">
                <div class="mb-3">
                    <h6 class="fw-semibold mb-0 text-dark">Document Status</h6>
                    <small class="text-muted">Current breakdown</small>
                </div>
                <div class="chart-wrap flex-grow-1 d-flex align-items-center justify-content-center" style="height:200px;">
                    <canvas id="chartStatus"></canvas>
                </div>
                <div class="donut-legend mt-3">
                    <div class="donut-item"><span style="background:#17a2b8"></span>Approved <strong>{{ $approvedPct }}%</strong></div>
                    <div class="donut-item"><span style="background:#ffc107"></span>Pending <strong>{{ $pendingPct }}%</strong></div>
                    <div class="donut-item"><span style="background:#dc3545"></span>Declined <strong>{{ $declinedPct }}%</strong></div>
                    <div class="donut-item"><span style="background:#bdc3c7"></span>Other <strong>{{ $otherPct }}%</strong></div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4 mb-4">
    <div class="col-12 col-lg-6">
        <div class="card shadow-sm h-100 chart-card">
            <div class="card-body">
                <div class="mb-3">
                    <h6 class="fw-semibold mb-0 text-dark">Documents by Department</h6>
                    <small class="text-muted">All departments</small>
                </div>
                <div class="chart-wrap" style="height:240px;">
                    <canvas id="chartDept"></canvas>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12 col-lg-3">
        <div class="card shadow-sm h-100 chart-card">
            <div class="card-body d-flex flex-column">
                <div class="mb-3">
                    <h6 class="fw-semibold mb-0 text-dark">Document Types</h6>
                    <small class="text-muted">By category</small>
                </div>
                <div class="chart-wrap flex-grow-1" style="height:220px;">
                    <canvas id="chartTypes"></canvas>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12 col-lg-3">
        <div class="card shadow-sm h-100 chart-card">
            <div class="card-body d-flex flex-column">
                <div class="mb-3">
                    <h6 class="fw-semibold mb-0 text-dark">This Week's Activity</h6>
                    <small class="text-muted">Uploads vs Approvals</small>
                </div>
                <div class="chart-wrap flex-grow-1" style="height:140px;">
                    <canvas id="chartWeekly"></canvas>
                </div>
                <div class="weekly-stats mt-3 d-flex justify-content-around text-center">
                    <div>
                        <div class="fw-bold text-dark" style="font-size:1.4rem;">{{ $weeklyUploaded }}</div>
                        <small class="text-muted">Uploaded</small>
                    </div>
                    <div style="border-left:1px solid #eee;"></div>
                    <div>
                        <div class="fw-bold text-dark" style="font-size:1.4rem;">{{ $weeklyApproved }}</div>
                        <small class="text-muted">Approved</small>
                    </div>
                    <div style="border-left:1px solid #eee;"></div>
                    <div>
                        <div class="fw-bold text-dark" style="font-size:1.4rem;">{{ $weeklyDeclined }}</div>
                        <small class="text-muted">Declined</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('js')
<script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
<script src="{{ asset('barcode/JsBarcode.all.min.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    document.addEventListener('DOMContentLoaded', function () {

        JsBarcode(".barcode").init();

        const TEAL = '#17a2b8';
        const TEAL_L = 'rgba(23,162,184,0.10)';
        const YELLOW = '#ffc107';
        const YELLOW_L = 'rgba(255,193,7,0.10)';
        const RED = '#dc3545';
        const RED_L = 'rgba(220,53,69,0.10)';
        const BLUE = '#3b82f6';
        const GRAY = '#bdc3c7';

        const DEPT_COLORS = [TEAL, YELLOW, RED, BLUE, TEAL, YELLOW, RED, BLUE];

        const POLAR_COLORS = [
            'rgba(23,162,184,0.80)',
            'rgba(255,193,7,0.80)',
            'rgba(220,53,69,0.80)',
            'rgba(59,130,246,0.80)',
            'rgba(23,162,184,0.45)',
            'rgba(189,195,199,0.80)',
        ];

        Chart.defaults.font.family = "'Helvetica Neue', Arial, sans-serif";
        Chart.defaults.color       = '#6c757d';

        new Chart(document.getElementById('chartMonthly'), {
            type: 'bar',
            data: {
                labels: ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'],
                datasets: [
                    {
                        label: 'Submitted',
                        data: @json($monthlySubmitted),
                        backgroundColor: TEAL,
                        borderRadius: 4,
                        borderSkipped: false,
                    },
                    {
                        label: 'Approved',
                        data: @json($monthlyApproved),
                        backgroundColor: YELLOW,
                        borderRadius: 4,
                        borderSkipped: false,
                    },
                    {
                        label: 'Declined',
                        data: @json($monthlyDeclined),
                        backgroundColor: RED,
                        borderRadius: 4,
                        borderSkipped: false,
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: { mode: 'index', intersect: false }
                },
                scales: {
                    x: { grid: { display: false }, border: { display: false } },
                    y: {
                        grid: { color: '#f0f0f0' },
                        border: { display: false, dash: [4, 4] },
                        ticks: { stepSize: 10 }
                    }
                }
            }
        });

        new Chart(document.getElementById('chartStatus'), {
            type: 'doughnut',
            data: {
                labels: ['Approved', 'Pending', 'Declined', 'Other'],
                datasets: [{
                    data: [
                        {{ $approvedCount }},
                        {{ $pendingCount }},
                        {{ $declinedCount }},
                        {{ max(0, $totalCount - $approvedCount - $pendingCount - $declinedCount) }}
                    ],
                    backgroundColor: [TEAL, YELLOW, RED, GRAY],
                    borderWidth: 2,
                    borderColor: '#fff',
                    hoverOffset: 8,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '72%',
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        callbacks: {
                            label: ctx => ` ${ctx.label}: ${ctx.parsed} docs`
                        }
                    }
                }
            }
        });

        new Chart(document.getElementById('chartDept'), {
            type: 'bar',
            data: {
                labels: @json($deptLabels),
                datasets: [{
                    label: 'Documents',
                    data: @json($deptCounts),
                    backgroundColor: DEPT_COLORS,
                    borderRadius: 4,
                    borderSkipped: false,
                }]
            },
            options: {
                indexAxis: 'y',
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        callbacks: {
                            label: c => ` ${c.parsed.x} documents`
                        }
                    }
                },
                scales: {
                    x: {
                        grid: { color: '#f0f0f0' },
                        border: { display: false, dash: [4, 4] },
                        ticks: { stepSize: 15 }
                    },
                    y: {
                        grid: { display: false },
                        border: { display: false },
                        ticks: { font: { size: 11 } }
                    }
                }
            }
        });

        new Chart(document.getElementById('chartTypes'), {
            type: 'polarArea',
            data: {
                labels: @json($typeLabels),
                datasets: [{
                    data: @json($typeCounts),
                    backgroundColor: POLAR_COLORS,
                    borderColor: '#fff',
                    borderWidth: 2,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: { font: { size: 10 }, padding: 8 }
                    }
                },
                scales: {
                    r: { ticks: { display: false }, grid: { color: '#eee' } }
                }
            }
        });

        new Chart(document.getElementById('chartWeekly'), {
            type: 'line',
            data: {
                labels: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
                datasets: [
                    {
                        label: 'Uploaded',
                        data: @json($weeklyUploadedByDay),
                        borderColor: TEAL,
                        backgroundColor: TEAL_L,
                        fill: true,
                        tension: 0.4,
                        pointRadius: 3,
                        pointBackgroundColor: TEAL,
                        borderWidth: 2,
                    },
                    {
                        label: 'Approved',
                        data: @json($weeklyApprovedByDay),
                        borderColor: YELLOW,
                        backgroundColor: YELLOW_L,
                        fill: true,
                        tension: 0.4,
                        pointRadius: 3,
                        pointBackgroundColor: YELLOW,
                        borderWidth: 2,
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: { mode: 'index', intersect: false }
                },
                scales: {
                    x: {
                        grid: { display: false },
                        border: { display: false },
                        ticks: { font: { size: 10 } }
                    },
                    y: { display: false }
                }
            }
        });

    });
</script>
@endsection