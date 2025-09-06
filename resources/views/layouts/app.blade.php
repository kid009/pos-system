<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" href="{{ asset('assets/images/favicon.png') }}" type="image/x-icon">
    <link rel="shortcut icon" href="{{ asset('assets/images/favicon.png') }}" type="image/x-icon">
    <title>@yield('title')</title>

    @include('layouts.partials.css')

    <script src="{{ asset('assets/js/jquery-3.5.1.min.js') }}"></script>
</head>

<body>

    <div class="loader-wrapper">
        <div class="theme-loader">
            <div class="loader-p"></div>
        </div>
    </div>
    <div class="page-wrapper" id="pageWrapper">

        @include('layouts.partials.header')

        <div class="page-body-wrapper horizontal-menu">

            @include('layouts.partials.sidebar')

            <div class="page-body">
                @yield('content')
            </div>

            @include('layouts.partials.footer')

        </div>

    </div>

    {{-- =================================================================== --}}
    {{-- ======== START: Global Notification Modal & Trigger Script ======== --}}
    {{-- =================================================================== --}}
    @include('layouts.partials.notification-modal')
    {{-- =================================================================== --}}
    {{-- ========= END: Global Notification Modal & Trigger Script ========= --}}
    {{-- =================================================================== --}}

    {{-- =================================================================== --}}
    {{-- ============== START: Global Delete Confirmation Modal ============== --}}
    {{-- =================================================================== --}}
    @include('layouts.partials.delete-confirm-modal')
    {{-- =================================================================== --}}
    {{-- =============== END: Global Delete Confirmation Modal =============== --}}
    {{-- =================================================================== --}}

    @include('layouts.partials.js')

</body>

</html>