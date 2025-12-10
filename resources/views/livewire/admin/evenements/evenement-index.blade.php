<div class="p-6">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-semibold">Événements</h1>
        <div>
            <a href="{{ route('admin.evenements.create') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 text-white rounded-md shadow">
                Nouvel événement
            </a>
        </div>
    </div>

    @if(session()->has('success'))
        <div class="mb-4 p-3 bg-green-50 border-l-4 border-green-500 text-green-700 rounded">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white p-4 rounded-lg shadow-sm mb-4">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-3">
            <input wire:model.debounce.300ms="search" type="text" placeholder="Recherche par titre..." class="w-full px-3 py-2 border rounded" />

            <select wire:model="filterType" class="w-full px-3 py-2 border rounded">
                <option value="">Tous les types</option>
                <option value="soutenance">Soutenance</option>
                <option value="seminaire">Séminaire</option>
                <option value="conference">Conférence</option>
                <option value="atelier">Atelier</option>
                <option value="autre">Autre</option>
            </select>

            <select wire:model="filterPublie" class="w-full px-3 py-2 border rounded">
                <option value="">Tous</option>
                <option value="publie">Publié</option>
                <option value="non">Non publié</option>
            </select>

            <select wire:model="perPage" class="w-full px-3 py-2 border rounded">
                <option value="15">15 / page</option>
                <option value="30">30 / page</option>
                <option value="50">50 / page</option>
            </select>
        </div>
    </div>

    <div class="overflow-x-auto bg-white rounded shadow">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Titre</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Type</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Lieu</th>
                    <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase">Important</th>
                    <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase">Publié</th>
                    <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($evenements as $e)
                    <tr>
                        <td class="px-4 py-3">
                            <div class="font-semibold">{{ $e->titre }}</div>
                            <div class="text-xs text-gray-500">{{ $e->date_fr }}</div>
                        </td>
                        <td class="px-4 py-3">{{ $e->periode_aff }}</td>
                        <td class="px-4 py-3">
                            <span class="inline-flex px-2 py-1 rounded text-xs {{ $e->type_classe }}">{{ $e->type_texte }}</span>
                        </td>
                        <td class="px-4 py-3">{{ $e->lieu ?? '-' }}</td>
                        <td class="px-4 py-3 text-center">
                            @if($e->est_important)
                                <span class="text-sm font-semibold text-red-600">Oui</span>
                            @else
                                <span class="text-sm text-gray-500">Non</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-center">
                            @if($e->est_publie)
                                <span class="text-sm font-semibold text-green-600">Publié</span>
                            @else
                                <span class="text-sm text-gray-500">Brouillon</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('evenements.show', $e) }}" target="_blank" class="text-sm px-2 py-1 border rounded">Voir</a>
                                <a href="{{ route('admin.evenements.edit', $e) }}" class="text-sm px-2 py-1 bg-yellow-400 rounded">Modifier</a>
                                <button wire:click="deleteConfirm({{ $e->id }})" class="text-sm px-2 py-1 bg-red-500 text-white rounded">Supprimer</button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-4 py-6 text-center text-gray-500">Aucun événement trouvé.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        <div class="p-4">
            {{ $evenements->links() }}
        </div>
    </div>

    <script>
        window.addEventListener('confirm-delete', event => {
            if (confirm(event.detail.message)) {
                Livewire.emit('delete', event.detail.id);
            }
        });

        // Bridge listener for server deletion
        Livewire.on('deleted', () => {
            // optional hook
        });
    </script>
</div>
