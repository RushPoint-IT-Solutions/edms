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

    </style>
    @yield('css')
    

</head>
<body>
    <div id="loader" class="loader"></div>
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
                        
                         <form class="app-search ">
                            <div class="position-relative">
                                <input type="text" class="form-control searchbar" placeholder="Search..." autocomplete="off">
                                <span class="mdi mdi-magnify search-widget-icon"></span>
                            </div>
                        </form>
                    </div>

                    <div class="d-flex align-items-center">
                        
                        <div class="dropdown topbar-head-dropdown ms-1 header-item" id="notificationDropdown">
                            <button type="button" class="btn btn-icon btn-topbar material-shadow-none btn-ghost-secondary rounded-circle" id="page-header-notifications-dropdown" data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-haspopup="true" aria-expanded="false">
                                <i class='bx bx-bell fs-22'></i>
                                <span class="position-absolute topbar-badge fs-10 translate-middle badge rounded-pill bg-danger">3<span class="visually-hidden">unread messages</span></span>
                            </button>
                            <div class="dropdown-menu dropdown-menu-lg dropdown-menu-end p-0" aria-labelledby="page-header-notifications-dropdown">
                                <div class="dropdown-head bg-pattern rounded-top" style="background-color: #800000;">
                                    <div class="p-3">
                                        <div class="row align-items-center">
                                            <div class="col">
                                                <h6 class="m-0 fs-16 fw-semibold text-white"> Notifications </h6>
                                            </div>
                                            <div class="col-auto dropdown-tabs">
                                                <span class="badge bg-light text-body fs-13"> 3 New</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="tab-content position-relative" id="notificationItemsTabContent">
                                    <div class="tab-pane fade show active py-2 ps-2" id="all-noti-tab" role="tabpanel">
                                        <div data-simplebar style="max-height: 300px;" class="pe-2">
                                            <div class="text-reset notification-item d-block dropdown-item position-relative">
                                                <div class="d-flex">
                                                    <div class="avatar-xs me-3 flex-shrink-0">
                                                        <span class="avatar-title bg-info-subtle text-info rounded-circle fs-16">
                                                            <i class="bx bx-badge-check"></i>
                                                        </span>
                                                    </div>
                                                    <div class="flex-grow-1">
                                                        <a href="#!" class="stretched-link">
                                                            <h6 class="mt-0 mb-2 lh-base">Your request has been approved</h6>
                                                        </a>
                                                        <p class="mb-0 fs-11 fw-medium text-uppercase text-muted">
                                                            <span><i class="mdi mdi-clock-outline"></i> 2 min ago</span>
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="text-reset notification-item d-block dropdown-item position-relative">
                                                <div class="d-flex">
                                                    <div class="avatar-xs me-3 flex-shrink-0">
                                                        <span class="avatar-title bg-warning-subtle text-warning rounded-circle fs-16">
                                                            <i class="bx bx-message-square-dots"></i>
                                                        </span>
                                                    </div>
                                                    <div class="flex-grow-1">
                                                        <a href="#!" class="stretched-link">
                                                            <h6 class="mt-0 mb-2 lh-base">New document uploaded</h6>
                                                        </a>
                                                        <p class="mb-0 fs-11 fw-medium text-uppercase text-muted">
                                                            <span><i class="mdi mdi-clock-outline"></i> 1 hour ago</span>
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="text-reset notification-item d-block dropdown-item position-relative">
                                                <div class="d-flex">
                                                    <div class="avatar-xs me-3 flex-shrink-0">
                                                        <span class="avatar-title bg-danger-subtle text-danger rounded-circle fs-16">
                                                            <i class="bx bx-error-circle"></i>
                                                        </span>
                                                    </div>
                                                    <div class="flex-grow-1">
                                                        <a href="#!" class="stretched-link">
                                                            <h6 class="mt-0 mb-2 lh-base">Document expiring soon</h6>
                                                        </a>
                                                        <p class="mb-0 fs-11 fw-medium text-uppercase text-muted">
                                                            <span><i class="mdi mdi-clock-outline"></i> 3 hours ago</span>
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="dropdown topbar-head-dropdown ms-1 header-item" id="messagesDropdown">
                            <button type="button" class="btn btn-icon btn-topbar material-shadow-none btn-ghost-secondary rounded-circle" id="page-header-messages-dropdown" data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-haspopup="true" aria-expanded="false">
                                <i class='bx bx-message-square-dots fs-22'></i>
                                <span class="position-absolute topbar-badge fs-10 translate-middle badge rounded-pill bg-success">5<span class="visually-hidden">unread messages</span></span>
                            </button>
                            <div class="dropdown-menu dropdown-menu-lg dropdown-menu-end p-0" aria-labelledby="page-header-messages-dropdown">
                                <div class="dropdown-head bg-pattern rounded-top" style="background-color: #800000;">
                                    <div class="p-3">
                                        <div class="row align-items-center">
                                            <div class="col">
                                                <h6 class="m-0 fs-16 fw-semibold text-white"> Messages </h6>
                                            </div>
                                            <div class="col-auto dropdown-tabs">
                                                <span class="badge bg-light text-body fs-13"> 5 New</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="tab-content position-relative" id="messageItemsTabContent">
                                    <div class="tab-pane fade show active py-2 ps-2" id="all-messages-tab" role="tabpanel">
                                        <div data-simplebar style="max-height: 300px;" class="pe-2">
                                            <div class="text-reset notification-item d-block dropdown-item">
                                                <div class="d-flex">
                                                    <img src="{{asset('assets/images/marsu-logo.png')}}" class="me-3 rounded-circle avatar-xs flex-shrink-0" alt="user-pic">
                                                    <div class="flex-grow-1">
                                                        <a href="#!" class="stretched-link">
                                                            <h6 class="mt-0 mb-1 fs-13 fw-semibold">John Doe</h6>
                                                        </a>
                                                        <div class="fs-13 text-muted">
                                                            <p class="mb-1">Your document has been reviewed...</p>
                                                        </div>
                                                        <p class="mb-0 fs-11 fw-medium text-uppercase text-muted">
                                                            <span><i class="mdi mdi-clock-outline"></i> 30 min ago</span>
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="text-reset notification-item d-block dropdown-item">
                                                <div class="d-flex">
                                                    <img src="{{asset('assets/images/marsu-logo.png')}}" class="me-3 rounded-circle avatar-xs flex-shrink-0" alt="user-pic">
                                                    <div class="flex-grow-1">
                                                        <a href="#!" class="stretched-link">
                                                            <h6 class="mt-0 mb-1 fs-13 fw-semibold">Jane Smith</h6>
                                                        </a>
                                                        <div class="fs-13 text-muted">
                                                            <p class="mb-1">Please check the new requirements...</p>
                                                        </div>
                                                        <p class="mb-0 fs-11 fw-medium text-uppercase text-muted">
                                                            <span><i class="mdi mdi-clock-outline"></i> 1 hour ago</span>
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="dropdown ms-sm-3 header-item topbar-user">
                            <button type="button" class="btn material-shadow-none" id="page-header-user-dropdown" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span class="d-flex align-items-center">
                                    <img class="rounded-circle header-profile-user" src="{{asset(auth()->user()->avatar)}}" onerror="this.src='{{url('assets/images/marsu-logo.png')}}';" alt="Header Avatar">
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
                        
                        <!-- Dashboard (Hidden for Users) -->
                        @if((auth()->user()->role != "User"))
                            <li class="nav-item {{ Route::current()->getName() == 'home' ? 'active' : '' }}">
                                <a class="nav-link menu-link" href="{{url('/home')}}">
                                    <i class="ri-dashboard-2-line"></i> 
                                    <span data-key="t-dashboards">Dashboard</span>
                                </a>
                            </li>
                        @endif

                        <!-- Search -->
                        <li class="nav-item {{ Route::current()->getName() == 'search' ? 'active' : '' }}">
                            <a class="nav-link menu-link" href="{{url('/search')}}">
                                <i class="ri-search-line"></i>
                                <span data-key="t-search">Search</span>
                            </a>
                        </li>

                        <!-- Copy Requests -->
                        <li class="nav-item {{ Route::current()->getName() == 'requests' ? 'active' : '' }}">
                            <a class="nav-link menu-link" href="{{url('/request')}}">
                                <i class="ri-file-copy-line"></i>
                                <span data-key="t-copy-requests">Copy Requests</span>
                            </a>
                        </li>

                        <!-- Pre-Assessment (Admin, DCO, or User ID 286 only) -->
                        @if((auth()->user()->role == "Administrator") || (auth()->user()->role == "Document Control Officer") || (auth()->user()->id == "286"))
                            <li class="nav-item {{ Route::current()->getName() == 'pre_assessment' ? 'active' : '' }}">
                                <a class="nav-link menu-link" href="{{url('/pre_assessment')}}">
                                    <i class="ri-file-text-line"></i>
                                    <span data-key="t-pre-assessment">Pre-assessment</span>
                                </a>
                            </li>
                        @endif

                        <!-- Change Requests -->
                        <li class="nav-item {{ Route::current()->getName() == 'change-requests' ? 'active' : '' }}">
                            <a class="nav-link menu-link" href="{{url('/change-requests')}}">
                                <i class="ri-edit-line"></i>
                                <span data-key="t-change-requests">Change Requests</span>
                            </a>
                        </li>

                        <!-- For Approval (For approvers only) -->
                        @if((count(auth()->user()->copy_approvers) != 0) || (count(auth()->user()->department_approvers) != 0) || (count(auth()->user()->change_approvers) != 0) || auth()->user()->role == 'Administrator')
                            <li class="nav-item {{ Route::current()->getName() == 'for-approval' ? 'active' : '' }}">
                                <a class="nav-link menu-link" href="{{url('/for-approval')}}">
                                    <i class="ri-checkbox-line"></i>
                                    <span data-key="t-for-approval">For Approval</span>
                                </a>
                            </li>
                        @endif

                        <!-- Documents -->
                        <li class="nav-item {{ Route::current()->getName() == 'documents' ? 'active' : '' }}">
                            <a class="nav-link menu-link" href="{{url('/documents')}}">
                                <i class="ri-folder-2-line"></i>
                                <span data-key="t-documents">Documents</span>
                            </a>
                        </li>

                        <!-- Acknowledgement -->
                        <li class="nav-item {{ Route::current()->getName() == 'acknowledgement' ? 'active' : '' }}">
                            <a class="nav-link menu-link" href="{{url('/acknowledgement')}}">
                                <i class="ri-user-star-line"></i>
                                <span data-key="t-acknowledgement">Acknowledgement</span>
                            </a>
                        </li>

                        <!-- Permits & Licenses (Specific roles and accountable persons) -->
                        @if((auth()->user()->role == 'Administrator') || (auth()->user()->role == 'Document Control Officer') || (auth()->user()->role == 'Business Process Manager') || (auth()->user()->role == 'Management Representative') || (auth()->user()->role == 'Department Head') || (count(auth()->user()->accountable_persons) != 0))
                            <li class="nav-item {{ Route::current()->getName() == 'permits' ? 'active' : '' }}">
                                <a class="nav-link menu-link" href="{{url('/permits')}}">
                                    <i class="ri-file-shield-line"></i>
                                    <span data-key="t-permits">Permits & Licenses</span>
                                </a>
                            </li>
                        @endif

                        <!-- Documents IA (Audit role only) -->
                        @if(auth()->user()->audit_role != null)
                            <li class="nav-item {{ Route::current()->getName() == 'audit' ? 'active' : '' }}">
                                <a class="nav-link menu-link" href="{{url('/audits')}}">
                                    <i class="ri-file-list-3-line"></i>
                                    <span data-key="t-audit">Documents IA</span>
                                </a>
                            </li>
                        @endif

                        <!-- Approvers (Admin, BPM, or Management Representative) -->
                        @if((auth()->user()->role == 'Administrator') || (auth()->user()->role == 'Business Process Manager') || (auth()->user()->role == 'Management Representative'))
                            <li class="nav-item {{ Route::current()->getName() == 'remove-approvers' ? 'active' : '' }}">
                                <a class="nav-link menu-link" href="{{url('/remove-approvers')}}">
                                    <i class="ri-user-unfollow-line"></i>
                                    <span data-key="t-approvers">Approvers</span>
                                </a>
                            </li>

                            <!-- Settings Submenu -->
                            <li class="nav-item">
                                <a class="nav-link menu-link collapsed {{ Route::current()->getName() == 'settings' ? 'active' : '' }}" href="#sidebarSettings" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarSettings">
                                    <i class="ri-settings-3-line"></i> 
                                    <span data-key="t-settings">Settings</span>
                                </a>
                                <div class="menu-dropdown collapse" id="sidebarSettings">
                                    <ul class="nav nav-sm flex-column">
                                        <li class="nav-item">
                                            <a href="{{url('/companies')}}" class="nav-link" data-key="t-companies">Companies</a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="{{url('/departments')}}" class="nav-link" data-key="t-departments">Departments</a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="{{url('/users')}}" class="nav-link" data-key="t-users">Users</a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="{{url('/dco')}}" class="nav-link" data-key="t-dco">DCO</a>
                                        </li>
                                    </ul>
                                </div>
                            </li>
                            
                            <!-- Reports Submenu -->
                            <li class="nav-item">
                                <a class="nav-link menu-link collapsed {{ Route::current()->getName() == 'reports' ? 'active' : '' }}" href="#sidebarReports" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarReports">
                                    <i class="ri-file-chart-line"></i> 
                                    <span data-key="t-reports">Reports</span>
                                </a>
                                <div class="menu-dropdown collapse" id="sidebarReports">
                                    <ul class="nav nav-sm flex-column">
                                        @if((auth()->user()->role == 'Administrator') || (auth()->user()->role == 'Management Representative'))
                                            <li class="nav-item">
                                                <a href="{{url('/logs')}}" class="nav-link" data-key="t-logs">Logs</a>
                                            </li>
                                        @endif
                                        <li class="nav-item">
                                            <a href="{{url('/dicr-reports')}}" class="nav-link" data-key="t-change-reports">Change Requests</a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="{{url('/copy-reports')}}" class="nav-link" data-key="t-copy-reports">Copy Requests</a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="{{url('/dco-reports')}}" class="nav-link" data-key="t-dco-reports">DCO</a>
                                        </li>
                                    </ul>
                                </div>
                            </li>
                        @endif

                        <!-- Memorandum (All users) -->
                        <li class="nav-item @if(Request::is('memorandum')) active @endif">
                            <a class="nav-link menu-link" href="{{url('memorandum')}}">
                                <i class="ri-sticky-note-line"></i>
                                <span data-key="t-memorandum">Memorandum</span>
                            </a>
                        </li>
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
                            {{date('Y')}} © LiMS
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

    <!--preloader-->
    <div id="preloader">
        <div id="status">
            <div class="spinner-border text-primary avatar-sm" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
        </div>
    </div>

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
    <script src="{{ asset('login_css/js/plugins/dataTables/datatables.min.js')}}"></script>
    <script src="{{ asset('login_css/js/jquery-3.1.1.min.js')}}"></script>
    <script src="{{ asset('login_css/js/plugins/metisMenu/jquery.metisMenu.js')}}"></script>
    <script src="{{ asset('login_css/js/plugins/slimscroll/jquery.slimscroll.min.js')}}"></script>

    <script src="{{ asset('login_css/js/inspinia.js')}}"></script>
    <script src="{{ asset('login_css/js/plugins/pace/pace.min.js')}}"></script>

    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.print.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>


    @yield('js')
    
    <!-- App js -->
    <script src="{{asset('/assets/js/app.js')}}"></script>

    <script>
        function show() {
            document.getElementById("loader").style.display = "block";
        }
        
        function logout() {
            event.preventDefault();
            document.getElementById('logout-form').submit();
        }
    </script>
    
    <script>
        window.addEventListener('load', function() {
            document.getElementById('loader').style.display = 'none';
        });
    </script>
    
</body>
</html>