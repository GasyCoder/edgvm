<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    {{-- Évite l’auto-détection téléphone sur mobile (souvent préférable) --}}
    <meta name="format-detection" content="telephone=no">

    {{-- CSRF (utile pour Livewire/AJAX) --}}
    <meta name="csrf-token" content="{{ csrf_token() }}">

    @php
        $siteUrl   = rtrim(config('app.url') ?: url('/'), '/');
        $siteName  = $appSettings->site_name ?: 'EDGVM';
        $brand     = 'École Doctorale Génie du Vivant et Modélisation';

        // Title: priorité $title -> seo_title -> site_name (+ brand si besoin)
        $pageTitle = trim(($title ?? '') ?: ($appSettings->seo_title ?? '') ?: ($siteName . ' – ' . $brand));

        // Description (max ~160-180 conseillé)
        $pageDescription = trim($appSettings->meta_description ?? '');
        $pageDescription = $pageDescription !== ''
            ? \Illuminate\Support\Str::limit($pageDescription, 180, '')
            : '';

        // Keywords (optionnel, peu utile aujourd’hui)
        $pageKeywords = trim($appSettings->meta_keywords ?? '');

        // Canonical
        $canonicalUrl = url()->current();

        // Image SEO (on réutilise le logo si vous n’avez pas d’image dédiée)
        $logoUrl = !empty($appSettings->logo_path) ? asset('storage/'.$appSettings->logo_path) : null;
        $ogImage = $logoUrl;

        // Robots : index en prod, noindex ailleurs
        $robots = app()->environment('production') ? 'index,follow' : 'noindex,nofollow';

        // Social links (sameAs)
        $sameAs = array_values(array_filter([
            $appSettings->facebook_url ?? null,
            $appSettings->twitter_url ?? null,
            $appSettings->linkedin_url ?? null,
            $appSettings->youtube_url ?? null,
            $appSettings->instagram_url ?? null,
        ]));
    @endphp

    <title>{{ $pageTitle }}</title>

    {{-- Canonical (anti-duplicate) --}}
    <link rel="canonical" href="{{ $canonicalUrl }}">

    {{-- Robots --}}
    <meta name="robots" content="{{ $robots }}">

    {{-- Meta description (SEO critical) --}}
    @if($pageDescription !== '')
        <meta name="description" content="{{ $pageDescription }}">
    @endif

    {{-- Meta keywords (facultatif) --}}
    @if($pageKeywords !== '')
        <meta name="keywords" content="{{ $pageKeywords }}">
    @endif

    {{-- Sitemap (facultatif ; sinon via robots.txt) --}}
    @if(!empty($appSettings->sitemap_url))
        <link rel="sitemap" type="application/xml" href="{{ $appSettings->sitemap_url }}">
    @endif

    {{-- Favicons --}}
    @if(!empty($appSettings->favicon_path))
        <link rel="icon" href="{{ asset('storage/'.$appSettings->favicon_path) }}">
        <link rel="apple-touch-icon" href="{{ asset('storage/'.$appSettings->favicon_path) }}">
    @endif

    {{-- Open Graph --}}
    <meta property="og:type" content="website">
    <meta property="og:site_name" content="{{ $siteName }}">
    <meta property="og:locale" content="{{ app()->getLocale() }}">
    <meta property="og:title" content="{{ $pageTitle }}">
    <meta property="og:url" content="{{ $canonicalUrl }}">
    @if($pageDescription !== '')
        <meta property="og:description" content="{{ $pageDescription }}">
    @endif
    @if($ogImage)
        <meta property="og:image" content="{{ $ogImage }}">
        <meta property="og:image:alt" content="{{ $siteName }}">
    @endif

    {{-- Twitter Card --}}
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $pageTitle }}">
    @if($pageDescription !== '')
        <meta name="twitter:description" content="{{ $pageDescription }}">
    @endif
    @if($ogImage)
        <meta name="twitter:image" content="{{ $ogImage }}">
    @endif

    {{-- JSON-LD : Organization (améliore la compréhension par Google) --}}
    <script type="application/ld+json">
        {!! json_encode([
            '@context' => 'https://schema.org',
            '@type'    => 'EducationalOrganization',
            'name'     => $siteName,
            'url'      => $siteUrl,
            'logo'     => $logoUrl,
            'email'    => $appSettings->site_email ?? null,
            'telephone'=> $appSettings->site_phone ?? null,
            'address'  => !empty($appSettings->site_address) ? [
                '@type' => 'PostalAddress',
                'streetAddress' => $appSettings->site_address,
                'addressCountry' => 'MG',
            ] : null,
            'sameAs'   => $sameAs ?: null,
        ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}
    </script>

    {{-- CSS/JS --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles

    {{-- Permet aux pages d’injecter des metas spécifiques : og:type=article, noindex, etc. --}}
    @stack('head')
</head>

<body class="antialiased">
    {{-- Pour l’accessibilité + SEO UX --}}
    <a href="#main-content" class="sr-only focus:not-sr-only focus:absolute focus:top-3 focus:left-3 bg-white text-black px-3 py-2 rounded">
        Aller au contenu principal
    </a>

    @livewire('frontend.navbar')

    <main id="main-content">
        {{ $slot }}
    </main>

    @livewire('frontend.footer')
    @livewire('frontend.scroll-to-top')

    @livewireScripts
    @stack('scripts')
</body>
</html>