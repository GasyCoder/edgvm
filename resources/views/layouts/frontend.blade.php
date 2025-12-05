<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'EDGVM - École Doctorale Génie du Vivant et Modélisation' }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="antialiased">
    @livewire('frontend.navbar')
    
    <main>
        {{ $slot }}
    </main>
    
    @livewire('frontend.footer')
    <!-- Scroll to Top Button -->
    @livewire('frontend.scroll-to-top')
    
    @livewireScripts
    @stack('scripts')
</body>
</html>