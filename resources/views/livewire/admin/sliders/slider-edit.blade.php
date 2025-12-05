<div>
    <!-- Breadcrumb -->
    <nav class="mb-6">
        <ol class="flex items-center gap-2 text-sm text-gray-600">
            <li><a href="{{ route('admin.dashboard') }}" class="hover:text-ed-primary">Dashboard</a></li>
            <li>/</li>
            <li><a href="{{ route('admin.sliders.index') }}" class="hover:text-ed-primary">Sliders</a></li>
            <li>/</li>
            <li class="text-gray-900 font-semibold">{{ $slider->nom }}</li>
        </ol>
    </nav>

    <!-- Formulaire -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <form wire:submit.prevent="update">
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
            <div class="flex justify-between items-center pt-6 border-t">
                <a href="{{ route('admin.slides.index', $slider) }}" 
                   class="inline-flex items-center gap-2 px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    Gérer les slides
                </a>
                
                <div class="flex gap-4">
                    <a href="{{ route('admin.sliders.index') }}" 
                       class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition">
                        Annuler
                    </a>
                    <button type="submit" 
                            class="px-6 py-2 bg-ed-primary text-white rounded-lg hover:bg-ed-secondary transition font-bold">
                        Mettre à jour
                    </button>
                </div>
            </div>
        </form>
    </div>

    <!-- Statistiques du slider -->
    <div class="mt-6 grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Total Slides</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $slider->slides()->count() }}</p>
                </div>
                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Slides Visibles</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $slider->slides()->visible()->count() }}</p>
                </div>
                <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Position</p>
                    <p class="text-2xl font-bold text-gray-900 capitalize">{{ $slider->position }}</p>
                </div>
                <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                </div>
            </div>
        </div>
    </div>
</div>