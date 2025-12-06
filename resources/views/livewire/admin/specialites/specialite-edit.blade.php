<div>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <a href="{{ route('admin.specialites.index') }}" 
               class="text-gray-600 hover:text-gray-900">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
            </a>
            <h2 class="text-2xl font-bold text-gray-800">Modifier la Spécialité</h2>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <!-- Stats -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                <div class="bg-white rounded-xl shadow-lg p-6 text-center">
                    <div class="text-3xl font-bold text-blue-600">{{ $specialite->theses->count() }}</div>
                    <div class="text-sm text-gray-600 mt-1">Thèses totales</div>
                </div>
                <div class="bg-white rounded-xl shadow-lg p-6 text-center">
                    <div class="text-3xl font-bold text-green-600">{{ $thesesEnCours }}</div>
                    <div class="text-sm text-gray-600 mt-1">En cours</div>
                </div>
                <div class="bg-white rounded-xl shadow-lg p-6 text-center">
                    <div class="text-3xl font-bold text-purple-600">{{ $thesesSoutenues }}</div>
                    <div class="text-sm text-gray-600 mt-1">Soutenues</div>
                </div>
            </div>

            <form wire:submit="save">
                <div class="bg-white rounded-xl shadow-lg p-8 space-y-6">
                    
                    <!-- Nom -->
                    <div>
                        <label for="nom" class="block text-sm font-bold text-gray-700 mb-2">
                            Nom de la spécialité <span class="text-red-500">*</span>
                        </label>
                        <input type="text" 
                               id="nom"
                               wire:model="nom"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-ed-primary focus:border-transparent @error('nom') border-red-500 @enderror"
                               placeholder="Ex: Biodiversité et Environnement">
                        @error('nom')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- EAD -->
                    <div>
                        <label for="ead_id" class="block text-sm font-bold text-gray-700 mb-2">
                            Équipe d'Accueil Doctoral (EAD) <span class="text-red-500">*</span>
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

                    <!-- Description -->
                    <div>
                        <label for="description" class="block text-sm font-bold text-gray-700 mb-2">
                            Description <span class="text-red-500">*</span>
                        </label>
                        <textarea id="description"
                                  wire:model="description"
                                  rows="6"
                                  class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-ed-primary focus:border-transparent @error('description') border-red-500 @enderror"
                                  placeholder="Décrivez la spécialité, ses objectifs, ses thématiques de recherche..."></textarea>
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
                        <a href="{{ route('admin.specialites.index') }}"
                           class="px-6 py-3 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300 transition font-semibold">
                            Annuler
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>