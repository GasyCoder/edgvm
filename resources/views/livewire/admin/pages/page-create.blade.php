<div class="p-6 lg:p-8">
    {{-- Breadcrumb --}}
    <nav class="mb-4">
        <ol class="flex items-center gap-2 text-sm text-gray-500">
            <li>
                <a href="{{ route('admin.dashboard') }}" class="hover:text-gray-800">
                    Dashboard
                </a>
            </li>
            <li class="text-gray-400">/</li>
            <li>
                <a href="{{ route('admin.pages.index') }}" class="hover:text-gray-800">
                    Pages
                </a>
            </li>
            <li class="text-gray-400">/</li>
            <li class="text-gray-900 font-semibold">
                Nouvelle page
            </li>
        </ol>
    </nav>

    {{-- Titre principal --}}
    <div class="mb-6 flex flex-col md:flex-row md:items-center md:justify-between gap-2">
        <div>
            <h1 class="text-2xl md:text-3xl font-bold text-gray-900">
                Créer une nouvelle page
            </h1>
            <p class="mt-1 text-sm text-gray-500">
                Configurez le titre, l’URL et le contenu de la page affichée sur le site EDGVM.
            </p>
        </div>

        <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-blue-50 text-xs text-blue-700 font-medium">
            <span class="w-1.5 h-1.5 rounded-full bg-blue-500"></span>
            Édition enrichie avec TinyMCE
        </div>
    </div>

    {{-- Formulaire --}}
    <form wire:submit.prevent="save" class="space-y-6">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 items-start">
            {{-- Colonne principale --}}
            <div class="lg:col-span-2 space-y-6">

                {{-- Carte Titre + Slug --}}
                <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-5 md:p-6 space-y-4">
                    {{-- Titre --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Titre de la page <span class="text-red-500">*</span>
                        </label>
                        <input
                            type="text"
                            wire:model.blur="titre"
                            class="w-full px-4 py-2.5 border text-sm md:text-base rounded-lg
                                   border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-transparent
                                   @error('titre') border-red-500 focus:ring-red-500 @enderror"
                            placeholder="Ex : À propos de l’École Doctorale EDGVM"
                        >
                        @error('titre')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Slug --}}
                    <div class="grid grid-cols-1 md:grid-cols-[1.2fr,0.8fr] gap-4 md:gap-6 items-end">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Slug (URL) <span class="text-red-500">*</span>
                            </label>
                            <input
                                type="text"
                                wire:model="slug"
                                class="w-full px-4 py-2.5 border text-sm md:text-base rounded-lg
                                       border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-transparent
                                       @error('slug') border-red-500 focus:ring-red-500 @enderror"
                                placeholder="ex : a-propos, organisation, conseil-scientifique…"
                            >
                            @error('slug')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                            <p class="mt-1 text-[11px] text-gray-500">
                                Utilisez uniquement des lettres minuscules, chiffres et tirets (-).
                            </p>
                        </div>

                        {{-- Aperçu de l’URL --}}
                        <div class="bg-gray-50 rounded-xl border border-dashed border-gray-200 px-3 py-2.5 text-xs md:text-sm text-gray-600">
                            <p class="font-medium text-gray-700 mb-1">
                                Aperçu de l’URL
                            </p>
                            @if($slug)
                                <p class="truncate">
                                    {{ route('pages.show', $slug) }}
                                </p>
                            @else
                                <p class="italic text-gray-400">
                                    L’URL apparaîtra ici une fois le slug renseigné.
                                </p>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Carte Contenu principal + TinyMCE --}}
                <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-5 md:p-6 space-y-3">
                    <div class="flex items-center justify-between gap-3">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">
                                Contenu principal
                            </label>
                            <p class="mt-1 text-xs text-gray-500">
                                Rédigez ici le contenu principal de la page (texte, listes, liens, tableaux…).
                            </p>
                        </div>
                        <span class="hidden md:inline-flex items-center gap-1 px-2.5 py-1 rounded-full bg-blue-50 text-[11px] font-medium text-blue-700">
                            TinyMCE actif
                        </span>
                    </div>

                    {{-- Wrapper ignoré par Livewire pour TinyMCE --}}
                    <div wire:ignore>
                        <textarea id="tinymce-page-editor" class="w-full"></textarea>
                    </div>

                    {{-- Champ réellement lié à Livewire --}}
                    <input type="hidden" wire:model="contenu">

                    @error('contenu')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror

                    <p class="mt-2 text-[11px] text-gray-400">
                        Vous pourrez ensuite enrichir la page avec des sections illustrées (images, blocs, etc.).
                    </p>
                </div>
            </div>

            {{-- Sidebar --}}
            <div class="space-y-5">

                {{-- Carte paramètres rapides --}}
                <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-5 space-y-4">
                    <h2 class="text-sm font-semibold text-gray-800 flex items-center gap-2">
                        <span class="w-1.5 h-5 rounded-full bg-gradient-to-b from-blue-600 to-indigo-600"></span>
                        Paramètres de la page
                    </h2>

                    {{-- Ordre --}}
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1.5">
                            Ordre d’affichage
                        </label>
                        <input
                            type="number"
                            min="0"
                            wire:model="ordre"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm
                                   focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        >
                        <p class="mt-1 text-[11px] text-gray-500">
                            Plus le nombre est petit, plus la page apparaît tôt dans les listes.
                        </p>
                        @error('ordre')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Visibilité --}}
                    <div class="pt-3 border-t border-gray-100">
                        <label class="block text-xs font-medium text-gray-700 mb-2">
                            Visibilité
                        </label>
                        <div class="flex items-center gap-3">
                            <button
                                type="button"
                                wire:click="$set('visible', !visible)"
                                class="relative inline-flex h-6 w-11 items-center rounded-full transition
                                       {{ $visible ? 'bg-blue-600' : 'bg-gray-300' }}"
                            >
                                <span class="inline-block h-4 w-4 transform rounded-full bg-white shadow
                                             transition {{ $visible ? 'translate-x-6' : 'translate-x-1' }}"></span>
                            </button>
                            <span class="text-sm text-gray-700">
                                {{ $visible ? 'Page visible sur le site public' : 'Page masquée' }}
                            </span>
                        </div>
                        <p class="mt-1 text-[11px] text-gray-500">
                            Vous pourrez modifier la visibilité à tout moment.
                        </p>
                    </div>

                    <hr class="my-6">

                    <div class="space-y-4">
                        <h3 class="text-sm font-semibold text-gray-800">
                            Menu de navigation
                        </h3>

                        {{-- Checkbox : attacher ou non au menu --}}
                        <label class="inline-flex items-center gap-2 text-sm text-gray-700">
                            <input
                                type="checkbox"
                                wire:model="attachToMenu"
                                class="rounded border-gray-300 text-ed-primary focus:ring-ed-primary"
                            >
                            <span>Ajouter cette page à un menu</span>
                        </label>

                        @if($attachToMenu)
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                {{-- Choix du menu --}}
                                <div>
                                    <label class="block text-xs font-semibold text-gray-600 mb-1">
                                        Menu
                                    </label>
                                    <select
                                        wire:model="menuId"
                                        class="w-full rounded-lg border-gray-300 text-sm focus:ring-ed-primary focus:border-ed-primary"
                                    >
                                        <option value="">— Sélectionner un menu —</option>
                                        @foreach($menus as $menu)
                                            <option value="{{ $menu->id }}">
                                                {{ $menu->nom }} ({{ $menu->slug }})
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('menuId')
                                        <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                {{-- Libellé dans le menu --}}
                                <div>
                                    <label class="block text-xs font-semibold text-gray-600 mb-1">
                                        Libellé du lien dans le menu
                                    </label>
                                    <input
                                        type="text"
                                        wire:model="menuLabel"
                                        class="w-full rounded-lg border-gray-300 text-sm focus:ring-ed-primary focus:border-ed-primary"
                                        placeholder="Par défaut : titre de la page"
                                    >
                                    <p class="text-[11px] text-gray-400 mt-1">
                                        Si laissé vide, le titre de la page sera utilisé.
                                    </p>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Carte Info auteur (optionnel si tu veux l’ajouter plus tard) --}}
                <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-5">
                    <h3 class="text-sm font-semibold text-gray-800 mb-2">
                        Conseils de rédaction
                    </h3>
                    <ul class="text-xs text-gray-600 space-y-1.5">
                        <li>• Utilisez des titres clairs (H2, H3) pour structurer la page.</li>
                        <li>• Privilégiez des phrases courtes et des paragraphes aérés.</li>
                        <li>• Intégrez des liens internes vers les autres pages EDGVM.</li>
                        <li>• Gardez un ton institutionnel et cohérent.</li>
                    </ul>
                </div>

                {{-- Actions --}}
                <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-5 space-y-3">
                    <button
                        type="submit"
                        wire:loading.attr="disabled"
                        class="w-full inline-flex items-center justify-center gap-2 px-4 py-2.5
                               bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold rounded-lg
                               shadow-sm transition disabled:opacity-60 disabled:cursor-not-allowed"
                    >
                        <span wire:loading.remove wire:target="save" class="inline-flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M5 13l4 4L19 7"/>
                            </svg>
                            Créer la page
                        </span>
                        <span wire:loading wire:target="save" class="inline-flex items-center gap-2">
                            <svg class="animate-spin h-4 w-4" viewBox="0 0 24 24" fill="none">
                                <circle class="opacity-25" cx="12" cy="12" r="10"
                                        stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor"
                                      d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
                            </svg>
                            Enregistrement...
                        </span>
                    </button>

                    <a href="{{ route('admin.pages.index') }}"
                       class="w-full inline-flex items-center justify-center px-4 py-2.5
                              border border-gray-300 rounded-lg text-sm text-gray-700
                              hover:bg-gray-50 transition">
                        Annuler et revenir à la liste
                    </a>
                </div>
            </div>
        </div>
    </form>

    {{-- Overlay de chargement global (optionnel) --}}
    <div wire:loading wire:target="save"
         class="fixed inset-0 z-[9999] flex items-center justify-center bg-black/40">
        <div class="bg-white rounded-2xl shadow-2xl px-6 py-5 max-w-sm w-full text-center">
            <div class="mx-auto mb-4 w-12 h-12 border-4 border-blue-500 border-t-transparent rounded-full animate-spin"></div>
            <h3 class="text-base font-semibold text-gray-900">
                Création de la page en cours...
            </h3>
            <p class="mt-2 text-xs text-gray-500">
                Merci de patienter quelques secondes, sans fermer cette fenêtre.
            </p>
        </div>
    </div>

    @push('scripts')
        <script>
            function initTinyMCEPage() {
                if (typeof tinymce === 'undefined') {
                    return;
                }

                // Supprime toute instance existante sur cet éditeur
                tinymce.remove('#tinymce-page-editor');

                tinymce.init({
                    selector: '#tinymce-page-editor',
                    height: 500,
                    menubar: false,
                    language: 'fr',
                    plugins: [
                        'advlist', 'autolink', 'lists', 'link', 'image', 'charmap', 'preview',
                        'anchor', 'searchreplace', 'visualblocks', 'code', 'fullscreen',
                        'insertdatetime', 'media', 'table', 'help', 'wordcount'
                    ],
                    toolbar: 'undo redo | blocks | bold italic underline | ' +
                             'alignleft aligncenter alignright alignjustify | ' +
                             'bullist numlist outdent indent | removeformat | ' +
                             'link table | code fullscreen',
                    content_style: 'body { font-family: system-ui, -apple-system, "Segoe UI", sans-serif; font-size: 14px; }',
                    setup: function (editor) {
                        editor.on('init', function () {
                            // Valeur initiale envoyée par Livewire
                            editor.setContent(@js($contenu));
                        });

                        editor.on('change keyup', function () {
                            // Sync vers la propriété Livewire
                            @this.set('contenu', editor.getContent());
                        });
                    }
                });
            }

            // Init au chargement du DOM
            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', initTinyMCEPage);
            } else {
                initTinyMCEPage();
            }

            // Re-init à chaque navigation Livewire v3
            document.addEventListener('livewire:navigated', function () {
                initTinyMCEPage();
            });
        </script>
    @endpush
</div>
