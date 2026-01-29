<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="overflow-x-hidden">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="format-detection" content="telephone=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    @php
        $siteUrl   = rtrim(config('app.url') ?: url('/'), '/');
        $siteName  = $appSettings->site_name ?? 'EDGVM';
        $brand     = 'École Doctorale Génie du Vivant et Modélisation';
        $pageTitle = $siteName . ' – ' . $brand;
        $pageDescription = trim($appSettings->meta_description ?? '');
        $pageKeywords = trim($appSettings->meta_keywords ?? '');
        $canonicalUrl = url()->current();
        $logoUrl = !empty($appSettings->logo_path) ? asset('storage/'.$appSettings->logo_path) : null;
        $ogImage = $logoUrl;
        $robots = app()->environment('production') ? 'index,follow' : 'noindex,nofollow';
        $sameAs = array_values(array_filter([
            $appSettings->facebook_url ?? null,
            $appSettings->twitter_url ?? null,
            $appSettings->linkedin_url ?? null,
            $appSettings->youtube_url ?? null,
            $appSettings->instagram_url ?? null,
        ]));
    @endphp

    <title inertia>{{ $pageTitle }}</title>

    <link rel="canonical" href="{{ $canonicalUrl }}">
    <meta name="robots" content="{{ $robots }}">

    @if($pageDescription !== '')
        <meta name="description" content="{{ $pageDescription }}">
    @endif

    @if($pageKeywords !== '')
        <meta name="keywords" content="{{ $pageKeywords }}">
    @endif

    @if(!empty($appSettings->sitemap_url))
        <link rel="sitemap" type="application/xml" href="{{ $appSettings->sitemap_url }}">
    @endif

    @if(!empty($appSettings->favicon_path))
        <link rel="icon" href="{{ asset('storage/'.$appSettings->favicon_path) }}">
        <link rel="apple-touch-icon" href="{{ asset('storage/'.$appSettings->favicon_path) }}">
    @endif

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

    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $pageTitle }}">
    @if($pageDescription !== '')
        <meta name="twitter:description" content="{{ $pageDescription }}">
    @endif
    @if($ogImage)
        <meta name="twitter:image" content="{{ $ogImage }}">
    @endif

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

    @routes
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.tiny.cloud/1/ggl24otsg6ii4amtaziztgfu6y1fv23npxoqemawhqdwvmnd/tinymce/8/tinymce.min.js" referrerpolicy="origin" crossorigin="anonymous"></script>
    @inertiaHead
</head>
<body class="antialiased overflow-x-hidden">
    <a href="#main-content" class="sr-only focus:not-sr-only focus:absolute focus:top-3 focus:left-3 bg-white text-black px-3 py-2 rounded">
        Aller au contenu principal
    </a>

    @inertia
</body>
</html>
