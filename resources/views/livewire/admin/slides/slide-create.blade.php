<div>
    <!-- Breadcrumb -->
    <nav class="mb-6">
        <ol class="flex items-center gap-2 text-sm text-gray-600">
            <li><a href="{{ route('admin.dashboard') }}" class="hover:text-ed-primary">Dashboard</a></li>
            <li>/</li>
            <li><a href="{{ route('admin.sliders.index') }}" class="hover:text-ed-primary">Sliders</a></li>
            <li>/</li>
            <li><a href="{{ route('admin.slides.index', $slider) }}" class="hover:text-ed-primary">{{ $slider->nom }}</a></li>
            <li>/</li>
            <li class="text-gray-900 font-semibold">Créer un slide</li>
        </ol>
    </nav>

    <!-- Formulaire -->
    <form wire:submit.prevent="save">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Colonne principale -->
            <div class="lg:col-span-2 space-y-6">
                
                <!-- Section Titre -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Titre du slide</h3>
                    
                    <div class="grid grid-cols-3 gap-4">
                        <div>
                            <label for="titre_ligne1" class="block text-sm font-medium text-gray-700 mb-2">
                                Ligne 1 (avant)
                            </label>
                            <input type="text" 
                                   id="titre_ligne1" 
                                   wire:model="titre_ligne1" 
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-ed-primary focus:border-transparent"
                                   placeholder="Ex: Le">
                            <p class="mt-1 text-xs text-gray-500">Texte avant le highlight</p>
                        </div>
                        
                        <div>
                            <label for="titre_highlight" class="block text-sm font-medium text-gray-700 mb-2">
                                En surbrillance <span class="text-red-500">*</span>
                            </label>
                            <input type="text" 
                                   id="titre_highlight" 
                                   wire:model="titre_highlight" 
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-ed-primary focus:border-transparent @error('titre_highlight') border-red-500 @enderror"
                                   placeholder="Ex: Génie du Vivant">
                            @error('titre_highlight')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <p class="mt-1 text-xs text-gray-500">Texte en jaune</p>
                        </div>
                        
                        <div>
                            <label for="titre_ligne2" class="block text-sm font-medium text-gray-700 mb-2">
                                Ligne 2 (après)
                            </label>
                            <input type="text" 
                                   id="titre_ligne2" 
                                   wire:model="titre_ligne2" 
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-ed-primary focus:border-transparent"
                                   placeholder="Ex: au Service de l'Humanité">
                            <p class="mt-1 text-xs text-gray-500">Texte après le highlight</p>
                        </div>
                    </div>
                    
                    <!-- Aperçu du titre -->
                    <div class="mt-4 p-4 bg-gray-50 rounded-lg">
                        <p class="text-xs text-gray-500 mb-2">Aperçu :</p>
                        <p class="text-xl font-black text-gray-900">
                            @if($titre_ligne1){{ $titre_ligne1 }} @endif
                            <span class="text-ed-yellow">{{ $titre_highlight ?: 'Titre highlight' }}</span>
                            @if($titre_ligne2) {{ $titre_ligne2 }}@endif
                        </p>
                    </div>
                </div>

                <!-- Section Description -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Description</h3>
                    
                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                            Sous-titre du slide
                        </label>
                        <textarea id="description" 
                                  wire:model="description" 
                                  rows="3"
                                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-ed-primary focus:border-transparent @error('description') border-red-500 @enderror"
                                  placeholder="Ex: École Doctorale de recherche scientifique d'excellence"></textarea>
                        @error('description')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-sm text-gray-500">Maximum 500 caractères</p>
                    </div>
                </div>

                <!-- Section Badge -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Badge</h3>
                    
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="badge_texte" class="block text-sm font-medium text-gray-700 mb-2">
                                Texte du badge
                            </label>
                            <input type="text" 
                                   id="badge_texte" 
                                   wire:model="badge_texte" 
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-ed-primary focus:border-transparent"
                                   placeholder="Ex: Université de Mahajanga">
                        </div>
                        
                        <div>
                            <label for="badge_icon" class="block text-sm font-medium text-gray-700 mb-2">
                                Icône du badge
                            </label>
                            <select id="badge_icon" 
                                    wire:model="badge_icon" 
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-ed-primary focus:border-transparent">
                                <option value="university">Université (point)</option>
                                <option value="research">Recherche (équipes)</option>
                                <option value="students">Étudiants (graduation)</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Section CTA -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Call-to-Action (CTA)</h3>
                    
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="texte_cta" class="block text-sm font-medium text-gray-700 mb-2">
                                Texte du bouton
                            </label>
                            <input type="text" 
                                   id="texte_cta" 
                                   wire:model="texte_cta" 
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-ed-primary focus:border-transparent"
                                   placeholder="Ex: Candidater maintenant">
                        </div>
                        
                        <div>
                            <label for="lien_cta" class="block text-sm font-medium text-gray-700 mb-2">
                                Lien du bouton
                            </label>
                            <input type="text" 
                                   id="lien_cta" 
                                   wire:model="lien_cta" 
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-ed-primary focus:border-transparent @error('lien_cta') border-red-500 @enderror"
                                   placeholder="https://...">
                            @error('lien_cta')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="space-y-6">
                    {{-- SECTION IMAGE DE FOND --}}
                    <div>
                        <h3 class="text-sm font-semibold text-gray-900 mb-1">Image de fond</h3>
                        <p class="text-xs text-gray-500">
                            Format : JPG, PNG, WebP – Taille recommandée : 1920x1080px – Max : 5MB
                        </p>
                    </div>

                    {{-- 1️⃣ UPLOADER UNE NOUVELLE IMAGE --}}
                    <div class="bg-white border border-gray-200 rounded-lg p-4 space-y-3">
                        <p class="text-sm font-semibold text-gray-800 mb-2">Uploader une nouvelle image</p>

                        <label
                            for="new_image"
                            class="flex flex-col items-center justify-center border-2 border-dashed border-gray-300 rounded-md px-4 py-6 text-center cursor-pointer hover:border-ed-primary hover:bg-ed-primary/5 transition"
                        >
                            <span class="text-sm text-gray-600">
                                <span class="font-semibold text-ed-primary">Cliquer pour sélectionner</span>
                                ou déposer une image ici
                            </span>
                            <span class="mt-1 text-xs text-gray-500">
                                PNG, JPG, WebP – Max: 5MB
                            </span>
                        </label>

                        <input
                            id="new_image"
                            type="file"
                            wire:model="new_image"
                            accept="image/*"
                            class="hidden"
                        >

                        @error('new_image')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror

                        {{-- ✅ Aperçu temporaire de la nouvelle image --}}
                        @if ($new_image)
                            <div class="mt-3">
                                <p class="text-xs font-semibold text-gray-700 mb-1">Aperçu (nouvelle image)</p>
                                <div class="overflow-hidden rounded-lg border border-gray-200 inline-block max-w-md">
                                    <img
                                        src="{{ $new_image->temporaryUrl() }}"
                                        alt="Aperçu nouvelle image"
                                        class="w-full h-48 object-cover"
                                    >
                                </div>
                            </div>
                        @endif
                    </div>
                    {{-- ✅ Aperçu de l'image sélectionnée depuis la médiathèque --}}
                    @if ($image_id)
                        @php
                            $selectedMedia = $medias->firstWhere('id', $image_id);
                        @endphp

                        @if ($selectedMedia)
                            <div class="mt-3">
                                <p class="text-xs font-semibold text-gray-700 mb-1">Aperçu (médiathèque)</p>
                                <div class="overflow-hidden rounded-lg border border-gray-200 inline-block max-w-md">
                                    <img
                                        src="{{ asset('storage/' . $selectedMedia->chemin) }}"
                                        alt="Aperçu image médiathèque"
                                        class="w-full h-48 object-cover"
                                    >
                                </div>
                            </div>
                        @endif
                    @endif
                    {{-- 2️⃣ OU SÉLECTIONNER DEPUIS LA MÉDIATHÈQUE --}}
                    <div class="bg-white border border-gray-200 rounded-lg p-4 space-y-3">
                        <p class="text-sm font-semibold text-gray-800">OU sélectionner depuis la médiathèque</p>

                        <div class="mt-1">
                            <select
                                wire:model.live="image_id"
                                class="block w-full rounded-md border-gray-300 text-sm shadow-sm focus:border-ed-primary focus:ring-ed-primary"
                            >
                                <option value="">-- Sélectionner une image --</option>
                                @foreach($medias as $media)
                                    <option value="{{ $media->id }}">{{ $media->nom_original }}</option>
                                @endforeach
                            </select>

                            @error('image_id')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>


            </div>

            <!-- Colonne latérale -->
            <div class="space-y-6">
                
                <!-- Paramètres -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Paramètres</h3>
                    
                    <!-- Ordre -->
                    <div class="mb-4">
                        <label for="ordre" class="block text-sm font-medium text-gray-700 mb-2">
                            Ordre <span class="text-red-500">*</span>
                        </label>
                        <input type="number" 
                               id="ordre" 
                               wire:model="ordre" 
                               min="1"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-ed-primary focus:border-transparent @error('ordre') border-red-500 @enderror">
                        @error('ordre')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-sm text-gray-500">Position dans le slider</p>
                    </div>

                    <!-- Visible -->
                    <div class="mb-4">
                        <label class="flex items-center gap-3 cursor-pointer">
                            <input type="checkbox" 
                                   wire:model="visible" 
                                   class="w-5 h-5 text-ed-primary border-gray-300 rounded focus:ring-2 focus:ring-ed-primary">
                            <span class="text-sm font-medium text-gray-700">Visible sur le site</span>
                        </label>
                    </div>

                    <!-- Couleur de fond -->
                    <div>
                        <label for="couleur_fond" class="block text-sm font-medium text-gray-700 mb-2">
                            Couleur de fond (gradient)
                        </label>
                        <select id="couleur_fond" 
                                wire:model="couleur_fond" 
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-ed-primary focus:border-transparent">
                            <option value="from-ed-primary/95 via-ed-secondary/90 to-teal-800/95">Vert primaire</option>
                            <option value="from-teal-800/95 via-ed-primary/90 to-green-900/95">Vert foncé</option>
                            <option value="from-green-900/95 via-ed-secondary/90 to-teal-700/95">Vert émeraude</option>
                            <option value="from-blue-900/95 via-blue-700/90 to-blue-800/95">Bleu</option>
                            <option value="from-purple-900/95 via-purple-700/90 to-purple-800/95">Violet</option>
                        </select>
                    </div>
                </div>

                <!-- Boutons d'action -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <div class="space-y-3">
                        <button type="submit" 
                                class="w-full px-6 py-3 bg-ed-primary text-white rounded-lg hover:bg-ed-secondary transition font-bold">
                            Créer le slide
                        </button>
                        
                        <a href="{{ route('admin.slides.index', $slider) }}" 
                           class="block w-full px-6 py-3 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition text-center">
                            Annuler
                        </a>
                    </div>
                </div>

            </div>
        </div>
    </form>
</div>