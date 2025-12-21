<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'POS System' }}</title>

    @vite(['resources/sass/app.scss', 'resources/js/app.js'])

    <style>
        body {
            overflow: hidden;
            /* ป้องกัน Scrollbar ซ้อน */
        }

        .pos-container {
            height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .product-grid {
            overflow-y: auto;
            height: calc(100vh - 80px);
        }

        .cart-panel {
            height: 100vh;
            border-left: 1px solid #ddd;
            display: flex;
            flex-direction: column;
        }

        .cart-items {
            flex-grow: 1;
            overflow-y: auto;
        }

        .cart-summary {
            background: #f8f9fa;
            border-top: 1px solid #ddd;
            padding: 20px;
        }
    </style>
</head>

<body class="bg-light">

    <x-alert-message />
    <x-confirm-modal />

    <div class="pos-container">
        {{ $slot }}
    </div>

</body>

</html>
