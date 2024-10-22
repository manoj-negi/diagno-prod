<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="layout-menu-fixed">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{isset($title)?$title.' - ':''}}{{ $siteSetting['name'] }}</title>
 
    <!-- Favicon -->
       <link rel="icon" type="image" href="{{url('Images').'/'.$siteSetting['favicon']}}" />
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
        href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap"
        rel="stylesheet" />
    
        
    <!-- Icons. Uncomment required icon fonts -->
    <link rel="stylesheet" href="{{url('vendor/fonts/boxicons.css')}}" />

    <!-- Core CSS -->
    <link rel="stylesheet" href="{{url('vendor/css/core.css')}}" class="template-customizer-core-css" />
    <link rel="stylesheet" href="{{url('vendor/css/theme-default.css')}}" class="template-customizer-theme-css" />
    <link rel="stylesheet" href="{{url('css/demo.css')}}" />

    
    <script src="{{url('js/jquery.min.js')}}"></script>

    <!-- Vendors CSS -->
    <link rel="stylesheet" href="{{url('vendor/libs/perfect-scrollbar/perfect-scrollbar.css')}}" />

    <link rel="stylesheet" href="{{url('vendor/libs/apex-charts/apex-charts.css')}}" />
    <link rel="stylesheet" href="{{ asset('datatable/libs/apex-charts/apex-charts.css') }}" />
    <link rel="stylesheet" href="{{ asset('datatable/libs/datatables-bs5/datatables.bootstrap5.css') }}">
    <link rel="stylesheet" href="{{ asset('datatable/libs/datatables-responsive-bs5/responsive.bootstrap5.css') }}">
    <link rel="stylesheet" href="{{ asset('datatable/libs/datatables-checkboxes-jquery/datatables.checkboxes.css') }}">
    <link rel="stylesheet" href="{{ asset('datatable/libs/datatables-buttons-bs5/buttons.bootstrap5.css') }}">
    <link rel="stylesheet" href="{{ asset('datatable/libs/datatables-rowgroup-bs5/rowgroup.bootstrap5.css') }}">
    <!-- Page CSS -->

    <!-- Helpers -->
    <script src="{{url('vendor/js/helpers.js')}}"></script>

    <!--! Template customizer & Theme config files MUST be included after core stylesheets and helpers.js in the <head> section -->
    <!--? Config:  Mandatory theme config file contain global vars & default theme options, Set your preferred theme option in this file.  -->
    <script src="{{url('js/config.js')}}"></script>
   <style> 
   .help-block {
    color: red;
   }
   </style>
    

</head>

<body>
    <!-- Layout wrapper -->
    <div class="layout-wrapper layout-content-navbar">
        <div class="layout-container">
            @include('layouts.includes.sidebar')
            <!-- Layout container -->
            <div class="layout-page">
                <!-- Content wrapper -->
                <div class="content-wrapper">
                    <!-- Content -->
                    @yield('content')
                </div>
            </div>
        </div>
    </div>


    <!-- Core JS -->
    <!-- build:js assets/vendor/js/core.js -->
    <script src="{{url('vendor/libs/jquery/jquery.js')}}"></script>
    <script src="{{url('vendor/libs/popper/popper.js')}}"></script>
    <script src="{{url('vendor/js/bootstrap.js')}}"></script>
    <script src="{{url('vendor/libs/perfect-scrollbar/perfect-scrollbar.js')}}"></script>

    <script src="{{url('vendor/js/menu.js')}}"></script>
    <!-- endbuild -->

    <!-- Vendors JS -->
    <script src="{{url('vendor/libs/apex-charts/apexcharts.js')}}"></script>

    <!-- Main JS -->
    <script src="{{url('js/main.js')}}"></script>

    <!-- Page JS -->
    <script src="{{url('js/dashboards-analytics.js')}}"></script>
    <script src="{{url('jss/select2.js')}}"></script>
    <script src="{{ asset('datatable/libs/datatables/jquery.dataTables.js') }}"></script>
    <script src="{{ asset('datatable/libs/datatables-bs5/datatables-bootstrap5.js') }}"></script>
    <script src="{{ asset('datatable/libs/datatables-responsive/datatables.responsive.js') }}"></script>
    <script src="{{ asset('datatable/libs/datatables-responsive-bs5/responsive.bootstrap5.js') }}"></script>
    <script src="{{ asset('datatable/libs/datatables-checkboxes-jquery/datatables.checkboxes.js') }}"></script>
    <script src="{{ asset('datatable/libs/datatables-buttons/datatables-buttons.js') }}"></script>
    <script src="{{ asset('datatable/libs/datatables-buttons-bs5/buttons.bootstrap5.js') }}"></script>
    <script src="{{ asset('datatable/libs/datatables-buttons/buttons.html5.js') }}"></script>
    <script src="{{ asset('datatable/libs/datatables-buttons/buttons.print.js') }}"></script>
    <!-- Place this tag in your head or just before your close body tag. -->
    <script async defer src="https://buttons.github.io/buttons.js"></script>
    @yield('js')
</body>

</html>