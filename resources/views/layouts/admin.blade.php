<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="icon" href="{{ asset('admin_assets/images/favicon.png') }}" type="image/x-icon">
    <link rel="shortcut icon" href="{{ asset('admin_assets/images/favicon.png') }}" type="image/x-icon">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Google font-->
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link
        href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&amp;display=swap"
        rel="stylesheet">
    <link
        href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&amp;display=swap"
        rel="stylesheet">
    <link
        href="https://fonts.googleapis.com/css2?family=Rubik:ital,wght@0,400;0,500;0,600;0,700;0,800;0,900;1,300;1,400;1,500;1,600;1,700;1,800;1,900&amp;display=swap"
        rel="stylesheet">

    <!-- Font Awesome-->
    <link rel="stylesheet" type="text/css" href="{{ asset('admin_assets/css/fontawesome.css') }}">
    <!-- ico-font-->
    <link rel="stylesheet" type="text/css" href="{{ asset('admin_assets/css/icofont.css') }}">
    <!-- Themify icon-->
    <link rel="stylesheet" type="text/css" href="{{ asset('admin_assets/css/themify.css') }}">
    <!-- Feather icon-->
    <link rel="stylesheet" type="text/css" href="{{ asset('admin_assets/css/feather-icon.css') }}">
    <!-- Plugins css start-->
    @stack('css')
    <!-- Plugins css Ends-->
    <!-- Bootstrap css-->
    <link rel="stylesheet" type="text/css" href="{{ asset('admin_assets/css/bootstrap.css') }}">
    <!-- App css-->
    <link rel="stylesheet" type="text/css" href="{{ asset('admin_assets/css/style.css') }}">
    <link id="color" rel="stylesheet" href="{{ asset('admin_assets/css/color-1.css') }}" media="screen">
    <!-- Responsive css-->
    <link rel="stylesheet" type="text/css" href="{{ asset('admin_assets/css/responsive.css') }}">

    <!-- latest jquery-->
    <script src="{{ asset('admin_assets/js/jquery-3.5.1.min.js') }}"></script>
</head>

<body>
    <!-- Loader starts-->
    <div class="loader-wrapper">
        <div class="theme-loader">
            <div class="loader-p"></div>
        </div>
    </div>
    <!-- Loader ends-->

    <!-- page-wrapper Start-->
    <div class="page-wrapper" id="pageWrapper">
        <!-- Page Header Start-->
        @include('layouts.partials.admin_header')
        <!-- Page Header Ends -->

        <!-- Page Body Start-->
        <div class="page-body-wrapper horizontal-menu">

            <!-- Page Sidebar Start-->
            @include('layouts.partials.admin_sidebar')
            <!-- Page Sidebar Ends-->

            <div class="page-body">

                {{-- breadcrumb --}}
                <div class="container-fluid">
                    <div class="page-header">
                        <div class="row">
                            <div class="col-sm-6">
                                <ol class="breadcrumb">
                                    @yield('breadcrumb')
                                </ol>
                            </div>
                            <div class="col-sm-6"></div>
                        </div>
                    </div>
                </div>
                {{-- breadcrumb --}}
                <div class="container-fluid">
                    @yield('content')
                </div>
            </div>

            <!-- footer start-->
            @include('layouts.partials.admin_footer')
            <!-- footer end-->

        </div>

    </div>

    
    <!-- feather icon js-->
    <script src="{{ asset('admin_assets/js/icons/feather-icon/feather.min.js') }}"></script>
    <script src="{{ asset('admin_assets/js/icons/feather-icon/feather-icon.js') }}"></script>
    <!-- Sidebar jquery-->
    <script src="{{ asset('admin_assets/js/sidebar-menu.js') }}"></script>
    <script src="{{ asset('admin_assets/js/config.js') }}"></script>
    <!-- Bootstrap js-->
    <script src="{{ asset('admin_assets/js/bootstrap/popper.min.js') }}"></script>
    <script src="{{ asset('admin_assets/js/bootstrap/bootstrap.min.js') }}"></script>
    <!-- Plugins JS start-->
    @stack('scripts')
    <!-- Plugins JS Ends-->
    <!-- Theme js-->
    <script src="{{ asset('admin_assets/js/script.js') }}"></script>
    <script src="{{ asset('admin_assets/js/theme-customizer/customizer.js') }}"></script>
    <!-- login js-->
    <!-- Plugin used-->

</body>

</html>