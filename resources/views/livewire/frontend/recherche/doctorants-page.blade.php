<div>
    {{-- HERO / EN-TÊTE --}}
    <section class="relative gradient-primary pt-24 md:pt-28 pb-16 md:pb-20 overflow-hidden">
        {{-- Décorations floues --}}
        <div class="absolute inset-0 opacity-20 pointer-events-none">
            <div class="absolute -top-10 -right-10 w-72 h-72 bg-white/40 rounded-full blur-3xl"></div>
            <div class="absolute bottom-0 -left-16 w-80 h-80 bg-ed-yellow/40 rounded-full blur-3xl"></div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            {{-- Breadcrumb --}}
            <nav class="mb-6">
                <ol class="flex items-center gap-2 text-xs text-white/70">
                    <li>
                        <a href="{{ route('home') }}" class="hover:text-white transition">
                            Accueil
                        </a>
                    </li>
                    <li>›</li>
                    <li class="text-white font-semibold">Doctorants</li>
                </ol>
            </nav>

            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-8">
                <div class="space-y-3">
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-black/10 border border-white/20">
                        <span class="w-2 h-2 rounded-full bg-ed-yellow animate-pulse"></span>
                        <span class="text-xs uppercase tracking-[0.18em] text-white/80 font-semibold">
                            École Doctorale EDGVM
                        </span>
                    </div>

                    <h1 class="text-3xl md:text-4xl font-extrabold text-white text-shadow">
                        Nos doctorants
                    </h1>

                    <p class="text-sm md:text-base text-white/90">
                        Affichage de
                        <span class="font-semibold">
                            {{ $this->doctorants->firstItem() ?? 0 }} – {{ $this->doctorants->lastItem() ?? 0 }}
                        </span>
                        sur
                        <span class="font-semibold">
                            {{ $this->doctorants->total() }}
                        </span>
                        doctorant(s) ayant une thèse.
                    </p>
                </div>

                {{-- Statistiques rapides --}}
                <div class="flex gap-4 md:gap-5">
                    <div class="glass rounded-2xl px-5 py-4 border border-white/20 min-w-[130px]">
                        <p class="text-xs text-white/80 uppercase tracking-wide flex items-center gap-1">
                            <svg class="w-4 h-4 text-ed-yellow" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M5 13l4 4L19 7"/>
                            </svg>
                            Total inscrits
                        </p>
                        <p class="text-2xl font-bold text-white mt-1">
                            {{ $this->totalDoctorants }}
                        </p>
                    </div>

                    <div class="glass rounded-2xl px-5 py-4 border border-white/20 min-w-[130px]">
                        <p class="text-xs text-white/80 uppercase tracking-wide flex items-center gap-1">
                            <span class="w-2 h-2 bg-emerald-400 rounded-full"></span>
                            Actifs
                        </p>
                        <p class="text-2xl font-bold text-white mt-1">
                            {{ $this->doctorantsActifs }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- BARRE DE RECHERCHE + FILTRES --}}
    <section class="bg-transparent -mt-6 md:-mt-8 mb-4 relative z-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white/95 backdrop-blur-lg shadow-lg rounded-2xl border border-gray-100 px-4 py-5 md:px-6 md:py-6">
                <div class="flex flex-col gap-4">
                    {{-- Barre de recherche pleine largeur --}}
                    <div>
                        <label class="text-xs md:text-sm font-semibold text-gray-600 mb-1.5 block">
                            Rechercher un doctorant
                        </label>
                        <div class="relative">
                            <input
                                type="text"
                                wire:model.live.debounce.300ms="search"
                                placeholder="Nom, prénom ou matricule…"
                                class="w-full pl-11 pr-4 py-3 text-sm md:text-base rounded-xl border border-gray-200
                                    bg-gray-50 focus:bg-white focus:ring-2 focus:ring-ed-primary/60
                                    focus:border-transparent placeholder:text-gray-400 transition"
                            >
                            <svg class="absolute left-3 top-2.5 md:top-2.5 w-5 h-5 md:w-6 md:h-6 text-gray-400"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-5.2-5.2M10.5 18a7.5 7.5 0 110-15 7.5 7.5 0 010 15z"/>
                            </svg>
                        </div>
                        @if($search)
                            <p class="mt-1 text-[11px] text-gray-500">
                                Résultats pour : <span class="font-semibold text-gray-700">“{{ $search }}”</span>
                            </p>
                        @endif
                    </div>

                    {{-- Filtres + Affichage sur UNE ligne (md+) --}}
                    <div class="flex flex-wrap md:flex-nowrap items-end gap-3 md:gap-4">
                        {{-- Filtre Statut de la thèse --}}
                        <div class="w-full sm:w-auto">
                            <label class="text-[11px] uppercase tracking-wide text-gray-500 font-semibold mb-1 block">
                                Statut de la thèse
                            </label>
                            <select
                                wire:model.live="filterStatut"
                                class="px-3 py-2.5 text-sm border border-gray-300 rounded-lg
                                    focus:ring-2 focus:ring-indigo-500 focus:border-transparent w-full sm:w-auto">
                                <option value="">Tous les statuts de thèse</option>
                                <option value="en_cours">En cours</option>
                                <option value="preparation">En préparation</option>
                                <option value="redaction">En rédaction</option>
                                <option value="soutenue">Soutenue</option>
                                <option value="suspendue">Suspendue</option>
                                <option value="abandonnee">Abandonnée</option>
                            </select>
                        </div>

                        {{-- Filtre École doctorale --}}
                        <div class="w-full sm:w-auto">
                            <label class="text-[11px] uppercase tracking-wide text-gray-500 font-semibold mb-1 block">
                                École doctorale
                            </label>
                            <select
                                wire:model.live="filterEad"
                                class="px-3 py-2.5 text-xs md:text-sm rounded-lg border border-gray-200 bg-white
                                    focus:ring-2 focus:ring-ed-primary/60 focus:border-transparent
                                    min-w-[170px] w-full sm:w-auto">
                                <option value="">Toutes les EAD</option>
                                @foreach($this->eads as $ead)
                                    <option value="{{ $ead->id }}">{{ Str::limit($ead->nom, 100) }}</option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Affichage (view mode) --}}
                        <div class="w-full sm:w-auto">
                            <label class="text-[11px] uppercase tracking-wide text-gray-500 font-semibold mb-1 block">
                                Affichage
                            </label>
                            <div class="flex bg-gray-100 rounded-full p-1 w-fit">
                                <button
                                    wire:click="setViewMode('grid')"
                                    class="px-3 py-1.5 rounded-full text-xs md:text-sm flex items-center gap-1
                                        {{ $viewMode === 'grid' ? 'bg-white shadow-sm text-ed-primary' : 'text-gray-600' }} transition">
                                    <svg class="w-4 h-4 md:w-5 md:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6
                                                a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16
                                                a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16
                                                a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>
                                    </svg>
                                    <span class="hidden md:inline">Grille</span>
                                </button>
                                <button
                                    wire:click="setViewMode('list')"
                                    class="px-3 py-1.5 rounded-full text-xs md:text-sm flex items-center gap-1
                                        {{ $viewMode === 'list' ? 'bg-white shadow-sm text-ed-primary' : 'text-gray-600' }} transition">
                                    <svg class="w-4 h-4 md:w-5 md:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M4 6h16M4 12h16M4 18h16"/>
                                    </svg>
                                    <span class="hidden md:inline">Liste</span>
                                </button>
                            </div>
                        </div>

                        {{-- Reset, poussé à droite sur grand écran --}}
                        @if($search || $filterStatut || $filterEad)
                            <div class="w-full sm:w-auto md:ml-auto">
                                <button
                                    wire:click="resetFilters"
                                    class="inline-flex items-center gap-1 px-3 py-2 bg-gray-100 hover:bg-gray-200
                                        text-gray-700 text-xs md:text-sm font-semibold rounded-lg transition w-full sm:w-auto justify-center md:justify-start">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                    Réinitialiser
                                </button>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>


    {{-- LISTE DES DOCTORANTS --}}
    <section class="py-8 bg-gradient-to-b from-gray-50 to-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                @if($this->doctorants->isEmpty())
                    {{-- Empty state --}}
                    <div class="text-center py-16">
                        <div class="inline-flex items-center justify-center w-20 h-20 rounded-full bg-gray-100 mb-4">
                            <svg class="w-10 h-10 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2
                                        c0-.656-.126-1.283-.356-1.857M7 20H2v-2
                                        a3 3 0 015.356-1.857M7 20v-2
                                        c0-.656.126-1.283.356-1.857m0 0
                                        a5.002 5.002 0 019.288 0M15 7
                                        a3 3 0 11-6 0 3 3 0 016 0zm6 3
                                        a2 2 0 11-4 0 2 2 0 014 0zM7 10
                                        a2 2 0 11-4 0 2 2 0 014 0z"/>
                            </svg>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-900 mb-2">Aucun doctorant trouvé</h3>
                        <p class="text-gray-600 mb-4">Essayez de modifier vos critères de recherche ou de réinitialiser les filtres.</p>
                        <button
                            wire:click="resetFilters"
                            class="btn-secondary text-sm">
                            Réinitialiser les filtres
                        </button>
                    </div>
                @else

                {{-- VUE GRILLE --}}
                @if($viewMode === 'grid')
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
                        @foreach($this->doctorants as $doctorant)
                            @php
                                // On choisit une "thèse principale" :
                                // priorité : en_cours > preparation > redaction > soutenue > autres
                                $thesePrincipale = $doctorant->theses
                                    ->sortBy(function ($these) {
                                        return match($these->statut) {
                                            'en_cours'    => 0,
                                            'preparation' => 1,
                                            'redaction'   => 2,
                                            'soutenue'    => 3,
                                            'suspendue'   => 4,
                                            'abandonnee'  => 5,
                                            default       => 99,
                                        };
                                    })
                                    ->first();

                                // Texte + couleur du badge (statut thèse prioritaire, sinon statut doctorant)
                                $badgeText  = null;
                                $badgeClass = null;

                                if ($thesePrincipale) {
                                    $statutThese = $thesePrincipale->statut;
                                    $badgeText   = ucfirst(str_replace('_', ' ', $statutThese));

                                    $badgeClass = match($statutThese) {
                                        'en_cours'    => 'bg-emerald-500',
                                        'preparation' => 'bg-amber-500',
                                        'redaction'   => 'bg-indigo-500',
                                        'soutenue'    => 'bg-blue-600',
                                        'suspendue'   => 'bg-orange-500',
                                        'abandonnee'  => 'bg-red-500',
                                        default       => 'bg-slate-500',
                                    };
                                } elseif ($doctorant->statut) {
                                    $statutDoc  = strtolower($doctorant->statut);
                                    $badgeText  = ucfirst($doctorant->statut);
                                    $badgeClass = match($statutDoc) {
                                        'actif'   => 'bg-emerald-500',
                                        'diplome' => 'bg-blue-500',
                                        'inactif' => 'bg-gray-400',
                                        default   => 'bg-slate-500',
                                    };
                                }
                            @endphp

                            <a href="{{ route('doctorants.show', $doctorant) }}"
                            class="card card-hover group overflow-hidden border border-gray-100 hover:border-ed-primary/40">
                                {{-- Header / Avatar + Badge --}}
                                <div class="relative bg-gradient-to-br from-ed-primary via-ed-secondary to-emerald-700 h-20 flex items-center justify-center">
                                    <div class="w-14 h-14 bg-white rounded-full flex items-center justify-center shadow-glow">
                                        <span class="text-xl font-bold text-ed-primary">
                                            {{ strtoupper(substr($doctorant->user->name ?? 'NN', 0, 1)) }}{{ strtoupper(substr(explode(' ', $doctorant->user->name ?? ' ')[1] ?? '', 0, 1)) }}
                                        </span>
                                    </div>

                                    @if($badgeText)
                                        <div class="absolute top-2 right-2">
                                            <span class="px-2 py-0.5 rounded-full text-[11px] font-semibold text-white {{ $badgeClass }} shadow-sm">
                                                {{ $badgeText }}
                                            </span>
                                        </div>
                                    @endif
                                </div>

                                {{-- Contenu --}}
                                <div class="p-4">
                                    <h3 class="font-semibold text-gray-900 text-sm mb-1 line-clamp-1 group-hover:text-ed-primary transition-colors">
                                        {{ $doctorant->user->name ?? 'Non renseigné' }}
                                    </h3>

                                    @if($doctorant->matricule)
                                        <p class="text-xs text-gray-500 mb-2">
                                            Matricule : {{ $doctorant->matricule }}
                                        </p>
                                    @endif

                                    @if($thesePrincipale)
                                        <div class="space-y-1.5 text-xs">
                                            {{-- Spécialité --}}
                                            @if($thesePrincipale->specialite)
                                                <div class="flex items-start gap-1.5">
                                                    <svg class="w-3.5 h-3.5 text-ed-primary mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13
                                                                C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13
                                                                C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13
                                                                C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                                                    </svg>
                                                    <span class="text-gray-600 line-clamp-1">
                                                        {{ $thesePrincipale->specialite->nom }}
                                                    </span>
                                                </div>
                                            @endif

                                            {{-- EAD --}}
                                            @if($thesePrincipale->ead)
                                                <div class="flex items-start gap-1.5">
                                                    <svg class="w-3.5 h-3.5 text-purple-500 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5
                                                                m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1
                                                                m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                                    </svg>
                                                    <span class="text-gray-600 line-clamp-1">
                                                        {{ $thesePrincipale->ead->nom }}
                                                    </span>
                                                </div>
                                            @endif
                                        </div>
                                    @else
                                        <p class="text-xs text-gray-400 italic mt-1">
                                            Aucune thèse renseignée
                                        </p>
                                    @endif

                                    {{-- Footer --}}
                                    <div class="mt-3 pt-3 border-t border-gray-100 flex justify-end">
                                        <span class="inline-flex items-center gap-1.5 text-[11px] font-semibold text-ed-primary group-hover:gap-2 transition-all">
                                            Voir la fiche
                                            <svg class="w-3.5 h-3.5 group-hover:translate-x-0.5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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

                {{-- VUE LISTE --}}
                @if($viewMode === 'list')
                    <div class="space-y-3">
                        @foreach($this->doctorants as $doctorant)
                            @php
                                // Thèse principale (même logique que pour la grille)
                                $thesePrincipale = $doctorant->theses
                                    ->sortBy(function ($these) {
                                        return match($these->statut) {
                                            'en_cours'    => 0,
                                            'preparation' => 1,
                                            'redaction'   => 2,
                                            'soutenue'    => 3,
                                            'suspendue'   => 4,
                                            'abandonnee'  => 5,
                                            default       => 99,
                                        };
                                    })
                                    ->first();

                                $badgeText  = null;
                                $badgeClass = null;

                                if ($thesePrincipale) {
                                    $statutThese = $thesePrincipale->statut;
                                    $badgeText   = ucfirst(str_replace('_', ' ', $statutThese));

                                    $badgeClass = match($statutThese) {
                                        'en_cours'    => 'bg-emerald-500',
                                        'preparation' => 'bg-amber-500',
                                        'redaction'   => 'bg-indigo-500',
                                        'soutenue'    => 'bg-blue-600',
                                        'suspendue'   => 'bg-orange-500',
                                        'abandonnee'  => 'bg-red-500',
                                        default       => 'bg-slate-500',
                                    };
                                } elseif ($doctorant->statut) {
                                    $statutDoc  = strtolower($doctorant->statut);
                                    $badgeText  = ucfirst($doctorant->statut);
                                    $badgeClass = match($statutDoc) {
                                        'actif'   => 'bg-emerald-500',
                                        'diplome' => 'bg-blue-500',
                                        'inactif' => 'bg-gray-400',
                                        default   => 'bg-slate-500',
                                    };
                                }
                            @endphp

                            <a href="{{ route('doctorants.show', $doctorant) }}"
                            class="card card-hover group flex items-center gap-4 p-4 border border-gray-100 hover:border-ed-primary/40">
                                {{-- Avatar --}}
                                <div class="relative flex-shrink-0">
                                    <div class="w-16 h-16 rounded-full bg-gradient-to-br from-ed-primary to-ed-secondary flex items-center justify-center text-white font-bold shadow-glow">
                                        {{ strtoupper(substr($doctorant->user->name ?? 'NN', 0, 1)) }}{{ strtoupper(substr(explode(' ', $doctorant->user->name ?? ' ')[1] ?? '', 0, 1)) }}
                                    </div>

                                    @if($badgeText)
                                        <span class="absolute -bottom-1 -right-1 px-2 py-0.5 text-[11px] rounded-full text-white font-semibold border-2 border-white {{ $badgeClass }}">
                                            {{ $badgeText }}
                                        </span>
                                    @endif
                                </div>

                                {{-- Contenu principal --}}
                                <div class="flex-1 min-w-0">
                                    <div class="flex flex-col md:flex-row md:items-start md:justify-between gap-2">
                                        <div class="min-w-0">
                                            <h3 class="font-semibold text-gray-900 group-hover:text-ed-primary transition-colors">
                                                {{ $doctorant->user->name ?? 'Non renseigné' }}
                                            </h3>

                                            @if($doctorant->matricule)
                                                <p class="text-xs text-gray-500 mt-0.5">
                                                    Matricule : {{ $doctorant->matricule }}
                                                </p>
                                            @endif

                                            @if($thesePrincipale)
                                                <p class="mt-2 text-sm text-gray-600 line-clamp-2">
                                                    {{ $thesePrincipale->sujet_these }}
                                                </p>
                                            @else
                                                <p class="mt-2 text-sm text-gray-400 italic">
                                                    Aucune thèse renseignée
                                                </p>
                                            @endif
                                        </div>

                                        {{-- Badges spé / EAD --}}
                                        @if($thesePrincipale)
                                            <div class="flex flex-wrap md:flex-col items-start md:items-end gap-1 md:gap-2 flex-shrink-0">
                                                @if($thesePrincipale->specialite)
                                                    <span class="badge badge-primary text-[11px]">
                                                        {{ $thesePrincipale->specialite->nom }}
                                                    </span>
                                                @endif

                                                @if($thesePrincipale->ead)
                                                    <span class="badge bg-purple-50 text-purple-700 border border-purple-100 text-[11px]">
                                                        {{ $thesePrincipale->ead->nom }}
                                                    </span>
                                                @endif
                                            </div>
                                        @endif
                                    </div>
                                </div>

                                {{-- Flèche --}}
                                <div class="flex-shrink-0">
                                    <svg class="w-5 h-5 text-gray-300 group-hover:text-ed-primary group-hover:translate-x-1 transition-all" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 5l7 7-7 7"/>
                                    </svg>
                                </div>
                            </a>
                        @endforeach
                    </div>
                @endif


                {{-- Pagination --}}
                <div class="mt-8">
                    {{ $this->doctorants->links() }}
                </div>
            @endif
        </div>
    </section>
</div>
