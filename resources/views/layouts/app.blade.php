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
    <script src="{{ asset('js/app.js') }}" defer></script>
    <link rel="stylesheet" type="text/css" href="{{asset('login_design/vendor/bootstrap/css/bootstrap.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('login_design/fonts/font-awesome-4.7.0/css/font-awesome.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('login_design/fonts/Linearicons-Free-v1.0.0/icon-font.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('login_design/vendor/animate/animate.css')}}">	
    <link rel="stylesheet" type="text/css" href="{{asset('login_design/vendor/css-hamburgers/hamburgers.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('login_design/vendor/animsition/css/animsition.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('login_design/vendor/select2/select2.min.css')}}">	
    <link rel="stylesheet" type="text/css" href="{{asset('login_design/vendor/daterangepicker/daterangepicker.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('login_design/css/util.css')}}">
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.32/dist/sweetalert2.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        .shownext { display: none; }
        li:hover + .shownext { display: block; }
        .preload-marsu {
            width: 100%;
            max-width: 380px;
        }
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
            /* width: 150px;
            height: 150px; */
            opacity: 2;
            color: white;
            font-size: 1.5em;
            font-weight: bold;
            text-align: center;
            line-height: 150px;
            border-radius: 10px;
            animation: bounce 1s infinite alternate;
        }

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

    </style>
    <!-- Fonts -->
    <!-- Styles -->
    {{-- <link href="{{ asset('css/app.css') }}" rel="stylesheet"> --}}
</head>

    <!-- <div id="loader" style="display:none;" class="loader">
    </div> -->
    @yield('content')
    @include('sweetalert::alert')
	<script src="{{ asset('login_design/vendor/jquery/jquery-3.2.1.min.js')}}"></script>
    <script src="{{ asset('login_design/vendor/animsition/js/animsition.min.js')}}"></script>
    <script src="{{ asset('login_design/vendor/bootstrap/js/popper.js')}}"></script>
    <script src="{{ asset('login_design/vendor/bootstrap/js/bootstrap.min.js')}}"></script>
    <script src="{{ asset('login_design/vendor/select2/select2.min.js')}}"></script>
    <script src="{{ asset('login_design/vendor/daterangepicker/moment.min.js')}}"></script>
    <script src="{{ asset('login_design/vendor/daterangepicker/daterangepicker.js')}}"></script>
    <script src="{{ asset('login_design/vendor/countdowntime/countdowntime.js')}}"></script>
    <script src="{{ asset('login_design/js/main.js')}}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.32/dist/sweetalert2.all.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {

            const form = document.querySelector('.preload-marsu');
            const preloader = document.getElementById('preloaderMarsu');

            // SHOW LOADER
            if (form) {
                form.addEventListener('submit', function () {
                    if(preloader){
                        preloader.style.display = 'flex';
                    }
                });
            }

            // HIDE LOADER
            window.addEventListener('load', function () {
                if(preloader){
                    preloader.style.display = 'none';
                }
            });

            // SUCCESS ALERT
            @if(session('success'))
                Swal.fire({
                    icon: 'success',
                    title: 'Success!',
                    text: '{{ session('success') }}'
                });
            @endif

            // ERROR ALERT
            @if ($errors->any())
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    html: `{!! implode('<br>', $errors->all()) !!}`
                });
            @endif

        });
    </script>
    @yield('scripts')
</body>
</html>
