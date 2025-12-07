<div>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <a href="{{ route('admin.doctorants.index') }}" 
               class="p-2 hover:bg-gray-100 rounded-lg transition">
                <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
            </a>
            <div>
                <h2 class="text-2xl font-bold text-gray-800">Profil du doctorant</h2>
                <p class="text-sm text-gray-600">{{ $doctorant->name }}</p>
            </div>
        </div>
    </x-slot>

    @php
        // Toujours essayer d'avoir UNE thèse de référence :
        // 1) thèse en cours (relation theseActive si chargée)
        // 2) sinon thèse avec statut en_cours dans la collection
        // 3) sinon dernière thèse par date_debut
        $theseActive = $doctorant->theseActive 
            ?? $doctorant->theses->where('statut', 'en_cours')->first()
            ?? $doctorant->theses->sortByDesc('date_debut')->first();

        $directeur   = $theseActive?->encadrants->firstWhere('pivot.role', 'directeur');
        $codirecteur = $theseActive?->encadrants->firstWhere('pivot.role', 'codirecteur');
    @endphp

    <div class="py-8">
        <div class="max-w-10xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Colonne principale -->
                <div class="lg:col-span-2 space-y-6">
                    
                    <!-- Card Identité -->
                    <div class="bg-white rounded-xl shadow-lg p-6">
                        <div class="flex items-center justify-between mb-6">
                            <h3 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                                <svg class="w-5 h-5 text-ed-primary" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/>
                                </svg>
                                Identité
                            </h3>
                            <a href="{{ route('admin.doctorants.edit', $doctorant) }}" 
                               class="px-4 py-2 bg-blue-100 text-blue-700 rounded-lg hover:bg-blue-200 transition text-sm font-semibold">
                                Modifier
                            </a>
                        </div>
                        
                        <div class="flex flex-col md:flex-row gap-6 items-start">
                            {{-- Photo / avatar --}}
                            <div class="flex flex-col items-center gap-2">
                                <div class="relative">
                                    <div class="w-24 h-24 rounded-full overflow-hidden ring-4 ring-ed-primary/10 bg-gray-100 flex items-center justify-center shadow-sm">
                                        @if($doctorant->user && $doctorant->user->profile_photo_path)
                                            <img src="{{ $doctorant->user->profile_photo_url ?? asset('storage/'.$doctorant->user->profile_photo_path) }}"
                                                 alt="Photo de {{ $doctorant->name }}"
                                                 class="w-full h-full object-cover">
                                        @else
                                            <span class="text-2xl font-bold text-gray-400">
                                                {{ strtoupper(mb_substr($doctorant->name, 0, 1)) }}
                                            </span>
                                        @endif
                                    </div>

                                    @if($doctorant->user)
                                        <span class="absolute bottom-1 right-1 w-3.5 h-3.5 rounded-full bg-green-500 ring-2 ring-white"></span>
                                    @endif
                                </div>

                                @if($doctorant->user)
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 bg-green-50 text-green-700 rounded-full text-xs font-semibold">
                                        <span class="w-1.5 h-1.5 rounded-full bg-green-500"></span>
                                        Compte actif
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 bg-gray-50 text-gray-500 rounded-full text-xs font-semibold">
                                        Aucun compte
                                    </span>
                                @endif
                            </div>

                            {{-- Infos texte --}}
                            <div class="flex-1 space-y-4">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <p class="text-xs text-gray-500 mb-1">Nom complet</p>
                                        <p class="text-sm font-semibold text-gray-900">
                                            {{ $doctorant->name }}
                                        </p>
                                    </div>
                                    <div>
                                        <p class="text-xs text-gray-500 mb-1">Matricule</p>
                                        <p class="text-sm font-mono font-semibold text-gray-900">
                                            {{ $doctorant->matricule }}
                                        </p>
                                    </div>
                                    <div>
                                        <p class="text-xs text-gray-500 mb-1">Email</p>
                                        @if($doctorant->user)
                                            <a href="mailto:{{ $doctorant->user->email }}" 
                                               class="text-sm text-blue-600 hover:underline">
                                                {{ $doctorant->user->email }}
                                            </a>
                                        @else
                                            <span class="text-sm text-gray-400">Aucun compte</span>
                                        @endif
                                    </div>
                                    <div>
                                        <p class="text-xs text-gray-500 mb-1">Téléphone</p>
                                        <p class="text-sm text-gray-900">
                                            {{ $doctorant->phone ?? '-' }}
                                        </p>
                                    </div>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 pt-4 border-t border-gray-100">
                                    <div>
                                        <p class="text-xs text-gray-500 mb-1">Date de naissance</p>
                                        <p class="text-sm text-gray-900">
                                            @if($doctorant->date_naissance)
                                                {{ $doctorant->date_naissance->format('d/m/Y') }}
                                            @else
                                                -
                                            @endif
                                        </p>
                                    </div>
                                    <div>
                                        <p class="text-xs text-gray-500 mb-1">Lieu de naissance</p>
                                        <p class="text-sm text-gray-900">
                                            {{ $doctorant->lieu_naissance ?? '-' }}
                                        </p>
                                    </div>
                                    <div class="md:col-span-2">
                                        <p class="text-xs text-gray-500 mb-1">Adresse</p>
                                        <p class="text-sm text-gray-900">
                                            {{ $doctorant->adresse ?? '-' }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Card Thèse -->
                    <div class="bg-white rounded-xl shadow-lg p-6">
                        <h3 class="text-lg font-bold text-gray-900 mb-6 flex items-center gap-2">
                            <svg class="w-5 h-5 text-ed-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            Informations sur la thèse
                        </h3>

                        @if($theseActive)
                            {{-- Sujet --}}
                            <div class="mb-6">
                                <p class="text-xs text-gray-500 mb-2">Sujet de thèse</p>
                                <p class="text-sm text-gray-900 leading-relaxed bg-gray-50 p-4 rounded-lg">
                                    {{ $theseActive->sujet_these }}
                                </p>
                            </div>

                            {{-- Détails principaux --}}
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                                <div>
                                    <p class="text-xs text-gray-500 mb-2">École Doctorale (EAD)</p>
                                    <p class="text-sm text-gray-900">
                                        {{ $theseActive->ead?->nom ?? '-' }}
                                    </p>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-500 mb-2">Statut de la thèse</p>
                                    @if($theseActive->statut === 'en_cours')
                                        <span class="inline-block px-3 py-1 bg-green-100 text-green-700 rounded-lg text-xs font-bold">
                                            En cours
                                        </span>
                                    @elseif($theseActive->statut === 'soutendue')
                                        <span class="inline-block px-3 py-1 bg-blue-100 text-blue-700 rounded-lg text-xs font-bold">
                                            Soutenue
                                        </span>
                                    @elseif($theseActive->statut === 'abandonnee')
                                        <span class="inline-block px-3 py-1 bg-red-100 text-red-700 rounded-lg text-xs font-bold">
                                            Abandonnée
                                        </span>
                                    @else
                                        <span class="inline-block px-3 py-1 bg-gray-100 text-gray-700 rounded-lg text-xs font-bold">
                                            {{ $theseActive->statut }}
                                        </span>
                                    @endif
                                </div>
                                <div>
                                    <p class="text-xs text-gray-500 mb-2">Date de début</p>
                                    <p class="text-sm text-gray-900">
                                        {{ $theseActive->date_debut?->format('d/m/Y') ?? '-' }}
                                    </p>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-500 mb-2">Date prévue de fin</p>
                                    <p class="text-sm text-gray-900">
                                        {{ $theseActive->date_prevue_fin?->format('d/m/Y') ?? '-' }}
                                    </p>
                                </div>
                            </div>

                            {{-- Résumé & mots-clés --}}
                            @if($theseActive->resume_these)
                                <div class="mb-4">
                                    <p class="text-xs text-gray-500 mb-2">Résumé</p>
                                    <p class="text-sm text-gray-900 leading-relaxed bg-gray-50 p-4 rounded-lg">
                                        {{ $theseActive->resume_these }}
                                    </p>
                                </div>
                            @endif

                            @if($theseActive->mots_cles)
                                <div class="mb-6">
                                    <p class="text-xs text-gray-500 mb-2">Mots-clés</p>
                                    <div class="flex flex-wrap gap-2">
                                        @foreach(explode(',', $theseActive->mots_cles) as $mot)
                                            <span class="inline-flex px-2 py-1 rounded-full bg-indigo-50 text-indigo-700 text-xs font-semibold">
                                                {{ trim($mot) }}
                                            </span>
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            {{-- Encadrement --}}
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Directeur -->
                                <div>
                                    <p class="text-xs text-gray-500 mb-2">Directeur de thèse</p>
                                    @if($directeur && $directeur->user)
                                        <div class="flex items-center gap-3 p-3 bg-blue-50 rounded-lg">
                                            <div class="w-10 h-10 bg-blue-200 rounded-full flex items-center justify-center">
                                                <svg class="w-5 h-5 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/>
                                                </svg>
                                            </div>
                                            <div>
                                                <p class="text-sm font-semibold text-gray-900">{{ $directeur->user->name }}</p>
                                                @if($directeur->grade)
                                                    <p class="text-xs text-gray-600">{{ $directeur->grade }}</p>
                                                @endif
                                            </div>
                                        </div>
                                    @else
                                        <p class="text-sm text-gray-400">Non défini</p>
                                    @endif
                                </div>

                                <!-- Co-directeur -->
                                <div>
                                    <p class="text-xs text-gray-500 mb-2">Co-directeur</p>
                                    @if($codirecteur && $codirecteur->user)
                                        <div class="flex items-center gap-3 p-3 bg-purple-50 rounded-lg">
                                            <div class="w-10 h-10 bg-purple-200 rounded-full flex items-center justify-center">
                                                <svg class="w-5 h-5 text-purple-600" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/>
                                                </svg>
                                            </div>
                                            <div>
                                                <p class="text-sm font-semibold text-gray-900">{{ $codirecteur->user->name }}</p>
                                                @if($codirecteur->grade)
                                                    <p class="text-xs text-gray-600">{{ $codirecteur->grade }}</p>
                                                @endif
                                            </div>
                                        </div>
                                    @else
                                        <p class="text-sm text-gray-400">Non défini</p>
                                    @endif
                                </div>
                            </div>
                        @else
                            <div class="p-4 bg-yellow-50 border border-yellow-200 rounded-lg">
                                <p class="text-sm text-yellow-800 font-semibold">
                                    Aucune thèse enregistrée pour ce doctorant.
                                </p>
                                <p class="text-xs text-yellow-700 mt-1">
                                    Vous pouvez créer une thèse pour ce doctorant depuis le menu 
                                    <strong>Thèses &gt; Nouvelle thèse</strong>.
                                </p>
                            </div>
                        @endif
                    </div>

                    <!-- Card Thèses enregistrées -->
                    @if($doctorant->theses->count() > 0)
                        <div class="bg-white rounded-xl shadow-lg p-6">
                            <h3 class="text-lg font-bold text-gray-900 mb-4">Toutes les thèses du doctorant</h3>
                            <div class="space-y-3">
                                @foreach($doctorant->theses as $these)
                                    <div class="p-4 bg-gray-50 rounded-lg">
                                        <div class="flex justify-between items-start mb-2">
                                            <h4 class="font-semibold text-gray-900">
                                                {{ \Illuminate\Support\Str::limit($these->sujet_these ?? 'Sujet non défini', 120) }}
                                            </h4>
                                            <div>
                                                @if($these->statut === 'en_cours')
                                                    <span class="px-2 py-1 bg-green-100 text-green-700 rounded text-xs font-bold">
                                                        En cours
                                                    </span>
                                                @elseif($these->statut === 'soutendue')
                                                    <span class="px-2 py-1 bg-blue-100 text-blue-700 rounded text-xs font-bold">
                                                        Soutenue
                                                    </span>
                                                @elseif($these->statut === 'abandonnee')
                                                    <span class="px-2 py-1 bg-red-100 text-red-700 rounded text-xs font-bold">
                                                        Abandonnée
                                                    </span>
                                                @else
                                                    <span class="px-2 py-1 bg-gray-100 text-gray-700 rounded text-xs font-bold">
                                                        {{ $these->statut }}
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="text-xs text-gray-600 space-x-4">
                                            <span>
                                                <strong>EAD :</strong> {{ $these->ead?->nom ?? '-' }}
                                            </span>
                                            <span>
                                                <strong>Début :</strong> {{ $these->date_debut?->format('d/m/Y') ?? '-' }}
                                            </span>
                                            <span>
                                                <strong>Fin prévue :</strong> {{ $these->date_prevue_fin?->format('d/m/Y') ?? '-' }}
                                            </span>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Sidebar -->
                <div class="space-y-6">
                    
                    <!-- Card Statut -->
                    <div class="bg-white rounded-xl shadow-lg p-6">
                        <h3 class="text-sm font-bold text-gray-500 uppercase mb-4">Statut</h3>
                        
                        <div class="space-y-4">
                            <div>
                                <p class="text-xs text-gray-500 mb-2">Niveau</p>
                                <span class="inline-block px-3 py-1 bg-blue-100 text-blue-700 rounded-lg text-sm font-bold">
                                    {{ $doctorant->niveau }}
                                </span>
                            </div>

                            <div>
                                <p class="text-xs text-gray-500 mb-2">Statut académique</p>
                                @if($doctorant->statut === 'actif')
                                    <span class="inline-block px-3 py-1 bg-green-100 text-green-700 rounded-lg text-xs font-bold">
                                        Actif
                                    </span>
                                @elseif($doctorant->statut === 'diplome')
                                    <span class="inline-block px-3 py-1 bg-blue-100 text-blue-700 rounded-lg text-xs font-bold">
                                        Diplômé
                                    </span>
                                @elseif($doctorant->statut === 'suspendu')
                                    <span class="inline-block px-3 py-1 bg-orange-100 text-orange-700 rounded-lg text-xs font-bold">
                                        Suspendu
                                    </span>
                                @else
                                    <span class="inline-block px-3 py-1 bg-red-100 text-red-700 rounded-lg text-xs font-bold">
                                        Abandonné
                                    </span>
                                @endif
                            </div>

                            <div>
                                <p class="text-xs text-gray-500 mb-2">Date d'inscription</p>
                                <p class="text-sm font-semibold text-gray-900">
                                    {{ $doctorant->date_inscription->format('d/m/Y') }}
                                </p>
                            </div>

                            <div>
                                <p class="text-xs text-gray-500 mb-2">Compte utilisateur</p>
                                @if($doctorant->user)
                                    <span class="inline-flex items-center gap-1 px-3 py-1 bg-green-100 text-green-700 rounded-lg text-sm font-bold">
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                        </svg>
                                        Actif
                                    </span>
                                @else
                                    <span class="inline-block px-3 py-1 bg-gray-100 text-gray-700 rounded-lg text-sm font-bold">
                                        Aucun compte
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Card EAD (via la thèse) -->
                    @php
                        $eadSide = $theseActive?->ead ?? $doctorant->theses->first()?->ead;
                    @endphp

                    @if($eadSide)
                        <div class="bg-white rounded-xl shadow-lg p-6">
                            <h3 class="text-sm font-bold text-gray-500 uppercase mb-4">EAD</h3>
                            <div class="p-4 bg-gradient-to-br from-blue-50 to-blue-100 rounded-lg">
                                <p class="text-sm font-bold text-gray-900 mb-1">{{ $eadSide->nom }}</p>
                                @if($eadSide->responsable && $eadSide->responsable->user)
                                    <p class="text-xs text-gray-600">
                                        Responsable : {{ $eadSide->responsable->user->name }}
                                    </p>
                                @endif
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
