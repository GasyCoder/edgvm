<div>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <a href="{{ route('admin.ead.index') }}" 
               class="text-gray-600 hover:text-gray-900">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
            </a>
            <h2 class="text-2xl font-bold text-gray-800">Modifier l'EAD</h2>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <!-- Stats -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                <div class="bg-white rounded-xl shadow-lg p-6 text-center">
                    <div class="text-3xl font-bold text-blue-600">{{ $specialitesCount }}</div>
                    <div class="text-sm text-gray-600 mt-1">Spécialités</div>
                </div>
                <div class="bg-white rounded-xl shadow-lg p-6 text-center">
                    <div class="text-3xl font-bold text-green-600">{{ $thesesEnCours }}</div>
                    <div class="text-sm text-gray-600 mt-1">Thèses en cours</div>
                </div>
                <div class="bg-white rounded-xl shadow-lg p-6 text-center">
                    <div class="text-3xl font-bold text-purple-600">{{ $thesesSoutenues }}</div>
                    <div class="text-sm text-gray-600 mt-1">Thèses soutenues</div>
                </div>
            </div>

            <form wire:submit="save">
                <div class="bg-white rounded-xl shadow-lg p-8 space-y-6">
                    
                    <!-- Nom -->
                    <div>
                        <label for="nom" class="block text-sm font-bold text-gray-700 mb-2">
                            Nom de l'EAD <span class="text-red-500">*</span>
                        </label>
                        <input type="text" 
                               id="nom"
                               wire:model="nom"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-ed-primary focus:border-transparent @error('nom') border-red-500 @enderror"
                               placeholder="Ex: Génie du Vivant et Modélisation">
                        @error('nom')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Domaine -->
                    <div>
                        <label for="domaine" class="block text-sm font-bold text-gray-700 mb-2">
                            Domaine
                        </label>
                        <input type="text" 
                               id="domaine"
                               wire:model="domaine"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-ed-primary focus:border-transparent @error('domaine') border-red-500 @enderror"
                               placeholder="Ex: Sciences de la Vie et de l'Environnement">
                        @error('domaine')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Responsable -->
                    <div>
                        <label for="responsable_id" class="block text-sm font-bold text-gray-700 mb-2">
                            Responsable de l'EAD <span class="text-red-500">*</span>
                        </label>
                        <select id="responsable_id"
                                wire:model="responsable_id"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-ed-primary focus:border-transparent @error('responsable_id') border-red-500 @enderror">
                            <option value="">-- Sélectionner un responsable --</option>
                            @foreach($encadrants as $encadrant)
                            <option value="{{ $encadrant->id }}">
                                {{ $encadrant->user->name }}
                                @if($encadrant->grade)
                                - {{ $encadrant->grade }}
                                @endif
                            </option>
                            @endforeach
                        </select>
                        @error('responsable_id')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <!-- Description -->
                    <div>
                        <label for="description" class="block text-sm font-bold text-gray-700 mb-2">
                            Description <span class="text-red-500">*</span>
                        </label>
                        <textarea id="description"
                                  wire:model="description"
                                  rows="6"
                                  class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-ed-primary focus:border-transparent @error('description') border-red-500 @enderror"
                                  placeholder="Décrivez l'EAD, ses missions, ses axes de recherche..."></textarea>
                        @error('description')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Boutons -->
                    <div class="flex gap-4 pt-6 border-t">
                        <button type="submit"
                                class="flex-1 px-6 py-3 bg-ed-primary text-white rounded-lg hover:bg-ed-secondary transition font-bold shadow-lg">
                            ✅ Enregistrer les modifications
                        </button>
                        <a href="{{ route('admin.ead.index') }}"
                           class="px-6 py-3 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300 transition font-semibold">
                            Annuler
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>