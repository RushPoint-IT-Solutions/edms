<!doctype html>
<html lang="en" data-layout="vertical" data-topbar="light" data-sidebar="dark" data-sidebar-size="lg" data-sidebar-image="none" data-preloader="disable" data-theme="default" data-theme-colors="default">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'LiMS') }}</title>
    <link rel="shortcut icon" href="{{url('assets/images/marsu-logo.png')}}">

    <!-- Layout config Js -->
    <script src="{{asset('/assets/js/layout.js')}}"></script>
    <!-- Bootstrap Css -->
    <link href="{{asset('/assets/css/bootstrap.min.css')}}" rel="stylesheet" type="text/css" />
    <!-- Icons Css -->
    <link href="{{asset('/assets/css/icons.min.css')}}" rel="stylesheet" type="text/css" />
    <!-- App Css-->
    <link href="{{asset('/assets/css/app.min.css')}}" rel="stylesheet" type="text/css" />
    <!-- custom Css-->
    <link href="{{asset('/assets/css/custom.min.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('/assets/css/style.css')}}" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.bootstrap5.min.css">

    <style>
        .helpdesk-link-wrapper {
            position: absolute;
            bottom: 0;
            width: 100%;
        }
        
        .loader {
            position: fixed;
            left: 0px;
            top: 0px;
            width: 100%;
            height: 100%;
            z-index: 9999;
            background: url("{{ asset('assets/images/loader.gif') }}") 50% 50% no-repeat white;
            opacity: .8;
            background-size: 120px 120px;
        }   
        .menu-title, .navbar-menu .navbar-nav .nav-link {
            color: #FFF !important;
        }
        .navbar-menu {
            background: #420906 !important;
        }

        .nav-item:has(.menu-dropdown .nav-link.active) > .nav-link {
            background-color: rgba(255, 193, 7, 0.1);
            border-left: 3px solid #ffffff;
        }

        .nav-item.active > .nav-link.collapsed {
            background-color: rgba(255, 193, 7, 0.15);
            border-left: 4px solid #ffffff;
        }

        .nav-item.active > .nav-link:not(.collapsed) {
            background-color: rgba(255, 193, 7, 0.15);
            border-left: 4px solid #ffffff;
        }

        .menu-dropdown .nav-item a.nav-link.active {
            color: #ffffff !important;
            font-weight: 600;
            background-color: rgba(255, 193, 7, 0.1);
            border-left: 2px solid #ffffff;
            padding-left: calc(0.75rem - 2px);
        }

        .menu-dropdown .nav-item a.nav-link:hover {
            background-color: rgba(255, 255, 255, 0.03);
            transition: all 0.3s ease;
        }

        .menu-dropdown .nav-item a.nav-link {
            color: rgba(255, 255, 255, 0.8) !important;
        }

        /* --- Pre-loader Container --- */
        #preloaderMarsu {
            background-color: white; 
            width: 100%;
            height: 100%;
            /* Center the logo vertically and horizontally */
            display: flex;
            justify-content: center;
            align-items: center;
            position: fixed;
            top: 0;
            left: 0;
            z-index: 9999;
            opacity: .8;
        }

        /* --- Logo Placeholder Style --- */
        .logo-placeholder {
            width: 150px;
            height: 150px;
            /* background-color: #007bff;  */
            opacity: 2;
            color: white;
            font-size: 1.5em;
            font-weight: bold;
            text-align: center;
            line-height: 150px;
            border-radius: 10px;
            animation: bounce 1s infinite alternate;
        }

        /* --- Keyframes for the Bouncing Animation --- */
        @keyframes bounce {
            0% {
                transform: translateY(0);
            }
            50% {
                transform: translateY(-15px); 
            }
            100% {
                transform: translateY(0);
            }
        }

        .dashboard-card {
            background: white;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
            transition: all 0.3s;
            height: 100%;
            display: flex;
            flex-direction: column;
        }

        .dashboard-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.12);
        }

        .dashboard-card .icon-circle {
            width: 45px;
            height: 45px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 12px;
            font-size: 20px;
            flex-shrink: 0;
        }

        .dashboard-card.pending .icon-circle {
            background: #e8f5e9;
            color: #4caf50;
        }

        .dashboard-card.declined .icon-circle {
            background: #fff3e0;
            color: #ff9800;
        }

        .dashboard-card.approved .icon-circle {
            background: #e3f2fd;
            color: #2196F3;
        }

        .dashboard-card h2 {
            font-size: 28px;
            font-weight: 700;
            margin: 0 0 4px 0;
            color: #2c3e50;
            line-height: 1;
        }

        .dashboard-card p {
            margin: 0;
            font-size: 13px;
            color: #6c757d;
            font-weight: 500;
        }

        .top-controls-container {
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            flex-wrap: wrap;
            gap: 10px;
            margin-bottom: 15px;
        }

        .left-controls {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .right-controls {
            display: flex;
            flex-direction: column;
            align-items: flex-end;
            gap: 10px;
        }

        .search-wrapper {
            display: flex;
            justify-content: flex-end;
        }

        .buttons-wrapper {
            display: flex;
            align-items: center;
            justify-content: flex-end;
            gap: 8px;
        }

        .bottom-controls-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 10px;
            margin-top: 15px;
            padding-top: 15px;
            border-top: 1px solid #dee2e6;
        }

        .table-scroll-container {
            overflow-x: auto;
        }

        .dataTables_length {
            margin: 0 !important;
            display: flex;
            align-items: center;
        }

        .dataTables_length label {
            display: flex;
            align-items: center;
            gap: 5px;
            margin: 0;
        }

        .dataTables_length select {
            padding: 6px 30px 6px 10px;
            border: 1px solid #dee2e6;
            border-radius: 4px;
            margin: 0;
        }

        .dataTables_filter {
            margin: 0 !important;
            display: flex;
            align-items: center;
        }

        .dataTables_filter label {
            display: flex;
            align-items: center;
            gap: 5px;
            margin: 0;
        }

        .dataTables_filter input {
            padding: 6px 12px;
            border: 1px solid #dee2e6;
            border-radius: 4px;
            margin: 0;
        }

        .dataTables_info {
            margin: 0 !important;
            padding: 8px 0;
        }

        .dataTables_paginate {
            margin: 0 !important;
        }

        .dt-buttons {
            margin: 0 !important;
            display: flex;
            gap: 5px;
        }

        .dt-buttons .dt-button {
            background: transparent !important;
            border: 1px solid #dee2e6 !important;
            color: #6c757d !important;
            padding: 4px 10px !important;
            border-radius: 4px !important;
            font-size: 12px !important;
            font-weight: 500 !important;
            white-space: nowrap;
            box-shadow: none !important;
            transition: all 0.2s;
        }

        .dt-buttons .dt-button:hover {
            background: #f8f9fa !important;
            border-color: #adb5bd !important;
            color: #495057 !important;
        }

        .dt-buttons .dt-button:first-child {
            border-radius: 4px 0 0 4px !important;
        }

        .dt-buttons .dt-button:last-child {
            border-radius: 0 4px 4px 0 !important;
            margin-left: -1px;
        }

        table.dataTable {
            width: 100% !important;
        }

        .table {
            margin-bottom: 0 !important;
        }

        .table thead th {
            white-space: nowrap;
            background-color: #f8f9fa;
        }
    </style>
    @yield('css')
    

</head>
<body>
    {{-- <div id="loader" class="loader"></div> --}}
    <div id="preloaderMarsu">
        <div class="logo-placeholder">
            <img src="{{asset('assets/images/marsu-logo.png')}}" alt="" height="120">
        </div>
    </div>

    <div id="layout-wrapper">

        <header id="page-topbar">
            <div class="layout-width">
                <div class="navbar-header">
                    <div class="d-flex">
                        <div class="navbar-brand-box horizontal-logo">
                            <a href="{{url('/')}}" class="logo logo-dark">
                                <span class="logo-sm">
                                    <img src="{{asset('assets/images/library-icon.png')}}" alt="" height="100">
                                </span>
                                <span class="logo-lg">
                                    <img src="{{asset('assets/images/library-icon.png')}}" alt="" height="100">
                                </span>
                            </a>

                            <a href="{{url('/')}}" class="logo logo-light">
                                <span class="logo-sm">
                                    <img src="{{asset('assets/images/library-icon.png')}}" alt="" height="100">
                                </span>
                                <span class="logo-lg">
                                    <img src="{{asset('assets/images/library-icon.png')}}" alt="" height="100">
                                </span>
                            </a>
                        </div>
                        
                        <button type="button" class="btn btn-sm px-3 fs-16 header-item vertical-menu-btn topnav-hamburger material-shadow-none" id="topnav-hamburger-icon">
                            <span class="hamburger-icon">
                                <span></span>
                                <span></span>
                                <span></span>
                            </span>
                        </button>
                        
                        <form class="app-search">
                            <div class="position-relative">
                                <input type="text" class="form-control searchbar" placeholder="Search..." autocomplete="off">
                                <span class="mdi mdi-magnify search-widget-icon"></span>
                            </div>
                        </form>
                    </div>

                    @php
                        $draft_requests  = getDraftRequest();
                        $dueDateAlerts   = getDueDateAlerts();
                        $totalNotifCount = count($draft_requests) + $dueDateAlerts->count();
                    @endphp

                    <div class="d-flex align-items-center">

                        <div class="dropdown topbar-head-dropdown ms-1 header-item" id="notificationDropdown">
                            <button type="button" 
                                    class="btn btn-icon btn-topbar material-shadow-none btn-ghost-secondary rounded-circle" 
                                    id="page-header-notifications-dropdown" 
                                    data-bs-toggle="dropdown" 
                                    data-bs-auto-close="outside" 
                                    aria-haspopup="true" 
                                    aria-expanded="false">
                                <i class='bx bx-bell fs-22'></i>
                                <span class="position-absolute topbar-badge fs-10 translate-middle badge rounded-pill bg-danger">
                                    {{ $totalNotifCount }}
                                    <span class="visually-hidden">notifications</span>
                                </span>
                            </button>

                            <div class="dropdown-menu dropdown-menu-lg dropdown-menu-end p-0" aria-labelledby="page-header-notifications-dropdown">
                                <div class="dropdown-head bg-pattern rounded-top" style="background-color: #800000;">
                                    <div class="p-3">
                                        <div class="row align-items-center">
                                            <div class="col">
                                                <h6 class="m-0 fs-16 fw-semibold text-white">Notifications</h6>
                                            </div>
                                            <div class="col-auto dropdown-tabs">
                                                <span class="badge bg-light text-body fs-13">{{ $totalNotifCount }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="tab-content position-relative" id="notificationItemsTabContent">
                                    <div class="tab-pane fade show active py-2 ps-2" id="all-noti-tab" role="tabpanel">
                                        <div data-simplebar style="max-height: 300px;" class="pe-2">

                                            @foreach ($dueDateAlerts as $alert)
                                            @php
                                                $isOverdue = $alert->is_overdue;
                                                $dueDate   = \Carbon\Carbon::parse($alert->due_date);
                                            @endphp
                                            <div class="text-reset notification-item d-block dropdown-item position-relative">
                                                <div class="d-flex">
                                                    <div class="avatar-xs me-3 flex-shrink-0">
                                                        <span class="avatar-title rounded-circle fs-16 {{ $isOverdue ? 'bg-danger-subtle text-danger' : 'bg-warning-subtle text-warning' }}">
                                                            <i class="{{ $isOverdue ? 'bx bx-alarm-exclamation' : 'bx bx-time' }}"></i>
                                                        </span>
                                                    </div>
                                                    <div class="flex-grow-1">
                                                        <a href="{{ url('change-request/' . $alert->id) }}" class="stretched-link">
                                                            <h6 class="mt-0 mb-1 lh-base">
                                                                DOC-{{ date('Y', strtotime($alert->created_at)) }}-{{ str_pad($alert->id, 3, '0', STR_PAD_LEFT) }}
                                                            </h6>
                                                        </a>
                                                        <p class="mb-1 fs-11 text-truncate" style="max-width: 220px;">
                                                            {{ $alert->title }}
                                                        </p>
                                                        <p class="mb-0 fs-11 fw-medium text-uppercase {{ $isOverdue ? 'text-danger' : 'text-warning' }}">
                                                            <i class="mdi mdi-calendar-clock"></i>
                                                            @if($isOverdue)
                                                                Overdue since {{ $dueDate->format('M d, Y') }}
                                                            @else
                                                                Due {{ $dueDate->diffForHumans() }}
                                                            @endif
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                            @endforeach

                                            @if($dueDateAlerts->count() > 0 && count($draft_requests) > 0)
                                            <div class="dropdown-divider my-1"></div>
                                            @endif

                                            @foreach ($draft_requests as $draft_request)
                                            <div class="text-reset notification-item d-block dropdown-item position-relative">
                                                <div class="d-flex">
                                                    <div class="avatar-xs me-3 flex-shrink-0">
                                                        <span class="avatar-title bg-info-subtle text-info rounded-circle fs-16">
                                                            <i class="bx bx-file"></i>
                                                        </span>
                                                    </div>
                                                    <div class="flex-grow-1">
                                                        <a href="{{ url('documents/create/'. $draft_request->id) }}" class="stretched-link">
                                                            <h6 class="mt-0 mb-2 lh-base">
                                                                DOC-{{ date('Y', strtotime($draft_request->created_at)) }}-{{ str_pad($draft_request->id,'3',0,STR_PAD_LEFT) }}
                                                            </h6>
                                                        </a>
                                                        <p class="mb-0 fs-11 fw-medium text-uppercase text-muted">
                                                            <span><i class="mdi mdi-pencil"></i> {{ $draft_request->title }}</span>
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                            @endforeach

                                            @if($totalNotifCount === 0)
                                            <div class="text-center p-4 text-muted">
                                                <i class="bx bx-bell-off fs-24 mb-2 d-block"></i>
                                                <small>No notifications</small>
                                            </div>
                                            @endif

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        @php
                            $pendingApproval = getPendingApproval();
                        @endphp
                        <div class="dropdown topbar-head-dropdown ms-1 header-item" id="messagesDropdown">
                            <button type="button" 
                                    class="btn btn-icon btn-topbar material-shadow-none btn-ghost-secondary rounded-circle" 
                                    id="page-header-messages-dropdown" 
                                    data-bs-toggle="dropdown" 
                                    data-bs-auto-close="outside" 
                                    aria-haspopup="true" 
                                    aria-expanded="false">
                                <i class='bx bx-message-square-dots fs-22'></i>
                                <span class="position-absolute topbar-badge fs-10 translate-middle badge rounded-pill bg-success">
                                    {{ count($pendingApproval) }}
                                    <span class="visually-hidden">pending approvals</span>
                                </span>
                            </button>

                            <div class="dropdown-menu dropdown-menu-lg dropdown-menu-end p-0" aria-labelledby="page-header-messages-dropdown">
                                <div class="dropdown-head bg-pattern rounded-top" style="background-color: #800000;">
                                    <div class="p-3">
                                        <div class="row align-items-center">
                                            <div class="col">
                                                <h6 class="m-0 fs-16 fw-semibold text-white">Pending Approval</h6>
                                            </div>
                                            <div class="col-auto dropdown-tabs">
                                                <span class="badge bg-light text-body fs-13">{{ count($pendingApproval) }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="tab-content position-relative" id="messageItemsTabContent">
                                    <div class="tab-pane fade show active py-2 ps-2" id="all-messages-tab" role="tabpanel">
                                        <div data-simplebar style="max-height: 300px;" class="pe-2">
                                            @foreach ($pendingApproval as $request)
                                            @php
                                                $req = $request->change_request;
                                            @endphp
                                            <div class="text-reset notification-item d-block dropdown-item">
                                                <div class="d-flex">
                                                    <img src="{{asset('assets/images/marsu-logo.png')}}" class="me-3 rounded-circle avatar-xs flex-shrink-0" alt="user-pic">
                                                    <div class="flex-grow-1">
                                                        <a href="{{ url('change-request/for_approval/'.$req->id) }}" class="stretched-link">
                                                            <h6 class="mt-0 mb-1 fs-13 fw-semibold">DOC-{{ str_pad($req->id,3,'0',STR_PAD_LEFT) }}</h6>
                                                        </a>
                                                        <div class="fs-13 text-muted">
                                                            <p class="mb-1">{{ $req->title }}</p>
                                                        </div>
                                                        <p class="mb-0 fs-11 fw-medium text-uppercase text-muted">
                                                            <span><i class="mdi mdi-clock-outline"></i> {{ $request->created_at->diffForHumans() }}</span>
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                            @endforeach

                                            @if(count($pendingApproval) === 0)
                                            <div class="text-center p-4 text-muted">
                                                <i class="bx bx-check-circle fs-24 mb-2 d-block"></i>
                                                <small>No pending approvals</small>
                                            </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="dropdown ms-sm-3 header-item topbar-user">
                            <button type="button" class="btn material-shadow-none" id="page-header-user-dropdown" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span class="d-flex align-items-center">
                                    <img class="rounded-circle header-profile-user" 
                                        src="{{asset(auth()->user()->avatar)}}" 
                                        onerror="this.src='{{url('assets/images/marsu-logo.png')}}';" 
                                        alt="Header Avatar">
                                    <span class="text-start ms-xl-2">
                                        <span class="d-none d-xl-inline-block ms-1 fw-medium user-name-text">{{ current(explode(' ',auth()->user()->name)) }}</span>
                                    </span>
                                </span>
                            </button>
                            <div class="dropdown-menu dropdown-menu-end">
                                <h6 class="dropdown-header">Welcome {{current(explode(' ',auth()->user()->name))}}!</h6>
                                <div class="dropdown-divider"></div>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="{{ route('logout') }}" onclick="logout(); show();"> 
                                    <i class="mdi mdi-logout text-muted fs-16 align-middle me-1"></i> 
                                    <span class="align-middle" data-key="t-logout">Logout</span>
                                </a>
                                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                    {{ csrf_field() }}
                                </form>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </header>

        <!-- ========== App Menu ========== -->
        <div class="app-menu navbar-menu">
            <!-- LOGO -->
            <div class="navbar-brand-box">
                <a href="{{url('/')}}" class="logo logo-light">
                    <span class="logo-sm">
                        <img src="{{asset('assets/images/marsu-logo.png')}}" alt="" height="50">
                    </span>
                    <span class="logo-lg">
                        <img src="{{asset('assets/images/marsu-logo.png')}}" alt="" height="55">
                    </span>
                </a>
                <button type="button" class="btn btn-sm p-0 fs-20 header-item float-end btn-vertical-sm-hover" id="vertical-hover">
                    <i class="ri-record-circle-line"></i>
                </button>
            </div>

            <div id="scrollbar">
                <div class="container-fluid">
                    <div id="two-column-menu"></div>
                    <ul class="navbar-nav" id="navbar-nav">
                        <li class="menu-title"><span data-key="t-menu">DOCUMENT MANAGEMENT SYSTEM</span></li>
                        <li class="menu-title"><span data-key="t-menu">Menu</span></li>
                        
                        <!-- Dashboard (Hidden for Users) -->
                        <li class="nav-item {{ Route::current()->getName() == 'home' ? 'active' : '' }}">
                            <a class="nav-link menu-link" href="{{url('/home')}}">
                                <i class="ri-dashboard-2-line"></i> 
                                <span data-key="t-dashboards">Dashboard</span>
                            </a>
                        </li>

                        <!-- Search -->
                        {{-- @can('search')
                        <li class="nav-item {{ Route::current()->getName() == 'search' ? 'active' : '' }}" onclick="show()">
                            <a class="nav-link menu-link" href="{{url('/search')}}">
                                <i class="ri-search-line"></i>
                                <span data-key="t-search">Search</span>
                            </a>
                        </li>
                        @endcan --}}

                        <!-- Copy Requests -->
                        {{-- @can('copy request')
                        <li class="nav-item {{ Route::current()->getName() == 'requests' ? 'active' : '' }}">
                            <a class="nav-link menu-link" href="{{url('/request')}}">
                                <i class="ri-file-copy-line"></i>
                                <span data-key="t-copy-requests">Copy Requests</span>
                            </a>
                        </li>
                        @endcan --}}

                        <!-- Pre-Assessment (Admin, DCO, or User ID 286 only) -->
                        {{-- @if((auth()->user()->role == "Administrator") || (auth()->user()->role == "Document Control Officer") || (auth()->user()->id == "286"))
                            <li class="nav-item {{ Route::current()->getName() == 'pre_assessment' ? 'active' : '' }}">
                                <a class="nav-link menu-link" href="{{url('/pre_assessment')}}">
                                    <i class="ri-file-text-line"></i>
                                    <span data-key="t-pre-assessment">Pre-assessment</span>
                                </a>
                            </li>
                        @endif --}}

                        <!-- Change Requests -->
                        @can('change request')
                        <li class="nav-item {{ Route::current()->getName() == 'change-requests' ? 'active' : '' }}">
                            <a class="nav-link menu-link" href="{{url('/change-requests')}}">
                                <i class="ri-edit-line"></i>
                                <span data-key="t-change-requests">My Files</span>
                            </a>
                        </li>
                        @endcan

                        <!-- For Approval (For approvers only) -->
                        @can('for approval')
                        <li class="nav-item {{ Route::current()->getName() == 'for-approval' ? 'active' : '' }}">
                            <a class="nav-link menu-link" href="{{url('/for-approval')}}">
                                <i class="ri-checkbox-line"></i>
                                <span data-key="t-for-approval">For Approval</span>
                            </a>
                        </li>
                        @endcan
                        {{-- @endif --}}

                        <!-- Documents -->
                        @can('documents')
                        <li class="nav-item {{ Route::current()->getName() == 'documents' ? 'active' : '' }}">
                            <a class="nav-link menu-link" href="{{url('/documents')}}">
                                <i class="ri-folder-2-line"></i>
                                <span data-key="t-documents">My Documents</span>
                            </a>
                        </li>
                        @endcan

                        {{-- <!-- Acknowledgement -->
                        <li class="nav-item {{ Route::current()->getName() == 'acknowledgement' ? 'active' : '' }}">
                            <a class="nav-link menu-link" href="{{url('/acknowledgement')}}">
                                <i class="ri-user-star-line"></i>
                                <span data-key="t-acknowledgement">Acknowledgement</span>
                            </a>
                        </li> --}}

                        <!-- Permits & Licenses (Specific roles and accountable persons) -->
                        @can('permits and license')
                        <li class="nav-item {{ Route::current()->getName() == 'permits' ? 'active' : '' }}">
                            <a class="nav-link menu-link" href="{{url('/permits')}}">
                                <i class="ri-file-shield-line"></i>
                                <span data-key="t-permits">Permits & Licenses</span>
                            </a>
                        </li>
                        @endcan

                        <!-- Documents IA (Audit role only) -->
                        {{-- @if(auth()->user()->audit_role != null)
                            <li class="nav-item {{ Route::current()->getName() == 'audit' ? 'active' : '' }}">
                                <a class="nav-link menu-link" href="{{url('/audits')}}">
                                    <i class="ri-file-list-3-line"></i>
                                    <span data-key="t-audit">Documents IA</span>
                                </a>
                            </li>
                        @endif --}}

                        {{-- <li class="nav-item {{ Route::current()->getName() == 'remove-approvers' ? 'active' : '' }}">
                            <a class="nav-link menu-link" href="{{url('/remove-approvers')}}">
                                <i class="ri-user-unfollow-line"></i>
                                <span data-key="t-approvers">Approvers</span>
                            </a>
                        </li> --}}

                        <!-- Settings Submenu -->
                        @if(auth()->user()->can('users') || auth()->user()->can('rmo') || auth()->user()->can('roles and permission') || auth()->user()->can('departments') || auth()->user()->can('teams') || auth()->user()->can('department'))
                        <li class="nav-item {{ Route::current()->getName() == 'settings' ? 'active' : '' }}">
                            <a class="nav-link menu-link {{ Route::current()->getName() == 'settings' ? '' : 'collapsed' }}" 
                            href="#sidebarSettings" data-bs-toggle="collapse" role="button" 
                            aria-expanded="{{ Route::current()->getName() == 'settings' ? 'true' : 'false' }}" 
                            aria-controls="sidebarSettings">
                                <i class="ri-settings-3-line"></i> 
                                <span data-key="t-settings">Settings</span>
                            </a>
                            <div class="menu-dropdown collapse {{ Route::current()->getName() == 'settings' ? 'show' : '' }}" id="sidebarSettings">
                                <ul class="nav nav-sm flex-column">
                                    {{-- <li class="nav-item">
                                        <a href="{{ url('companies') }}" class="nav-link {{ Request::is('companies') || Request::is('new-company') || Request::is('*company*') ? 'active' : '' }}" data-key="t-companies">Companies</a>
                                    </li> --}}
                                    @can('department')
                                    <li class="nav-item">
                                        <a href="{{ url('departments') }}" class="nav-link {{ Request::is('departments') || Request::is('new-department') || Request::is('*department*') ? 'active' : '' }}" data-key="t-departments">Departments</a>
                                    </li>
                                    @endcan
                                    
                                    <li class="nav-item">
                                        <a href="{{ url('teams') }}" class="nav-link {{ Request::is('teams') || Request::is('new-team') || Request::is('*team*') ? 'active' : '' }}" data-key="t-teams">Offices</a>
                                    </li>
                                        
                                    @can('users')
                                        <li class="nav-item">
                                            <a href="{{ url('users') }}" class="nav-link {{ Request::is('users') || Request::is('new-user') || Request::is('*user*') && !Request::is('remove-approvers') ? 'active' : '' }}" data-key="t-users">Users</a>
                                        </li>
                                    @endcan
                                    {{-- @can('rmo')
                                        <li class="nav-item">
                                            <a href="{{ url('dco') }}" class="nav-link {{ Request::is('dco') || Request::is('new-dco') || Request::is('*dco*') && !Request::is('dco-reports') ? 'active' : '' }}" data-key="t-dco">RMO</a>
                                        </li>
                                    @endcan --}}
                                    @can('roles and permission')
                                        <li class="nav-item">
                                            <a href="{{ url('roles') }}" class="nav-link" data-key="t-dco">Roles & Permissions</a>
                                        </li>
                                    @endcan
                                    {{-- @can("office")
                                    <li class="nav-item">
                                        <a href="{{ url('offices') }}" class="nav-link" data-key="t-dco">Offices</a>
                                    </li>
                                    @endcan --}}
                                </ul>
                            </div>
                        </li>
                        @endif

                        <!-- Reports Submenu -->
                        {{-- <li class="nav-item {{ Route::current()->getName() == 'reports' || Route::current()->getName() == 'logs' || Route::current()->getName() == 'dicr-reports' || Route::current()->getName() == 'copy-reports' || Route::current()->getName() == 'dco-reports' ? 'active' : '' }}">
                            <a class="nav-link menu-link {{ Route::current()->getName() == 'reports' || Route::current()->getName() == 'logs' || Route::current()->getName() == 'dicr-reports' || Route::current()->getName() == 'copy-reports' || Route::current()->getName() == 'dco-reports' ? '' : 'collapsed' }}" 
                            href="#sidebarReports" data-bs-toggle="collapse" role="button" 
                            aria-expanded="{{ Route::current()->getName() == 'reports' || Route::current()->getName() == 'logs' || Route::current()->getName() == 'dicr-reports' || Route::current()->getName() == 'copy-reports' || Route::current()->getName() == 'dco-reports' ? 'true' : 'false' }}" 
                            aria-controls="sidebarReports">
                                <i class="ri-file-chart-line"></i> 
                                <span data-key="t-reports">Reports</span>
                            </a>
                            <div class="menu-dropdown collapse {{ Route::current()->getName() == 'reports' || Route::current()->getName() == 'logs' || Route::current()->getName() == 'dicr-reports' || Route::current()->getName() == 'copy-reports' || Route::current()->getName() == 'dco-reports' ? 'show' : '' }}" id="sidebarReports">
                                <ul class="nav nav-sm flex-column">
                                    @if((auth()->user()->role == 'Administrator') || (auth()->user()->role == 'Management Representative'))
                                        <li class="nav-item">
                                            <a href="{{url('/logs')}}" class="nav-link {{ Route::current()->getName() == 'logs' ? 'active' : '' }}" data-key="t-logs">Logs</a>
                                        </li>
                                    @endif
                                    <li class="nav-item">
                                        <a href="{{url('/dicr-reports')}}" class="nav-link {{ Route::current()->getName() == 'dicr-reports' ? 'active' : '' }}" data-key="t-change-reports">Change Requests</a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{url('/copy-reports')}}" class="nav-link {{ Route::current()->getName() == 'copy-reports' ? 'active' : '' }}" data-key="t-copy-reports">Copy Requests</a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{url('/dco-reports')}}" class="nav-link {{ Route::current()->getName() == 'dco-reports' ? 'active' : '' }}" data-key="t-dco-reports">DCO</a>
                                    </li>
                                </ul>
                            </div>
                        </li> --}}

                        <!-- Memorandum (All users) -->
                        @can('approver stamp')
                        <li class="nav-item @if(Request::is('approver-stamp')) active @endif">
                            <a class="nav-link menu-link" href="{{url('approver-stamp')}}">
                                <i class="mdi mdi-stamper"></i>
                                <span data-key="t-memorandum">Approver Stamp</span>
                            </a>
                        </li>
                        @endcan

                        {{-- SSO Default Access --}}
                        @if(auth()->user()->google_id != null)
                        <li class="nav-item {{ Route::current()->getName() == 'change-requests' ? 'active' : '' }}">
                            <a class="nav-link menu-link" href="{{url('/change-requests')}}">
                                <i class="ri-edit-line"></i>
                                <span data-key="t-change-requests">Change Requests</span>
                            </a>
                        </li>
                        <li class="nav-item {{ Route::current()->getName() == 'for-approval' ? 'active' : '' }}">
                            <a class="nav-link menu-link" href="{{url('/for-approval')}}">
                                <i class="ri-checkbox-line"></i>
                                <span data-key="t-for-approval">For Approval</span>
                            </a>
                        </li>
                        <li class="nav-item {{ Route::current()->getName() == 'documents' ? 'active' : '' }}">
                            <a class="nav-link menu-link" href="{{url('/documents')}}">
                                <i class="ri-folder-2-line"></i>
                                <span data-key="t-documents">My Documents</span>
                            </a>
                        </li>
                        <li class="nav-item {{ Route::current()->getName() == 'permits' ? 'active' : '' }}">
                            <a class="nav-link menu-link" href="{{url('/permits')}}">
                                <i class="ri-file-shield-line"></i>
                                <span data-key="t-permits">Permits & Licenses</span>
                            </a>
                        </li>
                        <li class="nav-item @if(Request::is('approver-stamp')) active @endif">
                            <a class="nav-link menu-link" href="{{url('approver-stamp')}}">
                                <i class="mdi mdi-stamper"></i>
                                <span data-key="t-memorandum">Approver Stamp</span>
                            </a>
                        </li>
                        @endif
                    </ul>
                </div>
                <!-- Sidebar -->
            </div>

            <div class="sidebar-background"></div>
        </div>
        <!-- Left Sidebar End -->
        
        <!-- Vertical Overlay-->
        <div class="vertical-overlay"></div>
        
        <div class="main-content">
            <div class="page-content">
                <div class="container-fluid">
                    <div class="row"></div>
                    @yield('content')
                </div>
            </div>
        
            <footer class="footer">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-sm-6">
                            {{date('Y')}} © DMS
                        </div>
                        <div class="col-sm-6">
                            <div class="text-sm-end d-none d-sm-block">
                                Design & Develop by <span>.<</span>
                            </div>
                        </div>
                    </div>
                </div>
            </footer>
        </div>
    </div>

    <!--start back-to-top-->
    <button onclick="topFunction()" class="btn btn-danger btn-icon" id="back-to-top">
        <i class="ri-arrow-up-line"></i>
    </button>
    <!--end back-to-top-->

    <!-- Theme Settings -->
    {{-- @include('layouts.change_password') --}}
    @include('sweetalert::alert')
    
    <!-- JAVASCRIPT -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script src="{{asset('/assets/libs/bootstrap/js/bootstrap.bundle.min.js')}}"></script>

    <script src="{{asset('/assets/libs/simplebar/simplebar.min.js')}}"></script>
    <script src="{{asset('/assets/libs/node-waves/waves.min.js')}}"></script>
    <script src="{{asset('/assets/libs/feather-icons/feather.min.js')}}"></script>
    <script src="{{asset('/assets/js/pages/plugins/lord-icon-2.1.0.js')}}"></script>
    <script src="{{asset('/assets/js/plugins.js')}}"></script>

    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.bootstrap5.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.print.min.js"></script>


    @yield('js')
    
    <!-- App js -->
    <script src="{{asset('/assets/js/app.js')}}"></script>

    <script>
        function show() {
            document.getElementById("preloaderMarsu").style.display = "flex";
        }
        
        function logout() {
            event.preventDefault();
            document.getElementById('logout-form').submit();
        }
    </script>
    
    <script>
        window.addEventListener('load', function() {
            document.getElementById('preloaderMarsu').style.display = 'none';
        });
    </script>
    
</body>
</html>