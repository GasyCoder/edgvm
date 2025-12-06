<div>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <a href="{{ route('admin.doctorants.index') }}" 
               class="p-2 hover:bg-gray-100 rounded-lg transition">
                <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
            </a>
            <h2 class="text-2xl font-bold text-gray-800">Nouveau Doctorant</h2>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            <form wire:submit.prevent="save">
                <div class="bg-white rounded-xl shadow-lg p-8 space-y-8">
                    
                    <!-- Section Identité -->
                    <div class="border-b border-gray-200 pb-6">
                        <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                            <svg class="w-5 h-5 text-ed-primary" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/>
                            </svg>
                            Identité
                        </h3>
                        
                            <!-- Nom -->
                        <div class="md:col-span-3 pb-6">
                            <label for="name" class="block text-sm font-bold text-gray-700 mb-2">
                                Nom complet <span class="text-red-500">*</span>
                            </label>
                            <input type="text" 
                                    id="name"
                                    wire:model="name"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-ed-primary focus:border-transparent @error('name') border-red-500 @enderror">
                            @error('name')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Matricule -->
                            <div>
                                <label for="matricule" class="block text-sm font-bold text-gray-700 mb-2">
                                    Matricule <span class="text-red-500">*</span>
                                </label>
                                <input type="text" 
                                    id="matricule"
                                    wire:model="matricule"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-ed-primary focus:border-transparent @error('matricule') border-red-500 @enderror"
                                    placeholder="Ex: DOC2024001">
                                @error('matricule')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Email -->
                            <div>
                                <label for="email" class="block text-sm font-bold text-gray-700 mb-2">
                                    Email <span class="text-red-500">*</span>
                                </label>
                                <input type="email" 
                                    id="email"
                                    wire:model="email"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-ed-primary focus:border-transparent @error('email') border-red-500 @enderror"
                                    placeholder="exemple@univ-mahajanga.mg">
                                @error('email')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                                <p class="mt-1 text-xs text-gray-500">
                                    💡 Un compte utilisateur pourra être créé plus tard avec cet email
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Section Informations Personnelles -->
                    <div class="border-b border-gray-200 pb-6">
                        <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                            <svg class="w-5 h-5 text-ed-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                            Informations personnelles
                        </h3>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Date de naissance -->
                            <div>
                                <label for="date_naissance" class="block text-sm font-bold text-gray-700 mb-2">
                                    Date de naissance
                                </label>
                                <input type="date" 
                                       id="date_naissance"
                                       wire:model="date_naissance"
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-ed-primary focus:border-transparent @error('date_naissance') border-red-500 @enderror">
                                @error('date_naissance')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Lieu de naissance -->
                            <div>
                                <label for="lieu_naissance" class="block text-sm font-bold text-gray-700 mb-2">
                                    Lieu de naissance
                                </label>
                                <input type="text" 
                                       id="lieu_naissance"
                                       wire:model="lieu_naissance"
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-ed-primary focus:border-transparent @error('lieu_naissance') border-red-500 @enderror"
                                       placeholder="Ex: Mahajanga">
                                @error('lieu_naissance')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Téléphone -->
                            <div>
                                <label for="phone" class="block text-sm font-bold text-gray-700 mb-2">
                                    Téléphone
                                </label>
                                <input type="tel" 
                                       id="phone"
                                       wire:model="phone"
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-ed-primary focus:border-transparent @error('phone') border-red-500 @enderror"
                                       placeholder="+261 32 00 000 00">
                                @error('phone')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Adresse -->
                            <div>
                                <label for="adresse" class="block text-sm font-bold text-gray-700 mb-2">
                                    Adresse
                                </label>
                                <input type="text" 
                                       id="adresse"
                                       wire:model="adresse"
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-ed-primary focus:border-transparent @error('adresse') border-red-500 @enderror"
                                       placeholder="Ex: Mahajanga, Madagascar">
                                @error('adresse')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Section Informations Académiques -->
                    <div class="border-b border-gray-200 pb-6">
                        <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                            <svg class="w-5 h-5 text-ed-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                            </svg>
                            Informations académiques
                        </h3>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Niveau -->
                            <div>
                                <label for="niveau" class="block text-sm font-bold text-gray-700 mb-2">
                                    Niveau <span class="text-red-500">*</span>
                                </label>
                                <select id="niveau"
                                        wire:model="niveau"
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-ed-primary focus:border-transparent @error('niveau') border-red-500 @enderror">
                                    <option value="D1">D1 (Doctorat 1ère année)</option>
                                    <option value="D2">D2 (Doctorat 2ème année)</option>
                                    <option value="D3">D3 (Doctorat 3ème année)</option>
                                </select>
                                @error('niveau')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Date d'inscription -->
                            <div>
                                <label for="date_inscription" class="block text-sm font-bold text-gray-700 mb-2">
                                    Date d'inscription <span class="text-red-500">*</span>
                                </label>
                                <input type="date" 
                                       id="date_inscription"
                                       wire:model="date_inscription"
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-ed-primary focus:border-transparent @error('date_inscription') border-red-500 @enderror">
                                @error('date_inscription')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Statut -->
                            <div>
                                <label for="statut" class="block text-sm font-bold text-gray-700 mb-2">
                                    Statut <span class="text-red-500">*</span>
                                </label>
                                <select id="statut"
                                        wire:model="statut"
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-ed-primary focus:border-transparent @error('statut') border-red-500 @enderror">
                                    <option value="actif">Actif</option>
                                    <option value="diplome">Diplômé</option>
                                    <option value="suspendu">Suspendu</option>
                                    <option value="abandonne">Abandonné</option>
                                </select>
                                @error('statut')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- EAD -->
                            <div>
                                <label for="ead_id" class="block text-sm font-bold text-gray-700 mb-2">
                                    EAD <span class="text-red-500">*</span>
                                </label>
                                <select id="ead_id"
                                        wire:model="ead_id"
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-ed-primary focus:border-transparent @error('ead_id') border-red-500 @enderror">
                                    <option value="">-- Sélectionner une EAD --</option>
                                    @foreach($eads as $ead)
                                    <option value="{{ $ead->id }}">{{ $ead->nom }}</option>
                                    @endforeach
                                </select>
                                @error('ead_id')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Section Thèse -->
                    <div>
                        <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                            <svg class="w-5 h-5 text-ed-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            Informations sur la thèse
                        </h3>
                        
                        <div class="space-y-6">
                            <!-- Sujet de thèse -->
                            <div>
                                <label for="sujet_these" class="block text-sm font-bold text-gray-700 mb-2">
                                    Sujet de thèse
                                </label>
                                <textarea id="sujet_these"
                                          wire:model="sujet_these"
                                          rows="3"
                                          class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-ed-primary focus:border-transparent @error('sujet_these') border-red-500 @enderror"
                                          placeholder="Ex: Étude de l'impact des changements climatiques sur..."></textarea>
                                @error('sujet_these')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Directeur de thèse -->
                                <div>
                                    <label for="directeur_id" class="block text-sm font-bold text-gray-700 mb-2">
                                        Directeur de thèse <span class="text-red-500">*</span>
                                    </label>
                                    <select id="directeur_id"
                                            wire:model="directeur_id"
                                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-ed-primary focus:border-transparent @error('directeur_id') border-red-500 @enderror">
                                        <option value="">-- Sélectionner un directeur --</option>
                                        @foreach($encadrants as $encadrant)
                                        <option value="{{ $encadrant->id }}">
                                            {{ $encadrant->user->name }}
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

                                <!-- Co-directeur -->
                                <div>
                                    <label for="codirecteur_id" class="block text-sm font-bold text-gray-700 mb-2">
                                        Co-directeur
                                    </label>
                                    <select id="codirecteur_id"
                                            wire:model="codirecteur_id"
                                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-ed-primary focus:border-transparent @error('codirecteur_id') border-red-500 @enderror">
                                        <option value="">-- Sélectionner un co-directeur --</option>
                                        @foreach($encadrants as $encadrant)
                                        <option value="{{ $encadrant->id }}">
                                            {{ $encadrant->user->name }}
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
                    </div>

                    <!-- Boutons -->
                    <div class="flex gap-4 pt-6">
                        <button type="submit"
                                class="flex-1 px-6 py-3 bg-ed-primary text-white rounded-lg hover:bg-ed-secondary transition font-bold shadow-lg">
                            Créer le doctorant
                        </button>
                        <a href="{{ route('admin.doctorants.index') }}" 
                           class="px-6 py-3 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300 transition font-semibold">
                            Annuler
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>