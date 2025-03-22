<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Tom Select CSS -->
    <link href="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/css/tom-select.css" rel="stylesheet">
    <!-- Tom Select JS -->
    <script src="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/js/tom-select.complete.min.js"></script>

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        /* Tom Select Dark Mode Uyumu */
        html.dark .ts-wrapper.single .ts-control {
            background-color: #111827 !important;
            color: #f9fafb !important;
            border-color: #374151 !important;
            height: 42px !important;
            border-radius: 6px !important;
        }

        html.dark .ts-dropdown {
            background-color: #111827 !important;
            border-color: #374151 !important;
            color: #f9fafb !important;
        }

        html.dark .ts-dropdown .active {
            background-color: #374151 !important;
            color: #ffffff !important;
        }

        html.dark .ts-control {
            background-color: #111827 !important;
            color: #f9fafb !important;
            border-color: #374151 !important;
            display: flex !important;
            align-items: center !important;
            justify-content: start !important;
        }
    </style>

</head>

<body class="font-sans antialiased">
    <div class="min-h-screen bg-gray-100 dark:bg-gray-900">
        @include('layouts.navigation')

        <!-- Page Heading -->
        @isset($header)
            <header class="bg-white dark:bg-gray-800 shadow">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    {{ $header }}
                </div>
            </header>
        @endisset

        @if (session('success'))
            <x-toast type="success" :message="session('success')" />
        @endif

        @if (session('error'))
            <x-toast type="error" :message="session('error')" />
        @endif

        @if (session('info'))
            <x-toast type="info" :message="session('info')" />
        @endif

        <!-- Page Content -->
        <main>
            {{ $slot }}
        </main>
    </div>
</body>

</html>
