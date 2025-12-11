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
                {{ $page->titre }}
            </li>
        </ol>
    </nav>

    {{-- Titre + message succès --}}
    <div class="mb-6 flex flex-col md:flex-row md:items-center md:justify-between gap-2">
        <div>
            <h1 class="text-2xl md:text-3xl font-bold text-gray-900">
                Éditer la page
            </h1>
            <p class="mt-1 text-sm text-gray-500">
                Modifiez le titre, l’URL, le contenu et les sections de cette page.
            </p>
        </div>

        <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-blue-50 text-xs text-blue-700 font-medium">
            <span class="w-1.5 h-1.5 rounded-full bg-blue-500"></span>
            Édition enrichie avec TinyMCE
        </div>
    </div>

    @if (session()->has('success'))
        <div class="mb-6 p-4 bg-green-50 border border-green-200 text-green-800 rounded-xl text-sm flex items-center gap-2">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M9 12l2 2 4-4m5 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 items-start">
        {{-- Colonne principale --}}
        <div class="lg:col-span-2 space-y-6">

            {{-- Formulaire Titre / Slug / Contenu --}}
            <form wire:submit.prevent="save" class="space-y-6">
                {{-- Carte informations générales --}}
                <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-5 md:p-6 space-y-4">
                    <div class="flex items-center justify-between gap-2 mb-2">
                        <h2 class="text-sm font-semibold text-gray-800 flex items-center gap-2">
                            <span class="w-1.5 h-5 rounded-full bg-gradient-to-b from-blue-600 to-indigo-600"></span>
                            Informations générales
                        </h2>
                        <span class="text-[11px] text-gray-400">
                            ID : #{{ $page->id }}
                        </span>
                    </div>

                    {{-- Titre --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Titre de la page <span class="text-red-500">*</span>
                        </label>
                        <input
                            type="text"
                            wire:model="titre"
                            class="w-full px-4 py-2.5 border text-sm md:text-base rounded-lg
                                   border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-transparent
                                   @error('titre') border-red-500 focus:ring-red-500 @enderror"
                            placeholder="Ex : À propos de l’École Doctorale EDGVM"
                        >
                        @error('titre')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror>
                    </div>

                    {{-- Slug + preview URL --}}
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

                        <div class="bg-gray-50 rounded-xl border border-dashed border-gray-200 px-3 py-2.5 text-xs md:text-sm text-gray-600">
                            <p class="font-medium text-gray-700 mb-1">
                                URL publique
                            </p>
                            @if($slug)
                                <p class="truncate">
                                    {{ route('pages.show', $slug) }}
                                </p>
                            @else
                                <p class="italic text-gray-400">
                                    L’URL apparaîtra ici lorsque le slug sera renseigné.
                                </p>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Carte contenu principal avec TinyMCE --}}
                <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-5 md:p-6 space-y-3">
                    <div class="flex items-center justify-between gap-3">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">
                                Contenu principal
                            </label>
                            <p class="mt-1 text-xs text-gray-500">
                                Modifiez ici le contenu principal de la page (texte, listes, liens, tableaux…).
                            </p>
                        </div>
                        <span class="hidden md:inline-flex items-center gap-1 px-2.5 py-1 rounded-full bg-blue-50 text-[11px] font-medium text-blue-700">
                            TinyMCE actif
                        </span>
                    </div>

                    {{-- TinyMCE wrapper --}}
                    <div wire:ignore>
                        <textarea id="tinymce-page-editor-edit" class="w-full"></textarea>
                    </div>

                    {{-- Champ réellement lié à Livewire --}}
                    <input type="hidden" wire:model="contenu">

                    @error('contenu')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror

                    <div class="flex items-center justify-between mt-2">
                        <p class="text-[11px] text-gray-400">
                            Utilisez les titres (H2, H3) pour structurer le contenu de manière lisible.
                        </p>
                    </div>

                    {{-- Actions formulaire --}}
                    <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-100 mt-4">
                        <a href="{{ route('admin.pages.index') }}"
                           class="px-4 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm rounded-lg transition">
                            Annuler
                        </a>
                        <button
                            type="submit"
                            class="inline-flex items-center gap-2 px-4 py-2.5 bg-blue-600 hover:bg-blue-700
                                   text-white text-sm font-semibold rounded-lg shadow-sm transition">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M5 13l4 4L19 7"/>
                            </svg>
                            Enregistrer les modifications
                        </button>
                    </div>
                </div>
            </form>

            {{-- Carte Sections --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-5 md:p-6">
                <div class="flex items-center justify-between gap-3 mb-4">
                    <h2 class="text-sm font-semibold text-gray-800 flex items-center gap-2">
                        <span class="w-1.5 h-5 rounded-full bg-gradient-to-b from-indigo-600 to-blue-600"></span>
                        Sections de la page
                    </h2>
                    <span class="text-[11px] text-gray-500">
                        {{ count($sections) }} section(s)
                    </span>
                </div>

                {{-- Liste des sections --}}
                <div class="space-y-3 mb-6">
                    @forelse($sections as $section)
                        <div class="p-4 bg-gray-50/80 border border-gray-200 rounded-xl hover:bg-gray-50 transition">
                            <div class="flex items-start justify-between gap-4">
                                <div class="flex-1">
                                    <h3 class="font-semibold text-gray-900 flex items-center gap-2">
                                        <span class="inline-flex items-center justify-center w-6 h-6 text-xs font-bold rounded-full bg-indigo-100 text-indigo-700">
                                            {{ $section['ordre'] }}
                                        </span>
                                        {{ $section['titre'] ?: 'Section sans titre' }}
                                    </h3>
                                    <p class="text-sm text-gray-600 mt-1 line-clamp-2">
                                        {{ \Illuminate\Support\Str::limit($section['contenu'], 150) }}
                                    </p>
                                    <div class="flex flex-wrap items-center gap-4 mt-2 text-[11px] text-gray-500">
                                        <span>Ordre : {{ $section['ordre'] }}</span>
                                        @if($section['image_id'])
                                            <span class="flex items-center gap-1">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                          d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                                </svg>
                                                Image attachée
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                <div class="flex items-center gap-2">
                                    <button
                                        type="button"
                                        wire:click="editSection({{ $section['id'] }})"
                                        class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition"
                                        title="Modifier">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                  d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414
                                                     a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                    </button>
                                    <button
                                        type="button"
                                        wire:click="confirmDeleteSection({{ $section['id'] }})"
                                        class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition"
                                        title="Supprimer">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                  d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862
                                                     a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6
                                                     m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3
                                                     M4 7h16"/>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </div>
                    @empty
                        <p class="text-sm text-gray-500 text-center py-8">
                            Aucune section ajoutée pour le moment.
                        </p>
                    @endforelse
                </div>

                {{-- Ajouter une nouvelle section --}}
                <div class="border-t border-gray-100 pt-5">
                    <h3 class="font-medium text-gray-900 mb-3">
                        Ajouter une section
                    </h3>
                    <div class="space-y-4">
                        <input
                            type="text"
                            wire:model="newSection.titre"
                            placeholder="Titre de la section"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg text-sm
                                   focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        >

                        <textarea
                            wire:model="newSection.contenu"
                            rows="4"
                            placeholder="Contenu de la section..."
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg text-sm
                                   focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        ></textarea>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-2">
                                    Image (optionnel)
                                </label>
                                <select
                                    wire:model="newSection.image_id"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg text-sm
                                           focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                    <option value="">Aucune image</option>
                                    @foreach($this->medias as $media)
                                        <option value="{{ $media->id }}">{{ $media->titre }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-2">
                                    Ordre
                                </label>
                                <input
                                    type="number"
                                    wire:model="newSection.ordre"
                                    min="0"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg text-sm
                                           focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                >
                            </div>
                        </div>

                        <button
                            type="button"
                            wire:click="addSection"
                            class="w-full inline-flex items-center justify-center gap-2 px-4 py-2.5
                                   bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold
                                   rounded-lg shadow-sm transition">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M12 4v16m8-8H4"/>
                            </svg>
                            Ajouter la section
                        </button>
                    </div>
                </div>
            </div>
        </div>

        {{-- Sidebar --}}
        <div class="space-y-6">
            {{-- Paramètres --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-5">
                <h2 class="text-sm font-semibold text-gray-800 mb-4 flex items-center gap-2">
                    <span class="w-1.5 h-5 rounded-full bg-gradient-to-b from-blue-600 to-indigo-600"></span>
                    Paramètres de la page
                </h2>

                <div class="space-y-4">
                    {{-- Ordre --}}
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-2">
                            Ordre d’affichage
                        </label>
                        <input
                            type="number"
                            wire:model="ordre"
                            min="0"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm
                                   focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        >
                        @error('ordre')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-[11px] text-gray-500">
                            Plus le nombre est petit, plus la page apparaît tôt dans les listes.
                        </p>
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
                            Vous pouvez masquer temporairement la page si nécessaire.
                        </p>
                    </div>
                </div>
            </div>

            {{-- Menu de navigation --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-5">
                <h2 class="text-sm font-semibold text-gray-800 mb-4 flex items-center gap-2">
                    <span class="w-1.5 h-5 rounded-full bg-gradient-to-b from-emerald-500 to-teal-500"></span>
                    Menu de navigation
                </h2>

                <div class="space-y-4">
                    {{-- Checkbox : afficher ou non dans un menu --}}
                    <label class="inline-flex items-center gap-2 text-sm text-gray-700">
                        <input
                            type="checkbox"
                            wire:model="attachToMenu"
                            class="rounded border-gray-300 text-ed-primary focus:ring-ed-primary"
                        >
                        <span>Afficher cette page dans un menu</span>
                    </label>

                    @if($attachToMenu)
                        {{-- Choix du menu --}}
                        <div class="space-y-3">
                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-1">
                                    Menu
                                </label>
                                <select
                                    wire:model="menuId"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm
                                        focus:ring-ed-primary focus:border-ed-primary"
                                >
                                    <option value="">— Sélectionner un menu —</option>
                                    @foreach($menus as $menu)
                                        <option value="{{ $menu->id }}">
                                            {{ $menu->nom }} ({{ $menu->slug }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('menuId')
                                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Libellé dans le menu --}}
                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-1">
                                    Libellé dans le menu
                                </label>
                                <input
                                    type="text"
                                    wire:model="menuLabel"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm
                                        focus:ring-ed-primary focus:border-ed-primary"
                                    placeholder="Par défaut : titre de la page"
                                >
                                <p class="mt-1 text-[11px] text-gray-500">
                                    Si laissé vide, le titre de la page sera utilisé.
                                </p>
                            </div>

                            @if($menuItemId)
                                <p class="text-[11px] text-emerald-600 flex items-center gap-1">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                    Cette page est déjà liée à un élément de menu.
                                </p>
                            @endif
                        </div>
                    @endif
                </div>
            </div>


            {{-- Infos / aide rédaction --}}
            <div class="bg-blue-50 border border-blue-200 rounded-2xl p-5">
                <h3 class="text-sm font-semibold text-blue-900 mb-2">
                    Conseils de mise en forme
                </h3>
                <ul class="text-xs text-blue-800 space-y-1.5">
                    <li>• Utilisez des titres hiérarchisés (H2, H3) pour structurer le contenu.</li>
                    <li>• Évitez les très longs paragraphes, privilégiez l’aération.</li>
                    <li>• Ajoutez des liens internes vers d’autres pages EDGVM.</li>
                    <li>• Vérifiez l’orthographe avant publication.</li>
                </ul>
            </div>
        </div>
    </div>

    {{-- Modal d’édition de section --}}
    @if($editingSection)
        <div class="fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4"
             wire:click="$set('editingSection', null)">
            <div class="bg-white rounded-2xl max-w-2xl w-full max-h-[90vh] overflow-y-auto"
                 wire:click.stop>
                <div class="p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold text-gray-900">
                            Éditer la section
                        </h3>
                        <button
                            type="button"
                            wire:click="$set('editingSection', null)"
                            class="text-gray-400 hover:text-gray-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>

                    <div class="space-y-4">
                        <input
                            type="text"
                            wire:model="editingSection.titre"
                            placeholder="Titre de la section"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg text-sm
                                   focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        >

                        <textarea
                            wire:model="editingSection.contenu"
                            rows="6"
                            placeholder="Contenu de la section..."
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg text-sm
                                   focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        ></textarea>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-2">
                                    Image associée
                                </label>
                                <select
                                    wire:model="editingSection.image_id"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg text-sm
                                           focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                    <option value="">Aucune image</option>
                                    @foreach($this->medias as $media)
                                        <option value="{{ $media->id }}">{{ $media->titre }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-2">
                                    Ordre
                                </label>
                                <input
                                    type="number"
                                    wire:model="editingSection.ordre"
                                    min="0"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg text-sm
                                           focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                >
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-end gap-3 mt-6">
                        <button
                            type="button"
                            wire:click="$set('editingSection', null)"
                            class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg text-sm transition">
                            Annuler
                        </button>
                        <button
                            type="button"
                            wire:click="updateSection"
                            class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-sm transition">
                            Mettre à jour
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    {{-- Modal de confirmation suppression --}}
    @if($confirmingDeleteSection)
        <div class="fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4"
             wire:click="$set('confirmingDeleteSection', false)">
            <div class="bg-white rounded-2xl p-6 max-w-md w-full"
                 wire:click.stop>
                <h3 class="text-lg font-semibold text-gray-900 mb-3">
                    Confirmer la suppression
                </h3>
                <p class="text-sm text-gray-600 mb-6">
                    Êtes-vous sûr de vouloir supprimer cette section ? Cette action est irréversible.
                </p>
                <div class="flex justify-end gap-3">
                    <button
                        type="button"
                        wire:click="$set('confirmingDeleteSection', false)"
                        class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg text-sm transition">
                        Annuler
                    </button>
                    <button
                        type="button"
                        wire:click="deleteSection"
                        class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg text-sm transition">
                        Supprimer
                    </button>
                </div>
            </div>
        </div>
    @endif

    {{-- Overlay de chargement pour save --}}
    <div wire:loading wire:target="save"
         class="fixed inset-0 z-[9999] flex items-center justify-center bg-black/40">
        <div class="bg-white rounded-2xl shadow-2xl px-6 py-5 max-w-sm w-full text-center">
            <div class="mx-auto mb-4 w-12 h-12 border-4 border-blue-500 border-t-transparent rounded-full animate-spin"></div>
            <h3 class="text-base font-semibold text-gray-900">
                Enregistrement en cours...
            </h3>
            <p class="mt-2 text-xs text-gray-500">
                Merci de patienter, ne fermez pas cette page.
            </p>
        </div>
    </div>

    @push('scripts')
        <script>
            function initTinyMCEPageEdit() {
                if (typeof tinymce === 'undefined') {
                    return;
                }

                // Nettoyer l’ancienne instance si elle existe
                tinymce.remove('#tinymce-page-editor-edit');

                tinymce.init({
                    selector: '#tinymce-page-editor-edit',
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
                            // Charger le contenu Livewire actuel
                            editor.setContent(@js($contenu));
                        });

                        editor.on('change keyup', function () {
                            // Sync avec la propriété Livewire
                            @this.set('contenu', editor.getContent());
                        });
                    }
                });
            }

            // Init au chargement du DOM
            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', initTinyMCEPageEdit);
            } else {
                initTinyMCEPageEdit();
            }

            // Re-init après navigation Livewire (v3)
            document.addEventListener('livewire:navigated', function () {
                initTinyMCEPageEdit();
            });
        </script>
    @endpush
</div>
