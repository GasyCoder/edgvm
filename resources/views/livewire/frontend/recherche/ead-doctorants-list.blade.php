<div>
    @if($this->totalTheses > 0)
    <div class="bg-white rounded-2xl shadow-xl p-8">
        <!-- Header avec compteur et recherche -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
            <h2 class="text-2xl font-bold text-gray-900 flex items-center gap-2">
                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                </svg>
                Doctorants en cours
                <span class="text-lg font-normal text-gray-500">({{ $this->theses->count() }})</span>
            </h2>
            
            <!-- Barre de recherche (visible seulement si > 6 doctorants) -->
            @if($this->totalTheses > 6)
            <div class="relative w-full sm:w-64">
                <input 
                    type="text" 
                    wire:model.live.debounce.300ms="searchQuery"
                    placeholder="Rechercher un doctorant..."
                    class="w-full pl-10 pr-4 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                >
                <svg class="absolute left-3 top-2.5 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
            </div>
            @endif
        </div>
        
        <!-- Liste des doctorants -->
        <div class="space-y-3">
            @forelse($this->theses as $these)
            <a href="{{ route('doctorants.show', $these->doctorant) }}"
               class="group flex items-center gap-4 p-4 bg-gradient-to-r from-gray-50 to-white border border-gray-100 rounded-xl hover:border-blue-300 hover:shadow-lg transition-all duration-200">
                
                <!-- Avatar avec initiales -->
                <div class="relative w-11 h-11 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-full flex items-center justify-center flex-shrink-0 group-hover:scale-110 transition-transform">
                    <span class="text-white font-bold text-sm">
                        {{ strtoupper(substr($these->doctorant->user->name ?? 'NN', 0, 1)) }}{{ strtoupper(substr(explode(' ', $these->doctorant->user->name ?? ' ')[1] ?? '', 0, 1)) }}
                    </span>
                    <div class="absolute -bottom-1 -right-1 w-4 h-4 bg-green-500 border-2 border-white rounded-full"></div>
                </div>
                
                <div class="flex-1 min-w-0">
                    <div class="flex items-start justify-between gap-2">
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center gap-2">
                                <p class="font-semibold text-gray-900 text-sm group-hover:text-blue-600 transition-colors">
                                    {{ $these->doctorant->user->name ?? 'Non renseigné' }}
                                </p>
                                <svg class="w-4 h-4 text-gray-400 group-hover:text-blue-600 group-hover:translate-x-1 transition-all" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                </svg>
                            </div>
                            @if($these->doctorant->matricule)
                            <p class="text-xs text-gray-500 mt-0.5">Matricule: {{ $these->doctorant->matricule }}</p>
                            @endif
                            <p class="text-xs text-gray-600 line-clamp-1 mt-1">{{ $these->sujet_these }}</p>
                        </div>
                        
                        <!-- Badge spécialité -->
                        @if($these->specialite)
                        <span class="hidden sm:inline-flex px-2 py-1 bg-blue-50 text-blue-700 text-xs font-medium rounded-md whitespace-nowrap">
                            {{ $these->specialite->nom }}
                        </span>
                        @endif
                    </div>
                    
                    <!-- Dates -->
                    <div class="flex items-center gap-3 mt-2 text-xs text-gray-500">
                        @if($these->date_debut)
                        <span class="flex items-center gap-1">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            Début: {{ $these->date_debut->format('Y') }}
                        </span>
                        @endif
                        @if($these->date_prevue_fin)
                        <span class="flex items-center gap-1">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            Fin prévue: {{ $these->date_prevue_fin->format('Y') }}
                        </span>
                        @endif
                    </div>
                </div>
            </a>
            @empty
            <!-- Message si aucun résultat -->
            <div class="text-center py-8">
                <svg class="w-16 h-16 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                <p class="text-gray-500 text-sm">Aucun doctorant trouvé</p>
            </div>
            @endforelse
        </div>
        
        <!-- Bouton "Voir plus/moins" -->
        @if($this->totalTheses > $limit && !$searchQuery)
        <div class="mt-6 text-center">
            <button 
                wire:click="toggleShowAll"
                class="inline-flex items-center gap-2 px-6 py-2.5 bg-gradient-to-r from-blue-50 to-indigo-50 hover:from-blue-100 hover:to-indigo-100 text-blue-700 font-semibold rounded-lg transition-all duration-200 border border-blue-200"
            >
                <span>{{ $showAll ? 'Voir moins' : 'Voir tous les doctorants (' . $this->totalTheses . ')' }}</span>
                <svg class="w-4 h-4 transition-transform duration-200 {{ $showAll ? 'rotate-180' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                </svg>
            </button>
        </div>
        @endif
    </div>
    @endif
</div>