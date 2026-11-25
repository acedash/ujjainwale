<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        @php
            $defaultSiteTitle = 'Ujjain, Ujjain Simhasth 2028, Ujjain City';
            $pageTitle = !empty($title) ? $title . ' | ' . $defaultSiteTitle : $defaultSiteTitle;
            $metaDesc = $description ?? 'Ujjain, Ujjain Simhasth 2028, Ujjain City - Latest Local News, Simhasth 2028 Updates, Mahakal Darshan Guide, Culture & Local Business Directory.';
            $metaImage = $image ?? asset('images/ujjain-wale.jpeg');
        @endphp

        <title>{{ $pageTitle }}</title>
        <meta name="description" content="{{ $metaDesc }}">

        <!-- Open Graph / WhatsApp / Facebook Preview -->
        <meta property="og:type" content="website">
        <meta property="og:url" content="{{ url()->current() }}">
        <meta property="og:title" content="{{ $pageTitle }}">
        <meta property="og:description" content="{{ $metaDesc }}">
        <meta property="og:image" content="{{ $metaImage }}">
        <meta property="og:site_name" content="Ujjain, Ujjain Simhasth 2028, Ujjain City">

        <!-- Twitter Card -->
        <meta name="twitter:card" content="summary_large_image">
        <meta name="twitter:title" content="{{ $pageTitle }}">
        <meta name="twitter:description" content="{{ $metaDesc }}">
        <meta name="twitter:image" content="{{ $metaImage }}">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700|roboto:400,500,700,900" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        @livewireStyles
    </head>
    <body class="font-sans antialiased text-ink bg-gray-50 selection:bg-city-accent selection:text-white relative overflow-x-hidden">
        <!-- Background Orbs -->
        <div class="fixed inset-0 z-[-1] overflow-hidden pointer-events-none">
            <div class="absolute top-0 left-1/4 w-96 h-96 bg-city-accent/10 rounded-full mix-blend-multiply filter blur-3xl opacity-70 animate-blob"></div>
            <div class="absolute top-0 right-1/4 w-96 h-96 bg-directory-blue/10 rounded-full mix-blend-multiply filter blur-3xl opacity-70 animate-blob" style="animation-delay: 2s;"></div>
            <div class="absolute -bottom-32 left-1/2 w-96 h-96 bg-sage/20 rounded-full mix-blend-multiply filter blur-3xl opacity-70 animate-blob" style="animation-delay: 4s;"></div>
        </div>

        <div class="min-h-screen flex flex-col relative z-0">
            <x-masthead />

            <!-- Page Content -->
            <main class="flex-grow">
                {{ $slot }}
            </main>

            <x-footer />
        </div>
        @livewireScripts
        @stack('scripts')
    </body>
</html>
