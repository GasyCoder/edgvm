<div>
    <!-- Breadcrumb -->
    <nav class="mb-6">
        <ol class="flex items-center gap-2 text-sm text-gray-600">
            <li><a href="{{ route('admin.dashboard') }}" class="hover:text-ed-primary">Dashboard</a></li>
            <li>/</li>
            <li><a href="{{ route('admin.sliders.index') }}" class="hover:text-ed-primary">Sliders</a></li>
            <li>/</li>
            <li class="text-gray-900 font-semibold">Créer</li>
        </ol>
    </nav>

    <!-- Formulaire -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <form wire:submit.prevent="save">
            <!-- Nom du slider -->
            <div class="mb-6">
                <label for="nom" class="block text-sm font-medium text-gray-700 mb-2">
                    Nom du slider <span class="text-red-500">*</span>
                </label>
                <input type="text" 
                       id="nom" 
                       wire:model="nom" 
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-ed-primary focus:border-transparent @error('nom') border-red-500 @enderror"
                       placeholder="Ex: Slider Homepage">
                @error('nom')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Position -->
            <div class="mb-6">
                <label for="position" class="block text-sm font-medium text-gray-700 mb-2">
                    Position <span class="text-red-500">*</span>
                </label>
                <select id="position" 
                        wire:model="position" 
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-ed-primary focus:border-transparent @error('position') border-red-500 @enderror">
                    <option value="homepage">Page d'accueil</option>
                    <option value="about">À propos</option>
                    <option value="programmes">Programmes</option>
                    <option value="contact">Contact</option>
                </select>
                @error('position')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
                <p class="mt-1 text-sm text-gray-500">Où ce slider sera affiché sur le site</p>
            </div>

            <!-- Visible -->
            <div class="mb-6">
                <label class="flex items-center gap-3 cursor-pointer">
                    <input type="checkbox" 
                           wire:model="visible" 
                           class="w-5 h-5 text-ed-primary border-gray-300 rounded focus:ring-2 focus:ring-ed-primary">
                    <span class="text-sm font-medium text-gray-700">Visible sur le site</span>
                </label>
                <p class="mt-1 text-sm text-gray-500 ml-8">Décocher pour masquer le slider temporairement</p>
            </div>

            <!-- Boutons -->
            <div class="flex justify-end gap-4 pt-6 border-t">
                <a href="{{ route('admin.sliders.index') }}" 
                   class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition">
                    Annuler
                </a>
                <button type="submit" 
                        class="px-6 py-2 bg-ed-primary text-white rounded-lg hover:bg-ed-secondary transition font-bold">
                    Créer le slider
                </button>
            </div>
        </form>
    </div>
</div>