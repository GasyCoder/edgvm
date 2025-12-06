<div>
    <!-- Breadcrumb -->
    <nav class="mb-6">
        <ol class="flex items-center gap-2 text-sm text-gray-600">
            <li><a href="{{ route('admin.dashboard') }}" class="hover:text-ed-primary">Dashboard</a></li>
            <li>/</li>
            <li><a href="{{ route('admin.actualites.index') }}" class="hover:text-ed-primary">Actualités</a></li>
            <li>/</li>
            <li class="text-gray-900 font-semibold">Modifier</li>
        </ol>
    </nav>

    <h1 class="text-2xl font-bold mb-6">Modifier l'actualité</h1>

    <form wire:submit="save">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            
            <!-- Colonne principale (identique à create) -->
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

                <!-- Contenu avec éditeur -->
                <div class="bg-white rounded-lg shadow p-6">
                    <label class="block mb-2 font-semibold text-gray-700">
                        Contenu <span class="text-red-500">*</span>
                    </label>
                    
                    <div class="border border-gray-300 rounded-lg overflow-hidden">
                        <!-- Toolbar -->
                        <div class="bg-gray-50 border-b border-gray-300 p-2 flex gap-2 flex-wrap">
                            <button type="button" onclick="document.execCommand('bold', false, null)" 
                                    class="p-2 hover:bg-gray-200 rounded" title="Gras">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M11 5H7v10h4a5 5 0 000-10zm-1 8H9V7h1a3 3 0 010 6z"/>
                                </svg>
                            </button>
                            <button type="button" onclick="document.execCommand('italic', false, null)"
                                    class="p-2 hover:bg-gray-200 rounded" title="Italique">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M12 4v2h-2l-2 8h2v2H6v-2h2l2-8H8V4h4z"/>
                                </svg>
                            </button>
                            <button type="button" onclick="document.execCommand('insertUnorderedList', false, null)"
                                    class="p-2 hover:bg-gray-200 rounded" title="Liste à puces">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M3 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z"/>
                                </svg>
                            </button>
                            <button type="button" onclick="document.execCommand('insertOrderedList', false, null)"
                                    class="p-2 hover:bg-gray-200 rounded" title="Liste numérotée">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M3 4h14v2H3V4zm0 5h14v2H3V9zm0 5h14v2H3v-2z"/>
                                </svg>
                            </button>
                        </div>
                        
                        <div contenteditable="true"
                             wire:ignore
                             x-data="{ 
                                 content: @entangle('contenu'),
                                 init() {
                                     this.$el.innerHTML = this.content;
                                     this.$el.addEventListener('input', () => {
                                         this.content = this.$el.innerHTML;
                                     });
                                 }
                             }"
                             class="min-h-[300px] p-4 focus:outline-none prose max-w-none">
                        </div>
                    </div>
                    
                    @error('contenu')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Sidebar (identique à create mais avec bouton "Mettre à jour") -->
            <div class="space-y-6">
                
                <!-- Image à la une -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="font-semibold text-gray-700 mb-4">Image à la une</h3>
                    
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
                                wire:click="$set('showMediaSelector', true)"
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
                        <span class="font-semibold text-gray-700">Visible</span>
                    </label>
                </div>

                <!-- Informations -->
                <div class="bg-white rounded-lg shadow p-6 text-sm text-gray-600">
                    <p><strong>Créé le :</strong> {{ $actualite->created_at->format('d/m/Y à H:i') }}</p>
                    <p class="mt-2"><strong>Modifié le :</strong> {{ $actualite->updated_at->format('d/m/Y à H:i') }}</p>
                    <p class="mt-2"><strong>Auteur :</strong> {{ $actualite->auteur?->name ?? 'Non défini' }}</p>
                </div>

                <!-- Boutons -->
                <div class="bg-white rounded-lg shadow p-6 space-y-3">
                    <button type="submit" 
                            class="w-full px-6 py-3 bg-ed-primary text-white rounded-lg hover:bg-ed-secondary font-bold">
                        💾 Mettre à jour
                    </button>
                    <a href="{{ route('admin.actualites.index') }}"
                       class="block w-full px-6 py-3 border border-gray-300 rounded-lg text-center hover:bg-gray-50">
                        Annuler
                    </a>
                </div>
            </div>
        </div>
    </form>

    <!-- Modal sélection d'image (identique à create) -->
    @if($showMediaSelector)
    <div class="fixed inset-0 z-50 overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75" wire:click="$set('showMediaSelector', false)"></div>
            
            <div class="relative bg-white rounded-lg max-w-4xl w-full p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-bold">Sélectionner une image</h3>
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
</div>