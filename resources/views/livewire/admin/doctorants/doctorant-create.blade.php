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
                    
                    {{-- Section Identité & Compte Utilisateur --}}
                    <div class="border-b border-gray-200 pb-6">
                        <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                            <svg class="w-5 h-5 text-ed-primary" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/>
                            </svg>
                            Identité & Compte Utilisateur
                        </h3>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            {{-- Nom complet --}}
                            <div class="md:col-span-2">
                                <label for="name" class="block text-sm font-bold text-gray-700 mb-2">
                                    Nom complet <span class="text-red-500">*</span>
                                </label>
                                <input type="text" 
                                       id="name"
                                       wire:model="name"
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-ed-primary focus:border-transparent @error('name') border-red-500 @enderror"
                                       placeholder="Ex: Jean RAKOTO">
                                @error('name')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Matricule --}}
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

                            {{-- Email --}}
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
                            </div>
                        </div>

                        {{-- Info compte auto --}}
                        <div class="mt-4 p-4 bg-blue-50 rounded-lg border border-blue-200">
                            <div class="flex items-start gap-3">
                                <svg class="w-5 h-5 text-blue-600 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                                </svg>
                                <div>
                                    <p class="text-sm font-semibold text-blue-900">Compte utilisateur automatique</p>
                                    <p class="text-sm text-blue-700 mt-1">
                                        Un compte sera créé automatiquement avec le mot de passe par défaut : 
                                        <code class="px-2 py-1 bg-blue-100 rounded font-mono text-xs">
                                            doctorant123
                                        </code>
                                    </p>
                                    <p class="text-xs text-blue-600 mt-1">
                                        Le doctorant pourra le modifier lors de sa première connexion.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Section Informations Personnelles --}}
                    <div class="border-b border-gray-200 pb-6">
                        <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                            <svg class="w-5 h-5 text-ed-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                            Informations personnelles
                        </h3>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            {{-- Date de naissance --}}
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

                            {{-- Lieu de naissance --}}
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

                            {{-- Téléphone --}}
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

                            {{-- Adresse (textarea) --}}
                            <div class="md:col-span-2">
                                <label for="adresse" class="block text-sm font-bold text-gray-700 mb-2">
                                    Adresse
                                </label>
                                <textarea 
                                    id="adresse"
                                    wire:model="adresse"
                                    rows="3"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-ed-primary focus:border-transparent @error('adresse') border-red-500 @enderror"
                                    placeholder="Ex: Quartier, Ville, Pays"></textarea>
                                @error('adresse')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    {{-- Section Informations Académiques --}}
                    <div class="border-b border-gray-200 pb-6">
                        <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                            <svg class="w-5 h-5 text-ed-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                            </svg>
                            Informations académiques
                        </h3>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            {{-- Niveau --}}
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

                            {{-- Date d'inscription --}}
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

                            {{-- Statut en buttons --}}
                            <div class="md:col-span-2">
                                <label class="block text-sm font-bold text-gray-700 mb-2">
                                    Statut <span class="text-red-500">*</span>
                                </label>

                                <div class="flex flex-wrap gap-3">
                                    {{-- Actif --}}
                                    <button type="button"
                                            wire:click="$set('statut', 'actif')"
                                            class="px-3 py-2 rounded-lg border text-sm font-semibold transition
                                                @if($statut === 'actif')
                                                    bg-green-100 text-green-700 border-green-500 shadow-sm
                                                @else
                                                    bg-white text-gray-700 border-gray-300 hover:bg-gray-50
                                                @endif">
                                        Actif
                                    </button>

                                    {{-- Diplômé --}}
                                    <button type="button"
                                            wire:click="$set('statut', 'diplome')"
                                            class="px-3 py-2 rounded-lg border text-sm font-semibold transition
                                                @if($statut === 'diplome')
                                                    bg-blue-100 text-blue-700 border-blue-500 shadow-sm
                                                @else
                                                    bg-white text-gray-700 border-gray-300 hover:bg-gray-50
                                                @endif">
                                        Diplômé
                                    </button>

                                    {{-- Suspendu --}}
                                    <button type="button"
                                            wire:click="$set('statut', 'suspendu')"
                                            class="px-3 py-2 rounded-lg border text-sm font-semibold transition
                                                @if($statut === 'suspendu')
                                                    bg-orange-100 text-orange-700 border-orange-500 shadow-sm
                                                @else
                                                    bg-white text-gray-700 border-gray-300 hover:bg-gray-50
                                                @endif">
                                        Suspendu
                                    </button>

                                    {{-- Abandonné --}}
                                    <button type="button"
                                            wire:click="$set('statut', 'abandonne')"
                                            class="px-3 py-2 rounded-lg border text-sm font-semibold transition
                                                @if($statut === 'abandonne')
                                                    bg-red-100 text-red-700 border-red-500 shadow-sm
                                                @else
                                                    bg-white text-gray-700 border-gray-300 hover:bg-gray-50
                                                @endif">
                                        Abandonné
                                    </button>
                                </div>

                                @error('statut')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    {{-- Boutons --}}
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