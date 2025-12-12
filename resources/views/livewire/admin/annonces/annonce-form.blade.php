<div class="space-y-6">
    <div class="flex items-start justify-between gap-4">
        <div>
            <h1 class="text-xl font-bold text-gray-900">
                {{ $annonce ? 'Modifier une annonce' : 'Nouvelle annonce' }}
            </h1>
            <p class="text-sm text-gray-500">Annonce académique (contenu riche + fichier + ciblage + email).</p>
        </div>

        <a href="{{ route('admin.annonces.index') }}"
           class="px-4 py-2 rounded-xl border border-gray-200 hover:border-gray-300 text-sm font-semibold text-gray-700">
            Retour
        </a>
    </div>

    @if (session('success'))
        <div class="rounded-xl bg-emerald-50 text-emerald-800 px-4 py-3 text-sm">{{ session('success') }}</div>
    @endif

    <form wire:submit.prevent="save" class="bg-white rounded-2xl border border-gray-100 p-5 md:p-6 space-y-5">
        <div>
            <label class="block text-sm font-semibold text-gray-900 mb-1">Titre</label>
            <input type="text" wire:model.defer="titre"
                   class="w-full rounded-xl border-gray-200 focus:border-ed-primary focus:ring-ed-primary/20">
            @error('titre') <div class="text-xs text-red-600 mt-1">{{ $message }}</div> @enderror
        </div>

        <div>
            <label class="block text-sm font-semibold text-gray-900 mb-1">Contenu (texte riche)</label>

            {{-- TinyMCE (ou autre éditeur) --}}
            <div wire:ignore class="rounded-xl border border-gray-200 overflow-hidden">
                <textarea id="annonce_contenu" class="w-full">
{{ old('contenu_html', $contenu_html) }}
                </textarea>
            </div>

            @error('contenu_html') <div class="text-xs text-red-600 mt-1">{{ $message }}</div> @enderror

            <script>
                document.addEventListener('livewire:init', () => {
                    const initEditor = () => {
                        if (window.tinymce) {
                            const existing = tinymce.get('annonce_contenu');
                            if (existing) existing.remove();

                            tinymce.init({
                                selector: '#annonce_contenu',
                                height: 380,
                                menubar: false,
                                plugins: 'link lists table code',
                                toolbar: 'undo redo | blocks | bold italic underline | bullist numlist | link table | removeformat | code',
                                setup: (editor) => {
                                    editor.on('init', () => {
                                        editor.setContent(@js($contenu_html));
                                    });
                                    editor.on('change keyup', () => {
                                        @this.set('contenu_html', editor.getContent());
                                    });
                                }
                            });
                        }
                    };

                    initEditor();
                    Livewire.on('annonce-editor-refresh', () => initEditor());
                });
            </script>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label class="block text-sm font-semibold text-gray-900 mb-1">Cible (affichage)</label>
                <select wire:model.defer="cible"
                        class="w-full rounded-xl border-gray-200 focus:border-ed-primary focus:ring-ed-primary/20">
                    <option value="doctorant">Doctorant</option>
                    <option value="encadrant">Encadrant</option>
                    <option value="both">Doctorant / Encadrant</option>
                </select>
                @error('cible') <div class="text-xs text-red-600 mt-1">{{ $message }}</div> @enderror
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-900 mb-1">Fichier externe (Media)</label>
                <select wire:model.defer="media_id"
                        class="w-full rounded-xl border-gray-200 focus:border-ed-primary focus:ring-ed-primary/20">
                    <option value="">— Aucun —</option>
                    @foreach($medias as $m)
                        <option value="{{ $m->id }}">{{ $m->nom_original }}</option>
                    @endforeach
                </select>
                @error('media_id') <div class="text-xs text-red-600 mt-1">{{ $message }}</div> @enderror
                <div class="mt-1 text-xs text-gray-500">
                    Astuce: ajoute un fichier dans <a class="text-ed-primary font-semibold hover:underline" href="{{ route('admin.media.index') }}">Médiathèque</a>, puis reviens ici.
                </div>
            </div>

            <div class="flex items-center gap-3 pt-6">
                <input id="est_publie" type="checkbox" wire:model.defer="est_publie"
                       class="rounded border-gray-300 text-ed-primary focus:ring-ed-primary/20">
                <label for="est_publie" class="text-sm font-semibold text-gray-900">
                    Publier maintenant
                </label>
            </div>
        </div>

        <div class="rounded-2xl bg-gray-50 p-4 md:p-5 space-y-3">
            <div class="flex items-center gap-3">
                <input id="envoyer_email" type="checkbox" wire:model.live="envoyer_email"
                       class="rounded border-gray-300 text-ed-primary focus:ring-ed-primary/20">
                <label for="envoyer_email" class="text-sm font-semibold text-gray-900">
                    Envoyer par email
                </label>
            </div>

            @if($envoyer_email)
                <div class="max-w-sm">
                    <label class="block text-sm font-semibold text-gray-900 mb-1">Email cible</label>
                    <select wire:model.defer="email_cible"
                            class="w-full rounded-xl border-gray-200 focus:border-ed-primary focus:ring-ed-primary/20">
                        <option value="doctorant">Doctorant</option>
                        <option value="encadrant">Encadrant</option>
                        <option value="both">Doctorant / Encadrant</option>
                    </select>
                    @error('email_cible') <div class="text-xs text-red-600 mt-1">{{ $message }}</div> @enderror
                </div>
            @endif
        </div>

        <div class="flex items-center justify-end gap-3 pt-2">
            <a href="{{ route('admin.annonces.index') }}"
               class="px-5 py-2.5 rounded-xl border border-gray-200 text-sm font-semibold text-gray-700 hover:border-gray-300">
                Annuler
            </a>

            <button type="submit"
                    class="px-5 py-2.5 rounded-xl bg-ed-primary text-white text-sm font-semibold hover:bg-ed-secondary transition">
                Enregistrer
            </button>
        </div>
    </form>
</div>