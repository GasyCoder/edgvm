<div class="space-y-6">

    {{-- Breadcrumb --}}
    <nav class="text-sm text-slate-500">
        <ol class="flex flex-wrap items-center gap-2">
            <li><a href="{{ route('admin.dashboard') }}" class="hover:text-ed-primary">Dashboard</a></li>
            <li class="text-slate-300">/</li>
            <li><a href="{{ route('admin.actualites.index') }}" class="hover:text-ed-primary">Actualités</a></li>
            <li class="text-slate-300">/</li>
            <li class="text-slate-900 font-semibold">Nouvelle</li>
        </ol>
    </nav>

    {{-- Header --}}
    <div class="flex flex-col gap-4 md:flex-row md:items-end md:justify-between">
        <div class="min-w-0">
            <h1 class="text-xl md:text-2xl font-semibold text-slate-900 tracking-tight">
                Nouvelle actualité
            </h1>
            <p class="mt-1 text-sm text-slate-500">
                Rédigez le contenu, configurez la publication et sélectionnez vos médias.
            </p>
        </div>

        <div class="flex items-center gap-2">
            <a href="{{ route('admin.actualites.index') }}"
               class="inline-flex items-center justify-center rounded-xl px-4 py-2.5 text-sm font-semibold
                      border border-slate-200 bg-white text-slate-800 hover:bg-slate-50 transition">
                Retour
            </a>

            <button type="submit" form="form-actualite"
                    wire:loading.attr="disabled"
                    class="inline-flex items-center justify-center gap-2 rounded-xl px-4 py-2.5 text-sm font-semibold
                           bg-ed-primary text-white hover:bg-ed-secondary transition disabled:opacity-60 disabled:cursor-not-allowed">
                <svg wire:loading wire:target="save" class="h-4 w-4 animate-spin" viewBox="0 0 24 24" fill="none">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor"
                          d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
                </svg>
                <span wire:loading.remove wire:target="save">Créer</span>
                <span wire:loading wire:target="save">Création…</span>
            </button>
        </div>
    </div>

    <form id="form-actualite" wire:submit="save" class="space-y-6">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">

            {{-- Colonne principale --}}
            <div class="lg:col-span-8 space-y-6">

                {{-- Titre --}}
                <section class="rounded-2xl bg-white border border-slate-100 p-5 md:p-6">
                    <div class="flex items-start justify-between gap-4">
                        <div>
                            <h2 class="text-sm font-semibold text-slate-900">Titre</h2>
                            <p class="mt-0.5 text-xs text-slate-500">Le titre apparaît dans les listes et en page détail.</p>
                        </div>
                        <span class="text-xs text-slate-400">Obligatoire</span>
                    </div>

                    <div class="mt-4">
                        <input type="text"
                               wire:model="titre"
                               class="w-full px-4 py-2.5 rounded-xl border border-slate-200
                                      focus:border-ed-primary focus:ring-ed-primary/20"
                               placeholder="Titre de l'actualité">
                        @error('titre')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </section>

                {{-- Contenu --}}
                <section class="rounded-2xl bg-white border border-slate-100 p-5 md:p-6">
                    <div class="flex items-start justify-between gap-4">
                        <div>
                            <h2 class="text-sm font-semibold text-slate-900">Contenu</h2>
                            <p class="mt-0.5 text-xs text-slate-500">Rédigez le texte riche via TinyMCE.</p>
                        </div>
                        <span class="text-xs text-slate-400">Obligatoire</span>
                    </div>

                    <div class="mt-4">
                        <div wire:ignore class="rounded-xl border border-slate-200 overflow-hidden">
                            <textarea id="tinymce-editor" class="w-full"></textarea>
                        </div>

                        @error('contenu')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </section>

                {{-- Galerie --}}
                <section class="rounded-2xl bg-white border border-slate-100 p-5 md:p-6">
                    <div class="flex items-center justify-between gap-4">
                        <div>
                            <h2 class="text-sm font-semibold text-slate-900">Galerie</h2>
                            <p class="mt-0.5 text-xs text-slate-500">Images supplémentaires affichées dans l’article.</p>
                        </div>

                        <button type="button"
                                wire:click="openMediaSelector('gallery')"
                                class="inline-flex items-center gap-2 rounded-xl px-3.5 py-2 text-sm font-semibold
                                       border border-slate-200 bg-white text-slate-800 hover:bg-slate-50 transition">
                            <svg class="h-4 w-4 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M12 4v16m8-8H4"/>
                            </svg>
                            Ajouter
                        </button>
                    </div>

                    <div class="mt-4">
                        @if(!empty($galerieImages))
                            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-3">
                                @foreach($galerieImagesData as $media)
                                    <div class="group relative overflow-hidden rounded-xl border border-slate-200 bg-slate-50">
                                        <img src="{{ $media->url }}" alt="" class="h-28 w-full object-cover">

                                        <button type="button"
                                                wire:click="removeGalerieImage({{ $media->id }})"
                                                class="absolute top-2 right-2 inline-flex items-center justify-center
                                                       h-8 w-8 rounded-xl bg-white/90 border border-slate-200
                                                       text-slate-700 hover:text-red-600 hover:border-red-200 transition
                                                       opacity-0 group-hover:opacity-100">
                                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                      d="M6 18L18 6M6 6l12 12"/>
                                            </svg>
                                        </button>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="rounded-xl border border-dashed border-slate-200 bg-slate-50 px-4 py-8 text-center">
                                <p class="text-sm font-medium text-slate-700">Aucune image dans la galerie</p>
                                <p class="mt-1 text-xs text-slate-500">Cliquez sur “Ajouter” pour sélectionner des images.</p>
                            </div>
                        @endif
                    </div>
                </section>
            </div>

            {{-- Sidebar --}}
            <aside class="lg:col-span-4 space-y-6">

                {{-- Paramètres --}}
                <section class="rounded-2xl bg-white border border-slate-100 p-5 md:p-6">
                    <h2 class="text-sm font-semibold text-slate-900">Paramètres</h2>
                    <p class="mt-0.5 text-xs text-slate-500">Publication, date et options.</p>

                    <div class="mt-5 space-y-4">

                        {{-- Catégorie --}}
                        <div>
                            <label class="block text-xs font-semibold text-slate-700">
                                Catégorie <span class="text-red-500">*</span>
                            </label>

                            <select wire:model="category_id"
                                    class="mt-2 w-full px-3 py-2.5 rounded-xl border border-slate-200
                                           focus:border-ed-primary focus:ring-ed-primary/20">
                                <option value="">— Sélectionner —</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->nom }}</option>
                                @endforeach
                            </select>

                            @error('category_id')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror

                            <a href="{{ route('admin.categories.index') }}" target="_blank"
                               class="mt-2 inline-flex items-center gap-2 text-xs font-semibold text-ed-primary hover:text-ed-secondary">
                                Gérer les catégories
                            </a>
                        </div>

                        {{-- Date --}}
                        <div>
                            <label class="block text-xs font-semibold text-slate-700">Date de publication</label>
                            <input type="date" wire:model="date_publication"
                                   class="mt-2 w-full px-3 py-2.5 rounded-xl border border-slate-200
                                          focus:border-ed-primary focus:ring-ed-primary/20">
                        </div>

                        {{-- Toggles --}}
                        <div class="space-y-3">
                            <label class="flex items-start gap-3 cursor-pointer">
                                <input type="checkbox" wire:model="visible"
                                       class="mt-0.5 h-5 w-5 rounded border-slate-300 text-ed-primary focus:ring-ed-primary/20">
                                <span class="text-sm">
                                    <span class="font-semibold text-slate-800">Publier immédiatement</span>
                                    <span class="block text-xs text-slate-500">Visible sur le site public.</span>
                                </span>
                            </label>

                            <label class="flex items-start gap-3 cursor-pointer">
                                <input type="checkbox" wire:model="est_important"
                                       class="mt-0.5 h-5 w-5 rounded border-slate-300 text-red-600 focus:ring-red-600/20">
                                <span class="text-sm">
                                    <span class="font-semibold text-slate-800">Actualité importante</span>
                                    <span class="block text-xs text-slate-500">Mise en avant (homepage).</span>
                                </span>
                            </label>

                            <label class="flex items-start gap-3 cursor-pointer">
                                <input type="checkbox" wire:model="notifier_abonnes"
                                       class="mt-0.5 h-5 w-5 rounded border-slate-300 text-ed-primary focus:ring-ed-primary/20">
                                <span class="text-sm">
                                    <span class="font-semibold text-slate-800">Notifier les abonnés</span>
                                    <span class="block text-xs text-slate-500">Email envoyé si l’actualité est visible.</span>
                                </span>
                            </label>

                            <div class="rounded-xl bg-slate-50 border border-slate-100 p-3 text-xs text-slate-600">
                                Astuce : active “Notifier” seulement si tu publies maintenant (ou si la date est atteinte).
                            </div>
                        </div>
                    </div>
                </section>

                {{-- Tags --}}
                <section class="rounded-2xl bg-white border border-slate-100 p-5 md:p-6">
                    <div class="flex items-center justify-between gap-4">
                        <div>
                            <h2 class="text-sm font-semibold text-slate-900">Tags</h2>
                            <p class="mt-0.5 text-xs text-slate-500">Optionnel, pour mieux filtrer.</p>
                        </div>
                        <a href="{{ route('admin.tags.index') }}" target="_blank"
                           class="text-xs font-semibold text-ed-primary hover:text-ed-secondary">
                            Gérer
                        </a>
                    </div>

                    <div class="mt-4 max-h-52 overflow-y-auto rounded-xl border border-slate-200 p-2">
                        @forelse($tags as $tag)
                            <label class="flex items-center gap-2 rounded-lg px-2.5 py-2 hover:bg-slate-50 cursor-pointer">
                                <input type="checkbox"
                                       wire:model="selectedTags"
                                       value="{{ $tag->id }}"
                                       class="rounded border-slate-300 text-ed-primary focus:ring-ed-primary/20">
                                <span class="text-sm text-slate-700">{{ $tag->nom }}</span>
                            </label>
                        @empty
                            <p class="py-6 text-center text-sm text-slate-500">Aucun tag disponible.</p>
                        @endforelse
                    </div>
                </section>

                {{-- Image principale --}}
                <section class="rounded-2xl bg-white border border-slate-100 p-5 md:p-6">
                    <div class="flex items-center justify-between gap-4">
                        <div>
                            <h2 class="text-sm font-semibold text-slate-900">Image principale</h2>
                            <p class="mt-0.5 text-xs text-slate-500">Vignette de l’article.</p>
                        </div>
                        @if(!$selectedImage)
                            <button type="button"
                                    wire:click="openMediaSelector('featured')"
                                    class="text-xs font-semibold text-ed-primary hover:text-ed-secondary">
                                Sélectionner
                            </button>
                        @endif
                    </div>

                    <div class="mt-4">
                        @if($selectedImage)
                            <div class="relative overflow-hidden rounded-xl border border-slate-200 bg-slate-50">
                                <img src="{{ $selectedImage->url }}" alt="" class="h-44 w-full object-cover">
                                <button type="button"
                                        wire:click="removeImage"
                                        class="absolute top-2 right-2 inline-flex items-center justify-center
                                               h-9 w-9 rounded-xl bg-white/90 border border-slate-200
                                               text-slate-700 hover:text-red-600 hover:border-red-200 transition">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                </button>
                            </div>
                        @else
                            <button type="button"
                                    wire:click="openMediaSelector('featured')"
                                    class="w-full rounded-xl border border-dashed border-slate-200 bg-slate-50 px-4 py-10
                                           text-sm font-semibold text-slate-700 hover:border-ed-primary/50 hover:text-ed-primary transition">
                                <svg class="mx-auto mb-2 h-6 w-6 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                Choisir une image
                            </button>
                        @endif
                    </div>
                </section>

                {{-- Actions (bas) --}}
                <section class="rounded-2xl bg-white border border-slate-100 p-5 md:p-6">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <button type="submit"
                                wire:loading.attr="disabled"
                                class="inline-flex items-center justify-center gap-2 rounded-xl px-4 py-2.5 text-sm font-semibold
                                       bg-ed-primary text-white hover:bg-ed-secondary transition disabled:opacity-60 disabled:cursor-not-allowed">
                            <svg wire:loading wire:target="save" class="h-4 w-4 animate-spin" viewBox="0 0 24 24" fill="none">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor"
                                      d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
                            </svg>
                            <span wire:loading.remove wire:target="save">Créer</span>
                            <span wire:loading wire:target="save">Création…</span>
                        </button>

                        <a href="{{ route('admin.actualites.index') }}"
                           class="inline-flex items-center justify-center rounded-xl px-4 py-2.5 text-sm font-semibold
                                  border border-slate-200 bg-white text-slate-800 hover:bg-slate-50 transition">
                            Annuler
                        </a>
                    </div>
                </section>

            </aside>
        </div>
    </form>

    {{-- Overlay loading --}}
    <div wire:loading wire:target="save" class="fixed inset-0 z-[9999]">
        <div class="absolute inset-0 bg-slate-900/30 backdrop-blur-[1px]"></div>
        <div class="relative min-h-screen flex items-center justify-center px-4">
            <div class="w-full max-w-sm rounded-2xl bg-white border border-slate-100 p-6">
                <div class="flex items-start gap-3">
                    <div class="h-10 w-10 rounded-2xl bg-ed-primary/10 flex items-center justify-center">
                        <div class="h-5 w-5 border-2 border-ed-primary border-t-transparent rounded-full animate-spin"></div>
                    </div>
                    <div class="flex-1">
                        <p class="text-sm font-semibold text-slate-900">Création en cours…</p>
                        <p class="mt-1 text-xs text-slate-500">Veuillez patienter, ne fermez pas cette page.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal sélection médias (tu peux laisser ton modal actuel)
         ici je garde ton modal inchangé comme tu l’as demandé --}}
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
                            <div wire:click="selectImage({{ $media->id }})" class="cursor-pointer group relative">
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
        document.addEventListener('livewire:navigated', function () { initTinyMCE(); });

        function initTinyMCE() {
            if (typeof tinymce === 'undefined') return;

            tinymce.remove();

            tinymce.init({
                selector: '#tinymce-editor',
                height: 520,
                menubar: false,
                plugins: [
                    'advlist', 'autolink', 'lists', 'link', 'image', 'charmap', 'preview',
                    'anchor', 'searchreplace', 'visualblocks', 'code', 'fullscreen',
                    'insertdatetime', 'media', 'table', 'help', 'wordcount'
                ],
                toolbar: 'undo redo | blocks | bold italic forecolor | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | removeformat | link image | code | help',
                content_style: 'body { font-family: Inter, system-ui, -apple-system, Segoe UI, Roboto, Arial; font-size: 14px; }',
                setup: function (editor) {
                    editor.on('init', function () { editor.setContent(@this.contenu); });
                    editor.on('change', function () { @this.set('contenu', editor.getContent()); });
                }
            });
        }

        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', initTinyMCE);
        } else {
            initTinyMCE();
        }
    </script>
    @endpush

</div>
