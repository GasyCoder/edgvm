<div>
    {{-- Hero --}}
    <section class="relative gradient-primary pt-24 md:pt-28 pb-16 md:pb-20 overflow-hidden">
        <div class="absolute inset-0">
            <div class="absolute inset-0 bg-[url('/images/pattern.svg')] opacity-10"></div>
            <div class="absolute -top-10 right-0 w-72 h-72 bg-white/10 rounded-full blur-3xl"></div>
            <div class="absolute bottom-0 -left-16 w-80 h-80 bg-indigo-500/20 rounded-full blur-3xl"></div>
        </div>

        <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            {{-- Breadcrumb --}}
            <nav class="mb-6">
                <ol class="flex items-center gap-2 text-xs md:text-sm text-white/70">
                    <li>
                        <a href="{{ route('home') }}" class="hover:text-white transition">
                            Accueil
                        </a>
                    </li>
                    <li>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M9 5l7 7-7 7"/>
                        </svg>
                    </li>
                    <li class="text-white font-semibold">
                        Thèses
                    </li>
                </ol>
            </nav>

            <div class="flex flex-col md:flex-row md:items-end md:justify-between gap-6">
                <div>
                    <p class="text-sm uppercase tracking-[0.2em] text-teal-200 mb-2">
                        Répertoire scientifique
                    </p>
                    <h1 class="text-3xl md:text-4xl lg:text-5xl font-bold text-white leading-tight mb-3">
                        Thèses de l’École Doctorale
                    </h1>
                    <p class="text-sm md:text-base text-white/80 max-w-2xl">
                        Consultez les travaux de recherche des doctorants.
                    </p>
                </div>

                {{-- Résumé rapide --}}
                <div class="bg-white/10 border border-white/20 rounded-2xl px-4 py-3 md:px-5 md:py-4 backdrop-blur max-w-xs md:max-w-sm">
                    <p class="text-xs text-white/70 mb-1">
                        Thèses référencées
                    </p>
                    <p class="text-2xl font-semibold text-white">
                        {{ $theses->total() }} 
                        <span class="text-sm font-normal text-white/70">projets</span>
                    </p>
                </div>
            </div>
        </div>
    </section>

    {{-- Filtres + liste --}}
    <section class="py-10 md:py-14 bg-gradient-to-b from-slate-50 to-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
            {{-- Barre de recherche + vue --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4 md:p-5 flex flex-col gap-4 md:gap-5">
                <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                    {{-- Recherche --}}
                    <div class="w-full lg:max-w-xl">
                        <label class="block text-xs font-semibold text-gray-500 uppercase mb-1.5">
                            Rechercher une thèse
                        </label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M21 21l-4.35-4.35M11 18a7 7 0 100-14 7 7 0 000 14z"/>
                                </svg>
                            </span>
                            <input
                                type="text"
                                wire:model.live.debounce.400ms="search"
                                placeholder="Sujet, doctorant, mots-clés..."
                                class="w-full pl-9 pr-3 py-2.5 text-sm border border-gray-300 rounded-xl
                                       focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            >
                        </div>
                        <p class="mt-1 text-[11px] text-gray-400">
                            Exemple : « vulnérabilité climatique », « mangroves », « Boeny »...
                        </p>
                    </div>

                    {{-- Vue : grid / liste --}}
                    <div class="flex items-center justify-between lg:justify-end gap-3">
                        <span class="text-xs font-semibold text-gray-500 uppercase">
                            Affichage
                        </span>

                        <div class="inline-flex items-center rounded-xl border border-gray-200 bg-gray-50 p-0.5">
                            <button
                                type="button"
                                wire:click="setViewMode('grid')"
                                class="inline-flex items-center justify-center px-3 py-1.5 rounded-lg text-xs font-medium
                                       {{ $viewMode === 'grid' ? 'bg-white text-gray-900 shadow-sm' : 'text-gray-500 hover:text-gray-700' }}"
                                title="Vue grille"
                            >
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M4 6h7V4H4v2zm9 0h7V4h-7v2zM4 13h7v-2H4v2zm9 0h7v-2h-7v2zM4 20h7v-2H4v2zm9 0h7v-2h-7v2z"/>
                                </svg>
                                Grille
                            </button>

                            <button
                                type="button"
                                wire:click="setViewMode('list')"
                                class="inline-flex items-center justify-center px-3 py-1.5 rounded-lg text-xs font-medium
                                       {{ $viewMode === 'list' ? 'bg-white text-gray-900 shadow-sm' : 'text-gray-500 hover:text-gray-700' }}"
                                title="Vue liste"
                            >
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M4 6h16M4 12h16M4 18h16"/>
                                </svg>
                                Liste
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Contenu + sidebar --}}
            <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
                
                {{-- Colonne principale : liste des thèses --}}
                <div class="lg:col-span-3">
                    @if($theses->count())
                        {{-- Vue grille --}}
                        @if($viewMode === 'grid')
                            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
                                @foreach($theses as $these)
                                    <a href="{{ route('theses.show', $these) }}"
                                       class="group bg-white rounded-2xl border border-gray-100 shadow-sm hover:shadow-lg hover:-translate-y-0.5 transition-all duration-200 flex flex-col overflow-hidden">
                                        {{-- Bandeau supérieur --}}
                                        <div class="px-5 pt-4 flex items-start justify-between gap-3">
                                            <div class="flex-1">
                                                <div class="inline-flex items-center gap-2 mb-2">
                                                    @if($these->specialite)
                                                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full bg-indigo-50 text-[11px] text-indigo-700">
                                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                      d="M12 6v12m6-6H6"/>
                                                            </svg>
                                                            {{ $these->specialite->nom ?? $these->specialite->intitule ?? 'Spécialité' }}
                                                        </span>
                                                    @endif

                                                    @if($these->ead)
                                                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full bg-sky-50 text-[11px] text-sky-700 font-bold">
                                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                      d="M4 6h16M4 10h16M4 14h10M4 18h10"/>
                                                            </svg>
                                                            {{ Str::limit($these->ead->nom ?? 'EAD', 5) }}
                                                        </span>
                                                    @endif
                                                </div>

                                                <h2 class="text-sm font-semibold text-gray-900 line-clamp-3 group-hover:text-blue-700">
                                                    {{ $these->sujet_these }}
                                                </h2>
                                            </div>

                                            {{-- Statut --}}
                                            <div class="ml-2">
                                                <span class="inline-flex items-center px-2.5 py-1 rounded-full border text-[11px] font-medium {{ $these->statut_badge_classes }}">
                                                    {{ $these->statut_label }}
                                                </span>
                                            </div>
                                        </div>

                                        {{-- Corps --}}
                                        <div class="px-5 py-3 flex-1 flex flex-col gap-3">
                                            {{-- Doctorant --}}
                                            @if($these->doctorant)
                                                <div class="flex items-center gap-2 text-xs text-gray-600">
                                                    <div class="w-7 h-7 rounded-full bg-gray-100 flex items-center justify-center text-[11px] font-semibold text-gray-700">
                                                        {{ strtoupper(substr($these->doctorant->user->name ?? 'NN', 0, 1)) }}{{ strtoupper(substr(explode(' ', $these->doctorant->user->name ?? ' ')[1] ?? '', 0, 1)) }}
                                                    </div>
                                                    <div class="flex flex-col">
                                                        <span class="font-medium text-gray-800">
                                                            {{ $these->doctorant->user->name ?? '' }} 
                                                        </span>
                                                        <span class="text-[11px] text-gray-400">
                                                            Doctorant
                                                        </span>
                                                    </div>
                                                </div>
                                            @endif

                                            {{-- Résumé court --}}
                                            @if($these->resume_these)
                                                <p class="text-xs text-gray-600 line-clamp-3">
                                                    {{ Str::limit(strip_tags($these->resume_these), 220) }}
                                                </p>
                                            @else
                                                <p class="text-xs text-gray-400 italic">
                                                    Résumé non renseigné.
                                                </p>
                                            @endif
                                        </div>

                                        {{-- Bas de carte --}}
                                        <div class="px-5 pb-4 pt-2 mt-auto border-t border-gray-100 bg-gray-50/60 flex items-center justify-between text-[11px] text-gray-500">
                                            <div class="flex items-center gap-3">
                                                @if($these->date_debut)
                                                    <div class="flex items-center gap-1.5">
                                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                  d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7
                                                                     a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                                        </svg>
                                                        <span>Début {{ $these->date_debut->format('d/m/Y') }}</span>
                                                    </div>
                                                @endif

                                                @if($these->date_publication)
                                                    <div class="flex items-center gap-1.5">
                                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                  d="M12 8v4l3 3m6-3a9 9 0 11-18 0
                                                                     9 9 0 0118 0z"/>
                                                        </svg>
                                                        <span>Publié {{ $these->date_publication->format('d/m/Y') }}</span>
                                                    </div>
                                                @endif
                                            </div>

                                            <span class="inline-flex items-center gap-1 text-blue-600 font-medium">
                                                Voir la fiche
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                          d="M9 5l7 7-7 7"/>
                                                </svg>
                                            </span>
                                        </div>
                                    </a>
                                @endforeach
                            </div>
                        @else
                            {{-- Vue liste --}}
                            <div class="space-y-4">
                                @foreach($theses as $these)
                                    <a href="{{ route('theses.show', $these) }}"
                                       class="group bg-white rounded-2xl border border-gray-100 shadow-sm hover:shadow-md hover:-translate-y-0.5 transition-all duration-200 flex flex-col md:flex-row md:items-stretch overflow-hidden">
                                        
                                        {{-- Bandeau latéral gauche (statut + dates) --}}
                                        <div class="md:w-56 bg-gray-50/80 border-b md:border-b-0 md:border-r border-gray-100 p-4 flex flex-col justify-between gap-3">
                                            <div class="space-y-2">
                                                <span class="inline-flex items-center px-2.5 py-1 rounded-full border text-[11px] font-medium {{ $these->statut_badge_classes }}">
                                                    {{ $these->statut_label }}
                                                </span>

                                                @if($these->date_debut)
                                                    <div class="flex items-center gap-1.5 text-[11px] text-gray-600">
                                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                  d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7
                                                                     a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                                        </svg>
                                                        <span>Début {{ $these->date_debut->format('d/m/Y') }}</span>
                                                    </div>
                                                @endif

                                                @if($these->date_publication)
                                                    <div class="flex items-center gap-1.5 text-[11px] text-gray-600">
                                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                  d="M12 8v4l3 3m6-3a9 9 0 11-18 0
                                                                     9 9 0 0118 0z"/>
                                                        </svg>
                                                        <span> Soutenance {{ $these->date_publication->format('d/m/Y') }}</span>
                                                    </div>
                                                @endif
                                            </div>

                                            @if($these->specialite || $these->ead)
                                                <div class="space-y-1 text-[11px] text-gray-500">
                                                    @if($these->specialite)
                                                        <div class="flex items-center gap-1.5">
                                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                      d="M12 6v12m6-6H6"/>
                                                            </svg>
                                                            <span>{{ $these->specialite->nom ?? $these->specialite->intitule ?? 'Spécialité' }}</span>
                                                        </div>
                                                    @endif
                                                    @if($these->ead)
                                                        <div class="flex items-center gap-1.5">
                                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                      d="M4 6h16M4 10h16M4 14h10M4 18h10"/>
                                                            </svg>
                                                            <span>{{ $these->ead->sigle ?? $these->ead->nom ?? 'EAD' }}</span>
                                                        </div>
                                                    @endif
                                                </div>
                                            @endif
                                        </div>

                                        {{-- Corps principal --}}
                                        <div class="flex-1 p-5 flex flex-col gap-3">
                                            {{-- Sujet --}}
                                            <h2 class="text-sm md:text-base font-semibold text-gray-900 group-hover:text-blue-700 line-clamp-2">
                                                {{ $these->sujet_these }}
                                            </h2>

                                            {{-- Doctorant --}}
                                            @if($these->doctorant)
                                                <div class="flex items-center gap-2 text-xs text-gray-600">
                                                    <div class="w-8 h-8 rounded-full bg-gray-100 flex items-center justify-center text-[11px] font-semibold text-gray-700">
                                                        {{ strtoupper(substr($these->doctorant->prenom ?? '', 0, 1) . substr($these->doctorant->nom ?? '', 0, 1)) }}
                                                    </div>
                                                    <div class="flex flex-col">
                                                        <span class="font-medium text-gray-800">
                                                            {{ $these->doctorant->prenom ?? '' }} {{ $these->doctorant->nom ?? '' }}
                                                        </span>
                                                        <span class="text-[11px] text-gray-400">
                                                            Doctorant
                                                        </span>
                                                    </div>
                                                </div>
                                            @endif

                                            {{-- Résumé court --}}
                                            <div class="text-xs md:text-sm text-gray-600 line-clamp-3">
                                                @if($these->resume_these)
                                                    {{ Str::limit(strip_tags($these->resume_these), 260) }}
                                                @else
                                                    <span class="italic text-gray-400">
                                                        Résumé non renseigné.
                                                    </span>
                                                @endif
                                            </div>

                                            {{-- CTA --}}
                                            <div class="mt-2 flex items-center justify-between text-[11px] text-gray-500">
                                                <div class="flex items-center gap-3">
                                                    @if($these->date_debut)
                                                        <span>
                                                            Promo : {{ $these->date_debut->format('Y') }}
                                                        </span>
                                                    @endif
                                                </div>
                                                <span class="inline-flex items-center gap-1 text-blue-600 font-medium">
                                                    Voir la fiche détaillée
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                              d="M9 5l7 7-7 7"/>
                                                    </svg>
                                                </span>
                                            </div>
                                        </div>
                                    </a>
                                @endforeach
                            </div>
                        @endif

                        {{-- Pagination --}}
                        <div class="mt-8">
                            {{ $theses->links() }}
                        </div>
                    @else
                        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-10 text-center">
                            <div class="flex flex-col items-center gap-3">
                                <div class="w-12 h-12 rounded-full bg-gray-100 flex items-center justify-center">
                                    <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M9 13h6m-6-4h6m5 12H4a2 2 0 01-2-2V5
                                                 a2 2 0 012-2h7l2 2h7a2 2 0 012 2v10
                                                 a2 2 0 01-2 2z"/>
                                    </svg>
                                </div>
                                <p class="text-sm font-medium text-gray-800">
                                    Aucune thèse trouvée.
                                </p>
                                <p class="text-xs text-gray-500">
                                    Modifiez vos critères de recherche ou réessayez plus tard.
                                </p>
                            </div>
                        </div>
                    @endif
                </div>

                {{-- Sidebar : filtres statut --}}
                <aside class="lg:col-span-1 space-y-5">
                    {{-- Carte filtres statut --}}
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
                        <h3 class="text-sm font-semibold text-gray-900 mb-3 flex items-center gap-2">
                            <span class="w-1.5 h-5 bg-gradient-to-b from-blue-600 to-indigo-600 rounded-full"></span>
                            Filtrer par statut
                        </h3>

                        @php
                            $statutButtons = [
                                ''            => ['label' => 'Toutes les thèses', 'desc' => 'Sans filtre de statut'],
                                'preparation' => ['label' => 'En préparation',    'desc' => 'Sujet validé, début de travaux'],
                                'en_cours'    => ['label' => 'En cours',          'desc' => 'Travaux et rédaction en cours'],
                                'soutenue'    => ['label' => 'Soutenue',          'desc' => 'Thèse soutenue'],
                            ];
                        @endphp

                        <div class="space-y-2">
                            @foreach($statutButtons as $value => $data)
                                @php
                                    $isActive = $statut === $value;
                                @endphp
                                <button
                                    type="button"
                                    wire:click="setStatut('{{ $value }}')"
                                    class="w-full text-left px-3 py-2 rounded-xl border text-xs transition flex items-start gap-2
                                           {{ $isActive
                                                ? 'bg-blue-50 border-blue-200 text-blue-800 shadow-sm'
                                                : 'bg-white border-gray-200 text-gray-700 hover:bg-gray-50' }}"
                                >
                                    <span class="mt-1">
                                        @if($value === 'preparation')
                                            <span class="w-2 h-2 rounded-full bg-amber-500 inline-block"></span>
                                        @elseif($value === 'en_cours')
                                            <span class="w-2 h-2 rounded-full bg-blue-500 inline-block"></span>
                                        @elseif($value === 'soutenue')
                                            <span class="w-2 h-2 rounded-full bg-emerald-500 inline-block"></span>
                                        @else
                                            <span class="w-2 h-2 rounded-full bg-gray-400 inline-block"></span>
                                        @endif
                                    </span>
                                    <span class="flex-1">
                                        <span class="block font-semibold">
                                            {{ $data['label'] }}
                                        </span>
                                        @if($data['desc'])
                                            <span class="block text-[11px] text-gray-500">
                                                {{ $data['desc'] }}
                                            </span>
                                        @endif
                                    </span>
                                </button>
                            @endforeach
                        </div>
                    </div>

                    {{-- Petit encart d’info --}}
                    <div class="bg-blue-50 border border-blue-100 rounded-2xl p-4 text-xs text-blue-900">
                        <p class="font-semibold mb-1">
                            Astuce
                        </p>
                        <p class="leading-relaxed">
                            Combinez la recherche par mots-clés avec le filtre de statut pour retrouver rapidement
                            une thèse précise (par auteur, année ou thématique).
                        </p>
                    </div>
                </aside>
            </div>
        </div>
    </section>
</div>
