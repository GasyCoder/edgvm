<div>
    <!-- Hero Section -->
    <section class="relative gradient-primary pt-24 md:pt-28 pb-16 md:pb-20 overflow-hidden">
        <div class="absolute inset-0 opacity-10">
            <div class="absolute top-10 right-10 w-96 h-96 bg-white rounded-full blur-3xl"></div>
        </div>
        
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            
            <!-- Breadcrumb -->
            <nav class="mb-6">
                <ol class="flex items-center gap-2 text-xs text-white/70">
                    <li><a href="{{ route('home') }}" class="hover:text-white transition">Accueil</a></li>
                    <li>›</li>
                    <li><a href="{{ route('ead.index') }}" class="hover:text-white transition">EAD</a></li>
                    <li>›</li>
                    <li class="text-white font-semibold">{{ $ead->nom }}</li>
                </ol>
            </nav>
            
            <!-- Badge Domaine -->
            @if($ead->domaine)
            <div class="mb-4">
                <span class="inline-block px-4 py-2 bg-white/20 backdrop-blur-lg text-white text-sm font-semibold rounded-full border border-white/30">
                    {{ $ead->domaine }}
                </span>
            </div>
            @endif
            
            <!-- Titre -->
            <h1 class="text-3xl md:text-4xl font-bold text-white mb-6">
                {{ $ead->nom }}
            </h1>
            
            <!-- Stats -->
            <div class="flex flex-wrap gap-4 text-white/90 text-sm">
                <div class="flex items-center gap-2 bg-white/10 backdrop-blur-lg rounded-full px-4 py-2 border border-white/20">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                    </svg>
                    <span>{{ $ead->specialites->count() }} spécialités</span>
                </div>
                
                <div class="flex items-center gap-2 bg-white/10 backdrop-blur-lg rounded-full px-4 py-2 border border-white/20">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
                    <span>{{ $ead->doctorats_count }} doctorants actifs</span>
                </div>
                
                <div class="flex items-center gap-2 bg-white/10 backdrop-blur-lg rounded-full px-4 py-2 border border-white/20">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span>{{ $ead->theses_soutenues_count }} thèses soutenues</span>
                </div>
            </div>
        </div>
    </section>

    <!-- Contenu principal -->
    <section class="py-12 bg-gradient-to-b from-gray-50 to-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                
                <!-- Contenu principal -->
                <div class="lg:col-span-2 space-y-8">
                    
                    <!-- Présentation -->
                    <div class="bg-white rounded-2xl shadow-xl p-8">
                        <h2 class="text-2xl font-bold text-gray-900 mb-4 flex items-center gap-2">
                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            Présentation
                        </h2>
                        <div class="prose prose-gray max-w-none">
                            <p class="text-gray-700 leading-relaxed">{{ $ead->description }}</p>
                        </div>
                    </div>
                    
                    <!-- Spécialités -->
                    @if($ead->specialites->isNotEmpty())
                    <div class="bg-white rounded-2xl shadow-xl p-8">
                        <h2 class="text-2xl font-bold text-gray-900 mb-6 flex items-center gap-2">
                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                            </svg>
                            Spécialités de recherche
                        </h2>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            @foreach($ead->specialites as $specialite)
                            <a href="{{ route('programmes.show', $specialite) }}" 
                               class="group p-5 bg-gradient-to-br from-gray-50 to-white border-2 border-gray-100 rounded-xl hover:border-ed-primary hover:shadow-lg transition-all">
                                <div class="flex items-start gap-3">
                                    <div class="w-10 h-10 bg-ed-primary/10 rounded-lg flex items-center justify-center flex-shrink-0 group-hover:bg-ed-primary group-hover:scale-110 transition-all">
                                        <svg class="w-5 h-5 text-ed-primary group-hover:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                        </svg>
                                    </div>
                                    <div class="flex-1">
                                        <h3 class="font-bold text-gray-900 group-hover:text-ed-primary transition-colors mb-1">
                                            {{ $specialite->nom }}
                                        </h3>
                                        <p class="text-xs text-gray-600 line-clamp-2">
                                            {{ $specialite->description }}
                                        </p>
                                        <div class="flex items-center gap-3 mt-2 text-xs text-gray-500">
                                            <span>{{ $specialite->theses_en_cours }} en cours</span>
                                            <span>•</span>
                                            <span>{{ $specialite->theses_soutenues }} soutenues</span>
                                        </div>
                                    </div>
                                </div>
                            </a>
                            @endforeach
                        </div>
                    </div>
                    @endif
                    
                    @include('livewire.frontend.recherche._doctorants')
                </div>
                
                <!-- Sidebar -->
                <aside class="lg:col-span-1">
                    <div class="lg:sticky lg:top-24 space-y-6">
                        
                        <!-- Responsable -->
                        @if($ead->responsable)
                        <div class="bg-white rounded-2xl shadow-xl p-6">
                            <h3 class="text-lg font-bold text-gray-900 mb-4">Responsable</h3>
                            <div class="flex items-start gap-4">
                                <div class="w-16 h-16 bg-gradient-to-br from-blue-500 to-blue-600 rounded-full flex items-center justify-center flex-shrink-0">
                                    <svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                                <div class="flex-1">
                                    <p class="font-bold text-gray-900">
                                        {{ $ead->responsable->name }}
                                    </p>
                                    @if($ead->responsable->grade)
                                    <p class="text-sm text-gray-600">{{ $ead->responsable->grade }}</p>
                                    @endif
                                    @if($ead->responsable->email)
                                    <a href="mailto:{{ $ead->responsable->email }}" 
                                    class="text-sm text-blue-600 hover:text-blue-800 break-all">
                                        {{ $ead->responsable->email }}
                                    </a>
                                    @endif
                                    @if($ead->responsable->phone)
                                    <p class="text-sm text-gray-600 mt-1">
                                        📞 {{ $ead->responsable->phone }}
                                    </p>
                                    @endif
                                </div>
                            </div>
                        </div>
                        @endif
                        
                        <!-- Statistiques -->
                        <div class="bg-gradient-to-br from-blue-600 to-indigo-600 rounded-2xl shadow-xl p-6 text-white">
                            <h3 class="text-lg font-bold mb-4">📊 Statistiques</h3>
                            <div class="space-y-4">
                                <div class="flex justify-between items-center pb-3 border-b border-white/20">
                                    <span class="text-sm text-white/80">Spécialités</span>
                                    <span class="text-2xl font-bold">{{ $ead->specialites->count() }}</span>
                                </div>
                                <div class="flex justify-between items-center pb-3 border-b border-white/20">
                                    <span class="text-sm text-white/80">Doctorants</span>
                                    <span class="text-2xl font-bold">{{ $ead->doctorats_count }}</span>
                                </div>
                                <div class="flex justify-between items-center">
                                    <span class="text-sm text-white/80">Thèses soutenues</span>
                                    <span class="text-2xl font-bold">{{ $ead->theses_soutenues_count }}</span>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Contact -->
                        <div class="bg-white rounded-2xl shadow-xl p-6 border-2 border-blue-100">
                            <h3 class="text-lg font-bold text-gray-900 mb-3">Contactez-nous</h3>
                            <p class="text-sm text-gray-600 mb-4">
                                Pour plus d'informations sur cette équipe d'accueil doctoral.
                            </p>
                            <a href="mailto:contact@edgvm.mg" 
                               class="block text-center px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition font-semibold text-sm">
                                Nous contacter
                            </a>
                        </div>
                        
                        <!-- Navigation -->
                        <div class="bg-white rounded-2xl shadow-xl p-6">
                            <a href="{{ route('ead.index') }}" 
                               class="flex items-center gap-2 text-gray-600 hover:text-ed-primary transition text-sm font-semibold">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                                </svg>
                                Toutes les EAD
                            </a>
                        </div>
                    </div>
                </aside>
            </div>
        </div>
    </section>
</div>
