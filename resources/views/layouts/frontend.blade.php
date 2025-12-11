<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{-- Titre de la page --}}
    <title>
        @if(isset($title))
            {{ $title }}
        @elseif(!empty($appSettings->seo_title))
            {{ $appSettings->seo_title }}
        @elseif(!empty($appSettings->site_name))
            {{ $appSettings->site_name }} – École Doctorale Génie du Vivant et Modélisation
        @else
            EDGVM - École Doctorale Génie du Vivant et Modélisation
        @endif
    </title>

    {{-- Meta description --}}
    @if(!empty($appSettings->meta_description))
        <meta name="description" content="{{ $appSettings->meta_description }}">
    @endif

    {{-- Meta keywords --}}
    @if(!empty($appSettings->meta_keywords))
        <meta name="keywords" content="{{ $appSettings->meta_keywords }}">
    @endif

    {{-- Sitemap (optionnel) --}}
    @if(!empty($appSettings->sitemap_url))
        <link rel="sitemap" type="application/xml" href="{{ $appSettings->sitemap_url }}">
    @endif

    {{-- Favicon à partir du setting --}}
    @if(!empty($appSettings->favicon_path))
        <link rel="icon" type="image/png" href="{{ asset('storage/'.$appSettings->favicon_path) }}">
    @endif

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="antialiased">
    @livewire('frontend.navbar')
    
    <main>
        {{ $slot }}
    </main>
    
    @livewire('frontend.footer')
    @livewire('frontend.scroll-to-top')
    
    @livewireScripts
    @stack('scripts')
</body>
</html>
