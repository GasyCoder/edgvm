<div class="max-w-3xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Modifier le partenaire</h1>
            <p class="text-sm text-gray-500 mt-1">
                Mettez à jour les informations de ce partenaire.
            </p>
        </div>

        <a href="{{ route('admin.partenaires.index') }}"
           class="text-sm text-gray-600 hover:text-gray-900">
            Retour à la liste
        </a>
    </div>

    <form wire:submit.prevent="save" class="space-y-6">
        {{-- Infos de base --}}
        <div class="bg-white border border-gray-200 rounded-xl p-5 space-y-4">
            <div>
                <label class="block text-xs font-medium text-gray-700 mb-1">
                    Nom du partenaire *
                </label>
                <input type="text" wire:model.defer="nom"
                       class="w-full rounded-lg border-gray-300 text-sm focus:border-ed-primary focus:ring-ed-primary">
                @error('nom') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-xs font-medium text-gray-700 mb-1">
                    Description
                </label>
                <textarea wire:model.defer="description" rows="3"
                          class="w-full rounded-lg border-gray-300 text-sm focus:border-ed-primary focus:ring-ed-primary"></textarea>
                @error('description') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-xs font-medium text-gray-700 mb-1">
                    URL du partenaire
                </label>
                <input type="url" wire:model.defer="url"
                       class="w-full rounded-lg border-gray-300 text-sm focus:border-ed-primary focus:ring-ed-primary">
                @error('url') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>
        </div>

        {{-- Logo & affichage --}}
        <div class="bg-white border border-gray-200 rounded-xl p-5 space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 items-start">
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">
                        Logo du partenaire
                    </label>
                    <input type="file" wire:model="logo" accept="image/*"
                           class="w-full text-sm text-gray-700">
                    @error('logo') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    <p class="mt-1 text-xs text-gray-500">
                        Si vous choisissez un nouveau fichier, l’ancien logo sera remplacé.
                    </p>
                </div>

                <div class="flex flex-col items-center gap-2">
                    <span class="text-xs font-medium text-gray-700">Logo actuel / prévisualisation</span>

                    @if ($logo)
                        <img src="{{ $logo->temporaryUrl() }}"
                             class="w-40 h-40 rounded-lg object-contain border bg-white">
                    @elseif($partenaire->logo_url)
                        <img src="{{ $partenaire->logo_url }}"
                             class="w-40 h-40 rounded-lg object-contain border bg-white">
                    @else
                        <div class="w-40 h-40 rounded-lg border border-dashed border-gray-300 flex items-center justify-center text-xs text-gray-400">
                            Aucun logo
                        </div>
                    @endif
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">
                        Ordre d’affichage
                    </label>
                    <input type="number" min="1" wire:model.defer="ordre"
                           class="w-full rounded-lg border-gray-300 text-sm focus:border-ed-primary focus:ring-ed-primary">
                    @error('ordre') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>

                <div class="flex items-center mt-6">
                    <input type="checkbox" wire:model.defer="visible"
                           class="rounded border-gray-300 text-ed-primary focus:ring-ed-primary">
                    <span class="ml-2 text-sm text-gray-700">
                        Afficher ce partenaire sur le site
                    </span>
                </div>
            </div>
        </div>

        {{-- Boutons --}}
        <div class="flex items-center justify-end gap-3">
            <a href="{{ route('admin.partenaires.index') }}"
               class="px-4 py-2 rounded-lg border border-gray-200 text-sm text-gray-700 hover:bg-gray-50">
                Annuler
            </a>
            <button type="submit"
                    class="px-5 py-2.5 rounded-lg bg-ed-primary text-white text-sm font-semibold hover:bg-ed-secondary">
                Enregistrer
            </button>
        </div>
    </form>
</div>
