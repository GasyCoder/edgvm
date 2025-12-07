<div>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div class="flex items-center gap-4">
                <a href="{{ route('admin.theses.index') }}" 
                   class="p-2 hover:bg-gray-100 rounded-lg transition">
                    <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                </a>
                <h2 class="text-2xl font-bold text-gray-800">Détails de la thèse</h2>
            </div>
            
            <a href="{{ route('admin.theses.edit', $these) }}" 
               class="px-6 py-3 bg-ed-primary text-white rounded-lg hover:bg-ed-secondary transition font-bold shadow-lg flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                </svg>
                Modifier
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            {{-- Messages flash --}}
            @if (session()->has('success'))
                <div class="mb-6 p-4 bg-green-100 border-l-4 border-green-500 text-green-700 rounded">
                    {{ session('success') }}
                </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                
                {{-- COLONNE GAUCHE : Doctorant, Statut, Dates, ED / Université --}}
                <div class="lg:col-span-1 space-y-6">
                    
                    {{-- Carte Doctorant --}}
                    <div class="bg-white rounded-xl shadow-lg p-6">
                        @php
                            $statutThese = $these->statut ?? 'non défini';
                            $classesStatut = match($statutThese) {
                                'en_cours'   => 'bg-green-100 text-green-700',
                                'soutenue'   => 'bg-blue-100 text-blue-700',
                                'abandonnee' => 'bg-red-100 text-red-700',
                                'suspendue'  => 'bg-yellow-100 text-yellow-700',
                                default      => 'bg-gray-100 text-gray-700',
                            };
                        @endphp

                        <div class="flex items-start justify-between mb-4">
                            <h3 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                                <svg class="w-5 h-5 text-ed-primary" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z"/>
                                </svg>
                                Doctorant
                            </h3>

                            {{-- Statut de la thèse --}}
                            <span class="inline-block px-4 py-1.5 rounded-full text-xs font-bold {{ $classesStatut }}">
                                {{ ucfirst(str_replace('_', ' ', $statutThese)) }}
                            </span>
                        </div>
                        
                        <div class="flex flex-col items-center text-center">
                            {{-- Avatar --}}
                            <div class="w-20 h-20 bg-gradient-to-br from-blue-500 to-purple-600 rounded-full flex items-center justify-center mb-3">
                                <svg class="w-10 h-10 text-white" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z"/>
                                </svg>
                            </div>
                            
                            {{-- Nom --}}
                            <h4 class="text-lg font-bold text-gray-900 mb-1">
                                {{ $these->doctorant->user?->name ?? 'Pas de compte' }}
                            </h4>
                            
                            {{-- Matricule --}}
                            <p class="text-sm text-gray-500 mb-3">
                                Matricule : {{ $these->doctorant->matricule }}
                            </p>

                            {{-- Niveau doctorant (optionnel si tu as le champ) --}}
                            @if(!empty($these->doctorant->niveau))
                                <p class="text-xs text-gray-500 mb-2">
                                    Niveau : <span class="font-semibold">{{ $these->doctorant->niveau }}</span>
                                </p>
                            @endif
                            
                            {{-- Lien vers profil --}}
                            <a href="{{ route('admin.doctorants.show', $these->doctorant) }}" 
                            class="text-sm text-ed-primary hover:text-ed-secondary font-semibold">
                                Voir le profil complet →
                            </a>
                        </div>
                    </div>

                    {{-- Carte Dates importantes --}}
                    <div class="bg-white rounded-xl shadow-lg p-6">
                        <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                            <svg class="w-5 h-5 text-ed-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            Dates importantes
                        </h3>
                        
                        <div class="space-y-3">
                            @if($these->date_debut)
                                <div>
                                    <p class="text-xs font-semibold text-gray-500 uppercase">Date de début</p>
                                    <p class="text-sm text-gray-900">{{ $these->date_debut->format('d/m/Y') }}</p>
                                </div>
                            @endif

                            @if($these->date_prevue_fin)
                                <div>
                                    <p class="text-xs font-semibold text-gray-500 uppercase">Date de fin prévue</p>
                                    <p class="text-sm text-gray-900">{{ $these->date_prevue_fin->format('d/m/Y') }}</p>
                                </div>
                            @endif

                            @if($these->date_publication)
                                <div>
                                    <p class="text-xs font-semibold text-gray-500 uppercase">Date de publication / soutenance</p>
                                    <p class="text-sm text-gray-900 font-bold">
                                        {{ $these->date_publication->format('d/m/Y') }}
                                    </p>
                                </div>
                            @endif

                            @if(!$these->date_debut && !$these->date_prevue_fin && !$these->date_publication)
                                <p class="text-sm text-gray-400">Aucune date définie</p>
                            @endif
                        </div>
                    </div>

                    {{-- Carte École doctorale & Université --}}
                    <div class="bg-white rounded-xl shadow-lg p-6">
                        <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                            <svg class="w-5 h-5 text-ed-primary" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M10.394 2.08a1 1 0 00-.788 0l-7 3a1 1 0 000 1.84L5.25 8.051a.999.999 0 01.356-.257l4-1.714a1 1 0 11.788 1.838L7.667 9.088l1.94.831a1 1 0 00.787 0l7-3a1 1 0 000-1.838l-7-3zM3.31 9.397L5 10.12v4.102a8.969 8.969 0 00-1.05-.174 1 1 0 01-.89-.89 11.115 11.115 0 01.25-3.762zM9.3 16.573A9.026 9.026 0 007 14.935v-3.957l1.818.78a3 3 0 002.364 0l5.508-2.361a11.026 11.026 0 01.25 3.762 1 1 0 01-.89.89 8.968 8.968 0 00-5.35 2.524 1 1 0 01-1.4 0zM6 18a1 1 0 001-1v-2.065a8.935 8.935 0 00-2-.712V17a1 1 0 001 1z"/>
                            </svg>
                            École doctorale & Université
                        </h3>

                        <div class="space-y-3">
                            @if($these->ead)
                                <div>
                                    <p class="text-xs font-semibold text-gray-500 uppercase">École doctorale</p>
                                    <p class="text-sm text-gray-900 font-semibold">
                                        {{ $these->ead->nom }}
                                    </p>
                                    @if($these->ead->description)
                                        <p class="text-xs text-gray-600 mt-1">
                                            {{ $these->ead->description }}
                                        </p>
                                    @endif
                                </div>
                            @endif

                            <div>
                                <p class="text-xs font-semibold text-gray-500 uppercase">Université de soutenance</p>
                                @if($these->universite_soutenance)
                                    <p class="text-sm text-gray-900">
                                        {{ $these->universite_soutenance }}
                                    </p>
                                @else
                                    <p class="text-sm text-gray-400 italic">
                                        Non renseignée
                                    </p>
                                @endif
                            </div>
                        </div>
                    </div>

                </div>

                {{-- COLONNE DROITE : Sujet, Résumé, Mots-clés, Encadrement, Jury, PDF --}}
                <div class="lg:col-span-2 space-y-6">
                    
                    {{-- Sujet & résumé --}}
                    <div class="bg-white rounded-xl shadow-lg p-6">
                        <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                            <svg class="w-5 h-5 text-ed-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                            </svg>
                            Sujet & résumé
                        </h3>
                        
                        {{-- Sujet --}}
                        <div class="mb-4">
                            <p class="text-xs font-semibold text-gray-500 uppercase mb-1">
                                Sujet de la thèse
                            </p>
                            @if($these->sujet_these)
                                <p class="text-gray-800 leading-relaxed">
                                    {{ $these->sujet_these }}
                                </p>
                            @else
                                <p class="text-gray-400 italic">Sujet non défini</p>
                            @endif
                        </div>

                        {{-- Résumé --}}
                        <div x-data="{ expanded: false }">
                            <p class="text-xs font-semibold text-gray-500 uppercase mb-1">
                                Résumé
                            </p>

                            @if($these->resume_these)
                                {{-- Version courte --}}
                                <p x-show="!expanded"
                                class="text-gray-700 leading-relaxed whitespace-pre-line">
                                    {{ \Illuminate\Support\Str::limit($these->resume_these, 400) }}
                                </p>

                                {{-- Version complète --}}
                                <p x-show="expanded"
                                x-cloak
                                class="text-gray-700 leading-relaxed whitespace-pre-line">
                                    {{ $these->resume_these }}
                                </p>

                                {{-- Bouton Lire la suite / Réduire --}}
                                @if(mb_strlen($these->resume_these) > 400)
                                    <button type="button"
                                            class="mt-2 text-sm font-semibold text-ed-primary hover:text-ed-secondary flex items-center gap-1"
                                            @click="expanded = !expanded">
                                        <span x-show="!expanded">Lire la suite…</span>
                                        <span x-show="expanded" x-cloak>Réduire le texte</span>
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path x-show="!expanded" x-cloak
                                                stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 9l-7 7-7-7"/>
                                            <path x-show="expanded"
                                                stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M5 15l7-7 7 7"/>
                                        </svg>
                                    </button>
                                @endif
                            @else
                                <p class="text-gray-400 italic">Aucun résumé enregistré</p>
                            @endif
                        </div>

                    </div>

                    {{-- Mots-clés & métadonnées --}}
                    <div class="bg-white rounded-xl shadow-lg p-6">
                        <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                            <svg class="w-5 h-5 text-ed-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M7 7h.01M3 5a2 2 0 012-2h3.586a1 1 0 01.707.293l8.414 8.414a2 2 0 010 2.828l-3.586 3.586a2 2 0 01-2.828 0L3.293 9.707A1 1 0 013 9V5z"/>
                            </svg>
                            Mots-clés & métadonnées
                        </h3>

                        @php
                            $tags = $these->mots_cles
                                ? array_filter(array_map('trim', explode(',', $these->mots_cles)))
                                : [];
                        @endphp

                        <div class="mb-4">
                            <p class="text-xs font-semibold text-gray-500 uppercase mb-1">
                                Mots-clés
                            </p>
                            @if(count($tags))
                                <div class="flex flex-wrap gap-2">
                                    @foreach($tags as $tag)
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-indigo-50 text-indigo-700 border border-indigo-100">
                                            {{ $tag }}
                                        </span>
                                    @endforeach
                                </div>
                            @else
                                <p class="text-gray-400 italic">Aucun mot-clé renseigné</p>
                            @endif
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm text-gray-700">
                            <div>
                                <p class="text-xs font-semibold text-gray-500 uppercase mb-1">
                                    ID interne
                                </p>
                                <p>#{{ $these->id }}</p>
                            </div>
                            <div>
                                <p class="text-xs font-semibold text-gray-500 uppercase mb-1">
                                    Créée le
                                </p>
                                <p>{{ optional($these->created_at)->format('d/m/Y H:i') }}</p>
                            </div>
                            <div>
                                <p class="text-xs font-semibold text-gray-500 uppercase mb-1">
                                    Dernière mise à jour
                                </p>
                                <p>{{ optional($these->updated_at)->format('d/m/Y H:i') }}</p>
                            </div>
                        </div>
                    </div>

                    {{-- Encadrement --}}
                    <div class="bg-white rounded-xl shadow-lg p-6">
                        <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                            <svg class="w-5 h-5 text-ed-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                            </svg>
                            Équipe d'encadrement
                        </h3>

                        @php
                            $directeur   = $these->encadrants->firstWhere('pivot.role', 'directeur');
                            $codirecteur = $these->encadrants->firstWhere('pivot.role', 'codirecteur');
                        @endphp
                        
                        <div class="space-y-4">
                            @if($directeur)
                                <div class="p-4 bg-purple-50 rounded-lg border border-purple-200">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center gap-3">
                                            <div class="w-12 h-12 bg-purple-100 rounded-full flex items-center justify-center">
                                                <svg class="w-6 h-6 text-purple-600" fill="currentColor" viewBox="0 0 20 20">
                                                    <path d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z"/>
                                                </svg>
                                            </div>
                                            <div>
                                                <p class="text-sm font-semibold text-purple-900">Directeur de thèse</p>
                                                <p class="text-base font-bold text-gray-900">
                                                    {{ $directeur->user?->name ?? 'N/A' }}
                                                </p>
                                                @if($directeur->grade)
                                                    <p class="text-xs text-gray-600">{{ $directeur->grade }}</p>
                                                @endif
                                            </div>
                                        </div>
                                        @if(Route::has('admin.encadrants.show'))
                                            <a href="{{ route('admin.encadrants.show', $directeur) }}" 
                                               class="text-sm text-purple-600 hover:text-purple-800 font-semibold">
                                                Voir →
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            @endif

                            @if($codirecteur)
                                <div class="p-4 bg-indigo-50 rounded-lg border border-indigo-200">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center gap-3">
                                            <div class="w-12 h-12 bg-indigo-100 rounded-full flex items-center justify-center">
                                                <svg class="w-6 h-6 text-indigo-600" fill="currentColor" viewBox="0 0 20 20">
                                                    <path d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z"/>
                                                </svg>
                                            </div>
                                            <div>
                                                <p class="text-sm font-semibold text-indigo-900">Co-directeur de thèse</p>
                                                <p class="text-base font-bold text-gray-900">
                                                    {{ $codirecteur->user?->name ?? 'N/A' }}
                                                </p>
                                                @if($codirecteur->grade)
                                                    <p class="text-xs text-gray-600">{{ $codirecteur->grade }}</p>
                                                @endif
                                            </div>
                                        </div>
                                        @if(Route::has('admin.encadrants.show'))
                                            <a href="{{ route('admin.encadrants.show', $codirecteur) }}" 
                                               class="text-sm text-indigo-600 hover:text-indigo-800 font-semibold">
                                                Voir →
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            @endif

                            @if(!$directeur && !$codirecteur)
                                <p class="text-gray-400 italic text-center py-4">
                                    Aucun encadrant assigné
                                </p>
                            @endif
                        </div>
                    </div>

                    {{-- Jury --}}
                    <div class="bg-white rounded-xl shadow-lg p-6">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                                <svg class="w-5 h-5 text-ed-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                                Membres du jury
                            </h3>

                            <div class="flex items-center gap-2">
                                {{-- Bouton pour gérer / configurer le jury de cette thèse --}}
                                <a href="{{ route('admin.theses.jury.edit', $these) }}"
                                class="text-xs px-3 py-1.5 rounded-lg 
                                        {{ $these->jurys->count() > 0 
                                                ? 'bg-indigo-50 text-indigo-700 hover:bg-indigo-100' 
                                                : 'bg-green-50 text-green-700 hover:bg-green-100' }} 
                                        font-semibold">
                                    {{ $these->jurys->count() > 0 ? 'Gérer le jury' : 'Configurer le jury' }}
                                </a>

                                {{-- Bouton pour créer un NOUVEAU membre de jury (table jurys) --}}
                                <a href="{{ route('admin.jurys.create') }}"
                                class="text-xs px-3 py-1.5 rounded-lg bg-gray-100 text-gray-700 hover:bg-gray-200 font-semibold">
                                    + Nouveau membre
                                </a>
                            </div>
                        </div>

                        @if($these->jurys->count() > 0)
                            {{-- Liste des membres du jury pour cette thèse --}}
                            <div class="space-y-3">
                                @foreach($these->jurys->sortBy('pivot.ordre') as $membre)
                                    <div class="flex items-start justify-between border-b last:border-0 pb-2">
                                        <div>
                                            <p class="text-sm font-semibold text-gray-900">
                                                {{ $membre->nom }}
                                            </p>
                                            <p class="text-xs text-gray-600">
                                                {{ $membre->grade }} 
                                                @if($membre->universite)
                                                    – {{ $membre->universite }}
                                                @endif
                                            </p>
                                            @if($membre->email || $membre->phone)
                                                <p class="text-xs text-gray-500 mt-1">
                                                    @if($membre->email) {{ $membre->email }} @endif
                                                    @if($membre->email && $membre->phone) • @endif
                                                    @if($membre->phone) {{ $membre->phone }} @endif
                                                </p>
                                            @endif
                                        </div>
                                        <div class="text-right">
                                            <p class="text-xs font-semibold text-ed-primary uppercase">
                                                {{ $membre->pivot->role ?? 'Membre' }}
                                            </p>
                                            @if($membre->pivot->ordre)
                                                <p class="text-xs text-gray-400">
                                                    Ordre : {{ $membre->pivot->ordre }}
                                                </p>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            {{-- Message si aucun jury encore configuré --}}
                            <p class="text-sm text-gray-500">
                                Aucun jury n’a encore été configuré pour cette thèse.
                                Cliquez sur <span class="font-semibold">« Configurer le jury »</span> pour ajouter les membres,
                                ou sur <span class="font-semibold">« + Nouveau membre »</span> pour créer une nouvelle fiche jury.
                            </p>
                        @endif
                    </div>

                    {{-- Publication & PDF --}}
                    <div class="bg-white rounded-xl shadow-lg p-6">
                        <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                            <svg class="w-5 h-5 text-ed-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M12 4v12m0 0l-4-4m4 4l4-4M4 20h16"/>
                            </svg>
                            Publication & fichier PDF
                        </h3>

                        <div class="space-y-4">
                            <div>
                                <p class="text-xs font-semibold text-gray-500 uppercase mb-1">
                                    Statut de mise en ligne
                                </p>
                                @if($these->fichier)
                                    <p class="text-sm text-green-700 font-semibold">
                                        ✅ Fichier PDF associé à cette thèse
                                    </p>
                                @else
                                    <p class="text-sm text-gray-400 italic">
                                        Aucun fichier PDF n'est encore associé.
                                    </p>
                                @endif
                            </div>

                            @if($these->fichier)
                                <div class="p-3 bg-gray-50 border border-gray-200 rounded-lg text-sm">
                                    <p class="font-semibold text-gray-900">
                                        {{ $these->fichier->display_name ?? $these->fichier->nom_original }}
                                    </p>
                                    <p class="text-xs text-gray-500 mt-1">
                                        {{ $these->fichier->mime_type }} –
                                        {{ number_format(($these->fichier->taille_bytes ?? 0) / 1024, 1, ',', ' ') }} Ko
                                    </p>

                                    <div class="mt-3 flex flex-wrap gap-3">
                                        <a href="{{ $these->fichier->url }}" target="_blank"
                                           class="inline-flex items-center px-4 py-2 rounded-lg bg-ed-primary text-white text-xs font-semibold hover:bg-ed-secondary">
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                      d="M4 16v2a2 2 0 002 2h12a2 2 0 002-2v-2M7 10l5 5m0 0l5-5m-5 5V4"/>
                                            </svg>
                                            Télécharger le PDF
                                        </a>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>

                </div> {{-- /colonne droite --}}
            </div>
        </div>
    </div>
</div>