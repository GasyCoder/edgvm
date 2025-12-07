<div>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <a href="{{ route('admin.theses.index') }}" 
               class="p-2 hover:bg-gray-100 rounded-lg transition">
                <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
            </a>
            <h2 class="text-2xl font-bold text-gray-800">Nouvelle Thèse</h2>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            
            {{-- Messages flash --}}
            @if (session()->has('error'))
                <div class="mb-6 p-4 bg-red-100 border-l-4 border-red-500 text-red-700 rounded">
                    {{ session('error') }}
                </div>
            @endif

            @if (session()->has('success'))
                <div class="mb-6 p-4 bg-green-100 border-l-4 border-green-500 text-green-700 rounded">
                    {{ session('success') }}
                </div>
            @endif

            <form wire:submit.prevent="save">
                <div class="bg-white rounded-xl shadow-lg p-8 space-y-8">
                    
                    {{-- SECTION DOCTORANT --}}
                    <div class="border-b border-gray-200 pb-6">
                        <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                            <svg class="w-5 h-5 text-ed-primary" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z"/>
                            </svg>
                            Doctorant
                        </h3>
                        
                        <div class="space-y-4">
                            <div>
                                <label for="doctorant_id" class="block text-sm font-bold text-gray-700 mb-2">
                                    Sélectionner le doctorant <span class="text-red-500">*</span>
                                </label>
                                <select id="doctorant_id"
                                        wire:model="doctorant_id"
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-ed-primary focus:border-transparent @error('doctorant_id') border-red-500 @enderror">
                                    <option value="">-- Choisir un doctorant --</option>
                                    @foreach($doctorants as $doctorant)
                                        <option value="{{ $doctorant->id }}">
                                            {{ $doctorant->user?->name ?? 'Pas de compte' }} - {{ $doctorant->matricule }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('doctorant_id')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            @if($doctorants->isEmpty())
                                <div class="mt-3 p-3 bg-yellow-50 rounded-lg border border-yellow-200">
                                    <p class="text-sm text-yellow-800">
                                        ⚠️ Aucun doctorant disponible. Tous les doctorants ont déjà une thèse en cours.
                                    </p>
                                </div>
                            @endif
                        </div>
                    </div>

                    {{-- SECTION ENCADREMENT --}}
                    <div class="border-b border-gray-200 pb-6">
                        <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                            <svg class="w-5 h-5 text-ed-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                            </svg>
                            Encadrement
                        </h3>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            {{-- Directeur --}}
                            <div>
                                <label for="directeur_id" class="block text-sm font-bold text-gray-700 mb-2">
                                    Directeur de thèse <span class="text-red-500">*</span>
                                </label>
                                <select id="directeur_id"
                                        wire:model="directeur_id"
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-ed-primary focus:border-transparent @error('directeur_id') border-red-500 @enderror">
                                    <option value="">-- Choisir un directeur --</option>
                                    @foreach($encadrants as $encadrant)
                                        <option value="{{ $encadrant->id }}">
                                            {{ $encadrant->user?->name ?? 'Pas de compte' }}
                                            @if($encadrant->grade)
                                                ({{ $encadrant->grade }})
                                            @endif
                                        </option>
                                    @endforeach
                                </select>
                                @error('directeur_id')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Co-directeur --}}
                            <div>
                                <label for="codirecteur_id" class="block text-sm font-bold text-gray-700 mb-2">
                                    Co-directeur de thèse <span class="text-gray-400">(optionnel)</span>
                                </label>
                                <select id="codirecteur_id"
                                        wire:model="codirecteur_id"
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-ed-primary focus:border-transparent @error('codirecteur_id') border-red-500 @enderror">
                                    <option value="">-- Aucun co-directeur --</option>
                                    @foreach($encadrants as $encadrant)
                                        <option value="{{ $encadrant->id }}">
                                            {{ $encadrant->user?->name ?? 'Pas de compte' }}
                                            @if($encadrant->grade)
                                                ({{ $encadrant->grade }})
                                            @endif
                                        </option>
                                    @endforeach
                                </select>
                                @error('codirecteur_id')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    {{-- SECTION DÉTAILS DE LA THÈSE --}}
                    <div class="border-b border-gray-200 pb-6">
                        <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                            <svg class="w-5 h-5 text-ed-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            Détails de la thèse
                        </h3>

                        {{-- Sujet de la thèse --}}
                        <div class="mb-6">
                            <label for="sujet_these" class="block text-sm font-bold text-gray-700 mb-2">
                                Sujet de la thèse <span class="text-red-500">*</span>
                            </label>
                            <textarea id="sujet_these"
                                      wire:model="sujet_these"
                                      rows="3"
                                      class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-ed-primary focus:border-transparent @error('sujet_these') border-red-500 @enderror"
                                      placeholder="Ex : Gestion des risques environnementaux liés à l’usage des pesticides à Ambatoboeny..."></textarea>
                            @error('sujet_these')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            {{-- EAD --}}
                            <div>
                                <label for="ead_id" class="block text-sm font-bold text-gray-700 mb-2">
                                    École Doctorale (EAD)
                                </label>
                                <select id="ead_id"
                                        wire:model="ead_id"
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-ed-primary focus:border-transparent @error('ead_id') border-red-500 @enderror">
                                    <option value="">-- Sélectionner une EAD --</option>
                                    @foreach($eads as $ead)
                                        <option value="{{ $ead->id }}">{{ Str::limit($ead->nom, 25) }}</option>
                                    @endforeach
                                </select>
                                @error('ead_id')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Statut --}}
                            <div>
                                <label for="statut" class="block text-sm font-bold text-gray-700 mb-2">
                                    Statut <span class="text-red-500">*</span>
                                </label>
                                <select id="statut"
                                        wire:model="statut"
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-ed-primary focus:border-transparent @error('statut') border-red-500 @enderror">
                                    <option value="en_cours">En cours</option>
                                    <option value="soutenue">Soutenue</option>
                                    <option value="abandonnee">Abandonnée</option>
                                    <option value="suspendue">Suspendue</option>
                                </select>
                                @error('statut')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Date de début --}}
                            <div>
                                <label for="date_debut" class="block text-sm font-bold text-gray-700 mb-2">
                                    Date de début
                                </label>
                                <input type="date" 
                                       id="date_debut"
                                       wire:model="date_debut"
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-ed-primary focus:border-transparent @error('date_debut') border-red-500 @enderror">
                                @error('date_debut')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Date de fin prévue --}}
                            <div>
                                <label for="date_fin_prevue" class="block text-sm font-bold text-gray-700 mb-2">
                                    Date de fin prévue
                                </label>
                                <input type="date" 
                                       id="date_fin_prevue"
                                       wire:model="date_fin_prevue"
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-ed-primary focus:border-transparent @error('date_fin_prevue') border-red-500 @enderror">
                                @error('date_fin_prevue')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    {{-- SECTION PUBLICATION & RÉSUMÉ --}}
                    <div class="border-b border-gray-200 pb-6">
                        <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                            <svg class="w-5 h-5 text-ed-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M19 11H5m7-7v14"/>
                            </svg>
                            Informations de publication
                        </h3>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            {{-- Université de soutenance --}}
                            <div>
                                <label for="universite_soutenance" class="block text-sm font-bold text-gray-700 mb-2">
                                    Université de soutenance
                                </label>
                                <input type="text"
                                       id="universite_soutenance"
                                       wire:model="universite_soutenance"
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-ed-primary focus:border-transparent @error('universite_soutenance') border-red-500 @enderror"
                                       placeholder="Ex : Université de Mahajanga (UMG)">
                                @error('universite_soutenance')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Date de publication / soutenance --}}
                            <div>
                                <label for="date_publication" class="block text-sm font-bold text-gray-700 mb-2">
                                    Date de publication / soutenance
                                </label>
                                <input type="date"
                                       id="date_publication"
                                       wire:model="date_publication"
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-ed-primary focus:border-transparent @error('date_publication') border-red-500 @enderror">
                                @error('date_publication')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        {{-- Résumé --}}
                        <div class="mb-6">
                            <label for="resume_these" class="block text-sm font-bold text-gray-700 mb-2">
                                Résumé de la thèse <span class="text-gray-400">(optionnel)</span>
                            </label>
                            <textarea id="resume_these"
                                      wire:model="resume_these"
                                      rows="4"
                                      class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-ed-primary focus:border-transparent @error('resume_these') border-red-500 @enderror"
                                      placeholder="Résumé scientifique de la thèse (200–300 mots)..."></textarea>
                            @error('resume_these')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Mots-clés --}}
                        <div>
                            <label for="mots_cles" class="block text-sm font-bold text-gray-700 mb-2">
                                Mots-clés <span class="text-gray-400">(séparés par des virgules)</span>
                            </label>
                            <input type="text"
                                   id="mots_cles"
                                   wire:model="mots_cles"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-ed-primary focus:border-transparent @error('mots_cles') border-red-500 @enderror"
                                   placeholder="Ex : changement climatique, pesticides, Ambatoboeny, risques environnementaux">
                            @error('mots_cles')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    {{-- SECTION FICHIER PDF --}}
                    <div class="border-b border-gray-200 pb-6">
                        <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                            <svg class="w-5 h-5 text-ed-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 12H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            Fichier PDF de la thèse
                        </h3>

                        {{-- Upload direct depuis l’ordinateur --}}
                        <div class="space-y-3">
                            <div>
                                <label for="media" class="block text-sm font-bold text-gray-700 mb-2">
                                    Importer un fichier PDF <span class="text-gray-400">(optionnel)</span>
                                </label>
                                <input type="file"
                                    id="media"
                                    wire:model="media"
                                    accept="application/pdf"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-ed-primary focus:border-transparent @error('media') border-red-500 @enderror">
                                @error('media')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror

                                <div wire:loading wire:target="media" class="mt-1 text-xs text-gray-500">
                                    Upload du fichier en cours...
                                </div>
                            </div>

                            <div class="flex items-center gap-3">
                                <span class="text-xs text-gray-500">
                                    Vous pouvez soit importer un PDF depuis votre ordinateur, soit choisir un fichier déjà présent
                                    dans la médiathèque.
                                </span>
                            </div>

                            {{-- Bouton pour ouvrir la médiathèque --}}
                            <div class="mt-2">
                                <button type="button"
                                        wire:click="openMediaLibrary"
                                        class="inline-flex items-center px-4 py-2 bg-indigo-50 text-indigo-700 rounded-lg hover:bg-indigo-100 border border-indigo-200 text-sm font-semibold">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M3 7h18M3 12h18M3 17h18"/>
                                    </svg>
                                    Choisir depuis la médiathèque
                                </button>

                                @if($media_id)
                                    <p class="mt-2 text-xs text-green-700">
                                        ✅ Un fichier a été sélectionné dans la médiathèque (ID : {{ $media_id }}).
                                    </p>
                                @endif
                            </div>
                        </div>
                    </div>

                    {{-- INFO IMPORTANTE --}}
                    <div class="p-4 bg-blue-50 rounded-lg border border-blue-200">
                        <div class="flex items-start gap-3">
                            <svg class="w-5 h-5 text-blue-600 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                            </svg>
                            <div>
                                <p class="text-sm font-semibold text-blue-900">À savoir</p>
                                <ul class="text-sm text-blue-700 mt-1 list-disc list-inside space-y-1">
                                    <li>Le sujet de thèse est défini au niveau de la thèse (formulaire ci-dessus).</li>
                                    <li>Un doctorant ne peut avoir qu'une seule thèse en cours.</li>
                                    <li>Le directeur de thèse est obligatoire, le co-directeur est optionnel.</li>
                                    <li>Les informations de publication (université, date, résumé, mots-clés) facilitent l’archivage et la diffusion.</li>
                                    <li>Le fichier PDF est facultatif et peut être ajouté ou modifié plus tard.</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    {{-- BOUTONS --}}
                    <div class="flex gap-4 pt-6">
                        <button type="submit"
                                class="flex-1 px-6 py-3 bg-ed-primary text-white rounded-lg hover:bg-ed-secondary transition font-bold shadow-lg">
                            Créer la thèse
                        </button>
                        <a href="{{ route('admin.theses.index') }}" 
                           class="px-6 py-3 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300 transition font-semibold">
                            Annuler
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- MODAL MÉDIATHÈQUE PDF --}}
    @if($showMediaLibrary)
        <div class="fixed inset-0 z-40 flex items-center justify-center bg-black bg-opacity-50">
            <div class="bg-white rounded-2xl shadow-2xl max-w-3xl w-full mx-4 max-h-[80vh] flex flex-col">
                <div class="flex items-center justify-between px-6 py-4 border-b">
                    <h3 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                        <svg class="w-5 h-5 text-ed-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 7h18M3 12h18M3 17h18"/>
                        </svg>
                        Médiathèque – Fichiers PDF
                    </h3>
                    <button type="button"
                            wire:click="closeMediaLibrary"
                            class="p-2 rounded-full hover:bg-gray-100">
                        <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                <div class="px-6 py-4 overflow-y-auto">
                    @if($mediaDocuments->count() > 0)
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            @foreach($mediaDocuments as $doc)
                                <div class="border rounded-lg p-3 flex flex-col justify-between bg-gray-50">
                                    <div class="flex items-start gap-3">
                                        <div class="w-10 h-10 flex items-center justify-center rounded bg-red-100">
                                            <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 4v16m0 0l-4-4m4 4l4-4"/>
                                            </svg>
                                        </div>
                                        <div class="flex-1">
                                            <p class="text-sm font-semibold text-gray-900 truncate">
                                                {{ $doc->nom_original }}
                                            </p>
                                            <p class="text-xs text-gray-500">
                                                {{ $doc->mime_type }} – {{ number_format(($doc->taille_bytes ?? 0) / 1024, 1, ',', ' ') }} Ko
                                            </p>
                                            <p class="text-xs text-gray-400 mt-1">
                                                Ajouté le {{ optional($doc->created_at)->format('d/m/Y H:i') }}
                                            </p>
                                        </div>
                                    </div>

                                    <div class="mt-3 flex justify-end">
                                        <button type="button"
                                                wire:click="selectMedia({{ $doc->id }})"
                                                class="px-3 py-1.5 text-xs bg-ed-primary text-white rounded-lg hover:bg-ed-secondary font-semibold">
                                            Utiliser ce fichier
                                        </button>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8">
                            <p class="text-sm text-gray-600">
                                Aucun fichier PDF de thèse n'est encore enregistré dans la médiathèque.
                            </p>
                            <p class="text-xs text-gray-500 mt-1">
                                Importez d’abord un PDF via le formulaire principal pour l’ajouter ici.
                            </p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    @endif
</div>
