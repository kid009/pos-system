<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'POS System' }}</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Prompt:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap"
        rel="stylesheet">

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
