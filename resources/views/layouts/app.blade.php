<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Admin' }} - EDGVM Admin</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
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
            <!-- Top Bar -->
            <header class="bg-white shadow-sm">
                <div class="px-6 py-4 flex justify-between items-center">
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