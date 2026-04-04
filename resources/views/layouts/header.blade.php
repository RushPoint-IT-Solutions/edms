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
    <link href="{{ asset('login_css/css/plugins/sweetalert/sweetalert.css') }}" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

    <style>
        :root {
            --bs-first: #c7522a;
            --bs-second: #1CA7A6;
        }

        .btn-first {
            color: #ffffff;
            background-color: var(--bs-first);
            border-color: var(--bs-first);
        }

        .btn-first:focus,
        .btn-first:active,
        .btn-first.show {
            color: #ffffff;
            background-color: #a84322;
            border-color: #a84322;
            box-shadow: none; /* optional: removes blue glow */
        }

        .btn-first:hover {
            color: #ffffff;
            background-color: #a84322;
            border-color: #a84322;
        }

        .btn-second {
            color: #ffffff;
            background-color: var(--bs-second);
            border-color: var(--bs-second);
        }

        .btn-second:hover{
            color: #ffffff;
            background-color: #178f8e;
            border-color: #178f8e;
        }

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

        .navbar-brand-box {
            background: #420906 !important;
            border-bottom: 1px solid rgba(255, 255, 255, 0.15) !important;
        }

        .app-menu .nav-item > .nav-link .nav-link-text,
        .twocolumn-menu-item .nav-link-text,
        .vertical-overlay ~ #layout-wrapper .navbar-menu .nav-item > .collapse-item {
            background: #420906 !important;
            color: #fff !important;
        }

        [data-sidebar-size="sm"] .menu-title,
        [data-sidebar-size="sm"] .menu-title span:not(.dms-full):not(.dms-short) {
            display: block !important;
            visibility: visible !important;
            opacity: 1 !important;
            font-size: 8px !important;
            letter-spacing: 0.5px;
            text-align: center;
            overflow: hidden;
            white-space: nowrap;
            padding: 8px 0 4px;
        }

        [data-sidebar-size="sm"] .menu-title span[data-key="t-menu"] {
            font-size: 7px !important;
        }

        [data-sidebar-size="sm"] li.menu-title {
            display: block !important;
            min-height: auto !important;
            padding: 0 !important;
        }

        .dms-full  { display: inline; }
        .dms-short { display: none;   }

        li.menu-title {
            overflow: hidden !important;
            white-space: nowrap !important;
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
            border-radius: 5px;
            padding: 20px;
            background: white;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            position: relative;
            overflow: hidden;
            min-height: 105px;
        }
        
        .dashboard-card .icon-circle {
            position: absolute;
            right: 20px;
            top: 20px;
            width: 45px;
            height: 45px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #d07e0a;
        }
        
        .dashboard-card .icon-circle i {
            color: white;
            font-size: 20px;
        }
        
        .dashboard-card h2 {
            font-size: 32px;
            font-weight: 700;
            margin-bottom: 5px;
        }
        
        .dashboard-card p {
            color: #6c757d;
            margin: 0;
            font-size: 14px;
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

        .kpi-card {
            display: flex;
            align-items: center;
            gap: 14px;
            height: 100%;
            padding: 18px 16px;
            border: 1px solid transparent;
            border-radius: 12px;
            transition: transform 0.2s, box-shadow 0.2s;
        }

        .kpi-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 18px rgba(0, 0, 0, 0.1);
        }

        .kpi-icon {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 46px;
            height: 46px;
            border-radius: 12px;
            font-size: 1.3rem;
            flex-shrink: 0;
        }

        .kpi-total {
            background: #fff8f8;
            border-color: #f5d0d0;
        }

        .kpi-total .kpi-icon {
            background: #8b0000;
            color: #ffffff;
        }

        .kpi-pending {
            background: #fffbf0;
            border-color: #fde8b0;
        }

        .kpi-pending .kpi-icon {
            background: #e67e22;
            color: #ffffff;
        }

        .kpi-approved {
            background: #f0faf4;
            border-color: #b7e4c7;
        }

        .kpi-approved .kpi-icon {
            background: #27ae60;
            color: #ffffff;
        }

        .kpi-declined {
            background: #f8f9fa;
            border-color: #dee2e6;
        }

        .kpi-declined .kpi-icon {
            background: #6c757d;
            color: #ffffff;
        }

        .kpi-value {
            font-size: 1.8rem;
            font-weight: 800;
            line-height: 1;
            color: #1a1a2e;
            font-variant-numeric: tabular-nums;
        }

        .kpi-label {
            margin-top: 3px;
            font-size: 0.78rem;
            font-weight: 500;
            color: #6c757d;
        }

        .kpi-trend {
            margin-top: 5px;
            font-size: 0.72rem;
            font-weight: 600;
        }

        .kpi-trend.up   { color: #27ae60; }
        .kpi-trend.down { color: #c0392b; }
        .kpi-trend.neutral { color: #95a5a6; }

        .chart-card {
            border: 1px solid #f0f0f0 !important;
            border-radius: 7px !important;
        }

        .chart-wrap {
            position: relative;
            width: 100%;
        }

        .chart-wrap canvas {
            width: 100% !important;
        }

        .legend-dot {
            display: flex;
            align-items: center;
            gap: 5px;
            font-size: 0.75rem;
            color: #6c757d;
        }

        .legend-dot::before {
            content: "";
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: var(--dot);
            display: inline-block;
        }

        .donut-legend {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
        }

        .donut-item {
            display: flex;
            align-items: center;
            gap: 6px;
            flex: 1 1 calc(50% - 4px);
            font-size: 0.75rem;
            color: #495057;
        }

        .donut-item span {
            width: 10px;
            height: 10px;
            border-radius: 3px;
            display: inline-block;
            flex-shrink: 0;
        }

        .donut-item strong {
            margin-left: auto;
            color: #1a1a2e;
        }

        .weekly-stats {
            padding-top: 12px;
            border-top: 1px solid #f0f0f0;
        }

        .summary-card {
            display: flex;
            align-items: center;
            gap: 14px;
            padding: 16px 18px;
            background: #fff;
            border: 1px solid #f0f0f0;
            border-radius: 10px;
            text-decoration: none;
            color: inherit;
            transition: transform 0.2s, box-shadow 0.2s, border-color 0.2s;
            height: 100%;
        }

        .summary-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 18px rgba(0,0,0,0.08);
            border-color: #8b0000;
            color: inherit;
            text-decoration: none;
        }

        .summary-icon {
            width: 46px;
            height: 46px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.3rem;
            flex-shrink: 0;
        }

        .summary-card .count {
            font-size: 1.6rem;
            font-weight: 800;
            line-height: 1;
            color: #1a1a2e;
        }

        .summary-card .label {
            font-size: 0.78rem;
            font-weight: 500;
            color: #6c757d;
            margin-top: 3px;
        }

        .summary-card .sub {
            font-size: 0.7rem;
            color: #adb5bd;
            margin-top: 2px;
        }

        .file-card {
            position: relative;
            z-index: 1;
            width: 100%;

            transition: all 0.3s ease;
        }

        .file-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
        }

        .file-card .more-btn {
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .file-card:hover .more-btn,
        .file-card.dropdown-open .more-btn {
            opacity: 1;
        }

        .file-more-btn {
            background-color: #ffffff !important;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            transition: all 0.2s ease;
        }

        .file-more-btn:hover {
            background-color: #f8f9fa !important;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.15);
        }

        .file-more-btn:active {
            background-color: #e9ecef !important;
            transform: scale(0.95);
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);
        }

        .file-dropdown-menu {
            position: fixed;
            min-width: 200px;
            display: none;
            background: #ffffff;
            border-radius: 12px;
            overflow: hidden;
            z-index: 1025;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            animation: dropdownFadeIn 0.15s ease-out;
        }

        .file-dropdown-menu.show {
            display: block;
        }

        @keyframes dropdownFadeIn {
            from { opacity: 0; transform: translateY(-5px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        .file-dropdown-item {
            display: flex;
            align-items: center;
            gap: 12px;

            width: 100%;
            padding: 12px 16px;

            border: none;
            background: none;

            cursor: pointer;
            user-select: none;

            font-size: 0.875rem;
            font-weight: 500;
            text-align: left;
            color: #212529;

            transition: all 0.2s ease;
        }

        .file-dropdown-item:hover { 
            background-color: #f8f9fa; 
        }

        .file-dropdown-item:active { 
            background-color: #e9ecef; 
            transform: scale(0.98); 
        }

        .file-dropdown-item i {
            width: 20px;
            text-align: center;
            transition: transform 0.2s ease;
        }

        .file-dropdown-item:hover i { 
            transform: scale(1.1); 
        }

        .file-dropdown-divider {
            height: 1px;
            margin: 4px 0;
            background-color: #dee2e6;
        }

        .file-dropdown-item.danger { 
            color: #dc3545; 
        }

        .file-dropdown-item.danger:hover { 
            background-color: #fee; 
        }

        .drive-list-container {
            background: #ffffff;
            border-radius: 8px;
            overflow: hidden;
        }

        .drive-list-header {
            padding: 8px 16px;
            background: #f8f9fa;
            border-bottom: 1px solid #e0e0e0;
            font-size: 0.75rem;
            font-weight: 600;
            color: #5f6368;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }

        .drive-list-row {
            display: flex;
            align-items: center;
            gap: 16px;
            width: 100%;
        }

        .drive-list-item {
            padding: 8px 16px;
            border-bottom: 1px solid #f0f0f0;
            cursor: pointer;
            position: relative;
            transition: background-color 0.2s ease;
        }

        .drive-list-item:hover { 
            background-color: #f8f9fa; 
        }

        .drive-list-item:last-child { 
            border-bottom: none; 
        }

        .drive-col-name { 
            flex: 1; 
            min-width: 0; 
            padding-right: 12px; 
        }

        .drive-col-owner { 
            width: 110px; 
            flex-shrink: 0; 
        }

        .drive-col-dept { 
            width: 180px; 
            flex-shrink: 0; 
        }

        .drive-col-modified { 
            width: 120px; 
            flex-shrink: 0; 
        }

        .drive-col-size { 
            width: 80px; 
            text-align: right; 
            flex-shrink: 0; 
        }

        .drive-col-actions  { 
            width: 48px; 
            display: flex; 
            justify-content: center; 
            align-items: center; 
            flex-shrink: 0; 
        }

        .file-name {
            font-size: 0.875rem;
            font-weight: 500;
            color: #202124;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .file-subtitle {
            font-size: 0.75rem;
            color: #5f6368;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .meta-tag {
            display: inline-flex;
            align-items: center;
            gap: 3px;
            padding: 1px 5px;
            font-size: 0.65rem;
            color: #495057;
            background: #f1f3f5;
            border-radius: 4px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .qms-chart-card {
            border: 1px solid #f0f0f0 !important;
            border-radius: 7px !important;
        }

        #gridView,
        #listView {
            animation: fadeIn 0.2s ease-in;
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to   { opacity: 1; }
        }

        .empty-state {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
        }

        .empty-icon  { 
            font-size: 4rem; 
            color: #9ca3af; 
            margin-bottom: 1.5rem; 
        }

        .empty-title { 
            font-size: 1.3rem; 
            font-weight: 600; 
            color: #1f2937; 
            margin-bottom: 0.5rem; 
        }

        .empty-text  { 
            color: #6b7280; 
            margin-bottom: 1.3rem; 
        }

        #private-documents .dropdown-menu {
            z-index: 1055 !important;
            position: absolute !important;
        }
        .priv-item {
            border-top: none !important;
            border-right: none !important;
            border-bottom: 1px solid #f0f0f0 !important;
            transition: background-color 0.15s ease;
        }

        .priv-item:hover { 
            background-color: #fafafa; 
        }

        .priv-row-clickable:hover {
            box-shadow: 0 2px 8px rgba(0,0,0,0.06);
        }

        .priv-granted  { 
            background-color: #f0fdf4 !important; 
        }

        .priv-pending  { 
            background-color: #fffbf0 !important; 
        }
        .priv-none { 
            background-color: #ffffff !important; 
        }

        .pub-item { 
            background-color: #f0f7ff !important; 
        }

        .priv-status-badge {
            display: inline-flex;
            align-items: center;
            gap: 3px;
            padding: 2px 7px;
            font-size: 0.65rem;
            font-weight: 600;
            border-radius: 20px;
            background: var(--bc);
            color: var(--tc);
            white-space: nowrap;
        }

        .priv-legend {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            font-size: 0.57rem;
            color: #6c757d;
            font-weight: 500;
        }

        .priv-legend span {
            display: inline-block;
            width: 10px;
            height: 10px;
            border-radius: 3px;
            background: var(--lc);
            flex-shrink: 0;
        }

        .report-section { 
            display: none; 
        }

        .report-section.active { 
            display: block; 
        }

        .report-tab-btn.active {
            background: #8B0000;
            color: #fff;
            border-color: #8B0000;
        }

        .section-nav-card {
            border: 2px solid transparent;
            border-radius: 12px;
            cursor: pointer;
            transition: all 0.2s ease;
            background: #fff;
            padding: 1.25rem 1rem;
            text-align: center;
            user-select: none;
        }
        .section-nav-card:hover {
            border-color: #0d6efd;
            box-shadow: 0 4px 16px rgba(13,110,253,0.10);
        }
        .section-nav-card.active {
            border-color: #0d6efd;
            background: #f0f5ff;
            box-shadow: 0 4px 16px rgba(13,110,253,0.13);
        }
        .section-nav-card .nav-icon {
            width: 44px;
            height: 44px;
            border-radius: 10px;
            background: #e8f0fe;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 0.6rem;
            font-size: 1.2rem;
            color: #0d6efd;
            transition: background 0.2s;
        }
        .section-nav-card.active .nav-icon,
        .section-nav-card:hover .nav-icon {
            background: #0d6efd;
            color: #fff;
        }
        .section-nav-card .nav-label {
            font-size: 0.82rem;
            font-weight: 600;
            color: #495057;
            letter-spacing: 0.01em;
        }
        .section-nav-card.active .nav-label { color: #0d6efd; }
        .section-nav-card .nav-count {
            font-size: 0.72rem;
            color: #adb5bd;
            margin-top: 2px;
        }
        .nav-card-btn {
            position: relative;
            z-index: 2;
            width: 100%;
        }

        .config-panel { display: none; }
        .config-panel.active {
            display: flex;
            flex-direction: column;
        }
        .config-panel.active .card-body {
            flex: 1;
            display: flex;
            flex-direction: column;
        }
        .config-panel.active .table-scroll-container {
            flex: 1;
        }
        .panel-header {
            display: flex;
            align-items: center;
            gap: 0.6rem;
            padding: 1rem 1.25rem;
            border-bottom: 1px solid #f0f0f0;
        }
        .panel-header .panel-icon {
            width: 32px;
            height: 32px;
            border-radius: 8px;
            background: #e8f0fe;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1rem;
            color: #0d6efd;
        }
        .panel-header h6 { margin: 0; font-weight: 600; font-size: 0.95rem; color: #212529; }
        .panel-header p  { margin: 0; font-size: 0.75rem; color: #adb5bd; }

        .bottom-controls-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 0.5rem;
            padding-top: 0.75rem;
        }

        .table-scroll-container {
            min-height: 490px;
        }

        .section-nav-card {
            border: 2px solid transparent;
            border-radius: 10px;
            padding: 1rem;
            cursor: pointer;
            transition: border-color 0.2s, box-shadow 0.2s;
            background: #fff;
            text-align: center;
        }

        .section-nav-card:hover {
            border-color: #7b1c1c;
            box-shadow: 0 4px 12px rgba(123, 28, 28, 0.15) !important;
        }

        .section-nav-card.active {
            border-color: #7b1c1c;
            box-shadow: 0 4px 16px rgba(123, 28, 28, 0.2) !important;
        }

        .section-nav-card .nav-icon {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 48px;
            height: 48px;
            border-radius: 12px;
            font-size: 1.4rem;
            background-color: #f3e8e8;
            color: #7b1c1c;
            margin-bottom: 0.5rem;
            transition: background-color 0.2s, color 0.2s;
        }

        .section-nav-card.active .nav-icon {
            background-color: #7b1c1c;
            color: #fff;
        }
        .section-nav-card:hover {
            border: 1.5px solid #7b1c1c !important;
        }

        .section-nav-card:hover .nav-icon {
            background-color: #f3e0e0 !important;
        }

        .section-nav-card:hover .nav-icon i {
            color: #7b1c1c !important;
        }

        .section-nav-card.active .nav-icon {
            background-color: #f3e0e0 !important;
        }

        .section-nav-card.active .nav-icon i {
            color: #7b1c1c !important;
        }

        .section-nav-card .nav-label {
            font-weight: 600;
            color: #333;
            transition: color 0.2s;
        }

        .section-nav-card.active .nav-label {
            color: #7b1c1c;
        }

        .section-nav-card .nav-count {
            font-size: 0.8rem;
            color: #888;
        }

        .section-nav-card .btn-primary {
            background-color: #7b1c1c;
            border-color: #7b1c1c;
        }

        .section-nav-card .btn-primary:hover {
            background-color: #5c1414;
            border-color: #5c1414;
        }

        @media print {
            .filter-bar, .report-tabs, .export-actions,
            #preloaderMarsu, .navbar-menu, #page-topbar,
            .vertical-overlay {
            display: none !important; 
            }
            .report-section {
            display: block !important; 
            }
        }

        @media (max-width: 992px) {
            .drive-col-dept,
            .drive-col-owner,
            .drive-col-size { 
                display: none; 
            }
        }

        @media (max-width: 768px) {
            .drive-list-header { 
                display: none; 
            }
            .drive-list-row { 
                flex-direction: column; 
                align-items: flex-start; 
                gap: 8px; 
            }
        }
    </style>
    @yield('css')
</head>

<body>
    <div id="preloaderMarsu" style="display: none;">
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
                        
                        {{-- <form class="app-search">
                            <div class="position-relative">
                                <input type="text" class="form-control searchbar" placeholder="Search..." autocomplete="off">
                                <span class="mdi mdi-magnify search-widget-icon"></span>
                            </div>
                        </form> --}}
                    </div>

                    @php
                            $unreadDueDateAlerts = getUnreadDueDateAlerts();
                            $unreadDraftRequests = getUnreadDraftRequests();
                            $unreadPendingApproval = getUnreadPendingApproval();
                            $unreadCommentNotifications = getUnreadCommentNotifications();
                            $unreadPublishedNotifs = getUnreadPublishedNotifications();

                            $totalNotifCount = $unreadDueDateAlerts->count()
                                            + $unreadDraftRequests->count()
                                            + $unreadPendingApproval->count()
                                            + $unreadCommentNotifications->count()
                                            + $unreadPublishedNotifs->count();

                            $allNotifications = collect();

                            foreach ($unreadDueDateAlerts as $alert) {
                                $allNotifications->push(['type' => 'due_date', 'data' => $alert, 'sort_date' => $alert->created_at]);
                            }
                            foreach ($unreadDraftRequests as $draft) {
                                $allNotifications->push(['type' => 'draft', 'data' => $draft, 'sort_date' => $draft->created_at]);
                            }
                            foreach ($unreadPendingApproval as $pa) {
                                $allNotifications->push(['type' => 'pending_approval', 'data' => $pa, 'sort_date' => $pa->created_at]);
                            }
                            foreach ($unreadCommentNotifications as $notif) {
                                $allNotifications->push(['type' => 'comment', 'data' => $notif, 'sort_date' => $notif->created_at]);
                            }
                            foreach ($unreadPublishedNotifs as $notif) {
                                $allNotifications->push(['type' => 'published', 'data' => $notif, 'sort_date' => $notif->created_at]);
                            }

                            $allNotifications = $allNotifications->sortByDesc('sort_date');
                        @endphp

                        <div class="dropdown topbar-head-dropdown ms-1 header-item" id="notificationDropdown">
                            <button type="button"
                                    class="btn btn-icon btn-topbar material-shadow-none btn-ghost-secondary rounded-circle"
                                    id="page-header-notifications-dropdown"
                                    data-bs-toggle="dropdown"
                                    data-bs-auto-close="outside"
                                    aria-haspopup="true"
                                    aria-expanded="false">
                                <i class='bx bx-bell fs-22'></i>
                                <span class="position-absolute topbar-badge fs-11 translate-right badge rounded-pill bg-danger"
                                    {{ $totalNotifCount === 0 ? 'style=display:none' : '' }}>
                                    {{ $totalNotifCount }}
                                    <span class="visually-hidden">notifications</span>
                                </span>
                            </button>

                            <div class="dropdown-menu dropdown-menu-lg dropdown-menu-end p-0 shadow-lg"
                                aria-labelledby="page-header-notifications-dropdown"
                                style="border-radius: 12px; overflow: hidden; min-width: 340px; border: none;">

                                <div style="background: linear-gradient(135deg, #8b0000 0%, #5a0000 100%); padding: 14px 16px;">
                                    <div class="d-flex align-items-center justify-content-between">
                                        <div class="d-flex align-items-center gap-2">
                                            <div style="width:32px; height:32px; background:rgba(255,255,255,0.15); border-radius:8px; display:flex; align-items:center; justify-content:center;">
                                                <i class="bx bx-bell text-white" style="font-size:1rem;"></i>
                                            </div>
                                            <div>
                                                <h6 class="m-0 fw-semibold text-white" style="font-size:0.875rem;">Notifications</h6>
                                                <p class="m-0 text-white-50" style="font-size:0.65rem;">
                                                    {{ $totalNotifCount }} unread notification{{ $totalNotifCount !== 1 ? 's' : '' }}
                                                </p>
                                            </div>
                                        </div>
                                        <div class="d-flex align-items-center gap-2">
                                            @if($totalNotifCount > 0)
                                            <button type="button"
                                                    class="btn mark-all-read-btn"
                                                    style="font-size:0.62rem; padding:2px 8px; border:1px solid rgba(255,255,255,0.4); border-radius:20px; color:#fff; background:rgba(255,255,255,0.1); white-space:nowrap; line-height:1.5; transition: all 0.2s;"
                                                    onmouseover="this.style.background='rgba(255,255,255,0.25)'"
                                                    onmouseout="this.style.background='rgba(255,255,255,0.1)'"
                                                    data-type="all"
                                                    data-target="#notificationDropdown">
                                                <i class="bx bx-check-double me-1"></i>Mark all read
                                            </button>
                                            @endif
                                            <span style="background:rgba(255,255,255,0.2); color:#fff; font-size:0.7rem; font-weight:700; padding:2px 8px; border-radius:20px;">
                                                {{ $totalNotifCount }}
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                <div style="background:#fff;">
                                    <div data-simplebar style="max-height: 380px;">

                                        @forelse ($allNotifications as $notif)
                                            @php $type = $notif['type']; $item = $notif['data']; @endphp

                                            @if($type === 'due_date')
                                            @php
                                                $isOverdue = $item->is_overdue;
                                                $dueDate = \Carbon\Carbon::parse($item->due_date);
                                            @endphp
                                            <div class="notification-item"
                                                style="padding: 10px 14px; border-bottom: 1px solid #f5f5f5; transition: background 0.15s;"
                                                onmouseover="this.style.background='#fafafa'"
                                                onmouseout="this.style.background='#fff'">
                                                <div class="d-flex align-items-start gap-3">
                                                    <div style="width:36px; height:36px; border-radius:50%; display:flex; align-items:center; justify-content:center; flex-shrink:0;
                                                                background: {{ $isOverdue ? '#fee2e2' : '#fef9c3' }};">
                                                        <i class="{{ $isOverdue ? 'bx bx-alarm-exclamation' : 'bx bx-time' }}"
                                                        style="font-size:1rem; color:{{ $isOverdue ? '#dc2626' : '#d97706' }};"></i>
                                                    </div>
                                                    <div class="flex-grow-1" style="min-width:0;">
                                                        <div class="d-flex align-items-center justify-content-between gap-2 mb-1">
                                                            <a href="{{ url('change-request/for_approval/' . $item->id) }}"
                                                            class="notification-link stretched-link text-decoration-none"
                                                            data-type="due_date"
                                                            data-id="{{ $item->id }}"
                                                            style="font-size:0.8rem; font-weight:600; color:#1a1a2e;">
                                                                DOC-{{ date('Y', strtotime($item->created_at)) }}-{{ str_pad($item->id, 3, '0', STR_PAD_LEFT) }}
                                                            </a>
                                                            <span style="font-size:0.62rem; font-weight:600; padding:1px 6px; border-radius:20px; white-space:nowrap; flex-shrink:0;
                                                                        background: {{ $isOverdue ? '#fee2e2' : '#fef9c3' }};
                                                                        color: {{ $isOverdue ? '#dc2626' : '#d97706' }};">
                                                                <i class="mdi mdi-calendar-clock"></i>
                                                                {{ $isOverdue ? 'Overdue' : 'Due Soon' }}
                                                            </span>
                                                        </div>
                                                        <p class="mb-1 text-truncate" style="font-size:0.75rem; color:#6c757d; max-width:210px;">
                                                            {{ $item->title }}
                                                        </p>
                                                        <p class="mb-0" style="font-size:0.68rem; color:{{ $isOverdue ? '#dc2626' : '#d97706' }}; font-weight:500;">
                                                            <i class="mdi mdi-calendar-clock"></i>
                                                            {{ $isOverdue ? 'Overdue since ' . $dueDate->format('M d, Y') : 'Due ' . $dueDate->diffForHumans() }}
                                                        </p>
                                                    </div>
                                                    <div style="flex-shrink:0; color:#dee2e6; margin-top:2px;">
                                                        <i class="bx bx-chevron-right" style="font-size:1rem;"></i>
                                                    </div>
                                                </div>
                                            </div>

                                            @elseif($type === 'draft')
                                            <div class="notification-item"
                                                style="padding: 10px 14px; border-bottom: 1px solid #f5f5f5; transition: background 0.15s;"
                                                onmouseover="this.style.background='#fafafa'"
                                                onmouseout="this.style.background='#fff'">
                                                <div class="d-flex align-items-start gap-3">
                                                    <div style="width:36px; height:36px; border-radius:50%; background:#e0f2fe; display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                                                        <i class="bx bx-file" style="font-size:1rem; color:#0284c7;"></i>
                                                    </div>
                                                    <div class="flex-grow-1" style="min-width:0;">
                                                        <div class="d-flex align-items-center justify-content-between gap-2 mb-1">
                                                            <a href="{{ url('documents/create/' . $item->id) }}"
                                                            class="notification-link stretched-link text-decoration-none"
                                                            data-type="draft"
                                                            data-id="{{ $item->id }}"
                                                            style="font-size:0.8rem; font-weight:600; color:#1a1a2e;">
                                                                DOC-{{ date('Y', strtotime($item->created_at)) }}-{{ str_pad($item->id, '3', 0, STR_PAD_LEFT) }}
                                                            </a>
                                                            <span style="font-size:0.62rem; font-weight:600; padding:1px 6px; border-radius:20px; background:#e0f2fe; color:#0284c7; white-space:nowrap; flex-shrink:0;">
                                                                <i class="mdi mdi-pencil"></i> Draft
                                                            </span>
                                                        </div>
                                                        <p class="mb-0 text-truncate" style="font-size:0.75rem; color:#6c757d; max-width:210px;">
                                                            {{ $item->title }}
                                                        </p>
                                                    </div>
                                                    <div style="flex-shrink:0; color:#dee2e6; margin-top:2px;">
                                                        <i class="bx bx-chevron-right" style="font-size:1rem;"></i>
                                                    </div>
                                                </div>
                                            </div>

                                            @elseif($type === 'pending_approval')
                                            @php $req = $item->change_request; @endphp
                                            <div class="notification-item"
                                                style="padding: 10px 14px; border-bottom: 1px solid #f5f5f5; transition: background 0.15s;"
                                                onmouseover="this.style.background='#fafafa'"
                                                onmouseout="this.style.background='#fff'">
                                                <div class="d-flex align-items-start gap-3">
                                                    <div class="d-flex align-items-center justify-content-center"
                                                        style="width:36px; height:36px; border-radius:50%; flex-shrink:0; border:2px solid #f0f0f0; background:#f8fafc;">
                                                        <i class="bx bx-file" style="font-size:18px; color:#1CA7A6;"></i>
                                                    </div>
                                                    <div class="flex-grow-1" style="min-width:0;">
                                                        <div class="d-flex align-items-center justify-content-between gap-2 mb-1">
                                                            <a href="{{ url('change-request/for_approval/' . $req->id) }}"
                                                            class="notification-link stretched-link text-decoration-none"
                                                            data-type="pending_approval"
                                                            data-id="{{ $req->id }}"
                                                            style="font-size:0.8rem; font-weight:600; color:#1a1a2e;">
                                                                DOC-{{ str_pad($req->id, 3, '0', STR_PAD_LEFT) }}
                                                            </a>
                                                            <span style="font-size:0.62rem; font-weight:600; padding:1px 6px; border-radius:20px; background:#cfe2ff; color:#0d6efd; white-space:nowrap; flex-shrink:0;">
                                                                <i class="mdi mdi-clock-check-outline"></i> Pending Approval
                                                            </span>
                                                        </div>
                                                        <p class="mb-1 text-truncate" style="font-size:0.75rem; color:#6c757d; max-width:210px;">
                                                            {{ $req->title }}
                                                        </p>
                                                        <p class="mb-0" style="font-size:0.68rem; color:#9ca3af;">
                                                            <i class="mdi mdi-clock-outline"></i>
                                                            {{ $item->created_at->diffForHumans() }}
                                                        </p>
                                                    </div>
                                                    <div style="flex-shrink:0; color:#dee2e6; margin-top:2px;">
                                                        <i class="bx bx-chevron-right" style="font-size:1rem;"></i>
                                                    </div>
                                                </div>
                                            </div>

                                            @elseif($type === 'comment')
                                            @php $cr = $item->change_request; @endphp
                                            @if($cr)
                                            <div class="notification-item"
                                                style="padding: 10px 14px; border-bottom: 1px solid #f5f5f5; transition: background 0.15s;"
                                                onmouseover="this.style.background='#fafafa'"
                                                onmouseout="this.style.background='#fff'">
                                                <div class="d-flex align-items-start gap-3">
                                                    <div style="width:36px; height:36px; border-radius:50%; background:#f3e8ff; display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                                                        <i class="bx bx-comment-detail" style="font-size:1rem; color:#7c3aed;"></i>
                                                    </div>
                                                    <div class="flex-grow-1" style="min-width:0;">
                                                        <div class="d-flex align-items-center justify-content-between gap-2 mb-1">
                                                            <a href="{{ url('change-request/for_approval/' . $cr->id) }}"
                                                            class="notification-link stretched-link text-decoration-none"
                                                            data-type="comment"
                                                            data-id="{{ $cr->id }}"
                                                            style="font-size:0.8rem; font-weight:600; color:#1a1a2e;">
                                                                DOC-{{ date('Y', strtotime($cr->created_at)) }}-{{ str_pad($cr->id, 3, '0', STR_PAD_LEFT) }}
                                                            </a>
                                                            <span style="font-size:0.62rem; font-weight:600; padding:1px 6px; border-radius:20px; background:#f3e8ff; color:#7c3aed; white-space:nowrap; flex-shrink:0;">
                                                                <i class="mdi mdi-comment-text-outline"></i> New Comment
                                                            </span>
                                                        </div>
                                                        <p class="mb-0 text-truncate" style="font-size:0.75rem; color:#6c757d; max-width:210px;">
                                                            {{ $cr->title }}
                                                        </p>
                                                    </div>
                                                    <div style="flex-shrink:0; color:#dee2e6; margin-top:2px;">
                                                        <i class="bx bx-chevron-right" style="font-size:1rem;"></i>
                                                    </div>
                                                </div>
                                            </div>
                                            @endif

                                            @elseif($type === 'published')
                                            @php $cr = $item->change_request; @endphp
                                            <div class="notification-item"
                                                style="padding: 10px 14px; border-bottom: 1px solid #f5f5f5; transition: background 0.15s;"
                                                onmouseover="this.style.background='#fafafa'"
                                                onmouseout="this.style.background='#fff'">
                                                <div class="d-flex align-items-start gap-3">
                                                    <div style="width:36px; height:36px; border-radius:50%; background:#dcfce7; display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                                                        <i class="bx bx-file-blank" style="font-size:1rem; color:#16a34a;"></i>
                                                    </div>
                                                    <div class="flex-grow-1" style="min-width:0;">
                                                        <div class="d-flex align-items-center justify-content-between gap-2 mb-1">
                                                            <a href="{{ url('monitoring') }}"
                                                            class="notification-link stretched-link text-decoration-none"
                                                            data-type="published"
                                                            data-id="{{ $cr->id }}"
                                                            style="font-size:0.8rem; font-weight:600; color:#1a1a2e;">
                                                                DOC-{{ date('Y', strtotime($cr->created_at)) }}-{{ str_pad($cr->id, 3, '0', STR_PAD_LEFT) }}
                                                            </a>
                                                            <span style="font-size:0.62rem; font-weight:600; padding:1px 6px; border-radius:20px; background:#dcfce7; color:#16a34a; white-space:nowrap; flex-shrink:0;">
                                                                <i class="mdi mdi-check-circle-outline"></i> Published
                                                            </span>
                                                        </div>
                                                        <p class="mb-0 text-truncate" style="font-size:0.75rem; color:#6c757d; max-width:210px;">
                                                            {{ $cr->title }}
                                                        </p>
                                                    </div>
                                                    <div style="flex-shrink:0; color:#dee2e6; margin-top:2px;">
                                                        <i class="bx bx-chevron-right" style="font-size:1rem;"></i>
                                                    </div>
                                                </div>
                                            </div>
                                            @endif

                                        @empty
                                        <div class="text-center py-5 px-3">
                                            <div style="width:52px; height:52px; background:#fef2f2; border-radius:50%; display:flex; align-items:center; justify-content:center; margin:0 auto 12px;">
                                                <i class="bx bx-bell-off" style="font-size:1.5rem; color:#8b0000;"></i>
                                            </div>
                                            <p class="mb-0 fw-semibold" style="font-size:0.8rem; color:#374151;">You're all caught up!</p>
                                            <p class="mb-0" style="font-size:0.72rem; color:#9ca3af;">No new notifications</p>
                                        </div>
                                        @endforelse

                                    </div>
                                </div>

                                @if($totalNotifCount > 0)
                                <div style="padding:10px 14px; background:#fafafa; border-top:1px solid #f0f0f0; text-align:center;">
                                    <a href="{{ url('/change-requests') }}"
                                    style="font-size:0.75rem; color:#8b0000; font-weight:600; text-decoration:none;"
                                    onmouseover="this.style.textDecoration='underline'"
                                    onmouseout="this.style.textDecoration='none'">
                                        View all notifications <i class="bx bx-right-arrow-alt"></i>
                                    </a>
                                </div>
                                @endif
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
                            <div class="dropdown-menu dropdown-menu-end" style="width: 180px;">
                                <h6 class="dropdown-header">Welcome {{current(explode(' ',auth()->user()->name))}}!</h6>
                                <div class="dropdown-divider"></div>
                                <a href="{{ url('/documents') }}" class="dropdown-item {{ Request::is('documents*') && !Request::is('shared*') ? 'active' : '' }}" data-key="t-all-documents"> 
                                    <i class="mdi mdi-file-account-outline text-muted fs-16 align-middle me-1"></i> 
                                    <span class="align-middle" data-key="t-personal">Personal</span>
                                </a>
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

        <div class="app-menu navbar-menu">
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
                        <li class="menu-title mt-2">
                            <span data-key="t-menu" class="dms-full">DOCUMENT MANAGEMENT SYSTEM</span>
                            <span class="dms-short">DMS</span>
                        </li>
                        <li class="menu-title"><span data-key="t-menu">Menu</span></li>
                        
                        <!-- Dashboard -->
                        {{-- @if(canView('dashboard')) --}}
                        <li class="nav-item {{ Route::current()->getName() == 'home' ? 'active' : '' }}">
                            <a class="nav-link menu-link" href="{{url('/home')}}">
                                <i class="ri-dashboard-2-line"></i> 
                                <span data-key="t-dashboards">Dashboard</span>
                            </a>
                        </li>
                        {{-- @endif --}}

                        @if(canView("monitoring.view"))
                        <li class="nav-item {{ Request::is('monitoring*') ? 'active' : '' }}">
                            <a class="nav-link menu-link" href="{{ url('/monitoring') }}">
                                <i class="ri-bar-chart-box-line"></i>
                                <span>Monitoring</span>
                            </a>
                        </li>
                        @endif

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
                        @if(canView('files.view'))
                        <li class="nav-item {{ Route::current()->getName() == 'change-requests' ? 'active' : '' }}">
                            <a class="nav-link menu-link" href="{{url('/change-requests')}}">
                                <i class="ri-edit-line"></i>
                                @if(auth()->user()->role == 'Administrator')
                                    <span data-key="t-change-requests">Files</span>
                                @else
                                    <span data-key="t-change-requests">My Files</span>
                                @endif
                            </a>
                        </li>
                        @endif

                        @php
                            $forApprovalActive = Request::is('for-approval*') || Request::is('for-request-access*');

                            $docApprovalsCount   = \App\RequestApprover::where('user_id', auth()->id())
                                                        ->where('status', 'Pending')
                                                        ->count();

                            $accessRequestsCount = \App\DocumentRequestAccess::where('status', 'Pending')->count(); 

                            $totalForApprovalCount = $docApprovalsCount + $accessRequestsCount;
                        @endphp

                        @if(canView('document_approvals.view') || canView("access_request.view"))
                        <li class="nav-item {{ $forApprovalActive ? 'active' : '' }}">
                            <a class="nav-link menu-link {{ $forApprovalActive ? '' : 'collapsed' }}"
                            href="#sidebarForApproval" data-bs-toggle="collapse" role="button"
                            aria-expanded="{{ $forApprovalActive ? 'true' : 'false' }}"
                            aria-controls="sidebarForApproval">
                                <i class="ri-checkbox-line"></i>
                                <span data-key="t-for-approval">For Approval</span>
                                @if($totalForApprovalCount > 0)
                                    <span class="badge bg-warning rounded-pill ms-2">{{ $totalForApprovalCount }}</span>
                                @endif
                            </a>
                            <div class="menu-dropdown collapse {{ $forApprovalActive ? 'show' : '' }}" id="sidebarForApproval">
                                <ul class="nav nav-sm flex-column">
                                    @if(canView('document_approvals.view'))
                                    <li class="nav-item">
                                        <a href="{{ url('/for-approval') }}"
                                        class="nav-link {{ Request::is('for-approval*') ? 'active' : '' }}"
                                        data-key="t-approval-documents">
                                            Document Approvals
                                            @if($docApprovalsCount > 0)
                                                <span class="badge bg-warning rounded-pill float-end">{{ $docApprovalsCount }}</span>
                                            @endif
                                        </a>
                                    </li>
                                    @endif
                                    @if(canView('access_request.view'))
                                    <li class="nav-item">
                                        <a href="{{ url('/for-request-access') }}"
                                        class="nav-link {{ Request::is('for-request-access*') ? 'active' : '' }}"
                                        data-key="t-for-request-access">
                                            Access Requests
                                            @if($accessRequestsCount > 0)
                                                <span class="badge bg-warning rounded-pill float-end">{{ $accessRequestsCount }}</span>
                                            @endif
                                        </a>
                                    </li>
                                    @endif
                                </ul>
                            </div>
                        </li>
                        @endif

                        <!-- Documents -->
                        @if(canView('personal.view') || canView('share_with_me.view') || canView('share_with_others.view'))
                        @php
                            $docsActive = Request::is('documents*') || Request::is('shared-with-me*') || Request::is('shared-with-others*');
                        @endphp
                        <li class="nav-item {{ $docsActive ? 'active' : '' }}">
                            <a class="nav-link menu-link {{ $docsActive ? '' : 'collapsed' }}"
                            href="#sidebarDocuments" data-bs-toggle="collapse" role="button"
                            aria-expanded="{{ $docsActive ? 'true' : 'false' }}"
                            aria-controls="sidebarDocuments">
                                <i class="ri-folder-2-line"></i>
                                <span data-key="t-documents">Documents</span>
                            </a>
                            <div class="menu-dropdown collapse {{ $docsActive ? 'show' : '' }}" id="sidebarDocuments">
                                <ul class="nav nav-sm flex-column">
                                    @if(canView('personal.view'))
                                    <li class="nav-item">
                                        <a href="{{ url('/documents') }}"
                                        class="nav-link {{ Request::is('documents*') && !Request::is('shared*') ? 'active' : '' }}"
                                        data-key="t-all-documents">
                                            Personal
                                        </a>
                                    </li>
                                    @endif
                                    @if(canView('share_with_me.view'))
                                    <li class="nav-item">
                                        <a href="{{ url('/shared-with-me') }}"
                                        class="nav-link {{ Request::is('shared-with-me*') ? 'active' : '' }}"
                                        data-key="t-shared-with-me">
                                            Shared with Me
                                        </a>
                                    </li>
                                    @endif
                                    @if(canView('share_with_others.view'))
                                    <li class="nav-item">
                                        <a href="{{ url('/shared-with-others') }}"
                                        class="nav-link {{ Request::is('shared-with-others*') ? 'active' : '' }}"
                                        data-key="t-shared-with-others">
                                            Shared with Others
                                        </a>
                                    </li>
                                    @endif
                                </ul>
                            </div>
                        </li>
                        @endcan

                        {{-- <!-- Acknowledgement -->
                        <li class="nav-item {{ Route::current()->getName() == 'acknowledgement' ? 'active' : '' }}">
                            <a class="nav-link menu-link" href="{{url('/acknowledgement')}}">
                                <i class="ri-user-star-line"></i>
                                <span data-key="t-acknowledgement">Acknowledgement</span>
                            </a>
                        </li> --}}

                        <!-- Permits & Licenses -->
                        @if(canView('permits_and_license.view'))
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

                        <!-- Approver Stamp -->
                        @if(canView('approver_stamp.view'))
                        <li class="nav-item @if(Request::is('approver-stamp')) active @endif">
                            <a class="nav-link menu-link" href="{{url('approver-stamp')}}">
                                <i class="mdi mdi-stamper"></i>
                                <span data-key="t-memorandum">Approver Stamp</span>
                            </a>
                        </li>
                        @endif

                        @if((auth()->user()->role == 'Administrator'))
                            <li class="menu-title"><span data-key="t-menu">ADMIN</span></li>
                        @endif

                        <!-- Settings Submenu -->
                        @if(canView('users.view') || canView('roles.view') || canView('system_configuration.view'))
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
                                    {{-- @if(canView('department'))
                                    <li class="nav-item">
                                        <a href="{{ url('departments') }}" class="nav-link {{ Request::is('departments*') ? 'active' : '' }}" data-key="t-departments">Departments</a>
                                    </li>
                                    @endif --}}

                                    {{-- @if(canView('teams'))
                                    <li class="nav-item">
                                        <a href="{{ url('teams') }}" class="nav-link {{ Request::is('teams*') ? 'active' : '' }}" data-key="t-teams">Offices</a>
                                    </li>
                                    @endif --}}

                                    @if(canView('users.view'))
                                    <li class="nav-item">
                                        <a href="{{ url('users') }}" class="nav-link {{ Request::is('users*') ? 'active' : '' }}" data-key="t-users">Users</a>
                                    </li>
                                    @endif

                                    {{-- @if(canView('documents_type'))
                                    <li class="nav-item">w
                                        <a href="{{ url('documents_type') }}" class="nav-link {{ Request::is('documents_type*') ? 'active' : '' }}" data-key="t-documents_type">Type of Documents</a>
                                    </li>
                                    @endif --}}

                                    {{-- @can('rmo')
                                        <li class="nav-item">
                                            <a href="{{ url('dco') }}" class="nav-link {{ Request::is('dco') || Request::is('new-dco') || Request::is('*dco*') && !Request::is('dco-reports') ? 'active' : '' }}" data-key="t-dco">RMO</a>
                                        </li>
                                    @endcan --}}

                                    @if(canView('roles.view'))
                                    <li class="nav-item">
                                        <a href="{{ url('roles') }}" class="nav-link {{ Request::is('roles*') ? 'active' : '' }}" data-key="t-roles">Roles</a>
                                    </li>
                                    @endif

                                    {{-- @can("office")
                                    <li class="nav-item">
                                        <a href="{{ url('offices') }}" class="nav-link" data-key="t-dco">Offices</a>
                                    </li>
                                    @endcan --}}

                                    {{-- @if(canView('access_control'))
                                    <li class="nav-item">
                                        <a href="{{ url('access-control') }}" class="nav-link {{ Request::is('access-control*') ? 'active' : '' }}" data-key="t-access-control">Access Control</a>
                                    </li>
                                    @endif --}}

                                    @if(canView('system_configuration.view'))
                                    <li class="nav-item">
                                        <a href="{{ url('system-configuration') }}" class="nav-link {{ Request::is('system-configuration*') ? 'active' : '' }}" data-key="t-system-configuration">System Configuration</a>
                                    </li>
                                    @endif
                                </ul>
                            </div>
                        </li>
                        @endif

                        @if(canView("reports.view"))
                        <div>
                            <li class="nav-item @if(Request::is('reports')) active @endif">
                                <a class="nav-link menu-link" href="{{url('reports')}}">
                                    <i class="mdi mdi-file-chart"></i>
                                    <span data-key="t-reports">Reports</span>
                                </a>
                            </li>
                        </div>
                        @endif

                        {{-- SSO Default Access --}}
                        {{-- @if(auth()->user()->google_id != null)
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
                        @endif --}}

                    </ul>
                </div>
            </div>

            <div class="sidebar-background"></div>
        </div>

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

    <button onclick="topFunction()" class="btn btn-danger btn-icon" id="back-to-top">
        <i class="ri-arrow-up-line"></i>
    </button>

    @include('sweetalert::alert')
    
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

    <script src="{{ asset('login_css/js/plugins/sweetalert/sweetalert.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    @yield('js')
    
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
    
    {{-- <script>
        window.addEventListener('load', function() {
            document.getElementById('preloaderMarsu').style.display = 'none';
        });
    </script> --}}

    <script>
        document.addEventListener('DOMContentLoaded', function () {

            function updateBadge(badge, delta) {
                if (!badge) return;
                let current = parseInt(badge.textContent.trim()) || 0;
                let updated = Math.max(0, current + delta);
                badge.textContent = updated;
                badge.style.display = updated === 0 ? 'none' : '';
            }

            function setBadge(badge, value) {
                if (!badge) return;
                badge.textContent = value;
                badge.style.display = value === 0 ? 'none' : '';
            }

            function fadeOutItem(item) {
                if (!item) return;
                item.style.transition = 'opacity 0.3s ease';
                item.style.opacity = '0';
                item.style.overflow = 'hidden';
                setTimeout(function () {
                    item.style.maxHeight = '0';
                    item.style.padding = '0';
                    item.style.margin = '0';
                }, 300);
            }

            function showEmptyState(dropdownEl, type) {
                var container = dropdownEl
                    ? dropdownEl.querySelector('.tab-pane [data-simplebar]')
                    : null;
                if (!container) return;

                setTimeout(function () {
                    container.innerHTML = type === 'pending_approval'
                        ? '<div class="text-center p-4 text-muted"><i class="bx bx-check-circle fs-24 mb-2 d-block"></i><small>No pending approvals</small></div>'
                        : '<div class="text-center p-4 text-muted"><i class="bx bx-bell-off fs-24 mb-2 d-block"></i><small>No notifications</small></div>';
                }, 400);
            }

            function hideMarkAllBtn(dropdownEl) {
                var btn = dropdownEl ? dropdownEl.querySelector('.mark-all-read-btn') : null;
                if (btn) btn.style.display = 'none';
            }

            document.querySelectorAll('.notification-link').forEach(function (link) {
                link.addEventListener('click', function (e) {
                    e.preventDefault();
                    e.stopPropagation();

                    var clickedLink     = this;
                    var href            = clickedLink.getAttribute('href');
                    var changeRequestId = clickedLink.dataset.id;
                    var type            = clickedLink.dataset.type;

                    var dropdownEl  = clickedLink.closest('.dropdown');
                    var iconBadge   = dropdownEl ? dropdownEl.querySelector('button.btn-topbar .topbar-badge') : null;
                    var headerBadge = dropdownEl ? dropdownEl.querySelector('.dropdown-head .badge') : null;
                    var item        = clickedLink.closest('.notification-item');

                    fetch('{{ route("notifications.markRead") }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        },
                        body: JSON.stringify({
                            type:              type,
                            change_request_id: changeRequestId,
                        }),
                    })
                    .then(function (res) { return res.json(); })
                    .then(function (data) {
                        if (data.success && data.is_new_read) {
                            updateBadge(iconBadge, -1);
                            updateBadge(headerBadge, -1);
                            fadeOutItem(item);
                        }
                    })
                    .catch(function () {
                    })
                    .finally(function () {
                        setTimeout(function () {
                            window.location.href = href;
                        }, 400);
                    });
                });
            });

            document.querySelectorAll('.mark-all-read-btn').forEach(function (btn) {
                btn.addEventListener('click', function (e) {
                    e.preventDefault();
                    e.stopPropagation();

                    var type = this.dataset.type;
                    var targetSel  = this.dataset.target;
                    var dropdownEl = document.querySelector(targetSel);
                    var iconBadge = dropdownEl ? dropdownEl.querySelector('button.btn-topbar .topbar-badge') : null;
                    var headerBadge= dropdownEl ? dropdownEl.querySelector('.dropdown-head .badge') : null;
                    var clickedBtn = this;

                    clickedBtn.disabled = true;
                    clickedBtn.textContent = 'Marking...';

                    fetch('{{ route("notifications.markAllRead") }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        },
                        body: JSON.stringify({ type: type }),
                    })
                    .then(function (res) { return res.json(); })
                    .then(function (data) {
                        if (data.success) {
                            setBadge(iconBadge, 0);
                            setBadge(headerBadge, 0);

                            var items = dropdownEl ? dropdownEl.querySelectorAll('.notification-item') : [];
                            items.forEach(function (item) { fadeOutItem(item); });

                            showEmptyState(dropdownEl, type);
                            hideMarkAllBtn(dropdownEl);

                            setTimeout(function () {
                                window.location.reload();
                            }, 500);
                        }
                    })
                    .catch(function () {
                        clickedBtn.disabled = false;
                        clickedBtn.textContent = 'Mark all as read';
                    });
                });
            });

        });
        </script>

    <script>
        function updateDmsLabel() {
            const full  = document.querySelector('.dms-full');
            const short = document.querySelector('.dms-short');
            if (!full || !short) return;

            const sidebarWidth = document.querySelector('.app-menu')?.offsetWidth || 999;
            const isNarrow = sidebarWidth < 200;

            if (isNarrow) {
                full.style.display  = 'none';
                short.style.display = 'inline';
            } else {
                full.style.display  = 'inline';
                short.style.display = 'none';
            }
        }

        window.addEventListener('load', function () {
            updateDmsLabel();

            new MutationObserver(updateDmsLabel).observe(document.documentElement, {
                attributes: true
            });

            new MutationObserver(updateDmsLabel).observe(document.body, {
                attributes: true
            });

            const appMenu = document.querySelector('.app-menu');
            if (appMenu) {
                new MutationObserver(updateDmsLabel).observe(appMenu, {
                    attributes: true,
                    attributeFilter: ['style', 'class']
                });
            }

            const sidebar = document.querySelector('.app-menu');
            if (sidebar && window.ResizeObserver) {
                new ResizeObserver(updateDmsLabel).observe(sidebar);
            }

            let count = 0;
            const poll = setInterval(function () {
                updateDmsLabel();
                if (++count >= 10) clearInterval(poll);
            }, 300);
        });
    </script>
    
</body>
</html>