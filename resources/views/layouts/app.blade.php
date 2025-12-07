<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Admin' }} - EDGVM Admin</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <!-- TinyMCE -->
    <script src="https://cdn.tiny.cloud/1/ggl24otsg6ii4amtaziztgfu6y1fv23npxoqemawhqdwvmnd/tinymce/8/tinymce.min.js" referrerpolicy="origin" crossorigin="anonymous"></script>
    @livewireStyles
</head>
<body class="bg-gray-100 font-poppins">
    
    <div class="flex h-screen">
        <!-- Sidebar -->
        <aside class="w-64 bg-gradient-to-b from-ed-primary to-ed-secondary text-white flex-shrink-0">
            <div class="p-6">
                <h1 class="text-2xl font-bold">EDGVM Admin</h1>
                <p class="text-white/70 text-sm">Panel d'administration</p>
            </div>
            
            <nav class="mt-6">
                <a href="{{ route('admin.dashboard') }}" 
                   class="flex items-center gap-3 px-6 py-3 hover:bg-white/10 transition {{ request()->routeIs('admin.dashboard') ? 'bg-white/20 border-r-4 border-ed-yellow' : '' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                    </svg>
                    Dashboard
                </a>
                
                <a href="{{ route('admin.categories.index') }}" 
                   class="flex items-center gap-3 px-6 py-3 hover:bg-white/10 transition {{ request()->routeIs('admin.categories.*') ? 'bg-white/20 border-r-4 border-ed-yellow' : '' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                    </svg>
                    Catégories
                </a>

                <a href="{{ route('admin.tags.index') }}" 
                   class="flex items-center gap-3 px-6 py-3 hover:bg-white/10 transition {{ request()->routeIs('admin.tags.*') ? 'bg-white/20 border-r-4 border-ed-yellow' : '' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14"/>
                    </svg>
                    Tags
                </a>
                
                <a href="{{ route('admin.actualites.index') }}" 
                   class="flex items-center gap-3 px-6 py-3 hover:bg-white/10 transition {{ request()->routeIs('admin.actualites.*') ? 'bg-white/20 border-r-4 border-ed-yellow' : '' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/>
                    </svg>
                    Actualités
                </a>
                
                <a href="{{ route('admin.newsletter.subscribers') }}" 
                   class="flex items-center gap-3 px-6 py-3 hover:bg-white/10 transition {{ request()->routeIs('admin.newsletter.*') ? 'bg-white/20 border-r-4 border-ed-yellow' : '' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                    Newsletter
                </a>

                <!-- EAD - CORRIGÉ -->
                <a href="{{ route('admin.ead.index') }}" 
                   class="flex items-center gap-3 px-6 py-3 hover:bg-white/10 transition {{ request()->routeIs('admin.ead*') ? 'bg-white/20 border-r-4 border-ed-yellow' : '' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg>
                    EAD
                </a>

                <!-- Spécialités - CORRIGÉ -->
                <a href="{{ route('admin.specialites.index') }}" 
                   class="flex items-center gap-3 px-6 py-3 hover:bg-white/10 transition {{ request()->routeIs('admin.specialites*') ? 'bg-white/20 border-r-4 border-ed-yellow' : '' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                    </svg>
                    Spécialités
                </a>
                
                <a href="{{ route('admin.doctorants.index') }}" 
                class="flex items-center gap-3 px-6 py-3 hover:bg-white/10 transition {{ request()->routeIs('admin.doctorants*') ? 'bg-white/20 border-r-4 border-ed-yellow' : '' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
                    Doctorants
                </a>
                <a href="{{ route('admin.encadrants.index') }}" 
                class="flex items-center gap-3 px-6 py-3 hover:bg-white/10 transition {{ request()->routeIs('admin.encadrants*') ? 'bg-white/20 border-r-4 border-ed-yellow' : '' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                    Encadrants
                </a>
                <a href="{{ route('admin.theses.index') }}" 
                class="flex items-center gap-3 px-6 py-3 hover:bg-white/10 transition {{ request()->routeIs('admin.theses*') ? 'bg-white/20 border-r-4 border-ed-yellow' : '' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                    </svg>
                    Thèses
                </a>
                <a href="{{ route('admin.sliders.index') }}" 
                   class="flex items-center gap-3 px-6 py-3 hover:bg-white/10 transition {{ request()->routeIs('admin.sliders.*') || request()->routeIs('admin.slides.*') ? 'bg-white/20 border-r-4 border-ed-yellow' : '' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    Sliders & Slides
                </a>
                
                <a href="{{ route('admin.media.index') }}" 
                   class="flex items-center gap-3 px-6 py-3 hover:bg-white/10 transition {{ request()->routeIs('admin.media.*') ? 'bg-white/20 border-r-4 border-ed-yellow' : '' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01"/>
                    </svg>
                    Médiathèque
                </a>
                
                <div class="border-t border-white/10 my-4"></div>
                
                <a href="{{ route('home') }}" target="_blank"
                   class="flex items-center gap-3 px-6 py-3 hover:bg-white/10 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                    </svg>
                    Voir le site
                </a>
                
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="flex items-center gap-3 px-6 py-3 hover:bg-white/10 transition w-full text-left">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                        </svg>
                        Déconnexion
                    </button>
                </form>
            </nav>
        </aside>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- Top Bar - CORRIGÉ -->
            <header class="bg-white shadow-sm">
                <div class="px-6 py-4">
                    @if(isset($header))
                        {{ $header }}
                    @else
                        <div class="flex justify-between items-center">
                            <div>
                                <h2 class="text-2xl font-bold text-gray-900">{{ $title ?? 'Dashboard' }}</h2>
                                @isset($subtitle)
                                <p class="text-gray-600 text-sm">{{ $subtitle }}</p>
                                @endisset
                            </div>
                            <div class="flex items-center gap-4">
                                <span class="text-sm text-gray-600">{{ auth()->user()->name }}</span>
                                <div class="w-10 h-10 bg-ed-primary rounded-full flex items-center justify-center text-white font-bold">
                                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </header>

            <!-- Page Content -->
            <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-100 p-6">
                {{ $slot }}
            </main>
        </div>
    </div>

    @livewireScripts
    @stack('scripts')
</body>
</html>