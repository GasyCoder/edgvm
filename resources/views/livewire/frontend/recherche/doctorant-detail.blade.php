<div>
    <!-- Hero Section -->
    <section class="relative gradient-primary pt-24 md:pt-28 pb-16 md:pb-20 overflow-hidden">
        <div class="absolute inset-0 opacity-10">
            <div class="absolute top-10 right-10 w-96 h-96 bg-white rounded-full blur-3xl"></div>
            <div class="absolute bottom-10 left-10 w-96 h-96 bg-white rounded-full blur-3xl"></div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 mt-20">
            <div class="flex flex-col md:flex-row items-start md:items-center gap-6">
                <!-- Avatar -->
                <div class="relative">
                    <div class="w-28 h-28 bg-gradient-to-br from-white to-gray-100 rounded-full flex items-center justify-center shadow-2xl border-4 border-white/30">
                        <span class="text-4xl font-bold text-indigo-600">
                            {{ strtoupper(substr($doctorant->user->name ?? 'NN', 0, 1)) }}{{ strtoupper(substr(explode(' ', $doctorant->user->name ?? ' ')[1] ?? '', 0, 1)) }}
                        </span>
                    </div>
                    <div class="absolute -bottom-2 -right-2 w-8 h-8 bg-green-500 border-4 border-white rounded-full"></div>
                </div>

                <!-- Informations principales -->
                <div class="flex-1">
                    <h1 class="text-3xl md:text-4xl font-bold text-white mb-2">
                        {{ $doctorant->user->name ?? 'Non renseigné' }}
                    </h1>

                    @if($doctorant->matricule)
                        <p class="text-white/80 text-sm mb-3">
                            Matricule: {{ $doctorant->matricule }}
                        </p>
                    @endif

                    {{-- Badges --}}
                    <div class="flex flex-wrap gap-2">
                        @if($doctorant->niveau)
                            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-white/20 backdrop-blur-lg text-white text-sm font-semibold rounded-full border border-white/30">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 14l9-5-9-5-9 5 9 5z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055
                                            a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"/>
                                </svg>
                                {{ ucfirst($doctorant->niveau) }}
                            </span>
                        @endif

                        {{-- ✅ Badge calculé côté backend (statut de thèse ou doctorant) --}}
                        @if($badgeText)
                            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 backdrop-blur-lg text-sm font-semibold rounded-full border {{ $badgeClasses }}">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293
                                            a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293
                                            a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                        clip-rule="evenodd"/>
                                </svg>
                                {{ $badgeText }}
                            </span>
                        @endif

                        @if($thesePrincipale && $thesePrincipale->specialite)
                            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-blue-500/20 backdrop-blur-lg text-white text-sm font-semibold rounded-full border border-blue-400/30">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13
                                            C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13
                                            C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13
                                            C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                                </svg>
                                {{ $thesePrincipale->specialite->nom }}
                            </span>
                        @endif
                    </div>


                </div>
            </div>
        </div>
    </section>

    <!-- Contenu principal -->
    <section class="py-12 bg-gradient-to-b from-gray-50 to-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

                <!-- Colonne principale -->
                <div class="lg:col-span-2 space-y-8">

                    <!-- Thèse principale (en cours ou soutenue) -->
                    @if($thesePrincipale)
                        @php
                            $statutThese = $thesePrincipale->statut;
                        @endphp

                        <div class="bg-white rounded-2xl shadow-xl p-8">
                            <h2 class="text-2xl font-bold text-gray-900 mb-6 flex items-center gap-2">
                                <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                                @if($statutThese === 'soutenue')
                                    Thèse soutenue
                                @else
                                    Thèse en cours
                                @endif
                            </h2>

                            <div class="space-y-6">
                                <!-- Sujet de thèse -->
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Sujet de thèse</h3>
                                    <p class="text-gray-700 font-bold leading-relaxed">
                                        {{ $thesePrincipale->sujet_these }}
                                    </p>
                                </div>

                                <!-- Description -->
                                @if($thesePrincipale->description)
                                    <div>
                                        <h3 class="text-lg font-semibold text-gray-900 mb-2">Description</h3>
                                        <p class="text-gray-700 leading-relaxed">
                                            {{ $thesePrincipale->description }}
                                        </p>
                                    </div>
                                @endif

                                <!-- Résumé avec "Lire la suite" -->
                                @if($thesePrincipale->resume_these)
                                    <div x-data="{ expanded: false }">
                                        <h3 class="text-lg font-semibold text-gray-900 mb-3 flex items-center gap-2">
                                            <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                      d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414
                                                         a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                            </svg>
                                            Résumé
                                        </h3>

                                        {{-- Version courte --}}
                                        <p x-show="!expanded"
                                           class="text-gray-700 leading-relaxed whitespace-pre-line text-justify">
                                            {{ $this->resumeLimite }}
                                        </p>

                                        {{-- Version complète --}}
                                        <p x-show="expanded"
                                           x-cloak
                                           class="text-gray-700 leading-relaxed whitespace-pre-line text-justify">
                                            {{ $thesePrincipale->resume_these }}
                                        </p>

                                        {{-- Bouton Lire la suite / Réduire --}}
                                        @if($this->showReadMore)
                                            <button type="button"
                                                    class="mt-3 inline-flex items-center gap-1.5 text-sm font-semibold text-indigo-600 hover:text-indigo-800 transition-colors"
                                                    @click="expanded = !expanded">
                                                <span x-show="!expanded">Lire la suite</span>
                                                <span x-show="expanded" x-cloak>Réduire le texte</span>
                                                <svg class="w-4 h-4 transition-transform" :class="expanded && 'rotate-180'"
                                                     fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                          d="M19 9l-7 7-7-7"/>
                                                </svg>
                                            </button>
                                        @endif
                                    </div>
                                @endif

                                <!-- Mots-clés -->
                                @if($this->motsClesArray)
                                    <div>
                                        <h3 class="text-lg font-semibold text-gray-900 mb-3 flex items-center gap-2">
                                            <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                      d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7
                                                         a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                                            </svg>
                                            Mots-clés
                                        </h3>
                                        <div class="flex flex-wrap gap-2">
                                            @foreach($this->motsClesArray as $motCle)
                                                <span class="px-3 py-1.5 bg-indigo-50 text-indigo-700 text-sm font-medium rounded-full border border-indigo-100">
                                                    {{ $motCle }}
                                                </span>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif

                                <!-- Timeline -->
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-6">
                                    @if($thesePrincipale->date_debut)
                                        <div class="bg-gradient-to-br from-blue-50 to-indigo-50 rounded-xl p-4 border border-blue-100">
                                            <p class="text-xs text-blue-600 font-semibold mb-1">Date de début</p>
                                            <p class="text-lg font-bold text-blue-900">
                                                {{ $thesePrincipale->date_debut->format('d/m/Y') }}
                                            </p>
                                        </div>
                                    @endif

                                    @if($thesePrincipale->date_publication)
                                        <div class="bg-gradient-to-br from-purple-50 to-pink-50 rounded-xl p-4 border border-purple-100">
                                            <p class="text-xs text-purple-600 font-semibold mb-1">
                                                {{ $statutThese === 'soutenue' ? "Date de soutenance" : "Date de publication" }}
                                            </p>
                                            <p class="text-lg font-bold text-purple-900">
                                                {{ $thesePrincipale->date_publication->format('d/m/Y') }}
                                            </p>
                                        </div>
                                    @elseif($thesePrincipale->date_prevue_fin)
                                        <div class="bg-gradient-to-br from-purple-50 to-pink-50 rounded-xl p-4 border border-purple-100">
                                            <p class="text-xs text-purple-600 font-semibold mb-1">Fin prévue</p>
                                            <p class="text-lg font-bold text-purple-900">
                                                {{ $thesePrincipale->date_prevue_fin->format('d/m/Y') }}
                                            </p>
                                        </div>
                                    @endif

                                    @if($this->dureeThese)
                                        <div class="bg-gradient-to-br from-green-50 to-emerald-50 rounded-xl p-4 border border-green-100">
                                            <p class="text-xs text-green-600 font-semibold mb-1">Durée</p>
                                            <p class="text-lg font-bold text-green-900">
                                                {{ $this->dureeThese }}
                                            </p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @else
                        {{-- Cas rare : aucun sujet de thèse --}}
                        <div class="bg-white rounded-2xl shadow-xl p-8">
                            <h2 class="text-2xl font-bold text-gray-900 mb-3">
                                Thèse
                            </h2>
                            <p class="text-gray-600">
                                Aucune thèse n’est actuellement associée à ce doctorant dans le système.
                            </p>
                        </div>
                    @endif

                    <!-- Encadrement de la thèse principale -->
                    @if($thesePrincipale && $thesePrincipale->encadrants->isNotEmpty())
                        <div class="bg-white rounded-2xl shadow-xl p-8">
                            <h2 class="text-2xl font-bold text-gray-900 mb-6 flex items-center gap-2">
                                <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
                                Encadrement
                            </h2>

                            <div class="space-y-4">
                                @foreach($thesePrincipale->encadrants as $encadrant)
                                    <div class="flex items-start gap-4 p-4 bg-gradient-to-r from-gray-50 to-white border border-gray-100 rounded-xl hover:border-indigo-200 hover:shadow-md transition-all">
                                        <div class="w-14 h-14 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-full flex items-center justify-center flex-shrink-0">
                                            <svg class="w-7 h-7 text-white" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd"
                                                      d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z"
                                                      clip-rule="evenodd"/>
                                            </svg>
                                        </div>
                                        <div class="flex-1">
                                            <div class="flex items-start justify-between gap-2">
                                                <div>
                                                    <p class="font-bold text-gray-900">
                                                        {{ $encadrant->name ?: 'Non renseigné' }}
                                                    </p>
                                                    @if($encadrant->grade)
                                                        <p class="text-sm text-gray-600">{{ $encadrant->grade }}</p>
                                                    @endif
                                                    @if($encadrant->email)
                                                        <a href="mailto:{{ $encadrant->email }}"
                                                           class="text-sm text-indigo-600 hover:text-indigo-800 hover:underline">
                                                            {{ $encadrant->email }}
                                                        </a>
                                                    @endif
                                                </div>
                                                <span class="px-3 py-1 bg-indigo-100 text-indigo-700 text-xs font-semibold rounded-full">
                                                    {{ ucfirst($encadrant->pivot->role ?? 'Encadrant') }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <!-- Historique des thèses -->
                    @if($this->thesesHistorique->count() > 0)
                        <div class="bg-white rounded-2xl shadow-xl p-8">
                            <h2 class="text-2xl font-bold text-gray-900 mb-6 flex items-center gap-2">
                                <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                Historique des thèses
                            </h2>

                            <div class="space-y-3">
                                @foreach($this->thesesHistorique as $these)
                                    @php
                                        $isSoutenue = $these->statut === 'soutenue';
                                    @endphp
                                    <div class="flex items-start gap-3 p-4 bg-gray-50 rounded-lg">
                                        <div class="w-8 h-8 bg-gray-300 rounded-full flex items-center justify-center flex-shrink-0">
                                            <svg class="w-4 h-4 text-gray-600" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd"
                                                      d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z"
                                                      clip-rule="evenodd"/>
                                            </svg>
                                        </div>
                                        <div class="flex-1">
                                            <p class="font-semibold text-gray-900 text-sm">
                                                {{ $these->sujet_these }}
                                            </p>
                                            <div class="flex items-center gap-2 mt-1">
                                                <span class="text-xs text-gray-600">
                                                    {{ $these->date_debut?->format('Y') }}
                                                    -
                                                    {{ $these->date_publication?->format('Y') ?? 'En cours' }}
                                                </span>
                                                <span class="px-2 py-0.5 rounded text-xs font-medium 
                                                    {{ $isSoutenue ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700' }}">
                                                    {{ ucfirst($these->statut) }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Sidebar -->
                <aside class="lg:col-span-1">
                    <div class="lg:sticky lg:top-24 space-y-6">

                        <!-- EAD / Spécialité (thèse principale) -->
                        @if($thesePrincipale)
                            <div class="bg-gradient-to-br from-indigo-600 to-purple-600 rounded-2xl shadow-xl p-6 text-white">
                                <h3 class="text-lg font-bold mb-4 flex items-center gap-2">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5
                                                 m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1
                                                 m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                    </svg>
                                    Rattachement
                                </h3>

                                <div class="space-y-4">
                                    @if($thesePrincipale->ead)
                                        <div>
                                            <p class="text-xs text-white/70 mb-1">Équipe d'Accueil Doctoral</p>
                                            <a href="{{ route('ead.show', $thesePrincipale->ead) }}"
                                               class="font-semibold hover:underline block">
                                                {{ $thesePrincipale->ead->nom }}
                                            </a>
                                        </div>
                                    @endif

                                    @if($thesePrincipale->specialite)
                                        <div>
                                            <p class="text-xs text-white/70 mb-1">Spécialité</p>
                                            <a href="{{ route('programmes.show', $thesePrincipale->specialite) }}"
                                               class="font-semibold hover:underline block">
                                                {{ $thesePrincipale->specialite->nom }}
                                            </a>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endif

                        <!-- Statistiques -->
                        @if($doctorant->date_inscription || $doctorant->theses->count() > 0)
                            <div class="bg-white rounded-2xl shadow-xl p-6">
                                <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                                    <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2
                                                 a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0
                                                 a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14
                                                 a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                                    </svg>
                                    Statistiques
                                </h3>

                                <div class="space-y-3 text-sm">
                                    @if($doctorant->date_inscription)
                                        <div class="flex justify-between items-center pb-3 border-b border-gray-100">
                                            <span class="text-gray-600">Inscription</span>
                                            <span class="font-semibold text-gray-900">
                                                {{ $doctorant->date_inscription->format('Y') }}
                                            </span>
                                        </div>
                                    @endif

                                    <div class="flex justify-between items-center pb-3 border-b border-gray-100">
                                        <span class="text-gray-600">Thèses</span>
                                        <span class="font-semibold text-gray-900">
                                            {{ $doctorant->theses->count() }}
                                        </span>
                                    </div>

                                    @if($this->thesesSoutenuesCount > 0)
                                        <div class="flex justify-between items-center">
                                            <span class="text-gray-600">Soutenues</span>
                                            <span class="font-semibold text-green-600">
                                                {{ $this->thesesSoutenuesCount }}
                                            </span>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endif

                        <!-- Navigation -->
                        <div class="bg-white rounded-2xl shadow-xl p-6">
                            @if($thesePrincipale && $thesePrincipale->ead)
                                <a href="{{ route('ead.show', $thesePrincipale->ead) }}"
                                   class="flex items-center gap-2 text-gray-600 hover:text-indigo-600 transition text-sm font-semibold">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M15 19l-7-7 7-7"/>
                                    </svg>
                                    Retour à l'EAD
                                </a>
                            @else
                                <a href="{{ route('ead') }}"
                                   class="flex items-center gap-2 text-gray-600 hover:text-indigo-600 transition text-sm font-semibold">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M15 19l-7-7 7-7"/>
                                    </svg>
                                    Toutes les EAD
                                </a>
                            @endif
                        </div>
                    </div>
                </aside>
            </div>
        </div>
    </section>
</div>
