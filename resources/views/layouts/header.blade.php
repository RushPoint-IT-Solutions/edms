<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    @laravelPWA
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>
    <link rel="shortcut icon" href="{{ URL::asset('images/icon.png')}}">

    <!-- Scripts -->
    {{-- <script src="{{ asset('js/app.js') }}" defer></script> --}}
    <link href="{{ asset('login_css/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('login_css/font-awesome/css/font-awesome.css') }}" rel="stylesheet">

    <link href="{{ asset('login_css/css/animate.css') }}" rel="stylesheet">
    <link href="{{ asset('login_css/css/style.css') }}" rel="stylesheet">
    @yield('css')
    <style>
      
        .bold-text {
            font-weight: bold;
        }
        .shownext { display: none; }
        li:hover + .shownext { display: block; }
        .loader {
            position: fixed;
            left: 0px;
            top: 0px;
            width: 100%;
            height: 100%;
            z-index: 9999;
            background: url("{{ asset('login_css/img/loader.gif') }}") 50% 50% no-repeat white;
            opacity: .8;
            background-size: 120px 120px;
        }

        .dataTables_filter {
        float: right;
        text-align: right;
        }
        .dataTables_info {
        float: left;
        text-align: left;
        }
        textarea {
    resize: vertical;
    }
    @media (min-width: 768px) {
  .modal-xl {
    width: 90%;
   max-width:1200px;
  }
}
    </style>
    <!-- Fonts -->
    <!-- Styles -->
    {{-- <link href="{{ asset('css/app.css') }}" rel="stylesheet"> --}}
</head>
<body class='pace-done mini-navbar'>
    <div id="loader" style="display:none;" class="loader">
    </div>
    <div id="wrapper">
        <nav class="navbar-default navbar-static-side" role="navigation" style="margin-bottom: 0">
            <div class="sidebar-collapse">
                <ul class="nav metismenu tooltip-demo" id="side-menu">
                    <li class="nav-header">
                        <div class="dropdown profile-element"> <span>
                                <img alt="image" class="img-circle" style='width:50px;' src="{{asset('images/no_image.png')}}" />
                            </span>
                            <a data-toggle="dropdown" class="dropdown-toggle" href="#">
                                <span class="clear"> <span class="block m-t-xs"> <strong class="font-bold">{{auth()->user()->name}}</strong>
                                 </span> <span class="text-muted text-xs block">{{auth()->user()->role}} <b class="caret"></b></span> </span> </a>
                            <ul class="dropdown-menu animated fadeInRight m-t-xs">
                                <li><a href="#">Change Password</a></li>
                            </ul>
                        </div>
                        <div class="logo-element">
                            
                            <img alt="image" class="img-circle" style='width:50px;' src="{{asset('images/no_image.png')}}" />
                        </div>
                        
                    </li>
                    <!-- //sidebar -->
                    @if((auth()->user()->role != "User"))
                        <li class="{{ Route::current()->getName() == 'home' ? 'active' : '' }} shownext" data-toggle="tooltip" data-placement="right" title="Dashboard">
                            <a href="{{url('/home')}}"><i class="fa fa-th-large"></i> <span
                                    class="nav-label " >Dashboard </span></a>
                        </li>
                    @endif
                    <li class="{{ Route::current()->getName() == 'search' ? 'active' : '' }}" data-toggle="tooltip" data-placement="right" title="Search">
                        <a href="{{url('/search')}}"><i class="fa fa-search"></i> <span
                                class="nav-label">Search </span></a>
                    </li>
                    <li class="{{ Route::current()->getName() == 'requests' ? 'active' : '' }}" data-toggle="tooltip" data-placement="right" title="Copy Requests">
                        <a href="{{url('/request')}}"><i class="fa fa-paper-plane"></i> <span
                                class="nav-label">Copy Requests </span></a>
                    </li>
                    @if((auth()->user()->role == "Administrator") || (auth()->user()->role == "Document Control Officer") || (auth()->user()->id == "286"))
                    <li class="{{ Route::current()->getName() == 'pre_assessment' ? 'active' : '' }}" data-toggle="tooltip" data-placement="right" title="Pre-Assessment">
                        <a href="{{url('/pre_assessment')}}"><i class="fa fa-file"></i> <span
                                class="nav-label">Pre-assessment </span></a>
                    </li>
                    @endif
                    {{-- @if((auth()->user()->role != "User")) --}}
                    <li class="{{ Route::current()->getName() == 'change-requests' ? 'active' : '' }}" data-toggle="tooltip" data-placement="right" title="Change Requests">
                        <a href="{{url('/change-requests')}}"><i class="fa fa-edit"></i> <span
                                class="nav-label">Change Requests </span></a>
                    </li>
                    {{-- @endif --}}
                    @if((count(auth()->user()->copy_approvers) != 0) || (count(auth()->user()->department_approvers) != 0) || (count(auth()->user()->change_approvers) != 0) || auth()->user()->role == 'Administrator')
                    <li class="{{ Route::current()->getName() == 'for-approval' ? 'active' : '' }}" data-toggle="tooltip" data-placement="right" title="For Approval">
                        <a href="{{url('/for-approval')}}"><i class="fa fa-check-square-o"></i> <span
                                class="nav-label">For Approval </span></a>
                    </li>
                    @endif
                    {{-- @if((auth()->user()->role != "User")) --}}
                    <li class="{{ Route::current()->getName() == 'documents' ? 'active' : '' }}" data-toggle="tooltip" data-placement="right" title="Documents">
                        <a href="{{url('/documents')}}"><i class="fa fa-files-o"></i> <span
                                class="nav-label">Documents </span></a>
                    </li>
                    <li class="{{ Route::current()->getName() == 'acknowledgement' ? 'active' : '' }}" data-toggle="tooltip" data-placement="right" title="Acknowledgement">
                        <a href="{{url('/acknowledgement')}}"><i class="fa fa-vcard"></i> <span
                                class="nav-label">Acknowledgement </span></a>
                    </li>
                    {{-- @endif --}}
                    @if((auth()->user()->role == 'Administrator') || (auth()->user()->role == 'Document Control Officer') || (auth()->user()->role == 'Business Process Manager') || (auth()->user()->role == 'Management Representative') || (auth()->user()->role == 'Department Head') ||  (count(auth()->user()->accountable_persons) !=0 ))
                    <li class="{{ Route::current()->getName() == 'permits' ? 'active' : '' }}" data-toggle="tooltip" data-placement="right" title="Permits & Licenses">
                        <a href="{{url('/permits')}}"><i class="fa fa-file-archive-o"></i> <span
                                class="nav-label">Permits & Licenses </span></a>
                    </li>
                    @endif
                    @if(auth()->user()->audit_role != null)
                    <li class="{{ Route::current()->getName() == 'audit' ? 'active' : '' }}" data-toggle="tooltip" data-placement="right" title="Audit">
                        <a href="{{url('/audits')}}"><i class="fa fa-files-o"></i> <span
                                class="nav-label">Documents IA</span></a>
                    </li>
                    @endif
                    @if((auth()->user()->role == 'Administrator') || (auth()->user()->role == 'Business Process Manager') || (auth()->user()->role == 'Management Representative'))
                    <li class="{{ Route::current()->getName() == 'remove-approvers' ? 'active' : '' }}" data-toggle="tooltip" data-placement="right" title="Approvers">
                        <a href="{{url('/remove-approvers')}}"><i class="fa fa-search-minus"></i> <span
                                class="nav-label">Approvers</span></a>
                    </li>
                    <li class="{{ Route::current()->getName() == 'settings' ? 'active' : '' }}" data-toggle="tooltip" data-placement="right" title="Settings">
                        <a href="#"><i class="fa fa-gavel"></i> <span class="nav-label">Settings</span><span
                                class="fa arrow"></span></a>
                        <ul class="nav nav-second-level collapse">
                            <li ><a href="{{url('/companies')}}"></i>Companies</a></li>
                            <li><a href="{{url('/departments')}}"></i>Departments</a></li>
                            <li><a href="{{url('/users')}}"></i>Users</a></li>
                            <li><a href="{{url('/dco')}}"></i>DCO</a></li>
                        </ul>
                    </li>
                    
                    <li class="{{ Route::current()->getName() == 'reports' ? 'active' : '' }}" data-toggle="tooltip" data-placement="right" title="Reports">
                        <a href="#"><i class="fa fa-list-ul"></i> <span class="nav-label">Reports</span><span
                                class="fa arrow"></span></a>
                        <ul class="nav nav-second-level collapse">
                            @if((auth()->user()->role == 'Administrator') || (auth()->user()->role == 'Management Representative'))
                            <li><a href="{{url('/logs')}}"></i>Logs</a></li>
                            @endif
                            <li><a href="{{url('/dicr-reports')}}"></i>Change Requests</a></li>
                            <li><a href="{{url('/copy-reports')}}"></i>Copy Requests</a></li>
                            <li><a href="{{url('/dco-reports')}}"></i>DCO</a></li>
                        </ul>
                    </li>
                    @endif
                    <li class="@if(Request::is('memorandum')) active @endif" data-toggle="tooltip" data-placement="right" title="Memorandum">
                        <a href="{{url('memorandum')}}">
                            <i class="fa fa-sticky-note"></i>
                            <span class="nav-label">Memorandum</span>
                        </a>
                    </li>
                </ul>
            </div>
        </nav>

        <div id="page-wrapper" class="gray-bg">
            <div class="row border-bottom">
                <nav class="navbar navbar-static-top white-bg" role="navigation" style="margin-bottom: 0">
                    <div class="navbar-header">
                        <a class="navbar-minimalize minimalize-styl-2 btn btn-primary " href="#"><i
                                class="fa fa-bars"></i>
                             </a>
                            
                    </div>
                    <ul class="nav navbar-top-links navbar-right">
                        <li>
                            <span class="m-r-sm text-muted welcome-message" title='For Approval'>Welcome to {{ config('app.name', 'Laravel') }}</span>
                        </li>
                        <li>
                            <a class=" count-info " href="{{url('/for-approval')}}" title='For Approval'>
                                <i class="fa fa-bell"></i>  <span class="label label-warning">{{copy_approver_count()}}</span>
                            </a>
                        </li>
                        @if((auth()->user()->role == "Administrator") || (auth()->user()->role == "Document Control Officer") || (auth()->user()->id == "286"))
                        <li>
                            <a class=" count-info " href="{{url('/pre_assessment')}}" title='For Approval'>
                                <i class="fa fa-bell-o"></i>  <span class="label label-warning">{{pre_assessment_count()}}</span>
                            </a>
                        </li>
                        @endif
                        <li>
                            <a href="{{ route('logout') }}" onclick="logout(); show();">
                                <i class="fa fa-sign-out"></i> Log out
                            </a>
                        </li>
                    </ul>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        {{ csrf_field() }}
                    </form>
                </nav>
            </div>
            @yield('content')
            <div class="footer">
                <div class='text-right'>
                    WGROUP DEVELOPER &copy; {{date('Y')}}
                </div>
            </div>
        </div>
    </div>
    @include('sweetalert::alert')
    <script src="{{ asset('login_css/js/jquery-3.1.1.min.js')}}"></script>
    <script src="{{ asset('login_css/js/bootstrap.min.js')}}"></script>
    <script src="{{ asset('login_css/js/plugins/metisMenu/jquery.metisMenu.js')}}"></script>
    <script src="{{ asset('login_css/js/plugins/slimscroll/jquery.slimscroll.min.js')}}"></script>

    <script src="{{ asset('login_css/js/inspinia.js')}}"></script>
    <script src="{{ asset('login_css/js/plugins/pace/pace.min.js')}}"></script>
    @yield('js')
    <script>
        function show() {
            document.getElementById("loader").style.display = "block";
        }

        function logout() {
            event.preventDefault();
            document.getElementById('logout-form').submit();
        }

    document.addEventListener('DOMContentLoaded', function() {
        const select = document.getElementById('reason-for-new-request');
        const options = select.querySelectorAll('option');
    
        options.forEach(option => {
            const text = option.textContent;
            const parts = text.split('(');
            if (parts.length > 1) {
                option.innerHTML = `<span class="bold-text">${parts[0]}</span>(${parts[1]}`;
            } else {
                option.innerHTML = `<span class="bold-text">${text}</span>`;
            }
        });
    });
    </script>

</body>
</html>
