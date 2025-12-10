<div class="p-6 max-w-3xl mx-auto">
    <h1 class="text-2xl font-semibold mb-4">Modifier l'événement</h1>

    <form wire:submit.prevent="submit" class="space-y-6 bg-white p-6 rounded-lg shadow">
        <div>
            <label class="block text-sm font-medium text-gray-700">Titre *</label>
            <input wire:model.defer="evenement.titre" type="text" class="mt-1 w-full px-3 py-2 border rounded" />
            @error('evenement.titre') <span class="text-sm text-red-600">{{ $message }}</span> @enderror
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700">Description</label>
            <textarea wire:model.defer="evenement.description" rows="4" class="mt-1 w-full px-3 py-2 border rounded"></textarea>
            @error('evenement.description') <span class="text-sm text-red-600">{{ $message }}</span> @enderror
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700">Date de début *</label>
                <input wire:model.defer="evenement.date_debut" type="date" class="mt-1 w-full px-3 py-2 border rounded" />
                @error('evenement.date_debut') <span class="text-sm text-red-600">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Heure de début</label>
                <input wire:model.defer="evenement.heure_debut" type="time" class="mt-1 w-full px-3 py-2 border rounded" />
                @error('evenement.heure_debut') <span class="text-sm text-red-600">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Lieu</label>
                <input wire:model.defer="evenement.lieu" type="text" class="mt-1 w-full px-3 py-2 border rounded" />
                @error('evenement.lieu') <span class="text-sm text-red-600">{{ $message }}</span> @enderror
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700">Date de fin</label>
                <input wire:model.defer="evenement.date_fin" type="date" class="mt-1 w-full px-3 py-2 border rounded" />
                @error('evenement.date_fin') <span class="text-sm text-red-600">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Heure de fin</label>
                <input wire:model.defer="evenement.heure_fin" type="time" class="mt-1 w-full px-3 py-2 border rounded" />
                @error('evenement.heure_fin') <span class="text-sm text-red-600">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Type</label>
                <select wire:model.defer="evenement.type" class="mt-1 w-full px-3 py-2 border rounded">
                    <option value="soutenance">Soutenance</option>
                    <option value="seminaire">Séminaire</option>
                    <option value="conference">Conférence</option>
                    <option value="atelier">Atelier</option>
                    <option value="autre">Autre</option>
                </select>
                @error('evenement.type') <span class="text-sm text-red-600">{{ $message }}</span> @enderror
            </div>
        </div>

        <div class="flex items-center gap-6">
            <label class="inline-flex items-center">
                <input type="checkbox" wire:model="evenement.est_important" class="form-checkbox h-5 w-5 text-red-600" />
                <span class="ml-2 text-sm">Important</span>
            </label>

            <label class="inline-flex items-center">
                <input type="checkbox" wire:model="evenement.est_publie" class="form-checkbox h-5 w-5 text-green-600" />
                <span class="ml-2 text-sm">Publié</span>
            </label>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700">Lien d'inscription</label>
            <input wire:model.defer="evenement.lien_inscription" type="url" class="mt-1 w-full px-3 py-2 border rounded" placeholder="https://..." />
            @error('evenement.lien_inscription') <span class="text-sm text-red-600">{{ $message }}</span> @enderror
        </div>

        <div class="flex items-center justify-end gap-3">
            <a href="{{ route('admin.evenements.index') }}" class="px-4 py-2 border rounded">Annuler</a>
            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded">Enregistrer</button>
        </div>
    </form>
</div>
