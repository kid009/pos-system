<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" href="{{ asset('assets/images/favicon.png') }}" type="image/x-icon">
    <link rel="shortcut icon" href="{{ asset('assets/images/favicon.png') }}" type="image/x-icon">
    <title>{{ config('app.name', 'Laravel') }}</title>

    @include('layouts.partials.css')
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

    @include('layouts.partials.js')

    {{-- =================================================================== --}}
    {{-- ======== START: Global Notification Modal & Trigger Script ======== --}}
    {{-- =================================================================== --}}
    @if ($message = Session::get('success') ?? Session::get('error') ?? Session::get('warning'))
        @php
            $modalType = Session::get('success') ? 'success' : (Session::get('error') ? 'danger' : 'warning');
            $modalTitle = Session::get('success') ? 'Success!' : (Session::get('error') ? 'Error!' : 'Warning!');
        @endphp

        <div class="modal fade" id="notificationModal" tabindex="-1" role="dialog" aria-labelledby="notificationModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header modal-{{ $modalType }}-header">
                        <h5 class="modal-title" id="notificationModalLabel">{{ $modalTitle }}</h5>
                        <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        {{ $message }}
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-secondary" type="button" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>

        <script>
            document.addEventListener('DOMContentLoaded', function () {
                var notificationModal = new bootstrap.Modal(document.getElementById('notificationModal'));
                notificationModal.show();
            });
        </script>
    @endif
    {{-- =================================================================== --}}
    {{-- ========= END: Global Notification Modal & Trigger Script ========= --}}
    {{-- =================================================================== --}}
    
</body>

</html>