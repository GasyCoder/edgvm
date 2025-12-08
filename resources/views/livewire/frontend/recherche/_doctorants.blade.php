<!-- Doctorants -->
@if($ead->theses->isNotEmpty())
<div class="bg-white rounded-2xl shadow-xl p-8" x-data="{ 
    showAll: false, 
    searchQuery: '',
    theses: @js($ead->theses->load('doctorant.user', 'specialite')->map(function($these) {
        return [
            'id' => $these->id,
            'sujet_these' => $these->sujet_these,
            'date_debut' => $these->date_debut,
            'date_prevue_fin' => $these->date_prevue_fin,
            'url' => route('doctorants.show', $these->doctorant),
            'doctorant' => [
                'id' => $these->doctorant->id,
                'name' => $these->doctorant->user->name ?? 'Non renseigné',
                'matricule' => $these->doctorant->matricule,
            ],
            'specialite' => $these->specialite ? [
                'nom' => $these->specialite->nom
            ] : null
        ];
    })),
    get filteredTheses() {
        if (!this.searchQuery) return this.theses;
        return this.theses.filter(these => {
            const name = these.doctorant.name.toLowerCase();
            const titre = (these.sujet_these || '').toLowerCase();
            const query = this.searchQuery.toLowerCase();
            return name.includes(query) || titre.includes(query);
        });
    },
    get displayedTheses() {
        return this.showAll ? this.filteredTheses : this.filteredTheses.slice(0, 6);
    },
    get hasMore() {
        return this.filteredTheses.length > 6;
    }
}">
    <!-- Header avec compteur et recherche -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
        <h2 class="text-2xl font-bold text-gray-900 flex items-center gap-2">
            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
            </svg>
            Doctorants en cours
            <span class="text-lg font-normal text-gray-500">(<span x-text="filteredTheses.length"></span>)</span>
        </h2>
        
        <!-- Barre de recherche (visible seulement si > 6 doctorants) -->
        @if($ead->theses->count() > 6)
        <div class="relative w-full sm:w-64">
            <input 
                type="text" 
                x-model="searchQuery"
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
        <template x-for="these in displayedTheses" :key="these.id">
            <a :href="these.url"
               class="group flex items-center gap-4 p-4 bg-gradient-to-r from-gray-50 to-white border border-gray-100 rounded-xl hover:border-blue-300 hover:shadow-lg transition-all duration-200 cursor-pointer">
                
                <!-- Avatar avec initiales -->
                <div class="relative w-11 h-11 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-full flex items-center justify-center flex-shrink-0 group-hover:scale-110 transition-transform">
                    <span class="text-white font-bold text-sm" x-text="these.doctorant.name.split(' ').map(n => n[0]).join('').substring(0, 2)"></span>
                    <div class="absolute -bottom-1 -right-1 w-4 h-4 bg-green-500 border-2 border-white rounded-full"></div>
                </div>
                
                <div class="flex-1 min-w-0">
                    <div class="flex items-start justify-between gap-2">
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center gap-2">
                                <p class="font-semibold text-gray-900 text-sm group-hover:text-blue-600 transition-colors" x-text="these.doctorant.name"></p>
                                <svg class="w-4 h-4 text-gray-400 group-hover:text-blue-600 group-hover:translate-x-1 transition-all" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                </svg>
                            </div>
                            <p class="text-xs text-gray-500 mt-0.5" x-text="`Matricule: ${these.doctorant.matricule || 'N/A'}`"></p>
                            <p class="text-xs text-gray-600 line-clamp-1 mt-1" x-text="these.sujet_these"></p>
                        </div>
                        
                        <!-- Badge spécialité (optionnel) -->
                        <template x-if="these.specialite">
                            <span class="hidden sm:inline-flex px-2 py-1 bg-blue-50 text-blue-700 text-xs font-medium rounded-md whitespace-nowrap" x-text="these.specialite.nom"></span>
                        </template>
                    </div>
                    
                    <!-- Année de début -->
                    <div class="flex items-center gap-3 mt-2 text-xs text-gray-500">
                        <span class="flex items-center gap-1">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            Début: <span x-text="new Date(these.date_debut).getFullYear()"></span>
                        </span>
                        <template x-if="these.date_prevue_fin">
                            <span class="flex items-center gap-1">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                Fin prévue: <span x-text="new Date(these.date_prevue_fin).getFullYear()"></span>
                            </span>
                        </template>
                    </div>
                </div>
            </a>
        </template>
    </div>
    
    <!-- Message si aucun résultat -->
    <div x-show="filteredTheses.length === 0" class="text-center py-8" style="display: none;">
        <svg class="w-16 h-16 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
        </svg>
        <p class="text-gray-500 text-sm">Aucun doctorant trouvé</p>
    </div>
    
    <!-- Bouton "Voir plus/moins" -->
    <div x-show="hasMore && !searchQuery" class="mt-6 text-center" style="display: none;">
        <button 
            @click="showAll = !showAll"
            class="inline-flex items-center gap-2 px-6 py-2.5 bg-gradient-to-r from-blue-50 to-indigo-50 hover:from-blue-100 hover:to-indigo-100 text-blue-700 font-semibold rounded-lg transition-all duration-200 border border-blue-200"
        >
            <span x-text="showAll ? 'Voir moins' : `Voir tous les doctorants (${filteredTheses.length})`"></span>
            <svg class="w-4 h-4 transition-transform duration-200" :class="showAll && 'rotate-180'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
            </svg>
        </button>
    </div>
</div>
@endif