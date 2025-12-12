<div>
    <!-- Breadcrumb -->
    <nav class="mb-6">
        <ol class="flex items-center gap-2 text-sm text-slate-600">
            <li>
                <a href="{{ route('admin.dashboard') }}" class="hover:text-ed-primary transition">
                    Dashboard
                </a>
            </li>
            <li class="text-slate-400">/</li>
            <li>
                <a href="{{ route('admin.actualites.index') }}" class="hover:text-ed-primary transition">
                    Actualités
                </a>
            </li>
            <li class="text-slate-400">/</li>
            <li class="text-slate-900 font-semibold">Modifier</li>
        </ol>
    </nav>

    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-6">
        <div>
            <h1 class="text-2xl font-bold text-slate-900">Modifier l’actualité</h1>
            <p class="text-sm text-slate-500 mt-1">
                Mettez à jour le contenu, la visibilité et les médias associés.
            </p>
        </div>

        <div class="inline-flex items-center gap-2 rounded-xl bg-white px-3.5 py-2 text-sm text-slate-700 border border-slate-200">
            <svg class="h-4 w-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12s3.75-7.5 9.75-7.5S21.75 12 21.75 12s-3.75 7.5-9.75 7.5S2.25 12 2.25 12z"/>
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 15a3 3 0 100-6 3 3 0 000 6z"/>
            </svg>
            <span><span class="font-semibold">Vues :</span> {{ $actualite->vues_formatted }}</span>
        </div>
    </div>

    <form wire:submit="save">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            <!-- Colonne principale -->
            <div class="lg:col-span-2 space-y-6">

                <!-- Titre -->
                <div class="bg-white rounded-2xl border border-slate-200 p-6">
                    <label class="block mb-2 font-semibold text-slate-800">
                        Titre <span class="text-red-500">*</span>
                    </label>
                    <input type="text"
                           wire:model="titre"
                           class="w-full px-4 py-2.5 border border-slate-300 rounded-xl
                                  focus:outline-none focus:ring-2 focus:ring-ed-primary/20 focus:border-ed-primary"
                           placeholder="Titre de l'actualité">
                    @error('titre')
                        <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Contenu -->
                <div class="bg-white rounded-2xl border border-slate-200 p-6">
                    <label class="block mb-2 font-semibold text-slate-800">
                        Contenu <span class="text-red-500">*</span>
                    </label>

                    <div wire:ignore>
                        <textarea id="tinymce-editor" class="w-full"></textarea>
                    </div>

                    @error('contenu')
                        <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Galerie -->
                <div class="bg-white rounded-2xl border border-slate-200 p-6">
                    <div class="flex items-center justify-between gap-3 mb-4">
                        <div class="flex items-center gap-2">
                            <svg class="h-4 w-4 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      d="M3 16.5V6a2 2 0 012-2h14a2 2 0 012 2v10.5M3 16.5l4.5-4.5a2 2 0 012.828 0L14 15.672a2 2 0 002.828 0L21 11.5M3 16.5V18a2 2 0 002 2h14a2 2 0 002-2v-1.5"/>
                            </svg>
                            <h3 class="font-semibold text-slate-800">Galerie d’images</h3>
                        </div>

                        <button type="button" wire:click="openMediaSelector('gallery')"
                                class="inline-flex items-center gap-2 rounded-xl px-3.5 py-2 text-sm font-semibold
                                       bg-ed-primary text-white hover:bg-ed-secondary transition">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                            </svg>
                            <span>Ajouter</span>
                        </button>
                    </div>

                    @if(!empty($galerieImages))
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                            @foreach($galerieImagesData as $media)
                                <div class="relative group">
                                    <img src="{{ $media->url }}" class="w-full h-32 object-cover rounded-xl border border-slate-200">

                                    <button type="button"
                                            wire:click="removeGalerieImage({{ $media->id }})"
                                            class="absolute top-2 right-2 inline-flex items-center justify-center
                                                   h-8 w-8 rounded-full bg-white/95 border border-slate-200
                                                   text-slate-700 hover:text-red-600 hover:border-red-200 transition
                                                   opacity-0 group-hover:opacity-100">
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                                        </svg>
                                    </button>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="rounded-xl border border-dashed border-slate-300 bg-slate-50 px-4 py-10 text-center">
                            <p class="text-sm text-slate-600">
                                Aucune image dans la galerie. Cliquez sur “Ajouter” pour en sélectionner.
                            </p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">

                <!-- Catégorie -->
                <div class="bg-white rounded-2xl border border-slate-200 p-6">
                    <label class="block mb-2 font-semibold text-slate-800">
                        Catégorie <span class="text-red-500">*</span>
                    </label>

                    <select wire:model="category_id"
                            class="w-full px-4 py-2.5 border border-slate-300 rounded-xl
                                   focus:outline-none focus:ring-2 focus:ring-ed-primary/20 focus:border-ed-primary">
                        <option value="">-- Sélectionner --</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->nom }}</option>
                        @endforeach
                    </select>

                    @error('category_id')
                        <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                    @enderror

                    <a href="{{ route('admin.categories.index') }}" target="_blank"
                       class="mt-3 inline-flex items-center gap-2 text-xs font-semibold text-ed-primary hover:text-ed-secondary transition">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 6h9.75M10.5 12h9.75M10.5 18h9.75M3.75 6h.007v.008H3.75V6zm0 6h.007v.008H3.75V12zm0 6h.007v.008H3.75V18z"/>
                        </svg>
                        <span>Gérer les catégories</span>
                    </a>
                </div>

                <!-- Tags -->
                <div class="bg-white rounded-2xl border border-slate-200 p-6">
                    <label class="block mb-2 font-semibold text-slate-800">
                        Tags <span class="text-slate-500 text-sm font-normal">(optionnel)</span>
                    </label>

                    <div class="space-y-1.5 max-h-48 overflow-y-auto border border-slate-200 rounded-xl p-2.5">
                        @forelse($tags as $tag)
                            <label class="flex items-center gap-2 cursor-pointer hover:bg-slate-50 p-2 rounded-lg transition">
                                <input type="checkbox"
                                       wire:model="selectedTags"
                                       value="{{ $tag->id }}"
                                       class="rounded border-slate-300 text-ed-primary focus:ring-ed-primary/20">
                                <span class="text-sm text-slate-700">{{ $tag->nom }}</span>
                            </label>
                        @empty
                            <p class="text-sm text-slate-500 text-center py-4">Aucun tag disponible</p>
                        @endforelse
                    </div>

                    <a href="{{ route('admin.tags.index') }}" target="_blank"
                       class="mt-3 inline-flex items-center gap-2 text-xs font-semibold text-ed-primary hover:text-ed-secondary transition">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M9.568 3.75H7.125A3.375 3.375 0 003.75 7.125v2.443a3.375 3.375 0 00.988 2.386l8.489 8.49a3.375 3.375 0 004.773 0l2.444-2.444a3.375 3.375 0 000-4.773l-8.49-8.49a3.375 3.375 0 00-2.386-.987z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 6h.008v.008H6V6z"/>
                        </svg>
                        <span>Gérer les tags</span>
                    </a>
                </div>

                <!-- Image à la une -->
                <div class="bg-white rounded-2xl border border-slate-200 p-6">
                    <div class="flex items-center gap-2 mb-4">
                        <svg class="h-4 w-4 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 6.75h10.5v10.5H6.75V6.75z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 9.75V6a2.25 2.25 0 012.25-2.25h12A2.25 2.25 0 0120.25 6v12A2.25 2.25 0 0118 20.25H9.75"/>
                        </svg>
                        <h3 class="font-semibold text-slate-800">Image principale</h3>
                    </div>

                    @if($selectedImage)
                        <div class="relative">
                            <img src="{{ $selectedImage->url }}" class="w-full h-48 object-cover rounded-xl border border-slate-200">
                            <button type="button"
                                    wire:click="removeImage"
                                    class="absolute top-2 right-2 inline-flex items-center justify-center
                                           h-9 w-9 rounded-full bg-white/95 border border-slate-200
                                           text-slate-700 hover:text-red-600 hover:border-red-200 transition">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </button>
                        </div>
                    @else
                        <button type="button"
                                wire:click="openMediaSelector('featured')"
                                class="w-full rounded-xl border border-dashed border-slate-300 bg-slate-50
                                       px-4 py-6 text-slate-700 hover:border-ed-primary hover:text-ed-primary transition">
                            <div class="flex flex-col items-center gap-2">
                                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                          d="M3 16.5V6a2 2 0 012-2h14a2 2 0 012 2v10.5M3 16.5l4.5-4.5a2 2 0 012.828 0L14 15.672a2 2 0 002.828 0L21 11.5M3 16.5V18a2 2 0 002 2h14a2 2 0 002-2v-1.5"/>
                                </svg>
                                <span class="text-sm font-semibold">Sélectionner une image</span>
                                <span class="text-xs text-slate-500">Depuis la médiathèque</span>
                            </div>
                        </button>
                    @endif
                </div>

                <!-- Date -->
                <div class="bg-white rounded-2xl border border-slate-200 p-6">
                    <label class="block mb-2 font-semibold text-slate-800">Date de publication</label>
                    <input type="date"
                           wire:model="date_publication"
                           class="w-full px-4 py-2.5 border border-slate-300 rounded-xl
                                  focus:outline-none focus:ring-2 focus:ring-ed-primary/20 focus:border-ed-primary">
                </div>

                <!-- Visibilité -->
                <div class="bg-white rounded-2xl border border-slate-200 p-6">
                    <label class="flex items-start gap-3 cursor-pointer">
                        <input type="checkbox"
                               wire:model="visible"
                               class="mt-0.5 h-5 w-5 rounded border-slate-300 text-ed-primary focus:ring-ed-primary/20">
                        <span>
                            <span class="font-semibold text-slate-800">Publier</span>
                            <span class="block text-xs text-slate-500 mt-1">Visible sur le site public.</span>
                        </span>
                    </label>
                </div>

                <!-- Important -->
                <div class="bg-white rounded-2xl border border-slate-200 p-6">
                    <label class="flex items-start gap-3 cursor-pointer">
                        <input type="checkbox"
                               wire:model="est_important"
                               class="mt-0.5 h-5 w-5 rounded border-slate-300 text-red-600 focus:ring-red-600/20">
                        <span>
                            <span class="font-semibold text-slate-800">Mettre en avant</span>
                            <span class="block text-xs text-slate-500 mt-1">Affichage prioritaire (ex. page d’accueil).</span>
                        </span>
                    </label>
                </div>

                <!-- Stat Newsletter -->
                @if($actualite->notification_envoyee_le)
                    <div class="rounded-2xl border border-emerald-200 bg-emerald-50 p-5">
                        <div class="flex items-start gap-3 text-emerald-800">
                            <svg class="h-5 w-5 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4"/>
                            </svg>
                            <div>
                                <p class="text-sm font-semibold">Newsletter envoyée</p>
                                <p class="text-xs text-emerald-700/80 mt-1">
                                    {{ $actualite->notification_envoyee_le->format('d/m/Y à H:i') }}
                                </p>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Actions -->
                <div class="bg-white rounded-2xl border border-slate-200 p-6 space-y-3">
                    <button type="submit" wire:loading.attr="disabled"
                            class="w-full inline-flex items-center justify-center gap-2 rounded-xl px-4 py-3 text-sm font-semibold
                                   bg-ed-primary text-white hover:bg-ed-secondary transition
                                   disabled:opacity-60 disabled:cursor-not-allowed">
                        <svg wire:loading.remove wire:target="save" class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M16.862 4.487l1.688-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l9. -8.931z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 7.125L16.875 4.5"/>
                        </svg>

                        <svg wire:loading wire:target="save" class="h-4 w-4 animate-spin" viewBox="0 0 24 24" fill="none">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
                        </svg>

                        <span wire:loading.remove wire:target="save">Mettre à jour</span>
                        <span wire:loading wire:target="save">Mise à jour…</span>
                    </button>

                    <a href="{{ route('admin.actualites.index') }}"
                       class="w-full inline-flex items-center justify-center gap-2 rounded-xl px-4 py-3 text-sm font-semibold
                              border border-slate-300 bg-white text-slate-800 hover:bg-slate-50 transition">
                        <svg class="h-4 w-4 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                        <span>Annuler</span>
                    </a>
                </div>
            </div>
        </div>
    </form>

    <!-- Overlay loading -->
    <div wire:loading wire:target="save" class="fixed inset-0 z-[9999]">
        <div class="absolute inset-0 bg-black/50"></div>
        <div class="relative z-10 min-h-screen flex items-center justify-center px-4">
            <div class="bg-white rounded-2xl border border-slate-200 p-8 max-w-md w-full">
                <div class="text-center">
                    <div class="mx-auto mb-5 h-12 w-12 rounded-full border-4 border-ed-primary border-t-transparent animate-spin"></div>
                    <h3 class="text-lg font-bold text-slate-900">Mise à jour en cours…</h3>
                    <p class="mt-2 text-xs text-slate-500">Veuillez patienter, ne fermez pas cette page.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal sélection d'image -->
    @if($showMediaSelector)
        <div class="fixed inset-0 z-50 overflow-y-auto">
            <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20">
                <div class="fixed inset-0 bg-slate-900/50" wire:click="$set('showMediaSelector', false)"></div>

                <div class="relative bg-white rounded-2xl border border-slate-200 max-w-4xl w-full p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-bold text-slate-900">
                            {{ $mediaSelectorType === 'featured' ? "Sélectionner l'image principale" : "Ajouter des images à la galerie" }}
                        </h3>
                        <button type="button" wire:click="$set('showMediaSelector', false)"
                                class="h-9 w-9 inline-flex items-center justify-center rounded-xl border border-slate-200 hover:bg-slate-50 transition text-slate-700">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>

                    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4 max-h-96 overflow-y-auto">
                        @foreach($medias as $media)
                            <button type="button" wire:click="selectImage({{ $media->id }})"
                                    class="group relative text-left">
                                <img src="{{ $media->url }}" class="w-full h-32 object-cover rounded-xl border border-slate-200 group-hover:opacity-90 transition">
                                <div class="absolute inset-0 rounded-xl bg-black/0 group-hover:bg-black/25 transition flex items-center justify-center">
                                    <svg class="h-6 w-6 text-white opacity-0 group-hover:opacity-100 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                                    </svg>
                                </div>
                            </button>
                        @endforeach
                    </div>

                    <div class="mt-4">
                        {{ $medias->links() }}
                    </div>

                    <div class="mt-4 pt-4 border-t border-slate-200">
                        <a href="{{ route('admin.media.upload') }}" target="_blank"
                           class="inline-flex items-center gap-2 text-sm font-semibold text-ed-primary hover:text-ed-secondary transition">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 16V4m0 0l-4 4m4-4l4 4M4 20h16"/>
                            </svg>
                            <span>Uploader une nouvelle image</span>
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
                            'advlist','autolink','lists','link','image','charmap','preview',
                            'anchor','searchreplace','visualblocks','code','fullscreen',
                            'insertdatetime','media','table','help','wordcount'
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

            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', initTinyMCE);
            } else {
                initTinyMCE();
            }
        </script>
    @endpush
</div>
