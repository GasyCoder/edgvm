<div>
    <!-- Breadcrumb -->
    <nav class="mb-6">
        <ol class="flex items-center gap-2 text-sm text-gray-600">
            <li><a href="{{ route('admin.dashboard') }}" class="hover:text-ed-primary">Dashboard</a></li>
            <li>/</li>
            <li><a href="{{ route('admin.actualites.index') }}" class="hover:text-ed-primary">Actualités</a></li>
            <li>/</li>
            <li class="text-gray-900 font-semibold">Nouvelle</li>
        </ol>
    </nav>

    <h1 class="text-2xl font-bold mb-6">Nouvelle actualité</h1>

    <form wire:submit="save">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            
            <!-- Colonne principale -->
            <div class="lg:col-span-2 space-y-6">
                
                <!-- Titre -->
                <div class="bg-white rounded-lg shadow p-6">
                    <label class="block mb-2 font-semibold text-gray-700">
                        Titre <span class="text-red-500">*</span>
                    </label>
                    <input type="text" 
                           wire:model="titre"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-ed-primary"
                           placeholder="Titre de l'actualité">
                    @error('titre')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Contenu avec TinyMCE -->
                <div class="bg-white rounded-lg shadow p-6">
                    <label class="block mb-2 font-semibold text-gray-700">
                        Contenu <span class="text-red-500">*</span>
                    </label>
                    
                    <div wire:ignore>
                        <textarea id="tinymce-editor" class="w-full"></textarea>
                    </div>
                    
                    @error('contenu')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Galerie d'images -->
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="font-semibold text-gray-700">📸 Galerie d'images</h3>
                        <button type="button" 
                                wire:click="openMediaSelector('gallery')"
                                class="px-4 py-2 bg-ed-primary text-white rounded-lg hover:bg-ed-secondary text-sm font-bold">
                            ➕ Ajouter des images
                        </button>
                    </div>

                    @if(!empty($galerieImages))
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        @foreach($galerieImagesData as $media)
                        <div class="relative group">
                            <img src="{{ $media->url }}" class="w-full h-32 object-cover rounded-lg">
                            <button type="button" 
                                    wire:click="removeGalerieImage({{ $media->id }})"
                                    class="absolute top-2 right-2 p-1.5 bg-red-600 text-white rounded-full hover:bg-red-700 opacity-0 group-hover:opacity-100 transition">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </button>
                        </div>
                        @endforeach
                    </div>
                    @else
                    <p class="text-sm text-gray-500 text-center py-8 border-2 border-dashed border-gray-300 rounded-lg">
                        Aucune image dans la galerie. Cliquez sur "Ajouter des images" pour en ajouter.
                    </p>
                    @endif
                </div>
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                
                <!-- Catégorie -->
                <div class="bg-white rounded-lg shadow p-6">
                    <label class="block mb-2 font-semibold text-gray-700">
                        Catégorie <span class="text-red-500">*</span>
                    </label>
                    <select wire:model="category_id" 
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-ed-primary">
                        <option value="">-- Sélectionner --</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->nom }}</option>
                        @endforeach
                    </select>
                    @error('category_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    
                    <a href="{{ route('admin.categories.index') }}" target="_blank"
                       class="mt-2 inline-block text-xs text-ed-primary hover:text-ed-secondary">
                        ➕ Gérer les catégories
                    </a>
                </div>

                <!-- Tags -->
                <div class="bg-white rounded-lg shadow p-6">
                    <label class="block mb-2 font-semibold text-gray-700">
                        Tags <span class="text-gray-500 text-sm font-normal">(optionnel)</span>
                    </label>
                    
                    <div class="space-y-2 max-h-48 overflow-y-auto border border-gray-200 rounded-lg p-3">
                        @forelse($tags as $tag)
                            <label class="flex items-center gap-2 cursor-pointer hover:bg-gray-50 p-2 rounded">
                                <input type="checkbox" 
                                       wire:model="selectedTags" 
                                       value="{{ $tag->id }}"
                                       class="rounded text-ed-primary focus:ring-ed-primary">
                                <span class="text-sm">{{ $tag->nom }}</span>
                            </label>
                        @empty
                            <p class="text-sm text-gray-500 text-center py-4">Aucun tag disponible</p>
                        @endforelse
                    </div>
                    
                    <a href="{{ route('admin.tags.index') }}" target="_blank"
                       class="mt-2 inline-block text-xs text-ed-primary hover:text-ed-secondary">
                        ➕ Gérer les tags
                    </a>
                </div>

                <!-- Image à la une -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="font-semibold text-gray-700 mb-4">🖼️ Image principale</h3>
                    
                    @if($selectedImage)
                        <div class="relative">
                            <img src="{{ $selectedImage->url }}" class="w-full h-48 object-cover rounded-lg mb-2">
                            <button type="button" 
                                    wire:click="removeImage"
                                    class="absolute top-2 right-2 p-2 bg-red-600 text-white rounded-full hover:bg-red-700">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </button>
                        </div>
                    @else
                        <button type="button" 
                                wire:click="openMediaSelector('featured')"
                                class="w-full px-4 py-3 border-2 border-dashed border-gray-300 rounded-lg hover:border-ed-primary transition text-gray-600 hover:text-ed-primary">
                            <svg class="w-8 h-8 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            Sélectionner une image
                        </button>
                    @endif
                </div>

                <!-- Date de publication -->
                <div class="bg-white rounded-lg shadow p-6">
                    <label class="block mb-2 font-semibold text-gray-700">Date de publication</label>
                    <input type="date" 
                           wire:model="date_publication"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-ed-primary">
                </div>

                <!-- Visibilité -->
                <div class="bg-white rounded-lg shadow p-6">
                    <label class="flex items-center gap-3 cursor-pointer">
                        <input type="checkbox" 
                               wire:model="visible"
                               class="w-5 h-5 text-ed-primary rounded focus:ring-2 focus:ring-ed-primary">
                        <span class="font-semibold text-gray-700">Publier immédiatement</span>
                    </label>
                    <p class="text-xs text-gray-500 mt-2">L'actualité sera visible sur le site public</p>
                </div>

                <!-- Actualité importante -->
                <div class="bg-white rounded-lg shadow p-6">
                    <label class="flex items-center gap-3 cursor-pointer">
                        <input type="checkbox" 
                            wire:model="est_important"
                            class="w-5 h-5 text-red-600 rounded focus:ring-2 focus:ring-red-600">
                        <span class="font-semibold text-gray-700">🔥 Actualité importante</span>
                    </label>
                    <p class="text-xs text-gray-500 mt-2">Marquer comme vedette sur la page d'accueil</p>
                </div>

                <!-- Notification newsletter -->
                <div class="bg-white rounded-lg shadow p-6">
                    <label class="flex items-center gap-3 cursor-pointer">
                        <input type="checkbox" 
                            wire:model="notifier_abonnes"
                            class="w-5 h-5 text-ed-primary rounded focus:ring-2 focus:ring-ed-primary">
                        <span class="font-semibold text-gray-700">📧 Notifier les abonnés</span>
                    </label>
                    <p class="text-xs text-gray-500 mt-2">
                        Envoyer un email à tous les abonnés de la newsletter
                    </p>
                    
                    <div class="mt-3 p-3 bg-blue-50 border border-blue-200 rounded text-xs text-blue-800">
                        <strong>Note :</strong> Les notifications sont envoyées uniquement si l'actualité est visible.
                    </div>
                </div>

                <!-- Boutons avec Loading -->
                <div class="bg-white rounded-lg shadow p-6 space-y-3">
                    <button type="submit" 
                            wire:loading.attr="disabled"
                            class="w-full px-6 py-3 bg-ed-primary text-white rounded-lg hover:bg-ed-secondary font-bold disabled:opacity-50 disabled:cursor-not-allowed transition">
                        <span wire:loading.remove wire:target="save">✅ Créer l'actualité</span>
                        <span wire:loading wire:target="save" class="flex items-center justify-center gap-2">
                            <svg class="animate-spin h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            <span>Création en cours...</span>
                        </span>
                    </button>
                    <a href="{{ route('admin.actualites.index') }}"
                       class="block w-full px-6 py-3 border border-gray-300 rounded-lg text-center hover:bg-gray-50">
                        Annuler
                    </a>
                </div>
            </div>
        </div>
    </form>

    <!-- Overlay de chargement -->
    <div wire:loading wire:target="save" 
        class="fixed inset-0 z-[9999]" 
        style="left: 0 !important; right: 0 !important; top: 0 !important; bottom: 0 !important;">
        
        <!-- Background sombre -->
        <div class="absolute inset-0 bg-black bg-opacity-50"></div>
        
        <!-- Contenu centré -->
        <div class="relative z-10 min-h-screen flex items-center justify-center px-4">
            <div class="bg-white rounded-2xl shadow-2xl p-8 max-w-md w-full">
                <div class="text-center">
                    <!-- Spinner animé -->
                    <div class="mx-auto mb-6 w-16 h-16 border-4 border-ed-primary border-t-transparent rounded-full animate-spin"></div>
                    
                    <!-- Message principal -->
                    <h3 class="text-2xl font-bold text-gray-900 mb-3">
                        Création en cours...
                    </h3>
                    
                    <p class="mt-4 text-xs text-gray-500">
                        Veuillez patienter, ne fermez pas cette page...
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal sélection d'image (inchangé) -->
    @if($showMediaSelector)
    <div class="fixed inset-0 z-50 overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75" wire:click="$set('showMediaSelector', false)"></div>
            
            <div class="relative bg-white rounded-lg max-w-4xl w-full p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-bold">
                        {{ $mediaSelectorType === 'featured' ? 'Sélectionner l\'image principale' : 'Ajouter des images à la galerie' }}
                    </h3>
                    <button wire:click="$set('showMediaSelector', false)" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                <div class="grid grid-cols-4 gap-4 max-h-96 overflow-y-auto">
                    @foreach($medias as $media)
                    <div wire:click="selectImage({{ $media->id }})" 
                         class="cursor-pointer group relative">
                        <img src="{{ $media->url }}" class="w-full h-32 object-cover rounded-lg group-hover:opacity-75">
                        <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-30 rounded-lg transition flex items-center justify-center">
                            <svg class="w-8 h-8 text-white opacity-0 group-hover:opacity-100" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                        </div>
                    </div>
                    @endforeach
                </div>

                <div class="mt-4">
                    {{ $medias->links() }}
                </div>

                <div class="mt-4 pt-4 border-t">
                    <a href="{{ route('admin.media.upload') }}" target="_blank"
                       class="text-ed-primary hover:text-ed-secondary font-semibold">
                        ➕ Uploader une nouvelle image
                    </a>
                </div>
            </div>
        </div>
    </div>
    @endif

    @push('scripts')
    <script>
        document.addEventListener('livewire:navigated', function () {
            initTinyMCE();
        });

        function initTinyMCE() {
            if (typeof tinymce !== 'undefined') {
                tinymce.remove();
                
                tinymce.init({
                    selector: '#tinymce-editor',
                    height: 500,
                    menubar: false,
                    plugins: [
                        'advlist', 'autolink', 'lists', 'link', 'image', 'charmap', 'preview',
                        'anchor', 'searchreplace', 'visualblocks', 'code', 'fullscreen',
                        'insertdatetime', 'media', 'table', 'help', 'wordcount'
                    ],
                    toolbar: 'undo redo | blocks | bold italic forecolor | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | removeformat | link image | code | help',
                    content_style: 'body { font-family: Arial, sans-serif; font-size: 14px; }',
                    setup: function (editor) {
                        editor.on('init', function () {
                            editor.setContent(@this.contenu);
                        });
                        editor.on('change', function () {
                            @this.set('contenu', editor.getContent());
                        });
                    }
                });
            }
        }

        // Init au chargement
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', initTinyMCE);
        } else {
            initTinyMCE();
        }
    </script>
    @endpush
</div>