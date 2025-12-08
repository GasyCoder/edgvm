<div class="max-w-4xl mx-auto space-y-6">
    {{-- Flash message --}}
    @if (session('success'))
        <div class="rounded-md bg-green-50 border border-green-200 px-4 py-3 text-sm text-green-800">
            {{ session('success') }}
        </div>
    @endif

    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">
                Mot de la Directrice
            </h1>
            <p class="text-sm text-gray-500 mt-1">
                Ce contenu est affiché sur la page d’accueil (section “Mot de la Directrice”).
            </p>
        </div>
    </div>

    <form wire:submit.prevent="save" class="space-y-6">
        {{-- Identité --}}
        <div class="bg-white border border-gray-200 rounded-xl p-5 space-y-4">
            <h2 class="text-sm font-semibold text-gray-800 uppercase tracking-wide">Identité</h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Nom complet *</label>
                    <input type="text" wire:model.defer="nom"
                           class="w-full rounded-lg border-gray-300 text-sm focus:border-ed-primary focus:ring-ed-primary">
                    @error('nom') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Fonction</label>
                    <input type="text" wire:model.defer="fonction"
                           class="w-full rounded-lg border-gray-300 text-sm focus:border-ed-primary focus:ring-ed-primary">
                    @error('fonction') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Institution</label>
                    <input type="text" wire:model.defer="institution"
                           class="w-full rounded-lg border-gray-300 text-sm focus:border-ed-primary focus:ring-ed-primary">
                    @error('institution') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>
            </div>
        </div>

        {{-- Contact & photo --}}
        <div class="bg-white border border-gray-200 rounded-xl p-5 space-y-4">
            <h2 class="text-sm font-semibold text-gray-800 uppercase tracking-wide">Contact & visuel</h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Téléphone</label>
                    <input type="text" wire:model.defer="telephone"
                           class="w-full rounded-lg border-gray-300 text-sm focus:border-ed-primary focus:ring-ed-primary">
                    @error('telephone') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Email</label>
                    <input type="email" wire:model.defer="email"
                           class="w-full rounded-lg border-gray-300 text-sm focus:border-ed-primary focus:ring-ed-primary">
                    @error('email') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 items-start">
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-2">Photo</label>
                    <input type="file" wire:model="photo" accept="image/*"
                           class="w-full text-sm text-gray-700">

                    @error('photo') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror

                    <p class="mt-1 text-xs text-gray-500">Format recommandé : 800x550, max 2 Mo.</p>
                </div>

                <div class="flex flex-col items-center gap-2">
                    <span class="text-xs font-medium text-gray-700">Prévisualisation</span>

                    @if ($photo)
                        <img src="{{ $photo->temporaryUrl() }}" class="w-40 h-40 rounded-xl object-cover border">
                    @elseif($messageDirection && $messageDirection->photo_path)
                        <img src="{{ asset('storage/'.$messageDirection->photo_path) }}"
                             class="w-40 h-40 rounded-xl object-cover border">
                    @else
                        <div class="w-40 h-40 rounded-xl border border-dashed border-gray-300 flex items-center justify-center text-xs text-gray-400">
                            Aucune photo
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Texte affiché sur la home --}}
        <div class="bg-white border border-gray-200 rounded-xl p-5 space-y-4">
            <h2 class="text-sm font-semibold text-gray-800 uppercase tracking-wide">Contenu du message</h2>

            <div>
                <label class="block text-xs font-medium text-gray-700 mb-1">Citation (phrase en italique)</label>
                <textarea wire:model.defer="citation" rows="3"
                          class="w-full rounded-lg border-gray-300 text-sm focus:border-ed-primary focus:ring-ed-primary"></textarea>
                @error('citation') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-xs font-medium text-gray-700 mb-1">Texte principal (introduction)</label>
                <textarea wire:model.defer="message_intro" rows="4"
                          class="w-full rounded-lg border-gray-300 text-sm focus:border-ed-primary focus:ring-ed-primary"></textarea>
                @error('message_intro') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>
        </div>

        {{-- Chiffres clés --}}
        <div class="bg-white border border-gray-200 rounded-xl p-5 space-y-4">
            <h2 class="text-sm font-semibold text-gray-800 uppercase tracking-wide">Chiffres clés</h2>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Nombre de doctorants</label>
                    <input type="number" min="0" wire:model.defer="nb_doctorants"
                           class="w-full rounded-lg border-gray-300 text-sm focus:border-ed-primary focus:ring-ed-primary">
                    @error('nb_doctorants') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Nombre d'équipes</label>
                    <input type="number" min="0" wire:model.defer="nb_equipes"
                           class="w-full rounded-lg border-gray-300 text-sm focus:border-ed-primary focus:ring-ed-primary">
                    @error('nb_equipes') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Nombre de thèses</label>
                    <input type="number" min="0" wire:model.defer="nb_theses"
                           class="w-full rounded-lg border-gray-300 text-sm focus:border-ed-primary focus:ring-ed-primary">
                    @error('nb_theses') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>
            </div>
        </div>

        {{-- Visibilité + bouton submit --}}
        <div class="flex items-center justify-between">
            <label class="inline-flex items-center gap-2">
                <input type="checkbox" wire:model.defer="visible"
                       class="rounded border-gray-300 text-ed-primary focus:ring-ed-primary">
                <span class="text-sm text-gray-700">Afficher ce message sur la page d’accueil</span>
            </label>

            <button type="submit"
                    class="inline-flex items-center px-5 py-2.5 rounded-lg bg-ed-primary text-white text-sm font-semibold hover:bg-ed-primary/90">
                Enregistrer
            </button>
        </div>
    </form>
</div>
