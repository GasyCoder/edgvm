<div>
    <!-- Breadcrumb -->
    <nav class="mb-6">
        <ol class="flex items-center gap-2 text-sm text-gray-600">
            <li><a href="{{ route('admin.dashboard') }}" class="hover:text-ed-primary">Dashboard</a></li>
            <li>/</li>
            <li><a href="{{ route('admin.sliders.index') }}" class="hover:text-ed-primary">Sliders</a></li>
            <li>/</li>
            <li><a href="{{ route('admin.slides.index', $slide->slider) }}" class="hover:text-ed-primary">{{ $slide->slider->nom }}</a></li>
            <li>/</li>
            <li class="text-gray-900 font-semibold">Modifier</li>
        </ol>
    </nav>

    <!-- Formulaire -->
    <form wire:submit.prevent="update">
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
                        
                        <div class="space-y-3">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Lier à une actualité (recherche)</label>

                                <input
                                    type="text"
                                    wire:model.live="searchActualite"
                                    placeholder="Rechercher un titre d'actualité..."
                                    class="w-full px-4 py-2 border rounded-lg"
                                    autocomplete="off"
                                />

                                {{-- Dropdown résultats --}}
                                @if(!empty($searchActualite) && count($actualiteResults))
                                    <ul class="mt-2 max-h-56 overflow-auto bg-white border rounded-md shadow-sm">
                                        @foreach($actualiteResults as $res)
                                            <li class="px-3 py-2 hover:bg-gray-50 cursor-pointer flex justify-between items-center"
                                                wire:click="selectActualite({{ $res['id'] }})">
                                                <div class="text-sm">
                                                    <div class="font-medium text-gray-800">{{ $res['titre'] }}</div>
                                                    @if($res['date_publication']) 
                                                        <div class="text-xs text-gray-500">{{ $res['date_publication'] }}</div>
                                                    @endif
                                                </div>
                                                <div class="text-xs text-gray-400 ml-2">Sélectionner</div>
                                            </li>
                                        @endforeach
                                    </ul>
                                @elseif(!empty($searchActualite))
                                    <div class="mt-2 text-xs text-gray-500">Aucun résultat.</div>
                                @endif
                            </div>

                            {{-- Aperçu de la sélection --}}
                            @if($actualite_id)
                                <div class="mt-2 p-3 border rounded-md bg-gray-50 flex items-start justify-between">
                                    <div>
                                        @if($actPreview)
                                            <div class="text-sm font-medium text-gray-800">{{ $actPreview->titre }}</div>
                                            @if($actPreview->date_publication)
                                                <div class="text-xs text-gray-500">{{ $actPreview->date_publication->format('Y-m-d') }}</div>
                                            @endif
                                        @endif
                                        <div class="text-xs text-gray-600 mt-2">
                                            Lien généré :
                                            <a href="{{ $lien_cta }}" target="_blank" class="underline text-ed-primary">{{ $lien_cta }}</a>
                                        </div>
                                    </div>

                                    <div class="ml-4">
                                        <button type="button" wire:click="clearActualiteSelection" class="text-sm px-3 py-1 border rounded-md text-red-500">
                                            Annuler
                                        </button>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>

                    {{-- Champ lien personnalisé (readonly si actualité sélectionnée) --}}
                    <div class="mt-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Lien du bouton (personnalisé)</label>
                        <input type="text" id="lien_cta" wire:model="lien_cta"
                            @if($actualite_id) readonly @endif
                            class="w-full px-4 py-2 border rounded-lg @if($actualite_id) bg-gray-50 @endif"
                            placeholder="https://...">
                        <p class="mt-1 text-xs text-gray-500">
                            @if($actualite_id)
                                Le lien est généré automatiquement depuis l'actualité sélectionnée.
                            @else
                                Saisissez un lien personnalisé si vous ne liez pas le slide à une actualité.
                            @endif
                        </p>
                    </div>
                </div>


                <!-- Section Image -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Image de fond</h3>
                    
                    <!-- Image actuelle -->
                    @if($slide->image)
                    <div class="mb-6">
                        <p class="text-sm font-medium text-gray-700 mb-2">Image actuelle :</p>
                        <img src="{{ $slide->image->url }}" class="w-full h-48 object-cover rounded-lg shadow-md">
                    </div>
                    @endif
                    
                    <!-- Upload nouvelle image -->
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Uploader une nouvelle image
                        </label>
                        <input type="file" 
                               wire:model="new_image" 
                               accept="image/*"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-ed-primary focus:border-transparent">
                        @error('new_image')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-sm text-gray-500">Format: JPG, PNG, WebP - Taille recommandée: 1920x1080px - Max: 5MB</p>
                        
                        @if ($new_image)
                            <div class="mt-4">
                                <p class="text-sm font-medium text-gray-700 mb-2">Aperçu de la nouvelle image :</p>
                                <img src="{{ $new_image->temporaryUrl() }}" class="w-full h-48 object-cover rounded-lg">
                            </div>
                        @endif
                    </div>

                    <!-- OU sélectionner depuis la médiathèque -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            OU sélectionner depuis la médiathèque
                        </label>
                        <select wire:model="image_id" 
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-ed-primary focus:border-transparent">
                            <option value="">-- Sélectionner une image --</option>
                            @foreach($medias as $media)
                                <option value="{{ $media->id }}">{{ $media->nom_original }}</option>
                            @endforeach
                        </select>
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

                <!-- Informations -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Informations</h3>
                    
                    <div class="space-y-3 text-sm">
                        <div>
                            <span class="text-gray-600">Créé le :</span>
                            <span class="font-medium">{{ $slide->created_at->format('d/m/Y à H:i') }}</span>
                        </div>
                        <div>
                            <span class="text-gray-600">Modifié le :</span>
                            <span class="font-medium">{{ $slide->updated_at->format('d/m/Y à H:i') }}</span>
                        </div>
                        <div>
                            <span class="text-gray-600">Slider :</span>
                            <span class="font-medium">{{ $slide->slider->nom }}</span>
                        </div>
                    </div>
                </div>

                <!-- Boutons d'action -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <div class="space-y-3">
                        <button type="submit" 
                                class="w-full px-6 py-3 bg-ed-primary text-white rounded-lg hover:bg-ed-secondary transition font-bold">
                            Mettre à jour
                        </button>
                        
                        <a href="{{ route('admin.slides.index', $slide->slider) }}" 
                           class="block w-full px-6 py-3 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition text-center">
                            Annuler
                        </a>
                    </div>
                </div>

            </div>
        </div>
    </form>
</div>