<div>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div class="flex items-center gap-4">
                <a href="{{ route('admin.encadrants.index') }}" 
                   class="p-2 hover:bg-gray-100 rounded-lg transition">
                    <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                </a>
                <h2 class="text-2xl font-bold text-gray-800">Profil de l'encadrant</h2>
            </div>
            
            <a href="{{ route('admin.encadrants.edit', $encadrant) }}" 
               class="px-6 py-3 bg-ed-primary text-white rounded-lg hover:bg-ed-secondary transition font-bold shadow-lg flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                </svg>
                Modifier
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <!-- Messages flash -->
            @if (session()->has('success'))
            <div class="mb-6 p-4 bg-green-100 border-l-4 border-green-500 text-green-700 rounded">
                {{ session('success') }}
            </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                
                <!-- Colonne gauche : Informations principales -->
                <div class="lg:col-span-1 space-y-6">
                    
                    <!-- Carte profil -->
                    <div class="bg-white rounded-xl shadow-lg p-6">
                        <div class="flex flex-col items-center text-center">
                            <!-- Avatar -->
                            <div class="w-24 h-24 bg-gradient-to-br from-blue-500 to-purple-600 rounded-full flex items-center justify-center mb-4">
                                <svg class="w-12 h-12 text-white" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                            
                            <!-- Nom -->
                            <h3 class="text-xl font-bold text-gray-900 mb-1">
                                {{ $encadrant->user?->name ?? 'Pas de compte' }}
                            </h3>
                            
                            <!-- Grade -->
                            <span class="inline-block px-4 py-1.5 bg-purple-100 text-purple-700 rounded-lg text-sm font-semibold mb-4">
                                {{ $encadrant->grade ?? 'Non défini' }}
                            </span>
                            
                            <!-- Statut compte -->
                            @if($encadrant->user)
                            <div class="w-full p-3 bg-green-50 rounded-lg border border-green-200">
                                <div class="flex items-center justify-center gap-2 text-green-700">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                    </svg>
                                    <span class="font-semibold text-sm">Compte actif</span>
                                </div>
                            </div>
                            @else
                            <div class="w-full p-3 bg-gray-50 rounded-lg border border-gray-200">
                                <div class="flex items-center justify-center gap-2 text-gray-600">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                                    </svg>
                                    <span class="font-semibold text-sm">Pas de compte</span>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>

                    <!-- Coordonnées -->
                    <div class="bg-white rounded-xl shadow-lg p-6">
                        <h4 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                            <svg class="w-5 h-5 text-ed-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                            Coordonnées
                        </h4>
                        
                        <div class="space-y-3">
                            <!-- Email -->
                            <div class="flex items-start gap-3">
                                <svg class="w-5 h-5 text-gray-400 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                </svg>
                                <div class="flex-1">
                                    <p class="text-xs font-semibold text-gray-500 uppercase">Email</p>
                                    <p class="text-sm text-gray-900">{{ $encadrant->user?->email ?? 'N/A' }}</p>
                                </div>
                            </div>

                            <!-- Téléphone -->
                            @if($encadrant->phone)
                            <div class="flex items-start gap-3">
                                <svg class="w-5 h-5 text-gray-400 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                                </svg>
                                <div class="flex-1">
                                    <p class="text-xs font-semibold text-gray-500 uppercase">Téléphone</p>
                                    <p class="text-sm text-gray-900">{{ $encadrant->phone }}</p>
                                </div>
                            </div>
                            @endif

                            <!-- Bureau -->
                            @if($encadrant->bureau)
                            <div class="flex items-start gap-3">
                                <svg class="w-5 h-5 text-gray-400 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                </svg>
                                <div class="flex-1">
                                    <p class="text-xs font-semibold text-gray-500 uppercase">Bureau</p>
                                    <p class="text-sm text-gray-900">{{ $encadrant->bureau }}</p>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>

                    <!-- Spécialité -->
                    @if($encadrant->specialite)
                    <div class="bg-white rounded-xl shadow-lg p-6">
                        <h4 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                            <svg class="w-5 h-5 text-ed-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                            </svg>
                            Spécialité
                        </h4>
                        <p class="text-sm text-gray-700">{{ $encadrant->specialite }}</p>
                    </div>
                    @endif
                </div>

                <!-- Colonne droite : Thèses encadrées -->
                <div class="lg:col-span-2">
                    <div class="bg-white rounded-xl shadow-lg p-6">
                        <div class="flex items-center justify-between mb-6">
                            <h4 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                                <svg class="w-5 h-5 text-ed-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                                </svg>
                                Thèses encadrées
                                <span class="px-3 py-1 bg-teal-100 text-teal-700 rounded-lg text-sm font-bold">
                                    {{ $encadrant->theses->count() }}
                                </span>
                            </h4>
                        </div>

                        @forelse($encadrant->theses as $these)
                        <div class="mb-4 p-4 bg-gray-50 rounded-lg border border-gray-200 hover:border-ed-primary hover:bg-white transition">
                            <div class="flex items-start justify-between gap-4">
                                <div class="flex-1">
                                    <!-- Doctorant -->
                                    <div class="flex items-center gap-3 mb-3">
                                        <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center">
                                            <svg class="w-5 h-5 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z"/>
                                            </svg>
                                        </div>
                                        <div>
                                            <p class="font-semibold text-gray-900">{{ $these->doctorant->user?->name ?? 'Pas de compte' }}</p>
                                            <p class="text-xs text-gray-500">{{ $these->doctorant->matricule }}</p>
                                        </div>
                                    </div>

                                    <!-- Sujet -->
                                    <h5 class="font-semibold text-gray-900 mb-2">{{ $these->doctorant->sujet_these ?? 'Sujet non défini' }}</h5>

                                    <!-- Métadonnées -->
                                    <div class="flex flex-wrap gap-3 text-sm">
                                        <!-- Rôle -->
                                        <span class="inline-flex items-center gap-1 px-3 py-1 bg-indigo-100 text-indigo-700 rounded-lg font-semibold">
                                            @php
                                                $role = $these->encadrants()->where('encadrant_id', $encadrant->id)->first()?->pivot->role ?? 'Encadrant';
                                            @endphp
                                            {{ ucfirst($role) }}
                                        </span>

                                        <!-- EAD -->
                                        @if($these->ead)
                                        <span class="inline-flex items-center gap-1 px-3 py-1 bg-gray-100 text-gray-700 rounded-lg">
                                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M10.394 2.08a1 1 0 00-.788 0l-7 3a1 1 0 000 1.84L5.25 8.051a.999.999 0 01.356-.257l4-1.714a1 1 0 11.788 1.838L7.667 9.088l1.94.831a1 1 0 00.787 0l7-3a1 1 0 000-1.838l-7-3zM3.31 9.397L5 10.12v4.102a8.969 8.969 0 00-1.05-.174 1 1 0 01-.89-.89 11.115 11.115 0 01.25-3.762zM9.3 16.573A9.026 9.026 0 007 14.935v-3.957l1.818.78a3 3 0 002.364 0l5.508-2.361a11.026 11.026 0 01.25 3.762 1 1 0 01-.89.89 8.968 8.968 0 00-5.35 2.524 1 1 0 01-1.4 0zM6 18a1 1 0 001-1v-2.065a8.935 8.935 0 00-2-.712V17a1 1 0 001 1z"/>
                                            </svg>
                                            {{ $these->ead->nom }}
                                        </span>
                                        @endif

                                        <!-- Statut -->
                                        <span class="inline-flex items-center gap-1 px-3 py-1 
                                            {{ $these->statut === 'en_cours' ? 'bg-green-100 text-green-700' : '' }}
                                            {{ $these->statut === 'soutenue' ? 'bg-blue-100 text-blue-700' : '' }}
                                            {{ $these->statut === 'abandonnee' ? 'bg-red-100 text-red-700' : '' }}
                                            rounded-lg font-semibold">
                                            {{ ucfirst(str_replace('_', ' ', $these->statut ?? 'non défini')) }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @empty
                        <div class="text-center py-12">
                            <svg class="mx-auto h-12 w-12 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                            </svg>
                            <p class="text-lg font-medium text-gray-500">Aucune thèse encadrée</p>
                            <p class="text-sm text-gray-400 mt-1">Cet encadrant n'est actuellement assigné à aucune thèse</p>
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>