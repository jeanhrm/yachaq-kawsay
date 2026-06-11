<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" type="image/png" href="{{ asset('images/tupac.png') }}">
    <title>Yachaq Kawsay</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="font-sans antialiased" style="background:#FFFFFF;">
    <div class="min-h-screen">
        @include('layouts.navigation')
        @if (isset($header))
            <header style="background:var(--unicef-cyan-light);border-bottom:1px solid rgba(28,171,226,0.15);">
                <div class="max-w-7xl mx-auto py-4 px-4 sm:px-6 lg:px-8">
                    {{ $header }}
                </div>
            </header>
        @endif
        <main class="fade-in">
            {{ $slot }}
        </main>
    </div>
    @livewireScripts
</body>
</html>